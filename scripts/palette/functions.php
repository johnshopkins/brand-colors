<?php

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
