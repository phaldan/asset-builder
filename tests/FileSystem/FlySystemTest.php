<?php

namespace Phaldan\AssetBuilder\FileSystem;

use League\Flysystem\Adapter\Local;
use Phaldan\AssetBuilder\ContextMock;
use PHPUnit_Framework_TestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class FlySystemTest extends PHPUnit_Framework_TestCase {

  /**
   * @var FlySystem
   */
  private $target;

  /**
   * @var ContextMock
   */
  private $context;

  protected function setUp() {
    $this->context = new ContextMock();
    $this->target = new FlySystem($this->context);
  }

  private function mockAdapter() {
    $adapter = new FlyAdapterMock();
    $this->target->setAdapter($adapter);
    return $adapter;
  }

  /**
   * @test
   * @expectedException League\Flysystem\FileNotFoundException
   */
  public function read_fail() {
    $this->mockAdapter();
    $this->target->getContent('file.txt');
  }

  /**
   * @test
   */
  public function read_success() {
    $adapter = $this->mockAdapter();
    $adapter->setHas('file.txt');
    $adapter->setRead('file.txt', 'Lorem ipsum');
    $this->assertSame('Lorem ipsum', $this->target->getContent('file.txt'));
  }

  /**
   * @test
   */
  public function getFlySystem() {
    $this->context->setRootPath(__DIR__);
    $return = $this->target->getFlySystem();
    $this->assertNotNull($return);
    $this->assertInstanceOf(Local::class, $return->getAdapter());
    $this->assertEquals(__DIR__ . DIRECTORY_SEPARATOR, $return->getAdapter()->getPathPrefix());
  }
}
