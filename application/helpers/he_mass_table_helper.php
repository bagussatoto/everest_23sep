<?php

function tableExists($tblName)
{

    $ci =& get_instance();
    $ci->load->database();
    $result = $ci->db->query("SHOW TABLES LIKE '" . $tblName . "'")->result();

//    cekBiru($ci->db->last_query());
    if (sizeof($result)>0) {
        return 1;
    } else {
        return 0;
    }
}

function tableCopy($tblSource, $tblTarget)
{
    $ci =& get_instance();
    $ci->load->database();
    $q = "create table $tblTarget like $tblSource";
    $result = $ci->db->query($q);
    cekBiru($ci->db->last_query());
    if ($result) {
        return 1;
    } else {
        return 0;
    }
}

function tableForceCheck($tblTarget, $tblSource)
{
    if (!tableExists($tblTarget)) {
//        cekBiru("BELUM ADA TABLE-NYA");
        if (tableCopy($tblSource, $tblTarget)) {
//            cekBiru("BARU saja copy TABLE-NYA");
            return 1;
        } else {
//            cekBiru("tidak berhasil copy TABLE-NYA");
            return 0;
        }
    } else {
//        cekBiru("sudah ada TABLE-NYA");
        return 1;
    }
}

function heReturnTableName($arrPrefix, $arrRekening)
{
    $arrTablenames = array();
    
    $nameReplacers=array(
        "("=>"_",
        ")"=>"_",
    );
    
    if(sizeof($arrRekening)>0){
        if(sizeof($arrPrefix)){
            foreach ($arrPrefix as $key => $prefix){
                foreach ($arrRekening as $rekening){
                    $strRekName=$rekening;
                    foreach($nameReplacers as $src=>$target){
                        $strRekName=str_replace($src,$target,$strRekName);
                    }
                    $arrTablenames[$rekening][$key] = "_"."$prefix" . "__" . str_replace(" ", "_", $strRekName);
                }
            }
        }
    }

    if(sizeof($arrTablenames)>0){
        return $arrTablenames;
    }
    else{
        return null;
    }
}




?>
