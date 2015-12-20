<?php

namespace Phaldan\AssetBuilder\Processor;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class ProcessorListStub extends ProcessorList {

  private $content = [];
  private $return = [];
  private $compiler = [];
  private $allCompiler = [];

  public function __construct() {
  }

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

  public function get($file) {
    return isset($this->compiler[$file]) ? $this->compiler[$file] : null;
  }

  public function set($file, $compiler) {
    $this->compiler[$file] = $compiler;
  }

  public function add(Processor $compiler) {
    $this->allCompiler[] = $compiler;
  }

  public function getAdded() {
    return $this->allCompiler;
  }
}