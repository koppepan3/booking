<?php
//ファイルパス取得処理
$current_page = basename(__FILE__, ".php");

//共通バックエンド処理読み込み
include('backend.php');

//URLパラメータ取得
if(isset($_GET['date'])) { $date = $_GET['date']; } 
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>駐輪場予約サイト/<?php echo $date; ?>日の予約状況</title>
        <meta name="description" content="" />
        <link rel="stylesheet" href="details.css">
        <link rel="stylesheet" href="style.css">
        <!-- Favicon設定-->
        <link rel="apple-touch-icon" href="file/favicon/apple-touch-icon.png">
        <link type="image/x-icon" rel="icon" href="file/favicon/favicon.ico">
        <!--  Google Font 読み込み  -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;500;700&family=Zen+Kaku+Gothic+New:wght@500;700;900&display=swap" rel="stylesheet">
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
            <img id="statusBar" src="file/statusBar_1.svg">
            <div class="content"id="content_1">
                <div id="dates_selector">
                </div>
                <div id="ticket_list">
                    <?php GenerateDayTicket($dbh, $date); ?>
                </div>
                <div id="ticket_last_line"></div>
            </div>
        </div>

        <!--jQuery読み込み-->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script>let table = document.getElementById("table_container");let dateSelector = document.getElementById("dates_selector");let add_code = "";</script>
        <?php
        //チケットの生成php&JS
        try {

            //上部の日付スライダー生成処理
            $stmt1 = $dbh->prepare("SELECT * FROM booking WHERE occupied_number < 3");
            $res1 = $stmt1->execute();
            //配列の準備
            $dates_array = array();
            $week_array = array();
            //曜日と数字の対応を定義する配列
            $week = [
                '日', //0
                '月', //1
                '火', //2
                '水', //3
                '木', //4
                '金', //5
                '土', //6
            ];
            $loop_counter = 0;
            while($result1 = $stmt1->fetch()) {
                $result_date = date('j',strtotime($result1['starting_time']));
                $result_week = date('w',strtotime($result1['starting_time']));
                echo $result_date;
                if($loop_counter == 0){
                    $dates_array[] = $result_date;
                    $week_array[] = $week[$result_week];
                }elseif($result_date != end($dates_array)){
                    //重複して同じ日を配列に入れないようにする
                    $dates_array[] = $result_date;
                    $week_array[] = $week[$result_week];
                }
                $loop_counter++;
            }
            $array_key = array_search($date, $dates_array);
            $i = -2;
            while($i <= 2){
                $array_key_i = $array_key + $i;
                echo $array_key_i;
                if(array_key_exists($array_key_i, $dates_array) && $array_key_i > -1){
                    $date_i = $dates_array[$array_key_i];
                    $week_i = $week_array[$array_key_i]; 
                }else{
                    $date_i = "";
                    $week_i = "";
                   
                }
                if($i == 0){
                    ?>
                    <script>
                        dateSelector = document.getElementById("dates_selector");
                        add_code = "<div class=\"date_select date_select_today\"><h4><?php echo $week_i; ?></h4><a href=\"details.php?date=<?php echo $date_i; ?>\"><div class=\"date_holder\"><h3><?php echo $date_i; ?></h3></div></a></div>";
                        dateSelector.insertAdjacentHTML( 'beforeend', add_code);
                    </script>
                    <?php
                }else{
                    ?>
                    <script>
                        dateSelector = document.getElementById("dates_selector");
                        add_code = "<div class=\"date_select\"><h4><?php echo $week_i; ?></h4><a href=\"details.php?date=<?php echo $date_i; ?>\"><div class=\"date_holder\"><h3><?php echo $date_i; ?></h3></div></a></div>";
                        dateSelector.insertAdjacentHTML( 'beforeend', add_code);
                    </script>
                    <?php
                }
                $i++;
            }

        } catch (PDOException $e) {
            echo "接続失敗 ";
            header("Location: error.php?error_code=701");
        };
        ?>
    </body>
</html>