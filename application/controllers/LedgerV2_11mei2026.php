<?php

/**
 * Created by PhpStorm.
 * User: chepy (vansgare)
 * Date: 12/31/2025
 * Time: 10:00 PM
 */

class LedgerV2 extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
    }

    /*
     * ################################## viewBalances_l1 ##################################
     */
    public function viewBalances_l1_header($relName, $rekName, $cabangID = NULL)
    {

        $defPosition = detectRekDefaultPosition($rekName);
        $cfg = $this->config->item('accountBalanceProfiles')[$relName];

        $header = $cfg['header'];
        $headerFields = array();

        foreach($header as $kky => $label){
            if (strpos($kky, ':') !== false) {
                $kky = preg_replace('/:.*/', '' . $defPosition, $kky);
            }
            $headerFields[$kky] = $label;
        }

        return $headerFields;

    }
    public function viewBalances_l1()
    {
        $relName = $this->uri->segment(3);
        $rekName = urldecode($this->uri->segment(4));
        $q = isset($_GET['q']) && strlen($_GET['q']) ? $_GET['q'] : "";
        $defPosition = detectRekDefaultPosition($rekName);
        $headerFields = $this->viewBalances_l1_header($relName, $rekName);

        $cfgAll = $this->config->item('accountBalanceProfiles');
        $cfg = $cfgAll[$relName];

        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];
        $blob_ext = isset($_GET['blob_ext']) && strlen($_GET['blob_ext']) ? blobDecode($_GET['blob_ext']) : NULL;
        $getExternID = isset($_GET['ext_id']) && strlen($_GET['ext_id']) ? $_GET['ext_id'] : NULL;
        $getExtern2ID = isset($_GET['ext2_id']) && strlen($_GET['ext2_id']) ? $_GET['ext2_id'] : NULL;
        $getDate1 = isset($_GET['date']) && strlen($_GET['date']) ? $_GET['date'] : NULL;
        $getMainExtern2ID = isset($_GET['main_ext2_id']) && strlen($_GET['main_ext2_id']) ? $_GET['main_ext2_id'] : NULL;

        $periode_txt = $getDate1 !== NULL ? date("Y F", strtotime($getDate1)) : "";

        $subTitle = "account balance  <b class='text-red'>$periode_txt</b>";

        if ($q != "") {
            $subTitle .= " matched '$q'";
        }
        $thisPage = base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "?o=$cabangID";
        $thisURL  = base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "?q=$q&o=$cabangID";

        $mdlName = "Com" . $relName;
        $rekName_f = isset(fetchAccountStructureAlias()[$rekName]) ? fetchAccountStructureAlias()[$rekName] : $rekName;

        $showHutangHierarchy = false;
        $hutangHierarchyCurrent = "";
        $hutangHierarchyLinks = array();
        $hutangHierarchyNote = "";
        $hutangHierarchyEntityLabel = "entitas";
        $hutangHierarchyTotals = array(
            "all" => 0,
            "supplier_count" => 0,
            "cabang_count" => 0,
        );
        $hutangHierarchyAccounts = array();
        if ($relName == "RekeningPembantuHutangSaham") {
            $hutangHierarchyEntityLabel = "pemegang saham";
            $hutangHierarchyAccounts = array(
                "2020010" => array(
                    "label" => "Hutang ke Pemegang Saham",
                    "note" => "Kewajiban Pinjaman kepada Pemegang Saham",
                    "aliases" => array("2020010", "hutang ke pemegang saham", "hutang saham"),
                    "route_rel" => "RekeningPembantuHutangSaham",
                    "summary_rel" => "RekeningPembantuHutangSaham",
                ),
            );
            $hutangHierarchyNote = "Mode hutang pemegang saham aktif: saldo ditampilkan per akun dengan total kewajiban.";
        }
        elseif ($relName == "RekeningPembantuHutangBiayaBunga") {
            $hutangHierarchyEntityLabel = "entitas";
            $hutangHierarchyAccounts = array(
                "2010070" => array(
                    "label" => "Hutang Biaya Bunga",
                    "note" => "Kewajiban Biaya Bunga Berjalan",
                    "aliases" => array("2010070", "hutang biaya bunga"),
                    "route_rel" => "RekeningPembantuHutangBiayaBunga",
                    "summary_rel" => "RekeningPembantuHutangBiayaBunga",
                ),
            );
            $hutangHierarchyNote = "Mode hutang biaya bunga aktif: saldo ditampilkan per akun dengan total biaya bunga.";
        }
        elseif ($relName == "RekeningPembantuPph" || $relName == "RekeningPembantuPphMain") {
            $hutangHierarchyEntityLabel = "wajib pajak";
            $hutangHierarchyAccounts = array(
                "2030030" => array(
                    "label" => "Hutang PPh 23",
                    "note" => "Kewajiban Pajak Potong PPh 23",
                    "aliases" => array("2030030", "hutang pph23", "pph23"),
                    "route_rel" => $relName,
                    "summary_rel" => $relName,
                ),
            );
            $hutangHierarchyNote = "Mode hutang PPh aktif: saldo ditampilkan per akun dengan total kewajiban pajak.";
        }

        if (sizeof($hutangHierarchyAccounts) > 0) {
            $accountAliases = fetchAccountStructureAlias();
            $rekNameNorm = strtolower(trim((string)$rekName));
            foreach ($hutangHierarchyAccounts as $rekCode => $rekMeta) {
                $rekAliases = isset($rekMeta["aliases"]) && is_array($rekMeta["aliases"]) ? $rekMeta["aliases"] : array($rekCode);
                if (!in_array($rekCode, $rekAliases)) {
                    $rekAliases[] = $rekCode;
                }
                if (isset($accountAliases[$rekCode]) && trim((string)$accountAliases[$rekCode]) != "") {
                    $rekAliases[] = trim((string)$accountAliases[$rekCode]);
                }
                foreach ($rekAliases as $rekAlias) {
                    if (strtolower(trim((string)$rekAlias)) == $rekNameNorm) {
                        $hutangHierarchyCurrent = $rekCode;
                        break 2;
                    }
                }
            }
        }

        if ($hutangHierarchyCurrent != "") {
            $showHutangHierarchy = true;
            $hutangHierarchyQuery = array(
                "o" => $cabangID,
            );
            if ($q != "") {
                $hutangHierarchyQuery["q"] = $q;
            }
            if ($getDate1 != NULL) {
                $hutangHierarchyQuery["date"] = $getDate1;
            }
            if ($getExternID != NULL) {
                $hutangHierarchyQuery["ext_id"] = $getExternID;
            }
            if ($getExtern2ID != NULL) {
                $hutangHierarchyQuery["ext2_id"] = $getExtern2ID;
            }
            if ($getMainExtern2ID != NULL) {
                $hutangHierarchyQuery["main_ext2_id"] = $getMainExtern2ID;
            }

            $supplierAgg = array();
            $cabangAgg = array();
            foreach ($hutangHierarchyAccounts as $rekCode => $rekMeta) {
                $routeRel = isset($rekMeta["route_rel"]) ? trim((string)$rekMeta["route_rel"]) : $relName;
                if ($routeRel == "") {
                    $routeRel = $relName;
                }
                $summaryRel = isset($rekMeta["summary_rel"]) ? trim((string)$rekMeta["summary_rel"]) : $routeRel;
                if ($summaryRel == "") {
                    $summaryRel = $routeRel;
                }
                $routeUrl = base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $routeRel . "/" . rawurlencode($rekCode) . "?" . http_build_query($hutangHierarchyQuery);
                $hutangHierarchyLinks[$rekCode] = array(
                    "label" => isset($rekMeta["label"]) ? $rekMeta["label"] : $rekCode,
                    "note" => isset($rekMeta["note"]) ? $rekMeta["note"] : "",
                    "url" => $routeUrl,
                    "saldo" => 0,
                    "count" => 0,
                    "cabang_count" => 0,
                );

                $summaryMdlName = "Com" . $summaryRel;
                $summaryMdlPath = APPPATH . "models/Coms/" . $summaryMdlName . ".php";
                if (!file_exists($summaryMdlPath)) {
                    continue;
                }
                $this->load->model("Coms/" . $summaryMdlName);
                if (!class_exists($summaryMdlName, false)) {
                    continue;
                }

                $this->db->reset_query();
                $comHierarchy = new $summaryMdlName();
                $comHierarchy->addFilter("cabang_id='$cabangID'");
                if ($getExtern2ID != NULL) {
                    $comHierarchy->addFilter("extern2_id='$getExtern2ID'");
                }
                if ($getExternID != NULL) {
                    $comHierarchy->addFilter("extern_id='$getExternID'");
                }

                if ($getDate1 != NULL) {
                    $selectedThn = (int)date("Y", strtotime($getDate1));
                    $selectedBln = (int)date("m", strtotime($getDate1));
                    $this->db->where(array(
                        "thn" => $selectedThn,
                        "bln<=" => $selectedBln,
                    ));
                    $tmp_hierarchy_0 = $comHierarchy->fetchBalancePeriode($rekCode, "", "bulanan");
                }
                else {
                    $tmp_hierarchy_0 = $comHierarchy->fetchBalances($rekCode);
                }

                $tmp_hierarchy = array();
                if (sizeof($tmp_hierarchy_0) > 0) {
                    foreach ($tmp_hierarchy_0 as $tmp_hierarchy_spec) {
                        $tmp_hierarchy[$tmp_hierarchy_spec->extern_id] = $tmp_hierarchy_spec;
                    }
                }

                $saldoPosisi = detectRekDefaultPosition($rekCode);
                if ($saldoPosisi != "debet" && $saldoPosisi != "kredit") {
                    $saldoPosisi = "kredit";
                }
                $saldoPosisiLawan = ($saldoPosisi == "debet") ? "kredit" : "debet";
                $saldoValue = 0;
                $supplierMap = array();
                $cabangMap = array();
                if (sizeof($tmp_hierarchy) > 0) {
                    foreach ($tmp_hierarchy as $tmp_hierarchy_spec) {
                        $entityId = isset($tmp_hierarchy_spec->extern_id) ? trim((string)$tmp_hierarchy_spec->extern_id) : "";
                        if ($entityId != "") {
                            $supplierMap[$entityId] = 1;
                        }
                        $cabangTmp = isset($tmp_hierarchy_spec->cabang_id) ? trim((string)$tmp_hierarchy_spec->cabang_id) : "";
                        if ($cabangTmp != "") {
                            $cabangMap[$cabangTmp] = 1;
                        }
                        $saldoMain = isset($tmp_hierarchy_spec->$saldoPosisi) ? (float)$tmp_hierarchy_spec->$saldoPosisi : 0;
                        $saldoLawan = isset($tmp_hierarchy_spec->$saldoPosisiLawan) ? (float)$tmp_hierarchy_spec->$saldoPosisiLawan : 0;
                        if ($saldoLawan > 0) {
                            $saldoMain = $saldoLawan * -1;
                        }
                        $saldoValue += $saldoMain;
                    }
                }

                foreach ($supplierMap as $supplierKey => $dummyVal) {
                    $supplierAgg[$supplierKey] = 1;
                }
                foreach ($cabangMap as $cabangKey => $dummyVal) {
                    $cabangAgg[$cabangKey] = 1;
                }

                $hutangHierarchyLinks[$rekCode]["saldo"] = $saldoValue;
                $hutangHierarchyLinks[$rekCode]["count"] = sizeof($supplierMap);
                $hutangHierarchyLinks[$rekCode]["cabang_count"] = sizeof($cabangMap);
                $hutangHierarchyTotals["all"] += $saldoValue;
            }

            $hutangHierarchyTotals["supplier_count"] = sizeof($supplierAgg);
            $hutangHierarchyTotals["cabang_count"] = sizeof($cabangAgg);
        }

        // base url tanpa query
        $baseUrl__ = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $url_serverside_tmp = strtok($baseUrl__, '?');
        $url_serverside_tmp = str_replace("viewBalances_l1", "viewBalances_l1_fetch", $url_serverside_tmp);

        // kumpulin query param
        $params = array(
            'server' => 1,
            'json'   => 1,
        );

        if ($getExternID !== NULL)      $params['ext_id'] = $getExternID;
        if ($getExtern2ID !== NULL)     $params['ext2_id'] = $getExtern2ID;
        if ($getMainExtern2ID !== NULL) $params['main_ext2_id'] = $getMainExtern2ID;
        if ($getDate1 !== NULL)         $params['date'] = $getDate1;

        // kalau mau ikutkan blob_ext juga (pakai versi ENCODE biar aman di URL)
        if ($blob_ext !== NULL)         $params['blob_ext'] = blobEncode($blob_ext);

        // hasil final
        $url_serverside = $url_serverside_tmp . '?' . http_build_query($params);

        $param_to_excel = array(
            "mdl" => isset($mdlData) && (strlen($mdlData) > 5) ? $mdlData : $mdlName,
            "fifo" => isset($mdlData) && (strlen($mdlData) > 5) ? "MdlFifoSupplies" : "MdlFifoProdukJadi",
            "cabang_id" => $cabangID,
        );

        $param_to_excel_e = str_replace("=", "", blobEncode($param_to_excel));

        $dateSelected = isset($cfg["periode"]) && $cfg["periode"] == "bulanan" ? true : false;
        $data = array(
            "title" => "$rekName_f",
            "server" => 1,
            "server_json" => 1,
            "url_serverside" => $url_serverside,
            "subTitle" => "$subTitle $blob_ext",
            "headerFields" => $headerFields,
            "thisPage" => $thisPage,
            "thisURL" => $thisURL,
            "param_to_excel" => $param_to_excel_e,
            "q" => $q,
            "items" => [],
            // menampilkan pilihan bulan
            "dateSelected" => $dateSelected,
            "defaultDate" => isset($_GET['date']) ? $_GET['date'] : dtimeNow('Y-m'),
            "showHutangHierarchy" => $showHutangHierarchy,
            "hutangHierarchyCurrent" => $hutangHierarchyCurrent,
            "hutangHierarchyLinks" => $hutangHierarchyLinks,
            "hutangHierarchyNote" => $hutangHierarchyNote,
            "hutangHierarchyEntityLabel" => $hutangHierarchyEntityLabel,
            "hutangHierarchyTotals" => $hutangHierarchyTotals,
        );
        $this->load->view("ledger/view_balances_l1", $data);
    }
    public function viewBalances_l1_fetch()
    {
        $relName = $this->uri->segment(3);
        $rekName = urldecode($this->uri->segment(4));
        $dtPost = $this->input->post(NULL, TRUE);
        $cabangID = $this->session->login["cabang_id"];

        $cfgAll = $this->config->item('accountBalanceProfiles');
        $cfg = $cfgAll[$relName];

        $accountSubChilds = ($this->config->item('accountSubChilds') != NULL) ? $this->config->item('accountSubChilds') : array();
        $accountSuperSubChilds = ($this->config->item('accountSuperSubChilds') != NULL) ? $this->config->item('accountSuperSubChilds') : array();
        $accountSuperSubChildsNonRekening = ($this->config->item('accountSuperSubChildsNonRekening') != NULL) ? $this->config->item('accountSuperSubChildsNonRekening') : array();

        $blob_ext = isset($_GET['blob_ext']) && strlen($_GET['blob_ext']) ? blobDecode($_GET['blob_ext']) : NULL;

        $getExternID = isset($_GET['ext_id']) && strlen($_GET['ext_id']) ? $_GET['ext_id'] : NULL;
        $getExtern2ID = isset($_GET['ext2_id']) && strlen($_GET['ext2_id']) ? $_GET['ext2_id'] : NULL;
        $getMainExtern2ID = isset($_GET['main_ext2_id']) && strlen($_GET['main_ext2_id']) ? $_GET['main_ext2_id'] : NULL;

        $com_sub_nonRekening = array();
        if (sizeof($accountSuperSubChildsNonRekening) > 0) {
            if (isset($accountSuperSubChildsNonRekening[$rekName])) {
                $mdl_sub = "Com" . $accountSuperSubChildsNonRekening[$rekName];
                $this->load->model("Coms/" . $mdl_sub);
                $com_sub = new $mdl_sub();
                $com_sub->addFilter("cabang_id='$cabangID'");
                $com_subTmp = $com_sub->fetchBalances($rekName);
                if (sizeof($com_subTmp) > 0) {
                    foreach ($com_subTmp as $com_subSpec) {
                        $com_sub_nonRekening[$com_subSpec->extern_id] = $com_subSpec->extern_nama;
                    }
                }
            }
        }


        $mdlName = "Com" . $relName;
        $this->load->model("Coms/" . $mdlName);
        $com = new $mdlName();
        $rekening = $rekName;

        $defPosition = detectRekDefaultPosition($rekName);
        $monthNow = dtimeNow('Y-m');
        $regDate = isset($_GET['date']) ? $_GET['date'] : dtimeNow('Y-m');

        $opts = array(
            'periode' => isset($cfg["periode"]) && $regDate != $monthNow ? $cfg['periode'] : "forever",
            'rekening' => $rekName,
            'cabang_id' => $cabangID*1,
            'gudang_id' => null,
            'show_qty' => isset($cfg['showQty']) ? $cfg['showQty'] : false,
            'show_rp' => isset($cfg['showValue']) ? $cfg['showValue'] : false,
            'modules' => array('sa'=>1, 'sp'=>1),
            'mode' => $defPosition,
            'tahun' => isset($cfg["periode"]) && $regDate != $monthNow ? date('Y', strtotime($regDate)) : null,
            'bulan' => isset($cfg["periode"]) && $regDate != $monthNow ? date('m', strtotime($regDate)) : null,
        );

        if($getExtern2ID!=NULL){
            $opts['extern2_id'] = $getExtern2ID;
        }

        if ($opts['cabang_id'] == -1) {
            $opts['gudang_id'] = -1; // utama pusat
        }
        else {
            $opts['gudang_id'] = $opts['cabang_id'] * -10; // utama cabang
        }

        $headerFields = $this->viewBalances_l1_header($relName, $rekName);
        $columns = array_keys($headerFields);

        $res = $com->datatable($cfg, $dtPost, $opts, $columns);
        $rows = $res['data'];

//        cekBiru("BEFORE");
//        cekMerah(json_encode($rows));

        foreach ($rows as &$row) {
            switch ($defPosition) {
                case 'debet':
                    if ($row['kredit'] > 0) {
                        $row['debet']  = $row["kredit"] * -1;
                        $row['kredit'] = 0;
                    }
                    if ($row['qty_kredit'] > 0) {
                        $row['qty_debet']  = $row["qty_kredit"] * -1;
                        $row['qty_kredit'] = 0;
                    }
                    break;
                case 'kredit':
                    if ($row['debet'] > 0) {
                        $row['kredit'] = $row["debet"] * -1;
                        $row['debet']  = 0;
                    }
                    if ($row['qty_debet'] > 0) {
                        $row['qty_kredit'] = $row["qty_debet"] * -1;
                        $row['qty_debet']  = 0;
                    }
                    break;
            }
        }

        unset($row);

//        cekBiru("AFTER");
//        cekMerah(json_encode($rows));

        $sTmpCount = array();
        $sTmpIntransit = array();
        $paired_result = array();
        $pairedSerial_add_json = array();
        if (!empty($rows)) {
            /*
             * jika login bukan dari pusat / bisa menampilkan semua cabang WO jika stok berada pada WO PROJECT (dengan modal)
             */
            if ($cabangID != CB_ID_PUSAT) {
                if (isset($cfg['additionalPairedWo']) && sizeof($cfg['additionalPairedWo']) > 0) {
                    $mdlTasklist = $cfg['additionalPairedWo']["mdlNameData"];
                    $mdlLookup = $cfg['additionalPairedWo']["mdlMethodData"];
                    $this->load->model("Mdls/$mdlTasklist");
                    $mt = New $mdlTasklist();
                    $mt->addFilter("cabang_id=" . my_cabang_id());
                    $mtTmp = $mt->$mdlLookup()->result();
                    if (sizeof($mtTmp) > 0) {
                        $arrGudangWo = array();
                        $arrGudangProject = array();
                        $arrGudangProjectWo = array();
                        foreach ($mtTmp as $mtSpec) {
                            $label_gudang = $mtSpec->nama;
                            $label_gudang .= "<br>" . $mtSpec->produk_nama;
                            $arrGudangWo[$mtSpec->gudang_wo] = $label_gudang;
                            $arrGudangProject[$mtSpec->produk_id] = $mtSpec->nama;
                            $arrGudangProjectWo[$mtSpec->gudang_wo] = $mtSpec->produk_id;
                        }
                    }

                    $com->setFilters(array());
                    $com->addFilter("cabang_id='$cabangID'");
                    $com->addFilter("gudang_id>'0'");
                    $com->addFilter("debet>'0'");

                    $pids = array();
                    foreach ($rows as $rw) {
                        if (!empty($rw['pId'])) $pids[] = (int)$rw['pId'];
                    }

                    $pids = array_values(array_unique($pids));

                    $this->db->where_in("extern_id", $pids);

                    $koloms = array(
                        "extern_id",
                        "rek_id",
                        "extern_nama",
                        "qty_debet",
                        "qty_kredit",
                        "debet",
                        "kredit",
                        "cabang_id",
                        "gudang_id",
                    );

                    if (method_exists($com, "fetchBalancesCustom")) {
                        $this->db->select($koloms);
                        $tmp2 = $com->fetchBalancesCustom($rekName);
                    }
                    else {
                        $tmp2 = $com->fetchBalances($rekName);
                    }

                    $pairGudangQuery = $this->db->last_query();


                    foreach ($tmp2 as $item) {
                        $item_qty_debet = $item->qty_debet;
                        $item_debet = $item->debet;
                        //-------------------------
                        if (!isset($stokProject[$item->extern_id])) {
                            $stokProject[$item->extern_id] = 0;
                        }
                        if (!isset($stokProjectValue[$item->extern_id])) {
                            $stokProjectValue[$item->extern_id] = 0;
                        }
                        $stokProject[$item->extern_id] += $item->qty_debet;
                        $stokProjectValue[$item->extern_id] += $item->debet;
                        $nama = $item->extern_nama;
                        $cid = $item->cabang_id;
                        $pid = $item->extern_id;
                        //------------
                        if($item->qty_debet*1 > 0){
                            $addBalanceWo[$item->extern_id]["qty_debet_" . $item->gudang_id] = $item->qty_debet*1;
                        }
                        if($item->debet*1>0){
                            $addBalanceWo[$item->extern_id]["debet_" . $item->gudang_id] = $item->debet*1;
                        }

                        $addBalanceWoLink[$item->extern_id]["qty_debet_" . $item->gudang_id] = $item->gudang_id*1;
                        $addBalanceWoLink[$item->extern_id]["debet_" . $item->gudang_id] = $item->gudang_id*1;
                        //------------
                        if (isset($arrGudangProjectWo[$item->gudang_id]) && !isset($addBalanceProject[$item->extern_id]["qty_debet_project_" . $arrGudangProjectWo[$item->gudang_id]])) {
                            $addBalanceProject[$item->extern_id]["qty_debet_project_" . $arrGudangProjectWo[$item->gudang_id]] = 0;
                        }
                        if (isset($arrGudangProjectWo[$item->gudang_id]) && !isset($addBalanceProject[$item->extern_id]["debet_project_" . $arrGudangProjectWo[$item->gudang_id]])) {
                            $addBalanceProject[$item->extern_id]["debet_project_" . $arrGudangProjectWo[$item->gudang_id]] = 0;
                        }

                        if(isset($arrGudangProjectWo[$item->gudang_id])){
                            $addBalanceProject[$item->extern_id]["qty_debet_project_" . $arrGudangProjectWo[$item->gudang_id]] += $item->qty_debet*1;
                        }

                        if(isset($arrGudangProjectWo[$item->gudang_id])){
                            $addBalanceProject[$item->extern_id]["debet_project_" . $arrGudangProjectWo[$item->gudang_id]] += $item->debet*1;
                        }

                        //------------
                        if ($item->qty_debet > 0) {
                            $label_gudang = isset($arrGudangWo[$item->gudang_id]) ? $arrGudangWo[$item->gudang_id] : $arrGudangWo[$item->gudang_id];
                            $label_project = isset($arrGudangProject[$arrGudangProjectWo[$item->gudang_id]]) ? $arrGudangProject[$arrGudangProjectWo[$item->gudang_id]] : "-";
                            $arrGudangWoAvail["qty_debet_" . $item->gudang_id] = array(
                                "label" => $label_gudang . "<br>(QTY)",
                                "bg-color" => "#8cb6fa",
                            );
                            $arrGudangWoAvail["debet_" . $item->gudang_id] = array(
                                "label" => $label_gudang . "<br>(RP)",
                                "bg-color" => "#8cb6fa",
                            );
                            $arrGudangProjectAvail["qty_debet_project_" . $arrGudangProjectWo[$item->gudang_id]] = array(
                                "label" => $label_project . "<br>(QTY)",
                                "bg-color" => "#8cb6fa",
                            );
                            $arrGudangProjectAvail["debet_project_" . $arrGudangProjectWo[$item->gudang_id]] = array(
                                "label" => $label_project . "<br>(RP)",
                                "bg-color" => "#8cb6fa",
                            );

                            $linkHistory = base_url() . "Ledger/viewWorkorder/?cabang_id=$cid&produk_id=$pid";
                            $historyClick = "BootstrapDialog.closeAll();
                                        BootstrapDialog.show({
                                            title:'Daftar Produk $nama di Gudang Project:',
                                            message: $('<div></div>').load('" . $linkHistory . "'),
                                            size: BootstrapDialog.SIZE_WIDE,
                                            draggable:true,
                                            closable:true,
                                        });";
                            $pairedGudang_add[$item->extern_id] = array(
                                "link_history" => $linkHistory,
                            );
                        }
                    }

                }
            }
            /*
             * untuk menangani model dengan pair serial seperti produk unit dengan serial
             */
            if( isset($cfg['pairedSerial']) && $cfg['pairedSerial'] == true ){
                $sTmpCount = $com->getSerialCountMap($rows, $rekName, $opts);
                $sTmpIntransit = $com->getSerialIntransitCountMap($rows, $rekName, $opts);
            }
            $gudangWo = [];
            $addCustomLink = [];
            foreach($rows as $ur => $aa){
                $row = (object) $aa;
                $pId    = $produk_id = $aa['pId'];
                $kode   = isset($aa['kode']) ? $aa['kode'] : "";
                $jenis  = isset($aa['jenis']) ? $aa['jenis'] : "";
                $nama   = $extern_nama = $aa['extern_nama'];

                /*
                 * untuk membuat link ke pembatu jika rekening memiliki pembantu ke lv2 lv3 dan selanjutnya
                 * Lv1
                 * cfg->accountChilds
                 * cfg->accountChildsItems
                 * Lv2
                 * cfg->accountSubChilds
                 * Lv3
                 * cfg->accountSuperSubChilds
                 *
                 * lv2 non akunting
                 * cfg->accountSuperSubChildsNonRekening
                 *
                 */
                // pembantu tingkat 1
                if (isset($accountSubChilds[$extern_nama])) {
                    $tmpRow['link'] = base_url() . "LedgerV2/viewBalances_l1/" . $accountSubChilds[$extern_nama] . "/" . $extern_nama."?line=".__LINE__."";
                    $tmpRow['link_main']['extern_nama'] = base_url() . "LedgerV2/viewMoveDetails/$relName/$rekName/" . $row->extern_id . "?line=".__LINE__."&o=$cabangID";
                }
                // pembantu tingkat 3
                elseif (isset($accountSuperSubChilds[$extern_nama])) {
                    $text_pair = $blob_ext . " " . $extern_nama;
                    $text = blobEncode($text_pair);
                    $tmpRow['link'] = base_url() . "LedgerV2/viewBalances_l1/" . $accountSuperSubChilds[$extern_nama] . "/" . $extern_nama . "?line=".__LINE__."&ext2_id=" . $getMainExtern2ID . "&blob_ext=$text";
                    $tmpRow['link_main']['extern_nama'] = base_url() . "LedgerV2/viewMoveDetails/$relName/$rekName/" . $row->extern_id . "?line=".__LINE__."&o=$cabangID" . "&ext2_id=" . $getMainExtern2ID . "&blob_ext=$text";
                }
                // pembantu tingkat 2
                elseif (isset($accountSubChilds[$rekening])) {
                    $subExternID = $getExtern2ID;
                    $text_pair = $blob_ext . " " . $extern_nama;
                    $text = blobEncode($text_pair);
                    if ($subExternID > 0) {
                        $tmpRow['link'] = base_url() . "LedgerV2/viewMoveDetails/$relName/$rekName/" . $row->extern_id . "/$subExternID?line=".__LINE__."&o=$cabangID" . "&main_ext2_id=" . $row->extern_id . "&blob_ext=$text";
                    }
                    else {
                        $tmpRow['link'] = base_url() . "LedgerV2/viewBalances_l1/" . $accountSubChilds[$rekening] . "/" . $rekening . "?line=".__LINE__."&ext2_id=" . $row->extern_id . "&main_ext2_id=" . $row->extern_id . "&blob_ext=$text";
                    }
                    $tmpRow['link_main']['extern_nama'] = base_url() . "LedgerV2/viewMoveDetails/$relName/$rekName/" . $row->extern_id . "/$subExternID?line=".__LINE__."&o=$cabangID" . "&main_ext2_id=" . $row->extern_id . "&blob_ext=$text";
                }
                elseif (isset($row->extern_id) && array_key_exists($row->extern_id, $com_sub_nonRekening)) {
                    if ($getExternID != NULL) {
                        $text_pair = $blob_ext . " " . $extern_nama;
                        $text = blobEncode($text_pair);
                        $tmpRow['link'] = base_url() . "LedgerV2/viewMoveDetails/" . $accountSuperSubChildsNonRekening[$rekening] . "/" . $rekening . "/" . $row->extern_id . "?line=".__LINE__."&ext2_id=" . $row->extern2_id . "&blob_ext=$text";
                        $tmpRow['link_main']['extern_nama'] = NULL;
                    }
                    else {
                        $text_pair = $blob_ext . " " . $extern_nama;
                        $text = blobEncode($text_pair);
                        $tmpRow['link'] = base_url() . "LedgerV2/viewBalances_l1/" . $accountSuperSubChildsNonRekening[$rekening] . "/" . $rekening . "?line=".__LINE__."&ext_id=" . $row->extern_id . "&blob_ext=$text";
                        $tmpRow['link_main']['extern_nama'] = base_url() . "LedgerV2/viewMoveDetails/$relName/$rekName/" . $row->extern_id . "?line=".__LINE__."&o=$cabangID" . "&blob_ext=$text";
                    }
                }
                // tidak ada pembantu
                else {
                    $text_pair = $blob_ext . " " . $extern_nama;
                    $text = blobEncode($text_pair);
                    if ($rekening != "laba ditahan") {
                        //baca config custom link dari configaccounting
                        $tmpRow['link'] = base_url() . "LedgerV2/viewMoveDetails/$relName/$rekName/" . $row->extern_id . "?line=".__LINE__."&o=$cabangID" . "&ext2_id=" . $getExtern2ID . "&main_ext2_id=" . $getExtern2ID . "&blob_ext=$text";
                        $tmpRow['link_main']['extern_nama'] = NULL;
                    }
                    else {
                        $tmpRow['link'] = "#";
                        $tmpRow['link_main']['extern_nama'] = NULL;
                    }
                }

                $paired_result[$pId]['link_bootstrap'] = $tmpRow;

                if (isset($arrGudangWoAvail) && (sizeof($arrGudangWoAvail) > 0)) {
                    foreach ($arrGudangWoAvail as $keywo => $valwo) {
                        if(isset($addBalanceWo[$produk_id][$keywo]) && $addBalanceWo[$produk_id][$keywo]*1 > 0){
                            $tmpRow1[$keywo] = $addBalanceWo[$produk_id][$keywo];
                        }
                        if( isset($addBalanceWoLink[$produk_id][$keywo]) && $addBalanceWoLink[$produk_id][$keywo]*1 > 0 ){
                            $addCustomLink[$produk_id]["customLink"][$keywo] = $addBalanceWoLink[$produk_id][$keywo];
                        }
                    }
                }
                if (isset($arrGudangProjectAvail) && (sizeof($arrGudangProjectAvail) > 0)) {
                    foreach ($arrGudangProjectAvail as $keypj => $valpj) {
                        if(isset($addBalanceProject[$produk_id][$keypj]) &&  $addBalanceProject[$produk_id][$keypj]*1 > 0){
                            $tmpRow1[$keypj] = $addBalanceProject[$produk_id][$keypj];
                        }
                    }
                }

                if (isset($stokProject[$produk_id])) {
                    $tmpRow1["ng_qty_debet"] = $stokProject[$produk_id]*1;
                    $tmpRow1["ng_debet"] = $stokProjectValue[$produk_id]*1;
                }
                $gudangWo[$produk_id] = $tmpRow1;
//                $res['data'][$ur] = $tmpRow;

                if(isset($cfg['pairedModel'])){
                    $linkHistory = NULL;
                    if (isset($cfg['pairedModel']['jenisItems'])) {
                        $ctrlName_history = isset($cfg['pairedModel']['jenisItems'][$jenis]) ? $cfg['pairedModel']['jenisItems'][$jenis] : "";
                        $linkHistory = base_url() . "Data/viewHistories/$ctrlName_history/" . $produk_id;
                    }
                    $paired_result[$pId]['link_bootstrap']['title'] = "change histories $kode $nama";
                    $paired_result[$pId]['link_bootstrap']['link_history'] = $linkHistory;
                }

                if(!empty($sTmpCount)){
                    foreach($sTmpCount as $ky_keys => $cData){
                        if(!isset($cData[$produk_id])) continue;
                        $cabangID = $cData[$produk_id]['cabang_id'];
                        $gudang_id = $cData[$produk_id]['gudang_id'];
                        $linkHistory_serial = base_url() . "LedgerV2/viewSerial/?produk_id=" . $pId . "&cabang_id=$cabangID&gudang_id=$gudang_id";
                        $linkHistory_transit = base_url() . "LedgerV2/viewSerialTransit/?produk_id=" . $produk_id . "&cabang_id=$cabangID&gudang_id=$gudang_id";
                        $linkHistory_qr = base_url() . "addons/Qr/viewSerial/?produk_id=" . $produk_id . "&cabang_id=$cabangID&gudang_id=$gudang_id";
                        $linkHistory_barcode = base_url() . "addons/BarcodePrinter/viewSerial/?produk_id=" . $produk_id . "&cabang_id=$cabangID&gudang_id=$gudang_id";
                        $link_qr = "top.popBig('$linkHistory_qr')";
                        $link_barcode = "top.popBig('$linkHistory_barcode')";
                        $pairedSerial_add_json[$produk_id][$ky_keys] = array(
                            "jml_serial_transit" => isset($sTmpIntransit[$ky_keys][$produk_id]) ? $sTmpIntransit[$ky_keys][$produk_id]['jml_serial_transit'] : 0,
                            "link_qr_transit" => $linkHistory_transit,
                            "jml_serial" => isset($sTmpCount[$ky_keys][$produk_id]) ? $sTmpCount[$ky_keys][$produk_id]['jml_serial'] : 0,
                            "link_barcode" => $linkHistory_barcode,
                            "link_qr" => $linkHistory_qr,
                            "link_serial" => $linkHistory_serial,
                            "link_serial_label" => "Detail " . $nama,
                            "link_qr_label" => "Detail " . $nama,
                            "link_barcode_label" => "Detail " . $nama,
                        );
                    }
                }
                if(!empty($sTmpIntransit)){
                    foreach($sTmpIntransit as $ky_keys => $cData){
                        if(!isset($cData[$produk_id])) continue;
                        $cabangID = $cData[$produk_id]['cabang_id'];
                        $gudang_id = $cData[$produk_id]['gudang_id'];
                        $linkHistory_serial = base_url() . "LedgerV2/viewSerial/?produk_id=" . $pId . "&cabang_id=$cabangID&gudang_id=$gudang_id";
                        $linkHistory_transit = base_url() . "LedgerV2/viewSerialTransit/?produk_id=" . $produk_id . "&cabang_id=$cabangID&gudang_id=$gudang_id";
                        $linkHistory_qr = base_url() . "addons/Qr/viewSerial/?produk_id=" . $produk_id . "&cabang_id=$cabangID&gudang_id=$gudang_id";
                        $linkHistory_barcode = base_url() . "addons/BarcodePrinter/viewSerial/?produk_id=" . $produk_id . "&cabang_id=$cabangID&gudang_id=$gudang_id";
                        $link_qr = "top.popBig('$linkHistory_qr')";
                        $link_barcode = "top.popBig('$linkHistory_barcode')";
                        $pairedSerial_add_json[$produk_id][$ky_keys] = array(
                            "jml_serial_transit" => isset($sTmpIntransit[$ky_keys][$produk_id]) ? $sTmpIntransit[$ky_keys][$produk_id]['jml_serial_transit'] : 0,
                            "link_qr_transit" => $linkHistory_transit,
                            "jml_serial" => isset($sTmpCount[$ky_keys][$produk_id]) ? $sTmpCount[$ky_keys][$produk_id]['jml_serial'] : 0,
                            "link_barcode" => $linkHistory_barcode,
                            "link_qr" => $linkHistory_qr,
                            "link_serial" => $linkHistory_serial,
                            "link_serial_label" => "Detail " . $nama,
                            "link_qr_label" => "Detail " . $nama,
                            "link_barcode_label" => "Detail " . $nama,
                        );
                    }
                }
            }
        }

        $returnData = array(
            'draw' => (int)$this->input->post('draw'),
            'recordsTotal' => $res['recordsTotal'],
            'recordsFiltered' => $res['recordsFiltered'],
            'data' => $rows,
            'query' => $res['last_query'],
            'serial' => $pairedSerial_add_json,
            'paired_result' => $paired_result,
            'columns' => $columns,
            'defPosition' => $defPosition,
            'pairedGudang_add' => isset($pairedGudang_add) ? $pairedGudang_add : [],
            'gudangWo' => isset($gudangWo) ? $gudangWo : [],
            'addCustomLink' => isset($addCustomLink) ? $addCustomLink : [],
            'pairGudangQuery' => $pairGudangQuery,
            'footerSummaryPage' => $footerSummaryPage,
            'opts' => $opts,
        );

        $returnData['produk_disabled'] = array();

        echo json_encode($returnData);
    }


    //untuk view master serialloadMoveDetails
    public function viewserial_old()
    {

        $this->load->library("Ciqrcode");
        $qr = new Ciqrcode();
        $fields = array(
            "dtime" => "tgl masuk",
            "extern2_nama" => "sku",
            "extern_nama" => "serial",
            "qty_debet" => "jumlah",
            "qr" => "QR",
        );

        $produk_id = $_GET["produk_id"];
        $cabang_id = $_GET["cabang_id"];
        $gudang_id = $_GET["gudang_id"];

        $this->load->model("Coms/ComRekeningPembantuProdukPerSerial");
        $tr = new ComRekeningPembantuProdukPerSerial();

        $tr->setFilters(array());
        $tr->addFilter("produk_id='$produk_id'");
        $tr->addFilter("cabang_id='$cabang_id'");
        $tr->addFilter("gudang_id='$gudang_id'");
        $tr->addFilter("qty_debet>0");
        $this->db->order_by("dtime", "asc");
        $items = $tr->fetchBalances("1010030030");

        $datas = "
            <style>
                .highlighted {
                    background-color: yellow;
                }
            </style>
        ";
        $datas .= "<table class='table dataTable compact table-bordered table hover'>";
        $datas .= "<caption class='text-red text-center fa-2x'>double click pada serial untuk copy dan menandai<div>SHOW HIDE QRCODE <button id='toggle' class='btn btn-sm btn-warning'><i class='fa fa-eye'></i></button></div></caption>";
        $datas .= "<thead>";
        $datas .= "<tr>";
        $datas .= "<th>No</th>";
        foreach ($fields as $kk => $label) {
            $datas .= "<th>$label</th>";
        }
        $datas .= "</tr>";
        $datas .= "</thead>";
        $datas .= "<tbody>";

        $jsonOBJ = [];

        if (count($items) > 0) {
            $ix = 0;
            foreach ($items as $ii => $items_0) {
                if (!isset($totalSku[$items_0->extern2_nama])) {
                    $totalSku[$items_0->extern2_nama] = 0;
                }
                $totalSku[$items_0->extern2_nama] += $items_0->qty_debet;
                $ix++;
                $datas .= "<tr>";

                $jsonTmp = array();
                $jsonTmp["no"] = $ix;
                $datas .= "<td>$ix</td>";
                $serial = $items_0->extern_nama;
                foreach ($fields as $k => $alias) {
                    $jsonTmp[$k] = isset($items_0->$k) ? $items_0->$k : "";
                    $datas .= "<td>";
                    if ($k == "qr") {
                        $qrcode = $qr->get_qrcode_pembelian($serial);
                        $qrfile = base_url() . $qrcode['file'];
                        $listed = "<div class='qrcode'>";
                        $listed .= "<img src='$qrfile' title='$ii' class='img-thumbnail' style='width: 85px'>";
                        $listed .= "</div>";
                        $value = "$listed";
                    }
                    elseif ($k == "extern_nama") {
                        $listed = "<span>" . $items_0->$k . "</span>";
                        $value = $listed;
                    }
                    else {
                        $value = isset($items_0->$k) ? $items_0->$k : "";
                    }
                    $datas .= $value;
                    $datas .= "</td>";
                }
                $datas .= "</tr>";
                $jsonOBJ[] = $jsonTmp;
            }
        }

        $datas .= "</tbody>";
        $datas .= "</table>";


        $jsButton = "

            <script>

                var jsonTmp = '" . json_encode($jsonOBJ) . "';
                var dataJson = JSON.parse(jsonTmp);

                //console.log(dataJson);

                $('span').on('dblclick', function (e) {
                    e.preventDefault();
                    $(this).addClass('highlighted');
                    const selection = window.getSelection();
                    selection.removeAllRanges();
                    const range = document.createRange();
                    range.selectNodeContents(this);
                    selection.addRange(range);
                    const temp = $('<textarea>');
                    $('body').append(temp);
                    temp.val($(this).text()).select();
                    document.execCommand('copy');
                    temp.remove();
                });

                $('#toggle').on('click', function () {
                    $('.qrcode').slideToggle(200);
                    var icon = $(this).find('i');
                    if (icon.hasClass('fa-eye')) {
                        icon.removeClass('fa-eye').addClass('fa-eye-slash');
                    }
                    else {
                        icon.removeClass('fa-eye-slash').addClass('fa-eye');
                    }
                });
                
                $('.qrcode').slideToggle(200);

                document.getElementById('copy_serial').addEventListener('click', function () {
                    const jumlah = parseInt(document.getElementById('jumlah_serial').value, 10);
                    const tipe = document.getElementById('jenis_serial').value;
                    if (isNaN(jumlah) || jumlah <= 0) {
                        alert(\"Jumlah serial tidak valid.\");
                        return;
                    }
                    const spans = document.querySelectorAll('td > span');
                    const hasil = [];
                    for (let i = 0; i < spans.length && hasil.length < jumlah; i++) {
                        const span = spans[i];
                        const teks = span.textContent.trim();
                        if (span.dataset.used === \"1\") continue;
                                        if(tipe!='ANY'){
                        if (!teks.endsWith(`:`+tipe+``)) continue;
                                        }
                        hasil.push(teks);
                        span.dataset.used = \"1\";
                        span.style.backgroundColor = \"yellow\"; // highlight kuning
                    }
                    if (hasil.length === 0) {
                                        alert('Tidak ada serial baru dengan tipe ' + tipe);
                        return;
                    }
                    const textToCopy = hasil.join(',');
                    navigator.clipboard.writeText(textToCopy)
                    .then(() => {
                        alert('Berhasil disalin ke clipboard.');
                    })
                    .catch(() => {
                        alert('Gagal menyalin.');
                    });
                });


            </script>

        ";
        $headerTitle = "<div>";
        foreach ($totalSku as $sku => $jml) {
            $headerTitle .= "<div><span>$sku</span> : <span>$jml</span></div>";
        }
        $headerTitle .= "</div>";
        $headerTitle .= "
            <input type='number' id='jumlah_serial' placeholder='Jumlah serial'>
            <select id='jenis_serial'>
            <option value='IN'>IN</option>
            <option value='OT'>OT</option>
            <option value='PART'>PART</option>
            <option value='ANY'>ANY</option>
            </select>
            <button id='copy_serial'>Copy Serial</button>
        ";

        echo $headerTitle;
        echo $jsButton;
        echo $datas;

    }
    public function viewserial()
    {
        $data = array(
            'produk_id' => $this->input->get('produk_id', true),
            'cabang_id' => $this->input->get('cabang_id', true),
            'gudang_id' => $this->input->get('gudang_id', true),
            'ajax_url'  => base_url() . 'ledgerV2/viewserial_fetch' // sesuaikan controller path kamu
        );

        $this->load->view('ledger/viewserial', $data);
    }
    public function viewserial_fetch()
    {
        $this->load->library("Ciqrcode");
        $qr = new Ciqrcode();

        $produk_id = $this->input->get('produk_id', true);
        $cabang_id = $this->input->get('cabang_id', true);
        $gudang_id = $this->input->get('gudang_id', true);

        // DataTables params
        $draw   = (int)$this->input->get('draw');
        $start  = (int)$this->input->get('start');
        $length = (int)$this->input->get('length');
        if ($length <= 0) $length = 10;

        $searchValue = $this->input->get('search');
        $q = '';
        if (is_array($searchValue) && isset($searchValue['value'])) {
            $q = trim($searchValue['value']);
        }

        // kolom table DataTables (urutan harus sama dengan yang di view)
        $columns = array(
            'no',           // 0 (dummy)
            'dtime',        // 1
            'extern2_nama', // 2
            'extern_nama',  // 3
            'qty_debet',    // 4
            'qr'            // 5 (dummy)
        );

        $orderColIdx = 1;
        $orderDir = 'asc';
        $order = $this->input->get('order');
        if (is_array($order) && isset($order[0]['column'])) {
            $orderColIdx = (int)$order[0]['column'];
            $orderDir = (isset($order[0]['dir']) && strtolower($order[0]['dir']) === 'desc') ? 'desc' : 'asc';
        }
        $orderField = isset($columns[$orderColIdx]) ? $columns[$orderColIdx] : 'dtime';

        // Ambil data (sementara full fetch)
        $this->load->model("Coms/ComRekeningPembantuProdukPerSerial");
        $tr = new ComRekeningPembantuProdukPerSerial();

        $tr->setFilters(array());
        $tr->addFilter("produk_id='$produk_id'");
        $tr->addFilter("cabang_id='$cabang_id'");
        $tr->addFilter("gudang_id='$gudang_id'");
        $tr->addFilter("qty_debet>0");

        // Jangan pakai $this->db->order_by dulu untuk versi PHP-sorting
        $itemsAll = $tr->fetchBalances("1010030030"); // array of object

        $recordsTotal = is_array($itemsAll) ? count($itemsAll) : 0;

        // Filter search (simple: cari di sku/serial/tgl)
        $filtered = array();
        if ($q !== '') {
            $qLower = strtolower($q);
            foreach ($itemsAll as $row) {
                $hay = strtolower(
                    (string)$row->extern2_nama . ' ' .
                    (string)$row->extern_nama . ' ' .
                    (string)$row->dtime
                );
                if (strpos($hay, $qLower) !== false) {
                    $filtered[] = $row;
                }
            }
        } else {
            $filtered = $itemsAll;
        }

        $recordsFiltered = count($filtered);

        // Hitung total per SKU (berdasarkan hasil filter biar konsisten dengan table)
        $totalSku = array();
        foreach ($filtered as $r) {
            $sku = isset($r->extern2_nama) ? $r->extern2_nama : '';
            if (!isset($totalSku[$sku])) $totalSku[$sku] = 0;
            $totalSku[$sku] += (float)$r->qty_debet;
        }

        // Sorting
        usort($filtered, function($a, $b) use ($orderField, $orderDir) {
            $va = isset($a->$orderField) ? $a->$orderField : '';
            $vb = isset($b->$orderField) ? $b->$orderField : '';

            // coba numerik untuk qty
            if ($orderField === 'qty_debet') {
                $va = (float)$va; $vb = (float)$vb;
                $cmp = ($va < $vb) ? -1 : (($va > $vb) ? 1 : 0);
            } else {
                $va = (string)$va; $vb = (string)$vb;
                $cmp = strcmp($va, $vb);
            }

            return ($orderDir === 'desc') ? -$cmp : $cmp;
        });

        // Paging
        $pageRows = array_slice($filtered, $start, $length);

        // Build rows untuk DataTables
        $data = array();
        $no = $start;
        foreach ($pageRows as $row) {
            $no++;

            $serial = isset($row->extern_nama) ? $row->extern_nama : '';

            $qrHtml = '';
            if ($serial !== '') {
                $qrcode = $qr->get_qrcode_pembelian($serial);
                $qrfile = base_url() . $qrcode['file'];
                $qrHtml = "<div class='qrcode'><img src='{$qrfile}' class='img-thumbnail' style='width:85px'></div>";
            }

            $data[] = array(
                'no'           => $no,
                'dtime'        => isset($row->dtime) ? $row->dtime : '',
                'extern2_nama' => isset($row->extern2_nama) ? $row->extern2_nama : '',
                // span tetap dipakai biar fitur dblclick-copy jalan
                'extern_nama'  => "<span class='serial-span'>".(isset($row->extern_nama) ? $row->extern_nama : '')."</span>",
                'qty_debet'    => isset($row->qty_debet) ? $row->qty_debet : '',
                'qr'           => $qrHtml
            );
        }

        // HTML header total SKU + tool copy serial (dipakai di client)
        $headerTitle = "<div>";
        foreach ($totalSku as $sku => $jml) {
            if ($sku === '') continue;
            $headerTitle .= "<div><span>{$sku}</span> : <span>{$jml}</span></div>";
        }
        $headerTitle .= "</div>";
        $headerTitle .= "
        <input type='number' id='jumlah_serial' placeholder='Jumlah serial'>
        <select id='jenis_serial'>
            <option value='IN'>IN</option>
            <option value='OT'>OT</option>
            <option value='PART'>PART</option>
            <option value='ANY'>ANY</option>
        </select>
        <button id='copy_serial'>Copy Serial</button>
        <div style='margin-top:6px'>SHOW HIDE QRCODE <button id='toggle' class='btn btn-sm btn-warning'><i class='fa fa-eye'></i></button></div>
    ";

        $out = array(
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
            'extra' => array(
                'headerHtml' => $headerTitle
            )
        );

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($out));
    }
    public function viewSerialTransit()
    {
        //        arrPrint($_GET);
        $this->load->model("MdlTransaksi");
        $this->load->library("Ciqrcode");
        $t = new MdlTransaksi();
        $qr = new Ciqrcode();
        $fields = array(
            "dtime" => "tgl transit",
            "oleh_nama" => "oleh",
            "nomer" => "nomer",
            "extern2_nama" => "sku",
            "extern_nama" => "serial",
            "qty_debet" => "jumlah",
            //             "barcode"=>"barcode",
            "qr" => "QR",
        );

        $produk_id = $_GET["produk_id"];
        $cabang_id = $_GET["cabang_id"];
        $gudang_id = $_GET["gudang_id"];
        $this->load->model("Coms/ComRekeningPembantuProdukPerSerialIntransit");
        $this->load->model("Mdls/MdlProduk2");
        $tr = new ComRekeningPembantuProdukPerSerialIntransit();

        $tr->setFilters(array());
        $tr->addFilter("produk_id='$produk_id'");
        $tr->addFilter("cabang_id='$cabang_id'");
        $tr->addFilter("gudang_id='$gudang_id'");
        $tr->addFilter("qty_debet>0");
        $this->db->order_by("dtime", "asc");
        $items = $tr->fetchBalances("1010030030");
        $datas = "<table class='table table-bordered table hover'>";
        $datas .= "<tr>";
        $datas .= "<th>No</th>";
        foreach ($fields as $kk => $label) {
            $datas .= "<th>$label</th>";
        }
        $datas .= "</tr>";

        if (count($items) > 0) {
            $ix = 0;
            foreach ($items as $ii => $items_0) {
                $transaksi_id = $items_0->transaksi_id;
                $t->setFilters(array());
                $t->addfilter("id='$transaksi_id'");
                $tmp = $t->lookupMainTransaksi()->result();
                $items_0->dtime = formatField_he_format("dtime", $tmp[0]->dtime);
                $items_0->nomer = formatField_he_format("nomer", $tmp[0]->nomer);
                $items_0->oleh_nama = $tmp[0]->oleh_nama;
                //                arrPrint($tmp);
                //                matiHere();
                if (!isset($totalSku[$items_0->extern2_nama])) {
                    $totalSku[$items_0->extern2_nama] = 0;
                }
                $totalSku[$items_0->extern2_nama] += $items_0->qty_debet;
                $ix++;
                $datas .= "<tr>";
                $datas .= "<td>$ix</td>";
                $serial = $items_0->extern_nama;
                foreach ($fields as $k => $alias) {
                    $datas .= "<td>";

                    if ($k == "qr") {
                        $qrcode = $qr->get_qrcode_pembelian($serial);
                        $qrfile = base_url() . $qrcode['file'];
                        $listed = "<div class='bottom-borders' style='hheight: 30px;'>";
                        $listed .= "<div class='text-center no-padding' style='margin-bottom: 0px;'>";
                        $listed .= "</div>";
                        $listed .= "</div>";

                        $listed .= "<img src='$qrfile' title='$ii' class='img-thumbnail' style='width: 85px'>";
                        $listed .= "</div>";
                        $value = "$listed";
                    }
                    else {
                        $value = isset($items_0->$k) ? $items_0->$k : "";
                    }
                    $datas .= "$value";
                    $datas .= "</td>";
                }
                $datas .= "</tr>";
            }
        }

        //        arrPrint($totalSku);
        $datas .= "</table>";
        $headerTitle = "<div>";
        foreach ($totalSku as $sku => $jml) {
            $headerTitle .= "<div><span>$sku</span> : <span>$jml</span></div>";
        }
        $headerTitle .= "</div>";
        echo $headerTitle;
        echo $datas;

    }

    /*
     * ################################## viewBalances_periode_2 ##################################
     */

    public function viewBalances_periode_2()
    {
        $relName = $this->uri->segment(3);
        $rekName = urldecode($this->uri->segment(4));

        $dateSelect = isset($_GET['date']) && $_GET['date'] ? $_GET['date'] : date("Y-m-d");

        // header tabel final (sesuai yang kamu override di bawah)
        $headerFields = array(
            "pid"            => "pID",
            "kategori_nama"  => "kategori",
            "kode"           => "sku",
            "barcode"        => "barcode",
            "extern_nama"    => "nama produk",
            "qty_debet_akhir"=> "kuantiti (QTY)",
            "debet_akhir"    => "Nilai (IDR)",
        );

        $data = array(
            "mode"        => "saldo_periode",
            "title"       => "Persediaan per " . lgTranslateTime4($dateSelect),
            "subTitle"    => "Posisi persediaan <r>per ".lgTranslateTime4($dateSelect)."</r> gudang reguler di ".my_cabang_nama()." <small>projek belum termasuk</small>",
            "headerFields"=> $headerFields,

            // untuk view
            "defaultDate" => $dateSelect,
            "maxDate"     => dtimeNow('Y-m-d'),
            "oldDate"     => "2024-01-01",

            // url ajax datatable
            "dtAjaxUrl"   => base_url()."LedgerV2/viewBalances_periode_2_dt/".$relName."/".urlencode($rekName),
        );

        $this->load->view("ledger/viewBalances_periode_2", $data);
    }
    public function viewBalances_periode_2_dt($relName = null, $rekName = null)
    {
        $tbl1 = "__rek_pembantu_produk__1010030030";
        $tbl2 = "produk";

        $cabang_id = my_cabang_id();
        $gudang_id = my_gudang_id();

        // tanggal dari form
        $dtime = $this->input->get('date');
        if (!$dtime) $dtime = date('Y-m-d');

        $kategori = $this->input->get('kategori');
        if (!$kategori) $kategori = '__all__';

        // DataTables params
        $draw   = (int)$this->input->get('draw');
        $start  = (int)$this->input->get('start');
        $length = (int)$this->input->get('length');

        $search = $this->input->get('search');
        $q = (is_array($search) && isset($search['value'])) ? trim($search['value']) : '';

        $order  = $this->input->get('order');
        $orderColRaw = isset($order[0]['column']) ? (int)$order[0]['column'] : 0;
        $orderDir = isset($order[0]['dir']) ? strtolower($order[0]['dir']) : 'asc';
        $orderDir = ($orderDir === 'desc') ? 'desc' : 'asc';

        /**
         * PENTING:
         * View punya kolom tambahan "No." di index 0.
         * Data kolom sebenarnya mulai dari index 1.
         */
        $orderCol = $orderColRaw - 1;

        // whitelist kolom data (TANPA kolom No.)
        // index ini harus sama urutan columns[] di view (tanpa No.)
        $colMap = array(
            0 => "p.id",                   // pid
            1 => "p.kategori_nama",                 // kategori_nama (kalau join kategori)
            2 => "p.kode",                 // kode/sku
            3 => "p.barcode",
            4 => "p.nama",                 // extern_nama
            5 => "last.qty_debet_akhir",
            6 => "last.debet_akhir",
        );

        // fallback kalau user klik kolom No. atau index invalid
        if (!isset($colMap[$orderCol])) {
            $orderCol = 4; // default: nama produk
        }
        $orderBy = $colMap[$orderCol];

        // -------------------------
        // Subquery latest per produk
        // -------------------------
        $subMax = "
        SELECT extern_id, MAX(id) AS max_id
        FROM {$tbl1}
        WHERE cabang_id = ?
          AND gudang_id = ?
          AND DATE(dtime) <= ?
        GROUP BY extern_id
    ";

        $subLast = "
        SELECT t1.*
        FROM {$tbl1} t1
        INNER JOIN ({$subMax}) x ON x.max_id = t1.id
    ";

    // ====== build WHERE + bind ======
    $where = " WHERE p.status = 1 AND p.trash = 0 ";
    $bindWhere = array();

    // filter kategori dari dropdown (EXACT MATCH)
    if ($kategori !== '__all__') {
        if ($kategori === '__empty__') {
            // kategori kosong / null
            $where .= " AND (p.kategori_nama IS NULL OR p.kategori_nama = '') ";
        } else {
            $where .= " AND p.kategori_nama = ? ";
            $bindWhere[] = $kategori;
        }
    }

    // global search
        if ($q !== '') {
        $where .= " AND (
                    p.nama LIKE ? OR p.barcode LIKE ? OR p.kode LIKE ? OR CAST(p.id AS CHAR) LIKE ?
            OR p.kategori_nama LIKE ?
                )";
                $like = "%".$q."%";
        $bindWhere = array_merge($bindWhere, array($like,$like,$like,$like,$like));
        }

    // base FROM (INNER JOIN supaya hasil seperti “lama”: hanya yang punya saldo)
        $baseFrom = "
        FROM {$tbl2} p
        INNER JOIN ({$subLast}) last ON last.extern_id = p.id
        {$where}
    ";

    // base bind: untuk subquery + where
    $bindBase = array_merge(array($cabang_id, $gudang_id, $dtime), $bindWhere);

    // ====== recordsTotal (tanpa search & tanpa filter kategori) ======
    // kalau kamu mau recordsTotal ikut kategori dropdown, pindahkan filter kategori ke sini juga.
        $sqlTotal = "
        SELECT COUNT(*) AS cnt
        FROM {$tbl2} p
        INNER JOIN ({$subLast}) last ON last.extern_id = p.id
        WHERE p.status = 1 AND p.trash = 0
    ";
        $recordsTotal = (int)$this->db->query($sqlTotal, array($cabang_id, $gudang_id, $dtime))->row()->cnt;

    // ====== recordsFiltered (dengan search + kategori) ======
        $sqlFiltered = "SELECT COUNT(*) AS cnt {$baseFrom}";
    $recordsFiltered = (int)$this->db->query($sqlFiltered, $bindBase)->row()->cnt;

    // ====== handle length = -1 (ALL) + GUARD ======
        $DEFAULT_LEN = 25;
        $MAX_ALL = 5000;
        $guardMessage = null;

        if ($length === -1) {
        // Guard A: ALL tanpa keyword minimal 2 chars => batasi
            if ($q === '' || strlen($q) < 2) {
                $length = 1000;
                $start  = 0;
                $guardMessage = "Mode ALL dibatasi. Isi keyword minimal 2 karakter untuk mengambil lebih banyak data.";
            }

        // Guard B: kalau hasil terlalu besar => cap
            if ($recordsFiltered > $MAX_ALL) {
                $length = $MAX_ALL;
                $start  = 0;
                $guardMessage = "Mode ALL dibatasi maksimal {$MAX_ALL} baris (hasil pencarian terlalu besar).";
            }
        }

        if ($length === 0) $length = $DEFAULT_LEN;
        if ($length < -1)  $length = $DEFAULT_LEN;
        if ($start < 0)    $start  = 0;

        $limitSql = "";
        $bindLimit = array();
        if ($length !== -1) {
            $limitSql = " LIMIT ? OFFSET ? ";
            $bindLimit = array($length, $start);
        }

    // ====== data query ======
        $sqlData = "
        SELECT
            p.id AS pid,
            p.kategori_nama,
            p.kode,
            p.barcode,
            p.nama AS extern_nama,
            COALESCE(last.qty_debet_akhir,0) AS qty_debet_akhir,
            COALESCE(last.debet_akhir,0) AS debet_akhir
        {$baseFrom}
        ORDER BY {$orderBy} {$orderDir}
        {$limitSql}
    ";

    $rows = $this->db->query($sqlData, array_merge($bindBase, $bindLimit))->result_array();

        $out = array(
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $rows,
        );
        if ($guardMessage) $out["guardMessage"] = $guardMessage;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($out));
    }
    public function viewBalances_periode_2_categories()
    {
        $rows = $this->db->select("kategori_nama")
            ->from("produk")
            ->order_by("kategori_nama", "asc")
            ->get()->result_array();

        $out = array();
        foreach ($rows as $r) {
            $nm = $r['kategori_nama'];

            // normalize
            if ($nm === null) $nm = '';
            $nm = trim($nm);

            if ($nm === '') {
                $out[] = array("value" => "__empty__", "label" => "(Tanpa kategori)");
            } else {
                $out[] = array("value" => $nm, "label" => $nm);
            }
        }

        // optional: buang duplikat label/value
        // (kalau ada kategori sama)
        $uniq = array();
        $final = array();
        foreach ($out as $it) {
            $key = $it['value']."|".$it['label'];
            if (!isset($uniq[$key])) {
                $uniq[$key] = 1;
                $final[] = $it;
            }
        }

        $this->output->set_content_type('application/json')
            ->set_output(json_encode($final));
    }

    private function _buildSaldoQuery($tbl1, $tbl2, $cabang_id, $gudang_id, $dtime, $q, $kategoriNama)
    {
        $subMax = "
        SELECT extern_id, MAX(id) AS max_id
        FROM {$tbl1}
        WHERE cabang_id = ?
          AND gudang_id = ?
          AND DATE(dtime) <= ?
        GROUP BY extern_id
    ";
        $subLast = "
        SELECT t1.*
        FROM {$tbl1} t1
        INNER JOIN ({$subMax}) x ON x.max_id = t1.id
    ";

        $bindBase = array($cabang_id, $gudang_id, $dtime);

        $where = " WHERE p.status=1 AND p.trash=0 ";
        $bind = array();

        // filter kategori dari dropdown (exact match)
        if ($kategoriNama !== '' && $kategoriNama !== '__all__') {
            $where .= " AND p.kategori_nama = ? ";
            $bind[] = $kategoriNama;
        }

        // global search
        if ($q !== '') {
            $where .= " AND (
            p.nama LIKE ? OR p.barcode LIKE ? OR p.kode LIKE ? OR CAST(p.id AS CHAR) LIKE ?
            OR p.kategori_nama LIKE ?
        )";
            $like = "%".$q."%";
            $bind = array_merge($bind, array($like,$like,$like,$like,$like));
        }

        $from = "
        FROM {$tbl2} p
        INNER JOIN ({$subLast}) last ON last.extern_id = p.id
        {$where}
    ";

        return array(
            "from" => $from,
            "bind" => array_merge($bindBase, $bind),
        );
    }

    public function viewBalances_periode_2_export_csv($relName=null, $rekName=null)
    {
        $tbl1 = "__rek_pembantu_produk__1010030030";
        $tbl2 = "produk";

        $cabang_id = my_cabang_id();
        $gudang_id = my_gudang_id();

        $dtime = $this->input->get('date');
        if (!$dtime) $dtime = date('Y-m-d');

        $q = trim($this->input->get('q'));
        $kategori = $this->input->get('kategori');
        if (!$kategori) $kategori = '__all__';

        $qb = $this->_buildSaldoQuery($tbl1, $tbl2, $cabang_id, $gudang_id, $dtime, $q, $kategori);

        // GUARD export biar ga kebablasan
        $MAX_EXPORT = 50000;
        $sqlCount = "SELECT COUNT(*) AS cnt ".$qb['from'];
        $cnt = (int)$this->db->query($sqlCount, $qb['bind'])->row()->cnt;
        if ($cnt > $MAX_EXPORT) {
            show_error("Export dibatasi maksimal {$MAX_EXPORT} baris. Silakan persempit filter/search.", 400);
            return;
        }

        $sql = "
        SELECT
            p.id AS pid,
            p.kategori_nama,
            p.kode,
            p.barcode,
            p.nama AS nama_produk,
            COALESCE(last.qty_debet_akhir,0) AS qty,
            COALESCE(last.debet_akhir,0) AS nilai
        {$qb['from']}
        ORDER BY p.nama ASC
    ";

        $rows = $this->db->query($sql, $qb['bind'])->result_array();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="persediaan_'.$dtime.'.csv"');

        $out = fopen('php://output', 'w');
        fputcsv($out, array('PID','KATEGORI','SKU','BARCODE','NAMA PRODUK','QTY','NILAI'));

        foreach ($rows as $r) {
            fputcsv($out, array(
                $r['pid'],
                $r['kategori_nama'],
                $r['kode'],
                $r['barcode'],
                $r['nama_produk'],
                $r['qty'],
                $r['nilai'],
            ));
        }
        fclose($out);
    }

