<?php


class ComDivRekening extends MdlMother
{

    protected $filters = array();
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
                                "div_id",
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
    );
    private $outFieldsMutasi = array( // dari tabel rek mutasi rekening umum
                                      "dtime",
                                      "transaksi_id",
                                      "transaksi_no",
                                      "cabang_id",
                                      "div_id",
                                      "jenis",
                                      "debet_awal",
                                      "debet",
                                      "debet_akhir",
                                      "kredit_awal",
                                      "kredit",
                                      "kredit_akhir",
                                      "keterangan",
                                      "fulldate",
    );
    private $periode = array("harian", "bulanan", "tahunan", "forever");


    public function __construct()
    {
        $this->tableName = "_rek_master_div_cache";
        $this->tableName_master = array(
            "mutasi" => "_rek_master_div",
            //            "cache" => "_rek_master_cache",
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

    //  endregion setter, getter

    public function pair($inParams)
    {

        $this->load->helper("he_mass_table");
        $configBalanceProtections = $this->config->item("accountBalanceProtections");
        $this->inParams = $inParams;

        if (sizeof($this->inParams['loop']) > 0) {
            $lCounter = 0;
            foreach ($this->periode as $periode) {
                $akumJml[$periode] = array( //==define validasi debet vs kredit seimbang
                                            "kredit" => 0,
                                            "debet"  => 0,
                );
                $arrRekening = array();
                foreach ($this->inParams['loop'] as $key => $value) {
                    $lCounter++;

                    $position = detectRekPosition($key, $value);

                    $arrRekening[] = $key;
                    $table = heReturnTableName($this->tableName_master, $arrRekening);
                    $this->tableName_mutasi = $table[$key]["mutasi"];
//                    $this->tableName = $table[$key]["cache"];

                    $_preValues = $this->cekPreValue($key, $inParams['static']['cabang_id'], $inParams['static']['div_id'], $periode);

                    if (array_key_exists("id", $_preValues["cache"]) && ($_preValues["cache"]["id"] > 0)) {
                        $mode = "update";
                        $_preValues_id = $_preValues["cache"]["id"];
                    } else {
                        $mode = "insert";
                        $_preValues_id = 0;
                        $this->outParams[$lCounter]["cache"][$mode]["tgl"] = date("d");
                        $this->outParams[$lCounter]["cache"][$mode]["bln"] = date("m");
                        $this->outParams[$lCounter]["cache"][$mode]["thn"] = date("Y");
                    }
                    $akumJml[$periode][$position] += abs($value);


                    if ($_preValues['cache']['debet'] > 0) {
                        $preNumber = detectRekByPosition($key, $_preValues['cache']['debet'], "debet");
                    } else {
                        $preNumber = detectRekByPosition($key, $_preValues['cache']['kredit'], "kredit");
                    }
//                    $afterNumber = round($preNumber + $value);
                    $afterNumber = $preNumber + $value;
                    $afterPosition = detectRekPosition($key, $afterNumber);


                    if (in_array($key, $configBalanceProtections)) {
                        if ($afterPosition != detectRekDefaultPosition($key) && ($afterNumber != 0)) {
//                            die(lgShowAlert("insufficient balance for  $key."));
                            die(lgShowAlert("insufficient balance for $key. <br>You requested $value. <br>Available: $preNumber. <br>Post-value should be: $afterNumber"));
                        }
                    }


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
                    $this->outParams[$lCounter]["cache"][$mode]["rek_id"] = createRekCode($key);
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
                            $this->outParams[$lCounter]["mutasi"]["rek_id"] = createRekCode($key);
                            $this->outParams[$lCounter]["mutasi"]["rekening"] = $key;
                            $this->outParams[$lCounter]["mutasi"]["tabel"] = $this->tableName_mutasi;
                            break;
                    }
                    //  endregion mutasi rekening umum
                }


                //  region balancing debet vs kredit
                if (round($akumJml[$periode]["debet"], 2) != round($akumJml[$periode]["kredit"], 2)) {
                    $selisih = $akumJml[$periode]["debet"] - $akumJml[$periode]["kredit"];
                    die(lgShowAlert("rekening tidak balance, DEBET: " . $akumJml[$periode]["debet"] . " KREDIT: " . $akumJml[$periode]["kredit"] . "<br>SELISIH: $selisih"));
                    return false;
                } else {
//                    cekMerah("::: REKENING UMUM BALANCE :::");
                }
                //  endregion balancing debet vs kredit
            }

        }
//        else {
//            echo "loop params are empty<br>";
//            return false;
//        }

        if (sizeof($this->outParams) > 0) {
            return true;
        } else {
            return false;
        }

    }

    private function cekPreValue($rek, $cabang_id, $divID, $periode)
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
        $this->addFilter("div_id='$divID'");
        $this->addFilter("periode='$periode'");
        $criteria = array();
        if (sizeof($this->filters) > 0) {
            $fCnt = 0;
            $criteria = array();
            foreach ($this->filters as $f) {
                $fCnt++;
                $tmp = explode("=", $f);
                if (sizeof($tmp) > 1) { //==berarti pakai tanda samadengan =
                    $criteria[$tmp[0]] = trim($tmp[1], "'");
                } else {
                    $tmp = explode("<>", $f);
                    if (sizeof($tmp) > 1) { //==berarti pakai tanda tidak sama dengan <>

                        $criteria[$tmp[0] . "!="] = trim($tmp[1], "'");
                    }
                }
            }
        }

        //  region mengambil saldo dari rek_master_cache
        $this->db->where($criteria);
        $tmp = $this->db->get($this->tableName)->result();
//        cekBiru(__LINE__ . ": " . $this->db->last_query() . " # " . count($tmp));

        if (sizeof($tmp) > 0) {
            // bila count($tmp) > 0, maka ambil saldo periode sendiri, dan mode update
            foreach ($tmp as $row) {
                $result["cache"] = array(
                    "id"     => $row->id,
                    "debet"  => $row->debet,
                    "kredit" => $row->kredit,
                );
            }
        } else {
            // bila count($tmp) == 0, maka ambil saldo periode forever dan mode insert
            $this->filters = array();
            $this->addFilter("rekening='$rek'");
            $this->addFilter("cabang_id='$cabang_id'");
            $this->addFilter("div_id='$divID'");
            $this->addFilter("periode='$periode'");

            $criteria = array();
            if (sizeof($this->filters) > 0) {
                $fCnt = 0;
                $criteria = array();
                foreach ($this->filters as $f) {
                    $fCnt++;
                    $tmp = explode("=", $f);
                    if (sizeof($tmp) > 1) { //==berarti pakai tanda samadengan =
                        $criteria[$tmp[0]] = trim($tmp[1], "'");
                    } else {
                        $tmp = explode("<>", $f);
                        if (sizeof($tmp) > 1) { //==berarti pakai tanda tidak sama dengan <>

                            $criteria[$tmp[0] . "!="] = trim($tmp[1], "'");
                        }
                    }
                }
            }

            $this->db->where($criteria);
            $tmp = $this->db->get($this->tableName)->result();
            if (sizeof($tmp) > 0) {
                foreach ($tmp as $row) {
                    $result["cache"] = array(
                        "debet"  => $row->debet,
                        "kredit" => $row->kredit,
                    );
                }
            } else {
                $result["cache"] = array(
                    "debet"  => 0,
                    "kredit" => 0,
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
            } else {
                return false;
            }
        } else {
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
        } else {
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
                    "debet"    => $row->debet,
                    "kredit"   => $row->kredit,
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
                    "id"         => $row->extern_id,
                    "rek_id"         => $row->rek_id,
                    "name"       => $row->extern_nama,
                    "debet"      => $row->debet,
                    "kredit"     => $row->kredit,
                    "qty_debet"  => $row->qty_debet,
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


}