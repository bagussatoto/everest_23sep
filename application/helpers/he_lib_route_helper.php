<?php
function kirim_tele($mesage, $chat_id = "", $tele_token = "", $schedule_kirim = ""){
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlRoute");
    $i = new MdlRoute();
    // region token

    $arrToken = array(
        "mgk" => "885127931:AAH-dZeKGrCsoyKdtIfnON1JZq9TYtn73hI",
//        "mgk" => "885127931:AAH-dZeKGrCsoyKdtIfnON1JZq9TYtn73hI",
        "malioboro" => "735936770:AAH4xdu9gPvgXRJjuStOCpkdZxLlqOhPOOQ",
        "teknis" => "2006804072:AAF1qUtWoF88THjnMdDkXmPAhY0XnRYaGPs", //ok -1001457771609
        "demo" => "451277674:AAGp-rjOZJeHIj-5fpNm1kqP0XWcqF8tkHA", //ok // -1001376284892
        "ilm" => "485246660:AAHqy7cZlz9xf_R65rG_tHSDVDdjr1FTiJw",
    );

    $arrChatid = array(
        "thomas" => "315063433",
       "demo" => "-1001376284892",
        "mgk" => "-334575460",
        "teknis" => "-334575460",
//        "teknis" => "-1001322641337",
        "malioboro" => "-320538164",
//        "malioboro" => "-1001075040069",
        "ilm" => "-1001281974546",
        "warnet" => "-1001075040069",
    );

    $def_token = $arrToken['mgk'];
    $def_chatid = $arrChatid["teknis"];
//    echo "token $def_token /// chatid $def_chatid";
    // endregion token

    $chat_id_f = strlen($chat_id) == 0 ? $def_chatid : $chat_id;
    $tele_token_f = strlen($tele_token) == 0 ? $def_token : $tele_token;

    $data = array(
        "tele_chat_id"=>"$chat_id_f",
        "tele_token"=>"$tele_token_f",
        "tele_isi"=>"$mesage",
    );

//    matiHEre($mesage);
    //-------------
    if (strlen($schedule_kirim) > 5) {
//            $insert = $ci->addData($data);
//        $q = "insert into $tbl (schedule,tele_chat_id, tele_token, tele_isi)";
//        $q .= "values('$schedule_kirim','$chat_id_f' , '$tele_token_f', '$mesage')";
    }
    else {
        $insert = $i->addData($data);
//        $q = "insert into $tbl (tele_chat_id, tele_token, tele_isi)";
//        $q .= "values('$chat_id_f' , '$tele_token_f', '$mesage')";
    }

    return true;

}