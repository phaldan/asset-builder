<?php

namespace Phaldan\AssetBuilder\Binder;

use Phaldan\AssetBuilder\Processor\CompilerListStub;
use Phaldan\AssetBuilder\Processor\CompilerStub;
use Phaldan\AssetBuilder\FileSystem\FileSystemMock;
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
   * @var CompilerListStub
   */
  private $compiler;

  /**
   * @var FileSystemMock
   */
  private $fileSystem;

  protected function setUp() {
    $this->fileSystem = new FileSystemMock();
    $this->target = new SerialBinder($this->fileSystem);

    $this->files = new FileList();
    $this->compiler = new CompilerListStub();
  }

  private function assertBind($expected) {
    $this->assertEquals($expected, $this->target->bind($this->files, $this->compiler));
  }

  private function stubFileWithCompiler($file, $return, $mimeType) {
    $this->files->add($file);
    $this->fileSystem->setContent($file, 'plain');

    $compiler = new CompilerStub();
    $compiler->set('plain', $return);
    $compiler->setOutputMimeType($mimeType);
    $this->compiler->set($file, $compiler);
  }

  private function assertContentType($mimeType) {
    $result = xdebug_get_headers();
    $this->assertNotEmpty($result);
    $this->assertContains(sprintf(SerialBinder::HEADER, $mimeType), $result);
  }

  /**
   * @test
   * @runInSeparateProcess
   */
  public function bind_successWithoutFiles() {
    $this->assertBind('');
    $this->assertEmpty(xdebug_get_headers());
  }

  /**
   * @test
   * @runInSeparateProcess
   */
  public function bind_successSingleFile() {
    $this->stubFileWithCompiler('example.css', 'success', 'text/css');

    $this->assertBind('success');
    $this->assertContentType('text/css');
  }

  /**
   * @test
   * @runInSeparateProcess
   */
  public function bind_successMultipleFiles() {
    $this->stubFileWithCompiler('example1.css', 'success1', 'text/css');
    $this->stubFileWithCompiler('example2.css', 'success2', 'text/css');

    $this->assertBind('success1success2');
    $this->assertContentType('text/css');
  }

  /**
   * @test
   * @expectedException \Exception
   */
  public function bind_successDifferentContentTypes() {
    $this->stubFileWithCompiler('example.css', 'success1', 'text/css');
    $this->stubFileWithCompiler('example.js', 'success2', 'text/javascript');

    $this->assertBind(null);
  }
}
