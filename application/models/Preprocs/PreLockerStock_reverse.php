<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class PreLockerStock_reverse extends CI_Model
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $outParamss = array( //===output ke tabel

    );
    private $writeMode;
    private $outFields = array( // dari tabel rek_cache

//        "jenis",
//        "extern_id",
        "produk_id",
        "cabang_id",
        "nama",
        "satuan",
        "state",
        "jumlah",
        "oleh_id",
        "oleh_nama",
        "transaksi_id",
        "nomer",
        "gudang_id",
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function pair($master_id, $inParams)
    {
        cekHitam("cetak inParams PreLockerStock_reverse:: ");
        arrPrint($inParams);
        $this->inParams = $inParams;
        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            foreach ($this->inParams as $lCtr => $paramAsli) {
                $lCounter++;
                foreach ($paramAsli['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
                        $this->outParams[$lCounter][$key] = $value;
                    }
                    $this->outParams[$lCounter]["produk_id"] = $paramAsli['static']['extern_id'];
                }
                $defaultOlehID = isset($paramAsli['static']['oleh_id']) ? $paramAsli['static']['oleh_id'] : 0;
                $defaultTransID = isset($paramAsli['static']['transaksi_id']) ? $paramAsli['static']['transaksi_id'] : 0;
                $defaultGudangID = $paramAsli['static']['gudang_id'];

//                $_preValue = $this->cekPreValue($paramAsli['static']['jenis'], $paramAsli['static']['cabang_id'], $paramAsli['static']['produk_id'], $paramAsli['static']['state'], $defaultOlehID, $defaultTransID, $defaultGudangID);
                $_preValue = $this->cekPreValue("produk", $paramAsli['static']['cabang_id'], $paramAsli['static']['extern_id'], "active", 0, 0, $defaultGudangID);
                if ($_preValue != null) {
                    $this->outParams[$lCounter]["jumlah"] = ($paramAsli['static']['produk_qty'] + $_preValue);
                    $this->outParams[$lCounter]["mode"] = "update";

                    if ($this->outParams[$lCounter]["jumlah"] < 0) {
                        $msg = "Insufficient stock for " . $paramAsli['static']['nama'] . " with state: " . "active" . ", needed: " . $paramAsli['static']['produk_qty'] . ", avail: " . $_preValue;
                        die(lgShowAlert($msg));
                    }
                }
                else {
                    $this->outParams[$lCounter]["mode"] = "new";
                }
            }
            if (sizeof($this->outParams) > 0) {
                foreach ($this->outParams as $ctr => $params) {
                    cekOrange("params active:: ");
                    arrPrint($params);

                    $this->load->model("Mdls/MdlLockerStock");
                    $l = new MdlLockerStock();
                    $insertIDs = array();
                    $mode = $params['mode'];
                    unset($params['mode']);
                    switch ($mode) {
                        case "new":
                            unset($params['transaksi_id']);
                            $insertIDs[] = $l->addData($params);
                            break;
                        case "update":
                            unset($params['transaksi_id']);
                            $insertIDs[] = $l->updateData(array(
                                "cabang_id" => $params['cabang_id'],
                                "gudang_id" => $params['gudang_id'],
                                "produk_id" => $params['produk_id'],
                                "state" => "active",
                                "oleh_id" => 0,
                                "transaksi_id" => 0,
                            ), $params);
                            break;
                        default:
                            die("unknown writemode!");
                            break;
                    }
                    cekorange("LOCKER " . $this->db->last_query());
                }
//                if (sizeof($insertIDs) > 0) {
//                    return true;
//                }
//                else {
//                    return false;
//                }
            }
            else {
                die("active nothing to write down here");
//            return false;
            }


            $lCounterr = 0;
            foreach ($this->inParams as $lCtr => $paramAsli) {
                $lCounterr++;
                foreach ($paramAsli['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
                        $this->outParamss[$lCounterr][$key] = $value;
                    }
                    $this->outParamss[$lCounter]["produk_id"] = $paramAsli['static']['extern_id'];
                }
                $defaultOlehID = isset($paramAsli['static']['oleh_id']) ? $paramAsli['static']['oleh_id'] : 0;
                $defaultTransID = isset($paramAsli['static']['transaksi_id']) ? $paramAsli['static']['transaksi_id'] : 0;
                $defaultGudangID = $paramAsli['static']['gudang_id'];

                $this->load->model("MdlTransaksi");
                $tr = new MdlTransaksi();
                $tr->setFilters(array());
                $tr->addFilter("id='$defaultTransID'");
                $rslt = $tr->lookupMainTransaksi()->result();
                $defaultTransID = $rslt[0]->id_master;

//                $_preValue = $this->cekPreValue($paramAsli['static']['jenis'], $paramAsli['static']['cabang_id'], $paramAsli['static']['produk_id'], $paramAsli['static']['state'], $defaultOlehID, $defaultTransID, $defaultGudangID);
                $_preValue = $this->cekPreValue("produk", $paramAsli['static']['cabang_id'], $paramAsli['static']['extern_id'], "hold", 0, $defaultTransID, $defaultGudangID);
                if ($_preValue != null) {
                    $this->outParamss[$lCounterr]["jumlah"] = ($_preValue - $paramAsli['static']['produk_qty']);
                    $this->outParamss[$lCounterr]["mode"] = "update";

                    if ($this->outParamss[$lCounterr]["jumlah"] < 0) {
                        $msg = "Insufficient stock for " . $paramAsli['static']['nama'] . " with state: " . "hold" . ", needed: " . $paramAsli['static']['produk_qty'] . ", avail: " . $_preValue;
//                        die(lgShowAlert($msg));
                    }
                }
                else {
                    $this->outParamss[$lCounterr]["mode"] = "new";
                }
            }
            if (sizeof($this->outParamss) > 0) {
                foreach ($this->outParamss as $ctr => $params) {
                    $this->load->model("Mdls/MdlLockerStock");
                    $l = new MdlLockerStock();
                    $insertIDs = array();
                    $mode = $params['mode'];
                    unset($params['mode']);
                    switch ($mode) {
                        case "new":
                            $params['transaksi_id'] = $defaultTransID;
                            $insertIDs[] = $l->addData($params);
                            break;
                        case "update":
                            $params['transaksi_id'] = $defaultTransID;
                            $insertIDs[] = $l->updateData(array(
                                "cabang_id" => $params['cabang_id'],
                                "gudang_id" => $params['gudang_id'],
                                "produk_id" => $params['produk_id'],
                                "state" => "hold",
                                "oleh_id" => 0,
                                "transaksi_id" => $defaultTransID,
                            ), $params);
                            break;
                        default:
                            die("unknown writemode!");
                            break;
                    }
                    cekorange("LOCKER " . $this->db->last_query());

                }
//                if (sizeof($insertIDs) > 0) {
//                    return true;
//                }
//                else {
//                    return false;
//                }
            }
            else {
                die("Hold nothing to write down here");
            }
        }


        if ((sizeof($this->outParams) > 0) && (sizeof($this->outParamss) > 0)) {
            return true;
        }
        else {
            return false;
        }


    }

    private function cekPreValue($jenis, $cabang_id, $produk_id, $state = "active", $olehID = 0, $transaksiID = 0, $gudang_id)
    {

        $this->load->model("Mdls/MdlLockerStock");
        $l = new MdlLockerStock();


        $l->addFilter("jenis='$jenis'");
        $l->addFilter("cabang_id='$cabang_id'");
        $l->addFilter("gudang_id='$gudang_id'");
        $l->addFilter("produk_id='$produk_id'");
        $l->addFilter("state='$state'");
        $l->addFilter("oleh_id='$olehID'");
        $l->addFilter("transaksi_id='$transaksiID'");

        $tmp = $l->lookupAll()->result();
//        cekMerah($this->db->last_query() . " # " . count($tmp));

        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result = $row->jumlah;
            }
        }
        else {
            $result = null;
        }
        //  endregion mengambil saldo dari rek_cache

        return $result;
    }

    public function exec()
    {
        return true;
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
////                cekBiru("LOCKER ". $this->db->last_query());
//
//            }
//            if (sizeof($insertIDs) > 0) {
//                return true;
//            }
//            else {
//                return false;
//            }
//
//        }
//        else {
//            die("nothing to write down here");
//            return false;
//        }

    }
}