<?php

namespace Phaldan\AssetBuilder\Binder;

use DateTime;
use Phaldan\AssetBuilder\Processor\ProcessorListStub;
use Phaldan\AssetBuilder\Processor\ProcessorStub;
use Phaldan\AssetBuilder\Group\FileList;
use PHPUnit_Framework_TestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class SerialBinderTest extends PHPUnit_Framework_TestCase {

  /**
   * @var SerialBinder
   */
  private $target;

  /**
   * @var FileList
   */
  private $files;

  /**
   * @var ProcessorListStub
   */
  private $processor;

  protected function setUp() {
    $this->target = new SerialBinder();

    $this->files = new FileList();
    $this->processor = new ProcessorListStub();
  }

  private function executeBind() {
    return $this->target->bind($this->files, $this->processor);
  }

  private function assertBind($expected) {
    $this->assertEquals($expected, $this->executeBind());
    $this->assertNotEmpty($this->target->getLastModified());
    $this->assertNotEmpty($this->target->getMimeType());
  }

  private function stubFileWithProcessor($file, $return, $mimeType) {
    $this->files->add($file);
    $processor = new ProcessorStub();
    $processor->set($file, $return);
    $processor->setOutputMimeType($mimeType);
    $processor->setLastModified($file, new DateTime());
    $processor->setFiles($file, [$file => new DateTime()]);
    $this->processor->set($file, $processor);
  }

  /**
   * @test
   * @runInSeparateProcess
   */
  public function bind_successWithoutFiles() {
    $this->assertEmpty($this->executeBind());
    $this->assertEmpty($this->target->getLastModified());
    $this->assertEmpty($this->target->getMimeType());
  }

  /**
   * @test
   * @runInSeparateProcess
   */
  public function bind_successSingleFile() {
    $this->stubFileWithProcessor('example.css', 'success', 'text/css');

    $this->assertBind('success');
    $this->assertEquals('text/css', $this->target->getMimeType());
    $this->assertArrayHasKey('example.css', $this->target->getFiles());
  }

  /**
   * @test
   * @runInSeparateProcess
   */
  public function bind_successMultipleFiles() {
    $this->stubFileWithProcessor('example1.css', 'success1', 'text/css');
    $this->stubFileWithProcessor('example2.css', 'success2', 'text/css');

    $this->assertBind('success1success2');
    $this->assertEquals('text/css', $this->target->getMimeType());
    $this->assertNotNull($this->target->getFiles());
    $this->assertArrayHasKey('example1.css', $this->target->getFiles());
    $this->assertArrayHasKey('example2.css', $this->target->getFiles());
  }

  /**
   * @test
   * @runInSeparateProcess
   */
  public function bind_successResetFiles() {
    $this->stubFileWithProcessor('example1.css', 'success1', 'text/css');
    $this->stubFileWithProcessor('example2.css', 'success2', 'text/css');
    $this->target->bind($this->files, $this->processor);
    $this->processor = new ProcessorListStub();
    $this->files = new FileList();

    $this->stubFileWithProcessor('example.css', 'success', 'text/css');
    $this->assertBind('success');
    $this->assertArrayHasKey('example.css', $this->target->getFiles());
    $this->assertArrayNotHasKey('example1.css', $this->target->getFiles());
    $this->assertArrayNotHasKey('example2.css', $this->target->getFiles());
  }

  /**
   * @test
   * @expectedException \Exception
   */
  public function bind_successDifferentContentTypes() {
    $this->stubFileWithProcessor('example.css', 'success1', 'text/css');
    $this->stubFileWithProcessor('example.js', 'success2', 'text/javascript');

    $this->executeBind();
    $this->target->getMimeType();
  }
}
