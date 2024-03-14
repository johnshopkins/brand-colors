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
    $json = file_get_contents(dirname(__DIR__) . '/config/colors.json');
    return json_decode($json, true);
  }

  public static function getById(int $id): array
  {
    $colors = self::get();
    return $colors[$id - 1];
  }
}
