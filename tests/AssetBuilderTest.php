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
   * @var ContextMock
   */
  private $context;

  /**
   * @var CompilerList
   */
  private $compiler;

  protected function setUp() {
    AssetBuilderDouble::clearContainer();
    $container = AssetBuilder::getContainer();

    $this->context = new ContextMock();
    $container->register(Context::class, $this->context);

    $this->compiler = new CompilerList();
    $container->register(CompilerList::class, $this->compiler);
  }

  /**
   * @test
   */
  public function createProduction_success() {
    $target = AssetBuilder::createProduction('/absolute/', 'assets/css');
    $this->assertNotNull($target);
    $this->assertInstanceOf(Builder::class, $target);
    $this->assertEquals('/absolute/', $this->context->getRootPath());
    $this->assertTrue($this->context->hasMinifier());
    $this->assertFalse($this->context->hasDebug());
    $this->assertTrue($this->context->hasStopWatch());
  }

  /**
   * @test
   */
  public function createDebug_success() {
    $target = AssetBuilder::createDebug('/absolute/', 'assets/css');
    $this->assertNotNull($target);
    $this->assertInstanceOf(Builder::class, $target);
    $this->assertEquals('/absolute/', $this->context->getRootPath());
    $this->assertFalse($this->context->hasMinifier());
    $this->assertTrue($this->context->hasDebug());
    $this->assertTrue($this->context->hasStopWatch());
  }
}