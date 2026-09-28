<?php


class ComRekeningTransaksiPembantuTrashed extends MdlMother
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
        "extern2_id",
        "extern2_nama",
        "jenis",
        "npwp",
        "fulldate",
        "gudang_id",
        "gudang_nama",
        "harga",
        "harga_avg",
        "harga_awal",
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
        "_step_3_nomer",
        "_step_3_olehName",
        "_step_4_nomer",
        "_step_4_olehName",
        "_step_5_nomer",
        "_step_5_olehName",
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
    );
    private $periode = array("forever");
    protected $jenisTr;
    protected $sortBy = array(
        "kolom" => "id",
        "mode" => "desc",
    );

    public function __construct()
    {

        $this->tableName = "z_rekening_transaksi_pembantu_cache";
        $this->tableName_master = array(
            "mutasi" => "z_rekening_transaksi_pembantu_mutasi",
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
                foreach ($this->inParams as $array_params){
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
        }

        return true;
//
//        if (sizeof($insertIDs) > 0) {
//
//        }
//        else {
//            return false;
//        }
    }

    private function cekPreValue($rek, $cabang_id, $periode, $produk_id, $gudang_id, $date = NULL, $master_id, $transaksi_id, $extern2_id)
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

    public function fetchMovement($rek)
    {//==memanggil saldo2 dari rekening tertentu
        $tableName = $this->tableName_master['mutasi'];
        // $this->db->select("*");
        // $this->db->where(array("extern_id" => $externID));
        if(isset($this->sortBy)){
            $this->db->order_by($this->sortBy['kolom'], $this->sortBy['mode']);
        }
        else{
            $this->db->order_by("id", "desc");
        }
        $all_columns = $this->db->list_fields($tableName);
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

        $result = $this->db->get($tableName);
        //        cekkuning($this->db->last_query());

        return $result->result();
    }

    public function callMovementProduk($reks)
    {
        if(isset($this->jenisTr) && is_array($this->jenisTr)){
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

        // if(isset($this->sortBy)){
        //     $this->db->order_by($this->sortBy['kolom'], $this->sortBy['mode']);
        // }
        $srcPersediaans = $this->fetchMovement($reks);
        $produkIds = array();
        $transaksiIds = array();
        foreach ($srcPersediaans as $src) {
            // $produkIds[] = $src->extern_id;
            // $transaksiIds[] = $src->transaksi_id;
            $produkIds[$src->extern_id] = $src->extern_id;
            $transaksiIds[$src->transaksi_id] = $src->transaksi_id;
        }
        showLast_query("biru");
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

}