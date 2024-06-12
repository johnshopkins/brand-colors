<?php

namespace JohnsHopkins\Color;

class Colors
{
  /**
   * Get all brand colors
   * @return array
   */
  public static function get(): array
  {
    $json = file_get_contents(dirname(__DIR__) . '/config/web-colors.json');
    $colors = json_decode($json, true);

    return array_map(fn ($data) => new Color($data), $colors);
  }

  /**
   * Get a brand colors by ID
   * @param int $id Color ID
   * @return array|null
   */
  public static function getByID(int $id): Color|null
  {
    $colors = self::get();
    return $colors[$id - 1] ?? null;
  }
}
