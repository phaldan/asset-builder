<?php

namespace Phaldan\AssetBuilder\Group;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class FileList implements IteratorAggregate {

  private $array = null;

  /**
   * @param array $array
   */
  public function __construct(array $array = []) {
    $this->array = new ArrayIterator($array);
  }

  /**
   * @return Traversable An instance of an object implementing <b>Iterator</b> or
   */
  public function getIterator() {
    return $this->array;
  }

  /**
   * @param $file
   */
  public function add($file) {
    $this->array->append($file);
  }

  /**
   * @param array $files
   */
  public function addAll(array $files) {
    foreach ($files as $file) {
      $this->add($file);
    }
  }
}