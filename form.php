<?php if(isset($_GET['date'])) { $date = $_GET['date']; } if(isset($_GET['time'])) { $time = $_GET['time']; }
    if($date == 32){
        $Month = "6";
    }else{
        $Month = "5";
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
            <a href="details.php?date=<?php echo $date; ?>"><h1 id="backToTop">時間帯選択に戻る</h1></a>
            <img id="statusBar" src="file/statusBar_2.svg">
            <div class="content" id="content_1">
                <h1>予約内容の確認</h1>
                <div class="ticket">
                    <div class="ticket_left">
                        <p class="ticket_top">日付</p>
                        <h3 class="ticket_top"><?php echo $Month; ?><span class="smallLetter">月</span><?php echo $date; ?><span class="smallLetter">日</span></h3>
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
                $stmt1 = $dbh->prepare('SELECT * FROM booking WHERE hr like "%'.$username.'%"');
                $res1 = $stmt1->execute();
                $count = 0;
                while($data1 = $stmt1->fetch()){
                    if($data1['date'] >= date('d')){
                        $count++;
                    }
                }
                if($count >= 2){header("Location: error.php"); exit();}

                $stmt = $dbh->prepare('SELECT * FROM booking WHERE date = '.$date.' AND time = \''.$time.'\'');
                $res = $stmt->execute();
                while($data = $stmt->fetch()) {
                $exisiting_hr = $data['hr'];
                }
                $stmt1 = $dbh->prepare('UPDATE booking SET hr = \''.$username.','.$exisiting_hr.'\', status = status +1 WHERE date = '.$date.' AND time = \''.$time.'\'');
                $res1 = $stmt1->execute();
            } catch (PDOException $e) {
                echo "接続失敗 ";
                exit();
            };
            header("Location:confirm.php?date=".$date."&time=".$time);
        }

        
        ?>
    </body>
</html>