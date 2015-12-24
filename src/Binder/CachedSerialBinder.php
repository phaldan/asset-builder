<?php

namespace Phaldan\AssetBuilder\Binder;

use IteratorAggregate;
use Phaldan\AssetBuilder\Cache\Cache;
use Phaldan\AssetBuilder\Processor\CacheEntry;
use Phaldan\AssetBuilder\Processor\ProcessorList;
use Phaldan\AssetBuilder\FileSystem\FileSystem;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class CachedSerialBinder implements CachedBinder {

  /**
   * @var FileSystem
   */
  private $fileSystem;

  /**
   * @var Cache
   */
  private $cache;

  /**
   * @var Binder
   */
  private $binder;

  private $files = [];

  public function __construct(FileSystem $fileSystem, Cache $cache, Binder $binder) {
    $this->fileSystem = $fileSystem;
    $this->cache = $cache;
    $this->binder = $binder;
  }

  public function bind(IteratorAggregate $files, ProcessorList $compiler) {
    $key = $this->generateCacheKey($files);
    $entry = $this->cache->hasEntry($key) ? $this->requestCache($key, $files, $compiler) : $this->process($key, $files, $compiler);
    $this->files = $entry->getFiles();
    return $entry->getContent();
  }

  public function generateCacheKey(IteratorAggregate $files) {
    $key = '';
    foreach ($files as $file) {
      $key .= $file;
    }
    return $key;
  }

  private function requestCache($key, $files, $compiler) {
    $cache = new CacheEntry();
    $entry = $cache->unserialize($this->cache->getEntry($key));
    return $this->validateCache($entry) ? $entry : $this->process($key, $files, $compiler);
  }

  private function validateCache(CacheEntry $entry) {
    foreach ($entry->getFiles() as $file => $time) {
      $lastModified = $this->fileSystem->getModifiedTime($file);
      if (!empty($lastModified->diff($time)->format('%r'))) {
        return false;
      }
    }
    return true;
  }

  private function process($key, $files, $compiler) {
    $result = $this->binder->bind($files, $compiler);
    $entry = new CacheEntry($result, $this->binder->getFiles());
    $this->cache->setEntry($key, $entry);
    return $entry;
  }

  public function getFiles() {
    return $this->files;
  }
}