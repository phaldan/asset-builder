<?php

namespace Phaldan\AssetBuilder\Builder;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Phaldan\AssetBuilder\Binder\Binder;
use Phaldan\AssetBuilder\Compiler\Compiler;
use Phaldan\AssetBuilder\Compiler\CompilerList;
use Phaldan\AssetBuilder\Context;

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
   * @var Context
   */
  private $context;

  /**
   * @var CompilerList
   */
  private $compiler;

  /**
   * @param Binder $binder
   * @param Context $context
   * @param CompilerList $compiler
   */
  public function __construct(Binder $binder, Context $context, CompilerList $compiler) {
    $this->binder = $binder;
    $this->context = $context;
    $this->compiler = $compiler;
    $this->groups = new ArrayIterator();
  }

  /**
   * @param string $path
   */
  public function setRootPath($path = '.') {
    $this->context->setRootPath($path);
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
   * @param bool $boolean
   * @return Builder
   */
  public function enableMinifier($boolean = true) {
    $this->context->enableMinifier($boolean);
    return $this;
  }

  /**
   * @param bool $boolean
   * @return Builder
   */
  public function enableDebug($boolean = true) {
    $this->context->enableDebug($boolean);
    return $this;
  }

  /**
   * @param bool $boolean
   * @return Builder
   */
  public function enableStopWatch($boolean = true) {
    $this->context->enableStopWatch($boolean);
    return $this;
  }

  /**
   * @param Compiler $compiler
   * @return Builder
   */
  public function addCompiler(Compiler $compiler) {
    $this->compiler->add($compiler);
  }

  /**
   * @param $path
   * @return Builder
   */
  public function setCachePath($path = null) {
    $this->context->setCachePath($path);
    return $this;
  }

  /**
   * @param $group
   * @return string
   */
  public function execute($group) {
    if (!$this->groups->offsetExists($group)) {
      Exception::createGroupNotFound($group);
    } // @codeCoverageIgnore
    $files = $this->groups->offsetGet($group);
    return $this->binder->bind($files, $this->compiler);
  }
}