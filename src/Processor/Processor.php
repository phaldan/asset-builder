<?php

namespace Processor;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
interface Processor {

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