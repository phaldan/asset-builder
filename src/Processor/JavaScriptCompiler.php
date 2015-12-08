<?php

namespace Phaldan\AssetBuilder\Processor;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
abstract class JavaScriptProcessor implements Processor {

  const EXTENSION = 'js';
  const MIME_TYPE = 'text/javascript';

  /**
   * @inheritdoc
   */
  public function getSupportedExtension() {
    return self::EXTENSION;
  }

  /**
   * @inheritdoc
   */
  public function getOutputMimeType() {
    return self::MIME_TYPE;
  }
}