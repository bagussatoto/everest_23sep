<?php


class ComRugiLaba extends MdlMother
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
        $this->load->model("Coms/ComRekening");
        $this->load->helper("he_mass_table");
        $this->inParams = $inParams;


        $cr = New ComRekening();
        $cr->addFilter("cabang_id='" . $this->inParams['static']['cabang_id'] . "'");
        $tmp = $cr->fetchAllBalances();
//cekHitam("RL:: " . $this->db->last_query());
//arrPrint($tmp);

        $arrRekening = array();
        $arrRekeningCat = array(
            "penghasilan"           => 0,
            "biaya"                 => 0,
            "penghasilan lain lain" => 0,
            "biaya lain lain"       => 0,
        );
        $arrRugiLaba = array();
        $arrMainComRekening = array();
        $arrMainComRekeningCat = array(
            "penghasilan"           => 0,
            "biaya"                 => 0,
            "penghasilan lain lain" => 0,
            "biaya lain lain"       => 0,
        );
        $totals = array(
            "debet"  => 0,
            "kredit" => 0,
        );
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $eSpec) {
                $rek = $eSpec['rekening'];
                $debet = $eSpec['debet'];
                $kredit = $eSpec['kredit'];
                $rekCategory = detectRekCategory($rek);

                if (($rekCategory == "penghasilan") || ($rekCategory == "biaya")) {
                    if (!isset($arrRekeningCat[$rekCategory])) {
                        $arrRekeningCat[$rekCategory] = 0;
                    }
                    if (!isset($arrMainComRekeningCat[$rekCategory])) {
                        $arrMainComRekeningCat[$rekCategory] = 0;
                    }


                    $preValue = $this->cekPreValue($rek, $this->inParams['static']['cabang_id']);


                    if ($preValue['debet'] > 0) {
                        $preNumber = detectRekByPosition($rek, $preValue['debet'], "debet");
                    } else {
                        $preNumber = detectRekByPosition($rek, $preValue['kredit'], "kredit");
                    }

                    if ($eSpec['debet'] > 0) {
                        $currentNumber = detectRekByPosition($rek, $eSpec['debet'], "debet");
                    } else {
                        $currentNumber = detectRekByPosition($rek, $eSpec['kredit'], "kredit");
                    }
                    $afterNumber = $preNumber + $currentNumber;
                    $arrRekening[$rek] = $afterNumber;
                    $arrRekeningCat[$rekCategory] += $afterNumber;

                    $arrMainComRekening[$rek] = $currentNumber;
                    $arrMainComRekeningCat[$rekCategory] += $currentNumber;

                    $afterPosition = detectRekPosition($rek, $afterNumber);
                    switch ($afterPosition) {
                        case "debet":
                            $afterDebet = abs($afterNumber);
                            $afterKredit = 0;
                            break;
                        case "kredit":
                            $afterDebet = 0;
                            $afterKredit = abs($afterNumber);
                            break;
                        default:
                            $afterDebet = 0;
                            $afterKredit = 0;
                            break;
                    }

                    if (isset($this->inParams["static"])) {
                        foreach ($this->inParams["static"] as $key_static => $value_static) {
                            $eSpec[$key_static] = $value_static;
                        }
                    }

                    $arrRugiLaba[] = array(
                        "kategori"     => $rekCategory,
                        "rekening"     => $eSpec["rekening"],
                        "debet"        => $afterDebet,
                        "kredit"       => $afterKredit,
                        //                        "debet" => $eSpec["debet"] + $preValue['debet'],
                        //                        "kredit" => $eSpec["kredit"] + $preValue['kredit'],
                        "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                        "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                        "cabang_id"    => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                        "dtime"        => date("Y-m-d H:i:s"),
                        "author"       => $this->session->login['id'],
                        "keterangan"   => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                        "fulldate"     => date("Y-m-d"),
                    );

                    $totals['debet'] += ($eSpec["debet"] + $preValue["debet"]);
                    $totals['kredit'] += ($eSpec["kredit"] + $preValue["kredit"]);
                    cekLime($rekCategory);

                }

                if (($rekCategory == "penghasilan lain lain") || ($rekCategory == "biaya lain lain")) {
                    cekLime($rekCategory);
                    if (!isset($arrRekeningCat[$rekCategory])) {
                        $arrRekeningCat[$rekCategory] = 0;
                    }
                    if (!isset($arrMainComRekeningCat[$rekCategory])) {
                        $arrMainComRekeningCat[$rekCategory] = 0;
                    }


                    $preValue = $this->cekPreValue($rek, $this->inParams['static']['cabang_id']);


                    if ($preValue['debet'] > 0) {
                        $preNumber = detectRekByPosition($rek, $preValue['debet'], "debet");
                    } else {
                        $preNumber = detectRekByPosition($rek, $preValue['kredit'], "kredit");
                    }

                    if ($eSpec['debet'] > 0) {
                        $currentNumber = detectRekByPosition($rek, $eSpec['debet'], "debet");
                    } else {
                        $currentNumber = detectRekByPosition($rek, $eSpec['kredit'], "kredit");
                    }
                    $afterNumber = $preNumber + $currentNumber;
                    $arrRekening[$rek] = $afterNumber;
                    $arrRekeningCat[$rekCategory] += $afterNumber;

                    $arrMainComRekening[$rek] = $currentNumber;
                    $arrMainComRekeningCat[$rekCategory] += $currentNumber;

                    $afterPosition = detectRekPosition($rek, $afterNumber);
                    switch ($afterPosition) {
                        case "debet":
                            $afterDebet = abs($afterNumber);
                            $afterKredit = 0;
                            break;
                        case "kredit":
                            $afterDebet = 0;
                            $afterKredit = abs($afterNumber);
                            break;
                        default:
                            $afterDebet = 0;
                            $afterKredit = 0;
                            break;
                    }

                    if (isset($this->inParams["static"])) {
                        foreach ($this->inParams["static"] as $key_static => $value_static) {
                            $eSpec[$key_static] = $value_static;
                        }
                    }

                    $arrRugiLaba[] = array(
                        "kategori"     => $rekCategory,
                        "rekening"     => $eSpec["rekening"],
                        "debet"        => $afterDebet,
                        "kredit"       => $afterKredit,
                        //                        "debet" => $eSpec["debet"] + $preValue['debet'],
                        //                        "kredit" => $eSpec["kredit"] + $preValue['kredit'],
                        "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                        "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                        "cabang_id"    => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                        "dtime"        => date("Y-m-d H:i:s"),
                        "author"       => $this->session->login['id'],
                        "keterangan"   => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                        "fulldate"     => date("Y-m-d"),
                    );

                    $totals['debet'] += ($eSpec["debet"] + $preValue["debet"]);
                    $totals['kredit'] += ($eSpec["kredit"] + $preValue["kredit"]);

                }
            }

            if (sizeof($arrRekeningCat) > 0) {
                $arrRekening["rugilaba"] = $arrRekeningCat["penghasilan"] - $arrRekeningCat["biaya"];
                $arrRekening["rugilaba lain lain"] = $arrRekeningCat["penghasilan lain lain"] - $arrRekeningCat["biaya lain lain"];
            } else {
                $arrRekening["rugilaba"] = 0;
                $arrRekening["rugilaba lain lain"] = 0;
            }


            if (sizeof($arrMainComRekening) > 0) {
                $arrMainComRekening["rugilaba"] = $arrMainComRekeningCat["penghasilan"] - $arrMainComRekeningCat["biaya"];
                $arrMainComRekening["rugilaba lain lain"] = $arrMainComRekeningCat["penghasilan lain lain"] - $arrMainComRekeningCat["biaya lain lain"];
            } else {
                $arrMainComRekening["rugilaba"] = 0;
                $arrMainComRekening["rugilaba lain lain"] = 0;
            }
