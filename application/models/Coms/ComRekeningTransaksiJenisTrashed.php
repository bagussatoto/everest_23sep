<?php


class ComRekeningTransaksiJenisTrashed extends MdlMother
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
        "jenis",
        "npwp",
        "fulldate",
        "gudang_id",
        "gudang_nama",
        "harga",
        "harga_avg",
        "harga_awal",
        "transaksi_id",
//        "master_id",
        "dtime_order",
        "dtime_kirim",
        "dtime_terima",
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
//        "master_id",
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
        "transaksi_tipe",
    );
    private $periode = array("harian", "bulanan", "tahunan", "forever");


    public function __construct()
    {

        $this->tableName = "z_rekening_transaksi_jenis_cache";
        $this->tableName_master = array(
            "mutasi" => "z_rekening_transaksi_jenis_mutasi",
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

                if (sizeof($array_params['static']) > 0) {

                    $this->tableName_mutasi = $this->tableName_master["mutasi"];
                    $trash = $array_params['static']['trash'];
                    $masterID = $array_params['static']['master_id'];

                    //--------------------
                    $criteria = array(
                        "master_id" => $masterID,
                    );
                    $this->db->select('*');
                    $this->db->where($criteria);
                    $tmp = $this->db->get($this->tableName_mutasi);
                    if (sizeof($tmp) > 0) {
                        $data = array(
                            "trash" => $trash,
                        );
                        $this->db->where($criteria);
                        $this->db->update($this->tableName_mutasi, $data);
                        showLast_query("hitam");
                    }
                    //--------------------
                }

            }
        }

        return true;

//        if (sizeof($insertIDs) > 0) {
//
//        }
//        else {
//            return false;
//        }
    }

    private function cekPreValue($rek, $cabang_id, $periode, $produk_id, $gudang_id, $date = NULL, $jenisTr)
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
//        $this->addFilter("extern_id='$produk_id'");
        $this->addFilter("jenis='$jenisTr'");
//        $this->addFilter("master_id='$master_id'");
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
        showLast_query("kuning");

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
//            $this->addFilter("extern_id='$produk_id'");
            $this->addFilter("jenis='$jenisTr'");
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
            showLast_query("kuning");

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


}