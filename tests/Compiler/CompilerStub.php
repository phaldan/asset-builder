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

  public function process($content) {
  }
}