<?php


class ComRekeningPembantuRaw extends MdlMother
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
        "gudang_id",
        "gudang_nama",
        "debet_awal",
        "debet",
        "debet_akhir",
        "kredit_awal",
        "kredit",
        "kredit_akhir",
        "dtime_2",
        "dtime",
        "tgl",
        "bln",
        "thn",
        "extern_id",
        "extern_nama",
        "extern2_id",
        "extern2_nama",
        "extern3_id",
        "extern3_nama",
        "extern4_id",
        "extern4_nama",
        "jenis",
        "npwp",
        "fulldate",
        // "faktur",
        "toko_id",
        "toko_nama",
        "satuan_id",
        "satuan_nama",
        //-------------------
        "seller_id",
        "seller_nama",
        "oleh_top_id",
        "oleh_top_nama",
        "project_id",
        "project_nama",
        "workorder_id",
        "workorder_nama",
        "master_id",
        "produk_jenis_id",
        "produk_jenis_nama",
        "diskon",
        "diskon_persen",
        "pihak_id",
        "pihak_nama",
        "oleh_id",
        "oleh_nama",
        "oleh_top_id",
        "oleh_top_nama",
        "produk_id",
        "produk_nama",
        //-----
        "outdoor_id",
        "outdoor_nama",
        "outdoor_barcode",
        "outdoor_sku",
        "indoor_id_1",
        "indoor_nama_1",
        "indoor_barcode_1",
        "indoor_sku_1",
        "indoor_id_2",
        "indoor_nama_2",
        "indoor_barcode_2",
        "indoor_sku_2",
        "indoor_id_3",
        "indoor_nama_3",
        "indoor_barcode_3",
        "indoor_sku_3",
        "indoor_id_4",
        "indoor_nama_4",
        "indoor_barcode_4",
        "indoor_sku_4",
        "kategori_id",
        "kategori_nama",
        "produk_part_id_1",
        "produk_part_nama_1",
        "produk_part_barcode_1",
        "produk_part_id_2",
        "produk_part_nama_2",
        "produk_part_barcode_2",
        "heater_id",
        "heater_nama",
        "heater_barcode",
        //----------------
        "sales_admin_id",
        "sales_admin_nama",
        "salesman_id",
        "salesman_nama",
        "gudang_id_kirim",
        "gudang_nama_kirim",
        "delivery_id",
        "delivery_nama",
        "pengirim_id",
        "pengirim_nama",
        "pembayaran_nama",
        "harga_include_ppn",
        "produk_kode",
        "produk_jenis",
        "barcode",
        "diskon",
        "diskon_persen",
        "transaksi_id_1",
        "transaksi_no_1",
        "transaksi_id_2",
        "transaksi_no_2",
        "transaksi_id_3",
        "transaksi_no_3",
        "transaksi_id_4",
        "transaksi_no_4",
        "transaksi_id_5",
        "transaksi_no_5",
        "sub_harga",
        "sub_hpp",
        "sub_harga_include_ppn",
        "references_data",
        "sub_rugilaba",
        "references_data",
        "uang_muka_dipakai",
        "credit_note",
        "pph23",
        "pihak_tipe",
        "point_konsumen_nilai",
        "ppn_nilai_dibayar",
        "pph22_nilai",
        "nilai_biaya",
        "kelebihan_bayar",
        "kelebihan_bayar_nama",
        "deposit_konsumen",
        "pendapatan_lain_lain",
        //----------------
        "tagihan",
        "dibayar",
        "sisa",
        "transaksi_id_inv",
        "transaksi_no_inv",
        "sub_diskon",
        "sub_diskon_persen",
        "ppn_nilai",
        "sub_ppn_nilai",
        "diskon_1_id",
        "diskon_1_nama",
        "diskon_1_persen",
        "diskon_1_nilai",
        "sub_diskon_1_nilai",
        "diskon_2_id",
        "diskon_2_nama",
        "diskon_2_persen",
        "diskon_2_nilai",
        "sub_diskon_2_nilai",
        "diskon_3_id",
        "diskon_3_nama",
        "diskon_3_persen",
        "diskon_3_nilai",
        "sub_diskon_3_nilai",
        "diskon_4_id",
        "diskon_4_nama",
        "diskon_4_persen",
        "diskon_4_nilai",
        "sub_diskon_4_nilai",
        "diskon_5_id",
        "diskon_5_nama",
        "diskon_5_persen",
        "diskon_5_nilai",
        "sub_diskon_5_nilai",
        "diskon_6_id",
        "diskon_6_nama",
        "diskon_6_persen",
        "diskon_6_nilai",
        "sub_diskon_6_nilai",
        "diskon_7_id",
        "diskon_7_nama",
        "diskon_7_persen",
        "diskon_7_nilai",
        "sub_diskon_7_nilai",
        "harga_tandas",
        "harga_tandas_npph23",

        "tagihan_include_ppn",
        "ppn_nilai",
        "sub_ppn_nilai",
        "date_faktur",
        "nomor_faktur",
    );
    private $outFieldsMutasi = array( // dari tabel rek mutasi rekening
        "transaksi_id",
        "transaksi_no",
        "transaksi_jenis",
        "cabang_id",
        "cabang_nama",
        "gudang_id",
        "gudang_nama",
        "debet_awal",
        "debet",
        "debet_akhir",
        "kredit_awal",
        "kredit",
        "kredit_akhir",
        "extern_id",
        "extern_nama",
        "extern2_id",
        "extern2_nama",
        "extern3_id",
        "extern3_nama",
        "extern4_id",
        "extern4_nama",
        "npwp",
        "jenis",
        "dtime_2",
        "dtime",
        "fulldate",
        "keterangan",
        "faktur",
        "toko_id",
        "toko_nama",
        "hpp",
        "harga",
        "pihak_id",
        "pihak_nama",
        "oleh_id",
        "oleh_nama",
        "oleh_top_id",
        "oleh_top_nama",
        "produk_id",
        "produk_nama",
        "satuan_id",
        "satuan_nama",
        "rugilaba",
        //-------------------
        "seller_id",
        "seller_nama",
        "oleh_top_id",
        "oleh_top_nama",
        "project_id",
        "project_nama",
        "workorder_id",
        "workorder_nama",
        "master_id",
        "produk_jenis_id",
        "produk_jenis_nama",
        "diskon",
        "diskon_persen",
        "tipe_id",
        "tipe_nama",
        //-----
        "outdoor_id",
        "outdoor_nama",
        "outdoor_barcode",
        "outdoor_sku",
        "indoor_id_1",
        "indoor_nama_1",
        "indoor_barcode_1",
        "indoor_sku_1",
        "indoor_id_2",
        "indoor_nama_2",
        "indoor_barcode_2",
        "indoor_sku_2",
        "indoor_id_3",
        "indoor_nama_3",
        "indoor_barcode_3",
        "indoor_sku_3",
        "indoor_id_4",
        "indoor_nama_4",
        "indoor_barcode_4",
        "indoor_sku_4",
        "kategori_id",
        "kategori_nama",
        "produk_part_id_1",
        "produk_part_nama_1",
        "produk_part_barcode_1",
        "produk_part_id_2",
        "produk_part_nama_2",
        "produk_part_barcode_2",
        "heater_id",
        "heater_nama",
        "heater_barcode",
        //----------------
        "sales_admin_id",
        "sales_admin_nama",
        "salesman_id",
        "salesman_nama",
        "gudang_id_kirim",
        "gudang_nama_kirim",
        "delivery_id",
        "delivery_nama",
        "pengirim_id",
        "pengirim_nama",
        "pembayaran_nama",
        "harga_include_ppn",
        "produk_kode",
        "produk_jenis",
        "barcode",
        "diskon",
        "diskon_persen",
        "transaksi_id_1",
        "transaksi_no_1",
        "transaksi_id_2",
        "transaksi_no_2",
        "transaksi_id_3",
        "transaksi_no_3",
        "transaksi_id_4",
        "transaksi_no_4",
        "transaksi_id_5",
        "transaksi_no_5",
        "sub_harga",
        "sub_hpp",
        "sub_harga_include_ppn",
        "references_data",
        "sub_rugilaba",
        "references_data",
        "uang_muka_dipakai",
        "credit_note",
        "pph23",
        "pihak_tipe",
        "point_konsumen_nilai",
        "ppn_nilai_dibayar",
        "pph22_nilai",
        "nilai_biaya",
        "kelebihan_bayar",
        "kelebihan_bayar_nama",
        "deposit_konsumen",
        "pendapatan_lain_lain",
        //----------------
        "tagihan",
        "dibayar",
        "sisa",
        "transaksi_id_inv",
        "transaksi_no_inv",
        "sub_diskon",
        "sub_diskon_persen",
        "ppn_nilai",
        "sub_ppn_nilai",
        "diskon_1_id",
        "diskon_1_nama",
        "diskon_1_persen",
        "diskon_1_nilai",
        "sub_diskon_1_nilai",
        "diskon_2_id",
        "diskon_2_nama",
        "diskon_2_persen",
        "diskon_2_nilai",
        "sub_diskon_2_nilai",
        "diskon_3_id",
        "diskon_3_nama",
        "diskon_3_persen",
        "diskon_3_nilai",
        "sub_diskon_3_nilai",
        "diskon_4_id",
        "diskon_4_nama",
        "diskon_4_persen",
        "diskon_4_nilai",
        "sub_diskon_4_nilai",
        "diskon_5_id",
        "diskon_5_nama",
        "diskon_5_persen",
        "diskon_5_nilai",
        "sub_diskon_5_nilai",
        "diskon_6_id",
        "diskon_6_nama",
        "diskon_6_persen",
        "diskon_6_nilai",
        "sub_diskon_6_nilai",
        "diskon_7_id",
        "diskon_7_nama",
        "diskon_7_persen",
        "diskon_7_nilai",
        "sub_diskon_7_nilai",
        "harga_tandas",
        "harga_tandas_npph23",

        "tagihan_include_ppn",
        "ppn_nilai",
        "sub_ppn_nilai",
        "date_faktur",
        "nomor_faktur",
        "harga_bruto",
        "sub_harga_bruto",
        "hpp_riil",
        "sub_hpp_riil",
        "sub_nett1_include_ppn",
    );
