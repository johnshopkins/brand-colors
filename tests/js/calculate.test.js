import { calculate } from '../../assets/js/main';

test('Calculates correct luminance', () => {

  expect(calculate.luminance([0, 0, 0])).toBe(0);
  expect(calculate.luminance([255, 255, 255])).toBe(1);
  expect(calculate.luminance([0, 45, 114])).toBe(0.030916772592892317);
  expect(calculate.luminance([241, 196, 0])).toBe(0.5818062759397356);

});

test('Calculates correct contrast', () => {

  expect(calculate.contrast([255, 255, 255], [0, 45, 114])).toBe(12.98);
  expect(calculate.contrast([255, 255, 255], [241, 196, 0])).toBe(1.66);

});
