<?php

namespace Phaldan\AssetBuilder;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class Context {

  const DEFAULT_ROOT_PATH = '.';

  private $path = self::DEFAULT_ROOT_PATH;
  private $cache = null;
  private $minifier = false;
  private $debug = false;
  private $stopWatch = false;

  /**
   * Set relative or absolute path of root directory
   * @param string $path
   */
  public function setRootPath($path = '.') {
    $this->path = $path;
  }

  /**
   * Returns absolute path to root directory.
   * @return string
   */
  public function getRootPath() {
    return $this->path;
  }

  /**
   * @param bool|true $boolean
   */
  public function enableMinifier($boolean = true) {
    $this->minifier = $boolean;
  }

  /**
   * @return bool
   */
  public function hasMinifier() {
    return $this->minifier;
  }

  /**
   * @param bool|true $boolean
   */
  public function enableDebug($boolean = true) {
    $this->debug = $boolean;
  }

  /**
   * @return bool
   */
  public function hasDebug() {
    return $this->debug;
  }

  /**
   * @param bool|true $boolean
   */
  public function enableStopWatch($boolean = true) {
    $this->stopWatch = $boolean;
  }

  /**
   * @return bool
   */
  public function hasStopWatch() {
    return $this->stopWatch;
  }

  /**
   * @param null $path
   */
  public function setCachePath($path = null) {
    $this->cache = $path;
  }

  /**
   * @return null|string
   */
  public function getCachePath() {
    return $this->cache;
  }
}