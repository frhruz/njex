<?php
session_start();
require_once('dbconn.php');
require_once('dailyreport.php');

$dte = new datetime();
$dte1h = new datetime();;
$dte1h -> add(DateInterval::createFromDateString('1 hour'));
//Check_signin:
if(!empty($_SESSION) && isset($_SESSION['id'])){
    $edt_jnlno="";
    if(isset($_REQUEST['jnl_no'])){
        $edt_jnlno=$_REQUEST['jnl_no'];
    }
    //Session_limit: lastSession past from 1day
    $dte1dy = date_add($_SESSION['datetime'],date_interval_create_from_date_string("1 days"));
    if ($dte1dy > $dte){
             $_SESSION['datetime'] = $dte;
             $id =$_SESSION['id'];
             $user = get_userinfo($db,$id); 
    }
} else {
        header('Location: signin.php');
        exit();
}
//Show_editation_data:
$jnl_data="";
if (!empty($edt_jnlno)){
    $jnl_data = get_journal_data($db,$edt_jnlno,$_SESSION['id']);
}
//Check_User:
if(!empty($_POST)){   
	if (empty($error) && isset($_POST['email'])){
        $userdata->execute(array($_POST['email']));
        $record = $userdata->fetch();     
		if ($record['cnt'] = 0){
			$error['email'] = 'none';
        }
    } else {
        if (isset($error) && $error['email'] ==='none'){
            header('Location: ./signup/index.php');
            exit();
        }	 	
    }
    //Insert_database:
    if(isset($_REQUEST['insert'])){
        if($_POST['consulting_date'] !== '' and $_POST['title'] !==''){
            exec_insert_db($db,$_POST,$dte,$_SESSION['id']);
        }
    } elseif (isset($_REQUEST['update']) && isset($_REQUEST['jnl_no'])) {
    //Udate_database:
        if($_POST['consulting_date'] !== '' and $_POST['title'] !==''){
            exec_update_db($db,$_POST,$edt_jnlno,$dte,$_SESSION['id']);
        }
    }
    //Clear_POSTdata:
    if (empty($_POST["txtbox_search"])){
        header('Location: index.php');
        exit();
    } 
}
//Show_Data:
if (isset($user)){
  if (!empty($_REQUEST)){
    $query=get_search_text($_REQUEST['txtbox_search']);
  } else {
    $query="";
  }
    $username=(string)$user['name'];
    $rows = show_data($db,$query,$_SESSION['id']);	
  }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="description" content="日報キーワード検索"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>DailyReport | 業務日報Web版</title>

    <!-- Bootstrap core CSS -->
    <link rel="canonical" href="https://getbootstrap.com/docs/4.2/examples/starter-template/"> 
    <!-- Bootstrap core CSS -->
    <link href="./css/starter_bootstrap/bootstrap.min.css" rel="stylesheet">  
    <style type="text/css">
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
      }

      /* table td a.widelink {
        text-decoration: none;
        color: blue;
        display: block;
        width: 100%;
        height: 100%;
      } */

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }

      img.logo-icon{
        width: 1.08em;
        height: 1.08em;
        /* width: 21.5px; */
        /* height: 21.5px; */
        vertical-align: text-top;
      }

    </style>
    <link rel="stylesheet" type="text/css" href="./css/starter_bootstrap/starter-template.css">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <script src="./css/starter_bootstrap/jquery-3.3.1.slim.min.js"></script>
    <script src="./css/starter_bootstrap/bootstrap.bundle.min.js"></script>
    <link rel="shortcut icon" href="./img/logo-trial.png" type="image/png">
</head>
<body> 
  <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="./index.php" onclick="javascript:confirm('ページリセットしますか？');">
    <img src="./img/logo-trial.png" type="image/png" class="logo-icon">&nbsp;業務日報Web版&raquo;</a>
    <!-- <a class="navbar-brand" href="./index.php" onclick="javascript:var ret; ret=confirm('ページリセットしますか？'); if(ret){window.location.reload(true);}">業務日報Web版&raquo;</a> -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item"><!--test: <li class="nav-item active"> -->
            <a class="nav-link" href="http://9chat-e.mynt.work/" target="_blank">9Chat-e.Radio.Site&raquo;</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="./signin.php" onclick="javascript:return confirm('サインアウトしますか？')">Sign out&raquo;</a>
        </li>
      </ul>
    </div>
    <div>
      <!-- Search record -->
      <form class="form-inline my-2 my-lg-0" action="" method="post" onsubmit="return false;">　
        <input class="form-control mr-sm-2 txtbox_search" type="search"  name="txtbox_search" placeholder="　データ抽出" aria-label="Search" value="<?php if(!empty($_REQUEST['txtbox_search'])){print($_REQUEST['txtbox_search']);} ?>">
        <input type="button" class="btn btn-secondary my-2 my-sm-0" onclick="submit();" value="Search!">
        <!--test: <button class="btn btn-secondary my-2 my-sm-0" type="submit" name="btn_search" >Search!</button>  --> 
      </form>
    </div>
   </nav>

