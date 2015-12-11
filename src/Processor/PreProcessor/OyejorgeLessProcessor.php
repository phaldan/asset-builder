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
  public function executeProcessing($filePath) {
    $content = $this->getFileSystem()->getContent($filePath);
    return $this->getCompiler()->parse($content)->getCss();
  }

  /**
   * @inheritdoc
   */
  public function getFiles() {
    $array = parent::getFiles();
    foreach ($this->getCompiler()->AllParsedFiles() as $file) {
      $array[$file] = $this->getFileSystem()->getModifiedTime($file);
    }
    return $array;
  }
}