<?php
// ドライバ呼び出しを使用して MySQL データベースに接続します
$dsn = 'mysql:dbname=sai32023_sai3;host=127.0.0.1';
$user = 'sai32023_pdo';
$password = 'mz0422mtd';

try {
    $dbh = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    echo "接続失敗 ";
    exit();
}
?>