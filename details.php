<?php 
    if(isset($_GET['date'])) { $date = $_GET['date']; } 
    if($date == 32){
        $MonthDate = "6月1日";
    }else{
        $MonthDate = "5月".$date."日";
    }
?>
<?php include('dbconnect.php'); ?>
<?php
session_start();
//$username = $_SESSION['name'];
if (isset($_SESSION['index'])) {//ログインしているとき
    $username = $_SESSION['dantai'];
    $link = '<a href="logout.php">ログアウト</a>';
    $form_style = "none";
    $body_style = "block";
} else {//ログインしていない時
    header("Location:loginform.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>駐輪場予約サイト/予約状況</title>
        <meta name="description" content="" />
        <link rel="stylesheet" href="details.css">
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
            <img id="statusBar" src="file/statusBar_1.svg">
            <div class="content"id="content_1">
                <h1><?php echo $MonthDate; ?>の予約状況</h1>
                <div id="ticket_list"></div>
            </div>
        </div>

        <!--jQuery読み込み-->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script>let table = document.getElementById("table_container");let add_code = "";</script>
        <?php

        try {
            $stmt = $dbh->prepare('SELECT * FROM booking WHERE status != 3 AND date='.$date);
            $res = $stmt->execute();
            while($data = $stmt->fetch()) {
                $availableTime = $data['time'];
                $availableNumber = 3 - $data['status'];
                ?>
            <script>
                table = document.getElementById("ticket_list");
                add_code = "<div class=\"ticket\"><div class=\"ticket_left\"><p>時間帯</p><h3><?php echo $availableTime; ?></h3></div><div class=\"ticket_middle\"><p>残り枠数</p><h3><?php echo $availableNumber; ?></h3></div><div class=\"ticket_right\"><button onclick=\"location.href=\'form.php?date=<?php echo $date; ?>&time=<?php echo $availableTime; ?>\'\" class=\"submit_button\">予約する</button></div></div>";
                table.insertAdjacentHTML( 'beforeend', add_code);
            </script>
        <?php
            }
        } catch (PDOException $e) {
            echo "接続失敗 ";
            exit();
        };
        ?>
    </body>
</html>