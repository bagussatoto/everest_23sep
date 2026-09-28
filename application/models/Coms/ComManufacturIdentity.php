<?php


class ComManufacturIdentity extends MdlMother
{

    protected $filters = array();
    private $tableName;
    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $outFields = array( // dari tabel cache
        "id",
        "jenis",
        "target_jenis",
        "reference_jenis",
        "transaksi_id",
        "transaksi_no",
        "extern_id",
        "extern_nama",
        "nomer",
        "label",
        "amount",
        "used",
        "remain",
        "cabang_id",
        "cabang_nama",
        "oleh_id",
        "oleh_nama",
        "dtime",
        "fulldate",
        "produk_id",
        "produk_nama",
        "produk_fase_id",
        "produk_fase_nama",
        "qty_fase",
        "harga_fase",
        "fase_id",
        "fase_nama",
        "toko_id",
        "saldo_qty",
        "blobData",
        "kode_produksi",
    );
    private $koloms = array(
        "id",
        "jenis",
        "target_jenis",
        "reference_jenis",
        "transaksi_id",
        "extern_id",
        "extern_nama",
        "nomer",
        "label",
        "amount",
        "used",
        "remain",
        "cabang_id",
        "cabang_nama",
        "oleh_id",
        "oleh_nama",
        "dtime",
        "fulldate",
    );


    public function __construct()
    {
        $this->tableName = "manufactur_identity";
    }


    public function pair($inParams)
    {

        $this->inParams = $inParams;
        $srcGate = "items2_sum";//ini manual dulu ya
        // arrprint($this->inParams);
        //
        // matiHere();
        $srcGatebahanBaku = "rsltItems";
        $srcGateKomposisi = "items_komposisi";

        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            foreach ($this->inParams as $inSpec) {

                if (isset($inSpec['static']) && sizeof($inSpec['static']) > 0) {
                    $lCounter++;
                    $prev = $this->cekPreValue(
                        $inSpec['static']['fase_id'],
                        $inSpec['static']['produk_id'],
                        $inSpec['static']['cabang_id'],
                        $srcGate,
                        $inSpec['static']['jenis_tr'],
                        $inSpec['static']['kode_produksi']
                    );
                    $cCode = "_TR_" . $inSpec['static']['jenis_tr'];
                    $arrBahanBaku = $_SESSION[$cCode][$srcGatebahanBaku];
                    $arrKomposisi = $_SESSION[$cCode][$srcGateKomposisi];
                    $arrSumBahanBaku = $_SESSION[$cCode][$srcGate];

                    //tambahanparam
                    if (count($prev) > 0) {
                        //update data fase prev
                        arrPrintWebs($prev);
                        $this->load->model("Mdls/MdlManufacturIdentity");
                        $mm = new MdlManufacturIdentity();
                        $mm->setFilters(array());
                        $where = array("id" => $prev["id"]);
                        $updateVal = array(
                            "saldo_qty" => $prev["new_saldo"],
                        );
                        $mm->updateData($where, $updateVal) or matiHere("Gagal memperharui data QR produk, silahkan relogin dan coba kembali. Jika masih gagal silahkan hubungi admin untuk melakukan pengecekan. Terima kasih");
//cekLime($this->db->last_query());
//mati_disini(__LINE__);
                        //builddata fase prev + curent fase
                        $decodePrevValues = blobDecode($prev["blobData"]);
                        // master
                        $decodePrevValues[$inSpec['static']["fase_id"]]["master"] = $inSpec['static'];
                        // detail bahan baku
                        $decodePrevValues[$inSpec['static']["fase_id"]]["bahan_baku"] = $arrBahanBaku;
                        $decodePrevValues[$inSpec['static']["fase_id"]]["komposisi"] = $arrKomposisi;
                        $decodePrevValues[$inSpec['static']["fase_id"]]["sum_bahan_baku"] = $arrSumBahanBaku;

//arrPrintWebs($decodePrevValues);
                        $inSpec['static']["blobData"] = blobEncode($decodePrevValues);

                        // $decodePrevValues[]=$inSpec['static'];
                        // arrprint($decodePrevValues);
                        // arrprintWebs($curentData);
                        // matiHEre(__LINE__);
                    }
                    else {
                        // $this->outParams[$lCounter]["blobData"] = blobEncode($this->outParams[$lCounter]["fase_id"]);

                        // master
                        $prevData[$inSpec['static']["fase_id"]]["master"] = $inSpec['static'];
                        // detail bahan baku
                        $prevData[$inSpec['static']["fase_id"]]["bahan_baku"] = $arrBahanBaku;
                        $prevData[$inSpec['static']["fase_id"]]["komposisi"] = $arrKomposisi;
                        $prevData[$inSpec['static']["fase_id"]]["sum_bahan_baku"] = $arrSumBahanBaku;


//                        arrPrintWebs($prevData);
                        $inSpec['static']["blobData"] = blobEncode($prevData);
                    }

//                    cekHitam($this->db->last_query());
                    foreach ($inSpec['static'] as $key => $val) {
                        if (in_array($key, $this->outFields)) {
                            $this->outParams[$lCounter][$key] = $val;
                        }
                    }

                    if (sizeof($this->outParams) > 0) {
                        $result = true;
                    }
                    else {
                        $result = false;
                    }
                }
            }
        }
//arrPrintKuning($this->outParams);
//mati_disini(__LINE__);
// arrPrint(blobDecode($this->outParams[1]["blobData"]));
// mati_disini(__LINE__." || ".__FUNCTION__);


