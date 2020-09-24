<?php

class Log
{
  public static function write($message)
  {
    $dir = _ROOT_DIR_ . '/files/log/' . date('Y-m-d') . '.log';
    $contents = '';
    if (file_exists($dir)) {
      $contents = file_get_contents($dir);
    }
    $contents .= date('Y-m-d H:i:s') . ' ' . $message . "\n";
    file_put_contents($dir, $contents);
  }

}
