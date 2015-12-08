<?php

namespace Phaldan\AssetBuilder\Binder;

use Phaldan\AssetBuilder\Compiler\CompilerListStub;
use Phaldan\AssetBuilder\Compiler\CompilerStub;
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

  public function test() {
    $file = 'example.css';
    $this->files->add($file);
    $this->fileSystem->setContent($file, 'some-css-content');

    $compiler = new CompilerStub();
    $compiler->set('some-css-content', 'success');
    $list = new CompilerListStub();
    $list->set('example.css', $compiler);

    $this->assertEquals('success', $this->target->bind($this->files, $list));
  }
}
