<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComFifoValasExtern extends CI_Model
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $outFields = array( // dari tabel rek_cache
        "produk_id",
        "produk_nama",
        "extern_id",
        "extern_nama",
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
            return true;
//        if (sizeof($this->outParams) > 0) {
//            return true;
//        }
//        else {
//            return false;
//        }

    }

    public function exec()
    {
//
        if (sizeof($this->outParams) > 0) {
            foreach ($this->outParams as $ctr => $params) {
                $this->load->model("Mdls/MdlFifoValasExtern");
                $l = new MdlFifoValasExtern();
                $insertIDs = array();
                switch ($this->writeMode) {
                    case "new":
                        $insertIDs[] = $l->addData($params);
                        break;
//                    case "update":
//                        $insertIDs[]=$l->updateData(array(
//                            "cabang_id"=>$params['cabang_id'],
//                            "produk_id"=>$params['produk_id'],
//                            "state"=>$params['state'],
//                        ),$params);
//                        break;
                    default:
                        die("unknown writemode!");
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
//            die("nothing to write down here");
//            return false;
            return true;
        }

    }
}