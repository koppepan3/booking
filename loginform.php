<?php
//ファイルパス取得処理
$current_page = basename(__FILE__, ".php");

//ユーザ認証処理読み込み
include('user_auth.php');

//URLパラメータ取得処理
if(isset($_GET['invalidPassword'])){
    $invalidPassword =  $_GET['invalidPassword'];
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>駐輪場予約サイト/ログイン</title>
        <meta name=”description” content="" />
        <link rel="stylesheet" href="loginform.css">
        <link rel="stylesheet" href="style.css">
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
    </head>
    <body>
        <header>
            <a href="index.php"><img src="file/logo.svg" class="header_logo" alt=""></a>
        </header>
        <div id="notification_area">
            <?php
            if($invalidPassword == "true"){
                GenerateNotificationBanner("warning", "パスワードが間違っています", "ユーザ名・パスワードをもう一度確認してやり直してください");
            }
            
            function GenerateNotificationBanner($type, $title, $paragraph){
                switch ($type) {
                    case "information":
                        $svg_path = "file/information.svg";
                        $banner_class = "notification_infomation";
                    break;
                    case "warning":
                        $svg_path = "file/warning.svg";
                        $banner_class = "notification_warning";
                    break;
                }
                echo "<div class='notification {$banner_class}'><img src='{$svg_path}' alt=''><h2>{$title}</h2><p>{$paragraph}</p></div>";
            }
            ?>
        </div>
        <div id="contents">
            <div class="content">
                <h1 class="content_subtitle">予約システムログイン</h1>
                <form action="login.php" method="post">
                    <div id="select_holder" class="holder">
                        <label for="user_select" class="select_label">HR・部活</label>
                        <select id="user_select" name="user" required>
                            <option value="2">11HR</option>
                            <option value="3">12HR</option>
                            <option value="4">13HR</option>
                            <option value="5">14HR</option>
                            <option value="6">15HR</option>
                            <option value="7">16HR</option>
                            <option value="8">17HR</option>
                            <option value="9">21HR</option>
                            <option value="10">22HR</option>
                            <option value="11">23HR</option>
                            <option value="12">24HR</option>
                            <option value="13">25HR</option>
                            <option value="14">26HR</option>
                            <option value="15">31HR</option>
                            <option value="16">32HR</option>
                            <option value="17">33HR</option>
                            <option value="18">34HR</option>
                            <option value="19">35HR</option>
                            <option value="20">36HR</option>
                            <option value="21">美術班</option>
                            <option value="22">写真班</option>
                            <option value="23">生活文化</option>
                            <option value="24">百人一首</option>
                            <option value="25">工学情報班</option>
                            <option value="26">自然科学班</option>
                            <option value="27">演劇</option>
                            <option value="28">吹奏楽</option>
                            <option value="29">弦楽</option>
                            <option value="30">国際文化</option>
                            <option value="31">囲碁将棋</option>
                            <option value="1">備品班</option>
                        </select>
                    </div>
                    <div id="pass_holder" class="holder">
                        <label for="password_input" class="password_label">パスワード</label>
                        <input id="password_input" type="password" name="pass" required>
                    </div>
                    <input type="submit" value="ログイン" id="submit_button">
                </form>
            </div>
        </div>
        

        <!--jQuery読み込み-->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    </body>
</html>