<?php


class ComTransaksiProdukRequest extends MdlMother
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
        "jenis",
        "npwp",
        "fulldate",
        "gudang_id",
        "gudang_nama",
        "harga",
        "harga_avg",
        "harga_awal",
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
    );
    private $periode = array("forever");


    public function __construct()
    {
        $this->tableName_fifoAvg = "fifo_avg";
        $this->tableName = "z_transaksi_produk_request_cache";
        $this->tableName_master = array(
            "mutasi" => "z_transaksi_produk_request_mutasi",
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


        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            foreach ($this->periode as $periode) {
                foreach ($this->inParams as $array_params) {
                    $arrRekening = array();
                    foreach ($array_params['loop'] as $key => $x) {
                        $lCounter++;
                        $value_item = $array_params['static']['produk_nilai'];
                        $unit = $array_params['static']['produk_qty'];

                        $value = $value_item * $unit;
                        $position = $value > 0 ? "debet" : "kredit";

                        $arrRekening[] = $key;
//                        $table = heReturnTableName($this->tableName_master, $arrRekening);
                        $this->tableName_mutasi = $this->tableName_master["mutasi"];

//                        $gudangID = isset($array_params['static']['gudang_id']) ? $array_params['static']['gudang_id'] : "";
                        if(isset($array_params['static']['gudang_id'])){
                            $gudangID = $array_params['static']['gudang_id'];
                        }
                        else{
                            $msg = "Gagal menyimpan transaksi karena Lokasi gudang tidak terdaftar. Silahkan hubungi admin.";
                            die(lgShowAlertBiru($msg));
                        }

                        $_preValues = $this->cekPreValue(
                            $key,
                            $array_params['static']['cabang_id'],
                            $periode,
                            $array_params['static']['extern_id'],
                            $gudangID,
                            $array_params['static']['fulldate']
                        );

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
                            $preNumber = $_preValues['cache']['debet'];
                        }
                        else {
                            $preNumber = $_preValues['cache']['kredit'] * -1;
                        }
                        if ($_preValues['cache']['qty_debet'] > 0) {
                            $preQtyNumber = $_preValues['cache']['qty_debet'];
                        }
                        else {
                            $preQtyNumber = $_preValues['cache']['qty_kredit'] * -1;
                        }

                        $afterNumber = $preNumber + $value;
                        $afterQtyNumber = $preQtyNumber + $unit;

                        $afterPosition = $afterNumber > 0 ? "debet":"kredit";
                        $afterQtyPosition = $afterQtyNumber > 0 ? "debet":"kredit";
                        $afterNumberAvg = $afterQtyNumber == 0 ? 0 : $afterNumber / $afterQtyNumber;

//                        if (in_array($key, $configBalanceProtections)) {
//                            if ($afterPosition != detectRekDefaultPosition($key) && ($afterQtyNumber != 0)) {
//                                die(lgShowAlert("insufficient balance for $key."));
//                            }
//                        }

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

                            $this->outParams[$lCounter]["cache"][$mode]["rek_id"] = 0;
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

                                    $this->outParams[$lCounter]["mutasi"]["rek_id"] = 0;
                                    $this->outParams[$lCounter]["mutasi"]["rekening"] = $key;
                                    $this->outParams[$lCounter]["mutasi"]["harga"] = abs($value_item);
                                    $this->outParams[$lCounter]["mutasi"]["harga_avg"] = abs($afterNumberAvg);
                                    $this->outParams[$lCounter]["mutasi"]["harga_awal"] = abs($_preValues['cache']['harga']);

                                    break;
                            }
                        }
                        //  endregion mutasi rekening pembantu


                        //  langsung execution dibawah sini
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
//                                                            cekUngu("$sub_mode :: " . $this->db->last_query());
                                                            break;
                                                        case "update":


                                                            $this->db->where('id', $id);
                                                            $insertIDs[] = $this->db->update($tableName, $pSpec_mode_data);

                                                            break;
                                                    }
                                                }
                                                break;
                                            case "mutasi":

                                                unset($pSpec_mode["tabel"]);


                                                $this->db->insert($tableName_mutasi, $pSpec_mode);
                                                $insertIDs[] = $this->db->insert_id();
                                                cekUngu($this->db->last_query());
                                                break;
                                        }
                                    }
                                }
                                $this->outParams = array();


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


        if (sizeof($insertIDs) > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    private function cekPreValue($rek, $cabang_id, $periode, $produk_id, $gudang_id, $date = NULL)
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

    public function fetchMoves2($rek)
    {//==memanggil saldo2 dari rekening tertentu
        $tableNames = heReturnTableName($this->tableName_master, array($rek));
        $this->db->select("*");
//        $this->db->where(array("extern_id" => $externID));
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

    public function fetchMovement($rek)
    {//==memanggil saldo2 dari rekening tertentu
        $tableNames = heReturnTableName($this->tableName_master, array($rek));
        $this->db->select("*");
        // $this->db->where(array("extern_id" => $externID));
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
}