from pathlib import Path

from PIL import Image, ImageDraw, ImageFilter


ROOT = Path(__file__).resolve().parents[1]
OUT_DIR = ROOT / "public" / "images" / "low-fidelity"
OUT_DIR.mkdir(parents=True, exist_ok=True)

W, H = 1600, 1000
S = 2


P = {
    "bg": "#f6f8fb",
    "canvas": "#ffffff",
    "panel": "#fbfcfe",
    "soft": "#eef2f7",
    "soft2": "#e4e9f1",
    "line": "#cfd7e4",
    "line2": "#dbe2ec",
    "muted": "#aab4c2",
    "dark": "#7c8797",
    "ink": "#475569",
    "accent": "#94a3b8",
    "accent2": "#b8c2d1",
    "dark_bg": "#1f2430",
    "dark_panel": "#252b38",
    "dark_panel2": "#303747",
    "dark_line": "#424b5d",
    "dark_text": "#8e99aa",
    "dark_soft": "#6f7b8e",
    "blue": "#8ea4c8",
}


def rgba(hex_color, alpha=255):
    hex_color = hex_color.lstrip("#")
    return tuple(int(hex_color[i : i + 2], 16) for i in (0, 2, 4)) + (alpha,)


def sc(value):
    return int(round(value * S))


def box(x, y, w, h):
    return [sc(x), sc(y), sc(x + w), sc(y + h)]


def make_canvas(bg=P["bg"]):
    return Image.new("RGBA", (W * S, H * S), rgba(bg))


def draw_shadow(img, x, y, w, h, r=24, alpha=34, blur=22, offset=10):
    shadow = Image.new("RGBA", img.size, (0, 0, 0, 0))
    sd = ImageDraw.Draw(shadow)
    sd.rounded_rectangle(
        box(x, y + offset, w, h),
        radius=sc(r),
        fill=(15, 23, 42, alpha),
    )
    shadow = shadow.filter(ImageFilter.GaussianBlur(sc(blur)))
    img.alpha_composite(shadow)


def rr(draw, x, y, w, h, r=18, fill=P["canvas"], outline=P["line"], width=1):
    draw.rounded_rectangle(
        box(x, y, w, h),
        radius=sc(r),
        fill=rgba(fill),
        outline=rgba(outline) if outline else None,
        width=sc(width) if outline and width else 1,
    )


def rect(draw, x, y, w, h, fill=P["soft"], outline=None, width=1):
    draw.rectangle(
        box(x, y, w, h),
        fill=rgba(fill),
        outline=rgba(outline) if outline else None,
        width=sc(width),
    )


def line(draw, x1, y1, x2, y2, fill=P["line"], width=2):
    draw.line([sc(x1), sc(y1), sc(x2), sc(y2)], fill=rgba(fill), width=sc(width))


def circle(draw, x, y, r, fill=P["soft2"], outline=None, width=1):
    draw.ellipse(
        [sc(x - r), sc(y - r), sc(x + r), sc(y + r)],
        fill=rgba(fill),
        outline=rgba(outline) if outline else None,
        width=sc(width),
    )


def skeleton(draw, x, y, widths, height=12, gap=22, fill=P["muted"], alpha=255):
    for i, w in enumerate(widths):
        draw.rounded_rectangle(
            box(x, y + i * gap, w, height),
            radius=sc(height / 2),
            fill=rgba(fill, alpha),
        )


def progress(draw, x, y, w, h, pct, fill=P["accent"], bg=P["soft2"]):
    rr(draw, x, y, w, h, h / 2, fill=bg, outline=None)
    rr(draw, x, y, max(h, w * pct), h, h / 2, fill=fill, outline=None)


def browser_shell(img, dark=False):
    draw_shadow(img, 52, 42, 1496, 916, r=30, alpha=42, blur=28, offset=10)
    d = ImageDraw.Draw(img)
    fill = P["dark_bg"] if dark else P["canvas"]
    line_color = P["dark_line"] if dark else P["line2"]
    rr(d, 52, 42, 1496, 916, 30, fill=fill, outline=line_color, width=1)
    rr(d, 52, 42, 1496, 58, 30, fill=P["dark_panel"] if dark else P["panel"], outline=None)
    line(d, 52, 100, 1548, 100, fill=line_color, width=1)
    for i, color in enumerate([P["muted"], P["accent2"], P["line"]]):
        circle(d, 86 + i * 22, 72, 7, fill=color if not dark else P["dark_line"])
    rr(d, 178, 61, 470, 22, 11, fill=P["soft"] if not dark else P["dark_panel2"], outline=None)
    rr(d, 1296, 58, 196, 28, 14, fill=P["soft"] if not dark else P["dark_panel2"], outline=None)


