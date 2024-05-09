<?php

use JohnsHopkins\Color\Calculate;
use PHPUnit\Framework\TestCase;

class CalculateTest extends TestCase
{
  public function testLuminance(): void
  {
    $this->assertEquals(0, Calculate::luminance([0, 0, 0]));
    $this->assertEquals(1, Calculate::luminance([255, 255, 255]));

    $this->assertEquals(0.030916772592892317, Calculate::luminance([0, 45, 114]));
    $this->assertEquals(0.5818062759397356, Calculate::luminance([241, 196, 0]));
  }

  public function testContrast(): void
  {
    $this->assertEquals(21, Calculate::contrast([255, 255, 255], [0, 0, 0]));
    $this->assertEquals(12.98, Calculate::contrast([255, 255, 255], [0, 45, 114]));
    $this->assertEquals(1.66, Calculate::contrast([255, 255, 255], [241, 196, 0]));
  }
}
