<?php

namespace Phaldan\AssetBuilder\Processor\PreProcessor;

use Less_Parser;
use Phaldan\AssetBuilder\Context;
use Phaldan\AssetBuilder\FileSystem\FileSystem;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class OyejorgeLessProcessor extends LessProcessor {

  const OPTION_MINIFY = 'compress';

  /**
   * @var Less_Parser
   */
  private $compiler;

  /**
   * @var Context
   */
  private $context;
  private $importPaths = [];

  /**
   * @var FileSystem
   */
  private $fileSystem;

  /**
   * @param FileSystem $fileSystem
   * @param Context $context
   */
  public function __construct(FileSystem $fileSystem, Context $context) {
    $this->fileSystem = $fileSystem;
    $this->context = $context;
  }

  private function getCompiler() {
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
    $compiler->SetOption(self::OPTION_MINIFY, $this->context->hasMinifier());
  }

  /**
   * @inheritdoc
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