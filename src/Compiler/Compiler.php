<?php

namespace Phaldan\AssetBuilder\Compiler;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
interface Compiler {

  /**
   * Returns file extension
   * @return string
   */
  public function getSupportedExtension();

  /**
   * @param $content
   * @return string
   */
  public function process($content);
}