<?php

namespace Phaldan\AssetBuilder\Binder;

use Phaldan\AssetBuilder\Compiler\CompilerListStub;
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
   * @var FileSystemMock
   */
  private $fileSystem;

  protected function setUp() {
    $this->fileSystem = new FileSystemMock();
    $this->target = new SerialBinder($this->fileSystem);

    $this->files = new FileList();
  }

  private function assertBind($expected, $compiler) {
    $this->assertEquals($expected, $this->target->bind($this->files, $compiler));
  }

  public function test() {
    $this->files->add('example.css');
    $this->fileSystem->setContent('example.css', 'some-css-content');

    $compiler = new CompilerListStub();
    $compiler->setProcessReturn('example.css', 'success');

    $this->assertBind('success', $compiler);
    $this->assertEquals('some-css-content', $compiler->getProcessContent('example.css'));
  }
}
