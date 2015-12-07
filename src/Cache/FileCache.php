<?php

namespace Phaldan\AssetBuilder\Cache;

use DateTime;
use Phaldan\AssetBuilder\Context;
use Phaldan\AssetBuilder\FileSystem\FileSystem;

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
    $this->fileSystem->setContent($filePath, $value);
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
    return is_null($expire) ? $exists : $this->checkExpire($filePath, $expire);
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