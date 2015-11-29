<?php

namespace Phaldan\AssetBuilder\Builder;

use ArrayAccess;
use IteratorAggregate;
use Phaldan\AssetBuilder\Compiler\Compiler;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
interface Builder {

  /**
   * @param string $path
   */
  public function setRootPath($path = '.');

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
   * @param ArrayAccess $groups
   * @return Builder
   */
  public function addGroups(ArrayAccess $groups);

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
   * @param Compiler $compiler
   * @return Builder
   */
  public function addCompiler(Compiler $compiler);

  /**
   * Set to null to disable caching
   * @param $path
   * @return Builder
   */
  public function setCachePath($path = null);

  /**
   * Executes compiler, minifier, combines all files and prints the result.
   * @param $group
   */
  public function execute($group);

}