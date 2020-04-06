<?php
session_start();
require('../dbconn.php');
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<title>ユーザー登録</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link rel="stylesheet" href="../../css/style.css" />
</head>
<body>
<header>
	<h1>ユーザー登録</h1>
</header>
<main>
  <div id="content">
	<p>ユーザー登録が完了しました</p>
	<p><a href="../">ログインする</a></p>
  </div>
</main>
<footer>
    <p style="text-align: center"> &copy 2020 NANZIYO</p>
</footer> 
</body>
</html>
