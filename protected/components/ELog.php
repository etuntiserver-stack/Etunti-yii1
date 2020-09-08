<?php

/**
 * Provides log helper functions.
 */
class ELog extends CBehavior
{
  /**
   * Writes a formatted trace message.
   * This method will only log a message when the application is in debug mode.
   * @param string $category  Category of the message. It is case-insensitive.
   * @param string $fmt       Message format for {@link vsprintf}.
   * @param mixed ...$args    Optional args for formatting the message.
   * @return string           Final message that was passed to {@link Yii::trace}
   */
  public function trace($category = null, $fmt, ...$args)
  {
    $msg = vsprintf($fmt, $args);
    Yii::trace($msg, $category);
    return $msg;
  }

  /**
   * Logs a formatted info message.
   * @param string $category  Category of the message. It is case-insensitive.
   * @param string $fmt       Message format for {@link vsprintf}.
   * @param mixed ...$args    Optional args for formatting the message.
   * @return string           Final message that was passed to {@link Yii::log}
   */
  public function log($category = null, $fmt, ...$args)
  {
    $msg = vsprintf($fmt, $args);
    Yii::log($msg, 'info', $category);
    return $msg;
  }

  /**
   * Logs a formatted warning message.
   * @param string $category  Category of the message. It is case-insensitive.
   * @param string $fmt       Message format for {@link vsprintf}.
   * @param mixed ...$args    Optional args for formatting the message.
   * @return string           Final message that was passed to {@link Yii::log}
   */
  public function warning($category = null, $fmt, ...$args)
  {
    $msg = vsprintf($fmt, $args);
    Yii::log($msg, 'warning', $category);
    return $msg;
  }

  /**
   * Logs a formatted error message.
   * @param string $category  Category of the message. It is case-insensitive.
   * @param string $fmt       Message format for {@link vsprintf}.
   * @param mixed ...$args    Optional args for formatting the message.
   * @return string           Final message that was passed to {@link Yii::log}
   */
  public function error($category = null, $fmt, ...$args)
  {
    $msg = vsprintf($fmt, $args);
    Yii::log($msg, 'error', $category);
    return $msg;
  }
}
