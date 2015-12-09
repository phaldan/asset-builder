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
class CachingTest extends PHPUnit_Framework_TestCase {

  /**
   * @var Caching
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
    $this->target = new Caching($this->fileSystem, $this->cache, $this->context);
  }

  private function stubEntry($file, $content) {
    $this->target->set($file, $content);
    $this->cache->setHas($file, new DateTime());
    $this->fileSystem->setModifiedTime($file, new DateTime());
  }

  /**
   * @test
   */
  public function get_failDisabledCache() {
    $this->stubEntry('example.css', 'content');
    $this->assertNull($this->target->get('example.css'));
  }

  /**
   * @test
   */
  public function get_failNotFound() {
    $this->context->setCache(true);
    $this->assertNull($this->target->get('example'));
  }

  /**
   * @test
   */
  public function get_failExpire() {
    $this->context->setCache(true);
    $this->target->set('example', 'content');
    $this->assertNull($this->target->get('example'));
  }

  /**
   * @test
   */
  public function get_success() {
    $this->context->setCache(true);
    $this->stubEntry('example', 'content');
    $this->assertEquals('content', $this->target->get('example'));
  }

  /**
   * @test
   */
  public function set_fail() {
    $this->context->setCache(true);
    $this->stubEntry('example', 'content');
    $this->context->setCache(false);
    $this->target->set('example', '42');
    $this->context->setCache(true);
    $this->assertEquals('content', $this->target->get('example'));
  }
}
