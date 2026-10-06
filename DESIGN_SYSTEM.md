# USCC Webmail — Design System

Design system cho giao diện Roundcube (skin **Elastic**) dựa trên logo `source_code/uscc_logo.png`.
Đây là nguồn chân lý cho mọi quyết định màu sắc / logo. Khi đổi giá trị, sửa ở đây trước rồi đồng bộ vào
`skin-overrides/_variables.less`.

## 1. Nguyên tắc

1. **Navy là màu của hành động và cấu trúc** (nút chính, liên kết, focus, thanh điều hướng).
2. **Vàng là điểm nhấn hiếm**: viền, icon, chấm trạng thái. Không dùng vàng làm màu chữ trên nền trắng (contrast ≈ 2:1).
3. **Hồng chỉ là nền trang trí** (trang đăng nhập, empty state). Không dùng cho thành phần tương tác.
4. Giữ nguyên bố cục, kích thước, icon của Elastic. Chỉ đổi màu, logo và vài chi tiết nhận diện.
5. Chữ phải đạt WCAG AA (≥ 4.5:1 chữ thường, ≥ 3:1 chữ lớn / icon).

## 2. Phân tích logo

Logo tròn: đầu ngựa line-art và chữ Hán 象 màu navy rất đậm, nền trắng, viền ngoài vàng gradient kim loại, nền vuông bên ngoài là gradient trắng → hồng.

Màu lấy mẫu trực tiếp từ ảnh:

| Thành phần | Mẫu |
|---|---|
| Navy (đầu ngựa, chữ 象) | `#031140` |
| Vàng viền (tối → sáng) | `#dfb21b` → `#f3cd50` → `#fcfa89` |
| Nền hồng (trên → dưới) | `#f9c2bf` → `#ed9c9b` |

## 3. Bảng màu

### 3.1 Thương hiệu

| Token | Hex | Dùng cho |
|---|---|---|
| `navy-900` | `#031140` | Taskmenu, chữ tiêu đề, logo |
| `navy-800` | `#0A1F5C` | Hover taskmenu, header popover mobile |
| `navy-700` | `#13307F` | Nút primary khi hover |
| `navy-600` | `#1E4399` | **Màu chính** (`@color-main`): nút primary, focus, badge, link |
| `navy-400` | `#6C8CE0` | Accent trên nền tối (dark mode) |
| `navy-100` | `#E4E9F5` | Nền dòng được chọn |
| `navy-50`  | `#F3F5FB` | Nền header / toolbar |
| `gold-500` | `#DFB21B` | Chấm unread, viền nhấn, vạch chọn |
| `gold-300` | `#F3CD50` | Icon action trên taskmenu |
| `gold-200` | `#FCFA89` | Highlight nhẹ |
| `rose-200` | `#F9C2BF` | Gradient nền login (đầu) |
| `rose-300` | `#ED9C9B` | Gradient nền login (cuối) |

### 3.2 Trạng thái

Giữ nguyên Elastic: error `#ff5552`, success `#41b849`. Warning đổi sang `gold-500`.

### 3.3 Contrast (xấp xỉ, kiểm lại bằng DevTools khi triển khai)

| Cặp | Tỉ lệ | Đánh giá |
|---|---|---|
| `navy-900` / trắng | ≈ 17:1 | AAA |
| `navy-600` / trắng | ≈ 8:1 | AAA |
| trắng / `navy-600` (nút primary) | ≈ 8:1 | AAA |
| `gold-500` / `navy-900` | ≈ 8:1 | AA — chỉ dùng cho icon/viền trên nền navy |
| `gold-500` / trắng | ≈ 2:1 | **Không đạt — không dùng làm chữ** |

## 4. Ánh xạ token → biến Elastic

Nguồn biến: `source_code/skins/elastic/styles/colors.less`. Override qua `_variables.less`, không sửa `colors.less`.

| Biến Elastic | Giá trị |
|---|---|
| `@color-main` | `navy-600` |
| `@color-main-dark` | `navy-800` |
| `@color-link` / `@color-link-hover` | `navy-600` / `navy-700` |
| `@color-taskmenu-background` | `navy-900` |
| `@color-taskmenu-button-action` (+ `-hover`) | `gold-300` |
| `@color-taskmenu-button-selected-background` | `navy-800` |
| `@color-layout-header-background` | `navy-50` |
| `@color-list-selected-background` | `navy-100` |
| `@color-list-unread-status` | `gold-500` |
| `@color-warning` | `gold-500` |
| `@color-btn-primary-background` | `navy-600` (kế thừa từ `@color-main`) |
| `@color-layout-header` | `navy-900` |
| `@color-blockquote-0` | `navy-600` (mặc định `darken(main, 30%)` gần đen) |

`@color-black` **không** override: các màu viền/nền trung tính dẫn xuất từ nó bằng `lighten`/`tint` sẽ thành xanh bão hòa.

