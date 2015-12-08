<?php

namespace Phaldan\AssetBuilder\Binder;

use Exception;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
abstract class ContentTypeBinder implements Binder {

  const MESSAGE_MULTIPLE_MIME_TYPES = "Could not setup 'Content-Type'-Header. Multiple Content-Types exists: %s";
  const HEADER = "Content-Type: %s";

  private $mimeTypes = [];

  protected function addType($mimeType) {
    $this->mimeTypes[$mimeType] = true;
  }

  private function getType() {
    if (count($this->mimeTypes) > 1) {
      throw new Exception(sprintf(self::MESSAGE_MULTIPLE_MIME_TYPES, implode(', ', array_keys($this->mimeTypes))));
    }
    return key($this->mimeTypes);
  }

  protected function outputContentTypeHeader() {
    if (!is_null($this->getType())) {
      header(sprintf(self::HEADER, $this->getType()));
    }
  }
}