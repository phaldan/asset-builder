<?php

namespace Phaldan\AssetBuilder;

use PHPUnit_Framework_TestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class OptionMapTest extends PHPUnit_Framework_TestCase {

  /**
   * @var OptionMap
   */
  private $target;

  protected function setUp() {
    $this->target = new OptionMap();
  }

  /**
   * @test
   */
  public function get_fail() {
    $this->assertNull($this->target->offsetGet('test'));
  }

  /**
   * @test
   */
  public function get_success() {
    $this->target->offsetSet('test', 42);
    $this->assertEquals(42, $this->target->offsetGet('test'));
  }

  /**
   * @test
   */
  public function remove_fail() {
    $this->target->offsetSet('test', 42);
    $this->target->offsetUnset('example');
    $this->assertEquals(42, $this->target->offsetGet('test'));
  }

  /**
   * @test
   */
  public function remove_success() {
    $this->target->offsetSet('test', 42);
    $this->target->offsetUnset('test');
    $this->assertNull($this->target->offsetGet('test'));
  }

  /**
   * @test
   */
  public function serialize_success() {
    $this->target->offsetSet('test', 42);
    $this->assertNotEmpty($this->target->serialize());
  }

  /**
   * @test
   */
  public function unserialize_success() {
    $input = new OptionMap();
    $input->offsetSet('example', 1337);
    $this->target->offsetSet('test', 42);
    $this->target->unserialize($input->serialize());

    $this->assertEquals(42, $this->target->offsetGet('test'));
    $this->assertEquals(1337, $this->target->offsetGet('example'));
  }

  /**
   * @test
   * @expectedException \InvalidArgumentException
   */
  public function unserialize_fail() {
    $this->target->unserialize(1337);
  }

  /**
   * @test
   */
  public function jsonSerialize_success() {
    $this->target->offsetSet('test', 1337);
    $result = json_encode($this->target);

    $this->assertNotEmpty($result);
    $this->assertContains('test', $result);
    $this->assertContains('1337', $result);
  }
}
