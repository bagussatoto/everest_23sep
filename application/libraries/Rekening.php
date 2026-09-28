<?php


class Rekening
{
//    protected $toko_id;
    public function __construct()
    {
//        parent::__construct();
//        $this->load->helper("he_menu");
//        $this->load->model("Coms/ComLedger");
//        $this->koloms = array(
//            "nama rekening",
//            "debet",
//            "kredit",
//        );
//        if (!isset($this->session->login['id'])) {
//            gotoLogin();
//        }
//        validateUserSession($this->session->login['id']);

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

    }

    public function saldoYearToDate($cabangID, $periode, $date)
    {
        $this->ci->load->model("Coms/ComRekening");

//        arrPrintPink($this->accounts);
        $arrResult = array();
        $mutasi = array();
        if (sizeof($this->accounts) > 0) {
            foreach ($this->accounts as $rekening => $tabel) {
                // baca mutasi masing-masing rekening besar
                $this->ci->db->select("*");
                $this->ci->db->order_by("id", "desc");
                $this->ci->db->limit(1);
                $this->ci->db->where(
                    array(
                        "cabang_id" => "$cabangID",
                        "fulldate<=" => $date
                    )
                );
                $mutasi[$rekening] = $this->ci->db->get($tabel)->result();
                showLast_query("hijau");

            }
//arrPrintPink($mutasi);
            if (sizeof($mutasi) > 0) {
                foreach ($mutasi as $rekening => $spec) {
                    $period_debet = $periode . "_debet";
                    $period_kredit = $periode . "_kredit";
                    if (sizeof($spec) > 0) {
                        $arrResult[$rekening] = array(
                            "rekening" => $rekening,
                            "debet" => $spec[0]->$period_debet,
                            "kredit" => $spec[0]->$period_kredit,
                            "periode" => $periode,
                            "rek_id" => $spec[0]->rek_id,
                        );
                    }
                    else {
                        $arrResult[$rekening] = array(
                            "rekening" => $rekening,
                            "debet" => 0,
                            "kredit" => 0,
                            "periode" => $periode,
                            "rek_id" => 0,
                        );
                    }
                }
            }

            $str = "<table rules='all' style='border:1px solid black;'>";
            $str .= "<tr>";
            $str .= "<th>rekening  || cabangID [$cabangID]</th>";
            $str .= "<th>debet</th>";
            $str .= "<th>kredit</th>";
            $str .= "</tr>";
            $total_debet = 0;
            $total_kredit = 0;
            foreach ($arrResult as $rekening => $spec) {
                $total_debet += $spec['debet'];
                $total_kredit += $spec['kredit'];

                $str .= "<tr>";
                $str .= "<td style='text-align: left;'>$rekening</td>";
                $str .= "<td style='text-align: right;'>" . number_format($spec['debet']) . "</td>";
                $str .= "<td style='text-align: right;'>" . number_format($spec['kredit']) . "</td>";
                $str .= "</tr>";
            }
            $str .= "<tr>";
            $str .= "<td style='text-align: left;'>-</td>";
            $str .= "<td style='text-align: right;'>" . number_format($total_debet) . "</td>";
            $str .= "<td style='text-align: right;'>" . number_format($total_kredit) . "</td>";
            $str .= "</tr>";

            $str .= "</table>";
            $str .= "<br>";
            if (isset($_GET['debuger']) && ($_GET['debuger'] == 1)) {
                echo $str;
            }

        }

//arrPrintWebs($arrResult);
        return $arrResult;
    }

