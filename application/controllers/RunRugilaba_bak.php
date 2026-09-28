<?php


class RunRugilaba extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();

        $this->load->model("Coms/ComRugiLaba_cli2");
        $this->load->model("Coms/ComRugiLaba_cli");
        $this->load->model("Coms/ComNeraca_cli");
        $this->load->model("Mdls/MdlCabang");
        $this->load->helper("he_misc");
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
        $rl = New ComRugiLaba_cli();
        $n = New ComNeraca_cli();
        $c = New MdlCabang();
        $em = New SmtpMailer();

        $emTos = array(
            "thomas" => "namakamoe@gmail.com",
            "jasmanto" => "djasmanto@gmail.com",
        );

        $this->db->trans_begin();

        $dateTimeNow = dtimeNow();
        $date = date("Y-m-01");
        $dateNow = dtimeNow("d");
        $dateRun = 1;
//        $dateNow = 1;


        $prevBl = previousMonth();
        $dateLast_ex = explode("-", $prevBl);
        $periode = "bulanan";
        $bulan = $dateLast_ex[1];
        $tahun = $dateLast_ex[0];
//                $bulan = date("m");
//                $tahun = date("Y");

        //region script hanya dirun tiap tgl satu untuk bulan sebelumnya
        if ($dateNow != $dateRun) {
            mati_disini("transaksi ini hanya jalan tiap tgl $dateRun disetiap bulannya, sekarang tgl $dateTimeNow <hr>" . __METHOD__ . " @" . __LINE__);
        }
        //endregion

        //region Description ceking sudah pernah dirun atau belum
        $ceks = $rl->lookupMonth($prevBl);
        showLast_query("hijau");
