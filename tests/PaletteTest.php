<?php

namespace php;

use JohnsHopkins\Color\Calculate;
use JohnsHopkins\Color\Grades;
use Logger\Handler\CommandLineHandler;
use Logger\Logger;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Palette::class)]
class PaletteTest extends TestCase
{
  const DEBUG = false;

  protected function getGradesGTE(int $number)
  {
    return array_filter([0, 5, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100], fn ($grade) => $grade >= $number);
  }

  public function setUp(): void
  {
    parent::setUp();

    $grades = new Grades();
    $this->contrasts = $grades->contrasts;
  }

  public function testContrastOfPalettes(): void
  {
    $logger = new Logger(new CommandLineHandler());

    $testsRun = 0;
    $testsFailed = 0;

    $palettes = json_decode(file_get_contents(dirname(__DIR__) . '/config/palettes.json'), true);

    foreach ($palettes as $name => $colors) {

      // add white and black
      $colors[0] = [
        'rgb' => [238, 238, 238],
        'hex' => 'eeeeee'
      ];

      $colors[10] = [
        'rgb' => [0, 0, 0],
        'hex' => '000000'
      ];

      ksort($colors);

      // if (self::DEBUG) {
      //   $logger->info("------------------------------------------");
      //   $logger->info("Testing $name palette");
      //   $logger->info("------------------------------------------");
      // }

      foreach ($colors as $grade => $color) {

        // $logger->info("------------------------------------------");
        // $logger->info("Testing $grade grade");
        // $logger->info("------------------------------------------");

        foreach ($this->contrasts as $gradeDifference => $requiredContrast) {

          // $logger->info("------------------------------------------");
          // $logger->info("Testing $requiredContrast contrast");
          // $logger->info("------------------------------------------");

          $gradesToTest = $this->getGradesGTE($grade + $gradeDifference);

          foreach ($palettes as $n => $c) {

            $testColors = array_intersect_key($c, array_flip($gradesToTest));

            foreach ($testColors as $tg => $tc) {

              // $logger->info("Testing $name-$grade against $n-$tg");

              $contrast = Calculate::contrast($color['rgb'], $tc['rgb']) * 100;

              if (!self::DEBUG) {
                // run phpunit assertion
                $this->assertGreaterThanOrEqual($requiredContrast, $contrast);
              }

              if (self::DEBUG && $contrast < $requiredContrast) {
                // log out specifics about which test failed
                $logger->error("$name-$grade against $n-$tg FAILED. Required: $requiredContrast Actual: $contrast");
                $logger->debug("light: {$tc['hex']}, dark: {$color['hex']}");
                $logger->debug('------');
                $testsFailed++;
              }

              $testsRun++;
            }

          }

        }
      }
    }

    if (self::DEBUG) {
      $logger->info('------');
      $logger->info("Tests run: $testsRun");
      $logger->info("Tests failed: $testsFailed");
    }
  }
}
