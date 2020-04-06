<?php
session_start();
require('dbconn.php');

$dte = new datetime();
$dte1h = new datetime();;
$dte1h -> add(DateInterval::createFromDateString('1 hour'));
//Check_signin:
if(!empty($_SESSION) && isset($_SESSION['id'])){
    if(isset($_REQUEST['jnl_no'])){
        $edt_jnlno=$_REQUEST['jnl_no'];
    }
     //Session_limit: lastSession past from 1day
    $dte1dy = date_add($_SESSION['datetime'],date_interval_create_from_date_string("1 days"));
    if ($dte1dy > $dte){
        $_SESSION['datetime'] = $dte;
        $id =$_SESSION['id'];
        $signin = $db->prepare('SELECT * FROM m_user WHERE id =:id');
        $signin->bindParam(':id',$id,PDO::PARAM_INT);
        $signin->execute();
        $user = $signin->fetch();  
    }
} else {
        header('Location: signin.php');
        exit();
}
//Show_editation_data:
if (isset($edt_jnlno)){
    $data = $db->prepare("SELECT * FROM j_dailyreport WHERE jnl_no ='{$edt_jnlno}' AND del_flg=0");
    $data->execute();
    $jnl_data = $data->fetch();  
}
//Check_User:
if(!empty($_POST)){   
	if (empty($error) && isset($_POST['email'])){
        $email = $_POST['email'];
		$member->execute(array($email));
		$record = $member->fetch();
		if ($record['cnt'] = 0){
			$error['email'] = 'none';
        }
    } else {
        if (isset($error) && $error['email'] ==='none'){
            header('Location: /signup/index.php');
            exit();
        }	
       // echo('登録できませんでした。');   	
    }
    //Insert_database:
    if(isset($_REQUEST['regist'])){
        if($_POST['consulting_date'] !== '' and $_POST['title'] !==''){
            $insert_db ="INSERT INTO j_dailyreport ".
                "(customer_id, consulting_date, consulting_timezone,".
                " title, content, solution, remarks,".
                " regist_time, regist_user, update_time, update_user)".
                " VALUES (:customer_id, :consulting_date, :consulting_timezone,".
                " :title, :content, :solution, :remarks,".
                " :regist_time, :regist_user, :update_time, :update_user)";
            $stmt =$db->prepare($insert_db);
            $stmt->bindValue(':customer_id',NULL);
            $stmt->bindvalue(':consulting_date', date("Y-m-d",strtotime($_POST['consulting_date'])));
            $stmt->bindParam(':consulting_timezone',$_POST['consulting_timezone'],PDO::PARAM_STR);
            $stmt->bindParam(':title',$_POST['title'],PDO::PARAM_STR);
            $stmt->bindParam(':content',$_POST['content'],PDO::PARAM_STR);
            $stmt->bindParam(':solution',$_POST['solution'],PDO::PARAM_STR);
            $stmt->bindParam(':remarks',$_POST['remarks'],PDO::PARAM_STR);
            $stmt->bindvalue(':regist_time', $dte->format('Y-m-d H:i:s'));
            $stmt->bindvalue(':regist_user', $id);
            $stmt->bindvalue(':update_time', $dte->format('Y-m-d H:i:s'));
            $stmt->bindvalue(':update_user', $id);
            $stmt->execute();
        }
    } elseif (isset($_REQUEST['update']) && isset($_REQUEST['jnl_no'])) {
    //Udate_database:
        if($_POST['consulting_date'] !== '' and $_POST['title'] !==''){
            $update_db1 ="UPDATE j_dailyreport SET".
                " consulting_date = :consulting_date,".
                " consulting_timezone = :consulting_timezone,".
                " title = :title,".
                " content = :content,".
                " solution = :solution,".
                " remarks = :remarks,".
                " update_time = :update_time,".
                " update_user = :update_user";
            $update_db_del="";  
            if(!empty($_POST['del_flg']) && $_POST['del_flg']== "true"){
                $update_db_del= ", del_flg = :del_flg";
            }
            $update_db2= " WHERE jnl_no =". $edt_jnlno;
            
            $stmt =$db->prepare($update_db1.$update_db_del.$update_db2);
            $stmt->bindvalue(':consulting_date', date("Y-m-d",strtotime($_POST['consulting_date'])));
            $stmt->bindParam(':consulting_timezone',$_POST['consulting_timezone'],PDO::PARAM_STR);
            $stmt->bindParam(':title',$_POST['title'],PDO::PARAM_STR);
            $stmt->bindParam(':content',$_POST['content'],PDO::PARAM_STR);
            $stmt->bindParam(':solution',$_POST['solution'],PDO::PARAM_STR);
            $stmt->bindParam(':remarks',$_POST['remarks'],PDO::PARAM_STR);
            $stmt->bindvalue(':update_time', $dte->format('Y-m-d H:i:s'));
            $stmt->bindvalue(':update_user', $id);
            if(!empty($_POST['del_flg']) && $_POST['del_flg'] == "true"){
                $stmt->bindvalue(':del_flg',1);
            } 
            $stmt->execute();
        }
    }
    //Clear_POSTdata:
    if (empty($_POST["selectcont"])){
        header('Location: index.php');
        exit();
    } 
}
//Show_Data:
// if (isset($user)){
//     $username=(string)$user['name'];
//     $signin = $db->prepare('SELECT  * FROM j_dailyreport WHERE regist_user ='. $id .' AND del_flg = 0 ORDER BY consulting_date DESC, consulting_timezone DESC, jnl_no DESC ');
//     $signin->execute();
//     $rows = $signin->fetchAll();
// }
if (isset($user)){
    define("FLD_1","title");
    define("FLD_2","content");
    define("FLD_3","solution");
    define("FLD_4","remarks");

    $qry=$_REQUEST['selectcont'];
    // $qry=filter_input(INPUT_POST ,"selectcont");
    $q_str=htmlspecialchars($qry);
    $q_str1=htmlspecialchars(mb_convert_kana(trim($qry),'rn'));//全角英数文字→半角変換
    // $match1 ='<span style="color : blue; background-color:lightskyblue;">'.$q_str1.'</span>';
 
    //SQL select_str	
	if ($q_str1 === ""){
        $sel = "SELECT  * FROM j_dailyreport WHERE regist_user =". $id ." AND del_flg = 0 ORDER BY consulting_date DESC, consulting_timezone DESC, jnl_no DESC";      
    }else{
        $sel="SELECT * FROM j_dailyreport WHERE (";
        $sel=$sel.FLD_1." Like '%".$q_str1. "%' OR ".FLD_2." like '%".$q_str1."%' OR ".FLD_3." like '%".$q_str1."%' OR ".FLD_4." like '%".$q_str1."%')";
        $sel=$sel." AND regist_user =". $id ." AND del_flg = 0 ORDER BY consulting_date DESC, consulting_timezone DESC, jnl_no DESC";
    }
	
    // Show_data:
    try {  
        $username=(string)$user['name'];
        $stmt = $db->query($sel);
        $rows = $stmt->fetchAll();
        $stmt=null;
        //unset($db);//close Database
        } catch (PDOException $e) {
        die('Error: ' . $e->getMessage());
        }
    }
