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
   * @var array
   */
  private $entries = [];

  private $fileChanges = [];

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

  /**
   * @return Processor
   */
  public function getProcessor() {
    return $this->processor;
  }

  private function getCacheEntry($filePath) {
    return $this->cache->hasEntry($filePath) ? $this->requestCacheEntry($filePath) : $this->setCacheEntry($filePath);
  }

  private function requestCacheEntry($filePath) {
    if (isset($this->entries[$filePath])) {
      return $this->entries[$filePath];
    }
    $content = $this->cache->getEntry($filePath);
    $entry = new CacheEntry();
    $entry->unserialize($content);
    return $this->hasChanged($entry) ? $this->setCacheEntry($filePath) : $this->entries[$filePath] = $entry;
  }

  private function hasChanged(CacheEntry $entry) {
    foreach ($entry->getFiles() as $file => $lastModified) {
      if ($this->checkFileChanged($file, $lastModified)) {
        return true;
      }
    }
    return false;
  }

  private function checkFileChanged($file, $lastModified) {
    if (isset($this->fileChanges[$file])) {
      return $this->fileChanges[$file];
    }
    $time = $this->fileSystem->getModifiedTime($file);
    $changed = !empty($time->diff($lastModified)->format('%r'));
    $this->fileChanges[$file] = $changed;
    return $changed;
  }

  private function setCacheEntry($filePath) {
    $content = $this->processor->process($filePath);
    $files = $this->processor->getFiles($filePath);
    $lastModified = $this->processor->getLastModified($filePath);
    $entry = new CacheEntry($content, $files, $lastModified);
    $this->cache->setEntry($filePath, $entry);
    $this->entries[$filePath] = $entry;
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