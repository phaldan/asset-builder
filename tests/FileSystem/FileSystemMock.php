<?php

namespace Phaldan\AssetBuilder\FileSystem;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class FileSystemMock implements FileSystem {

  private $content = [];
  private $paths = [];
  private $globs = [];

  public function getContent($filePath) {
    return isset($this->content[$filePath]) ? $this->content[$filePath] : null;
  }

  public function setContent($filePath, $content) {
    $this->content[$filePath] = $content;
  }

  public function getAbsolutePaths(array $paths) {
    $key = json_encode($paths);
    return isset($this->paths[$key]) ? $this->paths[$key] : null;
  }

  public function setAbsolutePaths(array $paths, array $return) {
    $this->paths[json_encode($paths)] = $return;
  }

  public function getAbsolutePath($path) {
  }

  public function resolveGlob($pattern) {
    return isset($this->globs[$pattern]) ? $this->globs[$pattern] : [];
  }

  public function setGlob($pattern, $result) {
    $this->globs[$pattern] = $result;
  }
}