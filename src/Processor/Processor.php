<?php

namespace Phaldan\AssetBuilder\Processor;

use DateTime;

/**
 * @author Philipp Daniels <philipp.daniels@gmail.com>
 */
interface Processor {

  /**
   * Returns file extension of supported files
   * @return string
   */
  public function getFileExtension();

  /**
   * @return string
   */
  public function getOutputMimeType();


  /**
   * Transform to native language like CSS or JavaScript, and compress
   * @param $filePath
   * @return string
   */
  public function process($filePath);

  /**
   * Return all related files of processed file, like all imported files from processed less or sass.
   * @param $filePath
   * @return array
   */
  public function getFiles($filePath);


  /**
   * @param $filePath
   * @return DateTime
   */
  public function getLastModified($filePath);
}