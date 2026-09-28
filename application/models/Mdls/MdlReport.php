<?php

//--include_once "MdlHistoriData.php";
class MdlReport extends MdlMother //CI_Model
{

    protected $tableName = array();
    protected $tableNames = array();
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
    protected $tabel_cli;
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
    private $dataOriginal = array();

    private $dataCustomer = array();
    private $dataCustomerProduk = array();
    private $dataCustomerSeller = array();
    private $dataCustomerCabang = array();
    private $dataCustomerProdukCabang = array();
    private $dataCustomerSellerCabang = array();

    private $dataSeller = array();
    private $dataSellerCustomer = array();
    private $dataSellerProduk = array();
    private $dataSellerCabang = array();
    private $dataSellerCustomerCabang = array();
    private $dataSellerProdukCabang = array();

    private $dataProduk = array();
    private $dataProdukCustomer = array();
    private $dataProdukSeller = array();
    private $dataProdukCabang = array();
    private $dataProdukCustomerCabang = array();
    private $dataProdukSellerCabang = array();
    private $dataProdukSupplier = array();

    private $dataSupplies = array();
    private $dataSuppliesSupplier = array();
    private $dataSupplier = array();
    private $dataSupplierProduk = array();
    private $dataSupplierSupplies = array();

    private $dataCabang = array();
    private $dataCabangProduk = array();
    private $dataCabangSeller = array();
    private $dataCabangCustomer = array();

    private $jenis;
    private $condites;
    private $conditesCompared;


    public function setStartCommit()
    {
        return $this->db2->trans_start();
    }

    public function setEndCommit()
    {
        return $this->db2->trans_complete();
    }


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

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

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

    public function getDataP()
    {
        return $this->dataP;
    }

    public function setDataP($dataP)
    {
        $this->dataP = $dataP;
    }

    public function getDataOriginal()
    {
        return $this->dataOriginal;
    }

    public function setDataOriginal($dataOriginal)
    {
        $this->dataOriginal = $dataOriginal;
    }

    public function getDataCustomer()
    {
        return $this->dataCustomer;
    }

    public function setDataCustomer($dataCustomer)
    {
        $this->dataCustomer = $dataCustomer;
    }

    public function getDataCustomerProduk()
    {
        return $this->dataCustomerProduk;
    }

    public function setDataCustomerProduk($dataCustomerProduk)
    {
        $this->dataCustomerProduk = $dataCustomerProduk;
    }

    public function getDataCustomerSeller()
    {
        return $this->dataCustomerSeller;
    }

    public function setDataCustomerSeller($dataCustomerSeller)
    {
        $this->dataCustomerSeller = $dataCustomerSeller;
    }

    public function getDataCustomerCabang()
    {
        return $this->dataCustomerCabang;
    }

    public function setDataCustomerCabang($dataCustomerCabang)
    {
        $this->dataCustomerCabang = $dataCustomerCabang;
    }

    /**
     * @return array
     */
    public function getDataSellerCustomer()
    {
        return $this->dataSellerCustomer;
    }

    /**
     * @param array $dataSellerCustomer
     */
    public function setDataSellerCustomer($dataSellerCustomer)
    {
        $this->dataSellerCustomer = $dataSellerCustomer;
    }

    /**
     * @return array
     */
    public function getDataSellerCabang()
    {
        return $this->dataSellerCabang;
    }

    /**
     * @param array $dataSellerCabang
     */
    public function setDataSellerCabang($dataSellerCabang)
    {
        $this->dataSellerCabang = $dataSellerCabang;
    }

    /**
     * @return array
     */
    public function getDataProdukCustomer()
    {
        return $this->dataProdukCustomer;
    }

    /**
     * @param array $dataProdukCustomer
     */
    public function setDataProdukCustomer($dataProdukCustomer)
    {
        $this->dataProdukCustomer = $dataProdukCustomer;
    }

    /**
     * @return array
     */
    public function getDataProdukCabang()
    {
        return $this->dataProdukCabang;
    }

    /**
     * @param array $dataProdukCabang
     */
    public function setDataProdukCabang($dataProdukCabang)
    {
        $this->dataProdukCabang = $dataProdukCabang;
    }

    /**
     * @return array
     */
    public function getDataProdukSeller()
    {
        return $this->dataProdukSeller;
    }

    /**
     * @param array $dataProdukSeller
     */
    public function setDataProdukSeller($dataProdukSeller)
    {
        $this->dataProdukSeller = $dataProdukSeller;
    }

    /**
     * @return array
     */
    public function getDataCabang()
    {
        return $this->dataCabang;
    }

    /**
     * @param array $dataCabang
     */
    public function setDataCabang($dataCabang)
    {
        $this->dataCabang = $dataCabang;
    }

    /**
     * @return array
     */
    public function getDataCabangProduk()
    {
        return $this->dataCabangProduk;
    }

    /**
     * @param array $dataCabangProduk
     */
    public function setDataCabangProduk($dataCabangProduk)
    {
        $this->dataCabangProduk = $dataCabangProduk;
    }

    /**
     * @return array
     */
    public function getDataCabangSeller()
    {
        return $this->dataCabangSeller;
    }

    /**
     * @param array $dataCabangSeller
     */
    public function setDataCabangSeller($dataCabangSeller)
    {
        $this->dataCabangSeller = $dataCabangSeller;
    }

    /**
     * @return array
     */
    public function getDataCabangCustomer()
    {
        return $this->dataCabangCustomer;
    }

    /**
     * @param array $dataCabangCustomer
     */
    public function setDataCabangCustomer($dataCabangCustomer)
    {
        $this->dataCabangCustomer = $dataCabangCustomer;
    }

    /**
     * @return array
     */
    public function getDataCustomerProdukCabang()
    {
        return $this->dataCustomerProdukCabang;
    }

    /**
     * @param array $dataCustomerProdukCabang
     */
    public function setDataCustomerProdukCabang($dataCustomerProdukCabang)
    {
        $this->dataCustomerProdukCabang = $dataCustomerProdukCabang;
    }

    /**
     * @return array
     */
    public function getDataCustomerSellerCabang()
    {
        return $this->dataCustomerSellerCabang;
    }

    /**
     * @param array $dataCustomerSellerCabang
     */
    public function setDataCustomerSellerCabang($dataCustomerSellerCabang)
    {
        $this->dataCustomerSellerCabang = $dataCustomerSellerCabang;
    }

    /**
     * @return array
     */
    public function getDataSeller()
    {
        return $this->dataSeller;
    }

    /**
     * @param array $dataSeller
     */
    public function setDataSeller($dataSeller)
    {
        $this->dataSeller = $dataSeller;
    }

    /**
     * @return array
     */
    public function getDataSellerProduk()
    {
        return $this->dataSellerProduk;
    }

    /**
     * @param array $dataSellerProduk
     */
    public function setDataSellerProduk($dataSellerProduk)
    {
        $this->dataSellerProduk = $dataSellerProduk;
    }

    /**
     * @return array
     */
    public function getDataSellerCustomerCabang()
    {
        return $this->dataSellerCustomerCabang;
    }

    /**
     * @param array $dataSellerCustomerCabang
     */
    public function setDataSellerCustomerCabang($dataSellerCustomerCabang)
    {
        $this->dataSellerCustomerCabang = $dataSellerCustomerCabang;
    }

    /**
     * @return array
     */
    public function getDataSellerProdukCabang()
    {
        return $this->dataSellerProdukCabang;
    }

