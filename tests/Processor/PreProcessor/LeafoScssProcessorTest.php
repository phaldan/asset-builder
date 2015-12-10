<?php

namespace Phaldan\AssetBuilder\Processor\PreProcessor;

use Leafo\ScssPhp\Compiler as LeafoCompiler;
use Leafo\ScssPhp\Formatter\Crunched;
use Leafo\ScssPhp\Formatter\Expanded;
use Phaldan\AssetBuilder\Processor\ProcessorTestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class LeafoScssProcessorTest extends ProcessorTestCase {

  /**
   * @var LeafoScssProcessor
   */
  protected $target;

  protected function setUp() {
    parent::setUp();
    $this->context->enableMinifier(true);
    $this->target = new LeafoScssProcessor($this->fileSystem, $this->cache, $this->context);
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
    $result = $this->target->getSupportedExtension();
    $this->assertNotEmpty($result);
    $this->assertEquals(LeafoScssProcessor::EXTENSION, $result);
  }

  /**
   * @test
   */
  public function getOutputMimeType_success() {
    $result = $this->target->getOutputMimeType();
    $this->assertNotEmpty($result);
    $this->assertEquals(LeafoScssProcessor::MIME_TYPE, $result);
  }

  /**
   * @test
   */
  public function setImportPaths_success() {
    $paths = ['import/css'];
    $expected = ['/absolute/import/css'];
    $this->fileSystem->setAbsolutePaths($paths, $expected);

    $compiler = $this->stubCompiler();
    $this->assertSame($this->target, $this->target->setImportPaths($paths));
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
    $this->assertProcess($expected, $content);
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
    $this->target->setImportPaths($paths);

    $this->assertEquals(Expanded::class, $compiler->getFormatter());
    $this->assertEquals($expected, $compiler->getImportPaths());
    $this->assertProcess('output', 'input');
  }

  /**
   * @test
   */
  public function setCompiler_success() {
    $compiler = new LeafoCompilerMock();
    $compiler->setCompileReturn('input', 'output');

    $this->assertSame($this->target, $this->target->setCompiler($compiler));
    $this->assertEquals(Crunched::class, $compiler->getFormatter());
    $this->assertNull($compiler->getLineNumberStyle());
    $this->assertProcess('output', 'input');
  }


  /**
   * @test
   */
  public function setCompiler_successWithDebug() {
    $this->context->enableDebug(true);
    $compiler = new LeafoCompilerMock();
    $compiler->setCompileReturn('input', 'output');

    $this->target->setCompiler($compiler);
    $this->assertEquals(LeafoCompiler::LINE_COMMENTS, $compiler->getLineNumberStyle());
    $this->assertProcess('output', 'input');
  }
}
