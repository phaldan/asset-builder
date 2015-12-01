<?php

namespace Phaldan\AssetBuilder\FileSystem;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class FileSystemMock implements FileSystem {

  private $readContent = [];

  /**
   * @param $filePath
   * @return null|string
   */
  public function getContent($filePath) {
    return isset($this->readContent[$filePath]) ? $this->readContent[$filePath] : null;
  }

  public function setRead($file, $content) {
    $this->readContent[$file] = $content;
  }
}