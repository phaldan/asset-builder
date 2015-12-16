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

  public function processLastModified($filePath) {
    $lastModified = null;
    foreach ($this->getFiles($filePath) as $file => $time) {
      $lastModified = $this->compareLastModified($lastModified, $time);
    }
    $this->validateLastModified($lastModified);
    return $lastModified;
  }

  private function compareLastModified(DateTime $oldTime = null, DateTime $newTime) {
    return is_null($oldTime) || !empty($newTime->diff($oldTime)->format('%r')) ? $newTime : $oldTime;
  }

  private function validateLastModified($dateTime) {
    if (is_null($dateTime)) {
      throw Exception::unsetLastModified(get_class($this));
    }
  }
}