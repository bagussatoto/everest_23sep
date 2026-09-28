<?php


class ToolRepair2 extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->masterConfigUi = $this->config->item("heTransaksi_ui");
        $this->load->helper("he_angka");
    }

    function index()
    {
        $arrTools = array(
            "kas" => "viewUnsyncedKas",
            "produk" => "viewUnsyncedProduk",
            "produk rakitan" => "viewUnsyncedProdukRakitan",
            "supplies" => "viewUnsyncedSupplies",
            "valas" => "viewUnsyncedValas",
        );

//        foreach ($arrTools as $key => $value) {
//            echo "<div>";
//            echo "<h3>";
//            echo "<a href='" . base_url() . get_class($this) . "/$value' target='_blank'>:: $key ::</a>";
//            echo "</h3>";
//            echo "</div>";
//        }
    }

    //-------------------------
    public function patchProject()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("MdlTransaksi");
        $this->load->model("Coms/ComTransaksiProject");
        $arrComProject = array();
//        array(
//            "comName" => "TransaksiProject",
//            "loop" => array(
//                "project" => "grandTotal",
//            ),
//            "static" => array(
//                "cabang_id" => "placeID",
//                "cabang_nama" => "placeName",
//                "extern_id" => "projectID",
//                "extern_nama" => "projectName",
//                "terbayar" => "grandTotal",
//            ),
//            "reversable" => true,
//            "srcGateName" => "main",
//            "srcRawGateName" => "main",
//        ),

        $tr = New MdlTransaksi();
        $tr->addFilter("trash_4='0'");
        $tr->addFilter("jenis='588st'");
        $trTmp = $tr->lookupAll()->result();
        cekBiru(count($trTmp));
        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $trSpec) {
                $trid = $trSpec->id;
                $trreg = New MdlTransaksi();
                $trreg->setFilters(array());
                $trreg->addFilter("transaksi_id='$trid'");
                $trreg->setJointSelectFields("transaksi_id, main");
                $tmpReg = $trreg->lookupDataRegistries()->result();
                $main = blobDecode($tmpReg[0]->main);
                $arrComProject[$trid] = array(
                    "loop" => array(
                        "project" => $main["grandTotal"],
                    ),
                    "static" => array(
                        "cabang_id" => $main["placeID"],
                        "cabang_nama" => $main["placeName"],
                        "extern_id" => $main["projectID"],
                        "extern_nama" => $main["projectName"],
                        "terbayar" => $main["grandTotal"],
                        "transaksi_id" => $trSpec->id,
                        "transaksi_no" => $trSpec->nomer,
                        "dtime" => $trSpec->dtime,
                        "fulldate" => $trSpec->fulldate,
                    ),
                );
            }
        }
//        arrPrintCyan($arrComProject);


        $this->db->trans_start();

        if (sizeof($arrComProject) > 0) {
            foreach ($arrComProject as $trid => $spec) {
                $cp = New ComTransaksiProject();
                $cp->pair($spec);
                $cp->exec();
            }
        }

        mati_disini("---SETOP--- " . __LINE__);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekHijau("<h3>DONE...</h3>");
    }


    public function patchPenerimaanPenjualanTunai_OLD()
    {
        $date1 = "2024-07-01";
        $date2 = "2024-12-31";

        $this->load->model("MdlTransaksi");
        $tr = New MdlTransaksi();
        $tr->addFilter("jenis='4464'");
//        $tr->addFilter("trash_4='0'");
        $tr->addFilter("date(dtime)>='$date1'");
        $tr->addFilter("date(dtime)<='$date2'");
        $trTmp = $tr->lookupAll()->result();
        showLast_query("biru");
        cekBiru(count($trTmp));


        $this->db->trans_start();


        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $trSpec) {
                $trid = $trSpec->id;
                $trreg = New MdlTransaksi();
                $trreg->setFilters(array());
                $trreg->addFilter("transaksi_id='$trid'");
                $trreg->setJointSelectFields("transaksi_id, main");
                $tmpReg = $trreg->lookupDataRegistries()->result();
                $main = blobDecode($tmpReg[0]->main);
                $nilai_bayar = $main["nilai_bayar"];

                $tr = New MdlTransaksi();
                $where = array(
                    "id" => $trid,
                );
                $data = array(
                    "transaksi_nilai" => $nilai_bayar,
                    "transaksi_net" => $nilai_bayar,
                );
                $tr->setFilters(array());
                $tr->updateData($where, $data);
                showLast_query("orange");
            }
        }

//        mati_disini("---SETOP--- " . __LINE__);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekHijau("<h3>DONE...</h3>");


    }

    public function patchPenerimaanPenjualanTunai()
    {
//        $date1 = "2024-01-01";
//        $date2 = "2024-06-31";
//        $date1 = "2024-07-01";
//        $date2 = "2024-12-31";
        $date1 = "2025-01-01";
        $date2 = "2025-12-31";

        $this->load->model("MdlTransaksi");
        $tr = New MdlTransaksi();
        $tr->addFilter("jenis in ('4464','749')");
//        $tr->addFilter("trash_4='0'");
        $tr->addFilter("date(dtime)>='$date1'");
        $tr->addFilter("date(dtime)<='$date2'");
        $trTmp = $tr->lookupAll()->result();
        showLast_query("biru");
        cekBiru(count($trTmp));


        $this->db->trans_start();


        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $trSpec) {
                $trid = $trSpec->id;
                $trreg = New MdlTransaksi();
                $trreg->setFilters(array());
                $trreg->addFilter("transaksi_id='$trid'");
                $trreg->setJointSelectFields("transaksi_id, main");
                $tmpReg = $trreg->lookupDataRegistries()->result();
                $main = blobDecode($tmpReg[0]->main);
                $nilai_bayar = $main["nilai_bayar"];

                $tr = New MdlTransaksi();
                $where = array(
                    "id" => $trid,
                );
                $data = array(
                    "transaksi_nilai" => $nilai_bayar,
                    "transaksi_net" => $nilai_bayar,
                    //----
                    "bank_id" => isset($main["cash_account__folders"]) ? $main["cash_account__folders"] : 0,
                    "bank_nama" => isset($main["cash_account__folders_nama"]) ? $main["cash_account__folders_nama"] : 0,
                    "bank_rekening_id" => isset($main["cash_account"]) ? $main["cash_account"] : 0,
                    "bank_rekening_nama" => isset($main["cash_account__label"]) ? $main["cash_account__label"] : 0,
                );
                $tr->setFilters(array());
                $tr->updateData($where, $data);
                showLast_query("orange");
            }
        }

//        mati_disini("---SETOP--- " . __LINE__);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekHijau("<h3>DONE...</h3>");


    }


    public function run_susulanJurnal()
    {

        $this->load->model("MdlTransaksi");
        $this->load->model("CustomCounter");
        $this->load->helper("he_mass_table");
        $startDate = dtimeNow();


        $getTrID = (isset($_GET['tr_id']) && ($_GET['tr_id'] > 0)) ? $_GET['tr_id'] : 0;
        $addJudul = "";

        $tr = New MdlTransaksi();
        $tr->setSortBy(
            array(
                "kolom" => "id",
                "mode" => "ASC",
            )
        );
        $this->db->limit(1);

        $getTrID = 879252;

        // bila ada trID dari URL, maka ini adalah cek manual, tidak boleh close commit !!!
        if ($getTrID > 0) {
            $tr->addFilter("id='$getTrID'");

            $addJudul = "<br>cek manual";
        }
        else {
            $tr->addFilter("cli='0'");
        }

        $trTmp = $tr->lookupAll()->result();
        cekHere($this->db->last_query() . "<br>" . sizeof($trTmp));
//        mati_disini(__LINE__);


        if (sizeof($trTmp) > 0) {
            $trID_cli = $trTmp[0]->id;
            $trTmpCabangID = $trTmp[0]->cabang_id;
            $kolom = array(
                "trID" => "id",
                "jenisTr" => "jenis",
                "jenisTrMaster" => "jenis_master",
                "jenisTrTop" => "jenis_top",
                "nomer" => "nomer",
                "nomerTop" => "nomer_top",
                "dtime" => "dtime",
                "fulldate" => "fulldate",
                "stepNumber" => "step_number",
                "indexRegistry" => "indexing_registry",
                "olehID" => "oleh_id",
                "olehNama" => "oleh_nama",
            );

            $arrKolomTrans = array();
            foreach ($kolom as $key => $val) {
                $arrKolomTrans[$key] = isset($trTmp[0]->$val) ? $trTmp[0]->$val : NULL;
            }

            $reg = New MdlTransaksi();
            $key = "indexRegistry";
            $index_reg = blobDecode($arrKolomTrans[$key]);
            $reg->setFilters(array());
//            $reg->addFilter("id in ('" . implode("','", $index_reg) . "')");
            $reg->addFilter("transaksi_id='" . $trTmp[0]->id . "'");
            $regTmp = $reg->lookupDataRegistries()->result();
            $registryGates = array();
            foreach ($regTmp as $regSpec) {
                foreach ($regSpec as $key_reg => $val_reg) {
                    if ($key_reg != "transaksi_id") {
                        $registryGates[$key_reg] = blobDecode($val_reg);
                    }
                }
            }

//cekHitam(":: cetak REGISTRY ::");
//            arrPrintWebs($registryGates["items8_sum"]);
//mati_disini();
//            arrprint($arrKolomTrans);
//            arrPrint($registryGates["items"]);
//             mati_disini();
            $jenisTr = $arrKolomTrans['jenisTr'];
            $jenisTrMaster = $arrKolomTrans['jenisTrMaster'];
            $fulldate = $arrKolomTrans['fulldate'];
            $dtime = $arrKolomTrans['dtime'];
            $stepNumber = $arrKolomTrans['stepNumber'];
            $insertNum = $tmpNomorNota = $arrKolomTrans['nomer'];
            $olehNama = $arrKolomTrans['olehNama'];
            $insertID = $transaksiID = $arrKolomTrans['trID'];
            /*---------------------- jenismaster untuk gerbang utama masuk modul, jenisTr adalah targetnya */
            /*------end*/
            $configCore = loadConfigModulJenis_he_misc($jenisTrMaster, "coTransaksiCore");
            $configUi = loadConfigModulJenis_he_misc($jenisTrMaster, "coTransaksiUi");
            $configLayout = loadConfigModulJenis_he_misc($jenisTrMaster, "coTransaksiLayout");

            cekHitam(":: jenisTrMaster-> $jenisTrMaster :: jenisTr-> $jenisTr :: [trID_cli: $trID_cli]");

            $cliComponent = "components";

            $pakai_ini = 0;
            if ($pakai_ini == 1) {

                //region BUILD TABEL DATABASE OTOMATIS
                $buildTablesDetail = isset($configCore[$cliComponent][$jenisTr]['detail']) ? $configCore[$cliComponent][$jenisTr]['detail'] : array();
//arrPrintWebs($buildTablesDetail);
                if (sizeof($buildTablesDetail) > 0) {
                    foreach ($buildTablesDetail as $buildTablesDetail_specs) {
//arrPrintWebs($buildTablesDetail_specs);
                        $buildTablesDetail_specs_result = $buildTablesDetail_specs;
                        $srcGateName = $buildTablesDetail_specs['srcGateName'];
                        $srcRawGateName = $buildTablesDetail_specs['srcRawGateName'];
//                    cekHitam(__LINE__ . ":: $srcGateName");
                        if (isset($registryGates[$srcGateName]) && sizeof($registryGates[$srcGateName]) > 0) {
                            foreach ($registryGates[$srcGateName] as $itemSpec) {

//                            arrPrintWebs($itemSpec);
                                $mdlName = $buildTablesDetail_specs['comName'];
//                            cekBiru("== $srcGateName == $mdlName ==");
                                if (substr($mdlName, 0, 1) == "{") {
                                    $mdlName = trim($mdlName, "{");
                                    $mdlName = trim($mdlName, "}");
                                    $mdlName = str_replace($mdlName, $itemSpec[$mdlName], $mdlName);
                                }

//cekBiru("== $mdlName ==");
                                if (isset($buildTablesDetail_specs['loop'])) {
                                    foreach ($buildTablesDetail_specs['loop'] as $key => $val) {
//cekKuning(":: $key => $val ::");
                                        unset($buildTablesDetail_specs_result['loop']);
                                        if (substr($key, 0, 1) == "{") {
                                            $key = trim($key, "{");
                                            $key = trim($key, "}");
                                            $key = str_replace($key, $itemSpec[$key], $key);
                                        }
                                        $buildTablesDetail_specs_result['loop'][$key] = $val;
//                                cekHitam("LINE: " . __LINE__ . " ::sini bukan??  akan build tabel detail $key");
                                    }
                                }

//arrPrintWebs($buildTablesDetail_specs_result['loop']);
//                        cekHere($mdlName . " == " . $srcGateName);
                                $mdlName = "Com" . $mdlName;
                                $this->load->model("Coms/" . $mdlName);
                                $m = new $mdlName();
                                if (method_exists($m, "getTableNameMaster")) {
                                    if (sizeof($m->getTableNameMaster())) {
//                                cekMerah(":: $mdlName ::");
//                                arrPrintWebs($buildTablesDetail_specs_result);
                                        $m->buildTables($buildTablesDetail_specs_result);
                                    }
                                }
                            }

                        }
                        else {
//                        cekHere("TESTSTST");
                        }
                    }
                }
                else {
                    cekMerah(":: TIDAK ADA CONFIG cliComponent");
                }
                //endregion

            }


            $this->db->trans_start();

            $paramPatchers = $this->config->item('heTransaksi_paramPatchers') != null ? $this->config->item('heTransaksi_paramPatchers') : array();
            $paramForceFillers = $this->config->item('heTransaksi_paramForceFillers') != null ? $this->config->item('heTransaksi_paramForceFillers') : array();
            $validateSubComponent = $this->config->item('heTransaksi_validateComponentDetail') != null ? $this->config->item('heTransaksi_validateComponentDetail') : array();
            $paramForceFillersJenisTR = $this->config->item('heTransaksi_paramForceFillers_jenisTR') != null ? $this->config->item('heTransaksi_paramForceFillers_jenisTR') : array();

            $pakai_ini = 0;
            if ($pakai_ini == 1) {

                //region ----------subcomponents by cli
                $componentGate['detail'] = array();
                $componentConfig['master'] = array();
                $componentConfig['detail'] = array();
                if (isset($configCore['relativeComponets']) && $configCore['relativeComponets'] == true) {
                    $iterator = isset($registryGates['revert']['jurnal']['detail']) ? $registryGates['revert']['jurnal']['detail'] : array();
                    $revertedTarget = $registryGates['main']['pihakExternID'];
                    $componentConfig['detail'] = $iterator;
                    $iteratorMaster = $componentConfig['master'] = isset($registryGates['revert']['jurnal']['master']) ? $registryGates['revert']['jurnal']['master'] : array();
                }
                else {
                    $iterator = isset($configCore[$cliComponent][$jenisTr]['detail']) ? $configCore[$cliComponent][$jenisTr]['detail'] : array();
                    $componentConfig['detail'] = $iterator;
                    $iteratorMaster = $componentConfig['master'] = isset($configCore[$cliComponent][$jenisTr]['master']) ? $configCore[$cliComponent][$jenisTr]['master'] : array();

                    $revertedTarget = "";

                }
                $subComModel = array();
                if (sizeof($iterator) > 0) {
//                arrPrintKuning($iterator);
                    $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                    $filterNeeded = false;

                    $arrRekeningLoop = array();

//                if (in_array($mdlName, $compValidators)) {//perlu validasi filter
//                    $filterNeeded = true;
//                }
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName_orig = $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $loopRequire = isset($tComSpec['loopRequire']) ? $tComSpec['loopRequire'] : false;
                        $srcRawGateName = $tComSpec['srcRawGateName'];

                        echo "sub-component: $comName, $srcGateName, initializing values <br>";

                        $tmpOutParams[$cCtr] = array();
                        if (isset($registryGates[$srcGateName]) && sizeof($registryGates[$srcGateName]) > 0) {

                            foreach ($registryGates[$srcGateName] as $id => $dSpec) {
                                $comName = $comName_orig;
                                if (substr($comName, 0, 1) == "{") {
                                    $comName = trim($comName, "{");
                                    $comName = trim($comName, "}");
                                    $comName = str_replace($comName, $registryGates[$srcGateName][$id][$comName], $comName);
                                    $tComSpec['comName'] = $comName;
                                    $iterator[$cCtr]['comName'] = $comName;
                                }
//                        $subComModel[$comName] = $comName;
                                $filterNeeded = false;
                                $mdlName = "Com" . ucfirst($comName);
                                if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                    $filterNeeded = true;
                                }


                                $subParams = array();
                                if (isset($tComSpec['loop'])) {
                                    foreach ($tComSpec['loop'] as $key => $value) {
                                        if (substr($key, 0, 1) == "{") {
                                            $key = trim($key, "{");
                                            $key = trim($key, "}");
                                            $key = str_replace($key, $registryGates[$srcGateName][$id][$key], $key);
                                        }

                                        $subComModel[$key] = $comName;

                                        $realValue = makeValue($value, $registryGates[$srcGateName][$id], $registryGates[$srcGateName][$id], 0);

                                        if (strlen($key) > 1) {
                                            $subParams['loop'][$key] = $realValue;
                                        }
                                        else {
                                            $subParams['loop'] = array();
                                        }

                                        // =================== =================== ===================
                                        if (!isset($arrRekeningLoop[$dSpec[$tComSpec['static']['cabang_id']]][$key])) {
                                            $arrRekeningLoop[$dSpec[$tComSpec['static']['cabang_id']]][$key] = 0;
                                        }
                                        $arrRekeningLoop[$dSpec[$tComSpec['static']['cabang_id']]][$key] += $realValue;
                                        if ($realValue != 0) {
                                            cekUngu(":: cetak loop $key => $realValue ::");
                                        }

                                        if ($filterNeeded) {
                                            if ($subParams['loop'][$key] == 0) {
                                                unset($subParams['loop'][$key]);

                                                // =================== =================== ===================
                                            }
                                        }
                                    }
                                }
                                if (isset($tComSpec['static'])) {
                                    foreach ($tComSpec['static'] as $key => $value) {

                                        $realValue = makeValue($value, $registryGates[$srcGateName][$id], $registryGates[$srcGateName][$id], 0);
//                                    $subParams['static'][$key] = $realValue;
                                        $subParams['static'][$key] = trim($realValue);
//                                cekKuning("STATIC: $key diisi dengan $realValue");
                                    }
                                    if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                        foreach ($paramPatchers[$comName] as $k => $v) {
                                            if (!isset($subParams['static'][$k])) {
                                                $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                                cekOrange("fill :: $comName :: $k ($v) => " . $subParams['static'][$k]);
                                            }
                                        }
                                    }
                                    if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {

                                        $jenis = $registryGates['main']['jenis'];
                                        foreach ($paramForceFillers[$comName] as $k => $v) {
                                            $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                            cekOrange("fillforce :: $comName :: $k ($v) => " . $subParams['static'][$k]);
                                        }
                                    }
//                                arrPrintWebs($paramForceFillersJenisTR[$comName]);
//                                cekMerah($jenisTrMaster);
                                    // tambahan custom gerbang saat simpan transaksi, tidak bisa ditambahkan di coTransaksiCore/coTransaksiValues
                                    if (isset($paramForceFillersJenisTR[$comName][$jenisTrMaster]) && sizeof($paramForceFillersJenisTR[$comName][$jenisTrMaster]) > 0) {
                                        foreach ($paramForceFillersJenisTR[$comName][$jenisTrMaster] as $k => $v) {
                                            $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                            cekorange(":: $k diisikan dengan " . $subParams['static'][$k]);
                                        }
                                    }
                                    $subParams['static']["fulldate"] = $fulldate;
                                    $subParams['static']["dtime"] = $dtime;
                                    $subParams['static']["keterangan"] = $configUi['steps'][$stepNumber]['label'] . " nomor " . $tmpNomorNota . " oleh " . $olehNama;
                                    //------
                                    $subParams['static']["reference_id"] = isset($dSpec["referenceID"]) ? $dSpec["referenceID"] : "";
                                    $subParams['static']["reference_nomer"] = isset($dSpec["referenceNomer"]) ? $dSpec["referenceNomer"] : "";
                                    $subParams['static']["reference_jenis"] = isset($dSpec["jenisTr_reference"]) ? $dSpec["jenisTr_reference"] : "";
                                    $subParams['static']["reference_id_top"] = isset($dSpec["referenceID_top"]) ? $dSpec["referenceID_top"] : "";
                                    $subParams['static']["reference_nomer_top"] = isset($dSpec["referenceNomer_top"]) ? $dSpec["referenceNomer_top"] : "";
                                    $subParams['static']["reference_jenis_top"] = isset($dSpec["pihakExternMasterID"]) ? $dSpec["pihakExternMasterID"] : "";
                                    //------
                                    if (strlen($revertedTarget) > 1) {
                                        $subParams['static']['reverted_target'] = $revertedTarget;
                                    }
                                }
                                if (sizeof($subParams) > 0) {
                                    if ($filterNeeded) {
                                        if (isset($subParams['loop']) && !empty($subParams['loop'])) {
                                            $tmpOutParams[$cCtr][] = $subParams;
                                        }
                                    }
                                    else {
                                        if (empty($subParams['loop']) && $loopRequire == true) {
                                            unset($tmpOutParams[$cCtr]);
                                        }
                                        else {
                                            $tmpOutParams[$cCtr][] = $subParams;
                                        }
                                    }
                                }
                            }

                            $componentGate['detail'][$cCtr] = $subParams;
                        }

                    }
//                arrPrintKuning($tmpOutParams);
                    $it = 0;
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $it++;
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        if (isset($registryGates[$srcGateName]) && sizeof($registryGates[$srcGateName]) > 0) {
                            foreach ($registryGates[$srcGateName] as $id => $dSpec) {
                                if (substr($comName, 0, 1) == "{") {
                                    $comName = trim($comName, "{");
                                    $comName = trim($comName, "}");
                                    $comName = str_replace($comName, $registryGates[$srcGateName][$id][$comName], $comName);
//                            $tComSpec['comName'] = $comName;
//                            $iterator[$cCtr]['comName'] = $comName;
//
//
                                }
                            }
                        }
                        else {
                            $comName = NULL;
                        }
                        cekHere("::::: $comName ::::: $srcGateName :::::");


                        echo __LINE__ . " sub $cCtr component #$it: $comName, sending values**** <br>";

                        if ($comName != NULL) {
//cekHere(":: $comName ::");
                            $mdlName = "Com" . ucfirst($comName);
                            $this->load->model("Coms/" . $mdlName);
                            $m = new $mdlName();

                            if (isset($tmpOutParams[$cCtr]) && sizeof($tmpOutParams[$cCtr]) > 0) {
                                $tobeExecuted = true;
                            }
                            else {
                                $tobeExecuted = false;
                            }

                            if ($tobeExecuted) {
                                $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $jenisTrMaster . "/" . __FUNCTION__ . "/" . __LINE__);
                                $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $jenisTrMaster . "/" . __FUNCTION__ . "/" . __LINE__);
                            }
                            else {
                                cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                            }

                        }
                    }

                    cekMerah("HAHAHA");
                    $pakai_ini = 0;
                    if ($pakai_ini == 1) {
                        // region baca jurnal rekening besar
                        $jn = New ComJurnal();
                        $jn->addFilter("transaksi_id='$transaksiID'");
                        $jnTmp = $jn->lookupAll()->result();
//                    arrPrint($jnTmp);
                        $arrJurnal = array();
                        if (sizeof($jnTmp) > 0) {
                            foreach ($jnTmp as $ii => $spec) {
                                $defPosition = detectRekDefaultPosition($spec->rekening);
                                switch ($defPosition) {
                                    case "debet":
                                        $arrJurnal[$spec->cabang_id][$spec->rekening] = $spec->debet > 0 ? $spec->debet : $spec->kredit * -1;
                                        break;
                                    case "kredit":
                                        $arrJurnal[$spec->cabang_id][$spec->rekening] = $spec->kredit > 0 ? $spec->kredit : $spec->debet * -1;
                                        break;
                                    default:
                                        mati_disini("tidak menemukan default posisi rekening...");
                                        break;
                                }
                            }
                        }
                        // endregion

                        cekHere("cetak array jurnal");
                        arrPrint($arrJurnal);

                        cekHere("cetak rek loop");
                        arrPrint($arrRekeningLoop);


                        if (sizeof($arrJurnal) > 0) {
                            if (sizeof($arrRekeningLoop) > 0) {
                                foreach ($arrRekeningLoop as $cabang_id => $loopSpec) {
                                    foreach ($loopSpec as $rekening => $rekValue) {
                                        if (array_key_exists($rekening, $arrJurnal[$cabang_id])) {
                                            if (floor($rekValue) != floor($arrJurnal[$cabang_id][$rekening])) {
                                                mati_disini("nilai $rekening, jurnal: " . floor($arrJurnal[$cabang_id][$rekening]) . ", akumulasi pembantu: " . floor($rekValue));
                                            }
                                            else {
                                                cekHijau(":: COCOK ::");
                                            }
                                        }
                                    }
                                }
                            }
                        }


                    }


                    // validasi rekening besar vs rekening pembantu
                    validateBalancesComparison($trTmpCabangID, $componentGate, $componentConfig, "detail", $transaksiID, $tmpNomorNota);

                }
                else {
                    cekMerah("subcomponents [detail] is not set");
                }

                arrPrint($iteratorMaster);
                if (sizeof($iteratorMaster) > 0) {
                    $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                    $componentConfig['master'] = $iteratorMaster;
                    $cCtr = 0;
                    foreach ($iteratorMaster as $cCtr => $tComSpec) {
                        $cCtr++;
                        $comName = $tComSpec['comName'];
                        if (substr($comName, 0, 1) == "{") {
                            $comName = trim($comName, "{");
                            $comName = trim($comName, "}");
                            $comName = str_replace($comName, $registryGates[$srcGateName][$comName], $comName);
                        }
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        echo "component # $cCtr: $comName<br>";

                        $dSpec = $registryGates[$srcGateName];
                        $tmpOutParams = array();
                        if (isset($tComSpec['loop'])) {
                            foreach ($tComSpec['loop'] as $key => $value) {
                                if (substr($key, 0, 1) == "{") {
                                    $key = trim($key, "{");
                                    $key = trim($key, "}");
                                    $key = str_replace($key, $registryGates[$srcGateName][$key], $key);
                                }
                                $realValue = makeValue($value, $registryGates[$srcGateName], $registryGates[$srcGateName], 0);
                                $tmpOutParams['loop'][$key] = $realValue;
                            }
                        }
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {

                                $realValue = makeValue($value, $registryGates[$srcGateName], $registryGates[$srcGateName], 0);
                                $tmpOutParams['static'][$key] = $realValue;

                            }
                            if (!isset($tmpOutParams['static']["transaksi_id"])) {
                                $tmpOutParams['static']["transaksi_id"] = $insertID;
                            }
                            if (!isset($tmpOutParams['static']["transaksi_no"])) {
                                $tmpOutParams['static']["transaksi_no"] = $insertNum;
                            }
                            $tmpOutParams['static']["urut"] = $cCtr;
                            $tmpOutParams['static']["fulldate"] = $fulldate;
                            $tmpOutParams['static']["dtime"] = $dtime;
                            $tmpOutParams['static']["keterangan"] = $configUi['steps'][$stepNumber]['label'] . " nomor " . $tmpNomorNota . " oleh " . $olehNama;


                        }

                        if (isset($tComSpec['static2'])) {
                            //cekHere("DISINI OIII");
                            foreach ($tComSpec['static2'] as $key => $value) {

                                $realValue = makeValue($value, $registryGates[$srcGateName][$cCtr], $registryGates[$srcGateName][$cCtr], 0);
                                $tmpOutParams['static2'][$key] = $realValue;

                            }
                            if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                                $tmpOutParams['static2']["transaksi_id"] = $insertID;
                            }
                            if (!isset($tmpOutParams['static2']["transaksi_no"])) {
                                $tmpOutParams['static2']["transaksi_no"] = $insertNum;
                            }

                            $tmpOutParams['static2']["fulldate"] = $fulldate;
                            $tmpOutParams['static2']["dtime"] = $dtime;
                            $tmpOutParams['static2']["keterangan"] = $configUi['steps'][$stepNumber]['label'] . " nomor " . $tmpNomorNota . " oleh " . $olehNama;


                        }


                        $mdlName = "Com" . ucfirst($comName);
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();

                        //===filter value nol, jika harus difilter
                        $tobeExecuted = true;

                        if (in_array($mdlName, $compValidators)) {

                            $loopParams = isset($tmpOutParams['loop']) ? $tmpOutParams['loop'] : array();
                            if (sizeof($loopParams) > 0) {
                                foreach ($loopParams as $key => $val) {
                                    cekmerah("$comName : $key = $val ");
                                    if ($val == 0) {
                                        unset($tmpOutParams['loop'][$key]);
                                    }
                                }
                            }
                            if (sizeof($tmpOutParams['loop']) < 1) {
                                $tobeExecuted = false;
                            }

                        }

                        if ($tobeExecuted) {
                            $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        }

                        $componentGate['master'][$cCtr] = $tmpOutParams;
                    }
                }
                else {
                    cekHitam("TIDAK ADA CORE MASTER");
                }


                //endregion

            }

            arrPrintCyan($registryGates["main"]);

            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                $jenisTr_con = $jenisTrMaster;

                $iterator = array(
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
//                            "1010030030" => "sub_produk_rel_harga",//persediaan produk
//                            "1010030030" => "sub_produk_rel_harga_persediaan",//persediaan produk
                            "1010030030" => "sub_diskon_supplier_nilai_netto",//persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "extern_id",
                            "extern_nama" => "extern_nama",
                            "produk_qty" => "qty",
//                            "produk_nilai" => "produk_rel_harga",
//                            "produk_nilai" => "produk_rel_harga_persediaan",
                            "produk_nilai" => "diskon_supplier_nilai_netto",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "supplierID" => "pihakID",
                        ),
                        "srcGateName" => "items5_sum",
                        "srcRawGateName" => "items5_sum",
                    ),

                );
                if (sizeof($iteratorrrr) > 0) {
                    $componentConfig['master'] = $iterator;
                    $it = 0;
                    $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $it++;
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        cekHere("component #$it: $comName :: $srcGateName <br>");

                        $dSpec = $registryGates[$srcGateName];
                        $tmpOutParams = array();
                        if (isset($tComSpec['loop'])) {
                            foreach ($tComSpec['loop'] as $key => $value) {
                                if (substr($key, 0, 1) == "{") {
                                    $key = trim($key, "{");
                                    $key = trim($key, "}");
                                    $key = str_replace($key, $registryGates[$srcGateName][$key], $key);
                                }
                                $realValue = makeValue($value, $registryGates[$srcGateName], $registryGates[$srcGateName], 0);
                                if ($key != null) {
                                    $tmpOutParams['loop'][$key] = $realValue;
                                }

                            }
                        }
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {

                                $realValue = makeValue($value, $registryGates[$srcGateName], $registryGates[$srcGateName], 0);
                                $tmpOutParams['static'][$key] = $realValue;
                                cekHijau(":: NORMAL :: $key => $realValue ::");
                            }
                            if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                cekHijau(":: masuk ke PATCHER ::");
                                foreach ($paramPatchers[$comName] as $k => $v) {
                                    cekHijau(":: ada yang mau di-PATCHER ::");
                                    arrPrint($tmpOutParams['static']);
                                    if (!isset($tmpOutParams['static'][$k])) {
                                        $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
                                        cekHijau(":: PATCHER :: $key => $realValue ::");
                                    }

                                }
                            }
                            else {
                                cekMerah(":: TIDAK TERMASUK PATCHER ::");
                            }
                            if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                $jenis = $registryGates['main']['jenis'];
                                foreach ($paramForceFillers[$comName] as $k => $v) {
                                    $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
                                    cekHijau(":: FORCEFILL :: $key => $realValue ::");
                                }
                            }
                            $tmpOutParams['static']["urut"] = $cCtr;
                            $tmpOutParams['static']["fulldate"] = $fulldate;
                            $tmpOutParams['static']["dtime"] = $dtime;
                            $tmpOutParams['static']["keterangan"] = $this->configUi[$jenisTr]['steps'][$stepNumber]['label'] . " nomor " . $tmpNomorNota . " oleh " . $olehNama;

                            $tmpOutParams['static']["rejection"] = true;

                        }
                        if (isset($tComSpec['static2'])) {
                            foreach ($tComSpec['static2'] as $key => $value) {

                                $realValue = makeValue($value, $registryGates[$srcGateName], $registryGates[$srcGateName], 0);
                                $tmpOutParams['static2'][$key] = $realValue;

                            }
                            if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                foreach ($paramPatchers[$comName] as $k => $v) {
                                    if (!isset($subParams['static'][$k])) {
                                        $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                    }
                                }
                            }
                            if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                $jenis = $registryGates['main']['jenis'];
                                foreach ($paramForceFillers[$comName] as $k => $v) {
                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                }
                            }
                            $tmpOutParams['static2']["fulldate"] = $fulldate;
                            $tmpOutParams['static2']["dtime"] = $dtime;
                            $tmpOutParams['static2']["keterangan"] = $this->configUi[$jenisTr]['steps'][$stepNumber]['label'] . " nomor " . $tmpNomorNota . " oleh " . $olehNama;


                        }

                        $mdlName = "Com" . ucfirst($comName);
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();
                        //===filter value nol, jika harus difilter
                        $tobeExecuted = true;

                        if (in_array($mdlName, $compValidators)) {

                            $loopParams = isset($tmpOutParams['loop']) ? $tmpOutParams['loop'] : array();
                            if (sizeof($loopParams) > 0) {
                                foreach ($loopParams as $key => $val) {
                                    cekmerah("$comName : $key = $val ");
                                    if ($val == 0) {
                                        unset($tmpOutParams['loop'][$key]);
                                    }
                                }
                            }
                            if (sizeof($tmpOutParams['loop']) < 1) {
                                $tobeExecuted = false;
                            }

                        }

                        if ($tobeExecuted) {
                            cekBiru("kiriman komponen $comName");
                            arrPrint($tmpOutParams);
                            $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        }
                        else {
                            cekBiru("komponem $comName tidak memenuhi syarat untuk ditulis");
                        }

                    }

                    $pakai_ini = 0;
                    if ($pakai_ini == 1) {
                        $this->load->model("Mdls/MdlPaymentUangMuka");
                        $l = new MdlPaymentUangMuka();
                        $l->addFilter("extern_id='" . $registryGates['main']['pihakID'] . "'");
                        $l->addFilter("extern_label2='customer'");
                        $l->addFilter("label='uang muka konsumen'");
                        $l->addFilter("cabang_id='" . $registryGates['main']['placeID'] . "'");
                        $tmpUm = $l->lookupAll()->result();
                        if (sizeof($tmpUm) > 0) {
                            $preTagihan = $tmpUm[0]->tagihan;
                            $preSisa = $tmpUm[0]->sisa;
                            $newTahigan = $preTagihan + $nilai_bayar;
                            $newsisa = $preSisa + $nilai_bayar;
                            $update = array(
                                "tagihan" => $newTahigan,
                                "sisa" => $newsisa,
                            );
                            $where = array(
//                        "extern_id" => $registryGates['main'][$externSrc['id']],
//                        "extern_label2" => $externSrc['extLabel'],//pembeda vendor dan customer lihat di heTransaksi_misc ->uang muka
                                "id" => $tmpUm[0]->id,
                            );
                            $tr->updateUangMukaSrc($where, $update);
                        }
                        else {
                            //insertbaru brooo
                            $tr->writeUangMukaSrc($insertID, array(
                                "jenis" => $stepCode,
                                "target_jenis" => $uangMukaSrcConfig['jenisTarget'],
                                "reference_jenis" => $uangMukaSrcConfig['jenisSrc'],
                                "extern_id" => $registryGates['main']['pihakID'],
                                "extern_nama" => $registryGates['main']['pihakName'],
                                "nomer" => "",
                                "note" => "",
                                "label" => "uang muka konsumen",
                                "tagihan" => $nilai_bayar,
                                "terbayar" => 0,
                                "sisa" => $nilai_bayar,
                                "cabang_id" => $registryGates['main']['placeID'],
                                "cabang_nama" => $registryGates['main']['placeName'],
                                "oleh_id" => $this->session->login['id'],
                                "oleh_nama" => $this->session->login['nama'],
                                "dtime" => date("Y-m-d H:i:s"),
                                "fulldate" => date("Y-m-d"),
                                "extern_label2" => "customer",
                            ));
                        }
                        cekMerah($this->db->last_query());
                    }

                    // transaksi pembayarannya...
                    if ($paymentSrc[0]->transaksi_ref_id > 0) {
                        $tr = New MdlTransaksi();
                        $tr->setFilters(array());
                        $where = array(
                            "id" => $paymentSrc[0]->transaksi_ref_id,
                        );
                        $data = array(
                            "trash_4" => 1,
                            "cancel_id" => my_id(),
                            "cancel_name" => my_name(),
                            "cancel_dtime" => date("Y-m-d H:i:s"),
                            "deskripsi" => "reject $transaksiJenisLabel_reference nomer $transaksiNomer_reference",
                        );
                        $tr->updateData($where, $data);
                        showLast_query("orange");
                    }

                }
                else {
                    cekKuning("reject components iterator");
                }
                if (sizeof($iterator) > 0) {

                    $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                    $filterNeeded = false;
                    $arrRekeningLoop = array();
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName_orig = $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $loopRequire = isset($tComSpec['loopRequire']) ? $tComSpec['loopRequire'] : false;
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        echo "sub-component: $comName, $srcGateName, initializing values <br>";
                        $tmpOutParams[$cCtr] = array();
                        if (isset($registryGates[$srcGateName]) && sizeof($registryGates[$srcGateName]) > 0) {
                            foreach ($registryGates[$srcGateName] as $id => $dSpec) {
                                $comName = $comName_orig;
                                if (substr($comName, 0, 1) == "{") {
                                    $comName = trim($comName, "{");
                                    $comName = trim($comName, "}");
                                    $comName = str_replace($comName, $registryGates[$srcGateName][$id][$comName], $comName);
                                    $tComSpec['comName'] = $comName;
                                    $iterator[$cCtr]['comName'] = $comName;
                                }
                                $filterNeeded = false;
                                $mdlName = "Com" . ucfirst($comName);
                                if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                    $filterNeeded = true;
                                }
                                $subParams = array();
                                if (isset($tComSpec['loop'])) {
                                    foreach ($tComSpec['loop'] as $key => $value) {
                                        if (substr($key, 0, 1) == "{") {
                                            $key = trim($key, "{");
                                            $key = trim($key, "}");
                                            $key = str_replace($key, $registryGates[$srcGateName][$id][$key], $key);
                                        }

                                        $subComModel[$key] = $comName;

                                        $realValue = makeValue($value, $registryGates[$srcGateName][$id], $registryGates[$srcGateName][$id], 0);

                                        if (strlen($key) > 1) {
                                            $subParams['loop'][$key] = $realValue;
                                        }
                                        else {
                                            $subParams['loop'] = array();
                                        }

                                        // =================== =================== ===================
                                        if (!isset($arrRekeningLoop[$dSpec[$tComSpec['static']['cabang_id']]][$key])) {
                                            $arrRekeningLoop[$dSpec[$tComSpec['static']['cabang_id']]][$key] = 0;
                                        }
                                        $arrRekeningLoop[$dSpec[$tComSpec['static']['cabang_id']]][$key] += $realValue;
                                        if ($realValue != 0) {
                                            cekUngu(":: cetak loop $key => $realValue ::");
                                        }

                                        if ($filterNeeded) {
                                            if ($subParams['loop'][$key] == 0) {
                                                unset($subParams['loop'][$key]);

                                                // =================== =================== ===================
                                            }
                                        }
                                    }
                                }
                                if (isset($tComSpec['static'])) {
                                    foreach ($tComSpec['static'] as $key => $value) {

                                        $realValue = makeValue($value, $registryGates[$srcGateName][$id], $registryGates[$srcGateName][$id], 0);
//                                    $subParams['static'][$key] = $realValue;
                                        $subParams['static'][$key] = trim($realValue);
//                                cekKuning("STATIC: $key diisi dengan $realValue");
                                    }
                                    if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                        foreach ($paramPatchers[$comName] as $k => $v) {
                                            if (!isset($subParams['static'][$k])) {
                                                $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                                cekOrange("fill :: $comName :: $k ($v) => " . $subParams['static'][$k]);
                                            }
                                        }
                                    }
                                    if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {

                                        $jenis = $registryGates['main']['jenis'];
                                        foreach ($paramForceFillers[$comName] as $k => $v) {
                                            $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                            cekOrange("fillforce :: $comName :: $k ($v) => " . $subParams['static'][$k]);
                                        }
                                    }

                                    // tambahan custom gerbang saat simpan transaksi, tidak bisa ditambahkan di coTransaksiCore/coTransaksiValues
                                    if (isset($paramForceFillersJenisTR[$comName][$jenisTrMaster]) && sizeof($paramForceFillersJenisTR[$comName][$jenisTrMaster]) > 0) {
                                        foreach ($paramForceFillersJenisTR[$comName][$jenisTrMaster] as $k => $v) {
                                            $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                            cekorange(":: $k diisikan dengan " . $subParams['static'][$k]);
                                        }
                                    }
                                    $subParams['static']["fulldate"] = $fulldate;
                                    $subParams['static']["dtime"] = $dtime;
                                    $subParams['static']["keterangan"] = $configUi['steps'][$stepNumber]['label'] . " nomor " . $tmpNomorNota . " oleh " . $olehNama;
                                    //------
                                    $subParams['static']["reference_id"] = isset($dSpec["referenceID"]) ? $dSpec["referenceID"] : "";
                                    $subParams['static']["reference_nomer"] = isset($dSpec["referenceNomer"]) ? $dSpec["referenceNomer"] : "";
                                    $subParams['static']["reference_jenis"] = isset($dSpec["jenisTr_reference"]) ? $dSpec["jenisTr_reference"] : "";
                                    $subParams['static']["reference_id_top"] = isset($dSpec["referenceID_top"]) ? $dSpec["referenceID_top"] : "";
                                    $subParams['static']["reference_nomer_top"] = isset($dSpec["referenceNomer_top"]) ? $dSpec["referenceNomer_top"] : "";
                                    $subParams['static']["reference_jenis_top"] = isset($dSpec["pihakExternMasterID"]) ? $dSpec["pihakExternMasterID"] : "";
                                    //------
                                    if (strlen($revertedTarget) > 1) {
                                        $subParams['static']['reverted_target'] = $revertedTarget;
                                    }
                                }
                                if (sizeof($subParams) > 0) {
                                    if ($filterNeeded) {
                                        if (isset($subParams['loop']) && !empty($subParams['loop'])) {
                                            $tmpOutParams[$cCtr][] = $subParams;
                                        }
                                    }
                                    else {
                                        if (empty($subParams['loop']) && $loopRequire == true) {
                                            unset($tmpOutParams[$cCtr]);
                                        }
                                        else {
                                            $tmpOutParams[$cCtr][] = $subParams;
                                        }
                                    }
                                }
                            }
                            arrPrintHitam($subParams);
                            $componentGate['detail'][$cCtr] = $subParams;
                        }
                    }
                    arrPrintPink($tmpOutParams);
                    $it = 0;
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $it++;
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        if (isset($registryGates[$srcGateName]) && sizeof($registryGates[$srcGateName]) > 0) {
                            foreach ($registryGates[$srcGateName] as $id => $dSpec) {
                                if (substr($comName, 0, 1) == "{") {
                                    $comName = trim($comName, "{");
                                    $comName = trim($comName, "}");
                                    $comName = str_replace($comName, $registryGates[$srcGateName][$id][$comName], $comName);
//                            $tComSpec['comName'] = $comName;
//                            $iterator[$cCtr]['comName'] = $comName;
//
//
                                }
                            }
                        }
                        else {
                            $comName = NULL;
                        }
                        cekHere("::::: $comName ::::: $srcGateName :::::");


                        echo __LINE__ . " sub $cCtr component #$it: $comName, sending values**** <br>";

                        if ($comName != NULL) {

                            $mdlName = "Com" . ucfirst($comName);
                            $this->load->model("Coms/" . $mdlName);
                            $m = new $mdlName();

                            if (isset($tmpOutParams[$cCtr]) && sizeof($tmpOutParams[$cCtr]) > 0) {
                                $tobeExecuted = true;
                                cekUngu("MASUK TRUE");
                            }
                            else {
                                $tobeExecuted = false;
                                cekUngu("MASUK FALSE");
                            }

                            if ($tobeExecuted) {
                                $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $jenisTrMaster . "/" . __FUNCTION__ . "/" . __LINE__);
                                $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $jenisTrMaster . "/" . __FUNCTION__ . "/" . __LINE__);
                            }
                            else {
                                cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                            }

                        }
                    }

                }
                else {
                    cekMerah("subcomponents [detail] is not set");
                }
            }

            $stopDate = dtimeNow();


            cekHitam("--- MULAI VALIDATOR ---");
            $this->load->library("Validator");
            $vdt = New Validator();

            mati_disini("...cek MANUAL cli transaksi... rekening pembantu masuk disini (component detail)<br>start: $startDate<br>stop: $stopDate<br>butuh waktu: " . timeDiff($startDate, $stopDate));


            cekHijau("...tes cli transaksi... rekening pembantu masuk disini (component detail)<br>start: $startDate<br>stop: $stopDate<br>butuh waktu: " . timeDiff($startDate, $stopDate));
