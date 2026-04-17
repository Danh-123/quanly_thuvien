<?php
require_once 'config.php';

$sql = "SELECT MaBanDoc, HoTen, Email, SoDienThoai, DiaChi, CONVERT(VARCHAR(10), NgayDangKy, 103) as NgayDangKy FROM BanDoc";
$stmt = sqlsrv_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Quản lý bạn đọc</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="page-shell">
<section class="hero">
    <h1>👥 Quản lý bạn đọc</h1>
    <p>Danh sách bạn đọc đã đăng ký trong hệ thống thư viện.</p>
</section>

<section class="panel">
<h2>Danh sách bạn đọc</h2>
<div class="table-wrap">
<table>
    <tr><th>Mã bạn đọc</th><th>Họ tên</th><th>Email</th><th>Số điện thoại</th><th>Địa chỉ</th><th>Ngày đăng ký</th></tr>
    <?php while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
    <tr>
        <td><?= $row['MaBanDoc'] ?></td>
        <td><?= $row['HoTen'] ?></td>
        <td><?= $row['Email'] ?></td>
        <td><?= $row['SoDienThoai'] ?></td>
        <td><?= $row['DiaChi'] ?></td>
        <td><?= $row['NgayDangKy'] ?></td>
    </tr>
    <?php endwhile; ?>
</table>
</div>
</section>

<a class="back-link" href="index.php">← Về trang chủ</a>
</div>
</body>
</html>
<?php
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>