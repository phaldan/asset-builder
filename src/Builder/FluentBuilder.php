<?php

namespace Phaldan\AssetBuilder\Builder;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Phaldan\AssetBuilder\Binder\Binder;
use Processor\Processor;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class FluentBuilder implements Builder {

  /**
   * @var Binder
   */
  private $binder;

  /**
   * @var ArrayAccess
   */
  private $groups;

  /**
   * @param Binder $binder
   */
  public function __construct(Binder $binder) {
    $this->binder = $binder;
    $this->groups = new ArrayIterator();
  }

  /**
   * @param string $path
   */
  public function setRootPath($path = '.') {
  }

  /**
   * @param $name
   * @param IteratorAggregate $files
   * @return Builder
   */
  public function addGroup($name, IteratorAggregate $files) {
    $this->groups->offsetSet($name, $files);
  }

  /**
   * @param ArrayAccess $groups
   * @return Builder
   */
  public function addGroups(ArrayAccess $groups) {
    $this->groups = $groups;
  }

  /**
   * @param bool|true $boolean
   * @return Builder
   */
  public function enableMinifier($boolean = true) {
  }

  /**
   * @param bool|true $boolean
   * @return Builder
   */
  public function enableDebug($boolean = true) {
  }

  /**
   * @param bool|true $boolean
   * @return Builder
   */
  public function enableStopWatch($boolean = true) {
  }

  /**
   * @param Processor $processor
   * @return Builder
   */
  public function setCssProcessor(Processor $processor) {
  }

  /**
   * @param Processor $processor
   * @return Builder
   */
  public function setJsProcessor(Processor $processor) {
  }

  /**
   * @param $path
   * @return Builder
   */
  public function setCachePath($path = null) {
  }

  /**
   * @param $group
   * @return string
   */
  public function execute($group) {
    if (!$this->groups->offsetExists($group)) {
      Exception::createGroupNotFound($group);
    }
    $files = $this->groups->offsetGet($group);
    return $this->binder->bind($files);
  }
}