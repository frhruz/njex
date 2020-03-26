<?php
 session_start();
 require('../dbconn.php');

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
		$_SESSION['signup ']=$_POST;
		header('Location: check.php');
		exit();
	}
 }
 if (!empty($_REQUEST)){
 	if($_REQUEST['action'] == 'rewrite' && isset($_SESSION['signup '])){
		$_POST = $_SESSION['signup '];
 	}
 }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>ユーザー登録</title>

	<link rel="stylesheet" href="../../css/style.css" />
</head>
<body>
<div id="wrap">
<div id="head">
<h1>ユーザー登録</h1>
</div>

<div id="content">
<p>次のフォームに必要事項をご記入ください。</p>
<form action="" method="post" enctype="multipart/form-data">
	<dl>
		<dt>ユーザー名<span class="required">必須</span></dt>
		<dd>
			<input type="text" name="name" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['name'],ENT_QUOTES)); ?>" />
			<?php if ($error['name'] === 'blank'): ?>
				<p class="error">* ユーザー名を入力してください</p>
			<?php endif; ?>
		</dd>
		<dt>メールアドレス<span class="required">必須</span></dt>
		<dd>
        	<input type="text" name="email" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['email'],ENT_QUOTES)); ?>" />
			<?php if ($error['email'] === 'blank'): ?>
				<p class="error">* メールアドレスを入力してください</p>
			<?php endif; ?>	
			<?php if ($error['email'] === 'duplicate'): ?>
				<p class="error">* 入力したメールアドレスは既に登録されています。</p>
			<?php endif; ?>	
		</dd>
		<dt>パスワード（6文字以上）<span class="required">必須</span></dt>
		<dd>
        	<input type="password" name="password" size="10" maxlength="20" value="<?php print(htmlspecialchars($_POST['password'],ENT_QUOTES)); ?>" />
			<?php if ($error['password'] === 'length'): ?>
				<p class="error">* パスワードは6文字以上で入力してください</p>
			<?php endif; ?>	
			<?php if ($error['password'] === 'blank'): ?>
				<p class="error">* パスワードを入力してください</p>
			<?php endif; ?>	
		</dd>
		<!--
		<dt>写真など</dt>
		<dd>
        	<input type="file" name="image" size="35" value="test"  />
        </dd>
		-->
	</dl>
	<div><input type="submit" value="入力内容を確認する" /></div>
</form>
</div>
</body>
</html>
