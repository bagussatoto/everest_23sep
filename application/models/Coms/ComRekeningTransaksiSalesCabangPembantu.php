<?php


class ComRekeningTransaksiSalesCabangPembantu extends MdlMother
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
    private $outFields = array( // dari tabel cache-
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
        "extern2_id",
        "extern2_nama",
        "jenis",
        "npwp",
        "fulldate",
        "gudang_id",
        "gudang_nama",
        "harga",
        "harga_bruto",
        "harga_bruto_ori",
        "harga_avg",
        "harga_awal",
        "harga_netto",
        "harga_netto_ori",
        "harga_nppn",
        "ppn_nilai",
        "ppn_nilai_ori",
        "premi_nilai",
        "diskon_nilai",
        "premi_nilai_ori",
        "transaksi_id",
        "master_id",
        "produk_kode",
        "produk_part",
        "produk_label",
        "produk_jenis",
        "produk_satuan",
        "dtime_order",
        "dtime_kirim",
        "dtime_terima",
        "dtime_edit",
        "dtime_reject",
        "oleh_id_edit",
        "oleh_nama_edit",
        "oleh_id_reject",
        "oleh_nama_reject",
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
        "_step_1_nomer",
        "_step_1_olehName",
        "_step_2_olehName",
        "_step_2_nomer",
        "_step_3_nomer",
        "_step_3_olehName",
        "_step_4_nomer",
        "_step_4_olehName",
        "_step_5_nomer",
        "_step_5_olehName",
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
        "harga_bruto",
        "harga_bruto_ori",
        "harga_netto",
        "harga_netto_ori",
        "harga_nppn",
        "ppn_nilai",
        "ppn_nilai_ori",
        "premi_nilai",
        "diskon_nilai",
        "premi_nilai_ori",
        "harga_avg",
        "harga_awal",
        "master_id",
        "produk_kode",
        "produk_part",
        "produk_label",
        "produk_jenis",
        "produk_satuan",
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
    protected $jenisTr;
    protected $sortBy = array(
        "kolom" => "id",
        "mode" => "desc",
    );

    public function __construct()
    {

        $this->tableName = "z_sales_cabang_pembantu_cache";
        $this->tableName_master = array(
            "mutasi" => "z_sales_cabang_pembantu_mutasi",
        );
    }

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
                        $position = $unit > 0 ? "debet" : "kredit";

                        $arrRekening[] = $key;
                        $this->tableName_mutasi = $this->tableName_master["mutasi"];

                        $cabangID = $array_params['static']['cabang_id'];
                        $gudangID = $array_params['static']['gudang_id'];
                        $externID = $array_params['static']['extern_id'];
                        $transaksiID = $array_params['static']['transaksi_id'];
                        $fulldate = $array_params['static']['fulldate'];
                        $masterID = $array_params['static']['master_id'];
                        $extern2ID = $array_params['static']['extern2_id'];
                        $sellerID = $array_params['static']['seller_id'];
                        $transaksiTipe = $array_params['static']['transaksi_tipe'];
                        $transaksiStep = $array_params['static']['transaksi_step'];

                        $_preValues = $this->cekPreValue(
                            $key,
                            $cabangID,
                            $periode,
                            $externID,
//                            $gudangID,
                            $fulldate,
//                            $masterID,
                            $transaksiID
//                            $extern2ID,
//                            $sellerID
                        );

                        if (array_key_exists("id", $_preValues["cache"]) && ($_preValues["cache"]["id"] > 0)) {
                            $mode = "update";
                            $_preValues_id = $_preValues["cache"]["id"];
                            //--------------
                        }
                        else {
                            $mode = "insert";
                            $_preValues_id = 0;
                            $date_ex = explode("-", $fulldate);
                            $this->outParams[$lCounter]["cache"][$mode]["tgl"] = isset($date_ex[2]) ? $date_ex[2] : date("d");
                            $this->outParams[$lCounter]["cache"][$mode]["bln"] = isset($date_ex[1]) ? $date_ex[1] : date("m");
                            $this->outParams[$lCounter]["cache"][$mode]["thn"] = isset($date_ex[0]) ? $date_ex[0] : date("Y");
                            //--------------
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

                        cekhitam(":: $afterNumber, val: $value,  $afterPosition, afterQty: $afterQtyNumber, mode: $mode");


                        //  region cache rekening pembantu
                        $pakai_cache = 1;
                        if ($pakai_cache == 1) {
                            switch ($afterPosition) {
                                case "kredit":

                                    $this->outParams[$lCounter]["cache"][$mode]["kredit"] = abs($afterNumber);
                                    $this->outParams[$lCounter]["cache"][$mode]["debet"] = 0;
                                    //------------
                                    //                                    $this->outParams[$lCounter]["cache"][$mode]["debet_lap"] = $_preValues["cache"]["debet_lap"];
                                    //                                    $this->outParams[$lCounter]["cache"][$mode]["kredit_lap"] = $_preValues["cache"]["kredit_lap"] + abs($value);
                                    break;
                                case "debet":
                                    $this->outParams[$lCounter]["cache"][$mode]["kredit"] = 0;
                                    $this->outParams[$lCounter]["cache"][$mode]["debet"] = abs($afterNumber);

                                    //------------
                                    //                                    $this->outParams[$lCounter]["cache"][$mode]["debet_lap"] = $_preValues["cache"]["debet_lap"] + abs($value);
                                    //                                    $this->outParams[$lCounter]["cache"][$mode]["kredit_lap"] = $_preValues["cache"]["kredit_lap"];
                                    break;
                                default:
                                    die(lgShowAlert(__LINE__ . " gagal menentukan posisi rekening DEBET / KREDIT " . __FUNCTION__ . " on file " . __FILE__));
                                    break;
                            }
                            switch ($afterQtyPosition) {
                                case "kredit":

                                    $this->outParams[$lCounter]["cache"][$mode]["qty_kredit"] = abs($afterQtyNumber);
                                    $this->outParams[$lCounter]["cache"][$mode]["qty_debet"] = 0;
                                    //------------
                                    //                                    $this->outParams[$lCounter]["cache"][$mode]["qty_debet_lap"] = $_preValues["cache"]["qty_debet_lap"];
                                    //                                    $this->outParams[$lCounter]["cache"][$mode]["qty_kredit_lap"] = $_preValues["cache"]["qty_kredit_lap"] + abs($unit);
                                    break;
                                case "debet":
                                    $this->outParams[$lCounter]["cache"][$mode]["qty_kredit"] = 0;
                                    $this->outParams[$lCounter]["cache"][$mode]["qty_debet"] = abs($afterQtyNumber);

                                    //------------
                                    //                                    $this->outParams[$lCounter]["cache"][$mode]["qty_debet_lap"] = $_preValues["cache"]["qty_debet_lap"] + abs($unit);
                                    //                                    $this->outParams[$lCounter]["cache"][$mode]["qty_kredit_lap"] = $_preValues["cache"]["qty_kredit_lap"];

                                    break;
                                default:
                                    die(lgShowAlert(__LINE__ . " gagal menentukan posisi rekening DEBET / KREDIT " . __FUNCTION__ . " on file " . __FILE__));
                                    break;
                            }

                            switch ($position) {
                                case "kredit":
                                    if ($mode == "insert") {
                                        //------------
                                        $this->outParams[$lCounter]["cache"][$mode]["debet_lap"] = $_preValues["cache"]["debet_lap"];
                                        $this->outParams[$lCounter]["cache"][$mode]["kredit_lap"] = $_preValues["cache"]["kredit_lap"] + abs($value);
                                        //------------
                                        $this->outParams[$lCounter]["cache"][$mode]["qty_debet_lap"] = $_preValues["cache"]["qty_debet_lap"];
                                        $this->outParams[$lCounter]["cache"][$mode]["qty_kredit_lap"] = $_preValues["cache"]["qty_kredit_lap"] + abs($unit);
                                    }
                                    else {
                                        if (isset($array_params['static']['update_lap']) && ($array_params['static']['update_lap'] == false)) {
                                            $value = 0;
                                            $unit = 0;
                                        }
                                        $new_debet_lap = $_preValues["cache"]["debet_lap"] - abs($value) > 0 ? $_preValues["cache"]["debet_lap"] - abs($value) : 0;
                                        $new_kredit_lap = $_preValues["cache"]["kredit_lap"] + abs($value) > 0 ? $_preValues["cache"]["kredit_lap"] + abs($value) : 0;
                                        $new_qty_debet_lap = $_preValues["cache"]["qty_debet_lap"] - abs($unit) > 0 ? $_preValues["cache"]["qty_debet_lap"] - abs($unit) : 0;
                                        $new_qty_kredit_lap = $_preValues["cache"]["qty_kredit_lap"] + abs($unit) > 0 ? $_preValues["cache"]["qty_kredit_lap"] + abs($unit) : 0;
                                        //------------
                                        $this->outParams[$lCounter]["cache"][$mode]["debet_lap"] = $new_debet_lap;
                                        $this->outParams[$lCounter]["cache"][$mode]["kredit_lap"] = $new_kredit_lap;
                                        //------------
                                        $this->outParams[$lCounter]["cache"][$mode]["qty_debet_lap"] = $new_qty_debet_lap;
                                        $this->outParams[$lCounter]["cache"][$mode]["qty_kredit_lap"] = $new_qty_kredit_lap;
                                    }

                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_kredit"] = $_preValues['cache']['saldo_kredit'] + abs($value);
                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_kredit"] = $_preValues['cache']['saldo_qty_kredit'] + abs($unit);
                                    break;
                                case "debet":
                                    if ($mode == "insert") {
                                        //------------
                                        $this->outParams[$lCounter]["cache"][$mode]["debet_lap"] = $_preValues["cache"]["debet_lap"] + abs($value);
                                        $this->outParams[$lCounter]["cache"][$mode]["kredit_lap"] = $_preValues["cache"]["kredit_lap"];
                                        //------------
                                        $this->outParams[$lCounter]["cache"][$mode]["qty_debet_lap"] = $_preValues["cache"]["qty_debet_lap"] + abs($unit);
                                        $this->outParams[$lCounter]["cache"][$mode]["qty_kredit_lap"] = $_preValues["cache"]["qty_kredit_lap"];
                                    }
                                    else {
                                        if (isset($array_params['static']['update_lap']) && ($array_params['static']['update_lap'] == false)) {
                                            $value = 0;
                                            $unit = 0;
                                        }
                                        $new_debet_lap = $_preValues["cache"]["debet_lap"] + abs($value) > 0 ? $_preValues["cache"]["debet_lap"] + abs($value) : 0;
                                        $new_kredit_lap = $_preValues["cache"]["kredit_lap"] - abs($value) > 0 ? $_preValues["cache"]["kredit_lap"] - abs($value) : 0;
                                        $new_qty_debet_lap = $_preValues["cache"]["qty_debet_lap"] + abs($unit) > 0 ? $_preValues["cache"]["qty_debet_lap"] + abs($unit) : 0;
                                        $new_qty_kredit_lap = $_preValues["cache"]["qty_kredit_lap"] - abs($unit) > 0 ? $_preValues["cache"]["qty_kredit_lap"] - abs($unit) : 0;
                                        //------------
                                        $this->outParams[$lCounter]["cache"][$mode]["debet_lap"] = $new_debet_lap;
                                        $this->outParams[$lCounter]["cache"][$mode]["kredit_lap"] = $new_kredit_lap;
                                        //------------
                                        $this->outParams[$lCounter]["cache"][$mode]["qty_debet_lap"] = $new_qty_debet_lap;
                                        $this->outParams[$lCounter]["cache"][$mode]["qty_kredit_lap"] = $new_qty_kredit_lap;
                                    }

                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_debet"] = $_preValues['cache']['saldo_debet'] + abs($value);
                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_debet"] = $_preValues['cache']['saldo_qty_debet'] + abs($unit);
                                    break;
                                default:
                                    die(lgShowAlert(__LINE__ . " gagal menentukan posisi rekening DEBET / KREDIT " . __FUNCTION__ . " on file " . __FILE__));
                                    break;
                            }

                            $this->outParams[$lCounter]["cache"][$mode]["rekening"] = $key;
                            $this->outParams[$lCounter]["cache"][$mode]["periode"] = $periode;
                            $this->outParams[$lCounter]["cache"][$mode]["id"] = $_preValues_id;
                            $this->outParams[$lCounter]["cache"][$mode]["harga"] = $value_item;
                            // mereplace debet_lap, kredit_lap
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
                                    $this->outParams[$lCounter]["mutasi"]["harga"] = abs($value_item);
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
                                                            // arrPrintWebs($pSpec_mode_data);
                                                            $this->db->insert($tableName, $pSpec_mode_data);
                                                            $insertIDs[] = $this->db->insert_id();
                                                            cekUngu("$sub_mode :: " . $this->db->last_query());
                                                            break;
                                                        case "update":
                                                            // arrPrint($pSpec_mode_data);
                                                            // matiHEre($id);
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

        if (sizeof($insertIDs) > 0) {

            return true;
        }
        else {
            return false;
        }
    }

    private function cekPreValue_OLD($rek, $cabang_id, $periode, $produk_id, $gudang_id, $date = NULL, $master_id, $transaksi_id, $extern2_id)
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
        $this->addFilter("master_id='$master_id'");
        $this->addFilter("extern2_id='$extern2_id'");
        //        $this->addFilter("transaksi_id='$transaksi_id'");

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
                    "rejected" => $row->rejected,
                    "closed" => $row->closed,
                    "qty_debet_lap" => $row->qty_debet_lap,
                    "qty_kredit_lap" => $row->qty_kredit_lap,
                    "debet_lap" => $row->debet_lap,
                    "kredit_lap" => $row->kredit_lap,
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
            $this->addFilter("master_id='$master_id'");
            $this->addFilter("extern2_id='$extern2_id'");
            //            $this->addFilter("transaksi_id='$transaksi_id'");

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
                        //---------------
                        "batal" => $row->batal,
                        "rejected" => $row->rejected,
                        "closed" => $row->closed,
                        "qty_debet_lap" => $row->qty_debet_lap,
                        "qty_kredit_lap" => $row->qty_kredit_lap,
                        "debet_lap" => $row->debet_lap,
                        "kredit_lap" => $row->kredit_lap,
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
                    "rejected" => 0,
                    "closed" => 0,
                    "qty_debet_lap" => 0,
                    "qty_kredit_lap" => 0,
                    "debet_lap" => 0,
                    "kredit_lap" => 0,
                );
            }
        }
        //  endregion mengambil saldo dari rek_cache

        return $result;
    }

