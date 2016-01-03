<?php

namespace Phaldan\AssetBuilder\Binder;

use Phaldan\AssetBuilder\Context;
use PHPUnit_Framework_TestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class CacheBinderEntryTest extends PHPUnit_Framework_TestCase {

  /**
   * @var CacheBinderEntry
   */
  private $target;

  protected function setUp() {
    $this->target = new CacheBinderEntry();
  }

  /**
   * @test
   */
  public function getMimeType_fail() {
    $this->assertNull($this->target->getMimeType());
  }

  /**
   * @test
   */
  public function getMimeType_success() {
    $this->target->setMimeType('text/css');
    $this->assertEquals('text/css', $this->target->getMimeType());
  }

  /**
   * @test
   */
  public function getContext_fail() {
    $this->assertNull($this->target->getContext());
  }

  /**
   * @test
   */
  public function getContext_success() {
    $context = new Context();
    $this->target->setContext($context);
    $this->assertSame($context, $this->target->getContext());
  }

  /**
   * @test
   */
  public function serialize_success() {
    $context = new Context();
    $context->enableDebug(true);
    $this->target->setContext($context);
    $this->target->setMimeType('text/css');

    $result = $this->target->serialize();
    $this->assertNotEmpty($result);
    $this->assertContains('text\/css', $result);
    $this->assertContains(json_encode($context->serialize()), $result);
    $this->assertSame($context, $this->target->getContext());
  }

  /**
   * @test
   */
  public function unserialize_success() {
    $context = new Context();
    $context->enableDebug(true);
    $entry = new CacheBinderEntry();
    $entry->setContext($context);
    $entry->setMimeType('text/css');

    $result = $this->target->unserialize($entry->serialize());
    $this->assertEquals('text/css', $result->getMimeType());
    $this->assertTrue($result->getContext()->hasDebug());
    $this->assertFalse($result->getContext()->hasMinifier());
  }
}
