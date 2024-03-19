<?php
//ログイン確認処理
session_start();
if ($_SESSION['user_id'] == 1) {//備品班としてログインしている時
    $username = $_SESSION['user'];
    $user_id = $_SESSION['user_id'];
} else {//ログインしていない時
    header("Location:error.php?error_code=708");
}

include('dbconnect.php');//DB接続情報読み込み

//URLパラメータ取得処理
if(isset($_GET['message'])){
    $message =  $_GET['message'];
}

//直近の時間帯検索処理
try{
    $stmt = $dbh->prepare('SELECT * FROM booking');
    $res = $stmt->execute();
    while($result = $stmt->fetch()){
        $starting_time = strtotime($result['starting_time']);
        $ending_time = strtotime($result['ending_time']);
        $last_space_id = $result['space_id'];
        if($starting_time <= time() && time() <= $ending_time){
            $default_space_id = $result['space_id'];
            break;
        }else if($ending_time < time()){
            $default_space_id = $last_space_id;
            break;
        }
    }
} catch (PDOException $e) {
    echo "接続失敗 ";
    header("Location: error.php?error_code=701");
}
try{
    $stmt = $dbh->prepare('SELECT * FROM booking WHERE space_id = '.$default_space_id);
    $res = $stmt->execute();
    $result = $stmt->fetch();
    $starting_time = strtotime($result['starting_time']);
    $ending_time = strtotime($result['ending_time']);
    $ticket_starting_time = date('G:i',strtotime($result['starting_time']));
    $ticket_ending_time = date('G:i',strtotime($result['ending_time']));
    $default_time = $ticket_starting_time."~".$ticket_ending_time;
    $default_month = date('n',strtotime($result['starting_time']));
    $default_date = date('d',strtotime($result['starting_time']));
} catch (PDOException $e) {
    echo "接続失敗 ";
    header("Location: error.php?error_code=701");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <!-- 共通設定 -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name=”description” content="" />
        <link rel="stylesheet" href="style.css">
        <!-- 検索結果インデックス禁止 -->
        <meta name=”robots” content=”noindex”>
        <!-- Favicon設定-->
        <link rel="apple-touch-icon" href="file/favicon/apple-touch-icon.png">
        <link type="image/x-icon" rel="icon" href="file/favicon/favicon.ico">
        <!--  Google Font 読み込み  -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@700&family=Zen+Kaku+Gothic+New:wght@500;700;900&display=swap" rel="stylesheet">

        <!-- 個別設定 -->
        <title>駐輪場予約サイト/管理画面</title>
        <link rel="stylesheet" href="admin.css">
    <body>
        <header>
                <a href="index.php"><h1>駐輪場予約サイト/管理画面</h1></a>
            <details>
                <summary><?php if (isset($username) ){echo $username;} ?></summary>
                <li><a href="logout.php">ログアウト</a></li>
            </details>
        </header>
        <div id="contents">
            <div id="content_1" class="content">
                <h1>利用状況記録</h1>
                <div id="slider"><h2><?php echo $default_month; ?>月<?php echo $default_date; ?>日　<?php echo $default_time; ?></h2></div>
                <div class="ticket">
                    <h3>チケットID:012<br>予約団体:13HR</h3>
                    <div class="ticket_container">
                        <button>確認</button>
                        <button>ペナルティ</button>
                    </div>
                </div>
                
            </div>
            <div id="content_2" class="content" >
                <h1>新規予約</h1>
                <h2>予約をしたい日をタップして下さい。</h2>
                
            </div>
            <div id="content_3" class="content">
                <h1><?php if (isset($username) ){echo $username;} ?>の予約状況</h1>
                <div id="ticket_list"></div>
            </div>
        </div>

        <!--jQuery読み込み-->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script>
        </script>
    </body>
</html>