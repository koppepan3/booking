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
            
            <div id="content_1">
                <h1><?php echo $MonthDate; ?>の予約状況</h1>
                <table id="table_container">
                    <tr>
                        <th id="table_time">時間帯</th><th id="table_counts">残り枠数</th><th id="table_button"></th>
                    </tr>
                </table>
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
                table = document.getElementById("table_container");
                add_code = "<tr class=\"table_contents\"><td><?php echo $availableTime; ?></td><td><?php echo $availableNumber; ?></td><td><a href=\"form.php?date=<?php echo $date; ?>&time=<?php echo $availableTime; ?>\">予約する</a></td></tr>";
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