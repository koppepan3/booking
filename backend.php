<?php

//ログイン確認処理
session_start();
if (isset($_SESSION['user_id'])) {
    //ログインしている時
    //セッションからユーザ名等取得
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

//団体の予約状態表示
function GenerateUserReservedTickets($PDO, $user_id, $username){
    $user_tickets_array = GetUserReservedInfo($PDO, $user_id);
    $tickets_array_length = count($user_tickets_array);
    if($tickets_array_length == 0){
        echo "<h4>現在、予約された枠はありません。</h4>";
    }else{
        for($tickets = 0; $tickets <= $tickets_array_length - 1; $tickets++){
            $ticket_start = $user_tickets_array[$tickets]['starting_time'];
            $ticket_end = $user_tickets_array[$tickets]['ending_time'];
            $ticket_month = date('n', strtotime($ticket_start));
            $ticket_date = date('d', strtotime($ticket_start));
            $ticket_start_time = date('H:i', strtotime($ticket_start));
            $ticket_end_time = date('H:i', strtotime($ticket_end));
            $ticket_id = $user_tickets_array[$tickets]['ticket_index'];
            if($user_tickets_array[$tickets]['status'] == 'reserved'){
                $ticket_button = "<button class='submit_button' onclick=\"location.href='cancelform.php?ticket_id=$ticket_id'\">予約をキャンセルする</button>";
            }elseif($user_tickets_array[$tickets]['status'] == 'before'){
                $ticket_button = "<button class='submit_button_disabed' onclick=''>キャンセル不可(予約一時間前)</button>";
            }
            echo "<div class='reserved_ticket'><div class='ticket_left'><p class='ticket_top'>日付</p><h3 class='ticket_top'>$ticket_month<span class='smallLetter'>月</span>$ticket_date<span class='smallLetter'>日</span></h3><p class='ticket_bottom'>予約団体</p><h3 class='ticket_bottom'>$username</h3></div><div class='ticket_right'><p class='ticket_top'>時間帯</p><h3 class='ticket_top'>{$ticket_start_time}～{$ticket_end_time}</h3></div>$ticket_button</div>";
        }
    }
}

//団体の予約状態取得
function GetUserReservedInfo($PDO, $user_id) {
    $user_tickets_array = [];
    $stmt = $PDO->prepare("SELECT * FROM tickets WHERE user_id = ".$user_id." AND (status = 'reserved' OR status = 'before')");
    $res = $stmt->execute();
    while($result = $stmt->fetch()){
        //予約してあるチケットを多次元連想配列の形式で保存
        $user_tickets_array[] = $result;
    }
    //保存した配列を返す
    return $user_tickets_array;
}

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
while($result = $stmt->fetch()){
    $date_calendar_array[$order_count] = date("j",strtotime($result['date']));
    $ymd_calendar_array[$order_count] = date("Y-m-d",strtotime($result['date']));
    //クラス名決定
    $today = date("Y-m-d");
    if (strtotime($result['date']) < strtotime($today)){
        $date_class_array[$order_count] = "calendar_past";
    }elseif(strtotime($result['date']) == strtotime($today)){
        $date_class_array[$order_count] = "calendar_today";
        if(EmptyTickets($result['date'], $dbh) == false){
            $date_class_array[$order_count] = "calendar_today_unavaiable";
        }
    }else{
        if(EmptyTickets($result['date'], $dbh) == true){
            $date_class_array[$order_count] = "calendar_avaiable";
        }else{
            $date_class_array[$order_count] = "calendar_unavailable";
        }
    }
    $order_count++;
}

//その日に空き枠があるかないかの判定
function EmptyTickets($date, $PDO){
    $stmt = $PDO->prepare("SELECT * FROM booking WHERE occupied_number < 3 AND DATE_FORMAT(starting_time, '%Y-%m-%d') = DATE_FORMAT(:date, '%Y-%m-%d')");
    $stmt->bindValue(':date', $date);
    $res = $stmt->execute();
    if($result = $stmt->fetch()){
        //空き枠があった場合
        return true;
    }else{
        //空き枠がなかった場合
        return false;
    }    
}

//トップページのカレンダー生成処理
function GenerateCalender($calendar_array, $class_array, $calendar_ymd_array){
    //配列からカレンダーの行数(高さ)を取得
    $calendar_array_length = count($calendar_array);
    $calendar_row = ceil($calendar_array_length / 7) - 1;

    for ($row = 0; $row <= $calendar_row; $row++) {
        echo "<tr>";
        for ($column = 0; $column <= 6; $column++) {
            $calendar_index = 7 * $row + $column;
            if(
                $class_array[$calendar_index] == "calendar_today" ||
                $class_array[$calendar_index] == "calendar_avaiable"
                ){
                echo "<td class='".$class_array[$calendar_index]."'><a href='details.php?date=".$calendar_ymd_array[$calendar_index]."'>".$calendar_array[$calendar_index]."</a></td>";
            }else{
                echo "<td class='".$class_array[$calendar_index]."'>".$calendar_array[$calendar_index]."</td>";
            }
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