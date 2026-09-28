<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComSignature extends MdlMother
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $outFields = array( // dari tabel rek_cache
                                //        "id",
                                //        "cabang_id",
                                //        "cabang_nama",
                                //        "extern_id",
                                //        "extern_nama",
                                //        "jenis",
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

    public function pair($inParams)
    {
        $this->writeMode = "insert";
        $this->inParams = $inParams;
        if (sizeof($this->inParams['loop']) > 0) {

            $lCounter = 0;
            foreach ($this->inParams['loop'] as $pkey => $arrValue) {
                arrprint($arrValue);
                if (isset($arrValue) && sizeof($arrValue) > 0 && $arrValue != 0) {
                    foreach ($arrValue as $value) {
                        $lCounter++;
                        cekBiru("$pkey mendapatkan nilai $value");
                        $this->outParams[$lCounter][$pkey] = $value;
                        if (isset($this->inParams['static']) && sizeof($this->inParams['static']) > 0) {
                            foreach ($this->inParams['static'] as $key_static => $value_static) {
                                if (in_array($key_static, $this->outFields)) {
                                    cekHijau("$key_static mendapatkan nilai $value_static");
                                    $this->outParams[$lCounter][$key_static] = $value_static;
                                }
                            }
                        }
                        cekBiru("$pkey mendapatkan lagi nilai $value");
                        $this->outParams[$lCounter][$pkey] = $value;
                    }
                }


            }
        }

//        if (sizeof($this->outParams) > 0) {
//            return true;
//        }
//        else {
//            return false;
//        }
        return true;
    }

    public function exec()
    {
        if (sizeof($this->outParams) > 0) {
            arrPrint($this->outParams);
            foreach ($this->outParams as $ctr => $params) {
                $this->load->model("MdlTransaksi");
                $l = new MdlTransaksi();
                $insertIDs = array();
                switch ($this->writeMode) {
                    case "insert":
                        $insertIDs[] = $l->writeSignature($params['transaksi_id'], $params);
                        break;
                    default:
                        die("unknown writemode!");
                        break;
                }
                cekBiru($this->db->last_query());
            }

            if (sizeof($insertIDs) > 0) {
                return true;
            } else {
                return false;
            }

        } else {
//            die("nothing to write down here");
//            return false;
            return true;
        }

    }

    private function cekPreValue($jenis, $cabang_id, $transaksiID = 0)
    {

        $this->load->model("MdlTransaksi");
        $l = new MdlTransaksi();


        $l->addFilter("id='$transaksiID'");
        $l->addFilter("jenis='$jenis'");
        $l->addFilter("cabang_id='$cabang_id'");

        $tmp = $l->lookupAll()->result();
        cekMerah($this->db->last_query() . " # " . count($tmp));

        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result = $row->id;
            }
        } else {
            $result = null;
        }
        //  endregion mengambil saldo dari rek_cache

        return $result;
    }
}