//            mati_disini("...tes cli transaksi... rekening pembantu masuk disini (component detail)<br>start: $startDate<br>stop: $stopDate<br>butuh waktu: " . timeDiff($startDate, $stopDate));


            $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");


        }
        else {
            $stopDate = dtimeNow();
            cekMerah(":: TIDAK ADA yang perlu di-CLI-kan ::
                    <br>start: $startDate<br>stop: $stopDate<br>butuh waktu: " . timeDiff($startDate, $stopDate));
        }

    }

    // patch insert ke tabel transaksi_efaktur
    public function run_patchFaktur()
    {
        $this->load->model("MdlTransaksi");
        $t = new MdlTransaksi();
        $t->setfilters(array());
        $t->addFilter("link_id='0'");
        $t->addFilter("trash_4='0'");
        $t->addFilter("jenis in ('110')");
        $t->addFilter("gunggungan_mode='1'");
        $tTmp = $t->lookupJoined_OLD()->result();
        showLast_query("kuning");
        cekHere(count($tTmp));

        $this->db->trans_start();


        foreach ($tTmp as $spec) {
//            arrPrint($spec);
            $data = array(
                "transaksi_id" => $spec->transaksi_id,
                "nomer" => $spec->nomer,
                "dtime" => $spec->dtime,
                "oleh_id" => $spec->oleh_id,
                "oleh_nama" => $spec->oleh_nama,
                "produk_id" => $spec->sub_referensi_id_4,
                "produk_nama" => $spec->sub_referensi_nama_4,
                "pihak_id" => $spec->sub_pihak_id,
                "pihak_nama" => $spec->sub_pihak_nama,
                "efaktur" => $spec->efaktur,
                "date_faktur" => $spec->efaktur_dtime,
                "jumlah" => 1,
            );
            $this->db->insert('transaksi_efaktur', $data);
            showLast_query("hijau");
//            break;
        }


//        mati_disini("...cek MANUAL cli transaksi... ");
        $this->db->trans_complete() or mati_disini("Gagal saat berusaha  commit transaction!");

    }

    public function run_patchFakturUpdate()
    {
        $this->load->model("MdlTransaksi");
        $t = new MdlTransaksi();
        $t->setfilters(array());
        $t->addFilter("link_id='0'");
        $t->addFilter("trash_4='1'");
        $t->addFilter("jenis in ('110')");
//        $t->addFilter("gunggungan_mode='1'");
        $tTmp = $t->lookupAll()->result();
        showLast_query("kuning");
        cekHere(count($tTmp));

        $this->db->trans_start();


        foreach ($tTmp as $spec) {
//            $data = array(
//                "transaksi_id" => $spec->transaksi_id,
//                "nomer" => $spec->nomer,
//                "dtime" => $spec->dtime,
//                "oleh_id" => $spec->oleh_id,
//                "oleh_nama" => $spec->oleh_nama,
//                "produk_id" => $spec->sub_referensi_id_4,
//                "produk_nama" => $spec->sub_referensi_nama_4,
//                "pihak_id" => $spec->sub_pihak_id,
//                "pihak_nama" => $spec->sub_pihak_nama,
//                "efaktur" => $spec->efaktur,
//                "date_faktur" => $spec->efaktur_dtime,
//                "jumlah" => 1,
//            );
//            $this->db->insert('transaksi_efaktur', $data);
//            showLast_query("hijau");
            $where = array(
                "transaksi_id" => $spec->id,
            );
            $this->db->where($where);
            $tmp = $this->db->get('transaksi_efaktur')->result();
            showLast_query("biru");
            if (sizeof($tmp) > 0) {
                $data_update = array(
                    "jumlah" => "0",
                );
                $this->db->where('transaksi_id', $spec->id);
                $this->db->update('transaksi_efaktur', $data_update);
                showLast_query("orange");
            }

//            break;
        }


        mati_disini("...cek MANUAL cli transaksi... ");
        $this->db->trans_complete() or mati_disini("Gagal saat berusaha  commit transaction!");

    }


    public function run_biayaProjectSusulan()
    {
        $this->load->model("MdlTransaksi");
        $this->load->model("Coms/ComJurnal");
        $this->load->model("CustomCounter");

        $arrTrid = array(
//            677294,
//            677298,
//            680509,
//            681802,
//            681806,
            683575
        );

        $this->db->trans_start();


        $tr = New MdlTransaksi();
        $tr->addFilter("id in ('" . implode("','", $arrTrid) . "')");
        $trTmp = $tr->lookupAll()->result();
        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $spec) {
                $trID_cli = $spec->id;
                $trTmpCabangID = $spec->cabang_id;
                $kolom = array(
                    "trID" => "id",
                    "jenisTr" => "jenis",
                    "jenisTrMaster" => "jenis_master",
                    "jenisTrTop" => "jenis_top",
                    "nomer" => "nomer",
                    "nomerTop" => "nomer_top",
                    "dtime" => "dtime",
                    "fulldate" => "fulldate",
                    "stepNumber" => "step_number",
                    "indexRegistry" => "indexing_registry",
                    "olehID" => "oleh_id",
                    "olehNama" => "oleh_nama",
                );
                $arrKolomTrans = array();
                foreach ($kolom as $key => $val) {
                    $arrKolomTrans[$key] = isset($spec->$val) ? $spec->$val : NULL;
                }


                $reg = New MdlTransaksi();
                $reg->setFilters(array());
                $reg->addFilter("transaksi_id=$trID_cli");
                $regTmp = $reg->lookupDataRegistries()->result();
                $registryGates = array();
                foreach ($regTmp as $regSpec) {
                    foreach ($regSpec as $key_reg => $val_reg) {
                        if ($key_reg != "transaksi_id") {
                            $registryGates[$key_reg] = blobDecode($val_reg);
                        }
                    }
                }

                if (sizeof($registryGates["items2"]) > 0) {
                    $biaya_tambahan = 0;
                    foreach ($registryGates["items2"] as $ii => $iiSpec) {
                        foreach ($iiSpec as $iii => $iiiSpec) {
                            $registryGates["items2"][$ii][$iii]["harga_tambahan"] = $iiiSpec["harga"];
                            $registryGates["items2"][$ii][$iii]["biaya_tambahan"] = $iiiSpec["harga"] * $iiiSpec["jml"];
                            $biaya_tambahan += $iiiSpec["harga"] * $iiiSpec["jml"];
                        }
                    }
                    $registryGates["main"]["piutang_tambah"] = ($registryGates["main"]["type_pelaksana_txt"] == "vendor") ? $biaya_tambahan : 0;
                }

                $jenisTr = $arrKolomTrans['jenisTr'];
                $jenisTrMaster = $arrKolomTrans['jenisTrMaster'];
                $fulldate = $arrKolomTrans['fulldate'];
                $dtime = $arrKolomTrans['dtime'];
                $stepNum = $stepNumber = $arrKolomTrans['stepNumber'];
                $insertNum = $tmpNomorNota = $arrKolomTrans['nomer'];
                $olehID = $arrKolomTrans['olehID'];
                $olehNama = $arrKolomTrans['olehNama'];
                $insertID = $transaksiID = $arrKolomTrans['trID'];
                /*---------------------- jenismaster untuk gerbang utama masuk modul, jenisTr adalah targetnya */
                /*------end*/
                $configCore = loadConfigModulJenis_he_misc($jenisTrMaster, "coTransaksiCore");
                $configUi = loadConfigModulJenis_he_misc($jenisTrMaster, "coTransaksiUi");
                $configLayout = loadConfigModulJenis_he_misc($jenisTrMaster, "coTransaksiLayout");

                $paramPatchers = $this->config->item('heTransaksi_paramPatchers') != null ? $this->config->item('heTransaksi_paramPatchers') : array();
                $paramForceFillers = $this->config->item('heTransaksi_paramForceFillers') != null ? $this->config->item('heTransaksi_paramForceFillers') : array();
                $validateSubComponent = $this->config->item('heTransaksi_validateComponentDetail') != null ? $this->config->item('heTransaksi_validateComponentDetail') : array();
                $paramForceFillersJenisTR = $this->config->item('heTransaksi_paramForceFillers_jenisTR') != null ? $this->config->item('heTransaksi_paramForceFillers_jenisTR') : array();
                $cliComponent = "components";

                cekHitam(":: jenisTrMaster-> $jenisTrMaster :: jenisTr-> $jenisTr :: [trID_cli: $trID_cli]");

                arrPrint($configCore);
                arrPrintWebs($registryGates["items2"]);

                $componentGate['detail'] = array();
                $componentConfig['master'] = array();
                $componentConfig['detail'] = array();
                if (isset($configCore['relativeComponets']) && $configCore['relativeComponets'] == true) {
                    $iterator = isset($registryGates['revert']['jurnal']['detail']) ? $registryGates['revert']['jurnal']['detail'] : array();
                    $revertedTarget = $registryGates['main']['pihakExternID'];
                    $componentConfig['detail'] = $iterator;
                    $iteratorMaster = $componentConfig['master'] = isset($registryGates['revert']['jurnal']['master']) ? $registryGates['revert']['jurnal']['master'] : array();
                }
                else {
                    $iterator_sub = isset($configCore[$cliComponent][$jenisTr]['sub_detail']) ? $configCore[$cliComponent][$jenisTr]['sub_detail'] : array();
                    $iterator = isset($configCore[$cliComponent][$jenisTr]['detail']) ? $configCore[$cliComponent][$jenisTr]['detail'] : array();
                    $componentConfig['detail'] = $iterator;
                    $iteratorMaster = $componentConfig['master'] = isset($configCore[$cliComponent][$jenisTr]['master']) ? $configCore[$cliComponent][$jenisTr]['master'] : array();
                    $revertedTarget = "";
                }

                $subComModel = array();
                // region komponent detail
                if (sizeof($iterator) > 0) {
                    $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                    $filterNeeded = false;
                    $arrRekeningLoop = array();
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName_orig = $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $loopRequire = isset($tComSpec['loopRequire']) ? $tComSpec['loopRequire'] : false;
                        $srcRawGateName = $tComSpec['srcRawGateName'];

                        echo "sub-component: $comName, $srcGateName, initializing values <br>";

                        $tmpOutParams[$cCtr] = array();
                        if (isset($registryGates[$srcGateName]) && sizeof($registryGates[$srcGateName]) > 0) {
                            foreach ($registryGates[$srcGateName] as $id => $dSpec) {
                                $comName = $comName_orig;
                                if (substr($comName, 0, 1) == "{") {
                                    $comName = trim($comName, "{");
                                    $comName = trim($comName, "}");
                                    $comName = str_replace($comName, $registryGates[$srcGateName][$id][$comName], $comName);
                                    $tComSpec['comName'] = $comName;
                                    $iterator[$cCtr]['comName'] = $comName;
                                }

                                $filterNeeded = false;
                                $mdlName = "Com" . ucfirst($comName);
                                if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                    $filterNeeded = true;
                                }

                                $subParams = array();
                                if (isset($tComSpec['loop'])) {
                                    foreach ($tComSpec['loop'] as $key => $value) {
                                        if (substr($key, 0, 1) == "{") {
                                            $key = trim($key, "{");
                                            $key = trim($key, "}");
                                            $key = str_replace($key, $registryGates[$srcGateName][$id][$key], $key);
                                        }

                                        $subComModel[$key] = $comName;

                                        $realValue = makeValue($value, $registryGates[$srcGateName][$id], $registryGates[$srcGateName][$id], 0);

                                        if (strlen($key) > 1) {
                                            $subParams['loop'][$key] = $realValue;
                                        }
                                        else {
                                            $subParams['loop'] = array();
                                        }

                                        // =================== =================== ===================
                                        if (!isset($arrRekeningLoop[$dSpec[$tComSpec['static']['cabang_id']]][$key])) {
                                            $arrRekeningLoop[$dSpec[$tComSpec['static']['cabang_id']]][$key] = 0;
                                        }
                                        $arrRekeningLoop[$dSpec[$tComSpec['static']['cabang_id']]][$key] += $realValue;
                                        if ($realValue != 0) {
                                            cekUngu(":: cetak loop $key => $realValue ::");
                                        }

                                        if ($filterNeeded) {
                                            if ($subParams['loop'][$key] == 0) {
                                                unset($subParams['loop'][$key]);

                                                // =================== =================== ===================
                                            }
                                        }
                                    }
                                }
                                if (isset($tComSpec['static'])) {
                                    foreach ($tComSpec['static'] as $key => $value) {

                                        $realValue = makeValue($value, $registryGates[$srcGateName][$id], $registryGates[$srcGateName][$id], 0);
//                                    $subParams['static'][$key] = $realValue;
                                        $subParams['static'][$key] = trim($realValue);
//                                cekKuning("STATIC: $key diisi dengan $realValue");
                                    }
                                    if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                        foreach ($paramPatchers[$comName] as $k => $v) {
                                            if (!isset($subParams['static'][$k])) {
                                                $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                                cekOrange("fill :: $comName :: $k ($v) => " . $subParams['static'][$k]);
                                            }
                                        }
                                    }
                                    if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {

                                        $jenis = $registryGates['main']['jenis'];
                                        foreach ($paramForceFillers[$comName] as $k => $v) {
                                            $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                            cekOrange("fillforce :: $comName :: $k ($v) => " . $subParams['static'][$k]);
                                        }
                                    }
//                                arrPrintWebs($paramForceFillersJenisTR[$comName]);
//                                cekMerah($jenisTrMaster);
                                    // tambahan custom gerbang saat simpan transaksi, tidak bisa ditambahkan di coTransaksiCore/coTransaksiValues
                                    if (isset($paramForceFillersJenisTR[$comName][$jenisTrMaster]) && sizeof($paramForceFillersJenisTR[$comName][$jenisTrMaster]) > 0) {
                                        foreach ($paramForceFillersJenisTR[$comName][$jenisTrMaster] as $k => $v) {
                                            $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                            cekorange(":: $k diisikan dengan " . $subParams['static'][$k]);
                                        }
                                    }
                                    $subParams['static']["fulldate"] = $fulldate;
                                    $subParams['static']["dtime"] = $dtime;
                                    $subParams['static']["keterangan"] = $configUi['steps'][$stepNumber]['label'] . " nomor " . $tmpNomorNota . " oleh " . $olehNama;
                                    //------
                                    $subParams['static']["reference_id"] = isset($dSpec["referenceID"]) ? $dSpec["referenceID"] : "";
                                    $subParams['static']["reference_nomer"] = isset($dSpec["referenceNomer"]) ? $dSpec["referenceNomer"] : "";
                                    $subParams['static']["reference_jenis"] = isset($dSpec["jenisTr_reference"]) ? $dSpec["jenisTr_reference"] : "";
                                    $subParams['static']["reference_id_top"] = isset($dSpec["referenceID_top"]) ? $dSpec["referenceID_top"] : "";
                                    $subParams['static']["reference_nomer_top"] = isset($dSpec["referenceNomer_top"]) ? $dSpec["referenceNomer_top"] : "";
                                    $subParams['static']["reference_jenis_top"] = isset($dSpec["pihakExternMasterID"]) ? $dSpec["pihakExternMasterID"] : "";
                                    //------
                                    if (strlen($revertedTarget) > 1) {
                                        $subParams['static']['reverted_target'] = $revertedTarget;
                                    }
                                }
                                if (sizeof($subParams) > 0) {
                                    if ($filterNeeded) {
                                        if (isset($subParams['loop']) && !empty($subParams['loop'])) {
                                            $tmpOutParams[$cCtr][] = $subParams;
                                        }
                                    }
                                    else {
                                        if (empty($subParams['loop']) && $loopRequire == true) {
                                            unset($tmpOutParams[$cCtr]);
                                        }
                                        else {
                                            $tmpOutParams[$cCtr][] = $subParams;
                                        }
                                    }
                                }
                            }

                            $componentGate['detail'][$cCtr] = $subParams;
                        }

                    }
                    $it = 0;
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $it++;
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        if (isset($registryGates[$srcGateName]) && sizeof($registryGates[$srcGateName]) > 0) {
                            foreach ($registryGates[$srcGateName] as $id => $dSpec) {
                                if (substr($comName, 0, 1) == "{") {
                                    $comName = trim($comName, "{");
                                    $comName = trim($comName, "}");
                                    $comName = str_replace($comName, $registryGates[$srcGateName][$id][$comName], $comName);
//                            $tComSpec['comName'] = $comName;
//                            $iterator[$cCtr]['comName'] = $comName;
//
//
                                }
                            }
                        }
                        else {
                            $comName = NULL;
                        }
                        cekHere("::::: $comName ::::: $srcGateName :::::");


                        echo __LINE__ . " sub $cCtr component #$it: $comName, sending values**** <br>";

                        if ($comName != NULL) {
//cekHere(":: $comName ::");
                            $mdlName = "Com" . ucfirst($comName);
                            $this->load->model("Coms/" . $mdlName);
                            $m = new $mdlName();

                            if (isset($tmpOutParams[$cCtr]) && sizeof($tmpOutParams[$cCtr]) > 0) {
                                $tobeExecuted = true;
                            }
                            else {
                                $tobeExecuted = false;
                            }

                            if ($tobeExecuted) {
                                $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $jenisTrMaster . "/" . __FUNCTION__ . "/" . __LINE__);
                                $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $jenisTrMaster . "/" . __FUNCTION__ . "/" . __LINE__);
                            }
                            else {
                                cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                            }

                        }
                    }
                }
                else {
                    cekMerah("subcomponents [detail] is not set");
                }
                // endregion komponent detail

                // region komponent sub_detail
                if (sizeof($iterator_sub) > 0) {
                    $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                    $filterNeeded = false;
                    $arrRekeningLoop = array();
                    foreach ($iterator_sub as $cCtr => $tComSpec) {
                        $comName_orig = $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $loopRequire = isset($tComSpec['loopRequire']) ? $tComSpec['loopRequire'] : false;
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        echo "sub-component: $comName, $srcGateName, initializing values <br>";
                        $tmpOutParams[$cCtr] = array();
                        if (isset($registryGates[$srcGateName]) && sizeof($registryGates[$srcGateName]) > 0) {

                            foreach ($registryGates[$srcGateName] as $id => $ddSpec) {
                                foreach ($ddSpec as $dID => $dSpec) {
                                    $comName = $comName_orig;
                                    if (substr($comName, 0, 1) == "{") {
                                        $comName = trim($comName, "{");
                                        $comName = trim($comName, "}");
                                        $comName = str_replace($comName, $registryGates[$srcGateName][$id][$comName], $comName);
                                        $tComSpec['comName'] = $comName;
                                        $iterator[$cCtr]['comName'] = $comName;
                                    }
                                    $filterNeeded = false;
                                    $mdlName = "Com" . ucfirst($comName);
                                    if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                        $filterNeeded = true;
                                    }


                                    $subParams = array();
                                    if (isset($tComSpec['loop'])) {
                                        foreach ($tComSpec['loop'] as $key => $value) {
                                            if (substr($key, 0, 1) == "{") {
                                                $key = trim($key, "{");
                                                $key = trim($key, "}");
                                                $key = str_replace($key, $registryGates[$srcGateName][$id][$dID][$key], $key);
                                            }

                                            $subComModel[$key] = $comName;

                                            $realValue = makeValue($value, $registryGates[$srcGateName][$id][$dID], $registryGates[$srcGateName][$id][$dID], 0);

                                            if (strlen($key) > 1) {
                                                $subParams['loop'][$key] = $realValue;
                                            }
                                            else {
                                                $subParams['loop'] = array();
                                            }

                                            // =================== =================== ===================
                                            if (!isset($arrRekeningLoop[$dSpec[$tComSpec['static']['cabang_id']]][$key])) {
                                                $arrRekeningLoop[$dSpec[$tComSpec['static']['cabang_id']]][$key] = 0;
                                            }
                                            $arrRekeningLoop[$dSpec[$tComSpec['static']['cabang_id']]][$key] += $realValue;
                                            if ($realValue != 0) {
                                                cekUngu(":: cetak loop $key => $realValue ::");
                                            }

                                            if ($filterNeeded) {
                                                if ($subParams['loop'][$key] == 0) {
                                                    unset($subParams['loop'][$key]);

                                                    // =================== =================== ===================
                                                }
                                            }
                                        }
                                    }
                                    if (isset($tComSpec['static'])) {
                                        foreach ($tComSpec['static'] as $key => $value) {

                                            $realValue = makeValue($value, $registryGates[$srcGateName][$id][$dID], $registryGates[$srcGateName][$id][$dID], 0);
                                            $subParams['static'][$key] = $realValue;
//                                cekKuning("STATIC: $key diisi dengan $realValue");
                                        }
                                        if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                            foreach ($paramPatchers[$comName] as $k => $v) {
                                                if (!isset($subParams['static'][$k])) {
                                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                                    cekOrange("fill :: $comName :: $k ($v) => " . $subParams['static'][$k]);
                                                }
                                            }
                                        }
                                        if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {

                                            $jenis = $registryGates['main']['jenis'];
                                            foreach ($paramForceFillers[$comName] as $k => $v) {
                                                $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                                cekOrange("fillforce :: $comName :: $k ($v) => " . $subParams['static'][$k]);
                                            }
                                        }
                                        $subParams['static']["fulldate"] = $fulldate;
                                        $subParams['static']["dtime"] = $dtime;
                                        $subParams['static']["keterangan"] = $configUi['steps'][$stepNumber]['label'] . " nomor " . $tmpNomorNota . " oleh " . $olehNama;
                                        //------
                                        $subParams['static']["reference_id"] = isset($dSpec["referenceID"]) ? $dSpec["referenceID"] : "";
                                        $subParams['static']["reference_nomer"] = isset($dSpec["referenceNomer"]) ? $dSpec["referenceNomer"] : "";
                                        $subParams['static']["reference_jenis"] = isset($dSpec["jenisTr_reference"]) ? $dSpec["jenisTr_reference"] : "";
                                        $subParams['static']["reference_id_top"] = isset($dSpec["referenceID_top"]) ? $dSpec["referenceID_top"] : "";
                                        $subParams['static']["reference_nomer_top"] = isset($dSpec["referenceNomer_top"]) ? $dSpec["referenceNomer_top"] : "";
                                        $subParams['static']["reference_jenis_top"] = isset($dSpec["pihakExternMasterID"]) ? $dSpec["pihakExternMasterID"] : "";
                                        //------
                                        if (strlen($revertedTarget) > 1) {
                                            $subParams['static']['reverted_target'] = $revertedTarget;
                                        }
                                    }
                                    if (sizeof($subParams) > 0) {
                                        if ($filterNeeded) {
                                            if (isset($subParams['loop']) && !empty($subParams['loop'])) {
                                                $tmpOutParams[$cCtr][] = $subParams;
                                            }
                                        }
                                        else {
                                            if (empty($subParams['loop']) && $loopRequire == true) {
                                                unset($tmpOutParams[$cCtr]);
                                            }
                                            else {
                                                $tmpOutParams[$cCtr][] = $subParams;
                                            }
                                        }
                                    }

                                }
                                $componentGate['sub_detail'][$cCtr] = $subParams;
                            }


                        }

                    }
                    $it = 0;
                    foreach ($iterator_sub as $cCtr => $tComSpec) {
                        $it++;
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        if (isset($registryGates[$srcGateName]) && sizeof($registryGates[$srcGateName]) > 0) {
                            foreach ($registryGates[$srcGateName] as $id => $ddSpec) {
                                foreach ($ddSpec as $ixx => $dSpec) {
                                    if (substr($comName, 0, 1) == "{") {
                                        $comName = trim($comName, "{");
                                        $comName = trim($comName, "}");
                                        $comName = str_replace($comName, $registryGates[$srcGateName][$id][$ixx][$comName], $comName);
                                    }
                                }
                            }
                        }
                        else {
                            $comName = NULL;
                        }
                        cekHere("::::: $comName :::::");


                        echo __LINE__ . " sub $cCtr component #$it: $comName, sending values**** <br>";

                        if ($comName != NULL) {
                            cekHere(":: $comName ::");
                            $mdlName = "Com" . ucfirst($comName);
                            $this->load->model("Coms/" . $mdlName);
                            $m = new $mdlName();
                            if (isset($tmpOutParams[$cCtr]) && sizeof($tmpOutParams[$cCtr]) > 0) {
                                $tobeExecuted = true;
                            }
                            else {
                                $tobeExecuted = false;
                            }
                            arrPrintPink($tmpOutParams[$cCtr]);
                            if ($tobeExecuted) {
                                $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $jenisTrMaster . "/" . __FUNCTION__ . "/" . __LINE__);
                                $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $jenisTrMaster . "/" . __FUNCTION__ . "/" . __LINE__);
                            }
                            else {
                                cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                            }
                        }
                    }
                    // validasi rekening besar vs rekening pembantu
