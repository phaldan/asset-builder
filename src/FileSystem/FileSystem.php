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
}