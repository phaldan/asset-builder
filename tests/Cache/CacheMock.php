<?php

namespace Phaldan\AssetBuilder\Cache;

use DateTime;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class CacheMock implements Cache {

  private $entries = [];
  private $has = [];
  private $deleted = [];

  /**
   * @param $key
   * @param $value
   */
  public function setEntry($key, $value) {
    $this->entries[$key] = $value;
  }

  /**
   * @param $key
   * @return mixed
   */
  public function getEntry($key) {
    return isset($this->entries[$key]) ? $this->entries[$key] : null;
  }

  /**
   * @param $key
   * @param DateTime $expire
   * @return boolean
   */
  public function hasEntry($key, DateTime $expire = null) {
    return isset($this->has[$key]) && ($this->has[$key] == $expire || is_null($expire));
  }

  /**
   * @param $key
   * @param DateTime|true $expire
   */
  public function setHas($key, DateTime $expire = null) {
    $this->has[$key] = is_null($expire) ? true : $expire;
  }

  /**
   * @param $key
   */
  public function deleteEntry($key) {
    $this->deleted[$key] = true;
  }

  public function hasDeleted($key) {
    return isset($this->deleted[$key]);
  }
}