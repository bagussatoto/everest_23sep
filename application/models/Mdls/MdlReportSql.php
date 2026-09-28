<?php

//--include_once "MdlHistoriData.php";
class MdlReportSql extends MdlMother
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
        parent::__construct();
        // $this->db2 = $this->load->database('report', TRUE);
        // $this->tableNames = array(
        //     // "582a"   => "penjualan_produk_seller",
        //     // "582spd" => "penjualan_seller_produk",
        //     "pre_penjualan"          => array(
        //         "tableName" => "pre_penjualan_seller_produk",
        //     ),
        //     "pre_penjualan_canceled" => array(
        //         "tableName" => "pre_penjualan_seller_produk_canceled",
        //     ),
        //     "penjualan"              => array(
        //         "tableName"   => array(
        //             "psp" => "penjualan_seller_produk",
        //             "ps"  => "penjualan_seller",
        //             "pp"  => "penjualan_produk",
        //             "p"   => "penjualan",
        //         ),
        //         "jenis"       => array("582spd", "982"),
        //         "tableName_2" => "penjualan_seller",
        //     ),
        //     "pembelian_supplies"     => array(
        //         "tableName" => "pembelian_vendor_supplies",
        //         "jenis"     => array("461", "961"),
        //     ),
        //     "pembelian_produk"       => array(
        //         "tableName" => "pembelian_vendor_produk",
        //         "jenis"     => array("467", "967"),
        //     ),
        // );
        // $this->updKoloms = array(
        //     "unit_ot",
        //     "unit_in",
        //     "unit_af",
        //     "nilai_ot",
        //     "nilai_in",
        //     "nilai_af",
        //     // "counter",
        // );
        // $this->tabels = array(
        //     "cabang"  => "cabang",
        //     "subject" => "seller",
        //     "object"  => "produk",
        // );

    }


    public function callPenjualan()
    {
        $condites = array(
            "jenis" => "582spd",
        );
        $this->db->where($condites);
        // $src = $this->db->get("__rek_pembantu_produk__persediaan_produk");
        $src = $this->db->get("__rek_pembantu_produk__1010030030");
//arrPrintPink($src);
        return $src;
    }

    public function callPembelianAllM_old()
    {

        $condites = array(
            "jenis" => "467",
        );
        $selectPembelian = array(
            "sum(qty_debet) as 'unit_af'",
            "sum(debet) as 'nilai_af'",
            "month(dtime) as 'bl'",
            "year(dtime) as 'th'",
        );
        $this->db->select($selectPembelian);
        $this->db->where($condites);
        $this->db->group_by("bl,th");
        $this->db->order_by("th,bl");
        $src = $this->db->get("__rek_pembantu_produk__persediaan_produk")->result();

        return $src;
    }
    public function callPembelianAllM()
    {
        $condites = array(
            // "jenis" => "489",
            "transaksi_no like" => "467%",
            // "extern_id" => "221",
        );
        $selectPembelian = array(
            // "sum(qty_debet) as 'unit_af'",
            "sum(kredit) as 'nilai_af'",
            // "date(dtime) as 'tg'",
            "month(dtime) as 'bl'",
            "year(dtime) as 'th'",
            "extern_id",
            "extern_nama",
        );
        $this->db->select($selectPembelian);
        $this->db->where($condites);
        $this->db->group_by("bl,th");
        $this->db->order_by("th,bl");
        $src = $this->db->get("__rek_pembantu_supplier__hutang_dagang")->result();

        return $src;
    }

    public function callPenjualanAllM()
    {
        $tbl = "__rek_pembantu_produk__persediaan_produk";
        $tbl = "_rek_master_cache";
        $condites = array(
            "periode"  => "bulanan",
            "rekening" => "penjualan",
        );
        $selectPembelian = array(
            "sum(qty_kredit) as 'unit_af'",
            "sum(kredit) as 'nilai_af'",
            "month(dtime) as 'bl'",
            "year(dtime) as 'th'",
        );
        $this->db->select($selectPembelian);
        $this->db->where($condites);
        $this->db->group_by("bl,th");
        $this->db->order_by("th,bl");
        $src = $this->db->get($tbl)->result();

        return $src;
    }

    public function callPenjualanAllM_salah()
    {
        $condites = array(
            "jenis" => "582",
        );
        $selectPembelian = array(
            "sum(qty_kredit) as 'unit_af'",
            "sum(kredit) as 'nilai_af'",
            "month(dtime) as 'bl'",
            "year(dtime) as 'th'",
        );
        $this->db->select($selectPembelian);
        $this->db->where($condites);
        $this->db->group_by("bl,th");
        $this->db->order_by("th,bl");
        $src = $this->db->get("__rek_pembantu_produk__persediaan_produk")->result();

        return $src;
    }

    public function callPembelianAllD()
    {

        $condites = array(
            "jenis" => "467",
        );
        $selectPembelian = array(
            "sum(qty_debet) as 'unit_af'",
            "sum(debet) as 'nilai_af'",
            "date(dtime) as 'tg'",
        );
        $this->db->select($selectPembelian);
        $this->db->where($condites);
        $this->db->group_by("tg");
        $this->db->order_by("tg");
        $src = $this->db->get("__rek_pembantu_produk__persediaan_produk")->result();

        return $src;
    }

    public function callPenjualanAllD_salah()
    {
        $condites = array(
            "jenis" => "582",
        );
        $selectPembelian = array(
            "sum(qty_kredit) as 'unit_af'",
            "sum(kredit) as 'nilai_af'",
            "date(dtime) as 'tg'",
        );
        $this->db->select($selectPembelian);
        $this->db->where($condites);
        $this->db->group_by("tg");
        $this->db->order_by("tg");
        $src = $this->db->get("__rek_pembantu_produk__persediaan_produk")->result();

        return $src;
    }

    public function callPenjualanAllD()
    {
        $tbl = "__rek_master__persediaan_produk";
        $tbl = "_rek_master_cache";
        $condites = array(
            // "jenis" => "582",
            "periode"  => "harian",
            "rekening" => "penjualan",
        );
        $selectPembelian = array(
            "sum(qty_kredit) as 'unit_af'",
            "sum(kredit) as 'nilai_af'",
            "date(dtime) as 'tg'",
        );
        $this->db->select($selectPembelian);
        $this->db->where($condites);
        $this->db->group_by("tg");
        $this->db->order_by("tg");
        $src = $this->db->get($tbl)->result();

        return $src;
    }

    public function callPenjualanProdukAll()
    {
        $condites = array(
            "jenis" => "582",
        );
        $selectPembelian = array(
            "sum(qty_kredit) as 'unit_af'",
            "sum(kredit) as 'nilai_af'",
            "month(dtime) as 'bl'",
            "year(dtime) as 'th'",
            "extern_id as 'subject_id'",
        );
        $this->db->select($selectPembelian);
        $this->db->where($condites);
        $this->db->group_by("extern_id");
        $this->db->order_by("th,bl");
        // $this->db->where($condites);
        $src = $this->db->get("__rek_pembantu_produk__persediaan_produk")->result();

        return $src;
    }

    public function callPembelianVendor()
    {
        $condites = array(
            // "jenis" => "489",
            "transaksi_no like" => "467%",
            // "extern_id" => "221",
        );
        $selectPembelian = array(
            // "sum(qty_debet) as 'unit_af'",
            "sum(kredit) as 'nilai_af'",
            // "date(dtime) as 'tg'",
            "month(dtime) as 'bl'",
            "year(dtime) as 'th'",
            "extern_id",
            "extern_nama",
            "extern_nama",
        );
        $this->db->select($selectPembelian);
        $this->db->where($condites);
        $this->db->group_by("extern_id,bl,th");
        $this->db->order_by("th,bl");
        $src = $this->db->get("__rek_pembantu_supplier__hutang_dagang")->result();

        return $src;
    }

    public function callPembelianVendorReturn()
    {
        $condites = array(
            // "jenis" => "489",
            "transaksi_no like" => "967%",
            // "extern_id" => "221",
        );
        $selectPembelian = array(
            // "sum(qty_debet) as 'unit_af'",
            "sum(debet) as 'nilai_af'",
            // "date(dtime) as 'tg'",
            "month(dtime) as 'bl'",
            "year(dtime) as 'th'",
            "extern_id",
            "extern_nama",
            "extern_nama",
        );
        $this->db->select($selectPembelian);
        $this->db->where($condites);
        $this->db->group_by("extern_id,bl,th");
        $this->db->order_by("th,bl");
        $src = $this->db->get("__rek_pembantu_supplier__piutang_pembelian")->result();

        return $src;
    }
}