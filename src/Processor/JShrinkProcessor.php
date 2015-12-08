<?php

namespace Phaldan\AssetBuilder\Processor;

use JShrink\Minifier;
use Phaldan\AssetBuilder\Context;
use Phaldan\AssetBuilder\Processor\Minifier\JavaScriptProcessor;

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
   * @param $content
   * @return string
   */
  public function process($content) {
    return $this->context->hasMinifier() ? Minifier::minify($content) : $content;
  }
}