<?php

namespace Phaldan\AssetBuilder\Builder;

use ArrayIterator;
use Exception;
use Phaldan\AssetBuilder\Binder\BinderStub;
use Phaldan\AssetBuilder\Compiler\CompilerList;
use Phaldan\AssetBuilder\Compiler\CompilerStub;
use Phaldan\AssetBuilder\Compiler\DummyCompiler;
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
   * @var CompilerList
   */
  private $compiler;

  /**
   * @var IocContainer
   */
  private $container;

  protected function setUp() {
    $this->binder = new BinderStub();
    $this->context = new ContextMock();
    $this->compiler = new CompilerList();
    $this->container = new IocContainer();
    $this->target = new FluentBuilder($this->binder, $this->context, $this->compiler, $this->container);
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
    $this->target->addGroups($iterator);

    $this->assertEquals('success', $this->target->execute('group-name'));
  }

  /**
   * @test
   */
  public function addGroup_success() {
    $files = $this->stubBinder('success');
    $this->target->addGroup('group-name', $files);

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
    $compiler = new CompilerStub();
    $compiler->setSupportedExtension('css');
    $this->assertSame($this->target, $this->target->addCompiler($compiler));
    $this->assertSame($compiler, $this->compiler->get('asset/test.css'));
  }

  /**
   * @test
   */
  public function addCompiler_successWithClass() {
    $this->target->addCompiler(DummyCompiler::class);
    $file = 'file.' . DummyCompiler::EXTENSION;
    $this->assertInstanceOf(DummyCompiler::class, $this->compiler->get($file));
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