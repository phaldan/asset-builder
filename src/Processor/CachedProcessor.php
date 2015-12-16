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
abstract class CachedProcessor implements Processor {

  const MESSAGE_EXCEPTION = 'Please provide an implementation for executeProcessing(...) method';

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
   * @param $filePath
   * @return string
   * @throws \Exception
   */
  protected function executeProcessing($filePath) {
    throw Exception::processorOverrideNecessary(get_class($this));
  }

  /**
   * @param $filePath
   * @return array
   */
  protected function processFiles($filePath) {
    return [$filePath => $this->getFileSystem()->getModifiedTime($filePath)];
  }

  /**
   * @param $filePath
   * @return DateTime
   */
  protected function processLastModified($filePath) {
    return $this->fileSystem->getModifiedTime($filePath);
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
   * @inheritdoc
   */
  public final function process($filePath) {
    $cache = $this->getCacheEntry($filePath);
    if (is_null($cache)) {
      $content = $this->executeProcessing($filePath);
      $this->setCacheEntry($filePath, $content);
      return $content;
    } else {
      return $cache->getContent();
    }
  }

  /**
   * @param $filePath
   * @return null|CacheEntry
   */
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
      $entry = new CacheEntry($content, $this->getFiles($filePath), $this->getLastModified($filePath));
      $this->cache->setEntry($filePath, $entry);
    }
  }

  /**
   * @return Context
   */
  protected function getContext() {
    return $this->context;
  }

  /**
   * @return FileSystem
   */
  protected function getFileSystem() {
    return $this->fileSystem;
  }

  /**
   * @inheritdoc
   */
  public final function getFiles($filePath) {
    return $this->processFiles($filePath);
  }

  /**
   * @inheritdoc
   */
  public final function getLastModified($filePath) {
    return $this->processLastModified($filePath);
  }
}