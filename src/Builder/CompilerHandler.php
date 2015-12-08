<?php

namespace Phaldan\AssetBuilder\Builder;

use Phaldan\AssetBuilder\Processor\Compiler;
use Phaldan\AssetBuilder\Processor\CompilerList;
use Phaldan\AssetBuilder\DependencyInjection\IocContainer;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class CompilerHandler {

  /**
   * @var CompilerList
   */
  private $compiler;

  /**
   * @var IocContainer
   */
  private $container;

  /**
   * @param CompilerList $compiler
   * @param IocContainer $container
   */
  public function __construct(CompilerList $compiler, IocContainer $container) {
    $this->compiler = $compiler;
    $this->container = $container;
  }

  /**
   * @param Compiler|string $compiler
   */
  public function add($compiler) {
    $instance = $this->getInstance($compiler);
    $this->compiler->add($instance);
  }

  /**
   * @param $compiler
   * @return Compiler
   */
  private function getInstance($compiler) {
    $instance = $this->processInstance($compiler);
    $this->validateInstance($instance);
    return $instance;
  }

  private function processInstance($compiler) {
    if (is_object($compiler)) {
      return $compiler;
    } elseif (class_exists($compiler)) {
      return $this->container->getInstance($compiler);
    }
    InvalidArgumentException::createNeitherObjectOrClass($compiler);
  } // @codeCoverageIgnore

  private function validateInstance($instance) {
    if (!is_subclass_of($instance, Compiler::class)) {
      InvalidArgumentException::createNotSubclass($instance);
    } // @codeCoverageIgnore
  }

  /**
   * @return CompilerList
   */
  public function get() {
    return $this->compiler;
  }
}