def top_nav(draw, y=118):
    rr(draw, 84, y, 76, 38, 12, fill=P["ink"], outline=None)
    for i, w in enumerate([74, 86, 82, 70]):
        rr(draw, 566 + i * 106, y + 7, w, 14, 7, fill=P["soft2"], outline=None)
    rr(draw, 1322, y - 2, 72, 34, 17, fill=P["soft2"], outline=None)
    rr(draw, 1410, y - 6, 96, 42, 21, fill=P["ink"], outline=None)


def card(draw, x, y, w, h, r=22, fill=P["canvas"], outline=P["line2"]):
    rr(draw, x, y, w, h, r, fill=fill, outline=outline, width=1)


def draw_home():
    img = make_canvas()
    browser_shell(img)
    d = ImageDraw.Draw(img)
    top_nav(d)

    # Hero content
    skeleton(d, 112, 230, [168, 560, 482, 410], height=22, gap=42, fill=P["ink"])
    skeleton(d, 112, 430, [548, 498, 384], height=13, gap=25, fill=P["muted"], alpha=210)
    rr(d, 112, 535, 178, 48, 24, fill=P["ink"], outline=None)
    rr(d, 312, 535, 160, 48, 24, fill=P["soft2"], outline=None)

    # Hero visual panel
    draw_shadow(img, 865, 188, 565, 376, r=34, alpha=26, blur=18, offset=8)
    card(d, 865, 188, 565, 376, 34, fill=P["panel"])
    rr(d, 900, 226, 212, 34, 17, fill=P["soft2"], outline=None)
    rr(d, 1184, 226, 182, 34, 17, fill=P["soft2"], outline=None)
    card(d, 902, 288, 236, 206, 24, fill=P["soft"])
    circle(d, 946, 335, 30, fill=P["accent2"])
    skeleton(d, 992, 318, [110, 84, 128], height=10, gap=22, fill=P["muted"])
    for i in range(4):
        rr(d, 930 + i * 45, 414, 30, 48, 10, fill=P["canvas"], outline=P["line2"])
    card(d, 1176, 288, 214, 206, 24, fill=P["canvas"])
    for i, w in enumerate([148, 116, 168, 92, 132]):
        rr(d, 1202, 322 + i * 28, w, 8, 4, fill=P["accent2"], outline=None)
    rr(d, 1202, 468, 144, 14, 7, fill=P["soft2"], outline=None)

    # Stats strip
    for i in range(4):
        x = 112 + i * 214
        card(d, x, 650, 184, 108, 22, fill=P["canvas"])
        circle(d, x + 34, 684, 14, fill=P["soft2"])
        skeleton(d, x + 58, 674, [78, 104], height=10, gap=22, fill=P["muted"])
        progress(d, x + 26, 724, 132, 8, [0.42, 0.64, 0.52, 0.76][i])

    # Feature band
    card(d, 112, 798, 1318, 118, 24, fill=P["panel"])
    for i in range(4):
        x = 152 + i * 315
        circle(d, x, 850, 20, fill=P["soft2"])
        skeleton(d, x + 40, 830, [158, 212, 130], height=11, gap=22, fill=P["muted"])

    return save(img, "gambar-4-6-beranda-low-fidelity.png")


