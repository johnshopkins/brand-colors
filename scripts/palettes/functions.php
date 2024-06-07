<?php

use JohnsHopkins\Color\Calculate;
use JohnsHopkins\Color\Convert;
use JohnsHopkins\Color\Grades;

function decreaseLightness(array $startingHSL, $min, $max)
{
  $foundMinLightness = null;
  $foundMinColor = null;

  $foundMaxLightness = null;
  $foundMaxColor = null;

  $hsl = $startingHSL;

  $luminance = round(Calculate::luminance(Convert::hsl_rgb($startingHSL)), 3);

  while ($luminance > $min) {

    // decrease lightness value in HSL
    // $hsl[2]--;
    $hsl[2] -= .5;

    // convert new HSL to RGB and get luminance
    $rgb = Convert::hsl_rgb($hsl);
    $luminance = round(Calculate::luminance($rgb), 3);

    if ($foundMaxColor === null && $luminance <= $max) {
      // set max color if it isn't already set
      $foundMaxColor = $hsl;
      $foundMaxLightness = $hsl[2];
    }

    // set min color until white statement finishes
    $foundMinColor = $hsl;
    $foundMinLightness = $hsl[2];
  }

  // get midrange color
  $hsl[2] = $foundMaxLightness - (($foundMaxLightness - $foundMinLightness) / 2);
  return $hsl[2];

  // return [
  //   'min' => $foundMinColor,
  //   'mid' => $midColor,
  //   'max' => $foundMaxColor,
  // ];
}

function getStarterPalette(array $knownPalette): array
{
  $gradeNums = [0, 5, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100];
  $starter = array_flip($gradeNums);

  foreach ($knownPalette as $grade => $color) {
    $starter[$grade] = $color;
  }

  // add in black and white
  $starter[0] = [255, 255, 255];
  $starter[100] = [0, 0, 0];

  return $starter;
}

/**
 * Get all brand colors, shifting colors that are not
 * in one of our luminance ranges into a luminance range.
 * @return array
 */
function getCompliantColors(): array
{
  $json = file_get_contents(dirname(__DIR__, 2) . '/config/colors.json');
  $colors = json_decode($json, true);

  // colors that need to shift
  // id => [$grade, $opposite]
  $shift = [
    4 => [],
    5 => [null, true],
    7 => [],
    8 => [null, true],
    9 => [null, true],
    14 => [80], // maroon (shift from 70 t0 80 so we have two grades of red)
    16 => [null, true],
    17 => [null, true],
    18 => [null, true],
    21 => [null, true],
  ];

  $grades = new Grades();

  return array_map(function ($color) use ($grades, $shift) {

    if (!isset($shift[$color['id']])) {
      return $color;
    }

    $args = $shift[$color['id']];

    $new = $grades->shiftRGBtoGrade($color['rgb'], ...$args);

    $color['rgb'] = $new['rgb'];
    $color['hex'] = Convert::rgb_hex($new['rgb']);

    return $color;

  }, $colors);
}

/**
 * Group sequential ranges together
 * Ex: 5, 10, 20
 * @param $numbers
 * @return array
 */
function groupSequential($numbers): array
{
  // 5 is an oddball -- convert to 0 initially
  $key = array_search(5, $numbers);
  if ($key !== false) {
    $numbers[$key] = 0;
  }

  $groups = [];

  $prev = null;
  for ($i = 0, $g = 0, $max = count($numbers); $i < $max; $i++) {

    $thisNum = $numbers[$i];

    if ($i === 0 || $prev + 10 === $thisNum) {
      // if first number OR sequential to the previous number (by 10), assign to first group
      $groups[$g][] = $thisNum === 0 ? 5 : $thisNum;
    } else {
      // otherwise, start a new group
      $g++;
      $groups[$g][] = $thisNum === 0 ? 5 : $thisNum;
    }

    $prev = $thisNum;

  }

  return $groups;
}


// Table printing

function getColorCell(array $color, $full = false): string
{
  $rgb = 'rgb(' . implode(",", $color) . ')';
  $colspan = $full ? 3 : 1;
  return "<td colspan='$colspan' style='background-color:$rgb;'></td>";
}

function printTable($palettes)
{
  $grades = new Grades();

  foreach ($palettes as $name => $colors) {
    echo "<table style='table-layout: fixed; border: 1px solid #ccc; float:left; margin-right: 5px;' cellpadding='10' cellspacing='0'>";

    echo "<thead><tr>";
    echo "<th colspan='2' style='font-size:20px;'>{$name}</th>";
    echo "</tr></thead>";

    echo "<tr><th>Grade</th><th>Color</th><th>Lum</th><th>Pass</th></tr>";
    foreach ($colors as $grade => $color) {
      echo "<tr>";
      echo "<td>$grade</td>";

      if (is_array($color)) {
        echo getColorCell($color);
        $luminance = round(Calculate::luminance($color), 3);
        $bounds = $grades->bounds[$grade];
        $pass = $luminance >= $bounds[0] && $luminance <= $bounds[1];
        echo "<td>$luminance</td>";
        echo "<td>$pass</td>";
      } else {
        echo '<td></td>';
        echo '<td></td>';
        echo '<td></td>';
      }

      // if (is_array($color) && isset($color['mid'])) {
      //   // echo getColorCell($gradeColors['min']);
      //   echo getColorCell($color['mid']);
      //   // echo getColorCell($gradeColors['max']);
      // } else if (is_object($color)) {
      //   // this is the brand color
      //   echo getColorCell($color->rgb, true);
      // } else {
      //   echo '<td></td>';
      // }
      echo "</td></tr>";
    }

    echo "</tbody></table>";
  }
}
