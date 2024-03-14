<?php

namespace JohnsHopkins\Color;

class Calculate
{
  /**
   * Convert an 8-bit red, green, or blue value to sRGB.
   * @param int $v 8bit R, G, or B value (0-255)
   * @return float sRGB
   */
  private static function sRGB($v): float
  {
    $v /= 255;

    if ($v <= 0.03928) {
      return $v / 12.92;
    }

    return (($v + 0.055) / 1.055) ** 2.4;
  }

  /**
   * Calculate the relative luminance of an RGB color.
   * @param array $rgb [r, b, g] ex: [0, 45, 114]
   * @return float
   */
  public static function luminance($rgb)
  {
    $sR = self::sRGB($rgb[0]);
    $sG = self::sRGB($rgb[1]);
    $sB = self::sRGB($rgb[2]);

    return (0.2126 * $sR) + (0.7152 * $sG) + (0.0722 * $sB);
  }

  /**
   * Calculate the contrast ratio between two colors
   * Uses the forumla provided by W3C: https://www.w3.org/TR/WCAG20-TECHS/G17.html#G17-procedure
   * @param array $light Array of RGB values for the ligher color ex: [0, 45, 114]
   * @param array $dark  Array of RGB values for the darker color ex: [0, 45, 114]
   * @return float
   */
  public static function contrast(array $light, array $dark): float
  {
    $l1 = self::luminance($light) + 0.05;
    $l2 = self::luminance($dark) + 0.05;

    return round($l1 / $l2, 2);
  }
}
