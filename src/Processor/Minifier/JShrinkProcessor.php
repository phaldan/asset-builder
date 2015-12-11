<?php

namespace Phaldan\AssetBuilder\Processor\Minifier;

use JShrink\Minifier;

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
    return $this->getContext()->hasMinifier() ? Minifier::minify($content) : $content;
  }
}