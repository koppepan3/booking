<?php
session_start();
if (isset($_SESSION['user_id'])) {//ログインしているとき
    header("Location:index.php");
} else {//ログインしていない時
}
include('dbconnect.php');
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
    </head>
    <body>
        <header>
                <a href="index.php"><h1>駐輪場予約サイト</h1></a>
        </header>
        <div id="contents">
            <div class="content1">
                <h1>予約システムログイン</h1>
                <form action="login.php" method="post">
                    <div id="select_holder" class="holder">
                        
                        <label>
                            HR・部活<?php echo "sfdsdf";?>
                            <select name="user" required>
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
                        </label>
                    </div>
                    <div id="pass_holder" class="holder">
                        <label>
                            パスワード
                            <input type="password" name="pass" id="textbox" required>
                        </label>
                    </div>
                    <input type="submit" value="ログイン" id="submit">
                </form>
            </div>
        </div>
        

        <!--jQuery読み込み-->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    </body>
</html>