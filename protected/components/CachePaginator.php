<?php

/**
 * Simple helper for paginating results from any source, where the results
 * should be cached. Calculates resulting array.
 */
class CachePaginator extends CComponent
{
  /** @var string Primary cache key. */
  public $id;
  /** @var int Expire time for items, in seconds. The main data array is set to expire in 2 times this value. */
  public $expire = 900;
  /** @var int Default page size when not manually specified. */
  public $pageSize = 10;
  /** @var string Log category to write to. */
  public $logCategory = null;
  /** @var array Log entries that are already written to log if {@see $logCategory} is specified. */
  public $logEntries = [];

  /**
   * @var callable
   * Callback function which is called when new items are required, or previous
   * items must be refreshed. It should take page number and page size as
   * parameters (1st page number, 2nd page size), and return an array of items
   * equal to page size. The callback should return false in case of error.
   */
  public $callback;

  /**
   * Construct CachePaginator.
   *
   * @param string $id
   * Primary cache key.
   * @param bool $cross_domain
   * If true, user domain is not appended to the cache key, making the results
   * shared among all domains (but not all applications).
   */
  public function __construct($id = null, $cross_domain = false)
  {
    if (is_string($id))
      $this->setId($id, $cross_domain);
  }

  /**
   * Set the primary cache key for the paginator.
   *
   * @param string $id
   * Primary cache key.
   * @param bool $cross_domain
   * If true, user domain is not appended to the cache key, making the results
   * shared among all domains (but not all applications).
   */
  public function setId(string $id, $cross_domain = false)
  {
    if (empty($id))
      throw new \InvalidArgumentException("Empty ID for cache key.");
    $this->id = sprintf("%s_%s", $id, $cross_domain ? '_shared' : Yii::app()->user->domain);
  }

  /**
   * Get the specified page.
   *
   * This function assumes that the component is configured properly, and
   * callback is specified.
   *
   * @param int $page
   * Page number.
   * @param bool $force_refresh
   * If true, data is refreshed even if it hasn't expired.
   * @param int $page_size
   * Page size. If <= 0, {@see $this->pageSize} is used.
   * @param string $key_suffix
   * Appended to cache key if specified, so that the same paginator can be used
   * for multiple listings.
   * @return mixed
   * Resulting items array. Returns false if an empty page is requested, or if
   * an error occurs when calling the specified callback function.
   */
  public function getPage(int $page, bool $force_refresh = false, int $page_size = 0, string $key_suffix = null)
  {
    $page = max(1, $page);
    $page_size = ($page_size > 0) ? $page_size : $this->pageSize;
    $cache_key = $this->id . (is_string($key_suffix) ? "_$key_suffix" : '');
    $old_data_on_page = false;
    $first_index = ($page - 1) * $page_size;
    $index_range = range($first_index, $first_index + $page_size - 1);
    $requested = [];
    $data = Yii::app()->cache->get($cache_key) ?: [];

    if (!isset($data['items']) || !is_array($data['items'])) $data['items'] = [];
    if (!isset($data['eod']) || !is_int($data['eod'])) $data['eod'] = 0;
    if (!isset($data['times']) || !is_array($data['times'])) $data['times'] = [];

    foreach ($index_range as $index) {
      if (isset($data['items'][$index]))
        $requested[$index] = $data['items'][$index];
    }

    if (!$force_refresh && $data['eod'] != 0 && $data['eod'] < $first_index + count($requested)) {
      $this->log('info', $key_suffix, 'Empty page requested: %d', $page);
      // return false;
      return [];
    }

    foreach ($index_range as $index) {
      $update_time = $data['times'][$index] ?? 0;
      if (!isset($data['items'][$index]) || time() - $update_time > $this->expire) {
        if ($index != $data['eod'])
          $old_data_on_page = true;
        break;
      }
    }

    // If not enough items from array_splice, either this data has not yet been
    // fetched, or end has been reached.
    $is_eod = ($data['eod'] != 0 && $data['eod'] == $first_index + count($requested));
    if ($force_refresh || $old_data_on_page || (count($requested) != $page_size && !$is_eod)) {

      // Data will be refreshed; reset result array.
      $requested = [];

      try {
        // Data needs to be refreshed. Call the specified callback function.
        $requested_raw = $this->callback($page, $page_size);
      } catch (\Exception $ex) {
        $this->log('info', $key_suffix, 'Error while refreshing page %d with page size %d: %s', $page, $page_size, $ex->getMessage());
        // return false;
        return [];
      }

      if (false === $requested_raw) {
        // Error occured in the callback function.
        $this->log('info', $key_suffix, 'Callback function returned false (error).');
        // return false;
        return [];
      } elseif (!is_array($requested_raw)) {
        // Callback function must return an array (unless an error occured).
        $this->log('info', $key_suffix, 'Callback function return value is not an array. Returned type: %s', gettype($requested_raw));
        // return false;
        return [];
      } else {
        // Set indexes for requested items.
        $requested_raw = array_values($requested_raw);
        foreach ($index_range as $zero_index => $target_index) {
          if (isset($requested_raw[$zero_index]))
            $requested[$target_index] = $requested_raw[$zero_index];
        }
      }

      // Check if end of data, so that repeat requests are not made.
      if (empty($requested)) {
        $this->log('info', $key_suffix, 'Empty page requested: %d', $page);
        // return false;
        return [];
      } elseif (count($requested) != $page_size) {
        $this->log('info', $key_suffix, 'Page %d refreshed with %d items, end of data reached.', $page, count($requested));
        $data['items'] = array_replace($data['items'], $requested);
        ksort($data['items']);
        $data['eod'] = $first_index + count($requested);
        foreach ($index_range as $index)
          $data['times'][$index] = time();
        Yii::app()->cache->set($cache_key, $data, $this->expire * 2);
      } else {
        $this->log('info', $key_suffix, 'Page %d refreshed with %d items.', $page, count($requested));
        $data['items'] = array_replace($data['items'], $requested);
        ksort($data['items']);
        foreach ($index_range as $index)
          $data['times'][$index] = time();
        Yii::app()->cache->set($cache_key, $data, $this->expire * 2);
      }
    } else {
      // $this->log('info', $key_suffix, 'Page %d loaded from cache (%d items%s).', $page, count($requested), $is_eod ? '; end of data' : '');
    }

    return $requested;
  }

