<?php

namespace Phaldan\AssetBuilder\Binder;

use Phaldan\AssetBuilder\Cache\Cache;
use Phaldan\AssetBuilder\Compiler\CompilerList;
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

  protected function process($file, CompilerList $compiler) {
    return $this->context->hasCache() ? $this->caching($file, $compiler) : parent::process($file, $compiler);
  }

  private function caching($file, $compiler) {
    $time = $this->fileSystem->getModifiedTime($file);
    return $this->cache->hasEntry($file, $time) ? $this->cache->getEntry($file) : parent::process($file, $compiler);
  }
}