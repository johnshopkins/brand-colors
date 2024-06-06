<?php

/**
 * Creates a palette with 10 grades for each brand color
 * Eric's palette: https://leonardocolor.io/theme.html?name=Untitled&config=%7B%22baseScale%22%3A%22Identity+Blues%22%2C%22colorScales%22%3A%5B%7B%22name%22%3A%22Grayscale%22%2C%22colorKeys%22%3A%5B%22%2331261d%22%5D%2C%22colorspace%22%3A%22RGB%22%2C%22ratios%22%3A%5B%221%22%2C%221.15%22%2C%221.6%22%2C%222.25%22%2C%223.5%22%2C%224.48%22%2C%226.48%22%2C%229.29%22%2C%2213.2%22%2C%2217.22%22%2C%2221%22%5D%2C%22smooth%22%3Afalse%7D%2C%7B%22name%22%3A%22Red%22%2C%22colorKeys%22%3A%5B%22%23a6192e%22%2C%22%2376232f%22%2C%22%23e8927c%22%5D%2C%22colorspace%22%3A%22RGB%22%2C%22ratios%22%3A%5B%221%22%2C%221.15%22%2C%221.6%22%2C%222.25%22%2C%223.5%22%2C%224.48%22%2C%226.48%22%2C%229.29%22%2C%2213.2%22%2C%2217.22%22%2C%2221%22%5D%2C%22smooth%22%3Afalse%7D%2C%7B%22name%22%3A%22Orange%22%2C%22colorKeys%22%3A%5B%22%23ff9e1b%22%2C%22%23ff6900%22%2C%22%23cf4520%22%5D%2C%22colorspace%22%3A%22RGB%22%2C%22ratios%22%3A%5B%221%22%2C%221.15%22%2C%221.6%22%2C%222.25%22%2C%223.5%22%2C%224.48%22%2C%226.48%22%2C%229.29%22%2C%2213.2%22%2C%2217.22%22%2C%2221%22%5D%2C%22smooth%22%3Afalse%7D%2C%7B%22name%22%3A%22Gold%22%2C%22colorKeys%22%3A%5B%22%23f1c400%22%2C%22%239e5330%22%5D%2C%22colorspace%22%3A%22RGB%22%2C%22ratios%22%3A%5B%221%22%2C%221.15%22%2C%221.6%22%2C%222.25%22%2C%223.5%22%2C%224.48%22%2C%226.48%22%2C%229.29%22%2C%2213.2%22%2C%2217.22%22%2C%2221%22%5D%2C%22smooth%22%3Afalse%7D%2C%7B%22name%22%3A%22Green%22%2C%22colorKeys%22%3A%5B%22%23009b77%22%2C%22%2386c8bc%22%2C%22%23286140%22%5D%2C%22colorspace%22%3A%22RGB%22%2C%22ratios%22%3A%5B%221%22%2C%221.15%22%2C%221.6%22%2C%222.25%22%2C%223.5%22%2C%224.48%22%2C%226.48%22%2C%229.29%22%2C%2213.2%22%2C%2217.22%22%2C%2221%22%5D%2C%22smooth%22%3Afalse%7D%2C%7B%22name%22%3A%22Identity+Blues%22%2C%22colorKeys%22%3A%5B%22%23002d72%22%2C%22%230072ce%22%2C%22%2368ace5%22%5D%2C%22colorspace%22%3A%22RGB%22%2C%22ratios%22%3A%5B%221%22%2C%221.15%22%2C%221.6%22%2C%222.25%22%2C%223.5%22%2C%224.48%22%2C%226.48%22%2C%229.29%22%2C%2213.2%22%2C%2217.22%22%2C%2221%22%5D%2C%22smooth%22%3Afalse%7D%2C%7B%22name%22%3A%22Purple%22%2C%22colorKeys%22%3A%5B%22%2351284f%22%2C%22%23a15a95%22%2C%22%23a192b2%22%5D%2C%22colorspace%22%3A%22RGB%22%2C%22ratios%22%3A%5B%221%22%2C%221.15%22%2C%221.6%22%2C%222.25%22%2C%223.5%22%2C%224.48%22%2C%226.48%22%2C%229.29%22%2C%2213.2%22%2C%2217.22%22%2C%2221%22%5D%2C%22smooth%22%3Afalse%7D%2C%7B%22name%22%3A%22Neutral%22%2C%22colorKeys%22%3A%5B%22%234f2c1d%22%2C%22%23cca356%22%5D%2C%22colorspace%22%3A%22RGB%22%2C%22ratios%22%3A%5B%221%22%2C%221.15%22%2C%221.6%22%2C%222.25%22%2C%223.5%22%2C%224.48%22%2C%226.48%22%2C%229.29%22%2C%2213.2%22%2C%2217.22%22%2C%2221%22%5D%2C%22smooth%22%3Afalse%7D%5D%2C%22lightness%22%3A100%2C%22contrast%22%3A1%2C%22saturation%22%3A100%2C%22formula%22%3A%22wcag2%22%7D
 */


/**
 * @todo:
 * - add min and max colors so we can better evaluate
 * - in grayscale palette, use white and black as boundaries so that
 *   other values in HSL are evalauated other than L
 * - figure out a way to curve saturation
 */

use JohnsHopkins\Color\Convert;
use JohnsHopkins\Color\Grades;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
require_once 'functions.php';

$colors = getCompliantColors();