//    private $periode = array("harian", "bulanan", "tahunan", "forever");
    private $periode = array("forever");
    protected $jenisTr;
    protected $tableInMaster;
    protected $main;
    protected $detail;


    public function __construct()
    {
        $this->load->helper("he_mass_table");
        $this->tableName = "_raw_rek_pembantu_cache";
        $this->tableName_master = array(
            "mutasi" => "_raw_rek_pembantu",
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

        arrPrintWebs($inParams);
//        matiHere();
        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            foreach ($this->periode as $periode) {
                $akumJml[$periode] = array( //==define validasi debet vs kredit seimbang
                    "kredit" => 0,
                    "debet" => 0,
                );
                $arrRekening = array();
                foreach ($this->inParams as $array_params) {
                    foreach ($array_params['loop'] as $key => $value) {
//                        $lCounter++;

//                        $msg3 = "transaksi gagal disimpan karena id toko anda tidak dikenali. Silahkan hubungi admin. code: " . __LINE__;
//                        $tokoID = isset($array_params['static']['toko_id']) ? $array_params['static']['toko_id'] : mati_disini($msg3);


                        $position = detectRekPosition($key, $value);
                        $value_item = trim($array_params['static']['harga']);
                        $unit = trim($array_params['static']['jml']);

                        $arrRekening[] = $key;
                        $table = heReturnTableName($this->tableName_master, $arrRekening);


                        $tableNameMutasi = $table[$key]["mutasi"];
                        $tableNameCache = $this->tableName;

                        // region cek jumlah kolom tabel cache dan mutasi
                        $fieldsCache = $this->db->list_fields($tableNameCache);
                        $fieldsMutasi = $this->db->list_fields($tableNameMutasi);
                        if ((sizeof($fieldsCache)) != (sizeof($fieldsMutasi))) {
                            cekHitam(sizeof($fieldsCache) . " ::: " . sizeof($fieldsMutasi));
                            arrPrint(array_diff($fieldsMutasi, $fieldsCache));
                            $msgdb = "Transaksi gagal disimpan karena struktur data tidak valid. Silahkan hubungi admin. code: " . __LINE__;
                            mati_disini($msgdb);
                        }
                        // endregion

//                        $_preValues = $this->cekPreValue(
//                        $tableNameMutasi,
                        $_preValues = $this->cekPreValueCache(
                            $tableNameCache,
                            $key,
                            $array_params['static']['cabang_id'],
//                            $array_params['static']['toko_id'],
                            $periode,
                            $array_params['static']['extern_id'],
//                            $array_params['static']['extern2_id'],
//                            $array_params['static']['extern3_id'],
//                            $array_params['static']['extern4_id'],
                            $array_params['static']['fulldate'],
                            $array_params['static']['master_id']
                        );


                        if (array_key_exists("id", $_preValues["cache"]) && ($_preValues["cache"]["id"] > 0)) {
                            $mode = "update";
                            $_preValues_id = $_preValues["cache"]["id"];
                        }
                        else {
                            $mode = "insert";
                            $_preValues_id = 0;

                        }
                        $fulldate = $array_params['static']['fulldate'];
                        $fulldate_ex = explode("-", $fulldate);
                        $tgl = $fulldate_ex[2];
                        $bln = $fulldate_ex[1];
                        $thn = $fulldate_ex[0];
                        $this->outParams[$lCounter]["mutasi"]["tgl"] = isset($fulldate) ? $tgl : date("d");
                        $this->outParams[$lCounter]["mutasi"]["bln"] = isset($fulldate) ? $bln : date("m");
                        $this->outParams[$lCounter]["mutasi"]["thn"] = isset($fulldate) ? $thn : date("Y");
                        $this->outParams[$lCounter]["mutasi"]["tabel_mutasi"] = $tableNameMutasi;

                        cekmerah("position rekening: $position");
                        $akumJml[$periode][$position] += abs($value);

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
                        $afterQtyNumber = $preQtyNumber + $unit;
                        $afterPosition = detectRekPosition($key, $afterNumber);
                        $afterQtyPosition = detectRekPosition($key, $afterQtyNumber);
//                        mati_disini("$key, $afterNumber, $tokoID, [$afterPosition]");
//                        cekHitam("after qty postition " . $afterQtyPosition);
//                        cekHitam("after potition " . $afterPosition);
//                        cekHitam("potition " . $position);

                        //  region cache rekening pembantu
                        $pakai_cache = 1;
                        if ($pakai_cache == 1) {
                            switch ($afterPosition) {
                                case "kredit":
                                    $this->outParams[$lCounter]["cache"][$mode]["kredit"] = abs($afterNumber);
                                    $this->outParams[$lCounter]["cache"][$mode]["debet"] = 0;
                                    break;
                                case "debet":
                                    $this->outParams[$lCounter]["cache"][$mode]["debet"] = abs($afterNumber);
                                    $this->outParams[$lCounter]["cache"][$mode]["kredit"] = 0;
                                    break;
                                default:
                                    mati_disini(__LINE__ . " gagal menentukan posisi rekening [$key] DEBET / KREDIT [$key, $afterNumber, $tokoID] " . __FUNCTION__ . " on file " . __FILE__);
                                    break;
                            }
                            switch ($afterQtyPosition) {
                                case "kredit":
                                    $this->outParams[$lCounter]["cache"][$mode]["qty_kredit"] = abs($afterQtyNumber);
                                    $this->outParams[$lCounter]["cache"][$mode]["qty_debet"] = 0;
                                    break;
                                case "debet":
                                    $this->outParams[$lCounter]["cache"][$mode]["qty_debet"] = abs($afterQtyNumber);
                                    $this->outParams[$lCounter]["cache"][$mode]["qty_kredit"] = 0;
                                    break;
                                default:
                                    mati_disini(__LINE__ . " gagal menentukan posisi rekening [$key] DEBET / KREDIT " . __FUNCTION__ . " on file " . __FILE__);
                                    break;
                            }
                            switch ($position) {
                                case "kredit":
                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_kredit"] = $_preValues['cache']['saldo_kredit'] + abs($value);
                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_debet"] = $_preValues['cache']['saldo_debet'];
                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_kredit"] = $_preValues['cache']['saldo_qty_kredit'] + abs($unit);
                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_debet"] = $_preValues['cache']['saldo_qty_kredit'];
                                    break;
                                case "debet":
                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_debet"] = $_preValues['cache']['saldo_debet'] + abs($value);
                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_kredit"] = $_preValues['cache']['saldo_kredit'];
                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_debet"] = $_preValues['cache']['saldo_qty_debet'] + abs($unit);
                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_kredit"] = $_preValues['cache']['saldo_qty_kredit'];
                                    break;
                                default:
                                    mati_disini(__LINE__ . " gagal menentukan posisi rekening [$key] DEBET / KREDIT " . __FUNCTION__ . " on file " . __FILE__);
                                    break;
                            }


                            $this->outParams[$lCounter]["cache"][$mode]["rek_id"] = createRekCode($key, $array_params['static']['extern_id']);
                            $this->outParams[$lCounter]["cache"][$mode]["rekening"] = $key;
                            $this->outParams[$lCounter]["cache"][$mode]["periode"] = $periode;
                            $this->outParams[$lCounter]["cache"][$mode]["id"] = $_preValues_id;
                            $this->outParams[$lCounter]["cache"][$mode]["tableNameCache"] = $tableNameCache;

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
                                            $this->outParams[$lCounter]["mutasi"]["debet_awal"] = $_preValues["cache"]["debet"];
                                            $this->outParams[$lCounter]["mutasi"]["debet_akhir"] = abs($afterNumber);
                                            $this->outParams[$lCounter]["mutasi"]["kredit_awal"] = $_preValues["cache"]["kredit"];
                                            $this->outParams[$lCounter]["mutasi"]["kredit_akhir"] = 0;
                                            break;
                                        default:
                                            $this->outParams[$lCounter]["mutasi"]["debet_awal"] = $_preValues["cache"]["debet"];
                                            $this->outParams[$lCounter]["mutasi"]["debet_akhir"] = 0;

                                            $this->outParams[$lCounter]["mutasi"]["kredit_awal"] = $_preValues["cache"]["kredit"];
                                            $this->outParams[$lCounter]["mutasi"]["kredit_akhir"] = 0;
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
                                        case "debet":
                                            $this->outParams[$lCounter]["mutasi"]["debet"] = abs($value);
                                            $this->outParams[$lCounter]["mutasi"]["kredit"] = 0;
                                            $this->outParams[$lCounter]["mutasi"]["qty_debet"] = abs($unit);
                                            $this->outParams[$lCounter]["mutasi"]["qty_kredit"] = 0;
                                            // saldo bawah
                                            $this->outParams[$lCounter]["mutasi"]["saldo_debet"] = $_preValues['cache']['saldo_debet'] + abs($value);
                                            $this->outParams[$lCounter]["mutasi"]["saldo_kredit"] = $_preValues['cache']['saldo_kredit'];
                                            $this->outParams[$lCounter]["mutasi"]["saldo_qty_debet"] = $_preValues['cache']['saldo_qty_debet'] + abs($unit);
                                            $this->outParams[$lCounter]["mutasi"]["saldo_qty_kredit"] = $_preValues['cache']['saldo_qty_kredit'];
                                            break;
                                        case "kredit":
                                            $this->outParams[$lCounter]["mutasi"]["debet"] = 0;
                                            $this->outParams[$lCounter]["mutasi"]["kredit"] = abs($value);
                                            $this->outParams[$lCounter]["mutasi"]["qty_debet"] = 0;
                                            $this->outParams[$lCounter]["mutasi"]["qty_kredit"] = abs($unit);
                                            // saldo bawah
                                            $this->outParams[$lCounter]["mutasi"]["saldo_debet"] = $_preValues['cache']['saldo_debet'];
                                            $this->outParams[$lCounter]["mutasi"]["saldo_kredit"] = $_preValues['cache']['saldo_kredit'] + abs($value);
                                            $this->outParams[$lCounter]["mutasi"]["saldo_qty_debet"] = $_preValues['cache']['saldo_qty_debet'];
                                            $this->outParams[$lCounter]["mutasi"]["saldo_qty_kredit"] = $_preValues['cache']['saldo_qty_kredit'] + abs($unit);
                                            break;
                                        default:
                                            die(lgShowAlert("Transaksi gagal, karena rekening $key gagal menentukan posisi DEBET/KREDIT."));
                                            break;
                                    }

                                    foreach ($array_params['static'] as $key_static_mutasi => $value_static_mutasi) {
                                        if (in_array($key_static_mutasi, $this->outFieldsMutasi)) {
//                                            cekHijau($key_static_mutasi . "");
                                            $this->outParams[$lCounter]["mutasi"][$key_static_mutasi] = $value_static_mutasi;
                                        }
                                    }
                                    $this->outParams[$lCounter]["mutasi"]["rek_id"] = createRekCode($key, $array_params['static']['extern_id']);
                                    $this->outParams[$lCounter]["mutasi"]["rekening"] = $key;

                                    //region -- counter basic rekening
                                    $this->main["extern_id"] = $array_params['static']['extern_id'];

                                    $ccn = new CounterNumber();
                                    $ccn->setJenisTr($this->jenisTr);
                                    $ccn->setTransaksiGate($this->tableInMaster);
                                    $ccn->setMainGate($this->main);
                                    $ccn->setRekening($key);
//                                    $new_counter = $ccn->getCounterNumberRekeningPembantu();
////                            arrPrintHijau($new_counter);
//                                    foreach ($new_counter["main"] as $ckey => $cval) {
//                                        $this->outParams[$lCounter]["mutasi"][$ckey] = $cval;
//                                    }
                                    $addData = array(
                                        "jenisTr",
                                        "jenisTrMaster",
                                        "stepCode",
                                        "supplierID",
                                        "customerID",
                                        "olehID",
                                        "sellerID",
                                    );
                                    foreach ($addData as $dval) {
                                        $this->outParams[$lCounter]["mutasi"][$dval] = isset($this->main[$dval]) ? $this->main[$dval] : "0";
                                    }
                                    //endregion -- counter basic rekening
                                    break;
                            }
                        }
                        //  endregion mutasi rekening pembantu

                        $pakai_exec = 1;
                        if ($pakai_exec == 1) {
                            $insertIDs = array();
                            if (sizeof($this->outParams) > 0) {
                                foreach ($this->outParams as $lCounter => $pSpec) {
                                    foreach ($pSpec as $mode => $pSpec_mode) {
//                                        arrPrintPink($pSpec_mode);
                                        switch ($mode) {
                                            case "cache":
                                                foreach ($pSpec_mode as $sub_mode => $pSpec_mode_data) {
                                                    $id = $pSpec_mode_data["id"];
                                                    unset($pSpec_mode_data["id"]);

                                                    $tableName = $pSpec_mode_data["tableNameCache"];
                                                    unset($pSpec_mode_data["tableNameCache"]);

//                                arrPrint($pSpec_mode_data);
                                                    switch ($sub_mode) {
                                                        case "insert":
                                                            $this->db->insert($tableName, $pSpec_mode_data);
                                                            $insertIDs[] = $this->db->insert_id();
                                                            cekBiru($this->db->last_query());
                                                            break;
                                                        case "update":
//                                        cekHijau(":: UPDATE :: $id ::");

                                                            $this->db->where('id', $id);
                                                            $this->db->update($tableName, $pSpec_mode_data);
                                                            cekOrange($this->db->last_query());
                                                            break;
                                                    }
                                                }

                                                break;
                                            case "mutasi":
//                                                arrPrintHijau($pSpec_mode);
                                                $tableName_mutasi = $pSpec_mode["tabel_mutasi"];
                                                unset($pSpec_mode["tabel_mutasi"]);
                                                $this->db->insert($tableName_mutasi, $pSpec_mode);
                                                $insertIDs[] = $this->db->insert_id();
                                                cekHijau($this->db->last_query());
                                                break;
                                        }
                                    }
                                }
                                $this->outParams = array();
                                // matiHEre(__LINE__." ".__FUNCTION__);
                                if (sizeof($insertIDs) == 0) {
                                    cekMerah("::: PERIODE : $periode :::");
                                    return false;
                                }
                            }
                            else {
                                return false;
                            }
                        }
                    }
                }
            }
        }


