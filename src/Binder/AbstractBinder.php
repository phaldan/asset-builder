<?php

namespace Phaldan\AssetBuilder\Binder;

use DateTime;
use IteratorAggregate;
use Phaldan\AssetBuilder\Exception;
use Phaldan\AssetBuilder\Processor\ProcessorList;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
abstract class AbstractBinder implements Binder {

  /**
   * @var DateTime
   */
  private $lastModified;
  private $mimeTypes = [];
  private $files = [];

  /**
   * @inheritdoc
   */
  public function bind(IteratorAggregate $files, ProcessorList $compiler) {
    $this->files = [];
    $this->mimeTypes = [];
    $this->lastModified = null;
    return '';
  }


  protected function addMimeType($mimeType) {
    $this->mimeTypes[$mimeType] = true;
  }

  public function getMimeType() {
    if (count($this->mimeTypes) > 1) {
      throw Exception::foundMultipleMimiTypes(array_keys($this->mimeTypes));
    }
    return key($this->mimeTypes);
  }

  protected function setLastModified(DateTime $dateTime) {
    if (is_null($this->lastModified) || !empty($dateTime->diff($this->lastModified)->format('%r'))) {
      $this->lastModified = $dateTime;
    }
  }

  /**
   * @inheritdoc
   */
  public function getLastModified() {
    return $this->lastModified;
  }

  /**
   * @inheritdoc
   */
  public function getFiles() {
    return $this->files;
  }

  /**
   * @param array $files
   */
  protected function addAllFiles(array $files) {
    $this->files = array_merge($this->files, $files);
  }

  /**
   * @param array $files
   */
  protected function setFiles(array $files) {
    $this->files = $files;
  }
}