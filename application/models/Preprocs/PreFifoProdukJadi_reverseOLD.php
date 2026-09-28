<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreFifoProdukJadi_reverse extends CI_Model
{
    private $requiredParams = array(
        "id",
        "qty",
    );
    private $resultParams = array();
    private $inParams;
    private $outParams;
    private $result;
    private $outFields = array( // dari tabel rek_cache
        "produk_id",
        "produk_nama",
        "unit",
        "hpp",
        "jml_nilai",
        "cabang_id",
        "transaksi_id",
        "transaksi_jenis",
        "dtime",
        "fulldate",
        "gudang_id",
    );
    private $writeMode;

    public function __construct($resultParams = array())
    {
        parent::__construct();
        $this->resultParams = $resultParams;
    }

    //<editor-fold desc="getter-setter">

    public function getRequiredParams()
    {
        return $this->requiredParams;
    }

    public function setRequiredParams($requiredParams)
    {
        $this->requiredParams = $requiredParams;
    }

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

    public function getResultParams()
    {
        return $this->resultParams;
    }

    //</editor-fold>

    public function setResultParams($resultParams)
    {
        $this->resultParams = $resultParams;
    }

    public function pair($master_id, $inParams)
    {
        cekKuning("STARTING... ". get_class($this));

        if (!is_array($inParams)) {
            die("params required!");
        }

        $this->writeMode = "new";
        $this->inParams = $inParams;
        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            foreach ($this->inParams as $lCtr => $paramAsli) {
                $lCounter++;
                foreach ($paramAsli['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
                        $this->outParams[$lCounter][$key] = $value;
                    }
                }
                $this->writeMode = "new";
            }
        }
        if (sizeof($this->outParams) > 0) {
            foreach ($this->outParams as $ctr => $params) {
                $this->load->model("Mdls/MdlFifoProdukJadi");
                $l = new MdlFifoProdukJadi();
                $insertIDs = array();
                switch ($this->writeMode) {
                    case "new":
                        $insertIDs[] = $l->addData($params);
                        break;
                    default:
                        die("unknown writemode!");
                        break;
                }
                cekBiru($this->db->last_query());

            }
//            if (sizeof($insertIDs) > 0) {
//                return true;
//            }
//            else {
//                return false;
//            }
        }
        else {
            die("nothing to write down here");
            return false;
        }


//        mati_disini(get_class($this));

        if (sizeof($insertIDs) > 0) {
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