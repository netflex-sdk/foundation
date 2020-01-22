<?php

use Netflex\Foundation\Setting;
use Netflex\Foundation\StaticContent;

if (!function_exists('get_setting')) {
  /**
   * Retrieve the value of a setting
   *
   * @param string $key
   * @return mixed
   */
  function get_setting($key)
  {
    return Setting::get($key);
  }
}

if (!function_exists('static_content')) {
  /**
   * @param string $block
   * @param string $area
   * @param string $field
   * @return mixed|void
   */
  function static_content($block, $area = null, $field = null)
  {
    $static = StaticContent::retrieve($block);

    if (!$area) {
      return $static;
    }

    $block = $static->globals->first(function ($item) use ($area) {
      return $item->alias === $area;
    });

    if ($block) {
      $field = $field ?? $block->content_type;
      return $block->content->{$field} ?? null;
    }
  }
}
