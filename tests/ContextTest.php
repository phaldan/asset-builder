<?php

namespace Phaldan\AssetBuilder;

use PHPUnit_Framework_TestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class ContextTest extends PHPUnit_Framework_TestCase {

  /**
   * @var Context
   */
  private $target;

  protected function setUp() {
    $this->target = new Context();
  }

  /**
   * @test
   */
  public function getRootPath_success() {
    $this->assertNotNull($this->target->getRootPath());
    $this->assertEquals(Context::DEFAULT_ROOT_PATH, $this->target->getRootPath());
  }

  /**
   * @test
   */
  public function setRootPath_success() {
    $this->target->setRootPath('test');
    $this->assertEquals('test', $this->target->getRootPath());
  }

  /**
   * @test
   */
  public function hasMinifier_success() {
    $this->assertNotNull($this->target->hasMinifier());
    $this->assertFalse($this->target->hasMinifier());
  }

  /**
   * @test
   */
  public function enableMinifier_success() {
    $this->target->enableMinifier();
    $this->assertTrue($this->target->hasMinifier());
  }

  /**
   * @test
   */
  public function hasDebug_success() {
    $this->assertNotNull($this->target->hasDebug());
    $this->assertFalse($this->target->hasDebug());
  }

  /**
   * @test
   */
  public function enableDebug_success() {
    $this->target->enableDebug();
    $this->assertTrue($this->target->hasDebug());
  }

  /**
   * @test
   */
  public function hasStopWatch_success() {
    $this->assertNotNull($this->target->hasStopWatch());
    $this->assertFalse($this->target->hasStopWatch());
  }

  /**
   * @test
   */
  public function enableStopWatch_success() {
    $this->target->enableStopWatch();
    $this->assertTrue($this->target->hasStopWatch());
  }

  /**
   * @test
   */
  public function getCachePath_success() {
    $this->assertNull($this->target->getCachePath());
  }

  /**
   * @test
   */
  public function setCachePath_success() {
    $this->target->setCachePath('test');
    $this->assertEquals('test', $this->target->getCachePath());
  }
}