//        mati_disini(get_class($this));
        if (sizeof($insertIDs) > 0) {
//        if (sizeof($this->outParams) > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    private function cekPreValue($tableNameMutasi, $rek, $cabang_id, $toko_id, $periode, $pihak_id, $fulldate)
    {
        if ($fulldate != NULL) {
            $fulldate_ex = explode("-", $fulldate);
            $tgl = $fulldate_ex[2];
            $bln = $fulldate_ex[1];
            $thn = $fulldate_ex[0];
        }
        else {
            $tgl = date("d");
            $bln = date("m");
            $thn = date("Y");
        }

        $this->filters = array();
//        switch ($periode) {
//            case "harian":
//                $this->addFilter("tgl='$tgl'");
//                $this->addFilter("bln='$bln'");
//                $this->addFilter("thn='$thn'");
//                break;
//            case "bulanan":
//                $this->addFilter("bln='$bln'");
//                $this->addFilter("thn='$thn'");
//                break;
//            case "tahunan":
//                $this->addFilter("thn='$thn'");
//                break;
//            case "forever":
//                break;
//        }

        $this->addFilter("rekening='$rek'");
        $this->addFilter("cabang_id='$cabang_id'");
//        $this->addFilter("periode='$periode'");
//        $this->addFilter("toko_id='$toko_id'");
//        $this->addFilter("extern_id='$pihak_id'");
//        $this->addFilter("extern2_id='$extern2_id'");
//        $this->addFilter("extern3_id='$extern3_id'");
//        $this->addFilter("extern4_id='$extern4_id'");
        $result = array();
        $localFilters = array();
        if (sizeof($this->filters) > 0) {
            foreach ($this->filters as $f) {
                $tmpArr = explode("=", $f);
                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");

            }
        }
        $query = $this->db->select()
            ->from($tableNameMutasi)
            ->where($localFilters)
            ->limit(1)
            ->order_by('id', 'desc')
            ->get_compiled_select();
//        $this->db->order_by('id', 'DESC');
        $tmp = $this->db->query("{$query} FOR UPDATE")->result();
        showLast_query("biru");
        cekUngu("pre value: " . sizeof($tmp));
//        mati_disini(__LINE__ . " :: TEST...");


        if (sizeof($tmp) > 0) {
            // bila count($tmp) > 0, maka ambil saldo periode sendiri, dan mode update
            foreach ($tmp as $row) {
                $result["cache"] = array(
                    "id" => $row->id,
                    "debet" => $row->debet_akhir,
                    "kredit" => $row->kredit_akhir,
                    "qty_debet" => $row->qty_debet_akhir,
                    "qty_kredit" => $row->qty_kredit_akhir,

                    "saldo_debet" => $row->saldo_debet,
                    "saldo_kredit" => $row->saldo_kredit,
                    "saldo_qty_debet" => $row->saldo_qty_debet,
                    "saldo_qty_kredit" => $row->saldo_qty_kredit,
                );
            }
        }
        else {
            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                // bila count($tmp) == 0, maka ambil saldo periode forever dan mode insert
                $this->filters = array();
                $this->addFilter("rekening='$rek'");
                $this->addFilter("cabang_id='$cabang_id'");
                $this->addFilter("periode='forever'");
                $this->addFilter("extern_id='$pihak_id'");
                $this->addFilter("toko_id='$toko_id'");
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
                        );
                    }
                }
                else {
                    $result["cache"] = array(
                        "debet" => 0,
                        "kredit" => 0,
                    );
                }
            }
            else {
                $result["cache"] = array(
                    "debet" => 0,
                    "kredit" => 0,
                    "qty_debet" => 0,
                    "qty_kredit" => 0,
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

//        $tableName = $this->tableName;
//        $tableName_mutasi = $this->tableName_mutasi;

//        $insertIDs = array();
//        if (sizeof($this->outParams) > 0) {
//            foreach ($this->outParams as $lCounter => $pSpec) {
//                foreach ($pSpec as $mode => $pSpec_mode) {
//
//                    switch ($mode) {
//                        case "cache":
//                            foreach ($pSpec_mode as $sub_mode => $pSpec_mode_data) {
//                                $id = $pSpec_mode_data["id"];
//                                unset($pSpec_mode_data["id"]);
//
//                                $tableName = $pSpec_mode_data["tableNameCache"];
//                                unset($pSpec_mode_data["tableNameCache"]);
//
////                                arrPrint($pSpec_mode_data);
//                                switch ($sub_mode) {
//                                    case "insert":
//                                        $this->db->insert($tableName, $pSpec_mode_data);
//                                        $insertIDs[] = $this->db->insert_id();
//                                        cekBiru($this->db->last_query());
//                                        break;
//                                    case "update":
////                                        cekHijau(":: UPDATE :: $id ::");
//
//                                        $this->db->where('id', $id);
//                                        $this->db->update($tableName, $pSpec_mode_data);
//                                        cekOrange($this->db->last_query());
//                                        break;
//                                }
//                            }
//
//                            break;
//                        case "mutasi":
//                            $tableName_mutasi = $pSpec_mode["tabel_mutasi"];
//                            unset($pSpec_mode["tabel_mutasi"]);
//                            $this->db->insert($tableName_mutasi, $pSpec_mode);
//                            $insertIDs[] = $this->db->insert_id();
//                            cekHijau($this->db->last_query());
//                            break;
//                    }
//                }
//            }
//            // matiHEre(__LINE__." ".__FUNCTION__);
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
                "rekening" => $rek,
                "extern_id" => $externID
            )
        );
        $this->db->order_by("id", "asc");


        $criteria = array();
