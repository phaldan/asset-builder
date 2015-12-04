<?php

namespace Phaldan\AssetBuilder\Group;

use PHPUnit_Framework_TestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class FileListTest extends PHPUnit_Framework_TestCase {

  /**
   * @var FileList
   */
  private $target;

  protected function setUp() {
    $this->target = new FileList();
  }

  /**
   * @test
   */
  public function getIterator_successEmpty() {
    $this->assertNotNull($this->target->getIterator());
    $this->assertCount(0, $this->target->getIterator());
  }

  /**
   * @test
   */
  public function getIterator_successConstructor() {
    $target = new FileList(['file1.css', 'module/file2.css']);
    $this->assertContains('file1.css', $target->getIterator());
    $this->assertContains('module/file2.css', $target->getIterator());
    $this->assertCount(2, $target->getIterator());
  }

  /**
   * @test
   */
  public function add_success() {
    $this->target->add('file.css');
    $this->assertCount(1, $this->target->getIterator());
    $this->assertContains('file.css', $this->target->getIterator());
  }

  /**
   * @test
   */
  public function add_successWithDouble() {
    $this->target->add('file.css');
    $this->target->add('file.css');
    $this->assertCount(2, $this->target->getIterator());
  }

  /**
   * @test
   */
  public function addAll_success() {
    $array = ['file1.css', 'file2.css'];
    $this->target->addAll($array);
    $this->assertCount(2, $this->target->getIterator());
    $this->assertContains('file1.css', $this->target->getIterator());
    $this->assertContains('file2.css', $this->target->getIterator());
  }
}