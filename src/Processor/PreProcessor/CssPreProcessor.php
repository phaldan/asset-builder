<?php

namespace Phaldan\AssetBuilder\Processor\PreProcessor;

use Phaldan\AssetBuilder\Processor\Processor;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
abstract class CssPreProcessor extends Processor {

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