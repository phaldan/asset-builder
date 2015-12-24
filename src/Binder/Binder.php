<?php

namespace Phaldan\AssetBuilder\Binder;

use IteratorAggregate;
use Phaldan\AssetBuilder\Processor\ProcessorList;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
interface Binder {

  /**
   * @param IteratorAggregate $files
   * @param ProcessorList $compiler
   * @return string
   */
  public function bind(IteratorAggregate $files, ProcessorList $compiler);

  /**
   * Returns all related files of the last bind execution.
   * As minimum the files from the bind call will be returned,
   * but also all imported files on compiling LESS or SASS to CSS
   * @return array
   */
  public function getFiles();
}