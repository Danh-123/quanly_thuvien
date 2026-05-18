<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $maPhieuMuon = $_POST["MaPhieuMuon"];
    $ngayTraThucTe = date("Y-m-d");

    $resultMessage = '';
    $sql = "{call sp_TraSach(?, ?, ?)}";
    $params = array(
        array($maPhieuMuon, SQLSRV_PARAM_IN),
        array($ngayTraThucTe, SQLSRV_PARAM_IN),
        array(&$resultMessage, SQLSRV_PARAM_OUT, SQLSRV_PHPTYPE_STRING('UTF-8'), SQLSRV_SQLTYPE_NVARCHAR(4000))
    );
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt) {
        sqlsrv_free_stmt($stmt);
        $thongBao = trim($resultMessage) !== '' ? $resultMessage : 'Trả sách thành công!';
        echo "<script>alert('" . addslashes($thongBao) . "'); window.location='tra.php';</script>";
    } else {
        $errors = sqlsrv_errors();
        $thongBao = 'Phiếu mượn không hợp lệ hoặc đã trả rồi!';
        if (!empty($errors) && isset($errors[0]['message'])) {
            $thongBao = $errors[0]['message'];
        }
        echo "<script>alert('" . addslashes($thongBao) . "');</script>";
    }
}

$sqlMuon = "SELECT m.MaPhieuMuon, b.HoTen, s.TuaSach,
            CONVERT(VARCHAR(10), m.NgayMuon, 23) AS NgayMuon,
            CONVERT(VARCHAR(10), m.NgayHenTra, 23) AS NgayHenTra
            FROM MuonTra m
            JOIN BanDoc b ON m.MaBanDoc = b.MaBanDoc
            JOIN Sach s ON m.MaSach = s.MaSach
            WHERE m.TrangThai = N'dang_muon'";
$stmtMuon = sqlsrv_query($conn, $sqlMuon);
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Trả sách</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="page-shell">
<section class="hero">
    <h1>🔄 Lập phiếu trả sách</h1>
    <p>Xác nhận trả sách và tự động tính tiền phạt nếu quá hạn.</p>
</section>

<section class="panel">
<form method="POST">
    <label>Phiếu mượn chưa trả:</label>
    <select name="MaPhieuMuon" required>
           <option value="">Chọn phiếu mượn</option>
        <?php while($row = sqlsrv_fetch_array($stmtMuon, SQLSRV_FETCH_ASSOC)): ?>
        <option value="<?= $row['MaPhieuMuon'] ?>">
            <?= $row['HoTen'] ?> - <?= $row['TuaSach'] ?> (Hẹn trả: <?= $row['NgayHenTra'] ?>)
        </option>
        <?php endwhile; ?>
    </select><br><br>
    <input type="submit" value="Trả sách" class="btn">
</form>
</section>

<a class="back-link" href="index.php">← Về trang chủ</a>
</div>
</body>
</html>
<?php
sqlsrv_free_stmt($stmtMuon);
sqlsrv_close($conn);
?>