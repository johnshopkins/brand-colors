<?php

namespace JohnsHopkins\Color;

class Convert
{
  /**
   * Conver RGB to HSL
   * Used this formula: https://en.wikipedia.org/wiki/HSL_and_HSV#Formal_derivation
   * @param array $color Array of RGB values ex: [0, 45, 114]
   * @return array Array of HSL values
   */
  public static function rgb_hsl(array $color): array
  {
    // convert r,g,b [0,255] range to [0,1]
    $r = $color[0] / 255;
    $g = $color[1] / 255;
    $b = $color[2] / 255;

    // get min/max
    $min = min([$r, $g, $b]);
    $max = max([$r, $g, $b]);

    // chroma
    // colorfulness relative to the brightness of a similarly illuminated white
    $chroma = $max - $min;
    $chroma = round($chroma, 5);

    // lightness
    // average of the largest and smallest color components
    $lightness = ($max + $min) / 2;

    if ($min === $max) {
      $saturation = 0;
      $hue = 0;
    } else {

      // saturation
      $saturation = $chroma / (1 - abs(2 * $lightness - 1));

      // hue
      switch($max) {
        case $r:
          $segment = ($g - $b) / $chroma;
          $shift = 0 / 60;
          if ($segment < 0) {
            $shift = 360 / 60;
          }
          break;
        case $g:
          $segment = ($b - $r) / $chroma;
          $shift = 120 / 60;
          break;
        case $b:
          $segment = ($r - $g) / $chroma;
          $shift = 240 / 60;
          break;
      }
      $hue = $segment + $shift;

    }

    return [
      round($hue * 60),
      round($saturation * 100, 1),
      round($lightness * 100, 1),
    ];
  }

  /**
   * Convert HSL to RGB
   * Used this foruma: https://www.baeldung.com/cs/convert-color-hsl-rgb
   * @param array $color Array of HSL values ex: [216, 100, 22]
   * @return array
   */
  public static function hsl_rgb(array $color): array
  {
    $hue = $color[0];
    $saturation = $color[1] / 100;
    $lightness = $color[2] / 100;

    $chroma = (1 - abs(2 * $lightness - 1)) * $saturation;

    $x = $chroma * (1- abs(fmod(($hue / 60), 2) - 1));

    $r = 0;
    $g = 0;
    $b = 0;

    if ($hue < 60) {
      $r = $chroma;
      $g = $x;
    } else if ($hue < 120) {
      $r = $x;
      $g = $chroma;
    } else if ($hue < 180) {
      $g = $chroma;
      $b = $x;
    } else if ($hue < 240) {
      $g = $x;
      $b = $chroma;
    } else if ($hue < 300) {
      $r = $x;
      $b = $chroma;
    } else {
      $r = $chroma;
      $b = $x;
    }

    // lightness
    $m = $lightness - ($chroma / 2);

    return [
      (int) floor(($r + $m) * 255),
      (int) floor(($g + $m) * 255),
      (int) floor(($b + $m) * 255),
    ];
  }
}
