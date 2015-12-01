<?php

namespace Phaldan\AssetBuilder\FileSystem;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class FileSystemMock implements FileSystem {

  private $content = [];

  public function getContent($filePath) {
    return isset($this->content[$filePath]) ? $this->content[$filePath] : null;
  }

  public function setContent($filePath, $content) {
    $this->content[$filePath] = $content;
  }
}