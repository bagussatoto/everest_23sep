<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class PreSuppliesToAset extends MdlMother
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
        $source = $sentParams['static']['cabang_id'];
        $target = $sentParams['static']['extern_id'];
        $nilai_dipakai = $source == $target ? 0 : $sentParams['static']['nilai'];
        $conMdl = $target == "-1" ? "MdlGudangDefault_center" : "MdlGudangDefault";

        $this->load->model("Mdls/" . $conMdl);
        $r = new $conMdl();
        $r->addFilter("cabang_id='$target'");
        $gudangData = $r->lookupAll()->result();
        $gudang = $gudangData[0]->id;
        $gudang_nama = $gudangData[0]->name;
//        arrPrint($gudangData);

        $patchersKey = str_replace(" ", "_", $sentParams['static']['jenis']);
//        matiHEre($patchersKey);
//        arrPrint($this->resultParams);

//        matiHEre($conMdl);
        $patchers = array();
        foreach ($this->resultParams as $gateName => $paramSpec) {
//            arrPrint($paramSpec);
            foreach ($paramSpec as $key => $val) {
//                cekLime($key);
                $patchers[$gateName][$key . "_" . $patchersKey] = $$val;
            }
        }
//arrPrint($patchers);
//        matiHEre();
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