//                    validateBalancesComparison($trTmpCabangID, $componentGate, $componentConfig, "detail", $transaksiID, $tmpNomorNota);

                }
                else {
                    cekMerah("subcomponents [sub_detail] is not set");
                }
                // endregion komponent detail

                // region komponent master
                if (sizeof($iteratorMaster) > 0) {
                    $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                    $componentConfig['master'] = $iteratorMaster;
                    $cCtr = 0;
                    foreach ($iteratorMaster as $cCtr => $tComSpec) {
                        $cCtr++;
                        $comName = $tComSpec['comName'];
                        if (substr($comName, 0, 1) == "{") {
                            $comName = trim($comName, "{");
                            $comName = trim($comName, "}");
                            $comName = str_replace($comName, $registryGates[$srcGateName][$comName], $comName);
                        }
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        echo "component # $cCtr: $comName<br>";

                        $dSpec = $registryGates[$srcGateName];
                        $tmpOutParams = array();
                        if (isset($tComSpec['loop'])) {
                            foreach ($tComSpec['loop'] as $key => $value) {
                                if (substr($key, 0, 1) == "{") {
                                    $key = trim($key, "{");
                                    $key = trim($key, "}");
                                    $key = str_replace($key, $registryGates[$srcGateName][$key], $key);
                                }
                                $realValue = makeValue($value, $registryGates[$srcGateName], $registryGates[$srcGateName], 0);
                                $tmpOutParams['loop'][$key] = $realValue;
                            }
                        }
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {

                                $realValue = makeValue($value, $registryGates[$srcGateName], $registryGates[$srcGateName], 0);
                                $tmpOutParams['static'][$key] = $realValue;

                            }
                            if (!isset($tmpOutParams['static']["transaksi_id"])) {
                                $tmpOutParams['static']["transaksi_id"] = $insertID;
                            }
                            if (!isset($tmpOutParams['static']["transaksi_no"])) {
                                $tmpOutParams['static']["transaksi_no"] = $insertNum;
                            }
                            $tmpOutParams['static']["urut"] = $cCtr;
                            $tmpOutParams['static']["fulldate"] = $fulldate;
                            $tmpOutParams['static']["dtime"] = $dtime;
                            $tmpOutParams['static']["keterangan"] = $configUi['steps'][$stepNumber]['label'] . " nomor " . $tmpNomorNota . " oleh " . $olehNama;


                        }
                        if (isset($tComSpec['static2'])) {
                            //cekHere("DISINI OIII");
                            foreach ($tComSpec['static2'] as $key => $value) {

                                $realValue = makeValue($value, $registryGates[$srcGateName][$cCtr], $registryGates[$srcGateName][$cCtr], 0);
                                $tmpOutParams['static2'][$key] = $realValue;

                            }
                            if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                                $tmpOutParams['static2']["transaksi_id"] = $insertID;
                            }
                            if (!isset($tmpOutParams['static2']["transaksi_no"])) {
                                $tmpOutParams['static2']["transaksi_no"] = $insertNum;
                            }

                            $tmpOutParams['static2']["fulldate"] = $fulldate;
                            $tmpOutParams['static2']["dtime"] = $dtime;
                            $tmpOutParams['static2']["keterangan"] = $configUi['steps'][$stepNumber]['label'] . " nomor " . $tmpNomorNota . " oleh " . $olehNama;


                        }

                        $mdlName = "Com" . ucfirst($comName);
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();
                        //===filter value nol, jika harus difilter
                        $tobeExecuted = true;

                        if (in_array($mdlName, $compValidators)) {

                            $loopParams = isset($tmpOutParams['loop']) ? $tmpOutParams['loop'] : array();
                            if (sizeof($loopParams) > 0) {
                                foreach ($loopParams as $key => $val) {
                                    cekmerah("$comName : $key = $val ");
                                    if ($val == 0) {
                                        unset($tmpOutParams['loop'][$key]);
                                    }
                                }
                            }
                            if (sizeof($tmpOutParams['loop']) < 1) {
                                $tobeExecuted = false;
                            }

                        }

                        if ($tobeExecuted) {
                            $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        }

                        $componentGate['master'][$cCtr] = $tmpOutParams;
                    }
                }
                else {
                    cekHitam("TIDAK ADA CORE MASTER");
                }
                // endregion komponent master


                //region nulis paymentSource
                $stepCode = $configUi['steps'][$stepNum]['target'];
                $paymentSources = $this->config->item("payment_source");
                if (array_key_exists($stepCode, $paymentSources)) {
                    $payConfigs = isset($paymentSources[$stepCode][$stepNum]) ? $paymentSources[$stepCode][$stepNum] : array();
                    if (sizeof($payConfigs) > 0) {
                        foreach ($payConfigs as $paymentSrcConfig) {
                            $valueLabel = isset($paymentSrcConfig['label_key']) ? $paymentSrcConfig['label_key'] : $paymentSrcConfig['label'];
                            $valueSrc = $paymentSrcConfig['valueSrc'];
                            $externSrc = $paymentSrcConfig['externSrc'];
                            $valueAdd = isset($registryGates['main'][$paymentSrcConfig['addValueValidator']]) ? $registryGates['main'][$paymentSrcConfig['addValueValidator']] : 0;
                            if (isset($paymentSrcConfig['model'])) {
                                $mdlName = $paymentSrcConfig['model'];
                                $this->load->model("Mdls/$mdlName");
                                $pMdl = New $mdlName();
                                $pTmpMdl = $pMdl->lookupAll()->result();
                                $pTmpMdlResult = array();
                                if (sizeof($pTmpMdl) > 0) {
                                    foreach ($pTmpMdl as $pTmpMdlSpec) {
                                        $pTmpMdlResult[$pTmpMdlSpec->id] = $pTmpMdlSpec;
                                    }
                                }
                            }
                            else {
                                $pTmpMdlResult = array();
                            }

                            if (isset($registryGates['main'][$valueSrc]) && $registryGates['main'][$valueSrc] > 0) {
                                if (isset($externSrc['extern_label2'])) {
                                    //cek ada isinya atau kosong
                                    $cek = strlen($registryGates['main'][$externSrc['extern_label2']]) > 4 ? "" : matiHere("jenis biaya tidak dikenali " . __LINE__);//
                                }
                                //region cek duplikasi paymentsource
                                $tr->setFilters(array());
                                $tr->addFilter("transaksi_id='$insertID'");
                                $tr->addFilter("target_jenis='" . $paymentSrcConfig['jenisTarget'] . "'");
                                // $tr->addFilter("target_jenis='759'");
                                $validateIsInserted = $tr->lookUpAllPaymentSrc()->result();
                                if (sizeof($validateIsInserted) > 0) {
                                    matiHEre("Gagal menulis transaksi. Silahkan relogin untuk membersihkan sesi demi menghindari duplikasi data, dan coba kembali transaksi yang gagal");
                                }
                                //endregion

                                //-----------------------
                                cekHitam("valuelabel: $valueLabel, valueSrc: $valueSrc");
//                                $this->load->helper("he_payment_source");
                                //                        paymentSource($this->jenisTr, $componentJurnal, $registryGates['main'], $valueLabel, $valueSrc, $valueAdd);
                                //-----------------------

                                $arrPymSrc = array(
                                    "jenis" => $stepCode,
                                    "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                    "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                    "extern_id" => isset($registryGates['main'][$externSrc['id']]) ? $registryGates['main'][$externSrc['id']] : "",
                                    "extern_nama" => isset($registryGates['main'][$externSrc['nama']]) ? $registryGates['main'][$externSrc['nama']] : "",
                                    "nomer" => $tmpNomorNota,
                                    "label" => $paymentSrcConfig['label'],
                                    "tagihan" => $registryGates['main'][$valueSrc],
                                    "terbayar" => 0,
                                    "sisa" => $registryGates['main'][$valueSrc],
                                    "cabang_id" => isset($externSrc['cabang_id']) && isset($registryGates['main'][$externSrc['cabang_id']]) ? $registryGates['main'][$externSrc['cabang_id']] : $registryGates['main']['placeID'],
                                    "cabang_nama" => isset($externSrc['cabang_nama']) && isset($registryGates['main'][$externSrc['cabang_nama']]) ? $registryGates['main'][$externSrc['cabang_nama']] : $registryGates['main']['placeName'],
                                    "oleh_id" => $olehID,
                                    "oleh_nama" => $olehNama,
                                    "dtime" => $dtime,
                                    "fulldate" => $fulldate,
                                    "valas_id" => isset($externSrc['valasId']) && isset($registryGates['main'][$externSrc['valasId']]) ? $registryGates['main'][$externSrc['valasId']] : '',
                                    "valas_nama" => isset($externSrc['valasLabel']) && isset($registryGates['main'][$externSrc['valasLabel']]) ? $registryGates['main'][$externSrc['valasLabel']] : '',
                                    "valas_nilai" => isset($externSrc['valasValue']) && isset($registryGates['main'][$externSrc['valasValue']]) ? $registryGates['main'][$externSrc['valasValue']] : '',
                                    "tagihan_valas" => isset($externSrc['valasTagihan']) && isset($registryGates['main'][$externSrc['valasTagihan']]) ? $registryGates['main'][$externSrc['valasTagihan']] : '',
                                    "terbayar_valas" => 0,
                                    "sisa_valas" => isset($externSrc['valasSisa']) && isset($registryGates['main'][$externSrc['valasSisa']]) ? $registryGates['main'][$externSrc['valasSisa']] : '',
                                    "extern_label2" => (isset($externSrc['extern_label2']) && ($registryGates['main'][$externSrc['extern_label2']])) ? $registryGates['main'][$externSrc['extern_label2']] : "",
                                    "dpp_ppn" => (isset($externSrc['dpp_ppn']) && ($registryGates['main'][$externSrc['dpp_ppn']])) ? $registryGates['main'][$externSrc['dpp_ppn']] : 0,
                                    "ppn" => (isset($externSrc['ppn']) && ($registryGates['main'][$externSrc['ppn']])) ? $registryGates['main'][$externSrc['ppn']] : 0,
                                    "ppn_approved" => (isset($externSrc['ppn_approved']) && ($registryGates['main'][$externSrc['ppn_approved']])) ? $registryGates['main'][$externSrc['ppn_approved']] : 0,
                                    "ppn_sisa" => (isset($externSrc['ppn']) && ($registryGates['main'][$externSrc['ppn']])) ? $registryGates['main'][$externSrc['ppn']] : "",
                                    "ppn_status" => (isset($externSrc['ppn_status'])) ? $externSrc['ppn_status'] : 0,
                                    "extern_nilai2" => (isset($externSrc['extern_nilai2']) && ($registryGates['main'][$externSrc['extern_nilai2']])) ? $registryGates['main'][$externSrc['extern_nilai2']] : 0,
                                    "extern_date2" => (isset($externSrc['extern_date2']) && ($registryGates['main'][$externSrc['extern_date2']])) ? $registryGates['main'][$externSrc['extern_date2']] : "",
                                    "pph_23" => (isset($externSrc['pph_23']) && ($registryGates['main'][$externSrc['pph_23']])) ? $registryGates['main'][$externSrc['pph_23']] : "",
                                    "npwp" => (isset($externSrc['npwp']) && ($registryGates['main'][$externSrc['npwp']])) ? $registryGates['main'][$externSrc['npwp']] : "",
                                    "project_id" => isset($externSrc['project_id']) && isset($registryGates['main'][$externSrc['project_id']]) ? $registryGates['main'][$externSrc['project_id']] : "",
                                    "project_nama" => isset($externSrc['project_nama']) && isset($registryGates['main'][$externSrc['project_nama']]) ? $registryGates['main'][$externSrc['project_nama']] : "",
                                    "extern2_id" => (isset($externSrc['extern2_id']) && ($registryGates['main'][$externSrc['extern2_id']])) ? $registryGates['main'][$externSrc['extern2_id']] : "",
                                    "extern2_nama" => (isset($externSrc['extern2_nama']) && ($registryGates['main'][$externSrc['extern2_nama']])) ? $registryGates['main'][$externSrc['extern2_nama']] : "",
                                    "ppn_pph_faktor" => (isset($externSrc['ppn_pph_faktor']) && ($registryGates['main'][$externSrc['ppn_pph_faktor']])) ? $registryGates['main'][$externSrc['ppn_pph_faktor']] : "",
                                    "extern_jenis" => (isset($externSrc['extern_jenis']) && ($registryGates['main'][$externSrc['extern_jenis']])) ? $registryGates['main'][$externSrc['extern_jenis']] : "",
                                    "extern_nilai3" => (isset($externSrc['extern_nilai3']) && ($registryGates['main'][$externSrc['extern_nilai3']])) ? $registryGates['main'][$externSrc['extern_nilai3']] : "",
                                    "extern_nilai4" => (isset($externSrc['extern_nilai4']) && ($registryGates['main'][$externSrc['extern_nilai4']])) ? $registryGates['main'][$externSrc['extern_nilai4']] : "",
                                    "extern3_id" => isset($externSrc['extern3_id']) && isset($registryGates['main'][$externSrc['extern3_id']]) ? $registryGates['main'][$externSrc['extern3_id']] : "",
                                    "extern3_nama" => isset($externSrc['extern3_nama']) && isset($registryGates['main'][$externSrc['extern3_nama']]) ? $registryGates['main'][$externSrc['extern3_nama']] : "",
                                    "extern4_id" => isset($externSrc['extern4_id']) && isset($registryGates['main'][$externSrc['extern4_id']]) ? $registryGates['main'][$externSrc['extern4_id']] : "",
                                    "extern4_nama" => isset($externSrc['extern4_nama']) && isset($registryGates['main'][$externSrc['extern4_nama']]) ? $registryGates['main'][$externSrc['extern4_nama']] : "",
                                    "extern5_id" => isset($externSrc['extern5_id']) && isset($registryGates['main'][$externSrc['extern5_id']]) ? $registryGates['main'][$externSrc['extern5_id']] : "",
                                    "extern5_nama" => isset($externSrc['extern5_nama']) && isset($registryGates['main'][$externSrc['extern5_nama']]) ? $registryGates['main'][$externSrc['extern5_nama']] : "",
                                    "payment_locked" => (isset($externSrc['payment_locked']) && ($registryGates['main'][$externSrc['payment_locked']])) ? $registryGates['main'][$externSrc['payment_locked']] : 0,
                                    "cash_account" => (isset($externSrc['cash_account']) && ($registryGates['main'][$externSrc['cash_account']])) ? $registryGates['main'][$externSrc['cash_account']] : 0,
                                    "cash_account_nama" => (isset($externSrc['cash_account_nama']) && ($registryGates['main'][$externSrc['cash_account_nama']])) ? $registryGates['main'][$externSrc['cash_account_nama']] : 0,
                                );
                                $tr->writePaymentSrc($insertID, $arrPymSrc);
                                showLast_query("merah");
                            }
                        }
                    }
                }
                else {
                    cekMerah("TIDAK nulis paymentSrc");
                }

                $addPaymentSource = isset($configUi['steps'][$stepNum]['additionalStep']['shippingService']) ? $configUi['steps'][$stepNum]['additionalStep']['shippingService'] : array();
                //endregion

                //region pembantu paymentsource
                $paymentPembantuSources = $this->config->item("payment_pembantu_Source");
                if (array_key_exists($stepCode, $paymentPembantuSources)) {
                    $payPembantuConfigs = isset($paymentPembantuSources[$stepCode][$stepNum]) ? $paymentPembantuSources[$stepCode][$stepNum] : array();
                    if (sizeof($payPembantuConfigs) > 0) {
                        foreach ($payPembantuConfigs as $paymentSrcConfig) {
                            $valueSrc = $paymentSrcConfig['valueSrc'];
                            $externSrc = $paymentSrcConfig['externSrc'];
                            $gate = $paymentSrcConfig['gate'];
                            foreach ($registryGates['items2'] as $pembantuData_tmp) {
                                foreach ($pembantuData_tmp as $pembantuData) {
                                    if (isset($pembantuData[$valueSrc]) && $pembantuData[$valueSrc] > 0) {
                                        $arrPymSrc = array(
                                            "jenis" => $stepCode,
                                            "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                            "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                            "extern_id" => isset($pembantuData[$externSrc['id']]) ? $pembantuData[$externSrc['id']] : "",
                                            "extern_nama" => isset($pembantuData[$externSrc['nama']]) ? $pembantuData[$externSrc['nama']] : "",
                                            "nomer" => $tmpNomorNota,
                                            "label" => $paymentSrcConfig['label'],
                                            "tagihan" => $pembantuData[$valueSrc],
                                            "terbayar" => 0,
                                            "sisa" => $pembantuData[$valueSrc],
                                            "cabang_id" => $pembantuData['cabang_id'],
                                            "cabang_nama" => $pembantuData['cabang_nama'],
                                            "oleh_id" => $olehID,
                                            "oleh_nama" => $olehNama,
                                            "dtime" => $dtime,
                                            "fulldate" => $fulldate,
                                            "valas_id" => isset($externSrc['valasId']) && isset($pembantuData[$externSrc['valasId']]) ? $pembantuData[$externSrc['valasId']] : '',
                                            "valas_nama" => isset($externSrc['valasLabel']) && isset($pembantuData[$externSrc['valasLabel']]) ? $pembantuData[$externSrc['valasLabel']] : '',
                                            "valas_nilai" => isset($externSrc['valasValue']) && isset($pembantuData[$externSrc['valasValue']]) ? $pembantuData[$externSrc['valasValue']] : '',
                                            "tagihan_valas" => isset($externSrc['valasTagihan']) && isset($pembantuData[$externSrc['valasTagihan']]) ? $pembantuData[$externSrc['valasTagihan']] : '',
                                            "terbayar_valas" => 0,
                                            "sisa_valas" => isset($externSrc['valasSisa']) && isset($pembantuData[$externSrc['valasSisa']]) ? $pembantuData[$externSrc['valasSisa']] : '',
                                            //                            "extern_label2" => isset($pembantuData['pihakMainName']) ? $pembantuData['pihakMainName'] : "",
                                            "extern_label2" => (isset($externSrc['extern_label2']) && ($pembantuData[$externSrc['extern_label2']])) ? $pembantuData[$externSrc['extern_label2']] : "",
                                            "ppn" => (isset($externSrc['ppn']) && ($pembantuData[$externSrc['ppn']])) ? $pembantuData[$externSrc['ppn']] : "",
                                            "ppn_approved" => (isset($externSrc['ppn_approved']) && ($pembantuData[$externSrc['ppn_approved']])) ? $pembantuData[$externSrc['ppn_approved']] : 0,
                                            "ppn_sisa" => (isset($externSrc['ppn']) && ($pembantuData[$externSrc['ppn']])) ? $pembantuData[$externSrc['ppn']] : "",
                                            "ppn_status" => (isset($externSrc['ppn_status'])) ? $externSrc['ppn_status'] : 0,
                                            "extern_nilai2" => (isset($externSrc['extern_nilai2']) && ($pembantuData[$externSrc['extern_nilai2']])) ? $pembantuData[$externSrc['extern_nilai2']] : 0,
                                            "extern_date2" => (isset($externSrc['extern_date2']) && ($pembantuData[$externSrc['extern_date2']])) ? $pembantuData[$externSrc['extern_date2']] : "",
                                            "pph_23" => (isset($externSrc['pph_23']) && ($pembantuData[$externSrc['pph_23']])) ? $pembantuData[$externSrc['pph_23']] : "",
                                            "npwp" => (isset($externSrc['npwp']) && ($pembantuData[$externSrc['npwp']])) ? $pembantuData[$externSrc['npwp']] : "",
                                            "extern2_id" => (isset($externSrc['extern2_id']) && ($pembantuData[$externSrc['extern2_id']])) ? $pembantuData[$externSrc['extern2_id']] : "",
                                            "extern2_nama" => (isset($externSrc['extern2_nama']) && ($pembantuData[$externSrc['extern2_nama']])) ? $pembantuData[$externSrc['extern2_nama']] : "",
                                            "ppn_pph_faktor" => (isset($externSrc['ppn_pph_faktor']) && ($pembantuData[$externSrc['ppn_pph_faktor']])) ? $pembantuData[$externSrc['ppn_pph_faktor']] : "",
                                            "extern_jenis" => (isset($externSrc['extern_jenis']) && ($pembantuData[$externSrc['extern_jenis']])) ? $pembantuData[$externSrc['extern_jenis']] : "",
                                            "extern_nilai3" => (isset($externSrc['extern_nilai3']) && ($pembantuData[$externSrc['extern_nilai3']])) ? $pembantuData[$externSrc['extern_nilai3']] : "",
                                            "extern_nilai4" => (isset($externSrc['extern_nilai4']) && ($pembantuData[$externSrc['extern_nilai4']])) ? $pembantuData[$externSrc['extern_nilai4']] : "",
//                                    "npwp" => (isset($externSrc['npwp']) && ($pembantuData[$externSrc['npwp']])) ? $pembantuData[$externSrc['npwp']] : "",
                                            //                            "extern_nilai2" => (isset($externSrc['extern_nilai2']) && ($pembantuData[$externSrc['extern_nilai2']])) ? $pembantuData[$externSrc['extern_nilai2']] : "",
                                            "payment_locked" => (isset($externSrc['payment_locked']) && ($pembantuData[$externSrc['payment_locked']])) ? $pembantuData[$externSrc['payment_locked']] : 0,
                                            "cash_account" => (isset($externSrc['cash_account']) && ($pembantuData[$externSrc['cash_account']])) ? $pembantuData[$externSrc['cash_account']] : 0,
                                            "cash_account_nama" => (isset($externSrc['cash_account_nama']) && ($pembantuData[$externSrc['cash_account_nama']])) ? $pembantuData[$externSrc['cash_account_nama']] : 0,
                                        );
//                                        arrPrintWebs($arrPymSrc);
                                        $tr->writePaymentPembantuSrc($insertID, $arrPymSrc);
                                        showLast_query("pink");
                                    }
                                }

                            }

//                        cekMerah($this->db->last_query());
                        }

                    }

                }
                else {
                    cekMerah("TIDAK nulis paymentSrc");
                }
                //endregion


                validateAllBalances($trTmpCabangID);

