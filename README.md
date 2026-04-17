# Quản Lý Mượn Trả Sách Thư Viện

## 1. Mô tả đề tài
Đồ án môn Hệ quản trị cơ sở dữ liệu với mục tiêu xây dựng hệ thống quản lý thư viện bằng PHP + SQL Server.

Hệ thống gồm các chức năng:
- Quản lý sách
- Quản lý bạn đọc
- Mượn sách
- Trả sách và tính phạt
- Danh sách đang mượn
- Thống kê sách mượn nhiều

## 2. Công nghệ sử dụng
- SQL Server 2022
- Windows Authentication
- PHP 8.x (XAMPP)
- Microsoft ODBC Driver 18 for SQL Server
- PHP extension: sqlsrv

## 3. Cấu trúc CSDL
Tên CSDL: quanly_thuvien

7 bảng:
- TheLoai
- TacGia
- Sach
- Sach_TacGia
- BanDoc
- MuonTra
- Phat

File script tạo CSDL và dữ liệu mẫu:
- library.sql

## 4. Cấu trúc source code
- config.php
- index.php
- sach.php
- bandoc.php
- muon.php
- tra.php
- danhsach_muon.php
- thongke.php
- style.css
- library.sql
- README.md


## 5. Hướng dẫn cài đặt SQL Server + XAMPP
### Bước 1: Cài SQL Server
1. Cài SQL Server (bản Developer/Express đều được).
2. Bật SQL Server service.
3. Cài SSMS để chạy script SQL.

### Bước 2: Cài XAMPP
1. Cài XAMPP (PHP 8.x).
2. Bật Apache trong XAMPP Control Panel.

### Bước 3: Cài driver và extension sqlsrv
1. Cài Microsoft ODBC Driver 18 for SQL Server.
2. Bật extension sqlsrv trong php.ini.
3. Khởi động lại Apache.

## 6. Chạy file library.sql
1. Mở SSMS.
2. Kết nối vào instance SQL Server của máy bạn.
3. Mở file library.sql.
4. Execute để tạo CSDL và dữ liệu mẫu.

## 7. Cấu hình kết nối trong config.php
### Trường hợp chạy local (khuyên dùng khi demo trên máy cá nhân)
Mở file config.php và sửa các biến ở đầu file:

- $db_server = "localhost\\SQLEXPRESS";  <- sửa theo instance máy bạn
- $db_name = "quanly_thuvien";
- $db_driver = "ODBC Driver 18 for SQL Server";

Ví dụ:
- Máy A: localhost\\SQLEXPRESS
- Máy B: DESKTOP-ABC\\MSSQLSERVER01

### Trường hợp deploy public (Azure App Service, máy khác)
Không sửa code, chỉ cần cấu hình Environment Variables:

- DB_SERVER
- DB_NAME
- DB_DRIVER
- DB_USER
- DB_PASSWORD
- DB_ENCRYPT (true/false)
- DB_TRUST_SERVER_CERT (true/false)

Gợi ý cho Azure SQL:
- DB_SERVER = your-server.database.windows.net
- DB_NAME = quanly_thuvien
- DB_DRIVER = ODBC Driver 18 for SQL Server
- DB_USER = <sql_login>
- DB_PASSWORD = <sql_password>
- DB_ENCRYPT = true
- DB_TRUST_SERVER_CERT = false

Sau khi cấu hình, toàn bộ các trang sẽ dùng chung kết nối qua config.php.

## 8. Chạy ứng dụng
1. Chép source vào thư mục:
   C:\\xampp\\htdocs\\quanly_thuvien
2. Mở trình duyệt:
   http://localhost/quanly_thuvien/index.php

## 8.1. Deploy public nhanh (tham khảo)
Nếu deploy lên App Service:
1. Tạo App Service (Windows + PHP).
2. Deploy source từ GitHub.
3. Vào App Service -> Configuration -> Application settings.
4. Thêm các biến DB_* như mục 7.
5. Restart App Service và kiểm tra website.

## 9. Push source lên GitHub
### Cách 1: Upload trực tiếp trên web GitHub
1. Tạo repository mới trên GitHub.
2. Bấm Add file -> Upload files.
3. Kéo thả toàn bộ source.
4. Commit changes.

### Cách 2: Dùng lệnh git
1. Mở terminal tại thư mục dự án.
2. Chạy các lệnh:

git init
git add .
git commit -m "Initial commit - Library management project"
git branch -M main
git remote add origin https://github.com/<username>/<repo>.git
git push -u origin main

## 10. Nén source code để nộp
1. Chuột phải thư mục quanly_thuvien.
2. Chọn Send to -> Compressed (zipped) folder.
3. Đặt tên file zip gợi ý: 22110xxx_QuanLyThuVien.zip

## 11. Checklist hoàn thiện đồ án
- [ ] Source code chạy được trên localhost
- [ ] Đủ 7 file chức năng PHP
- [ ] Có file config.php
- [ ] Có file library.sql
- [ ] Có báo cáo Word/PDF
- [ ] Có nhật ký AI
- [ ] Có ERD (draw.io hoặc PNG/PDF)
- [ ] Đã push lên GitHub
- [ ] Có link Google Drive
- [ ] Đã kiểm tra quyền xem của các link

## 12. Lưu ý quyền truy cập SQL Server (Windows Authentication)
Vì hệ thống dùng Windows Authentication, tài khoản Windows của người chạy ứng dụng phải có quyền truy cập SQL Server.

Cách kiểm tra nhanh:
1. Mở SSMS bằng chính tài khoản Windows đang dùng.
2. Nếu đăng nhập được instance thì tài khoản đã có quyền cơ bản.

Nếu chưa có quyền, cấp quyền như sau (dành cho máy test):
1. Trong SSMS: Security -> Logins -> New Login.
2. Chọn tài khoản Windows (ví dụ: DESKTOP-ABC\\Student).
3. User Mapping -> tick CSDL quanly_thuvien.
4. Chọn role db_datareader và db_datawriter.
5. OK.