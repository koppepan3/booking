<?php include('dbconnect.php'); ?>
<?php
session_start();
$dantai = $_POST['dantai'];

$sql = "SELECT * FROM users WHERE dantai = :dantai";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':dantai', $dantai);
$stmt->execute();
$member = $stmt->fetch();
$dbpass = password_hash($member['pass'], \PASSWORD_DEFAULT);
//指定したハッシュがパスワードにマッチしているかチェック
if (password_verify($_POST['pass'], $dbpass)) {
    //DBのユーザー情報をセッションに保存
    $_SESSION['index'] = $member['index'];
    $_SESSION['dantai'] = $member['dantai'];
    header('Location: index.php');
    $msg = 'ログイン成功';
} else {
    $msg = '団体名またはパスワードが間違っています。';
}
?>

<h1><?php echo $msg; ?></h1>