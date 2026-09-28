<?php


class ComRugiLaba_cli extends MdlMother
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
    private $catException = array();


    public function __construct()
    {
        $this->tableName = "_rek_master_cache";
        $this->tableName_master = array(
            "mutasi" => "_rek_master",
            //            "cache" => "_rek_master_cache",
        );

        $this->catException = array(
            "penghasilan",
            "penghasilan lain lain",
            "biaya lain lain",
            "biaya",
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

    public function getPeriode2()
    {
        return $this->periode2;
    }

    public function setPeriode2($periode2)
    {
        $this->periode2 = $periode2;
    }

    public function getFilters2()
    {
        return $this->filters2;
    }

    public function setFilters2($filters)
    {
        $this->filters2 = $filters;
    }

    public function getCatException()
    {
        return $this->catException;
    }

    public function setCatException($catException)
    {
        $this->catException = $catException;
    }

    //  endregion setter, getter

    public function pair($inParams)
    {
        $this->load->model("Coms/ComRekening_cli");
        $this->load->helper("he_mass_table");
        $this->inParams = $inParams;
        $setFilters = array();

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
        cekKuning($this->db->last_query());
        //arrPrint($tmp);
        //arrPrint($this->filters);
        //arrPrint($this->filters2);
        //mati_disini();

        $arrRekening = array();
        $arrRekeningCat = array(
            "penghasilan" => 0,
            "biaya" => 0,
            "penghasilan lain lain" => 0,
            "biaya lain lain" => 0,
        );
        $arrRugiLaba = array();
        $arrMainComRekening = array();
        $arrMainComRekeningCat = array(
            "penghasilan" => 0,
            "biaya" => 0,
            "penghasilan lain lain" => 0,
            "biaya lain lain" => 0,
        );
        $totals = array(
            "debet" => 0,
            "kredit" => 0,
        );
        $totalss = array(
            "debet" => 0,
            "kredit" => 0,
        );
        if (sizeof($tmp) > 0) {

            $tb = "<table rules='all' style='border: 1px solid black;'>";
            $tb .= "<tr>";
            $tb .= "<td>NO.</td>";
            $tb .= "<td>rekening LAJUR</td>";
            $tb .= "<td>debet</td>";
            $tb .= "<td>kredit</td>";
            $tb .= "</tr>";

            $no = 0;
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


                    //                    $preValue = $this->cekPreValue($rek, $this->inParams['static']['cabang_id']);
                    $preValue = $this->cekPreValue($rek, $this->inParams['static']['cabang_id'], $eSpec['periode']);


                    if ($preValue['debet'] > 0) {
                        $preNumber = detectRekByPosition($rek, $preValue['debet'], "debet");
                    }
                    else {
                        $preNumber = detectRekByPosition($rek, $preValue['kredit'], "kredit");
                    }

                    if ($eSpec['debet'] > 0) {
                        $currentNumber = detectRekByPosition($rek, $eSpec['debet'], "debet");
                    }
                    else {
                        $currentNumber = detectRekByPosition($rek, $eSpec['kredit'], "kredit");
                    }

                    //                    $afterNumber = $preNumber + $currentNumber;
                    $afterNumber = $currentNumber;
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
                        "rek_id" => $eSpec["rek_id"],
                        "kategori" => $rekCategory,
                        "rekening" => $eSpec["rekening"],
                        "debet" => $afterDebet,
                        "kredit" => $afterKredit,

                        "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                        "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                        "cabang_id" => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                        "dtime" => date("Y-m-d H:i:s"),
                        "author" => isset($this->session->login) ? $this->session->login['id'] : 0,
                        "keterangan" => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                        "fulldate" => date("Y-m-d"),
                    );

                    //                    $totals['debet'] += ($eSpec["debet"] + $preValue["debet"]);
                    //                    $totals['kredit'] += ($eSpec["kredit"] + $preValue["kredit"]);
                    $totals['debet'] += $eSpec["debet"];
                    $totals['kredit'] += $eSpec["kredit"];

                }

                if (($rekCategory == "penghasilan lain lain") || ($rekCategory == "biaya lain lain")) {
                    //                    cekLime($rekCategory);
                    if (!isset($arrRekeningCat[$rekCategory])) {
                        $arrRekeningCat[$rekCategory] = 0;
                    }
                    if (!isset($arrMainComRekeningCat[$rekCategory])) {
                        $arrMainComRekeningCat[$rekCategory] = 0;
                    }

                    $preValue = $this->cekPreValue($rek, $this->inParams['static']['cabang_id']);

                    if ($preValue['debet'] > 0) {
                        $preNumber = detectRekByPosition($rek, $preValue['debet'], "debet");
                    }
                    else {
                        $preNumber = detectRekByPosition($rek, $preValue['kredit'], "kredit");
                    }

                    if ($eSpec['debet'] > 0) {
                        $currentNumber = detectRekByPosition($rek, $eSpec['debet'], "debet");
                    }
                    else {
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
                        "rek_id" => $eSpec["rek_id"],
                        "kategori" => $rekCategory,
                        "rekening" => $eSpec["rekening"],
                        "debet" => $afterDebet,
                        "kredit" => $afterKredit,
                        //                        "debet" => $eSpec["debet"] + $preValue['debet'],
                        //                        "kredit" => $eSpec["kredit"] + $preValue['kredit'],
                        "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                        "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                        "cabang_id" => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                        "dtime" => date("Y-m-d H:i:s"),
                        "author" => isset($this->session->login) ? $this->session->login['id'] : 0,
                        "keterangan" => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                        "fulldate" => date("Y-m-d"),
                    );

                    //                    $totals['debet'] += ($eSpec["debet"] + $preValue["debet"]);
                    //                    $totals['kredit'] += ($eSpec["kredit"] + $preValue["kredit"]);
                    $totals['debet'] += $eSpec["debet"];
                    $totals['kredit'] += $eSpec["kredit"];

                }


                $totalss['debet'] += $debet;
                $totalss['kredit'] += $kredit;

                $no++;
                $tb .= "<tr>";
                $tb .= "<td style='text-align: left;'>" . $no . "</td>";
                $tb .= "<td style='text-align: left;'>" . $rek . "</td>";
                $tb .= "<td style='text-align: right;'>" . number_format($debet, '5', '.', ',') . "</td>";
                $tb .= "<td style='text-align: right;'>" . number_format($kredit, '5', '.', ',') . "</td>";
                $tb .= "</tr>";

            }
            $selisihh = $totalss['debet'] - $totalss['kredit'];
            $tb .= "<tr>";
            $tb .= "<td style='text-align: left;'>-</td>";
            $tb .= "<td style='text-align: left;'>$selisihh</td>";
            $tb .= "<td style='text-align: right;'>" . number_format($totalss['debet'], '5', '.', ',') . "</td>";
            $tb .= "<td style='text-align: right;'>" . number_format($totalss['kredit'], '5', '.', ',') . "</td>";
            $tb .= "</tr>";
            $tb .= "</table>";
            echo $tb;


            if (sizeof($arrRekeningCat) > 0) {
                $arrRekening["9010"] = $arrRekeningCat["penghasilan"] - $arrRekeningCat["biaya"];
                $arrRekening["9020"] = $arrRekeningCat["penghasilan lain lain"] - $arrRekeningCat["biaya lain lain"];
            }
            else {
                $arrRekening["9010"] = 0;
                $arrRekening["9020"] = 0;
            }


            if (sizeof($arrMainComRekening) > 0) {
                $arrMainComRekening["9010"] = $arrMainComRekeningCat["penghasilan"] - $arrMainComRekeningCat["biaya"];
                $arrMainComRekening["9020"] = $arrMainComRekeningCat["penghasilan lain lain"] - $arrMainComRekeningCat["biaya lain lain"];
            }
            else {
                $arrMainComRekening["9010"] = 0;
                $arrMainComRekening["9020"] = 0;
            }


            switch (detectRekPosition("9010", $arrRekening["9010"])) {
                case "debet":
                    $arrRugiLaba[] = array(

                        "kategori" => "rugilaba",
                        "rekening" => "9010",
                        "debet" => abs($arrRekening["9010"]),
                        "kredit" => 0,
                        "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                        "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                        "cabang_id" => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                        "dtime" => date("Y-m-d H:i:s"),
                        "author" => isset($this->session->login) ? $this->session->login['id'] : 0,
                        "keterangan" => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                        "fulldate" => date("Y-m-d"),
                    );
                    $totals['debet'] += abs($arrRekening["9010"]);

                    break;
                case "kredit":
                    $arrRugiLaba[] = array(

                        "kategori" => "rugilaba",
                        "rekening" => "9010",
                        "kredit" => abs($arrRekening["9010"]),
                        "debet" => 0,
                        "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                        "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                        "cabang_id" => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                        "dtime" => date("Y-m-d H:i:s"),
                        "author" => isset($this->session->login) ? $this->session->login['id'] : 0,
                        "keterangan" => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                        "fulldate" => date("Y-m-d"),
                    );
                    $totals['kredit'] += abs($arrRekening["9010"]);
                    break;
            }

            switch (detectRekPosition("9020", $arrRekening["9020"])) {

                case "debet":
                    $arrRugiLaba[] = array(

                        "kategori" => "rugilaba lain lain",
                        "rekening" => "9020",
                        "debet" => abs($arrRekening["9020"]),
                        "kredit" => 0,
                        "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                        "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                        "cabang_id" => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                        "dtime" => date("Y-m-d H:i:s"),
                        "author" => isset($this->session->login) ? $this->session->login['id'] : 0,
                        "keterangan" => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                        "fulldate" => date("Y-m-d"),
                    );
                    $totals['debet'] += abs($arrRekening["9020"]);

                    break;
                case "kredit":
                    $arrRugiLaba[] = array(

                        "kategori" => "rugilaba lain lain",
                        "rekening" => "9020",
                        "kredit" => abs($arrRekening["9020"]),
                        "debet" => 0,
                        "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                        "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                        "cabang_id" => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                        "dtime" => date("Y-m-d H:i:s"),
                        "author" => isset($this->session->login) ? $this->session->login['id'] : "0",
                        "keterangan" => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                        "fulldate" => date("Y-m-d"),
                    );
                    $totals['kredit'] += abs($arrRekening["9020"]);
                    break;
            }
        }


        //mati_disini();