    public function saldoForever($cabangID, $periode, $date)
    {
        $this->ci->load->model("Coms/ComRekening");

//        arrPrintPink($this->accounts);
        $arrResult = array();
        $mutasi = array();
        if (sizeof($this->accounts) > 0) {
            foreach ($this->accounts as $rekening => $tabel) {
                // baca mutasi masing-masing rekening besar
                $this->ci->db->select("*");
                $this->ci->db->order_by("id", "desc");
                $this->ci->db->limit(1);
                $this->ci->db->where(
                    array(
                        "cabang_id" => "$cabangID",
//                        "fulldate!=" => $date
                    )
                );
                $mutasi[$rekening] = $this->ci->db->get($tabel)->result();
//                showLast_query("hijau");

            }
//arrPrintPink($mutasi);
            if (sizeof($mutasi) > 0) {
                foreach ($mutasi as $rekening => $spec) {
                    $period_debet = $periode . "_debet";
                    $period_kredit = $periode . "_kredit";
                    if (sizeof($spec) > 0) {
                        $arrResult[$rekening] = array(
                            "rekening" => $rekening,
                            "debet" => $spec[0]->$period_debet,
                            "kredit" => $spec[0]->$period_kredit,
                            "periode" => $periode,
                            "rek_id" => $spec[0]->rek_id,
                        );
                    }
                    else {
                        $arrResult[$rekening] = array(
                            "rekening" => $rekening,
                            "debet" => 0,
                            "kredit" => 0,
                            "periode" => $periode,
                            "rek_id" => 0,
                        );
                    }
                }
            }

            $str = "<table rules='all' style='border:1px solid black;'>";
            $str .= "<tr>";
            $str .= "<th>rekening  || cabangID [$cabangID]</th>";
            $str .= "<th>debet</th>";
            $str .= "<th>kredit</th>";
            $str .= "</tr>";
            $total_debet = 0;
            $total_kredit = 0;
            foreach ($arrResult as $rekening => $spec) {
                $total_debet += $spec['debet'];
                $total_kredit += $spec['kredit'];

                $str .= "<tr>";
                $str .= "<td style='text-align: left;'>$rekening</td>";
                $str .= "<td style='text-align: right;'>" . number_format($spec['debet']) . "</td>";
                $str .= "<td style='text-align: right;'>" . number_format($spec['kredit']) . "</td>";
                $str .= "</tr>";
            }
            $str .= "<tr>";
            $str .= "<td style='text-align: left;'>-</td>";
            $str .= "<td style='text-align: right;'>" . number_format($total_debet) . "</td>";
            $str .= "<td style='text-align: right;'>" . number_format($total_kredit) . "</td>";
            $str .= "</tr>";

            $str .= "</table>";
            $str .= "<br>";
            if (isset($_GET['debuger']) && ($_GET['debuger'] == 1)) {
                echo $str;
            }

        }

//arrPrintWebs($arrResult);
        return $arrResult;
    }

