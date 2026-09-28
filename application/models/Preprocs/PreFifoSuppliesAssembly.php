<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreFifoSuppliesAssembly extends CI_Model
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
    private $masterID;
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

    public function getMasterID()
    {
        return $this->masterID;
    }

    public function setMasterID($masterID)
    {
        $this->masterID = $masterID;
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

    public function pairOLD2($master_id, $inParams)
    {
//        cekHere("cetak inParams");
//        arrPrint($inParams);
//        cekHere("selesai cetak inParams");
//
        if (!is_array($inParams)) {
            die("params required!");
        }
        if (sizeof($inParams) > 0) {
            $needlesPIDs = array();
            $needlesPIDNama = array();
            $p_ids = array();
            foreach ($inParams as $sentParams) {
                foreach ($sentParams as $pSpec) {
                    $needlesPIDs[$pSpec['extern_id']] = $pSpec['produk_qty'];
                    $needlesPIDNama[$pSpec['extern_id']] = $pSpec['extern_nama'];

                    $p_ids[] = $pSpec["extern_id"]; // produk_id, result produksi
                }
            }
//            cekHere("cetak needles produk");
//            arrPrint($needlesPIDs);
//            arrPrint($p_ids);
//            cekHere("selesai cetak needles produk");


            $this->load->model("Mdls/MdlProdukKomposisi");
            $pk = new MdlProdukKomposisi();
            $pk->addFilter("status='1'");
            $pk->addFilter("produk_id in (" . implode(",", $p_ids) . ")");
            $tmpPk = $pk->lookupAll()->result();
//cekHere("cetak komposisi");
//arrPrint($tmpPk);
//cekHere("selesai cetak komposisi");
//
            $b_ids = array();
            $needlesBahanIn = array();
            $needlesKomposisi = array();
            if (sizeof($tmpPk) > 0) {
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

                    $needlesBahanIn[$pkSpec->produk_dasar_id]["nama"] = $pkSpec->produk_dasar_nama;
                    $needlesBahanIn[$pkSpec->produk_dasar_id]["jml"] += ($pkSpec->jml * $needlesPIDs[$pkSpec->produk_id]);
                    $needlesBahanIn[$pkSpec->produk_dasar_id]["produk_ids"][] = $pkSpec->produk_id;
                    $needlesKomposisi[$pkSpec->produk_id][$pkSpec->produk_dasar_id] = $pkSpec->jml;

                    $b_ids[$pkSpec->produk_dasar_id] = $pkSpec->produk_dasar_id; // bahan_id, bahan baku produksi, supplies
                }
            }
//            cekHere("cetak needles bahanIN");
//            arrPrint($needlesBahanIn);
//            cekHere("cetak pairingan komposisi");
//            arrPrint($needlesKomposisi);
//            mati_disini();


            $this->load->model("Mdls/MdlFifoSuppliesAssembly");
            foreach ($needlesBahanIn as $ppID => $nSpec) {
                $bb = new MdlFifoSuppliesAssembly();
                $bb->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
                $bb->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
                $bb->addFilter("produk_id='$ppID'");
                $bb->addFilter("unit>0");
                $tmpBB = $bb->lookupAllQty()->result();

                $tersedia = 0;
                if (sizeof($tmpBB) > 0) {
                    $tersedia = $tmpBB[0]->qty;
                }
                if ($nSpec["jml"] > $tersedia) {
                    $msg = "Jumlah stok " . $needlesBahanIn[$ppID]["nama"] . " tidak cukup.";
                    die(lgShowAlert($msg));
                    mati_disini("KURANGGGGG....");
                }
            }


            $b = new MdlFifoSuppliesAssembly();
            $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
            $b->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
            $b->addFilter("produk_id in (" . implode(",", $b_ids) . ")"); // kumpulan bahan id
            $b->addFilter("unit>0");
            $tmp = $b->lookupAll()->result();
            $tmpSrc = $tmp;

            cekBiru("cetak needle komposisi");
            arrPrint($needlesKomposisi);
//cekBiru("selesai cetak fifo sumber");
            mati_disini();
            $updatePairs = array();
            if (sizeof($tmp) > 0) {
                $pResult = array();
                $pResult2 = array();
//                $lastIndexProduk = array();
                $lastHpp = array();
                $patchers = array();
                $markedIDs = array();
                $pCtr = 0;

                $pNilai = 0;
                foreach ($needlesKomposisi as $pID => $needles) {
                    $totalNeed = 0;
                    if (!isset($lastIndexProduk)) {
                        $lastIndexProduk = 0;
                    }
                    if (!isset($pResult2[$lastIndexProduk])) {
                        $pResult2[$lastIndexProduk] = array();
                    }
                    foreach ($needles as $bID => $jml) {
                        $pCtr++;
                        if (!isset($fullfills[$bID])) {
                            $fullfills[$bID] = 0;
                        }
                        $kekurangans[$bID] = $needles[$bID] * $needlesPIDs[$pID];
//
//                        cekBiru("cetak array fifo cloningan");
//                        arrPrint($tmpSrc);
//                        cekBiru("selesai cetak array fifo cloningan");
                        if (!isset($lastHpp[$bID])) {
                            $lastHpp[$bID] = 0;
                        }
                        $nyerah = false;
                        while ($kekurangans[$bID] > 0 || $nyerah) {
                            $rowCtr = 0;
                            foreach ($tmpSrc as $row) {
                                if (!in_array($row->id, $markedIDs)) {
                                    $jmlAvail = $row->unit;
                                    if ((int)$row->produk_id == (int)$bID) {
                                        if ($jmlAvail >= $kekurangans[$bID]) {  //==jumlah yg tersedia ternyata cukup atau malah lebih
                                            $produk_id = $row->produk_id;
                                            $nama = $row->produk_nama;
                                            $hpp = $row->hpp;
                                            $qty = $jmlDiambil = $kekurangans[$bID];
                                            $subtotal = $hpp * $qty;
                                            $totalNeed += $subtotal;

                                            $fullfills[$bID] += $jmlDiambil;
                                            $kekurangans[$bID] -= $jmlDiambil;


                                            if ($lastHpp <> $hpp) {
                                                $pResult2[$lastIndexProduk] = array(
                                                    "pID" => $pID,
                                                    "pNama" => $needlesPIDNama[$pID],
                                                    "pQty" => $qty / $needlesKomposisi[$pID][$bID],
                                                    "pNilai" => $hpp,
                                                );
                                            }
                                            else {
                                                $pResult2[$lastIndexProduk]["pQty"] += $qty / $needlesKomposisi[$pID][$bID];
                                                $pResult2[$lastIndexProduk]["pNilai"] += $subtotal;
                                            }


                                            $sisaDiTabel = ($row->unit - $jmlDiambil);

                                            cekHere($row->id . " $pID, $produk_id, $nama jumlahnya cukup, yaitu $jmlAvail, akan diambil $jmlDiambil");
                                            cekHere("kekurangannya sekarang: " . $kekurangans[$bID] . "");

                                            //<editor-fold desc="build update pairs">
                                            $updatePairs[] = array(
                                                "id" => $row->id,
                                                "produk_id" => $row->produk_id,
                                                "unit" => ($row->unit - $jmlDiambil),
                                                "jml_nilai" => ($row->jml_nilai - ($jmlDiambil * $row->hpp)),
                                                "jml_ot" => ($row->jml_ot + $jmlDiambil),
                                                "jml_nilai_ot" => ($row->jml_nilai_ot + ($jmlDiambil * $row->hpp)),
                                            );
                                            //</editor-fold>

                                            //<editor-fold desc="manipulasi sumber fifo">
                                            $tmpSrc[$rowCtr]->unit = ($row->unit - $jmlDiambil);
                                            $tmpSrc[$rowCtr]->jml_nilai = ($row->jml_nilai - ($jmlDiambil * $row->hpp));
                                            $tmpSrc[$rowCtr]->jml_ot = ($row->jml_ot + $jmlDiambil);
                                            $tmpSrc[$rowCtr]->jml_nilai_ot = ($row->jml_nilai_ot + ($jmlDiambil * $row->hpp));
                                            //</editor-fold>

                                            cekMerah(__LINE__ . " :: sisa ditabel fifo: $sisaDiTabel :: src: " . $row->unit);

                                            if ($sisaDiTabel < 1) {
                                                $markedIDs[] = $row->id;

                                                cekUngu(__LINE__ . " :: unset line : " . $rowCtr);
                                                unset($tmpSrc[$rowCtr]);
                                            }


                                            //<editor-fold desc="patchers HPP dari FIFO RIIL, SUPPLIES">
//                                            foreach ($this->resultParams as $gateName => $paramSpec) {
//                                                foreach ($paramSpec as $key => $val) {
                                            foreach ($this->resultParams["rsltItems"] as $key => $val) {
                                                cekBiru("ATAS ::: $key => $val :::");

                                                $hasil[$key] = $$val;
                                            }
                                            $patchers["rsltItems"][] = $hasil;
//                                            }
                                            //</editor-fold>

                                            break;
                                        }
                                        else { //==jumlahnya kurang bro
                                            $produk_id = $row->produk_id;
                                            $nama = $row->produk_nama;
                                            $hpp = $row->hpp;
                                            $qty = $jmlDiambil = $jmlAvail;
                                            $subtotal = $hpp * $qty;
                                            $totalNeed += $subtotal;

                                            $fullfills[$bID] = $jmlDiambil;
                                            $kekurangans[$bID] -= $jmlDiambil;


                                            if ($lastHpp <> $subtotal) {
                                                $pResult2[$lastIndexProduk] = array(
                                                    "pID" => $pID,
                                                    "pNama" => $needlesPIDNama[$pID],
                                                    "pQty" => $qty / $needlesKomposisi[$pID][$bID],
                                                    "pNilai" => $hpp,
                                                );
                                            }
                                            else {
                                                $pResult2[$lastIndexProduk]["pQty"] += $qty / $needlesKomposisi[$pID][$bID];
                                                $pResult2[$lastIndexProduk]["pNilai"] += $hpp;
                                            }


                                            $sisaDiTabel = ($row->unit - $jmlDiambil);

                                            cekHere($row->id . " $pID, $produk_id, $nama jumlahnya tidak cukup, yaitu $jmlAvail, akan diambil $jmlDiambil");
                                            cekHere("kekurangannya sekarang: " . $kekurangans[$bID] . "<br>");

                                            //<editor-fold desc="build update pairs">
                                            $updatePairs[] = array(
                                                "id" => $row->id,
                                                "produk_id" => $row->produk_id,
                                                "unit" => ($row->unit - $jmlDiambil),
                                                "jml_nilai" => ($row->jml_nilai - ($jmlDiambil * $row->hpp)),
                                                "jml_ot" => ($row->jml_ot + $jmlDiambil),
                                                "jml_nilai_ot" => ($row->jml_nilai_ot + ($jmlDiambil * $row->hpp)),

                                            );
                                            //</editor-fold>

                                            //<editor-fold desc="manipulasi sumber fifo">
                                            $tmpSrc[$rowCtr]->unit = ($row->unit - $jmlDiambil);
                                            $tmpSrc[$rowCtr]->jml_nilai = ($row->jml_nilai - ($jmlDiambil * $row->hpp));
                                            $tmpSrc[$rowCtr]->jml_ot = ($row->jml_ot + $jmlDiambil);
                                            $tmpSrc[$rowCtr]->jml_nilai_ot = ($row->jml_nilai_ot + ($jmlDiambil * $row->hpp));
                                            //</editor-fold>

                                            cekMerah(__LINE__ . " :: sisa ditabel fifo: $sisaDiTabel ::");
                                            if ($sisaDiTabel < 1) {
                                                $markedIDs[] = $row->id;
                                            }


                                            //<editor-fold desc="patchers HPP dari FIFO RIIL">
//                                            foreach ($this->resultParams as $gateName => $paramSpec) {
//                                                foreach ($paramSpec as $key => $val) {
                                            foreach ($this->resultParams["rsltItems"] as $key => $val) {
                                                cekBiru("BAWAH ::: $key => $val :::");

                                                $hasil[$key] = $$val;
                                            }
                                            $patchers["rsltItems"][] = $hasil;
//                                            }
                                            //</editor-fold>

                                            if ($kekurangans[$bID] == 0) {
                                                unset($tmp[$rowCtr]);
                                                break;
                                            }
                                        }
                                    }
                                }
                                $rowCtr++;
                                $lastHpp[$bID] = $row->hpp;
                            }
                            if ($rowCtr > sizeof($tmp)) {
                                $nyerah = true;
                                cekHijau("baris fifo ke $rowCtr, padahal jumlah barisnya " . sizeof($tmp));
                                cekHijau("NYERAHHH... fifo untuk barang $bID tidak ada yang mau DIPAKE!");
                                die("UNABLE TO EXAMINE FIFO");
                                break;
                            }
                        }

//                        cekBiru("cetak akhir array fifo cloningan");
//                        arrPrint($tmpSrc);
//                        cekBiru("selesai akhir cetak array fifo cloningan");

                    }


                    $pNilai = $totalNeed;
                    cekKuning("pID: $pID, pHPP: $pNilai, dari: " . $totalNeed);
                    $pResult[$pID] = array(
                        "pID" => $pID,
                        "pNama" => $needlesPIDNama[$pID],
                        "pQty" => $needlesPIDs[$pID],
                        "pNilai" => $pNilai,
                    );

                    $lastIndexProduk++;
                }

//                if (sizeof($needlesPIDs) > 0) {
//                    foreach ($needlesPIDs as $pID => $pQty) {
//                        foreach ($needlesKomposisi[$pID] as $bID => $bQty) {
//                            if (!isset($patchers['items'][$pID]["hpp"])) {
//                                $patchers['items'][$pID]["hpp"] = 0;
//                            }
//                            if (!isset($patchers['items'][$pID]["sub_hpp"])) {
//                                $patchers['items'][$pID]["sub_hpp"] = 0;
//                            }
//                            $patchers['items'][$pID]["hpp"] += (($arrHppAvg[$bID] * $bQty)); // build patchers produk
//                            $patchers['items'][$pID]["sub_hpp"] += (($arrHppAvg[$bID] * $bQty) * $pQty); // build patchers produk
//                        }
//                    }
//                }


                if (sizeof($updatePairs) > 0) {
//                    cekBiru("cetak updatePairs FIfo murni");
//                    arrPrint($updatePairs);
//                    foreach ($updatePairs as $upSpec) {
//                        $updateData = $upSpec;
//                        unset($updateData["id"]);
//                        $b = new MdlFifoSuppliesAssembly();
//                        $b->updateData(array("id" => $upSpec['id']), $updateData);
//                        cekMerah($this->db->last_query());
//                    }
                }
//                cekBiru("cetak patchers:");
//                arrPrint($patchers);
//                $this->result = $patchers;
            }
            else {
//                $this->result = array();
            }
        }

//        cekBiru("cetak patcher fifo murni");
//        arrPrint($patchers);
        cekUngu("cetak pResult");
        arrPrint($pResult);
        cekUngu("cetak pResult2");
        arrPrint($pResult2);

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

        $arrBahan = array();
        $tmpBahan = array();
        $arrProduk = array();
        $arrProdukQty = array();
        if (sizeof($inParams) > 0) {
            $cCode = "_TR_" . $inParams[0]["static"]["jenisTr"];
//arrPrintHijau($inParams);
            foreach ($inParams as $sentParams) {
                foreach ($sentParams as $pSpec) {
                    $arrProduk[] = array(
                        "id" => $pSpec["extern_id"],
                        "nama" => $pSpec["extern_nama"],
                        "jml" => $pSpec["produk_qty"],
                    );
                    $arrProdukQty[$pSpec["extern_id"]] = $pSpec["produk_qty"];
                }
            }
//arrPrintWebs($arrProdukQty);
            $this->setMasterID($master_id);
            $components = $this->extractProducts($arrProduk)['indexed'];
            arrPrintWebs($components);
//mati_disini(__LINE__);
            if (sizeof($components) > 0) {
                foreach ($components as $pID => $cSpec) {
                    foreach ($cSpec as $pSpec) {
                        if (!isset($arrBahan[$pSpec['id']])) {
                            $arrBahan[$pSpec['id']] = array(
                                "id" => $pSpec['id'],
                                "nama" => $pSpec['nama'],
                                "jml" => $pSpec['jml'] * $arrProdukQty[$pID],
                            );
                        }
                        else {
                            $arrBahan[$pSpec['id']]["jml"] += $pSpec['jml'] * $arrProdukQty[$pID];
                        }
                    }
                }
            }

            arrPrintPink($arrBahan);


            $arrFIFO_bahan_srcs = $this->fetchFIFO_bahan($arrBahan, $inParams[0]["static"]["cabang_id"], $inParams[0]["static"]["gudang_id"]);
            $arrFIFO_bahan_srcs_ori = $arrFIFO_bahan_srcs;

//            mati_disini(__LINE__);

//
//arrPrintHijau($arrFIFO_bahan_srcs);
//arrPrintHijau($arrProduk);
//mati_disini(__LINE__);

            // <editor-fold defaultstate="collapsed" desc="KIRI -- periksa loker2 fifo yang available">
            echo "<td width=40% valign=top bgcolor=#ffffff>";
            //echo "<h2>NYUSUN DPO GAANNN...</h2>";
            echo "<div style='left:0;position:relative;width:100%;border:1px #009900 solid;'>";

            foreach ($arrProduk as $pSpec) {

                $pID = $pSpec['id'];
                if (!isset($cntProd[$pID])) {
                    $cntProd[$pID] = 0;
                }
                $pNewSpec[$pID] = array();

                echo "<h2><span style='background:#dd3300;color:#ffffff;padding:2px;'>" . $pSpec['jml'] . "</span> " . $pSpec['nama'] . "</h2>";

                //$topMembers[$pID] = array();
                $fJmlPair[$pID] = array();

                $terpenuhi[$pID] = 0;
                $kurangnya[$pID] = $pSpec['jml'];
                $indexFifo = 0;

                //$akumByBhn[$pID] = 0;
                $akumByProd[$pID] = 0;
                $procID = -1;

                while ($kurangnya[$pID] > 0) {
                    //echo "<h4 style='text-align:center;'>FIFO dijembreng!</h4>";
                    $procID++;
                    $tmpHeader = array(
                        "id",
                        "bID",
                        "needs",
                        "avail",
                        "prodAvail"
                    );
                    echo "<table cellspacing=0 cellpadding=0 border=1 bordercolor=#cccccc style='color:#0077ff;position:absolute:left:10px;'>";
                    echo "<tr>";
                    foreach ($tmpHeader as $k) {
                        echo "<th>$k</th>";
                    }
                    echo "</tr>";


                    foreach ($components[$pID] as $c) {
                        $bID = $c['id'];

                        // <editor-fold defaultstate="collapsed" desc="ini untuk keperluan ngindeks jembrengan fifo asli">
                        $tmpFifoID = $arrFIFO_bahan_srcs[$bID][$indexFifo]['id'];
                        $sequenceFIFOs[] = $tmpFifoID;
                        $tmpFifoSpec[$tmpFifoID] = $arrFIFO_bahan_srcs_ori[$bID][$indexFifo];
                        // </editor-fold>


                        $realNeedle = ($c['jml'] * $pSpec['jml']);
                        $maxProdAvail[$pID] = floor($arrFIFO_bahan_srcs[$bID][$indexFifo]['jml'] / $c['jml']);

                        $tmpTersedia = 0;
                        $tmpKurangnya = 0;
                        //===kalau ga cukup utk 1 produk
                        //===harus dinormalisasi dengan baris bawahnya (minjem)
                        if ($maxProdAvail[$pID] < 1) {
                            //echo "<div style='background:#e5e5e5;color:#dd3300;'>maxProd kurang, pakai normalisasi</div>";
                            $maxProdAvail[$pID] = 1;
                            $tmpTersedia = $arrFIFO_bahan_srcs[$bID][$indexFifo]['jml'];
                            $tmpKurangnya = ($c['jml'] - $tmpTersedia);
                            //===pinjam dari bawahnya
                            $arrFIFO_bahan_srcs[$bID][$indexFifo]['meminjam'] = $tmpKurangnya;
                            $arrFIFO_bahan_srcs[$bID][$indexFifo + 1]['dipinjam'] = $tmpKurangnya;

                            $nilaiHPPCampuran = (($arrFIFO_bahan_srcs[$bID][$indexFifo]['jml'] * $arrFIFO_bahan_srcs[$bID][$indexFifo]['hpp']) +
                                ($tmpKurangnya * $arrFIFO_bahan_srcs[$bID][$indexFifo + 1]['hpp']));
                            $arrFIFO_bahan_srcs[$bID][$indexFifo]['mixValue'] = $nilaiHPPCampuran;
                            //$arrFIFO_bahan_srcs[$bID][$indexFifo+1]['jml']-=($tmpKurangnya*$c['jml']);
                        }
                        $topMembers[$pID][$bID] = $maxProdAvail[$pID];


                        $tmpHeaderValues = array(
                            "id" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['id'],
                            "bID" => $bID,
                            "needs" => $realNeedle,
                            "avail" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['jml'],
                            "prodAvail" => $maxProdAvail[$pID]
                        );
                        echo "<tr>";
                        foreach ($tmpHeaderValues as $k => $v) {
                            echo "<td align=right>$v</td>";
                        }
                        echo "</tr>";

                        $tmpFifoSpec[$bID] = $arrFIFO_bahan_srcs[$bID][$indexFifo];
                    }
                    echo "</table>";

                    echo "<table cellspacing=0 cellpadding=0 border=1 bordercolor=#d5d5b5 style='background:#f5f5e5;color:#9900cc;margin-left:100px;'>";
                    $idxProc = 0;
                    //$jmlAcuan = 0;

                    $jmlAcuan = min($topMembers[$pID]);
                    $pHPP = 0;
                    //echo "<div style='background:#e5e5e5;color:#dd3300;'>jmlAcuan sekarang: <strong>$jmlAcuan</strong></div>";
                    $akumPerProdukDilihatDariBahan[$pID] = 0;


                    foreach ($topMembers[$pID] as $bID => $maxAvP) {

                        $nilaiPerLoker = 0;
                        if ($idxProc == 0) {
                            $subNilBhn[$bID] = 0;
                            if ($jmlAcuan >= $kurangnya[$pID]) {
                                $jmlProdDiambil[$pID] = $kurangnya[$pID];
                            }
                            else {
                                $jmlProdDiambil[$pID] = $jmlAcuan;
                            }

                            $tmpMemberArray = array(
                                "id",
                                "bahan_id",
                                "produk_id",
                                "nama",
                                "hpp",
                                "jml",
                                "diambil",
                                "subHPP",
                                "jmlProd",
                                "needle",
                                "sisa",
                                "meminjam",
                                "dipinjam",
                                "nilFIFO",
                                "nilProdNow",
                                "nilTotalNow",
                                //"dipinjam" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['dipinjam'],
                                "hpp_riil",
                                "ppv_riil",
                            );

                            echo "<tr>";
                            foreach ($tmpMemberArray as $k => $v) {
                                echo "<td align=right>";
                                echo "$v &nbsp;";
                                echo "<td>";
                            }
                            echo "</tr>";
                        }


                        $jmlFifoDiambil = ($jmlProdDiambil[$pID] * $components[$pID][$bID]['jml']);
                        $jmlFifoSisa = ($arrFIFO_bahan_srcs[$bID][$indexFifo]['jml'] - $jmlFifoDiambil);


                        if ($arrFIFO_bahan_srcs[$bID][$indexFifo]['id'] < 1) {
                            echo "<div style='display:block;background:#444444;color:#ffffcc;'>id FIFO utk $bID KOSONG (pada index $indexFifo)!</div>";
                        }
                        //===kalau tadi jumlahnya kurang dan meminjam
                        if (!isset($tmpMemberArray)) {
                            $tmpMemberArray = array();
                        }
                        if (!isset($tmpMemberArray2)) {
                            $tmpMemberArray2 = array();
                        }

                        if (isset($arrFIFO_bahan_srcs[$bID][$indexFifo]['meminjam']) && $arrFIFO_bahan_srcs[$bID][$indexFifo]['meminjam'] > 0) {

                            //ngupdatenya langsung dua baris
                            //echo $arrFIFO_bahan_srcs[$bID][$indexFifo]['id'] . "/" . $arrFIFO_bahan_srcs[$bID][$indexFifo + 1]['id'] . "<br>";
                            $realDiambil = ($jmlFifoDiambil - $arrFIFO_bahan_srcs[$bID][$indexFifo]['meminjam']);
                            $realSisa = ($jmlFifoSisa + $arrFIFO_bahan_srcs[$bID][$indexFifo]['meminjam']);
                            $tmpMemberArray = array(
                                "id" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['id'],
                                "bahan_id" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['produk_id'],
                                "produk_id" => $pID,
                                "nama" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['nama'],
                                "hpp" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['hpp'],
                                "jml" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['jml'],
                                "diambil" => $realDiambil,
                                "subHPP" => ($arrFIFO_bahan_srcs[$bID][$indexFifo]['hpp'] * $realDiambil),
                                "jmlProd" => $jmlProdDiambil[$pID],
                                "needle" => $jmlFifoDiambil,
                                "sisa" => $realSisa,
                                "meminjam" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['meminjam'],
                                "dipinjam" => 0,
                                //"dipinjam" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['dipinjam'],
                                "hpp_riil" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['hpp_riil'],
                                "ppv_riil" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['ppv_riil'],
                                "subHpp_riil" => ($arrFIFO_bahan_srcs[$bID][$indexFifo]['hpp_riil'] * $realDiambil),
                                "subPpv_riil" => ($arrFIFO_bahan_srcs[$bID][$indexFifo]['ppv_riil'] * $realDiambil),
                                //---
                                "suppliers_id" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['suppliers_id'],
                                "suppliers_nama" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['suppliers_nama'],
                                "oleh_id" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['oleh_id'],
                                "oleh_nama" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['oleh_nama'],
                                "purchase_id" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['purchase_id'],
                                "purchase_nomer" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['purchase_nomer'],
                                "ppn_in" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['ppn_in'],
                                "ppn_in_nilai" => ($arrFIFO_bahan_srcs[$bID][$indexFifo]['ppn_in'] * $realDiambil),
                            );
                            //echo "proses 1A<br>";
                            $tmpMemberArray2 = array(
                                "id" => $arrFIFO_bahan_srcs[$bID][$indexFifo + 1]['id'],
                                "bahan_id" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['produk_id'],
                                "produk_id" => $pID,
                                "nama" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['nama'],
                                "hpp" => $arrFIFO_bahan_srcs[$bID][$indexFifo + 1]['hpp'],
                                "jml" => $arrFIFO_bahan_srcs[$bID][$indexFifo + 1]['jml'],
                                "diambil" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['meminjam'],
                                "subHPP" => ($arrFIFO_bahan_srcs[$bID][$indexFifo]['hpp'] * $arrFIFO_bahan_srcs[$bID][$indexFifo]['meminjam']),
                                "jmlProd" => $jmlProdDiambil[$pID],
                                "needle" => $jmlFifoDiambil,
                                "sisa" => ($arrFIFO_bahan_srcs[$bID][$indexFifo + 1]['jml'] - $arrFIFO_bahan_srcs[$bID][$indexFifo]['meminjam']),
                                //"meminjam" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['meminjam'],
                                "meminjam" => 0,
                                "dipinjam" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['dipinjam'],
                                //-----
                                "hpp_riil" => $arrFIFO_bahan_srcs[$bID][$indexFifo + 1]['hpp_riil'],
                                "ppv_riil" => $arrFIFO_bahan_srcs[$bID][$indexFifo + 1]['ppv_riil'],
                                "subHpp_riil" => ($arrFIFO_bahan_srcs[$bID][$indexFifo]['hpp_riil'] * $arrFIFO_bahan_srcs[$bID][$indexFifo]['meminjam']),
                                "subPpv_riil" => ($arrFIFO_bahan_srcs[$bID][$indexFifo]['ppv_riil'] * $arrFIFO_bahan_srcs[$bID][$indexFifo]['meminjam']),
                                //---
                                "suppliers_id" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['suppliers_id'],
                                "suppliers_nama" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['suppliers_nama'],
                                "oleh_id" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['oleh_id'],
                                "oleh_nama" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['oleh_nama'],
                                "purchase_id" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['purchase_id'],
                                "purchase_nomer" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['purchase_nomer'],
                                "ppn_in" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['ppn_in'],
                                "ppn_in_nilai" => ($arrFIFO_bahan_srcs[$bID][$indexFifo]['ppn_in'] * $arrFIFO_bahan_srcs[$bID][$indexFifo]['meminjam']),
                            );
                            //echo "proses 1B<br>";
                            $arrFIFO_bahan_srcs[$bID][$indexFifo + 1]['jml'] -= $arrFIFO_bahan_srcs[$bID][$indexFifo]['meminjam'];
                        }
                        else {
                            if (isset($arrFIFO_bahan_srcs[$bID][$indexFifo]['dipinjam']) && $arrFIFO_bahan_srcs[$bID][$indexFifo]['dipinjam'] > 0) {
                                $realDiambil = ($jmlFifoDiambil);
                                $realSisa = ($jmlFifoSisa);
                                $tmpMemberArray = array(
                                    "id" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['id'],
                                    "bahan_id" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['produk_id'],
                                    "produk_id" => $pID,
                                    "nama" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['nama'],
                                    "hpp" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['hpp'],
                                    "jml" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['jml'],
                                    "diambil" => $realDiambil,
                                    "subHPP" => ($arrFIFO_bahan_srcs[$bID][$indexFifo]['hpp'] * $realDiambil),
                                    "jmlProd" => $jmlProdDiambil[$pID],
                                    "needle" => $jmlFifoDiambil,
                                    "sisa" => $realSisa,
                                    "meminjam" => 0,
                                    //"meminjam" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['meminjam'],
                                    "dipinjam" => 0,
                                    //"dipinjam" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['dipinjam'],
                                    //-----
                                    "hpp_riil" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['hpp_riil'],
                                    "ppv_riil" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['ppv_riil'],
                                    "subHpp_riil" => ($arrFIFO_bahan_srcs[$bID][$indexFifo]['hpp_riil'] * $realDiambil),
                                    "subPpv_riil" => ($arrFIFO_bahan_srcs[$bID][$indexFifo]['ppv_riil'] * $realDiambil),
                                    //---
                                    "suppliers_id" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['suppliers_id'],
                                    "suppliers_nama" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['suppliers_nama'],
                                    "oleh_id" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['oleh_id'],
                                    "oleh_nama" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['oleh_nama'],
                                    "purchase_id" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['purchase_id'],
                                    "purchase_nomer" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['purchase_nomer'],
                                    "ppn_in" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['ppn_in'],
                                    "ppn_in_nilai" => ($arrFIFO_bahan_srcs[$bID][$indexFifo]['ppn_in'] * $realDiambil),
                                );
                                //echo "proses 2<br>";
                            }
                            else {
                                $realDiambil = $jmlFifoDiambil;
                                $realSisa = $jmlFifoSisa;
                                $tmpMemberArray = array(
                                    "id" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['id'],
                                    "bahan_id" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['produk_id'],
                                    "produk_id" => $pID,
                                    "nama" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['nama'],
                                    "hpp" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['hpp'],
                                    "jml" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['jml'],
                                    "diambil" => $realDiambil,
                                    "subHPP" => ($arrFIFO_bahan_srcs[$bID][$indexFifo]['hpp'] * $realDiambil),
                                    "jmlProd" => $jmlProdDiambil[$pID],
                                    "needle" => $jmlFifoDiambil,
                                    "sisa" => $realSisa,
                                    "meminjam" => 0,
                                    "dipinjam" => 0,
                                    //"meminjam" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['meminjam'],
                                    //"dipinjam" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['dipinjam'],
                                    //-----
                                    "hpp_riil" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['hpp_riil'],
                                    "ppv_riil" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['ppv_riil'],
                                    "subHpp_riil" => ($arrFIFO_bahan_srcs[$bID][$indexFifo]['hpp_riil'] * $realDiambil),
                                    "subPpv_riil" => ($arrFIFO_bahan_srcs[$bID][$indexFifo]['ppv_riil'] * $realDiambil),
                                    //---
                                    "suppliers_id" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['suppliers_id'],
                                    "suppliers_nama" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['suppliers_nama'],
                                    "oleh_id" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['oleh_id'],
                                    "oleh_nama" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['oleh_nama'],
                                    "purchase_id" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['purchase_id'],
                                    "purchase_nomer" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['purchase_nomer'],
                                    "ppn_in" => $arrFIFO_bahan_srcs[$bID][$indexFifo]['ppn_in'],
                                    "ppn_in_nilai" => ($arrFIFO_bahan_srcs[$bID][$indexFifo]['ppn_in'] * $realDiambil),
                                );
                                //echo "jmlFIfoDiambil: $jmlFifoDiambil<br>";
                                //echo "realDiambil: $realDiambil<br>";
                                //echo "proses 3<br>";
                            }
                        }

                        if ((sizeof($tmpMemberArray) > 0) && (is_array($tmpMemberArray))) {
                            if (!isset($hppSatProd[$pID][$procID])) {
                                $hppSatProd[$pID][$procID] = 0;
                            }
                            if (!isset($akumProdInProc[$pID][$pID][$procID])) {
                                $akumProdInProc[$pID][$pID][$procID] = 0;
                            }
                            if (!isset($akumAllProc)) {
                                $akumAllProc = 0;
                            }
                            if (!isset($akumHPPPerProc[$pID][$procID])) {
                                $akumHPPPerProc[$pID][$procID] = 0;
                            }
                            if (!isset($totalPerBhn[$bID])) {
                                $totalPerBhn[$bID] = 0;
                            }
                            if (!isset($terpenuhiBhn[$bID])) {
                                $terpenuhiBhn[$bID] = 0;
                            }

                            $qtySat = $tmpMemberArray['diambil'];
                            $hppSat = $tmpMemberArray['hpp'];
                            $hppSatProd[$pID][$procID] += $hppSat;
                            $subPerProc[$pID][$procID] = ($qtySat * $hppSat);
                            $akumProdInProc[$pID][$pID][$procID] += $subPerProc[$pID][$procID];
                            $akumAllProc += $subPerProc[$pID][$procID];

                            $tmpMemberArray['subHPP'] = $subPerProc[$pID][$procID];
                            $tmpMemberArray['nilFIFO'] = $subPerProc[$pID][$procID];
                            $tmpMemberArray['nilProdNow'] = $akumProdInProc[$pID][$pID][$procID];
                            $tmpMemberArray['nilTotalNow'] = $akumAllProc;

                            $akumHPPPerProc[$pID][$procID] += ($tmpMemberArray['subHPP']);


                            $totalPerBhn[$bID] += ($subPerProc[$pID][$procID]);
                            $terpenuhiBhn[$bID] += $qtySat;

                            echo "<tr>";
                            $arrBold = array("hpp", "subHPP", "diambil");
                            $arrDanger = array("sisa");
                            foreach ($tmpMemberArray as $k => $v) {
                                echo "<td align=right>";
                                if (in_array($k, $arrBold)) {
                                    echo "<b>$v &nbsp;</b>";
                                }
                                else {
                                    if (in_array($k, $arrDanger)) {
                                        echo "<b style='color:#dd3300;'>$v</b>";
                                    }
                                    else {
                                        echo "$v &nbsp;";
                                    }
                                }

                                echo "<td>";
                            }
                            echo "</tr>";


                            $arrFifoRsltTarget_bahan[] = $tmpMemberArray;
                            unset($tmpMemberArray);
                        }
                        if ((sizeof($tmpMemberArray2) > 0) && (is_array($tmpMemberArray2))) {
                            if (!isset($hppSatProd[$pID][$procID])) {
                                $hppSatProd[$pID][$procID] = 0;
                            }
                            if (!isset($akumProdInProc[$pID][$pID][$procID])) {
                                $akumProdInProc[$pID][$pID][$procID] = 0;
                            }
                            if (!isset($akumAllProc)) {
                                $akumAllProc = 0;
                            }
                            if (!isset($akumHPPPerProc[$pID][$procID])) {
                                $akumHPPPerProc[$pID][$procID] = 0;
                            }
                            if (!isset($totalPerBhn[$bID])) {
                                $totalPerBhn[$bID] = 0;
                            }
                            if (!isset($terpenuhiBhn[$bID])) {
                                $terpenuhiBhn[$bID] = 0;
                            }

                            $qtySat = isset($tmpMemberArray2['diambil']) ? $tmpMemberArray2['diambil'] : 0;
                            $hppSat = isset($tmpMemberArray2['hpp']) ? $tmpMemberArray2['hpp'] : 0;
                            $hppSatProd[$pID][$procID] += $hppSat;
                            $subPerProc[$pID][$procID] = ($qtySat * $hppSat);
                            $akumProdInProc[$pID][$pID][$procID] += $subPerProc[$pID][$procID];
                            $akumAllProc += $subPerProc[$pID][$procID];

                            $tmpMemberArray2['subHPP'] = $subPerProc[$pID][$procID];
                            $tmpMemberArray2['nilFIFO'] = $subPerProc[$pID][$procID];
                            $tmpMemberArray2['nilProdNow'] = $akumProdInProc[$pID][$pID][$procID];
                            $tmpMemberArray2['nilTotalNow'] = $akumAllProc;

                            $akumHPPPerProc[$pID][$procID] += ($tmpMemberArray2['subHPP']);

                            $totalPerBhn[$bID] += ($subPerProc[$pID][$procID]);
                            $terpenuhiBhn[$bID] += $qtySat;

                            echo "<tr>";
                            $arrBold = array("hpp", "subHPP", "diambil");
                            $arrDanger = array("sisa");
                            foreach ($tmpMemberArray2 as $k => $v) {
                                echo "<td align=right>";
                                if (in_array($k, $arrBold)) {
                                    echo "<b>$v &nbsp;</b>";
                                }
                                else {
                                    if (in_array($k, $arrDanger)) {
                                        echo "<b style='color:#dd3300;'>$v</b>";
                                    }
                                    else {
                                        echo "$v &nbsp;";
                                    }
                                }

                                echo "<td>";
                            }
                            echo "</tr>";


                            $arrFifoRsltTarget_bahan[] = $tmpMemberArray2;
                            unset($tmpMemberArray2);
                        }


                        echo "<tr>";
                        echo "</tr>";

                        $idxProc++;

                        //===PENTING
                        //==indeks fifonya harus diupdate
                        //if ($tmpFifoSpec[$bID]['jml'] == $jmlFifoDiambil) {
                        if ($realSisa < 1) {
                            $arrFIFO_bahan_srcs[$bID][$indexFifo]['jml'] = 0;
                            array_shift($arrFIFO_bahan_srcs[$bID]);
                        }
                        else {
                            $arrFIFO_bahan_srcs[$bID][$indexFifo]['jml'] = $jmlFifoSisa;
                        }
                    }

                    echo "<tr><td colspan='12' align=right>" . $akumHPPPerProc[$pID][$procID] . "***</td></tr>";
                    echo "</table>";
                    //echo "selesai satu proses ($procID) pada produk $pID dengan akum HPP $akumHPPPerProc[$pID][$procID]<br>";
                    $terpenuhi[$pID] += $jmlProdDiambil[$pID];
                    $kurangnya[$pID] -= $jmlProdDiambil[$pID];


                    echo "<table cellspacing=0 cellpadding=0 border=1 bordercolor=#aaccaa align=right style='background:#e5f5e5;color:#009900;margin-left:250px;'>";

                    if (!isset($akumByProd[$pID])) {
                        $akumByProd[$pID] = 0;
                    }
                    if (!isset($akumAllProd[$pID])) {
                        $akumAllProd[$pID] = 0;
                    }
                    if (!isset($subTotalProd[$pID])) {
                        $subTotalProd[$pID] = 0;
                    }
                    if (!isset($totalPerProd[$pID])) {
                        $totalPerProd[$pID] = 0;
                    }
                    if (!isset($totalProd)) {
                        $totalProd = 0;
                    }


//                    $akumByProd[$pID] += $subHPPProduk[$pID];
//                    $akumAllProd += $subHPPProduk[$pID];


                    $subTotalProdThis = ($hppSatProd[$pID][$procID] * $jmlProdDiambil[$pID]);
                    $subTotalProd[$pID] += $akumHPPPerProc[$pID][$procID];
                    $totalPerProd[$pID] += $akumHPPPerProc[$pID][$procID];
                    $totalProd += $akumHPPPerProc[$pID][$procID];

                    $tmpMemberArrayP = array(
                        //"id" => $pID,
                        "produk_id" => $pID,
                        "produk_nama" => $pSpec['nama'],
                        "jml" => $jmlProdDiambil[$pID],
                        "hpp" => ($akumHPPPerProc[$pID][$procID] / $jmlProdDiambil[$pID]),
                        "subHPP" => ($akumHPPPerProc[$pID][$procID]),
                        "akumJmlProd" => $terpenuhi[$pID],
                        //"nilFIFO" => $akumByProdFifo[$pID][$fID],
                        "nilProc" => $akumHPPPerProc[$pID][$procID],
                        "nilProd" => $totalPerProd[$pID],
                        "nilTotal" => $totalProd,
                    );

                    echo "<tr>";
                    foreach ($tmpMemberArrayP as $k => $v) {
                        echo "<td>";
                        echo "$k";
                        echo "<td>";
                    }
                    echo "</tr>";
                    $arrFifoRsltTarget_produk[] = $tmpMemberArrayP;


                    $arrBold = array("akumJmlProd");
                    echo "<tr>";
                    foreach ($tmpMemberArrayP as $k => $v) {

                        if (in_array($k, $arrBold)) {
                            echo "<td align=right bgcolor=#009900>";
                            echo "<b style='color:#ffffff;'>$v</b>";
                            echo "<td>";
                        }
                        else {
                            echo "<td align=right>";
                            echo "$v";
                            echo "<td>";
                        }
                    }
                    echo "</tr>";

                    echo "</table>";
                    $finColor = $kurangnya[$pID] > 0 ? "#dd3300" : "#009900";
                    echo "<h4 style='text-align:right;color:$finColor;'>" . $terpenuhi[$pID] . "/" . $pSpec['jml'] . "</h4>";
                }
            }


            //<editor-fold desc="building UPDATE PAIRS, PATCHER's Bahan dan Produk">

//            cekBiru("cetak arrFifoRsltTarget_bahan");
//            arrPrint($arrFifoRsltTarget_bahan);
//            cekBiru("cetak arrFifo bahan original...");
//            arrPrint($arrFIFO_bahan_srcs_ori);
//            cekBiru("cetak arrFifoRsltTarget_produk");
//            arrPrint($arrFifoRsltTarget_produk);

            $updatePairs = array();
            $patchers = array();
            unset($_SESSION[$cCode]['rsltItems']);//resetor untuk fix dobel inject parampacher
            $it_rsltItems = isset($_SESSION[$cCode]['rsltItems']) && count($_SESSION[$cCode]['rsltItems']) > 0 ? count($_SESSION[$cCode]['rsltItems']) : 0;
            if (sizeof($arrFifoRsltTarget_bahan) > 0) {
                foreach ($arrFifoRsltTarget_bahan as $uSpec) {
                    $it_rsltItems++;
                    if (sizeof($arrFIFO_bahan_srcs_ori[$uSpec["bahan_id"]]) > 0) {
                        foreach ($arrFIFO_bahan_srcs_ori[$uSpec["bahan_id"]] as $k => $bSpec) {
                            if ($uSpec["id"] == $bSpec["id"]) {
                                $updatePairs[] = array(
                                    "id" => $uSpec["id"],
                                    "produk_id" => $uSpec["bahan_id"],
                                    "unit" => $uSpec["sisa"],
                                    "jml_nilai" => ($uSpec["sisa"] * $uSpec["hpp"]),
                                    "jml_ot" => $bSpec["jml_ot"] + $uSpec["diambil"],
                                    "jml_nilai_ot" => $bSpec["jml_nilai_ot"] + $uSpec["subHPP"],
                                    //-----
                                    "jml_nilai_riil" => ($uSpec["sisa"] * $uSpec["hpp_riil"]),
                                    "ppv_nilai_riil" => ($uSpec["sisa"] * $uSpec["ppv_riil"]),
                                );
                                $arrFIFO_bahan_srcs_ori[$uSpec["bahan_id"]][$k]["jml_ot"] = $bSpec["jml_ot"] + $uSpec["diambil"];
                                $arrFIFO_bahan_srcs_ori[$uSpec["bahan_id"]][$k]["jml_nilai_ot"] = $bSpec["jml_nilai_ot"] + $uSpec["subHPP"];
                            }

                        }
                    }
                    foreach ($this->resultParams["rsltItems"] as $key => $val) {
                        $hasil[$key] = $uSpec[$val];
                    }
                    $patchers["rsltItems"][$it_rsltItems] = $hasil;
                }

                if (sizeof($updatePairs) > 0) {
//                    cekBiru("cetak updatePairs FIfo murni");
//                    arrPrint($updatePairs);

                    $this->load->model("Mdls/MdlFifoSuppliesAssembly");

                    foreach ($updatePairs as $upSpec) {
                        $updateData = $upSpec;
                        unset($updateData["id"]);

                        $b = new MdlFifoSuppliesAssembly();
                        $b->updateData(array("id" => $upSpec['id']), $updateData);

                        cekKuning("cetak fifo supplies");
                        cekMerah($this->db->last_query());
                    }
                }
            }

            unset($_SESSION[$cCode]['rsltItems2']);//resetor untuk fix dobel inject parampacher
            $it_rsltItems2 = isset($_SESSION[$cCode]['rsltItems2']) && count($_SESSION[$cCode]['rsltItems2']) > 0 ? count($_SESSION[$cCode]['rsltItems2']) : 0;
            if (sizeof($arrFifoRsltTarget_produk) > 0) {
                foreach ($arrFifoRsltTarget_produk as $rSpec) {
                    $it_rsltItems2++;
                    foreach ($this->resultParams["rsltItems2"] as $key => $val) {
                        $hasil2[$key] = $rSpec[$val];
                    }
                    $patchers["rsltItems2"][$it_rsltItems2] = $hasil2;
                }
            }
            //</editor-fold>


            echo "</div>";
            echo "</td>";
            // </editor-fold>


            $this->result = $patchers;
        }
        else {
            $this->result = array();
        }


//        mati_disini(get_class($this));


        if (sizeof($updatePairs) > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public function extractProducts($arrProduk)
    {
        if (is_array($arrProduk) && sizeof($arrProduk) > 0) {
            $arrResults = array();
            $arrBahan = array();
            $p_ids = array();
            foreach ($arrProduk as $prodSpec) {
                $p_ids[] = $prodSpec["id"];
            }

            // ========================================== ========================================== ==========================================
            // ========================================== ========================================== ==========================================
            $tmpPk = array();
            $masterID = $this->getMasterID();
            $this->load->model("MdlTransaksi");
            $tr = New MdlTransaksi();
            $tr->setFilters(array());
            $tr->addFilter("transaksi_id='$masterID'");
            $rgTmp = $tr->lookupDataRegistries()->result();
//            $trTmp = $tr->lookupAll()->result();
//            $indexReg = blobDecode($trTmp[0]->indexing_registry);
//            $regID = isset($indexReg['items_komposisi']) ? $indexReg['items_komposisi'] : 0;
            if (sizeof($rgTmp) > 0) {
                $arrKomposisi = blobDecode($rgTmp[0]->items_komposisi);
                if (sizeof($arrKomposisi) > 0) {

                    foreach ($p_ids as $pID_needle) {
                        if (isset($arrKomposisi[$pID_needle]) && (isset($arrKomposisi[$pID_needle]['produk']))) {
                            foreach ($arrKomposisi[$pID_needle]['produk'] as $ikSpec) {
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

                $this->load->model("Mdls/MdlProdukKomposisi");
                $pk = new MdlProdukKomposisi();
                $pk->addFilter("status='1'");
                $pk->addFilter("jenis='produk'");
                $pk->addFilter("produk_id in (" . implode(",", $p_ids) . ")");
                $tmpPk = $pk->lookupAll()->result();
            }

//arrPrintWebs($tmpPk);
            if (sizeof($tmpPk) > 0) {
                foreach ($tmpPk as $rowss) {
                    $row = (object)$rowss;
//                    arrPrintPink($row);
                    $pID = $row->produk_id_hasil;
                    $arrResults[$pID][$row->produk_dasar_id] = array(
                        "id" => $row->produk_dasar_id,
                        "nama" => $row->produk_dasar_nama,
                        "jml" => $row->jml,
                    );
                    if (!in_array($row->produk_dasar_id, $arrBahan)) {
                        $arrBahan[] = $row->produk_dasar_id;
                    }
                }
//                arrPrintWebs($arrBahan);
//                arrPrintWebs($arrResults);
//                mati_disini(__LINE__);
                return array(
                    "raw" => $arrBahan, //===nama2 bahan lengkap, tidak pakai indeks
                    "indexed" => $arrResults//===nama2 bahan berdasarkan indeks id produk
                );
            }
            else {
                return null;
            }
        }
        else {
            die("Parameter fungsi " . __FUNCTION__ . " memerlukan array berupa daftar produk!");
        }
    }

    public function fetchFIFO_bahan($arrBahan, $cabang_id, $gudang_id)
    {
        if (is_array($arrBahan) && sizeof($arrBahan) > 0) {
            $arrResults = array();
            $b_ids = array();
            foreach ($arrBahan as $bSpec) {
                $b_ids[] = $bSpec['id'];
            }


            $this->load->model("Mdls/MdlFifoSuppliesAssembly");
            $bb = new MdlFifoSuppliesAssembly();
            $bb->addFilter("cabang_id='$cabang_id'");
            $bb->addFilter("gudang_id='$gudang_id'");
            $bb->addFilter("produk_id in (" . implode(",", $b_ids) . ")"); // kumpulan bahan id
            $bb->addFilter("unit>0");
            $tmp = $bb->lookupAll()->result();

            if (sizeof($tmp) > 0) {
                $cnt = 0;
                foreach ($tmp as $row) {
                    $bID = $row->produk_id;
                    $arrResults[$bID][] = array(
                        "cnt" => $cnt, //==ini id tabel (pk), bukan id relasi ke bahan atau yg lain
                        "id" => $row->id, //==ini id tabel (pk), bukan id relasi ke bahan atau yg lain
                        "bahan_id" => $row->produk_id,
                        "produk_id" => $row->produk_id,
                        "nama" => $row->produk_nama,
                        "jml" => $row->unit,
                        "hpp" => $row->hpp,
                        "akum" => $row->jml_nilai,
                        "jml_ot" => $row->jml_ot,
                        "jml_nilai_ot" => $row->jml_nilai_ot,
                        //-------
                        "hpp_riil" => $row->hpp_riil,
                        "jml_nilai_riil" => $row->jml_nilai_riil,
                        "ppv_riil" => $row->ppv_riil,
                        "ppv_nilai_riil" => $row->ppv_nilai_riil,
                    );
                    $cnt++;
                }
                return $arrResults;
            }
            else {
                return null;
            }
        }
        else {
            die("Parameter fungsi " . __FUNCTION__ . " memerlukan array berupa daftar bahan!");
        }
    }


    public function exec()
    {
        return $this->result;
    }


    // dibawah ini dipakai oleh manufactur per-fase. jadi hilangkan _NEW
    public function extractProducts_NEW($arrProduk)
    {
        if (is_array($arrProduk) && sizeof($arrProduk) > 0) {
            $arrResults = array();
            $arrBahan = array();
            $p_ids = array();
            foreach ($arrProduk as $prodSpec) {
                $p_ids[] = $prodSpec["id"];
            }
            arrPrintHijau($p_ids);


            // ========================================== ========================================== ==========================================
            // ========================================== ========================================== ==========================================
            $tmpPk = array();
            $masterID = $this->getMasterID();
//            $this->load->model("MdlTransaksi");
//            $tr = New MdlTransaksi();
//            $tr->setFilters(array());
//            $tr->addFilter("transaksi_id='$masterID'");
//            $rgTmp = $tr->lookupDataRegistries()->result();
//            $trTmp = $tr->lookupAll()->result();
//            $indexReg = blobDecode($trTmp[0]->indexing_registry);
//            $regID = isset($indexReg['items_komposisi']) ? $indexReg['items_komposisi'] : 0;
//            if (sizeof($rgTmp) > 0) {
//                $arrKomposisi = blobDecode($rgTmp[0]->items_komposisi);
            $arrKomposisi = $this->cCodeData;
            if (sizeof($arrKomposisi) > 0) {
                foreach ($p_ids as $pID_needle) {
                    if (isset($arrKomposisi[$pID_needle]) && (isset($arrKomposisi[$pID_needle]['produk']))) {
                        foreach ($arrKomposisi[$pID_needle]['produk'] as $ikSpec) {
                            $ikSpec["produk_id_hasil"] = $pID_needle;
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
//            }
//            else {
//                $this->load->model("Mdls/MdlProdukKomposisi");
//                $pk = new MdlProdukKomposisi();
//                $pk->addFilter("status='1'");
//                $pk->addFilter("jenis='produk'");
//                $pk->addFilter("produk_id in (" . implode(",", $p_ids) . ")");
//                $tmpPk = $pk->lookupAll()->result();
//            }

//arrPrintWebs($tmpPk);
            if (sizeof($tmpPk) > 0) {
                foreach ($tmpPk as $rowss) {
                    $row = (object)$rowss;
//                    arrPrintPink($row);
                    $pID = $row->produk_id_hasil;
                    $arrResults[$pID][$row->produk_dasar_id] = array(
                        "id" => $row->produk_dasar_id,
                        "nama" => $row->produk_dasar_nama,
                        "jml" => $row->jml,
                    );
                    if (!in_array($row->produk_dasar_id, $arrBahan)) {
                        $arrBahan[] = $row->produk_dasar_id;
                    }
                }
//                arrPrintWebs($arrBahan);
//                arrPrintWebs($arrResults);
//                mati_disini(__LINE__);
                return array(
                    "raw" => $arrBahan, //===nama2 bahan lengkap, tidak pakai indeks
                    "indexed" => $arrResults//===nama2 bahan berdasarkan indeks id produk
                );
            }
            else {
                return null;
            }
        }
        else {
            die("Parameter fungsi " . __FUNCTION__ . " memerlukan array berupa daftar produk!");
        }
    }

    public function fetchFIFO_bahan_NEW($arrBahan, $cabang_id, $gudang_id)
    {
        if (is_array($arrBahan) && sizeof($arrBahan) > 0) {
            $arrResults = array();
            $b_ids = array();
            foreach ($arrBahan as $bSpec) {
                $b_ids[] = $bSpec['id'];
            }


            $this->load->model("Mdls/MdlFifoSuppliesAssembly");
            $bb = new MdlFifoSuppliesAssembly();
            $bb->addFilter("cabang_id='$cabang_id'");
            $bb->addFilter("gudang_id='$gudang_id'");
            $bb->addFilter("produk_id in (" . implode(",", $b_ids) . ")"); // kumpulan bahan id
            $bb->addFilter("unit>0");
            $tmp = $bb->lookupAll()->result();
            if (sizeof($tmp) > 0) {
                $cnt = 0;
                foreach ($tmp as $row) {
                    $bID = $row->produk_id;
                    $arrResults[$bID][] = array(
                        "cnt" => $cnt, //==ini id tabel (pk), bukan id relasi ke bahan atau yg lain
                        "id" => $row->id, //==ini id tabel (pk), bukan id relasi ke bahan atau yg lain
                        "bahan_id" => $row->produk_id,
                        "produk_id" => $row->produk_id,
                        "nama" => $row->produk_nama,
                        "jml" => $row->unit,
                        "hpp" => $row->hpp,
                        "akum" => $row->jml_nilai,
                        "jml_ot" => $row->jml_ot,
                        "jml_nilai_ot" => $row->jml_nilai_ot,
                        //-------
                        "hpp_riil" => $row->hpp_riil,
                        "jml_nilai_riil" => $row->jml_nilai_riil,
                        "ppv_riil" => $row->ppv_riil,
                        "ppv_nilai_riil" => $row->ppv_nilai_riil,
                        //---
                        "suppliers_id" => $row->suppliers_id,
                        "suppliers_nama" => $row->suppliers_nama,
                        "oleh_id" => $row->oleh_id,
                        "oleh_nama" => $row->oleh_nama,
                        "purchase_id" => $row->purchase_id,
                        "purchase_nomer" => $row->purchase_nomer,
                        "ppn_in" => $row->ppn_in,
                        "ppn_in_nilai" => $row->ppn_in_nilai,
                    );
                    $cnt++;
                }
                return $arrResults;
            }
            else {
                return null;
            }
        }
        else {
            die("Parameter fungsi " . __FUNCTION__ . "  " . get_class($this) . " memerlukan array berupa daftar bahan! " . __LINE__);
        }
    }


}