<?php

/**
 * Shifts brand colors outside of a grade range to within a grade range.
 * In addition to adjusting the RGB and hex values, the following data is added:
 * - Grade
 * - Luminance
 */

use JohnsHopkins\Color\Calculate;
use JohnsHopkins\Color\Convert;
use JohnsHopkins\Color\Grades;

require_once dirname(__DIR__) . '/vendor/autoload.php';

// show shifted colors in a table
$mode = 'table';

// // save shifted colors to config/colors.json
// $mode = 'json';

$saveJSON = false;
$showTable = true;

$json = file_get_contents(dirname(__DIR__) . '/config/brand-colors.json');
$colors = json_decode($json, true);

$grades = new Grades(false);

// colors that need a shift
$shift = [
  4 => [],
  5 => [null, true],
  8 => [null, true],
  9 => [null, true],
  14 => [80], // maroon (so we can have two reds in the palette)
  16 => [null, true],
  17 => [null, true],
  18 => [null, true],
  21 => [null, true],
];

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

$colors = array_map(static function ($color) use ($grades, $mode, $shift) {

  $id = $color['id'];

  $grade = $grades->findGradeOfRGB($color['rgb']);

  if (isset($shift[$id])) {

    if ($mode === 'table') {
      $color['shifted'] = true;
      $color['original'] = [
        'rgb' => $color['rgb'],
        'hex' => $color['hex'],
        'grade' => $grade,
      ];
    }

    $new = $grades->shiftRGBtoGrade($color['rgb'], $shift[$id][0] ?? null, $shift[$id][1] ?? false);

    $color['rgb'] = $new['rgb'];
    $color['hex'] = Convert::rgb_hex($new['rgb']);
    $color['grade'] = $new['grade'];
  } else {
    $color['grade'] = $grade;
  }

  // standardize hex to lowercase
  $color['hex'] = strtolower($color['hex']);

  // add luminosity and contrast values
  $color['luminance'] = round(Calculate::luminance($color['rgb']) * 100, 2);
  $color['contrast'] = [
    'double-black' => Calculate::contrast($color['rgb'], [0, 0, 0]),
    'sable' => contrastWithSable($color),
    'white' => Calculate::contrast([255, 255, 255], $color['rgb']),
  ];

  return $color;

}, $colors);

if ($mode === 'json') {

  $json = json_encode($colors);
  file_put_contents(dirname(__DIR__) . '/config/colors.json', $json);
  exec('npm run format-json');

} else if ($mode === 'table') {

  echo "<table style='table-layout: fixed;' cellpadding='10' cellspacing='0' border='1'>";
  echo "<thead><tr><th>Name</th><th>Old</th><th>New</th></tr></thead><tbody>";

  array_filter(array_map(static function ($color) {

    if (!isset($color['shifted'])) {
      return null;
    }

    echo "<tr><td>{$color['name']}</td>";

    $rgb = 'rgb(' . implode(",", $color['original']['rgb']) . ')';
    echo "<td style='background-color:$rgb; color: #fff;'>{$color['original']['grade']}</td>";

    $rgb = 'rgb(' . implode(",", $color['rgb']) . ')';
    echo "<td style='background-color:$rgb; color: #fff;'>{$color['grade']}</td></tr>";

  }, $colors));

  echo "</tbody></table>";
}