//                break;
            }
        }

        cekHijau("...tes cli transaksi... rekening pembantu masuk disini (component detail)<br>start: $startDate<br>stop: $stopDate<br>butuh waktu: " . timeDiff($startDate, $stopDate));
        mati_disini("...tes cli transaksi... rekening pembantu masuk disini (component detail)<br>start: $startDate<br>stop: $stopDate<br>butuh waktu: " . timeDiff($startDate, $stopDate));
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("...tes cli transaksi... rekening pembantu masuk disini (component detail)<br>start: $startDate<br>stop: $stopDate<br>butuh waktu: " . timeDiff($startDate, $stopDate));

    }

    public function run_susulanJurnal_2()
    {

        $this->load->model("MdlTransaksi");
        $this->load->model("CustomCounter");
        $this->load->helper("he_mass_table");
        $startDate = dtimeNow();


        $getTrID = (isset($_GET['tr_id']) && ($_GET['tr_id'] > 0)) ? $_GET['tr_id'] : 0;
        $addJudul = "";

        $getTrID = "922157";

        $tr = New MdlTransaksi();
        $tr->setSortBy(
            array(
                "kolom" => "id",
                "mode" => "ASC",
            )
        );
        $this->db->limit(1);

        // bila ada trID dari URL, maka ini adalah cek manual, tidak boleh close commit !!!
        if ($getTrID > 0) {
            $tr->addFilter("id='$getTrID'");

            $addJudul = "<br>cek manual";
        }
        else {
            $tr->addFilter("cli='0'");
            mati_disini(__LINE__ . " WAJIB tentukan transaksi_id");
        }

        $trTmp = $tr->lookupAll()->result();
        cekHere($this->db->last_query() . "<br>" . sizeof($trTmp));

        if (sizeof($trTmp) > 0) {
            $trID_cli = $trTmp[0]->id;
            $trTmpCabangID = $trTmp[0]->cabang_id;
            $kolom = array(
                "trID" => "id",
                "jenisTr" => "jenis",
                "jenisTrMaster" => "jenis_master",
                "jenisTrTop" => "jenis_top",
                "nomer" => "nomer",
                "nomerTop" => "nomer_top",
                "dtime" => "dtime",
                "fulldate" => "fulldate",
                "stepNumber" => "step_number",
                "indexRegistry" => "indexing_registry",
                "olehID" => "oleh_id",
                "olehNama" => "oleh_nama",
            );

            $arrKolomTrans = array();
            foreach ($kolom as $key => $val) {
                $arrKolomTrans[$key] = isset($trTmp[0]->$val) ? $trTmp[0]->$val : NULL;
            }

            $reg = New MdlTransaksi();
            $reg->setFilters(array());
            $reg->addFilter("transaksi_id='" . $trTmp[0]->id . "'");
            $regTmp = $reg->lookupDataRegistries()->result();
            $registryGates = array();
            foreach ($regTmp as $regSpec) {
                foreach ($regSpec as $key_reg => $val_reg) {
                    if ($key_reg != "transaksi_id") {
                        $registryGates[$key_reg] = blobDecode($val_reg);
                    }
                }
            }


            $this->jenisTr = $jenisTrTarget = $jenisTr = $jenis = $arrKolomTrans['jenisTr'];
            $jenisTrMaster = $arrKolomTrans['jenisTrMaster'];
            $fulldate = $arrKolomTrans['fulldate'];
            $dtime = $arrKolomTrans['dtime'];
            $stepNumber = $arrKolomTrans['stepNumber'];
            $insertNum = $tmpNomorNota = $transaksi_no = $arrKolomTrans['nomer'];
            $pelaku_transaksi_id = $olehID = $arrKolomTrans['olehID'];
            $pelaku_transaksi_nama = $olehNama = $arrKolomTrans['olehNama'];
            $insertID = $transaksiID = $transaksi_id = $arrKolomTrans['trID'];
            $olehID = $arrKolomTrans['olehID'];
            $olehNama = $arrKolomTrans['olehNama'];
            $stepNumCurrent = 1;
            $stepNum = 2;
            $ppnFactor = $registryGates["main"]["ppnFactor"];
            //---------------------
            $cCode = "_TR_" . $this->jenisTr;
            $_SESSION[$cCode] = array();
            $_SESSION[$cCode] = $registryGates;


            $configCoreMasterModulJenis = $configCoreMasterModulJenisBuilder = $configCore = loadConfigModulJenis_he_misc($jenisTrMaster, "coTransaksiCore");
            $configUiMasterModulJenis = $configUi = loadConfigModulJenis_he_misc($jenisTrMaster, "coTransaksiUi");
            $configLayoutMasterModulJenis = $configLayout = loadConfigModulJenis_he_misc($jenisTrMaster, "coTransaksiLayout");
            $configValuesMasterModulJenis = $configValues = loadConfigModulJenis_he_misc($jenisTrMaster, "coTransaksiValues");


            cekHitam(":: jenisTrMaster-> $jenisTrMaster :: jenisTr-> $jenisTr :: [trID_cli: $trID_cli]");
//            arrPrint($configCoreMasterModulJenisBuilder);

            $this->db->trans_start();


            //region pre-processors (master)
            if (isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['master'])) {
                $iterator = isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['master']) ? $configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['master'] : array();
                $itemNumLabels = isset($configUiMasterModulJenis['shoppingCartNumFields']) ? $configUiMasterModulJenis['shoppingCartNumFields'] : array();

                echo "ITEM NUM LABELS [$jenisTrTarget] []";
                $tmpOutParams = array();
                if (sizeof($iterator) > 0) {
                    echo "<script>top.writeProgress('PERSIAPAN PRE-PROCESSOR...', 'HEAD');</script>";
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                        $switchResultParams = isset($tComSpec['switchResultParams']) ? $tComSpec['switchResultParams'] : false;

                        echo "master-preproc: $comName, initializing values <br>";
                        $tmpOutParams[$cCtr] = array();

                        $subParams = array();
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {

                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                                $subParams['static'][$key] = $realValue;

                            }

                            if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                foreach ($paramPatchers[$comName] as $k => $v) {
                                    if (!isset($subParams['static'][$k])) {
                                        $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                    }
                                }
                            }
                            if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                $jenis = $_SESSION[$cCode]['main']['jenis'];
                                foreach ($paramForceFillers[$comName] as $k => $v) {
                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                }
                            }

                            $subParams['static']["fulldate"] = $fulldate;
                            $subParams['static']["dtime"] = $dtime;
                            $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $olehNama;
                        }
                        if (sizeof($subParams) > 0) {
                            $tmpOutParams[$cCtr] = $subParams;
                        }

                        $mdlName = "Pre" . ucfirst($comName);
                        $this->load->model("Preprocs/" . $mdlName);
                        $m = new $mdlName($resultParams);

                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        if ($tobeExecuted) {
                            $m->pair($masterID, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $gotParams = $m->exec();
//
                            cekbiru("gotparams dari $comName");
                            arrprint($gotParams);
//
                            if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                //                                cekhijau("ada gotparam, sekarang mau replace");
                                foreach ($gotParams as $gateName => $gSpec) {

                                    if ($switchResultParams == true) {
                                        foreach ($gSpec as $id => $ggSpec) {
                                            if (!isset($_SESSION[$cCode][$gateName][$id])) {
                                                $_SESSION[$cCode][$gateName][$id] = array();
                                            }
                                            if (isset($_SESSION[$cCode][$gateName][$id])) {
                                                if (is_array($ggSpec) && sizeof($ggSpec) > 0) {
                                                    foreach ($ggSpec as $key => $val) {
                                                        $_SESSION[$cCode][$gateName][$id][$key] = $val;
                                                    }
                                                }
                                            }
                                            //cekMerah("REBUILDING VALUES..");
                                            if (sizeof($itemNumLabels) > 0) {
                                                //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                foreach ($itemNumLabels as $key => $label) {
                                                    //cekHere("$id === $key => $label");
                                                    if (isset($_SESSION[$cCode][$gateName][$id][$key])) {
                                                        $_SESSION[$cCode][$gateName][$id]['sub_' . $key] = ($_SESSION[$cCode][$gateName][$id]['jml'] * $_SESSION[$cCode][$gateName][$id][$key]);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    else {

                                        if (isset($_SESSION[$cCode]['main'])) {
                                            if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                foreach ($gSpec as $key => $val) {
                                                    cekbiru("injecting param $key with $val");
                                                    $_SESSION[$cCode]['main'][$key] = $val;
                                                }
                                            }
                                        }
                                        //==inject gotParams to child gate
                                        if (isset($_SESSION[$cCode]['main'])) {
                                            if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                foreach ($gSpec as $key => $val) {
                                                    $_SESSION[$cCode]['main'][$key] = $val;
                                                }
                                            }
                                        }
                                    }

                                }
                            }
                            else {
                                //                                cekmerah("TIDAK ada gotparam, tidak perlu replace");
                            }

                        }
                        else {
                            //                            cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                        }

                        $this->load->helper("he_value_builder");
                        fillValues_he_value_builder($jenisTrMaster, $stepNumCurrent, $stepNum, $configCoreMasterModulJenis, $configUiMasterModulJenis, $configValuesMasterModulJenis, $ppnFactor);

                    }
                }
                else {
                    //cekKuning("sub-preproc is not set");
                }


                $this->load->helper("he_value_builder");
                fillValues_he_value_builder($jenisTrMaster, $stepNumCurrent, $stepNum, $configCoreMasterModulJenisBuilder, $configUiMasterModulJenis, $configValuesMasterModulJenis, $ppnFactor);


            }
            else {
                echo("no processor defined. skipping preprocessor..<br>");
            }
            //endregion


            $registryGates = $_SESSION[$cCode];
            $rsltItems_valas = blobEncode($_SESSION[$cCode]["rsltItems"]);

            cekHijau("MULAI BAGIAN REGULER");

            //region components (sub_component dan component)
            $paramPatchers = $this->config->item('heTransaksi_paramPatchers') != null ? $this->config->item('heTransaksi_paramPatchers') : array();
            $paramForceFillers = $this->config->item('heTransaksi_paramForceFillers') != null ? $this->config->item('heTransaksi_paramForceFillers') : array();
            $validateSubComponent = $this->config->item('heTransaksi_validateComponentDetail') != null ? $this->config->item('heTransaksi_validateComponentDetail') : array();
            $paramForceFillersJenisTR = $this->config->item('heTransaksi_paramForceFillers_jenisTR') != null ? $this->config->item('heTransaksi_paramForceFillers_jenisTR') : array();

            $componentGate['detail'] = array();
            $componentConfig['master'] = array();
            $componentConfig['detail'] = array();
            $iterator = isset($configCoreMasterModulJenis["components"][$jenisTrTarget]['detail']) ? $configCoreMasterModulJenis["components"][$jenisTrTarget]['detail'] : array();
            $componentConfig['detail'] = $iterator;
            $iteratorMaster = $componentConfig['master'] = isset($configCoreMasterModulJenis["components"][$jenisTrTarget]['master']) ? $configCoreMasterModulJenis["components"][$jenisTrTarget]['master'] : array();
            $revertedTarget = "";
            $subComModel = array();
            if (sizeof($iterator) > 0) {
                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                $filterNeeded = false;
                $arrRekeningLoop = array();
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName_orig = $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $loopRequire = isset($tComSpec['loopRequire']) ? $tComSpec['loopRequire'] : false;
                    $srcRawGateName = $tComSpec['srcRawGateName'];

                    echo "sub-component: $comName, $srcGateName, initializing values <br>";

                    $tmpOutParams[$cCtr] = array();
                    if (isset($registryGates[$srcGateName]) && sizeof($registryGates[$srcGateName]) > 0) {

                        foreach ($registryGates[$srcGateName] as $id => $dSpec) {
                            $comName = $comName_orig;
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $registryGates[$srcGateName][$id][$comName], $comName);
                                $tComSpec['comName'] = $comName;
                                $iterator[$cCtr]['comName'] = $comName;
                            }

                            $filterNeeded = false;
                            $mdlName = "Com" . ucfirst($comName);
                            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                $filterNeeded = true;
                            }
                            $subParams = array();
                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $registryGates[$srcGateName][$id][$key], $key);
                                    }

                                    $subComModel[$key] = $comName;

                                    $realValue = makeValue($value, $registryGates[$srcGateName][$id], $registryGates[$srcGateName][$id], 0);

                                    if (strlen($key) > 1) {
                                        $subParams['loop'][$key] = $realValue;
                                    }
                                    else {
                                        $subParams['loop'] = array();
                                    }

                                    // =================== =================== ===================
                                    if (!isset($arrRekeningLoop[$dSpec[$tComSpec['static']['cabang_id']]][$key])) {
                                        $arrRekeningLoop[$dSpec[$tComSpec['static']['cabang_id']]][$key] = 0;
                                    }
                                    $arrRekeningLoop[$dSpec[$tComSpec['static']['cabang_id']]][$key] += $realValue;
                                    if ($realValue != 0) {
                                        cekUngu(":: cetak loop $key => $realValue ::");
                                    }

                                    if ($filterNeeded) {
                                        if ($subParams['loop'][$key] == 0) {
                                            unset($subParams['loop'][$key]);

                                            // =================== =================== ===================
                                        }
                                    }
                                }
                            }
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {
                                    $realValue = makeValue($value, $registryGates[$srcGateName][$id], $registryGates[$srcGateName][$id], 0);
                                    $subParams['static'][$key] = trim($realValue);
                                }
                                if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                    foreach ($paramPatchers[$comName] as $k => $v) {
                                        if (!isset($subParams['static'][$k])) {
                                            $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                            cekOrange("[$jenis] fill :: $comName :: $k ($v) => " . $subParams['static'][$k]);
                                        }
                                    }
                                }
                                if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                    foreach ($paramForceFillers[$comName] as $k => $v) {
                                        $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                        cekOrange("[$jenis] fillforce :: $comName :: $k ($v) => " . $subParams['static'][$k]);
                                    }
                                }

                                // tambahan custom gerbang saat simpan transaksi, tidak bisa ditambahkan di coTransaksiCore/coTransaksiValues
                                if (isset($paramForceFillersJenisTR[$comName][$jenisTrMaster]) && sizeof($paramForceFillersJenisTR[$comName][$jenisTrMaster]) > 0) {
                                    foreach ($paramForceFillersJenisTR[$comName][$jenisTrMaster] as $k => $v) {
                                        $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                        cekorange(":: $k diisikan dengan " . $subParams['static'][$k]);
                                    }
                                }
                                $subParams['static']["fulldate"] = $fulldate;
                                $subParams['static']["dtime"] = $dtime;
                                $subParams['static']["keterangan"] = $configUi['steps'][$stepNumber]['label'] . " nomor " . $tmpNomorNota . " oleh " . $olehNama;
                                //------
                                $subParams['static']["reference_id"] = isset($dSpec["referenceID"]) ? $dSpec["referenceID"] : "";
                                $subParams['static']["reference_nomer"] = isset($dSpec["referenceNomer"]) ? $dSpec["referenceNomer"] : "";
                                $subParams['static']["reference_jenis"] = isset($dSpec["jenisTr_reference"]) ? $dSpec["jenisTr_reference"] : "";
                                $subParams['static']["reference_id_top"] = isset($dSpec["referenceID_top"]) ? $dSpec["referenceID_top"] : "";
                                $subParams['static']["reference_nomer_top"] = isset($dSpec["referenceNomer_top"]) ? $dSpec["referenceNomer_top"] : "";
                                $subParams['static']["reference_jenis_top"] = isset($dSpec["pihakExternMasterID"]) ? $dSpec["pihakExternMasterID"] : "";
                                //------
                                if (strlen($revertedTarget) > 1) {
                                    $subParams['static']['reverted_target'] = $revertedTarget;
                                }
                            }
                            if (sizeof($subParams) > 0) {
                                if ($filterNeeded) {
                                    if (isset($subParams['loop']) && !empty($subParams['loop'])) {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    if (empty($subParams['loop']) && $loopRequire == true) {
                                        unset($tmpOutParams[$cCtr]);
                                    }
                                    else {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                            }
                        }

                        $componentGate['detail'][$cCtr] = $subParams;
                    }

                }
                $it = 0;
                foreach ($iterator as $cCtr => $tComSpec) {
                    $it++;
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    if (isset($registryGates[$srcGateName]) && sizeof($registryGates[$srcGateName]) > 0) {
                        foreach ($registryGates[$srcGateName] as $id => $dSpec) {
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $registryGates[$srcGateName][$id][$comName], $comName);
                            }
                        }
                    }
                    else {
                        $comName = NULL;
                    }
                    cekHere("::::: $comName ::::: $srcGateName :::::");


                    echo __LINE__ . " sub $cCtr component #$it: $comName, sending values**** <br>";

                    if ($comName != NULL) {

                        $mdlName = "Com" . ucfirst($comName);
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();

                        if (isset($tmpOutParams[$cCtr]) && sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        if ($tobeExecuted) {
                            $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $jenisTrMaster . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $jenisTrMaster . "/" . __FUNCTION__ . "/" . __LINE__);
                        }
                        else {
                            cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                        }

                    }
                }

                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    // region baca jurnal rekening besar
                    $jn = New ComJurnal();
                    $jn->addFilter("transaksi_id='$transaksiID'");
                    $jnTmp = $jn->lookupAll()->result();
