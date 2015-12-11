<?php

namespace Phaldan\AssetBuilder\Processor;

use DateTime;
use Phaldan\AssetBuilder\Cache\CacheMock;
use Phaldan\AssetBuilder\ContextMock;
use Phaldan\AssetBuilder\FileSystem\FileSystemMock;
use PHPUnit_Framework_TestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class ProcessorTest extends PHPUnit_Framework_TestCase {

  /**
   * @var ProcessorImpl
   */
  private $target;

  /**
   * @var FileSystemMock
   */
  private $fileSystem;

  /**
   * @var CacheMock
   */
  private $cache;

  /**
   * @var ContextMock
   */
  private $context;

  protected function setUp() {
    $this->fileSystem = new FileSystemMock();
    $this->cache = new CacheMock();
    $this->context = new ContextMock();
    $this->target = new ProcessorImpl($this->fileSystem, $this->cache, $this->context);
    $this->target->setExecuteProcessingReturn('example.css', 'plain');
  }

  /**
   * @test
   */
  public function process_successDisabledCache() {
    $this->context->setCache(false);
    $this->assertEquals('plain', $this->target->process('example.css'));
  }

  /**
   * @test
   */
  public function process_successCacheExpire() {
    $this->context->setCache(true);
    $this->assertEquals('plain', $this->target->process('example.css'));
  }

  /**
   * @test
   */
  public function process_successCacheEntryNotFound() {
    $this->context->setCache(true);
    $this->cache->setHas('example.css', new DateTime());
    $this->fileSystem->setModifiedTime('example.css', new DateTime());
    $this->assertEquals('plain', $this->target->process('example.css'));
  }

  /**
   * @test
   */
  public function process_success() {
    $this->context->setCache(true);
    $this->cache->setHas('example.css', new DateTime());
    $this->fileSystem->setModifiedTime('example.css', new DateTime());
    $this->cache->setEntry('example.css', 'cached');
    $this->assertEquals('cached', $this->target->process('example.css'));
  }

  /**
   * @test
   * @expectedException \Exception
   */
  public function executeProcessing_success() {
    $target = new DummyProcessor();
    $target->executeProcessing('test');
  }
}
