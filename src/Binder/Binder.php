<?php

namespace Phaldan\AssetBuilder\Binder;

use IteratorAggregate;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
interface Binder {

  /**
   * @param IteratorAggregate $files
   * @return string
   */
  public function bind(IteratorAggregate $files);
}