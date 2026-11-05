# PHPLab02 — Workshop Booking Portal
 
Ứng dụng PHP thuần (không database, không framework): xem workshop, tìm kiếm bằng GET,
đăng ký bằng POST, xem chi tiết và chỉnh sửa booking. Dữ liệu booking lưu trong PHP Session.
 
## Cách chạy
1. Cài PHP 8.1+ (hoặc XAMPP).
2. Mở terminal tại thư mục project và chạy: php -S localhost:8000
3. Mở trình duyệt: http://localhost:8000/
 
## Chức năng
- Catalog + tìm kiếm / lọc category (GET)
- Đăng ký workshop (POST + validation + CSRF)
- My Bookings (Session) + Clear session
- View Booking Detail (booking_detail.php?ref=...)
- Edit Booking (booking_edit.php?ref=...)
- Theme light/dark bằng Cookie
 
## Cấu trúc thư mục
- lib/ — bootstrap.php, functions.php
- data/ — workshops.php
- assets/ — style.css
- *.php — các trang
 
## Hạn chế
Không có database, không đăng nhập thật; dữ liệu mất khi hết phiên.