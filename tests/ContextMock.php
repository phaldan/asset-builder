<?php

namespace Phaldan\AssetBuilder;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class ContextMock extends Context {

  private $root;
  private $cache;

  public function setRootPath($path = '.') {
    $this->root = $path;
  }

  public function getRootPath() {
    return $this->root;
  }

  public function setCachePath($path = null) {
    $this->cache = $path;
  }

  public function getCachePath() {
    return $this->cache;
  }


}