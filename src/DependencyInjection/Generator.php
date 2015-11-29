<?php

namespace Phaldan\AssetBuilder\DependencyInjection;

use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class Generator {

  private $container;
  private $dependencyChain;

  public function __construct(IocContainer $container) {
    $this->container = $container;
    $this->dependencyChain = new Stack();
  }

  public function newInstance($class) {
    $reflection = new ReflectionClass($class);
    $constructor = $reflection->getConstructor();
    return is_null($constructor) ? $reflection->newInstance() : $this->instanceWithParameter($reflection, $constructor);
  }

  private function instanceWithParameter(ReflectionClass $class, ReflectionMethod $constructor) {
    $this->dependencyChain->push($class->getName());
    $parameters = $this->getParameters($class, $constructor);
    $this->dependencyChain->pop();
    return $class->newInstanceArgs($parameters);
  }

  private function getParameters(ReflectionClass $class, ReflectionMethod $method) {
    $array = [];
    foreach ($method->getParameters() as $parameter) {
      $array[] = $this->getParameterInstance($class, $parameter);
    }
    return $array;
  }

  private function getParameterInstance(ReflectionClass $class, ReflectionParameter $parameter) {
    $this->validateParameter($class, $parameter);
    return $this->container->getInstance($parameter->getClass()->getName());
  }

  private function validateParameter(ReflectionClass $class, ReflectionParameter $parameter) {
    if (is_null($parameter->getClass())) {
      InvalidArgumentException::createStaticParameter($class->getName(), $parameter->getName());
    } // @codeCoverageIgnore
    if ($this->dependencyChain->contains($parameter->getClass()->getName())) {
      $this->dependencyChain->push($parameter->getClass()->getName());
      InvalidArgumentException::createDependencyLoop($parameter->getName(), $class->getName(), $this->dependencyChain);
    } // @codeCoverageIgnore
  }
}