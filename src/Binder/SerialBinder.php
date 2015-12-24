<?php

namespace Phaldan\AssetBuilder\Binder;

use IteratorAggregate;
use Phaldan\AssetBuilder\Processor\ProcessorList;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class SerialBinder extends AbstractBinder {

  /**
   * @inheritdoc
   */
  public function bind(IteratorAggregate $files, ProcessorList $compiler) {
    $return = parent::bind($files, $compiler);
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
    $this->addAllFiles($processor->getFiles($file));
    return $result;
  }
}