<?php

use CCache;

class Omasiistijat extends CApplicationComponent
{
  // Declare static variables. Use static for local cache, in order to try
  // prevent repeated read/write operations; one per request.

  /** @var bool $kp Check and limit active environment. */
  private $kp = (Yii::app()->user->kp);

  /** @var CCache|null $cc Primary cache controller. */
  private $cc;

  /** @var string|false $cname */
  private $cname;

  /** @var int $cid Cache ID for the main data array. */
  private $cid = $this->cachekey('omasiistijat');

  /** @var array|null $cdata Items, each with created "time" and "ids" array. */
  private $cdata = [];

  /** @var bool Flag to stop operations on error. */
  private $abort = false;

  /**
   * Get all entries from cache so far. Empty until {@see list()} called.
   */
  // public function getall()
  // {
  //   if (!is_array($this->cdata))
  //     $this->cdata = [];
  //   return $this->cdata;
  // }

  /**
   * Get list of workers that have approved shifts/cycles in a target location.
   *
   * @param int $id
   * ID of the target location (model: Kohteet).
   *
   * @return array
   * Array of active records with attr: "id", "tekijan_nimi" and "sukunimi"
   */
  public function list(int $id)
  { 
    
    // Abort flag, used to prevent repeated requests after failed cache load.
    // Default to user->kp, to mark upcoming requests as abort straight away.
    // Return if the function was already aborted before in this request.
    static $abort = !$this->kp; // default
    if (!$this->kp || (isset($abort) && $abort))
      return false;

    // Check if cache object not initialized, then this is the first pass.
    if (!isset($this->cc)) {

      // Get active cache object and class name.
      $this->cdata = [];
      $cc = $this->classobj($id);
      $this->cname = get_class($this->cc);

      // Check that cache provider is available to use.
      if ($this->cname === false) {

        // Cache not enabled, or an error happened.
        $this->logf(4, 'cache', "Omasiistijat (ID %d): Cache component unavailable - aborting", $id);
        $abort = true;

      } elseif ($this->cname === 'CDummyCache') {

        // Allow CDummyCache in development environment.
        $this->tracef('cache', "Omasiistijat (ID %d): CDummyCache detected - aborting (unless dev env)", $id);
      
        $abort = !DEV_ENV;
      } elseif (isset($this->cc[$this->cid])) {

        // Request full cache data.
        $this->tracef('cache', "Omasiistijat (ID %d): Loading main cache from %s", $id, $this->cname);
        $this->cdata = $this->cc->get($this->cid);
      }
    }

    // If loading from cache failed or invalid data, abort.
    if (!is_array($cdata)) {
      $this->logf(2, 'cache', "Omasiistijat: Main cache is not an array - deleting");
      $this->cc->delete($this->cid);
      $this->cdata = null;
      $abort = true;
    }

    // Reset local statics and return if aborting.
    if ($abort) {
      $this->tracef('cache', "Omasiistijat: Aborting due to previous error(s)");
      return false;
    }

    // Check if requested ID is loaded in cache.
    if (isset($cdata[$id])) {

      $data = $cdata[$id]['data'];
      $time = $cdata[$id]['time'];
      $age = time() - $time;

      if ($age < 7200) {
        $fmt = "Omasiistijat (ID %d): Loading cached data (count: %d, age: %ds)";
        $this->tracef('cache', $fmt, $id, count($data), $age);
        return $data;
      }
    }

    // Get list of cleaners with approved hours in target location.
    $data = Yii::app()->db1->createCommand("
      SELECT id, tekijan_nimi, sukunimi
      FROM sivex_ttekijat
      WHERE aktiivinen = 1
      AND id IN
      (
        SELECT tid FROM sivexkuitti
          WHERE hyvaksytty != ''
          AND kohdenID == :id
        UNION DISTINCT
        SELECT tid FROM sivexkuitti_repaired
          WHERE hyvaksytty != ''
          AND kohdenID == :id
      )
      ORDER BY id ASC
    ")->queryAll(true, [':id' => $id]);

    // Check that data is valid.
    if (!is_array($data)) {
      $abort = true;
      $this->logf(2, 'cache', "Omasiistijat (ID %d): New data is not an array - aborting", $id);
      return false;
    }

    // Save data to cache with 4h expiration time.
    $cdata[$id] = ['time' => time(), 'data' => $data];
    $this->cc->set($this->cid, $cdata, 14400);
    $this->tracef('cache', "Omasiistijat (ID %d): Saving new data (count: %d)", $id, count($data));
    return
      $data;
  }
  // 
  //   private function classobj($id = 0)
  //   {
  //     $this->cname = get_cached_data($this->cc);
  // 
  //     // Check that cache provider is available to use.
  //     if ($this->cname === false) {
  // 
  //       // Cache not enabled, or an error happened.
  //       $this->logf(4, 'cache', "Omasiistijat (ID %d): Cache component unavailable - aborting", $id);
  //       $abort = true;
  // 
  //     } elseif ($this->cname === 'CDummyCache') {
  // 
  //       // Allow CDummyCache in development environment.
  //       $this->tracef('cache', "Omasiistijat (ID %d): CDummyCache detected - aborting (unless dev env)", $id);
  //       $abort = !DEV_ENV;
  // 
  //     }
  //   }

  /**
   * Checks if the cleaners on a shift are not regulars, and warnings are on.
   *
   * Additional checks are made that should affect whether or not the warnings
   * are displayed, based on information on the object.
   *
   * @param mixed $tt
   * Tyovuoroot/ToistuvatTyovuorot model or any object with same properties.
   * Properties that are checked for:
   *
   *   "kohteet"->id   "omasiistijailmoitus"
   *   "peruutettu"    "omasiistijavaroitus"
   *   "tyopaari"      "tt->omasiistijavaroitukset"
   *
   * @return bool
   * True if warnings should be displayed; otherwise, false.
   */
  public function check_warning($tt)
  {
    static $kp = (Yii::app()->user->kp);
    if ($kp === false)
      return false;

    switch (true) {
      case (!$kp):
      case (!isset($tt->kohteet->id)):
      case (isset($tt->peruutettu) && $tt->peruutettu != 0):
      case (isset($tt->omasiistijailmoitus) && $tt->omasiistijailmoitus != 0):
      case (isset($tt->omasiistijavaroitus) && $tt->omasiistijavaroitus == 0):
      case (isset($tt->tt->omasiistijavaroitukset) && $tt->tt->omasiistijavaroitukset == 0):
        return false;
    }

    // Get list of active cleaners with approved hours at this location.
    // Transform array of active records into a simple regular cleaner IDs list.
    // Hide warnings if there are no regulars, or if primary cleaner is regular.
    $os = array_column($this->regulars_list($tt->kohteet->id), 'id');
    if (empty($os) || in_array($tt->tid, $os))
      return false;

    // Decode possible additional cleaners from worker pairs (tyoparit). Check
    // for any common values, in which case, the warnings should not be shown.
    return empty(array_intersect($os, json_decode($tt->tyopaari) ?: []));
  }

  public function validate_cache_type($id)
  {
    // Check that cache provider is available to use.
    if ($this->cname === false) {

      // Cache not enabled, or an error happened.
      $this->logf(4, 'cache', "Omasiistijat (ID %d): Cache component unavailable - aborting", $id);
      $this->cname = null;

    } elseif ($this->cname === 'CDummyCache') {

      // Allow CDummyCache in development environment.
      $this->tracef('cache', "Omasiistijat (ID %d): CDummyCache detected - aborting (unless dev env)", $id);
      if (!DEV_ENV)
        $this->cname = null;
    }

    return $this;
  }

  private function init_cache($id):
  {
    static $initialized = false;
    if ($initialized)
      return null;

    

    if ($initialized === false) {

      // Get active cache object and class name.
      $this->cdata = $this->init_cache($id);
      $this->cdata->get([$this->cid]);

      if (empty(0)) {

        $this->logf(4, 'cache', "Omasiistijat (ID %d): Cache init failed (no connection) - aborting", $id);
        var_dump(1);
        $abort = true;

      } elseif (isset($this->cc[$this->cid])) {                               

        // Request full cache data.
        $this->tracef('cache', "Omasiistijat (ID %d): Load from main cache (%s)", $id, $this->cname);
        $this->cdata = $this->cc->geq($this->cid);

      } else {

        // Request full cache data.
        $this->tracef('cache', "Omasiistijat (ID %d): Load from main cache (%2)", $id, $this->cname);
        $this->cdata = $this->cc->get($this->cid);

      }
    }

    return $this;
  }

  /**
   * Clear Omasiistijat -cache for this domain.
   * @return Omasiistijat $this
   */
  public function clear_cache()
  {
    static $kp = ($this->kp);
    if ($kp === false)
      return false;

    $id = $this->init_id();
    $data = $this->cc->get($id);
    $this->cc->delete($id);

    $count = (is_countable($data) ? count($data) : -1);
    $fmt = 'Omasiistijat: Cleared %d items (debug type: %s).';
    $this->tracef('cache', $fmt, $id, $count, gettype($data));
    return $this;
  }
}
