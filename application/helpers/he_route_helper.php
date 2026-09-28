<?php
/**
 * Created by thomas Maya Graha Kencana.
 * Date: 6/9/13
 * Time: 3:44 PM
 */

function route_Host()
{
    return "192.168.11.100";
//    return "192.168.11.100";
//    return "mailermgk.com";
}

function route_DbUser()
{
    return "app_admin";
}

function route_DbPass()
{
    return "rapirapi619955";
}

function route_Db()
{
    return "route";
}

$pdo2 = New PDO("mysql:host=" . route_Host() . ";dbname=" . route_Db(), route_DbUser(), route_DbPass());
$pdo2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function deleteSentTeleMessage($param){
    $HOST = route_Host();
    $USERNAME = route_DbUser();
    $PWD = route_DbPass();
    $DB_1 = route_Db();
    $tbl = "terima";
    // Create connection
    $conn = mysql_connect($HOST, $USERNAME, $PWD, $DB_1);
    mysql_select_db($DB_1) or die(mysql_error() . " on " . __FUNCTION__ . " line " . __LINE__ . " file " . __FILE__ . "\n");
    $q = "DELETE from $tbl where id = '$param'";
    $x = mysql_query($q, $conn) or die(toAlert(mysql_error() . " on " . __FUNCTION__ . " line " . __LINE__ . " file " . __FILE__));
    if($x){
        return true;
    }else{
        return false;
    }
}

function route_terima_update_status($param){
    $HOST = route_Host();
    $USERNAME = route_DbUser();
    $PWD = route_DbPass();
    $DB_1 = route_Db();
    $tbl = "terima";
    // Create connection
    $conn = mysql_connect($HOST, $USERNAME, $PWD, $DB_1);
    mysql_select_db($DB_1) or die(mysql_error() . " on " . __FUNCTION__ . " line " . __LINE__ . " file " . __FILE__ . "\n");
    $q = "UPDATE $tbl set status = '0' where id = '$param'";
    $x = mysql_query($q, $conn) or die(toAlert(mysql_error() . " on " . __FUNCTION__ . " line " . __LINE__ . " file " . __FILE__));
    if($x){
        return true;
    }else{
        return false;
    }
}

function JsonToObject($json) {
    // decode terlebih dahulu data jsonnya menjadi bentuk array
    $array = json_decode($json);
    // memecah array ke dalam looping
    foreach ($array as $key=>$value) {
        // ga wajib...cuma buat nyamain datanya doang
        $key = strtolower(trim($key));
        // nah objectnya kita sisipin seperti ini
        // hampir mirip dengan menyisipkan sebuah array
        $object->$key = $value;
    }
    return $object;
}

