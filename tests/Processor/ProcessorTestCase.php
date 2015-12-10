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
   * @var FileSystemMock
   */
  protected $fileSystem;

  /**
   * @var CacheMock
   */
  protected $cache;

  /**
   * @var ContextMock
   */
  protected $context;

  protected function setUp() {
    $this->fileSystem = new FileSystemMock();
    $this->cache = new CacheMock();
    $this->context = new ContextMock();
  }
}