def draw_material():
    img = make_canvas()
    browser_shell(img)
    d = ImageDraw.Draw(img)
    top_nav(d)

    # Course sidebar
    rr(d, 84, 170, 328, 744, 26, fill=P["panel"], outline=P["line2"])
    rr(d, 112, 202, 82, 24, 12, fill=P["soft2"], outline=None)
    skeleton(d, 112, 244, [218, 176], height=12, gap=24, fill=P["muted"])
    progress(d, 112, 306, 248, 12, 0.46)

    y = 362
    for group, rows in enumerate([3, 3, 2]):
        rr(d, 112, y, 248, 34, 17, fill=P["soft2"], outline=None)
        y += 48
        for row in range(rows):
            active = group == 0 and row == 1
            rr(d, 112, y, 248, 38, 12, fill=P["ink"] if active else P["canvas"], outline=P["line2"] if not active else None)
            circle(d, 134, y + 19, 6, fill=P["canvas"] if active else P["muted"])
            skeleton(d, 154, y + 12, [132, 84], height=7, gap=13, fill=P["canvas"] if active else P["muted"], alpha=230)
            y += 46
        y += 20

    # Sticky content header
    rr(d, 440, 170, 1064, 84, 24, fill=P["panel"], outline=P["line2"])
    rr(d, 470, 194, 54, 36, 10, fill=P["soft2"], outline=None)
    skeleton(d, 548, 190, [310, 220], height=12, gap=24, fill=P["muted"])
    progress(d, 1254, 206, 180, 12, 0.52)

    # Hero lesson card
    draw_shadow(img, 462, 298, 948, 186, r=32, alpha=22, blur=18, offset=8)
    card(d, 462, 298, 948, 186, 32, fill=P["canvas"])
    rr(d, 498, 334, 138, 18, 9, fill=P["soft2"], outline=None)
    skeleton(d, 498, 378, [448, 602, 332], height=14, gap=26, fill=P["muted"])
    rr(d, 1210, 336, 126, 96, 28, fill=P["soft"], outline=P["line2"])
    circle(d, 1273, 384, 28, fill=P["accent2"])

    # Objective cards
    for i in range(4):
        x = 462 + i * 237
        card(d, x, 526, 208, 132, 22, fill=P["panel"])
        circle(d, x + 36, 562, 16, fill=P["accent2"])
        skeleton(d, x + 28, 604, [136, 112, 74], height=9, gap=18, fill=P["muted"])

    # Article and examples
    card(d, 462, 706, 442, 170, 24, fill=P["canvas"])
    skeleton(d, 498, 740, [300, 356, 318, 240, 346], height=11, gap=22, fill=P["muted"])
    card(d, 940, 706, 470, 170, 24, fill=P["soft"])
    for i, w in enumerate([260, 310, 220, 346, 286]):
        rr(d, 980, 742 + i * 22, w, 8, 4, fill=P["accent2"], outline=None)
    card(d, 982, 840, 146, 16, 8, fill=P["canvas"], outline=None)
    card(d, 1156, 840, 146, 16, 8, fill=P["canvas"], outline=None)

    return save(img, "gambar-4-7-materi-low-fidelity.png")


def editor_lines(draw, x, y, count, max_w=410, dark=True):
    for i in range(count):
        yy = y + i * 22
        line(draw, x, yy + 5, x + 18, yy + 5, fill=P["dark_line"] if dark else P["line2"], width=2)
        w = [250, 338, 188, 424, 294, 360, 160, 390][i % 8]
        rr(draw, x + 40, yy, min(w, max_w), 8, 4, fill=P["dark_soft"] if dark else P["muted"], outline=None)


def draw_practice():
    img = make_canvas(P["dark_bg"])
    browser_shell(img, dark=True)
    d = ImageDraw.Draw(img)

    # App top bar
    rr(d, 84, 120, 1420, 48, 0, fill=P["dark_panel"], outline=None)
    line(d, 84, 168, 1504, 168, fill=P["dark_line"], width=1)
    circle(d, 118, 144, 10, fill=P["dark_line"])
    rr(d, 150, 134, 260, 16, 8, fill=P["dark_soft"], outline=None)
    for i in range(3):
        rr(d, 630 + i * 132, 132, 104, 20, 10, fill=P["dark_panel2"], outline=P["dark_line"])
    rr(d, 1322, 130, 134, 26, 13, fill=P["blue"], outline=None)

    # Task sidebar
    rr(d, 84, 168, 328, 746, 0, fill=P["dark_panel"], outline=P["dark_line"])
    rr(d, 112, 198, 126, 14, 7, fill=P["dark_soft"], outline=None)
    rr(d, 326, 196, 46, 20, 10, fill=P["dark_panel2"], outline=P["dark_line"])
    y = 246
    for i in range(8):
        active = i == 2
        rr(d, 104, y, 284, 52, 12, fill=P["dark_panel2"] if active else P["dark_panel"], outline=P["dark_line"])
        if active:
            rect(d, 104, y, 4, 52, fill=P["blue"])
        circle(d, 130, y + 26, 9, fill=P["blue"] if i < 2 else P["dark_line"])
        skeleton(d, 154, y + 16, [148, 92], height=8, gap=16, fill=P["dark_soft"])
        y += 66
    rr(d, 112, 812, 248, 50, 14, fill=P["dark_panel2"], outline=P["dark_line"])
    progress(d, 132, 834, 204, 8, 0.38, fill=P["blue"], bg=P["dark_line"])

    # Editor
    rr(d, 412, 168, 598, 746, 0, fill="#1c202a", outline=P["dark_line"])
    rr(d, 412, 168, 598, 38, 0, fill=P["dark_panel"], outline=None)
    rr(d, 434, 178, 138, 24, 6, fill="#1c202a", outline=P["dark_line"])
    editor_lines(d, 448, 246, 23, max_w=456, dark=True)
    rr(d, 442, 816, 520, 56, 12, fill=P["dark_panel"], outline=P["dark_line"])
    for i in range(3):
        rr(d, 466, 836 + i * 14, [220, 312, 174][i], 6, 3, fill=P["dark_soft"], outline=None)

    # Preview and terminal panel
    rr(d, 1010, 168, 494, 746, 0, fill=P["dark_panel"], outline=P["dark_line"])
    rr(d, 1010, 168, 494, 38, 0, fill=P["dark_panel2"], outline=None)
    rr(d, 1036, 180, 120, 14, 7, fill=P["dark_soft"], outline=None)
    rr(d, 1188, 180, 120, 14, 7, fill=P["dark_line"], outline=None)
    rr(d, 1038, 236, 438, 308, 20, fill="#f4f6fa", outline=P["dark_line"])
    rr(d, 1070, 266, 180, 20, 10, fill=P["line"], outline=None)
    rr(d, 1070, 310, 242, 96, 20, fill=P["soft2"], outline=P["line2"])
    circle(d, 1114, 358, 26, fill=P["muted"])
    skeleton(d, 1158, 336, [116, 156, 104], height=8, gap=18, fill=P["muted"])
    for i in range(3):
        rr(d, 1070 + i * 128, 442, 102, 58, 14, fill=P["canvas"], outline=P["line2"])
        skeleton(d, 1088 + i * 128, 464, [58, 72], height=6, gap=13, fill=P["muted"])

    rr(d, 1038, 584, 438, 246, 18, fill="#171b24", outline=P["dark_line"])
    for i in range(9):
        rr(d, 1064, 616 + i * 20, [280, 210, 328, 186, 244, 318, 220, 346, 168][i], 6, 3, fill=P["dark_soft"], outline=None)
    rr(d, 1190, 858, 118, 28, 14, fill=P["dark_panel2"], outline=P["dark_line"])
    rr(d, 1324, 858, 118, 28, 14, fill=P["blue"], outline=None)

    return save(img, "gambar-4-8-kegiatan-praktik-low-fidelity.png")


