<?php

namespace Phaldan\AssetBuilder\Processor;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
abstract class ScssCompiler extends PreProcessorCompiler {

  const EXTENSION = 'scss';

  /**
   * @inheritdoc
   */
  public function getSupportedExtension() {
    return self::EXTENSION;
  }
}