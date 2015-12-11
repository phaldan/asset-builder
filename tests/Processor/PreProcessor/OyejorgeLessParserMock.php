<?php

namespace Phaldan\AssetBuilder\Processor\PreProcessor;

use Less_Parser;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class OyejorgeLessParserMock extends Less_Parser {

  private $return = [];
  private $str;
  private $importDirs;
  private $option = [];
  private static $files;
  private $reseted = false;

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

  public function SetOption($option, $value) {
    $this->option[$option] = $value;
  }

  public function GetOption($option) {
    return isset($this->option[$option]) ? $this->option[$option] : null;
  }

  public static function AllParsedFiles() {
    return self::$files;
  }

  public static function setParsedFiles(array $files) {
    self::$files = $files;
  }

  public function Reset($options = null) {
    $this->reseted = true;
  }

  public function hasReset() {
    return $this->reseted;
  }
}