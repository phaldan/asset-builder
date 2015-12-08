<?php

namespace Phaldan\AssetBuilder;

use Leafo\ScssPhp\Compiler;
use Phaldan\AssetBuilder\Builder\Builder;
use Phaldan\AssetBuilder\Processor\Minifier\CssProcessor;
use Phaldan\AssetBuilder\Processor\JavaScriptProcessor;
use Phaldan\AssetBuilder\Processor\LessProcessor;
use Phaldan\AssetBuilder\Processor\CssPreProcessor;
use Phaldan\AssetBuilder\Processor\ScssProcessor;
use Phaldan\AssetBuilder\DependencyInjection\IocContainer;
use Phaldan\AssetBuilder\FileSystem\FileSystem;
use Phaldan\AssetBuilder\Group\GlobFileList;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class AssetBuilder {

  /**
   * @var IocContainer
   */
  private $container;

  /**
   * @return IocContainer
   */
  public function getContainer() {
    return is_null($this->container) ? ($this->container = new ModuleContainer()) : $this->container;
  }

  /**
   * @param string $rootPath
   * @return Builder
   */
  public function create($rootPath = '.') {
    return $this->getContainer()->getInstance(Builder::class)->setRootPath($rootPath);
  }

  /**
   * @param string $rootPath Define root directory for all relative paths
   * @param string $importPath Define path for Less and Scss imports
   * @return Builder
   */
  public function createProduction($rootPath = '.', $importPath = null) {
    $instance = $this->create($rootPath)->enableStopWatch(true)->enableMinifier(true)->enableDebug(false)->setCachePath(sys_get_temp_dir());
    return $this->addCompiler($instance, $importPath);
  }

  /**
   * @param string $rootPath Define root directory for all relative paths
   * @param string $importPath Define path for Less and Scss imports
   * @return Builder
   */
  public function createDebug($rootPath = '.', $importPath = null) {
    $instance = $this->create($rootPath)->enableStopWatch(true)->enableMinifier(false)->enableDebug(true);
    return $this->addCompiler($instance, $importPath);
  }

  private function addCompiler(Builder $builder, $importPath = null) {
    return $builder->addCompiler(JavaScriptProcessor::class)->addCompiler(CssProcessor::class)->addCompiler($this->getLessCompiler($importPath))->addCompiler($this->getScssCompiler($importPath));
  }

  private function getLessCompiler($importPath = null) {
    $instance = $this->getContainer()->getInstance(LessProcessor::class);
    return $this->setImportPath($instance, $importPath);
  }

  private function getScssCompiler($importPath = null) {
    $instance = $this->getContainer()->getInstance(ScssProcessor::class);
    return $this->setImportPath($instance, $importPath);
  }

  private function setImportPath(CssPreProcessor $compiler, $importPath = null) {
    if (!is_null($importPath)) {
      $compiler->setImportPaths((array)$importPath);
    }
    return $compiler;
  }

  /**
   * @param array $globPatterns
   * @return GlobFileList
   */
  public function getGlobFileList(array $globPatterns) {
    $fs = $this->getContainer()->getInstance(FileSystem::class);
    return new GlobFileList($fs, $globPatterns);
  }
}