<?php

namespace Phaldan\AssetBuilder\Processor\Minifier;

use DateTime;
use Phaldan\AssetBuilder\Processor\ProcessorTestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class JShrinkProcessorTest extends ProcessorTestCase {

  /**
   * @var JShrinkProcessor
   */
  protected $target;

  protected function setUp() {
    parent::setUp();
    $this->target = new JShrinkProcessor($this->fileSystem, $this->cache, $this->context);
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
    $result = $this->target->getFileExtension();
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
    $dateTime = new DateTime();
    $this->fileSystem->setModifiedTime('example.file', $dateTime);

    $this->context->enableMinifier(true);
    $this->assertProcess($this->getExpected(), $this->getContent(), 'example.file');
    $this->assertSame($dateTime, $this->target->getLastModified());
  }

  /**
   * @test
   */
  public function process_fail() {
    $dateTime = new DateTime();
    $this->fileSystem->setModifiedTime('example.file', $dateTime);

    $this->assertProcess($this->getContent(), $this->getContent(), 'example.file');
    $this->assertSame($dateTime, $this->target->getLastModified());
  }

  /**
   * @test
   */
  public function getFiles_success() {
    $time = new DateTime();
    $this->fileSystem->setModifiedTime('example.file', $time);

    $this->assertProcess($this->getContent(), $this->getContent());
    $this->assertArrayHasKey('example.file', $this->target->getFiles());
    $this->assertSame($time, $this->target->getFiles()['example.file']);
  }

  /**
   * @test
   */
  public function process_successSkipMinify() {
    $dateTime = new DateTime();
    $this->fileSystem->setModifiedTime('example.min.file', $dateTime);

    $this->context->enableMinifier(true);
    $this->assertProcess($this->getContent(), $this->getContent(), 'example.min.file');
    $this->assertSame($dateTime, $this->target->getLastModified());
  }

  /**
   * @test
   */
  public function process_successSkipMinifyUpperCase() {
    $dateTime = new DateTime();
    $this->fileSystem->setModifiedTime('example.MIN.file', $dateTime);

    $this->context->enableMinifier(true);
    $this->assertProcess($this->getContent(), $this->getContent(), 'example.MIN.file');
    $this->assertSame($dateTime, $this->target->getLastModified());
  }
}
