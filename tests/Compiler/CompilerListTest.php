<?php

namespace Phaldan\AssetBuilder\Compiler;

use PHPUnit_Framework_TestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class CompilerListTest extends PHPUnit_Framework_TestCase {

  /**
   * @var CompilerList
   */
  private $target;

  protected function setUp() {
    $this->target = new CompilerList();
  }

  /**
   * @test
   */
  public function get_fail() {
    $this->assertNull($this->target->get('asset/test.css'));
  }

  /**
   * @test
   */
  public function get_success() {
    $compiler = new CompilerStub();
    $compiler->setSupportedExtension('css');
    $this->target->add($compiler);
    $this->assertSame($compiler, $this->target->get('asset/test.css'));
  }
}
