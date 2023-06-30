<?php
session_start();
//$username = $_SESSION['name'];
if (isset($_SESSION['index'])) {//ログインしているとき
    header("Location:index.php");
} else {//ログインしていない時
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>駐輪場予約サイト/ログイン</title>
        <meta name=”description” content="" />
        <link rel="stylesheet" href="login.css">
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
        <div id="form">
            <h1>予約システムログイン</h1>
            <form action="login.php" method="post">
            <div id="select_holder" class="holder">
                <label>
                    HR・部活
                    <select name="dantai" required>
                        <option value="11hr">11HR</option>
                        <option value="12hr">12HR</option>
                        <option value="13hr">13HR</option>
                        <option value="14hr">14HR</option>
                        <option value="15hr">15HR</option>
                        <option value="16hr">16HR</option>
                        <option value="17hr">17HR</option>
                        <option value="21hr">21HR</option>
                        <option value="22hr">22HR</option>
                        <option value="23hr">23HR</option>
                        <option value="24hr">24HR</option>
                        <option value="25hr">25HR</option>
                        <option value="26hr">26HR</option>
                        <option value="31hr">31HR</option>
                        <option value="32hr">32HR</option>
                        <option value="33hr">33HR</option>
                        <option value="34hr">34HR</option>
                        <option value="35hr">35HR</option>
                        <option value="36hr">36HR</option>
                        <option value="美術班">美術班</option>
                        <option value="写真班">写真班</option>
                        <option value="生活文化">生活文化</option>
                        <option value="百人一首">百人一首</option>
                        <option value="工学情報班">工学情報班</option>
                        <option value="自然科学班">自然科学班</option>
                        <option value="演劇">演劇</option>
                        <option value="吹奏楽">吹奏楽</option>
                        <option value="弦楽">弦楽</option>
                        <option value="国際文化">国際文化</option>
                        <option value="囲碁将棋">囲碁将棋</option>
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
        

        <!--jQuery読み込み-->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    </body>
</html>