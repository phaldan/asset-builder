<?php

namespace Phaldan\AssetBuilder\Binder;

use IteratorAggregate;
use Phaldan\AssetBuilder\Compiler\CompilerList;
use Phaldan\AssetBuilder\FileSystem\FileSystem;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class SerialBinder implements Binder {

  private $fileSystem;

  /**
   * @param FileSystem $fileSystem
   */
  public function __construct(FileSystem $fileSystem) {
    $this->fileSystem = $fileSystem;
  }

  /**
   * @param IteratorAggregate $files
   * @param CompilerList $compiler
   * @return string
   */
  public function bind(IteratorAggregate $files, CompilerList $compiler) {
    $return = '';
    foreach ($files as $file) {
      $return .= $this->process($file, $compiler);
    }
    return $return;
  }

  protected function process($file, CompilerList $list) {
    $content = $this->fileSystem->getContent($file);
    $compiler = $list->get($file);
    return $compiler->process($content);
  }
}