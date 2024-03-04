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
        <title>駐輪場予約サイト</title>
        <meta name=”description” content="" />
        <link rel="stylesheet" href="index.css">
        <link rel="stylesheet" href="style.css">
        <!-- Favicon設定-->
        <link rel="apple-touch-icon" href="file/favicon/apple-touch-icon.png">
        <link type="image/x-icon" rel="icon" href="file/favicon/favicon.ico">
        <!--  Google Font 読み込み  -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+New:wght@500;700&display=swap" rel="stylesheet">
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
            <div id="content_1" class="content">
                <h1>利用にあたって</h1>
                <p id="caution_1" class="caution">１枠は５０分です</p>
                <p id="caution_2" class="caution">駐輪場は同時に三団体が利用可能です</p>
                <p id="caution_3" class="caution">同時に予約できるのは最大２枠までです</p>
                <p id="description">※使用したい日の前日までに予約をして下さい。<br>※当日、枠に空きがある場合は、生徒会室で予約を受け付けます。</p>
            </div>
            <div id="content_2" class="content" >
                <h1>新規予約</h1>
                <h2>予約をしたい日をタップして下さい。</h2>
                <table>
                    <tr id="week">
                        <th>日</th><th>月</th><th>火</th><th>水</th><th>木</th><th>金</th><th>土</th>
                    </tr>
                    <tr>
                        <td class="calendar_lastMonth">30</td><td class="calendar_unavailable">1</td><td class="calendar_unavailable">2</td><td id="date03" class="calendar_unavailable">3</td><td class="calendar_unavailable">4</td><td class="calendar_unavailable">5</td><td  class="calendar_unavailable">6</td>
                    </tr>
                    <tr>
                        <td class="calendar_unavailable">7</td><td class="calendar_unavailable">8</td><td class="calendar_unavailable">9</td><td class="calendar_unavailable">10</td><td class="calendar_unavailable">11</td><td class="calendar_unavailable">12</td><td class="calendar_unavailable" id="date13">13</td>
                    </tr>
                    <tr>
                        <td class="calendar_unavailable" id="date14">14</td><td class="calendar_unavailable" id="date15">15</td><td class="calendar_unavailable" id="date16">16</td><td class="calendar_unavailable" id="date17">17</td><td id="date18" class="calendar_unavailable">18</td><td class="calendar_unavailable" id="date19">19</td><td class="calendar_unavailable" id="date20">20</td>
                    </tr>
                    <tr>
                        <td class="calendar_unavailable" id="date21">21</td><td class="calendar_unavailable" id="date22">22</td><td class="calendar_unavailable" id="date23">23</td><td class="calendar_unavailable" id="date24">24</td><td class="calendar_unavailable" id="date25">25</td><td class="calendar_unavailable" id="date26">26</td><td class="calendar_unavailable" id="date27">27</td>
                    </tr>
                    <tr>
                        <td class="calendar_unavailable" id="date28">28</td><td class="calendar_unavailable" id="date29">29</td><td class="calendar_unavailable" id="date30">30</td><td class="calendar_unavailable" id="date31">31</td><td class="calendar_unavailable" id="date32">1</td><td class="calendar_lastMonth">2</td><td class="calendar_lastMonth">3</td>
                    </tr>
                </table>
            </div>
            <div id="content_3" class="content">
                <h1><?php if (isset($username) ){echo $username;} ?>の予約状況</h1>
                <div id="ticket_list"></div>
                
            </div>
        </div>

        <!--jQuery読み込み-->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <?php include('dbconnect.php'); ?>
        <?php
        try {
            $stmt1 = $dbh->prepare('SELECT * FROM booking WHERE hr like "%'.$username.'%"');
            $res1 = $stmt1->execute();
            $count = 0;
            while($data1 = $stmt1->fetch()){
                if($data1['date'] >= date('d')){
                    $count++;
                }
            }
            if($count < 2){
            $stmt = $dbh->prepare('SELECT * FROM booking WHERE status != 3');
            $res = $stmt->execute();
            while($data = $stmt->fetch()) {
                $availableDate = $data['date'];
                if($availableDate >= date('d')){
                
                ?>
        <script>
            $(<?php echo "\"#date".$availableDate."\""; ?>).removeClass("calendar_unavailable");
            $(<?php echo "\"#date".$availableDate."\""; ?>).on("click", function() {
                window.location.href = 'details.php?date=<?php echo $availableDate; ?>';
            });
        </script>
        <?php
        }
            }
        }
        } catch (PDOException $e) {
            echo "接続失敗 ";
            exit();
        };
        ?>
        <script>
            $(<?php echo "\"#date".date('d')."\""; ?>).addClass("calendar_today");
        </script>

        <?php

        try {
            $stmt = $dbh->prepare('SELECT * FROM booking WHERE hr like "%'.$username.'%"');
            $res = $stmt->execute();
            $count = 0;
            while($data = $stmt->fetch()) {
                $count++;
                $reservedDate = $data['date'];
                $reservedTime = $data['time'];
                if($reservedDate == 32){
                    $Month = "6";
                }else{
                    $Month = "5";
                }
                ?>
            <script>
                table = document.getElementById("ticket_list");
                add_code = "<div class=\"reserved_ticket\"><div class=\"ticket_left\"><p class=\"ticket_top\">日付</p><h3 class=\"ticket_top\"><?php echo $Month; ?><span class=\"smallLetter\">月</span><?php echo $reservedDate; ?><span class=\"smallLetter\">日</span></h3><p class=\"ticket_bottom\">予約団体</p><h3 class=\"ticket_bottom\"><?php echo $username; ?></h3></div><div class=\"ticket_right\"><p class=\"ticket_top\">時間帯</p><h3 class=\"ticket_top\"><?php echo $reservedTime; ?></h3></div><button class=\"submit_button\" onclick=\"location.href=\'cancelform.php?date=<?php echo $reservedDate; ?>&time=<?php echo $reservedTime; ?>\'\">予約をキャンセルする</button></div>";
                table.insertAdjacentHTML( 'beforeend', add_code);
            </script>
        <?php
            }
            
            if($count == 0){
        ?>
            <script>
                table = document.getElementById("ticket_list");
                add_code = "<h4>現在、予約された枠はありません。</h4>";
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