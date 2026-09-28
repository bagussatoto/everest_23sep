<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 1/31/2019
 * Time: 10:00 PM
 */
class Ledger extends CI_Controller
{

    private $dates = array();

    public function __construct()
    {

        parent::__construct();
        if (!isset($this->session->login['id'])) {
            gotoLogin();
            // redirect(base_url() . "Login");
        }
        $this->load->model("MdlTransaksi");
        $trd = new MdlTransaksi();
        //        $trd->addFilter("jenis_top='" . $this->jenisTr . "'");
        $this->dates = $trd->lookupDates();
        $this->dates['entries'][date("y-m-d")] = date("y-m-d");
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
        }
        validateUserSession($this->session->login['id']);//
        $this->placeID = $this->session->login['cabang_id'];
        $this->gudangID = $this->session->login['gudang_id'];
        $this->gudangName = $this->session->login['gudang_nama'];
        // arrPrint($this->session);
        $this->summaryKey = array(
            "in",
            "out",
            "in_qty",
            "out_qty",
            "balance",
        );
    }

    public function index()
    {

        die("_");
    }

    public function viewBalances_l1()
    {
        // arrPrint($this->uri->segment_array());
        $relName = $this->uri->segment(3);
        $rekName = urldecode($this->uri->segment(4));
        $defPosition = detectRekDefaultPosition($rekName);


        $balConfig = isset($this->config->item('accountBalanceColumns')[$relName]) ? $this->config->item('accountBalanceColumns')[$relName] : array();
        $accountFilters = isset($this->config->item('accountBalanceColumns')[$relName]['viewFilters']) ? $this->config->item('accountBalanceColumns')[$relName]['viewFilters'] : array();
        $accountRekDetailAdditional = isset($this->config->item('accountRekDetailAdditional')[$rekName]) ? $this->config->item('accountRekDetailAdditional')[$rekName] : array();
        $accountBalanceAdditionalColumns = isset($this->config->item('accountBalanceAdditionalColumns')[$rekName]) ? $this->config->item('accountBalanceAdditionalColumns')[$rekName] : array();
        $accountSubChilds = ($this->config->item('accountSubChilds') != NULL) ? $this->config->item('accountSubChilds') : array();


        $q = isset($_GET['q']) && strlen($_GET['q']) ? $_GET['q'] : "";
        $sortBy = isset($_GET['sortBy']) && strlen($_GET['sortBy']) ? $_GET['sortBy'] : "extern_nama";
        $sortMode = isset($_GET['sortMode']) && strlen($_GET['sortMode']) ? $_GET['sortMode'] : "ASC";


        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];


        $thisPage = base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "?o=$cabangID";
        $thisURL = base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "?q=$q&o=$cabangID";


        $mdlName = "Com" . $relName;

        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $mdlName);
        $com = new $mdlName();

        //region model reguler / yang utama
        if (isset($balConfig['mdlData'])) {
            $mdlData = $balConfig['mdlData'];
            $this->load->model("Mdls/$mdlData");
            $sp = new $mdlData();

            //region data dari MdlSupplies
            $tmpSp = $sp->lookupAll()->result();
            $keySp = $balConfig['mdlDataKeys'];
            foreach ($tmpSp as $itemSp) {
                $dataSps = array();
                foreach ($keySp as $kolomSp) {
                    $dataSps[$kolomSp] = $itemSp->$kolomSp;
                }

                $itemSps[$itemSp->id] = $dataSps;
            }
            //endregion
            // arrPrint($tmpSp);
            // arrPrint($itemSps);
        }
        $com->addFilter("cabang_id='$cabangID'");
        if (sizeof($accountFilters) > 0) {
            foreach ($accountFilters as $f) {
                $f_ex = explode("=", $f);
                if (!isset($f_ex[1])) {
                    $f_ey = explode(">", $f_ex[0]);
                    if (substr($f_ey[1], 0, 1) == ".") {
                        $com->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                    }
                    else {
                        $com->addFilter($f_ey[0] . ">'" . $this->session->login[$f_ey[1]] . "'");
                    }
                }
                else {
                    if (substr($f_ex[1], 0, 1) == ".") {
                        $com->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                    }
                    else {
                        $com->addFilter($f_ex[0] . "='" . $this->session->login[$f_ex[1]] . "'");
                    }
                }
            }
        }
        if (isset($_GET['w'])) {
            $com->addFilter("gudang_id='" . $_GET['w'] . "'");
        }
        $tmp = $com->fetchBalances($rekName, $q, $sortBy, $sortMode);
        //        showLast_query("biru");

        //endregion


        $pairedResult = array();
        if (isset($balConfig['pairedModel']) && sizeof($balConfig['pairedModel'])) {
            $mdlName = $balConfig['pairedModel']['mdlName'];
            $this->load->model("Mdls/" . $mdlName);
            $mdl = New $mdlName();
            $mdlResult = $mdl->$balConfig['pairedModel']['mdlMethod']()->result();
            // cekHere($this->db->last_query());
            //            arrPrint($mdlResult);

            if (sizeof($mdlResult) > 0) {
                foreach ($mdlResult as $rSpec) {
                    foreach ($balConfig['pairedModel']['fieldName'] as $key => $val) {
                        $pairedResult[$rSpec->$balConfig['pairedModel']['key']][$key] = isset($rSpec->$val) ? $rSpec->$val : "";
                    }
                }
            }
        }


        $rkTempResult = array();
        if (sizeof($accountRekDetailAdditional) > 0) {
            foreach ($accountRekDetailAdditional as $rekeningNama => $spec) {
                $detailRelRekening = str_replace("akum penyu ", "", $rekeningNama);

                $this->load->model("Coms/ComRekening");
                $rk = New ComRekening();
                $rk->setFilters(array());
                $rk->addFilter("cabang_id='$cabangID'");
                $rkTemp = $rk->fetchBalances($rekeningNama);

                if (sizeof($rkTemp) > 0) {
                    $rkTempResult[$detailRelRekening] = $rkTemp[0];
                }
            }
        }


        if (isset($balConfig['additionalPairedModel']) && sizeof($balConfig['additionalPairedModel']) > 0) {
            $addMdlNameRek = $balConfig['additionalPairedModel']['mdlNameRek'];
            $addMethodRek = $balConfig['additionalPairedModel']['mdlMethodRek'];
            $addPrefix = $balConfig['additionalPairedModel']['prefix'];

            $addMdlNameData = $balConfig['additionalPairedModel']['mdlNameData'];
            $addMethodData = $balConfig['additionalPairedModel']['mdlMethodData'];

            $this->load->model("Mdls/$addMdlNameData");
            $dt = New $addMdlNameData();
            $dt->setFilters(array());
            $dt->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
            $dt->addFilter("trash=0");
            $dt->addFilter("status=1");
            $dt->addFilter("id>0");
            $tmpData = $dt->$addMethodData()->result();
            if (sizeof($tmpData) > 0) {
                $ids = array();
                foreach ($tmpData as $spec) {
                    $ids[] = $spec->id;
                }

                $dtr = New $addMdlNameRek();
                $dtr->setFilters(array());
                $dtr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
                $dtr->addFilter("gudang_id in ('" . implode("','", $ids) . "')");
                $tmpData = $dtr->$addMethodRek($rekName, $q, $sortBy, $sortMode);
                //                showLast_query("merah");
                //                cekMerah(sizeof($tmpData));
                $addDataResult = array();
                if (sizeof($tmpData) > 0) {
                    foreach ($tmpData as $tmpSpec) {
                        if (sizeof($balConfig['additionalViewedColumns']) > 0) {
                            foreach ($balConfig['additionalViewedColumns'] as $addKey => $addVal) {
                                $addKey_fix = str_replace($addPrefix, "", $addKey);
                                $addDataResult[$tmpSpec->extern_id][$addKey] = $tmpSpec->$addKey_fix;
                            }
                        }
                    }
                }

            }
        }


        $items = array();
        $items2 = array();
        $items_blok = array();
        $no = 0;
        if (sizeof($tmp) > 0) {
            $tmpRow = array();
            foreach ($tmp as $row) {

                $rekening = $row->rekening;
                $extern_nama = $row->extern_nama;
                $position = detectRekDefaultPosition($rekening);

                $rekening_relasi_tmp = isset($rkTempResult[$row->extern_nama]) ? $rkTempResult[$row->extern_nama] : array();
                $rekening_relasi = sizeof($rekening_relasi_tmp) && isset($rekening_relasi_tmp->rekening) ? $rekening_relasi_tmp->rekening : NULL;

                switch ($position) {
                    case "debet":
                        if ($row->kredit > 0) {
                            $row->debet = $row->kredit * -1;
                            $row->kredit = 0;
                        }
                        if ($row->qty_kredit > 0) {
                            $row->qty_debet = $row->qty_kredit * -1;
                            $row->qty_kredit = 0;
                        }
                        break;
                    case "kredit":
                        if ($row->debet > 0) {
                            $row->kredit = $row->debet * -1;
                            $row->debet = 0;
                        }
                        if ($row->qty_debet > 0) {
                            $row->qty_kredit = $row->qty_debet * -1;
                            $row->qty_debet = 0;
                        }
                        break;
                }

                foreach ($balConfig['viewedColumns'] as $key => $label) {
                    $tmpRow[$key] = isset($row->$key) ? $row->$key : "";
                    if (sizeof($pairedResult) > 0) {
                        if (array_key_exists($row->extern_id, $pairedResult)) {
                            foreach ($pairedResult[$row->extern_id] as $pkey => $pval) {
                                $tmpRow[$pkey] = $pval;
                            }
                        }
                    }
                }

                $tmpRow['satuan'] = isset($itemSps) && sizeof($itemSps) > 0 ? $itemSps[$row->extern_id]['satuan'] : "-";
                $tmpRow['pId'] = isset($row->extern_id) ? $row->extern_id : 0;

                if (isset($accountSubChilds[$extern_nama])) {

                    $tmpRow['link'] = base_url() . "Ledger/viewBalances_l1/" . $accountSubChilds[$extern_nama] . "/" . $extern_nama;
                    $tmpRow['link_main']['extern_nama'] = base_url() . "Ledger/viewMoveDetails/$relName/$rekName/" . $row->extern_id . "?o=$cabangID";
                }
                else {

                    $tmpRow['link'] = base_url() . "Ledger/viewMoveDetails/$relName/$rekName/" . $row->extern_id . "?o=$cabangID";
                    $tmpRow['link_main']['extern_nama'] = NULL;
                }


                if (isset($balConfig['additionalViewedColumns']) && sizeof($balConfig['additionalViewedColumns']) > 0) {
                    foreach ($balConfig['additionalViewedColumns'] as $addKey => $addVal) {
                        $tmpRow[$addKey] = isset($addDataResult[$row->extern_id][$addKey]) ? $addDataResult[$row->extern_id][$addKey] : 0;
                    }
                }
                if (isset($balConfig['additionalTotalViewedColumns']) && sizeof($balConfig['additionalTotalViewedColumns']) > 0) {
                    $src_qty = "qty_" . $position;
                    $src_ng_qty = "ng_qty_" . $position;
                    $src_val = $position;
                    $src_ng_val = "ng_" . $position;
                    $tmpRow['total_qty_' . $position] = $tmpRow[$src_qty] + $tmpRow[$src_ng_qty];
                    $tmpRow['total_' . $position] = $tmpRow[$src_val] + $tmpRow[$src_ng_val];
                }

                $no++;
                $no_main = $no;
                $items[$no] = $tmpRow;

                $saldo_rek_utama = $row->$position;


                // ================================================================================================
                if ($rekening_relasi != NULL) {

                    $positionRel = detectRekDefaultPosition($rekening_relasi);

                    $rekening_relasi_tmp->extern_nama = $rekening_relasi_tmp->rekening;

                    switch ($positionRel) {
                        case "debet":
                            if ($rekening_relasi_tmp->kredit > 0) {
                                $rekening_relasi_tmp->debet = $rekening_relasi_tmp->kredit * -1;
                                $rekening_relasi_tmp->kredit = 0;
                            }
                            if ($rekening_relasi_tmp->qty_kredit > 0) {
                                $rekening_relasi_tmp->qty_debet = $rekening_relasi_tmp->qty_kredit * -1;
                                $rekening_relasi_tmp->qty_kredit = 0;
                            }
                            break;
                        case "kredit":
                            if ($rekening_relasi_tmp->debet > 0) {
                                $rekening_relasi_tmp->kredit = $rekening_relasi_tmp->debet * -1;
                                $rekening_relasi_tmp->debet = 0;
                            }
                            if ($rekening_relasi_tmp->qty_debet > 0) {
                                $rekening_relasi_tmp->qty_kredit = $rekening_relasi_tmp->qty_debet * -1;
                                $rekening_relasi_tmp->qty_debet = 0;
                            }
                            break;
                    }
                    foreach ($balConfig['viewedColumns'] as $key => $label) {
                        $tmpRowRel[$key] = isset($rekening_relasi_tmp->$key) ? $rekening_relasi_tmp->$key : "";
                        if (sizeof($pairedResult) > 0) {
                            if (array_key_exists($rekening_relasi_tmp->extern_id, $pairedResult)) {
                                foreach ($pairedResult[$rekening_relasi_tmp->extern_id] as $pkey => $pval) {
                                    $tmpRowRel[$pkey] = $pval;
                                }
                            }
                        }
                    }

                    $tmpRowRel['satuan'] = isset($itemSps) && sizeof($itemSps) > 0 ? $itemSps[$rekening_relasi_tmp->extern_id]['satuan'] : "-";
                    $tmpRowRel['pId'] = 0;
                    $mainLink = isset($accountRekDetailAdditional[$rekening_relasi_tmp->rekening]['mainLink']) ? $accountRekDetailAdditional[$rekening_relasi_tmp->rekening]['mainLink'] : "";


                    if (isset($accountSubChilds[$rekening_relasi])) {

                        $tmpRowRel['link'] = base_url() . "Ledger/viewBalances_l1/" . $accountSubChilds[$rekening_relasi] . "/" . $rekening_relasi;
                        $tmpRowRel['link_main']['extern_nama'] = base_url() . "$mainLink" . $rekening_relasi_tmp->rekening;
                    }
                    else {

                        $tmpRowRel['link'] = base_url() . "$mainLink" . $rekening_relasi_tmp->rekening;
                        $tmpRowRel['link_main']['extern_nama'] = NULL;
                    }

                    //                    $tmpRowRel['link'] = base_url() . "$mainLink" . $rekening_relasi_tmp->rekening;

                    $no++;
                    $no_relasi = $no;
                    $items[$no] = $tmpRowRel;

                    $saldo_rek_relasi = $rekening_relasi_tmp->$positionRel;
                }
                else {
                    $no_relasi = NULL;
                    $saldo_rek_relasi = 0;
                }

                // ================================================================================================
                if (sizeof($accountRekDetailAdditional) > 0) {
                    $items_blok[] = array(
                        "main" => $no_main,
                        "relasi" => $no_relasi,
                    );
                }

                if (sizeof($accountBalanceAdditionalColumns) > 0) {

                    $items[$no]['netto'] = $saldo_rek_utama - $saldo_rek_relasi;
                }
                // ================================================================================================


            }
        }


        //ganti headerFields
        $headerFields = array(
            "rek_id" => "kode",
            "pId" => "pID",
            //            "kode" => "product code",
            //            "extern_nama" => "item names",
            //
            //
            // "satuan" => "UOM",
        );
        if (isset($balConfig['pairedModel']['viewedColumns']) && sizeof($balConfig['pairedModel']['viewedColumns'])) {
            foreach ($balConfig['pairedModel']['viewedColumns'] as $k => $v) {
                $headerFields[$k] = $v;
            }
        }
        $headerFields["extern_nama"] = "item names";
        $headerFields["satuan"] = "UOM";

        $headerQtyFields = array();
        $headerValueFields = array();
        if (isset($balConfig['viewedColumnsStatus']) && ($balConfig['viewedColumnsStatus'] == true)) {
            foreach ($balConfig['viewedColumns'] as $key => $val) {
                $headerQtyFields['qty_' . $key] = $val;
                $headerValueFields[$key] = $val;
            }
        }
        else {
            $headerQtyFields = array(
                "qty_" . $defPosition => "balance (QTY)",
            );
            $headerValueFields = array(
                $defPosition => "balance (IDR)",
            );
        }

        if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
            $headerFields = $headerFields + $headerQtyFields;
        }
        if (isset($balConfig['showValue']) && $balConfig['showValue'] == true) {
            $headerFields = $headerFields + $headerValueFields;
        }
        if (sizeof($accountBalanceAdditionalColumns) > 0) {
            $headerFields = $headerFields + $accountBalanceAdditionalColumns;
        }
        if (isset($balConfig['additionalViewedColumns']) && sizeof($balConfig['additionalViewedColumns']) > 0) {
            foreach ($balConfig['additionalViewedColumns'] as $key => $val) {
                $addViewedColumns[$key] = $val;
            }
            $headerFields = $headerFields + $addViewedColumns;
        }
        if (isset($balConfig['additionalTotalViewedColumns']) && sizeof($balConfig['additionalTotalViewedColumns']) > 0) {
            foreach ($balConfig['additionalTotalViewedColumns'] as $key => $val) {
                $addTotalViewedColumns[$key] = $val;
            }
            $headerFields = $headerFields + $addTotalViewedColumns;
        }
        //        arrPrint($headerFields);

        $subTitle = "balances ";
        if ($q != "") {
            $subTitle .= " matched '$q'";
        }

        $summaryAllowed = array("debet", "kredit", "qty_debet", "qty_kredit", "netto");
        if (isset($balConfig['additionalViewedColumns']) && sizeof($balConfig['additionalViewedColumns']) > 0) {
            $addKey = array_keys($balConfig['additionalViewedColumns']);
            $summaryAllowed = array_merge($summaryAllowed, $addKey);
        }
        if (isset($balConfig['additionalTotalViewedColumns']) && sizeof($balConfig['additionalTotalViewedColumns']) > 0) {
            $addKey = array_keys($balConfig['additionalTotalViewedColumns']);
            $summaryAllowed = array_merge($summaryAllowed, $addKey);
        }

        $param_to_excel = array(
            "mdl" => isset($mdlData) && (strlen($mdlData) > 5) ? $mdlData : $mdlName,
            "fifo" => isset($mdlData) && (strlen($mdlData) > 5) ? "MdlFifoSupplies" : "MdlFifoProdukJadi",
            // "mdl_data"  => $mdlData,
            "cabang_id" => $cabangID,
        );
        $param_to_excel_e = str_replace("=", "", blobEncode($param_to_excel));

        $data = array(
            "mode" => "saldo",
            "title" => "$rekName",
            "subTitle" => $subTitle,
            "items" => $items,
            "headerFields" => $headerFields,
            "thisPage" => $thisPage,
            "thisURL" => $thisURL,
            "q" => $q,
            //            "inspectTarget_mutasi" => base_url() . "Ledger/viewMoves_l2/$relName/$rekName/",
            "summary" => $summaryAllowed,
            "items_blok" => $items_blok,
            "param_to_excel" => $param_to_excel_e,
        );
        //endregion


        $this->load->view("ledger", $data);


    }

    public function viewBalances_t1()
    {
        //        arrPrint($this->uri->segment_array());
        $relName = $this->uri->segment(3);
        $rekName = str_replace("_", " ", urldecode($this->uri->segment(4)));
        $defPosition = detectRekDefaultPosition($rekName);

        $balConfig = isset($this->config->item('accountBalanceColumns')[$relName]) ? $this->config->item('accountBalanceColumns')[$relName] : array();
        $accountFilters = isset($this->config->item('accountBalanceColumns')[$relName]['viewFilters']) ? $this->config->item('accountBalanceColumns')[$relName]['viewFilters'] : array();


        $q = isset($_GET['q']) && strlen($_GET['q']) ? $_GET['q'] : "";
        $sortBy = isset($_GET['sortBy']) && strlen($_GET['sortBy']) ? $_GET['sortBy'] : "extern_nama";
        $sortMode = isset($_GET['sortMode']) && strlen($_GET['sortMode']) ? $_GET['sortMode'] : "ASC";


        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];


        $thisPage = base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "?o=$cabangID";
        $thisURL = base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "?q=$q&o=$cabangID";


        $mdlName = "Com" . $relName;

        $this->load->helper("he_mass_table");
        $this->load->model("Mdls/MdlGudang");
        $this->load->model("Coms/" . $mdlName);
        $com = new $mdlName();
        $gd = new MdlGudang();

        $gd->addFilter("cabang_id='$cabangID'");
        $tmpGd = $gd->lookupAll()->result();
        // cekHere($this->db->last_query());

        //region gudangId
        $itemsCbNama = array();
        foreach ($tmpGd as $itemGd) {
            $itemsCbNama[$itemGd->id] = $itemGd->nama;
        }
        $itemsCbNama[$this->gudangID] = $this->gudangName;
        //endregion

        $com->addFilter("cabang_id='$cabangID'");

        if (sizeof($accountFilters) > 0) {
            foreach ($accountFilters as $f) {
                $f_ex = explode("=", $f);
                if (!isset($f_ex[1])) {
                    $f_ey = explode(">", $f_ex[0]);

                    if (substr($f_ey[1], 0, 1) == ".") {
                        $com->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                    }
                    else {
                        $com->addFilter($f_ey[0] . ">'" . $this->session->login[$f_ey[1]] . "'");
                    }
                }
                else {
                    // if (substr($f_ex[1], 0, 1) == ".") {
                    //     $com->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                    // }
                    // else {
                    //     $com->addFilter($f_ex[0] . "='" . $this->session->login[$f_ex[1]] . "'");
                    // }
                    // arrPrint($f_ex);
                }
                // cekLime($f_ex . " $f_ey");
            }
        }

        if (isset($_GET['w'])) {
            $com->addFilter("gudang_id='" . $_GET['w'] . "'");
        }

        $tmp = $com->fetchBalances($rekName, $q, $sortBy, $sortMode);
        // cekUngu(get_class($this) . "<br>" . $this->db->last_query());

        $pairedResult = array();
        if (isset($balConfig['pairedModel']) && sizeof($balConfig['pairedModel'])) {
            $mdlName = $balConfig['pairedModel']['mdlName'];
            $this->load->model("Mdls/" . $mdlName);
            $mdl = New $mdlName();
            $mdlResult = $mdl->$balConfig['pairedModel']['mdlMethod']()->result();
            // cekHere($this->db->last_query());
            //            arrPrint($mdlResult);

            if (sizeof($mdlResult) > 0) {
                foreach ($mdlResult as $rSpec) {
                    foreach ($balConfig['pairedModel']['fieldName'] as $key => $val) {
                        $pairedResult[$rSpec->$balConfig['pairedModel']['key']][$key] = isset($rSpec->$val) ? $rSpec->$val : "";
                    }
                }
            }
        }

        // arrPrintWebs($tmp);
        $items = array();
        if (sizeof($tmp) > 0) {
            $tmpRow = array();
            foreach ($tmp as $row) {
                foreach ($balConfig['viewedColumns'] as $key => $label) {
                    $tmpRow[$key] = isset($row->$key) ? $row->$key : "";
                    if (sizeof($pairedResult) > 0) {
                        if (array_key_exists($row->extern_id, $pairedResult)) {
                            $tmpRow["pId"] = $row->extern_id;
                            foreach ($pairedResult[$row->extern_id] as $pkey => $pval) {
                                $tmpRow[$pkey] = $pval;
                            }
                        }
                    }
                }

                $tmpRow['link'] = base_url() . "Ledger/viewMoveDetails/$relName/$rekName/" . $row->extern_id . "?o=$cabangID";
                $items[$row->gudang_id][$row->extern_id] = $tmpRow;
                $produks[$row->extern_id] = $tmpRow;

                $itemsCbId[$row->gudang_id]['gudang_nama'] = $itemsCbNama[$row->gudang_id];
                $itemsCbId[$row->gudang_id]['gudang_id'] = $row->gudang_id;
            }
        }

        // arrPrint($produks);
        //ganti headerFields
        $headerFields = array(
            "rek_id" => "kode",
            //            "kode" => "product code",
            //            "extern_nama" => "item names",
            //
            //
        );
        if (isset($balConfig['pairedModel']['viewedColumns']) && sizeof($balConfig['pairedModel']['viewedColumns'])) {
            foreach ($balConfig['pairedModel']['viewedColumns'] as $k => $v) {
                $headerFields[$k] = $v;
            }
        }
        $headerFields["extern_nama"] = "item names";
        $headerQtyFields = array(
            "qty_" . $defPosition => "balance (QTY)",
        );
        $headerValueFields = array(
            $defPosition => "balance (IDR)",
        );
        if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
            $headerFields = $headerFields + $headerQtyFields;
        }
        if (isset($balConfig['showValue']) && $balConfig['showValue'] == true) {
            $headerFields = $headerFields + $headerValueFields;
        }


        $subTitle = "balances ";
        if ($q != "") {
            $subTitle .= " matched '$q'";
        }

        // arrPrint($tmpGd);
        // arrPrint($itemsCbNama);
        // arrPrint($itemsCbId);
        // arrPrint($gudangs);
        // arrPrint($balConfig);
        // arrPrint($balConfig['viewedColumns']);
        // arrPrint($tmp);
        // arrPrint($items);
        // arrPrint($headerFields);
        $fieldToshows = array(
            "pId" => "pID",
            "rek_id" => "kode",
            "kode" => "product code",
            "extern_nama" => "item names",
        );
        $fieldAttrHeader = array(
            // "no" => "class='text-center text-uppercase bg-info' rowspan='2'",
            "pId" => "class='text-center text-uppercase bg-info valign-m' rowspan='2'",
            "extern_nama" => "class='text-center text-uppercase bg-info valign-m' rowspan='2'",
            "rek_id" => "class='text-center text-uppercase bg-info valign-m' rowspan='2'",
            "kode" => "class='text-center text-uppercase bg-info valign-m' rowspan='2'",
        );

        $subHeaders = array(
            "balance (QTY)" => "class='text-center text-uppercase bg-info'",
            "balance (IDR)" => "class='text-center text-uppercase bg-info'",
        );
        //region penyusun header table nganbil dari config
        $specs_0 = array();
        $mainHeaders_00 = array();
        $specs_0["no"] = 0;
        foreach ($fieldToshows as $field => $fieldToshow) {
            $fAttr = isset($fieldAttrHeader[$field]) ? $fieldAttrHeader[$field] : "-";
            $specs_0[$fieldToshow] = $fAttr;

            $mainHeaders_00 = $specs_0;
            $rNo["no"] = $fAttr;
        }
        foreach ($itemsCbId as $itemCbs) {
            // $mainHeaders_1 = array("cabang" => "class='text-center bg-info' colspan='4'");
            $mainHeaders_1[$itemCbs['gudang_nama']] = "class='text-center bg-info' colspan='2'";
        }
        $mainHeaders_0 = array_replace($mainHeaders_00, $rNo);
        // $mainHeaders_1 = isset($mdlNameS[$item]['mainHeaders_1']) ? $mdlNameS[$item]['mainHeaders_1'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        // $mainHeaders_1 = array("cabang" => "class='text-center bg-info' colspan='4'");
        $mainHeaders = array_merge($mainHeaders_0, $mainHeaders_1);
        // $mainHeaders = $mainHeaders_0;
        // $subHeaders = isset($mdlNameS[$item]['subHeaders']) ? $mdlNameS[$item]['subHeaders'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        //endregion
        $subFields = array(
            "qty_debet" => array(
                "label" => "balance (QTY)",
                "attr" => "class='text-right'",
                "format" => "formatField2",
            ),
            "debet" => array(
                "label" => "balance (IDR)",
                "attr" => "class='text-right'",
                "format" => "formatField2",
            ),
        );
        foreach ($subFields as $field => $fChilds) {
            $sfields[] = $field;
            isset($fChilds['label']) ? $sfieldToshows[$field] = $fChilds['label'] : "";
            isset($fChilds['attr']) ? $sfieldAttr[$field] = $fChilds['attr'] : "";
            isset($fChilds['attrHeader']) ? $sfieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            isset($fChilds['link']) ? $sfieldLink[$field] = $fChilds['link'] : "";
            isset($fChilds['format']) ? $sfieldFormat[$field] = $fChilds['format'] : "";
        }
        foreach ($items as $cb_id => $item) {
            // arrPrint($item);
            foreach ($produks as $pId => $item2) {
                // $gspecs["id"]['value'] = 99;
                // $gspecs["id"]['attr'] = "class='text-center'";
                foreach ($sfieldToshows as $dField => $lField) {
                    // cekHitam("$dField");
                    $sattr = $sfieldAttr[$dField];
                    $svalue = isset($item[$pId][$dField]) ? $item[$pId][$dField] : 0;
                    $svalue_f = isset($sfieldFormat[$dField]) ? $sfieldFormat[$dField]($dField, $svalue) : $svalue;

                    $gspecs[$pId][$cb_id][$dField . "_$cb_id"]['value'] = $svalue_f;
                    $gspecs[$pId][$cb_id][$dField . "_$cb_id"]['attr'] = $sattr;
                }
            }
        }
        // arrPrint($gspecs);
        // arrPrint($produks);
        $no = 0;
        $specs = array();
        foreach ($produks as $proId => $item) {
            $no++;
            $specs["no"]['value'] = $no;
            $specs["no"]['attr'] = "class='text-right'";
            // $specs["id"]['value'] = 99;
            // $specs["id"]['attr'] = "class='text-center'";
            foreach ($fieldToshows as $field => $fieldToshow) {
                // cekHitam($field);
                $specs[$field]['value'] = isset($item[$field]) ? $item[$field] : "-";
                $specs[$field]['attr'] = "class='text-left'";
            }
            foreach ($gspecs[$proId] as $cId => $itemCbs) {

                foreach ($itemCbs as $filedCb => $itemCb) {

                    $gdSpecs[$filedCb] = $itemCb;
                }
                // arrPrint($itemCbs);
            }
            $bodies[] = $specs + $gdSpecs;
        }
        // arrPrint($bodies);
        $footers = array();
        $data = array(
            // "mode" => "saldo",
            "mode" => "persediaan",
            "title" => "$rekName",
            "subTitle" => $subTitle,
            "items" => $items,
            "cabangs" => $itemsCbId,
            //            "headerFields" => $balConfig['viewedColumns'],
            "rowLoop" => sizeof($itemsCbId),
            "headerFields" => $headerFields,
            "mainHeaders" => $mainHeaders,
            "subHeaders" => $subHeaders,
            "bodies" => $bodies,
            "footers" => $footers,

            "thisPage" => $thisPage,
            "thisURL" => $thisURL,
            "q" => $q,
            //            "inspectTarget_mutasi" => base_url() . "Ledger/viewMoves_l2/$relName/$rekName/",
        );
        //endregion


        $this->load->view("ledger", $data);


    }

    public function viewMoves_l1()
    {
        $relName = $this->uri->segment(3);
        $rekName = urldecode($this->uri->segment(4));

        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];
        $transaksiID = (isset($_GET['trID']) && $_GET['trID'] > 0) ? "&trID=" . $_GET['trID'] : "";
        $transaksiIDStyle = (isset($_GET['trID']) && $_GET['trID'] > 0) ? array($_GET['trID'] => "font-size:18px;font-weight:bold;") : array();

        $defPosition = detectRekDefaultPosition($rekName);
        $opPosition = $defPosition == "kredit" ? "debet" : "kredit";

        $balConfig = isset($this->config->item('accountMoveColumns')[$relName]) ? $this->config->item('accountMoveColumns')[$relName] : array();
        $accountFilters = isset($this->config->item('accountBalanceColumns')[$relName]['viewFilters']) ? $this->config->item('accountBalanceColumns')[$relName]['viewFilters'] : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();

        $linkKolomConfig = isset($this->config->item('accountMoveColumns')[$relName]['linkToDetail']) ? $this->config->item('accountMoveColumns')[$relName]['linkToDetail'] : array();
        $linkDetailConfig = isset($this->config->item('accountMoveColumns')[$relName]['baselink']) ? $this->config->item('accountMoveColumns')[$relName]['baselink'] : array();


        $mdlName = "Com" . $relName;

        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $mdlName);
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlCabang");

        $cb = New MdlCabang();
        $tmpCb = $cb->lookupAll()->result();
        $arrCabang = array();
        if (sizeof($tmpCb) > 0) {
            foreach ($tmpCb as $spec) {
                $arrCabang[$spec->id] = $spec->nama;
            }
        }
        $cabangNama_f = isset($arrCabang[$cabangID]) ? "(" . $arrCabang[$cabangID] . ")" : "";

        $com = new $mdlName();
        $tr = new MdlTransaksi();


        $com->addFilter("cabang_id='$cabangID'");

        if (sizeof($accountFilters) > 0) {
            foreach ($accountFilters as $f) {
                $f_ex = explode("=", $f);
                if (!isset($f_ex[1])) {
                    $f_ey = explode(">", $f_ex[0]);
                    if (substr($f_ey[1], 0, 1) == ".") {
                        $com->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                    }
                    else {
                        $com->addFilter($f_ey[0] . ">'" . $this->session->login[$f_ey[1]] . "'");
                    }
                }
                else {
                    if (substr($f_ex[1], 0, 1) == ".") {
                        $com->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                    }
                    else {
                        $com->addFilter($f_ex[0] . "='" . $this->session->login[$f_ex[1]] . "'");
                    }
                }
            }
        }


        if (!isset($_GET['date1']) && !isset($_GET['date2'])) {
            $limit = 20;
            $this->db->limit("$limit");
            $this->db->order_by("id", "DESC");

            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

            $subTitle_date = "";
            $subSubTitle_date = " <span style='font-size:12px;font-style:italic;'>(mutasi $limit terakhir)</span>";
        }
        else {
            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");
            $this->db->order_by("id", "DESC");
            $subTitle_date = lgTranslateTime($date1) . " - " . lgTranslateTime($date2);
            $subSubTitle_date = "";
        }


        $tmp = $com->fetchMoves($rekName);
        //        cekkuning($this->db->last_query());


        //region auto group in - out
        $arrProds = array();
        $tmpTrIds = array();
        foreach ($tmp as $temX) {
            $transaksi_id = $temX->transaksi_id;
            $position = $temX->debet > 0 ? "debet" : "kredit";
            $val = $temX->debet > 0 ? $temX->debet : $temX->kredit;
            $detectRek = detectRekByPosition($rekName, $val, $position);
            //            cekHere($temX->jenis);
            if ($detectRek > 0) {
                $arrMovedDetail["in"][$temX->jenis] = $position;
                if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                    $arrMovedDetail["in_qty"][$temX->jenis] = "qty_" . $position;
                    $newSelect = "qty_" . $position;
                    $qtyVal = $temX->$newSelect;
                }
                else {
                    $qtyVal = 0;
                }

            }
            else {
                $arrMovedDetail["out"][$temX->jenis] = $position;
                if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                    $arrMovedDetail["out_qty"][$temX->jenis] = "qty_" . $position;
                    $newSelect = "qty_" . $position;
                    $qtyVal = $temX->$newSelect;
                }
                else {
                    $qtyVal = 0;
                }

            }

            $arrProds[][$temX->jenis] = array(
                "$position" => "$val",
                "qty_" . $position => $qtyVal,
            );

            $tmpTrIds[$transaksi_id] = 1;
        }
        $trIds = array_keys($tmpTrIds);
        //endregion

        // region transaksi
        $listId = implode("','", $trIds);
        $tr->setFilters(array());
        $tr->addFilter("id in ('" . $listId . "')");
        $tmpTrs = $tr->lookupAll()->result();
        // cekLime($this->db->last_query());
        // arrPrint($tmpTrs);

        // endregion transaksi


        if (sizeof($balConfig)) {
            $headerFields = $balConfig['viewedColumns'];

        }
        else {
            die("balance config for $relName is not yet defined");
        }

        if ($cabangID == CB_ID_PUSAT) {
            $headerFields = array(
                "dtime" => "time",
                "jenis" => "note",
                "nomer_top" => "reference number",
                "transaksi_no" => "receipt number",
                "review_details" => "&nbsp;",
                "suppliers_nama" => "vendor",
                "cabang_nama" => "branch",
                "oleh_nama" => "by",
            );
        }
        else {
            $headerFields = array(
                "dtime" => "time",
                "jenis" => "note",
                "nomer_top" => "reference number",
                "transaksi_no" => "receipt number",
                "review_details" => "&nbsp;",
                "customers_nama" => "customer",
                "cabang_nama" => "branch",
                "oleh_nama" => "by",
            );
        }
        $jenisAliases = arrCodeAliasing($cabangID);

        // region builder data transaksi yg perlu muncul
        $trDatas = array();
        $trDatasLink = array();
        foreach ($tmpTrs as $tmpTr) {
            $datas = array();
            $datasLink = array();
            foreach ($headerFields as $hField => $hAlias) {
                $$hField = key_exists($hField, $tmpTr) ? $tmpTr->$hField : "";
                if (array_key_exists($hField, $tmpTr)) {
                    //                    $datas[$hField] = array_key_exists($tmpTr->$hField, $jenisAliases) ? $jenisAliases[$tmpTr->$hField] : $tmpTr->$hField;
                    if (array_key_exists($tmpTr->$hField, $jenisAliases)) {
                        $datas[$hField] = $jenisAliases[$tmpTr->$hField];
                    }
                    else {
                        $datas[$hField] = $tmpTr->$hField;
                    }
                }

                if (array_key_exists($hField, $linkKolomConfig)) {
                    $comPembantu = isset($linkKolomConfig[$hField]['rekening'][$rekName]['com']) ? $linkKolomConfig[$hField]['rekening'][$rekName]['com'] : $accountChilds[$rekName];
                    $rekName_new = isset($linkKolomConfig[$hField]['rekening'][$rekName]['rek']) ? $linkKolomConfig[$hField]['rekening'][$rekName]['rek'] : $rekName;
                    $link = base_url() . "$linkDetailConfig" . $comPembantu . "/$rekName_new/" . $tmpTr->$linkKolomConfig[$hField]['key'] . "?o=$cabangID&trID=" . $tmpTr->id;
                    $datasLink[$hField] = $link;
                }
            }
            $trDatas[$tmpTr->id] = $datas;
            $trDatasLink[$tmpTr->id] = $datasLink;
        }

        // endregion builder data transaksi yg perlu muncul


        $headerQtyFields = array(

            "qty_" . $defPosition . "_awal" => "prev (QTY)",
            "qty_" . $defPosition => "in (QTY)",
            "qty_" . $opPosition => "out (QTY)",
            "qty_" . $defPosition . "_akhir" => "balance (QTY)",

        );
        $headerValueFields = array(

            $defPosition . "_awal" => "prev (IDR)",
            $defPosition => "in (IDR)",
            $opPosition => "out (IDR)",
            $defPosition . "_akhir" => "balance (IDR)",
        );
        if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
            $headerFields = $headerFields + $headerQtyFields;
        }
        if (isset($balConfig['showValue']) && $balConfig['showValue'] == true) {
            $headerFields = $headerFields + $headerValueFields;
        }


        $items = array();
        if (sizeof($tmp) > 0) {
            krsort($tmp);
            foreach ($tmp as $row) {
                $subs = array();
                foreach ($headerFields as $key => $label) {
                    if (array_key_exists($key, $row)) {
                        $subs[$key] = key_exists($row->$key, $jenisAliases) ? $jenisAliases[$row->$key] : $row->$key;
                    }
                    if ($key == "review_details") {
                        $subs[$key] = $row->transaksi_id;
                    }
                }
                $subs_r = array_replace($subs, $trDatas[$row->transaksi_id]);
                $subs_r["transaksi_id"] = $row->transaksi_id;
                $items[] = $subs_r;


            }
        }


        $data = array(
            "mode" => "mutasi",
            "title" => isset($accountAlias[$rekName]) ? $accountAlias[$rekName] : $rekName,
            "subTitle" => "  $subTitle_date &nbsp; $cabangNama_f $subSubTitle_date",
            "items" => $items,

            "headerFields" => $headerFields,
            "filters" => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "?o=$cabangID$transaksiID",
            "summary" => isset($this->summaryKey) ? $this->summaryKey : array(),
            "addStyle" => isset($transaksiIDStyle) ? $transaksiIDStyle : array(),
            "addDetailLink" => sizeof($trDatasLink) > 0 ? $trDatasLink : array(),
        );
        $this->load->view("ledger", $data);


    }

    public function viewMoves_l2()
    {
        $relName = $this->uri->segment(3);
        $rekName = urldecode($this->uri->segment(4));
        $externID = $this->uri->segment(5);

        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];


        $defPosition = detectRekDefaultPosition($rekName);
        $opPosition = $defPosition == "kredit" ? "debet" : "kredit";

        $balConfig = isset($this->config->item('accountMoveColumns')[$relName]) ? $this->config->item('accountMoveColumns')[$relName] : array();
        $accountFilters = isset($this->config->item('accountBalanceColumns')[$relName]['viewFilters']) ? $this->config->item('accountBalanceColumns')[$relName]['viewFilters'] : array();
        //matiHEre($relName);
        $mdlName = "Com" . $relName;

        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $mdlName);
        $com = new $mdlName();

        $com->addFilter("cabang_id='$cabangID'");

        if (sizeof($accountFilters) > 0) {
            foreach ($accountFilters as $f) {
                $f_ex = explode("=", $f);
                if (!isset($f_ex[1])) {
                    $f_ey = explode(">", $f_ex[0]);
                    if (substr($f_ey[1], 0, 1) == ".") {
                        $com->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                    }
                    else {
                        $com->addFilter($f_ey[0] . ">'" . $this->session->login[$f_ey[1]] . "'");
                    }
                }
                else {
                    if (substr($f_ex[1], 0, 1) == ".") {
                        $com->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                    }
                    else {
                        $com->addFilter($f_ex[0] . "='" . $this->session->login[$f_ex[1]] . "'");
                    }
                }
            }
        }


        $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
        $this->db->where("fulldate>='" . $date1 . "'");
        $this->db->where("fulldate<='" . $date2 . "'");
        $tmp = $com->fetchMoves($rekName, $externID);
        //        cekkuning($this->db->last_query());
        //        arrPrint($tmp);
        //        die();


        if (sizeof($balConfig)) {
            $headerFields = $balConfig['viewedColumns'];


        }
        else {
            die("balance config for $relName is not yet defined");
        }


        $headerFields = array(
            //            "extern_nama"=>"item names",
            "dtime" => "time",
            "transaksi_no" => "receipt number",
            //            "keterangan"=>"description",
            //            "jenis"=>"activity",
        );
        $headerQtyFields = array(

            "qty_" . $defPosition . "_awal" => "prev (QTY)",
            "qty_" . $defPosition => "in (QTY)",
            "qty_" . $opPosition => "out (QTY)",
            "qty_" . $defPosition . "_akhir" => "balance (QTY)",

        );
        $headerValueFields = array(

            $defPosition . "_awal" => "prev (IDR)",
            $defPosition => "in (IDR)",
            $opPosition => "out (IDR)",
            $defPosition . "_akhir" => "balance (IDR)",
        );
        if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
            $headerFields = $headerFields + $headerQtyFields;
        }
        if (isset($balConfig['showValue']) && $balConfig['showValue'] == true) {
            $headerFields = $headerFields + $headerValueFields;
        }

        //        arrprint($headerFields);


        $title = "";
        $items = array();
        if (sizeof($tmp > 0)) {
            foreach ($tmp as $row) {
                $subs = array();
                foreach ($headerFields as $key => $label) {
                    $subs[$key] = $row->$key;
                }


                $items[] = $subs;
                $title = $row->extern_nama;
            }
        }


        //        arrprint($headerFields);

        //region prepare params to viewer
        $data = array(
            "mode" => "mutasi",
            "title" => "$title",
            "subTitle" => "changes",
            "items" => $items,
            //            "headerFields" => $balConfig['viewedColumns'],
            "headerFields" => $headerFields,
            "filters" => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "?o=$cabangID",
        );
        //endregion

        //        arrprint($data);
        $this->load->view("ledger", $data);

    }

    public function viewMoveDetails()
    {
        $arrMovedDetail = array();
        $relName = $this->uri->segment(3);
        $rekName = urldecode($this->uri->segment(4));
        $externID = $this->uri->segment(5);
        $transaksiIDStyle = (isset($_GET['trID']) && $_GET['trID'] > 0) ? array($_GET['trID'] => "font-size:18px;font-weight:bold;") : array();

        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];

        $defPosition = detectRekDefaultPosition($rekName);
        $opPosition = $defPosition == "kredit" ? "debet" : "kredit";

        $balConfig = isset($this->config->item('accountMoveColumns')[$relName]) ? $this->config->item('accountMoveColumns')[$relName] : array();
        $accountFilters = isset($this->config->item('accountBalanceColumns')[$relName]['viewFilters']) ? $this->config->item('accountBalanceColumns')[$relName]['viewFilters'] : array();
        $accountChildSourcesMdl = isset($this->config->item('accountChildSources')[$rekName]) ? $this->config->item('accountChildSources')[$rekName] : null;

        $mdlName = "Com" . $relName;
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $mdlName);
        $this->load->model("MdlTransaksi");
        $com = new $mdlName();
        $tr = new MdlTransaksi();
        $com->addFilter("cabang_id='$cabangID'");
        $com->addFilter("jenis<>''");
        if (sizeof($accountFilters) > 0) {
            foreach ($accountFilters as $f) {
                $f_ex = explode("=", $f);
                if (!isset($f_ex[1])) {
                    $f_ey = explode(">", $f_ex[0]);
                    if (substr($f_ey[1], 0, 1) == ".") {
                        $com->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                    }
                    else {
                        $com->addFilter($f_ey[0] . ">'" . $this->session->login[$f_ey[1]] . "'");
                    }
                }
                else {
                    if (substr($f_ex[1], 0, 1) == ".") {
                        $com->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                    }
                    else {
                        $com->addFilter($f_ex[0] . "='" . $this->session->login[$f_ex[1]] . "'");
                    }
                }
            }
        }


        if (!isset($_GET['date1']) && !isset($_GET['date2'])) {
            $limit = 20;
            $this->db->limit("$limit");
            $this->db->order_by("id", "DESC");

            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-01");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

            $subTitle_date = "";
            $subSubTitle_date = " <span style='font-size:12px;font-style:italic;'>(mutasi $limit terakhir)</span>";
        }
        else {
            $this->db->order_by("id", "DESC");

            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-01");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");

            $subTitle_date = lgTranslateTime($date1) . " - " . lgTranslateTime($date2);
            $subSubTitle_date = "";
        }


        $tmp = $com->fetchMoves($rekName, $externID);
        //showLast_query("biru");

        $titleTop = "";
        if ($accountChildSourcesMdl != null) {
            $this->load->model("Mdls/" . $accountChildSourcesMdl);
            $cmdl = New $accountChildSourcesMdl();
            $rslt = $cmdl->lookupByID($externID)->result();

            $rsltCode = isset($rslt[0]->kode) ? $rslt[0]->kode : "";
            $rsltNama = isset($rslt[0]->nama) ? $rslt[0]->nama : "";
            $titleTop = "$rsltCode $rsltNama";
        }


        //region auto group in - out
        $saldo_qty_berjalan = 0;
        $saldo_value_berjalan = 0;

        $arrProds = array();
        $tmpTrIds = array();
        if (sizeof($tmp) > 0) {
            krsort($tmp);
            //            arrPrint($tmp);
            foreach ($tmp as $temX) {
                $transaksi_id = $temX->transaksi_id;
                $position = $temX->debet > 0 ? "debet" : "kredit";
                $val = $temX->debet > 0 ? $temX->debet : $temX->kredit;
                $detectRek = detectRekByPosition($rekName, $val, $position);
                if ($detectRek > 0) {
                    $arrMovedDetail["in"][$temX->jenis] = $position;
                    if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                        $arrMovedDetail["in_qty"][$temX->jenis] = "qty_" . $position;
                        $newSelect = "qty_" . $position;
                        $qtyVal = $temX->$newSelect;
                    }
                    else {
                        $qtyVal = 0;
                    }
                }
                else {
                    $arrMovedDetail["out"][$temX->jenis] = $position;
                    if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                        $arrMovedDetail["out_qty"][$temX->jenis] = "qty_" . $position;
                        $newSelect = "qty_" . $position;
                        $qtyVal = $temX->$newSelect;
                    }
                    else {
                        $qtyVal = 0;
                    }
                }

                $arrProds[][$temX->jenis] = array(
                    "$position" => "$val",
                    "qty_" . $position => $qtyVal,
                );
                $tmpTrIds[$transaksi_id] = $transaksi_id;


                // mengurusi saldo berjalan...
                $saldo_qty_berjalan += ($temX->qty_debet - $temX->qty_kredit);
                $saldo_value_berjalan += ($temX->debet - $temX->kredit);
                $saldoBerjalan[$temX->id]['qty'] = $saldo_qty_berjalan;
                $saldoBerjalan[$temX->id]['value'] = $saldo_value_berjalan;

            }
            $trIds = array_keys($tmpTrIds);
        }
        //endregion
        //arrPrint($saldoBerjalan);


        // region transaksi
        $listId = implode("','", $trIds);
        $tr->setFilters(array());
        $tr->addFilter("id in ('" . $listId . "')");
        $tmpTrs = $tr->lookupAll()->result();
        //        cekLime($this->db->last_query());
        // arrPrint($tmpTrs);

        // endregion transaksi

        if (sizeof($balConfig)) {
            $headerFields = $balConfig['viewedColumns'];
            $arrHeaderBlacklist = array(
                "kredit",
                "debet",
                "qty_kredit",
                "qty_debet",
                "qty_kredit_akhir",
                "qty_debet_akhir",
                "kredit_akhir",
                "debet_akhir",
            );
            foreach ($headerFields as $key => $val) {
                if (in_array($key, $arrHeaderBlacklist)) {
                    unset($headerFields[$key]);
                }
            }
        }
        else {
            die("balance config for $relName is not yet defined");
        }


        $headerFields["ids_his"] = "reference number";
        $headerFields["transaksi_no"] = "receipt number";
        $headerFields["review_details"] = "&nbsp;";


        $jenisAliases = arrCodeAliasing($cabangID);


        // region builder data transaksi yg perlu muncul
        $trDatas = array();
        foreach ($tmpTrs as $tmpTr) {
            $datas = array();
            foreach ($headerFields as $hField => $hAlias) {
                //                $$hField = array_key_exists($hField, $tmpTr) ? $tmpTr->$hField : "";
                if (array_key_exists($hField, $tmpTr)) {
                    if (array_key_exists($tmpTr->$hField, $jenisAliases)) {
                        $datas[$hField] = $jenisAliases[$tmpTr->$hField];
                    }
                    else {
                        if (isset($tmpTr->$hField) && !is_numeric($tmpTr->$hField)) {
                            if ($tmpTr->$hField === base64_encode(base64_decode($tmpTr->$hField))) {
                                $datas[$hField] = isset($tmpTr->$hField) ? unserialize(base64_decode($tmpTr->$hField)) : "";
                                //                                $rslt = isset($tmpTr->$hField) ? unserialize(base64_decode($tmpTr->$hField)) : NULL;
                                //                                if(strlen($tmpTr->$hField) > 50){
                                //                                    $datas[$hField] = $rslt;
                                //                                }
                                //                                else{
                                //                                    $datas[$hField] = $tmpTr->$hField;
                                //                                }
                            }
                            else {
                                $datas[$hField] = $tmpTr->$hField;
                            }

                        }
                        else {
                            $datas[$hField] = $tmpTr->$hField;
                            //                            cekPink("$hField diambil dari tmpTr $hField");
                        }
                    }
                }
            }
            $trDatas[$tmpTr->id] = $datas;
        }

        // endregion builder data transaksi yg perlu muncul

        $headerQtyFields = array(
            "qty_" . $defPosition . "_awal" => "prev (QTY)",
            "in_qty" => "in qty",
            "out_qty" => "out qty",

            "saldo_qty_berjalan" => "saldo (QTY)",

            "qty_" . $defPosition . "_akhir" => "balance (QTY)",
        );
        $headerValueFields = array(
            $defPosition . "_awal" => "prev (IDR)",
            "in" => "in (IDR)",
            "out" => "out (IDR)",

            "saldo_berjalan" => "saldo (IDR)",

            $defPosition . "_akhir" => "balance (IDR)",
        );
        if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
            $headerFields = $headerFields + $headerQtyFields;
        }
        if (isset($balConfig['showValue']) && $balConfig['showValue'] == true) {
            $headerFields = $headerFields + $headerValueFields;
        }


        //region buld yIndex
        //        arrPrint($arrMovedDetail);
        $itemsCek = array();
        $items0 = array();
        //        krsort($arrProds);
        foreach ($arrProds as $row0) {
            $temX = array();
            foreach ($arrMovedDetail as $yparent => $ySpec) {
                $subs0 = array();
                foreach ($ySpec as $jn => $colloumb) {

                    if (isset($row0[$jn][$colloumb])) {
                        $val = $row0[$jn][$colloumb];
                    }
                    else {
                        $val = 0;
                    }
                    $subs0[$jn] = $val;

                    $subItemCek[$yparent] = $val;
                }
                $temX[$yparent] = $subs0;
            }
            $items0[] = $temX;
            $itemsCek[] = $subItemCek;
        }
        //endregion


        //region label detil transaksi
        $childHeaderLabels = $this->config->item('heTransaksi_ui');
        $detailsLabel = array();
        foreach ($childHeaderLabels as $tempSpec) {
            $xxTemp = $tempSpec["steps"];
            foreach ($xxTemp as $tempLabels) {
                $keyLabel = $tempLabels["target"];
                $keyValue = $tempLabels["label"];
                $detailsLabel[$keyLabel] = $keyValue;
            }
        }
        //endregion


        $title = "";
        // data transaksi dab
        $items = array();
        if (sizeof($tmp) > 0) {
            krsort($tmp);
            //            arrPrint($tmp);
            foreach ($tmp as $row) {
                if (isset($trDatas[$row->transaksi_id])) {
                    foreach ($trDatas[$row->transaksi_id] as $key => $val) {
                        $row->$key = $val;
                    }
                }

                $rekening = $row->rekening;
                $position = detectRekDefaultPosition($rekening);
                switch ($position) {
                    case "debet":
                        if ($row->kredit_awal > 0) {
                            $row->debet_awal = $row->kredit_awal * -1;
                            $row->kredit_awal = 0;
                        }
                        if ($row->kredit_akhir > 0) {
                            $row->debet_akhir = $row->kredit_akhir * -1;
                            $row->kredit_akhir = 0;
                        }
                        break;
                    case "kredit":
                        if ($row->debet_awal > 0) {
                            $row->kredit_awal = $row->debet_awal * -1;
                            $row->debet_awal = 0;
                        }
                        if ($row->debet_akhir > 0) {
                            $row->kredit_akhir = $row->debet_akhir * -1;
                            $row->debet_akhir = 0;
                        }
                        break;
                }

                //                $subs = array();
                //                foreach ($headerFields as $key => $label) {
                //                    if (array_key_exists($key, $row)) {
                //                        $subs[$key] = array_key_exists($row->$key, $jenisAliases) ? $jenisAliases[$row->$key] : $row->$key;
                //                    }
                //                }
                //                $subs_r = array_replace($subs, isset($trDatas[$row->transaksi_id]) ? $trDatas[$row->transaksi_id] : array());

                $subs_r = array();
                foreach ($headerFields as $key => $label) {
                    if (array_key_exists($key, $row)) {
                        if ((isset($row->$key)) && (!is_numeric($row->$key))) {
                            if ($row->$key === base64_encode(base64_decode($row->$key))) {

                                $subs_r[$key] = unserialize(base64_decode($row->$key));

                            }
                            else {
                                $subs_r[$key] = $row->$key;
                            }
                        }
                        else {
                            $subs_r[$key] = $row->$key;
                        }
                    }
                }


                $subs_r['saldo_qty_berjalan'] = isset($saldoBerjalan[$row->id]) ? $saldoBerjalan[$row->id]['qty'] : 0;
                $subs_r['saldo_berjalan'] = isset($saldoBerjalan[$row->id]) ? $saldoBerjalan[$row->id]['value'] : 0;


                $subs_r['transaksi_id'] = $row->transaksi_id;
                $subs_r['review_details'] = $row->transaksi_id;
                $items[] = $subs_r;
                //arrPrint($subs_r);

                $title = $row->extern_nama;
            }
        }
        //arrPrint($items);

        $data = array(
            "mode" => "mutasiDetails",
            "title" => $rekName . " &nbsp;" . $titleTop,
            "subTitle" => " $subTitle_date $subSubTitle_date",
            "items" => $items,
            "items2" => $items0,
            "headerFields" => $headerFields,
            "headerFields2" => $arrMovedDetail,
            "filters" => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "detailsLabels" => $detailsLabel,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "?o=$cabangID",

            "itemsCek" => $itemsCek,
            "summary" => isset($this->summaryKey) ? $this->summaryKey : array(),
            "addStyle" => isset($transaksiIDStyle) ? $transaksiIDStyle : array(),
        );

        $this->load->view("ledger", $data);

    }

    // mutasi dengan qty, @price, total value...
    public function viewMoveDetails_v1()
    {
        $arrMovedDetail = array();
        $relName = $this->uri->segment(3);
        $rekName = urldecode($this->uri->segment(4));
        $externID = $this->uri->segment(5);

        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];


        $defPosition = detectRekDefaultPosition($rekName);
        $opPosition = $defPosition == "kredit" ? "debet" : "kredit";

        $balConfig = isset($this->config->item('accountMoveColumns')[$relName]) ? $this->config->item('accountMoveColumns')[$relName] : array();
        $headLooping = isset($this->config->item('accountMoveColumns')[$relName]['headerLooping']) ? $this->config->item('accountMoveColumns')[$relName]['headerLooping'] : array();
        $accountFilters = isset($this->config->item('accountBalanceColumns')[$relName]['viewFilters']) ? $this->config->item('accountBalanceColumns')[$relName]['viewFilters'] : array();
        $accountChildSourcesMdl = isset($this->config->item('accountChildSources')[$rekName]) ? $this->config->item('accountChildSources')[$rekName] : null;


        $mdlName = "Com" . $relName;

        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $mdlName);
        $this->load->model("MdlTransaksi");
        $com = new $mdlName();
        $tr = new MdlTransaksi();

        $com->addFilter("cabang_id='$cabangID'");

        if (sizeof($accountFilters) > 0) {
            foreach ($accountFilters as $f) {
                $f_ex = explode("=", $f);
                if (!isset($f_ex[1])) {
                    $f_ey = explode(">", $f_ex[0]);
                    if (substr($f_ey[1], 0, 1) == ".") {
                        $com->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                    }
                    else {
                        $com->addFilter($f_ey[0] . ">'" . $this->session->login[$f_ey[1]] . "'");
                    }
                }
                else {
                    if (substr($f_ex[1], 0, 1) == ".") {
                        $com->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                    }
                    else {
                        $com->addFilter($f_ex[0] . "='" . $this->session->login[$f_ex[1]] . "'");
                    }
                }
            }
        }


        if (!isset($_GET['date1']) && !isset($_GET['date2'])) {
            $limit = 20;
            $this->db->limit("$limit");
            $this->db->order_by("id", "DESC");

            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

            $subTitle_date = "";
            $subSubTitle_date = " <span style='font-size:12px;font-style:italic;'>(mutasi $limit terakhir)</span>";
        }
        else {
            $this->db->order_by("id", "DESC");

            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");

            $subTitle_date = lgTranslateTime($date1) . " - " . lgTranslateTime($date2);
            $subSubTitle_date = "";
        }


        $tmp = $com->fetchMoves($rekName, $externID);
        //        cekHere($this->db->last_query() . "<hr> " . __METHOD__);
        //arrPrint($tmp);
        $titleTop = "";
        if ($accountChildSourcesMdl != null) {
            $this->load->model("Mdls/" . $accountChildSourcesMdl);
            $cmdl = New $accountChildSourcesMdl();
            $rslt = $cmdl->lookupByID($externID)->result();
            $rsltCode = isset($rslt[0]->kode) ? $rslt[0]->kode : "";
            $rsltNama = isset($rslt[0]->nama) ? $rslt[0]->nama : "";
            $titleTop = "$rsltCode $rsltNama";
        }


        //region auto group in - out
        $arrProds = array();
        $tmpTrIds = array();
        if (sizeof($tmp) > 0) {
            krsort($tmp);
            foreach ($tmp as $temX) {
                $transaksi_id = $temX->transaksi_id;
                $position = $temX->debet > 0 ? "debet" : "kredit";
                $val = $temX->debet > 0 ? $temX->debet : $temX->kredit;
                $detectRek = detectRekByPosition($rekName, $val, $position);
                //            cekHere($temX->jenis);
                if ($detectRek > 0) {
                    $arrMovedDetail["in"][$temX->jenis] = $position;
                    if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                        $arrMovedDetail["in_qty"][$temX->jenis] = "qty_" . $position;
                        $newSelect = "qty_" . $position;
                        $qtyVal = $temX->$newSelect;
                    }
                    else {
                        $qtyVal = 0;
                    }

                }
                else {
                    $arrMovedDetail["out"][$temX->jenis] = $position;
                    if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                        $arrMovedDetail["out_qty"][$temX->jenis] = "qty_" . $position;
                        $newSelect = "qty_" . $position;
                        $qtyVal = $temX->$newSelect;
                    }
                    else {
                        $qtyVal = 0;
                    }

                }

                $arrProds[$temX->transaksi_id][$temX->jenis] = array(
                    "$position" => "$val",
                    "qty_" . $position => $qtyVal,
                    "harga" => $temX->harga,
                );

                $tmpTrIds[$transaksi_id] = 1;
            }
            $trIds = array_keys($tmpTrIds);
        }
        //endregion


        // region transaksi
        $listId = implode("','", $trIds);
        $tr->setFilters(array());
        $tr->addFilter("id in ('" . $listId . "')");
        $tmpTrs = $tr->lookupAll()->result();
        // cekLime($this->db->last_query());
        // arrPrint($tmpTrs);
        $refDatas = array();
        foreach ($tmpTrs as $tmpTr) {
            $refDatas[$tmpTr->id]["nomer_top"] = $tmpTr->nomer_top;
            $refDatas[$tmpTr->id]["oleh_nama"] = $tmpTr->oleh_nama;
            $refDatas[$tmpTr->id]["cabang_nama"] = $tmpTr->cabang_nama;
            $refDatas[$tmpTr->id]["customers_nama"] = $tmpTr->customers_nama;
            $refDatas[$tmpTr->id]["suppliers_nama"] = $tmpTr->suppliers_nama;
        }
        // endregion transaksi

        if (sizeof($balConfig)) {
            $headerFields = $balConfig['viewedColumns'];

        }
        else {
            die("balance config for $relName is not yet defined");
        }

        if ($cabangID == CB_ID_PUSAT) {
            $headerFields = array(
                "dtime" => array(
                    "label" => "date",
                    // "attrHeader" => "class=''",
                    "attr" => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                "jenis" => array(
                    "label" => "note",
                    // "attrHeader" => "class=''",
                    "attr" => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                "nomer_top" => array(
                    "label" => "reference number",
                    "attrHeader" => "class='text-uppercase'",
                    "attr" => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                    "format" => "formatField",
                ),
                "transaksi_no" => array(
                    "label" => "receip number",
                    // "attrHeader" => "class=''",
                    "attr" => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                "suppliers_nama" => array(
                    "label" => "vendor",
                    "attr" => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                "cabang_nama" => array(
                    "label" => "branch",

                    "attr" => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                "oleh_nama" => array(
                    "label" => "pic by",
                    // "attrHeader" => "class=''",
                    "attr" => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
            );
            $total_colspan = sizeof($headerFields);
        }
        else {
            $headerFields = array(
                "dtime" => array(
                    "label" => "date",
                    // "attrHeader" => "class=''",
                    "attr" => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                "jenis" => array(
                    "label" => "note",
                    // "attrHeader" => "class=''",
                    "attr" => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                "nomer_top" => array(
                    "label" => "reference number",
                    "attrHeader" => "class='text-uppercase'",
                    "format" => "formatField",
                    "attr" => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                "transaksi_no" => array(
                    "label" => "receip number",
                    // "attrHeader" => "class=''",
                    "attr" => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                // "cabang_id"    => "branch",
                "oleh_nama" => array(
                    "label" => "pic by",
                    "attrHeader" => "class='text-uppercase'",
                    "attr" => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                "customers_nama" => array(
                    "label" => "customer",
                    "attrHeader" => "class='text-uppercase'",
                    "attr" => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                // "debet" => "values",
                // "kredit" => "values",
            );
            $total_colspan = sizeof($headerFields);
        }

        $jenisAliases = arrCodeAliasing($cabangID);
        $jmlLooping = sizeof($headLooping);

        // region penyusun / pemecah arai jadi array kecil-kecil
        foreach ($headerFields as $field => $fChilds) {
            $fields[] = $field;
            if (isset($fChilds['label'])) {
                $fieldToshows[$field] = $fChilds['label'];
            }
            if (isset($fChilds['attr'])) {
                $fieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $fieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $fieldFormat[$field] = $fChilds['format'];
            }

        }
        // endregion penyusun 


        // region builder data transaksi yg perlu muncul
        $trDatas = array();
        foreach ($tmpTrs as $tmpTr) {
            $datas = array();
            foreach ($fieldToshows as $hField => $hAlias) {
                $$hField = key_exists($hField, $tmpTr) ? $tmpTr->$hField : "";

                if (array_key_exists($hField, $tmpTr)) {
                    $datas[$hField] = key_exists($tmpTr->$hField, $jenisAliases) ? $jenisAliases[$tmpTr->$hField] : $tmpTr->$hField;
                }
            }
            $trDatas[$tmpTr->id] = $datas;
        }
        //        arrPrint($trDatas);
        // endregion builder data transaksi yg perlu muncul

        $headerQtyFields = array(

            "qty_a" => array(
                "label" => "saldo awal",
                "attr" => "class='text-center' colspan='$jmlLooping'",
            ),

            "in_qty" => array(
                "label" => "in",
                "attr" => "class='text-center' colspan='$jmlLooping'",
            ),
            "out_qty" => array(
                "label" => "out",
                "attr" => "class='text-center' colspan='$jmlLooping'",
            ),
            "qty_" . $defPosition . "_akhir" => array(
                "label" => "saldo",
                "attr" => "class='text-center' colspan='$jmlLooping'",
            ),
            // "avg_price" => array(
            //     "label" => "avg price",
            //     "attr" => "class='text-center valign-m' rowspan='2'",
            // ),


        );
        $headerValueFields = array(
            $defPosition . "_awal" => "prev (IDRs)",
            "in" => "in (IDR)",
            "out" => "out (IDR)",
            $defPosition . "_akhir" => "balance (IDR)",
        );
        if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
            $headerFields = $headerFields + $headerQtyFields;
        }
        if (isset($balConfig['showValue']) && $balConfig['showValue'] == true) {
            $headerFields = $headerFields + $headerValueFields;
        }


        //region buld yIndex
        //        arrPrint($arrMovedDetail);
        //        arrPrint($arrProds);
        $itemsCek = array();
        $items0 = array();
        krsort($arrProds);
        foreach ($arrProds as $row0) {
            $temX = array();
            foreach ($arrMovedDetail as $yparent => $ySpec) {
                $subs0 = array();
                foreach ($ySpec as $jn => $colloumb) {

                    if (isset($row0[$jn][$colloumb])) {
                        $val = $row0[$jn][$colloumb];
                    }
                    else {
                        $val = 0;
                    }
                    $subs0[$jn] = $val;

                    $subItemCek[$yparent] = $val;
                }
                $temX[$yparent] = $subs0;
            }
            $items0[] = $temX;
            $itemsCek[] = $subItemCek;
        }
        //        krsort($items0);
        //        arrPrint($items0);
        //        arrPrint($itemsCek);
        //endregion


        //region label detil transaksi
        $childHeaderLabels = $this->config->item('heTransaksi_ui');
        //        arrPrint($childHeaderLabels);
        $detailsLabel = array();
        foreach ($childHeaderLabels as $tempSpec) {
            $xxTemp = $tempSpec["steps"];
            //            arrPrint($xxTemp);

            foreach ($xxTemp as $tempLabels) {

                $keyLabel = $tempLabels["target"];
                $keyValue = $tempLabels["label"];

                $detailsLabel[$keyLabel] = $keyValue;
            }
        }

        //        arrPrint($detailsLabel);
        //endregion


        $arrCodeAliasing = arrCodeAliasing($cabangID);

        $title = "";
        $items = array();
        if (sizeof($tmp) > 0) {
            krsort($tmp);
            $mainDatas2 = array();
            foreach ($tmp as $row) {

                $subs = array();
                foreach ($fieldToshows as $key => $label) {

                    $refDatas_2 = $refDatas[$row->transaksi_id];
                    //arrPrint($refDatas_2);

                    if (array_key_exists($key, $row)) {
                        $subs[$key] = array_key_exists($row->$key, $jenisAliases) ? $jenisAliases[$row->$key] : $row->$key;

                    }
                    else {
                        //                        cekUngu("-- $key -- " . $row->$key);
                    }

                    if ($key == 'jenis') {
                        $mValue = $arrCodeAliasing[$row->$key];
                    }
                    else {
                        $mValue = isset($row->$key) ? $row->$key : 0;
                    }


                    $mainDatas[$key] = isset($row->$key) ? $mValue : $refDatas_2[$key];
                }

                //                $subs_r = array_replace($subs, isset($trDatas[$row->transaksi_id]) ? $trDatas[$row->transaksi_id] : array());

                $jenis = $row->jenis;
                $saldo = $row->qty_debet_akhir * $row->harga_avg;
                // cekHitam($jenis);
                // cekLime($row->qty_debet_awal);

                switch ($jenis) {

                    // in
                    // case "9999":
                    // case "19855":
                    //
                    //     $mainDatas2["qty_debet"] = $row->qty_debet;
                    //     $mainDatas2["harga_debet"] = $row->harga;
                    //     $mainDatas2["debet"] = $row->debet;
                    //
                    //     $mainDatas2["qty_kredit"] = 0;
                    //     $mainDatas2["harga_kredit"] = 0;
                    //     $mainDatas2["kredit"] = 0;
                    //
                    //     $mainDatas2["qty_saldo"] = $row->qty_debet_akhir;
                    //     $mainDatas2["harga_saldo"] = $row->harga;
                    //     $mainDatas2["saldo"] = $saldo;
                    //
                    //     // $mainDatas2["harga_avg"] = $row->harga_avg;
                    //     break;
                    //
                    // // ot
                    // case "5855":
                    // case "3344":
                    // case "382spd":
                    //     $mainDatas2["qty_debet"] = 0;
                    //     $mainDatas2["harga_debet"] = 0;
                    //     $mainDatas2["debet"] = 0;
                    //
                    //     $mainDatas2["qty_kredit"] = $row->qty_kredit;
                    //     $mainDatas2["harga_kredit"] = $row->harga;
                    //     $mainDatas2["kredit"] = $row->kredit;
                    //
                    //     $mainDatas2["qty_saldo"] = $row->qty_debet_akhir;
                    //     $mainDatas2["harga_saldo"] = $row->harga;
                    //     $mainDatas2["saldo"] = $saldo;
                    //
                    //     // $mainDatas2["harga_avg"] = $row->harga_avg;
                    //     break;

                    // saldo
                    default:
                        $mainDatas2["qty_debet_awal"] = $row->qty_debet_awal;
                        $mainDatas2["harga_debet_awal"] = $row->qty_debet_awal > 0 ? $row->harga_awal : 0;
                        $mainDatas2["debet_awal"] = $row->debet_awal;

                        $mainDatas2["qty_debet"] = $row->qty_debet;
                        $mainDatas2["harga_debet"] = $row->qty_debet > 0 ? $row->harga : 0;
                        $mainDatas2["debet"] = $row->debet;

                        $mainDatas2["qty_kredit"] = $row->qty_kredit;
                        $mainDatas2["harga_kredit"] = $row->qty_kredit > 0 ? $row->harga : 0;
                        $mainDatas2["kredit"] = $row->kredit;

                        $mainDatas2["qty_saldo"] = $row->qty_debet_akhir;
                        $mainDatas2["harga_saldo"] = $row->qty_debet_akhir > 0 ? $row->harga : 0;
                        $mainDatas2["saldo"] = $saldo;
                        // $mainDatas2["harga_avg"] = 0;
                        break;
                }


                $items[] = $mainDatas + $mainDatas2;

                $title = $row->extern_nama;
            }
        }


        //region prepare params to viewer
        $propertyFields = array(
            "qty_debet_awal" => array(
                "format" => "formatField",
                "attr" => "class='text-right'",
                "sumRow" => true,
            ),
            "harga_debet_awal" => array(
                "format" => "formatField",
                "attr" => "class='text-right'",
                "sumRow" => false,
            ),
            "debet_awal" => array(
                "format" => "formatField",
                "attr" => "class='text-right'",
                "sumRow" => true,
            ),

            "qty_debet" => array(
                "format" => "formatField",
                "attr" => "class='text-right'",
                "sumRow" => true,
            ),
            "harga_debet" => array(
                "format" => "formatField",
                "attr" => "class='text-right'",
                "sumRow" => true,
            ),
            "debet" => array(
                "format" => "formatField",
                "attr" => "class='text-right'",
                "sumRow" => true,
            ),

            "qty_kredit" => array(
                "format" => "formatField",
                "attr" => "class='text-right'",
                "sumRow" => true,
            ),
            "harga_kredit" => array(
                "format" => "formatField",
                "attr" => "class='text-right'",
                "sumRow" => true,
            ),
            "kredit" => array(
                "format" => "formatField",
                "attr" => "class='text-right'",
                "sumRow" => true,
            ),

            "qty_saldo" => array(
                "format" => "formatField",
                "attr" => "class='text-right'",
                // "sumRow" => true,
            ),
            "harga_saldo" => array(
                "format" => "formatField",
                "attr" => "class='text-right'",
                // "sumRow" => true,
            ),
            "saldo" => array(
                "format" => "formatField",
                "attr" => "class='text-right'",
                // "sumRow" => true,
            ),

            "harga_avg" => array(
                "format" => "formatField",
                "attr" => "class='text-right'",
            ),
            "transaksi_no" => array(
                "format" => "formatField",
                // "attr" => "class='text-right'",
            ),
            "dtime" => array(
                "format" => "formatField",
                // "attr" => "class='text-right'",
            ),
        );
        $summaryKey = array(
            //            "qty_debet_awal",
            //            "debet_awal",
            "qty_debet",
            "debet",
            "qty_kredit",
            "kredit",
        );

        $data = array(
            "mode" => "mutasiDetails_v1",
            "title" => $titleTop,
            "subTitle" => "&nbsp; $subTitle_date $subSubTitle_date",
            "items" => $items,
            "items2" => $items0,
            "fieldToshows" => $fieldToshows,
            "propertyFields" => $propertyFields,
            "headerFields" => $headerFields,
            // "headerFields2" => $arrMovedDetail,
            "headerFields2" => $headLooping,
            "total_colspan" => $total_colspan,


            "filters" => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "detailsLabels" => $detailsLabel,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "?o=$cabangID",

            "itemsCek" => $itemsCek,
            "summaryKey" => $summaryKey,
        );
        //endregion


        $this->load->view("ledger", $data);

    }

    public function viewMoveDetails_1()
    {
        $arrMovedDetail = array();
        $relName = $this->uri->segment(3);
        $rekName = urldecode($this->uri->segment(4));
        $externID = $this->uri->segment(5);
        $transaksiIDStyle = (isset($_GET['trID']) && $_GET['trID'] > 0) ? array($_GET['trID'] => "font-size:18px;font-weight:bold;") : array();

        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];
        $getPeriode = (isset($_GET['periode'])) ? $_GET['periode'] : null;
        $getDate = (isset($_GET['date'])) ? $_GET['date'] : null;
        $getDisabled = (isset($_GET['disabled'])) ? $_GET['disabled'] : null;


        $defPosition = detectRekDefaultPosition($rekName);
        $opPosition = $defPosition == "kredit" ? "debet" : "kredit";

        $balConfig = isset($this->config->item('accountMoveColumns')[$relName]) ? $this->config->item('accountMoveColumns')[$relName] : array();
        $accountFilters = isset($this->config->item('accountBalanceColumns')[$relName]['viewFilters']) ? $this->config->item('accountBalanceColumns')[$relName]['viewFilters'] : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();

        $linkKolomConfig = isset($this->config->item('accountMoveColumns')[$relName]['linkToDetail']) ? $this->config->item('accountMoveColumns')[$relName]['linkToDetail'] : array();
        $linkDetailConfig = isset($this->config->item('accountMoveColumns')[$relName]['baselink']) ? $this->config->item('accountMoveColumns')[$relName]['baselink'] : array();


        $mdlName = "Com" . $relName;

        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $mdlName);
        $this->load->model("MdlTransaksi");

        $tr = new MdlTransaksi();
        $com = new $mdlName();
        $com->addFilter("cabang_id='$cabangID'");
        if (sizeof($accountFilters) > 0) {
            foreach ($accountFilters as $f) {
                $f_ex = explode("=", $f);
                if (!isset($f_ex[1])) {
                    $f_ey = explode(">", $f_ex[0]);
                    if (substr($f_ey[1], 0, 1) == ".") {
                        $com->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                    }
                    else {
                        $com->addFilter($f_ey[0] . ">'" . $this->session->login[$f_ey[1]] . "'");
                    }
                }
                else {
                    if (substr($f_ex[1], 0, 1) == ".") {
                        $com->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                    }
                    else {
                        $com->addFilter($f_ex[0] . "='" . $this->session->login[$f_ex[1]] . "'");
                    }
                }
            }
        }

        if ($getPeriode != null) {
            switch ($getPeriode) {
                //                case "harian":
                //                    $date1 = isset($_GET['date']) ? $_GET['date'] : date("Y-m-01");
                //                    $date2 = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");
                //                    break;
                case "bulanan":
                    $date1 = isset($getDate) ? $getDate . "-01" : date("Y-m-01");
                    $date2 = isset($getDate) ? $getDate . "-31" : date("Y-m-d");
                    break;
                case "tahunan":
                    $getDate_ex = explode("-", $getDate);

                    $date1 = isset($getDate) ? $getDate_ex[0] . "-01-01" : date("Y-m-01");
                    $date2 = isset($getDate) ? $getDate_ex[0] . "-12-31" : date("Y-m-d");
                    break;

            }
            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");
            $subTitle_date = lgTranslateTime($date1) . " - " . lgTranslateTime($date2);
        }
        else {
            if (!isset($_GET['date1']) && !isset($_GET['date2'])) {
                $limit = 20;
                $this->db->limit("$limit");
                $this->db->order_by("id", "DESC");

                $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-01");
                $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

                $subTitle_date = "";
                $subSubTitle_date = " <span style='font-size:12px;font-style:italic;'>(mutasi $limit terakhir)</span>";
            }
            else {
                $this->db->order_by("id", "DESC");

                $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-01");
                $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
                $this->db->where("fulldate>='" . $date1 . "'");
                $this->db->where("fulldate<='" . $date2 . "'");

                $subTitle_date = lgTranslateTime($date1) . " - " . lgTranslateTime($date2);
                $subSubTitle_date = "";
            }
        }

        $tmp = $com->fetchMoves($rekName, $externID);
        //        cekLime($this->db->last_query());

        //region auto group in - out
        $saldo_qty_berjalan = 0;
        $saldo_value_berjalan = 0;

        $arrProds = array();
        $tmpTrIds = array();
        $saldoBerjalan = array();
        if (sizeof($tmp) > 0) {
            krsort($tmp);
            foreach ($tmp as $temX) {

                $position = $temX->debet > 0 ? "debet" : "kredit";
                $val = $temX->debet > 0 ? $temX->debet : $temX->kredit;
                $detectRek = detectRekByPosition($rekName, $val, $position);
                if ($detectRek > 0) {
                    $arrMovedDetail["in"][$temX->jenis] = $position;
                    if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                        $arrMovedDetail["in_qty"][$temX->jenis] = "qty_" . $position;
                        $newSelect = "qty_" . $position;
                        $qtyVal = $temX->$newSelect;
                    }
                    else {
                        $qtyVal = 0;
                    }

                }
                else {
                    $arrMovedDetail["out"][$temX->jenis] = $position;
                    if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                        $arrMovedDetail["out_qty"][$temX->jenis] = "qty_" . $position;
                        $newSelect = "qty_" . $position;
                        $qtyVal = $temX->$newSelect;
                    }
                    else {
                        $qtyVal = 0;
                    }
                }

                $arrProds[][$temX->jenis] = array(
                    "$position" => "$val",
                    "qty_" . $position => $qtyVal,
                );
                $transaksi_id = $temX->transaksi_id;
                $tmpTrIds[$transaksi_id] = $transaksi_id;


                // mengurusi saldo berjalan...
                $saldo_qty_berjalan += ($temX->qty_debet - $temX->qty_kredit);
                $saldo_value_berjalan += ($temX->debet - $temX->kredit);
                $saldoBerjalan[$temX->id]['qty'] = $saldo_qty_berjalan;
                $saldoBerjalan[$temX->id]['value'] = $saldo_value_berjalan;

            }
        }
        //endregion
        //arrPrint($saldoBerjalan);


        if (sizeof($balConfig)) {
            $headerFields = $balConfig['viewedColumns'];
            $arrHeaderBlacklist = array(
                "kredit", "debet", "qty_kredit", "qty_debet", //"jenis",
            );
            foreach ($headerFields as $key => $val) {
                if (in_array($key, $arrHeaderBlacklist)) {
                    unset($headerFields[$key]);
                }
            }
        }
        else {
            die("balance config for $relName is not yet defined");
        }


        $headerFields["ids_his"] = "reference number";
        $headerFields["transaksi_no"] = "receipt number";
        $headerFields["review_details"] = "&nbsp;";

        $headerQtyFields = array(
            "qty_" . $defPosition . "_awal" => "prev (QTY)",
            "in_qty" => "in qty",
            "out_qty" => "out qty",

            "saldo_qty_berjalan" => "saldo (QTY)",

            "qty_" . $defPosition . "_akhir" => "balance (YTD)",
        );
        $headerValueFields = array(
            $defPosition . "_awal" => "prev (IDR)",
            "in" => "in (IDR)",
            "out" => "out (IDR)",

            "saldo_berjalan" => "saldo (IDR)",

            $defPosition . "_akhir" => "balance (YTD)",
        );
        if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
            $headerFields = $headerFields + $headerQtyFields;
        }
        if (isset($balConfig['showValue']) && $balConfig['showValue'] == true) {
            $headerFields = $headerFields + $headerValueFields;
        }

        //region buld yIndex
        $items0 = array();
        foreach ($arrProds as $row0) {
            $temX = array();
            foreach ($arrMovedDetail as $yparent => $ySpec) {
                $subs0 = array();
                foreach ($ySpec as $jn => $colloumb) {
                    if (isset($row0[$jn][$colloumb])) {
                        $val = $row0[$jn][$colloumb];
                    }
                    else {
                        $val = 0;
                    }
                    $subs0[$jn] = $val;


                }
                $temX[$yparent] = $subs0;
            }
            $items0[] = $temX;
        }
        //endregion


        //region label detil transaksi
        $jenisAliases = arrCodeAliasing($cabangID);
        $arrTransData = array();
        $trDatasLink = array();
        $listId = implode("','", $tmpTrIds);
        if (strlen($listId) > 3) {
            $tr->setFilters(array());
            $tr->addFilter("id in ('" . $listId . "')");
            $tmpTrs = $tr->lookupAll()->result();
            foreach ($tmpTrs as $tmpTrsSpec) {
                $arrTransData[$tmpTrsSpec->id] = array(
                    "jenis" => array_key_exists($tmpTrsSpec->jenis, $jenisAliases) ? $jenisAliases[$tmpTrsSpec->jenis] : $tmpTrsSpec->jenis,
                    "customers_id" => $tmpTrsSpec->customers_id,
                    "customers_nama" => $tmpTrsSpec->customers_nama,
                    "suppliers_id" => $tmpTrsSpec->suppliers_id,
                    "suppliers_nama" => $tmpTrsSpec->suppliers_nama,
                    "oleh_id" => $tmpTrsSpec->oleh_id,
                    "oleh_nama" => $tmpTrsSpec->oleh_nama,
                    "cabang_id" => $tmpTrsSpec->cabang_id,
                    "cabang_nama" => $tmpTrsSpec->cabang_nama,
                    "ids_his" => $tmpTrsSpec->ids_his,
                );
                foreach ($tmpTrsSpec as $key => $val) {
                    if (array_key_exists($key, $linkKolomConfig)) {
                        $comPembantu = isset($linkKolomConfig[$key]['rekening'][$rekName]['com']) ? $linkKolomConfig[$key]['rekening'][$rekName]['com'] : $accountChilds[$rekName];
                        $rekName_new = isset($linkKolomConfig[$key]['rekening'][$rekName]['rek']) ? $linkKolomConfig[$key]['rekening'][$rekName]['rek'] : $rekName;
                        $link = base_url() . "$linkDetailConfig" . $comPembantu . "/$rekName_new/" . $tmpTrsSpec->$linkKolomConfig[$key]['key'] . "?o=$cabangID&trID=" . $tmpTrsSpec->id;
                        $trDatasLink[$tmpTrsSpec->id][$key] = $link;
                    }
                }
            }
        }


        $childHeaderLabels = $this->config->item('heTransaksi_ui');
        $detailsLabel = array();
        foreach ($childHeaderLabels as $tempSpec) {
            $xxTemp = $tempSpec["steps"];
            foreach ($xxTemp as $tempLabels) {
                $keyLabel = $tempLabels["target"];
                $keyValue = $tempLabels["label"];
                $detailsLabel[$keyLabel] = $keyValue;
            }
        }
        //endregion


        $title = $rekName;
        $items = array();
        if (sizeof($tmp) > 0) {
            krsort($tmp);
            //            arrPrint($tmp);
            foreach ($tmp as $row) {
                if (isset($arrTransData[$row->transaksi_id])) {
                    foreach ($arrTransData[$row->transaksi_id] as $key => $val) {
                        $row->$key = $val;
                    }
                }

                $rekening = $row->rekening;
                $position = detectRekDefaultPosition($rekening);
                switch ($position) {
                    case "debet":
                        if ($row->kredit_awal > 0) {
                            $row->debet_awal = $row->kredit_awal * -1;
                            $row->kredit_awal = 0;
                        }
                        if ($row->kredit_akhir > 0) {
                            $row->debet_akhir = $row->kredit_akhir * -1;
                            $row->kredit_akhir = 0;
                        }
                        break;
                    case "kredit":
                        if ($row->debet_awal > 0) {
                            $row->kredit_awal = $row->debet_awal * -1;
                            $row->debet_awal = 0;
                        }
                        if ($row->debet_akhir > 0) {
                            $row->kredit_akhir = $row->debet_akhir * -1;
                            $row->debet_akhir = 0;
                        }
                        break;
                }


                $subs = array();
                foreach ($headerFields as $key => $label) {
                    if (array_key_exists($key, $row)) {
                        if ((isset($row->$key)) && (!is_numeric($row->$key))) {
                            if ($row->$key === base64_encode(base64_decode($row->$key))) {

                                $subs[$key] = unserialize(base64_decode($row->$key));

                            }
                            else {
                                $subs[$key] = $row->$key;
                            }
                        }
                        else {
                            $subs[$key] = $row->$key;
                        }
                    }
                }


                $subs['saldo_qty_berjalan'] = isset($saldoBerjalan[$row->id]) ? $saldoBerjalan[$row->id]['qty'] : 0;
                $subs['saldo_berjalan'] = isset($saldoBerjalan[$row->id]) ? $saldoBerjalan[$row->id]['value'] : 0;

                $subs['transaksi_id'] = $row->transaksi_id;
                $subs['review_details'] = $row->transaksi_id;
                $items[] = $subs;
            }
        }
        //        arrPrint($items);

        $data = array(
            "mode" => "mutasiDetails",
            "title" => "$title",
            "subTitle" => " $subTitle_date $subSubTitle_date",
            "items" => $items,
            "items2" => $items0,
            "headerFields" => $headerFields,
            "headerFields2" => $arrMovedDetail,
            "filters" => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "detailsLabels" => $detailsLabel,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "?o=$cabangID",
            "disabled" => $getDisabled,
            "summary" => isset($this->summaryKey) ? $this->summaryKey : array(),
            "addDetailLink" => sizeof($trDatasLink) > 0 ? $trDatasLink : array(),
        );
        $this->load->view("ledger", $data);

    }

    public function viewMovementGroupOLD()
    {
        //        $tbl_src = "__rek_pembantu_produk__persediaan_produk";
        //        $tbl_hr = "__rek_pembantu_produk__persediaan_produk_hr";
        //        $tbl_bl = "__rek_pembantu_produk__persediaan_produk_bl";
        //        $tbl_bl = "__rek_pembantu_produk__persediaan_produk_groupjenis_bl";
        //        $tbl_bl = "__rek_pembantu_produk__persediaan_produk";

        $mv = isset($_GET['mv']) ? $_GET['mv'] : "";
        $this->load->config("heTransaksi_report");
        $item = $this->uri->segment(3);
        $cabangID = $this->placeID;
        $date = $date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow("Y-m");
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m");

        $mdlNameS = $this->config->item('heMovementBaru') ? $this->config->item('heMovementBaru') : array();
        $tbl_bl = ((sizeof($mdlNameS) > 0) && (isset($mdlNameS[$item]['tblMutasi']))) ? $mdlNameS[$item]['tblMutasi'] : "";


        $comName = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['com'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $itemLabel = isset($mdlNameS[$item]['label']) ? $mdlNameS[$item]['label'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $mdlName = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['mdl'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $rekening = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['rek'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $comName);
        $this->load->model("Mdls/" . $mdlName);
        $com = new $comName();
        // $com = new ComRekeningPembantuProduk();
        $mdl = new $mdlName();
        $mdlFields = key_exists($item, $mdlNameS) ? array_keys($mdlNameS[$item]['mdlFields']) : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");

        foreach ($mdlNameS[$item]['mdlFields'] as $field => $fChilds) {
            $fields[] = $field;
            isset($fChilds['label']) ? $fieldToshows[$field] = $fChilds['label'] : "";
            isset($fChilds['attr']) ? $fieldAttr[$field] = $fChilds['attr'] : "";
            isset($fChilds['attrHeader']) ? $fieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            isset($fChilds['link']) ? $fieldLink[$field] = $fChilds['link'] : "";
            isset($fChilds['format']) ? $fieldFormat[$field] = $fChilds['format'] : "";
        }

        // arrPrint($fieldToshows);
        $headers = isset($mdlNameS[$item]['headers']) ? $mdlNameS[$item]['headers'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        // arrPrint($headers);
        $mainHeaders_1 = array();
        foreach ($headers as $hKey => $hSpecs) {
            $mSpecs[$hSpecs["label"]] = $hSpecs['attr'];
            $mainHeaders_1 = $mSpecs;

            foreach ($hSpecs["subHeader"] as $shKey => $shSpecs) {
                // cekHere($shKey);
                // arrPrint($shSpecs);
                $subHeaders[$hKey][$shSpecs['label']] = $shSpecs['attr'];
            }
        }

        //region penyusun header table nganbil dari config
        $specs_0 = array();
        $mainHeaders_00 = array();
        $specs_0["no"] = 0;
        foreach ($fieldToshows as $field => $fieldToshow) {
            $fAttr = isset($fieldAttrHeader[$field]) ? $fieldAttrHeader[$field] : "-";
            $specs_0[$fieldToshow] = $fAttr;

            $mainHeaders_00 = $specs_0;
            $rNo["no"] = $fAttr;
        }
        $mainHeaders_0 = array_replace($mainHeaders_00, $rNo);
        // $mainHeaders_1 = isset($mdlNameS[$item]['mainHeaders_1']) ? $mdlNameS[$item]['mainHeaders_1'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $mainHeaders = array_merge($mainHeaders_0, $mainHeaders_1);
        // $subHeaders = isset($headers['subHeaders']) ? $headers['subHeaders'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        // arrPrint($subHeaders);
        //endregion

        $list_produkId = "";
        // $list_produkId = "76,386,80,34,36,37,38,39";
        // $list_produkId = "76,34";
        // $list_produkId = "102,37,87";
        //
        //        $jenisExternal = array(
        //            "982", "582spd", "382spd", "467", "967"
        //        );
        $jenisExternal = isset($mdlNameS[$item]['jenisTransaksiEksternal']) ? $mdlNameS[$item]['jenisTransaksiEksternal'] : array();

        $bl = formatTanggal($date, "n");
        $th = formatTanggal($date, "Y");
        $arrWhere = array(
            // "extern_id" => $src_extern_id,
            "cabang_id" => $cabangID,
            // "gudang_id" => $src_gudang_id,
            // "bln"       => $bl,
            "month(dtime)" => $bl,
            // "thn"       => $th,
            "year(dtime)" => $th,
            // "dtime "   => "$th-$bl-1",
        );
        if (strlen($list_produkId) > 1) {

            $this->db->where_in("extern_id", explode(",", $list_produkId));
        }
        // $this->db->where_in("jenis",$jenis);
        $this->db->where($arrWhere);
        $srcDatas = $this->db->get($tbl_bl)->result();
        //        showLast_query("lime");
        //         arrPrint($srcDatas);
        // $srcDatas = sizeof($srcBl) > 0 ? $srcBl[0] : array();


        //region source 2 query
        $arrWhere = array(
            // "extern_id" => $src_extern_id,
            "cabang_id" => $cabangID,
            // "gudang_id" => $src_gudang_id,
            // "bln"       => $bl,
            // "thn"       => $th,
            "dtime <" => "$th-$bl-1",
        );
        if (strlen($list_produkId) > 1) {
            $this->db->where_in("extern_id", explode(",", $list_produkId));
        }
        // $this->db->limit(1);
        $this->db->order_by("id", "asc");

        // $this->db->where_in("jenis",$jenis);
        $this->db->where($arrWhere);
        $srcBlLast = $this->db->get($tbl_bl)->result();
        //endregion
        //        showLast_query("hijau");
        // arrPrintHere($srcBlLast);
        $extrDatas2 = array();
        $intrDatas2 = array();
        $allDatas2 = array();
        foreach ($srcBlLast as $srcSpecs) {
            // $dataSource2[$srcSpecs->extern_id] = (array)$srcSpecs;
            $jenis = $srcSpecs->jenis;
            // cekHere("$jenis");
            if (in_array($jenis, $jenisExternal)) {
                $extrDatas2[$srcSpecs->extern_id] = (array)$srcSpecs;
            }
            else {
                $intrDatas2[$srcSpecs->extern_id] = (array)$srcSpecs;
            }

            $allDatas2[$srcSpecs->extern_id] = (array)$srcSpecs;
        }
        //arrPrint($allDatas2);
        // arrPrintWebs($subHeaders);
        if ($this->placeID <= 0) {
            $mdl->setFilters(array());

            $mdl->db->where("jenis in ('item_rakitan','item') AND `status` = '1' AND `trash` = '0'");
        }
        if (strlen($list_produkId) > 1) {
            $mdl->addFilter("id in ($list_produkId)");
        }
        $produks = $mdl->lookupAll()->result();

        //region data seluruh produk
        foreach ($produks as $pDatas) {
            foreach ($mdlFields as $mdlField) {
                $$mdlField = $pDatas->$mdlField;
            }
            foreach ($fieldToshows as $mdlField => $label) {
                $$mdlField = $pDatas->$mdlField;
                $specs[$mdlField] = $pDatas->$mdlField;
            }

            $arrProId[$id] = $specs;
        }
        //endregion

        // arrPrint($srcDatas);
        $extrDatas0 = array();
        $intrDatas0 = array();
        $extrDatas = array();
        $intrDatas = array();
        foreach ($srcDatas as $srcSpecs) {
            // arrPrintHere($srcSpecs);
            $jenis = $srcSpecs->jenis;

            // cekHere("$jenis");
            if (in_array($jenis, $jenisExternal)) {
                $extrDatas0[] = $srcSpecs;
            }
            else {
                $intrDatas0[] = $srcSpecs;
            }

        }
        // arrPrintWebs($extrDatas0);
        // matiHere();

        //region ngesum data


        $arrSum = array(
            // "debet_awal",
            "debet",
            // "debet_akhir",
            // "kredit_awal",
            "kredit",
            // "kredit_akhir",
            // "qty_debet_awal",
            "qty_debet",
            // "qty_debet_akhir",
            // "qty_kredit_awal",
            "qty_kredit",
            // "qty_kredit_akhir",
        );
        $arrSumView = array(
            "qty_debet_awal",
            "debet_awal",

            "qty_debet_int",
            "debet_int",
            "qty_debet",
            "debet",

            "qty_kredit_int",
            "kredit_int",
            "qty_kredit",
            "kredit",
            "qty_akhir",

            "qty_kredit",
            "akhir",
        );

        $allDatas = array();
        //region mengmbil data terakhir
        // arrPrintWebs($srcDatas);
        foreach ($srcDatas as $ky => $srcData) {
            $extern_id = $srcData->extern_id;
            // cekHere($srcData->qty_debet_akhir . " $extern_id // $ky *** $ururt");
            $allDataAkhir[$extern_id]['qty_debet_akhir'] = $srcData->qty_debet_akhir;
            $allDataAkhir[$extern_id]['debet_akhir'] = $srcData->debet_akhir;
            $allDataAkhir[$extern_id]['harga_akhir'] = $srcData->harga;
        }
        //endregion
        // arrPrint($allDataAkhir);
        // matiHere();

        krsort($srcDatas);
        foreach ($srcDatas as $ky => $srcData) {
            $extern_id = $srcData->extern_id;
            foreach ($arrSum as $itemSum) {
                if (!isset($allDatasSum[$extern_id][$itemSum])) {
                    $allDatasSum[$extern_id][$itemSum] = 0;
                }
                $allDatasSum[$extern_id][$itemSum] += $srcData->$itemSum;
            }

            //region mengambil data awal
            $allDataAwal[$extern_id]['qty_debet_awal'] = $srcData->qty_debet_awal;
            $allDataAwal[$extern_id]['debet_awal'] = $srcData->debet_awal;
            $harga_satuan = $srcData->qty_debet_awal > 0 ? ($srcData->debet_awal / $srcData->qty_debet_awal) : 0;
            $allDataAwal[$extern_id]['harga_debet_awal'] = $harga_satuan;

            //            cekBiru(":: $extern_id :: qty awal: " . $srcData->qty_debet_awal . ", value awal: " . $srcData->debet_awal . ", harga: " . $harga_satuan);
            //endregion

            $allDatas[$extern_id] = (array)$srcData;
        }
        //        arrPrint($allDataAwal);


        $extrDatas = array();
        $extrDataAwal = array();
        $extrDataAkhir = array();
        //region mengambil data akhir external
        foreach ($extrDatas0 as $items) {
            $extern_id = $items->extern_id;
            $extrDataAkhir[$extern_id]['qty_debet_akhir'] = $items->qty_debet_akhir;
            $extrDataAkhir[$extern_id]['debet_akhir'] = $items->debet_akhir;
        }
        //endregion
        // arrPrintWebs($extrDatas0);
        krsort($extrDatas0);
        foreach ($extrDatas0 as $items) {
            $extern_id = $items->extern_id;
            foreach ($arrSum as $itemSum) {
                if (!isset($extrDatasSum[$extern_id][$itemSum])) {
                    $extrDatasSum[$extern_id][$itemSum] = 0;
                }
                $extrDatasSum[$extern_id][$itemSum] += $items->$itemSum;
            }
            //region mengambil data awal external
            $extrDataAwal[$extern_id]['qty_debet_awal'] = $items->qty_debet_awal;
            $extrDataAwal[$extern_id]['debet_awal'] = $items->debet_awal;
            //endregion
            $extrDatas[$extern_id] = (array)$items;

            if ($items->qty_debet > 0) {
                if (!isset($extrDatasSum[$extern_id]['debet_harga'])) {
                    $extrDatasSum[$extern_id]['debet_harga'][($items->harga * 1)] = 0;
                }
                $extrDatasSum[$extern_id]['debet_harga'][($items->harga * 1)] += $items->qty_debet;
            }
        }
        // arrPrint($extrDatasSum);


        $intrDatas = array();
        $intrDataAwal = array();
        $intrDataAkhir = array();
        // arrPrintHere($intrDatas0);
        //region data akhir
        foreach ($intrDatas0 as $items) {
            $intern_id = $items->extern_id;
            $intrDataAkhir[$intern_id]['qty_debet_akhir'] = $items->qty_debet_akhir;
            $intrDataAkhir[$intern_id]['debet_akhir'] = $items->debet_akhir;
        }
        //endregion

        krsort($intrDatas0);
        foreach ($intrDatas0 as $items) {
            // arrPrint($items);
            $intern_id = $items->extern_id;
            foreach ($arrSum as $itemSum) {
                if (!isset($intrDatasSum[$intern_id][$itemSum])) {
                    $intrDatasSum[$intern_id][$itemSum] = 0;
                }
                $intrDatasSum[$intern_id][$itemSum] += $items->$itemSum;
            }
            // cekHere("$intern_id " . $items->qty_debet_awal);
            $intrDataAwal[$intern_id]['qty_debet_awal'] = $items->qty_debet_awal;
            $intrDataAwal[$intern_id]['debet_awal'] = $items->debet_awal;
            // arrPrint($intrDatasSum);
            $intrDatas[$intern_id] = (array)$items;

            if ($items->qty_debet > 0) {
                if (!isset($intrDatasSum[$intern_id]['debet_harga'])) {
                    $intrDatasSum[$intern_id]['debet_harga'][($items->harga * 1)] = 0;
                }
                $intrDatasSum[$intern_id]['debet_harga'][($items->harga * 1)] += $items->qty_debet;
            }
            if ($items->qty_kredit > 0) {
                if (!isset($intrDatasSum[$intern_id]['kredit_harga'])) {
                    $intrDatasSum[$intern_id]['kredit_harga'][($items->harga * 1)] = 0;
                }
                $intrDatasSum[$intern_id]['kredit_harga'][($items->harga * 1)] += $items->qty_kredit;
            }
        }
        //endregion

        // arrPrintWebs($intrDatas0);
        // arrPrintWebs($intrDataAwal);
        // arrPrintWebs($extrDatas0);
        // arrPrint($extrDataAwal);
        // arrPrint($extrDatasSum);
        // arrPrintWebs($intrDatasSum);
        // arrPrint($extrDatas);
        // matiHere();

        // switch ($mv) {
        //     case "all":
        //         $dataSourceAwal = $allDataAwal;
        //         $dataSourceSum = $allDatasSum;
        //         $dataSource = $allDatas;
        //         $dataSourceAkhir = $allDataAkhir;
        //
        //         $dataSource2 = $allDatas2;
        //         break;
        //     case "external":
        //         $dataSourceAwal = $extrDataAwal;
        //         $dataSourceSum = $extrDatasSum;
        //         $dataSource = $extrDatas;
        //         $dataSourceAkhir = $extrDataAkhir;
        //         $dataSource2 = $extrDatas2;
        //         break;
        //     case "internal":
        //         $dataSourceAwal = $intrDataAwal;
        //         $dataSourceSum = $intrDatasSum;
        //         $dataSource = $intrDatas;
        //         $dataSourceAkhir = $intrDataAkhir;
        //         $dataSource2 = $intrDatas2;
        //         break;
        // }

        // arrPrint($extrDatas);
        // arrPrintWebs($intrDatas);
        //         matiHere(__METHOD__ . __LINE__);
        // arrPrint($arrQtyHarga);
        // matiHere();
        // arrPrint($arrBeAf);
        // arrPrint($arrProId);
        // arrPrint($arrMutasi);
        // arrPrint($dataSource);
        // mati_disini();
        $mutasiKoloms = array(
            "qty_debet_awal",
            "harga_debet_awal",
            "debet_awal",

            "qty_debet",
            "harga_debet",
            "debet",

            "qty_avail",
            "harga_avail",
            "avail",

            "qty_kredit",
            "harga_kredit",
            "kredit",

            "qty_akhir",
            "harga_akhir",
            "akhir",
        );
        $specs = array();
        $bodies = array();
        $no = 0;

        // arrPrintWebs($allDataAkhir);
        //         arrPrintWebs($allDatas2);

        //region body produk
        foreach ($arrProId as $pId => $pSpecs) {

            // arrPrint($pSpecs);
            $no++;

            $specs['no']['value'] = $no;
            $specs['no']['attr'] = "class='text-right'";
            //region data produk
            foreach ($fieldToshows as $mdlField => $label) {
                $$mdlField = $pSpecs[$mdlField];

                $value = $pSpecs[$mdlField];

                if (isset($fieldLink[$mdlField])) {
                    $specs[$mdlField]['value'] = "<a target=\"_blank\" href=\"" . base_url() . $fieldLink[$mdlField] . $pId . "/?o=$cabangID\">" . $value . "</a>";
                    // $specs[$mdlField]['value'] = "<a href=\"#\">$value</a>";
                }
                else {

                    $specs[$mdlField]['value'] = $value;
                }

                // $specs[$mdlField]['value'] = ;
                $specs[$mdlField]['attr'] = isset($fieldAttr[$mdlField]) ? $fieldAttr[$mdlField] : "class='text-left'";
            }
            //endregion


            //region begining
            $akhirLast = isset($allDatas2[$pId]['qty_debet_akhir']) ? $allDatas2[$pId]['qty_debet_akhir'] : 0;
            $akhirValueLast = isset($allDatas2[$pId]['debet_akhir']) ? $allDatas2[$pId]['debet_akhir'] : 0;


            $specs['qty_debet_awal']['value'] = isset($allDataAwal[$pId]['qty_debet_awal']) ? $allDataAwal[$pId]['qty_debet_awal'] : $akhirLast;
            $specs['qty_debet_awal']['attr'] = "class='text-right'";

            $specs['harga_debet_awal']['value'] = isset($allDataAwal[$pId]['harga_debet_awal']) ? $allDataAwal[$pId]['harga_debet_awal'] : ($akhirLast > 0) ? $akhirValueLast / $akhirLast : 0;
            $specs['harga_debet_awal']['attr'] = "class='text-right'";

            $debetAwalLast = isset($allDatas2[$pId]) ? $allDatas2[$pId]['harga'] * $allDatas2[$pId]['qty_debet_akhir'] : 0;
            $specs['debet_awal']['value'] = isset($allDataAwal[$pId]['debet_awal']) ? $allDataAwal[$pId]['debet_awal'] : 0;
            $specs['debet_awal']['value'] = isset($allDataAwal[$pId]['debet_awal']) ? $allDataAwal[$pId]['debet_awal'] : $debetAwalLast;
            $specs['debet_awal']['attr'] = "class='text-right'";
            //endregion


            //region incoming masuk
            //region internal
            $specs['qty_debet_int']['value'] = isset($intrDatasSum[$pId]['qty_debet']) ? $intrDatasSum[$pId]['qty_debet'] : 0;
            $specs['qty_debet_int']['attr'] = "class='text-right'";

            // $specs['harga_debet_int']['value'] = isset($intrDatasSum[$pId]['harga_debet']) ? $intrDatasSum[$pId]['harga_debet'] : 0;
            if ($intrDatasSum[$pId]['qty_debet'] > 0) {
                $harga_satuan = $intrDatas[$pId]['harga'] * 1;
            }
            else {
                $harga_satuan = 0;
            }
            $specs['harga_debet_int']['value'] = isset($intrDatas[$pId]['harga']) ? $harga_satuan : 0;
            $specs['harga_debet_int']['attr'] = "class='text-right'";

            $specs['debet_int']['value'] = isset($intrDatasSum[$pId]['debet']) ? $intrDatasSum[$pId]['debet'] : 0;
            $specs['debet_int']['attr'] = "class='text-right'";
            //endregion

            //region external
            $specs['qty_debet']['value'] = isset($extrDatasSum[$pId]['qty_debet']) ? $extrDatasSum[$pId]['qty_debet'] : 0;
            $specs['qty_debet']['attr'] = "class='text-right'";

            if ($extrDatasSum[$pId]['qty_debet'] > 0) {
                // $harga_satuan = $extrDatasSum[$pId]['harga'] *1 ;
                $harga_satuan = $extrDatasSum[$pId]['debet'] / $extrDatasSum[$pId]['qty_debet'];
            }
            else {
                $harga_satuan = 0;
            }

            // $specs['harga_debet']['value'] = isset($extrDatasSum[$pId]['harga_debet']) ? $extrDatasSum[$pId]['harga_debet'] : 0;
            $specs['harga_debet']['value'] = isset($extrDatas[$pId]['harga']) ? $harga_satuan : 0;
            $specs['harga_debet']['attr'] = "class='text-right'";

            $specs['debet']['value'] = isset($extrDatasSum[$pId]['debet']) ? $extrDatasSum[$pId]['debet'] : 0;
            $specs['debet']['attr'] = "class='text-right'";
            //endregion
            //endregion


            //region outgoing keluar

            //region internal
            $specs['qty_kredit_int']['value'] = isset($intrDatasSum[$pId]['qty_kredit']) ? $intrDatasSum[$pId]['qty_kredit'] : 0;
            $specs['qty_kredit_int']['attr'] = "class='text-right'";

            if ($intrDatasSum[$pId]['qty_kredit'] > 0) {
                // $harga_satuan = $intrDatas[$pId]['harga'] *1 ;
                $harga_satuan = $intrDatasSum[$pId]['kredit'] / $intrDatasSum[$pId]['qty_kredit'];
            }
            else {
                $harga_satuan = 0;
            }
            // $specs['harga_kredit_int']['value'] = isset($intrDatasSum[$pId]['harga_kredit']) ? $intrDatasSum[$pId]['harga_kredit'] : 0;
            $specs['harga_kredit_int']['value'] = isset($intrDatas[$pId]['harga']) ? $harga_satuan : 0;
            $specs['harga_kredit_int']['attr'] = "class='text-right'";

            $specs['kredit_int']['value'] = isset($intrDatasSum[$pId]['kredit']) ? $intrDatasSum[$pId]['kredit'] : 0;
            $specs['kredit_int']['attr'] = "class='text-right'";
            //endregion

            //region external
            $specs['qty_kredit']['value'] = isset($extrDatasSum[$pId]['qty_kredit']) ? $extrDatasSum[$pId]['qty_kredit'] : 0;
            $specs['qty_kredit']['attr'] = "class='text-right'";

            if ($extrDatasSum[$pId]['qty_kredit'] > 0) {
                // $harga_satuan = $intrDatas[$pId]['harga'] *1 ;
                $harga_satuan = $extrDatasSum[$pId]['kredit'] / $extrDatasSum[$pId]['qty_kredit'];
            }
            else {
                $harga_satuan = 0;
            }
            // $specs['harga_kredit']['value'] = isset($extrDatasSum[$pId]['harga_kredit']) ? $extrDatasSum[$pId]['harga_kredit'] : 0;
            $specs['harga_kredit']['value'] = isset($intrDatas[$pId]['harga']) ? $harga_satuan : 0;
            $specs['harga_kredit']['attr'] = "class='text-right'";

            $specs['kredit']['value'] = isset($extrDatasSum[$pId]['kredit']) ? $extrDatasSum[$pId]['kredit'] : 0;
            $specs['kredit']['attr'] = "class='text-right'";
            //endregion

            //endregion


            //region ending

            $qtyDebetAkhirLast = isset($allDatas2[$pId]) ? $allDatas2[$pId]['qty_debet_akhir'] : 0;
            $debetAkhirLast = isset($allDatas2[$pId]) ? $allDatas2[$pId]['debet_akhir'] : 0;

            $specs['qty_akhir']['value'] = isset($allDataAkhir[$pId]['qty_debet_akhir']) ? $allDataAkhir[$pId]['qty_debet_akhir'] : $qtyDebetAkhirLast;
            $specs['qty_akhir']['attr'] = "class='text-right'";

            if (isset($allDataAkhir[$pId]['qty_debet_akhir'])) {
                if ($allDataAkhir[$pId]['qty_debet_akhir'] > 0) {
                    //                $harga_satuan = $allDataAkhir[$pId]['harga_akhir'] * 1;
                    $harga_satuan = $allDataAkhir[$pId]['debet_akhir'] / $allDataAkhir[$pId]['qty_debet_akhir'];
                }
                else {
                    $harga_satuan = 0;
                }
            }
            else {
                if ((isset($allDatas2[$pId]['qty_debet_akhir'])) && ($allDatas2[$pId]['qty_debet_akhir'] > 0)) {
                    $harga_satuan = $allDatas2[$pId]['debet_akhir'] / $allDatas2[$pId]['qty_debet_akhir'];
                }
                else {
                    $harga_satuan = 0;
                }
            }

            // $specs['harga_akhir']['value'] = isset($allDataAkhir[$pId]['harga_akhir']) ? $allDataAkhir[$pId]['harga_akhir'] : 0;
            //            $specs['harga_akhir']['value'] = isset($allDataAkhir[$pId]['harga_akhir']) ? $harga_satuan : "**";
            $specs['harga_akhir']['value'] = $harga_satuan;
            $specs['harga_akhir']['attr'] = "class='text-right'";

            // $specs['akhir']['value'] = isset($allDataAkhir[$pId]['debet_akhir']) ? $allDataAkhir[$pId]['debet_akhir'] : 0;
            $debetAkhirLast = isset($allDatas2[$pId]) ? $allDatas2[$pId]['harga'] * $allDatas2[$pId]['qty_debet_akhir'] : 0;
            $specs['akhir']['value'] = isset($allDataAkhir[$pId]['debet_akhir']) ? $allDataAkhir[$pId]['debet_akhir'] : $debetAkhirLast;
            $specs['akhir']['attr'] = "class='text-right'";

            //endregion


            $bodies[] = $specs;

            // if(!isset($sumvalues[]))
        }
        //endregion

        // arrPrintHere($allDataAkhir);
        // $sumQty = "";
        // arrPrint($sumQty);

        // region footers
        //        $footer_colspan = sizeof($mdlNameS[$item]['mdlFields'])-1;
        //        cekHere($footer_colspan);
        $footers = array(
            "total" => "colspan='3' class='text-right bg-info text-uppercase'",
            // formatField("qty_debet_awal", $sumQty['qty_debet_awal']) => "class='text-right bg-info text-uppercase'",
            // "-"                                                      => "class='text-right bg-info text-uppercase'",
            // formatField("debet_awal", $sumQty['debet_awal'])         => "class='text-right bg-info text-uppercase'",
            //
            // formatField("qty_debet", $sumQty['qty_debet']) => "class='text-right bg-info text-uppercase'",
            // "--"                                           => "class='text-right bg-info text-uppercase'",
            // formatField("debet", $sumQty['debet'])         => "class='text-right bg-info text-uppercase'",
            //
            // formatField("qty_avail", $sumQty['qty_avail']) => "class='text-right bg-info text-uppercase'",
            // "."                                            => "class='text-right bg-info text-uppercase'",
            // formatField("avail", $sumQty['avail'])         => "class='text-right bg-info text-uppercase'",
            //
            // formatField("qty_kredit", $sumQty['qty_kredit']) => "class='text-right bg-info text-uppercase'",
            // ".."                                             => "class='text-right bg-info text-uppercase'",
            // formatField("kredit", $sumQty['kredit'])         => "class='text-right bg-info text-uppercase'",
            //
            // formatField("qty_akhir", $sumQty['qty_akhir']) => "class='text-right bg-info text-uppercase'",
            // ""                                             => "class='text-right bg-info text-uppercase'",
            // formatField("akhir", $sumQty['akhir'])         => "class='text-right bg-info text-uppercase'",
        );
        // endregion footers

        //         arrPrint($bodies);
        // matiHere();
        // $month = date("Y-m",$date1);
        // cekHitam($month);
        // $dRange = formatField("dtime", $date1) . " s/d " . formatField("dtime", $date2);
        $lastDay = formatTanggal($date1, "t");
        $date2 = "$th-$bl-$lastDay";
        $dRange = formatField("dtime", $date1) . " s/d " . formatField("dtime", $date2);
        $dateRange = "($dRange)";

        $strO = isset($_GET['o']) ? "&o=" . $_GET['o'] : "";
        $strDate1 = isset($_GET['date1']) ? "&date1=" . $_GET['date1'] : "";

        $btnGroups = array(
            // "all"      => array(
            //     "label" => "All Movement",
            //     "link"  => "Ledger/viewMovementGroup/fg?mv=all" . $strO . $strDate1,
            // ),
            // "external" => array(
            //     "label" => "external Movement",
            //     "link"  => "Ledger/viewMovementGroup/fg?mv=external" . $strO . $strDate1,
            // ),
            // "internal" => array(
            //     "label" => "internal Movement",
            //     "link"  => "Ledger/viewMovementGroup/fg?mv=internal" . $strO . $strDate1,
            // ),
        );
        //        $dateRange = "<span class='font-size-0-8 text-grey-2'>$dRange</span>";
        // <span class='blink meta'>save mode data terlimit</span>
        $data = array(
            "mode" => "movementGroup",
            "title" => "$itemLabel movements $dateRange",
            "subTitle" => "",
            // "items"       => array(),
            // "items2"      => array(),
            "date1" => $date1,
            "date2" => $date2,

            "rowLoop" => 6,
            "mainHeaders" => $mainHeaders,
            "subHeaders" => $subHeaders,
            "bodies" => $bodies,
            "footers" => $footers,
            "sumfooters" => $arrSumView,
            "btnGroups" => $btnGroups,
            "mdlFields" => isset($mdlNameS[$item]['mdlFields']) ? $mdlNameS[$item]['mdlFields'] : array(),

            "detailsLabels" => "",
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?o=$cabangID",
        );
        //endregion

        //        arrprint($data);
        $this->load->view("ledger", $data);
    }

    public function viewMovementGroup()
    {


        $mv = isset($_GET['mv']) ? $_GET['mv'] : "";
        $this->load->config("heTransaksi_report");
        $item = $this->uri->segment(3);
        $cabangID = $this->placeID;
        $date = $date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow("Y-m");
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m");

        $mdlNameS = $this->config->item('heMovementBaru') ? $this->config->item('heMovementBaru') : array();
        $tbl_bl = ((sizeof($mdlNameS) > 0) && (isset($mdlNameS[$item]['tblMutasi']))) ? $mdlNameS[$item]['tblMutasi'] : "";


        $comName = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['com'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $itemLabel = isset($mdlNameS[$item]['label']) ? $mdlNameS[$item]['label'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $mdlName = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['mdl'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $rekening = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['rek'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $comName);
        $this->load->model("Mdls/" . $mdlName);
        $com = new $comName();
        // $com = new ComRekeningPembantuProduk();
        $mdl = new $mdlName();
        $mdlFields = key_exists($item, $mdlNameS) ? array_keys($mdlNameS[$item]['mdlFields']) : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");

        foreach ($mdlNameS[$item]['mdlFields'] as $field => $fChilds) {
            $fields[] = $field;
            isset($fChilds['label']) ? $fieldToshows[$field] = $fChilds['label'] : "";
            isset($fChilds['attr']) ? $fieldAttr[$field] = $fChilds['attr'] : "";
            isset($fChilds['attrHeader']) ? $fieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            isset($fChilds['link']) ? $fieldLink[$field] = $fChilds['link'] : "";
            isset($fChilds['format']) ? $fieldFormat[$field] = $fChilds['format'] : "";
        }


        $jenisExternal = isset($mdlNameS[$item]['jenisTransaksiEksternal']) ? $mdlNameS[$item]['jenisTransaksiEksternal'] : array();
        $jenisBom = isset($mdlNameS[$item]['jenisTransaksi_bom']) ? $mdlNameS[$item]['jenisTransaksi_bom'] : array();
        $jenisNG = isset($mdlNameS[$item]['jenisTransaksi_ng']) ? $mdlNameS[$item]['jenisTransaksi_ng'] : array();
        $jenisBiaya = isset($mdlNameS[$item]['jenisTransaksi_biaya']) ? $mdlNameS[$item]['jenisTransaksi_biaya'] : array();


        $headers = isset($mdlNameS[$item]['headers']) ? $mdlNameS[$item]['headers'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $additionalHeaders = isset($mdlNameS[$item]['additionalHeaders']) ? $mdlNameS[$item]['additionalHeaders'] : array();

        $mainHeaders_1 = array();
        foreach ($headers as $hKey => $hSpecs) {

            foreach ($hSpecs["subHeader"] as $shKey => $shSpecs) {
                $subHeaders[$hKey][$shSpecs['label']] = $shSpecs['attr'];
            }

            if (sizeof($additionalHeaders) > 0) {
                foreach ($additionalHeaders as $jenisTrLabel => $jSpec) {
                    if (array_key_exists($jenisTrLabel, $mdlNameS[$item]) && sizeof($mdlNameS[$item][$jenisTrLabel]) > 0) {
                        if (isset($jSpec['subHeader'][$hKey]) && sizeof($jSpec['subHeader'][$hKey]) > 0) {
                            foreach ($jSpec['subHeader'][$hKey] as $jhSpecs) {
                                $subHeaders[$hKey][$jhSpecs['label']] = $jhSpecs['attr'];
                            }
                        }
                    }
                }
            }

            $attr = str_replace("colspan", "colspan='" . sizeof($subHeaders[$hKey]) . "'", $hSpecs['attr']);
            $mSpecs[$hSpecs["label"]] = $attr;
            $mainHeaders_1 = $mSpecs;

        }

        //region penyusun header table nganbil dari config
        $specs_0 = array();
        $mainHeaders_00 = array();
        $specs_0["no"] = 0;
        foreach ($fieldToshows as $field => $fieldToshow) {
            $fAttr = isset($fieldAttrHeader[$field]) ? $fieldAttrHeader[$field] : "-";
            $specs_0[$fieldToshow] = $fAttr;

            $mainHeaders_00 = $specs_0;
            $rNo["no"] = $fAttr;
        }
        $mainHeaders_0 = array_replace($mainHeaders_00, $rNo);
        $mainHeaders = array_merge($mainHeaders_0, $mainHeaders_1);
        //endregion

        /* -----------------------------------------------
         * == filter produk list id
         * ----------------------------------------------------*/
        // if(show_debuger() == 1){
        //     $list_produkId = "175,176";
        // }
        // else{
        $list_produkId = "";
        // }

        //region ambil mutasi periode saat ini
        $bl = formatTanggal($date, "n");
        $th = formatTanggal($date, "Y");
        $arrWhere = array(
            // "extern_id" => $src_extern_id,
            "cabang_id" => $cabangID,
            // "gudang_id" => $src_gudang_id,
            // "bln"       => $bl,
            "month(dtime)" => $bl,
            // "thn"       => $th,
            "year(dtime)" => $th,
            // "dtime "   => "$th-$bl-1",
        );
        if (strlen($list_produkId) > 1) {

            $this->db->where_in("extern_id", explode(",", $list_produkId));
        }
        // $this->db->where_in("jenis",$jenis);
        $this->db->where($arrWhere);
        $srcDatas = $this->db->get($tbl_bl)->result();
        //        showLast_query("lime");
        //         arrPrint($srcDatas);
        // $srcDatas = sizeof($srcBl) > 0 ? $srcBl[0] : array();
        //endregion

        //region source 2 query, ambil data mutasi sebelumnya...
        $arrWhere = array(
            // "extern_id" => $src_extern_id,
            "cabang_id" => $cabangID,
            // "gudang_id" => $src_gudang_id,
            // "bln"       => $bl,
            // "thn"       => $th,
            "dtime <" => "$th-$bl-1",
        );
        if (strlen($list_produkId) > 1) {
            $this->db->where_in("extern_id", explode(",", $list_produkId));
        }
        // $this->db->limit(1);
        $this->db->order_by("id", "asc");

        // $this->db->where_in("jenis",$jenis);
        $this->db->where($arrWhere);
        $srcBlLast = $this->db->get($tbl_bl)->result();
        //        showLast_query("hijau");
        // arrPrintHere($srcBlLast);
        //endregion

        //region pengelompokan jenis transaksi ke internal, eksternal, bom, not good
        $extrDatas2 = array();
        $intrDatas2 = array();
        $bomDatas2 = array();
        $ngDatas2 = array();
        $expDatas2 = array();
        $allDatas2 = array();
        foreach ($srcBlLast as $srcSpecs) {
            // $dataSource2[$srcSpecs->extern_id] = (array)$srcSpecs;
            $jenis = $srcSpecs->jenis;
            // cekHere("$jenis");
            if (in_array($jenis, $jenisExternal)) {
                $extrDatas2[$srcSpecs->extern_id] = (array)$srcSpecs;
            }
            elseif (in_array($jenis, $jenisBom)) {
                $bomDatas2[$srcSpecs->extern_id] = (array)$srcSpecs;
            }
            elseif (in_array($jenis, $jenisNG)) {
                $ngDatas2[$srcSpecs->extern_id] = (array)$srcSpecs;
            }
            elseif (in_array($jenis, $jenisBiaya)) {
                $expDatas2[$srcSpecs->extern_id] = (array)$srcSpecs;
            }
            else {
                $intrDatas2[$srcSpecs->extern_id] = (array)$srcSpecs;
            }

            $allDatas2[$srcSpecs->extern_id] = (array)$srcSpecs;
        }
        //endregion

        //region data seluruh produk
        //        if ($this->placeID <= 0) {
        $mdl->setFilters(array());
        $mdl->db->where("jenis in ('item_rakitan','item') AND `status` = '1' AND `trash` = '0'");
        //        }
        if (strlen($list_produkId) > 1) {
            $mdl->addFilter("id in ($list_produkId)");
        }
        $produks = $mdl->lookupAll()->result();
        //        showLast_query("kuning");
        foreach ($produks as $pDatas) {
            foreach ($mdlFields as $mdlField) {
                $$mdlField = $pDatas->$mdlField;
            }
            foreach ($fieldToshows as $mdlField => $label) {
                $$mdlField = $pDatas->$mdlField;
                $specs[$mdlField] = $pDatas->$mdlField;
            }

            $arrProId[$id] = $specs;
        }
        //endregion


        $extrDatas0 = array();
        $intrDatas0 = array();
        $bomDatas0 = array();
        $ngDatas0 = array();
        $expDatas0 = array();
        $extrDatas = array();
        $intrDatas = array();
        $bomDatas = array();
        $ngDatas = array();
        $expDatas = array();
        foreach ($srcDatas as $srcSpecs) {
            $jenis = $srcSpecs->jenis;
            if (in_array($jenis, $jenisExternal)) {
                $extrDatas0[] = $srcSpecs;
            }
            elseif (in_array($jenis, $jenisBom)) {
                $bomDatas0[] = $srcSpecs;
            }
            elseif (in_array($jenis, $jenisNG)) {
                $ngDatas0[] = $srcSpecs;
            }
            elseif (in_array($jenis, $jenisBiaya)) {
                $expDatas0[] = $srcSpecs;
            }
            else {
                $intrDatas0[] = $srcSpecs;
            }
        }

        //region ngesum data
        $arrSum = array(
            "debet",
            "kredit",
            "debet_bom",
            "kredit_bom",
            "debet_ng",
            "kredit_ng",

            "qty_debet",
            "qty_kredit",
            "qty_debet_bom",
            "qty_kredit_bom",
            "qty_debet_ng",
            "qty_kredit_ng",

        );
        $arrSumView = array(
            "qty_debet_awal",
            "debet_awal",

            "qty_debet_int",
            "debet_int",

            "qty_debet",
            "debet",

            "qty_debet_bom",
            "debet_bom",

            "qty_debet_ng",
            "debet_ng",

            "qty_kredit_int",
            "kredit_int",

            "qty_kredit",
            "kredit",

            "qty_kredit_bom",
            "kredit_bom",

            "qty_kredit_ng",
            "kredit_ng",

            "qty_akhir",
            "qty_kredit",
            "akhir",
        );

        $allDatas = array();
        //region mengmbil data terakhir
        // arrPrintWebs($srcDatas);
        foreach ($srcDatas as $ky => $srcData) {
            $extern_id = $srcData->extern_id;
            // cekHere($srcData->qty_debet_akhir . " $extern_id // $ky *** $ururt");
            $allDataAkhir[$extern_id]['qty_debet_akhir'] = $srcData->qty_debet_akhir;
            $allDataAkhir[$extern_id]['debet_akhir'] = $srcData->debet_akhir;
            $allDataAkhir[$extern_id]['harga_akhir'] = $srcData->harga;
        }
        //endregion


        krsort($srcDatas);
        foreach ($srcDatas as $ky => $srcData) {
            $extern_id = $srcData->extern_id;
            foreach ($arrSum as $itemSum) {
                if (!isset($allDatasSum[$extern_id][$itemSum])) {
                    $allDatasSum[$extern_id][$itemSum] = 0;
                }
                $allDatasSum[$extern_id][$itemSum] += isset($srcData->$itemSum) ? $srcData->$itemSum : 0;
            }

            //region mengambil data awal
            $allDataAwal[$extern_id]['qty_debet_awal'] = $srcData->qty_debet_awal;
            $allDataAwal[$extern_id]['debet_awal'] = $srcData->debet_awal;
            $harga_satuan = $srcData->qty_debet_awal > 0 ? ($srcData->debet_awal / $srcData->qty_debet_awal) : 0;
            $allDataAwal[$extern_id]['harga_debet_awal'] = $harga_satuan;

            //            cekBiru(":: $extern_id :: qty awal: " . $srcData->qty_debet_awal . ", value awal: " . $srcData->debet_awal . ", harga: " . $harga_satuan);
            //endregion

            $allDatas[$extern_id] = (array)$srcData;
        }
        //        arrPrint($allDataAwal);


        $extrDatas = array();
        $extrDataAwal = array();
        $extrDataAkhir = array();
        //region mengambil data akhir external
        foreach ($extrDatas0 as $items) {
            $extern_id = $items->extern_id;
            $extrDataAkhir[$extern_id]['qty_debet_akhir'] = $items->qty_debet_akhir;
            $extrDataAkhir[$extern_id]['debet_akhir'] = $items->debet_akhir;
        }
        //endregion

        krsort($extrDatas0);
        foreach ($extrDatas0 as $items) {
            $extern_id = $items->extern_id;
            foreach ($arrSum as $itemSum) {
                if (!isset($extrDatasSum[$extern_id][$itemSum])) {
                    $extrDatasSum[$extern_id][$itemSum] = 0;
                }
                $extrDatasSum[$extern_id][$itemSum] += isset($items->$itemSum) ? $items->$itemSum : 0;
            }

            //region mengambil data awal external
            $extrDataAwal[$extern_id]['qty_debet_awal'] = $items->qty_debet_awal;
            $extrDataAwal[$extern_id]['debet_awal'] = $items->debet_awal;
            //endregion

            $extrDatas[$extern_id] = (array)$items;

            if ($items->qty_debet > 0) {
                if (!isset($extrDatasSum[$extern_id]['debet_harga'])) {
                    $extrDatasSum[$extern_id]['debet_harga'][($items->harga * 1)] = 0;
                }
                $extrDatasSum[$extern_id]['debet_harga'][($items->harga * 1)] += $items->qty_debet;
            }
        }


        //region bom
        $bomDatas = array();
        $bomDataAwal = array();
        $bomDataAkhir = array();
        //region mengambil data akhir external
        foreach ($bomDatas0 as $items) {
            $extern_id = $items->extern_id;
            $bomDataAkhir[$extern_id]['qty_debet_akhir'] = $items->qty_debet_akhir;
            $bomDataAkhir[$extern_id]['debet_akhir'] = $items->debet_akhir;
        }
        //endregion

        krsort($bomDatas0);
        foreach ($bomDatas0 as $items) {
            $extern_id = $items->extern_id;
            foreach ($arrSum as $itemSum) {
                if (!isset($bomDatasSum[$extern_id][$itemSum])) {
                    $bomDatasSum[$extern_id][$itemSum] = 0;
                }
                $bomDatasSum[$extern_id][$itemSum] += isset($items->$itemSum) ? $items->$itemSum : 0;
            }

            //region mengambil data awal external
            $bomDataAwal[$extern_id]['qty_debet_awal'] = $items->qty_debet_awal;
            $bomDataAwal[$extern_id]['debet_awal'] = $items->debet_awal;
            //endregion

            $bomDatas[$extern_id] = (array)$items;

            if ($items->qty_debet > 0) {
                if (!isset($bomDatasSum[$extern_id]['debet_harga'][($items->harga * 1)])) {
                    $bomDatasSum[$extern_id]['debet_harga'][($items->harga * 1)] = 0;
                }
                //                cekHitam($items->harga);
                $bomDatasSum[$extern_id]['debet_harga'][($items->harga * 1)] += isset($items->qty_debet) ? $items->qty_debet : 0;
            }
        }
        //endregion


        //region not good
        $ngDatas = array();
        $ngDataAwal = array();
        $ngDataAkhir = array();
        //region mengambil data akhir external
        foreach ($ngDatas0 as $items) {
            $extern_id = $items->extern_id;
            $ngDataAkhir[$extern_id]['qty_debet_akhir'] = $items->qty_debet_akhir;
            $ngDataAkhir[$extern_id]['debet_akhir'] = $items->debet_akhir;
        }
        //endregion

        krsort($ngDatas0);
        foreach ($ngDatas0 as $items) {
            $extern_id = $items->extern_id;
            foreach ($arrSum as $itemSum) {
                if (!isset($ngDatasSum[$extern_id][$itemSum])) {
                    $ngDatasSum[$extern_id][$itemSum] = 0;
                }
                $ngDatasSum[$extern_id][$itemSum] += isset($items->$itemSum) ? $items->$itemSum : 0;
            }

            //region mengambil data awal external
            $ngDataAwal[$extern_id]['qty_debet_awal'] = $items->qty_debet_awal;
            $ngDataAwal[$extern_id]['debet_awal'] = $items->debet_awal;
            //endregion

            $ngDatas[$extern_id] = (array)$items;

            if ($items->qty_debet > 0) {
                if (!isset($ngDatasSum[$extern_id]['debet_harga'][($items->harga * 1)])) {
                    $ngDatasSum[$extern_id]['debet_harga'][($items->harga * 1)] = 0;
                }
                //                cekHitam($items->harga);
                $ngDatasSum[$extern_id]['debet_harga'][($items->harga * 1)] += isset($items->qty_debet) ? $items->qty_debet : 0;
            }
        }
        //endregion


        //region biayakan
        $expDatas = array();
        $expDataAwal = array();
        $expDataAkhir = array();
        //region mengambil data akhir external
        foreach ($expDatas0 as $items) {
            $extern_id = $items->extern_id;
            $expDataAkhir[$extern_id]['qty_debet_akhir'] = $items->qty_debet_akhir;
            $expDataAkhir[$extern_id]['debet_akhir'] = $items->debet_akhir;
        }
        //endregion

        krsort($expDatas0);
        foreach ($expDatas0 as $items) {
            $extern_id = $items->extern_id;
            foreach ($arrSum as $itemSum) {
                if (!isset($expDatasSum[$extern_id][$itemSum])) {
                    $expDatasSum[$extern_id][$itemSum] = 0;
                }
                $expDatasSum[$extern_id][$itemSum] += isset($items->$itemSum) ? $items->$itemSum : 0;
            }

            //region mengambil data awal external
            $expDataAwal[$extern_id]['qty_debet_awal'] = $items->qty_debet_awal;
            $expDataAwal[$extern_id]['debet_awal'] = $items->debet_awal;
            //endregion

            $expDatas[$extern_id] = (array)$items;

            if ($items->qty_debet > 0) {
                if (!isset($expDatasSum[$extern_id]['debet_harga'][($items->harga * 1)])) {
                    $expDatasSum[$extern_id]['debet_harga'][($items->harga * 1)] = 0;
                }
                //                cekHitam($items->harga);
                $expDatasSum[$extern_id]['debet_harga'][($items->harga * 1)] += isset($items->qty_debet) ? $items->qty_debet : 0;
            }
        }
        //endregion


        $intrDatas = array();
        $intrDataAwal = array();
        $intrDataAkhir = array();
        //region data akhir
        foreach ($intrDatas0 as $items) {
            $intern_id = $items->extern_id;
            $intrDataAkhir[$intern_id]['qty_debet_akhir'] = $items->qty_debet_akhir;
            $intrDataAkhir[$intern_id]['debet_akhir'] = $items->debet_akhir;
        }
        //endregion

        krsort($intrDatas0);
        foreach ($intrDatas0 as $items) {
            // arrPrint($items);
            $intern_id = $items->extern_id;
            foreach ($arrSum as $itemSum) {
                if (!isset($intrDatasSum[$intern_id][$itemSum])) {
                    $intrDatasSum[$intern_id][$itemSum] = 0;
                }
                $intrDatasSum[$intern_id][$itemSum] += isset($items->$itemSum) ? $items->$itemSum : 0;
            }
            // cekHere("$intern_id " . $items->qty_debet_awal);
            $intrDataAwal[$intern_id]['qty_debet_awal'] = $items->qty_debet_awal;
            $intrDataAwal[$intern_id]['debet_awal'] = $items->debet_awal;
            // arrPrint($intrDatasSum);
            $intrDatas[$intern_id] = (array)$items;

            if ($items->qty_debet > 0) {
                if (!isset($intrDatasSum[$intern_id]['debet_harga'])) {
                    $intrDatasSum[$intern_id]['debet_harga'][($items->harga * 1)] = 0;
                }
                $intrDatasSum[$intern_id]['debet_harga'][($items->harga * 1)] += $items->qty_debet;
            }
            if ($items->qty_kredit > 0) {
                if (!isset($intrDatasSum[$intern_id]['kredit_harga'][($items->harga * 1)])) {
                    $intrDatasSum[$intern_id]['kredit_harga'][($items->harga * 1)] = 0;
                }
                $intrDatasSum[$intern_id]['kredit_harga'][($items->harga * 1)] += isset($items->qty_kredit) ? $items->qty_kredit : 0;
            }
        }
        //endregion


        $mutasiKoloms = array(
            "qty_debet_awal",
            "harga_debet_awal",
            "debet_awal",

            "qty_debet",
            "harga_debet",
            "debet",

            "qty_avail",
            "harga_avail",
            "avail",

            "qty_kredit",
            "harga_kredit",
            "kredit",

            "qty_akhir",
            "harga_akhir",
            "akhir",
        );
        $specs = array();
        $bodies = array();
        $no = 0;


        //region body produk
        foreach ($arrProId as $pId => $pSpecs) {

            // arrPrint($pSpecs);
            $no++;

            $specs['no']['value'] = $no;
            $specs['no']['attr'] = "class='text-right'";

            //region data produk
            foreach ($fieldToshows as $mdlField => $label) {
                $$mdlField = $pSpecs[$mdlField];

                $value = $pSpecs[$mdlField];

                if (isset($fieldLink[$mdlField])) {
                    $specs[$mdlField]['value'] = "<a target=\"_blank\" href=\"" . base_url() . $fieldLink[$mdlField] . $pId . "/?o=$cabangID\">" . $value . "</a>";
                    // $specs[$mdlField]['value'] = "<a href=\"#\">$value</a>";
                }
                else {

                    $specs[$mdlField]['value'] = $value;
                }

                // $specs[$mdlField]['value'] = ;
                $specs[$mdlField]['attr'] = isset($fieldAttr[$mdlField]) ? $fieldAttr[$mdlField] : "class='text-left'";
            }
            //endregion


            //region begining
            $akhirLast = isset($allDatas2[$pId]['qty_debet_akhir']) ? $allDatas2[$pId]['qty_debet_akhir'] : 0;
            $akhirValueLast = isset($allDatas2[$pId]['debet_akhir']) ? $allDatas2[$pId]['debet_akhir'] : 0;


            $specs['qty_debet_awal']['value'] = isset($allDataAwal[$pId]['qty_debet_awal']) ? $allDataAwal[$pId]['qty_debet_awal'] : $akhirLast;
            $specs['qty_debet_awal']['attr'] = "class='text-right'";

            $specs['harga_debet_awal']['value'] = isset($allDataAwal[$pId]['harga_debet_awal']) ? $allDataAwal[$pId]['harga_debet_awal'] : ($akhirLast > 0) ? $akhirValueLast / $akhirLast : 0;
            $specs['harga_debet_awal']['attr'] = "class='text-right'";
// cekHijau($allDatas2[$pId]['harga_avg'] ." x ". $allDatas2[$pId]['qty_debet_akhir'] . " = ". $allDatas2[$pId]['harga_avg'] * $allDatas2[$pId]['qty_debet_akhir']);
            $debetAwalLast = isset($allDatas2[$pId]) ? $allDatas2[$pId]['harga_avg'] * $allDatas2[$pId]['qty_debet_akhir'] : 0;
            //            $specs['debet_awal']['value'] = isset($allDataAwal[$pId]['debet_awal']) ? $allDataAwal[$pId]['debet_awal'] : 0;
            $specs['debet_awal']['value'] = isset($allDataAwal[$pId]['debet_awal']) ? $allDataAwal[$pId]['debet_awal'] : $debetAwalLast;
            $specs['debet_awal']['attr'] = "class='text-right'";
            //endregion


            //region incoming masuk
            //region internal
            $specs['qty_debet_int']['value'] = isset($intrDatasSum[$pId]['qty_debet']) ? $intrDatasSum[$pId]['qty_debet'] : 0;
            $specs['qty_debet_int']['attr'] = "class='text-right'";

            // $specs['harga_debet_int']['value'] = isset($intrDatasSum[$pId]['harga_debet']) ? $intrDatasSum[$pId]['harga_debet'] : 0;
            if ((isset($intrDatasSum[$pId]['qty_debet'])) && ($intrDatasSum[$pId]['qty_debet'] > 0)) {
                $harga_satuan = $intrDatas[$pId]['harga'] * 1;
            }
            else {
                $harga_satuan = 0;
            }
            $specs['harga_debet_int']['value'] = isset($intrDatas[$pId]['harga']) ? $harga_satuan : 0;
            $specs['harga_debet_int']['attr'] = "class='text-right'";

            $specs['debet_int']['value'] = isset($intrDatasSum[$pId]['debet']) ? $intrDatasSum[$pId]['debet'] : 0;
            $specs['debet_int']['attr'] = "class='text-right'";
            //endregion

            //region external
            if (sizeof($jenisExternal) > 0) {
                $specs['qty_debet']['value'] = isset($extrDatasSum[$pId]['qty_debet']) ? $extrDatasSum[$pId]['qty_debet'] : 0;
                $specs['qty_debet']['attr'] = "class='text-right'";

                if ((isset($extrDatasSum[$pId]['qty_debet'])) && ($extrDatasSum[$pId]['qty_debet'] > 0)) {
                    // $harga_satuan = $extrDatasSum[$pId]['harga'] *1 ;
                    $harga_satuan = $extrDatasSum[$pId]['debet'] / $extrDatasSum[$pId]['qty_debet'];
                }
                else {
                    $harga_satuan = 0;
                }

                // $specs['harga_debet']['value'] = isset($extrDatasSum[$pId]['harga_debet']) ? $extrDatasSum[$pId]['harga_debet'] : 0;
                $specs['harga_debet']['value'] = isset($extrDatas[$pId]['harga']) ? $harga_satuan : 0;
                $specs['harga_debet']['attr'] = "class='text-right'";

                $specs['debet']['value'] = isset($extrDatasSum[$pId]['debet']) ? $extrDatasSum[$pId]['debet'] : 0;
                $specs['debet']['attr'] = "class='text-right'";
            }
            //endregion

            //region bom
            if (sizeof($jenisBom) > 0) {
                $specs['qty_debet_bom']['value'] = isset($bomDatasSum[$pId]['qty_debet']) ? $bomDatasSum[$pId]['qty_debet'] : 0;
                $specs['qty_debet_bom']['attr'] = "class='text-right'";

                if (isset($bomDatasSum[$pId]['qty_debet']) && ($bomDatasSum[$pId]['qty_debet'] > 0)) {
                    $harga_satuan = $bomDatasSum[$pId]['debet'] / $bomDatasSum[$pId]['qty_debet'];
                }
                else {
                    $harga_satuan = 0;
                }

                // $specs['harga_debet']['value'] = isset($extrDatasSum[$pId]['harga_debet']) ? $extrDatasSum[$pId]['harga_debet'] : 0;
                $specs['harga_debet_bom']['value'] = isset($bomDatas[$pId]['harga']) ? $harga_satuan : 0;
                $specs['harga_debet_bom']['attr'] = "class='text-right'";

                $specs['debet_bom']['value'] = isset($bomDatasSum[$pId]['debet']) ? $bomDatasSum[$pId]['debet'] : 0;
                $specs['debet_bom']['attr'] = "class='text-right'";
            }
            //endregion

            //region notgood
            if (sizeof($jenisNG) > 0) {
                $specs['qty_debet_ng']['value'] = isset($ngDatasSum[$pId]['qty_debet']) ? $ngDatasSum[$pId]['qty_debet'] : 0;
                $specs['qty_debet_ng']['attr'] = "class='text-right'";

                if (isset($ngDatasSum[$pId]['qty_debet']) && ($ngDatasSum[$pId]['qty_debet'] > 0)) {
                    $harga_satuan = $ngDatasSum[$pId]['debet'] / $ngDatasSum[$pId]['qty_debet'];
                }
                else {
                    $harga_satuan = 0;
                }

                // $specs['harga_debet']['value'] = isset($extrDatasSum[$pId]['harga_debet']) ? $extrDatasSum[$pId]['harga_debet'] : 0;
                $specs['harga_debet_ng']['value'] = isset($ngDatas[$pId]['harga']) ? $harga_satuan : 0;
                $specs['harga_debet_ng']['attr'] = "class='text-right'";

                $specs['debet_ng']['value'] = isset($ngDatasSum[$pId]['debet']) ? $ngDatasSum[$pId]['debet'] : 0;
                $specs['debet_ng']['attr'] = "class='text-right'";
            }
            //endregion

            //region biayakan
            if (sizeof($jenisBiaya) > 0) {
                $specs['qty_debet_exp']['value'] = isset($expDatasSum[$pId]['qty_debet']) ? $expDatasSum[$pId]['qty_debet'] : 0;
                $specs['qty_debet_exp']['attr'] = "class='text-right'";

                if (isset($expDatasSum[$pId]['qty_debet']) && ($expDatasSum[$pId]['qty_debet'] > 0)) {
                    $harga_satuan = $expDatasSum[$pId]['debet'] / $expDatasSum[$pId]['qty_debet'];
                }
                else {
                    $harga_satuan = 0;
                }

                $specs['harga_debet_exp']['value'] = isset($expDatas[$pId]['harga']) ? $harga_satuan : 0;
                $specs['harga_debet_exp']['attr'] = "class='text-right'";

                $specs['debet_exp']['value'] = isset($expDatasSum[$pId]['debet']) ? $expDatasSum[$pId]['debet'] : 0;
                $specs['debet_exp']['attr'] = "class='text-right'";
            }
            //endregion

            //endregion


            //region outgoing keluar

            //region internal
            $specs['qty_kredit_int']['value'] = isset($intrDatasSum[$pId]['qty_kredit']) ? $intrDatasSum[$pId]['qty_kredit'] : 0;
            $specs['qty_kredit_int']['attr'] = "class='text-right'";

            if ((isset($intrDatasSum[$pId]['qty_kredit'])) && ($intrDatasSum[$pId]['qty_kredit'] > 0)) {
                // $harga_satuan = $intrDatas[$pId]['harga'] *1 ;
                $harga_satuan = $intrDatasSum[$pId]['kredit'] / $intrDatasSum[$pId]['qty_kredit'];
            }
            else {
                $harga_satuan = 0;
            }
            // $specs['harga_kredit_int']['value'] = isset($intrDatasSum[$pId]['harga_kredit']) ? $intrDatasSum[$pId]['harga_kredit'] : 0;
            $specs['harga_kredit_int']['value'] = isset($intrDatas[$pId]['harga']) ? $harga_satuan : 0;
            $specs['harga_kredit_int']['attr'] = "class='text-right'";

            $specs['kredit_int']['value'] = isset($intrDatasSum[$pId]['kredit']) ? $intrDatasSum[$pId]['kredit'] : 0;
            $specs['kredit_int']['attr'] = "class='text-right'";
            //endregion

            //region external
            if (sizeof($jenisExternal) > 0) {
                $specs['qty_kredit']['value'] = isset($extrDatasSum[$pId]['qty_kredit']) ? $extrDatasSum[$pId]['qty_kredit'] : 0;
                $specs['qty_kredit']['attr'] = "class='text-right'";

                if ((isset($extrDatasSum[$pId]['qty_kredit'])) && ($extrDatasSum[$pId]['qty_kredit'] > 0)) {
                    // $harga_satuan = $intrDatas[$pId]['harga'] *1 ;
                    $harga_satuan = $extrDatasSum[$pId]['kredit'] / $extrDatasSum[$pId]['qty_kredit'];
                }
                else {
                    $harga_satuan = 0;
                }
                // $specs['harga_kredit']['value'] = isset($extrDatasSum[$pId]['harga_kredit']) ? $extrDatasSum[$pId]['harga_kredit'] : 0;
                $specs['harga_kredit']['value'] = isset($intrDatas[$pId]['harga']) ? $harga_satuan : 0;
                $specs['harga_kredit']['attr'] = "class='text-right'";

                $specs['kredit']['value'] = isset($extrDatasSum[$pId]['kredit']) ? $extrDatasSum[$pId]['kredit'] : 0;
                $specs['kredit']['attr'] = "class='text-right'";
            }
            //endregion

            //region bom
            if (sizeof($jenisBom) > 0) {
                $on = 1;
                if ($on == 1) {

                    $specs['qty_kredit_bom']['value'] = isset($bomDatasSum[$pId]['qty_kredit']) ? $bomDatasSum[$pId]['qty_kredit'] : 0;
                    $specs['qty_kredit_bom']['attr'] = "class='text-right'";

                    if ((isset($bomDatasSum[$pId]['qty_kredit'])) && ($bomDatasSum[$pId]['qty_kredit'] > 0)) {
                        $harga_satuan = $bomDatasSum[$pId]['kredit'] / $bomDatasSum[$pId]['qty_kredit'];
                    }
                    else {
                        $harga_satuan = 0;
                    }
                    $specs['harga_kredit_bom']['value'] = isset($bomDatas[$pId]['harga']) ? $harga_satuan : 0;
                    $specs['harga_kredit_bom']['attr'] = "class='text-right'";

                    $specs['kredit_bom']['value'] = isset($bomDatasSum[$pId]['kredit']) ? $bomDatasSum[$pId]['kredit'] : 0;
                    $specs['kredit_bom']['attr'] = "class='text-right'";
                }
            }
            //endregion

            //region notgood
            if (sizeof($jenisNG) > 0) {
                $on = 1;
                if ($on == 1) {

                    $specs['qty_kredit_ng']['value'] = isset($ngDatasSum[$pId]['qty_kredit']) ? $ngDatasSum[$pId]['qty_kredit'] : 0;
                    $specs['qty_kredit_ng']['attr'] = "class='text-right'";

                    if ((isset($ngDatasSum[$pId]['qty_kredit'])) && ($ngDatasSum[$pId]['qty_kredit'] > 0)) {
                        $harga_satuan = $ngDatasSum[$pId]['kredit'] / $ngDatasSum[$pId]['qty_kredit'];
                    }
                    else {
                        $harga_satuan = 0;
                    }
                    $specs['harga_kredit_ng']['value'] = isset($ngDatas[$pId]['harga']) ? $harga_satuan : 0;
                    $specs['harga_kredit_ng']['attr'] = "class='text-right'";

                    $specs['kredit_ng']['value'] = isset($ngDatasSum[$pId]['kredit']) ? $ngDatasSum[$pId]['kredit'] : 0;
                    $specs['kredit_ng']['attr'] = "class='text-right'";
                }
            }
            //endregion

            //region biayakan
            if (sizeof($jenisBiaya) > 0) {
                $on = 1;
                if ($on == 1) {

                    $specs['qty_kredit_exp']['value'] = isset($expDatasSum[$pId]['qty_kredit']) ? $expDatasSum[$pId]['qty_kredit'] : 0;
                    $specs['qty_kredit_exp']['attr'] = "class='text-right'";

                    if ((isset($expDatasSum[$pId]['qty_kredit'])) && ($expDatasSum[$pId]['qty_kredit'] > 0)) {
                        $harga_satuan = $expDatasSum[$pId]['kredit'] / $expDatasSum[$pId]['qty_kredit'];
                    }
                    else {
                        $harga_satuan = 0;
                    }
                    $specs['harga_kredit_exp']['value'] = isset($expDatas[$pId]['harga']) ? $harga_satuan : 0;
                    $specs['harga_kredit_exp']['attr'] = "class='text-right'";

                    $specs['kredit_exp']['value'] = isset($expDatasSum[$pId]['kredit']) ? $expDatasSum[$pId]['kredit'] : 0;
                    $specs['kredit_exp']['attr'] = "class='text-right'";
                }
            }
            //endregion

            //endregion


            //region ending

            $qtyDebetAkhirLast = isset($allDatas2[$pId]) ? $allDatas2[$pId]['qty_debet_akhir'] : 0;
            $debetAkhirLast = isset($allDatas2[$pId]) ? $allDatas2[$pId]['debet_akhir'] : 0;

            $specs['qty_akhir']['value'] = isset($allDataAkhir[$pId]['qty_debet_akhir']) ? $allDataAkhir[$pId]['qty_debet_akhir'] : $qtyDebetAkhirLast;
            $specs['qty_akhir']['attr'] = "class='text-right'";

            if (isset($allDataAkhir[$pId]['qty_debet_akhir'])) {
                if ($allDataAkhir[$pId]['qty_debet_akhir'] > 0) {
                    //                $harga_satuan = $allDataAkhir[$pId]['harga_akhir'] * 1;
                    $harga_satuan = $allDataAkhir[$pId]['debet_akhir'] / $allDataAkhir[$pId]['qty_debet_akhir'];
                }
                else {
                    $harga_satuan = 0;
                }
            }
            else {
                if ((isset($allDatas2[$pId]['qty_debet_akhir'])) && ($allDatas2[$pId]['qty_debet_akhir'] > 0)) {
                    $harga_satuan = $allDatas2[$pId]['debet_akhir'] / $allDatas2[$pId]['qty_debet_akhir'];
                }
                else {
                    $harga_satuan = 0;
                }
            }

            // $specs['harga_akhir']['value'] = isset($allDataAkhir[$pId]['harga_akhir']) ? $allDataAkhir[$pId]['harga_akhir'] : 0;
            //            $specs['harga_akhir']['value'] = isset($allDataAkhir[$pId]['harga_akhir']) ? $harga_satuan : "**";
            $specs['harga_akhir']['value'] = $harga_satuan;
            $specs['harga_akhir']['attr'] = "class='text-right'";

            // $specs['akhir']['value'] = isset($allDataAkhir[$pId]['debet_akhir']) ? $allDataAkhir[$pId]['debet_akhir'] : 0;
            $debetAkhirLast = isset($allDatas2[$pId]) ? $allDatas2[$pId]['harga_avg'] * $allDatas2[$pId]['qty_debet_akhir'] : 0;
            $specs['akhir']['value'] = isset($allDataAkhir[$pId]['debet_akhir']) ? $allDataAkhir[$pId]['debet_akhir'] : $debetAkhirLast;
            $specs['akhir']['attr'] = "class='text-right'";

            //endregion


            $bodies[] = $specs;

            // if(!isset($sumvalues[]))
        }
        //endregion


        // region footers
        $footers = array(
            "total" => "colspan='3' class='text-right bg-info text-uppercase'",
        );
        // endregion footers


        $lastDay = formatTanggal($date1, "t");
        $date2 = "$th-$bl-$lastDay";
        $dRange = formatField("dtime", $date1) . " s/d " . formatField("dtime", $date2);
        $dateRange = "($dRange)";

        $strO = isset($_GET['o']) ? "&o=" . $_GET['o'] : "";
        $strDate1 = isset($_GET['date1']) ? "&date1=" . $_GET['date1'] : "";

        $btnGroups = array(
            // "all"      => array(
            //     "label" => "All Movement",
            //     "link"  => "Ledger/viewMovementGroup/fg?mv=all" . $strO . $strDate1,
            // ),
            // "external" => array(
            //     "label" => "external Movement",
            //     "link"  => "Ledger/viewMovementGroup/fg?mv=external" . $strO . $strDate1,
            // ),
            // "internal" => array(
            //     "label" => "internal Movement",
            //     "link"  => "Ledger/viewMovementGroup/fg?mv=internal" . $strO . $strDate1,
            // ),
        );
        //        $dateRange = "<span class='font-size-0-8 text-grey-2'>$dRange</span>";
        // <span class='blink meta'>save mode data terlimit</span>
        $data = array(
            "mode" => "movementGroup",
            "title" => "$itemLabel movements $dateRange",
            "subTitle" => "",
            // "items"       => array(),
            // "items2"      => array(),
            "date1" => $date1,
            "date2" => $date2,

            "rowLoop" => 6,
            "mainHeaders" => $mainHeaders,
            "subHeaders" => $subHeaders,
            "bodies" => $bodies,
            "footers" => $footers,
            "sumfooters" => $arrSumView,
            "footersBlacklist" => array("no", "id", "kode", "nama"),
            "btnGroups" => $btnGroups,
            "mdlFields" => isset($mdlNameS[$item]['mdlFields']) ? $mdlNameS[$item]['mdlFields'] : array(),

            "detailsLabels" => "",
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?o=$cabangID",
        );
        //endregion

        //        arrprint($data);
        $this->load->view("ledger", $data);
    }

    public function viewMovement()
    {
        $tbl_src = "__rek_pembantu_produk__persediaan_produk";
        $tbl_hr = "__rek_pembantu_produk__persediaan_produk_hr";
        $tbl_bl = "__rek_pembantu_produk__persediaan_produk_bl";
        // $tbl_bl = "__rek_pembantu_produk__persediaan_produk_bl_external";
        $this->load->config("heTransaksi_report");
        $item = $this->uri->segment(3);
        $cabangID = $this->placeID;
        $date = $date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow("Y-m") . "-01";
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

        $mdlNameS = $this->config->item('heMovement') ? $this->config->item('heMovement') : array();

        // cekHitam($item);
        // arrPrint($mdlNameS[$item]);
        $comName = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['com'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        // $itemLabel = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['label'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $itemLabel = isset($mdlNameS[$item]['label']) ? $mdlNameS[$item]['label'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $mdlName = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['mdl'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $rekening = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['rek'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $comName);
        $this->load->model("Mdls/" . $mdlName);
        $com = new $comName();
        // $com = new ComRekeningPembantuProduk();
        $mdl = new $mdlName();
        $mdlFields = key_exists($item, $mdlNameS) ? array_keys($mdlNameS[$item]['mdlFields']) : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");

        foreach ($mdlNameS[$item]['mdlFields'] as $field => $fChilds) {
            $fields[] = $field;
            isset($fChilds['label']) ? $fieldToshows[$field] = $fChilds['label'] : "";
            isset($fChilds['attr']) ? $fieldAttr[$field] = $fChilds['attr'] : "";
            isset($fChilds['attrHeader']) ? $fieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            isset($fChilds['link']) ? $fieldLink[$field] = $fChilds['link'] : "";
            isset($fChilds['format']) ? $fieldFormat[$field] = $fChilds['format'] : "";
        }

        // arrPrint($fieldToshows);
        //region penyusun header table nganbil dari config
        $specs_0 = array();
        $mainHeaders_00 = array();
        $specs_0["no"] = 0;
        foreach ($fieldToshows as $field => $fieldToshow) {
            $fAttr = isset($fieldAttrHeader[$field]) ? $fieldAttrHeader[$field] : "-";
            $specs_0[$fieldToshow] = $fAttr;

            $mainHeaders_00 = $specs_0;
            $rNo["no"] = $fAttr;
        }
        $mainHeaders_0 = array_replace($mainHeaders_00, $rNo);
        $mainHeaders_1 = isset($mdlNameS[$item]['mainHeaders_1']) ? $mdlNameS[$item]['mainHeaders_1'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $mainHeaders = array_merge($mainHeaders_0, $mainHeaders_1);
        $subHeaders = isset($mdlNameS[$item]['subHeaders']) ? $mdlNameS[$item]['subHeaders'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        //endregion

        $list_produkId = "";
        // $list_produkId = "76,386,80,34,36,37,38,39";
        // $list_produkId = "76,8";
        // $list_produkId = "102";

        $bl = formatTanggal($date, "n");
        $th = formatTanggal($date, "Y");
        $arrWhere = array(
            // "extern_id" => $src_extern_id,
            "cabang_id" => $cabangID,
            // "gudang_id" => $src_gudang_id,
            "bln" => $bl,
            "thn" => $th,
        );
        if (strlen($list_produkId) > 1) {

            $this->db->where_in("extern_id", explode(",", $list_produkId));
        }
        $this->db->where($arrWhere);
        $srcBl = $this->db->get($tbl_bl)->result();
        // showLast_query("lime");
        // arrPrint($srcBl);
        // $srcDatas = sizeof($srcBl) > 0 ? $srcBl[0] : array();

        // if (sizeof($srcBl) == 0) {
        $arrWhere = array(
            // "extern_id" => $src_extern_id,
            "cabang_id" => $cabangID,
            // "gudang_id" => $src_gudang_id,
            // "bln"       => $bl,
            // "thn"       => $th,
            "dtime <" => "$th-$bl-1",
        );
        if (strlen($list_produkId) > 1) {
            $this->db->where_in("extern_id", explode(",", $list_produkId));
        }
        // $this->db->limit(1);
        $this->db->order_by("id", "asc");

        $this->db->where($arrWhere);
        $srcBlLast = $this->db->get($tbl_bl)->result();
        // showLast_query("merah");
        // arrPrintWebs($srcBlLast);
        foreach ($srcBlLast as $srcSpecs) {
            $dataSource2[$srcSpecs->extern_id] = (array)$srcSpecs;
        }
        // }


        $srcDatas = sizeof($srcBl) > 0 ? $srcBl : array();

        if ($this->placeID <= 0) {
            $mdl->setFilters(array());
            if (strlen($list_produkId) > 1) {
                $mdl->addFilter("id in ($list_produkId)");
            }
            $mdl->db->where("jenis in ('item_rakitan','item') AND `status` = '1' AND `trash` = '0'");
        }
        // limit untuk proses debuging saja
        //  $mdl->addFilter("id='41'");
        // $mdl->addFilter("id='80'");
        //         $this->db->limit(10);

        $produks = $mdl->lookupAll()->result();

        //region data seluruh produk
        foreach ($produks as $pDatas) {
            foreach ($mdlFields as $mdlField) {
                $$mdlField = $pDatas->$mdlField;
            }
            foreach ($fieldToshows as $mdlField => $label) {
                $$mdlField = $pDatas->$mdlField;
                $specs[$mdlField] = $pDatas->$mdlField;
            }

            $arrProId[$id] = $specs;
        }
        //endregion

        foreach ($srcDatas as $srcSpecs) {
            $dataSource[$srcSpecs->extern_id] = (array)$srcSpecs;
        }


        // arrPrint($arrQtyHarga);
        // matiHere();
        // arrPrint($arrBeAf);
        // arrPrint($arrProId);
        // arrPrint($arrMutasi);
        // mati_disini();
        $mutasiKoloms = array(
            "qty_debet_awal",
            "harga_debet_awal",
            "debet_awal",

            "qty_debet",
            "harga_debet",
            "debet",

            "qty_avail",
            "harga_avail",
            "avail",

            "qty_kredit",
            "harga_kredit",
            "kredit",

            "qty_akhir",
            "harga_akhir",
            "akhir",
        );
        $specs = array();
        $bodies = array();
        $no = 0;

        //region body produk
        foreach ($arrProId as $pId => $pSpecs) {

            // arrPrint($pSpecs);
            $no++;
            // $dataMutasi = isset($arrMutasi[$pId]) ? $arrMutasi[$pId] : $arrMutasi_3[$pId];
            $debet = "**";
            // arrPrintWebs($pSpecs);
            $specs['no']['value'] = $no;
            $specs['no']['attr'] = "class='text-right'";
            //region data produk
            foreach ($fieldToshows as $mdlField => $label) {
                $$mdlField = $pSpecs[$mdlField];

                $value = $pSpecs[$mdlField];

                if (isset($fieldLink[$mdlField])) {
                    $specs[$mdlField]['value'] = "<a target=\"_blank\" href=\"" . base_url() . $fieldLink[$mdlField] . $pId . "/?o=$cabangID\">" . $value . "</a>";
                    // $specs[$mdlField]['value'] = "<a href=\"#\">$value</a>";
                }
                else {

                    $specs[$mdlField]['value'] = $value;
                }

                // $specs[$mdlField]['value'] = ;
                $specs[$mdlField]['attr'] = isset($fieldAttr[$mdlField]) ? $fieldAttr[$mdlField] : "class='text-left'";
            }
            //endregion

            // arrPrint($dataSource);
            $dataMutasi = isset($dataSource[$pId]) ? $dataSource[$pId] : array();


            if (isset($dataSource[$pId])) {
                $harga_debet_awal = isset($dataSource[$pId]) && $dataSource[$pId]['qty_debet_awal'] > 0 ? $dataSource[$pId]['qty_debet_awal'] . "x&nbsp;" . formatField("number", $dataSource[$pId]['harga_awal'] * 1) : "-";
                $dataMutasi["harga_debet_awal"] = $harga_debet_awal;
            }
            else {
                // if (!isset($dataSource[$pId])) {
                // matiHere();
                // arrPrint($dataSource2);
                $harga_debet_awal = isset($dataSource2[$pId]) && $dataSource2[$pId]['qty_debet_akhir'] > 0 ? $dataSource2[$pId]['qty_debet_akhir'] . "x&nbsp;" . formatField("number", $dataSource2[$pId]['harga'] * 1) : "-";
                $dataMutasi["harga_debet_awal"] = isset($harga_debet_awal) ? $harga_debet_awal : 0;
                $dataMutasi["qty_debet_awal"] = isset($dataSource2[$pId]['qty_debet_akhir']) ? $dataSource2[$pId]['qty_debet_akhir'] : 0;
                $dataMutasi["debet_awal"] = isset($dataSource2[$pId]['debet_akhir']) ? $dataSource2[$pId]['debet_akhir'] : 0;
            }


            $harga_debet = isset($dataSource[$pId]) && $dataSource[$pId]['qty_debet'] > 0 ? $dataSource[$pId]['qty_debet'] . "x&nbsp;" . number_format($dataSource[$pId]['harga'] * 1, "0", "", ",") : "-";
            $dataMutasi["harga_debet"] = $harga_debet;
            if (isset($dataSource[$pId])) {
                $qty_avail = isset($dataSource[$pId]) ? $dataSource[$pId]['qty_debet_awal'] + $dataSource[$pId]['qty_debet'] : 0;
                $harga_avail = isset($dataSource[$pId]) && $qty_avail > 0 ? $qty_avail . "x&nbsp;" . number_format($dataSource[$pId]['harga'] * 1, "0", "", ",") : "-";
            }
            else {
                $qty_avail = isset($dataSource2[$pId]) ? $dataSource2[$pId]['qty_debet_awal'] + $dataSource2[$pId]['qty_debet'] : 0;
                $harga_avail = isset($dataSource2[$pId]) && $qty_avail > 0 ? $qty_avail . "x&nbsp;" . number_format($dataSource2[$pId]['harga'] * 1, "0", "", ",") : "-";
            }

            $dataMutasi["harga_avail"] = $harga_avail;

            $harga_kredit = isset($dataSource[$pId]) && $dataSource[$pId]['qty_kredit'] > 0 ? $dataSource[$pId]['qty_kredit'] . "x&nbsp;" . number_format($dataSource[$pId]['harga'] * 1, "0", "", ",") : "-";
            $dataMutasi["harga_kredit"] = $harga_kredit;

            // $qty_akhir = isset($dataSource[$pId]) ? $dataSource[$pId]['qty_akhir'] + $dataSource[$pId]['qty_debet'] :0;
            // if (sizeof($dataSource) > 0) {
            if (isset($dataSource[$pId])) {
                $qty_akhir = isset($dataSource[$pId]) ? $dataSource[$pId]['qty_debet_akhir'] : 0;
                $harga_akhir = isset($dataSource[$pId]) && $qty_akhir > 0 ? $qty_akhir . "x&nbsp;" . number_format($dataSource[$pId]['harga'] * 1, "0", "", ",") : "-";
                $dataMutasi["harga_akhir"] = $harga_akhir;
            }
            else {
                $qty_akhir = isset($dataSource2[$pId]) ? $dataSource2[$pId]['qty_debet_akhir'] : 0;
                $harga_akhir = isset($dataSource2[$pId]) && $qty_akhir > 0 ? $qty_akhir . "x&nbsp;" . number_format($dataSource2[$pId]['harga'] * 1, "0", "", ",") : "-";
                $dataMutasi["harga_akhir"] = $harga_akhir;
                $dataMutasi["debet_akhir"] = $dataSource2[$pId]['debet_akhir'];
            }

            // arrPrint($mutasiKoloms);
            // cekMerah($pId . __METHOD__ . __LINE__);
            // arrPrint($dataMutasi);

            foreach ($mutasiKoloms as $mutasiKolom_0) {

                $mutasiKolom = isset($mutasiKolom_0) ? $mutasiKolom_0 : "--";
                $qtyDebetAwal = isset($dataMutasi['qty_debet_awal']) ? $dataMutasi['qty_debet_awal'] : 0;

                $debetAwal = isset($dataMutasi['debet_awal']) ? $dataMutasi['debet_awal'] : 0;
                $hargaAkhir = isset($dataMutasi['harga_akhir']) ? $dataMutasi['harga_akhir'] : 0;
                $debetAkhir = isset($dataMutasi['debet_akhir']) ? $dataMutasi['debet_akhir'] : 0;

                $qtyDebet = isset($dataMutasi['qty_debet']) ? $dataMutasi['qty_debet'] : 0;
                $qtyKredit = isset($dataMutasi['qty_kredit']) ? $dataMutasi['qty_kredit'] : 0;


                if (($mutasiKolom == "debet_awal") && ($qtyDebetAwal == 0)) {
                    $val2 = 0;
                }
                elseif (($mutasiKolom == "debet") && ($qtyDebet == 0)) {
                    $val2 = 0;
                    $val2_debet = 0;
                }
                elseif (($mutasiKolom == "qty_avail")) {
                    $val2 = $qtyDebetAwal + $qtyDebet;
                }
                elseif (($mutasiKolom == "avail")) {
                    $val2 = $debetAwal + (($mutasiKolom == "debet") && ($qtyDebet == 0)) ? 0 : $debet;
                }
                elseif (($mutasiKolom == "kredit") && ((isset($dataMutasi['qty_kredit']) ? $dataMutasi['qty_kredit'] : 0) == 0)) {
                    $val2 = 0;
                }
                elseif (($mutasiKolom == "qty_akhir")) {
                    $val2 = $qtyDebetAwal + $qtyDebet - $qtyKredit;
                }
                elseif (($mutasiKolom == "akhir")) {
                    // $val2 = number_format($debetAkhir, "0", ".", ",");
                    $val2 = $debetAkhir;
                }
                else {
                    $val2 = isset($dataMutasi[$mutasiKolom]) ? $dataMutasi[$mutasiKolom] : 0;
                }
                // $specs[$mutasiKolom]['value'] = isset($dataMutasi[$mutasiKolom]) ? $dataMutasi[$mutasiKolom] : "--";
                $specs[$mutasiKolom]['value'] = $val2;
                // $specs[$mutasiKolom]['value'] = "**";
                $specs[$mutasiKolom]['attr'] = "class='text-right'";

                // cekHijau("$mutasiKolom ** $val2");
                if (!isset($sumQty[$mutasiKolom])) {
                    $sumQty[$mutasiKolom] = 0;
                }
                $sumQty[$mutasiKolom] += $val2;
            }

            $bodies[] = $specs;
        }
        //endregion

        // arrPrint($sumQty);
        // region footers
        $footers = array(
            "total" => "colspan='3' class='text-right bg-info text-uppercase'",
            formatField("qty_debet_awal", $sumQty['qty_debet_awal']) => "class='text-right bg-info text-uppercase'",
            "-" => "class='text-right bg-info text-uppercase'",
            formatField("debet_awal", $sumQty['debet_awal']) => "class='text-right bg-info text-uppercase'",

            formatField("qty_debet", $sumQty['qty_debet']) => "class='text-right bg-info text-uppercase'",
            "--" => "class='text-right bg-info text-uppercase'",
            formatField("debet", $sumQty['debet']) => "class='text-right bg-info text-uppercase'",

            formatField("qty_avail", $sumQty['qty_avail']) => "class='text-right bg-info text-uppercase'",
            "." => "class='text-right bg-info text-uppercase'",
            formatField("avail", $sumQty['avail']) => "class='text-right bg-info text-uppercase'",

            formatField("qty_kredit", $sumQty['qty_kredit']) => "class='text-right bg-info text-uppercase'",
            ".." => "class='text-right bg-info text-uppercase'",
            formatField("kredit", $sumQty['kredit']) => "class='text-right bg-info text-uppercase'",

            formatField("qty_akhir", $sumQty['qty_akhir']) => "class='text-right bg-info text-uppercase'",
            "" => "class='text-right bg-info text-uppercase'",
            formatField("akhir", $sumQty['akhir']) => "class='text-right bg-info text-uppercase'",
        );
        // endregion footers
        //         arrPrint($bodies);
        // matiHere();
        // $month = date("Y-m",$date1);
        // cekHitam($month);
        // $dRange = formatField("dtime", $date1) . " s/d " . formatField("dtime", $date2);
        $lastDay = formatTanggal($date1, "t");
        $date2 = "$th-$bl-$lastDay";
        $dRange = formatField("dtime", $date1) . " s/d " . formatField("dtime", $date2);
        $dateRange = "($dRange)";
        //        $dateRange = "<span class='font-size-0-8 text-grey-2'>$dRange</span>";
        // <span class='blink meta'>save mode data terlimit</span>
        $data = array(
            "mode" => "movement",
            "title" => "$itemLabel movements $dateRange",
            "subTitle" => "",
            // "items"       => array(),
            // "items2"      => array(),
            "date1" => $date1,
            "date2" => $date2,

            "rowLoop" => 5,
            "mainHeaders" => $mainHeaders,
            "subHeaders" => $subHeaders,
            "bodies" => $bodies,
            "footers" => $footers,
            "sumfooters" => $sumQty,

            "detailsLabels" => "",
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?o=$cabangID",
        );
        //endregion

        //        arrprint($data);
        $this->load->view("ledger", $data);
    }

    public function viewMovement_v2()
    {
        $this->load->config("heTransaksi_report");
        $item = $this->uri->segment(3);
        $cabangID = $this->placeID;
        $date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow("Y-m") . "-01";
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

        $mdlNameS = $this->config->item('heMovement') ? $this->config->item('heMovement') : array();

        // cekHitam($item);
        // arrPrint($mdlNameS[$item]);
        $comName = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['com'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        // $itemLabel = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['label'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $itemLabel = isset($mdlNameS[$item]['label']) ? $mdlNameS[$item]['label'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $mdlName = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['mdl'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $rekening = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['rek'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $comName);
        $this->load->model("Mdls/" . $mdlName);
        $com = new $comName();
        // $com = new ComRekeningPembantuProduk();
        $mdl = new $mdlName();
        $mdlFields = key_exists($item, $mdlNameS) ? array_keys($mdlNameS[$item]['mdlFields']) : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");

        foreach ($mdlNameS[$item]['mdlFields'] as $field => $fChilds) {
            $fields[] = $field;
            isset($fChilds['label']) ? $fieldToshows[$field] = $fChilds['label'] : "";
            isset($fChilds['attr']) ? $fieldAttr[$field] = $fChilds['attr'] : "";
            isset($fChilds['attrHeader']) ? $fieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            isset($fChilds['link']) ? $fieldLink[$field] = $fChilds['link'] : "";
            isset($fChilds['format']) ? $fieldFormat[$field] = $fChilds['format'] : "";
        }

        // arrPrint($fieldToshows);
        //region penyusun header table nganbil dari config
        $specs_0 = array();
        $mainHeaders_00 = array();
        $specs_0["no"] = 0;
        foreach ($fieldToshows as $field => $fieldToshow) {
            $fAttr = isset($fieldAttrHeader[$field]) ? $fieldAttrHeader[$field] : "-";
            $specs_0[$fieldToshow] = $fAttr;

            $mainHeaders_00 = $specs_0;
            $rNo["no"] = $fAttr;
        }
        $mainHeaders_0 = array_replace($mainHeaders_00, $rNo);
        $mainHeaders_1 = isset($mdlNameS[$item]['mainHeaders_1']) ? $mdlNameS[$item]['mainHeaders_1'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $mainHeaders = array_merge($mainHeaders_0, $mainHeaders_1);
        $subHeaders = isset($mdlNameS[$item]['subHeaders']) ? $mdlNameS[$item]['subHeaders'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        //endregion

        function nanMovement($qty, $value)
        {
            if (($qty <= 0) && ($value > 0)) {
                $var = "Nan";
            }
            elseif (($qty == 0) && ($value > 0)) {
                $var = "Nan";
            }
            elseif (($qty > 0) && ($value <= 0)) {
                $var = "Nan";
            }
            else {
                $var = $value;
            }

            return $var;
        }

        // $mdl = new MdlProduk();
        // $com = new ComRekeningPembantuProduk();

        $list_produkId = "";
        // $list_produkId = "76,386";
        // $list_produkId = "76,80";
        $bKoloms = array(
            "extern_id",
            "debet_awal",
            "debet",
            "kredit",
            "debet_akhir",
            // "kredit_awal",
            // "kredit_akhir",
            "qty_debet_awal",
            "qty_debet",
            "qty_kredit",
            // "qty_kredit_akhir",
            // "qty_kredit_awal",
            "qty_debet_akhir",
            "harga",
            "harga_avg",
        );
        if (strlen($list_produkId) > 1) {
            $com->addFilter("extern_id in ($list_produkId)");
        }

        // $com->addFilter("extern_id='" . 315 . "'");
        $com->addFilter("cabang_id='" . $this->placeID . "'");
        $com->addFilter("gudang_id='" . $this->gudangID . "'");
        $com->db->where("DATE(dtime) >= '$date1' AND DATE(dtime) <= '$date2'");
        $tmpBegin = $com->fetchMovement($rekening);
        // cekBiru($this->db->last_query() . "<hr> row:: " . sizeof($tmpBegin));
        // arrPrint($tmpBegin);

        $com->addFilter("cabang_id='" . $this->placeID . "'");
        $com->db->where("DATE(dtime) <= '$date2'");
        $tmpUnbegin_0 = $com->fetchMovement($rekening);
        $tmpUnbegin = array();
        foreach ($tmpUnbegin_0 as $itemSpecs) {
            $ubExtern_id = $itemSpecs->extern_id;
            $tmpUnbegin[$ubExtern_id] = $itemSpecs;
        }
        // cekMerah($this->db->last_query() . " <hr> row:: " . $comName . " ===== " . sizeof($tmpUnbegin));


        // $com->addFilter("extern_id='" . 315 . "'");
        $com->addFilter("cabang_id='" . $this->placeID . "'");
        $com->db->where("DATE(dtime) >= '$date1' AND DATE(dtime) <= '$date2'");
        $tmpRange = $com->fetchMovement($rekening);
        // cekHijau($this->db->last_query() . " <hr>row:: " . $comName . " ====== " . sizeof($tmpRange));

        // arrPrint($tmpBegin);
        // arrPrintWebs($tmpUnbegin);
        // arrPrint($tmpRange);

        // $mdl->addFilter("jenis='" . "item_rakitan" . "'");
        // $pro->setFilters(array());
        if ($this->placeID <= 0) {
            $mdl->setFilters(array());
            if (strlen($list_produkId) > 1) {
                $mdl->addFilter("id in ($list_produkId)");
            }
            $mdl->db->where("jenis in ('item_rakitan','item') AND `status` = '1' AND `trash` = '0'");
        }
        // limit untuk proses debuging saja
        //  $mdl->addFilter("id='41'");
        // $mdl->addFilter("id='80'");
        //         $this->db->limit(10);

        $produks = $mdl->lookupAll()->result();
        // cekLime($this->db->last_query() . "<hr>$item ---- $mdlName");

        // arrPrint($produks);
        // arrPrint($mdlFields);
        // arrPrint($fieldToshows);
        // arrPrint($tmpBegin);
        // matiHere(__LINE__);

        /*---------------------------
         * mutasi tanpa batasan tanggal mulai
         * ------------------------*/
        // arrPrint($tmpUnbegin);
        $arrMutasi_3 = array();
        foreach ($tmpUnbegin as $rDatas) {
            foreach ($bKoloms as $bKolom) {
                $$bKolom = $rDatas->$bKolom;
            }
            //cekHere(":: $extern_id :: $qty_debet_akhir ::");
            //            $arrMutasi_3[$extern_id]['harga_debet_awal'] = number_format($harga, "0", ".", ",");
            $arrMutasi_3[$extern_id]['qty_debet_awal'] = $qty_debet_akhir;
            $arrMutasi_3[$extern_id]['qty_debet'] = 0;
            $arrMutasi_3[$extern_id]['qty_kredit'] = 0;
            $arrMutasi_3[$extern_id]['qty_debet_akhir'] = $qty_debet_akhir;
            $arrMutasi_3[$extern_id]['qty_harga_debet'] = 0;
            if ($qty_debet_akhir > 0) {
                if (!isset($arrMutasi_3[$extern_id]['qty_harga_debet_awal'][$harga])) {
                    $arrMutasi_3[$extern_id]['qty_harga_debet_awal'][$harga] = 0;
                }
                $arrMutasi_3[$extern_id]['qty_harga_debet_awal'][$harga] += $qty_debet_akhir;
            }

            $arrMutasi_3[$extern_id]['debet_awal'] = $debet_akhir;
            $arrMutasi_3[$extern_id]['debet'] = $debet;
            $arrMutasi_3[$extern_id]['kredit'] = $kredit;
            $arrMutasi_3[$extern_id]['debet_akhir'] = $debet_akhir;

            if ($qty_kredit > 0) {
                if (!isset($arrMutasi_3[$extern_id]['qty_harga_kredit'][$harga])) {
                    $arrMutasi_3[$extern_id]['qty_harga_kredit'][$harga] = 0;
                }
                $arrMutasi_3[$extern_id]['qty_harga_kredit'][$harga] += $qty_kredit;
            }
            // $arrMutasi_3[$produk_id]['on_hpp'] = $on_hpp;

            $on_qty = $qty_debet + $qty_kredit;
            if ($on_qty > 0) {
                $on_hpp_3 = ($kredit + $debet) / $on_qty;
                // $arrMutasi[$extern_id]['on_hpp'] = ($kredit + $debet) / ($qty_debet + $qty_kredit);
                //                 cekMerah("$extern_id = ($kredit + $debet) / ($qty_debet + $qty_kredit)");
            }
            else {
                // $arrMutasi[$extern_id]['on_hpp'] = ($kredit + $debet);
                // $on_hpp_3 = ($kredit + $debet);
                $on_hpp_3 = ($kredit + $debet);
                // cekBiru("$extern_id = ($kredit + $debet) / 00001)");
            }

            // if (($qty_debet + $qty_kredit) > 0) {
            //     $on_hpp_3 = ($kredit + $debet) / ($qty_debet + $qty_kredit);
            //     // $arrMutasi_3[$extern_id]['on_hpp'] = ($kredit + $debet) / ($qty_debet + $qty_kredit);
            // }
            // else {
            //     $on_hpp_3 = ($kredit + $debet);
            //     // $arrMutasi_3[$extern_id]['on_hpp'] = ($kredit + $debet);
            // }
            if (!isset($arrMutasi_3[$extern_id]['on_hpp'])) {
                $arrMutasi_3[$extern_id]['on_hpp'] = 0;
            }
            $arrMutasi_3[$extern_id]['on_hpp'] += "999" . $on_hpp_3;
        }
        //         arrPrint($tmpUnbegin);
        //         arrPrint($arrMutasi_3);
        /*---------------------------
         * data stok awal dan akhir
         * $arrBeAf
         * ------------------------*/
        $spec = array();
        foreach ($tmpBegin as $bDatas) {
            foreach ($bKoloms as $bKolom) {
                $$bKolom = $bDatas->$bKolom;
            }
            $spec['qty_debet_awal'] = $qty_debet_awal;
            $spec['qty_debet_akhir'] = $qty_debet_akhir;
            if (($qty_debet + $qty_kredit) > 0) {
                $spec['on_hpp'] = ($kredit + $debet) / ($qty_debet + $qty_kredit);
            }
            else {
                $spec['on_hpp'] = ($kredit + $debet);
            }
            $arrBeAf[$extern_id][] = $spec;
        }
        // arrPrintWebs($arrBeAf);
        //         arrPrint($tmpRange);
        /*---------------------------
         * mutasi dengan range tanggal
         * ------------------------*/
        $arrMutasi = array();
        $no = 0;
        //arrPrint($tmpRange);
        foreach ($tmpRange as $rDatas) {
            $no++;
            foreach ($bKoloms as $bKolom) {
                $$bKolom = $rDatas->$bKolom;
            }
            $harga = $harga_avg;
            $jml_be = $arrBeAf[$extern_id][0]["qty_debet_awal"];
            //            cekHere(":: $jml_be ::");
            $arrEnd = end($arrBeAf[$extern_id])["qty_debet_akhir"];
            if ($jml_be > 0) {
                if (!isset($arrMutasi[$extern_id]['qty_harga_debet_awal'][$harga])) {
                    $arrMutasi[$extern_id]['qty_harga_debet_awal'][$harga] = 0;
                }
                $arrMutasi[$extern_id]['qty_harga_debet_awal'][$harga] = $jml_be;
            }
            // else{
            //     $arrMutasi[$extern_id]['qty_harga_debet_awal'][$harga] = "**";
            // }
            $arrMutasi[$extern_id]['qty_debet_awal'] = $jml_be;
            if (!isset($arrMutasi[$extern_id]['qty_debet'])) {
                $arrMutasi[$extern_id]['qty_debet'] = 0;
            }
            $arrMutasi[$extern_id]['qty_debet'] += $qty_debet;
            if ($qty_debet > 0) {
                if (!isset($arrMutasi[$extern_id]['qty_harga_debet'][$harga])) {
                    $arrMutasi[$extern_id]['qty_harga_debet'][$harga] = 0;
                }
                $arrMutasi[$extern_id]['qty_harga_debet'][$harga] += $qty_debet;
            }
            // else{
            //     $arrMutasi[$extern_id]['qty_harga_debet'][$harga] = 0;
            // }
            if (!isset($arrMutasi[$extern_id]['qty_kredit'])) {
                $arrMutasi[$extern_id]['qty_kredit'] = 0;
            }
            $arrMutasi[$extern_id]['qty_kredit'] += $qty_kredit;
            if ($qty_kredit > 0) {
                if (!isset($arrMutasi[$extern_id]['qty_harga_kredit'][$harga])) {
                    $arrMutasi[$extern_id]['qty_harga_kredit'][$harga] = 0;
                }
                $arrMutasi[$extern_id]['qty_harga_kredit'][$harga] += $qty_kredit;
            }
            $arrMutasi[$extern_id]['qty_debet_akhir'] = $arrEnd;
            $arrMutasi[$extern_id]['debet_awal'] = $jml_be * $harga;
            if (!isset($arrMutasi[$extern_id]['debet'])) {
                $arrMutasi[$extern_id]['debet'] = 0;
            }
            $arrMutasi[$extern_id]['debet'] += $debet;
            if (!isset($arrMutasi[$extern_id]['kredit'])) {
                $arrMutasi[$extern_id]['kredit'] = 0;
            }
            $arrMutasi[$extern_id]['kredit'] += $kredit;
            $arrMutasi[$extern_id]['debet_akhir'] = $debet_akhir;
            $on_qty = $qty_debet + $qty_kredit;
            if ($on_qty > 0) {
                $on_hpp = ($kredit + $debet) / $on_qty;
            }
            else {
                $on_hpp = ($kredit + $debet);
            }
            if (!isset($hppId[$extern_id])) {
                $hppId[$extern_id] = 0;
            }
            $hppId[$extern_id] += $on_hpp;
            if (!isset($qtyId[$extern_id])) {
                $qtyId[$extern_id] = 0;
            }
            $qtyId[$extern_id] += $on_qty;
            $arrMutasi[$extern_id]['on_hpp'] = 88;
        }
        // arrPrintWebs($tmpRange);
        //         arrPrintWebs($arrMutasi);
        //         arrPrintWebs($qtyId);
        $jml_row = sizeof($tmpRange);
        foreach ($qtyId as $extern_id_2 => $sumQty) {
            // $hpp_avg = $hppId[$extern_id_2];
            // if($sumQty > 0){
            //     $hpp_avg = $hppId[$extern_id_2] / $sumQty;
            // }
            // else{
            $hpp_avg = $hppId[$extern_id_2];
            // }

            // cekLime("$extern_id_2 :: $hpp_avg / $sumQty");
            $arrMutasi[$extern_id_2]['on_hpp'] = $hpp_avg;
            // $arrMutasi[$extern_id_2]['on_hpp'] = "99".$hpp_avg;
            // $arrMutasi[$extern_id_2]['on_hpp'] = $hpp_avg / $sumQty;
        }

        // $arrMutasi[$extern_id]['on_hpp'] = 0;

        // arrPrint($arrMutasi);

        //region data seluruh produk
        foreach ($produks as $pDatas) {
            foreach ($mdlFields as $mdlField) {
                $$mdlField = $pDatas->$mdlField;
            }
            foreach ($fieldToshows as $mdlField => $label) {
                $$mdlField = $pDatas->$mdlField;
                $specs[$mdlField] = $pDatas->$mdlField;
            }

            $arrProId[$id] = $specs;
        }
        //endregion

        // arrPrint($arrBeAf);
        // arrPrint($arrProId);
        // arrPrint($arrMutasi);
        // mati_disini();
        $mutasiKoloms = array(
            "qty_debet_awal",
            "harga_debet_awal",
            "debet_awal",

            "qty_debet",
            "harga_debet",
            "debet",

            "qty_avail",
            "harga_avail",
            "avail",

            "qty_kredit",
            "harga_kredit",
            "kredit",

            "qty_akhir",
            "harga_akhir",
            "akhir",
        );
        $specs = array();
        $bodies = array();
        $no = 0;

        //region body produk
        foreach ($arrProId as $pId => $pSpecs) {
            // arrPrint($pSpecs);
            $no++;
            // $dataMutasi = isset($arrMutasi[$pId]) ? $arrMutasi[$pId] : $arrMutasi_3[$pId];
            if (isset($arrMutasi[$pId])) {
                $dataMutasi = $arrMutasi[$pId];
                // cekHitam(__LINE__);
            }
            elseif (isset($arrMutasi_3[$pId])) {
                // cekKuning(__LINE__);
                $dataMutasi = $arrMutasi_3[$pId];
            }
            else {
                // cekMerah(__LINE__);
                $dataMutasi = array();
            }

            //             cekHijau("id $pId");
            //             arrPrint($dataMutasi);
            // arrPrint($arrMutasi[$pId]);

            //region begining balance

            //            arrPrint($arrMutasi);
            //            arrPrint($arrMutasi_3);
            //            cekMerah($pId);
            //
            $hasil = "";
            if (isset($arrMutasi[$pId]['qty_harga_debet_awal'])) {
                $jmlAwal = count($arrMutasi[$pId]['qty_harga_debet_awal']);
                // arrPrint($ee);
                $cek = 0;
                foreach ($arrMutasi[$pId]['qty_harga_debet_awal'] as $hargaItem => $jmlItem) {
                    $cek++;
                    cekBiru($cek);
                    $hargaItem = 1 * $hargaItem;
                    if ($cek == 1) {

                        $var = $jmlItem . "x&nbsp;&nbsp;" . number_format($hargaItem, "0", ".", ",");
                        if ($hasil == "") {
                            $hasil .= "$var";
                        }
                        else {
                            $hasil = "$hasil<br>$var";
                        }
                    }

                }
            }
            elseif (isset($arrMutasi_3[$pId]['qty_harga_debet_awal'])) {
                $jmlAwal = count($arrMutasi_3[$pId]['qty_harga_debet_awal']);
                // arrPrint($ee);
                $cek = 0;
                foreach ($arrMutasi_3[$pId]['qty_harga_debet_awal'] as $hargaItem => $jmlItem) {
                    $cek++;
                    $hargaItem = 1 * $hargaItem;
                    //                    $var = $jmlItem . "x&nbsp;&nbsp;" . number_format($hargaItem, "0", ".", ",");
                    $var = $arrMutasi_3[$pId]['qty_debet_awal'] . "x&nbsp;&nbsp;" . number_format($hargaItem, "0", ".", ",");
                    if ($hasil == "") {
                        $hasil .= "$var";
                    }
                    else {
                        $hasil = "$hasil<br>$var";
                    }
                }
            }
            else {
                //                cekMerah(":: [$pId] ::");
                $hasil = "-";
                //                $arrMutasi[$pId]['qty_harga_debet_awal'] = array();
            }


            $dataMutasi['harga_debet_awal'] = $hasil;
            if ($dataMutasi["qty_debet_awal"] == 0) {
                $dataMutasi['harga_debet_awal'] = "-";
            }

            // $dataMutasi['harga_debet_awal'] = 321;
            //arrPrint($dataMutasi);
            //endregion begining balance


            $hasil = "";
            if (isset($arrMutasi[$pId]['qty_harga_debet'])) {
                // cekHijau();
                foreach ($arrMutasi[$pId]['qty_harga_debet'] as $hargaItem => $jmlItem) {
                    // foreach ($hargaItems as $hargaItem => $jmlItem) {
                    $hargaItem = 1 * $hargaItem;

                    // $var = $jmlItem . "x&nbsp;@$hargaItem";
                    $var = $jmlItem . "x&nbsp;&nbsp;" . number_format($hargaItem, "0", ".", ",");
                    if ($hasil == "") {
                        $hasil .= "$var";
                    }
                    else {
                        $hasil = "$hasil<br>$var";
                    }
                    // }
                }
                // cekHijau("jj" .$hasil);
            }
            //            elseif (isset($arrMutasi_3[$pId]['qty_harga_debet'])) {
            //                // cekHijau();
            //                foreach ($arrMutasi_3[$pId]['qty_harga_debet'] as $hargaItem => $jmlItem) {
            //                    // foreach ($hargaItems as $hargaItem => $jmlItem) {
            //                    $hargaItem = 1 * $hargaItem;
            //
            //                    // $var = $jmlItem . "x&nbsp;@$hargaItem";
            //                    $var = $arrMutasi_3[$pId]['qty_debet'] . "x&nbsp;&nbsp;" . number_format($hargaItem, "0", ".", ",");
            //                    if ($hasil == "") {
            //                        $hasil .= "$var";
            //                    }
            //                    else {
            //                        $hasil = "$hasil<br>$var";
            //                    }
            //                    // }
            //                }
            //                // cekHijau("jj" .$hasil);
            //            }
            else {
                $hasil = 0;
                $arrMutasi[$pId]['qty_harga_debet'] = array();
            }
            //            cekMerah("qty_harga_debet");
            //             cekMerah($hasil);
            $dataMutasi['harga_debet'] = $hasil;
            // arrPrintWebs($dataMutasi);


            // arrPrint($arrMutasi[$pId]['qty_harga_debet_awal']);
            // arrPrintWebs($arrMutasi[$pId]['qty_harga_debet']);
            // matiHere(__LINE__);
            // region harga available
            if ((sizeof($arrMutasi[$pId]['qty_harga_debet_awal']) > 0) && (sizeof($arrMutasi[$pId]['qty_harga_debet']) > 0)) {
                // matiHere(__LINE__);
                // $arrHargaAvail = $arrMutasi[$pId]['qty_harga_debet_awal'] + $arrMutasi[$pId]['qty_harga_debet'];
                $arrHargaAvail = $arrMutasi[$pId]['qty_harga_debet_awal'] + $arrMutasi[$pId]['qty_harga_debet'];
            }
            elseif (sizeof($arrMutasi[$pId]['qty_harga_debet_awal']) > 0) {

                $arrHargaAvail = $arrMutasi[$pId]['qty_harga_debet_awal'];
            }
            elseif (sizeof($arrMutasi[$pId]['qty_harga_debet']) > 0) {
                $arrHargaAvail = $arrMutasi[$pId]['qty_harga_debet'];
            }
            else {
                $arrHargaAvail = array();
            }
            // arrPrint($arrHargaAvail);
            // matiHere(__LINE__);

            $hasil = "";
            if (isset($arrHargaAvail)) {
                foreach ($arrHargaAvail as $hargaItem => $jmlItem) {
                    // foreach ($hargaItems as $hargaItem => $jmlItem) {
                    $hargaItem = 1 * $hargaItem;

                    // $var = $jmlItem . "x&nbsp;@$hargaItem";
                    $var = $jmlItem . "x&nbsp;&nbsp;" . number_format($hargaItem, "0", ".", ",");
                    if ($hasil == "") {
                        $hasil .= "$var";
                    }
                    else {
                        $hasil = "$hasil<br>$var";
                    }
                    // }
                }
            }
            else {
                $hasil = 0;
            }
            $dataMutasi['harga_avail'] = $hasil;
            if ($dataMutasi["qty_debet_awal"] > 0 && $dataMutasi["qty_debet"] == 0) {

                $dataMutasi['harga_avail'] = $dataMutasi['harga_debet_awal'];
            }
            // endregion harga available


            $hasil = "";
            if (isset($arrMutasi[$pId]['qty_harga_kredit'])) {
                foreach ($arrMutasi[$pId]['qty_harga_kredit'] as $hargaItem => $jmlItem) {
                    // foreach ($hargaItems as $hargaItem => $jmlItem) {
                    $hargaItem = 1 * $hargaItem;
                    $var = $jmlItem . "x&nbsp;&nbsp;" . number_format($hargaItem, "0", ".", ",");
                    // $qty = $items
                    if ($hasil == "") {
                        $hasil .= "$var";
                    }
                    else {
                        $hasil = "$hasil<br>$var";
                    }
                    // }
                }
            }
            else {
                $hasil = "-";
            }
            $dataMutasi['harga_kredit'] = $hasil;


            //region harga akhir
            // $arrHargaAvail = $arrMutasi[$pId]['qty_harga_debet_awal'] + $arrMutasi[$pId]['qty_harga_debet'];
            //            if(sizeof($arrMutasi[$pId]['qty_harga_debet_awal']) > 0 && sizeof($arrMutasi[$pId]['qty_harga_debet']) > 0){
            //                $arrHargaAvail = $arrMutasi[$pId]['qty_harga_debet_awal'] + $arrMutasi[$pId]['qty_harga_debet'];
            //            }
            //            elseif(sizeof($arrMutasi[$pId]['qty_harga_debet_awal']) > 0 && sizeof($arrMutasi[$pId]['qty_harga_debet']) == 0){
            //                $arrHargaAvail = $arrMutasi[$pId]['qty_harga_debet_awal'];
            //            }
            //            elseif(sizeof($arrMutasi[$pId]['qty_harga_debet_awal']) == 0 && sizeof($arrMutasi[$pId]['qty_harga_debet']) > 0){
            //                $arrHargaAvail = $arrMutasi[$pId]['qty_harga_debet'];
            //            }
            //            else{
            //                $arrHargaAvail = array();
            //            }
            //            arrPrint($arrHargaAvail);
            $hasil = "";
            if (isset($arrHargaAvail)) {
                foreach ($arrHargaAvail as $hargaItem => $jmlItem) {
                    //                    $jmlItem=0;
                    if (isset($arrMutasi[$pId]['qty_harga_kredit'])) {

                        foreach ($arrMutasi[$pId]['qty_harga_kredit'] as $hargaItem2 => $jmlItem2) {
                            if ($hargaItem == $hargaItem2) {
                                $jmlItem = $jmlItem - $jmlItem2;
                            }
                        }
                    }

                    if ($jmlItem > 0) {
                        $var = $jmlItem . "x&nbsp;&nbsp;" . number_format($hargaItem, "0", ".", ",");
                        if ($hasil == "") {
                            $hasil .= "$var";
                        }
                        else {
                            $hasil = "$hasil<br>$var";
                        }
                    }
                    else {
                        $hasil = 0;
                    }
                }
            }
            else {
                $hasil = 0;
            }

            $dataMutasi['harga_akhir'] = $hasil;
            if ($dataMutasi["qty_debet_akhir"] > 0 && $dataMutasi["qty_debet"] == 0 && $dataMutasi["qty_kredit"] == 0) {
                $dataMutasi['harga_akhir'] = $dataMutasi["harga_debet_awal"];
            }

            //endregion harga akhir

            $specs['no']['value'] = $no;
            $specs['no']['attr'] = "class='text-right'";
            //region data produk
            foreach ($fieldToshows as $mdlField => $label) {
                $$mdlField = $pSpecs[$mdlField];

                $value = $pSpecs[$mdlField];

                if (isset($fieldLink[$mdlField])) {
                    $specs[$mdlField]['value'] = "<a target=\"_blank\" href=\"" . base_url() . $fieldLink[$mdlField] . $pId . "/?o=$cabangID\">" . $value . "</a>";
                    // $specs[$mdlField]['value'] = "<a href=\"#\">$value</a>";
                }
                else {

                    $specs[$mdlField]['value'] = $value;
                }

                // $specs[$mdlField]['value'] = ;
                $specs[$mdlField]['attr'] = isset($fieldAttr[$mdlField]) ? $fieldAttr[$mdlField] : "class='text-left'";
            }
            //endregion

            // cekLime("pID:: $pId");
            // arrPrint($dataMutasi);
            // if (sizeof($dataMutasi) > 0) {
            //     $qty_be = $dataMutasi['qty_debet_awal'];
            //     $qty_in = $dataMutasi['qty_debet'];
            //     $qty_ot = $dataMutasi['qty_kredit'];
            //     $qty_af = $dataMutasi['qty_debet_akhir'];
            //     $harga = $dataMutasi['on_hpp'] * 1;
            //
            //     // $amount_be = $dataMutasi['debet_awal'];
            //     // $amount_in = $dataMutasi['debet'];
            //     // $amount_av = $dataMutasi['debet_awal'] + $dataMutasi['debet'];
            //     // $amount_ot = $dataMutasi['kredit'];
            //     // $amount_af = $dataMutasi['debet_awal'] + $dataMutasi['debet'] - $dataMutasi['kredit'];
            // }
            // else {
            //     $qty_be = 0;
            //     $qty_in = 0;
            //     $qty_ot = 0;
            //     $qty_af = 0;
            //     $harga = 0;
            // }

            // arrPrint($mutasiKoloms);
            // cekMerah($pId);
            //            arrPrint($dataMutasi);

            foreach ($mutasiKoloms as $mutasiKolom_0) {

                $mutasiKolom = isset($mutasiKolom_0) ? $mutasiKolom_0 : "--";
                $qtyDebetAwal = isset($dataMutasi['qty_debet_awal']) ? $dataMutasi['qty_debet_awal'] : 0;

                $debetAwal = isset($dataMutasi['debet_awal']) ? $dataMutasi['debet_awal'] : 0;
                $hargaAkhir = isset($dataMutasi['harga_akhir']) ? $dataMutasi['harga_akhir'] : 0;
                $debetAkhir = isset($dataMutasi['debet_akhir']) ? $dataMutasi['debet_akhir'] : 0;

                $qtyDebet = isset($dataMutasi['qty_debet']) ? $dataMutasi['qty_debet'] : 0;
                $qtyKredit = isset($dataMutasi['qty_kredit']) ? $dataMutasi['qty_kredit'] : 0;


                if (($mutasiKolom == "debet_awal") && ($qtyDebetAwal == 0)) {
                    $val2 = 0;
                }
                elseif (($mutasiKolom == "debet") && ($qtyDebet == 0)) {
                    $val2 = 0;
                    $val2_debet = 0;
                }
                elseif (($mutasiKolom == "qty_avail")) {
                    $val2 = $qtyDebetAwal + $qtyDebet;
                }
                elseif (($mutasiKolom == "avail")) {
                    $val2 = $debetAwal + (($mutasiKolom == "debet") && ($qtyDebet == 0)) ? 0 : $debet;
                }
                elseif (($mutasiKolom == "kredit") && ((isset($dataMutasi['qty_kredit']) ? $dataMutasi['qty_kredit'] : 0) == 0)) {
                    $val2 = 0;
                }
                elseif (($mutasiKolom == "qty_akhir")) {
                    $val2 = $qtyDebetAwal + $qtyDebet - $qtyKredit;
                }
                elseif (($mutasiKolom == "akhir")) {
                    $val2 = number_format($debetAkhir, "0", ".", ",");
                }
                else {
                    $val2 = isset($dataMutasi[$mutasiKolom]) ? $dataMutasi[$mutasiKolom] : 0;
                }
                // $specs[$mutasiKolom]['value'] = isset($dataMutasi[$mutasiKolom]) ? $dataMutasi[$mutasiKolom] : "--";
                $specs[$mutasiKolom]['value'] = $val2;
                // $specs[$mutasiKolom]['value'] = "**";
                $specs[$mutasiKolom]['attr'] = "class='text-right'";
            }

            $bodies[] = $specs;
        }
        //endregion

        // region footers
        $footers = array();
        // endregion footers
        //         arrPrint($bodies);
        // matiHere();
        // $month = date("Y-m",$date1);
        // cekHitam($month);
        $dRange = formatField("dtime", $date1) . " s/d " . formatField("dtime", $date2);
        $dateRange = "($dRange)";
        //        $dateRange = "<span class='font-size-0-8 text-grey-2'>$dRange</span>";
        $data = array(
            "mode" => "movement",
            "title" => "$itemLabel movements $dateRange",
            "subTitle" => "",
            // "items"       => array(),
            // "items2"      => array(),
            "date1" => $date1,
            "date2" => $date2,

            "rowLoop" => 5,
            "mainHeaders" => $mainHeaders,
            "subHeaders" => $subHeaders,
            "bodies" => $bodies,
            "footers" => $footers,

            "detailsLabels" => "",
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?o=$cabangID",
        );
        //endregion

        //        arrprint($data);
        $this->load->view("ledger", $data);

    }

    public function viewMovement_ori()
    {
        $this->load->config("heTransaksi_report");
        $item = $this->uri->segment(3);
        $cabangID = $this->placeID;
        $date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow("Y-m") . "-01";
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow("Y-m-d");

        $mdlNameS = $this->config->item('heMovement') ? $this->config->item('heMovement') : array();

        // cekHitam($item);
        // arrPrint($mdlNameS[$item]);
        $comName = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['com'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        // $itemLabel = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['label'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $itemLabel = isset($mdlNameS[$item]['label']) ? $mdlNameS[$item]['label'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $mdlName = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['mdl'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $rekening = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['rek'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $comName);
        $this->load->model("Mdls/" . $mdlName);
        $com = new $comName();
        // $com = new ComRekeningPembantuProduk();
        $mdl = new $mdlName();
        $mdlFields = key_exists($item, $mdlNameS) ? array_keys($mdlNameS[$item]['mdlFields']) : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");

        foreach ($mdlNameS[$item]['mdlFields'] as $field => $fChilds) {
            $fields[] = $field;
            isset($fChilds['label']) ? $fieldToshows[$field] = $fChilds['label'] : "";
            isset($fChilds['attr']) ? $fieldAttr[$field] = $fChilds['attr'] : "";
            isset($fChilds['attrHeader']) ? $fieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            isset($fChilds['link']) ? $fieldLink[$field] = $fChilds['link'] : "";
            isset($fChilds['format']) ? $fieldFormat[$field] = $fChilds['format'] : "";
        }

        // arrPrint($fieldToshows);
        //region penyusun header table nganbil dari config
        $specs_0 = array();
        $mainHeaders_00 = array();
        $specs_0["no"] = 0;
        foreach ($fieldToshows as $field => $fieldToshow) {
            $fAttr = isset($fieldAttrHeader[$field]) ? $fieldAttrHeader[$field] : "-";
            $specs_0[$fieldToshow] = $fAttr;

            $mainHeaders_00 = $specs_0;
            $rNo["no"] = $fAttr;
        }
        $mainHeaders_0 = array_replace($mainHeaders_00, $rNo);
        $mainHeaders_1 = isset($mdlNameS[$item]['mainHeaders_1']) ? $mdlNameS[$item]['mainHeaders_1'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $mainHeaders = array_merge($mainHeaders_0, $mainHeaders_1);
        $subHeaders = isset($mdlNameS[$item]['subHeaders']) ? $mdlNameS[$item]['subHeaders'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        //endregion

        function nanMovement($qty, $value)
        {
            if (($qty <= 0) && ($value > 0)) {
                $var = "Nan";
            }
            elseif (($qty == 0) && ($value > 0)) {
                $var = "Nan";
            }
            elseif (($qty > 0) && ($value <= 0)) {
                $var = "Nan";
            }
            else {
                $var = $value;
            }

            return $var;
        }

        // $mdl = new MdlProduk();
        // $com = new ComRekeningPembantuProduk();
        $bKoloms = array(
            "extern_id",
            "debet_awal",
            "debet",
            "kredit",
            "debet_akhir",
            // "kredit_awal",
            // "kredit_akhir",
            "qty_debet_awal",
            "qty_debet",
            "qty_kredit",
            // "qty_kredit_akhir",
            // "qty_kredit_awal",
            "qty_debet_akhir",
            "harga",
            "harga_avg",
        );
        // $com->addFilter("extern_id='" . 328 . "'");
        // $com->addFilter("extern_id='" . 315 . "'");
        $com->addFilter("cabang_id='" . $this->placeID . "'");
        $com->addFilter("gudang_id='" . $this->gudangID . "'");
        $com->db->where("DATE(dtime) >= '$date1' AND DATE(dtime) <= '$date2'");
        $tmpBegin = $com->fetchMovement($rekening);
        // cekBiru($this->db->last_query());

        $com->addFilter("cabang_id='" . $this->placeID . "'");
        $com->db->where("DATE(dtime) <= '$date2'");
        $tmpUnbegin = $com->fetchMovement($rekening);
        // cekMerah($this->db->last_query()) . " <hr>". $comName;
        // $com->addFilter("extern_id='" . 315 . "'");
        $com->addFilter("cabang_id='" . $this->placeID . "'");
        $com->db->where("DATE(dtime) >= '$date1' AND DATE(dtime) <= '$date2'");
        $tmpRange = $com->fetchMovement($rekening);
        // cekHijau($this->db->last_query()) . " <hr> 99". $comName;

        // $mdl->addFilter("jenis='" . "item_rakitan" . "'");
        // $pro->setFilters(array());
        if ($this->placeID <= 0) {
            $mdl->setFilters(array());
            $mdl->db->where("jenis in ('item_rakitan','item') AND `status` = '1' AND `trash` = '0'");
        }
        // $mdl->addFilter("id='222'");
        $produks = $mdl->lookupAll()->result();
        // cekLime($this->db->last_query() . "<hr>$item ---- $mdlName");

        // arrPrint($produks);
        // arrPrint($mdlFields);
        // arrPrint($fieldToshows);
        // arrPrint($tmpBegin);

        /*---------------------------
         * mutasi tanpa batasan tanggal mulai
         * ------------------------*/
        // arrPrint($tmpUnbegin);
        $arrMutasi_3 = array();
        foreach ($tmpUnbegin as $rDatas) {
            foreach ($bKoloms as $bKolom) {
                $$bKolom = $rDatas->$bKolom;
            }

            $arrMutasi_3[$extern_id]['qty_debet_awal'] = $qty_debet_akhir;
            $arrMutasi_3[$extern_id]['qty_debet'] = 0;
            $arrMutasi_3[$extern_id]['qty_kredit'] = 0;
            $arrMutasi_3[$extern_id]['qty_debet_akhir'] = $qty_debet_akhir;

            $arrMutasi_3[$extern_id]['debet_awal'] = $debet_akhir;
            $arrMutasi_3[$extern_id]['debet'] = $debet;
            $arrMutasi_3[$extern_id]['kredit'] = $kredit;
            $arrMutasi_3[$extern_id]['debet_akhir'] = $debet_akhir;
            // $arrMutasi_3[$produk_id]['on_hpp'] = $on_hpp;

            $on_qty = $qty_debet + $qty_kredit;
            if ($on_qty > 0) {
                $on_hpp_3 = ($kredit + $debet) / $on_qty;
                // $arrMutasi[$extern_id]['on_hpp'] = ($kredit + $debet) / ($qty_debet + $qty_kredit);
                // cekMerah("$extern_id = ($kredit + $debet) / ($qty_debet + $qty_kredit)");
            }
            else {
                // $arrMutasi[$extern_id]['on_hpp'] = ($kredit + $debet);
                // $on_hpp_3 = ($kredit + $debet);
                $on_hpp_3 = ($kredit + $debet);
                // cekBiru("$extern_id = ($kredit + $debet) / 00001)");
            }

            // if (($qty_debet + $qty_kredit) > 0) {
            //     $on_hpp_3 = ($kredit + $debet) / ($qty_debet + $qty_kredit);
            //     // $arrMutasi_3[$extern_id]['on_hpp'] = ($kredit + $debet) / ($qty_debet + $qty_kredit);
            // }
            // else {
            //     $on_hpp_3 = ($kredit + $debet);
            //     // $arrMutasi_3[$extern_id]['on_hpp'] = ($kredit + $debet);
            // }
            if (!isset($arrMutasi_3[$extern_id]['on_hpp'])) {
                $arrMutasi_3[$extern_id]['on_hpp'] = 0;
            }
            $arrMutasi_3[$extern_id]['on_hpp'] += "999" . $on_hpp_3;
        }
        // arrPrint($tmpUnbegin);
        // arrPrint($arrMutasi_3);
        /*---------------------------
         * data stok awal dan akhir
         * $arrBeAf
         * ------------------------*/
        $spec = array();
        foreach ($tmpBegin as $bDatas) {
            foreach ($bKoloms as $bKolom) {
                $$bKolom = $bDatas->$bKolom;

            }
            $spec['qty_debet_awal'] = $qty_debet_awal;
            $spec['qty_debet_akhir'] = $qty_debet_akhir;
            if (($qty_debet + $qty_kredit) > 0) {

                $spec['on_hpp'] = ($kredit + $debet) / ($qty_debet + $qty_kredit);
            }
            else {
                $spec['on_hpp'] = ($kredit + $debet);
            }
            $arrBeAf[$extern_id][] = $spec;
        }
        // arrPrint($arrBeAf);

        // arrPrint($tmpRange);
        /*---------------------------
         * mutasi dengan range tanggal
         * ------------------------*/
        $arrMutasi = array();
        $no = 0;
        foreach ($tmpRange as $rDatas) {
            $no++;
            foreach ($bKoloms as $bKolom) {
                $$bKolom = $rDatas->$bKolom;
            }

            $jml_be = $arrBeAf[$extern_id][0]["qty_debet_awal"];
            $arrEnd = end($arrBeAf[$extern_id])["qty_debet_akhir"];

            $arrMutasi[$extern_id]['qty_debet_awal'] = $jml_be;

            if (!isset($arrMutasi[$extern_id]['qty_debet'])) {
                $arrMutasi[$extern_id]['qty_debet'] = 0;
            }
            $arrMutasi[$extern_id]['qty_debet'] += $qty_debet;

            if (!isset($arrMutasi[$extern_id]['qty_kredit'])) {
                $arrMutasi[$extern_id]['qty_kredit'] = 0;
            }
            $arrMutasi[$extern_id]['qty_kredit'] += $qty_kredit;

            $arrMutasi[$extern_id]['qty_debet_akhir'] = $arrEnd;

            if ($no == 1) {

                $arrMutasi[$extern_id]['debet_awal'] = $debet_awal;
            }

            if (!isset($arrMutasi[$extern_id]['debet'])) {
                $arrMutasi[$extern_id]['debet'] = 0;
            }
            $arrMutasi[$extern_id]['debet'] += $debet;

            if (!isset($arrMutasi[$extern_id]['kredit'])) {
                $arrMutasi[$extern_id]['kredit'] = 0;
            }
            $arrMutasi[$extern_id]['kredit'] += $kredit;

            $arrMutasi[$extern_id]['debet_akhir'] = $debet_akhir;

            $on_qty = $qty_debet + $qty_kredit;
            if ($on_qty > 0) {
                $on_hpp = ($kredit + $debet) / $on_qty;
                // $arrMutasi[$extern_id]['on_hpp'] = ($kredit + $debet) / ($qty_debet + $qty_kredit);
                // cekMerah("$extern_id = ($kredit + $debet) / ($qty_debet + $qty_kredit)");
            }
            else {
                // $arrMutasi[$extern_id]['on_hpp'] = ($kredit + $debet);
                $on_hpp = ($kredit + $debet);
                // $on_hpp = ($kredit + $debet) / $on_qty;
                // cekBiru("$extern_id = ($kredit + $debet) / 00001)");
            }

            if (!isset($hppId[$extern_id])) {
                $hppId[$extern_id] = 0;
            }
            $hppId[$extern_id] += $on_hpp;

            if (!isset($qtyId[$extern_id])) {
                $qtyId[$extern_id] = 0;
            }
            $qtyId[$extern_id] += $on_qty;

            // cekHitam("$extern_id:: $on_hpp / $on_qty");
            // $arrMutasi[$extern_id]['on_hpp'] = $harga_avg;
            $arrMutasi[$extern_id]['on_hpp'] = 88;

        }
        // arrPrintWebs($tmpRange);
        // arrPrintWebs($arrMutasi);
        $jml_row = sizeof($tmpRange);
        foreach ($qtyId as $extern_id_2 => $sumQty) {
            // $hpp_avg = $hppId[$extern_id_2];
            // if($sumQty > 0){
            //     $hpp_avg = $hppId[$extern_id_2] / $sumQty;
            // }
            // else{
            $hpp_avg = $hppId[$extern_id_2];
            // }

            // cekLime("$extern_id_2 :: $hpp_avg / $sumQty");
            $arrMutasi[$extern_id_2]['on_hpp'] = $hpp_avg;
            // $arrMutasi[$extern_id_2]['on_hpp'] = "99".$hpp_avg;
            // $arrMutasi[$extern_id_2]['on_hpp'] = $hpp_avg / $sumQty;
        }

        // $arrMutasi[$extern_id]['on_hpp'] = 0;

        // arrPrint($arrMutasi);

        //region data seluruh produk
        foreach ($produks as $pDatas) {
            foreach ($mdlFields as $mdlField) {
                $$mdlField = $pDatas->$mdlField;
            }
            foreach ($fieldToshows as $mdlField => $label) {
                $$mdlField = $pDatas->$mdlField;
                $specs[$mdlField] = $pDatas->$mdlField;
            }

            $arrProId[$id] = $specs;
        }
        //endregion


        // arrPrint($arrBeAf);
        // arrPrint($arrProId);
        // mati_disini();
        $specs = array();
        $bodies = array();
        $no = 0;
        //region body produk
        foreach ($arrProId as $pId => $pSpecs) {
            $no++;
            // $dataMutasi = isset($arrMutasi[$pId]) ? $arrMutasi[$pId] : $arrMutasi_3[$pId];
            if (isset($arrMutasi[$pId])) {
                $dataMutasi = $arrMutasi[$pId];
            }
            elseif (isset($arrMutasi_3[$pId])) {
                $dataMutasi = $arrMutasi_3[$pId];

            }
            else {
                $dataMutasi = array();
            }
            // cekHijau("id $pId");
            // arrPrint($dataMutasi);

            $specs['no']['value'] = $no;
            $specs['no']['attr'] = "class='text-right'";
            foreach ($fieldToshows as $mdlField => $label) {
                $$mdlField = $pSpecs[$mdlField];

                $value = $pSpecs[$mdlField];

                if (isset($fieldLink[$mdlField])) {
                    $specs[$mdlField]['value'] = "<a target=\"_blank\" href=\"" . base_url() . $fieldLink[$mdlField] . $pId . "/?o=$cabangID\">" . $value . "</a>";
                    // $specs[$mdlField]['value'] = "<a href=\"#\">$value</a>";
                }
                else {

                    $specs[$mdlField]['value'] = $value;
                }

                // $specs[$mdlField]['value'] = ;
                $specs[$mdlField]['attr'] = isset($fieldAttr[$mdlField]) ? $fieldAttr[$mdlField] : "class='text-left'";
            }

            if (sizeof($dataMutasi) > 0) {
                $qty_be = $dataMutasi['qty_debet_awal'];
                $qty_in = $dataMutasi['qty_debet'];
                $qty_ot = $dataMutasi['qty_kredit'];
                $qty_af = $dataMutasi['qty_debet_akhir'];
                $harga = $dataMutasi['on_hpp'] * 1;

                // $amount_be = $dataMutasi['debet_awal'];
                // $amount_in = $dataMutasi['debet'];
                // $amount_av = $dataMutasi['debet_awal'] + $dataMutasi['debet'];
                // $amount_ot = $dataMutasi['kredit'];
                // $amount_af = $dataMutasi['debet_awal'] + $dataMutasi['debet'] - $dataMutasi['kredit'];
            }
            else {
                $qty_be = 0;
                $qty_in = 0;
                $qty_ot = 0;
                $qty_af = 0;
                $harga = 0;
            }

            // [223] => Array
            //         (
            //             [qty_debet_awal] => 0
            //             [qty_debet] => 1
            //             [qty_kredit] => 1
            //             [qty_debet_akhir] => 0
            //             [on_hpp] => 4800000
            //         )
            if ($pId == 315) {
                // cekHitam();
                // cekHijau($arrMutasi_3[$pId]['debet_awal']);
                // arrPrintWebs($arrMutasi[$pId]);
            }

            $qty_av = $qty_be + $qty_in;
            // $amount_be = $dataMutasi['debet_awal'];
            $amount_be = $arrMutasi[$pId]['debet_awal'];
            $amount_in = $dataMutasi['debet'];
            $amount_av = $dataMutasi['debet_awal'] + $dataMutasi['debet'];
            $amount_ot = $arrMutasi[$pId]['kredit'];
            $amount_af = $dataMutasi['debet_awal'] + $dataMutasi['debet'] - $arrMutasi[$pId]['kredit'];

            //region awal
            $specs['qty_debet_awal']['value'] = $qty_be;
            $specs['qty_debet_awal']['attr'] = "class='text-right'";
            // $specs['harga_be']['value'] = $harga;
            // $specs['harga_be']['attr'] = "class='text-right'";
            // $specs['sum_be']['value'] = (($qty_be > 0) && ($amount_be == 0)) ? "Nan" : $amount_be;
            $specs['sum_be']['value'] = nanMovement($qty_be, $amount_be);
            $specs['sum_be']['attr'] = "class='text-right'";
            //endregion

            //region in masuk
            $specs['qty_debet']['value'] = $qty_in;
            $specs['qty_debet']['attr'] = "class='text-right'";
            // $specs['harga_in']['value'] = $harga;
            // $specs['harga_in']['attr'] = "class='text-right'";
            $specs['sum_in']['value'] = nanMovement($qty_in, $amount_in);
            $specs['sum_in']['attr'] = "class='text-right'";
            //endregion

            //region tersedia available
            $specs['qty_kredit']['value'] = $qty_av;
            $specs['qty_kredit']['attr'] = "class='text-right'";
            // $specs['harga_ot']['value'] = $harga;
            // $specs['harga_ot']['attr'] = "class='text-right'";
            $specs['sum_ot']['value'] = (($qty_av > 0) && ($amount_av == 0)) ? "Nan" : $amount_av;
            $specs['sum_ot']['attr'] = "class='text-right'";
            //endregion

            //region ot output keluar
            $specs['qty_avail']['value'] = $qty_ot;
            $specs['qty_avail']['attr'] = "class='text-right'";
            // $specs['harga_av']['value'] = $harga;
            // $specs['harga_av']['attr'] = "class='text-right'";
            $specs['sum_av']['value'] = $amount_ot;
            $specs['sum_av']['attr'] = "class='text-right'";
            //endregion

            //region akhir
            $specs['qty_debet_akhir']['value'] = $qty_af;
            $specs['qty_debet_akhir']['attr'] = "class='text-right'";
            // $specs['harga_af']['value'] = $harga;
            // $specs['harga_af']['attr'] = "class='text-right'";
            $specs['sum_af']['value'] = (($qty_af > 0) && ($amount_af <= 0)) ? "Nan" : $amount_af;
            $specs['sum_af']['attr'] = "class='text-right'";
            //endregion

            $bodies[] = $specs;
        }
        //endregion

        // region footers
        $footers = array();
        // endregion footers
        // arrPrint($bodies);
        //                matiHere();
        // $month = date("Y-m",$date1);
        // cekHitam($month);
        $dRange = formatField("dtime", $date1) . " s/d " . formatField("dtime", $date2);
        $dateRange = "<span class='font-size-0-8 text-grey-2'>$dRange</span>";
        $data = array(
            "mode" => "movement",
            "title" => "$itemLabel movements $dateRange",
            "subTitle" => "",
            // "items"       => array(),
            // "items2"      => array(),
            "date1" => $date1,
            "date2" => $date2,

            "rowLoop" => 5,
            "mainHeaders" => $mainHeaders,
            "subHeaders" => $subHeaders,
            "bodies" => $bodies,
            "footers" => $footers,

            "detailsLabels" => "",
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?o=$cabangID",
        );
        //endregion

        //        arrprint($data);
        $this->load->view("ledger", $data);

    }


    public function viewDetail_l1()
    {
        $relName = $this->uri->segment(3);
        $rekName = urldecode($this->uri->segment(4));

        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];

        $defPosition = detectRekDefaultPosition($rekName);
        $opPosition = $defPosition == "kredit" ? "debet" : "kredit";

        $balConfig = isset($this->config->item('accountMoveColumns')[$relName]) ? $this->config->item('accountMoveColumns')[$relName] : array();
        $accountFilters = isset($this->config->item('accountBalanceColumns')[$relName]['viewFilters']) ? $this->config->item('accountBalanceColumns')[$relName]['viewFilters'] : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();


        $mdlName = "Com" . $relName;

        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $mdlName);
        $this->load->model("MdlTransaksi");
        $com = new $mdlName();
        $tr = new MdlTransaksi();


        $com->addFilter("cabang_id='$cabangID'");

        if (sizeof($accountFilters) > 0) {
            foreach ($accountFilters as $f) {
                $f_ex = explode("=", $f);
                if (!isset($f_ex[1])) {
                    $f_ey = explode(">", $f_ex[0]);
                    if (substr($f_ey[1], 0, 1) == ".") {
                        $com->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                    }
                    else {
                        $com->addFilter($f_ey[0] . ">'" . $this->session->login[$f_ey[1]] . "'");
                    }
                }
                else {
                    if (substr($f_ex[1], 0, 1) == ".") {
                        $com->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                    }
                    else {
                        $com->addFilter($f_ex[0] . "='" . $this->session->login[$f_ex[1]] . "'");
                    }
                }
            }
        }


        if (!isset($_GET['date1']) && !isset($_GET['date2'])) {
            $this->db->order_by("id", "DESC");
            switch ($_GET['periode']) {
                case "bulanan":
                    $date1 = isset($_GET['date']) ? $_GET['date'] . "-01" : date("Y-m-d");
                    $date2 = isset($_GET['date']) ? $_GET['date'] . "-31" : date("Y-m-d");
                    break;
                case "tahunan":
                    $date1 = isset($_GET['date']) ? $_GET['date'] . "-01-01" : date("Y-m-d");
                    $date2 = isset($_GET['date']) ? $_GET['date'] . "-12-31" : date("Y-m-d");
                    break;
                default:
                    $date1 = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");
                    $date2 = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");
                    break;
            }


            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");

            $subTitle_date = lgTranslateTime($date1) . " - " . lgTranslateTime($date2);
        }
        else {
            $this->db->order_by("id", "DESC");

            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");

            $subTitle_date = lgTranslateTime($date1) . " - " . lgTranslateTime($date2);
        }
        //cekHere(":: $date1 :: $date2 ::");

        $tmp = $com->fetchMoves($rekName);
        //        cekkuning($this->db->last_query());


        //region auto group in - out
        $arrProds = array();
        $tmpTrIds = array();
        foreach ($tmp as $temX) {
            $transaksi_id = $temX->transaksi_id;
            $position = $temX->debet > 0 ? "debet" : "kredit";
            $val = $temX->debet > 0 ? $temX->debet : $temX->kredit;
            $detectRek = detectRekByPosition($rekName, $val, $position);
            if ($detectRek > 0) {
                $arrMovedDetail["in"][$temX->jenis] = $position;
                if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                    $arrMovedDetail["in_qty"][$temX->jenis] = "qty_" . $position;
                    $newSelect = "qty_" . $position;
                    $qtyVal = $temX->$newSelect;
                }
                else {
                    $qtyVal = 0;
                }

            }
            else {
                $arrMovedDetail["out"][$temX->jenis] = $position;
                if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                    $arrMovedDetail["out_qty"][$temX->jenis] = "qty_" . $position;
                    $newSelect = "qty_" . $position;
                    $qtyVal = $temX->$newSelect;
                }
                else {
                    $qtyVal = 0;
                }

            }

            $arrProds[][$temX->jenis] = array(
                "$position" => "$val",
                "qty_" . $position => $qtyVal,
            );

            $tmpTrIds[$transaksi_id] = 1;
        }
        $trIds = array_keys($tmpTrIds);
        //endregion

        // region transaksi
        $listId = implode("','", $trIds);
        $tr->setFilters(array());
        $tr->addFilter("id in ('" . $listId . "')");
        $tmpTrs = $tr->lookupAll()->result();
        // cekLime($this->db->last_query());
        // arrPrint($tmpTrs);

        // endregion transaksi


        if (sizeof($balConfig)) {
            $headerFields = $balConfig['viewedColumns'];

        }
        else {
            die("balance config for $relName is not yet defined");
        }


        if ($cabangID == CB_ID_PUSAT) {
            $headerFields = array(
                "dtime" => "time",
                "jenis" => "note",
                //                "nomer_top" => "reference number",
                "transaksi_no" => "receipt number",
                "suppliers_nama" => "vendor",
                "cabang_nama" => "branch",
                "oleh_nama" => "by",
            );
        }
        else {
            $headerFields = array(
                "dtime" => "time",
                "jenis" => "note",
                //                "nomer_top" => "reference number",
                "transaksi_no" => "receipt number",
                "customers_nama" => "customer",
                //                "cabang_nama" => "branch",
                "oleh_nama" => "by",
            );
        }
        $jenisAliases = arrCodeAliasing($cabangID);

        // region builder data transaksi yg perlu muncul
        $trDatas = array();
        foreach ($tmpTrs as $tmpTr) {
            $datas = array();
            foreach ($headerFields as $hField => $hAlias) {
                $$hField = key_exists($hField, $tmpTr) ? $tmpTr->$hField : "";
                if (array_key_exists($hField, $tmpTr)) {
                    $datas[$hField] = key_exists($tmpTr->$hField, $jenisAliases) ? $jenisAliases[$tmpTr->$hField] : $tmpTr->$hField;
                }
            }
            $counters = blobDecode($tmpTr->counters);
            $datas['nomer'] = $tmpTr->nomer . "-" . digit_5($counters['stepCode|placeID'][$tmpTr->jenis . "|" . $tmpTr->cabang_id]);
            $datas['urut'] = "-" . digit_5($counters['stepCode|placeID'][$tmpTr->jenis . "|" . $tmpTr->cabang_id]);
            $datas['cabang_nama'] = $tmpTr->cabang_nama;
            $trDatas[$tmpTr->id] = $datas;
        }
        //         arrPrint($trDatas);
        // endregion builder data transaksi yg perlu muncul


        //        $headerQtyFields = array(
        //
        //            "qty_" . $defPosition . "_awal" => "prev (QTY)",
        //            "qty_" . $defPosition => "in (QTY)",
        //            "qty_" . $opPosition => "out (QTY)",
        //            "qty_" . $defPosition . "_akhir" => "balance (QTY)",
        //
        //        );
        $headerValueFields = array(
            //            $defPosition . "_awal" => "prev (IDR)",
            $defPosition => "in (IDR)",
            $opPosition => "out (IDR)",
            //            $defPosition . "_akhir" => "balance (IDR)",
        );
        $headerValueFieldsView = array(
            //            $defPosition . "_awal" => "prev (IDR)",
            //            $defPosition => "in (IDR)",
            //            $opPosition => "out (IDR)",
            //
            "balance" => "balance (IDR)",
        );

        //        if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
        //            $headerFields = $headerFields + $headerQtyFields;
        //        }
        //
        if (isset($balConfig['showValue']) && $balConfig['showValue'] == true) {
            $headerFieldsView = $headerFields + $headerValueFieldsView;
            $headerFields = $headerFields + $headerValueFields;
        }


        $items = array();
        if (sizeof($tmp) > 0) {
            krsort($tmp);
            $subs = array();
            foreach ($tmp as $row) {

                if (($row->debet > 0) || ($row->kredit > 0)) {

                    foreach ($headerFields as $key => $label) {
                        if (array_key_exists($key, $row)) {
                            if (key_exists($row->$key, $jenisAliases)) {
                                $subs[$key] = $jenisAliases[$row->$key];
                            }
                            else {
                                $subs[$key] = $row->$key;
                            }

                            //                            if((is_numeric($subs[$key])) && ($key != "jenis")){
                            //                                if(!isset($subs['balance'])){
                            //                                    $subs['balance'] = 0;
                            //                                }
                            //                                if($row->$defPosition > 0){
                            ////                                    cekMerah(":: +");
                            //                                    $nilai = $subs[$key];
                            //                                }
                            //                                elseif($row->$opPosition > 0){
                            ////                                    cekMerah(":: -");
                            //                                    $nilai = $subs[$key] *-1;
                            //                                }
                            //                                else{
                            ////                                    cekMerah(":: +/-");
                            //                                    $nilai = $subs[$key];
                            //                                }
                            //                                cekHere($nilai);
                            //                                $subs['balance'] = $nilai;
                            //                            }
                        }
                    }
                    if (!isset($subs['balance'])) {
                        $subs['balance'] = 0;
                    }
                    if ($row->$defPosition > 0) {
                        $nilai = $row->$defPosition;
                    }
                    elseif ($row->$opPosition > 0) {
                        $nilai = $row->$opPosition * -1;
                    }

                    $subs['balance'] = $nilai;
                    $subs_r = array_replace($subs, $trDatas[$row->transaksi_id]);
                    $items[] = $subs_r;
                }
            }
        }

        //arrprint($headerFieldsView);
        //region prepare params to viewer
        $headerFields['balance'] = "balance (IDR)";
        $data = array(
            "mode" => "rlDetail",
            "title" => isset($accountAlias[$rekName]) ? $accountAlias[$rekName] : $rekName,
            "subTitle" => $datas['cabang_nama'] . " &nbsp;&nbsp;  $subTitle_date",
            "items" => $items,
            //            "headerFields" => $headerFields,
            "headerFields" => $headerFieldsView,
            "filters" => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "?o=$cabangID",
            "summary" => isset($this->summaryKey) ? $this->summaryKey : array(),
        );
        //endregion


        $this->load->view("ledger", $data);


    }
}