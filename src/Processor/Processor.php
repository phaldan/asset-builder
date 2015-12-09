<?php

namespace Phaldan\AssetBuilder\Processor;

use Phaldan\AssetBuilder\Cache\Cache;
use Phaldan\AssetBuilder\Context;
use Phaldan\AssetBuilder\FileSystem\FileSystem;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
abstract class Processor {

  /**
   * @var FileSystem
   */
  private $fileSystem;
  /**
   * @var Cache
   */
  private $cache;
  /**
   * @var Context
   */
  private $context;

  /**
   * Returns file extension
   * @return string
   */
  public abstract function getSupportedExtension();

  /**
   * @return string
   */
  public abstract function getOutputMimeType();

  /**
   * @param $file
   * @return string
   */
  protected function executeProcessing($file) {

  }

  /**
   * @param FileSystem $fileSystem
   * @param Cache $cache
   * @param Context $context
   */
  public function __construct(FileSystem $fileSystem, Cache $cache, Context $context) {
    $this->fileSystem = $fileSystem;
    $this->cache = $cache;
    $this->context = $context;
  }

  /**
   * Transform to native language like CSS or JavaScript, and compress
   * @param $file
   * @return string
   */
  public function process($file) {
    $content = $this->getCacheEntry($file);
    if (is_null($content)) {
      $content = $this->executeProcessing($file);
      $this->setCacheEntry($file, $content);
    }
    return $content;
  }

  private function getCacheEntry($filePath) {
    return $this->context->hasCache() ? $this->requestCacheEntry($filePath) : null;
  }

  /**
   * @param $file
   * @return string|null
   */
  protected function requestCacheEntry($file) {
    $time = $this->fileSystem->getModifiedTime($file);
    return $this->cache->hasEntry($file, $time) ? $this->cache->getEntry($file) : null;
  }

  private function setCacheEntry($filePath, $content) {
    if ($this->context->hasCache()) {
      $this->cache->setEntry($filePath, $content);
    }
  }
}