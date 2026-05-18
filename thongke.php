<?php
require_once 'config.php';

$sqlTop = "{call sp_ThongKeTopSachMuon}";
$stmtTop = sqlsrv_query($conn, $sqlTop);
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
sqlsrv_close($conn);
?>