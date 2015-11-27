<?php

namespace Phaldan\AssetBuilder\Builder;

use ArrayIterator;
use Exception;
use Phaldan\AssetBuilder\Binder\BinderStub;
use Phaldan\AssetBuilder\Group\FileList;
use PHPUnit_Framework_TestCase;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class FluentBuilderTest extends PHPUnit_Framework_TestCase {

  /**
   * @var FluentBuilder
   */
  private $target;

  /**
   * @var BinderStub
   */
  private $binder;

  protected function setUp() {
    $this->binder = new BinderStub();
    $this->target = new FluentBuilder($this->binder);
  }

  /**
   * @test
   * @expectedException Exception
   */
  public function execute_failEmpty() {
    $this->target->execute('test');
  }

  /**
   * @test
   * @expectedException Exception
   */
  public function execute_failNotFound() {
    $this->target->addGroup('group-name', new FileList([]));
    $this->target->execute('test');
  }

  /**
   * @test
   */
  public function addGroups_success() {
    $files = new FileList([]);
    $this->binder->set($files, 'success');

    $iterator = new ArrayIterator();
    $iterator->offsetSet('group-name', $files);
    $this->target->addGroups($iterator);

    $this->assertEquals('success', $this->target->execute('group-name'));
  }

  /**
   * @test
   */
  public function addGroup_success() {
    $files = new FileList([]);
    $this->binder->set($files, 'success');
    $this->target->addGroup('group-name', $files);

    $this->assertEquals('success', $this->target->execute('group-name'));
  }
}