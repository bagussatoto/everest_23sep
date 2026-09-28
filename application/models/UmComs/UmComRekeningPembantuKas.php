<?php


class UmComRekeningPembantuKas extends MdlMother
{

    protected $filters = array();
    protected $tableName;
    private $tableName_mutasi;
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
        "tgl",
        "bln",
        "thn",
        "extern_id",
        "extern_nama",
        "jenis",
        "npwp",
        "fulldate",
//        "toko_id",
//        "toko_nama",
        "extern2_id",
        "extern2_nama",
        "extern3_id",
        "extern3_nama",
        "extern4_id",
        "extern4_nama",
        //        "pihak_id",
        //        "pihak_nama",
        //        "oleh_id",
        //        "oleh_nama",
        //        "oleh_top_id",
        //        "oleh_top_nama",
//        "produk_id",
//        "produk_nama",
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
        "keterangan",
//        "toko_id",
//        "toko_nama",
        "extern2_id",
        "extern2_nama",
        "extern3_id",
        "extern3_nama",
        "extern4_id",
        "extern4_nama",
        "pihak_id",
        "pihak_nama",
        "oleh_id",
        "oleh_nama",
        "oleh_top_id",
        "oleh_top_nama",
//        "produk_id",
//        "produk_nama",
    );
    private $periode = array("harian", "bulanan", "tahunan", "forever");
    protected $jenisTr;
    protected $tableInMaster;
    protected $main;
    protected $detail;
    private $addKolomData = array(
        "_company",
        "_company_jenisTr",
        "_company_jenisTrMaster",
        "_company_stepCode",
        "_company_supplierID",
        "_company_customerID",
        "_company_olehID",
        "_company_sellerID",
        "_company_cabangID",
        "_company_cabang2ID",
        "_company_gudangID",
        "_company_gudang2ID",
        "_company_modul",
        "_company_subModul",
        "_company_cabangID_jenisTr",
        "_company_cabangID_jenisTrMaster",
        "_company_cabangID_stepCode",
        "_company_cabangID_supplierID",
        "_company_cabangID_customerID",
        "_company_cabangID_olehID",
        "_company_cabangID_sellerID",
        "_company_cabangID_cabangID",
        "_company_cabangID_cabang2ID",
        "_company_cabangID_gudangID",
        "_company_cabangID_gudang2ID",
        "_company_cabangID_modul",
        "_company_cabangID_subModul",
        "_company_cabangID_modul_jenisTr",
        "_company_cabangID_modul_jenisTrMaster",
        "_company_cabangID_modul_stepCode",
        "_company_cabangID_modul_supplierID",
        "_company_cabangID_modul_customerID",
        "_company_cabangID_modul_olehID",
        "_company_cabangID_modul_sellerID",
        "_company_cabangID_modul_cabangID",
        "_company_cabangID_modul_cabang2ID",
        "_company_cabangID_modul_gudangID",
        "_company_cabangID_modul_gudang2ID",
        "_company_cabangID_modul_subModul",
        "_company_cabangID_modul_subModul_jenisTr",
        "_company_cabangID_modul_subModul_jenisTrMaster",
        "_company_cabangID_modul_subModul_stepCode",
        "_company_cabangID_modul_subModul_supplierID",
        "_company_cabangID_modul_subModul_customerID",
        "_company_cabangID_modul_subModul_olehID",
        "_company_cabangID_modul_subModul_sellerID",
        "_company_cabangID_modul_subModul_cabangID",
        "_company_cabangID_modul_subModul_cabang2ID",
        "_company_cabangID_modul_subModul_gudangID",
        "_company_cabangID_modul_subModul_gudang2ID",
        "_company_cabangID_modul_subModul_jenisTr_jenisTrMaster",
        "_company_cabangID_modul_subModul_jenisTr_stepCode",
        "_company_cabangID_modul_subModul_jenisTr_supplierID",
        "_company_cabangID_modul_subModul_jenisTr_customerID",
        "_company_cabangID_modul_subModul_jenisTr_olehID",
        "_company_cabangID_modul_subModul_jenisTr_sellerID",
        "_company_cabangID_modul_subModul_jenisTr_cabangID",
        "_company_cabangID_modul_subModul_jenisTr_cabang2ID",
        "_company_cabangID_modul_subModul_jenisTr_gudangID",
        "_company_cabangID_modul_subModul_jenisTr_gudang2ID",
        "_company_cabangID_modul_subModul_jenisTr_stepCode_jenisTrMaster",
        "_company_cabangID_modul_subModul_jenisTr_stepCode_supplierID",
        "_company_cabangID_modul_subModul_jenisTr_stepCode_customerID",
        "_company_cabangID_modul_subModul_jenisTr_stepCode_olehID",
        "_company_cabangID_modul_subModul_jenisTr_stepCode_sellerID",
        "_company_cabangID_modul_subModul_jenisTr_stepCode_cabangID",
        "_company_cabangID_modul_subModul_jenisTr_stepCode_cabang2ID",
        "_company_cabangID_modul_subModul_jenisTr_stepCode_gudangID",
        "_company_cabangID_modul_subModul_jenisTr_stepCode_gudang2ID",
    );

    public function getJenisTr()
    {
        return $this->jenisTr;
    }

    public function setJenisTr($jenisTr)
    {
        $this->jenisTr = $jenisTr;
    }

    public function getTableInMaster()
    {
        return $this->tableInMaster;
    }

    public function setTableInMaster($tableInMaster)
    {
        $this->tableInMaster = $tableInMaster;
    }

    public function getMain()
    {
        return $this->main;
    }

    public function setMain($main)
    {
        $this->main = $main;
    }

    public function getDetail()
    {
        return $this->detail;
    }

    public function setDetail($detail)
    {
        $this->detail = $detail;
    }


    public function __construct()
    {
        $this->tableName = "_rek_pembantu_subkas_cache";
        $this->tableName_master = array(
            "mutasi" => "_rek_pembantu_subkas",
            //            "cache" => "_rek_pembantu_subkas_cache",
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
        $this->load->helper("he_mass_table");
        $configBalanceProtections = $this->config->item("accountBalanceProtections");
        $this->inParams = $inParams;
        //arrPrint($this->inParams);
        /* ---------------------------------------------------------------------------------
         * [] khusus untuk develop, normnya jalan tanpa []
         * ---------------------------------------------------------------------------------*/
        // $this->inParams[] = $inParams;

        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            foreach ($this->periode as $periode) {
                foreach ($this->inParams as $array_params) {
                    // arrPrint($array_params);
                    // matiHEre();
                    $arrRekening = array();
                    foreach ($array_params['loop'] as $key => $value) {
                        $lCounter++;

                        $position = detectRekPosition($key, $value);

                        $arrRekening[] = $key;
                        $table = heReturnTableName($this->tableName_master, $arrRekening);
                        $this->tableName_mutasi = $table[$key]["mutasi"];


                        $externID = (isset($array_params['static']['extern_id']) && ($array_params['static']['extern_id'] > 0)) ? $array_params['static']['extern_id'] : die(lgShowAlert("unknown cashID*. Please contact system administrator."));

                        //  region cek saldo awal
                        $_preValues = $this->cekPreValue(
                            $key,
                            $array_params['static']['cabang_id'],
                            $periode,
//                            $array_params['static']['produk_id'],
                            $array_params['static']['toko_id'],
                            $array_params['static']['extern_id']
                        );
                        //  endregion cek saldo awal

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
                        //                    $afterNumber = round($preNumber + $value);
                        $afterNumber = $preNumber + $value;
                        $afterPosition = detectRekPosition($key, $afterNumber);


                        if (in_array($key, $configBalanceProtections)) {
                            if ($afterPosition != detectRekDefaultPosition($key) && ($afterNumber != 0)) {
                                die(lgShowAlert("insufficient balance for $key " . $array_params['static']['extern_nama'] . ""));
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
                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_kredit"] = $_preValues['cache']['saldo_kredit'] + abs($value);
                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_debet"] = $_preValues['cache']['saldo_kredit'];
                                    //                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_kredit"] = $_preValues['cache']['saldo_qty_kredit'] + abs($unit);
                                    //                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_debet"] = $_preValues['cache']['saldo_qty_kredit'];
                                    break;
                                case "debet":
                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_debet"] = $_preValues['cache']['saldo_debet'] + abs($value);
                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_kredit"] = $_preValues['cache']['saldo_debet'];
                                    //                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_debet"] = $_preValues['cache']['saldo_qty_debet'] + abs($unit);
                                    //                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_kredit"] = $_preValues['cache']['saldo_qty_debet'];
                                    break;
                                default:
                                    mati_disini(__LINE__ . " gagal menentukan posisi rekening DEBET / KREDIT " . __FUNCTION__ . " on file " . __FILE__);
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
                                            $this->outParams[$lCounter]["mutasi"]["saldo_debet"] = $_preValues['cache']['saldo_debet'] + abs($value);
                                            $this->outParams[$lCounter]["mutasi"]["saldo_kredit"] = $_preValues['cache']['saldo_kredit'];
                                            break;
                                        case "kredit":
                                            $this->outParams[$lCounter]["mutasi"]["kredit"] = abs($value);
                                            $this->outParams[$lCounter]["mutasi"]["debet"] = 0;
                                            $this->outParams[$lCounter]["mutasi"]["saldo_kredit"] = $_preValues['cache']['saldo_kredit'] + abs($value);
                                            $this->outParams[$lCounter]["mutasi"]["saldo_debet"] = $_preValues['cache']['saldo_debet'];
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
                                    //region -- counter basic rekening
//                                    $dataDetail = $this->detail[$array_params['static']['extern_id']];
//                                    $dataDetail["extern_id"] = $array_params['static']['extern_id'];
                                    //arrPrintKuning($dataDetail);
//                                    $ccn = new CounterNumber();
//                                    $ccn->setJenisTr($this->jenisTr);
//                                    $ccn->setTransaksiGate($this->tableInMaster);
//                                    $ccn->setMainGate($dataDetail);
//                                    $ccn->setRekening($key);
//                                    $new_counter = $ccn->getCounterNumberRekeningPembantu();
//                                    //                            arrPrintHijau($new_counter);
//                                    foreach ($new_counter["main"] as $ckey => $cval) {
//                                        $this->outParams[$lCounter]["mutasi"][$ckey] = $cval;
//                                    }
//                                    $addData = array(
//                                        "jenisTr",
//                                        "jenisTrMaster",
//                                        "stepCode",
//                                        "supplierID",
//                                        "customerID",
//                                        "olehID",
//                                        "sellerID",
//                                    );
//                                    foreach ($addData as $dval) {
//                                        $this->outParams[$lCounter]["mutasi"][$dval] = isset($dataDetail[$dval]) ? $dataDetail[$dval] : "0";
//                                    }
//                                    //endregion -- counter basic rekening

//                                    if (isset($this->addKolomData)) {
//                                        foreach ($this->addKolomData as $kolom) {
//                                            $this->outParams[$lCounter]["mutasi"][$kolom] = isset($this->tableInMaster[$kolom]) ? $this->tableInMaster[$kolom] : 0;
//                                        }
//                                        //                            arrPrintPink($this->outParams[$lCounter]["mutasi"]);
//                                    }

                                    break;
                            }
                        }
                        //  endregion mutasi rekening pembantu
                    }
                }
            }
        }


        if (sizeof($this->outParams) > 0) {
            return true;
        }
        else {
            echo "loop params are empty<br>";
            return false;
        }
    }

    private function cekPreValue($rek, $cabang_id, $periode, $toko_id, $extern_id)
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
        $this->addFilter("periode='$periode'");
        $this->addFilter("extern_id='$extern_id'");
