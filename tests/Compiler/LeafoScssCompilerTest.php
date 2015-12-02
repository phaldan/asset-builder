<?php

namespace Phaldan\AssetBuilder\Compiler;

use Leafo\ScssPhp\Compiler as LeafoCompiler;
use Leafo\ScssPhp\Formatter\Crunched;
use Leafo\ScssPhp\Formatter\Expanded;
use Phaldan\AssetBuilder\ContextMock;
use Phaldan\AssetBuilder\FileSystem\FileSystemMock;
use PHPUnit_Framework_TestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class LeafoScssCompilerTest extends PHPUnit_Framework_TestCase {

  /**
   * @var LeafoScssCompiler
   */
  private $target;

  /**
   * @var FileSystemMock
   */
  private $fileSystem;

  /**
   * @var ContextMock
   */
  private $context;

  protected function setUp() {
    $this->fileSystem = new FileSystemMock();
    $this->context = new ContextMock();
    $this->target = new LeafoScssCompiler($this->fileSystem, $this->context);
  }

  private function stubCompiler() {
    $compiler = new LeafoCompilerMock();
    $this->target->setCompiler($compiler);
    return $compiler;
  }

  /**
   * @test
   */
  public function getSupportedExtension_success() {
    $this->assertEquals(LeafoScssCompiler::EXTENSION, $this->target->getSupportedExtension());
  }

  /**
   * @test
   */
  public function getCompiler_success() {
    $return = $this->target->getCompiler();
    $this->assertNotNull($return);
    $this->assertInstanceOf(LeafoCompiler::class, $return);
  }

  /**
   * @test
   */
  public function setCompiler_success() {
    $paths = ['import/css'];
    $expected = ['/absolute/import/css'];
    $this->fileSystem->setAbsolutePaths($paths, $expected);
    $this->target->setImportDirs($paths);
    $compiler = $this->stubCompiler();

    $this->assertSame($compiler, $this->target->getCompiler());
    $this->assertEquals($expected, $compiler->getImportPaths());
    $this->assertEquals(Expanded::class, $compiler->getFormatter());
  }

  /**
   * @test
   */
  public function setImportDirs_success() {
    $paths = ['import/css'];
    $expected = ['/absolute/import/css'];
    $this->fileSystem->setAbsolutePaths($paths, $expected);

    $compiler = $this->stubCompiler();
    $this->assertSame($this->target, $this->target->setImportDirs($paths));
    $this->assertEquals($expected, $compiler->getImportPaths());
  }

  /**
   * @test
   */
  public function compile_success() {
    $compiler = $this->stubCompiler();
    $compiler->setCompileReturn('input', 'output');
    $this->assertEquals('output', $this->target->process('input'));
    $this->assertNotNull($compiler->getImportPaths());
    $this->assertEmpty($compiler->getImportPaths());
  }

  /**
   * @test
   */
  public function setCompiler_successMinify() {
    $this->context->enableMinifier(true);
    $this->assertEquals(Crunched::class, $this->stubCompiler()->getFormatter());
  }
}
