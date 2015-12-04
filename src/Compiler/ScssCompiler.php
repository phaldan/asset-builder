<?php

namespace Phaldan\AssetBuilder\Compiler;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
abstract class ScssCompiler implements Compiler {

  const EXTENSION = 'scss';

  /**
   * @param array $paths
   * @return LessCompiler
   */
  abstract public function setImportPaths(array $paths);

  /**
   * @inheritdoc
   */
  public function getSupportedExtension() {
    return self::EXTENSION;
  }
}