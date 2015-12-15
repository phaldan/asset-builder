<?php

namespace Phaldan\AssetBuilder\Processor;
use DateTime;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class ProcessorStub extends Processor {

  private $extension;
  private $mimeType;

  public function __construct() {
  }

  public function getFileExtension() {
    return $this->extension;
  }

  public function setFileExtension($extension) {
    $this->extension = $extension;
  }

  private $responses = [];

  public function process($file) {
    return isset($this->responses[$file]) ? $this->responses[$file] : null;
  }

  public function set($file, $response) {
    $this->responses[$file] = $response;
  }

  public function getOutputMimeType() {
    return $this->mimeType;
  }

  public function setOutputMimeType($mimeType) {
    $this->mimeType = $mimeType;
  }

  public function getFiles() {
  }

  public function setLastModified(DateTime $dateTime) {
    parent::setLastModified($dateTime);
  }
}