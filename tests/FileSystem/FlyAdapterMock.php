<?php

namespace Phaldan\AssetBuilder\FileSystem;

use League\Flysystem\AdapterInterface;
use League\Flysystem\Config;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class FlyAdapterMock implements AdapterInterface {

  const ATTR_CONTENTS = 'contents';

  private $has = [];
  private $read = [];

  /**
   * @inheritdoc
   */
  public function write($path, $contents, Config $config) {
  }

  /**
   * @inheritdoc
   */
  public function writeStream($path, $resource, Config $config) {
  }

  /**
   * @inheritdoc
   */
  public function update($path, $contents, Config $config) {
  }

  /**
   * @inheritdoc
   */
  public function updateStream($path, $resource, Config $config) {
  }

  /**
   * @inheritdoc
   */
  public function rename($path, $newpath) {
  }

  /**
   * @inheritdoc
   */
  public function copy($path, $newpath) {
  }

  /**
   * @inheritdoc
   */
  public function delete($path) {
  }

  /**
   * @inheritdoc
   */
  public function deleteDir($dirname) {
  }

  /**
   * @inheritdoc
   */
  public function createDir($dirname, Config $config) {
  }

  /**
   * @inheritdoc
   */
  public function setVisibility($path, $visibility) {
  }

  /**
   * @inheritdoc
   */
  public function has($path) {
    return isset($this->has[$path]);
  }

  public function setHas($path) {
    $this->has[$path] = true;
  }

  /**
   * @inheritdoc
   */
  public function read($path) {
    return isset($this->read[$path]) ? $this->read[$path] : false;
  }

  public function setRead($path, $content) {
    $this->read[$path] = [self::ATTR_CONTENTS => $content];
  }

  /**
   * @inheritdoc
   */
  public function readStream($path) {
  }

  /**
   * @inheritdoc
   */
  public function listContents($directory = '', $recursive = false) {
  }

  /**
   * @inheritdoc
   */
  public function getMetadata($path) {
  }

  /**
   * @inheritdoc
   */
  public function getSize($path) {
  }

  /**
   * @inheritdoc
   */
  public function getMimetype($path) {
  }

  /**
   * @inheritdoc
   */
  public function getTimestamp($path) {
  }

  /**
   * @inheritdoc
   */
  public function getVisibility($path) {
  }
}