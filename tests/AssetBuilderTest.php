<?php

namespace Phaldan\AssetBuilder;

use Phaldan\AssetBuilder\Compiler\CompilerList;
use PHPUnit_Framework_TestCase;
use Phaldan\AssetBuilder\Builder\Builder;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class AssetBuilderTest extends PHPUnit_Framework_TestCase {

  /**
   * @var AssetBuilder
   */
  private $target;

  /**
   * @var ContextMock
   */
  private $context;

  /**
   * @var CompilerList
   */
  private $compiler;

  protected function setUp() {
    $this->target = new AssetBuilder();
    $container = $this->target->getContainer();

    $this->context = new ContextMock();
    $container->register(Context::class, $this->context);

    $this->compiler = new CompilerList();
    $container->register(CompilerList::class, $this->compiler);
  }

  /**
   * @test
   */
  public function createProduction_success() {
    $builder = $this->target->createProduction('/absolute/', 'assets/css');
    $this->assertNotNull($builder);
    $this->assertInstanceOf(Builder::class, $builder);
    $this->assertEquals('/absolute/', $this->context->getRootPath());
    $this->assertTrue($this->context->hasMinifier());
    $this->assertFalse($this->context->hasDebug());
    $this->assertTrue($this->context->hasStopWatch());
  }

  /**
   * @test
   */
  public function createDebug_success() {
    $builder = $this->target->createDebug('/absolute/', 'assets/css');
    $this->assertNotNull($builder);
    $this->assertInstanceOf(Builder::class, $builder);
    $this->assertEquals('/absolute/', $this->context->getRootPath());
    $this->assertFalse($this->context->hasMinifier());
    $this->assertTrue($this->context->hasDebug());
    $this->assertTrue($this->context->hasStopWatch());
  }
}