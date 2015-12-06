<?php

namespace Phaldan\AssetBuilder\Builder;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Phaldan\AssetBuilder\Binder\Binder;
use Phaldan\AssetBuilder\Context;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class Executor {

  const HEADER_TIMING = 'X-Runtime: %s';
  const COMMENT_TIMING = '/*! Runtime: %s */';

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
   * @param Binder $binder
   * @param Context $context
   */
  public function __construct(Binder $binder, Context $context) {
    $this->binder = $binder;
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
      Exception::createGroupNotFound($group);
    } // @codeCoverageIgnore
    $files = $this->groups->offsetGet($group);
    return $this->binder->bind($files, $compiler->get());
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