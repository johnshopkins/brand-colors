<?php

use JohnsHopkins\Color\Colors;
use JohnsHopkins\Color\Palette;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
require_once 'functions.php';

// show palettes in a table
$mode = 'table';

// // save palettes to config/palettes.json
// $mode = 'json';

$colors = Colors::get();

$palettes = [
  'grayscale' => [
    'colors' => [
      50 => [120, 116, 112],  // get the colors to be more gray (swatch taken from eric's scale and modified to be within range 50)
      90 => $colors[21]->rgb, // sable
    ],
    'settings' => [
      'grayscale' => true,
    ]
  ],
  'red' => [
    'colors' => [
      30 => $colors[10]->rgb, // 486 (tertiary coral)
      70 => $colors[12]->rgb, // 186 (tertiary red)
      80 => $colors[13]->rgb, // 188 (tertiary maroon)
    ]
  ],
  'orange' => [
    'colors' => [
      30 => $colors[2]->rgb,  // 1375 (secondary-orange)
      40 => $colors[7]->rgb,  // 1505 (tertiary-orange)
      50 => $colors[11]->rgb, // 173 (tertiary dark orange)
    ]
  ],
  'gold' => [
    'colors' => [
      30 => $colors[5]->rgb, // 7406 (secondary yellow)
      60 => $colors[8]->rgb, // 7586 (tertiary brown)
    ]
  ],
  'warm green' => [
    'colors' => [
      40 => $colors[20]->rgb, // 7490 (tertiary green)
      70 => $colors[19]->rgb, // 7734 (tertiary dark green)
    ]
  ],
  'cool green' => [
    'colors' => [
      30 => $colors[18]->rgb, // 564 (tertiary seafoam)
      50 => $colors[3]->rgb,  // 3278 (secondary green)
    ]
  ],
  'blue' => [
    'colors' => [
      30 => $colors[1]->rgb,  // 284 (spirit blue)
      40 => $colors[17]->rgb, // 279 (tertiary blue)
      50 =>  $colors[4]->rgb, // 285 (secondary blue)
      80 => $colors[0]->rgb,  // 288 (heritage blue)
    ]
  ],
  'cool purple' => [
    'colors' => [
      40 => $colors[16]->rgb, // 666 (tertiary lavender)
    ]
  ],
  'warm purple' => [
    'colors' => [
      50 => $colors[15]->rgb, // 7655 (tertiary purple)
      80 => $colors[14]->rgb, // 262 (tertiary dark purple)
    ]
  ],
  'neutral' => [
    'colors' => [
      30 => $colors[6]->rgb, // 7407 (tertiary tan)
      80 => $colors[9]->rgb, // 4625 (tertiary dark brown)
    ]
  ]
];

$palettes = array_map(fn ($knownPalette) => (new Palette())->create($knownPalette), $palettes);

if ($mode === 'json') {

  $json = json_encode($palettes);
  file_put_contents(dirname(__DIR__, 2) . '/config/palettes.json', $json);
  exec('npm run format-json');

} else if ($mode === 'table') {
  printTable($palettes);
}