        return $result;
    }

    // private function cekPreValue($targetJenis, $transaksiID, $label, $cabangID)
    // {
    //     $this->load->model("Mdls/MdlCreditNote");
    //     $tr = new MdlCreditNote();
    //     $tr->setFilters(array());
    //     $tr->addFilter("label='$label'");
    //     $tr->addFilter("cabang_id='$cabangID'");
    //     $tr->addFilter("target_jenis='$targetJenis'");
    //     $tr->addFilter("transaksi_id='$transaksiID'");
    //     $tmpR = $tr->lookupAll()->result();
    //     cekHitam($this->db->last_query() . " # " . sizeof($tmpR));
    //     if (sizeof($tmpR) > 0) {
    //         foreach ($tmpR as $row) {
    //             $result = array(
    //                 "id" => $row->id,
    //                 "amount" => $row->amount,
    //                 "used" => $row->used,
    //                 "remain" => $row->remain,
    //             );
    //         }
    //     }
    //     else {
    //         $result = array(
    //             "id" => 0,
    //             "amount" => 0,
    //             "used" => 0,
    //             "remain" => 0,
    //         );
    //     }
    //
    //     return $result;
    // }

    public function addFilter($f)
    {
        $this->filters[] = $f;
    }

    public function exec()
    {

        $this->load->model("Mdls/MdlManufacturIdentity");
        if (sizeof($this->outParams) > 0) {
            foreach ($this->outParams as $cnt => $oSpec) {
                // arrPrint($oSpec);
                $tr = new MdlManufacturIdentity();
                $tr->addData($oSpec) or mati_disini("gagal mencatat identitas manufactur");
                // ceklime($this->db->last_query());

//                 foreach ($oSpec as $mode => $pSpec) {
//                     switch ($mode) {
//                         case "update":
//                             $tr->updateData($oSpec['where'], $oSpec['update']) or die("can not update CreditNote source");
//                             break;
//                         case "insert":
//                             $tr->addData($pSpec) or die("can not add CreditNote source");
//                             break;
// //                        default:
// //                            break;
//                     }
//                     cekHitam($this->db->last_query());
//                 }
            }

            // mati_disini("exec ATAS");
            return true;
        }
        else {
            cekUngu("exec tidak ngapa2in");
            return true;
        }

    }

    public function cekPreValue($curentFaseID, $produkID, $cabangID, $srcGate, $jenisTr, $kode_produksi)
    {
        $prevFase = $curentFaseID - 1;
        $this->load->model("Mdls/MdlManufacturIdentity");
        $cCode = $cCode = "_TR_" . $jenisTr;
        $prevDataSrc = $_SESSION[$cCode][$srcGate];

        $result = array();
        if ($prevFase > 0) {
            $this->addFilter("produk_id='$produkID'");
            $this->addFilter("fase_id='$prevFase'");
            $this->addFilter("cabang_id='$cabangID'");
//            $this->addFilter("qty_fase >'0'");
            $this->addFilter("saldo_qty >'0'");
            $this->addFilter("kode_produksi='$kode_produksi'");
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
            if (count($tmp) > 0) {
                foreach ($tmp as $temp) {
                    // arrPrint($temp);
                    $produk_faseID = $temp->produk_fase_id;
                    if (isset($prevDataSrc[$produk_faseID])) {
                        $result = array(
                            "id" => $temp->id,
                            "blobData" => $temp->blobData,
                            "saldo_qty" => $temp->saldo_qty,
                            "dipakai_qty" => $prevDataSrc[$produk_faseID]["jml"],
                            "new_saldo" => $temp->saldo_qty - $prevDataSrc[$produk_faseID]["jml"],
                        );
                    }
                }
            }

        }
        else {
            cekHitam(" fase 1 kosong");

        }

        // matiHEre(__LINE__);
        return $result;


    }


}