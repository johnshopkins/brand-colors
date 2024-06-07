<?php

use JohnsHopkins\Color\Colors;
use JohnsHopkins\Color\Convert;
use JohnsHopkins\Color\Grades;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
require_once 'functions.php';

$colors = Colors::get();

$palettes = [
  'grayscale' => [
    'colors' => [
      50 => [120, 116, 112],  // get the colors to be more gray (swatch taken from eric's scale and modified to be within range 50)
      90 => $colors[21]->rgb, // sable
    ],
    'settings' => [
      'grayscale' => true,
    ]
  ],
  'red' => [
    'colors' => [
      30 => $colors[10]->rgb, // 486 (tertiary coral)
      70 => $colors[12]->rgb, // 186 (tertiary red)
      80 => $colors[13]->rgb, // 188 (tertiary maroon)
    ]
  ],
  'orange' => [
    'colors' => [
      30 => $colors[2]->rgb,  // 1375 (secondary-orange)
      40 => $colors[7]->rgb,  // 1505 (tertiary-orange)
      50 => $colors[11]->rgb, // 173 (tertiary dark orange)
    ]
  ],
  'gold' => [
    'colors' => [
      30 => $colors[5]->rgb, // 7406 (secondary yellow)
      60 => $colors[8]->rgb, // 7586 (tertiary brown)
    ]
  ],
  'warm green' => [
    'colors' => [
      40 => $colors[20]->rgb, // 7490 (tertiary green)
      70 => $colors[19]->rgb, // 7734 (tertiary dark green)
    ]
  ],
  'cool green' => [
    'colors' => [
      30 => $colors[18]->rgb, // 564 (tertiary seafoam)
      50 => $colors[3]->rgb,  // 3278 (secondary green)
    ]
  ],
  'blue' => [
    'colors' => [
      30 => $colors[1]->rgb,  // 284 (spirit blue)
      40 => $colors[17]->rgb, // 279 (tertiary blue)
      50 =>  $colors[4]->rgb, // 285 (secondary blue)
      80 => $colors[0]->rgb,  // 288 (heritage blue)
    ]
  ],
  'cool purple' => [
    'colors' => [
      40 => $colors[16]->rgb, // 666 (tertiary lavender)
    ]
  ],
  'warm purple' => [
    'colors' => [
      50 => $colors[15]->rgb, // 7655 (tertiary purple)
      80 => $colors[14]->rgb, // 262 (tertiary dark purple)
    ]
  ],
  'neutral' => [
    'colors' => [
      30 => $colors[6]->rgb, // 7407 (tertiary tan)
      80 => $colors[9]->rgb, // 4625 (tertiary dark brown)
    ]
  ]
];

$palettes = array_map(static function ($knownPalette) {

  $grades = new Grades();

  $compiledPalette = getStarterPalette($knownPalette['colors']);

  // find missing grades
  $gaps = array_keys(array_filter($compiledPalette, 'is_int'));

  // group missing grades together that will use the same bookend colors
  $gapGroups = groupSequential($gaps);

  foreach($gapGroups as $group) {

    $startGrade = $group[0] === 5 ? 0 : $group[0] - 10; // starting bookend
    $endGrade = $group[count($group) - 1] + 10;   // ending bookend

    // convert to HSL
    $startColor = Convert::rgb_hsl($compiledPalette[$startGrade]);
    $endColor = Convert::rgb_hsl($compiledPalette[$endGrade]);

    if (!isset($knownPalette['settings']['grayscale']) || $knownPalette['settings']['grayscale'] === false) {
      // do not factor the in hue and saturation of the starting or ending color (used for non-grayscale palettes)
      if ($startColor == [0, 0, 100] || $startColor == [0, 0, 0]) {
        // white or black - use the hue and saturation of the end color
        $startColor[0] = $endColor[0];
        $startColor[1] = $endColor[1];
      } else if ($endColor == [0, 0, 100] || $endColor == [0, 0, 0]) {
        // white or black - use the hue and saturation of the start color
        $endColor[0] = $startColor[0];
        $endColor[1] = $startColor[1];
      }
    }

    // get hude and saturation intervals for missing grades
    // lightness does not factor in here because we calculate lightness based on required luminance ranges
    $gapsToFill = count($group);
    $hueInterval = (min(abs($startColor[0] - $endColor[0]), 360 - abs($startColor[0] - $endColor[0]))) / ($gapsToFill + 1);
    $saturationInteraval = ($startColor[1] - $endColor[1]) / ($gapsToFill + 1);

    // direction we're moving around the color wheel
    $clockwise = ((($startColor[0] - $endColor[0]) + 360) % 360) < 180;

    foreach ($group as $i => $grade) {

      // adjust hue using the interval
      $hue = $clockwise ? $startColor[0] - $hueInterval : $startColor[0] + $hueInterval;
      if ($hue < 0) {
        // adjust negative angles
        $hue = 360 + $hue;
      }

      // adjust saturation using the interval
      $saturation = $startColor[1] - $saturationInteraval;

      // adjust lightness to fit into this grade
      $lightness = decreaseLightness([$hue, $saturation, $startColor[2]], ...$grades->bounds[$grade]);

      // put it alltogether
      $newColor = [$hue, $saturation, $lightness];

      $compiledPalette[$grade] = Convert::hsl_rgb($newColor);

      $startColor = $newColor;
    }
  }

  // remove black and white
  unset($compiledPalette[0], $compiledPalette[100]);

  return $compiledPalette;

}, $palettes);

// $json = json_encode(array_map(function ($palette) {
//   return array_map(fn ($rgb) => '#' . Convert::rgb_hex($rgb), $palette);
// }, $palettes), JSON_PRETTY_PRINT);
//
// echo $json;

printTable($palettes);

