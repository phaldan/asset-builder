<?php

namespace Phaldan\AssetBuilder\Processor;

use Phaldan\AssetBuilder\Cache\CacheMock;
use Phaldan\AssetBuilder\ContextMock;
use Phaldan\AssetBuilder\FileSystem\FileSystemMock;
use PHPUnit_Framework_TestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class ProcessorTestCase extends PHPUnit_Framework_TestCase {

  /**
   * @var Processor
   */
  protected $target;

  /**
   * @var FileSystemMock
   */
  protected $fileSystem;

  /**
   * @var ContextMock
   */
  protected $context;

  protected function setUp() {
    $this->fileSystem = new FileSystemMock();
    $this->context = new ContextMock();
  }

  protected function assertProcess($expected, $current, $file = 'example.file') {
    $this->fileSystem->setContent($file, $current);
    $this->assertEquals($expected, $this->target->process($file));
  }
}