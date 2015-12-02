<?php

namespace Phaldan\AssetBuilder\Compiler;

use Leafo\ScssPhp\Compiler as LeafoCompiler;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class LeafoCompilerMock extends LeafoCompiler {

  private $import;
  protected $formatter;
  private $compileReturn = [];

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
}