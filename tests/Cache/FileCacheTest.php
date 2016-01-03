<?php

namespace Phaldan\AssetBuilder\Cache;

use DateTime;
use Phaldan\AssetBuilder\ContextMock;
use Phaldan\AssetBuilder\FileSystem\FileSystemMock;
use PHPUnit_Framework_TestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class FileCacheTest extends PHPUnit_Framework_TestCase {

  /**
   * @var FileCache
   */
  private $target;

  /**
   * @var ContextMock
   */
  private $context;

  /**
   * @var FileSystemMock
   */
  private $fileSystem;

  protected function setUp() {
    $this->context = new ContextMock();
    $this->fileSystem = new FileSystemMock();

    $this->target = new FileCache($this->context, $this->fileSystem);
  }

  /**
   * @test
   */
  public function getFilePath_success() {
    $this->context->setCachePath('/cache/');
    $result = $this->target->getFilePath('/path/file.txt');
    $this->assertNotEmpty($result);
    $this->assertStringStartsWith('/cache/', $result);
    $this->assertContains(md5('/path/file.txt'), $result);
  }

  /**
   * @test
   */
  public function getEntry_success() {
    $key = '/path/file.txt';
    $this->fileSystem->setContent($this->target->getFilePath($key), 'content');
    $this->assertEquals('content', $this->target->getEntry($key));
  }

  /**
   * @test
   */
  public function getEntry_fail() {
    $key = '/path/file.txt';
    $this->assertNull($this->target->getEntry($key));
  }

  /**
   * @test
   */
  public function setEntry_success() {
    $key = '/path/file.txt';
    $this->target->setEntry($key, 'content');
    $this->assertEquals('content', $this->fileSystem->getContent($this->target->getFilePath($key)));
  }

  /**
   * @test
   */
  public function hasEntry_fail() {
    $this->assertFalse($this->target->hasEntry('/path/file.txt'));
  }

  /**
   * @test
   */
  public function hasEntry_failExpire() {
    $this->assertFalse($this->target->hasEntry('/path/file.txt', new DateTime()));
  }

  /**
   * @test
   */
  public function hasEntry_failExpireToOld() {
    $key = '/path/file.txt';
    $this->fileSystem->setExists($this->target->getFilePath($key));
    $this->fileSystem->setModifiedTime($this->target->getFilePath($key), (new DateTime())->setTimestamp(1337));
    $this->assertFalse($this->target->hasEntry($key, new DateTime()));
  }

  /**
   * @test
   */
  public function hasEntry_success() {
    $key = '/path/file.txt';
    $this->fileSystem->setExists($this->target->getFilePath($key));
    $this->assertTrue($this->target->hasEntry($key));
  }

  /**
   * @test
   */
  public function hasEntry_successExpire() {
    $key = '/path/file.txt';
    $expire = new DateTime();
    $this->fileSystem->setExists($this->target->getFilePath($key));
    $this->fileSystem->setModifiedTime($this->target->getFilePath($key), $expire);
    $this->assertTrue($this->target->hasEntry($key, $expire));
  }

  /**
   * @test
   */
  public function deleteEntry_success() {
    $key = '/path/file.txt';
    $path = $this->target->getFilePath($key);
    $this->fileSystem->setExists($path);
    $this->target->deleteEntry($key);
    $this->assertTrue($this->fileSystem->hasDeleted($path));
  }
}