//                    arrPrint($jnTmp);
                    $arrJurnal = array();
                    if (sizeof($jnTmp) > 0) {
                        foreach ($jnTmp as $ii => $spec) {
                            $defPosition = detectRekDefaultPosition($spec->rekening);
                            switch ($defPosition) {
                                case "debet":
                                    $arrJurnal[$spec->cabang_id][$spec->rekening] = $spec->debet > 0 ? $spec->debet : $spec->kredit * -1;
                                    break;
                                case "kredit":
                                    $arrJurnal[$spec->cabang_id][$spec->rekening] = $spec->kredit > 0 ? $spec->kredit : $spec->debet * -1;
                                    break;
                                default:
                                    mati_disini("tidak menemukan default posisi rekening...");
                                    break;
                            }
                        }
                    }
                    // endregion

                    cekHere("cetak array jurnal");
                    arrPrint($arrJurnal);

                    cekHere("cetak rek loop");
                    arrPrint($arrRekeningLoop);


                    if (sizeof($arrJurnal) > 0) {
                        if (sizeof($arrRekeningLoop) > 0) {
                            foreach ($arrRekeningLoop as $cabang_id => $loopSpec) {
                                foreach ($loopSpec as $rekening => $rekValue) {
                                    if (array_key_exists($rekening, $arrJurnal[$cabang_id])) {
                                        if (floor($rekValue) != floor($arrJurnal[$cabang_id][$rekening])) {
                                            mati_disini("nilai $rekening, jurnal: " . floor($arrJurnal[$cabang_id][$rekening]) . ", akumulasi pembantu: " . floor($rekValue));
                                        }
                                        else {
                                            cekHijau(":: COCOK ::");
                                        }
                                    }
                                }
                            }
                        }
                    }


                }

            }
            else {
                cekMerah("subcomponents [detail] is not set");
            }

            if (sizeof($iteratorMaster) > 0) {
                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                $componentConfig['master'] = $iteratorMaster;
                $cCtr = 0;
                foreach ($iteratorMaster as $cCtr => $tComSpec) {
                    $cCtr++;
                    $comName = $tComSpec['comName'];
                    if (substr($comName, 0, 1) == "{") {
                        $comName = trim($comName, "{");
                        $comName = trim($comName, "}");
                        $comName = str_replace($comName, $registryGates[$srcGateName][$comName], $comName);
                    }
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "component # $cCtr: $comName<br>";

                    $dSpec = $registryGates[$srcGateName];
                    $tmpOutParams = array();
                    if (isset($tComSpec['loop'])) {
                        foreach ($tComSpec['loop'] as $key => $value) {
                            if (substr($key, 0, 1) == "{") {
                                $key = trim($key, "{");
                                $key = trim($key, "}");
                                $key = str_replace($key, $registryGates[$srcGateName][$key], $key);
                            }
                            $realValue = makeValue($value, $registryGates[$srcGateName], $registryGates[$srcGateName], 0);
                            $tmpOutParams['loop'][$key] = $realValue;
                        }
                    }
                    if (isset($tComSpec['static'])) {
                        foreach ($tComSpec['static'] as $key => $value) {

                            $realValue = makeValue($value, $registryGates[$srcGateName], $registryGates[$srcGateName], 0);
                            $tmpOutParams['static'][$key] = $realValue;

                        }
                        if (!isset($tmpOutParams['static']["transaksi_id"])) {
                            $tmpOutParams['static']["transaksi_id"] = $insertID;
                        }
                        if (!isset($tmpOutParams['static']["transaksi_no"])) {
                            $tmpOutParams['static']["transaksi_no"] = $insertNum;
                        }
                        $tmpOutParams['static']["urut"] = $cCtr;
                        $tmpOutParams['static']["fulldate"] = $fulldate;
                        $tmpOutParams['static']["dtime"] = $dtime;
                        $tmpOutParams['static']["keterangan"] = $configUi['steps'][$stepNumber]['label'] . " nomor " . $tmpNomorNota . " oleh " . $olehNama;


                    }
                    if (isset($tComSpec['static2'])) {
                        //cekHere("DISINI OIII");
                        foreach ($tComSpec['static2'] as $key => $value) {

                            $realValue = makeValue($value, $registryGates[$srcGateName][$cCtr], $registryGates[$srcGateName][$cCtr], 0);
                            $tmpOutParams['static2'][$key] = $realValue;

                        }
                        if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                            $tmpOutParams['static2']["transaksi_id"] = $insertID;
                        }
                        if (!isset($tmpOutParams['static2']["transaksi_no"])) {
                            $tmpOutParams['static2']["transaksi_no"] = $insertNum;
                        }

                        $tmpOutParams['static2']["fulldate"] = $fulldate;
                        $tmpOutParams['static2']["dtime"] = $dtime;
                        $tmpOutParams['static2']["keterangan"] = $configUi['steps'][$stepNumber]['label'] . " nomor " . $tmpNomorNota . " oleh " . $olehNama;


                    }

                    $mdlName = "Com" . ucfirst($comName);
                    $this->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();

                    //===filter value nol, jika harus difilter
                    $tobeExecuted = true;
                    if (in_array($mdlName, $compValidators)) {

                        $loopParams = isset($tmpOutParams['loop']) ? $tmpOutParams['loop'] : array();
                        if (sizeof($loopParams) > 0) {
                            foreach ($loopParams as $key => $val) {
                                cekmerah("$comName : $key = $val ");
                                if ($val == 0) {
                                    unset($tmpOutParams['loop'][$key]);
                                }
                            }
                        }
                        if (sizeof($tmpOutParams['loop']) < 1) {
                            $tobeExecuted = false;
                        }

                    }
                    if ($tobeExecuted) {
                        $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $jenisTrTarget . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $jenisTrTarget . "/" . __FUNCTION__ . "/" . __LINE__);
                    }
                    $componentGate['master'][$cCtr] = $tmpOutParams;
                }
            }
            else {
                cekHitam("TIDAK ADA CORE MASTER");
            }
            //endregion


            //region processing sub-post-processors, always
            $iterator = isset($configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['detail']) ? $configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['detail'] : array();
            if (sizeof($iterator) > 0) {
                $tmpOutParams = array();
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "sub-postProcessor: $comName, initializing values <br>";
                    echo "<script>top.writeProgress('MENYIAPKAN DATA SUB-PROCESSORS UNTUK DIKIRIM...', 'head');</script>";
                    $tmpOutParams[$cCtr] = array();
                    foreach ($_SESSION[$cCode][$srcGateName] as $cnt => $dSpec) {
                        $subParams = array();
                        if (isset($tComSpec['loop'])) {
                            foreach ($tComSpec['loop'] as $key => $value) {

                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$cnt], $_SESSION[$cCode][$srcGateName][$cnt], 0);
                                $subParams['loop'][$key] = $realValue;

                            }
                        }
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {

                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$cnt], $_SESSION[$cCode][$srcGateName][$cnt], 0);
                                $subParams['static'][$key] = $realValue;
                                //                                cekBiru("$key diisi dengan $realValue");

                            }
                            if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                foreach ($paramPatchers[$comName] as $k => $v) {
                                    if (!isset($subParams['static'][$k])) {
                                        $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                    }
                                }
                            }
                            if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                $jenis = $_SESSION[$cCode]['main']['jenis'];
                                foreach ($paramForceFillers[$comName] as $k => $v) {
                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                    //                                    cekorange(":: $k diisikan dengan " . $subParams['static'][$k]);
                                }
                            }
                            $subParams['static']["fulldate"] = $fulldate;
                            $subParams['static']["dtime"] = $dtime;
                            $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $olehNama;
                        }
                        if (sizeof($subParams) > 0) {
                            $tmpOutParams[$cCtr][] = $subParams;
                        }
                        echo "<script>top.writeProgress('" . isset($subParams['static']['name']) ? $subParams['static']['name'] : "" . " " . isset($subParams['static']['extern_nama']) ? $subParams['static']['extern_nama'] : "" . " " . isset($subParams['static']['nama']) ? $subParams['static']['nama'] : "" . "');</script>";
                    }
                }

                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    if (sizeof($tmpOutParams[$cCtr]) > 0) {

                        echo "sub-postProcessor: $comName, sending values <br>";
                        echo "<script>top.writeProgress('SENDING SUB-PROCESSORS ($comName)...', 'head');</script>";
                        $mdlName = "Com" . ucfirst($comName);
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();

                        $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $jenisTrTarget . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $jenisTrTarget . "/" . __FUNCTION__ . "/" . __LINE__);
