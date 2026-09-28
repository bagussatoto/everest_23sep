<?php


class ComRekeningPembantuAntarcabang extends MdlMother
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
        //        "debet_awal",
        "debet",
        //        "debet_akhir",
        //        "kredit_awal",
        "kredit",
        //        "kredit_akhir",
        "dtime",
        "tgl",
        "bln",
        "thn",
        //        "cabang2_id",
        //        "cabang2_nama",
        "extern_id",
        "extern_nama",
        "jenis",
        "npwp",
        "fulldate",
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
        "dtime",
        //        "cabang2_id",
        //        "cabang2_nama",
        "extern_id",
        "extern_nama",
        "jenis",
        "npwp",
        "fulldate",
        "keterangan",
    );
//    private $periode = array("harian", "bulanan", "tahunan", "forever");
    private $periode = array("bulanan", "tahunan", "forever");


    public function __construct()
    {
        $this->tableName = "_rek_pembantu_antarcabang_cache";
        $this->tableName_master = array(
            "mutasi" => "_rek_pembantu_antarcabang",
            //            "cache" => "_rek_pembantu_antarcabang_cache",
        );
        $this->accountMinusAllowedJenisTr = $this->config->item("accountMinusAllowedJenisTr") != NULL ? $this->config->item("accountMinusAllowedJenisTr") : array();
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
        $this->inParams = $inParams;
        $configBalanceProtections = $this->config->item("accountBalanceProtections");
//arrPrintPink($this->inParams);
//arrPrintKuning($this->accountMinusAllowedJenisTr);
//        $starttime = microtime(true);
        if (sizeof($this->inParams['loop']) > 0) {
            $lCounter = 0;
            foreach ($this->periode as $periode) {
                $arrRekening = array();
                foreach ($this->inParams['loop'] as $key => $value) {
                    $lCounter++;

                    $position = detectRekPosition($key, $value);

                    $arrRekening[] = $key;
                    $table = heReturnTableName($this->tableName_master, $arrRekening);
                    $this->tableName_mutasi = $table[$key]["mutasi"];
                    $key_alias = isset(fetchAccountStructureAlias()[$key]) ? fetchAccountStructureAlias()[$key] : $key;

                    //  region cek saldo awal
                    $_preValues = $this->cekPreValue($key, $inParams['static']['cabang_id'], $periode, $inParams['static']['extern_id']);
//                    cekLime($this->db->last_query());
//                    $endtime = microtime(true);
//                    $exectime = $endtime-$starttime;
//                    cekHitam("exectime ".$exectime);
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

                    // ada potensi selisih desimal (bisa negatif dan positif)
                    $afterNumber = $preNumber + $value;
                    $afterNumber_bulat = (($afterNumber < 0) && ($afterNumber > -1)) ? ceil($afterNumber) : $afterNumber;
//                    $afterNumber_bulat = $afterNumber;
                    cekHere("afterNumber_bulat: $afterNumber_bulat");
                    $afterPosition = detectRekPosition($key, $afterNumber_bulat);


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
                                mati_disini(__LINE__ . " gagal menentukan posisi rekening DEBET / KREDIT " . __FUNCTION__ . " on file " . __FILE__);
                                break;
                        }
                        switch ($position) {
                            case "kredit":
                                $this->outParams[$lCounter]["cache"][$mode]["saldo_kredit"] = $_preValues["cache"]["saldo_kredit"] + abs($value);
//                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_debet"] = 0;
                                $this->outParams[$lCounter]["cache"][$mode]["saldo_kredit_periode"] = $_preValues["cache"]["saldo_kredit_periode"] + abs($value);
//                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_debet_periode"] = 0;
                                break;
                            case "debet":
                                $this->outParams[$lCounter]["cache"][$mode]["saldo_debet"] = $_preValues["cache"]["saldo_debet"] + abs($value);
//                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_kredit"] = 0;
                                $this->outParams[$lCounter]["cache"][$mode]["saldo_debet_periode"] = $_preValues["cache"]["saldo_debet_periode"] + abs($value);
//                                    $this->outParams[$lCounter]["cache"][$mode]["saldo_kredit_periode"] = 0;
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
//                    arrPrint($this->outParams);
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


                                //  region validasi saldo, tidak boleh minus
//                                if ($this->outParams[$lCounter]["mutasi"]["nilai_af"] < 0) {
//                                    mati_disini(__LINE__ . " terjadi kesalahan pada saldo piutang cabang, saldo piutang bernilai minus. " . __CLASS__ . " :: " . __FUNCTION__);
//                                }
                                //  endregion validasi saldo, tidak boleh minus


                                $allowMinus = false;
                                if (in_array($key, $this->accountMinusAllowedJenisTr["rekening"])) {
                                    $allowMinus = true;
                                }
                                else {
                                    $allowMinus = false;
                                }
//                                if (in_array($inParams['static']['jenis'], $this->accountMinusAllowedJenisTr["jenisTransaksi"])) {
//                                }
//                                else {
//                                    $allowMinus = false;
//                                }
                                if ($allowMinus == true) {
//                                    mati_disini("boleh allow minus $allowMinus, $key :: " . $inParams['static']['jenis']);
                                }
                                else {
                                    if (in_array($key, $configBalanceProtections)) {
                                        if ($afterPosition != detectRekDefaultPosition($key) && ($afterNumber_bulat != 0)) {
                                            cekMerah("after position: $afterPosition, after number: $afterNumber_bulat");
//                                        die(lgShowAlert("insufficient balance for $key."));
                                            die(lgShowAlert("saldo rekening $key_alias tidak cukup ($preNumber). silahkan diperiksa kembali."));
                                        }

                                    }
                                }

                                break;
                        }
                    }
                    //  endregion mutasi rekening pembantu
                }

            }
        }

