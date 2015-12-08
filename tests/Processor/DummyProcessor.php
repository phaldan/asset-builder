<?php

namespace Phaldan\AssetBuilder\Processor;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class DummyProcessor extends JavaScriptProcessor {

  /**
   * @param $content
   * @return string
   */
  public function process($content) {
    return $content;
  }
}