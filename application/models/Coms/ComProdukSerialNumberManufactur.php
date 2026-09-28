<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComProdukSerialNumberManufactur extends CI_Model
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $outFields = array( // dari tabel rek_cache
//        "jenis",
        "serial_number",
        "produk_id",
        "produk_nama",
        "cabang_id",
        "cabang_nama",
//        "nama",
//        "satuan",
//        "state",
//        "jumlah",
        "oleh_id",
        "oleh_nama",
//        "transaksi_id",
//        "nomer",
//        "gudang_id",
//
        "status",
        "trash",
        "dtime",
        "fulldate",
        "transaksi_id",
        "transaksi_no",
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function pair($inParams)
    {
        $this->inParams = $inParams;
        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            if ($this->inParams['static']['fase_id'] == 1) {

                foreach ($this->inParams['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
                        $this->outParams[$lCounter][$key] = $value;
                    }
                }

                //// locker membutuhkan cabangID dan gudangID, bila tidak ada kiriman gerbang nilai maka dihentikan saja.
//                $msg_1 = "Transaksi gagal disimpan karena id cabang login tidak terdaftar. silahkan relogin atau hubungi admin.";
////                $msg_2 = "Transaksi gagal disimpan karena id cabang login tidak terdaftar. silahkan relogin atau hubungi admin.";
////                $msg_3 = "Transaksi gagal disimpan karena id cabang login tidak terdaftar. silahkan relogin atau hubungi admin.";
//                $msg_4 = "Transaksi gagal disimpan karena id gudang login tidak terdaftar. silahkan relogin atau hubungi admin.";
//                $msg_5 = "Transaksi gagal disimpan karena id produk tidak terdaftar. silahkan relogin atau hubungi admin.";
//
//                $cabangID = isset($paramAsli['static']['cabang_id']) ? $paramAsli['static']['cabang_id'] : mati_disini($msg_1);
//                $defaultOlehID = isset($paramAsli['static']['oleh_id']) ? $paramAsli['static']['oleh_id'] : 0;
//                $defaultTransID = isset($paramAsli['static']['transaksi_id']) ? $paramAsli['static']['transaksi_id'] : 0;
//                $defaultGudangID = isset($paramAsli['static']['gudang_id']) ? $paramAsli['static']['gudang_id'] : mati_disini($msg_4);
//                $produkID = isset($paramAsli['static']['produk_id']) ? $paramAsli['static']['produk_id'] : mati_disini($msg_5);

                $_preValue = $this->cekPreValue(
                    $this->inParams['static']['serial_number'],
                    $this->inParams['static']['cabang_id'],
                    $this->inParams['static']['produk_id']
                );
                ceklime($this->db->last_query());

                if ($_preValue != null) {
                    $this->outParams[$lCounter]["mode"] = "skip";


                }
                else {
                    $this->outParams[$lCounter]["mode"] = "new";


                }

                $pakai_exec = 1;
                if ($pakai_exec == 1) {
                    if (sizeof($this->outParams) > 0) {
                        $insertIDs = array();
                        foreach ($this->outParams as $ctr => $params) {
                            $this->load->model("Mdls/MdlProdukPerSerialNumber");
                            $l = new MdlProdukPerSerialNumber();
                            $insertIDs = array();
                            $mode = $params['mode'];
                            unset($params['mode']);
                            switch ($mode) {
                                case "new":
                                    $insertIDs[] = $l->addData($params);
                                    break;
//                                case "update":
//                                    $insertIDs[] = $l->updateData(array(
//                                        "cabang_id" => $params['cabang_id'],
//                                        "gudang_id" => $params['gudang_id'],
//                                        "produk_id" => $params['produk_id'],
//                                        "state" => $params['state'],
//                                        "oleh_id" => $params['oleh_id'],
//                                        "transaksi_id" => $params['transaksi_id'],
//                                    ), $params);
//                                    break;
                                case "skip":

                                    break;
                                default:
                                    die("unknown writemode!");
                                    break;
                            }
                            showLast_query("kuning");
                        }
                        $this->outParams = array();

                        if (sizeof($insertIDs) == 0) {
                            cekMerah("::: PERIODE : $periode :::");
//                            return true;
                        }
                    }
                    else {
                        cekMerah("::: PERIODE : $periode :::");
//                        return true;
                    }
                }

            }

        }

//        mati_disini(__FUNCTION__ . " :::: " . __LINE__);
        return true;


//        if (sizeof($insertIDs) > 0) {
//            return true;
//        }
//        else {
//            return false;
//        }


    }

    private function cekPreValue($jenis, $cabang_id, $produk_id)
    {

        $this->load->model("Mdls/MdlProdukPerSerialNumber");
        $l = new MdlProdukPerSerialNumber();


        $l->addFilter("serial_number='$jenis'");
//        $l->addFilter("cabang_id='$cabang_id'");
//        $l->addFilter("gudang_id='$gudang_id'");
//        $l->addFilter("produk_id='$produk_id'");
//        $l->addFilter("state='$state'");
//        $l->addFilter("oleh_id='$olehID'");
//        $l->addFilter("transaksi_id='$transaksiID'");

//        $tmp = $l->lookupAll()->result();
//        cekMerah($this->db->last_query() . " # " . count($tmp));
//        arrPrint($l->getfilters());
//        matiHEre();
        $result = array();
        $localFilters = array();
        if (sizeof($l->getfilters()) > 0) {
            foreach ($l->getfilters() as $f) {
                $tmpArr = explode("=", $f);
                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");
            }
        }

        $query = $this->db->select()
            ->from($l->getTableName())
            ->where($localFilters)
            ->limit(1)
            ->get_compiled_select();
        $tmp = $this->db->query("{$query} FOR UPDATE")->row_array();
//matiHere("ini $state");
//        $nilai = $tmp['qty_debet'];
        if (sizeof($tmp) > 0) {

            $result = $tmp['id'];

        }
        else {
            $result = null;
        }
        //  endregion mengambil saldo dari rek_cache

        return $result;
    }

    public function exec()
    {
//        if (sizeof($this->outParams) > 0) {
//            foreach ($this->outParams as $ctr => $params) {
//                $this->load->model("Mdls/MdlLockerStock");
//                $l = new MdlLockerStock();
//                $insertIDs = array();
//                $mode = $params['mode'];
//                unset($params['mode']);
//                switch ($mode) {
//                    case "new":
//                        $insertIDs[] = $l->addData($params);
//                        break;
//                    case "update":
//                        $insertIDs[] = $l->updateData(array(
//                            "cabang_id" => $params['cabang_id'],
//                            "gudang_id" => $params['gudang_id'],
//                            "produk_id" => $params['produk_id'],
//                            "state" => $params['state'],
//                            "oleh_id" => $params['oleh_id'],
//                            "transaksi_id" => $params['transaksi_id'],
//                        ), $params);
//                        break;
//                    default:
//                        die("unknown writemode!");
//                        break;
//                }
//            }
//            if (sizeof($insertIDs) > 0) {
//                return true;
//            }
//            else {
//                return false;
//            }
//        }
//        else {
//            die("nothing to write down here");
//            return false;
//        }

        return true;
    }

}