<?php

namespace Phaldan\AssetBuilder\Binder;

use DateTime;
use IteratorAggregate;
use Phaldan\AssetBuilder\Processor\ProcessorList;
use SplObjectStorage;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class BinderStub implements Binder {

  /**
   * @var SplObjectStorage
   */
  private $list = [];
  private $files;
  private $lastModified;

  /**
   * @param IteratorAggregate $files
   * @param ProcessorList $compiler
   * @return string
   */
  public function bind(IteratorAggregate $files, ProcessorList $compiler) {
    $key = json_encode(iterator_to_array($files));
    return isset($this->list[$key]) ? $this->list[$key] : null;
  }

  public function set(IteratorAggregate $files, $return) {
    $key = json_encode(iterator_to_array($files));
    $this->list[$key] = $return;
  }

  /**
   * @inheritdoc
   */
  public function getFiles() {
    return $this->files;
  }

  public function setFiles(array $files) {
    $this->files = $files;
  }

  /**
   * @inheritdoc
   */
  public function getLastModified() {
    return $this->lastModified;
  }

  public function setLastModified(DateTime $dateTime) {
    $this->lastModified = $dateTime;
  }
}