<?php


class LaporanKeuangan
{
//    protected $toko_id;
    public function __construct()
    {

        $this->ci =& get_instance();
        $accounts = $this->ci->config->item("accountStructure");
        $this->catException = $this->ci->config->item('accountCatExceptions') != null ? $this->ci->config->item('accountCatExceptions') : array();
        foreach ($accounts as $accountSpec) {
            foreach ($accountSpec as $account_rekening) {
                $rekening_replacer0 = str_replace(" ", "_", $account_rekening);
                $rekening_replacer1 = str_replace("(", "_", $rekening_replacer0);
                $rekening_replacer2 = str_replace(")", "_", $rekening_replacer1);
                $tabel_master = "__rek_master__" . $rekening_replacer2;
                if ($this->ci->db->table_exists($tabel_master)) {
                    $this->accounts[$account_rekening] = $tabel_master;
                }
            }
        }

        $this->ci->load->helper("he_misc");
        $this->ci->load->helper("he_angka");
        $this->ci->load->library("smtpMailer");
        $this->ci->load->helper("he_mass_table");
        $this->ci->load->model("MdlTransaksi");

        $this->ci->load->model("Coms/ComRugiLaba_cli2");
        $this->ci->load->model("Coms/ComRugiLaba_cli");
        $this->ci->load->model("Coms/ComNeraca_cli");
        $this->ci->load->model("Coms/ComRekening_cli");
        $this->ci->load->model("Coms/ComJurnal");
        $this->ci->load->model("Coms/ComRekening");

        $this->ci->load->model("Mdls/MdlCabang");
        $this->ci->load->model("Mdls/MdlRekeningKoreksi");
        $this->ci->load->model("Mdls/MdlNeraca");
        $this->ci->load->model("Mdls/MdlRugilaba");
        $this->ci->load->model("Mdls/MdlNeracaLajur");
        $this->ci->load->model("Mdls/MdlFinanceConfig");
        //-----
        $this->ci->load->model("Mdls/MdlNeracaAdjTmp");
        $this->ci->load->model("Mdls/MdlNeracaAdj");
        $this->ci->load->model("Mdls/MdlRugilabaAdj");
        //-----


        $this->table = array(
            "rugilaba" => "rugilaba",
            "neraca" => "neraca",
            "lajur" => "neraca_lajur",
        );
        $this->accountChilds = $this->ci->config->item("accountChilds") != null ? $this->ci->config->item("accountChilds") : array();
//        $accountAlias = $this->ci->config->item("accountAlias") != null ? $this->ci->config->item("accountAlias") : array();
        $this->accountException = $this->ci->config->item("accountRekOppositeExceptions") != null ? $this->ci->config->item("accountRekOppositeExceptions") : array();
        $this->accountCatException = $this->ci->config->item("accountCatOppositeExceptions") != null ? $this->ci->config->item("accountCatOppositeExceptions") : array();
//        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->ci->config->item("accountRekeningSort");
        $this->categoryRL = $this->ci->config->item("categoryRL");// ini masih setting dari script
        $this->accountAlias = fetchAccountStructureAlias();
        $this->rekException = array("1001");
        $this->accountNetto = $this->ci->config->item("accountNetto") != null ? $this->ci->config->item("accountNetto") : array();
    }


