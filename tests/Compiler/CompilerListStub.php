<?php

namespace Phaldan\AssetBuilder\Compiler;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class CompilerListStub extends CompilerList {

  private $content = [];
  private $return = [];

  public function process($file, $content) {
    $this->content[$file] = $content;
    return isset($this->return[$file]) ? $this->return[$file] : null;
  }

  public function setProcessReturn($file, $return) {
    $this->return[$file] = $return;
  }

  public function getProcessContent($file) {
    return isset($this->content[$file]) ? $this->content[$file] : null;
  }
}