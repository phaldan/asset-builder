<?php

namespace Phaldan\AssetBuilder\Processor;

use DateTime;
use Phaldan\AssetBuilder\Cache\CacheMock;
use Phaldan\AssetBuilder\FileSystem\FileSystemMock;
use PHPUnit_Framework_TestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class CachedProcessorTest extends PHPUnit_Framework_TestCase {

  /**
   * @var CachedProcessor
   */
  private $target;

  /**
   * @var ProcessorStub
   */
  private $processor;

  /**
   * @var CacheMock
   */
  private $cache;

  /**
   * @var FileSystemMock
   */
  private $fileSystem;

  protected function setUp() {
    $this->processor = new ProcessorStub();
    $this->cache = new CacheMock();
    $this->fileSystem = new FileSystemMock();
    $this->target = new CachedProcessor($this->processor, $this->cache, $this->fileSystem);
  }

  private function stubProcessor($file, $content, $time, $files) {
    $this->processor->set($file, $content);
    $this->processor->setLastModified($file, $time);
    $this->processor->setFiles($file, $files);
  }

  private function assertCacheEntry($file, $content, $lastModified, $files) {
    $cache = $this->cache->getEntry($file);
    $this->assertNotNull($cache);
    $this->assertInstanceOf(CacheEntry::class, $cache);
    $this->assertEquals($content, $cache->getContent());
    $this->assertSame($lastModified, $cache->getLastModified());
    $this->assertEquals($files, $cache->getFiles());
  }

  /**
   * @test
   */
  public function getFileExtension_success() {
    $this->processor->setFileExtension('example.file');
    $this->assertEquals('example.file', $this->target->getFileExtension());
    $this->assertNull($this->cache->getEntry('example.file'));
  }

  /**
   * @test
   */
  public function getOutputMimeType_success() {
    $this->processor->setOutputMimeType('example.file');
    $this->assertEquals('example.file', $this->target->getOutputMimeType());
    $this->assertNull($this->cache->getEntry('example.file'));
  }

  /**
   * @test
   */
  public function getLastModified_success() {
    $time = new DateTime();
    $files = ['example.file' => $time];
    $this->stubProcessor('example.file', 'content', $time, $files);

    $this->processor->setLastModified('example.file', $time);
    $this->assertSame($time, $this->target->getLastModified('example.file'));
    $this->assertCacheEntry('example.file', 'content', $time, $files);
  }

  /**
   * @test
   */
  public function getFiles_success() {
    $time = new DateTime();
    $files = ['example.file' => $time];
    $this->stubProcessor('example.file', 'content', $time, $files);

    $this->assertEquals($files, $this->target->getFiles('example.file'));
    $this->assertCacheEntry('example.file', 'content', $time, $files);
  }

  /**
   * @test
   */
  public function process_success() {
    $time = new DateTime();
    $files = ['example.file' => $time];
    $this->stubProcessor('example.file', 'content', $time, $files);

    $this->assertEquals('content', $this->target->process('example.file'));
    $this->assertCacheEntry('example.file', 'content', $time, $files);
  }

  /**
   * @test
   */
  public function process_successCached() {
    $file = 'example.file';
    $time = new DateTime();
    $files = [$file => $time];

    $entry = new CacheEntry('cached', $files);
    $this->cache->setHas($file, $time);
    $this->cache->setEntry($file, $entry->serialize());
    $this->fileSystem->setModifiedTime($file, $time);
    $this->stubProcessor($file, 'content', $time, $files);

    $this->assertEquals('cached', $this->target->process($file));
    $this->assertEquals($entry->serialize(), $this->cache->getEntry($file));
  }

  /**
   * @test
   */
  public function process_failCacheTooOld() {
    $file = 'example.file';
    $cacheTime = new DateTime();
    $newTime = (new DateTime())->setTimestamp($cacheTime->getTimestamp() + 42);

    $entry = new CacheEntry('cached', [$file => $cacheTime]);
    $this->cache->setHas($file);
    $this->cache->setEntry($file, $entry->serialize());

    $this->fileSystem->setModifiedTime($file, $newTime);
    $this->stubProcessor($file, 'content', $newTime, [$file => $newTime]);

    $this->assertEquals('content', $this->target->process($file));
    $this->assertCacheEntry($file, 'content', $newTime, [$file => $newTime]);
  }
}
