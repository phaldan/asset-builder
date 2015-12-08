<?php

namespace Phaldan\AssetBuilder\Builder;

use ArrayIterator;
use Exception;
use Phaldan\AssetBuilder\Binder\BinderStub;
use Phaldan\AssetBuilder\Processor\ProcessorList;
use Phaldan\AssetBuilder\Processor\ProcessorStub;
use Phaldan\AssetBuilder\Processor\DummyProcessor;
use Phaldan\AssetBuilder\ContextMock;
use Phaldan\AssetBuilder\DependencyInjection\IocContainer;
use Phaldan\AssetBuilder\Group\FileList;
use PHPUnit_Framework_TestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class FluentBuilderTest extends PHPUnit_Framework_TestCase {

  /**
   * @var FluentBuilder
   */
  private $target;

  /**
   * @var BinderStub
   */
  private $binder;

  /**
   * @var ContextMock
   */
  private $context;

  /**
   * @var ProcessorList
   */
  private $compiler;

  /**
   * @var IocContainer
   */
  private $container;

  protected function setUp() {
    $this->binder = new BinderStub();
    $this->context = new ContextMock();
    $this->compiler = new ProcessorList();
    $this->container = new IocContainer();

    $handler = new CompilerHandler($this->compiler, $this->container);
    $executor = new Executor($this->binder, $this->context);
    $this->target = new FluentBuilder($executor, $this->context, $handler);
  }

  private function stubBinder($return) {
    $files = new FileList([]);
    $this->binder->set($files, $return);
    return $files;
  }

  private function createGroupList($return, $name) {
    $files = $this->stubBinder($return);
    $iterator = new ArrayIterator();
    $iterator->offsetSet($name, $files);
    return $iterator;
  }

  /**
   * @test
   * @expectedException Exception
   */
  public function execute_failEmpty() {
    $this->target->execute('test');
  }

  /**
   * @test
   * @expectedException Exception
   */
  public function execute_failNotFound() {
    $this->target->addGroup('group-name', new FileList([]));
    $this->target->execute('test');
  }

  /**
   * @test
   */
  public function addGroups_success() {
    $iterator = $this->createGroupList('success', 'group-name');

    $this->assertSame($this->target, $this->target->addGroups($iterator));
    $this->assertEquals('success', $this->target->execute('group-name'));
    $this->assertEmpty(headers_list());
  }

  /**
   * @test
   * @runInSeparateProcess
   */
  public function addGroups_successWithTiming() {
    $this->context->enableStopWatch(true);
    $iterator = $this->createGroupList('success', 'group-name');

    $this->assertSame($this->target, $this->target->addGroups($iterator));
    $result = sprintf(Executor::COMMENT_TIMING, '0.000');
    $this->assertEquals($result . 'success', $this->target->execute('group-name'));
    $this->assertEmpty(headers_list());
  }

  /**
   * @test
   */
  public function addGroup_success() {
    $files = $this->stubBinder('success');
    $this->assertSame($this->target, $this->target->addGroup('group-name', $files));
    $this->assertEquals('success', $this->target->execute('group-name'));
  }

  /**
   * @test
   */
  public function setRootPath_success() {
    $this->assertSame($this->target, $this->target->setRootPath('test'));
    $this->assertEquals('test', $this->context->getRootPath());
  }

  /**
   * @test
   */
  public function enableMinifier_success() {
    $this->assertSame($this->target, $this->target->enableMinifier(true));
    $this->assertEquals(true, $this->context->hasMinifier());
  }

  /**
   * @test
   */
  public function enableDebug_success() {
    $this->assertSame($this->target, $this->target->enableDebug(true));
    $this->assertEquals(true, $this->context->hasDebug());
  }

  /**
   * @test
   */
  public function enableStopWatch_success() {
    $this->assertSame($this->target, $this->target->enableStopWatch(true));
    $this->assertEquals(true, $this->context->hasStopWatch());
  }

  /**
   * @test
   */
  public function setCachePath_success() {
    $this->assertSame($this->target, $this->target->setCachePath('test'));
    $this->assertEquals('test', $this->context->getCachePath());
  }

  /**
   * @test
   */
  public function addCompiler_success() {
    $compiler = new ProcessorStub();
    $compiler->setSupportedExtension('css');
    $this->assertSame($this->target, $this->target->addCompiler($compiler));
    $this->assertSame($compiler, $this->compiler->get('asset/test.css'));
  }

  /**
   * @test
   */
  public function addCompiler_successWithClass() {
    $this->target->addCompiler(DummyProcessor::class);
    $file = 'file.' . DummyProcessor::EXTENSION;
    $this->assertInstanceOf(DummyProcessor::class, $this->compiler->get($file));
  }

  /**
   * @test
   * @expectedException InvalidArgumentException
   */
  public function add_failNotObjectOrClass() {
    $this->target->addCompiler(1234);
  }

  /**
   * @test
   * @expectedException InvalidArgumentException
   */
  public function add_failNotSubClass() {
    $this->target->addCompiler(new \stdClass());
  }
}