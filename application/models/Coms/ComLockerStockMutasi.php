<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/3/2018
 * Time: 3:41 PM
 */
class ComLockerStockMutasi extends MdlMother
{
    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $outFields = array(
        // dari tabel rek_cache
        "jenis",
        "produk_id",
        "extern_id",
        "cabang_id",
        "nama",
        "satuan",
        "state",
        "qty_debet",
        "oleh_id",
        "oleh_nama",
        "transaksi_id",
        "transaksi_no",
        "keterangan",
        "nomer",
        "gudang_id",
        "status",
        "trash",
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Mdls/MdlLockerStockCache");
        $this->load->model("Mdls/MdlLockerStockMutasi");
        $this->load->model("Mdls/MdlLockerStock");
//        $this->tableName = "stock_locker_cache";
    }

    public function pair__($inParams)
    {
        $this->inParams = $inParams;

        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            $_preValue = 0;
            foreach ($this->inParams as $lCtr => $paramAsli) {
//                arrPrint($paramAsli);

                if (($paramAsli['static']['qty_debet']) > 0) {
                    $position_start = "qty_debet_awal";
                    $position = "qty_debet";
                    $position_last = "qty_debet_akhir";
                }
                else {
                    $position_start = "qty_debet_awal";
                    $position = "qty_kredit";
                    $position_last = "qty_debet_akhir";
                }

                $lCounter++;
                foreach ($paramAsli['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
                        $this->outParams[$lCounter][$key] = $value;
                    }
                }
                $defaultOlehID = isset($paramAsli['static']['oleh_id']) ? $paramAsli['static']['oleh_id'] : 0;
                $defaultTransID = isset($paramAsli['static']['transaksi_id']) ? $paramAsli['static']['transaksi_id'] : 0;
                $defaultGudangID = $paramAsli['static']['gudang_id'];
                $tmpCache = 0;
//                $_preValue = $this->cekPreValue($paramAsli['static']['cabang_id'], $paramAsli['static']['extern_id'], $defaultGudangID);
                $_preValue = $this->cekPreValue($paramAsli['static']['cabang_id'], $paramAsli['static']['extern_id'], $defaultGudangID, $paramAsli['static']['extern_jenis']);
                if ($_preValue != null) {
                    $pakaicache = 1;
                    if ($pakaicache == 1) {
                        $this->outParams[$lCounter]["cache"]["qty_debet"] = ($paramAsli['static']['qty_debet'] + $_preValue);
                        $this->outParams[$lCounter]["cache"]["mode"] = "update";
                        if ($this->outParams[$lCounter]["cache"]["qty_debet"] < 0) {
                            $msg = "Insufficient stock for " . $paramAsli['static']['nama'] . " with state: " . $paramAsli['static']['state'] . ", needed: " . $paramAsli['static']['qty_debet'] . ", avail: " . $_preValue;
                            mati_disini($msg);
                            die(lgShowAlert($msg));
                        }
                        $where = array(
                            "cabang_id" => $paramAsli['static']['cabang_id'],
                            "gudang_id" => $paramAsli['static']['gudang_id'],
                            "extern_id" => $paramAsli['static']['extern_id'],
                            "jenis" => "produk",
                        );
                        $tmpUpdateCache = array(
                            "qty_debet" => ($paramAsli['static']['qty_debet'] + $_preValue),
                        );


                        cekBiru($this->db->last_query());
//                        if($_preValue != $_preValue2){
//                            $tmpUpdateCache = array(
//                                "qty_debet" => ($paramAsli['static']['qty_debet'] + $_preValue2),
//                            );
//                            matiHere("pre $_preValue"." after update preval".$_preValue2);
//                        }
//                        else{
//                            cekHitam("yang ini harus lolos prevalu sebelum exec $_preValue"." akan update preval".$_preValue2);
//                        }
                        $newCache = $paramAsli['static']['qty_debet'] + $_preValue;

                        $c = new MdlLockerStockCache();
                        $insertIDs[] = $c->updateData($where, $tmpUpdateCache);
//                        $tmpCache = $c->lookUpStockSumActive($paramAsli['static']['cabang_id'], $paramAsli['static']['gudang_id'], $paramAsli['static']['extern_id']);

                    }

                    $pakaimutasi = 1;
                    if ($pakaimutasi == 1) {
                        $this->outParams[$lCounter]["mutasi"][$position_start] = $_preValue;
                        $this->outParams[$lCounter]["mutasi"][$position] = $paramAsli['static']['qty_debet'] > 0 ? $paramAsli['static']['qty_debet'] : $paramAsli['static']['qty_debet'] * -1;
                        $this->outParams[$lCounter]["mutasi"][$position_last] = ($paramAsli['static']['qty_debet'] + $_preValue);

                        $this->outParams[$lCounter]["mutasi"]["mode"] = "new";
                        if ($this->outParams[$lCounter]["mutasi"][$position_last] < 0) {
                            $msg = "Insufficient stock for " . $paramAsli['static']['nama'] . " with state: " . $paramAsli['static']['state'] . ", needed: " . $paramAsli['static']['qty_debet'] . ", avail: " . $_preValue;
                            mati_disini($msg);
                            die(lgShowAlert($msg));
                        }
                        $tmpUpdateMutasi = array(
                            "jenis" => "produk",
                            "cabang_id" => $paramAsli['static']['cabang_id'],
                            "gudang_id" => $paramAsli['static']['gudang_id'],
                            "extern_id" => $paramAsli['static']['extern_id'],
                            "extern_nama" => $paramAsli['static']['extern_nama'],
                            "transaksi_id" => $paramAsli['static']['transaksi_id'],
                            "transaksi_no" => $paramAsli['static']['transaksi_no'],
                            "$position_start" => $_preValue,
                            "$position" => $paramAsli['static']['qty_debet'] > 0 ? $paramAsli['static']['qty_debet'] : $paramAsli['static']['qty_debet'] * -1,
                            "$position_last" => ($paramAsli['static']['qty_debet'] + $_preValue),
                        );
                        cekHere("mutasi");

                        $m = new MdlLockerStockMutasi();
                        $insertIDs[] = $m->addData($tmpUpdateMutasi);
                        cekMerah($this->db->last_query());

//                        matiHEre();
                    }
                }
                else {
                    //region cache
                    $this->outParams[$lCounter]["cache"]["qty_debet"] = $paramAsli['static']['qty_debet'];
                    $this->outParams[$lCounter]["cache"]["mode"] = "new";
                    $tmpUpdateCache = array(
                        "cabang_id" => $paramAsli['static']['cabang_id'],
                        "gudang_id" => $paramAsli['static']['gudang_id'],
                        "extern_id" => $paramAsli['static']['extern_id'],
                        "extern_nama" => $paramAsli['static']['extern_nama'],
                        "qty_debet" => ($paramAsli['static']['qty_debet']),
                        "jenis" => "produk",
                    );
                    $c = new MdlLockerStockCache();
                    $insertIDs[] = $c->addData($tmpUpdateCache);
                    cekBiru($this->db->last_query());
                    //endregion
                    //region mutasi
                    $tmpUpdateMutasi = array(
                        "cabang_id" => $paramAsli['static']['cabang_id'],
                        "gudang_id" => $paramAsli['static']['gudang_id'],
                        "jenis" => "produk",
                        "extern_id" => $paramAsli['static']['extern_id'],
                        "extern_nama" => $paramAsli['static']['extern_nama'],
                        "transaksi_id" => $paramAsli['static']['transaksi_id'],
                        "transaksi_no" => $paramAsli['static']['transaksi_no'],
                        "$position_start" => 0,
                        "$position" => $paramAsli['static']['qty_debet'],
                        "$position_last" => $paramAsli['static']['qty_debet'],
                    );
                    $m = new MdlLockerStockMutasi();
                    $insertIDs[] = $m->addData($tmpUpdateMutasi);
//                    $tmpCache = $m->lookUpStockSumActive($paramAsli['static']['cabang_id'], $paramAsli['static']['gudang_id'], $paramAsli['static']['extern_id']);
                    cekMerah($this->db->last_query());
                    //endregion
                }
                //region cek locker vs cache locker
//                $ls = new MdlLockerStock();
//                $tmpLocker = $ls->lookUpStockSumActive($paramAsli['static']['cabang_id'], $paramAsli['static']['gudang_id'], $paramAsli['static']['extern_id']);
//                cekPink($this->db->last_query());
//matiHEre();

//                cekBiru($tmpLocker . " " . $tmpCache);
//                if ($tmpLocker == $tmpCache) {

//                } else {
//                    matiHere("unbalance locker $tmpLocker vs CACHE  $tmpCache");
//                    $selesai = false;
//                    $_preValue2 = $this->cekPreValue($paramAsli['static']['cabang_id'], $paramAsli['static']['extern_id'], $defaultGudangID);
//                    while ($selesai != true) {
//
//                        $where = array(
//                            "cabang_id" => $paramAsli['static']['cabang_id'],
//                            "gudang_id" => $paramAsli['static']['gudang_id'],
//                            "extern_id" => $paramAsli['static']['extern_id'],
//                        );
//                        $tmpUpdateCache2 = array(
//                            "qty_debet" => ($paramAsli['static']['qty_debet'] + $_preValue3),
//                        );
//                        $c = new MdlLockerStockCache();
//                        $insertIDs[] = $c->updateData($where, $tmpUpdateCache2);
//
//                        cekBiru($this->db->last_query());
//
//
//                        $_preValue3 = $this->cekPreValue($paramAsli['static']['cabang_id'], $paramAsli['static']['extern_id'], $defaultGudangID);
//                        if ($_preValue2 != $_preValue3) {
//                            cekPink("VALIDATE :::cache tidak sama ulangi loop: qty prevalue $_preValue" . " qty pre value $_preValue2 ");
//                            $selesai = false;
//                        } else {
//                            $pakaimutasi = 1;
//                            if ($pakaimutasi == 1) {
//                                $this->outParams[$lCounter]["mutasi"][$position_start] = $_preValue2;
//                                $this->outParams[$lCounter]["mutasi"][$position] = $paramAsli['static']['qty_debet'] > 0 ? $paramAsli['static']['qty_debet'] : $paramAsli['static']['qty_debet'] * -1;
//                                $this->outParams[$lCounter]["mutasi"][$position_last] = ($paramAsli['static']['qty_debet'] + $_preValue2);
//
//                                $this->outParams[$lCounter]["mutasi"]["mode"] = "new";
//                                if ($this->outParams[$lCounter]["mutasi"][$position_last] < 0) {
//                                    $msg = "Insufficient stock for " . $paramAsli['static']['nama'] . " with state: " . $paramAsli['static']['state'] . ", needed: " . $paramAsli['static']['qty_debet'] . ", avail: " . $_preValue3;
//                                    die(lgShowAlert($msg));
//                                }
//                                $tmpUpdateMutasi2 = array(
//                                    "cabang_id" => $paramAsli['static']['cabang_id'],
//                                    "gudang_id" => $paramAsli['static']['gudang_id'],
//                                    "extern_id" => $paramAsli['static']['extern_id'],
//                                    "extern_nama" => $paramAsli['static']['extern_nama'],
//                                    "transaksi_id" => $paramAsli['static']['transaksi_id'],
//                                    "transaksi_no" => $paramAsli['static']['transaksi_no'],
//                                    "$position_start" => $_preValue2,
//                                    "$position" => $paramAsli['static']['qty_debet'] > 0 ? $paramAsli['static']['qty_debet'] : $paramAsli['static']['qty_debet'] * -1,
//                                    "$position_last" => ($paramAsli['static']['qty_debet'] + $_preValue2),
//                                );
//                                cekHere("mutasi");
//
//                                $m = new MdlLockerStockMutasi();
//                                $insertIDs[] = $m->addData($tmpUpdateMutasi2);
//                                cekMerah($this->db->last_query());
//
//                            }
//                            $selesai = true;
//                        }
//                    }
//                }


            }

            if (sizeof($insertIDs) > 0) {
                return true;
            }
            else {
                return false;
            }

        }
    }

    private function cekPreValue($cabang_id, $produk_id, $gudang_id, $extern_jenis = null)
    {

        $this->load->model("Mdls/MdlLockerStockCache");
        $l = new MdlLockerStockCache();

        $this->addFilter("jenis='produk'");
        $this->addFilter("cabang_id='$cabang_id'");
        $this->addFilter("gudang_id='$gudang_id'");
        $this->addFilter("extern_id='$produk_id'");
        if ($extern_jenis != null) {
            $this->addFilter("jenis='$extern_jenis'");
        }

//        $tmp = $l->lookupAll()->result();
//        if (sizeof($tmp) > 0) {
//            foreach ($tmp as $row) {
//                $result = $row->qty_debet;
//            }
//        } else {
//            $result = null;
//        }

        $result = array();
        $localFilters = array();
        if (sizeof($this->filters) > 0) {
            foreach ($this->filters as $f) {
                $tmpArr = explode("=", $f);
                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");

            }
        }
//arrPrint($this->filters);
        $query = $this->db->select()
            ->from($l->getTableName())
            ->where($localFilters)
            ->limit(1)
            ->get_compiled_select();

        $tmp = $this->db->query("{$query} FOR UPDATE")->row_array();
        cekHitam($this->db->last_query());
        if (sizeof($tmp) > 0) {
            $nilai = $tmp['qty_debet'];
        }
        else {
            $nilai = null;
        }

//        matiHEre();
        return $nilai;
    }

    public function exec__()
    {

        return true;

    }

    //sementara bypas------------------------------
    public function pair($inParams)
    {

        return true;
    }

    public function exec()
    {

        return true;

    }
}