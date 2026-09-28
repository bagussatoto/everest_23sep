<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComFifoSuppliesProses extends CI_Model
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $outFields = array( // dari tabel rek_cache
        "produk_id",
        "produk_nama",
        "unit",
        "hpp",
        "jml_nilai",
        "cabang_id",
        "transaksi_id",
        "transaksi_jenis",
        "fulldate",
        "gudang_id",

        "jml_nilai_riil",
        "ppv_nilai_riil",
        "ppv_riil",
        "hpp_riil",

        "ppn_in",
        "ppn_in_nilai",
        "suppliers_id",
        "suppliers_nama",

        "hpp_nppv",
        "jml_nilai_nppv",
        "produk_jenis",
        "produk_jenis_id",
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function getOutFields()
    {
        return $this->outFields;
    }

    public function setOutFields($outFields)
    {
        $this->outFields = $outFields;
    }

    public function pair($inParams)
    {

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
            return true;
        }
        else {
            return false;
        }
    }

    public function exec()
    {
        if (sizeof($this->outParams) > 0) {
            foreach ($this->outParams as $ctr => $params) {
                $this->load->model("Mdls/MdlFifoSuppliesProses");
                $l = new MdlFifoSuppliesProses();
                $insertIDs = array();
                switch ($this->writeMode) {
                    case "new":
                        $insertIDs[] = $l->addData($params);
                        break;
                    default:
                        die(get_class($this) . "<br>unknown writemode!");
                        break;
                }
                cekBiru($this->db->last_query());

            }
            if (sizeof($insertIDs) > 0) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            die("nothing to write down here");
            return false;
        }
    }
}