//                    cekBiru($this->db->last_query());
                    }
                }
            }
            //endregion

            //region processing main-post-processors, always
            $iterator = isset($configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['master']) ? $configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['master'] : array();
            if (sizeof($iterator) > 0) {
                echo "<script>top.writeProgress('MEMPROSES MAIN-PROCESSORS...', 'head');</script>";
                $tmpOutParams = array();
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "post-processor: $comName<br>";

                    $dSpec = $_SESSION[$cCode][$srcGateName];
                    $tmpOutParams = array();
                    if (isset($tComSpec['loop'])) {
                        foreach ($tComSpec['loop'] as $key => $value) {

                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                            $tmpOutParams['loop'][$key] = $realValue;

                        }
                    }
                    if (isset($tComSpec['static'])) {
                        //cekHere("DISINI OIII");
                        foreach ($tComSpec['static'] as $key => $value) {

                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                            $tmpOutParams['static'][$key] = $realValue;

                        }
                        if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                            foreach ($paramPatchers[$comName] as $k => $v) {
                                if (!isset($tmpOutParams['static'][$k])) {
                                    $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
                                    echo "<script>top.writeProgress(':: $key diisikan dengan " . $tmpOutParams['static'][$k] . ");</script>";
                                }
                            }
                        }
                        if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                            $jenis = $_SESSION[$cCode]['main']['jenis'];
                            foreach ($paramForceFillers[$comName] as $k => $v) {
                                $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
                                echo "<script>top.writeProgress(':: $key diisikan dengan " . $tmpOutParams['static'][$k] . ");</script>";
                            }
                        }
                        $tmpOutParams['static']["fulldate"] = $fulldate;
                        $tmpOutParams['static']["dtime"] = $dtime;
                        $tmpOutParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $olehNama;
                    }
                    if (isset($tComSpec['static2'])) {
                        foreach ($tComSpec['static2'] as $key => $value) {

                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$cCtr], $_SESSION[$cCode][$srcGateName][$cCtr], 0);
                            $tmpOutParams['static2'][$key] = $realValue;

                        }
                        if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                            foreach ($paramPatchers[$comName] as $k => $v) {
                                if (!isset($subParams['static'][$k])) {
                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                }
                            }
                        }
                        if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                            $jenis = $_SESSION[$cCode]['main']['jenis'];
                            foreach ($paramForceFillers[$comName] as $k => $v) {
                                $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                            }
                        }
                        $tmpOutParams['static2']["fulldate"] = $fulldate;
                        $tmpOutParams['static2']["dtime"] = $dtime;
                        $tmpOutParams['static2']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $olehNama;
                    }

                    //lgShowError("Ada kesalahan",);
                    $mdlName = "Com" . ucfirst($comName);
                    $this->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();
                    $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $jenisTrTarget . "/" . __FUNCTION__ . "/" . __LINE__);
                    $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $jenisTrTarget . "/" . __FUNCTION__ . "/" . __LINE__);
                }
            }
            //endregion


            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                $stepNum = 1;
                //region nulis paymentSource
                $stepCode = $configUiMasterModulJenis['steps'][$stepNum]['target'];
                $paymentSources = $this->config->item("payment_source");
                cekHere("[stepCode: $stepCode]");
                if (array_key_exists($stepCode, $paymentSources)) {
                    $payConfigs = isset($paymentSources[$stepCode][$stepNum]) ? $paymentSources[$stepCode][$stepNum] : array();
                    if (sizeof($payConfigs) > 0) {
                        foreach ($payConfigs as $paymentSrcConfig) {
                            $valueLabel = isset($paymentSrcConfig['label_key']) ? $paymentSrcConfig['label_key'] : $paymentSrcConfig['label'];
                            $valueSrc = $paymentSrcConfig['valueSrc'];
                            $externSrc = $paymentSrcConfig['externSrc'];
                            $valueAdd = isset($_SESSION[$cCode]['main'][$paymentSrcConfig['addValueValidator']]) ? $_SESSION[$cCode]['main'][$paymentSrcConfig['addValueValidator']] : 0;
                            if (isset($paymentSrcConfig['model'])) {
                                $mdlName = $paymentSrcConfig['model'];
                                $this->load->model("Mdls/$mdlName");
                                $pMdl = New $mdlName();
                                $pTmpMdl = $pMdl->lookupAll()->result();
                                $pTmpMdlResult = array();
                                if (sizeof($pTmpMdl) > 0) {
                                    foreach ($pTmpMdl as $pTmpMdlSpec) {
                                        $pTmpMdlResult[$pTmpMdlSpec->id] = $pTmpMdlSpec;
                                    }
                                }
                            }
                            else {
                                $pTmpMdlResult = array();
                            }
                            if (isset($_SESSION[$cCode]['main'][$valueSrc]) && $_SESSION[$cCode]['main'][$valueSrc] > 0) {
                                if (isset($externSrc['extern_label2'])) {
                                    //cek ada isinya atau kosong
                                    $cek = strlen($_SESSION[$cCode]['main'][$externSrc['extern_label2']]) > 4 ? "" : matiHere("jenis biaya tidak dikenali " . __LINE__);//
                                }
                                //region cek duplikasi paymentsource
                                $tr->setFilters(array());
                                $tr->addFilter("transaksi_id='$insertID'");
                                $tr->addFilter("target_jenis='" . $paymentSrcConfig['jenisTarget'] . "'");
                                $validateIsInserted = $tr->lookUpAllPaymentSrc()->result();
                                showLast_query("biru");
                                if (sizeof($validateIsInserted) > 0) {
                                    matiHEre("Gagal menulis transaksi. Silahkan relogin untuk membersihkan sesi demi menghindari duplikasi data, dan coba kembali transaksi yang gagal");
                                }
                                //endregion


                                cekHitam("valuelabel: $valueLabel, valueSrc: $valueSrc");
                                $this->load->helper("he_payment_source");

                                $arrPymSrc = array(
                                    "jenis" => $stepCode,
                                    "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                    "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                    "extern_id" => isset($_SESSION[$cCode]['main'][$externSrc['id']]) ? $_SESSION[$cCode]['main'][$externSrc['id']] : "",
                                    "extern_nama" => isset($_SESSION[$cCode]['main'][$externSrc['nama']]) ? $_SESSION[$cCode]['main'][$externSrc['nama']] : "",
                                    "nomer" => $tmpNomorNota2,
                                    "label" => $paymentSrcConfig['label'],

                                    "tagihan" => $_SESSION[$cCode]['main'][$valueSrc],
                                    "terbayar" => 0,
                                    "sisa" => $_SESSION[$cCode]['main'][$valueSrc],

//                                "cabang_id" => $_SESSION[$cCode]['main']['placeID'],
//                                "cabang_nama" => $_SESSION[$cCode]['main']['placeName'],
                                    "cabang_id" => isset($_SESSION[$cCode]['main'][$externSrc['cabang_id']]) ? $_SESSION[$cCode]['main'][$externSrc['cabang_id']] : $_SESSION[$cCode]['main']['placeID'],
                                    "cabang_nama" => isset($_SESSION[$cCode]['main'][$externSrc['cabangnama']]) ? $_SESSION[$cCode]['main'][$externSrc['cabang_nama']] : $_SESSION[$cCode]['main']['placeName'],
                                    "oleh_id" => $pelaku_transaksi_id,
                                    "oleh_nama" => $pelaku_transaksi_nama,
                                    "dtime" => $dtime,
                                    "fulldate" => $fulldate,
                                    "valas_id" => isset($externSrc['valasId']) && isset($_SESSION[$cCode]['main'][$externSrc['valasId']]) ? $_SESSION[$cCode]['main'][$externSrc['valasId']] : '',
                                    "valas_nama" => isset($externSrc['valasLabel']) && isset($_SESSION[$cCode]['main'][$externSrc['valasLabel']]) ? $_SESSION[$cCode]['main'][$externSrc['valasLabel']] : '',
                                    "valas_nilai" => isset($externSrc['valasValue']) && isset($_SESSION[$cCode]['main'][$externSrc['valasValue']]) ? $_SESSION[$cCode]['main'][$externSrc['valasValue']] : '',

                                    "tagihan_valas" => isset($externSrc['valasTagihan']) && isset($_SESSION[$cCode]['main'][$externSrc['valasTagihan']]) ? $_SESSION[$cCode]['main'][$externSrc['valasTagihan']] : '',
                                    "terbayar_valas" => 0,
                                    "sisa_valas" => isset($externSrc['valasSisa']) && isset($_SESSION[$cCode]['main'][$externSrc['valasSisa']]) ? $_SESSION[$cCode]['main'][$externSrc['valasSisa']] : '',

                                    //                            "extern_label2" => isset($_SESSION[$cCode]['main']['pihakMainName']) ? $_SESSION[$cCode]['main']['pihakMainName'] : "",
                                    "extern_label2" => (isset($externSrc['extern_label2']) && ($_SESSION[$cCode]['main'][$externSrc['extern_label2']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_label2']] : "",

                                    "dpp_ppn" => (isset($externSrc['dpp_ppn']) && ($_SESSION[$cCode]['main'][$externSrc['dpp_ppn']])) ? $_SESSION[$cCode]['main'][$externSrc['dpp_ppn']] : 0,
                                    "ppn" => (isset($externSrc['ppn']) && ($_SESSION[$cCode]['main'][$externSrc['ppn']])) ? $_SESSION[$cCode]['main'][$externSrc['ppn']] : 0,
                                    "ppn_approved" => (isset($externSrc['ppn_approved']) && ($_SESSION[$cCode]['main'][$externSrc['ppn_approved']])) ? $_SESSION[$cCode]['main'][$externSrc['ppn_approved']] : 0,
                                    "ppn_sisa" => (isset($externSrc['ppn']) && ($_SESSION[$cCode]['main'][$externSrc['ppn']])) ? $_SESSION[$cCode]['main'][$externSrc['ppn']] : "",
                                    "ppn_status" => (isset($externSrc['ppn_status'])) ? $externSrc['ppn_status'] : 0,
                                    "extern_nilai2" => (isset($externSrc['extern_nilai2']) && ($_SESSION[$cCode]['main'][$externSrc['extern_nilai2']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_nilai2']] : 0,
                                    "extern_date2" => (isset($externSrc['extern_date2']) && ($_SESSION[$cCode]['main'][$externSrc['extern_date2']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_date2']] : "",
                                    "pph_23" => (isset($externSrc['pph_23']) && ($_SESSION[$cCode]['main'][$externSrc['pph_23']])) ? $_SESSION[$cCode]['main'][$externSrc['pph_23']] : "",

                                    "npwp" => (isset($externSrc['npwp']) && ($_SESSION[$cCode]['main'][$externSrc['npwp']])) ? $_SESSION[$cCode]['main'][$externSrc['npwp']] : "",
//                                "extern2_id" => (isset($externSrc['extern2_id']) && ($_SESSION[$cCode]['main'][$externSrc['extern2_id']])) ? $_SESSION[$cCode]['main'][$externSrc['extern2_id']] : "",
//                                "extern2_nama" => (isset($externSrc['extern2_nama']) && ($_SESSION[$cCode]['main'][$externSrc['extern2_nama']])) ? $_SESSION[$cCode]['main'][$externSrc['extern2_nama']] : "",
                                    "ppn_pph_faktor" => (isset($externSrc['ppn_pph_faktor']) && ($_SESSION[$cCode]['main'][$externSrc['ppn_pph_faktor']])) ? $_SESSION[$cCode]['main'][$externSrc['ppn_pph_faktor']] : "",
                                    "extern_jenis" => (isset($externSrc['extern_jenis']) && ($_SESSION[$cCode]['main'][$externSrc['extern_jenis']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_jenis']] : "",
                                    "extern_nilai3" => (isset($externSrc['extern_nilai3']) && ($_SESSION[$cCode]['main'][$externSrc['extern_nilai3']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_nilai3']] : "",
                                    "extern_nilai4" => (isset($externSrc['extern_nilai4']) && ($_SESSION[$cCode]['main'][$externSrc['extern_nilai4']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_nilai4']] : "",
                                    "npwp" => (isset($externSrc['npwp']) && ($_SESSION[$cCode]['main'][$externSrc['npwp']])) ? $_SESSION[$cCode]['main'][$externSrc['npwp']] : "",
                                    //                            "extern_nilai2" => (isset($externSrc['extern_nilai2']) && ($_SESSION[$cCode]['main'][$externSrc['extern_nilai2']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_nilai2']] : "",
                                    "payment_locked" => (isset($externSrc['payment_locked']) && ($_SESSION[$cCode]['main'][$externSrc['payment_locked']])) ? $_SESSION[$cCode]['main'][$externSrc['payment_locked']] : 0,
                                    "cash_account" => (isset($externSrc['cash_account']) && ($_SESSION[$cCode]['main'][$externSrc['cash_account']])) ? $_SESSION[$cCode]['main'][$externSrc['cash_account']] : 0,
                                    "cash_account_nama" => (isset($externSrc['cash_account_nama']) && ($_SESSION[$cCode]['main'][$externSrc['cash_account_nama']])) ? $_SESSION[$cCode]['main'][$externSrc['cash_account_nama']] : 0,

                                    "extern2_id" => (isset($externSrc['extern2_id']) && ($_SESSION[$cCode]['main'][$externSrc['extern2_id']])) ? $_SESSION[$cCode]['main'][$externSrc['extern2_id']] : $externSrc['extern2_id'],
                                    "extern2_nama" => (isset($externSrc['extern2_nama']) && ($_SESSION[$cCode]['main'][$externSrc['extern2_nama']])) ? $_SESSION[$cCode]['main'][$externSrc['extern2_nama']] : $externSrc['extern2_nama'],

                                    "extern3_id" => isset($_SESSION[$cCode]['main'][$externSrc['extern3_id']]) ? $_SESSION[$cCode]['main'][$externSrc['extern3_id']] : "",
                                    "extern3_nama" => isset($_SESSION[$cCode]['main'][$externSrc['extern3_nama']]) ? $_SESSION[$cCode]['main'][$externSrc['extern3_nama']] : "",
                                    "extern4_id" => isset($_SESSION[$cCode]['main'][$externSrc['extern4_id']]) ? $_SESSION[$cCode]['main'][$externSrc['extern4_id']] : "",
                                    "extern4_nama" => isset($_SESSION[$cCode]['main'][$externSrc['extern4_nama']]) ? $_SESSION[$cCode]['main'][$externSrc['extern4_nama']] : "",
                                    "extern5_id" => isset($_SESSION[$cCode]['main'][$externSrc['extern5_id']]) ? $_SESSION[$cCode]['main'][$externSrc['extern5_id']] : "",
                                    "extern5_nama" => isset($_SESSION[$cCode]['main'][$externSrc['extern5_nama']]) ? $_SESSION[$cCode]['main'][$externSrc['extern5_nama']] : "",
                                    //----
                                    "biaya_rekening" => makeValue($externSrc['biaya_rekening'], $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0),
                                    "biaya_rekening_label" => makeValue($externSrc['biaya_rekening_label'], $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0),
                                    "biaya_rekening_id" => makeValue($externSrc['biaya_rekening_id'], $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0),
                                    "biaya_rekening_id_label" => makeValue($externSrc['biaya_rekening_id_label'], $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0),
                                    "biaya_rekening2_id" => makeValue($externSrc['biaya_rekening2_id'], $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0),
                                    "biaya_rekening2_id_label" => makeValue($externSrc['biaya_rekening2_id_label'], $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0),
                                    "cabang2_id" => makeValue($externSrc['cabang2_id'], $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0),
                                    "cabang2_nama" => makeValue($externSrc['cabang2_nama'], $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0),
                                );
                                arrPrintCyan($arrPymSrc);
                                $tr->writePaymentSrc($insertID, $arrPymSrc);
                                cekMerah($this->db->last_query());

                            }
                        }
                    }
                }
                else {
                    cekMerah("TIDAK nulis paymentSrc");
                }

                //endregion
            }

            //region nulis uangMukaSource
            /*dimatiin geser ke ComUangmukaSourceDetail karena ada di items.
            /*revisi tanggal 27 mei 2020 subject digeser ke vendor dari jenis transaksi misal uangmuka asuransi,uang muka pembelian ->uang muka.
             *
             */
            $stepCode = $configUiMasterModulJenis['steps'][1]['target'];
            $uangMukaSources = $this->config->item("uang_muka");
            if (array_key_exists($stepCode, $uangMukaSources)) {
                cekMerah(":: starting UANG MUKA  SOURCE");
                $uangMukaConfigs = isset($uangMukaSources[$stepCode][1]) ? $uangMukaSources[$stepCode][1] : array();
                if (sizeof($uangMukaConfigs) > 0) {
                    $cekPreValue = "";
                    $this->load->model("Mdls/MdlPaymentUangMuka");
                    $l = new MdlPaymentUangMuka();
                    foreach ($uangMukaConfigs as $uangMukaSrcConfig) {
                        $valueSrc = $uangMukaSrcConfig['valueSrc'];
                        $externSrc = $uangMukaSrcConfig['externSrc'];
                        $externLabel = $externSrc['extLabel'];
//                        cekHitam("[$valueSrc] [$externSrc] [$externLabel]");
//                        arrPrint($externSrc);
                        $setExtern2_id = isset($_SESSION[$cCode]['main'][$externSrc['extern2_id']]) ? $_SESSION[$cCode]['main'][$externSrc['extern2_id']] : 0;
                        $nilai_uangmuka = isset($_SESSION[$cCode]['main'][$valueSrc]) ? $_SESSION[$cCode]['main'][$valueSrc] : 0;
                        $externSrc_id = isset($_SESSION[$cCode]['main'][$externSrc['id']]) ? $_SESSION[$cCode]['main'][$externSrc['id']] : 0;
                        if ($this->jenisTr == "4467") {
                            //4467
                            $externCabang_id = "-1";
                            $cabang_nama = "PUSAT";
                        }
                        else {
                            $externCabang_id = isset($_SESSION[$cCode]['main']['placeID']) ? $_SESSION[$cCode]['main']['placeID'] : 0;
                            $cabang_nama = $_SESSION[$cCode]['main']['placeName'];
                        }

                        if (isset($_SESSION[$cCode]['main'][$valueSrc]) && $_SESSION[$cCode]['main'][$valueSrc] <> 0) {
                            $l->addFilter("extern_id='" . $externSrc_id . "'");
                            $l->addFilter("extern2_id='" . $setExtern2_id . "'");//referensi PO
                            $l->addFilter("extern_label2='" . $externLabel . "'");
                            $l->addFilter("cabang_id='" . $externCabang_id . "'");
                            $tmpUm = $l->lookupAll()->result();
                            showLast_query("biru");
                            if (sizeof($tmpUm) > 0) {
//                                arrPrintCyan($tmpUm);
                                //update here broo
                                $preID = $tmpUm[0]->id;
                                $preTagihan = $tmpUm[0]->tagihan;
                                $preSisa = $tmpUm[0]->sisa;
                                $newTahigan = $preTagihan + $nilai_uangmuka;
                                $newsisa = $preSisa + $nilai_uangmuka;
                                $update = array(
                                    "tagihan" => $newTahigan,
                                    "sisa" => $newsisa,
                                );
                                $where = array(
                                    "id" => $preID,
//                                    "extern_id" => $externSrc_id,
//                                    "extern2_id" => $setExtern2_id,
//                                    "extern_label2" => $externLabel,//pembeda vendor dan customer lihat di heTransaksi_misc ->uang muka
                                );
                                $tr->updateUangMukaSrc($where, $update);
                                cekHitam($this->db->last_query());
                                cekHitam($this->db->affected_rows());
                            }
                            else {
                                //insertbaru brooo
                                $tr->writeUangMukaSrc($insertID, array(
                                    "jenis" => $stepCode,
                                    "target_jenis" => $uangMukaSrcConfig['jenisTarget'],
                                    "reference_jenis" => $uangMukaSrcConfig['jenisSrc'],
                                    "extern_id" => $_SESSION[$cCode]['main'][$externSrc['id']],
                                    "extern_nama" => $_SESSION[$cCode]['main'][$externSrc['nama']],
                                    "extern2_id" => $_SESSION[$cCode]['main'][$externSrc['extern2_id']],
                                    "extern2_nama" => $_SESSION[$cCode]['main'][$externSrc['extern2_nama']],
                                    "nomer" => "",
                                    "note" => "",
                                    "label" => $uangMukaSrcConfig['label'],
                                    "tagihan" => $_SESSION[$cCode]['main'][$valueSrc],
                                    "terbayar" => 0,
                                    "sisa" => $_SESSION[$cCode]['main'][$valueSrc],
                                    "cabang_id" => $externCabang_id,
                                    "cabang_nama" => $cabang_nama,
                                    "oleh_id" => $this->session->login['id'],
                                    "oleh_nama" => $this->session->login['nama'],
                                    "dtime" => date("Y-m-d H:i:s"),
                                    "fulldate" => date("Y-m-d"),
                                    "extern_label2" => $externSrc['extLabel'],
                                    "project_id" => $externSrc['project_id'],
                                    "project_nama" => $externSrc['project_nama'],
                                ));
                                cekMerah($this->db->last_query());
                            }
                        }
                        else {
                            cekHitam("tidak ada nilai untuk UANG MUKA");
                        }
                    }
                }
                else {
                    cekLime("not write uang muka");
                }

            }
            else {
                cekMerah("not write uang muka");
            }
            //endregion


            cekHijau("BAGIAN REGULER SELESAI");
//            mati_disini(__LINE__);
            cekHijau("MULAI BAGIAN AUTO SETOR KE DC/PUSAT");

            $autoJurnalSetor = 1;
            if ($autoJurnalSetor == 1) {

                //region pre-processors auto (item)
                $iterator = isset($configCoreMasterModulJenisBuilder['preProcessorAuto'][$jenisTrTarget]['detail']) ? $configCoreMasterModulJenisBuilder['preProcessorAuto'][$jenisTrTarget]['detail'] : array();
                if (sizeof($iterator) > 0) {


                    $itemNumLabels = isset($configUiMasterModulJenis['shoppingCartNumFields']) ? $configUiMasterModulJenis['shoppingCartNumFields'] : array();
                    echo "ITEM NUM LABELS";

                    if (sizeof($iterator) > 0) {
                        foreach ($iterator as $cCtr => $tComSpec) {
                            $comName = $tComSpec['comName'];
                            $srcGateName = $tComSpec['srcGateName'];
                            $srcRawGateName = $tComSpec['srcRawGateName'];

                            echo "sub-preproc: $comName, initializing values <br>";

                            foreach ($_SESSION[$cCode][$srcGateName] as $xid => $dSpec) {
                                $tmpOutParams[$cCtr] = array();
                                $id = $xid;
                                $subParams = array();

                                if (isset($tComSpec['static'])) {
                                    foreach ($tComSpec['static'] as $key => $value) {

                                        $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                        $subParams['static'][$key] = $realValue;

                                    }
                                    if (!isset($subParams['static']["transaksi_id"])) {
                                        //									$subParams['static']["transaksi_id"] = $masterID;
                                    }
                                    $subParams['static']["fulldate"] = $fulldate;
                                    $subParams['static']["dtime"] = $dtime;
                                    $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " oleh " . $olehNama;
                                }
                                if (sizeof($subParams) > 0) {
                                    $tmpOutParams[$cCtr][] = $subParams;


                                    $comName = $tComSpec['comName'];
                                    $srcGateName = $tComSpec['srcGateName'];
                                    $srcRawGateName = $tComSpec['srcRawGateName'];
                                    $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();

                                    //                                echo "sub preproc #$it: $comName, sending values <br>";

                                    $mdlName = "Pre" . ucfirst($comName);
                                    $this->load->model("Preprocs/" . $mdlName);
                                    $m = new $mdlName($resultParams);


                                    if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                        $tobeExecuted = true;
                                    }
                                    else {
                                        $tobeExecuted = false;
                                    }

                                    if ($tobeExecuted) {
                                        $m->pair(0, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                        $gotParams = $m->exec();

                                        cekmerah("gotparams dari pre-proc $comName");
                                        arrprint($gotParams);


                                        if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor

                                            foreach ($gotParams as $gateName => $paramSpec) {
                                                cekBiru(":: getParams inject ke $gateName ::");
                                                if (!isset($_SESSION[$cCode][$gateName])) {
                                                    $_SESSION[$cCode][$gateName] = array();
                                                    //                                    cekhijau("building the session: $gateName");
                                                }
                                                else {
                                                    //                                    cekhijau("NOT building the session: $gateName");
                                                }

                                                foreach ($paramSpec as $id => $gSpec) {
                                                    //										$id=$gSpec['id'];


                                                    if (!isset($_SESSION[$cCode][$gateName][$id])) {
                                                        $_SESSION[$cCode][$gateName][$id] = array();
                                                    }


                                                    if (isset($_SESSION[$cCode][$gateName][$id])) {
                                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                            foreach ($gSpec as $key => $val) {
                                                                cekHere(":: injecte ke $gateName, ::: $key diisi dengan $val");
                                                                $_SESSION[$cCode][$gateName][$id][$key] = $val;
                                                            }

                                                        }
                                                    }
                                                    //==inject gotParams to child gate
                                                    cekHitam("srcGateName = $srcGateName :: " . __LINE__);
                                                    if (isset($_SESSION[$cCode][$srcGateName][$id])) {
                                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                            foreach ($gSpec as $key => $val) {
                                                                $_SESSION[$cCode][$srcGateName][$id][$key] = $val;
                                                            }

                                                        }
                                                    }

                                                    //cekMerah("REBUILDING VALUES..");
                                                    if (sizeof($itemNumLabels) > 0) {
                                                        //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                        foreach ($itemNumLabels as $key => $label) {
                                                            //cekHere("$id === $key => $label");
                                                            if (isset($_SESSION[$cCode][$gateName][$id][$key])) {
                                                                $_SESSION[$cCode][$gateName][$id]['sub_' . $key] = ($_SESSION[$cCode][$gateName][$id]['jml'] * $_SESSION[$cCode][$gateName][$id][$key]);
                                                            }
                                                        }
                                                    }
                                                }
                                                //                                    arrPrint($items);die();
                                            }


                                        }

                                    }
                                    else {
                                        cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                                    }


                                }
                            }
                        }
                    }
                    else {
                        //cekKuning("sub-preproc is not set");
                    }


                    $this->load->helper("he_value_builder");
                    fillValues_he_value_builder($jenisTrMaster, 1, 1, $configCoreMasterModulJenisBuilder, $configUiMasterModulJenis, $configValuesMasterModulJenis, $ppnFactor);


                    //region injector gerbang value untuk pembatalan ppv dan selisih
                    if (isset($_SESSION[$cCode]["revert"]["preProc"]["replacer"])) {
                        $replace = $_SESSION[$cCode]["revert"]["preProc"]["replacer"];
                        $jenisTrReference = $_SESSION[$cCode]["main"]["jenisTr_reference"];
                        switch ($jenisTrReference) {
                            case "460":
                                $tempCalculate = array(
                                    //                                "selisih" => ($_SESSION[$cCode]["main"]["hpp_riil"] + $_SESSION[$cCode]["main"]["exchange__nilai_tambah_ppn_in"]) - ($_SESSION[$cCode]["main"]["exchange__nilai_tambah_piutang_pembelian"]),
                                    //                                "exchange__harga" => $_SESSION[$cCode]["main"]["hpp_riil"],//riil
                                    //                                "exchange__hpp_nppv" => $_SESSION[$cCode]["main"]["hpp_nppv"],//riil+ppv
                                    //                                "exchange__ppv" => $_SESSION[$cCode]["main"]["ppv_riil"],//riil+ppv
                                );
                                break;
                            default:
                                $tempCalculate = array(
                                    "selisih" => ($_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"]) - ($_SESSION[$cCode]["main"]["nett"] + $_SESSION[$cCode]["main"]["ppv"]),
                                    "hpp_nppv" => $_SESSION[$cCode]["main"]["hpp"],
                                    "hpp_nppn" => $_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"],
                                );
                                break;
                        }
                        //                    $tempCalculate = array(
                        //                        "selisih" => ($_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"]) - ($_SESSION[$cCode]["main"]["nett"] + $_SESSION[$cCode]["main"]["ppv"]),
                        //                        "hpp_nppv" => $_SESSION[$cCode]["main"]["hpp"],
                        //                        "hpp_nppn" => $_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"],
                        //                    );

                        //arrPrintWebs($tempCalculate);
                        foreach ($replace['recalculate'] as $iKey => $gate) {
                            $_SESSION[$cCode]["main"][$gate] = $tempCalculate[$gate];
                        }

                        cekLime($_SESSION[$cCode]["main"]["hpp"] . "+" . $_SESSION[$cCode]["main"]["ppn"] . "-" . $_SESSION[$cCode]["main"]["nett"]);

                    }

                    //endregion


                }
                else {
                    echo("no processor defined. skipping preprocessor..<br>");
                }
                //endregion

                //region pre-processors auto (master)
                $iterator = isset($configCoreMasterModulJenisBuilder['preProcessorAuto'][$jenisTrTarget]['master']) ? $configCoreMasterModulJenisBuilder['preProcessorAuto'][$jenisTrTarget]['master'] : array();
                if (sizeof($iterator) > 0) {

                    $itemNumLabels = isset($configUiMasterModulJenis['shoppingCartNumFields']) ? $configUiMasterModulJenis['shoppingCartNumFields'] : array();

                    if (sizeof($iterator) > 0) {
                        foreach ($iterator as $cCtr => $tComSpec) {

                            $comName = $tComSpec['comName'];
                            $srcGateName = $tComSpec['srcGateName'];
                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                            $switchResultParams = isset($tComSpec['switchResultParams']) ? $tComSpec['switchResultParams'] : false;

                            $subParams = array();

                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {
                                    $realValue = makeValue($value, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                                    $subParams['static'][$key] = $realValue;

                                    //                                cekPink2("$comName == $value || $realValue");
                                    //                                cekPink2("valas_harga " . $_SESSION[$cCode]['main']['valas_harga']);
                                    //                                cekPink2("uang_muka_valas_harga " . $_SESSION[$cCode]['main']['uang_muka_valas_harga']);
                                }

                                if (!isset($subParams['static']["transaksi_id"])) {
                                    //									$subParams['static']["transaksi_id"] = $masterID;
                                }

                                $subParams['static']["fulldate"] = $fulldate;
                                $subParams['static']["dtime"] = $dtime;
                                $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " oleh " . $olehNama;
                            }
                            $tmpOutParams[$cCtr] = $subParams;

                            $mdlName = "Pre" . ucfirst($comName);
                            $this->load->model("Preprocs/" . $mdlName);
                            $m = new $mdlName($resultParams);


                            if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                $tobeExecuted = true;
                            }
                            else {
                                $tobeExecuted = false;
                            }

                            if ($tobeExecuted) {
                                $m->pair(0, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                $gotParams = $m->exec();

                                cekbiru("gotparams dari pre-proc $comName");
                                arrprint($gotParams);

                                if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                    foreach ($gotParams as $gateName => $gSpec) {
                                        //										$id=$gSpec['id'];

                                        if ($switchResultParams == true) {

                                            foreach ($gSpec as $id => $ggSpec) {
                                                if (!isset($_SESSION[$cCode][$gateName][$id])) {
                                                    $_SESSION[$cCode][$gateName][$id] = array();
                                                }

                                                if (isset($_SESSION[$cCode][$gateName][$id])) {
                                                    if (is_array($ggSpec) && sizeof($ggSpec) > 0) {
                                                        foreach ($ggSpec as $key => $val) {
                                                            $_SESSION[$cCode][$gateName][$id][$key] = $val;
                                                        }
                                                    }
                                                }

                                                //cekMerah("REBUILDING VALUES..");
                                                if (sizeof($itemNumLabels) > 0) {
                                                    //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                    foreach ($itemNumLabels as $key => $label) {
                                                        //cekHere("$id === $key => $label");
                                                        if (isset($_SESSION[$cCode][$gateName][$id][$key])) {
                                                            $_SESSION[$cCode][$gateName][$id]['sub_' . $key] = ($_SESSION[$cCode][$gateName][$id]['jml'] * $_SESSION[$cCode][$gateName][$id][$key]);
                                                        }
                                                    }
                                                }

                                            }
                                        }
                                        else {
                                            if (isset($_SESSION[$cCode]['main'])) {
                                                if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                    foreach ($gSpec as $key => $val) {
                                                        $_SESSION[$cCode]['main'][$key] = $val;
                                                    }
                                                }
                                            }
                                            //==inject gotParams to child gate
                                            if (isset($_SESSION[$cCode]['main'])) {
                                                if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                    foreach ($gSpec as $key => $val) {
                                                        $_SESSION[$cCode]['main'][$key] = $val;
                                                    }
                                                }
                                            }
                                            //cekMerah("REBUILDING VALUES..");
                                            if (sizeof($itemNumLabels) > 0) {
                                                //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                foreach ($itemNumLabels as $key => $label) {
                                                    cekHere("$id === $key => $label");
                                                    if (isset($_SESSION[$cCode]['main'][$key])) {
                                                        $_SESSION[$cCode]['main']['sub_' . $key] = ($_SESSION[$cCode]['main']['jml'] * $_SESSION[$cCode]['main'][$key]);
                                                    }
                                                }
                                            }
                                        }

                                    }
                                }
                            }
                            else {
                                cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                            }

                            cekPink2("fillvalue setelah $comName");
                            $this->load->helper("he_value_builder");
                            fillValues_he_value_builder($jenisTrMaster, 1, 1, $configCoreMasterModulJenisBuilder, $configUiMasterModulJenis, $configValuesMasterModulJenis);


                        }
                    }
                    else {
                        //cekKuning("sub-preproc is not set");
                    }

                    $this->load->helper("he_value_builder");
                    fillValues_he_value_builder($jenisTrMaster, 1, 1, $configCoreMasterModulJenisBuilder, $configUiMasterModulJenis, $configValuesMasterModulJenis, $ppnFactor);
                }
                else {
                    echo("no processor defined. skipping preprocessor..<br>");
                }
                //endregion

                //region processing sub-components auto
                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                $filterNeeded = false;
                $iterator = isset($configCoreMasterModulJenisBuilder['componentsAuto'][$jenisTrTarget]['detail']) ? $configCoreMasterModulJenisBuilder['componentsAuto'][$jenisTrTarget]['detail'] : array();
                $revertedTarget = "";
                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $tmpOutParams[$cCtr] = array();
                        $gg = 0;
                        $srcGateName = $tComSpec['srcGateName'];
                        foreach ($_SESSION[$cCode][$srcGateName] as $id => $dSpec) {
                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $_SESSION[$cCode][$srcGateName][$id][$comName], $comName);
                            }
                            $mdlName = "Com" . ucfirst($comName);
                            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                //cekLime($mdlName. "line");
                                $filterNeeded = true;
                            }
                            else {
                                cekLime($mdlName . "like");
                                $filterNeeded = false;
                            }
                            echo "sub-component: $comName, initializing values <br>";
                            $subParams = array();                            //arrPrint($tComSpec);
                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $_SESSION[$cCode][$srcGateName][$id][$key], $key);
                                    }

                                    $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                    $subParams['loop'][$key] = $realValue;

                                    if ($filterNeeded) {
                                        if ($subParams['loop'][$key] == 0) {
                                            unset($subParams['loop'][$key]);
                                        }
                                    }
                                }
                            }
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {

                                    $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;

                                }
                                if (!isset($subParams['static']["transaksi_id"])) {
                                    $subParams['static']["transaksi_id"] = $insertID;
                                }
                                if (!isset($subParams['static']["transaksi_no"])) {
                                    $subParams['static']["transaksi_no"] = $insertNum;
                                }

                                $subParams['static']["fulldate"] = $fulldate;
                                $subParams['static']["dtime"] = $dtime;
                                $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $olehNama;
                                if (strlen($revertedTarget) > 1) {
                                    $subParams['static']['reverted_target'] = $revertedTarget;
                                }
                            }
                            if (sizeof($subParams) > 0) {
                                if ($filterNeeded) {
                                    if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                    //                                CekHijiau("asem" .$gg++);
                                }
                            }
                            else {
                                cekhitam("subparam TIDAK ada isinya");
                            }
                        }

                        $componentGate['detail'][$cCtr] = $subParams;
                    }

                    foreach ($iterator as $cCtr => $tComSpec) {
                        $srcGateName = $tComSpec['srcGateName'];
                        foreach ($_SESSION[$cCode][$srcGateName] as $id => $dSpec) {
                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $_SESSION[$cCode][$srcGateName][$id][$comName], $comName);
                            }
                        }
                        echo "sub component: $comName, sending values <br>";

                        $mdlName = "Com" . ucfirst($comName);
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();
                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        if ($tobeExecuted) {
                            $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        }
                        else {
                            cekMerah("$comName tidak eksekusi");
                        }

                    }
                }
                else {
                    //cekKuning("subcomponents is not set");
                }
                //endregion

                //region processing main components auto
                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                $iterator = isset($configCoreMasterModulJenisBuilder['componentsAuto'][$jenisTrTarget]['master']) ? $configCoreMasterModulJenisBuilder['componentsAuto'][$jenisTrTarget]['master'] : array();
                if (sizeof($iterator) > 0) {
                    $componentConfig['master'] = $iterator;
                    $cCtr = 0;
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $cCtr++;
                        $comName = $tComSpec['comName'];
                        if (substr($comName, 0, 1) == "{") {
                            $comName = trim($comName, "{");
                            $comName = trim($comName, "}");
                            $comName = str_replace($comName, $_SESSION[$cCode]['main'][$comName], $comName);
                        }
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        echo "component # $cCtr: $comName<br>";

                        $dSpec = $_SESSION[$cCode][$srcGateName];
                        $tmpOutParams = array();
                        if (isset($tComSpec['loop'])) {
                            foreach ($tComSpec['loop'] as $key => $value) {
                                if (substr($key, 0, 1) == "{") {
                                    $key = trim($key, "{");
                                    $key = trim($key, "}");
                                    $key = str_replace($key, $_SESSION[$cCode]['main'][$key], $key);
                                }
                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                                $tmpOutParams['loop'][$key] = $realValue;
                            }
                        }
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {

                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                                $tmpOutParams['static'][$key] = $realValue;

                            }
                            if (!isset($tmpOutParams['static']["transaksi_id"])) {
                                $tmpOutParams['static']["transaksi_id"] = $insertID;
                            }
                            if (!isset($tmpOutParams['static']["transaksi_no"])) {
                                $tmpOutParams['static']["transaksi_no"] = $insertNum;
                            }
                            $tmpOutParams['static']["urut"] = $cCtr;
                            $tmpOutParams['static']["fulldate"] = $fulldate;
                            $tmpOutParams['static']["dtime"] = $dtime;
                            $tmpOutParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $olehNama;
                        }

                        if (isset($tComSpec['static2'])) {
                            foreach ($tComSpec['static2'] as $key => $value) {

                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$cCtr], $_SESSION[$cCode][$srcGateName][$cCtr], 0);
                                $tmpOutParams['static2'][$key] = $realValue;

                            }
                            if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                                $tmpOutParams['static2']["transaksi_id"] = $insertID;
                            }
                            if (!isset($tmpOutParams['static2']["transaksi_no"])) {
                                $tmpOutParams['static2']["transaksi_no"] = $insertNum;
                            }

                            $tmpOutParams['static2']["fulldate"] = $fulldate;
                            $tmpOutParams['static2']["dtime"] = $dtime;
                            $tmpOutParams['static2']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $olehNama;

                        }


                        $mdlName = "Com" . ucfirst($comName);
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();
                        //===filter value nol, jika harus difilter
                        $tobeExecuted = true;
                        if (in_array($mdlName, $compValidators)) {

                            $loopParams = isset($tmpOutParams['loop']) ? $tmpOutParams['loop'] : array();
                            if (sizeof($loopParams) > 0) {
                                foreach ($loopParams as $key => $val) {
                                    cekmerah("$comName : $key = $val ");
                                    if ($val == 0) {
                                        unset($tmpOutParams['loop'][$key]);
                                    }
                                }
                            }
                            if (sizeof($tmpOutParams['loop']) < 1) {
                                $tobeExecuted = false;
                            }

                        }

                        if ($tobeExecuted) {
                            $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        }

                        $componentGate['master'][$cCtr] = $tmpOutParams;
                    }
                }
                else {
                    //cekKuning("components is not set");
                }
                //endregion

                //region processing sub-post-processors auto, always
                $iterator = isset($configCoreMasterModulJenisBuilder['postProcessorAuto'][$jenisTrTarget]['detail']) ? $configCoreMasterModulJenisBuilder['postProcessorAuto'][$jenisTrTarget]['detail'] : array();
                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        echo "[$cCtr] sub-postProcessor: $comName, gate: $srcGateName, initializing values <br>";
                        $tmpOutParams[$cCtr] = array();
                        if (isset($_SESSION[$cCode][$srcGateName]) && (sizeof($_SESSION[$cCode][$srcGateName]) > 0)) {
                            foreach ($_SESSION[$cCode][$srcGateName] as $xid => $dSpec) {
                                $id = $xid;
                                $subParams = array();
                                if (isset($tComSpec['loop'])) {
                                    foreach ($tComSpec['loop'] as $key => $value) {

                                        $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                        $subParams['loop'][$key] = $realValue;

                                    }
                                }
                                if (isset($tComSpec['static'])) {
                                    foreach ($tComSpec['static'] as $key => $value) {
                                        cekHitam("gate: $srcGateName, dengan key $id");
                                        $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                        $subParams['static'][$key] = $realValue;

                                    }
                                    if (!isset($subParams['static']["transaksi_id"])) {
                                        $subParams['static']["transaksi_id"] = $insertID;
                                    }
                                    if (!isset($subParams['static']["transaksi_no"])) {
                                        $subParams['static']["transaksi_no"] = $insertNum;
                                    }
                                    if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                        foreach ($paramPatchers[$comName] as $k => $v) {
                                            if (!isset($subParams['static'][$k])) {
                                                $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                            }
                                        }
                                    }
                                    if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                        $jenis = $_SESSION[$cCode]['main']['jenis'];
                                        foreach ($paramForceFillers[$comName] as $k => $v) {
                                            $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                        }
                                    }

                                    $subParams['static']["fulldate"] = $fulldate;
                                    $subParams['static']["dtime"] = $dtime;
                                    if (isset($_SESSION[$cCode]['revert']['postProc']['detail'])) {
                                        $subParams['static']["reverted_target"] = $_SESSION[$cCode]['main']['pihakExternID'];
                                    }

                                    $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $olehNama;
                                }

                                if (sizeof($subParams) > 0) {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                }
                            }
                        }
                    }

                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        if (isset($_SESSION[$cCode][$srcGateName])) {
                            $mdlName = "Com" . ucfirst($comName);
                            $this->load->model("Coms/" . $mdlName);
                            $m = new $mdlName();
                            $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName | " . $this->jenisTr . " | " . __FUNCTION__ . " | " . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        }

                    }
                }
                //endregion

                //region processing main-post-processors auto, always
                $iterator = isset($configCoreMasterModulJenisBuilder['postProcessorAuto'][$jenisTrTarget]['master']) ? $configCoreMasterModulJenisBuilder['postProcessorAuto'][$jenisTrTarget]['master'] : array();
                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        echo "post-processor: $comName<br>LINE: " . __LINE__;

                        $dSpec = $_SESSION[$cCode][$srcGateName];
                        $tmpOutParams = array();
                        if (isset($tComSpec['loop'])) {
                            foreach ($tComSpec['loop'] as $key => $value) {

                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                                $tmpOutParams['loop'][$key] = $realValue;

                            }
                        }
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {
                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                                $tmpOutParams['static'][$key] = $realValue;
                            }
                            if (!isset($tmpOutParams['static']["transaksi_id"])) {
                                $tmpOutParams['static']["transaksi_id"] = $insertID;
                            }
                            if (!isset($tmpOutParams['static']["transaksi_no"])) {
                                $tmpOutParams['static']["transaksi_no"] = $insertNum;
                            }
                            if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                foreach ($paramPatchers[$comName] as $k => $v) {
                                    if (!isset($tmpOutParams['static'][$k])) {
                                        $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
                                    }
                                }
                            }
                            if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                $jenis = $_SESSION[$cCode]['main']['jenis'];
                                foreach ($paramForceFillers[$comName] as $k => $v) {
                                    $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
                                }
                            }

                            $tmpOutParams['static']["fulldate"] = $fulldate;
                            $tmpOutParams['static']["dtime"] = $dtime;
                            $tmpOutParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $olehNama;
                        }
                        if (isset($tComSpec['static2'])) {
                            foreach ($tComSpec['static2'] as $key => $value) {

                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$cCtr], $_SESSION[$cCode][$srcGateName][$cCtr], 0);
                                $tmpOutParams['static2'][$key] = $realValue;

                            }
                            if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                                $tmpOutParams['static2']["transaksi_id"] = $insertID;
                            }
                            if (!isset($tmpOutParams['static2']["transaksi_no"])) {
                                $tmpOutParams['static2']["transaksi_no"] = $insertNum;
                            }

                            $tmpOutParams['static2']["fulldate"] = $fulldate;
                            $tmpOutParams['static2']["dtime"] = $dtime;
                            $tmpOutParams['static2']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $olehNama;


                        }

                        $mdlName = "Com" . ucfirst($comName);
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();
                        $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada post-processor: $comName | " . $this->jenisTr . " | " . __FUNCTION__ . " | " . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        cekHitam($this->db->last_query());
                    }
                }
                else {

                }
                //endregion
            }

            cekHijau("BAGIAN AUTO SETOR KE DC/PUSAT SELESAI");
