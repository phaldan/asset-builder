<?php

namespace Phaldan\AssetBuilder\Group;

use PHPUnit_Framework_TestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class FileListTest extends PHPUnit_Framework_TestCase {

  public function test() {
    $target = new FileList(['file1.css', 'module/file2.css']);
    $this->assertNotNull($target->getIterator());
    $this->assertContains('file1.css', $target->getIterator());
    $this->assertContains('module/file2.css', $target->getIterator());
    $this->assertCount(2, $target->getIterator());
  }
}