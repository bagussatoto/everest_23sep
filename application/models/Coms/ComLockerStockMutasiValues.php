<?php

class ComLockerStockMutasiValues extends MdlMother
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
        "dtime",
        "tgl",
        "bln",
        "thn",
        "fulldate",
    );
    private $outFieldsMutasi = array(
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
        "fulldate",
        "dtime",
    );
    private $tableNames = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Mdls/MdlLockerStockCache");
        $this->load->model("Mdls/MdlLockerStockMutasi");
        $this->load->model("Mdls/MdlLockerStock");
        $this->tableNames = array(
            "cache" => "stock_locker_values_cache",
            "mutasi" => "stock_locker_values_mutasi",
        );
    }

    public function pair($inParams)
    {
        $this->inParams = $inParams;

        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            $_preValue = array();

            $paramAsli = $this->inParams;

            if (($paramAsli['static']['nilai']) > 0) {
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
            $rekening = $paramAsli['static']['rekening'];
            $value = $paramAsli['static']['nilai'];
            $periode = "forever";

            $tmpCache = 0;
            $_preValues = $this->cekPreValue($rekening, $paramAsli['static']['cabang_id'], $paramAsli['static']['extern_id'], $defaultGudangID);
//                if (sizeof($_preValue)>0) {
//                    $pakaicache = 1;
//                    if ($pakaicache == 1) {
//                        $this->outParams[$lCounter]["cache"]["qty_debet"] = ($paramAsli['static']['qty_debet'] + $_preValue);
//                        $this->outParams[$lCounter]["cache"]["mode"] = "update";
//                        if ($this->outParams[$lCounter]["cache"]["qty_debet"] < 0) {
//                            $msg = "Insufficient stock for " . $paramAsli['static']['nama'] . " with state: " . $paramAsli['static']['state'] . ", needed: " . $paramAsli['static']['qty_debet'] . ", avail: " . $_preValue;
////                            mati_disini($msg);
//                            die(lgShowAlert($msg));
//                        }
//                        $where = array(
//                            "cabang_id" => $paramAsli['static']['cabang_id'],
//                            "gudang_id" => $paramAsli['static']['gudang_id'],
//                            "extern_id" => $paramAsli['static']['extern_id'],
//                            "jenis" => "produk",
//                        );
//                        $tmpUpdateCache = array(
//                            "qty_debet" => ($paramAsli['static']['qty_debet'] + $_preValue),
//                        );
//
//
//                        cekBiru($this->db->last_query());
////                        if($_preValue != $_preValue2){
////                            $tmpUpdateCache = array(
////                                "qty_debet" => ($paramAsli['static']['qty_debet'] + $_preValue2),
////                            );
////                            matiHere("pre $_preValue"." after update preval".$_preValue2);
////                        }
////                        else{
////                            cekHitam("yang ini harus lolos prevalu sebelum exec $_preValue"." akan update preval".$_preValue2);
////                        }
//                        $newCache = $paramAsli['static']['qty_debet'] + $_preValue;
//
//                        $c = new MdlLockerStockCache();
//                        $insertIDs[] = $c->updateData($where, $tmpUpdateCache);
////                        $tmpCache = $c->lookUpStockSumActive($paramAsli['static']['cabang_id'], $paramAsli['static']['gudang_id'], $paramAsli['static']['extern_id']);
//
//                    }
//
//                    $pakaimutasi = 1;
//                    if ($pakaimutasi == 1) {
//                        $this->outParams[$lCounter]["mutasi"][$position_start] = $_preValue;
//                        $this->outParams[$lCounter]["mutasi"][$position] = $paramAsli['static']['qty_debet'] > 0 ? $paramAsli['static']['qty_debet'] : $paramAsli['static']['qty_debet'] * -1;
//                        $this->outParams[$lCounter]["mutasi"][$position_last] = ($paramAsli['static']['qty_debet'] + $_preValue);
//
//                        $this->outParams[$lCounter]["mutasi"]["mode"] = "new";
//                        if ($this->outParams[$lCounter]["mutasi"][$position_last] < 0) {
//                            $msg = "Insufficient stock for " . $paramAsli['static']['nama'] . " with state: " . $paramAsli['static']['state'] . ", needed: " . $paramAsli['static']['qty_debet'] . ", avail: " . $_preValue;
//                            die(lgShowAlert($msg));
//                        }
//                        $tmpUpdateMutasi = array(
//                            "jenis" => "produk",
//                            "cabang_id" => $paramAsli['static']['cabang_id'],
//                            "gudang_id" => $paramAsli['static']['gudang_id'],
//                            "extern_id" => $paramAsli['static']['extern_id'],
//                            "extern_nama" => $paramAsli['static']['extern_nama'],
//                            "transaksi_id" => $paramAsli['static']['transaksi_id'],
//                            "transaksi_no" => $paramAsli['static']['transaksi_no'],
//                            "$position_start" => $_preValue,
//                            "$position" => $paramAsli['static']['qty_debet'] > 0 ? $paramAsli['static']['qty_debet'] : $paramAsli['static']['qty_debet'] * -1,
//                            "$position_last" => ($paramAsli['static']['qty_debet'] + $_preValue),
//                        );
//                        cekHere("mutasi");
//
//                        $m = new MdlLockerStockMutasi();
//                        $insertIDs[] = $m->addData($tmpUpdateMutasi);
//                        cekMerah($this->db->last_query());
//
////                        matiHEre();
//                    }
//                }
//                else {
//                    //region cache
//                    $this->outParams[$lCounter]["cache"]["qty_debet"] = $paramAsli['static']['qty_debet'];
//                    $this->outParams[$lCounter]["cache"]["mode"] = "new";
//                    $tmpUpdateCache = array(
//                        "cabang_id" => $paramAsli['static']['cabang_id'],
//                        "gudang_id" => $paramAsli['static']['gudang_id'],
//                        "extern_id" => $paramAsli['static']['extern_id'],
//                        "extern_nama" => $paramAsli['static']['extern_nama'],
//                        "qty_debet" => ($paramAsli['static']['qty_debet']),
//                        "jenis" => "produk",
//                    );
//                    $c = new MdlLockerStockCache();
//                    $insertIDs[] = $c->addData($tmpUpdateCache);
//                    cekBiru($this->db->last_query());
//                    //endregion
//                    //region mutasi
//                    $tmpUpdateMutasi = array(
//                        "cabang_id" => $paramAsli['static']['cabang_id'],
//                        "gudang_id" => $paramAsli['static']['gudang_id'],
//                        "jenis" => "produk",
//                        "extern_id" => $paramAsli['static']['extern_id'],
//                        "extern_nama" => $paramAsli['static']['extern_nama'],
//                        "transaksi_id" => $paramAsli['static']['transaksi_id'],
//                        "transaksi_no" => $paramAsli['static']['transaksi_no'],
//                        "$position_start" => 0,
//                        "$position" => $paramAsli['static']['qty_debet'],
//                        "$position_last" => $paramAsli['static']['qty_debet'],
//                    );
//                    $m = new MdlLockerStockMutasi();
//                    $insertIDs[] = $m->addData($tmpUpdateMutasi);
////                    $tmpCache = $m->lookUpStockSumActive($paramAsli['static']['cabang_id'], $paramAsli['static']['gudang_id'], $paramAsli['static']['extern_id']);
//                    cekMerah($this->db->last_query());
//                    //endregion
//                }

            if (array_key_exists("id", $_preValues) && ($_preValues["id"] > 0)) {
                $mode = "update";
                $_preValues_id = $_preValues["id"];
            }
            else {
                $mode = "insert";
                $_preValues_id = 0;
                $this->outParams[$lCounter]["cache"][$mode]["tgl"] = date("d");
                $this->outParams[$lCounter]["cache"][$mode]["bln"] = date("m");
                $this->outParams[$lCounter]["cache"][$mode]["thn"] = date("Y");
            }

            if ((sizeof($_preValues)>0) && ($_preValues['debet'] > 0)) {
                $preNumber = detectRekByPosition($rekening, $_preValues['debet'], "debet");
            }
            else {
                $preNumber = detectRekByPosition($rekening, $_preValues['kredit'], "kredit");
            }
            $afterNumber = $preNumber + $value;
            $afterPosition = detectRekPosition($rekening, $afterNumber);
//cekHitam(":: $preNumber :: $afterPosition ::");
            //  region cache rekening pembantu
            $pakai_cache = 1;
            if ($pakai_cache == 1) {
                switch ($afterPosition) {
                    case "kredit":
                        //  region cache rekening umum
                        $this->outParams[$lCounter]["cache"][$mode]["kredit"] = abs($afterNumber);
                        $this->outParams[$lCounter]["cache"][$mode]["debet"] = 0;
                        //  endregion cache rekening umum
                        break;
                    case "debet":
                        //  region cache rekening umum
                        $this->outParams[$lCounter]["cache"][$mode]["debet"] = abs($afterNumber);
                        $this->outParams[$lCounter]["cache"][$mode]["kredit"] = 0;
                        //  endregion cache rekening umum
                        break;
                    default:
                        die(lgShowAlert(__LINE__ . " gagal menentukan posisi rekening DEBET / KREDIT " . __FUNCTION__ . " on file " . __FILE__));
                        break;
                }

//                $this->outParams[$lCounter]["cache"][$mode]["rek_id"] = createRekCode($key, $this->inParams['static']['extern_id']);
                $this->outParams[$lCounter]["cache"][$mode]["rekening"] = $rekening;
                $this->outParams[$lCounter]["cache"][$mode]["periode"] = $periode;
                $this->outParams[$lCounter]["cache"][$mode]["id"] = $_preValues_id;

                foreach ($this->inParams['static'] as $key_static => $value_static) {
                    if (in_array($key_static, $this->outFields)) {
                        $this->outParams[$lCounter]["cache"][$mode][$key_static] = $value_static;
                    }
                }
            }
            //  endregion cache rekening pembantu
//cekPink($this->outParams[$lCounter]);
            //  region mutasi rekening pembantu
            $pakai_mutasi = 1;
            if ($pakai_mutasi == 1) {
                switch ($periode) {
                    case "forever":
                        switch ($afterPosition) {
                            case "kredit":
                                //  region cache rekening umum
                                $this->outParams[$lCounter]["mutasi"]["kredit_awal"] = $_preValues["kredit"];
                                $this->outParams[$lCounter]["mutasi"]["kredit_akhir"] = abs($afterNumber);

                                $this->outParams[$lCounter]["mutasi"]["debet_awal"] = $_preValues["debet"];
                                $this->outParams[$lCounter]["mutasi"]["debet_akhir"] = 0;
                                //  endregion cache rekening umum
                                break;
                            case "debet":
                                //  region cache rekening umum
                                $this->outParams[$lCounter]["mutasi"]["debet_awal"] = $_preValues["debet"];
                                $this->outParams[$lCounter]["mutasi"]["debet_akhir"] = abs($afterNumber);

                                $this->outParams[$lCounter]["mutasi"]["kredit_awal"] = $_preValues["kredit"];
                                $this->outParams[$lCounter]["mutasi"]["kredit_akhir"] = 0;
                                //  endregion cache rekening umum
                                break;
                            default:
                                $this->outParams[$lCounter]["mutasi"]["debet_awal"] = $_preValues["debet"];
                                $this->outParams[$lCounter]["mutasi"]["debet_akhir"] = 0;

                                $this->outParams[$lCounter]["mutasi"]["kredit_awal"] = $_preValues["kredit"];
                                $this->outParams[$lCounter]["mutasi"]["kredit_akhir"] = 0;
                                break;
                        }
                        switch ($position) {
                            case "debet":
                                $this->outParams[$lCounter]["mutasi"]["debet"] = abs($value);
                                $this->outParams[$lCounter]["mutasi"]["kredit"] = 0;
                                break;
                            case "kredit":
                                $this->outParams[$lCounter]["mutasi"]["kredit"] = abs($value);
                                $this->outParams[$lCounter]["mutasi"]["debet"] = 0;
                                break;
                            default:
                                die(lgShowAlert("Transaksi gagal, karena rekening $key gagal menentukan posisi DEBET/KREDIT."));
                                break;
                        }

                        foreach ($this->inParams['static'] as $key_static_mutasi => $value_static_mutasi) {
                            if (in_array($key_static_mutasi, $this->outFieldsMutasi)) {
                                $this->outParams[$lCounter]["mutasi"][$key_static_mutasi] = $value_static_mutasi;
                            }
                        }
//                        $this->outParams[$lCounter]["mutasi"]["rek_id"] = createRekCode($key, $this->inParams['static']['extern_id']);
                        $this->outParams[$lCounter]["mutasi"]["rekening"] = $rekening;

                        break;
                }
            }
            //  endregion mutasi rekening pembantu

            return true;

//cekKuning($this->outParams);
//            mati_disini(get_class($this));
//            if (sizeof($insertIDs) > 0) {
//                return true;
//            }
//            else {
//                return false;
//            }

        }
    }

    private function cekPreValue($rekening, $cabang_id, $produk_id, $gudang_id)
    {

        $this->load->model("Mdls/MdlLockerStockCacheValues");
        $l = new MdlLockerStockCacheValues();

//        $this->addFilter("jenis='produk'");
        $this->addFilter("cabang_id='$cabang_id'");
//        $this->addFilter("gudang_id='$gudang_id'");
        $this->addFilter("extern_id='$produk_id'");
        $this->addFilter("rekening='$rekening'");

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
        cekHitam($this->db->last_query());

        if (sizeof($tmp) > 0) {
            $nilai = array(
                "id" => $tmp['id'],
                "debet" => $tmp['debet'],
                "kredit" => $tmp['kredit'],
                "rekening" => $tmp['rekening'],
                "extern_id" => $tmp['extern_id'],
            );
        }
        else {
            $nilai = array(
                "debet" => 0,
                "kredit" => 0,
            );
        }


        return $nilai;
    }

    public function exec()
    {

        $tableName = $this->tableNames['cache'];
        $tableName_mutasi = $this->tableNames['mutasi'];

        $insertIDs = array();
        if (sizeof($this->outParams) > 0) {
            foreach ($this->outParams as $lCounter => $pSpec) {
                foreach ($pSpec as $mode => $pSpec_mode) {

                    switch ($mode) {
                        case "cache":

                            foreach ($pSpec_mode as $sub_mode => $pSpec_mode_data) {
                                $id = $pSpec_mode_data["id"];
                                unset($pSpec_mode_data["id"]);

//                                arrPrint($pSpec_mode_data);
                                switch ($sub_mode) {
                                    case "insert":
//                                        cekHijau(":: INSERT :: $id ::");

                                        $this->db->insert($tableName, $pSpec_mode_data);
                                        $insertIDs[] = $this->db->insert_id();

                                        cekHere($this->db->last_query());
                                        break;
                                    case "update":
//                                        cekHijau(":: UPDATE :: $id ::");

                                        $this->db->where('id', $id);
                                        $this->db->update($tableName, $pSpec_mode_data);

                                        cekHere($this->db->last_query());
                                        break;
                                }
                            }
                            break;
                        case "mutasi":
//                            $tableName_mutasi = $pSpec_mode["tabel"];
//                            unset($pSpec_mode["tabel"]);
//                            cekHijau(":: INSERT MUTASI REKENING ::");
//                            arrPrint($pSpec_mode);

                            $this->db->insert($tableName_mutasi, $pSpec_mode);
                            $insertIDs[] = $this->db->insert_id();
                            cekHijau($this->db->last_query());
                            break;
                    }
                }
            }



//            mati_disini(get_class($this));

            if (sizeof($insertIDs) > 0) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }



        return true;

    }
}


