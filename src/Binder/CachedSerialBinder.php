<?php

namespace Phaldan\AssetBuilder\Binder;

use IteratorAggregate;
use Phaldan\AssetBuilder\Cache\Cache;
use Phaldan\AssetBuilder\Context;
use Phaldan\AssetBuilder\Processor\ProcessorList;

/**
 * This CacheBinder implementations checks all related files for modifications in a serial order.
 *
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class CachedSerialBinder extends AbstractBinder implements CachedBinder {

  /**
   * @var Cache
   */
  private $cache;

  /**
   * @var Binder
   */
  private $binder;

  /**
   * @var CacheValidator
   */
  private $validator;
  /**
   * @var Context
   */
  private $context;

  /**
   * @param Cache $cache
   * @param Binder $binder
   * @param CacheValidator $validator
   * @param Context $context
   */
  public function __construct(Cache $cache, Binder $binder, CacheValidator $validator, Context $context) {
    $this->cache = $cache;
    $this->binder = $binder;
    $this->validator = $validator;
    $this->context = $context;
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
    return $this->validator->validate($entry) ? $entry : $this->process($key, $files, $compiler);
  }

  private function process($key, $files, $compiler) {
    $result = $this->binder->bind($files, $compiler);
    $entry = new CacheBinderEntry($result, $this->binder->getFiles(), $this->binder->getLastModified());
    $entry->setMimeType($this->binder->getMimeType());
    $entry->setContext($this->context);
    $this->cache->setEntry($key, $entry);
    return $entry;
  }
}