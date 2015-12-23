<?php

namespace Phaldan\AssetBuilder;

use InvalidArgumentException;
use LogicException;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class Exception extends \Exception {

  const MESSAGE_MULTIPLE_MIME_TYPES = "Could not setup 'Content-Type'-Header. Multiple Content-Types exists: %s";
  const MESSAGE_GROUP_NOT_FOUND = "Could not find group '%s'! Please provide a group via 'addGroup(...)' or 'addGroups(..)'";
  const MESSAGE_NOT_OBJECT_OR_CLASS = "Parameter $%s (type: '%s') must be an object or class";
  const MESSAGE_NOT_SUBCLASS = "'%s' must be a subclass of '%s'";
  const MESSAGE_REAL_PATH = "The following path doesn't exists: '%s'";
  const MESSAGE_UNSERIALIZE_INPUT = "Input for 'unserialize()' must be a string.";
  const MESSAGE_PROCESSOR_OVERRIDE = "Please provide an implementation for '%s::executeProcessing(...)' method";
  const MESSAGE_EMPTY_FILE_LIST = "'%s::getFiles()' cannot return an empty list.";
  const FILE_NOT_FOUND = "File '%s' does not exists.";

  /**
   * @param array $mimeTypes
   * @return LogicException
   */
  public static function foundMultipleMimiTypes(array $mimeTypes) {
    return new LogicException(sprintf(self::MESSAGE_MULTIPLE_MIME_TYPES, implode(',', $mimeTypes)));
  }

  /**
   * @param $group
   * @return Exception
   */
  public static function groupNotFound($group) {
    return new Exception(sprintf(self::MESSAGE_GROUP_NOT_FOUND, $group));
  }


  /**
   * @param $value
   * @return InvalidArgumentException
   */
  public static function neitherObjectOrClass($value) {
    return new InvalidArgumentException(sprintf(self::MESSAGE_NOT_OBJECT_OR_CLASS, 'compiler', gettype($value)));
  }

  /**
   * @param $instance
   * @param $class
   * @return InvalidArgumentException
   */
  public static function notSubclass($instance, $class) {
    return new InvalidArgumentException(sprintf(self::MESSAGE_NOT_SUBCLASS, get_class($instance), $class));
  }

  /**
   * @param $path
   * @return InvalidArgumentException
   */
  public static function pathNotFound($path) {
    return new InvalidArgumentException(sprintf(self::MESSAGE_REAL_PATH, $path));
  }

  /**
   * @return InvalidArgumentException
   */
  public static function invalidUnserializeInput() {
    return new InvalidArgumentException(self::MESSAGE_UNSERIALIZE_INPUT);
  }

  /**
   * @param $class
   * @return LogicException
   */
  public static function processorOverrideNecessary($class) {
    return new LogicException(sprintf(self::MESSAGE_PROCESSOR_OVERRIDE, $class));
  }

  /**
   * @param $class
   * @return LogicException
   */
  public static function emptyFileList($class) {
    return new LogicException(sprintf(self::MESSAGE_EMPTY_FILE_LIST, $class));
  }

  public static function fileNotFound($path) {
    return new InvalidArgumentException(sprintf(self::FILE_NOT_FOUND, $path));
  }
}