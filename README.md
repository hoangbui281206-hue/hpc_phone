# HPC PHONE - Website bán điện thoại

Dự án học tập WEB212 sử dụng PHP OOP, MVC, PDO và MySQL.

## 1. Yêu cầu
- PHP 8.1 trở lên, bật extension pdo_mysql
- MySQL 8 hoặc MariaDB
- Composer
- VS Code

## 2. Cài đặt
1. Giải nén thư mục dự án và mở bằng VS Code.
2. Mở Terminal tại thư mục `hpc_phone`.
3. Chạy `composer install`.
4. Import `database/hpc_phone.sql` vào phpMyAdmin hoặc MySQL.
5. Sao chép `.env.example` thành `.env` và chỉnh thông tin DB.
6. Chạy server: `php -S localhost:8000 -t public`.
7. Mở `http://localhost:8000`.

## 3. Tạo tài khoản Admin
- Đăng ký tài khoản bình thường trên website.
- Trong phpMyAdmin, chạy:
  `UPDATE users SET role='admin' WHERE email='email-cua-ban';`
- Đăng xuất và đăng nhập lại, menu Quản trị sẽ xuất hiện.

## 4. Cấu trúc MVC
- `app/Models`: truy vấn dữ liệu bằng PDO.
- `app/Views`: giao diện.
- `app/Controllers`: nhận request và điều phối.
- `app/Core`: Router, Database, BaseModel, BaseController.
- `public/index.php`: Front Controller.
- `database`: SQL khởi tạo.

## 5. Lưu ý
Đây là phiên bản nền tảng để học và phát triển tiếp. Trước khi triển khai thật cần bổ sung CSRF token cho toàn bộ form, upload ảnh an toàn, phân trang/tìm kiếm, quản lý danh mục và biến thể, voucher, email PHPMailer, kiểm thử bảo mật và cấu hình HTTPS. Không dùng dữ liệu thanh toán từ trình duyệt làm căn cứ xác nhận giao dịch.