<main role="main" class="container" style="margin:0 auto; padding-top: 0px;">
<div class="starter-template">
<div class="container">
 <section class="area">
  <p>  
    ◆ログインユーザー: <?php print(htmlspecialchars($user['name'],ENT_QUOTES)); ?> さん &emsp;
    ◆Email: <?php print(htmlspecialchars($user['email'],ENT_QUOTES)); ?> <br>
  </p>
  <p class="lead" style="margin:0 auto;">
    <?php print(htmlspecialchars($user['name'],ENT_QUOTES)); ?>さん、こんにちは。本日も無理なく続けていきましょう。<br> 
  </p>
    <?php if (isset($rows)) { ?>
    　<form action="" method="get">
      <h5>■業務日報データ一覧</h5>
      <table class='table'>
        <thead>
          <tr>
            <th scope="col">[#]活動日</th>
            <th scope="col">時間帯</th>
            <th scope="col">タイトル</th>
            <th scope="col">データ更新日時</th>
          </tr>                   
        </thead>
        <tbody>
        <?php foreach ($rows as $row){ ?>
            <?php $clm0 = htmlspecialchars($row['jnl_no']); ?> 
            <?php $clm1 = htmlspecialchars($row['consulting_date']); ?> 
            <?php $clm2 = htmlspecialchars($row['consulting_timezone']); ?>
            <?php if(mb_strlen($row['title']) > 20): ?>
                <?php $clm3 = htmlspecialchars(mb_substr($row['title'],0,20))."…"; ?>
            <?php else: ?>
                <?php $clm3 = htmlspecialchars($row['title']); ?>
            <?php endif; ?>
            <?php if(mb_strlen($row['content']) > 14): ?>
                <?php $clm4 = htmlspecialchars(mb_substr($row['content'],0,14)."…"); ?>
            <?php else: ?>
                <?php $clm4 = htmlspecialchars($row['content']); ?>
            <?php endif; ?>
            <?php if(mb_strlen($row['solution']) > 14): ?>
                <?php $clm5 = htmlspecialchars(mb_substr($row['solution'],0,14)."…"); ?>              
            <?php else: ?>
                <?php $clm5 = htmlspecialchars($row['solution']); ?>
            <?php endif; ?>
            <?php if(mb_strlen($row['remarks']) > 14): ?>
                <?php $clm6 = htmlspecialchars(mb_substr($row['remarks'],0,14)."…"); ?>
            <?php else: ?>
                <?php $clm6 = htmlspecialchars($row['remarks']); ?>
            <?php endif; ?>
            <?php $clm7 = htmlspecialchars($row['schedule_date']); ?>
            <?php $clm8 = htmlspecialchars($row['update_time']); ?>         
            <?php if(!empty($edt_jnlno) && $clm0 == $edt_jnlno): ?>
                <tr bgcolor="#cccccc">
            <?php else: ?>
                <tr onclick="location.href='index.php?jnl_no=<?php print(htmlspecialchars($clm0));?>&txtbox_search=<?php if(!empty($_REQUEST)){print($_REQUEST['txtbox_search']);}?>&edit=true#edit'">
            <?php endif; ?>
                <td><?php echo "[{$clm0}] ".$clm1; ?></td>
                <td><?php echo $clm2; ?></td>
                <td><?php echo $clm3; ?></td>     
                <td><?php echo nl2br($clm8); ?></td>
            </tr>
            </tbody>
        <?php } ?>
        </table>
        <?php } else { ?>
        <p>None</p> 
        <?php } ?>
      </table>
      </form>
     <hr noshade>
 </section>
 <section class="area">
  <div class="form_data">
    <label for="label1"><a name="edit">業務日報<?php if(empty($_REQUEST)){print('新規');}else{print('編集');}?>入力フォームの表示切替え</a></label>
    <input type="checkbox" id="label1" <?php if(!empty($edt_jnlno)){print('checked=checked');} ?>/>    
    <div class="close_n_show">
    <form action="" method="post"　onsubmit="return false;">
        <?php if (!empty($edt_jnlno)):?>
            <h5><a name="edit">■業務日報データ編集</a></h5> 
            <p>
                <?php print(htmlspecialchars($user['name'],ENT_QUOTES)); ?>さん、選択内容の修正をおこなってからご登録ください。
            </p>
        <?php else: ?>
            <h5>■業務日報データ新規登録</h5>
            <p>
                <?php print(htmlspecialchars($user['name'],ENT_QUOTES)); ?>さん、本日の仕事内容をご登録ください。
            </p>
        <?php endif; ?>　
        <dl>
            <?php if (!empty($edt_jnlno)): ?>
                <dt>◆ジャーナルNO. <?php print($jnl_data['jnl_no']); $_POST['jnl_no']=$edt_jnlno;?>
                &emsp;◆対応日と時間帯
                </dt>
                <dd></dd>  
            <?php endif; ?>
            <br>
            <dt></dt>
            <dd>
                <?php if (!empty($edt_jnlno)): ?>
                    <input type="date" name="consulting_date" value="<?php print($jnl_data['consulting_date']);?>">
                    <input type="text" name="consulting_timezone" value="<?php print($jnl_data['consulting_timezone']);?>">
                <?php else: ?>
                    <input type="date" name="consulting_date" value="<?php echo date('Y-m-d');?>"> 
                    <input type="text" name="consulting_timezone" value="<?php echo $dte->format('H:00').'-'.$dte1h->format('H:00');?>">
                <?php endif; ?>
            </dd>
            <dt>◆タイトル（128文字迄）</dt>
            <dd>
                <?php if (!empty($edt_jnlno)): ?>
                    <textarea name="title" cols="50" rows="2"><?php print($jnl_data['title']);?></textarea>   
                <?php else: ?>             
                    <textarea name="title" cols="50" rows="2"></textarea>
                <?php endif; ?>        
            </dd>
            <dt>◆仕事内容（1024文字迄）</dt>
            <dd>
                <?php if (!empty($edt_jnlno)): ?>
                    <textarea name="content" cols="50" rows="5"><?php print($jnl_data['content']);?></textarea>   
                <?php else: ?>             
                    <textarea name="content" cols="50" rows="5"></textarea>
                <?php endif; ?>
            </dd>
            <dt>◆対応内容（1024文字迄）</dt>
            <dd>
                <?php if (!empty($edt_jnlno)): ?> 
                    <textarea name="solution" cols="50" rows="4"><?php print($jnl_data['solution']);?></textarea>   
                <?php else: ?>             
                    <textarea name="solution" cols="50" rows="4"></textarea>
                <?php endif; ?>
            </dd>
            <dt>◆備考（256文字迄）</dt>
            <dd>
                <?php if (!empty($edt_jnlno)): ?> 
                    <textarea name="remarks" cols="50" rows="2"><?php print($jnl_data['remarks']);?></textarea>     
                <?php else: ?>             
                    <textarea name="remarks" cols="50" rows="2"></textarea>
                <?php endif; ?>
            </dd>
            <br>
            <dt></dt>
            <dd> 
                <?php if (!empty($edt_jnlno)): ?>      
                    <input type="checkbox" name="del_flg" value=<?php if(!empty($edt_jnlno)){print("true");}?> id="del_flg" />&emsp;※登録内容を削除する場合はチェックしてください。
                <?php endif; ?>
            </dd>
            <dt></dt>
            <dd>      
                <input type="hidden" name="jnl_no" value="<?php if(!empty($edt_jnlno)){print($edt_jnlno);}?>" />
            </dd>    
            <div class="regist_data">
                <dt></dt>
                <dd> 
                    <?php if (!empty($edt_jnlno)):
                        $btn_name=array("update","更新登録する");    
                    else:
                        $btn_name=array("insert","新規登録する"); 
                    endif;?>
                    <button type="submit" class="btn tn-lg btn-primary" name="<?php print($btn_name[0]);?>" onclick="javascript:confirm('入力した項目を登録しますか？');"><?php print($btn_name[1]);?></button>     
                </dd>
            </div>
        </dl>
    </form>
    <div class="bottom" style="text-align: center">
        <p>
            <a href="#">▲ ページトップへ</a>
        </p>
    </div>
  </div> <!-- close_n_show -->
  </div> <!-- form_data -->   
</section>
  <hr noshade> 
        <p class="mt-3 text-muted"> &copy 2020 NANZIYO</p> 
</div> <!-- starter templete -->
</div> <!-- class="container"> -->
</main>
</body> 
</html>