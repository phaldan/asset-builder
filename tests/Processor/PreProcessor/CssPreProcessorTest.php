<?php

namespace Phaldan\AssetBuilder\Processor\PreProcessor;

use Phaldan\AssetBuilder\ContextMock;
use Phaldan\AssetBuilder\FileSystem\FileSystemMock;
use PHPUnit_Framework_TestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class CssPreProcessorTest extends PHPUnit_Framework_TestCase {

  /**
   * @expectedException \LogicException
   */
  public function test() {
    $target = new CssPreProcessorImpl(new FileSystemMock(), new ContextMock());
    $target->getLastModified('example.file');
  }
}