//        if ($ceks->num_rows() > 0) {
        if (sizeof($ceks) > 0) {
            // writeLog("generate rugi-laba","auto","cli","","","","generator rugi-laba");


            matiHere("untuk $tahun $bulan sudah runing <hr>" . __METHOD__ . " @" . __LINE__);
        }
        else {
            $em->setAddressFrom("noreply.mgkcore@gmail.com");
            $em->setAddressTo($emTos);
            $em->setSubject("noreply :: " . __METHOD__);
            $em->kirim_email("running lagi untuk $tahun $bulan @$dateTimeNow");

//            cekMerah($ceks->num_rows() . " siap siap ngerun");
        }
        //endregion

        // cekHere("$dateNow | $tahun | $bulan | $prevBl | $dateNow");
        // mati_disini(__METHOD__);

        $c->setFilters(array());
        //        $c->addFilter("id='1'");
        $c->addFilter("trash='0'");
        $tmpCabang = $c->lookupAll()->result();
        // showLast_query("biru");
        foreach ($tmpCabang as $cSpec) {
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
//                "bln<=" => $bulan,
//                "thn<=" => $tahun,
                "date(dtime)<" => $dateTimeNow,
            );


            cekHitam(":: MULAI RL " . $cSpec->id . " :: " . $cSpec->nama);

            // $rl = New ComRugiLaba_cli();

            $rl->setFilters2($filters2);
            $rl->setFilters($filters);
            $rl->pair($static);
            showLast_query("lime");
            $rl->exec();
            cekKuning(":: DONE RL " . $cSpec->id . " :: " . $cSpec->nama);
            //mati_disini();


            cekHitam(":: MULAI NERACA " . $cSpec->id . " :: " . $cSpec->nama);


            $n->setFilters2($filters2);
            $n->setFilters($filters);
            $n->pair($static);
            $n->exec();
            showLast_query("lime");
            cekHitam(":: CLOSE NERACA " . $cSpec->id . " :: " . $cSpec->nama);


        }

        writeLog("generate rugi-laba", "auto", "cli", "", "", "", "generator rugi-laba");

        mati_disini("CILUKBAAA.... TESTING LAGI... HI HI HI  BELUM DICOMMIT");

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        }
        else {
            $this->db->trans_commit();
        }

        cekHijau("<h1>done</h1>");

        // writeLog("generate rugi-laba","auto","cli","","","","generator rugi-laba");

    }

    function tahunan()
    {
        $rl = New ComRugiLaba_cli();
        $n = New ComNeraca_cli();
        $c = New MdlCabang();
        $em = New SmtpMailer();

        $emTos = array(
            "thomas" => "namakamoe@gmail.com",
            "jasmanto" => "djasmanto@gmail.com",
        );

        $this->db->trans_begin();

        $dateTimeNow = dtimeNow();
        $dateNow = dtimeNow("d-m");
        $dateRun = "01-01";
//        $dateNow = "01-01";


        $prevBl = previousYear();
        $dateLast_ex = explode("-", $prevBl);
        $periode = "tahunan";
//        $bulan = $dateLast_ex[1];
        $tahun = $dateLast_ex[0];
        //        $bulan = date("m");
//        $tahun = date("Y");

        //region script hanya dirun tiap tgl satu untuk bulan sebelumnya
        if ($dateNow != $dateRun) {
            mati_disini("transaksi ini hanya jalan tiap tgl $dateRun disetiap tahunnya, sekarang tgl $dateTimeNow <hr>" . __METHOD__ . " @" . __LINE__);
        }
        //endregion

        //region Description ceking sudah pernah dirun atau belum
        $ceks = $rl->lookupYear($prevBl);
//        showLast_query("hijau");
//        arrPrint($ceks->result());
//
//        if ($ceks->num_rows() > 0) {
        if (sizeof($ceks) > 0) {


            matiHere("untuk $tahun  sudah runing <hr>" . __METHOD__ . " @" . __LINE__);
        }
        else {
            $em->setAddressFrom("noreply.mgkcore@gmail.com");
            $em->setAddressTo($emTos);
            $em->setSubject("noreply :: " . __METHOD__);
            $em->kirim_email("running lagi untuk $tahun  @$dateTimeNow");


//            cekMerah($ceks->num_rows() . " siap siap ngerun");
        }
        //endregion

        cekHere("$dateNow | $tahun | $prevBl | $dateNow");
//        mati_disini(":: --- under maintenance --- ::");


        $c->setFilters(array());
        $c->addFilter("trash='0'");
        $tmpCabang = $c->lookupAll()->result();
        // showLast_query("biru");
        foreach ($tmpCabang as $cSpec) {
            $static = array(
                "static" => array(
                    "cabang_id" => $cSpec->id,
                    "dtime" => $dateTimeNow,
                    "fulldate" => $dateNow,
                    //                    "bln" => $dateLast_ex[1],
                    //                    "thn" => $dateLast_ex[0],
//                    "bln"       => $bulan,
                    "thn" => $tahun,
                    "periode" => $periode,
                ),
            );

            $filters = array(
                "periode" => $periode,
                "cabang_id" => $cSpec->id,
//                "bln" => $bulan,
                "thn" => $tahun,
            );
            $filters2 = array(
                "periode=" => $periode,
                "cabang_id=" => $cSpec->id,
//                "bln<=" => $bulan,
                "thn<=" => $tahun,
            );


            cekHitam(":: MULAI RL " . $cSpec->id . " :: " . $cSpec->nama);

            // $rl = New ComRugiLaba_cli();

            $rl->setFilters2($filters2);
            $rl->setFilters($filters);
            $rl->pair($static);
            showLast_query("lime");
            $rl->exec();
            cekKuning(":: DONE RL " . $cSpec->id . " :: " . $cSpec->nama);
            //mati_disini();


            cekHitam(":: MULAI NERACA " . $cSpec->id . " :: " . $cSpec->nama);


            $n->setFilters2($filters2);
            $n->setFilters($filters);
            $n->pair($static);
            $n->exec();
            showLast_query("lime");
            cekHitam(":: CLOSE NERACA " . $cSpec->id . " :: " . $cSpec->nama);


        }

//        writeLog("generate rugi-laba","auto","cli","","","","generator rugi-laba");

        mati_disini("CILUKBAAA.... TESTING LAGI... HI HI HI  BELUM DICOMMIT");
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        }
        else {
            $this->db->trans_commit();
        }

        cekHijau("<h1>done</h1>");

        // writeLog("generate rugi-laba","auto","cli","","","","generator rugi-laba");

    }

    function forever()
    {
        $rl = New ComRugiLaba_cli();
        $n = New ComNeraca_cli();
        $c = New MdlCabang();
        $em = New SmtpMailer();

        $emTos = array(
            "thomas" => "namakamoe@gmail.com",
            "jasmanto" => "djasmanto@gmail.com",
        );

        $this->db->trans_begin();

        $dateTimeNow = dtimeNow();
        $dateNow = dtimeNow("d-m");
        $dateRun = "01-01";
//        $dateNow = "01-01";

        $prevBl = previousYear();
        $dateLast_ex = explode("-", $prevBl);
        $periode = "forever";
//        $bulan = $dateLast_ex[1];
        $tahun = $dateLast_ex[0];
        //        $bulan = date("m");
        $prevBl = $tahun = date("Y");

        //region script hanya dirun tiap tgl satu untuk bulan sebelumnya
        if ($dateNow != $dateRun) {
            mati_disini("transaksi ini hanya jalan tiap tgl $dateRun disetiap tahunnya, sekarang tgl $dateTimeNow <hr>" . __METHOD__ . " @" . __LINE__);
        }
        //endregion

        //region Description ceking sudah pernah dirun atau belum
        $ceks = $rl->lookupYear($prevBl);
//        showLast_query("hijau");
//        arrPrint($ceks->result());
//


//        if ($ceks->num_rows() > 0) {
        if (sizeof($ceks) > 0) {

            $em->setAddressFrom("noreply.mgkcore@gmail.com");
            $em->setAddressTo($emTos);
            $em->setSubject("noreply :: " . __METHOD__);
            $em->kirim_email("running lagi untuk $tahun  @$dateTimeNow");

//            matiHere("untuk $tahun $bulan sudah runing <hr>" . __METHOD__ . " @" . __LINE__);
        }
        else {
//            cekMerah($ceks->num_rows() . " siap siap ngerun");
        }
        //endregion

        cekHere("$dateNow | $tahun | $prevBl | $dateNow");
//        mati_disini(":: --- under maintenance --- ::");


        $c->setFilters(array());
        $c->addFilter("trash='0'");
        $tmpCabang = $c->lookupAll()->result();
        // showLast_query("biru");
        foreach ($tmpCabang as $cSpec) {
            $static = array(
                "static" => array(
                    "cabang_id" => $cSpec->id,
                    "dtime" => $dateTimeNow,
                    "fulldate" => $dateNow,
                    //                    "bln" => $dateLast_ex[1],
                    //                    "thn" => $dateLast_ex[0],
//                    "bln"       => $bulan,
//                    "thn" => $tahun,
                    "periode" => $periode,
                ),
            );

            $filters = array(
                "periode" => $periode,
                "cabang_id" => $cSpec->id,
//                "bln" => $bulan,
//                "thn" => $tahun,
            );
            $filters2 = array(
                "periode=" => $periode,
                "cabang_id=" => $cSpec->id,
//                "bln<=" => $bulan,
//                "thn<=" => $tahun,
            );


            cekHitam(":: MULAI RL " . $cSpec->id . " :: " . $cSpec->nama);

            // $rl = New ComRugiLaba_cli();

            $rl->setFilters2($filters2);
            $rl->setFilters($filters);
            $rl->pair($static);
            showLast_query("lime");
            $rl->exec();
            cekKuning(":: DONE RL " . $cSpec->id . " :: " . $cSpec->nama);
            //mati_disini();


            cekHitam(":: MULAI NERACA " . $cSpec->id . " :: " . $cSpec->nama);


            $n->setFilters2($filters2);
            $n->setFilters($filters);
            $n->pair($static);
            $n->exec();
            showLast_query("lime");
            cekHitam(":: CLOSE NERACA " . $cSpec->id . " :: " . $cSpec->nama);


        }

//        writeLog("generate rugi-laba","auto","cli","","","","generator rugi-laba");

        mati_disini("CILUKBAAA.... TESTING LAGI... HI HI HI  BELUM DICOMMIT");
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        }
        else {
            $this->db->trans_commit();
        }

        cekHijau("<h1>done</h1>");

        // writeLog("generate rugi-laba","auto","cli","","","","generator rugi-laba");

    }


    //---------------------------------------------------
    //---------------------------------------------------
    function bulananNew_old()
    {

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

        $this->db->trans_begin();

        $dateTimeNow = dtimeNow();
        $date = date("Y-m-01");
//        $date = "2022-05-01";// ini nanti dimatikan
        $dateNow = dtimeNow("d");
        $dateRun = 1;
//        $dateNow = 1;


        $prevBl = previousMonth();
//        $prevBl = "2022-04";// ini nanti dimatikan
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
            if ($dateNow != $dateRun) {
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
                $nl = New MdlNeracaLajur();
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


        mati_disini("CILUKBAAA.... TESTING LAGI... HI HI HI  BELUM DICOMMIT");


        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
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

    function bulananNew()
    {

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

        $this->db->trans_begin();

        $dateTimeNow = dtimeNow();
        $date = date("Y-m-01");
        $date = "2022-12-01";// ini nanti dimatikan
        $dateNow = dtimeNow("d");
        $dateRun = 1;
//        $dateNow = 1;


        $prevBl = previousMonth();
        $prevBl = "2022-11";// ini nanti dimatikan
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
            if ($dateNow != $dateRun) {
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
                    $nl = New MdlNeracaLajur();
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
            }


            $rl->setFilters2($filters2);
            $rl->setFilters($filters);
            $rl->pairNoCut($static, $arrLajurNew);
            $resultRL = $rl->execNoCut();
//            arrPrint($resultRL);


//            cekHitam(":: MULAI NERACA " . $cSpec->id . " :: " . $cSpec->nama);
            $n->setFilters2($filters2);
            $n->setFilters($filters);
            $n->pairNoCut($static, $resultRL['neraca']);
            $resultNeraca = $n->execNoCut();

//            mati_disini(":: CLOSE NERACA " . $cSpec->id . " :: " . $cSpec->nama);
            if (sizeof($resultNeraca) > 0) {
                foreach ($resultNeraca as $i => $spec) {
                    //------
                    $cr = New ComRekening_cli();
                    $cr->addFilter("rekening='" . $spec['rekening'] . "'");
                    $cr->addFilter("thn='" . date("Y") . "'");
                    $cr->addFilter("bln='" . date("m") . "'");
                    $cr->addFilter("periode='$periode'");
                    $cr->addFilter("cabang_id='" . $spec['cabang_id'] . "'");
                    $crTmp = $cr->lookupAll()->result();
                    if (sizeof($crTmp) > 0) {
                        //update
                        $data = array(
                            "debet" => $spec['debet'],
                            "kredit" => $spec['kredit'],
                        );
                        $where = array(
                            "id" => $crTmp[0]->id
                        );
//                        $cr->updateData($where, $data);
//                        showLast_query("orange");
//                        cekHere("::: update.....");
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
//                        cekHijau("::: insert.....");
                    }
                }
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
            $this->db->trans_rollback();
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

    function tahunanNew()
    {

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

        $this->db->trans_begin();

        $dateTimeNow = dtimeNow();
        $date = date("Y-01-01");
        $date = "2022-01-01"; // ini dimatikan
        $dateNow = dtimeNow("d");
        $dateRun = 1;
//        $dateNow = 1;


        $prevBl = previousYear();
        $prevBl = "2021"; // ini dimatikan
        $dateLast_ex = explode("-", $prevBl);
        $periode = "tahunan";
//        $bulan = $dateLast_ex[1];
        $tahun = $dateLast_ex[0];

//        $bulanLast = $bulan - 1;
        $getDateLastNeraca = $tahun - 1;
        $cekMerah = ("prevBL: $prevBl : tahun: $tahun : lastDateNeraca: $getDateLastNeraca");
        cekMerah($cekMerah);

        $pakai_ini = 0;
        if ($pakai_ini == 1) {
            //region script hanya dirun tiap tgl satu untuk bulan sebelumnya
            if ($dateNow != $dateRun) {
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
//                $em->setAddressFrom("noreply.mgkcore@gmail.com");
//                $em->setAddressTo($emTos);
//                $em->setSubject("noreply :: " . __METHOD__);
//                $em->kirim_email("running lagi untuk $tahun $bulan @$dateTimeNow");
            }
            //endregion
        }


        $c->setFilters(array());
        $c->addFilter("id='1'");
        $c->addFilter("trash='0'");
        $c->addFilter("jenis='cabang'");
        $tmpCabang = $c->lookupAll()->result();


        foreach ($tmpCabang as $cSpec) {
            $cabangID = $cSpec->id;

            $static = array(
                "static" => array(
                    "cabang_id" => $cSpec->id,
                    "dtime" => $dateTimeNow,
                    "fulldate" => $dateNow,
                    //                    "bln" => $dateLast_ex[1],
                    //                    "thn" => $dateLast_ex[0],
//                    "bln" => $bulan,
                    "thn" => $tahun,
                    "periode" => $periode,
                ),
            );
            $filters = array(
                "periode" => $periode,
                "cabang_id" => $cSpec->id,
//                "bln" => $bulan,
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
            showLast_query("biru");

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
                arrPrintWebs($arrRek);
                // membaca in/out mutasi masing-masing rekening...
                if (sizeof($arrRek) > 0) {
                    $arrMutasi = array();
                    foreach ($arrRek as $rek) {

                        $mts = New ComRekening_cli();
                        $mts->addFilter("cabang_id='$cabangID'");
                        $mts->addFilter("transaksi_id>'0'");
//                        $mts->addFilter("transaksi_id>'0'");
//                        $mts->addFilter("date(dtime)>='$tahun-01-01'");
//                        $mts->addFilter("date(dtime)<='$tahun-12-31'");
                        $mts->addFilter("fulldate>='$tahun-01-01'");
                        $mts->addFilter("fulldate<='$tahun-12-31'");
                        $arrMutasi[$rek] = $mts->fetchMoves($rek);
                        cekHijau("[MUTASI]: " . $this->db->last_query());
//                        arrPrint($arrMutasi);
//                        break;
                    }
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
                        cekHitam("rekening debet dan kredit lebih dari 0 => $rek");
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
            echo "<br><b>NERACA TERAKHIR ($cabangID) (tahun $getDateLastNeraca)</b><br>$str";
            //endregion


            //region mutasi...
            $totalDebet = 0;
            $totalKredit = 0;
            $str = "";
            $str .= "<table rules='all' border='1px solid black;'>";
            foreach ($arrMutasiResult as $spec) {
                $totalDebet += isset($spec['debet']) ? $spec['debet'] : 0;
                $totalKredit += isset($spec['kredit']) ? $spec['kredit'] : 0;
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
            echo "<br><b>MUTASI ($cabangID) (tahun $tahun)</b><br>$str";
            //endregion


            //region lajur...
            $totalDebet = 0;
            $totalKredit = 0;
            $str = "";
            $str .= "<table rules='all' border='1px solid black;'>";
            $arrLajurNew = array();
            $arrSpec = array();
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
                    "thn" => $tahun,
                    "periode" => $periode,
                );
                $nl = New MdlNeracaLajur();
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
            echo "<br><b>LAJUR ($cabangID) (tahun $tahun)</b><br>$str";
            //endregion


            $rl->setFilters2($filters2);
            $rl->setFilters($filters);
            $rl->pairNoCut($static, $arrLajurNew);
            $resultRL = $rl->execNoCut();
            cekHijau($this->db->last_query());
//            arrPrint($resultRL['rugilaba']);

            $str = "<table rules='all' style='border:1px solid black;'>";
            $debet = 0;
            $kredit = 0;
            foreach ($resultRL['rugilaba'] as $spec) {
                $debet += $spec['debet'];
                $kredit += $spec['kredit'];
                $str .= "<tr>";
                $str .= "<td>" . $spec['rekening'] . "</td>";
                $str .= "<td>" . $spec['debet'] . "</td>";
                $str .= "<td>" . $spec['kredit'] . "</td>";
                $str .= "</tr>";
            }
            $str .= "<tr>";
            $str .= "<td>-</td>";
            $str .= "<td>" . $debet . "</td>";
            $str .= "<td>" . $kredit . "</td>";
            $str .= "</tr>";
            $str .= "</table>";
            echo "<br><b>RUGILABA ($cabangID) (tahun $tahun)</b><br>$str";
//            mati_disini();

//            cekHitam(":: MULAI NERACA " . $cSpec->id . " :: " . $cSpec->nama);
            $n->setFilters2($filters2);
            $n->setFilters($filters);
            $n->pairNoCut($static, $resultRL['neraca']);
            $n->execNoCut();

            cekHitam(":: CLOSE NERACA " . $cSpec->id . " :: " . $cSpec->nama);
//            mati_disini();
//            break;
        }


        // region simpan config view rl dan neraca
        $categoryRL = $this->config->item("categoryRL") != NULL ? $this->config->item("categoryRL") : array();
        $accountRekeningSort = $this->config->item("accountRekeningSort") != NULL ? $this->config->item("accountRekeningSort") : array();
        $accountStructure = $this->config->item("accountStructure") != NULL ? $this->config->item("accountStructure") : array();
        $arrConfig = array(
            "categoryRL" => array(
                "param" => "categoryRL",
                "values" => blobEncode($categoryRL),
//                "bln" => $bulan,
                "thn" => $tahun,
                "periode" => $periode,
            ),
            "accountRekeningSort" => array(
                "param" => "accountRekeningSort",
                "values" => blobEncode($accountRekeningSort),
//                "bln" => $bulan,
                "thn" => $tahun,
                "periode" => $periode,
            ),
            "accountStructure" => array(
                "param" => "accountStructure",
                "values" => blobEncode($accountStructure),
//                "bln" => $bulan,
                "thn" => $tahun,
                "periode" => $periode,
            ),
        );

        foreach ($arrConfig as $fcSpec) {
            $fc->addData($fcSpec);
            cekHijau($this->db->last_query());
        }
        // endregion


        mati_disini("CILUKBAAA.... TESTING LAGI... HI HI HI  BELUM DICOMMIT");


        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        }
        else {
            $this->db->trans_commit();
        }

        $pakai_ini = 0;
        if ($pakai_ini == 1) {
            $em->setAddressFrom("noreply.mgkcore@gmail.com");
            $em->setAddressTo($emTos);
            $em->setSubject("noreply :: " . __METHOD__);
            $em->kirim_email("running lagi (tahunan) untuk $tahun $bulan @$dateTimeNow");
        }

        cekHijau("<h1>done</h1>");

        // writeLog("generate rugi-laba","auto","cli","","","","generator rugi-laba");

    }

    function foreverNew()
    {
        $rl = New ComRugiLaba_cli();
        $n = New ComNeraca_cli();
        $c = New MdlCabang();
        $em = New SmtpMailer();
        $fc = New MdlFinanceConfig();

        $emTos = array(
            "thomas" => "namakamoe@gmail.com",
            "jasmanto" => "djasmanto@gmail.com",
        );

        $this->db->trans_begin();

        $dateTimeNow = dtimeNow();
        $dateNow = dtimeNow("d-m");
        $dateRun = "01-01";

        $dateNow = "01-01";// ini dimatikan
        $dateTimeNow = "2021-01-01 00:05:11";// ini dimatikan

        $prevBl = previousYear();
        $prevBl = "2021";// ini dimatikan
        $dateLast_ex = explode("-", $prevBl);
        $periode = "forever";
        $tahun = $dateLast_ex[0];
//        $prevBl = $tahun = date("Y");

        $pakai_ini = 0;
        if ($pakai_ini == 1) {

            //region script hanya dirun tiap tgl satu untuk bulan sebelumnya
            if ($dateNow != $dateRun) {
                mati_disini("transaksi ini hanya jalan tiap tgl $dateRun disetiap tahunnya, sekarang tgl $dateTimeNow <hr>" . __METHOD__ . " @" . __LINE__);
            }
            //endregion

            //region Description ceking sudah pernah dirun atau belum
            $ceks = $rl->lookupYear($prevBl);
//        showLast_query("hijau");
//        arrPrint($ceks->result());
//


//        if ($ceks->num_rows() > 0) {
            if (sizeof($ceks) > 0) {

//                $em->setAddressFrom("noreply.mgkcore@gmail.com");
//                $em->setAddressTo($emTos);
//                $em->setSubject("noreply :: " . __METHOD__);
//                $em->kirim_email("running lagi untuk $tahun  @$dateTimeNow");

//            matiHere("untuk $tahun $bulan sudah runing <hr>" . __METHOD__ . " @" . __LINE__);
            }
            else {
//            cekMerah($ceks->num_rows() . " siap siap ngerun");
            }
            //endregion
        }

        cekHere("$dateNow | $tahun | $prevBl | $dateNow");
//        mati_disini(":: --- under maintenance --- ::");


        $c->setFilters(array());
//        $c->addFilter("id='25'");
        $c->addFilter("trash='0'");
        $c->addFilter("jenis='cabang'");
        $tmpCabang = $c->lookupAll()->result();
//        showLast_query("biru");
//        mati_disini();
        $main = array();
        foreach ($tmpCabang as $cSpec) {
            $cabangID = $cSpec->id;
            $cabangName = $cSpec->nama;

            if ($cabangID > 0) {
                $jenisTr = "1001";
            }
            else {
                $jenisTr = "1000";
            }


            cekHitam(":: MULAI TRANSAKSI " . $cSpec->id . " :: " . $cSpec->nama);
            //region transaksional
            $main = array(
                "olehID" => "-100",
                "olehName" => "sys",
                "placeID" => $cabangID,
                "placeName" => $cabangName,
                "cabangID" => $cabangID,
                "cabangName" => $cabangName,
                "gudangID" => 0,
                "gudangName" => "",
                "jenisTr" => $jenisTr,
                "jenisTrMaster" => $jenisTr,
                "jenisTrTop" => $jenisTr,
                "jenisTrName" => "rugilaba neraca",
                "stepNumber" => "1",
                "stepCode" => $jenisTr,
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow(),
                "harga" => 0,
                "divID" => "18",
                "divName" => "default",
                "subtotal" => 0,
                "reference" => "0",
                "jenis" => $jenisTr,
                "transaksi_jenis" => $jenisTr,
                "next_step_code" => "",
                "next_group_code" => "",
                "step_number" => "1",
                "step_current" => "1",
                "longitude" => "",
                "lattitude" => "",
                "accuracy" => "",
                "nilai_bayar" => "0",
                "new_sisa" => "0",
                "note" => "0",
                "description" => "",
                "pihakDisc" => "",
//                "pihakMainName" => $pihakMain[$pihakMainID],
//                "pihakMainID" => $pihakMainID,
//                "pihakMainChild" => "penyusutan " . $pihakMain[$pihakMainID],
//                "rekAkumPenyu" => "akum penyu " . $pihakMain[$pihakMainID],
//                "rekName_1" => $rekName_1_child,
//                "comRekName_2" => $comRekName_2_child,
//                "rekName_2" => $rekName_2_child,
//                "comRekName_3" => $comRekName_3_child,
//                "rekName_3" => $rekName_3_child,
//                "rekName3ID" => $rekName3IDChild,
            );


            $tableIn_master = array(
                "trash" => "0",
                "jenis_master" => $jenisTr,
                "jenis_top" => $jenisTr,
                "jenis" => $jenisTr,
                "jenis_label" => "rugilaba neraca",
                "div_id" => "18",
                "div_nama" => "default",
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow(),
                "oleh_id" => "-100",
                "oleh_nama" => "sys",
                "cabang_id" => $cabangID,
                "cabang_nama" => $cabangName,
                "transaksi_nilai" => 0,
                "transaksi_jenis" => $jenisTr,
                "gudang_id" => 0,
                "gudang_nama" => "",
                "gudang2_id" => "-1",
                "gudang2_nama" => "default center warehouse",
                "keterangan" => "",
                "cabang2_id" => "-1",
                "cabang2_nama" => "PUSAT",
//                "pihakMainName" => $pihakMainID,
//                "pihakMainID" => $pihakMainID,
            );


            //region penomoran receipt
            $this->load->model("CustomCounter");
            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");

            $counterForNumber = array($this->config->item('heTransaksi_core')[$jenisTr]['formatNota']);
            if (!in_array($counterForNumber[0], $this->config->item('heTransaksi_core')[$jenisTr]['counters'])) {
                die("Used number should be registered in 'counters' config as well");
            }
            echo "<div style='background:#ff7766;'>";
            foreach ($counterForNumber as $i => $cRawParams) {
                $cParams = explode("|", $cRawParams);
                $cValues = array();
                foreach ($cParams as $param) {
                    $cValues[$i][$param] = $main[$param];
                }
                $cRawValues = implode("|", $cValues[$i]);
                $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

            }
            echo "</div style='background:#ff7766;'>";

            $stepNumber = 1;
            $tmpNomorNota = $paramSpec['paramString'];

            if (isset($this->config->item('heTransaksi_ui')[$jenisTr]['steps'][2])) {
                $nextProp = array(
                    "num" => 2,
                    "code" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][2]['target'],
                    "label" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][2]['label'],
                    "groupID" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][2]['userGroup'],
                );
            }
            else {
                $nextProp = array(
                    "num" => 0,
                    "code" => "",
                    "label" => "",
                    "groupID" => "",
                );
            }
            //endregion

            //region dynamic counters
            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");
            $configCustomParams = $this->config->item('heTransaksi_core')[$jenisTr]['counters'];
            $configCustomParams[] = "stepCode";

            if (sizeof($configCustomParams) > 0) {
                $cContent = array();
                foreach ($configCustomParams as $i => $cRawParams) {
                    $cParams = explode("|", $cRawParams);
                    $cValues = array();
                    foreach ($cParams as $param) {
                        $cValues[$i][$param] = $main[$param];
                    }
                    $cRawValues = implode("|", $cValues[$i]);
                    $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

                    $cContent[$cRawParams][$cRawValues] = $paramSpec['value'];
                    switch ($paramSpec['id']) {
                        case 0: //===counter type is new
                            $paramKeyRaw = print_r($cParams, true);
                            $paramValuesRaw = print_r($cValues[$i], true);
                            $cn->writeNewCount($cParams, $cValues[$i], $paramKeyRaw, $paramValuesRaw);
                            break;
                        default: //===counter to be updated
                            $cn->updateCount($paramSpec['id'], $paramSpec['value']);
                            break;
                    }
                }
            }
            $appliedCounters = base64_encode(serialize($cContent));
            $appliedCounters_inText = print_r($cContent, true);

            //region addition on master
            $addValues = array(
                'counters' => $appliedCounters,
                'counters_intext' => $appliedCounters_inText,
                'nomer' => $tmpNomorNota,
                'dtime' => date("Y-m-d H:i:s"),
                'fulldate' => date("Y-m-d"),
                "step_avail" => sizeof($this->config->item('heTransaksi_ui')[$jenisTr]['steps']),
                "step_number" => 1,
                "step_current" => 1,
                "next_step_num" => $nextProp['num'],
                "next_step_code" => $nextProp['code'],
                "next_step_label" => $nextProp['label'],
                "next_group_code" => $nextProp['groupID'],
                "tail_number" => 1,
                "tail_code" => $this->config->item('heTransaksi_ui')[$jenisTr]['steps'][1]['target'],
            );
            foreach ($addValues as $key => $val) {
                $tableIn_master[$key] = $val;
            }
            //endregion

            //region addition on detail
            $addSubValues = array(
                "sub_step_number" => 1,
                "sub_step_current" => 1,
                "sub_step_avail" => sizeof($this->config->item("heTransaksi_ui")[$jenisTr]['steps']),
                "next_substep_num" => $nextProp['num'],
                "next_substep_code" => $nextProp['code'],
                "next_substep_label" => $nextProp['label'],
                "next_subgroup_code" => $nextProp['groupID'],
                "sub_tail_number" => 1,
                "sub_tail_code" => $this->config->item('heTransaksi_ui')[$jenisTr]['steps'][1]['target'],
            );
//            foreach ($tableIn_detail as $id => $dSpec) {
//                foreach ($addSubValues as $key => $val) {
//                    $tableIn_detail[$id][$key] = $val;
//                }
//            }

            //endregion

            //region ----------write transaksi, transaksi_data, main_fields, main_values, main_applets, etc
            if (sizeof($tableIn_master) > 0) {
                $tableIn_master['status_4'] = 11;
                $tableIn_master['trash_4'] = 0;

                $tr = new MdlTransaksi();
                $tr->addFilter("transaksi.cabang_id='" . $tableIn_master['cabang_id'] . "'");
                $insertID = $tr->writeMainEntries($tableIn_master);
                showLast_query("hijau");
                $mongoList['main'][] = $insertID;
                $epID = $tr->writeMainEntries_entryPoint($insertID, $insertID, $tableIn_master);
                $mongoList['main'][] = $epID;

                $insertNum = $tableIn_master['nomer'];
                $main['nomer'] = $insertNum;
                if ($insertID < 1) {
                    die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                }

                //==transaksi_id dan nomor nota diinject kan ke gate utama
                $injectors = array(
                    "transaksi_id" => $insertID,
                    "nomer" => $tmpNomorNota,
                );
                $arrInjectorsTarget = array(
                    "items",
                );
//                foreach ($injectors as $key => $val) {
//                    $main[$key] = $val;
//                    foreach ($arrInjectorsTarget as $target) {
//                        foreach ($items as $xis => $iSpec) {
//                            $id = isset($iSpec['id']) && $iSpec['id'] > 0 ? $iSpec['id'] : $xis;
//                            if (isset($items[$id])) {
//                                $items[$id][$key] = $val;
//                            }
//                        }
//                        foreach ($gate[$target] as $xis => $iSpec) {
//                            $id = isset($iSpec['id']) && $iSpec['id'] > 0 ? $iSpec['id'] : $xis;
//                            // if (isset($gate[$target][$id])) {
//                            $gate[$target][$id][$key] = $val;
//                            // }
//                        }
//                    }
//                }

                //===signature
                $dwsign = $tr->writeSignature($insertID, array(
                    "nomer" => $main['nomer'],
                    "step_number" => 1,
                    "step_code" => $jenisTr,
                    "step_name" => $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][1]['label'],
                    "group_code" => $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][1]['userGroup'],
                    "oleh_id" => "-100",
                    "oleh_nama" => "sys",
                    "keterangan" => $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][1]['label'] . " oleh sys",
                    "transaksi_id" => $insertID,
                )) or die("Failed to write signature");
                showLast_query("kuning");
                $mongoList['sign'][] = $dwsign;
                $idHis = array(
                    $stepNumber => array(
                        "step" => $stepNumber,
                        "trID" => $insertID,
                        "nomer" => $tmpNomorNota,
                        "counters" => $appliedCounters,
                        "counters_intext" => $appliedCounters_inText,
                    ),
                );
                $idHis_blob = blobEncode($idHis);
                $idHis_intext = print_r($idHis, true);
                $tr = new MdlTransaksi();
                $dupState = $tr->updateData(array("id" => $insertID), array(
                    "next_step_num" => $nextProp['num'],
                    "next_step_code" => $nextProp['code'],
                    "next_step_label" => $nextProp['label'],
                    "next_group_code" => $nextProp['groupID'],

                    //===references
                    "id_master" => $insertID,
                    "id_top" => $insertID,
                    "ids_prev" => "",
                    "ids_prev_intext" => "",
                    "nomer_top" => $main['nomer'],
                    "nomers_prev" => "",
                    "nomers_prev_intext" => "",
                    "jenises_prev" => "",
                    "jenises_prev_intext" => "",
                    "ids_his" => $idHis_blob,
                    "ids_his_intext" => $idHis_intext,
                )) or die("Failed to update tr next-state!");
                showLast_query("orange");
                $addValues = array(
                    //===references
                    "id_master" => $insertID,
                    "id_top" => $insertID,
                    "ids_prev" => "",
                    "ids_prev_intext" => "",
                    "nomer_top" => $main['nomer'],
                    "nomers_prev" => "",
                    "nomers_prev_intext" => "",
                    "jenises_prev" => "",
                    "jenises_prev_intext" => "",
                    "ids_his" => $idHis_blob,
                    "ids_his_intext" => $idHis_intext,
                );
                foreach ($addValues as $key => $val) {
                    $tableIn_master[$key] = $val;
                }

            }

//            if (sizeof($tableIn_master_values) > 0) {
//                if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['mainValues'])) {
//                    $inserMainValues = array();
//                    foreach ($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['mainValues'] as $key => $src) {
//                        if (isset($tableIn_master_values[$key])) {
//                            $dd = $tr->writeMainValues($insertID, array(
//                                "key" => $key,
//                                "value" => $tableIn_master_values[$key],
//                            ));
//                            $inserMainValues[] = $dd;
//                            $mongoList['mainValues'][] = $dd;
//                        }
//
//                    }
//                    if (sizeof($inserMainValues) > 0) {
//                        $arrBlob = blobEncode($inserMainValues);
//                        $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
//                    }
//                }
//            }

//            if (sizeof($main_add_values) > 0) {
//                $inserMainValues = array();
//                foreach ($main_add_values as $key => $val) {
//                    $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
//                    $inserMainValues[] = $dd;
//                    $mongoList['mainValues'][] = $dd;
//                }
//                if (sizeof($inserMainValues) > 0) {
//                    $arrBlob = blobEncode($inserMainValues);
//                    $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
//                }
//
////                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
//            }

//            if (sizeof($main_inputs) > 0) {
//                foreach ($main_inputs as $key => $val) {
//                    $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
//                    $inserMainValues[] = $dd;
//                    $mongoList['mainValues'][] = $dd;
//                }
//                if (sizeof($inserMainValues) > 0) {
//                    $arrBlob = blobEncode($inserMainValues);
//                    $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
//                }
////                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
//            }

//            if (sizeof($main_add_fields) > 0) {
//                foreach ($main_add_fields as $key => $val) {
//                    $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
//                }
////                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
//            }

//            if (sizeof($main_elements) > 0) {
//                foreach ($main_elements as $elName => $aSpec) {
//                    $tr->writeMainElements($insertID, array(
//                        "mdl_name" => isset($aSpec['mdl_name']) ? $aSpec['mdl_name'] : "",
//                        "key" => isset($aSpec['key']) ? $aSpec['key'] : 0,
//                        "value" => isset($aSpec['value']) ? $aSpec['value'] : "",
//                        "name" => $aSpec['name'],
//                        "label" => isset($aSpec['label']) ? $aSpec['label'] : "",
//                        "contents" => isset($aSpec['contents']) ? $aSpec['contents'] : "",
//                        "contents_intext" => isset($aSpec['contents_intext']) ? $aSpec['contents_intext'] : "",
//                    ));
//
//                    //==nebeng bikin inputLabels
//                    $currentValue = "";
//                    switch ($aSpec['elementType']) {
//                        case "dataModel":
//                            $currentValue = $aSpec['key'];
//                            break;
//                        case "dataField":
//                            $currentValue = $aSpec['value'];
//                            break;
//                    }
//                    if (array_key_exists($elName, $relOptionConfigs)) {
//                        if (isset($relOptionConfigs[$elName][$currentValue])) {
//                            if (sizeof($relOptionConfigs[$elName][$currentValue]) > 0) {
//                                foreach ($relOptionConfigs[$elName][$currentValue] as $oValueName => $oValSpec) {
//                                    $inputLabels[$oValueName] = $oValSpec['label'];
//                                    if (isset($oValSpec['auth'])) {
//                                        if (isset($oValSpec['auth']['groupID'])) {
//                                            $inputAuthConfigs[$oValueName] = $oValSpec['auth']['groupID'];
//                                        }
//                                    }
//                                }
//                            }
//                        }
//                        else {
//                            //						cekKuning("option $currentValue pada $eName TIDAK ada pilihannya");
//                        }
//                    }
////                                cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
//                }
//            }

//            if (sizeof($tableIn_detail) > 0) {
//                $insertIDs = array();
//                $insertDeIDs = array();
//                foreach ($tableIn_detail as $dSpec) {
//                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
//                    $insertIDs[] = $insertDetailID;
//                    $insertDeIDs[$insertID][] = $insertDetailID;
//                    $mongoList['detail'][] = $insertDetailID;
//                    if ($epID != 999) {
//                        $insertEpID = $tr->writeDetailEntries($epID, $dSpec);
//                        $insertIDs[] = $insertEpID;
//                        $insertDeIDs[$epID][] = $insertEpID;
//                        $mongoList['detail'][] = $insertEpID;
//                    }
////                                cekUngu("LINE: " . __LINE__ . " <br> " . $this->db->last_query());
//                }
//                if (sizeof($insertIDs) == 0) {
//                    die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
//                }
//                else {
//                    $indexing_details = array();
//                    foreach ($insertDeIDs as $key => $numb) {
//                        $indexing_details[$key] = $numb;
//                    }
//                    foreach ($indexing_details as $k => $arrID) {
//                        $arrBlob = blobEncode($arrID);
//                        $this->db->query("UPDATE transaksi SET indexing_details = '$arrBlob' WHERE id=$k");
//                        cekOrange($this->db->last_query());
//                    }
//                }
//            }

//            if (sizeof($tableIn_detail2) > 0) {
//                $insertIDs = array();
//                foreach ($tableIn_detail2 as $dSpec) {
//                    $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
//                    $mongoList['detail'] = $insertIDs;
//                    if ($epID != 999) {
//                        $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
//                        $mongoList['detail'] = $insertIDs;
//                    }
////                                cekUngu($this->db->last_query());
//                }
//            }

//            if (sizeof($tableIn_detail2_sum) > 0) {
//                $insertIDs = array();
//                foreach ($tableIn_detail2_sum as $dSpec) {
//                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
//                    $insertIDs[] = $insertDetailID;
//                    $mongoList['detail'][] = $insertDetailID;
//
//                    if ($epID != 999) {
//                        $insertDetailID = $tr->writeDetailEntries($epID, $dSpec);
//                        $insertIDs[] = $insertDetailID;
//                        $mongoList['detail'][] = $insertDetailID;
//                    }
//                }
////                            cekOrange($this->db->last_query());
//            }

//            if (sizeof($tableIn_detail_rsltItems) > 0) {
//                $insertIDs = array();
//                foreach ($tableIn_detail_rsltItems as $dSpec) {
//                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
//                    $insertIDs[] = $insertDetailID;
//                    $mongoList['detail'][] = $insertDetailID;
//                    if ($epID != 999) {
//                        $insertDetailID = $tr->writeDetailEntries($epID, $dSpec);
//                        $insertIDs[] = $insertDetailID;
//                        $mongoList['detail'][] = $insertDetailID;
//                    }
////                                cekUngu($this->db->last_query());
//                }
//            }

//            if (sizeof($tableIn_detail_values) > 0) {
//                foreach ($tableIn_detail_values as $pID => $dSpec) {
//                    if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues'])) {
//                        $insertIDs = array();
//                        foreach ($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues'] as $key => $src) {
//                            if (isset($tableIn_detail[$pID])) {
//                                $dd = $tr->writeDetailValues($insertID, array(
//                                    "produk_jenis" => $tableIn_detail[$pID]['produk_jenis'],
//                                    "produk_id" => $pID,
//                                    "key" => $key,
//                                    "value" => $dSpec[$src],
//                                ));
//                                $insertIDs[$pID][] = $dd;
//                                $mongoList['detailValues'][] = $dd;
//                            }
////                                        cekLime($this->db->last_query());
//                        }
//                        if (sizeof($insertIDs) > 0) {
//                            $arrBlob = blobEncode($insertIDs);
//                            $this->db->query("UPDATE transaksi SET indexing_detail_values = '$arrBlob' WHERE id=$insertID");
//                        }
//                    }
//                }
//            }

//            if (sizeof($tableIn_detail_values2_sum) > 0) {
//                foreach ($tableIn_detail_values2_sum as $pID => $dSpec) {
//                    if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues2_sum'])) {
//                        foreach ($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues2_sum'] as $key => $src) {
//                            $dd = $tr->writeDetailValues($insertID, array(
//                                "produk_jenis" => $tableIn_detail2_sum[$pID]['produk_jenis'],
//                                "produk_id" => $pID,
//                                "key" => $key,
//                                "value" => $dSpec[$src],
//                            ));
//                            $insertIDs[] = $dd;
//                            $mongoList['detailValues'][] = $dd;
//                        }
//                    }
//                }
//            }

            //endregion

            //===components akan langsung dieksekusi jika steps-nya tidak pakai approval
//            $steps = $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'];
//            $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
//            $filterNeeded = false;

            //====registri value-gate
            $baseRegistries = array(
                'main' => isset($main) ? $main : array(),
                'items' => isset($items) ? $items : array(),
                'items2' => isset($items2) ? $items2 : array(),
                'items2_sum' => isset($items2_sum) ? $items2_sum : array(),
                'items3' => isset($items3) ? $items3 : array(),
                'items3_sum' => isset($items3_sum) ? $items3_sum : array(),
                'rsltItems' => isset($rsltItems) ? $rsltItems : array(),
                'rsltItems2' => isset($rsltItems2) ? $rsltItems2 : array(),
                'tableIn_master' => isset($tableIn_master) ? $tableIn_master : array(),
                'tableIn_detail' => isset($tableIn_detail) ? $tableIn_detail : array(),
                'tableIn_detail2_sum' => isset($tableIn_detail2_sum) ? $tableIn_detail2_sum : array(),
                'tableIn_detail_rsltItems' => isset($tableIn_detail_rsltItems) ? $tableIn_detail_rsltItems : array(),
                'tableIn_detail_rsltItems2' => isset($tableIn_detail_rsltItems2) ? $tableIn_detail_rsltItems2 : array(),
                'tableIn_master_values' => isset($tableIn_master_values) ? $tableIn_master_values : array(),
                'tableIn_detail_values' => isset($tableIn_detail_values) ? $tableIn_detail_values : array(),
                'tableIn_detail_values_rsltItems' => isset($tableIn_detail_values_rsltItems) ? $tableIn_detail_values_rsltItems : array(),
                'tableIn_detail_values_rsltItems2' => isset($tableIn_detail_values_rsltItems2) ? $tableIn_detail_values_rsltItems2 : array(),
                'tableIn_detail_values2_sum' => isset($tableIn_detail_values2_sum) ? $tableIn_detail_values2_sum : array(),
                'main_add_values' => isset($main_add_values) ? $main_add_values : array(),
                'main_add_fields' => isset($main_add_fields) ? $main_add_fields : array(),
                'main_elements' => isset($main_elements) ? $main_elements : array(),
                'main_inputs' => isset($main_inputs) ? $main_inputs : array(),
                'main_inputs_orig' => isset($main_inputs) ? $main_inputs : array(),
                "receiptDetailFields" => isset($this->config->item("heTransaksi_layout")[$jenisTr]['receiptDetailFields'][1]) ? $this->config->item("heTransaksi_layout")[$jenisTr]['receiptDetailFields'][1] : array(),
                "receiptSumFields" => isset($this->config->item("heTransaksi_layout")[$jenisTr]['receiptSumFields'][1]) ? $this->config->item("heTransaksi_layout")[$jenisTr]['receiptSumFields'][1] : array(),
                "receiptDetailFields2" => isset($this->config->item("heTransaksi_layout")[$jenisTr]['receiptDetailFields2'][1]) ? $this->config->item("heTransaksi_layout")[$jenisTr]['receiptDetailFields2'][1] : array(),
                "receiptSumFields2" => isset($this->config->item("heTransaksi_layout")[$jenisTr]['receiptSumFields2'][1]) ? $this->config->item("heTransaksi_layout")[$jenisTr]['receiptSumFields2'][1] : array(),
            );

            //===
            $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
            showLast_query("hijau");
//            $mongRegID[] = $doWriteReg;

            //endregion

            //endregion
            $static = array(
                "static" => array(
                    "cabang_id" => $cSpec->id,
                    "dtime" => $dateTimeNow,
                    "fulldate" => $dateNow,
                    //                    "bln" => $dateLast_ex[1],
                    //                    "thn" => $dateLast_ex[0],
//                    "bln"       => $bulan,
//                    "thn" => $tahun,
                    "periode" => $periode,
                    "transaksi_no" => $insertNum,
                    "transaksi_id" => $insertID,
                ),
            );

            $filters = array(
                "periode" => $periode,
                "cabang_id" => $cSpec->id,
//                "bln" => $bulan,
//                "thn" => $tahun,
            );
            $filters2 = array(
                "periode=" => $periode,
                "cabang_id=" => $cSpec->id,
//                "bln<=" => $bulan,
//                "thn<=" => $tahun,
            );


            cekHitam(":: MULAI RL " . $cSpec->id . " :: " . $cSpec->nama);

//            mati_disini("SETOP");
            // $rl = New ComRugiLaba_cli();

            $rl->setFilters2($filters2);
            $rl->setFilters($filters);
            $rl->pair($static);
            showLast_query("lime");
            $rl->exec();
            cekKuning(":: DONE RL " . $cSpec->id . " :: " . $cSpec->nama);


            cekHitam(":: MULAI NERACA " . $cSpec->id . " :: " . $cSpec->nama);


            $n->setFilters2($filters2);
            $n->setFilters($filters);
            $n->pair($static);
            $n->exec();
            showLast_query("lime");
            cekHitam(":: CLOSE NERACA " . $cSpec->id . " :: " . $cSpec->nama);
//            mati_disini("SETOP");

        }

        // region simpan config view rl dan neraca
        $categoryRL = $this->config->item("categoryRL") != NULL ? $this->config->item("categoryRL") : array();
        $accountRekeningSort = $this->config->item("accountRekeningSort") != NULL ? $this->config->item("accountRekeningSort") : array();
        $accountStructure = $this->config->item("accountStructure") != NULL ? $this->config->item("accountStructure") : array();
        $arrConfig = array(
            "categoryRL" => array(
                "param" => "categoryRL",
                "values" => blobEncode($categoryRL),
//                "bln" => $bulan,
                "thn" => $tahun,
                "periode" => $periode,
            ),
            "accountRekeningSort" => array(
                "param" => "accountRekeningSort",
                "values" => blobEncode($accountRekeningSort),
//                "bln" => $bulan,
                "thn" => $tahun,
                "periode" => $periode,
            ),
            "accountStructure" => array(
                "param" => "accountStructure",
                "values" => blobEncode($accountStructure),
//                "bln" => $bulan,
                "thn" => $tahun,
                "periode" => $periode,
            ),
        );

        foreach ($arrConfig as $fcSpec) {
            $fc->addData($fcSpec);
            cekHijau($this->db->last_query());
        }
        // endregion