function gagal_kirim_log($array,$json = ''){

    $route_id = $array['id'];
    $json1 = json_decode($json, TRUE);

    $chat_id = $json1['chat_id'];
    $hitung = route_sent_count($route_id, $chat_id);

    $pdo2 = New PDO("mysql:host=" . route_Host() . ";dbname=" . route_Db(), route_DbUser(), route_DbPass());
    $pdo2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if($hitung == 0){

        $tbl = "terima";

        $respon_decs = $json1['respon']['description'];
        $respon_err = $json1['respon']['error_code'];
        $code = $json1['code'];
        $status = $json1['status'];

        if($code==400 && $respon_err==400 && $respon_decs=='Bad Request: chat not found'){
            $q = "UPDATE $tbl set status='2', status_text='$respon_decs' where id='$route_id'";
            $x = $pdo2->prepare($q);
            if( $x->execute() ){
                cekhere('set status jadi 00-code:'.$code.'-res_err:'.$respon_err.'-stat_text:'.$respon_decs.'-route:'.$route_id);
            }
        }
        elseif($code==0 && $status=='Failed to connect to api.telegram.org port 443: Network is unreachable'){
            $q1 = "select resend from $tbl where id = '$route_id'";
            $x1 = $pdo2->prepare($q1);
            $x1->execute();
            $l1 = $x1->fetchObject();
            if($l1->resend > 5){
                $q = "UPDATE $tbl set status = '99', status_text='$status' where id = '$route_id'";
                $x = $pdo2->prepare($q);
                if( $x->execute() ){
                    cekhere('set status jadi 55 -'.$code.'-'.$status);
                }
            }
            else{
                $q = "UPDATE $tbl set status = '55', resend= resend+1, status_text='$status' where id = '$route_id'";
                $x = $pdo2->prepare($q);
                if( $x->execute() ){
                    cekhere('set status jadi 55 -'.$code.'-'.$status);
                }
            }
        }
        elseif($code==403 && $respon_err==403 &&  $respon_decs=='Forbidden: bot was blocked by the user'){
            $q = "UPDATE $tbl set status = '99', status_text='$respon_decs' where id = '$route_id'";
            $x = $pdo2->prepare($q);
            if( $x->execute() ){
                cekhere('set status jadi 99 -'.$code.'-'.$status);
            }
        }
        elseif($code==400 && $respon_err==400 && $respon_decs=='Bad Request: wrong file identifier/HTTP URL specified'){
            $q = "UPDATE $tbl set status = '99', status_text='$respon_decs (gambar sudah pernah diupload sebelumnya)' where id = '$route_id'";
            $x = $pdo2->prepare($q);
            if( $x->execute() ){
                cekhere('set status jadi 99-code:'.$code.'-res_err:'.$respon_err.'-stat_text:'.$respon_decs.'-route:'.$route_id);
            }
        }
        elseif($code==401 && $respon_err==401 && $respon_decs=='Unauthorized'){
            $q = "UPDATE $tbl set status = '99', status_text='$respon_decs Unauthorized' where id = '$route_id'";
            $x = $pdo2->prepare($q);
            if( $x->execute() ){
                cekhere('set status jadi 99-code:'.$code.'-res_err:'.$respon_err.'-stat_text:'.$respon_decs.'-route:'.$route_id);
            }
        }
//        elseif($code==413 && $respon_err==413 && $respon_decs=='Bad Request: wrong file identifier/HTTP URL specified'){
//            $q = "UPDATE $tbl set status = '99', status_text='$respon_decs (gambar sudah pernah diupload sebelumnya)' where id = '$route_id'";
//            $x = $pdo2->prepare($q);
//            if( $x->execute() ){
//                cekhere('set status jadi 99-code:'.$code.'-res_err:'.$respon_err.'-stat_text:'.$respon_decs.'-route:'.$route_id);
//            }
//        }
        else{
            cekhere($code.'-'.$status);
            cekhere($code.'-'.$respon_err.'-'.$respon_decs);
            arrPrint($array);
            matihere('kode error '.$code.' belum terdefine');
        }

    }
    else{

        $tbl = "terima";

        $q = "UPDATE $tbl set status = '991', status_text='id:$route_id - File Ini Sudah pernah dikirim' where id = '$route_id'";
        $x = $pdo2->prepare($q);
        $x->execute();

        echo "<b>File Ini Sudah pernah dikirim</b><br>";

    }
}

