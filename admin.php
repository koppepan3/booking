<?php
//ログイン確認処理
session_start();
if ($_SESSION['user_id'] == 1) {//備品班としてログインしている時
    $username = $_SESSION['user'];
    $user_id = $_SESSION['user_id'];
} else {//ログインしていない時
    header("Location:error.php?error_code=708");
}

include('dbconnect.php');//DB接続情報読み込み

//URLパラメータ取得処理
if(isset($_GET['message'])){
    $message =  $_GET['message'];
}

//直近の時間帯検索処理
try{
    $stmt = $dbh->prepare('SELECT * FROM booking');
    $res = $stmt->execute();
    while($result = $stmt->fetch()){
        $starting_time = strtotime($result['starting_time']);
        $ending_time = strtotime($result['ending_time']);
        $last_space_id = $result['space_id'];
        $default_space_id = $result['space_id'];
        if($starting_time <= time() && time() <= $ending_time){
            $default_space_id = $result['space_id'];
            break;
        }else if(time() < $starting_time){
            $default_space_id = $last_space_id;
            break;
        }
    }
} catch (PDOException $e) {
    echo "接続失敗 ";
    header("Location: error.php?error_code=701");
}

//予約枠がいくつあるか確認
try{
    $stmt = $dbh->prepare('SELECT * FROM booking');
    $res = $stmt->execute();
    while($result = $stmt->fetch()){
        $max_space_id = $result['space_id'];
    }
} catch (PDOException $e) {
    echo "接続失敗 ";
    header("Location: error.php?error_code=701");
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
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@700&family=Zen+Kaku+Gothic+New:wght@500;700;900&display=swap" rel="stylesheet">

        <!-- Slick css読み込み -->
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
        
        <!-- 個別設定 -->
        <title>駐輪場予約サイト/管理画面</title>
        <link rel="stylesheet" href="admin.css">
    <body>
        <header>
                <a href="index.php"><h1>駐輪場予約サイト/管理画面</h1></a>
            <details>
                <summary><?php if (isset($username) ){echo $username;} ?></summary>
                <li><a href="logout.php">ログアウト</a></li>
            </details>
        </header>
        <div id="contents">
            <div id="content_1" class="content">
                <h1>利用状況記録</h1>
                <div id="slider">
                    <a href="javascript:void(0)" onclick="prev_space();" id="prev_arrow"><img src="file/arrow_back.svg"></a>
                    <a href="javascript:void(0)" onclick="next_space();" id="next_arrow"><img src="file/arrow_back.svg"></a>
                </div>
                <div id="tickets_container">
                    <!--
                    <div class="ticket">
                        <h3>チケットID:012<br>予約団体:13HR</h3>
                        <select class="selector" id="select_12">
                            <option value="unselected">未選択</option>
                            <option value="confirmed">確認済</option>
                            <option value="unused">ペナルティ</option>
                        </select>
                    </div>
                -->
                </div>
            </div>
            <div id="content_2" class="content" >
                <h1>各団体の予約状況</h1>
            </div>
            <div id="content_3" class="content">
                <h1>ペナルティ一覧</h1>
                <div class="penalty_ticket">

                </div>
            </div>
        </div>

        <!--jQuery読み込み-->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>				

        <!--利用状況　チケット生成処理-->
        <?php
            try{
                $stmt = $dbh->prepare('SELECT * FROM booking');
                $res = $stmt->execute();
                while($result = $stmt->fetch()){
                    $space_time = date('G:i',strtotime($result['starting_time']))."~".date('G:i',strtotime($result['ending_time']));
                    $space_month = date('n',strtotime($result['starting_time']));
                    $space_date = date('d',strtotime($result['starting_time']));
                    $space_id = $result['space_id'];
                    ?>
                    <!--時間帯のタイトル生成処理-->
                    <script>
                        table = document.getElementById("slider");
                        add_code = "<h2 class='js_hidden space_id_<?php echo $space_id;?>'><?php echo $space_month; ?>月<?php echo $space_date; ?>日　<?php echo $space_time; ?></h2>";
                        table.insertAdjacentHTML( 'beforeend', add_code);
                    </script>
                    <?php
                    $stmt1 = $dbh->prepare('SELECT * FROM tickets WHERE attatched_space = '.$space_id);
                    $res1 = $stmt1->execute();
                    while($result1 = $stmt1->fetch()){
                        $ticket_id = $result1['ticket_index'];
                        $user_id = $result1['user_id'];
                        $ticket_status = $result1['status'];
                        //ユーザ名をusersテーブルから取得
                        $stmt2 = $dbh->prepare('SELECT * FROM users WHERE user_id = '.$user_id);
                        $res2 = $stmt2->execute();
                        $result2 = $stmt2->fetch();
                        $ticket_group = $result2['user'];
                        switch($ticket_status){
                            case "before":
                                ?>
                                <!--チケット生成-->
                                <script>
                                    table = document.getElementById("tickets_container");
                                    add_code = "<div class='ticket js_hidden space_id_<?php echo $space_id;?>'><h3>チケットID:<?php echo $ticket_id;?><br>予約団体:<?php echo $ticket_group;?></h3><select class='selector' id='select_<?php echo $ticket_id;?>'><option value='unselected' selected>未選択</option><option value='confirmed'>確認済</option><option value='unused'>ペナルティ</option></select></div>";
                                    table.insertAdjacentHTML( 'beforeend', add_code);
                                </script>
                                <?php
                                break;
                            case "confirmed":
                                ?>
                                <!--チケット生成-->
                                <script>
                                    table = document.getElementById("tickets_container");
                                    add_code = "<div class='ticket js_hidden space_id_<?php echo $space_id;?>'><h3>チケットID:<?php echo $ticket_id;?><br>予約団体:<?php echo $ticket_group;?></h3><select class='selector' id='select_<?php echo $ticket_id;?>'><option value='unselected'>未選択</option><option value='confirmed' selected>確認済</option><option value='unused'>ペナルティ</option></select></div>";
                                    table.insertAdjacentHTML( 'beforeend', add_code);
                                </script>
                                <?php
                                break;
                            case "unused":
                                ?>
                                <!--チケット生成-->
                                <script>
                                    table = document.getElementById("tickets_container");
                                    add_code = "<div class='ticket js_hidden space_id_<?php echo $space_id;?>'><h3>チケットID:<?php echo $ticket_id;?><br>予約団体:<?php echo $ticket_group;?></h3><select class='selector' id='select_<?php echo $ticket_id;?>'><option value='unselected'>未選択</option><option value='confirmed'>確認済</option><option value='unused' selected>ペナルティ</option></select></div>";
                                    table.insertAdjacentHTML( 'beforeend', add_code);
                                </script>
                                <?php
                                break;
                            default:
                                break;
                            }
                        ?>
                        
                        <?php
                    }
                }   
            } catch (PDOException $e) {
                echo "接続失敗 ";
                header("Location: error.php?error_code=701");
            }
            ?>

        <script>
            //利用状況記録スライダー機能実装
            var space_id_global = <?php echo $default_space_id;?>;
            show_space( space_id_global );

            function show_space(space_id){
                $('.space_id_' + space_id).removeClass("js_hidden");
            }

            function hide_space(space_id){
                $('.space_id_' + space_id).addClass("js_hidden");
            }

            function prev_space(){
                if(space_id_global == 1){
                }else{
                    hide_space(space_id_global);
                    show_space(space_id_global - 1);
                    space_id_global = space_id_global - 1;
                }
            }

            function next_space(){
                if(space_id_global == <?php echo $max_space_id;?>){
                }else{
                    hide_space(space_id_global);
                    show_space(space_id_global + 1);
                    space_id_global = space_id_global + 1;
                }
            }
        </script>

        <!--各団体の予約状況生成-->
        <?php
        try{
            $stmt = $dbh->prepare('SELECT * FROM users');
            $res = $stmt->execute();
            while($result = $stmt->fetch()){
                //usersテーブルからユーザを取得し、それぞれの団体ごとにgroup_ticket生成
                $user_id = $result['user_id'];
                $user_name = $result['user'];
                ?>
                <script>
                    table = document.getElementById("content_2");
                    add_code = "<div class='group_ticket'><h2><?php echo $user_name;?></h2><div id='datetime_container_<?php echo $user_id;?>' class='datetime_container'></div></div>";
                    table.insertAdjacentHTML( 'beforeend', add_code);
                </script>
                <?php
                //その団体の予約チケットを取得してpタグをdatetime_container内に生成
                $stmt1 = $dbh->prepare("SELECT * FROM tickets WHERE user_id = ".$user_id." AND ( status = 'reserved' OR status = 'before')");
                $res1 = $stmt1->execute();
                while($result1 = $stmt1->fetch()){
                    $result_time = date('G:i',strtotime($result1['starting_time']))."~".date('G:i',strtotime($result1['ending_time']));
                    $result_month = date('n',strtotime($result1['starting_time']));
                    $result_date = date('d',strtotime($result1['starting_time']));
                ?>
                <script>
                    table = document.getElementById("datetime_container_<?php echo $user_id;?>");
                    add_code = "<p><?php echo $result_month;?>月<?php echo $result_date;?>日    <?php echo $result_time;?></p>";
                    table.insertAdjacentHTML( 'beforeend', add_code);
                </script>
                <?php
                }
            }
        } catch (PDOException $e) {
            echo "接続失敗 ";
            header("Location: error.php?error_code=701");
        }
        ?>

        <?php
        //団体の予約内容取得処理
        try {
            $stmt = $dbh->prepare("SELECT * FROM tickets WHERE status = 'unused' ");
            $res = $stmt->execute();
            while($result = $stmt->fetch()) {
                $ticket_month = date('n',strtotime($result['starting_time']));
                $ticket_date = date('d',strtotime($result['starting_time']));
                $ticket_starting_time = date('G:i',strtotime($result['starting_time']));
                $ticket_ending_time = date('G:i',strtotime($result['ending_time']));
                $ticket_id = $result['ticket_index'];
                $ticket_user_id = $result['user_id'];
                $stmt1 = $dbh->prepare("SELECT * FROM users WHERE user_id = ".$ticket_user_id);
                $res1 = $stmt1->execute();
                $result1 = $stmt1->fetch();
                $ticket_user = $result1['user'];
                ?>
            <script>
                //チケットのhtml挿入処理
                add_code = "<div class='reserved_ticket'><div class='ticket_left'><p class='ticket_top'>日付</p><h3 class='ticket_top'><?php echo $ticket_month; ?><span class='smallLetter'>月</span><?php echo $ticket_date; ?><span class='smallLetter'>日</span></h3><p class='ticket_bottom'>予約団体</p><h3 class='ticket_bottom'><?php echo $ticket_user; ?></h3></div><div class='ticket_right'><p class='ticket_top'>時間帯</p><h3 class='ticket_top'><?php echo $ticket_starting_time; ?>～<?php echo $ticket_ending_time; ?></h3><p class='ticket_bottom'>チケットID</p><h3 class='ticket_bottom'><?php echo $ticket_id; ?></h3></div></div>";
                document.getElementById("content_3").insertAdjacentHTML( 'beforeend', add_code);
            </script>
        <?php
            }
        } catch (PDOException $e) {
            echo "接続失敗 ";
            header("Location:error.php?error_code=701");
        };
        ?>
        <!--利用状況送信処理-->
        <script>
            async function fetchData(ticket_id, status) {
                console.log(status) ;
                const postData = {
                    ticket_id: ticket_id,
                    status: status
                }
                var data = await fetch("request.php",
                {
                    method: 'POST',
                    headers: {
                    'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(postData)
                } );
            }
            var selectors = document.getElementsByClassName("selector");
            var selectors_array = Array.from(selectors);
            selectors_array.forEach(function(target) {
            target.addEventListener('change',  (e) => {
                var str = target.id;
                var id = str.substr(7);
                SelectedValue = e.target.value;
                fetchData(id, SelectedValue);
            })});

            
        </script>
    </body>
</html>