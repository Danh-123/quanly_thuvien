<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $maBanDoc = $_POST["MaBanDoc"];
    $maSach = $_POST["MaSach"];
    $ngayMuon = date("Y-m-d");
    $ngayHenTra = date("Y-m-d", strtotime("+14 days"));

    $sql = "INSERT INTO MuonTra (MaBanDoc, MaSach, NgayMuon, NgayHenTra, TrangThai) VALUES (?, ?, ?, ?, N'dang_muon')";
    $params = array($maBanDoc, $maSach, $ngayMuon, $ngayHenTra);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt) {
        $sqlUpdate = "UPDATE Sach SET SoLuongTon = SoLuongTon - 1 WHERE MaSach = ?";
        sqlsrv_query($conn, $sqlUpdate, array($maSach));
        echo "<script>alert('Mượn sách thành công! Ngày hẹn trả: $ngayHenTra'); window.location='muon.php';</script>";
    } else {
        echo "<script>alert('Lỗi: Không thể mượn sách');</script>";
    }
}

$sqlBanDoc = "SELECT MaBanDoc, HoTen FROM BanDoc";
$stmtBanDoc = sqlsrv_query($conn, $sqlBanDoc);

$sqlSach = "SELECT MaSach, TuaSach FROM Sach WHERE SoLuongTon > 0";
$stmtSach = sqlsrv_query($conn, $sqlSach);
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Mượn sách</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="page-shell">
<section class="hero">
    <h1>📝 Lập phiếu mượn sách</h1>
    <p>Tạo phiếu mượn mới cho bạn đọc và cập nhật số lượng tồn tự động.</p>
</section>

<section class="panel">
<form method="POST">
    <label>Bạn đọc:</label>
    <select name="MaBanDoc" required>
        <option value="">Chọn bạn đọc</option>
        <?php while($row = sqlsrv_fetch_array($stmtBanDoc, SQLSRV_FETCH_ASSOC)): ?>
        <option value="<?= $row['MaBanDoc'] ?>"><?= $row['HoTen'] ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Sách (còn trong thư viện):</label>
    <select name="MaSach" required>
        <option value="">Chọn sách</option>
        <?php while($row = sqlsrv_fetch_array($stmtSach, SQLSRV_FETCH_ASSOC)): ?>
        <option value="<?= $row['MaSach'] ?>"><?= $row['TuaSach'] ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <input type="submit" value="Mượn sách" class="btn">
</form>
</section>

<a class="back-link" href="index.php">← Về trang chủ</a>
</div>
</body>
</html>
<?php
sqlsrv_free_stmt($stmtBanDoc);
sqlsrv_free_stmt($stmtSach);
sqlsrv_close($conn);
?>