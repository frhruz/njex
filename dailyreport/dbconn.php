<?php
 try{
     $db = new PDO('mysql:dbname=dailyreport;host=127.0.0.1;charset=utf8', 'root', 'パスワード');
    } catch(PDOException $e){
    print('DB接続エラー：'. $e->getmessage());
 }
?>