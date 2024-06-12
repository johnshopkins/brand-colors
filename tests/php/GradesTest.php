<?php

namespace php;

use JohnsHopkins\Color\Calculate;
use JohnsHopkins\Color\Grades;
use Logger\Handler\CommandLineHandler;
use Logger\Logger;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Grades::class)]
#[UsesClass(Calculate::class)]
class GradesTest extends TestCase
{
  const DEBUG = false;

  public function testCalculate(): void
  {
    $grades = new Grades(self::DEBUG);

    $expected = [
      0 => [1, 1],
      5 => [0.85, 0.95],
      10 => [0.75, 0.84],
      20 => [0.64, 0.74],
      30 => [0.381, 0.624],
      40 => [0.288, 0.3],
      50 => [0.175, 0.183],
      60 => [0.1, 0.125],
      70 => [.05, 0.093],
      80 => [.026, 0.045],
      90 => [.01, 0.025],
      100 => [0, 0],
    ];

    $this->assertEquals($expected, $grades->bounds);
  }

  public function testRanges(): void
  {
    $logger = new Logger(new CommandLineHandler());

    $grades = new Grades(self::DEBUG);

    $tests = [
      "min->min" =>[0, 0],
      "max->max" => [1, 1],
      "min->max" =>[0, 1],
      "max->min" => [1, 0],
    ];

    $testsRun = 0;
    $testsFailed = 0;

    foreach ($grades->bounds as $grade => $b) {

      // test each grade

      if (self::DEBUG) {
        $logger->info("------------------------------------------");
        $logger->info("Testing $grade grade");
        $logger->info("------------------------------------------");
      }

      foreach ($grades->contrasts as $gradeDifference => $contrast) {

        // with each contrasting grade

        $contrastingGrade = $grade + $gradeDifference;
        if ($contrastingGrade % 10 !== 0) {
          $contrastingGrade += 5;
        }

        if (!isset($grades->bounds[$contrastingGrade])) {
          // over 100 or under 0
          continue;
        }

        if (self::DEBUG) {
          $logger->info("Testing $grade against $contrastingGrade");
          $logger->info("-------------------");
        }

        $contrastingBounds = $grades->bounds[$contrastingGrade];

        foreach ($tests as $name => $testBounds) {

          $l1 = $b[$testBounds[0]];
          $l2 = $contrastingBounds[$testBounds[1]];

          $ratio = $this->getContrastRatio($l1, $l2);

          if (self::DEBUG) {
            $l1test = $testBounds[0] === 0 ? 'L1 minimum' : 'L1 maximum';
            $l2test = $testBounds[1] === 0 ? 'L2 minimum' : 'L2 maximum';
            $logger->info("Test: $l1test ($l1) against $l2test ($l2)");
            $logger->info("Expected: $contrast, Actual: $ratio");
          }

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

          if (self::DEBUG) {
            $logger->info("-");
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

  // public function testFindGradeOfRGB(): void
  // {
  //   $grades = new Grades(self::DEBUG);
  //
  //   // black
  //   $this->assertEquals(100, $grades->findGradeOfRGB([0, 0, 0]));
  //
  //   // heritage blue
  //   $this->assertEquals(80, $grades->findGradeOfRGB([0, 45, 114]));
  //
  //   // secondary green
  //   $this->assertEquals(46, $grades->findGradeOfRGB([0, 155, 119]));
  //
  //   // accent tan
  //   $this->assertEquals(30, $grades->findGradeOfRGB([203, 160, 82]));
  //
  //   // secondary blue
  //   $this->assertEquals(58, $grades->findGradeOfRGB([0, 114, 206]));
  //
  //   // accent orange
  //   $this->assertEquals(31, $grades->findGradeOfRGB([255, 105, 0]));
  //
  //   // white
  //   $this->assertEquals(0, $grades->findGradeOfRGB([255, 255, 255]));
  // }

  protected function getContrastRatio($l1, $l2)
  {
    return round(($l1 + 0.05) / ($l2 + 0.05), 2);
  }
}
