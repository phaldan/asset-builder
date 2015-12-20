<?php

namespace Phaldan\AssetBuilder\DependencyInjection;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class IocContainer {

  private $relations = [];
  private $instances = [];
  private $generator;
  private $validator;

  public function __construct() {
    $this->validator = new Validator();
    $this->configure();
    $this->instances[get_class($this)] = $this;
    $this->instances[IocContainer::class] = $this;
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
      $concrete = $this->getConcreteClass($class);
      $this->instances[$class] = $this->generator->newInstance($concrete);
    }
  }

  private function getConcreteClass($class) {
    if (isset($this->relations[$class])) {
      return $this->relations[$class];
    } else {
      $this->validator->validateClass($class);
      return $class;
    }
  }

  /**
   * Register relationship between interface/abstract class and concrete implemented class.
   * @param string $abstractClass
   * @param object|string $concreteClass
   * @throws InvalidArgumentException
   */
  public function register($abstractClass, $concreteClass) {
    $this->validator->validateConcrete($abstractClass, $concreteClass);
    if (is_object($concreteClass)) {
      $this->instances[$abstractClass] = $concreteClass;
    } else {
      $this->relations[$abstractClass] = $concreteClass;
    }
  }
}