//        $endtime = microtime(true);
//        $exectime = $endtime-$starttime;
//        cekHitam("exectime ".$exectime);
        if (sizeof($this->outParams) > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    private function cekPreValue($rek, $cabang_id, $periode, $pihakId)
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
        $this->addFilter("extern_id='$pihakId'");
//
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
//showLast_query("kuning");
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
                    // saldo bawah
                    "saldo_debet" => $row->saldo_debet,
                    "saldo_kredit" => $row->saldo_kredit,
                    "saldo_debet_periode" => $row->saldo_debet_periode,
                    "saldo_kredit_periode" => $row->saldo_kredit_periode,
                );
            }
        }
        else {
            // bila count($tmp) == 0, maka ambil saldo periode forever dan mode insert
            $this->filters = array();
            $this->addFilter("rekening='$rek'");
            $this->addFilter("cabang_id='$cabang_id'");
            $this->addFilter("periode='forever'");
            $this->addFilter("extern_id='$pihakId'");
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
                        "debet" => $row->debet,
                        "kredit" => $row->kredit,
                        // saldo bawah
                        "saldo_debet" => $row->saldo_debet,
                        "saldo_kredit" => $row->saldo_kredit,
                        "saldo_debet_periode" => 0,
                        "saldo_kredit_periode" => 0,
                    );
                }
            }
            else {
                $result["cache"] = array(
                    "debet" => 0,
                    "kredit" => 0,
                    // saldo bawah
                    "saldo_debet" => 0,
                    "saldo_kredit" => 0,
                    "saldo_debet_periode" => 0,
                    "saldo_kredit_periode" => 0,
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
                "periode" => $periode,
                "rekening" => $rek,
            )
        );
        if($externID != ""){
            $this->db->where("extern_id", $externID);
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


        $result = $this->db->get($this->tableName);

        return $result->result();
    }



    /**
     * Build query from profile config.
     * $mode: 'data' | 'total' | 'filtered'
     * Return: array('db'=>$db, 'activeCols'=>$activeCols)
     */
    public function build($cfg, $opts = array(), $dtPost = array(), $mode = 'data', $columns=array())
    {
        $db = $this->db;
        $db->reset_query();

        // 1) FROM + base join + base where (support :placeholder)
        $this->applyBase($db, $cfg, $opts);

        // 2) resolve columns (columns+layout) or fallback legacy select
        $resolved    = $this->resolveColumns($cfg, $opts, $columns);
        $activeCols  = $resolved['activeCols'];
        $needModules = $resolved['needModules'];
//        arrPrintWebs($activeCols);
        // 3) modules join (enabled by cfg/modules override/required by columns)
        $this->applyModules($db, $cfg, $opts, $needModules);

        // 4) SELECT from active columns (or legacy)
        $this->applySelect($db, $cfg, $activeCols);

        // 5) common filters (opsional)
        $this->applyCommonFilters($db, $opts);

        // 6) DataTables apply
        if ($mode === 'filtered') {
            // search only
            $this->applyDt($db, $cfg, $dtPost, $activeCols, true, false, false);
        }
        elseif ($mode === 'data') {
            // search + order + limit
            $this->applyDt($db, $cfg, $dtPost, $activeCols, true, true, true);
        }
        else {
            // total: nothing
        }

        return array('db' => $db, 'activeCols' => $activeCols);
    }
    public function build2($cfg, $opts = array(), $dtPost = array(), $mode = 'data')
    {
        $db = $this->db;
        $db->reset_query();

        // 1) FROM + base join + base where (support :placeholder)
        $this->applyBase($db, $cfg, $opts);

        // 2) resolve columns (columns+layout) or fallback legacy select
        $resolved    = $this->resolveColumns($cfg, $opts);
        $activeCols  = $resolved['activeCols'];
        $needModules = $resolved['needModules'];

        // 3) modules join (enabled by cfg/modules override/required by columns)
        $this->applyModules($db, $cfg, $opts, $needModules);

        // 4) SELECT from active columns (or legacy)
        $this->applySelect($db, $cfg, $activeCols);

        // 5) common filters (opsional)
        $this->applyCommonFilters($db, $opts);

        // 6) DataTables apply
        if ($mode === 'filtered') {
            // search only
            $this->applyDt($db, $cfg, $dtPost, $activeCols, true, false, false);
        }
        elseif ($mode === 'data') {
            // search + order + limit
            $this->applyDt($db, $cfg, $dtPost, $activeCols, true, true, true);
        }
        else {
            // total: nothing
        }

        return array('db' => $db, 'activeCols' => $activeCols);
    }

    /**
     * Datatables response: recordsTotal, recordsFiltered, data
     */
    public function datatable($cfg, $dtPost, $opts = array(), $columns=array())
    {
        // TOTAL
        $qTotal = $this->build($cfg, $opts, $dtPost, 'total', $columns);
        $recordsTotal = $this->countQuery($qTotal['db']);

        // FILTERED
        $qFiltered = $this->build($cfg, $opts, $dtPost, 'filtered', $columns);
        $recordsFiltered = $this->countQuery($qFiltered['db']);

        // DATA
        $qData = $this->build($cfg, $opts, $dtPost, 'data', $columns);
        $rows = $qData['db']->get()->result_array();

        // post-process optional (boleh kamu hapus kalau gak perlu)
        $start = isset($dtPost['start']) ? (int)$dtPost['start'] : 0;
        $no = $start + 1;
        foreach ($rows as &$row) {
            if (!isset($row['no'])) $row['no'] = $no++;
            if (!isset($row['pId']) && isset($row['extern_id'])) $row['pId'] = $row['extern_id'];

            // contoh aliasing tipe produk (sesuaikan key kamu)
            if (!isset($row['tipe_produk'])) {
                $js = null;
                if (isset($row['jml_serial'])) $js = (int)$row['jml_serial'];
                elseif (isset($row['serial_available_count'])) $js = (int)$row['serial_available_count'];
                $row['tipe_produk'] = ($js !== null && $js > 0) ? 'serial' : 'generik';
            }

        }
        unset($row);

        return array(
            'recordsTotal' => (int)$recordsTotal,
            'recordsFiltered' => (int)$recordsFiltered,
            'data' => $rows,
            'last_query' => $qData['db']->last_query(),
        );
    }

    protected function replaceSqlPlaceholders($sql, $opts, $db)
    {
        if (!is_string($sql) || $sql === '') return $sql;

        return preg_replace_callback('/:([a-zA-Z_][a-zA-Z0-9_]*)/', function($m) use ($opts, $db) {
            $k = $m[1];
            if (!isset($opts[$k])) return $m[0];
            return $db->escape($opts[$k]);
        }, $sql);
    }


    /* ============================================================
     *  BASE: FROM / JOIN / WHERE
     * ============================================================ */
    protected function applyBase($db, $cfg, $opts)
    {
        if (empty($cfg['base']['from'])) {
            throw new Exception("Config missing base.from");
        }

        //$db->from($cfg['base']['from']);
        $from = $this->replaceSqlPlaceholders($cfg['base']['from'], $opts, $db);
        $db->from($from);

        // base joins: each is [table, on, type, escape?]
        if (!empty($cfg['base']['joins'])) {
            foreach ($cfg['base']['joins'] as $j) {
//                $table  = $j[0];
//                $on     = isset($j[1]) ? $j[1] : '';
                $table  = $this->replaceSqlPlaceholders($j[0], $opts, $db);
                $on     = isset($j[1]) ? $this->replaceSqlPlaceholders($j[1], $opts, $db) : '';
                $type   = isset($j[2]) ? $j[2] : 'left';
                $escape = isset($j[3]) ? (bool)$j[3] : true;

                $db->join($table, $on, $type, $escape);
            }
        }

        // base where supports placeholders ":key" => take from $opts['key']
        if (!empty($cfg['base']['where'])) {
            foreach ($cfg['base']['where'] as $k => $v) {
                if (is_string($v) && strlen($v) > 0 && $v[0] === ':') {
                    $optKey = substr($v, 1);
                    if (isset($opts[$optKey])) $db->where($k, $opts[$optKey]);
                } else {
                    $db->where($k, $v);
                }
            }
        }
    }

    protected function applyCommonFilters($db, $opts)
    {
        // optional convenience
        if (isset($opts['cabang_id']) && $opts['cabang_id'] !== null) {
            $db->where('cabang_id', (int)$opts['cabang_id']);
        }
//        if (isset($opts['gudang_id']) && $opts['gudang_id'] !== null) {
//            $db->where('s.gudang_id', (int)$opts['gudang_id']);
//        }
    }

    /* ============================================================
     *  COLUMNS + LAYOUT RESOLVE
     * ============================================================ */
    public function getDtColumns($cfg, $opts = array())
    {
        $resolved = $this->resolveColumns($cfg, $opts);
        $activeCols = $resolved['activeCols'];

        $out = array();

        // kalau kamu mau ada kolom "No"
        $out[] = array(
            'data' => 'no',
            'title' => 'No',
            'orderable' => false,
            'searchable' => false
        );

        foreach ($activeCols as $c) {
            $out[] = array(
                'data' => $c['key'],          // key di result_array()
                'title' => $c['label'],       // label hasil resolve (bisa berubah karena mode debet/kredit)
                'orderable' => !empty($c['order']),
                'searchable' => !empty($c['search']),
            );
        }

        return $out;
    }

    protected function resolveColumns($cfg, $opts, $columns)
    {
        $activeCols  = array();
        $needModules = array();

        $colOrder = array_values($columns); // pastikan numeric list urutan
        $extraIdx = count($colOrder);   // start index untuk kolom tambahan (di belakang)

        // New style: columns + layout
        if (!empty($cfg['columns']) && !empty($cfg['layout'])) {
            foreach ($cfg['layout'] as $key) {
                if (!isset($cfg['columns'][$key])) continue;

                $num = array_search($key, $colOrder, true);

                // kalau tidak terdaftar, taruh di belakang (unik, tidak overwrite)
                if ($num === false) {
                    $pos = $extraIdx++;
                } else {
                    $pos = $num;
                }

                $c = $cfg['columns'][$key];
                $mode = isset($opts['mode']) ? $opts['mode'] : null;
                if ($mode === 'kredit') {
                    if ($key === 'qty')    $c['select'] = 'r_ppc.qty_kredit:qty';
                    if ($key === 'amount') $c['select'] = 'r_ppc.kredit:amount';
                }
                elseif ($mode === 'debet') {
                    if ($key === 'qty')    $c['select'] = 'r_ppc.qty_debet:qty';
                    if ($key === 'amount') $c['select'] = 'r_ppc.debet:amount';
                }
                // toggle group qty/rp by flag
                if (!empty($c['flag']) && $c['flag'] === 'qty' && isset($opts['show_qty']) && !$opts['show_qty']) continue;
                if (!empty($c['flag']) && $c['flag'] === 'rp'  && isset($opts['show_rp'])  && !$opts['show_rp'])  continue;
                // optional permission callback: function($key,$col,$opts){ return true/false; }
                if (!empty($opts['can_view_col']) && is_callable($opts['can_view_col'])) {
                    if (!$opts['can_view_col']($key, $c, $opts)) continue;
                }
                // modules required by columns
                if (!empty($c['requires']) && is_array($c['requires'])) {
                    foreach ($c['requires'] as $m) $needModules[$m] = true;
                }
                $activeCols[$pos] = array(
                    'key'      => $key,
                    'label'    => isset($c['label']) ? $c['label'] : $key,
                    'select'   => isset($c['select']) ? $c['select'] : null,   // "expr:alias"
                    'search'   => isset($c['search']) ? $c['search'] : null,
                    'order'    => isset($c['order']) ? $c['order'] : null,     // "p.kode" or "alias" or "(expr)"
                    'requires' => isset($c['requires']) ? $c['requires'] : array(),
                );
            }
        }

        ksort($activeCols); // penting: rapihin sesuai index pos
        $activeCols = array_values($activeCols); // optional: jadi 0..n-1

        // Legacy fallback: cfg['select'] (alias => fields)
        if (empty($activeCols) && !empty($cfg['select'])) {
            foreach ($cfg['select'] as $alias => $fields) {
                foreach ($fields as $f) {
                    $activeCols[] = array(
                        'key'    => $this->inferKeyFromSelect($f),
                        'label'  => $this->inferKeyFromSelect($f),
                        'select' => $this->normalizeSelect($alias, $f), // already "a.col AS x"
                        'search' => null,
                        'order'  => null,
                        'requires' => array(),
                    );
                }
            }
        }

        return array(
            'activeCols' => $activeCols,
            'needModules' => $needModules,
        );
    }

    protected function inferKeyFromSelect($field)
    {
        if (strpos($field, ':') !== false) {
            $parts = explode(':', $field);
            return trim(end($parts));
        }
        return trim($field);
    }

    /* ============================================================
     *  MODULES
     * ============================================================ */
    protected function applyModules($db, $cfg, $opts, $needModules)
    {
        if (empty($cfg['modules'])) return;

        foreach ($cfg['modules'] as $name => $m) {
            $enabled = !empty($m['enabled']);

            // allow override from opts.modules
            if (isset($opts['modules']) && is_array($opts['modules']) && array_key_exists($name, $opts['modules'])) {
                $enabled = (bool)$opts['modules'][$name];
            }

            // auto-enable if required by selected columns
            if (!$enabled && isset($needModules[$name])) $enabled = true;

            if (!$enabled) continue;

            // join module (subquery) - escape=false important
            if (!empty($m['join'])) {
                $j      = $m['join'];
                $table  = $j['sql'];
                $on     = $j['on'];
                $type   = isset($j['type']) ? $j['type'] : 'left';
                $db->join($table, $on, $type, false);
            }

            // compatibility: module select (kalau kamu masih pakai cara lama)
            // Kalau sudah columns+layout, lebih bagus definisikan select module di columns.
            if (!empty($m['select']) && empty($cfg['columns'])) {
                foreach ($m['select'] as $sf) {
                    $db->select($this->normalizeRawSelect($sf), false);
                }
            }
        }
    }

    /* ============================================================
     *  SELECT
     * ============================================================ */
    protected function applySelect($db, $cfg, $activeCols)
    {
        $selects = array();

        // New style: use columns[*].select (raw "expr:alias")
        if (!empty($cfg['columns']) && !empty($cfg['layout'])) {
            foreach ($activeCols as $c) {
                if (empty($c['select'])) continue;
                $selects[] = $this->normalizeRawSelect($c['select']);
            }
        }
        else {
            // Legacy: already normalized in resolveColumns
            foreach ($activeCols as $c) {
                if (empty($c['select'])) continue;
                $selects[] = $c['select'];
            }
        }

        if (empty($selects)) $selects[] = '1 AS dummy';
        $db->select(implode(",\n", $selects), false);
    }

    /* ============================================================
     *  DATATABLES APPLY
     * ============================================================ */
    protected function applyDt($db, $cfg, $dtPost, $activeCols, $applySearch, $applyOrder, $applyLimit)
    {
        $dtCfg = isset($cfg['datatable']) ? $cfg['datatable'] : array();

        // Search: prefer activeCols[*].search, fallback dtCfg.search_like
        if ($applySearch) {
            $sv = isset($dtPost['search']['value']) ? trim($dtPost['search']['value']) : '';
            if ($sv !== '') {
                $searchCols = array();

                foreach ($activeCols as $c) {
                    if (!empty($c['search'])) $searchCols[] = $c['search'];
                }
                if (empty($searchCols) && !empty($dtCfg['search_like'])) {
                    $searchCols = $dtCfg['search_like'];
                }

                if (!empty($searchCols)) {
                    $db->group_start();
                    $first = true;
                    foreach ($searchCols as $col) {
                        if ($first) { $db->like($col, $sv); $first = false; }
                        else { $db->or_like($col, $sv); }
                    }
                    $db->group_end();
                }
            }
        }

//        arrPrintWebs($activeCols);
//        arrPrint($dtPost['order'][0]);
//        die();
        // Order: index-based mapping from activeCols (layout order)
        if ($applyOrder && !empty($dtPost['order'][0])) {
            $idx = (int)$dtPost['order'][0]['column']-1;
            $dir = (isset($dtPost['order'][0]['dir']) && strtolower($dtPost['order'][0]['dir']) === 'desc') ? 'DESC' : 'ASC';

            $orderExpr = null;

            if (isset($activeCols[$idx]) && !empty($activeCols[$idx]['order'])) {
                $orderExpr = $activeCols[$idx]['order'];
            }
            elseif (!empty($dtCfg['order_map']) && isset($dtCfg['order_map'][$idx])) {
                // fallback legacy order_map
                $orderExpr = $dtCfg['order_map'][$idx];
            }

            if ($orderExpr) {
                // escape=false is CRUCIAL for alias/expression
                $db->order_by($orderExpr, $dir, false);
            }
        }
        else {
            if (!empty($dtCfg['default_order'])) {
                foreach ($dtCfg['default_order'] as $col => $dir) {
                    $db->order_by($col, $dir, false);
                }
            }
        }

        // Limit
        if ($applyLimit) {
            $start  = isset($dtPost['start']) ? (int)$dtPost['start'] : 0;
            $length = isset($dtPost['length']) ? (int)$dtPost['length'] : 10;
            if ($length > 0) $db->limit($length, $start);
        }
    }

    /* ============================================================
     *  COUNT SAFE (wrapper subquery)
     * ============================================================ */
    public function countQuery($db)
    {
        $sql = $db->get_compiled_select('', false);

        // remove ORDER BY + LIMIT if any
        $sql = preg_replace('/\s+ORDER\s+BY\s+[\s\S]+$/i', '', $sql);
        $sql = preg_replace('/\s+LIMIT\s+\d+(\s*,\s*\d+)?\s*$/i', '', $sql);

        $q = $this->db->query("SELECT COUNT(*) AS cnt FROM ($sql) x");
        $row = $q->row_array();
        return isset($row['cnt']) ? (int)$row['cnt'] : 0;
    }

    /* ============================================================
     *  NORMALIZERS
     * ============================================================ */
    public function normalizeSelect($alias, $field)
    {
        // supports "field:alias" OR "expr:alias"
        if (strpos($field, ':') !== false) {
            list($f, $as) = explode(':', $field, 2);
            $f = trim($f); $as = trim($as);

            // if looks like expression, treat as raw
            if (preg_match('/[\(\)\s]/', $f)) {
                return $f . " AS " . $as;
            }
            return $alias . "." . $f . " AS " . $as;
        }
        return $alias . "." . trim($field);
    }

    public function normalizeRawSelect($expr)
    {
        // supports "expr:alias"
        if (strpos($expr, ':') !== false) {
            list($e, $as) = explode(':', $expr, 2);
            return trim($e) . " AS " . trim($as);
        }
        return $expr;
    }

    public function getSerialCountMap($prod,$rek,$opts)
    {
        if (empty($prod)) return array();

        $pids = array();
        foreach ($prod as $rw) {
            if (!empty($rw['pId'])) $pids[] = (int)$rw['pId'];
        }

        $pids = array_values(array_unique($pids));

        $this->db->select('produk_nama, extern2_nama, produk_id, gudang_id, COUNT(1) AS cnt', false);
        $this->db->from('_rek_pembantu_produk_perserial_cache'); // sesuaikan tabel kamu
        $this->db->where('qty_debet >', 0);
        $this->db->where('rekening', $rek);
        $this->db->where('cabang_id', $opts['cabang_id']);
        $this->db->where_in('produk_id', $pids);
        $this->db->group_by('produk_id, cabang_id, gudang_id');

        $rows = $this->db->get()->result_array();
        $map = array();
        foreach ($rows as $r) {
            $pid = (int)$r['produk_id'];
            $gid = (int)$r['gudang_id'];
            $cnt = (int)$r['cnt'];

//            if (!isset($map[$pid])) {
//                $map[$pid] = array(
//                    'qty_debet'    => array(
////                        "qty" => 0,
////                        "gudang_id" => 0,
//                    ),
//                    'ng_qty_debet' => array(
////                        "qty" => 0,
////                        "gudang_id" => 0,
//                    ),
//                    'wo_qty_debet' => array(
////                        "qty" => 0,
////                        "gudang_id" => 0,
//                    ),
//                );
//            }

            if ($gid > 0 && $gid < 1000) {
                if(!isset($map['ng_qty_debet'][$pid])){
                    $map['ng_qty_debet'][$pid] = array();
                }
                if(!isset($map['ng_qty_debet'][$pid]['jml_serial'] ) ){
                    $map['ng_qty_debet'][$pid]['jml_serial']  = 0;
                }
                $map['ng_qty_debet'][$pid]['jml_serial'] += $cnt;
                $map['ng_qty_debet'][$pid]['gudang_id'] = $gid;
                $map['ng_qty_debet'][$pid]['cabang_id'] = $opts['cabang_id'];
            }
            elseif($gid < 0) {
                if(!isset($map['qty_debet'][$pid])){
                    $map['qty_debet'][$pid] = array();
                }
                if(!isset($map['qty_debet'][$pid]['jml_serial'] ) ){
                    $map['qty_debet'][$pid]['jml_serial']  = 0;
                }
                $map['qty_debet'][$pid]['jml_serial'] += $cnt;
                $map['qty_debet'][$pid]['gudang_id'] = $gid;
                $map['qty_debet'][$pid]['cabang_id'] = $opts['cabang_id'];
            }
            else{
                if(!isset($map['wo_qty_debet'][$pid])){
                    $map['wo_qty_debet'][$pid] = array();
                }
                if(!isset($map['wo_qty_debet'][$pid]['jml_serial'] ) ){
                    $map['wo_qty_debet'][$pid]['jml_serial']  = 0;
                }
                $map['wo_qty_debet'][$pid]['jml_serial'] += $cnt;
                $map['wo_qty_debet'][$pid]['gudang_id'] = $gid;
                $map['wo_qty_debet'][$pid]['cabang_id'] = $opts['cabang_id'];
            }
        }


        return $map;
    }

    public function getSerialIntransitCountMap($prod,$rek,$opts)
    {
        if (empty($prod)) return array();

        $pids = array();
        foreach ($prod as $r) {
            if (!empty($r['pId'])) $pids[] = (int)$r['pId'];
        }

        $pids = array_values(array_unique($pids));

        $this->db->select('produk_id, gudang_id, COUNT(1) AS cnt', false);
        $this->db->from('_rek_pembantu_produk_perserial_intransit_cache'); // sesuaikan nama tabel kamu
        $this->db->where('qty_debet >', 0); // atau kondisi transit yang bener di tabelmu
        $this->db->where('rekening', $rek);
        $this->db->where('cabang_id', $opts['cabang_id']);
        $this->db->where_in('produk_id', $pids);
        $this->db->group_by('produk_id,gudang_id');

        $rows = $this->db->get()->result_array();

        $map = array();
        foreach ($rows as $r) {
            $pid = (int)$r['produk_id'];
            $gid = (int)$r['gudang_id'];
            $cnt = (int)$r['cnt'];

            if ($gid > 0 && $gid < 1000) {
                if(!isset($map['ng_qty_debet'][$pid])){
                    $map['ng_qty_debet'][$pid] = array();
                }
                if(!isset($map['ng_qty_debet'][$pid]['jml_serial_transit'] ) ){
                    $map['ng_qty_debet'][$pid]['jml_serial_transit']  = 0;
                }
                $map['ng_qty_debet'][$pid]['jml_serial_transit'] += $cnt;
                $map['ng_qty_debet'][$pid]['gudang_id'] = $gid;
                $map['ng_qty_debet'][$pid]['cabang_id'] = $opts['cabang_id'];
            }
            elseif($gid < 0) {
                if(!isset($map['qty_debet'][$pid])){
                    $map['qty_debet'][$pid] = array();
                }
                if(!isset($map['qty_debet'][$pid]['jml_serial_transit'] ) ){
                    $map['qty_debet'][$pid]['jml_serial_transit']  = 0;
                }
                $map['qty_debet'][$pid]['jml_serial_transit'] += $cnt;
                $map['qty_debet'][$pid]['gudang_id'] = $gid;
                $map['qty_debet'][$pid]['cabang_id'] = $opts['cabang_id'];
            }
            else{
                if(!isset($map['wo_qty_debet'][$pid])){
                    $map['wo_qty_debet'][$pid] = array();
                }
                if(!isset($map['wo_qty_debet'][$pid]['jml_serial_transit'] ) ){
                    $map['wo_qty_debet'][$pid]['jml_serial_transit']  = 0;
                }
                $map['wo_qty_debet'][$pid]['jml_serial_transit'] += $cnt;
                $map['wo_qty_debet'][$pid]['gudang_id'] = $gid;
                $map['wo_qty_debet'][$pid]['cabang_id'] = $opts['cabang_id'];
            }

        }
        return $map;
    }
}