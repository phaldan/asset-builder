<?php

namespace Phaldan\AssetBuilder\Processor;

use DateTime;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class ProcessorStub implements Processor {

  private $extension;
  private $mimeType;
  private $responses = [];
  private $lastModified = [];
  private $files = [];

  public function __construct() {
  }

  public function getFileExtension() {
    return $this->extension;
  }

  public function setFileExtension($extension) {
    $this->extension = $extension;
  }

  public function process($filePath) {
    return isset($this->responses[$filePath]) ? $this->responses[$filePath] : null;
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

  public function getLastModified($filePath) {
    return isset($this->lastModified[$filePath]) ? $this->lastModified[$filePath] : null;
  }

  public function setLastModified($filePath, DateTime $dateTime) {
    $this->lastModified[$filePath] = $dateTime;
  }

  public function getFiles($filePath) {
    return isset($this->files[$filePath]) ? $this->files[$filePath] : null;
  }

  public function setFiles($filePath, array $files) {
    $this->files[$filePath] = $files;
  }
}