<?php

namespace Phaldan\AssetBuilder\Processor\Minifier;

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
    $this->target = new YuiCssProcessor($this->fileSystem, $this->cache, $this->context);
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
    $expected = "/*! Important comment */\nbody{margin:0;padding:0}";
    $this->assertProcess($expected, $this->getContent());
  }

  /**
   * @test
   */
  public function setCompressor_success() {
    $this->assertSame($this->target, $this->stubCompressor('input', 'output'));
    $this->assertProcess('output', 'input');
  }

  /**
   * @test
   */
  public function process_false() {
    $this->context->enableMinifier(false);
    $this->stubCompressor('input', 'output');
    $this->assertProcess('input', 'input');
  }
}
