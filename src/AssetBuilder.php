<?php

namespace Phaldan\AssetBuilder;

use Phaldan\AssetBuilder\Builder\Builder;
use Phaldan\AssetBuilder\Builder\FluentBuilder;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
abstract class AssetBuilder {

  /**
   * @return Builder
   */
  public static function create() {
    return new FluentBuilder();
  }
}