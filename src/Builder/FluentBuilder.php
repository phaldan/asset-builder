<?php

namespace Phaldan\AssetBuilder\Builder;

use ArrayAccess;
use IteratorAggregate;
use Phaldan\AssetBuilder\Context;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class FluentBuilder implements Builder {

  /**
   * @var Executor
   */
  private $executor;

  /**
   * @var Context
   */
  private $context;

  /**
   * @var CompilerHandler
   */
  private $compiler;

  /**
   * @param Executor $executor
   * @param Context $context
   * @param CompilerHandler $compiler
   */
  public function __construct(Executor $executor, Context $context, CompilerHandler $compiler) {
    $this->executor = $executor;
    $this->context = $context;
    $this->compiler = $compiler;
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
    $this->executor->addGroup($name, $files);
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function addGroups(ArrayAccess $groups) {
    $this->executor->addGroups($groups);
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
    return $this->executor->execute($group, $this->compiler);
  }
}