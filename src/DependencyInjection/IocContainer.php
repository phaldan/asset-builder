<?php

namespace Phaldan\AssetBuilder\DependencyInjection;

use ReflectionClass;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class IocContainer {

  private $relations = [];
  private $instances = [];
  private $generator;

  public function __construct() {
    $this->configure();
    $this->instances[get_class($this)] = $this;
    $this->generator = new Generator($this);
  }

  /**
   * Placeholder. Can be be overwritten by child class. Will be called at constructor.
   */
  public function configure() {
  }

  /**
   * @param $class
   * @return object
   * @throws InvalidArgumentException
   */
  public function getInstance($class) {
    $this->create($class);
    return $this->instances[$class];
  }

  private function create($class) {
    if (!isset($this->instances[$class])) {
      $concrete = isset($this->relations[$class]) ? $this->relations[$class] : $this->validateClass($class);
      $this->instances[$class] = $this->generator->newInstance($concrete);
    }
  }

  private function validateClass($class) {
    $reflection = new ReflectionClass($class);
    if ($reflection->isInterface() || $reflection->isAbstract()) {
      InvalidArgumentException::createNewInstance($class);
    }
    return $class;
  }

  /**
   * Register relationship between interface/abstract class and concrete implemented class.
   * @param string $abstractClass
   * @param object|string $concreteClass
   * @throws InvalidArgumentException
   */
  public function register($abstractClass, $concreteClass) {
    $this->validateConcrete($abstractClass, $concreteClass);
    if (is_object($concreteClass)) {
      $this->instances[$abstractClass] = $concreteClass;
    } else {
      $this->relations[$abstractClass] = $concreteClass;
    }
  }

  private function validateConcrete($abstractClass, $concreteClass) {
    $reflection = new ReflectionClass($concreteClass);
    if (!$reflection->isSubclassOf($abstractClass) && $abstractClass != $reflection->getName()) {
      InvalidArgumentException::createNotSubclass($concreteClass, $abstractClass);
    }
    if ($reflection->isInterface() || $reflection->isAbstract()) {
      InvalidArgumentException::createNotConcrete($concreteClass);
    }
  }
}