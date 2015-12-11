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
    $this->target = new OyejorgeLessProcessor($this->fileSystem, $this->cache, $this->context);
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
    $this->assertProcess($expected, $content);
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

    $time = new \DateTime();
    $this->fileSystem->setModifiedTime('import.file', $time);
    $this->fileSystem->setModifiedTime('example.file', $time);

    $this->assertProcess('output', 'input');
    $this->assertArrayHasKey('import.file', $this->target->getFiles());
    $this->assertSame($time, $this->target->getFiles()['import.file']);
    $this->assertArrayHasKey('example.file', $this->target->getFiles());
    $this->assertSame($time, $this->target->getFiles()['example.file']);
  }
}
