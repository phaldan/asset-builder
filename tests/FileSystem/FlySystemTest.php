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

  private function mockFileContent($file, $content) {
    $adapter = $this->mockAdapter();
    $adapter->setHas($file);
    $adapter->setRead($file, $content);
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
    $this->mockFileContent('file.txt', 'Lorem ipsum');
    $this->assertSame('Lorem ipsum', $this->target->getContent('file.txt'));
  }

  /**
   * @test
   */
  public function read_successWithAbsolute() {
    $this->context->setRootPath('/absolute/');
    $this->mockFileContent('test/file.txt', 'Lorem ipsum');
    $this->assertSame('Lorem ipsum', $this->target->getContent('/absolute/test/file.txt'));
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

  /**
   * @test
   */
  public function resolveGlob_success() {
    $this->context->setRootPath(__DIR__ . DIRECTORY_SEPARATOR);
    $result = $this->target->resolveGlob('*.php');
    $this->assertNotEmpty($result);
    $this->assertContains(__FILE__, $result);
  }

  /**
   * @test
   */
  public function setContent_success() {
    $adapter = $this->mockAdapter();
    $this->target->setContent('file.css', 'content');
    $this->assertEquals('content', $adapter->getWrite('file.css'));
  }

  /**
   * @test
   */
  public function exists_success() {
    $adapter = $this->mockAdapter();
    $adapter->setHas('file.css');
    $this->assertTrue($this->target->exists('file.css'));
  }

  /**
   * @test
   */
  public function exists_fail() {
    $this->mockAdapter();
    $this->assertFalse($this->target->exists('file.css'));
  }

  /**
   * @test
   */
  public function getModifiedTime_success() {
    $adapter = $this->mockAdapter();
    $adapter->setTimestamp('file.css', 1337);
    $adapter->setHas('file.css');
    $this->assertEquals(1337, $this->target->getModifiedTime('file.css'));
  }

  /**
   * @test
   * @expectedException League\Flysystem\FileNotFoundException
   */
  public function getModifiedTime_fail() {
    $this->mockAdapter();
    $this->target->getModifiedTime('file.css');
  }
}
