<?php


class ComTransaksiProjectItem extends MdlMother
{

    protected $filters = array();
    protected $filters2 = array();
    protected $criteria2 = array();
    private $tableName;
    private $tableName_lajur;
    private $tableName_mutasi;
    private $tableName_master = array();
    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $outParams2 = array( //===output ke tabel

    );
    private $outFields = array( // dari tabel rek_cache
        "rekening",
        "periode",
        "cabang_id",
        "debet_awal",
        "debet",
        "debet_akhir",
        "kredit_awal",
        "kredit",
        "kredit_akhir",
        "dtime",
        "tgl",
        "bln",
        "thn",
        "fulldate",
        "extern_id",
        "extern_nama",
    );
    private $outFieldsMutasi = array( // dari tabel rek mutasi rekening umum
        "dtime",
        "transaksi_id",
        "transaksi_no",
        "cabang_id",
        "jenis",
        "debet_awal",
        "debet",
        "debet_akhir",
        "kredit_awal",
        "kredit",
        "kredit_akhir",
        "keterangan",
        "fulldate",
        "extern_id",
        "extern_nama",
    );
    private $periode = array("forever");
    private $periode2 = array();
    private $catException = array();


    public function __construct()
    {
        $this->tableName = "z_transaksi_project_cache";
        $this->tableName_master = array(
            "mutasi" => "z_transaksi_project_mutasi",
        );

        $this->catException = $this->config->item('accountCatExceptions') != null ? $this->config->item('accountCatExceptions') : array();
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

    public function getTableName_lajur()
    {
        return $this->tableName_lajur;
    }

    public function setTableName_lajur($tableName_lajur)
    {
        $this->tableName_lajur = $tableName_lajur;
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

    public function getOutParams2()
    {
        return $this->outParams2;
    }

    public function setOutParams2($outParams2)
    {
        $this->outParams2 = $outParams2;
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

    public function setTableNameMutasi($tableName_mutasi)
    {
        $this->tableName_mutasi = $tableName_mutasi;
    }

    public function getPeriode2()
    {
        return $this->periode2;
    }

    public function setPeriode2($periode2)
    {
        $this->periode2 = $periode2;
    }

    public function getCatException()
    {
        return $this->catException;
    }

    public function setCatException($catException)
    {
        $this->catException = $catException;
    }

    public function getFilters2()
    {
        return $this->filters2;
    }

    public function setFilters2($filters2)
    {
        $this->filters2 = $filters2;
    }

    public function getCriteria2()
    {
        return $this->criteria2;
    }

    //  endregion setter, getter

    public function pair($inParams)
    {
        arrPrintWebs($inParams);
        $this->load->helper("he_mass_table");
        $configBalanceProtections = $this->config->item("accountBalanceProtections");
        $this->inParams = $inParams[0];

        if (sizeof($this->inParams['loop']) > 0) {

            $lCounter = 0;
            foreach ($this->periode as $periode) {
                $akumJml[$periode] = array( //==define validasi debet vs kredit seimbang
                    "kredit" => 0,
                    "debet" => 0,
                );
                $arrRekening = array();
                foreach ($this->inParams['loop'] as $key => $value) {
                    $lCounter++;

//                    $position = detectRekPosition($key, $value);
                    $position = $value > 0 ? "debet" : "kredit";

                    $arrRekening[] = $key;
//                    $table = heReturnTableName($this->tableName_master, $arrRekening);
                    $this->tableName_mutasi = $this->tableName_master["mutasi"];


                    $_preValues = $this->cekPreValue($key, $this->inParams['static']['cabang_id'], $periode, $this->inParams['static']['extern_id']);

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
                    $akumJml[$periode][$position] += abs($value);


                    if ($_preValues['cache']['debet'] > 0) {
//                        $preNumber = detectRekByPosition($key, $_preValues['cache']['debet'], "debet");
                        $preNumber = $_preValues['cache']['debet'];
                    }
                    else {
//                        $preNumber = detectRekByPosition($key, $_preValues['cache']['kredit'], "kredit");
                        $preNumber = $_preValues['cache']['kredit'] * -1;
                    }

                    $afterNumber = $preNumber + $value;
                    $afterPosition = $afterNumber > 0 ? "debet" : "kredit";
//                    $afterPosition = detectRekPosition($key, $afterNumber);


//                    if (in_array($key, $configBalanceProtections)) {
//                        if ($afterPosition != detectRekDefaultPosition($key) && (round($afterNumber) != 0)) {
//                            cekMerah("[$afterPosition] insufficient balance for  $key. [$afterNumber] [$preNumber] " . round($afterNumber));
//                            die(lgShowAlert("insufficient balance for $key. <br>You requested $value. <br>Available: $preNumber. <br>Post-value should be: $afterNumber"));
//                        }
//                    }


                    //  region cache rekening umum
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
                            die(lgShowAlert(__LINE__ . " gagal menentukan posisi rekening DEBET / KREDIT " . __FUNCTION__ . " " . __FILE__));
                            break;
                    }
                    $this->outParams[$lCounter]["cache"][$mode]["rek_id"] = 0;
                    $this->outParams[$lCounter]["cache"][$mode]["rekening"] = $key;
                    $this->outParams[$lCounter]["cache"][$mode]["periode"] = $periode;
                    $this->outParams[$lCounter]["cache"][$mode]["id"] = $_preValues_id;
                    $this->outParams[$lCounter]["cache"][$mode]["tabel"] = $this->tableName;

                    foreach ($this->inParams['static'] as $key_static => $value_static) {
                        if (in_array($key_static, $this->outFields)) {
                            $this->outParams[$lCounter]["cache"][$mode][$key_static] = $value_static;
                        }
                    }
                    //  endregion cache rekening umum


                    //  region mutasi rekening umum
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
                            $this->outParams[$lCounter]["mutasi"]["rek_id"] = 0;
                            $this->outParams[$lCounter]["mutasi"]["rekening"] = $key;
                            $this->outParams[$lCounter]["mutasi"]["tabel"] = $this->tableName_mutasi;
                            break;
                    }
                    //  endregion mutasi rekening umum
                }


                //  region balancing debet vs kredit
                $balance = 0;
                if ($balance == 1) {
                    if (round($akumJml[$periode]["debet"], 1) != round($akumJml[$periode]["kredit"], 1)) {
                        $selisih = $akumJml[$periode]["debet"] - $akumJml[$periode]["kredit"];
                        die(lgShowAlert("rekening tidak balance, DEBET: " . $akumJml[$periode]["debet"] . " KREDIT: " . $akumJml[$periode]["kredit"] . "<br>SELISIH: $selisih"));
                        return false;
                    }
                    else {
//                    cekMerah("::: REKENING UMUM BALANCE :::");
                    }
                }
                //  endregion balancing debet vs kredit

            }

        }

//mati_disini(get_class($this));
        if (sizeof($this->outParams) > 0) {
            return true;
        }
        else {
            return false;
        }

    }

    private function cekPreValue($rek, $cabang_id, $periode, $extern_id)
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

        $tmp = $this->db->query("{$query} FOR UPDATE")->row_array();
        showLast_query("orange");

        if (sizeof($tmp) > 0) {
            // bila count($tmp) > 0, maka ambil saldo periode sendiri, dan mode update
//            foreach ($tmp as $row) {
//                $result["cache"] = array(
//                    "id"     => $row->id,
//                    "debet"  => $row->debet,
//                    "kredit" => $row->kredit,
//
//                );
//            }
            $result["cache"] = array(
                "id" => $tmp['id'],
                "debet" => $tmp['debet'],
                "kredit" => $tmp['kredit'],

            );
        }
        else {
            $result["cache"] = array(
                "debet" => 0,
                "kredit" => 0,
            );

//            // bila count($tmp) == 0, maka ambil saldo periode forever dan mode insert
//            $rekCat = detectRekCategory($rek);
//            if (!in_array($rekCat, $this->catException)) {
//
//                $periode = "forever";
//                $this->filters = array();
//                $this->addFilter("rekening='$rek'");
//                $this->addFilter("cabang_id='$cabang_id'");
//                $this->addFilter("periode='$periode'");
//
//                switch ($periode) {
//                    case "harian":
//                        $this->addFilter("tgl='$tgl'");
//                        $this->addFilter("bln='$bln'");
//                        $this->addFilter("thn='$thn'");
//                        break;
//                    case "bulanan":
//                        $this->addFilter("bln='$bln'");
//                        $this->addFilter("thn='$thn'");
//                        break;
//                    case "tahunan":
//                        $this->addFilter("thn='$thn'");
//                        break;
//                    case "forever":
//                        break;
//                }
//
//                $localFilters = array();
//                if (sizeof($this->filters) > 0) {
//                    foreach ($this->filters as $f) {
//                        $tmpArr = explode("=", $f);
////                    $localFilters[$tmpArr[0]]=$tmpArr[1];
//                        $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");
//                    }
//                }
//
//                $query = $this->db->select()
//                    ->from($this->tableName)
//                    ->where($localFilters)
//                    ->limit(1)
//                    ->get_compiled_select();
//
//                $tmp = $this->db->query("{$query} FOR UPDATE")->row_array();
//
//                if (sizeof($tmp) > 0) {
////                foreach ($tmp as $row) {
////                    $result["cache"] = array(
////                        "debet"  => $row->debet,
////                        "kredit" => $row->kredit,
////                    );
////                }
//                    $result["cache"] = array(
////                    "id"     => $tmp['id'],
//                        "debet" => $tmp['debet'],
//                        "kredit" => $tmp['kredit'],
//
//                    );
//                }
//                else {
//                    $result["cache"] = array(
//                        "debet" => 0,
//                        "kredit" => 0,
//                    );
//                }
//            }
//            else {
//                $result["cache"] = array(
//                    "debet" => 0,
//                    "kredit" => 0,
//                );
//
////                $this->filters = array();
////                $this->addFilter("rekening='$rek'");
////                $this->addFilter("cabang_id='$cabang_id'");
////                $this->addFilter("periode='$periode'");
////                switch ($periode) {
////                    case "harian":
////                        $this->addFilter("tgl='$tgl'");
////                        $this->addFilter("bln='$bln'");
////                        $this->addFilter("thn='$thn'");
////                        break;
////                    case "bulanan":
////                        $this->addFilter("bln='$bln'");
////                        $this->addFilter("thn='$thn'");
////                        break;
////                    case "tahunan":
////                        $this->addFilter("thn='$thn'");
////                        break;
////                    case "forever":
////                        break;
////                }
////                $localFilters = array();
////                if (sizeof($this->filters) > 0) {
////                    foreach ($this->filters as $f) {
////                        $tmpArr = explode("=", $f);
////                        $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");
////                    }
////                }
////                $query = $this->db->select()
////                    ->from($this->tableName)
////                    ->where($localFilters)
////                    ->limit(1)
////                    ->order_by("id", "DESC")
////                    ->get_compiled_select();
////
////                $tmp = $this->db->query("{$query} FOR UPDATE")->row_array();
////                if (sizeof($tmp) > 0) {
////
////                    $result["cache"] = array(
//////                    "id"     => $tmp['id'],
////                        "debet" => $tmp['debet'],
////                        "kredit" => $tmp['kredit'],
////
////                    );
////                }
////                else {
////                    $result["cache"] = array(
////                        "debet" => 0,
////                        "kredit" => 0,
////                    );
////                }
//
//            }

        }

        return $result;
    }

