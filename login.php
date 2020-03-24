<?php
session_start();
require('dbconn.php');

$dte = new datetime();
$email='';
if (isset($_COOKIE['email']) && $_COOKIE['email'] !== ''){
  $email = $_COOKIE['email'];
}
if (!empty($_POST)){
  $email = $_POST['email'];
  if ($_POST['email'] !== '' && $_POST['password'] !== ''){
    $login = $db->prepare('SELECT * FROM m_user WHERE email = :email AND pw = :pw');
    $login->bindParam(':email',$_POST['email'],PDO::PARAM_STR_CHAR);
    $login->bindValue(':pw',sha1($_POST['password']));
    $login->execute();
    $user = $login->fetch();
    if ($user){
      $_SESSION['id'] =$user['id'];
      $_SESSION['datetime'] =$dte;

      if (isset($_POST['save']) && $_POST['save'] === 'on'){
          setcookie('email', $_POST['email'],time()+60*60*24*7);
      }
      header('Location: index.php');
      exit();
    }else{
      $error['login']='failed';
    }
  } else {
    $error['login']='blank';
  }
} else {
  $error['login']='blank';
}
if($email ==''){
  $error['login']='blank';
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="../css/style.css" />
<title>ユーザーログイン</title>
</head>
<body>
<header>
  <div id="head">
    <h1>■ユーザーログイン</h1>
  </div>
  <div id="lead">
    <p>ユーザー登録手続きがまだの方はこちらから。</p>
    <p>&raquo;<a href="join/">ユーザー登録手続きへ</a></p>
  </div>
  <hr size="20" noshade>
 </header> 
  <div id="content">
  <p>メールアドレスとパスワードを入力しログインしてください。</p>
    <form action="" method="post">
      <dl>
        <dt>◆メールアドレス</dt>
        <dd>
          <input type="text" name="email" size="35" maxlength="255" value="<?php print(htmlspecialchars($email,ENT_QUOTES)); ?>" />
          <?php if(!empty($error)): ?>
            <?php if($error['login'] === 'blank'): ?>
              <p class="error">＊メールアドレスとパスワードを入力してください。 </p>
            <?php endif; ?>
            <?php if($error['login'] === 'failed'): ?>
              <p class="error">＊ログイン失敗！正しいログイン情報を入力してください。</p>
            <?php endif; ?>
          <?php endif; ?>
        </dd>
        <dt>◆パスワード</dt>
        <dd>
          <input type="password" name="password" size="35" maxlength="255" value="<?php if (!empty($_POST)){print(htmlspecialchars($_POST['password'],ENT_QUOTES));}?>" />
        </dd>
        <br>
        <dt></dt>
        <dd>
          <input type="checkbox" name="save" value="on" id="save"> ＊次回から自動的にログインする場合はチェックを入れてください。
        </dd>
      </dl>
      <div>
        <input type="submit" value="ログインする" />
      </div>
    </form>
  </div> 
  <hr size="20" noshade>
<footer>
　<div id="foot">
    <p style="text-align: center"> &copy NANZIYO 2020</p>
    <hr size="20" noshade>
  </div>
</footer> 
</body>
</html>
