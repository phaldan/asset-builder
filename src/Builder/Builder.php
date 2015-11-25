<?php

namespace Phaldan\AssetBuilder\Builder;

use Iterator;
use IteratorAggregate;
use Processor\Processor;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
interface Builder {

  /**
   * Create new instance and set base path for file search
   * @param string $path
   */
  public function __construct($path = '.');

  /**
   * Add single group with a list of files. A IteratorAggregate is needed for the files, because on complex iterators it
   * gives much more flexibility. As example you can think of an Iterator, which doesn't know the list of files, but has
   * a list of pattern and this pattern must be used for a scan of the file-system.
   *
   * @param $name
   * @param IteratorAggregate $files
   * @return Builder
   */
  public function addGroup($name, IteratorAggregate $files);

  /**
   * Iterator returns a list of Group instances
   *
   * @param Iterator $groups
   * @return Builder
   */
  public function addGroups(Iterator $groups);

  /**
   * @param bool|true $boolean
   * @return Builder
   */
  public function enableMinifier($boolean = true);

  /**
   * @param bool|true $boolean
   * @return Builder
   */
  public function enableDebug($boolean = true);

  /**
   * @param bool|true $boolean
   * @return Builder
   */
  public function enableStopWatch($boolean = true);

  /**
   * @param Processor $processor
   * @return Builder
   */
  public function setCssProcessor(Processor $processor);

  /**
   * @param Processor $processor
   * @return Builder
   */
  public function setJsProcessor(Processor $processor);

  /**
   * Set to null to disable caching
   * @param $path
   * @return Builder
   */
  public function setCacheDir($path = null);

  /**
   * Executes compiler, minifier, combines all files and prints the result.
   * @param $group
   */
  public function execute($group);

}