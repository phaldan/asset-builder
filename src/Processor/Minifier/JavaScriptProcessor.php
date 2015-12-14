<?php

namespace Phaldan\AssetBuilder\Processor\Minifier;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
abstract class JavaScriptProcessor extends Minifier {

  const EXTENSION = 'js';
  const MIME_TYPE = 'text/javascript';

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