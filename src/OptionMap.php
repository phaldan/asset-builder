<?php

namespace Phaldan\AssetBuilder;

use ArrayAccess;
use Serializable;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class OptionMap implements Serializable, ArrayAccess {

  private $options = [];

  /**
   * @inheritdoc
   */
  public function offsetExists($offset) {
    return isset($this->options[$offset]);
  }

  /**
   * @inheritdoc
   */
  public function offsetGet($offset) {
    return $this->offsetExists($offset) ? $this->options[$offset] : null;
  }

  /**
   * @inheritdoc
   */
  public function offsetSet($offset, $value) {
    $this->options[$offset] = $value;
  }

  /**
   * @inheritdoc
   */
  public function offsetUnset($offset) {
    if ($this->offsetExists($offset)) {
      $this->options[$offset] = null;
    }
  }

  /**
   * @inheritdoc
   */
  public function serialize() {
    return serialize($this->options);
  }

  /**
   * @inheritdoc
   */
  public function unserialize($serialized) {
    if (!is_string($serialized)) {
      throw new \InvalidArgumentException('Input is for unserialize() must be a string.');
    }
    $this->options = array_merge($this->options, unserialize($serialized));
  }
}