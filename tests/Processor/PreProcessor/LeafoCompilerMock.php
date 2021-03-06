<?php

namespace Phaldan\AssetBuilder\Processor\PreProcessor;

use Leafo\ScssPhp\Compiler as LeafoCompiler;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class LeafoCompilerMock extends LeafoCompiler {

  private $import;
  protected $formatter;
  private $compileReturn = [];
  private $style;
  private $parsedFiles = [];

  public function __construct() {
  }

  /**
   * @inheritdoc
   */
  public function setImportPaths($path) {
    $this->import = $path;
  }

  public function getImportPaths() {
    return $this->import;
  }

  /**
   * @inheritdoc
   */
  public function setFormatter($formatterName) {
    $this->formatter = $formatterName;
  }

  public function getFormatter() {
    return $this->formatter;
  }

  /**
   * @inheritdoc
   */
  public function compile($code, $path = null) {
    return isset($this->compileReturn[$code]) ? $this->compileReturn[$code] : null;
  }

  public function setCompileReturn($code, $return) {
    $this->compileReturn[$code] = $return;
  }

  public function setLineNumberStyle($lineNumberStyle) {
    $this->style = $lineNumberStyle;
  }

  public function getLineNumberStyle() {
    return $this->style;
  }

  public function getParsedFiles() {
    return $this->parsedFiles;
  }

  public function setParsedFiles(array $files) {
    $this->parsedFiles = $files;
  }
}