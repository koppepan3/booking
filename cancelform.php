<?php
//ログイン確認処理
session_start();
if (isset($_SESSION['user_id'])) {//ログインしている時
    $username = $_SESSION['user'];
    $user_id = $_SESSION['user_id'];
} else {//ログインしていない時
    header("Location:loginform.php");
}

//URLパラメータ取得処理
if(isset($_GET['ticket_id'])) { $ticket_id = $_GET['ticket_id']; }

include('dbconnect.php');

//チケットIDの確認
try{
    $stmt = $dbh->prepare("SELECT * FROM tickets WHERE ticket_index = ".$ticket_id);
    $res = $stmt->execute();
    $result = $stmt->fetch();
    $ticket_month = date('n',strtotime($result['starting_time']));
    $ticket_date = date('d',strtotime($result['starting_time']));
    $ticket_starting_time = date('G:i',strtotime($result['starting_time']));
    $ticket_ending_time = date('G:i',strtotime($result['ending_time']));
    $ticket_status = $result['status'];
    $ticket_user = $result['user_id'];
    $ticket_attatched_space = $result['attatched_space'];
    if($ticket_user == $user_id && strcmp($ticket_status, "reserved")  == 0){
        //正常な場合の処理
    }else if($ticket_user == $user_id && $ticket_status == "before"){
        //開始時間一時間前の処理
        header("Location:error.php?error_code=700");
    }else if($ticket_user == $user_id && $ticket_status == "canceled"){
        //既にキャンセル済み場合の処理
        header("Location:error.php?error_code=704");
    }else{
        //その他の場合の処理
        header("Location:error.php?");
    }
} catch (PDOException $e){
    echo "接続失敗 ";
    header("Location:error.php?error_code=701");
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
        <link href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+New:wght@500;700&display=swap" rel="stylesheet">

        <!-- 個別設定 -->
        <title>駐輪場予約サイト/キャンセル内容の確認</title>
        <link rel="stylesheet" href="form.css">
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
            <a href="index.php"><h1 id="backToTop"></h1></a>
            <div class="content" id="content_1">
                <h1>キャンセル内容の確認</h1>
                <div class="ticket">
                    <div class="ticket_left">
                        <p class="ticket_top">日付</p>
                        <h3 class="ticket_top"><?php echo $ticket_month; ?><span class="smallLetter">月</span><?php echo $ticket_date; ?><span class="smallLetter">日</span></h3>
                        <p class="ticket_bottom">予約団体</p>
                        <h3 class="ticket_bottom"><?php echo $ticket_user; ?></h3>
                    </div>
                    <div class="ticket_right">
                        <p class="ticket_top">時間帯</p>
                        <h3 class="ticket_top"><?php echo $ticket_starting_time;?>～<?php echo $ticket_ending_time;?></h3>
                    </div>
                    <form method="post">
                        <input class="submit_button" type="submit" name="button" value="予約をキャンセルする"/>
                    </form> 
                </div>
            </div>
        </div>

        <!--jQuery読み込み-->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <?php
        if(isset($_POST['button'])){
            try {
                $stmt = $dbh->prepare("UPDATE booking SET occupied_number=occupied_number-1 WHERE space_id = ".$ticket_attatched_space);
                $res = $stmt->execute();
                $stmt1 = $dbh->prepare("UPDATE tickets SET status='canceled' WHERE ticket_index = ".$ticket_id);
                $res1 = $stmt1->execute();
            } catch (PDOException $e) {
                echo "接続失敗 ";
                header("Location:error.php?error_code=701");
            };
            header("Location:index.php?message=予約はキャンセルされました<br>(チケットID: ".$ticket_id.")");
        }

        
        ?>
    </body>
</html>