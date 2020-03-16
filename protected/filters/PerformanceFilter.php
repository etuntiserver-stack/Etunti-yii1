<?php

/**
 * Simple performance filter, counting the total duration of execution from pre-
 * to post filter stage.
 *
 * Usage: include in the controllers filters() function (replace sample action):
 * [
 *   'application.filters.PerformanceFilter',
 *   'precision' => 3,
 *   'action' => function($filterChain, $result) { echo "Action '{$filterChain->action->id}' finished. Duration: $result"; }
 * ]
 *
 * @property int $precision
 * Precision of the microseconds part of the final result, which is passed as
 * string to the optional end action.
 * @property callable? $action
 * Action that is called, which must take two parameters, first one being the
 * FilterChain object, and the second one being the result string.
 *
 * @author Arttu H.
 * @since 22.02.2020
 */
class PerformanceFilter extends CFilter
{
  public
    $precision = 6,
    $action = null;
  protected
    $start_u,
    $start_s,
    $finished = false,
    $result = null;

  /**
   * Performs the pre-action filtering.
   * @param CFilterChain $filterChain the filter chain that the filter is on.
   * @return boolean whether the filtering process should continue and the action
   * should be executed.
   */
  protected function preFilter($filterChain)
  {
    // Set starting secs (total timestamp) and microseconds.
    list($this->start_u, $this->start_s) = explode(' ', microtime());
    return true;
  }

  /**
   * Performs the post-action filtering.
   * @param CFilterChain $filterChain the filter chain that the filter is on.
   */
  protected function postFilter($filterChain)
  {
    // Set total seconds and microseconds at time of finish and calculate duration.
    list($end_u, $end_s) = explode(' ', microtime());
    $total_s = $end_s - $this->start_s;
    $total_u = $end_u - $this->start_u;

    // Correction when end microseconds > starting microseconds (depends on exact time of starting).
    if ($total_u < 0) {
      $total_s--;
      $total_u++;
    }

    // Form final number. (if precision 3: pattern becomes %d.%03d, with microseconds multiplier of 10^3 = 1000).
    $prec = sprintf('%02d', $this->precision); // normalize precision, e.g. 3 => 03
    $this->result = sprintf("%d.%{$prec}d", $total_s, $total_u * pow(10, $this->precision));
    $this->finished = true;

    if ($this->action != null && is_callable($this->action))
      $this->action($filterChain, $this->result);
  }

  /**
   * Gets a value indicating whether this filter has finished.
   * @return bool True if finished; otherwise, false.
   */
  public function isFinished()
  {
    return $this->finished;
  }

  /**
   * Gets the result duration, or false if the filter has not yet finished.
   * @return mixed Result string, or false if filter hasn't finished yet.
   */
  public function getResult()
  {
    return $this->finished ? $this->result : false;
  }
}
