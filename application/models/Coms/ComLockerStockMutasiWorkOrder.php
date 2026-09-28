<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/3/2018
 * Time: 3:41 PM
 */
class ComLockerStockMutasiWorkOrder extends MdlMother
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
        "src_key",
        "project_id",
        "work_order_id",
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Mdls/MdlLockerStockWorkOrderCache");
        $this->load->model("Mdls/MdlLockerStockWorkOderMutasi");
        $this->load->model("Mdls/MdlLockerStockWorkOrder");
//        $this->tableName = "stock_locker_cache";
    }

    public function pair($inParams)
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
                $_preValue = $this->cekPreValue($paramAsli['static']['cabang_id'], $paramAsli['static']['extern_id'], $defaultGudangID, $paramAsli['static']['project_id'],$paramAsli['static']['work_order_id'],"active");
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
//                        $where = array(
//                            "cabang_id" => $paramAsli['static']['cabang_id'],
//                            "gudang_id" => $paramAsli['static']['gudang_id'],
//                            "extern_id" => $paramAsli['static']['extern_id'],
//                            "jenis" => "produk",
//                        );
                        $where = array(
                            "src_key"=>$paramAsli['static']['cabang_id'].$defaultGudangID.$paramAsli['static']['project_id'].$paramAsli['static']['work_order_id'].$paramAsli['static']['extern_id']."active",
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

                        $c = new MdlLockerStockWorkOrderCache();
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

                        $m = new MdlLockerStockWorkOderMutasi();
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
                        "src_key"=> $paramAsli['static']['cabang_id'].$defaultGudangID.$paramAsli['static']['project_id'].$paramAsli['static']['work_order_id'].$paramAsli['static']['extern_id']."active",
                        "project_id"=>$paramAsli['static']['project_id'],
                        "work_order_id"=>$paramAsli['static']['work_order_id'],
                    );
                    $c = new MdlLockerStockWorkOrderCache();
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
                        "src_key"=> $paramAsli['static']['cabang_id'].$defaultGudangID.$paramAsli['static']['project_id'].$paramAsli['static']['work_order_id'].$paramAsli['static']['extern_id']."active",
                        "project_id"=>$paramAsli['static']['project_id'],
                        "work_order_id"=>$paramAsli['static']['work_order_id'],
                    );
                    $m = new MdlLockerStockWorkOderMutasi();
                    $insertIDs[] = $m->addData($tmpUpdateMutasi);
//                    $tmpCache = $m->lookUpStockSumActive($paramAsli['static']['cabang_id'], $paramAsli['static']['gudang_id'], $paramAsli['static']['extern_id']);
                    cekMerah($this->db->last_query());
                    //endregion
                }
            }

            if (sizeof($insertIDs) > 0) {
                return true;
            }
            else {
                return false;
            }

        }
    }
    private function cekPreValue($cabang_id, $produk_id, $gudang_id,$project_id,$workorder_id,$state)
    {

        $this->load->model("Mdls/MdlLockerStockWorkOrderCache");
        $l = new MdlLockerStockWorkOrderCache();
        $param_src = "$cabang_id"."$gudang_id"."$project_id"."$workorder_id"."$produk_id"."$state";
        $this->addFilter("src_key='$param_src'");
//        if ($extern_jenis != null) {
//            $this->addFilter("jenis='$extern_jenis'");
//        }

        $result = array();
        $localFilters = array();
        if (sizeof($this->filters) > 0) {
            foreach ($this->filters as $f) {
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
//        arrprint($tmp);
//        cekHitam($this->db->last_query());
        if (sizeof($tmp) > 0) {
            $nilai = $tmp['qty_debet'];
        }
        else {
            $nilai = null;
        }

//        matiHEre();
        return $nilai;
    }

//    //sementara bypas------------------------------
//    public function pair($inParams)
//    {
//        return true;
//    }

    public function exec()
    {
//matiHEre(__LINE__."|| ".__FILE__."||".__FUNCTION__);
        return true;

    }
}