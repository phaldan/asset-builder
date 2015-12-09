<?php

namespace Phaldan\AssetBuilder\Processor\Minifier;

use CSSmin;
use Phaldan\AssetBuilder\Context;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class YuiCssProcessor extends CssProcessor {

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
   * @param $file
   * @return string
   */
  public function process($file) {
    return $this->context->hasMinifier() ? $this->getCompressor()->run($file) : $file;
  }

  private function getCompressor() {
    if (is_null($this->compressor)) {
      $this->setCompressor(new CSSmin(false));
    }
    return $this->compressor;
  }

  /**
   * @param CSSmin $compressor
   * @return YuiCssProcessor
   */
  public function setCompressor(CSSmin $compressor) {
    $this->compressor = $compressor;
    return $this;
  }
}