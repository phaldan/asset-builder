<?php

namespace Phaldan\AssetBuilder\Processor;

use DateTime;
use PHPUnit_Framework_TestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class CacheEntryTest extends PHPUnit_Framework_TestCase {

  /**
   * @test
   */
  public function serialize_success() {
    $target = new CacheEntry('example', ['example.file' => new DateTime()], new DateTime());
    $this->assertEquals('example', $target->getContent());
    $this->assertNotEmpty($target->getFiles());
    $this->assertNotEmpty($target->getLastModified());
    $this->assertNotEmpty($target->serialize());
  }

  /**
   * @test
   */
  public function unserialize_success() {
    $input = new CacheEntry('example', ['example.file' => new DateTime()], new DateTime());
    $target = new CacheEntry();
    $target->unserialize($input->serialize());
    $this->assertEquals('example', $target->getContent());
    $this->assertNotEmpty($target->getFiles());
    $this->assertNotEmpty($target->getLastModified());
  }

  /**
   * @test
   */
  public function unserialize_sucessEmpty() {
    $input = new CacheEntry();
    $target = new CacheEntry('example', ['example.file' => new DateTime()], new DateTime());
    $target->unserialize($input->serialize());
    $this->assertNull($target->getContent());
    $this->assertNull($target->getFiles());
    $this->assertNull($target->getLastModified());
  }
}
