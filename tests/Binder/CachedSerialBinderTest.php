<?php

namespace Phaldan\AssetBuilder\Binder;

use DateTime;
use Phaldan\AssetBuilder\Cache\CacheMock;
use Phaldan\AssetBuilder\Processor\CacheEntry;
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
  }

  private function assertCache($iterator, $content, $files) {
    $cache = $this->cache->getEntry($this->target->generateCacheKey($iterator));
    $entry = new CacheEntry();
    $result = $entry->unserialize($cache);
    $this->assertEquals($content, $result->getContent());
    $this->assertEquals($files, $result->getFiles());
  }

  private function assertCacheEntry($iterator, $content, $files) {
    $entry = $this->cache->getEntry($this->target->generateCacheKey($iterator));
    $this->assertNotNull($entry);
    $this->assertInstanceOf(CacheEntry::class, $entry);
    $this->assertEquals($content, $entry->getContent());
    $this->assertEquals($files, $entry->getFiles());
  }

  private function setCache($iterator, $value, $files) {
    $key = $this->target->generateCacheKey($iterator);
    $entry = new CacheEntry($value, $files);
    $this->cache->setHas($key);
    $this->cache->setEntry($key, $entry->serialize());
  }

  /**
   * @test
   */
  public function bind_successCacheSet() {
    $iterator = new FileList(['example1.file', 'example2.file']);
    $this->binder->set($iterator, 'Lorem Ipsum');
    $this->binder->setFiles(['example.file']);

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

    $this->binder->set($iterator, 'Lorem Ipsum');
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
    $iterator = new FileList(['example.file']);
    $this->binder->set($iterator, 'string');
    $this->binder->setFiles(['example.file' => new DateTime()]);

    $this->executeBind($iterator);
    $this->assertNotEmpty($this->target->getFiles());
    $this->assertArrayHasKey('example.file', $this->target->getFiles());
  }

  /**
   * @test
   */
  public function getFiles_successFromCache() {
    $iterator = new FileList(['example.file']);
    $files = ['example.file' => new DateTime()];
    $this->fileSystem->setModifiedTime('example.file', new DateTime());

    $this->binder->set($iterator, 'string');
    $this->setCache($iterator, 'cached', $files);
    $this->executeBind($iterator);

    $this->assertNotEmpty($this->target->getFiles());
    $this->assertArrayHasKey('example.file', $this->target->getFiles());
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

    $this->binder->set($iterator, 'Lorem Ipsum');
    $this->binder->setFiles($files);
    $this->setCache($iterator, 'cached', [$file => new DateTime()]);

    $this->assertBind($iterator, 'Lorem Ipsum');
    $this->assertCacheEntry($iterator, 'Lorem Ipsum', $files);
  }
}
