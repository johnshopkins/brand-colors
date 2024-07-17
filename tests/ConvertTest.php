<?php

use JohnsHopkins\Color\Convert;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Convert::class)]
class ConvertTest extends TestCase
{
  public function setUp(): void
  {
    parent::setUp();

    $json = file_get_contents(dirname(__DIR__) . '/config/web-colors.json');
    $colors = json_decode($json, true);
    $this->colors = $colors['all'];
  }

  public function testRGB_HEX(): void
  {
    foreach ($this->colors as $color) {
      $this->assertEqualsIgnoringCase($color['hex'], Convert::rgb_hex($color['rgb']));
    }
  }

  public function testHEX_RGB(): void
  {
    foreach ($this->colors as $color) {
      $this->assertEqualsIgnoringCase($color['rgb'], Convert::hex_rgb($color['hex']));
    }
  }

  public function testRGB_HSL(): void
  {
    $this->assertEquals([216, 100, 22.4], Convert::rgb_hsl([0, 45, 114]));
    $this->assertEquals([210, 68.9, 67.3], Convert::rgb_hsl([114, 172, 229]));
    $this->assertEquals([49, 100, 47.3], Convert::rgb_hsl([241, 196, 0]));
    $this->assertEquals([0.0, 100, 47.3], Convert::rgb_hsl([241, 0, 0]));
  }

  public function testHSL_RGB(): void
  {
    $this->assertEquals([0, 46, 114], Convert::hsl_rgb([216, 100, 22.4]));
    $this->assertEquals([114, 172, 229], Convert::hsl_rgb([210, 68.9, 67.3]));
    $this->assertEquals([241, 197, 0], Convert::hsl_rgb([49, 100, 47.3]));
  }
}
