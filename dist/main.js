const c = (t) => (t = t / 255, t <= 0.03928 ? t / 12.92 : ((t + 0.055) / 1.055) ** 2.4), r = (t) => {
  const n = c(t[0]), s = c(t[1]), o = c(t[2]);
  return 0.2126 * n + 0.7152 * s + 0.0722 * o;
}, e = (t, n) => {
  const s = r(t) + 0.05, o = r(n) + 0.05;
  return parseFloat((s / o).toFixed(2));
};
export {
  e as contrast,
  r as luminance
};
