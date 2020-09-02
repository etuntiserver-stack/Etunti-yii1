<?php

namespace application\components\etunti;

use CLogger;

/**
 * Provides log helper functions as a component "module".
 */
class ELog extends \CApplicationComponent
{
  /** @var CLogger $clogger object instance. */
  public static $clogger = \Yii::getLogger();

  public $default_channel = 'application';
  public $default_level = 'info';

  /**
   * Create an instance of {@see ELog}.
   *
   * Assigns default channel and logging level for further calls. The logging
   * level can be a valid string, or a corresponding integer value, seen below:
   *     0=trace, 1:info, 2:warning, 3:error, 4:profile
   * 
   * @param mixed $default_level     Level string, or their corresponding integer value.
   * @param string $default_channel  Default logging channel category, e.g. system.web.
   *
   * @return null
   * {@inheritdoc}
   */
  private function __construct($default_level = 1, string $default_channel = 'application')
  {
    // Translate possible numeral level into string, and assign default level.
    $this->default_level = self::translate_level($default_level, 'info');
    $this->default_channel = $default_channel;
  }

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


  /**
   * Output corresponding level string for the logger engine:
   *
   * @param int $level
   * Input value to translate (0-4):
   *   0:trace 1:info 2:warning 3:error 4:profile
   *
   * @return string
   * Level string, corresponding to the value of $level as minmaxed to 0..4.
   */
  public static function translate_level($level, $default='info') :string
  {
    // Indexed levels, for quick access
    static $levels = ['trace','info','warning','error','profile'];

    // Check if level is a valid string already, to return as is.
    if (in_array($level, $levels)) {
      return $level;
    }

    // Check if level is numeric.
    if (is_numeric($level)) {

      // Minmax value to between 0 and 4.
      switch (min(4, max(0, +$level))) {
        case 0  : return 'trace';
        case 1  : return 'info'; 
        case 2  : return 'warning';
        case 3  : return 'error';
        case 4  : return 'profile';
        default : return 'info'; 
      }
    }

    // Invalid non-numeric value; return the default value.
    return $default;
  }
}

class ELogEntry
{
  /** @var CLogger */
  protected static $logger = \Yii::app()->getLogger();

  public $default_level;
  public $default_channel;
  public $logs = [];
  private $stack = [];

  public function __construct($default_level = 'info', string $default_channel = 'application')
  {
    $this->default_level = ELog::translate_level($default_level, 'info');
    $this->default_channel = $default_channel ?: 'application';
  }

  public function flush()
  {
    foreach ($this->logs as $log) {
      static::$logger->log($log[0], $log[1], $log[2]);
    }
  }

  public function push(?int $count = null)
  {
    if (null !== $count) {
      $slice = array_slice($this->stack, 0, $count);
      $this->logs = array_merge($this->logs, $slice);
      $this->stack = array_diff($this->stack, $slice);
    } else {
      $this->logs = array_merge($this->logs, $this->stack);
      unset($this->stack);
      $this->stack = [];
    }

    return $this;
  }

  protected function rise($lvl=[], $ch=[])
  {
    $e = array_shift($this->stack) ?: [];
    (empty($e[1]) || !empty($lvl)) && ($e[1]=( is_array($lvl) ? array_shift($lvl) : $lvl )) ?:[];
    (empty($e[2]) || !empty($ch)) && ($e[2]=( is_array($ch) ? array_shift($ch) : $ch )) ?:[];
    foreach ($ch as $c) {
      foreach ($lvl as $l) {
        $new = clone $e;
        $new[2] = $c;
        $new[1] = $l;
        $this->stack[] = $new;
      }
    }

    if (count($this->stack) <= 1) {
      }
    }

    if (!empty($args)) {
      if (empty($this->stack)) {
        $this->default_channel = array_shift($args);
      } else {
        $this->stack[2] = array_shift($args);

        foreach ($args as $ch) {
          $new = clone $this->stack;
          $new[2] = $ch;
          $this->entry($new[1], $new[0], null, $new[2]);
          $this->logs[] = $this->stack = &$new;
        }
      }
    }

