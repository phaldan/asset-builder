<?php

namespace Phaldan\AssetBuilder\Compiler;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
abstract class LessCompiler implements Compiler {

  const EXTENSION = 'less';

  /**
   * @inheritdoc
   */
  public function getSupportedExtension() {
    return self::EXTENSION;
  }
}