<?php

namespace JohnsHopkins\Color;

class Palette
{
  protected Grades $grades;

  public function __construct()
  {
    $this->grades = new Grades();
  }

  public function create($knownPalette)
  {
    $compiledPalette = $this->getStarterPalette($knownPalette['colors']);

    // find missing grades
    $gaps = array_keys(array_filter($compiledPalette, 'is_int'));

    // group missing grades together that will use the same bookend colors
    $gapGroups = $this->groupSequential($gaps);

    foreach($gapGroups as $group) {

      $startGrade = $group[0] === 5 ? 0 : $group[0] - 10; // starting bookend
      $endGrade = $group[count($group) - 1] + 10;   // ending bookend

      // convert to HSL
      $startColor = Convert::rgb_hsl($compiledPalette[$startGrade]->rgb);
      $endColor = Convert::rgb_hsl($compiledPalette[$endGrade]->rgb);

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

      // get hue and saturation intervals for missing grades
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
        $lightness = $this->decreaseLightness([$hue, $saturation, $startColor[2]], ...$this->grades->bounds[$grade]);

        // put it alltogether
        $newColor = [$hue, $saturation, $lightness];

        $rgb = Convert::hsl_rgb($newColor);
        $compiledPalette[$grade] = [
          'rgb' => $rgb,
          'hex' => Convert::rgb_hex($rgb)
        ];

        $startColor = $newColor;
      }
    }

    // remove black and white
    unset($compiledPalette[0], $compiledPalette[100]);

    return $compiledPalette;
  }

  protected function decreaseLightness(array $startingHSL, $min, $max)
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

  protected function getStarterPalette(array $knownPalette): array
  {
    $gradeNums = [0, 5, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100];
    $starter = array_flip($gradeNums);

    foreach ($knownPalette as $grade => $color) {
      $starter[$grade] = $color;
    }

    // add in black and white
    $starter[0] = (object) ['rgb' => [255, 255, 255]];
    $starter[100] = (object) ['rgb' => [0, 0, 0]];

    return $starter;
  }

  /**
   * Group sequential ranges together
   * Ex: 5, 10, 20
   * @param $numbers
   * @return array
   */
  protected function groupSequential($numbers): array
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

  protected function printTable($palettes)
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

  protected function getColorCell(array $color, $full = false): string
  {
    $rgb = 'rgb(' . implode(",", $color) . ')';
    $colspan = $full ? 3 : 1;
    return "<td colspan='$colspan' style='background-color:$rgb;'></td>";
  }
}