    return $this;
  }

  /**
   * Add output entry to formatter and then add the formatted message to stage
   * for logging using the active logging engine.
   *
   * Use null as $level or as $channel to use the defaults in this object. Log
   * entries should be flushed, like for example:
   *
   * ```php
   * <?php
   * $log->entry()->out(...)->flush();
   * ?>
   * ```
   *
   * @param string|int $level  Logging level ({@see translate_level()}).
   * @param string $fmt        Format string for {@see sprintf()}.
   * @param array|null $args   Args for {@see sprintf()}.
   * @param string $channel    Targets a specific channel.
   * @return null
   */
  private function entry($level=null, string $fmt=null, ?array $args=null, string $channel=null)
  {
    $msg = (empty($args) ? $fmt : vsprintf($fmt, $args));
    $level = ELog::translate_level($level, null);
    $new = &[$msg,$level,$channel];
    $this->stack[] = &$new;
    return $this;
  }


  public function foreach_levels(...$levels)
  {
    if (!empty($levels)) {
      $first = ELog::translate_level(array_shift($levels));
      if (empty($this->stack)) {
        $this->default_level = $first;
      } else {
        $this->stack[1] = $levels;

        foreach ($levels as $ch) {
          $new = clone $this->stack;
          $new[2] = $ch;
          $this->logs[] = $this->stack = &$new;
        }
      }
    }

    return $this;
  }

  /** Shortcut to {@see outf()}; dump message to default channel and level. */
  protected function out(string $fmt, ...$args)
  {
    return $this->entry(null, $fmt, $args, null);
  }

  /** Shortcut to {@see outf()}; log trace message to default channel and level. */
  protected function trace(string $fmt, ...$args)
  {
    return $this->entry('trace', $fmt, $args, null);
  }

  /** Shortcut to {@see outf()}; log info message to default channel and level. */
  protected function info(string $fmt, ...$args)
  {
    return $this->entry('info', $fmt, $args, null);
  }

  /** Shortcut to {@see outf()}; log warning message to default channel and level. */
  protected function warning(string $fmt, ...$args)
  {
    return $this->entry('warning', $fmt, $args, null);
  }

  /** Shortcut to {@see outf()}; log error message to default channel and level. */
  protected function error(string $fmt, ...$args)
  {
    return $this->entry('error', $fmt, $args, null);
  }
}

/**
 * CLogFilter class file
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CLogFilter preprocesses the logged messages before they are handled by a log route.
 *
 * CLogFilter is meant to be used by a log route to preprocess the logged messages
 * before they are handled by the route. The default implementation of CLogFilter
 * prepends additional context information to the logged messages. In particular,
 * by setting {@link logVars}, predefined PHP variables such as
 * $_SERVER, $_POST, etc. can be saved as a log message, which may help identify/debug
 * issues encountered.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.logging
 */
class ELogFilter extends \CLogFilter
{
  /** Initialize the filter and local variable defaults. */
  public function __construct($levels, $channels)
  {
    $this->elogLevels = (is_array($levels) ? $levels : [$levels]) ?: [];
    $this->elogChannels = (is_array($channels) ? $channels : [$channels]) ?: [];

    // prefix each log message with the current user session ID.
    $this->prefixSession=true;

    // prefix each log message with the current user name and ID.
    $this->prefixUser=true;
  }

	/**
	 * {@inheritDoc} Filters the given log messages.
   * 
	 * This is the main method of CLogFilter. It processes the log messages by
   * adding context information, etc.
   *
	 * @param array $logs
   * The log messages to process.
   *
	 * @return array
   * Array with processed log entries.
   *
   * {@see \CLogFilter::filter()
	 */
	public function filter(&$logs)
	{
		if (!empty($logs))
		{
			if(($message=$this->getContext())!=='')
				array_unshift($logs,array($message,CLogger::LEVEL_INFO,'application',YII_BEGIN_TIME));
			$this->format($logs);
		}
    return $logs;
    parent::
	}

	/**
	 * Formats the log messages.
	 * The default implementation will prefix each message with session ID
	 * if {@link prefixSession} is set true. It may also prefix each message
	 * with the current user's name and ID if {@link prefixUser} is true.
	 * @param array $logs the log messages
	 */
	protected function format(&$logs)
	{
		// $prefix='';
		// if($this->prefixSession && ($id=session_id())!=='')
		// 	$prefix.="[$id]";
		// if($this->prefixUser && ($user=Yii::app()->getComponent('user',false))!==null)
		// 	$prefix.='['.$user->getName().']['.$user->getId().']';
		// if($prefix!=='')
		// {
		// 	foreach($logs as &$log)
		// 		$log[0]=$prefix.' '.$log[0];
		// }
	}

	/**
	 * Generates the context information to be logged.
	 * The default implementation will dump user information, system variables, etc.
	 * @return string the context information. If an empty string, it means no context information.
	 */
	protected function getContext()
	{
// 		$context=array();
// 		if($this->logUser && ($user=Yii::app()->getComponent('user',false))!==null)
// 			$context[]='User: '.$user->getName().' (ID: '.$user->getId().')';
// 
// 		if($this->dumper==='var_export' || $this->dumper==='print_r')
// 		{
// 			foreach($this->logVars as $name)
// 				if(($value=$this->getGlobalsValue($name))!==null)
// 					$context[]="\${$name}=".call_user_func($this->dumper,$value,true);
// 		}
// 		else
// 		{
// 			foreach($this->logVars as $name)
// 				if(($value=$this->getGlobalsValue($name))!==null)
// 					$context[]="\${$name}=".call_user_func($this->dumper,$value);
// 		}
// 
// 		return implode("\n\n",$context);
	}

	/**
	 * @param string[] $path
	 * @return string|null
	 */
	private function getGlobalsValue(&$path)
	{
		// if(is_scalar($path))
		// 	return !empty($GLOBALS[$path]) ? $GLOBALS[$path] : null;
		// $pathAux=$path;
		// $parts=array();
		// $value=$GLOBALS;
		// do
		// {
		// 	$value=$value[$parts[]=array_shift($pathAux)];
		// }
		// while(!empty($value) && !empty($pathAux) && !is_string($value));
		// $path=implode('.',$parts);
		// return $value;
	}
}