//        arrPrint($arrRugiLaba);
        if (sizeof($arrRugiLaba) > 0) {
            cekHitam(":: MENULIS RUGILABA KE TABEL " . __LINE__);


            $this->load->model("Mdls/MdlRugilaba");
            foreach ($arrRugiLaba as $arrSpec) {
                foreach ($this->inParams['static'] as $rkey => $rval) {

                    $arrSpec[$rkey] = $rval;
                }
//                arrPrint($arrSpec);
                $rl = New MdlRugilaba();
                $rl->addData($arrSpec, $rl->getTableName());
                //                cekUngu($this->db->last_query());
            }
        }


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

                $arrComRekening["comName"] = "Rekening_cli";
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

                $tb = "<table rules='all' style='border: 1px solid black;'>";
                $tb .= "<tr>";
                $tb .= "<td>rekening</td>";
                $tb .= "<td>debet</td>";
                $tb .= "<td>kredit</td>";
                $tb .= "</tr>";

                $loopParams = isset($arrComRekening['loop']) ? $arrComRekening['loop'] : array();
                if (sizeof($loopParams) > 0) {
                    foreach ($loopParams as $key => $val) {
                        if ($val == 0) {
                            unset($arrComRekening['loop'][$key]);
                        }
                    }
                }

                $model = "Com" . $arrComRekening["comName"];
                cekHitam("arrComRekening: $model");
                $this->load->model("Coms/" . $model);
                $c = New $model;
                $c->setFilters(array());
                $c->setFilters($setFilters);
                $c->pair($arrComRekening);
                $this->outParams[] = $c->exec();


                //arrPrint($arrComRekening);
                //arrPrint($setFilters);
                //mati_disini();
                //

                foreach ($arrComRekening["loop"] as $rek => $val) {
                    if (!isset($debetVal)) {
                        $debetVal = 0;
                        $kreditVal = 0;
                    }
                    if (!isset($kreditVal)) {
                        $debetVal = 0;
                        $kreditVal = 0;
                    }

                    if (detectRekPosition($rek, $val) == "debet") {
                        $debetVal = abs($val);
                        $kreditVal = 0;
                    }
                    else {
                        $debetVal = 0;
                        $kreditVal = abs($val);
                    }


                    $tb .= "<tr>";
                    $tb .= "<td style='text-align: left;'>" . $rek . "</td>";
                    $tb .= "<td style='text-align: right;'>" . number_format($debetVal, '5', '.', ',') . "</td>";
                    $tb .= "<td style='text-align: right;'>" . number_format($kreditVal, '5', '.', ',') . "</td>";
                    $tb .= "</tr>";
                }


                $tb .= "<tr>";
                $tb .= "<td style='text-align: left;'>-</td>";
                $tb .= "<td style='text-align: right;'>" . number_format($totals['debet'], '5', '.', ',') . "</td>";
                $tb .= "<td style='text-align: right;'>" . number_format($totals['kredit'], '5', '.', ',') . "</td>";
                $tb .= "</tr>";
                $tb .= "</table>";
                echo $tb;
            }

            if (round($totals['debet'], 2) != round($totals['kredit'], 2)) {
                $selisih = $totals['debet'] - $totals['kredit'];
                $msg = "rugilaba tidak balance! " . $totals['debet'] . " vs " . $totals['kredit'] . ". ";
                $msg .= "selisih : $selisih";
                mati_disini(($msg));
                die(lgShowAlert($msg));
            }

            if (sizeof($this->outParams) > 0) {
                return true;
            }
            else {
                return false;
            }

        }
    }

    private function cekPreValue__($rek, $cabang_id)
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
                }
                else {
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
        //        cekMerah($this->db->last_query());
        if (sizeof($tmp) > 0) {
            // bila count($tmp) > 0, maka ambil saldo periode sendiri, dan mode update
            foreach ($tmp as $row) {
                $result = array(
                    "id" => $row->id,
                    "rekening" => $row->rekening,
                    "debet" => $row->debet,
                    "kredit" => $row->kredit,
                );
            }
        }
        else {
            $result = array(
                "debet" => 0,
                "kredit" => 0,
            );
        }
        //  endregion mengambil saldo dari rek_cache

        return $result;
    }

    private function cekPreValue($rek, $cabang_id, $periode)
    {

        //        $tgl = (sizeof($filterDate) && (isset($filterDate['tgl']))) ? $filterDate['tgl'] : date("d");
        //        $bln = (sizeof($filterDate) && (isset($filterDate['bln']))) ? $filterDate['bln'] : date("m");
        //        $thn = (sizeof($filterDate) && (isset($filterDate['thn']))) ? $filterDate['thn'] : date("Y");
        //
        $tgl = date("d");
        $bln = date("m");
        $thn = date("Y");


        $this->filters = array();

        switch ($periode) {
            case "harian":
                $this->addFilter("tgl='$tgl'");
                $this->addFilter("bln='$bln'");
                $this->addFilter("thn='$thn'");
                break;
            case "bulanan":
                $this->addFilter("bln='$bln'");
                $this->addFilter("thn='$thn'");
                break;
            case "tahunan":
                $this->addFilter("thn='$thn'");
                break;
            case "forever":
                break;
        }

        $this->addFilter("rekening='$rek'");
        $this->addFilter("cabang_id='$cabang_id'");
        $this->addFilter("periode='$periode'");

        $result = array();

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
            ->order_by("id", "DESC")
            ->get_compiled_select();

        $tmp = $this->db->query("{$query} FOR UPDATE")->row_array();


        if (sizeof($tmp) > 0) {

            $result = array(
                "id" => $tmp['id'],
                "debet" => $tmp['debet'],
                "kredit" => $tmp['kredit'],

            );
        }
        else {

            $rekCat = detectRekCategory($rek);
            if (!in_array($rekCat, $this->catException)) {

                // bila count($tmp) == 0, maka ambil saldo periode forever dan mode insert
                $periode = "forever";
                $this->filters = array();
                $this->addFilter("rekening='$rek'");
                $this->addFilter("cabang_id='$cabang_id'");
                $this->addFilter("periode='$periode'");

                switch ($periode) {
                    case "harian":
                        $this->addFilter("tgl='$tgl'");
                        $this->addFilter("bln='$bln'");
                        $this->addFilter("thn='$thn'");
                        break;
                    case "bulanan":
                        $this->addFilter("bln='$bln'");
                        $this->addFilter("thn='$thn'");
                        break;
                    case "tahunan":
                        $this->addFilter("thn='$thn'");
                        break;
                    case "forever":
                        break;
                }

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
                    ->order_by("id", "DESC")
                    ->get_compiled_select();

                $tmp = $this->db->query("{$query} FOR UPDATE")->row_array();


                if (sizeof($tmp) > 0) {

                    $result = array(
                        //                    "id"     => $tmp['id'],
                        "debet" => $tmp['debet'],
                        "kredit" => $tmp['kredit'],

                    );
                }
                else {
                    $result = array(
                        "debet" => 0,
                        "kredit" => 0,
                    );
                }
            }
            else {
                $this->filters = array();
                $this->addFilter("rekening='$rek'");
                $this->addFilter("cabang_id='$cabang_id'");
                $this->addFilter("periode='$periode'");
                switch ($periode) {
                    case "harian":
                        //                        $this->addFilter("tgl='$tgl'");
                        //                        $this->addFilter("bln='$bln'");
                        //                        $this->addFilter("thn='$thn'");
                        break;
                    case "bulanan":
                        //                        $this->addFilter("bln='$bln'");
                        //                        $this->addFilter("thn='$thn'");
                        break;
                    case "tahunan":
                        //                        $this->addFilter("thn='$thn'");
                        break;
                    case "forever":
                        break;
                }
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
                    ->order_by("id", "DESC")
                    ->get_compiled_select();

                $tmp = $this->db->query("{$query} FOR UPDATE")->row_array();
                //                cekHitam(":: " . $this->db->last_query());
                if (sizeof($tmp) > 0) {

                    $result = array(
                        //                    "id"     => $tmp['id'],
                        "debet" => $tmp['debet'],
                        "kredit" => $tmp['kredit'],

                    );
                }
                else {
                    $result = array(
                        "debet" => 0,
                        "kredit" => 0,
                    );
                }


            }

        }


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

    public function lookupMonth($yearMonth)
    {
        $dateLast_ex = explode("-", $yearMonth);
        $periode = "bulanan";
        $bulan = $dateLast_ex[1];
        $tahun = $dateLast_ex[0];
        $tbl = $this->tableName;
        $defaultDate = "$tahun-$bulan";
//        $wheres = array(
//            "periode" => $periode,
//            "rekening" => "rugilaba",
//            "thn" => $tahun,
//            "bln" => $bulan,
//        );
//        $this->db->where($wheres);
//        $vars = $this->db->get($tbl);

        $this->load->model("Mdls/MdlRugilaba");
        $rlTmp = new MdlRugilaba();
        $rlTmp->addFilter("periode='bulanan'");
        $vars = $rlTmp->fetchBalances($defaultDate);


        return $vars;
    }

    public function lookupYear($yearMonth)
    {
        $dateLast_ex = explode("-", $yearMonth);
        $periode = "tahunan";
//        $bulan = $dateLast_ex[1];
        $tahun = $dateLast_ex[0];
        $tbl = $this->tableName;
        $defaultDate = "$tahun";


//        $wheres = array(
//            "periode" => $periode,
//            "rekening" => "rugilaba",
//            "thn" => $tahun,
////            "bln"      => $bulan,
//        );
//        $this->db->where($wheres);
//        $vars = $this->db->get($tbl);

        $this->load->model("Mdls/MdlRugilaba");
        $rlTmp = new MdlRugilaba();
        $rlTmp->addFilter("periode='tahunan'");
        $vars = $rlTmp->fetchBalances($defaultDate);


        return $vars;
    }


    // =================================================================== //

    public function pairNoCut($inParams, $lajur)
    {
        $this->load->model("Coms/ComRekening_cli");
        $this->load->helper("he_mass_table");
        $this->inParams = $inParams;
        $setFilters = array();

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
//        $tmp = $cr->fetchAllBalances2();


        $tmp = $lajur;

        $arrRekening = array();
        $arrRekeningCat = array(
            "penghasilan" => 0,
            "biaya" => 0,
            "penghasilan lain lain" => 0,
            "biaya lain lain" => 0,
        );
        $arrRugiLaba = array();
        $arrMainComRekening = array();
        $arrMainComRekeningCat = array(
            "penghasilan" => 0,
            "biaya" => 0,
            "penghasilan lain lain" => 0,
            "biaya lain lain" => 0,
        );
        $totals = array(
            "debet" => 0,
            "kredit" => 0,
        );
        $totalss = array(
            "debet" => 0,
            "kredit" => 0,
        );
        if (sizeof($tmp) > 0) {

            $tb = "<table rules='all' style='border: 1px solid black;'>";
            $tb .= "<tr>";
            $tb .= "<td>rekening LAJUR</td>";
            $tb .= "<td>debet</td>";
            $tb .= "<td>kredit</td>";
            $tb .= "</tr>";


            foreach ($tmp as $eSpec) {
                $rek = $eSpec['rekening'];
                $debet = $eSpec['debet'];
                $kredit = $eSpec['kredit'];
                $rekCategory = detectRekCategory($rek);
//cekHere("$rekCategory, $rek");
                if (($rekCategory == "penghasilan") || ($rekCategory == "biaya")) {
                    if (!isset($arrRekeningCat[$rekCategory])) {
                        $arrRekeningCat[$rekCategory] = 0;
                    }
                    if (!isset($arrMainComRekeningCat[$rekCategory])) {
                        $arrMainComRekeningCat[$rekCategory] = 0;
                    }


                    //                    $preValue = $this->cekPreValue($rek, $this->inParams['static']['cabang_id']);
                    $preValue = $this->cekPreValue($rek, $this->inParams['static']['cabang_id'], $eSpec['periode']);


                    if ($preValue['debet'] > 0) {
                        $preNumber = detectRekByPosition($rek, $preValue['debet'], "debet");
                    }
                    else {
                        $preNumber = detectRekByPosition($rek, $preValue['kredit'], "kredit");
                    }

                    if ($eSpec['debet'] > 0) {
                        $currentNumber = detectRekByPosition($rek, $eSpec['debet'], "debet");
                    }
                    else {
                        $currentNumber = detectRekByPosition($rek, $eSpec['kredit'], "kredit");
                    }

                    //                    $afterNumber = $preNumber + $currentNumber;
                    $afterNumber = $currentNumber;
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
                        "rek_id" => $eSpec["rek_id"],
                        "kategori" => $rekCategory,
                        "rekening" => $eSpec["rekening"],
                        "debet" => $afterDebet,
                        "kredit" => $afterKredit,

                        "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                        "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                        "cabang_id" => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                        "dtime" => date("Y-m-d H:i:s"),
                        "author" => isset($this->session->login) ? $this->session->login['id'] : 0,
                        "keterangan" => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                        "fulldate" => date("Y-m-d"),
                    );

                    //                    $totals['debet'] += ($eSpec["debet"] + $preValue["debet"]);
                    //                    $totals['kredit'] += ($eSpec["kredit"] + $preValue["kredit"]);
                    $totals['debet'] += $eSpec["debet"];
                    $totals['kredit'] += $eSpec["kredit"];

                }

                if (($rekCategory == "penghasilan lain lain") || ($rekCategory == "biaya lain lain")) {
                    //                    cekLime($rekCategory);
                    if (!isset($arrRekeningCat[$rekCategory])) {
                        $arrRekeningCat[$rekCategory] = 0;
                    }
                    if (!isset($arrMainComRekeningCat[$rekCategory])) {
                        $arrMainComRekeningCat[$rekCategory] = 0;
                    }

                    $preValue = $this->cekPreValue($rek, $this->inParams['static']['cabang_id']);

                    if ($preValue['debet'] > 0) {
                        $preNumber = detectRekByPosition($rek, $preValue['debet'], "debet");
                    }
                    else {
                        $preNumber = detectRekByPosition($rek, $preValue['kredit'], "kredit");
                    }

                    if ($eSpec['debet'] > 0) {
                        $currentNumber = detectRekByPosition($rek, $eSpec['debet'], "debet");
                    }
                    else {
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
                        "rek_id" => $eSpec["rek_id"],
                        "kategori" => $rekCategory,
                        "rekening" => $eSpec["rekening"],
                        "debet" => $afterDebet,
                        "kredit" => $afterKredit,
                        //                        "debet" => $eSpec["debet"] + $preValue['debet'],
                        //                        "kredit" => $eSpec["kredit"] + $preValue['kredit'],
                        "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                        "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                        "cabang_id" => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                        "dtime" => date("Y-m-d H:i:s"),
                        "author" => isset($this->session->login) ? $this->session->login['id'] : 0,
                        "keterangan" => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                        "fulldate" => date("Y-m-d"),
                    );

                    //                    $totals['debet'] += ($eSpec["debet"] + $preValue["debet"]);
                    //                    $totals['kredit'] += ($eSpec["kredit"] + $preValue["kredit"]);
                    $totals['debet'] += $eSpec["debet"];
                    $totals['kredit'] += $eSpec["kredit"];

                }


                $totalss['debet'] += $debet;
                $totalss['kredit'] += $kredit;

                $tb .= "<tr>";
                $tb .= "<td style='text-align: left;'>" . $rek . "</td>";
                $tb .= "<td style='text-align: right;'>" . number_format($debet, '5', '.', ',') . "</td>";
                $tb .= "<td style='text-align: right;'>" . number_format($kredit, '5', '.', ',') . "</td>";
                $tb .= "</tr>";

            }
            $selisihh = $totalss['debet'] - $totalss['kredit'];
            $tb .= "<tr>";
            $tb .= "<td style='text-align: left;'>$selisihh</td>";
            $tb .= "<td style='text-align: right;'>" . number_format($totalss['debet'], '5', '.', ',') . "</td>";
            $tb .= "<td style='text-align: right;'>" . number_format($totalss['kredit'], '5', '.', ',') . "</td>";
            $tb .= "</tr>";
            $tb .= "</table>";
//            echo $tb;


            if (sizeof($arrRekeningCat) > 0) {
                $arrRekening["rugilaba"] = $arrRekeningCat["penghasilan"] - $arrRekeningCat["biaya"];
                $arrRekening["rugilaba lain lain"] = $arrRekeningCat["penghasilan lain lain"] - $arrRekeningCat["biaya lain lain"];
            }
            else {
                $arrRekening["rugilaba"] = 0;
                $arrRekening["rugilaba lain lain"] = 0;
            }


            if (sizeof($arrMainComRekening) > 0) {
                $arrMainComRekening["rugilaba"] = $arrMainComRekeningCat["penghasilan"] - $arrMainComRekeningCat["biaya"];
                $arrMainComRekening["rugilaba lain lain"] = $arrMainComRekeningCat["penghasilan lain lain"] - $arrMainComRekeningCat["biaya lain lain"];
            }
            else {
                $arrMainComRekening["rugilaba"] = 0;
                $arrMainComRekening["rugilaba lain lain"] = 0;
            }


            switch (detectRekPosition("rugilaba", $arrRekening["rugilaba"])) {
                case "debet":
                    $arrRugiLaba[] = array(

                        "kategori" => "rugilaba",
                        "rekening" => "rugilaba",
                        "debet" => abs($arrRekening["rugilaba"]),
                        "kredit" => 0,
                        "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                        "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                        "cabang_id" => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                        "dtime" => date("Y-m-d H:i:s"),
                        "author" => isset($this->session->login) ? $this->session->login['id'] : 0,
                        "keterangan" => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                        "fulldate" => date("Y-m-d"),
                    );
                    $totals['debet'] += abs($arrRekening["rugilaba"]);

                    break;
                case "kredit":
                    $arrRugiLaba[] = array(

                        "kategori" => "rugilaba",
                        "rekening" => "rugilaba",
                        "kredit" => abs($arrRekening["rugilaba"]),
                        "debet" => 0,
                        "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                        "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                        "cabang_id" => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                        "dtime" => date("Y-m-d H:i:s"),
                        "author" => isset($this->session->login) ? $this->session->login['id'] : 0,
                        "keterangan" => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                        "fulldate" => date("Y-m-d"),
                    );
                    $totals['kredit'] += abs($arrRekening["rugilaba"]);
                    break;
            }

            switch (detectRekPosition("rugilaba lain lain", $arrRekening["rugilaba lain lain"])) {

                case "debet":
                    $arrRugiLaba[] = array(

                        "kategori" => "rugilaba lain lain",
                        "rekening" => "rugilaba lain lain",
                        "debet" => abs($arrRekening["rugilaba lain lain"]),
                        "kredit" => 0,
                        "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                        "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                        "cabang_id" => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                        "dtime" => date("Y-m-d H:i:s"),
                        "author" => isset($this->session->login) ? $this->session->login['id'] : 0,
                        "keterangan" => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                        "fulldate" => date("Y-m-d"),
                    );
                    $totals['debet'] += abs($arrRekening["rugilaba lain lain"]);

                    break;
                case "kredit":
                    $arrRugiLaba[] = array(

                        "kategori" => "rugilaba lain lain",
                        "rekening" => "rugilaba lain lain",
                        "kredit" => abs($arrRekening["rugilaba lain lain"]),
                        "debet" => 0,
                        "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                        "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                        "cabang_id" => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                        "dtime" => date("Y-m-d H:i:s"),
                        "author" => isset($this->session->login) ? $this->session->login['id'] : "0",
                        "keterangan" => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                        "fulldate" => date("Y-m-d"),
                    );
                    $totals['kredit'] += abs($arrRekening["rugilaba lain lain"]);
                    break;
            }
        }


        //region menulis rugilaba ke tabel rugilaba
        if (sizeof($arrRugiLaba) > 0) {
//            cekHitam(":: MENULIS RUGILABA KE TABEL " . __LINE__);
            $this->load->model("Mdls/MdlRugilaba");
            foreach ($arrRugiLaba as $arrSpec) {
                foreach ($this->inParams['static'] as $rkey => $rval) {
                    $arrSpec[$rkey] = $rval;
                }
//                arrPrint($arrSpec);
                $rl = New MdlRugilaba();
                $rl->addData($arrSpec, $rl->getTableName());
                cekUngu($this->db->last_query());
            }
        }
        //endregion

//        if (sizeof($arrRekening) > 0) {
//            foreach ($arrRekening as $key => $value) {
//
//                if (!isset($arrComJurnal["loop"][$key])) {
//                    $arrComJurnal["comName"] = "";
//                    $arrComJurnal["loop"][$key] = 0;
//                }
//
//                if (!isset($arrComRekening["loop"][$key])) {
//                    $arrComRekening["comName"] = "";
//                    $arrComRekening["loop"][$key] = 0;
//                }
//
//                $arrComJurnal["comName"] = "Jurnal";
//                $arrComJurnal["loop"][$key] = isset($arrMainComRekening[$key]) ? -($arrMainComRekening[$key]) : 0;
//
//                $arrComRekening["comName"] = "Rekening_cli";
//                $arrComRekening["loop"][$key] = isset($arrMainComRekening[$key]) ? -($arrMainComRekening[$key]) : 0;
//
//                if (isset($this->inParams["static"])) {
//                    foreach ($this->inParams["static"] as $key_static => $value_static) {
//                        $arrComJurnal["static"][$key_static] = $value_static;
//                        $arrComRekening["static"][$key_static] = $value_static;
//                    }
//                }
//            }
//
//            if (sizeof($arrComJurnal) > 0) {
//
//                $model = "Com" . $arrComJurnal["comName"];
//                $this->load->model("Coms/" . $model);
//                $c = New $model;
//                $c->pair($arrComJurnal);
//                $this->outParams[] = $c->exec();
//
//            }
//
//            if (sizeof($arrComRekening) > 0) {
//
//                $tb = "<table rules='all' style='border: 1px solid black;'>";
//                $tb .= "<tr>";
//                $tb .= "<td>rekening</td>";
//                $tb .= "<td>debet</td>";
//                $tb .= "<td>kredit</td>";
//                $tb .= "</tr>";
//
//                $loopParams = isset($arrComRekening['loop']) ? $arrComRekening['loop'] : array();
//                if (sizeof($loopParams) > 0) {
//                    foreach ($loopParams as $key => $val) {
//                        if ($val == 0) {
//                            unset($arrComRekening['loop'][$key]);
//                        }
//                    }
//                }
//
//                $model = "Com" . $arrComRekening["comName"];
//                cekHitam("arrComRekening: $model");
//                $this->load->model("Coms/" . $model);
//                $c = New $model;
//                $c->setFilters(array());
//                $c->setFilters($setFilters);
//                $c->pair($arrComRekening);
//                $this->outParams[] = $c->exec();
//
//
//                //arrPrint($arrComRekening);
//                //arrPrint($setFilters);
//                //mati_disini();
//                //
//
//                foreach ($arrComRekening["loop"] as $rek => $val) {
//                    if (!isset($debetVal)) {
//                        $debetVal = 0;
//                        $kreditVal = 0;
//                    }
//                    if (!isset($kreditVal)) {
//                        $debetVal = 0;
//                        $kreditVal = 0;
//                    }
//
//                    if (detectRekPosition($rek, $val) == "debet") {
//                        $debetVal = abs($val);
//                        $kreditVal = 0;
//                    }
//                    else {
//                        $debetVal = 0;
//                        $kreditVal = abs($val);
//                    }
//
//
//                    $tb .= "<tr>";
//                    $tb .= "<td style='text-align: left;'>" . $rek . "</td>";
//                    $tb .= "<td style='text-align: right;'>" . number_format($debetVal, '5', '.', ',') . "</td>";
//                    $tb .= "<td style='text-align: right;'>" . number_format($kreditVal, '5', '.', ',') . "</td>";
//                    $tb .= "</tr>";
//                }
//
//
//                $tb .= "<tr>";
//                $tb .= "<td style='text-align: left;'>-</td>";
//                $tb .= "<td style='text-align: right;'>" . number_format($totals['debet'], '5', '.', ',') . "</td>";
//                $tb .= "<td style='text-align: right;'>" . number_format($totals['kredit'], '5', '.', ',') . "</td>";
//                $tb .= "</tr>";
//                $tb .= "</table>";
//                echo $tb;
//            }
//
//            if (round($totals['debet'], 2) != round($totals['kredit'], 2)) {
//                $selisih = $totals['debet'] - $totals['kredit'];
//                $msg = "rugilaba tidak balance! " . $totals['debet'] . " vs " . $totals['kredit'] . ". ";
//                $msg .= "selisih : $selisih";
//                die(lgShowAlert($msg));
//            }
//
////            if (sizeof($this->outParams) > 0) {
////                return true;
////            }
////            else {
////                return false;
////            }
//
//        }


        if (sizeof($arrRugiLaba) > 0) {
            $this->load->model("Mdls/MdlRugilaba");
            foreach ($arrRugiLaba as $arrSpec) {
                if (array_key_exists($arrSpec['rekening'], $tmp)) {
                    unset($tmp[$arrSpec['rekening']]);
                }
                else {
                    if (!isset($tmp[$arrSpec['rekening']])) {
                        $tmp[$arrSpec['rekening']]['rek_id'] = 0;
                        $tmp[$arrSpec['rekening']]['rekening'] = $arrSpec['rekening'];
                        $tmp[$arrSpec['rekening']]['debet'] = $arrSpec['kredit'];
                        $tmp[$arrSpec['rekening']]['kredit'] = $arrSpec['debet'];
                    }
                }
            }
        }

        $this->outParams = array(
            "rugilaba" => $arrRugiLaba,
            "neraca" => $tmp,
        );


    }

    public function execNoCut()
    {

        return $this->outParams;
    }


    public function pairNoCut_view($inParams, $lajur)
    {
        $this->load->model("Coms/ComRekening_cli");
        $this->load->helper("he_mass_table");
        $this->inParams = $inParams;
        $setFilters = array();

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
//        $tmp = $cr->fetchAllBalances2();


        $tmp = $lajur;

        $arrRekening = array();
        $arrRekeningCat = array(
            "penghasilan" => 0,
            "biaya" => 0,
            "penghasilan lain lain" => 0,
            "biaya lain lain" => 0,
        );
        $arrRugiLaba = array();
        $arrMainComRekening = array();
        $arrMainComRekeningCat = array(
            "penghasilan" => 0,
            "biaya" => 0,
            "penghasilan lain lain" => 0,
            "biaya lain lain" => 0,
        );
        $totals = array(
            "debet" => 0,
            "kredit" => 0,
        );
        $totalss = array(
            "debet" => 0,
            "kredit" => 0,
        );
        if (sizeof($tmp) > 0) {

            $tb = "<table rules='all' style='border: 1px solid black;'>";
            $tb .= "<tr>";
            $tb .= "<td>rekening LAJUR</td>";
            $tb .= "<td>debet</td>";
            $tb .= "<td>kredit</td>";
            $tb .= "</tr>";


            foreach ($tmp as $eSpec) {
                $rek = $eSpec['rekening'];
                $debet = $eSpec['debet'];
                $kredit = $eSpec['kredit'];
                $bulan_target = $eSpec["bln"];
                $tahun_target = $eSpec["thn"];
                $periode_target = $eSpec["periode"];
                $rekCategory = detectRekCategory($rek);

                if (($rekCategory == "penghasilan") || ($rekCategory == "biaya")) {
                    if (!isset($arrRekeningCat[$rekCategory])) {
                        $arrRekeningCat[$rekCategory] = 0;
                    }
                    if (!isset($arrMainComRekeningCat[$rekCategory])) {
                        $arrMainComRekeningCat[$rekCategory] = 0;
                    }


                    //                    $preValue = $this->cekPreValue($rek, $this->inParams['static']['cabang_id']);
                    $preValue = $this->cekPreValue($rek, $this->inParams['static']['cabang_id'], $eSpec['periode']);


                    if ($preValue['debet'] > 0) {
                        $preNumber = detectRekByPosition($rek, $preValue['debet'], "debet");
                    }
                    else {
                        $preNumber = detectRekByPosition($rek, $preValue['kredit'], "kredit");
                    }

                    if ($eSpec['debet'] > 0) {
                        $currentNumber = detectRekByPosition($rek, $eSpec['debet'], "debet");
                    }
                    else {
                        $currentNumber = detectRekByPosition($rek, $eSpec['kredit'], "kredit");
                    }

                    //                    $afterNumber = $preNumber + $currentNumber;
                    $afterNumber = $currentNumber;
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
                        "rek_id" => $eSpec["rek_id"],
                        "kategori" => $rekCategory,
                        "rekening" => $eSpec["rekening"],
                        "debet" => $afterDebet,
                        "kredit" => $afterKredit,

                        "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                        "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                        "cabang_id" => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                        "dtime" => date("Y-m-d H:i:s"),
                        "author" => isset($this->session->login) ? $this->session->login['id'] : 0,
                        "keterangan" => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                        "fulldate" => date("Y-m-d"),
                        "bln" => $eSpec["bln"],
                        "thn" => $eSpec["thn"],
                        "periode" => $eSpec["periode"],
                    );

                    //                    $totals['debet'] += ($eSpec["debet"] + $preValue["debet"]);
                    //                    $totals['kredit'] += ($eSpec["kredit"] + $preValue["kredit"]);
                    $totals['debet'] += $eSpec["debet"];
                    $totals['kredit'] += $eSpec["kredit"];

                }

                if (($rekCategory == "penghasilan lain lain") || ($rekCategory == "biaya lain lain")) {
                    //                    cekLime($rekCategory);
                    if (!isset($arrRekeningCat[$rekCategory])) {
                        $arrRekeningCat[$rekCategory] = 0;
                    }
                    if (!isset($arrMainComRekeningCat[$rekCategory])) {
                        $arrMainComRekeningCat[$rekCategory] = 0;
                    }

                    $preValue = $this->cekPreValue($rek, $this->inParams['static']['cabang_id'], "");

                    if ($preValue['debet'] > 0) {
                        $preNumber = detectRekByPosition($rek, $preValue['debet'], "debet");
                    }
                    else {
                        $preNumber = detectRekByPosition($rek, $preValue['kredit'], "kredit");
                    }

                    if ($eSpec['debet'] > 0) {
                        $currentNumber = detectRekByPosition($rek, $eSpec['debet'], "debet");
                    }
                    else {
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
                        "rek_id" => $eSpec["rek_id"],
                        "kategori" => $rekCategory,
                        "rekening" => $eSpec["rekening"],
                        "debet" => $afterDebet,
                        "kredit" => $afterKredit,
                        //                        "debet" => $eSpec["debet"] + $preValue['debet'],
                        //                        "kredit" => $eSpec["kredit"] + $preValue['kredit'],
                        "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                        "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                        "cabang_id" => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                        "dtime" => date("Y-m-d H:i:s"),
                        "author" => isset($this->session->login) ? $this->session->login['id'] : 0,
                        "keterangan" => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                        "fulldate" => date("Y-m-d"),
                        "bln" => $eSpec["bln"],
                        "thn" => $eSpec["thn"],
                        "periode" => $eSpec["periode"],
                    );

                    //                    $totals['debet'] += ($eSpec["debet"] + $preValue["debet"]);
                    //                    $totals['kredit'] += ($eSpec["kredit"] + $preValue["kredit"]);
                    $totals['debet'] += $eSpec["debet"];
                    $totals['kredit'] += $eSpec["kredit"];

                }


                $totalss['debet'] += $debet;
                $totalss['kredit'] += $kredit;

                $tb .= "<tr>";
                $tb .= "<td style='text-align: left;'>" . $rek . "</td>";
                $tb .= "<td style='text-align: right;'>" . number_format($debet, '5', '.', ',') . "</td>";
                $tb .= "<td style='text-align: right;'>" . number_format($kredit, '5', '.', ',') . "</td>";
                $tb .= "</tr>";

            }

            $selisihh = $totalss['debet'] - $totalss['kredit'];
            $tb .= "<tr>";
            $tb .= "<td style='text-align: left;'>$selisihh</td>";
            $tb .= "<td style='text-align: right;'>" . number_format($totalss['debet'], '5', '.', ',') . "</td>";
            $tb .= "<td style='text-align: right;'>" . number_format($totalss['kredit'], '5', '.', ',') . "</td>";
            $tb .= "</tr>";
            $tb .= "</table>";
//            echo $tb;


            if (sizeof($arrRekeningCat) > 0) {
//                $arrRekening["rugilaba"] = $arrRekeningCat["penghasilan"] - $arrRekeningCat["biaya"];
//                $arrRekening["rugilaba lain lain"] = $arrRekeningCat["penghasilan lain lain"] - $arrRekeningCat["biaya lain lain"];
                $arrRekening["9010"] = $arrRekeningCat["penghasilan"] - $arrRekeningCat["biaya"];
                $arrRekening["9020"] = $arrRekeningCat["penghasilan lain lain"] - $arrRekeningCat["biaya lain lain"];
            }
            else {
//                $arrRekening["rugilaba"] = 0;
//                $arrRekening["rugilaba lain lain"] = 0;
                $arrRekening["9010"] = 0;
                $arrRekening["9020"] = 0;
            }


            if (sizeof($arrMainComRekening) > 0) {
//                $arrMainComRekening["rugilaba"] = $arrMainComRekeningCat["penghasilan"] - $arrMainComRekeningCat["biaya"];
//                $arrMainComRekening["rugilaba lain lain"] = $arrMainComRekeningCat["penghasilan lain lain"] - $arrMainComRekeningCat["biaya lain lain"];
                $arrMainComRekening["9010"] = $arrMainComRekeningCat["penghasilan"] - $arrMainComRekeningCat["biaya"];
                $arrMainComRekening["9020"] = $arrMainComRekeningCat["penghasilan lain lain"] - $arrMainComRekeningCat["biaya lain lain"];
            }
            else {
//                $arrMainComRekening["rugilaba"] = 0;
//                $arrMainComRekening["rugilaba lain lain"] = 0;
                $arrMainComRekening["9010"] = 0;
                $arrMainComRekening["9020"] = 0;
            }

//            arrPrintWebs($arrRekening["rugilaba"]);
//            mati_disini();

            switch (detectRekPosition("9010", $arrRekening["9010"])) {
                case "debet":
                    $arrRugiLaba[] = array(

                        "kategori" => "rugilaba",
                        "rekening" => "9010",
                        "debet" => abs($arrRekening["9010"]),
                        "kredit" => 0,
                        "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                        "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                        "cabang_id" => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                        "dtime" => date("Y-m-d H:i:s"),
                        "author" => isset($this->session->login) ? $this->session->login['id'] : 0,
                        "keterangan" => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                        "fulldate" => date("Y-m-d"),
                        "bln" => $bulan_target,
                        "thn" => $tahun_target,
                        "periode" => $periode_target,
                    );
                    $totals['debet'] += abs($arrRekening["9010"]);

                    break;
                case "kredit":
                    $arrRugiLaba[] = array(
                        "kategori" => "rugilaba",
                        "rekening" => "9010",
                        "kredit" => abs($arrRekening["9010"]),
                        "debet" => 0,
                        "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                        "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                        "cabang_id" => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                        "dtime" => date("Y-m-d H:i:s"),
                        "author" => isset($this->session->login) ? $this->session->login['id'] : 0,
                        "keterangan" => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                        "fulldate" => date("Y-m-d"),
                        "bln" => $bulan_target,
                        "thn" => $tahun_target,
                        "periode" => $periode_target,
                    );
                    $totals['kredit'] += abs($arrRekening["9010"]);
                    break;
            }

            switch (detectRekPosition("9020", $arrRekening["9020"])) {

                case "debet":
                    $arrRugiLaba[] = array(

                        "kategori" => "rugilaba lain lain",
                        "rekening" => "9020",
                        "debet" => abs($arrRekening["9020"]),
                        "kredit" => 0,
                        "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                        "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                        "cabang_id" => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                        "dtime" => date("Y-m-d H:i:s"),
                        "author" => isset($this->session->login) ? $this->session->login['id'] : 0,
                        "keterangan" => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                        "fulldate" => date("Y-m-d"),
                        "bln" => $bulan_target,
                        "thn" => $tahun_target,
                        "periode" => $periode_target,
                    );
                    $totals['debet'] += abs($arrRekening["9020"]);

                    break;
                case "kredit":
                    $arrRugiLaba[] = array(

                        "kategori" => "rugilaba lain lain",
                        "rekening" => "9020",
                        "kredit" => abs($arrRekening["9020"]),
                        "debet" => 0,
                        "transaksi_id" => isset($this->inParams["static"]['transaksi_id']) ? $this->inParams["static"]['transaksi_id'] : "0",
                        "transaksi_no" => isset($this->inParams["static"]['transaksi_no']) ? $this->inParams["static"]['transaksi_no'] : "",
                        "cabang_id" => isset($this->inParams["static"]['cabang_id']) ? $this->inParams["static"]['cabang_id'] : "",
                        "dtime" => date("Y-m-d H:i:s"),
                        "author" => isset($this->session->login) ? $this->session->login['id'] : "0",
                        "keterangan" => isset($this->inParams["static"]['keterangan']) ? $this->inParams["static"]['keterangan'] : "0",
                        "fulldate" => date("Y-m-d"),
                        "bln" => $bulan_target,
                        "thn" => $tahun_target,
                        "periode" => $periode_target,
                    );
                    $totals['kredit'] += abs($arrRekening["9020"]);
                    break;
            }
        }


        //region menulis rugilaba ke tabel rugilaba
        if (sizeof($arrRugiLaba) > 0) {
//            $this->load->model("Mdls/MdlRugilaba");
            foreach ($arrRugiLaba as $arrSpec) {
                foreach ($this->inParams['static'] as $rkey => $rval) {
                    $arrSpec[$rkey] = $rval;
                }
//                arrPrint($arrSpec);
//                $rl = New MdlRugilaba();
//                $rl->addData($arrSpec, $rl->getTableName());
//                cekUngu($this->db->last_query());
            }
        }
        //endregion


        if (sizeof($arrRugiLaba) > 0) {
            $this->load->model("Mdls/MdlRugilaba");
            foreach ($arrRugiLaba as $arrSpec) {
                if (array_key_exists($arrSpec['rekening'], $tmp)) {

                    unset($tmp[$arrSpec['rekening']]);
                }
                else {
                    if (!isset($tmp[$arrSpec['rekening']])) {
                        $tmp[$arrSpec['rekening']]['rek_id'] = 0;
                        $tmp[$arrSpec['rekening']]['rekening'] = $arrSpec['rekening'];
                        $tmp[$arrSpec['rekening']]['debet'] = $arrSpec['kredit'];
                        $tmp[$arrSpec['rekening']]['kredit'] = $arrSpec['debet'];
                    }
                }
            }
        }

        $this->outParams = array(
            "rugilaba" => $arrRugiLaba,
            "neraca" => $tmp,
        );


    }

    public function execNoCut_view()
    {

        return $this->outParams;
    }
}