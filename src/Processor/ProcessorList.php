<?php

namespace Phaldan\AssetBuilder\Processor;

use Phaldan\AssetBuilder\Cache\Cache;
use Phaldan\AssetBuilder\Context;
use Phaldan\AssetBuilder\FileSystem\FileSystem;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class ProcessorList {

  /**
   * @var Context
   */
  private $context;
  private $cache;
  private $fileSystem;
  private $list = [];

  /**
   * @param Context $context
   * @param Cache $cache
   * @param FileSystem $fileSystem
   */
  public function __construct(Context $context, Cache $cache, FileSystem $fileSystem) {
    $this->context = $context;
    $this->cache = $cache;
    $this->fileSystem = $fileSystem;
  }

  /**
   * @param Processor $compiler
   */
  public function add(Processor $compiler) {
    $this->list[$compiler->getFileExtension()] = $compiler;
  }

  /**
   * @param $file
   * @return Processor|null
   */
  public function get($file) {
    $extension = pathinfo($file, PATHINFO_EXTENSION);
    return isset($this->list[$extension]) ? $this->getEntry($extension) : null;
  }

  private function getEntry($extension) {
    $entry = $this->list[$extension];
    if ($this->context->hasCache() && !($entry instanceof CachedProcessor)) {
      $this->list[$extension] = new CachedProcessor($entry, $this->cache, $this->fileSystem);
    }
    return $this->list[$extension];
  }

  /**
   * @param $file
   * @param $content
   * @return null|string
   */
  public function process($file, $content) {
    $compiler = $this->get($file);
    return is_null($compiler) ? null : $compiler->process($content);
  }
}