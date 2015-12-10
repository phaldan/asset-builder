<?php

namespace Phaldan\AssetBuilder\Processor\PreProcessor;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
abstract class LessProcessor extends CssPreProcessor {

  const EXTENSION = 'less';

  /**
   * @inheritdoc
   */
  public function getFileExtension() {
    return self::EXTENSION;
  }
}