<?php

namespace JohnsHopkins\Color;

use cli\Table;
use Logger\Handler\CommandLineHandler;
use Logger\Logger;

/**
 * Tool used to
 * - generate our color grades
 * - determine the grade in which any given RGB value fits
 * - shift a given RGB color to fit into a grade
 */
class Grades
{
  protected Logger $logger;

  /**
   * Starting bounds, where we only know white and black
   * @var array
   */
  public array $bounds = [
    0 => [1, 1], // white
    5 => [null, null],
    10 => [null, null],
    20 => [null, null],
    30 => [null, null],
    40 => [null, null],
    50 => [null, null],
    60 => [null, null],
    70 => [null, null],
    80 => [null, null],
    90 => [null, null],
    100 => [0, 0] // black
  ];

  /**
   * Grade difference => min required contrasts
   * @var array
   */
  public array $contrasts = [
    40 => 3,
    50 => 4.5,
    70 => 7,
  ];

  public function __construct(protected bool $debug = false)
  {
    $this->logger = new Logger(new CommandLineHandler());
    $this->calculate();
  }

  /**
   * Find the grade of a given RGB color
   * @param array $rgb [r, b, g] ex: [0, 45, 114]
   * @return int
   */
  public function findGradeOfRGB(array $rgb): int
  {
    $luminance = round(Calculate::luminance($rgb), 3);

    $prevMax = null;

    foreach ($this->bounds as $grade => $bound) {

      if ($luminance >= $bound[0] && $luminance <= $bound[1]) {
        return $grade;
      }

      if ($prevMax && $luminance < $prevMax && $luminance > $bound[0]) {

        // between this grade and previous grade

        $previousGrade = $grade - 10;

        // difference between luminance 1-9 grades in-between grades
        // ex: difference between max luminance of grade 50 and minimum luminamce of grade 60 to get grades 51, 52, 53, etc...
        $betweenGradesDiff = ($prevMax - $bound[0]) / 10;

        // will hold the found "between grade" of this color. ex: 43
        $betweenGrade = null;

        // initial lower bound (between grade 1)
        $midGradeMin = $bound[0];

        for ($i = 1; $i <= 10; $i++) {

          // grade $i upperbound
          $midGradeMax = $midGradeMin + $betweenGradesDiff;

          if ($luminance <= $midGradeMax) {

            // figure out if it's closer to the lower or upper luminance bound
            // this will determine if the color is say, 43 or 44
            if ($i === 1 || $i === 9) {
              // can't go to multiple of 10 grade, return this one (41, 49, etc...)
              $betweenGrade = $previousGrade + $i;
            } else {
              // get the right in-between grade based on difference between lower and upper max
              $diffFromLowerGrade = $luminance - $midGradeMin;
              $diffFromHigherGrade = $midGradeMax - $luminance;
              $betweenGrade = $diffFromLowerGrade <= $diffFromHigherGrade ? $previousGrade + ($i - 1) : $previousGrade + $i;
            }
          }

          // this midgrade maximum is now next midgrade's minimum
          $midGradeMin = $midGradeMax;

          if ($betweenGrade) {
            break;
          }
        }

        return $betweenGrade;
      }

      $prevMax = $bound[1];
    }
  }

  /**
   * Shift an RGB value to a grade
   * @param array $rgb      Array of RGB values ex: [0, 45, 114]
   * @param int|null $grade Specific grade to shift the RGB color to
   * @param bool $opposite  By default, the RGB color will be shifted to the
   *                        closest rade. To shift to the farthest grade, pass TRUE.
   * @return array          Array of RGB values ex: [0, 45, 114]
   */
  public function shiftRGBtoGrade(array $rgb, int $grade = null, bool $opposite = false): array
  {
    $currentGrade = $this->findGradeOfRGB($rgb);

    if ($grade) {
      $roundToGrade = $grade;
    } else {
      $roundToGrade = (round($currentGrade / 10) * 10);
    }

    if ($grade === null && $opposite) {
      if ($currentGrade > $roundToGrade) {
        $roundToGrade+= 10;
      } else {
        $roundToGrade-= 10;
      }
    }

    $roundTo = $this->bounds[$roundToGrade];

    $colors = $this->getGradeColor($rgb, ...$roundTo);
    $direction = $currentGrade > $roundToGrade ? 'down' : 'up';

    return [
      'grade' => $roundToGrade,
      'color' => $direction === 'up' ? $colors['max'] : $colors['min'],
    ];
  }

