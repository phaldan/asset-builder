<?php

namespace Phaldan\AssetBuilder\Compiler;

use Less_Parser;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class OyejorgeLessParserMock extends Less_Parser {

  private $return = [];
  private $str;
  private $importDirs;

  public function __construct() {
  }

  public function parse($str, $file_uri = null) {
    $this->str = $str;
    return $this;
  }

  public function getCss() {
    return isset($this->return[$this->str]) ? $this->return[$this->str] : null;
  }

  public function setCss($str, $return) {
    $this->return[$str] = $return;
  }

  public function SetImportDirs($dirs) {
    $this->importDirs = $dirs;
  }

  public function GetImportDirs() {
    return $this->importDirs;
  }


}