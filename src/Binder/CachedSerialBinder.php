<?php

namespace Phaldan\AssetBuilder\Binder;

use Phaldan\AssetBuilder\Cache\Cache;
use Phaldan\AssetBuilder\Processor\ProcessorList;
use Phaldan\AssetBuilder\Context;
use Phaldan\AssetBuilder\FileSystem\FileSystem;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class CachedSerialBinder extends SerialBinder {

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

  public function __construct(FileSystem $fileSystem, Cache $cache, Context $context) {
    parent::__construct($fileSystem);
    $this->fileSystem = $fileSystem;
    $this->cache = $cache;
    $this->context = $context;
  }

  protected function process($file, ProcessorList $compiler) {
    return $this->context->hasCache() ? $this->caching($file, $compiler) : parent::process($file, $compiler);
  }

  private function caching($file, $compiler) {
    $time = $this->fileSystem->getModifiedTime($file);
    return $this->cache->hasEntry($file, $time) ? $this->cache->getEntry($file) : $this->set($file, $compiler);
  }

  private function set($file, $compiler) {
    $content = parent::process($file, $compiler);
    $this->cache->setEntry($file, $content);
    return $content;
  }
}