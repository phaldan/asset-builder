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

  protected function executeProcessing($filePath) {
    return isset($this->returns[$filePath]) ? $this->returns[$filePath] : null;
  }

  public function setExecuteProcessingReturn($file, $return) {
    $this->returns[$file] = $return;
  }

  public function getContent($filePath) {
    return parent::getContent($filePath);
  }
}