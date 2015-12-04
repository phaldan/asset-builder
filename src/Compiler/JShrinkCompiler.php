<?php

namespace Phaldan\AssetBuilder\Compiler;

use JShrink\Minifier;
use Phaldan\AssetBuilder\Context;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class JShrinkCompiler extends JavaScriptCompiler {

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