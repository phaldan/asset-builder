<?php

namespace Phaldan\AssetBuilder\DependencyInjection;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class DummyLoopComplex {

  public function __construct(DummyLoopMiddle $middle) {
  }
}