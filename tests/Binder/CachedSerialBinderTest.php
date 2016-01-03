<?php

namespace Phaldan\AssetBuilder\Binder;

use DateTime;
use Phaldan\AssetBuilder\Cache\CacheMock;
use Phaldan\AssetBuilder\Processor\ProcessorListStub;
use Phaldan\AssetBuilder\FileSystem\FileSystemMock;
use Phaldan\AssetBuilder\Group\FileList;
use PHPUnit_Framework_TestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class CachedSerialBinderTest extends PHPUnit_Framework_TestCase {

  /**
   * @var CachedSerialBinder
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
   * @var BinderStub
   */
  private $binder;

  protected function setUp() {
    $this->fileSystem = new FileSystemMock();
    $this->cache = new CacheMock();
    $this->binder = new BinderStub();
    $this->target = new CachedSerialBinder($this->fileSystem, $this->cache, $this->binder);
  }

  private function executeBind($iterator) {
    return $this->target->bind($iterator, new ProcessorListStub());
  }

  private function assertBind($iterator, $expected) {
    $this->assertEquals($expected, $this->executeBind($iterator));
    $this->assertNotEmpty($this->target->getLastModified());
    $this->assertEquals('text/css', $this->target->getMimeType());
  }

  private function assertCache($iterator, $content, $files) {
    $cache = $this->cache->getEntry($this->target->generateCacheKey($iterator));
    $entry = new CacheBinderEntry();
    $result = $entry->unserialize($cache);
    $this->assertEquals($content, $result->getContent());
    $this->assertEquals($files, $result->getFiles());
    $this->assertNotEmpty($result->getLastModified());
  }

  private function assertCacheEntry($iterator, $content, $files) {
    $entry = $this->cache->getEntry($this->target->generateCacheKey($iterator));
    $this->assertNotNull($entry);
    $this->assertInstanceOf(CacheBinderEntry::class, $entry);
    $this->assertEquals($content, $entry->getContent());
    $this->assertEquals($files, $entry->getFiles());
    $this->assertNotEmpty($entry->getLastModified());
  }

  private function setCache($iterator, $value, $files) {
    $key = $this->target->generateCacheKey($iterator);
    $entry = new CacheBinderEntry($value, $files, new DateTime());
    $entry->setMimeType('text/css');
    $this->cache->setHas($key);
    $this->cache->setEntry($key, $entry->serialize());
  }

  private function stubBinder($iterator, $return, $files, $time) {
    $this->binder->set($iterator, $return);
    $this->binder->setFiles($files);
    $this->binder->setLastModified($time);
    $this->binder->setMimeType('text/css');
  }

  /**
   * @test
   */
  public function bind_successCacheSet() {
    $iterator = new FileList(['example1.file', 'example2.file']);
    $this->stubBinder($iterator, 'Lorem Ipsum', ['example.file'], new DateTime());

    $this->assertBind($iterator, 'Lorem Ipsum');
    $this->assertCacheEntry($iterator, 'Lorem Ipsum', ['example.file']);
  }

  /**
   * @test
   */
  public function bind_successCacheGet() {
    $iterator = new FileList(['example1.file', 'example2.file']);
    $files = ['example.file' => new DateTime()];
    $this->fileSystem->setModifiedTime('example.file', new DateTime());

    $this->setCache($iterator, 'cached', $files);

    $this->assertBind($iterator, 'cached');
    $this->assertCache($iterator, 'cached', $files);
  }

  /**
   * @test
   */
  public function getFiles_fail() {
    $this->assertEmpty($this->target->getFiles());
  }

  /**
   * @test
   */
  public function getFiles_success() {
    $file = 'example.file';
    $time = new DateTime();
    $iterator = new FileList([$file]);
    $this->stubBinder($iterator, 'string', [$file => $time], $time);

    $this->executeBind($iterator);
    $this->assertNotEmpty($this->target->getFiles());
    $this->assertArrayHasKey($file, $this->target->getFiles());
  }

  /**
   * @test
   */
  public function getFiles_successFromCache() {
    $file = 'example.file';
    $time = new DateTime();
    $iterator = new FileList([$file]);

    $this->fileSystem->setModifiedTime($file, $time);
    $this->setCache($iterator, 'cached', [$file => $time]);
    $this->executeBind($iterator);

    $this->assertNotEmpty($this->target->getFiles());
    $this->assertArrayHasKey($file, $this->target->getFiles());
  }

  /**
   * @test
   */
  public function bind_successCacheTooOld() {
    $newerTime = new DateTime();
    $newerTime->setTimestamp($newerTime->getTimestamp() + 42);

    $file = 'example.file';
    $iterator = new FileList([$file]);
    $files = [$file => $newerTime];

    $this->fileSystem->setModifiedTime($file, $newerTime);

    $this->stubBinder($iterator, 'Lorem Ipsum', $files, new DateTime());
    $this->setCache($iterator, 'cached', [$file => new DateTime()]);

    $this->assertBind($iterator, 'Lorem Ipsum');
    $this->assertCacheEntry($iterator, 'Lorem Ipsum', $files);
  }
}