//        writeLog("generate rugi-laba","auto","cli","","","","generator rugi-laba");

        mati_disini("CILUKBAAA.... TESTING LAGI... HI HI HI  BELUM DICOMMIT");

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        }
        else {
            $this->db->trans_commit();
        }

        $pakai_ini = 1;
        if ($pakai_ini == 1) {

            $em->setAddressFrom("noreply.mgkcore@gmail.com");
            $em->setAddressTo($emTos);
            $em->setSubject("noreply :: " . __METHOD__);
            $em->kirim_email("running (forever) untuk $tahun  @$dateTimeNow");

        }


        cekHijau("<h1>done</h1>");

        // writeLog("generate rugi-laba","auto","cli","","","","generator rugi-laba");

    }


    //-neraca konsolidasi static BULANAN-----------------------------
    function bulananNeracaKonsolidasi()
    {


        $dateTimeNow = dtimeNow();
        $date = date("Y-m-01");
//        $date = "2021-03-01";// ini nanti dimatikan
        $dateNow = dtimeNow("d");
        $dateRun = 1;

        $prevBl = previousMonth();
//        $prevBl = "2021-01";// ini nanti dimatikan
        $dateLast_ex = explode("-", $prevBl);
        $periode = "bulanan";
        $bulan = $dateLast_ex[1];
        $tahun = $dateLast_ex[0];
        //---------------------------

        //---------------------------
//        $bulanLast = $bulan - 1;
//        if (strlen($bulanLast) == 1) {
//            $bulanLast = "0$bulanLast";
//        }
//        if ($bulan == "01") {
//            $bulanLast = 12;
//            $tahunLast = $tahun - 1;
//            $getDateLastNeraca = "$tahunLast-$bulanLast";
//        }
//        else {
//            $getDateLastNeraca = "$tahun-$bulanLast";
//        }
//        $cekMerah = ("prevBL: $prevBl : bulan: $bulan : tahun: $tahun : lastDateNeraca: $getDateLastNeraca");
//        cekMerah($cekMerah);

        //-----------------------------------------------
        // cek neraca per-cabang
        $n = New MdlNeraca();
        $arrFilter = array(
            "periode='$periode'",
            "bln='$bulan'",
            "thn='$tahun'",
            "status='1'",
            "trash='0'",
        );
        $n->setFilters(array());
        foreach ($arrFilter as $f) {
            $n->addFilter($f);
        }
        $nTmp = $n->lookupAll()->result();
//        showLast_query("biru");
//        cekBiru(sizeof($nTmp));

        // cek neraca konsolidasi
        $n = New MdlNeraca();
        $arrFilter = array(
            "periode='$periode'",
            "bln='$bulan'",
            "thn='$tahun'",
            "status='1'",
            "trash='0'",
        );
        $n->setFilters(array());
        foreach ($arrFilter as $f) {
            $n->addFilter($f);
        }
        $n->addFilter("tipe in ('konsolidasi_riil', 'konsolidasi_cost')");
        $nKonsolidasiTmp = $n->lookupAll()->result();
        showLast_query("biru");
        if (sizeof($nKonsolidasiTmp) > 0) {
            mati_disini("sudah ada neraca konsolidasi $periode, bulan $bulan, tahun $tahun ...");
        }

        //------------------------------------------------
        $rekExceptionKonsolidasi = $this->config->item('accountNeracaExceptions_konsolidasi') != NULL ? $this->config->item('accountNeracaExceptions_konsolidasi') : array();
        $rekTipeKonsolidasi = $this->config->item('accountNeracaTipe_konsolidasi') != NULL ? $this->config->item('accountNeracaTipe_konsolidasi') : array(); // cost atau riil
        //------------------------------------------------
//        arrPrintWebs($rekTipeKonsolidasi);

        $this->db->trans_begin();

        $arrFields = array(
            "rek_id",
            "kategori",
            "rekening",
            "periode",
            "bln",
            "thn",
        );
        $items = array();
        $subItems = array();
        $konsolidasiItems = array();
        $konsolidasiItemValues = array();
        if (sizeof($nTmp) > 0) {
            foreach ($nTmp as $nSpec) {
                if (!in_array($nSpec->rekening, $rekExceptionKonsolidasi)) {

                    foreach ($arrFields as $field) {
                        $subItems[$field] = isset($nSpec->$field) ? $nSpec->$field : 0;
                    }
                    if (!isset($items[$nSpec->rekening])) {
                        $items[$nSpec->rekening] = $subItems;
                    }
                    // default definisi debet
                    if (!isset($items[$nSpec->rekening]['debet'])) {
                        $items[$nSpec->rekening]['debet'] = 0;
                    }
                    // default definisi kredit
                    if (!isset($items[$nSpec->rekening]['kredit'])) {
                        $items[$nSpec->rekening]['kredit'] = 0;
                    }
                    //---------------------------------------------------
                    $items[$nSpec->rekening]['debet'] += isset($nSpec->debet) ? $nSpec->debet : 0;
                    $items[$nSpec->rekening]['kredit'] += isset($nSpec->kredit) ? $nSpec->kredit : 0;

                }

            }

            if (sizeof($items) > 0) {
                foreach ($items as $rekName => $rekSpec) {

                    if (($rekSpec['debet'] > 0) && ($rekSpec['kredit'] > 0)) {
                        cekHitam(":: $rekName :: " . $rekSpec['debet'] . " :: " . $rekSpec['kredit']);
                        //----------------------------------
                        $defaultPosition = detectRekDefaultPosition($rekName);
                        $oppositePosition = $defaultPosition == "debet" ? "kredit" : "debet";
                        //----------------------------------
                        if ($defaultPosition == "debet") {
                            $kredit = 0;
                            $debet = $rekSpec[$defaultPosition] - $rekSpec[$oppositePosition];
                            if ($debet < 0) {
                                $kredit = $debet * -1;
                                $debet = 0;
                            }
                        }
                        elseif ($defaultPosition == "kredit") {
                            $debet = 0;
                            $kredit = $rekSpec[$defaultPosition] - $rekSpec[$oppositePosition];
                            if ($kredit < 0) {
                                $debet = $kredit * -1;
                                $kredit = 0;
                            }
                        }
                        //----------------------------------

                        cekKuning("$rekName :: debet -> $debet :: kredit -> $kredit ::");

                        // replace items rekName debet/kredit
                        $rekSpec['debet'] = $debet;
                        $rekSpec['kredit'] = $kredit;

                        $items[$rekName]['debet'] = $debet;
                        $items[$rekName]['kredit'] = $kredit;
                    }

                    //--------------------------------------
                    if ($rekSpec['debet'] > 0) {
                        $value = $rekSpec['debet'];
                    }
                    else {
                        $value = $rekSpec['kredit'];
                    }
                    $konsolidasiItemValues[$rekName] = $value;
                }

                foreach ($items as $rekName => $rekSpec) {
                    foreach ($rekTipeKonsolidasi as $tipe => $tSpec) {
                        if (sizeof($tSpec) > 0) {
                            if (isset($tSpec[$rekName])) {
                                $rumus = $tSpec[$rekName];
                                $value = makeValue($rumus, $konsolidasiItemValues, $konsolidasiItemValues, 0);
                                if ($rekSpec['debet'] > 0) {
                                    $rekSpec['debet'] = $value;
                                }
                                else {
                                    $rekSpec['kredit'] = $value;
                                }
                                $konsolidasiItems[$tipe][$rekName] = $rekSpec;
                            }
                            else {
                                $konsolidasiItems[$tipe][$rekName] = $rekSpec;
                            }
                        }
                        else {
                            $konsolidasiItems[$tipe][$rekName] = $rekSpec;
                        }
                    }
                }

                //-------------------------------
                $header = array(
                    "rekening" => "nama",
                    "debet" => "debet",
                    "kredit" => "kredit",
                );
                $str = "";
                foreach ($konsolidasiItems as $tipe => $kSpec) {
                    $total = array();
                    //region untuk view debuger
                    $str .= "<h2>--- $tipe ---</h2>";
                    $str .= "<table rules='all' style='border:1px solid black;'>";
                    $str .= "<tr style='font-weight: bold;'>";
                    foreach ($header as $val) {
                        $str .= "<td>$val</td>";
                    }
                    $str .= "</tr>";

                    foreach ($kSpec as $iSpec) {

                        $str .= "<tr>";
                        foreach ($header as $key => $val) {
                            $str .= "<td>";
                            $str .= formatField($key, $iSpec[$key]);
                            $str .= "</td>";

                            if (is_numeric($iSpec[$key])) {
                                if (!isset($total[$key])) {
                                    $total[$key] = 0;
                                }
                                $total[$key] += $iSpec[$key];
                            }
                        }
                        $str .= "</tr>";

                    }


                    $str .= "<tr style='font-weight: bold;'>";
                    foreach ($header as $key => $val) {
                        $str .= "<td>";
                        $str .= isset($total[$key]) ? formatField($key, $total[$key]) : "";
                        $str .= "</td>";
                    }
                    $str .= "</tr>";

                    $str .= "</table>";
                    $str .= "<br><br><br>";
                    //endregion

                    //----------------------------------
//                    arrPrint($total);
                    if (floor($total['debet']) != floor($total['kredit'])) {
                        if (round($total['debet'], 1) != round($total['kredit'], 1)) {
                            if (round($total['debet'], 2) != round($total['kredit'], 2)) {
                                mati_disini("neraca konsolidasi $tipe unbalance.");
                            }
                        }
                    }
                    foreach ($kSpec as $iSpec) {
                        $iSpec['tipe'] = "konsolidasi_" . $tipe;
                        $iSpec['dtime'] = date("Y-m-d H:i:s");
                        $iSpec['status'] = 1;
                        $iSpec['trash'] = 0;
                        $iSpec['cabang_id'] = 0;

                        // ---- MENULIS KE TABEL NERACA KONSOLIDASI COST / KONSOLIDASI RIIL
                        $nn = New MdlNeraca();
                        $nn->addData($iSpec);
//                        showLast_query("hijau");
                    }
                }

                //-------------------------------


            }
        }


//        echo $str;

//        mati_disini("CILUKBAAA.... TESTING LAGI... HI HI HI  BELUM DICOMMIT");
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        }
        else {
            $this->db->trans_commit();
        }

    }
}