/*
 * ################################## viewMoveDetails ##################################
 */

    public function viewMoveDetails()
    {
        $arrMovedDetail = array();
        $relName = $this->uri->segment(3);
        $rekName = urldecode($this->uri->segment(4));
        $rekName_extern_1 = $this->uri->segment(5) != NULL ? urldecode($this->uri->segment(5)) : NULL;
        $externID = $this->uri->segment(6) != NULL ? $this->uri->segment(6) : NULL;
        $transaksiIDStyle = (isset($_GET['trID']) && $_GET['trID'] > 0) ? array($_GET['trID'] => "font-size:18px;font-weight:bold;") : array();

        // $tokoID = (isset($_GET['to']) && $_GET['to'] <> 0) ? $_GET['to'] : $this->session->login['toko_id'];
        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];
        $getExtern2ID = isset($_GET['ext2_id']) && strlen($_GET['ext2_id']) ? $_GET['ext2_id'] : NULL;
        $getMainExtern2ID = isset($_GET['main_ext2_id']) && strlen($_GET['main_ext2_id']) ? $_GET['main_ext2_id'] : NULL;
        $blob_ext = isset($_GET['blob_ext']) && strlen($_GET['blob_ext']) ? blobDecode($_GET['blob_ext']) : NULL;
        $gudangID = (isset($_GET['w']) && $_GET['w'] <> 0) ? $_GET['w'] : $this->session->login['gudang_id'];

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
        // $com->addFilter("toko_id='$tokoID'");

        $com->addFilter("cabang_id='$cabangID'");
        $com->addFilter("jenis<>''");
        if ($getExtern2ID != NULL) {
            $com->addFilter("extern2_id='$getExtern2ID'");
        }

        if (isset($_GET['w'])) {
            $com->addFilter("gudang_id='$gudangID'");
        }
        else {
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
        }


        //        $com->addFilter("gudang_id='9'");

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

        //        arrPrint($this->uri->segment_array());
        //        cekMErah($mdlName . " ::: " . $rekName . " ::: $rekName_extern_1 ::: " . $externID . " ");
        //        $tmp = $com->fetchMoves($rekName, $externID);
        $tmp = $com->fetchMoves($rekName, $rekName_extern_1, $externID);
        //        showLast_query("biru");

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
            // arrPrint($tmp);
            foreach ($tmp as $temX) {
                $transaksi_id = $temX->transaksi_id;
                $position = $temX->debet > 0 ? "debet" : "kredit";
                $val = $temX->debet > 0 ? $temX->debet : $temX->kredit;
                $detectRek = detectRekByPosition($rekName, $val, $position);
                 //cekBiru($detectRek);
                if ($detectRek > 0) {
                    $keyPosition = "in";
                    $keyPositionQty = "in_qty";
                    //                    cekHitam($detectRek);
                    // $arrMovedDetail["in"][$temX->jenis] = $position;
                    if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                        // $arrMovedDetail["in_qty"][$temX->jenis] = "qty_" . $position;
                        $qty_position = "qty_" . $position;
                        $newSelect = "qty_" . $position;
                        $qtyVal = $temX->$newSelect;
                    }
                    else {
                        // cekPink($detectRek);
                        $qtyVal = 0;
                    }
                }
                else {
                    //                    cekMerah($detectRek);
                    $keyPosition = "out";
                    $keyPositionQty = "out_qty";
                    // $arrMovedDetail["out"][$temX->jenis] = $position;
                    if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                        // $arrMovedDetail["out_qty"][$temX->jenis] = "qty_" . $position;
                        $qty_position = "qty_" . $position;
                        $newSelect = "qty_" . $position;
                        $qtyVal = $temX->$newSelect;
                    }
                    else {
                        $qtyVal = 0;
                    }
                }
                $arrMovedDetail[$keyPosition][$temX->jenis] = array(
                    "$keyPosition" => $position,
                    "$keyPositionQty" => $qty_position,

                );
                $arrProds[][$keyPosition][$temX->jenis] = array(
                    "qty_" . $position => $qtyVal,
                    "$position" => "$val",

                );
                // arrprint($arrProds);
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

        // region transaksi
        $listId = implode("','", $trIds);
        $tr->setFilters(array());
        $tr->addFilter("id in ('" . $listId . "')");
        // $tr->addFilter("toko_id='$tokoID'");
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


        // $headerFields["ids_his"] = "reference number";
        //        $headerFields["nomers_prev"] = "referensi nota";
        $headerFields["transaksi_no"] = "nomer nota";
        // $headerFields["review_details"] = "&nbsp;";
        $headerFields["harga_avg"] = "hpp satuan";


        $jenisAliases = arrCodeAliasing($cabangID);


        // region builder data transaksi yg perlu muncul
        $arrDecode = array("ids_his", "counters");
        $trDatas = array();
        $regDatas = array();
        $arrTrIDs = array();
        $arrDataTr = array(
            "customers_id",
            "customers_nama",
            "suppliers_id",
            "suppliers_nama",
            "oleh_id",
            "oleh_nama",
            "jenis_master",
            "trash_4",
        );
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
            foreach ($arrDataTr as $kolomTr) {
                $datas[$kolomTr] = isset($tmpTr->$kolomTr) ? $tmpTr->$kolomTr : "";
            }
            $trDatas[$tmpTr->id] = $datas;
            $arrTrIDs[] = $tmpTr->id;
        }

        if (sizeof($arrTrIDs) > 0) {

            $trk = New MdlTransaksi();
            $trk->setFilters(array());
            //            $trk->addFilter("param='main'");
            $trk->addFilter("transaksi_id in ('" . implode("','", $arrTrIDs) . "')");
            $trkTmp = $trk->lookupDataRegistries()->result();
            //            showLast_query("kuning");
            //            arrPrint($arrTrIDs);
            if (sizeof($trkTmp) > 0) {
                //                cekhitam(count($arrTrIDs));
                foreach ($trkTmp as $trkSpec) {
                    $regTrID = $trkSpec->transaksi_id;
                    foreach ($trkSpec as $key_reg => $val_reg) {
                        if ($key_reg == "main") {

                            $regTrID = $trkSpec->transaksi_id;
                            $param = $key_reg;
                            $regData = blobDecode($val_reg);

                            if (sizeof($regData) > 0) {
                                foreach ($headerFields as $key => $val) {
                                    if (isset($regData[$key])) {
                                        $regDatas[$regTrID][$key] = $regData[$key];
                                        if($regData["paymentMethod"] == "cashless"){
                                            $regDatas[$regTrID]["cash_account__merchant"] = $regData["cash_account__label"];
                                        }
                                    }
                                }
                            }
                        }

                    }
                }
            }
        }

        // endregion builder data transaksi yg perlu muncul
        // arrprintWebs($regData);
        $headerPrev = array(
            "prev" => "saldo awal"
            // "qty_" . $defPosition . "_awal" => "prev (QTY)",
            // $defPosition . "_awal" => "prev (IDR)",
        );
        $headermove2 = array(
            "qty" => array(
                "in" => array(
                    "in_qty" => "qty",
                ),
                "out" => array(
                    "out_qty" => "qty",
                ),
            ),
            "value" => array(
                "in" => array(
                    "in" => "IDR",
                ),
                "out" => array(
                    "out" => "IDR",
                ),
            ),
            "balance" => array(
                "qty_" . $defPosition . "_akhir" => "balance (QTY)",
                $defPosition . "_akhir" => "balance (IDR)",
            ),
        );
        $arrMovedDetailSrc2 = array(
            "qty" => array(
                "prev" => array(
                    "qty_" . $defPosition . "_awal" => "QTY"
                ),
                "balance" => array(
                    "qty_" . $defPosition . "_akhir" => "QTY"
                ),
            ),
            "value" => array(
                "prev" => array(
                    $defPosition . "_awal" => "IDR"
                ),
                "balance" => array(
                    $defPosition . "_akhir" => "IDR"
                ),
            ),
        );
        // $arrMovedDetail2 = array(
        //     "prev"    => array(
        //         "qty_" . $defPosition . "_awal" => "QTY",
        //         $defPosition . "_awal"          => "IDR",
        //     ),
        //     "balance" => array(
        //         "qty_" . $defPosition . "_akhir" => "QTY",
        //         $defPosition . "_akhir"          => "IDR",
        //     ),
        //
        // );
        $headerMoveMaster = array(
            "in" => "masuk",
            "out" => "keluar",
        );
        $headerBalance = array(
            "balance" => "saldo akhir",

        );
        $headerQtyFields = array(
            "qty_" . $defPosition . "_awal" => "prev (QTY)",
            "in_qty" => "in qty",
            "out_qty" => "out qty",
            "qty_" . $defPosition . "_akhir" => "balance (QTY)**",
        );
        $headerValueFields = array(
            $defPosition . "_awal" => "prev (IDR)",
            "in" => "in (IDR)",
            "out" => "out (IDR)",
            $defPosition . "_akhir" => "balance (IDR)**",
        );
        $arrMovedSubDetail = array();
        $arrMovedDetail2 = array();
        if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
            foreach ($headermove2["qty"] as $keSub => $detilH) {
                foreach ($detilH as $xx => $yy) {
                    $arrMovedSubDetail[$keSub][$xx] = $yy;
                }

            }
            foreach ($arrMovedDetailSrc2["qty"] as $paramKey => $paramData) {
                foreach ($paramData as $y => $v) {
                    $arrMovedDetail2[$paramKey][$y] = $v;
                }

            }

        }

        //         matiHere();
        if (isset($balConfig['showValue']) && $balConfig['showValue'] == true) {
            foreach ($headermove2["value"] as $keSub1 => $detilH2) {
                foreach ($detilH2 as $xx1 => $yy1) {
                    $arrMovedSubDetail[$keSub1][$xx1] = $yy1;
                }

            }
            foreach ($arrMovedDetailSrc2["value"] as $paramValue => $paramDataValue) {
                foreach ($paramDataValue as $yv => $vv) {
                    $arrMovedDetail2[$paramValue][$yv] = $vv;
                }
            }
        }
        // arrPrint($arrMovedDetail2);
        $headerFields = $headerFields + $headerPrev + $headerMoveMaster + $headerBalance;

        //region buld yIndex
        //        arrPrintWebs($arrMovedSubDetail);
        //        arrPrint($arrProds);
        $items0 = array();
        $itemsCek = array();
        //        krsort($arrProds);
        // arrPrint($arrProds);
        // arrPrintWebs($arrMovedDetail);
        // matiHere();
        foreach ($arrProds as $rowKey => $row0) {
            foreach ($row0 as $mKey => $mData) {
                $temX = array();
                // arrPrint($mData);
                // cekHitam($mKey);
                // arrprint($arrMovedDetail[$mKey]);
                // matiHere();
                $subs0 = array();
                $subItemCek0 = array();
                foreach ($arrMovedDetail[$mKey] as $yparent => $ySpec) {
                    foreach ($ySpec as $jn => $colloumb) {
                        if (isset($mData[$yparent][$colloumb])) {
                            $val = $mData[$yparent][$colloumb];
                            // cekMErah($rowKey."|".$yparent."|$jn|".$val);
                            $subs0[$yparent][$jn] = $val;
                            $subItemCek[$yparent] = $val;
                        }
                        // else {
                        //     $val = 0;
                        // }

                        // $subs0[$jn] = $val;
                    }

                    $temX[$mKey] = $subs0;
                    $subItemCek0[$mKey] = $subItemCek;
                }
                // arrPrint($mData);
                // arrPrint($temX);
                // matiHere($yparent);
                $items2[] = $temX;
                $itemsCek[] = $subItemCek0;
                // arrprint($temX);
                // matiHere();
                // $tempData[]
            }
            // $temX = array();
            // foreach ($arrMovedDetail as $yparent => $ySpec) {
            //     $subs0 = array();
            //     foreach ($ySpec as $jn => $colloumb) {
            //
            //         if (isset($row0[$jn][$colloumb])) {
            //             $val = $row0[$jn][$colloumb];
            //         }
            //         else {
            //             $val = 0;
            //         }
            //         $subs0[$jn][$colloumb] = $val;
            //         // $subs0[$jn] = $val;
            //     }
            //     $temX[$yparent] = $subs0;
            // }
            // $items0[] = $temX;
        }
        //endregion
        // arrPrint($items0);
        // matiHEre();


        //region label detil transaksi
        $childHeaderLabels = $this->config->item('heTransaksi_ui');
        switch ($cabangID) {
            case "-1":
                $placeLocation = "center";
                break;
            default:
                $placeLocation = "branch";
                break;
        }
        $detailsLabel = array();
        foreach ($childHeaderLabels as $tempSpec) {
            $xxTemp = $tempSpec["steps"];
            foreach ($xxTemp as $tempLabels) {
                $keyLabel = $tempLabels["target"];
                //                $keyValue = $tempLabels["label"];
                if (isset($tempLabels["placeLabel"][$placeLocation]) && count($tempLabels["placeLabel"][$placeLocation]) > 0) {
                    $keyValue = $tempLabels["placeLabel"][$placeLocation];
                    //                    matiHEre(__LINE__);
                }
                else {
                    $keyValue = $tempLabels["label"];
                }
                $detailsLabel[$keyLabel] = $keyValue;
            }
        }
        //endregion


        // arrPrint($arrMovedDetail);
        $title = "";
        // data transaksi dab
        $items = array();
        if (sizeof($tmp) > 0) {
            krsort($tmp);

            foreach ($tmp as $row) {
                if (isset($regDatas[$row->transaksi_id])) {
                    foreach ($regDatas[$row->transaksi_id] as $k_reg => $v_reg) {
                        $row->$k_reg = $v_reg;
                    }
                }

                if (isset($trDatas[$row->transaksi_id])) {
                    foreach ($trDatas[$row->transaksi_id] as $key => $val) {
                        $row->$key = $val;
                    }
                }
                //                arrPrintCyan($row);

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
                // arrPrint($row);
                $subs_r = array();
                foreach ($headerFields as $key => $label) {
                    if (array_key_exists($key, $row)) {
                        if ((isset($row->$key)) && (!is_numeric($row->$key))) {
                            // cekHitam($row->$key);
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
                    if (($key == "jenis") && (isset($jenisAliases[$row->$key]))) {
                        $subs_r[$key] = $jenisAliases[$row->$key];
                    }
                }
                foreach ($arrMovedDetail2 as $key1 => $srcBalanceData) {
                    foreach ($srcBalanceData as $srcBkey => $srcBLabel) {
                        if ((isset($row->$srcBkey)) && (!is_numeric($row->$srcBkey))) {
                            // cekHitam($row->$key);
                            if ($row->$srcBkey === base64_encode(base64_decode($row->$srcBkey))) {

                                $subs_r[$srcBkey] = unserialize(base64_decode($row->$srcBkey));

                            }
                            else {
                                $subs_r[$srcBkey] = $row->$srcBkey;
                            }
                        }
                        else {
                            $subs_r[$srcBkey] = $row->$srcBkey;
                        }
                    }
                }


                $subs_r['saldo_qty_berjalan'] = isset($saldoBerjalan[$row->id]) ? $saldoBerjalan[$row->id]['qty'] : 0;
                $subs_r['saldo_berjalan'] = isset($saldoBerjalan[$row->id]) ? $saldoBerjalan[$row->id]['value'] : 0;
                $subs_r['transaksi_id'] = $row->transaksi_id;
                $subs_r['review_details'] = $row->transaksi_id;
                $modul = isset($this->masterConfigUi[$row->jenis_master]["modul"]) ? $this->masterConfigUi[$row->jenis_master]["modul"] : false;
                $subs_r['modul'] = $modul;
                $subs_r['modul_path'] = base_url() . $modul . "/";
                $subs_r['jenis_master'] = $row->jenis_master;
                $subs_r['trash_4'] = $row->trash_4;;

                if (in_array($row->jenis_master, $this->blacklistJenisMaster)) {
                    $subs_r['jenis_master_bg_color'] = "red";
                }
                else {
                    $subs_r['jenis_master_bg_color'] = NULL;
                }

                $items[] = $subs_r;


                $title = $row->extern_nama;
            }
        }
        // arrPrint(array_slice($items,0,1));
        // arrPrint($items);
        $link_excel = "";
        // $link_excel = base_url() . "ExcelWriter/mutasi/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "?o=$cabangID";

        // arrprint($headerFields);
        // arrprint($arrMovedSubDetail);
        //        arrPrint($this->rekAlias);
        $rekNameAlias = isset($this->rekAlias[$rekName]) ? $this->rekAlias[$rekName] : $rekName;
        //        arrPrintKuning($_GET);
        //        cekHere(http_build_query($_GET));
        $geturl = "&" . http_build_query($_GET);
        $data = array(
            "mode" => "viewMoveDetails",
            "title" => $rekNameAlias . " &nbsp;" . $titleTop,
            "subTitle" => " $blob_ext $subTitle_date $subSubTitle_date ",
            "items" => $items,
            "items2" => $items2,
            "headerFields" => $headerFields,
            "headerFields2" => $arrMovedDetail,
            "headerFields3" => $arrMovedSubDetail,
            "headerFields4" => $arrMovedDetail2,
            "filters" => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "detailsLabels" => $detailsLabel,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6) . "?o=$cabangID",
            "link_excel" => $link_excel,
            "itemsCek" => $itemsCek,
            "summary" => isset($this->summaryKey) ? $this->summaryKey : array(),
            "addStyle" => isset($transaksiIDStyle) ? $transaksiIDStyle : array(),
            "geturl" => isset($geturl) ? $geturl : "",
        );

        $this->load->view("ledger", $data);

    }

    public function viewWorkorder()
    {

        $this->load->library("Ciqrcode");
        $qr = new Ciqrcode();
        $fields = array(
            "qty_debet" => "QTY",
            "debet" => "RP",
        );

        $produk_id = $_GET["produk_id"];
        $cabang_id = $_GET["cabang_id"];
//        $gudang_id = $_GET["gudang_id"];
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $this->load->model("Mdls/MdlProduk2");
        $this->load->model("Mdls/MdlTasklistProject");
        $tr = new ComRekeningPembantuProduk();

        $tr->setFilters(array());
        $tr->addFilter("extern_id='$produk_id'");
        $tr->addFilter("cabang_id='$cabang_id'");
        $tr->addFilter("gudang_id>1");
        $tr->addFilter("qty_debet>0");
        $this->db->order_by("dtime", "asc");
        $items = $tr->fetchBalances("1010030030");
        //        showLast_query("biru");

        $mt = New MdlTasklistProject();
        $mt->addFilter("cabang_id=$cabang_id");
        $mtTmp = $mt->lookupAll()->result();
        if (sizeof($mtTmp) > 0) {
            $arrGudangWo = array();
            foreach ($mtTmp as $mtSpec) {
                $label_gudang = $mtSpec->nama;
                $label_gudang .= "<br>" . $mtSpec->no_spk;
                $label_gudang .= "<br>" . $mtSpec->produk_nama;
                $arrGudangWo[$mtSpec->gudang_wo] = $label_gudang;
            }
        }


        $pd = New MdlProduk2();
        $pd->addFilter("id='$produk_id'");
        $pdTmp = $pd->lookupAll()->result();
        $produk_id = $pdTmp[0]->id;
        $produk_nama = $pdTmp[0]->nama;
        $produk_merek = $pdTmp[0]->merek_nama;

        $datas = "<table class='table table-bordered table hover'>";
        $datas .= "<tr>";
        $datas .= "<th>No</th>";
        $datas .= "<th>Nama Gudang Project</th>";
        foreach ($fields as $kk => $label) {
            $datas .= "<th>$label</th>";
        }
        $datas .= "</tr>";

        if (count($items) > 0) {
            $ix = 0;
            foreach ($items as $ii => $items_0) {

                $ix++;
                $datas .= "<tr>";
                $datas .= "<td>$ix</td>";
                $nama = isset($arrGudangWo[$items_0->gudang_id]) ? $arrGudangWo[$items_0->gudang_id] : $items_0->gudang_id;
                $datas .= "<td>$nama</td>";
                foreach ($fields as $k => $alias) {
                    $datas .= "<td>";
                    $datas .= formatField($k, $items_0->$k);
                    $datas .= "</td>";
                    if (is_numeric($items_0->$k)) {
                        if (!isset($summary[$k])) {
                            $summary[$k] = 0;
                        }
                        $summary[$k] += $items_0->$k;
                    }

                }
                $datas .= "</tr>";
            }
            $datas .= "<tr>";
            $datas .= "<th>-</th>";
            $datas .= "<th>-</th>";
            foreach ($fields as $k => $alias) {
                $datas .= "<th>";
                $datas .= formatField($k, $summary[$k]);
                $datas .= "</th>";
            }
            $datas .= "</tr>";
        }

        $datas .= "</table>";
        $headerTitle = "<div>";
        $headerTitle .= "<div><span style='font-size:15px;font-weight:bold;'>($produk_id) $produk_merek $produk_nama</span></div>";
        $headerTitle .= "</div>";
        echo $headerTitle;
        echo $datas;

    }
}
