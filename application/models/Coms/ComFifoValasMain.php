<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComFifoValasMain extends CI_Model
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
        "transaksi_no",
        "dtime",
        "fulldate",
        "gudang_id",
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
        $this->inParams[0] = $inParams;
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

        return true;


    }

    public function exec()
    {

        if (sizeof($this->outParams) > 0) {
            foreach ($this->outParams as $ctr => $params) {
                $this->load->model("Mdls/MdlFifoValas");
                $l = new MdlFifoValas();
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
            return true;

        }
        else {
            return true;
        }

    }
}