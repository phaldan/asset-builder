<?php

namespace Phaldan\AssetBuilder\Builder;

use ArrayIterator;
use Exception;
use Phaldan\AssetBuilder\Binder\BinderStub;
use Phaldan\AssetBuilder\ContextMock;
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

  protected function setUp() {
    $this->binder = new BinderStub();
    $this->context = new ContextMock();
    $this->target = new FluentBuilder($this->binder, $this->context);
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
    $this->target->setRootPath('test');
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
}