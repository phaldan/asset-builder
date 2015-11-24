<?php

namespace Phaldan\AssetBuilder;

use PHPUnit_Framework_TestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class AssetBuilderTest extends PHPUnit_Framework_TestCase {

  public function test() {
    $target = new AssetBuilder();
    $target->set('test');

    $this->assertNotNull($target);
    $this->assertEquals('test', $target->get());
  }
}