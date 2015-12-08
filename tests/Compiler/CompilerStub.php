<?php

namespace Phaldan\AssetBuilder\Compiler;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class CompilerStub implements Compiler {

  private $extension;

  public function getSupportedExtension() {
    return $this->extension;
  }

  public function setSupportedExtension($extension) {
    $this->extension = $extension;
  }

  private $responses = [];

  public function process($content) {
    return isset($this->responses[$content]) ? $this->responses[$content] : null;
  }

  public function set($content, $response) {
    $this->responses[$content] = $response;
  }

  public function getOutputMimeType() {
  }
}