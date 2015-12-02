<?php

namespace Phaldan\AssetBuilder\Compiler;

use Less_Parser;
use Phaldan\AssetBuilder\FileSystem\FileSystem;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class OyejorgeLessCompiler extends LessCompiler {

  /**
   * @var Less_Parser
   */
  private $compiler;
  private $importPaths = [];

  /**
   * @var FileSystem
   */
  private $fileSystem;

  /**
   * @param FileSystem $fileSystem
   */
  public function __construct(FileSystem $fileSystem) {
    $this->fileSystem = $fileSystem;
  }

  /**
   * @return Less_Parser
   */
  public function getCompiler() {
    if (is_null($this->compiler)) {
      $this->setCompiler(new Less_Parser());
    }
    return $this->compiler;
  }

  /**
   * @param Less_Parser $compiler
   */
  public function setCompiler(Less_Parser $compiler) {
    $this->compiler = $compiler;
    $compiler->SetImportDirs($this->importPaths);
  }

  /**
   * @param array $paths
   * @return $this
   */
  public function setImportPaths(array $paths) {
    $this->importPaths = $this->fileSystem->getAbsolutePaths($paths);
    if (!is_null($this->compiler)) {
      $this->compiler->SetImportDirs($this->importPaths);
    }
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function process($content) {
    return $this->getCompiler()->parse($content)->getCss();
  }
}