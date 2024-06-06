<?php

use JohnsHopkins\Color\Calculate;
use JohnsHopkins\Color\Convert;
use JohnsHopkins\Color\Grades;

function getStarterPalette(): array
{
  $gradeNums = [0, 5, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100];
  $emptyPalette = array_flip($gradeNums);

  $emptyPalette[0] = [255, 255, 255];
  $emptyPalette[100] = [0, 0, 0];

  return $emptyPalette;
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

    $color['rgb'] = $new['color'];
    $color['hex'] = Convert::rgb_hex($new['color']);

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

  foreach ($palettes as $name => $colors) {
    echo "<table style='table-layout: fixed; border: 1px solid #ccc; float:left; margin-right: 5px;' cellpadding='10' cellspacing='0'>";

    echo "<thead><tr>";
    echo "<th colspan='2' style='font-size:20px;'>{$name}</th>";
    echo "</tr></thead>";

    echo "<tr><th>Grade</th><th>Color</th></tr>";
    foreach ($colors as $grade => $color) {
      echo "<tr>";
      echo "<td>$grade</td>";

      if (is_array($color)) {
        echo getColorCell($color);
      } else {
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
