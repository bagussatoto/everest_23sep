<?php


class ComJurnal_activityItem extends MdlMother
{

    protected $filters = array();
    protected $filters2 = array();
    protected $tableName;
    private $tableName_lajur;
    private $tableName_mutasi;
    private $tableName_master = array();
    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $outFields = array( // dari tabel rek_cache
        "rekening",
        "periode",
        "cabang_id",
        "cabang_nama",
        "cabang2_id",
        "cabang2_nama",
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
        "activity",
        "master_id",
        "transaksi_id",
        "jenis_master",
        "jenis",
    );
    private $outFieldsMutasi = array( // dari tabel rek mutasi rekening umum
        "dtime",
        "master_id",
        "transaksi_id",
        "transaksi_no",
        "cabang_id",
        "cabang_nama",
        "cabang2_id",
        "cabang2_nama",
        "jenis",
        "debet_awal",
        "debet",
        "debet_akhir",
        "kredit_awal",
        "kredit",
        "kredit_akhir",
        "keterangan",
        "fulldate",
        "activity",
        "oleh_id",
        "oleh_nama",
        "jenis_master",
    );
    private $outFieldsJurnal = array(
        "jenis",
        "j_jenis",
        "rekening",
        "debet",
        "kredit",
        "transaksi_id",
        "transaksi_no",
        "cabang_id",
        "cabang_nama",
        "cabang2_id",
        "cabang2_nama",
        "dtime",
        "keterangan",
        "fulldate",
        "activity",
        "master_id",
        "jenis_master",
    );
    private $periode = array(
//        "harian",
//        "bulanan",
//        "tahunan",
        "forever",
    );
    private $whitelistUpdate = array(
        "debet",
        "kredit",
    );
    private $blacklistUpdate = array(
        "request",
    );
    private $blacklistUpdateMode;


