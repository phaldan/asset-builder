<?php

namespace Phaldan\AssetBuilder;

use PHPUnit_Framework_TestCase;
use Phaldan\AssetBuilder\Builder\Builder;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class AssetBuilderTest extends PHPUnit_Framework_TestCase {

  public function test() {
    $this->markTestIncomplete('Needs Binder');
    $target = AssetBuilder::create();
    $this->assertNotNull($target);
    $this->assertInstanceOf(Builder::class, $target);
  }
}