<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $maBanDoc = $_POST["MaBanDoc"];
    $maSach = $_POST["MaSach"];
    $ngayMuon = date("Y-m-d");
    $ngayHenTra = date("Y-m-d", strtotime("+14 days"));
    $resultMessage = '';

    $sql = "{call sp_MuonSach(?, ?, ?, ?, ?)}";
    $params = array(
        array($maBanDoc, SQLSRV_PARAM_IN),
        array($maSach, SQLSRV_PARAM_IN),
        array($ngayMuon, SQLSRV_PARAM_IN),
        array($ngayHenTra, SQLSRV_PARAM_IN),
        array(&$resultMessage, SQLSRV_PARAM_OUT, SQLSRV_PHPTYPE_STRING('UTF-8'), SQLSRV_SQLTYPE_NVARCHAR(4000))
    );
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt) {
        sqlsrv_free_stmt($stmt);
        $thongBao = trim($resultMessage) !== '' ? $resultMessage : 'Mượn sách thành công!';
        echo "<script>alert('" . addslashes($thongBao) . "'); window.location='muon.php';</script>";
    } else {
        $errors = sqlsrv_errors();
        $thongBao = 'Lỗi: Không thể mượn sách';
        if (!empty($errors) && isset($errors[0]['message'])) {
            $thongBao = $errors[0]['message'];
        }
        echo "<script>alert('" . addslashes($thongBao) . "');</script>";
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