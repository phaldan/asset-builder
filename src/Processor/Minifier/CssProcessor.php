<?php

namespace Phaldan\AssetBuilder\Processor\Minifier;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
abstract class CssProcessor extends Minifier {

  const EXTENSION = 'css';
  const MIME_TYPE = 'text/css';

  /**
   * @inheritdoc
   */
  public function getFileExtension() {
    return self::EXTENSION;
  }

  /**
   * @inheritdoc
   */
  public function getOutputMimeType() {
    return self::MIME_TYPE;
  }
}