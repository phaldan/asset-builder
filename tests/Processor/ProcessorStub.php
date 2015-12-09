<?php

namespace Phaldan\AssetBuilder\Processor;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class ProcessorStub extends Processor {

  private $extension;
  private $mimeType;

  public function __construct() {
  }

  public function getSupportedExtension() {
    return $this->extension;
  }

  public function setSupportedExtension($extension) {
    $this->extension = $extension;
  }

  private $responses = [];

  public function process($file) {
    return isset($this->responses[$file]) ? $this->responses[$file] : null;
  }

  public function set($content, $response) {
    $this->responses[$content] = $response;
  }

  public function getOutputMimeType() {
    return $this->mimeType;
  }

  public function setOutputMimeType($mimeType) {
    $this->mimeType = $mimeType;
  }
}