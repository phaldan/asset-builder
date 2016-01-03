<?php

namespace Phaldan\AssetBuilder\Binder;

use IteratorAggregate;
use Phaldan\AssetBuilder\Cache\Cache;
use Phaldan\AssetBuilder\Processor\ProcessorList;
use Phaldan\AssetBuilder\FileSystem\FileSystem;

/**
 * This CacheBinder implementations checks all related files for modifications in a serial order.
 *
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class CachedSerialBinder extends AbstractBinder implements CachedBinder {

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

  /**
   * @param FileSystem $fileSystem
   * @param Cache $cache
   * @param Binder $binder
   */
  public function __construct(FileSystem $fileSystem, Cache $cache, Binder $binder) {
    $this->fileSystem = $fileSystem;
    $this->cache = $cache;
    $this->binder = $binder;
  }

  /**
   * @inheritdoc
   */
  public function bind(IteratorAggregate $files, ProcessorList $compiler) {
    parent::bind($files, $compiler);
    $key = $this->generateCacheKey($files);
    $entry = $this->getEntry($key, $files, $compiler);
    $this->update($entry);
    return $entry->getContent();
  }

  private function getEntry($key, IteratorAggregate $files, ProcessorList $compiler) {
    if ($this->cache->hasEntry($key)) {
      return $this->requestCache($key, $files, $compiler);
    } else {
      return $this->process($key, $files, $compiler);
    }
  }

  private function update(CacheBinderEntry $entry) {
    $this->setFiles($entry->getFiles());
    $this->setLastModified($entry->getLastModified());
    $this->addMimeType($entry->getMimeType());
  }

  public function generateCacheKey(IteratorAggregate $files) {
    $key = '';
    foreach ($files as $file) {
      $key .= $file;
    }
    return $key;
  }

  private function requestCache($key, $files, $compiler) {
    $cache = new CacheBinderEntry();
    $entry = $cache->unserialize($this->cache->getEntry($key));
    return $this->validateCache($entry) ? $entry : $this->process($key, $files, $compiler);
  }

  private function validateCache(CacheBinderEntry $entry) {
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
    $entry = new CacheBinderEntry($result, $this->binder->getFiles(), $this->binder->getLastModified());
    $entry->setMimeType($this->binder->getMimeType());
    $this->cache->setEntry($key, $entry);
    return $entry;
  }
}