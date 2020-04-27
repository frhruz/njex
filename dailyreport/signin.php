<?php
session_start();
require_once('dbconn.php');

if (!empty($_REQUEST) and ($_REQUEST['action'] !== 'new')){

  $dte = new datetime();
  $email='';
if (isset($_COOKIE['email']) && $_COOKIE['email'] !== ''){
  $email = $_COOKIE['email'];
}
if (!empty($_POST)){
  $email = $_POST['email'];
  if ($_POST['email'] !== '' && $_POST['password'] !== ''){
    $signin = $db->prepare('SELECT * FROM m_user WHERE email = :email AND pw = :pw');
    $signin->bindParam(':email',$_POST['email'],PDO::PARAM_STR_CHAR);
    $signin->bindValue(':pw',sha1($_POST['password']));
    $signin->execute();
    //$signin->execute(array($_POST['email']), sha1($_POST['password']));//sample
    $user = $signin->fetch();
    if ($user){
      $_SESSION['id'] =$user['id'];
      $_SESSION['datetime'] =$dte;

      if (isset($_POST['save']) && $_POST['save'] === 'on'){
          setcookie('email', $_POST['email'],time()+60*60*24*7);
      }
      header('Location: index.php');
      exit();
    }else{
      $error['signin']='failed';
    }
  } else {
    $error['signin']='blank';
  }
} else {
  $error['signin']='blank';
}
if($email ==''){
  $error['signin']='blank';
}
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="description" content="業務日報システムログイン, 9cjat-e.Radio Trial, NANZIYO"/>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
<link rel="stylesheet" type="text/css" href="../css/signin.css" />
<title>業務日報システム ユーザーログイン</title>
</head>
<body class="text-center">
<div class="pw-form pw-form-container">
  <form action="" method="post" style="text-align: center" class="form-signin">
    <img class="mb-4" src="../img/n.png" alt="9chat-e.radio:trial:NANZIYO" width="72" height="72"> 
    <h1 class="h3 mb-3 font-weight-normal">Please sign in<br>to the DailyReport System</h1>
    <p class="mt-5 mb-3 text-muted">※ユーザー登録はこちらへ&nbsp;<a href="signup/">&raquo;SignUp</a><br>
     <?php if(!empty($error)): ?>
      <?php if($error['signin'] === 'blank'): ?>
        <p class="error">Please Enter your Email Address and password!!</p>
      <?php endif; ?>
        <?php if($error['signin'] === 'failed'): ?>
          <p class="error" style="color: red;">Sign In Failed!! Please ReEnter your information!!</p>
        <?php endif; ?>
      <?php endif; ?>
    </p>
    
      <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" required autofocus value="<?php if(!empty($email)){print(htmlspecialchars($email,ENT_QUOTES));} ?>" >
      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" placeholder="Password" id="inputPassword" name="password" class="form-control" required value="<?php if (!empty($_POST)){print(htmlspecialchars($_POST['password'],ENT_QUOTES));}?>">
        <!-- <span class="field-icon"> -->
          <!-- <i toggle="#password-field" class="mdi mdi-eye toggle-password"></i> -->
        <!-- </span> -->
      <!-- <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required value="<?php if (!empty($_POST)){print(htmlspecialchars($_POST['password'],ENT_QUOTES));}?>"> -->
    <p class="checkbox mb-3">
      <!-- <label class="checkbox mb-3"> -->
        <input type="checkbox" name="save" value="on" checked="checked"> Remember me, Signin Info.
      <!-- </label> -->
    </p>
    <button class="btn btn-lg btn-primary" type="submit">Sign in</button>
    <p class="mt-3 text-muted"> &copy 2020 NANZIYO</p>  
  </form>
  </div>
  <script>
  // パスワードの表示・非表示切替
  $(".toggle-password").click(function() {
  // iconの切り替え
  $(this).toggleClass("mdi-eye mdi-eye-off");
  
  // 入力フォームの取得
  var input = $(this).parent().prev("input");
  // type切替
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
  });
  </script>
</body>
</html>