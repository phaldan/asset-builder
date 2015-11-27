<?php

namespace Phaldan\AssetBuilder\Builder;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class Exception extends \Exception {

  const MESSAGE_GROUP_NOT_FOUND = "Could not find group '%s'! Please provide a group via 'addGroup(...)' or 'addGroups(..)'";

  public static function createGroupNotFound($group) {
    throw new Exception(sprintf(self::MESSAGE_GROUP_NOT_FOUND, $group));
  }
}