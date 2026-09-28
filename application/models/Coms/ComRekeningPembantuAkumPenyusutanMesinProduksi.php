<?php


class ComRekeningPembantuAkumPenyusutanMesinProduksi extends MdlMother
{

    protected $filters = array();
    protected $tableName;
    private $tableName_mutasi;
    private $tableName_fifoAvg;
    private $tableName_master = array();
    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $outFields = array( // dari tabel cache
        "rekening",
        "periode",
        "cabang_id",
        "cabang_nama",
        //        "debet_awal",
        "debet",
        //        "debet_akhir",
        //        "kredit_awal",
        "kredit",
        //        "kredit_akhir",
        //        "qty_debet_awal",
        "qty_debet",
        //        "qty_debet_akhir",
        //        "qty_kredit_awal",
        "qty_kredit",
        //        "qty_kredit_akhir",
        "dtime",
        "tgl",
        "bln",
        "thn",
        "extern_id",
        "extern_nama",
        "jenis",
        "npwp",
        "fulldate",
        "gudang_id",
        "gudang_nama",
    );
    private $koloms = array(
        "cabang_id",
        "produk_id",
        "nama",
        "jml",
        "hpp",
        "jml_nilai",
        //        "jml_ot",
        //        "jml_nilai_ot",
    );
    private $outFieldsMutasi = array( // dari tabel rek mutasi rekening
        "transaksi_id",
        "transaksi_no",
        "transaksi_jenis",
        "cabang_id",
        "cabang_nama",
        "debet_awal",
        "debet",
        "debet_akhir",
        "kredit_awal",
        "kredit",
        "kredit_akhir",
        "qty_debet_awal",
        "qty_debet",
        "qty_debet_akhir",
        "qty_kredit_awal",
        "qty_kredit",
        "qty_kredit_akhir",
        "dtime",
        "extern_id",
        "extern_nama",
        "jenis",
        "npwp",
        "fulldate",
        "gudang_id",
        "gudang_nama",
        "keterangan",
    );
    private $periode = array("harian", "bulanan", "tahunan", "forever");


    public function __construct()
    {
//        $this->tableName_fifoAvg = "fifo_avg";
        $this->tableName = "_rek_pembantu_akumpenyumesinproduksi_cache";
        $this->tableName_master = array(
            "mutasi" => "_rek_pembantu_akumpenyumesinproduksi",
        );
    }

    //  region setter, getter

    public function getTableNameMaster()
    {
        return $this->tableName_master;
    }

    public function setTableNameMaster($tableName_master)
    {
        $this->tableName_master = $tableName_master;
    }

    public function getPeriode()
    {
        return $this->periode;
    }

