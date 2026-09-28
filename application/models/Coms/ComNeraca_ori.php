<?php


class ComNeraca extends MdlMother
{

    protected $filters = array();
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

    //  endregion setter, getter

    public function pair($inParams)
    {
        $this->load->model("ComRekening");
        $this->load->helper("he_mass_table");
        $this->inParams = $inParams;


        $cr = New ComRekening();
        $cr->addFilter("cabang_id='" . $this->inParams['static']['cabang_id'] . "'");
        $tmp = $cr->fetchAllBalances();
        cekBiru("cetak rekening, setelah rugilaba");
        arrPrint($tmp);
//mati_disini();

        $arrRekening = array();
        $arrRekeningCat = array();
        $arrNeraca = array();
        $arrLR = array();
        $ldtSpec = array();
        $totals = array(
            "debet"  => 0,
            "kredit" => 0,
        );


        if (sizeof($tmp) > 0) {
            $ldtCtr = 0;
            foreach ($tmp as $eSpec) {
                $rek = $eSpec['rekening'];
                $rekCategory = detectRekCategory($rek);

                $neracaSrcs = array("aktiva", "hutang", "modal");
                $lrSrcs = array("laba(rugi)");


                if (in_array($rekCategory, $neracaSrcs)) {
                    if (!isset($arrRekeningCat[$rekCategory])) {
                        $arrRekeningCat[$rekCategory] = 0;
                    }

                    $catNeraca = $rekCategory;

//                    $rekReplacers=array(
//                        "rugilaba"=>"laba ditahan"
//                    );
//                    foreach($rekReplacers as $asal=>$menjadi){
//                        $eSpec["rekening"]=str_replace("$asal","$menjadi",$eSpec["rekening"]);
//                    }
//
//                    $rekCatReplacers=array(
//                        "laba(rugi)"=>"modal"
//                    );
//                    foreach($rekCatReplacers as $asal=>$menjadi){
//                        $catNeraca=str_replace("$asal","$menjadi",$catNeraca);
//                    }
//
                    $arrNeraca[] = array(
                        "kategori"     => $catNeraca,
                        "rekening"     => $eSpec["rekening"],
                        "debet"        => $eSpec["debet"],
                        "kredit"       => $eSpec["kredit"],
                        "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                        "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                        "cabang_id"    => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                        "dtime"        => date("Y-m-d H:i:s"),
                        "author"       => $this->session->login['id'],
                        "keterangan"   => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                        "fulldate"     => date("Y-m-d"),
                    );
                    $totals['debet'] += $eSpec["debet"];
                    $totals['kredit'] += $eSpec["kredit"];
                }


                if (in_array($rekCategory, $lrSrcs)) {
                    cekbiru("member of LR, namanya $rek $rekCategory " . __LINE__);
                    cekbiru("mengisi laba ditahan dengan nilai D/K " . $eSpec['debet'] . "/" . $eSpec['kredit']);

                    $ldtCtr++;

                    $rekening = "laba ditahan";
                    $catNeraca = detectRekCategory($rekening);


                    if (!isset($ldtSpec[$ldtCtr])) {
                        $ldtSpec[$ldtCtr] = array(
                            "kategori"     => $catNeraca,
                            "rekening"     => $rekening,
                            "debet"        => 0,
                            "kredit"       => 0,
                            "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                            "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                            "cabang_id"    => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                            "dtime"        => date("Y-m-d H:i:s"),
                            "author"       => $this->session->login['id'],
                            "keterangan"   => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                            "fulldate"     => date("Y-m-d"),
                        );
                    }
                    $ldtSpec[$ldtCtr]['debet'] += $eSpec['debet'];
                    $ldtSpec[$ldtCtr]['kredit'] += $eSpec['kredit'];


                    if ($eSpec['debet'] > 0) {
                        $preNumber = detectRekByPosition($rek, $eSpec['debet'], "debet");
                    } else {
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
//                    $arrComJurnal["loop"]["laba ditahan"] = -($preNumber);

                    $arrComRekening[$ldtCtr]["comName"] = "Rekening";
                    $arrComRekening[$ldtCtr]["loop"][$key] = -($preNumber);
//                    $arrComRekening["loop"]["laba ditahan"] = -($preNumber);

                    if (isset($this->inParams["static"])) {
                        foreach ($this->inParams["static"] as $key_static => $value_static) {
                            $arrComJurnal[$ldtCtr]["static"][$key_static] = $value_static;
                            $arrComRekening[$ldtCtr]["static"][$key_static] = $value_static;
                        }
                    }

                }

            }
            //=============sini

            cekmerah("KDTSPEC" . __LINE__);
            arrprint($ldtSpec);
//            cekmerah("cetak comJurnal" . __LINE__);
//            arrPrint($arrComJurnal);
//            mati_disini();

            if (sizeof($ldtSpec)) {
                foreach ($ldtSpec as $ctr => $ldtSpecVal) {

                    $eSpec = $ldtSpecVal;
                    $arrNeraca[] = $ldtSpecVal;
                    $totals['debet'] += $eSpec["debet"];
                    $totals['kredit'] += $eSpec["kredit"];


                    if ($eSpec['debet'] > 0) {
                        $preNumber = detectRekByPosition($rek, $eSpec['debet'], "debet");
                    } else {
                        $preNumber = detectRekByPosition($rek, $eSpec['kredit'], "kredit");
                    }
                    $arrComJurnal[$ctr]["loop"]["laba ditahan"] = -($preNumber);
                    $arrComRekening[$ctr]["loop"]["laba ditahan"] = -($preNumber);
                }

            }
            cekmerah("cetak comJurnal" . __LINE__);
            arrPrint($arrComJurnal);
//            mati_disini();

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

                    $model = "Com" . $arrComRekeningSpec["comName"];
                    $this->load->model($model);
                    $c = New $model;
                    $c->pair($arrComRekeningSpec);
                    $this->outParams[] = $c->exec();
                }
            }
        }
        cekBiru("cetak array NERACA...");
        arrPrint($arrNeraca);
//        mati_disini();

        if (sizeof($arrNeraca) > 0) {
            $this->load->model("Mdls/MdlNeraca");

            foreach ($arrNeraca as $arrSpec) {
                $rl = New MdlNeraca();
                $rl->addData($arrSpec, $rl->getTableName());
                cekMerah($this->db->last_query());
            }

            if (round($totals['debet'], 2) != round($totals['kredit'], 2)) {
                die(lgShowAlert("neraca tidak balance! " . $totals['debet'] . " vs " . $totals['kredit']));
            }

            return true;
        } else {
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
        } else {
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


}