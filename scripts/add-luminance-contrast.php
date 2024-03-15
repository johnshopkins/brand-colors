<?php

use JohnsHopkins\Color\Calculate;
use JohnsHopkins\Color\Colors;

require_once dirname(__DIR__) . '/vendor/autoload.php';

function contrastWithSable(array $color): float
{
  $sable = [49, 38, 29];

  $sableLum = Calculate::luminance($sable);
  $colorLum = Calculate::luminance($color['rgb']); // do not use rounded version in $color['luminance']

  if ($sableLum > $colorLum) {
    // double black
    return Calculate::contrast($sable, $color['rgb']);
  }

  return Calculate::contrast($color['rgb'], $sable);
}

$json = file_get_contents(dirname(__DIR__) . '/config/colors.json');
$colors = json_decode($json, true);

$colors = array_map(static function ($color) {

  $color['luminance'] = round(Calculate::luminance($color['rgb']) * 100, 2);
  $color['contrast'] = [
    'double-black' => Calculate::contrast($color['rgb'], [0, 0, 0]),
    'sable' => contrastWithSable($color),
    'white' => Calculate::contrast([255, 255, 255], $color['rgb']),
  ];

  return $color;

}, $colors);

$json = json_encode($colors, JSON_PRETTY_PRINT);
file_put_contents(dirname(__DIR__) . '/config/colors.json', $json);