    public function setPeriode($periode)
    {
        $this->periode = $periode;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function getTableNameTmp()
    {
        return $this->tableName__tmp;
    }

    public function setTableNameTmp($tableName__tmp)
    {
        $this->tableName__tmp = $tableName__tmp;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    public function getInParams()
    {
        return $this->inParams;
    }

    public function setInParams($inParams)
    {
        $this->inParams = $inParams;
    }

    public function getOutParams()
    {
        return $this->outParams;
    }

    public function setOutParams($outParams)
    {
        $this->outParams = $outParams;
    }

    public function getOutFields()
    {
        return $this->outFields;
    }

    public function setOutFields($outFields)
    {
        $this->outFields = $outFields;
    }

    public function getOutFieldsMutasi()
    {
        return $this->outFieldsMutasi;
    }

    public function setOutFieldsMutasi($outFieldsMutasi)
    {
        $this->outFieldsMutasi = $outFieldsMutasi;
    }

    public function getTableNameMutasi()
    {
        return $this->tableName_mutasi;
    }

    //  endregion setter, getter

    public function setTableNameMutasi($tableName_mutasi)
    {
        $this->tableName_mutasi = $tableName_mutasi;
    }

    public function pair($inParams)
    {
//        cekHere("cetak inParams " . get_class($this));
//        arrPrint($inParams);
//mati_disini();

        $this->load->helper("he_mass_table");
        $configBalanceProtections = $this->config->item("accountBalanceProtections");
        $this->inParams = $inParams;
        $itemsMode = 1;
        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            if ($itemsMode == 1) {
                foreach ($this->periode as $periode) {
                    foreach ($this->inParams as $array_params) {
                        $arrRekening = array();
                        foreach ($array_params['loop'] as $key => $x) {
//                        $lCounter++;

                            $value_item = $array_params['static']['produk_nilai'];
                            $unit = $array_params['static']['produk_qty'];

//                            $value = $value_item * $unit;
                            $value = $x;
                            $position = detectRekPosition($key, $value);

                            $arrRekening[] = $key;
                            $table = heReturnTableName($this->tableName_master, $arrRekening);
                            $this->tableName_mutasi = $table[$key]["mutasi"];
//                        $this->tableName = $table[$key]["cache"];

                            $_preValues = $this->cekPreValue($key, $array_params['static']['cabang_id'], $periode, $array_params['static']['extern_id'], $array_params['static']['gudang_id']);

                            if (array_key_exists("id", $_preValues["cache"]) && ($_preValues["cache"]["id"] > 0)) {
                                $mode = "update";
                                $_preValues_id = $_preValues["cache"]["id"];
                            }
                            else {
                                $mode = "insert";
                                $_preValues_id = 0;
                                $this->outParams[$lCounter]["cache"][$mode]["tgl"] = date("d");
                                $this->outParams[$lCounter]["cache"][$mode]["bln"] = date("m");
                                $this->outParams[$lCounter]["cache"][$mode]["thn"] = date("Y");
                            }


                            if ($_preValues['cache']['debet'] > 0) {
                                $preNumber = detectRekByPosition($key, $_preValues['cache']['debet'], "debet");
                            }
                            else {
                                $preNumber = detectRekByPosition($key, $_preValues['cache']['kredit'], "kredit");
                            }
                            if ($_preValues['cache']['qty_debet'] > 0) {
                                $preQtyNumber = detectRekByPosition($key, $_preValues['cache']['qty_debet'], "debet");
                            }
                            else {
                                $preQtyNumber = detectRekByPosition($key, $_preValues['cache']['qty_kredit'], "kredit");
                            }
                            $afterNumber = round($preNumber + $value);
                            $afterQtyNumber = $preQtyNumber + $unit;

                            $afterPosition = detectRekPosition($key, $afterNumber);
                            $afterQtyPosition = detectRekPosition($key, $afterQtyNumber);


                            if (in_array($key, $configBalanceProtections)) {
                                if ($afterPosition != detectRekDefaultPosition($key) && ($afterQtyNumber != 0)) {
                                    die(lgShowAlert("insufficient balance for $key. You requested $value. Available: $preNumber"));
                                }
                            }


                            //  region cache rekening pembantu
                            $pakai_cache = 1;
                            if ($pakai_cache == 1) {
                                switch ($afterPosition) {
                                    case "kredit":
                                        //  region cache rekening pembantu
                                        $this->outParams[$lCounter]["cache"][$mode]["kredit"] = abs($afterNumber);
                                        $this->outParams[$lCounter]["cache"][$mode]["debet"] = 0;
                                        //  endregion cache rekening pembantu
                                        break;
                                    case "debet":
                                        //  region cache rekening pembantu
                                        $this->outParams[$lCounter]["cache"][$mode]["kredit"] = 0;
                                        $this->outParams[$lCounter]["cache"][$mode]["debet"] = abs($afterNumber);
                                        //  endregion cache rekening pembantu
                                        break;
                                    default:
                                        die(lgShowAlert(__LINE__ . " gagal menentukan posisi rekening DEBET / KREDIT " . __FUNCTION__ . " on file " . __FILE__));
                                        break;
                                }
                                switch ($afterQtyPosition) {
                                    case "kredit":
                                        //  region cache rekening pembantu
                                        $this->outParams[$lCounter]["cache"][$mode]["qty_kredit"] = abs($afterQtyNumber);
                                        $this->outParams[$lCounter]["cache"][$mode]["qty_debet"] = 0;
                                        //  endregion cache rekening pembantu
                                        break;
                                    case "debet":
                                        //  region cache rekening pembantu
                                        $this->outParams[$lCounter]["cache"][$mode]["qty_kredit"] = 0;
                                        $this->outParams[$lCounter]["cache"][$mode]["qty_debet"] = abs($afterQtyNumber);
                                        //  endregion cache rekening pembantu
                                        break;
                                    default:
                                        die(lgShowAlert(__LINE__ . " gagal menentukan posisi rekening DEBET / KREDIT " . __FUNCTION__ . " on file " . __FILE__));
                                        break;
                                }
                                switch ($position) {
                                    case "kredit":
                                        $this->outParams[$lCounter]["cache"][$mode]["saldo_kredit"] = $_preValues["cache"]["saldo_kredit"] + abs($value);
//                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_debet"] = 0;
                                        $this->outParams[$lCounter]["cache"][$mode]["saldo_kredit_periode"] = $_preValues["cache"]["saldo_kredit_periode"] + abs($value);
//                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_debet_periode"] = 0;

                                        $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_kredit"] = $_preValues["cache"]["saldo_qty_kredit"] + abs($unit);
//                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_debet"] = 0;
                                        $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_kredit_periode"] = $_preValues["cache"]["saldo_qty_kredit_periode"] + abs($unit);
//                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_debet_periode"] = 0;
                                        break;
                                    case "debet":
                                        $this->outParams[$lCounter]["cache"][$mode]["saldo_debet"] = $_preValues["cache"]["saldo_debet"] + abs($value);
//                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_kredit"] = 0;
                                        $this->outParams[$lCounter]["cache"][$mode]["saldo_debet_periode"] = $_preValues["cache"]["saldo_debet_periode"] + abs($value);
//                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_kredit_periode"] = 0;

                                        $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_debet"] = $_preValues["cache"]["saldo_qty_debet"] + abs($unit);
//                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_kredit"] = 0;
                                        $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_debet_periode"] = $_preValues["cache"]["saldo_qty_debet_periode"] + abs($unit);
//                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_kredit_periode"] = 0;
                                        break;
                                    default:
                                        die(lgShowAlert(__LINE__ . " gagal menentukan posisi rekening DEBET / KREDIT " . __FUNCTION__ . " " . __FILE__));
                                        break;
                                }

                                $this->outParams[$lCounter]["cache"][$mode]["rek_id"] = createRekCode($key, $array_params['static']['extern_id']);
                                $this->outParams[$lCounter]["cache"][$mode]["rekening"] = $key;
                                $this->outParams[$lCounter]["cache"][$mode]["periode"] = $periode;
                                $this->outParams[$lCounter]["cache"][$mode]["id"] = $_preValues_id;

                                foreach ($array_params['static'] as $key_static => $value_static) {
                                    if (in_array($key_static, $this->outFields)) {
                                        $this->outParams[$lCounter]["cache"][$mode][$key_static] = $value_static;
                                    }
                                }
                            }
                            //  endregion cache rekening pembantu

                            //  region mutasi rekening pembantu
                            $pakai_mutasi = 1;
                            if ($pakai_mutasi == 1) {
                                switch ($periode) {
                                    case "forever":
                                        switch ($afterPosition) {
                                            case "kredit":
                                                //  region cache rekening umum
                                                $this->outParams[$lCounter]["mutasi"]["kredit_awal"] = $_preValues["cache"]["kredit"];
                                                $this->outParams[$lCounter]["mutasi"]["kredit_akhir"] = abs($afterNumber);
                                                $this->outParams[$lCounter]["mutasi"]["debet_awal"] = $_preValues["cache"]["debet"];
                                                $this->outParams[$lCounter]["mutasi"]["debet_akhir"] = 0;
                                                //  endregion cache rekening umum
                                                break;
                                            case "debet":
                                                //  region cache rekening umum
                                                $this->outParams[$lCounter]["mutasi"]["kredit_awal"] = $_preValues["cache"]["kredit"];
                                                $this->outParams[$lCounter]["mutasi"]["kredit_akhir"] = 0;
                                                $this->outParams[$lCounter]["mutasi"]["debet_awal"] = $_preValues["cache"]["debet"];
                                                $this->outParams[$lCounter]["mutasi"]["debet_akhir"] = abs($afterNumber);
                                                //  endregion cache rekening umum
                                                break;
                                            default:
                                                $this->outParams[$lCounter]["mutasi"]["kredit_awal"] = $_preValues["cache"]["kredit"];
                                                $this->outParams[$lCounter]["mutasi"]["kredit_akhir"] = 0;
                                                $this->outParams[$lCounter]["mutasi"]["debet_awal"] = $_preValues["cache"]["debet"];
                                                $this->outParams[$lCounter]["mutasi"]["debet_akhir"] = 0;
                                                break;
                                        }
                                        switch ($afterQtyPosition) {
                                            case "kredit":
                                                //  region cache rekening umum
                                                $this->outParams[$lCounter]["mutasi"]["qty_kredit_awal"] = $_preValues["cache"]["qty_kredit"];
                                                $this->outParams[$lCounter]["mutasi"]["qty_kredit_akhir"] = abs($afterQtyNumber);
                                                $this->outParams[$lCounter]["mutasi"]["qty_debet_awal"] = $_preValues["cache"]["qty_debet"];
                                                $this->outParams[$lCounter]["mutasi"]["qty_debet_akhir"] = 0;
                                                //  endregion cache rekening umum
                                                break;
                                            case "debet":
                                                //  region cache rekening umum
                                                $this->outParams[$lCounter]["mutasi"]["qty_kredit_awal"] = $_preValues["cache"]["qty_kredit"];
                                                $this->outParams[$lCounter]["mutasi"]["qty_kredit_akhir"] = 0;
                                                $this->outParams[$lCounter]["mutasi"]["qty_debet_awal"] = $_preValues["cache"]["qty_debet"];
                                                $this->outParams[$lCounter]["mutasi"]["qty_debet_akhir"] = abs($afterQtyNumber);
                                                //  endregion cache rekening umum
                                                break;
                                            default:
                                                $this->outParams[$lCounter]["mutasi"]["qty_kredit_awal"] = $_preValues["cache"]["qty_kredit"];
                                                $this->outParams[$lCounter]["mutasi"]["qty_kredit_akhir"] = 0;
                                                $this->outParams[$lCounter]["mutasi"]["qty_debet_awal"] = $_preValues["cache"]["qty_debet"];
                                                $this->outParams[$lCounter]["mutasi"]["qty_debet_akhir"] = 0;
                                                break;
                                        }
                                        switch ($position) {
                                            case "kredit":
                                                //  region cache rekening umum
                                                $this->outParams[$lCounter]["mutasi"]["kredit"] = abs($value);
                                                $this->outParams[$lCounter]["mutasi"]["debet"] = 0;
                                                $this->outParams[$lCounter]["mutasi"]["qty_kredit"] = abs($unit);
                                                $this->outParams[$lCounter]["mutasi"]["qty_debet"] = 0;
                                                //  endregion cache rekening umum
                                                break;
                                            case "debet":
                                                //  region cache rekening umum
                                                $this->outParams[$lCounter]["mutasi"]["debet"] = abs($value);
                                                $this->outParams[$lCounter]["mutasi"]["kredit"] = 0;
                                                $this->outParams[$lCounter]["mutasi"]["qty_debet"] = abs($unit);
                                                $this->outParams[$lCounter]["mutasi"]["qty_kredit"] = 0;
                                                //  endregion cache rekening umum
                                                break;
                                            default:
                                                die(lgShowAlert("Transaksi gagal, karena rekening $key gagal menentukan posisi DEBET/KREDIT."));
                                                break;
                                        }

                                        foreach ($array_params['static'] as $key_static_mutasi => $value_static_mutasi) {
                                            if (in_array($key_static_mutasi, $this->outFieldsMutasi)) {
                                                $this->outParams[$lCounter]["mutasi"][$key_static_mutasi] = $value_static_mutasi;
                                            }
                                        }
                                        $this->outParams[$lCounter]["mutasi"]["rek_id"] = createRekCode($key, $array_params['static']['extern_id']);
                                        $this->outParams[$lCounter]["mutasi"]["rekening"] = $key;


                                        //  region validasi saldo, tidak boleh minus
//                                    if ($this->outParams[$lCounter]["mutasi"]["nilai_af"] < 0) {
//                                        mati_disini(__LINE__ . " terjadi kesalahan pada saldo value persediaan, saldo value bernilai minus. " . __CLASS__ . " :: " . __FUNCTION__);
//                                    } elseif ($this->outParams[$lCounter]["mutasi"]["unit_af"] < 0) {
//                                        $msg = "Transaksi gagal karena terjadi saldo unit " . $array_params['static']['extern_id'] . " bernilai minus (" . $this->outParams[$lCounter]['mutasi']['unit_af'] . ")";
//                                        die(lgShowAlert($msg));
//                                    }
                                        //  endregion validasi saldo, tidak boleh minus
                                        break;

                                }
                            }
                            //  endregion mutasi rekening pembantu


                            //region Exec()
                            $pakai_exec = 1;
                            if ($pakai_exec == 1) {
                                $tableName = $this->tableName;
                                $tableName_mutasi = $this->tableName_mutasi;

                                $insertIDs = array();
                                if (sizeof($this->outParams) > 0) {
                                    foreach ($this->outParams as $lCounter => $pSpec) {
                                        foreach ($pSpec as $mode => $pSpec_mode) {
                                            switch ($mode) {
                                                case "cache":
                                                    foreach ($pSpec_mode as $sub_mode => $pSpec_mode_data) {
                                                        $id = $pSpec_mode_data["id"];
                                                        unset($pSpec_mode_data["id"]);

                                                        switch ($sub_mode) {
                                                            case "insert":

                                                                $this->db->insert($tableName, $pSpec_mode_data);
                                                                $insertIDs[] = $this->db->insert_id();
                                                                cekUngu("$sub_mode :: " . $this->db->last_query());
                                                                break;
                                                            case "update":

                                                                $this->db->where('id', $id);
                                                                $insertIDs[] = $this->db->update($tableName, $pSpec_mode_data);
                                                                cekOrange("$sub_mode :: " . $this->db->last_query());
                                                                break;
                                                        }
                                                    }
                                                    break;
                                                case "mutasi":

                                                    unset($pSpec_mode["tabel"]);

                                                    $this->db->insert($tableName_mutasi, $pSpec_mode);
                                                    $insertIDs[] = $this->db->insert_id();
                                                    cekHijau("$mode :: " . $this->db->last_query());
                                                    break;
                                            }
                                        }
                                    }
                                    $this->outParams = array();
//                                cekKuning($insertIDs);
                                    if (sizeof($insertIDs) == 0) {
                                        cekMerah("::: PERIODE : $periode :::");
                                        return false;
                                    }
                                }
                                else {
                                    cekMerah("::: PERIODE : $periode :::");
                                    return false;
                                }
                            }
                            //endregion

                        }
                    }
                }
            }
            else {
                $this->inParams = $inParams;
                if (sizeof($this->inParams['loop']) > 0) {
                    foreach ($this->periode as $periode) {
                        $arrRekening = array();
                        foreach ($this->inParams['loop'] as $key => $value) {
                            $lCounter++;

                            $position = detectRekPosition($key, $value);

                            $arrRekening[] = $key;
                            $table = heReturnTableName($this->tableName_master, $arrRekening);
                            $this->tableName_mutasi = $table[$key]["mutasi"];


                            $_preValues = $this->cekPreValue($key, $this->inParams['static']['cabang_id'], $periode, $this->inParams['static']['extern_id'], $this->inParams['static']['gudang_id']);

                            if (array_key_exists("id", $_preValues["cache"]) && ($_preValues["cache"]["id"] > 0)) {
                                $mode = "update";
                                $_preValues_id = $_preValues["cache"]["id"];
                            }
                            else {
                                $mode = "insert";
                                $_preValues_id = 0;
                                $this->outParams[$lCounter]["cache"][$mode]["tgl"] = date("d");
                                $this->outParams[$lCounter]["cache"][$mode]["bln"] = date("m");
                                $this->outParams[$lCounter]["cache"][$mode]["thn"] = date("Y");
                            }

                            if ($_preValues['cache']['debet'] > 0) {
                                $preNumber = detectRekByPosition($key, $_preValues['cache']['debet'], "debet");
                            }
                            else {
                                $preNumber = detectRekByPosition($key, $_preValues['cache']['kredit'], "kredit");
                            }
                            $afterNumber = round($preNumber + $value);
                            $afterPosition = detectRekPosition($key, $afterNumber);


                            if (in_array($key, $configBalanceProtections)) {
                                if ($afterPosition != detectRekDefaultPosition($key) && ($afterNumber != 0)) {
                                    die(lgShowAlert("insufficient balance for $key " . $this->inParams['static']['extern_nama'] . ""));
                                }
                            }


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
                                switch ($position) {
                                    case "kredit":
                                        $this->outParams[$lCounter]["cache"][$mode]["saldo_kredit"] = $_preValues["cache"]["saldo_kredit"] + abs($value);
//                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_debet"] = 0;
                                        $this->outParams[$lCounter]["cache"][$mode]["saldo_kredit_periode"] = $_preValues["cache"]["saldo_kredit_periode"] + abs($value);
//                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_debet_periode"] = 0;

//                                        $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_kredit"] = $_preValues["cache"]["saldo_qty_kredit"] + abs($unit);
////                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_debet"] = 0;
//                                        $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_kredit_periode"] = $_preValues["cache"]["saldo_qty_kredit_periode"] + abs($unit);
////                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_debet_periode"] = 0;
                                        break;
                                    case "debet":
                                        $this->outParams[$lCounter]["cache"][$mode]["saldo_debet"] = $_preValues["cache"]["saldo_debet"] + abs($value);
//                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_kredit"] = 0;
                                        $this->outParams[$lCounter]["cache"][$mode]["saldo_debet_periode"] = $_preValues["cache"]["saldo_debet_periode"] + abs($value);
//                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_kredit_periode"] = 0;

//                                        $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_debet"] = $_preValues["cache"]["saldo_qty_debet"] + abs($unit);
////                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_kredit"] = 0;
//                                        $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_debet_periode"] = $_preValues["cache"]["saldo_qty_debet_periode"] + abs($unit);
////                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_kredit_periode"] = 0;
                                        break;
                                    default:
                                        die(lgShowAlert(__LINE__ . " gagal menentukan posisi rekening DEBET / KREDIT " . __FUNCTION__ . " " . __FILE__));
                                        break;
                                }

                                $this->outParams[$lCounter]["cache"][$mode]["rek_id"] = createRekCode($key, $this->inParams['static']['extern_id']);
                                $this->outParams[$lCounter]["cache"][$mode]["rekening"] = $key;
                                $this->outParams[$lCounter]["cache"][$mode]["periode"] = $periode;
                                $this->outParams[$lCounter]["cache"][$mode]["id"] = $_preValues_id;

                                foreach ($this->inParams['static'] as $key_static => $value_static) {
                                    if (in_array($key_static, $this->outFields)) {
                                        $this->outParams[$lCounter]["cache"][$mode][$key_static] = $value_static;
                                    }
                                }
                            }
                            //  endregion cache rekening pembantu

                            //  region mutasi rekening pembantu
                            $pakai_mutasi = 1;
                            if ($pakai_mutasi == 1) {
                                switch ($periode) {
                                    case "forever":
                                        switch ($afterPosition) {
                                            case "kredit":
                                                //  region cache rekening umum
                                                $this->outParams[$lCounter]["mutasi"]["kredit_awal"] = $_preValues["cache"]["kredit"];
                                                $this->outParams[$lCounter]["mutasi"]["kredit_akhir"] = abs($afterNumber);

                                                $this->outParams[$lCounter]["mutasi"]["debet_awal"] = $_preValues["cache"]["debet"];
                                                $this->outParams[$lCounter]["mutasi"]["debet_akhir"] = 0;
                                                //  endregion cache rekening umum
                                                break;
                                            case "debet":
                                                //  region cache rekening umum
                                                $this->outParams[$lCounter]["mutasi"]["debet_awal"] = $_preValues["cache"]["debet"];
                                                $this->outParams[$lCounter]["mutasi"]["debet_akhir"] = abs($afterNumber);

                                                $this->outParams[$lCounter]["mutasi"]["kredit_awal"] = $_preValues["cache"]["kredit"];
                                                $this->outParams[$lCounter]["mutasi"]["kredit_akhir"] = 0;
                                                //  endregion cache rekening umum
                                                break;
                                            default:
                                                $this->outParams[$lCounter]["mutasi"]["debet_awal"] = $_preValues["cache"]["debet"];
                                                $this->outParams[$lCounter]["mutasi"]["debet_akhir"] = 0;

                                                $this->outParams[$lCounter]["mutasi"]["kredit_awal"] = $_preValues["cache"]["kredit"];
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
                                        $this->outParams[$lCounter]["mutasi"]["rek_id"] = createRekCode($key, $this->inParams['static']['extern_id']);
                                        $this->outParams[$lCounter]["mutasi"]["rekening"] = $key;


                                        break;
                                }
                            }
                            //  endregion mutasi rekening pembantu


                            //region Exec()
                            $pakai_exec = 1;
                            if ($pakai_exec == 1) {
                                $tableName = $this->tableName;
                                $tableName_mutasi = $this->tableName_mutasi;

                                $insertIDs = array();
                                if (sizeof($this->outParams) > 0) {
                                    foreach ($this->outParams as $lCounter => $pSpec) {
                                        foreach ($pSpec as $mode => $pSpec_mode) {
                                            switch ($mode) {
                                                case "cache":
                                                    foreach ($pSpec_mode as $sub_mode => $pSpec_mode_data) {
                                                        $id = $pSpec_mode_data["id"];
                                                        unset($pSpec_mode_data["id"]);

                                                        switch ($sub_mode) {
                                                            case "insert":

                                                                $this->db->insert($tableName, $pSpec_mode_data);
                                                                $insertIDs[] = $this->db->insert_id();
                                                                cekUngu("$sub_mode :: " . $this->db->last_query());
                                                                break;
                                                            case "update":

                                                                $this->db->where('id', $id);
                                                                $insertIDs[] = $this->db->update($tableName, $pSpec_mode_data);
                                                                cekOrange("$sub_mode :: " . $this->db->last_query());
                                                                break;
                                                        }
                                                    }
                                                    break;
                                                case "mutasi":

                                                    unset($pSpec_mode["tabel"]);

                                                    $this->db->insert($tableName_mutasi, $pSpec_mode);
                                                    $insertIDs[] = $this->db->insert_id();
                                                    cekHijau("$mode :: " . $this->db->last_query());
                                                    break;
                                            }
                                        }
                                    }
                                    $this->outParams = array();
//                                cekKuning($insertIDs);
                                    if (sizeof($insertIDs) == 0) {
                                        cekMerah("::: PERIODE : $periode :::");
                                        return false;
                                    }
                                }
                                else {
                                    cekMerah("::: PERIODE : $periode :::");
                                    return false;
                                }
                            }
                            //endregion
                        }
                    }
                }
            }

        }


        if (sizeof($insertIDs) > 0) {

            return true;
        }
        else {
            return false;
        }
    }

    private function cekPreValue($rek, $cabang_id, $periode, $produk_id, $gudang_id)
    {
        $tgl = date("d");
        $bln = date("m");
        $thn = date("Y");


        $this->filters = array();
        switch ($periode) {
            case "harian":
                $this->addFilter("tgl='$tgl'");
                $this->addFilter("bln='$bln'");
                $this->addFilter("thn='$thn'");
                break;
            case "bulanan":
                $this->addFilter("bln='$bln'");
                $this->addFilter("thn='$thn'");
                break;
            case "tahunan":
                $this->addFilter("thn='$thn'");
                break;
            case "forever":
                break;
        }

        $this->addFilter("rekening='$rek'");
        $this->addFilter("cabang_id='$cabang_id'");
        $this->addFilter("gudang_id='$gudang_id'");
        $this->addFilter("periode='$periode'");
        $this->addFilter("extern_id='$produk_id'");
//        $criteria = array();
//        if (sizeof($this->filters) > 0) {
//            $fCnt = 0;
//            $criteria = array();
//            foreach ($this->filters as $f) {
//                $fCnt++;
//                $tmp = explode("=", $f);
//                if (sizeof($tmp) > 1) { //==berarti pakai tanda samadengan =
//                    $criteria[$tmp[0]] = trim($tmp[1], "'");
//                }
//                else {
//                    $tmp = explode("<>", $f);
//                    if (sizeof($tmp) > 1) { //==berarti pakai tanda tidak sama dengan <>
//
//                        $criteria[$tmp[0] . "!="] = trim($tmp[1], "'");
//                    }
//                }
//            }
//        }
//
//        //  region mengambil saldo dari rek_cache
//        $this->db->where($criteria);
//        $tmp = $this->db->get($this->tableName)->result();
//        cekHitam($this->db->last_query() . " # " . count($tmp));
        $result = array();
        $localFilters = array();
        if (sizeof($this->filters) > 0) {
            foreach ($this->filters as $f) {
                $tmpArr = explode("=", $f);
                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");

            }
        }

        $query = $this->db->select()
            ->from($this->tableName)
            ->where($localFilters)
            ->limit(1)
            ->get_compiled_select();

        $tmp = $this->db->query("{$query} FOR UPDATE")->result();

        if (sizeof($tmp) > 0) {
            // bila count($tmp) > 0, maka ambil saldo periode sendiri, dan mode update
            foreach ($tmp as $row) {
                $result["cache"] = array(
                    "id" => $row->id,
                    "debet" => $row->debet,
                    "kredit" => $row->kredit,
                    "qty_debet" => $row->qty_debet,
                    "qty_kredit" => $row->qty_kredit,
                    // saldo bawah
                    "saldo_debet" => $row->saldo_debet,
                    "saldo_kredit" => $row->saldo_kredit,
                    "saldo_debet_periode" => $row->saldo_debet_periode,
                    "saldo_kredit_periode" => $row->saldo_kredit_periode,
                    // saldo bawah
                    "saldo_qty_debet" => $row->saldo_qty_debet,
                    "saldo_qty_kredit" => $row->saldo_qty_kredit,
                    "saldo_qty_debet_periode" => $row->saldo_qty_debet_periode,
                    "saldo_qty_kredit_periode" => $row->saldo_qty_kredit_periode,
                );
            }
        }
        else {
            // bila count($tmp) == 0, maka ambil saldo periode forever dan mode insert
            $this->filters = array();
            $this->addFilter("rekening='$rek'");
            $this->addFilter("cabang_id='$cabang_id'");
            $this->addFilter("gudang_id='$gudang_id'");
            $this->addFilter("periode='forever'");
            $this->addFilter("extern_id='$produk_id'");
//
//            $criteria = array();
//            if (sizeof($this->filters) > 0) {
//                $fCnt = 0;
//                $criteria = array();
//                foreach ($this->filters as $f) {
//                    $fCnt++;
//                    $tmp = explode("=", $f);
//                    if (sizeof($tmp) > 1) { //==berarti pakai tanda samadengan =
//                        $criteria[$tmp[0]] = trim($tmp[1], "'");
//                    }
//                    else {
//                        $tmp = explode("<>", $f);
//                        if (sizeof($tmp) > 1) { //==berarti pakai tanda tidak sama dengan <>
//
//                            $criteria[$tmp[0] . "!="] = trim($tmp[1], "'");
//                        }
//                    }
//                }
//            }
//
//            $this->db->where($criteria);
//            $tmp = $this->db->get($this->tableName)->result();
//            cekHere($this->db->last_query() . " # " . count($tmp));
            $result = array();
            $localFilters = array();
            if (sizeof($this->filters) > 0) {
                foreach ($this->filters as $f) {
                    $tmpArr = explode("=", $f);
                    $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");

                }
            }

            $query = $this->db->select()
                ->from($this->tableName)
                ->where($localFilters)
                ->limit(1)
                ->get_compiled_select();

            $tmp = $this->db->query("{$query} FOR UPDATE")->result();

            if (sizeof($tmp) > 0) {
                foreach ($tmp as $row) {
                    $result["cache"] = array(
                        "debet" => $row->debet,
                        "kredit" => $row->kredit,
                        "qty_debet" => $row->qty_debet,
                        "qty_kredit" => $row->qty_kredit,
                        // saldo bawah
                        "saldo_debet" => $row->saldo_debet,
                        "saldo_kredit" => $row->saldo_kredit,
                        "saldo_debet_periode" => 0,
                        "saldo_kredit_periode" => 0,
                        // saldo bawah
                        "saldo_qty_debet" => $row->saldo_qty_debet,
                        "saldo_qty_kredit" => $row->saldo_qty_kredit,
                        "saldo_qty_debet_periode" => 0,
                        "saldo_qty_kredit_periode" => 0,
                    );
                }
            }
            else {
                $result["cache"] = array(
                    "debet" => 0,
                    "kredit" => 0,
                    "qty_debet" => 0,
                    "qty_kredit" => 0,
                    // saldo bawah
                    "saldo_debet" => 0,
                    "saldo_kredit" => 0,
                    "saldo_debet_periode" => 0,
                    "saldo_kredit_periode" => 0,
                    // saldo bawah
                    "saldo_qty_debet" => 0,
                    "saldo_qty_kredit" => 0,
                    "saldo_qty_debet_periode" => 0,
                    "saldo_qty_kredit_periode" => 0,
                );
            }
        }
        //  endregion mengambil saldo dari rek_cache

        return $result;
    }

    public function addFilter($f)
    {
        $this->filters[] = $f;
    }

    public function exec()
    {
//
//        $tableName = $this->tableName;
//        $tableName_mutasi = $this->tableName_mutasi;
//
//        $insertIDs = array();
//        if (sizeof($this->outParams) > 0) {
//            foreach ($this->outParams as $lCounter => $pSpec) {
//                foreach ($pSpec as $mode => $pSpec_mode) {
//
//                    switch ($mode) {
//                        case "cache":
//
//                            foreach ($pSpec_mode as $sub_mode => $pSpec_mode_data) {
//                                $id = $pSpec_mode_data["id"];
//                                unset($pSpec_mode_data["id"]);
//
////                                arrPrint($pSpec_mode_data);
//                                switch ($sub_mode) {
//                                    case "insert":
////                                        cekHijau(":: INSERT :: $id ::");
//
//                                        $this->db->insert($tableName, $pSpec_mode_data);
//                                        $insertIDs[] = $this->db->insert_id();
//                                        cekUngu("$sub_mode :: " . $this->db->last_query());
//                                        break;
//                                    case "update":
////                                        cekHijau(":: UPDATE :: $id ::");
//
//                                        $this->db->where('id', $id);
//                                        $this->db->update($tableName, $pSpec_mode_data);
//                                        cekOrange("$sub_mode :: " . $this->db->last_query());
//                                        break;
//                                }
//                            }
//                            break;
//                        case "mutasi":
////                            $tableName_mutasi = $pSpec_mode["tabel"];
//                            unset($pSpec_mode["tabel"]);
////                            cekHijau(":: INSERT MUTASI REKENING ::");
////                            arrPrint($pSpec_mode);
//
//                            $this->db->insert($tableName_mutasi, $pSpec_mode);
//                            $insertIDs[] = $this->db->insert_id();
//                            cekHijau("$mode :: " . $this->db->last_query());
//                            break;
//                    }
//                }
//            }
//
//
//
//            if (sizeof($insertIDs) > 0) {
//                return true;
//            }
//            else {
//                return false;
//            }
//        }
//        else {
//            return false;
//        }

        return true;
    }

    public function lookupLastEntries($cabang_id)
    {
//        $periode = $this->getPeriode()['3'];
        $condite = array("trash" => "0", "cabang_id" => "$cabang_id");
        $arrKoloms = $this->getKoloms();
        $selectKolom = "";
        foreach ($arrKoloms as $kolom) {
            $selectKolom .= "$kolom,";
        }
        $selectKolom = rtrim($selectKolom, ",");

        $this->db->select($selectKolom);
        $this->db->where($condite);
        $q = $this->db->get($this->tableName_fifoAvg)->result();

        return $q;
    }

    public function getKoloms()
    {
        return $this->koloms;
    }

    //region tambahan widi cek last stok ambil dari fifo avg

    public function setKoloms($koloms)
    {
        $this->koloms = $koloms;
    }

    //endregion

    public function buildTables($inParams)
    {

        $this->load->helper("he_mass_table");

        $arrRekening = array();
        $this->inParams = $inParams;
        if (sizeof($this->inParams['loop']) > 0) {
            foreach ($this->periode as $periode) {
                $arrRekening = array();
                foreach ($this->inParams['loop'] as $key => $value) {
                    $arrRekening[] = $key;
                }
            }
        }
        else {
            $arrRekening = array();
        }


        if (sizeof($arrRekening) > 0) {
            $result = heReturnTableName($this->tableName_master, $arrRekening);
            if (sizeof($result) > 0) {
                foreach ($result as $rek => $arrSpec) {
                    foreach ($arrSpec as $key => $val) {
//                        cekMerah("create tabel $val - $key");
                        $result_c = tableForceCheck($val, $this->tableName_master[$key]);
                    }
                }
            }
        }
    }


    public function fetchBalances($rek, $key = "", $sortBy = "", $sortMode = "ASC")
    {//==memanggil saldo2 dari rekening tertentu
//        $tableNames = heReturnTableName($this->tableName_master, array($rek));
        $this->db->select("*");
        $this->db->where(array("periode" => "forever", "rekening" => $rek));
//        $this->db->join("produk", "produk.id = extern_id ");
        if ($sortBy != "") {
            $this->db->order_by($sortBy, $sortMode);
        }
        else {
//            $this->db->order_by("UPPER(" . $this->tableName . ".id)", "desc");
            $this->db->order_by("rek_id", "asc");
        }

        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }


        if ($key != "") {
            $this->createSmartSearch($key, array("extern_nama"));
        }


        $result = $this->db->get($this->tableName);
//        cekkuning($this->db->last_query());
        $results = array();
        if (sizeof($result->result()) > 0) {
            foreach ($result->result() as $row) {
                $results[] = array(
                    "id" => $row->extern_id,
                    "rek_id" => $row->rek_id,
                    "name" => $row->extern_nama,
                    "debet" => $row->debet,
                    "kredit" => $row->kredit,
                    "qty_debet" => $row->qty_debet,
                    "qty_kredit" => $row->qty_kredit,
                );
            }
        }

        // yang direturn hasil dari tabel, apa adanya...
        return $result->result();

    }

