<?php

namespace Phaldan\AssetBuilder\Processor\Minifier;

use Phaldan\AssetBuilder\Processor\CachedProcessor;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
abstract class Minifier extends CachedProcessor {

  const SKIP_EXTENSION = 'min';

  /**
   * @inheritdoc
   */
  protected function skipMinifier($filePath) {
    $extension = strtolower(pathinfo(pathinfo($filePath, PATHINFO_FILENAME), PATHINFO_EXTENSION));
    return $extension == self::SKIP_EXTENSION || !$this->getContext()->hasMinifier();
  }
}