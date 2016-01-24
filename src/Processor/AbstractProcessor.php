<?php

namespace Phaldan\AssetBuilder\Processor;

use DateTime;
use Phaldan\AssetBuilder\Context;
use Phaldan\AssetBuilder\Exception;
use Phaldan\AssetBuilder\FileSystem\FileSystem;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
abstract class AbstractProcessor implements Processor {

  /**
   * @var FileSystem
   */
  private $fileSystem;
  /**
   * @var Context
   */
  private $context;

  public function __construct(FileSystem $fileSystem, Context $context) {
    $this->fileSystem = $fileSystem;
    $this->context = $context;
  }

  /**
   * @return FileSystem
   */
  protected function getFileSystem() {
    return $this->fileSystem;
  }

  /**
   * @return Context
   */
  protected function getContext() {
    return $this->context;
  }

  /**
   * @inheritdoc
   */
  public function process($filePath) {
    return $this->executeProcessing($filePath);
  }

  /**
   * @param $filePath
   * @return string
   * @throws \Exception
   */
  protected function executeProcessing($filePath) {
    throw Exception::processorOverrideNecessary(get_class($this), $filePath);
  }

  /**
   * @inheritdoc
   */
  public function getFiles($filePath) {
    return $this->processFiles($filePath);
  }

  /**
   * @param $filePath
   * @return array
   */
  protected function processFiles($filePath) {
    return [$filePath => $this->getFileSystem()->getModifiedTime($filePath)];
  }

  /**
   * @inheritdoc
   */
  public function getLastModified($filePath) {
    return $this->processLastModified($filePath);
  }

  /**
   * @param $filePath
   * @return DateTime
   */
  protected function processLastModified($filePath) {
    return $this->fileSystem->getModifiedTime($filePath);
  }
}