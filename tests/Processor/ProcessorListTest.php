<?php

namespace Phaldan\AssetBuilder\Processor;

use PHPUnit_Framework_TestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class ProcessorListTest extends PHPUnit_Framework_TestCase {

  /**
   * @var ProcessorList
   */
  private $target;

  protected function setUp() {
    $this->target = new ProcessorList();
  }

  private function stubCompiler($extension) {
    $compiler = new ProcessorStub();
    $compiler->setFileExtension($extension);
    $this->target->add($compiler);
    return $compiler;
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
    $compiler = $this->stubCompiler('css');
    $this->assertSame($compiler, $this->target->get('asset/test.css'));
  }

  /**
   * @test
   */
  public function process_success() {
    $compiler = $this->stubCompiler('css');
    $compiler->set('some-css-definition', 'content');
    $this->assertEquals('content', $this->target->process('example.css', 'some-css-definition'));
  }
}
