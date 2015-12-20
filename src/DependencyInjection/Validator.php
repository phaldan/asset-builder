<?php

namespace Phaldan\AssetBuilder\DependencyInjection;

use ReflectionClass;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class Validator {

  /**
   * @param $class
   */
  public function validateClass($class) {
    $reflection = new ReflectionClass($class);
    if ($reflection->isInterface() || $reflection->isAbstract()) {
      throw InvalidArgumentException::classIsAbstractOrInterface($class);
    }
  }

  /**
   * @param $abstractClass
   * @param $concreteClass
   */
  public function validateConcrete($abstractClass, $concreteClass) {
    $reflection = new ReflectionClass($concreteClass);
    if (!$reflection->isSubclassOf($abstractClass) && $abstractClass != $reflection->getName()) {
      throw InvalidArgumentException::notSubclass($concreteClass, $abstractClass);
    }
    if ($reflection->isInterface() || $reflection->isAbstract()) {
      throw InvalidArgumentException::couldNotCreateConcreteInstance($concreteClass);
    }
  }
}