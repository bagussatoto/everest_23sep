<?php


function paymentSource($jenisTr, $componentJurnal, $mainGate, $label, $valueSrc, $valueAdd = 0)
{
    cekungu(":: $label, $valueSrc ::");
    arrPrintPink($componentJurnal);
    //-------------------
    $valueRekeningJurnal = 0;
    if(sizeof($componentJurnal)>0){
        foreach($componentJurnal as $jSpec){
            if(isset($jSpec["loop"][$label])){
                cekBiru(":: $label :: " . $jSpec["loop"][$label]);
                $valueRekeningJurnal = $jSpec["loop"][$label];
                break;
            }
        }
    }
    //-------------------
    $valueExternSrc = isset($mainGate[$valueSrc]) ? $mainGate[$valueSrc] : 0;
    $valueRekeningJurnal_new = $valueRekeningJurnal + $valueAdd;

    cekMerah("HAHAHAHA, dari jurnal: $valueRekeningJurnal_new, dari main: $valueExternSrc");

    if($valueRekeningJurnal_new != $valueExternSrc){
        $msg = "Transaksi gagal disimpan karena kesalahan konfigurasi $label. Silahkan hubungi admin. Code " . __LINE__ . ", " . __FUNCTION__ . ", " . __FILE__;
//        mati_disini($msg);
    }


//    mati_disini("HAHAHAHA, $valueRekeningJurnal, $valueExternSrc");

}


?>