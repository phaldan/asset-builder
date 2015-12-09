<?php

namespace Phaldan\AssetBuilder\Processor\Minifier;

use JShrink\Minifier;
use Phaldan\AssetBuilder\Context;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class JShrinkProcessor extends JavaScriptProcessor {

  private $context;

  /**
   * @param Context $context
   */
  public function __construct(Context $context) {
    $this->context = $context;
  }

  /**
   * @param $file
   * @return string
   * @throws \Exception
   */
  public function process($file) {
    return $this->context->hasMinifier() ? Minifier::minify($file) : $file;
  }
}