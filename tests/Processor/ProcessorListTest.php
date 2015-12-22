<?php

namespace Phaldan\AssetBuilder\Processor;

use Phaldan\AssetBuilder\Cache\CacheMock;
use Phaldan\AssetBuilder\ContextMock;
use Phaldan\AssetBuilder\FileSystem\FileSystemMock;
use PHPUnit_Framework_TestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class ProcessorListTest extends PHPUnit_Framework_TestCase {

  /**
   * @var ProcessorList
   */
  private $target;

  /**
   * @var ContextMock
   */
  private $context;

  protected function setUp() {
    $this->context = new ContextMock();
    $this->target = new ProcessorList($this->context, new CacheMock(), new FileSystemMock());
  }

  private function stubCompiler($extension) {
    $compiler = new ProcessorStub();
    $compiler->setFileExtension($extension);
    $this->target->add($compiler);
    return $compiler;
  }

  /**
   * @test
   */
  public function get_fail() {
    $this->assertNull($this->target->get('asset/test.css'));
  }

  /**
   * @test
   */
  public function get_success() {
    $compiler = $this->stubCompiler('css');
    $this->assertSame($compiler, $this->target->get('asset/test.css'));
  }

  /**
   * @test
   */
  public function get_successWithCache() {
    $this->context->setCache(true);
    $compiler = $this->stubCompiler('css');
    $result = $this->target->get('asset/test.css');
    $this->assertInstanceOf(CachedProcessor::class, $result);
    $this->assertSame($compiler, $result->getProcessor());
  }

  /**
   * @test
   */
  public function process_success() {
    $compiler = $this->stubCompiler('css');
    $compiler->set('some-css-definition', 'content');
    $this->assertEquals('content', $this->target->process('example.css', 'some-css-definition'));
  }

  /**
   * @test
   * @expectedException \LogicException
   */
  public function process_fail() {
    $processor = new DummyProcessor();
    $this->target->add($processor);
    $this->target->process('example.' . DummyProcessor::EXTENSION, 'some-js-content');
  }
}
