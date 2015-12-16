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
abstract class Processor {

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
   * Returns file extension of supported files
   * @return string
   */
  public abstract function getFileExtension();

  /**
   * @return string
   */
  public abstract function getOutputMimeType();

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
   * Transform to native language like CSS or JavaScript, and compress
   * @param $filePath
   * @return string
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
   * Return all related files of processed file, like all imported files from processed less or sass.
   * @param $filePath
   * @return array
   */
  public final function getFiles($filePath) {
    return $this->processFiles($filePath);
  }

  /**
   * @param $filePath
   * @return DateTime
   */
  public final function getLastModified($filePath) {
    return $this->processLastModified($filePath);
  }
}