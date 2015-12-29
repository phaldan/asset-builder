<?php

namespace Phaldan\AssetBuilder\Binder;

use DateTime;
use Phaldan\AssetBuilder\Processor\CacheEntry;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class CacheBinderEntry extends CacheEntry {

  const DATA_MIME_TYPE = 'mime-type';

  public function __construct($content = null, array $files = null, DateTime $lastModified = null, $mimeType = null) {
    parent::__construct($content, $files, $lastModified);
    $this->set(self::DATA_MIME_TYPE, $mimeType);
  }

  public function getMimeType() {
    return $this->get(self::DATA_MIME_TYPE);
  }
}