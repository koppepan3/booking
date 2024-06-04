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

if(isset($_GET['space_id'])) { $space_id = $_GET['space_id']; } 

try{
    $stmt = $dbh->prepare("SELECT * FROM booking WHERE space_id = ".$space_id);
    $res = $stmt->execute();
    $result = $stmt->fetch();
    $month = date('n',strtotime($result['starting_time']));
    $date = date('j',strtotime($result['starting_time']));
    $starting_time = $result['starting_time'];
    $ending_time = $result['ending_time'];
    $time = date('H:i',strtotime($starting_time))."～".date('H:i',strtotime($ending_time));
}catch (PDOException $e) {
    echo "接続失敗";
    header("Location: error.php?error_code=701");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>駐輪場予約サイト/予約確定</title>
        <meta name="description" content="" />
        <link rel="stylesheet" href="form.css">
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
            <a href="details.php?date=<?php echo $date; ?>"><h1 id="backToTop"></h1></a>
            <img id="statusBar" src="file/statusBar_2.svg">
            <div class="content" id="content_1">
                <h1>予約内容の確認</h1>
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
                    <form method="post">
                        <input class="submit_button" type="submit" name="button" value="予約を確定する"/>
                    </form> 
                </div>
            </div>
        </div>

        <!--jQuery読み込み-->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script>let table = document.getElementById("table_container");let add_code = "";</script>
        <?php
        if(isset($_POST['button'])){
            try {
                $stmt = $dbh->prepare("SELECT * FROM booking WHERE space_id = ".$space_id);
                $res = $stmt->execute();
                $result = $stmt->fetch();
                $occupied_number = $result['occupied_number'];
                $stmt3 = $dbh->prepare("SELECT * FROM tickets WHERE user_id = ".$user_id."  AND (status = 'reserved' OR status = 'before')");
                $res3 = $stmt3->execute();
                $UserTicketCount = 0;
                while($data = $stmt3->fetch()){
                    $UserTicketCount++;
                }
                if($occupied_number < 3 && $UserTicketCount < 2){//枠が開いている場合の処理
                    $stmt1 = $dbh->prepare("UPDATE booking SET occupied_number = occupied_number + 1 WHERE space_id = ".$space_id);
                    $res1 = $stmt1->execute();
                    $stmt2 = $dbh->prepare("INSERT INTO tickets (user_id, attatched_space, status, starting_time, ending_time) VALUES (".$user_id.", ".$space_id.", 'reserved', '".$starting_time."', '".$ending_time."');SELECT LAST_INSERT_ID();");
                    $res2 = $stmt2->execute();
                    $ticket_id = $dbh -> lastInsertId();
                }else{
                    header("Location: error.php?error_code=703");
                }
            } catch (PDOException $e) {
                echo "接続失敗 ";
                header("Location: error.php?error_code=701");
            };
            header("Location:confirm.php?ticket_id=".$ticket_id);
        }

        
        ?>
    </body>
</html>