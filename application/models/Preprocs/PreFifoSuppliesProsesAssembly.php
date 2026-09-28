<?php


class PreFifoSuppliesProsesAssembly extends CI_Model
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

    public function pair($master_id, $inParams)
    {
        if (!is_array($inParams)) {
            die("params required!");
        }
//        cekBiru("cetak inParams FIFO SUPPLIES");
//        arrPrint($inParams);
//
//


        $arrBahan = array();
        $tmpBahan = array();
        $arrProduk = array();
        $arrProdukQty = array();
        if (sizeof($inParams) > 0) {
            $cCode = "_TR_" . $inParams[0]["static"]["jenisTr"];

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

            $this->setMasterID($master_id);
            $components = $this->extractProducts($arrProduk)['indexed'];
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

            $arrFIFO_bahan_srcs = $this->fetchFIFO_bahan($arrBahan, $inParams[0]["static"]["cabang_id"], $inParams[0]["static"]["gudang_id"]);
            $arrFIFO_bahan_srcs_ori = $arrFIFO_bahan_srcs;


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

                    $this->load->model("Mdls/MdlFifoSuppliesProsesAssembly");

                    foreach ($updatePairs as $upSpec) {
                        $updateData = $upSpec;
                        unset($updateData["id"]);

                        $b = new MdlFifoSuppliesProsesAssembly();
                        $b->updateData(array("id" => $upSpec['id']), $updateData);

                        cekKuning("cetak fifo supplies");
                        cekMerah($this->db->last_query());
                    }
                }
            }


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
//mati_disini(":: masterID: $masterID ::");
//            if ($regID > 0) {
//                $rg = New MdlTransaksi();
//                $rg->setFilters(array());
//                $rg->addFilter("id='$regID'");
//                $rgTmp = $rg->lookupRegistries()->result();
//                $arrKomposisi = blobDecode($rgTmp[0]->values);
//                if(sizeof($arrKomposisi) > 0){
//
//                    foreach ($p_ids as $pID_needle) {
//                        if (isset($arrKomposisi[$pID_needle]) && (isset($arrKomposisi[$pID_needle]['produk']))) {
//                            foreach ($arrKomposisi[$pID_needle]['produk'] as $ikSpec) {
//                                $tmpPk[] = $ikSpec;
//                            }
//                        }
//                    }
//                }
//                else{
//                    $this->load->model("Mdls/MdlProdukKomposisi");
//                    $pk = new MdlProdukKomposisi();
//                    $pk->addFilter("status='1'");
//                    $pk->addFilter("jenis='produk'");
//                    $pk->addFilter("produk_id in (" . implode(",", $p_ids) . ")");
//                    $tmpPk = $pk->lookupAll()->result();
//                }
//            }
//            else {
//
//                $this->load->model("Mdls/MdlProdukKomposisi");
//                $pk = new MdlProdukKomposisi();
//                $pk->addFilter("status='1'");
//                $pk->addFilter("jenis='produk'");
//                $pk->addFilter("produk_id in (" . implode(",", $p_ids) . ")");
//                $tmpPk = $pk->lookupAll()->result();
//            }
            if (sizeof($tmpPk) > 0) {
                foreach ($tmpPk as $row) {
                    $pID = $row->produk_id;
                    $arrResults[$pID][$row->produk_dasar_id] = array(
                        "id" => $row->produk_dasar_id,
                        "nama" => $row->produk_dasar_nama,
                        "jml" => $row->jml,
                    );
                    if (!in_array($row->produk_dasar_id, $arrBahan)) {
                        $arrBahan[] = $row->produk_dasar_id;
                    }
                }
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


            $this->load->model("Mdls/MdlFifoSuppliesProsesAssembly");
            $bb = new MdlFifoSuppliesProsesAssembly();
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
                        //-----
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
}