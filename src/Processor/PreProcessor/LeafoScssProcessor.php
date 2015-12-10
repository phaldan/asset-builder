<?php

namespace Phaldan\AssetBuilder\Processor\PreProcessor;

use Leafo\ScssPhp\Compiler as LeafoCompiler;
use Leafo\ScssPhp\Formatter\Crunched;
use Leafo\ScssPhp\Formatter\Expanded;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class LeafoScssProcessor extends ScssProcessor {

  /**
   * @var LeafoCompiler
   */
  private $compiler;
  private $importPaths = [];

  /**
   * @inheritdoc
   */
  public function setImportPaths(array $paths) {
    $this->importPaths = $this->getFileSystem()->getAbsolutePaths($paths);
    if (!is_null($this->compiler)) {
      $this->compiler->setImportPaths($this->importPaths);
    }
    return $this;
  }

  /**
   * @param LeafoCompiler $compiler
   * @return LeafoScssProcessor
   */
  public function setCompiler(LeafoCompiler $compiler) {
    $compiler->setImportPaths($this->importPaths);
    $compiler->setFormatter($this->getFormatter());
    $compiler->setLineNumberStyle($this->getLineNumberStyle());
    $this->compiler = $compiler;
    return $this;
  }

  private function getFormatter() {
    return ($this->getContext()->hasMinifier()) ? Crunched::class : Expanded::class;
  }

  private function getLineNumberStyle() {
    return $this->getContext()->hasDebug() ? LeafoCompiler::LINE_COMMENTS : null;
  }

  private function getCompiler() {
    if (is_null($this->compiler)) {
      $this->setCompiler(new LeafoCompiler());
    }
    return $this->compiler;
  }

  /**
   * @inheritdoc
   */
  public function executeProcessing($filePath) {
    $content = $this->getFileSystem()->getContent($filePath);
    return $this->getCompiler()->compile($content);
  }
}