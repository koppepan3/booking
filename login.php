<?php include('dbconnect.php'); ?>
<?php
session_start();
$login_user = $_POST['user'];

//PDO
$sql = "SELECT * FROM users WHERE user_id = :temp";//tempはSQLインジェクション対策　bindValueで実際の値を代入している
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':temp', $login_user);
$stmt->execute();
$db_user = $stmt->fetch();
$dbpass = password_hash($db_user['password'], \PASSWORD_DEFAULT);//指定したハッシュがパスワードにマッチしているかチェック
if (password_verify($_POST['pass'], $dbpass)) {//ログイン成功処理
    //DBのユーザー情報をセッションに保存
    $_SESSION['user_id'] = $db_user['user_id'];
    $_SESSION['user'] = $db_user['user'];
    header('Location: index.php');
} else {//ログイン失敗処理
    header('Location: loginform.php?invalidPassword=true');
}
?>