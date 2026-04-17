<?php
// FILE CAU HINH KET NOI CSDL DUNG CHUNG CHO TOAN BO DU AN
// Nguoi dung chi can sua 3 dong duoi day cho phu hop may cua minh.

$db_server = "localhost\\SQLEXPRESS"; // NGUOI DUNG SUA DONG NAY
$db_name = "quanly_thuvien";
$db_driver = "ODBC Driver 18 for SQL Server";

header('Content-Type: text/html; charset=utf-8');

$connectionOptions = array(
    "Database" => $db_name,
    "CharacterSet" => "UTF-8",
    "Driver" => $db_driver,
    "TrustServerCertificate" => true
);

$conn = sqlsrv_connect($db_server, $connectionOptions);
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>