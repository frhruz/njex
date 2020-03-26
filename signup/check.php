<?php
 session_start();
 require('../dbconn.php');
 
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
	header('Location: thanks.php');
	exit();
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
<p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
<form action="" method="post">
	<input type="hidden" name="action" value="submit" />
	<dl>
		<dt>ユーザー名</dt>
		<dd>
			<?php print(htmlspecialchars($_SESSION['signup']['name'],ENT_QUOTES)); ?>
        </dd>
		<dt>メールアドレス</dt>
		<dd>
			<?php print(htmlspecialchars($_SESSION['signup']['email'],ENT_QUOTES)); ?>
        </dd>
		<dt>パスワード</dt>
		<dd>
		【表示されません】
		</dd>
		<!--<dt>写真など</dt>
		<dd>
		</dd>
		-->
	</dl>
	<div><a href="index.php?action=rewrite">&laquo;&nbsp;ユーザー登録情報修正</a> | <input type="submit" value="登録する" /></div>
</form>
</div>

</div>
</body>
</html>
