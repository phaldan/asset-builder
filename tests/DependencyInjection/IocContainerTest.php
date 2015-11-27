<?php

namespace Phaldan\AssetBuilder\DependencyInjection;

use Exception;
use InvalidArgumentException;
use PHPUnit_Framework_TestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class IocContainerTest extends PHPUnit_Framework_TestCase {

  /**
   * @var IocContainer
   */
  private $target;

  protected function setUp() {
    $this->target = new IocContainer();
  }

  /**
   * @test
   */
  public function getInstance_successWithDummyBasic() {
    $instance = $this->target->getInstance(DummyBasic::class);
    $this->assertNotNull($instance);
    $this->assertInstanceOf(DummyBasic::class, $instance);
  }

  /**
   * @test
   */
  public function getInstance_successSingleton() {
    $instance = $this->target->getInstance(DummyBasic::class);
    $this->assertSame($instance, $this->target->getInstance(DummyBasic::class));
  }

  /**
   * @test
   * @expectedException InvalidArgumentException
   */
  public function getInstance_failWithInterface() {
    $this->target->getInstance(DummyInterface::class);
  }

  /**
   * @test
   * @expectedException InvalidArgumentException
   */
  public function getInstance_failWithAbstract() {
    $this->target->getInstance(DummyAbstract::class);
  }

  /**
   * @test
   * @expectedException InvalidArgumentException
   */
  public function register_failWithUnrelatedClasses() {
    $this->target->register(DummyInterface::class, DummyAbstractSubclass::class);
  }

  /**
   * @test
   * @expectedException InvalidArgumentException
   */
  public function register_failWithInterfaceAsConcreteClass() {
    $this->target->register(DummyInterface::class, DummyInterfaceExtension::class);
  }

  /**
   * @test
   * @expectedException InvalidArgumentException
   */
  public function register_failWithAbstractAsConcreteClass() {
    $this->target->register(DummyAbstract::class, DummyAbstractExtension::class);
  }

  /**
   * @test
   */
  public function register_successWithInterface() {
    $this->target->register(DummyInterface::class, DummyInterfaceSubclass::class);
    $instance = $this->target->getInstance(DummyInterface::class);
    $this->assertNotNull($instance);
    $this->assertInstanceOf(DummyInterfaceSubclass::class, $instance);
  }

  /**
   * @test
   */
  public function register_successWithAbstract() {
    $this->target->register(DummyAbstract::class, DummyAbstractSubclass::class);
    $instance = $this->target->getInstance(DummyAbstract::class);
    $this->assertNotNull($instance);
    $this->assertInstanceOf(DummyAbstractSubclass::class, $instance);
  }

  /**
   * @test
   */
  public function register_successWithInstance() {
    $instance = new DummyAbstractSubclass();
    $this->target->register(DummyAbstract::class, $instance);
    $this->assertSame($instance, $this->target->getInstance(DummyAbstract::class));
  }

  /**
   * @test
   * @expectedException InvalidArgumentException
   */
  public function register_failWithInstance() {
    $this->target->register(DummyInterface::class, new DummyAbstractSubclass());
  }

  /**
   * @test
   */
  public function register_successSameInstance() {
    $instance = new DummyBasic();
    $this->target->register(DummyBasic::class, $instance);
    $this->assertSame($instance, $this->target->getInstance(DummyBasic::class));
  }

  /**
   * @test
   */
  public function getInstance_successWithParameter() {
    $instance = $this->target->getInstance(SpyDummy::class);
    $this->assertNotNull($instance);
    $this->assertInstanceOf(SpyDummy::class, $instance);
    $this->assertNotNull($instance->getDummy());
  }

  /**
   * @test
   */
  public function getInstance_successWithRegisteredParameter() {
    $dummy = new DummyBasic();
    $this->target->register(DummyBasic::class, $dummy);
    $instance = $this->target->getInstance(SpyDummy::class);
    $this->assertNotNull($instance);
    $this->assertInstanceOf(SpyDummy::class, $instance);
    $this->assertSame($dummy, $instance->getDummy());
  }

  /**
   * @test
   */
  public function getInstance_successWithContainer() {
    $this->assertSame($this->target, $this->target->getInstance(IocContainer::class));
  }

  /**
   * @test
   * @expectedException InvalidArgumentException
   */
  public function getInstance_failStaticParameter() {
    $this->target->getInstance(DummyStaticParameter::class);
  }

  /**
   * @test
   * @expectedException InvalidArgumentException
   */
  public function getInstance_failArrayParameter() {
    $this->target->getInstance(DummyArrayParameter::class);
  }

  /**
   * @test
   * @expectedException Exception
   */
  public function getInstance_failWithRandomString() {
    $this->target->getInstance('Lorem ipsum');
  }

  /**
   * @test
   * @expectedException Exception
   */
  public function register_failWithStringConcrete() {
    $this->target->register(DummyInterface::class, 'Lorem ipsum');
  }

  /**
   * @test
   * @expectedException Exception
   */
  public function register_failWithStringAbstract() {
    $this->target->register('Lorem ipsum', DummyBasic::class);
  }

  /**
   * @test
   * @expectedException Exception
   */
  public function register_failWithRandomString() {
    $this->target->register('Lorem ipsum', 'dolor sit amet');
  }

  /**
   * @test
   * @expectedException InvalidArgumentException
   */
  public function getInstance_failSimpleDependencyLoop() {
    $this->target->getInstance(DummyLoopSimple::class);
  }

  /**
   * @test
   * @expectedException InvalidArgumentException
   */
  public function getInstance_failComplexDependencyLoop() {
    $this->target->getInstance(DummyLoopComplex::class);
  }
}