    public function saldoForeverRange($cabangID, $periode, $date1, $date2)
    {
        $this->ci->load->model("Coms/ComRekening");

        $arrResult = array();
        $mutasi = array();
        if (sizeof($this->accounts) > 0) {
            foreach ($this->accounts as $rekening => $tabel) {
                // baca mutasi masing-masing rekening besar
                $this->ci->db->select("*");
                $this->ci->db->order_by("id", "desc");
//                $this->ci->db->limit(1);
                $this->ci->db->where(
                    array(
                        "cabang_id" => "$cabangID",
//                        "date(dtime)<=" => $date,
                        "fulldate>=" => $date1,
                        "fulldate<=" => $date2,
//                        "jenis!=" => ""
                    )

                );
                $mutasi[$rekening] = $this->ci->db->get($tabel)->result();
//                showLast_query("hijau");
//                break;
            }
//arrPrintWebs($mutasi);
//mati_disini();
//            if (sizeof($mutasi) > 0) {
//                foreach ($mutasi as $rekening => $spec) {
//                    $period_debet = $periode . "_debet";
//                    $period_kredit = $periode . "_kredit";
//                    if (sizeof($spec) > 0) {
//                        $arrResult[$rekening] = array(
//                            "rekening" => $rekening,
//                            "debet" => $spec[0]->$period_debet,
//                            "kredit" => $spec[0]->$period_kredit,
//                            "periode" => $periode,
//                            "rek_id" => $spec[0]->rek_id,
//                        );
//                    }
//                    else {
//                        $arrResult[$rekening] = array(
//                            "rekening" => $rekening,
//                            "debet" => 0,
//                            "kredit" => 0,
//                            "periode" => $periode,
//                            "rek_id" => 0,
//                        );
//                    }
//                }
//            }
            $arrResult = array();
            if (sizeof($mutasi) > 0) {
                foreach ($mutasi as $rekening => $rekSpec) {
//                    $period_debet = $periode . "_debet";
//                    $period_kredit = $periode . "_kredit";

                    if (sizeof($rekSpec) > 0) {
                        foreach ($rekSpec as $spec) {
                            $position = detectRekDefaultPosition($rekening);
//                            cekHere("def position: $position");
                            $debet = $spec->debet;
                            $kredit = $spec->kredit;
                            switch ($position) {
                                case "debet":
                                    if ($debet > 0) {
                                        $debet = $debet;
                                        $kredit = 0;
                                    }
                                    else {
                                        $debet = $kredit * -1;
                                        $kredit = 0;
                                    }
                                    break;
                                case "kredit":
                                    if ($kredit > 0) {
                                        $kredit = $kredit;
                                        $debet = 0;
                                    }
                                    else {
                                        $kredit = $debet * -1;
                                        $debet = 0;
                                    }
                                    break;
                            }

                            if (!isset($arrResult[$rekening]["debet"])) {
                                $arrResult[$rekening]["debet"] = 0;
                            }
                            if (!isset($arrResult[$rekening]["kredit"])) {
                                $arrResult[$rekening]["kredit"] = 0;
                            }


                            $arrResult[$rekening]["rekening"] = $rekening;
                            $arrResult[$rekening]["periode"] = $spec->periode;
                            $arrResult[$rekening]["rek_id"] = $spec->rek_id;
                            $arrResult[$rekening]["debet"] += $debet;
                            $arrResult[$rekening]["kredit"] += $kredit;

//                        $arrResult[$rekening]["rekening"] = $rekening;
//                        $arrResult[$rekening]["rekening"] = $rekening;
//                        $arrResult[$rekening]["rekening"] = $rekening;
//                        $arrResult[$rekening] = array(
//                            "rekening" => $rekening,
//                            "debet" => $spec[0]->$period_debet,
//                            "kredit" => $spec[0]->$period_kredit,
//                            "periode" => $periode,
//                            "rek_id" => $spec[0]->rek_id,
//                        );
                        }
                    }
                    else {
                        $arrResult[$rekening] = array(
                            "rekening" => $rekening,
                            "debet" => 0,
                            "kredit" => 0,
                            "periode" => $periode,
                            "rek_id" => 0,
                        );
                    }
                }
            }


            //region view lajur
            $str = "<table rules='all' style='border:1px solid black;'>";
            $str .= "<tr>";
            $str .= "<th>rekening  || cabangID [$cabangID]</th>";
            $str .= "<th>debet</th>";
            $str .= "<th>kredit</th>";
            $str .= "</tr>";
            $total_debet = 0;
            $total_kredit = 0;
            foreach ($arrResult as $rekening => $spec) {

                if ($spec['debet'] > 0) {
//                    cekHere("$rekening " . __LINE__);
                    $spec['debet'] = $spec['debet'];
                    $spec['kredit'] = 0;
                }
                elseif ($spec['debet'] < 0) {
//                    cekHere("$rekening " . __LINE__);
                    $spec['kredit'] = $spec['debet'] * -1;
                    $spec['debet'] = 0;
                }
                elseif ($spec['kredit'] > 0) {
//                    cekHere("$rekening " . __LINE__);
                    $spec['kredit'] = $spec['kredit'];
                    $spec['debet'] = 0;
                }
                elseif ($spec['kredit'] < 0) {
//                    cekHere("$rekening " . __LINE__);
                    $spec['debet'] = $spec['kredit'] * -1;
                    $spec['kredit'] = 0;
                }
                else {
//                    cekHere("$rekening " . __LINE__);
                    $spec['debet'] = 0;
                    $spec['kredit'] = 0;
                }
                $arrResult[$rekening]["debet"] = $spec['debet'];
                $arrResult[$rekening]["kredit"] = $spec['kredit'];

                $total_debet += $spec['debet'];
                $total_kredit += $spec['kredit'];

                $str .= "<tr>";
                $str .= "<td style='text-align: left;'>$rekening</td>";
                $str .= "<td style='text-align: right;'>" . number_format($spec['debet']) . "</td>";
                $str .= "<td style='text-align: right;'>" . number_format($spec['kredit']) . "</td>";
                $str .= "</tr>";
            }
            $str .= "<tr>";
            $str .= "<td style='text-align: left;'>-</td>";
            $str .= "<td style='text-align: right;'>" . number_format($total_debet) . "</td>";
            $str .= "<td style='text-align: right;'>" . number_format($total_kredit) . "</td>";
            $str .= "</tr>";

            $str .= "</table>";
            $str .= "<br>";
            if (isset($_GET['debuger']) && ($_GET['debuger'] == 1)) {
//                echo $str;
            }
            //endregion

//            cekHere("[cabangID: $cabangID] DEBET: ". number_format($total_debet) . ", KREDIT: " . number_format($total_kredit));

        }

//arrPrintWebs($arrResult['biaya import']);
//mati_disini();
        return $arrResult;
    }

