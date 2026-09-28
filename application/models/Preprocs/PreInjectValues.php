<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class PreInjectValues extends MdlMother
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $result;
    private $outFields = array( // dari tabel rek_cache
        "tagihan",
        "terbayar",
        "returned",
        "sisa",
        "cabang_id",
        "cabang_nama",
        "extern_id",
        "extern_nama",
        "transaksi_id",
        "jenis",
        "label",
    );
    private $resultParams = array();

    //<editor-fold desc="getter and setter">
    public function getInParams()
    {
        return $this->inParams;
    }

    public function setInParams($inParams)
    {
        $this->inParams = $inParams;
    }

    public function getOutParams()
    {
        return $this->outParams;
    }

    public function setOutParams($outParams)
    {
        $this->outParams = $outParams;
    }

    public function getWriteMode()
    {
        return $this->writeMode;
    }

    public function setWriteMode($writeMode)
    {
        $this->writeMode = $writeMode;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function setResult($result)
    {
        $this->result = $result;
    }

    public function getOutFields()
    {
        return $this->outFields;
    }

    public function setOutFields($outFields)
    {
        $this->outFields = $outFields;
    }
    //</editor-fold>
    public function setResultParams($resultParams)
    {
        $this->resultParams = $resultParams;
    }



    public function __construct($resultParams = array())
    {
        parent::__construct();
        $this->resultParams = $resultParams;
    }

    public function pair($master_id, $sentParams)
    {
        if (!is_array($sentParams)) {
            die("params required!");
        }
        $source = $sentParams['static'];
         $nilai_dipakai= isset($sentParams['static']['nilai']) ? $sentParams['static']['nilai'] :0;
        $total_nilai = isset($sentParams['static']['hpp_nppn']) ? $sentParams['static']['hpp_nppn'] :0;
//        arrPrint($sentParams);

        $patchersKey = str_replace(" ", "_", $sentParams['static']['jenis']);
//        matiHEre($patchersKey);
//        arrPrint($this->resultParams);
        $ppnFactor = $sentParams["static"]["ppnFactor"];
        // $ppn = $nilai_dipakai *($ppnFactor/100);
        $srcNilai = array(
            "hpp" =>$nilai_dipakai,
            "harga" =>$nilai_dipakai,
            // "ppn" =>$ppn,
            "nilai_dipakai" =>$total_nilai,
            "nett2" =>$nilai_dipakai,
            "nett" =>$nilai_dipakai,
            "sub_harga" =>$nilai_dipakai,
            "subtotal" =>$nilai_dipakai,

        );
//        arrPrint($this->resultParams);
//        matiHEre($ppn);
        $patchers = array();
        foreach ($this->resultParams as $gateName => $paramSpec) {
//            arrPrint($paramSpec);

            foreach ($paramSpec as $key => $val) {
//                cekLime($val);
                $patchers[$gateName][$val] = $srcNilai[$val];
            }
        }
//arrPrint($patchers);
//        matiHEre("pree");
        $this->result = $patchers;
        if (sizeof($this->result) > 0) {
            return true;
        }
        else {
            return false;
        }

    }


    public function exec()
    {
        return $this->result;
    }
}