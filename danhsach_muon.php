<?php
require_once 'config.php';

$sql = "SELECT m.MaPhieuMuon, b.HoTen, s.TuaSach,
    CONVERT(VARCHAR(10), m.NgayMuon, 23) AS NgayMuon,
    CONVERT(VARCHAR(10), m.NgayHenTra, 23) AS NgayHenTra,
        DATEDIFF(DAY, m.NgayHenTra, GETDATE()) AS SoNgayQuaHan
        FROM MuonTra m
        JOIN BanDoc b ON m.MaBanDoc = b.MaBanDoc
        JOIN Sach s ON m.MaSach = s.MaSach
        WHERE m.TrangThai = N'dang_muon'
        ORDER BY m.NgayHenTra ASC";
$stmt = sqlsrv_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Danh sách đang mượn</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="page-shell">
<section class="hero">
    <h1>📋 Danh sách đang mượn</h1>
    <p>Theo dõi phiếu mượn hiện tại và trạng thái quá hạn.</p>
</section>

<section class="panel">
<div class="table-wrap">
<table>
    <tr>
        <th>Mã phiếu</th>
        <th>Bạn đọc</th>
        <th>Tựa sách</th>
        <th>Ngày mượn</th>
        <th>Ngày hẹn trả</th>
        <th>Trạng thái</th>
    </tr>
    <?php while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)):
        $quaHan = $row['SoNgayQuaHan'];
        $trangThai = ($quaHan > 0) ? "<span class='quaHan'>Quá hạn $quaHan ngày</span>" : "Đang mượn";
    ?>
    <tr>
        <td><?= $row['MaPhieuMuon'] ?></td>
        <td><?= $row['HoTen'] ?></td>
        <td><?= $row['TuaSach'] ?></td>
        <td><?= $row['NgayMuon'] ?></td>
        <td><?= $row['NgayHenTra'] ?></td>
        <td><?= $trangThai ?></td>
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