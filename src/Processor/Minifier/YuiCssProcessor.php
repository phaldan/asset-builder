<?php

namespace Phaldan\AssetBuilder\Processor\Minifier;

use CSSmin;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class YuiCssProcessor extends CssProcessor {

  /**
   * @var CSSmin
   */
  private $compressor;

  /**
   * @inheritdoc
   */
  protected function executeProcessing($filePath) {
    $content = $this->getFileSystem()->getContent($filePath);
    return $this->getContext()->hasMinifier() ? $this->getCompressor()->run($content) : $content;
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