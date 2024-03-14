const m = [
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
    hex: "002D72"
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
    hex: "68ACE5"
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
    hex: "FF9E1B"
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
    hex: "009B77"
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
    hex: "0072CE"
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
    hex: "F1C400"
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
    hex: "CBA052"
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
    hex: "FF6900"
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
    hex: "9E5330"
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
    hex: "4F2C1D"
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
    hex: "E8927C"
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
    hex: "CF4520"
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
    hex: "A6192E"
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
    hex: "76232F"
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
    hex: "51284F"
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
    hex: "A15A95"
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
    hex: "A192B2"
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
    hex: "418FDE"
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
    hex: "86C8BC"
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
    hex: "286140"
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
    hex: "719949"
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
    hex: "31261D"
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
    hex: "FFFFFF"
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
    hex: "000000"
  }
], y = (e) => (e = e / 255, e <= 0.03928 ? e / 12.92 : ((e + 0.055) / 1.055) ** 2.4), i = (e) => {
  const r = y(e[0]), t = y(e[1]), a = y(e[2]);
  return 0.2126 * r + 0.7152 * t + 0.0722 * a;
}, s = (e, r) => {
  const t = i(e) + 0.05, a = i(r) + 0.05;
  return parseFloat((t / a).toFixed(2));
};
export {
  m as colors,
  s as contrast,
  i as luminance
};
