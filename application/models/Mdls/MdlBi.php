<?php

//--include_once "MdlHistoriData.php";
class MdlBi extends MdlMother
{

    protected $tableName = "set_bi";
    protected $fields = array();
    protected $indexFields;
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
    private $jenis;
    private $condites;


    //<editor-fold desc="geter setter">
    public function getDataGlundungs()
    {
        return $this->dataGlundungs;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
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

    //</editor-fold>

    function __construct()
    {
        parent::__construct();

        // $this->db2 = $this->load->database('report', TRUE);
        $this->tableNames = array(
            "1"  => "__rek_pembantu_produk__persediaan_produk",
            "00" => "_rek_pembantu_produk_cache",
            "11" => "price_per_supplier",
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
        $this->jenisPenjualan = array(
            "582spd", "382spd"
        );
        $this->jenisPenjualanReturn = array(
            "982"
        );
        $this->kolomSelecteds = array(
            "1" => array(
                "extern_id",
                "extern_nama",
                "cabang_id",
                "dtime",
                // "qty_debet_awal",
                "qty_debet",
                // "qty_debet_akhir",
                // "qty_kredit_awal",
                "qty_kredit",
                // "qty_kredit_akhir",
            )
        );
        $this->kolomOuts = array(
            "1"  => array(
                "extern_id",
                "extern_nama",
                "cabang_id",
                // "qty_debet_awal",
                // "qty_debet",
                // "qty_debet_akhir",
                // "qty_kredit_awal",
                // "qty_kredit",
                // "qty_kredit_akhir",
            ),
            "00" => array(
                "cabang_id",
                "extern_id",
                "extern_nama",
            ),
            "11" => array(
                "suppliers_id",
                "nilai",
                "dtime",
            ),
        );
    }

    public function getStokNow($produk_ids = "")
    {
        // $kolomSelected = $this->kolomSelecteds[1];
        $kolomOuts = $this->kolomOuts["00"];

        $tbl = $this->tableNames["00"];
        $wheres = array(
            "periode" => "forever",
        );

        if ($produk_ids == "") {

        }
        else {
            if (is_array($produk_ids)) {
                $this->db->where_in("produk_id", $produk_ids);
            }
            else {
                $this->db->where("produk_id", $produk_ids);
            }
        }
        // $this->db->select($kolomSelected);
        // $this->db->where_in("jenis", $jeniesis);
        sizeof($wheres) > 0 ? $this->db->where($wheres) : "";
        $q = $this->db->get($tbl);
        $qDatas = $q->result();

        $datas = array();
        $mains = array();
        $sums = array();
        if (sizeof($qDatas) > 0) {
            // arrPrint($qDatas);
            foreach ($qDatas as $qData) {
                $produk_id = $qData->extern_id;
                $cabang_id = $qData->cabang_id;
                // $qty_kredit = $qData->qty_kredit;
                $qty_debet = $qData->qty_debet;
                // foreach ($kolomSelected as $kolomKey) {
                //     $$kolomKey = $qData->$kolomKey;
                // }
                foreach ($kolomOuts as $kolom) {
                    $tmpDatas[$kolom] = $qData->$kolom;
                }

                $mains[$cabang_id][$produk_id] = $tmpDatas;
                // $salesSums[$cabang_id][$produk_id] = $datas;

                // cekHijau("$cabang_id /// $produk_id ==== $qty_kredit");

                if (!isset($sums[$cabang_id][$produk_id]["qty_debet_sum"])) {
                    $sums[$cabang_id][$produk_id]["qty_debet_sum"] = 0;
                }
                $sums[$cabang_id][$produk_id]["qty_debet_sum"] += $qty_debet;


            }
            // arrPrintWebs($salesSums);
        }
        // $datas['mains'] = $mains;
        $datas['sums'] = $sums;

        return $datas;
    }

    public function getStokNowAll()
    {
        // $kolomSelected = $this->kolomSelecteds[1];
        $kolomOuts = $this->kolomOuts["00"];

        $tbl = $this->tableNames["00"];
        $wheres = array(
            "periode" => "forever",
            // "extern_id" => "254"
        );

        // $this->db->select($kolomSelected);
        // $this->db->where_in("jenis", $jeniesis);
        sizeof($wheres) > 0 ? $this->db->where($wheres) : "";
        $q = $this->db->get($tbl);
        $qDatas = $q->result();

        $datas = array();
        $mains = array();
        // $sums = array();
        if (sizeof($qDatas) > 0) {
            // arrPrint($qDatas);
            foreach ($qDatas as $qData) {
                $produk_id = $qData->extern_id;
                $cabang_id = $qData->cabang_id;
                // $qty_kredit = $qData->qty_kredit;
                $qty_debet = $qData->qty_debet;
                // foreach ($kolomSelected as $kolomKey) {
                //     $$kolomKey = $qData->$kolomKey;
                // }
                foreach ($kolomOuts as $kolom) {
                    $tmpDatas[$kolom] = $qData->$kolom;
                }

                $mains[$produk_id] = $tmpDatas;
                // $salesSums[$cabang_id][$produk_id] = $datas;

                // cekHijau("$cabang_id /// $produk_id ==== $qty_debet");

                if (!isset($sums[$produk_id]["qty_debet_sum"])) {
                    $sums[$produk_id]["qty_debet_sum"] = 0;
                }
                $sums[$produk_id]["qty_debet_sum"] += $qty_debet;

                $stok_cabangs[$cabang_id][$produk_id]["qty_debet_sum"] = $qty_debet;
                // arrPrintWebs($sums);
            }
            // arrPrintWebs($salesSums);
        }
        // $datas['mains'] = $mains;
        $datas['sums'] = $sums;
        $datas['cabang'] = $stok_cabangs;

        return $datas;
    }

    public function getLastPurchase($produk_ids = "")
    {
        // $kolomSelected = $this->kolomSelecteds[1];
        $kolomOuts = $this->kolomOuts["11"];

        $tbl = $this->tableNames["11"];
        $wheres = array(
            "jenis" => "produk",
        );

        if ($produk_ids == "") {

        }
        else {
            if (is_array($produk_ids)) {
                $this->db->where_in("produk_id", $produk_ids);
            }
            else {
                $this->db->where("produk_id", $produk_ids);
            }
        }
        // $this->db->select($kolomSelected);
        // $this->db->where_in("jenis", $jeniesis);
        sizeof($wheres) > 0 ? $this->db->where($wheres) : "";
        $this->db->order_by("dtime", "desc");
        // $this->db->limit(1);
        $q = $this->db->get($tbl);
        $qDatas = $q->result();

        $datas = array();
        $mains = array();
        $sums = array();
        if (sizeof($qDatas) > 0) {
            // arrPrint($qDatas);
            foreach ($qDatas as $qData) {
                $produk_id = $qData->produk_id;
                $supplier_id = $qData->suppliers_id;
                $dtime = $qData->dtime;
                $nilai = $qData->nilai;
                // foreach ($kolomSelected as $kolomKey) {
                //     $$kolomKey = $qData->$kolomKey;
                // }
                foreach ($kolomOuts as $kolom) {
                    $tmpDatas[$kolom] = $qData->$kolom;
                }

                $datas[$produk_id][$supplier_id] = $tmpDatas;
                // $salesSums[$cabang_id][$produk_id] = $datas;

                // cekHijau("$cabang_id /// $produk_id ==== $qty_kredit");

                // if (!isset($sums[$cabang_id][$produk_id]["qty_debet_sum"])) {
                //     $sums[$cabang_id][$produk_id]["qty_debet_sum"] = 0;
                // }
                // $sums[$cabang_id][$produk_id]["qty_debet_sum"] += $qty_debet;


            }
            // arrPrintWebs($salesSums);
        }
        // $datas['mains'] = $mains;
        $datas_akhir['datas'] = $datas;

        return $datas_akhir;
    }

    public function getPenjualan($wheres = "")
    {
        $kolomSelected = $this->kolomSelecteds[1];
        $kolomOuts = $this->kolomOuts[1];
        $jeniesis = $this->jenisPenjualan;
        $tbl = $this->tableNames[1];

        // $wheres = array(
        //     // "extern_id" => "34",
        //     "dtime >" => "2019-01-01",
        // );

        $this->db->select($kolomSelected);
        $this->db->where_in("jenis", $jeniesis);
        sizeof($wheres) > 0 ? $this->db->where($wheres) : "";
        $q = $this->db->get($tbl);

        return $q;
    }

    public function getReturnPenjualan($wheres = "")
    {
        $kolomSelected = $this->kolomSelecteds[1];
        $kolomOuts = $this->kolomOuts[1];
        $jeniesis = $this->jenisPenjualanReturn;
        $tbl = $this->tableNames[1];

        // $wheres = array(
        //     // "extern_id" => "34",
        //     "dtime >" => "2019-01-01",
        // );

        $this->db->select($kolomSelected);
        $this->db->where_in("jenis", $jeniesis);
        sizeof($wheres) > 0 ? $this->db->where($wheres) : "";
        $q = $this->db->get($tbl);

        return $q;
    }

    public function lookupPenjualanProduk()
    {
        $kolomSelected = $this->kolomSelecteds[1];
        $kolomOuts = $this->kolomOuts[1];

        $wheres = array(
            // "extern_id" => "34",
            "dtime >" => "2020-01-01",
        );

        $qDatas = $this->getPenjualan($wheres)->result();


        if (sizeof($qDatas) > 0) {
            // arrPrint($qDatas);
            $salesSums = array();
            foreach ($qDatas as $qData) {
                $produk_id = $qData->extern_id;
                foreach ($kolomSelected as $kolomKey) {
                    $$kolomKey = $qData->$kolomKey;
                }
                foreach ($kolomOuts as $kolom) {
                    $datas[$kolom] = $qData->$kolom;
                }

                $salesSums[$cabang_id][$produk_id] = $datas;

                if (!isset($salesSums[$cabang_id][$produk_id]["qty_kredit_sum"])) {
                    $salesSums[$cabang_id][$produk_id]["qty_kredit_sum"] = 0;
                }

                $salesSums[$cabang_id][$produk_id]["qty_kredit_sum"] += $qty_kredit;


            }
        }
        // return $q;
        return $salesSums;

    }

    public function lookupPenjualanProdukHrAll()
    {
        $kolomSelected = $this->kolomSelecteds[1];
        $kolomOuts = $this->kolomOuts[1];

        $wheres = array();
        if (isset($this->condites)) {
            $wheres = $this->condites;
        }
        // else{
        //     $wheres = array(
        //         // "extern_id" => "34",
        //         // "dtime >" => "2020-02-01",
        //     );
        // }

        $qDatas = $this->getPenjualan($wheres)->result();


        $salesSums = array();
        $salesMains = array();
        if (sizeof($qDatas) > 0) {
            // arrPrint($qDatas);
            foreach ($qDatas as $qData) {
                $produk_id = $qData->extern_id;
                foreach ($kolomSelected as $kolomKey) {
                    $$kolomKey = $qData->$kolomKey;
                }
                foreach ($kolomOuts as $kolom) {
                    $datas[$kolom] = $qData->$kolom;
                }

                $salesMains[$produk_id] = $datas;
                // $salesSums[$cabang_id][$produk_id] = $datas;

                // cekHijau("$cabang_id /// $produk_id ==== $qty_kredit");

                if (!isset($salesSums[$produk_id]["qty_kredit_sum"])) {
                    $salesSums[$produk_id]["qty_kredit_sum"] = 0;
                }
                $salesSums[$produk_id]["qty_kredit_sum"] += $qty_kredit;


            }
            // arrPrintWebs($salesSums);
        }
        $salesDatas['mains'] = $salesMains;
        $salesDatas['sums'] = $salesSums;
        // return $q;
        // return $salesSums;
        return $salesDatas;
    }

    public function lookupReturnPenjualanProdukHrAll()
    {
        $kolomSelected = $this->kolomSelecteds[1];
        $kolomOuts = $this->kolomOuts[1];

        $wheres = array();
        if (isset($this->condites)) {
            $wheres = $this->condites;
        }
        // else{
        //     $wheres = array(
        //         // "extern_id" => "34",
        //         // "dtime >" => "2020-02-01",
        //     );
        // }

        $qDatas = $this->getReturnPenjualan($wheres)->result();


        $salesSums = array();
        $salesMains = array();
        if (sizeof($qDatas) > 0) {
            // arrPrint($qDatas);
            foreach ($qDatas as $qData) {
                $produk_id = $qData->extern_id;
                foreach ($kolomSelected as $kolomKey) {
                    $$kolomKey = $qData->$kolomKey;
                }
                foreach ($kolomOuts as $kolom) {
                    $datas[$kolom] = $qData->$kolom;
                }

                $salesMains[$produk_id] = $datas;
                // $salesSums[$cabang_id][$produk_id] = $datas;

                // cekHijau("$cabang_id /// $produk_id ==== $qty_kredit");

                if (!isset($salesSums[$produk_id]["qty_debet_sum"])) {
                    $salesSums[$produk_id]["qty_debet_sum"] = 0;
                }
                $salesSums[$produk_id]["qty_debet_sum"] += $qty_debet;


            }
            // arrPrintWebs($salesSums);
        }
        $salesDatas['mains'] = $salesMains;
        $salesDatas['sums'] = $salesSums;
        // return $q;
        // return $salesSums;
        return $salesDatas;
    }

    public function lookupPenjualanProdukHr()
    {
        $kolomSelected = $this->kolomSelecteds[1];
        $kolomOuts = $this->kolomOuts[1];

        $wheres = array();
        if (isset($this->condites)) {
            $wheres = $this->condites;
        }
        // else{
        //     $wheres = array(
        //         // "extern_id" => "34",
        //         // "dtime >" => "2020-02-01",
        //     );
        // }

        $qDatas = $this->getPenjualan($wheres)->result();


        $salesSums = array();
        $salesMains = array();
        if (sizeof($qDatas) > 0) {
            // arrPrint($qDatas);
            foreach ($qDatas as $qData) {
                $produk_id = $qData->extern_id;
                foreach ($kolomSelected as $kolomKey) {
                    $$kolomKey = $qData->$kolomKey;
                }
                foreach ($kolomOuts as $kolom) {
                    $datas[$kolom] = $qData->$kolom;
                }

                $salesMains[$cabang_id][$produk_id] = $datas;
                // $salesSums[$cabang_id][$produk_id] = $datas;

                // cekHijau("$cabang_id /// $produk_id ==== $qty_kredit");

                if (!isset($salesSums[$cabang_id][$produk_id]["qty_kredit_sum"])) {
                    $salesSums[$cabang_id][$produk_id]["qty_kredit_sum"] = 0;
                }
                $salesSums[$cabang_id][$produk_id]["qty_kredit_sum"] += $qty_kredit;


            }
            // arrPrintWebs($salesSums);
        }
        $salesDatas['mains'] = $salesMains;
        $salesDatas['sums'] = $salesSums;
        // return $q;
        // return $salesSums;
        return $salesDatas;
    }


    /* ===================================
     *
     * ------------------------------*/
    public function lookupBiPenjualanProduk()
    {

        $wheres = array(
            "jenis" => "bi_pembelian_produk",
            "trash" => "0",
        );
        $this->db->where($wheres);
        $vars = $this->db->get($this->tableName);

        return $vars;
    }
}