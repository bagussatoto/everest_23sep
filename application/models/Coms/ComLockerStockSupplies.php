<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComLockerStockSupplies extends CI_Model
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $outFields = array( // dari tabel rek_cache

        //        "jenis",
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
        "biaya_id",
        "oleh2_id",
        "oleh2_nama",
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
            $errorCounter = 0;
            foreach ($this->inParams as $lCtr => $paramAsli) {
                $lCounter++;
                foreach ($paramAsli['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
                        $this->outParams[$lCounter][$key] = $value;
                    }
                }
                $defaultOlehID = isset($paramAsli['static']['oleh_id']) ? $paramAsli['static']['oleh_id'] : 0;
                $defaultTransID = isset($paramAsli['static']['transaksi_id']) ? $paramAsli['static']['transaksi_id'] : 0;
                $defaultGudangID = $paramAsli['static']['gudang_id'];
                $defaultBiayaID = $paramAsli['static']['biaya_id'];

                $_preValue = $this->cekPreValue(
                    $paramAsli['static']['cabang_id'],
                    $paramAsli['static']['produk_id'],
                    $paramAsli['static']['state'],
                    $defaultOlehID,
                    $defaultTransID,
                    $defaultGudangID,
                    $defaultBiayaID
                );

//                cekMerah("_preValue= " . $_preValue);
//                cekMerah("paramAsli['static']['jumlah']= " . $paramAsli['static']['jumlah']);
//                arrPrint($paramAsli['static']);
//                $paramAsli['static']['jumlah'] = 1;
//                arrPrint( $paramAsli['static']['jumlah'] );
//                arrPrint( $_preValue );
//                arrPrint( $lCounter );
//                cekUngu("=================$lCounter==================");

                if ($_preValue != null) {
                    $this->outParams[$lCounter]["jumlah"] = ($paramAsli['static']['jumlah'] + $_preValue);
                    $this->outParams[$lCounter]["mode"] = "update";
                    if ($this->outParams[$lCounter]["jumlah"] < 0) {
                        $msg = "Insufficient stock for " . html_escape($paramAsli['static']['nama']) . " with state: " . $paramAsli['static']['state'] . ", needed: " . $paramAsli['static']['jumlah'] . ", avail: " . $_preValue;
//                        matiHere($msg);
                        die(lgShowAlert($msg));
                    }
                }
                else {
                    if ($this->outParams[$lCounter]["jumlah"] < 0) {
                        $msg = "transaksi gagal, karena " . html_escape($paramAsli['static']['nama']) . " state " . $paramAsli['static']['state'] . ", needed: " . $paramAsli['static']['jumlah'] . ", avail: 0";
                        die(lgShowAlert($msg));
                    }
                    $this->outParams[$lCounter]["mode"] = "new";
                }

                // cekMerah("LINE= " . __LINE__);

                $pakai_exec = 1;
                if ($pakai_exec == 1) {
                    if (sizeof($this->outParams) > 0) {
                        $insertIDs = array();
                        foreach ($this->outParams as $ctr => $params) {
                            $this->load->model("Mdls/MdlLockerStockSupplies");
                            $l = new MdlLockerStockSupplies();
                            $insertIDs = array();
                            $mode = $params['mode'];
                            unset($params['mode']);
                            switch ($mode) {
                                case "new":
                                    $insertIDs[] = $l->addData($params);
                                    break;
                                case "update":
                                    $insertIDs[] = $l->updateData(array(
                                        "cabang_id" => $params['cabang_id'],
                                        "gudang_id" => $params['gudang_id'],
                                        "biaya_id" => isset($params['biaya_id']) && $params['biaya_id'] != '' ? $params['biaya_id'] : null,
                                        "produk_id" => $params['produk_id'],
                                        "state" => $params['state'],
                                        "oleh_id" => $params['oleh_id'],
                                        "transaksi_id" => $params['transaksi_id'],
                                    ), $params);
                                    break;
                                default:
                                    die("unknown writemode!");
                                    break;
                            }
//                            arrPrint($params);
//                            showLast_query("kuning");
                        }
                        $this->outParams = array();
                        if (sizeof($insertIDs) == 0) {
//                            cekMerah("::: PERIODE : $periode :::");
                            return false;
                        }
                    }
                    else {
//                        cekMerah("nothing to write down here");
                        return false;
                    }
                }
            }
        }

        return true;

    }

    private function cekPreValue($cabang_id, $produk_id, $state = "active", $olehID = 0, $transaksiID = 0, $gudang_id, $biaya_id)
    {

        $this->load->model("Mdls/MdlLockerStockSupplies");
        $l = new MdlLockerStockSupplies();

        //        $l->addFilter("jenis='$jenis'");
        $l->addFilter("cabang_id='$cabang_id'");
        $l->addFilter("gudang_id='$gudang_id'");
        $l->addFilter("produk_id='$produk_id'");

        // penggunaan dari pindah gudang pusat tidak menggunakan biaya,
        // biaya id di gunakan jika mindah dari cabang projek ke gudang workorder
        if ($biaya_id != '') {
            $l->addFilter("biaya_id='$biaya_id'");
        }
        else {
            $l->addFilter("biaya_id is NULL");
        }

        $l->addFilter("state='$state'");
        $l->addFilter("oleh_id='$olehID'");
        $l->addFilter("transaksi_id='$transaksiID'");

        $tmp = $l->lookupAll()->result();

//        cekMerah("cekPreValue<br>" . $this->db->last_query() . " # " . count($tmp));
//        arrPrint($tmp);

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
    }
}