    public function exec()
    {
        $insertIDs = array();
        if (sizeof($this->outParams) > 0) {
            foreach ($this->outParams as $lCounter => $pSpec) {
                foreach ($pSpec as $mode => $pSpec_mode) {
                    switch ($mode) {
                        case "cache":

                            foreach ($pSpec_mode as $sub_mode => $pSpec_mode_data) {
                                $id = $pSpec_mode_data["id"];
                                $tableName = $pSpec_mode_data["tabel"];
                                unset($pSpec_mode_data["id"]);
                                unset($pSpec_mode_data["tabel"]);

                                switch ($sub_mode) {
                                    case "insert":

                                        $this->db->insert($tableName, $pSpec_mode_data);
                                        $insertIDs[] = $this->db->insert_id();
                                        cekBiru($this->db->last_query());

                                        break;
                                    case "update":

                                        $this->db->where('id', $id);
                                        $this->db->update($tableName, $pSpec_mode_data);
                                        cekOrange($this->db->last_query());

                                        break;
                                }
                            }
                            break;
                        case "mutasi":
                            $tableName_mutasi = $pSpec_mode["tabel"];
                            unset($pSpec_mode["tabel"]);

                            $this->db->insert($tableName_mutasi, $pSpec_mode);
                            $insertIDs[] = $this->db->insert_id();
                            cekHijau($this->db->last_query());
                            break;
                    }
                }
            }


//            mati_disini("COM_REKENING TESTING...");

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

    //-----------------------------------------------
    public function addFilter($f)
    {
        $this->filters[] = $f;
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
//arrPrint($arrRekening);
//        mati_disini();
        if (sizeof($arrRekening) > 0) {
            $result = heReturnTableName($this->tableName_master, $arrRekening);
//            $addMasterTables = array(
//                "rugilaba",
//                "laba ditahan",
//            );
//            foreach($addMasterTables as $rN){
//                $arrRekening[]=$rN;
//            }
//            arrPrint($result);
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

    public function fetchAllBalances()
    {//==memanggil saldo2 dari rekening tertentu
        //        $tableNames = heReturnTableName($this->tableName_master, array($rek));
        $this->db->select("*");
        $this->db->where(array("periode" => "forever"));
//        $this->db->order_by("id", "desc");
        $this->db->order_by("rek_id", "asc");

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
//        cekkuning($this->db->last_query());


        $results = array();
        if (sizeof($result->result()) > 0) {
            foreach ($result->result() as $row) {
                $results[$row->rekening] = array(
                    "rek_id" => $row->rek_id,
                    "rekening" => $row->rekening,
                    "debet" => $row->debet,
                    "kredit" => $row->kredit,
                );
            }
        }

        return $results;
    }

    public function fetchAllBalancesTmp($defaultDate = "")
    {
        //==memanggil saldo2 dari rekening tertentu
        $this->db->select("*");
//        $this->db->where(array("periode" => "forever"));
//        $this->db->where("periode='tahunan'");
        $this->db->where("periode='bulanan'");
        $this->db->where("bln<='10'");
        $this->db->where("thn='2019'");

//        $this->db->order_by("rek_id", "asc");
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
//        cekkuning($this->db->last_query());


        $results = array();
        if (sizeof($result->result()) > 0) {
            foreach ($result->result() as $row) {
                $results[$row->rekening] = array(
                    "rek_id" => $row->rek_id,
                    "rekening" => $row->rekening,
                    "debet" => $row->debet,
                    "kredit" => $row->kredit,
                );
            }
        }

        return $results;
    }

    public function fetchAllBalances2()
    {

        $this->db->select("*");
        $this->db->order_by("rek_id", "asc");

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

        $result = $this->db->get($this->tableName)->result();
        cekkuning(":: " . $this->db->last_query());

        $results = array();
        if (sizeof($result) > 0) {
            foreach ($result as $row) {
                $results[$row->rekening] = array(
                    "rek_id" => $row->rek_id,
                    "rekening" => $row->rekening,
                    "debet" => $row->debet,
                    "kredit" => $row->kredit,
                );
            }
        }


//        if (sizeof($this->filters) > 0) {
//            $this->db->select("*");
//            $this->db->order_by("rek_id", "asc");
//            $criteria = array();
//            $criteria2 = "";
//            $this->fetchCriteria();
//            $criteria = $this->getCriteria();
//            $criteria2 = $this->getCriteria2();
//            if (sizeof($criteria) > 0) {
//                $this->db->where($criteria);
//            }
//            if ($criteria2 != "") {
//                $this->db->where($criteria2);
//            }
//
//            $result = $this->db->get($this->tableName)->result();
//        }


//mati_disini();
        return $results;
    }

    public function fetchAllBalancesTahunan()
    {//==memanggil saldo2 dari rekening tertentu
        //        $tableNames = heReturnTableName($this->tableName_master, array($rek));
        $this->db->select("*");
//        $this->db->where(array("periode" => "forever"));
        $this->db->where(array("periode" => "tahunan"));
        $this->db->order_by("rek_id", "asc");

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
//        cekkuning($this->db->last_query());


        $results = array();
        if (sizeof($result->result()) > 0) {
            foreach ($result->result() as $row) {
                $results[$row->rekening] = array(
                    "rek_id" => $row->rek_id,
                    "rekening" => $row->rekening,
                    "debet" => $row->debet,
                    "kredit" => $row->kredit,
                );
            }
        }

        return $results;
    }

    public function fetchBalances($rek)
    {//==memanggil saldo2 dari rekening tertentu
        //        $tableNames = heReturnTableName($this->tableName_master, array($rek));
        $this->db->select("*");
        $this->db->where(array("periode" => "forever", "rekening" => $rek));
//        $this->db->order_by("id", "desc");
        $this->db->order_by("rek_id", "asc");

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
//        cekkuning($this->db->last_query());
        $results = array();
        if (sizeof($result->result()) > 0) {
            foreach ($result->result() as $row) {
                $results[] = array(
                    "rek_id" => $row->rek_id,
                    "id" => $row->extern_id,
                    "name" => $row->extern_nama,
                    "debet" => $row->debet,
                    "kredit" => $row->kredit,
                    "qty_debet" => $row->qty_debet,
                    "qty_kredit" => $row->qty_kredit,
                );
            }
        }
        return $result->result();
    }

    public function fetchMoves($rek)
    {//==memanggil saldo2 dari rekening tertentu
        $tableNames = heReturnTableName($this->tableName_master, array($rek));
        $this->db->select("*");

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


    public function fetchAllBalancesPeriode()
    {
        //==memanggil saldo2 dari rekening tertentu
        $this->db->select("*");
//        $this->db->where(
//            array(
//                "periode" => "forever",
//            )
//        );
//        $this->db->order_by("rek_id", "asc");
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

        $result = $this->db->get($this->tableName)->result();
//        cekkuning($this->db->last_query());

        $results = array();
        if (sizeof($result) > 0) {
            foreach ($result as $row) {
                $results[$row->periode][$row->rekening] = array(
                    "rek_id" => $row->rek_id,
                    "rekening" => $row->rekening,
                    "debet" => $row->debet,
                    "kredit" => $row->kredit,
                );
            }
        }
//arrPrint($results);
//mati_disini();
        return $results;
    }

    public function fetchAllBalancesHarian()
    {
        //==memanggil saldo2 dari rekening tertentu
        $this->db->select("*");
        $this->db->where(array("periode" => "harian"));
        $this->db->order_by("rek_id", "asc");

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
//        cekkuning($this->db->last_query());


        $results = array();
        if (sizeof($result->result()) > 0) {
            foreach ($result->result() as $row) {
                $results[$row->rekening] = array(
                    "rek_id" => $row->rek_id,
                    "rekening" => $row->rekening,
                    "debet" => $row->debet,
                    "kredit" => $row->kredit,
                );
            }
        }

        return $results;
    }

    public function fetchAllBalancesBulanan($date = "")
    {

        $this->db->select("*");

        $this->db->where(array("periode" => "bulanan"));
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

        if (strlen($date) > 3) {
            $date_ex = explode("-", $date);
            $thn = $date_ex[0];
            $bln = $date_ex[1];
            $where = " (bln<='12' or bln<='$bln') and thn<='$thn'";
            $this->db->where($where);
        }
        $result = $this->db->get($this->tableName);
//        cekkuning($this->db->last_query());


        $results = array();
        if (sizeof($result->result()) > 0) {
            foreach ($result->result() as $row) {
                $results[$row->rekening] = array(
                    "rek_id" => $row->rek_id,
                    "rekening" => $row->rekening,
                    "debet" => $row->debet,
                    "kredit" => $row->kredit,
                    "periode" => $row->periode,
                );
            }
        }

        return $results;
    }


    //new
    public function fetchAllBalancesMonthly($date = "")
    {
        $this->db->select("*");
        $this->db->where(array("periode" => "bulanan"));
        $this->db->order_by("rek_id", "asc");
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

        if (strlen($date) > 3) {
            $date_ex = explode("-", $date);
            $thn = $date_ex[0];
            $bln = $date_ex[1];
            $where = "bln='$bln' and thn='$thn'";
            $this->db->where($where);
        }

        $result = $this->db->get($this->tableName);
        $results = array();
        if (sizeof($result->result()) > 0) {
            foreach ($result->result() as $row) {
                $results[$row->rekening] = array(
                    "rek_id" => $row->rek_id,
                    "rekening" => $row->rekening,
                    "debet" => $row->debet,
                    "kredit" => $row->kredit,
                );
            }
        }
        return $results;
    }

    public function fetchAllBalancesMonthToDate()
    {

        $this->db->select("*");
        $this->db->where(array("periode" => "bulanan"));
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

        $date = date("Y-m");

        if (strlen($date) > 3) {
            $date_ex = explode("-", $date);
            $thn = $date_ex[0];
            $bln = $date_ex[1];
            $where = "bln='$bln' and thn='$thn'";
            $this->db->where($where);
        }
        $result = $this->db->get($this->tableName);

        $results = array();
        if (sizeof($result->result()) > 0) {
            foreach ($result->result() as $row) {
                $results[$row->rekening] = array(
                    "rek_id" => $row->rek_id,
                    "rekening" => $row->rekening,
                    "debet" => $row->debet,
                    "kredit" => $row->kredit,
                    "periode" => $row->periode,
                );
            }
        }

        return $results;
    }

    public function fetchAllBalancesYearly($date = "")
    {

        $this->db->select("*");

        $this->db->where(array("periode" => "tahunan"));
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

        if (strlen($date) == 4) {
            $where = " thn='$date'";
            $this->db->where($where);
        }
        $result = $this->db->get($this->tableName);

        $results = array();
        if (sizeof($result->result()) > 0) {
            foreach ($result->result() as $row) {
                $results[$row->rekening] = array(
                    "rek_id" => $row->rek_id,
                    "rekening" => $row->rekening,
                    "debet" => $row->debet,
                    "kredit" => $row->kredit,
                    "periode" => $row->periode,
                );
            }
        }

        return $results;
    }

    public function fetchAllBalancesYearToDate($date = "")
    {

        $this->db->select("*");

        $this->db->where(array("periode" => "tahunan"));
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

        if (strlen($date) > 3) {
            $date_ex = explode("-", $date);
            $thn = $date_ex[0];
            $bln = $date_ex[1];
            $where = " (bln<='12' or bln<='$bln') and thn<='$thn'";
            $this->db->where($where);
        }
        $result = $this->db->get($this->tableName);
//        cekkuning($this->db->last_query());


        $results = array();
        if (sizeof($result->result()) > 0) {
            foreach ($result->result() as $row) {
                $results[$row->rekening] = array(
                    "rek_id" => $row->rek_id,
                    "rekening" => $row->rekening,
                    "debet" => $row->debet,
                    "kredit" => $row->kredit,
                    "periode" => $row->periode,
                );
            }
        }

        return $results;
    }


    public function fetchBalancePeriode($rek, $periode)
    {
        //==memanggil saldo2 dari rekening tertentu
        $tableNames = $this->tableName;
        $this->db->select("*");
        $this->db->where(
            array(
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

    public function fetchAllBalances_all()
    {

        $this->db->select("*");
        $this->db->where(
            array(
                "periode" => "forever"
            )
        );
//        $this->db->order_by("id", "desc");
        $this->db->order_by("rek_id", "asc");

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
//        cekkuning($this->db->last_query());


        $results = array();
        if (sizeof($result->result()) > 0) {
            foreach ($result->result() as $row) {
                $results[$row->rekening] = array(
                    "rek_id" => $row->rek_id,
                    "rekening" => $row->rekening,
                    "debet" => $row->debet,
                    "kredit" => $row->kredit,
                );
            }
        }

//        return $results;
        return $result->result();
    }

    public function insertTodayMoves($rek, $datas)
    {
        $this->load->helper("he_mass_table");

//        $rek = $datas['rekening'];
        $tableNames = heReturnTableName($this->tableName_master, array($rek))[$rek]['mutasi'];

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

    public function lookUpAll()
    {
        //==memanggil saldo2 dari rekening tertentu
        $this->db->select("*");
        //        $this->db->where(
        //            array(
        //                "periode" => "forever",
        //            )
        //        );
        //        $this->db->order_by("rek_id", "asc");
        $this->db->order_by("id", "asc");

        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
//            arrPrint($criteria);
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        $result = $this->db->get($this->tableName);
//                cekkuning($this->db->last_query());


        return $result;
    }

}