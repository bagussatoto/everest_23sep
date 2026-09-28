<?php

/**
 * Created by JetBrains PhpStorm.
 * User: azes
 * Date: 5/9/12
 * Time: 11:56 AM
 * To change this template use File | Settings | File Templates.
 */

//include_once "Bs_37.php";


class Bigdata
{
    protected $loginSessions;
    protected $jenisTr;
    protected $cabangId;
    protected $sortBy;
    protected $condites;
    protected $limit;

    public function getLimit()
    {
        return $this->limit;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    public function getCondites()
    {
        return $this->condites;
    }

    public function setCondites($condites)
    {
        $this->condites = $condites;
    }

    public function getJenisTr()
    {
        return $this->jenisTr;
    }

    public function setJenisTr($jenisTr)
    {
        $this->jenisTr = $jenisTr;
    }

    public function getCabangId()
    {
        return $this->cabangId;
    }

    public function setCabangId($cabangId)
    {
        $this->cabangId = $cabangId;
    }

    public function getSortBy()
    {
        return $this->sortBy;
    }

    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
    }

    public function getLoginSessions()
    {
        return $this->loginSessions;
    }

    public function setLoginSessions($loginSessions)
    {
        $this->loginSessions = $loginSessions;
    }

    public function __construct()
    {
        // parent::__construct();
        $this->CI =& get_instance();
        $this->ci = $this->CI;
    }

    public function callBdProdukAkunting()
    {
        $this->ci->load->helper("he_mass_table");
        $this->ci->load->model("Coms/ComRekeningPembantuProduk");
        $ps = new ComRekeningPembantuProduk();

        if (isset($this->condites)) {
            $condites = isset($this->condites) ? $this->condites : matiHere("condites sharap diset");
            $this->ci->db->where($condites);
        }
        else {
            $limit = isset($this->limit) ? $this->limit : matiHere( "tolong limit diSet karena condite juga belum ada ".__LINE__);

            $this->ci->db->limit($limit);
        }
        $jenisTr = isset($this->jenisTr) ? $this->jenisTr : matiHere("jenisTr harap diset dulu " . __METHOD__);
        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $ps->setSortBy($sortings);

        $ps->setJenisTr($jenisTr);
        $src = $ps->callMovementProduk("persediaan_produk");
        // $masterData = $src['data'];
        // $masterDataJml = $src['data_jml'];

        return $src;
    }

    public function callBdProdukNonAkunting()
    {
        // echo __METHOD__;
        /* -----------------------------------------------------------
        * produk spek
        * -----------------------------------------------------------*/
        $this->ci->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $prSpeks = $pr->callSpecs();
        // cekBiru($prSpeks);

        $this->ci->load->model("MdlTransaksi");
        $ps = new MdlTransaksi();

        $kolomYgGakPerlu = array(
            "indexing_sub_details",
            "indexing_items3_sum",
            "indexing_registry",
            "rsltItems2_revert",
            "rsltItems_revert",
            "tableIn_detail_values_rsltItems",
            "tableIn_detail_values_rsltItems2",
            "tableIn_detail_values2_sum",
            "main_add_values",
            "main_add_fields",
            "main_elements",
            "main_inputs",
            "main_inputs_orig",
            "receiptDetailFields",
            "receiptSumFields",
            "receiptDetailFields2",
            "receiptDetailSrcFields",
            "receiptSumFields2",
            "jurnal_index",
            "postProcessor",
            "preProcessor",
            "revert",
            "items_komposisi",
            "jurnalItems",
            "componentsBuilder",
            "items5_sum",
            "items6_sum",
            "items7_sum",
            "items8_sum",
            "items9_sum",
            "items10_sum",
            "items2",
            "items2_sum",
            "itemSrc",
            "itemSrc_sum",
            "items3",
            "items3_sum",
            "items4",
            "items4_sum",
            "items_noapprove",
            "rsltItems",
            "rsltItems2",
            "rsltItems3",
            "rsltItems3_sub",
        );
        $ps->setBlockFields($kolomYgGakPerlu);

        if(isset($this->limit)){
            $this->ci->db->limit($this->limit);
        }

        if (isset($this->sortBy)) {
            $this->ci->db->order_by($this->sortBy['kolom'], $this->sortBy['mode']);
        }

        $akumulasi_condites = $condites = array(
            // "transaksi.jenis" => $this->jenisTr,
            "transaksi.link_id" => 0,
        );
        $jenis_trs = array(
            "582so",
            "382so",
            "588so"
        );
        $this->ci->db->where_in("jenis",$jenis_trs);
        if(isset($this->condites)){
            $akumulasi_condites = $condites + $this->condites;
        }
        $this->ci->db->where($akumulasi_condites);
        $srcs = $ps->lookupTransaksiDataRegistries()->result();
        // showLast_query("kuning");
        // arrPrintPink($srcs);
        $trSpeks = array();
        foreach ($srcs as $src) {
            $trSpeks[$src->id] = $src;
        }
        // arrPrintWebs($trSpeks);
        foreach ($srcs as $src) {
            $trId = $src->id;
            $items = blobDecode($src->items);
            $mains = blobDecode($src->main);
            // arrPrintKuning($mains);
            // cekHijau($trId);
            foreach ($items as $produk_id => $item) {

                $dataBaru = $mains + $item + (array)(isset($prSpeks[$produk_id]) ? $prSpeks[$produk_id] : array()) + (array)$trSpeks[$trId];
                // $dataBaru = $item + (array)$prSpeks[$produk_id];
                // $dataBaru = $item + (array)$trSpeks[$trId];
                $masterData[] = $dataBaru;
            }
            // cekOrange("$trId");
            // cekBiru($items);
        }

        $vars = array();
        $vars['data'] = $masterData;

        return $vars;
    }
}
