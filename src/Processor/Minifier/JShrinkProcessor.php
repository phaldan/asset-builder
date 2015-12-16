<?php

namespace Phaldan\AssetBuilder\Processor\Minifier;

use JShrink\Minifier as JShrinkMin;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class JShrinkProcessor extends JavaScriptProcessor {

  /**
   * @inheritdoc
   * @throws \Exception
   */
  protected function executeProcessing($filePath) {
    $content = $this->getFileSystem()->getContent($filePath);
    return $this->skipMinifier($filePath) ? $content : JShrinkMin::minify($content);
  }
}