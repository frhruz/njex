<?php
 session_start();
 require_once('../dbconn.php');
 
 if(!isset($_SESSION['signup'])){
	header('Location: index.php');
	exit();
 }
 if (!empty($_POST)){
	$statement = $db->prepare('INSERT INTO m_user SET name=?, email=?, pw=?, regist_user=?, update_user=?, regist_time=now()');
	$statement->execute(array(
		$_SESSION['signup']['name'],
		$_SESSION['signup']['email'],
		sha1($_SESSION['signup']['password']),
		$_session['signup']['name'],
		$_session['signup']['name']));

	unset($_SESSION['signup']);
	header('Location: thanks.html');
	exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">	
	<meta name="description" content="業務日報システム ユーザー登録確認, 9cjat-e.Radio Trial, NANZIYO"/>	
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
	<link rel="stylesheet" type="text/css" href="../../css/signin.css" />
	<title>Check Your Sign up info.<br>ユーザー登録内容の確認</title>
</head>
<body class="text-center">
  <form action="" method="post" style="text-align: center" class="form-signin" enctype="multipart/form-data"> 
    <!-- eyecatch -->
	<img class="mb-4" src="../../img/n.png" alt="9chat-e.radio:trial:NANZIYO" width="72" height="72"> 
	<h1 class="h3 mb-3 font-weight-normal">Please Check your Sign up information! <br> for the DailyReport System.<br></h1>
	<!-- description for Signup form -->
    <p class="mt-5 mb-3 text-muted">記入した内容を確認して、<br>ユーザー登録してください。</p>
	<input type="hidden" name="action" value="submit"/>

	<table class="table">
    <tbody>
    <tr>
      <th scope="row">ユーザー名</th>
      <td><?php print(htmlspecialchars($_SESSION['signup']['name'],ENT_QUOTES)); ?></td>
    </tr>
    <tr>
      <th scope="row">メールアドレス</th>
      <td><?php print(htmlspecialchars($_SESSION['signup']['email'],ENT_QUOTES)); ?></td>
	</tr>
	<tr>
      <th scope="row">パスワード</th>
      <td>【表示されません】</td>
    </tr>
  </tbody>
  </table>

	<a href="index.php?action=rewrite">&laquo;&nbsp;ユーザー登録情報を修正する</a><br><br>
	<button class="btn btn-lg btn-primary btn-block" type="submit">Sign up:ユーザー登録</button><br>
	<div> 
      <p class="mt-3 text-muted"> &copy 2020 NANZIYO</p>  
    </div>
  </form>
  
</body>
</html>
