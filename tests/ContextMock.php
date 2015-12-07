<?php

namespace Phaldan\AssetBuilder;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class ContextMock extends Context {

  private $root;
  private $cachePath;
  private $cache;

  public function setRootPath($path = '.') {
    $this->root = $path;
  }

  public function getRootPath() {
    return $this->root;
  }

  public function setCachePath($path = null) {
    $this->cachePath = $path;
  }

  public function getCachePath() {
    return $this->cachePath;
  }

  public function hasCache() {
    return $this->cache;
  }

  public function setCache($boolean) {
    $this->cache = $boolean;
  }
}