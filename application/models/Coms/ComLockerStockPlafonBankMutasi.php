<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/3/2018
 * Time: 3:41 PM
 */
class ComLockerStockPlafonBankMutasi extends MdlMother
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
        "debet",
        "kredit",
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
        $this->load->model("Mdls/MdlLockerPlafonHutangBankCache");
        $this->load->model("Mdls/MdlLockerPlafonHutangBankMutasi");
//        $this->load->model("Mdls/MdlLockerStock");
//        $this->tableName = "stock_locker_cache";
    }

    public function pair($inParams)
    {
        $this->inParams = $inParams;
//arrPrint($inParams);
        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            $_preValue = 0;
            foreach ($this->inParams as $lCtr => $paramAsli) {
//                arrPrint($paramAsli);

                if (($paramAsli['static']['debet']) > 0) {
                    $position_start = "debet_awal";
                    $position = "debet";
                    $position_last = "debet_akhir";
                }
                else {
                    $position_start = "debet_awal";
                    $position = "kredit";
                    $position_last = "debet_akhir";
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
                $_preValue = $this->cekPreValue($paramAsli['static']['cabang_id'], $paramAsli['static']['extern_id'], $defaultGudangID);
                if ($_preValue != null) {
                    $pakaicache = 1;
                    if ($pakaicache == 1) {
                        $this->outParams[$lCounter]["cache"]["debet"] = ($paramAsli['static']['debet'] + $_preValue);
                        $this->outParams[$lCounter]["cache"]["mode"] = "update";
                        if ($this->outParams[$lCounter]["cache"]["debet"] < 0) {
                            $msg = "Insufficient stock for " . $paramAsli['static']['nama'] . " with state: " . $paramAsli['static']['state'] . ", needed: " . $paramAsli['static']['qty_debet'] . ", avail: " . $_preValue;
//                            mati_disini($msg);
                            die(lgShowAlert($msg));
                        }
                        $where = array(
                            "cabang_id" => $paramAsli['static']['cabang_id'],
                            "gudang_id" => $paramAsli['static']['gudang_id'],
                            "extern_id" => $paramAsli['static']['extern_id'],
                        );
                        $tmpUpdateCache = array(
                            "debet" => ($paramAsli['static']['debet'] + $_preValue),
                        );
                        $c = new MdlLockerPlafonHutangBankCache();
                        $insertIDs[] = $c->updateData($where, $tmpUpdateCache);
                        cekBiru($this->db->last_query());
                    }

                    $this->outParams[$lCounter]["mutasi"][$position_start] = $_preValue;
                    $this->outParams[$lCounter]["mutasi"][$position] = $paramAsli['static']['debet'] > 0 ? $paramAsli['static']['debet'] : $paramAsli['static']['debet'] * -1;
                    $this->outParams[$lCounter]["mutasi"][$position_last] = ($paramAsli['static']['debet'] + $_preValue);

                    $this->outParams[$lCounter]["mutasi"]["mode"] = "new";
                    if ($this->outParams[$lCounter]["mutasi"][$position_last] < 0) {
                        $msg = "Insufficient stock for " . $paramAsli['static']['nama'] . " with state: " . $paramAsli['static']['state'] . ", needed: " . $paramAsli['static']['qty_debet'] . ", avail: " . $_preValue;
                        die(lgShowAlert($msg));
                    }
                    $tmpUpdateMutasi = array(
                        "jenis" => "plafon hutang bank",
                        "cabang_id" => $paramAsli['static']['cabang_id'],
                        "gudang_id" => $paramAsli['static']['gudang_id'],
                        "extern_id" => $paramAsli['static']['extern_id'],
                        "extern_nama" => $paramAsli['static']['extern_nama'],
                        "transaksi_id" => $paramAsli['static']['transaksi_id'],
                        "transaksi_no" => $paramAsli['static']['transaksi_no'],
                        "transaksi_jenis" => $paramAsli['static']['transksi_jenis'],
                        "$position_start" => $_preValue,
                        "$position" => $paramAsli['static']['debet'] > 0 ? $paramAsli['static']['debet'] : $paramAsli['static']['debet'] * -1,
                        "$position_last" => ($paramAsli['static']['debet'] + $_preValue),
                    );
                    cekHere("mutasi");
                    $m = new MdlLockerPlafonHutangBankMutasi();
                    $insertIDs[] = $m->addData($tmpUpdateMutasi);
                    cekMerah($this->db->last_query());

                }
                else {
                    //region cache
                    $this->outParams[$lCounter]["cache"]["debet"] = $paramAsli['static']['debet'];
                    $this->outParams[$lCounter]["cache"]["mode"] = "new";
                    $tmpUpdateCache = array(
                        "cabang_id" => $paramAsli['static']['cabang_id'],
                        "gudang_id" => $paramAsli['static']['gudang_id'],
                        "extern_id" => $paramAsli['static']['extern_id'],
                        "extern_nama" => $paramAsli['static']['extern_nama'],
                        "debet" => ($paramAsli['static']['debet']),
                        "jenis" => "plafon hutang bank",
                    );
                    $c = new MdlLockerPlafonHutangBankCache();
                    $insertIDs[] = $c->addData($tmpUpdateCache);
                    cekBiru($this->db->last_query() . " :: " . $this->db->affected_rows());

                    //endregion
                    //region mutasi
                    $tmpUpdateMutasi = array(
                        "cabang_id" => $paramAsli['static']['cabang_id'],
                        "gudang_id" => $paramAsli['static']['gudang_id'],
                        "jenis" => "plafon hutang bank",
                        "extern_id" => $paramAsli['static']['extern_id'],
                        "extern_nama" => $paramAsli['static']['extern_nama'],
                        "transaksi_id" => $paramAsli['static']['transaksi_id'],
                        "transaksi_no" => $paramAsli['static']['transaksi_no'],
                        "transaksi_jenis" => $paramAsli['static']['transksi_jenis'],
                        "$position_start" => 0,
                        "$position" => $paramAsli['static']['debet'],
                        "$position_last" => $paramAsli['static']['debet'],
                    );
                    $m = new MdlLockerPlafonHutangBankMutasi();
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

    private function cekPreValue($cabang_id, $produk_id, $gudang_id)
    {

        $this->load->model("Mdls/MdlLockerPlafonHutangBankCache");
        $l = new MdlLockerPlafonHutangBankCache();

        $this->addFilter("jenis='plafon hutang bank'");
        $this->addFilter("cabang_id='$cabang_id'");
        $this->addFilter("gudang_id='$gudang_id'");
        $this->addFilter("extern_id='$produk_id'");
        $this->addFilter("extern_id='$produk_id'");

//matiHEre();
//matiHEre();
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
            $nilai = $tmp['debet'];
        }
        else {
            $nilai = null;
        }

//        matiHEre($nilai);
        return $nilai;
    }

    public function exec()
    {

        return true;

    }


}