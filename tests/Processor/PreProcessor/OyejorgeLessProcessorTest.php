<?php

namespace Phaldan\AssetBuilder\Processor\PreProcessor;

use Phaldan\AssetBuilder\Processor\ProcessorTestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class OyejorgeLessProcessorTest extends ProcessorTestCase {

  /**
   * @var OyejorgeLessProcessor
   */
  protected $target;

  protected function setUp() {
    parent::setUp();
    $this->context->enableMinifier(true);
    $this->target = new OyejorgeLessProcessor($this->fileSystem, $this->context);
  }

  private function stubCompiler() {
    $compiler = new OyejorgeLessParserMock();
    $compiler->setParsedFiles([]);
    $this->target->setCompiler($compiler);
    return $compiler;
  }

  private function stubImportPath(array $current, array $expected) {
    $this->fileSystem->setAbsolutePaths($current, $expected);
    return $this->target->setImportPaths($current);
  }

  private function getContent() {
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
    $this->assertEquals(OyejorgeLessProcessor::EXTENSION, $result);
  }

  /**
   * @test
   */
  public function getOutputMimeType_success() {
    $result = $this->target->getOutputMimeType();
    $this->assertNotEmpty($result);
    $this->assertEquals(OyejorgeLessProcessor::MIME_TYPE, $result);
  }

  /**
   * @test
   */
  public function setCompiler_success() {
    $expected = ['/absolute/asset/css'];
    $this->stubImportPath(['asset/css'], $expected);

    $compiler = $this->stubCompiler();
    $this->assertEquals($expected, $compiler->GetImportDirs());
    $this->assertTrue($compiler->GetOption(OyejorgeLessProcessor::OPTION_MINIFY));
  }

  /**
   * @test
   */
  public function process_success() {
    $expected = "body{padding:0;margin:0}body p{padding:20px 0}";
    $this->assertProcess($expected, $this->getContent());
  }

  /**
   * @test
   */
  public function process_successMultipleTimes() {
    $this->assertProcess("body{padding:0;margin:0}body p{padding:20px 0}", $this->getContent());
    $this->context->enableMinifier(false);
    $expected = "body {\n  padding:0;\n  margin:0;\n}\nbody p {\n  padding:20px 0;\n}\n";
    $this->assertProcess($expected, $this->getContent());
  }

  /**
   * @test
   */
  public function process_false() {
    $this->context->enableMinifier(false);
    $compiler = $this->stubCompiler();
    $compiler->setCss('input', 'output');
    $this->assertProcess('output', 'input');
    $this->assertFalse($compiler->GetOption(OyejorgeLessProcessor::OPTION_MINIFY));
    $this->assertTrue($compiler->hasReset());
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

  /**
   * @test
   */
  public function getFiles_success() {
    $compiler = $this->stubCompiler();
    $compiler->setCss('input', 'output');
    $compiler->setParsedFiles(['import.file']);

    $file = 'example.file';
    $time = new \DateTime();
    $this->fileSystem->setModifiedTime('import.file', $time);
    $this->fileSystem->setModifiedTime($file, $time);

    $this->assertProcess('output', 'input');
    $this->assertArrayHasKey('import.file', $this->target->getFiles($file));
    $this->assertSame($time, $this->target->getFiles($file)['import.file']);
    $this->assertArrayHasKey('example.file', $this->target->getFiles($file));
    $this->assertSame($time, $this->target->getFiles($file)['example.file']);
  }
}
