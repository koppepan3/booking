<?php
//ファイルパス取得処理
$current_page = basename(__FILE__, ".php");

//共通バックエンド処理読み込み
include('backend.php');

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
        <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">

        <!-- 個別設定 -->
        <title>駐輪場予約サイト</title>
        <link rel="stylesheet" href="index.css">
    <body>
        <header>
            <a href="index.php"><img src="file/logo.svg" class="header_logo" alt=""></a>
        </header>
        <div id="notification_area">
                <div class="notification notification_infomation">
                    <img src="file/information.svg" alt="">
                    <h2>予約上限に達しました</h2>
                    <p>１つの団体で同時に予約できる枠数(2枠)に達しました。現在の予約枠をキャンセル、もしくは現在の予約枠を利用した後に、新たな予約ができるようになります。</p>
                </div>
                <div class="notification notification_warning">
                    <img src="file/warning.svg" alt="">
                    <h2>予約上限に達しました</h2>
                    <p>１つの団体で同時に予約できる枠数(2枠)に達しました。現在の予約枠をキャンセル、もしくは現在の予約枠を利用した後に、新たな予約ができるようになります。</p>
                </div>
            </div>
        <div id="contents">
            
            <div class="content">
                <h1 class="content_subtitle">利用にあたって</h1>
            </div>
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
                    <?php GenerateCalender($date_calendar_array, $date_class_array, $ymd_calendar_array);?>
                </table>
            </div>
            <div id="content_3" class="content">
                <h1><?php if (isset($username) ){echo $username;} ?>の予約状況</h1>
                <div id="ticket_list">
                    <?php GenerateUserReservedTickets($dbh, $user_id, $username); ?>
                </div>
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
    </body>
</html>