<?php

namespace Phaldan\AssetBuilder\Compiler;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class CompilerList {

  private $list = [];

  /**
   * @param Compiler $compiler
   */
  public function add(Compiler $compiler) {
    $this->list[$compiler->getSupportedExtension()] = $compiler;
  }

  /**
   * @param $file
   * @return Compiler|null
   */
  public function get($file) {
    $extension = pathinfo($file, PATHINFO_EXTENSION);
    return isset($this->list[$extension]) ? $this->list[$extension] : null;
  }

  /**
   * @param $file
   * @param $content
   * @return null|string
   */
  public function process($file, $content) {
    $compiler = $this->get($file);
    return is_null($compiler) ? null : $compiler->process($content);
  }
}