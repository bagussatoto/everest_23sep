<?php

//--include_once "MdlHistoriData.php";
class MdlReport extends CI_Model
{

    protected $tableName = array();
    protected $fields = array();
    protected $indexFields;
    // protected $validationRules = array();
    // protected $listedFieldsSelectFolder = array();
    // protected $listedFieldsViewFolder = array();
    // protected $listedFieldsSelectItem = array();
    // protected $listedFieldsViewItem = array();
    // protected $listedFieldsFormFolder = array();
    // protected $listedFieldsFormItem = array();
    // protected $listedFieldsForm = array();
    // protected $listedFieldsHidden = array();
    // protected $kategori;
    // protected $kategori_id;
    // protected $search;
    private $tabel;
    private $debug;
    private $periode;
    private $order;
    private $limit;

    private $tanggal;
    private $minggu;
    private $bulan;
    private $tahun;
    private $datas = array();
    private $dataGlundungs = array();
    private $dataPp = array();
    private $dataP = array();
    private $jenis;
    private $condites;
    private $conditesCompared;


    //<editor-fold desc="geter setter">
    public function getDataPp()
    {
        return $this->dataPp;
    }

    public function setDataPp($dataPp)
    {
        $this->dataPp = $dataPp;
    }


    public function getDataGlundungs()
    {
        return $this->dataGlundungs;
    }

    public function setDataGlundungs($dataGlundungs)
    {
        $this->dataGlundungs = $dataGlundungs;
    }

    public function setOrder($order)
    {
        $this->order = $order;
    }

    public function setPeriode($periode)
    {
        $this->periode = $periode;
    }

    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    public function setTanggal($tanggal)
    {
        $this->tanggal = $tanggal;
    }

    public function setMinggu($minggu)
    {
        $this->minggu = $minggu;
    }

    public function setBulan($bulan)
    {
        $this->bulan = $bulan;
    }

    public function setTahun($tahun)
    {
        $this->tahun = $tahun;
    }

    // public function setTableName($tableName)
    // {
    //     $this->tableName = $tableName;
    // }

    public function setDatas($datas)
    {
        $this->datas = $datas;
    }

    public function setJenis($jenis)
    {
        $this->jenis = $jenis;
    }

    public function setCondites($condites)
    {
        $this->condites = $condites;
    }

    public function setConditesCompared($conditesCompared)
    {
        $this->conditesCompared = $conditesCompared;
    }

    public function setTabel($tabel)
    {
        $this->tabel = $tabel;
    }

    /**
     * @return array
     */
    public function getDataP()
    {
        return $this->dataP;
    }

    /**
     * @param array $dataP
     */
    public function setDataP($dataP)
    {
        $this->dataP = $dataP;
    }

    //</editor-fold>

    function __construct()
    {
        $this->db2 = $this->load->database('report', TRUE);
        $this->tableNames = array(
            // "582a"   => "penjualan_produk_seller",
            // "582spd" => "penjualan_seller_produk",
            "pre_penjualan" => array(
                "tableName" => "pre_penjualan_seller_produk",
            ),
            "pre_penjualan_canceled" => array(
                "tableName" => "pre_penjualan_seller_produk_canceled",
            ),
            "penjualan" => array(
                "tableName" => array(
                    "psp" => "penjualan_seller_produk",
                    "ps" => "penjualan_seller",
                    "pp" => "penjualan_produk",
                    "p" => "penjualan",
                ),
                "jenis" => array("582spd", "982"),
                "tableName_2" => "penjualan_seller",
            ),
            "hpp" => array(
                "tableName" => array(
                    "psp" => "hpp_seller_produk",
                    "ps" => "hpp_seller",
                    "pp" => "hpp_produk",
                    "p" => "hpp",
                ),
                "jenis" => array("582spd", "982"),
                "tableName_2" => "hpp_seller",
            ),
            "biaya" => array(
                "tableName" => array(
                    "psp" => "biaya_seller_produk",
                    "ps" => "biaya_seller",
                    "pp" => "biaya_produk",
                    "p" => "biaya",
                ),

                "jenis" => array("4449", "2674", "2676", "888_1", "1675", "1463", "2675", "2677", "1677"),
                "tableName_2" => "biaya_seller",
            ),
            "pembelian_supplies" => array(
                "tableName" => "pembelian_vendor_supplies",
                "jenis" => array("461", "961"),
            ),
            "pembelian_produk" => array(
                "tableName" => "pembelian_vendor_produk",
                "jenis" => array("467", "967"),
            ),
        );
        $this->updKoloms = array(
            "unit_ot",
            "unit_in",
            "unit_af",
            "nilai_ot",
            "nilai_in",
            "nilai_af",
            // "counter",
        );
        $this->tabels = array(
            "cabang" => "cabang",
            "subject" => "seller",
            "object" => "produk",
        );

    }

    public function updateData($where, $datas)
    {
        $tbl = isset($this->tableName) ? $this->tableName : matiHere(__METHOD__ . " tablename belum diset");
        // $criteria = array();
        // $criteria2 = "";
        // if (sizeof($this->filters) > 0) {
        //     $this->fetchCriteria();
        //     $criteria = $this->getCriteria();
        //     $criteria2 = $this->getCriteria2();
        // }
        // if (sizeof($criteria) > 0) {
        //     $this->db->where($criteria);
        // }
        // if ($criteria2 != "") {
        //     $this->db->where($criteria2);
        // }
        $this->db2->where($where);
        $this->db2->update($tbl, $datas);
        if ($this->debug === true) {
            cekMerah($this->db2->last_query());
        }

        return true;
    }

    public function addData($data)
    {
        $tbl = isset($this->tableName) ? $this->tableName : matiHere(__METHOD__ . " tablename belum diset");
        //        $this->db->insert($this->tableName, $data);
        //        return $this->db->insert_id();
        //        arrprint($data);
        //        arrprint($this->filters);
        // if (sizeof($this->filters) > 0) {
        //     $fCnt = 0;
        //     foreach ($this->filters as $f) {
        //         //                cekbiru($f);
        //         $strF = explode("=", $f);
        //         //                arrprint($data);
        //         if (sizeof($strF) > 1) {
        //             $tmpKey = $strF[0];
        //             $exKey = explode(".", $tmpKey);
        //             if (sizeof($exKey) > 1) {
        //                 $origKey = $exKey[1];
        //                 if (isset($data[$tmpKey])) {
        //                     cekhijau("removing $f, replaced by $origKey");
        //                     $data[$origKey] = $data[$tmpKey];
        //                     unset($data[$tmpKey]);
        //                 }
        //             } else {
        //                 //                        cekhijau("NOT removing $f");
        //                 $origKey = $tmpKey;
        //             }
        //             if (!isset($data[$origKey])) {
        //                 $data[$strF[0]] = trim($strF[1], "'");
        //             }
        //         }
        //
        //     }
        //     //            arrprint($data);
        // }
        $this->db2->insert($tbl, $data);

        if ($this->debug === true) {
            cekBiru($this->db2->last_query());
        }

        return $this->db2->insert_id();
    }

    // function lookupPenjualanProdukSeller($subject_id, $object_id)
    // {
    //     // $dbReport = $this->load->database('report', TRUE);
    //     $dbReport = $this->db2;
    //     $arrWhere = array(
    //         "object_id ="  => $subject_id,
    //         "subject_id =" => $object_id,
    //     );
    //
    //     $dbReport->where($arrWhere);
    //     $dbReport->order_by('id');
    //     $q = $dbReport->get($this->tableName);
    //
    //     if ($this->debug === true) {
    //         cekHijau($dbReport->last_query());
    //     }
    //
    //     return $q;
    // }
    // --------------------------------------
    public function lookupPenjualanSellerProduk($seller_id, $produk_id)
    {
        $subject_id = $seller_id;
        $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["psp"];
        $dbReport = $this->db2;

        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        switch ($periode) {
            case "harian":
                isset($this->tanggal) ? $dbReport->where("tanggal", $this->tanggal) : "";
                break;
            case "mingguan":
                if (isset($this->minggu)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "mg" => $this->minggu,
                        "th" => $this->tahun,
                    ));
                }

