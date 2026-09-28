<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/17/2018
 * Time: 2:51 PM
 */
class _selectorItemAktiva extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper("he_session_replacer");
        $this->jenisTr = $this->uri->segment(4);
        $cCode = "_TR_" . $this->jenisTr;

    }

    public function selectItem()
    {

        $jenisTr = $this->uri->segment(4);
        $sesionReplacer = replaceSession();

        $cCode = "_TR_" . $jenisTr;
        $cekES = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : "";
        $cID = isset($_SESSION[$cCode]['main']['placeID']) ? $_SESSION[$cCode]['main']['placeID'] : $this->session->login['cabang_id'];
        $gID = isset($_SESSION[$cCode]['main']['gudangID']) ? $_SESSION[$cCode]['main']['gudangID'] : $this->session->login['gudang_id'];


        $mdlName = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->uri->segment(5);

        $fields = $this->config->item("heTransaksi_ui")[$jenisTr]['selectorFields'];
        $modelFilter = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorFilters']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorFilters'] : array();
        $modelFilter2 = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorFilters2']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorFilters2'] : array();
        $selectorFields = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorViewedFields']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorViewedFields'] : array();
        $selectorParamFields = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorParamFields']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorParamFields'] : array();

        $selectorModel = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorModel']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorModel'] : "MdlProduk";
        $selectorSrcModel = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorSrcModel']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorSrcModel'] : "MdlProduk";
        $selectorView = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorView']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorView'] : "_selectorAktiva";
        $key = isset($_GET['search']) ? $_GET['search'] : "";
        $preLocker = isset($this->config->item("heTransaksi_ui")[$jenisTr]['validLocker']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['validLocker'] : false;
        $items = array();

        if ($preLocker) {
            $mdlPreLocker = $this->config->item("heTransaksi_ui")[$jenisTr]["lockerCheck"]["lockerCheck"]["mdlName"];
            $this->load->model("Mdls/" . $mdlPreLocker);
            $pl = new $mdlPreLocker();
        }

        $this->load->model("Mdls/MdlAsetDetail");
        $p = new MdlAsetDetail();

        if (sizeof($modelFilter2) > 0) {
            foreach ($modelFilter2 as $f) {
                $f_ex = explode("=", $f);
                if (!isset($f_ex[1])) {
                    $f_ey = explode(">", $f_ex[0]);
                    if (substr($f_ey[1], 0, 1) == ".") {
                        $p->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                    }
                    else {
                        if (isset($_SESSION[$cCode]['main'][$f_ey[1]])) {
                            $p->addFilter($f_ey[0] . ">'" . $_SESSION[$cCode]['main'][$f_ey[1]] . "'");
                        }
                        else {
                            $p->addFilter($f_ey[0] . ">0");
                        }
                    }
                }
                else {
                    if (substr($f_ex[1], 0, 1) == ".") {
                        $p->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                    }
                    else {
                        if (isset($_SESSION[$cCode]['main'][$f_ex[1]])) {
                            $p->addFilter($f_ex[0] . "='" . $_SESSION[$cCode]['main'][$f_ex[1]] . "'");
                        }
                        else {
                            $p->addFilter($f_ex[0] . "=''");
                        }

                    }
                }
            }
        }

//        $this->db->limit(20);
        $tmp1 = $p->lookupByKeyword($key)->result();
        cekOrange($this->db->last_query());
        $selectedAsset = array();
        if (sizeof($tmp1) > 0) {
            foreach ($tmp1 as $ky => $sel) {
                $selectedAsset[] = $sel->id;
            }
        }

        //region load setup_depre
        $this->load->model("Mdls/MdlSetupDepresiasiAssetsSales");
        $setup = new MdlSetupDepresiasiAssetsSales();
        $setup->addFilter("cabang_id='" . $_SESSION['login']['cabang_id'] . "'");
        $tmpSetup = $setup->lookupAll()->result();

//        arrPrint($tmpSetup);

        $prdSetup = array();
        if (sizeof($tmpSetup) > 0) {
            foreach ($tmpSetup as $stp) {
                $prdSetup[$stp->extern_id] = $stp;
            }
        }


//        arrPrint($prdSetup);
        //endregion load setup_depre

        //region load cache pembantu aset
        $this->load->model("Coms/ComRekeningPembantuAktivaBerwujud");
        $oi = new ComRekeningPembantuAktivaBerwujud();
        $oi->addFilter("cabang_id='" . $_SESSION['login']['cabang_id'] . "'");
        $oi->addFilter("periode='forever'");
        $oi->addFilter("");
        $tmpi = $oi->lookupAll()->result();

        $prdCache = array();
        if (sizeof($tmpi) > 0) {
            foreach ($tmpi as $cache) {
                $prdCache[$cache->extern_id] = $cache->debet;
            }
        }

//        cekOrange($this->db->last_query());
//        arrPrint($prdCache);
        //endregion load cache pembantu aset

        $this->load->model("Mdls/MdlLockerValue");
        $l = new MdlLockerValue();
        $l->addFilter("jenis=aktiva");
        $l->addFilter("cabang_id='" . $_SESSION['login']['cabang_id'] . "'");
        $l->addFilter("state=active");
        $lockerValue = $l->lookupAll()->result();

        $lkValue = array();
        if (sizeof($lockerValue) > 0) {
            foreach ($lockerValue as $lock) {
                $lkValue[$lock->produk_id] = $lock;
            }
        }

//        arrPrint($lkValue);

        $this->load->model("Mdls/" . $mdlName);
        $o = new $mdlName();
        //pairing produk
        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();

//        arrPrint($selectedAsset);

        if (sizeof($modelFilter) > 0) {
            foreach ($modelFilter as $f) {
                $f_ex = explode("=", $f);
                if (!isset($f_ex[1])) {
                    $f_ey = explode(">", $f_ex[0]);
                    if (substr($f_ey[1], 0, 1) == ".") {
                        $o->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                    }
                    else {
                        if (isset($_SESSION[$cCode]['main'][$f_ey[1]])) {
                            $o->addFilter($f_ey[0] . ">'" . $_SESSION[$cCode]['main'][$f_ey[1]] . "'");
                        }
                        else {
                            $o->addFilter($f_ey[0] . ">0");
                        }
                    }
                }
                else {
                    if (substr($f_ex[1], 0, 1) == ".") {
                        $o->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                    }
                    else {
                        if (isset($_SESSION[$cCode]['main'][$f_ex[1]])) {
                            $o->addFilter($f_ex[0] . "='" . $_SESSION[$cCode]['main'][$f_ex[1]] . "'");
                        }
                        else {
                            $o->addFilter($f_ex[0] . "=''");
                        }
                    }
                }
            }
        }

        $this->db->limit(20);
        $tmp2 = $o->lookupByKeyword($key)->result();

        $tmpO = array();
        if (sizeof($tmp2) > 0) {
            foreach ($tmp2 as $k => $aset) {
                if (in_array($aset->produk_id, $selectedAsset)) {
                    $tmpO[] = $aset;
                }
            }
        }

        if (sizeof($tmpO) > 0) {
            $processor = base_url() . $this->config->item("heTransaksi_ui")[$jenisTr]['selectorProcessor'] . "/$jenisTr";
            if (isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorSocket'])) {
                $socketConfig = $this->config->item("heTransaksi_ui")[$jenisTr]['selectorSocket'];
            }
            $socketParams = array();
            $socketURL = array();

            $colors = array(
                "#000000",
                "#0056cd",
                "#ff7700",
                "#009900",
                "#9999cc",
            );
            foreach ($tmpO as $row) {
                $satuan = isset($row->satuan) && strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                $pID = isset($row->produk_id) ? $row->produk_id : $row->id;

                $this->load->model("MdlTransaksi");
                $tr = new MdlTransaksi();
                $tr->addFilter("transaksi.jenis=8787");
                $tr->addFilter("transaksi_data.produk_id='" . $pID . "'");

                $tmpHist = $tr->lookupRecentUndoneEntries_joined($sesionReplacer)->result();

                $arrListDepre = array();
                if (sizeof($tmpHist) > 0) {
                    foreach ($tmpHist as $k => $datas) {
                        $arrListDepre[] = $datas;
                    }
                }

//                arrPrint($prdSetup[$pID]);

                $countDepre = count($arrListDepre);
                $countDepre = $prdSetup[$pID]->used > 0 ? ($prdSetup[$pID]->used + $countDepre) : $countDepre;

                $b->addFilter($b->getTableName() . ".id=" . $pID);
                $tmpP = $b->lookupAll($pID)->result();

                $defaultValue = isset($tmpP[0]->moq) ? $tmpP[0]->moq : 0;
                foreach ($selectorParamFields as $key => $src) {

                    $isSetup = isset($prdSetup[$pID]) && sizeof($prdSetup[$pID]) > 0 ? "<span class='text-green'>" . number_format($prdCache[$pID], 0) . "</span> / <span class='text-orange'>" . number_format($lkValue[$pID]->nilai, 0) . "</span>" : "<span class='text-red'>not yet set <i onclick=\"window.open('" . base_url() . "SetupDepresiasi/view/Assets?show=" . $pID . "')\" class='btn btn-mn btn-success glyphicon glyphicon-wrench'></i></span>";
//                    $countDepreTxt = $countDepre>0 ? "<span class='badge bg-red'>".$countDepre."x depre</span>" : "<span class='text-green'>".number_format($prdCache[$pID],0)."</span>";
                    $countDepreTxt = $countDepre > 0 ? "<span class='badge bg-red'>" . $countDepre . "x depre</span>" : "blm depre";
                    $tmp['extra_button'] = $key == "nama" ? "<div style='padding-top:2px;border-top: 1px solid lightgray;margin-top:3px;'> $countDepreTxt | $isSetup </div>" : "";

                    $tmp[$key] = $row->$src != "" ? $row->$src : "$key - null ";
                }


                $tmp['minValue'] = $defaultValue;
                $tmp['target'] = $processor;
                $tmp['label'] = "";
                if (sizeof($selectorFields) > 0) {
                    $nCtr = 0;
                    foreach ($selectorFields as $f) {
                        $nCtr++;
                        $align = $nCtr == 1 ? "text-left" : "text-right";
                        $fSize = $nCtr == 1 ? "font-size:0.9em" : "font-size:0.7em";
                        $color = isset($colors[$nCtr]) ? $colors[$nCtr] : "#000000";
                        if (is_numeric($row->$f)) {
                            $tmp['label'] .= "<span style='$fSize ;margin:0px 2px 0px 2px;color:$color;' class='no-padding no-border $align'>" . number_format($row->$f) . "</span>";
                        }
                        else {
                            $newFields = in_array($f, arrAvailFields()) ? formatNota($f, $row->$f) : $row->$f;
                            $tmp['label'] .= "<span style='$fSize ;margin:0px 2px 0px 2px;color:$color;' class='no-padding no-border $align'>" . $newFields . "</span>";
                        }
                    }
                    $tmp['label'] = rtrim($tmp['label'], "| ");
                    $tmp['label'] = ($tmp['label']);
                }


                $addParams = array(
                    "cCode" => $cCode,
                );
                $socketURL[$tmp['id']] = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorSocket']) ? base_url() . $this->config->item("heTransaksi_ui")[$jenisTr]['selectorSocket']['targetURL'] . "?" : "";
                $socketParams[$tmp['id']] = isset($socketConfig['inParams']) ? $socketConfig['inParams'] : array();

                if (isset($socketParams[$tmp['id']]) && sizeof($socketParams[$tmp['id']]) > 0) {
                    foreach ($socketParams[$tmp['id']] as $key => $src) {
                        $socketURL[$tmp['id']] .= "&$key={" . $src . "}";
                    }
                    if (sizeof($addParams) > 0) {
                        foreach ($addParams as $key => $src) {
                            $socketURL[$tmp['id']] .= "&$key=$src";
                        }
                    }
                }

                if ($preLocker) {
                    $stokLocker = $pl->cekLoker($cID, $pID, "active", "", "", $gID);
                    $valLocker = isset($stokLocker['jumlah']) ? $stokLocker['jumlah'] : 0;

                    if ($valLocker > 0) {
                        $items[] = $tmp;
                    }
                }
                else {
                    $items[] = $tmp;
                }
            }
        }
        else {

        }

        $data = array(
            "mode" => "view",
            "cCode" => "$cCode",
            "items" => $items,
            "socketParams" => isset($socketParams) ? $socketParams : array(),
            "socketURL" => isset($socketURL) ? $socketURL : "",
        );

        $this->load->view("$selectorView", $data);

    }


    public function selectItem2()
    {

        $jenisTr = $this->uri->segment(4);
        $cCode = "_TR_" . $jenisTr;
        $mdlName = $this->uri->segment(5);


        $fields = $this->config->item("heTransaksi_ui")[$jenisTr]['selectorFields'];
        $modelFilter = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorFilters']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorFilters'] : array();
        $selectorFields = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorViewedFields']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorViewedFields'] : array();
        $selectorParamFields = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorParamFields']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorParamFields'] : array();

        $selectorModel = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorModel2']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorModel2'] : "MdlProduk";
        $selectorSrcModel = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorSrcModel2']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['selectorSrcModel2'] : "MdlProduk";

        $key = isset($_GET['search']) ? $_GET['search'] : "";
        $items = array();

        $this->load->model("Mdls/" . $mdlName);
        $o = new $mdlName();

        //pairing produk
        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();

        if (sizeof($modelFilter) > 0) {
            foreach ($modelFilter as $f) {
                $f_ex = explode("=", $f);
                if (!isset($f_ex[1])) {
                    $f_ey = explode(">", $f_ex[0]);
                    if (substr($f_ey[1], 0, 1) == ".") {
                        $o->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                    }
                    else {
                        if (isset($_SESSION[$cCode]['main'][$f_ey[1]])) {

                            $o->addFilter($f_ey[0] . ">'" . $_SESSION[$cCode]['main'][$f_ey[1]] . "'");
                        }
                    }
                }
                else {
                    if (substr($f_ex[1], 0, 1) == ".") {
                        $o->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                    }
                    else {
                        if (isset($_SESSION[$cCode]['main'][$f_ex[1]])) {
                            $o->addFilter($f_ex[0] . "='" . $_SESSION[$cCode]['main'][$f_ex[1]] . "'");
                        }

                    }
                }
            }
        }

