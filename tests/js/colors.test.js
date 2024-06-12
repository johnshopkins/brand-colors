import { colors } from '../../assets/js/main';
import colorsJson from '../../config/web-colors.json';
import Color from "../../assets/js/src/color.js";

// console.log(colorsJson)

test('get()', () => {

  expect(colors.get()).toStrictEqual(colorsJson.map(color => new Color(color)));

});

test('getById()', () => {

  expect(colors.getByID(10).pms).toStrictEqual('4625 C');
  expect(colors.getByID(1).pms).toStrictEqual('288 C');
  expect(colors.getByID(25)).toBeNull();

});

test('getContrastingColor()', () => {

  expect(colors.getByID(10).getContrastingColor()).toStrictEqual(colors.getByID(23));
  expect(colors.getByID(1).getContrastingColor()).toStrictEqual(colors.getByID(23));

  expect(colors.getByID(6).getContrastingColor()).toStrictEqual(colors.getByID(22));
  expect(colors.getByID(6).getContrastingColor(true)).toStrictEqual(colors.getByID(24));

});