//    private function cekPreValue($rek, $cabang_id, $periode, $produk_id, $gudang_id, $date = NULL, $master_id, $transaksi_id, $extern2_id, $seller_id)
    private function cekPreValue($rek, $cabang_id, $periode, $produk_id, $date = NULL, $transaksi_id)
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
//        $this->addFilter("gudang_id='$gudang_id'");
        $this->addFilter("periode='$periode'");
        $this->addFilter("extern_id='$produk_id'");
//        $this->addFilter("master_id='$master_id'");
//        $this->addFilter("extern2_id='$extern2_id'");
//        $this->addFilter("seller_id='$seller_id'");

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
            // bila count($tmp) == 0, maka ambil saldo periode forever dan mode insert
            $this->filters = array();
            $this->addFilter("rekening='$rek'");
            $this->addFilter("cabang_id='$cabang_id'");
//        $this->addFilter("gudang_id='$gudang_id'");
            $this->addFilter("periode='forever'");
            $this->addFilter("extern_id='$produk_id'");
//        $this->addFilter("master_id='$master_id'");
//        $this->addFilter("extern2_id='$extern2_id'");
//        $this->addFilter("seller_id='$seller_id'");

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

    public function fetchMovement($rek)
    {//==memanggil saldo2 dari rekening tertentu
        $tableName = $this->tableName_master['mutasi'];
        // $this->db->select("*");
        // $this->db->where(array("extern_id" => $externID));
        if (isset($this->sortBy)) {
            $this->db->order_by($this->sortBy['kolom'], $this->sortBy['mode']);
        }
        else {
            $this->db->order_by("id", "desc");
        }
        $all_columns = $this->db->list_fields($tableName);
        $blackList_column = array(
            // "cabang_nama",
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

        $result = $this->db->get($tableName);
        //        cekkuning($this->db->last_query());

        return $result->result();
    }

    public function fetchCache($rek)
    {//==memanggil saldo2 dari rekening tertentu
        $tableName = $this->tableName;
        // $this->db->select("*");
        // $this->db->where(array("extern_id" => $externID));
        if (isset($this->sortBy)) {
            $this->db->order_by($this->sortBy['kolom'], $this->sortBy['mode']);
        }
        else {
            $this->db->order_by("id", "desc");
        }
        $all_columns = $this->db->list_fields($tableName);
        $blackList_column = array(
            // "cabang_nama",
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

        $result = $this->db->get($tableName);
        // cekkuning($this->db->last_query());

        return $result->result();
    }

    public function callMovementProduk($reks)
    {
        if (isset($this->jenisTr) && is_array($this->jenisTr)) {
            $this->db->where_in("rekening", $this->jenisTr);
        }
        else {
            $condites = array(
                "rekening" => $this->jenisTr,
                // "extern_id" => "6577",
                // "extern_id" => "22341",
            );
            $this->db->where($condites);
        }
        // 
        $common_condites = array(// "trash" => 0
        );
        $this->db->where($common_condites);
        // if(isset($this->sortBy)){
        //     $this->db->order_by($this->sortBy['kolom'], $this->sortBy['mode']);
        // }
        $srcPersediaans = $this->fetchCache($reks);
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
        // matiHere(__LINE__);
        /* -----------------------------------------------------------
         * produk transaksi
         * -----------------------------------------------------------*/
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        // $trSpeks = $tr->callSpecs($transaksiIds);
        // arrPrintWebs($trSpeks);
        /* ------------------------------------------------------------------------------------------
         * spek produk harus ditambahin data dari registri juga, kalau hanya dari persediaan tidak bisa
         * mendapatkan harga jual
         * ------------------------------------------------------------------------------------------*/
        $srcs = $tr->lookupTransaksiDataRegistries($transaksiIds)->result();
        // showLast_query("pink");
        // matiHere(__LINE__);
        foreach ($srcs as $src) {
            $trSpeks0[$src->id] = $src;
        }

        $trSpeks = array();
        $dataBaru_1 = array();
        foreach ($srcs as $src) {
            $trId = $src->id;
            $items = blobDecode($src->items);
            $mains = blobDecode($src->main);
            $newMains = addPrefixKeyM_he_format($mains);
            // arrPrintKuning($mains);
            // arrPrintWebs($newMains);

            foreach ($items as $produk_id => $item) {
                $newItem = addPrefixKeyI_he_format($item);
                $dataBaru_1 = $newItem + (array)$trSpeks0[$trId] + $newMains;
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

    public function callCacheProduk($reks)
    {
        if (isset($this->jenisTr) && is_array($this->jenisTr)) {
            $this->db->where_in("rekening", $this->jenisTr);
        }
        else {
            $condites = array(
                "rekening" => $this->jenisTr,
                // "extern_id" => "278",
                // "extern_id" => "22341",
            );
            $this->db->where($condites);
        }
        //
        $common_condites = array(
            // "trash" => 0,
            // "extern_id" => "278",
        );
        $this->db->where($common_condites);
        // if(isset($this->sortBy)){
        //     $this->db->order_by($this->sortBy['kolom'], $this->sortBy['mode']);
        // }
        $srcPersediaans = $this->fetchCache($reks);
        $produkIds = array();
        $transaksiIds = array();
        foreach ($srcPersediaans as $src) {
            // $produkIds[] = $src->extern_id;
            // $transaksiIds[] = $src->transaksi_id;
            $extern_id = $src->extern_id;
            $transaksi_id = $src->transaksi_id;
            $master_id = $src->master_id;
            $rekening = $src->rekening;

            $produkIds[$extern_id] = $extern_id;
            $transaksiIds[$transaksi_id] = $transaksi_id;

            /* -----------------------------------------------------------------------------------------------
             * pembentukan array data dengan key baru (rekening_key)
             * -----------------------------------------------------------------------------------------------*/
            $srcDatas = (array)$src;
            foreach ($srcDatas as $key => $value) {
                $newKey = $rekening . "_" . $key;

                $srcPersediaans_00[$master_id][$extern_id][$newKey] = $value;
            }
        }

        // showLast_query("biru");
        // cekOrange(sizeof($srcPersediaans));
        // arrPrintKuning($transaksiIds);
        // arrPrintKuning($produkIds);
        //
        // arrPrintHijau(sizeof($srcPersediaans_00));
        // arrPrintHijau($srcPersediaans_00);
        // arrPrintPink($srcPersediaans);
        // matiHere(__LINE__);

        foreach ($srcPersediaans_00 as $mst_id => $prods) {
            foreach ($prods as $prod_id => $cacheDatas) {
                $dataKeys["mst_id"] = $mst_id;
                $dataKeys["prod_id"] = $prod_id;
                $srcPersediaans_01[] = $cacheDatas + $dataKeys;
            }
        }

        // cekKuning(sizeof($srcPersediaans_01));
        // arrPrintKuning($srcPersediaans_01);
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
        // matiHere(__LINE__);
        /* -----------------------------------------------------------
         * produk transaksi
         * -----------------------------------------------------------*/
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        // $trSpeks = $tr->callSpecs($transaksiIds);
        // arrPrintWebs($trSpeks);
        /* ------------------------------------------------------------------------------------------
         * spek produk harus ditambahin data dari registri juga, kalau hanya dari persediaan tidak bisa
         * mendapatkan harga jual
         * ------------------------------------------------------------------------------------------*/
        $pakai = 0;
        if ($pakai == 1) {
            $srcs = $tr->lookupTransaksiDataRegistries($transaksiIds)->result();// showLast_query("pink");
            // matiHere(__LINE__);
            foreach ($srcs as $src) {
                $trSpeks0[$src->id] = $src;
            }
            $trSpeks = array();
            $dataBaru_1 = array();
            foreach ($srcs as $src) {
                $trId = $src->id;
                $items = blobDecode($src->items);
                $mains = blobDecode($src->main);
                $newMains = addPrefixKeyM_he_format($mains);
                // arrPrintKuning($mains);
                // arrPrintWebs($newMains);

                foreach ($items as $produk_id => $item) {
                    $newItem = addPrefixKeyI_he_format($item);
                    // $dataBaru_1 = $newItem + (array)$trSpeks0[$trId] + $newMains;
                    $dataBaru_1 = $newItem + (array)$trSpeks0[$trId];
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
        }

        // --------------------------------------------------------------------------------

        // matiHere(__LINE__);
        foreach ($srcPersediaans_01 as $mst_id => $srcPersediaan) {
            $prId = $srcPersediaan['prod_id'];
            // $trId = $srcPersediaan->transaksi_id;

            // $dataBaru = (array)$srcPersediaan + (array)$prSpeks[$prId] + (array)$trSpeks[$trId];
            // $dataBaru = (array)$srcPersediaan + (array)(isset($prSpeks[$prId]) ? $prSpeks[$prId] : array()) + (array)(isset($trSpeks[$trId][$prId]) ? $trSpeks[$trId][$prId] : array());
            $dataBaru = (array)$srcPersediaan + (array)(isset($prSpeks[$prId]) ? $prSpeks[$prId] : array());
            $masterData[] = $dataBaru;
        }

        $vars = array();
        $vars['data'] = $masterData;
        $vars['data_jml']['total'] = sizeof($srcPersediaans);
        $vars['data_jml']['produk'] = sizeof($produkIds);
        $vars['data_jml']['transaksi'] = sizeof($transaksiIds);


        return $vars;
    }

    public function fetchLastMove($jenis = "")
    {
        $tableName = $this->tableName_master['mutasi'];
        $blackList_column = array(
            "cabang_nama",
            "gudang_nama",
            "r_move",
        );

        $condites = array(
            "rekening" => "582spo",
        );
        $selectPembelian = array(
            // "sum(qty_kredit) as 'unit_af'",
            // "sum(kredit) as 'nilai_af'",
            "month(dtime) as 'bl'",
            "year(dtime) as 'th'",
            "date(dtime) as 'tanggal'",
            "id",
            "dtime",
            "extern_id as 'subject_id'",
        );
        $this->db->select($selectPembelian);
        $this->db->where($condites);
        // $this->db->group_by("extern_id");
        $this->db->order_by("id,th,bl", "asc");
        $this->db->where($condites);
        $src_0 = $this->db->get($tableName)->result();
        showLast_query("biru");
        cekLime(sizeof($src_0));
        arrPrintKuning($src_0);

        $src = array();
        foreach ($src_0 as $item) {
            $src[$item->subject_id]['dtime_last'][$item->dtime][] = $item->dtime;
        }

        arrPrintPink($src);
        return $src;
    }

    public function callOrderan($reks)
    {
        $rekenings = array(
            "582spo",
            // "382spo"
        );
        $this->db->where_in("rekening", $rekenings);
        $condites = array(
            // "rekening" => "582spo",
            // "rekening" => "582so",
            "rekening" => "382spo",
            // "qty_kredit_lap >" => "0",
        );
        // $this->db->where($condites);
        // $this->db->group_by("extern_id");
        $this->db->order_by("id", "asc");
        // $this->db->where($condites);
        $src_0 = $srcPersediaans = $this->fetchCache($reks);
        // showLast_query("biru");
        // cekLime(sizeof($src_0));
        // arrPrintKuning($src_0);

        $src = array();
        $salesMan = array();
        $produkIds = array();
        $masterIds = array();
        $masterProdukIds = array();
        $outraws = array();
        foreach ($src_0 as $item) {

            $oleh_id = $item->oleh_id;
            $oleh_nama = $item->oleh_nama;
            $produk_id = $item->extern_id;
            $master_id = $item->master_id;

            $qty_kredit = $item->qty_kredit_lap;
            $kredit = $item->kredit_lap;
            $debet = $item->debet_lap;

            // ---------------------------------------------------------------------
            if (!isset($srcProduk[$item->extern_id]['sum_qty_kredit'])) {
                $srcProduk[$item->extern_id]['sum_qty_kredit'] = 0;
            }
            $srcProduk[$item->extern_id]['sum_qty_kredit'] += $qty_kredit;
            // ---------------------------------------------------------------------
            if (!isset($srcCustomer[$item->customer_id]['sum_qty_kredit'])) {
                $srcCustomer[$item->customer_id]['sum_qty_kredit'] = 0;
            }
            $srcCustomer[$item->customer_id]['sum_qty_kredit'] += $qty_kredit;
            // ---------------------------------------------------------------------
            if (!isset($srcMaster[$item->master_id]['sum_qty_kredit'])) {
                $srcMaster[$item->master_id]['sum_qty_kredit'] = 0;
            }
            $srcMaster[$item->master_id]['sum_qty_kredit'] += $qty_kredit;
            // ---------------------------------------------------------------------

            $salesMan[$master_id][$oleh_id] = $oleh_nama;

            $produkIds[$produk_id] = $produk_id;
            $masterIds[$master_id] = $master_id;
            $masterProdukIds[$master_id][$produk_id] = $produk_id;
            $outraws[] = (array)$item;


        }

        // cekMerah(sizeof($srcMaster));
        // cekOrange(sizeof($srcCustomer));
        // cekKuning(sizeof($srcProduk));
        // // arrPrintPink($srcProduk);
        // arrPrintPink($outraws);

        /* --------------------------------
         * update tgl spo
         * --------------------------------*/
        // $this->db->trans_start();
        foreach ($masterIds as $masterIdx) {
            $this->db->select(array("id as tr_id", "dtime as tr_dtime", "_company_jenisTr", "_company_stepCode", "_company_customerID", "_company_sellerID", "_company_olehID"));
            $this->db->where(array("id" => $masterIdx));
            $tr_result = $this->db->get("transaksi")->row_array();
            //     $dtime_tr = $tr_result->dtime;
            //     $dtime_tr_full = formatTanggal($dtime_tr, 'Y-m-d');
            // showLast_query("merah");
            //     arrPrint($tr_result);
            //
            //     $this->db->where(array("master_id" => $masterIdx, "rekening" => "582spo", "fulldate!=" => $dtime_tr_full));
            //     $this->db->update("z_rekening_transaksi_pembantu_cache", array("dtime" => $dtime_tr, "fulldate" => $dtime_tr_full));
            //     showLast_query("kuning");
            // break;

            $trDatas[$masterIdx] = $tr_result;
        }
        // $this->db->trans_complete();
        // mati_disini();
        // arrPrint($trDatas);
        // arrPrint($masterIds);
        // cekBiru(sizeof($masterIds));

        /* ----------------------------------------------------------
         * 582pkd
         * ----------------------------------------------------------*/
        $src_pkds = array();
        if (sizeof($masterIds) > 0) {

            $condites = array(
                "rekening" => "582pkd",
                "rekening or" => "382pkd",
                // "rekening or" => "582pkd",
            );
            $conditestr = "(rekening= '582pkd' OR rekening= '382pkd')";
            $this->db->where($conditestr);
            $this->db->where_in('master_id', $masterIds);

            $this->db->order_by("id", "asc");
            $src_pkds = $this->fetchCache($reks);
        }
        // showLast_query("kuning");
        // cekBiru(sizeof($src_spos));
        // arrPrintKuning($src_pkds);

        foreach ($src_pkds as $item_spo) {
            //    qty_debet_lap
            $spo_mast_id = $item_spo->master_id;
            $spo_ext_id = $item_spo->extern_id;
            $spo_debet = $item_spo->debet_lap * 1;
            $spo_kredit = $item_spo->kredit_lap * 1;
            $spo_rejected = $item_spo->rejected;
            $spo_closed = $item_spo->closed;

            $spo_datas[$item_spo->master_id][$item_spo->extern_id]['pkd_qty_debet_lap'] = $item_spo->qty_debet_lap;
            $spo_datas[$item_spo->master_id][$item_spo->extern_id]['pkd_debet_lap'] = $spo_debet;
            $spo_datas[$item_spo->master_id][$item_spo->extern_id]['pkd_qty_kredit_lap'] = $item_spo->qty_kredit_lap;
            $spo_datas[$item_spo->master_id][$item_spo->extern_id]['pkd_kredit_lap'] = $spo_kredit;
            $spo_datas[$item_spo->master_id][$item_spo->extern_id]['pkd_rejected'] = $spo_rejected;
            $spo_datas[$item_spo->master_id][$item_spo->extern_id]['pkd_closed'] = $spo_closed;


        }
        // arrPrintPink($spo_datas);
        /* -----------------------------------------------------------
       * produk spek
       * -----------------------------------------------------------*/
        $prSpeks = array();
        if (sizeof($produkIds) > 0) {

            // $produkIds = "";
            // $transaksiIds = "";
            $this->load->model("Mdls/MdlProduk");
            $pr = new MdlProduk();
            $prSpeks = $pr->callSpecs($produkIds);
        }
        // showLast_query("orange");
        // cekKuning(sizeof($produkIds));
        // cekKuning(sizeof($prSpeks));
        // arrPrintKuning($prSpeks);
        $otraws = array();
        foreach ($outraws as $outraw) {
            // arrPrintPink($outraw);
            // break;
            $prod_id = $outraw['extern_id'];
            $mast_id = $outraw['master_id'];
            $oleh_id = $outraw['oleh_id'];
            $seller_id = $outraw['seller_id'];
            $customer_id = $outraw['customer_id'];
            $cabang_id = $outraw['cabang_id'];
            $data_pkd = isset($spo_datas[$mast_id][$prod_id]) ? $spo_datas[$mast_id][$prod_id] : array();
            $data_tr = isset($trDatas[$mast_id]) ? $trDatas[$mast_id] : array();
            // arrPrint($data_spo);
            // cekMerah(__LINE__);
            $otraws[] = (isset($prSpeks[$prod_id]) ? (array)$prSpeks[$prod_id] : array()) + $outraw + $data_pkd + $data_tr;

            // $otmasters[$mast_id] = $srcMaster[$mast_id] + $outraw;
            // $otsales[$seller_id] = $srcSales[$seller_id] + $outraw;
            // $otcustomer[$customer_id] = $srcCustomer[$customer_id] + $outraw;
            // $otcabang[$cabang_id] = $srcCabang[$cabang_id] + $outraw;
            // break;
        }
        // arrPrintHijau($otraws);
        $srcs = array();
        // $srcs['produk'] = $otproduk;
        // $srcs['master'] = $otmasters;
        // $srcs['salesman'] = $otsales;
        // $srcs['customer'] = $otcustomer;
        // $srcs['cabang'] = $otcabang;
        $srcs['raw'] = $otraws;

        return $srcs;
    }

    public function callOutstandingBulanan($reks)
    {
        $srcs = $this->callOutstanding($reks, "bulanan");

        return $srcs;
    }

    public function callOutstanding($reks, $periode = "forever")
    {

        $condites = array(
            "rekening" => "582pkd",
            // "qty_kredit_lap >" => "0",
            "year(dtime) >" => "2020",
            "periode" => $periode,
        );

        // $this->db->where($condites);
        // $this->db->group_by("extern_id");
        $this->db->order_by("id", "asc");
        $this->db->where($condites);
        $src_0 = $srcPersediaans = $this->fetchCache($reks);
        // showLast_query("biru");
        // cekLime(sizeof($src_0));
        // arrPrintKuning($src_0);

        $dtime_now = dtimeNow('Y-m-d');
        $dtime_Y = dtimeNow('Y');
        $dtime_m = dtimeNow('m');
        $dtime_Y = dtimeNow('d');
        $dtime_t = dtimeNow('t');
        $dtime_target = "$dtime_Y-$dtime_m-$dtime_t";

        /* ------------------------------------------------------------------------
         * mensumary per-per object
         * ------------------------------------------------------------------------*/
        $src = array();
        $outraws = array();
        foreach ($src_0 as $item) {

            $produk_id = $item->extern_id;
            $master_id = $item->master_id;

            $qty_kredit = $item->qty_kredit_lap;
            $kredit = $item->kredit_lap;
            $debet = $item->debet_lap;
            if ($qty_kredit > 0) {
                // --------PRODUK-------------------------------------------------------------
                if (!isset($srcProduk[$item->extern_id]['sum_qty_kredit'])) {
                    $srcProduk[$item->extern_id]['sum_qty_kredit'] = 0;
                }
                $srcProduk[$item->extern_id]['sum_qty_kredit'] += $qty_kredit;

                if (!isset($srcProduk[$item->extern_id]['sum_kredit'])) {
                    $srcProduk[$item->extern_id]['sum_kredit'] = 0;
                }
                $srcProduk[$item->extern_id]['sum_kredit'] += $kredit;
                // --------CUSTOMER-------------------------------------------------------------
                if (!isset($srcCustomer[$item->customer_id]['sum_debet'])) {
                    $srcCustomer[$item->customer_id]['sum_debet'] = 0;
                }
                $srcCustomer[$item->customer_id]['sum_debet'] += $debet;
                // ---------
                if (!isset($srcCustomer[$item->customer_id]['sum_kredit'])) {
                    $srcCustomer[$item->customer_id]['sum_kredit'] = 0;
                }
                $srcCustomer[$item->customer_id]['sum_kredit'] += $kredit;
                // --------MASTER-------------------------------------------------------------
                if (!isset($srcMaster[$item->master_id]['sum_debet'])) {
                    $srcMaster[$item->master_id]['sum_debet'] = 0;
                }
                $srcMaster[$item->master_id]['sum_debet'] += $debet;
                if (!isset($srcMaster[$item->master_id]['sum_kredit'])) {
                    $srcMaster[$item->master_id]['sum_kredit'] = 0;
                }
                $srcMaster[$item->master_id]['sum_kredit'] += $kredit;
                // --------SALESMAN-------------------------------------------------------------
                if (!isset($srcSales[$item->seller_id]['sum_debet'])) {
                    $srcSales[$item->seller_id]['sum_debet'] = 0;
                }
                $srcSales[$item->seller_id]['sum_debet'] += $debet;
                // ------------
                if (!isset($srcSales[$item->seller_id]['sum_kredit'])) {
                    $srcSales[$item->seller_id]['sum_kredit'] = 0;
                }
                $srcSales[$item->seller_id]['sum_kredit'] += $kredit;
                // --------CABANG-------------------------------------------------------------
                if (!isset($srcCabang[$item->cabang_id]['sum_debet'])) {
                    $srcCabang[$item->cabang_id]['sum_debet'] = 0;
                }
                $srcCabang[$item->cabang_id]['sum_debet'] += $debet;
                // ------------
                if (!isset($srcCabang[$item->cabang_id]['sum_kredit'])) {
                    $srcCabang[$item->cabang_id]['sum_kredit'] = 0;
                }
                $srcCabang[$item->cabang_id]['sum_kredit'] += $kredit;
                // ---------------------------------------------------------------------

                /* ------------------------------------
                     * untuk membuat potret tiap akhir bulan
                     * belom jadi lo ini
                     * --------------------------------------*/

                $condite_cek = array(
                    'fulldate' => $dtime_target,
                );
                // $this->db->where($condite_cek);
                // $this->lookupAll()->result();
                // showLast_query("merah");
                // break;

                // if($qty_kredit > 0){
                $produkIds[$produk_id] = $produk_id;
                $masterIds[$master_id] = $master_id;
                $masterProdukIds[$master_id][$produk_id] = $produk_id;
                $outraws[] = (array)$item;
            }
        }

        // cekMerah(sizeof($srcSales));
        // arrPrintPink($srcSales);
        // cekMerah(sizeof($srcMaster));
        // cekOrange(sizeof($outraws));
        // cekOrange(sizeof($srcProduk));
        // arrPrintPink($srcCustomer);
        // cekKuning(sizeof($srcProduk));
        // arrPrintPink($srcProduk);
        // arrPrintPink($srcMaster);
        // cekHijau(sizeof($masterIds));

        /* ----------------------------------------------------------
         * 582spo
         * ----------------------------------------------------------*/
        $condites = array(
            "rekening" => "582spo",
            "qty_debet_lap >" => "0",
        );

        $this->db->where($condites);
        $this->db->where_in('master_id', $masterIds);
        // $this->db->group_by("extern_id");
        $this->db->order_by("id", "asc");
        // $this->db->where($condites);
        $src_spos = $this->fetchCache($reks);

        foreach ($src_spos as $item_spo) {
            //    qty_debet_lap
            $spo_mast_id = $item_spo->master_id;
            $spo_ext_id = $item_spo->extern_id;
            $spo_debet = $item_spo->debet_lap * 1;
            $spo_datas[$item_spo->master_id][$item_spo->extern_id]['spo_qty_debet_lap'] = $item_spo->qty_debet_lap;
            $spo_datas[$item_spo->master_id][$item_spo->extern_id]['spo_debet_lap'] = $spo_debet;

            // --------MASTER-------------------------------------------------------------
            if (!isset($srcMaster[$item_spo->master_id]['sum_spo_debet'])) {
                $srcMaster[$item_spo->master_id]['sum_spo_debet'] = 0;
            }
            $srcMaster[$item_spo->master_id]['sum_spo_debet'] += $spo_debet;
            // --------CUSTOMER-------------------------------------------------------------
            if (!isset($srcCustomer[$item_spo->customer_id]['sum_spo_debet'])) {
                $srcCustomer[$item_spo->customer_id]['sum_spo_debet'] = 0;
            }
            $srcCustomer[$item_spo->customer_id]['sum_spo_debet'] += $spo_debet;
            // --------SALESMAN-------------------------------------------------------------
            if (!isset($srcSales[$item_spo->seller_id]['sum_spo_debet'])) {
                $srcSales[$item_spo->seller_id]['sum_spo_debet'] = 0;
            }
            $srcSales[$item_spo->seller_id]['sum_spo_debet'] += $spo_debet;
            // --------CABANG-------------------------------------------------------------
            if (!isset($srcCabang[$item_spo->cabang_id]['sum_spo_debet'])) {
                $srcCabang[$item_spo->cabang_id]['sum_spo_debet'] = 0;
            }
            $srcCabang[$item_spo->cabang_id]['sum_spo_debet'] += $spo_debet;
            // ----------------------------------------------------------------
        }
        // arrPrintWebs($spo_datas);

        /* -----------------------------------------------------------
        * produk spek
        * -----------------------------------------------------------*/
        // $produkIds = "";
        // $transaksiIds = "";
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $prSpeks = $pr->callSpecs($produkIds);
        // showLast_query("orange");
        // cekKuning(sizeof($produkIds));
        // cekKuning(sizeof($prSpeks));
        // arrPrintKuning($prSpeks);

        /* --------------------------------------------------------------------------
         * pengabungan
         * --------------------------------------------------------------------------*/
        $otproduk = array();
        foreach ($prSpeks as $produk_id => $prSpek) {

            $outProduk = $srcProduk[$produk_id];
            $otproduk[] = (array)$prSpek + $outProduk;

        }
        // cekHijau(sizeof($otproduk));
        // arrPrintHijau($otproduk);

        // arrPrintPink($srcMaster);
        foreach ($outraws as $outraw) {
            // arrPrintPink($outraw);
            // break;
            $prod_id = $outraw['extern_id'];
            $mast_id = $outraw['master_id'];
            $oleh_id = $outraw['oleh_id'];
            $seller_id = $outraw['seller_id'];
            $customer_id = $outraw['customer_id'];
            $cabang_id = $outraw['cabang_id'];
            $data_spo = isset($spo_datas[$mast_id][$prod_id]) ? $spo_datas[$mast_id][$prod_id] : array();
            // arrPrint($data_spo);
            // cekMerah(__LINE__);
            $otraws[] = (isset($prSpeks[$prod_id]) ? (array)$prSpeks[$prod_id] : array()) + $outraw + $data_spo;

            $otmasters[$mast_id] = $srcMaster[$mast_id] + $outraw;
            $otsales[$seller_id] = $srcSales[$seller_id] + $outraw;
            $otcustomer[$customer_id] = $srcCustomer[$customer_id] + $outraw;
            $otcabang[$cabang_id] = $srcCabang[$cabang_id] + $outraw;
            // break;
        }

        $srcs = array();
        $srcs['produk'] = $otproduk;
        $srcs['master'] = $otmasters;
        $srcs['salesman'] = $otsales;
        $srcs['customer'] = $otcustomer;
        $srcs['cabang'] = $otcabang;
        $srcs['raw'] = $otraws;
        // $srcs['customer'] = $otcustomer;
        // $srcs['pso'] = $otmaster;

        return $srcs;
    }

    public function callOutstandingDgPrev($reks)
    {
        $rekenings = array(
            "582spo",
            // "382spo"
        );
        $this->db->where_in("rekening", $rekenings);
        $condites = array(
            "rekening" => "582pkd",
            // "qty_kredit_lap >" => "0",
            // "year(dtime) >" => "2020",
            "periode" => "forever",
        );
        $this->db->where($condites);
        // $this->db->group_by("extern_id");
        $this->db->order_by("id", "asc");
        // $this->db->where($condites);
        $src_0 = $srcPersediaans = $this->fetchCache($reks);
        // showLast_query("biru");
        // cekLime(sizeof($src_0));
        // arrPrintKuning($src_0);

        $src = array();
        $salesMan = array();
        $produkIds = array();
        $masterIds = array();
        $masterProdukIds = array();
        $outraws = array();
        foreach ($src_0 as $item) {

            $oleh_id = $item->oleh_id;
            $oleh_nama = $item->oleh_nama;
            $produk_id = $item->extern_id;
            $master_id = $item->master_id;

            $qty_kredit = $item->qty_kredit_lap;
            $kredit = $item->kredit_lap;
            $debet = $item->debet_lap;

            // ---------------------------------------------------------------------
            if (!isset($srcProduk[$item->extern_id]['sum_qty_kredit'])) {
                $srcProduk[$item->extern_id]['sum_qty_kredit'] = 0;
            }
            $srcProduk[$item->extern_id]['sum_qty_kredit'] += $qty_kredit;
            // ---------------------------------------------------------------------
            if (!isset($srcCustomer[$item->customer_id]['sum_qty_kredit'])) {
                $srcCustomer[$item->customer_id]['sum_qty_kredit'] = 0;
            }
            $srcCustomer[$item->customer_id]['sum_qty_kredit'] += $qty_kredit;
            // ---------------------------------------------------------------------
            if (!isset($srcMaster[$item->master_id]['sum_qty_kredit'])) {
                $srcMaster[$item->master_id]['sum_qty_kredit'] = 0;
            }
            $srcMaster[$item->master_id]['sum_qty_kredit'] += $qty_kredit;
            // ---------------------------------------------------------------------

            $salesMan[$master_id][$oleh_id] = $oleh_nama;

            $produkIds[$produk_id] = $produk_id;
            $masterIds[$master_id] = $master_id;
            $masterProdukIds[$master_id][$produk_id] = $produk_id;
            $outraws[] = (array)$item;


        }

        // cekMerah(sizeof($srcMaster));
        // cekOrange(sizeof($srcCustomer));
        // cekKuning(sizeof($srcProduk));
        // // arrPrintPink($srcProduk);
        // arrPrintPink($outraws);

        /* --------------------------------
         * update tgl spo
         * --------------------------------*/
        // $this->db->trans_start();
        foreach ($masterIds as $masterIdx) {
            $this->db->select(array("id as tr_id", "dtime as tr_dtime", "_company_jenisTr", "_company_stepCode", "_company_customerID", "_company_sellerID", "_company_olehID"));
            $this->db->where(array("id" => $masterIdx));
            $tr_result = $this->db->get("transaksi")->row_array();
            //     $dtime_tr = $tr_result->dtime;
            //     $dtime_tr_full = formatTanggal($dtime_tr, 'Y-m-d');
            // showLast_query("merah");
            //     arrPrint($tr_result);
            //
            //     $this->db->where(array("master_id" => $masterIdx, "rekening" => "582spo", "fulldate!=" => $dtime_tr_full));
            //     $this->db->update("z_rekening_transaksi_pembantu_cache", array("dtime" => $dtime_tr, "fulldate" => $dtime_tr_full));
            //     showLast_query("kuning");
            // break;

            $trDatas[$masterIdx] = $tr_result;
        }
        // $this->db->trans_complete();
        // mati_disini();
        // arrPrint($trDatas);
        // arrPrint($masterIds);
        // cekBiru(sizeof($masterIds));

        /* ----------------------------------------------------------
         * 582pkd
         * ----------------------------------------------------------*/
        $src_pkds = array();
        if (sizeof($masterIds) > 0) {

            $condites = array(
                "rekening" => "582spo",
            );
            // $conditestr = "(rekening= '582pkd' OR rekening= '382pkd')";
            $this->db->where($condites);
            $this->db->where_in('master_id', $masterIds);

            $this->db->order_by("id", "asc");
            $src_pkds = $this->fetchCache($reks);
        }
        // showLast_query("kuning");
        // cekBiru(sizeof($src_spos));
        // arrPrintKuning($src_pkds);

        foreach ($src_pkds as $item_spo) {
            //    qty_debet_lap
            $spo_mast_id = $item_spo->master_id;
            $spo_ext_id = $item_spo->extern_id;
            $spo_debet = $item_spo->debet_lap * 1;
            $spo_kredit = $item_spo->kredit_lap * 1;
            $spo_rejected = $item_spo->rejected;
            $spo_closed = $item_spo->closed;

            $spo_datas[$item_spo->master_id][$item_spo->extern_id]['pkd_qty_debet_lap'] = $item_spo->qty_debet_lap;
            $spo_datas[$item_spo->master_id][$item_spo->extern_id]['pkd_debet_lap'] = $spo_debet;
            $spo_datas[$item_spo->master_id][$item_spo->extern_id]['pkd_qty_kredit_lap'] = $item_spo->qty_kredit_lap;
            $spo_datas[$item_spo->master_id][$item_spo->extern_id]['pkd_kredit_lap'] = $spo_kredit;
            $spo_datas[$item_spo->master_id][$item_spo->extern_id]['pkd_rejected'] = $spo_rejected;
            $spo_datas[$item_spo->master_id][$item_spo->extern_id]['pkd_closed'] = $spo_closed;


        }
        // arrPrintPink($spo_datas);
        /* -----------------------------------------------------------
       * produk spek
       * -----------------------------------------------------------*/
        $prSpeks = array();
        if (sizeof($produkIds) > 0) {

            // $produkIds = "";
            // $transaksiIds = "";
            $this->load->model("Mdls/MdlProduk");
            $pr = new MdlProduk();
            $prSpeks = $pr->callSpecs($produkIds);
        }
        // showLast_query("orange");
        // cekKuning(sizeof($produkIds));
        // cekKuning(sizeof($prSpeks));
        // arrPrintKuning($prSpeks);
        $otraws = array();
        foreach ($outraws as $outraw) {
            // arrPrintPink($outraw);
            // break;
            $prod_id = $outraw['extern_id'];
            $mast_id = $outraw['master_id'];
            $oleh_id = $outraw['oleh_id'];
            $seller_id = $outraw['seller_id'];
            $customer_id = $outraw['customer_id'];
            $cabang_id = $outraw['cabang_id'];
            $data_pkd = isset($spo_datas[$mast_id][$prod_id]) ? $spo_datas[$mast_id][$prod_id] : array();
            $data_tr = isset($trDatas[$mast_id]) ? $trDatas[$mast_id] : array();
            // arrPrint($data_spo);
            // cekMerah(__LINE__);
            $otraws[] = (isset($prSpeks[$prod_id]) ? (array)$prSpeks[$prod_id] : array()) + $outraw + $data_pkd + $data_tr;

            // $otmasters[$mast_id] = $srcMaster[$mast_id] + $outraw;
            // $otsales[$seller_id] = $srcSales[$seller_id] + $outraw;
            // $otcustomer[$customer_id] = $srcCustomer[$customer_id] + $outraw;
            // $otcabang[$cabang_id] = $srcCabang[$cabang_id] + $outraw;
            // break;
        }
        // arrPrintHijau($otraws);
        $srcs = array();
        // $srcs['produk'] = $otproduk;
        // $srcs['master'] = $otmasters;
        // $srcs['salesman'] = $otsales;
        // $srcs['customer'] = $otcustomer;
        // $srcs['cabang'] = $otcabang;
        $srcs['raw'] = $otraws;
    }

    public function callOutstandingPembelian($reks)
    {

        $condites = array(
            "rekening" => "467",
            // "qty_kredit_lap >" => "0",
            "year(dtime) >" => "2020",
        );

        $this->db->where($condites);
        // $this->db->group_by("extern_id");
        $this->db->order_by("id", "asc");
        $this->db->where($condites);
        $src_0 = $srcPersediaans = $this->fetchCache($reks);
        // showLast_query("biru");
        // cekLime(sizeof($src_0));
        // arrPrintKuning($src_0);

        $dtime_now = dtimeNow('Y-m-d');
        $dtime_Y = dtimeNow('Y');
        $dtime_m = dtimeNow('m');
        $dtime_Y = dtimeNow('d');
        $dtime_t = dtimeNow('t');
        $dtime_target = "$dtime_Y-$dtime_m-$dtime_t";

        /* ------------------------------------------------------------------------
         * mensumary per-per object
         * ------------------------------------------------------------------------*/
        $src = array();
        $outraws = array();
        foreach ($src_0 as $item) {

            $produk_id = $item->extern_id;
            $master_id = $item->master_id;

            $qty_kredit = $item->qty_kredit_lap;
            $kredit = $item->kredit_lap;
            $debet = $item->debet_lap;
            if ($qty_kredit > 0) {
                // --------PRODUK-------------------------------------------------------------
                if (!isset($srcProduk[$item->extern_id]['sum_qty_kredit'])) {
                    $srcProduk[$item->extern_id]['sum_qty_kredit'] = 0;
                }
                $srcProduk[$item->extern_id]['sum_qty_kredit'] += $qty_kredit;

                if (!isset($srcProduk[$item->extern_id]['sum_kredit'])) {
                    $srcProduk[$item->extern_id]['sum_kredit'] = 0;
                }
                $srcProduk[$item->extern_id]['sum_kredit'] += $kredit;
                // --------CUSTOMER-------------------------------------------------------------
                if (!isset($srcCustomer[$item->customer_id]['sum_debet'])) {
                    $srcCustomer[$item->customer_id]['sum_debet'] = 0;
                }
                $srcCustomer[$item->customer_id]['sum_debet'] += $debet;
                // ---------
                if (!isset($srcCustomer[$item->customer_id]['sum_kredit'])) {
                    $srcCustomer[$item->customer_id]['sum_kredit'] = 0;
                }
                $srcCustomer[$item->customer_id]['sum_kredit'] += $kredit;
                // --------MASTER-------------------------------------------------------------
                if (!isset($srcMaster[$item->master_id]['sum_debet'])) {
                    $srcMaster[$item->master_id]['sum_debet'] = 0;
                }
                $srcMaster[$item->master_id]['sum_debet'] += $debet;
                if (!isset($srcMaster[$item->master_id]['sum_kredit'])) {
                    $srcMaster[$item->master_id]['sum_kredit'] = 0;
                }
                $srcMaster[$item->master_id]['sum_kredit'] += $kredit;
                // --------SALESMAN-------------------------------------------------------------
                if (!isset($srcSales[$item->seller_id]['sum_debet'])) {
                    $srcSales[$item->seller_id]['sum_debet'] = 0;
                }
                $srcSales[$item->seller_id]['sum_debet'] += $debet;
                // ------------
                if (!isset($srcSales[$item->seller_id]['sum_kredit'])) {
                    $srcSales[$item->seller_id]['sum_kredit'] = 0;
                }
                $srcSales[$item->seller_id]['sum_kredit'] += $kredit;
                // --------CABANG-------------------------------------------------------------
                if (!isset($srcCabang[$item->cabang_id]['sum_debet'])) {
                    $srcCabang[$item->cabang_id]['sum_debet'] = 0;
                }
                $srcCabang[$item->cabang_id]['sum_debet'] += $debet;
                // ------------
                if (!isset($srcCabang[$item->cabang_id]['sum_kredit'])) {
                    $srcCabang[$item->cabang_id]['sum_kredit'] = 0;
                }
                $srcCabang[$item->cabang_id]['sum_kredit'] += $kredit;
                // ---------------------------------------------------------------------

                /* ------------------------------------
                     * untuk membuat potret tiap akhir bulan
                     * belom jadi lo ini
                     * --------------------------------------*/

                $condite_cek = array(
                    'fulldate' => $dtime_target,
                );
                // $this->db->where($condite_cek);
                // $this->lookupAll()->result();
                // showLast_query("merah");
                // break;

                // if($qty_kredit > 0){
                $produkIds[$produk_id] = $produk_id;
                $masterIds[$master_id] = $master_id;
                $masterProdukIds[$master_id][$produk_id] = $produk_id;
                $outraws[] = (array)$item;
            }
        }

        // cekMerah(sizeof($srcSales));
        // arrPrintPink($srcSales);
        // cekMerah(sizeof($srcMaster));
        // cekOrange(sizeof($outraws));
        // cekOrange(sizeof($srcProduk));
        // arrPrintPink($srcCustomer);
        // cekKuning(sizeof($srcProduk));
        // arrPrintPink($srcProduk);
        // arrPrintPink($srcMaster);
        // cekHijau(sizeof($masterIds));

        /* ----------------------------------------------------------
         * 582spo
         * ----------------------------------------------------------*/
        $condites = array(
            "rekening" => "466r",
            "qty_debet_lap >" => "0",
        );

        $this->db->where($condites);
        $this->db->where_in('master_id', $masterIds);
        // $this->db->group_by("extern_id");
        $this->db->order_by("id", "asc");
        // $this->db->where($condites);
        $src_spos = $this->fetchCache($reks);
        // showLast_query("kuning");
        // cekBiru(sizeof($src_spos));
        // arrPrintKuning($src_spos);
        // foreach ($masterProdukIds as $mastMaster_id => $mastExten_ids) {
        //     foreach ($mastExten_ids as $mastExten_id) {
        //         foreach ($src_spos as $item_spo) {
        //             //    qty_debet_lap
        //
        //             $spo_mast_id = $item_spo->master_id;
        //             $spo_ext_id = $item_spo->extern_id;
        //
        //             if ($spo_mast_id == $mastMaster_id && $spo_ext_id == $mastExten_id) {
        //
        //                 $spo_debet = $item_spo->debet_lap * 1;
        //                 $spo_datas[$item_spo->master_id][$item_spo->extern_id]['spo_qty_debet_lap'] = $item_spo->qty_debet_lap;
        //                 $spo_datas[$item_spo->master_id][$item_spo->extern_id]['spo_debet_lap'] = $spo_debet;
        //
        //                 // --------MASTER-------------------------------------------------------------
        //                 if (!isset($srcMaster[$item_spo->master_id]['sum_spo_debet'])) {
        //                     $srcMaster[$item_spo->master_id]['sum_spo_debet'] = 0;
        //                 }
        //                 $srcMaster[$item_spo->master_id]['sum_spo_debet'] += $spo_debet;
        //                 // --------CUSTOMER-------------------------------------------------------------
        //                 if (!isset($srcCustomer[$item_spo->customer_id]['sum_spo_debet'])) {
        //                     $srcCustomer[$item_spo->customer_id]['sum_spo_debet'] = 0;
        //                 }
        //                 $srcCustomer[$item_spo->customer_id]['sum_spo_debet'] += $spo_debet;
        //                 // // --------SALESMAN-------------------------------------------------------------
        //                 // if (!isset($srcSales[$item_spo->seller_id]['sum_spo_debet'])) {
        //                 //     $srcSales[$item_spo->seller_id]['sum_spo_debet'] = 0;
        //                 // }
        //                 // $srcSales[$item_spo->seller_id]['sum_spo_debet'] += $spo_debet;
        //                 // --------CABANG-------------------------------------------------------------
        //                 if (!isset($srcCabang[$item_spo->cabang_id]['sum_spo_debet'])) {
        //                     $srcCabang[$item_spo->cabang_id]['sum_spo_debet'] = 0;
        //                 }
        //                 $srcCabang[$item_spo->cabang_id]['sum_spo_debet'] += $spo_debet;
        //                 // ----------------------------------------------------------------
        //             }
        //
        //             // --------SALESMAN-------------------------------------------------------------
        //             if (!isset($srcSales[$item_spo->seller_id]['sum_spo_debet'])) {
        //                 $srcSales[$item_spo->seller_id]['sum_spo_debet'] = 0;
        //             }
        //             $srcSales[$item_spo->seller_id]['sum_spo_debet'] += $spo_debet;
        //
        //         }
        //     }
        // }
        foreach ($src_spos as $item_spo) {
            //    qty_debet_lap
            $spo_mast_id = $item_spo->master_id;
            $spo_ext_id = $item_spo->extern_id;
            $spo_debet = $item_spo->debet_lap * 1;
            $spo_datas[$item_spo->master_id][$item_spo->extern_id]['spo_qty_debet_lap'] = $item_spo->qty_debet_lap;
            $spo_datas[$item_spo->master_id][$item_spo->extern_id]['spo_debet_lap'] = $spo_debet;

            // --------MASTER-------------------------------------------------------------
            if (!isset($srcMaster[$item_spo->master_id]['sum_spo_debet'])) {
                $srcMaster[$item_spo->master_id]['sum_spo_debet'] = 0;
            }
            $srcMaster[$item_spo->master_id]['sum_spo_debet'] += $spo_debet;
            // --------CUSTOMER-------------------------------------------------------------
            if (!isset($srcCustomer[$item_spo->customer_id]['sum_spo_debet'])) {
                $srcCustomer[$item_spo->customer_id]['sum_spo_debet'] = 0;
            }
            $srcCustomer[$item_spo->customer_id]['sum_spo_debet'] += $spo_debet;
            // --------SALESMAN-------------------------------------------------------------
            if (!isset($srcSales[$item_spo->seller_id]['sum_spo_debet'])) {
                $srcSales[$item_spo->seller_id]['sum_spo_debet'] = 0;
            }
            $srcSales[$item_spo->seller_id]['sum_spo_debet'] += $spo_debet;
            // --------CABANG-------------------------------------------------------------
            if (!isset($srcCabang[$item_spo->cabang_id]['sum_spo_debet'])) {
                $srcCabang[$item_spo->cabang_id]['sum_spo_debet'] = 0;
            }
            $srcCabang[$item_spo->cabang_id]['sum_spo_debet'] += $spo_debet;
            // ----------------------------------------------------------------
        }
        // arrPrintWebs($spo_datas);

        /* -----------------------------------------------------------
        * produk spek
        * -----------------------------------------------------------*/
        // $produkIds = "";
        // $transaksiIds = "";
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $prSpeks = $pr->callSpecs($produkIds);
        // showLast_query("orange");
        // cekKuning(sizeof($produkIds));
        // cekKuning(sizeof($prSpeks));
        // arrPrintKuning($prSpeks);

        /* --------------------------------------------------------------------------
         * pengabungan
         * --------------------------------------------------------------------------*/
        $otproduk = array();
        foreach ($prSpeks as $produk_id => $prSpek) {

            $outProduk = $srcProduk[$produk_id];
            $otproduk[] = (array)$prSpek + $outProduk;

        }
        // cekHijau(sizeof($otproduk));
        // arrPrintHijau($otproduk);

        // arrPrintPink($srcMaster);
        foreach ($outraws as $outraw) {
            // arrPrintPink($outraw);
            // break;
            $prod_id = $outraw['extern_id'];
            $mast_id = $outraw['master_id'];
            $oleh_id = $outraw['oleh_id'];
            $seller_id = $outraw['seller_id'];
            $customer_id = $outraw['customer_id'];
            $cabang_id = $outraw['cabang_id'];
            $data_spo = isset($spo_datas[$mast_id][$prod_id]) ? $spo_datas[$mast_id][$prod_id] : array();
            // arrPrint($data_spo);
            // cekMerah(__LINE__);
            $otraws[] = (isset($prSpeks[$prod_id]) ? (array)$prSpeks[$prod_id] : array()) + $outraw + $data_spo;

            $otmasters[$mast_id] = $srcMaster[$mast_id] + $outraw;
            $otsales[$seller_id] = $srcSales[$seller_id] + $outraw;
            $otcustomer[$customer_id] = $srcCustomer[$customer_id] + $outraw;
            $otcabang[$cabang_id] = $srcCabang[$cabang_id] + $outraw;
            // break;
        }

        $srcs = array();
        $srcs['produk'] = $otproduk;
        $srcs['master'] = $otmasters;
        $srcs['salesman'] = $otsales;
        $srcs['customer'] = $otcustomer;
        $srcs['cabang'] = $otcabang;
        $srcs['raw'] = $otraws;
        // $srcs['customer'] = $otcustomer;
        // $srcs['pso'] = $otmaster;
        return $srcs;
    }
}