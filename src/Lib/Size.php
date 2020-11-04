<?php

declare(strict_types=1);

namespace VeronQ\WordpressYAML;

use VeronQ\WordpressYAML\Traits\DataGroupTrait;
use VeronQ\WordpressYAML\Traits\DefaultArgsTrait;

/**
 * Class Size
 * @package VeronQ\WordpressYAML
 */
class Size
{
  use DefaultArgsTrait;
  use DataGroupTrait;

  const FN_BASE_ARGS = [
    'width' => 0,
    'height' => 0,
    'crop' => false,
  ];

  /**
   * Size constructor.
   *
   * @param string|array $filename
   * @param array $defaultArgs
   */
  public function __construct($filename, array $defaultArgs = [])
  {
    $this->getDataYAML($filename);
    $this->setDefaultArgs(self::FN_BASE_ARGS, $defaultArgs);

    add_action('after_setup_theme', [$this, 'registerImageSizes'], Config::$priority);
    add_filter('image_size_names_choose', [$this, 'filterImageNames'], Config::$priority);
  }

  /**
   * Register new image sizes.
   */
  public function registerImageSizes(): void
  {
    foreach ($this->data as $args) {
      [
        'name' => $name,
        'width' => $width,
        'height' => $height,
        'crop' => $crop,
      ] = $this->getArgs($args);
      add_image_size($name, $width, $height, $crop);
    }
  }

  /**
   * Filters the names and labels of the default image sizes.
   *
   * @param array $size_names
   *
   * @return array
   */
  public function filterImageNames(array $size_names): array
  {
    foreach ($this->data as $key => $value) {
      $size_names[$key] = $key;
    }

    return $size_names;
  }
}