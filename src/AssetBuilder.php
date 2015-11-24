<?php

namespace Phaldan\AssetBuilder;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class AssetBuilder {

  private $value;

  public function set($value) {
    $this->value = $value;
  }

  public function get() {
    return $this->value;
  }
}