<?php

namespace Phaldan\AssetBuilder;

use Leafo\ScssPhp\Compiler;
use Phaldan\AssetBuilder\Builder\Builder;
use Phaldan\AssetBuilder\Compiler\CssCompiler;
use Phaldan\AssetBuilder\Compiler\JavaScriptCompiler;
use Phaldan\AssetBuilder\Compiler\LessCompiler;
use Phaldan\AssetBuilder\Compiler\PreProcessorCompiler;
use Phaldan\AssetBuilder\Compiler\ScssCompiler;
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
   * @param string $rootPath
   * @return Builder
   */
  public static function create($rootPath = '.') {
    return self::getContainer()->getInstance(Builder::class)->setRootPath($rootPath);
  }

  /**
   * @param string $rootPath Define root directory for all relative paths
   * @param string $importPath Define path for Less and Scss imports
   * @return Builder
   */
  public static function createProduction($rootPath = '.', $importPath = null) {
    $instance = self::create($rootPath)->enableStopWatch(true)->enableMinifier(true)->enableDebug(false)->setCachePath(sys_get_temp_dir());
    return self::addCompiler($instance, $importPath);
  }

  /**
   * @param string $rootPath Define root directory for all relative paths
   * @param string $importPath Define path for Less and Scss imports
   * @return Builder
   */
  public static function createDebug($rootPath = '.', $importPath = null) {
    $instance = self::create($rootPath)->enableStopWatch(true)->enableMinifier(false)->enableDebug(true);
    return self::addCompiler($instance, $importPath);
  }


  private function addCompiler(Builder $builder, $importPath = null) {
    return $builder->addCompiler(JavaScriptCompiler::class)->addCompiler(CssCompiler::class)->addCompiler(self::getLessCompiler($importPath))->addCompiler(self::getScssCompiler($importPath));
  }

  private static function getLessCompiler($importPath = null) {
    $instance = self::getContainer()->getInstance(LessCompiler::class);
    return self::setImportPath($instance, $importPath);
  }

  private static function getScssCompiler($importPath = null) {
    $instance = self::getContainer()->getInstance(ScssCompiler::class);
    return self::setImportPath($instance, $importPath);
  }

  private static function setImportPath(PreProcessorCompiler $compiler, $importPath = null) {
    if (!is_null($importPath)) {
      $compiler->setImportPaths((array)$importPath);
    }
    return $compiler;
  }
}