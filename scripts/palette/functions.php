<?php

use JohnsHopkins\Color\Calculate;
use JohnsHopkins\Color\Convert;

/**
 * Get three colors per grade (min, mid, max) based on mininum
 * and maximum luminance of the grade
 * @param array $startingRGB RBG color to base new colors from. Value is an array of RGB values ex: [0, 45, 114]
 * @param float $min         Minimum luminance required by the grade
 * @param float $max         Maximum luminance required by the grade
 * @return array
 */
function getGradeColors(array $startingRGB, $min, $max): array
{
  $startingHSL = Convert::rgb_hsl($startingRGB);
  $startingLuminance = round(Calculate::luminance($startingRGB), 3);

  // which direction are we moving in the grades
  // down == we're looking for a lighter color
  // up === we're looking for a darker color
  $direction = $startingLuminance > $min ? 'down' : 'up';

  $foundMinLightness = null;
  $foundMinColor = null;

  $foundMaxLightness = null;
  $foundMaxColor = null;

  $hsl = $startingHSL;
  $luminance = $startingLuminance;

  while ($direction === 'up' ? $luminance < $max : $luminance > $min) {

    // increase or decrease lightness value in HSL
    $direction === 'up' ? $hsl[2]++ : $hsl[2]--;

    // convert new HSL to RGB and get luminance
    $rgb = Convert::hsl_rgb($hsl);
    $luminance = round(Calculate::luminance($rgb), 3);

    if ($direction === 'up') {

      if ($foundMinColor === null && $luminance >= $min) {
        // set min color if it isn't already set
        $foundMinColor = $rgb;
        $foundMinLightness = $hsl[2];
      }

      // set max color until black statement finishes
      $foundMaxColor = $rgb;
      $foundMaxLightness = $hsl[2];

    } else {

      if ($foundMaxColor === null && $luminance <= $max) {
        // set max color if it isn't already set
        $foundMaxColor = $rgb;
        $foundMaxLightness = $hsl[2];
      }

      // set min color until white statement finishes
      $foundMinColor = $rgb;
      $foundMinLightness = $hsl[2];

    }
  }

  // get midrange color
  $hsl[2] = $foundMaxLightness - (($foundMaxLightness - $foundMinLightness) / 2);
  $midColor = Convert::hsl_rgb($hsl);

  return [
    'min' => $foundMinColor,
    'mid' => $midColor,
    'max' => $foundMaxColor
  ];
}

function getColorCell(array $color, $full = false): string
{
  $rgb = 'rgb(' . implode(",", $color) . ')';
  $colspan = $full ? 3 : 1;
  return "<td colspan='$colspan' style='background-color:$rgb;'></td>";
}

function printTable($colors)
{
  echo "<table style='table-layout: fixed;' cellpadding='10' cellspacing='0' border='1'>";

  echo "<thead><tr>";

  foreach ($colors as $color) {
    echo "<th style='font-size:20px;'>{$color['name']}<br />{$color['grade']}</th>";
  }
  echo "</tr></thead>";

  echo "<tbody><tr>";
  foreach ($colors as $color) {

    echo "<td><table>";
    echo "<tr><th>Grade</th><th>Min</th><th>Mid</th><th>Max</th></tr>";

    foreach ($color['palette'] as $grade => $gradeColors) {
      echo "<tr>";
      echo "<td>$grade</td>";

      if (isset($gradeColors['min'])) {
        echo getColorCell($gradeColors['min']);
        echo getColorCell($gradeColors['mid']);
        echo getColorCell($gradeColors['max']);
      } else {
        // this is the brand color
        echo getColorCell($gradeColors, true);
      }
      echo "</td></tr>";
    }

    echo "</table></td>";

  }
  echo "</tr></tbody>";
  echo "</table>";
}
