<?php

namespace Phaldan\AssetBuilder\Processor;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
abstract class Processor {

  /**
   * Returns file extension
   * @return string
   */
  public abstract function getSupportedExtension();

  /**
   * @return string
   */
  public abstract function getOutputMimeType();

  /**
   * Transform to native language like CSS or JavaScript, and compress
   * @param $content
   * @return string
   */
  public abstract function process($content);
}