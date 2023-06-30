<?php
session_start();
$_SESSION = array();//セッションの中身をすべて削除
session_destroy();//セッションを破壊
?>



<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>駐輪場予約サイト/ログアウトしました。</title>
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
        <style>
            #text{
                font-family: 'Zen Kaku Gothic New';
                font-style: normal;
                font-weight: 500;
                font-size: 17px;
                line-height: 100%;
                color: #000000;
                margin-top: 60px;
                margin-left: 10px;
            }

            #loginLink a{
                font-family: 'Zen Kaku Gothic New';
                font-style: normal;
                font-weight: 500;
                font-size: 17px;
                line-height: 100%;
                color: #000000;
                text-decoration: underline !important;
                margin-left: 10px;
            }
        </style>
    </head>
    <body>
        <header>
                <a href="index.php"><h1>駐輪場予約サイト</h1></a>
        </header>
        <p id="text">ログアウトしました。</p>
        <p id="loginLink"><a href="index.php">ログインへ</a></P>
    </body>
</html>