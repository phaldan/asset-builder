<?php

namespace Phaldan\AssetBuilder\Processor;

use Phaldan\AssetBuilder\Processor\Minifier\JavaScriptProcessor;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class DummyProcessor extends JavaScriptProcessor {

  public function __construct() {
  }

  public function process($file) {
    return $file;
  }

  public function getFiles() {
  }

  public function executeProcessing($filePath) {
    parent::executeProcessing($filePath);
  }
}