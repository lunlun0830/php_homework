<?php
$conn = mysqli_connect('localhost', 'root', '', 'mail_homework');
if (!$conn) {
    die('資料庫連線失敗，請先匯入 database.sql');
}
mysqli_set_charset($conn, 'utf8mb4');
?>

