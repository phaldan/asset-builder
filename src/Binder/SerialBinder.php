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
      $content = $this->fileSystem->getContent($file);
      $return .= $compiler->process($file, $content);
    }
    return $return;
  }
}