<?php

namespace Phaldan\AssetBuilder\Processor\PreProcessor;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
abstract class ScssProcessor extends CssPreProcessor {

  const EXTENSION = 'scss';

  /**
   * @inheritdoc
   */
  public function getSupportedExtension() {
    return self::EXTENSION;
  }
}