//            mati_disini(__LINE__);
            cekHijau("MULAI BAGIAN KONEK 110 KE DC/PUSAT");

            //region connecting antar cabang
            $stepNum = 1;
            $origJenis = $jenisTrMaster;
//            $steps = isset($this->configUi[$origJenis]['steps']) ? $this->configUi[$origJenis]['steps'] : array();
//            $connector = isset($this->configUi[$origJenis]['connectTo']) ? $this->configUi[$origJenis]['connectTo'] : "";
//            $preReplacer = isset($this->configUi[$jenisTrMaster]['replacerConnectTo']) ? $this->configUi[$jenisTrMaster]['replacerConnectTo'] : array();
//            $replacerConnectToItems = isset($this->configUi[$jenisTrMaster]['replacerConnectToItems']) ? $this->configUi[$jenisTrMaster]['replacerConnectToItems'] : array();
//            $validateValueConnector = isset($this->configUi[$jenisTrMaster]['connectoValidate'][$stepNum]) ? $this->configUi[$jenisTrMaster]['connectoValidate'][$stepNum] : array();
            $steps = isset($configUiMasterModulJenis['steps']) ? $configUiMasterModulJenis['steps'] : array();
            $connector = isset($configUiMasterModulJenis['connectTo']) ? $configUiMasterModulJenis['connectTo'] : "";
            $preReplacer = isset($configUiMasterModulJenis['replacerConnectTo']) ? $configUiMasterModulJenis['replacerConnectTo'] : array();
            $replacerConnectToItems = isset($configUiMasterModulJenis['replacerConnectToItems']) ? $configUiMasterModulJenis['replacerConnectToItems'] : array();
            $validateValueConnector = isset($configUiMasterModulJenis['connectoValidate'][$stepNum]) ? $configUiMasterModulJenis['connectoValidate'][$stepNum] : array();

            $mongoListConnect = array();
            $mongRegIDConnect = array();

            if (strlen($connector) > 0) {
                cekMerah("CONNEC TO $connector |$stepNum|" . sizeof($steps));
                $masterID = $getTrID;
                $configUiMasterModulJenis = loadConfigModulJenis_he_misc($connector, "coTransaksiUi");
                $configCoreMasterModulJenis = loadConfigModulJenis_he_misc($connector, "coTransaksiCore");
                $configLayoutMasterModulJenis = loadConfigModulJenis_he_misc($connector, "coTransaksiLayout");
                $tCodeTargetJenisTransaksi = $configUiMasterModulJenis['steps'][1]['target'];
                $modul_transaksi = $this->config->item("heTransaksi_ui")[$connector]["modul"];
                if (isset($this->configUi[$jenisTrMaster]['connectoValidate'][$stepNum])) {
                    $validateValueConnector = $this->configUi[$jenisTrMaster]['connectoValidate'][$stepNum];
                    $preVal = $_SESSION[$cCode]['main'][$validateValueConnector];
                    $stepNum = $preVal > 0 ? $stepNum : "1000";//1000 untuk nglewatin step biar gak jalan connectingnya karena nilai yang dicari 0 kasusnya cash in advance ppn sudah masuk pusat tidak perlu diterbitkan auto dorong ppn ke pusat
                }

                if ($stepNum == sizeof($steps)) {
                    cekMerah("NOW CONNECTING to $connector");


                    if (sizeof($configCoreMasterModulJenis) < 1) {
                        die("konfigurasi connector harus memiliki step lebih dari satu!");
                    }


                    $oldCode = $cCode;
                    $cCode = "_TR_" . $connector;


                    he_clone_transaction_session($oldCode, $cCode);
                    //==replace pertama
                    $masterReplacersO = array(
                        "jenisTr" => $connector,
                        "jenisTrMaster" => $connector,
                        "jenisTrTop" => $configUiMasterModulJenis['steps'][1]['target'],
                        "jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                        "jenis_label" => $configUiMasterModulJenis['steps'][1]['label'],
                        "transaksi_jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                        "stepCode" => $configUiMasterModulJenis['steps'][1]['target'],
                        "placeID" => isset($preReplacer['place2ID']) ? $preReplacer['place2ID'] : $_SESSION[$cCode]['main']['place2ID'],
                        "placeName" => isset($preReplacer['place2Name']) ? $preReplacer['place2Name'] : $_SESSION[$cCode]['main']['place2Name'],
                        "place2ID" => $_SESSION[$cCode]['main']['placeID'],
                        "place2Name" => $_SESSION[$cCode]['main']['placeName'],
                        "cabangID" => isset($preReplacer['cabang2ID']) ? $preReplacer['cabang2ID'] : $_SESSION[$cCode]['main']['place2ID'],
                        "cabangName" => isset($preReplacer['place2Name']) ? $preReplacer['place2Name'] : $_SESSION[$cCode]['main']['place2Name'],
                        "cabang2ID" => $_SESSION[$cCode]['main']['placeID'],
                        "cabang2Name" => $_SESSION[$cCode]['main']['placeName'],
                        //
                        "gudang2ID" => $_SESSION[$cCode]['main']['gudangID'],
                        "gudang2Name" => $_SESSION[$cCode]['main']['gudangName'],
                        "gudangID" => isset($preReplacer['gudang2ID']) ? $preReplacer['gudang2ID'] : $_SESSION[$cCode]['main']['gudang2ID'],
                        "gudangName" => isset($preReplacer['gudang2Name']) ? $preReplacer['gudang2Name'] : $_SESSION[$cCode]['main']['gudang2Name'],
                        "pihakID" => isset($_SESSION[$cCode]['main']['placeID']) ? $_SESSION[$cCode]['main']['placeID'] : "",
                        "pihakName" => isset($_SESSION[$cCode]['main']['placeName']) ? $_SESSION[$cCode]['main']['placeName'] : "",
                        "pihakName2" => $_SESSION[$cCode]['main']['placeName'],
                        "gudang" => $_SESSION[$cCode]['main']['gudangID'],
                        "gudang__name" => $_SESSION[$cCode]['main']['gudangName'],
                        "gudang__label" => $_SESSION[$cCode]['main']['gudangName'],
                        "efaktur_source" => isset($preReplacer['efaktur_source']) ? $_SESSION[$cCode]['main']['nomer'] : "",
                        "referensi_id" => $midmaster,
                        "referensi_nomer" => $mNumMaster,
                        "referensi_jenis" => $mJenisMaster,

                    );
                    foreach ($masterReplacersO as $key => $val) {
                        $_SESSION[$cCode]['main'][$key] = $val;
                        //                    $_SESSION[$cCode]['main'][$key] = $val;
                    }
                    $masterReplacers = array(
                        //                    "referensi_id" => $masterID, (dimatikan)
                        "inv" => $tmpNomorNota,
                        "jenis_master" => $connector,
                        "jenis_top" => $configUiMasterModulJenis['steps'][1]['target'],
                        "jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                        "jenis_label" => $configUiMasterModulJenis['steps'][1]['label'],
                        "transaksi_jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                        "cabang_id" => isset($preReplacer['cabang2ID']) ? $preReplacer['cabang2ID'] : $_SESSION[$cCode]['tableIn_master']['cabang2_id'],
                        "cabang_nama" => isset($preReplacer['cabang2Name']) ? $preReplacer['cabang2Name'] : $_SESSION[$cCode]['tableIn_master']['cabang2_nama'],
                        "cabang2_id" => $_SESSION[$cCode]['tableIn_master']['cabang_id'],
                        "cabang2_nama" => $_SESSION[$cCode]['tableIn_master']['cabang_nama'],
                        "gudang_id" => isset($preReplacer['gudang2ID']) ? $preReplacer['gudang2ID'] : $_SESSION[$cCode]['tableIn_master']['gudang2_id'],
                        "gudang_nama" => isset($preReplacer['gudang2Name']) ? $preReplacer['gudang2Name'] : $_SESSION[$cCode]['tableIn_master']['gudang2_nama'],
                        "gudang2_id" => $_SESSION[$cCode]['tableIn_master']['gudang_id'],
                        "gudang2_nama" => $_SESSION[$cCode]['tableIn_master']['gudang_nama'],
                        "gudang" => $_SESSION[$cCode]['tableIn_master']['gudang_id'],
                        "gudang__name" => $_SESSION[$cCode]['tableIn_master']['gudang_nama'],
                        "gudang__label" => $_SESSION[$cCode]['tableIn_master']['gudang_nama'],

                        "step_avail" => sizeof($configUiMasterModulJenis['steps']),
                        "step_current" => 1,
                        "step_number" => 1,
                        "next_step_code" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['target'] : "",
                        "next_step_label" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['label'] : "",
                        "next_group_code" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['userGroup'] : "",
                        "next_step_num" => isset($configUiMasterModulJenis['steps'][2]) ? 2 : "0",
                        "efaktur_source" => isset($preReplacer['efaktur_source']) ? $_SESSION[$cCode]['main']['nomer'] : "",
                        "referensi_id" => $midmaster,
                        "referensi_nomer" => $mNumMaster,
                        "referensi_jenis" => $mJenisMaster,

                    );
                    foreach ($masterReplacers as $key => $val) {
                        $_SESSION[$cCode]['tableIn_master'][$key] = $val;
                    }

                    //replacer items
                    if (count($replacerConnectToItems) > 0) {
                        foreach ($_SESSION[$cCode]["items"] as $iiItems => $datas) {
                            foreach ($replacerConnectToItems as $nKey => $nValue_key) {
                                $_SESSION[$cCode]["items"][$iiItems][$nKey] = $datas[$nValue_key];
                            }
                        }
                    }


                    //region penomoran receipt #2
                    //<editor-fold desc="==========penomoran">
                    $this->load->model("CustomCounter");
                    $cn = new CustomCounter("transaksi");
                    $cn->setType("transaksi");
                    $cn->setModul($modul_transaksi);
                    $cn->setStepCode($tCodeTargetJenisTransaksi);
                    $counterForNumber = array($configCoreMasterModulJenis['formatNota']);
                    if (!in_array($counterForNumber[0], $configCoreMasterModulJenis['counters'])) {
                        die(__LINE__ . " Used number should be registered in 'counters' config as well");
                    }

                    foreach ($counterForNumber as $i => $cRawParams) {
                        $cParams = explode("|", $cRawParams);
                        $cValues = array();
                        foreach ($cParams as $param) {
                            //                    $cValues[$i][$param] = $_SESSION[$cCode]['main'][$param];
                            //                    echo "filling $param with " . $_SESSION[$cCode]['main'][$param] . "<br>";
                            $cValues[$i][$param] = $_SESSION[$cCode]['main'][$param];
                            //                    echo "filling $param with " . $_SESSION[$cCode]['main'][$param] . "<br>";
                        }
                        $cRawValues = implode("|", $cValues[$i]);
                        $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

                    }

                    $tmpNomorNota2 = $paramSpec['paramString'];
                    $tmpNomorNota2Alias = formatNota("nomer_nolink", $tmpNomorNota2);


                    //</editor-fold>
                    //endregion

                    //region dynamic counters #2
                    // <editor-fold defaultstate="collapsed" desc="==========__init+update dynamic-counters ">
                    $cn = new CustomCounter("transaksi");
                    $cn->setType("transaksi");
                    $cn->setModul($modul_transaksi);
                    $cn->setStepCode($tCodeTargetJenisTransaksi);
                    $configCustomParams = $configCoreMasterModulJenis['counters'];
                    $configCustomParams[] = "stepCode";
                    if (sizeof($configCustomParams) > 0) {
                        $cContent = array();
                        foreach ($configCustomParams as $i => $cRawParams) {
                            $cParams = explode("|", $cRawParams);
                            $cValues = array();
                            foreach ($cParams as $param) {
                                $cValues[$i][$param] = $_SESSION[$cCode]['main'][$param];
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
                            //echo "<hr>";
                        }
                    }
                    $appliedCounters2 = base64_encode(serialize($cContent));
                    $appliedCounters_inText2 = print_r($cContent, true);
                    // </editor-fold>
                    //endregion

                    //region tambahan counter
                    $this->load->library("CounterNumber");
                    $ccn = new CounterNumber();
                    $ccn->setCCode($cCode);
                    $ccn->setJenisTr($connector);
                    $ccn->setTransaksiGate($_SESSION[$cCode]['tableIn_master']);
                    $ccn->setMainGate($_SESSION[$cCode]['main']);
                    $ccn->setItemsGate($_SESSION[$cCode]['items']);
                    $ccn->setItems2SumGate($_SESSION[$cCode]['items2_sum']);
                    $new_counter = $ccn->getCounterNumber();


                    if (isset($new_counter['main']) && sizeof($new_counter['main']) > 0) {
                        foreach ($new_counter['main'] as $ckey => $cval) {
                            $_SESSION[$cCode]['tableIn_master'][$ckey] = $cval;
                            $_SESSION[$cCode]['main'][$ckey] = $cval;
                        }
                    }
                    if (isset($new_counter['items']) && sizeof($new_counter['items']) > 0) {
                        foreach ($new_counter['items'] as $ikey => $iSpec) {
                            foreach ($iSpec as $iikey => $iival) {
                                $_SESSION[$cCode]['items'][$ikey][$iikey] = $iival;
                            }
                        }
                    }
                    if (isset($new_counter['items2_sum']) && sizeof($new_counter['items2_sum']) > 0) {
                        foreach ($new_counter['items2_sum'] as $ikey => $iSpec) {
                            foreach ($iSpec as $iikey => $iival) {
                                $_SESSION[$cCode]['items2_sum'][$ikey][$iikey] = $iival;
                            }
                        }
                    }
                    //endregion

                    $addValues = array(
                        'counters' => $appliedCounters2,
                        'counters_intext' => $appliedCounters_inText2,
                        'nomer' => $tmpNomorNota2,
                        'nomer2' => $tmpNomorNota2Alias,
                        'dtime' => $dtime,
                        'fulldate' => $fulldate,
                    );
                    foreach ($addValues as $key => $val) {
                        $_SESSION[$cCode]['tableIn_master'][$key] = $val;
                    }

                    //===cloning nota cab1 ke cab2
                    //===daftar perbedaan
                    //== referensi_id, inv, jenis, nomer, counters, counters_inText, cabang_id, cabang_nama, cabang2_id, cabang2_nama,

                    //==replace kedua
                    $masterReplacers = array(
                        "nomer" => $tmpNomorNota2,
                        "nomer2" => $tmpNomorNota2Alias,
                        "counters" => $appliedCounters2,
                        "counters_intext" => $appliedCounters_inText2,
                    );
                    foreach ($masterReplacers as $key => $val) {
                        $_SESSION[$cCode]['tableIn_master'][$key] = $val;
                    }

                    //===cloning detail/items cabang1 ke cabang2
                    //===yang direplace: sub_step_number, sub_step_current, sub_step_avail, next_substep_num, next_substep_code, next_substep_label, next_subgroup_code
                    $detailReplacers = array(
                        "sub_step_avail" => sizeof($configUiMasterModulJenis['steps']),
                        "sub_step_current" => 1,
                        "sub_step_number" => 1,
                        "next_substep_num" => $_SESSION[$cCode]['tableIn_master']['next_step_num'],
                        "next_substep_code" => $_SESSION[$cCode]['tableIn_master']['next_step_code'],
                        "next_substep_label" => $_SESSION[$cCode]['tableIn_master']['next_step_label'],
                        "next_subgroup_code" => $_SESSION[$cCode]['tableIn_master']['next_group_code'],
                    );
                    if (isset($_SESSION[$cCode]['tableIn_detail']) && sizeof($_SESSION[$cCode]['tableIn_detail']) > 0) {
                        //                    cekmerah("tulis rincian transaksi kedua");
                        foreach ($_SESSION[$cCode]['tableIn_detail'] as $k => $dSpec) {
                            foreach ($dSpec as $key => $val) {
                                $_SESSION[$cCode]['tableIn_detail'][$k][$key] = isset($detailReplacers[$key]) ? $detailReplacers[$key] : $val;
                            }
                        }
                    }
                    else {
                        //                    cekmerah("GAGAL tulis rincian transaksi kedua");
                    }


                    //region ----------write transaksi & transaksi_data #2
                    if (isset($_SESSION[$cCode]['tableIn_master']) && sizeof($_SESSION[$cCode]['tableIn_master']) > 0) {
                        $tr = new MdlTransaksi();
                        $insertID = $tr->writeMainEntries($_SESSION[$cCode]['tableIn_master']);
                        cekUngu($this->db->last_query());
                        $epID = $tr->writeMainEntries_entryPoint($insertID, $masterID, $_SESSION[$cCode]['tableIn_master']);
                        $insertNum = $_SESSION[$cCode]['tableIn_master']['nomer'];
                        $_SESSION[$cCode]['main']['nomer'] = $insertNum;
                        $mongoListConnect['main'] = array($insertID, $epID);
                        cekmerah("tulis transaksi kedua :: trID $insertID");
                        cekmerah($this->db->last_query());
                        if ($insertID < 1) {
                            die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                        }
                    }
                    else {
                        cekmerah("GAGAL tulis transaksi kedua");
                    }
                    if (isset($_SESSION[$cCode]['tableIn_master_values']) && sizeof($_SESSION[$cCode]['tableIn_master_values']) > 0) {
                        $inserMainValues = array();
                        foreach ($_SESSION[$cCode]['tableIn_master_values'] as $key => $val) {
                            $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                            $inserMainValues[] = $dd;
                            $mongoListConnect['mainValues'][] = $dd;
                        }
                        if (sizeof($inserMainValues) > 0) {
                            $arrBlob = blobEncode($inserMainValues);
                            $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                        }
                    }
                    if (isset($_SESSION[$cCode]['main_add_values']) && sizeof($_SESSION[$cCode]['main_add_values']) > 0) {
                        foreach ($_SESSION[$cCode]['main_add_values'] as $key => $val) {
                            $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                            $mongoListConnect['mainValues'][] = $dd;
                        }
                    }
                    if (isset($_SESSION[$cCode]['main_inputs']) && sizeof($_SESSION[$cCode]['main_inputs']) > 0) {
                        foreach ($_SESSION[$cCode]['main_inputs'] as $key => $val) {
                            $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                            $inserMainValues[] = $dd;
                            $mongoListConnect['mainValues'][] = $dd;
                        }
                    }
                    if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
                        //                    cekMerah("ada mainElements");
                        foreach ($_SESSION[$cCode]['main_elements'] as $elName => $aSpec) {
                            $tr->writeMainElements($insertID, array(
                                "mdl_name" => isset($aSpec['mdl_name']) ? $aSpec['mdl_name'] : "",
                                "key" => isset($aSpec['key']) ? $aSpec['key'] : 0,
                                "value" => isset($aSpec['value']) ? $aSpec['value'] : "",
                                "name" => $aSpec['name'],
                                "label" => $aSpec['label'],
                                "contents" => isset($aSpec['contents']) ? $aSpec['contents'] : "",
                                "contents_intext" => isset($aSpec['contents_intext']) ? $aSpec['contents_intext'] : "",

                            ));
                        }
                    }
                    if (isset($_SESSION[$cCode]['tableIn_detail']) && sizeof($_SESSION[$cCode]['tableIn_detail']) > 0) {
                        $insertIDs = array();
                        $insertDeIDs = array();
                        foreach ($_SESSION[$cCode]['tableIn_detail'] as $dSpec) {
                            $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                            if ($insertDetailID < 1) {
                                die("Gagal saat berusaha write transaction detail entry pada " . __FILE__ . " baris " . __LINE__);
                            }
                            else {
                                $insertIDs[] = $insertDetailID;
                                $insertDeIDs[$insertID][] = $insertDetailID;
                                $mongoListConnect['detail'][] = $insertDetailID;
                            }
                            if ($epID != 999) {
                                $insertEpID = $tr->writeDetailEntries($epID, $dSpec);
                                if ($insertEpID < 1) {
                                    die("Gagal saat berusaha write transaction detail entry point pada " . __FILE__ . " baris " . __LINE__);
                                }
                                else {
                                    $insertIDs[] = $insertEpID;
                                    $insertDeIDs[$epID][] = $insertEpID;
                                    $mongoListConnect['detail'][] = $insertEpID;
                                }
                            }
                        }
                        if (sizeof($insertIDs) == 0) {
                            die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
                        }
                        else {
                            $indexing_details = array();
                            foreach ($insertDeIDs as $key => $numb) {
                                $indexing_details[$key] = $numb;
                            }

                            foreach ($indexing_details as $k => $arrID) {
                                $arrBlob = blobEncode($arrID);
                                $this->db->query("UPDATE transaksi SET indexing_details = '$arrBlob' WHERE id=$k");
                                cekOrange($this->db->last_query());
                            }
                        }
                    }
                    if (isset($_SESSION[$cCode]['tableIn_detail2_sum']) && sizeof($_SESSION[$cCode]['tableIn_detail2_sum']) > 0) {
                        $insertIDs = array();
                        foreach ($_SESSION[$cCode]['tableIn_detail2_sum'] as $dSpec) {
                            $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                            $mongoListConnect['detail'] = $insertIDs;
                            if ($epID != 999) {
                                $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                                $mongoListConnect['detail'] = $mongoListConnect['detail'] = $insertIDs;;
                            }
                        }
                    }
                    if (isset($_SESSION[$cCode]['tableIn_detail_values']) && sizeof($_SESSION[$cCode]['tableIn_detail_values']) > 0) {
                        $insertIDs = array();
                        foreach ($_SESSION[$cCode]['tableIn_detail_values'] as $pID => $dSpec) {
                            if (isset($configCoreMasterModulJenis['tableIn']['detailValues'])) {
                                foreach ($configCoreMasterModulJenis['tableIn']['detailValues'] as $key => $src) {
                                    $dd = $tr->writeDetailValues($insertID, array(
                                        "produk_jenis" => $_SESSION[$cCode]['tableIn_detail'][$pID]['produk_jenis'],
                                        "produk_id" => $pID,
                                        "key" => $key,
                                        "value" => isset($dSpec[$src]) ? $dSpec[$src] : 0,
                                    ));
                                    $insertIDs[] = $dd;
                                    $mongoListConnect['detailValues'][] = $dd;
                                }
                            }
                        }
                        if (sizeof($insertIDs) > 0) {
                            $arrBlob = blobEncode($insertIDs);
                            $this->db->query("UPDATE transaksi SET indexing_detail_values = '$arrBlob' WHERE id=$insertID");
                        }
                    }
                    if (isset($_SESSION[$cCode]['tableIn_detail_values2_sum']) && sizeof($_SESSION[$cCode]['tableIn_detail_values2_sum']) > 0) {
                        foreach ($_SESSION[$cCode]['tableIn_detail_values2_sum'] as $pID => $dSpec) {
                            if (isset($configCoreMasterModulJenis['tableIn']['detailValues2_sum'])) {
                                foreach ($configCoreMasterModulJenis['tableIn']['detailValues2_sum'] as $key => $src) {
                                    $insertIDs[] = $tr->writeDetailValues($insertID, array(
                                        "produk_jenis" => $_SESSION[$cCode]['tableIn_detail2_sum'][$pID]['produk_jenis'],
                                        "produk_id" => $pID,
                                        "key" => $key,
                                        "value" => $dSpec[$src],
                                    ));
                                }
                            }
                        }
                    }

                    //
                    //region nulis paymentSource
                    $stepCode = $configUiMasterModulJenis['steps'][1]['target'];
                    $paymentSources = $this->config->item("payment_source");
                    if (array_key_exists($stepCode, $paymentSources)) {
                        $payConfigs = $paymentSources[$stepCode];
                        if (sizeof($payConfigs) > 0) {
                            foreach ($payConfigs as $paymentSrcConfig) {
                                //					$paymentSrcConfig = $paymentSources[$stepCode];
                                $valueSrc = $paymentSrcConfig['valueSrc'];
                                $externSrc = $paymentSrcConfig['externSrc'];
                                $tr->writePaymentSrc($insertID, array(
                                    "jenis" => $stepCode,
                                    "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                    "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                    "extern_id" => $_SESSION[$cCode]['main'][$externSrc['id']],
                                    "extern_nama" => $_SESSION[$cCode]['main'][$externSrc['nama']],
                                    "nomer" => $tmpNomorNota2,
                                    "label" => $paymentSrcConfig['label'],
                                    "tagihan" => $_SESSION[$cCode]['main'][$valueSrc],
                                    "terbayar" => 0,
                                    "sisa" => $_SESSION[$cCode]['main'][$valueSrc],
                                    "cabang_id" => $_SESSION[$cCode]['main']['placeID'],
                                    "cabang_nama" => $_SESSION[$cCode]['main']['placeName'],
                                    "oleh_id" => $olehID,
                                    "oleh_nama" => $olehNama,
                                    "dtime" => $dtime,
                                    "fulldate" => $fulldate,
                                    "valas_id" => isset($_SESSION[$cCode]['main'][$externSrc['valasId']]) ? $_SESSION[$cCode]['main'][$externSrc['valasId']] : '',
                                    "valas_nama" => isset($_SESSION[$cCode]['main'][$externSrc['valasLabel']]) ? $_SESSION[$cCode]['main'][$externSrc['valasLabel']] : '',
                                    "valas_nilai" => isset($_SESSION[$cCode]['main'][$externSrc['valasValue']]) ? $_SESSION[$cCode]['main'][$externSrc['valasValue']] : '',
                                    "tagihan_valas" => isset($_SESSION[$cCode]['main'][$externSrc['valasTagihan']]) ? $_SESSION[$cCode]['main'][$externSrc['valasTagihan']] : '',
                                    "terbayar_valas" => 0,
                                    "sisa_valas" => isset($_SESSION[$cCode]['main'][$externSrc['valasSisa']]) ? $_SESSION[$cCode]['main'][$externSrc['valasSisa']] : '',
                                ));
                            }
                        }
                    }
                    else {
                        //cekMerah("TIDAK nulis paymentSrc");
                    }
                    //endregion


                    //region nulis paymentAntiSource
                    $stepCode = $configUiMasterModulJenis['steps'][1]['target'];
                    $paymentSources = $this->config->item("payment_antiSource");
                    if (array_key_exists($stepCode, $paymentSources)) {
                        $payConfigs = $paymentSources[$stepCode];
                        if (sizeof($payConfigs) > 0) {
                            foreach ($payConfigs as $paymentSrcConfig) {
                                //					$paymentSrcConfig = $paymentSources[$stepCode];
                                $valueSrc = $paymentSrcConfig['valueSrc'];
                                $externSrc = $paymentSrcConfig['externSrc'];
                                $tr->writePaymentAntiSrc($insertID, array(
                                    "jenis" => $stepCode,
                                    "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                    "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                    "extern_id" => $_SESSION[$cCode]['main'][$externSrc['id']],
                                    "extern_nama" => $_SESSION[$cCode]['main'][$externSrc['nama']],
                                    "nomer" => $tmpNomorNota2,
                                    "label" => $paymentSrcConfig['label'],
                                    "tagihan" => $_SESSION[$cCode]['main'][$valueSrc],
                                    "terbayar" => 0,
                                    "sisa" => $_SESSION[$cCode]['main'][$valueSrc],
                                    "cabang_id" => $_SESSION[$cCode]['main']['placeID'],
                                    "cabang_nama" => $_SESSION[$cCode]['main']['placeName'],
                                    "oleh_id" => $olehID,
                                    "oleh_nama" => $olehNama,
                                    "dtime" => $dtime,
                                    "fulldate" => $fulldate,
                                ));
                            }
                        }


                        //cekMerah($this->db->last_query());

                    }
                    else {
                        //cekMerah("TIDAK nulis paymentSrc");
                    }
                    //endregion


                    $idHis_decode[$stepNum] = array(
                        "dtime" => $dtime,
                        "fulldate" => $fulldate,
                        "olehID" => $_SESSION[$cCode]['main']['olehID'],
                        "olehName" => $_SESSION[$cCode]['main']['olehName'],
                        "step" => $stepNum,
                        "trID" => $insertID,
                        "nomer" => $tmpNomorNota2,
                        "nomer2" => $tmpNomorNota2Alias,
                        "counters" => $appliedCounters2,
                        "counters_intext" => $appliedCounters_inText2,
                    );
                    $idHis_blob = blobEncode($idHis_decode);
                    $idHis_intext = print_r($idHis_decode, true);

                    $_SESSION[$cCode]['tableIn_master']['ids_his'] = $idHis_blob;
                    $_SESSION[$cCode]['tableIn_master']['ids_his_intext'] = $idHis_intext;

                    $tr = new MdlTransaksi();
                    $dupState = $tr->updateData(array("id" => $insertID), array(
                        "id_master" => $masterID,
                        "id_top" => $insertID,
                        "ids_his" => $idHis_blob,
                        "ids_his_intext" => $idHis_intext,

                    )) or die("Failed to update tr next-state!");

                    $baseRegistries = array(
                        'main' => isset($_SESSION[$cCode]['main']) ? $_SESSION[$cCode]['main'] : array(),
                        'items' => isset($_SESSION[$cCode]['items']) ? $_SESSION[$cCode]['items'] : array(),
                        'items2' => isset($_SESSION[$cCode]['items2']) ? $_SESSION[$cCode]['items2'] : array(),
                        'items2_sum' => isset($_SESSION[$cCode]['items2_sum']) ? $_SESSION[$cCode]['items2_sum'] : array(),
                        'itemSrc' => isset($_SESSION[$cCode]['itemSrc']) ? $_SESSION[$cCode]['itemSrc'] : array(),
                        'itemSrc_sum' => isset($_SESSION[$cCode]['itemSrc_sum']) ? $_SESSION[$cCode]['itemSrc_sum'] : array(),
                        'items3' => isset($_SESSION[$cCode]['items3']) ? $_SESSION[$cCode]['items3'] : array(),
                        'items3_sum' => isset($_SESSION[$cCode]['items3_sum']) ? $_SESSION[$cCode]['items3_sum'] : array(),
                        'items4' => isset($_SESSION[$cCode]['items4']) ? $_SESSION[$cCode]['items4'] : array(),
                        'items4_sum' => isset($_SESSION[$cCode]['items4_sum']) ? $_SESSION[$cCode]['items4_sum'] : array(),
                        'items5_sum' => isset($_SESSION[$cCode]['items5_sum']) ? $_SESSION[$cCode]['items5_sum'] : array(),
                        'items6_sum' => isset($_SESSION[$cCode]['items6_sum']) ? $_SESSION[$cCode]['items6_sum'] : array(),
                        'items7_sum' => isset($_SESSION[$cCode]['items7_sum']) ? $_SESSION[$cCode]['items7_sum'] : array(),
                        'items8_sum' => isset($_SESSION[$cCode]['items8_sum']) ? $_SESSION[$cCode]['items8_sum'] : array(),
                        'items9_sum' => isset($_SESSION[$cCode]['items9_sum']) ? $_SESSION[$cCode]['items9_sum'] : array(),
                        'items10_sum' => isset($_SESSION[$cCode]['items10_sum']) ? $_SESSION[$cCode]['items10_sum'] : array(),
                        'items_noapprove' => isset($_SESSION[$cCode]['items_noapprove']) ? $_SESSION[$cCode]['items_noapprove'] : array(),

                        'rsltItems' => isset($_SESSION[$cCode]['rsltItems']) ? $_SESSION[$cCode]['rsltItems'] : array(),
                        'rsltItems2' => isset($_SESSION[$cCode]['rsltItems2']) ? $_SESSION[$cCode]['rsltItems2'] : array(),
                        'rsltItems3' => isset($_SESSION[$cCode]['rsltItems3']) ? $_SESSION[$cCode]['rsltItems3'] : array(),

                        'tableIn_master' => isset($_SESSION[$cCode]['tableIn_master']) ? $_SESSION[$cCode]['tableIn_master'] : array(),
                        'tableIn_detail' => isset($_SESSION[$cCode]['tableIn_detail']) ? $_SESSION[$cCode]['tableIn_detail'] : array(),
                        'tableIn_detail2_sum' => isset($_SESSION[$cCode]['tableIn_detail2_sum']) ? $_SESSION[$cCode]['tableIn_detail2_sum'] : array(),
                        'tableIn_detail_rsltItems' => isset($_SESSION[$cCode]['tableIn_detail_rsltItems']) ? $_SESSION[$cCode]['tableIn_detail_rsltItems'] : array(),
                        'tableIn_detail_rsltItems2' => isset($_SESSION[$cCode]['tableIn_detail_rsltItems2']) ? $_SESSION[$cCode]['tableIn_detail_rsltItems2'] : array(),
                        'tableIn_master_values' => isset($_SESSION[$cCode]['tableIn_master_values']) ? $_SESSION[$cCode]['tableIn_master_values'] : array(),
                        'tableIn_detail_values' => isset($_SESSION[$cCode]['tableIn_detail_values']) ? $_SESSION[$cCode]['tableIn_detail_values'] : array(),
                        'tableIn_detail_values_rsltItems' => isset($_SESSION[$cCode]['tableIn_detail_values_rsltItems']) ? $_SESSION[$cCode]['tableIn_detail_values_rsltItems'] : array(),
                        'tableIn_detail_values_rsltItems2' => isset($_SESSION[$cCode]['tableIn_detail_values_rsltItems2']) ? $_SESSION[$cCode]['tableIn_detail_values_rsltItems2'] : array(),
                        'tableIn_detail_values2_sum' => isset($_SESSION[$cCode]['tableIn_detail_values2_sum']) ? $_SESSION[$cCode]['tableIn_detail_values2_sum'] : array(),
                        'main_add_values' => isset($_SESSION[$cCode]['main_add_values']) ? $_SESSION[$cCode]['main_add_values'] : array(),
                        'main_add_fields' => isset($_SESSION[$cCode]['main_add_fields']) ? $_SESSION[$cCode]['main_add_fields'] : array(),
                        'main_elements' => isset($_SESSION[$cCode]['main_elements']) ? $_SESSION[$cCode]['main_elements'] : array(),
                        'main_inputs' => isset($_SESSION[$cCode]['main_inputs']) ? $_SESSION[$cCode]['main_inputs'] : array(),
                        'main_inputs_orig' => isset($_SESSION[$cCode]['main_inputs']) ? $_SESSION[$cCode]['main_inputs'] : array(),
                        "receiptDetailFields" => isset($configLayoutMasterModulJenis['receiptDetailFields'][1]) ? $configLayoutMasterModulJenis['receiptDetailFields'][1] : array(),
                        "receiptSumFields" => isset($configLayoutMasterModulJenis['receiptSumFields'][1]) ? $configLayoutMasterModulJenis['receiptSumFields'][1] : array(),
                        "receiptDetailFields2" => isset($configLayoutMasterModulJenis['receiptDetailFields2'][1]) ? $configLayoutMasterModulJenis['receiptDetailFields2'][1] : array(),
                        "receiptSumFields2" => isset($configLayoutMasterModulJenis['receiptSumFields2'][1]) ? $configLayoutMasterModulJenis['receiptSumFields2'][1] : array(),
                        "receiptDetailSrcFields" => isset($configLayoutMasterModulJenis['receiptDetailSrcFields'][1]) ? $configLayoutMasterModulJenis['receiptDetailSrcFields'][1] : array(),
                        "items_komposisi" => isset($_SESSION[$cCode]['items_komposisi']) ? $_SESSION[$cCode]['items_komposisi'] : array(),
                        "componentsBuilder" => isset($_SESSION[$cCode]['componentsBuilder']) ? $_SESSION[$cCode]['componentsBuilder'] : array(),
                        "jurnalItems" => isset($_SESSION[$cCode]['jurnalItems']) ? $_SESSION[$cCode]['jurnalItems'] : array(),
                        "jurnal_index" => isset($_SESSION[$cCode]['jurnal_index']) ? $_SESSION[$cCode]['jurnal_index'] : array(),
                        "postProcessor" => isset($_SESSION[$cCode]['postProcessor']) ? $_SESSION[$cCode]['postProcessor'] : array(),
                        "preProcessor" => isset($_SESSION[$cCode]['preProcessor']) ? $_SESSION[$cCode]['preProcessor'] : array(),
                        "revert" => isset($_SESSION[$cCode]['revert']) ? $_SESSION[$cCode]['revert'] : array(),

                    );
                    $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or mati_disini("Ada kesalahan", "Gagal saat berusaha  write base params into registries");
                    showLast_query("kuning");
                    $mongRegIDConnect = $doWriteReg;
                    //endregion

                }
                else {
                    cekMerah("to be delayed to connect to $connector");
                }
            }
            else {
                cekKuning("TIDAK ADA KONEKTOR DARI KODE $jenisTrMaster");
            }
            //endregion

            cekHijau("BAGIAN KONEK 110 KE DC/PUSAT SELESAI");
