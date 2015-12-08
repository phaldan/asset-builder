<?php

namespace Phaldan\AssetBuilder\Binder;

use IteratorAggregate;
use Phaldan\AssetBuilder\Processor\ProcessorList;
use Phaldan\AssetBuilder\FileSystem\FileSystem;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class SerialBinder extends ContentTypeBinder {

  private $fileSystem;

  /**
   * @param FileSystem $fileSystem
   */
  public function __construct(FileSystem $fileSystem) {
    $this->fileSystem = $fileSystem;
  }

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
    $content = $this->fileSystem->getContent($file);
    $compiler = $list->get($file);
    $this->addType($compiler->getOutputMimeType());
    return $compiler->process($content);
  }
}