    /**
     * @param array $dataSellerProdukCabang
     */
    public function setDataSellerProdukCabang($dataSellerProdukCabang)
    {
        $this->dataSellerProdukCabang = $dataSellerProdukCabang;
    }

    /**
     * @return array
     */
    public function getDataProduk()
    {
        return $this->dataProduk;
    }

    /**
     * @param array $dataProduk
     */
    public function setDataProduk($dataProduk)
    {
        $this->dataProduk = $dataProduk;
    }

    /**
     * @return array
     */
    public function getDataProdukCustomerCabang()
    {
        return $this->dataProdukCustomerCabang;
    }

    /**
     * @param array $dataProdukCustomerCabang
     */
    public function setDataProdukCustomerCabang($dataProdukCustomerCabang)
    {
        $this->dataProdukCustomerCabang = $dataProdukCustomerCabang;
    }

    /**
     * @return array
     */
    public function getDataProdukSellerCabang()
    {
        return $this->dataProdukSellerCabang;
    }

    /**
     * @param array $dataProdukSellerCabang
     */
    public function setDataProdukSellerCabang($dataProdukSellerCabang)
    {
        $this->dataProdukSellerCabang = $dataProdukSellerCabang;
    }

    /**
     * @return array
     */
    public function getTableNames()
    {
        return $this->tableNames;
    }

    /**
     * @param array $tableNames
     */
    public function setTableNames($tableNames)
    {
        $this->tableNames = $tableNames;
    }

    /**
     * @return array
     */
    public function getDataProdukSupplier()
    {
        return $this->dataProdukSupplier;
    }

    /**
     * @param array $dataProdukSupplier
     */
    public function setDataProdukSupplier($dataProdukSupplier)
    {
        $this->dataProdukSupplier = $dataProdukSupplier;
    }

    /**
     * @return array
     */
    public function getDataSupplier()
    {
        return $this->dataSupplier;
    }

    /**
     * @param array $dataSupplier
     */
    public function setDataSupplier($dataSupplier)
    {
        $this->dataSupplier = $dataSupplier;
    }

    /**
     * @return array
     */
    public function getDataSupplierProduk()
    {
        return $this->dataSupplierProduk;
    }

    /**
     * @param array $dataSupplierProduk
     */
    public function setDataSupplierProduk($dataSupplierProduk)
    {
        $this->dataSupplierProduk = $dataSupplierProduk;
    }

    /**
     * @return array
     */
    public function getDataSupplies()
    {
        return $this->dataSupplies;
    }

    /**
     * @param array $dataSupplies
     */
    public function setDataSupplies($dataSupplies)
    {
        $this->dataSupplies = $dataSupplies;
    }

    /**
     * @return array
     */
    public function getDataSuppliesSupplier()
    {
        return $this->dataSuppliesSupplier;
    }

    /**
     * @param array $dataSuppliesSupplier
     */
    public function setDataSuppliesSupplier($dataSuppliesSupplier)
    {
        $this->dataSuppliesSupplier = $dataSuppliesSupplier;
    }

    /**
     * @return array
     */
    public function getDataSupplierSupplies()
    {
        return $this->dataSupplierSupplies;
    }

    /**
     * @param array $dataSupplierSupplies
     */
    public function setDataSupplierSupplies($dataSupplierSupplies)
    {
        $this->dataSupplierSupplies = $dataSupplierSupplies;
    }

    /**
     * @return string
     */
    public function getTabelCli()
    {
        return $this->tabel_cli;
    }

    /**
     * @param string $tabel_cli
     */
    public function setTabelCli($tabel_cli)
    {
        $this->tabel_cli = $tabel_cli;
    }

    //</editor-fold>


