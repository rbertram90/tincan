<?php

namespace TinCan;

/**
 * Represents an image file.
 *
 * @since 0.05
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
class TCImage
{
  public const ERR_FILE_TYPE = 'file-type';
  public const ERR_FILE_SIZE = 'file-size';
  public const ERR_FILE_GENERAL = 'file-gen';

  // Maximum file size is 1MB. Value below is bytes.
  public const MAX_FILE_SIZE = 1000000;

  /**
   * @since 0.05
   */
  protected $file_name;

  /**
   * @since 0.05
   */
  protected $file_type;

  /**
   * @since 0.05
   */
  protected $mime_type;

  /**
   * @since 0.05
   */
  protected $file_size;

  /**
   * @since 0.05
   */
  protected $width;

  /**
   * @since 0.05
   */
  protected $height;

  /**
   * TODO.
   *
   * @since 0.05
   */
  public function scale_to_square($source_image, $size)
  {
    if (empty($size)) {
      return null;
    }

    $image = null;

    if (IMAGETYPE_JPEG == $this->file_type) {
      $image = imagecreatefromjpeg($source_image);
    } elseif (IMAGETYPE_PNG == $this->file_type) {
      $image = imagecreatefrompng($source_image);
    }

    // Image needs to be a square, so crop to the size of the shortest side.
    $crop_size = ($this->width > $this->height) ? $this->height : $this->width;

    $center_x = (($this->width / 2) - ($crop_size / 2));
    $center_y = (($this->height / 2) - ($crop_size / 2));

    if ($center_x < 0) {
      $center_x = 0;
    }

    if ($center_y < 0) {
      $center_y = 0;
    }

    $crop_options = [
      'x' => $center_x,
      'y' => $center_y,
     'width' => $crop_size,
     'height' => $crop_size,
    ];

    $cropped_image = imagecrop($image, $crop_options);

    $scaled_image = imagescale($cropped_image, $size, $size, IMG_BICUBIC);

    return $scaled_image;
  }

  /**
   * Determines if this image is a valid format.
   *
   * @since 0.05
   *
   * @return bool true if image format is valid
   */
  public function is_valid_type()
  {
    if ((IMAGETYPE_JPEG != $this->file_type) && (IMAGETYPE_PNG != $this->file_type)) {
      return false;
    }

    if (('image/jpeg' != $this->mime_type) && ('image/png' != $this->mime_type)) {
      return false;
    }

    return true;
  }

  /**
   * Determines if this image is below the maximum file size.
   *
   * @since 0.05
   *
   * @return bool true if image size is valid
   */
  public function is_valid_size()
  {
    return $this->file_size <= self::MAX_FILE_SIZE;
  }

  /**
   * @since 0.05
   */
  public function __get($name)
  {
    return $this->$name;
  }

  /**
   * @since 0.05
   */
  public function __set($name, $value)
  {
    $this->$name = $value;
  }
}