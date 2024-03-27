<?php
// ドライバ呼び出しを使用して MySQL データベースに接続します
$dsn = 'mysql:dbname=xs060814_sai32023;host=127.0.0.1';
$user = 'xs060814_root';
$password = 'mz0422mtd';

try {
    $dbh = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    echo "接続失敗 ";
    header("Location:error.php?error_code=701");
}

$stmt = $dbh->prepare("UPDATE tickets SET status = 'before' WHERE starting_time <= SUBTIME(now(), '01:00:00') AND status = 'reserved'");
$res = $stmt->execute();
?>