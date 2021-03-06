<?php

namespace Netflex\Foundation;

use Netflex\API\Facades\API;

use Netflex\Support\Retrievable;
use Netflex\Support\ReactiveObject;

use Illuminate\Support\Facades\Cache;

class Variable extends ReactiveObject
{
  use Retrievable;

  protected static $base_path = 'foundation/variables';

  /** @var array */
  protected $timestamps = [];

  /**
   * Retrieve the value of a Setting
   *
   * @param string $key
   * @return mixed
   */
  public static function get($key)
  {
    $setting = static::retrieve($key);
    return $setting ? $setting->value : null;
  }

  /**
   * @param mixed $value
   * @return mixed
   */
  public function getValueAttribute($value)
  {
    switch ($this->format) {
      case 'boolean':
        return (bool) (int) $value;
      case 'json':
        if (is_string($value)) {
          return json_decode($value);
        }

        return $value;
      default:
        return $value;
    }
  }

  /**
   * @return static[]
   */
  public static function all()
  {
    $templates = Cache::rememberForever('variables', function () {
      return API::get('foundation/variables');
    });

    return collect($templates)->map(function ($content) {
      return new static($content);
    });
  }

  /**
   * @param string $alias
   * @return static|void
   */
  public static function retrieve($alias)
  {
    return static::all()->first(function ($content) use ($alias) {
      return $content->alias === $alias;
    });
  }
}
