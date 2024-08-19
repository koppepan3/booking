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

//カレンダーの日時設定
$calender_row_1 = [7, 8, 9, 10, 11, 12, 13];
$calender_row_2 = [14, 15, 16, 17, 18, 19, 20];
$calender_row_3 = [14, 15, 16, 17, 18, 19, 20];
$calender_row_4 = [14, 15, 16, 17, 18, 19, 20];
$calender_row_5 = [14, 15, 16, 17, 18, 19, 20];
$calender_row_array = [$calender_row_1, $calender_row_2, $calender_row_3, $calender_row_4, $calender_row_5];

//トップページのカレンダー生成処理
function GenerateCalender($calender){
    for ($row = 0; $row <= 4; $row++) {
        echo "<tr>";
        for ($column = 0; $column <= 6; $column++) {
            echo "<td class='calendar_unavailable'>".$calender[$row][$column]."</td>";
        }
        echo "</tr>";
    }
}