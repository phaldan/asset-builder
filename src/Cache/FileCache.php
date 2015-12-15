<?php

namespace Phaldan\AssetBuilder\Cache;

use DateTime;
use Phaldan\AssetBuilder\Context;
use Phaldan\AssetBuilder\FileSystem\FileSystem;
use Serializable;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class FileCache implements Cache {

  const FILENAME_FORMAT = 'pas-%s.cache';

  /**
   * @var Context
   */
  private $context;
  /**
   * @var FileSystem
   */
  private $fileSystem;

  /**
   * @param Context $context
   * @param FileSystem $fileSystem
   */
  public function __construct(Context $context, FileSystem $fileSystem) {
    $this->context = $context;
    $this->fileSystem = $fileSystem;
  }

  /**
   * @inheritdoc
   */
  public function setEntry($key, $value) {
    $filePath = $this->getFilePath($key);
    $this->fileSystem->setContent($filePath, $this->serialize($value));
  }

  private function serialize($value) {
    return (is_object($value) && $value instanceof Serializable) ? $value->serialize() : $value;
  }

  /**
   * @inheritdoc
   */
  public function getEntry($key) {
    $filePath = $this->getFilePath($key);
    return $this->fileSystem->getContent($filePath);
  }

  /**
   * @inheritdoc
   */
  public function hasEntry($key, DateTime $expire = null) {
    $filePath = $this->getFilePath($key);
    $exists = $this->fileSystem->exists($filePath);
    return $exists && !is_null($expire) ? $this->checkExpire($filePath, $expire) : $exists;
  }

  private function checkExpire($filePath, DateTime $expire) {
    return $this->fileSystem->getModifiedTime($filePath)->getTimestamp() >= $expire->getTimestamp();
  }

  /**
   * @param $key
   * @return string
   */
  public function getFilePath($key) {
    return $this->context->getCachePath() . sprintf(self::FILENAME_FORMAT, md5($key));
  }
}