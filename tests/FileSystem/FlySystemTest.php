<?php

namespace Phaldan\AssetBuilder\FileSystem;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Cached\CachedAdapter;
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

  private function mockAdapter($root) {
    $this->context->setRootPath($root);
    $adapter = new FlyAdapterMock();
    $this->target->setAdapter($adapter, $root);
    return $adapter;
  }

  private function mockFileContent($root, $file, $content) {
    $adapter = $this->mockAdapter($root);
    $adapter->setHas($file);
    $adapter->setRead($file, $content);
    return $adapter;
  }

  private function assertGlob($expected, $pattern) {
    $this->context->setRootPath(__DIR__ . DIRECTORY_SEPARATOR);
    $result = $this->target->resolveGlob($pattern);
    $this->assertNotEmpty($result);
    $this->assertContains($expected, $result);
  }

  /**
   * @test
   * @expectedException League\Flysystem\FileNotFoundException
   */
  public function read_fail() {
    $this->mockAdapter('/absolute/');
    $this->target->getContent('file.txt');
  }

  /**
   * @test
   */
  public function read_successRelative() {
    $this->mockFileContent('/absolute/', 'file.txt', 'Lorem ipsum');
    $this->assertSame('Lorem ipsum', $this->target->getContent('file.txt'));
  }

  /**
   * @test
   */
  public function read_successWithAbsolute() {
    $this->mockFileContent('/absolute/', 'test/file.txt', 'Lorem ipsum');
    $this->assertSame('Lorem ipsum', $this->target->getContent('/absolute/test/file.txt'));
  }

  /**
   * @test
   */
  public function read_successDifferentAbsolute() {
    $this->mockAdapter('/absolute/');
    $adapter = new FlyAdapterMock();
    $this->target->setAdapter($adapter, '/tmp/');
    $adapter->setHas('example.txt');
    $adapter->setRead('example.txt', 'Lorem ipsum');
    $this->assertSame('Lorem ipsum', $this->target->getContent('/tmp/example.txt'));
  }

  /**
   * @test
   */
  public function getFlySystem_success() {
    $root = __DIR__ . DIRECTORY_SEPARATOR;
    $this->context->setRootPath($root);
    $return = $this->target->getFlySystem($root);
    $this->assertNotNull($return);
    $this->assertInstanceOf(CachedAdapter::class, $return->getAdapter());
    $this->assertInstanceOf(Local::class, $return->getAdapter()->getAdapter());
    $this->assertEquals($root, $return->getAdapter()->getAdapter()->getPathPrefix());
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
  public function resolveGlob_successSpecific() {
    $this->assertGlob(__FILE__, basename(__FILE__));
  }

  /**
   * @test
   */
  public function resolveGlob_successMultipleChar() {
    $this->assertGlob(__FILE__, '*.php');
  }

  /**
   * @test
   */
  public function resolveGlob_successSingleChar() {
    $file = basename(__FILE__);
    $this->assertGlob(__FILE__, '?' . substr($file, 1));
  }

  /**
   * @test
   */
  public function resolveGlob_successSpecificChars() {
    $file = basename(__FILE__);
    $this->assertGlob(__FILE__, '[A-Z]' . substr($file, 1));
  }

  /**
   * @test
   */
  public function resolveGlob_successSpecificCharList() {
    $file = basename(__FILE__);
    $this->assertGlob(__FILE__, '[EFG]' . substr($file, 1));
  }

  /**
   * @test
   */
  public function resolveGlob_successNegateSpecificCharList() {
    $file = basename(__FILE__);
    $this->assertGlob(__FILE__, '[!A-D]' . substr($file, 1));
  }

  /**
   * @test
   */
  public function resolveGlob_successList() {
    $this->assertGlob(__FILE__, '*.{php,sh}');
  }

  /**
   * @test
   */
  public function setContent_success() {
    $adapter = $this->mockAdapter('/absolute/');
    $this->target->setContent('file.css', 'content');
    $this->assertEquals('content', $adapter->getWrite('file.css'));
  }

  /**
   * @test
   */
  public function exists_success() {
    $adapter = $this->mockAdapter('/absolute/');
    $adapter->setHas('file.css');
    $this->assertTrue($this->target->exists('file.css'));
  }

  /**
   * @test
   */
  public function exists_fail() {
    $this->mockAdapter('/absolute/');
    $this->assertFalse($this->target->exists('file.css'));
  }

  /**
   * @test
   */
  public function getModifiedTime_success() {
    $result = $this->target->getModifiedTime(__FILE__);
    $this->assertNotNull($result);
  }

  /**
   * @test
   * @expectedException \InvalidArgumentException
   */
  public function getModifiedTime_fail() {
    $this->mockAdapter('/absolute/');
    $this->target->getModifiedTime('file.css');
  }
}
