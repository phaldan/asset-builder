<?php

namespace Phaldan\AssetBuilder\Compiler;

use Phaldan\AssetBuilder\ContextMock;
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

  /**
   * @var ContextMock
   */
  private $context;

  protected function setUp() {
    $this->fileSystem = new FileSystemMock();
    $this->context = new ContextMock();
    $this->context->enableMinifier(true);
    $this->target = new OyejorgeLessCompiler($this->fileSystem, $this->context);
  }

  private function stubCompiler() {
    $compiler = new OyejorgeLessParserMock();
    $this->target->setCompiler($compiler);
    return $compiler;
  }

  private function stubImportPath(array $current, array $expected) {
    $this->fileSystem->setAbsolutePaths($current, $expected);
    return $this->target->setImportPaths($current);
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
  public function setCompiler_success() {
    $expected = ['/absolute/asset/css'];
    $this->stubImportPath(['asset/css'], $expected);

    $compiler = $this->stubCompiler();
    $this->assertEquals($expected, $compiler->GetImportDirs());
    $this->assertTrue($compiler->GetOption(OyejorgeLessCompiler::OPTION_MINIFY));
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
    $expected = "body{padding: 0;margin: 0}body p{padding: 20px 0}";
    $this->assertEquals($expected, $this->target->process($content));
  }

  /**
   * @test
   */
  public function process_false() {
    $this->context->enableMinifier(false);
    $compiler = $this->stubCompiler();
    $compiler->setCss('input', 'output');
    $this->assertEquals('output', $this->target->process('input'));
    $this->assertFalse($compiler->GetOption(OyejorgeLessCompiler::OPTION_MINIFY));
  }

  /**
   * @test
   */
  public function setImportPaths_success() {
    $compiler = $this->stubCompiler();
    $expected = ['/absolute/asset/css'];

    $this->assertSame($this->target, $this->stubImportPath(['asset/css'], $expected));
    $this->assertEquals($expected, $compiler->GetImportDirs());
  }
}
