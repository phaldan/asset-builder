<?php

namespace Phaldan\AssetBuilder\Processor;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class DummyCompiler extends JavaScriptCompiler {

  /**
   * @param $content
   * @return string
   */
  public function process($content) {
    return $content;
  }
}