<?php

/**
 * Created by JetBrains PhpStorm.
 * User: azes
 * Date: 5/9/12
 * Time: 11:56 AM
 * To change this template use File | Settings | File Templates.
 */


class LibCoa
{
    protected $toko_id;

    public function getTokoId()
    {
        return $this->toko_id;
    }

    public function setTokoId($toko_id)
    {
        $this->toko_id = $toko_id;
    }

    protected $mainObject;

    public function getMainObject()
    {
        return $this->mainObject;
    }

    public function setMainObject($mainObject)
    {
        $this->mainObject = $mainObject;
    }

    protected $externalId;

    public function getExternalId()
    {
        return $this->externalId;
    }

    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
    }

    protected $mainDatas;

    public function getMainDatas()
    {
        return $this->mainDatas;
    }

    public function setMainDatas($mainDatas)
    {
        $this->mainDatas = $mainDatas;
    }


    public function __construct()
    {
        // parent::__construct();
        $this->ci =& get_instance();

    }

    public function addDataCoa()
    {
        $mainObj = isset($this->mainObject) ? $this->mainObject : matiHere("mainObject harap di set ya");
        $mainInsertId = isset($this->externalId) ? $this->externalId : matiHere("externId tulong dong di set dulu");
        $srcData = isset($this->mainDatas) ? $this->mainDatas : matiHere("array mainDatas juga perlu diset lebih dolo");
        $data = is_object($srcData) ? (array)$srcData : $srcData;
        // $mainInsertId = $srcData->id;

        /* ---------------------------------------------------------------
         * tested pada auto COA yg pakai aaproval masuk di doApproveFrom
         * --------------------------------------------------------------*/
        // if (method_exists($mainObj, "getConnectingData")) {
        $nama = ucwords($data['nama']);
        $negara = isset($data['country']) ? $data['country'] : "";
        $extern_tipe = $negara == "ID" ? "lokal" : "non_lokal";
        $my_name = my_name();

        // cekBiru($negara . " $extern_tipe");
        $connectings = $mainObj->getConnectingData();
        foreach ($connectings as $model => $param_connecting) {
            $fields = isset($param_connecting['fields']) ? $param_connecting['fields'] : $param_connecting;
            $this->ci->load->model($param_connecting['path'] . "/$model");
            $connObj = new $model();
            // $strHead_code = isset($param_connecting['staticOptions'][$extern_tipe]) ? $param_connecting['staticOptions'][$extern_tipe] : matiHere("parameter");
            if (isset($param_connecting['staticOptions'])) {

                $strHead_code = is_array($param_connecting['staticOptions']) ? $param_connecting['staticOptions'][$extern_tipe] : $param_connecting['staticOptions'];
                // $strHead_code = is_array($param_connecting['staticOptions']) ? $param_connecting['staticOptions'] : $param_connecting['staticOptions'];
                // arrPrint($strHead_code);
                // matiHere();
            }
            else {
                mati_disini("static optionnya tolong dikasih");
            }
            $datas = array();

            // if (is_array($strHead_code)) {
            //     matiHere(__LINE__ . " " . __FILE__);
            // }
            // else {

                foreach ($fields as $field => $cfParams) {

                    if (isset($cfParams['var_main'])) {
                        $cNilai = $$cfParams['var_main'];
                    }
                    else {
                        $cNilai = $cfParams['str'];
                    }

                    $datas[$field] = $cNilai;
                }


                /* -------------------------------------------------
                 * menulis ke table connecting
                 * -------------------------------------------------*/
                $lastInset_code = $connObj->$param_connecting['fungsi']($strHead_code, $datas);
                showLast_query("merah");
                //                        mati_disini("hahaha -- $strHead_code -- ");

                /* -------------------------------------------------
                 * ngupdate ke data utama
                 * -------------------------------------------------*/
                if (isset($param_connecting['updateMain'])) {

                    foreach ($param_connecting['updateMain']['condites'] as $key => $condite) {
                        $mainCondites[$key] = $$condite;
                    }
                    foreach ($param_connecting['updateMain']['datas'] as $key => $val) {
                        $mainUpdate[$key] = $$val;
                    }

                    $mainObj->updateData($mainCondites, $mainUpdate);
                    showLast_query("orange");
                }
            // }

            // cekHitam($lastInset_code);
        }

        // arrPrint($connecting);
        // }
        // else{
        //     cekLime("belum ada methode getConnectingData");
        // }
        //    -------------------------------------------------------------
    }

}
