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
    $this->target->setRootPath('.');
    $this->assertEquals(dirname(__DIR__) . DIRECTORY_SEPARATOR, $this->target->getRootPath());
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
    $this->target->setCachePath(sys_get_temp_dir());
    $this->assertEquals(sys_get_temp_dir() . DIRECTORY_SEPARATOR, $this->target->getCachePath());
    $this->assertTrue($this->target->hasCache());
  }

  /**
   * @test
   * @expectedException \InvalidArgumentException
   */
  public function setCachePath_fail() {
    $this->target->setCachePath('Lorem Ipsum');
  }

  /**
   * @test
   */
  public function hasCache_fail() {
    $this->assertFalse($this->target->hasCache());
  }
}