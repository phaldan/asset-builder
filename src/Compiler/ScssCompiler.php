<?php

namespace Phaldan\AssetBuilder\Compiler;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
abstract class ScssCompiler implements Compiler {

  const EXTENSION = 'scss';

  public function getSupportedExtension() {
    return self::EXTENSION;
  }
}