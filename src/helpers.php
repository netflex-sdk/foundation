<?php

use Netflex\Foundation\Variable;
use Netflex\Foundation\GlobalContent;

if (!function_exists('variable')) {
  /**
   * Retrieve the value of a variable
   *
   * @param string $key
   * @return mixed
   */
  function variable($key)
  {
    return Variable::get($key);
  }
}

if (!function_exists('global_content')) {
  /**
   * @param string $block
   * @param string $area
   * @param string $field
   * @return mixed|void
   */
  function global_content($block, $area = null, $field = null)
  {
    $static = GlobalContent::retrieve($block);

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
