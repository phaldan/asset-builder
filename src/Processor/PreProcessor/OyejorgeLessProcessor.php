<?php

namespace Phaldan\AssetBuilder\Processor\PreProcessor;

use Less_Parser;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class OyejorgeLessProcessor extends LessProcessor {

  const OPTION_MINIFY = 'compress';

  /**
   * @var Less_Parser
   */
  private $compiler;

  private $importPaths = [];

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
    $compiler->Reset();
    $compiler->SetImportDirs($this->importPaths);
    $compiler->SetOption(self::OPTION_MINIFY, $this->getContext()->hasMinifier());
  }

  /**
   * @inheritdoc
   */
  public function setImportPaths(array $paths) {
    $this->importPaths = $this->getFileSystem()->getAbsolutePaths($paths);
    if (!is_null($this->compiler)) {
      $this->compiler->SetImportDirs($this->importPaths);
    }
    return $this;
  }

  /**
   * @inheritdoc
   */
  protected function executeProcessing($filePath) {
    $this->setCompiler($this->getCompiler());
    $content = $this->getFileSystem()->getContent($filePath);
    $this->getCompiler()->parse($content);
    return $this->getCompiler()->getCss();
  }

  /**
   * @inheritdoc
   */
  protected function processFiles($filePath) {
    $array = parent::processFiles($filePath);
    foreach ($this->getCompiler()->AllParsedFiles() as $file) {
      $array[$file] = $this->getFileSystem()->getModifiedTime($file);
    }
    return $array;
  }
}