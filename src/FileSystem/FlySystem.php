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
    $result = $this->getFlySystem()->read($filePath);
    return ($result === false) ? null : $result;
  }
}