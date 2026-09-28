<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/26/2018
 * Time: 5:01 PM
 */
require_once "Modul_Controller.php";

class _processPihak extends Modul_Controller
{

    // private $jenisTr;

    public function __construct()
    {
        parent::__construct();
        // $this->jenisTr = $this->uri->segment(4);
        // $cCode = "_TR_" . $this->jenisTr;
    }

    public function select()
    {

//        $accountChildConfig = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountChildConfig = $this->config->item("accountChildsItems") != null ? $this->config->item("accountChildsItems") : array();
        $pihakMainValueSrc = isset($this->configUi[$this->jenisTr]['pihakMainValueSrc']) ? $this->configUi[$this->jenisTr]['pihakMainValueSrc'] : array();
        $pihakValidate = isset($this->configUi[$this->jenisTr]['pihakValidate']) ? $this->configUi[$this->jenisTr]['pihakValidate'] : array();
        $pihakAddValidate = isset($this->configUi[$this->jenisTr]['pihakAddValidate']) ? $this->configUi[$this->jenisTr]['pihakAddValidate'] : array();
        $srcModel = isset($this->configUi[$this->jenisTr]['selectorModelTarget']) ? $this->configUi[$this->jenisTr]['selectorModelTarget'] : null;
        $shoppingCartFieldSrc = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array();
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $dateTarget = previousYear(dtimeNow("Y-m-d"));
        $cCode = $this->cCode;
        $pihakId = isset($_GET['id']) ? $_GET['id'] : 0;

        unset($_SESSION[$cCode]['items']);
        unset($_SESSION[$cCode]['items2']);
        unset($_SESSION[$cCode]['items2_sum']);
        unset($_SESSION[$cCode]['items3_sum']);
        unset($_SESSION[$cCode]['items4']);
        unset($_SESSION[$cCode]['items5_sum']);
        unset($_SESSION[$cCode]['items6_sum']);
        $_SESSION[$cCode]['items'] = array();
        // $_SESSION[$cCode]['items2'] = array();
        // $_SESSION[$cCode]['items2_sum'] = array();
        // $_SESSION[$cCode]['items3_sum'] = array();
        // $_SESSION[$cCode]['items4'] = array();
        $this->load->model("Mdls/MdlAccounts");
        $a = new MdlAccounts();


        /*
         * selector auto build items dari rekening lajur tahun target
         */
//         arrprint($accountChildConfig);
//         matiHEre();
        if ($srcModel != null) {
            if (!isset($_SESSION[$cCode]["items2"])) {
                $mdlName = $this->uri->segment(5);

                $this->load->model("Mdls/" . $mdlName);
                $b = new $mdlName();
                $tmpB = $b->lookupByID($pihakId)->result();
                if (sizeof($tmpB) > 0) {
                    if (isset($this->configUi[$this->jenisTr]["pihakMainNota"]) && $this->configUi[$this->jenisTr]["pihakMainNota"] == true) {
                        $selectColumn = "nomer";
                    }
                    else {
                        $selectColumn = "nama";
                    }

                    $_SESSION[$cCode]['main']['pihakID'] = $pihakId;
                    $_SESSION[$cCode]['main']['pihakName'] = isset($tmpB[0]->$selectColumn) ? $tmpB[0]->$selectColumn : "";
                    $_SESSION[$cCode]['main']['pihakName2'] = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";
                    $_SESSION[$cCode]['main']['pihakDisc'] = isset($tmpB[0]->diskon) ? $tmpB[0]->diskon : "";
                    $_SESSION[$cCode]['main']['dateTarget'] = $dateTarget;

                    //            $tmpPihakName = isset($tmpB[0]->$selectColumn) ? $tmpB[0]->$selectColumn : "";
                    $tmpPihakName = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";


                    $pairPajak = isset($this->configUi[$this->jenisTr]['pairPajak']) ? $this->configUi[$this->jenisTr]['pairPajak'] : false;
                    $pairPajakData = $this->config->item("pairPajak");
                    //            matiHere($selectColumn);
                    if (isset($tmpB[0]->name)) {
                        $tmpPihakName = $tmpB[0]->name;
                    }


                    if (sizeof($pihakMainValueSrc) > 0) {
                        foreach ($pihakMainValueSrc as $key => $src) {
                            $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
                        }
                    }
                    $warehouse = getDefaultWarehouseID($this->session->login['cabang_id']);
                    $arehouseTarget = getDefaultWarehouseID($pihakId);
                    //            arrPrint($warehouse);
                    $_SESSION[$cCode]['main']['pihakID'] = $pihakId;
                    $_SESSION[$cCode]['main']['gudang2ID'] = $arehouseTarget["gudang_id"];
                    $_SESSION[$cCode]['main']['gudang2Name'] = $arehouseTarget["gudang_id"];
                    $_SESSION[$cCode]['main']['pihakName'] = $warehouse['gudang_nama'];
                }


                //---------------------------------------------------
                if (sizeof($pihakValidate) > 0) {

                    foreach ($pihakValidate as $kolom => $spec) {
                        if (isset($tmpB[0]->$kolom) && ($tmpB[0]->$kolom != NULL)) {
                            $result = $tmpB[0]->$kolom;
                            $tb_kolom = $spec['result'][$result]['kolom'];
                            $tb_label = $spec['result'][$result]['label'];
                            if (isset($tmpB[0]->$tb_kolom) && ($tmpB[0]->$tb_kolom != NULL)) {
                                cekHijau("LANJUT...");
                            }
                            else {
                                $label = $tmpB[0]->nama . ", " . $tb_label;
                                die(lgShowAlertBiru($label));
                            }
                        }
                        else {
                            $label = $tmpB[0]->nama . ", " . $spec['result']['none']['label'];
                            die(lgShowAlertBiru($label));
                        }
                    }
                }


                if (sizeof($pihakAddValidate) > 0) {
                    $addMode = isset($pihakAddValidate['mode']) ? $pihakAddValidate['mode'] : NULL;
                    $addFilter = isset($pihakAddValidate['filter']) ? $pihakAddValidate['filter'] : array();
                    if (sizeof($addFilter) > 0) {
                        foreach ($addFilter as $kf => $vf) {

                            cekHere(":: $kf => $vf :: $addMode ::");
                            switch ($addMode) {
                                case "!=":
                                    if ($tmpB[0]->$kf != $vf) {
                                        $label = $pihakAddValidate['label'][$kf];
                                        die(lgShowAlertBiru($label));
                                    }
                                    break;
                                case "==":
                                    if ($tmpB[0]->$kf == $vf) {
                                        $label = $pihakAddValidate['label'][$kf];
                                        die(lgShowAlertBiru($label));
                                    }
                                    break;
                                default:
                                    cekHitam(":: masuk sini, default ::");
                                    break;
                            }

                        }
                    }
                }

                //---------------------------------------------------
                // unset($_SESSION[$cCode]['items']);
                // unset($_SESSION[$cCode]['items2']);
                // unset($_SESSION[$cCode]['items2_sum']);
                // unset($_SESSION[$cCode]['items3_sum']);
                // unset($_SESSION[$cCode]['items4']);
                // cekMerah($srcModel);
                /*
                 * cuma butuh keynya saja value gak dipakai daripada harus foreach
                 */
                $childBlackList = array(
                    "1010030030" => "produk",
                    "1010030070" => "rakitan",
                    "1010030010" => "supplies",
                    "1020010010" => "KENDARAAN",
                    "1020020010" => "PERALATAN KANTOR",
                    "1020020020" => "AKUMULASI PENYUSUTAN PERALATAN KANTOR",
                    "1020030010" => "MESIN",
                    "1020040010" => "MESIN PRODUKSI",
                    "1020041010" => "PERALATAN PRODUKSI",
                    "1020050010" => "BANGUNAN",
                    "1020050020" => "AKUMULASI PENYUSUTAN BANGUNAN",
                    "1020070010" => "ASET TETAP BELUM DITEMPATKAN",
                    "6040010" => "RekeningPembantuDepresiasi",
                    "6040020" => "RekeningPembantuDepresiasi",
                    "6040030" => "RekeningPembantuDepresiasi",
                    "6040040" => "RekeningPembantuDepresiasi",
                    "6040050" => "RekeningPembantuDepresiasi",
                    "6040060" => "RekeningPembantuDepresiasi",
                );
                // $this->load->model("Mdls/" . $srcModel);//di pasang disini kaen yg tahun lewat sudah tidak ada baiya  karena sudah di RL
                $this->load->model("Mdls/MdlNeraca");
                $this->load->model("Mdls/MdlNeracaAdj");

                $src = new MdlNeracaAdj();
                $src->addFilter("cabang_id='$pihakId'");
                $src->addFilter("rebuild='0'");
                // $src->addFilter("rekening in ('6010','6030','6031','6130')");
                // $src->addFilter("rekening in ('6010','6030','6031')");
//                $src->addFilter("periode='forever'");
                $src->addFilter("periode='tahunan'");
                $this->db->order_by("rekening", "asc");
                $tempLajur = $src->fetchBalances("$dateTarget");
                if (sizeof($tempLajur) == 0) {

                    $src = new MdlNeraca();
                    $src->addFilter("cabang_id='$pihakId'");
                    // $src->addFilter("rekening in ('6010','6030','6031','6130')");
                    // $src->addFilter("rekening in ('6010','6030','6031')");
                    $src->addFilter("periode='tahunan'");
                    $this->db->order_by("rekening", "asc");
                    $tempLajur = $src->fetchBalances("$dateTarget");

                }

                $prevLajur = array();
                $accountChild = array();
                if (sizeof($tempLajur) > 0) {
                    foreach ($tempLajur as $_temp) {
                        $prevLajur[$_temp->rekening] = array(
                            "debet" => $_temp->debet,
                            "kredit" => $_temp->kredit,
                        );
                        if (isset($accountChildConfig[$_temp->rekening])) {
                            if (isset($childBlackList[$_temp->rekening])) {
                                //gak usah dikikin array nya
                            }
                            else {
                                $childModel = "com" . $accountChildConfig[$_temp->rekening];
                                $this->load->model("Coms/" . $childModel);
                                // cekbiru($childModel."$_temp->rekening");
// cekHitam($childModel);
                                $m = new $childModel();

                                $m->setFilters(array());
                                $m->addFilter("cabang_id='$pihakId'");
                                $m->addFilter("periode='tahunan'");
                                $m->addFilter("thn='$dateTarget'");
                                $m->addFilter("rekening='" . $_temp->rekening . "'");

                                $tmpBalance = $m->lookUpAll()->result();
                                // cekHitam($this->db->last_query());
                                // arrprint($tmpBalance);
                                // matiHEre();
                                if (sizeof($tmpBalance) > 0) {
                                    foreach ($tmpBalance as $tmpBalance_0) {
                                        $accountChild[$_temp->rekening]["prev"][$tmpBalance_0->extern_id] = array(
                                            "extern_id" => $tmpBalance_0->extern_id,
                                            "extern_nama" => $tmpBalance_0->extern_nama,
                                            "debet_prev" => $tmpBalance_0->debet,
                                            "kredit_prev" => $tmpBalance_0->kredit,
                                        );
                                    }
                                    //
                                }

                                // cekBiru($this->db->last_query());
                                // arrPrint($balance);
                                // matiHere($childModel);
                            }


                        }

                    }
                }
                // arrPrint($accountChild);
                // matiHere();

                $this->load->model("Coms/ComRekening");
                $b = new ComRekening();
                $b->addFilter("cabang_id='$pihakId'");

                $balanceForever = $b->fetchAllBalances();

                //region coalist
                $a->addFilter("is_active='1'");
                $a->addFilter("is_rekening_pembantu='0'");
                $a->addFilter("is_gl='1'");
                $temp = $a->lookUpAll()->result();
                $coaLabel = array();
                foreach ($temp as $temp_0) {
                    $coaLabel[$temp_0->head_code] = $temp_0->head_name;
                }
                // arrprint($balanceForever);
                // matiHere();

                //endregion

                $lajur = array();
                $dataRekeningPembantu = array();
                foreach ($balanceForever as $rekID => $dataBal) {
                    $tmpJml = 1;
                    $rid = $rekID;
                    $lajur[$rekID] = $coaLabel[$rekID];
                    if (sizeof($shoppingCartFieldSrc)) {
                        if (!array_key_exists($rid, $_SESSION[$cCode]['items4'])) {
                            $tmp = array(
                                "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                                "id" => $rid,
                                "jml" => $tmpJml,
                                "harga" => 0,
                                "debet" => 0,
                                "kredit" => 0,
                                "subtotal" => 0,
                                // "date_target" => previousYear(),
                            );

                            // arrPrint($shoppingCartFieldSrc);
                            // matiHere();
                            foreach ($shoppingCartFieldSrc as $key => $src) {
                                // cekHere($dataBal[$src] . " " . $src);
                                // $tmp[$key] = makeValue($src, $tmp, $tmp, $row->$src);
                                $tmp[$key] = $dataBal[$src];
                            }

                            // if ($subAmountConfig != null) {
                            //     $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $_SESSION[$cCode]['items'][$rid], 0);
                            // }
                            // else {
                            //     $tmp['subtotal'] = 0;
                            // }
                            $tmp['subtotal'] = 0;
                            // arrPrint($tmp);
                            // matiHEre(__LINE__);
                            //region bagian pembantu forever
                            if (isset($accountChildConfig[$rekID])) {
                                if (isset($childBlackList[$rekID])) {
                                    //gak usah dikikin array nya
                                }
                                else {
                                    $childModel = "Com" . $accountChildConfig[$rekID];
                                    cekungu(":: $childModel ::");
                                    $this->load->model("Coms/" . $childModel);
                                    // cekbiru($childModel."$_temp->rekening");
                                    $curentDate = date("Y");
                                    $m = new $childModel();

                                    $m->setFilters(array());
                                    $m->addFilter("cabang_id='$pihakId'");
                                    $m->addFilter("periode='forever'");
                                    // $m->addFilter("thn='$curentDate'");
                                    $m->addFilter("rekening='" . $rekID . "'");
                                    $this->db->order_by("extern_id", "asc");

                                    $tmpBalanceForever = $m->lookUpAll()->result();


                                    // $temp = $a->lookUpAll()->result();
                                    // cekHitam($this->db->last_query());
                                    // arrprint($tmpBalance);
                                    // matiHEre();
                                    if (sizeof($tmpBalanceForever) > 0) {
                                        foreach ($tmpBalanceForever as $tmpBalanceForever_0) {
                                            // $a->setFilters(array());
                                            // $a->addFilter("p_head_code='$rekID'");
                                            // $a->addFilter("extern_id='".$tmpBalanceForever_0->extern_id."'");
                                            //
                                            // $coaChild = $a->lookUpAll()->result();
                                            // ceklime($this->db->last_query());
                                            // arrPrint($coaChild);
                                            // cekMErah(__LINE__." |$rekID|".$coaChild[0]->head_code." || ".$coaChild[0]->p_head_code);
                                            $accountChild[$rekID]["adj"][$tmpBalanceForever_0->extern_id] = array(

                                                "extern_id" => $tmpBalanceForever_0->extern_id,
                                                "extern_nama" => $tmpBalanceForever_0->extern_nama,
                                                "debet_adj" => 0,
                                                "kredit_adj" => 0,
                                                "editTarget" => base_url() . $this->modul . "/" . $this->uri->segment(2) . "/selectRekeningChild/" . $this->jenisTr . "/" . $this->uri->segment(5),
                                                "comName" => isset($accountChildConfig[$rekID]) ? $accountChildConfig[$rekID] : "none",
                                            );
                                            $accountChild[$rekID]["curent"][$tmpBalanceForever_0->extern_id] = array(

                                                "extern_id" => $tmpBalanceForever_0->extern_id,
                                                "extern_nama" => $tmpBalanceForever_0->extern_nama,
                                                "debet_curent" => $tmpBalanceForever_0->debet,
                                                "kredit_curent" => $tmpBalanceForever_0->kredit,
                                                "debet_ori" => $tmpBalanceForever_0->debet,
                                                "kredit_ori" => $tmpBalanceForever_0->kredit,
                                                "debet_curent_ori" => isset($tmpBalanceForever_0->debet) ? $tmpBalanceForever_0->debet : 0,
                                                "kredit_curent_ori" => isset($tmpBalanceForever_0->kredit) ? $tmpBalanceForever_0->kredit : 0,
                                            );


                                            $dataRekeningPembantu[$rekID][$tmpBalanceForever_0->extern_id] = array(
                                                // "coa_code"=>isset($coaChild[0]->head_code) ? $coaChild[0]->head_code:"",
                                                "extern_id" => $tmpBalanceForever_0->extern_id,
                                                "extern_nama" => $tmpBalanceForever_0->extern_nama,
                                            );


                                        }
                                        //
                                    }

                                    // cekBiru($this->db->last_query());
                                    // arrPrint($balance);
                                    // matiHere($childModel);
                                }


                            }
                            //endregion
                            // matiHere();

                            // $_SESSION[$cCode]['items'][$rid] = $tmp;
                            $_SESSION[$cCode]["items2"][$rekID] = $coaLabel[$rekID];
                            $_SESSION[$cCode]['items4'][$rid] = array(
                                "prev" => array(
                                    "debet_prev" => isset($prevLajur[$rid]["debet"]) ? $prevLajur[$rid]["debet"] : 0,
                                    "kredit_prev" => isset($prevLajur[$rid]["kredit"]) ? $prevLajur[$rid]["kredit"] : 0,
                                ),
                                "adj" => array(
                                    "debet_adj" => 0,
                                    "kredit_adj" => 0,
                                    "editTarget" => base_url() . $this->modul . "/" . $this->uri->segment(2) . "/selectAutoRekening/" . $this->jenisTr . "/" . $this->uri->segment(5),
                                ),
                                "curent" => array(
                                    "debet_curent" => isset($dataBal["debet"]) ? $dataBal["debet"] : 0,
                                    "kredit_curent" => isset($dataBal["kredit"]) ? $dataBal["kredit"] : 0,
                                    "debet_curent_ori" => isset($dataBal["debet"]) ? $dataBal["debet"] : 0,
                                    "kredit_curent_ori" => isset($dataBal["kredit"]) ? $dataBal["kredit"] : 0,
                                ),
                            );
                            $_SESSION[$cCode]['items5_sum'] = $dataRekeningPembantu;
                            $_SESSION[$cCode]['items6_sum'] = $accountChild;


                        }

                    }
                }
            }
            else {

                /*README
                 * sudah ada sesinya, cukup udpate gerbang valuenya
                 * yang perlu diudpate:
                 * items update akan digunakan untuk jurnal
                 * items4 ->untuk ui dan shopingcart
                 * gerbang main akan dicuekin, karena jurnal jalan di items
                 */
                // matiHEre("sampaiii");

            }
            // arrPrint($tempLajur);

            if (!empty($childBlackList)) {
                $_SESSION[$cCode]['rsltItems3'] = $childBlackList;
            }

        }

        // arrprint($dataChildCoa);
        // matiHere(__LINE__);

        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : matiHEre("undefine ppn factor, please logout and login again");
        // region resetor session delivery dan billing detail
        $gateReset = array("main", "tableIn_master_values");
        $resetor = array(
            "vendorDetails",
            "billingDetails",
            "deliveryDetails",
        );
        foreach ($gateReset as $gate) {
            if (isset($_SESSION[$cCode][$gate])) {
                foreach ($_SESSION[$cCode][$gate] as $keys => $values) {
                    $keysTmp = explode("__", $keys);
                    // buang yang sama dulu
                    if (in_array($keys, $resetor)) {
                        unset($_SESSION[$cCode][$gate][$keys]);
                    }
                    // buang yang mengandung __
                    if (in_array($keysTmp[0], $resetor)) {
                        unset($_SESSION[$cCode][$gate][$keys]);
                    }
                }
            }
        }
        if (isset($_SESSION[$cCode]['main_elements'])) {
            foreach ($resetor as $resetValue) {
                if (array_key_exists($resetValue, $_SESSION[$cCode]['main_elements'])) {
                    unset($_SESSION[$cCode]['main_elements'][$resetValue]);
                }
            }
        }
        // endregion

        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $stepNum = (isset($_SESSION[$cCode]['stepNumber']) && ($_SESSION[$cCode]['stepNumber'] > 0)) ? $_SESSION[$cCode]['stepNumber'] : 1;
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "sellerID" => my_id(),
            "sellerName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "jenis_usaha" => my_jenis_usaha(),
            "tokoID" => my_toko_id(),
            "tokoNama" => my_toko_nama(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
            "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
            //                "stepNumber" => isset($stepNum) ? $stepNum : ($stepNum = 0),
            //                "stepCode" => isset($this->configUiJenis['steps'][$stepNum]['target']) ? $this->configUiJenis['steps'][$stepNum]['target'] : 0,
            "stepNumber" => 1,
            "stepCode" => isset($this->configUiJenis['steps'][1]['target']) ? $this->configUiJenis['steps'][1]['target'] : 0,
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($this->jenisTr, $pihakId, $initMasterValues, $this->modul);
//        matiHEre(__LINE__);
        echo "<script>";
        echo "top.document.getElementById('pihakName').value='" . $tmpPihakName . "';";
        echo "top.document.getElementById('pilihan_outlet').innerHTML='';";
        echo "</script>";

        //        mati_disini("==========");

        /* --------------------------------------------------
             * ngereload shoping cart dlm modul
             * --------------------------------------------------*/
        echo "<script>";
        echo "  top.$('#shopping_cart').load('" . MODUL_PATH . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=0');";
        echo "</script>";
    }

    public function select2()
    {

        $pihakMainValueSrc = isset($this->configUi[$this->jenisTr]['pihakMainValueSrc']) ? $this->configUi[$this->jenisTr]['pihakMainValueSrc'] : array();
        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();

        $cCode = "_TR_" . $this->jenisTr;
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $mdlName = $this->uri->segment(5);

        $this->load->model("Mdls/" . $mdlName);
        $b = new $mdlName();
        $tmpB = $b->lookupByID($id)->result();

        if (isset($this->configUi[$this->jenisTr]["pihakMainNota"]) && $this->configUi[$this->jenisTr]["pihakMainNota"] == true) {
            $selectColumn = "nomer";
        }
        else {
            $selectColumn = "nama";
        }

        if (sizeof($tmpB) > 0) {
            $_SESSION[$cCode]['main']['pihak2ID'] = $id;
            $_SESSION[$cCode]['main']['pihak2Name'] = isset($tmpB[0]->$selectColumn) ? $tmpB[0]->$selectColumn : "";
            $_SESSION[$cCode]['main']['pihak2Name2'] = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";
            $_SESSION[$cCode]['main']['pihak2Disc'] = isset($tmpB[0]->diskon) ? $tmpB[0]->diskon : "";
            $_SESSION[$cCode]['main']['pihak2Mdl'] = isset($tmpB[0]->mdl_name) ? $tmpB[0]->mdl_name : "";
            $_SESSION[$cCode]['main']['pihak2Com'] = isset($accountChilds[$tmpB[0]->nama]) ? $accountChilds[$tmpB[0]->nama] : "";

            if (isset($tmpB[0]->exchange)) {
                $_SESSION[$cCode]['main']['pihak2Exchange'] = $tmpB[0]->exchange;
            }


            $resetorPihak3 = array(
                "pihak3ID",
                "pihak3Name",
                "pihak3Name3",
                "pihak3Disc",
                "pihak3Mdl",
                "pihak3Com",
            );
            foreach ($resetorPihak3 as $isi) {
                if (isset($_SESSION[$cCode]['main'][$isi])) {
                    $_SESSION[$cCode]['main'][$isi] = null;
                    unset($_SESSION[$cCode]['main'][$isi]);
                }
                if (isset($_SESSION[$cCode]['items'])) {
                    foreach ($_SESSION[$cCode]['items'] as $pid => $iSpec) {
                        if (isset($iSpec[$isi])) {
                            $iSpec[$isi] = null;
                            unset($iSpec[$isi]);
                        }
                        $_SESSION[$cCode]['items'][$pid] = $iSpec;
                    }
                }
            }


            $tmpPihakName = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";
            if (isset($tmpB[0]->name)) {
                $tmpPihakName = $tmpB[0]->name;
            }
            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
                }
            }

            $this->load->library("ValueGate");
            $vg = new ValueGate();
            $vg->setConfigUiJenis($this->configUiJenis);
            $vg->setConfigCoreJenis($this->configCoreJenis);
            $vg->setConfigValuesJenis($this->configValuesJenis);
            $vg->setPpnFactor(my_ppn_factor());
            $initMasterValues = array(
                "olehID" => my_id(),
                "olehName" => my_name(),
                "sellerID" => my_id(),
                "sellerName" => my_name(),
                "placeID" => my_cabang_id(),
                "placeName" => my_cabang_nama(),
                "divID" => my_div_id(),
                "divName" => my_div_nama(),
                "cabangID" => my_cabang_id(),
                "cabangName" => my_cabang_nama(),
                "gudangID" => my_gudang_id(),
                "gudangName" => my_gudang_nama(),
                "jenis_usaha" => my_jenis_usaha(),
                "tokoID" => my_toko_id(),
                "tokoNama" => my_toko_nama(),
                "jenisTr" => $this->jenisTr,
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
                "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
                "stepNumber" => 1,
                "stepCode" => $this->configUiJenis['steps'][1]['target'],
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow("Y-m-d"),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

            echo "<script>";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "top.document.getElementById('pihak2Name').value='" . $tmpPihakName . "';";
            echo "top.document.getElementById('pilihan_outlet2').innerHTML='';";
            echo "</script>";
        }
        else {
            $_SESSION[$cCode]['main']['pihakID'] = $id;
            $_SESSION[$cCode]['main']['pihakName'] = "default warehouse";
            $_SESSION[$cCode]['main']['pihakName2'] = "";
            //            $_SESSION[$cCode]['out_master']['pihakID'] = $id;
            //            $_SESSION[$cCode]['out_master']['pihakName'] = "default warehouse";
            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
                    //                    $_SESSION[$cCode]['out_master'][$key] = $tmpB[0]->$src;
                }
            }

