<?php
// FILE CAU HINH KET NOI CSDL DUNG CHUNG CHO TOAN BO DU AN
//
// Cach 1 (de chay local):
// - Sua truc tiep 3 bien o ben duoi: $db_server, $db_name, $db_driver
// - Khong can DB_USER/DB_PASSWORD neu dung Windows Authentication tren may ca nhan
//
// Cach 2 (de deploy public, vi du Azure App Service):
// - Dat cac bien moi truong: DB_SERVER, DB_NAME, DB_DRIVER, DB_USER, DB_PASSWORD
// - Neu co bien moi truong, he thong uu tien dung bien moi truong

$db_server = getenv('DB_SERVER') ?: "localhost\\SQLEXPRESS"; // NGUOI DUNG SUA DONG NAY NEU CHAY LOCAL
$db_name = getenv('DB_NAME') ?: "quanly_thuvien";
$db_driver = getenv('DB_DRIVER') ?: "ODBC Driver 18 for SQL Server";
$db_user = getenv('DB_USER') ?: "";
$db_password = getenv('DB_PASSWORD') ?: "";

function env_bool($name, $default)
{
    $value = getenv($name);
    if ($value === false || $value === '') {
        return $default;
    }

    $normalized = strtolower(trim($value));
    return in_array($normalized, array('1', 'true', 'yes', 'on'), true);
}

// Local thuong de false. Deploy cloud (Azure SQL) nen de true.
$db_encrypt = env_bool('DB_ENCRYPT', false);
$db_trust_server_certificate = env_bool('DB_TRUST_SERVER_CERT', true);

header('Content-Type: text/html; charset=utf-8');

$connectionOptions = array(
    "Database" => $db_name,
    "CharacterSet" => "UTF-8",
    "Driver" => $db_driver,
    "Encrypt" => $db_encrypt,
    "TrustServerCertificate" => $db_trust_server_certificate
);

// Neu co user/password thi dung SQL Authentication.
if ($db_user !== '' && $db_password !== '') {
    $connectionOptions["UID"] = $db_user;
    $connectionOptions["PWD"] = $db_password;
}

$conn = sqlsrv_connect($db_server, $connectionOptions);
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>