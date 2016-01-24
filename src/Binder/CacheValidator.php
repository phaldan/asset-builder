<?php

namespace Phaldan\AssetBuilder\Binder;

use DateTime;
use Phaldan\AssetBuilder\Cache\Cache;
use Phaldan\AssetBuilder\Context;
use Phaldan\AssetBuilder\FileSystem\FileSystem;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class CacheValidator {

  /**
   * @var FileSystem
   */
  private $fileSystem;
  /**
   * @var Context
   */
  private $context;
  /**
   * @var Cache
   */
  private $cache;

  /**
   * @param FileSystem $fileSystem
   * @param Context $context
   * @param Cache $cache
   */
  public function __construct(FileSystem $fileSystem, Context $context, Cache $cache) {
    $this->fileSystem = $fileSystem;
    $this->context = $context;
    $this->cache = $cache;
  }

  /**
   * @param CacheBinderEntry $entry
   * @return bool
   */
  public function validate(CacheBinderEntry $entry) {
    return $this->handleContext($entry) && $this->handleFiles($entry);
  }

  private function handleContext(CacheBinderEntry $entry) {
    if ($this->context == $entry->getContext()) {
      return true;
    }
    $this->clearCacheEntries($entry);
    return false;
  }

  private function clearCacheEntries(CacheBinderEntry $entry) {
    foreach (array_keys($entry->getFiles()) as $file) {
      $this->cache->deleteEntry($file);
    }
  }

  private function handleFiles(CacheBinderEntry $entry) {
    foreach ($entry->getFiles() as $file => $time) {
      if ($this->hasChanged($file, $time)) {
        return false;
      }
    }
    return true;
  }

  private function hasChanged($file, DateTime $dateTime) {
    $lastModified = $this->fileSystem->getModifiedTime($file);
    return !empty($lastModified->diff($dateTime)->format('%r'));
  }
}