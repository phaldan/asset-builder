<?php

namespace Phaldan\AssetBuilder\Processor;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class ProcessorImpl extends Processor {

  private $returns = [];

  public function getSupportedExtension() {
  }

  public function getOutputMimeType() {
  }

  protected function executeProcessing($file) {
    return isset($this->returns[$file]) ? $this->returns[$file] : null;
  }

  public function setExecuteProcessingReturn($file, $return) {
    $this->returns[$file] = $return;
  }
}