<?php

namespace Phaldan\AssetBuilder\Processor;

use DateTime;
use Serializable;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class CacheEntry implements Serializable {

  const DATA_CONTENT = 'content';
  const DATA_FILES = 'files';
  const DATA_LAST_MODIFIED = 'time';

  private $data = [];

  /**
   * @param null $content
   * @param array|null $files
   * @param DateTime|null $lastModified
   */
  public function __construct($content = null, array $files = null, DateTime $lastModified = null) {
    $this->set(self::DATA_CONTENT, $content);
    $this->set(self::DATA_FILES, $files);
    $this->set(self::DATA_LAST_MODIFIED, $lastModified);
  }

  private function set($key, $value) {
    $this->data[$key] = $value;
  }

  private function get($key) {
    return $this->data[$key];
  }

  /**
   * @return string
   */
  public function getContent() {
    return $this->get(self::DATA_CONTENT);
  }

  /**
   * @return array
   */
  public function getFiles() {
    return $this->get(self::DATA_FILES);
  }

  /**
   * @return DateTime
   */
  public function getLastModified() {
    return $this->get(self::DATA_LAST_MODIFIED);
  }

  /**
   * @inheritdoc
   */
  public function serialize() {
    return serialize($this->data);
  }

  /**
   * @inheritdoc
   */
  public function unserialize($serialized) {
    $this->data = unserialize($serialized);
    return $this;
  }
}