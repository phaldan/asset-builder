<?php

namespace Phaldan\AssetBuilder\Binder;

use DateTime;
use Phaldan\AssetBuilder\Cache\CacheMock;
use Phaldan\AssetBuilder\Compiler\CompilerListStub;
use Phaldan\AssetBuilder\ContextMock;
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
   * @var ContextMock
   */
  private $context;

  protected function setUp() {
    $this->fileSystem = new FileSystemMock();
    $this->cache = new CacheMock();
    $this->context = new ContextMock();
    $this->target = new CachedSerialBinder($this->fileSystem, $this->cache, $this->context);
  }

  private function stubCache($file, $content, $timeCache, $timeFile) {
    $this->context->setCache(true);
    $this->cache->setEntry($file, $content);
    $this->cache->setHas($file, $timeCache);
    $this->fileSystem->setModifiedTime($file, $timeFile);
  }

  private function stubCompiler($file, $content) {
    $compiler = new CompilerListStub();
    $compiler->setProcessReturn($file, $content);
    return $compiler;
  }

  /**
   * @test
   */
  public function process_fail() {
    $file = 'example.css';
    $compiler = $this->stubCompiler($file, 'content');
    $this->assertEquals('content', $this->target->bind(new FileList([$file]), $compiler));
  }

  /**
   * @test
   */
  public function process_success() {
    $file = 'example.css';
    $time = new DateTime();
    $this->stubCache($file, 'cached', $time, $time);

    $this->assertEquals('cached', $this->target->bind(new FileList([$file]), new CompilerListStub()));
  }

  /**
   * @test
   */
  public function process_failExpire() {
    $file = 'example.css';
    $this->stubCache($file, 'cached', (new DateTime())->setTimestamp(1337), new DateTime());

    $compiler = $this->stubCompiler('example.css', 'content');
    $this->assertEquals('content', $this->target->bind(new FileList([$file]), $compiler));
    $this->assertEquals('content', $this->cache->getEntry($file));
  }
}
