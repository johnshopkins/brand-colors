<?php

use JohnsHopkins\Color\Color;
use JohnsHopkins\Color\Colors;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Color::class)]
#[CoversClass(Colors::class)]
class ColorTest extends TestCase
{
  public function testGet(): void
  {
    $colors = json_decode(file_get_contents(dirname(__DIR__) . '/config/web-colors.json'), true);
    $colorObjects = array_map(fn ($data) => new Color($data), $colors['all']);

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
