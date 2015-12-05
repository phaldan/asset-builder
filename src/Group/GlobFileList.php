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
   * @return FileList
   */
  public function getIterator() {
    return is_null($this->iterator) ? $this->processGlobs() : $this->iterator;
  }

  private function processGlobs() {
    $this->iterator = new FileList();
    foreach (parent::getIterator() as $pattern) {
      $files = $this->fileSystem->resolveGlob($pattern);
      $this->iterator->addAll($files);
    }
    return $this->iterator;
  }

  /**
   * @param $file
   */
  public function add($file) {
    parent::add($file);
    $this->iterator = null;
  }
}