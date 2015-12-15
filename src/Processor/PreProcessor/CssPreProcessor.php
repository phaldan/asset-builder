<?php

namespace Phaldan\AssetBuilder\Processor\PreProcessor;

use DateTime;
use Phaldan\AssetBuilder\Exception;
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

  /**
   * @inheritdoc
   */
  public function getLastModified() {
    $lastModified = null;
    foreach ($this->getFiles() as $file => $time) {
      $lastModified = $this->processLastModified($lastModified, $time);
    }
    return $this->validateLastModified($lastModified);
  }

  private function processLastModified(DateTime $oldTime = null, DateTime $newTime) {
    return is_null($oldTime) || !empty($newTime->diff($oldTime)->format('%r')) ? $newTime : $oldTime;
  }

  private function validateLastModified($dateTime) {
    if (is_null($dateTime)) {
      throw Exception::unsetLastModified(get_class($this));
    }
    return $dateTime;
  }
}