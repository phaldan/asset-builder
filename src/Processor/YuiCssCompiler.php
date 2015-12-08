<?php

namespace Phaldan\AssetBuilder\Processor;

use CSSmin;
use Phaldan\AssetBuilder\Context;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class YuiCssCompiler extends CssCompiler {

  /**
   * @var CSSmin
   */
  private $compressor;

  /**
   * @var Context
   */
  private $context;

  public function __construct(Context $context) {
    $this->context = $context;
  }

  /**
   * @param $content
   * @return string
   */
  public function process($content) {
    return $this->context->hasMinifier() ? $this->getCompressor()->run($content) : $content;
  }

  private function getCompressor() {
    if (is_null($this->compressor)) {
      $this->setCompressor(new CSSmin(false));
    }
    return $this->compressor;
  }

  /**
   * @param CSSmin $compressor
   * @return YuiCssCompiler
   */
  public function setCompressor(CSSmin $compressor) {
    $this->compressor = $compressor;
    return $this;
  }
}