    function __construct()
    {
//        $this->db2 = $this->load->database('report', TRUE);
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
                    "pp" => "penjualan_produk",

                    "penjualan" => "penjualan",

                    // ------------------------------------
                    "seller" => "penjualan_seller",
                    "seller_cabang" => "penjualan_seller_cabang",
                    "seller_produk" => "penjualan_seller_produk",
                    "seller_customer" => "penjualan_seller_customer",
                    "seller_produk_cabang" => "penjualan_seller_produk_cabang",
                    "seller_customer_cabang" => "penjualan_seller_customer_cabang",

                    // ------------------------------------
                    "produk" => "penjualan_produk",
                    "produk_cabang" => "penjualan_produk_cabang",
                    "produk_customer" => "penjualan_produk_customer",
                    "produk_seller" => "penjualan_produk_seller",
                    "produk_customer_cabang" => "penjualan_produk_customer_cabang",
                    "produk_seller_cabang" => "penjualan_produk_seller_cabang",

                    // ------------------------------------
                    "customer" => "penjualan_customer",
                    "customer_cabang" => "penjualan_customer_cabang",
                    "customer_seller" => "penjualan_customer_seller",
                    "customer_produk" => "penjualan_customer_produk",
                    "customer_seller_cabang" => "penjualan_customer_seller_cabang",
                    "customer_produk_cabang" => "penjualan_customer_produk_cabang",

                    // ------------------------------------
                    "cabang" => "penjualan_cabang",
                    "cabang_seller" => "penjualan_cabang_seller",
                    "cabang_produk" => "penjualan_cabang_produk",
                    "cabang_customer" => "penjualan_cabang_customer",


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
//                "tableName" => "pembelian_vendor_supplies",
                "tableName" => array(
                    // ------------------------------------
//                    "all" => "pembelian",
                    "supplies" => "pembelian_supplies",
                    "supplies_supplier" => "pembelian_supplies_vendor",
//                    "supplier" => "pembelian_vendor",
                    "supplier_supplies" => "pembelian_vendor_supplies",
                    // ------------------------------------
                ),
                "jenis" => array("461", "961"),
            ),
            "pembelian_produk" => array(
                "tableName" => array(
                    // ------------------------------------
                    "all" => "pembelian",
                    "produk" => "pembelian_produk",
                    "produk_supplier" => "pembelian_produk_vendor",
                    "supplier" => "pembelian_vendor",
                    "supplier_produk" => "pembelian_vendor_produk",
                    // ------------------------------------
                ),
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
            "mongo",

//            "nilai_nppn_ot",
//            "nilai_nppn_in",
//            "nilai_nppn_af",
//            "nilai_nppv_ot",
//            "nilai_nppv_in",
//            "nilai_nppv_af",
//            "nilai_ppv_ot",
//            "nilai_ppv_in",
//            "nilai_ppv_af",
        );
        $this->tabels = array(
            "cabang" => "cabang",
            "subject" => "seller",
            "object" => "produk",
        );
        $this->tabel_cli = "__cli_laporan";

    }

    public function updateData($where, $datas)
    {
//        cekHitam($this->tableName);
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

    public function lookupData($tableName, $arrWhere)
    {

        $dbReport = $this->db2;
        if (isset($this->limit)) {
            $dbReport->limit($this->limit);
        }
        $dbReport->where($arrWhere);
        $q = $dbReport->get($tableName);

        return $q;
    }


    //<editor-fold desc="hpp">
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
    //</editor-fold>


    //<editor-fold desc="biaya">
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
    //</editor-fold>

    //---------------------------------------

    //<editor-fold desc="PENJUALAN SELLER">

    // -- PENJUALAN SELLER -------------------------
    public function lastCounterPenjualanSellerPeriode($seller_id)
    {
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["seller"];

        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("subject_id", $seller_id);
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
        $tableName = $this->tableNames[$jenis]["tableName"]["seller"];
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
        $tableName = $this->tableNames[$jenis]["tableName"]["seller"];

        $updKoloms = $this->updKoloms;

        if (isset($this->dataSeller)) {
            $datas = $this->dataSeller;
            $datas['periode'] = $this->periode;

        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        $tmp_3 = $this->lookupPenjualanSeller($subject_id)->result();


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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    //-----PENJUALAN SELLER PRODUK---------------------------
    public function lookupPenjualanSellerProduk($cabang_id, $seller_id, $produk_id)
    {
        $subject_id = $seller_id;
        $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["seller_produk"];
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

    public function lastCounterPenjualanSellerProdukPeriode($cabang_id, $seller_id, $produk_id)
    {
        $subject_id = $seller_id;
        $object_id = $produk_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $this->tableNames[$jenis]["tableName"]["seller_produk"];
        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("object_id", $object_id);
        $this->db2->where("subject_id", $subject_id);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function writePenjualanSellerProduk($cabang_id, $seller_id, $produk_id)
    {

        $subject_id = $seller_id;
        $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["seller_produk"];

        $updKoloms = $this->updKoloms;

        if (isset($this->dataSellerProduk)) {
            $datas = $this->dataSellerProduk;
            $datas['periode'] = $this->periode;

        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        $tmp_3 = $this->lookupPenjualanSellerProduk($cabang_id, $subject_id, $object_id)->result();

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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    //------PENJUALAN SELLER CUSTOMER-------------------------
    public function lookupPenjualanSellerCustomer($cabang_id, $seller_id, $customer_id)
    {
        $subject_id = $seller_id;
        $object_id = $customer_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["seller_customer"];
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
//            "cabang_id =" => $cabang_id,
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

    public function lastCounterSellerCustomerPeriode($cabang_id, $seller_id, $customer_id)
    {
        $subject_id = $seller_id;
        $object_id = $customer_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $this->tableNames[$jenis]["tableName"]["seller_customer"];

        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("subject_id", $subject_id);
        $this->db2->where("object_id", $object_id);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function writePenjualanSellerCustomer($cabang_id, $seller_id, $customer_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");

        $subject_id = $seller_id;
        $object_id = $customer_id;

        $tableName = $this->tableNames[$jenis]["tableName"]["seller_customer"];
        $updKoloms = $this->updKoloms;

        if (isset($this->dataSellerCustomer)) {
            $datas = $this->dataSellerCustomer;
            $datas['periode'] = $this->periode;

        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterSellerCustomerPeriode($cabang_id, $subject_id, $object_id)->result();

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

        $tmp_3 = $this->lookupPenjualanSellerCustomer($cabang_id, $subject_id, $object_id)->result();

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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    //-------PENJUALAN SELLER CABANG-----------------------
    public function lookupPenjualanSellerCabang($cabang_id, $seller_id)
    {
        $subject_id = $seller_id;
        $object_id = $cabang_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["seller_cabang"];
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

    public function lastCounterSellerCabangPeriode($cabang_id, $seller_id)
    {
        $subject_id = $seller_id;
        $object_id = $cabang_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $this->tableNames[$jenis]["tableName"]["seller_cabang"];

        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("subject_id", $subject_id);
        $this->db2->where("object_id", $object_id);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function writePenjualanSellerCabang($cabang_id, $seller_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");

        $subject_id = $seller_id;
        $object_id = $cabang_id;

        $tableName = $this->tableNames[$jenis]["tableName"]["seller_cabang"];
        $updKoloms = $this->updKoloms;

        if (isset($this->dataSellerCabang)) {
            $datas = $this->dataSellerCabang;
            $datas['periode'] = $this->periode;

        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterSellerCabangPeriode($cabang_id, $subject_id)->result();

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

        $tmp_3 = $this->lookupPenjualanSellerCabang($cabang_id, $subject_id)->result();

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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    //------PENJUALAN SELLER CUSTOMER CABANG-------------------------
    public function lookupPenjualanSellerCustomerCabang($cabang_id, $seller_id, $customer_id)
    {
        $subject_id = $seller_id;
        $object_id = $customer_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["seller_customer_cabang"];
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

    public function lastCounterSellerCustomerCabangPeriode($cabang_id, $seller_id, $customer_id)
    {
        $subject_id = $seller_id;
        $object_id = $customer_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $this->tableNames[$jenis]["tableName"]["seller_customer_cabang"];

        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("subject_id", $subject_id);
        $this->db2->where("object_id", $object_id);
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

    public function writePenjualanSellerCustomerCabang($cabang_id, $seller_id, $customer_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");

        $subject_id = $seller_id;
        $object_id = $customer_id;

        $tableName = $this->tableNames[$jenis]["tableName"]["seller_customer_cabang"];
        $updKoloms = $this->updKoloms;

        if (isset($this->dataSellerCustomerCabang)) {
            $datas = $this->dataSellerCustomerCabang;
            $datas['periode'] = $this->periode;

        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterSellerCustomerCabangPeriode($cabang_id, $subject_id, $object_id)->result();

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

        $tmp_3 = $this->lookupPenjualanSellerCustomerCabang($cabang_id, $subject_id, $object_id)->result();

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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    //-----PENJUALAN SELLER PRODUK CABANG---------------------------
    public function lookupPenjualanSellerProdukCabang($cabang_id, $seller_id, $produk_id)
    {
        $subject_id = $seller_id;
        $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["seller_produk_cabang"];
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
            "cabang_id =" => $cabang_id,
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

    public function lastCounterPenjualanSellerProdukCabangPeriode($cabang_id, $seller_id, $produk_id)
    {
        $subject_id = $seller_id;
        $object_id = $produk_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $this->tableNames[$jenis]["tableName"]["seller_produk_cabang"];
        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("object_id", $object_id);
        $this->db2->where("subject_id", $subject_id);
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

    public function writePenjualanSellerProdukCabang($cabang_id, $seller_id, $produk_id)
    {

        $subject_id = $seller_id;
        $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["seller_produk_cabang"];

        $updKoloms = $this->updKoloms;

        if (isset($this->dataSellerProdukCabang)) {
            $datas = $this->dataSellerProdukCabang;
            $datas['periode'] = $this->periode;

        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterSellerCustomerCabangPeriode($cabang_id, $subject_id, $object_id)->result();

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

        $tmp_3 = $this->lookupPenjualanSellerProdukCabang($cabang_id, $subject_id, $object_id)->result();

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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }



    //</editor-fold>


    //<editor-fold desc="PENJUALAN PRODUK">
    // ---- PENJUALAN PRODUK -----------------------
    public function lastCounterPenjualanProdukPeriode($produk_id)
    {
        $subject_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["produk"];
        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("subject_id", $subject_id);
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
        $tableName = $this->tableNames[$jenis]["tableName"]["produk"];
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
//            "cabang_id =" => 0,
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
        $tableName = $this->tableNames[$jenis]["tableName"]["produk"];

        $updKoloms = $this->updKoloms;

        if (isset($this->dataProduk)) {
            $datas = $this->dataProduk;
            $datas['periode'] = $this->periode;

        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterPenjualanProdukPeriode($subject_id)->result();

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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    // ------PENJUALAN PRODUK CABANG------------------
    public function lastCounterPenjualanProdukCabangPeriode($produk_id, $cabang_id)
    {
        $subject_id = $produk_id;
        $object_id = $cabang_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["produk_cabang"];

        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("subject_id", $subject_id);
        $this->db2->where("object_id", $object_id);

        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {

            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function lookupPenjualanProdukCabang($produk_id, $cabang_id)
    {
        $subject_id = $produk_id;
        $object_id = $cabang_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["produk_cabang"];
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
//            "cabang_id =" => $cabang_id,
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
        $object_id = $cabang_id;
        $tableName = $this->tableNames[$jenis]["tableName"]["produk_cabang"];

        $updKoloms = $this->updKoloms;

        if (isset($this->dataProdukCabang)) {
            $datas = $this->dataProdukCabang;
            $datas['periode'] = $this->periode;

        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterPenjualanProdukCabangPeriode($subject_id, $object_id)->result();

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

        $tmp_3 = $this->lookupPenjualanProdukCabang($subject_id, $object_id)->result();

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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    // -------PENJUALAN PRODUK CUSTOMER----------
    public function lookupPenjualanProdukCustomer($cabang_id, $produk_id, $customer_id)
    {
        $subject_id = $produk_id;
        $object_id = $customer_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["produk_customer"];
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

    public function lastCounterProdukCustomerPeriode($cabang_id, $produk_id, $customer_id)
    {
        $subject_id = $produk_id;
        $object_id = $customer_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $this->tableNames[$jenis]["tableName"]["produk_customer"];

        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("subject_id", $subject_id);
        $this->db2->where("object_id", $object_id);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function writePenjualanProdukCustomer($cabang_id, $produk_id, $customer_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");

        $subject_id = $produk_id;
        $object_id = $customer_id;

        $tableName = $this->tableNames[$jenis]["tableName"]["produk_customer"];
        $updKoloms = $this->updKoloms;

        if (isset($this->dataProdukCustomer)) {
            $datas = $this->dataProdukCustomer;
            $datas['periode'] = $this->periode;

        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterProdukCustomerPeriode($cabang_id, $subject_id, $object_id)->result();

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

        $tmp_3 = $this->lookupPenjualanProdukCustomer($cabang_id, $subject_id, $object_id)->result();

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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    //--------PENJUALAN PRODUK SELLER---------------------------
    public function lookupPenjualanProdukSeller($cabang_id, $produk_id, $seller_id)
    {
        $subject_id = $produk_id;
        $object_id = $seller_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["produk_seller"];
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

    public function lastCounterProdukSellerPeriode($cabang_id, $produk_id, $seller_id)
    {
        $subject_id = $produk_id;
        $object_id = $seller_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $this->tableNames[$jenis]["tableName"]["produk_seller"];

        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("subject_id", $subject_id);
        $this->db2->where("object_id", $object_id);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function writePenjualanProdukSeller($cabang_id, $produk_id, $seller_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");

        $subject_id = $produk_id;
        $object_id = $seller_id;

        $tableName = $this->tableNames[$jenis]["tableName"]["produk_seller"];
        $updKoloms = $this->updKoloms;

        if (isset($this->dataProdukSeller)) {
            $datas = $this->dataProdukSeller;
            $datas['periode'] = $this->periode;

        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterProdukSellerPeriode($cabang_id, $subject_id, $object_id)->result();

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

        $tmp_3 = $this->lookupPenjualanProdukSeller($cabang_id, $subject_id, $object_id)->result();

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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    // -------PENJUALAN PRODUK CUSTOMER CABANG----------
    public function lookupPenjualanProdukCustomerCabang($cabang_id, $produk_id, $customer_id)
    {
        $subject_id = $produk_id;
        $object_id = $customer_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["produk_customer_cabang"];
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

    public function lastCounterProdukCustomerCabangPeriode($cabang_id, $produk_id, $customer_id)
    {
        $subject_id = $produk_id;
        $object_id = $customer_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $this->tableNames[$jenis]["tableName"]["produk_customer_cabang"];

        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("subject_id", $subject_id);
        $this->db2->where("object_id", $object_id);
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

    public function writePenjualanProdukCustomerCabang($cabang_id, $produk_id, $customer_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");

        $subject_id = $produk_id;
        $object_id = $customer_id;

        $tableName = $this->tableNames[$jenis]["tableName"]["produk_customer_cabang"];
        $updKoloms = $this->updKoloms;

        if (isset($this->dataProdukCustomerCabang)) {
            $datas = $this->dataProdukCustomerCabang;
            $datas['periode'] = $this->periode;

        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterProdukCustomerCabangPeriode($cabang_id, $subject_id, $object_id)->result();

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

        $tmp_3 = $this->lookupPenjualanProdukCustomerCabang($cabang_id, $subject_id, $object_id)->result();

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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    //--------PENJUALAN PRODUK SELLER---------------------------
    public function lookupPenjualanProdukSellerCabang($cabang_id, $produk_id, $seller_id)
    {
        $subject_id = $produk_id;
        $object_id = $seller_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["produk_seller_cabang"];
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

    public function lastCounterProdukSellerCabangPeriode($cabang_id, $produk_id, $seller_id)
    {
        $subject_id = $produk_id;
        $object_id = $seller_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $this->tableNames[$jenis]["tableName"]["produk_seller_cabang"];

        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("subject_id", $subject_id);
        $this->db2->where("object_id", $object_id);
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

    public function writePenjualanProdukSellerCabang($cabang_id, $produk_id, $seller_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");

        $subject_id = $produk_id;
        $object_id = $seller_id;

        $tableName = $this->tableNames[$jenis]["tableName"]["produk_seller_cabang"];
        $updKoloms = $this->updKoloms;

        if (isset($this->dataProdukSellerCabang)) {
            $datas = $this->dataProdukSellerCabang;
            $datas['periode'] = $this->periode;

        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterProdukSellerCabangPeriode($cabang_id, $subject_id, $object_id)->result();

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

        $tmp_3 = $this->lookupPenjualanProdukSellerCabang($cabang_id, $subject_id, $object_id)->result();

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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }


    // --------------------------------------
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


    //-----------------------------------------------------
    //</editor-fold>


    //<editor-fold desc="PENJUALAN CABANG">
    //----PENJUALAN CABANG SELLER--------------------------------------
    public function lookupPenjualanCabangSeller($cabang_id, $cabang_id, $seller_id)
    {
        $subject_id = $cabang_id;
        $object_id = $seller_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["cabang_seller"];
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

    public function lastCounterCabangSellerPeriode($cabang_id, $cabang_id, $seller_id)
    {
        $subject_id = $cabang_id;
        $object_id = $seller_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $this->tableNames[$jenis]["tableName"]["cabang_seller"];

        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("cabang_id", $cabang_id);
        $this->db2->where("subject_id", $subject_id);
        $this->db2->where("object_id", $object_id);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function writePenjualanCabangSeller($cabang_id, $cabang_id, $seller_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");

        $subject_id = $cabang_id;
        $object_id = $seller_id;

        $tableName = $this->tableNames[$jenis]["tableName"]["cabang_seller"];
        $updKoloms = $this->updKoloms;

        if (isset($this->dataCabangSeller)) {
            $datas = $this->dataCabangSeller;
            $datas['periode'] = $this->periode;

        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterCabangSellerPeriode($cabang_id, $subject_id, $object_id)->result();

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

        $tmp_3 = $this->lookupPenjualanCabangSeller($cabang_id, $subject_id, $object_id)->result();

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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    //----PENJUALAN CABANG PRODUK-------------------------------------------------
    public function lookupPenjualanCabangProduk($cabang_id, $cabang_id, $produk_id)
    {
        $subject_id = $cabang_id;
        $object_id = $produk_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["cabang_produk"];
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

    public function lastCounterCabangProdukPeriode($cabang_id, $cabang_id, $produk_id)
    {
        $subject_id = $cabang_id;
        $object_id = $produk_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $this->tableNames[$jenis]["tableName"]["cabang_produk"];

        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("cabang_id", $cabang_id);
        $this->db2->where("subject_id", $subject_id);
        $this->db2->where("object_id", $object_id);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function writePenjualanCabangProduk($cabang_id, $cabang_id, $produk_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");

        $subject_id = $cabang_id;
        $object_id = $produk_id;

        $tableName = $this->tableNames[$jenis]["tableName"]["cabang_produk"];
        $updKoloms = $this->updKoloms;

        if (isset($this->dataCabangProduk)) {
            $datas = $this->dataCabangProduk;
            $datas['periode'] = $this->periode;

        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterCabangProdukPeriode($cabang_id, $subject_id, $object_id)->result();

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

        $tmp_3 = $this->lookupPenjualanCabangProduk($cabang_id, $subject_id, $object_id)->result();

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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    //----PENJUALAN CABANG CUSTOMER-------------------------------------------------
    public function lookupPenjualanCabangCustomer($cabang_id, $cabang_id, $customer_id)
    {
        $subject_id = $cabang_id;
        $object_id = $customer_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["cabang_customer"];
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

    public function lastCounterCabangCustomerPeriode($cabang_id, $cabang_id, $customer_id)
    {
        $subject_id = $cabang_id;
        $object_id = $customer_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $this->tableNames[$jenis]["tableName"]["cabang_customer"];

        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("cabang_id", $cabang_id);
        $this->db2->where("subject_id", $subject_id);
        $this->db2->where("object_id", $object_id);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function writePenjualanCabangCustomer($cabang_id, $cabang_id, $customer_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");

        $subject_id = $cabang_id;
        $object_id = $customer_id;

        $tableName = $this->tableNames[$jenis]["tableName"]["cabang_customer"];
        $updKoloms = $this->updKoloms;

        if (isset($this->dataCabangCustomer)) {
            $datas = $this->dataCabangCustomer;
            $datas['periode'] = $this->periode;

        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterCabangCustomerPeriode($cabang_id, $subject_id, $object_id)->result();

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

        $tmp_3 = $this->lookupPenjualanCabangCustomer($cabang_id, $subject_id, $object_id)->result();

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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    //-----PENJUALAN CABANG------------------------------------------------
    public function lookupPenjualanCabang($cabang_id, $cabang_id)
    {
        $subject_id = $cabang_id;
//        $object_id = $customer_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["cabang"];
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
//            "object_id =" => $object_id,
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

    public function lastCounterCabangPeriode($cabang_id, $cabang_id)
    {
        $subject_id = $cabang_id;
//        $object_id = $customer_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $this->tableNames[$jenis]["tableName"]["cabang"];

        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("cabang_id", $cabang_id);
        $this->db2->where("subject_id", $subject_id);
//        $this->db2->where("object_id", $object_id);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function writePenjualanCabang($cabang_id, $cabang_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");

        $subject_id = $cabang_id;
//        $object_id = $customer_id;

        $tableName = $this->tableNames[$jenis]["tableName"]["cabang"];
        $updKoloms = $this->updKoloms;

        if (isset($this->dataCabang)) {
            $datas = $this->dataCabang;
            $datas['periode'] = $this->periode;

        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterCabangPeriode($cabang_id, $subject_id)->result();

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

        $tmp_3 = $this->lookupPenjualanCabang($cabang_id, $subject_id)->result();

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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }
    //</editor-fold>


    //<editor-fold desc="PENJUALAN CUSTOMER">
    // -----PENJUALAN CUSTOMER SELLER---------------------------------
    public function lastCounterPenjualanCustomerSellerPeriode($cabang_id, $customer_id, $seller_id)
    {
        $subject_id = $customer_id;
        $object_id = $seller_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["customer_seller"];

        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("subject_id", $subject_id);
        $this->db2->where("object_id", $object_id);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function lookupPenjualanCustomerSeller($cabang_id, $customer_id, $seller_id)
    {
        $subject_id = $customer_id;
        $object_id = $seller_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["customer_seller"];
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

    public function writePenjualanCustomerSeller($cabang_id, $customer_id, $seller_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $subject_id = $customer_id;
        $object_id = $seller_id;

        $tableName = $this->tableNames[$jenis]["tableName"]["customer_seller"];
        $updKoloms = $this->updKoloms;

        if (isset($this->dataCustomerSeller)) {
            $datas = $this->dataCustomerSeller;
            $datas['periode'] = $this->periode;

        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterPenjualanCustomerSellerPeriode($cabang_id, $subject_id, $object_id)->result();

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


        $tmp_3 = $this->lookupPenjualanCustomerSeller($cabang_id, $subject_id, $object_id)->result();


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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    // -----PENJUALAN CUSTOMER PRODUK---------------------------------
    public function lastCounterPenjualanCustomerProdukPeriode($cabang_id, $customer_id, $produk_id)
    {
        $subject_id = $customer_id;
        $object_id = $produk_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["customer_produk"];

        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("subject_id", $subject_id);
        $this->db2->where("object_id", $object_id);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function lookupPenjualanCustomerProduk($cabang_id, $customer_id, $produk_id)
    {
        $subject_id = $customer_id;
        $object_id = $produk_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["customer_produk"];
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

    public function writePenjualanCustomerProduk($cabang_id, $customer_id, $produk_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $subject_id = $customer_id;
        $object_id = $produk_id;

        $tableName = $this->tableNames[$jenis]["tableName"]["customer_produk"];
        $updKoloms = $this->updKoloms;

        if (isset($this->dataCustomerProduk)) {
            $datas = $this->dataCustomerProduk;
            $datas['periode'] = $this->periode;

        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterPenjualanCustomerProdukPeriode($cabang_id, $subject_id, $object_id)->result();

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

        $tmp_3 = $this->lookupPenjualanCustomerProduk($cabang_id, $subject_id, $object_id)->result();


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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    // ------PENJUALAN CUSTOMER CABANG--------------------------------
    public function lastCounterPenjualanCustomerCabangPeriode($cabang_id, $customer_id, $cabang_id)
    {
        $subject_id = $customer_id;
        $object_id = $cabang_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["customer_cabang"];

        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("subject_id", $subject_id);
        $this->db2->where("object_id", $object_id);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function lookupPenjualanCustomerCabang($cabang_id, $customer_id, $cabang_id)
    {
        $subject_id = $customer_id;
        $object_id = $cabang_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["customer_cabang"];
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

    public function writePenjualanCustomerCabang($cabang_id, $customer_id, $cabang_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $subject_id = $customer_id;
        $object_id = $cabang_id;

        $tableName = $this->tableNames[$jenis]["tableName"]["customer_cabang"];
        $updKoloms = $this->updKoloms;

        if (isset($this->dataCustomerCabang)) {
            $datas = $this->dataCustomerCabang;
            $datas['periode'] = $this->periode;


        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterPenjualanCustomerCabangPeriode($cabang_id, $subject_id, $object_id)->result();

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

        $tmp_3 = $this->lookupPenjualanCustomerCabang($cabang_id, $subject_id, $object_id)->result();


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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    // -------PENJUALAN CUSTOMER-------------------------------
    public function lastCounterPenjualanCustomerPeriode($cabang_id, $customer_id)
    {
        $subject_id = $customer_id;
//        $object_id = $produk_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["customer"];

        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("subject_id", $subject_id);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function lookupPenjualanCustomer($cabang_id, $customer_id)
    {
        $subject_id = $customer_id;
//        $object_id = $produk_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["customer"];
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

    public function writePenjualanCustomer($cabang_id, $customer_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $subject_id = $customer_id;
//        $object_id = $produk_id;

        $tableName = $this->tableNames[$jenis]["tableName"]["customer"];
        $updKoloms = $this->updKoloms;

        if (isset($this->dataCustomer)) {
            $datas = $this->dataCustomer;
            $datas['periode'] = $this->periode;


        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterPenjualanCustomerPeriode($cabang_id, $subject_id)->result();

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


        $tmp_3 = $this->lookupPenjualanCustomer($cabang_id, $subject_id)->result();


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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    // -----PENJUALAN CUSTOMER SELLER CABANG---------------------------------
    public function lastCounterPenjualanCustomerSellerCabangPeriode($cabang_id, $customer_id, $seller_id)
    {
        $subject_id = $customer_id;
        $object_id = $seller_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["customer_seller_cabang"];

        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("subject_id", $subject_id);
        $this->db2->where("object_id", $object_id);
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

    public function lookupPenjualanCustomerSellerCabang($cabang_id, $customer_id, $seller_id)
    {
        $subject_id = $customer_id;
        $object_id = $seller_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["customer_seller_cabang"];
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

    public function writePenjualanCustomerSellerCabang($cabang_id, $customer_id, $seller_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $subject_id = $customer_id;
        $object_id = $seller_id;

        $tableName = $this->tableNames[$jenis]["tableName"]["customer_seller_cabang"];
        $updKoloms = $this->updKoloms;

        if (isset($this->dataCustomerSellerCabang)) {
            $datas = $this->dataCustomerSellerCabang;
            $datas['periode'] = $this->periode;


        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterPenjualanCustomerSellerCabangPeriode($cabang_id, $subject_id, $object_id)->result();

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


        $tmp_3 = $this->lookupPenjualanCustomerSellerCabang($cabang_id, $subject_id, $object_id)->result();


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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    // -----PENJUALAN CUSTOMER PRODUK CABANG---------------------------------
    public function lastCounterPenjualanCustomerProdukCabangPeriode($cabang_id, $customer_id, $produk_id)
    {
        $subject_id = $customer_id;
        $object_id = $produk_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["customer_produk_cabang"];

        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("subject_id", $subject_id);
        $this->db2->where("object_id", $object_id);
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

    public function lookupPenjualanCustomerProdukCabang($cabang_id, $customer_id, $produk_id)
    {
        $subject_id = $customer_id;
        $object_id = $produk_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["customer_produk_cabang"];
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

    public function writePenjualanCustomerProdukCabang($cabang_id, $customer_id, $produk_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $subject_id = $customer_id;
        $object_id = $produk_id;

        $tableName = $this->tableNames[$jenis]["tableName"]["customer_produk_cabang"];
        $updKoloms = $this->updKoloms;

        if (isset($this->dataCustomerProdukCabang)) {
            $datas = $this->dataCustomerProdukCabang;
            $datas['periode'] = $this->periode;


        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterPenjualanCustomerProdukCabangPeriode($cabang_id, $subject_id, $object_id)->result();

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

        $tmp_3 = $this->lookupPenjualanCustomerProdukCabang($cabang_id, $subject_id, $object_id)->result();


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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }



    //</editor-fold>


    //<editor-fold desc="PENJUALAN">
    public function lastCounterPenjualanPeriode()
    {
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        // arrPrintWebs($this->tableNames);
        // cekHere($jenis);
        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["penjualan"];
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
        $tableName = $this->tableNames[$jenis]["tableName"]["penjualan"];
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
//            "cabang_id =" => 0,
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

        $tableName = $this->tableNames[$jenis]["tableName"]["penjualan"];
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
            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
//        mati_disini(__FUNCTION__);
    }
    //</editor-fold>


    //---------------------------------------

    //<editor-fold desc="PEMBELIAN">
    public function lastCounterPembelianPeriode()
    {
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");

        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["all"];

        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


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

    public function lookupPembelian()
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["all"];
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
//            "cabang_id =" => 0,
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

        return $q;

    }

    public function writePembelian()
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");

        $tableName = $this->tableNames[$jenis]["tableName"]["all"];
        $updKoloms = $this->updKoloms;

        if (isset($this->datas)) {
            $datas = $this->datas;
            $datas['periode'] = $this->periode;
        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterPembelianPeriode()->result();

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

        $tmp_3 = $this->lookupPembelian()->result();

        $this->tableName = $tableName;
        if (sizeof($tmp_3) == 0) {
            $this->addData($datas);
        }
        else {

            $dbDatas = $tmp_3[0];

            foreach ($updKoloms as $updKolom) {
                $$updKolom = $dbDatas->$updKolom + $datas[$updKolom];

                $updDatas[$updKolom] = $dbDatas->$updKolom + $datas[$updKolom];
            }
            $condite = array(
                "id" => $dbDatas->id,
            );
            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }

    }
    //</editor-fold>

    //<editor-fold desc="PEMBELIAN SUPPLIER">

    // -- PEMBELIAN SUPPLIER -------------------------
    public function lastCounterPembelianSupplierPeriode($seller_id)
    {
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["supplier"];

        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("subject_id", $seller_id);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function lookupPembelianSupplier($seller_id)
    {
        $subject_id = $seller_id;
        // $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["supplier"];
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

    public function writePembelianSupplier($seller_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $subject_id = $seller_id;
        $tableName = $this->tableNames[$jenis]["tableName"]["supplier"];

        $updKoloms = $this->updKoloms;

        if (isset($this->dataSupplier)) {
            $datas = $this->dataSupplier;
            $datas['periode'] = $this->periode;

        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        $tmp_3 = $this->lookupPembelianSupplier($subject_id)->result();


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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    //-----PEMBELIAN SELLER PRODUK---------------------------
    public function lookupPembelianSupplierProduk($cabang_id, $seller_id, $produk_id)
    {
        $subject_id = $seller_id;
        $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["supplier_produk"];
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

    public function lastCounterPembelianSupplierProdukPeriode($cabang_id, $seller_id, $produk_id)
    {
        $subject_id = $seller_id;
        $object_id = $produk_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $this->tableNames[$jenis]["tableName"]["supplier_produk"];
        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("object_id", $object_id);
        $this->db2->where("subject_id", $subject_id);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function writePembelianSupplierProduk($cabang_id, $seller_id, $produk_id)
    {

        $subject_id = $seller_id;
        $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["supplier_produk"];

        $updKoloms = $this->updKoloms;

        if (isset($this->dataSupplierProduk)) {
            $datas = $this->dataSupplierProduk;
            $datas['periode'] = $this->periode;

        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        $tmp_3 = $this->lookupPembelianSupplierProduk($cabang_id, $subject_id, $object_id)->result();

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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }




    //</editor-fold>

    //<editor-fold desc="PEMBELIAN PRODUK">
    // ---- PENJUALAN PRODUK -----------------------
    public function lastCounterPembelianProdukPeriode($produk_id)
    {
        $subject_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["produk"];
        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("subject_id", $subject_id);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function lookupPembelianProduk($produk_id)
    {
        $subject_id = $produk_id;
        // $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["produk"];
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
//            "cabang_id =" => 0,
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

    public function writePembelianProduk($produk_id)
    {
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $subject_id = $produk_id;

        $tableName = $this->tableNames[$jenis]["tableName"]["produk"];

        $updKoloms = $this->updKoloms;

        if (isset($this->dataProduk)) {
            $datas = $this->dataProduk;
            $datas['periode'] = $this->periode;
        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterPembelianProdukPeriode($subject_id)->result();

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

        $tmp_3 = $this->lookupPembelianProduk($subject_id)->result();

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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);

        }
    }

    //--------PENJUALAN PRODUK SELLER---------------------------
    public function lookupPembelianProdukSupplier($cabang_id, $produk_id, $seller_id)
    {
        $subject_id = $produk_id;
        $object_id = $seller_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["produk_supplier"];
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

    public function lastCounterPembelianProdukSupplierPeriode($cabang_id, $produk_id, $seller_id)
    {
        $subject_id = $produk_id;
        $object_id = $seller_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $this->tableNames[$jenis]["tableName"]["produk_supplier"];

        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("subject_id", $subject_id);
        $this->db2->where("object_id", $object_id);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function writePembelianProdukSupplier($cabang_id, $produk_id, $seller_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");

        $subject_id = $produk_id;
        $object_id = $seller_id;

        $tableName = $this->tableNames[$jenis]["tableName"]["produk_supplier"];
        $updKoloms = $this->updKoloms;

        if (isset($this->dataProdukSupplier)) {
            $datas = $this->dataProdukSupplier;
            $datas['periode'] = $this->periode;
        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }
        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterPembelianProdukSupplierPeriode($cabang_id, $subject_id, $object_id)->result();

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

        $tmp_3 = $this->lookupPembelianProdukSupplier($cabang_id, $subject_id, $object_id)->result();

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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    // --------------------------------------
    //-----------------------------------------------------
    //</editor-fold>


    // ---- PENJUALAN SUPPLIES -----------------------
    public function lastCounterPembelianSuppliesPeriode($produk_id)
    {
        $subject_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["supplies"];
        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("subject_id", $subject_id);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function lookupPembelianSupplies($produk_id)
    {
        $subject_id = $produk_id;
        // $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["supplies"];
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
//            "cabang_id =" => 0,
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

    public function writePembelianSupplies($produk_id)
    {
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $subject_id = $produk_id;

        $tableName = $this->tableNames[$jenis]["tableName"]["supplies"];

        $updKoloms = $this->updKoloms;

        if (isset($this->dataSupplies)) {
            $datas = $this->dataSupplies;
            $datas['periode'] = $this->periode;
        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterPembelianSuppliesPeriode($subject_id)->result();

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

        $tmp_3 = $this->lookupPembelianSupplies($subject_id)->result();

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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);

        }
    }

    //--------PENJUALAN SUPPLIES SUPPLIER---------------------------
    public function lookupPembelianSuppliesSupplier($cabang_id, $produk_id, $seller_id)
    {
        $subject_id = $produk_id;
        $object_id = $seller_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["supplies_supplier"];
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

    public function lastCounterPembelianSuppliesSupplierPeriode($cabang_id, $produk_id, $seller_id)
    {
        $subject_id = $produk_id;
        $object_id = $seller_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $this->tableNames[$jenis]["tableName"]["supplies_supplier"];

        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("subject_id", $subject_id);
        $this->db2->where("object_id", $object_id);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function writePembelianSuppliesSupplier($cabang_id, $produk_id, $seller_id)
    {

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");

        $subject_id = $produk_id;
        $object_id = $seller_id;

        $tableName = $this->tableNames[$jenis]["tableName"]["supplies_supplier"];
        $updKoloms = $this->updKoloms;

        if (isset($this->dataSuppliesSupplier)) {
            $datas = $this->dataSuppliesSupplier;
            $datas['periode'] = $this->periode;
        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }
        //region Description
        $tanggal = $this->tanggal;
        $tmp_c = $this->lastCounterPembelianSuppliesSupplierPeriode($cabang_id, $subject_id, $object_id)->result();

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

        $tmp_3 = $this->lookupPembelianSuppliesSupplier($cabang_id, $subject_id, $object_id)->result();

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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }

    //-----PEMBELIAN SELLER PRODUK---------------------------
    public function lookupPembelianSupplierSupplies($cabang_id, $seller_id, $produk_id)
    {
        $subject_id = $seller_id;
        $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["supplier_supplies"];
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

    public function lastCounterPembelianSupplierSuppliesPeriode($cabang_id, $seller_id, $produk_id)
    {
        $subject_id = $seller_id;
        $object_id = $produk_id;

        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");


        $tbl = $this->tableNames[$jenis]["tableName"]["supplier_supplies"];
        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";


        $this->db2->where("periode", $periode);
        $this->db2->where("object_id", $object_id);
        $this->db2->where("subject_id", $subject_id);
        $this->db2->order_by("id DESC");
        $this->db2->limit(1);
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            // arrPrintWebs($q->result());
            cekOrange($this->db2->last_query());
        }

        return $q;
    }

    public function writePembelianSupplierSupplies($cabang_id, $seller_id, $produk_id)
    {

        $subject_id = $seller_id;
        $object_id = $produk_id;
        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
        $tableName = $this->tableNames[$jenis]["tableName"]["supplier_supplies"];

        $updKoloms = $this->updKoloms;

        if (isset($this->dataSupplierSupplies)) {
            $datas = $this->dataSupplierSupplies;
            $datas['periode'] = $this->periode;

        }
        else {
            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
        }

        $tmp_3 = $this->lookupPembelianSupplierSupplies($cabang_id, $subject_id, $object_id)->result();

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

            $updDatas['mongo'] = "0";
            $this->updateData($condite, $updDatas);
            // cekHitam("bagian update");
        }
    }


    // --------------------------------------
    public function lookupPembelianProduk__()
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

    public function lookupPembelianSupplies__()
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

    public function callPembelianAll__()
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
//    public function lastCounterPenjualanCabangPeriode($cabang_id)
//    {
//        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
//        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");
//
//        // arrPrintWebs($this->tableNames);
//        // cekHere($jenis);
//        $tbl = $tableName = $this->tableNames[$jenis]["tableName"]["p"];
//        // $tableName = "penjualan_seller_produk";
//        strlen($tbl) < 2 ? matiHere(__METHOD__ . " table dB tidak terdeteksi ====>>> $tbl") : "";
//        // arrPrintWebs($tableName);
//
//
//        $this->db2->where("periode", $periode);
//        $this->db2->where("cabang_id", $cabang_id);
//        $this->db2->order_by("id DESC");
//        $this->db2->limit(1);
//        $q = $this->db2->get($tbl);
//
//        if ($this->debug === true) {
//            // arrPrintWebs($q->result());
//            cekOrange($this->db2->last_query());
//        }
//
//        return $q;
//    }
//
//    public function lookupPenjualanCabang($cabang_id)
//    {
//        // $subject_id = $produk_id;
//        // $object_id = $produk_id;
//        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
//        $tableName = $this->tableNames[$jenis]["tableName"]["p"];
//        $dbReport = $this->db2;
//
//        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " periode belum di set");
//
//        switch ($periode) {
//            case "harian":
//                isset($this->tanggal) ? $dbReport->where("tanggal", $this->tanggal) : "";
//                break;
//            case "mingguan":
//                if (isset($this->minggu)) {
//                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
//                    $dbReport->where(array(
//                        "mg" => $this->minggu,
//                        "th" => $this->tahun,
//                    ));
//                }
//
//                break;
//            case "bulanan":
//                if (isset($this->bulan)) {
//                    !isset($this->tahun) ? matiHere(__FUNCTION__ . " tahun belum di set") : "";
//                    $dbReport->where(array(
//                        "bl" => $this->bulan,
//                        "th" => $this->tahun,
//                    ));
//                }
//                break;
//            case "tahunan":
//                isset($this->tahun) ? $dbReport->where("th", $this->tahun) : "";
//                break;
//        }
//
//        $arrWhere = array(
//            // "subject_id =" => $subject_id,
//            // "object_id ="  => $object_id,
//            "cabang_id =" => $cabang_id,
//        );
//        $arrWhere["periode ="] = $periode;
//
//        $dbReport->where($arrWhere);
//
//        if (isset($this->order)) {
//            $dbReport->order_by($this->order);
//        }
//        else {
//            $dbReport->order_by('id ASC');
//        }
//
//        if (isset($this->limit)) {
//            $dbReport->limit($this->limit);
//        }
//
//        $q = $dbReport->get($tableName);
//
//        if ($this->debug === true) {
//            cekKuning($dbReport->last_query());
//        }
//
//        return $q;
//
//    }
//
//    public function writePenjualanCabang($cabang_id)
//    {
//
//        $jenis = isset($this->jenis) ? $this->jenis : matiHere(__METHOD__ . " <b>setJenis</b> silahkan di set");
//        // $subject_id = $produk_id;
//        // $object_id = $produk_id;
//        $tableName = $this->tableNames[$jenis]["tableName"]["p"];
//        // $dbReport = $this->db2;
//        $updKoloms = $this->updKoloms;
//
////        if (isset($this->dataP)) {
////            $datas = $this->dataP;
////            $datas['periode'] = $this->periode;
////        }
////        else {
////            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
////        }
//        if (isset($this->dataOriginal)) {
//            $datas = $this->dataOriginal;
//            $datas['periode'] = $this->periode;
//        }
//        else {
//            matiHere(__METHOD__ . " <b>setDatas</b> silahkan di set dolo ya......");
//        }
//
//        //region Description
//        $tanggal = $this->tanggal;
//        $tmp_c = $this->lastCounterPenjualanCabangPeriode($cabang_id)->result();
//
//        if (sizeof($tmp_c) > 0) {
//            $tCounter = $tmp_c[0];
//            $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
//            $lastTanggal = $tCounter->tanggal;
//
//            if ($tanggal != $lastTanggal) {
//                $counter = $lastCounter + 1;
//            }
//            else {
//                $counter = $lastCounter;
//            }
//        }
//        else {
//            $counter = 1;
//            $lastCounter = 0;
//            $lastTanggal = "";
//        }
//        //endregion
//        $datas["counter"] = $counter;
//        $datas["cabang_id"] = $cabang_id;
//
//        $tmp_3 = $this->lookupPenjualanCabang($cabang_id)->result();
//        // arrPrint($tmp_3);
//        // arrPrintWebs($datas);
//        // matiHere(__FILE__ ." @". __LINE__);
//        $this->tableName = $tableName;
//        if (sizeof($tmp_3) == 0) {
//            $this->addData($datas);
//        }
//        else {
//
//            $dbDatas = $tmp_3[0];
//            // $newCounter = $dbDatas->counter +1;
//            foreach ($updKoloms as $updKolom) {
//                $$updKolom = $dbDatas->$updKolom + $datas[$updKolom];
//
//                $updDatas[$updKolom] = $dbDatas->$updKolom + $datas[$updKolom];
//            }
//            $condite = array(
//                "id" => $dbDatas->id,
//            );
//
//            $this->updateData($condite, $updDatas);
//            // cekHitam("bagian update");
//        }
//    }


    // --------------------------------------
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
        cekHere($this->db2->last_query());
        if ($this->debug === true) {
            cekBiru($this->db2->last_query());
        }

        return $q;
    }

    public function lookupSalesMonthly()
    {

        $condites = isset($this->condites) ? $this->condites : "";
        $conditesCompared = isset($this->conditesCompared) ? $this->conditesCompared : NULL;

//        $tbl = $this->tableNames['penjualan']['tableName']['ps'];
        $tbl = $this->tableNames['penjualan']['tableName']['seller'];
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
// arrPrint($condites_0);
        $tbl = $this->tableNames['pembelian_supplies']['tableName']['supplier_supplies'];
        // cekMerah($tbl);
        // foreach ($condites_0 as $kolom => $nilai) {
        //     $condites[$tbl.'.'.$kolom] = $nilai;
        // }

        if (isset($this->condites)) {
            $this->db2->where($condites);
        }
        $this->db2->order_by("subject_nama ASC");
        $this->db2->where(array("periode" => "bulanan"));
        $q = $this->db2->get($tbl);
// $q= array();
        if ($this->debug === true) {
            cekBiru($this->db2->last_query());
        }

        return $q;
    }

    public function lookupPurchasingFgMonthly()
    {

        $condites = isset($this->condites) ? $this->condites : "";

        $tbl = $this->tableNames['pembelian_produk']['tableName']['supplier'];
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

    public function lookUpAll()
    {
        $tbl = $this->tabel;
        $q = $this->db2->get($tbl);

        if ($this->debug === true) {
            cekBiru($this->db2->last_query());
        }

        return $q;
    }

    public function lookupPembelianProdukAll()
    {
        $condites = isset($this->condites) ? $this->condites : matiHere(__METHOD__ . " <b>setCondites</b> silahkan di set");
        $periode = isset($this->periode) ? $this->periode : matiHere(__METHOD__ . " <b>setPeriode</b> silahkan di set");
        $tableName = $this->tableNames["pembelian_produk"]["tableName"]["produk"];

        $this->db2->where($condites + array("periode" => $periode));
        $q = $this->db2->get($tableName);
        // $this->lookup

        if ($this->debug === true) {
            cekKuning($this->db2->last_query());
        }

        return $q;
    }

    /* ---------------------------------------------
     * pembelian vendor
     * ---------------------------------------------*/
    public function callPembelianVendor()
    {
        $periode = isset($this->periode) ? $this->periode : matiHere("periode silahkan di set");
        $condites = array(
            "periode" => $periode,
            // "transaksi_no like" => "467%",
            // "extern_id" => "221",
        );
        $selectPembelian = array(
            // "sum(qty_debet) as 'unit_af'",
            "sum(kredit) as 'nilai_af'",
            // "date(dtime) as 'tg'",
            // "month(dtime) as 'bl'",
            // "year(dtime) as 'th'",
            "extern_id",
            "extern_nama",
        );
        // $this->db->select($selectPembelian);
        $this->db2->where($condites);
        // $this->db->group_by("extern_id,bl,th");
        $this->db2->order_by("th,bl");
        $src = $this->db2->get("pembelian_vendor")->result();

        return $src;
    }

    // public function callPembelianVendorReturn()
    // {
    //     $condites = array(
    //         // "jenis" => "489",
    //         "transaksi_no like" => "967%",
    //         // "extern_id" => "221",
    //     );
    //     $selectPembelian = array(
    //         // "sum(qty_debet) as 'unit_af'",
    //         "sum(debet) as 'nilai_af'",
    //         // "date(dtime) as 'tg'",
    //         "month(dtime) as 'bl'",
    //         "year(dtime) as 'th'",
    //         "extern_id",
    //         "extern_nama",
    //         "extern_nama",
    //     );
    //     $this->db->select($selectPembelian);
    //     $this->db->where($condites);
    //     $this->db->group_by("extern_id,bl,th");
    //     $this->db->order_by("th,bl");
    //     $src = $this->db->get("__rek_pembantu_supplier__piutang_pembelian")->result();
    //
    //     return $src;
    // }


    public function lookUpCliReportID($transaksi_id)
    {
        $arrWhere = array(
            "transaksi_id" => $transaksi_id,
        );

        $tableName = $this->tabel_cli;

        $this->db2->where($arrWhere);
        $q = $this->db2->get($tableName)->result();
        if (sizeof($q) > 0) {
            $result = 1;
        }
        else {
            $result = 0;
        }
        return $result;
    }


}


?>