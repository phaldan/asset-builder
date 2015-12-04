<?php

namespace Phaldan\AssetBuilder\Compiler;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
abstract class LessCompiler implements Compiler {

  const EXTENSION = 'less';

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