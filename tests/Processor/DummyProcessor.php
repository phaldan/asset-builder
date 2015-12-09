<?php

namespace Phaldan\AssetBuilder\Processor;

use Phaldan\AssetBuilder\Processor\Minifier\JavaScriptProcessor;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class DummyProcessor extends JavaScriptProcessor {

  public function __construct() {
  }

  /**
   * @param $file
   * @return string
   */
  public function process($file) {
    return $file;
  }
}