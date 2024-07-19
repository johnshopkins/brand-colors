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

$modes = ['print', 'json'];

// json: save palettes to config/palettes.json
// print: show palettes in an html table
if (!isset($argv)) {
  // browser
  $mode = 'print';
} else {
  // cli
  $mode = $argv[1] ?? null;
  if (!in_array($mode, $modes)) {
    die('No valid mode selected.');
  }
}

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
  20 => [],
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

function logError($message)
{
  echo "\033[31m$message \033[0m\n";
}

$colors = array_map(static function ($color) use ($grades, $mode, $shift) {

  $id = $color['id'];

  $grade = $grades->findGradeOfRGB($color['rgb']);

  if (isset($shift[$id])) {

    if ($mode === 'print') {
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
    'sable' => contrastWithSable($color),
    'white' => Calculate::contrast([255, 255, 255], $color['rgb']),
  ];

  // highest contrast between white and sable
  $color['aa_contrast'] = array_keys($color['contrast'], max($color['contrast']))[0];

  if ($color['contrast'][$color['aa_contrast']] < 4.5) {
    logError("{$color['name']} contrasting AA color does not meet 4.5:1 contrast ratio.");
    die();
  }

  // add double black to list of contrasts
  $color['contrast']['double-black'] = Calculate::contrast($color['rgb'], [0, 0, 0]);

  return $color;

}, $colors);

if ($mode === 'json') {

  $organized = [
    'all' => $colors,
    'alert' => [
      'error' => $colors[12],
      'warning' => $colors[5],
      'success' => $colors[3],
      'info' => $colors[4]
    ],
    'sorted' => [
      'primary' => [],
      'secondary' => [],
      'accent' => [],
    ]
  ];

  foreach ($colors as $color) {
    $organized['sorted'][$color['type']][] = $color;
  }

  // hex to palette family token
  $tokens = [];
  foreach ($colors as $color) {
    $tokens["#{$color['hex']}"] = "--jhu-{$color['slug']}"; 
  }

  $json = json_encode($organized);
  file_put_contents(dirname(__DIR__) . '/config/web-colors.json', $json);

  $json = json_encode($tokens);
  file_put_contents(dirname(__DIR__) . '/config/hex-to-brand-tokens.json', $json);

} else if ($mode === 'print') {

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
