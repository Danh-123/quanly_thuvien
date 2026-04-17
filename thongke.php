<?php
require_once 'config.php';

$sqlTop = "SELECT TOP 5 s.TuaSach, COUNT(m.MaPhieuMuon) AS SoLanMuon
           FROM Sach s
           JOIN MuonTra m ON s.MaSach = m.MaSach
           GROUP BY s.TuaSach
           ORDER BY SoLanMuon DESC";
$stmtTop = sqlsrv_query($conn, $sqlTop);

$sqlTong = "SELECT COUNT(MaPhieuMuon) AS TongMuon FROM MuonTra";
$stmtTong = sqlsrv_query($conn, $sqlTong);
$rowTong = sqlsrv_fetch_array($stmtTong, SQLSRV_FETCH_ASSOC);

$sqlDangMuon = "SELECT COUNT(MaPhieuMuon) AS DangMuon FROM MuonTra WHERE TrangThai = N'dang_muon'";
$stmtDangMuon = sqlsrv_query($conn, $sqlDangMuon);
$rowDangMuon = sqlsrv_fetch_array($stmtDangMuon, SQLSRV_FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Thống kê</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="page-shell">
<section class="hero">
    <h1>📊 Thống kê thư viện</h1>
    <p>Tổng quan hoạt động mượn trả và sách được mượn nhiều nhất.</p>
</section>

<section class="panel">
<div class="stats-grid">
    <div class="stat-card">
        <h3>Tổng số lượt mượn</h3>
        <span class="stat-number"><?= $rowTong['TongMuon'] ?></span>
    </div>
    <div class="stat-card">
        <h3>Sách đang được mượn</h3>
        <span class="stat-number"><?= $rowDangMuon['DangMuon'] ?></span>
    </div>
</div>

<h3>🏆 Top 5 sách được mượn nhiều nhất</h3>
<div class="table-wrap">
<table>
    <tr><th>Tựa sách</th><th>Số lần mượn</th></tr>
    <?php while($row = sqlsrv_fetch_array($stmtTop, SQLSRV_FETCH_ASSOC)): ?>
    <tr>
        <td><?= $row['TuaSach'] ?></td>
        <td><?= $row['SoLanMuon'] ?></td>
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
sqlsrv_free_stmt($stmtTop);
sqlsrv_free_stmt($stmtTong);
sqlsrv_free_stmt($stmtDangMuon);
sqlsrv_close($conn);
?>