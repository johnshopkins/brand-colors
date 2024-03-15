var m = Object.defineProperty;
var u = (e, t, r) => t in e ? m(e, t, { enumerable: !0, configurable: !0, writable: !0, value: r }) : e[t] = r;
var a = (e, t, r) => (u(e, typeof t != "symbol" ? t + "" : t, r), r);
const n = (e) => (e = e / 255, e <= 0.03928 ? e / 12.92 : ((e + 0.055) / 1.055) ** 2.4), c = (e) => {
  const t = n(e[0]), r = n(e[1]), l = n(e[2]);
  return 0.2126 * t + 0.7152 * r + 0.0722 * l;
}, d = (e, t) => {
  const r = c(e) + 0.05, l = c(t) + 0.05;
  return parseFloat((r / l).toFixed(2));
}, C = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  contrast: d,
  luminance: c
}, Symbol.toStringTag, { value: "Module" })), g = [
  {
    id: 1,
    name: "Heritage Blue",
    slug: "heritage-blue",
    type: "primary",
    pms: "288 C",
    cmyk: [
      100,
      80,
      6,
      32
    ],
    rgb: [
      0,
      45,
      114
    ],
    hex: "002D72",
    luminance: 3.09,
    contrast: {
      "double-black": 1.62,
      sable: 1.14,
      white: 12.98
    }
  },
  {
    id: 2,
    name: "Spirit Blue",
    slug: "spirit-blue",
    type: "primary",
    pms: "284 C",
    cmyk: [
      56,
      18,
      0,
      0
    ],
    rgb: [
      114,
      172,
      229
    ],
    hex: "68ACE5",
    luminance: 38.74,
    contrast: {
      "double-black": 8.75,
      sable: 6.14,
      white: 2.4
    }
  },
  {
    id: 3,
    name: "Secondary Orange",
    slug: "secondary-orange",
    type: "secondary",
    pms: "1375 C",
    cmyk: [
      0,
      45,
      94,
      0
    ],
    rgb: [
      255,
      158,
      27
    ],
    hex: "FF9E1B",
    luminance: 45.79,
    contrast: {
      "double-black": 10.16,
      sable: 7.13,
      white: 2.07
    }
  },
  {
    id: 4,
    name: "Secondary Green",
    slug: "secondary-green",
    type: "secondary",
    pms: "3278 C",
    cmyk: [
      99,
      0,
      69,
      0
    ],
    rgb: [
      0,
      155,
      119
    ],
    hex: "009B77",
    luminance: 24.77,
    contrast: {
      "double-black": 5.95,
      sable: 4.18,
      white: 3.53
    }
  },
  {
    id: 5,
    name: "Secondary Blue",
    slug: "secondary-blue",
    type: "secondary",
    pms: "285 C",
    cmyk: [
      90,
      48,
      0,
      0
    ],
    rgb: [
      0,
      114,
      206
    ],
    hex: "0072CE",
    luminance: 16.49,
    contrast: {
      "double-black": 4.3,
      sable: 3.02,
      white: 4.89
    }
  },
  {
    id: 6,
    name: "Secondary Yellow",
    slug: "secondary-yellow",
    type: "secondary",
    pms: "7406 C",
    cmyk: [
      0,
      20,
      100,
      2
    ],
    rgb: [
      241,
      196,
      0
    ],
    hex: "F1C400",
    luminance: 58.18,
    contrast: {
      "double-black": 12.64,
      sable: 8.86,
      white: 1.66
    }
  },
  {
    id: 7,
    name: "Tertiary Tan",
    slug: "tertiary-tan",
    type: "tertiary",
    pms: "7407 C",
    cmyk: [
      6,
      36,
      79,
      12
    ],
    rgb: [
      203,
      160,
      82
    ],
    hex: "CBA052",
    luminance: 38.45,
    contrast: {
      "double-black": 8.69,
      sable: 6.1,
      white: 2.42
    }
  },
  {
    id: 8,
    name: "Tertiary Orange",
    slug: "tertiary-orange",
    type: "tertiary",
    pms: "1505 C",
    cmyk: [
      0,
      56,
      90,
      0
    ],
    rgb: [
      255,
      105,
      0
    ],
    hex: "FF6900",
    luminance: 31.36,
    contrast: {
      "double-black": 7.27,
      sable: 5.1,
      white: 2.89
    }
  },
  {
    id: 9,
    name: "Tertiary Brown",
    slug: "tertiary-brown",
    type: "tertiary",
    pms: "7586 C",
    cmyk: [
      0,
      69,
      89,
      41
    ],
    rgb: [
      158,
      83,
      48
    ],
    hex: "9E5330",
    luminance: 13.67,
    contrast: {
      "double-black": 3.73,
      sable: 2.62,
      white: 5.62
    }
  },
  {
    id: 10,
    name: "Tertiary Dark Brown",
    slug: "tertiary-dark-brown",
    type: "tertiary",
    pms: "4625 C",
    cmyk: [
      30,
      72,
      74,
      80
    ],
    rgb: [
      79,
      44,
      29
    ],
    hex: "4F2C1D",
    luminance: 3.55,
    contrast: {
      "double-black": 1.71,
      sable: 1.2,
      white: 12.28
    }
  },
  {
    id: 11,
    name: "Tertiary Coral",
    slug: "tertiary-coral",
    type: "tertiary",
    pms: "486 C",
    cmyk: [
      0,
      55,
      50,
      0
    ],
    rgb: [
      232,
      146,
      124
    ],
    hex: "E8927C",
    luminance: 39.17,
    contrast: {
      "double-black": 8.83,
      sable: 6.2,
      white: 2.38
    }
  },
  {
    id: 12,
    name: "Tertiary Dark Orange",
    slug: "tertiary-dark-orange",
    type: "tertiary",
    pms: "173 C",
    cmyk: [
      0,
      82,
      94,
      2
    ],
    rgb: [
      207,
      69,
      32
    ],
    hex: "CF4520",
    luminance: 17.63,
    contrast: {
      "double-black": 4.53,
      sable: 3.17,
      white: 4.64
    }
  },
  {
    id: 13,
    name: "Tertiary Red",
    slug: "tertiary-red",
    type: "tertiary",
    pms: "187 C",
    cmyk: [
      7,
      100,
      82,
      26
    ],
    rgb: [
      166,
      25,
      46
    ],
    hex: "A6192E",
    luminance: 9,
    contrast: {
      "double-black": 2.8,
      sable: 1.96,
      white: 7.5
    }
  },
  {
    id: 14,
    name: "Tertiary Maroon",
    slug: "tertiary-maroon",
    type: "tertiary",
    pms: "188 C",
    cmyk: [
      16,
      100,
      65,
      58
    ],
    rgb: [
      118,
      35,
      47
    ],
    hex: "76232F",
    luminance: 5.26,
    contrast: {
      "double-black": 2.05,
      sable: 1.44,
      white: 10.24
    }
  },
  {
    id: 15,
    name: "Tertiary Dark Purple",
    slug: "tertiary-dark-purple",
    type: "tertiary",
    pms: "262 C",
    cmyk: [
      58,
      92,
      12,
      54
    ],
    rgb: [
      81,
      40,
      79
    ],
    hex: "51284F",
    luminance: 3.83,
    contrast: {
      "double-black": 1.77,
      sable: 1.24,
      white: 11.89
    }
  },
  {
    id: 16,
    name: "Tertiary Purple",
    slug: "tertiary-purple",
    type: "tertiary",
    pms: "7655 C",
    cmyk: [
      33,
      72,
      0,
      0
    ],
    rgb: [
      161,
      90,
      149
    ],
    hex: "A15A95",
    luminance: 17.06,
    contrast: {
      "double-black": 4.41,
      sable: 3.09,
      white: 4.76
    }
  },
  {
    id: 17,
    name: "Tertiary Lavender",
    slug: "tertiary-lavender",
    type: "tertiary",
    pms: "666 C",
    cmyk: [
      36,
      39,
      2,
      5
    ],
    rgb: [
      161,
      146,
      178
    ],
    hex: "A192B2",
    luminance: 31.35,
    contrast: {
      "double-black": 7.27,
      sable: 5.1,
      white: 2.89
    }
  },
  {
    id: 18,
    name: "Tertiary Blue",
    slug: "tertiary-blue",
    type: "tertiary",
    pms: "279 C",
    cmyk: [
      68,
      34,
      0,
      0
    ],
    rgb: [
      65,
      143,
      222
    ],
    hex: "418FDE",
    luminance: 26.04,
    contrast: {
      "double-black": 6.21,
      sable: 4.36,
      white: 3.38
    }
  },
  {
    id: 19,
    name: "Tertiary Seafoam",
    slug: "tertiary-seafoam",
    type: "tertiary",
    pms: "564 C",
    cmyk: [
      43,
      0,
      23,
      0
    ],
    rgb: [
      134,
      200,
      188
    ],
    hex: "86C8BC",
    luminance: 50.01,
    contrast: {
      "double-black": 11,
      sable: 7.72,
      white: 1.91
    }
  },
  {
    id: 20,
    name: "Tertiary Dark Green",
    slug: "tertiary-dark-green",
    type: "tertiary",
    pms: "7734 C",
    cmyk: [
      77,
      0,
      82,
      65
    ],
    rgb: [
      40,
      97,
      64
    ],
    hex: "286140",
    luminance: 9.37,
    contrast: {
      "double-black": 2.87,
      sable: 2.02,
      white: 7.31
    }
  },
  {
    id: 21,
    name: "Tertiary Green",
    slug: "tertiary-green",
    type: "tertiary",
    pms: "7490 C",
    cmyk: [
      57,
      6,
      92,
      19
    ],
    rgb: [
      113,
      153,
      73
    ],
    hex: "719949",
    luminance: 26.77,
    contrast: {
      "double-black": 6.35,
      sable: 4.46,
      white: 3.3
    }
  },
  {
    id: 22,
    name: "Sable",
    slug: "sable",
    type: "grayscale",
    pms: "Black 4 C",
    cmyk: [
      41,
      57,
      72,
      90
    ],
    rgb: [
      49,
      38,
      29
    ],
    hex: "31261D",
    luminance: 2.13,
    contrast: {
      "double-black": 1.43,
      sable: 1,
      white: 14.73
    }
  },
  {
    id: 23,
    name: "White",
    slug: "white",
    type: "grayscale",
    pms: null,
    cmyk: [
      0,
      0,
      0,
      0
    ],
    rgb: [
      255,
      255,
      255
    ],
    hex: "FFFFFF",
    luminance: 100,
    contrast: {
      "double-black": 21,
      sable: 14.73,
      white: 1
    }
  },
  {
    id: 24,
    name: "Double Black",
    slug: "double-black",
    type: "grayscale",
    pms: null,
    cmyk: [
      100,
      100,
      100,
      100
    ],
    rgb: [
      0,
      0,
      0
    ],
    hex: "000000",
    luminance: 0,
    contrast: {
      "double-black": 1,
      sable: 1.43,
      white: 21
    }
  }
], p = (e, { [e]: t, ...r }) => r;
class k {
  constructor(t) {
    a(this, "id");
    a(this, "name");
    a(this, "slug");
    a(this, "type");
    a(this, "pms");
    a(this, "cmyk");
    a(this, "rgb");
    a(this, "hex");
    a(this, "luminance");
    a(this, "contrast");
    for (let r in t)
      this[r] = t[r];
  }
  /**
   * Find the best contrasting color (White or Sable) for this color.
   * @param useDoubleBlack Use Double Black instead of Sable
   * @returns object
   */
  getContrastingColor(t = !1) {
    const r = {
      sable: 22,
      white: 23,
      "double-black": 24
    }, l = p(
      t ? "sable" : "double-black",
      this.contrast
    ), o = Object.keys(l).sort((y, b) => l[b] - l[y]);
    return i(r[o[0]]);
  }
}
const s = () => g.map((e) => new k(e)), i = (e) => s()[e - 1] || null, w = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  get: s,
  getByID: i
}, Symbol.toStringTag, { value: "Module" }));
export {
  C as calculate,
  w as colors
};
