<?php

namespace Phaldan\AssetBuilder\Compiler;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
abstract class JavaScriptCompiler implements Compiler {

  const EXTENSION = 'js';

  public function getSupportedExtension() {
    return self::EXTENSION;
  }
}