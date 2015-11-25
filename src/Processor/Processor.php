<?php

namespace Processor;

use Context;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
interface Processor {

  /**
   * @param Context $context
   * @return Processor
   */
  public function setContext(Context $context);

  /**
   * @param $file
   * @return string
   */
  public function get($file);
}