<?php

namespace JohnsHopkins\Color;

class Color
{
  public int $id;
  public string $name;
  public string $slug;
  public string $type;
  public string|null $pms;
  public array $cmyk;
  public array $rgb;
  public string $hex;
  public string $grade;
  public string $luminance;
  public array $contrast;
  public string $aa_contrast;

  public function __construct($data)
  {
    foreach($data as $key => $value){
      $this->{$key} = $value;
    }
  }

  /**
   * Find the best contrasting color (White or Sable) for this color.
   * @param $useDoubleBlack Use Double Black instead of Sable
   * @return Color
   */
  public function getContrastingColor($useDoubleBlack = false): Color
  {
    $ids = [
      'sable' => 22,
      'white' => 23,
      'double-black' => 24,
    ];

    $contrast = $this->contrast;

    if (!$useDoubleBlack) {
      unset($contrast['double-black']);
    } else {
      unset($contrast['sable']);
    }

    // sort highest to lowest value
    arsort($contrast);
    $keys = array_keys($contrast);

    // return highest value color
    return Colors::getByID($ids[$keys[0]]);
  }
}
