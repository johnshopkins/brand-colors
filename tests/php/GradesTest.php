<?php

namespace php;

use JohnsHopkins\Color\Grades;
use Logger\Handler\CommandLineHandler;
use Logger\Logger;
use PHPUnit\Framework\TestCase;

class GradesTest extends TestCase
{
  const DEBUG = false;

  public function testCalculate(): void
  {
    $grades = new Grades(false);
    $grades->calculate();

    $expected = [
      0 => [1, 1],
      10 => [0.75, 0.95],
      20 => [0.64, 0.74],
      30 => [0.39, 0.624],
      40 => [0.288, 0.3],
      50 => [0.175, 0.183],
      60 => [0.1, 0.125],
      70 => [.05, 0.095],
      80 => [.026, 0.047],
      90 => [.01, 0.025],
      100 => [0, 0],
    ];

    $this->assertEquals($expected, $grades->bounds);
  }

  public function testRanges(): void
  {
    $logger = new Logger(new CommandLineHandler());

    $grades = new Grades(false);
    $grades->calculate();

    $tests = [
      "min->min" =>[0, 0],
      "max->max" => [1, 1],
      "min->max" =>[0, 1],
      "max->min" => [1, 0],
    ];

    $testsRun = 0;
    $testsFailed = 0;

    // // USWDS
    // $grades->bounds = [
    //   10 => [0.750,0.820],
    //   20 => [0.500,0.650],
    //   30 => [0.350,0.450],
    //   40 => [0.225,0.300],
    //   50 => [0.175,0.183],
    //   60 => [0.100,0.125],
    //   70 => [0.050,0.070],
    //   80 => [0.020,0.040],
    //   90 => [0.005,0.015],
    // ];

    foreach ($grades->bounds as $grade => $b) {

      // test each grade

      foreach ($grades->contrasts as $gradeDifference => $contrast) {

        // with each contrasting grade

        $contrastingGrade = $grade + $gradeDifference;

        if (!isset($grades->bounds[$contrastingGrade])) {
          // over 100 or under 0
          continue;
        }

        $contrastingBounds = $grades->bounds[$grade + $gradeDifference];

        foreach ($tests as $name => $testBounds) {

          $l1 = $b[$testBounds[0]];
          $l2 = $contrastingBounds[$testBounds[1]];

          $ratio = $this->getContrastRatio($l1, $l2);

          if (!self::DEBUG) {
            // run phpunit assertion
            $this->assertGreaterThanOrEqual($contrast, $ratio);
          }

          if (self::DEBUG && $ratio < $contrast) {
            // log out specifics about which test failed
            $logger->error("{$grade}->$contrastingGrade $name FAILED. Required: $contrast Actual: $ratio");
            $logger->debug("L1: $l1, L2: $l2");
            $logger->debug('------');
            $testsFailed++;
          }

          $testsRun++;

        }
      }
    }

    if (self::DEBUG) {
      $logger->info('------');
      $logger->info("Tests run: $testsRun");
      $logger->info("Tests failed: $testsFailed");
    }

  }

  protected function getContrastRatio($l1, $l2)
  {
    return round(($l1 + 0.05) / ($l2 + 0.05), 2);
  }
}
