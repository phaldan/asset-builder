<?php

namespace Phaldan\AssetBuilder\Group;

use Phaldan\AssetBuilder\FileSystem\FileSystemMock;
use PHPUnit_Framework_TestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 *
 */
class GlobbyFileListTest extends PHPUnit_Framework_TestCase {

  /**
   * @var GlobbyFileList
   */
  private $target;

  /**
   * @var FileSystemMock
   */
  private $fileSystem;

  protected function setUp() {
    $this->fileSystem = new FileSystemMock();
    $this->target = new GlobbyFileList($this->fileSystem);
  }

  private function stubIgnoreGlob() {
    $this->fileSystem->setGlob('file.js', ['file.js']);
    $this->fileSystem->setGlob('*.js', ['file.js', 'example.js']);
  }

  private function assertGetIteratorIgnore($expect) {
    $iterator = $this->target->getIterator();
    $this->assertNotEmpty($iterator);
    $this->assertCount(1, $iterator);
    $this->assertContains($expect, $iterator);
    $this->assertSame($iterator, $this->target->getIterator());
  }

  /**
   * @test
   */
  public function getIterator_successFallback() {
    $this->fileSystem->setGlob('*.js', ['file.js']);
    $this->target->add('*.js');
    $this->assertNotEmpty($this->target->getIterator());
    $this->assertContains('file.js', $this->target->getIterator());
  }

  /**
   * @test
   */
  public function getIterator_successWithIgnore() {
    $this->target->add('!file.js');
    $this->assertEmpty($this->target->getIterator());
  }

  /**
   * @test
   */
  public function getIterator_successWithUpstreamIgnore() {
    $this->stubIgnoreGlob();
    $this->target->add('!file.js');
    $this->target->add('*.js');
    $this->assertGetIteratorIgnore('example.js');
  }

  /**
   * @test
   */
  public function getIterator_successWithDownstreamIgnore() {
    $this->stubIgnoreGlob();
    $this->target->add('*.js');
    $this->target->add('!file.js');
    $this->assertGetIteratorIgnore('example.js');
  }

  /**
   * @test
   */
  public function getIterator_sucessWithGlobStarEmpty() {
    $this->fileSystem->setGlob('modules/*/', []);
    $this->target->add('modules/**/css/*.css');
    $this->assertEmpty($this->target->getIterator());
  }

  /**
   * @test
   */
  public function getIterator_successWithGlobStarFiles() {
    $this->fileSystem->setGlob('modules/*/', ['modules/Accordion/', 'modules/Example/']);
    $this->fileSystem->setGlob('modules/*/*/', ['modules/Accordion/css/', 'modules/Example/css/']);
    $this->fileSystem->setGlob('modules/*/*/*/', []);
    $this->fileSystem->setGlob('modules/*/css/*.css', ['modules/Example/css/example.css']);
    $this->fileSystem->setGlob('modules/*/*/css/*.css', []);
    $this->target->add('modules/**/css/*.css');
    $result = $this->target->getIterator();
    $this->assertNotEmpty($result);
    $this->assertCount(1, $result);
    $this->assertContains('modules/Example/css/example.css', $result);
  }
}