function route_sent_log($array){
    $route_id = $array['route_id'];
    $hitung = route_sent_count($route_id, $array['chat_id']);

    $pdo2 = New PDO("mysql:host=" . route_Host() . ";dbname=" . route_Db(), route_DbUser(), route_DbPass());
    $pdo2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $arrTempCallBackResult = array(
        "id"                  => $array['id'],
        "route_id"            => $array['route_id'],
        "status"              => $array['status'],
        "message_id"          => $array['message_id'],
        "from_id"             => $array['from_id'],
        "from_is_bot"         => $array['from_is_bot'],
        "from_first_name"     => $array['from_first_name'],
        "from_username"       => $array['from_username'],
        "chat_id"             => $array['chat_id'],
        "chat_first_name"     => $array['chat_first_name'],
        "chat_last_name"      => $array['chat_last_name'],
        "chat_username"       => $array['chat_username'],
        "chat_type"           => $array['chat_type'],
        "time"                => $array['time'],
        "photo_file_id"       => $array['photo_file_id'],
        "photo_file_size"     => $array['photo_file_size'],
        "text"                => $array['text'],
        "entities"            => $array['entities'],
        "dtime_stamp"         => $array['dtime_stamp']
    );

    $id                  = $arrTempCallBackResult['id'];
    $route_id            = $arrTempCallBackResult['route_id'];
    $status              = $arrTempCallBackResult['status'];
    $message_id          = $arrTempCallBackResult['message_id'];
    $from_id             = $arrTempCallBackResult['from_id'];
    $from_is_bot         = $arrTempCallBackResult['from_is_bot'];
    $from_first_name     = $arrTempCallBackResult['from_first_name'];
    $from_username       = $arrTempCallBackResult['from_username'];
    $chat_id             = $arrTempCallBackResult['chat_id'];
    $chat_first_name     = $arrTempCallBackResult['chat_first_name'];
    $chat_last_name      = $arrTempCallBackResult['chat_last_name'];
    $chat_username       = $arrTempCallBackResult['chat_username'];
    $chat_type           = $arrTempCallBackResult['chat_type'];
    $time                = $arrTempCallBackResult['time'];
    $photo_file_id       = $arrTempCallBackResult['photo_file_id'];
    $photo_file_size     = $arrTempCallBackResult['photo_file_size'];
    $text                = $arrTempCallBackResult['text'];
    $entities            = $arrTempCallBackResult['entities'];
    $dtime_stamp         = $arrTempCallBackResult['dtime_stamp'];

    $tbl = "message_sent";
    $q  = "insert into $tbl (route_id,status,message_id,from_id,from_is_bot,from_first_name,from_username,chat_id,chat_first_name,chat_last_name,chat_username,chat_type,time,photo_file_id,photo_file_size,text,entities)";
    $q .= "values('$route_id','$status','$message_id','$from_id','$from_is_bot','$from_first_name','$from_username','$chat_id','$chat_first_name','$chat_last_name','$chat_username','$chat_type','$time','$photo_file_id','$photo_file_size','$text','$entities')";
    $x = $pdo2->prepare($q);

    if( $x->execute() ){
        $sent = route_sent_count($route_id, $chat_id);
        if($sent > 0){
            $updateStatus = route_terima_update_status($route_id);
            if($updateStatus == true){
                $delete = deleteSentTeleMessage($route_id);
                return true;
            }
            else{
                return false;
            }
        }
        else{
            echo "gagal menyimpan ke message_sent data tidak dihapus dari DB utama dengan id = $route_id";
        }
    }
    else{
        echo "gagal save :-( ";
    }
}

function route_sent_count($param, $chat_id){
    $HOST = route_Host();
    $USERNAME = route_DbUser();
    $PWD = route_DbPass();
    $DB_1 = route_Db();
    $pdo2 = New PDO("mysql:host=" . route_Host() . ";dbname=" . route_Db(), route_DbUser(), route_DbPass());
    $pdo2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $tbl = "message_sent";
    $q1 = "select COUNT(*) as count from $tbl where route_id='$param' and chat_id='$chat_id'";
    $x1 = $pdo2->prepare($q1);
    $x1->execute();
    $l1 = $x1->fetchObject();
    return $l1->count;
}

