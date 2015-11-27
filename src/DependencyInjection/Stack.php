<?php

namespace Phaldan\AssetBuilder\DependencyInjection;

use SplStack;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class Stack extends SplStack {

  const SEPARATOR = ', ';

  public function contains($value) {
    foreach ($this as $entry) {
      if ($entry == $value) {
        return true;
      }
    }
    return false;
  }

  public function toString($separator = self::SEPARATOR) {
    $array = [];
    foreach ($this as $entry) {
      $array[] = $entry;
    }
    return join($separator, $array);
  }
}