//        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
//            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
//        if ($criteria2 != "") {
//            $this->db->where($criteria2);
//        }


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

    public function fetchMovesAll($rek)
    {
        //==memanggil saldo2 dari rekening tertentu
        $tableNames = heReturnTableName($this->tableName_master, array($rek));
        $this->db->select("*");
        $this->db->where(array("rekening" => $rek));
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


        return $result->result();
    }

    //--------------------------------------------------
//    private function cekPreValueCache($tableName, $rek, $cabang_id, $toko_id, $periode, $pihak_id, $fulldate, $master_id)
    private function cekPreValueCache($tableName, $rek, $cabang_id, $periode, $extern_id, $fulldate, $master_id)
    {
        if ($fulldate != NULL) {
            $fulldate_ex = explode("-", $fulldate);
            $tgl = $fulldate_ex[2];
            $bln = $fulldate_ex[1];
            $thn = $fulldate_ex[0];
        }
        else {
            $tgl = date("d");
            $bln = date("m");
            $thn = date("Y");
        }

        $this->filters = array();
//        switch ($periode) {
//            case "harian":
//                $this->addFilter("tgl='$tgl'");
//                $this->addFilter("bln='$bln'");
//                $this->addFilter("thn='$thn'");
//                break;
//            case "bulanan":
//                $this->addFilter("bln='$bln'");
//                $this->addFilter("thn='$thn'");
//                break;
//            case "tahunan":
//                $this->addFilter("thn='$thn'");
//                break;
//            case "forever":
//                break;
//        }

        $this->addFilter("master_id='$master_id'");
        $this->addFilter("rekening='$rek'");
        $this->addFilter("cabang_id='$cabang_id'");
        $this->addFilter("periode='$periode'");
        $this->addFilter("extern_id='$extern_id'");
//        $this->addFilter("toko_id='$toko_id'");
//        $this->addFilter("extern2_id='$extern2_id'");
//        $this->addFilter("extern3_id='$extern3_id'");
//        $this->addFilter("extern4_id='$extern4_id'");
        $result = array();
        $localFilters = array();
        if (sizeof($this->filters) > 0) {
            foreach ($this->filters as $f) {
                $tmpArr = explode("=", $f);
                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");

            }
        }
        $query = $this->db->select()
            ->from($tableName)
            ->where($localFilters)
            ->limit(1)
            ->order_by('id', 'desc')
            ->get_compiled_select();
//        $this->db->order_by('id', 'DESC');
        $tmp = $this->db->query("{$query} FOR UPDATE")->result();
        showLast_query("biru");
//        mati_disini(__LINE__ . " :: TEST...");


        if (sizeof($tmp) > 0) {
            // bila count($tmp) > 0, maka ambil saldo periode sendiri, dan mode update
            foreach ($tmp as $row) {
                $result["cache"] = array(
                    "id" => $row->id,
                    "debet" => $row->debet,
                    "kredit" => $row->kredit,
                    "qty_debet" => $row->qty_debet,
                    "qty_kredit" => $row->qty_kredit,

                    "saldo_debet" => $row->saldo_debet,
                    "saldo_kredit" => $row->saldo_kredit,
                    "saldo_qty_debet" => $row->saldo_qty_debet,
                    "saldo_qty_kredit" => $row->saldo_qty_kredit,
                );
            }
        }
        else {
            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                // bila count($tmp) == 0, maka ambil saldo periode forever dan mode insert
                $this->filters = array();
                $this->addFilter("rekening='$rek'");
                $this->addFilter("cabang_id='$cabang_id'");
                $this->addFilter("periode='forever'");
                $this->addFilter("extern_id='$pihak_id'");
                $this->addFilter("toko_id='$toko_id'");
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
                        );
                    }
                }
                else {
                    $result["cache"] = array(
                        "debet" => 0,
                        "kredit" => 0,
                    );
                }
            }
            else {
                $result["cache"] = array(
                    "debet" => 0,
                    "kredit" => 0,
                    "qty_debet" => 0,
                    "qty_kredit" => 0,
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

    public function fetchBalancesMutasi($rek, $key = "", $sortBy = "", $sortMode = "DESC")
    {//==memanggil saldo2 dari rekening tertentu
        $tableNamesResult = heReturnTableName($this->tableName_master, array($rek));
        $tableNames = $tableNamesResult[$rek]["mutasi"];
        $this->db->select("*");
        $this->db->where(
            array(
                "rekening" => $rek
            )
        );

        if ($sortBy != "") {
            $this->db->order_by($sortBy, $sortMode);
        }
        else {
            $this->db->order_by("id", "DESC");
        }
        $this->db->limit(1);

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


        $result = $this->db->get($tableNames);
        showLast_query("biru");

//        $results = array();
//        if (sizeof($result->result()) > 0) {
//            foreach ($result->result() as $row) {
//                $results[] = array(
//                    "id" => $row->extern_id,
//                    "rek_id" => $row->rek_id,
//                    "name" => $row->extern_nama,
//                    "debet" => $row->debet,
//                    "kredit" => $row->kredit,
//                    "qty_debet" => $row->qty_debet,
//                    "qty_kredit" => $row->qty_kredit,
//                );
//            }
//        }

        // yang direturn hasil dari tabel, apa adanya...
        return $result->result();

    }

    public function fetchMovesAllExtern($rek, $extern_id = NULL, $extern2_id = NULL, $extern3_id = NULL, $extern4_id = NULL)
    {
        $arrFilterExtern["rekening"] = $rek;
        if ($extern_id != NULL) {
            $arrFilterExtern["extern_id"] = $extern_id;
        }
        if ($extern2_id != NULL) {
            $arrFilterExtern["extern2_id"] = $extern2_id;
        }
        if ($extern3_id != NULL) {
            $arrFilterExtern["extern3_id"] = $extern3_id;
        }
        if ($extern4_id != NULL) {
            $arrFilterExtern["extern4_id"] = $extern4_id;
        }

        //==memanggil saldo2 dari rekening tertentu
        $tableNames = heReturnTableName($this->tableName_master, array($rek));
        /* --------------------------------------------------------------------------
         * * (bintang)  dimatikan supaya bisa dioverwite dari pengunanya
         * --------------------------------------------------------------------------*/
        // $this->db->select("*");
        // $this->db->where($arrFilterExtern);
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

        $result = $this->db->get($tableNames[$rek]['mutasi'])->result();

        return $result;
    }

//     public function updateData($where,$data, $rek){
// // cekMerah($rek ."||". $this->tableNames[$rek]);
//
//         $this->db->where($where);
//         $this->db->update($this->tableName[$rek], $data);
//
//         return true;
//     }
//
//     public function tableNames($rek){
//         $tableNames = heReturnTableName($this->tableName_master, array($rek));
//
//         $var = $tableNames[$rek]['mutasi'];
//
//         return $var;
//     }
}