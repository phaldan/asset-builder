<?php

namespace Phaldan\AssetBuilder;

use Phaldan\AssetBuilder\Builder\Builder;
use Phaldan\AssetBuilder\DependencyInjection\IocContainer;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
abstract class AssetBuilder {

  /**
   * @var IocContainer
   */
  private static $container;

  /**
   * @return IocContainer
   */
  public static function getContainer() {
    return is_null(self::$container) ? (self::$container = new ModuleContainer()) : self::$container;
  }

  protected static function clearContainer() {
    self::$container = null;
  }

  /**
   * @return Builder
   */
  public static function create() {
    return self::getContainer()->getInstance(Builder::class);
  }
}