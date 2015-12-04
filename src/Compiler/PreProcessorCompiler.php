<?php

namespace Phaldan\AssetBuilder\Compiler;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
interface PreProcessorCompiler extends Compiler {

  /**
   * @param array $paths
   * @return LessCompiler
   */
  public function setImportPaths(array $paths);
}