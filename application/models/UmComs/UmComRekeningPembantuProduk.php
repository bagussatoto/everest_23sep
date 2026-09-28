<?php


class UmComRekeningPembantuProduk extends MdlMother
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

        "extern3_id",
        "extern3_nama",

        "extern4_id",
        "extern4_nama",

        "jenis",
        "npwp",
        "fulldate",
        "gudang_id",
        "gudang_nama",
        "harga",
        "harga_avg",
        "harga_awal",
        "produk_id",
        "produk_nama",
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
        "extern3_id",
        "extern3_nama",
        "extern4_id",
        "extern4_nama",
        "jenis",
        "npwp",
        "fulldate",
        "gudang_id",
        "gudang_nama",
        "keterangan",
        "harga",
        "harga_avg",
        "harga_awal",
        "produk_id",
        "produk_nama",
    );
    private $periode = array("harian", "bulanan", "tahunan", "forever");
    protected $jenisTr;
    protected $sortBy = array(
        "kolom" => "id",
        "mode" => "desc",
    );
    protected $dateRangeOld;

    public function getDateRangeOld()
    {
        return $this->dateRangeOld;
    }

    public function setDateRangeOld($dateRangeOld)
    {
        $this->dateRangeOld = $dateRangeOld;
    }


    public function __construct()
    {
        $this->tableName_fifoAvg = "fifo_avg";
        $this->tableName = "_rek_pembantu_produk_cache";
        $this->tableName_master = array(
            "mutasi" => "_rek_pembantu_produk",
            //            "cache" => "_rek_pembantu_produk_cache",
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
        cekBiru($inParams);
        //         matiHere(__LINE__);
        $this->load->helper("he_mass_table");
        $configBalanceProtections = $this->config->item("accountBalanceProtections");
        $this->inParams = $inParams;

        /* ---------------------------------------------------------------------------------
         * [] khusus untuk develop, normnya jalan tanpa []
         * ---------------------------------------------------------------------------------*/
        // $this->inParams[] = $inParams;

        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            foreach ($this->periode as $periode) {
                foreach ($this->inParams as $array_params) {
                    $arrRekening = array();
                    foreach ($array_params['loop'] as $key => $x) {
                        $rekName = fetchAccountStructureAlias()[$key];

                        $value_item = trim($array_params['static']['produk_nilai']);
                        $unit = trim($array_params['static']['produk_qty']);

                        $value = $x;
                        $position = detectRekPosition($key, $value);

                        $arrRekening[] = $key;
                        $table = heReturnTableName($this->tableName_master, $arrRekening);
                        $this->tableName_mutasi = $table[$key]["mutasi"];
                        //                        $this->tableName = $table[$key]["cache"];

                        //                        $msg = "Silahkan memilih dahulu produk sebelum melanjutkan transaksi.";
                        //                        $externID = (isset($array_params['static']['extern_id']) && ($array_params['static']['extern_id'] > 0)) ? $array_params['static']['extern_id'] : die(lgShowAlert("$msg"));

                        $_preValues = $this->cekPreValue($key, $array_params['static']['cabang_id'], $periode, $array_params['static']['extern_id'], $array_params['static']['gudang_id'],$array_params['static']['produk_id']);
                        //                        $_preValues = $this->cekPreValue($key, $array_params['static']['cabang_id'], $periode, $externID, $array_params['static']['gudang_id']);

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

                        $afterNumber = $preNumber + $value;
                        $afterQtyNumber = $preQtyNumber + $unit;

                        $afterPosition = detectRekPosition($key, $afterNumber);
                        $afterQtyPosition = detectRekPosition($key, $afterQtyNumber);


                        $afterNumberAvg = $afterQtyNumber == 0 ? 0 : $afterNumber / $afterQtyNumber;

                        cekhitam(":: $afterNumber, val: $value,  $afterPosition " . __LINE__ . " " . __METHOD__);
                        //                        if (in_array($key, $configBalanceProtections)) {
                        //                            if ($afterPosition != detectRekDefaultPosition($key) && ($afterQtyNumber != 0) && (round($afterNumber, 2) != 0)) {
                        //                                $pName = $array_params['static']['extern_nama'];
                        //                                $pId = $array_params['static']['extern_id'];
                        //
                        //                                cekOrange("insufficient balance for $key. You requested $value. Available: $preNumber <hr>" . $afterPosition . " != " . detectRekDefaultPosition($key) . " && ($afterQtyNumber != 0)");
                        //                                die(lgShowAlert("insufficient balance for $key, #$pId $pName. *You requested $value. Available: $preNumber"));
                        //                            }
                        //                        }

                        if (in_array($key, $configBalanceProtections)) {
                            if ($afterPosition != detectRekDefaultPosition($key) && ($afterQtyNumber != 0) && (round($afterNumber, 2) != 0)) {
                                //                            if ($afterPosition != detectRekDefaultPosition($key) && ($afterQtyNumber != 0)) {
                                $pName = $array_params['static']['extern_nama'];
                                $pId = $array_params['static']['extern_id'];
                                //                                cekOrange("Persediaan tidak cukup untuk $key. Permintaan $value. Tersedia: $preNumber <hr>" . $afterPosition . " != " . detectRekDefaultPosition($key) . " && ($afterQtyNumber != 0)");
                                //                                die(lgShowAlert("Persediaan tidak cukup untuk $key, #$pId $pName. Permintaan: $value. Tersedia: $preNumber"));
                                die(lgShowAlert("Saldo rekening $rekName $pId $pName tidak cukup. Anda membutuhkan $value, tersedia $preNumber. Silahkan menghubungi admin."));
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
                                                //                                                matiHere();
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

    private function cekPreValue($rek, $cabang_id, $periode, $extern_id, $gudang_id,$produk_id)
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
        $this->addFilter("extern_id='$extern_id'");
        $this->addFilter("produk_id='$produk_id'");
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

        //  region mengambil saldo dari rek_cache
        //        $this->db->where($criteria);
        //        $tmp = $this->db->get($this->tableName)->result();
        //        cekHitam($this->db->last_query() . " # " . count($tmp));
        $localFilters = array();
        if (sizeof($this->filters) > 0) {
            foreach ($this->filters as $f) {
                $tmpArr = explode("=", $f);
                //                    $localFilters[$tmpArr[0]]=$tmpArr[1];
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
            $this->addFilter("extern_id='$extern_id'");
            $this->addFilter("produk_id='$produk_id'");

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
            $localFilters = array();
            if (sizeof($this->filters) > 0) {
                foreach ($this->filters as $f) {
                    $tmpArr = explode("=", $f);
                    //                    $localFilters[$tmpArr[0]]=$tmpArr[1];
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
    {
        //==memanggil saldo2 dari rekening tertentu
        /* -----------------------------------------
         * $this->load->helper("he_mass_table");
         * helper ini diload di controler ya tidak perlu masuk auto load
         * */

        $tableNames = heReturnTableName($this->tableName_master, array($rek));
        $this->db->select("*");
        $this->db->where(array("extern_id" => $externID, "transaksi_id !=" => '0'));
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
    {
        //==memanggil saldo2 dari rekening tertentu
        return $this->fetchMovement($rek);
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
            $this->db->order_by("id", "desc");
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

        $result = $this->db->get($tableNames[$rek]['mutasi'])->result();
        // cekkuning($this->db->last_query());
        // cekKuning(sizeof($result));

        return $result;
    }

    private function fetchMovementOLD($rek)
    {
        $this->db2 = $this->load->database('dbOLD', TRUE);
        // cekMerah($rek);
        $reks["010304"] = "persediaan produk";
        $reke = $reks[$rek];
        //==memanggil saldo2 dari rekening tertentu
        $tableNames = heReturnTableName($this->tableName_master, array($reke));
        // arrPrintHijau($tableNames);
        // cekHijau(" $reke") ;
        // $this->db->select("*");
        // $this->db->where(array("extern_id" => $externID));
        // $this->db2->where($condites);

        if(isset($this->dateRangeOld)){
            $this->db2->where($this->dateRangeOld);
        }

        if (isset($this->jenisTr) && is_array($this->jenisTr)) {
            $this->db2->where_in("jenis", $this->jenisTr);
        }
        else {
            $condites = array(
                "jenis" => $this->jenisTr,
            );
            $this->db2->where($condites);
        }
        if (isset($this->sortBy)) {
            $this->db2->order_by($this->sortBy['kolom'], $this->sortBy['mode']);
        }
        else {
            $this->db->order_by("id", "desc");
        }
        $all_columns = $this->db2->list_fields($tableNames[$reke]['mutasi']);
        $blackList_column = array(
            "cabang_nama",
            "gudang_nama",
            "r_move",
        );
        $columns = array_diff($all_columns, $blackList_column);
        $this->db2->select($columns);

        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db2->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db2->where($criteria2);
        }

        $result = $this->db2->get($tableNames[$reke]['mutasi'])->result();
        // cekHijau($this->db2->last_query());
        // cekBiru(sizeof($result));
        // matiDisini(__LINE__);
        // return $result->result_array();
        return $result;
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
        // $srcPersediaanOlds = $this->fetchMovementOLD($rek);

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

        //         $srcPersediaanOlds = $this->fetchMovementOLD($rek);
        //         foreach ($srcPersediaanOlds as $srcPersediaanOld) {
        //             $produkIds[$srcPersediaanOld->extern_id] = $srcPersediaanOld->extern_id;
        //             $transaksiIds[$srcPersediaanOld->transaksi_id] = $srcPersediaanOld->transaksi_id;
        //         }
        // arrPrint(sizeof($transaksiIds));ntOLD($rek);
        //         foreach ($srcPersediaanOlds as $srcPersediaanOld) {
        //             $produkIds[$srcPersediaanOld->extern_id] = $srcPersediaanOld->extern_id;
        //             $transaksiIds[$srcPersediaanOld->transaksi_id] = $srcPersediaanOld->transaksi_id;
        //         }
        // arrPrint(sizeof($transaksiIds));

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
        // showLast_query("hijau");
        // cekBiru(sizeof($srcs));
        if(sizeof($srcs) > 0){

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
            $masterDataDiskon = array();
            foreach ($srcPersediaans as $srcPersediaan) {
                $prId = $srcPersediaan->extern_id;
                $trId = $srcPersediaan->transaksi_id;
                $trSpekDiskon = isset($trSpeks[$trId][$prId]) ? $trSpeks[$trId][$prId] : array();
                $m_total_disc = isset($trSpekDiskon['m_total_disc']) ? $trSpekDiskon['m_total_disc'] : 0;
                $m_nomer = isset($trSpekDiskon['nomer']) ? $trSpekDiskon['nomer'] : "";
                $dataDiskon = array(
                    "kode" => "",
                    "no_part" => "",
                    "kendaraan_nama" => "",
                    "satuan" => "",
                    "nama" => "diskon",
                    "nomer" => $m_nomer,
                    "oleh_nama" => isset($trSpekDiskon['oleh_nama']) ? $trSpekDiskon['oleh_nama'] : "",
                    "dtime" => isset($trSpekDiskon['dtime']) ? $trSpekDiskon['dtime'] : "",
                    "cabang_nama" => isset($trSpekDiskon['cabang_nama']) ? $trSpekDiskon['cabang_nama'] : "",
                    "customers_nama" => isset($trSpekDiskon['customers_nama']) ? $trSpekDiskon['customers_nama'] : "",
                    "i_sub_jual_nppn" => $m_total_disc * -1,
                    "i_harga_nett1" => 0,
                    "qty_kredit" => 0,
                    "qty_debet" => 0,
                );
                // arrPrint($dataDiskon);
                // mati_disini();
                // $dataBaru = (array)$srcPersediaan + (array)$prSpeks[$prId] + (array)$trSpeks[$trId];
                $dataBaru = (array)$srcPersediaan + (array)(isset($prSpeks[$prId]) ? $prSpeks[$prId] : array()) + (array)(isset($trSpeks[$trId][$prId]) ? $trSpeks[$trId][$prId] : array());
                $masterData[] = $dataBaru;
                if ($m_total_disc > 0 && $trId) {
                    $masterDataDiskon[$trId] = $dataDiskon + $dataBaru;

                }
            }
            $masterDataBaru = array_merge($masterData, $masterDataDiskon);

            $vars = array();
            $vars['data'] = $masterDataBaru;
            $vars['data_jml']['total'] = sizeof($srcPersediaans);
            $vars['data_jml']['produk'] = sizeof($produkIds);
            $vars['data_jml']['transaksi'] = sizeof($transaksiIds);
        }
        else{
            $vars = array();
            $vars['data'] = array();
            $vars['data_jml']['total'] = 0;
            $vars['data_jml']['produk'] = 0;
            $vars['data_jml']['transaksi'] = 0;
        }


        //arrPrintWebs($srcPersediaans);
        //arrPrintWebs($masterDataBaru);
        //arrPrintWebs($produkIds);


        return $vars;
    }

    public function callMovementProdukOLD($rek)
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
        // $srcPersediaanOlds = $this->fetchMovementOLD($rek);

        // $srcPersediaans = $this->fetchMovement($rek);
        // $produkIds = array();
        // $transaksiIds = array();
        // foreach ($srcPersediaans as $src) {
        //     // $produkIds[] = $src->extern_id;
        //     // $transaksiIds[] = $src->transaksi_id;
        //     $produkIds[$src->extern_id] = $src->extern_id;
        //     $transaksiIds[$src->transaksi_id] = $src->transaksi_id;
        // }
        // showLast_query("biru");
        // arrPrintPink($srcPersediaans);
        // arrPrintKuning($transaksiIds);
        // cekBiru(sizeof($srcPersediaans));
        // matiHere(__LINE__);

        $srcPersediaans = $this->fetchMovementOLD($rek);
        foreach ($srcPersediaans as $srcPersediaanOld) {
            $produkIds[$srcPersediaanOld->extern_id] = $srcPersediaanOld->extern_id;
            $transaksiIds[$srcPersediaanOld->transaksi_id] = $srcPersediaanOld->transaksi_id;
        }
        // cekHijau(sizeof($srcPersediaans));
        // arrPrint(sizeof($transaksiIds));
        // matiHere(__LINE__);
        if(sizeof($srcPersediaans) > 0){

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
            $this->load->model("MdlTransaksiOLD");
            $tr = new MdlTransaksiOLD();
            // $trSpeks = $tr->callSpecs($transaksiIds);
            // arrPrintWebs($trSpeks);
            /* ------------------------------------------------------------------------------------------
             * spek produk harus ditambahin data dari registri juga, kalau hanya dari persediaan tidak bisa
             * mendapatkan harga jual
             * ------------------------------------------------------------------------------------------*/
            $koloms = array(
                // "_company_cabangID_modul_subModul_jenisTr_stepCode_gudang2ID", "company_id", "modul", "subModul", "transaksi_id", "items2", "items2_sum", "itemSrc", "itemSrc_sum", "items3", "items3_sum", "items4", "items4_sum", "items_noapprove", "rsltItems", "rsltItems2", "rsltItems3", "rsltItems3_sub", "tableIn_master", "tableIn_detail", "tableIn_detail2_sum", "tableIn_detail_rsltItems", "tableIn_detail_rsltItems2", "tableIn_master_values", "tableIn_detail_values", "tableIn_detail_values_rsltItems", "tableIn_detail_values_rsltItems2", "tableIn_detail_values2_sum", "rsltItems3_sub", "main_add_values", "main_add_fields", "main_elements", "main_inputs", "main_inputs_orig", "receiptDetailFields", "receiptSumFields", "receiptDetailFields2", "receiptDetailSrcFields", "receiptSumFields2", "jurnal_index", "postProcessor", "preProcessor", "revert", "items_komposisi", "jurnalItems", "componentsBuilder", "items5_sum", "items6_sum", "items7_sum", "items8_sum", "items9_sum", "items10_sum",
                "rsltItems3_sub", "rsltItems_revert", "rsltItems2_revert"
            );
            $tr->setBlockFields($koloms);
            $srcs = $tr->lookupTransaksiDataRegistries($transaksiIds)->result();
            /*-----------debug harus dari model/transaksiOLD------------------------------------*/
            // cekPink(sizeof($srcs));
            // arrPrintPink($srcs);
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

                // matiDisini(__LINE__);

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
            // cekMerah(sizeof($trSpeks));
            // --------------------------------------------------------------------------------

            // matiHere(__LINE__);
            $masterDataDiskon = array();
            foreach ($srcPersediaans as $srcPersediaan) {
                $prId = $srcPersediaan->extern_id;
                $trId = $srcPersediaan->transaksi_id;
                $trSpekDiskon = isset($trSpeks[$trId][$prId]) ? $trSpeks[$trId][$prId] : array();
                $m_total_disc = isset($trSpekDiskon['m_total_disc']) ? $trSpekDiskon['m_total_disc'] : 0;
                $m_nomer = isset($trSpekDiskon['nomer']) ? $trSpekDiskon['nomer'] : "";
                $dataDiskon = array(
                    "kode" => "",
                    "no_part" => "",
                    "kendaraan_nama" => "",
                    "satuan" => "",
                    "nama" => "diskon",
                    "nomer" => $m_nomer,
                    "oleh_nama" => isset($trSpekDiskon['oleh_nama']) ? $trSpekDiskon['oleh_nama'] : "",
                    "dtime" => isset($trSpekDiskon['dtime']) ? $trSpekDiskon['dtime'] : "",
                    "cabang_nama" => isset($trSpekDiskon['cabang_nama']) ? $trSpekDiskon['cabang_nama'] : "",
                    "customers_nama" => isset($trSpekDiskon['customers_nama']) ? $trSpekDiskon['customers_nama'] : "",
                    "i_sub_jual_nppn" => $m_total_disc * -1,
                    "i_harga_nett1" => 0,
                    "qty_kredit" => 0,
                    "qty_debet" => 0,
                );
                // arrPrint($dataDiskon);
                // mati_disini();
                // $dataBaru = (array)$srcPersediaan + (array)$prSpeks[$prId] + (array)$trSpeks[$trId];
                $dataBaru = (array)$srcPersediaan + (array)(isset($prSpeks[$prId]) ? $prSpeks[$prId] : array()) + (array)(isset($trSpeks[$trId][$prId]) ? $trSpeks[$trId][$prId] : array());
                $masterData[] = $dataBaru;
                if ($m_total_disc > 0 && $trId) {
                    $masterDataDiskon[$trId] = $dataDiskon + $dataBaru;

                }
            }
            $masterDataBaru = array_merge($masterData, $masterDataDiskon);

            $vars = array();
            $vars['data'] = $masterDataBaru;
            $vars['data_jml']['total'] = sizeof($srcPersediaans);
            $vars['data_jml']['produk'] = sizeof($produkIds);
            $vars['data_jml']['transaksi'] = sizeof($transaksiIds);


            //arrPrintWebs($srcPersediaans);
            //arrPrintWebs($masterDataBaru);
            //arrPrintWebs($produkIds);
        }
        else{
            $vars = array();
            $vars['data'] = array();
            $vars['data_jml']['total'] = 0;
            $vars['data_jml']['produk'] = 0;
            $vars['data_jml']['transaksi'] = 0;
        }


        return $vars;
    }

    public function fetchLastMove($jenis = "")
    {

        $blackList_column = array(
            "cabang_nama",
            "gudang_nama",
            "r_move",
        );

        $condites = array(
            "jenis" => "467",
        );
        $selectPembelian = array(
            // "sum(qty_kredit) as 'unit_af'",
            // "sum(kredit) as 'nilai_af'",
            "month(dtime) as 'bl'",
            "year(dtime) as 'th'",
            "id",
            "dtime",
            "extern_id as 'subject_id'",
        );
        $this->db->select($selectPembelian);
        $this->db->where($condites);
        // $this->db->group_by("extern_id");
        $this->db->order_by("id,th,bl", "asc");
        $this->db->where($condites);
        //        $src_0 = $this->db->get("__rek_pembantu_produk__persediaan_produk")->result();
        $src_0 = $this->db->get("__rek_pembantu_produk__010304")->result();
        // cekLime(sizeof($src_0));
        foreach ($src_0 as $item) {
            $src[$item->subject_id]['dtime_last'] = $item->dtime;
        }

        return $src;
    }

    /* =====================================================================
* untuk memadukan mutasi tansa transaksi onal, diexecusi dr controler CLI::insertMutasiProduk
* == seharusnya sebelum insert harus bisa deteksi sudah ada atau belum transaksi pada bulan tersebut
* =====================================================================*/
    public function fetchLastMoves()
    {
        $this->load->helper("he_mass_table");

        //        $rek = "persediaan produk";
        $rek = "010304";
        $tableNames = heReturnTableName($this->tableName_master, array($rek))[$rek]['mutasi'];
        // arrPrint($tableNames);
        $vars = $this->db->get($tableNames);

        return $vars;
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
        $arrExternID = $externID < 1 ? array() : array("extern_id" => $externID);
        $this->db->select("*");
        $this->db->where(
            array(
                // "extern_id" => $externID,
                "periode" => $periode,
                "rekening" => $rek,
            ) + $arrExternID
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