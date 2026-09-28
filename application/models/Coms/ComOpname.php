<?php


class ComOpname extends MdlMother
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
//        "rekening",
//        "periode",
        "cabang_id",
        "cabang_nama",
        //        "debet_awal",
//        "debet",
        //        "debet_akhir",
        //        "kredit_awal",
//        "kredit",
        //        "kredit_akhir",
        //        "qty_debet_awal",
//        "qty_debet",
        //        "qty_debet_akhir",
        //        "qty_kredit_awal",
//        "qty_kredit",
        //        "qty_kredit_akhir",
//        "dtime",
//        "tgl",
//        "bln",
//        "thn",
        "extern_id",
        "extern_nama",
//        "jenis",
//        "npwp",
//        "fulldate",
        "gudang_id",
        "gudang_nama",
//        "harga",
//        "harga_avg",
//        "harga_awal",
        "dtime_acc_1",
        "acc_id_1",
        "acc_nama_1",
        "dtime_acc_2",
        "acc_id_2",
        "acc_nama_2",
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
        //-----
        "dtime_acc_1",
        "acc_id_1",
        "acc_nama_1",
        "dtime_acc_2",
        "acc_id_2",
        "acc_nama_2",
    );
    private $periode = array("forever");
    protected $jenisTr;
    protected $sortBy = array(
        "kolom" => "id",
        "mode" => "asc",
    );

    public function __construct()
    {
        $this->tableName = "dashboard_opname";
    }

    //  region setter, getter
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
        $this->inParams = $array_params = $inParams;

        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            foreach ($this->periode as $periode) {
//                foreach ($this->inParams as $array_params) {
                $arrRekening = array();
                foreach ($array_params['loop'] as $key => $x) {

                    if (isset($array_params['static']['referensi_id']) && ($array_params['static']['referensi_id'] > 0)) {

                        $value_item = trim($array_params['static']['produk_nilai']);
                        $unit = trim($array_params['static']['produk_qty']);

                        $value = $x;

                        $_preValues = $this->cekPreValue(
                            $array_params['static']['jenis'],
                            $array_params['static']['cabang_id'],
//                            $array_params['static']['produk_id'],
                            $array_params['static']['gudang_id'],
                            $array_params['static']['referensi_id']

                        );
                        if (array_key_exists("id", $_preValues["cache"]) && ($_preValues["cache"]["id"] > 0)) {
                            $_preValues_id = $_preValues["cache"]["id"];
                        }
                        else {

                            mati_disini("Data stok opname tidak lengkap, silahkan diperiksa kembali. code: " . __LINE__);
                        }

                        $mode = "update";
                        foreach ($array_params['static'] as $key => $value) {
                            if (in_array($key, $this->outFields)) {
                                $this->outParams[$lCounter]["cache"][$mode][$key] = $value;
                            }
                        }
                        $this->outParams[$lCounter]["cache"][$mode]["id"] = $_preValues_id;

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

//                                            case "mutasi":
//
//                                                unset($pSpec_mode["tabel"]);
//
//                                                $this->db->insert($tableName_mutasi, $pSpec_mode);
//                                                $insertIDs[] = $this->db->insert_id();
//                                                cekHijau("$mode :: " . $this->db->last_query());
//                                                break;
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
                                cekMerah("::: PERIODE : $periode :::");
                                return false;
                            }
                        }
                        //endregion
                    }
                    else {
                        $insertIDs[$lCounter] = true;
                    }

                }
//                }
            }
        }


        if (sizeof($insertIDs) > 0) {

            return true;
        }
        else {
            return false;
        }
    }

//    private function cekPreValue($jenis, $cabang_id, $produk_id, $gudang_id)
    private function cekPreValue($jenis, $cabang_id, $gudang_id, $referensi_id)
    {
        $this->filters = array();
        $this->addFilter("jenis='$jenis'");
        $this->addFilter("cabang_id='$cabang_id'");
        $this->addFilter("gudang_id='$gudang_id'");
//        $this->addFilter("produk_id='$produk_id'");
        $this->addFilter("id='$referensi_id'");
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
                    "cabang_id" => $row->cabang_id,
                    "gudang_id" => $row->gudang_id,
                    "jenis" => $row->jenis,
//                    "produk_id" => $row->produk_id,
//                    "jml_stok_buku" => $row->jml_stok_buku,
//                    "jml_stok_opname" => $row->jml_stok_opname,
//                    "jml_stok_acc_1" => $row->jml_stok_acc_1,
//                    "jml_stok_acc_2" => $row->jml_stok_acc_2,

                );
            }
        }
        else {
            // bila count($tmp) == 0, maka ambil saldo periode forever dan mode insert
            $this->filters = array();
            $this->addFilter("jenis='$jenis'");
            $this->addFilter("cabang_id='$cabang_id'");
            $this->addFilter("gudang_id='$gudang_id'");
//            $this->addFilter("produk_id='$produk_id'");
            $this->addFilter("id='$referensi_id'");
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
                        "id" => $row->id,
                        "cabang_id" => $row->cabang_id,
                        "gudang_id" => $row->gudang_id,
                        "jenis" => $row->jenis,
//                        "produk_id" => $row->produk_id,
//                        "jml_stok_buku" => $row->jml_stok_buku,
//                        "jml_stok_opname" => $row->jml_stok_opname,
//                        "jml_stok_acc_1" => $row->jml_stok_acc_1,
//                        "jml_stok_acc_2" => $row->jml_stok_acc_2,
                    );
                }
            }
            else {
                $result["cache"] = array(
                    "cabang_id" => "",
                    "gudang_id" => "",
                    "jenis" => "",

//                    "produk_id" => $row->produk_id,
//                    "jml_stok_buku" => $row->jml_stok_buku,
//                    "jml_stok_opname" => $row->jml_stok_opname,
//                    "jml_stok_acc_1" => $row->jml_stok_acc_1,
//                    "jml_stok_acc_2" => $row->jml_stok_acc_2,
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


}