//        $this->addFilter("produk_id='$produk_id'");
//        $this->addFilter("toko_id='$toko_id'");
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
                    "harga" => $row->harga,
                    "saldo_debet" => $row->saldo_debet,
                    "saldo_kredit" => $row->saldo_kredit,
                    "saldo_qty_debet" => $row->saldo_qty_debet,
                    "saldo_qty_kredit" => $row->saldo_qty_kredit,
                );
            }
        }
        else {
            // bila count($tmp) == 0, maka ambil saldo periode forever dan mode insert
            $this->filters = array();
            $this->addFilter("rekening='$rek'");
            $this->addFilter("cabang_id='$cabang_id'");
            $this->addFilter("periode='forever'");
//            $this->addFilter("produk_id='$produk_id'");
            $this->addFilter("extern_id='$extern_id'");
//            $this->addFilter("toko_id='$toko_id'");
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
                        //                        "id" => $row->id,
                        "debet" => $row->debet,
                        "kredit" => $row->kredit,
                        "qty_debet" => $row->qty_debet,
                        "qty_kredit" => $row->qty_kredit,
                        "harga" => $row->harga,
                        "saldo_debet" => $row->saldo_debet,
                        "saldo_kredit" => $row->saldo_kredit,
                        "saldo_qty_debet" => $row->saldo_qty_debet,
                        "saldo_qty_kredit" => $row->saldo_qty_kredit,
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
                    "saldo_debet" => 0,
                    "saldo_kredit" => 0,
                    "saldo_qty_debet" => 0,
                    "saldo_qty_kredit" => 0,
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

    }

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

    public function fetchBalances($rek, $key = "", $sortBy = "", $sortMode = "ASC", $extern_id)
    {//==memanggil saldo2 dari rekening tertentu
        //        $tableNames = heReturnTableName($this->tableName_master, array($rek));
        $this->db->select("*");
        $this->db->where(
            array(
                "extern_id" => "$extern_id",
                "periode" => "forever",
                "rekening" => $rek
            )
        );
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

    public function fetchMoves($rek, $externID, $produk_id)
    {//==memanggil saldo2 dari rekening tertentu
        $tableNames = heReturnTableName($this->tableName_master, array($rek));
        $this->db->select("*");
        $this->db->where(
            array(
                "rekening" => $rek,
                "produk_id" => $produk_id,
                "extern_id" => $externID
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


    public function fetchMovesByTransIDs($rek, $trIDs, $extern_id)
    {//==memanggil saldo2 dari rekening tertentu
        $tableNames = heReturnTableName($this->tableName_master, array($rek));
        $this->db->select("*");
        $this->db->where(
            array(
                "rekening" => $rek,
                "extern_id" => $extern_id
            )
        );
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

    public function fetchBalancePeriode($rek, $externID, $periode, $produk_id)
    {//==memanggil saldo2 dari rekening tertentu
        $tableNames = $this->tableName;
        $this->db->select("*");
        $this->db->where(
            array(
                "produk_id" => $produk_id,
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