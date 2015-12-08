<?php

namespace Phaldan\AssetBuilder\Builder;

use Phaldan\AssetBuilder\Processor\Processor;
use Phaldan\AssetBuilder\Processor\ProcessorList;
use Phaldan\AssetBuilder\DependencyInjection\IocContainer;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class CompilerHandler {

  /**
   * @var ProcessorList
   */
  private $compiler;

  /**
   * @var IocContainer
   */
  private $container;

  /**
   * @param ProcessorList $compiler
   * @param IocContainer $container
   */
  public function __construct(ProcessorList $compiler, IocContainer $container) {
    $this->compiler = $compiler;
    $this->container = $container;
  }

  /**
   * @param Processor|string $compiler
   */
  public function add($compiler) {
    $instance = $this->getInstance($compiler);
    $this->compiler->add($instance);
  }

  /**
   * @param $compiler
   * @return Processor
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
    if (!is_subclass_of($instance, Processor::class)) {
      InvalidArgumentException::createNotSubclass($instance);
    } // @codeCoverageIgnore
  }

  /**
   * @return ProcessorList
   */
  public function get() {
    return $this->compiler;
  }
}