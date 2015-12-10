<?php

namespace Phaldan\AssetBuilder\Binder;

use IteratorAggregate;
use Phaldan\AssetBuilder\Processor\ProcessorList;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class SerialBinder extends ContentTypeBinder {

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
    $this->outputContentTypeHeader();
    return $return;
  }

  protected function process($file, ProcessorList $list) {
    $compiler = $list->get($file);
    $this->addType($compiler->getOutputMimeType());
    return $compiler->process($file);
  }
}