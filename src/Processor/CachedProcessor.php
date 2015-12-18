<?php

namespace Phaldan\AssetBuilder\Processor;

use DateTime;
use Phaldan\AssetBuilder\Cache\Cache;
use Phaldan\AssetBuilder\Context;
use Phaldan\AssetBuilder\Exception;
use Phaldan\AssetBuilder\FileSystem\FileSystem;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class CachedProcessor implements Processor {

  /**
   * @var Cache
   */
  private $cache;

  /**
   * @var Processor
   */
  private $processor;

  /**
   * @var FileSystem
   */
  private $fileSystem;

  /**
   * @param Processor $processor
   * @param Cache $cache
   * @param FileSystem $fileSystem
   */
  public function __construct(Processor $processor, Cache $cache, FileSystem $fileSystem) {
    $this->processor = $processor;
    $this->cache = $cache;
    $this->fileSystem = $fileSystem;
  }

  private function getCacheEntry($filePath) {
    return $this->cache->hasEntry($filePath) ? $this->requestCacheEntry($filePath) : $this->setCacheEntry($filePath);
  }

  private function requestCacheEntry($filePath) {
    $entry = new CacheEntry();
    $entry->unserialize($this->cache->getEntry($filePath));
    return $this->hasChanged($entry) ? $this->setCacheEntry($filePath) : $entry;
  }

  private function hasChanged(CacheEntry $entry) {
    foreach ($entry->getFiles() as $file => $lastModified) {
      $time = $this->fileSystem->getModifiedTime($file);
      if (!empty($time->diff($lastModified)->format('%r'))) {
        return true;
      }
    }
    return false;
  }

  private function setCacheEntry($filePath) {
    $content = $this->processor->process($filePath);
    $files = $this->processor->getFiles($filePath);
    $lastModified = $this->processor->getLastModified($filePath);
    $entry = new CacheEntry($content, $files, $lastModified);
    $this->cache->setEntry($filePath, $entry);
    return $entry;
  }

  /**
   * @inheritdoc
   */
  public function process($filePath) {
    return $this->getCacheEntry($filePath)->getContent();
  }

  /**
   * @inheritdoc
   */
  public function getFiles($filePath) {
    return $this->getCacheEntry($filePath)->getFiles();
  }

  /**
   * @inheritdoc
   */
  public function getLastModified($filePath) {
    return $this->getCacheEntry($filePath)->getLastModified();
  }

  /**
   * @inheritdoc
   */
  public function getFileExtension() {
    return $this->processor->getFileExtension();
  }

  /**
   * @inheritdoc
   */
  public function getOutputMimeType() {
    return $this->processor->getOutputMimeType();
  }
}