    public function __construct()
    {
        $this->tableName = "activity_cache";
        $this->tableName_master = array(
            "cache" => "activity_cache",
            "mutasi" => "activity_mutasi",
            "jurnal" => "activity_jurnal",
        );

        $this->regulerRoutesConfig = ($this->config->item('heTransaksi_regulerRoutes') != NULL) ? $this->config->item('heTransaksi_regulerRoutes') : array();
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

    public function getOutFieldsJurnal()
    {
        return $this->outFieldsJurnal;
    }

    public function setOutFieldsJurnal($outFieldsJurnal)
    {
        $this->outFieldsJurnal = $outFieldsJurnal;
    }

    public function getWhitelistUpdate()
    {
        return $this->whitelistUpdate;
    }

    public function setWhitelistUpdate($whitelistUpdate)
    {
        $this->whitelistUpdate = $whitelistUpdate;
    }

    public function getBlacklistUpdate()
    {
        return $this->blacklistUpdate;
    }

    public function setBlacklistUpdate($blacklistUpdate)
    {
        $this->blacklistUpdate = $blacklistUpdate;
    }

    public function getBlacklistUpdateMode()
    {
        return $this->blacklistUpdateMode;
    }

    public function setBlacklistUpdateMode($blacklistUpdateMode)
    {
        $this->blacklistUpdateMode = $blacklistUpdateMode;
    }

    //  endregion setter, getter

    public function pair($inParams)
    {
        arrPrint($inParams);
        if (sizeof($inParams) > 0) {
            foreach ($inParams as $array_params) {

                $this->inParams = $array_params;
                if (sizeof($this->inParams['loop']) > 0) {

                    $this->outParams = array();
                    $lCounter = 0;
                    foreach ($this->periode as $periode) {
                        $akumJml[$periode] = array(
                            "kredit" => 0,
                            "debet" => 0,
                        );

                        foreach ($this->inParams['loop'] as $key => $value) {

                            $transaksiID = $this->inParams['static']['transaksi_id'];
                            $masterID = $this->inParams['static']['master_id'];
//                    $jenis = $this->inParams['static']['jenis'];
                            $jenis = $this->inParams['static']['jenis_master'];
                            $step_number = $this->inParams['static']['step_number'];
                            $cabangID = $this->inParams['static']['cabang_id'];
                            $cabang2ID = $this->inParams['static']['cabang2_id'];

                            $new_sisa = isset($this->inParams['static']['new_sisa']) ? $this->inParams['static']['new_sisa'] : 0;
                            if ($new_sisa > 0) {
                                $running = false;
                            }
                            else {
                                $running = true;
                            }

                            cekKuning("$running ::: $new_sisa");
                            if ($running == true) {

                                $jenisConnect = heGetOriginTCode($jenis);
                                if ($jenisConnect != NULL) {
                                    $max_step = sizeof($this->regulerRoutesConfig[$jenisConnect]);
                                }
                                else {
                                    $max_step = sizeof($this->regulerRoutesConfig[$jenis]);
                                }
                                cekHitam("jenis: $jenis, connect code: $jenisConnect, max step: $max_step");
                                if ($step_number == $max_step) {
                                    $this->blacklistUpdateMode = true;
                                    cekHitam("ADA BLACKLIST");
                                }
                                else {
                                    $this->blacklistUpdateMode = false;
                                    cekHitam("TIDAK ADA BLACKLIST");
                                }

                                if (isset($this->regulerRoutesConfig[$jenis][$step_number]) && sizeof($this->regulerRoutesConfig[$jenis][$step_number]) > 0) {
                                    $routesConfig = $this->regulerRoutesConfig[$jenis][$step_number];
                                    foreach ($routesConfig as $keyRoutes => $valRoutes) {
                                        $lCounter++;

                                        $_preValues = $this->cekPreValue($key, $cabangID, $cabang2ID, $periode, $transaksiID, $masterID, $valRoutes);
                                        arrPrint($_preValues);

                                        if (array_key_exists('id', $_preValues['cache']) && ($_preValues['cache'] > 0)) {
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
                                        if (isset($_preValues['cache']['debet']) || isset($_preValues['cache']['kredit'])) {
                                            if ($_preValues['cache']['debet'] > 0) {
                                                $preNumber = $_preValues['cache']['debet'];
                                            }
                                            else {
                                                $preNumber = $_preValues['cache']['kredit'] * -1;
                                            }
                                        }
                                        else {
                                            $preNumber = 0;
                                        }

                                        $position = $keyRoutes == "debet" ? "debet" : "kredit";
                                        $number = $keyRoutes == "debet" ? 1 : '-1';
                                        $afterNumber = $preNumber + $number;
                                        $afterPosition = $afterNumber >= 0 ? $position : "kredit";

                                        cekUngu("activity: $valRoutes, preNumber: $preNumber, number: $number, afterNumber: $afterNumber, 
                position: $position, afterPosition: $afterPosition");

                                        //  region cache
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

                                        $this->outParams[$lCounter]["cache"][$mode]["rekening"] = $key;
                                        $this->outParams[$lCounter]["cache"][$mode]["periode"] = $periode;
                                        $this->outParams[$lCounter]["cache"][$mode]["id"] = $_preValues_id;
                                        $this->outParams[$lCounter]["cache"][$mode]["tabel"] = $this->tableName_master['cache'];
                                        $this->outParams[$lCounter]["cache"][$mode]["activity"] = $valRoutes;

                                        foreach ($this->inParams['static'] as $key_static => $value_static) {
                                            if (in_array($key_static, $this->outFields)) {
                                                $this->outParams[$lCounter]["cache"][$mode][$key_static] = $value_static;
                                            }
                                        }
                                        //  endregion cache

                                        // region mutasi
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
                                                        $this->outParams[$lCounter]["mutasi"]["debet"] = abs($number);
                                                        $this->outParams[$lCounter]["mutasi"]["kredit"] = 0;
                                                        break;
                                                    case "kredit":
                                                        $this->outParams[$lCounter]["mutasi"]["kredit"] = abs($number);
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
//                                    $this->outParams[$lCounter]["mutasi"]["rek_id"] = createRekCode($key);
                                                $this->outParams[$lCounter]["mutasi"]["activity"] = $valRoutes;
                                                $this->outParams[$lCounter]["mutasi"]["rekening"] = $key;
                                                $this->outParams[$lCounter]["mutasi"]["tabel"] = $this->tableName_master['mutasi'];
                                                break;
                                        }
                                        // endregion mutasi

                                        // region jurnal
                                        $pakai_jurnal = 0;
                                        if ($pakai_jurnal == 1) {
                                            foreach ($this->inParams['static'] as $key_static_jurnal => $value) {
                                                if (in_array($key, $this->outFieldsJurnal)) {
                                                    $this->outParams[$lCounter]["jurnal"][$key_static_jurnal] = $value;
                                                }
                                            }
                                            $this->outParams[$lCounter]["jurnal"][$position] = abs($number);
                                            $this->outParams[$lCounter]["jurnal"]['rekening'] = $key;
                                            $this->outParams[$lCounter]["jurnal"]["activity"] = $valRoutes;
                                            $this->outParams[$lCounter]["jurnal"]["tabel"] = $this->tableName_master['jurnal'];
                                        }
                                        // endregion jurnal
                                    }

                                }
                                else {
                                    cekKuning("TIDAK ADA JURNAL ACTIVITY...");
                                }
                            }

                        }

                    }

                    if (sizeof($this->outParams) > 0) {
                        $total = array(
                            "debet" => 0,
                            "kredit" => 0,
                        );
                        foreach ($this->outParams as $opSpec) {
                            if (isset($opSpec['jurnal']) && sizeof($opSpec['jurnal']) > 0) {

                                $debet = isset($opSpec['jurnal']['debet']) ? $opSpec['jurnal']['debet'] : 0;
                                $kredit = isset($opSpec['jurnal']['kredit']) ? $opSpec['jurnal']['kredit'] : 0;

                                $total['debet'] += $debet;
                                $total['kredit'] += $kredit;

                            }
                        }

                        $selisih = $total['debet'] - $total['kredit'];
                        if ($selisih != 0) {
                            mati_disini("Transaksi Gagal disimpan, jurnal aktivitas tidak balance.");
                        }
                    }


                    // region execution
                    arrPrint($this->outParams);
                    $pakai_exec = 1;
                    if ($pakai_exec == 1) {
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
                                                        if ($this->blacklistUpdateMode == true) {
                                                            if (in_array($pSpec_mode_data['activity'], $this->blacklistUpdate)) {
                                                                foreach ($pSpec_mode_data as $key => $val) {
                                                                    if (!in_array($key, $this->whitelistUpdate)) {
                                                                        unset($pSpec_mode_data[$key]);
                                                                    }
                                                                }
                                                            }
                                                            else {
                                                                cekHitam("TIDAK ADA BLACKLIST");
                                                            }
                                                        }

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
                                        case "jurnal":
                                            $tableName_jurnal = $pSpec_mode["tabel"];
                                            unset($pSpec_mode["tabel"]);

                                            $this->db->insert($tableName_jurnal, $pSpec_mode);
                                            $insertIDs[] = $this->db->insert_id();
                                            cekUngu($this->db->last_query());
                                            break;
                                    }
                                }
                            }
                            $this->outParams = array();

                            if (sizeof($insertIDs) > 0) {
                                return true;
                            }
                            else {
                                return false;
                            }
                        }
                    }
                    // endregion execution
                }

            }
        }

        return true;
    }

    private function cekPreValue($rek, $cabang_id, $cabang2_id, $periode, $transaksiID, $masterID, $activity)
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
        $this->addFilter("periode='$periode'");
        $this->addFilter("master_id='$masterID'");
        $this->addFilter("activity='$activity'");
//        $this->addFilter("cabang_id='$cabang_id'");

        $this->db->group_start();
        $this->db->where(array("cabang_id" => $cabang_id));
        $this->db->or_where(array("cabang2_id" => $cabang2_id));
        $this->db->or_where(array("cabang_id" => $cabang2_id));
        $this->db->or_where(array("cabang2_id" => $cabang_id));
        $this->db->group_end();


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
        showLast_query("biru");

        if (sizeof($tmp) > 0) {
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
        }

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