  /**
   * Filter pagination results. In order to achieve this, data for all pages
   * starting from page 1 must be fetched until the requested data is acquired.
   *
   * This function will loop each item on each page from the first page onward,
   * and picking items that pass the provided callback function (return TRUE).
   *
   * @param int $page
   * Page number.
   * @param callable $selector
   * Selector callback, which takes one parameter (the item) and returns true if
   * the item should be included in the result set; otherwise, false.
   * @param bool $force_refresh
   * If true, data is refreshed even if it hasn't expired.
   * @param int $page_size
   * Page size. If <= 0, {@see $this->pageSize} is used.
   * @param string $key_suffix
   * Appended to cache key if specified, so that the same paginator can be used
   * for multiple listings.
   * @param int $limit
   * If > 0, limit results to that many items.
   */
  public function filtered(int $page, callable $selector, int $page_size = 0, string $key_suffix = null, int $limit = 0)
  {
    $page = max(1, $page);
    $page_size = ($page_size > 0) ? $page_size : $this->pageSize;
    $cache_key = $this->id . (is_string($key_suffix) ? "_$key_suffix" : '');
    $first_index = ($page - 1) * $page_size;
    $index_range = range($first_index, $first_index + $page_size - 1);
    $approved = [];
    $requested = [];
    $data = Yii::app()->cache->get($cache_key) ?: [];

    if (!isset($data['items']) || !is_array($data['items'])) $data['items'] = [];
    if (!isset($data['eod']) || !is_int($data['eod'])) $data['eod'] = 0;
    if (!isset($data['times']) || !is_array($data['times'])) $data['times'] = [];

    $requested_page_reached = ($page == 1);
    $current_index = 0;
    while (count($requested) < $page_size) {

      if (!isset($data['items'][$current_index])) {
        $data['items'] = array_replace($data['items'], $this->getPage((int) floor($current_index / $page_size) + 1, false, $page_size, $key_suffix));
      }

      if (!isset($data['items'][$current_index])) {
        break;
      }

      if ($selector($data['items'][$current_index])) {
        if ($requested_page_reached) {
          $requested[$index_range[count($requested)]] = $data['items'][$current_index];
        } else {
          $approved[] = $data['items'][$current_index];
          if (count($approved) > ($page - 1) * $page_size)
            $requested_page_reached = true;
        }
      }

      $current_index++;
      if ($limit > 0 && count($requested) >= $limit)
        break;
    }

    return $requested;
  }

  /**
   * Deletes the cached data associated with this key.
   *
   * @param string $key_suffix
   * Appended to cache key if specified, so that the same paginator can be used
   * for multiple listings.
   */
  public function delete($key_suffix = null)
  {
    Yii::app()->cache->delete($this->id . (is_string($key_suffix) ? "_$key_suffix" : ''));
    $this->log('info', $key_suffix, 'Cached data was manually cleared.');
  }

  /**
   * Add a log entry. Writes to log file if {@see $logCategory} is specified.
   *
   * @param string $level
   * Log level (e.g. info, error, ...).
   * @param string $key_suffix
   * Appended to cache key if specified, so that the same paginator can be used
   * for multiple listings.
   * @param string $format
   * Format for {@see sprintf()}.
   * @param mixed ...$args
   * Optional arguments for {@see sprintf()}.
   */
  private function log(string $level, $key_suffix, string $format, ...$args)
  {
    if (empty($format))
      return;

    $format = sprintf(
      '(CachePaginator ID %s): %s (from uid %d@%s)',
      $this->id . (is_string($key_suffix) ? "_$key_suffix" : ''),
      $format,
      Yii::app()->user->getId(),
      Yii::app()->user->domain
    );

    array_unshift($args, $format);
    $message = call_user_func_array('sprintf', $args);
    $this->logEntries[] = ['time' => time(), 'message' => $message];

    if (!empty($this->logCategory))
      Yii::getLogger()->log($message, $level, $this->logCategory);
  }
}
