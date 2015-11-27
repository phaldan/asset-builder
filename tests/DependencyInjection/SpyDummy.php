<?php

namespace Phaldan\AssetBuilder\DependencyInjection;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class SpyDummy {

  private $dummy;

  public function __construct(DummyBasic $dummy) {
    $this->dummy = $dummy;
  }

  public function getDummy() {
    return $this->dummy;
  }
}