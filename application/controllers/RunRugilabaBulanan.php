<?php


class RunRugilabaBulanan extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();

        $this->load->model("Coms/ComRugiLaba_cli2");
        $this->load->model("Coms/ComRugiLaba_cli");
        $this->load->model("Coms/ComNeraca_cli");
        $this->load->model("Mdls/MdlCabang");
        $this->load->helper("he_misc");
        $this->load->helper("he_angka");
        $this->load->library("smtpMailer");

        $this->load->model("Coms/ComRekening_cli");
        $this->load->helper("he_mass_table");
        $this->load->model("Mdls/MdlNeraca");
        $this->load->model("Mdls/MdlNeracaLajur");
        $this->load->model("Mdls/MdlFinanceConfig");
        $this->load->model("MdlTransaksi");
    }

    function index()
    {


    }

    function bulananNew()
    {

        $cr = new ComRekening_cli();
        $rl = new ComRugiLaba_cli2();
        $n = new ComNeraca_cli();
        $c = new MdlCabang();
        $em = new SmtpMailer();
        $fc = new MdlFinanceConfig();

        $emTos = array(
            "thomas" => "namakamoe@gmail.com",
            "jasmanto" => "djasmanto@gmail.com",
        );

        $this->db->trans_begin();

        $dateTimeNow = dtimeNow();
        $date = date("Y-m-01");
//        $date = "2025-02-01";// ini nanti dimatikan
        $dateDayNow = dtimeNow("d");
        $dateNow = dtimeNow("Y-m-d");
        $dateRun = 1;
//        $dateNow = 1;


        $prevBl = previousMonth();
//        $prevBl = "2025-01";// ini nanti dimatikan
        $dateLast_ex = explode("-", $prevBl);
        $periode = "bulanan";
        $bulan = $dateLast_ex[1];
        $tahun = $dateLast_ex[0];
        //---------------------------

        //---------------------------
        $bulanLast = $bulan - 1;
        if (strlen($bulanLast) == 1) {
            $bulanLast = "0$bulanLast";
        }
        if ($bulan == "01") {
            $bulanLast = 12;
            $tahunLast = $tahun - 1;
            $getDateLastNeraca = "$tahunLast-$bulanLast";
        }
        else {
            $getDateLastNeraca = "$tahun-$bulanLast";
        }
        $cekMerah = ("prevBL: $prevBl : bulan: $bulan : tahun: $tahun : lastDateNeraca: $getDateLastNeraca");
        cekMerah($cekMerah);
//mati_disini();
        $pakai_ini = 0;
        if ($pakai_ini == 1) {
            //region script hanya dirun tiap tgl satu untuk bulan sebelumnya
            if ($dateDayNow != $dateRun) {
                mati_disini("transaksi ini hanya jalan tiap tgl $dateRun disetiap bulannya, sekarang tgl $dateTimeNow <hr>" . __METHOD__ . " @" . __LINE__);
            }
            //endregion

            //region Description ceking sudah pernah dirun atau belum
            $ceks = $rl->lookupMonth($prevBl);
            if (sizeof($ceks) > 0) {
                // writeLog("generate rugi-laba","auto","cli","","","","generator rugi-laba");
                matiHere("untuk $tahun $bulan sudah runing <hr>" . __METHOD__ . " @" . __LINE__);
            }
            else {
                $em->setAddressFrom("noreply.mgkcore@gmail.com");
                $em->setAddressTo($emTos);
                $em->setSubject("noreply :: " . __METHOD__);
                $em->kirim_email("running lagi untuk $tahun $bulan @$dateTimeNow");
            }
            //endregion
        }


        $c->setFilters(array());
//        $c->addFilter("id='-1'");
        $c->addFilter("trash='0'");
        $c->addFilter("jenis='cabang'");
        $tmpCabang = $c->lookupAll()->result();
        foreach ($tmpCabang as $cSpec) {
            $cabangID = $cSpec->id;
            $pakai_inin = 0;
            if ($pakai_inin == 1) {

                $static = array(
                    "static" => array(
                        "cabang_id" => $cSpec->id,
                        "dtime" => $dateTimeNow,
                        "fulldate" => $dateNow,
                        //                    "bln" => $dateLast_ex[1],
                        //                    "thn" => $dateLast_ex[0],
                        "bln" => $bulan,
                        "thn" => $tahun,
                        "periode" => $periode,
                    ),
                );
                $filters = array(
                    "periode" => $periode,
                    "cabang_id" => $cSpec->id,
                    "bln" => $bulan,
                    "thn" => $tahun,
                );
                $filters2 = array(
                    "periode=" => $periode,
                    "cabang_id=" => $cSpec->id,
                    "date(dtime)<" => $date,
                );
                cekHitam(":: MULAI RL " . $cSpec->id . " :: " . $cSpec->nama);


                $cr->setFilters(array());
                $cr->setFilters2(array());
                $cr->setFilters($filters);
                $cr->setFilters2($filters2);
                $cr->addFilter("cabang_id='" . $cSpec->id . "'");
                if (isset($this->filters)) {
                    $setFilters = $this->filters;
                    foreach ($this->filters as $kf => $vf) {
                        $cr->addFilter("$kf='$vf'");
                    }
                }
                if (isset($this->filters2)) {
                    $cr->setFilters2($this->filters2);
                }
                $tmp = $cr->fetchAllBalances2();
                //arrPrint($tmp);
                if (sizeof($tmp) > 0) {
                    $arrRek = array();
                    $arrRekSaldo = array();
                    foreach ($tmp as $rek => $rSpec) {
                        $arrRek[$rek] = $rek;

                        $rSpec['debet'] = 0;
                        $rSpec['kredit'] = 0;
                        $arrRekSaldo[$rek] = $rSpec;
                    }

                    // membaca in/out mutasi masing-masing rekening...
                    if (sizeof($arrRek) > 0) {
                        $arrMutasi = array();
                        foreach ($arrRek as $rek) {

                            $mts = new ComRekening_cli();
                            $mts->addFilter("cabang_id='$cabangID'");
                            $mts->addFilter("transaksi_id>'0'");
                            $mts->addFilter("date(dtime)>='$tahun-$bulan-01'");
                            $mts->addFilter("date(dtime)<='$tahun-$bulan-31'");
                            $arrMutasi[$rek] = $mts->fetchMoves($rek);
//                        cekkuning(" MUTASI ". $this->db->last_query());
//                        arrPrint($arrMutasi);
//                        break;
                        }
                        cekkuning(" MUTASI " . $this->db->last_query());
                        if (sizeof($arrMutasi) > 0) {

                            $arrRekMutasi = array();
                            $arrMutasiResult = array();
                            foreach ($arrMutasi as $rek => $mSpec) {
                                foreach ($mSpec as $mmSpec) {

                                    if (!isset($arrMutasiResult[$rek]["debet"])) {
                                        $arrMutasiResult[$rek]["debet"] = 0;
                                    }
                                    if (!isset($arrMutasiResult[$rek]["kredit"])) {
                                        $arrMutasiResult[$rek]["kredit"] = 0;
                                    }

                                    $arrMutasiResult[$rek]["rek_id"] = $mmSpec->rek_id;
                                    $arrMutasiResult[$rek]["rekening"] = $mmSpec->rekening;
                                    $arrMutasiResult[$rek]["debet"] += $mmSpec->debet;
                                    $arrMutasiResult[$rek]["kredit"] += $mmSpec->kredit;
                                    $arrMutasiResult[$rek]["periode"] = $periode;

                                    $arrRekMutasi[$mmSpec->rekening] = $mmSpec->rekening;
                                }
                            }

//                        arrPrint($arrMutasiResult);
                        }
                    }
                }

                // mengambil neraca terakhir....
                $ner = new MdlNeraca();
                $ner->addFilter("cabang_id='" . $cabangID . "'");
                $ner->addFilter("periode='$periode'");
                $tmpLastNeraca = $ner->fetchBalances($getDateLastNeraca);
                cekPink($this->db->last_query());

                $tmpRekNeraca = array();
                $tmpLastNeracaResult = array();
                if (sizeof($tmpLastNeraca) > 0) {
                    foreach ($tmpLastNeraca as $lnSpec) {
                        $rek = $lnSpec->rekening;

                        if (!isset($tmpLastNeracaResult[$rek]["debet"])) {
                            $tmpLastNeracaResult[$rek]["debet"] = 0;
                        }
                        if (!isset($tmpLastNeracaResult[$rek]["kredit"])) {
                            $tmpLastNeracaResult[$rek]["kredit"] = 0;
                        }
                        if (($lnSpec->debet > 0) && ($lnSpec->kredit > 0)) {
//                        cekHitam("rekening debet dan kredit lebih dari 0 => $rek");
                            $val_detail = $lnSpec->debet - $lnSpec->kredit;
                            if ($val_detail > 0) {
                                $debet = $val_detail;
                                $kredit = 0;
                            }
                            else {
                                $debet = 0;
                                $kredit = $val_detail * -1;
                            }
                        }
                        else {
                            $debet = $lnSpec->debet;
                            $kredit = $lnSpec->kredit;
                        }
                        $tmpLastNeracaResult[$rek]["rek_id"] = $lnSpec->rek_id;
                        $tmpLastNeracaResult[$rek]["rekening"] = $lnSpec->rekening;
                        $tmpLastNeracaResult[$rek]["debet"] += $debet;
                        $tmpLastNeracaResult[$rek]["kredit"] += $kredit;
                        $tmpLastNeracaResult[$rek]["periode"] = $lnSpec->periode;

                        $tmpRekNeraca[$rek] = $rek;
                    }
                }
//            arrPrint($tmpLastNeracaResult);
                $arrLajur = array();
                if (sizeof($tmpLastNeracaResult) > 0) {
                    foreach ($tmpLastNeracaResult as $rek => $spec) {
                        $defaultPosition = detectRekDefaultPosition($rek);

                        if (($spec['debet'] > 0) && ($spec['kredit'] > 0)) {
//                        cekHitam("rekening debet dan kredit lebih dari 0 => $rek");
                            $val_detail = $spec['debet'] - $spec['kredit'];
                            if ($val_detail > 0) {
                                $debetLast = $val_detail;
                                $kreditLast = 0;
                            }
                            else {
                                $debetLast = 0;
                                $kreditLast = $val_detail * -1;
                            }
                        }
                        else {
                            $debetLast = $spec['debet'];
                            $kreditLast = $spec['kredit'];
                        }

                        if (isset($arrMutasiResult[$rek])) {
                            $debetMutasi = $arrMutasiResult[$rek]['debet'];
                            $kreditMutasi = $arrMutasiResult[$rek]['kredit'];
                        }
                        else {
                            $debetMutasi = 0;
                            $kreditMutasi = 0;
                        }


                        if ($defaultPosition == "debet") {
                            if ($debetLast > 0) {
                                $saldo_debet = $debetLast + $debetMutasi - $kreditMutasi;
//                            cekOrange("$rek -> $saldo_debet = $debetLast + $debetMutasi - $kreditMutasi");
                            }
                            else {
                                $saldo_debet = -$kreditLast + $debetMutasi - $kreditMutasi;
//                            cekLime("$rek -> $saldo_debet = -$kreditLast + $debetMutasi - $kreditMutasi");
                            }
                            $saldo_kredit = 0;
                        }
                        elseif ($defaultPosition == "kredit") {
                            if ($kreditLast > 0) {
                                $saldo_kredit = $kreditLast + $kreditMutasi - $debetMutasi;
                                $saldo_debet = 0;
//                            cekPink("$rek -> $saldo_kredit = $kreditLast + $kreditMutasi - $debetMutasi");
                            }
                            else {
                                $saldo_kredit = -$debetLast + $kreditMutasi - $debetMutasi;
                                $saldo_debet = 0;
//                            cekHere("$rek -> $saldo_kredit = -$debetLast + $kreditMutasi - $debetMutasi");
                            }
                        }
                        else {
                            mati_disini("posisi rekening $rek tidak diketahui. cek config heAccounting...");
                        }
                        $arrLajur[$rek]["rek_id"] = $spec['rek_id'];
                        $arrLajur[$rek]["rekening"] = $spec['rekening'];
                        $arrLajur[$rek]["debet"] = $saldo_debet;
                        $arrLajur[$rek]["kredit"] = $saldo_kredit;
                        $arrLajur[$rek]["periode"] = $spec['periode'];
                    }
                }
                foreach ($arrMutasiResult as $rek => $spec) {
                    if (!array_key_exists($rek, $tmpLastNeracaResult)) {
//                        cekOrange("memproses rekening $rek");
                        $debetMutasi = $spec['debet'];
                        $kreditMutasi = $spec['kredit'];
                        $debetLast = 0;
                        $kreditLast = 0;

                        $defaultPosition = detectRekDefaultPosition($rek);
                        if ($defaultPosition == "debet") {
                            $saldo_debet = $debetLast + $debetMutasi - $kreditMutasi;
                            $saldo_kredit = 0;
                        }
                        elseif ($defaultPosition == "kredit") {
                            $saldo_debet = 0;
                            $saldo_kredit = $kreditLast + $kreditMutasi - $debetMutasi;
                        }
                        else {
                            mati_disini("posisi rekening $rek tidak diketahui. cek config heAccounting...");
                        }
                        $arrLajur[$rek]["rek_id"] = $spec['rek_id'];
                        $arrLajur[$rek]["rekening"] = $spec['rekening'];
                        $arrLajur[$rek]["debet"] = $saldo_debet;
                        $arrLajur[$rek]["kredit"] = $saldo_kredit;
                        $arrLajur[$rek]["periode"] = $spec['periode'];
                    }
                }


                //region neraca terakhir...
                $totalDebet = 0;
                $totalKredit = 0;
                $str = "";
                $str .= "<table rules='all' border='1px solid black;'>";
                foreach ($tmpLastNeracaResult as $spec) {
                    $totalDebet += $spec['debet'];
                    $totalKredit += $spec['kredit'];
                    $str .= "<tr>";
                    $str .= "<td>" . $spec['rekening'] . "</td>";
                    $str .= "<td style='text-align: right;'>" . $spec['debet'] . "</td>";
                    $str .= "<td style='text-align: right;'>" . $spec['kredit'] . "</td>";
                    $str .= "</tr>";
                }
                $selisih = $totalDebet - $totalKredit;
                $str .= "<tr>";
                $str .= "<td>$selisih</td>";
                $str .= "<td style='text-align: right;'>" . $totalDebet . "</td>";
                $str .= "<td style='text-align: right;'>" . $totalKredit . "</td>";
                $str .= "</tr>";
                $str .= "</table>";
                echo "<br><b>NERACA TERAKHIR</b><br>$str";
                //endregion


                //region mutasi...
                $totalDebet = 0;
                $totalKredit = 0;
                $str = "";
                $str .= "<table rules='all' border='1px solid black;'>";
                foreach ($arrMutasiResult as $spec) {
                    $totalDebet += $spec['debet'];
                    $totalKredit += $spec['kredit'];
                    $str .= "<tr>";
                    $str .= "<td>" . $spec['rekening'] . "</td>";
                    $str .= "<td style='text-align: right;'>" . $spec['debet'] . "</td>";
                    $str .= "<td style='text-align: right;'>" . $spec['kredit'] . "</td>";
                    $str .= "</tr>";
                }
                $selisih = $totalDebet - $totalKredit;
                $str .= "<tr>";
                $str .= "<td>$selisih</td>";
                $str .= "<td style='text-align: right;'>" . $totalDebet . "</td>";
                $str .= "<td style='text-align: right;'>" . $totalKredit . "</td>";
                $str .= "</tr>";
                $str .= "</table>";
                echo "<br><b>MUTASI</b><br>$str";
                //endregion


                //region lajur...
                $totalDebet = 0;
                $totalKredit = 0;
                $str = "";
                $str .= "<table rules='all' border='1px solid black;'>";
                $arrLajurNew = array();
                foreach ($arrLajur as $rek => $spec) {
//                arrPrint($spec);
                    $rekCategory = detectRekCategory($spec['rekening']);
                    if ($spec['debet'] < 0) {
                        $spec['kredit'] = $spec['debet'] * -1;
                        $spec['debet'] = 0;
                    }
                    if ($spec['kredit'] < 0) {
                        $spec['debet'] = $spec['kredit'] * -1;
                        $spec['kredit'] = 0;
                    }
                    $arrLajurNew[$rek] = $spec;

                    $totalDebet += $spec['debet'];
                    $totalKredit += $spec['kredit'];

                    $str .= "<tr>";
                    $str .= "<td>" . $spec['rekening'] . "</td>";
                    $str .= "<td style='text-align: right;'>" . $spec['debet'] . "</td>";
                    $str .= "<td style='text-align: right;'>" . $spec['kredit'] . "</td>";
                    $str .= "</tr>";
                    $arrSpec = array(
                        "rek_id" => "",
                        "kategori" => $rekCategory,
                        "rekening" => $spec['rekening'],
                        "debet" => $spec['debet'],
                        "kredit" => $spec['kredit'],
                        "transaksi_id" => "",
                        "transaksi_no" => "",
                        "cabang_id" => $cabangID,
                        "dtime" => $dateTimeNow,
                        "author" => "",
                        "keterangan" => "",
                        "fulldate" => $dateTimeNow,
                        "bln" => $bulan,
                        "thn" => $tahun,
                        "periode" => $periode,
                    );
                    $nl = new MdlNeracaLajur();
                    $nl->addData($arrSpec, $nl->getTableName());
                    cekUngu($this->db->last_query());
                }
                $selisih = $totalDebet - $totalKredit;
                $str .= "<tr>";
                $str .= "<td>$selisih</td>";
                $str .= "<td style='text-align: right;'>" . $totalDebet . "</td>";
                $str .= "<td style='text-align: right;'>" . $totalKredit . "</td>";
                $str .= "</tr>";
                $str .= "</table>";
                echo "<br><b>LAJUR</b><br>$str";
                //endregion

            }
            else {
                $static = array(
                    "static" => array(
                        "cabang_id" => $cabangID,
                        "dtime" => $dateTimeNow,
                        "fulldate" => $dateNow,
                        "bln" => $bulan,
                        "thn" => $tahun,
                        "periode" => $periode,
                    ),
                );
                $filters = array(
                    "periode" => $periode,
                    "cabang_id" => $cabangID,
                    "bln" => $bulan,
                    "thn" => $tahun,
                );
                $filters2 = array(
                    "periode=" => $periode,
                    "cabang_id=" => $cabangID,
//                    "date(dtime)<=" => $date2,
                    "bln" => $bulan,
                    "thn" => $tahun,
                );
                $cr->setFilters(array());
                $cr->setFilters2(array());
                $cr->setFilters($filters);
                $cr->setFilters2($filters2);
                $cr->addFilter("cabang_id='" . $cabangID . "'");
                if (isset($this->filters)) {
                    $setFilters = $this->filters;
                    foreach ($this->filters as $kf => $vf) {
                        $cr->addFilter("$kf='$vf'");
                    }
                }
                if (isset($this->filters2)) {
                    $cr->setFilters2($this->filters2);
                }
                $tmp = $cr->fetchAllBalances2();
                showlast_query("biru");
                $arrLajurNew = array();
                foreach ($tmp as $spec) {
                    $rek = $spec['rekening'];
                    if (!in_array($rek, $arrRekBlacklist)) {
                        $arrLajurNew[$rek] = $spec;
                    }
                }

                if($cabangID == "-1"){
                    $debetPassTotal = 0;
                    $kreditPassTotal = 0;
                    $rekByPass = array(
                        "5030",
                        "8020",
                        "8030",
                    );
                    $rekByPassNol = array(
                        "8040",
                        "8050",
                    );
                    foreach ($rekByPassNol as $rekNol){
                        if(isset($arrLajurNew[$rekNol])){
                            $arrLajurNew[$rekNol] = NULL;
                            unset($arrLajurNew[$rekNol]);
                        }
                    }
                    foreach ($rekByPass as $rekPass){
                        $debetPass = isset($arrLajurNew[$rekPass]["debet"]) ? $arrLajurNew[$rekPass]["debet"] : 0;
                        $kreditPass = isset($arrLajurNew[$rekPass]["kredit"]) ? $arrLajurNew[$rekPass]["kredit"] : 0;
                        $debetPassTotal += $debetPass;
                        $kreditPassTotal += $kreditPass;

                        $arrLajurNew[$rekPass] = NULL;
                        unset($arrLajurNew[$rekPass]);
                    }
                    $tambahanRekPass = $debetPassTotal - $kreditPassTotal;
                    cekHere("[tambahanRekPass: $tambahanRekPass] [$debetPassTotal - $kreditPassTotal]");
                    if($tambahanRekPass < 0){
                        if(isset($arrLajurNew["7010150"]["kredit"]) && ($arrLajurNew["7010150"]["kredit"] > 0)){
                            $arrLajurNew["7010150"]["kredit"] = $arrLajurNew["7010150"]["kredit"] + ($tambahanRekPass *-1);
                        }
                        elseif(isset($arrLajurNew["7010150"]["debet"]) && ($arrLajurNew["7010150"]["debet"] > 0)){
                            $arrLajurNew["7010150"]["debet"] = $arrLajurNew["7010150"]["debet"] + $tambahanRekPass;
                        }
                        else{
                            $arrLajurNew["7010150"]["kredit"] = $arrLajurNew["7010150"]["kredit"] + ($tambahanRekPass *-1);
                        }
                    }
                    else{
                        if(isset($arrLajurNew["7010150"]["kredit"]) && ($arrLajurNew["7010150"]["kredit"] > 0)){
                            $arrLajurNew["7010150"]["kredit"] = $arrLajurNew["7010150"]["kredit"] - $tambahanRekPass;
                        }
                        elseif(isset($arrLajurNew["7010150"]["debet"]) && ($arrLajurNew["7010150"]["debet"] > 0)){
                            $arrLajurNew["7010150"]["debet"] = $arrLajurNew["7010150"]["debet"] + $tambahanRekPass;
                        }
                        else{
                            $arrLajurNew["7010150"]["kredit"] = $arrLajurNew["7010150"]["kredit"] - $tambahanRekPass;
                        }
                    }
                }

            }


            $rl->setFilters2($filters2);
            $rl->setFilters($filters);
            $rl->pairNoCut($static, $arrLajurNew);
            $resultRL = $rl->execNoCut();


//            cekHitam(":: MULAI NERACA " . $cSpec->id . " :: " . $cSpec->nama);
            $n->setFilters2($filters2);
            $n->setFilters($filters);
            $n->pairNoCut($static, $resultRL['neraca']);
            $resultNeraca = $n->execNoCut();

//            mati_disini(":: CLOSE NERACA " . $cSpec->id . " :: " . $cSpec->nama);
            if (sizeof($resultNeraca) > 0) {
//                cekHere("MASUK DISINI...");
//                arrPrintPink($resultNeraca);

                foreach ($resultNeraca as $rek => $rSpec) {
                    if (($rSpec["debet"] > 0) && ($rSpec["kredit"] > 0)) {
                        $def_position = detectRekDefaultPosition($rek);
                        switch ($def_position) {
                            case "debet":
                                $netto = $rSpec["debet"] - $rSpec["kredit"];
                                if ($netto > 0) {
                                    $resultNeraca[$rek]["debet"] = $netto;
                                    $resultNeraca[$rek]["kredit"] = 0;
                                }
                                else {
                                    $resultNeraca[$rek]["debet"] = 0;
                                    $resultNeraca[$rek]["kredit"] = $netto * -1;
                                }

                                break;
                            case "kredit":
                                $netto = $rSpec["kredit"] - $rSpec["debet"];
                                if ($netto > 0) {
                                    $resultNeraca[$rek]["debet"] = 0;
                                    $resultNeraca[$rek]["kredit"] = $netto;
                                }
                                else {
                                    $resultNeraca[$rek]["debet"] = $netto * -1;
                                    $resultNeraca[$rek]["kredit"] = 0;
                                }
                                break;
                        }
                    }
                }

                foreach ($resultNeraca as $i => $spec) {
//                    arrPrintPink($spec);
                    //------
                    $pakai_ini = 0;
                    if ($pakai_ini == 1) {
                        $cr = new ComRekening_cli();
                        $cr->addFilter("rekening='" . $spec['rekening'] . "'");
                        $cr->addFilter("thn='" . date("Y") . "'");
                        $cr->addFilter("bln='" . date("m") . "'");
                        $cr->addFilter("periode='$periode'");
                        $cr->addFilter("cabang_id='" . $spec['cabang_id'] . "'");
                        $crTmp = $cr->lookupAll()->result();
                        showLast_query("biru");
                        if (sizeof($crTmp) > 0) {
                            //update
                            $data = array(
                                "debet" => $spec['debet'],
                                "kredit" => $spec['kredit'],
                            );
                            $where = array(
                                "id" => $crTmp[0]->id
                            );
                            $cr->updateData($where, $data);
                            showLast_query("orange");
                        }
                        else {
                            // insert
                            $data = array(
                                "debet" => $spec['debet'],
                                "kredit" => $spec['kredit'],
                                "rekening" => $spec['rekening'],
                                "cabang_id" => $spec['cabang_id'],
                                "cabang_nama" => isset($spec['cabang_nama']) ? $spec['cabang_nama'] : "",
                                "periode" => "$periode",
                                "thn" => date("Y"),
                                "bln" => date("m"),
                                "tgl" => date("d"),
                                "dtime" => date("Y-m-d H:i:s"),
                                "fulldate" => date("Y-m-d"),
                            );
                            $cr->addData($data);
                            showLast_query("hijau");
                        }
                    }
                    else {
                        // select dulu...
                        $where_nr = array(
                            "rekening" => $spec['rekening'],
                            "cabang_id" => $spec['cabang_id'],
                            "periode" => "$periode",
                            "thn" => date("Y"),
                            "bln" => date("m"),
                        );
                        $cr->setFilters(array());
                        foreach ($where_nr as $kf => $vf) {
                            $cr->addFilter("$kf='$vf'");

                        }
                        $crTmp = $cr->lookupAll()->result();
                        if (count($crTmp) == 0) {

                            // insert
                            $data = array(
                                "debet" => $spec['debet'],
                                "kredit" => $spec['kredit'],
                                "rekening" => $spec['rekening'],
                                "cabang_id" => $spec['cabang_id'],
                                "cabang_nama" => isset($spec['cabang_nama']) ? $spec['cabang_nama'] : "",
                                "periode" => "$periode",
                                "thn" => date("Y"),
                                "bln" => date("m"),
                                "tgl" => date("d"),
                                "dtime" => date("Y-m-d H:i:s"),
                                "fulldate" => date("Y-m-d"),
                            );
                            $cr->addData($data);
                            showLast_query("hijau");
                        }
                    }
                }
            }
            else {
                cekhitam("tidak ada result neraca cache");
            }

        }


        // region simpan config view rl dan neraca
        $categoryRL = $this->config->item("categoryRL") != NULL ? $this->config->item("categoryRL") : array();
        $accountRekeningSort = $this->config->item("accountRekeningSort") != NULL ? $this->config->item("accountRekeningSort") : array();
        $accountStructure = $this->config->item("accountStructure") != NULL ? $this->config->item("accountStructure") : array();
        $arrConfig = array(
            "categoryRL" => array(
                "param" => "categoryRL",
                "values" => blobEncode($categoryRL),
                "bln" => $bulan,
                "thn" => $tahun,
                "periode" => $periode,
            ),
            "accountRekeningSort" => array(
                "param" => "accountRekeningSort",
                "values" => blobEncode($accountRekeningSort),
                "bln" => $bulan,
                "thn" => $tahun,
                "periode" => $periode,
            ),
            "accountStructure" => array(
                "param" => "accountStructure",
                "values" => blobEncode($accountStructure),
                "bln" => $bulan,
                "thn" => $tahun,
                "periode" => $periode,
            ),
        );

        foreach ($arrConfig as $fcSpec) {
            $fc->addData($fcSpec);
            cekHijau($this->db->last_query());
        }
        // endregion


//        mati_disini("CILUKBAAA.... TESTING LAGI... HI HI HI  BELUM DICOMMIT");


        if ($this->db->trans_status() === FALSE) {
            $dbError = method_exists($this->db, "error") ? $this->db->error() : array("code" => 0, "message" => "");
            $this->db->trans_rollback();
            if (is_array($dbError) && isset($dbError["code"]) && intval($dbError["code"]) !== 0) {
                $errCode = isset($dbError["code"]) ? $dbError["code"] : 0;
                $errMsg = isset($dbError["message"]) ? $dbError["message"] : "unknown";
                cekMerah("rollback DB error [$errCode] $errMsg");
                log_message("error", __METHOD__ . " rollback DB error [$errCode] $errMsg");
            }
            else {
                cekMerah("rollback karena trans_status FALSE tanpa detail DB error");
                log_message("error", __METHOD__ . " rollback karena trans_status FALSE tanpa detail DB error");
            }
        }
        else {
            $this->db->trans_commit();
        }


        $pakai_ini = 0;
        if ($pakai_ini == 1) {
            $em->setAddressFrom("noreply.mgkcore@gmail.com");
            $em->setAddressTo($emTos);
            $em->setSubject("noreply :: " . __METHOD__);
            $em->kirim_email("running (tahunan) untuk $tahun $bulan @$dateTimeNow");
        }

        cekHijau("<h1>done</h1>");

        // writeLog("generate rugi-laba","auto","cli","","","","generator rugi-laba");

    }

}
