<?php


class PreFifoAverageSuppliesProsesAssembly extends CI_Model
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


    public function __construct($resultParams = array())
    {
        parent::__construct();
        $this->resultParams = $resultParams;
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

    public function pair($master_id, $inParams)
    {

        if (!is_array($inParams)) {
            die("params required!");
        }
        if (sizeof($inParams) > 0) {
            $needlesPIDs = array();
            $p_ids = array();
            $cCode = "_TR_" . $inParams[0]["static"]["jenisTr"];

            foreach ($inParams as $sentParams) {
                foreach ($sentParams as $pSpec) {
                    $needlesPIDs[$pSpec['extern_id']] = $pSpec['produk_qty'];

                    $p_ids[] = $pSpec["extern_id"]; // produk_id, hasil produksi
                }
            }

            $tmpPk = array();
            $masterID = $master_id;
            $this->load->model("MdlTransaksi");
            $tr = New MdlTransaksi();
            $tr->addFilter("id='$masterID'");
            $trTmp = $tr->lookupAll()->result();
            $indexReg = blobDecode($trTmp[0]->indexing_registry);
            $regID = isset($indexReg['items_komposisi']) ? $indexReg['items_komposisi'] : 0;
            if($regID > 0){
                $rg = New MdlTransaksi();
                $rg->setFilters(array());
                $rg->addFilter("id='$regID'");
                $rgTmp = $rg->lookupRegistries()->result();
                $arrKomposisi = blobDecode($rgTmp[0]->values);
                if(sizeof($arrKomposisi) > 0){

                    foreach ($p_ids as $pID_needle){
                        if (isset($arrKomposisi[$pID_needle]) && (isset($arrKomposisi[$pID_needle]['produk']))) {
                            foreach ($arrKomposisi[$pID_needle]['produk'] as $ikSpec){
                                $tmpPk[] = $ikSpec;
                            }
                        }
                    }
                }
                else{
                    $this->load->model("Mdls/MdlProdukKomposisi");
                    $pk = new MdlProdukKomposisi();
                    $pk->addFilter("status='1'");
                    $pk->addFilter("jenis='produk'");
                    $pk->addFilter("produk_id in (" . implode(",", $p_ids) . ")");
                    $tmpPk = $pk->lookupAll()->result();
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

//            cekHitam("cetak komposisi produk");
//            arrPrint($tmpPk);
//            mati_disini();

            $b_ids = array();
            $needlesBahanIn = array();
            $needlesKomposisi = array();
            if (sizeof($tmpPk) > 0) {
//                arrPrint($tmpPk);
                foreach ($tmpPk as $pkSpec) {
                    if (!isset($needlesBahanIn[$pkSpec->produk_dasar_id])) {
                        $needlesBahanIn[$pkSpec->produk_dasar_id] = array(
                            "jml" => 0,
                            "produk_ids" => array(),
                        );
                    }
                    if (!isset($needlesKomposisi[$pkSpec->produk_id][$pkSpec->produk_dasar_id])) {
                        $needlesKomposisi[$pkSpec->produk_id][$pkSpec->produk_dasar_id] = 0;
                    }

                    $needlesBahanIn[$pkSpec->produk_dasar_id]["jml"] += ($pkSpec->jml * $needlesPIDs[$pkSpec->produk_id]);
                    $needlesBahanIn[$pkSpec->produk_dasar_id]["produk_ids"][] = $pkSpec->produk_id;
                    $needlesKomposisi[$pkSpec->produk_id][$pkSpec->produk_dasar_id] = $pkSpec->jml;

                    $b_ids[$pkSpec->produk_dasar_id] = $pkSpec->produk_dasar_id; // bahan_id, bahan baku produksi, supplies
                }
            }
            cekHere("cetak needles bahanIN");


            $this->load->model("Mdls/MdlFifoAverageSuppliesAssembly");
            $b = new MdlFifoAverageSuppliesAssembly();
            $b->addFilter("jenis='supplies_proses'");
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
                cekBiru("cetak patchers:");
                arrPrint($patchers);
                $this->result = $patchers;
            }
            else {
                cekBiru("TIDAK mendapatkan FIFO Average.....");
                $this->result = array();
            }
        }


//        cekMerah(":: cetak hasil pengambilan fifo... average");
//        arrPrint($patchers['items']);
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
}