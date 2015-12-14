<?php

namespace Phaldan\AssetBuilder\Binder;

use Phaldan\AssetBuilder\Exception;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
abstract class ContentTypeBinder implements Binder {

  const HEADER = "Content-Type: %s";

  private $mimeTypes = [];

  protected function addType($mimeType) {
    $this->mimeTypes[$mimeType] = true;
  }

  private function getType() {
    if (count($this->mimeTypes) > 1) {
      throw Exception::foundMultipleMimiTypes(array_keys($this->mimeTypes));
    }
    return key($this->mimeTypes);
  }

  protected function outputContentTypeHeader() {
    if (!is_null($this->getType())) {
      header(sprintf(self::HEADER, $this->getType()));
    }
  }
}