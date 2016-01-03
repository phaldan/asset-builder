<?php

namespace Phaldan\AssetBuilder\Binder;

use DateTime;
use Phaldan\AssetBuilder\Context;
use Phaldan\AssetBuilder\Processor\CacheEntry;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
class CacheBinderEntry extends CacheEntry {

  const DATA_MIME_TYPE = 'mime-type';
  const DATA_CONTEXT = 'context';

  public function __construct($content = null, array $files = null, DateTime $lastModified = null) {
    parent::__construct($content, $files, $lastModified);
    $this->set(self::DATA_MIME_TYPE, null);
    $this->set(self::DATA_CONTEXT, null);
  }

  /**
   * @return string
   */
  public function getMimeType() {
    return $this->get(self::DATA_MIME_TYPE);
  }

  /**
   * @param $type
   */
  public function setMimeType($type) {
    $this->set(self::DATA_MIME_TYPE, $type);
  }

  /**
   * @return Context
   */
  public function getContext() {
    return $this->get(self::DATA_CONTEXT);
  }

  /**
   * @param Context $context
   */
  public function setContext(Context $context) {
    $this->set(self::DATA_CONTEXT, $context);
  }

  /**
   * @inheritdoc
   */
  public function serialize() {
    return is_null($this->getContext()) ? parent::serialize() : $this->serializeContext();
  }

  private function serializeContext() {
    $backup = $this->getContext();
    $this->set(self::DATA_CONTEXT, $backup->serialize());
    $result = parent::serialize();
    $this->set(self::DATA_CONTEXT, $backup);
    return $result;
  }

  /**
   * @inheritdoc
   */
  public function unserialize($serialized) {
    parent::unserialize($serialized);
    $this->transformContext();
    return $this;
  }

  public function transformContext() {
    $context = new Context();
    $string = $this->get(self::DATA_CONTEXT);
    $this->set(self::DATA_CONTEXT, (is_null($string)) ? $context : $context->unserialize($string));
  }
}