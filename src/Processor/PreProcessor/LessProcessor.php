<?php

namespace Phaldan\AssetBuilder\Processor\PreProcessor;

use Phaldan\AssetBuilder\Processor\CssPreProcessor;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
abstract class LessProcessor extends CssPreProcessor {

  const EXTENSION = 'less';

  /**
   * @inheritdoc
   */
  public function getSupportedExtension() {
    return self::EXTENSION;
  }
}