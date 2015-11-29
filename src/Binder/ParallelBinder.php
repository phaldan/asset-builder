<?php

namespace Phaldan\AssetBuilder\Binder;

use IteratorAggregate;
use Phaldan\AssetBuilder\Compiler\CompilerList;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class ParallelBinder implements Binder {

  /**
   * @param IteratorAggregate $files
   * @param CompilerList $compiler
   * @return string
   */
  public function bind(IteratorAggregate $files, CompilerList $compiler) {
  }
}