<?php

class Log
{
  public static function write($message)
  {
    $file = _ROOT_DIR_ . '/files/log/' . date('Y-m-d') . '.log';
    $contents = '';
    if (file_exists($file)) {
      $contents = file_get_contents($file);
    }
    $contents .= date('Y-m-d H:i:s') . ' ' . $message . "\n";
    file_put_contents($file, $contents);
  }
}
