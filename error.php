<?php
//ログイン確認処理
session_start();
if (isset($_SESSION['user_id'])) {//ログインしている時
    $username = $_SESSION['user'];
    $user_id = $_SESSION['user_id'];
} else {//ログインしていない時
    header("Location:loginform.php");
}

if(isset($_GET['error_code'])) { $error_code = $_GET['error_code']; } 

//エラーコードからエラー内容を取得
switch($error_code){
    case 700:
        $error = "予約の一時間前のためキャンセルはできません";
        break;
    case 701:
        $error = "データベース接続失敗";
        break;
    case 703:
        $error = "該当の枠は既に埋まっています";
        break;
    case 704:
        $error = "この予約は既にキャンセル済みです";
        break;
    case 707:
        $error = "同時に予約可能な枠数の上限に達しました";
        break;
    case 708:
        $error = "管理画面ログインエラー<br>　備品班としてログインしてください";
        break;
    default:
        $error_code = "0";
        $error = "該当するエラーはありません";
        break;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>駐輪場予約サイト/エラー<?php echo $error_code; ?></title>
        <meta name="description" content="" />
        <link rel="stylesheet" href="error.css">
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
        <div id="content_1" class="content">
            <h1>エラーが発生しました</h1>
            <p>エラーコード: <?php echo $error_code; ?></p>
            <p>エラーの内容: <?php echo $error; ?></p>
            <p>エラーが繰り返し発生する場合は文化祭本部までお問い合わせください。</p>
            <button class="submit_button" onclick="location.href='index.php'">トップページに戻る</button>
        </div>
    </body>
</html>