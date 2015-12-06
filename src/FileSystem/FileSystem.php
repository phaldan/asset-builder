<?php

namespace Phaldan\AssetBuilder\FileSystem;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
interface FileSystem {

  /**
   * @param $filePath
   * @return null|string
   */
  public function getContent($filePath);

  /**
   * @param $filePath
   * @param $content
   */
  public function setContent($filePath, $content);

  /**
   * Turns a list of relative and absolute paths to a list of absolute paths
   * @param array $paths
   * @return array
   */
  public function getAbsolutePaths(array $paths);

  /**
   * Turns a relative or absolute path into a absolute path
   * @param $path
   * @return string
   */
  public function getAbsolutePath($path);

  /**
   * Resolve glob pattern and returns list of files/directories
   * @param $pattern
   * @return array
   */
  public function resolveGlob($pattern);
}