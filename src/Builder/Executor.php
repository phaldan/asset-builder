<?php

namespace Phaldan\AssetBuilder\Builder;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Phaldan\AssetBuilder\Binder\Binder;
use Phaldan\AssetBuilder\Binder\CachedBinder;
use Phaldan\AssetBuilder\Context;
use Phaldan\AssetBuilder\DependencyInjection\IocContainer;
use Phaldan\AssetBuilder\Exception;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class Executor {

  const HEADER_TIMING = 'X-Runtime: %s';
  const COMMENT_TIMING = '/*! Runtime: %s */';

  /**
   * @var IocContainer
   */
  private $container;

  /**
   * @var ArrayAccess
   */
  private $groups;

  /**
   * @var Context
   */
  private $context;

  /**
   * @param IocContainer $container
   * @param Context $context
   */
  public function __construct(IocContainer $container, Context $context) {
    $this->container = $container;
    $this->context = $context;
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
    $start = $this->startTimer();
    $result = $this->process($group, $compiler);
    $runtime = $this->stopTimer($start);
    return $this->createRuntimeComment($runtime) . $result;
  }

  private function process($group, CompilerHandler $compiler) {
    if (!$this->groups->offsetExists($group)) {
      throw Exception::groupNotFound($group);
    }
    $files = $this->groups->offsetGet($group);
    return $this->getBinder()->bind($files, $compiler->get());
  }

  private function getBinder() {
    $class = $this->context->hasCache() ? CachedBinder::class : Binder::class;
    return $this->container->getInstance($class);
  }

  private function startTimer() {
    return $this->context->hasStopWatch() ? microtime(true) : null;
  }

  private function stopTimer($startTime) {
    if ($this->context->hasStopWatch()) {
      $time = microtime(true) - $startTime;
      $formatted = number_format($time, 3);
      header(sprintf(self::HEADER_TIMING, $formatted));
      return $formatted;
    }
    return null;
  }

  private function createRuntimeComment($runtime) {
    if ($this->context->hasStopWatch()) {
      return sprintf(self::COMMENT_TIMING, $runtime);
    }
    return '';
  }
}