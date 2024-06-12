<?php

use JohnsHopkins\Color\Colors;
use JohnsHopkins\Color\Grades;
use JohnsHopkins\Color\Palette;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$modes = ['print', 'json', 'scss'];

// json: save palettes to config/palettes.json
// print: show palettes in an html table
if (!isset($argv)) {
  // browser
  $mode = 'print';
} else {
  // cli
  $mode = $argv[1] ?? null;
  if (!in_array($mode, $modes)) {
    die('No valid mode selected.');
  }
}

$colors = Colors::get();

$palettes = [
  'Grayscale' => [
    'colors' => [
      50 => (object) [
        'rgb' => [120, 116, 112],
        'hex' => '787470',
      ],                     // get the colors to be more gray (swatch taken from eric's scale and modified to be within range 50)
      90 => $colors[22],     // sable
    ],
    'settings' => [
      'grayscale' => true,
    ]
  ],
  'Red' => [
    'colors' => [
      30 => $colors[10], // 486 (accent coral)
      70 => $colors[12], // 186 (accent red)
      80 => $colors[13], // 188 (accent maroon)
    ]
  ],
  'Orange' => [
    'colors' => [
      30 => $colors[2],  // 1375 (secondary-orange)
      40 => $colors[7],  // 1505 (accent-orange)
      50 => $colors[11], // 173 (accent dark orange)
    ]
  ],
  'Gold' => [
    'colors' => [
      30 => $colors[5], // 7406 (secondary yellow)
      60 => $colors[8], // 7586 (accent brown)
    ]
  ],
  'Warm Green' => [
    'colors' => [
      40 => $colors[20], // 7490 (accent green)
      70 => $colors[19], // 7734 (accent dark green)
    ]
  ],
  'Cool Green' => [
    'colors' => [
      30 => $colors[18], // 564 (accent seafoam)
      50 => $colors[3],  // 3278 (secondary green)
    ]
  ],
  'Blue' => [
    'colors' => [
      30 => $colors[1],  // 284 (spirit blue)
      40 => $colors[17], // 279 (accent blue)
      50 =>  $colors[4], // 285 (secondary blue)
      80 => $colors[0],  // 288 (heritage blue)
    ]
  ],
  'Cool Purple' => [
    'colors' => [
      40 => $colors[16], // 666 (accent lavender)
    ]
  ],
  'Warm Purple' => [
    'colors' => [
      50 => $colors[15], // 7655 (accent purple)
      80 => $colors[14], // 262 (accent dark purple)
    ]
  ],
  'Neutral' => [
    'colors' => [
      30 => $colors[6], // 7407 (accent tan)
      80 => $colors[9], // 4625 (accent dark brown)
    ]
  ],

  'green' => [
    'colors' => [
      30 => $colors[18], // 564 (accent seafoam)
      40 => $colors[20], // 7490 (accent green)
      50 => $colors[3],  // 3278 (secondary green)
      70 => $colors[19], // 7734 (accent dark green)
    ]
  ],
  'purple' => [
    'colors' => [
      40 => $colors[16], // 666 (accent lavender)
      50 => $colors[15], // 7655 (accent purple)
      80 => $colors[14], // 262 (accent dark purple)
    ]
  ],
];

$palettes = array_map(fn ($knownPalette) => (new Palette())->create($knownPalette), $palettes);

if ($mode === 'json') {

  $palettes = array_map(function ($colors) {
    return array_map(function ($color) {
      if (is_a($color, \JohnsHopkins\Color\Color::class)) {
        return ['rgb' => $color->rgb, 'hex' => $color->hex, 'brand' => $color->id];
      }
      return $color;
    }, $colors);
  }, $palettes);

  $json = json_encode($palettes);
  file_put_contents(dirname(__DIR__) . '/config/palettes.json', $json);

} else if ($mode === 'print') {

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

  printTable($palettes);
}

