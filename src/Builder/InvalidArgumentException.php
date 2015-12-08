<?php

namespace Phaldan\AssetBuilder\Builder;

use Phaldan\AssetBuilder\Processor\Processor;


/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class InvalidArgumentException extends \InvalidArgumentException {

  const MESSAGE_NOT_OBJECT_OR_CLASS = "Parameter $%s (type: '%s') must be an object or class";
  const MESSAGE_NOT_SUBCLASS = "'%s' must be a subclass of '%s'";

  /**
   * @param $value
   * @throws InvalidArgumentException
   */
  public static function createNeitherObjectOrClass($value) {
    throw new self(sprintf(self::MESSAGE_NOT_OBJECT_OR_CLASS, 'compiler', gettype($value)));
  }

  /**
   * @param $instance
   * @throws InvalidArgumentException
   */
  public static function createNotSubclass($instance) {
    throw new self(sprintf(self::MESSAGE_NOT_SUBCLASS, get_class($instance), Processor::class));
  }
}