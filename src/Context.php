<?php

namespace Phaldan\AssetBuilder;

use Serializable;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class Context implements Serializable {

  const DEFAULT_ROOT_PATH = '.';
  const OPTION_ROOT = 'path';
  const OPTION_CACHE = 'cache';
  const OPTION_MINIFIER = 'minifier';
  const OPTION_DEBUG = 'debug';
  const OPTION_STOPWATCH = 'stopwatch';

  /**
   * @var OptionMap
   */
  private $options;

  public function __construct() {
    $this->options = new OptionMap();
    $this->options[self::OPTION_ROOT] = self::DEFAULT_ROOT_PATH;
    $this->options[self::OPTION_CACHE] = null;
    $this->options[self::OPTION_MINIFIER] = false;
    $this->options[self::OPTION_DEBUG] = false;
    $this->options[self::OPTION_STOPWATCH] = false;
  }

  /**
   * Set relative or absolute path of root directory
   * @param string $path
   */
  public function setRootPath($path = '.') {
    $this->options->offsetSet(self::OPTION_ROOT, $this->getRealPath($path));
  }

  private function getRealPath($path) {
    $realPath = realpath($path);
    if (!$realPath) {
      throw Exception::pathNotFound($path);
    }
    return $realPath . DIRECTORY_SEPARATOR;
  }

  /**
   * Returns absolute path to root directory.
   * @return string
   */
  public function getRootPath() {
    return $this->options->offsetGet(self::OPTION_ROOT);
  }

  public function enableMinifier() {
    $this->options->offsetSet(self::OPTION_MINIFIER, true);
  }

  public function disableMinifier() {
    $this->options->offsetSet(self::OPTION_MINIFIER, false);
  }

  /**
   * @return bool
   */
  public function hasMinifier() {
    return $this->options->offsetGet(self::OPTION_MINIFIER);
  }

  public function enableDebug() {
    $this->options->offsetSet(self::OPTION_DEBUG, true);
  }

  public function disableDebug() {
    $this->options->offsetSet(self::OPTION_DEBUG, false);
  }

  /**
   * @return bool
   */
  public function hasDebug() {
    return $this->options->offsetGet(self::OPTION_DEBUG);
  }

  public function enableStopWatch() {
    $this->options->offsetSet(self::OPTION_STOPWATCH, true);
  }

  public function disableStopWatch() {
    $this->options->offsetSet(self::OPTION_STOPWATCH, false);
  }

  /**
   * @return bool
   */
  public function hasStopWatch() {
    return $this->options->offsetGet(self::OPTION_STOPWATCH);
  }

  /**
   * @param null $path
   */
  public function setCachePath($path = null) {
    $this->options->offsetSet(self::OPTION_CACHE, $this->getRealPath($path));
  }

  /**
   * @return null|string
   */
  public function getCachePath() {
    return $this->options->offsetGet(self::OPTION_CACHE);
  }

  /**
   * @return boolean
   */
  public function hasCache() {
    return !is_null($this->getCachePath());
  }

  /**
   * @inheritdoc
   */
  public function serialize() {
    return $this->options->serialize();
  }

  /**
   * @inheritdoc
   */
  public function unserialize($serialized) {
    $this->options->unserialize($serialized);
    return $this;
  }
}