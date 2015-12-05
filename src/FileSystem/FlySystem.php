<?php

namespace Phaldan\AssetBuilder\FileSystem;

use League\Flysystem\Adapter\Local;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Filesystem as FlyFileSystem;
use Phaldan\AssetBuilder\Context;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class FlySystem implements FileSystem {

  const ABSOLUTE_PATH_REGEX = '/^([a-zA-Z]:\\\\|\/)/';

  /**
   * @var FlyFileSystem
   */
  private $flySystem;
  private $context;

  /**
   * @param Context $context
   */
  public function __construct(Context $context) {
    $this->context = $context;
  }

  /**
   * @return FlyFileSystem
   */
  public function getFlySystem() {
    if (is_null($this->flySystem)) {
      $adapter = new Local($this->context->getRootPath());
      $this->setAdapter($adapter);
    }
    return $this->flySystem;
  }

  /**
   * @param AdapterInterface $adapter
   */
  public function setAdapter(AdapterInterface $adapter) {
    $this->flySystem = new FlyFileSystem($adapter);
  }

  /**
   * @inheritdoc
   */
  public function getContent($filePath) {
    $relative = $this->getRelativePath($filePath);
    $result = $this->getFlySystem()->read($relative);
    return ($result === false) ? null : $result;
  }

  private function getRelativePath($file) {
    $root = $this->context->getRootPath();
    return (strpos($file, $root) === 0) ? substr($file, strlen($root)) : $file;
  }

  /**
   * @inheritdoc
   */
  public function getAbsolutePaths(array $paths) {
    $array = [];
    foreach ($paths as $path) {
      $array[] = $this->getAbsolutePath($path);
    }
    return $array;
  }

  /**
   * @inheritdoc
   */
  public function getAbsolutePath($path) {
    return $this->isAbsolute($path) ? $path : $this->context->getRootPath() . $path;
  }

  private function isAbsolute($path) {
    return preg_match(self::ABSOLUTE_PATH_REGEX, $path) === 1;
  }

  /**
   * @inheritdoc
   */
  public function resolveGlob($pattern) {
    $absolute = $this->getAbsolutePath($pattern);
    return glob($absolute);
  }
}