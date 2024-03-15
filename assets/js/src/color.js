import { getByID } from './colors.js';

const removeKey = (key, {[key]: _, ...rest}) => rest;

class Color {

  id;
  name;
  slug;
  type;
  pms;
  cmyk;
  rgb;
  hex;
  luminance;
  contrast;

  constructor(data) {
    for (let key in data) {
      this[key] = data[key]
    }
  }
  /**
   * Find the best contrasting color (White or Sable) for this color.
   * @param useDoubleBlack Use Double Black instead of Sable
   * @returns object
   */
  getContrastingColor(useDoubleBlack = false) {

    const ids = {
      sable: 22,
      white: 23,
      'double-black': 24
    };

    const contrast = removeKey(
      !useDoubleBlack ? 'double-black' : 'sable',
      this.contrast
    );

    // sort highest to lowest value
    const sorted = Object.keys(contrast).sort((a, b) => contrast[b] - contrast[a]);

    // return highest value color
    return getByID(ids[sorted[0]]);

  }
}

export default Color
