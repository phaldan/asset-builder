<?php

namespace Phaldan\AssetBuilder\Binder;

use IteratorAggregate;
use Phaldan\AssetBuilder\Processor\ProcessorList;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class SerialBinder extends HttpHeaderBinder {

  /**
   * @param IteratorAggregate $files
   * @param ProcessorList $compiler
   * @return string
   */
  public function bind(IteratorAggregate $files, ProcessorList $compiler) {
    $return = '';
    foreach ($files as $file) {
      $return .= $this->process($file, $compiler);
    }
    $this->processHttpHeader();
    return $return;
  }

  protected function process($file, ProcessorList $list) {
    $processor = $list->get($file);
    $result = $processor->process($file);
    $this->addMimeType($processor->getOutputMimeType());
    $this->setLastModified($processor->getLastModified($file));
    return $result;
  }
}