    public function saldoMonthToDate($cabangID, $periode, $date)
    {
        $this->ci->load->model("Coms/ComRekening");

        $date_ex = explode("-", $date);
        $date_first = $date_ex[0] . "-" . $date_ex[1] . "-01";
        $arrResult = array();
        $mutasi = array();
        if (sizeof($this->accounts) > 0) {
            foreach ($this->accounts as $rekening => $tabel) {
                $rekCat = detectRekCategory($rekening);

                // baca mutasi masing-masing rekening besar
                $this->ci->db->select("*");
                $this->ci->db->order_by("id", "desc");
                $this->ci->db->limit(1);

                if (!in_array($rekCat, $this->catException)) {
                    $this->ci->db->where(
                        array(
                            "cabang_id" => "$cabangID",
                            "date(dtime)<=" => $date,
                        )
                    );
                }
                else {
                    $this->ci->db->where(
                        array(
                            "cabang_id" => "$cabangID",
                            "month(dtime)" => $date_ex[1],
                            "year(dtime)" => $date_ex[0],
                        )
                    );
                }
                $mutasi[$rekening] = $this->ci->db->get($tabel)->result();
//                showLast_query("hijau");
            }

            if (sizeof($mutasi) > 0) {
                foreach ($mutasi as $rekening => $spec) {
                    $period_debet = $periode . "_debet";
                    $period_kredit = $periode . "_kredit";
                    if (sizeof($spec) > 0) {
                        $arrResult[$rekening] = array(
                            "rekening" => $rekening,
                            "debet" => $spec[0]->$period_debet,
                            "kredit" => $spec[0]->$period_kredit,
                            "periode" => $periode,
                            "rek_id" => $spec[0]->rek_id,
                        );
                    }
                    else {
                        $arrResult[$rekening] = array(
                            "rekening" => $rekening,
                            "debet" => 0,
                            "kredit" => 0,
                            "periode" => $periode,
                            "rek_id" => 0,
                        );
                    }
                }
            }
//arrPrintWebs($arrResult);
            $str = "<br><table rules='all' style='border:1px solid black;'>";
            $str .= "<tr>";
            $str .= "<th>rekening  || cabangID [$cabangID]</th>";
            $str .= "<th>debet</th>";
            $str .= "<th>kredit</th>";
            $str .= "</tr>";
            $total_debet = 0;
            $total_kredit = 0;
            foreach ($arrResult as $rekening => $spec) {
                $total_debet += $spec['debet'];
                $total_kredit += $spec['kredit'];

                $str .= "<tr>";
                $str .= "<td style='text-align: left;'>$rekening</td>";
                $str .= "<td style='text-align: right;'>" . number_format($spec['debet']) . "</td>";
                $str .= "<td style='text-align: right;'>" . number_format($spec['kredit']) . "</td>";
                $str .= "</tr>";
            }
            $str .= "<tr>";
            $str .= "<td style='text-align: left;'>-</td>";
            $str .= "<td style='text-align: right;'>" . number_format($total_debet) . "</td>";
            $str .= "<td style='text-align: right;'>" . number_format($total_kredit) . "</td>";
            $str .= "</tr>";

            $str .= "</table>";
            $str .= "<br>";
//            echo $str;
        }

//arrPrintWebs($arrResult);
        return $arrResult;
    }

