<?php

use JohnsHopkins\Color\Colors;
use JohnsHopkins\Color\Grades;
use JohnsHopkins\Color\Palette;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$modes = ['print', 'json'];

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
      0 => $colors[21], // white
      50 => (object) [
        'rgb' => [120, 116, 112],
        'hex' => '787470',
      ],                     // get the colors to be more gray (swatch taken from eric's scale and modified to be within range 50)
      90 => $colors[22],     // sable
      100 => $colors[23], // black
    ],
    'settings' => [
      'grayscale' => true,
    ]
  ],
  'Red' => [
    'colors' => [
      0 => $colors[21],
      30 => $colors[20], // 486 (Salmon)
      70 => $colors[15], // 186 (Dark Red)
      80 => $colors[16], // 188 (Maroon)
      100 => $colors[23],
    ]
  ],
  'Orange' => [
    'colors' => [
      0 => $colors[21],
      30 => $colors[12],  // 1375 (orange)
      40 => $colors[13],  // 1505 (red-orange)
      50 => $colors[14], // 173 (Red)
      100 => $colors[23],
    ]
  ],
  'Gold' => [
    'colors' => [
      0 => $colors[21],
      30 => $colors[11], // 7406 (Gold)
      60 => $colors[18], // 7586 (Light Brown)
      100 => $colors[23],
    ]
  ],
  'Warm Green' => [
    'colors' => [
      0 => $colors[21],
      40 => $colors[7], // 7490 (Lime Green)
      70 => $colors[6], // 7734 (Forest Green)
      100 => $colors[23],
    ]
  ],
  'Cool Green' => [
    'colors' => [
      0 => $colors[21],
      30 => $colors[4], // 564 (Mint Green)
      50 => $colors[5],  // 3278 (Homewood Green)
      100 => $colors[23],
    ]
  ],
  'Blue' => [
    'colors' => [
      0 => $colors[21],
      30 => $colors[1],  // 284 (spirit blue)
      40 => $colors[3], // 279 (Harbor Blue)
      50 =>  $colors[2], // 285 (Medium Blue)
      80 => $colors[0],  // 288 (heritage blue)
      100 => $colors[23],
    ]
  ],
  'Warm Purple' => [
    'colors' => [
      0 => $colors[21],
      50 => $colors[10], // 7655 (Purple)
      80 => $colors[9], // 262 (Plum)
      100 => $colors[23],
    ]
  ],
  'Cool Purple' => [
    'colors' => [
      0 => $colors[21],
      40 => $colors[8], // 666 (Lavender)
      100 => $colors[23],
    ]
  ],
  'Neutral' => [
    'colors' => [
      0 => $colors[21],
      30 => $colors[17], // 7407 (Sand)
      80 => $colors[19], // 4625 (Dark Brown)
      100 => $colors[23],
    ]
  ],

  // 'green' => [
  //   'colors' => [
  //     30 => $colors[4], // 564 (Mint Green)
  //     40 => $colors[7], // 7490 (Lime Green)
  //     50 => $colors[5],  // 3278 (Homewood Green)
  //     70 => $colors[6], // 7734 (Forest Green)
  //   ]
  // ],
  // 'purple' => [
  //   'colors' => [
  //     40 => $colors[8], // 666 (Lavender)
  //     50 => $colors[10], // 7655 (Purple)
  //     80 => $colors[9], // 262 (Plum)
  //   ]
  // ],
];

$palettes = array_map(fn ($knownPalette) => (new Palette())->create($knownPalette), $palettes);

if ($mode === 'json') {

  // expanded palettes
  $palettes = array_map(function ($colors) {
    return array_map(function ($color) {
      if (is_a($color, \JohnsHopkins\Color\Color::class)) {
        return ['rgb' => $color->rgb, 'hex' => $color->hex, 'brand' => $color->id];
      }
      return (array) $color;
    }, $colors);
  }, $palettes);

  // hex to palette family token
  $tokens = [];
  foreach ($palettes as $paletteName => $colors) {
    $paletteName = strtolower(str_replace(' ', '-', $paletteName));
    foreach ($colors as $grade => $color) {
      if (!in_array($color['hex'], ['ffffff', '000000'])) {
        $tokens["#{$color['hex']}"] = "--jhu-$paletteName-$grade";
      }
    }
  }

  $json = json_encode($palettes);
  file_put_contents(dirname(__DIR__) . '/config/palettes.json', $json);

  $json = json_encode($tokens);
  file_put_contents(dirname(__DIR__) . '/config/hex-to-palette-tokens.json', $json);

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

