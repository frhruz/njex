<?php
 session_start();
 require_once('../dbconn.php');

 if (!empty($_POST)){
	if ($_POST['name'] ===''){
		$error['name'] = 'blank';
	}
	if ($_POST['email'] ===''){
		$error['email'] = 'blank';
	}
	if (strlen($_POST['password']) < 6) {
		$error['password'] = 'length';
	}
	if ($_POST['password'] ===''){
		$error['password'] = 'blank';
	}
	if (empty($error)){
		$member = $db->prepare('SELECT COUNT(*) AS cnt FROM m_user WHERE email=?'); 
		$member->execute(array($_POST['email']));
		$record = $member->fetch();
		if ($record['cnt'] > 0){
			$error['email'] = 'duplicate';
		}
	} 

	if(empty($error)){
		$_SESSION['signup']=$_POST;
		header('Location: check.php');
		exit();
	}
 }
 if (!empty($_REQUEST)){
 	if($_REQUEST['action'] == 'rewrite' && isset($_SESSION['signup'])){
		$_POST = $_SESSION['signup'];
 	}
 }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<meta name="description" content="業務日報システム ユーザー登録, 9cjat-e.Radio Trial, NANZIYO"/>
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
	<title>ユーザー登録</title>
</head>
<body class="text-center">
  <form action="" method="post" style="text-align: center" class="form-signin" enctype="multipart/form-data"> 
    <!-- eyecatch -->
	<img class="mb-4" src="../../img/n.png" alt="9chat-e.radio:trial:NANZIYO" width="72" height="72"> 
    <!-- description for Signup form -->
	<h1 class="h3 mb-3 font-weight-normal">Please sign up <br> for the DailyReport System.<br></h1>
	<p class="mt-5 mb-3 text-muted">ユーザー登録フォーム<br>以下フォームに必要事項をご記入ください。<br>
	<span style="color: #f33;">*Required items. *マークは入力必須項目です。</span>
	</p>
	<!-- Check input data -->
		<?php if(isset($error)): ?>
			<span class="required">Error</span>
		<?php endif; ?>
		<?php if ($error['name'] === 'blank'): ?>		
			<p class="error">ユーザー名を入力してください</p>
		<?php endif; ?>
		<?php if ($error['email'] === 'blank'): ?>
			<p class="error">メールアドレスを入力してください</p>
		<?php endif; ?>	
		<?php if ($error['email'] === 'duplicate'): ?>
			<p class="error">入力したメールアドレスは既に登録されています。</p>
		<?php endif; ?>
		<?php if ($error['password'] === 'length'): ?>
			<p class="error">パスワードは6文字以上で入力してください。</p>
		<?php endif; ?>	
		<?php if ($error['password'] === 'blank'): ?>
			<p class="error">パスワードを入力してください</p>
		<?php endif; ?>	
	<!-- Form -->
	<label for="inputName" class="sr-only">Your Name or nickname</label>
    <input type="name" id="inputName" name="name" class="form-control" placeholder="Your Name or nickname. *" required autofocus value="<?php print(htmlspecialchars($_POST['name'],ENT_QUOTES)); ?>" >
	<!-- <input type="text" name="name" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['name'],ENT_QUOTES)); ?>" /> -->
	<br>	
	<label for="inputEmail" class="sr-only">Email address</label>
    <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address. *" required autofocus value="<?php print(htmlspecialchars($email,ENT_QUOTES)); ?>" >
	<!-- <input type="text" name="email" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['email'],ENT_QUOTES)); ?>" /> -->
	<br>		
    <label for="inputPassword" class="sr-only">Password</label>
    <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password. *" required value="<?php if (!empty($_POST)){print(htmlspecialchars($_POST['password'],ENT_QUOTES));}?>" >
	<!-- <input type="password" name="password" size="10" maxlength="20" value="<?php print(htmlspecialchars($_POST['password'],ENT_QUOTES)); ?>" /> -->
	<br>
	<button class="btn btn-lg btn-primary" type="submit">ユーザー登録:Sign up</button>
	<p class="text-muted"> &copy 2020 NANZIYO</p>
  </form>
</body>
</html>
