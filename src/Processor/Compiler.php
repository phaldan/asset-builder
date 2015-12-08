<?php

namespace Phaldan\AssetBuilder\Processor;

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
   * @return string
   */
  public function getOutputMimeType();

  /**
   * Transform to native language like CSS or JavaScript, and compress
   * @param $content
   * @return string
   */
  public function process($content);
}