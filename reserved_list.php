<?php include('dbconnect.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>駐輪場予約サイト/予約状況一覧</title>
        <meta name="description" content="" />
        <link rel="stylesheet" href="reserved_list.css">
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
        </header>
        <div id="contents">
            <div id="content_1">
                <h1>予約状況一覧</h1>
                <table id="table_container">
                    <tr>
                        <th id="table_time">日</th><th id="table_counts">時間</th><th id="table_group">予約団体</th>
                    </tr>
                </table>
            </div>
        </div>

        <!--jQuery読み込み-->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script>let table = document.getElementById("table_container");let add_code = "";</script>
        <?php

        try {
            $stmt = $dbh->prepare('SELECT * FROM booking');
            $res = $stmt->execute();
            while($data = $stmt->fetch()) {
                $reservedDate = $data['date'];
                $reservedTime = $data['time'];
                $reservedGroup = $data['hr'];
                if($reservedDate == 32){
                    $MonthDate = "6月1日";
                }else{
                    $MonthDate = "5月".$reservedDate."日";
                }
                ?>
            <script>
                table = document.getElementById("table_container");
                add_code = "<tr class=\"table_contents\"><td><?php echo $MonthDate; ?></td><td><?php echo $reservedTime; ?></td><td><?php echo $reservedGroup; ?></td></tr>";
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