<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["them_sach"])) {
    $tuaSach = $_POST["TuaSach"];
    $namXuatBan = $_POST["NamXuatBan"];
    $soLuongTon = $_POST["SoLuongTon"];
    $maTheLoai = $_POST["MaTheLoai"];

    $resultMessage = '';
    $sql = "{call sp_ThemSach(?, ?, ?, ?, ?)}";
    $params = array(
        array($tuaSach, SQLSRV_PARAM_IN),
        array($namXuatBan, SQLSRV_PARAM_IN),
        array($soLuongTon, SQLSRV_PARAM_IN),
        array($maTheLoai, SQLSRV_PARAM_IN),
        array(&$resultMessage, SQLSRV_PARAM_OUT, SQLSRV_PHPTYPE_STRING('UTF-8'), SQLSRV_SQLTYPE_NVARCHAR(4000))
    );
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt) {
        sqlsrv_free_stmt($stmt);
        $thongBao = trim($resultMessage) !== '' ? $resultMessage : 'Thêm sách thành công!';
        echo "<script>alert('" . addslashes($thongBao) . "'); window.location='sach.php';</script>";
    } else {
        $errors = sqlsrv_errors();
        $thongBao = 'Lỗi: Không thể thêm sách';
        if (!empty($errors) && isset($errors[0]['message'])) {
            $thongBao = $errors[0]['message'];
        }
        echo "<script>alert('" . addslashes($thongBao) . "');</script>";
    }
}

$sqlSach = "SELECT s.MaSach, s.TuaSach, s.NamXuatBan, s.SoLuongTon, tl.TenTheLoai
            FROM Sach s JOIN TheLoai tl ON s.MaTheLoai = tl.MaTheLoai";
$stmtSach = sqlsrv_query($conn, $sqlSach);

$sqlTheLoai = "SELECT MaTheLoai, TenTheLoai FROM TheLoai";
$stmtTheLoai = sqlsrv_query($conn, $sqlTheLoai);
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Quản lý sách</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="page-shell">
<section class="hero">
    <h1>📖 Quản lý sách</h1>
    <p>Xem danh sách sách theo thể loại và thêm sách mới vào thư viện.</p>
</section>

<section class="panel">
<h2>Danh sách sách</h2>
<div class="table-wrap">
<table>
    <tr><th>Mã sách</th><th>Tựa sách</th><th>Năm</th><th>Số lượng tồn</th><th>Thể loại</th></tr>
    <?php while($row = sqlsrv_fetch_array($stmtSach, SQLSRV_FETCH_ASSOC)): ?>
    <tr>
        <td><?= $row['MaSach'] ?></td>
        <td><?= $row['TuaSach'] ?></td>
        <td><?= $row['NamXuatBan'] ?></td>
        <td><?= $row['SoLuongTon'] ?></td>
        <td><?= $row['TenTheLoai'] ?></td>
    </tr>
    <?php endwhile; ?>
</table>
</div>
</section>

<section class="panel">
<h2>➕ Thêm sách mới</h2>
<form method="POST">
    <label>Tựa sách:</label> <input type="text" name="TuaSach" required><br><br>
    <label>Năm xuất bản:</label> <input type="number" name="NamXuatBan" required><br><br>
    <label>Số lượng tồn:</label> <input type="number" name="SoLuongTon" required><br><br>
    <label>Thể loại:</label>
    <select name="MaTheLoai" required>
        <option value="">Chọn thể loại</option>
        <?php while($row = sqlsrv_fetch_array($stmtTheLoai, SQLSRV_FETCH_ASSOC)): ?>
        <option value="<?= $row['MaTheLoai'] ?>"><?= $row['TenTheLoai'] ?></option>
        <?php endwhile; ?>
    </select><br><br>
    <input type="submit" name="them_sach" value="Thêm sách" class="btn">
</form>
</section>

<a class="back-link" href="index.php">← Về trang chủ</a>
</div>
</body>
</html>
<?php
sqlsrv_free_stmt($stmtSach);
sqlsrv_free_stmt($stmtTheLoai);
sqlsrv_close($conn);
?>