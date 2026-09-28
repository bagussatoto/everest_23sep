<?php


class ComRekeningPembantuAntarGudangProduk extends MdlMother
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
        "extern2_id",
        "extern2_nama",
        "jenis",
        "npwp",
        "fulldate",
        "gudang_id",
        "gudang_nama",
        "harga",
        "harga_avg",
        "harga_awal",
        "supplierID",
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
        "extern2_id",
        "extern2_nama",
        "jenis",
        "npwp",
        "fulldate",
        "gudang_id",
        "gudang_nama",
        "keterangan",
        "harga",
        "harga_avg",
        "harga_awal",
        "supplierID",
    );
    private $periode = array("harian", "bulanan", "tahunan", "forever");
    protected $jenisTr;
    protected $sortBy = array(
        "kolom" => "id",
        "mode" => "asc",
    );

    public function __construct()
    {
        $this->tableName_fifoAvg = "fifo_avg";
        $this->tableName = "_rek_pembantu_subantargudang_cache";
        $this->tableName_master = array(
            "mutasi" => "_rek_pembantu_subantargudang",
        );
    }

    //  region setter, getter
    public function getJenisTr()
    {
        return $this->jenisTr;
    }

    public function setJenisTr($jenisTr)
    {
        $this->jenisTr = $jenisTr;
    }

    public function getSortBy()
    {
        return $this->sortBy;
    }

    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
    }

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
        $this->load->helper("he_mass_table");
        $configBalanceProtections = $this->config->item("accountBalanceProtections");
        $this->inParams = $inParams;

        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            foreach ($this->periode as $periode) {
                foreach ($this->inParams as $array_params) {
                    $arrRekening = array();
                    foreach ($array_params['loop'] as $key => $x) {

                        $value_item = trim($array_params['static']['produk_nilai']);
                        $unit = trim($array_params['static']['produk_qty']);

                        $value = $x;
                        $position = detectRekPosition($key, $value);

                        $arrRekening[] = $key;
                        $table = heReturnTableName($this->tableName_master, $arrRekening);
                        $this->tableName_mutasi = $table[$key]["mutasi"];
                        //                        $this->tableName = $table[$key]["cache"];

                        $_preValues = $this->cekPreValue(
                            $key,
                            $array_params['static']['cabang_id'],
                            $periode,
                            $array_params['static']['extern_id'],
                            $array_params['static']['gudang_id'],
                            $array_params['static']['fulldate'],
                            $array_params['static']['extern2_id']
                        );


                        if (array_key_exists("id", $_preValues["cache"]) && ($_preValues["cache"]["id"] > 0)) {
                            $mode = "update";
                            $_preValues_id = $_preValues["cache"]["id"];
                        }
                        else {
                            $mode = "insert";
                            $_preValues_id = 0;
                            $this->outParams[$lCounter]["cache"][$mode]["tgl"] = isset($date_ex[2]) ? $date_ex[2] : date("d");
                            $this->outParams[$lCounter]["cache"][$mode]["bln"] = isset($date_ex[1]) ? $date_ex[1] : date("m");
                            $this->outParams[$lCounter]["cache"][$mode]["thn"] = isset($date_ex[0]) ? $date_ex[0] : date("Y");
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

                        $afterNumber = $preNumber + $value;
                        //                        $afterNumber = (($preNumber + $value)>0.1) ? $preNumber + $value : 0;
                        $afterQtyNumber = $preQtyNumber + $unit;

                        $afterPosition = detectRekPosition($key, $afterNumber);
                        $afterQtyPosition = detectRekPosition($key, $afterQtyNumber);


                        $afterNumberAvg = $afterQtyNumber == 0 ? 0 : $afterNumber / $afterQtyNumber;

                        cekhitam(":: $afterNumber, val: $value,  $afterPosition, afterQty: $afterQtyNumber");
                        if (in_array($key, $configBalanceProtections)) {
                            if ($afterPosition != detectRekDefaultPosition($key) && ($afterQtyNumber != 0)) {
                                $pName = $array_params['static']['extern_name'];
                                cekMerah("insufficient balance for $key. You requested $value. Available: $preNumber");
                                die(lgShowAlert("Saldo tidak cukup untuk $key, $pName. Anda membutuhkan $value, tersedia $preNumber. Silahkan menghubungi admin."));
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
                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_kredit_periode"] = $_preValues["cache"]["saldo_kredit_periode"] + abs($value);
                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_kredit"] = $_preValues["cache"]["saldo_qty_kredit"] + abs($unit);
                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_kredit_periode"] = $_preValues["cache"]["saldo_qty_kredit_periode"] + abs($unit);
                                    break;
                                case "debet":
                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_debet"] = $_preValues["cache"]["saldo_debet"] + abs($value);
                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_debet_periode"] = $_preValues["cache"]["saldo_debet_periode"] + abs($value);
                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_debet"] = $_preValues["cache"]["saldo_qty_debet"] + abs($unit);
                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_debet_periode"] = $_preValues["cache"]["saldo_qty_debet_periode"] + abs($unit);

                                    break;
                                default:
                                    die(lgShowAlert(__LINE__ . " gagal menentukan posisi rekening DEBET / KREDIT " . __FUNCTION__ . " " . __FILE__));
                                    break;
                            }
                            $this->outParams[$lCounter]["cache"][$mode]["rek_id"] = createRekCode($key, $array_params['static']['extern_id']);
                            $this->outParams[$lCounter]["cache"][$mode]["rekening"] = $key;
                            $this->outParams[$lCounter]["cache"][$mode]["periode"] = $periode;
                            $this->outParams[$lCounter]["cache"][$mode]["id"] = $_preValues_id;
                            $this->outParams[$lCounter]["cache"][$mode]["harga"] = $value_item;
                            $this->outParams[$lCounter]["cache"][$mode]["harga_avg"] = abs($afterNumberAvg);
                            $this->outParams[$lCounter]["cache"][$mode]["harga_awal"] = abs($_preValues['cache']['harga']);

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

                                    $this->outParams[$lCounter]["mutasi"]["harga"] = abs($value_item);
                                    $this->outParams[$lCounter]["mutasi"]["harga_avg"] = abs($afterNumberAvg);
                                    $this->outParams[$lCounter]["mutasi"]["harga_awal"] = abs($_preValues['cache']['harga']);


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


        if (sizeof($insertIDs) > 0) {

            return true;
        }
        else {
            return false;
        }
    }

    private function cekPreValue($rek, $cabang_id, $periode, $produk_id, $gudang_id, $date = NULL, $gudang2_id)
    {
        if ($date != NULL) {
            $date_ex = explode("-", $date);
            $tgl = $date_ex[2];
            $bln = $date_ex[1];
            $thn = $date_ex[0];
        }
        else {
            $tgl = date("d");
            $bln = date("m");
            $thn = date("Y");
        }


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
        $this->addFilter("extern2_id='$gudang2_id'");
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
        showLast_query("biru");
        if (sizeof($tmp) > 0) {
            // bila count($tmp) > 0, maka ambil saldo periode sendiri, dan mode update
            foreach ($tmp as $row) {
                $result["cache"] = array(
                    "id" => $row->id,
                    "debet" => $row->debet,
                    "kredit" => $row->kredit,
                    "qty_debet" => $row->qty_debet,
                    "qty_kredit" => $row->qty_kredit,
                    "harga" => $row->harga,
                    "saldo_qty_debet" => $row->saldo_qty_debet,
                    "saldo_qty_kredit" => $row->saldo_qty_kredit,
                    "saldo_debet" => $row->saldo_debet,
                    "saldo_kredit" => $row->saldo_kredit,
                    "saldo_debet_periode" => $row->saldo_debet_periode,
                    "saldo_kredit_periode" => $row->saldo_kredit_periode,
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
            $this->addFilter("extern2_id='$gudang2_id'");
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
            showLast_query("biru");
            if (sizeof($tmp) > 0) {
                foreach ($tmp as $row) {
                    $result["cache"] = array(
                        "debet" => $row->debet,
                        "kredit" => $row->kredit,
                        "qty_debet" => $row->qty_debet,
                        "qty_kredit" => $row->qty_kredit,
                        "harga" => $row->harga,
                        "saldo_qty_debet" => $row->saldo_qty_debet,
                        "saldo_qty_kredit" => $row->saldo_qty_kredit,
                        "saldo_debet" => $row->saldo_debet,
                        "saldo_kredit" => $row->saldo_kredit,
                        "saldo_debet_periode" => 0,
                        "saldo_kredit_periode" => 0,
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
                    "harga" => 0,
                    "saldo_qty_debet" => 0,
                    "saldo_qty_kredit" => 0,
                    "saldo_debet" => 0,
                    "saldo_kredit" => 0,
                    "saldo_debet_periode" => 0,
                    "saldo_kredit_periode" => 0,
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
        $this->db->where(
            array(
                "extern_id" => $externID
//                "transaksi_id >" => '0'
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

        $result = $this->db->get($tableNames[$rek]['mutasi']);
        //        cekkuning($this->db->last_query());

        return $result->result();
    }

    public function fetchMoves2($rek)
    {//==memanggil saldo2 dari rekening tertentu
        $tableNames = heReturnTableName($this->tableName_master, array($rek));
        $this->db->select("*");
        //        $this->db->where(array("extern_id" => $externID));
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

    public function fetchMovement($rek)
    {//==memanggil saldo2 dari rekening tertentu
        $tableNames = heReturnTableName($this->tableName_master, array($rek));
        // $this->db->select("*");
        // $this->db->where(array("extern_id" => $externID));
        if (isset($this->sortBy)) {
            $this->db->order_by($this->sortBy['kolom'], $this->sortBy['mode']);
        }
        else {
            $this->db->order_by("id", "asc");
        }
        $all_columns = $this->db->list_fields($tableNames[$rek]['mutasi']);
        $blackList_column = array(
            "cabang_nama",
            "gudang_nama",
            "r_move",
        );
        $columns = array_diff($all_columns, $blackList_column);
        $this->db->select($columns);

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

    public function callMovementProduk($rek)
    {
        if (isset($this->jenisTr) && is_array($this->jenisTr)) {
            $this->db->where_in("jenis", $this->jenisTr);
        }
        else {
            $condites = array(
                "jenis" => $this->jenisTr,
            );
            $this->db->where($condites);
        }

        // if(isset($this->sortBy)){
        //     $this->db->order_by($this->sortBy['kolom'], $this->sortBy['mode']);
        // }
        $srcPersediaans = $this->fetchMovement($rek);
        $produkIds = array();
        $transaksiIds = array();
        foreach ($srcPersediaans as $src) {
            // $produkIds[] = $src->extern_id;
            // $transaksiIds[] = $src->transaksi_id;
            $produkIds[$src->extern_id] = $src->extern_id;
            $transaksiIds[$src->transaksi_id] = $src->transaksi_id;
        }
        // showLast_query("biru");
        // arrPrintPink($srcPersediaans);
        // arrPrintKuning($transaksiIds);
        // matiHere(__LINE__);
        /* -----------------------------------------------------------
        * produk spek
        * -----------------------------------------------------------*/
        // $produkIds = "";
        // $transaksiIds = "";
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $prSpeks = $pr->callSpecs($produkIds);
        // showLast_query("kuning");
        // arrPrint($prSpeks);

        /* -----------------------------------------------------------
         * produk transaksi
         * -----------------------------------------------------------*/
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        // $trSpeks = $tr->callSpecs($transaksiIds);
        // arrPrintKuning($trSpeks);
        /* ------------------------------------------------------------------------------------------
         * spek produk harus ditambahin data dari registri juga, kalau hanya dari persediaan tidak bisa
         * mendapatkan harga jual
         * ------------------------------------------------------------------------------------------*/
        $srcs = $tr->lookupTransaksiDataRegistries($transaksiIds)->result();
        // showLast_query("kuning");
        // $trSpeks = array();
        foreach ($srcs as $src) {
            // arrPrintPink($src);
            $trSpeks0[$src->id] = $src;
        }

        $trSpeks = array();
        $dataBaru_1 = array();
        foreach ($srcs as $src) {
            $trId = $src->id;
            $items = blobDecode($src->items);
            $mains = blobDecode($src->main);
            $newMains = isset($mains) ? addPrefixKeyM_he_format($mains) : array();
            $newMains = sizeof($mains) > 1 ? addPrefixKeyM_he_format($mains) : array();
            // arrPrintKuning($mains);
            // arrPrintWebs($newMains);

            foreach ($items as $produk_id => $item) {
                // arrPrintKuning($item);
                $jenis = $trSpeks0[$trId]->jenis;
                $mata_uang = isset($newMains['m_currencyDetails__nama']) ? $newMains['m_currencyDetails__nama'] : "IDR";
                $mata_uang_kurs = isset($newMains['m_currencyDetails__exchange']) ? $newMains['m_currencyDetails__exchange'] : 1;
                // cekKuning($jenis);
                $mataUangs['mata_uang'] = $mata_uang;
                $mataUangs['mata_uang_kurs'] = $mata_uang_kurs;
                $newItem = addPrefixKeyI_he_format($item);
                $dataBaru_1 = $newItem + (array)$trSpeks0[$trId] + $newMains + $mataUangs;
                // $dataBaru_1 = $item;
                // $dataBaru = $item + (array)$trSpeks[$trId];
                // $masterData0[] = $dataBaru;

                $trSpeks[$trId][$produk_id] = $dataBaru_1;
                // cekBiru($item);
            }
            // cekOrange("$trId");
            // arrPrintKuning($mains);
            // cekBiru($items);
        }
        // --------------------------------------------------------------------------------

        // matiHere(__LINE__);
        foreach ($srcPersediaans as $srcPersediaan) {
            $prId = $srcPersediaan->extern_id;
            $trId = $srcPersediaan->transaksi_id;

            // $dataBaru = (array)$srcPersediaan + (array)$prSpeks[$prId] + (array)$trSpeks[$trId];
            $dataBaru = (array)$srcPersediaan + (array)(isset($prSpeks[$prId]) ? $prSpeks[$prId] : array()) + (array)(isset($trSpeks[$trId][$prId]) ? $trSpeks[$trId][$prId] : array());
            $masterData[] = $dataBaru;
        }

        $vars = array();
        $vars['data'] = $masterData;
        $vars['data_jml']['total'] = sizeof($srcPersediaans);
        $vars['data_jml']['produk'] = sizeof($produkIds);
        $vars['data_jml']['transaksi'] = sizeof($transaksiIds);


        return $vars;
    }

    public function callMovementProduk__ori($rek)
    {
        if (isset($this->jenisTr) && is_array($this->jenisTr)) {
            $this->db->where_in("jenis", $this->jenisTr);
        }
        else {
            $condites = array(
                "jenis" => $this->jenisTr,
            );
            $this->db->where($condites);
        }

        // if(isset($this->sortBy)){
        //     $this->db->order_by($this->sortBy['kolom'], $this->sortBy['mode']);
        // }
        $srcPersediaans = $this->fetchMovement($rek);
        $produkIds = array();
        $transaksiIds = array();
        foreach ($srcPersediaans as $src) {
            $produkIds[] = $src->extern_id;
            $transaksiIds[] = $src->transaksi_id;
        }
        // showLast_query("biru");
        // arrPrintPink($srcPersediaans);
        // arrPrintKuning($transaksiIds);
        $produkIds = "";
        // $transaksiIds = "";
        /* -----------------------------------------------------------
        * produk spek
        * -----------------------------------------------------------*/
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $prSpeks = $pr->callSpecs($produkIds);
        // arrPrint($prSpeks);

        /* -----------------------------------------------------------
         * produk transaksi
         * -----------------------------------------------------------*/
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        // $trSpeks = $tr->callSpecs($transaksiIds);
        // arrPrintKuning($trSpeks);
        /* ------------------------------------------------------------------------------------------
         * spek produk harus ditambahin data dari registri juga, kalau hanya dari persediaan tidak bisa
         * mendapatkan harga jual
         * ------------------------------------------------------------------------------------------*/
        $srcs = $tr->lookupTransaksiDataRegistries($transaksiIds)->result();
        // showLast_query("kuning");
        // $trSpeks = array();
        foreach ($srcs as $src) {
            $trSpeks0[$src->id] = $src;
        }
        foreach ($srcs as $src) {
            $trId = $src->id;
            $items = blobDecode($src->items);
            $mains = blobDecode($src->main);

            foreach ($items as $produk_id => $item) {

                $dataBaru = $item + (array)$trSpeks0[$trId];
                // $dataBaru = $item;
                // $dataBaru = $item + (array)$trSpeks[$trId];
                // $masterData0[] = $dataBaru;

                $trSpeks[$trId][$produk_id] = $dataBaru;
            }
            // cekOrange("$trId");
            // cekBiru($items);
        }
        // cekMerah("items ".sizeof($trSpeks) . "persediaan " . sizeof($srcPersediaans));
        // cekBiru($masterData0);
        // --------------------------------------------------------------------------------

        // arrPrintWebs($trSpeks);
        // foreach ($trSpeks as $trSpek) {
        //     $id_his = $trSpek->ids_his;
        //
        //     cekBiru(blobDecode($id_his));
        // }

        foreach ($srcPersediaans as $srcPersediaan) {
            $prId = $srcPersediaan->extern_id;
            $trId = $srcPersediaan->transaksi_id;

            $dataBaru = (array)$srcPersediaan + (array)(isset($prSpeks[$prId]) ? $prSpeks[$prId] : array()) + (array)$trSpeks[$trId][$prId];
            $masterData[] = $dataBaru;

            $arrProduk[] = "$prId - $trId";
        }

        // cek
        // arrPrint($trSpeks);
        // arrPrintWebs($arrProduk);

        $vars = array();
        $vars['data'] = $masterData;
        $vars['data_jml'] = sizeof($masterData);


        return $vars;
    }

    /* =====================================================================
* untuk memadukan mutasi tansa transaksi onal, diexecusi dr controler CLI::insertMutasiProduk
* == seharusnya sebelum insert harus bisa deteksi sudah ada atau belum transaksi pada bulan tersebut
* =====================================================================*/
    public function fetchLastMoves()
    {
        $this->load->helper("he_mass_table");

        // $rek = "persediaan produk";
        $rek = "1010030030";
        $tableNames = heReturnTableName($this->tableName_master, array($rek))[$rek]['mutasi'];
        // arrPrint($tableNames);
        $vars = $this->db->get($tableNames);

        return $vars;
    }

    /* -----------------------------------------------------------------------------------------
     * mendapatkan posisi stok terakhir dr tanggal yg ditentukan
     * -----------------------------------------------------------------------------------------*/
    public function fetchLastMovemenPersediaan($date_stop)
    {
        $this->load->helper("he_mass_table");

        // $rek = "persediaan produk";
        $rek = "1010030030";
        $tableNames = heReturnTableName($this->tableName_master, array($rek));
        $tableName = $tableNames[$rek]['mutasi'];

        $condites = array(
            "date(dtime)<=" => $date_stop,
        );
        $this->db->where($condites);
        $this->db->order_by("extern_id,id", "asc");
        $sources = $this->db->get($tableName)->result();

        $stokCabangs = array();
        foreach ($sources as $source) {
            $extern_id = $source->extern_id;
            $cabang_id = $source->cabang_id;
            // $qty_debet_akhir = $source->qty_debet_akhir;

            // $vars[$cabang_id][$extern_id]['qty_debet_akhir'] = $qty_debet_akhir;
            $stokCabangs[$cabang_id][$extern_id] = (array)$source;
        }

        return $stokCabangs;
    }

    /* ----------------------------------------------------------------------------------------------------------
     * status persediaan
     * ----------------------------------------------------------------------------------------------------------*/
    private function populateGlobalStok($sources)
    {
        /* ------------------------------------------------------------------------------------
          * mengambil transaksi terakhir perproduk dari tiap cabang
          * ------------------------------------------------------------------------------------*/
        $stokCabangs = array();
        foreach ($sources as $source) {
            $extern_id = $source->extern_id;
            $cabang_id = $source->cabang_id;
            $qty_debet_akhir = $source->qty_debet_akhir;

            // $vars[$cabang_id][$extern_id]['qty_debet_akhir'] = $qty_debet_akhir;
            $stokCabangs[$cabang_id][$extern_id] = (array)$source;
        }

        /* ------------------------------------------------------------------------------------
         * ngakumulasi data perproduk dr tiap cabnag
         * ------------------------------------------------------------------------------------*/
        foreach ($stokCabangs as $stokCabang_0) {
            foreach ($stokCabang_0 as $extern_id_00 => $items) {
                // arrPrintHijau($items);
                $qty_debet_akhir_00 = $items['qty_debet_akhir'];

                if (!isset($stokTotals[$extern_id_00]['qty_debet_akhir_global'])) {
                    $stokTotals[$extern_id_00]['qty_debet_akhir_global'] = 0;
                }
                $stokTotals[$extern_id_00]['qty_debet_akhir_global'] += $qty_debet_akhir_00;

                $lastTransaksies[$extern_id_00] = $items;
            }
        }

        /* ------------------------------------------------------------------------------------
         * pengabungan array
         * ------------------------------------------------------------------------------------*/
        foreach ($lastTransaksies as $extern_id_01 => $lastTransaksy) {
            $vars[$extern_id_01] = $lastTransaksy + $stokTotals[$extern_id_01];
        }

        // cekKuning(sizeof($stokTotals));
        // arrPrintKuning($stokTotals);
        // cekKuning(sizeof($vars));
        // arrPrintKuning($vars);
        // matiHere(__LINE__);
        return $vars;
    }

    public function callGlobalStokAwal($date_now = "")
    {
        if ($date_now == "") {
            $date_now = dtimeNow('Y-m-d');
        }
        $this->load->helper("he_mass_table");

        // $rek = "persediaan produk";
        $rek = "1010030030";
        $tableNames = heReturnTableName($this->tableName_master, array($rek))[$rek]['mutasi'];
        // arrPrint($tableNames);
        $condites = array(
            "date(dtime)<" => $date_now,
            // "cabang_id"    => "-1",
        );
        $this->db->where($condites);
        $this->db->order_by("extern_id,id", "asc");
        $sources = $this->db->get($tableNames)->result();
        // showLast_query("kuning");
        // arrPrintHijau($vars);

        // /* ------------------------------------------------------------------------------------
        //  * mengambil transaksi terakhir perproduk dari tiap cabang
        //  * ------------------------------------------------------------------------------------*/
        // $stokCabangs = array();
        // foreach ($sources as $source) {
        //     $extern_id = $source->extern_id;
        //     $cabang_id = $source->cabang_id;
        //     $qty_debet_akhir = $source->qty_debet_akhir;
        //
        //     // $vars[$cabang_id][$extern_id]['qty_debet_akhir'] = $qty_debet_akhir;
        //     $stokCabangs[$cabang_id][$extern_id] = (array)$source;
        // }
        //
        // /* ------------------------------------------------------------------------------------
        //  * ngakumulasi data perproduk dr tiap cabnag
        //  * ------------------------------------------------------------------------------------*/
        // foreach ($stokCabangs as $stokCabang_0) {
        //     foreach ($stokCabang_0 as $extern_id_00 => $items) {
        //         // arrPrintHijau($items);
        //         $qty_debet_akhir_00 = $items['qty_debet_akhir'];
        //
        //         if (!isset($stokTotals[$extern_id_00]['qty_debet_akhir_global'])) {
        //             $stokTotals[$extern_id_00]['qty_debet_akhir_global'] = 0;
        //         }
        //         $stokTotals[$extern_id_00]['qty_debet_akhir_global'] += $qty_debet_akhir_00;
        //
        //         $lastTransaksies[$extern_id_00] = $items;
        //     }
        // }
        //
        // /* ------------------------------------------------------------------------------------
        //  * pengabungan array
        //  * ------------------------------------------------------------------------------------*/
        // foreach ($lastTransaksies as $extern_id_01 => $lastTransaksy) {
        //     $vars[$extern_id_01] = $lastTransaksy + $stokTotals[$extern_id_01];
        // }
        //
        // // cekKuning(sizeof($stokTotals));
        // // arrPrintKuning($stokTotals);
        // // cekKuning(sizeof($vars));
        // // arrPrintKuning($vars);
        // // matiHere(__LINE__);
        // return $vars;

        $arrayData = $this->populateGlobalStok($sources);

        return $arrayData;
    }

    public function callGlobalStokMovemen($date_start = "", $date_stop = "")
    {

        $date_stop = ($date_stop == "") ? dtimeNow('Y-m-d') : $date_stop;
        $date_start = ($date_start == "") ? dtimeNow('Y-m') . '-01' : $date_start;

        /* -----------------------------------------------------------------
         * stok awal
         * -----------------------------------------------------------------*/
        $sourceAwal = $this->callGlobalStokAwal($date_start);
        // showLast_query("kuning");
        // cekKuning(sizeof($sourceAwal));
        /* ------------------------------------------------------------------
         * stok NOW (akhir)
         * ------------------------------------------------------------------*/
        // $sourceAwal_2 = $this->callGlobalStokAwal($date_stop);
        // showLast_query('pink');
        $this->load->helper("he_mass_table");

        // $rek = "persediaan produk";
        $rek = "1010030030";
        $tableNames = heReturnTableName($this->tableName_master, array($rek));
        $tableName = $tableNames[$rek]['mutasi'];

        $condites = array(
            "date(dtime)<=" => $date_stop,
        );
        $this->db->where($condites);
        $this->db->order_by("extern_id,id", "asc");
        $sources = $this->db->get($tableName)->result();

        $sourceAkhir = $arrayData = sizeof($sources) > 0 ? $this->populateGlobalStok($sources) : $sources;
        // showLast_query("orange");
        // cekOrange(sizeof($arrayData));
        /* -----------------------------------------------------------------
         * penyusun data stok akhir
         * -----------------------------------------------------------------*/
        // foreach ($sourceAwal as $produk_id => $dtAwal) {
        //     $sourceAkhir[$produk_id] = $dtAwal;
        // }
        // foreach ($arrayData as $produk_id => $varNow) {
        //     $sourceAkhir[$produk_id] = $varNow;
        // }
        // arrPrintPink($sourceAwal_2);
        // arrPrintPink($varNows);
        // arrPrintPink($sourceAkhir);
        // --------------------------------------------------------------------

        //
        /* --------------------------------------------------------------------
         * stok masuk dan keluar MOVEMENT dan penjualan
         * --------------------------------------------------------------------*/
        // $rekenings = array("582spd");
        // $this->db->where_in("jenis", $rekenings);
        $condites_move = array(
            "date(dtime)>=" => $date_start,
            "date(dtime)<=" => $date_stop,
        );
        $this->db->where($condites_move);
        // $moveDatas = $this->fetchMovement("persediaan_produk");
        $moveDatas = $this->fetchMovement("1010030030");
        // showLast_query("lime");
        /* -----------------------------------------------------------------------------------------
         * mendapatkan transaksi yg mengkosongkan stok
         * -----------------------------------------------------------------------------------------*/
        $sourceKosong = sizeof($moveDatas) > 0 ? $this->populateGlobalStok($moveDatas) : $moveDatas;
        // arrPrintPink($sourceKosong);
        $stokEnols = array();
        foreach ($sourceKosong as $ext_id => $itemKosong) {
            $qty_debet_akhir_global = $itemKosong['qty_debet_akhir_global'];
            if ($qty_debet_akhir_global == 0) {
                $stokEnols[$ext_id] = $itemKosong;
            }

        }

        //-----------------
        $jenisTr_masuk = array(
            "467", "460", "461"//,"985","1985"
        );
        $jenisTr_keluar = array(
            "582spd", "382spd"
        );
        $jenisTr_opname = array(
            "1119", "2229", "3339", "5559"
        );
        //-----------------

        $stokIns = array();
        $stokOts = array();
        $stokDijual = array();
        $stokDijualReturn = array();
        $stokOpnamePlus = array();
        $stokOpnameMinus = array();
        $stokReturnPurchase = array();
        $transaksiSpd = array();
        foreach ($moveDatas as $moveData) {
            $ext_id = $moveData->extern_id;
            $qty_debet = $moveData->qty_debet;
            $qty_kredit = $moveData->qty_kredit;
            $qty_debet_akhir = $moveData->qty_debet_akhir;
            $debet = $moveData->debet;
            $kredit = $moveData->kredit;
            $jenis_tr = $moveData->jenis;
            $transaksi_id = $moveData->transaksi_id;
            $dtime = $moveData->dtime;
            $cabang_id = $moveData->cabang_id;

            if (!isset($stokIns[$ext_id]['qty_debet_global'])) {
                $stokIns[$ext_id]['qty_debet_global'] = 0;
            }
            if (in_array($jenis_tr, $jenisTr_masuk)) {
                $stokIns[$ext_id]['qty_debet_global'] += $qty_debet;
            }

            if (!isset($stokOts[$ext_id]['qty_kredit_global'])) {
                $stokOts[$ext_id]['qty_kredit_global'] = 0;
            }
            if (in_array($jenis_tr, $jenisTr_keluar)) {
                $stokOts[$ext_id]['qty_kredit_global'] += $qty_kredit;
            }

            if (!isset($stokOpnamePlus[$ext_id]['qty_opname_debet'])) {
                $stokOpnamePlus[$ext_id]['qty_opname_debet'] = 0;
            }
            if (!isset($stokOpnameMinus[$ext_id]['qty_opname_kredit'])) {
                $stokOpnameMinus[$ext_id]['qty_opname_kredit'] = 0;
            }
            if (in_array($jenis_tr, $jenisTr_opname)) {
                $stokOpnamePlus[$ext_id]['qty_opname_debet'] += $qty_debet;
                $stokOpnameMinus[$ext_id]['qty_opname_kredit'] += $qty_kredit;
            }


            /* -----------------------------------------------------------------------------------------
             * handlink khusus data penjualan
             * -----------------------------------------------------------------------------------------*/
            if (($jenis_tr == "582spd") || ($jenis_tr == "382spd")) {
                $transaksiSpd[$ext_id][$transaksi_id] = $transaksi_id;
                $dtimeSpd[$ext_id] = $dtime;

                if (!isset($stokDijual[$ext_id]['qty_penjualan_global'])) {
                    $stokDijual[$ext_id]['qty_penjualan_global'] = 0;
                }
                $stokDijual[$ext_id]['qty_penjualan_global'] += $qty_kredit;

                if (!isset($stokDijual[$ext_id]['penjualan_global'])) {
                    $stokDijual[$ext_id]['penjualan_global'] = 0;
                }
                $stokDijual[$ext_id]['penjualan_global'] += $kredit;
            }
            if ($jenis_tr == "982") {
                //
                //                $transaksiSpd[$ext_id][$transaksi_id] = $transaksi_id;
                //                $dtimeSpd[$ext_id] = $dtime;
                //
                if (!isset($stokDijualReturn[$ext_id]['qty_return_penjualan_global'])) {
                    $stokDijualReturn[$ext_id]['qty_return_penjualan_global'] = 0;
                }
                $stokDijualReturn[$ext_id]['qty_return_penjualan_global'] += $qty_debet;

                if (!isset($stokDijualReturn[$ext_id]['return_penjualan_global'])) {
                    $stokDijualReturn[$ext_id]['return_penjualan_global'] = 0;
                }
                $stokDijualReturn[$ext_id]['return_penjualan_global'] += $debet;
            }
            //-------------------------------------------------------------------------------------
            if (($jenis_tr == "967") || ($jenis_tr == "960")) {

                //                $transaksiSpd[$ext_id][$transaksi_id] = $transaksi_id;
                //                $dtimeSpd[$ext_id] = $dtime;

                if (!isset($stokReturnPurchase[$ext_id]['qty_return_purchase_global'])) {
                    $stokReturnPurchase[$ext_id]['qty_return_purchase_global'] = 0;
                }
                $stokReturnPurchase[$ext_id]['qty_return_purchase_global'] += $qty_kredit;

                if (!isset($stokReturnPurchase[$ext_id]['return_purchase_global'])) {
                    $stokReturnPurchase[$ext_id]['return_purchase_global'] = 0;
                }
                $stokReturnPurchase[$ext_id]['return_purchase_global'] += $kredit;
            }
            //-------------------------------------------------------------------------------------
        }


        // arrPrintKuning($stokEnols);
        // arrPrintKuning($transaksiSpd);
        foreach ($transaksiSpd as $prodId => $item) {
            $stokDijual[$prodId]['jml_nota'] = sizeof($item);
            $stokDijual[$prodId]['dtime_spd'] = $dtimeSpd[$prodId];
        }

        // matiHere(__LINE__);
        $vars = array();
        $vars['awal'] = $sourceAwal;
        $vars['masuk'] = $stokIns;
        $vars['keluar'] = $stokOts;
        $vars['akhir'] = $sourceAkhir;
        $vars['kosong'] = $stokEnols;
        $vars['penjualan'] = $stokDijual;
        $vars['return_penjualan'] = $stokDijualReturn;
        $vars['opname_plus'] = $stokOpnamePlus;
        $vars['opname_minus'] = $stokOpnameMinus;
        $vars['return_purchase'] = $stokReturnPurchase;

        return $vars;
    }

    // ------------------------------------------------------------------------------------------

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


    public function fetchBalancesByPeriode($rek, $key = "", $sortBy = "", $sortMode = "ASC", $periode, $dateSelect)
    {
        //==memanggil saldo2 dari rekening tertentu
        $this->db->select("*");
        $this->db->where(
            array(
                "periode" => $periode,
                "rekening" => $rek,
                "date(dtime)<=" => $dateSelect
            )
        );
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
        cekkuning($this->db->last_query());

        $results = array();
        if (sizeof($result->result()) > 0) {
            foreach ($result->result() as $row) {
//                arrPrintWebs($row);
                $results[$row->extern_id] = array(
                    "id" => $row->extern_id,
                    "rek_id" => $row->rek_id,
                    "name" => $row->extern_nama,
                    "debet" => $row->debet,
                    "kredit" => $row->kredit,
                    "qty_debet" => $row->qty_debet,
                    "qty_kredit" => $row->qty_kredit,
                    "cabang_id" => $row->cabang_id,
                    "gudang_id" => $row->gudang_id,
                );
            }
        }
//        arrPrintWebs($results);
        $total_debet = 0;
        $total_kredit = 0;
        foreach ($results as $pid => $spec) {
            $total_debet += $spec["debet"];
            $total_kredit += $spec["kredit"];
        }
        cekMerah("==== debet: $total_debet, kredit: $total_kredit ====");

        mati_disini(__LINE__ . " :: " . sizeof($results));
        // yang direturn hasil dari tabel, apa adanya...
        return $result->result();

    }

    public function fetchMoves2_periode($rek, $dateSelect)
    {//==memanggil saldo2 dari rekening tertentu
        $tableNames = heReturnTableName($this->tableName_master, array($rek));
        $this->db->select("*");
        $this->db->where(
            array(
                "date(dtime)<=" => $dateSelect
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

        $result = $this->db->get($tableNames[$rek]['mutasi']);
//        cekkuning($this->db->last_query());

        $results = array();
        if (sizeof($result->result()) > 0) {
            foreach ($result->result() as $row) {
                $results[$row->extern_id] = array(
                    "id" => $row->extern_id,
                    "rek_id" => $row->rek_id,
                    "rekening" => $row->rekening,
                    "extern_id" => $row->extern_id,
                    "extern_nama" => $row->extern_nama,

                    "debet" => $row->debet,
                    "kredit" => $row->kredit,
                    "qty_debet" => $row->qty_debet,
                    "qty_kredit" => $row->qty_kredit,

                    "debet_akhir" => $row->debet_akhir,
                    "kredit_akhir" => $row->kredit_akhir,
                    "qty_debet_akhir" => $row->qty_debet_akhir,
                    "qty_kredit_akhir" => $row->qty_kredit_akhir,

                    "cabang_id" => $row->cabang_id,
                    "gudang_id" => $row->gudang_id,
                    "dtime" => $row->dtime,
                    "jenis" => $row->jenis,
                    "fulldate" => $row->fulldate,
                    "transaksi_id" => $row->transaksi_id,
                    "transaksi_no" => $row->transaksi_no,
                );
            }
        }
//        arrPrintWebs($results);
        $total_debet = 0;
        $total_kredit = 0;
        $total_debet_akhir = 0;
        $total_kredit_akhir = 0;
        $result_akhir = array();
        foreach ($results as $pid => $spec) {
            $total_debet += $spec["debet"];
            $total_kredit += $spec["kredit"];
            $total_debet_akhir += $spec["debet_akhir"];
            $total_kredit_akhir += $spec["kredit_akhir"];
//            if($spec["kredit_akhir"] > 0){
//                cekHitam("produkID: $pid");
//            }
            //----------------
            $debet = $spec["debet_akhir"];
            $kredit = $spec["kredit_akhir"];
            $qty_debet = $spec["qty_debet_akhir"];
            $qty_kredit = $spec["qty_kredit_akhir"];

            $debet_netto = $debet - $kredit;
            $qty_debet_netto = $qty_debet - $qty_kredit;

            $data = array(
                "id" => $spec["id"],
                "rek_id" => $spec["rek_id"],
                "rekening" => $spec["rekening"],
                "extern_id" => $spec["extern_id"],
                "extern_nama" => $spec["extern_nama"],

                "debet" => $debet_netto,
                "kredit" => 0,
                "qty_debet" => $qty_debet_netto,
                "qty_kredit" => 0,

                "cabang_id" => $spec["cabang_id"],
                "cabang_nama" => $spec["cabang_nama"],
                "gudang_id" => $spec["gudang_id"],
                "gudang_nama" => $spec["gudang_nama"],
                "dtime" => $spec["dtime"],
                "jenis" => $spec["jenis"],
                "fulldate" => $spec["fulldate"],
                "transaksi_id" => $spec["transaksi_id"],
                "transaksi_no" => $spec["transaksi_no"],
            );
            $result_akhir[$pid] = (object)$data;
        }
//        cekMerah("==== debet: $total_debet, kredit: $total_kredit ====");
//        cekBiru("==== debet: $total_debet_akhir, kredit: $total_kredit_akhir ====");
//        mati_disini(__LINE__ . " :: " . sizeof($results));

//arrPrintHijau($result_akhir);
//mati_disini(__LINE__);

//        return $result->result();
        return $result_akhir;
    }

}