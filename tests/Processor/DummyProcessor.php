<?php

namespace Phaldan\AssetBuilder\Processor;

use Phaldan\AssetBuilder\Processor\Minifier\JavaScriptProcessor;

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