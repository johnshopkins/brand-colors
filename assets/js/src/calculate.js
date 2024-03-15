const sRGB = (value) => {
  value = value / 255;

  if (value <= 0.03928) {
    return value / 12.92;
  }

  return ((value + 0.055) / 1.055) ** 2.4;
};

const luminance = (rgb) => {

  const sR = sRGB(rgb[0]);
  const sG = sRGB(rgb[1]);
  const sB = sRGB(rgb[2]);

  return ((0.2126 * sR) + (0.7152 * sG) + (0.0722 * sB));
};

/**
 * Calculate the contrast ratio between two colors
 * Uses the forumla provided by W3C: https://www.w3.org/TR/WCAG20-TECHS/G17.html#G17-procedure
 * @param lightRGB array Array of RGB values for the ligher color ex: [0, 45, 114]
 * @param darkRGB array  Array of RGB values for the darker color ex: [0, 45, 114]
 * @returns {number}
 */
const contrast = (lightRGB, darkRGB) => {
  const l1 = luminance(lightRGB) + 0.05;
  const l2 = luminance(darkRGB) + 0.05;

  return parseFloat((l1 / l2).toFixed(2));
};

export { contrast, luminance };