    //KOREKSI LAPORAN KEUANGAN BULANAN---------------------------
    public function generateKeuanganBulanan($cabangID, $periode, $date, $rekening_tambahan, $trID = "", $trNomer = "")
    {
        $this->ci->load->model("Coms/ComRugiLaba_cli2");
        $this->ci->load->model("Coms/ComRugiLaba_cli");
        $this->ci->load->model("Coms/ComNeraca_cli");
        $this->ci->load->model("Mdls/MdlCabang");
        $this->ci->load->model("Mdls/MdlRekeningKoreksi");
        $this->ci->load->helper("he_misc");
        $this->ci->load->library("smtpMailer");

        $this->ci->load->model("Coms/ComRekening_cli");
        $this->ci->load->helper("he_mass_table");
        $this->ci->load->model("Mdls/MdlNeraca");
        $this->ci->load->model("Mdls/MdlRugilaba");
        $this->ci->load->model("Mdls/MdlNeracaLajur");
        $this->ci->load->model("Mdls/MdlFinanceConfig");
        $this->ci->load->model("MdlTransaksi");
        $cr = New ComRekening_cli();
        $rl = New ComRugiLaba_cli2();
        $n = New ComNeraca_cli();
        $c = New MdlCabang();
        $em = New SmtpMailer();
        $fc = New MdlFinanceConfig();

        $emTos = array(
            "thomas" => "namakamoe@gmail.com",
            "jasmanto" => "djasmanto@gmail.com",
        );


        $dateTimeNow = dtimeNow();
//        $date = date("Y-m-01");
        $dateNow = dtimeNow("d");
//        $dateRun = 1;


//        $prevBl = previousMonth();
//        $dateLast_ex = explode("-", $prevBl);
        $dateLast_ex = explode("-", $date);
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
        $cekMerah = ("prevBL: $date : bulan: $bulan : tahun: $tahun : lastDateNeraca: $getDateLastNeraca");
        cekMerah($cekMerah);
//        mati_disini(__FUNCTION__);
//arrPrintWebs($rekening_tambahan);

        $c->setFilters(array());
        $c->addFilter("id='$cabangID'");
        $c->addFilter("trash='0'");
        $c->addFilter("jenis='cabang'");
        $tmpCabang = $c->lookupAll()->result();
        showLast_query("biru");
        foreach ($tmpCabang as $cSpec) {
            $cabangID = $cSpec->id;

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
                    "tipe" => "koreksi",
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

            //-----------------------------
            $rk = New MdlRekeningKoreksi();
            $rk->setFilters(array());
            $rk->addFilter("status='1'");
            $rk->addFilter("cabang_id='$cabangID'");
            $rk->addFilter("koreksi_periode='$periode'");
            $rk->addFilter("year(koreksi_dtime)='$tahun'");
            $rk->addFilter("month(koreksi_dtime)='$bulan'");
            $rkTmp = $rk->lookupAll()->result();
            showLast_query("biru");
            if (sizeof($rkTmp) > 0) {
                foreach ($rkTmp as $rkSpec) {
                    $koreksi_number = $rkSpec->koreksi_number;
                }
            }
            else {
                $koreksi_number = 0;
            }

            //-----------------------------
            $arrWhere = array(
                "cabang_id" => $cabangID,
                "periode" => "bulanan",
                "bln" => $bulan,
                "thn" => $tahun,
                "trash" => 0,
            );
            $arrData = array(
                "trash" => 1,
            );
            $nrc = New MdlNeraca();
            foreach ($arrWhere as $fk => $fv) {
                $nrc->addFilter("$fk=$fv");
            }
            $nrcTmp = $nrc->lookupAll()->result();
            if (sizeof($nrcTmp) > 0) {
                $nrc->setFilters(array());
                $nrc->updateData($arrWhere, $arrData);
                showLast_query("orange");
            }

            $rlb = New MdlRugilaba();
            foreach ($arrWhere as $fk => $fv) {
                $rlb->addFilter("$fk=$fv");
            }
            $rlbTmp = $rlb->lookupAll()->result();
            if (sizeof($rlbTmp) > 0) {
                $rlb->setFilters(array());
                $rlb->updateData($arrWhere, $arrData);
                showLast_query("orange");
            }
            //-----------------------------

//mati_disini(__CLASS__);
            $pakai_ini = 0;
            if ($pakai_ini == 1) {

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

                            $mts = New ComRekening_cli();
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

            }


            //region lajur...
            $arrLajur = array();
            $nl = New MdlNeracaLajur();
            $nl->addFilter("cabang_id='$cabangID'");
            $nl->addFilter("status='1'");
            $nl->addFilter("trash='0'");
            $nl->addFilter("bln='$bulan'");
            $nl->addFilter("thn='$tahun'");
            $nl->addFilter("periode='$periode'");
            $nlTmp = $nl->lookupAll()->result();
//            showLast_query("hijau");

            foreach ($nlTmp as $spec) {
                $rek = $spec->rekening;
                $debet = $spec->debet;
                $kredit = $spec->kredit;

                $defaultPosition = detectRekDefaultPosition($rek);
                if (($spec->debet > 0) && ($spec->kredit > 0)) {
                    $val_detail = $spec->debet - $spec->kredit;
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
                    $debetLast = $spec->debet;
                    $kreditLast = $spec->kredit;
                }

                if (isset($rekening_tambahan[$rek])) {
                    $debetMutasi = $rekening_tambahan[$rek]['debet'];
                    $kreditMutasi = $rekening_tambahan[$rek]['kredit'];
                }
                else {
                    $debetMutasi = 0;
                    $kreditMutasi = 0;
                }

                if ($defaultPosition == "debet") {
                    if ($debetLast > 0) {
                        $saldo_debet = $debetLast + $debetMutasi - $kreditMutasi;
//                            cekOrange("$rek -> $saldo_debet = $debetLast + $debetMutasi - $kreditMutasi");
                        $saldo_kredit = 0;
                    }
                    else {
                        $saldo_debet = -$kreditLast + $debetMutasi - $kreditMutasi;
//                            cekLime("$rek -> $saldo_debet = -$kreditLast + $debetMutasi - $kreditMutasi");
                        $saldo_kredit = 0;
                    }
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

                $arrLajur[$rek]["rek_id"] = $spec->rek_id;
                $arrLajur[$rek]["rekening"] = $spec->rekening;
                $arrLajur[$rek]["debet"] = $saldo_debet;
                $arrLajur[$rek]["kredit"] = $saldo_kredit;
                $arrLajur[$rek]["periode"] = $spec->periode;
            }
            foreach ($rekening_tambahan as $rek => $spec) {
                if (!array_key_exists($rek, $arrLajur)) {
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
//            arrPrintPink($arrLajur);
//            mati_disini(__FUNCTION__);

            // menghapus/menonaktifkan neraca lajur
            $nl = New MdlNeracaLajur();
            foreach ($arrWhere as $fk => $fv) {
                $nl->addFilter("$fk=$fv");
            }
            $nrcTmp = $nl->lookupAll()->result();
            if (sizeof($nrcTmp) > 0) {
                $nl->setFilters(array());
                $nl->updateData($arrWhere, $arrData);
                showLast_query("orange");
            }

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
                    "tipe" => "koreksi",
                );
                $nl = New MdlNeracaLajur();
                $nl->addData($arrSpec, $nl->getTableName());
                showLast_query("ungu");
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


            $rl->setFilters2($filters2);
            $rl->setFilters($filters);
            $rl->pairNoCut($static, $arrLajurNew);
            $resultRL = $rl->execNoCut();
//            arrPrint($resultRL);


//            cekHitam(":: MULAI NERACA " . $cSpec->id . " :: " . $cSpec->nama);
            $n->setFilters2($filters2);
            $n->setFilters($filters);
            $n->pairNoCut($static, $resultRL['neraca']);
            $n->execNoCut();


//            mati_disini(":: CLOSE NERACA " . $cSpec->id . " :: " . $cSpec->nama);
            cekUngu(":: menulis ke tabel REKENING_KOREKSI ::");
            $rlk = New ComRugiLaba_cli2();
            $rlk->setFilters2($filters2);
            $rlk->setFilters($filters);
            $rlk->pairNoCut_view($static, $rekening_tambahan);
            $resultRLK = $rlk->execNoCut_view();
//arrPrintPink($resultRLK);

            $nk = New ComNeraca_cli();
            $nk->setFilters2($filters2);
            $nk->setFilters($filters);
            $nk->pairNoCut_view($static, $resultRLK['neraca']);
            $resultNK = $nk->execNoCut_view();
//arrPrintWebs($resultNK);

            $rk = New MdlRekeningKoreksi();
            if (sizeof($resultRLK['rugilaba']) > 0) {
                foreach ($resultRLK['rugilaba'] as $specc) {
                    $data = array(
                        "kategori" => $specc['kategori'],
                        "rekening" => $specc['rekening'],
                        "debet" => $specc['debet'],
                        "kredit" => $specc['kredit'],
                        "transaksi_id" => $trID,
                        "transaksi_no" => $trNomer,
                        "cabang_id" => $specc['cabang_id'],
                        "dtime" => $specc['dtime'],
                        "status" => 1,
                        "trash" => 0,
                        "periode" => $periode,
                        "tipe" => $specc['tipe'],
//                        "koreksi_dtime" => $specc['dtime'],
                        "koreksi_dtime" => $date,
                        "koreksi_periode" => $periode,
                        "koreksi_number" => $koreksi_number + 1,
                    );
                    $rk->setFilters(array());
                    $rk->addData($data);
                    showLast_query("lime");
                }
            }
            if (sizeof($resultNK) > 0) {
                foreach ($resultNK as $specc) {
                    $data = array(
                        "kategori" => $specc['kategori'],
                        "rekening" => $specc['rekening'],
                        "debet" => $specc['debet'],
                        "kredit" => $specc['kredit'],
                        "transaksi_id" => $trID,
                        "transaksi_no" => $trNomer,
                        "cabang_id" => $specc['cabang_id'],
                        "dtime" => $specc['dtime'],
                        "status" => 1,
                        "trash" => 0,
                        "periode" => $periode,
                        "tipe" => $specc['tipe'],
//                        "koreksi_dtime" => $specc['dtime'],
                        "koreksi_dtime" => $date,
                        "koreksi_periode" => $periode,
                        "koreksi_number" => $koreksi_number + 1,
                    );
                    $rk->setFilters(array());
                    $rk->addData($data);
                    showLast_query("lime");
                }
            }
        }


        // region simpan config view rl dan neraca
        $categoryRL = $this->ci->config->item("categoryRL") != NULL ? $this->ci->config->item("categoryRL") : array();
        $accountRekeningSort = $this->ci->config->item("accountRekeningSort") != NULL ? $this->ci->config->item("accountRekeningSort") : array();
        $accountStructure = $this->ci->config->item("accountStructure") != NULL ? $this->ci->config->item("accountStructure") : array();
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
//            showLast_query("hijau");
        }
        // endregion


    }


    // periode = bulanan, tahunan
    public function getNeraca($cabangID, $periode, $defaultDate)
    {
        $ner = New MdlNeraca();
        if (is_array($cabangID)) {
            if (sizeof($cabangID) > 0) {
                $ner->addFilter("cabang_id in ('" . implode("','", $cabangID) . "')");
            }
            else {
                // tidak ada filter cabang
            }
        }
        else {
            $ner->addFilter("cabang_id='$cabangID'");
        }
        $ner->addFilter("periode='$periode'");
        $tmp = $ner->fetchBalances($defaultDate);

        $oldDate = "";
        $last_date = $defaultDate;

        $categories = array();
        $rekenings = array();
//        $rekeningsCab = array();
//        $rekeningsKonsolidasi = array();
        $rekeningsName = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $cabID = $row->cabang_id;
                $defPos = detectRekDefaultPosition($row->rekening);
                $rek_alias = isset($this->accountAlias[$row->rekening]) ? $this->accountAlias[$row->rekening] : $row->rekening;
                if (strlen($row->kategori) > 1) {
                    if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {
                        if (!in_array($row->kategori, $categories)) {
                            $categories[] = $row->kategori;
                        }
                        // rekenings cabang selected
                        if (!isset($rekenings[$row->kategori])) {
                            $rekenings[$row->kategori] = array();
                        }
                        // rekening index per-cabang
                        if (!isset($rekeningsCab[$cabID][$row->kategori])) {
                            $rekeningsCab[$cabID][$row->kategori] = array();
                        }
                        // rekening konsolidasi
                        if (!isset($rekeningsKonsolidasi[$row->kategori])) {
                            $rekeningsKonsolidasi[$row->kategori] = array();
                        }


                        if (in_array($row->rekening, $this->accountException)) {
//                            $tmpCol = array(
//                                "rek_id" => "",
//                                "rekening" => $row->rekening,
////                                "debet" => ($row->kredit * -1),
////                                "kredit" => ($row->debet * -1),
//                                "link" => "",
//                            );
                            $debet = ($row->kredit * -1);
                            $kredit = ($row->debet * -1);

                        }
                        else {
                            switch ($defPos) {
                                case "debet":
                                    if ($row->kredit > 0) {
                                        $debet = $row->kredit * -1;
                                        $kredit = 0;
                                    }
                                    else {
                                        $debet = $row->debet;
                                        $kredit = $row->kredit;
                                    }
                                    break;
                                case "kredit":
                                    if ($row->debet > 0) {
                                        $debet = 0;
                                        $kredit = $row->debet * -1;
                                    }
                                    else {
                                        $debet = $row->debet;
                                        $kredit = $row->kredit;
                                    }
                                    break;
                                default:
                                    $debet = $row->debet;
                                    $kredit = $row->kredit;
                                    break;
                            }

//                            $tmpCol = array(
//                                "rek_id" => "",
//                                "rekening" => $row->rekening,
////                                "debet" => $debet,
////                                "kredit" => $kredit,
//                                "link" => "",
//                            );
                        }
                        $link = "";
                        if (isset($this->accountChilds[$row->rekening])) {
                            $link .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $this->accountChilds[$row->rekening] . "/" . $row->rekening . "/" . $row->periode . "?date=$oldDate'><span class='fa fa-clone'></span></a>";
                        }
                        $link .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "'><span class='glyphicon glyphicon-time'></span></a></span>";


                        if (sizeof($this->accountCatException) > 0) {
                            foreach ($this->accountCatException as $cat => $c_rekName) {
                                if (in_array($row->rekening, $c_rekName)) {
                                    $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                    //region cabang selected
                                    $rekenings[$cat][$row->rekening]["rek_id"] = "";
                                    $rekenings[$cat][$row->rekening]["rekening"] = $row->rekening;
                                    $rekenings[$cat][$row->rekening]["rekening_alias"] = $rek_alias;

                                    if (!isset($rekenings[$cat][$row->rekening]["debet"])) {
                                        $rekenings[$cat][$row->rekening]["debet"] = 0;
                                    }
                                    if (!isset($rekenings[$cat][$row->rekening]["kredit"])) {
                                        $rekenings[$cat][$row->rekening]["kredit"] = 0;
                                    }
                                    $rekenings[$cat][$row->rekening]["debet"] += $debet;
                                    $rekenings[$cat][$row->rekening]["kredit"] += $kredit;
                                    $rekenings[$cat][$row->rekening]["link"] = $link;
                                    //endregion

                                    //region index per-cabang
                                    $rekeningsCab[$cabID][$cat][$row->rekening]["rek_id"] = "";
                                    $rekeningsCab[$cabID][$cat][$row->rekening]["rekening"] = $row->rekening;
                                    $rekeningsCab[$cabID][$cat][$row->rekening]["rekening_alias"] = $rek_alias;

                                    if (!isset($rekeningsCab[$cabID][$cat][$row->rekening]["debet"])) {
                                        $rekeningsCab[$cabID][$cat][$row->rekening]["debet"] = 0;
                                    }
                                    if (!isset($rekeningsCab[$cabID][$cat][$row->rekening]["kredit"])) {
                                        $rekeningsCab[$cabID][$cat][$row->rekening]["kredit"] = 0;
                                    }
                                    $rekeningsCab[$cabID][$cat][$row->rekening]["debet"] += $debet;
                                    $rekeningsCab[$cabID][$cat][$row->rekening]["kredit"] += $kredit;
                                    $rekeningsCab[$cabID][$cat][$row->rekening]["link"] = $link;
                                    //endregion

                                    //region konsolidasi
                                    $rekeningsKonsolidasi[$cat][$row->rekening]["rek_id"] = "";
                                    $rekeningsKonsolidasi[$cat][$row->rekening]["rekening"] = $row->rekening;
                                    $rekeningsKonsolidasi[$cat][$row->rekening]["rekening_alias"] = $rek_alias;

                                    if (!isset($rekeningsKonsolidasi[$cat][$row->rekening]["debet"])) {
                                        $rekeningsKonsolidasi[$cat][$row->rekening]["debet"] = 0;
                                    }
                                    if (!isset($rekeningsKonsolidasi[$cat][$row->rekening]["kredit"])) {
                                        $rekeningsKonsolidasi[$cat][$row->rekening]["kredit"] = 0;
                                    }
                                    $rekeningsKonsolidasi[$cat][$row->rekening]["debet"] += $debet;
                                    $rekeningsKonsolidasi[$cat][$row->rekening]["kredit"] += $kredit;
                                    $rekeningsKonsolidasi[$cat][$row->rekening]["link"] = $link;
                                    //endregion
                                }
                                else {
                                    $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                    //region cabang selected
                                    $rekenings[$row->kategori][$row->rekening]["rek_id"] = "";
                                    $rekenings[$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                    $rekenings[$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                    if (!isset($rekenings[$row->kategori][$row->rekening]["debet"])) {
                                        $rekenings[$row->kategori][$row->rekening]["debet"] = 0;
                                    }
                                    if (!isset($rekenings[$row->kategori][$row->rekening]["kredit"])) {
                                        $rekenings[$row->kategori][$row->rekening]["kredit"] = 0;
                                    }
                                    $rekenings[$row->kategori][$row->rekening]["debet"] += $debet;
                                    $rekenings[$row->kategori][$row->rekening]["kredit"] += $kredit;
                                    $rekenings[$row->kategori][$row->rekening]["link"] = $link;
                                    //endregion

                                    //region index per-cabang
                                    $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rek_id"] = "";
                                    $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                    $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                    if (!isset($rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"])) {
                                        $rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"] = 0;
                                    }
                                    if (!isset($rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"])) {
                                        $rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"] = 0;
                                    }
                                    $rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"] += $debet;
                                    $rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"] += $kredit;
                                    $rekeningsCab[$cabID][$row->kategori][$row->rekening]["link"] = $link;
                                    //endregion

                                    //region konsolidasi
                                    $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rek_id"] = "";
                                    $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                    $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                    if (!isset($rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"])) {
                                        $rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"] = 0;
                                    }
                                    if (!isset($rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"])) {
                                        $rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"] = 0;
                                    }
                                    $rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"] += $debet;
                                    $rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"] += $kredit;
                                    $rekeningsKonsolidasi[$row->kategori][$row->rekening]["link"] = $link;
                                    //endregion
                                }
                            }
                        }
                        else {
                            //region cabang selected
                            $rekenings[$row->kategori][$row->rekening]["rek_id"] = "";
                            $rekenings[$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                            $rekenings[$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                            if (!isset($rekenings[$row->kategori][$row->rekening]["debet"])) {
                                $rekenings[$row->kategori][$row->rekening]["debet"] = 0;
                            }
                            if (!isset($rekenings[$row->kategori][$row->rekening]["kredit"])) {
                                $rekenings[$row->kategori][$row->rekening]["kredit"] = 0;
                            }
                            $rekenings[$row->kategori][$row->rekening]["debet"] += $debet;
                            $rekenings[$row->kategori][$row->rekening]["kredit"] += $kredit;
                            $rekenings[$row->kategori][$row->rekening]["link"] = $link;
                            //endregion

                            //region index per-cabang
                            $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rek_id"] = "";
                            $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                            $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                            if (!isset($rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"])) {
                                $rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"] = 0;
                            }
                            if (!isset($rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"])) {
                                $rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"] = 0;
                            }
                            $rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"] += $debet;
                            $rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"] += $kredit;
                            $rekeningsCab[$cabID][$row->kategori][$row->rekening]["link"] = $link;
                            //endregion

                            //region konsolidasi
                            $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rek_id"] = "";
                            $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                            $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                            if (!isset($rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"])) {
                                $rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"] = 0;
                            }
                            if (!isset($rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"])) {
                                $rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"] = 0;
                            }
                            $rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"] += $debet;
                            $rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"] += $kredit;
                            $rekeningsKonsolidasi[$row->kategori][$row->rekening]["link"] = $link;
                            //endregion
                        }
                    }
                }

//                $last_date = "$row->thn-$row->bln";
            }
        }

        $arrResultRekening = array(
            "rekenings" => $rekenings,
            "rekeningsCabang" => isset($rekeningsCab) ? $rekeningsCab : array(),
            "rekeningsKonsolidasi" => isset($rekeningsKonsolidasi) ? $rekeningsKonsolidasi : array(),
            "rekeningsName" => $rekeningsName,
            "categories" => $categories,
        );

//        arrPrintHijau($arrResultRekening["rekenings"]);
//        arrPrintPink($arrResultRekening["rekeningsCabang"]);
//        arrPrintWebs($arrResultRekening["rekeningsKonsolidasi"]);
        return $arrResultRekening;
    }

    public function getNeracaTtm($cabangID, $periode, $defaultDate)
    {
        foreach ($defaultDate as $tahun_ex) {
            $ner = new MdlNeraca();
            $ner->addFilter("periode='$periode'");
            if (is_array($cabangID)) {
                if (sizeof($cabangID) > 0) {
                    $ner->addFilter("cabang_id in ('" . implode("','", $cabangID) . "')");
                }
            }
            else {
                $ner->addFilter("cabang_id='$cabangID'");
            }
            $tmp[$tahun_ex] = $ner->fetchBalances($tahun_ex);
//            showLast_query("biru");
        }
//        arrPrintPink($tmp["2022-04"]);
        $arrCabang = array();
        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        $rekeningsKonsolidasi = array();
        $rekeningsKonsolidasiNilai = array();
        $rekeningsKonsolidasiKanan = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $thn_ex => $thn_ex_spec) {
                foreach ($thn_ex_spec as $row) {
//                    arrPrintPink($row);
                    $defPos = detectRekDefaultPosition($row->rekening);
                    $rek_name = $row->rekening;
                    $rek_name_alias = isset($this->accountAlias[$row->rekening]) ? $this->accountAlias[$row->rekening] : $row->rekening;
                    if (strlen($row->kategori) > 1) {
                        if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {
                            $arrCabang[$row->cabang_id] = isset($arrCabangs[$row->cabang_id]) ? $arrCabangs[$row->cabang_id] : "";
                            if (!in_array($row->kategori, $categories)) {
                                $categories[] = $row->kategori;
                            }

                            //region data per-cabang
                            if (!isset($rekenings[$row->cabang_id][$row->kategori])) {
                                $rekenings[$row->cabang_id][$row->kategori] = array();
                            }
                            if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet_' . $thn_ex])) {
                                $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet_' . $thn_ex] = 0;
                            }
                            if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit_' . $thn_ex])) {
                                $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit_' . $thn_ex] = 0;
                            }
                            //endregion

                            //region data konsolidasian
                            if (!isset($rekeningsKonsolidasiNilai[$row->kategori])) {
                                $rekeningsKonsolidasiNilai[$row->kategori] = array();
                            }
                            if (!isset($rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['debet_' . $thn_ex])) {
                                $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['debet_' . $thn_ex] = 0;
                            }
                            if (!isset($rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['kredit_' . $thn_ex])) {
                                $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['kredit_' . $thn_ex] = 0;
                            }
                            //endregion

                            //region data konsolidasian total kanan
                            if (!isset($rekeningsKonsolidasiKanan[$row->kategori])) {
                                $rekeningsKonsolidasiKanan[$row->kategori] = array();
                            }
                            if (!isset($rekeningsKonsolidasiKanan[$row->kategori][$row->rekening]['debet'])) {
                                $rekeningsKonsolidasiKanan[$row->kategori][$row->rekening]['debet'] = 0;
                            }
                            if (!isset($rekeningsKonsolidasiKanan[$row->kategori][$row->rekening]['kredit'])) {
                                $rekeningsKonsolidasiKanan[$row->kategori][$row->rekening]['kredit'] = 0;
                            }
                            //endregion

                            if (in_array($row->rekening, $this->accountException)) {
                                $debet = $row->kredit * -1;
                                $kredit = $row->debet * -1;
                            }
                            else {
                                switch ($defPos) {
                                    case "debet":
                                        if ($row->kredit > 0) {
                                            $debet = $row->kredit * -1;
                                            $kredit = 0;
                                        }
                                        else {
                                            $debet = $row->debet;
                                            $kredit = $row->kredit;
                                        }
                                        break;
                                    case "kredit":
                                        if ($row->debet > 0) {
                                            $debet = 0;
                                            $kredit = $row->debet * -1;
                                        }
                                        else {
                                            $debet = $row->debet;
                                            $kredit = $row->kredit;
                                        }
                                        break;
                                    default:
                                        $debet = $row->debet;
                                        $kredit = $row->kredit;
                                        break;
                                }
                            }

                            if (sizeof($this->accountCatException) > 0) {
                                foreach ($this->accountCatException as $cat => $c_rekName) {
                                    if (in_array($row->rekening, $c_rekName)) {
                                        //region data per-cabang
                                        if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['debet_' . $thn_ex])) {
                                            $rekenings[$row->cabang_id][$cat][$row->rekening]['debet_' . $thn_ex] = 0;
                                        }
                                        if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['kredit_' . $thn_ex])) {
                                            $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit_' . $thn_ex] = 0;
                                        }
                                        if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['link'])) {
                                            $rekenings[$row->cabang_id][$cat][$row->rekening]['link'] = "";
                                        }
                                        $rekenings[$row->cabang_id][$cat][$row->rekening]['debet_' . $thn_ex] += $debet;
                                        $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit_' . $thn_ex] += $kredit;
                                        $rekenings[$row->cabang_id][$cat][$row->rekening]['rekening'] = $rek_name;
                                        $rekenings[$row->cabang_id][$cat][$row->rekening]['rekening_alias'] = $rek_name_alias;
                                        //endregion
                                        //region data konsolidasian
                                        if (!isset($rekeningsKonsolidasiNilai[$cat][$row->rekening]['debet_' . $thn_ex])) {
                                            $rekeningsKonsolidasiNilai[$cat][$row->rekening]['debet_' . $thn_ex] = 0;
                                        }
                                        if (!isset($rekeningsKonsolidasiNilai[$cat][$row->rekening]['kredit_' . $thn_ex])) {
                                            $rekeningsKonsolidasiNilai[$cat][$row->rekening]['kredit_' . $thn_ex] = 0;
                                        }
                                        if (!isset($rekeningsKonsolidasiNilai[$cat][$row->rekening]['link'])) {
                                            $rekeningsKonsolidasiNilai[$cat][$row->rekening]['link'] = "";
                                        }
                                        $rekeningsKonsolidasiNilai[$cat][$row->rekening]['debet_' . $thn_ex] += $debet;
                                        $rekeningsKonsolidasiNilai[$cat][$row->rekening]['kredit_' . $thn_ex] += $kredit;
                                        $rekeningsKonsolidasiNilai[$cat][$row->rekening]['rekening'] = $rek_name;
                                        $rekeningsKonsolidasiNilai[$cat][$row->rekening]['rekening_alias'] = $rek_name_alias;
                                        //endregion
                                        //region data konsolidasian total kanan
                                        if (!isset($rekeningsKonsolidasiKanan[$cat][$row->rekening]['debet'])) {
                                            $rekeningsKonsolidasiKanan[$cat][$row->rekening]['debet'] = 0;
                                        }
                                        if (!isset($rekeningsKonsolidasiKanan[$cat][$row->rekening]['kredit'])) {
                                            $rekeningsKonsolidasiKanan[$cat][$row->rekening]['kredit'] = 0;
                                        }
                                        if (!isset($rekeningsKonsolidasiKanan[$cat][$row->rekening]['link'])) {
                                            $rekeningsKonsolidasiKanan[$cat][$row->rekening]['link'] = "";
                                        }
                                        $rekeningsKonsolidasiKanan[$cat][$row->rekening]['debet'] += $debet;
                                        $rekeningsKonsolidasiKanan[$cat][$row->rekening]['kredit'] += $kredit;
                                        $rekeningsKonsolidasiKanan[$cat][$row->rekening]['rekening'] = $rek_name;
                                        $rekeningsKonsolidasiKanan[$cat][$row->rekening]['rekening_alias'] = $rek_name_alias;
                                        //endregion
                                        $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                    }
                                    else {
                                        //region data per-cabang
                                        $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet_' . $thn_ex] += $debet;
                                        $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit_' . $thn_ex] += $kredit;
                                        $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['rekening'] = $rek_name;
                                        $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['rekening_alias'] = $rek_name_alias;
                                        //endregion
                                        //region data konsolidasian
                                        $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['debet_' . $thn_ex] += $debet;
                                        $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['kredit_' . $thn_ex] += $kredit;
                                        $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['rekening'] = $rek_name;
                                        $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['rekening_alias'] = $rek_name_alias;
                                        //endregion
                                        //region data konsolidasian total kanan
                                        $rekeningsKonsolidasiKanan[$row->kategori][$row->rekening]['debet'] += $debet;
                                        $rekeningsKonsolidasiKanan[$row->kategori][$row->rekening]['kredit'] += $kredit;
                                        $rekeningsKonsolidasiKanan[$row->kategori][$row->rekening]['rekening'] = $rek_name;
                                        $rekeningsKonsolidasiKanan[$row->kategori][$row->rekening]['rekening_alias'] = $rek_name_alias;
                                        //endregion
                                        $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                    }
                                }
                            }
                            else {
                                $rekenings[$row->kategori][] = $rekenings;
                                $rekeningsKonsolidasiNilai[$row->kategori][] = $rekeningsKonsolidasiNilai;
                                $rekeningsKonsolidasiKanan[$row->kategori][] = $rekeningsKonsolidasiKanan;
                            }

//                            $whID = getDefaultWarehouseID($row->cabang_id);
//                            $childLink = "";
//                            if (isset($this->accountChilds[$row->rekening])) {
//                                $childLink = "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $this->accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&w=" . $whID['gudang_id'] . "'>
//                                        <span class='fa fa-clone'></span></a>";
//                            }
//                            $childLink2 = "$childLink <span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&w=" . $whID['gudang_id'] . "'>
//                                        <span class='glyphicon glyphicon-time'></span></a></span>";
//
//                            $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['link'] = $childLink2;
//                            $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['link_' . $thn_ex] = "";
//                            $rekeningsKonsolidasiKanan[$row->kategori][$row->rekening]['link_'] = "";
                        }
                    }
                }
            }
        }


        $arrResultRekening = array(
            "rekenings" => $rekenings,
            "rekeningsCabang" => isset($rekeningsCab) ? $rekeningsCab : array(),
            "rekeningsKonsolidasi" => isset($rekeningsKonsolidasiNilai) ? $rekeningsKonsolidasiNilai : array(),
            "rekeningsKonsolidasiTotal" => isset($rekeningsKonsolidasiKanan) ? $rekeningsKonsolidasiKanan : array(),
            "rekeningsName" => $rekeningsName,
            "categories" => $categories,
        );
//        arrPrintHijau($arrResultRekening["rekenings"]);
//        arrPrintPink($arrResultRekening["rekeningsCabang"]);
//        arrPrintWebs($arrResultRekening["rekeningsKonsolidasi"]);
//        arrPrintWebs($arrResultRekening["rekeningsKonsolidasiTotal"]);

        return $arrResultRekening;
    }

    public function getNeracaYtd($cabangID, $periode = "tahunan", $defaultDate = "")
    {

        $cr = New ComRekening_cli();
        $n = New ComNeraca_cli();
        $rl = New ComRugiLaba_cli();

        if (!is_array($cabangID)) {
            $cabangIDs = array($cabangID);
        }
        else {
            $cabangIDs = $cabangID;
        }
        $categories = array();
        $rekenings = array();
        $rekeningsName = array();

        foreach ($cabangIDs as $cabangID) {

            $static = array(
                "static" => array(
                    "cabang_id" => $cabangID,
                    "dtime" => date("Y-m-d H:i:s"),
                    "fulldate" => date("Y-m-d"),
                    "bln" => date("m"),
                    "thn" => date("Y"),
                    "periode" => $periode,
                ),
            );

            //------ ambil saldo rek_master_cache
            $cr->addFilter("thn='" . date("Y") . "'");
            $cr->addFilter("periode='$periode'");
//        if (is_array($cabangID)) {
//            if (sizeof($cabangID) > 0) {
//                $cr->addFilter("cabang_id in ('" . implode("','", $cabangID) . "')");
//            }
//        }
//        else {
//            $cr->addFilter("cabang_id='$cabangID'");
//        }
            $cr->addFilter("cabang_id='$cabangID'");
            $crTmp = $cr->lookupAll()->result();
            $lajur = array();
            if (sizeof($crTmp) > 0) {
                foreach ($crTmp as $spec) {
                    $lajur[$spec->rekening] = array(
                        "rek_id" => $spec->rek_id,
                        "rekening" => $spec->rekening,
                        "debet" => $spec->debet,
                        "kredit" => $spec->kredit,
                        "periode" => $spec->periode,
                    );
                }
            }

            //------ masuk com ke rugilaba
            //        $rl->setFilters2($filters2);
            //        $rl->setFilters($filters);
            $rl->pairNoCut_view($static, $lajur);
            $resultRL = $rl->execNoCut_view();

            //------ masuk com ke neraca
            //        $n->setFilters2($filters2);
            //        $n->setFilters($filters);
            $n->pairNoCut_view($static, $resultRL['neraca']);
            $resultNeraca = $n->execNoCut_view();
//arrPrintHijau($resultNeraca);

            $tmp = array();
            if (sizeof($resultNeraca) > 0) {
                foreach ($resultNeraca as $nn => $nSpec) {
                    $temp = array();
                    foreach ($nSpec as $key => $val) {
                        $temp[$key] = $val;
                    }
                    $tmp[$nn] = (object)$temp;
                }
            }

            $oldDate = "";
//            $categories = array();
//            $rekenings = array();
//            $rekeningsName = array();
            if (sizeof($tmp) > 0) {
                foreach ($tmp as $row) {
                    $cabID = $row->cabang_id;
                    $defPos = detectRekDefaultPosition($row->rekening);
                    $rek_alias = isset($this->accountAlias[$row->rekening]) ? $this->accountAlias[$row->rekening] : $row->rekening;

                    if (strlen($row->kategori) > 1) {
                        if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {

                            if (!in_array($row->kategori, $categories)) {
                                $categories[] = $row->kategori;
                            }
                            // rekenings cabang selected
                            if (!isset($rekenings[$row->kategori])) {
                                $rekenings[$row->kategori] = array();
                            }
                            // rekening index per-cabang
                            if (!isset($rekeningsCab[$cabID][$row->kategori])) {
                                $rekeningsCab[$cabID][$row->kategori] = array();
                            }
                            // rekening konsolidasi
                            if (!isset($rekeningsKonsolidasi[$row->kategori])) {
                                $rekeningsKonsolidasi[$row->kategori] = array();
                            }


                            if (in_array($row->rekening, $this->accountException)) {
//                            $tmpCol = array(
//                                "rek_id" => "",
//                                "rekening" => $row->rekening,
////                                "debet" => ($row->kredit * -1),
////                                "kredit" => ($row->debet * -1),
//                                "link" => "",
//                            );
                                $debet = ($row->kredit * -1);
                                $kredit = ($row->debet * -1);

                            }
                            else {
                                switch ($defPos) {
                                    case "debet":
                                        if ($row->kredit > 0) {
                                            $debet = $row->kredit * -1;
                                            $kredit = 0;
                                        }
                                        else {
                                            $debet = $row->debet;
                                            $kredit = $row->kredit;
                                        }
                                        break;
                                    case "kredit":
                                        if ($row->debet > 0) {
                                            $debet = 0;
                                            $kredit = $row->debet * -1;
                                        }
                                        else {
                                            $debet = $row->debet;
                                            $kredit = $row->kredit;
                                        }
                                        break;
                                    default:
                                        $debet = $row->debet;
                                        $kredit = $row->kredit;
                                        break;
                                }

//                            $tmpCol = array(
//                                "rek_id" => "",
//                                "rekening" => $row->rekening,
////                                "debet" => $debet,
////                                "kredit" => $kredit,
//                                "link" => "",
//                            );
                            }

                            $link = "";
                            if (isset($this->accountChilds[$row->rekening])) {
                                $link .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $this->accountChilds[$row->rekening] . "/" . $row->rekening . "/" . $row->periode . "?date=$oldDate'><span class='fa fa-clone'></span></a>";
                            }
                            $link .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "'><span class='glyphicon glyphicon-time'></span></a></span>";


                            if (sizeof($this->accountCatException) > 0) {
                                foreach ($this->accountCatException as $cat => $c_rekName) {
                                    if (in_array($row->rekening, $c_rekName)) {
                                        $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                        //region cabang selected
                                        $rekenings[$cat][$row->rekening]["rek_id"] = "";
                                        $rekenings[$cat][$row->rekening]["rekening"] = $row->rekening;
                                        $rekenings[$cat][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekenings[$cat][$row->rekening]["debet"])) {
                                            $rekenings[$cat][$row->rekening]["debet"] = 0;
                                        }
                                        if (!isset($rekenings[$cat][$row->rekening]["kredit"])) {
                                            $rekenings[$cat][$row->rekening]["kredit"] = 0;
                                        }
                                        $rekenings[$cat][$row->rekening]["debet"] += $debet;
                                        $rekenings[$cat][$row->rekening]["kredit"] += $kredit;
                                        $rekenings[$cat][$row->rekening]["link"] = $link;
                                        //endregion

                                        //region index per-cabang
                                        $rekeningsCab[$cabID][$cat][$row->rekening]["rek_id"] = "";
                                        $rekeningsCab[$cabID][$cat][$row->rekening]["rekening"] = $row->rekening;
                                        $rekeningsCab[$cabID][$cat][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekeningsCab[$cabID][$cat][$row->rekening]["debet"])) {
                                            $rekeningsCab[$cabID][$cat][$row->rekening]["debet"] = 0;
                                        }
                                        if (!isset($rekeningsCab[$cabID][$cat][$row->rekening]["kredit"])) {
                                            $rekeningsCab[$cabID][$cat][$row->rekening]["kredit"] = 0;
                                        }
                                        $rekeningsCab[$cabID][$cat][$row->rekening]["debet"] += $debet;
                                        $rekeningsCab[$cabID][$cat][$row->rekening]["kredit"] += $kredit;
                                        $rekeningsCab[$cabID][$cat][$row->rekening]["link"] = $link;
                                        //endregion

                                        //region konsolidasi
                                        $rekeningsKonsolidasi[$cat][$row->rekening]["rek_id"] = "";
                                        $rekeningsKonsolidasi[$cat][$row->rekening]["rekening"] = $row->rekening;
                                        $rekeningsKonsolidasi[$cat][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekeningsKonsolidasi[$cat][$row->rekening]["debet"])) {
                                            $rekeningsKonsolidasi[$cat][$row->rekening]["debet"] = 0;
                                        }
                                        if (!isset($rekeningsKonsolidasi[$cat][$row->rekening]["kredit"])) {
                                            $rekeningsKonsolidasi[$cat][$row->rekening]["kredit"] = 0;
                                        }
                                        $rekeningsKonsolidasi[$cat][$row->rekening]["debet"] += $debet;
                                        $rekeningsKonsolidasi[$cat][$row->rekening]["kredit"] += $kredit;
                                        $rekeningsKonsolidasi[$cat][$row->rekening]["link"] = $link;
                                        //endregion
                                    }
                                    else {
                                        $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                        //region cabang selected
                                        $rekenings[$row->kategori][$row->rekening]["rek_id"] = "";
                                        $rekenings[$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                        $rekenings[$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekenings[$row->kategori][$row->rekening]["debet"])) {
                                            $rekenings[$row->kategori][$row->rekening]["debet"] = 0;
                                        }
                                        if (!isset($rekenings[$row->kategori][$row->rekening]["kredit"])) {
                                            $rekenings[$row->kategori][$row->rekening]["kredit"] = 0;
                                        }
                                        $rekenings[$row->kategori][$row->rekening]["debet"] += $debet;
                                        $rekenings[$row->kategori][$row->rekening]["kredit"] += $kredit;
                                        $rekenings[$row->kategori][$row->rekening]["link"] = $link;
                                        //endregion

                                        //region index per-cabang
                                        $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rek_id"] = "";
                                        $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                        $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"])) {
                                            $rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"] = 0;
                                        }
                                        if (!isset($rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"])) {
                                            $rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"] = 0;
                                        }
                                        $rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"] += $debet;
                                        $rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"] += $kredit;
                                        $rekeningsCab[$cabID][$row->kategori][$row->rekening]["link"] = $link;
                                        //endregion

                                        //region konsolidasi
                                        $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rek_id"] = "";
                                        $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                        $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"])) {
                                            $rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"] = 0;
                                        }
                                        if (!isset($rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"])) {
                                            $rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"] = 0;
                                        }
                                        $rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"] += $debet;
                                        $rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"] += $kredit;
                                        $rekeningsKonsolidasi[$row->kategori][$row->rekening]["link"] = $link;
                                        //endregion
                                    }
                                }
                            }
                            else {
                                //region cabang selected
                                $rekenings[$row->kategori][$row->rekening]["rek_id"] = "";
                                $rekenings[$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                $rekenings[$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                if (!isset($rekenings[$row->kategori][$row->rekening]["debet"])) {
                                    $rekenings[$row->kategori][$row->rekening]["debet"] = 0;
                                }
                                if (!isset($rekenings[$row->kategori][$row->rekening]["kredit"])) {
                                    $rekenings[$row->kategori][$row->rekening]["kredit"] = 0;
                                }
                                $rekenings[$row->kategori][$row->rekening]["debet"] += $debet;
                                $rekenings[$row->kategori][$row->rekening]["kredit"] += $kredit;
                                $rekenings[$row->kategori][$row->rekening]["link"] = $link;
                                //endregion

                                //region index per-cabang
                                $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rek_id"] = "";
                                $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                if (!isset($rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"])) {
                                    $rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"] = 0;
                                }
                                if (!isset($rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"])) {
                                    $rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"] = 0;
                                }
                                $rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"] += $debet;
                                $rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"] += $kredit;
                                $rekeningsCab[$cabID][$row->kategori][$row->rekening]["link"] = $link;
                                //endregion

                                //region konsolidasi
                                $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rek_id"] = "";
                                $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                if (!isset($rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"])) {
                                    $rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"] = 0;
                                }
                                if (!isset($rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"])) {
                                    $rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"] = 0;
                                }
                                $rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"] += $debet;
                                $rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"] += $kredit;
                                $rekeningsKonsolidasi[$row->kategori][$row->rekening]["link"] = $link;
                                //endregion
                            }


                        }
                    }
                }
            }

        }

        $arrResultRekening = array(
            "rekenings" => $rekenings,
            "rekeningsCabang" => isset($rekeningsCab) ? $rekeningsCab : array(),
            "rekeningsKonsolidasi" => isset($rekeningsKonsolidasi) ? $rekeningsKonsolidasi : array(),
            "rekeningsName" => $rekeningsName,
            "categories" => $categories,
        );

//        arrPrintHijau($arrResultRekening["rekenings"]);
//        arrPrintPink($arrResultRekening["rekeningsCabang"]);
//        arrPrintWebs($arrResultRekening["rekeningsKonsolidasi"]);
        return $arrResultRekening;

    }

    public function getNeracaMtd($cabangID, $periode = "bulanan", $defaultDate = "")
    {

        $cr = New ComRekening_cli();
        $n = New ComNeraca_cli();
        $rl = New ComRugiLaba_cli();

        if (!is_array($cabangID)) {
            $cabangIDs = array($cabangID);
        }
        else {
            $cabangIDs = $cabangID;
        }
        $categories = array();
        $rekenings = array();
        $rekeningsName = array();

        foreach ($cabangIDs as $cabangID) {

            $static = array(
                "static" => array(
                    "cabang_id" => $cabangID,
                    "dtime" => date("Y-m-d H:i:s"),
                    "fulldate" => date("Y-m-d"),
                    "bln" => date("m"),
                    "thn" => date("Y"),
                    "periode" => $periode,
                ),
            );

            //------ ambil saldo rek_master_cache
            $cr->addFilter("bln='" . date("m") . "'");
            $cr->addFilter("thn='" . date("Y") . "'");
            $cr->addFilter("periode='$periode'");
//        if (is_array($cabangID)) {
//            if (sizeof($cabangID) > 0) {
//                $cr->addFilter("cabang_id in ('" . implode("','", $cabangID) . "')");
//            }
//        }
//        else {
//            $cr->addFilter("cabang_id='$cabangID'");
//        }
            $cr->addFilter("cabang_id='$cabangID'");
            $crTmp = $cr->lookupAll()->result();
            showLast_query("biru");
            $lajur = array();
            if (sizeof($crTmp) > 0) {
                foreach ($crTmp as $spec) {
                    $lajur[$spec->rekening] = array(
                        "rek_id" => $spec->rek_id,
                        "rekening" => $spec->rekening,
                        "debet" => $spec->debet,
                        "kredit" => $spec->kredit,
                        "periode" => $spec->periode,
                    );
                }
            }

            //------ masuk com ke rugilaba
            //        $rl->setFilters2($filters2);
            //        $rl->setFilters($filters);
            $rl->pairNoCut_view($static, $lajur);
            $resultRL = $rl->execNoCut_view();

            //------ masuk com ke neraca
            //        $n->setFilters2($filters2);
            //        $n->setFilters($filters);
            $n->pairNoCut_view($static, $resultRL['neraca']);
            $resultNeraca = $n->execNoCut_view();
//arrPrintHijau($resultNeraca);

            $tmp = array();
            if (sizeof($resultNeraca) > 0) {
                foreach ($resultNeraca as $nn => $nSpec) {
                    $temp = array();
                    foreach ($nSpec as $key => $val) {
                        $temp[$key] = $val;
                    }
                    $tmp[$nn] = (object)$temp;
                }
            }

            $oldDate = "";
//            $categories = array();
//            $rekenings = array();
//            $rekeningsName = array();
            if (sizeof($tmp) > 0) {
                foreach ($tmp as $row) {
                    $cabang_id = $cabID = $row->cabang_id;
                    $defPos = detectRekDefaultPosition($row->rekening);
                    $rek_alias = isset($this->accountAlias[$row->rekening]) ? $this->accountAlias[$row->rekening] : $row->rekening;

                    if (strlen($row->kategori) > 1) {
                        if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {

                            if (!in_array($row->kategori, $categories)) {
                                $categories[] = $row->kategori;
                            }
                            // rekenings cabang selected
                            if (!isset($rekenings[$row->kategori])) {
                                $rekenings[$row->kategori] = array();
                            }
                            // rekening index per-cabang
                            if (!isset($rekeningsCab[$cabID][$row->kategori])) {
                                $rekeningsCab[$cabID][$row->kategori] = array();
                            }
                            // rekening konsolidasi
                            if (!isset($rekeningsKonsolidasi[$row->kategori])) {
                                $rekeningsKonsolidasi[$row->kategori] = array();
                            }


                            if (in_array($row->rekening, $this->accountException)) {
//                            $tmpCol = array(
//                                "rek_id" => "",
//                                "rekening" => $row->rekening,
////                                "debet" => ($row->kredit * -1),
////                                "kredit" => ($row->debet * -1),
//                                "link" => "",
//                            );
                                $debet = ($row->kredit * -1);
                                $kredit = ($row->debet * -1);

                            }
                            else {
                                switch ($defPos) {
                                    case "debet":
                                        if ($row->kredit > 0) {
                                            $debet = $row->kredit * -1;
                                            $kredit = 0;
                                        }
                                        else {
                                            $debet = $row->debet;
                                            $kredit = $row->kredit;
                                        }
                                        break;
                                    case "kredit":
                                        if ($row->debet > 0) {
                                            $debet = 0;
                                            $kredit = $row->debet * -1;
                                        }
                                        else {
                                            $debet = $row->debet;
                                            $kredit = $row->kredit;
                                        }
                                        break;
                                    default:
                                        $debet = $row->debet;
                                        $kredit = $row->kredit;
                                        break;
                                }

//                            $tmpCol = array(
//                                "rek_id" => "",
//                                "rekening" => $row->rekening,
////                                "debet" => $debet,
////                                "kredit" => $kredit,
//                                "link" => "",
//                            );
                            }

                            $link = "";
                            if (isset($this->accountChilds[$row->rekening])) {
                                $link .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $this->accountChilds[$row->rekening] . "/" . $row->rekening . "/" . $row->periode . "?date=$oldDate&o=$cabang_id'><span class='fa fa-clone'></span></a>";
                            }
                            $link .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?&o=$cabang_id'><span class='glyphicon glyphicon-time'></span></a></span>";


                            if (sizeof($this->accountCatException) > 0) {
                                foreach ($this->accountCatException as $cat => $c_rekName) {
                                    if (in_array($row->rekening, $c_rekName)) {
                                        $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                        //region cabang selected
                                        $rekenings[$cat][$row->rekening]["rek_id"] = "";
                                        $rekenings[$cat][$row->rekening]["rekening"] = $row->rekening;
                                        $rekenings[$cat][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekenings[$cat][$row->rekening]["debet"])) {
                                            $rekenings[$cat][$row->rekening]["debet"] = 0;
                                        }
                                        if (!isset($rekenings[$cat][$row->rekening]["kredit"])) {
                                            $rekenings[$cat][$row->rekening]["kredit"] = 0;
                                        }
                                        $rekenings[$cat][$row->rekening]["debet"] += $debet;
                                        $rekenings[$cat][$row->rekening]["kredit"] += $kredit;
                                        $rekenings[$cat][$row->rekening]["link"] = $link;
                                        //endregion

                                        //region index per-cabang
                                        $rekeningsCab[$cabID][$cat][$row->rekening]["rek_id"] = "";
                                        $rekeningsCab[$cabID][$cat][$row->rekening]["rekening"] = $row->rekening;
                                        $rekeningsCab[$cabID][$cat][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekeningsCab[$cabID][$cat][$row->rekening]["debet"])) {
                                            $rekeningsCab[$cabID][$cat][$row->rekening]["debet"] = 0;
                                        }
                                        if (!isset($rekeningsCab[$cabID][$cat][$row->rekening]["kredit"])) {
                                            $rekeningsCab[$cabID][$cat][$row->rekening]["kredit"] = 0;
                                        }
                                        $rekeningsCab[$cabID][$cat][$row->rekening]["debet"] += $debet;
                                        $rekeningsCab[$cabID][$cat][$row->rekening]["kredit"] += $kredit;
                                        $rekeningsCab[$cabID][$cat][$row->rekening]["link"] = $link;
                                        //endregion

                                        //region konsolidasi
                                        $rekeningsKonsolidasi[$cat][$row->rekening]["rek_id"] = "";
                                        $rekeningsKonsolidasi[$cat][$row->rekening]["rekening"] = $row->rekening;
                                        $rekeningsKonsolidasi[$cat][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekeningsKonsolidasi[$cat][$row->rekening]["debet"])) {
                                            $rekeningsKonsolidasi[$cat][$row->rekening]["debet"] = 0;
                                        }
                                        if (!isset($rekeningsKonsolidasi[$cat][$row->rekening]["kredit"])) {
                                            $rekeningsKonsolidasi[$cat][$row->rekening]["kredit"] = 0;
                                        }
                                        $rekeningsKonsolidasi[$cat][$row->rekening]["debet"] += $debet;
                                        $rekeningsKonsolidasi[$cat][$row->rekening]["kredit"] += $kredit;
                                        $rekeningsKonsolidasi[$cat][$row->rekening]["link"] = $link;
                                        //endregion
                                    }
                                    else {
                                        $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                        //region cabang selected
                                        $rekenings[$row->kategori][$row->rekening]["rek_id"] = "";
                                        $rekenings[$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                        $rekenings[$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekenings[$row->kategori][$row->rekening]["debet"])) {
                                            $rekenings[$row->kategori][$row->rekening]["debet"] = 0;
                                        }
                                        if (!isset($rekenings[$row->kategori][$row->rekening]["kredit"])) {
                                            $rekenings[$row->kategori][$row->rekening]["kredit"] = 0;
                                        }
                                        $rekenings[$row->kategori][$row->rekening]["debet"] += $debet;
                                        $rekenings[$row->kategori][$row->rekening]["kredit"] += $kredit;
                                        $rekenings[$row->kategori][$row->rekening]["link"] = $link;
                                        //endregion

                                        //region index per-cabang
                                        $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rek_id"] = "";
                                        $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                        $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"])) {
                                            $rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"] = 0;
                                        }
                                        if (!isset($rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"])) {
                                            $rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"] = 0;
                                        }
                                        $rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"] += $debet;
                                        $rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"] += $kredit;
                                        $rekeningsCab[$cabID][$row->kategori][$row->rekening]["link"] = $link;
                                        //endregion

                                        //region konsolidasi
                                        $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rek_id"] = "";
                                        $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                        $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"])) {
                                            $rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"] = 0;
                                        }
                                        if (!isset($rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"])) {
                                            $rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"] = 0;
                                        }
                                        $rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"] += $debet;
                                        $rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"] += $kredit;
                                        $rekeningsKonsolidasi[$row->kategori][$row->rekening]["link"] = $link;
                                        //endregion
                                    }
                                }
                            }
                            else {
                                //region cabang selected
                                $rekenings[$row->kategori][$row->rekening]["rek_id"] = "";
                                $rekenings[$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                $rekenings[$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                if (!isset($rekenings[$row->kategori][$row->rekening]["debet"])) {
                                    $rekenings[$row->kategori][$row->rekening]["debet"] = 0;
                                }
                                if (!isset($rekenings[$row->kategori][$row->rekening]["kredit"])) {
                                    $rekenings[$row->kategori][$row->rekening]["kredit"] = 0;
                                }
                                $rekenings[$row->kategori][$row->rekening]["debet"] += $debet;
                                $rekenings[$row->kategori][$row->rekening]["kredit"] += $kredit;
                                $rekenings[$row->kategori][$row->rekening]["link"] = $link;
                                //endregion

                                //region index per-cabang
                                $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rek_id"] = "";
                                $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                if (!isset($rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"])) {
                                    $rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"] = 0;
                                }
                                if (!isset($rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"])) {
                                    $rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"] = 0;
                                }
                                $rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"] += $debet;
                                $rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"] += $kredit;
                                $rekeningsCab[$cabID][$row->kategori][$row->rekening]["link"] = $link;
                                //endregion

                                //region konsolidasi
                                $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rek_id"] = "";
                                $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                if (!isset($rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"])) {
                                    $rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"] = 0;
                                }
                                if (!isset($rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"])) {
                                    $rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"] = 0;
                                }
                                $rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"] += $debet;
                                $rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"] += $kredit;
                                $rekeningsKonsolidasi[$row->kategori][$row->rekening]["link"] = $link;
                                //endregion
                            }


                        }
                    }
                }
            }

        }

        $arrResultRekening = array(
            "rekenings" => $rekenings,
            "rekeningsCabang" => isset($rekeningsCab) ? $rekeningsCab : array(),
            "rekeningsKonsolidasi" => isset($rekeningsKonsolidasi) ? $rekeningsKonsolidasi : array(),
            "rekeningsName" => $rekeningsName,
            "categories" => $categories,
        );

//        arrPrintHijau($arrResultRekening["rekenings"]);
//        arrPrintPink($arrResultRekening["rekeningsCabang"]);
//        arrPrintWebs($arrResultRekening["rekeningsKonsolidasi"]);
        return $arrResultRekening;

    }


    public function getNeracaMtd_($cabangID, $periode = "bulanan", $defaultDate = "")
    {

        $cr = New ComRekening_cli();
        $n = New ComNeraca_cli();
        $rl = New ComRugiLaba_cli();

        $static = array(
            "static" => array(
                "cabang_id" => $cabangID,
                "dtime" => date("Y-m-d H:i:s"),
                "fulldate" => date("Y-m-d"),
                "bln" => date("m"),
                "thn" => date("Y"),
                "periode" => $periode,
            ),
        );

        //------ ambil saldo rek_master_cache
        $cr->addFilter("bln='" . date("m") . "'");
        $cr->addFilter("thn='" . date("Y") . "'");
        $cr->addFilter("periode='$periode'");
        if (is_array($cabangID)) {
            if (sizeof($cabangID) > 0) {
                $cr->addFilter("cabang_id in ('" . implode("','", $cabangID) . "')");
            }
        }
        else {
            $cr->addFilter("cabang_id='$cabangID'");
        }
        $crTmp = $cr->lookupAll()->result();
        $lajur = array();
        if (sizeof($crTmp) > 0) {
            foreach ($crTmp as $spec) {
                $lajur[$spec->rekening] = array(
                    "rek_id" => $spec->rek_id,
                    "rekening" => $spec->rekening,
                    "debet" => $spec->debet,
                    "kredit" => $spec->kredit,
                    "periode" => $spec->periode,
                );
            }
        }

        //------ masuk com ke rugilaba
//        $rl->setFilters2($filters2);
//        $rl->setFilters($filters);
        $rl->pairNoCut_view($static, $lajur);
        $resultRL = $rl->execNoCut_view();

        //------ masuk com ke neraca
//        $n->setFilters2($filters2);
//        $n->setFilters($filters);
        $n->pairNoCut_view($static, $resultRL['neraca']);
        $resultNeraca = $n->execNoCut_view();
//arrPrintHijau($resultNeraca);

        $tmp = array();
        if (sizeof($resultNeraca) > 0) {
            foreach ($resultNeraca as $nn => $nSpec) {
                $temp = array();
                foreach ($nSpec as $key => $val) {
                    $temp[$key] = $val;
                }
                $tmp[$nn] = (object)$temp;
            }
        }

        $oldDate = "";
        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $cabID = $row->cabang_id;
                $defPos = detectRekDefaultPosition($row->rekening);
                $rek_alias = isset($this->accountAlias[$row->rekening]) ? $this->accountAlias[$row->rekening] : $row->rekening;

                if (strlen($row->kategori) > 1) {
                    if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {

                        if (!in_array($row->kategori, $categories)) {
                            $categories[] = $row->kategori;
                        }
                        // rekenings cabang selected
                        if (!isset($rekenings[$row->kategori])) {
                            $rekenings[$row->kategori] = array();
                        }
                        // rekening index per-cabang
                        if (!isset($rekeningsCab[$cabID][$row->kategori])) {
                            $rekeningsCab[$cabID][$row->kategori] = array();
                        }
                        // rekening konsolidasi
                        if (!isset($rekeningsKonsolidasi[$row->kategori])) {
                            $rekeningsKonsolidasi[$row->kategori] = array();
                        }


                        if (in_array($row->rekening, $this->accountException)) {
//                            $tmpCol = array(
//                                "rek_id" => "",
//                                "rekening" => $row->rekening,
////                                "debet" => ($row->kredit * -1),
////                                "kredit" => ($row->debet * -1),
//                                "link" => "",
//                            );
                            $debet = ($row->kredit * -1);
                            $kredit = ($row->debet * -1);

                        }
                        else {
                            switch ($defPos) {
                                case "debet":
                                    if ($row->kredit > 0) {
                                        $debet = $row->kredit * -1;
                                        $kredit = 0;
                                    }
                                    else {
                                        $debet = $row->debet;
                                        $kredit = $row->kredit;
                                    }
                                    break;
                                case "kredit":
                                    if ($row->debet > 0) {
                                        $debet = 0;
                                        $kredit = $row->debet * -1;
                                    }
                                    else {
                                        $debet = $row->debet;
                                        $kredit = $row->kredit;
                                    }
                                    break;
                                default:
                                    $debet = $row->debet;
                                    $kredit = $row->kredit;
                                    break;
                            }

//                            $tmpCol = array(
//                                "rek_id" => "",
//                                "rekening" => $row->rekening,
////                                "debet" => $debet,
////                                "kredit" => $kredit,
//                                "link" => "",
//                            );
                        }

                        $link = "";
                        if (isset($this->accountChilds[$row->rekening])) {
                            $link .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $this->accountChilds[$row->rekening] . "/" . $row->rekening . "/" . $row->periode . "?date=$oldDate'><span class='fa fa-clone'></span></a>";
                        }
                        $link .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "'><span class='glyphicon glyphicon-time'></span></a></span>";


                        if (sizeof($this->accountCatException) > 0) {
                            foreach ($this->accountCatException as $cat => $c_rekName) {
                                if (in_array($row->rekening, $c_rekName)) {
                                    $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                    //region cabang selected
                                    $rekenings[$cat][$row->rekening]["rek_id"] = "";
                                    $rekenings[$cat][$row->rekening]["rekening"] = $row->rekening;
                                    $rekenings[$cat][$row->rekening]["rekening_alias"] = $rek_alias;

                                    if (!isset($rekenings[$cat][$row->rekening]["debet"])) {
                                        $rekenings[$cat][$row->rekening]["debet"] = 0;
                                    }
                                    if (!isset($rekenings[$cat][$row->rekening]["kredit"])) {
                                        $rekenings[$cat][$row->rekening]["kredit"] = 0;
                                    }
                                    $rekenings[$cat][$row->rekening]["debet"] += $debet;
                                    $rekenings[$cat][$row->rekening]["kredit"] += $kredit;
                                    $rekenings[$cat][$row->rekening]["link"] = $link;
                                    //endregion

                                    //region index per-cabang
                                    $rekeningsCab[$cabID][$cat][$row->rekening]["rek_id"] = "";
                                    $rekeningsCab[$cabID][$cat][$row->rekening]["rekening"] = $row->rekening;
                                    $rekeningsCab[$cabID][$cat][$row->rekening]["rekening_alias"] = $rek_alias;

                                    if (!isset($rekeningsCab[$cabID][$cat][$row->rekening]["debet"])) {
                                        $rekeningsCab[$cabID][$cat][$row->rekening]["debet"] = 0;
                                    }
                                    if (!isset($rekeningsCab[$cabID][$cat][$row->rekening]["kredit"])) {
                                        $rekeningsCab[$cabID][$cat][$row->rekening]["kredit"] = 0;
                                    }
                                    $rekeningsCab[$cabID][$cat][$row->rekening]["debet"] += $debet;
                                    $rekeningsCab[$cabID][$cat][$row->rekening]["kredit"] += $kredit;
                                    $rekeningsCab[$cabID][$cat][$row->rekening]["link"] = $link;
                                    //endregion

                                    //region konsolidasi
                                    $rekeningsKonsolidasi[$cat][$row->rekening]["rek_id"] = "";
                                    $rekeningsKonsolidasi[$cat][$row->rekening]["rekening"] = $row->rekening;
                                    $rekeningsKonsolidasi[$cat][$row->rekening]["rekening_alias"] = $rek_alias;

                                    if (!isset($rekeningsKonsolidasi[$cat][$row->rekening]["debet"])) {
                                        $rekeningsKonsolidasi[$cat][$row->rekening]["debet"] = 0;
                                    }
                                    if (!isset($rekeningsKonsolidasi[$cat][$row->rekening]["kredit"])) {
                                        $rekeningsKonsolidasi[$cat][$row->rekening]["kredit"] = 0;
                                    }
                                    $rekeningsKonsolidasi[$cat][$row->rekening]["debet"] += $debet;
                                    $rekeningsKonsolidasi[$cat][$row->rekening]["kredit"] += $kredit;
                                    $rekeningsKonsolidasi[$cat][$row->rekening]["link"] = $link;
                                    //endregion
                                }
                                else {
                                    $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                    //region cabang selected
                                    $rekenings[$row->kategori][$row->rekening]["rek_id"] = "";
                                    $rekenings[$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                    $rekenings[$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                    if (!isset($rekenings[$row->kategori][$row->rekening]["debet"])) {
                                        $rekenings[$row->kategori][$row->rekening]["debet"] = 0;
                                    }
                                    if (!isset($rekenings[$row->kategori][$row->rekening]["kredit"])) {
                                        $rekenings[$row->kategori][$row->rekening]["kredit"] = 0;
                                    }
                                    $rekenings[$row->kategori][$row->rekening]["debet"] += $debet;
                                    $rekenings[$row->kategori][$row->rekening]["kredit"] += $kredit;
                                    $rekenings[$row->kategori][$row->rekening]["link"] = $link;
                                    //endregion

                                    //region index per-cabang
                                    $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rek_id"] = "";
                                    $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                    $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                    if (!isset($rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"])) {
                                        $rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"] = 0;
                                    }
                                    if (!isset($rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"])) {
                                        $rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"] = 0;
                                    }
                                    $rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"] += $debet;
                                    $rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"] += $kredit;
                                    $rekeningsCab[$cabID][$row->kategori][$row->rekening]["link"] = $link;
                                    //endregion

                                    //region konsolidasi
                                    $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rek_id"] = "";
                                    $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                    $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                    if (!isset($rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"])) {
                                        $rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"] = 0;
                                    }
                                    if (!isset($rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"])) {
                                        $rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"] = 0;
                                    }
                                    $rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"] += $debet;
                                    $rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"] += $kredit;
                                    $rekeningsKonsolidasi[$row->kategori][$row->rekening]["link"] = $link;
                                    //endregion
                                }
                            }
                        }
                        else {
                            //region cabang selected
                            $rekenings[$row->kategori][$row->rekening]["rek_id"] = "";
                            $rekenings[$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                            $rekenings[$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                            if (!isset($rekenings[$row->kategori][$row->rekening]["debet"])) {
                                $rekenings[$row->kategori][$row->rekening]["debet"] = 0;
                            }
                            if (!isset($rekenings[$row->kategori][$row->rekening]["kredit"])) {
                                $rekenings[$row->kategori][$row->rekening]["kredit"] = 0;
                            }
                            $rekenings[$row->kategori][$row->rekening]["debet"] += $debet;
                            $rekenings[$row->kategori][$row->rekening]["kredit"] += $kredit;
                            $rekenings[$row->kategori][$row->rekening]["link"] = $link;
                            //endregion

                            //region index per-cabang
                            $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rek_id"] = "";
                            $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                            $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                            if (!isset($rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"])) {
                                $rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"] = 0;
                            }
                            if (!isset($rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"])) {
                                $rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"] = 0;
                            }
                            $rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"] += $debet;
                            $rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"] += $kredit;
                            $rekeningsCab[$cabID][$row->kategori][$row->rekening]["link"] = $link;
                            //endregion

                            //region konsolidasi
                            $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rek_id"] = "";
                            $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                            $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                            if (!isset($rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"])) {
                                $rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"] = 0;
                            }
                            if (!isset($rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"])) {
                                $rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"] = 0;
                            }
                            $rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"] += $debet;
                            $rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"] += $kredit;
                            $rekeningsKonsolidasi[$row->kategori][$row->rekening]["link"] = $link;
                            //endregion
                        }


                    }
                }
            }
        }

        $arrResultRekening = array(
            "rekenings" => $rekenings,
            "rekeningsCabang" => isset($rekeningsCab) ? $rekeningsCab : array(),
            "rekeningsKonsolidasi" => isset($rekeningsKonsolidasi) ? $rekeningsKonsolidasi : array(),
            "rekeningsName" => $rekeningsName,
            "categories" => $categories,
        );

//        arrPrintHijau($arrResultRekening["rekenings"]);
//        arrPrintPink($arrResultRekening["rekeningsCabang"]);
//        arrPrintWebs($arrResultRekening["rekeningsKonsolidasi"]);
        return $arrResultRekening;

    }

    public function getNeracaCompared($cabangID, $periode, $defaultDate)
    {
        foreach ($defaultDate as $tahun_ex) {
            $ner = New MdlNeraca();
            if (is_array($cabangID)) {
                if (sizeof($cabangID) > 0) {
                    $ner->addFilter("cabang_id in ('" . implode("','", $cabangID) . "')");
                }
            }
            else {
                $ner->addFilter("cabang_id='$cabangID'");
            }
            $ner->addFilter("periode='$periode'");
            $tmp[$tahun_ex] = $ner->fetchBalances($tahun_ex);
//            cekHere(":: $tahun_ex ::");
//            showLast_query("biru");
        }

        $oldDate = date("Y-m");
//        $last_date = $defaultDate;

        $categories = array();
        $rekenings = array();
//        $rekeningsCab = array();
//        $rekeningsKonsolidasi = array();
        $rekeningsName = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $thn_ex => $thn_ex_spec) {
                foreach ($thn_ex_spec as $row) {
                    $cabID = $row->cabang_id;
                    $defPos = detectRekDefaultPosition($row->rekening);
                    $rek_alias = isset($this->accountAlias[$row->rekening]) ? $this->accountAlias[$row->rekening] : $row->rekening;
                    if (strlen($row->kategori) > 1) {
                        if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {
                            if (!in_array($row->kategori, $categories)) {
                                $categories[] = $row->kategori;
                            }
                            // rekenings cabang selected
                            if (!isset($rekenings[$row->kategori])) {
                                $rekenings[$row->kategori] = array();
                            }
                            // rekening index per-cabang
                            if (!isset($rekeningsCab[$cabID][$row->kategori])) {
                                $rekeningsCab[$cabID][$row->kategori] = array();
                            }
                            // rekening konsolidasi
                            if (!isset($rekeningsKonsolidasi[$row->kategori])) {
                                $rekeningsKonsolidasi[$row->kategori] = array();
                            }


                            if (in_array($row->rekening, $this->accountException)) {
//                            $tmpCol = array(
//                                "rek_id" => "",
//                                "rekening" => $row->rekening,
////                                "debet" => ($row->kredit * -1),
////                                "kredit" => ($row->debet * -1),
//                                "link" => "",
//                            );
                                $debet = ($row->kredit * -1);
                                $kredit = ($row->debet * -1);

                            }
                            else {
                                switch ($defPos) {
                                    case "debet":
                                        if ($row->kredit > 0) {
                                            $debet = $row->kredit * -1;
                                            $kredit = 0;
                                        }
                                        else {
                                            $debet = $row->debet;
                                            $kredit = $row->kredit;
                                        }
                                        break;
                                    case "kredit":
                                        if ($row->debet > 0) {
                                            $debet = 0;
                                            $kredit = $row->debet * -1;
                                        }
                                        else {
                                            $debet = $row->debet;
                                            $kredit = $row->kredit;
                                        }
                                        break;
                                    default:
                                        $debet = $row->debet;
                                        $kredit = $row->kredit;
                                        break;
                                }

//                            $tmpCol = array(
//                                "rek_id" => "",
//                                "rekening" => $row->rekening,
////                                "debet" => $debet,
////                                "kredit" => $kredit,
//                                "link" => "",
//                            );
                            }
                            $link = "";
//                        if (isset($this->accountChilds[$row->rekening])) {
//                            $link .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $this->accountChilds[$row->rekening] . "/" . $row->rekening . "/" . $row->periode . "?date=$oldDate'><span class='fa fa-clone'></span></a>";
//                        }
//                        $link .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "'><span class='glyphicon glyphicon-time'></span></a></span>";


                            if (sizeof($this->accountCatException) > 0) {
                                foreach ($this->accountCatException as $cat => $c_rekName) {
                                    if (in_array($row->rekening, $c_rekName)) {
                                        $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                        //region cabang selected
                                        $rekenings[$cat][$row->rekening]["rek_id"] = "";
                                        $rekenings[$cat][$row->rekening]["rekening"] = $row->rekening;
                                        $rekenings[$cat][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekenings[$cat][$row->rekening]["debet_" . $thn_ex])) {
                                            $rekenings[$cat][$row->rekening]["debet_" . $thn_ex] = 0;
                                        }
                                        if (!isset($rekenings[$cat][$row->rekening]["kredit_" . $thn_ex])) {
                                            $rekenings[$cat][$row->rekening]["kredit_" . $thn_ex] = 0;
                                        }

                                        $rekenings[$cat][$row->rekening]["debet_" . $thn_ex] += $debet;
                                        $rekenings[$cat][$row->rekening]["kredit_" . $thn_ex] += $kredit;
                                        $rekenings[$cat][$row->rekening]["link"] = $link;
                                        //endregion

                                        //region index per-cabang
                                        $rekeningsCab[$cabID][$cat][$row->rekening]["rek_id"] = "";
                                        $rekeningsCab[$cabID][$cat][$row->rekening]["rekening"] = $row->rekening;
                                        $rekeningsCab[$cabID][$cat][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekeningsCab[$cabID][$cat][$row->rekening]["debet_" . $thn_ex])) {
                                            $rekeningsCab[$cabID][$cat][$row->rekening]["debet_" . $thn_ex] = 0;
                                        }
                                        if (!isset($rekeningsCab[$cabID][$cat][$row->rekening]["kredit_" . $thn_ex])) {
                                            $rekeningsCab[$cabID][$cat][$row->rekening]["kredit_" . $thn_ex] = 0;
                                        }
                                        $rekeningsCab[$cabID][$cat][$row->rekening]["debet_" . $thn_ex] += $debet;
                                        $rekeningsCab[$cabID][$cat][$row->rekening]["kredit_" . $thn_ex] += $kredit;
                                        $rekeningsCab[$cabID][$cat][$row->rekening]["link"] = $link;
                                        //endregion

                                        //region konsolidasi
                                        $rekeningsKonsolidasi[$cat][$row->rekening]["rek_id"] = "";
                                        $rekeningsKonsolidasi[$cat][$row->rekening]["rekening"] = $row->rekening;
                                        $rekeningsKonsolidasi[$cat][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekeningsKonsolidasi[$cat][$row->rekening]["debet_" . $thn_ex])) {
                                            $rekeningsKonsolidasi[$cat][$row->rekening]["debet_" . $thn_ex] = 0;
                                        }
                                        if (!isset($rekeningsKonsolidasi[$cat][$row->rekening]["kredit_" . $thn_ex])) {
                                            $rekeningsKonsolidasi[$cat][$row->rekening]["kredit_" . $thn_ex] = 0;
                                        }
                                        $rekeningsKonsolidasi[$cat][$row->rekening]["debet_" . $thn_ex] += $debet;
                                        $rekeningsKonsolidasi[$cat][$row->rekening]["kredit_" . $thn_ex] += $kredit;
                                        $rekeningsKonsolidasi[$cat][$row->rekening]["link"] = $link;
                                        //endregion

                                        //region cabang selected, tahun
                                        $rekeningsTahun[$thn_ex][$cat][$row->rekening]["rek_id"] = "";
                                        $rekeningsTahun[$thn_ex][$cat][$row->rekening]["rekening"] = $row->rekening;
                                        $rekeningsTahun[$thn_ex][$cat][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekeningsTahun[$thn_ex][$cat][$row->rekening]["debet"])) {
                                            $rekeningsTahun[$thn_ex][$cat][$row->rekening]["debet"] = 0;
                                        }
                                        if (!isset($rekeningsTahun[$thn_ex][$cat][$row->rekening]["kredit"])) {
                                            $rekeningsTahun[$thn_ex][$cat][$row->rekening]["kredit"] = 0;
                                        }

                                        $rekeningsTahun[$thn_ex][$cat][$row->rekening]["debet"] += $debet;
                                        $rekeningsTahun[$thn_ex][$cat][$row->rekening]["kredit"] += $kredit;
                                        $rekeningsTahun[$thn_ex][$cat][$row->rekening]["link"] = $link;
                                        //endregion

                                        //region index per-cabang, tahun
                                        $rekeningsCabTahun[$thn_ex][$cabID][$cat][$row->rekening]["rek_id"] = "";
                                        $rekeningsCabTahun[$thn_ex][$cabID][$cat][$row->rekening]["rekening"] = $row->rekening;
                                        $rekeningsCabTahun[$thn_ex][$cabID][$cat][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekeningsCabTahun[$thn_ex][$cabID][$cat][$row->rekening]["debet"])) {
                                            $rekeningsCabTahun[$thn_ex][$cabID][$cat][$row->rekening]["debet"] = 0;
                                        }
                                        if (!isset($rekeningsCabTahun[$thn_ex][$cabID][$cat][$row->rekening]["kredit"])) {
                                            $rekeningsCabTahun[$thn_ex][$cabID][$cat][$row->rekening]["kredit"] = 0;
                                        }
                                        $rekeningsCabTahun[$thn_ex][$cabID][$cat][$row->rekening]["debet"] += $debet;
                                        $rekeningsCabTahun[$thn_ex][$cabID][$cat][$row->rekening]["kredit"] += $kredit;
                                        $rekeningsCabTahun[$thn_ex][$cabID][$cat][$row->rekening]["link"] = $link;
                                        //endregion

                                        //region konsolidasi, tahun
                                        $rekeningsKonsolidasiTahun[$thn_ex][$cat][$row->rekening]["rek_id"] = "";
                                        $rekeningsKonsolidasiTahun[$thn_ex][$cat][$row->rekening]["rekening"] = $row->rekening;
                                        $rekeningsKonsolidasiTahun[$thn_ex][$cat][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekeningsKonsolidasiTahun[$thn_ex][$cat][$row->rekening]["debet"])) {
                                            $rekeningsKonsolidasiTahun[$thn_ex][$cat][$row->rekening]["debet"] = 0;
                                        }
                                        if (!isset($rekeningsKonsolidasiTahun[$thn_ex][$cat][$row->rekening]["kredit"])) {
                                            $rekeningsKonsolidasiTahun[$thn_ex][$cat][$row->rekening]["kredit"] = 0;
                                        }
                                        $rekeningsKonsolidasiTahun[$thn_ex][$cat][$row->rekening]["debet"] += $debet;
                                        $rekeningsKonsolidasiTahun[$thn_ex][$cat][$row->rekening]["kredit"] += $kredit;
                                        $rekeningsKonsolidasiTahun[$thn_ex][$cat][$row->rekening]["link"] = $link;
                                        //endregion
                                    }
                                    else {
                                        $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                        //region cabang selected
                                        $rekenings[$row->kategori][$row->rekening]["rek_id"] = "";
                                        $rekenings[$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                        $rekenings[$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekenings[$row->kategori][$row->rekening]["debet_" . $thn_ex])) {
                                            $rekenings[$row->kategori][$row->rekening]["debet_" . $thn_ex] = 0;
                                        }
                                        if (!isset($rekenings[$row->kategori][$row->rekening]["kredit_" . $thn_ex])) {
                                            $rekenings[$row->kategori][$row->rekening]["kredit_" . $thn_ex] = 0;
                                        }
                                        $rekenings[$row->kategori][$row->rekening]["debet_" . $thn_ex] += $debet;
                                        $rekenings[$row->kategori][$row->rekening]["kredit_" . $thn_ex] += $kredit;
                                        $rekenings[$row->kategori][$row->rekening]["link"] = $link;
                                        //endregion

                                        //region index per-cabang
                                        $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rek_id"] = "";
                                        $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                        $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet_" . $thn_ex])) {
                                            $rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet_" . $thn_ex] = 0;
                                        }
                                        if (!isset($rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit_" . $thn_ex])) {
                                            $rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit_" . $thn_ex] = 0;
                                        }
                                        $rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet_" . $thn_ex] += $debet;
                                        $rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit_" . $thn_ex] += $kredit;
                                        $rekeningsCab[$cabID][$row->kategori][$row->rekening]["link"] = $link;
                                        //endregion

                                        //region konsolidasi
                                        $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rek_id"] = "";
                                        $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                        $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet_" . $thn_ex])) {
                                            $rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet_" . $thn_ex] = 0;
                                        }
                                        if (!isset($rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit_" . $thn_ex])) {
                                            $rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit_" . $thn_ex] = 0;
                                        }
                                        $rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet_" . $thn_ex] += $debet;
                                        $rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit_" . $thn_ex] += $kredit;
                                        $rekeningsKonsolidasi[$row->kategori][$row->rekening]["link"] = $link;
                                        //endregion

                                        //region cabang selected, tahun
                                        $rekeningsTahun[$thn_ex][$row->kategori][$row->rekening]["rek_id"] = "";
                                        $rekeningsTahun[$thn_ex][$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                        $rekeningsTahun[$thn_ex][$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekeningsTahun[$thn_ex][$row->kategori][$row->rekening]["debet"])) {
                                            $rekeningsTahun[$thn_ex][$row->kategori][$row->rekening]["debet"] = 0;
                                        }
                                        if (!isset($rekeningsTahun[$thn_ex][$row->kategori][$row->rekening]["kredit"])) {
                                            $rekeningsTahun[$thn_ex][$row->kategori][$row->rekening]["kredit"] = 0;
                                        }
                                        $rekeningsTahun[$thn_ex][$row->kategori][$row->rekening]["debet"] += $debet;
                                        $rekeningsTahun[$thn_ex][$row->kategori][$row->rekening]["kredit"] += $kredit;
                                        $rekeningsTahun[$thn_ex][$row->kategori][$row->rekening]["link"] = $link;
                                        //endregion

                                        //region index per-cabang, tahun
                                        $rekeningsCabTahun[$thn_ex][$cabID][$row->kategori][$row->rekening]["rek_id"] = "";
                                        $rekeningsCabTahun[$thn_ex][$cabID][$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                        $rekeningsCabTahun[$thn_ex][$cabID][$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekeningsCabTahun[$thn_ex][$cabID][$row->kategori][$row->rekening]["debet"])) {
                                            $rekeningsCabTahun[$thn_ex][$cabID][$row->kategori][$row->rekening]["debet"] = 0;
                                        }
                                        if (!isset($rekeningsCabTahun[$thn_ex][$cabID][$row->kategori][$row->rekening]["kredit"])) {
                                            $rekeningsCabTahun[$thn_ex][$cabID][$row->kategori][$row->rekening]["kredit"] = 0;
                                        }
                                        $rekeningsCabTahun[$thn_ex][$cabID][$row->kategori][$row->rekening]["debet"] += $debet;
                                        $rekeningsCabTahun[$thn_ex][$cabID][$row->kategori][$row->rekening]["kredit"] += $kredit;
                                        $rekeningsCabTahun[$thn_ex][$cabID][$row->kategori][$row->rekening]["link"] = $link;
                                        //endregion

                                        //region konsolidasi, tahun
                                        $rekeningsKonsolidasiTahun[$thn_ex][$row->kategori][$row->rekening]["rek_id"] = "";
                                        $rekeningsKonsolidasiTahun[$thn_ex][$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                        $rekeningsKonsolidasiTahun[$thn_ex][$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekeningsKonsolidasiTahun[$thn_ex][$row->kategori][$row->rekening]["debet"])) {
                                            $rekeningsKonsolidasiTahun[$thn_ex][$row->kategori][$row->rekening]["debet"] = 0;
                                        }
                                        if (!isset($rekeningsKonsolidasiTahun[$thn_ex][$row->kategori][$row->rekening]["kredit"])) {
                                            $rekeningsKonsolidasiTahun[$thn_ex][$row->kategori][$row->rekening]["kredit"] = 0;
                                        }
                                        $rekeningsKonsolidasiTahun[$thn_ex][$row->kategori][$row->rekening]["debet"] += $debet;
                                        $rekeningsKonsolidasiTahun[$thn_ex][$row->kategori][$row->rekening]["kredit"] += $kredit;
                                        $rekeningsKonsolidasiTahun[$thn_ex][$row->kategori][$row->rekening]["link"] = $link;
                                        //endregion
                                    }
                                }
                            }
                            else {
                                //region cabang selected
                                $rekenings[$row->kategori][$row->rekening]["rek_id"] = "";
                                $rekenings[$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                $rekenings[$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                if (!isset($rekenings[$row->kategori][$row->rekening]["debet"])) {
                                    $rekenings[$row->kategori][$row->rekening]["debet"] = 0;
                                }
                                if (!isset($rekenings[$row->kategori][$row->rekening]["kredit"])) {
                                    $rekenings[$row->kategori][$row->rekening]["kredit"] = 0;
                                }
                                $rekenings[$row->kategori][$row->rekening]["debet"] += $debet;
                                $rekenings[$row->kategori][$row->rekening]["kredit"] += $kredit;
                                $rekenings[$row->kategori][$row->rekening]["link"] = $link;
                                //endregion

                                //region index per-cabang
                                $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rek_id"] = "";
                                $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                if (!isset($rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"])) {
                                    $rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"] = 0;
                                }
                                if (!isset($rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"])) {
                                    $rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"] = 0;
                                }
                                $rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"] += $debet;
                                $rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"] += $kredit;
                                $rekeningsCab[$cabID][$row->kategori][$row->rekening]["link"] = $link;
                                //endregion

                                //region konsolidasi
                                $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rek_id"] = "";
                                $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                if (!isset($rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"])) {
                                    $rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"] = 0;
                                }
                                if (!isset($rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"])) {
                                    $rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"] = 0;
                                }
                                $rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"] += $debet;
                                $rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"] += $kredit;
                                $rekeningsKonsolidasi[$row->kategori][$row->rekening]["link"] = $link;
                                //endregion

                                //region cabang selected, tahun
                                $rekeningsTahun[$thn_ex][$row->kategori][$row->rekening]["rek_id"] = "";
                                $rekeningsTahun[$thn_ex][$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                $rekeningsTahun[$thn_ex][$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                if (!isset($rekeningsTahun[$thn_ex][$row->kategori][$row->rekening]["debet"])) {
                                    $rekeningsTahun[$thn_ex][$row->kategori][$row->rekening]["debet"] = 0;
                                }
                                if (!isset($rekeningsTahun[$thn_ex][$row->kategori][$row->rekening]["kredit"])) {
                                    $rekeningsTahun[$thn_ex][$row->kategori][$row->rekening]["kredit"] = 0;
                                }
                                $rekeningsTahun[$thn_ex][$row->kategori][$row->rekening]["debet"] += $debet;
                                $rekeningsTahun[$thn_ex][$row->kategori][$row->rekening]["kredit"] += $kredit;
                                $rekeningsTahun[$thn_ex][$row->kategori][$row->rekening]["link"] = $link;
                                //endregion

                                //region index per-cabang, tahun
                                $rekeningsCabTahun[$thn_ex][$cabID][$row->kategori][$row->rekening]["rek_id"] = "";
                                $rekeningsCabTahun[$thn_ex][$cabID][$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                $rekeningsCabTahun[$thn_ex][$cabID][$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                if (!isset($rekeningsCabTahun[$thn_ex][$cabID][$row->kategori][$row->rekening]["debet"])) {
                                    $rekeningsCabTahun[$thn_ex][$cabID][$row->kategori][$row->rekening]["debet"] = 0;
                                }
                                if (!isset($rekeningsCabTahun[$thn_ex][$cabID][$row->kategori][$row->rekening]["kredit"])) {
                                    $rekeningsCabTahun[$thn_ex][$cabID][$row->kategori][$row->rekening]["kredit"] = 0;
                                }
                                $rekeningsCabTahun[$thn_ex][$cabID][$row->kategori][$row->rekening]["debet"] += $debet;
                                $rekeningsCabTahun[$thn_ex][$cabID][$row->kategori][$row->rekening]["kredit"] += $kredit;
                                $rekeningsCabTahun[$thn_ex][$cabID][$row->kategori][$row->rekening]["link"] = $link;
                                //endregion

                                //region konsolidasi, tahun
                                $rekeningsKonsolidasiTahun[$thn_ex][$row->kategori][$row->rekening]["rek_id"] = "";
                                $rekeningsKonsolidasiTahun[$thn_ex][$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                $rekeningsKonsolidasiTahun[$thn_ex][$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                if (!isset($rekeningsKonsolidasiTahun[$thn_ex][$row->kategori][$row->rekening]["debet"])) {
                                    $rekeningsKonsolidasiTahun[$thn_ex][$row->kategori][$row->rekening]["debet"] = 0;
                                }
                                if (!isset($rekeningsKonsolidasiTahun[$thn_ex][$row->kategori][$row->rekening]["kredit"])) {
                                    $rekeningsKonsolidasiTahun[$thn_ex][$row->kategori][$row->rekening]["kredit"] = 0;
                                }
                                $rekeningsKonsolidasiTahun[$thn_ex][$row->kategori][$row->rekening]["debet"] += $debet;
                                $rekeningsKonsolidasiTahun[$thn_ex][$row->kategori][$row->rekening]["kredit"] += $kredit;
                                $rekeningsKonsolidasiTahun[$thn_ex][$row->kategori][$row->rekening]["link"] = $link;
                                //endregion
                            }
                        }
                    }
                }

            }
        }

        $arrResultRekening = array(
            "rekenings" => $rekenings,
            "rekeningsCabang" => isset($rekeningsCab) ? $rekeningsCab : array(),
            "rekeningsKonsolidasi" => isset($rekeningsKonsolidasi) ? $rekeningsKonsolidasi : array(),
            "rekeningsCompared" => $rekeningsTahun,
            "rekeningsCabangCompared" => isset($rekeningsCabTahun) ? $rekeningsCabTahun : array(),
            "rekeningsKonsolidasiCompared" => isset($rekeningsCabTahun) ? $rekeningsCabTahun : array(),
            "rekeningsName" => $rekeningsName,
            "categories" => $categories,
        );

//        arrPrintHijau($arrResultRekening["rekenings"]);
//        arrPrintPink($arrResultRekening["rekeningsCabang"]);
//        arrPrintWebs($arrResultRekening["rekeningsKonsolidasi"]);
        return $arrResultRekening;
    }


    // periode = bulanan, tahunan
    public function getRugilaba($cabangID, $periode, $defaultDate)
    {
        $defaultDate_ex = explode("-", $defaultDate);
        $tahun = $defaultDate_ex[0];
        $bulan = $defaultDate_ex[1];

        $d_start = "$tahun-$bulan-01";
        $d_last = formatTanggal($d_start, "t");
        $d_stop = "$tahun-$bulan-$d_last";


        $rl = new MdlRugilaba();
        if (is_array($cabangID)) {
            if (sizeof($cabangID) > 0) {
                $rl->addFilter("cabang_id in ('" . implode("','", $cabangID) . "')");
            }
        }
        else {
            $rl->addFilter("cabang_id='$cabangID'");
        }
        $rl->addFilter("periode='$periode'");
        $tmp = $rl->fetchBalances($defaultDate);

        $categories = array();
        $rekenings = array();
//        $rekeningsCab = array();
//        $rekeningsKonsolidasi = array();
        $rekeningsName = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $cabID = $row->cabang_id;
                foreach ($this->categoryRL as $k => $catSpec) {
                    if (array_key_exists($row->rekening, $catSpec)) {

                        if (!isset($rekeningsName[$k])) {
                            $rekeningsName[$k] = array();
                        }
                        if (!in_array($row->rekening, $this->rekException)) {
                            if ($row->debet > 0) {
                                $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                $value = $value > 0 ? $value * -1 : $value;
                            }
                            else {
                                $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                $value = $value < 0 ? $value * -1 : $value;
                            }
                        }
                        else {
                            if ($row->debet > 0) {
                                $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                            }
                            else {
                                $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                            }
                        }

                        $rek_nama_alias = isset($this->accountAlias[$row->rekening]) ? $this->accountAlias[$row->rekening] : $row->rekening;
//                        $tmpCol = array(
//                            "rek_id" => "",
//                            "rekening" => isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening,
//                            "values" => $value,
//                            "link" => "",
//                        );
                        $link = "";
                        if (isset($this->accountChilds[$row->rekening])) {
                            $link .= "<a href='" . base_url() . "Ledger/viewBalances_l1_periode/" . $this->accountChilds[$row->rekening] . "/" . $row->rekening . "?o=$cabangID&date1=$d_start&date2=$d_stop&periode=bulanan' title='view detail $rek_nama_alias'><span class='fa fa-clone'></span></a>";
                        }
                        $link .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=$cabangID&date1=$d_start&date2=$d_stop' title='view mutasi $rek_nama_alias'><span class='glyphicon glyphicon-time'></span></a></span>";


                        //region cabang selected
                        if (!isset($rekenings[$k])) {
                            $rekenings[$k] = array();
                        }
                        $rekenings[$k][$row->rekening]["rek_id"] = "";
                        $rekenings[$k][$row->rekening]["rekening"] = $row->rekening;
                        $rekenings[$k][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                        $rekenings[$k][$row->rekening]["values"] = $value;
                        $rekenings[$k][$row->rekening]["link"] = $link;
                        //endregion

                        //region index per-cabang selected
                        if (!isset($rekeningsCab[$cabID][$k])) {
                            $rekeningsCab[$cabID][$k] = array();
                        }
                        $rekeningsCab[$cabID][$k][$row->rekening]["rek_id"] = "";
                        $rekeningsCab[$cabID][$k][$row->rekening]["rekening"] = $row->rekening;
                        $rekeningsCab[$cabID][$k][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                        $rekeningsCab[$cabID][$k][$row->rekening]["values"] = $value;
                        $rekeningsCab[$cabID][$k][$row->rekening]["link"] = $link;
                        //endregion

                        //region konsolidasian selected
                        if (!isset($rekeningsKonsolidasi[$k])) {
                            $rekeningsKonsolidasi[$k] = array();
                        }
                        $rekeningsKonsolidasi[$k][$row->rekening]["rek_id"] = "";
                        $rekeningsKonsolidasi[$k][$row->rekening]["rekening"] = $row->rekening;
                        $rekeningsKonsolidasi[$k][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                        if (!isset($rekeningsKonsolidasi[$k][$row->rekening]["values"])) {
                            $rekeningsKonsolidasi[$k][$row->rekening]["values"] = 0;
                        }
                        $rekeningsKonsolidasi[$k][$row->rekening]["values"] += $value;
                        $rekeningsKonsolidasi[$k][$row->rekening]["link"] = "";
                        //endregion


                        if (array_key_exists($row->rekening, $this->accountNetto)) {
                            $value_netto = $value != null ? $value : 0;
                            $target_rekening = $this->accountNetto[$row->rekening];
                            //region cabang selected
                            if (!isset($rekeningsNetto[$k])) {
                                $rekenings[$k] = array();
                            }
                            $rekeningsNetto[$k][$target_rekening]["rek_id"] = "";
                            $rekeningsNetto[$k][$target_rekening]["rekening"] = $target_rekening;
                            $rekeningsNetto[$k][$target_rekening]["rekening_alias"] = $rek_nama_alias;
                            $rekeningsNetto[$k][$target_rekening]["values"] = $value_netto;
                            $rekeningsNetto[$k][$target_rekening]["link"] = $link;
                            //endregion

                            //region index per-cabang selected
                            if (!isset($rekeningsCabNetto[$cabID][$k])) {
                                $rekeningsCabNetto[$cabID][$k] = array();
                            }
                            $rekeningsCabNetto[$cabID][$k][$target_rekening]["rek_id"] = "";
                            $rekeningsCabNetto[$cabID][$k][$target_rekening]["rekening"] = $target_rekening;
                            $rekeningsCabNetto[$cabID][$k][$target_rekening]["rekening_alias"] = $rek_nama_alias;
                            $rekeningsCabNetto[$cabID][$k][$target_rekening]["values"] = $value_netto;
                            $rekeningsCabNetto[$cabID][$k][$target_rekening]["link"] = $link;
                            //endregion

                            //region konsolidasian selected
                            if (!isset($rekeningsKonsolidasiNetto[$k])) {
                                $rekeningsKonsolidasiNetto[$k] = array();
                            }
                            $rekeningsKonsolidasiNetto[$k][$target_rekening]["rek_id"] = "";
                            $rekeningsKonsolidasiNetto[$k][$target_rekening]["rekening"] = $target_rekening;
                            $rekeningsKonsolidasiNetto[$k][$target_rekening]["rekening_alias"] = $rek_nama_alias;
                            if (!isset($rekeningsKonsolidasiNetto[$k][$target_rekening]["values"])) {
                                $rekeningsKonsolidasiNetto[$k][$target_rekening]["values"] = 0;
                            }
                            $rekeningsKonsolidasiNetto[$k][$target_rekening]["values"] += $value_netto;
                            $rekeningsKonsolidasiNetto[$k][$target_rekening]["link"] = "";
                            //endregion
                        }
                        else {
                            //region cabang selected
                            if (!isset($rekeningsNetto[$k])) {
                                $rekenings[$k] = array();
                            }
                            $rekeningsNetto[$k][$row->rekening]["rek_id"] = "";
                            $rekeningsNetto[$k][$row->rekening]["rekening"] = $row->rekening;
                            $rekeningsNetto[$k][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                            $rekeningsNetto[$k][$row->rekening]["values"] = $value;
                            $rekeningsNetto[$k][$row->rekening]["link"] = $link;
                            //endregion

                            //region index per-cabang selected
                            if (!isset($rekeningsCabNetto[$cabID][$k])) {
                                $rekeningsCabNetto[$cabID][$k] = array();
                            }
                            $rekeningsCabNetto[$cabID][$k][$row->rekening]["rek_id"] = "";
                            $rekeningsCabNetto[$cabID][$k][$row->rekening]["rekening"] = $row->rekening;
                            $rekeningsCabNetto[$cabID][$k][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                            $rekeningsCabNetto[$cabID][$k][$row->rekening]["values"] = $value;
                            $rekeningsCabNetto[$cabID][$k][$row->rekening]["link"] = $link;
                            //endregion

                            //region konsolidasian selected
                            if (!isset($rekeningsKonsolidasiNetto[$k])) {
                                $rekeningsKonsolidasiNetto[$k] = array();
                            }
                            $rekeningsKonsolidasiNetto[$k][$row->rekening]["rek_id"] = "";
                            $rekeningsKonsolidasiNetto[$k][$row->rekening]["rekening"] = $row->rekening;
                            $rekeningsKonsolidasiNetto[$k][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                            if (!isset($rekeningsKonsolidasiNetto[$k][$row->rekening]["values"])) {
                                $rekeningsKonsolidasiNetto[$k][$row->rekening]["values"] = 0;
                            }
                            $rekeningsKonsolidasiNetto[$k][$row->rekening]["values"] += $value;
                            $rekeningsKonsolidasiNetto[$k][$row->rekening]["link"] = "";
                            //endregion
                        }
                    }
                }

            }
//            reset($dates);
//            $oldDate = key($dates);
        }

        $arrResultRekening = array(
            "rekenings" => $rekenings,
            "rekeningsCabang" => isset($rekeningsCab) ? $rekeningsCab : array(),
            "rekeningsKonsolidasi" => isset($rekeningsKonsolidasi) ? $rekeningsKonsolidasi : array(),

            "rekeningsNetto" => isset($rekeningsNetto) ? $rekeningsNetto : array(),
            "rekeningsCabangNetto" => isset($rekeningsCabNetto) ? $rekeningsCabNetto : array(),
            "rekeningsKonsolidasiNetto" => isset($rekeningsKonsolidasiNetto) ? $rekeningsKonsolidasiNetto : array(),

            "rekeningsName" => $rekeningsName,
            "categories" => $categories,
        );
//        arrPrintHijau($arrResultRekening["rekenings"]);
//        arrPrintPink($arrResultRekening["rekeningsCabang"]);
//        arrPrintWebs($arrResultRekening["rekeningsKonsolidasi"]);
        return $arrResultRekening;
    }

    public function getRugilabaTtm($cabangID, $periode, $defaultDate)
    {
        foreach ($defaultDate as $tahun_ex) {
            $ner = new MdlRugilaba();
            $ner->addFilter("periode='bulanan'");
            if (is_array($cabangID)) {
                if (sizeof($cabangID) > 0) {
                    $ner->addFilter("cabang_id in ('" . implode("','", $cabangID) . "')");
                }
            }
            else {
                $ner->addFilter("cabang_id='$cabangID'");
            }
            $tmp[$tahun_ex] = $ner->fetchBalances($tahun_ex);//$defaultDate
//            showLast_query("biru");
        }
//arrPrintHijau($tmp["2021-07"]);
        $arrCabang = array();
        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        $rekeningsKonsolidasiNilai = array();
        $rekeningsKonsolidasiKanan = array();
        $rekeningsNetto = array();
        $rekeningsKonsolidasiNilaiNetto = array();
        $rekeningsKonsolidasiKananNetto = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $thn_ex => $thn_ex_spec) {
                foreach ($thn_ex_spec as $row) {
//                    arrPrintPink($row);
//                    cekHere(":: " . $row->rekening);
                    $defPos = detectRekDefaultPosition($row->rekening);
                    $rek_name = $row->rekening;
                    $rek_name_alias = isset($this->accountAlias[$row->rekening]) ? $this->accountAlias[$row->rekening] : $row->rekening;
                    foreach ($this->categoryRL as $k => $catSpec) {
                        if (array_key_exists($row->rekening, $catSpec)) {
                            $arrCabang[$row->cabang_id] = isset($arrCabangs[$row->cabang_id]) ? $arrCabangs[$row->cabang_id] : "";

                            if (!in_array($row->rekening, $this->rekException)) {
                                if ($row->debet > 0) {
                                    $value = detectRekByPosition($row->rekening, $row->debet, "debet") * -1;
                                    $value = $value > 0 ? $value * -1 : $value;
                                }
                                else {
                                    $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                    $value = $value < 0 ? $value * -1 : $value;
                                }
                            }
                            else {
                                if ($row->debet > 0) {
                                    $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                }
                                else {
                                    $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                }
                            }


                            //region data per-cabang
                            if (!isset($rekenings[$thn_ex][$k][$row->cabang_id])) {
                                $rekenings[$thn_ex][$k][$row->cabang_id] = array();
                            }
                            if (!isset($rekeningsName[$k])) {
                                $rekeningsName[$k] = array();
                            }

//                            if (!in_array($row->rekening, $this->rekException)) {
//                                if ($row->debet > 0) {
//                                    $value = detectRekByPosition($row->rekening, $row->debet, "debet") * -1;
//                                    $value = $value > 0 ? $value * -1 : $value;
//                                }
//                                else {
//                                    $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
//                                    $value = $value < 0 ? $value * -1 : $value;
//                                }
//                            }
//                            else {
//                                if ($row->debet > 0) {
//                                    $value = detectRekByPosition($row->rekening, $row->debet, "debet");
//                                }
//                                else {
//                                    $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
//                                }
//                            }

                            if (!isset($rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['values'])) {
                                $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['values'] = 0;
                            }
                            $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['rek_id'] = "";
                            $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['rekening'] = $row->rekening;
                            $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['rekening_alias'] = isset($this->accountAlias[$row->rekening]) ? $this->accountAlias[$row->rekening] : $row->rekening;
                            $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['values'] += ($value != null) ? $value : 0;
                            $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['link'] = "";

//                            $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewDetail_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";
//                            $link_detail = isset($accountChilds[$row->rekening]) ? "<span class='pull-right'><a href='" . base_url() . "Ledger/viewBalances_l1_periode/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate&date1=$defaultDate' target='_blank'><span class='fa fa-clone'></span></a></span>" : "";
//                            $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['link'] = $link;
//                            $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['link_detail'] = $link_detail;

                            //endregion

                            //region data konsolidasian
                            if (!isset($rekeningsKonsolidasiNilai[$thn_ex][$k])) {
                                $rekeningsKonsolidasiNilai[$thn_ex][$k] = array();
                            }
                            if (!isset($rekeningsName[$k])) {
                                $rekeningsName[$k] = array();
                            }

//                            if (!in_array($row->rekening, $this->rekException)) {
//                                if ($row->debet > 0) {
//                                    $value = detectRekByPosition($row->rekening, $row->debet, "debet") * -1;
//                                    $value = $value > 0 ? $value * -1 : $value;
//                                }
//                                else {
//                                    $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
//                                    $value = $value < 0 ? $value * -1 : $value;
//                                }
//                            }
//                            else {
//                                if ($row->debet > 0) {
//                                    $value = detectRekByPosition($row->rekening, $row->debet, "debet");
//                                }
//                                else {
//                                    $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
//                                }
//                            }

                            if (!isset($rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['values'])) {
                                $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['values'] = 0;
                            }
                            $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['rek_id'] = "";
                            $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['rekening'] = $row->rekening;
                            $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['rekening_alias'] = isset($this->accountAlias[$row->rekening]) ? $this->accountAlias[$row->rekening] : $row->rekening;
                            $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['values'] += ($value != null) ? $value : 0;
                            $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['link'] = "";
//                            if($thn_ex == "2021-07"){
//                                cekHere($row->rekening . " :: " . $k . " :: " . $value);
//                            }

//                                    $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewDetail_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";
//                                    $link_detail = isset($accountChilds[$row->rekening]) ? "<span class='pull-right'><a href='" . base_url() . "Ledger/viewBalances_l1_periode/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate&date1=$defaultDate' target='_blank'><span class='fa fa-clone'></span></a></span>" : "";
//                            $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['link'] = "";
//                            $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['link_detail'] = "";

                            //endregion

                            //region data konsolidasian total kanan
                            if (!isset($rekeningsKonsolidasiKanan[$k])) {
                                $rekeningsKonsolidasiKanan[$k] = array();
                            }

//                                    if (!isset($rekeningsName[$k])) {
//                                        $rekeningsName[$k] = array();
//                                    }
//
//                                    if (!in_array($row->rekening, $rekException)) {
//                                        if ($row->debet > 0) {
//                                            $value = detectRekByPosition($row->rekening, $row->debet, "debet") * -1;
//                                            $value = $value > 0 ? $value * -1 : $value;
//                                        }
//                                        else {
//                                            $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
//                                            $value = $value < 0 ? $value * -1 : $value;
//                                        }
//                                    }
//                                    else {
//                                        if ($row->debet > 0) {
//                                            $value = detectRekByPosition($row->rekening, $row->debet, "debet");
//                                        }
//                                        else {
//                                            $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
//                                        }
//                                    }

                            if (!isset($rekeningsKonsolidasiKanan[$k][$row->rekening]['values'])) {
                                $rekeningsKonsolidasiKanan[$k][$row->rekening]['values'] = 0;
                            }
                            $rekeningsKonsolidasiKanan[$k][$row->rekening]['rek_id'] = "";
                            $rekeningsKonsolidasiKanan[$k][$row->rekening]['rekening'] = $row->rekening;
                            $rekeningsKonsolidasiKanan[$k][$row->rekening]['rekening_alias'] = isset($this->accountAlias[$row->rekening]) ? $this->accountAlias[$row->rekening] : $row->rekening;
                            $rekeningsKonsolidasiKanan[$k][$row->rekening]['values'] += ($value != null) ? $value : 0;
//                            $rekeningsKonsolidasiKanan[$k][$row->rekening]['link'] = "";
//
//                            $rekeningsKonsolidasiKanan[$k][$row->rekening]['link'] = "";
//                            $rekeningsKonsolidasiKanan[$k][$row->rekening]['link_detail'] = "";

                            //endregion

                            if (array_key_exists($row->rekening, $this->accountNetto)) {
                                $value_netto = $value != null ? $value : 0;
                                $target_rekening = $this->accountNetto[$row->rekening];

                                //region data per-cabang
                                if (!isset($rekeningsNetto[$thn_ex][$k][$row->cabang_id])) {
                                    $rekeningsNetto[$thn_ex][$k][$row->cabang_id] = array();
                                }
                                if (!isset($rekeningsName[$k])) {
                                    $rekeningsName[$k] = array();
                                }

                                if (!isset($rekeningsNetto[$thn_ex][$k][$row->cabang_id][$target_rekening]['values'])) {
                                    $rekeningsNetto[$thn_ex][$k][$row->cabang_id][$target_rekening]['values'] = 0;
                                }
                                $rekeningsNetto[$thn_ex][$k][$row->cabang_id][$target_rekening]['rek_id'] = "";
                                $rekeningsNetto[$thn_ex][$k][$row->cabang_id][$target_rekening]['rekening'] = $target_rekening;
                                $rekeningsNetto[$thn_ex][$k][$row->cabang_id][$target_rekening]['rekening_alias'] = isset($this->accountAlias[$target_rekening]) ? $this->accountAlias[$target_rekening] : $target_rekening;
                                $rekeningsNetto[$thn_ex][$k][$row->cabang_id][$target_rekening]['values'] += ($value_netto != null) ? $value_netto : 0;
                                $rekeningsNetto[$thn_ex][$k][$row->cabang_id][$target_rekening]['link'] = "";
                                //endregion

                                //region data konsolidasian
                                if (!isset($rekeningsKonsolidasiNilaiNetto[$thn_ex][$k])) {
                                    $rekeningsKonsolidasiNilaiNetto[$thn_ex][$k] = array();
                                }
                                if (!isset($rekeningsName[$k])) {
                                    $rekeningsName[$k] = array();
                                }

                                if (!isset($rekeningsKonsolidasiNilaiNetto[$thn_ex][$k][$target_rekening]['values'])) {
                                    $rekeningsKonsolidasiNilaiNetto[$thn_ex][$k][$target_rekening]['values'] = 0;
                                }
                                $rekeningsKonsolidasiNilaiNetto[$thn_ex][$k][$target_rekening]['rek_id'] = "";
                                $rekeningsKonsolidasiNilaiNetto[$thn_ex][$k][$target_rekening]['rekening'] = $target_rekening;
                                $rekeningsKonsolidasiNilaiNetto[$thn_ex][$k][$target_rekening]['rekening_alias'] = isset($this->accountAlias[$target_rekening]) ? $this->accountAlias[$target_rekening] : $target_rekening;
                                $rekeningsKonsolidasiNilaiNetto[$thn_ex][$k][$target_rekening]['values'] += ($value_netto != null) ? $value_netto : 0;
                                $rekeningsKonsolidasiNilaiNetto[$thn_ex][$k][$target_rekening]['link'] = "";

                                //endregion

                                //region data konsolidasian total kanan
                                if (!isset($rekeningsKonsolidasiKananNetto[$k])) {
                                    $rekeningsKonsolidasiKananNetto[$k] = array();
                                }

                                if (!isset($rekeningsKonsolidasiKananNetto[$k][$target_rekening]['values'])) {
                                    $rekeningsKonsolidasiKananNetto[$k][$target_rekening]['values'] = 0;
                                }
                                $rekeningsKonsolidasiKananNetto[$k][$target_rekening]['rek_id'] = "";
                                $rekeningsKonsolidasiKananNetto[$k][$target_rekening]['rekening'] = $target_rekening;
                                $rekeningsKonsolidasiKananNetto[$k][$target_rekening]['rekening_alias'] = isset($this->accountAlias[$target_rekening]) ? $this->accountAlias[$target_rekening] : $target_rekening;
                                $rekeningsKonsolidasiKananNetto[$k][$target_rekening]['values'] += ($value_netto != null) ? $value_netto : 0;
                                //endregion
                            }
                            else {
                                //region data per-cabang
                                if (!isset($rekeningsNetto[$thn_ex][$k][$row->cabang_id])) {
                                    $rekeningsNetto[$thn_ex][$k][$row->cabang_id] = array();
                                }
                                if (!isset($rekeningsName[$k])) {
                                    $rekeningsName[$k] = array();
                                }

                                if (!isset($rekeningsNetto[$thn_ex][$k][$row->cabang_id][$row->rekening]['values'])) {
                                    $rekeningsNetto[$thn_ex][$k][$row->cabang_id][$row->rekening]['values'] = 0;
                                }
                                $rekeningsNetto[$thn_ex][$k][$row->cabang_id][$row->rekening]['rek_id'] = "";
                                $rekeningsNetto[$thn_ex][$k][$row->cabang_id][$row->rekening]['rekening'] = $row->rekening;
                                $rekeningsNetto[$thn_ex][$k][$row->cabang_id][$row->rekening]['rekening_alias'] = isset($this->accountAlias[$row->rekening]) ? $this->accountAlias[$row->rekening] : $row->rekening;
                                $rekeningsNetto[$thn_ex][$k][$row->cabang_id][$row->rekening]['values'] += ($value != null) ? $value : 0;
                                $rekeningsNetto[$thn_ex][$k][$row->cabang_id][$row->rekening]['link'] = "";
                                //endregion

                                //region data konsolidasian
                                if (!isset($rekeningsKonsolidasiNilaiNetto[$thn_ex][$k])) {
                                    $rekeningsKonsolidasiNilaiNetto[$thn_ex][$k] = array();
                                }
                                if (!isset($rekeningsName[$k])) {
                                    $rekeningsName[$k] = array();
                                }

                                if (!isset($rekeningsKonsolidasiNilaiNetto[$thn_ex][$k][$row->rekening]['values'])) {
                                    $rekeningsKonsolidasiNilaiNetto[$thn_ex][$k][$row->rekening]['values'] = 0;
                                }
                                $rekeningsKonsolidasiNilaiNetto[$thn_ex][$k][$row->rekening]['rek_id'] = "";
                                $rekeningsKonsolidasiNilaiNetto[$thn_ex][$k][$row->rekening]['rekening'] = $row->rekening;
                                $rekeningsKonsolidasiNilaiNetto[$thn_ex][$k][$row->rekening]['rekening_alias'] = isset($this->accountAlias[$row->rekening]) ? $this->accountAlias[$row->rekening] : $row->rekening;
                                $rekeningsKonsolidasiNilaiNetto[$thn_ex][$k][$row->rekening]['values'] += ($value != null) ? $value : 0;
                                $rekeningsKonsolidasiNilaiNetto[$thn_ex][$k][$row->rekening]['link'] = "";

                                //endregion

                                //region data konsolidasian total kanan
                                if (!isset($rekeningsKonsolidasiKananNetto[$k])) {
                                    $rekeningsKonsolidasiKananNetto[$k] = array();
                                }

                                if (!isset($rekeningsKonsolidasiKananNetto[$k][$row->rekening]['values'])) {
                                    $rekeningsKonsolidasiKananNetto[$k][$row->rekening]['values'] = 0;
                                }
                                $rekeningsKonsolidasiKananNetto[$k][$row->rekening]['rek_id'] = "";
                                $rekeningsKonsolidasiKananNetto[$k][$row->rekening]['rekening'] = $row->rekening;
                                $rekeningsKonsolidasiKananNetto[$k][$row->rekening]['rekening_alias'] = isset($this->accountAlias[$row->rekening]) ? $this->accountAlias[$row->rekening] : $row->rekening;
                                $rekeningsKonsolidasiKananNetto[$k][$row->rekening]['values'] += ($value != null) ? $value : 0;

                                //endregion

                            }
                        }
                    }
                }
            }
        }

        $arrResultRekening = array(
            "rekenings" => $rekenings,
            "rekeningsCabang" => isset($rekeningsCab) ? $rekeningsCab : array(),
            "rekeningsKonsolidasi" => isset($rekeningsKonsolidasiNilai) ? $rekeningsKonsolidasiNilai : array(),
            "rekeningsKonsolidasiTotal" => isset($rekeningsKonsolidasiKanan) ? $rekeningsKonsolidasiKanan : array(),

            "rekeningsNetto" => isset($rekeningsNetto) ? $rekeningsNetto : array(),
            "rekeningsCabangNetto" => isset($rekeningsCabNetto) ? $rekeningsCabNetto : array(),
            "rekeningsKonsolidasiNetto" => isset($rekeningsKonsolidasiNilaiNetto) ? $rekeningsKonsolidasiNilaiNetto : array(),
            "rekeningsKonsolidasiTotalNetto" => isset($rekeningsKonsolidasiKananNetto) ? $rekeningsKonsolidasiKananNetto : array(),

            "rekeningsName" => $rekeningsName,
            "categories" => $categories,
        );
//        arrPrintHijau($arrResultRekening["rekenings"]);
//        arrPrintPink($arrResultRekening["rekeningsCabang"]);
//        arrPrintWebs($arrResultRekening["rekeningsKonsolidasi"]);
//        arrPrintWebs($arrResultRekening["rekeningsKonsolidasiTotal"]);

        return $arrResultRekening;
    }

    public function getRugilabaYtd($cabangID, $periode = "tahunan", $defaultDate)
    {

        $cr = New ComRekening_cli();
        $n = New ComNeraca_cli();
        $rl = New ComRugiLaba_cli();

        if (!is_array($cabangID)) {
            $cabangIDs = array($cabangID);
        }
        else {
            $cabangIDs = $cabangID;
        }
        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        $arrCabang = array();

        foreach ($cabangIDs as $cabangID) {

            $static = array(
                "static" => array(
                    "cabang_id" => $cabangID,
                    "dtime" => date("Y-m-d H:i:s"),
                    "fulldate" => date("Y-m-d"),
                    "bln" => date("m"),
                    "thn" => date("Y"),
                    "periode" => $periode,
                ),
            );

            //------ ambil saldo rek_master_cache
            $cr->addFilter("cabang_id='$cabangID'");
            $cr->addFilter("thn='" . date("Y") . "'");
            $cr->addFilter("periode='$periode'");
//            if (is_array($cabangID)) {
//                if (sizeof($cabangID) > 0) {
//                    $cr->addFilter("cabang_id in ('" . implode("','", $cabangID) . "')");
//                }
//            }
//            else {
//                $cr->addFilter("cabang_id='$cabangID'");
//            }
            $crTmp = $cr->lookupAll()->result();
//            showLast_query("biru");
            $lajur = array();
            if (sizeof($crTmp) > 0) {
                foreach ($crTmp as $spec) {
                    $lajur[$spec->rekening] = array(
                        "rek_id" => $spec->rek_id,
                        "rekening" => $spec->rekening,
                        "debet" => $spec->debet,
                        "kredit" => $spec->kredit,
                        "periode" => $spec->periode,
                    );
                }
            }

            //------ masuk com ke rugilaba
//        $rl->setFilters2($filters2);
//        $rl->setFilters($filters);
            $rl->pairNoCut_view($static, $lajur);
            $resultRL = $rl->execNoCut_view();

            //------ masuk com ke neraca
//        $n->setFilters2($filters2);
//        $n->setFilters($filters);
            $n->pairNoCut_view($static, $resultRL['neraca']);
            $resultNeraca = $n->execNoCut_view();
//arrPrintHijau($resultNeraca);
//arrPrintHijau($resultRL['rugilaba']);

            $tmp = array();
            if (sizeof($resultRL['rugilaba']) > 0) {
                foreach ($resultRL['rugilaba'] as $nn => $nSpec) {
                    $temp = array();
                    foreach ($nSpec as $key => $val) {
                        $temp[$key] = $val;
                    }
                    $tmp[$nn] = (object)$temp;
                }
            }
//arrPrintPink($tmp);
            $oldDate = "";
//        $categories = array();
//        $rekenings = array();
//        $rekeningsName = array();
            if (sizeof($tmp) > 0) {
                foreach ($tmp as $row) {
                    $cabID = $row->cabang_id;
                    foreach ($this->categoryRL as $k => $catSpec) {
                        if (array_key_exists($row->rekening, $catSpec)) {
                            $arrCabang[$row->cabang_id] = isset($arrCabangs[$row->cabang_id]) ? $arrCabangs[$row->cabang_id] : "";

                            if (!isset($rekeningsName[$k])) {
                                $rekeningsName[$k] = array();
                            }
                            if (!in_array($row->rekening, $this->rekException)) {
                                if ($row->debet > 0) {
                                    $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                    $value = $value > 0 ? $value * -1 : $value;
                                }
                                else {
                                    $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                    $value = $value < 0 ? $value * -1 : $value;
                                }
                            }
                            else {
                                if ($row->debet > 0) {
                                    $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                }
                                else {
                                    $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                }
                            }

                            $rek_nama_alias = isset($this->accountAlias[$row->rekening]) ? $this->accountAlias[$row->rekening] : $row->rekening;
//                        $tmpCol = array(
//                            "rek_id" => "",
//                            "rekening" => isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening,
//                            "values" => $value,
//                            "link" => "",
//                        );

                            $link = "";
                            if (isset($this->accountChilds[$row->rekening])) {
                                $link .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $this->accountChilds[$row->rekening] . "/" . $row->rekening . "'><span class='fa fa-clone'></span></a>";
//                            $link .= "<a href='" . base_url() . "Ledger/viewBalances_l1_periode/" . $this->accountChilds[$row->rekening] . "/" . $row->rekening . "?o=$cabangID&date1=$d_start&date2=$d_stop&periode=bulanan' title='view detail $rek_nama_alias'><span class='fa fa-clone'></span></a>";
                            }
                            $link .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoves_l1/Rekening/" . $row->rekening . "'><span class='glyphicon glyphicon-time'></span></a></span>";
//                        $link .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=$cabangID&date1=$d_start&date2=$d_stop' title='view mutasi $rek_nama_alias'><span class='glyphicon glyphicon-time'></span></a></span>";

                            //region cabang selected
                            if (!isset($rekenings[$k])) {
                                $rekenings[$k] = array();
                            }
                            $rekenings[$k][$row->rekening]["rek_id"] = "";
                            $rekenings[$k][$row->rekening]["rekening"] = $row->rekening;
                            $rekenings[$k][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                            $rekenings[$k][$row->rekening]["values"] = $value;
                            $rekenings[$k][$row->rekening]["link"] = $link;
                            //endregion

                            //region index per-cabang selected
                            if (!isset($rekeningsCab[$cabID][$k])) {
                                $rekeningsCab[$cabID][$k] = array();
                            }
                            $rekeningsCab[$cabID][$k][$row->rekening]["rek_id"] = "";
                            $rekeningsCab[$cabID][$k][$row->rekening]["rekening"] = $row->rekening;
                            $rekeningsCab[$cabID][$k][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                            $rekeningsCab[$cabID][$k][$row->rekening]["values"] = $value;
                            $rekeningsCab[$cabID][$k][$row->rekening]["link"] = $link;
                            //endregion

                            //region konsolidasian selected
                            if (!isset($rekeningsKonsolidasi[$k])) {
                                $rekeningsKonsolidasi[$k] = array();
                            }
                            $rekeningsKonsolidasi[$k][$row->rekening]["rek_id"] = "";
                            $rekeningsKonsolidasi[$k][$row->rekening]["rekening"] = $row->rekening;
                            $rekeningsKonsolidasi[$k][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                            if (!isset($rekeningsKonsolidasi[$k][$row->rekening]["values"])) {
                                $rekeningsKonsolidasi[$k][$row->rekening]["values"] = 0;
                            }
                            $rekeningsKonsolidasi[$k][$row->rekening]["values"] += $value;
                            $rekeningsKonsolidasi[$k][$row->rekening]["link"] = "";
                            //endregion

                            //region index per-cabang selected
                            if (!isset($rekeningsCabCat[$k][$cabID])) {
                                $rekeningsCabCat[$k][$cabID] = array();
                            }
                            $rekeningsCabCat[$k][$cabID][$row->rekening]["rek_id"] = "";
                            $rekeningsCabCat[$k][$cabID][$row->rekening]["rekening"] = $row->rekening;
                            $rekeningsCabCat[$k][$cabID][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                            $rekeningsCabCat[$k][$cabID][$row->rekening]["values"] = $value;
                            $rekeningsCabCat[$k][$cabID][$row->rekening]["link"] = $link;
                            //endregion


                            if (array_key_exists($row->rekening, $this->accountNetto)) {
                                $value_netto = $value != null ? $value : 0;
                                $target_rekening = $this->accountNetto[$row->rekening];

                                //region cabang selected
                                if (!isset($rekeningsNetto[$k])) {
                                    $rekeningsNetto[$k] = array();
                                }
                                $rekeningsNetto[$k][$target_rekening]["rek_id"] = "";
                                $rekeningsNetto[$k][$target_rekening]["rekening"] = $target_rekening;
                                $rekeningsNetto[$k][$target_rekening]["rekening_alias"] = $rek_nama_alias;
                                $rekeningsNetto[$k][$target_rekening]["values"] = $value_netto;
                                $rekeningsNetto[$k][$target_rekening]["link"] = $link;
                                //endregion

                                //region index per-cabang selected
                                if (!isset($rekeningsCabNetto[$cabID][$k])) {
                                    $rekeningsCabNetto[$cabID][$k] = array();
                                }
                                $rekeningsCabNetto[$cabID][$k][$target_rekening]["rek_id"] = "";
                                $rekeningsCabNetto[$cabID][$k][$target_rekening]["rekening"] = $target_rekening;
                                $rekeningsCabNetto[$cabID][$k][$target_rekening]["rekening_alias"] = $rek_nama_alias;
                                $rekeningsCabNetto[$cabID][$k][$target_rekening]["values"] = $value_netto;
                                $rekeningsCabNetto[$cabID][$k][$target_rekening]["link"] = $link;
                                //endregion

                                //region konsolidasian selected
                                if (!isset($rekeningsKonsolidasiNetto[$k])) {
                                    $rekeningsKonsolidasiNetto[$k] = array();
                                }
                                $rekeningsKonsolidasiNetto[$k][$target_rekening]["rek_id"] = "";
                                $rekeningsKonsolidasiNetto[$k][$target_rekening]["rekening"] = $target_rekening;
                                $rekeningsKonsolidasiNetto[$k][$target_rekening]["rekening_alias"] = $rek_nama_alias;
                                if (!isset($rekeningsKonsolidasiNetto[$k][$target_rekening]["values"])) {
                                    $rekeningsKonsolidasiNetto[$k][$target_rekening]["values"] = 0;
                                }
                                $rekeningsKonsolidasiNetto[$k][$target_rekening]["values"] += $value_netto;
                                $rekeningsKonsolidasiNetto[$k][$target_rekening]["link"] = "";
                                //endregion

                                //region index per-cabang selected
                                if (!isset($rekeningsCabCatNetto[$k][$cabID])) {
                                    $rekeningsCabCatNetto[$k][$cabID] = array();
                                }
                                $rekeningsCabCatNetto[$k][$cabID][$target_rekening]["rek_id"] = "";
                                $rekeningsCabCatNetto[$k][$cabID][$target_rekening]["rekening"] = $target_rekening;
                                $rekeningsCabCatNetto[$k][$cabID][$target_rekening]["rekening_alias"] = $rek_nama_alias;
                                $rekeningsCabCatNetto[$k][$cabID][$target_rekening]["values"] = $value_netto;
                                $rekeningsCabCatNetto[$k][$cabID][$target_rekening]["link"] = $link;
                                //endregion
                            }
                            else {
                                //region cabang selected
                                if (!isset($rekeningsNetto[$k])) {
                                    $rekeningsNetto[$k] = array();
                                }
                                $rekeningsNetto[$k][$row->rekening]["rek_id"] = "";
                                $rekeningsNetto[$k][$row->rekening]["rekening"] = $row->rekening;
                                $rekeningsNetto[$k][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                                $rekeningsNetto[$k][$row->rekening]["values"] = $value;
                                $rekeningsNetto[$k][$row->rekening]["link"] = $link;
                                //endregion

                                //region index per-cabang selected
                                if (!isset($rekeningsCabNetto[$cabID][$k])) {
                                    $rekeningsCabNetto[$cabID][$k] = array();
                                }
                                $rekeningsCabNetto[$cabID][$k][$row->rekening]["rek_id"] = "";
                                $rekeningsCabNetto[$cabID][$k][$row->rekening]["rekening"] = $row->rekening;
                                $rekeningsCabNetto[$cabID][$k][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                                $rekeningsCabNetto[$cabID][$k][$row->rekening]["values"] = $value;
                                $rekeningsCabNetto[$cabID][$k][$row->rekening]["link"] = $link;
                                //endregion

                                //region konsolidasian selected
                                if (!isset($rekeningsKonsolidasiNetto[$k])) {
                                    $rekeningsKonsolidasiNetto[$k] = array();
                                }
                                $rekeningsKonsolidasiNetto[$k][$row->rekening]["rek_id"] = "";
                                $rekeningsKonsolidasiNetto[$k][$row->rekening]["rekening"] = $row->rekening;
                                $rekeningsKonsolidasiNetto[$k][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                                if (!isset($rekeningsKonsolidasiNetto[$k][$row->rekening]["values"])) {
                                    $rekeningsKonsolidasiNetto[$k][$row->rekening]["values"] = 0;
                                }
                                $rekeningsKonsolidasiNetto[$k][$row->rekening]["values"] += $value;
                                $rekeningsKonsolidasiNetto[$k][$row->rekening]["link"] = "";
                                //endregion

                                //region index per-cabang selected
                                if (!isset($rekeningsCabCatNetto[$k][$cabID])) {
                                    $rekeningsCabCatNetto[$k][$cabID] = array();
                                }
                                $rekeningsCabCatNetto[$k][$cabID][$row->rekening]["rek_id"] = "";
                                $rekeningsCabCatNetto[$k][$cabID][$row->rekening]["rekening"] = $row->rekening;
                                $rekeningsCabCatNetto[$k][$cabID][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                                $rekeningsCabCatNetto[$k][$cabID][$row->rekening]["values"] = $value;
                                $rekeningsCabCatNetto[$k][$cabID][$row->rekening]["link"] = $link;
                                //endregion
                            }

                        }
                    }

                }
//            reset($dates);
//            $oldDate = key($dates);
            }

        }

        $arrResultRekening = array(
            "rekenings" => $rekenings,
            "rekeningsCabang" => isset($rekeningsCab) ? $rekeningsCab : array(),
            "rekeningsCabangCategory" => isset($rekeningsCabCat) ? $rekeningsCabCat : array(),
            "rekeningsKonsolidasi" => isset($rekeningsKonsolidasi) ? $rekeningsKonsolidasi : array(),

            "rekeningsNetto" => isset($rekeningsNetto) ? $rekeningsNetto : array(),
            "rekeningsCabangNetto" => isset($rekeningsCabNetto) ? $rekeningsCabNetto : array(),
            "rekeningsKonsolidasiNetto" => isset($rekeningsKonsolidasiNetto) ? $rekeningsKonsolidasiNetto : array(),
            "rekeningsKonsolidasiTotalNetto" => isset($rekeningsKonsolidasiKananNetto) ? $rekeningsKonsolidasiKananNetto : array(),
            "rekeningsCabangCategoryNetto" => isset($rekeningsCabCatNetto) ? $rekeningsCabCatNetto : array(),

            "rekeningsName" => $rekeningsName,
            "categories" => $categories,
            "arrCabang" => $arrCabang,
        );

//        arrPrintHijau($arrResultRekening["rekenings"]);
//        arrPrintPink($arrResultRekening["rekeningsCabang"]);
//        arrPrintWebs($arrResultRekening["rekeningsKonsolidasi"]);
        return $arrResultRekening;


    }

    public function getRugilabaMtd($cabangID, $periode = "bulanan", $defaultDate)
    {

        $cr = New ComRekening_cli();
        $n = New ComNeraca_cli();
        $rl = New ComRugiLaba_cli();

        if (!is_array($cabangID)) {
            $cabangIDs = array($cabangID);
        }
        else {
            $cabangIDs = $cabangID;
        }
        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        $arrCabang = array();

        foreach ($cabangIDs as $cabangID) {

            $static = array(
                "static" => array(
                    "cabang_id" => $cabangID,
                    "dtime" => date("Y-m-d H:i:s"),
                    "fulldate" => date("Y-m-d"),
                    "bln" => date("m"),
                    "thn" => date("Y"),
                    "periode" => $periode,
                ),
            );

            //------ ambil saldo rek_master_cache
            $cr->addFilter("cabang_id='$cabangID'");
            $cr->addFilter("bln='" . date("m") . "'");
            $cr->addFilter("thn='" . date("Y") . "'");
            $cr->addFilter("periode='$periode'");
//            if (is_array($cabangID)) {
//                if (sizeof($cabangID) > 0) {
//                    $cr->addFilter("cabang_id in ('" . implode("','", $cabangID) . "')");
//                }
//            }
//            else {
//                $cr->addFilter("cabang_id='$cabangID'");
//            }
            $crTmp = $cr->lookupAll()->result();
            showLast_query("biru");
            $lajur = array();
            if (sizeof($crTmp) > 0) {
                foreach ($crTmp as $spec) {
                    $lajur[$spec->rekening] = array(
                        "rek_id" => $spec->rek_id,
                        "rekening" => $spec->rekening,
                        "debet" => $spec->debet,
                        "kredit" => $spec->kredit,
                        "periode" => $spec->periode,
                    );
                }
            }

            //------ masuk com ke rugilaba
//        $rl->setFilters2($filters2);
//        $rl->setFilters($filters);
            $rl->pairNoCut_view($static, $lajur);
            $resultRL = $rl->execNoCut_view();

            //------ masuk com ke neraca
//        $n->setFilters2($filters2);
//        $n->setFilters($filters);
            $n->pairNoCut_view($static, $resultRL['neraca']);
            $resultNeraca = $n->execNoCut_view();
//arrPrintHijau($resultNeraca);
//arrPrintHijau($resultRL['rugilaba']);

            $tmp = array();
            if (sizeof($resultRL['rugilaba']) > 0) {
                foreach ($resultRL['rugilaba'] as $nn => $nSpec) {
                    $temp = array();
                    foreach ($nSpec as $key => $val) {
                        $temp[$key] = $val;
                    }
                    $tmp[$nn] = (object)$temp;
                }
            }
//arrPrintPink($tmp);
            $oldDate = "";
//        $categories = array();
//        $rekenings = array();
//        $rekeningsName = array();
            if (sizeof($tmp) > 0) {
                foreach ($tmp as $row) {
                    $cabID = $row->cabang_id;
                    foreach ($this->categoryRL as $k => $catSpec) {
                        if (array_key_exists($row->rekening, $catSpec)) {
                            $arrCabang[$row->cabang_id] = isset($arrCabangs[$row->cabang_id]) ? $arrCabangs[$row->cabang_id] : "";

                            if (!isset($rekeningsName[$k])) {
                                $rekeningsName[$k] = array();
                            }
                            if (!in_array($row->rekening, $this->rekException)) {
                                if ($row->debet > 0) {
                                    $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                    $value = $value > 0 ? $value * -1 : $value;
                                }
                                else {
                                    $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                    $value = $value < 0 ? $value * -1 : $value;
                                }
                            }
                            else {
                                if ($row->debet > 0) {
                                    $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                }
                                else {
                                    $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                }
                            }

                            $rek_nama_alias = isset($this->accountAlias[$row->rekening]) ? $this->accountAlias[$row->rekening] : $row->rekening;
//                        $tmpCol = array(
//                            "rek_id" => "",
//                            "rekening" => isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening,
//                            "values" => $value,
//                            "link" => "",
//                        );

                            $link = "";
                            if (isset($this->accountChilds[$row->rekening])) {
                                $link .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $this->accountChilds[$row->rekening] . "/" . $row->rekening . "'><span class='fa fa-clone'></span></a>";
//                            $link .= "<a href='" . base_url() . "Ledger/viewBalances_l1_periode/" . $this->accountChilds[$row->rekening] . "/" . $row->rekening . "?o=$cabangID&date1=$d_start&date2=$d_stop&periode=bulanan' title='view detail $rek_nama_alias'><span class='fa fa-clone'></span></a>";
                            }
                            $link .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoves_l1/Rekening/" . $row->rekening . "'><span class='glyphicon glyphicon-time'></span></a></span>";
//                        $link .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=$cabangID&date1=$d_start&date2=$d_stop' title='view mutasi $rek_nama_alias'><span class='glyphicon glyphicon-time'></span></a></span>";

                            //region cabang selected
                            if (!isset($rekenings[$k])) {
                                $rekenings[$k] = array();
                            }
                            $rekenings[$k][$row->rekening]["rek_id"] = "";
                            $rekenings[$k][$row->rekening]["rekening"] = $row->rekening;
                            $rekenings[$k][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                            $rekenings[$k][$row->rekening]["values"] = $value;
                            $rekenings[$k][$row->rekening]["link"] = $link;
                            //endregion

                            //region index per-cabang selected
                            if (!isset($rekeningsCab[$cabID][$k])) {
                                $rekeningsCab[$cabID][$k] = array();
                            }
                            $rekeningsCab[$cabID][$k][$row->rekening]["rek_id"] = "";
                            $rekeningsCab[$cabID][$k][$row->rekening]["rekening"] = $row->rekening;
                            $rekeningsCab[$cabID][$k][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                            $rekeningsCab[$cabID][$k][$row->rekening]["values"] = $value;
                            $rekeningsCab[$cabID][$k][$row->rekening]["link"] = $link;
                            //endregion

                            //region konsolidasian selected
                            if (!isset($rekeningsKonsolidasi[$k])) {
                                $rekeningsKonsolidasi[$k] = array();
                            }
                            $rekeningsKonsolidasi[$k][$row->rekening]["rek_id"] = "";
                            $rekeningsKonsolidasi[$k][$row->rekening]["rekening"] = $row->rekening;
                            $rekeningsKonsolidasi[$k][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                            if (!isset($rekeningsKonsolidasi[$k][$row->rekening]["values"])) {
                                $rekeningsKonsolidasi[$k][$row->rekening]["values"] = 0;
                            }
                            $rekeningsKonsolidasi[$k][$row->rekening]["values"] += $value;
                            $rekeningsKonsolidasi[$k][$row->rekening]["link"] = "";
                            //endregion

                            //region index per-cabang selected
                            if (!isset($rekeningsCabCat[$k][$cabID])) {
                                $rekeningsCabCat[$k][$cabID] = array();
                            }
                            $rekeningsCabCat[$k][$cabID][$row->rekening]["rek_id"] = "";
                            $rekeningsCabCat[$k][$cabID][$row->rekening]["rekening"] = $row->rekening;
                            $rekeningsCabCat[$k][$cabID][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                            $rekeningsCabCat[$k][$cabID][$row->rekening]["values"] = $value;
                            $rekeningsCabCat[$k][$cabID][$row->rekening]["link"] = $link;
                            //endregion


//                        $rekenings[$k][$row->rekening] = $tmpCol;
                            if (array_key_exists($row->rekening, $this->accountNetto)) {
                                $value_netto = $value != null ? $value : 0;
                                $target_rekening = $this->accountNetto[$row->rekening];

                                //region cabang selected
                                if (!isset($rekeningsNetto[$k])) {
                                    $rekeningsNetto[$k] = array();
                                }
                                $rekeningsNetto[$k][$target_rekening]["rek_id"] = "";
                                $rekeningsNetto[$k][$target_rekening]["rekening"] = $target_rekening;
                                $rekeningsNetto[$k][$target_rekening]["rekening_alias"] = $rek_nama_alias;
                                $rekeningsNetto[$k][$target_rekening]["values"] = $value_netto;
                                $rekeningsNetto[$k][$target_rekening]["link"] = $link;
                                //endregion

                                //region index per-cabang selected
                                if (!isset($rekeningsCabNetto[$cabID][$k])) {
                                    $rekeningsCabNetto[$cabID][$k] = array();
                                }
                                $rekeningsCabNetto[$cabID][$k][$target_rekening]["rek_id"] = "";
                                $rekeningsCabNetto[$cabID][$k][$target_rekening]["rekening"] = $target_rekening;
                                $rekeningsCabNetto[$cabID][$k][$target_rekening]["rekening_alias"] = $rek_nama_alias;
                                $rekeningsCabNetto[$cabID][$k][$target_rekening]["values"] = $value_netto;
                                $rekeningsCabNetto[$cabID][$k][$target_rekening]["link"] = $link;
                                //endregion

                                //region konsolidasian selected
                                if (!isset($rekeningsKonsolidasiNetto[$k])) {
                                    $rekeningsKonsolidasiNetto[$k] = array();
                                }
                                $rekeningsKonsolidasiNetto[$k][$target_rekening]["rek_id"] = "";
                                $rekeningsKonsolidasiNetto[$k][$target_rekening]["rekening"] = $target_rekening;
                                $rekeningsKonsolidasiNetto[$k][$target_rekening]["rekening_alias"] = $rek_nama_alias;
                                if (!isset($rekeningsKonsolidasiNetto[$k][$target_rekening]["values"])) {
                                    $rekeningsKonsolidasiNetto[$k][$target_rekening]["values"] = 0;
                                }
                                $rekeningsKonsolidasiNetto[$k][$target_rekening]["values"] += $value_netto;
                                $rekeningsKonsolidasiNetto[$k][$target_rekening]["link"] = "";
                                //endregion

                                //region index per-cabang selected
                                if (!isset($rekeningsCabCatNetto[$k][$cabID])) {
                                    $rekeningsCabCatNetto[$k][$cabID] = array();
                                }
                                $rekeningsCabCatNetto[$k][$cabID][$target_rekening]["rek_id"] = "";
                                $rekeningsCabCatNetto[$k][$cabID][$target_rekening]["rekening"] = $target_rekening;
                                $rekeningsCabCatNetto[$k][$cabID][$target_rekening]["rekening_alias"] = $rek_nama_alias;
                                $rekeningsCabCatNetto[$k][$cabID][$target_rekening]["values"] = $value_netto;
                                $rekeningsCabCatNetto[$k][$cabID][$target_rekening]["link"] = $link;
                                //endregion

                            }
                            else {

                                //region cabang selected
                                if (!isset($rekeningsNetto[$k])) {
                                    $rekeningsNetto[$k] = array();
                                }
                                $rekeningsNetto[$k][$row->rekening]["rek_id"] = "";
                                $rekeningsNetto[$k][$row->rekening]["rekening"] = $row->rekening;
                                $rekeningsNetto[$k][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                                $rekeningsNetto[$k][$row->rekening]["values"] = $value;
                                $rekeningsNetto[$k][$row->rekening]["link"] = $link;
                                //endregion

                                //region index per-cabang selected
                                if (!isset($rekeningsCabNetto[$cabID][$k])) {
                                    $rekeningsCabNetto[$cabID][$k] = array();
                                }
                                $rekeningsCabNetto[$cabID][$k][$row->rekening]["rek_id"] = "";
                                $rekeningsCabNetto[$cabID][$k][$row->rekening]["rekening"] = $row->rekening;
                                $rekeningsCabNetto[$cabID][$k][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                                $rekeningsCabNetto[$cabID][$k][$row->rekening]["values"] = $value;
                                $rekeningsCabNetto[$cabID][$k][$row->rekening]["link"] = $link;
                                //endregion

                                //region konsolidasian selected
                                if (!isset($rekeningsKonsolidasiNetto[$k])) {
                                    $rekeningsKonsolidasiNetto[$k] = array();
                                }
                                $rekeningsKonsolidasiNetto[$k][$row->rekening]["rek_id"] = "";
                                $rekeningsKonsolidasiNetto[$k][$row->rekening]["rekening"] = $row->rekening;
                                $rekeningsKonsolidasiNetto[$k][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                                if (!isset($rekeningsKonsolidasiNetto[$k][$row->rekening]["values"])) {
                                    $rekeningsKonsolidasiNetto[$k][$row->rekening]["values"] = 0;
                                }
                                $rekeningsKonsolidasiNetto[$k][$row->rekening]["values"] += $value;
                                $rekeningsKonsolidasiNetto[$k][$row->rekening]["link"] = "";
                                //endregion

                                //region index per-cabang selected
                                if (!isset($rekeningsCabCatNetto[$k][$cabID])) {
                                    $rekeningsCabCatNetto[$k][$cabID] = array();
                                }
                                $rekeningsCabCatNetto[$k][$cabID][$row->rekening]["rek_id"] = "";
                                $rekeningsCabCatNetto[$k][$cabID][$row->rekening]["rekening"] = $row->rekening;
                                $rekeningsCabCatNetto[$k][$cabID][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                                $rekeningsCabCatNetto[$k][$cabID][$row->rekening]["values"] = $value;
                                $rekeningsCabCatNetto[$k][$cabID][$row->rekening]["link"] = $link;
                                //endregion

                            }
                        }
                    }
                }
//            reset($dates);
//            $oldDate = key($dates);
            }

        }

        $arrResultRekening = array(
            "rekenings" => $rekenings,
            "rekeningsCabang" => isset($rekeningsCab) ? $rekeningsCab : array(),
            "rekeningsCabangCategory" => isset($rekeningsCabCat) ? $rekeningsCabCat : array(),
            "rekeningsKonsolidasi" => isset($rekeningsKonsolidasi) ? $rekeningsKonsolidasi : array(),

            "rekeningsNetto" => isset($rekeningsNetto) ? $rekeningsNetto : array(),
            "rekeningsCabangNetto" => isset($rekeningsCabNetto) ? $rekeningsCabNetto : array(),
            "rekeningsKonsolidasiNetto" => isset($rekeningsKonsolidasiNetto) ? $rekeningsKonsolidasiNetto : array(),
            "rekeningsKonsolidasiTotalNetto" => isset($rekeningsKonsolidasiKananNetto) ? $rekeningsKonsolidasiKananNetto : array(),
            "rekeningsCabangCategoryNetto" => isset($rekeningsCabCatNetto) ? $rekeningsCabCatNetto : array(),

            "rekeningsName" => $rekeningsName,
            "categories" => $categories,
            "arrCabang" => $arrCabang,
        );

//        arrPrintHijau($arrResultRekening["rekenings"]);
//        arrPrintPink($arrResultRekening["rekeningsCabang"]);
//        arrPrintWebs($arrResultRekening["rekeningsKonsolidasi"]);
        return $arrResultRekening;


    }

    public function getRugilabaMtd_($cabangID, $periode = "bulanan", $defaultDate)
    {

        $cr = New ComRekening_cli();
        $n = New ComNeraca_cli();
        $rl = New ComRugiLaba_cli();

        $static = array(
            "static" => array(
                "cabang_id" => $cabangID,
                "dtime" => date("Y-m-d H:i:s"),
                "fulldate" => date("Y-m-d"),
                "bln" => date("m"),
                "thn" => date("Y"),
                "periode" => $periode,
            ),
        );

        //------ ambil saldo rek_master_cache
        $cr->addFilter("bln='" . date("m") . "'");
        $cr->addFilter("thn='" . date("Y") . "'");
        $cr->addFilter("periode='$periode'");
        if (is_array($cabangID)) {
            if (sizeof($cabangID) > 0) {
                $cr->addFilter("cabang_id in ('" . implode("','", $cabangID) . "')");
            }
        }
        else {
            $cr->addFilter("cabang_id='$cabangID'");
        }
        $crTmp = $cr->lookupAll()->result();
        $lajur = array();
        if (sizeof($crTmp) > 0) {
            foreach ($crTmp as $spec) {
                $lajur[$spec->rekening] = array(
                    "rek_id" => $spec->rek_id,
                    "rekening" => $spec->rekening,
                    "debet" => $spec->debet,
                    "kredit" => $spec->kredit,
                    "periode" => $spec->periode,
                );
            }
        }

        //region ------ masuk com ke rugilaba
//        $rl->setFilters2($filters2);
//        $rl->setFilters($filters);
        $rl->pairNoCut_view($static, $lajur);
        $resultRL = $rl->execNoCut_view();
        //endregion

        //region ------ masuk com ke neraca
//        $n->setFilters2($filters2);
//        $n->setFilters($filters);
        $n->pairNoCut_view($static, $resultRL['neraca']);
        $resultNeraca = $n->execNoCut_view();
//arrPrintHijau($resultNeraca);
        //endregion

        $tmp = array();
        if (sizeof($resultRL['rugilaba']) > 0) {
            foreach ($resultRL['rugilaba'] as $nn => $nSpec) {
                $temp = array();
                foreach ($nSpec as $key => $val) {
                    $temp[$key] = $val;
                }
                $tmp[$nn] = (object)$temp;
            }
        }

        $oldDate = "";
        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $cabID = $row->cabang_id;
                foreach ($this->categoryRL as $k => $catSpec) {
                    if (array_key_exists($row->rekening, $catSpec)) {

                        if (!isset($rekeningsName[$k])) {
                            $rekeningsName[$k] = array();
                        }
                        if (!in_array($row->rekening, $this->rekException)) {
                            if ($row->debet > 0) {
                                $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                $value = $value > 0 ? $value * -1 : $value;
                            }
                            else {
                                $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                $value = $value < 0 ? $value * -1 : $value;
                            }
                        }
                        else {
                            if ($row->debet > 0) {
                                $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                            }
                            else {
                                $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                            }
                        }

                        $rek_nama_alias = isset($this->accountAlias[$row->rekening]) ? $this->accountAlias[$row->rekening] : $row->rekening;
//                        $tmpCol = array(
//                            "rek_id" => "",
//                            "rekening" => isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening,
//                            "values" => $value,
//                            "link" => "",
//                        );

                        $link = "";
//                        if (isset($this->accountChilds[$row->rekening])) {
//                            $link .= "<a href='" . base_url() . "Ledger/viewBalances_l1_periode/" . $this->accountChilds[$row->rekening] . "/" . $row->rekening . "?o=$cabangID&date1=$d_start&date2=$d_stop&periode=bulanan' title='view detail $rek_nama_alias'><span class='fa fa-clone'></span></a>";
//                        }
//                        $link .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=$cabangID&date1=$d_start&date2=$d_stop' title='view mutasi $rek_nama_alias'><span class='glyphicon glyphicon-time'></span></a></span>";

                        //region cabang selected
                        if (!isset($rekenings[$k])) {
                            $rekenings[$k] = array();
                        }
                        $rekenings[$k][$row->rekening]["rek_id"] = "";
                        $rekenings[$k][$row->rekening]["rekening"] = $row->rekening;
                        $rekenings[$k][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                        $rekenings[$k][$row->rekening]["values"] = $value;
                        $rekenings[$k][$row->rekening]["link"] = $link;
                        //endregion

                        //region index per-cabang selected
                        if (!isset($rekeningsCab[$cabID][$k])) {
                            $rekeningsCab[$cabID][$k] = array();
                        }
                        $rekeningsCab[$cabID][$k][$row->rekening]["rek_id"] = "";
                        $rekeningsCab[$cabID][$k][$row->rekening]["rekening"] = $row->rekening;
                        $rekeningsCab[$cabID][$k][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                        $rekeningsCab[$cabID][$k][$row->rekening]["values"] = $value;
                        $rekeningsCab[$cabID][$k][$row->rekening]["link"] = $link;
                        //endregion

                        //region konsolidasian selected
                        if (!isset($rekeningsKonsolidasi[$k])) {
                            $rekeningsKonsolidasi[$k] = array();
                        }
                        $rekeningsKonsolidasi[$k][$row->rekening]["rek_id"] = "";
                        $rekeningsKonsolidasi[$k][$row->rekening]["rekening"] = $row->rekening;
                        $rekeningsKonsolidasi[$k][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                        if (!isset($rekeningsKonsolidasi[$k][$row->rekening]["values"])) {
                            $rekeningsKonsolidasi[$k][$row->rekening]["values"] = 0;
                        }
                        $rekeningsKonsolidasi[$k][$row->rekening]["values"] += $value;
                        $rekeningsKonsolidasi[$k][$row->rekening]["link"] = "";
                        //endregion
//                        $rekenings[$k][$row->rekening] = $tmpCol;
                    }
                }

            }
//            reset($dates);
//            $oldDate = key($dates);
        }

        $arrResultRekening = array(
            "rekenings" => $rekenings,
            "rekeningsCabang" => isset($rekeningsCab) ? $rekeningsCab : array(),
            "rekeningsKonsolidasi" => isset($rekeningsKonsolidasi) ? $rekeningsKonsolidasi : array(),
            "rekeningsName" => $rekeningsName,
            "categories" => $categories,
        );

//        arrPrintHijau($arrResultRekening["rekenings"]);
//        arrPrintPink($arrResultRekening["rekeningsCabang"]);
//        arrPrintWebs($arrResultRekening["rekeningsKonsolidasi"]);
        return $arrResultRekening;


    }

    public function getRugilabaCompared($cabangID, $periode, $defaultDate, $mode = "year")
    {
//        $defaultDate_ex = explode("-", $defaultDate);
//        $tahun = $defaultDate_ex[0];
//        $bulan = $defaultDate_ex[1];
//
//        $d_start = "$tahun-$bulan-01";
//        $d_last = formatTanggal($d_start, "t");
//        $d_stop = "$tahun-$bulan-$d_last";

        foreach ($defaultDate as $tahun_ex) {

            $rl = new MdlRugilaba();
            if (is_array($cabangID)) {
                if (sizeof($cabangID) > 0) {
                    $rl->addFilter("cabang_id in ('" . implode("','", $cabangID) . "')");
                }
            }
            else {
                $rl->addFilter("cabang_id='$cabangID'");
            }
            $rl->addFilter("periode='$periode'");
            $tmp[$tahun_ex] = $rl->fetchBalances($tahun_ex);
//            showLast_query("biru");
        }

        $categories = array();
        $rekenings = array();
//        $rekeningsCab = array();
//        $rekeningsKonsolidasi = array();
        $rekeningsName = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $thn_ex => $thn_ex_spec) {
                foreach ($thn_ex_spec as $row) {
                    if ($mode == "year") {
                        $thn_ex = $row->thn;
                    }
                    $cabID = $row->cabang_id;
                    foreach ($this->categoryRL as $k => $catSpec) {
                        if (array_key_exists($row->rekening, $catSpec)) {

                            if (!isset($rekeningsName[$k])) {
                                $rekeningsName[$k] = array();
                            }
                            if (!in_array($row->rekening, $this->rekException)) {
                                if ($row->debet > 0) {
                                    $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                    $value = $value > 0 ? $value * -1 : $value;
                                }
                                else {
                                    $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                    $value = $value < 0 ? $value * -1 : $value;
                                }
                            }
                            else {
                                if ($row->debet > 0) {
                                    $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                }
                                else {
                                    $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                }
                            }

                            $rek_nama_alias = isset($this->accountAlias[$row->rekening]) ? $this->accountAlias[$row->rekening] : $row->rekening;
//                        $tmpCol = array(
//                            "rek_id" => "",
//                            "rekening" => isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening,
//                            "values" => $value,
//                            "link" => "",
//                        );
                            $link = "";
//                        if (isset($this->accountChilds[$row->rekening])) {
//                            $link .= "<a href='" . base_url() . "Ledger/viewBalances_l1_periode/" . $this->accountChilds[$row->rekening] . "/" . $row->rekening . "?o=$cabangID&date1=$d_start&date2=$d_stop&periode=bulanan' title='view detail $rek_nama_alias'><span class='fa fa-clone'></span></a>";
//                        }
//                        $link .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=$cabangID&date1=$d_start&date2=$d_stop' title='view mutasi $rek_nama_alias'><span class='glyphicon glyphicon-time'></span></a></span>";


                            //region cabang selected
                            if (!isset($rekenings[$thn_ex][$k])) {
                                $rekenings[$thn_ex][$k] = array();
                            }
                            $rekenings[$thn_ex][$k][$row->rekening]["rek_id"] = "";
                            $rekenings[$thn_ex][$k][$row->rekening]["rekening"] = $row->rekening;
                            $rekenings[$thn_ex][$k][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                            $rekenings[$thn_ex][$k][$row->rekening]["values"] = $value;
                            $rekenings[$thn_ex][$k][$row->rekening]["link"] = $link;
                            //endregion

                            //region index per-cabang, thn, cabID, cat
                            if (!isset($rekeningsCab[$thn_ex][$cabID][$k])) {
                                $rekeningsCab[$thn_ex][$cabID][$k] = array();
                            }
                            $rekeningsCab[$thn_ex][$cabID][$k][$row->rekening]["rek_id"] = "";
                            $rekeningsCab[$thn_ex][$cabID][$k][$row->rekening]["rekening"] = $row->rekening;
                            $rekeningsCab[$thn_ex][$cabID][$k][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                            $rekeningsCab[$thn_ex][$cabID][$k][$row->rekening]["values"] = $value;
                            $rekeningsCab[$thn_ex][$cabID][$k][$row->rekening]["link"] = $link;
                            //endregion
                            //region index per-cabang, thn, cabID, cat
                            if (!isset($rekeningsCabCat[$thn_ex][$k][$cabID])) {
                                $rekeningsCabCat[$thn_ex][$k][$cabID] = array();
                            }
                            $rekeningsCabCat[$thn_ex][$k][$cabID][$row->rekening]["rek_id"] = "";
                            $rekeningsCabCat[$thn_ex][$k][$cabID][$row->rekening]["rekening"] = $row->rekening;
                            $rekeningsCabCat[$thn_ex][$k][$cabID][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                            $rekeningsCabCat[$thn_ex][$k][$cabID][$row->rekening]["values"] = $value;
                            $rekeningsCabCat[$thn_ex][$k][$cabID][$row->rekening]["link"] = $link;
                            $rekeningsCabCat[$thn_ex][$k][$cabID][$row->rekening]["link_detail"] = "";
                            //endregion

                            //region konsolidasian selected
                            if (!isset($rekeningsKonsolidasi[$thn_ex][$k])) {
                                $rekeningsKonsolidasi[$thn_ex][$k] = array();
                            }
                            $rekeningsKonsolidasi[$thn_ex][$k][$row->rekening]["rek_id"] = "";
                            $rekeningsKonsolidasi[$thn_ex][$k][$row->rekening]["rekening"] = $row->rekening;
                            $rekeningsKonsolidasi[$thn_ex][$k][$row->rekening]["rekening_alias"] = $rek_nama_alias;
                            if (!isset($rekeningsKonsolidasi[$thn_ex][$k][$row->rekening]["values"])) {
                                $rekeningsKonsolidasi[$thn_ex][$k][$row->rekening]["values"] = 0;
                            }
                            $rekeningsKonsolidasi[$thn_ex][$k][$row->rekening]["values"] += $value;
                            $rekeningsKonsolidasi[$thn_ex][$k][$row->rekening]["link"] = "";
                            //endregion

                        }
                    }

                }

            }
//            reset($dates);
//            $oldDate = key($dates);
        }

        $arrResultRekening = array(
            "rekenings" => $rekenings,
            "rekeningsCabang" => isset($rekeningsCab) ? $rekeningsCab : array(),
            "rekeningsCabangCategory" => isset($rekeningsCabCat) ? $rekeningsCabCat : array(),
            "rekeningsKonsolidasi" => isset($rekeningsKonsolidasi) ? $rekeningsKonsolidasi : array(),
            "rekeningsName" => $rekeningsName,
            "categories" => $categories,
        );
//        arrPrintHijau($arrResultRekening["rekenings"]);
//        arrPrintPink($arrResultRekening["rekeningsCabang"]);
//        arrPrintWebs($arrResultRekening["rekeningsKonsolidasi"]);
        return $arrResultRekening;
    }


    //------------------------------
    public function rebuildLajur($cabang_id, $periode, $tahun, $bulan = "")
    {
        $arrRekNetto = array(
            "3020060",
//            "laba ditahan",
        );
        $arrRekRugilabaBlacklist = array(
            "9010",
            "9020",
        );

        $arrRekeningCoa = rekening_coa_he_accounting();

        //region adjustment
        $natadj = New MdlNeracaAdjTmp();
        $natadj->addFilter("rebuild='0'");
        $natadj->addFilter("cabang_id='$cabang_id'");
        $natadj->addFilter("periode='$periode'");
        $natadj->addFilter("thn='$tahun'");
        $natadjTmp = $natadj->lookupAll()->result();
//        showLast_query("biru");
        $total_adj = array();
        if (sizeof($natadjTmp) > 0) {
            foreach ($natadjTmp as $spec) {
                if (!isset($total_adj['debet'])) {
                    $total_adj['debet'] = 0;
                }
                $total_adj['debet'] += $spec->debet;

                if (!isset($total_adj['kredit'])) {
                    $total_adj['kredit'] = 0;
                }
                $total_adj['kredit'] += $spec->kredit;
            }
            if (($total_adj['debet'] > 0) && ($total_adj['kredit'] > 0)) {
                $selisih = $total_adj['debet'] - $total_adj['kredit'];
                $selisih = $selisih < 0 ? ($selisih * -1) : $selisih;
                if ($selisih > 2) {
                    $msg = "Adjustment yang anda lakukan tidak balance. silahkan diperiksa kembali. Code: " . __LINE__;
                    mati_disini($msg);
                }
            }
            else {
                $msg = "Adjustment gagal disimpan karena rekening adjustment tidak ditemukan. silahkan diperiksa kembali. Code: " . __LINE__;
                mati_disini($msg);
            }
        }
        else {
            $msg = "Adjustment gagal disimpan karena rekening adjustment tidak ditemukan. silahkan diperiksa kembali. Code: " . __LINE__;
            mati_disini($msg);
        }
        //endregion

        //region neraca
        $nat = New MdlNeracaAdj();
        $nat->addFilter("rebuild='0'");
        $nat->addFilter("cabang_id='$cabang_id'");
        $nat->addFilter("periode='$periode'");
        $nat->addFilter("thn='$tahun'");
        $natTmp = $nat->lookupAll()->result();
//        showLast_query("biru");
        if (sizeof($natTmp) == 0) {
            $nat = New MdlNeraca();
            $nat->addFilter("cabang_id='$cabang_id'");
            $nat->addFilter("periode='$periode'");
            $nat->addFilter("thn='$tahun'");
            $natTmp = $nat->lookupAll()->result();
            showLast_query("kuning");
        }
        //endregion

        //region rugilaba
        $rla = New MdlRugilabaAdj();
        $rla->addFilter("rebuild='0'");
        $rla->addFilter("cabang_id='$cabang_id'");
        $rla->addFilter("periode='$periode'");
        $rla->addFilter("thn='$tahun'");
        $rlaTmp = $rla->lookupAll()->result();
//        showLast_query("biru");
        if (sizeof($rlaTmp) == 0) {
            $rla = New MdlRugilaba();
            $rla->addFilter("cabang_id='$cabang_id'");
            $rla->addFilter("periode='$periode'");
            $rla->addFilter("thn='$tahun'");
            $rlaTmp = $rla->lookupAll()->result();
            showLast_query("kuning");
        }
        //endregion

//
//        arrPrintKuning($natadjTmp);
//        arrPrintWebs($natTmp);
//        arrPrint($rlaTmp);

        $arrLajur = array();
        foreach ($natTmp as $nSpec) {
            $nSpec_clone = (array)$nSpec;
            $rekening = $nSpec->rekening;
            $debet = $nSpec->debet;
            $kredit = $nSpec->kredit;


            unset($nSpec_clone["debet"]);
            unset($nSpec_clone["kredit"]);

            if (!isset($arrLajur[$rekening])) {
                $arrLajur[$rekening] = $nSpec_clone;
            }

            if (!isset($arrLajur[$rekening]["debet"])) {
                $arrLajur[$rekening]["debet"] = 0;
            }
            $arrLajur[$rekening]["debet"] += $debet;

            if (!isset($arrLajur[$rekening]["kredit"])) {
                $arrLajur[$rekening]["kredit"] = 0;
            }
            $arrLajur[$rekening]["kredit"] += $kredit;

        }


        // rugilaba
        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            foreach ($rlaTmp as $rlSpec) {
                $rlSpec_clone = (array)$rlSpec;
                $debet = $rlSpec->debet;
                $kredit = $rlSpec->kredit;
                $rekening = $rlSpec->rekening;
                $rekening = !is_numeric($rekening) ? $arrRekeningCoa[$rekening] : $rekening;
                $rlSpec_clone["rekening"] = $rekening;
//cekUngu(":: $rekening ::");
                unset($rlSpec_clone["debet"]);
                unset($rlSpec_clone["kredit"]);

                if (!in_array($rekening, $arrRekRugilabaBlacklist)) {
                    $arrLajur[$rekening] = $rlSpec_clone;
                    if (!isset($arrLajur[$rekening]["debet"])) {
                        $arrLajur[$rekening]["debet"] = 0;
                    }
                    $arrLajur[$rekening]["debet"] += $debet;

                    if (!isset($arrLajur[$rekening]["kredit"])) {
                        $arrLajur[$rekening]["kredit"] = 0;
                    }
                    $arrLajur[$rekening]["kredit"] += $kredit;
                }
                else {
                    cekHitam("$rekening masuk sini");
                    $rekening_pengganti = "3020060";
                    $arrLajur[$rekening_pengganti]["rekening"] = $rekening_pengganti;
                    if ($debet > 0) {
                        if (!isset($arrLajur[$rekening_pengganti]["debet"])) {
                            $arrLajur[$rekening_pengganti]["debet"] = 0;
                        }
                        $arrLajur[$rekening_pengganti]["debet"] += $debet;
                    }
                    else {
                        if (!isset($arrLajur[$rekening_pengganti]["kredit"])) {
                            $arrLajur[$rekening_pengganti]["kredit"] = 0;
                        }
                        $arrLajur[$rekening_pengganti]["kredit"] += $kredit;
                    }
                }
            }
        }

        // adjustment
        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            foreach ($natadjTmp as $adjSpec) {
                $adjSpec_clone = (array)$adjSpec;
                $debet = $adjSpec->debet;
                $kredit = $adjSpec->kredit;
                $rekening = $adjSpec->rekening;
                $rekening = !is_numeric($rekening) ? $arrRekeningCoa[$rekening] : $rekening;
                $adjSpec_clone["rekening"] = $rekening;

                unset($adjSpec_clone["debet"]);
                unset($adjSpec_clone["kredit"]);

                if (!in_array($rekening, $arrRekRugilabaBlacklist)) {
                    if (!isset($arrLajur[$rekening])) {
                        $arrLajur[$rekening] = $adjSpec_clone;
                    }
                    if (!isset($arrLajur[$rekening]["debet"])) {
                        $arrLajur[$rekening]["debet"] = 0;
                    }
                    $arrLajur[$rekening]["debet"] += $debet;

                    if (!isset($arrLajur[$rekening]["kredit"])) {
                        $arrLajur[$rekening]["kredit"] = 0;
                    }
                    $arrLajur[$rekening]["kredit"] += $kredit;
                }
                else {
                    $rekening_pengganti = "3020060";
                    $arrLajur[$rekening_pengganti]["rekening"] = $rekening_pengganti;
                    if ($debet > 0) {
                        if (!isset($arrLajur[$rekening_pengganti]["debet"])) {
                            $arrLajur[$rekening_pengganti]["debet"] = 0;
                        }
                        $arrLajur[$rekening_pengganti]["debet"] += $debet;
                    }
                    else {
                        if (!isset($arrLajur[$rekening_pengganti]["kredit"])) {
                            $arrLajur[$rekening_pengganti]["kredit"] = 0;
                        }
                        $arrLajur[$rekening_pengganti]["kredit"] += $kredit;
                    }
                }
            }
        }

        foreach ($arrLajur as $rek => $rSpec) {
            if (($rSpec["debet"] > 0) && ($rSpec["kredit"] > 0)) {
                $def_position = detectRekDefaultPosition($rek);
                switch ($def_position) {
                    case "debet":
                        $netto = $rSpec["debet"] - $rSpec["kredit"];
                        if ($netto > 0) {
                            $arrLajur[$rek]["debet"] = $netto;
                            $arrLajur[$rek]["kredit"] = 0;
                        }
                        else {
                            $arrLajur[$rek]["debet"] = 0;
                            $arrLajur[$rek]["kredit"] = $netto * -1;
                        }

                        break;
                    case "kredit":
                        $netto = $rSpec["kredit"] - $rSpec["debet"];
                        if ($netto > 0) {
                            $arrLajur[$rek]["debet"] = 0;
                            $arrLajur[$rek]["kredit"] = $netto;
                        }
                        else {
                            $arrLajur[$rek]["debet"] = $netto * -1;
                            $arrLajur[$rek]["kredit"] = 0;
                        }
                        break;
                }
            }
        }


        //region total bawah
        $total = array();
        foreach ($arrLajur as $spec) {
            if (!isset($total['debet'])) {
                $total['debet'] = 0;
            }
            $total['debet'] += $spec['debet'];

            if (!isset($total['kredit'])) {
                $total['kredit'] = 0;
            }
            $total['kredit'] += $spec['kredit'];
        }
        //endregion

//        arrPrintWebs($total);
//        mati_disini(__LINE__);

        return $arrLajur;


    }

    public function rebuildLaporan($cabangID, $periode, $lajur, $tahun, $bulan = "")
    {
//        arrPrintKuning($lajur);

        $cr = New ComRekening_cli();
        $n = New ComNeraca_cli();
        $rl = New ComRugiLaba_cli();
        $static = array(
            "static" => array(
                "cabang_id" => $cabangID,
                "dtime" => date("Y-m-d H:i:s"),
                "fulldate" => date("Y-m-d"),
                "bln" => $bulan,
                "thn" => $tahun,
                "periode" => $periode,
            ),
        );

        //------ masuk com ke rugilaba
        $rl->pairNoCut_view($static, $lajur);
        $resultRL = $rl->execNoCut_view();

        //------ masuk com ke neraca
        $n->pairNoCut_view($static, $resultRL['neraca']);
        $resultNeraca = $n->execNoCut_view();
//        arrPrintHijau($resultRL['neraca']);
//        cekHere("--RUGILABA--");
//        arrPrintHijau($resultRL['rugilaba']);
//        cekHere("--NERACA--");
//        arrPrintPink($resultNeraca);

        $total = array();
        $resultNeraca_new = array();
//        foreach($resultRL['neraca'] as $nSpec){
        foreach ($resultNeraca as $nSpec) {
            if (!isset($total['debet'])) {
                $total['debet'] = 0;
            }
            $total['debet'] += $nSpec['debet'];

            if (!isset($total['kredit'])) {
                $total['kredit'] = 0;
            }
            $total['kredit'] += $nSpec['kredit'];

            //----------------
            $nSpec_clone = $nSpec;
            $debet = $nSpec_clone["debet"];
            $kredit = $nSpec_clone["kredit"];
            $rekening = $nSpec_clone["rekening"];
            unset($nSpec_clone["debet"]);
            unset($nSpec_clone["kredit"]);
            if (!isset($resultNeraca_new[$rekening])) {
                $resultNeraca_new[$rekening] = $nSpec_clone;
            }
            if (!isset($resultNeraca_new[$rekening]["debet"])) {
                $resultNeraca_new[$rekening]["debet"] = 0;
            }
            $resultNeraca_new[$rekening]["debet"] += $debet;

            if (!isset($resultNeraca_new[$rekening]["kredit"])) {
                $resultNeraca_new[$rekening]["kredit"] = 0;
            }
            $resultNeraca_new[$rekening]["kredit"] += $kredit;
        }
        foreach ($resultNeraca_new as $rek => $rSpec) {
            if (($rSpec["debet"] > 0) && ($rSpec["kredit"] > 0)) {
                $def_position = detectRekDefaultPosition($rek);
                switch ($def_position) {
                    case "debet":
                        $netto = $rSpec["debet"] - $rSpec["kredit"];
                        if ($netto > 0) {
                            $resultNeraca_new[$rek]["debet"] = $netto;
                            $resultNeraca_new[$rek]["kredit"] = 0;
                        }
                        else {
                            $resultNeraca_new[$rek]["debet"] = 0;
                            $resultNeraca_new[$rek]["kredit"] = $netto * -1;
                        }

                        break;
                    case "kredit":
                        $netto = $rSpec["kredit"] - $rSpec["debet"];
                        if ($netto > 0) {
                            $resultNeraca_new[$rek]["debet"] = 0;
                            $resultNeraca_new[$rek]["kredit"] = $netto;
                        }
                        else {
                            $resultNeraca_new[$rek]["debet"] = $netto * -1;
                            $resultNeraca_new[$rek]["kredit"] = 0;
                        }
                        break;
                }
            }
        }

//        arrPrint($total);
//        arrPrintPink($resultNeraca_new);

        $pakai_ini = 0;
        if ($pakai_ini == 1) {
            $tmp = array();
            if (sizeof($resultNeraca) > 0) {
                foreach ($resultNeraca as $nn => $nSpec) {
                    $temp = array();
                    foreach ($nSpec as $key => $val) {
                        $temp[$key] = $val;
                    }
                    $tmp[$nn] = (object)$temp;
                }
            }
            if (sizeof($tmp) > 0) {
                foreach ($tmp as $row) {
                    $cabID = $row->cabang_id;
                    $defPos = detectRekDefaultPosition($row->rekening);
                    $rek_alias = isset($this->accountAlias[$row->rekening]) ? $this->accountAlias[$row->rekening] : $row->rekening;

                    if (strlen($row->kategori) > 1) {
                        if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {

                            if (!in_array($row->kategori, $categories)) {
                                $categories[] = $row->kategori;
                            }
                            // rekenings cabang selected
                            if (!isset($rekenings[$row->kategori])) {
                                $rekenings[$row->kategori] = array();
                            }
                            // rekening index per-cabang
                            if (!isset($rekeningsCab[$cabID][$row->kategori])) {
                                $rekeningsCab[$cabID][$row->kategori] = array();
                            }
                            // rekening konsolidasi
                            if (!isset($rekeningsKonsolidasi[$row->kategori])) {
                                $rekeningsKonsolidasi[$row->kategori] = array();
                            }


                            if (in_array($row->rekening, $this->accountException)) {
//                            $tmpCol = array(
//                                "rek_id" => "",
//                                "rekening" => $row->rekening,
////                                "debet" => ($row->kredit * -1),
////                                "kredit" => ($row->debet * -1),
//                                "link" => "",
//                            );
                                $debet = ($row->kredit * -1);
                                $kredit = ($row->debet * -1);

                            }
                            else {
                                switch ($defPos) {
                                    case "debet":
                                        if ($row->kredit > 0) {
                                            $debet = $row->kredit * -1;
                                            $kredit = 0;
                                        }
                                        else {
                                            $debet = $row->debet;
                                            $kredit = $row->kredit;
                                        }
                                        break;
                                    case "kredit":
                                        if ($row->debet > 0) {
                                            $debet = 0;
                                            $kredit = $row->debet * -1;
                                        }
                                        else {
                                            $debet = $row->debet;
                                            $kredit = $row->kredit;
                                        }
                                        break;
                                    default:
                                        $debet = $row->debet;
                                        $kredit = $row->kredit;
                                        break;
                                }

//                            $tmpCol = array(
//                                "rek_id" => "",
//                                "rekening" => $row->rekening,
////                                "debet" => $debet,
////                                "kredit" => $kredit,
//                                "link" => "",
//                            );
                            }

                            $link = "";
                            if (isset($this->accountChilds[$row->rekening])) {
                                $link .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $this->accountChilds[$row->rekening] . "/" . $row->rekening . "/" . $row->periode . "?date=$oldDate'><span class='fa fa-clone'></span></a>";
                            }
                            $link .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "'><span class='glyphicon glyphicon-time'></span></a></span>";


                            if (sizeof($this->accountCatException) > 0) {
                                foreach ($this->accountCatException as $cat => $c_rekName) {
                                    if (in_array($row->rekening, $c_rekName)) {
                                        $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                        //region cabang selected
                                        $rekenings[$cat][$row->rekening]["rek_id"] = "";
                                        $rekenings[$cat][$row->rekening]["rekening"] = $row->rekening;
                                        $rekenings[$cat][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekenings[$cat][$row->rekening]["debet"])) {
                                            $rekenings[$cat][$row->rekening]["debet"] = 0;
                                        }
                                        if (!isset($rekenings[$cat][$row->rekening]["kredit"])) {
                                            $rekenings[$cat][$row->rekening]["kredit"] = 0;
                                        }
                                        $rekenings[$cat][$row->rekening]["debet"] += $debet;
                                        $rekenings[$cat][$row->rekening]["kredit"] += $kredit;
                                        $rekenings[$cat][$row->rekening]["link"] = $link;
                                        //endregion

                                        //region index per-cabang
                                        $rekeningsCab[$cabID][$cat][$row->rekening]["rek_id"] = "";
                                        $rekeningsCab[$cabID][$cat][$row->rekening]["rekening"] = $row->rekening;
                                        $rekeningsCab[$cabID][$cat][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekeningsCab[$cabID][$cat][$row->rekening]["debet"])) {
                                            $rekeningsCab[$cabID][$cat][$row->rekening]["debet"] = 0;
                                        }
                                        if (!isset($rekeningsCab[$cabID][$cat][$row->rekening]["kredit"])) {
                                            $rekeningsCab[$cabID][$cat][$row->rekening]["kredit"] = 0;
                                        }
                                        $rekeningsCab[$cabID][$cat][$row->rekening]["debet"] += $debet;
                                        $rekeningsCab[$cabID][$cat][$row->rekening]["kredit"] += $kredit;
                                        $rekeningsCab[$cabID][$cat][$row->rekening]["link"] = $link;
                                        //endregion

                                        //region konsolidasi
                                        $rekeningsKonsolidasi[$cat][$row->rekening]["rek_id"] = "";
                                        $rekeningsKonsolidasi[$cat][$row->rekening]["rekening"] = $row->rekening;
                                        $rekeningsKonsolidasi[$cat][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekeningsKonsolidasi[$cat][$row->rekening]["debet"])) {
                                            $rekeningsKonsolidasi[$cat][$row->rekening]["debet"] = 0;
                                        }
                                        if (!isset($rekeningsKonsolidasi[$cat][$row->rekening]["kredit"])) {
                                            $rekeningsKonsolidasi[$cat][$row->rekening]["kredit"] = 0;
                                        }
                                        $rekeningsKonsolidasi[$cat][$row->rekening]["debet"] += $debet;
                                        $rekeningsKonsolidasi[$cat][$row->rekening]["kredit"] += $kredit;
                                        $rekeningsKonsolidasi[$cat][$row->rekening]["link"] = $link;
                                        //endregion
                                    }
                                    else {
                                        $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                        //region cabang selected
                                        $rekenings[$row->kategori][$row->rekening]["rek_id"] = "";
                                        $rekenings[$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                        $rekenings[$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekenings[$row->kategori][$row->rekening]["debet"])) {
                                            $rekenings[$row->kategori][$row->rekening]["debet"] = 0;
                                        }
                                        if (!isset($rekenings[$row->kategori][$row->rekening]["kredit"])) {
                                            $rekenings[$row->kategori][$row->rekening]["kredit"] = 0;
                                        }
                                        $rekenings[$row->kategori][$row->rekening]["debet"] += $debet;
                                        $rekenings[$row->kategori][$row->rekening]["kredit"] += $kredit;
                                        $rekenings[$row->kategori][$row->rekening]["link"] = $link;
                                        //endregion

                                        //region index per-cabang
                                        $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rek_id"] = "";
                                        $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                        $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"])) {
                                            $rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"] = 0;
                                        }
                                        if (!isset($rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"])) {
                                            $rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"] = 0;
                                        }
                                        $rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"] += $debet;
                                        $rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"] += $kredit;
                                        $rekeningsCab[$cabID][$row->kategori][$row->rekening]["link"] = $link;
                                        //endregion

                                        //region konsolidasi
                                        $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rek_id"] = "";
                                        $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                        $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                        if (!isset($rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"])) {
                                            $rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"] = 0;
                                        }
                                        if (!isset($rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"])) {
                                            $rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"] = 0;
                                        }
                                        $rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"] += $debet;
                                        $rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"] += $kredit;
                                        $rekeningsKonsolidasi[$row->kategori][$row->rekening]["link"] = $link;
                                        //endregion
                                    }
                                }
                            }
                            else {
                                //region cabang selected
                                $rekenings[$row->kategori][$row->rekening]["rek_id"] = "";
                                $rekenings[$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                $rekenings[$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                if (!isset($rekenings[$row->kategori][$row->rekening]["debet"])) {
                                    $rekenings[$row->kategori][$row->rekening]["debet"] = 0;
                                }
                                if (!isset($rekenings[$row->kategori][$row->rekening]["kredit"])) {
                                    $rekenings[$row->kategori][$row->rekening]["kredit"] = 0;
                                }
                                $rekenings[$row->kategori][$row->rekening]["debet"] += $debet;
                                $rekenings[$row->kategori][$row->rekening]["kredit"] += $kredit;
                                $rekenings[$row->kategori][$row->rekening]["link"] = $link;
                                //endregion

                                //region index per-cabang
                                $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rek_id"] = "";
                                $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                $rekeningsCab[$cabID][$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                if (!isset($rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"])) {
                                    $rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"] = 0;
                                }
                                if (!isset($rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"])) {
                                    $rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"] = 0;
                                }
                                $rekeningsCab[$cabID][$row->kategori][$row->rekening]["debet"] += $debet;
                                $rekeningsCab[$cabID][$row->kategori][$row->rekening]["kredit"] += $kredit;
                                $rekeningsCab[$cabID][$row->kategori][$row->rekening]["link"] = $link;
                                //endregion

                                //region konsolidasi
                                $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rek_id"] = "";
                                $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rekening"] = $row->rekening;
                                $rekeningsKonsolidasi[$row->kategori][$row->rekening]["rekening_alias"] = $rek_alias;

                                if (!isset($rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"])) {
                                    $rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"] = 0;
                                }
                                if (!isset($rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"])) {
                                    $rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"] = 0;
                                }
                                $rekeningsKonsolidasi[$row->kategori][$row->rekening]["debet"] += $debet;
                                $rekeningsKonsolidasi[$row->kategori][$row->rekening]["kredit"] += $kredit;
                                $rekeningsKonsolidasi[$row->kategori][$row->rekening]["link"] = $link;
                                //endregion
                            }


                        }
                    }
                }
            }
        }


        $arrLaporanKeuangan = array(
            "rugilaba" => isset($resultRL['rugilaba']) ? $resultRL['rugilaba'] : array(),
            "neraca" => $resultNeraca_new,
        );
        return $arrLaporanKeuangan;
    }

    public function execRebuildLaporan($cabangID, $periode, $arrLaporanKeuangan, $transaksiID, $transaksiNomer, $tahun, $bulan = "")
    {
//        arrPrintPink($arrLaporanKeuangan['rugilaba']);
//mati_disini(__LINE__);

        // region update rugilaba adj
        $rlAdj = New MdlRugilabaAdj();
        $updateData = array(
            "rebuild" => "1",
        );
        $updateWhere = array(
            "rebuild" => "0",
            "cabang_id" => $cabangID,
            "periode" => $periode,
            "bln" => $bulan,
            "thn" => $tahun,
        );
        $rlAdj->updateData($updateWhere, $updateData);
        showLast_query("orange");
        // endregion update rugilaba adj

        // region update neraca adj
        $nrcAdj = New MdlNeracaAdj();
        $updateData = array(
            "rebuild" => "1",
        );
        $updateWhere = array(
            "rebuild" => "0",
            "cabang_id" => $cabangID,
            "periode" => $periode,
            "bln" => $bulan,
            "thn" => $tahun,
        );
        $nrcAdj->updateData($updateWhere, $updateData);
        showLast_query("orange");
        // endregion update neraca adj

        // region update adjustment TMP
        $nrcAdjTmp = New MdlNeracaAdjTmp();
        $updateData = array(
            "rebuild" => "1",
        );
        $updateWhere = array(
            "rebuild" => "0",
            "periode" => $periode,
            "transaksi_id" => $transaksiID,
        );
        $nrcAdjTmp->updateData($updateWhere, $updateData);
        showLast_query("orange");
        // endregion update adjustment


//mati_disini(__LINE__);


        // region insert tabel rugilaba adj
        if (sizeof($arrLaporanKeuangan["rugilaba"]) > 0) {
            foreach ($arrLaporanKeuangan["rugilaba"] as $spec) {
                $spec["transaksi_id"] = $transaksiID;
                $spec["transaksi_no"] = $transaksiNomer;
                $spec["oleh_id"] = my_id();
                $spec["oleh_nama"] = my_name();

                $rlAdj = New MdlRugilabaAdj();
                $rlAdj->addData($spec);
                showLast_query("hijau");
            }
        }
        // endregion insert tabel rugilaba adj

        // region insert tabel neraca adj
        $total = array();
        if (sizeof($arrLaporanKeuangan["neraca"]) > 0) {
            foreach ($arrLaporanKeuangan["neraca"] as $spec) {
                $spec["transaksi_id"] = $transaksiID;
                $spec["transaksi_no"] = $transaksiNomer;
                $spec["oleh_id"] = my_id();
                $spec["oleh_nama"] = my_name();

                $nrcAdj = New MdlNeracaAdj();
                $nrcAdj->addData($spec);
                showLast_query("hijau");


                if (!isset($total['debet'])) {
                    $total['debet'] = 0;
                }
                $total['debet'] += $spec['debet'];

                if (!isset($total['kredit'])) {
                    $total['kredit'] = 0;
                }
                $total['kredit'] += $spec['kredit'];

            }

            $total_debet = isset($total["debet"]) ? $total["debet"] : 0;
            $total_kredit = isset($total["kredit"]) ? $total["kredit"] : 0;
            $total_selisih = $total_debet - $total_kredit;
            $total_selisih = $total_selisih < 0 ? $total_selisih * -1 : $total_selisih;
            if ($total_selisih > 2) {
                $msg = "neraca hasil adjustment tidak balance. silahkan diperiksa lagi atau hubungi admin. ";
                $msg .= "D: $total_debet vs K: $total_kredit, ";
                $msg .= "selisih: $total_selisih";
                mati_disini($msg);
            }
            else {
                cekHijau("<h3>ADJUSTMENT BERHASIL</h3>");
            }
        }
        // endregion insert tabel neraca adj


    }

    public function execReverseJurnal($transaksiID)
    {
        // pembalik bila rekening neraca ke existing
        // comJurnal, membaca jurnal pengurang existing
        $category_pembalik = array(
            "aktiva",
            "hutang",
            "modal",
//            "biaya",
        );
        $rekBlacklist = array(
            "3020060",
        );

        $cj = New ComJurnal();
        $cj->addFilter("transaksi_id='$transaksiID'");
        $cjTmp = $cj->lookupAll()->result();
        $loop = array();
        $static = array();
        $pembantu = array();
        if (sizeof($cjTmp) > 0) {
            foreach ($cjTmp as $cjSpec) {
                //region data jurnal
                $rekening = $cjSpec->rekening;
                $debet = $cjSpec->debet;
                $kredit = $cjSpec->kredit;
                $jenis = $cjSpec->jenis;
                $transaksi_id = $cjSpec->transaksi_id;
                $transaksi_nomer = $cjSpec->transaksi_no;
                $cabang_id = $cjSpec->cabang_id;
                $dtime = $cjSpec->dtime;
                $fulldate = $cjSpec->fulldate;
                $keterangan = $cjSpec->keterangan;
                $rekening_2 = $cjSpec->rekening_2;
                $rekening_alias = $cjSpec->rekening_alias;
                //endregion

                $category = detectRekCategory($rekening);
                $def_position = detectRekDefaultPosition($rekening);
                switch ($def_position) {
                    case "debet":
                        if ($debet > 0) {
                            $value = $debet;
                        }
                        else {
                            $value = $kredit * -1;
                        }
                        break;
                    case "kredit":
                        if ($kredit > 0) {
                            $value = $kredit;
                        }
                        else {
                            $value = $debet * -1;
                        }
                        break;
                }
                $value_balik = $value * -1;
                cekHere(":: [$category] [$def_position] $rekening, D: $debet, K: $kredit, val orig: $value, val balik: $value_balik ::");

                if (in_array($category, $category_pembalik)) {
                    if (!in_array($rekening, $rekBlacklist)) {
                        $loop[$rekening] = $value_balik;
                        $static = array(
                            "cabang_id" => $cabang_id,
                            "jenis" => $jenis,
                            "transaksi_id" => $transaksi_id,
                            "transaksi_no" => $transaksi_nomer,
                            "dtime" => $dtime,
                            "fulldate" => $fulldate,
                            "keterangan" => $keterangan,
                            "rekening_2" => $rekening_2,
                            "rekening_alias" => $rekening_alias,
                        );


                    }
                }

            }

//            arrPrintHijau($loop);
//            arrPrintPink($static);

            // mengembalikan rekening besar yang masuk aktiva, hutang, modal
            if (sizeof($loop) > 1) {
                $arrJurnal["loop"] = $loop;
                $arrJurnal["static"] = $static;

                $arrRekening["loop"] = $loop;
                $arrRekening["static"] = $static;

                arrPrintHijau($arrJurnal);
                arrPrintKuning($arrRekening);

                $pakai_ini = 1;//untuk menjalankan jurnal dan rekening besar
                if ($pakai_ini == 1) {

                    $j = New ComJurnal();
                    $j->pair($arrJurnal);
                    $j->exec();

                    $c = New ComRekening();
                    $c->pair($arrRekening);
                    $c->exec();
                }


                // region mengembalikan pembantu dari rekening neraca
                $tr = New MdlTransaksi();
                $tr->setFilters(array());
                $tr->setJointSelectFields("transaksi_id, rsltItems2");
                $tr->addFilter("transaksi_id='$transaksiID'");
                $regTmp = $tr->lookupDataRegistries()->result();
                showLast_query("biru");

                $detail_pembantu = isset($regTmp[0]->rsltItems2) ? blobDecode($regTmp[0]->rsltItems2) : array();
                if (sizeof($detail_pembantu) > 0) {
                    foreach ($detail_pembantu as $master_rek => $msSpec) {
                        $master_rek_ex = explode("_", $master_rek);
                        $master_rek_key = $master_rek_ex[0];
                        if (array_key_exists($master_rek_key, $arrRekening["loop"])) {
//                            arrPrintWebs($msSpec);
                            $detail_data_pair = array();
                            foreach ($msSpec as $ctr => $spec) {

                                foreach ($spec["loop"] as $key => $val) {
                                    $detail_data_pair[$ctr]["loop"][$key] = ($val * -1);
                                }

                                $detail_data_pair[$ctr]["static"] = $spec["static"];
                                $detail_data_pair[$ctr]["static"]["transaksi_id"] = $transaksi_id;
                                $detail_data_pair[$ctr]["static"]["transaksi_no"] = $transaksi_nomer;
//                                arrPrintKuning($detail_data_pair);

                                $comName = "Com" . $spec["comName"];
                                $this->ci->load->model("Coms/$comName");
                                $md = New $comName();
                                $md->pair($detail_data_pair);
                                $md->exec();

                            }
                        }
                    }
                }
                // endregion mengembalikan pembantu dari rekening neraca
            }


        }


    }
    //------------------------------
}
