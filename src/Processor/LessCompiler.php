<?php

namespace Phaldan\AssetBuilder\Processor;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
abstract class LessCompiler extends PreProcessorCompiler {

  const EXTENSION = 'less';

  /**
   * @inheritdoc
   */
  public function getSupportedExtension() {
    return self::EXTENSION;
  }
}