<?php

namespace Phaldan\AssetBuilder\DependencyInjection;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class InvalidArgumentException extends \InvalidArgumentException {

  const MESSAGE_REGISTER = "Could not create a new instance. '%s' is an interface or abstract class. You have to register a class of a concrete implementation.";
  const MESSAGE_NOT_SUBCLASS = "Registration failed. '%s' is not a subclass of '%s'.";
  const MESSAGE_NOT_CONCRETE = "'%s' is an interface or abstract class and cannot be used as concrete class.";
  const MESSAGE_STATIC_PARAMETER = "Please extend '$%s' parameter with a class (type hint) at '%s::construct(...)'.";
  const MESSAGE_DEPENDENCY_LOOP = "Dependency loop detected. Cannot resolve dependency for '$%s' parameter at '%s::construct(...)'. Dependency chain: \n%s";

  /**
   * @param $class
   * @return InvalidArgumentException
   */
  public static function classIsAbstractOrInterface($class) {
    return new self(sprintf(self::MESSAGE_REGISTER, $class));
  }

  /**
   * @param $concreteClass
   * @param $abstractClass
   * @return InvalidArgumentException
   */
  public static function notSubclass($concreteClass, $abstractClass) {
    $class = is_object($concreteClass) ? get_class($concreteClass) : $concreteClass;
    return new self(sprintf(self::MESSAGE_NOT_SUBCLASS, $class, $abstractClass));
  }

  /**
   * @param $class
   * @return InvalidArgumentException
   */
  public static function couldNotCreateConcreteInstance($class) {
    return new self(sprintf(self::MESSAGE_NOT_CONCRETE, $class));
  }

  /**
   * @param $class
   * @param $param
   * @return InvalidArgumentException
   */
  public static function constructorContainsStaticParameter($class, $param) {
    return new self(sprintf(self::MESSAGE_STATIC_PARAMETER, $param, $class));
  }

  /**
   * @param $parameter
   * @param $class
   * @param Stack $dependencyChain
   * @return InvalidArgumentException
   */
  public static function dependencyChainContainsLoop($parameter, $class, Stack $dependencyChain) {
    $chain = $dependencyChain->toString(' <= ');
    return new self(sprintf(self::MESSAGE_DEPENDENCY_LOOP, $parameter, $class, $chain));
  }
}