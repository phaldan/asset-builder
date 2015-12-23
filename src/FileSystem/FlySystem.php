<?php

namespace Phaldan\AssetBuilder\FileSystem;

use DateTime;
use League\Flysystem\Adapter\Local;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Cached\CachedAdapter;
use League\Flysystem\Cached\Storage\Memory;
use League\Flysystem\Filesystem as FlyFileSystem;
use Phaldan\AssetBuilder\Context;
use Phaldan\AssetBuilder\Exception;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class FlySystem implements FileSystem {

  const ABSOLUTE_PATH_REGEX = '/^([a-zA-Z]:\\\\|\/)/';

  /**
   * @var FlyFileSystem
   */
  private $flySystem = [];
  private $cache;
  private $context;

  /**
   * @param Context $context
   */
  public function __construct(Context $context) {
    $this->context = $context;
    $this->cache = new Memory();
  }

  /**
   * @param $filePath
   * @return FlyFileSystem
   */
  public function getFlySystem($filePath) {
    return $this->isAlternative($filePath) ? $this->getAlternativeSystem($filePath) : $this->getRootSystem();
  }

  private function isAlternative($filePath) {
    return $this->isAbsolute($filePath) && strpos($filePath, $this->context->getRootPath()) !== 0;
  }

  private function getAlternativeSystem($filePath) {
    $dir = dirname($filePath) . DIRECTORY_SEPARATOR;
    $this->setFileSystem($dir);
    return $this->flySystem[$dir];
  }

  private function getRootSystem() {
    $path = $this->context->getRootPath();
    $this->setFileSystem($path);
    return $this->flySystem[$path];
  }

  private function setFileSystem($path) {
    if (!isset($this->flySystem[$path])) {
      $adapter = new Local($path);
      $this->setAdapter($adapter, $path);
    }
  }

  /**
   * @param AdapterInterface $adapter
   * @param $path
   */
  public function setAdapter(AdapterInterface $adapter, $path) {
    $cached = new CachedAdapter($adapter, $this->cache);
    $this->flySystem[$path] = new FlyFileSystem($cached);
  }

  /**
   * @inheritdoc
   */
  public function getContent($filePath) {
    $relative = $this->getRelativePath($filePath);
    $result = $this->getFlySystem($filePath)->read($relative);
    return ($result === false) ? null : $result;
  }

  private function getRelativePath($file) {
    $root = $this->context->getRootPath();
    return (strpos($file, $root) === 0) ? substr($file, strlen($root)) : $this->getAlternativeRelativePath($file);
  }

  private function getAlternativeRelativePath($filePath) {
    return $this->isAbsolute($filePath) ? basename($filePath) : $filePath;
  }

  /**
   * @inheritdoc
   */
  public function setContent($filePath, $content) {
    $relative = $this->getRelativePath($filePath);
    $this->getFlySystem($filePath)->put($relative, $content);
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
    return glob($absolute, GLOB_BRACE);
  }

  /**
   * @inheritdoc
   */
  public function exists($filePath) {
    $relative = $this->getRelativePath($filePath);
    return $this->getFlySystem($filePath)->has($relative);
  }

  /**
   * @inheritdoc
   */
  public function getModifiedTime($filePath) {
    $path = $this->getAbsolutePath($filePath);
    if (!file_exists($path)) {
      throw Exception::fileNotFound($path);
    }
    $time = new DateTime();
    return $time->setTimestamp(filemtime($path));
  }
}