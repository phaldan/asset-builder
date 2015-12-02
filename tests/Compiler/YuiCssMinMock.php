<?php

namespace Phaldan\AssetBuilder\Compiler;

use CSSmin;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class YuiCssMinMock extends CSSmin {

  private $css = [];

  public function __construct() {
  }

  /**
   * @inheritdoc
   */
  public function run($css = '', $linebreak_pos = FALSE) {
    return isset($this->css[$css]) ? $this->css[$css] : null;
  }

  public function set($css, $return) {
    $this->css[$css] = $return;
  }
}