  /**
   * Creates three color swatches (min, mid, max) for a given RGB color value
   * based on mininum and maximum luminance of the grade
   * @param array $startingRGB RBG color to base new colors from. Value is an array of RGB values ex: [0, 45, 114]
   * @param float $min         Minimum luminance required by the grade
   * @param float $max         Maximum luminance required by the grade
   * @return array
   */
  function getGradeColor(array $startingRGB, $min, $max): array
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

    // $colors = [];

    while ($direction === 'up' ? $luminance < $max : $luminance > $min) {

      // increase or decrease lightness value in HSL
      $direction === 'up' ? $hsl[2]++ : $hsl[2]--;

      // convert new HSL to RGB and get luminance
      $rgb = Convert::hsl_rgb($hsl);
      $luminance = round(Calculate::luminance($rgb), 3);

      // $colors[] = [$rgb, $luminance];

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
      'max' => $foundMaxColor,
      // 'all' => $colors,
    ];
  }

  protected function log($level, $message)
  {
    if ($this->debug) {
      $this->logger->$level($message);
    }
  }

  /**
   * Determine luminance grades
   * @return void
   */
  protected function calculate(): void
  {
    // calculate the grades we can initally figure out based on our contrast/grade rules
    // and the colors we already know (white and black)
    for ($i = 0; $i < 2; $i++) {
      foreach ($this->bounds as $grade => $bound) {

        // we'll manually create grade 5
        if ($grade === 5) continue;

        $this->evaluate($grade, 'up', true);
        $this->evaluate($grade, 'down', true);
      }
    }

    $this->roundBounds();

    // add in missing contraints based on other bounds:
    // * if missing the min in one row, make it the (0.001 more) max of the row BELOW
    // * if missing the max in one row, make it the (0.001 less) min of the row ABOVE
    foreach ($this->bounds as $grade => $bound) {

      // we'll manually create grade 5
      if ($grade === 5) {
        continue;
      }

      // make sure no maxes are the same as prev grade mins
      if (isset($this->bounds[$grade - 10]) && $bound[1] === $this->bounds[$grade - 10][0]) {
        $this->bounds[$grade][1] = $bound[1] - .001;
      }

      if ($bound[0] === null) {
        $this->bounds[$grade][0] = round($this->bounds[$grade + 10][1] + .001, 3);
      } else if ($bound[1] === null) {
        $this->bounds[$grade][1] = round($this->bounds[$grade - 10][0] - .001, 3);
      }
    }

    $this->roundBounds();


    // manual adjustments

    // get spirit blue (0.39) within grade 30
    $this->bounds[30][0] = .387;
    $this->bounds[70][1] = .095;
    $this->bounds[80][1] = .047;

    // get tertiary maroon within grade 70
    $this->bounds[70][0] = .05;

    // adjust grade 60 to be less tight
    $this->bounds[10][0] = .750;
    $this->bounds[20][0] = .640;
    $this->bounds[20][1] = .740;
    $this->bounds[60][1] = .125;

    // adjust grade 10 max less close to white
    $this->bounds[10][1] = .950;

    // adjust grade 90 max less close to black
    $this->bounds[90][0] = .010;

    // // create level 5
    $this->bounds[5][0] = 0.85;
    $this->bounds[5][1] = 0.95;
    $this->bounds[10][1] = 0.84;
  }

  protected function evaluate(int $currentGrade, string $direction = 'up'): void
  {
    $startLum = $direction === 'up' ? $this->bounds[$currentGrade][0] : $this->bounds[$currentGrade][1];

    if ($startLum === null) return;

    foreach ($this->contrasts as $gradeInt => $minContrast) {

      $newGrade = $direction === 'up' ? $currentGrade + $gradeInt : $currentGrade - $gradeInt;

      if ($newGrade >= 100 || $newGrade <= 0) {
        continue;
      }

      $newLum = $direction === 'up' ?
        $this->l2($startLum, $minContrast) :
        $this->l1($startLum, $minContrast);

      $i = $direction === 'up' ? 1 : 0;

      $roundMethod = $direction === 'up' ? 'min' : 'max';

      $currentLum = $this->bounds[$newGrade][$i];

      $this->bounds[$newGrade][$i] = $currentLum === null ?
        $newLum :
        $roundMethod($currentLum, $newLum);
    }
  }

  /**
   * Not technically rounding, which can be too much to still meet contrast
   * guidelines. Instead, just chop off anything after 3 decimal places.
   *
   * Numbers like 0.18333333333333 and 0.10555555555556 are truncated
   * vs rounded to prevent inaccuracies in the luminance bounds.
   *
   * Example: 0.10555555555556 usually rounds to 0.106 (grade 60 max),
   * but that is too high to meet contrast rules.
   *
   * @return void
   */
  protected function roundBounds(): void
  {
    // detects numbers like 0.18333333333333 and 0.10555555555556
    $repeatingDecimal = '/.\d{2}(\d)\1{10,}/';

    $this->bounds = array_map(function ($bounds) use ($repeatingDecimal) {

      // round min up
      if ($bounds[0] !== null) {
        if (preg_match($repeatingDecimal, $bounds[0])) {
          // repeating decimal; truncate
          $bounds[0] = ceil($bounds[0] * 1000) / 1000;
        } else {
          $bounds[0] = round($bounds[0], 3, PHP_ROUND_HALF_UP);
        }
      }

      // round max down
      if ($bounds[1] !== null) {
        if (preg_match($repeatingDecimal, $bounds[1])) {
          // reoeating decimal; truncate
          $bounds[1] = floor($bounds[1] * 1000) / 1000;
        } else {
          $bounds[1] = round($bounds[1], 3, PHP_ROUND_HALF_DOWN);
        }
      }

      return $bounds;

    }, $this->bounds);
  }

  /**
   * Get L1 based on C and L2
   * @param $l2
   * @param $c
   * @return float
   */
  /**
   * Get L1 based on C and L2
   * @param float $l2 Luminance of the darker color
   * @param float $c  Required contrast
   * @return float
   */
  protected function l1(float $l2, float $c = 4.5)
  {
    return ($c * $l2) + (0.05 * $c) - 0.05;
  }

  /**
   * Get L2 based on C and L1
   * @param $l1
   * @param $c
   * @return float
   */

  /**
   * Get L2 based on C and L1
   * @param float $l1 Luminance of the lighter color
   * @param float $c  Required contrast
   * @return float
   */
  protected function l2(float $l1, float $c = 4.5)
  {
    return (($l1 + 0.05) / $c) - 0.05;
  }

  public function printTable(bool $withDiff = false): void
  {
    $rows = array_map(function ($bound, $grade) use ($withDiff) {

      // get rid of null values (str_replace(): Passing null to parameter #3 ($subject) of type array|string is deprecated)
      $bound = array_map(fn ($b) => is_null($b) ? '' : $b, $bound);

      // add grade as first item
      array_unshift($bound, $grade);

      if ($withDiff) {
        // find difference between min/max
        $bound[] = ($bound[1] !== '' && $bound[2] !== '') ? $bound[2] - $bound[1] : '';
      }

      return $bound;
    }, $this->bounds, array_keys($this->bounds));

    $headers = ['Grade', 'Min', 'Max'];
    if ($withDiff) {
      $headers[] = 'Diff';
    }

    $table = new Table($headers, $rows);
    $table->display();

  }
}
