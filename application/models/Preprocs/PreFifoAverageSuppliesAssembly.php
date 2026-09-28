<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreFifoAverageSuppliesAssembly extends CI_Model
{
    private $requiredParams = array(
        "id",
        "qty",
    );
    private $resultParams = array(//        "hpp" => "hpp",
    );
    private $inParams;
    private $outParams;
    private $result;
    protected $cCodeData;


    public function __construct($resultParams = array())
    {
        parent::__construct();
        $this->resultParams = $resultParams;
    }

    public function getCCodeData()
    {
        return $this->cCodeData;
    }

    public function setCCodeData($cCodeData)
    {
        $this->cCodeData = $cCodeData;
    }

    //<editor-fold desc="getter-setter">

    public function getRequiredParams()
    {
        return $this->requiredParams;
    }

    public function setRequiredParams($requiredParams)
    {
        $this->requiredParams = $requiredParams;
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

    public function getResultParams()
    {
        return $this->resultParams;
    }

    //</editor-fold>

    public function setResultParams($resultParams)
    {
        $this->resultParams = $resultParams;
    }

    public function pairOLD($master_id, $inParams)
    {
        cekHere("cetak inParams");
        arrPrint($inParams);

        if (!is_array($inParams)) {
            die("params required!");
        }
        if (sizeof($inParams) > 0) {
            $needlesPIDs = array();
            $p_ids = array();
            foreach ($inParams as $sentParams) {
                foreach ($sentParams as $pSpec) {
                    $needlesPIDs[$pSpec['extern_id']] = $pSpec['produk_qty'];

                    $p_ids[] = $pSpec["extern_id"]; // produk_id, hasil produksi
                }
            }

            cekHere("cetak needles produk");
            arrPrint($needlesPIDs);

            $this->load->model("Mdls/MdlProdukKomposisi");
            $pk = new MdlProdukKomposisi();
            $pk->addFilter("status='1'");
            $pk->addFilter("produk_id in (" . implode(",", $p_ids) . ")");
            $tmpPk = $pk->lookupAll()->result();
//arrPrint($tmpPk);
//mati_disini();
            $b_ids = array();
            $needles = array();
            $needlesKomposisi = array();
            if (sizeof($tmpPk) > 0) {
                foreach ($tmpPk as $pkSpec) {
                    if (!isset($needles[$pkSpec->produk_dasar_id])) {
                        $needles[$pkSpec->produk_dasar_id] = 0;
                    }
                    if (!isset($needlesKomposisi[$pkSpec->produk_id][$pkSpec->produk_dasar_id])) {
                        $needlesKomposisi[$pkSpec->produk_id][$pkSpec->produk_dasar_id] = 0;
                    }
                    $needles[$pkSpec->produk_dasar_id] += ($pkSpec->jml * $needlesPIDs[$pkSpec->produk_id]);
                    $needlesKomposisi[$pkSpec->produk_id][$pkSpec->produk_dasar_id] = $pkSpec->jml;

                    $b_ids[$pkSpec->produk_dasar_id] = $pkSpec->produk_dasar_id; // bahan_id, bahan baku produksi, supplies
                }
            }
            cekHere("cetak needles bahan");
            arrPrint($needles);
            cekHere("cetak needles komposisi");
            arrPrint($needlesKomposisi);


            $this->load->model("Mdls/MdlFifoAverageSuppliesAssembly");
            $b = new MdlFifoAverageSuppliesAssembly();
            $b->addFilter("jenis='supplies'");
            $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
            $b->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
            $b->addFilter("produk_id in (" . implode(",", $b_ids) . ")"); // kumpulan bahan id
            $tmp = $b->lookupAll()->result();

            $updatePairs = array();
            if (sizeof($tmp) > 0) {
                $patchers = array();
                foreach ($tmp as $row) {
                    foreach ($this->resultParams['items2_sum'] as $key => $val) {
                        $patchers['items2_sum'][$row->produk_id][$key] = $row->$val; // build patchers bahan
                    }

                    //==update yg sesuai
                    if (array_key_exists($row->produk_id, $needles)) {
                        $updatePairs[] = array(
                            "id" => $row->id,
                            "produk_id" => $row->produk_id,
                            "jml" => ($row->jml - $needles[$row->produk_id]),
                            "jml_nilai" => ($row->jml_nilai - ($row->hpp * $needles[$row->produk_id])),
                        );
                    }
                }

                if (sizeof($needlesPIDs) > 0) {
                    foreach ($needlesPIDs as $pID => $pQty) {
                        foreach ($needlesKomposisi[$pID] as $bID => $bQty) {
                            foreach ($this->resultParams['items'] as $key => $val) {
                                if (!isset($patchers['items'][$pID][$key])) {
                                    $patchers['items'][$pID][$key] = 0;
                                }
                                $patchers['items'][$pID][$key] += (($patchers['items2_sum'][$bID][$val] * $bQty) * $pQty); // build patchers produk
                            }
                        }
                    }
                }


                if (sizeof($updatePairs) > 0) {
                    foreach ($updatePairs as $upSpec) {
                        $updateData = $upSpec;
                        unset($updateData["id"]);
                        $b = new MdlFifoAverageSuppliesAssembly();
                        $b->updateData(array("id" => $upSpec['id']), $updateData);
                        cekMerah($this->db->last_query());
                    }
                }
                cekBiru("cetak patchers:");
                arrPrint($patchers);
                $this->result = $patchers;
            }
            else {
                $this->result = array();
            }
        }


        mati_disini(get_class($this));
        if (sizeof($updatePairs) > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public function pair($master_id, $inParams)
    {

        if (!is_array($inParams)) {
            die("params required!");
        }

        if (sizeof($inParams) > 0) {
            $needlesPIDs = array();
            $p_ids = array();
            $cCode = "_TR_" . $inParams[0]["static"]["jenisTr"];
//arrPrintWebs($inParams);
            foreach ($inParams as $sentParams) {
                foreach ($sentParams as $pSpec) {
                    $needlesPIDs[$pSpec['extern_id']] = $pSpec['produk_qty'];

                    $p_ids[] = $pSpec["extern_id"]; // produk_id, hasil produksi
                }
            }
            cekHitam(":: cetak komposisi :: $master_id");

//            arrPrintPink($this->cCodeData);
//            mati_disini(__LINE__);


            $tmpPk = array();
            $masterID = $master_id;
            $this->load->model("MdlTransaksi");
            $tr = New MdlTransaksi();
            $tr->addFilter("id='$masterID'");
            $trTmp = $tr->lookupAll()->result();
            showLast_query("biru");
//            $indexReg = blobDecode($trTmp[0]->indexing_registry);
//            $regID = isset($indexReg['items_komposisi']) ? $indexReg['items_komposisi'] : 0;
            if (sizeof($trTmp) > 0) {
                $rg = New MdlTransaksi();
                $rg->setFilters(array());
                $rg->addFilter("transaksi_id='$masterID'");
                $rgTmp = $rg->lookupDataRegistries()->result();
                showLast_query("biru");
//                arrPrintKuning($rgTmp);
                $arrKomposisi = blobDecode($rgTmp[0]->items_komposisi);
                if (sizeof($arrKomposisi) > 0) {

                    foreach ($p_ids as $pID_needle) {
                        if (isset($arrKomposisi[$pID_needle]) && (isset($arrKomposisi[$pID_needle]['produk']))) {
                            foreach ($arrKomposisi[$pID_needle]['produk'] as $ikSpec) {
//                                arrPrintHijau($ikSpec);
//                                $ikSpec["produk_id_hasil"] = $pID_needle;
                                $ikSpec->produk_id_hasil = $pID_needle;
                                $tmpPk[] = $ikSpec;
                            }
                        }
                    }
                }
                else {
                    $this->load->model("Mdls/MdlProdukKomposisi");
                    $pk = new MdlProdukKomposisi();
                    $pk->addFilter("status='1'");
                    $pk->addFilter("jenis='produk'");
                    $pk->addFilter("produk_id in (" . implode(",", $p_ids) . ")");
                    $tmpPk = $pk->lookupAll()->result();
                }
            }
            else {
//                cekPink(":: komposisi dari data komposisi ::");
                $this->load->model("Mdls/MdlProdukKomposisi");
                $pk = new MdlProdukKomposisi();
                $pk->addFilter("status='1'");
                $pk->addFilter("jenis='produk'");
                $pk->addFilter("produk_id in (" . implode(",", $p_ids) . ")");
                $tmpPk = $pk->lookupAll()->result();
            }
//            arrPrint($arrKomposisi);
//            arrPrint($tmpPk);
//            mati_disini(":: $masterID :: " . __LINE__);

            $b_ids = array();
            $needlesBahanIn = array();
            $needlesKomposisi = array();
            if (sizeof($tmpPk) > 0) {
                foreach ($tmpPk as $pkSpecs) {
                    $pkSpec = (object)$pkSpecs;
//                    arrPrintHijau($pkSpec);
                    if (!isset($needlesBahanIn[$pkSpec->produk_dasar_id])) {
                        $needlesBahanIn[$pkSpec->produk_dasar_id] = array(
                            "jml" => 0,
                            "produk_ids" => array(),
                        );
                    }
                    if (!isset($needlesKomposisi[$pkSpec->produk_id_hasil][$pkSpec->produk_dasar_id])) {
                        $needlesKomposisi[$pkSpec->produk_id_hasil][$pkSpec->produk_dasar_id] = 0;
                    }

                    $needlesBahanIn[$pkSpec->produk_dasar_id]["jml"] += ($pkSpec->jml * $needlesPIDs[$pkSpec->produk_id_hasil]);
                    $needlesBahanIn[$pkSpec->produk_dasar_id]["produk_ids"][] = $pkSpec->produk_id_hasil;
                    $needlesKomposisi[$pkSpec->produk_id_hasil][$pkSpec->produk_dasar_id] = $pkSpec->jml;

                    $b_ids[$pkSpec->produk_dasar_id] = $pkSpec->produk_dasar_id; // bahan_id, bahan baku produksi, supplies
                }
            }
            cekHere("cetak needles bahanIN");
//arrPrintWebs($needlesBahanIn);

            $this->load->model("Mdls/MdlFifoAverageSuppliesAssembly");
            $b = new MdlFifoAverageSuppliesAssembly();
            $b->addFilter("jenis='supplies'");
            $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
            $b->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
            $b->addFilter("produk_id in (" . implode(",", $b_ids) . ")"); // kumpulan bahan id
            $tmp = $b->lookupAll()->result();
            cekBiru($this->db->last_query());

            $updatePairs = array();
            if (sizeof($tmp) > 0) {
//                cekBiru("mendapatkan FIFO Average.....");
                $patchers = array();
                foreach ($tmp as $row) {
                    if (!isset($patchers["items2_sum"][$row->produk_id]["hpp"])) {
                        $patchers["items2_sum"][$row->produk_id]["hpp"] = 0;
                    }
                    if (!isset($arrHppAvg[$row->produk_id])) {
                        $arrHppAvg[$row->produk_id] = 0;
                    }
                    $patchers["items2_sum"][$row->produk_id]["hpp"] = $row->hpp; // build patchers bahan
                    $patchers["items2_sum"][$row->produk_id]["sub_hpp"] = ($row->hpp * $needlesBahanIn[$row->produk_id]["jml"]); // build patchers bahan
                    $arrHppAvg[$row->produk_id] = $row->hpp;

//                    if (isset($needlesBahanIn[$row->produk_id]["produk_ids"])) {
//                        foreach ($needlesBahanIn[$row->produk_id]["produk_ids"] as $pID) {
//                            if (!isset($patchers['items'][$pID])) {
//                                $patchers['items'][$pID] = 0;
//                            }
//                            $patchers['items'][$pID] += $patchers['items2_sum'][$row->produk_id];
//                        }
//                    }

                    //==update yg sesuai
                    if (array_key_exists($row->produk_id, $needlesBahanIn)) {
                        $updatePairs[] = array(
                            "id" => $row->id,
                            "produk_id" => $row->produk_id,
                            "jml" => ($row->jml - $needlesBahanIn[$row->produk_id]["jml"]),
                            "jml_nilai" => ($row->jml_nilai - ($row->hpp * $needlesBahanIn[$row->produk_id]["jml"])),

                            "ppn_in_nilai" => ($row->ppn_in_nilai - ($row->ppn_in * $needlesBahanIn[$row->produk_id]["jml"])),
                        );
                    }
                }
//                arrPrint($arrHppAvg);
                if (sizeof($needlesPIDs) > 0) {
                    foreach ($needlesPIDs as $pID => $pQty) {
                        foreach ($needlesKomposisi[$pID] as $bID => $bQty) {
                            if (!isset($patchers['items'][$pID]["hpp"])) {
                                $patchers['items'][$pID]["hpp"] = 0;
                            }
                            if (!isset($patchers['items'][$pID]["sub_hpp"])) {
                                $patchers['items'][$pID]["sub_hpp"] = 0;
                            }
                            $patchers['items'][$pID]["hpp"] += (($arrHppAvg[$bID] * $bQty)); // build patchers produk
                            $patchers['items'][$pID]["sub_hpp"] += (($arrHppAvg[$bID] * $bQty) * $pQty); // build patchers produk

                        }
                    }
                }


                if (sizeof($updatePairs) > 0) {
                    foreach ($updatePairs as $upSpec) {
                        $updateData = $upSpec;
                        unset($updateData["id"]);
                        $b = new MdlFifoAverageSuppliesAssembly();
                        $b->updateData(array("id" => $upSpec['id']), $updateData);
                        cekMerah($this->db->last_query());
                    }
                }
//                cekBiru("cetak patchers:");
//                arrPrint($patchers);
                $this->result = $patchers;
            }
            else {
                cekBiru("TIDAK mendapatkan FIFO Average.....");
                $this->result = array();
            }
        }


//        mati_disini(get_class($this));
        if (sizeof($updatePairs) > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public function exec()
    {
        return $this->result;
    }


    public function pair__manufactur($master_id, $inParams)
    {

        if (!is_array($inParams)) {
            die("params required!");
        }

        if (sizeof($inParams) > 0) {
            $needlesPIDs = array();
            $p_ids = array();
            $cCode = "_TR_" . $inParams[0]["static"]["jenisTr"];
//arrPrintWebs($inParams);
            foreach ($inParams as $sentParams) {
                foreach ($sentParams as $pSpec) {
                    $needlesPIDs[$pSpec['extern_id']] = $pSpec['produk_qty'];

                    $p_ids[] = $pSpec["extern_id"]; // produk_id, hasil produksi
                }
            }
            cekHitam(":: cetak komposisi :: $master_id");

            arrPrintPink($this->cCodeData);
            mati_disini(__LINE__);


            $tmpPk = array();
            $masterID = $master_id;
//            $this->load->model("MdlTransaksi");
//            $tr = New MdlTransaksi();
//            $tr->addFilter("id='$masterID'");
//            $trTmp = $tr->lookupAll()->result();
//            showLast_query("biru");
//            $indexReg = blobDecode($trTmp[0]->indexing_registry);
//            $regID = isset($indexReg['items_komposisi']) ? $indexReg['items_komposisi'] : 0;
//            if (sizeof($trTmp) > 0) {
//                $rg = New MdlTransaksi();
//                $rg->setFilters(array());
//                $rg->addFilter("transaksi_id='$masterID'");
//                $rgTmp = $rg->lookupDataRegistries()->result();
//                showLast_query("biru");
////                arrPrintKuning($rgTmp);
//                $arrKomposisi = blobDecode($rgTmp[0]->items_komposisi);
//                if (sizeof($arrKomposisi) > 0) {
//                    foreach ($p_ids as $pID_needle) {
//                        if (isset($arrKomposisi[$pID_needle]) && (isset($arrKomposisi[$pID_needle]['produk']))) {
//                            foreach ($arrKomposisi[$pID_needle]['produk'] as $ikSpec) {
//                                $ikSpec["produk_id_hasil"] = $pID_needle;
//                                $tmpPk[] = $ikSpec;
//                            }
//                        }
//                    }
//                }
//                else {
//                    $this->load->model("Mdls/MdlProdukKomposisi");
//                    $pk = new MdlProdukKomposisi();
//                    $pk->addFilter("status='1'");
//                    $pk->addFilter("jenis='produk'");
//                    $pk->addFilter("produk_id in (" . implode(",", $p_ids) . ")");
//                    $tmpPk = $pk->lookupAll()->result();
//                }
//            }
//            else {
////                cekPink(":: komposisi dari data komposisi ::");
//                $this->load->model("Mdls/MdlProdukKomposisi");
//                $pk = new MdlProdukKomposisi();
//                $pk->addFilter("status='1'");
//                $pk->addFilter("jenis='produk'");
//                $pk->addFilter("produk_id in (" . implode(",", $p_ids) . ")");
//                $tmpPk = $pk->lookupAll()->result();
//            }
//            arrPrint($arrKomposisi);
            arrPrint($tmpPk);
            mati_disini(":: $masterID ::");

            $b_ids = array();
            $needlesBahanIn = array();
            $needlesKomposisi = array();
            if (sizeof($tmpPk) > 0) {
                foreach ($tmpPk as $pkSpecs) {
                    $pkSpec = (object)$pkSpecs;
                    arrPrintHijau($pkSpec);
                    if (!isset($needlesBahanIn[$pkSpec->produk_dasar_id])) {
                        $needlesBahanIn[$pkSpec->produk_dasar_id] = array(
                            "jml" => 0,
                            "produk_ids" => array(),
                        );
                    }
                    if (!isset($needlesKomposisi[$pkSpec->produk_id_hasil][$pkSpec->produk_dasar_id])) {
                        $needlesKomposisi[$pkSpec->produk_id_hasil][$pkSpec->produk_dasar_id] = 0;
                    }

                    $needlesBahanIn[$pkSpec->produk_dasar_id]["jml"] += ($pkSpec->jml * $needlesPIDs[$pkSpec->produk_id_hasil]);
                    $needlesBahanIn[$pkSpec->produk_dasar_id]["produk_ids"][] = $pkSpec->produk_id_hasil;
                    $needlesKomposisi[$pkSpec->produk_id_hasil][$pkSpec->produk_dasar_id] = $pkSpec->jml;

                    $b_ids[$pkSpec->produk_dasar_id] = $pkSpec->produk_dasar_id; // bahan_id, bahan baku produksi, supplies
                }
            }
            cekHere("cetak needles bahanIN");
//arrPrintWebs($needlesBahanIn);


            $this->load->model("Mdls/MdlFifoAverageSuppliesAssembly");
            $b = new MdlFifoAverageSuppliesAssembly();
            $b->addFilter("jenis='supplies'");
            $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
            $b->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
            $b->addFilter("produk_id in (" . implode(",", $b_ids) . ")"); // kumpulan bahan id
            $tmp = $b->lookupAll()->result();
            cekBiru($this->db->last_query());

            $updatePairs = array();
            if (sizeof($tmp) > 0) {
//                cekBiru("mendapatkan FIFO Average.....");
                $patchers = array();
                foreach ($tmp as $row) {
                    if (!isset($patchers["items2_sum"][$row->produk_id]["hpp"])) {
                        $patchers["items2_sum"][$row->produk_id]["hpp"] = 0;
                    }
                    if (!isset($arrHppAvg[$row->produk_id])) {
                        $arrHppAvg[$row->produk_id] = 0;
                    }
                    $patchers["items2_sum"][$row->produk_id]["hpp"] = $row->hpp; // build patchers bahan
                    $patchers["items2_sum"][$row->produk_id]["sub_hpp"] = ($row->hpp * $needlesBahanIn[$row->produk_id]["jml"]); // build patchers bahan
                    $arrHppAvg[$row->produk_id] = $row->hpp;

//                    if (isset($needlesBahanIn[$row->produk_id]["produk_ids"])) {
//                        foreach ($needlesBahanIn[$row->produk_id]["produk_ids"] as $pID) {
//                            if (!isset($patchers['items'][$pID])) {
//                                $patchers['items'][$pID] = 0;
//                            }
//                            $patchers['items'][$pID] += $patchers['items2_sum'][$row->produk_id];
//                        }
//                    }

                    //==update yg sesuai
                    if (array_key_exists($row->produk_id, $needlesBahanIn)) {
                        $updatePairs[] = array(
                            "id" => $row->id,
                            "produk_id" => $row->produk_id,
                            "jml" => ($row->jml - $needlesBahanIn[$row->produk_id]["jml"]),
                            "jml_nilai" => ($row->jml_nilai - ($row->hpp * $needlesBahanIn[$row->produk_id]["jml"])),

                            "ppn_in_nilai" => ($row->ppn_in_nilai - ($row->ppn_in * $needlesBahanIn[$row->produk_id]["jml"])),
                        );
                    }
                }
//                arrPrint($arrHppAvg);
                if (sizeof($needlesPIDs) > 0) {
                    foreach ($needlesPIDs as $pID => $pQty) {
                        foreach ($needlesKomposisi[$pID] as $bID => $bQty) {
                            if (!isset($patchers['items'][$pID]["hpp"])) {
                                $patchers['items'][$pID]["hpp"] = 0;
                            }
                            if (!isset($patchers['items'][$pID]["sub_hpp"])) {
                                $patchers['items'][$pID]["sub_hpp"] = 0;
                            }
                            $patchers['items'][$pID]["hpp"] += (($arrHppAvg[$bID] * $bQty)); // build patchers produk
                            $patchers['items'][$pID]["sub_hpp"] += (($arrHppAvg[$bID] * $bQty) * $pQty); // build patchers produk

                        }
                    }
                }


                if (sizeof($updatePairs) > 0) {
                    foreach ($updatePairs as $upSpec) {
                        $updateData = $upSpec;
                        unset($updateData["id"]);
                        $b = new MdlFifoAverageSuppliesAssembly();
                        $b->updateData(array("id" => $upSpec['id']), $updateData);
                        cekMerah($this->db->last_query());
                    }
                }
//                cekBiru("cetak patchers:");
//                arrPrint($patchers);
                $this->result = $patchers;
            }
            else {
                cekBiru("TIDAK mendapatkan FIFO Average.....");
                $this->result = array();
            }
        }


//        mati_disini(get_class($this));
        if (sizeof($updatePairs) > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    // dibawah ini dipakai oleh manufactur per-fase
    public function pair_NEW($master_id, $inParams)
    {
        $this->load->model("Mdls/MdlFifoAverageSupplies");

        if (!is_array($inParams)) {
            die("params required!");
        }
        if (sizeof($inParams) > 0) {
            $needles = array();
//            $needlesPIDs = array();

//            arrPrintPink($inParams);
//            mati_disini();
            $ids = array();
            $tmp = array();
            foreach ($inParams as $sentParams) {
                foreach ($sentParams as $pSpec) {
                    $needles[$pSpec['extern_id']] = $pSpec['produk_qty'];
//                    $needlesPIDs[$pSpec['extern_id']] = $pSpec['produk_ids'];

                    $ids[] = $pSpec["extern_id"];


                    $b = new MdlFifoAverageSupplies();
                    $b->addFilter("jenis='supplies'");
                    $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
                    $b->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
                    $b->addFilter("produk_id='" . $pSpec["extern_id"] . "'");
//                    $tmp = $b->lookupAll()->result();
                    $localFilters = array();
                    if (sizeof($b->getfilters()) > 0) {
                        foreach ($b->getfilters() as $f) {
                            $tmpArr = explode("=", $f);
                            $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");
                        }
                    }
                    $query = $this->db->select()
                        ->from($b->getTableName())
                        ->where($localFilters)
                        ->limit(1)
                        ->get_compiled_select();
                    $subtmp = $this->db->query("{$query} FOR UPDATE")->row_array();
                    $tmp[] = (object)$subtmp;
                }
            }
//arrPrint($tmp);
//arrPrintPink($needles);
//if(isset($needles[0])){
//    arrPrintPink($inParams);
//    mati_disini();
//}

            $updatePairs = array();
            if (sizeof($tmp) > 0) {
                $patchers = array();
                foreach ($tmp as $row) {

                    foreach ($this->resultParams as $gateName => $paramSpec) {
                        foreach ($paramSpec as $key => $val) {
                            $patchers[$gateName][$row->produk_id][$key] = $row->$val;
                        }
                    }
                    arrPrintKuning($row);
//                    mati_disini();


                    //==update yg sesuai
                    if (array_key_exists($row->produk_id, $needles)) {
                        $updatePairs[] = array(
                            "id" => $row->id,
                            "produk_id" => $row->produk_id,
                            "jml" => ($row->jml - $needles[$row->produk_id]),
                            "jml_nilai" => ($row->jml_nilai - ($row->hpp * $needles[$row->produk_id])),
                            "ppn_in_nilai" => ($row->ppn_in_nilai - ($row->ppn_in * $needles[$row->produk_id])),
                        );
                    }
                }
                arrPrintWebs($updatePairs);
//                mati_disini("PID: ".$row->produk_id);
                if (sizeof($updatePairs) > 0) {
                    foreach ($updatePairs as $upSpec) {
                        $updateData = $upSpec;
                        unset($updateData["id"]);
                        $b = new MdlFifoAverageSupplies();
                        $b->updateData(array("id" => $upSpec['id']), $updateData);
                        cekMerah($this->db->last_query());
                    }
                }
                $this->result = $patchers;
            }
            else {
                $this->result = array();
            }
        }
        if (sizeof($updatePairs) > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public function exec_NEW()
    {
        return $this->result;
    }
}