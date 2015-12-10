<?php

namespace Phaldan\AssetBuilder\Processor\Minifier;

use JShrink\Minifier;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class JShrinkProcessor extends JavaScriptProcessor {

  /**
   * @param $filePath
   * @return string
   * @throws \Exception
   */
  public function executeProcessing($filePath) {
    $content = $this->getContent($filePath);
    return $this->getContext()->hasMinifier() ? Minifier::minify($content) : $content;
  }
}