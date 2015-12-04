<?php

namespace Phaldan\AssetBuilder\Group;

use Phaldan\AssetBuilder\FileSystem\FileSystemMock;
use PHPUnit_Framework_TestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class GlobFileListTest extends PHPUnit_Framework_TestCase {

  /**
   * @var GlobFileList
   */
  private $target;

  /**
   * @var FileSystemMock
   */
  private $fileSystem;

  protected function setUp() {
    $this->fileSystem = new FileSystemMock();
    $this->target = new GlobFileList($this->fileSystem);
  }

  private function getIteratorGlob($file) {
    $this->fileSystem->setGlob($file, [$file]);
    $this->target->add($file);
    return $this->target->getIterator();
  }

  /**
   * @test
   */
  public function getIterator_success() {
    $expected = ['assets/css/file1.css', 'assets/css/file2.css'];
    $this->fileSystem->setGlob('assets/css/*.css', $expected);
    $this->target->add('assets/css/*.css');

    $result = $this->target->getIterator();
    $this->assertNotEmpty($result);
    $this->assertContains('assets/css/file1.css', $result);
    $this->assertContains('assets/css/file2.css', $result);
  }

  /**
   * @test
   */
  public function add_success() {
    $file1 = 'file1.css';
    $iterator1 = $this->getIteratorGlob($file1);
    $file2 = 'file2.css';
    $iterator2 = $this->getIteratorGlob($file2);

    $this->assertNotSame($iterator1, $iterator2);
    $this->assertContains($file1, $iterator1);
    $this->assertNotContains($file2, $iterator1);
    $this->assertContains($file1, $iterator2);
    $this->assertContains($file2, $iterator2);
  }
}
