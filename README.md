# MacCodePrettify cho Typecho

**MacCodePrettify** là một plugin nhỏ gọn dành cho mã nguồn Typecho, giúp làm đẹp các khối mã (Code Block) trong bài viết với giao diện cửa sổ Mac OS cực kỳ trực quan và hiện đại.

Được phát triển và tối ưu hóa đặc biệt để khắc phục các lỗi xung đột giao diện trên các theme Typecho phổ biến (như theme Handsome).

---

## ✨ Tính năng nổi bật

* **Giao diện Mac OS:** Dựng sẵn thanh tiêu đề với 3 nút bấm đặc trưng và hiển thị tự động tên ngôn ngữ lập trình ở giữa.
* **Tích hợp PrismJS (Dark Theme):** Tự động highlight cú pháp cho mọi ngôn ngữ phổ biến với giao diện Tomorrow Night (tương tự VS Code).
* **Nút Sao chép tuỳ chỉnh:** Tích hợp nút Copy dạng viền mảnh thanh lịch kèm SVG icon. Bạn có thể tự do thay đổi text (ví dụ: "Sao chép", "Đã lưu!") ngay trong bảng Cài đặt của Typecho.
* **Đánh số dòng (Line Numbers):** Hiển thị số dòng rõ ràng, đã được fix lỗi triệt để không bị đè lên chữ.
* **Tương thích 100% PJAX/AJAX:** Sử dụng `MutationObserver` và Event Listeners để đảm bảo code block mới vẫn hiển thị tuyệt đẹp kể cả khi website load trang ngầm (không cần f5).
* **Trải nghiệm Mobile vuốt mượt mà:** Thanh cuộn ngang tàng hình, chỉ hiển thị tinh tế khi người dùng thực sự di chuột (hover) hoặc vuốt chạm (touch) trên điện thoại.

## 🚀 Hướng dẫn cài đặt

1. Tải toàn bộ mã nguồn về máy.
2. Đảm bảo tên thư mục là `MacCodePrettify`.
3. Upload thư mục `MacCodePrettify` lên host của bạn theo đường dẫn: `usr/plugins/`
4. Đăng nhập vào trang quản trị Typecho.
5. Chuyển đến mục **Bảng điều khiển (Console)** -> **Phần mở rộng (Plugins)**.
6. Tìm **MacCodePrettify** và nhấn **Kích hoạt (Activate)**.
7. Nhấn vào **Cài đặt (Settings)** để tùy chỉnh chữ cho nút sao chép theo ý muốn.

*(Lưu ý: Nếu theme bạn đang dùng đã có tính năng Code Highlight mặc định, hãy tắt nó đi trong Cài đặt Theme để tránh xung đột giao diện).*

## 💡 Cách sử dụng
Trong trình soạn thảo bài viết của Typecho (sử dụng Markdown), bạn chỉ cần bọc đoạn code bằng 3 dấu ngoặc kép ngược (backtick) kèm tên ngôn ngữ. Ví dụ:

\`\`\`php
<?php
echo "Xin chào Typecho!";
?>
\`\`\`

## 📝 Bản quyền & Tác giả
* **Tác giả:** Đặng Minh Đông
* **Bản quyền:** Copyright (c) 2026 by Đông.

Mọi đóng góp và báo lỗi vui lòng tạo Issue trên kho lưu trữ này. Chúc các bạn viết blog vui vẻ!
