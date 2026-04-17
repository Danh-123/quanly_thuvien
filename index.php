<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Quản lý thư viện</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="page-shell">
	<section class="hero">
		<h1>Hệ thống quản lý thư viện</h1>
		<p>Quản lý sách, bạn đọc, mượn trả và thống kê trên SQL Server.</p>
	</section>

	<section class="panel">
		<h2>Chức năng chính</h2>
		<ul class="nav-list">
			<li><a href="sach.php">Quản lý sách</a></li>
			<li><a href="bandoc.php">Quản lý bạn đọc</a></li>
			<li><a href="muon.php">Mượn sách</a></li>
			<li><a href="tra.php">Trả sách</a></li>
			<li><a href="danhsach_muon.php">Danh sách đang mượn</a></li>
			<li><a href="thongke.php">Thống kê</a></li>
		</ul>
	</section>
</div>
</body>
</html>
<?php sqlsrv_close($conn); ?>
