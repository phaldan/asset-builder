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
    $this->context->enableMinifier(true);
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
  public function process_success() {
    $content = "
      body {
        padding: 0;
        margin: 0;

        p {
          padding: 20px 0;
        }
      }
    ";
    $expected = "body{padding:0;margin:0}body p{padding:20px 0}";
    $this->assertEquals($expected, $this->target->process($content));
  }

  /**
   * @test
   */
  public function process_false() {
    $this->context->enableMinifier(false);
    $compiler = $this->stubCompiler();
    $compiler->setCompileReturn('input', 'output');

    $paths = ['import/css'];
    $expected = ['/absolute/import/css'];
    $this->fileSystem->setAbsolutePaths($paths, $expected);
    $this->target->setImportDirs($paths);

    $this->assertEquals(Expanded::class, $compiler->getFormatter());
    $this->assertEquals($expected, $compiler->getImportPaths());
    $this->assertEquals('output', $this->target->process('input'));
  }

  /**
   * @test
   */
  public function setCompiler_success() {
    $compiler = new LeafoCompilerMock();
    $compiler->setCompileReturn('input', 'output');

    $this->assertSame($this->target, $this->target->setCompiler($compiler));
    $this->assertEquals(Crunched::class, $compiler->getFormatter());
    $this->assertEquals('output', $this->target->process('input'));
  }
}