function kirim_sms_route($no_tlp, $mesage, $schedule_kirim = "")
{

}
function kirim_sms_route_($no_tlp, $mesage, $schedule_kirim = "")
{
    $HOST = route_Host();
    $USERNAME = route_DbUser();
    $PWD = route_DbPass();
    $DB_1 = route_Db();

    $tbl = "terima";

    // Create connection
    $conn = mysql_connect("$HOST", "$USERNAME", "$PWD", "$DB_1");
    mysql_select_db("$DB_1") or die(mysql_error() . " on " . __FUNCTION__ . " line " . __LINE__ . " file " . __FILE__ . "\n");

    //-------------
    if (strlen($schedule_kirim) > 5) {
        $q = "insert into $tbl (schedule,sms_tujuan,sms_isi)";
        $q .= "values('$schedule_kirim','$no_tlp','$mesage')";
    }
    else {
        $q = "insert into $tbl (sms_tujuan,sms_isi)";
        $q .= "values('$no_tlp','$mesage')";
    }

    $x = mysql_query($q, $conn) or die(toAlert(mysql_error() . " on " . __FUNCTION__ . " line " . __LINE__ . " file " . __FILE__));

    //-------------
    if (mysql_affected_rows($conn) > 0) {
        return 1;
    }
    else {
        die (toAlert("gagagl kirim sms"));
        return 0;
    }

    mysql_close($conn);
    $db_0 = New _00_DBclass();
    $db0->connect();

}

function kirim_tele_route__($mesage, $chat_id = "",$token_id="", $schedule_kirim = ""){

}

function kirim_tele_route_($mesage, $chat_id = "",$token_id="", $schedule_kirim = "")
{
    $HOST = route_Host();
    $USERNAME = route_DbUser();
    $PWD = route_DbPass();
    $DB_1 = route_Db();

    $tbl = "terima";
//    $token = "238288170:AAHvbOAKJ2LivGTI78qdL9wP9XEm5GKFIDw";
//    $token = "311442256:AAFXX5k7S-UGHSPqRJ-bDvcYB4YVh0ldiec";
    $token = "735936770:AAH4xdu9gPvgXRJjuStOCpkdZxLlqOhPOOQ";

    // Create connection
    $conn = mysql_connect("$HOST", "$USERNAME", "$PWD", "$DB_1");
    mysql_select_db("$DB_1") or die(mysql_error() . " on " . __FUNCTION__ . " line " . __LINE__ . " file " . __FILE__ . "\n");
    $chat_id_f = strlen($chat_id) == 0 ? "-320538164" : $chat_id;
    $token_id_f = strlen($token_id) == 0 ? $token : $token_id;
    //-------------
    if (strlen($schedule_kirim) > 5) {
        $q = "insert into $tbl (schedule,tele_chat_id,tele_isi,tele_token)";
        $q .= "values('$schedule_kirim','$chat_id_f','$mesage','$token_id_f')";
    }
    else {
        $q = "insert into $tbl (tele_chat_id,tele_isi,tele_token)";
        $q .= "values('$chat_id_f','$mesage','$token_id_f')";
    }
    $x = mysql_query($q, $conn) or die(toAlert(mysql_error() . " on " . __FUNCTION__ . " line " . __LINE__ . " file " . __FILE__));
    //-------------
    if (mysql_affected_rows($conn) > 0) {
        return 1;
    }
    else {
        die (toAlert("gagagl kirim tele"));
        return 0;
    }
    mysql_close($conn);
    $db_0 = New _00_DBclass();
    $db0->connect();

}

