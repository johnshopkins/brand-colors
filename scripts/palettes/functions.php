<?php

use JohnsHopkins\Color\Calculate;
use JohnsHopkins\Color\Grades;

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
      echo "</td></tr>";
    }

    echo "</tbody></table>";
  }
}
