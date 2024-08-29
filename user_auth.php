<?php

//ログイン確認処理
session_start();
if (isset($_SESSION['user_id'])) {
    //ログインしている時
    //セッションからユーザ名等取得
    $username = $_SESSION['user'];
    $user_id = $_SESSION['user_id'];
    //閲覧権限
    switch ($current_page) {
        case "admin":
            if($user_id != 1){
                header("Location:error.php?error_code=708");
            }
        break;
        case "loginform":
            header("Location:index.php");
        break;
        default:
            if($user_id == 1){
                header("Location:admin.php");
            }
    }
} else {
    //ログインしていない時
    if($current_page != "loginform"){
        header("Location:loginform.php");
    }
}