import { mkdirSync, writeFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const rootDir = join(__dirname, '..');
const outDir = join(rootDir, 'public', 'images', 'guides', 'wireframes-low-fi');
const galleryDir = join(rootDir, 'public', 'wireframes');

mkdirSync(outDir, { recursive: true });
mkdirSync(galleryDir, { recursive: true });

const W = 1280;
const H = 760;

const P = {
  bg: '#f8fafc',
  card: '#ffffff',
  soft: '#eef2f7',
  soft2: '#e2e8f0',
  line: '#cbd5e1',
  muted: '#94a3b8',
  muted2: '#64748b',
  ink: '#0f172a',
  ink2: '#334155',
  cyan: '#22c1dc',
  cyanSoft: '#e6faff',
  indigo: '#6366f1',
  indigoSoft: '#eef2ff',
  fuchsia: '#d946ef',
  fuchsiaSoft: '#fdf4ff',
  emerald: '#42b883',
  emeraldSoft: '#eafaf3',
  amber: '#e2a93b',
  dark: '#111827',
  dark2: '#1f2937',
  black: '#020617',
};

const pages = [];

function esc(value) {
  return String(value)
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;');
}

function attrs(options) {
  return Object.entries(options)
    .filter(([, value]) => value !== undefined && value !== null && value !== false)
    .map(([key, value]) => `${key}="${esc(value)}"`)
    .join(' ');
}

function rect(x, y, w, h, options = {}) {
  return `<rect ${attrs({
    x,
    y,
    width: w,
    height: h,
    rx: options.r ?? 10,
    fill: options.fill ?? P.card,
    stroke: options.stroke ?? P.line,
    'stroke-width': options.sw ?? 1,
    opacity: options.opacity,
    'stroke-dasharray': options.dash,
  })}/>`;
}

function line(x1, y1, x2, y2, options = {}) {
  return `<line ${attrs({
    x1,
    y1,
    x2,
    y2,
    stroke: options.stroke ?? P.line,
    'stroke-width': options.sw ?? 1,
    'stroke-linecap': options.cap ?? 'round',
    opacity: options.opacity,
    'stroke-dasharray': options.dash,
  })}/>`;
}

function circle(cx, cy, r, options = {}) {
  return `<circle ${attrs({
    cx,
    cy,
    r,
    fill: options.fill ?? P.soft2,
    stroke: options.stroke,
    'stroke-width': options.sw,
    opacity: options.opacity,
  })}/>`;
}

function text(value, x, y, options = {}) {
  return `<text ${attrs({
    x,
    y,
    fill: options.fill ?? P.ink,
    'font-size': options.size ?? 16,
    'font-weight': options.weight ?? 600,
    'text-anchor': options.anchor,
    'letter-spacing': options.spacing,
    opacity: options.opacity,
  })}>${esc(value)}</text>`;
}

function pill(value, x, y, w, options = {}) {
  return [
    rect(x, y, w, options.h ?? 34, {
      r: options.r ?? 17,
      fill: options.fill ?? P.soft,
      stroke: options.stroke ?? 'none',
      sw: 0,
    }),
    text(value, x + w / 2, y + (options.textY ?? 22), {
      size: options.size ?? 12,
      weight: options.weight ?? 800,
      fill: options.color ?? P.ink2,
      anchor: 'middle',
      spacing: options.spacing ?? 0.4,
    }),
  ].join('');
}

function skeleton(x, y, widths, options = {}) {
  const height = options.h ?? 10;
  const gap = options.gap ?? 14;
  return widths
    .map((w, i) =>
      rect(x, y + i * gap, w, height, {
        r: height / 2,
        fill: options.fill ?? P.muted,
        stroke: 'none',
        sw: 0,
        opacity: options.opacity ?? 0.72,
      }),
    )
    .join('');
}

function progress(x, y, w, pct, options = {}) {
  const h = options.h ?? 10;
  return [
    rect(x, y, w, h, { r: h / 2, fill: options.bg ?? P.soft2, stroke: 'none', sw: 0 }),
    rect(x, y, Math.max(8, w * (pct / 100)), h, {
      r: h / 2,
      fill: options.color ?? P.cyan,
      stroke: 'none',
      sw: 0,
    }),
  ].join('');
}

function ring(cx, cy, r, pct, color) {
  const c = Math.PI * 2 * r;
  return [
    `<circle cx="${cx}" cy="${cy}" r="${r}" fill="none" stroke="${P.soft2}" stroke-width="14"/>`,
    `<circle cx="${cx}" cy="${cy}" r="${r}" fill="none" stroke="${color}" stroke-width="14" stroke-linecap="round" stroke-dasharray="${(c * pct) / 100} ${c}" transform="rotate(-90 ${cx} ${cy})"/>`,
  ].join('');
}

function topNav(active = 'Beranda', signedIn = false) {
  const items = ['Beranda', 'Materi', 'Sandbox', 'Resources'];
  return [
    rect(0, 0, W, 82, { r: 0, fill: P.card, stroke: 'none', sw: 0 }),
    line(0, 82, W, 82, { stroke: P.line }),
    rect(64, 28, 76, 22, { r: 6, fill: P.ink, stroke: 'none', sw: 0 }),
    text('Utilwind', 74, 44, { size: 12, weight: 800, fill: P.card }),
    ...items.map((item, i) => {
      const x = 450 + i * 90;
      return [
        text(item, x, 49, {
          size: 15,
          weight: item === active ? 800 : 700,
          fill: item === active ? P.ink : P.muted2,
        }),
        item === active ? rect(x, 58, 44, 4, { r: 2, fill: P.cyan, stroke: 'none', sw: 0 }) : '',
      ].join('');
    }),
    circle(980, 42, 10, { fill: P.soft2 }),
    signedIn
      ? [
          circle(1110, 42, 21, { fill: P.ink }),
          text('Na', 1110, 47, { size: 12, weight: 800, fill: P.card, anchor: 'middle' }),
          text('Nabila', 1142, 47, { size: 14, weight: 800, fill: P.ink2 }),
          line(1200, 38, 1208, 46, { stroke: P.muted, sw: 2 }),
          line(1216, 38, 1208, 46, { stroke: P.muted, sw: 2 }),
        ].join('')
      : [
          text('Masuk', 1060, 48, { size: 14, weight: 800, fill: P.ink2 }),
          rect(1124, 22, 92, 44, { r: 22, fill: P.ink, stroke: 'none', sw: 0 }),
          text('Daftar', 1170, 50, { size: 14, weight: 800, fill: P.card, anchor: 'middle' }),
        ].join(''),
  ].join('');
}

function pageWrap(title, body, options = {}) {
  const footerX = options.footerX ?? 36;
  const footer = [
    rect(footerX, 708, 290, 28, { r: 14, fill: P.card, stroke: P.line }),
    text('Low fidelity wireframe - Utilwind', footerX + 22, 727, { size: 12, weight: 800, fill: P.muted2 }),
    text(options.pageLabel ?? title, 1220, 727, { size: 12, weight: 800, fill: P.muted2, anchor: 'end' }),
  ].join('');

  return `<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="${W}" height="${H}" viewBox="0 0 ${W} ${H}" role="img" aria-labelledby="title desc">
  <title id="title">${esc(title)}</title>
  <desc id="desc">${esc(options.desc ?? 'Wireframe low fidelity untuk media pembelajaran Utilwind.')}</desc>
  <style>
    text { font-family: "Plus Jakarta Sans", Inter, Arial, sans-serif; }
    .mono { font-family: "Fira Code", Consolas, monospace; }
  </style>
  <rect width="${W}" height="${H}" fill="${options.bg ?? P.bg}"/>
  ${body}
  ${options.hideFooter ? '' : footer}
</svg>
`;
}

function featureCard(x, y, title, pct, color) {
  return [
    rect(x, y, 244, 126, { r: 20, fill: P.card, stroke: P.line }),
    circle(x + 34, y + 34, 16, { fill: P.soft2 }),
    skeleton(x + 64, y + 26, [120, 82], { h: 9, gap: 22 }),
    text(title, x + 26, y + 86, { size: 13, weight: 800, fill: P.ink2 }),
    progress(x + 26, y + 100, 182, pct, { color, h: 8 }),
  ].join('');
}

function makeHome() {
  const body = [
    topNav('Beranda', false),
    text('Belajar', 58, 178, { size: 64, weight: 900, fill: P.ink }),
    text('Utility-First', 58, 248, { size: 60, weight: 500, fill: P.muted2 }),
    text('Tailwind CSS', 58, 318, { size: 64, weight: 900, fill: P.indigo }),
    skeleton(62, 356, [430, 500, 360], { h: 14, gap: 24, fill: P.muted2, opacity: 0.65 }),
    rect(62, 454, 202, 48, { r: 24, fill: P.ink, stroke: 'none', sw: 0 }),
    text('Mulai Belajar', 163, 484, { size: 15, weight: 800, fill: P.card, anchor: 'middle' }),
    rect(282, 454, 148, 48, { r: 24, fill: P.card, stroke: P.line }),
    text('Lihat Fitur', 356, 484, { size: 15, weight: 800, fill: P.ink2, anchor: 'middle' }),
    rect(632, 162, 610, 350, { r: 22, fill: P.card, stroke: P.line }),
    text('Live Sandbox', 660, 205, { size: 18, weight: 900, fill: P.ink }),
    skeleton(660, 226, [190, 120], { h: 9, gap: 16, fill: P.muted2 }),
    rect(660, 260, 334, 214, { r: 16, fill: P.black, stroke: 'none', sw: 0 }),
    circle(684, 282, 6, { fill: '#f87171' }),
    circle(704, 282, 6, { fill: '#fbbf24' }),
    circle(724, 282, 6, { fill: '#34d399' }),
    text('<button class="rounded-full px-6">', 684, 326, {
      size: 12,
      weight: 700,
      fill: P.cyan,
    }),
    text('Mulai Belajar', 710, 360, { size: 12, weight: 700, fill: P.card }),
    text('</button>', 684, 394, { size: 12, weight: 700, fill: P.cyan }),
    rect(1016, 262, 176, 82, { r: 16, fill: P.soft, stroke: P.line }),
    text('PROGRES MATERI', 1034, 296, { size: 11, weight: 900, fill: P.muted2, spacing: 1.2 }),
    progress(1034, 320, 126, 78, { color: P.cyan }),
    rect(1016, 362, 176, 82, { r: 16, fill: P.soft, stroke: P.line }),
    text('PROGRES LAB', 1034, 396, { size: 11, weight: 900, fill: P.muted2, spacing: 1.2 }),
    progress(1034, 420, 126, 62, { color: P.fuchsia }),
    ...[
      featureCard(62, 560, 'Materi Terstruktur', 76, P.cyan),
      featureCard(330, 560, 'Kamus Class', 52, P.indigo),
      featureCard(598, 560, 'Live Sandbox', 66, P.fuchsia),
      featureCard(866, 560, 'Progress Belajar', 88, P.emerald),
    ],
  ].join('');

  return pageWrap('Wireframe Halaman Awal', body, { pageLabel: '01 / Halaman Awal' });
}

function makeAuth() {
  const body = [
    rect(52, 36, 112, 38, { r: 19, fill: P.card, stroke: P.line }),
    text('Kembali', 108, 60, { size: 13, weight: 800, fill: P.ink2, anchor: 'middle' }),
    rect(110, 96, 1060, 560, { r: 28, fill: P.card, stroke: P.line }),
    line(612, 96, 612, 656, { stroke: P.line }),
    text('Masuk / Daftar', 176, 168, { size: 34, weight: 900, fill: P.ink }),
    skeleton(178, 192, [280, 220], { h: 10, gap: 18 }),
    pill('Masuk', 176, 238, 104, { fill: P.ink, color: P.card }),
    pill('Daftar', 292, 238, 104, { fill: P.soft, color: P.ink2 }),
    text('Alamat Email', 178, 320, { size: 12, weight: 900, fill: P.muted2, spacing: 0.8 }),
    rect(176, 334, 342, 54, { r: 14, fill: P.bg, stroke: P.line }),
    text('nama@email.com', 202, 368, { size: 14, weight: 600, fill: P.muted2 }),
    text('Password', 178, 424, { size: 12, weight: 900, fill: P.muted2, spacing: 0.8 }),
    rect(176, 438, 342, 54, { r: 14, fill: P.bg, stroke: P.line }),
    skeleton(202, 460, [142], { h: 12, fill: P.muted2, opacity: 0.55 }),
    rect(176, 518, 18, 18, { r: 5, fill: P.card, stroke: P.line }),
    text('Ingat sesi saya', 206, 532, { size: 13, weight: 700, fill: P.ink2 }),
    rect(176, 566, 342, 54, { r: 17, fill: P.ink, stroke: 'none', sw: 0 }),
    text('Masuk ke Workspace', 347, 600, { size: 15, weight: 900, fill: P.card, anchor: 'middle' }),
    rect(674, 140, 420, 420, { r: 30, fill: P.black, stroke: 'none', sw: 0 }),
    circle(722, 188, 8, { fill: '#f87171' }),
    circle(750, 188, 8, { fill: '#fbbf24' }),
    circle(778, 188, 8, { fill: '#34d399' }),
    text('Preview Media Pembelajaran', 718, 238, { size: 18, weight: 900, fill: P.card }),
    skeleton(718, 268, [280, 220, 310], { h: 10, gap: 20, fill: '#64748b', opacity: 0.72 }),
    rect(718, 350, 158, 80, { r: 16, fill: '#1e293b', stroke: '#334155' }),
    rect(902, 350, 142, 80, { r: 16, fill: '#1e293b', stroke: '#334155' }),
    text('Materi', 744, 386, { size: 13, weight: 800, fill: P.cyan }),
    progress(744, 404, 96, 70, { color: P.cyan, bg: '#334155' }),
    text('Kuis', 928, 386, { size: 13, weight: 800, fill: P.fuchsia }),
    progress(928, 404, 80, 55, { color: P.fuchsia, bg: '#334155' }),
    rect(718, 464, 326, 46, { r: 23, fill: P.card, stroke: 'none', sw: 0 }),
    text('Lanjutkan Belajar', 881, 493, { size: 14, weight: 900, fill: P.ink, anchor: 'middle' }),
  ].join('');

  return pageWrap('Wireframe Halaman Autentikasi', body, { pageLabel: '02 / Autentikasi' });
}

function makeDashboard() {
  const stats = [
    ['Materi selesai', '52/65', P.cyan, 80],
    ['Lab tuntas', '3/5', P.fuchsia, 60],
    ['Rata-rata kuis', '86', P.indigo, 86],
  ];
  const body = [
    topNav('Beranda', true),
    text('Dasbor Belajar', 58, 142, { size: 38, weight: 900, fill: P.ink }),
    skeleton(60, 170, [420, 300], { h: 11, gap: 18 }),
    ...stats.map((item, i) => {
      const x = 58 + i * 286;
      return [
        rect(x, 220, 250, 118, { r: 18, fill: P.card, stroke: P.line }),
        text(item[0], x + 22, 254, { size: 12, weight: 900, fill: P.muted2, spacing: 0.7 }),
        text(item[1], x + 22, 296, { size: 32, weight: 900, fill: P.ink }),
        progress(x + 22, 312, 176, item[3], { color: item[2], h: 8 }),
      ].join('');
    }),
    rect(58, 372, 690, 284, { r: 20, fill: P.card, stroke: P.line }),
    text('Grafik Nilai Kuis', 88, 416, { size: 18, weight: 900, fill: P.ink }),
    skeleton(88, 438, [250], { h: 9 }),
    ...[92, 170, 248, 326, 404, 482].map((x, i) =>
      rect(x, 600 - [72, 112, 94, 146, 126, 164][i], 42, [72, 112, 94, 146, 126, 164][i], {
        r: 9,
        fill: i % 2 ? P.indigo : P.cyan,
        stroke: 'none',
        sw: 0,
        opacity: 0.86,
      }),
    ).join(''),
    line(88, 600, 692, 600, { stroke: P.line }),
    rect(784, 372, 438, 284, { r: 20, fill: P.card, stroke: P.line }),
    text('Aktivitas Terbaru', 814, 416, { size: 18, weight: 900, fill: P.ink }),
    ...[0, 1, 2, 3].map((i) => {
      const y = 452 + i * 45;
      const color = [P.cyan, P.fuchsia, P.indigo, P.emerald][i];
      return [
        circle(824, y + 6, 9, { fill: color }),
        skeleton(846, y, [230, 146], { h: 9, gap: 17 }),
        line(814, y + 32, 1188, y + 32, { stroke: P.soft2 }),
      ].join('');
    }).join(''),
    rect(910, 220, 312, 118, { r: 18, fill: P.emeraldSoft, stroke: '#b7ead4' }),
    text('Token Kelas', 940, 256, { size: 13, weight: 900, fill: '#0f766e', spacing: 0.7 }),
    skeleton(940, 282, [130], { h: 12, fill: '#0f766e', opacity: 0.38 }),
    rect(1088, 260, 100, 40, { r: 20, fill: P.ink, stroke: 'none', sw: 0 }),
    text('Gabung', 1138, 286, { size: 13, weight: 900, fill: P.card, anchor: 'middle' }),
  ].join('');

  return pageWrap('Wireframe Dashboard', body, { pageLabel: '03 / Dashboard' });
}

function makeMateri() {
  const body = [
    topNav('Materi', true),
    rect(58, 118, 258, 558, { r: 22, fill: P.card, stroke: P.line }),
    text('Daftar Materi', 88, 162, { size: 20, weight: 900, fill: P.ink }),
    ...['Bab 01 Pendahuluan', '1.1 HTML dan CSS', '1.2 Tailwind CSS', '1.3 CDN', '1.4 Instalasi', '1.5 Konfigurasi', 'Bab 02 Layouting', 'Bab 03 Styling'].map((item, i) => {
      const y = 198 + i * 48;
      const active = i === 2;
      return [
        rect(82, y - 24, 204, 36, {
          r: 12,
          fill: active ? P.cyanSoft : 'transparent',
          stroke: active ? '#b6ecf5' : 'none',
          sw: active ? 1 : 0,
        }),
        circle(102, y - 6, 6, { fill: active ? P.cyan : P.soft2 }),
        text(item, 118, y, { size: 13, weight: active ? 900 : 700, fill: active ? P.ink : P.muted2 }),
      ].join('');
    }),
    rect(348, 118, 558, 558, { r: 22, fill: P.card, stroke: P.line }),
    pill('MATERI 1.2', 386, 150, 112, { fill: P.cyanSoft, color: '#0891b2', spacing: 1.1 }),
    text('Konsep Dasar Tailwind CSS', 386, 210, { size: 34, weight: 900, fill: P.ink }),
    skeleton(386, 242, [426, 462, 380], { h: 12, gap: 24, fill: P.muted2, opacity: 0.6 }),
    rect(386, 334, 478, 132, { r: 18, fill: P.bg, stroke: P.line }),
    text('Contoh kode', 414, 368, { size: 14, weight: 900, fill: P.ink2 }),
    skeleton(414, 396, [330, 270, 360], { h: 10, gap: 22, fill: P.ink2, opacity: 0.34 }),
    rect(386, 504, 210, 48, { r: 24, fill: P.ink, stroke: 'none', sw: 0 }),
    text('Tandai Selesai', 491, 534, { size: 14, weight: 900, fill: P.card, anchor: 'middle' }),
    rect(614, 504, 168, 48, { r: 24, fill: P.card, stroke: P.line }),
    text('Lanjut', 698, 534, { size: 14, weight: 900, fill: P.ink2, anchor: 'middle' }),
    rect(940, 118, 282, 240, { r: 22, fill: P.card, stroke: P.line }),
    text('Progress Bab', 970, 160, { size: 18, weight: 900, fill: P.ink }),
    ring(1082, 238, 58, 72, P.cyan),
    text('72%', 1082, 248, { size: 24, weight: 900, fill: P.ink, anchor: 'middle' }),
    skeleton(972, 326, [180], { h: 10 }),
    rect(940, 386, 282, 290, { r: 22, fill: P.card, stroke: P.line }),
    text('Rangkuman', 970, 428, { size: 18, weight: 900, fill: P.ink }),
    skeleton(970, 460, [198, 220, 170, 206], { h: 10, gap: 26 }),
    rect(970, 596, 190, 42, { r: 21, fill: P.indigoSoft, stroke: '#d7dcff' }),
    text('Buka Kuis Bab', 1065, 623, { size: 13, weight: 900, fill: P.indigo, anchor: 'middle' }),
  ].join('');

  return pageWrap('Wireframe Halaman Materi', body, { pageLabel: '04 / Halaman Materi' });
}

function makeQuiz() {
  const body = [
    rect(0, 0, W, 78, { r: 0, fill: P.card, stroke: 'none', sw: 0 }),
    line(0, 78, W, 78, { stroke: P.line }),
    text('Evaluasi Bab 1', 58, 48, { size: 23, weight: 900, fill: P.ink }),
    progress(272, 38, 390, 45, { color: P.cyan, h: 10 }),
    text('05 / 10', 686, 47, { size: 14, weight: 900, fill: P.muted2 }),
    rect(980, 20, 96, 38, { r: 19, fill: P.black, stroke: 'none', sw: 0 }),
    text('12:00', 1028, 45, { size: 14, weight: 900, fill: P.card, anchor: 'middle' }),
    rect(1096, 20, 122, 38, { r: 19, fill: P.card, stroke: P.line }),
    text('Selesai', 1157, 45, { size: 14, weight: 900, fill: P.ink2, anchor: 'middle' }),
    rect(58, 120, 772, 520, { r: 24, fill: P.card, stroke: P.line }),
    pill('SOAL PILIHAN GANDA', 92, 156, 188, { fill: P.cyanSoft, color: '#0891b2', spacing: 1 }),
    text('Class Tailwind untuk tombol rounded?', 92, 224, {
      size: 28,
      weight: 900,
      fill: P.ink,
    }),
    skeleton(92, 254, [560, 430], { h: 11, gap: 22 }),
    ...['A', 'B', 'C', 'D'].map((label, i) => {
      const y = 332 + i * 74;
      const active = i === 1;
      return [
        rect(92, y, 680, 54, {
          r: 16,
          fill: active ? P.indigoSoft : P.bg,
          stroke: active ? '#c6cdfb' : P.line,
        }),
        circle(120, y + 27, 13, { fill: active ? P.indigo : P.card, stroke: active ? P.indigo : P.line, sw: 2 }),
        text(label, 120, y + 32, {
          size: 12,
          weight: 900,
          fill: active ? P.card : P.muted2,
          anchor: 'middle',
        }),
        skeleton(150, y + 22, [[260, 210, 320, 240][i]], { h: 10, fill: P.ink2, opacity: 0.42 }),
      ].join('');
    }),
    rect(872, 120, 350, 520, { r: 24, fill: P.card, stroke: P.line }),
    text('Navigasi Soal', 906, 164, { size: 20, weight: 900, fill: P.ink }),
    ...Array.from({ length: 10 }, (_, i) => {
      const x = 906 + (i % 5) * 56;
      const y = 198 + Math.floor(i / 5) * 56;
      const fill = i < 4 ? P.cyan : i === 4 ? P.indigo : P.soft;
      const color = i <= 4 ? P.card : P.muted2;
      return [
        rect(x, y, 42, 42, { r: 12, fill, stroke: 'none', sw: 0 }),
        text(String(i + 1), x + 21, y + 27, { size: 13, weight: 900, fill: color, anchor: 'middle' }),
      ].join('');
    }).join(''),
    line(906, 332, 1188, 332, { stroke: P.soft2 }),
    text('Catatan refleksi', 906, 374, { size: 16, weight: 900, fill: P.ink2 }),
    rect(906, 396, 282, 114, { r: 16, fill: P.bg, stroke: P.line, dash: '5 8' }),
    skeleton(930, 426, [206, 152, 190], { h: 9 }),
    rect(906, 548, 128, 42, { r: 21, fill: P.card, stroke: P.line }),
    text('Sebelumnya', 970, 575, { size: 13, weight: 900, fill: P.ink2, anchor: 'middle' }),
    rect(1050, 548, 138, 42, { r: 21, fill: P.ink, stroke: 'none', sw: 0 }),
    text('Berikutnya', 1119, 575, { size: 13, weight: 900, fill: P.card, anchor: 'middle' }),
  ].join('');

  return pageWrap('Wireframe Halaman Kuis', body, { pageLabel: '05 / Halaman Kuis' });
}

function makePraktik() {
  const body = [
    rect(0, 0, W, H, { r: 0, fill: '#151515', stroke: 'none', sw: 0 }),
    rect(0, 0, W, 48, { r: 0, fill: '#2d2d2d', stroke: 'none', sw: 0 }),
    text('LAB 01: Struktur HTML dan Tailwind CDN', 56, 30, { size: 14, weight: 900, fill: P.card }),
    rect(586, 12, 202, 26, { r: 5, fill: '#3f3f46', stroke: '#52525b' }),
    text('Simpan & Jalankan', 687, 30, { size: 12, weight: 900, fill: P.card, anchor: 'middle' }),
    rect(1018, 12, 90, 26, { r: 5, fill: P.black, stroke: '#334155' }),
    text('Skor 100%', 1063, 30, { size: 12, weight: 800, fill: P.cyan, anchor: 'middle' }),
    rect(1192, 9, 72, 30, { r: 6, fill: '#475569', stroke: 'none', sw: 0 }),
    text('Keluar', 1228, 29, { size: 12, weight: 900, fill: P.card, anchor: 'middle' }),
    rect(0, 48, 320, 664, { r: 0, fill: '#232323', stroke: 'none', sw: 0 }),
    text('DAFTAR TUGAS', 16, 70, { size: 11, weight: 900, fill: '#a5b4fc', spacing: 0.8 }),
    ...['Pasang Tailwind CDN', 'Susun Bagian Semantik', 'Buat Tombol Nyaman', 'Bentuk Kartu Profil', 'Pusatkan Konten'].map((item, i) => {
      const y = 96 + i * 84;
      const active = i === 0;
      return [
        rect(0, y - 16, 320, active ? 64 : 54, { r: 0, fill: active ? '#34343a' : '#232323', stroke: 'none', sw: 0 }),
        text(active ? '>' : '✓', 20, y + 4, { size: 13, weight: 900, fill: active ? P.cyan : '#34d399' }),
        text(item, 42, y + 4, { size: 13, weight: 800, fill: active ? P.card : '#cbd5e1' }),
        active ? rect(18, y + 34, 284, 80, { r: 5, fill: '#2a2a2a', stroke: '#444' }) : '',
        active ? skeleton(30, y + 58, [230, 260, 220], { h: 8, gap: 18, fill: '#cbd5e1', opacity: 0.75 }) : '',
      ].join('');
    }),
    rect(320, 48, 385, 664, { r: 0, fill: '#111418', stroke: 'none', sw: 0 }),
    rect(320, 48, 118, 36, { r: 0, fill: '#1f2937', stroke: P.cyan }),
    text('index.html', 354, 70, { size: 12, weight: 900, fill: P.card }),
    text('1', 342, 116, { size: 13, weight: 700, fill: '#475569' }),
    text('<!doctype html>', 372, 116, { size: 13, weight: 700, fill: '#94a3b8' }),
    text('2', 342, 152, { size: 13, weight: 700, fill: '#475569' }),
    text('<script src="https://cdn.tailwindcss.com"></script>', 372, 152, {
      size: 13,
      weight: 700,
      fill: P.cyan,
    }),
    text('3', 342, 188, { size: 13, weight: 700, fill: '#475569' }),
    skeleton(372, 178, [238], { h: 10, fill: '#64748b', opacity: 0.7 }),
    text('4', 342, 224, { size: 13, weight: 700, fill: '#475569' }),
    skeleton(372, 214, [286], { h: 10, fill: '#64748b', opacity: 0.7 }),
    text('5', 342, 260, { size: 13, weight: 700, fill: '#475569' }),
    skeleton(372, 250, [210], { h: 10, fill: '#64748b', opacity: 0.7 }),
    rect(455, 374, 116, 34, { r: 6, fill: 'transparent', stroke: P.cyan }),
    text('MODE TINJAU', 513, 396, { size: 12, weight: 900, fill: P.cyan, anchor: 'middle' }),
    rect(705, 48, 575, 456, { r: 0, fill: P.bg, stroke: 'none', sw: 0 }),
    rect(705, 48, 575, 36, { r: 0, fill: P.card, stroke: 'none', sw: 0 }),
    text('LOCALHOST:8000', 732, 70, { size: 11, weight: 900, fill: P.ink2 }),
    rect(760, 130, 360, 220, { r: 18, fill: P.card, stroke: P.line }),
    circle(830, 204, 42, { fill: P.soft2 }),
    skeleton(900, 174, [150, 210, 120], { h: 12, gap: 26 }),
    rect(900, 282, 154, 42, { r: 21, fill: P.ink, stroke: 'none', sw: 0 }),
    text('Tombol Profil', 977, 309, { size: 13, weight: 900, fill: P.card, anchor: 'middle' }),
    rect(705, 504, 575, 208, { r: 0, fill: '#1f1f1f', stroke: 'none', sw: 0 }),
    text('TERMINAL', 722, 524, { size: 11, weight: 900, fill: P.card }),
    line(705, 536, 1280, 536, { stroke: '#444' }),
    text('INIT', 720, 560, { size: 13, weight: 700, fill: '#64748b' }),
    text('Lingkungan kerja berhasil dimuat.', 784, 560, { size: 13, weight: 800, fill: '#34d399' }),
    text('19.13.40 [Sistem] Mode tinjau aktif.', 720, 590, { size: 13, weight: 700, fill: '#94a3b8' }),
    rect(0, 712, W, 48, { r: 0, fill: '#0ea5e9', stroke: 'none', sw: 0 }),
    text('</> master*', 14, 742, { size: 12, weight: 900, fill: P.card }),
    text('UTF-8   HTML', 1218, 742, { size: 12, weight: 900, fill: P.card, anchor: 'end' }),
  ].join('');

  return pageWrap('Wireframe Halaman Praktik', body, {
    pageLabel: '06 / Halaman Praktik',
    bg: '#151515',
    hideFooter: true,
  });
}

function adminSideNav(active = 'Analytics') {
  const items = ['Dashboard', 'Pengguna', 'Kelas', 'Quiz', 'Lab', 'Analytics', 'Laporan'];
  return [
    rect(0, 0, 294, H, { r: 0, fill: P.card, stroke: 'none', sw: 0 }),
    line(294, 0, 294, H, { stroke: P.line }),
    rect(60, 58, 128, 34, { r: 8, fill: P.ink, stroke: 'none', sw: 0 }),
    text('Utilwind', 124, 80, { size: 14, weight: 900, fill: P.card, anchor: 'middle' }),
    ...items.map((item, i) => {
      const y = 144 + i * 62;
      const isActive = item === active;
      return [
        rect(48, y - 24, 206, 44, {
          r: 12,
          fill: isActive ? P.indigoSoft : 'transparent',
          stroke: isActive ? '#d7dcff' : 'none',
          sw: isActive ? 1 : 0,
        }),
        circle(76, y - 2, 10, { fill: isActive ? P.indigo : P.muted }),
        text(item, 100, y + 4, { size: 14, weight: 800, fill: isActive ? P.ink : P.muted2 }),
      ].join('');
    }),
    line(0, 690, 294, 690, { stroke: P.line }),
    circle(82, 728, 24, { fill: P.muted }),
    skeleton(124, 716, [116, 88], { h: 10, gap: 18 }),
  ].join('');
}

function makeAnalytics() {
  const body = [
    adminSideNav('Analytics'),
    text('Detail Analytics Mahasiswa', 340, 72, { size: 30, weight: 900, fill: P.ink }),
    skeleton(342, 98, [380, 210], { h: 10, gap: 18 }),
    circle(1010, 72, 11, { fill: P.muted }),
    circle(1080, 72, 11, { fill: P.muted }),
    rect(1146, 58, 94, 14, { r: 7, fill: P.ink, stroke: 'none', sw: 0 }),
    skeleton(1168, 88, [64], { h: 8 }),
    rect(340, 150, 340, 168, { r: 22, fill: P.card, stroke: P.line }),
    circle(402, 220, 42, { fill: P.soft2 }),
    text('Profil Mahasiswa', 476, 202, { size: 18, weight: 900, fill: P.ink }),
    skeleton(476, 226, [176, 128], { h: 10, gap: 22 }),
    rect(372, 274, 244, 30, { r: 15, fill: P.emeraldSoft, stroke: '#b7ead4' }),
    progress(390, 304, 190, 78, { color: P.emerald, h: 8 }),
    rect(710, 150, 494, 168, { r: 22, fill: P.card, stroke: P.line }),
    text('Ringkasan Penguasaan', 746, 202, { size: 18, weight: 900, fill: P.ink }),
    skeleton(746, 226, [330, 260], { h: 10, gap: 22 }),
    ring(1120, 234, 56, 72, P.cyan),
    text('72%', 1120, 244, { size: 22, weight: 900, fill: P.ink, anchor: 'middle' }),
    ...[
      ['Materi', P.cyan, 86],
      ['Kuis', P.indigo, 74],
      ['Praktik', P.fuchsia, 68],
      ['Fokus', P.emerald, 82],
      ['Remedial', P.amber, 30],
    ].map((item, i) => {
      const x = 340 + i * 176;
      return [
        rect(x, 348, 154, 124, { r: 18, fill: P.card, stroke: P.line }),
        circle(x + 30, 390, 17, { fill: item[1] }),
        text(item[0], x + 58, 390, { size: 14, weight: 900, fill: P.ink2 }),
        progress(x + 30, 432, 100, item[2], { color: item[1], h: 8 }),
      ].join('');
    }),
    rect(340, 504, 490, 178, { r: 22, fill: P.card, stroke: P.line }),
    text('Capaian Per Bab', 374, 544, { size: 18, weight: 900, fill: P.ink }),
    ...[0, 1, 2].map((i) => {
      const y = 584 + i * 36;
      const color = [P.cyan, P.indigo, P.fuchsia][i];
      return [
        circle(382, y, 12, { fill: color }),
        skeleton(414, y - 6, [168], { h: 10 }),
        progress(620, y - 6, 160, [76, 62, 68][i], { color, h: 10 }),
      ].join('');
    }),
    rect(866, 504, 338, 178, { r: 22, fill: P.card, stroke: P.line }),
    text('Aktivitas Terakhir', 900, 544, { size: 18, weight: 900, fill: P.ink }),
    ...[0, 1, 2, 3].map((i) => {
      const y = 582 + i * 28;
      return [
        rect(904, y - 14, 250, 24, { r: 12, fill: P.bg, stroke: P.line }),
        circle(922, y - 2, 7, { fill: P.muted }),
        skeleton(944, y - 8, [[90, 120, 76, 100][i], 46], { h: 7, gap: 0 }),
      ].join('');
    }),
  ].join('');

  return pageWrap('Wireframe Detail Analytics', body, {
    pageLabel: '07 / Detail Analytics',
    footerX: 326,
  });
}

function register(file, title, svg) {
  const output = join(outDir, file);
  writeFileSync(output, svg, 'utf8');
  pages.push({ file, title });
}

register('01-halaman-awal.svg', 'Halaman Awal', makeHome());
register('02-autentikasi.svg', 'Halaman Autentikasi', makeAuth());
register('03-dashboard.svg', 'Dashboard', makeDashboard());
register('04-halaman-materi.svg', 'Halaman Materi', makeMateri());
register('05-halaman-kuis.svg', 'Halaman Kuis', makeQuiz());
register('06-halaman-praktik.svg', 'Halaman Praktik', makePraktik());
register('07-detail-analytics.svg', 'Detail Analytics', makeAnalytics());

const gallery = `<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Wireframe Low Fidelity Utilwind</title>
  <style>
    :root {
      color-scheme: light;
      --bg: #f1f5f9;
      --card: #ffffff;
      --line: #cbd5e1;
      --ink: #0f172a;
      --muted: #64748b;
      --cyan: #22c1dc;
      --indigo: #6366f1;
    }

    * { box-sizing: border-box; }

    body {
      margin: 0;
      font-family: "Plus Jakarta Sans", Inter, Arial, sans-serif;
      background: var(--bg);
      color: var(--ink);
    }

    header {
      padding: 32px clamp(20px, 4vw, 56px) 16px;
      display: grid;
      gap: 10px;
    }

    h1 {
      margin: 0;
      font-size: clamp(28px, 4vw, 44px);
      line-height: 1.05;
      letter-spacing: 0;
    }

    p {
      margin: 0;
      max-width: 820px;
      color: var(--muted);
      font-size: 15px;
      line-height: 1.7;
    }

    main {
      padding: 20px clamp(20px, 4vw, 56px) 56px;
      display: grid;
      gap: 28px;
    }

    .wireframe-card {
      background: var(--card);
      border: 1px solid var(--line);
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 18px 50px rgba(15, 23, 42, 0.08);
    }

    .wireframe-head {
      min-height: 56px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      padding: 14px 18px;
      border-bottom: 1px solid var(--line);
      background: #f8fafc;
    }

    .wireframe-head h2 {
      margin: 0;
      font-size: 16px;
      letter-spacing: 0;
    }

    .wireframe-head a {
      color: var(--indigo);
      font-size: 13px;
      font-weight: 800;
      text-decoration: none;
    }

    img {
      display: block;
      width: 100%;
      height: auto;
      background: #f8fafc;
    }

    .notes {
      padding: 18px;
      color: var(--muted);
      font-size: 13px;
      line-height: 1.7;
      border-top: 1px solid var(--line);
    }
  </style>
</head>
<body>
  <header>
    <h1>Wireframe Low Fidelity Utilwind</h1>
    <p>Rancangan sederhana untuk halaman awal, autentikasi, dashboard, materi, kuis, praktik, dan detail analytics. Komposisinya mengikuti media Utilwind: belajar Tailwind CSS, progres belajar, evaluasi, lab coding, dan pemantauan dosen.</p>
  </header>
  <main>
    ${pages
      .map(
        (page, index) => `<section class="wireframe-card">
      <div class="wireframe-head">
        <h2>${String(index + 1).padStart(2, '0')}. ${esc(page.title)}</h2>
        <a href="../images/guides/wireframes-low-fi/${page.file}">Buka SVG</a>
      </div>
      <img src="../images/guides/wireframes-low-fi/${page.file}" alt="Wireframe ${esc(page.title)}">
      <div class="notes">Low fidelity: fokus pada susunan konten, hierarki, navigasi, dan area interaksi utama. Warna hanya dipakai sebagai aksen agar tetap sesuai karakter Utilwind tanpa masuk ke detail visual final.</div>
    </section>`,
      )
      .join('\n')}
  </main>
</body>
</html>
`;

writeFileSync(join(galleryDir, 'utilwind-low-fidelity.html'), gallery, 'utf8');

console.log(`Generated ${pages.length} wireframes in ${outDir}`);
console.log(`Gallery: ${join(galleryDir, 'utilwind-low-fidelity.html')}`);