    public function fetchMoves($rek, $externID)
    {//==memanggil saldo2 dari rekening tertentu
        $tableNames = heReturnTableName($this->tableName_master, array($rek));
        $this->db->select("*");
        $this->db->where(array("extern_id" => $externID));
        $this->db->order_by("id", "asc");

        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        $result = $this->db->get($tableNames[$rek]['mutasi']);
//        cekkuning($this->db->last_query());

        return $result->result();
    }

    public function fetchMovesByTransIDs($rek, $trIDs)
    {//==memanggil saldo2 dari rekening tertentu
        $tableNames = heReturnTableName($this->tableName_master, array($rek));
        $this->db->select("*");
        if (is_array($trIDs) && sizeof($trIDs) > 0) {
            $this->db->where("transaksi_id  IN (" . implode(",", $trIDs) . ")");
        }
        $this->db->order_by("id", "asc");

        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        $result = $this->db->get($tableNames[$rek]['mutasi']);
//        cekkuning($this->db->last_query());

        return $result->result();
    }


    public function insertTodayMoves($rek, $datas)
    {
        $this->load->helper("he_mass_table");

//        $rek = "kas";
        $tableNames = heReturnTableName($this->tableName_master, array($rek))[$rek]['mutasi'];
        // $this->addData($datas);
        $this->db->insert($tableNames, $datas);
    }

    public function insertTodayBalances($datas)
    {
        $this->load->helper("he_mass_table");

//        $rek = "kas";
        $tableNames = $this->tableName;
        // $this->addData($datas);
        $this->db->insert($tableNames, $datas);
    }

    public function fetchBalancePeriode($rek, $externID, $periode)
    {//==memanggil saldo2 dari rekening tertentu
        $tableNames = $this->tableName;
        $this->db->select("*");
        $this->db->where(
            array(
                "extern_id" => $externID,
                "periode" => $periode,
                "rekening" => $rek,
            )
        );
        $this->db->order_by("id", "asc");


        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }


        $result = $this->db->get($this->tableName);

        return $result->result();
    }
}