function apiRequestMedia($method, $data, $TOKEN, $chat_id, $DB_id='')
{

    $path_doc = isset($data['document']) ? $data['document'] : "";
    $arrRequest = array(
        "sendDocument" => "?chat_id=$chat_id&document=$path_doc"
    );

    if(in_array($method,array_flip($arrRequest))){
        $method .= $arrRequest[$method];
    }

    $url    = "https://api.telegram.org/bot" . $TOKEN . "/". $method;
//    $url    = "https://149.154.167.220/bot" . $TOKEN . "/". $method;

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_NOBODY, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.101 Safari/537.36");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_REFERER, "https://api.telegram.org/");
//    curl_setopt($ch, CURLOPT_REFERER, "https://149.154.167.220/");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $datas = curl_exec($ch);
    $error = curl_error($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    $debug['code'] = $status;
    $debug['status'] = $error;
    $debug['url'] = $url;
    $debug['data'] = $data;
    $debug['chat_id'] = $chat_id;
    $debug['respon'] = json_decode($datas, true);
//    $debug['respon1'] = json_decode($datas, true);
    return json_encode($debug);

}

function errLogTele($array,$json = ''){

    $route_id = $array['route_id'];
    $hitung = route_sent_count($route_id, $array['chat_id']);
    if($hitung == 0){
        $arrTempCallBackResult = array(
            "id"                  => $array['id'],
            "route_id"            => $array['route_id'],
            "status"              => $array['status'],
            "message_id"          => $array['message_id'],
            "from_id"             => $array['from_id'],
            "from_is_bot"         => $array['from_is_bot'],
            "from_first_name"     => $array['from_first_name'],
            "from_username"       => $array['from_username'],
            "chat_id"             => $array['chat_id'],
            "chat_first_name"     => $array['chat_first_name'],
            "chat_last_name"      => $array['chat_last_name'],
            "chat_username"       => $array['chat_username'],
            "chat_type"           => $array['chat_type'],
            "time"                => $array['time'],
            "photo_file_id"       => $array['photo_file_id'],
            "photo_file_size"     => $array['photo_file_size'],
            "text"                => $array['text'],
            "entities"            => $array['entities'],
            "dtime_stamp"         => $array['dtime_stamp']
        );
        $id                  = $arrTempCallBackResult['id'];
        $route_id            = $arrTempCallBackResult['route_id'];
        $status              = $arrTempCallBackResult['status'];
        $message_id          = $arrTempCallBackResult['message_id'];
        $from_id             = $arrTempCallBackResult['from_id'];
        $from_is_bot         = $arrTempCallBackResult['from_is_bot'];
        $from_first_name     = $arrTempCallBackResult['from_first_name'];
        $from_username       = $arrTempCallBackResult['from_username'];
        $chat_id             = $arrTempCallBackResult['chat_id'];
        $chat_first_name     = $arrTempCallBackResult['chat_first_name'];
        $chat_last_name      = $arrTempCallBackResult['chat_last_name'];
        $chat_username       = $arrTempCallBackResult['chat_username'];
        $chat_type           = $arrTempCallBackResult['chat_type'];
        $time                = $arrTempCallBackResult['time'];
        $photo_file_id       = $arrTempCallBackResult['photo_file_id'];
        $photo_file_size     = $arrTempCallBackResult['photo_file_size'];
        $text                = $arrTempCallBackResult['text'];
        $entities            = $arrTempCallBackResult['entities'];
        $dtime_stamp         = $arrTempCallBackResult['dtime_stamp'];
        $HOST = route_Host();
        $USERNAME = route_DbUser();
        $PWD = route_DbPass();
        $DB_1 = route_Db();
        $tbl = "terima";
        $filter = stripslashes(strip_tags(htmlspecialchars($json,ENT_QUOTES)));
        // Create connection
        $conn = mysql_connect($HOST, $USERNAME, $PWD, $DB_1);
        mysql_select_db($DB_1) or die(mysql_error() . " on " . __FUNCTION__ . " line " . __LINE__ . " file " . __FILE__ . "\n");
        $q = "UPDATE $tbl set status = '55', status_text='$filter' where id = '$route_id' AND status='1'";
        $x = mysql_query($q, $conn) or die(toAlert(mysql_error() . " on " . __FUNCTION__ . " line " . __LINE__ . " file " . __FILE__));
    }else{
        $HOST = route_Host();
        $USERNAME = route_DbUser();
        $PWD = route_DbPass();
        $DB_1 = route_Db();
        $tbl = "terima";
        // Create connection
        $conn = mysql_connect($HOST, $USERNAME, $PWD, $DB_1);
        mysql_select_db($DB_1) or die(mysql_error() . " on " . __FUNCTION__ . " line " . __LINE__ . " file " . __FILE__ . "\n");
        $q = "UPDATE $tbl set status = '99',status_text='File Ini Sudah pernah dikirim' where id = '$route_id' AND status='1'";
        $x = mysql_query($q, $conn) or die(toAlert(mysql_error() . " on " . __FUNCTION__ . " line " . __LINE__ . " file " . __FILE__));
        echo "File Ini Sudah pernah dikirim";
    }

}

function kirim_tele_route($mesage, $chat_id = "",$token_id = "", $schedule_kirim = "", $file_type = "text", $file_path = "")
{

    $arrToken = array(
        "mgk" => "885127931:AAH-dZeKGrCsoyKdtIfnON1JZq9TYtn73hI",
//        "mgk" => "885127931:AAH-dZeKGrCsoyKdtIfnON1JZq9TYtn73hI",
        "malioboro" => "735936770:AAH4xdu9gPvgXRJjuStOCpkdZxLlqOhPOOQ",
//        "malioboro" => "311442256:AAFXX5k7S-UGHSPqRJ-bDvcYB4YVh0ldiec",
        "demo" => "451277674:AAGp-rjOZJeHIj-5fpNm1kqP0XWcqF8tkHA",
        "ilm" => "485246660:AAHqy7cZlz9xf_R65rG_tHSDVDdjr1FTiJw",
        "mgks" => "2006804072:AAF1qUtWoF88THjnMdDkXmPAhY0XnRYaGPs",
    );

    $arrChatid = array(
        "thomas" => "315063433",
//        "demo" => "-1001376284892",
        "mgk" => "-1001376284892",
//        "teknis" => "-334575460", //old
        "teknis" => "-1001457771609",
//        "teknis" => "-1001322641337",
        "malioboro" => "-320538164",
//        "malioboro" => "-1001075040069",
        "ilm" => "-1001281974546",
        "warnet" => "-1001075040069",
    );

    $def_token = $arrToken['mgks'];
    $def_chatid = $arrChatid["teknis"];


    //chatid notif MCM -1001180394986
    $HOST = route_Host();
    $USERNAME = route_DbUser();
    $PWD = route_DbPass();
    $DB_1 = route_Db();
    $tbl = "terima";
//    $token = "238288170:AAHvbOAKJ2LivGTI78qdL9wP9XEm5GKFIDw";
//    $token = "311442256:AAFXX5k7S-UGHSPqRJ-bDvcYB4YVh0ldiec";
    $token = "735936770:AAH4xdu9gPvgXRJjuStOCpkdZxLlqOhPOOQ";
    $token = $def_token;
// Create connection
//    $conn = mysqli_connect($HOST, $USERNAME, $PWD, $DB_1);
    $connMysqli = new mysqli($HOST, $USERNAME, $PWD, $DB_1);

// Check connection
    if ($connMysqli->connect_error) {
        die("Connection failed: " . $connMysqli->connect_error);
    }

    $connMysqli->select_db($DB_1);

//    mysqli_select_db($DB_1) or die(mysqli_error() . " on " . __FUNCTION__ . " line " . __LINE__ . " file " . __FILE__ . "\n");

//    $chat_id_f = strlen($chat_id) == 0 ? "218544604" : $chat_id;
    $chat_id_f = strlen($chat_id) == 0 ? $def_chatid : $chat_id;
    //select default token jika token tidak ditentukan
    $token_id_f = strlen($token_id) == 0 ? $def_token : $token_id;
    //-------------
    if($file_type=='text'){
        if (strlen($schedule_kirim) > 5) {
            $q = "insert into $tbl (schedule,tele_chat_id,tele_isi,tele_token)";
            $q .= "values('$schedule_kirim','$chat_id_f','$mesage','$token_id_f')";
        }
        else {
            $q = "insert into $tbl (tele_chat_id,tele_isi,tele_token)";
            $q .= "values('$chat_id_f','$mesage','$token_id_f')";
        }
    }elseif($file_type=='image'){
        if (strlen($schedule_kirim) > 5) {
            $q = "insert into $tbl (schedule,tele_chat_id,tele_content_jenis,address_img,tele_isi,tele_token)";
            $q .= "values('$schedule_kirim','$chat_id_f','$file_type','$file_path','$mesage','$token_id_f')";
        }
        else {
            $q = "insert into $tbl (tele_chat_id,tele_content_jenis,address_img,tele_isi,tele_token)";
            $q .= "values('$chat_id_f','$file_type','$file_path','$mesage','$token_id_f')";
        }
    }else{
        if (strlen($schedule_kirim) > 5) {
            $q = "insert into $tbl (schedule,tele_chat_id,tele_content_jenis,address_doc,tele_isi,tele_token)";
            $q .= "values('$schedule_kirim','$chat_id_f','$file_type','$mesage','$token_id_f')";
        }
        else {
            $q = "insert into $tbl (tele_chat_id,tele_content_jenis,address_doc,tele_isi,tele_token)";
            $q .= "values('$chat_id_f','$file_type','$file_path','$mesage','$token_id_f')";
        }
    }

    $x = $connMysqli->query($q) or die(toAlert(mysqli_error() . " on " . __FUNCTION__ . " line " . __LINE__ . " file " . __FILE__));

    //-------------
    if ( $connMysqli->affected_rows > 0) {

        $result= array(
            "status" => 1,
            "query" => $q,
            "data" => $x,
        );
//        echo json_encode($result);

    }
    else {
        die (toAlert("gagagl kirim tele"));
        return 0;
    }

    $connMysqli->close();

//    $db_0 = New _00_DBclass();
//    $db0->connect();

}

// kirim email
function kirim_email_route($email_tujuan, $judul, $message, $nama_tujuan = "", $schedule_kirim = "",$pengirim=""){

}
function kirim_email_route_($email_tujuan, $judul, $message, $nama_tujuan = "", $schedule_kirim = "",$pengirim="")
{
    $HOST = route_Host();
    $USERNAME = route_DbUser();
    $PWD = route_DbPass();
    $DB_1 = route_Db();
    $tbl = "terima";
    // Create connection
    $conn = mysql_connect("$HOST", "$USERNAME", "$PWD", "$DB_1");
    mysql_select_db("$DB_1") or die(mysql_error() . " on " . __FUNCTION__ . " line " . __LINE__ . " file " . __FILE__ . "\n");
    //-------------
    $polaemail = "/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,3}$/i";
    if (!preg_match($polaemail, $email_tujuan)) {
        die(mati_disini("alamat email yg dituju harap ditulis secara lengakap f: " . __FUNCTION__));
    }
    $asal = "noreply";
    $nama_pengirim = strlen($pengirim) > 4 ? "$pengirim" : "$asal";
    $email_pengirim = "mgk.mailer.daemon@gmail.com";
    $nama_tujuan = strlen($nama_tujuan) == 0 ? "$email_tujuan" : "$nama_tujuan";
    if (strlen($schedule_kirim) > 5) {
        $q = "insert into $tbl (schedule,em_nama_pengirim, em_email_pengirim, em_nama_tujuan, em_email_tujuan, em_judul, em_isi_html)";
        $q .= "values('$schedule_kirim','$nama_pengirim','$email_pengirim','$nama_tujuan','$email_tujuan','$judul','$message')";
    }
    else {
        $q = "insert into $tbl (em_nama_pengirim,em_email_pengirim, em_nama_tujuan, em_email_tujuan, em_judul, em_isi_html)";
        $q .= "values('$nama_pengirim','$email_pengirim','$nama_tujuan','$email_tujuan','$judul','$message')";
    }
    $x = mysql_query($q, $conn) or die(toAlert(mysql_error() . " on " . __FUNCTION__ . " line " . __LINE__ . " file " . __FILE__));
    //-------------
    if (mysql_affected_rows($conn) > 0) {
        return 1;
    }
    else {
        return 0;
        die (toAlert("ada kesalahan ......... pingiriman gagal"));
    }
    mysql_close($conn);
    $db_0 = New _00_DBclass();
    $db0->connect();

}


?>