//            mati_disini(__LINE__);


            $stopDate = dtimeNow();
//            mati_disini(__LINE__ . " || STOP CEK DULU...");

            cekHitam("--- MULAI VALIDATOR ---");
            $this->load->library("Validator");
            $vdt = New Validator();

            // validasi lajur DC/PUSAT
            validateAllBalances("-1");

            // validasi lajur CABANG
            validateAllBalances("1");


            cekHijau("...tes cli transaksi... rekening pembantu masuk disini (component detail)<br>start: $startDate<br>stop: $stopDate<br>butuh waktu: " . timeDiff($startDate, $stopDate));
            mati_disini("...tes cli transaksi... rekening pembantu masuk disini (component detail)<br>start: $startDate<br>stop: $stopDate<br>butuh waktu: " . timeDiff($startDate, $stopDate));


            $this->db->trans_complete() or mati_disini("Gagal saat berusaha  commit transaction!");

            cekHijau("<h3> [$getTrID] SELESAI... </h3>");
        }
        else {
            $stopDate = dtimeNow();
            cekMerah(":: TIDAK ADA yang perlu di-CLI-kan ::
                    <br>start: $startDate<br>stop: $stopDate<br>butuh waktu: " . timeDiff($startDate, $stopDate));
        }

    }


    public function cek_cek()
    {
        $tbl_1 = "neraca";
        $tbl_2 = "_rek_master_cache";
        $periode = "bulanan";
        $thn = "2026";
        $bln = "04";
        $bln_cache = "05";
        $cabang_id = "34";


        $this->db->trans_start();


        // region cache---------------------
        $arrFilter = array(
            "periode" => $periode,
            "thn" => $thn,
            "bln" => $bln_cache,
            "cabang_id" => $cabang_id,
//            "status" => 1,
//            "trash" => 0,
        );
        $this->db->where($arrFilter);
        $query1 = $this->db->get($tbl_2)->result();
        foreach ($query1 as $spec) {
            $rekeningCache[$spec->rekening] = $spec;
            $rekenings[$spec->rekening] = $spec->rekening;
        }
        showLast_query("kuning");
        //endregion cache---------------------

        // region neraca
        $arrFilter = array(
            "periode" => $periode,
            "thn" => $thn,
            "bln" => $bln,
            "cabang_id" => $cabang_id,
            "status" => 1,
            "trash" => 0,
        );
        $this->db->where($arrFilter);
        $query0 = $this->db->get($tbl_1)->result();
        showLast_query("biru");
        foreach ($query0 as $spec) {
            $rekenings[$spec->rekening] = $spec->rekening;
            if (!array_key_exists($spec->rekening, $rekeningCache)) {
                $debet = $spec->debet;
                $kredit = $spec->kredit;
                $data = array(
                    'debet' => $spec->debet,
                    'kredit' => $spec->kredit,
                    'periode' => $periode,
                    'rekening' => $spec->rekening,
                    'cabang_id' => $spec->cabang_id,
                    'bln' => $bln_cache,
                    'thn' => $thn,
                );
                $this->db->insert($tbl_2, $data);
                showLast_query("hijau");
            }
        }
        // endregion neraca

        //region cache---------------------
        $debet_total = 0;
        $kredit_total = 0;
        $arrFilter = array(
            "periode" => $periode,
            "thn" => $thn,
            "bln" => $bln_cache,
            "cabang_id" => $cabang_id,
//            "status" => 1,
//            "trash" => 0,
        );
        $this->db->where($arrFilter);
        $query1 = $this->db->get($tbl_2)->result();
        foreach ($query1 as $spec) {
            $debet = $spec->debet;
            $kredit = $spec->kredit;

            $debet_total += $spec->debet;
            $kredit_total += $spec->kredit;
        }
        $selisih = $debet_total - $kredit_total;
        cekHitam("debet: $debet_total");
        cekHitam("kredit: $kredit_total");
        cekHitam("selisih: $selisih");
        showLast_query("kuning");
        //endregion cache


        mati_disini(__LINE__);
        $this->db->trans_complete() or mati_disini("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3> [$getTrID] SELESAI... </h3>");
    }

    public function cekUMProject()
    {
        $this->load->model("MdlTransaksi");
        $jenis = "4467";
        $trIDs = array();


        $tr = New MdlTransaksi();
        $tr->addFilter("jenis='$jenis'");
        $trTmp = $tr->lookupAll()->result();
        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $iSpec) {
                $trIDs[$iSpec->id] = $iSpec->id;
            }

            $tr = New MdlTransaksi();
            $tr->setFilters(array());
            $tr->setJointSelectFields("transaksi_id, main");
            $tr->addFilter("transaksi_id in ('" . implode("','", $trIDs) . "')");
            $trReg = $tr->lookupDataRegistries()->result();
            showLast_query("biru");
//            arrPrint($trReg);
            foreach ($trReg as $regSpec) {
                $trid = $regSpec->transaksi_id;
                $main = blobDecode($regSpec->main);
                if (isset($main["referensi_so_project"])) {
                    $project_id = $main["referensi_so_project"];
                    $project_nama = $main["referensi_so_project__nama"];
                }
                elseif (isset($main["referensi_so__project_id"])) {
                    $project_id = $main["referensi_so__project_id"];
                    $project_nama = $main["referensi_so__project_nama"];
                }
                else {
                    $project_id = 0;
                    $project_nama = "";
                }
                $arrDataUpdate[$trid] = array(
                    "ppn_nilai" => $main["ppn"],
                    "transaksi_net" => $main["dpp_ppn"],
                    "project_id" => $project_id,
                    "project_nama" => $project_nama,
                    "reference_jenis" => $main["referensi_um__ref_jenis"],
                    "reference_id" => $main["referensi_so__id"],
                    "reference_nomer" => $main["referensi_so__nomer"],
                );

            }
//            arrPrintWebs($arrDataUpdate);


        }


        $this->db->trans_start();


        if (sizeof($arrDataUpdate) > 0) {
            foreach ($arrDataUpdate as $trid => $data) {
                $tr = New MdlTransaksi();
                $tr->setFilters(array());
                $where = array(
                    "id" => $trid,
                );
                $tr->updateData($where, $data);
                showLast_query("orange");
            }
        }


//        mati_disini("...tes cli transaksi... rekening pembantu masuk disini (component detail)");


        $this->db->trans_complete() or mati_disini("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3> [$getTrID] SELESAI... </h3>");
    }

    public function genRekeningUMProject()
    {
        $this->load->model("Coms/ComRekeningPembantuPpnProject");
        $this->load->model("Coms/ComRekeningPembantuCustomerProject");
        $this->load->model("MdlTransaksi");
        $rek = "2010050";
        $rek_sub = "2010050060";

        $pymSrc = New MdlTransaksi();
        $pymSrc->setFilters(array());
        $pymSrc->addFilter("sisa>10");
        $pymSrc->addFilter("label='uang muka konsumen'");
        $pymSrc->addFilter("jenis='4467'");
        $pymSrc->addFilter("project_id>'0'");
        $pymSrcTmp = $pymSrc->lookUpAllPaymentSrc()->result();
        showLast_query("biru");
        cekBiru(count($pymSrcTmp));

        $this->db->trans_start();

        $pakai_ini = 0;
        if ($pakai_ini == 1) {
            if (sizeof($pymSrcTmp) > 0) {
                foreach ($pymSrcTmp as $ii => $spec) {
                    // cek dulu, jika belum ada maka buatkan
                    $crpp = New ComRekeningPembantuCustomerProject();
                    $crpp->addFilter("extern2_id=" . $spec->project_id);
                    $crpp->addFilter("extern_id=" . $spec->extern_id);
                    $crpp->addFilter("cabang_id=" . $spec->cabang_id);
                    $tmp = $crpp->fetchBalances($rek);
                    showLast_query("kuning");
                    if (sizeof($tmp) == 0) {
                        $data[$ii] = array(
                            "loop" => array(
                                "2010050" => $spec->sisa,// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => $spec->cabang_id,
                                "extern_id" => $spec->extern_id,
                                "extern_nama" => $spec->extern_nama,
                                "extern2_id" => $spec->project_id,// projectid
                                "extern2_nama" => $spec->project_nama,// projectnama
                                "jenis" => $spec->jenis,
                                "transaksi_no" => $spec->nomer,
                                "transaksi_id" => $spec->transaksi_id,
                                "dtime" => $spec->dtime,
                                "fulldate" => $spec->fulldate,
                                "oleh_id" => $spec->oleh_id,
                                "oleh_nama" => $spec->oleh_nama,
                            ),
                        );
                    }
                }
                cekBiru(count($data));
                if (sizeof($data) > 0) {
                    foreach ($data as $dataspec) {
                        $crpp = New ComRekeningPembantuCustomerProject();
                        $crpp->pair($dataspec);
                        $crpp->exec();

                    }
                }
            }
        }

        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            if (sizeof($pymSrcTmp) > 0) {
                foreach ($pymSrcTmp as $ii => $spec) {
                    // cek dulu, jika belum ada maka buatkan
                    $crpp = New ComRekeningPembantuPpnProject();
                    $crpp->addFilter("extern2_id=" . $spec->extern_id);// id konsumen
                    $crpp->addFilter("extern_id=" . $spec->project_id);// id project
                    $crpp->addFilter("cabang_id=" . $spec->cabang_id);// id cabang
                    $tmp = $crpp->fetchBalances("2030060");
                    showLast_query("kuning");
                    if (sizeof($tmp) == 0) {
                        $data[$ii] = array(
                            "loop" => array(
                                "2030060" => $spec->ppn_sisa,// ppn keluaran belum ada faktur
                            ),
                            "static" => array(
                                "cabang_id" => $spec->cabang_id,
                                "extern_id" => $spec->project_id,
                                "extern_nama" => $spec->project_nama,
                                "extern2_id" => $spec->extern_id,
                                "extern2_nama" => $spec->extern_nama,
                                "jenis" => $spec->jenis,
                                "transaksi_no" => $spec->nomer,
                                "transaksi_id" => $spec->transaksi_id,
                                "dtime" => $spec->dtime,
                                "fulldate" => $spec->fulldate,
                                "oleh_id" => $spec->oleh_id,
                                "oleh_nama" => $spec->oleh_nama,
                            ),
                        );
                    }
                    else {
                        cekHijau("SUDAH ADA: " . $spec->extern_id . " == " . $spec->project_id . " == " . $spec->ppn_sisa);
                    }
                }
                cekBiru(count($data));
//                arrPrintCyan($data);
                if (sizeof($data) > 0) {
                    foreach ($data as $dataspec) {
                        $crpp = New ComRekeningPembantuPpnProject();
                        $crpp->pair($dataspec);
                        $crpp->exec();
                    }
                }
            }
        }

//        mati_disini("...tes cli transaksi... rekening pembantu masuk disini (component detail)");

        $this->db->trans_complete() or mati_disini("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3> [$getTrID] SELESAI... </h3>");
    }

}