//arrPrint($arrMainComRekening);
//            die("hoop rl dilebarin broo");
            switch (detectRekPosition("rugilaba", $arrRekening["rugilaba"])) {
                case "debet":
                    $arrRugiLaba[] = array(
                        "kategori"     => "rugilaba",
                        "rekening"     => "rugilaba",
                        "debet"        => abs($arrRekening["rugilaba"]),
                        "kredit"       => 0,
                        "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                        "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                        "cabang_id"    => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                        "dtime"        => date("Y-m-d H:i:s"),
                        "author"       => $this->session->login['id'],
                        "keterangan"   => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                        "fulldate"     => date("Y-m-d"),
                    );
                    $totals['debet'] += abs($arrRekening["rugilaba"]);

                    break;
                case "kredit":
                    $arrRugiLaba[] = array(
                        "kategori"     => "rugilaba",
                        "rekening"     => "rugilaba",
                        "kredit"       => abs($arrRekening["rugilaba"]),
                        "debet"        => 0,
                        "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                        "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                        "cabang_id"    => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                        "dtime"        => date("Y-m-d H:i:s"),
                        "author"       => $this->session->login['id'],
                        "keterangan"   => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                        "fulldate"     => date("Y-m-d"),
                    );
                    $totals['kredit'] += abs($arrRekening["rugilaba"]);
                    break;
            }
            cekhitam(detectRekPosition("rugilaba lain lain", $arrRekening["rugilaba lain lain"]) . " line " . __LINE__);
            switch (detectRekPosition("rugilaba lain lain", $arrRekening["rugilaba lain lain"])) {

                case "debet":
                    $arrRugiLaba[] = array(
                        "kategori"     => "rugilaba lain lain",
                        "rekening"     => "rugilaba lain lain",
                        "debet"        => abs($arrRekening["rugilaba lain lain"]),
                        "kredit"       => 0,
                        "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                        "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                        "cabang_id"    => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                        "dtime"        => date("Y-m-d H:i:s"),
                        "author"       => $this->session->login['id'],
                        "keterangan"   => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                        "fulldate"     => date("Y-m-d"),
                    );
                    $totals['debet'] += abs($arrRekening["rugilaba lain lain"]);

                    break;
                case "kredit":
                    $arrRugiLaba[] = array(
                        "kategori"     => "rugilaba lain lain",
                        "rekening"     => "rugilaba lain lain",
                        "kredit"       => abs($arrRekening["rugilaba lain lain"]),
                        "debet"        => 0,
                        "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                        "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                        "cabang_id"    => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                        "dtime"        => date("Y-m-d H:i:s"),
                        "author"       => $this->session->login['id'],
                        "keterangan"   => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                        "fulldate"     => date("Y-m-d"),
                    );
                    $totals['kredit'] += abs($arrRekening["rugilaba lain lain"]);
                    break;
            }
        }

        cekmerah("ARRRUGILABA " . __LINE__);
        arrprint($arrRugiLaba);
        arrprint($totals);
        if (sizeof($arrRugiLaba) > 0) {
            $this->load->model("Mdls/MdlRugilaba");
            foreach ($arrRugiLaba as $arrSpec) {
                $rl = New MdlRugilaba();
                $rl->addData($arrSpec, $rl->getTableName());

            }
        }


        cekmerah("arrREKENING, line " . __LINE__);
        arrprint($arrRekening);
        if (sizeof($arrRekening) > 0) {
            foreach ($arrRekening as $key => $value) {

                if (!isset($arrComJurnal["loop"][$key])) {
                    $arrComJurnal["comName"] = "";
                    $arrComJurnal["loop"][$key] = 0;
                }

                if (!isset($arrComRekening["loop"][$key])) {
                    $arrComRekening["comName"] = "";
                    $arrComRekening["loop"][$key] = 0;
                }

                $arrComJurnal["comName"] = "Jurnal";
                $arrComJurnal["loop"][$key] = isset($arrMainComRekening[$key]) ? -($arrMainComRekening[$key]) : 0;

                $arrComRekening["comName"] = "Rekening";
                $arrComRekening["loop"][$key] = isset($arrMainComRekening[$key]) ? -($arrMainComRekening[$key]) : 0;

                if (isset($this->inParams["static"])) {
                    foreach ($this->inParams["static"] as $key_static => $value_static) {
                        $arrComJurnal["static"][$key_static] = $value_static;
                        $arrComRekening["static"][$key_static] = $value_static;
                    }
                }
            }

            if (sizeof($arrComJurnal) > 0) {

                $model = "Com" . $arrComJurnal["comName"];
                $this->load->model("Coms/" . $model);
                $c = New $model;
                $c->pair($arrComJurnal);
                $this->outParams[] = $c->exec();

            }


            if (sizeof($arrComRekening) > 0) {

                $model = "Com" . $arrComRekening["comName"];
                $this->load->model("Coms/" . $model);
                $c = New $model;
                $c->pair($arrComRekening);
                $this->outParams[] = $c->exec();

            }

            if (round($totals['debet'], 2) != round($totals['kredit'], 2)) {
                $selisih = $totals['debet'] - $totals['kredit'];
                $msg = "rugilaba tidak balance! " . $totals['debet'] . " vs " . $totals['kredit'] . ". ";
                $msg .= "selisih : $selisih";
                die(lgShowAlert($msg));
            }


//            $cr = New ComRekening();
//            $cr->addFilter("cabang_id='" . $this->inParams['static']['cabang_id'] . "'");
//            $tmp = $cr->fetchAllBalances();
//
//            cekBiru("cetak rekening, setelah rugilaba");
//            arrPrint($tmp);
//            cekMerah("RUGI LABA DONE");
//            mati_disini();
            if (sizeof($this->outParams) > 0) {
                return true;
            } else {
                return false;
            }

        }
    }

    private function cekPreValue($rek, $cabang_id)
    {
        $this->load->model("Mdls/MdlRugilaba");
        $rl = New MdlRugilaba();


        $this->filters = array();
        $this->addFilter("rekening='$rek'");
        $this->addFilter("cabang_id='$cabang_id'");
        $criteria = array();
        if (sizeof($this->filters) > 0) {
            $fCnt = 0;
            $criteria = array();
            foreach ($this->filters as $f) {
                $fCnt++;
                $tmp = explode("=", $f);
                if (sizeof($tmp) > 1) { //==berarti pakai tanda samadengan =
                    $criteria[$tmp[0]] = trim($tmp[1], "'");
                } else {
                    $tmp = explode("<>", $f);
                    if (sizeof($tmp) > 1) { //==berarti pakai tanda tidak sama dengan <>

                        $criteria[$tmp[0] . "!="] = trim($tmp[1], "'");
                    }
                }
            }
        }

        //  region mengambil saldo dari tabel rugilaba
        $this->db->limit(1);
        $this->db->order_by('id', 'DESC');
        $this->db->where($criteria);
        $tmp = $this->db->get($rl->getTableName())->result();
        cekMerah($this->db->last_query());
        if (sizeof($tmp) > 0) {
            // bila count($tmp) > 0, maka ambil saldo periode sendiri, dan mode update
            foreach ($tmp as $row) {
                $result = array(
                    "id"       => $row->id,
                    "rekening" => $row->rekening,
                    "debet"    => $row->debet,
                    "kredit"   => $row->kredit,
                );
            }
        } else {
            $result = array(
                "debet"  => 0,
                "kredit" => 0,
            );
        }
        //  endregion mengambil saldo dari rek_cache

        return $result;
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