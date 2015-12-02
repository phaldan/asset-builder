<?php

namespace Phaldan\AssetBuilder\Compiler;

use Leafo\ScssPhp\Compiler as LeafoCompiler;
use Leafo\ScssPhp\Formatter\Crunched;
use Leafo\ScssPhp\Formatter\Expanded;
use Phaldan\AssetBuilder\Context;
use Phaldan\AssetBuilder\FileSystem\FileSystem;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class LeafoScssCompiler extends ScssCompiler {

  private $fileSystem;
  private $context;
  private $importPaths = [];

  /**
   * @var LeafoCompiler
   */
  private $compiler;

  public function __construct(FileSystem $fileSystem, Context $context) {
    $this->fileSystem = $fileSystem;
    $this->context = $context;
  }

  public function setImportDirs(array $paths) {
    $this->importPaths = $this->fileSystem->getAbsolutePaths($paths);
    if (!is_null($this->compiler)) {
      $this->compiler->setImportPaths($this->importPaths);
    }
    return $this;
  }

  public function setCompiler(LeafoCompiler $compiler) {
    $compiler->setImportPaths($this->importPaths);
    $compiler->setFormatter($this->getFormatter());
    $this->compiler = $compiler;
  }

  private function getFormatter() {
    return ($this->context->hasMinifier()) ? Crunched::class : Expanded::class;
  }

  public function getCompiler() {
    if (is_null($this->compiler)) {
      $this->setCompiler(new LeafoCompiler());
    }
    return $this->compiler;
  }

  /**
   * @inheritdoc
   */
  public function process($content) {
    return $this->getCompiler()->compile($content);
  }
}