<?php
include('dbconnect.php');
$raw = file_get_contents('php://input'); // POSTされた生のデータを受け取る
$data = json_decode($raw,true); // json形式をphp変数に変換

$ticket_id = $data['ticket_id']; // やりたい処理
$status = $data['status'];

if($status != 'unselected'){
    try {
        $stmt = $dbh->prepare("UPDATE tickets SET status = '".$status."' WHERE ticket_index = ".$ticket_id);
        $res = $stmt->execute();
    } catch (PDOException $e) {
        echo "接続失敗 ";
    };
}

?>