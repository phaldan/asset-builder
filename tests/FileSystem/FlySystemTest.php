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
  public function getFlySystem_success() {
    $root = __DIR__ . DIRECTORY_SEPARATOR;
    $this->context->setRootPath($root);
    $return = $this->target->getFlySystem();
    $this->assertNotNull($return);
    $this->assertInstanceOf(Local::class, $return->getAdapter());
    $this->assertEquals($root, $return->getAdapter()->getPathPrefix());
  }

  /**
   * @test
   */
  public function getAbsolutePaths_success() {
    $this->context->setRootPath('/absolute/');
    $this->assertEquals(['/absolute/file.txt', '/absolute/style.css'], $this->target->getAbsolutePaths(['file.txt', 'style.css']));
  }

  /**
   * @test
   */
  public function getAbsolutePath_success() {
    $this->context->setRootPath('/absolute/');
    $this->assertEquals('/absolute/file.txt', $this->target->getAbsolutePath('file.txt'));
  }

  /**
   * @test
   */
  public function getAbsolutePath_successForUnix() {
    $this->context->setRootPath('/absolute/');
    $this->assertEquals('/root/file.txt', $this->target->getAbsolutePath('/root/file.txt'));
  }

  /**
   * @test
   */
  public function getAbsolutePath_successForWindows() {
    $this->context->setRootPath('C:\\absolute\\');
    $this->assertEquals('C:\\root\\file.txt', $this->target->getAbsolutePath('C:\\root\\file.txt'));
  }
}
