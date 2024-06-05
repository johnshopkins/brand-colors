<?php

/**
 * Shifts brand colors outside of a grade range to within a grade range
 */

use JohnsHopkins\Color\Grades;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$json = file_get_contents(dirname(__DIR__) . '/config/colors.json');
$colors = json_decode($json, true);

$grades = new Grades(false);

// colors that fall outside of a range
$colors = [
  [$colors[3]],
  [$colors[4], null, true],
  [$colors[6]],
  [$colors[7], null, true],
  [$colors[8], null, true],
  [$colors[13], 80], // maroon
  [$colors[15], null, true],
  [$colors[16], null, true],
  [$colors[17], null, true],
  [$colors[20], null, true],
];

echo "<table style='table-layout: fixed;' cellpadding='10' cellspacing='0' border='1'>";
echo "<thead><tr><th>Name</th><th>Old</th><th>New</th></tr></thead>";

echo "<tbody>";
$rows = array_map(function ($c) use ($grades) {

  $color = $c[0];
  $opposite = $c[1] ?? false;

  echo "<tr>";

  echo "<td>{$color['name']}</td>";

  $oldGrade = $grades->findGradeOfRGB($color['rgb']);

  $rgb = 'rgb(' . implode(",", $color['rgb']) . ')';
  echo "<td style='background-color:$rgb; color: #fff;'>$oldGrade</td>";

  $new = $grades->shiftRGBtoGrade($color['rgb'], $opposite);

  $newRGB = $new['direction'] === 'up' ? $new['colors']['max'] : $new['colors']['min'];
  $rgb = 'rgb(' . implode(",", $newRGB) . ')';
  echo "<td style='background-color:$rgb; color: #fff;'>{$new['grade']}</td>";

  echo "</tr>";

}, $colors);
echo "</tbody></table>";


// // closest range
// echo "<table style='table-layout: fixed;' cellpadding='10' cellspacing='0' border='1'>";
// echo "<thead><tr><th>Name</th><th>Old</th><th>New</th></tr></thead>";
//
// echo "<tbody>";
// $rows = array_map(function ($c) use ($grades) {
//
//   $color = $c[0];
//
//   echo "<tr>";
//
//   echo "<td>{$color['name']}</td>";
//
//   $oldGrade = $grades->findGradeOfRGB($color['rgb']);
//
//   $rgb = 'rgb(' . implode(",", $color['rgb']) . ')';
//   echo "<td style='background-color:$rgb; color: #fff;'>$oldGrade</td>";
//
//   $new = $grades->shiftRGBtoGrade($color['rgb']);
//
//   $newRGB = $new['direction'] === 'up' ? $new['colors']['max'] : $new['colors']['min'];
//   $rgb = 'rgb(' . implode(",", $newRGB) . ')';
//   echo "<td style='background-color:$rgb; color: #fff;'>{$new['grade']}</td>";
//
//   echo "</tr>";
//
// }, $colors);
// echo "</tbody></table>";
//
//
// // farthest range
// echo "<table style='table-layout: fixed;' cellpadding='10' cellspacing='0' border='1'>";
// echo "<thead><tr><th>Name</th><th>Old</th><th>New</th></tr></thead>";
//
// echo "<tbody>";
// $rows = array_map(function ($c) use ($grades) {
//
//   $color = $c[0];
//
//   echo "<tr>";
//
//   echo "<td>{$color['name']}</td>";
//
//   $oldGrade = $grades->findGradeOfRGB($color['rgb']);
//
//   $rgb = 'rgb(' . implode(",", $color['rgb']) . ')';
//   echo "<td style='background-color:$rgb; color: #fff;'>$oldGrade</td>";
//
//   $new = $grades->shiftRGBtoGrade($color['rgb'], true);
//
//   $newRGB = $new['direction'] === 'up' ? $new['colors']['max'] : $new['colors']['min'];
//   $rgb = 'rgb(' . implode(",", $newRGB) . ')';
//   echo "<td style='background-color:$rgb; color: #fff;'>{$new['grade']}</td>";
//
//   echo "</tr>";
//
// }, $colors);
// echo "</tbody></table>";
