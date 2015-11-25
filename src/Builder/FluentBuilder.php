<?php

namespace Phaldan\AssetBuilder\Builder;

use Iterator;
use IteratorAggregate;
use Processor\Processor;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class FluentBuilder implements Builder {

  /**
   * @param string $path
   */
  public function __construct($path = '.') {
  }

  /**
   * @param $name
   * @param IteratorAggregate $files
   * @return Builder
   */
  public function addGroup($name, IteratorAggregate $files) {
  }

  /**
   * @param Iterator $groups
   * @return Builder
   */
  public function addGroups(Iterator $groups) {
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
  public function setCacheDir($path = null) {
  }

  /**
   * @param $group
   */
  public function execute($group) {
  }
}