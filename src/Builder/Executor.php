<?php

namespace Phaldan\AssetBuilder\Builder;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Phaldan\AssetBuilder\Binder\Binder;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class Executor {

  /**
   * @var Binder
   */
  private $binder;

  /**
   * @var ArrayAccess
   */
  private $groups;

  public function __construct(Binder $binder) {
    $this->binder = $binder;
    $this->groups = new ArrayIterator();
  }

  /**
   * @param $name
   * @param IteratorAggregate $files
   * @return $this
   */
  public function addGroup($name, IteratorAggregate $files) {
    $this->groups->offsetSet($name, $files);
    return $this;
  }

  /**
   * @param ArrayAccess $groups
   * @return $this
   */
  public function addGroups(ArrayAccess $groups) {
    $this->groups = $groups;
    return $this;
  }

  /**
   * @param $group
   * @param CompilerHandler $compiler
   * @return string
   * @throws Exception
   */
  public function execute($group, CompilerHandler $compiler) {
    if (!$this->groups->offsetExists($group)) {
      Exception::createGroupNotFound($group);
    } // @codeCoverageIgnore
    $files = $this->groups->offsetGet($group);
    return $this->binder->bind($files, $compiler->get());
  }
}