    public function saldoYearly($cabangID, $periode, $date)
    {
        $this->ci->load->model("Coms/ComRekening");
        $date_ex = explode("-", $date);
        $date_first = $date_ex[0] . "-" . $date_ex[1] . "-01";
//        arrPrintPink($this->accounts);
        $arrResult = array();
        $mutasi = array();
        if (sizeof($this->accounts) > 0) {
            foreach ($this->accounts as $rekening => $tabel) {
                $rekCat = detectRekCategory($rekening);

                // baca mutasi masing-masing rekening besar
                $this->ci->db->select("*");
                $this->ci->db->order_by("id", "desc");
                $this->ci->db->limit(1);

                if (!in_array($rekCat, $this->catException)) {
                    $this->ci->db->where(
                        array(
                            "cabang_id" => "$cabangID",
                            "fulldate<=" => $date
                        )
                    );
                }
                else {
                    $this->ci->db->where(
                        array(
                            "cabang_id" => "$cabangID",
//                            "month(dtime)" => $date_ex[1],
                            "year(dtime)" => $date_ex[0],
                        )
                    );
                }

                $mutasi[$rekening] = $this->ci->db->get($tabel)->result();
                showLast_query("hijau");

            }
//arrPrintPink($mutasi);
            if (sizeof($mutasi) > 0) {
                foreach ($mutasi as $rekening => $spec) {
                    $period_debet = $periode . "_debet";
                    $period_kredit = $periode . "_kredit";
                    if (sizeof($spec) > 0) {
                        $arrResult[$rekening] = array(
                            "rekening" => $rekening,
                            "debet" => $spec[0]->$period_debet,
                            "kredit" => $spec[0]->$period_kredit,
                            "periode" => $periode,
                            "rek_id" => $spec[0]->rek_id,
                        );
                    }
                    else {
                        $arrResult[$rekening] = array(
                            "rekening" => $rekening,
                            "debet" => 0,
                            "kredit" => 0,
                            "periode" => $periode,
                            "rek_id" => 0,
                        );
                    }
                }
            }

            $str = "<table rules='all' style='border:1px solid black;'>";
            $str .= "<tr>";
            $str .= "<th>rekening  || cabangID [$cabangID]</th>";
            $str .= "<th>debet</th>";
            $str .= "<th>kredit</th>";
            $str .= "</tr>";
            $total_debet = 0;
            $total_kredit = 0;
            foreach ($arrResult as $rekening => $spec) {
                $total_debet += $spec['debet'];
                $total_kredit += $spec['kredit'];

                $str .= "<tr>";
                $str .= "<td style='text-align: left;'>$rekening</td>";
                $str .= "<td style='text-align: right;'>" . number_format($spec['debet']) . "</td>";
                $str .= "<td style='text-align: right;'>" . number_format($spec['kredit']) . "</td>";
                $str .= "</tr>";
            }
            $str .= "<tr>";
            $str .= "<td style='text-align: left;'>-</td>";
            $str .= "<td style='text-align: right;'>" . number_format($total_debet) . "</td>";
            $str .= "<td style='text-align: right;'>" . number_format($total_kredit) . "</td>";
            $str .= "</tr>";

            $str .= "</table>";
            $str .= "<br>";
//            echo $str;
        }

//arrPrintWebs($arrResult);
        return $arrResult;
    }

