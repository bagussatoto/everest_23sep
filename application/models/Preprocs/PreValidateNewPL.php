<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class PreValidateNewPL extends MdlMother
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $outFields = array( // dari tabel rek_cache
        "nomer",
        "step_number",
        "step_code",
        "step_name",
        "group_code",
        "oleh_id",
        "oleh_nama",
        "keterangan",
        "transaksi_id",
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function pair($masterID="",$inParams)
    {
        $cCode = "_TR_".$inParams['static']['jenis'];
        unset($_SESSION[$cCode]['main']['valid_ppn']);
        $_SESSION[$cCode]['main']['valid_ppn']=$inParams['static']['nilai'];
        // $valid
        // arrPrint($inParams);
        // matiHere($cCode);
        //
        return true;
    }

    public function exec()
    {
        return true;
    }




}