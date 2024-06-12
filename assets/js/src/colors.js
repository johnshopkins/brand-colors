import colorsData from '../../../config/web-colors.json';
import Color from './color';

/**
 * Get all brand colors
 * @returns array of Color objects
 */
const get = () => {
  return colorsData.map(color => {
    return new Color(color);
  });
};

/**
 * Get a brand color by its ID
 * @param id
 * @returns Color object
 */
const getByID = (id) => {
  const colors = get();
  return colors[id - 1] || null;
};

export { get, getByID };
