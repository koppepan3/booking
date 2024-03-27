<?php
//ログイン確認処理
session_start();
if (isset($_SESSION['user_id'])) {//ログインしている時
    $username = $_SESSION['user'];
    $user_id = $_SESSION['user_id'];
} else {//ログインしていない時
    header("Location:loginform.php");
}

//備品班の場合管理画面に遷移
if($user_id == 1){
    header("Location:admin.php");
}

include('dbconnect.php');//DB接続情報読み込み

//URLパラメータ取得処理
if(isset($_GET['message'])){
    $message =  $_GET['message'];
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
        <title>駐輪場予約サイト</title>
        <link rel="stylesheet" href="index.css">
    <body>
        <header>
                <a href="index.php"><h1>駐輪場予約サイト</h1></a>
            <details>
                <summary><?php if (isset($username) ){echo $username;} ?></summary>
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
        <div id="modal_overlay">
            <div id="modal_content">
                <h1>メッセージ</h1>
                <h2><?php echo $message; ?></h2>
                <button onclick="document.getElementById('modal_overlay').style.display = 'none';">メッセージを閉じる</button>
            </div>
        </div>

        <?php //モーダル表示処理
        if(isset($_GET['message'])){
            $message =  $_GET['message']; ?>
        <script>document.getElementById("modal_overlay").style.display = "block";</script>
        <?php } ?>

        <!--jQuery読み込み-->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <?php
        try {
            //団体の同時予約数取得
            $stmt1 = $dbh->prepare("SELECT * FROM tickets WHERE user_id = ".$user_id."  AND (status = 'reserved' OR status = 'before')");
            $res1 = $stmt1->execute();
            $count = 0;
            while($data1 = $stmt1->fetch()){
                $count++;
            }

            //同時予約数が上限未満の場合
            //空いている枠のある日にclassとリンク付与
            if($count < 2){
            $stmt = $dbh->prepare('SELECT * FROM booking WHERE occupied_number < 3');
            $res = $stmt->execute();
            while($data = $stmt->fetch()) {
                $availableDate = date('d',strtotime($data['starting_time']));
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
            header("Location: error.php?error_code=701");
        };
        ?>

        <!--今日の日付を取得してクラス付与-->
        <script>
            $(<?php echo "\"#date".date('d')."\""; ?>).addClass("calendar_today");
        </script>

        <?php
        //団体の予約内容取得処理
        try {
            $stmt = $dbh->prepare("SELECT * FROM tickets WHERE user_id = ".$user_id." AND status = 'reserved' ");
            $res = $stmt->execute();
            $count = 0;
            while($result = $stmt->fetch()) {
                $count++;
                $ticket_month = date('n',strtotime($result['starting_time']));
                $ticket_date = date('d',strtotime($result['starting_time']));
                $ticket_starting_time = date('G:i',strtotime($result['starting_time']));
                $ticket_ending_time = date('G:i',strtotime($result['ending_time']));
                $ticket_id = $result['ticket_index'];
                ?>
                <script>
                    //チケットのhtml挿入処理
                    add_code = "<div class=\"reserved_ticket\"><div class=\"ticket_left\"><p class=\"ticket_top\">日付</p><h3 class=\"ticket_top\"><?php echo $ticket_month; ?><span class=\"smallLetter\">月</span><?php echo $ticket_date; ?><span class=\"smallLetter\">日</span></h3><p class=\"ticket_bottom\">予約団体</p><h3 class=\"ticket_bottom\"><?php echo $username; ?></h3></div><div class=\"ticket_right\"><p class=\"ticket_top\">時間帯</p><h3 class=\"ticket_top\"><?php echo $ticket_starting_time; ?>～<?php echo $ticket_ending_time; ?></h3></div><button class=\"submit_button\" onclick=\"location.href=\'cancelform.php?ticket_id=<?php echo $ticket_id; ?>\'\">予約をキャンセルする</button></div>";
                    document.getElementById("ticket_list").insertAdjacentHTML( 'beforeend', add_code);
                </script>
        <?php
            }
            $stmt1 = $dbh->prepare("SELECT * FROM tickets WHERE user_id = ".$user_id." AND status = 'before' ");
            $res1 = $stmt1->execute();
            while($result1 = $stmt1->fetch()) {
                $count++;
                $ticket_month = date('n',strtotime($result1['starting_time']));
                $ticket_date = date('d',strtotime($result1['starting_time']));
                $ticket_starting_time = date('G:i',strtotime($result1['starting_time']));
                $ticket_ending_time = date('G:i',strtotime($result1['ending_time']));
                $ticket_id = $result1['ticket_index'];
                ?>
                <script>
                    //チケットのhtml挿入処理
                    add_code = "<div class=\"reserved_ticket\"><div class=\"ticket_left\"><p class=\"ticket_top\">日付</p><h3 class=\"ticket_top\"><?php echo $ticket_month; ?><span class=\"smallLetter\">月</span><?php echo $ticket_date; ?><span class=\"smallLetter\">日</span></h3><p class=\"ticket_bottom\">予約団体</p><h3 class=\"ticket_bottom\"><?php echo $username; ?></h3></div><div class=\"ticket_right\"><p class=\"ticket_top\">時間帯</p><h3 class=\"ticket_top\"><?php echo $ticket_starting_time; ?>～<?php echo $ticket_ending_time; ?></h3></div><button class=\"submit_button_disabed\" onclick=\"\">キャンセル不可(予約一時間前)</button></div>";
                    document.getElementById("ticket_list").insertAdjacentHTML( 'beforeend', add_code);
                </script>
                <?php
            }
            
            //予約数が0の場合の処理
            if($count == 0){
        ?>
            <script>
                add_code = "<h4>現在、予約された枠はありません。</h4>";
                document.getElementById("ticket_list").insertAdjacentHTML( 'beforeend', add_code);
            </script>   
        <?php
            }
        } catch (PDOException $e) {
            echo "接続失敗 ";
            header("Location:error.php?error_code=701");
        };
        ?>
    </body>
</html>