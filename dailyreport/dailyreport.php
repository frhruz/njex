<!-- //Dailyreport:業務日報Web版 -->
<!-- //(C)2020Apr,NANZIYO. -->
<?php

function get_session_limit(datetime $session_dte,string $str_expiration){
    $dte_lmt = $session_dte;
    return  date_add($dte_lmt,date_interval_create_from_date_string($str_expiration));
}

function get_userinfo(PDO $dbcon,string $userid){
    $signin = $dbcon->prepare('SELECT * FROM m_user WHERE id =:id');
    $signin->bindParam(':id',$userid,PDO::PARAM_INT);
    $signin->execute();
    $usr = $signin->fetch(); 
    return $usr;
}

function get_journal_data(PDO $dbcon,string $edit_journalno, string $userid){
    $data = $dbcon->prepare("SELECT * FROM j_dailyreport WHERE jnl_no ='{$edit_journalno}' AND regist_user ='{$userid}' AND del_flg=0");
    $data->execute();
    $jnl_dt = $data->fetch();  
    return $jnl_dt;
}

function exec_insert_db(PDO $dbcon,array $insert_post,datetime $insert_datetime,string $userid):void{         
    $insert_db ="INSERT INTO j_dailyreport ".
        "(customer_id, consulting_date, consulting_timezone,".
        " title, content, solution, remarks,".
        " regist_time, regist_user, update_time, update_user)".
        " VALUES (:customer_id, :consulting_date, :consulting_timezone,".
        " :title, :content, :solution, :remarks,".
        " :regist_time, :regist_user, :update_time, :update_user)";
    try {  
        $stmt =$dbcon->prepare($insert_db);
        $stmt->bindValue(':customer_id',NULL);
        $stmt->bindvalue(':consulting_date', date("Y-m-d",strtotime($insert_post['consulting_date'])));
        $stmt->bindParam(':consulting_timezone',$insert_post['consulting_timezone'],PDO::PARAM_STR);
        $stmt->bindParam(':title',$insert_post['title'],PDO::PARAM_STR);
        $stmt->bindParam(':content',$insert_post['content'],PDO::PARAM_STR);
        $stmt->bindParam(':solution',$insert_post['solution'],PDO::PARAM_STR);
        $stmt->bindParam(':remarks',$insert_post['remarks'],PDO::PARAM_STR);
        $stmt->bindvalue(':regist_time', $insert_datetime->format('Y-m-d H:i:s'));
        $stmt->bindvalue(':regist_user', $userid);
        $stmt->bindvalue(':update_time', $insert_datetime->format('Y-m-d H:i:s'));
        $stmt->bindvalue(':update_user', $userid);
        $stmt->execute();
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}

function exec_update_db(PDO $dbcon,array $update_post,string $edit_journalno,datetime $update_datetime,string $userid):void{           
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
    if(!empty($update_post['del_flg']) && $update_post['del_flg']== "true"){
        $update_db_del= ", del_flg = :del_flg";
    }
    try {  
        $update_db2= " WHERE jnl_no =". $edit_journalno;   
        $stmt =$dbcon->prepare($update_db1.$update_db_del.$update_db2);
        $stmt->bindvalue(':consulting_date', date("Y-m-d",strtotime($update_post['consulting_date'])));
        $stmt->bindParam(':consulting_timezone',$update_post['consulting_timezone'],PDO::PARAM_STR);
        $stmt->bindParam(':title',$update_post['title'],PDO::PARAM_STR);
        $stmt->bindParam(':content',$update_post['content'],PDO::PARAM_STR);
        $stmt->bindParam(':solution',$update_post['solution'],PDO::PARAM_STR);
        $stmt->bindParam(':remarks',$update_post['remarks'],PDO::PARAM_STR);
        $stmt->bindvalue(':update_time', $update_datetime->format('Y-m-d H:i:s'));
        $stmt->bindvalue(':update_user', $userid);
        if(!empty($update_post['del_flg']) && $update_post['del_flg'] == "true"){
            $stmt->bindvalue(':del_flg',1);
        } 
        $stmt->execute();
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}

function get_search_text(string $search_text){
    // $q_str=htmlspecialchars($search_text);
    $q_str=htmlspecialchars(mb_convert_kana(trim($search_text),'rn'));//全角英数文字→半角変換
    return $q_str;
}

function show_data(PDO $dbcon,string $str_qry,string $userid){
    define("FLD_1","title");
    define("FLD_2","content");
    define("FLD_3","solution");
    define("FLD_4","remarks");
    if ($str_qry === ""){
        $sel = "SELECT  * FROM j_dailyreport WHERE regist_user =". $userid ." AND del_flg = 0 ORDER BY consulting_date DESC, consulting_timezone DESC, jnl_no DESC";      
    }else{
        $sel="SELECT * FROM j_dailyreport WHERE (";
        $sel=$sel.FLD_1." Like '%".$str_qry. "%' OR ".FLD_2." like '%".$str_qry."%' OR ".FLD_3." like '%".$str_qry."%' OR ".FLD_4." like '%".$str_qry."%')";
        $sel=$sel." AND regist_user =". $userid ." AND del_flg = 0 ORDER BY consulting_date DESC, consulting_timezone DESC, jnl_no DESC";
    }	
    try {  
        $stmt = $dbcon->query($sel);
        $rows = $stmt->fetchAll();
        $stmt=null;
        return $rows;
        //unset($dbcon);//close Database
    } catch (PDOException $e) {
        die($e->getMessage());
        window.alert('Error:'.$e);
        return '';
    }
}
// --- Function EOL　---
?>
