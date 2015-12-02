<?php

namespace Phaldan\AssetBuilder\Compiler;

use Less_Parser;
use Phaldan\AssetBuilder\FileSystem\FileSystemMock;
use PHPUnit_Framework_TestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class OyejorgeLessCompilerTest extends PHPUnit_Framework_TestCase {

  /**
   * @var OyejorgeLessCompiler
   */
  private $target;

  /**
   * @var FileSystemMock
   */
  private $fileSystem;

  protected function setUp() {
    $this->fileSystem = new FileSystemMock();
    $this->target = new OyejorgeLessCompiler($this->fileSystem);
  }

  private function stubCompiler() {
    $compiler = new OyejorgeLessParserMock();
    $this->target->setCompiler($compiler);
    return $compiler;
  }

  /**
   * @test
   */
  public function getSupportedExtension_success() {
    $this->assertEquals(OyejorgeLessCompiler::EXTENSION, $this->target->getSupportedExtension());
  }

  /**
   * @test
   */
  public function getCompiler_success() {
    $return = $this->target->getCompiler();
    $this->assertNotNull($return);
    $this->assertInstanceOf(Less_Parser::class, $return);
  }

  /**
   * @test
   */
  public function setCompiler_success() {
    $dirs = ['asset/css'];
    $expected = ['/absolute/asset/css'];
    $this->fileSystem->setAbsolutePaths($dirs, $expected);
    $this->target->setImportPaths($dirs);

    $compiler = $this->stubCompiler();
    $this->assertSame($compiler, $this->target->getCompiler());
    $this->assertEquals($expected, $compiler->GetImportDirs());
  }

  /**
   * @test
   */
  public function process_success() {
    $compiler = $this->stubCompiler();
    $compiler->setCss('input', 'output');
    $this->assertEquals('output', $this->target->process('input'));
  }

  /**
   * @test
   */
  public function setImportPaths_success() {
    $dirs = ['asset/css'];
    $expected = ['/absolute/asset/css'];
    $this->fileSystem->setAbsolutePaths($dirs, $expected);
    $compiler = $this->stubCompiler();

    $this->assertSame($this->target, $this->target->setImportPaths($dirs));
    $this->assertEquals($expected, $compiler->GetImportDirs());
  }
}
