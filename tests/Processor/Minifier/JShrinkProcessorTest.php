<?php

namespace Phaldan\AssetBuilder\Processor\Minifier;

use Phaldan\AssetBuilder\Processor\ProcessorTestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class JShrinkProcessorTest extends ProcessorTestCase {

  /**
   * @var JShrinkProcessor
   */
  private $target;

  protected function setUp() {
    parent::setUp();
    $this->target = new JShrinkProcessor($this->fileSystem, $this->cache, $this->context);
  }

  private function stubFile($file) {
    $content = $this->getContent();
    $this->fileSystem->setContent($file, $content);
  }

  private function getContent() {
    return "
      /*! Important Comment */
      (function ($) {
        /* Waste comment */
        alert('Hello World!'); // Also unnecessary
      }(Tc.$));
    ";
  }

  private function getExpected() {
    return "/*! Important Comment */\n(function($){alert('Hello World!');}(Tc.$));";
  }

  /**
   * @test
   */
  public function getSupportedExtension_success() {
    $result = $this->target->getSupportedExtension();
    $this->assertNotEmpty($result);
    $this->assertEquals(JShrinkProcessor::EXTENSION, $result);
  }

  /**
   * @test
   */
  public function getOutputMimeType_success() {
    $result = $this->target->getOutputMimeType();
    $this->assertNotEmpty($result);
    $this->assertEquals(JShrinkProcessor::MIME_TYPE, $result);
  }

  /**
   * @test
   */
  public function process_success() {
    $this->context->enableMinifier(true);
    $this->stubFile('example.css');
    $this->assertEquals($this->getExpected(), $this->target->executeProcessing('example.css'));
  }

  /**
   * @test
   */
  public function process_fail() {
    $this->stubFile('example.css');
    $this->assertEquals($this->getContent(), $this->target->executeProcessing('example.css'));
  }
}
