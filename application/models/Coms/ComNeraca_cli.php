<?php


class ComNeraca_cli extends MdlMother
{

    protected $filters = array();
    protected $filters2 = array();
    private $tableName;
    private $tableName_lajur;
    private $tableName_mutasi;
    private $tableName_master = array();
    private $inParams = array( //===inputan dari transaksi
    );
    private $outParams = array( //===output ke tabel
    );
    private $outParams2 = array( //===output ke tabel
    );
    private $outFields = array( // dari tabel rek_cache
        "rekening",
        "periode",
        "cabang_id",
        "debet_awal",
        "debet",
        "debet_akhir",
        "kredit_awal",
        "kredit",
        "kredit_akhir",
        "dtime",
        "tgl",
        "bln",
        "thn",
        "fulldate",
        "tipe",
    );
    private $outFieldsMutasi = array( // dari tabel rek mutasi rekening umum
        "dtime",
        "transaksi_id",
        "transaksi_no",
        "cabang_id",
        "jenis",
        "debet_awal",
        "debet",
        "debet_akhir",
        "kredit_awal",
        "kredit",
        "kredit_akhir",
        "keterangan",
    );
    private $periode = array("harian", "bulanan", "tahunan", "forever");
    private $periode2 = array();


    public function __construct()
    {
        $this->tableName = "_rek_master_cache";
        $this->tableName_master = array(
            "mutasi" => "_rek_master",
            //            "cache" => "_rek_master_cache",
        );
    }

    //  region setter, getter
    public function getTableNameMaster()
    {
        return $this->tableName_master;
    }

    public function setTableNameMaster($tableName_master)
    {
        $this->tableName_master = $tableName_master;
    }

    public function getPeriode()
    {
        return $this->periode;
    }