    public function saldoMonthly($cabangID, $periode, $date)
    {
        $this->ci->load->model("Coms/ComRekening");

        $date_ex = explode("-", $date);
        $date_first = $date_ex[0] . "-" . $date_ex[1] . "-01";
        $arrResult = array();
        $mutasi = array();
        if (sizeof($this->accounts) > 0) {
            foreach ($this->accounts as $rekening => $tabel) {
                $rekCat = detectRekCategory($rekening);

                // baca mutasi masing-masing rekening besar
                $this->ci->db->select("*");
                $this->ci->db->order_by("id", "desc");
                $this->ci->db->limit(1);

                if (!in_array($rekCat, $this->catException)) {
                    $this->ci->db->where(
                        array(
                            "cabang_id" => "$cabangID",
                            "date(dtime)<=" => $date,
                        )
                    );
                }
                else {
                    $this->ci->db->where(
                        array(
                            "cabang_id" => "$cabangID",
                            "month(dtime)" => $date_ex[1],
                            "year(dtime)" => $date_ex[0],
                        )
                    );
                }
                $mutasi[$rekening] = $this->ci->db->get($tabel)->result();
//                showLast_query("hijau");
            }

            if (sizeof($mutasi) > 0) {
                foreach ($mutasi as $rekening => $spec) {
                    $period_debet = $periode . "_debet";
                    $period_kredit = $periode . "_kredit";
                    if (sizeof($spec) > 0) {
                        $arrResult[$rekening] = array(
                            "rekening" => $rekening,
                            "debet" => $spec[0]->$period_debet,
                            "kredit" => $spec[0]->$period_kredit,
                            "periode" => $periode,
                            "rek_id" => $spec[0]->rek_id,
                        );
                    }
                    else {
                        $arrResult[$rekening] = array(
                            "rekening" => $rekening,
                            "debet" => 0,
                            "kredit" => 0,
                            "periode" => $periode,
                            "rek_id" => 0,
                        );
                    }
                }
            }
//arrPrintWebs($arrResult);
            $str = "<br><table rules='all' style='border:1px solid black;'>";
            $str .= "<tr>";
            $str .= "<th>rekening  || cabangID [$cabangID]</th>";
            $str .= "<th>debet</th>";
            $str .= "<th>kredit</th>";
            $str .= "</tr>";
            $total_debet = 0;
            $total_kredit = 0;
            foreach ($arrResult as $rekening => $spec) {
                $total_debet += $spec['debet'];
                $total_kredit += $spec['kredit'];

                $str .= "<tr>";
                $str .= "<td style='text-align: left;'>$rekening</td>";
                $str .= "<td style='text-align: right;'>" . number_format($spec['debet']) . "</td>";
                $str .= "<td style='text-align: right;'>" . number_format($spec['kredit']) . "</td>";
                $str .= "</tr>";
            }
            $str .= "<tr>";
            $str .= "<td style='text-align: left;'>-</td>";
            $str .= "<td style='text-align: right;'>" . number_format($total_debet) . "</td>";
            $str .= "<td style='text-align: right;'>" . number_format($total_kredit) . "</td>";
            $str .= "</tr>";

            $str .= "</table>";
            $str .= "<br>";
//            echo $str;
        }

//arrPrintWebs($arrResult);
        return $arrResult;
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
            showLast_query("hijau");

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
            showLast_query("hijau");
        }
        // endregion


    }
}