            $this->load->library("ValueGate");
            $vg = new ValueGate();
            $vg->setConfigUiJenis($this->configUiJenis);
            $vg->setConfigCoreJenis($this->configCoreJenis);
            $vg->setConfigValuesJenis($this->configValuesJenis);
            $vg->setPpnFactor(my_ppn_factor());
            $initMasterValues = array(
                "olehID" => my_id(),
                "olehName" => my_name(),
                "placeID" => my_cabang_id(),
                "placeName" => my_cabang_nama(),
                "divID" => my_div_id(),
                "divName" => my_div_nama(),
                "cabangID" => my_cabang_id(),
                "cabangName" => my_cabang_nama(),
                "gudangID" => my_gudang_id(),
                "gudangName" => my_gudang_nama(),
                "jenis_usaha" => my_jenis_usaha(),
                "tokoID" => my_toko_id(),
                "tokoNama" => my_toko_nama(),
                "jenisTr" => $this->jenisTr,
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
                "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
                "stepNumber" => 1,
                "stepCode" => $this->configUiJenis['steps'][1]['target'],
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow("Y-m-d"),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

            echo "<script>";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "top.document.getElementById('pihakName').value='" . $_SESSION[$cCode]['main']['pihakName2'] . "';";
            echo "top.document.getElementById('pilihan_outlet').innerHTML='';";
            echo "</script>";
        }
    }

    public function select3()
    {

        $pihakMainValueSrc = isset($this->configUi[$this->jenisTr]['pihakMainValueSrc']) ? $this->configUi[$this->jenisTr]['pihakMainValueSrc'] : array();
        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();

        $cCode = "_TR_" . $this->jenisTr;
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        //        $mdlName = $this->uri->segment(5);
        if (isset($_SESSION[$cCode]['main']['pihak2Mdl'])) {
            $mdlName = $_SESSION[$cCode]['main']['pihak2Mdl'];
        }
        else {
            $mdlName = $this->uri->segment(5);
        }

        $this->load->model("Mdls/" . $mdlName);
        $b = new $mdlName();
        $tmpB = $b->lookupByID($id)->result();

        if (isset($this->configUi[$this->jenisTr]["pihakMainNota"]) && $this->configUi[$this->jenisTr]["pihakMainNota"] == true) {
            $selectColumn = "nomer";
        }
        else {
            $selectColumn = "nama";
        }

        if (sizeof($tmpB) > 0) {
            $_SESSION[$cCode]['main']['pihak3ID'] = $id;
            $_SESSION[$cCode]['main']['pihak3Name'] = isset($tmpB[0]->$selectColumn) ? $tmpB[0]->$selectColumn : "";
            $_SESSION[$cCode]['main']['pihak3Name3'] = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";
            $_SESSION[$cCode]['main']['pihak3Disc'] = isset($tmpB[0]->diskon) ? $tmpB[0]->diskon : "";
            $_SESSION[$cCode]['main']['pihak3Mdl'] = isset($tmpB[0]->mdl_name) ? $tmpB[0]->mdl_name : "";
            $_SESSION[$cCode]['main']['pihak3Com'] = isset($accountChilds[$tmpB[0]->nama]) ? $accountChilds[$tmpB[0]->nama] : "";


            $tmpPihakName = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";

            if (isset($tmpB[0]->name)) {
                $tmpPihakName = $tmpB[0]->name;
            }


            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
                }
            }

            $this->load->library("ValueGate");
            $vg = new ValueGate();
            $vg->setConfigUiJenis($this->configUiJenis);
            $vg->setConfigCoreJenis($this->configCoreJenis);
            $vg->setConfigValuesJenis($this->configValuesJenis);
            $vg->setPpnFactor(my_ppn_factor());
            $initMasterValues = array(
                "olehID" => my_id(),
                "olehName" => my_name(),
                "placeID" => my_cabang_id(),
                "placeName" => my_cabang_nama(),
                "divID" => my_div_id(),
                "divName" => my_div_nama(),
                "cabangID" => my_cabang_id(),
                "cabangName" => my_cabang_nama(),
                "gudangID" => my_gudang_id(),
                "gudangName" => my_gudang_nama(),
                "jenis_usaha" => my_jenis_usaha(),
                "tokoID" => my_toko_id(),
                "tokoNama" => my_toko_nama(),
                "jenisTr" => $this->jenisTr,
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
                "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
                "stepNumber" => 1,
                "stepCode" => $this->configUiJenis['steps'][1]['target'],
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow("Y-m-d"),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

            echo "<script>";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "top.document.getElementById('pihak3Name').value='" . $tmpPihakName . "';";
            echo "top.document.getElementById('pilihan_outlet3').innerHTML='';";
            echo "</script>";
        }
        else {
            $_SESSION[$cCode]['main']['pihakID'] = $id;
            $_SESSION[$cCode]['main']['pihakName'] = "default warehouse";
            $_SESSION[$cCode]['main']['pihakName2'] = "";
            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
                    //                    $_SESSION[$cCode]['out_master'][$key] = $tmpB[0]->$src;
                }
            }

            $this->load->library("ValueGate");
            $vg = new ValueGate();
            $vg->setConfigUiJenis($this->configUiJenis);
            $vg->setConfigCoreJenis($this->configCoreJenis);
            $vg->setConfigValuesJenis($this->configValuesJenis);
            $vg->setPpnFactor(my_ppn_factor());
            $initMasterValues = array(
                "olehID" => my_id(),
                "olehName" => my_name(),
                "placeID" => my_cabang_id(),
                "placeName" => my_cabang_nama(),
                "divID" => my_div_id(),
                "divName" => my_div_nama(),
                "cabangID" => my_cabang_id(),
                "cabangName" => my_cabang_nama(),
                "gudangID" => my_gudang_id(),
                "gudangName" => my_gudang_nama(),
                "jenis_usaha" => my_jenis_usaha(),
                "tokoID" => my_toko_id(),
                "tokoNama" => my_toko_nama(),
                "jenisTr" => $this->jenisTr,
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
                "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
                "stepNumber" => 1,
                "stepCode" => $this->configUiJenis['steps'][1]['target'],
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow("Y-m-d"),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

            echo "<script>";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "top.document.getElementById('pihakName').value='" . $_SESSION[$cCode]['main']['pihakName2'] . "';";
            echo "top.document.getElementById('pilihan_outlet').innerHTML='';";
            echo "</script>";
        }
    }

    public function selectExtern()
    {


        $cCode = "_TR_" . $this->jenisTr;
        $id = isset($_GET['id']) ? $_GET['id'] : 0;

        if (isset($_SESSION[$cCode]['main']['pihak2Mdl'])) {
            $mdlName = $_SESSION[$cCode]['main']['pihak2Mdl'];
        }
        else {
            $mdlName = $this->uri->segment(5);
        }


        $this->load->model("Mdls/" . $mdlName);
        $b = new $mdlName();
        $tmpB = $b->lookupByID($id)->result();

        //        if (isset($this->configUi[$this->jenisTr]["pihakMainNota"]) && $this->configUi[$this->jenisTr]["pihakMainNota"] == true) {
        //            $selectColumn = "nomer";
        //        }
        //        else {
        //            $selectColumn = "nama";
        //        }
        $arrCekKolom = array(
            "nomer" => array(
                "pihakMainNota",
                "pihakExternNota"
            ),
            "name" => array("pihakExternName"),
            "nama" => array("pihakExternName"),
        );
        $selectColumn = "nama";
        foreach ($arrCekKolom as $keyCek => $valueCeks) {
            foreach ($valueCeks as $valueCek) {
                if (isset($this->configUi[$this->jenisTr][$valueCek]) && $this->configUi[$this->jenisTr][$valueCek] == true) {
                    $selectColumn = $keyCek;
                    break;
                }
            }
        }

        if (sizeof($tmpB) > 0) {
            $_SESSION[$cCode]['main']['pihakExternID'] = $id;
            $_SESSION[$cCode]['main']['pihakExternMasterID'] = isset($tmpB[0]->id_master) ? $tmpB[0]->id_master : "";
            $_SESSION[$cCode]['main']['pihakExternName'] = isset($tmpB[0]->$selectColumn) ? $tmpB[0]->$selectColumn : "";
            $_SESSION[$cCode]['main']['pihakExternValueSrc'] = isset($tmpB[0]->value_src) ? $tmpB[0]->value_src : "";
            $_SESSION[$cCode]['main']['pihakExternRevertStep'] = isset($tmpB[0]->revertStep) ? $tmpB[0]->revertStep : false;
            $_SESSION[$cCode]['main']['pihakExternDetailGate'] = isset($tmpB[0]->detailGate) ? $tmpB[0]->detailGate : "items";


            $tmpPihakName = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";

            if (isset($tmpB[0]->name)) {
                $tmpPihakName = $tmpB[0]->name;
            }


            if (isset($pihakMainValueSrc) && sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
                }
            }


            $this->load->library("ValueGate");
            $vg = new ValueGate();
            $vg->setConfigUiJenis($this->configUiJenis);
            $vg->setConfigCoreJenis($this->configCoreJenis);
            $vg->setConfigValuesJenis($this->configValuesJenis);
            $vg->setPpnFactor(my_ppn_factor());
            $initMasterValues = array(
                "olehID" => my_id(),
                "olehName" => my_name(),
                "sellerID" => my_id(),
                "sellerName" => my_name(),
                "placeID" => my_cabang_id(),
                "placeName" => my_cabang_nama(),
                "divID" => my_div_id(),
                "divName" => my_div_nama(),
                "cabangID" => my_cabang_id(),
                "cabangName" => my_cabang_nama(),
                "gudangID" => my_gudang_id(),
                "gudangName" => my_gudang_nama(),
                "jenis_usaha" => my_jenis_usaha(),
                "tokoID" => my_toko_id(),
                "tokoNama" => my_toko_nama(),
                "jenisTr" => $this->jenisTr,
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
                "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
                //                "stepNumber" => isset($stepNum) ? $stepNum : ($stepNum = 0),
                //                "stepCode" => isset($this->configUiJenis['steps'][$stepNum]['target']) ? $this->configUiJenis['steps'][$stepNum]['target'] : 0,
                "stepNumber" => 1,
                "stepCode" => isset($this->configUiJenis['steps'][1]['target']) ? $this->configUiJenis['steps'][1]['target'] : 0,
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow("Y-m-d"),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

            //            echo "<script>";
            //            echo "  top.$('#shopping_cart').load('" . MODUL_PATH . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            ////            echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?ohYes=ohNo');";
            //            echo "top.document.getElementById('pihakExternName').value='" . $tmpPihakName . "';";
            //            echo "top.document.getElementById('pihak_extern_label').value='" . $tmpPihakName . "';";
            //            echo "top.document.getElementById('pilihan_outlet').innerHTML='';";
            //            echo "top.document.getElementById('pilihan_extern').innerHTML='';";
            //            echo "</script>";
        }
        else {
            //            $_SESSION[$cCode]['main']['pihakID'] = $id;
            //            $_SESSION[$cCode]['main']['pihakName'] = "default warehouse";
            //            $_SESSION[$cCode]['main']['pihakName2'] = "";
            ////            $_SESSION[$cCode]['out_master']['pihakID'] = $id;
            ////            $_SESSION[$cCode]['out_master']['pihakName'] = "default warehouse";
            //            if (sizeof($pihakMainValueSrc) > 0) {
            //                foreach ($pihakMainValueSrc as $key => $src) {
            //                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
            ////                    $_SESSION[$cCode]['out_master'][$key] = $tmpB[0]->$src;
            //                }
            //            }
            //            echo "<script>";
            //            echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?ohYes=ohNo');";
            //            echo "top.document.getElementById('pihakName').value='" . $_SESSION[$cCode]['main']['pihakName2'] . "';";
            //            echo "top.document.getElementById('pilihan_outlet').innerHTML='';";
            //            echo "</script>";
        }
        //        mati_disini("$selectColumn :: $tmpPihakName");
        echo "<script>";
        echo "top.document.getElementById('pihakExternName').value='" . $tmpPihakName . "';";
        echo "top.document.getElementById('pilihan_extern').innerHTML='';";
        echo "  top.$('#shopping_cart').load('" . MODUL_PATH . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "</script>";
    }

    public function remove()
    {
        $cCode = $this->cCode;

        $_SESSION[$cCode]['main']['pihakID'] = null;
        $_SESSION[$cCode]['main']['pihakName'] = null;
        $_SESSION[$cCode]['main']['pihakName2'] = null;
        unset($_SESSION[$cCode]['main']['pihakID']);
        unset($_SESSION[$cCode]['main']['pihakName']);
        unset($_SESSION[$cCode]['main']['pihakName2']);

        $_SESSION[$cCode]['main']['pihak2ID'] = null;
        $_SESSION[$cCode]['main']['pihak2Name'] = null;
        $_SESSION[$cCode]['main']['pihak2Name2'] = null;
        unset($_SESSION[$cCode]['main']['pihak2ID']);
        unset($_SESSION[$cCode]['main']['pihak2Name']);
        unset($_SESSION[$cCode]['main']['pihak2Name2']);


    }

    public function removeExtern()
    {
        $cCode = $this->cCode;

        $_SESSION[$cCode]['main']['pihakExternID'] = null;
        $_SESSION[$cCode]['main']['pihakExternMasterID'] = null;
        $_SESSION[$cCode]['main']['pihakExternName'] = null;
        $_SESSION[$cCode]['main']['pihakExternValueSrc'] = null;
        $_SESSION[$cCode]['main']['pihakExternRevertStep'] = null;
        $_SESSION[$cCode]['main']['pihakExternDetailGate'] = null;
        unset($_SESSION[$cCode]['main']['pihakExternID']);
        unset($_SESSION[$cCode]['main']['pihakExternMasterID']);
        unset($_SESSION[$cCode]['main']['pihakExternName']);
        unset($_SESSION[$cCode]['main']['pihakExternValueSrc']);
        unset($_SESSION[$cCode]['main']['pihakExternRevertStep']);
        unset($_SESSION[$cCode]['main']['pihakExternDetailGate']);

        //        $_SESSION[$cCode]['main']['pihak2ID'] = null;
        //        $_SESSION[$cCode]['main']['pihak2Name'] = null;
        //        $_SESSION[$cCode]['main']['pihak2Name2'] = null;
        //        unset($_SESSION[$cCode]['main']['pihak2ID']);
        //        unset($_SESSION[$cCode]['main']['pihak2Name']);
        //        unset($_SESSION[$cCode]['main']['pihak2Name2']);


    }

    //---------------------------
    public function select3UM()
    {

        $pihakMainValueSrc = isset($this->configUi[$this->jenisTr]['pihakMainValueSrc']) ? $this->configUi[$this->jenisTr]['pihakMainValueSrc'] : array();
        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();

        $cCode = "_TR_" . $this->jenisTr;
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $mdlName = $this->uri->segment(5);
        //        if (isset($_SESSION[$cCode]['main']['pihak3Mdl'])) {
        //            $mdlName = $_SESSION[$cCode]['main']['pihak3Mdl'];
        //        }
        //        else {
        //            $mdlName = $this->uri->segment(5);
        //        }

        $this->load->model("Mdls/" . $mdlName);
        $b = new $mdlName();
        $tmpB = $b->lookupByID($id)->result();

        if (isset($this->configUi[$this->jenisTr]["pihakMainNota"]) && $this->configUi[$this->jenisTr]["pihakMainNota"] == true) {
            $selectColumn = "nomer";
        }
        else {
            $selectColumn = "nama";
        }

        $dummyElementResetor = isset($this->configUi[$this->jenisTr]["dummyElementResetor"]) ? $this->configUi[$this->jenisTr]["dummyElementResetor"] : array();
        if (sizeof($dummyElementResetor) > 0) {
            foreach ($dummyElementResetor as $rVal) {
                $_SESSION[$cCode]['main'][$rVal] = NULL;
                unset($_SESSION[$cCode]['main'][$rVal]);
            }
        }


        if (sizeof($tmpB) > 0) {
            $_SESSION[$cCode]['main']['pihak3ID'] = $id;
            $_SESSION[$cCode]['main']['pihak3Name'] = isset($tmpB[0]->$selectColumn) ? $tmpB[0]->$selectColumn : "";
            $_SESSION[$cCode]['main']['pihak3Name3'] = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";
            $_SESSION[$cCode]['main']['pihak3Disc'] = isset($tmpB[0]->diskon) ? $tmpB[0]->diskon : "";
            $_SESSION[$cCode]['main']['pihak3Mdl'] = isset($tmpB[0]->mdl_name) ? $tmpB[0]->mdl_name : "";
            $_SESSION[$cCode]['main']['pihak3Com'] = isset($accountChilds[$tmpB[0]->nama]) ? $accountChilds[$tmpB[0]->nama] : "";


            $tmpPihakName = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";

            if (isset($tmpB[0]->name)) {
                $tmpPihakName = $tmpB[0]->name;
            }


            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
                }
            }

            $this->load->library("ValueGate");
            $vg = new ValueGate();
            $vg->setConfigUiJenis($this->configUiJenis);
            $vg->setConfigCoreJenis($this->configCoreJenis);
            $vg->setConfigValuesJenis($this->configValuesJenis);
            $vg->setPpnFactor(my_ppn_factor());
            $initMasterValues = array(
                "olehID" => my_id(),
                "olehName" => my_name(),
                "placeID" => my_cabang_id(),
                "placeName" => my_cabang_nama(),
                "divID" => my_div_id(),
                "divName" => my_div_nama(),
                "cabangID" => my_cabang_id(),
                "cabangName" => my_cabang_nama(),
                "gudangID" => my_gudang_id(),
                "gudangName" => my_gudang_nama(),
                "jenis_usaha" => my_jenis_usaha(),
                "tokoID" => my_toko_id(),
                "tokoNama" => my_toko_nama(),
                "jenisTr" => $this->jenisTr,
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
                "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
                "stepNumber" => 1,
                "stepCode" => $this->configUiJenis['steps'][1]['target'],
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow("Y-m-d"),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

            echo "<script>";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "top.document.getElementById('pihak3Name').value='" . $tmpPihakName . "';";
            echo "top.document.getElementById('pilihan_outlet3').innerHTML='';";
            echo "</script>";
        }
        else {
            $_SESSION[$cCode]['main']['pihakID'] = $id;
            $_SESSION[$cCode]['main']['pihakName'] = "default warehouse";
            $_SESSION[$cCode]['main']['pihakName2'] = "";
            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
                    //                    $_SESSION[$cCode]['out_master'][$key] = $tmpB[0]->$src;
                }
            }

            $this->load->library("ValueGate");
            $vg = new ValueGate();
            $vg->setConfigUiJenis($this->configUiJenis);
            $vg->setConfigCoreJenis($this->configCoreJenis);
            $vg->setConfigValuesJenis($this->configValuesJenis);
            $vg->setPpnFactor(my_ppn_factor());
            $initMasterValues = array(
                "olehID" => my_id(),
                "olehName" => my_name(),
                "placeID" => my_cabang_id(),
                "placeName" => my_cabang_nama(),
                "divID" => my_div_id(),
                "divName" => my_div_nama(),
                "cabangID" => my_cabang_id(),
                "cabangName" => my_cabang_nama(),
                "gudangID" => my_gudang_id(),
                "gudangName" => my_gudang_nama(),
                "jenis_usaha" => my_jenis_usaha(),
                "tokoID" => my_toko_id(),
                "tokoNama" => my_toko_nama(),
                "jenisTr" => $this->jenisTr,
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
                "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
                "stepNumber" => 1,
                "stepCode" => $this->configUiJenis['steps'][1]['target'],
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow("Y-m-d"),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

            echo "<script>";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "top.document.getElementById('pihakName').value='" . $_SESSION[$cCode]['main']['pihakName2'] . "';";
            echo "top.document.getElementById('pilihan_outlet').innerHTML='';";
            echo "</script>";
        }
    }


    public function selectAutoRekening()
    {
        /*README
                      * sudah ada sesinya, cukup udpate gerbang valuenya
                      * yang perlu diudpate:
                      * items update akan digunakan untuk jurnal
                      * items4 ->untuk ui dan shopingcart
                      * gerbang main akan dicuekin, karena jurnal jalan di items
                      */
        // $cekGudang = getDefaultWarehouseID($this->session->login['cabang_id']);
        // arrPrint($cekGudang);
        // matiHEre();
        $pihakMainValueSrc = isset($this->configUi[$this->jenisTr]['pihakMainValueSrc']) ? $this->configUi[$this->jenisTr]['pihakMainValueSrc'] : array();
        $pihakValidate = isset($this->configUi[$this->jenisTr]['pihakValidate']) ? $this->configUi[$this->jenisTr]['pihakValidate'] : array();
        $pihakAddValidate = isset($this->configUi[$this->jenisTr]['pihakAddValidate']) ? $this->configUi[$this->jenisTr]['pihakAddValidate'] : array();
        $srcModel = isset($this->configUi[$this->jenisTr]['selectorModelTarget']) ? $this->configUi[$this->jenisTr]['selectorModelTarget'] : null;
        $shoppingCartFieldSrc = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array();
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $dateTarget = previousYear(dtimeNow("Y-m-d"));
        $cCode = $this->cCode;
        arrPrint($_GET);
        $key = $_GET["key"];
        $rekid = $_GET["rid"];
        $val = $_GET[$key];
        // if (!isset($_SESSION[$cCode]["items"][$rekid])) {
        //     matiHere("Gagal mengambil nilai.<br> silahkan bersihkan shopingcart terlebih dahulu dan pilih ulang cabang");
        // }
        $_SESSION[$cCode]["items"][$rekid][$key] = $val;
        if (!isset($_SESSION[$cCode]["items4"][$rekid])) {
            matiHere("Gagal mengambil nilai.<br> silahkan bersihkan shopingcart terlebih dahulu dan pilih ulang cabang");
        }

        $newKey = $key == "debet_adj" ? "debet" : "kredit";
        foreach ($_SESSION[$cCode]["items4"][$rekid] as $ch => $arrKolom) {
            // arrPrint($arrKolom);
            if (isset($arrKolom[$key])) {
                $_SESSION[$cCode]["items4"][$rekid][$ch][$key] = $val;
                $_SESSION[$cCode]["items3_sum"][$rekid][$newKey] = $val;
                switch ($key) {
                    case "debet_adj":
                        $curent_debet = 0;
                        $curent_kredit = $_SESSION[$cCode]["items4"][$rekid]["curent"]["kredit_curent_ori"] - $val;

                        break;
                    case "kredit_adj":
                        $curent_debet = $_SESSION[$cCode]["items4"][$rekid]["curent"]["debet_curent_ori"] - $val;
                        $curent_kredit = 0;
                        break;
                }
                $_SESSION[$cCode]["items4"][$rekid]["curent"]["debet_curent"] = $curent_debet;
                $_SESSION[$cCode]["items4"][$rekid]["curent"]["kredit_curent"] = $curent_kredit;

            }
        }

        if ($val > 0) {

            $debet_prev = isset($_SESSION[$cCode]["items4"][$rekid]['prev']['debet_prev']) ? $_SESSION[$cCode]["items4"][$rekid]['prev']['debet_prev'] : "0";
            $kredit_prev = isset($_SESSION[$cCode]["items4"][$rekid]['prev']['kredit_prev']) ? $_SESSION[$cCode]["items4"][$rekid]['prev']['kredit_prev'] : "0";
            $debet_curent_ori = isset($_SESSION[$cCode]["items4"][$rekid]['curent']['debet_curent_ori']) ? $_SESSION[$cCode]["items4"][$rekid]['curent']['debet_curent_ori'] : "0";
            $kredit_curent_ori = isset($_SESSION[$cCode]["items4"][$rekid]['curent']['kredit_curent_ori']) ? $_SESSION[$cCode]["items4"][$rekid]['curent']['kredit_curent_ori'] : "0";
            $debet_adj = isset($_SESSION[$cCode]["items4"][$rekid]['adj']['debet_adj']) ? $_SESSION[$cCode]["items4"][$rekid]['adj']['debet_adj'] : "0";
            $kredit_adj = isset($_SESSION[$cCode]["items4"][$rekid]['adj']['kredit_adj']) ? $_SESSION[$cCode]["items4"][$rekid]['adj']['kredit_adj'] : "0";

            if ($debet_curent_ori * 1 > 0) {
                if ($debet_adj * 1 > 0) {
                    $debet_after = $debet_curent_ori * 1 + $debet_adj * 1;
                    if ($debet_after * 1 >= 0) {
                        $kredit_after = 0;
                    }
                    else {
                        $kredit_after = $debet_after * -1;
                        $debet_after = 0;
                    }
                }
                else if ($kredit_adj * 1 > 0) {
                    $debet_after = $debet_curent_ori * 1 - $kredit_adj * 1;
                    if ($debet_after * 1 >= 0) {
                        $kredit_after = 0;
                    }
                    else {
                        $kredit_after = $debet_after * -1;
                        $debet_after = 0;
                    }
                }
                else {

                }

            }
            else if ($kredit_curent_ori * 1 > 0) {
                if ($debet_adj * 1 > 0) {
                    $kredit_after = $kredit_curent_ori * 1 - $debet_adj * 1;
                    if ($kredit_after * 1 >= 0) {
                        $debet_after = 0;
                    }
                    else {
                        $debet_after = $kredit_after * -1;
                        $kredit_after = 0;
                    }
                }
                else if ($kredit_adj * 1 > 0) {
                    $kredit_after = $kredit_curent_ori * 1 + $kredit_adj * 1;
                    if ($kredit_after * 1 >= 0) {
                        $debet_after = 0;
                    }
                    else {
                        $debet_after = $kredit_after * -1;
                        $kredit_after = 0;
                    }
                }
                else {

                }
            }
            else {
                $debet_after = 0;
                $kredit_after = 0;
            }

            $_SESSION[$cCode]["items"][$rekid] = array(
                "id" => $rekid,
                "coa_code" => $rekid,
                "head_code" => $rekid,
                "rekening" => $rekid,
                "rekening_label" => $_SESSION[$cCode]["items2"][$rekid],
                "nama" => $_SESSION[$cCode]["items2"][$rekid],
                "name" => $_SESSION[$cCode]["items2"][$rekid],
                "jml" => 1,
                "qty" => 1,
                "harga" => 0,

//                "$key" => $val,

                "debet_prev" => $debet_prev,
                "kredit_prev" => $kredit_prev,
                "debet_curent_ori" => $debet_curent_ori,
                "kredit_curent_ori" => $kredit_curent_ori,
                "debet_adj" => $debet_adj,
                "kredit_adj" => $kredit_adj,
                "debet_after" => $debet_after,
                "kredit_after" => $kredit_after,

            );
        }
        else {
            if ($val == 0) {
                unset($_SESSION[$cCode]["items3_sum"][$rekid]);
                unset($_SESSION[$cCode]["items"][$rekid]);
            }
        }
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $stepNum = (isset($_SESSION[$cCode]['stepNumber']) && ($_SESSION[$cCode]['stepNumber'] > 0)) ? $_SESSION[$cCode]['stepNumber'] : 1;
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "sellerID" => my_id(),
            "sellerName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "jenis_usaha" => my_jenis_usaha(),
            "tokoID" => my_toko_id(),
            "tokoNama" => my_toko_nama(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
            "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
            //                "stepNumber" => isset($stepNum) ? $stepNum : ($stepNum = 0),
            //                "stepCode" => isset($this->configUiJenis['steps'][$stepNum]['target']) ? $this->configUiJenis['steps'][$stepNum]['target'] : 0,
            "stepNumber" => 1,
            "stepCode" => isset($this->configUiJenis['steps'][1]['target']) ? $this->configUiJenis['steps'][1]['target'] : 0,
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($this->jenisTr, $rekid, $initMasterValues, $this->modul);
//        echo "<script>";
//        echo "  top.$('#shopping_cart').load('" . MODUL_PATH . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=0');";
//        echo "</script>";

        // matiHEre("sampaiii");

    }

    public function selectRekeningChild()
    {
        $cCode = $this->cCode;
        // arrPrint($_GET);
        $key = $_GET["key"];//field yg dikirm
        $rekid = $pihakId = $_GET["rid"];//coa rekening
        $crid = $_GET["crid"];//id pembantu
        $val = $_GET[$key];//nilai adjustment bisa debet/kredit
        $mkey = $_GET["masterkey"];
// matihere(__LINE__);
        /*
         * README
         * - items3_sum menyimpan saldo/ summary dari entry adjustment rekening pembantu nilai yang akan digunakan sebagai dasr jurnal
         * - Akan dibuildkan component menggunakan preproc dengna dasar itema3_sum ke gerbang rsltItems
         * items4 = menyimpan data rekening master untuk ui/shopingcart
         * items5_sum = menyimpan semua pembantu dari rekening besar jika ada pembantunya dengna index rekening
         * items6_sum  = menyimpan data pembantu untuk keperluan ui
         * items7_sum untuk builder auto pembantu keperluan jurnal
         * items8_sum auto builder dari preproc untuk keperluan menulis rekening pembantu karena model yang yang relative berdasarkan jenis rekening yg di adjustment
         */
        $_SESSION[$cCode]["items6_sum"][$rekid][$mkey][$crid][$key] = 0;
        $_SESSION[$cCode]["items4"][$rekid][$key] = 0;
        if (isset($_SESSION[$cCode]["items4"][$rekid])) {
            $_SESSION[$cCode]["items4"][$rekid][$key] = 0;//direset dulu untuk direbuild ulang karena nilai dari pembantu
            $_SESSION[$cCode]["items6_sum"][$rekid][$mkey][$crid][$key] = 0;//direset dulu untuk direbuild ulang karena nilai dari pembantu
            // arrPrint($_SESSION[$cCode]["items4"][$rekid]);
            // arrPrint($_SESSION[$cCode]["items6_sum"][$rekid]);
        }

// arrPrint($_SESSION[$cCode]["items4"][$rekid][$key]);
//         matiHere();
        // matiHEre("lagi dibuild untuk gerbang nilai");

        // $_SESSION[$cCode]["items"][$rekid][$key] = $val;//ini harusnya direset karena berupa saldo dari rekening pembantunya
        $_SESSION[$cCode]["items"][$rekid][$key] = 0;
        if (!isset($_SESSION[$cCode]["items4"][$rekid])) {
            matiHere("Gagal mengambil nilai.<br> silahkan bersihkan shopingcart terlebih dahulu dan pilih ulang cabang");
        }


        $newKey = $key == "debet_adj" ? "debet" : "kredit";

        if (isset($_SESSION[$cCode]["items6_sum"][$rekid])) {
            // arrPrint($_SESSION[$cCode]["items6_sum"][$rekid][$mkey][$crid][$key]);
            // cekMErah($rekid."|| ".$mkey."||".$crid."|| ".$key);
            $_SESSION[$cCode]["items6_sum"][$rekid][$mkey][$crid][$key] = $val;

            $pem_kredit_curent_ori = $_SESSION[$cCode]["items6_sum"][$rekid]["curent"][$crid]["kredit_curent_ori"];
            $pem_debet_curent_ori = $_SESSION[$cCode]["items6_sum"][$rekid]["curent"][$crid]["debet_curent_ori"];

            //tambah untuk keperliuan UI Pembantu
            switch ($key) {
                case "debet_adj":
                    if ($pem_kredit_curent_ori * 1 > 0) {
                        $curent_debet = 0;
                        $curent_kredit = $_SESSION[$cCode]["items6_sum"][$rekid]["curent"][$crid]["kredit_curent_ori"] - $val;
                    }
                    else {
                        $curent_debet = 0;
                        $curent_kredit = $_SESSION[$cCode]["items6_sum"][$rekid]["curent"][$crid]["kredit_curent_ori"] - $val;
                    }
                    break;
                case "kredit_adj":
                    if ($pem_kredit_curent_ori * 1 > 0) {
                        $curent_debet = $_SESSION[$cCode]["items6_sum"][$rekid]["curent"][$crid]["debet_curent_ori"] - $val;
                        $curent_kredit = 0;
                    }
                    else {
                        $curent_debet = $_SESSION[$cCode]["items6_sum"][$rekid]["curent"][$crid]["debet_curent_ori"] - $val;
                        $curent_kredit = 0;
                    }
                    break;
            }
            $_SESSION[$cCode]["items6_sum"][$rekid]["curent"][$crid]["debet_curent"] = $curent_debet;
            $_SESSION[$cCode]["items6_sum"][$rekid]["curent"][$crid]["kredit_curent"] = $curent_kredit;


            $pre_index = $rekid . "_" . $crid;
            unset($_SESSION[$cCode]["items7_sum"][$pre_index]);
            if ($val > 0) {
                $_SESSION[$cCode]["items7_sum"][$pre_index] = array(
                    "rekening_id" => $rekid,
                    "extern_id" => $crid,
                    "extern_nama" => $_SESSION[$cCode]["items6_sum"][$rekid][$mkey][$crid]["extern_nama"],
                    "src_position" => $newKey,
                    "$newKey" => $val,
                    "cabang_id" => $_SESSION[$cCode]["main"]["placeID"],
                    "cabang_nama" => $_SESSION[$cCode]["main"]["placename"],
                    "gudang_id" => $_SESSION[$cCode]["main"]["gudangID"],
                    "gudang_nama" => $_SESSION[$cCode]["main"]["gudangName"],
                    "gudang2ID" => $_SESSION[$cCode]["main"]["gudang2ID"],
                    "gudang2Name" => $_SESSION[$cCode]["main"]["gudang2Name"],
                    "pihakID" => $_SESSION[$cCode]["main"]["pihakID"],
                    "pihakName" => $_SESSION[$cCode]["main"]["pihakName"],
                    "comName" => $_SESSION[$cCode]["items6_sum"][$rekid][$mkey][$crid]["comName"],
                    "olehID" => $_SESSION[$cCode]["main"]["olehID"],
                    "olehName" => $_SESSION[$cCode]["main"]["olehName"],
                    "jenisTr" => $_SESSION[$cCode]["main"]["jenisTr"],
                    // "kredit"=>,
                );
            }
        }
        // arrPrint($_SESSION[$cCode]["items7_sum"]);
        // matiHere(__LINE__);
        //rekap ke rekenening
        // $sumItems = array();
        foreach ($_SESSION[$cCode]["items6_sum"][$rekid] as $data) {
            foreach ($data as $iid => $tempIIData) {
                // arrPrint($tempIIData);
                if (isset($tempIIData[$key])) {
                    if (!isset($sumRek[$rekid][$mkey][$key])) {
                        $sumRek[$rekid][$mkey][$key] = 0;
                    }
                    if (!isset($sumItems[$rekid][$key])) {
                        $sumItems[$rekid][$key] = 0;
                    }
                    $sumRek[$rekid][$mkey][$key] += $tempIIData[$key];
                    $sumItems[$rekid][$key] += $tempIIData[$key];
                }
            }
        }


        // arrPrint($_SESSION[$cCode]["items4"][$rekid]);
//         arrPrint($_SESSION[$cCode]["items6_sum"][$rekid]['adj']);
        //     matiHEre();

        //sum adj hanya untuk rekening yg di pilih
        $sumAdj = array();
        foreach ($_SESSION[$cCode]["items6_sum"][$rekid]['adj'] as $d) {
            if (!isset($sumAdj['debet_adj'])) {
                $sumAdj['debet_adj'] = 0;
            }
            if (!isset($sumAdj['kredit_adj'])) {
                $sumAdj['kredit_adj'] = 0;
            }
            $sumAdj['debet_adj'] += $d['debet_adj'] * 1;
            $sumAdj['kredit_adj'] += $d['kredit_adj'] * 1;
        }


        foreach ($_SESSION[$cCode]["items4"][$rekid] as $ch => $arrKolom) {

            if (isset($arrKolom[$key])) {
                $_SESSION[$cCode]["items4"][$rekid][$ch][$key] = $sumRek[$rekid][$ch][$key];
                $_SESSION[$cCode]["items3_sum"][$rekid][$newKey] = $sumRek[$rekid][$ch][$key];

                $debet_curent_ori = $_SESSION[$cCode]["items4"][$rekid]["curent"]["debet_curent_ori"];
                $kredit_curent_ori = $_SESSION[$cCode]["items4"][$rekid]["curent"]["kredit_curent_ori"];

                switch ($key) {
                    case "debet_adj":
                        if ($debet_curent_ori * 1 > 0) {
                            $curent_debet = $_SESSION[$cCode]["items4"][$rekid]["curent"]["debet_curent_ori"] + $sumRek[$rekid][$ch][$key] - $sumRek[$rekid][$ch]['kredit_adj'];
                            $curent_kredit = 0;
                        }
                        else {
                            $curent_debet = 0;
                            $curent_kredit = $_SESSION[$cCode]["items4"][$rekid]["curent"]["kredit_curent_ori"] - $sumRek[$rekid][$ch][$key] + $sumRek[$rekid][$ch]['kredit_adj'];
                        }
                        break;
                    case "kredit_adj":
                        if ($debet_curent_ori * 1 > 0) {
                            $curent_debet = $_SESSION[$cCode]["items4"][$rekid]["curent"]["debet_curent_ori"] - $sumRek[$rekid][$ch][$key] + $sumRek[$rekid][$ch]['debet_adj'];
                            $curent_kredit = 0;
                        }
                        else {
                            $curent_debet = 0;
                            $curent_kredit = $_SESSION[$cCode]["items4"][$rekid]["curent"]["kredit_curent_ori"] + $sumRek[$rekid][$ch][$key] - $sumRek[$rekid][$ch]['debet_adj'];
                        }
                        break;
                }

                $_SESSION[$cCode]["items4"][$rekid]["curent"]["debet_curent"] = $curent_debet;
                $_SESSION[$cCode]["items4"][$rekid]["curent"]["kredit_curent"] = $curent_kredit;

                //tambahan buat balance rekening utama
                $debet_adj = $sumAdj['debet_adj'];
                $kredit_adj = $sumAdj['kredit_adj'];

                if ($debet_adj * 1 > 0 && $kredit_adj * 1 > 0) {
                    if ($debet_adj * 1 > $kredit_adj * 1) {
                        $_SESSION[$cCode]["items4"][$rekid]["adj"]["debet_adj"] = $debet_adj * 1 - $kredit_adj * 1;
                        $_SESSION[$cCode]["items4"][$rekid]["adj"]["kredit_adj"] = 0;
                    }
                    else if ($kredit_adj * 1 > $debet_adj * 1) {
                        $_SESSION[$cCode]["items4"][$rekid]["adj"]["debet_adj"] = 0;
                        $_SESSION[$cCode]["items4"][$rekid]["adj"]["kredit_adj"] = $kredit_adj * 1 - $debet_adj * 1;
                    }
                    else {
                        cekHere("ada yang masuk sini gak...???? LINE: " . __LINE__);
                        $_SESSION[$cCode]["items4"][$rekid]["adj"]["debet_adj"] = 0;
                        $_SESSION[$cCode]["items4"][$rekid]["adj"]["kredit_adj"] = 0;
                    }
                }
            }
        }


        // matiHere(__LINE__);
        if ($val > 0) {

        }
        else {
            if ($val == 0) {
                unset($_SESSION[$cCode]["items3_sum"][$rekid]);
                unset($_SESSION[$cCode]["items"][$rekid]);
            }
        }

        //load ulang mdl account untuk ambil data yang akan di inject ke items
        if (sizeof($_SESSION[$cCode]["items3_sum"][$rekid]) > 0) {
            // $this->load->model("Mdls/MdlAccounts");
            // $a = new MdlAccounts();
            // $a->addFilter("head_code='$rekid'");
            // // $a->addFilter("is_rekening_pembantu='0'");
            // // $a->addFilter("is_gl='1'");
            // $temp = $a->lookUpAll()->result();
            //update ke items

            $debet_prev = isset($_SESSION[$cCode]["items4"][$rekid]['prev']['debet_prev']) ? $_SESSION[$cCode]["items4"][$rekid]['prev']['debet_prev'] : "0";
            $kredit_prev = isset($_SESSION[$cCode]["items4"][$rekid]['prev']['kredit_prev']) ? $_SESSION[$cCode]["items4"][$rekid]['prev']['kredit_prev'] : "0";
            $debet_curent_ori = isset($_SESSION[$cCode]["items4"][$rekid]['curent']['debet_curent_ori']) ? $_SESSION[$cCode]["items4"][$rekid]['curent']['debet_curent_ori'] : "0";
            $kredit_curent_ori = isset($_SESSION[$cCode]["items4"][$rekid]['curent']['kredit_curent_ori']) ? $_SESSION[$cCode]["items4"][$rekid]['curent']['kredit_curent_ori'] : "0";
            $debet_adj = isset($_SESSION[$cCode]["items4"][$rekid]['adj']['debet_adj']) ? $_SESSION[$cCode]["items4"][$rekid]['adj']['debet_adj'] : "0";
            $kredit_adj = isset($_SESSION[$cCode]["items4"][$rekid]['adj']['kredit_adj']) ? $_SESSION[$cCode]["items4"][$rekid]['adj']['kredit_adj'] : "0";

            if ($debet_curent_ori * 1 > 0) {
                if ($debet_adj * 1 > 0) {
                    $debet_after = $debet_curent_ori * 1 + $debet_adj * 1;
                    if ($debet_after * 1 >= 0) {
                        $kredit_after = 0;
                    }
                    else {
                        $kredit_after = $debet_after * -1;
                        $debet_after = 0;
                    }
                }
                else if ($kredit_adj * 1 > 0) {
                    $debet_after = $debet_curent_ori * 1 - $kredit_adj * 1;
                    if ($debet_after * 1 >= 0) {
                        $kredit_after = 0;
                    }
                    else {
                        $kredit_after = $debet_after * -1;
                        $debet_after = 0;
                    }
                }
                else {

                }

            }
            else if ($kredit_curent_ori * 1 > 0) {
                if ($debet_adj * 1 > 0) {
                    $kredit_after = $kredit_curent_ori * 1 - $debet_adj * 1;
                    if ($kredit_after * 1 >= 0) {
                        $debet_after = 0;
                    }
                    else {
                        $debet_after = $kredit_after * -1;
                        $kredit_after = 0;
                    }
                }
                else if ($kredit_adj * 1 > 0) {
                    $kredit_after = $kredit_curent_ori * 1 + $kredit_adj * 1;
                    if ($kredit_after * 1 >= 0) {
                        $debet_after = 0;
                    }
                    else {
                        $debet_after = $kredit_after * -1;
                        $kredit_after = 0;
                    }
                }
                else {

                }
            }
            else {
                $debet_after = 0;
                $kredit_after = 0;
            }


            $_SESSION[$cCode]["items"][$rekid] = array(
                "id" => $rekid,
                "coa_code" => $rekid,
                "head_code" => $rekid,
                "rekening" => $rekid,
                "rekening_label" => $_SESSION[$cCode]["items2"][$rekid],
                "nama" => $_SESSION[$cCode]["items2"][$rekid],
                "name" => $_SESSION[$cCode]["items2"][$rekid],
                "jml" => 1,
                "qty" => 1,
                "harga" => 0,
                "prev" => 0,
                // $_SESSION[$cCode]["items4"][$rekid]["curent"]["kredit_curent"] = $curent_kredit;
//                "$key" => $sumItems[$rekid][$key],

                "debet_prev" => $debet_prev,
                "kredit_prev" => $kredit_prev,
                "debet_curent_ori" => $debet_curent_ori,
                "kredit_curent_ori" => $kredit_curent_ori,
                "debet_adj" => $debet_adj,
                "kredit_adj" => $kredit_adj,

                "debet_after" => $debet_after,
                "kredit_after" => $kredit_after,

            );

        }
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $stepNum = (isset($_SESSION[$cCode]['stepNumber']) && ($_SESSION[$cCode]['stepNumber'] > 0)) ? $_SESSION[$cCode]['stepNumber'] : 1;
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "sellerID" => my_id(),
            "sellerName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "jenis_usaha" => my_jenis_usaha(),
            "tokoID" => my_toko_id(),
            "tokoNama" => my_toko_nama(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
            "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
            //                "stepNumber" => isset($stepNum) ? $stepNum : ($stepNum = 0),
            //                "stepCode" => isset($this->configUiJenis['steps'][$stepNum]['target']) ? $this->configUiJenis['steps'][$stepNum]['target'] : 0,
            "stepNumber" => 1,
            "stepCode" => isset($this->configUiJenis['steps'][1]['target']) ? $this->configUiJenis['steps'][1]['target'] : 0,
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($this->jenisTr, $pihakId, $initMasterValues, $this->modul);
        // arrPrint($_GET);
        //        echo "<script>";
        //        echo "  top.$('#shopping_cart').load('" . MODUL_PATH . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=0');";
        //        echo "</script>";

    }
}