?>

<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="description" content="日報キーワード検索"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
<title>業務日報Web版</title>
</head>

<body>
  <header>
    <h4 class="font-weight-normal">■業務日報Web版</h4>  
    <div>  
        <p>ログインユーザー: <?php print(htmlspecialchars($user['name'],ENT_QUOTES)); ?> </p> 
        <p>Email: <?php print(htmlspecialchars($user['email'],ENT_QUOTES)); ?> </p>
        <br>
        <p>
            <?php print(htmlspecialchars($user['name'],ENT_QUOTES)); ?>さん、こんにちは。本日も無理なく続けていきましょう。
        </p>
    </div>
    <div style="text-align: center">
        <p>
            <a href="index.php" onclick="javascript:return confirm('ページリセットしますか？')">ページリセット&raquo;&emsp;</a>         
            <a href="signin.php" onclick="javascript:return confirm('ログアウトしますか？');">ログアウト&raquo;</a>
        </p>
    </div> 
    <hr size="20" noshade>
  </header>

  <main>
  <div class="content">
     <h4>■業務日報登録データ表示</h4>
     <form action="" method="post">
        <p>◆登録データ抽出（あいまい検索可）　
                <input type="text" name="selectcont"  size="30" value="<?php print($_REQUEST['selectcont']); ?>"/>
                <button type="submit" name="select">レコード抽出</button> 
        </p>
    </form>    
    <div class="table">
      <?php if (isset($rows)) {  ?>
    　<form action="" method="get">
      <table class='table'>
        <thead style="width: device-width;">
        <tr>
            <th style="font-weight: lighter;">NO</th>
            <th style="width: 120px; font-weight: lighter;">活動日</th>
            <th style="font-weight: lighter;">時間帯</th>
            <th style="font-weight: lighter;">タイトル</th>
            <th style="font-weight: lighter;">仕事内容</th>
            <th style="font-weight: lighter;" >対応内容</th>
            <th style="font-weight: lighter;">備考</th>
            <th style="font-weight: lighter;">データ更新日時</th>
            <th style="font-weight: lighter;">編集</th>
        </tr>                   
        </thead>
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
            <tbody>
            <?php if(isset($edt_jnlno) && $clm0 == $edt_jnlno): ?>
                <tr bgcolor="#cccccc">
            <?php else: ?>
                <tr>
            <?php endif; ?>
                <td><?php echo $clm0; ?></td>
                <td><?php echo $clm1; ?></td>
                <td><?php echo $clm2; ?></td>
                <td><?php echo $clm3; ?></td>     
                <td><?php echo nl2br($clm4); ?></td>
                <td><?php echo nl2br($clm5); ?></td> 
                <td><?php echo nl2br($clm6); ?></td>
                <td><?php echo nl2br($clm8); ?></td>
                <td>      
                    <a href="index.php?jnl_no=<?php print(htmlspecialchars($clm0));?>&selectcont=<?php print($_REQUEST['selectcont']);?>&edit=true" style="color: blue;">編集</a>  
                </td>
            </tr>
            </tbody>
        <?php } ?>
        </table>
        <?php } else { ?>
        <p>None</p> 
        <?php } ?>
      </table>
      </form>
     </div> <!-- table -->
     <hr size="20" noshade>
    </div>  <!-- content -->
    
    <div class="hidden_box">
    <label for="label1">業務日報入力フォームの表示切替え</label>
    <input type="checkbox" name="label_click" id="label1" <?php if(isset($edt_jnlno)){print('checked=checked');} ?>" />
    <div class="hidden_show">
    　<form action="" method="post">
        <?php if (isset($edt_jnlno)):?>
            <h4>■業務日報データ編集</h4>
            <p>
                <?php print(htmlspecialchars($user['name'],ENT_QUOTES)); ?>さん、選択内容の修正をおこなってからご登録ください。
            </p>
        <?php else: ?>
            <h4>■業務日報データ登録</h4>
            <p>
                <?php print(htmlspecialchars($user['name'],ENT_QUOTES)); ?>さん、本日の仕事内容をご登録ください。
            </p>
        <?php endif; ?>
  
      <dl>
        <?php if (isset($edt_jnlno)): ?>
         <dt>◆ジャーナルNO. <?php print($jnl_data['jnl_no']); $_POST['jnl_no']=$edt_jnlno;?></dt>
         <dd></dd>  
        <?php endif; ?>
        <br>
        <dt>◆対応日と時間帯</dt>
            <dd>
            <?php if (isset($edt_jnlno)): ?>
                <input type="date" name="consulting_date" value="<?php print($jnl_data['consulting_date']);?>"> 
                <input type="text" name="consulting_timezone" value="<?php print($jnl_data['consulting_timezone']);?>">
            <?php else: ?>
                <input type="date" name="consulting_date" value="<?php echo date('Y-m-d');?>"> 
                <input type="text" name="consulting_timezone" value="<?php echo $dte->format('H:00').'-'.$dte1h->format('H:00');?>">
            <?php endif; ?>
            </dd>
        <dt>◆タイトル（128文字迄）</dt>
            <dd>
                <?php if (isset($edt_jnlno)): ?>
                    <textarea name="title" cols="50" rows="2"><?php print($jnl_data['title']);?></textarea>   
                <?php else: ?>             
                    <textarea name="title" cols="50" rows="2"></textarea>
                <?php endif; ?>        
            </dd>
        <dt>◆仕事内容（1024文字迄）</dt>
            <dd>
                <?php if (isset($edt_jnlno)): ?>
                    <textarea name="content" cols="50" rows="5"><?php print($jnl_data['content']);?></textarea>   
                <?php else: ?>             
                    <textarea name="content" cols="50" rows="5"></textarea>
                <?php endif; ?>
            </dd>
        <dt>◆対応内容（1024文字迄）</dt>
            <dd>
                <?php if (isset($edt_jnlno)): ?> 
                    <textarea name="solution" cols="50" rows="4"><?php print($jnl_data['solution']);?></textarea>   
                <?php else: ?>             
                    <textarea name="solution" cols="50" rows="4"></textarea>
                <?php endif; ?>
            </dd>
        <dt>◆備考（256文字迄）</dt>
            <dd>
                <?php if (isset($edt_jnlno)): ?> 
                    <textarea name="remarks" cols="50" rows="2"><?php print($jnl_data['remarks']);?></textarea>     
                <?php else: ?>             
                    <textarea name="remarks" cols="50" rows="2"></textarea>
                <?php endif; ?>
            </dd>
        <br>
        <dt></dt>
            <dd> 
            <?php if (isset($edt_jnlno)): ?>      
                <input type="checkbox" name="del_flg" value=<?php if(isset($edt_jnlno)){print("true");}?> id="del_flg" />＊登録内容を削除する場合はチェックしてください。
            <?php endif; ?>
            </dd>
        <dt></dt>
            <dd>      
                <input type="hidden" name="jnl_no" value="<?php if(isset($edt_jnlno)){print($edt_jnlno);}?>" />
            </dd>    
        <div class="submit">
          <dt></dt>
            <dd> 
                <?php 
                  if (isset($edt_jnlno)):
                    $btn_name=array("update","更新登録する");    
                  else:
                    $btn_name=array("regist","新規登録する"); 
                  endif;
                ?>
                <button type="submit" name="<?php print($btn_name[0]);?>" onclick="javascript:return confirm('入力した項目を登録しますか？');"><?php print($btn_name[1]); ?></button>            
                <button name="clear" onclick="javascript:return confirm('入力した項目をクリアしますか？')">入力クリア</button>
            </dd>
        </div>
        </dl>
      </form>
　    <div class="bottom" style="text-align: center">
      <p>
        <a href="#">▲ ページトップへ</a>
      </p>
　    </div>
    </div> <!-- hidden_show -->
   </div> <!-- hidden_box -->
   <hr size="20" noshade> 
  </main>
  <footer>
    <p style="text-align: center"> &copy 2020 NANZIYO</p>
  </footer> 
</body> 
</html>