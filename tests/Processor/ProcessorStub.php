<?php

namespace Phaldan\AssetBuilder\Processor;
use DateTime;
use Phaldan\AssetBuilder\Cache\CacheMock;
use Phaldan\AssetBuilder\Context;
use Phaldan\AssetBuilder\FileSystem\FileSystemMock;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class ProcessorStub extends Processor {

  private $extension;
  private $mimeType;
  private $lastModified;

  public function __construct() {
    parent::__construct(new FileSystemMock(), new CacheMock(), new Context());
  }

  public function getFileExtension() {
    return $this->extension;
  }

  public function setFileExtension($extension) {
    $this->extension = $extension;
  }

  private $responses = [];

  protected function executeProcessing($filePath) {
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

  protected function processLastModified($filePath) {
    return $this->lastModified;
  }

  public function setLastModified(DateTime $dateTime) {
    $this->lastModified = $dateTime;
  }
}