def draw_user_dashboard():
    img = make_canvas()
    browser_shell(img)
    d = ImageDraw.Draw(img)
    top_nav(d)

    # Sidebar dashboard pengguna
    rr(d, 84, 170, 258, 744, 26, fill=P["panel"], outline=P["line2"])
    circle(d, 126, 222, 22, fill=P["accent2"])
    skeleton(d, 164, 204, [112, 82], height=10, gap=20, fill=P["muted"])
    rr(d, 112, 282, 198, 42, 14, fill=P["ink"], outline=None)
    circle(d, 136, 303, 8, fill=P["canvas"])
    skeleton(d, 154, 296, [92, 60], height=7, gap=13, fill=P["canvas"], alpha=235)
    for i in range(4):
        y = 344 + i * 54
        rr(d, 112, y, 198, 38, 14, fill=P["canvas"], outline=P["line2"])
        circle(d, 136, y + 19, 7, fill=P["muted"])
        skeleton(d, 156, y + 13, [[96, 122, 78, 110][i]], height=8, fill=P["muted"])
    rr(d, 112, 618, 198, 100, 18, fill=P["soft"], outline=P["line2"])
    progress(d, 136, 668, 150, 10, 0.58)
    rr(d, 112, 756, 198, 84, 18, fill=P["canvas"], outline=P["line2"])
    skeleton(d, 136, 782, [118, 82, 138], height=8, gap=17, fill=P["muted"])

    # Main header and profile badge
    skeleton(d, 390, 184, [120, 272, 210], height=12, gap=28, fill=P["muted"])
    rr(d, 1292, 182, 172, 44, 22, fill=P["canvas"], outline=P["line2"])
    circle(d, 1320, 204, 14, fill=P["accent2"])
    skeleton(d, 1348, 197, [78], height=10, fill=P["muted"])

    # Overall progress card
    draw_shadow(img, 1140, 252, 324, 164, r=28, alpha=22, blur=16, offset=6)
    card(d, 1140, 252, 324, 164, 28, fill=P["canvas"])
    skeleton(d, 1170, 286, [110, 72], height=10, gap=22, fill=P["muted"])
    circle(d, 1388, 314, 28, fill=P["soft2"])
    progress(d, 1170, 362, 248, 12, 0.66)
    skeleton(d, 1170, 386, [52, 104, 44], height=7, gap=0, fill=P["muted"])

    # Insight learning analytics card
    draw_shadow(img, 390, 252, 720, 164, r=30, alpha=20, blur=16, offset=6)
    card(d, 390, 252, 720, 164, 30, fill=P["canvas"])
    rr(d, 422, 284, 164, 18, 9, fill=P["soft2"], outline=None)
    skeleton(d, 422, 324, [360, 486], height=12, gap=24, fill=P["muted"])
    progress(d, 422, 378, 428, 11, 0.64)
    for i in range(3):
        x = 884 + i * 68
        rr(d, x, 290, 52, 84, 16, fill=P["soft"], outline=P["line2"])
        circle(d, x + 26, 314, 8, fill=P["accent2"])
        skeleton(d, x + 14, 344, [24, 32], height=6, gap=13, fill=P["muted"])

    # Progress detail cards
    card(d, 390, 452, 316, 176, 24, fill=P["canvas"])
    circle(d, 432, 492, 24, fill=P["soft2"])
    skeleton(d, 474, 476, [132, 92], height=10, gap=22, fill=P["muted"])
    progress(d, 424, 554, 236, 12, 0.62)
    for i in range(3):
        circle(d, 430 + i * 72, 590, 8, fill=P["accent2"])
        rr(d, 446 + i * 72, 586, 34, 8, 4, fill=P["muted"], outline=None)

    card(d, 736, 452, 728, 176, 24, fill=P["canvas"])
    for i in range(4):
        x = 770 + i * 168
        circle(d, x, 496, 18, fill=P["accent2"])
        skeleton(d, x + 34, 478, [82, 120], height=10, gap=20, fill=P["muted"])
        progress(d, x, 560, 126, 9, [0.7, 0.42, 0.76, 0.54][i])

    # Four metric cards
    for i in range(4):
        x = 390 + i * 268
        card(d, x, 660, 238, 126, 22, fill=P["canvas"])
        circle(d, x + 34, 696, 16, fill=P["accent2"])
        skeleton(d, x + 62, 684, [92, 64], height=9, gap=20, fill=P["muted"])
        rr(d, x + 30, 746, 92, 16, 8, fill=P["ink"] if i == 0 else P["soft2"], outline=None)
        progress(d, x + 138, 750, 70, 7, [0.72, 0.46, 0.58, 0.82][i])

    # Chart panel
    card(d, 390, 824, 646, 108, 24, fill=P["canvas"])
    skeleton(d, 426, 850, [164, 104], height=10, gap=21, fill=P["muted"])
    chart_x, chart_y = 650, 852
    for i in range(4):
        line(d, chart_x, chart_y + i * 18, chart_x + 330, chart_y + i * 18, fill=P["line2"], width=1)
    points_a = [(chart_x + 8, chart_y + 58), (chart_x + 72, chart_y + 40), (chart_x + 136, chart_y + 46), (chart_x + 200, chart_y + 26), (chart_x + 264, chart_y + 34), (chart_x + 324, chart_y + 18)]
    points_b = [(chart_x + 8, chart_y + 70), (chart_x + 72, chart_y + 56), (chart_x + 136, chart_y + 64), (chart_x + 200, chart_y + 44), (chart_x + 264, chart_y + 52), (chart_x + 324, chart_y + 36)]
    d.line([(sc(x), sc(y)) for x, y in points_a], fill=rgba(P["ink"]), width=sc(4), joint="curve")
    d.line([(sc(x), sc(y)) for x, y in points_b], fill=rgba(P["accent2"]), width=sc(4), joint="curve")
    for x, y in points_a + points_b:
        circle(d, x, y, 4, fill=P["canvas"], outline=P["muted"], width=2)

    # Activity composition and log column
    card(d, 1066, 824, 398, 108, 24, fill=P["canvas"])
    d.pieslice(box(1100, 846, 74, 74), start=0, end=140, fill=rgba(P["ink"]))
    d.pieslice(box(1100, 846, 74, 74), start=140, end=258, fill=rgba(P["accent2"]))
    d.pieslice(box(1100, 846, 74, 74), start=258, end=360, fill=rgba(P["muted"]))
    circle(d, 1137, 883, 18, fill=P["canvas"])
    for i in range(3):
        skeleton(d, 1198, 850 + i * 23, [148, 88], height=8, gap=14, fill=P["muted"])

    return save(img, "gambar-4-9-dashboard-pengguna-low-fidelity.png")


def save(img, filename):
    img = img.resize((W, H), Image.Resampling.LANCZOS).convert("RGB")
    path = OUT_DIR / filename
    img.save(path, "PNG", optimize=True)
    return path


if __name__ == "__main__":
    paths = [draw_home(), draw_material(), draw_practice(), draw_user_dashboard()]
    for path in paths:
        print(path)