Quy tắc không biểu diễn được bằng biến nằm trong `skin-overrides/_brand.less` (vạch vàng taskmenu, nền gradient + kích thước logo trang login).

## 5. Dark mode

Các biến `@color-dark-*` của Elastic dẫn xuất từ `darken(@color-main, …)`. Với navy, kết quả gần như đen,
nên **phải gán tường minh**:

| Biến | Giá trị |
|---|---|
| `@color-dark-background` | `#0B1226` |
| `@color-dark-font` | `#C9D3EA` |
| `@color-dark-border` | `#2A3760` |
| `@color-dark-main` | `navy-400` |
| `@color-dark-list-selected` | `navy-400` |
| `@color-dark-list-selected-background` | `#16213F` |
| `@color-dark-input-border-focus` | `navy-400` |
| `@color-dark-scrollbar-thumb` | `#3A4A80` |
| `@color-dark-btn-primary-background` | `navy-600` |
| `@color-dark-list-border` | `#1B2748` |
| `@color-dark-input-border` | `#5A6BA0` |
| `@color-dark-popover-background` | `#070C1C` |

## 6. Typography

Giữ Roboto (đã bundle trong `skins/elastic/fonts`), cỡ gốc 14px (`@page-font-size`). Không thêm webfont.

## 7. Logo

PNG gốc có nền vuông gradient hồng nên **không dùng trực tiếp trong UI**. Các biến thể được tạo sẵn trong `skin-overrides/images/` và cài vào `skins/elastic/images/` (Roundcube chỉ phục vụ logo qua `static.php` từ thư mục skin, không đọc từ `public_html`):

| File | Kích thước | Dùng cho |
|---|---|---|
| `uscc_login.png` | 400×400, nền trong suốt, cắt tròn | Trang đăng nhập (hiển thị ~120px) |
| `uscc_small.png` | 128×128, nền trong suốt, cắt tròn | Logo nhỏ (màn hình hẹp) |
| `uscc_dark.svg` | vector, tô trắng (dùng cho cả `dark` và `small-dark`) | Logo dark mode (bản SVG gốc là đơn sắc, không có vòng vàng) |

**Watermark** (khung giữa màn hình khi chưa chọn thư) là trang HTML riêng `skins/elastic/watermark.html`, tự nhúng CSS và không nạp `styles.css`, nên phải sửa trực tiếp file này (bản mới: `skin-overrides/watermark.html`): logo 150px, mờ ~85% (light, lớp phủ trắng) / ~80% (dark, lớp phủ `#0b1226`).

Quy tắc: kích thước tối thiểu 24px; chừa khoảng trống quanh logo ≥ 1/4 đường kính; không kéo giãn, không đổi màu vòng vàng.

## 8. Thành phần

| Thành phần | Quy định |
|---|---|
| Taskmenu | Nền `navy-900`; mục đang chọn nền `navy-800` + vạch vàng 3px bên trái |
| Nút | Primary: `navy-600` (hover `navy-700`); secondary: xám trung tính; danger: đỏ |
| Danh sách thư | Chọn = `navy-100`; chưa đọc = chữ đậm + chấm `gold-500` |
| Form | Focus: viền `navy-600` + shadow 25% cùng màu |
| Badge | Nền `navy-600`, chữ trắng |
| Trang login | Nền gradient `#fff → rose-200`, thẻ trắng, logo tròn |

## 9. Phạm vi và quy trình triển khai

| Việc | Vị trí | Ghi chú |
|---|---|---|
| Override màu | `skins/elastic/styles/_variables.less` | Hook có sẵn: `variables.less` cuối file `@import (reference, optional) "_variables"` |
| Biên dịch | `skins/elastic/Makefile` (`make css`) | Cần Node + `less` + `less-plugin-clean-css` |
| Logo | `skins/elastic/images/uscc_*` + `$config['skin_logo']` trong `config.inc.php` | Đường dẫn `/images/...` được phân giải trong thư mục skin; config chỉ bật khi file đã tồn tại |
| Cache | Hard-refresh sau khi build | |

`skin-overrides/install.sh` (chạy bằng `sudo`) sao lưu, cài `_variables.less` + `_brand.less` + favicon vào skin, sửa `meta.json` và build lại CSS bằng Node trong Docker.

## 10. Còn hard-code màu cũ (cần kiểm tra sau build)

- `skins/elastic/deps/bootstrap.min.css` (primary mặc định của Bootstrap)
- `skins/elastic/images/logo.svg`, `favicon.ico` (nằm trong thư mục root-owned)
- Một số hex lẻ trong `widgets/*.less`, `styles.less` (`#9804`, `#9727`, `#6797`…) — rgba mờ, kiểm tra bằng mắt
- jQuery UI theme `bootstrap`
