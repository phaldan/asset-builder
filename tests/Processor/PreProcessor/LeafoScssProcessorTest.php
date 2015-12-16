<?php

namespace Phaldan\AssetBuilder\Processor\PreProcessor;

use DateTime;
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
    $this->target = new LeafoScssProcessor($this->fileSystem, $this->context);
  }

  private function stubCompiler() {
    $compiler = new LeafoCompilerMock();
    $this->target->setCompiler($compiler);
    return $compiler;
  }

  private function stubCompilerReturn($input, $output) {
    $compiler = $this->stubCompiler();
    $compiler->setCompileReturn($input, $output);
    return $compiler;
  }

  private function getExampleScss() {
    return "
      body {
        padding: 0;
        margin: 0;

        p {
          padding: 20px 0;
        }
      }
    ";
  }

  /**
   * @test
   */
  public function getSupportedExtension_success() {
    $result = $this->target->getFileExtension();
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
    $expected = "body{padding:0;margin:0}body p{padding:20px 0}";
    $this->assertProcess($expected, $this->getExampleScss());
  }

  /**
   * @test
   */
  public function process_successMultipleTimes() {
    $this->assertProcess("body{padding:0;margin:0}body p{padding:20px 0}", $this->getExampleScss());
    $this->context->enableMinifier(false);
    $expected = "body {\n  padding: 0;\n  margin: 0;\n}\nbody p {\n  padding: 20px 0;\n}\n";
    $this->assertProcess($expected, $this->getExampleScss());
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

  /**
   * @test
   */
  public function getFiles_success() {
    $file = 'example.file';
    $time = new DateTime();
    $this->fileSystem->setModifiedTime($file, $time);

    $compiler = $this->stubCompilerReturn('input', 'output');
    $compiler->setParsedFiles(['imported.file' => 1337]);

    $this->assertProcess('output', 'input');
    $this->assertArrayHasKey('imported.file', $this->target->getFiles($file));
    $this->assertEquals(1337, $this->target->getFiles($file)['imported.file']->getTimestamp());
    $this->assertArrayHasKey($file, $this->target->getFiles($file));
    $this->assertSame($time, $this->target->getFiles($file)['example.file']);
  }

  /**
   * @test
   */
  public function getLastModified_successWithoutProcessing() {
    $file = 'example.file';
    $dateTime = new DateTime();
    $this->fileSystem->setModifiedTime($file, $dateTime);
    $this->assertSame($dateTime, $this->target->getLastModified($file));
  }

  /**
   * @test
   */
  public function getLastModified_success() {
    $file = 'example.file';
    $dateTime = new DateTime();
    $this->fileSystem->setModifiedTime($file, $dateTime);
    $this->stubCompilerReturn('input', 'output');

    $this->assertProcess('output', 'input', $file);
    $this->assertSame($dateTime, $this->target->getLastModified($file));
  }

  /**
   * @test
   */
  public function getLastModified_successMultiple() {
    $file = 'example.file';
    $dateTime = new DateTime();
    $this->fileSystem->setModifiedTime($file, $dateTime);

    $time = $dateTime->getTimestamp() + 42;
    $compiler = $this->stubCompilerReturn('input', 'output');
    $compiler->setParsedFiles(['import.file' => $time]);

    $this->assertProcess('output', 'input', $file);
    $this->assertEquals($time, $this->target->getLastModified($file)->getTimestamp());
  }
}
