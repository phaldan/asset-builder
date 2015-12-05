<?php

namespace Phaldan\AssetBuilder\Builder;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Phaldan\AssetBuilder\Binder\Binder;
use Phaldan\AssetBuilder\Compiler\Compiler;
use Phaldan\AssetBuilder\Compiler\CompilerList;
use Phaldan\AssetBuilder\Context;
use Phaldan\AssetBuilder\DependencyInjection\IocContainer;

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
   * @var CompilerHandler
   */
  private $compiler;

  /**
   * @param Binder $binder
   * @param Context $context
   * @param CompilerHandler $compiler
   */
  public function __construct(Binder $binder, Context $context, CompilerHandler $compiler) {
    $this->binder = $binder;
    $this->context = $context;
    $this->compiler = $compiler;
    $this->groups = new ArrayIterator();
  }

  /**
   * @inheritdoc
   */
  public function setRootPath($path = '.') {
    $this->context->setRootPath($path);
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function addGroup($name, IteratorAggregate $files) {
    $this->groups->offsetSet($name, $files);
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function addGroups(ArrayAccess $groups) {
    $this->groups = $groups;
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function enableMinifier($boolean = true) {
    $this->context->enableMinifier($boolean);
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function enableDebug($boolean = true) {
    $this->context->enableDebug($boolean);
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function enableStopWatch($boolean = true) {
    $this->context->enableStopWatch($boolean);
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function addCompiler($compiler) {
    $this->compiler->add($compiler);
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function setCachePath($path = null) {
    $this->context->setCachePath($path);
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function execute($group) {
    if (!$this->groups->offsetExists($group)) {
      Exception::createGroupNotFound($group);
    } // @codeCoverageIgnore
    $files = $this->groups->offsetGet($group);
    return $this->binder->bind($files, $this->compiler->get());
  }
}