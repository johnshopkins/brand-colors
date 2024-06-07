<?php

use JohnsHopkins\Color\Calculate;
use JohnsHopkins\Color\Grades;

function getColorCell(array $color, bool $brandColor = false): string
{
  $rgb = 'rgb(' . implode(",", $color) . ')';
  $html = "<td style='background-color:$rgb;' align='center'>";

  if ($brandColor) {
    $html .= '<span style="-webkit-text-stroke: 0.7px #ccc;">•</span>';
  }

  $html .= "</td>";
  return $html;
}

function printTable($palettes)
{
  $grades = new Grades();

  foreach ($palettes as $name => $colors) {
    echo "<table style='table-layout: fixed; border: 1px solid #ccc; float:left; margin-right: 5px;' cellpadding='10' cellspacing='0'>";

    echo "<thead><tr>";
    echo "<th colspan='2' style='font-size:20px;'>{$name}</th>";
    echo "</tr></thead>";

    echo "<tr>";
    echo "<th>Grade</th><th>Color</th>";
    // echo "<th>Lum</th><th>Pass</th>";
    echo "</tr>";

    foreach ($colors as $grade => $color) {
      echo "<tr>";
      echo "<td>$grade</td>";

      if (is_array($color) || (is_object($color) && $color->hex == '787470')) {
        $color = (array) $color;

        // created color (|| is a hack for gray 50, which isn't actually a brand color)
        echo getColorCell($color['rgb']);
        // $luminance = round(Calculate::luminance($color['rgb']), 3);
        // $bounds = $grades->bounds[$grade];
        // $pass = $luminance >= $bounds[0] && $luminance <= $bounds[1];
        // echo "<td>$luminance</td>";
        // echo "<td>$pass</td>";

      } else {
        // brand color
        echo getColorCell($color->rgb, true);
        // $luminance = round(Calculate::luminance($color->rgb), 3);
        // $bounds = $grades->bounds[$grade];
        // $pass = $luminance >= $bounds[0] && $luminance <= $bounds[1];
        // echo "<td>$luminance</td>";
        // echo "<td>$pass</td>";
      }
      echo "</td></tr>";
    }

    echo "</tbody></table>";
  }
}
