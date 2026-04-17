<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $maPhieuMuon = $_POST["MaPhieuMuon"];
    $ngayTraThucTe = date("Y-m-d");

        $sqlGet = "SELECT CONVERT(VARCHAR(10), NgayHenTra, 23) AS NgayHenTra, MaSach FROM MuonTra WHERE MaPhieuMuon = ? AND TrangThai = N'dang_muon'";
    $stmtGet = sqlsrv_query($conn, $sqlGet, array($maPhieuMuon));
    $row = sqlsrv_fetch_array($stmtGet, SQLSRV_FETCH_ASSOC);

    if ($row) {
        $ngayHenTra = $row['NgayHenTra'];
        $maSach = $row['MaSach'];
        $ngayTra = new DateTime($ngayTraThucTe);
            $ngayHen = new DateTime($ngayHenTra);
        $soNgayQuaHan = 0;
        $tienPhat = 0;

        if ($ngayTra > $ngayHen) {
            $soNgayQuaHan = $ngayTra->diff($ngayHen)->days;
            $tienPhat = $soNgayQuaHan * 5000;

            $sqlPhat = "INSERT INTO Phat (MaPhieuMuon, SoNgayQuaHan, TienPhat, NgayThu) VALUES (?, ?, ?, ?)";
            $paramsPhat = array($maPhieuMuon, $soNgayQuaHan, $tienPhat, $ngayTraThucTe);
            sqlsrv_query($conn, $sqlPhat, $paramsPhat);
        }

        $sqlUpdate = "UPDATE MuonTra SET NgayTraThucTe = ?, TrangThai = N'da_tra' WHERE MaPhieuMuon = ?";
        sqlsrv_query($conn, $sqlUpdate, array($ngayTraThucTe, $maPhieuMuon));

        $sqlTangSach = "UPDATE Sach SET SoLuongTon = SoLuongTon + 1 WHERE MaSach = ?";
        sqlsrv_query($conn, $sqlTangSach, array($maSach));

        $thongBao = "Trả sách thành công!";
        if ($tienPhat > 0) $thongBao .= " Quá hạn $soNgayQuaHan ngày. Tiền phạt: " . number_format($tienPhat) . " VNĐ";
        echo "<script>alert('$thongBao'); window.location='tra.php';</script>";
    } else {
        echo "<script>alert('Phiếu mượn không hợp lệ hoặc đã trả rồi!');</script>";
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