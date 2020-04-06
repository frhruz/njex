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
    //$login->execute(array($_POST['email']), sha1($_POST['password']));//sample
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

<!DOCTYPE html>
<html lang="ja">
<head>
<meta name="description" content="業務日報システムログイン"/>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!-- <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> -->
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="../css/style.css" />
<title>ユーザーログイン</title>
</head>
<body>
<header>
    <h1>■ユーザーログイン</h1>
    <div id="lead">
      <p>ユーザー登録手続きがまだの方はこちらから。</p>
      <p>&raquo;<a href="signup/">ユーザー登録手続きへ</a></p>
    </div>
  <hr size="20" noshade>
</header> 
<div class="content">
   <p style="text-align: center">メールアドレスとパスワードを入力しログインしてください。</p> 
    <form action="" method="post" style="text-align: center">
      <dl>
        <dt>◆メールアドレス</dt>
        <dd>
          <input type="text" name="email" size="35" maxlength="255" value="<?php print(htmlspecialchars($email,ENT_QUOTES)); ?>" />
          <?php if(!empty($error)): ?>
            <?php if($error['login'] === 'blank'): ?>
              <p class="error">＊メールアドレスとパスワードを入力してください。 </p>
            <?php endif; ?>
            <?php if($error['login'] === 'failed'): ?>
              <p class="error" style="color: red;">＊ログイン失敗！正しいログイン情報を入力してください。</p>
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
          <input type="checkbox" name="save" id="save" value="on" checked > 次回から自動的にログインする。
        </dd>
      </dl>
      <div>
        <input type="submit" value="ログインする" />
      </div>
    </form>
  <hr size="20" noshade>
</div> 
<footer>
    <p style="text-align: center"> &copy 2020 NANZIYO</p>
</footer> 
</body>
</html>