//        $o->createSmartSearch($key,$o->getListedFieldsSelectItem());
        $tmpO = $o->lookupByKeyword($key)->result();
//        cekmerah($this->db->last_query());

        if (sizeof($tmpO) > 0) {
            $processor = base_url() . $this->config->item("heTransaksi_ui")[$jenisTr]['selectorProcessor2'] . "/$jenisTr";

            if (isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorSocket'])) {
                $socketConfig = $this->config->item("heTransaksi_ui")[$jenisTr]['selectorSocket'];
            }
            $socketParams = array();
            $socketURL = array();

//            arrprint($tmpO);

            foreach ($tmpO as $row) {
                $satuan = isset($row->satuan) && strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                $pID = isset($row->produk_id) ? $row->produk_id : $row->id;

                $b->addFilter("id=" . $pID);
                $tmpP = $b->lookupAll($pID)->result();
//                cekkuning($this->db->last_query());
                $defaultValue = isset($tmpP[0]->moq) ? $tmpP[0]->moq : 0;
                foreach ($selectorParamFields as $key => $src) {
                    $tmp[$key] = $row->$src;
                }
                $tmp['minValue'] = $defaultValue;
                $tmp['target'] = $processor;
                $tmp['label'] = "";
                if (sizeof($selectorFields) > 0) {
                    foreach ($selectorFields as $f) {
                        if (is_numeric($row->$f)) {
                            $tmp['label'] .= "" . number_format($row->$f) . " | ";
                        }
                        else {
                            $tmp['label'] .= "" . $row->$f . " | ";
                        }
                    }
                    $tmp['label'] = rtrim($tmp['label'], "| ");
//                    $tmp['label'] = "<div class='no-padding'>". $tmp['label'] . "</div> " . $row->jumlah ;
//                    $tmp['label'] = "<div style='font-size:0.8em' class='no-padding'>" . ($tmp['label']) . "</div> ";
                    $tmp['label'] = "<div style='font-size:0.8em' class='no-padding'>" . ($tmp['label']) . "</div> ";

                }


                $addParams = array(
                    "cCode" => $cCode,
                );
                $socketURL[$tmp['id']] = isset($this->config->item("heTransaksi_ui")[$jenisTr]['selectorSocket']) ? base_url() . $this->config->item("heTransaksi_ui")[$jenisTr]['selectorSocket']['targetURL'] . "?" : "";
                $socketParams[$tmp['id']] = isset($socketConfig['inParams']) ? $socketConfig['inParams'] : array();
                if (isset($socketParams[$tmp['id']]) && sizeof($socketParams[$tmp['id']]) > 0) {
                    foreach ($socketParams[$tmp['id']] as $key => $src) {
                        $socketURL[$tmp['id']] .= "&$key={" . $src . "}";
                    }
                    if (sizeof($addParams) > 0) {
                        foreach ($addParams as $key => $src) {
                            $socketURL[$tmp['id']] .= "&$key=$src";
                        }
                    }
                }


                $items[] = $tmp;
            }
        }


        $data = array(
            "mode" => "view",
            "cCode" => "$cCode",
            //            "arrayFields"=>$selectorFields,
            "items" => $items,
            "socketParams" => isset($socketParams) ? $socketParams : array(),
            "socketURL" => isset($socketURL) ? $socketURL : "",
        );


//        arrprint($data);die();

        $this->load->view("_selector", $data);

    }

}