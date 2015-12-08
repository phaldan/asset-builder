<?php

namespace Phaldan\AssetBuilder\Processor;

use Phaldan\AssetBuilder\ContextMock;
use PHPUnit_Framework_TestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class JShrinkProcessorTest extends PHPUnit_Framework_TestCase {

  /**
   * @var JShrinkProcessor
   */
  private $target;

  /**
   * @var ContextMock
   */
  private $context;

  protected function setUp() {
    $this->context = new ContextMock();
    $this->target = new JShrinkProcessor($this->context);
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
    $content = $this->getContent();
    $this->assertEquals($this->getExpected(), $this->target->process($content));
  }

  /**
   * @test
   */
  public function process_fail() {
    $content = $this->getContent();
    $this->assertEquals($content, $this->target->process($content));
  }
}