    public function setPeriode($periode)
    {
        $this->periode = $periode;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function getTableNameTmp()
    {
        return $this->tableName__tmp;
    }

    public function setTableNameTmp($tableName__tmp)
    {
        $this->tableName__tmp = $tableName__tmp;
    }

    public function getTableName_lajur()
    {
        return $this->tableName_lajur;
    }

    public function setTableName_lajur($tableName_lajur)
    {
        $this->tableName_lajur = $tableName_lajur;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    public function getFilters2()
    {
        return $this->filters2;
    }

    public function setFilters2($filters)
    {
        $this->filters2 = $filters;
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

    public function getOutParams2()
    {
        return $this->outParams2;
    }

    public function setOutParams2($outParams2)
    {
        $this->outParams2 = $outParams2;
    }

    public function getOutFields()
    {
        return $this->outFields;
    }

    public function setOutFields($outFields)
    {
        $this->outFields = $outFields;
    }

    public function getOutFieldsMutasi()
    {
        return $this->outFieldsMutasi;
    }

    public function setOutFieldsMutasi($outFieldsMutasi)
    {
        $this->outFieldsMutasi = $outFieldsMutasi;
    }

    public function getTableNameMutasi()
    {
        return $this->tableName_mutasi;
    }

    public function setTableNameMutasi($tableName_mutasi)
    {
        $this->tableName_mutasi = $tableName_mutasi;
    }

    public function getPeriode2()
    {
        return $this->periode2;
    }

    public function setPeriode2($periode2)
    {
        $this->periode2 = $periode2;
    }

    //  endregion setter, getter

    public function pair($inParams)
    {
        $this->load->model("ComRekening_cli");
        $this->load->helper("he_mass_table");
        $this->inParams = $inParams;

        cekHitam("::: COM NERACA :::");


        $cr = New ComRekening_cli();

        $cr->setFilters(array());
        $cr->setFilters2(array());

        $cr->addFilter("cabang_id='" . $this->inParams['static']['cabang_id'] . "'");
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
        //cekKuning($this->db->last_query());
        //arrPrint($tmp);
        //mati_disini();


        $arrComJurnal = array();
        $arrComRekening = array();
        $arrRekening = array();
        $arrRekeningCat = array();
        $arrNeraca = array();
        $arrLR = array();
        $ldtSpec = array();
        $totals = array(
            "debet" => 0,
            "kredit" => 0,
        );

        if (sizeof($tmp) > 0) {
            $ldtCtr = 0;
            foreach ($tmp as $eSpec) {
                $rek = $eSpec['rekening'];
                $rekCategory = detectRekCategory($rek);

                $neracaSrcs = array("aktiva", "hutang", "modal", "lain-lain-kr");
                $lrSrcs = array("laba(rugi)");


                if (in_array($rekCategory, $neracaSrcs)) {
                    if (!isset($arrRekeningCat[$rekCategory])) {
                        $arrRekeningCat[$rekCategory] = 0;
                    }

                    $catNeraca = $rekCategory;

                    $arrNeraca[] = array(
                        "rek_id" => $eSpec["rek_id"],
                        "kategori" => $catNeraca,
                        "rekening" => $eSpec["rekening"],
                        "debet" => $eSpec["debet"],
                        "kredit" => $eSpec["kredit"],
                        "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                        "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                        "cabang_id" => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                        "dtime" => date("Y-m-d H:i:s"),
                        "author" => isset($this->session->login) ? $this->session->login['id'] : 0,
                        "keterangan" => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                        "fulldate" => date("Y-m-d"),
                    );
                    $totals['debet'] += $eSpec["debet"];
                    $totals['kredit'] += $eSpec["kredit"];
                }


                if (in_array($rekCategory, $lrSrcs)) {
                    //                    cekbiru("member of LR, namanya $rek $rekCategory " . __LINE__);
                    //                    cekbiru("mengisi laba ditahan dengan nilai D/K " . $eSpec['debet'] . "/" . $eSpec['kredit']);

                    $ldtCtr++;

//                    $rekening = "laba ditahan";
                    $rekening = "3020060";
                    $catNeraca = detectRekCategory($rekening);


                    if (!isset($ldtSpec[$ldtCtr])) {
                        $ldtSpec[$ldtCtr] = array(
                            "rek_id" => $eSpec["rek_id"],
                            "kategori" => $catNeraca,
                            "rekening" => $rekening,
                            "debet" => 0,
                            "kredit" => 0,
                            "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                            "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                            "cabang_id" => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                            "dtime" => date("Y-m-d H:i:s"),
                            "author" => isset($this->session->login) ? $this->session->login['id'] : 0,
                            "keterangan" => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                            "fulldate" => date("Y-m-d"),
                        );
                    }
                    $ldtSpec[$ldtCtr]['debet'] += $eSpec['debet'];
                    $ldtSpec[$ldtCtr]['kredit'] += $eSpec['kredit'];


                    if ($eSpec['debet'] > 0) {
                        $preNumber = detectRekByPosition($rek, $eSpec['debet'], "debet");
                    }
                    else {
                        $preNumber = detectRekByPosition($rek, $eSpec['kredit'], "kredit");
                    }


                    $key = $rek;
                    if (!isset($arrComJurnal[$ldtCtr]["loop"][$key])) {
                        $arrComJurnal[$ldtCtr]["comName"] = "";
                        $arrComJurnal[$ldtCtr]["loop"][$key] = 0;
                    }
                    if (!isset($arrComRekening[$ldtCtr]["loop"][$key])) {
                        $arrComRekening[$ldtCtr]["comName"] = "";
                        $arrComRekening[$ldtCtr]["loop"][$key] = 0;
                    }

                    $arrComJurnal[$ldtCtr]["comName"] = "Jurnal";
                    $arrComJurnal[$ldtCtr]["loop"][$key] = -($preNumber);


                    $arrComRekening[$ldtCtr]["comName"] = "Rekening_cli";
                    $arrComRekening[$ldtCtr]["loop"][$key] = -($preNumber);


                    if (isset($this->inParams["static"])) {
                        foreach ($this->inParams["static"] as $key_static => $value_static) {
                            $arrComJurnal[$ldtCtr]["static"][$key_static] = $value_static;
                            $arrComRekening[$ldtCtr]["static"][$key_static] = $value_static;
                        }
                    }

                }

            }
            //=============sini

            if (sizeof($ldtSpec)) {
                foreach ($ldtSpec as $ctr => $ldtSpecVal) {

                    $eSpec = $ldtSpecVal;
                    $rek = $eSpec['rekening'];
                    $arrNeraca[] = $ldtSpecVal;
                    $totals['debet'] += $eSpec["debet"];
                    $totals['kredit'] += $eSpec["kredit"];


                    if ($eSpec['debet'] > 0) {
                        //                        cekHijau("HEHE");
                        $preNumber = detectRekByPosition($rek, $eSpec['debet'], "debet");
                    }
                    else {
                        //                        cekHijau("HAHA");
                        $preNumber = detectRekByPosition($rek, $eSpec['kredit'], "kredit");
                    }
                    //                    cekHitam("$rek :: preNumber :: $preNumber");

                    //                    $arrComJurnal[$ctr]["loop"]["laba ditahan"] = -($preNumber);
                    //                    $arrComRekening[$ctr]["loop"]["laba ditahan"] = -($preNumber);
                    $arrComJurnal[$ctr]["loop"]["3020060"] = ($preNumber);
                    $arrComRekening[$ctr]["loop"]["3020060"] = ($preNumber);
                }

            }

            //=============EO sini
            if (sizeof($arrComJurnal) > 0) {
                foreach ($arrComJurnal as $arrComJurnalSpec) {

                    $model = "Com" . $arrComJurnalSpec["comName"];
                    $this->load->model($model);
                    $c = New $model;
                    $c->pair($arrComJurnalSpec);
                    $this->outParams[] = $c->exec();
                }
            }

            if (sizeof($arrComRekening) > 0) {

                foreach ($arrComRekening as $arrComRekeningSpec) {

                    $loopParams = isset($arrComRekeningSpec['loop']) ? $arrComRekeningSpec['loop'] : array();
                    if (sizeof($loopParams) > 0) {
                        foreach ($loopParams as $key => $val) {
                            if ($val == 0) {
                                unset($arrComRekeningSpec['loop'][$key]);
                            }
                        }
                    }


                    $model = "Com" . $arrComRekeningSpec["comName"];
//                    cekKuning("Com -> " . $model);
                    $this->load->model($model);
                    $c = New $model;
                    $c->setFilters(array());
                    //                    $c->setFilters2(array());
                    $c->setFilters($setFilters);
                    $c->pair($arrComRekeningSpec);
                    $this->outParams[] = $c->exec();
                }
            }
            else {
                cekKuning(":: TIDAK ADA arrRekening ::");
            }
        }

        //        cekBiru("cetak array NERACA...");


        if (sizeof($arrNeraca) > 0) {
            $this->load->model("Mdls/MdlNeraca");

//            cekUngu(":: cetak NERACA ::");
            $tb = "<table rules='all' style='border: 1px solid black;'>";
            $tb .= "<tr>";
            $tb .= "<td>rekening</td>";
            $tb .= "<td>debet</td>";
            $tb .= "<td>kredit</td>";
            $tb .= "</tr>";

            foreach ($arrNeraca as $arrSpec) {
                foreach ($this->inParams['static'] as $rkey => $rval) {
                    $arrSpec[$rkey] = $rval;
                }
                $rl = New MdlNeraca();
                $rl->addData($arrSpec, $rl->getTableName());
                //cekUngu($this->db->last_query());

                $tb .= "<tr>";
                $tb .= "<td style='text-align: left;'>" . $arrSpec['rekening'] . "</td>";
                $tb .= "<td style='text-align: right;'>" . number_format($arrSpec['debet'], '5', '.', ',') . "</td>";
                $tb .= "<td style='text-align: right;'>" . number_format($arrSpec['kredit'], '5', '.', ',') . "</td>";
                $tb .= "</tr>";
            }

            $tb .= "<tr>";
            $tb .= "<td style='text-align: left;'>-</td>";
            $tb .= "<td style='text-align: right;'>" . number_format($totals['debet'], '5', '.', ',') . "</td>";
            $tb .= "<td style='text-align: right;'>" . number_format($totals['kredit'], '5', '.', ',') . "</td>";
            $tb .= "</tr>";
            $tb .= "</table>";
            echo $tb;

            //            cekHitam(":: DEBET :: " . round($totals['debet'], 0));
            //            cekHitam(":: KREDIT :: " . round($totals['kredit'], 0));

//            if (round($totals['debet'], 0) != round($totals['kredit'], 0)) {
//                $selisih = number_format($totals['debet'] - $totals['kredit'], "10", ".", ",");
//                mati_disini("neraca tidak balance!! " . round($totals['debet'], 1) . " vs " . round($totals['kredit'], 1) . " SELISIH: " . $selisih);
//                cekHitam("neraca tidak balance!! " . round($totals['debet'], 1) . " vs " . round($totals['kredit'], 1) . " SELISIH: " . $selisih);
////                die(lgShowAlert("neraca tidak balance! " . $totals['debet'] . " vs " . $totals['kredit']));
//            }
            $selisih = $totals['debet'] - $totals['kredit'];
            cekUngu(__LINE__ . " selisih: $selisih ");
            $selisih = $selisih < 0 ? $selisih * -1 : $selisih;
            if ($selisih > 5) {
                cekHitam("selisih $selisih");
                mati_disini(__LINE__ . " neraca tidak balance!! " . round($totals['debet'], 1) . " vs " . round($totals['kredit'], 1) . " SELISIH: " . $selisih);
            }

            /*if (round($totals['debet'], 1) != round($totals['kredit'], 1)) {
                if (round($totals['debet'], 0) != round($totals['kredit'], 0)) {
                    if (floor($totals['debet']) != floor($totals['kredit'])) {
                        $selisih = $selisih < 0 ? $selisih * -1 : $selisih;
                        if ($selisih > 2) {
                            cekHitam("selisih $selisih");
                            mati_disini(__LINE__ . " neraca tidak balance!! " . round($totals['debet'], 1) . " vs " . round($totals['kredit'], 1) . " SELISIH: " . $selisih);
                        }
                        else {
                            cekKuning("selisih $selisih");
                        }
                    }
                    else {
//                    mati_disini("neraca tidak balance!! " . round($totals['debet'], 1) . " vs " . round($totals['kredit'], 1) . " SELISIH: " . $selisih);
                    }
                }
            }
            else {

            }*/
//mati_disini(":: UNDER MAINTENANCE ::");
            return true;
        }
        else {
            return false;
        }

    }

    public function addFilter($f)
    {
        $this->filters[] = $f;
    }

    public function exec()
    {

        return true;
    }

    public function buildTables($inParams)
    {

        $this->load->helper("he_mass_table");

        $arrRekening = array();
        $this->inParams = $inParams;
        if (sizeof($this->inParams['loop']) > 0) {
            foreach ($this->periode as $periode) {
                $arrRekening = array();
                foreach ($this->inParams['loop'] as $key => $value) {
                    $arrRekening[] = $key;
                }
            }
        }
        else {
            $arrRekening = array();
        }

        if (sizeof($arrRekening) > 0) {
            $result = heReturnTableName($this->tableName_master, $arrRekening);
            if (sizeof($result) > 0) {
                foreach ($result as $rek => $arrSpec) {
                    foreach ($arrSpec as $key => $val) {
                        //                        cekMerah("create tabel $val - $key");
                        $result_c = tableForceCheck($val, $this->tableName_master[$key]);
                    }
                }
            }
        }
    }


    public function pairNoCut($inParams, $neraca)
    {
        $this->load->model("ComRekening_cli");
        $this->load->helper("he_mass_table");
        $this->inParams = $inParams;

        cekHitam("::: COM NERACA :::");


        $cr = New ComRekening_cli();
        $cr->setFilters(array());
        $cr->setFilters2(array());
//        $cr->addFilter("cabang_id='" . $this->inParams['static']['cabang_id'] . "'");
//        if (isset($this->filters)) {
//            $setFilters = $this->filters;
//            foreach ($this->filters as $kf => $vf) {
//                $cr->addFilter("$kf='$vf'");
//            }
//        }
//        if (isset($this->filters2)) {
//            $cr->setFilters2($this->filters2);
//        }
//        $tmp = $cr->fetchAllBalances2();

        $tmp = $neraca;


        $arrComJurnal = array();
        $arrComRekening = array();
        $arrRekening = array();
        $arrRekeningCat = array();
        $arrNeraca = array();
        $arrLR = array();
        $ldtSpec = array();
        $totals = array(
            "debet" => 0,
            "kredit" => 0,
        );

        if (sizeof($tmp) > 0) {
            $ldtCtr = 0;
            foreach ($tmp as $eSpec) {
                $rek = $eSpec['rekening'];
                $rekCategory = detectRekCategory($rek);

                $neracaSrcs = array("aktiva", "hutang", "modal", "lain-lain-kr");
                $lrSrcs = array("laba(rugi)");


                if (in_array($rekCategory, $neracaSrcs)) {
                    if (!isset($arrRekeningCat[$rekCategory])) {
                        $arrRekeningCat[$rekCategory] = 0;
                    }

                    $catNeraca = $rekCategory;

                    $arrNeraca[] = array(
                        "rek_id" => $eSpec["rek_id"],
                        "kategori" => $catNeraca,
                        "rekening" => $eSpec["rekening"],
                        "debet" => $eSpec["debet"],
                        "kredit" => $eSpec["kredit"],
                        "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                        "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                        "cabang_id" => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                        "dtime" => date("Y-m-d H:i:s"),
                        "author" => isset($this->session->login) ? $this->session->login['id'] : 0,
                        "keterangan" => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                        "fulldate" => date("Y-m-d"),
                        "tipe" => isset($this->inParams["static"]['tipe']) ? $this->inParams["static"]['tipe'] : "",
                    );
                    $totals['debet'] += $eSpec["debet"];
                    $totals['kredit'] += $eSpec["kredit"];
                }


                if (in_array($rekCategory, $lrSrcs)) {
                    $ldtCtr++;
//                    $rekening = "laba ditahan";
                    $rekening = "3020060";
                    $catNeraca = detectRekCategory($rekening);


                    if (!isset($ldtSpec[$ldtCtr])) {
                        $ldtSpec[$ldtCtr] = array(
                            "rek_id" => $eSpec["rek_id"],
                            "kategori" => $catNeraca,
                            "rekening" => $rekening,
                            "debet" => 0,
                            "kredit" => 0,
                            "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                            "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                            "cabang_id" => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                            "dtime" => date("Y-m-d H:i:s"),
                            "author" => isset($this->session->login) ? $this->session->login['id'] : 0,
                            "keterangan" => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                            "fulldate" => date("Y-m-d"),
                            "tipe" => isset($this->inParams["static"]['tipe']) ? $this->inParams["static"]['tipe'] : "",
                        );
                    }
                    $ldtSpec[$ldtCtr]['debet'] += $eSpec['debet'];
                    $ldtSpec[$ldtCtr]['kredit'] += $eSpec['kredit'];


                    if ($eSpec['debet'] > 0) {
                        $preNumber = detectRekByPosition($rek, $eSpec['debet'], "debet");
                    }
                    else {
                        $preNumber = detectRekByPosition($rek, $eSpec['kredit'], "kredit");
                    }


                    $key = $rek;
                    if (!isset($arrComJurnal[$ldtCtr]["loop"][$key])) {
                        $arrComJurnal[$ldtCtr]["comName"] = "";
                        $arrComJurnal[$ldtCtr]["loop"][$key] = 0;
                    }
                    if (!isset($arrComRekening[$ldtCtr]["loop"][$key])) {
                        $arrComRekening[$ldtCtr]["comName"] = "";
                        $arrComRekening[$ldtCtr]["loop"][$key] = 0;
                    }

                    $arrComJurnal[$ldtCtr]["comName"] = "Jurnal";
                    $arrComJurnal[$ldtCtr]["loop"][$key] = -($preNumber);


                    $arrComRekening[$ldtCtr]["comName"] = "Rekening_cli";
                    $arrComRekening[$ldtCtr]["loop"][$key] = -($preNumber);


                    if (isset($this->inParams["static"])) {
                        foreach ($this->inParams["static"] as $key_static => $value_static) {
                            $arrComJurnal[$ldtCtr]["static"][$key_static] = $value_static;
                            $arrComRekening[$ldtCtr]["static"][$key_static] = $value_static;
                        }
                    }

                }

            }
            //=============sini

            if (sizeof($ldtSpec)) {
                foreach ($ldtSpec as $ctr => $ldtSpecVal) {

                    $eSpec = $ldtSpecVal;
                    $rek = $eSpec['rekening'];
                    $arrNeraca[] = $ldtSpecVal;
                    $totals['debet'] += $eSpec["debet"];
                    $totals['kredit'] += $eSpec["kredit"];


                    if ($eSpec['debet'] > 0) {
                        $preNumber = detectRekByPosition($rek, $eSpec['debet'], "debet");
                    }
                    else {
                        $preNumber = detectRekByPosition($rek, $eSpec['kredit'], "kredit");
                    }
//                    $arrComJurnal[$ctr]["loop"]["laba ditahan"] = ($preNumber);
//                    $arrComRekening[$ctr]["loop"]["laba ditahan"] = ($preNumber);
                    $arrComJurnal[$ctr]["loop"]["3020060"] = ($preNumber);
                    $arrComRekening[$ctr]["loop"]["3020060"] = ($preNumber);
                }

            }

            //=============EO sini
//            if (sizeof($arrComJurnal) > 0) {
//                foreach ($arrComJurnal as $arrComJurnalSpec) {
//
//                    $model = "Com" . $arrComJurnalSpec["comName"];
//                    $this->load->model($model);
//                    $c = New $model;
//                    $c->pair($arrComJurnalSpec);
//                    $this->outParams[] = $c->exec();
//                }
//            }

            if (sizeof($arrComRekening) > 0) {
                foreach ($arrComRekening as $arrComRekeningSpec) {
                    $loopParams = isset($arrComRekeningSpec['loop']) ? $arrComRekeningSpec['loop'] : array();
                    if (sizeof($loopParams) > 0) {
                        foreach ($loopParams as $key => $val) {
                            if ($val == 0) {
                                unset($arrComRekeningSpec['loop'][$key]);
                            }
                        }
                    }


//                    $model = "Com" . $arrComRekeningSpec["comName"];
//                    $this->load->model($model);
//                    $c = New $model;
//                    $c->setFilters(array());
//                    $c->setFilters($setFilters);
//                    $c->pair($arrComRekeningSpec);
//                    $this->outParams[] = $c->exec();
                }
            }
            else {
                cekKuning(":: TIDAK ADA arrRekening ::");
            }
        }


        if (sizeof($arrNeraca) > 0) {
            $this->load->model("Mdls/MdlNeraca");

            cekUngu(":: cetak NERACA ::");
            $tb = "<table rules='all' style='border: 1px solid black;'>";
            $tb .= "<tr>";
            $tb .= "<td>rekening</td>";
            $tb .= "<td>debet</td>";
            $tb .= "<td>kredit</td>";
            $tb .= "</tr>";

            $arrNeracaNew = array();
            foreach ($arrNeraca as $arrSpec) {
                foreach ($this->inParams['static'] as $rkey => $rval) {
                    $arrSpec[$rkey] = $rval;
                }
//                arrPrintPink($arrSpec);
                $rl = New MdlNeraca();
                $rl->addData($arrSpec, $rl->getTableName());
                cekUngu($this->db->last_query());

                $tb .= "<tr>";
                $tb .= "<td style='text-align: left;'>" . $arrSpec['rekening'] . "</td>";
                $tb .= "<td style='text-align: right;'>" . number_format($arrSpec['debet'], '5', '.', ',') . "</td>";
                $tb .= "<td style='text-align: right;'>" . number_format($arrSpec['kredit'], '5', '.', ',') . "</td>";
                $tb .= "</tr>";

//                $arrNeracaNew[$i] = $arrSpec;
                $arrNeracaNew[$arrSpec['rekening']]['kategori'] = $arrSpec['kategori'];
                $arrNeracaNew[$arrSpec['rekening']]['rekening'] = $arrSpec['rekening'];

                if (!isset($arrNeracaNew[$arrSpec['rekening']]['debet'])) {
                    $arrNeracaNew[$arrSpec['rekening']]['debet'] = 0;
                }
                if (!isset($arrNeracaNew[$arrSpec['rekening']]['kredit'])) {
                    $arrNeracaNew[$arrSpec['rekening']]['kredit'] = 0;
                }

                $arrNeracaNew[$arrSpec['rekening']]['debet'] += $arrSpec['debet'];
                $arrNeracaNew[$arrSpec['rekening']]['kredit'] += $arrSpec['kredit'];

                $arrNeracaNew[$arrSpec['rekening']]['cabang_id'] = $arrSpec['cabang_id'];
                $arrNeracaNew[$arrSpec['rekening']]['cabang_nama'] = isset($arrSpec['cabang_nama']) ? $arrSpec['cabang_nama'] : "";
                //---------------
            }

            $tb .= "<tr>";
            $tb .= "<td style='text-align: left;'>-</td>";
            $tb .= "<td style='text-align: right;'>" . number_format($totals['debet'], '5', '.', ',') . "</td>";
            $tb .= "<td style='text-align: right;'>" . number_format($totals['kredit'], '5', '.', ',') . "</td>";
            $tb .= "</tr>";
            $tb .= "</table>";
            echo $tb;

//            if (round($totals['debet'],2) != round($totals['kredit'],2)) {
//                mati_disini("neraca tidak balance!! " . round($totals['debet'], 2) . " vs " . round($totals['kredit'], 2) . " SELISIH: " . $selisih);
//            }
//            elseif (round($totals['debet'],1) != round($totals['kredit'],1)) {
//                mati_disini("neraca tidak balance!! " . round($totals['debet'], 1) . " vs " . round($totals['kredit'], 1) . " SELISIH: " . $selisih);
//            }
            $selisih = $totals['debet'] - $totals['kredit'];
            cekUngu(__LINE__ . " selisih: $selisih ");
            if (round($totals['debet'], 1) != round($totals['kredit'], 1)) {
                if (round($totals['debet'], 0) != round($totals['kredit'], 0)) {
                    if (floor($totals['debet']) != floor($totals['kredit'])) {
                        $selisih = $selisih < 0 ? $selisih * -1 : $selisih;
                        if ($selisih > 5) {
                            cekHitam("selisih $selisih");
                            mati_disini(__LINE__ . " neraca tidak balance!! " . round($totals['debet'], 1) . " vs " . round($totals['kredit'], 1) . " SELISIH: " . $selisih);
                        }
                        else {
                            cekKuning("selisih $selisih");
                        }
                    }
                    else {
//                    mati_disini("neraca tidak balance!! " . round($totals['debet'], 1) . " vs " . round($totals['kredit'], 1) . " SELISIH: " . $selisih);
                    }
                }
            }
            else {

            }
//            if (floor($totals['debet']) != floor($totals['kredit'])) {
//                $selisih = number_format($totals['debet'] - $totals['kredit'], "10", ".", ",");
//                mati_disini("neraca tidak balance!! " . round($totals['debet'], 1) . " vs " . round($totals['kredit'], 1) . " SELISIH: " . $selisih);
//            }

//            return true;
            $this->outParams = $arrNeracaNew;
        }
        else {
            return false;
        }

    }

    public function execNoCut()
    {

        return $this->outParams;
    }


    public function pairNoCut_view($inParams, $neraca)
    {
        $this->load->model("ComRekening_cli");
        $this->load->helper("he_mass_table");
        $this->inParams = $inParams;

//        cekHitam("::: COM NERACA :::");


        $cr = New ComRekening_cli();
        $cr->setFilters(array());
        $cr->setFilters2(array());
//        $cr->addFilter("cabang_id='" . $this->inParams['static']['cabang_id'] . "'");
//        if (isset($this->filters)) {
//            $setFilters = $this->filters;
//            foreach ($this->filters as $kf => $vf) {
//                $cr->addFilter("$kf='$vf'");
//            }
//        }
//        if (isset($this->filters2)) {
//            $cr->setFilters2($this->filters2);
//        }
//        $tmp = $cr->fetchAllBalances2();

        $tmp = $neraca;


        $arrComJurnal = array();
        $arrComRekening = array();
        $arrRekening = array();
        $arrRekeningCat = array();
        $arrNeraca = array();
        $arrLR = array();
        $ldtSpec = array();
        $totals = array(
            "debet" => 0,
            "kredit" => 0,
        );

        if (sizeof($tmp) > 0) {
            $ldtCtr = 0;
            foreach ($tmp as $eSpec) {
                $rek = $eSpec['rekening'];
                $rekCategory = detectRekCategory($rek);

                $neracaSrcs = array("aktiva", "hutang", "modal", "lain-lain-kr");
                $lrSrcs = array("laba(rugi)");


                if (in_array($rekCategory, $neracaSrcs)) {
                    if (!isset($arrRekeningCat[$rekCategory])) {
                        $arrRekeningCat[$rekCategory] = 0;
                    }

                    $catNeraca = $rekCategory;

                    $arrNeraca[] = array(
                        "rek_id" => $eSpec["rek_id"],
                        "kategori" => $catNeraca,
                        "rekening" => $eSpec["rekening"],
                        "debet" => $eSpec["debet"],
                        "kredit" => $eSpec["kredit"],
                        "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                        "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                        "cabang_id" => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                        "dtime" => date("Y-m-d H:i:s"),
                        "author" => isset($this->session->login) ? $this->session->login['id'] : 0,
                        "keterangan" => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                        "fulldate" => date("Y-m-d"),
                        "tipe" => isset($this->inParams["static"]['tipe']) ? $this->inParams["static"]['tipe'] : "",
                    );
                    $totals['debet'] += $eSpec["debet"];
                    $totals['kredit'] += $eSpec["kredit"];
                }


                if (in_array($rekCategory, $lrSrcs)) {
                    $ldtCtr++;
//                    $rekening = "laba ditahan";
                    $rekening = "3020060";
                    $catNeraca = detectRekCategory($rekening);


                    if (!isset($ldtSpec[$ldtCtr])) {
                        $ldtSpec[$ldtCtr] = array(
                            "rek_id" => $eSpec["rek_id"],
                            "kategori" => $catNeraca,
                            "rekening" => $rekening,
                            "debet" => 0,
                            "kredit" => 0,
                            "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                            "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                            "cabang_id" => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                            "dtime" => date("Y-m-d H:i:s"),
                            "author" => isset($this->session->login) ? $this->session->login['id'] : 0,
                            "keterangan" => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                            "fulldate" => date("Y-m-d"),
                            "tipe" => isset($this->inParams["static"]['tipe']) ? $this->inParams["static"]['tipe'] : "",
                        );
                    }
                    $ldtSpec[$ldtCtr]['debet'] += $eSpec['debet'];
                    $ldtSpec[$ldtCtr]['kredit'] += $eSpec['kredit'];


                    if ($eSpec['debet'] > 0) {
                        $preNumber = detectRekByPosition($rek, $eSpec['debet'], "debet");
                    }
                    else {
                        $preNumber = detectRekByPosition($rek, $eSpec['kredit'], "kredit");
                    }


                    $key = $rek;
                    if (!isset($arrComJurnal[$ldtCtr]["loop"][$key])) {
                        $arrComJurnal[$ldtCtr]["comName"] = "";
                        $arrComJurnal[$ldtCtr]["loop"][$key] = 0;
                    }
                    if (!isset($arrComRekening[$ldtCtr]["loop"][$key])) {
                        $arrComRekening[$ldtCtr]["comName"] = "";
                        $arrComRekening[$ldtCtr]["loop"][$key] = 0;
                    }

                    $arrComJurnal[$ldtCtr]["comName"] = "Jurnal";
                    $arrComJurnal[$ldtCtr]["loop"][$key] = -($preNumber);


                    $arrComRekening[$ldtCtr]["comName"] = "Rekening_cli";
                    $arrComRekening[$ldtCtr]["loop"][$key] = -($preNumber);


                    if (isset($this->inParams["static"])) {
                        foreach ($this->inParams["static"] as $key_static => $value_static) {
                            $arrComJurnal[$ldtCtr]["static"][$key_static] = $value_static;
                            $arrComRekening[$ldtCtr]["static"][$key_static] = $value_static;
                        }
                    }

                }

            }
            //=============sini

            if (sizeof($ldtSpec)) {
                foreach ($ldtSpec as $ctr => $ldtSpecVal) {

                    $eSpec = $ldtSpecVal;
                    $rek = $eSpec['rekening'];
                    $arrNeraca[] = $ldtSpecVal;
                    $totals['debet'] += $eSpec["debet"];
                    $totals['kredit'] += $eSpec["kredit"];


                    if ($eSpec['debet'] > 0) {
                        $preNumber = detectRekByPosition($rek, $eSpec['debet'], "debet");
                    }
                    else {
                        $preNumber = detectRekByPosition($rek, $eSpec['kredit'], "kredit");
                    }
//                    $arrComJurnal[$ctr]["loop"]["laba ditahan"] = ($preNumber);
//                    $arrComRekening[$ctr]["loop"]["laba ditahan"] = ($preNumber);
                    $arrComJurnal[$ctr]["loop"]["3020060"] = ($preNumber);
                    $arrComRekening[$ctr]["loop"]["3020060"] = ($preNumber);
                }

            }

            //=============EO sini
//            if (sizeof($arrComJurnal) > 0) {
//                foreach ($arrComJurnal as $arrComJurnalSpec) {
//
//                    $model = "Com" . $arrComJurnalSpec["comName"];
//                    $this->load->model($model);
//                    $c = New $model;
//                    $c->pair($arrComJurnalSpec);
//                    $this->outParams[] = $c->exec();
//                }
//            }

            if (sizeof($arrComRekening) > 0) {
                foreach ($arrComRekening as $arrComRekeningSpec) {
                    $loopParams = isset($arrComRekeningSpec['loop']) ? $arrComRekeningSpec['loop'] : array();
                    if (sizeof($loopParams) > 0) {
                        foreach ($loopParams as $key => $val) {
                            if ($val == 0) {
                                unset($arrComRekeningSpec['loop'][$key]);
                            }
                        }
                    }


//                    $model = "Com" . $arrComRekeningSpec["comName"];
//                    $this->load->model($model);
//                    $c = New $model;
//                    $c->setFilters(array());
//                    $c->setFilters($setFilters);
//                    $c->pair($arrComRekeningSpec);
//                    $this->outParams[] = $c->exec();
                }
            }
            else {
                cekKuning(":: TIDAK ADA arrRekening ::");
            }
        }


        if (sizeof($arrNeraca) > 0) {
            $this->load->model("Mdls/MdlNeraca");

//            cekUngu(":: cetak NERACA ::");
            $tb = "<table rules='all' style='border: 1px solid black;'>";
            $tb .= "<tr>";
            $tb .= "<td>rekening</td>";
            $tb .= "<td>debet</td>";
            $tb .= "<td>kredit</td>";
            $tb .= "</tr>";

            $arrNeracaNew = array();
            foreach ($arrNeraca as $i => $arrSpec) {
                foreach ($this->inParams['static'] as $rkey => $rval) {
                    $arrSpec[$rkey] = $rval;
                }
                $arrNeracaNew[$i] = $arrSpec;


//                $rl = New MdlNeraca();
//                $rl->addData($arrSpec, $rl->getTableName());
                //cekUngu($this->db->last_query());

                $tb .= "<tr>";
                $tb .= "<td style='text-align: left;'>" . $arrSpec['rekening'] . "</td>";
                $tb .= "<td style='text-align: right;'>" . number_format($arrSpec['debet'], '5', '.', ',') . "</td>";
                $tb .= "<td style='text-align: right;'>" . number_format($arrSpec['kredit'], '5', '.', ',') . "</td>";
                $tb .= "</tr>";
            }

            $tb .= "<tr>";
            $tb .= "<td style='text-align: left;'>-</td>";
            $tb .= "<td style='text-align: right;'>" . number_format($totals['debet'], '5', '.', ',') . "</td>";
            $tb .= "<td style='text-align: right;'>" . number_format($totals['kredit'], '5', '.', ',') . "</td>";
            $tb .= "</tr>";
            $tb .= "</table>";
//            echo "<br> " . $tb;

//            if (round($totals['debet'], 1) != round($totals['kredit'], 1)) {
//                $selisih = number_format($totals['debet'] - $totals['kredit'], "10", ".", ",");
//                mati_disini("neraca tidak balance!! " . round($totals['debet'], 1) . " vs " . round($totals['kredit'], 1) . " SELISIH: " . $selisih);
//                cekHitam("neraca tidak balance!! " . round($totals['debet'], 1) . " vs " . round($totals['kredit'], 1) . " SELISIH: " . $selisih);
////                die(lgShowAlert("neraca tidak balance! " . $totals['debet'] . " vs " . $totals['kredit']));
//            }

            $this->outParams = $arrNeracaNew;
        }
        else {
            $this->outParams = array();
        }

    }

    public function execNoCut_view()
    {

        return $this->outParams;
    }
}