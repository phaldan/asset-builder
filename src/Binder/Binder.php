<?php

namespace Phaldan\AssetBuilder\Binder;

use IteratorAggregate;
use Phaldan\AssetBuilder\Processor\CompilerList;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
interface Binder {

  /**
   * @param IteratorAggregate $files
   * @param CompilerList $compiler
   * @return string
   */
  public function bind(IteratorAggregate $files, CompilerList $compiler);
}