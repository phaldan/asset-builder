<?php

namespace Phaldan\AssetBuilder\Binder;

use DateTime;
use Phaldan\AssetBuilder\Exception;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
abstract class HttpHeaderBinder implements Binder {

  const HEADER_CONTENT_TYPE = "Content-Type: %s";
  const HEADER_LAST_MODIFIED = "Last-Modified: %s";

  /**
   * @var DateTime
   */
  private $lastModified;
  private $mimeTypes = [];

  protected function addMimeType($mimeType) {
    $this->mimeTypes[$mimeType] = true;
  }

  private function getMimeType() {
    if (count($this->mimeTypes) > 1) {
      throw Exception::foundMultipleMimiTypes(array_keys($this->mimeTypes));
    }
    return key($this->mimeTypes);
  }

  protected function processHttpHeader() {
    if (!is_null($this->getMimeType())) {
      header(sprintf(self::HEADER_CONTENT_TYPE, $this->getMimeType()));
    }
    if (!is_null($this->lastModified)) {
      header(sprintf(self::HEADER_LAST_MODIFIED, $this->lastModified->format('D, d M Y H:i:s T')));
    }
  }

  protected function setLastModified(DateTime $dateTime) {
    if (is_null($this->lastModified) || !empty($dateTime->diff($this->lastModified)->format('%r'))) {
      $this->lastModified = $dateTime;
    }
  }
}