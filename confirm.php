<?php
//ログイン確認処理
session_start();
if (isset($_SESSION['user_id'])) {//ログインしている時
    $username = $_SESSION['user'];
    $user_id = $_SESSION['user_id'];
} else {//ログインしていない時
    header("Location:loginform.php");
}

include('dbconnect.php');//DB接続情報読み込み

if(isset($_GET['ticket_id'])) { $ticket_id = $_GET['ticket_id']; } 

try{
    $stmt = $dbh->prepare("SELECT * FROM tickets WHERE ticket_index = ".$ticket_id);
    $res = $stmt->execute();
    $result = $stmt->fetch();
    $month = date('n',strtotime($result['starting_time']));
    $date = date('j',strtotime($result['starting_time']));
    $starting_time = $result['starting_time'];
    $ending_time = $result['ending_time'];
    $time = date('H:i',strtotime($starting_time))."～".date('H:i',strtotime($ending_time));
}catch (PDOException $e) {
    echo "接続失敗 ";
    header("Location: error.php?error_code=701");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>駐輪場予約サイト/予約状況</title>
        <meta name="description" content="" />
        <link rel="stylesheet" href="confirm.css">
        <link rel="stylesheet" href="style.css">
        <!-- Favicon設定-->
        <link rel="apple-touch-icon" href="file/favicon/apple-touch-icon.png">
        <link type="image/x-icon" rel="icon" href="file/favicon/favicon.ico">
        <!--  Google Font 読み込み  -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+New:wght@500;700&display=swap" rel="stylesheet">
    </head>
    <body>
        <header>
            <a href="index.php"><h1>駐輪場予約サイト</h1></a>
            <details>
                <summary><?php if (isset($username) ){echo $username;} ?></summary>
                <li><a href="hr_detail.php">予約状況確認</a></li>
                <li><a href="logout.php">ログアウト</a></li>
            </details>
        </header>
        <div id="contents">
            <a href="index.php"><h1 id="backToTop">トップに戻る</h1></a>
            <img id="statusBar" src="file/statusBar_3.svg">
            <div class="content" id="content_1">
                <h1>予約完了画面</h1>
                <h2>　以下の内容で予約が完了しました。下記の注意事項を確認の上、利用開始時刻の５分前までに駐輪場にお越しください。</h2>
                <div class="ticket">
                    <div class="ticket_left">
                        <p class="ticket_top">日付</p>
                        <h3 class="ticket_top"><?php echo $month; ?><span class="smallLetter">月</span><?php echo $date; ?><span class="smallLetter">日</span></h3>
                        <p class="ticket_bottom">予約団体</p>
                        <h3 class="ticket_bottom"><?php echo $username; ?></h3>
                    </div>
                    <div class="ticket_right">
                        <p class="ticket_top">時間帯</p>
                        <h3 class="ticket_top"><?php echo $time; ?></h3>
                    </div>
                </div>
            </div>
            <div class="content" id="content_2">
                <h1>使用にあたって</h1>
                <p id="caution_1" class="caution">木材加工、ネジの打ち込み、電動工具の使用が可能</p>
                <p id="caution_2" class="caution">電動のこぎり、チェーンソー。釘の使用は禁止</p>
                <p id="caution_3" class="caution">使用可能な塗料は水性のもののみ(アクリル絵の具/ポスターカラーなど)</p>
                <p id="caution_4" class="caution">塗装の際はブルーシートの上に更に新聞紙等の雑紙を敷く</p>
            </div>
        </div>

        <!--jQuery読み込み-->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script>let table = document.getElementById("table_container");let add_code = "";</script>
    </body>
</html>