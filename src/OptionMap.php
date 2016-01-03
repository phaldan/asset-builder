<?php

namespace Phaldan\AssetBuilder;

use ArrayAccess;
use JsonSerializable;
use Serializable;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class OptionMap implements Serializable, ArrayAccess, JsonSerializable {

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
    return json_encode($this->options);
  }

  /**
   * @inheritdoc
   */
  public function unserialize($serialized) {
    if (!is_string($serialized)) {
      throw Exception::invalidUnserializeInput();
    }
    $this->options = array_merge($this->options, json_decode($serialized, true));
  }

  /**
   * @inheritdoc
   */
  public function jsonSerialize() {
    return $this->serialize();
  }
}