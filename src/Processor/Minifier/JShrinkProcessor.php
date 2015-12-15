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
    $fs = $this->getFileSystem();
    $content = $fs->getContent($filePath);
    $this->setLastModified($fs->getModifiedTime($filePath));
    return $this->skipMinifier($filePath) ? $content : JShrinkMin::minify($content);
  }
}