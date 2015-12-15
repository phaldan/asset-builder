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
   * @var DateTime
   */
  private $lastModified;
  private $files = [];

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
   * @param $file
   * @return string
   */
  public function process($file) {
    $content = $this->getCacheEntry($file);
    if (is_null($content)) {
      $this->setFiles($file);
      $content = $this->executeProcessing($file);
      $this->setCacheEntry($file, $content);
    }
    return $content;
  }

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
      $this->cache->setEntry($filePath, $content);
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
   * @param $filePath
   */
  protected function setFiles($filePath) {
    $this->files = [$filePath => $this->getFileSystem()->getModifiedTime($filePath)];
  }

  /**
   * Return all related files of processed file, like all imported files from processed less or sass.
   * @return array
   */
  public function getFiles() {
    return $this->files;
  }

  /**
   * @return DateTime
   */
  public function getLastModified() {
    if (is_null($this->lastModified)) {
      throw Exception::unsetLastModified(get_class($this));
    }
    return $this->lastModified;
  }

  /**
   * @param DateTime $dateTime
   */
  protected function setLastModified(DateTime $dateTime) {
    $this->lastModified = $dateTime;
  }
}