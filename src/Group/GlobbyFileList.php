<?php

namespace Phaldan\AssetBuilder\Group;

/**
 * Implement extended glob pattern from https://github.com/sindresorhus/globby
 * Especially support negation via ! and globstar via **.
 *
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class GlobbyFileList extends GlobFileList {

  const IGNORE_SYMBOL = '!';
  const GLOBSTAR_SYMBOL = '**';
  const WILDCARD_SYMBOL = '*';

  private $ignoredFiles = [];

  /**
   * @inheritdoc
   */
  public function getIterator() {
    $this->removeIgnores();
    return parent::getIterator();
  }

  private function removeIgnores() {
    $iterator = parent::getIterator();
    if (!empty($this->ignoredFiles)) {
      $iterator = iterator_to_array($iterator);
      $result = array_diff($iterator, $this->ignoredFiles);
      $this->ignoredFiles = [];

      if ($result != $iterator) {
        parent::setIterator(new FileList($result));
      }
    }
  }

  /**
   * @inheritdoc
   */
  protected function resolvePattern($pattern) {
    return ($this->isIgnore($pattern)) ? $this->processIgnore($pattern) : $this->extendedResolve($pattern);
  }

  private function isIgnore($pattern) {
    return (strpos($pattern, self::IGNORE_SYMBOL) === 0);
  }

  private function processIgnore($ignore) {
    $pattern = substr($ignore, 1);
    $this->ignoredFiles = array_merge($this->ignoredFiles, $this->extendedResolve($pattern));
    return [];
  }

  private function extendedResolve($pattern) {
    $pos = strpos($pattern, self::GLOBSTAR_SYMBOL);
    return $pos === false ? parent::resolvePattern($pattern) : $this->resolveGlobStar($pattern, $pos);
  }

  private function resolveGlobStar($pattern, $pos) {
    $prefix = substr($pattern, 0, $pos);
    $suffix = substr($pattern, $pos + 1);
    return $this->broadSearch($prefix, $suffix);
  }

  private function broadSearch($pattern, $suffix) {
    $dirs = $this->getFileSystem()->resolveGlob($pattern . self::WILDCARD_SYMBOL . DIRECTORY_SEPARATOR);
    $files = [];
    if (!empty($dirs)) {
      $files = $this->extendedResolve($pattern . $suffix);
      $results = $this->broadSearch($pattern . self::WILDCARD_SYMBOL . DIRECTORY_SEPARATOR, $suffix);
      $files = array_merge($files, $results);
    }
    return $files;
  }

  /**
   * @inheritdoc
   */
  public function add($pattern) {
    parent::add($pattern);
    $this->ignoredFiles = [];
  }
}