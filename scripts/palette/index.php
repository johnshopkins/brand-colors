<?php

use JohnsHopkins\Color\Grades;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
require_once __DIR__ . '/functions.php';

$json = file_get_contents(dirname(__DIR__, 2) . '/config/colors.json');
$colors = json_decode($json, true);

$grades = new Grades();
$gradeNums = [5, 10, 20, 30, 40, 50, 60, 70, 80, 90];
$emptyPalette = array_flip($gradeNums);

$colors = array_filter(array_map(function ($color) use ($emptyPalette, $gradeNums, $grades) {

  // don't include white and black
  if (in_array($color['slug'], ['white', 'double-black'])) {
    return null;
  }

  // grade of this brand color
  $color['grade'] = $grades->findGradeOfRGB($color['rgb']);

  $palette = $emptyPalette;

  if ($color['grade'] % 10 === 0) {
    // set the brand color as this grade's color
    $palette[$color['grade']] = $color['rgb'];
  }

  // index of current or closest grade (rounded up)
  $index = array_search($color['grade'] % 10 === 0 ? $color['grade'] : $color['grade'] + 5, $gradeNums);

  // go down grades to white
  $down = array_reverse(array_slice($grades->bounds, 0, $index + 1, true), true);
  $currentRGB = $color['rgb']; // reset RGB starting point
  foreach ($down as $gradeToFind => $bounds) {

    if ($gradeToFind === 0) {
      // white
      continue;
    }

    $palette[$gradeToFind] = $grades->getGradeColors($currentRGB, ...$bounds);

    // set new RGB as the min of the previous grade
    $currentRGB = $palette[$gradeToFind]['min'];
  }


  // go up grades to black
  $up = array_slice($grades->bounds, $index + 1, null, true);
  $currentRGB = $color['rgb'];
  foreach ($up as $gradeToFind => $bounds) {

    if ($gradeToFind === 100 || is_array($palette[$gradeToFind])) {
      // black or brand color
      continue;
    }

    $palette[$gradeToFind] = $grades->getGradeColors($currentRGB, ...$bounds);

    // set new RGB as the max of the previous grade
    $currentRGB = $palette[$gradeToFind]['max'];
  }

  $color['palette'] = $palette;

  return $color;

}, $colors));

printTable($colors);
