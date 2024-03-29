<?php

use JohnsHopkins\Color\Color;
use JohnsHopkins\Color\Colors;
use PHPUnit\Framework\TestCase;

class ColorTest extends TestCase
{
  public function testGet(): void
  {
    $colors = json_decode(file_get_contents(dirname(__DIR__, 2) . '/config/colors.json'), true);
    $colorObjects = array_map(fn ($data) => new Color($data), $colors);

    $this->assertEquals(Colors::get(), $colorObjects);
  }

  public function testGetByID(): void
  {
    $this->assertEquals('4625 C', Colors::getByID(10)->pms);
    $this->assertEquals('288 C', Colors::getByID(1)->pms);
    $this->assertNull(Colors::getByID(25));
  }

  public function testGetContrastingColor(): void
  {
    $this->assertEquals(Colors::getByID(23), Colors::getByID(10)->getContrastingColor());
    $this->assertEquals(Colors::getByID(23), Colors::getByID(1)->getContrastingColor());

    $this->assertEquals(Colors::getByID(22), Colors::getByID(6)->getContrastingColor());
    $this->assertEquals(Colors::getByID(24), Colors::getByID(6)->getContrastingColor(true));
  }
}
