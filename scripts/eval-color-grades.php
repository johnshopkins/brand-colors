<?php

/**
 * Calculates the grade of each brand color
 */

use cli\Table;
use JohnsHopkins\Color\Calculate;
use JohnsHopkins\Color\Grades;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$json = file_get_contents(dirname(__DIR__) . '/config/brand-colors.json');
$colors = json_decode($json, true);

$grades = new Grades(true);

$rows = array_map(function ($color) use ($grades) {

  $luminance = round(Calculate::luminance($color['rgb']), 3);
  $grade = $grades->findGradeOfRGB($color['rgb']);

  return [
    $color['name'],
    $luminance,
    $grade,
    $grade % 10 !== 0 ? 'y' : '',
  ];
}, $colors);


$table = new Table([
  'Name',
  'Luminance',
  'Grade',
  'Needs shift?',
], $rows);
$table->display();
