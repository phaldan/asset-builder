<?php

namespace Phaldan\AssetBuilder\Processor;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
abstract class CssPreProcessor implements Processor {

  const MIME_TYPE = 'text/css';

  /**
   * @param array $paths
   * @return CssPreProcessor
   */
  public abstract function setImportPaths(array $paths);

  /**
   * @inheritdoc
   */
  public function getOutputMimeType() {
    return self::MIME_TYPE;
  }
}