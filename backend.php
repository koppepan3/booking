<?php

//ログイン確認処理
session_start();
if (isset($_SESSION['user_id'])) {
    //ログインしている時

    $username = $_SESSION['user'];
    $user_id = $_SESSION['user_id'];
    //閲覧権限
    switch ($current_page) {
        case "admin":
            if($user_id != 1){
                header("Location:error.php?error_code=708");
            }
        break;
        default:
            if($user_id == 1){
                header("Location:admin.php");
            }
    }
} else {
    //ログインしていない時

    header("Location:loginform.php");
}

//DB接続情報読み込み
include('dbconnect.php');

//団体の予約上限のチェック
$stmt = $dbh->prepare("SELECT * FROM tickets WHERE user_id = ".$user_id."  AND (status = 'reserved' OR status = 'before')");
$res = $stmt->execute();
$reserved_tickets_count = 0;
while($result = $stmt->fetch()){
    $reserved_tickets_count++;
}

//DBからカレンダーの内容を取得して配列にいれる処理
$stmt = $dbh->prepare("SELECT * from calendar");
$res = $stmt->execute();
$order_count = 0;
while($result= $stmt->fetch()){
    $date_calender_array[$order_count] = date("j",strtotime($result['date']));
    $date_class_array[$order_count] = "calendar_unavailable";
    $order_count++;
}

//その日に空き枠があるかないかの判定
function EmptyTickets(){

}

//トップページのカレンダー生成処理
function GenerateCalender($calendar_array, $class_array){
    //配列からカレンダーの行数(高さ)を取得
    $calendar_array_length = count($calendar_array);
    $calendar_row = ceil($calendar_array_length / 7) - 1;

    for ($row = 0; $row <= $calendar_row; $row++) {
        echo "<tr>";
        for ($column = 0; $column <= 6; $column++) {
            $calendar_index = 7 * $row + $column;
            echo "<td class='".$class_array[$calendar_index]."'>".$calendar_array[$calendar_index]."</td>";
        }
        echo "</tr>";
    }
}

/*
try {
    $stmt = $dbh->prepare("");
    $res = $stmt->execute();
};
*/