<?php

namespace Phaldan\AssetBuilder\Group;

use Phaldan\AssetBuilder\FileSystem\FileSystem;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class GlobFileList extends FileList {

  /**
   * @var FileList
   */
  private $iterator;

  /**
   * @var FileSystem
   */
  private $fileSystem;

  /**
   * @inheritdoc
   */
  public function __construct(FileSystem $fileSystem, array $array = []) {
    parent::__construct($array);
    $this->fileSystem = $fileSystem;
  }

  /**
   * @inheritdoc
   */
  public function getIterator() {
    return is_null($this->iterator) ? $this->processGlobs() : $this->iterator->getIterator();
  }

  private function processGlobs() {
    $this->iterator = new FileList();
    foreach (parent::getIterator() as $pattern) {
      $files = $this->resolvePattern($pattern);
      $this->iterator->addAll($files);
    }
    return $this->iterator->getIterator();
  }

  /**
   * Resolve pattern and returns file list
   * @param $pattern
   * @return array
   */
  protected function resolvePattern($pattern) {
    return $this->fileSystem->resolveGlob($pattern);
  }

  /**
   * @param FileList $iterator
   */
  protected function setIterator(FileList $iterator) {
    $this->iterator = $iterator;
  }

  /**
   * @param $pattern
   */
  public function add($pattern) {
    parent::add($pattern);
    $this->iterator = null;
  }

  /**
   * @return FileSystem
   */
  protected function getFileSystem() {
    return $this->fileSystem;
  }
}