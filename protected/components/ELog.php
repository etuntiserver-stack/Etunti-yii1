<?php

namespace application\components\etunti;

/**
 * Provides log helper functions as a component "module".
 */
class ELog extends \CApplicationComponent
{
  /** @var CLogger $clogger object instance. */
  public static $clogger = \Yii::getLogger();

  public $channel = 'application';
  public $level = 'info';

  /**
   * {@inheritdoc}
   *
   * Create an instance of {@see ELog}.
   *
   * @param mixed $level     0=trace, 1:info, 2:warning, 3:error, 4:profile
   * @param string $channel  Logging channel (category), e.g. 'system.web'.
   */
  private function __construct($level = 1, string $channel = 'application')
  {
    // Indexed levels, for quick access
    static $levels = ['trace','info','warning','error','profile'];

    if (is_int($level)) {
      if (!isset($levels[$level])) {




        // TODO: LOG
        // TODO: LOG
        // TODO: LOG
        // TODO: LOG
        // TODO: LOG
        // TODO: LOG  gitj




      }
    }

    // Translate numeral level into string.
    if (is_int($level)) {

      // 0:trace 1:info 2:warning 3:error 4:profile
      switch ($level) {
        case 0  : $level = 'trace';   break;
        case 1  : $level = 'info';    break;
        case 2  : $level = 'warning'; break;
        case 3  : $level = 'error';   break;
        case 4  : $level = 'profile'; break;
        default : $level = 'info';    break;
      }
    }

    $this->level = $level;
    $this->channel = $channel;
  }

  #region Conan

  /**
   * Format message, and forward this entry to the log engine.
   * @param string $channel  Targets a specific channel.
   * @param string $fmt      Format string for {@see sprintf()}.
   * @param mixed ...$args   Args for {@see sprintf()}.
   */
  protected function to(string $channel, string $fmt, ...$args)
  {
    array_unshift($fmt, $args);
    $m = call_user_func_array('sprintf', $args);
    \Yii::log($m, $this->level, $channel);
  }

  /**
   * Format message, and forward this entry to the log engine.
   * @param string $fmt     Format string for {@see sprintf()}.
   * @param mixed ...$args  Args for {@see sprintf()}.
   */
  protected function out(string $fmt, ...$args)
  {
    array_unshift($args, $this->channel, $fmt);
    call_user_func_array([$this, 'to'], $args);
  }

  /**
   * Format trace message, and forward this entry to the log engine.
   * @param string $fmt     Format string for {@see sprintf()}.
   * @param mixed ...$args  Args for {@see sprintf()}.
   */
  protected function trace(string $fmt, ...$args)
  {
    array_unshift($args, 'trace', $fmt);
    call_user_func_array([$this, 'to'], $args);
  }

  /**
   * Format trace message, and forward this entry to the log engine.
   * @param string $fmt     Format string for {@see sprintf()}.
   * @param mixed ...$args  Args for {@see sprintf()}.
   */
  protected function info(string $fmt, ...$args)
  {
    array_unshift($args, 'info', $fmt);
    call_user_func_array([$this, 'to'], $args);
  }

  /**
   * Format trace message, and forward this entry to the log engine.
   * @param string $fmt     Format string for {@see sprintf()}.
   * @param mixed ...$args  Args for {@see sprintf()}.
   */
  protected function warning(string $fmt, ...$args)
  {
    array_unshift($args, 'warning', $fmt);
    call_user_func_array([$this, 'to'], $args);
  }

  /**
   * Format trace message, and forward this entry to the log engine.
   * @param string $fmt     Format string for {@see sprintf()}.
   * @param mixed ...$args  Args for {@see sprintf()}.
   */
  protected function error(string $fmt, ...$args)
  {
    array_unshift($args, 'error', $fmt);
    call_user_func_array([$this, 'to'], $args);
  }

  #endregion
  #region Static Methods

  /**
   * Create an entry for target channel, ready for formatting.
   *
   * @param string|int  $level 0=trace, 1:info, 2:warning, 3:error, 4:profile
   * @param string      $ch Logging channel (category), e.g. 'system.web'.
   * @return ELog       Log entry, ready for formatting.
   */
  public static function mkentry($level = 'info', string $channel = 'application'): ELog
  {
    return (new ELog($level, $channel));
  }

  /**
   * Create a trace entry for target channel, ready for formatting.
   *
   * @param string $ch  Logging channel (category), e.g. 'system.web'.
   * @return ELog       Log entry, ready for formatting.
   */
  public static function mktrace(string $channel = 'application'): Elog
  {
    return (new ELog('trace', $channel));
  }

  #endregion
}