$palettes = [
  'grayscale' => [
    // 50 => [120, 116, 112], // get the colors to be more gray (swatch taken from eric's scale and modified to be within range 50)
    90 => $colors[21]['rgb'],
  ],
  'red' => [
    30 => $colors[10]['rgb'],
    70 => $colors[12]['rgb'],
    80 => $colors[13]['rgb'],
  ],
  'orange' => [
    30 => $colors[2]['rgb'],
    40 => $colors[7]['rgb'],
    50 => $colors[11]['rgb'],
  ],
  'gold' => [
    30 => $colors[5]['rgb'],
    60 => $colors[8]['rgb'],
  ],
  'warm green' => [
    // 30 => $colors[18]['rgb'],
    40 => $colors[20]['rgb'],
    // 50 => $colors[3]['rgb'],
    70 => $colors[19]['rgb'],
  ],
  'cool green' => [
    30 => $colors[18]['rgb'],
    50 => $colors[3]['rgb'],
  ],
  'blue' => [
    30 => $colors[1]['rgb'],
    40 => $colors[17]['rgb'],
    50 =>  $colors[4]['rgb'],
    80 => $colors[0]['rgb'],
  ],
  'cool purple' => [
    40 => $colors[16]['rgb'],
  ],
  'warm purple' => [
    50 => $colors[15]['rgb'],
    80 => $colors[14]['rgb'],
  ],
  'neutral' => [
    30 => $colors[6]['rgb'],
    80 => $colors[9]['rgb'],
  ]
];

$palettes = array_map(static function ($knownPalette) {

  $grades = new Grades();

  $compiledPalette = getStarterPalette();

  // going from first color down to white and last color down to black,
  // do not affect hue or satration -- only hue

  // colors already filled in the palette
  $filled = array_keys(array_filter($knownPalette, 'is_array'));

  // go from white to first found color
  // create grades inbetween white and first found color
  $first = array_shift($filled);

  // index of first color grade (to be used with $grades)
  $index = $compiledPalette[$first];

  // add first color to compiled palette
  $compiledPalette[$first] = $knownPalette[$first];

  // go down grades to white
  $down = array_reverse(array_slice($grades->bounds, 0, $index + 1, true), true);
  $currentRGB = $knownPalette[$first]; // reset RGB starting point

  foreach ($down as $gradeToFind => $bounds) {

    if ($gradeToFind === 0) {
      // white
      continue;
    }

    // this is only affecting lightness
    $colors = $grades->getGradeColors($currentRGB, ...$bounds);
    $compiledPalette[$gradeToFind] = $colors['mid'];

    // set new RGB as the min of the previous grade
    $currentRGB = $colors['min'];
  }

  // go from last found color to black
  // create grades inbetween last found color and black
  if (!empty($filled)) {
    $last = array_pop($filled);

    // index of last color grade (to be used with $grades)
    $index = $compiledPalette[$last];

    // go up grades to black
    $up = array_slice($grades->bounds, $index + 1, null, true);
    $currentRGB = $knownPalette[$last];
    foreach ($up as $gradeToFind => $bounds) {

      if ($gradeToFind === 100 || is_array($compiledPalette[$gradeToFind])) {
        // black or brand color
        continue;
      }

      $colors = $grades->getGradeColors($currentRGB, ...$bounds);
      $compiledPalette[$gradeToFind] = $colors['mid'];

      // set new RGB as the max of the previous grade
      $currentRGB = $colors['max'];
    }
  }

  // fill in the gaps

  $gaps = array_keys(array_filter($compiledPalette, 'is_int'));

  // first, do known colors
  foreach ($gaps as $missingGrade) {
    if (isset($knownPalette[$missingGrade])) {
      // this color is preset
      $compiledPalette[$missingGrade] = $knownPalette[$missingGrade];
    }
  }

  // find still missing gaps
  $gaps = array_keys(array_filter($compiledPalette, 'is_int'));

  // group grades together that will use the same bookend colors
  $gapGroups = groupSequential($gaps);

  foreach($gapGroups as $group) {

    $startGrade = $group[0] === 5 ? 0 : $group[0] - 10;
    $endGrade = $group[count($group) - 1] + 10;

    $startColor = Convert::rgb_hsl($compiledPalette[$startGrade]);
    $endColor = Convert::rgb_hsl($compiledPalette[$endGrade]);

    $gapsToFill = count($group);

    $diffIntervals = [
      (min(abs($startColor[0] - $endColor[0]), 360 - abs($startColor[0] - $endColor[0]))) / ($gapsToFill + 1),
      ($startColor[1] - $endColor[1]) / ($gapsToFill + 1),
      ($startColor[2] - $endColor[2]) / ($gapsToFill + 1),
    ];

    // direction we're moving around the color wheel
    $clockwise = ((($startColor[0] - $endColor[0]) + 360) % 360) < 180;

    foreach ($group as $grade) {

      $hue = $clockwise ? $startColor[0] - $diffIntervals[0] : $startColor[0] + $diffIntervals[0];
      if ($hue < 0) {
        $hue = 360 + $hue;
      }

      $startColor = [
        $hue,
        $startColor[1] - $diffIntervals[1],
        $startColor[2] - $diffIntervals[2],
      ];

      $compiledPalette[$grade] = Convert::hsl_rgb($startColor);
    }

  }

  // remove black and white
  unset($compiledPalette[0], $compiledPalette[100]);

  return $compiledPalette;

}, $palettes);

printTable($palettes);
