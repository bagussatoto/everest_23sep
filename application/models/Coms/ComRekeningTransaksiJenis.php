<?php


class ComRekeningTransaksiJenis extends MdlMother
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
        "debet",
        "kredit",
        "qty_debet",
        "qty_kredit",
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
        "harga",
        "harga_avg",
        "harga_awal",
        "transaksi_id",
//        "master_id",
        "dtime_order",
        "dtime_kirim",
        "dtime_terima",
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
        "harga",
        "harga_avg",
        "harga_awal",
//        "master_id",
        //------
        "_stepCode_placeID",
        "_stepCode_olehID",
        "_stepCode_placeID_olehID",
        "_stepCode_placeID_olehID_customerID",
        "_stepCode_customerID",
        "_stepCode_placeID_customerID",
        "_stepCode_olehID_customerID",
        "_stepCode",
        "_stepCode_placeID_olehID_supplierID",
        "_stepCode_supplierID",
        "_stepCode_placeID_supplierID",
        "_stepCode_olehID_supplierID",
        "dtime_order",
        "dtime_kirim",
        "dtime_terima",
        "transaksi_tipe",
    );
    private $periode = array("harian", "bulanan", "tahunan", "forever");


    public function __construct()
    {

        $this->tableName = "z_rekening_transaksi_jenis_cache";
        $this->tableName_master = array(
            "mutasi" => "z_rekening_transaksi_jenis_mutasi",
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
        // arrPrint($inParams);
        // matiHEre();
//        $this->load->helper("he_mass_table");
//        $configBalanceProtections = $this->config->item("accountBalanceProtections");
        $this->inParams = $array_params = $inParams;

        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            foreach ($this->periode as $periode) {
//                foreach ($this->inParams as $array_params) {
                    $arrRekening = array();
                    foreach ($array_params['loop'] as $key => $x) {

                        $value_item = trim($array_params['static']['produk_nilai']);
                        $unit = trim($array_params['static']['produk_qty']);

                        $value = $x;
//                        $position = $value > 0 ? "debet":"kredit";
                        $position = $unit > 0 ? "debet" : "kredit";

                        $arrRekening[] = $key;
//                        $table = heReturnTableName($this->tableName_master, $arrRekening);
                        $this->tableName_mutasi = $this->tableName_master["mutasi"];


                        $_preValues = $this->cekPreValue(
                            $key,
                            $array_params['static']['cabang_id'],
                            $periode,
                            $array_params['static']['extern_id'],
                            $array_params['static']['gudang_id'],
                            $array_params['static']['fulldate'],
                            $array_params['static']['jenis']
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
                            $preNumber = $_preValues['cache']['debet'];
                        }
                        else {
                            $preNumber = $_preValues['cache']['kredit'] * -1;
                        }
                        if ($_preValues['cache']['qty_debet'] > 0) {
                            $preQtyNumber = $_preValues['cache']['qty_debet'];
                        }
                        else {
                            $preQtyNumber = ($_preValues['cache']['qty_kredit'] * -1);
                        }

                        $afterQtyNumber = $preQtyNumber + $unit;
                        $afterNumber = $preNumber + $value;
//                        $afterPosition = detectRekPosition($key, $afterNumber);
//                        $afterQtyPosition = detectRekPosition($key, $afterQtyNumber);
                        $afterPosition = $afterNumber > 0 ? "debet" : "kredit";
                        $afterQtyPosition = $afterQtyNumber > 0 ? "debet" : "kredit";
//                        $afterNumberAvg = $afterQtyNumber == 0 ? 0 : $afterNumber / $afterQtyNumber;

                        cekhitam(":: $afterNumber, val: $value,  $afterPosition, afterQty: $afterQtyNumber");
//                        if (in_array($key, $configBalanceProtections)) {
//                            if ($afterPosition != detectRekDefaultPosition($key) && ($afterQtyNumber != 0)) {
//                                $pName = $array_params['static']['extern_name'];
//                                cekMerah("insufficient balance for $key. You requested $value. Available: $preNumber");
//                                die(lgShowAlert("insufficient balance for $key, $pName. You requested $value. Available: $preNumber"));
//                            }
//                        }

                        //  region cache rekening pembantu
                        $pakai_cache = 1;
                        if ($pakai_cache == 1) {
                            switch ($afterPosition) {
                                case "kredit":
                                    $this->outParams[$lCounter]["cache"][$mode]["kredit"] = abs($afterNumber);
                                    $this->outParams[$lCounter]["cache"][$mode]["debet"] = 0;
                                    break;
                                case "debet":
                                    $this->outParams[$lCounter]["cache"][$mode]["kredit"] = 0;
                                    $this->outParams[$lCounter]["cache"][$mode]["debet"] = abs($afterNumber);
                                    break;
                                default:
                                    die(lgShowAlert(__LINE__ . " gagal menentukan posisi rekening DEBET / KREDIT " . __FUNCTION__ . " on file " . __FILE__));
                                    break;
                            }
                            switch ($afterQtyPosition) {
                                case "kredit":
                                    $this->outParams[$lCounter]["cache"][$mode]["qty_kredit"] = abs($afterQtyNumber);
                                    $this->outParams[$lCounter]["cache"][$mode]["qty_debet"] = 0;
                                    break;
                                case "debet":
                                    $this->outParams[$lCounter]["cache"][$mode]["qty_kredit"] = 0;
                                    $this->outParams[$lCounter]["cache"][$mode]["qty_debet"] = abs($afterQtyNumber);
                                    break;
                                default:
                                    die(lgShowAlert(__LINE__ . " gagal menentukan posisi rekening DEBET / KREDIT " . __FUNCTION__ . " on file " . __FILE__));
                                    break;
                            }

//                            $this->outParams[$lCounter]["cache"][$mode]["rek_id"] = createRekCode($key, $array_params['static']['extern_id']);
                            $this->outParams[$lCounter]["cache"][$mode]["rekening"] = $key;
                            $this->outParams[$lCounter]["cache"][$mode]["periode"] = $periode;
                            $this->outParams[$lCounter]["cache"][$mode]["id"] = $_preValues_id;
//                            $this->outParams[$lCounter]["cache"][$mode]["harga"] = $value_item;
//                            $this->outParams[$lCounter]["cache"][$mode]["harga_avg"] = abs($afterNumberAvg);
//                            $this->outParams[$lCounter]["cache"][$mode]["harga_awal"] = abs($_preValues['cache']['harga']);
//
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
                                            $this->outParams[$lCounter]["mutasi"]["kredit_awal"] = $_preValues["cache"]["kredit"];
                                            $this->outParams[$lCounter]["mutasi"]["kredit_akhir"] = abs($afterNumber);
                                            $this->outParams[$lCounter]["mutasi"]["debet_awal"] = $_preValues["cache"]["debet"];
                                            $this->outParams[$lCounter]["mutasi"]["debet_akhir"] = 0;
                                            break;
                                        case "debet":
                                            $this->outParams[$lCounter]["mutasi"]["kredit_awal"] = $_preValues["cache"]["kredit"];
                                            $this->outParams[$lCounter]["mutasi"]["kredit_akhir"] = 0;
                                            $this->outParams[$lCounter]["mutasi"]["debet_awal"] = $_preValues["cache"]["debet"];
                                            $this->outParams[$lCounter]["mutasi"]["debet_akhir"] = abs($afterNumber);
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
//                                    $this->outParams[$lCounter]["mutasi"]["rek_id"] = createRekCode($key, $array_params['static']['extern_id']);
                                    $this->outParams[$lCounter]["mutasi"]["rekening"] = $key;

//                                    $this->outParams[$lCounter]["mutasi"]["harga"] = abs($value_item);
//                                    $this->outParams[$lCounter]["mutasi"]["harga_avg"] = abs($afterNumberAvg);
//                                    $this->outParams[$lCounter]["mutasi"]["harga_awal"] = abs($_preValues['cache']['harga']);


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
//                }
            }
        }


        if (sizeof($insertIDs) > 0) {

            return true;
        }
        else {
            return false;
        }
    }

    private function cekPreValue($rek, $cabang_id, $periode, $produk_id, $gudang_id, $date = NULL, $jenisTr)
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
//        $this->addFilter("extern_id='$produk_id'");
        $this->addFilter("jenis='$jenisTr'");
//        $this->addFilter("master_id='$master_id'");
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
showLast_query("kuning");

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
//            $this->addFilter("extern_id='$produk_id'");
            $this->addFilter("jenis='$jenisTr'");
//            $this->addFilter("master_id='$master_id'");
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
showLast_query("kuning");

            if (sizeof($tmp) > 0) {
                foreach ($tmp as $row) {
                    $result["cache"] = array(
                        "debet" => $row->debet,
                        "kredit" => $row->kredit,
                        "qty_debet" => $row->qty_debet,
                        "qty_kredit" => $row->qty_kredit,
                        "harga" => $row->harga,
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


}