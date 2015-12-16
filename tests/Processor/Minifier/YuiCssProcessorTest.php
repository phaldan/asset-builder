<?php

namespace Phaldan\AssetBuilder\Processor\Minifier;

use DateTime;
use Phaldan\AssetBuilder\Processor\ProcessorTestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class YuiCssProcessorTest extends ProcessorTestCase {

  /**
   * @var YuiCssProcessor
   */
  protected $target;

  protected function setUp() {
    parent::setUp();
    $this->context->enableMinifier(true);
    $this->target = new YuiCssProcessor($this->fileSystem, $this->context);
  }

  private function stubCompressor($current, $expected) {
    $compressor = new YuiCssMinMock();
    $compressor->set($current, $expected);
    return $this->target->setCompressor($compressor);
  }

  private function getContent() {
    return "
      /*! Important comment */
      body {
        /* Waste comment */
        margin: 0;
        padding: 0;  /* Also unnecessary */
      }
    ";
  }

  /**
   * @test
   */
  public function getSupportedExtension_success() {
    $result = $this->target->getFileExtension();
    $this->assertNotEmpty($result);
    $this->assertEquals(YuiCssProcessor::EXTENSION, $result);
  }

  /**
   * @test
   */
  public function getOutputMimeType_success() {
    $result = $this->target->getOutputMimeType();
    $this->assertNotEmpty($result);
    $this->assertEquals(YuiCssProcessor::MIME_TYPE, $result);
  }

  /**
   * @test
   */
  public function process_success() {
    $file = 'example.file';
    $dateTime = new DateTime();
    $this->fileSystem->setModifiedTime($file, $dateTime);

    $expected = "/*! Important comment */\nbody{margin:0;padding:0}";
    $this->assertProcess($expected, $this->getContent(), $file);
    $this->assertSame($dateTime, $this->target->getLastModified($file));
  }

  /**
   * @test
   */
  public function setCompressor_success() {
    $file = 'example.file';
    $dateTime = new DateTime();
    $this->fileSystem->setModifiedTime($file, $dateTime);

    $this->assertSame($this->target, $this->stubCompressor('input', 'output'));
    $this->assertProcess('output', 'input', $file);
    $this->assertSame($dateTime, $this->target->getLastModified($file));
  }

  /**
   * @test
   */
  public function process_false() {
    $file = 'example.file';
    $dateTime = new DateTime();
    $this->fileSystem->setModifiedTime($file, $dateTime);

    $this->context->enableMinifier(false);
    $this->stubCompressor('input', 'output');
    $this->assertProcess('input', 'input', $file);
    $this->assertSame($dateTime, $this->target->getLastModified($file));
  }

  /**
   * @test
   */
  public function getFiles_success() {
    $file = 'example.file';
    $time = new DateTime();
    $this->fileSystem->setModifiedTime($file, $time);

    $this->stubCompressor('input', 'output');
    $this->assertProcess('output', 'input');
    $this->assertArrayHasKey($file, $this->target->getFiles($file));
    $this->assertSame($time, $this->target->getFiles($file)[$file]);
  }
}