                break;
            case "bulanan":
                if (isset($this->bulan)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "bl" => $this->bulan,
                        "th" => $this->tahun,
                    ));
                }
                break;
            case "tahunan":
                isset($this->tahun) ? $dbReport->where("th", $this->tahun) : "";
                break;
        }

        $arrWhere = array(
            "subject_id =" => $subject_id,
            "object_id =" => $object_id,
            // "periode ="    => $periode,
        );
        $arrWhere["periode ="] = $periode;

        $dbReport->where($arrWhere);

        if (isset($this->order)) {
            $dbReport->order_by($this->order);
        }
        else {
            $dbReport->order_by('id ASC');
        }

        if (isset($this->limit)) {
            $dbReport->limit($this->limit);
        }

        $q = $dbReport->get($tableName);

        if ($this->debug === true) {
            cekKuning($dbReport->last_query());
        }

        return $q;

    }

    public function lastCounterPeriode()
    {
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        // arrPrintWebs($this->tableNames);
        // cekHere($jenis);
        $tbl = $this->tableNames[$jenis]["tableName"]["psp"];
        // $tableName = "penjualan_seller_produk";
        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";
        // arrPrintWebs($tableName);


        $this->db2->where("periode", $periode);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function writePenjualanSellerProduk($seller_id, $produk_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $subject_id = $seller_id;
        $object_id = $produk_id;
        $tableName = $this->tableNames[$jenis]["tableName"]["psp"];
        // $dbReport = $this->db2;
        $updKoloms = $this->updKoloms;

        if (isset($this->datas)) {
            $datas = $this->datas;
            $datas['periode'] = $this->periode;
        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        $tmp_3 = $this->lookupPenjualanSellerProduk($subject_id, $object_id)->result();
        // arrPrint($tmp_3);
        // arrPrint($datas);
        // matiHere(__FILE__);
        $this->tableName = $tableName;
        if (sizeof($tmp_3) == 0) {
            $this->addData($datas);
        }
        else {

            $dbDatas = $tmp_3[0];
            // $newCounter = $dbDatas->counter +1;
            foreach ($updKoloms as $updKolom) {
                $$updKolom = $dbDatas->$updKolom + $datas[$updKolom];

                $updDatas[$updKolom] = $dbDatas->$updKolom + $datas[$updKolom];
            }
            $condite = array(
                "id" => $dbDatas->id,
            );

            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }


    //region hpp
    public function writeHpp()
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        // $subject_id = $produk_id;
        // $object_id = $produk_id;
        $tableName = $this->tableNames[$jenis]["tableName"]["p"];
        // $dbReport = $this->db2;
        $updKoloms = $this->updKoloms;

        if (isset($this->dataP)) {
            $datas = $this->dataP;
            $datas['periode'] = $this->periode;
        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterHppPeriode()->result();

        if (sizeof($tmp_c) > 0) {
            $tCounter = $tmp_c[0];
            $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
            $lastTanggal = $tCounter->tanggal;

            if ($tanggal != $lastTanggal) {
                $counter = $lastCounter + 1;
            }
            else {
                $counter = $lastCounter;
            }
        }
        else {
            $counter = 1;
            $lastCounter = 0;
            $lastTanggal = "";
        }
        //endregion
        $datas["counter"] = $counter;

        $tmp_3 = $this->lookupHpp()->result();
        // arrPrint($tmp_3);
        // arrPrintWebs($datas);
        // matiHere(__FILE__ ." @". __LINE__);
        $this->tableName = $tableName;
        if (sizeof($tmp_3) == 0) {
            $this->addData($datas);
        }
        else {

            $dbDatas = $tmp_3[0];
            // $newCounter = $dbDatas->counter +1;
            foreach ($updKoloms as $updKolom) {
                $$updKolom = $dbDatas->$updKolom + $datas[$updKolom];

                $updDatas[$updKolom] = $dbDatas->$updKolom + $datas[$updKolom];
            }
            $condite = array(
                "id" => $dbDatas->id,
            );

            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    public function lastCounterHppPeriode()
    {
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        // arrPrintWebs($this->tableNames);
        // cekHere($jenis);
        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["p"];
        // $tableName = "hpp_seller_produk";
        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";
        // arrPrintWebs($tableName);


        $this->db2->where("periode", $periode);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function lookupHpp()
    {
        // $subject_id = $produk_id;
        // $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["p"];
        $dbReport = $this->db2;

        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        switch ($periode) {
            case "harian":
                isset($this->tanggal) ? $dbReport->where("tanggal", $this->tanggal) : "";
                break;
            case "mingguan":
                if (isset($this->minggu)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "mg" => $this->minggu,
                        "th" => $this->tahun,
                    ));
                }

                break;
            case "bulanan":
                if (isset($this->bulan)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "bl" => $this->bulan,
                        "th" => $this->tahun,
                    ));
                }
                break;
            case "tahunan":
                isset($this->tahun) ? $dbReport->where("th", $this->tahun) : "";
                break;
        }

        $arrWhere = array(
            // "subject_id =" => $subject_id,
            // "object_id ="  => $object_id,
            "cabang_id =" => 0,
        );
        $arrWhere["periode ="] = $periode;

        $dbReport->where($arrWhere);

        if (isset($this->order)) {
            $dbReport->order_by($this->order);
        }
        else {
            $dbReport->order_by('id ASC');
        }

        if (isset($this->limit)) {
            $dbReport->limit($this->limit);
        }

        $q = $dbReport->get($tableName);

        if ($this->debug === true) {
            cekKuning("$periode ::" . $dbReport->last_query());
        }
        // matiHere(__METHOD__ . " periode::$periode");
        return $q;

    }

    public function writeHppProduk($produk_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $subject_id = $produk_id;
        // $object_id = $produk_id;
        $tableName = $this->tableNames[$jenis]["tableName"]["pp"];
        // $dbReport = $this->db2;
        $updKoloms = $this->updKoloms;

        if (isset($this->dataPp)) {
            $datas = $this->dataPp;
            $datas['periode'] = $this->periode;
        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterHppProdukPeriode()->result();

        if (sizeof($tmp_c) > 0) {
            $tCounter = $tmp_c[0];
            $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
            $lastTanggal = $tCounter->tanggal;

            if ($tanggal != $lastTanggal) {
                $counter = $lastCounter + 1;
            }
            else {
                $counter = $lastCounter;
            }
        }
        else {
            $counter = 1;
            $lastCounter = 0;
            $lastTanggal = "";
        }
        //endregion
        $datas["counter"] = $counter;

        $tmp_3 = $this->lookupHppProduk($subject_id)->result();
        // arrPrint($tmp_3);
        // arrPrintWebs($datas);
        // matiHere(__FILE__ ." @". __LINE__);
        $this->tableName = $tableName;
        if (sizeof($tmp_3) == 0) {
            $this->addData($datas);
        }
        else {

            $dbDatas = $tmp_3[0];
            // $newCounter = $dbDatas->counter +1;
            foreach ($updKoloms as $updKolom) {
                $$updKolom = $dbDatas->$updKolom + $datas[$updKolom];

                $updDatas[$updKolom] = $dbDatas->$updKolom + $datas[$updKolom];
            }
            $condite = array(
                "id" => $dbDatas->id,
            );

            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    public function lastCounterHppProdukPeriode()
    {
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        // arrPrintWebs($this->tableNames);
        // cekHere($jenis);
        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["pp"];
        // $tableName = "hpp_seller_produk";
        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";
        // arrPrintWebs($tableName);


        $this->db2->where("periode", $periode);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function lookupHppProduk($produk_id)
    {
        $subject_id = $produk_id;
        // $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["pp"];
        $dbReport = $this->db2;

        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        switch ($periode) {
            case "harian":
                isset($this->tanggal) ? $dbReport->where("tanggal", $this->tanggal) : "";
                break;
            case "mingguan":
                if (isset($this->minggu)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "mg" => $this->minggu,
                        "th" => $this->tahun,
                    ));
                }

                break;
            case "bulanan":
                if (isset($this->bulan)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "bl" => $this->bulan,
                        "th" => $this->tahun,
                    ));
                }
                break;
            case "tahunan":
                isset($this->tahun) ? $dbReport->where("th", $this->tahun) : "";
                break;
        }

        $arrWhere = array(
            "subject_id =" => $subject_id,
            // "object_id ="  => $object_id,
            "cabang_id =" => 0,
        );
        $arrWhere["periode ="] = $periode;

        $dbReport->where($arrWhere);

        if (isset($this->order)) {
            $dbReport->order_by($this->order);
        }
        else {
            $dbReport->order_by('id ASC');
        }

        if (isset($this->limit)) {
            $dbReport->limit($this->limit);
        }

        $q = $dbReport->get($tableName);

        if ($this->debug === true) {
            cekKuning($dbReport->last_query());
        }

        return $q;

    }

    public function writeHppCabang($cabang_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        // $subject_id = $produk_id;
        // $object_id = $produk_id;
        $tableName = $this->tableNames[$jenis]["tableName"]["p"];
        // $dbReport = $this->db2;
        $updKoloms = $this->updKoloms;

        if (isset($this->dataP)) {
            $datas = $this->dataP;
            $datas['periode'] = $this->periode;
        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterHppCabangPeriode($cabang_id)->result();

        if (sizeof($tmp_c) > 0) {
            $tCounter = $tmp_c[0];
            $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
            $lastTanggal = $tCounter->tanggal;

            if ($tanggal != $lastTanggal) {
                $counter = $lastCounter + 1;
            }
            else {
                $counter = $lastCounter;
            }
        }
        else {
            $counter = 1;
            $lastCounter = 0;
            $lastTanggal = "";
        }
        //endregion
        $datas["counter"] = $counter;
        $datas["cabang_id"] = $cabang_id;

        $tmp_3 = $this->lookupHppCabang($cabang_id)->result();
        // arrPrint($tmp_3);
        // arrPrintWebs($datas);
        // matiHere(__FILE__ ." @". __LINE__);
        $this->tableName = $tableName;
        if (sizeof($tmp_3) == 0) {
            $this->addData($datas);
        }
        else {

            $dbDatas = $tmp_3[0];
            // $newCounter = $dbDatas->counter +1;
            foreach ($updKoloms as $updKolom) {
                $$updKolom = $dbDatas->$updKolom + $datas[$updKolom];

                $updDatas[$updKolom] = $dbDatas->$updKolom + $datas[$updKolom];
            }
            $condite = array(
                "id" => $dbDatas->id,
            );

            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    public function lastCounterHppCabangPeriode($cabang_id)
    {
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        // arrPrintWebs($this->tableNames);
        // cekHere($jenis);
        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["p"];
        // $tableName = "hpp_seller_produk";
        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";
        // arrPrintWebs($tableName);


        $this->db2->where("periode", $periode);
        $this->db2->where("cabang_id", $cabang_id);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function lookupHppCabang($cabang_id)
    {
        // $subject_id = $produk_id;
        // $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["p"];
        $dbReport = $this->db2;

        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        switch ($periode) {
            case "harian":
                isset($this->tanggal) ? $dbReport->where("tanggal", $this->tanggal) : "";
                break;
            case "mingguan":
                if (isset($this->minggu)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "mg" => $this->minggu,
                        "th" => $this->tahun,
                    ));
                }

                break;
            case "bulanan":
                if (isset($this->bulan)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "bl" => $this->bulan,
                        "th" => $this->tahun,
                    ));
                }
                break;
            case "tahunan":
                isset($this->tahun) ? $dbReport->where("th", $this->tahun) : "";
                break;
        }

        $arrWhere = array(
            // "subject_id =" => $subject_id,
            // "object_id ="  => $object_id,
            "cabang_id =" => $cabang_id,
        );
        $arrWhere["periode ="] = $periode;

        $dbReport->where($arrWhere);

        if (isset($this->order)) {
            $dbReport->order_by($this->order);
        }
        else {
            $dbReport->order_by('id ASC');
        }

        if (isset($this->limit)) {
            $dbReport->limit($this->limit);
        }

        $q = $dbReport->get($tableName);

        if ($this->debug === true) {
            cekKuning($dbReport->last_query());
        }

        return $q;

    }

    public function writeHppProdukCabang($produk_id, $cabang_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $subject_id = $produk_id;
        // $object_id = $produk_id;
        $tableName = $this->tableNames[$jenis]["tableName"]["pp"];
        // $dbReport = $this->db2;
        $updKoloms = $this->updKoloms;

        if (isset($this->dataPp)) {
            $datas = $this->dataPp;
            $datas['periode'] = $this->periode;
        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterHppProdukCabangPeriode($cabang_id)->result();

        if (sizeof($tmp_c) > 0) {
            $tCounter = $tmp_c[0];
            $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
            $lastTanggal = $tCounter->tanggal;

            if ($tanggal != $lastTanggal) {
                $counter = $lastCounter + 1;
            }
            else {
                $counter = $lastCounter;
            }
        }
        else {
            $counter = 1;
            $lastCounter = 0;
            $lastTanggal = "";
        }
        //endregion
        $datas["counter"] = $counter;
        $datas["cabang_id"] = $cabang_id;

        $tmp_3 = $this->lookupHppProdukCabang($subject_id, $cabang_id)->result();
        // arrPrint($tmp_3);
        // arrPrintWebs($datas);
        // matiHere(__FILE__ ." @". __LINE__);
        $this->tableName = $tableName;
        if (sizeof($tmp_3) == 0) {
            $this->addData($datas);
        }
        else {

            $dbDatas = $tmp_3[0];
            // $newCounter = $dbDatas->counter +1;
            foreach ($updKoloms as $updKolom) {
                $$updKolom = $dbDatas->$updKolom + $datas[$updKolom];

                $updDatas[$updKolom] = $dbDatas->$updKolom + $datas[$updKolom];
            }
            $condite = array(
                "id" => $dbDatas->id,
            );

            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    public function lastCounterHppProdukCabangPeriode($cabang_id)
    {
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        // arrPrintWebs($this->tableNames);
        // cekHere($jenis);
        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["pp"];
        // $tableName = "hpp_seller_produk";
        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";
        // arrPrintWebs($tableName);


        $this->db2->where("periode", $periode);
        $this->db2->where("cabang_id", $cabang_id);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function lookupHppProdukCabang($produk_id, $cabang_id)
    {
        $subject_id = $produk_id;
        // $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["pp"];
        $dbReport = $this->db2;

        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        switch ($periode) {
            case "harian":
                isset($this->tanggal) ? $dbReport->where("tanggal", $this->tanggal) : "";
                break;
            case "mingguan":
                if (isset($this->minggu)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "mg" => $this->minggu,
                        "th" => $this->tahun,
                    ));
                }

                break;
            case "bulanan":
                if (isset($this->bulan)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "bl" => $this->bulan,
                        "th" => $this->tahun,
                    ));
                }
                break;
            case "tahunan":
                isset($this->tahun) ? $dbReport->where("th", $this->tahun) : "";
                break;
        }

        $arrWhere = array(
            "subject_id =" => $subject_id,
            // "object_id ="  => $object_id,
            "cabang_id =" => $cabang_id,
        );
        $arrWhere["periode ="] = $periode;

        $dbReport->where($arrWhere);

        if (isset($this->order)) {
            $dbReport->order_by($this->order);
        }
        else {
            $dbReport->order_by('id ASC');
        }

        if (isset($this->limit)) {
            $dbReport->limit($this->limit);
        }

        $q = $dbReport->get($tableName);

        if ($this->debug === true) {
            cekKuning($dbReport->last_query());
        }

        return $q;

    }

    public function writeHppSeller($seller_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $subject_id = $seller_id;
        // $object_id = $produk_id;
        $tableName = $this->tableNames[$jenis]["tableName"]["ps"];
        // $dbReport = $this->db2;
        $updKoloms = $this->updKoloms;

        if (isset($this->dataGlundungs)) {
            $datas = $this->dataGlundungs;
            $datas['periode'] = $this->periode;
        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        $tmp_3 = $this->lookupHppSeller($subject_id)->result();
        // arrPrint($tmp_3);
        // arrPrintWebs($datas);
        // matiHere(__FILE__ ." @". __LINE__);
        $this->tableName = $tableName;
        if (sizeof($tmp_3) == 0) {
            $this->addData($datas);
        }
        else {

            $dbDatas = $tmp_3[0];
            // $newCounter = $dbDatas->counter +1;
            foreach ($updKoloms as $updKolom) {
                $$updKolom = $dbDatas->$updKolom + $datas[$updKolom];

                $updDatas[$updKolom] = $dbDatas->$updKolom + $datas[$updKolom];
            }
            $condite = array(
                "id" => $dbDatas->id,
            );

            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    public function lastCounterHppSellerPeriode()
    {
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        // arrPrintWebs($this->tableNames);
        // cekHere($jenis);
        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["ps"];
        // $tableName = "hpp_seller_produk";
        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";
        // arrPrintWebs($tableName);


        $this->db2->where("periode", $periode);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function lookupHppSeller($seller_id)
    {
        $subject_id = $seller_id;
        // $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["ps"];
        $dbReport = $this->db2;

        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        switch ($periode) {
            case "harian":
                isset($this->tanggal) ? $dbReport->where("tanggal", $this->tanggal) : "";
                break;
            case "mingguan":
                if (isset($this->minggu)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "mg" => $this->minggu,
                        "th" => $this->tahun,
                    ));
                }

                break;
            case "bulanan":
                if (isset($this->bulan)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "bl" => $this->bulan,
                        "th" => $this->tahun,
                    ));
                }
                break;
            case "tahunan":
                isset($this->tahun) ? $dbReport->where("th", $this->tahun) : "";
                break;
        }

        $arrWhere = array(
            "subject_id =" => $subject_id,
            // "object_id ="  => $object_id,
            // "periode ="    => $periode,
        );
        $arrWhere["periode ="] = $periode;

        $dbReport->where($arrWhere);

        if (isset($this->order)) {
            $dbReport->order_by($this->order);
        }
        else {
            $dbReport->order_by('id ASC');
        }

        if (isset($this->limit)) {
            $dbReport->limit($this->limit);
        }

        $q = $dbReport->get($tableName);

        if ($this->debug === true) {
            cekKuning($dbReport->last_query());
        }

        return $q;

    }

    public function writeHppSellerProduk($seller_id, $produk_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $subject_id = $seller_id;
        $object_id = $produk_id;
        $tableName = $this->tableNames[$jenis]["tableName"]["psp"];
        // $dbReport = $this->db2;
        $updKoloms = $this->updKoloms;

        if (isset($this->datas)) {
            $datas = $this->datas;
            $datas['periode'] = $this->periode;
        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        $tmp_3 = $this->lookupHppSellerProduk($subject_id, $object_id)->result();
        // arrPrint($tmp_3);
        // arrPrint($datas);
        // matiHere(__FILE__);
        $this->tableName = $tableName;
        if (sizeof($tmp_3) == 0) {
            $this->addData($datas);
        }
        else {

            $dbDatas = $tmp_3[0];
            // $newCounter = $dbDatas->counter +1;
            foreach ($updKoloms as $updKolom) {
                $$updKolom = $dbDatas->$updKolom + $datas[$updKolom];

                $updDatas[$updKolom] = $dbDatas->$updKolom + $datas[$updKolom];
            }
            $condite = array(
                "id" => $dbDatas->id,
            );

            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    public function lookupHppSellerProduk($seller_id, $produk_id)
    {
        $subject_id = $seller_id;
        $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["psp"];
        $dbReport = $this->db2;

        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        switch ($periode) {
            case "harian":
                isset($this->tanggal) ? $dbReport->where("tanggal", $this->tanggal) : "";
                break;
            case "mingguan":
                if (isset($this->minggu)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "mg" => $this->minggu,
                        "th" => $this->tahun,
                    ));
                }

                break;
            case "bulanan":
                if (isset($this->bulan)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "bl" => $this->bulan,
                        "th" => $this->tahun,
                    ));
                }
                break;
            case "tahunan":
                isset($this->tahun) ? $dbReport->where("th", $this->tahun) : "";
                break;
        }

        $arrWhere = array(
            "subject_id =" => $subject_id,
            "object_id =" => $object_id,
            // "periode ="    => $periode,
        );
        $arrWhere["periode ="] = $periode;

        $dbReport->where($arrWhere);

        if (isset($this->order)) {
            $dbReport->order_by($this->order);
        }
        else {
            $dbReport->order_by('id ASC');
        }

        if (isset($this->limit)) {
            $dbReport->limit($this->limit);
        }

        $q = $dbReport->get($tableName);

        if ($this->debug === true) {
            cekKuning($dbReport->last_query());
        }

        return $q;

    }
    //endregion hpp


    //region biaya
    public function writeBiaya()
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        // $subject_id = $produk_id;
        // $object_id = $produk_id;
        $tableName = $this->tableNames[$jenis]["tableName"]["p"];
        // $dbReport = $this->db2;
        $updKoloms = $this->updKoloms;

        if (isset($this->dataP)) {
            $datas = $this->dataP;
            $datas['periode'] = $this->periode;
        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterBiayaPeriode()->result();

        if (sizeof($tmp_c) > 0) {
            $tCounter = $tmp_c[0];
            $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
            $lastTanggal = $tCounter->tanggal;

            if ($tanggal != $lastTanggal) {
                $counter = $lastCounter + 1;
            }
            else {
                $counter = $lastCounter;
            }
        }
        else {
            $counter = 1;
            $lastCounter = 0;
            $lastTanggal = "";
        }
        //endregion
        $datas["counter"] = $counter;

        $tmp_3 = $this->lookupBiaya()->result();
        // arrPrint($tmp_3);
        // arrPrintWebs($datas);
        // matiHere(__FILE__ ." @". __LINE__);
        $this->tableName = $tableName;
        if (sizeof($tmp_3) == 0) {
            $this->addData($datas);
//            cekHijau($this->db->last_query());
        }
        else {

            $dbDatas = $tmp_3[0];
            // $newCounter = $dbDatas->counter +1;
            foreach ($updKoloms as $updKolom) {
                $$updKolom = $dbDatas->$updKolom + $datas[$updKolom];

                $updDatas[$updKolom] = $dbDatas->$updKolom + $datas[$updKolom];
            }
            $condite = array(
                "id" => $dbDatas->id,
            );

            $this->updateData($condite, $updDatas);
//            cekHijau($this->db->last_query());
            // cekHitam("bagian update");
        }
    }

    public function lastCounterBiayaPeriode()
    {
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        // arrPrintWebs($this->tableNames);
        // cekHere($jenis);
        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["p"];
        // $tableName = "biaya_seller_produk";
        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";
        // arrPrintWebs($tableName);


        $this->db2->where("periode", $periode);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function lookupBiaya()
    {
        // $subject_id = $produk_id;
        // $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["p"];
        $dbReport = $this->db2;

        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        switch ($periode) {
            case "harian":
                isset($this->tanggal) ? $dbReport->where("tanggal", $this->tanggal) : "";
                break;
            case "mingguan":
                if (isset($this->minggu)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "mg" => $this->minggu,
                        "th" => $this->tahun,
                    ));
                }

                break;
            case "bulanan":
                if (isset($this->bulan)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "bl" => $this->bulan,
                        "th" => $this->tahun,
                    ));
                }
                break;
            case "tahunan":
                isset($this->tahun) ? $dbReport->where("th", $this->tahun) : "";
                break;
        }

        $arrWhere = array(
            // "subject_id =" => $subject_id,
            // "object_id ="  => $object_id,
            "cabang_id =" => 0,
        );
        $arrWhere["periode ="] = $periode;

        $dbReport->where($arrWhere);

        if (isset($this->order)) {
            $dbReport->order_by($this->order);
        }
        else {
            $dbReport->order_by('id ASC');
        }

        if (isset($this->limit)) {
            $dbReport->limit($this->limit);
        }

        $q = $dbReport->get($tableName);

        if ($this->debug === true) {
            cekKuning("$periode ::" . $dbReport->last_query());
        }
        // matiHere(__METHOD__ . " periode::$periode");
        return $q;

    }

    public function writeBiayaProduk($produk_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $subject_id = $produk_id;
        // $object_id = $produk_id;
        $tableName = $this->tableNames[$jenis]["tableName"]["pp"];
        // $dbReport = $this->db2;
        $updKoloms = $this->updKoloms;

        if (isset($this->dataPp)) {
            $datas = $this->dataPp;
            $datas['periode'] = $this->periode;
        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterBiayaProdukPeriode()->result();

        if (sizeof($tmp_c) > 0) {
            $tCounter = $tmp_c[0];
            $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
            $lastTanggal = $tCounter->tanggal;

            if ($tanggal != $lastTanggal) {
                $counter = $lastCounter + 1;
            }
            else {
                $counter = $lastCounter;
            }
        }
        else {
            $counter = 1;
            $lastCounter = 0;
            $lastTanggal = "";
        }
        //endregion
        $datas["counter"] = $counter;

        $tmp_3 = $this->lookupBiayaProduk($subject_id)->result();
        // arrPrint($tmp_3);
        // arrPrintWebs($datas);
        // matiHere(__FILE__ ." @". __LINE__);
        $this->tableName = $tableName;
        if (sizeof($tmp_3) == 0) {
            $this->addData($datas);
        }
        else {

            $dbDatas = $tmp_3[0];
            // $newCounter = $dbDatas->counter +1;
            foreach ($updKoloms as $updKolom) {
                $$updKolom = $dbDatas->$updKolom + $datas[$updKolom];

                $updDatas[$updKolom] = $dbDatas->$updKolom + $datas[$updKolom];
            }
            $condite = array(
                "id" => $dbDatas->id,
            );

            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    public function lastCounterBiayaProdukPeriode()
    {
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        // arrPrintWebs($this->tableNames);
        // cekHere($jenis);
        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["pp"];
        // $tableName = "biaya_seller_produk";
        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";
        // arrPrintWebs($tableName);


        $this->db2->where("periode", $periode);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function lookupBiayaProduk($produk_id)
    {
        $subject_id = $produk_id;
        // $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["pp"];
        $dbReport = $this->db2;

        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        switch ($periode) {
            case "harian":
                isset($this->tanggal) ? $dbReport->where("tanggal", $this->tanggal) : "";
                break;
            case "mingguan":
                if (isset($this->minggu)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "mg" => $this->minggu,
                        "th" => $this->tahun,
                    ));
                }

                break;
            case "bulanan":
                if (isset($this->bulan)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "bl" => $this->bulan,
                        "th" => $this->tahun,
                    ));
                }
                break;
            case "tahunan":
                isset($this->tahun) ? $dbReport->where("th", $this->tahun) : "";
                break;
        }

        $arrWhere = array(
            "subject_id =" => $subject_id,
            // "object_id ="  => $object_id,
            "cabang_id =" => 0,
        );
        $arrWhere["periode ="] = $periode;

        $dbReport->where($arrWhere);

        if (isset($this->order)) {
            $dbReport->order_by($this->order);
        }
        else {
            $dbReport->order_by('id ASC');
        }

        if (isset($this->limit)) {
            $dbReport->limit($this->limit);
        }

        $q = $dbReport->get($tableName);

        if ($this->debug === true) {
            cekKuning($dbReport->last_query());
        }

        return $q;

    }

    public function writeBiayaCabang($cabang_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        // $subject_id = $produk_id;
        // $object_id = $produk_id;
        $tableName = $this->tableNames[$jenis]["tableName"]["p"];
        // $dbReport = $this->db2;
        $updKoloms = $this->updKoloms;

        if (isset($this->dataP)) {
            $datas = $this->dataP;
            $datas['periode'] = $this->periode;
        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterBiayaCabangPeriode($cabang_id)->result();

        if (sizeof($tmp_c) > 0) {
            $tCounter = $tmp_c[0];
            $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
            $lastTanggal = $tCounter->tanggal;

            if ($tanggal != $lastTanggal) {
                $counter = $lastCounter + 1;
            }
            else {
                $counter = $lastCounter;
            }
        }
        else {
            $counter = 1;
            $lastCounter = 0;
            $lastTanggal = "";
        }
        //endregion
        $datas["counter"] = $counter;
        $datas["cabang_id"] = $cabang_id;

        $tmp_3 = $this->lookupBiayaCabang($cabang_id)->result();
        // arrPrint($tmp_3);
        // arrPrintWebs($datas);
        // matiHere(__FILE__ ." @". __LINE__);
        $this->tableName = $tableName;
        if (sizeof($tmp_3) == 0) {
            $this->addData($datas);
        }
        else {

            $dbDatas = $tmp_3[0];
            // $newCounter = $dbDatas->counter +1;
            foreach ($updKoloms as $updKolom) {
                $$updKolom = $dbDatas->$updKolom + $datas[$updKolom];

                $updDatas[$updKolom] = $dbDatas->$updKolom + $datas[$updKolom];
            }
            $condite = array(
                "id" => $dbDatas->id,
            );

            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    public function lastCounterBiayaCabangPeriode($cabang_id)
    {
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        // arrPrintWebs($this->tableNames);
        // cekHere($jenis);
        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["p"];
        // $tableName = "biaya_seller_produk";
        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";
        // arrPrintWebs($tableName);


        $this->db2->where("periode", $periode);
        $this->db2->where("cabang_id", $cabang_id);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function lookupBiayaCabang($cabang_id)
    {
        // $subject_id = $produk_id;
        // $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["p"];
        $dbReport = $this->db2;

        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        switch ($periode) {
            case "harian":
                isset($this->tanggal) ? $dbReport->where("tanggal", $this->tanggal) : "";
                break;
            case "mingguan":
                if (isset($this->minggu)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "mg" => $this->minggu,
                        "th" => $this->tahun,
                    ));
                }

                break;
            case "bulanan":
                if (isset($this->bulan)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "bl" => $this->bulan,
                        "th" => $this->tahun,
                    ));
                }
                break;
            case "tahunan":
                isset($this->tahun) ? $dbReport->where("th", $this->tahun) : "";
                break;
        }

        $arrWhere = array(
            // "subject_id =" => $subject_id,
            // "object_id ="  => $object_id,
            "cabang_id =" => $cabang_id,
        );
        $arrWhere["periode ="] = $periode;

        $dbReport->where($arrWhere);

        if (isset($this->order)) {
            $dbReport->order_by($this->order);
        }
        else {
            $dbReport->order_by('id ASC');
        }

        if (isset($this->limit)) {
            $dbReport->limit($this->limit);
        }

        $q = $dbReport->get($tableName);

        if ($this->debug === true) {
            cekKuning($dbReport->last_query());
        }

        return $q;

    }

    public function writeBiayaProdukCabang($produk_id, $cabang_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $subject_id = $produk_id;
        // $object_id = $produk_id;
        $tableName = $this->tableNames[$jenis]["tableName"]["pp"];
        // $dbReport = $this->db2;
        $updKoloms = $this->updKoloms;

        if (isset($this->dataPp)) {
            $datas = $this->dataPp;
            $datas['periode'] = $this->periode;
        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterBiayaProdukCabangPeriode($cabang_id)->result();

        if (sizeof($tmp_c) > 0) {
            $tCounter = $tmp_c[0];
            $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
            $lastTanggal = $tCounter->tanggal;

            if ($tanggal != $lastTanggal) {
                $counter = $lastCounter + 1;
            }
            else {
                $counter = $lastCounter;
            }
        }
        else {
            $counter = 1;
            $lastCounter = 0;
            $lastTanggal = "";
        }
        //endregion
        $datas["counter"] = $counter;
        $datas["cabang_id"] = $cabang_id;

        $tmp_3 = $this->lookupBiayaProdukCabang($subject_id, $cabang_id)->result();
        // arrPrint($tmp_3);
        // arrPrintWebs($datas);
        // matiHere(__FILE__ ." @". __LINE__);
        $this->tableName = $tableName;
        if (sizeof($tmp_3) == 0) {
            $this->addData($datas);
        }
        else {

            $dbDatas = $tmp_3[0];
            // $newCounter = $dbDatas->counter +1;
            foreach ($updKoloms as $updKolom) {
                $$updKolom = $dbDatas->$updKolom + $datas[$updKolom];

                $updDatas[$updKolom] = $dbDatas->$updKolom + $datas[$updKolom];
            }
            $condite = array(
                "id" => $dbDatas->id,
            );

            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    public function lastCounterBiayaProdukCabangPeriode($cabang_id)
    {
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        // arrPrintWebs($this->tableNames);
        // cekHere($jenis);
        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["pp"];
        // $tableName = "biaya_seller_produk";
        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";
        // arrPrintWebs($tableName);


        $this->db2->where("periode", $periode);
        $this->db2->where("cabang_id", $cabang_id);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function lookupBiayaProdukCabang($produk_id, $cabang_id)
    {
        $subject_id = $produk_id;
        // $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["pp"];
        $dbReport = $this->db2;

        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        switch ($periode) {
            case "harian":
                isset($this->tanggal) ? $dbReport->where("tanggal", $this->tanggal) : "";
                break;
            case "mingguan":
                if (isset($this->minggu)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "mg" => $this->minggu,
                        "th" => $this->tahun,
                    ));
                }

                break;
            case "bulanan":
                if (isset($this->bulan)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "bl" => $this->bulan,
                        "th" => $this->tahun,
                    ));
                }
                break;
            case "tahunan":
                isset($this->tahun) ? $dbReport->where("th", $this->tahun) : "";
                break;
        }

        $arrWhere = array(
            "subject_id =" => $subject_id,
            // "object_id ="  => $object_id,
            "cabang_id =" => $cabang_id,
        );
        $arrWhere["periode ="] = $periode;

        $dbReport->where($arrWhere);

        if (isset($this->order)) {
            $dbReport->order_by($this->order);
        }
        else {
            $dbReport->order_by('id ASC');
        }

        if (isset($this->limit)) {
            $dbReport->limit($this->limit);
        }

        $q = $dbReport->get($tableName);

        if ($this->debug === true) {
            cekKuning($dbReport->last_query());
        }

        return $q;

    }

    public function writeBiayaSeller($seller_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $subject_id = $seller_id;
        // $object_id = $produk_id;
        $tableName = $this->tableNames[$jenis]["tableName"]["ps"];
        // $dbReport = $this->db2;
        $updKoloms = $this->updKoloms;

        if (isset($this->dataGlundungs)) {
            $datas = $this->dataGlundungs;
            $datas['periode'] = $this->periode;
        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        $tmp_3 = $this->lookupBiayaSeller($subject_id)->result();
        // arrPrint($tmp_3);
        // arrPrintWebs($datas);
        // matiHere(__FILE__ ." @". __LINE__);
        $this->tableName = $tableName;
        if (sizeof($tmp_3) == 0) {
            $this->addData($datas);
        }
        else {

            $dbDatas = $tmp_3[0];
            // $newCounter = $dbDatas->counter +1;
            foreach ($updKoloms as $updKolom) {
                $$updKolom = $dbDatas->$updKolom + $datas[$updKolom];

                $updDatas[$updKolom] = $dbDatas->$updKolom + $datas[$updKolom];
            }
            $condite = array(
                "id" => $dbDatas->id,
            );

            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    public function lastCounterBiayaSellerPeriode()
    {
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        // arrPrintWebs($this->tableNames);
        // cekHere($jenis);
        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["ps"];
        // $tableName = "biaya_seller_produk";
        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";
        // arrPrintWebs($tableName);


        $this->db2->where("periode", $periode);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function lookupBiayaSeller($seller_id)
    {
        $subject_id = $seller_id;
        // $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["ps"];
        $dbReport = $this->db2;

        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        switch ($periode) {
            case "harian":
                isset($this->tanggal) ? $dbReport->where("tanggal", $this->tanggal) : "";
                break;
            case "mingguan":
                if (isset($this->minggu)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "mg" => $this->minggu,
                        "th" => $this->tahun,
                    ));
                }

                break;
            case "bulanan":
                if (isset($this->bulan)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "bl" => $this->bulan,
                        "th" => $this->tahun,
                    ));
                }
                break;
            case "tahunan":
                isset($this->tahun) ? $dbReport->where("th", $this->tahun) : "";
                break;
        }

        $arrWhere = array(
            "subject_id =" => $subject_id,
            // "object_id ="  => $object_id,
            // "periode ="    => $periode,
        );
        $arrWhere["periode ="] = $periode;

        $dbReport->where($arrWhere);

        if (isset($this->order)) {
            $dbReport->order_by($this->order);
        }
        else {
            $dbReport->order_by('id ASC');
        }

        if (isset($this->limit)) {
            $dbReport->limit($this->limit);
        }

        $q = $dbReport->get($tableName);

        if ($this->debug === true) {
            cekKuning($dbReport->last_query());
        }

        return $q;

    }

    public function writeBiayaSellerProduk($seller_id, $produk_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $subject_id = $seller_id;
        $object_id = $produk_id;
        $tableName = $this->tableNames[$jenis]["tableName"]["psp"];
        // $dbReport = $this->db2;
        $updKoloms = $this->updKoloms;

        if (isset($this->datas)) {
            $datas = $this->datas;
            $datas['periode'] = $this->periode;
        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        $tmp_3 = $this->lookupBiayaSellerProduk($subject_id, $object_id)->result();
        // arrPrint($tmp_3);
        // arrPrint($datas);
        // matiHere(__FILE__);
        $this->tableName = $tableName;
        if (sizeof($tmp_3) == 0) {
            $this->addData($datas);
        }
        else {

            $dbDatas = $tmp_3[0];
            // $newCounter = $dbDatas->counter +1;
            foreach ($updKoloms as $updKolom) {
                $$updKolom = $dbDatas->$updKolom + $datas[$updKolom];

                $updDatas[$updKolom] = $dbDatas->$updKolom + $datas[$updKolom];
            }
            $condite = array(
                "id" => $dbDatas->id,
            );

            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    public function lookupBiayaSellerProduk($seller_id, $produk_id)
    {
        $subject_id = $seller_id;
        $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["psp"];
        $dbReport = $this->db2;

        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        switch ($periode) {
            case "harian":
                isset($this->tanggal) ? $dbReport->where("tanggal", $this->tanggal) : "";
                break;
            case "mingguan":
                if (isset($this->minggu)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "mg" => $this->minggu,
                        "th" => $this->tahun,
                    ));
                }

                break;
            case "bulanan":
                if (isset($this->bulan)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "bl" => $this->bulan,
                        "th" => $this->tahun,
                    ));
                }
                break;
            case "tahunan":
                isset($this->tahun) ? $dbReport->where("th", $this->tahun) : "";
                break;
        }

        $arrWhere = array(
            "subject_id =" => $subject_id,
            "object_id =" => $object_id,
            // "periode ="    => $periode,
        );
        $arrWhere["periode ="] = $periode;

        $dbReport->where($arrWhere);

        if (isset($this->order)) {
            $dbReport->order_by($this->order);
        }
        else {
            $dbReport->order_by('id ASC');
        }

        if (isset($this->limit)) {
            $dbReport->limit($this->limit);
        }

        $q = $dbReport->get($tableName);

        if ($this->debug === true) {
            cekKuning($dbReport->last_query());
        }

        return $q;

    }
    //endregion biaya


    // --------------------------------------
    public function lastCounterPenjualanSellerPeriode()
    {
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        // arrPrintWebs($this->tableNames);
        // cekHere($jenis);
        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["ps"];
        // $tableName = "penjualan_seller_produk";
        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";
        // arrPrintWebs($tableName);


        $this->db2->where("periode", $periode);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function lookupPenjualanSeller($seller_id)
    {
        $subject_id = $seller_id;
        // $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["ps"];
        $dbReport = $this->db2;

        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        switch ($periode) {
            case "harian":
                isset($this->tanggal) ? $dbReport->where("tanggal", $this->tanggal) : "";
                break;
            case "mingguan":
                if (isset($this->minggu)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "mg" => $this->minggu,
                        "th" => $this->tahun,
                    ));
                }

                break;
            case "bulanan":
                if (isset($this->bulan)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "bl" => $this->bulan,
                        "th" => $this->tahun,
                    ));
                }
                break;
            case "tahunan":
                isset($this->tahun) ? $dbReport->where("th", $this->tahun) : "";
                break;
        }

        $arrWhere = array(
            "subject_id =" => $subject_id,
            // "object_id ="  => $object_id,
            // "periode ="    => $periode,
        );
        $arrWhere["periode ="] = $periode;

        $dbReport->where($arrWhere);

        if (isset($this->order)) {
            $dbReport->order_by($this->order);
        }
        else {
            $dbReport->order_by('id ASC');
        }

        if (isset($this->limit)) {
            $dbReport->limit($this->limit);
        }

        $q = $dbReport->get($tableName);

        if ($this->debug === true) {
            cekKuning($dbReport->last_query());
        }

        return $q;

    }

    public function writePenjualanSeller($seller_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $subject_id = $seller_id;
        // $object_id = $produk_id;
        $tableName = $this->tableNames[$jenis]["tableName"]["ps"];
        // $dbReport = $this->db2;
        $updKoloms = $this->updKoloms;

        if (isset($this->dataGlundungs)) {
            $datas = $this->dataGlundungs;
            $datas['periode'] = $this->periode;
        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        $tmp_3 = $this->lookupPenjualanSeller($subject_id)->result();
        // arrPrint($tmp_3);
        // arrPrintWebs($datas);
        // matiHere(__FILE__ ." @". __LINE__);
        $this->tableName = $tableName;
        if (sizeof($tmp_3) == 0) {
            $this->addData($datas);
        }
        else {

            $dbDatas = $tmp_3[0];
            // $newCounter = $dbDatas->counter +1;
            foreach ($updKoloms as $updKolom) {
                $$updKolom = $dbDatas->$updKolom + $datas[$updKolom];

                $updDatas[$updKolom] = $dbDatas->$updKolom + $datas[$updKolom];
            }
            $condite = array(
                "id" => $dbDatas->id,
            );

            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    // --------------------------------------
    public function lastCounterPenjualanProdukPeriode()
    {
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        // arrPrintWebs($this->tableNames);
        // cekHere($jenis);
        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["pp"];
        // $tableName = "penjualan_seller_produk";
        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";
        // arrPrintWebs($tableName);


        $this->db2->where("periode", $periode);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function lookupPenjualanProduk($produk_id)
    {
        $subject_id = $produk_id;
        // $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["pp"];
        $dbReport = $this->db2;

        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        switch ($periode) {
            case "harian":
                isset($this->tanggal) ? $dbReport->where("tanggal", $this->tanggal) : "";
                break;
            case "mingguan":
                if (isset($this->minggu)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "mg" => $this->minggu,
                        "th" => $this->tahun,
                    ));
                }

                break;
            case "bulanan":
                if (isset($this->bulan)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "bl" => $this->bulan,
                        "th" => $this->tahun,
                    ));
                }
                break;
            case "tahunan":
                isset($this->tahun) ? $dbReport->where("th", $this->tahun) : "";
                break;
        }

        $arrWhere = array(
            "subject_id =" => $subject_id,
            // "object_id ="  => $object_id,
            "cabang_id =" => 0,
        );
        $arrWhere["periode ="] = $periode;

        $dbReport->where($arrWhere);

        if (isset($this->order)) {
            $dbReport->order_by($this->order);
        }
        else {
            $dbReport->order_by('id ASC');
        }

        if (isset($this->limit)) {
            $dbReport->limit($this->limit);
        }

        $q = $dbReport->get($tableName);

        if ($this->debug === true) {
            cekKuning($dbReport->last_query());
        }

        return $q;

    }

    public function writePenjualanProduk($produk_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $subject_id = $produk_id;
        // $object_id = $produk_id;
        $tableName = $this->tableNames[$jenis]["tableName"]["pp"];
        // $dbReport = $this->db2;
        $updKoloms = $this->updKoloms;

        if (isset($this->dataPp)) {
            $datas = $this->dataPp;
            $datas['periode'] = $this->periode;
        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterPenjualanProdukPeriode()->result();

        if (sizeof($tmp_c) > 0) {
            $tCounter = $tmp_c[0];
            $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
            $lastTanggal = $tCounter->tanggal;

            if ($tanggal != $lastTanggal) {
                $counter = $lastCounter + 1;
            }
            else {
                $counter = $lastCounter;
            }
        }
        else {
            $counter = 1;
            $lastCounter = 0;
            $lastTanggal = "";
        }
        //endregion
        $datas["counter"] = $counter;

        $tmp_3 = $this->lookupPenjualanProduk($subject_id)->result();
        // arrPrint($tmp_3);
        // arrPrintWebs($datas);
        // matiHere(__FILE__ ." @". __LINE__);
        $this->tableName = $tableName;
        if (sizeof($tmp_3) == 0) {
            $this->addData($datas);
        }
        else {

            $dbDatas = $tmp_3[0];
            // $newCounter = $dbDatas->counter +1;
            foreach ($updKoloms as $updKolom) {
                $$updKolom = $dbDatas->$updKolom + $datas[$updKolom];

                $updDatas[$updKolom] = $dbDatas->$updKolom + $datas[$updKolom];
            }
            $condite = array(
                "id" => $dbDatas->id,
            );

            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    public function lastCounterPenjualanProdukCabangPeriode($cabang_id)
    {
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        // arrPrintWebs($this->tableNames);
        // cekHere($jenis);
        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["pp"];
        // $tableName = "penjualan_seller_produk";
        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";
        // arrPrintWebs($tableName);


        $this->db2->where("periode", $periode);
        $this->db2->where("cabang_id", $cabang_id);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function lookupPenjualanProdukCabang($produk_id, $cabang_id)
    {
        $subject_id = $produk_id;
        // $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["pp"];
        $dbReport = $this->db2;

        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        switch ($periode) {
            case "harian":
                isset($this->tanggal) ? $dbReport->where("tanggal", $this->tanggal) : "";
                break;
            case "mingguan":
                if (isset($this->minggu)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "mg" => $this->minggu,
                        "th" => $this->tahun,
                    ));
                }

                break;
            case "bulanan":
                if (isset($this->bulan)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "bl" => $this->bulan,
                        "th" => $this->tahun,
                    ));
                }
                break;
            case "tahunan":
                isset($this->tahun) ? $dbReport->where("th", $this->tahun) : "";
                break;
        }

        $arrWhere = array(
            "subject_id =" => $subject_id,
            // "object_id ="  => $object_id,
            "cabang_id =" => $cabang_id,
        );
        $arrWhere["periode ="] = $periode;

        $dbReport->where($arrWhere);

        if (isset($this->order)) {
            $dbReport->order_by($this->order);
        }
        else {
            $dbReport->order_by('id ASC');
        }

        if (isset($this->limit)) {
            $dbReport->limit($this->limit);
        }

        $q = $dbReport->get($tableName);

        if ($this->debug === true) {
            cekKuning($dbReport->last_query());
        }

        return $q;

    }

    public function writePenjualanProdukCabang($produk_id, $cabang_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $subject_id = $produk_id;
        // $object_id = $produk_id;
        $tableName = $this->tableNames[$jenis]["tableName"]["pp"];
        // $dbReport = $this->db2;
        $updKoloms = $this->updKoloms;

        if (isset($this->dataPp)) {
            $datas = $this->dataPp;
            $datas['periode'] = $this->periode;
        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterPenjualanProdukCabangPeriode($cabang_id)->result();

        if (sizeof($tmp_c) > 0) {
            $tCounter = $tmp_c[0];
            $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
            $lastTanggal = $tCounter->tanggal;

            if ($tanggal != $lastTanggal) {
                $counter = $lastCounter + 1;
            }
            else {
                $counter = $lastCounter;
            }
        }
        else {
            $counter = 1;
            $lastCounter = 0;
            $lastTanggal = "";
        }
        //endregion
        $datas["counter"] = $counter;
        $datas["cabang_id"] = $cabang_id;

        $tmp_3 = $this->lookupPenjualanProdukCabang($subject_id, $cabang_id)->result();
        // arrPrint($tmp_3);
        // arrPrintWebs($datas);
        // matiHere(__FILE__ ." @". __LINE__);
        $this->tableName = $tableName;
        if (sizeof($tmp_3) == 0) {
            $this->addData($datas);
        }
        else {

            $dbDatas = $tmp_3[0];
            // $newCounter = $dbDatas->counter +1;
            foreach ($updKoloms as $updKolom) {
                $$updKolom = $dbDatas->$updKolom + $datas[$updKolom];

                $updDatas[$updKolom] = $dbDatas->$updKolom + $datas[$updKolom];
            }
            $condite = array(
                "id" => $dbDatas->id,
            );

            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    public function lookupPenjualanProdukAll()
    {
        // $subject_id = $produk_id;
        // $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["pp"];
        $dbReport = $this->db2;

        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");
        $condites = isset($this->condites) ? $this->condites : array();
        switch ($periode) {
            case "harian":
                isset($this->tanggal) ? $dbReport->where("tanggal", $this->tanggal) : "";
                break;
            case "mingguan":
                if (isset($this->minggu)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "mg" => $this->minggu,
                        "th" => $this->tahun,
                    ));
                }

                break;
            case "bulanan":
                if (isset($this->bulan)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "bl" => $this->bulan,
                        "th" => $this->tahun,
                    ));
                }
                break;
            case "tahunan":
                isset($this->tahun) ? $dbReport->where("th", $this->tahun) : "";
                break;
        }

        $arrWhere = array(
            // "subject_id =" => $subject_id,
            // "object_id ="  => $object_id,
            "cabang_id =" => 0,
        );
        $arrWhere["periode ="] = $periode;

        $dbReport->where(($arrWhere + $condites));

        if (isset($this->order)) {
            $dbReport->order_by($this->order);
        }
        else {
            $dbReport->order_by('id ASC');
        }

        if (isset($this->limit)) {
            $dbReport->limit($this->limit);
        }

        $q = $dbReport->get($tableName);

        if ($this->debug === true) {
            cekKuning($dbReport->last_query());
        }

        return $q;

    }

    public function lookupPenjualanProdukKategori()
    {

    }

    // --------------------------------------
    public function lookupPembelianProduk()
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $tbl = $this->tableNames["pembelian_produk"];
        $dbReport = $this->db2;

        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");
        $condites = isset($this->condites) ? $this->condites : array();
        switch ($periode) {
            case "harian":
                isset($this->tanggal) ? $dbReport->where("tanggal", $this->tanggal) : "";
                break;
            case "mingguan":
                if (isset($this->minggu)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "mg" => $this->minggu,
                        "th" => $this->tahun,
                    ));
                }

                break;
            case "bulanan":
                if (isset($this->bulan)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "bl" => $this->bulan,
                        "th" => $this->tahun,
                    ));
                }
                break;
            case "tahunan":
                isset($this->tahun) ? $dbReport->where("th", $this->tahun) : "";
                break;
        }

        $arrWhere = array(
            // "subject_id =" => $subject_id,
            // "object_id ="  => $object_id,
            // "cabang_id =" => 0,
        );
        $arrWhere["periode ="] = $periode;

        $dbReport->where(($arrWhere + $condites));

        if (isset($this->order)) {
            $dbReport->order_by($this->order);
        }
        else {
            $dbReport->order_by('id ASC');
        }

        if (isset($this->limit)) {
            $dbReport->limit($this->limit);
        }

        $q = $dbReport->get($tableName);

        if ($this->debug === true) {
            cekKuning($dbReport->last_query());
        }

        return $q;

    }

    public function lookupPembelianSupplies()
    {

        // $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $tbl = $this->tableNames["pembelian_supplies"];
        $dbReport = $this->db2;

        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");
        $condites = isset($this->condites) ? $this->condites : array();
        switch ($periode) {
            case "harian":
                isset($this->tanggal) ? $dbReport->where("tanggal", $this->tanggal) : "";
                break;
            case "mingguan":
                if (isset($this->minggu)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "mg" => $this->minggu,
                        "th" => $this->tahun,
                    ));
                }

                break;
            case "bulanan":
                if (isset($this->bulan)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "bl" => $this->bulan,
                        "th" => $this->tahun,
                    ));
                }
                break;
            case "tahunan":
                isset($this->tahun) ? $dbReport->where("th", $this->tahun) : "";
                break;
        }

        $arrWhere = array(
            // "subject_id =" => $subject_id,
            // "object_id ="  => $object_id,
            // "cabang_id =" => 0,
        );
        $arrWhere["periode ="] = $periode;

        $dbReport->where(($arrWhere + $condites));

        if (isset($this->order)) {
            $dbReport->order_by($this->order);
        }
        else {
            $dbReport->order_by('id ASC');
        }

        if (isset($this->limit)) {
            $dbReport->limit($this->limit);
        }

        $q = $dbReport->get($tableName);

        if ($this->debug === true) {
            cekKuning($dbReport->last_query());
        }

        return $q;

    }

    public function callPembelianAll()
    {
        $tmp_produks = $this->lookupPembelianProduk();
        // arrPrint($tmp_produks->result());
        foreach ($tmp_produks->result() as $produk) {
            $produk_id = $produk->object_id;
            $bl = $produk->bl;
            $th = $produk->th;
            if (!isset($sumProdukId[$th][$bl][$produk_id])) {
                $sumProdukId[$th][$bl][$produk_id] = 0;
            }
            $sumProdukId[$th][$bl][$produk_id] += $produk->nilai_af;
        }
        foreach ($sumProdukId as $th_2 => $produkIds_2) {
            foreach ($produkIds_2 as $bl_2 => $produkIds_2a) {
                // arrPrintWebs($produkIds_2a);
                foreach ($produkIds_2a as $prId_2 => $produkNilai) {
                    if (!isset($sumProduk[$th_2][$bl_2])) {
                        $sumProduk[$th_2][$bl_2] = 0;
                    }
                    $sumProduk[$th_2][$bl_2] += $produkNilai;
                }
            }
        }

        $tmp_supplies = $this->lookupPembelianSupplies();
        foreach ($tmp_supplies->result() as $supplies) {
            $supplies_id = $supplies->object_id;
            $bl = $supplies->bl;
            $th = $supplies->th;
            if (!isset($sumSuppliesId[$th][$bl][$supplies_id])) {
                $sumSuppliesId[$th][$bl][$supplies_id] = 0;
            }
            $sumSuppliesId[$th][$bl][$supplies_id] += $supplies->nilai_af;
        }
        foreach ($sumSuppliesId as $th_2 => $suppliesIds_2) {
            foreach ($suppliesIds_2 as $bl_2 => $suppliesIds_2a) {
                // arrPrintWebs($suppliesIds_2a);
                foreach ($suppliesIds_2a as $prId_2 => $suppliesNilai) {
                    if (!isset($sumSupplies[$th_2][$bl_2])) {
                        $sumSupplies[$th_2][$bl_2] = 0;
                    }
                    $sumSupplies[$th_2][$bl_2] += $suppliesNilai;
                }
            }
        }


        // arrPrint($sumProduk);
        // arrPrint($sumSupplies);

        foreach ($sumProduk as $sTh => $subjecs_0) {
            foreach ($subjecs_0 as $sBl => $sNilai_1) {

                $sNilai_2 = $sumSupplies[$sTh][$sBl];
                if (!isset($sumPembelian[$sTh][$sBl])) {
                    $sumPembelian[$sTh][$sBl] = 0;
                }
                $sumPembelian[$sTh][$sBl] += ($sNilai_1 + $sNilai_2);
            }
        }

        return $sumPembelian;
    }

    // --------------------------------------
    public function lastCounterPenjualanPeriode()
    {
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        // arrPrintWebs($this->tableNames);
        // cekHere($jenis);
        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["p"];
        // $tableName = "penjualan_seller_produk";
        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";
        // arrPrintWebs($tableName);


        $this->db2->where("periode", $periode);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function lookupPenjualan()
    {
        // $subject_id = $produk_id;
        // $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["p"];
        $dbReport = $this->db2;

        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        switch ($periode) {
            case "harian":
                isset($this->tanggal) ? $dbReport->where("tanggal", $this->tanggal) : "";
                break;
            case "mingguan":
                if (isset($this->minggu)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "mg" => $this->minggu,
                        "th" => $this->tahun,
                    ));
                }

                break;
            case "bulanan":
                if (isset($this->bulan)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "bl" => $this->bulan,
                        "th" => $this->tahun,
                    ));
                }
                break;
            case "tahunan":
                isset($this->tahun) ? $dbReport->where("th", $this->tahun) : "";
                break;
        }

        $arrWhere = array(
            // "subject_id =" => $subject_id,
            // "object_id ="  => $object_id,
            "cabang_id =" => 0,
        );
        $arrWhere["periode ="] = $periode;

        $dbReport->where($arrWhere);

        if (isset($this->order)) {
            $dbReport->order_by($this->order);
        }
        else {
            $dbReport->order_by('id ASC');
        }

        if (isset($this->limit)) {
            $dbReport->limit($this->limit);
        }

        $q = $dbReport->get($tableName);

        if ($this->debug === true) {
            cekKuning("$periode ::" . $dbReport->last_query());
        }
        // matiHere(__METHOD__ . " periode::$periode");
        return $q;

    }

    public function writePenjualan()
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        // $subject_id = $produk_id;
        // $object_id = $produk_id;
        $tableName = $this->tableNames[$jenis]["tableName"]["p"];
        // $dbReport = $this->db2;
        $updKoloms = $this->updKoloms;

        if (isset($this->dataP)) {
            $datas = $this->dataP;
            $datas['periode'] = $this->periode;
        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterPenjualanPeriode()->result();

        if (sizeof($tmp_c) > 0) {
            $tCounter = $tmp_c[0];
            $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
            $lastTanggal = $tCounter->tanggal;

            if ($tanggal != $lastTanggal) {
                $counter = $lastCounter + 1;
            }
            else {
                $counter = $lastCounter;
            }
        }
        else {
            $counter = 1;
            $lastCounter = 0;
            $lastTanggal = "";
        }
        //endregion
        $datas["counter"] = $counter;

        $tmp_3 = $this->lookupPenjualan()->result();
        // arrPrint($tmp_3);
//         arrPrintWebs($datas);
//         matiHere(__FILE__ ." @". __LINE__);
        $this->tableName = $tableName;
        if (sizeof($tmp_3) == 0) {
            $this->addData($datas);
        }
        else {

            $dbDatas = $tmp_3[0];
            // $newCounter = $dbDatas->counter +1;
            foreach ($updKoloms as $updKolom) {
                $$updKolom = $dbDatas->$updKolom + $datas[$updKolom];

                $updDatas[$updKolom] = $dbDatas->$updKolom + $datas[$updKolom];
            }
            $condite = array(
                "id" => $dbDatas->id,
            );

            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    public function lastCounterPenjualanCabangPeriode($cabang_id)
    {
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        // arrPrintWebs($this->tableNames);
        // cekHere($jenis);
        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["p"];
        // $tableName = "penjualan_seller_produk";
        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";
        // arrPrintWebs($tableName);


        $this->db2->where("periode", $periode);
        $this->db2->where("cabang_id", $cabang_id);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function lookupPenjualanCabang($cabang_id)
    {
        // $subject_id = $produk_id;
        // $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["p"];
        $dbReport = $this->db2;

        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        switch ($periode) {
            case "harian":
                isset($this->tanggal) ? $dbReport->where("tanggal", $this->tanggal) : "";
                break;
            case "mingguan":
                if (isset($this->minggu)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "mg" => $this->minggu,
                        "th" => $this->tahun,
                    ));
                }

                break;
            case "bulanan":
                if (isset($this->bulan)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "bl" => $this->bulan,
                        "th" => $this->tahun,
                    ));
                }
                break;
            case "tahunan":
                isset($this->tahun) ? $dbReport->where("th", $this->tahun) : "";
                break;
        }

        $arrWhere = array(
            // "subject_id =" => $subject_id,
            // "object_id ="  => $object_id,
            "cabang_id =" => $cabang_id,
        );
        $arrWhere["periode ="] = $periode;

        $dbReport->where($arrWhere);

        if (isset($this->order)) {
            $dbReport->order_by($this->order);
        }
        else {
            $dbReport->order_by('id ASC');
        }

        if (isset($this->limit)) {
            $dbReport->limit($this->limit);
        }

        $q = $dbReport->get($tableName);

        if ($this->debug === true) {
            cekKuning($dbReport->last_query());
        }

        return $q;

    }

    public function writePenjualanCabang($cabang_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        // $subject_id = $produk_id;
        // $object_id = $produk_id;
        $tableName = $this->tableNames[$jenis]["tableName"]["p"];
        // $dbReport = $this->db2;
        $updKoloms = $this->updKoloms;

        if (isset($this->dataP)) {
            $datas = $this->dataP;
            $datas['periode'] = $this->periode;
        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterPenjualanCabangPeriode($cabang_id)->result();

        if (sizeof($tmp_c) > 0) {
            $tCounter = $tmp_c[0];
            $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
            $lastTanggal = $tCounter->tanggal;

            if ($tanggal != $lastTanggal) {
                $counter = $lastCounter + 1;
            }
            else {
                $counter = $lastCounter;
            }
        }
        else {
            $counter = 1;
            $lastCounter = 0;
            $lastTanggal = "";
        }
        //endregion
        $datas["counter"] = $counter;
        $datas["cabang_id"] = $cabang_id;

        $tmp_3 = $this->lookupPenjualanCabang($cabang_id)->result();
        // arrPrint($tmp_3);
        // arrPrintWebs($datas);
        // matiHere(__FILE__ ." @". __LINE__);
        $this->tableName = $tableName;
        if (sizeof($tmp_3) == 0) {
            $this->addData($datas);
        }
        else {

            $dbDatas = $tmp_3[0];
            // $newCounter = $dbDatas->counter +1;
            foreach ($updKoloms as $updKolom) {
                $$updKolom = $dbDatas->$updKolom + $datas[$updKolom];

                $updDatas[$updKolom] = $dbDatas->$updKolom + $datas[$updKolom];
            }
            $condite = array(
                "id" => $dbDatas->id,
            );

            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    public function lookupPenjualanAll()
    {
        // $subject_id = $produk_id;
        // $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["p"];
        $dbReport = $this->db2;

        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");
        $condites = isset($this->condites) ? $this->condites : array();
        switch ($periode) {
            case "harian":
                isset($this->tanggal) ? $dbReport->where("tanggal", $this->tanggal) : "";
                break;
            case "mingguan":
                if (isset($this->minggu)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "mg" => $this->minggu,
                        "th" => $this->tahun,
                    ));
                }

                break;
            case "bulanan":
                if (isset($this->bulan)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "bl" => $this->bulan,
                        "th" => $this->tahun,
                    ));
                }
                break;
            case "tahunan":
                isset($this->tahun) ? $dbReport->where("th", $this->tahun) : "";
                break;
        }

        $arrWhere = array(
            // "subject_id =" => $subject_id,
            // "object_id ="  => $object_id,
            "cabang_id =" => 0,
        );
        $arrWhere["periode ="] = $periode;

        $dbReport->where(($arrWhere + $condites));
        $dbReport->select('*,QUARTER(tanggal) as `quarter`');
        if (isset($this->order)) {
            $dbReport->order_by($this->order);
        }
        else {
            $dbReport->order_by('id ASC');
        }

        if (isset($this->limit)) {
            $dbReport->limit($this->limit);
        }

        $q = $dbReport->get($tableName);

        if ($this->debug === true) {
            cekKuning($dbReport->last_query());
        }

        return $q;

    }

    public function lookupQuarterPenjualanAll()
    {
        // $subject_id = $produk_id;
        // $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["p"];
        $dbReport = $this->db2;

        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");
        $condites = isset($this->condites) ? $this->condites : array();
        switch ($periode) {
            case "harian":
                isset($this->tanggal) ? $dbReport->where("tanggal", $this->tanggal) : "";
                break;
            case "mingguan":
                if (isset($this->minggu)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "mg" => $this->minggu,
                        "th" => $this->tahun,
                    ));
                }

                break;
            case "bulanan":
                if (isset($this->bulan)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "bl" => $this->bulan,
                        "th" => $this->tahun,
                    ));
                }
                break;
            case "tahunan":
                isset($this->tahun) ? $dbReport->where("th", $this->tahun) : "";
                break;
        }

        $arrWhere = array(
            // "subject_id =" => $subject_id,
            // "object_id ="  => $object_id,
            "cabang_id =" => 0,
        );
        $arrWhere["periode ="] = $periode;

        $dbReport->where(($arrWhere + $condites));

        if (isset($this->order)) {
            $dbReport->order_by($this->order);
        }
        else {
            $dbReport->order_by('id ASC');
        }

        if (isset($this->limit)) {
            $dbReport->limit($this->limit);
        }

        $q = $dbReport->get($tableName);

        if ($this->debug === true) {
            cekKuning($dbReport->last_query());
        }

        return $q;

    }

    // --------------------------------------
    public function _resetorReport($jenis)
    {

        $tbl = $this->tableNames[$jenis]['tableName'];
        $tbl_2 = isset($this->tableNames[$jenis]['tableName_2']) ? $this->tableNames[$jenis]['tableName_2'] : "";
        $tableNames = $this->tableNames[$jenis]["tableName"];

        if (is_array($tableNames)) {
            foreach ($tableNames as $tbl) {
                $this->db2->truncate($tbl);
                if ($this->debug === true) {
                    cekBiru($this->db2->last_query());
                }
            }
        }
        else {
            $this->db2->truncate($tableNames);
            if ($this->debug === true) {
                cekBiru($this->db2->last_query());
            }
        }

        // if (strlen($tbl_2) > 3) {
        //     $this->db2->truncate($tbl_2);
        //     if ($this->debug === true) {
        //         cekUngu($this->db2->last_query());
        //     }
        // }

    }

    public function lookupPreSalesCanceledMonthly()
    {

        $condites = isset($this->condites) ? $this->condites : "";

        $tbl = $this->tableNames['pre_penjualan_canceled']['tableName'];
        if (isset($this->condites)) {
            $this->db2->where($condites);
        }
        if (isset($this->limit)) {
            $this->db2->limit($this->limit);
        }
        $this->db2->order_by("subject_nama ASC");
        $this->db2->where(array("periode" => "bulanan"));
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            cekBiru($this->db2->last_query());
        }

        return $q;
    }

    public function lookupPreSalesMonthly()
    {

        $condites = isset($this->condites) ? $this->condites : "";

        $tbl = $this->tableNames['pre_penjualan']['tableName'];
        if (isset($this->condites)) {
            $this->db2->where($condites);
        }
        if (isset($this->limit)) {
            $this->db2->limit($this->limit);
        }
        $this->db2->order_by("subject_nama ASC");
        $this->db2->where(array("periode" => "bulanan"));
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            cekBiru($this->db2->last_query());
        }

        return $q;
    }

    public function lookupSalesMonthly()
    {

        $condites = isset($this->condites) ? $this->condites : "";
        $conditesCompared = isset($this->conditesCompared) ? $this->conditesCompared : NULL;

        $tbl = $this->tableNames['penjualan']['tableName']['ps'];
        if (isset($this->condites)) {
            $this->db2->where($condites);
        }
        if (isset($this->limit)) {
            $this->db2->limit($this->limit);
        }
        if ($conditesCompared != NULL) {
            $this->db2->where($conditesCompared);
        }
        $this->db2->order_by("subject_nama ASC");
        $this->db2->where(array("periode" => "bulanan"));
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            cekBiru($this->db2->last_query());
        }

        return $q;
    }

    public function lookupSalesMonthlyCabang()
    {
        $var = __METHOD__;

        return $var;
    }

    public function lookupPurchasingSpMonthly()
    {

        $condites = isset($this->condites) ? $this->condites : "";

        $tbl = $this->tableNames['pembelian_supplies']['tableName'];
        if (isset($this->condites)) {
            $this->db2->where($condites);
        }
        $this->db2->order_by("subject_nama ASC");
        $this->db2->where(array("periode" => "bulanan"));
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            cekBiru($this->db2->last_query());
        }

        return $q;
    }

    public function lookupPurchasingFgMonthly()
    {

        $condites = isset($this->condites) ? $this->condites : "";

        $tbl = $this->tableNames['pembelian_produk']['tableName'];
        if (isset($this->condites)) {
            $this->db2->where($condites);
        }
        $this->db2->order_by("subject_nama ASC");
        $this->db2->where(array("periode" => "bulanan"));
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            cekBiru($this->db2->last_query());
        }

        return $q;
    }

    public function lookupPrePenjualan()
    {
        // $subject_id = $seller_id;
        // $object_id = $produk_id;
        // $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = "pre_penjualan_seller_produk";
        $dbReport = $this->db2;

        // $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");
        $periode = "harian";
        switch ($periode) {
            case "harian":
                isset($this->tanggal) ? $dbReport->where("tanggal", $this->tanggal) : "";
                break;
            case "mingguan":
                if (isset($this->minggu)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "mg" => $this->minggu,
                        "th" => $this->tahun,
                    ));
                }

                break;
            case "bulanan":
                if (isset($this->bulan)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "bl" => $this->bulan,
                        "th" => $this->tahun,
                    ));
                }
                break;
            case "tahunan":
                isset($this->tahun) ? $dbReport->where("th", $this->tahun) : "";
                break;
        }

        // $arrWhere = array(
        //     "subject_id =" => $subject_id,
        //     // "object_id ="  => $object_id,
        //     // "periode ="    => $periode,
        // );
        $arrWhere["periode ="] = $periode;

        $dbReport->where($arrWhere);

        if (isset($this->order)) {
            $dbReport->order_by($this->order);
        }
        else {
            $dbReport->order_by('id ASC');
        }

        if (isset($this->limit)) {
            $dbReport->limit($this->limit);
        }

        $q = $dbReport->get($tableName);

        if ($this->debug === true) {
            cekKuning($dbReport->last_query());
        }

        return $q;
    }

    // pre_penjualan_seller_produk_canceled
    public function lookupPrePenjualanCanceled()
    {
        // $subject_id = $seller_id;
        // $object_id = $produk_id;
        // $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = "pre_penjualan_seller_produk_canceled";
        $dbReport = $this->db2;

        // $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");
        $periode = "harian";
        switch ($periode) {
            case "harian":
                isset($this->tanggal) ? $dbReport->where("tanggal", $this->tanggal) : "";
                break;
            case "mingguan":
                if (isset($this->minggu)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "mg" => $this->minggu,
                        "th" => $this->tahun,
                    ));
                }

                break;
            case "bulanan":
                if (isset($this->bulan)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "bl" => $this->bulan,
                        "th" => $this->tahun,
                    ));
                }
                break;
            case "tahunan":
                isset($this->tahun) ? $dbReport->where("th", $this->tahun) : "";
                break;
        }

        // $arrWhere = array(
        //     "subject_id =" => $subject_id,
        //     // "object_id ="  => $object_id,
        //     // "periode ="    => $periode,
        // );
        $arrWhere["periode ="] = $periode;

        $dbReport->where($arrWhere);

        if (isset($this->order)) {
            $dbReport->order_by($this->order);
        }
        else {
            $dbReport->order_by('id ASC');
        }

        if (isset($this->limit)) {
            $dbReport->limit($this->limit);
        }

        $q = $dbReport->get($tableName);

        if ($this->debug === true) {
            cekKuning($dbReport->last_query());
        }

        return $q;
    }

    public function lookupPrePenjualan_($reportnama)
    {
        $tabel = $this->tabels[$reportnama];
        // $object_id = $produk_id;
        // $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = "pre_penjualan_" . $tabel;
        $dbReport = $this->db2;

        // $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");
        $periode = "bulanan";
        switch ($periode) {
            case "harian":
                isset($this->tanggal) ? $dbReport->where("tanggal", $this->tanggal) : "";
                break;
            case "mingguan":
                if (isset($this->minggu)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "mg" => $this->minggu,
                        "th" => $this->tahun,
                    ));
                }

                break;
            case "bulanan":
                if (isset($this->bulan)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "bl" => $this->bulan,
                        "th" => $this->tahun,
                    ));
                }
                break;
            case "tahunan":
                isset($this->tahun) ? $dbReport->where("th", $this->tahun) : "";
                break;
        }

        // $arrWhere = array(
        //     "subject_id =" => $subject_id,
        //     // "object_id ="  => $object_id,
        //     // "periode ="    => $periode,
        // );
        $arrWhere["periode ="] = $periode;
        if (isset($this->condites)) {
            foreach ($this->condites as $key => $val) {
                $arrWhere["$key ="] = $val;
            }
        }

        $dbReport->where($arrWhere);

        if (isset($this->order)) {
            $dbReport->order_by($this->order);
        }
        else {
            $dbReport->order_by('id ASC');
        }

        if (isset($this->limit)) {
            $dbReport->limit($this->limit);
        }

        $q = $dbReport->get($tableName);

        if ($this->debug === true) {
            cekKuning($dbReport->last_query());
        }

        return $q;
    }

    public function writePrePenjualanMovement($reportnama, $newdatas)
    {
        // $tables = array(
        //   ""
        // );

        // $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        // $object_id = $produk_id;
        // $tableName = $this->tableNames[$jenis]["tableName"];
        // $dbReport = $this->db2;
        $updKoloms = $this->updKoloms;

        if (isset($this->datas)) {
            $datas = $this->datas;
            $datas['periode'] = $this->periode;
        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        $tmp_3 = $this->lookupPrePenjualan_($reportnama)->result();
        arrPrint($tmp_3);
        // arrPrint($newdatas);
        // matiHere(__FILE__);
        $this->tabels[$reportnama];

        $tabel = $this->tabels[$reportnama];
        $tbl = "pre_penjualan_" . $tabel;
        if (sizeof($tmp_3) == 0) {
            $this->db2->insert($tbl, $newdatas);

            if ($this->debug === true) {
                cekBiru($this->db2->last_query());
            }
        }
        else {
            $newdatas['unit_otbe'] = $tmp_3[0]->unit_ot;
            $newdatas['unit_inbe'] = $tmp_3[0]->unit_in;
            $newdatas['unit_be'] = $tmp_3[0]->unit_af;

            $new_unit_in = $tmp_3[0]->unit_in + $newdatas['unit_inin'] - $newdatas['unit_inot'];
            $newdatas['unit_in'] = $new_unit_in;

            // $newdatas['unit_af'] = $tmp_3[0]->unit_af + $new_unit_in - $newdatas['unit_ot'];

            $new_unit_ot = $tmp_3[0]->unit_ot + $newdatas['unit_otin'] - $newdatas['unit_otot'];
            $newdatas['unit_ot'] = $new_unit_ot;

            $newdatas['unit_af'] = $tmp_3[0]->unit_af + $new_unit_in - $new_unit_ot;

            $newdatas['nilai_otbe'] = $tmp_3[0]->nilai_ot;
            $newdatas['nilai_inbe'] = $tmp_3[0]->nilai_in;
            $newdatas['nilai_be'] = $tmp_3[0]->nilai_af;

            $new_nilai_in = $tmp_3[0]->nilai_in + $newdatas['nilai_inin'] - $newdatas['nilai_inot'];
            cekHitam($new_nilai_in . " = " . $tmp_3[0]->nilai_in . " + " . $newdatas['nilai_inin'] . " - " . $newdatas['nilai_inot']);
            $newdatas['nilai_in'] = $new_nilai_in;

            $new_nilai_ot = $tmp_3[0]->nilai_ot + $newdatas['nilai_otin'] - $newdatas['nilai_otot'];
            cekMerah($new_nilai_ot . " = " . $tmp_3[0]->nilai_ot . " + " . $newdatas['nilai_otin'] . " - " . $newdatas['nilai_otot']);
            $newdatas['nilai_ot'] = $new_nilai_ot;

            $newdatas['nilai_af'] = $tmp_3[0]->nilai_af + $new_nilai_in - $new_nilai_ot;
            // $newdatas['nilai_af'] = $tmp_3[0]->nilai_af + $newdatas['nilai_in'] - $newdatas['nilai_ot'];

            arrPrintWebs($newdatas);
            // matiHere(__FILE__);
            $this->db2->insert($tbl, $newdatas);

            if ($this->debug === true) {
                cekMerah($this->db2->last_query());
            }

        }
    }

    public function lookupPrePenjualanMovement($reportnama)
    {
        $tabel = $this->tabels[$reportnama];
        // $object_id = $produk_id;
        // $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = "pre_penjualan_" . $tabel;
        $tableName = "___lap_582so_cabang";
        // $tableName = "___lap_582so_seller";
        $dbReport = $this->db;

        // $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");
        $periode = "bulanan";
        switch ($periode) {
            case "harian":
                isset($this->tanggal) ? $dbReport->where("tanggal", $this->tanggal) : "";
                break;
            case "mingguan":
                if (isset($this->minggu)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "mg" => $this->minggu,
                        "th" => $this->tahun,
                    ));
                }

                break;
            case "bulanan":
                if (isset($this->bulan)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $dbReport->where(array(
                        "bl" => $this->bulan,
                        "th" => $this->tahun,
                    ));
                }
                break;
            case "tahunan":
                isset($this->tahun) ? $dbReport->where("th", $this->tahun) : "";
                break;
        }

        // $arrWhere = array(
        //     "subject_id =" => $subject_id,
        //     // "object_id ="  => $object_id,
        //     // "periode ="    => $periode,
        // );
        $arrWhere["periode ="] = $periode;
        if (isset($this->condites)) {
            foreach ($this->condites as $key => $val) {
                $arrWhere["$key ="] = $val;
            }
        }

        $dbReport->where($arrWhere);

        if (isset($this->order)) {
            $dbReport->order_by($this->order);
        }
        else {
            $dbReport->order_by('id ASC');
        }

        if (isset($this->limit)) {
            $dbReport->limit($this->limit);
        }

        $q = $dbReport->get($tableName);

        if ($this->debug === true) {
            cekKuning($dbReport->last_query());
        }

        return $q;
    }

    public function lookupTransaksiReport()
    {
        $tableName = !isset($this->tabel) ? mati_disini("tabel belum di set " . __METHOD__) : $this->tabel;

        $periode = isset($this->periode) ? $this->periode : "";
        isset($this->limit) ? $this->db->limit($this->limit) : "";
        isset($this->order) ? $this->db->order_by($this->order) : "";

        switch ($periode) {
            case "harian":
                isset($this->tanggal) ? $this->db->where("tanggal", $this->tanggal) : matiHere(__METHOD__ . " tgl belum diset");
                break;
            case "mingguan":
                if (isset($this->minggu)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $this->db->where(array(
                        "mg" => $this->minggu,
                        "th" => $this->tahun,
                    ));
                }

                break;
            case "bulanan":
                if (isset($this->bulan)) {
                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
                    $this->db->where(array(
                        "bl" => $this->bulan,
                        "th" => $this->tahun,
                    ));
                }
                break;
            case "tahunan":
                isset($this->tahun) ? $this->db->where("th", $this->tahun) : "";
                break;
        }

        // $arrWhere = array(
        //     "subject_id =" => $subject_id,
        //     // "object_id ="  => $object_id,
        //     // "periode ="    => $periode,
        // );
        $arrWhere["periode ="] = $periode;
        if (isset($this->condites)) {
            foreach ($this->condites as $key => $val) {
                $arrWhere["$key ="] = $val;
            }
        }
        $this->db->where($arrWhere);
        $q = $this->db->get($tableName);

        // if ($this->debug === true) {
        //     cekKuning($dbReport->last_query());
        // }

        return $q;
    }

    public function writeTransaksiReport($newDatas)
    {
        $tmp_3 = $this->lookupTransaksiReport()->result();
        showLast_query("ungu");
        $lastData = sizeof($tmp_3) > 0 ? $tmp_3[0] : "";
        $lastData_id = sizeof($tmp_3) > 0 ? $lastData->id : "";

        cekHijau("lastdata " . __LINE__);
        arrPrint($lastData);
        // arrPrint($newdatas);
        // matiHere(__FILE__);
        $tableName = !isset($this->tabel) ? mati_disini("tabel belum di set " . __METHOD__) : $this->tabel;

        if (sizeof($tmp_3) == 0) {

            isset($this->periode) ? $arrWhere["periode ="] = $this->periode : "";
            if (isset($this->condites)) {
                foreach ($this->condites as $key => $val) {
                    $arrWhere["$key ="] = $val;
                }
            }
            $this->db->where($arrWhere);
            $this->db->limit(1);
            $this->db->order_by("id", "DESC");
            $tmp_4 = $this->db->get($tableName)->result();
            showLast_query("kuning");

            if (sizeof($tmp_4) > 0) {
                $tCounter = $tmp_4[0];
                arrPrint($tCounter);
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastTanggal = $tCounter->tanggal;


                $counter = $lastCounter + 1;

                $newDatas["jmltr_be"] = $tCounter->jmltr_af;
                // $newDatas["jmltr_inbe"] = $tCounter->jmltr_in;
                // $newDatas["jmltr_otbe"] = $tCounter->jmltr_ot;
                $newDatas["jmltr_af"] = $tCounter->jmltr_af + $newDatas["jmltr_in"] - $newDatas["jmltr_ot"];

                $newDatas["unit_be"] = $tCounter->unit_af;
                // $newDatas["unit_inbe"] = $tCounter->unit_in;
                // $newDatas["unit_otbe"] = $tCounter->unit_ot;
                $newDatas["unit_af"] = $tCounter->unit_af + $newDatas["unit_in"] - $newDatas["unit_ot"];

                $newDatas["nilai_be"] = $tCounter->nilai_af;
                // $newDatas["nilai_inbe"] = $tCounter->nilai_in;
                // $newDatas["nilai_otbe"] = $tCounter->nilai_ot;
                $newDatas["nilai_otbe"] = $tCounter->nilai_af + $newDatas["nilai_in"] - $newDatas["nilai_ot"];
            }
            else {
                $counter = 1;
            }


            $newDatas["counter"] = $counter;
            arrPrintWebs($newDatas);
            // matiHere("insert");
            $this->db->insert($tableName, $newDatas);
            showLast_query("merah");
        }
        else {

            $new_jmltr_be = $lastData->jmltr_be;
            // $newdatas['jmltr_be'] = $new_jmltr_be;
            // ====================================
            //     $newdatas['jmltr_inbe'] = $lastData->jmltr_in;
            $jmltr_inin = $lastData->jmltr_inin + $newDatas["jmltr_inin"];
            $newdatas['jmltr_inin'] = $jmltr_inin;
            $jmltr_inot = $lastData->jmltr_inot + $newDatas["jmltr_inot"];
            $newdatas['jmltr_inot'] = $jmltr_inot;

            $new_jmltr_in = $lastData->jmltr_inbe + $jmltr_inin - $jmltr_inot;
            $newdatas['jmltr_in'] = $new_jmltr_in;
            // ====================================

            // $newdatas['jmltr_otbe'] = $lastData->jmltr_ot;
            //     $newdatas['jmltr_inbe'] = $lastData->jmltr_in;
            $jmltr_otin = $lastData->jmltr_otin + $newDatas["jmltr_otin"];
            $newdatas['jmltr_otin'] = $jmltr_otin;
            $jmltr_otot = $lastData->jmltr_otot + $newDatas["jmltr_otot"];
            $newdatas['jmltr_otot'] = $jmltr_otot;
            $new_jmltr_ot = $lastData->jmltr_otbe + $jmltr_otin - $jmltr_otot;
            $newdatas['jmltr_ot'] = $new_jmltr_ot;

            $new_jmltr_af = $new_jmltr_be + $new_jmltr_in - $new_jmltr_ot;
            $newdatas['jmltr_af'] = $new_jmltr_af;
            // =============================

            $new_unit_be = $lastData->unit_be;

            $unit_inin = $lastData->unit_inin + $newDatas["unit_inin"];
            $newdatas['unit_inin'] = $unit_inin;
            $unit_inot = $lastData->unit_inot + $newDatas["unit_inot"];
            $newdatas['unit_inot'] = $unit_inot;

            $new_unit_in = $lastData->unit_inbe + $unit_inin - $unit_inot;
            $newdatas['unit_in'] = $new_unit_in;

            $unit_otin = $lastData->unit_otin + $newDatas["unit_otin"];
            $newdatas['unit_otin'] = $unit_otin;
            $unit_otot = $lastData->unit_otot + $newDatas["unit_otot"];
            $newdatas['unit_otot'] = $unit_otot;

            $new_unit_ot = $lastData->unit_otbe + $unit_otin - $unit_otot;
            $newdatas['unit_ot'] = $new_unit_ot;

            $new_unit_af = $new_unit_be + $new_unit_in - $new_unit_ot;
            $newdatas['unit_af'] = $new_unit_af;

            // =====================================
            $new_nilai_be = $lastData->nilai_be;

            $nilai_inin = $lastData->nilai_inin + $newDatas["nilai_inin"];
            $newdatas['nilai_inin'] = $nilai_inin;
            $nilai_inot = $lastData->nilai_inot + $newDatas["nilai_inot"];
            $newdatas['nilai_inot'] = $nilai_inot;

            $new_nilai_in = $lastData->nilai_inbe + $nilai_inin - $nilai_inot;
            $newdatas['nilai_in'] = $new_nilai_in;

            $nilai_otin = $lastData->nilai_otin + $newDatas["nilai_otin"];
            $newdatas['nilai_otin'] = $nilai_otin;
            $nilai_otot = $lastData->nilai_otot + $newDatas["nilai_otot"];
            $newdatas['nilai_otot'] = $nilai_otot;

            $new_nilai_ot = $lastData->nilai_otbe + $nilai_otin - $nilai_otot;
            $newdatas['nilai_ot'] = $new_nilai_ot;

            $new_nilai_af = $new_nilai_be + $new_nilai_in - $new_nilai_ot;
            $newdatas['nilai_af'] = $new_nilai_af;
            // ===================================

            $newdatas['counter_terakhir'] = $newDatas['counter_terakhir'];

            // $newdatas['nilai_af'] = $tmp_3[0]->nilai_af + $newdatas['nilai_in'] - $newdatas['nilai_ot'];

            arrPrintWebs($newdatas);
            // matiHere(__FILE__);
            $this->db->where("id = '$lastData_id'");
            $this->db->update($tableName, $newdatas);
            showLast_query("biru");
            // matiHere("update");
            // if ($this->debug === true) {
            //     cekMerah($this->db2->last_query());
            // }

        }

    }

    public function lookUpAll(){
        $tbl = $this->tabel;
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            cekBiru($this->db2->last_query());
        }

        return $q;
}


}