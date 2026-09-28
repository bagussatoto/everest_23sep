<?php


class ComRekeningTransaksiSalesReturnSalesmanTransaksi extends MdlMother
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
//        "cabang_id",
//        "cabang_nama",
//        "gudang_id",
//        "gudang_nama",
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
        "seller_id",
        "seller_nama",
        "jenis",
        "rekening_nama",
        "npwp",
        "fulldate",
//        "gudang_id",
//        "gudang_nama",
        "harga",
        "harga_avg",
        "harga_awal",
        "transaksi_id",
        "transaksi_no",
        "master_id",
        "master_jenis",
        "harga_bruto",
        "harga_netto",
        "harga_nppn",
        "diskon_nilai",
        "premi_nilai",
        "ongkir_nilai",
        "ppn_nilai",
        "master_jenis",
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
        "_step_1_nomer",
        "_step_1_olehName",
        "_step_2_nomer",
        "_step_2_olehName",
        "_step_3_nomer",
        "_step_3_olehName",
        "_step_4_nomer",
        "_step_4_olehName",
        "_step_5_nomer",
        "_step_5_olehName",
        "step_current",
        "step_number",
        "next_step_num",
        "rel_target_jenis",
        "supplier_id",
        "supplier_nama",
        "dtime_order",
        "dtime_kirim",
        "dtime_terima",
        "dtime_edit",
        "dtime_reject",
        "oleh_id_edit",
        "oleh_nama_edit",
        "oleh_id_reject",
        "oleh_nama_reject",
        "customer_id",
        "customer_nama",
        "supplier_id",
        "supplier_nama",
        "oleh_id",
        "oleh_nama",
        "seller_id",
        "seller_nama",
        "step_reject",
    );
    private $koloms = array(
        "cabang_id",
        "produk_id",
        "nama",
        "jml",
        "hpp",
        "jml_nilai",
    );
    private $outFieldsMutasi = array( // dari tabel rek mutasi rekening
        "rekening",
        "rekening_nama",
        "transaksi_id",
        "transaksi_no",
        "transaksi_jenis",
//        "cabang_id",
//        "cabang_nama",
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
        "seller_id",
        "seller_nama",
        "jenis",
        "npwp",
        "fulldate",
//        "gudang_id",
//        "gudang_nama",
        "keterangan",
        "harga_bruto",
        "harga_netto",
        "harga_nppn",
        "diskon_nilai",
        "premi_nilai",
        "ppn",
        "ppn_nilai",
        "ongkir_nilai",
        "harga_avg",
        "harga_awal",
        "master_id",
        "master_jenis",
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
        "dtime_edit",
        "dtime_reject",
        "oleh_id_edit",
        "oleh_nama_edit",
        "oleh_id_reject",
        "oleh_nama_reject",
        "_step_1_nomer",
        "_step_1_olehName",
        "_step_2_nomer",
        "_step_2_olehName",
        "_step_3_nomer",
        "_step_3_olehName",
        "_step_4_nomer",
        "_step_4_olehName",
        "_step_5_nomer",
        "_step_5_olehName",
        "transaksi_tipe",
        "customer_id",
        "customer_nama",
        "supplier_id",
        "supplier_nama",
        "oleh_id",
        "oleh_nama",
        "seller_id",
        "seller_nama",
        "step_reject",
    );
    private $periode = array("harian", "bulanan", "tahunan", "forever");


    public function __construct()
    {
        // TOTAL KONSOLIDASIAN
        $this->tableName = "z_sales_return_salesman_transaksi_cache";
        $this->tableName_master = array(
            "mutasi" => "z_sales_return_salesman_transaksi_mutasi",
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
        $this->inParams = $array_params = $inParams;
        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            foreach ($this->periode as $periode) {
                $arrRekening = array();
                foreach ($array_params['loop'] as $key => $x) {

                    $value_item = trim($array_params['static']['produk_nilai']);
                    $unit = trim($array_params['static']['produk_qty']);

                    $value = $x;
                    $position = $value > 0 ? "debet" : "kredit";

                    $arrRekening[] = $key;
                    $this->tableName_mutasi = $this->tableName_master["mutasi"];

                    $sellerID = $array_params['static']['seller_id'];
                    $cabangID = $array_params['static']['cabang_id'];
                    $gudangID = $array_params['static']['gudang_id'];
                    $externID = $array_params['static']['extern_id'];
                    $transaksiID = $array_params['static']['transaksi_id'];
                    $fulldate = $array_params['static']['fulldate'];
                    $transaksiTipe = $array_params['static']['transaksi_tipe'];
                    $masterID = $array_params['static']['master_id'];
                    $transaksiStep = $array_params['static']['transaksi_step'];
                    $_preValues = $this->cekPreValue(
                        $key,
//                        $cabangID,
                        $periode,
                        $externID,
//                        $gudangID,
                        $fulldate,
                        $transaksiID,
                        $sellerID,
                        $masterID
                    );

                    if (array_key_exists("id", $_preValues["cache"]) && ($_preValues["cache"]["id"] > 0)) {
                        $mode = "update";
                        $_preValues_id = $_preValues["cache"]["id"];
                    }
                    else {
                        $date_ex = explode("-", $fulldate);
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

                    $afterPosition = $afterNumber > 0 ? "debet" : "kredit";
                    $afterQtyPosition = $afterQtyNumber > 0 ? "debet" : "kredit";

                    cekhitam(":: $afterNumber, val: $value,  $afterPosition, afterQty: $afterQtyNumber, prevQty: $preQtyNumber");


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
                        switch ($position) {
                            case "kredit":
                                $this->outParams[$lCounter]["cache"][$mode]["saldo_kredit"] = $_preValues['cache']['saldo_kredit'] + abs($value);
                                break;
                            case "debet":
                                $this->outParams[$lCounter]["cache"][$mode]["saldo_debet"] = $_preValues['cache']['saldo_debet'] + abs($value);
                                break;
                            default:
                                die(lgShowAlert(__LINE__ . " gagal menentukan posisi rekening DEBET / KREDIT " . __FUNCTION__ . " on file " . __FILE__));
                                break;
                        }

                        $this->outParams[$lCounter]["cache"][$mode]["rekening"] = $key;
                        $this->outParams[$lCounter]["cache"][$mode]["periode"] = $periode;
                        $this->outParams[$lCounter]["cache"][$mode]["id"] = $_preValues_id;

                        if ($transaksiTipe == "rejected") {
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_reject"] = $_preValues['cache']['saldo_reject'] + abs($value);
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_closed"] = $_preValues['cache']['saldo_closed'];
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_edit"] = $_preValues['cache']['saldo_edit'];
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_reject"] = $_preValues['cache']['saldo_qty_reject'] + abs($unit);
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_closed"] = $_preValues['cache']['saldo_qty_closed'];
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_edit"] = $_preValues['cache']['saldo_qty_edit'];
                        }
                        if ($transaksiTipe == "closed") {
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_closed"] = $_preValues['cache']['saldo_closed'] + abs($value);
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_edit"] = $_preValues['cache']['saldo_edit'];
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_reject"] = $_preValues['cache']['saldo_qty_reject'];
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_closed"] = $_preValues['cache']['saldo_qty_closed'] + abs($unit);
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_edit"] = $_preValues['cache']['saldo_qty_edit'];
                        }
                        if ($transaksiTipe == "edited") {
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_closed"] = $_preValues['cache']['saldo_closed'];
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_edit"] = $_preValues['cache']['saldo_edit'];
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_reject"] = $_preValues['cache']['saldo_qty_reject'];
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_closed"] = $_preValues['cache']['saldo_qty_closed'];
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_edit"] = $_preValues['cache']['saldo_qty_edit'];
                        }
                        if ($transaksiTipe == "batal") {
                            $this->outParams[$lCounter]["cache"][$mode]["batal"] = $_preValues['cache']['batal'] + abs($unit);
                            $this->outParams[$lCounter]["cache"][$mode]["batal_nilai"] = $_preValues['cache']['batal_nilai'] + abs($value);
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_closed"] = $_preValues['cache']['saldo_closed'];
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_edit"] = $_preValues['cache']['saldo_edit'];
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_reject"] = $_preValues['cache']['saldo_qty_reject'];
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_closed"] = $_preValues['cache']['saldo_qty_closed'];
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_edit"] = $_preValues['cache']['saldo_qty_edit'];
                        }

                        if ($transaksiStep == "order") {
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_order"] = $_preValues['cache']['saldo_order'] + abs($value);
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_order"] = $_preValues['cache']['saldo_qty_order'] + abs($unit);
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_kirim"] = $_preValues['cache']['saldo_kirim'];
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_kirim"] = $_preValues['cache']['saldo_qty_kirim'];
                        }
                        if ($transaksiStep == "kirim") {
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_order"] = $_preValues['cache']['saldo_order'];
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_order"] = $_preValues['cache']['saldo_qty_order'];
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_kirim"] = $_preValues['cache']['saldo_kirim'] + abs($value);
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_kirim"] = $_preValues['cache']['saldo_qty_kirim'] + abs($unit);
                        }

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
            }
        }


        if (sizeof($insertIDs) > 0) {

            return true;
        }
        else {
            return false;
        }
    }

    private function cekPreValue_OLD($rek, $cabang_id, $periode, $produk_id, $gudang_id, $date = NULL, $transaksi_id, $seller_id)
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
            showLast_query("biru");
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

//    private function cekPreValue($rek, $cabang_id, $periode, $produk_id, $gudang_id, $date = NULL, $transaksi_id, $seller_id)
    private function cekPreValue($rek, $periode, $produk_id, $date = NULL, $transaksi_id, $seller_id, $master_id)
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
//        $this->addFilter("cabang_id='$cabang_id'");
//        $this->addFilter("gudang_id='$gudang_id'");
        $this->addFilter("periode='$periode'");
        $this->addFilter("master_id='$master_id'");
        $this->addFilter("seller_id='$seller_id'");
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
                    //---------------
                    "batal" => $row->batal,
                    "batal_nilai" => $row->batal_nilai,
                    "rejected" => $row->rejected,
                    "closed" => $row->closed,
                    "qty_debet_lap" => $row->qty_debet_lap,
                    "qty_kredit_lap" => $row->qty_kredit_lap,
                    "debet_lap" => $row->debet_lap,
                    "kredit_lap" => $row->kredit_lap,
                    "saldo_debet" => $row->saldo_debet,
                    "saldo_kredit" => $row->saldo_kredit,
                    "saldo_qty_debet" => $row->saldo_qty_debet,
                    "saldo_qty_kredit" => $row->saldo_qty_kredit,
                    "saldo_reject" => $row->saldo_reject,
                    "saldo_closed" => $row->saldo_closed,
                    "saldo_qty_reject" => $row->saldo_qty_reject,
                    "saldo_qty_closed" => $row->saldo_qty_closed,
                    "saldo_edit" => $row->saldo_edit,
                    "saldo_qty_edit" => $row->saldo_qty_edit,
                    "saldo_order" => $row->saldo_order,
                    "saldo_kirim" => $row->saldo_kirim,
                    "saldo_qty_order" => $row->saldo_qty_order,
                    "saldo_qty_kirim" => $row->saldo_qty_kirim,
                );
            }
        }
        else {
            // bila count($tmp) == 0, maka ambil saldo periode forever dan mode insert
            $this->filters = array();
            $this->addFilter("rekening='$rek'");
//        $this->addFilter("cabang_id='$cabang_id'");
//        $this->addFilter("gudang_id='$gudang_id'");
            $this->addFilter("periode='forever'");
            $this->addFilter("master_id='$master_id'");
            $this->addFilter("seller_id='$seller_id'");
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
//                        "id" => $row->id,
                        "debet" => $row->debet,
                        "kredit" => $row->kredit,
                        "qty_debet" => $row->qty_debet,
                        "qty_kredit" => $row->qty_kredit,
                        "harga" => $row->harga,
                        //---------------
                        "batal" => $row->batal,
                        "batal_nilai" => $row->batal_nilai,
                        "rejected" => $row->rejected,
                        "closed" => $row->closed,
                        "qty_debet_lap" => $row->qty_debet_lap,
                        "qty_kredit_lap" => $row->qty_kredit_lap,
                        "debet_lap" => $row->debet_lap,
                        "kredit_lap" => $row->kredit_lap,
//                        "saldo_debet"=>$row->saldo_debet,
//                        "saldo_kredit"=>$row->saldo_kredit,
//                        "saldo_qty_debet"=>$row->saldo_qty_debet,
//                        "saldo_qty_kredit"=>$row->saldo_qty_kredit,
//                        "saldo_reject"=>$row->saldo_reject,
//                        "saldo_closed"=>$row->saldo_closed,
//                        "saldo_qty_reject"=>$row->saldo_qty_reject,
//                        "saldo_qty_closed"=>$row->saldo_qty_closed,
//                        "saldo_edit"=>$row->saldo_edit,
//                        "saldo_qty_edit"=>$row->saldo_qty_edit,
                        "saldo_debet" => 0,
                        "saldo_kredit" => 0,
                        "saldo_qty_debet" => 0,
                        "saldo_qty_kredit" => 0,
                        "saldo_reject" => 0,
                        "saldo_closed" => 0,
                        "saldo_qty_reject" => 0,
                        "saldo_qty_closed" => 0,
                        "saldo_edit" => 0,
                        "saldo_qty_edit" => 0,
                        "saldo_order" => 0,
                        "saldo_kirim" => 0,
                        "saldo_qty_order" => 0,
                        "saldo_qty_kirim" => 0,
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
                    //---------------
                    "batal" => 0,
                    "batal_nilai" => 0,
                    "rejected" => 0,
                    "closed" => 0,
                    "qty_debet_lap" => 0,
                    "qty_kredit_lap" => 0,
                    "debet_lap" => 0,
                    "kredit_lap" => 0,
                    "saldo_debet" => 0,
                    "saldo_kredit" => 0,
                    "saldo_qty_debet" => 0,
                    "saldo_qty_kredit" => 0,
                    "saldo_reject" => 0,
                    "saldo_closed" => 0,
                    "saldo_qty_reject" => 0,
                    "saldo_qty_closed" => 0,
                    "saldo_edit" => 0,
                    "saldo_qty_edit" => 0,
                    "saldo_order" => 0,
                    "saldo_kirim" => 0,
                    "saldo_qty_order" => 0,
                    "saldo_qty_kirim" => 0,
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


}