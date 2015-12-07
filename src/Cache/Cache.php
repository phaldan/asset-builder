<?php

namespace Phaldan\AssetBuilder\Cache;

use DateTime;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
interface Cache {

  /**
   * @param $key
   * @param $value
   */
  public function setEntry($key, $value);

  /**
   * @param $key
   * @return mixed
   */
  public function getEntry($key);

  /**
   * @param $key
   * @param DateTime $expire
   * @return boolean
   */
  public function hasEntry($key, DateTime $expire = null);
}