<?php

namespace Phaldan\AssetBuilder\Binder;

use IteratorAggregate;
use SplObjectStorage;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class BinderStub implements Binder {

  /**
   * @var SplObjectStorage
   */
  private $list;

  public function __construct() {
    $this->list = new SplObjectStorage();
  }


  /**
   * @param IteratorAggregate $files
   * @return string
   */
  public function bind(IteratorAggregate $files) {
    return $this->list->offsetExists($files) ? $this->list->offsetGet($files) : null;
  }

  public function set(IteratorAggregate $files, $return) {
    $this->list->offsetSet($files, $return);
  }
}