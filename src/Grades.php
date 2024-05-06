<?php

namespace JohnsHopkins\Color;

use cli\Table;
use Logger\Handler\CommandLineHandler;
use Logger\Logger;

class Grades
{
  protected Logger $logger;

  /**
   * Starting bounds, where we only know white and black
   * @var array
   */
  public array $bounds = [
    0 => [1, 1], // white
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
  }

  protected function log($level, $message)
  {
    if ($this->debug) {
      $this->logger->$level($message);
    }
  }

  public function calculate()
  {
    // calculate and grades we can initally based on our contrast/grade rules
    for ($i = 0; $i < 2; $i++) {
      foreach ($this->bounds as $grade => $bound) {
        $this->evaluate($grade, 'up', true);
        $this->evaluate($grade, 'down', true);
      }
    }

    $this->roundBounds();

    // add in missing contraints based on other bounds:
    // * if missing the min in one row, make it the (0.001 more) max of the row BELOW
    // * if missing the max in one row, make it the (0.001 less) min of the row ABOVE
    foreach ($this->bounds as $grade => $bound) {

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

    // // run it one more time now that no constraints are missing
    // // nothing changes here
    // foreach ($this->bounds as $grade => $bound) {
    //   $this->evaluate($grade, 'up');
    //   $this->evaluate($grade, 'down');
    // }

    $this->roundBounds();


    // manual adjustments

    // get spirit blue (0.39) within grade 30
    $this->bounds[30][0] = .390;
    $this->bounds[70][1] = .095;
    $this->bounds[80][1] = .047;

    // adjust grade 60 to be less tight
    $this->bounds[10][0] = .750;
    $this->bounds[20][0] = .640;
    $this->bounds[20][1] = .740;
    $this->bounds[60][1] = .125;

    // adjust grade 10 max less close to white
    $this->bounds[10][1] = .950;

    // adjust grade 90 max less close to black
    $this->bounds[90][0] = .010;
  }

  public function evaluate(int $currentGrade, string $direction = 'up', bool $debug = false)
  {
    if ($debug) $this->log('debug', "--------------------");
    if ($debug) $this->log('debug', "GRADE: $currentGrade -- $direction");
    if ($debug) $this->log('debug', "--------------------");

    $startLum = $direction === 'up' ? $this->bounds[$currentGrade][0] : $this->bounds[$currentGrade][1];

    if ($startLum === null) return;

    foreach ($this->contrasts as $gradeInt => $minContrast) {

      if ($debug) $this->log('debug', "Contrast test: $minContrast");

      $newGrade = $direction === 'up' ? $currentGrade + $gradeInt : $currentGrade - $gradeInt;

      if ($newGrade >= 100 || $newGrade <= 0) continue;

      if ($debug) $this->log('debug', "New grade: $newGrade");

      $newLum = $direction === 'up' ?
        $this->l2($startLum, $minContrast) :
        $this->l1($startLum, $minContrast);

      if ($debug) $this->log('debug',  "New lum for grade $newGrade: $newLum");


      $i = $direction === 'up' ? 1 : 0;

      $roundMethod = $direction === 'up' ? 'min' : 'max';

      if (isset($this->contrastsChecked[$newGrade]) && !in_array($minContrast, $this->contrastsChecked[$newGrade][$i])) {
        $this->contrastsChecked[$newGrade][$i][] = $minContrast;
      }

      if (!isset($this->bounds[$newGrade])) {
        $this->bounds[$newGrade] = [null, null];
      }

      $currentLum = $this->bounds[$newGrade][$i];

      $this->bounds[$newGrade][$i] = $currentLum === null ?
        $newLum :
        $roundMethod($currentLum, $newLum);

      if ($debug) $this->log('debug', "--------");
    }

    if ($debug) $this->log('debug', "--------");
  }

  /**
   * No rounding, which can be too much to still meet contrast guidelines.
   * Instead, just chop off anything after 3 decimal places.
   * @return void
   */
  protected function roundBounds(): void
  {
    // detects numbers like 0.18333333333333 and 0.10555555555556
    // truncate vs rounding these numbers to prevent inaccuracies
    // ex: 0.10555555555556 usually rounds to 0.106 (grade 60 max),
    // but that is too high to meet contrast rules.
    $repeatingDecimal = '/.\d{2}(\d)\1{10,}/';

    $this->bounds = array_map(function ($bounds) use ($repeatingDecimal) {

      // round min up
      if ($bounds[0] !== null) {
        if (preg_match($repeatingDecimal, $bounds[0])) {
          // reoeating decimal; truncate
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
  public function l1(float $l2, float $c = 4.5)
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
  public function l2(float $l1, float $c = 4.5)
  {
    return (($l1 + 0.05) / $c) - 0.05;
  }

  public function printTable(bool $withDiff = false)
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
