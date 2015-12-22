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

  private function stubFile($file, $content, $time) {
    $this->fileSystem->setModifiedTime($file, $time);
    $this->stubProcessor($file, $content, $time, [$file => $time]);
  }

  private function stubCacheEntry($content, $file, $time, $files = null) {
    $list = is_null($files) ? [$file => $time] : $files;
    $entry = new CacheEntry($content, $list, $time);
    $this->cache->setHas($file, $time);
    $this->cache->setEntry($file, $entry->serialize());
    return $entry;
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
  public function process_successPersistentCached() {
    $file = 'example.file';
    $time = new DateTime();

    $entry = $this->stubCacheEntry('cached', $file, $time);
    $this->stubFile($file, 'content', $time);

    $this->assertEquals('cached', $this->target->process($file));
    $this->assertEquals($entry->serialize(), $this->cache->getEntry($file));
  }

  /**
   * @test
   */
  public function process_failPersistentCacheTooOld() {
    $file = 'example.file';
    $cacheTime = new DateTime();
    $newTime = (new DateTime())->setTimestamp($cacheTime->getTimestamp() + 42);

    $this->stubCacheEntry('cached', $file, $cacheTime);
    $this->stubFile($file, 'content', $newTime);

    $this->assertEquals('content', $this->target->process($file));
    $this->assertCacheEntry($file, 'content', $newTime, [$file => $newTime]);
  }

  /**
   * @test
   */
  public function process_successInternalRequestEntryCache() {
    $file = 'example.file';
    $time = new DateTime();
    $files = [$file => $time];

    $this->stubProcessor($file, 'content', $time, $files);
    $this->target->process($file);
    $this->cache->setHas($file);
    $this->stubProcessor($file, 'changed', $time, $files);

    $this->assertEquals('content', $this->target->process($file));
    $this->assertCacheEntry($file, 'content', $time, $files);
  }

  /**
   * @test
   */
  public function process_successInternalFileChangedCache() {
    $file1 = 'example1.file';
    $file2 = 'example2.file';
    $time = new DateTime();
    $newTime = (new DateTime())->setTimestamp($time->getTimestamp() + 42);

    $entry1 = $this->stubCacheEntry('cached1', $file1, $time);
    $this->stubFile($file1, 'content', $time);
    $this->target->process($file1);

    $entry2 = $this->stubCacheEntry('cached2', $file2, $time, [$file2 => $time, $file1 => $time]);
    $this->stubFile($file1, 'content', $newTime);
    $this->stubFile($file2, 'content', $time);

    $this->assertEquals('cached2', $this->target->process($file2));
    $this->assertEquals($entry1->serialize(), $this->cache->getEntry($file1));
    $this->assertEquals($entry2->serialize(), $this->cache->getEntry($file2));
  }
}
