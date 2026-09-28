<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 03/04/2019
 * Time: 13.50
 */

class CliTransaksi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
//        $this->load->library("SmtpMailer");

        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlCliLogTime");
        $this->load->model("Coms/ComJurnal");
        $this->load->model("Coms/ComRekeningPembantuRaw");
        $this->load->model("Coms/ComRekeningPembantuRawMain");

    }

    public function run_cliTransaksi_()
    {
//        header("refresh:2");
//        mati_disini();
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
//        $tr->addFilter("cli='0'");
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
//arrPrintWebs($registryGates["items4_sum"]);
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

            cekHitam(":: jenisTrMaster-> $jenisTrMaster :: jenisTr-> $jenisTr ::");
//            cekPink2("config CORE");
//            arrPrint($configCore);
//            cekPink2("=============");
//arrPrintWebs($registryGates);
            //region BUILD TABEL DATABASE OTOMATIS
            $cliComponent = "components";
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

//mati_disini(__LINE__);

            $this->db->trans_start();


            //region ----------subcomponents by cli
            //<editor-fold desc="----------subcomponents by cli">
            $paramPatchers = $this->config->item('heTransaksi_paramPatchers') != null ? $this->config->item('heTransaksi_paramPatchers') : array();
            $paramForceFillers = $this->config->item('heTransaksi_paramForceFillers') != null ? $this->config->item('heTransaksi_paramForceFillers') : array();
            $validateSubComponent = $this->config->item('heTransaksi_validateComponentDetail') != null ? $this->config->item('heTransaksi_validateComponentDetail') : array();

            $componentGate['detail'] = array();
            $componentConfig['master'] = array();
            $componentConfig['detail'] = array();
            if (isset($configCore['relativeComponets']) && $configCore['relativeComponets'] == true) {
                $iterator = isset($registryGates['revert']['jurnal']['detail']) ? $registryGates['revert']['jurnal']['detail'] : array();
                $revertedTarget = $registryGates['main']['pihakExternID'];
                $componentConfig['detail'] = $iterator;
                $componentConfig['master'] = isset($registryGates['revert']['jurnal']['master']) ? $registryGates['revert']['jurnal']['master'] : array();
            }
            else {
                $iterator = isset($configCore[$cliComponent][$jenisTr]['detail']) ? $configCore[$cliComponent][$jenisTr]['detail'] : array();
                $componentConfig['detail'] = $iterator;
                $componentConfig['master'] = isset($configCore[$cliComponent][$jenisTr]['master']) ? $configCore[$cliComponent][$jenisTr]['master'] : array();

                $revertedTarget = "";

            }

// arrPrint($registryGates["items"]);
// arrPrintPink($registryGates["items2_sum"]);
//             matiHEre();
            $subComModel = array();
            if (sizeof($iterator) > 0) {
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

                        $componentGate['detail'][$cCtr] = $subParams;
                    }

                }
//arrPrintKuning($tmpOutParams);
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
                    cekHere("::::: $comName :::::");


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
                cekMerah("subcomponents is not set");
            }


            //</editor-fold>
            //endregion

            //---VALIDASI QTY/JML, OUT/IN
            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                if (sizeof($validateSubComponent) > 0) {
//                cekKuning($validateSubComponent);
//                cekPink($subComModel);
                    if (sizeof($subComModel) > 0) {

                        if (isset($validateSubComponent['enabled']) && ($validateSubComponent['enabled'] == true)) {

                            $arrRekeningItems = array();
                            if (!in_array($jenisTrMaster, $validateSubComponent['jenisTrException'])) {

                                $qtyValidate = false;
                                foreach ($subComModel as $rek => $subCom) {
                                    if (in_array($subCom, $validateSubComponent['subComponent']['detail'])) {
                                        $subComs = "Com" . $subCom;
                                        $this->load->model("Coms/" . $subComs);
                                        $md = New $subComs();
                                        $tbl = $md->getTableNameMaster()['mutasi'];
                                        $tblName = "_" . $tbl . "__" . str_replace(" ", "_", $rek);
                                        $md->setTableName($tblName);
                                        $md->addFilter("transaksi_id='$transaksiID'");
                                        $mdTmp = $md->lookupAll()->result();
                                        showLast_query("biru");
//                                cekHijau($mdTmp);
                                        if (sizeof($mdTmp) > 0) {
                                            foreach ($mdTmp as $mdSpec) {
                                                $arrRekeningItems[$mdSpec->extern_id]['nama'] = $mdSpec->extern_nama;

                                                if (!isset($arrRekeningItems[$mdSpec->extern_id]['jml_debet'])) {
                                                    $arrRekeningItems[$mdSpec->extern_id]['jml_debet'] = 0;
                                                }
                                                $arrRekeningItems[$mdSpec->extern_id]['jml_debet'] += $mdSpec->qty_debet;

                                                if (!isset($arrRekeningItems[$mdSpec->extern_id]['jml_kredit'])) {
                                                    $arrRekeningItems[$mdSpec->extern_id]['jml_kredit'] = 0;
                                                }
                                                $arrRekeningItems[$mdSpec->extern_id]['jml_kredit'] += $mdSpec->qty_kredit;
                                            }
                                        }

                                        $qtyValidate = true;
                                    }
                                }


                                $arrRequestItems = array();
                                if (isset($registryGates['items']) && (sizeof($registryGates['items']) > 0)) {
                                    foreach ($registryGates['items'] as $pID => $iSpec) {
                                        $arrRequestItems[$pID]['nama'] = $iSpec['nama'];
                                        $arrRequestItems[$pID]['jml'] = $iSpec['jml'];

                                    }
                                }

                                if (count($arrRequestItems) != count($arrRekeningItems)) {
                                    // STOP
                                    $msg = "Jumlah item request " . sizeof($arrRequestItems) . " tidak sama dengan jumlah masuk rekening " . sizeof($arrRekeningItems) . " line " . __LINE__;
                                    mati_disini($msg);
                                }
                                else {
                                    cekHijau("request " . count($arrRequestItems) . ", rekening " . count($arrRekeningItems));
                                    foreach ($arrRequestItems as $pID => $spec) {
                                        $req_nama = $spec['nama'];
                                        $req_jml = $spec['jml'];
                                        $rek_jml = (isset($arrRekeningItems[$pID]['jml_debet']) && ($arrRekeningItems[$pID]['jml_debet'] > 0)) ? $arrRekeningItems[$pID]['jml_debet'] : $arrRekeningItems[$pID]['jml_kredit'];
                                        $rek_jml_debet = (isset($arrRekeningItems[$pID]['jml_debet']) && ($arrRekeningItems[$pID]['jml_debet'] > 0)) ? $arrRekeningItems[$pID]['jml_debet'] : 0;
                                        $rek_jml_kredit = (isset($arrRekeningItems[$pID]['jml_kredit']) && ($arrRekeningItems[$pID]['jml_kredit'] > 0)) ? $arrRekeningItems[$pID]['jml_kredit'] : 0;

                                        if (in_array($jenisTrMaster, $validateSubComponent['dobleValidate'])) {
                                            cekBiru("cek request vs rekDebet dan request vs reqKredit");
                                            // request vs rek qty debet
                                            if ($req_jml != $rek_jml_debet) {
                                                // STOP
                                                $msg = "$req_nama, jumlah request $req_jml tidak sama dengan jumlah masuk rekening $rek_jml_debet";
                                                mati_disini($msg);
                                            }
                                            else {
                                                // LANJUT
                                                cekHijau("$req_nama, request $req_jml, rekening qtyDebet $rek_jml_debet");
                                            }
                                            // request vs rek qty kredit
                                            if ($req_jml != $rek_jml_kredit) {
                                                // STOP
                                                $msg = "$req_nama, jumlah request $req_jml tidak sama dengan jumlah masuk rekening $rek_jml_kredit";
                                                mati_disini($msg);
                                            }
                                            else {
                                                // LANJUT
                                                cekHijau("$req_nama, request $req_jml, rekening qtyKredit $rek_jml_kredit");
                                            }
                                        }
                                        else {
                                            cekBiru("cek request vs rekJml");
                                            if ($req_jml != $rek_jml) {
                                                if ($qtyValidate == true) {
                                                    // STOP
                                                    $msg = "$req_nama, jumlah request $req_jml tidak sama dengan jumlah masuk rekening $rek_jml";
                                                    mati_disini($msg);
                                                }
                                            }
                                            else {
                                                // LANJUT
                                                cekHijau("$req_nama, request $req_jml, rekening $rek_jml");
                                            }

                                        }

                                    }
                                }


                            }
                            else {
                                cekPink2(":: $jenisTrMaster masuk exception ::");
                            }
                        }
                    }
                }

            }

            //region update status sudah dirunning by cli
            $tr = New MdlTransaksi();
            $tr->setFilters(array());
            $where = array(
                "id" => $transaksiID,
            );
            $updateData = array(
                "cli" => 1,
            );
            $tr->updateData($where, $updateData);
            cekHere($this->db->last_query());
            //endregion

            $stopDate = dtimeNow();

            // region menulis ke tabel log time cli
            $cl = New MdlCliLogTime();
            $arrCliData = array(
                "web" => "cli",
                "judul" => "CLI $insertNum $addJudul",
                "waktu_start" => $startDate,
                "waktu_stop" => $stopDate,
                "waktu" => timeDiff($startDate, $stopDate),
                "transaksi_id" => $insertID,
                "nomer" => $insertNum,
                "jenis" => $jenisTr,
                "jenis_master" => $jenisTrMaster,
            );
            $rslt = $cl->addData($arrCliData);
            cekHere($this->db->last_query());
            // endregion


            cekHitam("--- MULAI VALIDATOR ---");
            $this->load->library("Validator");
            $vdt = New Validator();
            $vdt->validateMasterDetail($trID_cli, $componentConfig['master'], $componentConfig['detail']);


            if ($getTrID > 0) {
                mati_disini("...cek MANUAL cli transaksi... rekening pembantu masuk disini (component detail)<br>start: $startDate<br>stop: $stopDate<br>butuh waktu: " . timeDiff($startDate, $stopDate));
            }


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

    public function run_cliTransaksi()
    {
        if (isset($_GET["stop"]) && ($_GET["stop"] == 1)) {
            cekMerah("TIDAK AUTO REFRESH");
        }
        else {
//            header("refresh:2");
        }

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

        if (sizeof($trTmp) > 0) {
            $trID_cli = $trTmp[0]->id;
            $trTmpCabangID = $trTmp[0]->cabang_id;
            echo $trID_cli;
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
            arrPrintWebs($registryGates["items8_sum"]);
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
//            cekPink2("config CORE");
//            arrPrint($configCore);
//            cekPink2("=============");
//arrPrintWebs($registryGates);
            //region BUILD TABEL DATABASE OTOMATIS
            $cliComponent = "components";
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

//mati_disini(__LINE__);

            $this->db->trans_start();


            //region ----------subcomponents by cli
            //<editor-fold desc="----------subcomponents by cli">
            $paramPatchers = $this->config->item('heTransaksi_paramPatchers') != null ? $this->config->item('heTransaksi_paramPatchers') : array();
            $paramForceFillers = $this->config->item('heTransaksi_paramForceFillers') != null ? $this->config->item('heTransaksi_paramForceFillers') : array();
            $validateSubComponent = $this->config->item('heTransaksi_validateComponentDetail') != null ? $this->config->item('heTransaksi_validateComponentDetail') : array();
            $paramForceFillersJenisTR = $this->config->item('heTransaksi_paramForceFillers_jenisTR') != null ? $this->config->item('heTransaksi_paramForceFillers_jenisTR') : array();

            $componentGate['detail'] = array();
            $componentConfig['master'] = array();
            $componentConfig['detail'] = array();
            if (isset($configCore['relativeComponets']) && $configCore['relativeComponets'] == true) {
                $iterator = isset($registryGates['revert']['jurnal']['detail']) ? $registryGates['revert']['jurnal']['detail'] : array();
                $revertedTarget = $registryGates['main']['pihakExternID'];
                $componentConfig['detail'] = $iterator;
                $componentConfig['master'] = isset($registryGates['revert']['jurnal']['master']) ? $registryGates['revert']['jurnal']['master'] : array();
            }
            else {
                $iterator = isset($configCore[$cliComponent][$jenisTr]['detail']) ? $configCore[$cliComponent][$jenisTr]['detail'] : array();
                $componentConfig['detail'] = $iterator;
                $componentConfig['master'] = isset($configCore[$cliComponent][$jenisTr]['master']) ? $configCore[$cliComponent][$jenisTr]['master'] : array();

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
            //endregion

            //region subcomponent subdetail multi dimensional array/2 level array
            /*
             * contoh items6,items2 dll
             */
            $componentGate['sub_detail'] = array();
            $componentConfig['sub_detail'] = array();
            if (isset($configCore['relativeComponets']) && $configCore['relativeComponets'] == true) {
                $iterator = isset($registryGates['revert']['jurnal']['sub_detail']) ? $registryGates['revert']['jurnal']['sub_detail'] : array();
                $revertedTarget = $registryGates['main']['pihakExternID'];
                $componentConfig['sub_detail'] = $iterator;
                $componentConfig['master'] = isset($registryGates['revert']['jurnal']['master']) ? $registryGates['revert']['jurnal']['master'] : array();
//                cekHItam("masuk atas");
            }
            else {
                $iterator = isset($configCore[$cliComponent][$jenisTr]['sub_detail']) ? $configCore[$cliComponent][$jenisTr]['sub_detail'] : array();
                $componentConfig['sub_detail'] = $iterator;
                $componentConfig['master'] = isset($configCore[$cliComponent][$jenisTr]['master']) ? $configCore[$cliComponent][$jenisTr]['master'] : array();
                $revertedTarget = "";
//                cekHItam("masuk bawah");
            }
//            arrPrintKuning($iterator);
//            cekHitam("[$jenisTr]");
//            arrPrintKuning($configCore[$cliComponent][$jenisTr]);
//            mati_disini(__LINE__);

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
                foreach ($iterator as $cCtr => $tComSpec) {
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
//                            $tComSpec['comName'] = $comName;
//                            $iterator[$cCtr]['comName'] = $comName;
//
//
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
//                        arrprintWebs($tmpOutParams);
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
                validateBalancesComparison($trTmpCabangID, $componentGate, $componentConfig, "detail", $transaksiID, $tmpNomorNota);

            }
            else {
                cekMerah("subcomponents [sub_detail] is not set");
            }
            //endregion

            //---VALIDASI QTY/JML, OUT/IN
            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                if (sizeof($validateSubComponent) > 0) {
//                cekKuning($validateSubComponent);
//                cekPink($subComModel);
                    if (sizeof($subComModel) > 0) {

                        if (isset($validateSubComponent['enabled']) && ($validateSubComponent['enabled'] == true)) {

                            $arrRekeningItems = array();
                            if (!in_array($jenisTrMaster, $validateSubComponent['jenisTrException'])) {

                                $qtyValidate = false;
                                foreach ($subComModel as $rek => $subCom) {
                                    if (in_array($subCom, $validateSubComponent['subComponent']['detail'])) {
                                        $subComs = "Com" . $subCom;
                                        $this->load->model("Coms/" . $subComs);
                                        $md = New $subComs();
                                        $tbl = $md->getTableNameMaster()['mutasi'];
                                        $tblName = "_" . $tbl . "__" . str_replace(" ", "_", $rek);
                                        $md->setTableName($tblName);
                                        $md->addFilter("transaksi_id='$transaksiID'");
                                        $mdTmp = $md->lookupAll()->result();
                                        showLast_query("biru");
//                                cekHijau($mdTmp);
                                        if (sizeof($mdTmp) > 0) {
                                            foreach ($mdTmp as $mdSpec) {
                                                $arrRekeningItems[$mdSpec->extern_id]['nama'] = $mdSpec->extern_nama;

                                                if (!isset($arrRekeningItems[$mdSpec->extern_id]['jml_debet'])) {
                                                    $arrRekeningItems[$mdSpec->extern_id]['jml_debet'] = 0;
                                                }
                                                $arrRekeningItems[$mdSpec->extern_id]['jml_debet'] += $mdSpec->qty_debet;

                                                if (!isset($arrRekeningItems[$mdSpec->extern_id]['jml_kredit'])) {
                                                    $arrRekeningItems[$mdSpec->extern_id]['jml_kredit'] = 0;
                                                }
                                                $arrRekeningItems[$mdSpec->extern_id]['jml_kredit'] += $mdSpec->qty_kredit;
                                            }
                                        }

                                        $qtyValidate = true;
                                    }
                                }


                                $arrRequestItems = array();
                                if (isset($registryGates['items']) && (sizeof($registryGates['items']) > 0)) {
                                    foreach ($registryGates['items'] as $pID => $iSpec) {
                                        $arrRequestItems[$pID]['nama'] = $iSpec['nama'];
                                        $arrRequestItems[$pID]['jml'] = $iSpec['jml'];

                                    }
                                }

                                if (count($arrRequestItems) != count($arrRekeningItems)) {
                                    // STOP
                                    $msg = "Jumlah item request " . sizeof($arrRequestItems) . " tidak sama dengan jumlah masuk rekening " . sizeof($arrRekeningItems) . " line " . __LINE__;
                                    mati_disini($msg);
                                }
                                else {
                                    cekHijau("request " . count($arrRequestItems) . ", rekening " . count($arrRekeningItems));
                                    foreach ($arrRequestItems as $pID => $spec) {
                                        $req_nama = $spec['nama'];
                                        $req_jml = $spec['jml'];
                                        $rek_jml = (isset($arrRekeningItems[$pID]['jml_debet']) && ($arrRekeningItems[$pID]['jml_debet'] > 0)) ? $arrRekeningItems[$pID]['jml_debet'] : $arrRekeningItems[$pID]['jml_kredit'];
                                        $rek_jml_debet = (isset($arrRekeningItems[$pID]['jml_debet']) && ($arrRekeningItems[$pID]['jml_debet'] > 0)) ? $arrRekeningItems[$pID]['jml_debet'] : 0;
                                        $rek_jml_kredit = (isset($arrRekeningItems[$pID]['jml_kredit']) && ($arrRekeningItems[$pID]['jml_kredit'] > 0)) ? $arrRekeningItems[$pID]['jml_kredit'] : 0;

                                        if (in_array($jenisTrMaster, $validateSubComponent['dobleValidate'])) {
                                            cekBiru("cek request vs rekDebet dan request vs reqKredit");
                                            // request vs rek qty debet
                                            if ($req_jml != $rek_jml_debet) {
                                                // STOP
                                                $msg = "$req_nama, jumlah request $req_jml tidak sama dengan jumlah masuk rekening $rek_jml_debet";
                                                mati_disini($msg);
                                            }
                                            else {
                                                // LANJUT
                                                cekHijau("$req_nama, request $req_jml, rekening qtyDebet $rek_jml_debet");
                                            }
                                            // request vs rek qty kredit
                                            if ($req_jml != $rek_jml_kredit) {
                                                // STOP
                                                $msg = "$req_nama, jumlah request $req_jml tidak sama dengan jumlah masuk rekening $rek_jml_kredit";
                                                mati_disini($msg);
                                            }
                                            else {
                                                // LANJUT
                                                cekHijau("$req_nama, request $req_jml, rekening qtyKredit $rek_jml_kredit");
                                            }
                                        }
                                        else {
                                            cekBiru("cek request vs rekJml");
                                            if ($req_jml != $rek_jml) {
                                                if ($qtyValidate == true) {
                                                    // STOP
                                                    $msg = "$req_nama, jumlah request $req_jml tidak sama dengan jumlah masuk rekening $rek_jml";
                                                    mati_disini($msg);
                                                }
                                            }
                                            else {
                                                // LANJUT
                                                cekHijau("$req_nama, request $req_jml, rekening $rek_jml");
                                            }

                                        }

                                    }
                                }


                            }
                            else {
                                cekPink2(":: $jenisTrMaster masuk exception ::");
                            }
                        }
                    }
                }

            }

            //region update status sudah dirunning by cli
            $tr = New MdlTransaksi();
            $tr->setFilters(array());
            $where = array(
                "id" => $transaksiID,
            );
            $updateData = array(
                "cli" => 1,
            );
            $tr->updateData($where, $updateData);
            cekHere($this->db->last_query());
            //endregion

            $stopDate = dtimeNow();

            // region menulis ke tabel log time cli
            $cl = New MdlCliLogTime();
            $arrCliData = array(
                "web" => "cli",
                "judul" => "CLI $insertNum $addJudul",
                "waktu_start" => $startDate,
                "waktu_stop" => $stopDate,
                "waktu" => timeDiff($startDate, $stopDate),
                "transaksi_id" => $insertID,
                "nomer" => $insertNum,
                "jenis" => $jenisTr,
                "jenis_master" => $jenisTrMaster,
            );
            $rslt = $cl->addData($arrCliData);
            cekHere($this->db->last_query());
            // endregion


            cekHitam("--- MULAI VALIDATOR ---");
            $this->load->library("Validator");
            $vdt = New Validator();
//            $vdt->validateMasterDetail($trID_cli, $componentConfig['master'], $componentConfig['detail']);


            if ($getTrID > 0) {
                mati_disini(__LINE__ . " || ...cek MANUAL cli transaksi... rekening pembantu masuk disini (component detail)<br>start: $startDate<br>stop: $stopDate<br>butuh waktu: " . timeDiff($startDate, $stopDate));
            }


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

    public function run_cliPenjualan()
    {
        $this->load->helper("he_angka");
        $srcField = array(
            "cabangID" => "cabang_id",
            "placeID" => "cabang_id",
            "cabang_id" => "cabang_id",
            "toko_id" => "toko_id",
            "machine_id" => "machine_id",
            "jenis" => "jenis",
            "jenisTr" => "jenis",
            "toko_nama" => "toko_nama",
            // "oleh_id"      => "oleh_id",
            // "oleh_nama"    => "oleh_nama",
            "transaksi_no" => "nomer",
            "transaksi_id" => "transaksi_id",
            "pihak_id" => "extern2_id",
            "pihak_nama" => "extern2_nama",
            "oleh_id" => "extern_id",
            "oleh_nama" => "extern_nama",
            "dtime2" => "fulldate",
            "kas" => "sisa",
            "nomer" => "nomer",
        );
        $component = array(
            "master" => array(
                //catatan jurnal penjualan tidak jadi dijalanknan hanya nulis raw nya saja
                array(
                    "comName" => "Jurnal",
                    "loop" => array(
                        "1010010" => "kas",//kas setara kas
                        "4" => "penjualan",//penjualan
                        "2030090" => "ppn_gunggungan",//ppn gunggunan
                        "1010070" => "piutang_kasir",//piutang kasir
                    ),
                    "static" => array(
                        "cabang_id" => "cabang_id",
                        "jenis" => "jenis",
                        // "transaksi_no" => "nomer",
                        "toko_id" => "toko_id",
                        "toko_nama" => "toko_nama",
                        "transaksi_no" => "nomer",
                        "transaksi_id" => "transaksi_id",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                array(
                    "comName" => "Rekening",
                    "loop" => array(
                        "1010010" => "kas",//kas setara kas
                        "4" => "penjualan",//penjualan
                        "2030090" => "ppn_gunggungan",//ppn gunggunan
                        "1010070" => "piutang_kasir",//piutang kasir
                    ),
                    "static" => array(
                        "cabang_id" => "cabang_id",
                        "jenis" => "jenis",
                        // "transaksi_no" => "nomer",
                        "toko_id" => "toko_id",
                        "toko_nama" => "toko_nama",
                        "transaksi_no" => "nomer",
                        "transaksi_id" => "transaksi_id",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),

                // //rekening pembantu kas/ setara kas
                // array(
                //     "comName" => "RekeningPembantuKasSetarakas",
                //     "loop" => array(
                //         "1010010" => "kas", // kas
                //     ),
                //     "static" => array(
                //         "toko_id" => "toko_id",
                //         "cabang_id" => "cabang_id",
                //         "extern_id" => ".1010010",
                //         "extern_nama" => ".kas setara kas",
                //         "jenis" => "jenisTr",
                //         "produk_id" => ".1010010010",
                //         "produk_nama" => ".kas",
                //         "produk_nilai" => "kas",
                //         "transaksi_no" => "nomer",
                //         "transaksi_id" => "transaksi_id",
                //     ),
                //     "srcGateName" => "main",
                //     "srcRawGateName" => "main",
                // ),
                // //rekening pembantu penjualan
                // array(
                //     "comName" => "RekeningPembantuPenjualan",
                //     "loop" => array(
                //         "4" => "penjualan",//penjualan
                //     ),
                //     "static" => array(
                //         "toko_id" => "toko_id",
                //         "cabang_id" => "cabang_id",
                //         "extern_id" => ".4010",
                //         "extern_nama" => ".lokal",
                //         "extern2_id" => ".0",//lihat coa untuk urutannya
                //         "extern2_nama" => ".0",
                //         "extern3_id" => ".0",
                //         "extern3_nama" => ".0",
                //         "extern4_id" => ".0",
                //         "extern4_nama" => ".0",
                //         "jenis" => "jenisTr",
                //         "produk_id" => ".4010010",
                //         "produk_nama" => ".lokal",
                //         "jml" => ".1",
                //         "harga" => "penjualan",
                //         "hpp" => "penjualan",
                //         "produk_nilai" => "penjualan",
                //         "oleh_id" => "oleh_id",
                //         "oleh_nama" => "oleh_nama",
                //         "pihak_id" => "pihak_id",
                //         "pihak_nama" => "pihak_nama",
                //         "oleh_top_id" => "oleh_top_id",
                //         "oleh_top_nama" => "oleh_top_nama",
                //         "transaksi_no" => "nomer",
                //         "transaksi_id" => "transaksi_id",
                //     ),
                //     "srcGateName" => "main",
                //     "srcRawGateName" => "main",
                // ),
                // //rekening pembantu kas
                // array(
                //     "comName" => "RekeningPembantuKas",
                //     "loop" => array(
                //         "1010010" => "kas",
                //     ),
                //     "static" => array(
                //         //                            "cabang_id" => "cabang_id",
                //         "cabang_id" => "cabang_id",
                //         "extern_id" => ".1010010010",
                //         "extern_nama" => ".kas",
                //         "produk_id" => "cash_account",
                //         "produk_nama" => "cash_account__nama",
                //         "produk_nilai" => "kas",
                //         "jenis" => "jenisTr",
                //         "toko_id" => "toko_id",
                //         "toko_nama" => "toko_nama",
                //         "oleh_id" => "oleh_id",
                //         "oleh_nama" => "oleh_nama",
                //         "transaksi_no" => "nomer",
                //         "transaksi_id" => "transaksi_id",
                //     ),
                //     "srcGateName" => "items7_sum",
                //     "srcRawGateName" => "items7_sum",
                // ),

                //rekening pembantu kas/ setara kas
                array(
                    "comName" => "RekeningPembantuKasSetarakas",
                    "loop" => array(
                        "1010010" => "kas", // kas
                    ),
                    "static" => array(
                        "toko_id" => "toko_id",
                        "cabang_id" => "cabang_id",
                        "extern_id" => ".1010010",
                        "extern_nama" => ".kas setara kas",
                        "jenis" => "jenis",
                        // "transaksi_no" => "nomer",
                        "produk_id" => ".1010010010",
                        "produk_nilai" => "kas",
                        "produk_nama" => ".kas",
                        "transaksi_id" => "transaksi_id",
                        "transaksi_no" => "transaksi_no",
                        "pihak_id" => "machine_id",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                //rekening pembantu penjualan
                array(
                    "comName" => "RekeningPembantuPenjualan",
                    "loop" => array(
                        "4" => "penjualan",//penjualan
                    ),
                    "static" => array(
                        "toko_id" => "toko_id",
                        "cabang_id" => "cabang_id",
                        "extern_id" => ".4010",
                        "extern_nama" => ".lokal",
                        "extern2_id" => ".0",//lihat coa untuk urutannya
                        "extern2_nama" => ".0",
                        "extern3_id" => ".0",
                        "extern3_nama" => ".0",
                        "extern4_id" => ".0",
                        "extern4_nama" => ".0",
                        "jenis" => "jenisTr",
                        "produk_id" => ".4010010",
                        "produk_nama" => ".Penjualan",
                        "jml" => ".-1",
                        "harga" => "penjualan",
                        "hpp" => "penjualan",
                        "produk_nilai" => "penjualan",
                        "oleh_id" => "oleh_id",
                        "oleh_nama" => "oleh_nama",
                        "pihak_id" => "customers_id",
                        "pihak_nama" => "customers_nama",
                        "oleh_top_id" => "oleh_id",
                        "oleh_top_nama" => "oleh_nama",
                        "transaksi_id" => "transaksi_id",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),


                //rekening pembantu karyawan (piutang kasir) namabaha piutang
                array(
                    "comName" => "RekeningPembantuKaryawan",
                    "loop" => array(
                        "1010070" => "piutang_kasir",
                    ),
                    "static" => array(
                        "pihak_id" => "machine_id",
                        "cabang_id" => "cabang_id",
                        "extern_id" => ".1010070",
                        "extern_nama" => ".piutang kasir",
                        "produk_id" => "oleh_id",
                        "produk_nama" => "oleh_nama",
                        "produk_nilai" => "piutang_kasir",
                        "toko_id" => "toko_id",
                        "toko_nama" => "toko_nama",
                        "oleh_id" => "oleh_id",
                        "oleh_nama" => "oleh_nama",
                        "transaksi_id" => "transaksi_id",
                        "transaksi_no" => "transaksi_no",
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),

                /*
                 * hanya jalan raw penjualan main karena raw penjualan detail(items)sudah bareng degnan jurnal point biar bida realtime
                 */
                //raw pembantu penjualan(diskon penjulan) cabang aka point
                array(
                    "comName" => "RekeningPembantuRawMain",
                    "loop" => array(
                        "4" => "add_diskon",//rekening pembelian untuk keperluan lap
                    ),
                    /*
                     * untuk gerbang coa nantinya dibuat relative ya
                     */
                    "static" => array(
                        "toko_id" => "toko_id",
                        "cabang_id" => "cabang_id",
                        "extern_id" => ".4010",//lokal ,non lokal
                        "extern_nama" => ".lokal",
                        "extern2_id" => ".4010040",//lihat coa untuk urutannya
                        "extern2_nama" => ".diskon penjualan",
                        "extern3_id" => ".0",
                        "extern3_nama" => ".0",
                        "extern4_id" => ".0",
                        "extern4_nama" => ".0",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                        "produk_id" => "customers_id",//customer id
                        "produk_nama" => "customers_nama",
                        "jml" => ".1",

                        "harga" => "diskon",
                        "hpp" => "diskon",
                        "oleh_id" => "oleh_id",
                        "oleh_nama" => "oleh_nama",
                        "pihak_id" => "customer_id",
                        "pihak_nama" => "customer_nama",
                        "oleh_top_id" => "oleh_id",
                        "oleh_top_nama" => "oleh_nama",
                        "satuan_id" => "satuan_id",
                        "satuan_nama" => "satuan",

                        "transaksi_id" => "transaksi_id",
                    ),
                    "srcGateName" => "items8_sum",
                    "srcRawGateName" => "items8_sum",
                ),

            ),

            "detail" => array(
                //rekening pembantu kas
                array(
                    "comName" => "RekeningPembantuKasItem",
                    "loop" => array(
                        "1010010" => "kas_item",
                    ),
                    "static" => array(
                        //                            "cabang_id" => "placeID",
                        "cabang_id" => "cabang_id",
                        "cabang_nama" => "cabangName",
                        "extern_id" => ".1010010010",
                        "extern_nama" => ".kas",
                        "produk_id" => "cash_account",
                        "produk_nama" => "cash_account__label",
                        "produk_nilai" => "kas_item",
                        "jenis" => "jenisTr",
                        //                            "toko_id" => "tokoID",
                        //                            "toko_nama" => "tokoName",
                        "toko_id" => "toko_id",
                        "toko_nama" => "toko_nama",
                        "oleh_id" => "olehID",
                        "oleh_nama" => "olehName",
                        "transaksi_id" => "transaksi_id",
                        "transaksi_no" => "transaksi_no",
                        "machine_id" => "machine_id",
                    ),
                    "srcGateName" => "items7_sum",
                    "srcRawGateName" => "items7_sum",
                ),


            ),
        );

        $this->db->trans_start();


        $this->load->model("Mdls/MdlPaymentSource");
        $this->load->model("Coms/ComRekeningPembantuPenjualan");
        //        $this->load->model("Mdls/MdlGudangDefault");

        $r = new ComRekeningPembantuPenjualan();
        $p = new MdlPaymentSource();
        //        $g = new MdlGudangDefault();
        // $p->addFilter("cabang_id='101'");//tembakan dulu
        //        $p->addFilter("sisa>'0'");
        $p->addFilter("jenis='758'");
        $p->addFilter("cli_penjualan='0'");
        $this->db->order_by("dtime", "asc");
        $this->db->limit(1);
        $tmp = $p->lookUpAll()->result();
        cekHitam($this->db->last_query());
        $main = array();
        $main = array();
        $dtaCustomerTmp = array();
        if (count($tmp) > 0) {
            // arrPrint($tmp);

            // cekMErah($tmp[0]->id);
            $tableID = $tmp[0]->id;
            $datetime = $tmp[0]->dtime;
            $transaksi_no = $tmp[0]->nomer;
            $transaksi_id = $tmp[0]->transaksi_id;
            $machine_id = $tmp[0]->machine_id;
            $cabang_id = $tmp[0]->cabang_id;
            $jenis = $tmp[0]->jenis;
            $tokoID = $tmp[0]->toko_id;
            $cabangID_validate = $tmp[0]->cabang_id;
            $blobData = blobDecode($tmp[0]->externValueBlob);
            //arrPrintWebs($blobData);
            $grandHarga = $tmp[0]->extern_nilai2;//bruto penjualan
            //            $masterKas = $tmp[0]->sisa;//netto kas
            $masterKas = $tmp[0]->tagihan;//netto kas
            $piutangkasir_plus = $tmp[0]->extern_nilai3;//lebih setor
            $piutangkasir_minus = $tmp[0]->extern_nilai4;//kurang setor
            $defaultGudang = getDefaultWarehouseID($cabang_id);
            arrPrint($defaultGudang);

            //            matiHere($piutangkasir_minus);
            $global_diskon = $tmp[0]->diskon;//kurang setor
            $master_piutangkasir = $piutangkasir_minus - $piutangkasir_plus;//kalau plus menambah piutang piutang kasir kalau minus mengurangi piutang kasir

            $finalPrekas = ($masterKas + $piutangkasir_plus) - $piutangkasir_minus;

            //region cek transaksi id dari machine id sudah pernah di execute atau belum
            $r->addFilter("cabang_id='$cabang_id'");
            $r->addFilter("toko_id='$tokoID'");
            $r->addFilter("pihak_id='$machine_id'");
            $moveData = $r->fetchMovesByTransIDs("4", $transaksi_id);
            if (count($moveData) > 0) {
                matiHere("tranksi sudah pernah dieksekusi silahkan dicek trid $transaksi_id machine_id $machine_id cabang_id $cabang_id");
            }
            //             cekHitam($this->db->last_query());
            //
            // matiHEre();
            //endregion

            //region bagian kas akun un tuk akomodasi lebih kuran setor
            $this->load->model("Mdls/MdlBankAccount_cash");
            $b = new MdlBankAccount_cash();
            $b->addFilter("cabang_id='$cabangID_validate'");
            $b->addFilter("toko_id='$tokoID'");
            $tmpBank = $b->lookUpAll()->result();
            cekMErah($this->db->last_query());
            $bank = array();
            if (count($tmpBank) > 0) {
                foreach ($tmpBank as $tmpBank_0) {
                    $bank[$tmpBank_0->id] = $tmpBank_0->nama;
                }
            }
            //            arrPrint($bank);
            //            matiHEre();

            //endregion


            $dataDtime = array();
            $allsales = 0;
            $addDiscSales = 0;
            if (count($blobData) > 0) {
                //                arrPrint($blobData);
                //                matiHere();
                foreach ($blobData["items"] as $mode => $salesData) {
                    //                     arrPrint($salesData);
                    //                     matiHEre($mode);
                    $salesDate = $salesData->fulldate;

                    //                    $notaNilai = $salesData->harga;
                    $notaNilai = $salesData->harga * 1;
                    $add_disc = $salesData->add_disc;

                    $invoiceID = $salesData->nomor;
                    $invoiceNum = $salesData->transaksi_id;
                    $allsales += $notaNilai;

                    $addDiscSales += $salesData->add_disc;
                    $itemsTrans = json_decode(base64_decode($salesData->produk), true);
                    $valPeritem = 0;
                    if (count($itemsTrans) > 0) {
                        foreach ($itemsTrans as $pid => $pidData) {
                            //                            arrPrintPink($pidData);
                            $valPeritem += ($pidData["subtotal"] * 1);
                        }
                    }
                    else {
                        matiHere("rincian nota kosong!");
                    }

                    $selisih_a = $notaNilai - $valPeritem;
                    $selisih_a = ($selisih_a < 0) ? ($selisih_a * -1) : $selisih_a;
                    //                    if (($notaNilai*1) != ($valPeritem*1)) {
                    //                    if ($notaNilai != $valPeritem) {// diganti ke selisih karena ketemu 0,00000000xxxxxxx
                    if ($selisih_a > 1) {// diganti ke selisih karena ketemu 0,00000000xxxxxxx
                        cekHitam("$invoiceID || $invoiceNum :::: $notaNilai != $valPeritem");
                        matiHEre("$mode tidka sama rincian nota dengan nilai nota $notaNilai!=$valPeritem [$selisih_a]");
                    }

                    // if(!isset())
                    $dataDtime[$salesDate][] = array(
                        "harga" => $notaNilai,
                        "add_disc" => $add_disc,
                    );


                    $dtaCustomerTmp[$salesData->cash_account][] = array(
                        "olehID" => $salesData->olehID,
                        "olehName" => $salesData->olehName,
                        "sellerID" => $salesData->sellerID,
                        "customerID" => $salesData->customerID,
                        "customerName" => $salesData->customerName,
                        "toko_id" => $salesData->tokoID,
                        "toko_nama" => $salesData->tokoNama,
                        "sellerName" => $salesData->sellerName,
                        "placeName" => $salesData->sellerName,
                        "cabangID" => $salesData->cabangID,
                        "cabang_id" => $salesData->cabangID,
                        "cabangName" => $salesData->cabangName,
                        "gudangID" => $salesData->gudangID,
                        "gudangName" => $salesData->gudangName,
                        "divID" => $salesData->divID,
                        "tokoID" => $salesData->tokoID,
                        "tokoNama" => $salesData->tokoNama,
                        "stepCode" => $salesData->stepCode,
                        "dtime" => $salesData->dtime,
                        "fulldate" => $salesData->fulldate,
                        "harga" => $salesData->harga,
                        "grand_total" => $salesData->grand_total,
                        "cash_account" => $salesData->cash_account,
                        "cash_account__label" => $salesData->cash_account__label,
                        "transaksi_id" => $salesData->transaksi_id,
                        "tagihan" => $salesData->tagihan,

                    );

                    // matiHEre();
                }
                //arrPrint($dtaCustomerTmp);
                //                matiHere();
                //region versibaru untuk kas baca dari gerbang payment broo
                $dtaCustomer = array();
                if (isset($blobData["payment"]) && count($blobData["payment"]) > 0) {
                    //                    arrPrint($blobData["payment"]);
                    foreach ($blobData["payment"] as $cash_accountID => $cashAccountData) {
                        $dtaCustomer[$cash_accountID] = array(
                            "olehID" => $tmp[0]->oleh_id,
                            "olehName" => $tmp[0]->oleh_nama,
                            "sellerID" => $tmp[0]->oleh_id,
                            "customerID" => "",
                            "customerName" => "",
                            "toko_id" => $tmp[0]->toko_id,
                            "toko_nama" => $tmp[0]->toko_id,
                            "sellerName" => $tmp[0]->oleh_nama,
                            "placeName" => $tmp[0]->sellerName,
                            "cabangID" => $tmp[0]->cabang_id,
                            "cabang_id" => $tmp[0]->cabang_id,
                            "cabangName" => $tmp[0]->cabang_nama,
                            "gudangID" => $defaultGudang["gudang_id"],
                            "gudangName" => $defaultGudang["gudang_nama"],
                            "divID" => $tmp[0]->div_id,
                            "tokoID" => $tmp[0]->toko_id,
                            "tokoNama" => $tmp[0]->toko_nama,
                            "stepCode" => $tmp[0]->jenis,
                            "dtime" => $tmp[0]->dtime,
                            "fulldate" => $tmp[0]->fulldate,
                            "harga" => $cashAccountData["nilai"],
                            "grand_total" => $cashAccountData["nilai"],
                            "cash_account" => $cashAccountData["payID"],
                            "cash_account__label" => $cashAccountData["payNama"],
                            "transaksi_id" => $tmp[0]->transaksi_id,
                            "tagihan" => $cashAccountData["nilai"],
                            "kas_item" => $cashAccountData["nilai"],
                            "transaksi_no" => $tmp[0]->nomer,
                            "jenisTr" => $tmp[0]->jenis,
                            "jenis" => $tmp[0]->jenis,
                        );
                    }

                }
                //                arrPrint($dtaCustomerTmp);

                //                    matiHere();
                //                    matiHere();
                //endregion
            }
            else {
                matiHEre("data rincian kosong transaksi tidak bisa dilanjut " . __LINE__ . " FUNCTION " . __FUNCTION__);
            }

            $selisih = $grandHarga - ($allsales - $addDiscSales);
            $selisih = reformatExponent($selisih);
            $selisih = ($selisih < 0) ? ($selisih * -1) : $selisih;
            cekOrange(__LINE__ . " ::: $selisih");
            if ($selisih > 10) {
                //            if ($grandHarga != ($allsales-$addDiscSales)) {
                //cek tambah /kurang setor
                // $newHarga = $grandHarga+$addDiscSales;
                // if()
                // if($grandHarga!=$finalPrekas){
                //     matiHEre("$grandHarga!=$finalPrekas rincian paymentsource tidak sama dengan summary**");
                // }
                // else{
                //     matiHEre("--$grandHarga!=$finalPrekas---");
                // }
                cekMerah(":: $grandHarga != ($allsales - $addDiscSales) ::");
                matiHEre("$grandHarga!=$allsales rincian paymentsource tidak sama dengan summary $cabang_id*id table $tableID*$datetime*");
            }
            //            $selisih = $grandHarga - ($allsales-$addDiscSales);
            //            mati_disini(__LINE__ . " ::: $selisih");
            $finalData = array();
            $penjualan_bruto = 0;
            $addDisc = 0;
            if (count($dataDtime) > 0) {
                foreach ($dataDtime as $dt => $dtValue) {
                    foreach ($srcField as $key => $srcKey) {
                        $finalData[$dt][$key] = $tmp[0]->$srcKey;
                    }
                    // arrPrint($dtValue);
                    $finalData[$dt]["dtime"] = $dt;
                    $finalData[$dt]["dtime"] = $dt;
                    $finalData[$dt]["fulldate"] = $dt;
                    $finalData[$dt]["kas"] = $tmp[0]->tagihan;
                    $finalData[$dt]["piutang_kasir"] = $tmp[0]->extern_nilai4 - $tmp[0]->extern_nilai3;

                    foreach ($dtValue as $dtValue_0) {
                        if (!isset($finalData[$dt]["penjualan"])) {
                            $finalData[$dt]["penjualan"] = 0;
                        }
                        if (!isset($finalData[$dt]["add_diskon"])) {
                            $finalData[$dt]["add_diskon"] = 0;
                        }
                        $penjualan_bruto += $dtValue_0["harga"];
                        $addDisc += $dtValue_0["add_disc"];
                        $penjualan = $penjualan_bruto - $addDisc;
                        $finalData[$dt]["penjualan"] = $penjualan;
                        $finalData[$dt]["add_diskon"] = $addDisc * -1;
                        $finalData[$dt]["diskon"] = $addDisc;
                    }


                }
                // arrprint($dataDtime);
            }

            //             arrprint($dtaCustomerTmp);
            //             matiHere();
            //            $dtaCustomer = array();
            $totalKas = 0;
            if (count($dtaCustomer) > 0) {
                //region perhitangan kas lebih/kurang
                $totalKas = 0;
                if (count($dtaCustomer) > 0) {
                    foreach ($dtaCustomer as $bank_id => $kasData) {
                        //                        if (isset($bank[$bank_id])) {
                        //                            cekHitam($piutangkasir_plus . "|| " . $piutangkasir_minus);
                        //                            $newval = ($dtaCustomer[$bank_id]["pre_kas"] + $piutangkasir_plus) - $piutangkasir_minus;
                        //                            $dtaCustomer[$bank_id]["kas_item"] = $newval;
                        //                        }
                        //                        else {
                        //                            $dtaCustomer[$bank_id]["kas_item"] = $dtaCustomer[$bank_id]["pre_kas"];
                        //                        }
                        $totalKas += $dtaCustomer[$bank_id]["kas_item"];

                    }
                    //                    matiHere("INI");

                }
                else {
                    matiHEre("KOG KOSOSNG");
                }

                //endregion
            }
            arrPrintWebs($dtaCustomer);
            //             matiHere($totalKas);

            //region validasi akhir sebelum data dipakai tranasksi divalidasi ulang
            $cekRincianFinal = 0;
            foreach ($finalData as $finalData_0) {
                $cekRincianFinal += $finalData_0["penjualan"];
            }

            $selisih_final = $cekRincianFinal - $grandHarga;
            $selisih_final = reformatExponent($selisih_final);
            $selisih_final = ($selisih_final < 0) ? ($selisih_final * -1) : $selisih_final;
            if ($selisih_final > 10) {
                //            if ($cekRincianFinal != $grandHarga) {
                matiHEre("data rincian per tanggal settlement tidak sama dengan total settlement $cekRincianFinal != $grandHarga ||" . __LINE__);
            }

            $selisih_master = $totalKas - $masterKas;
            $selisih_master = reformatExponent($selisih_master);
            $selisih_master = ($selisih_master < 0) ? ($selisih_master * -1) : $selisih_master;
            if ($selisih_master > 10) {
                //            if ($totalKas != $masterKas) {
                $selisih = $masterKas - $totalKas;
                cekHitam($tmp[0]->id . "|rincian kas |$totalKas != $masterKas|| global diskon $global_diskon selisih " . $selisih);
                matiHEre("data rincian kas per settlement tidak sama dengan setoran $cabang_id*id table $tableID*$datetime*");
            }
            // arrprint($dtaCustomer);
            // matiHere(__LINE__);
            //endregion
            // arrPrint($finalData);
            if (count($finalData) > 0) {
                foreach ($finalData as $dtimeSrc => $mainData) {
                    $dtime = $dtimeSrc;
                    $fulldate = $mainData["fulldate"];
                    $jenisTrName = "settlement pos";
                    $oleh_nama = $mainData["oleh_nama"];
                    $this->jenisTr = $jenis = $mainData["jenis"];
                    $buildTablesMaster = $component["master"];
                    if (sizeof($buildTablesMaster) > 0) {
                        $bCtr = 0;
                        foreach ($buildTablesMaster as $buildTablesMaster_specs) {
                            $bCtr++;
                            $mdlName = $buildTablesMaster_specs['comName'];
                            // if (substr($mdlName, 0, 1) == "{") {
                            //     $mdlName = trim($mdlName, "{");
                            //     $mdlName = trim($mdlName, "}");
                            //     $mdlName = str_replace($mdlName, $main[$mdlName], $mdlName);
                            // }

                            //--- INI UNTUK BUILD TABLES REKENING
                            $mdlName = "Com" . $mdlName;
                            $this->load->model("Coms/" . $mdlName);
                            $m = new $mdlName();
                            if (isset($buildTablesMaster_specs['loop']) && sizeof($buildTablesMaster_specs['loop']) > 0) {
                                foreach ($buildTablesMaster_specs['loop'] as $key => $val) {
                                    if (substr($key, 0, 1) == "{") {
                                        $oldParam = $buildTablesMaster_specs['loop'][$key];
                                        unset($buildTablesMaster_specs['loop'][$key]);
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $main[$key], $key);
                                        $buildTablesMaster_specs['loop'][$key] = $oldParam;
                                    }
                                }
                            }
                            if (method_exists($m, "getTableNameMaster")) {
                                // arrPrint($buildTablesMaster_specs);
                                // matiHEre(__LINE__);
                                if (sizeof($m->getTableNameMaster())) {
                                    $m->buildTables($buildTablesMaster_specs);
                                    // cekHijau(" === build tabel rekening === ");
                                }
                            }
                        }
                    }

                    $buildTablesDetail = $component["detail"];
                    if (sizeof($component["detail"]) > 0) {
                        foreach ($buildTablesDetail as $buildTablesDetail_specs) {
                            foreach ($dtaCustomer as $itemSpec) {
                                $mdlName = $buildTablesDetail_specs['comName'];
                                // cekLime($mdlName);
                                if (substr($mdlName, 0, 1) == "{") {
                                    $mdlName = trim($mdlName, "{");
                                    $mdlName = trim($mdlName, "}");
                                    $mdlName = str_replace($mdlName, $itemSpec[$mdlName], $mdlName);
                                }
                                $mdlName = "Com" . $mdlName;
                                $this->load->model("Coms/" . $mdlName);
                                $m = new $mdlName();

                                if (isset($buildTablesDetail_specs['loop']) && sizeof($buildTablesDetail_specs['loop']) > 0) {
                                    foreach ($buildTablesDetail_specs['loop'] as $key => $val) {
                                        if (substr($key, 0, 1) == "{") {
                                            $oldParam = $buildTablesDetail_specs['loop'][$key];
                                            unset($buildTablesDetail_specs['loop'][$key]);
                                            $key = trim($key, "{");
                                            $key = trim($key, "}");
                                            $key = str_replace($key, $itemSpec[$key], $key);
                                            $buildTablesDetail_specs['loop'][$key] = $oldParam;
                                        }
                                    }
                                }
                                if (method_exists($m, "getTableNameMaster")) {
                                    if (sizeof($m->getTableNameMaster())) {
                                        $m->buildTables($buildTablesDetail_specs);
                                        // cekHitam(" === build tabel rekening === ");
                                    }
                                }
                            }
                        }
                    }

                    $componentGate['master'] = array();
                    $componentConfig['master'] = array();
                    //==filter nilai, jika NOL tidak dikirim, sesuai config==
                    $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();

                    $iterator = array();
                    $componentConfig['master'] = $buildTablesMaster;
                    $iterator = $buildTablesMaster;
                    $tempTableinMAster = $mainData;
                    //region master
                    // $iterator = array();
                    if (sizeof($iterator) > 0) {
                        $componentConfig['master'] = $iterator;
                        $cCtr = 0;
                        foreach ($iterator as $cCtr => $tComSpec) {
                            $cCtr++;
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $mainData, $comName);
                            }
                            // $srcGateName = $tComSpec['srcGateName'];
                            // $srcRawGateName = $tComSpec['srcRawGateName'];
                            // cekHere("component # $cCtr: $comName<br>");

                            $dSpec = $mainData;
                            $tmpOutParams = array();
                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $mainData[$key], $key);
                                    }
                                    $realValue = makeValue($value, $mainData, $mainData, 0);
                                    $tmpOutParams['loop'][$key] = $realValue;
                                }
                            }
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {
                                    $realValue = makeValue($value, $mainData, $mainData, 0);
                                    $tmpOutParams['static'][$key] = $realValue;
                                }
                                if (!isset($tmpOutParams['static']["transaksi_id"])) {
                                    $tmpOutParams['static']["transaksi_id"] = "0000";
                                }
                                if (!isset($tmpOutParams['static']["transaksi_no"])) {
                                    $tmpOutParams['static']["transaksi_no"] = "0000";
                                }
                                $tmpOutParams['static']["urut"] = $cCtr;
                                $tmpOutParams['static']["fulldate"] = $fulldate;
                                $tmpOutParams['static']["dtime"] = $dtime;
                                $tmpOutParams['static']["keterangan"] = $jenisTrName . " oleh " . $oleh_nama;
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
                            // arrprint($jenis);
                            //                     matiHEre();
                            if ($tobeExecuted) {
                                //----- kiriman gerbang untuk counter mutasi rekening
                                if (method_exists($m, "setTableInMaster")) {
                                    $m->setTableInMaster($tempTableinMAster);
                                }
                                if (method_exists($m, "setMain")) {
                                    $m->setMain($mainData);
                                }
                                if (method_exists($m, "setJenisTr")) {
                                    $m->setJenisTr($jenis);
                                }
                                arrPrint($tmpOutParams);
                                //----- kiriman gerbang untuk counter mutasi rekening
                                $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            }
                            $componentGate['master'][$cCtr] = $tmpOutParams;
                        }
                    }
                    else {
                        cekKuning("components is not set");
                    }
                    //endregion


                }
                $buildTablesDetail = $component["detail"];
                if (sizeof($buildTablesDetail) > 0) {
                    foreach ($buildTablesDetail as $buildTablesDetail_specs) {
                        // arrPrint($buildTablesDetail_specs);
                        // arrPrint($buildTablesDetail_specs);
                        foreach ($dtaCustomer as $itemSpec) {
                            $mdlName = $buildTablesDetail_specs['comName'];
                            // cekLime($mdlName);
                            if (substr($mdlName, 0, 1) == "{") {
                                $mdlName = trim($mdlName, "{");
                                $mdlName = trim($mdlName, "}");
                                $mdlName = str_replace($mdlName, $itemSpec[$mdlName], $mdlName);
                            }
                            $mdlName = "Com" . $mdlName;
                            $this->load->model("Coms/" . $mdlName);
                            $m = new $mdlName();

                            if (isset($buildTablesDetail_specs['loop']) && sizeof($buildTablesDetail_specs['loop']) > 0) {
                                foreach ($buildTablesDetail_specs['loop'] as $key => $val) {
                                    if (substr($key, 0, 1) == "{") {
                                        $oldParam = $buildTablesDetail_specs['loop'][$key];
                                        unset($buildTablesDetail_specs['loop'][$key]);
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $itemSpec[$key], $key);
                                        $buildTablesDetail_specs['loop'][$key] = $oldParam;
                                    }
                                }
                            }
                            if (method_exists($m, "getTableNameMaster")) {
                                if (sizeof($m->getTableNameMaster())) {
                                    $m->buildTables($buildTablesDetail_specs);
                                    // cekHitam(" === build tabel rekening === ");
                                }
                            }
                        }
                    }
                }
                //region processing sub-components, if in single step geser ke CLI

                $componentGate['detail'] = array();
                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                $filterNeeded = false;
                $componentConfig['detail'] = $buildTablesDetail;
                $iterator = $buildTablesDetail;
                // $iterator =array();
                if (sizeof($iterator) > 0) {
                    $comsLocation = "Coms";
                    $comsPrefix = "Com";
                    foreach ($iterator as $cCtr => $tComSpec) {
                        // arrprint($tComSpec);
                        $tmpOutParams[$cCtr] = array();
                        $gg = 0;
                        // $srcGateName = $tComSpec['srcGateName'];
                        // if ($componentsDetailLoop == true) {
                        foreach ($dtaCustomer as $id => $dSpec) {
                            // $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $dtaCustomer, $comName);
                            }

                            $mdlName = "$comsPrefix" . ucfirst($comName);
                            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                $filterNeeded = true;
                            }
                            else {
                                $filterNeeded = false;
                            }
                            // cekHere("sub-component: [$comsLocation] $comName, initializing values <br>");

                            $subParams = array();

                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $dSpec[$key], $key);
                                    }

                                    $realValue = makeValue($value, $dSpec, $dSpec, 0);
                                    // cekMErah("$key =>".$realValue);
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
                                    $realValue = makeValue($value, $dSpec, $dSpec, 0);
                                    $subParams['static'][$key] = $realValue;
                                }
                                if (!isset($subParams['static']["transaksi_id"])) {
                                    $subParams['static']["transaksi_id"] = 0000;
                                }
                                if (!isset($subParams['static']["transaksi_no"])) {
                                    $subParams['static']["transaksi_no"] = 0000;
                                }

                                $subParams['static']["fulldate"] = $fulldate;
                                $subParams['static']["dtime"] = $dtime;
                                $subParams['static']["keterangan"] = $jenisTrName . " oleh " . $oleh_nama;
                            }

                            if (sizeof($subParams) > 0) {
                                //                                cekhitam("subparam ada isinya");
                                if ($filterNeeded) {
                                    if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                }
                            }
                            else {
                                cekhitam("subparam TIDAK ada isinya");
                            }
                        }


                        $componentGate['detail'][$cCtr] = $subParams;
                    }
                    // arrPrint($tmpOutParams);
                    // matiHEre($cCtr);

                    foreach ($iterator as $cCtr => $tComSpec) {
                        // $srcGateName = $tComSpec['srcGateName'];
                        foreach ($dtaCustomer as $id => $dSpec) {
                            // $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $dtaCustomer[$id][$comName], $comName);
                            }
                        }
                        cekHere("sub component: [$comsLocation] $comName, sending values " . __LINE__ . "<br>");

                        $mdlName = "$comsPrefix" . ucfirst($comName);
                        $this->load->model("$comsLocation/" . $mdlName);
                        $m = new $mdlName();
                        //===filter value nol, jika harus difilter

                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        // matiHEre($tobeExecuted);
                        if ($tobeExecuted) {
                            //----- kiriman gerbang
                            if (method_exists($m, "setTableInMaster")) {
                                $m->setTableInMaster($tempTableinMAster);
                            }
                            if (method_exists($m, "setDetail")) {
                                $m->setDetail($dtaCustomer);
                            }
                            if (method_exists($m, "setJenisTr")) {
                                $m->setJenisTr($this->jenisTr);
                            }
                            //----- kiriman gerbang
                            $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            cekBiru($this->db->last_query());
                        }
                        else {
                            cekMerah("$comName tidak eksekusi");
                        }

                    }
                }
                else {
                    cekKuning("subcomponents is not set");
                }

                //endregion
            }
            // matiHere();
            validateAllBalances($tokoID, $cabangID_validate);
            // matiHere();
            //region update row yang sudah diambil
            $p->setFilters(array());
            $p->updateData(array("id" => $tableID), array("cli_penjualan" => "1"));
            cekHitam($this->db->last_query());
            //endregion

        }

        // arrprint($dataDtime);

        //        matiHEre("complitt dan berhasil...");
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
    }

    //---------------------------------------
    public function run_bukuPembantuPenjualan()
    {
        if (isset($_GET["r"]) && ($_GET["r"] > 0)) {
            $refresh = $_GET["r"];
            header("refresh:$refresh");
        }


        $this->db->trans_start();
        $start = microtime(true);
        $force = isset($_GET["force"]) ? $_GET["force"] : "none";
        $cekjam = date("H");
        $this->load->helper("he_angka");
        $jenisTr = "5822spd";
        $arrJenisTr = array(
            "5822so",
            "5822spd",
            "9822",
            //-----
            "4822",
            "9912",
            //-----
            "5823so",
            "5823spd",
        );
        $main = array();
        $items = array();
        $tableIn_master = array();
        $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();


        $timeTOexec = true;
        if ($timeTOexec) {
            $tr = New MdlTransaksi();
//            $tr->addFilter("jenis='$jenisTr'");
            $tr->addFilter("jenis in ('" . implode("','", $arrJenisTr) . "')");
            $tr->addFilter("r_maju='0'");
//            $tr->addFilter("id<=3000");
            $this->db->order_by("id", "asc");
            $this->db->limit("1");
            $trTmp = $tr->lookupAll()->result();
            if (sizeof($trTmp) > 0) {
                $transaksi_id = $trTmp[0]->id;
                $fulldate = $trTmp[0]->fulldate;
                $dtime = $trTmp[0]->dtime;
                $jenisTrName = $trTmp[0]->jenis_label;
                $oleh_id = $trTmp[0]->oleh_id;
                $oleh_nama = $trTmp[0]->oleh_nama;
                $jenisTr = $trTmp[0]->jenis_master;
                $referenceJenisTr = $trTmp[0]->reference_jenis;
                $idsHis = ($trTmp[0]->ids_his != null) ? blobDecode($trTmp[0]->ids_his) : array();
                if (sizeof($idsHis) > 0) {
                    foreach ($idsHis as $step_his => $data_his) {
                        $keyss_ids = "transaksi_id_" . $step_his;
                        $keyss_nomers = "transaksi_no_" . $step_his;
                        $trTmp[0]->$keyss_ids = $data_his["trID"];
                        $trTmp[0]->$keyss_nomers = $data_his["nomer"];
                    }
                }

                $trData = (array)$trTmp[0];

                // region membaca registry items dan main
                $trReg = New MdlTransaksi();
                $trReg->setFilters(array());
                $trReg->setJointSelectFields("transaksi_id, main, items, tableIn_master");
                $trReg->addFilter("transaksi_id='$transaksi_id'");
                $trRegTmp = $trReg->lookupDataRegistries()->result();
                showLast_query("biru");
                foreach ($trRegTmp as $regs) {
                    foreach ($regs as $key => $val) {
                        if ($key != "transaksi_id") {
                            switch ($key) {
                                case "main":
                                    $main = blobDecode($val);
                                    break;
                                case "items":
                                    $items = blobDecode($val);
                                    break;
                                case "tableIn_master":
                                    $tableIn_master = blobDecode($val);
                                    break;
                            }
                        }
                    }
                }
                // endregion

                if (sizeof($items) > 0) {
                    foreach ($items as $pID => $iSpec) {
//                        "ppn_nilai" => "ppn_nilai",
//                        "sub_ppn_nilai" => "sub_ppn_nilai",
                        $iSpec["ppn_nilai"] = ($iSpec["nett1"] * 0.11);
                        $iSpec["sub_ppn_nilai"] = ($iSpec["sub_nett1"] * 0.11);
                        foreach ($main as $m_key => $m_val) {
                            $new_m_key = "main__" . $m_key;
                            $iSpec[$new_m_key] = $m_val;
                        }
                        foreach ($trData as $tr_key => $tr_val) {
                            $new_tr_key = "tr__" . $tr_key;
                            $iSpec[$new_tr_key] = $tr_val;
                        }
//                        arrPrintKuning($iSpec);
                        $items[$pID] = $iSpec;
                    }
                }


                $srcField = array(
                    "cabangID" => "cabang_id",
                    "placeID" => "cabang_id",
                    "cabang_id" => "cabang_id",
                    "toko_id" => "toko_id",
                    "machine_id" => "machine_id",
                    "jenis" => "jenis",
                    "jenisTr" => "jenis",
                    "toko_nama" => "toko_nama",
                    "pihak_id" => "pihak_id",
                    "pihak_nama" => "pihak_nama",
                    "oleh_id" => "oleh_id",
                    "oleh_nama" => "oleh_nama",
                    "dtime" => "dtime",
//            "kas" => "sisa",
//            "nomer" => "nomer",
                );
                $selectedItemFields = array(
                    "id" => "id",
                    "toko_id" => "toko_id",
                    "cabang_id" => "cabang_id",
                    "produk_id" => "produk_id",
                    "produk_nama" => "produk_nama",
                    "valid_qty" => "valid_qty",
                    "produk_ord_jml" => "produk_ord_jml",
                    "produk_ord_jml_return" => "produk_ord_jml_return",
                    "sisa" => "sisa",
                    "produk_ord_hrg" => "produk_ord_hrg",
                    "produk_ord_hpp" => "produk_ord_hpp",
                    "dtime" => "dtime",
                    "fulldate" => "dtime",
                    "jml" => "sisa",
                    "qty" => "sisa",
                    "satuan" => "satuan",

                );

                switch ($jenisTr) {
                    case "5822":
                    case "5823":
                        $postproc = array(
                            "master" => array(),
                            "detail" => array(
                                array(
                                    "comName" => "RekeningPembantuRaw",
                                    "loop" => array(
                                        "4010" => "sub_nett1",//rekening pembelian untuk keperluan lap
                                    ),
                                    "static" => array(
                                        "cabang_id" => "cabangID",
                                        "cabang_nama" => "cabangName",
                                        "extern_id" => ".4010",//lokal ,non lokal
                                        "extern_nama" => ".penjualan",
                                        "extern2_id" => ".4010010",//lihat coa untuk urutannya
                                        "extern2_nama" => ".lokal",
//                            "extern3_id" => ".0",
//                            "extern3_nama" => "machine_id",//diisi machinid
//                            "extern4_id" => ".0",
//                            "extern4_nama" => ".0",
                                        "jenis" => "tr__jenis",
                                        "transaksi_id" => "tr__id",
                                        "transaksi_no" => "tr__nomer",
                                        "produk_id" => "id",
                                        "produk_nama" => "nama",
                                        "produk_kode" => "produk_kode",
                                        "produk_jenis" => "jenis",
                                        "barcode" => "barcode",
                                        "jml" => "jml",
                                        "harga" => "nett1",// harga dpp
                                        "hpp" => "hpp",// hpp produk
                                        "harga_include_ppn" => "harga_include_ppn",// harga include ppn
                                        "sub_harga" => "sub_nett1",// harga dpp
                                        "sub_hpp" => "sub_hpp",// hpp produk
                                        "sub_harga_include_ppn" => "sub_harga_include_ppn",// harga include ppn
                                        "oleh_id" => "tr__oleh_id",
                                        "oleh_nama" => "tr__oleh_nama",
                                        "pihak_id" => "pihakID",// konsumen
                                        "pihak_nama" => "pihakName", // konsumen
                                        "oleh_top_id" => "oleh_top_id",
                                        "oleh_top_nama" => "oleh_top_nama",
                                        "satuan_id" => "satuan_id",
                                        "satuan_nama" => "satuan_nama",
                                        "rugilaba" => "rugilaba",
                                        "master_id" => "tr__id_master",
//                                        "diskon" => "discNilai",
//                                        "diskon_persen" => "discPersen",
                                        "diskon" => "disc",
                                        "diskon_persen" => "disc_percent",
                                        "sub_diskon" => "sub_disc",
                                        "sub_diskon_persen" => "sub_disc_percent",
                                        //----------------
                                        "outdoor_id" => "outdoor_id",
                                        "outdoor_nama" => "outdoor_nama",
                                        "outdoor_barcode" => "outdoor_barcode",
                                        "outdoor_sku" => "outdoor_sku",
                                        "indoor_id_1" => "indoor_id_1",
                                        "indoor_nama_1" => "indoor_nama_1",
                                        "indoor_barcode_1" => "indoor_barcode_1",
                                        "indoor_sku_1" => "indoor_sku_1",
                                        "indoor_id_2" => "indoor_id_2",
                                        "indoor_nama_2" => "indoor_nama_2",
                                        "indoor_barcode_2" => "indoor_barcode_2",
                                        "indoor_sku_2" => "indoor_sku_2",
                                        "indoor_id_3" => "indoor_id_3",
                                        "indoor_nama_3" => "indoor_nama_3",
                                        "indoor_barcode_3" => "indoor_barcode_3",
                                        "indoor_sku_3" => "indoor_sku_3",
                                        "indoor_id_4" => "indoor_id_4",
                                        "indoor_nama_4" => "indoor_nama_4",
                                        "indoor_barcode_4" => "indoor_barcode_4",
                                        "indoor_sku_4" => "indoor_sku_4",
                                        "kategori_id" => "kategori_id",
                                        "kategori_nama" => "kategori_nama",
                                        "produk_part_id_1" => "part_id_1",
                                        "produk_part_nama_1" => "part_nama_1",
                                        "produk_part_barcode_1" => "part_barcode_1",
                                        "produk_part_id_2" => "part_id_2",
                                        "produk_part_nama_2" => "part_nama_2",
                                        "produk_part_barcode_2" => "part_barcode_2",
                                        "heater_id" => "heater_id",
                                        "heater_nama" => "heater_nama",
                                        "heater_barcode" => "heater_barcode",
                                        //----------------
                                        "sales_admin_id" => "main__sellerID",
                                        "sales_admin_nama" => "main__sellerName",
                                        "salesman_id" => "main__salesmanDetails",
                                        "salesman_nama" => "main__salesmanDetails__nama",
                                        "gudang_id_kirim" => "main__gudangStatusDetails",
                                        "gudang_nama_kirim" => "main__gudangStatusDetails__nama",
                                        "delivery_id" => "main__shippingMethod",
                                        "delivery_nama" => "main__shippingMethod__name",
                                        "pengirim_id" => "main__pengirimID",
                                        "pengirim_nama" => "main__pengirimName",
                                        "pembayaran_nama" => "main__paymentMethod",
                                        //----------------
                                        "transaksi_id_1" => "tr__transaksi_id_1",
                                        "transaksi_no_1" => "tr__transaksi_no_1",
                                        "transaksi_id_2" => "tr__transaksi_id_2",
                                        "transaksi_no_2" => "tr__transaksi_no_2",
                                        "transaksi_id_3" => "tr__transaksi_id_3",
                                        "transaksi_no_3" => "tr__transaksi_no_3",
                                        "transaksi_id_4" => "tr__transaksi_id_4",
                                        "transaksi_no_4" => "tr__transaksi_no_4",
                                        "transaksi_id_5" => "tr__transaksi_id_5",
                                        "transaksi_no_5" => "tr__transaksi_no_5",
                                        //----------------
                                        "transaksi_nilai" => "new_net3",
                                        "ppn_nilai" => "ppn_nilai",
                                        "sub_ppn_nilai" => "sub_ppn_nilai",
                                        //----------------
//                                        "kirim_metode_id" => "kirim_metode_id",
//                                        "kirim_metode_nama" => "kirim_metode_nama",
                                    ),
                                    "srcGateName" => "items",
                                    "srcRawGateName" => "items",
                                ),
                            ),
                        );
                        break;
                    case "9822":
                        $postproc = array(
                            "master" => array(),
                            "detail" => array(
                                array(
                                    "comName" => "RekeningPembantuRaw",
                                    "loop" => array(
                                        "4010" => "-sub_nett1",//rekening pembelian untuk keperluan lap
                                    ),
                                    "static" => array(
                                        "cabang_id" => "cabangID",
                                        "cabang_nama" => "cabangName",
                                        "extern_id" => ".4020",//lokal ,non lokal
                                        "extern_nama" => ".return penjualan",
                                        "extern2_id" => ".4020010",//lihat coa untuk urutannya
                                        "extern2_nama" => ".lokal",
//                            "extern3_id" => ".0",
//                            "extern3_nama" => "machine_id",//diisi machinid
//                            "extern4_id" => ".0",
//                            "extern4_nama" => ".0",
                                        "jenis" => "tr__jenis",
                                        "transaksi_id" => "tr__id",
                                        "transaksi_no" => "tr__nomer",
                                        "produk_id" => "id",
                                        "produk_nama" => "nama",
                                        "produk_kode" => "produk_kode",
                                        "produk_jenis" => "jenis",
                                        "barcode" => "barcode",
                                        "jml" => "-jml",
                                        "harga" => "nett1",// harga dpp
                                        "hpp" => "hpp",// hpp produk
                                        "harga_include_ppn" => "harga_include_ppn",// harga include ppn
                                        "sub_harga" => "sub_nett1",// harga dpp
                                        "sub_hpp" => "sub_hpp",// hpp produk
                                        "sub_harga_include_ppn" => "sub_harga_include_ppn",// harga include ppn
                                        "oleh_id" => "tr__oleh_id",
                                        "oleh_nama" => "tr__oleh_nama",
                                        "pihak_id" => "pihakID",// konsumen
                                        "pihak_nama" => "pihakName", // konsumen
                                        "oleh_top_id" => "oleh_top_id",
                                        "oleh_top_nama" => "oleh_top_nama",
                                        "satuan_id" => "satuan_id",
                                        "satuan_nama" => "satuan_nama",
                                        "rugilaba" => "rugilaba",
                                        "master_id" => "tr__id_master",
//                                        "diskon" => "discNilai",
//                                        "diskon_persen" => "discPersen",
                                        "diskon" => "disc",
                                        "diskon_persen" => "disc_percent",
                                        "sub_diskon" => "sub_disc",
                                        "sub_diskon_persen" => "sub_disc_percent",
                                        //----------------
                                        "outdoor_id" => "outdoor_id",
                                        "outdoor_nama" => "outdoor_nama",
                                        "outdoor_barcode" => "outdoor_barcode",
                                        "outdoor_sku" => "outdoor_sku",
                                        "indoor_id_1" => "indoor_id_1",
                                        "indoor_nama_1" => "indoor_nama_1",
                                        "indoor_barcode_1" => "indoor_barcode_1",
                                        "indoor_sku_1" => "indoor_sku_1",
                                        "indoor_id_2" => "indoor_id_2",
                                        "indoor_nama_2" => "indoor_nama_2",
                                        "indoor_barcode_2" => "indoor_barcode_2",
                                        "indoor_sku_2" => "indoor_sku_2",
                                        "indoor_id_3" => "indoor_id_3",
                                        "indoor_nama_3" => "indoor_nama_3",
                                        "indoor_barcode_3" => "indoor_barcode_3",
                                        "indoor_sku_3" => "indoor_sku_3",
                                        "indoor_id_4" => "indoor_id_4",
                                        "indoor_nama_4" => "indoor_nama_4",
                                        "indoor_barcode_4" => "indoor_barcode_4",
                                        "indoor_sku_4" => "indoor_sku_4",
                                        "kategori_id" => "kategori_id",
                                        "kategori_nama" => "kategori_nama",
                                        "produk_part_id_1" => "part_id_1",
                                        "produk_part_nama_1" => "part_nama_1",
                                        "produk_part_barcode_1" => "part_barcode_1",
                                        "produk_part_id_2" => "part_id_2",
                                        "produk_part_nama_2" => "part_nama_2",
                                        "produk_part_barcode_2" => "part_barcode_2",
                                        "heater_id" => "heater_id",
                                        "heater_nama" => "heater_nama",
                                        "heater_barcode" => "heater_barcode",
                                        //----------------
                                        "sales_admin_id" => "main__sellerID",
                                        "sales_admin_nama" => "main__sellerName",
                                        "salesman_id" => "main__salesmanDetails",
                                        "salesman_nama" => "main__salesmanDetails__nama",
                                        "gudang_id_kirim" => "main__gudangStatusDetails",
                                        "gudang_nama_kirim" => "main__gudangStatusDetails__nama",
                                        "delivery_id" => "main__shippingMethod",
                                        "delivery_nama" => "main__shippingMethod__name",
                                        "pengirim_id" => "main__pengirimID",
                                        "pengirim_nama" => "main__pengirimName",
                                        "pembayaran_nama" => "main__paymentMethod",
                                        //----------------
                                        "transaksi_id_1" => "tr__transaksi_id_1",
                                        "transaksi_no_1" => "tr__transaksi_no_1",
                                        "transaksi_id_2" => "tr__transaksi_id_2",
                                        "transaksi_no_2" => "tr__transaksi_no_2",
                                        "transaksi_id_3" => "tr__transaksi_id_3",
                                        "transaksi_no_3" => "tr__transaksi_no_3",
                                        "transaksi_id_4" => "tr__transaksi_id_4",
                                        "transaksi_no_4" => "tr__transaksi_no_4",
                                        "transaksi_id_5" => "tr__transaksi_id_5",
                                        "transaksi_no_5" => "tr__transaksi_no_5",
                                        //----------------
                                        "transaksi_nilai" => "new_net3",
                                        "ppn_nilai" => "ppn_nilai",
                                        "sub_ppn_nilai" => "sub_ppn_nilai",
                                        //----------------
//                                        "kirim_metode_id" => "kirim_metode_id",
//                                        "kirim_metode_nama" => "kirim_metode_nama",
                                    ),
                                    "srcGateName" => "items",
                                    "srcRawGateName" => "items",
                                ),

                            ),
                        );
                        break;
                    case "4822":
                        switch ($main["jenis_source"]) {
                            case "4464":
                                $tr = New MdlTransaksi();
                                $tr->setFilters(array());
                                $tr->addFilter("id=" . $main["refID"]);
                                $trTmpx = $tr->lookupAll()->result();
                                if (sizeof($trTmpx) > 0) {
                                    $master_idd = $trTmpx[0]->id_master;
                                    $where = array(
                                        "master_id" => $master_idd,
                                    );
                                    $data = array(
                                        "transaksi_id_inv" => $trTmp[0]->id,
                                        "transaksi_no_inv" => $trTmp[0]->nomer,
                                    );
                                    $tbl = "__raw_rek_pembantu__4010";
                                    $crr = New ComRekeningPembantuRaw();
                                    $crr->setTableName($tbl);
                                    $crr->updateData($where, $data);
                                    showLast_query("orange");

                                    $refs = array(
                                        "referenceID_1" => $trTmpx[0]->id_top,
                                        "referenceID_2" => $trTmpx[0]->id,
//                                        "referenceID_3" => $main["referenceID_3"],
//                                        "referenceID_4" => $main["referenceID_4"],
                                    );
                                    $main["references_ids"] = implode(",", $refs);

                                }
                                break;
                            default:
                                $master_idd = $main["referenceID_1"];
                                $where = array(
                                    "master_id" => $master_idd,
                                );
                                $data = array(
                                    "transaksi_id_inv" => $trTmp[0]->id,
                                    "transaksi_no_inv" => $trTmp[0]->nomer,
                                );
                                $tbl = "__raw_rek_pembantu__4010";
                                $crr = New ComRekeningPembantuRaw();
                                $crr->setTableName($tbl);
                                $crr->updateData($where, $data);
                                showLast_query("orange");
                                //------------------------------
                                $refs = array(
                                    "referenceID_1" => $main["referenceID_1"],
                                    "referenceID_2" => $main["referenceID_2"],
                                    "referenceID_3" => $main["referenceID_3"],
                                    "referenceID_4" => $main["referenceID_4"],
                                );
                                $main["references_ids"] = implode(",", $refs);

                                break;
                        }

                        $postproc = array(
                            "master" => array(),
                            "detail" => array(
                                array(
                                    "comName" => "RekeningPembantuRaw",
                                    "loop" => array(
                                        "4010" => "sub_nett1",//rekening pembelian untuk keperluan lap
                                    ),
                                    "static" => array(
                                        "cabang_id" => "cabangID",
                                        "cabang_nama" => "cabangName",
                                        "extern_id" => ".4010",//lokal ,non lokal
                                        "extern_nama" => ".penjualan",
                                        "extern2_id" => ".4010010",//lihat coa untuk urutannya
                                        "extern2_nama" => ".lokal",
//                            "extern3_id" => ".0",
//                            "extern3_nama" => "machine_id",//diisi machinid
//                            "extern4_id" => ".0",
//                            "extern4_nama" => ".0",
                                        "jenis" => "tr__jenis",
                                        "transaksi_id" => "tr__id",
                                        "transaksi_no" => "tr__nomer",
                                        "produk_id" => "id",
                                        "produk_nama" => "nama",
                                        "produk_kode" => "produk_kode",
                                        "produk_jenis" => "jenis",
                                        "barcode" => "barcode",
                                        "jml" => "jml",
                                        "harga" => "nett1",// harga dpp
                                        "hpp" => "hpp",// hpp produk
                                        "harga_include_ppn" => "harga_include_ppn",// harga include ppn
                                        "sub_harga" => "sub_nett1",// harga dpp
                                        "sub_hpp" => "sub_hpp",// hpp produk
                                        "sub_harga_include_ppn" => "sub_harga_include_ppn",// harga include ppn
                                        "oleh_id" => "tr__oleh_id",
                                        "oleh_nama" => "tr__oleh_nama",
                                        "pihak_id" => "pihakID",// konsumen
                                        "pihak_nama" => "pihakName", // konsumen
                                        "oleh_top_id" => "oleh_top_id",
                                        "oleh_top_nama" => "oleh_top_nama",
                                        "satuan_id" => "satuan_id",
                                        "satuan_nama" => "satuan_nama",
                                        "rugilaba" => "rugilaba",
                                        "master_id" => "tr__id_master",
//                                        "diskon" => "discNilai",
//                                        "diskon_persen" => "discPersen",
                                        "diskon" => "disc",
                                        "diskon_persen" => "disc_percent",
                                        "sub_diskon" => "sub_disc",
                                        "sub_diskon_persen" => "sub_disc_percent",
                                        //----------------
                                        "outdoor_id" => "outdoor_id",
                                        "outdoor_nama" => "outdoor_nama",
                                        "outdoor_barcode" => "outdoor_barcode",
                                        "outdoor_sku" => "outdoor_sku",
                                        "indoor_id_1" => "indoor_id_1",
                                        "indoor_nama_1" => "indoor_nama_1",
                                        "indoor_barcode_1" => "indoor_barcode_1",
                                        "indoor_sku_1" => "indoor_sku_1",
                                        "indoor_id_2" => "indoor_id_2",
                                        "indoor_nama_2" => "indoor_nama_2",
                                        "indoor_barcode_2" => "indoor_barcode_2",
                                        "indoor_sku_2" => "indoor_sku_2",
                                        "indoor_id_3" => "indoor_id_3",
                                        "indoor_nama_3" => "indoor_nama_3",
                                        "indoor_barcode_3" => "indoor_barcode_3",
                                        "indoor_sku_3" => "indoor_sku_3",
                                        "indoor_id_4" => "indoor_id_4",
                                        "indoor_nama_4" => "indoor_nama_4",
                                        "indoor_barcode_4" => "indoor_barcode_4",
                                        "indoor_sku_4" => "indoor_sku_4",
                                        "kategori_id" => "kategori_id",
                                        "kategori_nama" => "kategori_nama",
                                        "produk_part_id_1" => "part_id_1",
                                        "produk_part_nama_1" => "part_nama_1",
                                        "produk_part_barcode_1" => "part_barcode_1",
                                        "produk_part_id_2" => "part_id_2",
                                        "produk_part_nama_2" => "part_nama_2",
                                        "produk_part_barcode_2" => "part_barcode_2",
                                        "heater_id" => "heater_id",
                                        "heater_nama" => "heater_nama",
                                        "heater_barcode" => "heater_barcode",
                                        //----------------
                                        "sales_admin_id" => "main__sellerID",
                                        "sales_admin_nama" => "main__sellerName",
                                        "salesman_id" => "main__salesmanDetails",
                                        "salesman_nama" => "main__salesmanDetails__nama",
                                        "gudang_id_kirim" => "main__gudangStatusDetails",
                                        "gudang_nama_kirim" => "main__gudangStatusDetails__nama",
                                        "delivery_id" => "main__shippingMethod",
                                        "delivery_nama" => "main__shippingMethod__name",
                                        "pengirim_id" => "main__pengirimID",
                                        "pengirim_nama" => "main__pengirimName",
                                        "pembayaran_nama" => "main__paymentMethod",
                                        //----------------
                                        "transaksi_id_1" => "tr__transaksi_id_1",
                                        "transaksi_no_1" => "tr__transaksi_no_1",
                                        "transaksi_id_2" => "tr__transaksi_id_2",
                                        "transaksi_no_2" => "tr__transaksi_no_2",
                                        "transaksi_id_3" => "tr__transaksi_id_3",
                                        "transaksi_no_3" => "tr__transaksi_no_3",
                                        "transaksi_id_4" => "tr__transaksi_id_4",
                                        "transaksi_no_4" => "tr__transaksi_no_4",
                                        "transaksi_id_5" => "tr__transaksi_id_5",
                                        "transaksi_no_5" => "tr__transaksi_no_5",
                                        //----------------
                                        "transaksi_nilai" => "new_net3",
                                        "ppn_nilai" => "ppn_nilai",
                                        "sub_ppn_nilai" => "sub_ppn_nilai",
                                        //----------------
//                                        "kirim_metode_id" => "kirim_metode_id",
//                                        "kirim_metode_nama" => "kirim_metode_nama",
                                        "references_data" => "main__references_ids",
                                    ),
                                    "srcGateName" => "items",
                                    "srcRawGateName" => "items",
                                ),

                            ),
                        );


                        break;
                    case "9912":
                        if ($referenceJenisTr == "5822spd") {
                            $refs = array(
                                "referenceID_1" => $trTmp[0]->reference_id,
//                                "referenceID_2" => $trTmp[0]->id,
                            );
                            $main["references_ids"] = implode(",", $refs);
                            $postproc = array(
                                "master" => array(),
                                "detail" => array(
                                    array(
                                        "comName" => "RekeningPembantuRaw",
                                        "loop" => array(
                                            "4010" => "-sub_nett1",//rekening pembelian untuk keperluan lap
                                        ),
                                        "static" => array(
                                            "cabang_id" => "cabangID",
                                            "cabang_nama" => "cabangName",
                                            "extern_id" => ".4010",//lokal ,non lokal
                                            "extern_nama" => ".penjualan",
                                            "extern2_id" => ".4010010",//lihat coa untuk urutannya
                                            "extern2_nama" => ".lokal",
//                            "extern3_id" => ".0",
//                            "extern3_nama" => "machine_id",//diisi machinid
//                            "extern4_id" => ".0",
//                            "extern4_nama" => ".0",
                                            "jenis" => "tr__jenis",
                                            "transaksi_id" => "tr__id",
                                            "transaksi_no" => "tr__nomer",
                                            "produk_id" => "id",
                                            "produk_nama" => "nama",
                                            "produk_kode" => "produk_kode",
                                            "produk_jenis" => "jenis",
                                            "barcode" => "barcode",
                                            "jml" => "-jml",
                                            "harga" => "nett1",// harga dpp
                                            "hpp" => "hpp",// hpp produk
                                            "harga_include_ppn" => "harga_include_ppn",// harga include ppn
                                            "sub_harga" => "sub_nett1",// harga dpp
                                            "sub_hpp" => "sub_hpp",// hpp produk
                                            "sub_harga_include_ppn" => "sub_harga_include_ppn",// harga include ppn
                                            "oleh_id" => "tr__oleh_id",
                                            "oleh_nama" => "tr__oleh_nama",
                                            "pihak_id" => "pihakID",// konsumen
                                            "pihak_nama" => "pihakName", // konsumen
                                            "oleh_top_id" => "oleh_top_id",
                                            "oleh_top_nama" => "oleh_top_nama",
                                            "satuan_id" => "satuan_id",
                                            "satuan_nama" => "satuan_nama",
                                            "rugilaba" => "rugilaba",
                                            "master_id" => "tr__id_master",
//                                        "diskon" => "discNilai",
//                                        "diskon_persen" => "discPersen",
                                            "diskon" => "disc",
                                            "diskon_persen" => "disc_percent",
                                            "sub_diskon" => "sub_disc",
                                            "sub_diskon_persen" => "sub_disc_percent",
                                            //----------------
                                            "outdoor_id" => "outdoor_id",
                                            "outdoor_nama" => "outdoor_nama",
                                            "outdoor_barcode" => "outdoor_barcode",
                                            "outdoor_sku" => "outdoor_sku",
                                            "indoor_id_1" => "indoor_id_1",
                                            "indoor_nama_1" => "indoor_nama_1",
                                            "indoor_barcode_1" => "indoor_barcode_1",
                                            "indoor_sku_1" => "indoor_sku_1",
                                            "indoor_id_2" => "indoor_id_2",
                                            "indoor_nama_2" => "indoor_nama_2",
                                            "indoor_barcode_2" => "indoor_barcode_2",
                                            "indoor_sku_2" => "indoor_sku_2",
                                            "indoor_id_3" => "indoor_id_3",
                                            "indoor_nama_3" => "indoor_nama_3",
                                            "indoor_barcode_3" => "indoor_barcode_3",
                                            "indoor_sku_3" => "indoor_sku_3",
                                            "indoor_id_4" => "indoor_id_4",
                                            "indoor_nama_4" => "indoor_nama_4",
                                            "indoor_barcode_4" => "indoor_barcode_4",
                                            "indoor_sku_4" => "indoor_sku_4",
                                            "kategori_id" => "kategori_id",
                                            "kategori_nama" => "kategori_nama",
                                            "produk_part_id_1" => "part_id_1",
                                            "produk_part_nama_1" => "part_nama_1",
                                            "produk_part_barcode_1" => "part_barcode_1",
                                            "produk_part_id_2" => "part_id_2",
                                            "produk_part_nama_2" => "part_nama_2",
                                            "produk_part_barcode_2" => "part_barcode_2",
                                            "heater_id" => "heater_id",
                                            "heater_nama" => "heater_nama",
                                            "heater_barcode" => "heater_barcode",
                                            //----------------
                                            "sales_admin_id" => "main__sellerID",
                                            "sales_admin_nama" => "main__sellerName",
                                            "salesman_id" => "main__salesmanDetails",
                                            "salesman_nama" => "main__salesmanDetails__nama",
                                            "gudang_id_kirim" => "main__gudangStatusDetails",
                                            "gudang_nama_kirim" => "main__gudangStatusDetails__nama",
                                            "delivery_id" => "main__shippingMethod",
                                            "delivery_nama" => "main__shippingMethod__name",
                                            "pengirim_id" => "main__pengirimID",
                                            "pengirim_nama" => "main__pengirimName",
                                            "pembayaran_nama" => "main__paymentMethod",
                                            //----------------
                                            "transaksi_id_1" => "tr__transaksi_id_1",
                                            "transaksi_no_1" => "tr__transaksi_no_1",
                                            "transaksi_id_2" => "tr__transaksi_id_2",
                                            "transaksi_no_2" => "tr__transaksi_no_2",
                                            "transaksi_id_3" => "tr__transaksi_id_3",
                                            "transaksi_no_3" => "tr__transaksi_no_3",
                                            "transaksi_id_4" => "tr__transaksi_id_4",
                                            "transaksi_no_4" => "tr__transaksi_no_4",
                                            "transaksi_id_5" => "tr__transaksi_id_5",
                                            "transaksi_no_5" => "tr__transaksi_no_5",
                                            //----------------
                                            "transaksi_nilai" => "new_net3",
                                            "ppn_nilai" => "ppn_nilai",
                                            "sub_ppn_nilai" => "sub_ppn_nilai",
                                            //----------------
//                                        "kirim_metode_id" => "kirim_metode_id",
//                                        "kirim_metode_nama" => "kirim_metode_nama",
                                            "reference_data" => "main__references_ids",
                                        ),
                                        "srcGateName" => "items",
                                        "srcRawGateName" => "items",
                                    ),
                                ),
                            );
                        }
                        else {
                            $postproc = array(
                                "master" => array(),
                                "detail" => array(),
                            );
                        }
                        break;
                    default:
                        mati_disini("processor belum di setting...");
                        break;
                }

                if (sizeof($items) > 0) {
                    foreach ($items as $pID => $iSpec) {
//                        "ppn_nilai" => "ppn_nilai",
//                        "sub_ppn_nilai" => "sub_ppn_nilai",
                        $iSpec["ppn_nilai"] = ($iSpec["nett1"] * 0.11);
                        $iSpec["sub_ppn_nilai"] = ($iSpec["sub_nett1"] * 0.11);
                        foreach ($main as $m_key => $m_val) {
                            $new_m_key = "main__" . $m_key;
                            $iSpec[$new_m_key] = $m_val;
                        }
                        foreach ($trData as $tr_key => $tr_val) {
                            $new_tr_key = "tr__" . $tr_key;
                            $iSpec[$new_tr_key] = $tr_val;
                        }
//                        arrPrintKuning($iSpec);
                        $items[$pID] = $iSpec;
                    }
                }

//                arrPrintCyan($items);
//                arrPrintHitam($postproc);

                //region postproc
                cekMerah("mulai postproc ::::::" . __LINE__);
                $iterator = array();
                $iterator = $postproc["detail"];
                if (sizeof($iterator) > 0) {
                    $comsLocation = "Coms";
                    $comsPrefix = "Com";
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $tmpOutParams[$cCtr] = array();
                        $gg = 0;
                        foreach ($items as $id => $dSpec) {
                            // $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $items, $comName);
                            }

                            $mdlName = "$comsPrefix" . ucfirst($comName);
                            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                $filterNeeded = true;
                            }
                            else {
                                $filterNeeded = false;
                            }
                            // cekHere("sub-component: [$comsLocation] $comName, initializing values <br>");

                            $subParams = array();

                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $dSpec[$key], $key);
                                    }

                                    $realValue = makeValue($value, $dSpec, $dSpec, 0);
                                    // cekMErah("$key =>".$realValue);
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
                                    $realValue = makeValue($value, $dSpec, $dSpec, 0);
                                    $subParams['static'][$key] = $realValue;
                                }
                                if (!isset($subParams['static']["transaksi_id"])) {
                                    $subParams['static']["transaksi_id"] = 0000;
                                }
                                if (!isset($subParams['static']["transaksi_no"])) {
                                    $subParams['static']["transaksi_no"] = 0000;
                                }

                                $subParams['static']["fulldate"] = $fulldate;
                                $subParams['static']["dtime"] = $dtime;
                                $subParams['static']["dtime_2"] = date("Y-m-d H:i");
                                $subParams['static']["keterangan"] = $jenisTrName . " oleh " . $oleh_nama;
                            }

                            if (sizeof($subParams) > 0) {
                                //                                cekhitam("subparam ada isinya");
                                if ($filterNeeded) {
                                    if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                }
                            }
                            else {
                                cekhitam("subparam TIDAK ada isinya");
                            }
                        }

                        $componentGate['detail'][$cCtr] = $subParams;
                    }

                    foreach ($iterator as $cCtr => $tComSpec) {
                        // $srcGateName = $tComSpec['srcGateName'];
                        foreach ($items as $id => $dSpec) {
                            // $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $items[$id][$comName], $comName);
                            }
                        }
                        cekHere("sub component: [$comsLocation] $comName, sending values " . __LINE__ . "<br>");

                        $mdlName = "$comsPrefix" . ucfirst($comName);
                        $this->load->model("$comsLocation/" . $mdlName);
                        $m = new $mdlName();
                        //===filter value nol, jika harus difilter

                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        if ($tobeExecuted) {
                            //----- kiriman gerbang
                            if (method_exists($m, "setTableInMaster")) {
                                $m->setTableInMaster($tableIn_master);
                            }
                            if (method_exists($m, "setDetail")) {
                                $m->setDetail($items);
                            }
                            if (method_exists($m, "setJenisTr")) {
                                $m->setJenisTr($jenisTr);
                            }
                            //----- kiriman gerbang
                            $m->pair($tmpOutParams[$cCtr]) or matiHere("Tidak berhasil memasang  values pada komponen: $comName/" . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or matiHere("Gagal saat berusaha  exec values pada komponen: $comName/" . "/" . __FUNCTION__ . "/" . __LINE__);
//                            cekBiru($this->db->last_query());
                        }
                        else {
//                            cekMerah("$comName tidak eksekusi");
                        }

                    }
                }
                else {
                    cekKuning("sub post-components is not set");
                }
                //endregion


                $tr = New MdlTransaksi();
                $tr->setFilters(array());
                $where = array(
                    "id" => $transaksi_id,
                );
                $data = array(
                    "r_maju" => 1,
                );
                $tr->updateData($where, $data);
                showLast_query("orange");


            }
            else {
                cekMerah("<h3>HABIS...</h3>");
            }


        }
        $end = microtime(true);
        $selesai = $end - $start;


//        matiHEre("complitt [selesai dalam $selesai]");

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>SELESAI... [$selesai]</h3>");

    }

    public function run_bukuPembantuKas()
    {
        if (isset($_GET["r"]) && ($_GET["r"] > 0)) {
            $refresh = $_GET["r"];
            header("refresh:$refresh");
        }


        $this->db->trans_start();
        $start = microtime(true);
        $force = isset($_GET["force"]) ? $_GET["force"] : "none";
        $cekjam = date("H");
        $this->load->helper("he_angka");
        $arrJenisTr = array(
            "749",
            "4464",
            "4467",
            "489",
            "464",
        );
        $main = array();
        $items = array();
        $tableIn_master = array();
        $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();


        $timeTOexec = true;
        if ($timeTOexec) {
            $tr = New MdlTransaksi();
            $tr->addFilter("jenis in ('" . implode("','", $arrJenisTr) . "')");
            $tr->addFilter("r_maju='0'");
//            $tr->addFilter("id<=3000");
            $this->db->order_by("id", "asc");
            $this->db->limit("1");
            $trTmp = $tr->lookupAll()->result();
            if (sizeof($trTmp) > 0) {
                $transaksi_id = $trTmp[0]->id;
                $fulldate = $trTmp[0]->fulldate;
                $dtime = $trTmp[0]->dtime;
                $jenisTrName = $trTmp[0]->jenis_label;
                $oleh_id = $trTmp[0]->oleh_id;
                $oleh_nama = $trTmp[0]->oleh_nama;
                $jenisTr = $trTmp[0]->jenis_master;
                $idsHis = ($trTmp[0]->ids_his != null) ? blobDecode($trTmp[0]->ids_his) : array();
                if (sizeof($idsHis) > 0) {
                    foreach ($idsHis as $step_his => $data_his) {
                        $keyss_ids = "transaksi_id_" . $step_his;
                        $keyss_nomers = "transaksi_no_" . $step_his;
                        $trTmp[0]->$keyss_ids = $data_his["trID"];
                        $trTmp[0]->$keyss_nomers = $data_his["nomer"];
                    }
                }

                $trData = (array)$trTmp[0];

                // region membaca registry items dan main
                $trReg = New MdlTransaksi();
                $trReg->setFilters(array());
                $trReg->setJointSelectFields("transaksi_id, main, items, tableIn_master");
                $trReg->addFilter("transaksi_id='$transaksi_id'");
                $trRegTmp = $trReg->lookupDataRegistries()->result();
                foreach ($trRegTmp as $regs) {
                    foreach ($regs as $key => $val) {
                        if ($key != "transaksi_id") {
                            switch ($key) {
                                case "main":
                                    $main = blobDecode($val);
                                    break;
                                case "items":
                                    $items = blobDecode($val);
                                    break;
                                case "tableIn_master":
                                    $tableIn_master = blobDecode($val);
                                    break;
                            }
                        }
                    }
                }
                // endregion

                if (sizeof($main) > 0) {
                    foreach ($main as $key => $val) {
                        foreach ($trData as $tr_key => $tr_val) {
                            $new_tr_key = "tr__" . $tr_key;
                            $main[$new_tr_key] = $tr_val;
                        }
                    }
                }
//                arrPrintWebs($main);

                $srcField = array(
                    "cabangID" => "cabang_id",
                    "placeID" => "cabang_id",
                    "cabang_id" => "cabang_id",
                    "toko_id" => "toko_id",
                    "machine_id" => "machine_id",
                    "jenis" => "jenis",
                    "jenisTr" => "jenis",
                    "toko_nama" => "toko_nama",
                    "pihak_id" => "pihak_id",
                    "pihak_nama" => "pihak_nama",
                    "oleh_id" => "oleh_id",
                    "oleh_nama" => "oleh_nama",
                    "dtime" => "dtime",
//            "kas" => "sisa",
//            "nomer" => "nomer",
                );
                $selectedItemFields = array(
                    "id" => "id",
                    "toko_id" => "toko_id",
                    "cabang_id" => "cabang_id",
                    "produk_id" => "produk_id",
                    "produk_nama" => "produk_nama",
                    "valid_qty" => "valid_qty",
                    "produk_ord_jml" => "produk_ord_jml",
                    "produk_ord_jml_return" => "produk_ord_jml_return",
                    "sisa" => "sisa",
                    "produk_ord_hrg" => "produk_ord_hrg",
                    "produk_ord_hpp" => "produk_ord_hpp",
                    "dtime" => "dtime",
                    "fulldate" => "dtime",
                    "jml" => "sisa",
                    "qty" => "sisa",
                    "satuan" => "satuan",

                );
//mati_disini("::: $jenisTr :::");
                //region COMPONENT JURNAL

//                $preproc = array(
////                "master" => array(),
////                "detail" => array(
////                    array(
////                        "comName" => "FifoAverage",
////                        "loop" => array(),
////                        "static" => array(
////                            "cabang_id" => "cabang_id",
////                            "extern_id" => "produk_id",
////                            "extern_nama" => "produk_nama",
////                            "produk_qty" => "jml_dipakai",
////                            "gudang_id" => "gudang_id",
////                            "toko_id" => "toko_id",
////                            "harga" => "harga",
////                            "harga_jual" => "harga",
////                            "satuan_id" => "satuan_id",
////                            "satuan_nama" => "satuan",
////                        ),
////                        "resultParams" => array(
////                            "rsltItems" => array(
////                                // "qty" => "jml",
////                                "hpp" => "hpp",
////                                "produk_id" => "produk_id",
////                                "produk_nama" => "nama",
////                                "harga_jual" => "harga_jual",
////                                "satuan_id" => "satuan_id",
////                                "satuan_nama" => "satuan",
////                            ),
////                        ),
////                        "srcGateName" => "items9_sum",
////                        "srcRawGateName" => "items9_sum",
////                    ),
////                ),
//                );
//                $component = array(
////                "master" => array(
////                    //catatan jurnal penjualan tidak jadi dijalanknan hanya nulis raw nya saja
////
////                    // /*
////                    //  * untuk penjualan di transaksi settlement ,disini hanya jalan raw pembantu penjualan saja.
////                    //  */
////                    // array(
////                    //     "comName" => "Jurnal",
////                    //     "loop" => array(
////                    //         "1010010" => "kas",//kas setara kas
////                    //         "4" => "penjualan",//penjualan
////                    //         "2030090" => "ppn_gunggungan",//ppn gunggunan
////                    //     ),
////                    //     "static" => array(
////                    //         "cabang_id" => "cabang_id",
////                    //         "jenis" => "jenisTr",
////                    //         // "transaksi_no" => "nomer",
////                    //         "toko_id" => "toko_id",
////                    //         "toko_nama" => "toko_nama",
////                    //         "transaksi_no" => "nomer",
////                    //         "transaksi_id" => "transaksi_id",
////                    //     ),
////                    //     "srcGateName" => "main",
////                    //     "srcRawGateName" => "main",
////                    // ),
////                    // array(
////                    //     "comName" => "Rekening",
////                    //     "loop" => array(
////                    //         "1010010" => "kas",//kas setara kas
////                    //         "4" => "penjualan",//penjualan
////                    //         "2030090" => "ppn_gunggungan",//ppn gunggunan
////                    //     ),
////                    //     "static" => array(
////                    //         "cabang_id" => "cabang_id",
////                    //         "jenis" => "jenisTr",
////                    //         // "transaksi_no" => "nomer",
////                    //         "toko_id" => "toko_id",
////                    //         "toko_nama" => "toko_nama",
////                    //         "transaksi_no" => "nomer",
////                    //         "transaksi_id" => "transaksi_id",
////                    //     ),
////                    //     "srcGateName" => "main",
////                    //     "srcRawGateName" => "main",
////                    // ),
////                    // //rekening pembantu kas/ setara kas
////                    // array(
////                    //     "comName" => "RekeningPembantuKasSetarakas",
////                    //     "loop" => array(
////                    //         "1010010" => "kas", // kas
////                    //     ),
////                    //     "static" => array(
////                    //         "toko_id" => "toko_id",
////                    //         "cabang_id" => "cabang_id",
////                    //         "extern_id" => ".1010010",
////                    //         "extern_nama" => ".kas setara kas",
////                    //         "jenis" => "jenisTr",
////                    //         "produk_id" => ".1010010010",
////                    //         "produk_nama" => ".kas",
////                    //         "produk_nilai" => "kas",
////                    //         "transaksi_no" => "nomer",
////                    //         "transaksi_id" => "transaksi_id",
////                    //     ),
////                    //     "srcGateName" => "main",
////                    //     "srcRawGateName" => "main",
////                    // ),
////                    // //rekening pembantu penjualan
////                    // array(
////                    //     "comName" => "RekeningPembantuPenjualan",
////                    //     "loop" => array(
////                    //         "4" => "penjualan",//penjualan
////                    //     ),
////                    //     "static" => array(
////                    //         "toko_id" => "toko_id",
////                    //         "cabang_id" => "cabang_id",
////                    //         "extern_id" => ".4010",
////                    //         "extern_nama" => ".lokal",
////                    //         "extern2_id" => ".0",//lihat coa untuk urutannya
////                    //         "extern2_nama" => ".0",
////                    //         "extern3_id" => ".0",
////                    //         "extern3_nama" => ".0",
////                    //         "extern4_id" => ".0",
////                    //         "extern4_nama" => ".0",
////                    //         "jenis" => "jenisTr",
////                    //         "produk_id" => ".4010010",
////                    //         "produk_nama" => ".lokal",
////                    //         "jml" => ".1",
////                    //         "harga" => "penjualan",
////                    //         "hpp" => "penjualan",
////                    //         "produk_nilai" => "penjualan",
////                    //         "oleh_id" => "oleh_id",
////                    //         "oleh_nama" => "oleh_nama",
////                    //         "pihak_id" => "pihak_id",
////                    //         "pihak_nama" => "pihak_nama",
////                    //         "oleh_top_id" => "oleh_top_id",
////                    //         "oleh_top_nama" => "oleh_top_nama",
////                    //         "transaksi_no" => "nomer",
////                    //         "transaksi_id" => "transaksi_id",
////                    //     ),
////                    //     "srcGateName" => "main",
////                    //     "srcRawGateName" => "main",
////                    // ),
////                    // //rekening pembantu kas
////                    // array(
////                    //     "comName" => "RekeningPembantuKas",
////                    //     "loop" => array(
////                    //         "1010010" => "kas",
////                    //     ),
////                    //     "static" => array(
////                    //         //                            "cabang_id" => "cabang_id",
////                    //         "cabang_id" => "cabang_id",
////                    //         "extern_id" => ".1010010010",
////                    //         "extern_nama" => ".kas",
////                    //         "produk_id" => "cash_account",
////                    //         "produk_nama" => "cash_account__nama",
////                    //         "produk_nilai" => "kas",
////                    //         "jenis" => "jenisTr",
////                    //         "toko_id" => "toko_id",
////                    //         "toko_nama" => "toko_nama",
////                    //         "oleh_id" => "oleh_id",
////                    //         "oleh_nama" => "oleh_nama",
////                    //         "transaksi_no" => "nomer",
////                    //         "transaksi_id" => "transaksi_id",
////                    //     ),
////                    //     "srcGateName" => "items7_sum",
////                    //     "srcRawGateName" => "items7_sum",
////                    // ),
////
////                    /*
////     * jurnal cabang point masuk
////     */
////                    array(
////                        "comName" => "Jurnal",
////                        "loop" => array(
////                            "4" => "penjualan_minus",//penjualan
////                            "2010050" => "diskon_penjualan",//hutang ke konsumen
////                            "1010030" => "-sub_hpp",//persediaan
////                            "5" => "sub_hpp",//hpp
////                            // "2030090" => "ppn_gunggungan",//ppn gunggunan
////                            // // hutang ke kosnumen
////                            // "2010050" => "hutang_ke_konsumen",
//////                             "1010070" => "piutang_kasir",//piutang kasir
////                            // "2010050"=>"",
////
////                        ),
////                        "static" => array(
////                            "cabang_id" => "cabang_id",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                            "transaksi_id" => "transaksi_id",
////                            "toko_id" => "toko_id",
////                            "toko_nama" => "toko_nama",
////
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "Rekening",
////                        "loop" => array(
////                            "4" => "penjualan_minus",//penjualan
////                            "2010050" => "diskon_penjualan",//hutang ke konsumen
////                            "1010030" => "-sub_hpp",//persediaan
////                            "5" => "sub_hpp",//hpp
////                            // "2030090" => "ppn_gunggungan",//ppn gunggunan
////                            // // hutang ke kosnumen
////                            // "2010050" => "hutang_ke_konsumen",
//////                             "1010070" => "piutang_kasir",//piutang kasir
////                            // "2010050"=>"",
////
////                        ),
////                        "static" => array(
////                            "cabang_id" => "cabang_id",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                            "transaksi_id" => "transaksi_id",
////                            "toko_id" => "toko_id",
////                            "toko_nama" => "toko_nama",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    //rekening pembantu hutang ke konsumen  lv 1
////                    array(
////                        "comName" => "RekeningPembantuHutangKeKonsumen",
////                        "loop" => array(
////                            "2010050" => "diskon_penjualan",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "cabang_id",
////                            "toko_id" => "toko_id",
////                            "extern_id" => ".2010050",
////                            "extern_nama" => ".hutang ke konsumen",
////                            "produk_id" => ".2010050030",
////                            "produk_nama" => ".point",
////                            // "produk_qty"  => ".1",
////                            "produk_nilai" => "diskon_penjualan",
////                            "jenis" => "jenisTr",
////                            "toko_nama" => "toko_nama",
////                            "oleh_id" => "oleh_id",
////                            "oleh_nama" => "oleh_nama",
////                            "transaksi_no" => "nomer",
////                            "transaksi_id" => "transaksi_id",
////
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    //rekening pembantu penjualan
////                    array(
////                        "comName" => "RekeningPembantuPenjualan",
////                        "loop" => array(
////                            "4" => "penjualan_minus",//penjualan
////                        ),
////                        "static" => array(
////                            "toko_id" => "toko_id",
////                            "cabang_id" => "cabang_id",
////                            "extern_id" => ".4010",
////                            "extern_nama" => ".lokal",
////                            "extern2_id" => ".0",//lihat coa untuk urutannya
////                            "extern2_nama" => ".0",
////                            "extern3_id" => ".0",
////                            "extern3_nama" => ".0",
////                            "extern4_id" => ".0",
////                            "extern4_nama" => ".0",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                            "produk_id" => ".4010030",
////                            "produk_nama" => ".point",
////                            "jml" => ".-1",
////                            "harga" => "hpp_point_satuan",
////                            "hpp" => "hpp_point_satuan",
////                            "produk_nilai" => "hpp_point_satuan",
////                            "oleh_id" => "oleh_id",
////                            "oleh_nama" => "oleh_nama",
////                            "pihak_id" => "customers_id",
////                            "pihak_nama" => "customers_nama",
////                            "oleh_top_id" => "oleh_id",
////                            "oleh_top_nama" => "oleh_nama",
////                            "transaksi_id" => "transaksi_id",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////
////                    /*
////                     * jurnal cabang point out ke pusat
////                     */
////                    array(
////                        "comName" => "Jurnal",
////                        "loop" => array(
////                            "2010050" => "penjualan_minus",//hutang ke konsumen
////                            "2040010" => "diskon_penjualan",//hutang ke pusat
////                        ),
////                        "static" => array(
////                            "toko_id" => "toko_id",
////                            "cabang_id" => "cabang_id",
////                            "gudang_id" => "gudang_id",
////                            "transaksi_no" => "nomer",
////                            "transaksi_id" => "transaksi_id",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////
////                    ),
////                    array(
////                        "comName" => "Rekening",
////                        "loop" => array(
////                            "2010050" => "penjualan_minus",//hutang ke konsumen
////                            "2040010" => "diskon_penjualan",//hutang ke pusat
////                        ),
////                        "static" => array(
////                            "toko_id" => "toko_id",
////                            "cabang_id" => "cabang_id",
////                            "gudang_id" => "gudang_id",
////                            "transaksi_no" => "nomer",
////                            "transaksi_id" => "transaksi_id",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    // //rekening pembantu hutang ke konsumen  out lv 1
////                    array(
////                        "comName" => "RekeningPembantuHutangKeKonsumen",
////                        "loop" => array(
////                            "2010050" => "penjualan_minus",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "cabang_id",
////                            "toko_id" => "toko_id",
////                            "extern_id" => ".2010050",
////                            "extern_nama" => ".hutang ke konsumen",
////                            "produk_id" => ".2010050030",
////                            "produk_nama" => ".point",
////                            "produk_nilai" => "penjualan_minus",
////                            "jenis" => "jenisTr",
////                            "toko_nama" => "toko_nama",
////                            "oleh_id" => "oleh_id",
////                            "oleh_nama" => "oleh_nama",
////                            "transaksi_no" => "nomer",
////                            "transaksi_id" => "transaksi_id",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "RekeningPembantuAntarcabang",
////                        "loop" => array(
////                            "2040010" => "diskon_penjualan",
////                        ),
////                        "static" => array(
////                            "toko_id" => "toko_id",
////                            "cabang_id" => "cabang_id",
////                            "cabang2_id" => "cabang2_id",
////                            "cabang2_nama" => "cabang2_nama",
////                            "extern_id" => ".2040010",
////                            "extern_nama" => ".hutang kepusat",
////                            "produk_id" => "cabang2_id",
////                            "produk_nama" => ".pusat",
////                            "oleh_id" => "oleh_id",
////                            "oleh_name" => "oleh_nama",
////                            "transaksi_no" => "nomer",
////                            "transaksi_id" => "transaksi_id",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////
////                    /*
////                     * jurnal pusat
////                     */
////                    array(
////                        "comName" => "Jurnal",
////                        "loop" => array(
////                            "2010050" => "diskon_penjualan",//hutang ke konsumen
////                            "1010060010" => "diskon_penjualan",//piutang cabang
////                        ),
////                        "static" => array(
////                            "cabang_id" => "cabang2_id",
////                            "gudang_id" => "gudang2_id",
////                            "toko_id" => "toko_id",
////                            "transaksi_no" => "nomer",
////                            "transaksi_id" => "transaksi_id",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "Rekening",
////                        "loop" => array(
////                            "2010050" => "diskon_penjualan",//hutang ke konsumen
////                            "1010060010" => "diskon_penjualan",//piutang cabang
////                        ),
////                        "static" => array(
////                            "cabang_id" => "cabang2_id",
////                            "gudang_id" => "gudang2_id",
////                            "toko_id" => "toko_id",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    //rekening pembantu hutang ke konsumen  lv 1 PUSAT
////                    array(
////                        "comName" => "RekeningPembantuHutangKeKonsumen",
////                        "loop" => array(
////                            "2010050" => "diskon_penjualan",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "cabang2_id",
////                            "toko_id" => "toko_id",
////                            "extern_id" => ".2010050",
////                            "extern_nama" => ".hutang ke konsumen",
////                            "produk_id" => ".2010050030",
////                            "produk_nama" => ".point",
////                            "produk_nilai" => "diskon_penjualan",
////                            "harga" => "harga",
////                            "hpp" => "hpp",
////                            "jenis" => "jenisTr",
////                            "toko_nama" => "toko_nama",
////                            "oleh_id" => "oleh_id",
////                            "oleh_nama" => "oleh_nama",
////                            "transaksi_no" => "nomer",
////                            "transaksi_id" => "transaksi_id",
////
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    //rekening pembantu piutang cabang
////                    array(
////                        "comName" => "RekeningPembantuAntarcabang",
////                        "loop" => array(
////                            "1010060010" => "diskon_penjualan",
////                        ),
////                        "static" => array(
////                            "toko_id" => "toko_id",
////                            "cabang_id" => "cabang2_id",
////                            "cabang2_id" => "cabang_id",
////                            "cabang2_nama" => "cabang_nama",
////                            "extern_id" => ".1010060010",
////                            "extern_nama" => ".piutang cabang",
////                            "produk_id" => "cabang_id",
////                            "produk_nama" => "cabang_nama",
////                            "transaksi_no" => "nomer",
////                            "transaksi_id" => "transaksi_id",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    // //raw pembantu kas setara kas
////                    // array(
////                    //     "comName" => "RekeningPembantuRawMain",
////                    //     "loop" => array(
////                    //         "1010010" => "kas",//rekening pembelian untuk keperluan lap
////                    //     ),
////                    //     /*
////                    //      * untuk gerbang coa nantinya dibuat relative ya
////                    //      */
////                    //     "static" => array(
////                    //         //                            "toko_id" => "tokoID",
////                    //         //                            "cabang_id" => "placeID",
////                    //         "toko_id" => "toko_id",
////                    //         "cabang_id" => "cabang_id",
////                    //         "extern_id" => ".1010010010",//lokal ,non lokal
////                    //         "extern_nama" => ".kas",
////                    //         "extern2_id" => ".0",//lihat coa untuk urutannya
////                    //         "extern2_nama" => ".0",
////                    //         "extern3_id" => ".0",
////                    //         "extern3_nama" => ".0",
////                    //         "extern4_id" => ".0",
////                    //         "extern4_nama" => ".0",
////                    //         "jenis" => "jenisTr",
////                    //         "transaksi_no" => "nomer",
////                    //         "produk_id" => "cash_account",
////                    //         "produk_nama" => "cash_account__nama",
////                    //         "jml" => ".1",
////                    //         "harga" => "kas",
////                    //         "hpp" => "kas",
////                    //         "oleh_id" => "oleh_id",
////                    //         "oleh_nama" => "oleh_nama",
////                    //         "pihak_id" => ".0",
////                    //         "pihak_nama" => ".0",
////                    //         "oleh_top_id" => "oleh_id",
////                    //         "oleh_top_nama" => "oleh_nama",
////                    //     ),
////                    //     "srcGateName" => "items7_sum",
////                    //     "srcRawGateName" => "items7_sum",
////                    // ),
////
////                    //rekening pembantu hutang ke konsumen lv2 in cabang
////                    array(
////                        "comName" => "RekeningPembantuCustomer",
////                        "loop" => array(
////                            "2010050" => "hpp_point",//rekening pembantu hutang konsumen
////                        ),
////                        "static" => array(
////                            "cabang_id" => "cabang_id",
////                            "extern_id" => ".2010050",//hutan ke konsumen
////                            "extern_nama" => ".hutang ke konsumen",
////                            "extern2_id" => ".2010050030",//point
////                            "extern2_nama" => ".point",
////                            "produk_id" => "customers_id",//customer id
////                            "produk_nama" => "customers_nama",
////
////                            "qty" => "qty_point",
////                            "produk_qty" => "qty_point",
////                            "produk_nilai" => "hpp_point_satuan",
////                            "harga" => "harga",
////                            "hpp" => "hpp",
////                            "jenis" => "jenisTr",
////                            "toko_id" => "toko_id",
////                            "toko_nama" => "toko_nama",
////                            "oleh_id" => "oleh_id",
////                            "oleh_nama" => "oleh_nama",
////                            "transaksi_no" => "nomer",
////                            "transaksi_id" => "transaksi_id",
////                            // "transaksi_id" => "transaksi_id",
////                        ),
////                        "srcGateName" => "items8_sum",
////                        "srcRawGateName" => "items8_sum",
////                    ),
////                    //rekening pembantu hutang ke konsumen cabang lv2 out ke pusat
////                    array(
////                        "comName" => "RekeningPembantuCustomer",
////                        "loop" => array(
////                            "2010050" => "hpp_point_minus",//rekening pembantu hutang konsumen
////                        ),
////                        "static" => array(
////                            "cabang_id" => "cabang_id",
////                            "extern_id" => ".2010050",//hutan ke konsumen
////                            "extern_nama" => ".hutang ke konsumen",
////                            "extern2_id" => ".2010050030",//point
////                            "extern2_nama" => ".point",
////                            "produk_id" => "customers_id",//customer id
////                            "produk_nama" => "customers_nama",
////                            "produk_qty" => "qty_penjualan_minus",
////                            "qty" => "qty_penjualan_minus",
////                            "produk_nilai" => "hpp_point_satuan",
////                            "jenis" => "jenisTr",
////                            "toko_id" => "toko_id",
////                            "toko_nama" => "toko_nama",
////                            "oleh_id" => "oleh_id",
////                            "oleh_nama" => "oleh_nama",
////                            "transaksi_no" => "nomer",
////                            "transaksi_id" => "transaksi_id",
////                            "harga" => "harga",
////                            "hpp" => "hpp",
////                            // "transaksi_id" => "transaksi_id",
////                        ),
////                        "srcGateName" => "items8_sum",
////                        "srcRawGateName" => "items8_sum",
////                    ),
////                    //raw pembantu penjualan(diskon penjulan) cabang aka point
////                    array(
////                        "comName" => "RekeningPembantuRawMain",
////                        "loop" => array(
////                            "4" => "penjualan_minus",//rekening pembelian untuk keperluan lap
////                        ),
////                        /*
////                         * untuk gerbang coa nantinya dibuat relative ya
////                         */
////                        "static" => array(
////                            "toko_id" => "toko_id",
////                            "cabang_id" => "cabang_id",
////                            "extern_id" => ".4010",//lokal ,non lokal
////                            "extern_nama" => ".lokal",
////                            "extern2_id" => ".4010030",//lihat coa untuk urutannya
////                            "extern2_nama" => ".diskon penjualan",
////                            "extern3_id" => ".0",
////                            "extern3_nama" => ".0",
////                            "extern4_id" => ".0",
////                            "extern4_nama" => ".0",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                            "produk_id" => "customers_id",//customer id
////                            "produk_nama" => "customers_nama",
////                            "jml" => ".1",
////
////                            "harga" => "hpp_point_satuan",
////                            "hpp" => "hpp_point_satuan",
////                            "oleh_id" => "oleh_id",
////                            "oleh_nama" => "oleh_nama",
////                            "pihak_id" => "customer_id",
////                            "pihak_nama" => "customer_nama",
////                            "oleh_top_id" => "oleh_id",
////                            "oleh_top_nama" => "oleh_nama",
////                            "satuan_id" => "satuan_id",
////                            "satuan_nama" => "satuan",
////
////                            "transaksi_id" => "transaksi_id",
////                        ),
////                        "srcGateName" => "items8_sum",
////                        "srcRawGateName" => "items8_sum",
////                    ),
////
////                    //rekening pembantu hutang ke konsumen lv2 in PUSAT
////                    array(
////                        "comName" => "RekeningPembantuCustomer",
////                        "loop" => array(
////                            "2010050" => "hpp_point",//rekening pembantu hutang konsumen
////                        ),
////                        "static" => array(
////                            "cabang_id" => "cabang2_id",
////                            "extern_id" => ".2010050",//hutan ke konsumen
////                            "extern_nama" => ".hutang ke konsumen",
////                            "extern2_id" => ".2010050030",//point
////                            "extern2_nama" => ".point",
////                            "produk_id" => "customers_id",//customer id
////                            "produk_nama" => "customers_nama",
////                            // "produk_nilai" => "subtotal",
////                            "produk_qty" => "qty_point",
////                            "qty" => "qty_point",
////                            "produk_nilai" => "hpp_point_satuan",
////                            "jenis" => "jenisTr",
////                            "toko_id" => "toko_id",
////                            "toko_nama" => "toko_nama",
////                            "oleh_id" => "oleh_id",
////                            "oleh_nama" => "oleh_nama",
////                            "transaksi_no" => "nomer",
////                            "transaksi_id" => "transaksi_id",
////                            // "transaksi_id" => "transaksi_id",
////                        ),
////                        "srcGateName" => "items8_sum",
////                        "srcRawGateName" => "items8_sum",
////                    ),
////
////                    //rekening pembantu persediaan
////                    array(
////                        "comName" => "RekeningPembantuPersediaan",
////                        "loop" => array(
////                            "1010030" => "-sub_hpp", // persediaan
////                        ),
////                        "static" => array(
////                            "toko_id" => "toko_id",
////                            "cabang_id" => "cabang_id",
////                            "gudang_id" => "gudang_id",
////                            "extern_id" => ".1010030030",//produk
////                            "extern_nama" => ".persediaan produk",
////                            "extern2_id" => ".0",//lihat coa untuk urutannya
////                            "extern2_nama" => ".0",
////                            "extern3_id" => ".0",
////                            "extern3_nama" => ".0",
////                            "extern4_id" => ".0",
////                            "extern4_nama" => ".0",
////                            "jenis" => "jenisTr",
////                            "transaksi_id" => "transaksi_id",
////                            "transaksi_no" => "transaksi_no",
////                            "produk_id" => ".1010030030",
////                            "produk_nama" => ".persediaan produk",
////                            "jml" => ".1",
////                            "harga" => "hpp",
////                            "hpp" => "hpp",
////                            "produk_nilai" => "hpp",
////                            "oleh_id" => "oleh_id",
////                            "oleh_nama" => "oleh_nama",
////                            "pihak_id" => "oleh_id",
////                            "pihak_nama" => "oleh_nama",
////                            "oleh_top_id" => "oleh_id",
////                            "oleh_top_nama" => "oleh_nama",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "RekeningPembantuHpp",
////                        "loop" => array(
////                            "5" => "sub_hpp", // hpp
////                        ),
////                        "static" => array(
////                            "toko_id" => "toko_id",
////                            "cabang_id" => "cabang_id",
////                            "gudang_id" => "gudang_id",
////                            "extern_id" => ".5010",//
////                            "extern_nama" => ".harga pokok penjualan",
////                            "extern2_id" => ".0",//lihat coa untuk urutannya
////                            "extern2_nama" => ".0",
////                            "extern3_id" => ".0",
////                            "extern3_nama" => ".0",
////                            "extern4_id" => ".0",
////                            "extern4_nama" => ".0",
////                            "jenis" => "jenisTr",
////                            "produk_id" => ".5010010",
////                            "produk_nama" => ".lokal",
////                            "jml" => ".1",
////                            "harga" => "hpp",
////                            "hpp" => "hpp",
////                            "produk_nilai" => "hpp",
////                            "oleh_id" => "oleh_id",
////                            "oleh_nama" => "oleh_nama",
////                            "pihak_id" => "oleh_id",
////                            "pihak_nama" => "oleh_nama",
////                            "oleh_top_id" => "oleh_id",
////                            "oleh_top_nama" => "oleh_nama",
////                            "transaksi_id" => "transaksi_id",
////                            "transaksi_no" => "transaksi_no",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                ),
////                "detail" => array(
////                    //raw pembantu penjualan numpang langsung nulis raw
////                    array(
////                        "comName" => "RekeningPembantuProduk",
////                        "loop" => array(
////                            "1010030" => "-sub_hpp",
////                        ),
////                        "static" => array(
////                            "toko_id" => "toko_id",
////                            "cabang_id" => "cabang_id",
////                            "extern_id" => ".1010030030",
////                            "extern_nama" => ".persediaan produk",
////                            "produk_qty" => "-jml_dipakai",
////                            "qty_ditunda" => "jml_ditunda",
////                            //                            "produk_nilai" => "harga",//harga jual asli
////                            "produk_nilai" => "hpp",//hpp produk yang terjual
////                            "gudang_id" => "gudang_id",
////                            "jenis" => ".582",
////                            "extern2_id" => ".0",//lihat coa untuk urutannya
////                            "extern2_nama" => ".0",
////                            "extern3_id" => ".0",
////                            "extern3_nama" => ".0",
////                            "extern4_id" => ".0",
////                            "extern4_nama" => ".0",
////                            "produk_id" => "produk_id",
////                            "produk_nama" => "produk_nama",
////                            "jml" => "jml_dipakai",
////                            //                            "harga" => "harga_avg",//harga rata rata karena penjumlahan
////                            "harga" => "hpp",//harga rata rata karena penjumlahan
////                            "harga_jual" => "harga_jual",//harga rata rata karena penjumlahan
////                            "hpp" => "hpp",
////                            "oleh_id" => "oleh_id",
////                            "oleh_nama" => "oleh_nama",
////                            "pihak_id" => "oleh_id",
////                            "pihak_nama" => "oleh_nama",
////                            "oleh_top_id" => "oleh_id",
////                            "oleh_top_nama" => "oleh_nama",
////                            "satuan_id" => "satuan_id",
////                            "satuan_nama" => "satuan",
////                            "rugilaba" => "harga_jual-hpp",
////                            "transaksi_id" => "transaksi_id",
////                            "transaksi_no" => "transaksi_no",
////                        ),
////                        "srcGateName" => "rsltItems",
////                        "srcRawGateName" => "rsltItems",
////                    ),
////
////                    array(
////                        "comName" => "RekeningPembantuRaw",
////                        "loop" => array(
////                            "5" => "sub_hpp",//rekening pembelian untuk keperluan lap
////                        ),
////                        /*
////                         * untuk gerbang coa nantinya dibuat relative ya
////                         */
////                        "static" => array(
////                            "toko_id" => "toko_id",
////                            "cabang_id" => "cabang_id",
////                            "extern_id" => ".5010",//hpp penjualan
////                            "extern_nama" => ".penjualan",
////                            "extern2_id" => ".5010010",//lihat coa untuk urutannya
////                            "extern2_nama" => ".penjualan",
////                            "extern3_id" => ".0",
////                            "extern3_nama" => ".0",
////                            "extern4_id" => ".0",
////                            "extern4_nama" => ".0",
////                            "jenis" => ".758",
////                            "produk_id" => "produk_id",
////                            "produk_nama" => "produk_nama",
////                            "jml" => "qty",
////                            "harga" => "harga",
////                            "hpp" => "hpp",
////                            "oleh_id" => "oleh_id",
////                            "oleh_nama" => "oleh_nama",
////                            "pihak_id" => "oleh_id",
////                            "pihak_nama" => "oleh_nama",
////                            "oleh_top_id" => "oleh_id",
////                            "oleh_top_nama" => "oleh_nama",
////                            "satuan_id" => "satuan_id",
////                            "transaksi_id" => "transaksi_id",
////                            "transaksi_no" => "transaksi_no",
////                            "satuan_nama" => "satuan",
////                        ),
////                        "srcGateName" => "items9_sum",
////                        "srcRawGateName" => "items9_sum",
////                    ),
////
////                    //bagian locker active
////                    array(
////                        "comName" => "LockerStock",
////                        "loop" => array(),
////                        "static" => array(
////                            "cabang_id" => "cabang_id",
////                            "jenis" => ".produk",
////                            "state" => ".active",
////                            "jumlah" => "-jml_dipakai",
////                            "produk_id" => "produk_id",
////                            "nama" => "produk_nama",
////                            "satuan" => "satuan",
////                            "transaksi_id" => ".0",
////                            "oleh_id" => ".0",
////                            "gudang_id" => "gudang_id",
////                            "toko_id" => "toko_id",
////                        ),
////                        "srcGateName" => "items9_sum",
////                        "srcRawGateName" => "items9_sum",
////                    ),
////
////                    array(
////                        "comName" => "LockerStockMutasi",
////                        "loop" => array(),
////                        "static" => array(
////                            "cabang_id" => "cabang_id",
////                            "extern_id" => "produk_id",
////                            "extern_nama" => "extern_nama",
////                            "qty_debet" => "-jml_dipakai",
////                            "produk_nilai" => "hpp",
////                            "gudang_id" => "gudang_id",
////                            "jenis" => ".758",
////                            "toko_id" => "toko_id",
////                        ),
////                        "reversable" => true,
////                        "srcGateName" => "items9_sum",
////                        "srcRawGateName" => "items9_sum",
////
////                    ),
////                ),
//                );
                switch ($jenisTr) {
                    case "4464":
                        $main["tr__references_ids"] = $main["refID"];
                        $postproc = array(
                            "master" => array(
                                array(
                                    "comName" => "RekeningPembantuRawMain",
                                    "loop" => array(
                                        "1010010010" => "nilai_entry",//rekening pembelian untuk keperluan lap
                                    ),
                                    "static" => array(
                                        "cabang_id" => "cabangID",
                                        "cabang_nama" => "cabangName",
                                        "extern_id" => ".1010010010",//lokal ,non lokal
                                        "extern_nama" => ".kas",
                                        "extern2_id" => "tr__jenis_master",
                                        "extern2_nama" => ".penjualan tunai",
//                            "extern3_id" => ".0",
//                            "extern3_nama" => "machine_id",//diisi machinid
//                            "extern4_id" => ".0",
//                            "extern4_nama" => ".0",
                                        "jenis" => "jenisTr",
                                        "transaksi_id" => "tr__id",
                                        "transaksi_no" => "tr__nomer",
                                        "produk_id" => "cash_account",// account cash
                                        "produk_nama" => "cash_account__nama",
                                        "produk_kode" => "cash_account__folders_nama",// bank
                                        "produk_jenis" => ".0",
                                        "barcode" => ".0",
                                        "jml" => ".1",
                                        "harga" => "nilai_entry",// yang harus dibayar
                                        "hpp" => "nilai_entry",
                                        "harga_include_ppn" => "harga_include_ppn",// harga include ppn
                                        "sub_harga" => "nilai_entry",// harga dpp
                                        "sub_hpp" => "nilai_entry",// hpp produk
                                        "sub_harga_include_ppn" => "sub_harga_include_ppn",// harga include ppn
                                        "oleh_id" => "tr__oleh_id",
                                        "oleh_nama" => "tr__oleh_nama",
                                        "pihak_id" => "pihakID",// konsumen
                                        "pihak_nama" => "pihakName", // konsumen
                                        "oleh_top_id" => "oleh_top_id",
                                        "oleh_top_nama" => "oleh_top_nama",
                                        "satuan_id" => "satuan_id",
                                        "satuan_nama" => "satuan_nama",
                                        "rugilaba" => "rugilaba",
                                        "master_id" => "tr__id_master",
                                        "diskon" => "discNilai",
                                        "diskon_persen" => "discPersen",
                                        //----------------
//                                        "outdoor_id" => "outdoor_id",
//                                        "outdoor_nama" => "outdoor_nama",
//                                        "outdoor_barcode" => "outdoor_barcode",
//                                        "outdoor_sku" => "outdoor_sku",
//                                        "indoor_id_1" => "indoor_id_1",
//                                        "indoor_nama_1" => "indoor_nama_1",
//                                        "indoor_barcode_1" => "indoor_barcode_1",
//                                        "indoor_sku_1" => "indoor_sku_1",
//                                        "indoor_id_2" => "indoor_id_2",
//                                        "indoor_nama_2" => "indoor_nama_2",
//                                        "indoor_barcode_2" => "indoor_barcode_2",
//                                        "indoor_sku_2" => "indoor_sku_2",
//                                        "indoor_id_3" => "indoor_id_3",
//                                        "indoor_nama_3" => "indoor_nama_3",
//                                        "indoor_barcode_3" => "indoor_barcode_3",
//                                        "indoor_sku_3" => "indoor_sku_3",
//                                        "indoor_id_4" => "indoor_id_4",
//                                        "indoor_nama_4" => "indoor_nama_4",
//                                        "indoor_barcode_4" => "indoor_barcode_4",
//                                        "indoor_sku_4" => "indoor_sku_4",
//                                        "kategori_id" => "kategori_id",
//                                        "kategori_nama" => "kategori_nama",
//                                        "produk_part_id_1" => "part_id_1",
//                                        "produk_part_nama_1" => "part_nama_1",
//                                        "produk_part_barcode_1" => "part_barcode_1",
//                                        "produk_part_id_2" => "part_id_2",
//                                        "produk_part_nama_2" => "part_nama_2",
//                                        "produk_part_barcode_2" => "part_barcode_2",
//                                        "heater_id" => "heater_id",
//                                        "heater_nama" => "heater_nama",
//                                        "heater_barcode" => "heater_barcode",
                                        //----------------
                                        "sales_admin_id" => "main__sellerID",
                                        "sales_admin_nama" => "main__sellerName",
                                        "salesman_id" => "pihakMain2ID",
                                        "salesman_nama" => "pihakMain2Name",
                                        "gudang_id_kirim" => "gudangStatusDetails",
                                        "gudang_nama_kirim" => "gudangStatusDetails__nama",
                                        "delivery_id" => "shippingMethod",
                                        "delivery_nama" => "shippingMethod__name",
                                        "pengirim_id" => "tr__pengirim_id",
                                        "pengirim_nama" => "tr__pengirim_nama",
                                        "pembayaran_nama" => "paymentMethod",
                                        //----------------
                                        "transaksi_id_1" => "tr__transaksi_id_1",
                                        "transaksi_no_1" => "tr__transaksi_no_1",
                                        "transaksi_id_2" => "tr__transaksi_id_2",
                                        "transaksi_no_2" => "tr__transaksi_no_2",
                                        "transaksi_id_3" => "tr__transaksi_id_3",
                                        "transaksi_no_3" => "tr__transaksi_no_3",
                                        "transaksi_id_4" => "tr__transaksi_id_4",
                                        "transaksi_no_4" => "tr__transaksi_no_4",
                                        "transaksi_id_5" => "tr__transaksi_id_5",
                                        "transaksi_no_5" => "tr__transaksi_no_5",
                                        //----------------
                                        "uang_muka_dipakai" => "uang_muka_dipakai",
                                        "credit_note" => "credit_amount",
                                        "pph23" => "pph23",
                                        "pihak_tipe" => "selectedType_konsumen",
                                        "references_data" => "tr__references_ids",
                                        //----------------
                                        "tagihan" => "sisa",
                                        "dibayar" => "nilai_bayar",
                                        "sisa" => "new_sisa",
                                    ),
                                    "srcGateName" => "items",
                                    "srcRawGateName" => "items",
                                ),
                            ),
                            "detail" => array(),
                        );
                        break;
                    case "4467":
                        $postproc = array(
                            "master" => array(
                                array(
                                    "comName" => "RekeningPembantuRawMain",
                                    "loop" => array(
                                        "1010010010" => "nett",//rekening pembelian untuk keperluan lap
                                    ),
                                    "static" => array(
                                        "cabang_id" => "cabangID",
                                        "cabang_nama" => "cabangName",
                                        "extern_id" => ".1010010010",//lokal ,non lokal
                                        "extern_nama" => ".kas",
                                        "extern2_id" => "tr__jenis_master",
                                        "extern2_nama" => ".uang muka",
//                            "extern3_id" => ".0",
//                            "extern3_nama" => "machine_id",//diisi machinid
//                            "extern4_id" => ".0",
//                            "extern4_nama" => ".0",
                                        "jenis" => "jenisTr",
                                        "transaksi_id" => "tr__id",
                                        "transaksi_no" => "tr__nomer",
                                        "produk_id" => "cash_account",// account cash
                                        "produk_nama" => "cash_account__nama",
                                        "produk_kode" => "cash_account__folders_nama",// bank
                                        "produk_jenis" => ".0",
                                        "barcode" => ".0",
                                        "jml" => ".1",
                                        "harga" => "nett",// yang harus dibayar
                                        "hpp" => "nett",
                                        "harga_include_ppn" => "harga_include_ppn",// harga include ppn
                                        "sub_harga" => "nett",// harga dpp
                                        "sub_hpp" => "nett",// hpp produk
                                        "sub_harga_include_ppn" => "sub_harga_include_ppn",// harga include ppn
                                        "oleh_id" => "tr__oleh_id",
                                        "oleh_nama" => "tr__oleh_nama",
                                        "pihak_id" => "pihakID",// konsumen
                                        "pihak_nama" => "pihakName", // konsumen
                                        "oleh_top_id" => "oleh_top_id",
                                        "oleh_top_nama" => "oleh_top_nama",
                                        "satuan_id" => "satuan_id",
                                        "satuan_nama" => "satuan_nama",
                                        "rugilaba" => "rugilaba",
                                        "master_id" => "tr__id_master",
                                        "diskon" => "discNilai",
                                        "diskon_persen" => "discPersen",
                                        //----------------
//                                        "outdoor_id" => "outdoor_id",
//                                        "outdoor_nama" => "outdoor_nama",
//                                        "outdoor_barcode" => "outdoor_barcode",
//                                        "outdoor_sku" => "outdoor_sku",
//                                        "indoor_id_1" => "indoor_id_1",
//                                        "indoor_nama_1" => "indoor_nama_1",
//                                        "indoor_barcode_1" => "indoor_barcode_1",
//                                        "indoor_sku_1" => "indoor_sku_1",
//                                        "indoor_id_2" => "indoor_id_2",
//                                        "indoor_nama_2" => "indoor_nama_2",
//                                        "indoor_barcode_2" => "indoor_barcode_2",
//                                        "indoor_sku_2" => "indoor_sku_2",
//                                        "indoor_id_3" => "indoor_id_3",
//                                        "indoor_nama_3" => "indoor_nama_3",
//                                        "indoor_barcode_3" => "indoor_barcode_3",
//                                        "indoor_sku_3" => "indoor_sku_3",
//                                        "indoor_id_4" => "indoor_id_4",
//                                        "indoor_nama_4" => "indoor_nama_4",
//                                        "indoor_barcode_4" => "indoor_barcode_4",
//                                        "indoor_sku_4" => "indoor_sku_4",
//                                        "kategori_id" => "kategori_id",
//                                        "kategori_nama" => "kategori_nama",
//                                        "produk_part_id_1" => "part_id_1",
//                                        "produk_part_nama_1" => "part_nama_1",
//                                        "produk_part_barcode_1" => "part_barcode_1",
//                                        "produk_part_id_2" => "part_id_2",
//                                        "produk_part_nama_2" => "part_nama_2",
//                                        "produk_part_barcode_2" => "part_barcode_2",
//                                        "heater_id" => "heater_id",
//                                        "heater_nama" => "heater_nama",
//                                        "heater_barcode" => "heater_barcode",
                                        //----------------
                                        "sales_admin_id" => "main__sellerID",
                                        "sales_admin_nama" => "main__sellerName",
                                        "salesman_id" => "pihakMain2ID",
                                        "salesman_nama" => "pihakMain2Name",
                                        "gudang_id_kirim" => "gudangStatusDetails",
                                        "gudang_nama_kirim" => "gudangStatusDetails__nama",
                                        "delivery_id" => "shippingMethod",
                                        "delivery_nama" => "shippingMethod__name",
                                        "pengirim_id" => "tr__pengirim_id",
                                        "pengirim_nama" => "tr__pengirim_nama",
                                        "pembayaran_nama" => "paymentMethod",
                                        //----------------
                                        "transaksi_id_1" => "tr__transaksi_id_1",
                                        "transaksi_no_1" => "tr__transaksi_no_1",
                                        "transaksi_id_2" => "tr__transaksi_id_2",
                                        "transaksi_no_2" => "tr__transaksi_no_2",
                                        "transaksi_id_3" => "tr__transaksi_id_3",
                                        "transaksi_no_3" => "tr__transaksi_no_3",
                                        "transaksi_id_4" => "tr__transaksi_id_4",
                                        "transaksi_no_4" => "tr__transaksi_no_4",
                                        "transaksi_id_5" => "tr__transaksi_id_5",
                                        "transaksi_no_5" => "tr__transaksi_no_5",
                                        //----------------
                                        "uang_muka_dipakai" => "uang_muka_dipakai",
                                        "credit_note" => "credit_amount",
                                        "pph23" => "pph23",
                                        "pihak_tipe" => "selectedType_konsumen",
                                        "dpp_nilai" => "dpp_nilai",
                                        "ppn" => "ppn",
                                        "references_data" => "tr__references_ids",
                                        //----------------
                                        "tagihan" => "sisa",
                                        "dibayar" => "nilai_bayar",
                                        "sisa" => "new_sisa",
                                    ),
                                    "srcGateName" => "items",
                                    "srcRawGateName" => "items",
                                ),
                            ),
                            "detail" => array(),
                        );
                        break;
                    case "749":
                        if (isset($main["refs"])) {
                            $refs = blobDecode($main["refs"]);
                            $main["tr__references_ids"] = implode(",", $refs);
                            cekHere($main["tr__references_ids"]);
                        }
                        $postproc = array(
                            "master" => array(
                                array(
                                    "comName" => "RekeningPembantuRawMain",
                                    "loop" => array(
                                        "1010010010" => "nilai_entry",//rekening pembelian untuk keperluan lap
                                    ),
                                    "static" => array(
                                        "cabang_id" => "cabangID",
                                        "cabang_nama" => "cabangName",
                                        "extern_id" => ".1010010010",//lokal ,non lokal
                                        "extern_nama" => ".kas",
                                        "extern2_id" => "tr__jenis_master",//lihat coa untuk urutannya
                                        "extern2_nama" => ".penerimaan piutang",
//                            "extern3_id" => ".0",
//                            "extern3_nama" => "machine_id",//diisi machinid
//                            "extern4_id" => ".0",
//                            "extern4_nama" => ".0",
                                        "jenis" => "jenisTr",
                                        "transaksi_id" => "tr__id",
                                        "transaksi_no" => "tr__nomer",
                                        "produk_id" => "cash_account",// cash account
                                        "produk_nama" => "cash_account__nama",// cash account
                                        "produk_kode" => "cash_account__folders_nama",// bank
                                        "produk_jenis" => "jenis",
                                        "barcode" => "barcode",
                                        "jml" => ".1",
                                        "harga" => "nilai_entry",// harga dpp
                                        "hpp" => "nilai_entry",// hpp produk
                                        "harga_include_ppn" => "harga_include_ppn",// harga include ppn
                                        "sub_harga" => "nilai_entry",// harga dpp
                                        "sub_hpp" => "nilai_entry",// hpp produk
                                        "sub_harga_include_ppn" => "sub_harga_include_ppn",// harga include ppn
                                        "oleh_id" => "tr__oleh_id",
                                        "oleh_nama" => "tr__oleh_nama",
                                        "pihak_id" => "pihakID",// konsumen
                                        "pihak_nama" => "pihakName", // konsumen
                                        "oleh_top_id" => "oleh_top_id",
                                        "oleh_top_nama" => "oleh_top_nama",
                                        "satuan_id" => "satuan_id",
                                        "satuan_nama" => "satuan_nama",
                                        "rugilaba" => "rugilaba",
                                        "master_id" => "tr__id_master",
                                        "diskon" => "discNilai",
                                        "diskon_persen" => "discPersen",
                                        //----------------
//                                        "outdoor_id" => "outdoor_id",
//                                        "outdoor_nama" => "outdoor_nama",
//                                        "outdoor_barcode" => "outdoor_barcode",
//                                        "outdoor_sku" => "outdoor_sku",
//                                        "indoor_id_1" => "indoor_id_1",
//                                        "indoor_nama_1" => "indoor_nama_1",
//                                        "indoor_barcode_1" => "indoor_barcode_1",
//                                        "indoor_sku_1" => "indoor_sku_1",
//                                        "indoor_id_2" => "indoor_id_2",
//                                        "indoor_nama_2" => "indoor_nama_2",
//                                        "indoor_barcode_2" => "indoor_barcode_2",
//                                        "indoor_sku_2" => "indoor_sku_2",
//                                        "indoor_id_3" => "indoor_id_3",
//                                        "indoor_nama_3" => "indoor_nama_3",
//                                        "indoor_barcode_3" => "indoor_barcode_3",
//                                        "indoor_sku_3" => "indoor_sku_3",
//                                        "indoor_id_4" => "indoor_id_4",
//                                        "indoor_nama_4" => "indoor_nama_4",
//                                        "indoor_barcode_4" => "indoor_barcode_4",
//                                        "indoor_sku_4" => "indoor_sku_4",
//                                        "kategori_id" => "kategori_id",
//                                        "kategori_nama" => "kategori_nama",
//                                        "produk_part_id_1" => "part_id_1",
//                                        "produk_part_nama_1" => "part_nama_1",
//                                        "produk_part_barcode_1" => "part_barcode_1",
//                                        "produk_part_id_2" => "part_id_2",
//                                        "produk_part_nama_2" => "part_nama_2",
//                                        "produk_part_barcode_2" => "part_barcode_2",
//                                        "heater_id" => "heater_id",
//                                        "heater_nama" => "heater_nama",
//                                        "heater_barcode" => "heater_barcode",
                                        //----------------
                                        "sales_admin_id" => "main__sellerID",
                                        "sales_admin_nama" => "main__sellerName",
                                        "salesman_id" => "main__salesmanDetails",
                                        "salesman_nama" => "main__salesmanDetails__nama",
                                        "gudang_id_kirim" => "main__gudangStatusDetails",
                                        "gudang_nama_kirim" => "main__gudangStatusDetails__nama",
                                        "delivery_id" => "main__shippingMethod",
                                        "delivery_nama" => "main__shippingMethod__name",
                                        "pengirim_id" => "main__pengirimID",
                                        "pengirim_nama" => "main__pengirimName",
                                        "pembayaran_nama" => "main__paymentMethod",
                                        //----------------
                                        "transaksi_id_1" => "tr__transaksi_id_1",
                                        "transaksi_no_1" => "tr__transaksi_no_1",
                                        "transaksi_id_2" => "tr__transaksi_id_2",
                                        "transaksi_no_2" => "tr__transaksi_no_2",
                                        "transaksi_id_3" => "tr__transaksi_id_3",
                                        "transaksi_no_3" => "tr__transaksi_no_3",
                                        "transaksi_id_4" => "tr__transaksi_id_4",
                                        "transaksi_no_4" => "tr__transaksi_no_4",
                                        "transaksi_id_5" => "tr__transaksi_id_5",
                                        "transaksi_no_5" => "tr__transaksi_no_5",
                                        //----------------
                                        "uang_muka_dipakai" => "uang_muka_dipakai",
                                        "credit_note" => "credit_amount",
                                        "pph23" => "pph23",
                                        "pihak_tipe" => "selectedType_konsumen",
                                        "point_konsumen_nilai" => "point_konsumen_nilai",
                                        "ppn_nilai_dibayar" => "ppn_nilai_dibayar",
                                        "pph22_nilai" => "pph22_nilai",
                                        "nilai_biaya" => "nilai_biaya",
                                        "kelebihan_bayar" => "kelebihanBayar",
                                        "kelebihan_bayar_nama" => "kelebihanBayar__name",
                                        "deposit_konsumen" => "deposit_konsumen",
                                        "pendapatan_lain_lain" => "pendapatan_lain_lain",
                                        "references_data" => "tr__references_ids",
                                        //----------------
                                        "tagihan" => "sisa",
                                        "dibayar" => "nilai_bayar",
                                        "sisa" => "new_sisa",
                                    ),
                                    "srcGateName" => "items",
                                    "srcRawGateName" => "items",
                                ),
                            ),
                            "detail" => array(),
                        );
                        break;
                    case "489":
                        if (isset($main["refs"])) {
                            $refs = blobDecode($main["refs"]);
                            $main["tr__references_ids"] = implode(",", $refs);
                            cekHere($main["tr__references_ids"]);
                        }
                        $postproc = array(
                            "master" => array(
                                array(
                                    "comName" => "RekeningPembantuRawMain",
                                    "loop" => array(
                                        "1010010010" => "-nilai_entry",//rekening pembelian untuk keperluan lap
                                    ),
                                    "static" => array(
                                        "cabang_id" => "cabangID",
                                        "cabang_nama" => "cabangName",
                                        "extern_id" => ".1010010010",//lokal ,non lokal
                                        "extern_nama" => ".kas",
                                        "extern2_id" => "tr__jenis_master",//lihat coa untuk urutannya
                                        "extern2_nama" => ".pembayaran hutang dagang",
//                            "extern3_id" => ".0",
//                            "extern3_nama" => "machine_id",//diisi machinid
//                            "extern4_id" => ".0",
//                            "extern4_nama" => ".0",
                                        "jenis" => "jenisTr",
                                        "transaksi_id" => "tr__id",
                                        "transaksi_no" => "tr__nomer",
                                        "produk_id" => "cash_account",// cash account
                                        "produk_nama" => "cash_account__nama",
                                        "produk_kode" => "cash_account__folders_nama",// bank
                                        "produk_jenis" => "jenis",
                                        "barcode" => "barcode",
                                        "jml" => ".1",
                                        "harga" => "nilai_entry",// harga dpp
                                        "hpp" => "nilai_entry",// hpp produk
                                        "harga_include_ppn" => "harga_include_ppn",// harga include ppn
                                        "sub_harga" => "nilai_entry",// harga dpp
                                        "sub_hpp" => "nilai_entry",// hpp produk
                                        "sub_harga_include_ppn" => "sub_harga_include_ppn",// harga include ppn
                                        "oleh_id" => "tr__oleh_id",
                                        "oleh_nama" => "tr__oleh_nama",
                                        "pihak_id" => "pihakID",// supplier
                                        "pihak_nama" => "pihakName", // supplier
                                        "oleh_top_id" => "oleh_top_id",
                                        "oleh_top_nama" => "oleh_top_nama",
                                        "satuan_id" => "satuan_id",
                                        "satuan_nama" => "satuan_nama",
                                        "rugilaba" => "rugilaba",
                                        "master_id" => "tr__id_master",
                                        "diskon" => "discNilai",
                                        "diskon_persen" => "discPersen",
                                        //----------------
//                                        "outdoor_id" => "outdoor_id",
//                                        "outdoor_nama" => "outdoor_nama",
//                                        "outdoor_barcode" => "outdoor_barcode",
//                                        "outdoor_sku" => "outdoor_sku",
//                                        "indoor_id_1" => "indoor_id_1",
//                                        "indoor_nama_1" => "indoor_nama_1",
//                                        "indoor_barcode_1" => "indoor_barcode_1",
//                                        "indoor_sku_1" => "indoor_sku_1",
//                                        "indoor_id_2" => "indoor_id_2",
//                                        "indoor_nama_2" => "indoor_nama_2",
//                                        "indoor_barcode_2" => "indoor_barcode_2",
//                                        "indoor_sku_2" => "indoor_sku_2",
//                                        "indoor_id_3" => "indoor_id_3",
//                                        "indoor_nama_3" => "indoor_nama_3",
//                                        "indoor_barcode_3" => "indoor_barcode_3",
//                                        "indoor_sku_3" => "indoor_sku_3",
//                                        "indoor_id_4" => "indoor_id_4",
//                                        "indoor_nama_4" => "indoor_nama_4",
//                                        "indoor_barcode_4" => "indoor_barcode_4",
//                                        "indoor_sku_4" => "indoor_sku_4",
//                                        "kategori_id" => "kategori_id",
//                                        "kategori_nama" => "kategori_nama",
//                                        "produk_part_id_1" => "part_id_1",
//                                        "produk_part_nama_1" => "part_nama_1",
//                                        "produk_part_barcode_1" => "part_barcode_1",
//                                        "produk_part_id_2" => "part_id_2",
//                                        "produk_part_nama_2" => "part_nama_2",
//                                        "produk_part_barcode_2" => "part_barcode_2",
//                                        "heater_id" => "heater_id",
//                                        "heater_nama" => "heater_nama",
//                                        "heater_barcode" => "heater_barcode",
                                        //----------------
                                        "sales_admin_id" => "main__sellerID",
                                        "sales_admin_nama" => "main__sellerName",
                                        "salesman_id" => "main__salesmanDetails",
                                        "salesman_nama" => "main__salesmanDetails__nama",
                                        "gudang_id_kirim" => "main__gudangStatusDetails",
                                        "gudang_nama_kirim" => "main__gudangStatusDetails__nama",
                                        "delivery_id" => "main__shippingMethod",
                                        "delivery_nama" => "main__shippingMethod__name",
                                        "pengirim_id" => "main__pengirimID",
                                        "pengirim_nama" => "main__pengirimName",
                                        "pembayaran_nama" => "main__paymentMethod",
                                        //----------------
                                        "transaksi_id_1" => "tr__transaksi_id_1",
                                        "transaksi_no_1" => "tr__transaksi_no_1",
                                        "transaksi_id_2" => "tr__transaksi_id_2",
                                        "transaksi_no_2" => "tr__transaksi_no_2",
                                        "transaksi_id_3" => "tr__transaksi_id_3",
                                        "transaksi_no_3" => "tr__transaksi_no_3",
                                        "transaksi_id_4" => "tr__transaksi_id_4",
                                        "transaksi_no_4" => "tr__transaksi_no_4",
                                        "transaksi_id_5" => "tr__transaksi_id_5",
                                        "transaksi_no_5" => "tr__transaksi_no_5",
                                        //----------------
                                        "uang_muka_dipakai" => "uang_muka_dipakai",
                                        "credit_note" => "credit_amount",
                                        "pph23" => "pph23",
                                        "pihak_tipe" => "selectedType_konsumen",
                                        "point_konsumen_nilai" => "point_konsumen_nilai",
                                        "ppn_nilai_dibayar" => "ppn_nilai_dibayar",
                                        "pph22_nilai" => "pph22_nilai",
                                        "nilai_biaya" => "nilai_biaya",
                                        "kelebihan_bayar" => "kelebihanBayar",
                                        "kelebihan_bayar_nama" => "kelebihanBayar__name",
                                        "deposit_konsumen" => "deposit_konsumen",
                                        "pendapatan_lain_lain" => "pendapatan_lain_lain",
                                        "references_data" => "tr__references_ids",
                                        //----------------
                                        "tagihan" => "sisa",
                                        "dibayar" => "nilai_bayar",
                                        "sisa" => "new_sisa",
                                        "tagihan_include_ppn" => "tagihan_bayar",
                                        "ppn_nilai" => "ppn_final",
                                        "sub_ppn_nilai" => "ppn_final",
                                        "date_faktur" => "dateFaktur",
                                        "nomor_faktur" => "eFaktur",
                                    ),
                                    "srcGateName" => "items",
                                    "srcRawGateName" => "items",
                                ),
                            ),
                            "detail" => array(),
                        );
                        break;
                    case "464":
                        $postproc = array(
                            "master" => array(
                                array(
                                    "comName" => "RekeningPembantuRawMain",
                                    "loop" => array(
                                        "1010010010" => "-nett",//rekening pembelian untuk keperluan lap
                                    ),
                                    "static" => array(
                                        "cabang_id" => "cabangID",
                                        "cabang_nama" => "cabangName",
                                        "extern_id" => ".1010010010",//lokal ,non lokal
                                        "extern_nama" => ".kas",
                                        "extern2_id" => "tr__jenis_master",//lihat coa untuk urutannya
                                        "extern2_nama" => ".uang muka ke supplier",
//                            "extern3_id" => ".0",
//                            "extern3_nama" => "machine_id",//diisi machinid
//                            "extern4_id" => ".0",
//                            "extern4_nama" => ".0",
                                        "jenis" => "jenisTr",
                                        "transaksi_id" => "tr__id",
                                        "transaksi_no" => "tr__nomer",
                                        "produk_id" => "cash_account",// cash account
                                        "produk_nama" => "cash_account__nama",
                                        "produk_kode" => "cash_account__folders_nama",// bank
                                        "produk_jenis" => "jenis",
                                        "barcode" => "barcode",
                                        "jml" => ".1",
                                        "harga" => "nett",// harga dpp
                                        "hpp" => "nett",// hpp produk
                                        "harga_include_ppn" => "harga_include_ppn",// harga include ppn
                                        "sub_harga" => "nett",// harga dpp
                                        "sub_hpp" => "nett",// hpp produk
                                        "sub_harga_include_ppn" => "sub_harga_include_ppn",// harga include ppn
                                        "oleh_id" => "tr__oleh_id",
                                        "oleh_nama" => "tr__oleh_nama",
                                        "pihak_id" => "pihakID",// supplier
                                        "pihak_nama" => "pihakName", // supplier
                                        "oleh_top_id" => "oleh_top_id",
                                        "oleh_top_nama" => "oleh_top_nama",
                                        "satuan_id" => "satuan_id",
                                        "satuan_nama" => "satuan_nama",
                                        "rugilaba" => "rugilaba",
                                        "master_id" => "tr__id_master",
                                        "diskon" => "discNilai",
                                        "diskon_persen" => "discPersen",
                                        //----------------
//                                        "outdoor_id" => "outdoor_id",
//                                        "outdoor_nama" => "outdoor_nama",
//                                        "outdoor_barcode" => "outdoor_barcode",
//                                        "outdoor_sku" => "outdoor_sku",
//                                        "indoor_id_1" => "indoor_id_1",
//                                        "indoor_nama_1" => "indoor_nama_1",
//                                        "indoor_barcode_1" => "indoor_barcode_1",
//                                        "indoor_sku_1" => "indoor_sku_1",
//                                        "indoor_id_2" => "indoor_id_2",
//                                        "indoor_nama_2" => "indoor_nama_2",
//                                        "indoor_barcode_2" => "indoor_barcode_2",
//                                        "indoor_sku_2" => "indoor_sku_2",
//                                        "indoor_id_3" => "indoor_id_3",
//                                        "indoor_nama_3" => "indoor_nama_3",
//                                        "indoor_barcode_3" => "indoor_barcode_3",
//                                        "indoor_sku_3" => "indoor_sku_3",
//                                        "indoor_id_4" => "indoor_id_4",
//                                        "indoor_nama_4" => "indoor_nama_4",
//                                        "indoor_barcode_4" => "indoor_barcode_4",
//                                        "indoor_sku_4" => "indoor_sku_4",
//                                        "kategori_id" => "kategori_id",
//                                        "kategori_nama" => "kategori_nama",
//                                        "produk_part_id_1" => "part_id_1",
//                                        "produk_part_nama_1" => "part_nama_1",
//                                        "produk_part_barcode_1" => "part_barcode_1",
//                                        "produk_part_id_2" => "part_id_2",
//                                        "produk_part_nama_2" => "part_nama_2",
//                                        "produk_part_barcode_2" => "part_barcode_2",
//                                        "heater_id" => "heater_id",
//                                        "heater_nama" => "heater_nama",
//                                        "heater_barcode" => "heater_barcode",
                                        //----------------
                                        "sales_admin_id" => "main__sellerID",
                                        "sales_admin_nama" => "main__sellerName",
                                        "salesman_id" => "main__salesmanDetails",
                                        "salesman_nama" => "main__salesmanDetails__nama",
                                        "gudang_id_kirim" => "main__gudangStatusDetails",
                                        "gudang_nama_kirim" => "main__gudangStatusDetails__nama",
                                        "delivery_id" => "main__shippingMethod",
                                        "delivery_nama" => "main__shippingMethod__name",
                                        "pengirim_id" => "main__pengirimID",
                                        "pengirim_nama" => "main__pengirimName",
                                        "pembayaran_nama" => "main__paymentMethod",
                                        //----------------
                                        "transaksi_id_1" => "tr__transaksi_id_1",
                                        "transaksi_no_1" => "tr__transaksi_no_1",
                                        "transaksi_id_2" => "tr__transaksi_id_2",
                                        "transaksi_no_2" => "tr__transaksi_no_2",
                                        "transaksi_id_3" => "tr__transaksi_id_3",
                                        "transaksi_no_3" => "tr__transaksi_no_3",
                                        "transaksi_id_4" => "tr__transaksi_id_4",
                                        "transaksi_no_4" => "tr__transaksi_no_4",
                                        "transaksi_id_5" => "tr__transaksi_id_5",
                                        "transaksi_no_5" => "tr__transaksi_no_5",
                                        //----------------
                                        "uang_muka_dipakai" => "uang_muka_dipakai",
                                        "credit_note" => "credit_amount",
                                        "pph23" => "pph23",
                                        "pihak_tipe" => "selectedType_konsumen",
                                        "point_konsumen_nilai" => "point_konsumen_nilai",
                                        "ppn_nilai_dibayar" => "ppn_nilai_dibayar",
                                        "pph22_nilai" => "pph22_nilai",
                                        "nilai_biaya" => "nilai_biaya",
                                        "kelebihan_bayar" => "kelebihanBayar",
                                        "kelebihan_bayar_nama" => "kelebihanBayar__name",
                                        "deposit_konsumen" => "deposit_konsumen",
                                        "pendapatan_lain_lain" => "pendapatan_lain_lain",
                                        "references_data" => "tr__references_ids",
                                        "ppn_nilai" => "ppn_pengganti",
                                        "sub_ppn_nilai" => "ppn_pengganti",
                                        "tagihan" => "dpp_pengganti",
                                        "date_faktur" => "dateFaktur",
                                        "nomor_faktur" => "eFaktur",
                                        "dibayar" => "kas_nilai",
                                    ),
                                    "srcGateName" => "items",
                                    "srcRawGateName" => "items",
                                ),
                            ),
                            "detail" => array(),
                        );
                        break;
                    default:
                        mati_disini("processor belum di setting...");
                        break;
                }

                //endregion
//                mati_disini(__LINE__);

                //region postproc
                cekMerah("mulai postproc ::::::" . __LINE__);
                $iterator = array();
                $iterator = $postproc["detail"];
                if (sizeof($iterator) > 0) {
                    $comsLocation = "Coms";
                    $comsPrefix = "Com";
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $tmpOutParams[$cCtr] = array();
                        $gg = 0;
                        foreach ($items as $id => $dSpec) {
                            // $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $items, $comName);
                            }

                            $mdlName = "$comsPrefix" . ucfirst($comName);
                            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                $filterNeeded = true;
                            }
                            else {
                                $filterNeeded = false;
                            }
                            // cekHere("sub-component: [$comsLocation] $comName, initializing values <br>");

                            $subParams = array();

                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $dSpec[$key], $key);
                                    }

                                    $realValue = makeValue($value, $dSpec, $dSpec, 0);
                                    // cekMErah("$key =>".$realValue);
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
                                    $realValue = makeValue($value, $dSpec, $dSpec, 0);
                                    $subParams['static'][$key] = $realValue;
                                }
                                if (!isset($subParams['static']["transaksi_id"])) {
                                    $subParams['static']["transaksi_id"] = 0000;
                                }
                                if (!isset($subParams['static']["transaksi_no"])) {
                                    $subParams['static']["transaksi_no"] = 0000;
                                }

                                $subParams['static']["fulldate"] = $fulldate;
                                $subParams['static']["dtime"] = $dtime;
                                $subParams['static']["dtime_2"] = date("Y-m-d H:i");
                                $subParams['static']["keterangan"] = $jenisTrName . " oleh " . $oleh_nama;
                            }

                            if (sizeof($subParams) > 0) {
                                //                                cekhitam("subparam ada isinya");
                                if ($filterNeeded) {
                                    if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                }
                            }
                            else {
                                cekhitam("subparam TIDAK ada isinya");
                            }
                        }

                        $componentGate['detail'][$cCtr] = $subParams;
                    }
                    // arrPrint($tmpOutParams);
                    // matiHEre($cCtr);

                    foreach ($iterator as $cCtr => $tComSpec) {
                        // $srcGateName = $tComSpec['srcGateName'];
                        foreach ($items as $id => $dSpec) {
                            // $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $items[$id][$comName], $comName);
                            }
                        }
                        cekHere("sub component: [$comsLocation] $comName, sending values " . __LINE__ . "<br>");

                        $mdlName = "$comsPrefix" . ucfirst($comName);
                        $this->load->model("$comsLocation/" . $mdlName);
                        $m = new $mdlName();
                        //===filter value nol, jika harus difilter

                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        if ($tobeExecuted) {
                            //----- kiriman gerbang
                            if (method_exists($m, "setTableInMaster")) {
                                $m->setTableInMaster($tableIn_master);
                            }
                            if (method_exists($m, "setDetail")) {
                                $m->setDetail($items);
                            }
                            if (method_exists($m, "setJenisTr")) {
                                $m->setJenisTr($jenisTr);
                            }
                            //----- kiriman gerbang
                            $m->pair($tmpOutParams[$cCtr]) or matiHere("Tidak berhasil memasang  values pada komponen: $comName/" . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or matiHere("Gagal saat berusaha  exec values pada komponen: $comName/" . "/" . __FUNCTION__ . "/" . __LINE__);
//                            cekBiru($this->db->last_query());
                        }
                        else {
//                            cekMerah("$comName tidak eksekusi");
                        }

                    }
                }
                else {
                    cekKuning("sub post-components is not set");
                }


                $mainData = $main;
                $iterator = array();
                $iterator = $postproc["master"];
                if (sizeof($iterator) > 0) {
                    $componentConfig['master'] = $iterator;
                    $cCtr = 0;
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $cCtr++;
                        $comName = $tComSpec['comName'];
                        if (substr($comName, 0, 1) == "{") {
                            $comName = trim($comName, "{");
                            $comName = trim($comName, "}");
                            $comName = str_replace($comName, $mainData, $comName);
                        }
                        cekHere("component # $cCtr: $comName<br>");

                        $dSpec = $mainData;
                        $tmpOutParams = array();
                        if (isset($tComSpec['loop'])) {
                            foreach ($tComSpec['loop'] as $key => $value) {
                                if (substr($key, 0, 1) == "{") {
                                    $key = trim($key, "{");
                                    $key = trim($key, "}");
                                    $key = str_replace($key, $mainData[$key], $key);
                                }
                                $realValue = makeValue($value, $mainData, $mainData, 0);
                                $tmpOutParams['loop'][$key] = $realValue;
                            }
                        }
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {
                                $realValue = makeValue($value, $mainData, $mainData, 0);
                                $tmpOutParams['static'][$key] = $realValue;
                            }
                            if (!isset($tmpOutParams['static']["transaksi_id"])) {
                                $tmpOutParams['static']["transaksi_id"] = "0000";
                            }
                            if (!isset($tmpOutParams['static']["transaksi_no"])) {
                                $tmpOutParams['static']["transaksi_no"] = "0000";
                            }
                            $tmpOutParams['static']["urut"] = $cCtr;
                            $tmpOutParams['static']["fulldate"] = $mainData["dtime"];
                            $tmpOutParams['static']["dtime"] = $mainData["dtime"];
                            $tmpOutParams['static']["dtime_2"] = date("Y-m-d H:i");
                            $tmpOutParams['static']["keterangan"] = $jenisTrName . " oleh " . $oleh_nama;
                        }

                        $mdlName = "Com" . ucfirst($comName);
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();

                        //===filter value nol, jika harus difilter
                        $tobeExecuted = true;
//                        if (in_array($mdlName, $compValidators)) {
//                            $loopParams = isset($tmpOutParams['loop']) ? $tmpOutParams['loop'] : array();
//                            if (sizeof($loopParams) > 0) {
//                                foreach ($loopParams as $key => $val) {
//                                    cekmerah("$comName : $key = $val ");
//                                    if ($val == 0) {
//                                        unset($tmpOutParams['loop'][$key]);
//                                    }
//                                }
//                            }
//                            if (sizeof($tmpOutParams['loop']) < 1) {
//                                $tobeExecuted = false;
//                            }
//                        }
                        // arrprint($jenis);
                        //                     matiHEre();
                        if ($tobeExecuted) {
                            //----- kiriman gerbang untuk counter mutasi rekening
                            if (method_exists($m, "setTableInMaster")) {
                                $m->setTableInMaster($tableIn_master);
                            }
                            if (method_exists($m, "setMain")) {
                                $m->setMain($mainData);
                            }
                            if (method_exists($m, "setJenisTr")) {
                                $m->setJenisTr($mainData["jenis"]);
                            }
                            //----- kiriman gerbang untuk counter mutasi rekening
                            $m->pair($tmpOutParams) or mati_disini("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or mati_disini("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        }
//                        $componentGate['master'][$cCtr] = $tmpOutParams;
                    }
                }
                else {
                    cekKuning("components is not set");
                }
                //endregion


                $tr = New MdlTransaksi();
                $tr->setFilters(array());
                $where = array(
                    "id" => $transaksi_id,
                );
                $data = array(
                    "r_maju" => 1,
                );
                $tr->updateData($where, $data);
                showLast_query("orange");


            }
            else {
                cekMerah("<h3>HABIS...</h3>");
            }


        }
        $end = microtime(true);
        $selesai = $end - $start;


//        matiHEre("complitt [selesai dalam $selesai]");


        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");


        cekHijau("<h3>SELESAI... [$selesai]</h3>");

    }

    public function run_bukuPembantuPembelian()
    {
        if (isset($_GET["r"]) && ($_GET["r"] > 0)) {
            $refresh = $_GET["r"];
            header("refresh:$refresh");
        }


        $this->db->trans_start();
        $start = microtime(true);
        $force = isset($_GET["force"]) ? $_GET["force"] : "none";
        $cekjam = date("H");
        $this->load->helper("he_angka");
//        $jenisTr = "5822spd";
        $arrJenisTr = array(
            "467",
            "1467",
            //-----
            "967",
            "9967",
            //-----
            "1967",
            "19967",
        );
        $main = array();
        $items = array();
        $tableIn_master = array();
        $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();


        $timeTOexec = true;
        if ($timeTOexec) {
            $tr = New MdlTransaksi();
            $tr->addFilter("jenis in ('" . implode("','", $arrJenisTr) . "')");
            $tr->addFilter("r_maju='0'");
            $this->db->order_by("id", "asc");
            $this->db->limit("1");
            $trTmp = $tr->lookupAll()->result();
            if (sizeof($trTmp) > 0) {
                $transaksi_id = $trTmp[0]->id;
                $fulldate = $trTmp[0]->fulldate;
                $dtime = $trTmp[0]->dtime;
                $jenisTrName = $trTmp[0]->jenis_label;
                $oleh_id = $trTmp[0]->oleh_id;
                $oleh_nama = $trTmp[0]->oleh_nama;
                $jenisTr = $trTmp[0]->jenis_master;
                $idsHis = ($trTmp[0]->ids_his != null) ? blobDecode($trTmp[0]->ids_his) : array();
                if (sizeof($idsHis) > 0) {
                    foreach ($idsHis as $step_his => $data_his) {
                        $keyss_ids = "transaksi_id_" . $step_his;
                        $keyss_nomers = "transaksi_no_" . $step_his;
                        $trTmp[0]->$keyss_ids = $data_his["trID"];
                        $trTmp[0]->$keyss_nomers = $data_his["nomer"];
                    }
                }

                $trData = (array)$trTmp[0];

                // region membaca registry items dan main
                $trReg = New MdlTransaksi();
                $trReg->setFilters(array());
                $trReg->setJointSelectFields("transaksi_id, main, items, tableIn_master");
                $trReg->addFilter("transaksi_id='$transaksi_id'");
                $trRegTmp = $trReg->lookupDataRegistries()->result();
                showLast_query("biru");
                foreach ($trRegTmp as $regs) {
                    foreach ($regs as $key => $val) {
                        if ($key != "transaksi_id") {
                            switch ($key) {
                                case "main":
                                    $main = blobDecode($val);
                                    break;
                                case "items":
                                    $items = blobDecode($val);
                                    break;
                                case "tableIn_master":
                                    $tableIn_master = blobDecode($val);
                                    break;
                            }
                        }
                    }
                }
                // endregion

                if (sizeof($items) > 0) {
                    foreach ($items as $pID => $iSpec) {
//                        "ppn_nilai" => "ppn_nilai",
//                        "sub_ppn_nilai" => "sub_ppn_nilai",
//                        $iSpec["ppn_nilai"] = ($iSpec["nett1"] * 0.11);
//                        $iSpec["sub_ppn_nilai"] = ($iSpec["sub_nett1"] * 0.11);
                        foreach ($main as $m_key => $m_val) {
                            $new_m_key = "main__" . $m_key;
                            $iSpec[$new_m_key] = $m_val;
                        }
                        foreach ($trData as $tr_key => $tr_val) {
                            $new_tr_key = "tr__" . $tr_key;
                            $iSpec[$new_tr_key] = $tr_val;
                        }
//                        arrPrintKuning($iSpec);
                        $items[$pID] = $iSpec;
                    }
                }


                $srcField = array(
                    "cabangID" => "cabang_id",
                    "placeID" => "cabang_id",
                    "cabang_id" => "cabang_id",
                    "toko_id" => "toko_id",
                    "machine_id" => "machine_id",
                    "jenis" => "jenis",
                    "jenisTr" => "jenis",
                    "toko_nama" => "toko_nama",
                    "pihak_id" => "pihak_id",
                    "pihak_nama" => "pihak_nama",
                    "oleh_id" => "oleh_id",
                    "oleh_nama" => "oleh_nama",
                    "dtime" => "dtime",
//            "kas" => "sisa",
//            "nomer" => "nomer",
                );
                $selectedItemFields = array(
                    "id" => "id",
                    "toko_id" => "toko_id",
                    "cabang_id" => "cabang_id",
                    "produk_id" => "produk_id",
                    "produk_nama" => "produk_nama",
                    "valid_qty" => "valid_qty",
                    "produk_ord_jml" => "produk_ord_jml",
                    "produk_ord_jml_return" => "produk_ord_jml_return",
                    "sisa" => "sisa",
                    "produk_ord_hrg" => "produk_ord_hrg",
                    "produk_ord_hpp" => "produk_ord_hpp",
                    "dtime" => "dtime",
                    "fulldate" => "dtime",
                    "jml" => "sisa",
                    "qty" => "sisa",
                    "satuan" => "satuan",

                );

                //region COMPONENT JURNAL
                cekUngu("$jenisTr");
//                $preproc = array(
////                "master" => array(),
////                "detail" => array(
////                    array(
////                        "comName" => "FifoAverage",
////                        "loop" => array(),
////                        "static" => array(
////                            "cabang_id" => "cabang_id",
////                            "extern_id" => "produk_id",
////                            "extern_nama" => "produk_nama",
////                            "produk_qty" => "jml_dipakai",
////                            "gudang_id" => "gudang_id",
////                            "toko_id" => "toko_id",
////                            "harga" => "harga",
////                            "harga_jual" => "harga",
////                            "satuan_id" => "satuan_id",
////                            "satuan_nama" => "satuan",
////                        ),
////                        "resultParams" => array(
////                            "rsltItems" => array(
////                                // "qty" => "jml",
////                                "hpp" => "hpp",
////                                "produk_id" => "produk_id",
////                                "produk_nama" => "nama",
////                                "harga_jual" => "harga_jual",
////                                "satuan_id" => "satuan_id",
////                                "satuan_nama" => "satuan",
////                            ),
////                        ),
////                        "srcGateName" => "items9_sum",
////                        "srcRawGateName" => "items9_sum",
////                    ),
////                ),
//                );
//                $component = array(
////                "master" => array(
////                    //catatan jurnal penjualan tidak jadi dijalanknan hanya nulis raw nya saja
////
////                    // /*
////                    //  * untuk penjualan di transaksi settlement ,disini hanya jalan raw pembantu penjualan saja.
////                    //  */
////                    // array(
////                    //     "comName" => "Jurnal",
////                    //     "loop" => array(
////                    //         "1010010" => "kas",//kas setara kas
////                    //         "4" => "penjualan",//penjualan
////                    //         "2030090" => "ppn_gunggungan",//ppn gunggunan
////                    //     ),
////                    //     "static" => array(
////                    //         "cabang_id" => "cabang_id",
////                    //         "jenis" => "jenisTr",
////                    //         // "transaksi_no" => "nomer",
////                    //         "toko_id" => "toko_id",
////                    //         "toko_nama" => "toko_nama",
////                    //         "transaksi_no" => "nomer",
////                    //         "transaksi_id" => "transaksi_id",
////                    //     ),
////                    //     "srcGateName" => "main",
////                    //     "srcRawGateName" => "main",
////                    // ),
////                    // array(
////                    //     "comName" => "Rekening",
////                    //     "loop" => array(
////                    //         "1010010" => "kas",//kas setara kas
////                    //         "4" => "penjualan",//penjualan
////                    //         "2030090" => "ppn_gunggungan",//ppn gunggunan
////                    //     ),
////                    //     "static" => array(
////                    //         "cabang_id" => "cabang_id",
////                    //         "jenis" => "jenisTr",
////                    //         // "transaksi_no" => "nomer",
////                    //         "toko_id" => "toko_id",
////                    //         "toko_nama" => "toko_nama",
////                    //         "transaksi_no" => "nomer",
////                    //         "transaksi_id" => "transaksi_id",
////                    //     ),
////                    //     "srcGateName" => "main",
////                    //     "srcRawGateName" => "main",
////                    // ),
////                    // //rekening pembantu kas/ setara kas
////                    // array(
////                    //     "comName" => "RekeningPembantuKasSetarakas",
////                    //     "loop" => array(
////                    //         "1010010" => "kas", // kas
////                    //     ),
////                    //     "static" => array(
////                    //         "toko_id" => "toko_id",
////                    //         "cabang_id" => "cabang_id",
////                    //         "extern_id" => ".1010010",
////                    //         "extern_nama" => ".kas setara kas",
////                    //         "jenis" => "jenisTr",
////                    //         "produk_id" => ".1010010010",
////                    //         "produk_nama" => ".kas",
////                    //         "produk_nilai" => "kas",
////                    //         "transaksi_no" => "nomer",
////                    //         "transaksi_id" => "transaksi_id",
////                    //     ),
////                    //     "srcGateName" => "main",
////                    //     "srcRawGateName" => "main",
////                    // ),
////                    // //rekening pembantu penjualan
////                    // array(
////                    //     "comName" => "RekeningPembantuPenjualan",
////                    //     "loop" => array(
////                    //         "4" => "penjualan",//penjualan
////                    //     ),
////                    //     "static" => array(
////                    //         "toko_id" => "toko_id",
////                    //         "cabang_id" => "cabang_id",
////                    //         "extern_id" => ".4010",
////                    //         "extern_nama" => ".lokal",
////                    //         "extern2_id" => ".0",//lihat coa untuk urutannya
////                    //         "extern2_nama" => ".0",
////                    //         "extern3_id" => ".0",
////                    //         "extern3_nama" => ".0",
////                    //         "extern4_id" => ".0",
////                    //         "extern4_nama" => ".0",
////                    //         "jenis" => "jenisTr",
////                    //         "produk_id" => ".4010010",
////                    //         "produk_nama" => ".lokal",
////                    //         "jml" => ".1",
////                    //         "harga" => "penjualan",
////                    //         "hpp" => "penjualan",
////                    //         "produk_nilai" => "penjualan",
////                    //         "oleh_id" => "oleh_id",
////                    //         "oleh_nama" => "oleh_nama",
////                    //         "pihak_id" => "pihak_id",
////                    //         "pihak_nama" => "pihak_nama",
////                    //         "oleh_top_id" => "oleh_top_id",
////                    //         "oleh_top_nama" => "oleh_top_nama",
////                    //         "transaksi_no" => "nomer",
////                    //         "transaksi_id" => "transaksi_id",
////                    //     ),
////                    //     "srcGateName" => "main",
////                    //     "srcRawGateName" => "main",
////                    // ),
////                    // //rekening pembantu kas
////                    // array(
////                    //     "comName" => "RekeningPembantuKas",
////                    //     "loop" => array(
////                    //         "1010010" => "kas",
////                    //     ),
////                    //     "static" => array(
////                    //         //                            "cabang_id" => "cabang_id",
////                    //         "cabang_id" => "cabang_id",
////                    //         "extern_id" => ".1010010010",
////                    //         "extern_nama" => ".kas",
////                    //         "produk_id" => "cash_account",
////                    //         "produk_nama" => "cash_account__nama",
////                    //         "produk_nilai" => "kas",
////                    //         "jenis" => "jenisTr",
////                    //         "toko_id" => "toko_id",
////                    //         "toko_nama" => "toko_nama",
////                    //         "oleh_id" => "oleh_id",
////                    //         "oleh_nama" => "oleh_nama",
////                    //         "transaksi_no" => "nomer",
////                    //         "transaksi_id" => "transaksi_id",
////                    //     ),
////                    //     "srcGateName" => "items7_sum",
////                    //     "srcRawGateName" => "items7_sum",
////                    // ),
////
////                    /*
////     * jurnal cabang point masuk
////     */
////                    array(
////                        "comName" => "Jurnal",
////                        "loop" => array(
////                            "4" => "penjualan_minus",//penjualan
////                            "2010050" => "diskon_penjualan",//hutang ke konsumen
////                            "1010030" => "-sub_hpp",//persediaan
////                            "5" => "sub_hpp",//hpp
////                            // "2030090" => "ppn_gunggungan",//ppn gunggunan
////                            // // hutang ke kosnumen
////                            // "2010050" => "hutang_ke_konsumen",
//////                             "1010070" => "piutang_kasir",//piutang kasir
////                            // "2010050"=>"",
////
////                        ),
////                        "static" => array(
////                            "cabang_id" => "cabang_id",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                            "transaksi_id" => "transaksi_id",
////                            "toko_id" => "toko_id",
////                            "toko_nama" => "toko_nama",
////
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "Rekening",
////                        "loop" => array(
////                            "4" => "penjualan_minus",//penjualan
////                            "2010050" => "diskon_penjualan",//hutang ke konsumen
////                            "1010030" => "-sub_hpp",//persediaan
////                            "5" => "sub_hpp",//hpp
////                            // "2030090" => "ppn_gunggungan",//ppn gunggunan
////                            // // hutang ke kosnumen
////                            // "2010050" => "hutang_ke_konsumen",
//////                             "1010070" => "piutang_kasir",//piutang kasir
////                            // "2010050"=>"",
////
////                        ),
////                        "static" => array(
////                            "cabang_id" => "cabang_id",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                            "transaksi_id" => "transaksi_id",
////                            "toko_id" => "toko_id",
////                            "toko_nama" => "toko_nama",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    //rekening pembantu hutang ke konsumen  lv 1
////                    array(
////                        "comName" => "RekeningPembantuHutangKeKonsumen",
////                        "loop" => array(
////                            "2010050" => "diskon_penjualan",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "cabang_id",
////                            "toko_id" => "toko_id",
////                            "extern_id" => ".2010050",
////                            "extern_nama" => ".hutang ke konsumen",
////                            "produk_id" => ".2010050030",
////                            "produk_nama" => ".point",
////                            // "produk_qty"  => ".1",
////                            "produk_nilai" => "diskon_penjualan",
////                            "jenis" => "jenisTr",
////                            "toko_nama" => "toko_nama",
////                            "oleh_id" => "oleh_id",
////                            "oleh_nama" => "oleh_nama",
////                            "transaksi_no" => "nomer",
////                            "transaksi_id" => "transaksi_id",
////
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    //rekening pembantu penjualan
////                    array(
////                        "comName" => "RekeningPembantuPenjualan",
////                        "loop" => array(
////                            "4" => "penjualan_minus",//penjualan
////                        ),
////                        "static" => array(
////                            "toko_id" => "toko_id",
////                            "cabang_id" => "cabang_id",
////                            "extern_id" => ".4010",
////                            "extern_nama" => ".lokal",
////                            "extern2_id" => ".0",//lihat coa untuk urutannya
////                            "extern2_nama" => ".0",
////                            "extern3_id" => ".0",
////                            "extern3_nama" => ".0",
////                            "extern4_id" => ".0",
////                            "extern4_nama" => ".0",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                            "produk_id" => ".4010030",
////                            "produk_nama" => ".point",
////                            "jml" => ".-1",
////                            "harga" => "hpp_point_satuan",
////                            "hpp" => "hpp_point_satuan",
////                            "produk_nilai" => "hpp_point_satuan",
////                            "oleh_id" => "oleh_id",
////                            "oleh_nama" => "oleh_nama",
////                            "pihak_id" => "customers_id",
////                            "pihak_nama" => "customers_nama",
////                            "oleh_top_id" => "oleh_id",
////                            "oleh_top_nama" => "oleh_nama",
////                            "transaksi_id" => "transaksi_id",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////
////                    /*
////                     * jurnal cabang point out ke pusat
////                     */
////                    array(
////                        "comName" => "Jurnal",
////                        "loop" => array(
////                            "2010050" => "penjualan_minus",//hutang ke konsumen
////                            "2040010" => "diskon_penjualan",//hutang ke pusat
////                        ),
////                        "static" => array(
////                            "toko_id" => "toko_id",
////                            "cabang_id" => "cabang_id",
////                            "gudang_id" => "gudang_id",
////                            "transaksi_no" => "nomer",
////                            "transaksi_id" => "transaksi_id",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////
////                    ),
////                    array(
////                        "comName" => "Rekening",
////                        "loop" => array(
////                            "2010050" => "penjualan_minus",//hutang ke konsumen
////                            "2040010" => "diskon_penjualan",//hutang ke pusat
////                        ),
////                        "static" => array(
////                            "toko_id" => "toko_id",
////                            "cabang_id" => "cabang_id",
////                            "gudang_id" => "gudang_id",
////                            "transaksi_no" => "nomer",
////                            "transaksi_id" => "transaksi_id",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    // //rekening pembantu hutang ke konsumen  out lv 1
////                    array(
////                        "comName" => "RekeningPembantuHutangKeKonsumen",
////                        "loop" => array(
////                            "2010050" => "penjualan_minus",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "cabang_id",
////                            "toko_id" => "toko_id",
////                            "extern_id" => ".2010050",
////                            "extern_nama" => ".hutang ke konsumen",
////                            "produk_id" => ".2010050030",
////                            "produk_nama" => ".point",
////                            "produk_nilai" => "penjualan_minus",
////                            "jenis" => "jenisTr",
////                            "toko_nama" => "toko_nama",
////                            "oleh_id" => "oleh_id",
////                            "oleh_nama" => "oleh_nama",
////                            "transaksi_no" => "nomer",
////                            "transaksi_id" => "transaksi_id",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "RekeningPembantuAntarcabang",
////                        "loop" => array(
////                            "2040010" => "diskon_penjualan",
////                        ),
////                        "static" => array(
////                            "toko_id" => "toko_id",
////                            "cabang_id" => "cabang_id",
////                            "cabang2_id" => "cabang2_id",
////                            "cabang2_nama" => "cabang2_nama",
////                            "extern_id" => ".2040010",
////                            "extern_nama" => ".hutang kepusat",
////                            "produk_id" => "cabang2_id",
////                            "produk_nama" => ".pusat",
////                            "oleh_id" => "oleh_id",
////                            "oleh_name" => "oleh_nama",
////                            "transaksi_no" => "nomer",
////                            "transaksi_id" => "transaksi_id",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////
////                    /*
////                     * jurnal pusat
////                     */
////                    array(
////                        "comName" => "Jurnal",
////                        "loop" => array(
////                            "2010050" => "diskon_penjualan",//hutang ke konsumen
////                            "1010060010" => "diskon_penjualan",//piutang cabang
////                        ),
////                        "static" => array(
////                            "cabang_id" => "cabang2_id",
////                            "gudang_id" => "gudang2_id",
////                            "toko_id" => "toko_id",
////                            "transaksi_no" => "nomer",
////                            "transaksi_id" => "transaksi_id",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "Rekening",
////                        "loop" => array(
////                            "2010050" => "diskon_penjualan",//hutang ke konsumen
////                            "1010060010" => "diskon_penjualan",//piutang cabang
////                        ),
////                        "static" => array(
////                            "cabang_id" => "cabang2_id",
////                            "gudang_id" => "gudang2_id",
////                            "toko_id" => "toko_id",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    //rekening pembantu hutang ke konsumen  lv 1 PUSAT
////                    array(
////                        "comName" => "RekeningPembantuHutangKeKonsumen",
////                        "loop" => array(
////                            "2010050" => "diskon_penjualan",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "cabang2_id",
////                            "toko_id" => "toko_id",
////                            "extern_id" => ".2010050",
////                            "extern_nama" => ".hutang ke konsumen",
////                            "produk_id" => ".2010050030",
////                            "produk_nama" => ".point",
////                            "produk_nilai" => "diskon_penjualan",
////                            "harga" => "harga",
////                            "hpp" => "hpp",
////                            "jenis" => "jenisTr",
////                            "toko_nama" => "toko_nama",
////                            "oleh_id" => "oleh_id",
////                            "oleh_nama" => "oleh_nama",
////                            "transaksi_no" => "nomer",
////                            "transaksi_id" => "transaksi_id",
////
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    //rekening pembantu piutang cabang
////                    array(
////                        "comName" => "RekeningPembantuAntarcabang",
////                        "loop" => array(
////                            "1010060010" => "diskon_penjualan",
////                        ),
////                        "static" => array(
////                            "toko_id" => "toko_id",
////                            "cabang_id" => "cabang2_id",
////                            "cabang2_id" => "cabang_id",
////                            "cabang2_nama" => "cabang_nama",
////                            "extern_id" => ".1010060010",
////                            "extern_nama" => ".piutang cabang",
////                            "produk_id" => "cabang_id",
////                            "produk_nama" => "cabang_nama",
////                            "transaksi_no" => "nomer",
////                            "transaksi_id" => "transaksi_id",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    // //raw pembantu kas setara kas
////                    // array(
////                    //     "comName" => "RekeningPembantuRawMain",
////                    //     "loop" => array(
////                    //         "1010010" => "kas",//rekening pembelian untuk keperluan lap
////                    //     ),
////                    //     /*
////                    //      * untuk gerbang coa nantinya dibuat relative ya
////                    //      */
////                    //     "static" => array(
////                    //         //                            "toko_id" => "tokoID",
////                    //         //                            "cabang_id" => "placeID",
////                    //         "toko_id" => "toko_id",
////                    //         "cabang_id" => "cabang_id",
////                    //         "extern_id" => ".1010010010",//lokal ,non lokal
////                    //         "extern_nama" => ".kas",
////                    //         "extern2_id" => ".0",//lihat coa untuk urutannya
////                    //         "extern2_nama" => ".0",
////                    //         "extern3_id" => ".0",
////                    //         "extern3_nama" => ".0",
////                    //         "extern4_id" => ".0",
////                    //         "extern4_nama" => ".0",
////                    //         "jenis" => "jenisTr",
////                    //         "transaksi_no" => "nomer",
////                    //         "produk_id" => "cash_account",
////                    //         "produk_nama" => "cash_account__nama",
////                    //         "jml" => ".1",
////                    //         "harga" => "kas",
////                    //         "hpp" => "kas",
////                    //         "oleh_id" => "oleh_id",
////                    //         "oleh_nama" => "oleh_nama",
////                    //         "pihak_id" => ".0",
////                    //         "pihak_nama" => ".0",
////                    //         "oleh_top_id" => "oleh_id",
////                    //         "oleh_top_nama" => "oleh_nama",
////                    //     ),
////                    //     "srcGateName" => "items7_sum",
////                    //     "srcRawGateName" => "items7_sum",
////                    // ),
////
////                    //rekening pembantu hutang ke konsumen lv2 in cabang
////                    array(
////                        "comName" => "RekeningPembantuCustomer",
////                        "loop" => array(
////                            "2010050" => "hpp_point",//rekening pembantu hutang konsumen
////                        ),
////                        "static" => array(
////                            "cabang_id" => "cabang_id",
////                            "extern_id" => ".2010050",//hutan ke konsumen
////                            "extern_nama" => ".hutang ke konsumen",
////                            "extern2_id" => ".2010050030",//point
////                            "extern2_nama" => ".point",
////                            "produk_id" => "customers_id",//customer id
////                            "produk_nama" => "customers_nama",
////
////                            "qty" => "qty_point",
////                            "produk_qty" => "qty_point",
////                            "produk_nilai" => "hpp_point_satuan",
////                            "harga" => "harga",
////                            "hpp" => "hpp",
////                            "jenis" => "jenisTr",
////                            "toko_id" => "toko_id",
////                            "toko_nama" => "toko_nama",
////                            "oleh_id" => "oleh_id",
////                            "oleh_nama" => "oleh_nama",
////                            "transaksi_no" => "nomer",
////                            "transaksi_id" => "transaksi_id",
////                            // "transaksi_id" => "transaksi_id",
////                        ),
////                        "srcGateName" => "items8_sum",
////                        "srcRawGateName" => "items8_sum",
////                    ),
////                    //rekening pembantu hutang ke konsumen cabang lv2 out ke pusat
////                    array(
////                        "comName" => "RekeningPembantuCustomer",
////                        "loop" => array(
////                            "2010050" => "hpp_point_minus",//rekening pembantu hutang konsumen
////                        ),
////                        "static" => array(
////                            "cabang_id" => "cabang_id",
////                            "extern_id" => ".2010050",//hutan ke konsumen
////                            "extern_nama" => ".hutang ke konsumen",
////                            "extern2_id" => ".2010050030",//point
////                            "extern2_nama" => ".point",
////                            "produk_id" => "customers_id",//customer id
////                            "produk_nama" => "customers_nama",
////                            "produk_qty" => "qty_penjualan_minus",
////                            "qty" => "qty_penjualan_minus",
////                            "produk_nilai" => "hpp_point_satuan",
////                            "jenis" => "jenisTr",
////                            "toko_id" => "toko_id",
////                            "toko_nama" => "toko_nama",
////                            "oleh_id" => "oleh_id",
////                            "oleh_nama" => "oleh_nama",
////                            "transaksi_no" => "nomer",
////                            "transaksi_id" => "transaksi_id",
////                            "harga" => "harga",
////                            "hpp" => "hpp",
////                            // "transaksi_id" => "transaksi_id",
////                        ),
////                        "srcGateName" => "items8_sum",
////                        "srcRawGateName" => "items8_sum",
////                    ),
////                    //raw pembantu penjualan(diskon penjulan) cabang aka point
////                    array(
////                        "comName" => "RekeningPembantuRawMain",
////                        "loop" => array(
////                            "4" => "penjualan_minus",//rekening pembelian untuk keperluan lap
////                        ),
////                        /*
////                         * untuk gerbang coa nantinya dibuat relative ya
////                         */
////                        "static" => array(
////                            "toko_id" => "toko_id",
////                            "cabang_id" => "cabang_id",
////                            "extern_id" => ".4010",//lokal ,non lokal
////                            "extern_nama" => ".lokal",
////                            "extern2_id" => ".4010030",//lihat coa untuk urutannya
////                            "extern2_nama" => ".diskon penjualan",
////                            "extern3_id" => ".0",
////                            "extern3_nama" => ".0",
////                            "extern4_id" => ".0",
////                            "extern4_nama" => ".0",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                            "produk_id" => "customers_id",//customer id
////                            "produk_nama" => "customers_nama",
////                            "jml" => ".1",
////
////                            "harga" => "hpp_point_satuan",
////                            "hpp" => "hpp_point_satuan",
////                            "oleh_id" => "oleh_id",
////                            "oleh_nama" => "oleh_nama",
////                            "pihak_id" => "customer_id",
////                            "pihak_nama" => "customer_nama",
////                            "oleh_top_id" => "oleh_id",
////                            "oleh_top_nama" => "oleh_nama",
////                            "satuan_id" => "satuan_id",
////                            "satuan_nama" => "satuan",
////
////                            "transaksi_id" => "transaksi_id",
////                        ),
////                        "srcGateName" => "items8_sum",
////                        "srcRawGateName" => "items8_sum",
////                    ),
////
////                    //rekening pembantu hutang ke konsumen lv2 in PUSAT
////                    array(
////                        "comName" => "RekeningPembantuCustomer",
////                        "loop" => array(
////                            "2010050" => "hpp_point",//rekening pembantu hutang konsumen
////                        ),
////                        "static" => array(
////                            "cabang_id" => "cabang2_id",
////                            "extern_id" => ".2010050",//hutan ke konsumen
////                            "extern_nama" => ".hutang ke konsumen",
////                            "extern2_id" => ".2010050030",//point
////                            "extern2_nama" => ".point",
////                            "produk_id" => "customers_id",//customer id
////                            "produk_nama" => "customers_nama",
////                            // "produk_nilai" => "subtotal",
////                            "produk_qty" => "qty_point",
////                            "qty" => "qty_point",
////                            "produk_nilai" => "hpp_point_satuan",
////                            "jenis" => "jenisTr",
////                            "toko_id" => "toko_id",
////                            "toko_nama" => "toko_nama",
////                            "oleh_id" => "oleh_id",
////                            "oleh_nama" => "oleh_nama",
////                            "transaksi_no" => "nomer",
////                            "transaksi_id" => "transaksi_id",
////                            // "transaksi_id" => "transaksi_id",
////                        ),
////                        "srcGateName" => "items8_sum",
////                        "srcRawGateName" => "items8_sum",
////                    ),
////
////                    //rekening pembantu persediaan
////                    array(
////                        "comName" => "RekeningPembantuPersediaan",
////                        "loop" => array(
////                            "1010030" => "-sub_hpp", // persediaan
////                        ),
////                        "static" => array(
////                            "toko_id" => "toko_id",
////                            "cabang_id" => "cabang_id",
////                            "gudang_id" => "gudang_id",
////                            "extern_id" => ".1010030030",//produk
////                            "extern_nama" => ".persediaan produk",
////                            "extern2_id" => ".0",//lihat coa untuk urutannya
////                            "extern2_nama" => ".0",
////                            "extern3_id" => ".0",
////                            "extern3_nama" => ".0",
////                            "extern4_id" => ".0",
////                            "extern4_nama" => ".0",
////                            "jenis" => "jenisTr",
////                            "transaksi_id" => "transaksi_id",
////                            "transaksi_no" => "transaksi_no",
////                            "produk_id" => ".1010030030",
////                            "produk_nama" => ".persediaan produk",
////                            "jml" => ".1",
////                            "harga" => "hpp",
////                            "hpp" => "hpp",
////                            "produk_nilai" => "hpp",
////                            "oleh_id" => "oleh_id",
////                            "oleh_nama" => "oleh_nama",
////                            "pihak_id" => "oleh_id",
////                            "pihak_nama" => "oleh_nama",
////                            "oleh_top_id" => "oleh_id",
////                            "oleh_top_nama" => "oleh_nama",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "RekeningPembantuHpp",
////                        "loop" => array(
////                            "5" => "sub_hpp", // hpp
////                        ),
////                        "static" => array(
////                            "toko_id" => "toko_id",
////                            "cabang_id" => "cabang_id",
////                            "gudang_id" => "gudang_id",
////                            "extern_id" => ".5010",//
////                            "extern_nama" => ".harga pokok penjualan",
////                            "extern2_id" => ".0",//lihat coa untuk urutannya
////                            "extern2_nama" => ".0",
////                            "extern3_id" => ".0",
////                            "extern3_nama" => ".0",
////                            "extern4_id" => ".0",
////                            "extern4_nama" => ".0",
////                            "jenis" => "jenisTr",
////                            "produk_id" => ".5010010",
////                            "produk_nama" => ".lokal",
////                            "jml" => ".1",
////                            "harga" => "hpp",
////                            "hpp" => "hpp",
////                            "produk_nilai" => "hpp",
////                            "oleh_id" => "oleh_id",
////                            "oleh_nama" => "oleh_nama",
////                            "pihak_id" => "oleh_id",
////                            "pihak_nama" => "oleh_nama",
////                            "oleh_top_id" => "oleh_id",
////                            "oleh_top_nama" => "oleh_nama",
////                            "transaksi_id" => "transaksi_id",
////                            "transaksi_no" => "transaksi_no",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                ),
////                "detail" => array(
////                    //raw pembantu penjualan numpang langsung nulis raw
////                    array(
////                        "comName" => "RekeningPembantuProduk",
////                        "loop" => array(
////                            "1010030" => "-sub_hpp",
////                        ),
////                        "static" => array(
////                            "toko_id" => "toko_id",
////                            "cabang_id" => "cabang_id",
////                            "extern_id" => ".1010030030",
////                            "extern_nama" => ".persediaan produk",
////                            "produk_qty" => "-jml_dipakai",
////                            "qty_ditunda" => "jml_ditunda",
////                            //                            "produk_nilai" => "harga",//harga jual asli
////                            "produk_nilai" => "hpp",//hpp produk yang terjual
////                            "gudang_id" => "gudang_id",
////                            "jenis" => ".582",
////                            "extern2_id" => ".0",//lihat coa untuk urutannya
////                            "extern2_nama" => ".0",
////                            "extern3_id" => ".0",
////                            "extern3_nama" => ".0",
////                            "extern4_id" => ".0",
////                            "extern4_nama" => ".0",
////                            "produk_id" => "produk_id",
////                            "produk_nama" => "produk_nama",
////                            "jml" => "jml_dipakai",
////                            //                            "harga" => "harga_avg",//harga rata rata karena penjumlahan
////                            "harga" => "hpp",//harga rata rata karena penjumlahan
////                            "harga_jual" => "harga_jual",//harga rata rata karena penjumlahan
////                            "hpp" => "hpp",
////                            "oleh_id" => "oleh_id",
////                            "oleh_nama" => "oleh_nama",
////                            "pihak_id" => "oleh_id",
////                            "pihak_nama" => "oleh_nama",
////                            "oleh_top_id" => "oleh_id",
////                            "oleh_top_nama" => "oleh_nama",
////                            "satuan_id" => "satuan_id",
////                            "satuan_nama" => "satuan",
////                            "rugilaba" => "harga_jual-hpp",
////                            "transaksi_id" => "transaksi_id",
////                            "transaksi_no" => "transaksi_no",
////                        ),
////                        "srcGateName" => "rsltItems",
////                        "srcRawGateName" => "rsltItems",
////                    ),
////
////                    array(
////                        "comName" => "RekeningPembantuRaw",
////                        "loop" => array(
////                            "5" => "sub_hpp",//rekening pembelian untuk keperluan lap
////                        ),
////                        /*
////                         * untuk gerbang coa nantinya dibuat relative ya
////                         */
////                        "static" => array(
////                            "toko_id" => "toko_id",
////                            "cabang_id" => "cabang_id",
////                            "extern_id" => ".5010",//hpp penjualan
////                            "extern_nama" => ".penjualan",
////                            "extern2_id" => ".5010010",//lihat coa untuk urutannya
////                            "extern2_nama" => ".penjualan",
////                            "extern3_id" => ".0",
////                            "extern3_nama" => ".0",
////                            "extern4_id" => ".0",
////                            "extern4_nama" => ".0",
////                            "jenis" => ".758",
////                            "produk_id" => "produk_id",
////                            "produk_nama" => "produk_nama",
////                            "jml" => "qty",
////                            "harga" => "harga",
////                            "hpp" => "hpp",
////                            "oleh_id" => "oleh_id",
////                            "oleh_nama" => "oleh_nama",
////                            "pihak_id" => "oleh_id",
////                            "pihak_nama" => "oleh_nama",
////                            "oleh_top_id" => "oleh_id",
////                            "oleh_top_nama" => "oleh_nama",
////                            "satuan_id" => "satuan_id",
////                            "transaksi_id" => "transaksi_id",
////                            "transaksi_no" => "transaksi_no",
////                            "satuan_nama" => "satuan",
////                        ),
////                        "srcGateName" => "items9_sum",
////                        "srcRawGateName" => "items9_sum",
////                    ),
////
////                    //bagian locker active
////                    array(
////                        "comName" => "LockerStock",
////                        "loop" => array(),
////                        "static" => array(
////                            "cabang_id" => "cabang_id",
////                            "jenis" => ".produk",
////                            "state" => ".active",
////                            "jumlah" => "-jml_dipakai",
////                            "produk_id" => "produk_id",
////                            "nama" => "produk_nama",
////                            "satuan" => "satuan",
////                            "transaksi_id" => ".0",
////                            "oleh_id" => ".0",
////                            "gudang_id" => "gudang_id",
////                            "toko_id" => "toko_id",
////                        ),
////                        "srcGateName" => "items9_sum",
////                        "srcRawGateName" => "items9_sum",
////                    ),
////
////                    array(
////                        "comName" => "LockerStockMutasi",
////                        "loop" => array(),
////                        "static" => array(
////                            "cabang_id" => "cabang_id",
////                            "extern_id" => "produk_id",
////                            "extern_nama" => "extern_nama",
////                            "qty_debet" => "-jml_dipakai",
////                            "produk_nilai" => "hpp",
////                            "gudang_id" => "gudang_id",
////                            "jenis" => ".758",
////                            "toko_id" => "toko_id",
////                        ),
////                        "reversable" => true,
////                        "srcGateName" => "items9_sum",
////                        "srcRawGateName" => "items9_sum",
////
////                    ),
////                ),
//                );
                switch ($jenisTr) {
//                    case "5822":
//                        $postproc = array(
//                            "master" => array(),
//                            "detail" => array(
//                                array(
//                                    "comName" => "RekeningPembantuRaw",
//                                    "loop" => array(
//                                        "4010" => "sub_nett1",//rekening pembelian untuk keperluan lap
//                                    ),
//                                    "static" => array(
//                                        "cabang_id" => "cabangID",
//                                        "cabang_nama" => "cabangName",
//                                        "extern_id" => ".4010",//lokal ,non lokal
//                                        "extern_nama" => ".penjualan",
//                                        "extern2_id" => ".4010010",//lihat coa untuk urutannya
//                                        "extern2_nama" => ".lokal",
////                            "extern3_id" => ".0",
////                            "extern3_nama" => "machine_id",//diisi machinid
////                            "extern4_id" => ".0",
////                            "extern4_nama" => ".0",
//                                        "jenis" => "tr__jenis",
//                                        "transaksi_id" => "tr__id",
//                                        "transaksi_no" => "tr__nomer",
//                                        "produk_id" => "id",
//                                        "produk_nama" => "nama",
//                                        "produk_kode" => "produk_kode",
//                                        "produk_jenis" => "jenis",
//                                        "barcode" => "barcode",
//                                        "jml" => "jml",
//                                        "harga" => "nett1",// harga dpp
//                                        "hpp" => "hpp",// hpp produk
//                                        "harga_include_ppn" => "harga_include_ppn",// harga include ppn
//                                        "sub_harga" => "sub_nett1",// harga dpp
//                                        "sub_hpp" => "sub_hpp",// hpp produk
//                                        "sub_harga_include_ppn" => "sub_harga_include_ppn",// harga include ppn
//                                        "oleh_id" => "tr__oleh_id",
//                                        "oleh_nama" => "tr__oleh_nama",
//                                        "pihak_id" => "pihakID",// konsumen
//                                        "pihak_nama" => "pihakName", // konsumen
//                                        "oleh_top_id" => "oleh_top_id",
//                                        "oleh_top_nama" => "oleh_top_nama",
//                                        "satuan_id" => "satuan_id",
//                                        "satuan_nama" => "satuan_nama",
//                                        "rugilaba" => "rugilaba",
//                                        "master_id" => "tr__id_master",
////                                        "diskon" => "discNilai",
////                                        "diskon_persen" => "discPersen",
//                                        "diskon" => "disc",
//                                        "diskon_persen" => "disc_percent",
//                                        "sub_diskon" => "sub_disc",
//                                        "sub_diskon_persen" => "sub_disc_percent",
//                                        //----------------
//                                        "outdoor_id" => "outdoor_id",
//                                        "outdoor_nama" => "outdoor_nama",
//                                        "outdoor_barcode" => "outdoor_barcode",
//                                        "outdoor_sku" => "outdoor_sku",
//                                        "indoor_id_1" => "indoor_id_1",
//                                        "indoor_nama_1" => "indoor_nama_1",
//                                        "indoor_barcode_1" => "indoor_barcode_1",
//                                        "indoor_sku_1" => "indoor_sku_1",
//                                        "indoor_id_2" => "indoor_id_2",
//                                        "indoor_nama_2" => "indoor_nama_2",
//                                        "indoor_barcode_2" => "indoor_barcode_2",
//                                        "indoor_sku_2" => "indoor_sku_2",
//                                        "indoor_id_3" => "indoor_id_3",
//                                        "indoor_nama_3" => "indoor_nama_3",
//                                        "indoor_barcode_3" => "indoor_barcode_3",
//                                        "indoor_sku_3" => "indoor_sku_3",
//                                        "indoor_id_4" => "indoor_id_4",
//                                        "indoor_nama_4" => "indoor_nama_4",
//                                        "indoor_barcode_4" => "indoor_barcode_4",
//                                        "indoor_sku_4" => "indoor_sku_4",
//                                        "kategori_id" => "kategori_id",
//                                        "kategori_nama" => "kategori_nama",
//                                        "produk_part_id_1" => "part_id_1",
//                                        "produk_part_nama_1" => "part_nama_1",
//                                        "produk_part_barcode_1" => "part_barcode_1",
//                                        "produk_part_id_2" => "part_id_2",
//                                        "produk_part_nama_2" => "part_nama_2",
//                                        "produk_part_barcode_2" => "part_barcode_2",
//                                        "heater_id" => "heater_id",
//                                        "heater_nama" => "heater_nama",
//                                        "heater_barcode" => "heater_barcode",
//                                        //----------------
//                                        "sales_admin_id" => "main__sellerID",
//                                        "sales_admin_nama" => "main__sellerName",
//                                        "salesman_id" => "main__salesmanDetails",
//                                        "salesman_nama" => "main__salesmanDetails__nama",
//                                        "gudang_id_kirim" => "main__gudangStatusDetails",
//                                        "gudang_nama_kirim" => "main__gudangStatusDetails__nama",
//                                        "delivery_id" => "main__shippingMethod",
//                                        "delivery_nama" => "main__shippingMethod__name",
//                                        "pengirim_id" => "main__pengirimID",
//                                        "pengirim_nama" => "main__pengirimName",
//                                        "pembayaran_nama" => "main__paymentMethod",
//                                        //----------------
//                                        "transaksi_id_1" => "tr__transaksi_id_1",
//                                        "transaksi_no_1" => "tr__transaksi_no_1",
//                                        "transaksi_id_2" => "tr__transaksi_id_2",
//                                        "transaksi_no_2" => "tr__transaksi_no_2",
//                                        "transaksi_id_3" => "tr__transaksi_id_3",
//                                        "transaksi_no_3" => "tr__transaksi_no_3",
//                                        "transaksi_id_4" => "tr__transaksi_id_4",
//                                        "transaksi_no_4" => "tr__transaksi_no_4",
//                                        "transaksi_id_5" => "tr__transaksi_id_5",
//                                        "transaksi_no_5" => "tr__transaksi_no_5",
//                                        //----------------
//                                        "transaksi_nilai" => "new_net3",
//                                        "ppn_nilai" => "ppn_nilai",
//                                        "sub_ppn_nilai" => "sub_ppn_nilai",
//                                    ),
//                                    "srcGateName" => "items",
//                                    "srcRawGateName" => "items",
//                                ),
//
//                            ),
//                        );
//                        break;
//                    case "9822":
//                        $postproc = array(
//                            "master" => array(),
//                            "detail" => array(
//                                array(
//                                    "comName" => "RekeningPembantuRaw",
//                                    "loop" => array(
//                                        "4010" => "-sub_nett1",//rekening pembelian untuk keperluan lap
//                                    ),
//                                    "static" => array(
//                                        "cabang_id" => "cabangID",
//                                        "cabang_nama" => "cabangName",
//                                        "extern_id" => ".4020",//lokal ,non lokal
//                                        "extern_nama" => ".return penjualan",
//                                        "extern2_id" => ".4020010",//lihat coa untuk urutannya
//                                        "extern2_nama" => ".lokal",
////                            "extern3_id" => ".0",
////                            "extern3_nama" => "machine_id",//diisi machinid
////                            "extern4_id" => ".0",
////                            "extern4_nama" => ".0",
//                                        "jenis" => "tr__jenis",
//                                        "transaksi_id" => "tr__id",
//                                        "transaksi_no" => "tr__nomer",
//                                        "produk_id" => "id",
//                                        "produk_nama" => "nama",
//                                        "produk_kode" => "produk_kode",
//                                        "produk_jenis" => "jenis",
//                                        "barcode" => "barcode",
//                                        "jml" => "-jml",
//                                        "harga" => "nett1",// harga dpp
//                                        "hpp" => "hpp",// hpp produk
//                                        "harga_include_ppn" => "harga_include_ppn",// harga include ppn
//                                        "sub_harga" => "sub_nett1",// harga dpp
//                                        "sub_hpp" => "sub_hpp",// hpp produk
//                                        "sub_harga_include_ppn" => "sub_harga_include_ppn",// harga include ppn
//                                        "oleh_id" => "tr__oleh_id",
//                                        "oleh_nama" => "tr__oleh_nama",
//                                        "pihak_id" => "pihakID",// konsumen
//                                        "pihak_nama" => "pihakName", // konsumen
//                                        "oleh_top_id" => "oleh_top_id",
//                                        "oleh_top_nama" => "oleh_top_nama",
//                                        "satuan_id" => "satuan_id",
//                                        "satuan_nama" => "satuan_nama",
//                                        "rugilaba" => "rugilaba",
//                                        "master_id" => "tr__id_master",
////                                        "diskon" => "discNilai",
////                                        "diskon_persen" => "discPersen",
//                                        "diskon" => "disc",
//                                        "diskon_persen" => "disc_percent",
//                                        "sub_diskon" => "sub_disc",
//                                        "sub_diskon_persen" => "sub_disc_percent",
//                                        //----------------
//                                        "outdoor_id" => "outdoor_id",
//                                        "outdoor_nama" => "outdoor_nama",
//                                        "outdoor_barcode" => "outdoor_barcode",
//                                        "outdoor_sku" => "outdoor_sku",
//                                        "indoor_id_1" => "indoor_id_1",
//                                        "indoor_nama_1" => "indoor_nama_1",
//                                        "indoor_barcode_1" => "indoor_barcode_1",
//                                        "indoor_sku_1" => "indoor_sku_1",
//                                        "indoor_id_2" => "indoor_id_2",
//                                        "indoor_nama_2" => "indoor_nama_2",
//                                        "indoor_barcode_2" => "indoor_barcode_2",
//                                        "indoor_sku_2" => "indoor_sku_2",
//                                        "indoor_id_3" => "indoor_id_3",
//                                        "indoor_nama_3" => "indoor_nama_3",
//                                        "indoor_barcode_3" => "indoor_barcode_3",
//                                        "indoor_sku_3" => "indoor_sku_3",
//                                        "indoor_id_4" => "indoor_id_4",
//                                        "indoor_nama_4" => "indoor_nama_4",
//                                        "indoor_barcode_4" => "indoor_barcode_4",
//                                        "indoor_sku_4" => "indoor_sku_4",
//                                        "kategori_id" => "kategori_id",
//                                        "kategori_nama" => "kategori_nama",
//                                        "produk_part_id_1" => "part_id_1",
//                                        "produk_part_nama_1" => "part_nama_1",
//                                        "produk_part_barcode_1" => "part_barcode_1",
//                                        "produk_part_id_2" => "part_id_2",
//                                        "produk_part_nama_2" => "part_nama_2",
//                                        "produk_part_barcode_2" => "part_barcode_2",
//                                        "heater_id" => "heater_id",
//                                        "heater_nama" => "heater_nama",
//                                        "heater_barcode" => "heater_barcode",
//                                        //----------------
//                                        "sales_admin_id" => "main__sellerID",
//                                        "sales_admin_nama" => "main__sellerName",
//                                        "salesman_id" => "main__salesmanDetails",
//                                        "salesman_nama" => "main__salesmanDetails__nama",
//                                        "gudang_id_kirim" => "main__gudangStatusDetails",
//                                        "gudang_nama_kirim" => "main__gudangStatusDetails__nama",
//                                        "delivery_id" => "main__shippingMethod",
//                                        "delivery_nama" => "main__shippingMethod__name",
//                                        "pengirim_id" => "main__pengirimID",
//                                        "pengirim_nama" => "main__pengirimName",
//                                        "pembayaran_nama" => "main__paymentMethod",
//                                        //----------------
//                                        "transaksi_id_1" => "tr__transaksi_id_1",
//                                        "transaksi_no_1" => "tr__transaksi_no_1",
//                                        "transaksi_id_2" => "tr__transaksi_id_2",
//                                        "transaksi_no_2" => "tr__transaksi_no_2",
//                                        "transaksi_id_3" => "tr__transaksi_id_3",
//                                        "transaksi_no_3" => "tr__transaksi_no_3",
//                                        "transaksi_id_4" => "tr__transaksi_id_4",
//                                        "transaksi_no_4" => "tr__transaksi_no_4",
//                                        "transaksi_id_5" => "tr__transaksi_id_5",
//                                        "transaksi_no_5" => "tr__transaksi_no_5",
//                                        //----------------
//                                        "transaksi_nilai" => "new_net3",
//                                        "ppn_nilai" => "ppn_nilai",
//                                        "sub_ppn_nilai" => "sub_ppn_nilai",
//                                    ),
//                                    "srcGateName" => "items",
//                                    "srcRawGateName" => "items",
//                                ),
//
//                            ),
//                        );
//                        break;
//                    case "4822":
//                        $postproc = array(
//                            "master" => array(),
//                            "detail" => array(
//                                array(
//                                    "comName" => "RekeningPembantuRaw",
//                                    "loop" => array(
//                                        "4010" => "sub_nett1",//rekening pembelian untuk keperluan lap
//                                    ),
//                                    "static" => array(
//                                        "cabang_id" => "cabangID",
//                                        "cabang_nama" => "cabangName",
//                                        "extern_id" => ".4010",//lokal ,non lokal
//                                        "extern_nama" => ".penjualan",
//                                        "extern2_id" => ".4010010",//lihat coa untuk urutannya
//                                        "extern2_nama" => ".lokal",
////                            "extern3_id" => ".0",
////                            "extern3_nama" => "machine_id",//diisi machinid
////                            "extern4_id" => ".0",
////                            "extern4_nama" => ".0",
//                                        "jenis" => "tr__jenis",
//                                        "transaksi_id" => "tr__id",
//                                        "transaksi_no" => "tr__nomer",
//                                        "produk_id" => "id",
//                                        "produk_nama" => "nama",
//                                        "produk_kode" => "produk_kode",
//                                        "produk_jenis" => "jenis",
//                                        "barcode" => "barcode",
//                                        "jml" => "jml",
//                                        "harga" => "nett1",// harga dpp
//                                        "hpp" => "hpp",// hpp produk
//                                        "harga_include_ppn" => "harga_include_ppn",// harga include ppn
//                                        "sub_harga" => "sub_nett1",// harga dpp
//                                        "sub_hpp" => "sub_hpp",// hpp produk
//                                        "sub_harga_include_ppn" => "sub_harga_include_ppn",// harga include ppn
//                                        "oleh_id" => "tr__oleh_id",
//                                        "oleh_nama" => "tr__oleh_nama",
//                                        "pihak_id" => "pihakID",// konsumen
//                                        "pihak_nama" => "pihakName", // konsumen
//                                        "oleh_top_id" => "oleh_top_id",
//                                        "oleh_top_nama" => "oleh_top_nama",
//                                        "satuan_id" => "satuan_id",
//                                        "satuan_nama" => "satuan_nama",
//                                        "rugilaba" => "rugilaba",
//                                        "master_id" => "tr__id_master",
////                                        "diskon" => "discNilai",
////                                        "diskon_persen" => "discPersen",
//                                        "diskon" => "disc",
//                                        "diskon_persen" => "disc_percent",
//                                        "sub_diskon" => "sub_disc",
//                                        "sub_diskon_persen" => "sub_disc_percent",
//                                        //----------------
//                                        "outdoor_id" => "outdoor_id",
//                                        "outdoor_nama" => "outdoor_nama",
//                                        "outdoor_barcode" => "outdoor_barcode",
//                                        "outdoor_sku" => "outdoor_sku",
//                                        "indoor_id_1" => "indoor_id_1",
//                                        "indoor_nama_1" => "indoor_nama_1",
//                                        "indoor_barcode_1" => "indoor_barcode_1",
//                                        "indoor_sku_1" => "indoor_sku_1",
//                                        "indoor_id_2" => "indoor_id_2",
//                                        "indoor_nama_2" => "indoor_nama_2",
//                                        "indoor_barcode_2" => "indoor_barcode_2",
//                                        "indoor_sku_2" => "indoor_sku_2",
//                                        "indoor_id_3" => "indoor_id_3",
//                                        "indoor_nama_3" => "indoor_nama_3",
//                                        "indoor_barcode_3" => "indoor_barcode_3",
//                                        "indoor_sku_3" => "indoor_sku_3",
//                                        "indoor_id_4" => "indoor_id_4",
//                                        "indoor_nama_4" => "indoor_nama_4",
//                                        "indoor_barcode_4" => "indoor_barcode_4",
//                                        "indoor_sku_4" => "indoor_sku_4",
//                                        "kategori_id" => "kategori_id",
//                                        "kategori_nama" => "kategori_nama",
//                                        "produk_part_id_1" => "part_id_1",
//                                        "produk_part_nama_1" => "part_nama_1",
//                                        "produk_part_barcode_1" => "part_barcode_1",
//                                        "produk_part_id_2" => "part_id_2",
//                                        "produk_part_nama_2" => "part_nama_2",
//                                        "produk_part_barcode_2" => "part_barcode_2",
//                                        "heater_id" => "heater_id",
//                                        "heater_nama" => "heater_nama",
//                                        "heater_barcode" => "heater_barcode",
//                                        //----------------
//                                        "sales_admin_id" => "main__sellerID",
//                                        "sales_admin_nama" => "main__sellerName",
//                                        "salesman_id" => "main__salesmanDetails",
//                                        "salesman_nama" => "main__salesmanDetails__nama",
//                                        "gudang_id_kirim" => "main__gudangStatusDetails",
//                                        "gudang_nama_kirim" => "main__gudangStatusDetails__nama",
//                                        "delivery_id" => "main__shippingMethod",
//                                        "delivery_nama" => "main__shippingMethod__name",
//                                        "pengirim_id" => "main__pengirimID",
//                                        "pengirim_nama" => "main__pengirimName",
//                                        "pembayaran_nama" => "main__paymentMethod",
//                                        //----------------
//                                        "transaksi_id_1" => "tr__transaksi_id_1",
//                                        "transaksi_no_1" => "tr__transaksi_no_1",
//                                        "transaksi_id_2" => "tr__transaksi_id_2",
//                                        "transaksi_no_2" => "tr__transaksi_no_2",
//                                        "transaksi_id_3" => "tr__transaksi_id_3",
//                                        "transaksi_no_3" => "tr__transaksi_no_3",
//                                        "transaksi_id_4" => "tr__transaksi_id_4",
//                                        "transaksi_no_4" => "tr__transaksi_no_4",
//                                        "transaksi_id_5" => "tr__transaksi_id_5",
//                                        "transaksi_no_5" => "tr__transaksi_no_5",
//                                        //----------------
//                                        "transaksi_nilai" => "new_net3",
//                                        "ppn_nilai" => "ppn_nilai",
//                                        "sub_ppn_nilai" => "sub_ppn_nilai",
//                                    ),
//                                    "srcGateName" => "items",
//                                    "srcRawGateName" => "items",
//                                ),
//
//                            ),
//                        );
//
////                        arrPrintPink($main);
//                        switch ($main["jenis_source"]) {
//                            case "4464":
//                                $tr = New MdlTransaksi();
//                                $tr->setFilters(array());
//                                $tr->addFilter("id=" . $main["refID"]);
//                                $trTmpx = $tr->lookupAll()->result();
//                                if (sizeof($trTmpx) > 0) {
//                                    $master_idd = $trTmpx[0]->id_master;
//                                    $where = array(
//                                        "master_id" => $master_idd,
//                                    );
//                                    $data = array(
//                                        "transaksi_id_inv" => $trTmp[0]->id,
//                                        "transaksi_no_inv" => $trTmp[0]->nomer,
//                                    );
//                                    $tbl = "__raw_rek_pembantu__4010";
//                                    $crr = New ComRekeningPembantuRaw();
//                                    $crr->setTableName($tbl);
//                                    $crr->updateData($where, $data);
//                                    showLast_query("orange");
//                                }
//
//                                break;
//                            default:
//                                $master_idd = $main["referenceID_1"];
//                                $where = array(
//                                    "master_id" => $master_idd,
//                                );
//                                $data = array(
//                                    "transaksi_id_inv" => $trTmp[0]->id,
//                                    "transaksi_no_inv" => $trTmp[0]->nomer,
//                                );
//                                $tbl = "__raw_rek_pembantu__4010";
//                                $crr = New ComRekeningPembantuRaw();
//                                $crr->setTableName($tbl);
//                                $crr->updateData($where, $data);
//                                showLast_query("orange");
////                                mati_disini(__LINE__ . " SETOP DULU...");
//                                break;
//                        }
//
//                        break;

                    case "1466":
                    case "1467":
                    case "466":
                    case "467":
                        $postproc = array(
                            "master" => array(),
                            "detail" => array(
                                array(
                                    "comName" => "RekeningPembantuRaw",
                                    "loop" => array(
                                        "1040" => "sub_harga",//rekening pembelian untuk keperluan lap
                                    ),
                                    "static" => array(
                                        "cabang_id" => "cabangID",
                                        "cabang_nama" => "cabangName",
                                        "extern_id" => ".1040",//produk ,non produk
                                        "extern_nama" => ".pembelian",
                                        "extern2_id" => ".1040010",//lihat coa untuk urutannya
                                        "extern2_nama" => ".produk",
//                            "extern3_id" => ".0",
//                            "extern3_nama" => "machine_id",//diisi machinid
//                            "extern4_id" => ".0",
//                            "extern4_nama" => ".0",
                                        "jenis" => "tr__jenis",
                                        "transaksi_id" => "tr__id",
                                        "transaksi_no" => "tr__nomer",
                                        "produk_id" => "id",
                                        "produk_nama" => "nama",
                                        "produk_kode" => "produk_kode",
                                        "produk_jenis" => "jenis",
                                        "barcode" => "barcode",
                                        "jml" => "jml",
                                        "harga" => "harga",// harga dpp
                                        "hpp" => "hpp",// hpp produk
                                        "harga_include_ppn" => "nett",// harga include ppn
                                        "sub_harga" => "sub_harga",// harga dpp
                                        "sub_hpp" => "sub_hpp",// hpp produk
                                        "sub_harga_include_ppn" => "sub_nett",// harga include ppn
                                        "oleh_id" => "tr__oleh_id",
                                        "oleh_nama" => "tr__oleh_nama",
                                        "pihak_id" => "pihakID",// supplier
                                        "pihak_nama" => "pihakName", // supplier
                                        "oleh_top_id" => "oleh_top_id",
                                        "oleh_top_nama" => "oleh_top_nama",
                                        "satuan_id" => "satuan_id",
                                        "satuan_nama" => "satuan_nama",
                                        "rugilaba" => "rugilaba",
                                        "master_id" => "tr__id_master",
//                                        "diskon" => "discNilai",
//                                        "diskon_persen" => "discPersen",
                                        "diskon" => "disc",
                                        "diskon_persen" => "disc_percent",
                                        "sub_diskon" => "sub_disc",
                                        "sub_diskon_persen" => "sub_disc_percent",
                                        //----------------
                                        "outdoor_id" => "outdoor_id",
                                        "outdoor_nama" => "outdoor_nama",
                                        "outdoor_barcode" => "outdoor_barcode",
                                        "outdoor_sku" => "outdoor_sku",
                                        "indoor_id_1" => "indoor_id_1",
                                        "indoor_nama_1" => "indoor_nama_1",
                                        "indoor_barcode_1" => "indoor_barcode_1",
                                        "indoor_sku_1" => "indoor_sku_1",
                                        "indoor_id_2" => "indoor_id_2",
                                        "indoor_nama_2" => "indoor_nama_2",
                                        "indoor_barcode_2" => "indoor_barcode_2",
                                        "indoor_sku_2" => "indoor_sku_2",
                                        "indoor_id_3" => "indoor_id_3",
                                        "indoor_nama_3" => "indoor_nama_3",
                                        "indoor_barcode_3" => "indoor_barcode_3",
                                        "indoor_sku_3" => "indoor_sku_3",
                                        "indoor_id_4" => "indoor_id_4",
                                        "indoor_nama_4" => "indoor_nama_4",
                                        "indoor_barcode_4" => "indoor_barcode_4",
                                        "indoor_sku_4" => "indoor_sku_4",
                                        "kategori_id" => "kategori_id",
                                        "kategori_nama" => "kategori_nama",
                                        "produk_part_id_1" => "part_id_1",
                                        "produk_part_nama_1" => "part_nama_1",
                                        "produk_part_barcode_1" => "part_barcode_1",
                                        "produk_part_id_2" => "part_id_2",
                                        "produk_part_nama_2" => "part_nama_2",
                                        "produk_part_barcode_2" => "part_barcode_2",
                                        "heater_id" => "heater_id",
                                        "heater_nama" => "heater_nama",
                                        "heater_barcode" => "heater_barcode",
                                        //----------------
                                        "sales_admin_id" => "main__sellerID",
                                        "sales_admin_nama" => "main__sellerName",
                                        "salesman_id" => "main__salesmanDetails",
                                        "salesman_nama" => "main__salesmanDetails__nama",
                                        "gudang_id_kirim" => "main__gudangStatusDetails",
                                        "gudang_nama_kirim" => "main__gudangStatusDetails__nama",
                                        "delivery_id" => "main__shippingMethod",
                                        "delivery_nama" => "main__shippingMethod__name",
                                        "pengirim_id" => "main__pengirimID",
                                        "pengirim_nama" => "main__pengirimName",
                                        "pembayaran_nama" => "main__paymentMethod",
                                        //----------------
                                        "transaksi_id_1" => "tr__transaksi_id_1",
                                        "transaksi_no_1" => "tr__transaksi_no_1",
                                        "transaksi_id_2" => "tr__transaksi_id_2",
                                        "transaksi_no_2" => "tr__transaksi_no_2",
                                        "transaksi_id_3" => "tr__transaksi_id_3",
                                        "transaksi_no_3" => "tr__transaksi_no_3",
                                        "transaksi_id_4" => "tr__transaksi_id_4",
                                        "transaksi_no_4" => "tr__transaksi_no_4",
                                        "transaksi_id_5" => "tr__transaksi_id_5",
                                        "transaksi_no_5" => "tr__transaksi_no_5",
                                        //----------------
                                        "transaksi_nilai" => "new_net3",
                                        "ppn_nilai" => "ppn",
                                        "sub_ppn_nilai" => "sub_ppn",
                                        //----------------
                                        "diskon_1_id" => "diskon_1_id",
                                        "diskon_1_nama" => "diskon_1_nama",
                                        "diskon_1_persen" => "diskon_1_persen",
                                        "diskon_1_nilai" => "diskon_1_nilai",
                                        "sub_diskon_1_nilai" => "sub_diskon_1_nilai",
                                        "diskon_2_id" => "diskon_2_id",
                                        "diskon_2_nama" => "diskon_2_nama",
                                        "diskon_2_persen" => "diskon_2_persen",
                                        "diskon_2_nilai" => "diskon_2_nilai",
                                        "sub_diskon_2_nilai" => "sub_diskon_2_nilai",
                                        "diskon_3_id" => "diskon_3_id",
                                        "diskon_3_nama" => "diskon_3_nama",
                                        "diskon_3_persen" => "diskon_3_persen",
                                        "diskon_3_nilai" => "diskon_3_nilai",
                                        "sub_diskon_3_nilai" => "sub_diskon_3_nilai",
                                        "diskon_4_id" => "diskon_4_id",
                                        "diskon_4_nama" => "diskon_4_nama",
                                        "diskon_4_persen" => "diskon_4_persen",
                                        "diskon_4_nilai" => "diskon_4_nilai",
                                        "sub_diskon_4_nilai" => "sub_diskon_4_nilai",
                                        "diskon_5_id" => "diskon_5_id",
                                        "diskon_5_nama" => "diskon_5_nama",
                                        "diskon_5_persen" => "diskon_5_persen",
                                        "diskon_5_nilai" => "diskon_5_nilai",
                                        "sub_diskon_5_nilai" => "sub_diskon_5_nilai",
                                        "diskon_6_id" => "diskon_6_id",
                                        "diskon_6_nama" => "diskon_6_nama",
                                        "diskon_6_persen" => "diskon_6_persen",
                                        "diskon_6_nilai" => "diskon_6_nilai",
                                        "sub_diskon_6_nilai" => "sub_diskon_6_nilai",
                                        "diskon_7_id" => "diskon_7_id",
                                        "diskon_7_nama" => "diskon_7_nama",
                                        "diskon_7_persen" => "diskon_7_persen",
                                        "diskon_7_nilai" => "diskon_7_nilai",
                                        "sub_diskon_7_nilai" => "sub_diskon_7_nilai",
                                        "harga_tandas" => "hrg_tandas",
                                        "harga_tandas_npph23" => "hrg_tandas_npph23",
                                        //------
                                        "references_data" => "main__references_ids",
                                        "invoice_supplier" => "main__description_main_followup",
                                    ),
                                    "srcGateName" => "items",
                                    "srcRawGateName" => "items",
                                ),

                            ),
                        );
                        break;
                    case "967":
                    case "9967":
                        $postproc = array(
                            "master" => array(),
                            "detail" => array(
                                array(
                                    "comName" => "RekeningPembantuRaw",
                                    "loop" => array(
                                        "1040" => "-sub_harga",//rekening pembelian untuk keperluan lap
                                    ),
                                    "static" => array(
                                        "cabang_id" => "cabangID",
                                        "cabang_nama" => "cabangName",
                                        "extern_id" => ".1040",//produk ,non produk
                                        "extern_nama" => ".pembelian",
                                        "extern2_id" => ".1040010",//lihat coa untuk urutannya
                                        "extern2_nama" => ".produk",
//                            "extern3_id" => ".0",
//                            "extern3_nama" => "machine_id",//diisi machinid
//                            "extern4_id" => ".0",
//                            "extern4_nama" => ".0",
                                        "jenis" => "tr__jenis",
                                        "transaksi_id" => "tr__id",
                                        "transaksi_no" => "tr__nomer",
                                        "produk_id" => "id",
                                        "produk_nama" => "nama",
                                        "produk_kode" => "produk_kode",
                                        "produk_jenis" => "jenis",
                                        "barcode" => "barcode",
                                        "jml" => "-jml",
                                        "harga" => "harga",// harga dpp
                                        "hpp" => "hpp",// hpp produk
                                        "harga_include_ppn" => "nett",// harga include ppn
                                        "sub_harga" => "sub_harga",// harga dpp
                                        "sub_hpp" => "sub_hpp",// hpp produk
                                        "sub_harga_include_ppn" => "sub_nett",// harga include ppn
                                        "oleh_id" => "tr__oleh_id",
                                        "oleh_nama" => "tr__oleh_nama",
                                        "pihak_id" => "pihakID",// supplier
                                        "pihak_nama" => "pihakName", // supplier
                                        "oleh_top_id" => "oleh_top_id",
                                        "oleh_top_nama" => "oleh_top_nama",
                                        "satuan_id" => "satuan_id",
                                        "satuan_nama" => "satuan_nama",
                                        "rugilaba" => "rugilaba",
                                        "master_id" => "tr__id_master",
//                                        "diskon" => "discNilai",
//                                        "diskon_persen" => "discPersen",
                                        "diskon" => "disc",
                                        "diskon_persen" => "disc_percent",
                                        "sub_diskon" => "sub_disc",
                                        "sub_diskon_persen" => "sub_disc_percent",
                                        //----------------
                                        "outdoor_id" => "outdoor_id",
                                        "outdoor_nama" => "outdoor_nama",
                                        "outdoor_barcode" => "outdoor_barcode",
                                        "outdoor_sku" => "outdoor_sku",
                                        "indoor_id_1" => "indoor_id_1",
                                        "indoor_nama_1" => "indoor_nama_1",
                                        "indoor_barcode_1" => "indoor_barcode_1",
                                        "indoor_sku_1" => "indoor_sku_1",
                                        "indoor_id_2" => "indoor_id_2",
                                        "indoor_nama_2" => "indoor_nama_2",
                                        "indoor_barcode_2" => "indoor_barcode_2",
                                        "indoor_sku_2" => "indoor_sku_2",
                                        "indoor_id_3" => "indoor_id_3",
                                        "indoor_nama_3" => "indoor_nama_3",
                                        "indoor_barcode_3" => "indoor_barcode_3",
                                        "indoor_sku_3" => "indoor_sku_3",
                                        "indoor_id_4" => "indoor_id_4",
                                        "indoor_nama_4" => "indoor_nama_4",
                                        "indoor_barcode_4" => "indoor_barcode_4",
                                        "indoor_sku_4" => "indoor_sku_4",
                                        "kategori_id" => "kategori_id",
                                        "kategori_nama" => "kategori_nama",
                                        "produk_part_id_1" => "part_id_1",
                                        "produk_part_nama_1" => "part_nama_1",
                                        "produk_part_barcode_1" => "part_barcode_1",
                                        "produk_part_id_2" => "part_id_2",
                                        "produk_part_nama_2" => "part_nama_2",
                                        "produk_part_barcode_2" => "part_barcode_2",
                                        "heater_id" => "heater_id",
                                        "heater_nama" => "heater_nama",
                                        "heater_barcode" => "heater_barcode",
                                        //----------------
                                        "sales_admin_id" => "main__sellerID",
                                        "sales_admin_nama" => "main__sellerName",
                                        "salesman_id" => "main__salesmanDetails",
                                        "salesman_nama" => "main__salesmanDetails__nama",
                                        "gudang_id_kirim" => "main__gudangStatusDetails",
                                        "gudang_nama_kirim" => "main__gudangStatusDetails__nama",
                                        "delivery_id" => "main__shippingMethod",
                                        "delivery_nama" => "main__shippingMethod__name",
                                        "pengirim_id" => "main__pengirimID",
                                        "pengirim_nama" => "main__pengirimName",
                                        "pembayaran_nama" => "main__paymentMethod",
                                        //----------------
                                        "transaksi_id_1" => "tr__transaksi_id_1",
                                        "transaksi_no_1" => "tr__transaksi_no_1",
                                        "transaksi_id_2" => "tr__transaksi_id_2",
                                        "transaksi_no_2" => "tr__transaksi_no_2",
                                        "transaksi_id_3" => "tr__transaksi_id_3",
                                        "transaksi_no_3" => "tr__transaksi_no_3",
                                        "transaksi_id_4" => "tr__transaksi_id_4",
                                        "transaksi_no_4" => "tr__transaksi_no_4",
                                        "transaksi_id_5" => "tr__transaksi_id_5",
                                        "transaksi_no_5" => "tr__transaksi_no_5",
                                        //----------------
                                        "transaksi_nilai" => "new_net3",
                                        "ppn_nilai" => "ppn",
                                        "sub_ppn_nilai" => "sub_ppn",
                                        //----------------
                                        "diskon_1_id" => "diskon_1_id",
                                        "diskon_1_nama" => "diskon_1_nama",
                                        "diskon_1_persen" => "diskon_1_persen",
                                        "diskon_1_nilai" => "diskon_1_nilai",
                                        "sub_diskon_1_nilai" => "sub_diskon_1_nilai",
                                        "diskon_2_id" => "diskon_2_id",
                                        "diskon_2_nama" => "diskon_2_nama",
                                        "diskon_2_persen" => "diskon_2_persen",
                                        "diskon_2_nilai" => "diskon_2_nilai",
                                        "sub_diskon_2_nilai" => "sub_diskon_2_nilai",
                                        "diskon_3_id" => "diskon_3_id",
                                        "diskon_3_nama" => "diskon_3_nama",
                                        "diskon_3_persen" => "diskon_3_persen",
                                        "diskon_3_nilai" => "diskon_3_nilai",
                                        "sub_diskon_3_nilai" => "sub_diskon_3_nilai",
                                        "diskon_4_id" => "diskon_4_id",
                                        "diskon_4_nama" => "diskon_4_nama",
                                        "diskon_4_persen" => "diskon_4_persen",
                                        "diskon_4_nilai" => "diskon_4_nilai",
                                        "sub_diskon_4_nilai" => "sub_diskon_4_nilai",
                                        "diskon_5_id" => "diskon_5_id",
                                        "diskon_5_nama" => "diskon_5_nama",
                                        "diskon_5_persen" => "diskon_5_persen",
                                        "diskon_5_nilai" => "diskon_5_nilai",
                                        "sub_diskon_5_nilai" => "sub_diskon_5_nilai",
                                        "diskon_6_id" => "diskon_6_id",
                                        "diskon_6_nama" => "diskon_6_nama",
                                        "diskon_6_persen" => "diskon_6_persen",
                                        "diskon_6_nilai" => "diskon_6_nilai",
                                        "sub_diskon_6_nilai" => "sub_diskon_6_nilai",
                                        "diskon_7_id" => "diskon_7_id",
                                        "diskon_7_nama" => "diskon_7_nama",
                                        "diskon_7_persen" => "diskon_7_persen",
                                        "diskon_7_nilai" => "diskon_7_nilai",
                                        "sub_diskon_7_nilai" => "sub_diskon_7_nilai",
                                        "harga_tandas" => "hrg_tandas",
                                        "harga_tandas_npph23" => "hrg_tandas_npph23",
                                        //------
                                        "references_data" => "main__references_ids",
                                    ),
                                    "srcGateName" => "items",
                                    "srcRawGateName" => "items",
                                ),

                            ),
                        );
                        break;
                    case "1967":
                    case "19967":
                        $refs = array(
                            "referenceID_1" => $main["transaksiDatas__id_master"],
                            "referenceID_2" => $main["transaksiDatas"],
                        );
                        $main["references_ids"] = implode(",", $refs);
//                        arrPrintHijau($main);
                        $postproc = array(
                            "master" => array(),
                            "detail" => array(
                                array(
                                    "comName" => "RekeningPembantuRaw",
                                    "loop" => array(
                                        "1040" => "-sub_harga",//rekening pembelian untuk keperluan lap
                                    ),
                                    "static" => array(
                                        "cabang_id" => "cabangID",
                                        "cabang_nama" => "cabangName",
                                        "extern_id" => ".1040",//produk ,non produk
                                        "extern_nama" => ".pembelian",
                                        "extern2_id" => ".1040010",//lihat coa untuk urutannya
                                        "extern2_nama" => ".produk",
//                            "extern3_id" => ".0",
//                            "extern3_nama" => "machine_id",//diisi machinid
//                            "extern4_id" => ".0",
//                            "extern4_nama" => ".0",
                                        "jenis" => "tr__jenis",
                                        "transaksi_id" => "tr__id",
                                        "transaksi_no" => "tr__nomer",
                                        "produk_id" => "id",
                                        "produk_nama" => "nama",
                                        "produk_kode" => "produk_kode",
                                        "produk_jenis" => "jenis",
                                        "barcode" => "barcode",
                                        "jml" => "-jml",
                                        "harga" => "harga",// harga dpp
                                        "hpp" => "hpp",// hpp produk
                                        "harga_include_ppn" => "nett",// harga include ppn
                                        "sub_harga" => "sub_harga",// harga dpp
                                        "sub_hpp" => "sub_hpp",// hpp produk
                                        "sub_harga_include_ppn" => "sub_nett",// harga include ppn
                                        "oleh_id" => "tr__oleh_id",
                                        "oleh_nama" => "tr__oleh_nama",
                                        "pihak_id" => "pihakID",// supplier
                                        "pihak_nama" => "pihakName", // supplier
                                        "oleh_top_id" => "oleh_top_id",
                                        "oleh_top_nama" => "oleh_top_nama",
                                        "satuan_id" => "satuan_id",
                                        "satuan_nama" => "satuan_nama",
                                        "rugilaba" => "rugilaba",
                                        "master_id" => "tr__id_master",
//                                        "diskon" => "discNilai",
//                                        "diskon_persen" => "discPersen",
                                        "diskon" => "disc",
                                        "diskon_persen" => "disc_percent",
                                        "sub_diskon" => "sub_disc",
                                        "sub_diskon_persen" => "sub_disc_percent",
                                        //----------------
                                        "outdoor_id" => "outdoor_id",
                                        "outdoor_nama" => "outdoor_nama",
                                        "outdoor_barcode" => "outdoor_barcode",
                                        "outdoor_sku" => "outdoor_sku",
                                        "indoor_id_1" => "indoor_id_1",
                                        "indoor_nama_1" => "indoor_nama_1",
                                        "indoor_barcode_1" => "indoor_barcode_1",
                                        "indoor_sku_1" => "indoor_sku_1",
                                        "indoor_id_2" => "indoor_id_2",
                                        "indoor_nama_2" => "indoor_nama_2",
                                        "indoor_barcode_2" => "indoor_barcode_2",
                                        "indoor_sku_2" => "indoor_sku_2",
                                        "indoor_id_3" => "indoor_id_3",
                                        "indoor_nama_3" => "indoor_nama_3",
                                        "indoor_barcode_3" => "indoor_barcode_3",
                                        "indoor_sku_3" => "indoor_sku_3",
                                        "indoor_id_4" => "indoor_id_4",
                                        "indoor_nama_4" => "indoor_nama_4",
                                        "indoor_barcode_4" => "indoor_barcode_4",
                                        "indoor_sku_4" => "indoor_sku_4",
                                        "kategori_id" => "kategori_id",
                                        "kategori_nama" => "kategori_nama",
                                        "produk_part_id_1" => "part_id_1",
                                        "produk_part_nama_1" => "part_nama_1",
                                        "produk_part_barcode_1" => "part_barcode_1",
                                        "produk_part_id_2" => "part_id_2",
                                        "produk_part_nama_2" => "part_nama_2",
                                        "produk_part_barcode_2" => "part_barcode_2",
                                        "heater_id" => "heater_id",
                                        "heater_nama" => "heater_nama",
                                        "heater_barcode" => "heater_barcode",
                                        //----------------
                                        "sales_admin_id" => "main__sellerID",
                                        "sales_admin_nama" => "main__sellerName",
                                        "salesman_id" => "main__salesmanDetails",
                                        "salesman_nama" => "main__salesmanDetails__nama",
                                        "gudang_id_kirim" => "main__gudangStatusDetails",
                                        "gudang_nama_kirim" => "main__gudangStatusDetails__nama",
                                        "delivery_id" => "main__shippingMethod",
                                        "delivery_nama" => "main__shippingMethod__name",
                                        "pengirim_id" => "main__pengirimID",
                                        "pengirim_nama" => "main__pengirimName",
                                        "pembayaran_nama" => "main__paymentMethod",
                                        //----------------
                                        "transaksi_id_1" => "tr__transaksi_id_1",
                                        "transaksi_no_1" => "tr__transaksi_no_1",
                                        "transaksi_id_2" => "tr__transaksi_id_2",
                                        "transaksi_no_2" => "tr__transaksi_no_2",
                                        "transaksi_id_3" => "tr__transaksi_id_3",
                                        "transaksi_no_3" => "tr__transaksi_no_3",
                                        "transaksi_id_4" => "tr__transaksi_id_4",
                                        "transaksi_no_4" => "tr__transaksi_no_4",
                                        "transaksi_id_5" => "tr__transaksi_id_5",
                                        "transaksi_no_5" => "tr__transaksi_no_5",
                                        //----------------
                                        "transaksi_nilai" => "new_net3",
                                        "ppn_nilai" => "ppn",
                                        "sub_ppn_nilai" => "sub_ppn",
                                        //----------------
                                        "diskon_1_id" => "diskon_1_id",
                                        "diskon_1_nama" => "diskon_1_nama",
                                        "diskon_1_persen" => "diskon_1_persen",
                                        "diskon_1_nilai" => "diskon_1_nilai",
                                        "sub_diskon_1_nilai" => "sub_diskon_1_nilai",
                                        "diskon_2_id" => "diskon_2_id",
                                        "diskon_2_nama" => "diskon_2_nama",
                                        "diskon_2_persen" => "diskon_2_persen",
                                        "diskon_2_nilai" => "diskon_2_nilai",
                                        "sub_diskon_2_nilai" => "sub_diskon_2_nilai",
                                        "diskon_3_id" => "diskon_3_id",
                                        "diskon_3_nama" => "diskon_3_nama",
                                        "diskon_3_persen" => "diskon_3_persen",
                                        "diskon_3_nilai" => "diskon_3_nilai",
                                        "sub_diskon_3_nilai" => "sub_diskon_3_nilai",
                                        "diskon_4_id" => "diskon_4_id",
                                        "diskon_4_nama" => "diskon_4_nama",
                                        "diskon_4_persen" => "diskon_4_persen",
                                        "diskon_4_nilai" => "diskon_4_nilai",
                                        "sub_diskon_4_nilai" => "sub_diskon_4_nilai",
                                        "diskon_5_id" => "diskon_5_id",
                                        "diskon_5_nama" => "diskon_5_nama",
                                        "diskon_5_persen" => "diskon_5_persen",
                                        "diskon_5_nilai" => "diskon_5_nilai",
                                        "sub_diskon_5_nilai" => "sub_diskon_5_nilai",
                                        "diskon_6_id" => "diskon_6_id",
                                        "diskon_6_nama" => "diskon_6_nama",
                                        "diskon_6_persen" => "diskon_6_persen",
                                        "diskon_6_nilai" => "diskon_6_nilai",
                                        "sub_diskon_6_nilai" => "sub_diskon_6_nilai",
                                        "diskon_7_id" => "diskon_7_id",
                                        "diskon_7_nama" => "diskon_7_nama",
                                        "diskon_7_persen" => "diskon_7_persen",
                                        "diskon_7_nilai" => "diskon_7_nilai",
                                        "sub_diskon_7_nilai" => "sub_diskon_7_nilai",
                                        "harga_tandas" => "hrg_tandas",
                                        "harga_tandas_npph23" => "hrg_tandas_npph23",
                                        //------
                                        "references_data" => "main__references_ids",
                                    ),
                                    "srcGateName" => "items",
                                    "srcRawGateName" => "items",
                                ),

                            ),
                        );
                        break;

                    default:
                        mati_disini("[$jenisTr] processor belum di setting...");
                        break;
                }

//                $postditunda = array(
////                "master" => array(),
////                "detail" => array(
//////                array(
//////                    "comName" => "FifoAverageDitunda",
//////                    "loop" => array(),
//////                    "static" => array(
//////                        "jenis" => ".produk",
//////                        "jml" => "jml_ditunda",
//////                        "produk_id" => "id",
//////                        "hpp" => "hpp",
//////                        "jml_nilai" => "sub_hpp",
//////                        "nama" => "nama",
//////                        "cabang_id" => "cabang_id",
//////                        "gudang_id" => "gudang_id",
//////                        "toko_id" => "toko_id",
//////                        "toko_nama" => "",
//////                    ),
//////                    "reversable" => true,
//////                    "srcGateName" => "items",
//////                    "srcRawGateName" => "items",
//////                ),
////                ),
//                );

                //endregion

                if (sizeof($items) > 0) {
                    foreach ($items as $pID => $iSpec) {
//                        "ppn_nilai" => "ppn_nilai",
//                        "sub_ppn_nilai" => "sub_ppn_nilai",
//                        $iSpec["ppn_nilai"] = ($iSpec["nett1"] * 0.11);
//                        $iSpec["sub_ppn_nilai"] = ($iSpec["sub_nett1"] * 0.11);
                        foreach ($main as $m_key => $m_val) {
                            $new_m_key = "main__" . $m_key;
                            $iSpec[$new_m_key] = $m_val;
                        }
                        foreach ($trData as $tr_key => $tr_val) {
                            $new_tr_key = "tr__" . $tr_key;
                            $iSpec[$new_tr_key] = $tr_val;
                        }
//                        arrPrintKuning($iSpec);
                        $items[$pID] = $iSpec;
                    }
                }
//arrPrintHijau($items);
                //region postproc
                cekMerah("mulai postproc :::::: -- " . __LINE__);
                $iterator = array();
                $iterator = $postproc["detail"];
                if (sizeof($iterator) > 0) {
                    $comsLocation = "Coms";
                    $comsPrefix = "Com";
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $tmpOutParams[$cCtr] = array();
                        $gg = 0;
                        foreach ($items as $id => $dSpec) {
                            // $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $items, $comName);
                            }

                            $mdlName = "$comsPrefix" . ucfirst($comName);
                            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                $filterNeeded = true;
                            }
                            else {
                                $filterNeeded = false;
                            }
                            // cekHere("sub-component: [$comsLocation] $comName, initializing values <br>");

                            $subParams = array();

                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $dSpec[$key], $key);
                                    }

                                    $realValue = makeValue($value, $dSpec, $dSpec, 0);
                                    // cekMErah("$key =>".$realValue);
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
                                    $realValue = makeValue($value, $dSpec, $dSpec, 0);
                                    $subParams['static'][$key] = $realValue;
                                }
                                if (!isset($subParams['static']["transaksi_id"])) {
                                    $subParams['static']["transaksi_id"] = 0000;
                                }
                                if (!isset($subParams['static']["transaksi_no"])) {
                                    $subParams['static']["transaksi_no"] = 0000;
                                }

                                $subParams['static']["fulldate"] = $fulldate;
                                $subParams['static']["dtime"] = $dtime;
                                $subParams['static']["dtime_2"] = date("Y-m-d H:i");
                                $subParams['static']["keterangan"] = $jenisTrName . " oleh " . $oleh_nama;
                            }

                            if (sizeof($subParams) > 0) {
                                //                                cekhitam("subparam ada isinya");
                                if ($filterNeeded) {
                                    if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                }
                            }
                            else {
                                cekhitam("subparam TIDAK ada isinya");
                            }
                        }

                        $componentGate['detail'][$cCtr] = $subParams;
                    }
//                    arrPrintHijau($tmpOutParams);
//                    matiHEre($cCtr);

                    foreach ($iterator as $cCtr => $tComSpec) {
                        // $srcGateName = $tComSpec['srcGateName'];
                        foreach ($items as $id => $dSpec) {
                            // $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $items[$id][$comName], $comName);
                            }
                        }
                        cekHere("sub component: [$comsLocation] $comName, sending values " . __LINE__ . "<br>");

                        $mdlName = "$comsPrefix" . ucfirst($comName);
                        $this->load->model("$comsLocation/" . $mdlName);
                        $m = new $mdlName();
                        //===filter value nol, jika harus difilter

                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        if ($tobeExecuted) {
                            //----- kiriman gerbang
                            if (method_exists($m, "setTableInMaster")) {
                                $m->setTableInMaster($tableIn_master);
                            }
                            if (method_exists($m, "setDetail")) {
                                $m->setDetail($items);
                            }
                            if (method_exists($m, "setJenisTr")) {
                                $m->setJenisTr($jenisTr);
                            }
                            //----- kiriman gerbang
                            $m->pair($tmpOutParams[$cCtr]) or matiHere("Tidak berhasil memasang  values pada komponen: $comName/" . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or matiHere("Gagal saat berusaha  exec values pada komponen: $comName/" . "/" . __FUNCTION__ . "/" . __LINE__);
//                            cekBiru($this->db->last_query());
                        }
                        else {
//                            cekMerah("$comName tidak eksekusi");
                        }

                    }
                }
                else {
                    cekKuning("sub post-components is not set");
                }
                //endregion


                $tr = New MdlTransaksi();
                $tr->setFilters(array());
                $where = array(
                    "id" => $transaksi_id,
                );
                $data = array(
                    "r_maju" => 1,
                );
                $tr->updateData($where, $data);
                showLast_query("orange");


            }
            else {
                cekMerah("<h3>HABIS...</h3>");
            }


        }
        $end = microtime(true);
        $selesai = $end - $start;


//        matiHEre("complitt [selesai dalam $selesai]");


        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");


        cekHijau("<h3>SELESAI... [$selesai]</h3>");

    }

    public function runBudgetingProject_()
    {
        $this->load->helper("he_angka");
        $component = array(
            "master" => array(
                // jurnal ke 1 piutang dagang/penjualan per WO
                /*
                 * realtive costnem masuk ke COA Hpp produk
                 * costing  masuk ke kategory
                 */
                array(
                    "comName" => "Jurnal",
                    "loop" => array(
                        "1010020080" => "harga_budget",// piutang dagang project
//                        "2030060" => "ppn",// ppn Kelauran belum faktur
                        "4010" => "harga_budget",// penjualan projek, menggunakan gerbang yang bulat, bukan yang masih desimal (14 desember 2022)

                        "5020" => "hpp_budget",//hpp budget project debet
                        "3020010" => "hpp_budget",//efisiensi kredit
                    ),

                    "static" => array(
                        "cabang_id" => "placeID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                array(
                    "comName" => "Rekening",
                    "loop" => array(
                        "1010020080" => "harga_budget",// piutang dagang project
//                        "2030060" => "ppn",// ppn Keluaran belum faktur
                        "4010" => "harga_budget",// penjualan projek, menggunakan gerbang yang bulat, bukan yang masih desimal (14 desember 2022)
                        "5020" => "hpp_budget",//hpp budget project
                        "3020010" => "hpp_budget",//efisiensi
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                //rekening pembantu piutang project
                array(
                    "comName" => "RekeningPembantuCustomer",
                    "loop" => array(
                        "1010020080" => "harga_budget",// piutang dagang
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "pihakID",
                        "extern_nama" => "pihakName",
                        "jenis" => "jenisTr",
                        // "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                //rekening pembantu penjualan project
                array(
                    "comName" => "RekeningPembantuPenjualanKonsumen",// lokal - konsumen
                    "loop" => array(
                        "4010" => "harga_budget",// penjualan
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => ".4010030",
                        "extern_nama" => ".project",
                        "extern2_id" => "pihakID",
                        "extern2_nama" => "pihakName",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                        "harga" => "harga_budget",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                //pembantu ppn belum faktur
//                array(
//                    "comName" => "RekeningPembantuCustomer",
//                    "loop" => array(
//                        "2030060" => "ppn",// ppn out
//                    ),
//                    "static" => array(
//                        "cabang_id" => "placeID",
//                        "extern_id" => "pihakID",
//                        "extern_nama" => "pihakName",
//                        "jenis" => "jenisTr",
//                        // "transaksi_no" => "nomer",
//                    ),
//                    "srcGateName" => "main",
//                    "srcRawGateName" => "main",
//                ),

            ),
            "detail" => array(
                // pembantu efisiensi bahan baku (RAB) => kredit perkategori biaya
                array(
                    "comName" => "RekeningPembantuEfisiensiBiaya",
                    "loop" => array(
                        "3020010" => "sub_hpp_produk_budget",//bahan baku dengan nilai bom masih single produk belum suport multi, jik sudah suport multi pakai yang ke 2
                        // "3020010" => "supplies_bom",//bahan baku dengan nilai bom
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "categori_biaya_id",//id kategori biaya
                        "extern_nama" => "categori_biaya_nama",
                        "extern2_id" => "categori_biaya_id",
                        "extern2_nama" => "categori_biaya_nama",
                        "produk_qty" => "jml",
                        "produk_nilai" => "hpp",
                        "gudang_id" => "gudangID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),
                array(
                    "comName" => "RekeningPembantuEfisiensiBiaya",
                    "loop" => array(
                        "3020010" => "sub_hpp_jasa_budget",//bahan baku dengan nilai bom masih single produk belum suport multi, jik sudah suport multi pakai yang ke 2
                        // "3020010" => "supplies_bom",//bahan baku dengan nilai bom
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "categori_biaya_id",
                        "extern_nama" => "categori_biaya_nama",
//                        "extern2_id" => "categori_biaya_id",
//                        "extern2_nama" => "categori_biaya_nama",
                        "produk_qty" => "jml",
                        "produk_nilai" => "hpp",
                        "gudang_id" => "gudangID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),
                // pembantu LV2 efisiensi (RAB) => kredit per jenis biaya per kategori project, wo,spk
                array(
                    "comName" => "RekeningPembantuEfisiensiBiayaSub",
                    "loop" => array(
                        "3020010" => "sub_hpp_produk_budget",//efisiensi biaya
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id",//biaya nya
                        "extern_nama" => "nama",
                        "extern2_id" => "categori_biaya_id",
                        "extern2_nama" => "categori_biaya_nama",
                        "extern3_id" => "project_id",//projectid
                        "extern3_nama" => "project_nama",
                        "extern4_id" => "work_order_id",//wo id
                        "extern4_nama" => "work_order_nama",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),
                array(
                    "comName" => "RekeningPembantuEfisiensiBiayaSub",
                    "loop" => array(
                        "3020010" => "sub_hpp_jasa_budget",//efisiensi biaya
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id",//biaya nya
                        "extern_nama" => "nama",
                        "extern2_id" => "categori_biaya_id",
                        "extern2_nama" => "categori_biaya_nama",
                        "extern3_id" => "project_id",//projectid
                        "extern3_nama" => "project_nama",
                        "extern4_id" => "work_order_id",//wo id
                        "extern4_nama" => "work_order_nama",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),

                //pembantu hpp produksi bahan baku in
                array(
                    "comName" => "RekeningPembantuBiayaKomposisiProduksi",
                    "loop" => array(
                        "5020" => "sub_hpp_produk_budget", // isi loop adalah overhead,tenaga kerja,biaya kirim
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id", // ID supplies
                        "extern_nama" => "nama", // NAME supplies
                        "extern2_id" => "categori_biaya_id", // ID biaya
                        "extern2_nama" => "categori_biaya_nama", // label biaya
                        "jenis" => "jenisTr",
                        "produk_qty" => "jml",
                        "produk_nilai" => "hpp",
                        "gudang_id" => "gudangID",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),
                array(
                    "comName" => "RekeningPembantuBiayaKomposisiProduksi",
                    "loop" => array(
                        "5020" => "sub_hpp_jasa_budget", // isi loop adalah overhead,tenaga kerja,biaya kirim
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id", // ID supplies
                        "extern_nama" => "nama", // NAME supplies
                        "extern2_id" => "categori_biaya_id", // ID biaya
                        "extern2_nama" => "categori_biaya_nama", // label biaya
                        "jenis" => "jenisTr",
                        "produk_qty" => "jml",
                        "produk_nilai" => "hpp",
                        "gudang_id" => "gudangID",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),

                //nulis raw efisiensi
                array(
                    "comName" => "RekeningPembantuRawItemEfisiensi",
                    "loop" => array(
                        "3020010" => "sub_hpp_produk_budget", // isi loop adalah overhead,tenaga kerja,biaya kirim
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => ".3020010",//biaya
                        "extern_nama" => ".efisensi",
                        "extern2_nama" => "categori_biaya_nama",
                        "extern2_id" => "categori_biaya_id",
                        "extern3_id" => "work_order_id",//projectid
                        "extern3_nama" => "pihakWoProjekName",
                        "extern4_id" => "id",//biaya
                        "extern4_nama" => "nama",
                        "produk_id" => "project_id",//project
                        "produk_nama" => "project_nama",
                        "produk_kode" => "no_spk",
                        "produk_jenis" => ".project",
//                            "barcode" => "barcode",
                        "jml" => "jml",
                        "harga" => "hpp",// harga dpp
                        "hpp" => "hpp",// hpp produk
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),
                array(
                    "comName" => "RekeningPembantuRawItemEfisiensi",
                    "loop" => array(
                        "3020010" => "sub_hpp_jasa_budget", // isi loop adalah overhead,tenaga kerja,biaya kirim
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => ".3020010",//biaya
                        "extern_nama" => ".efisensi",
                        "extern2_nama" => "categori_biaya_nama",
                        "extern2_id" => "categori_biaya_id",
                        "extern3_id" => "work_order_id",//projectid
                        "extern3_nama" => "work_order_nama",
                        "extern4_id" => "id",//biaya
                        "extern4_nama" => "nama",
                        "produk_id" => "project_id",//project
                        "produk_nama" => "project_nama",
                        "produk_kode" => "no_spk",
                        "produk_jenis" => ".project",
//                            "barcode" => "barcode",
                        "jml" => "jml",
                        "harga" => "hpp",// harga dpp
                        "hpp" => "hpp",// hpp produk
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),

                //raw hpp
                array(
                    "comName" => "RekeningPembantuRaw",
                    "loop" => array(
                        "5020" => "sub_hpp_produk_budget", // isi loop adalah overhead,tenaga kerja,biaya kirim
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
//                        "extern_id" => ".3020010",//biaya
//                        "extern_nama" => ".efisensi",
                        "extern_id" => "id",//biaya
                        "extern_nama" => "nama",
                        "extern2_nama" => "categori_biaya_nama",
                        "extern2_id" => "categori_biaya_id",
                        "extern3_id" => "work_order_id",//projectid
                        "extern3_nama" => "pihakWoProjekName",

                        "produk_id" => "project_id",//project
                        "produk_nama" => "project_nama",
                        "produk_kode" => "no_spk",
                        "produk_jenis" => ".project",
//                            "barcode" => "barcode",
                        "jml" => "jml",
                        "harga" => "hpp",// harga dpp
                        "hpp" => "hpp",// hpp produk
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),
                array(
                    "comName" => "RekeningPembantuRaw",
                    "loop" => array(
                        "5020" => "sub_hpp_jasa_budget",
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
//                        "extern_id" => ".3020010",//biaya
//                        "extern_nama" => ".efisensi",
                        "extern_id" => "id",//biaya
                        "extern_nama" => "nama",
                        "extern2_nama" => "categori_biaya_nama",
                        "extern2_id" => "categori_biaya_id",
                        "extern3_id" => "work_order_id",//projectid
                        "extern3_nama" => "work_order_nama",
                        "produk_id" => "project_id",//project
                        "produk_nama" => "project_nama",
                        "produk_kode" => "no_spk",
                        "produk_jenis" => ".project",
//                            "barcode" => "barcode",
                        "jml" => "jml",
                        "harga" => "hpp",// harga dpp
                        "hpp" => "hpp",// hpp produk
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),


            ),
        );
        $this->db->trans_start();
        $start = microtime(true);
        $force = isset($_GET["force"]) ? $_GET["force"] : "none";
        $cekjam = date("H");
        $this->load->helper("he_angka");
        $jenisTr = "588st";
        $arrJenisTr = array(
            "588st",
        );
        $main = array();
        $items = array();
        $tableIn_master = array();
        $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();


        $timeTOexec = true;
        if ($timeTOexec) {
            $itemSelect = array(
                //key =>src
                "id" => "produk_dasar_id",
                "nama" => "produk_dasar_nama",
                "jenis" => "jenis",
                "no_spk" => "no_spk",
                "work_order_id" => "sub_fase_id",
                "work_order_nama" => "sub_fase_nama",
                "project_id" => "produk_id",
                "project_nama" => "produk_nama",
                "produk_id" => "produk_dasar_id",
                "jml" => "jml",
                "qty" => "jml",
                "harga" => "harga",
                "categori_biaya_id" => "cat_id",
                "categori_biaya_nama" => "cat_nama",
                "dtime" => "dtime"

            );
            $this->load->model("Mdls/MdlProjectKomposisiWorkorderSub");
            $tr = New MdlProjectKomposisiWorkorderSub;
            $tr->setFilters(array());
            $tr->addFilter("cli='0'");
            $tr->addFilter("status='1'");
            $tr->addFilter("trash='0'");
            $tr->addFilter("jenis_transaksi='sub_wo'");
            $tr->addFilter("jenis_transaksi='sub_wo'");
            $pakaiini = 0;
            if ($pakaiini == 1) {
//                $tr->addFilter("no_spk='001/SPK-INT/962/001/III/2024'");
                $tr->addFilter("no_spk='006/SPK-INT/979/006/III/2024'");
            }
            $this->db->order_by("id", "asc");
            $datas = $tr->lookUpall()->result();
            cekHitam($this->db->last_query());
            $grupSpkItems = array();

            if (count($datas) > 0) {
                $this->load->model("Mdls/MdlProdukProject");
                $p = new MdlProdukProject();


                foreach ($datas as $dataMaster) {
                    $grupSpkItems[$dataMaster->no_spk][] = (array)$dataMaster;
                }
                $selecyKey = key($grupSpkItems);//untuk ambil first key array
                $selectedGrupSpk = $grupSpkItems[$selecyKey];

                //ambil info transaksi startproject untuk ambil ID dan nomer dari produk project
                $produk_id = $selectedGrupSpk[0]["produk_id"];
                $p->addFilter("id='$produk_id'");
                $tempProduk = $p->fectDataProject()->result();
                $mainData = array(
                    "transaksi_id" => $tempProduk[0]->project_start_id,
                    "transaksi_no" => $tempProduk[0]->project_start_nomer,
                    "nomer" => $tempProduk[0]->project_start_nomer,
                    "oleh_id" => $tempProduk[0]->project_started_id,
                    "oleh_nama" => $tempProduk[0]->project_started_name,
                    "dtime" => $tempProduk[0]->project_started_dtime,
                    "pihak_id" => $tempProduk[0]->customer_id,
                    "pihak_nama" => $tempProduk[0]->customer_nama,
                    "pihakID" => $tempProduk[0]->customer_id,
                    "pihakName" => $tempProduk[0]->customer_nama,
                    "project_id" => $tempProduk[0]->id,
                    "project_nama" => $tempProduk[0]->nama,
                    "jenisTr" => "588st",
                    "cabang_id" => "1",
                    "placeID" => "1",
                    "placeName" => "CABANG 1",
                    "cabang_nama" => "CABANG 1",
                    "gudangID" => "-10",
                    "gudangName" => "default branch #1",
                    "gudang_id" => "-10",
                    "gudang_nama" => "default branch #1",
                );

//arrprint($selectedGrupSpk);
//matiHere();
                $iidsUpdate = array();
                $items = array();
                $subharga = 0;
                $subhpp = 0;
                foreach ($selectedGrupSpk as $datas_0) {
//                    arrPrint($datas_0);
                    $iidsUpdate[$datas_0["id"]] = $datas_0["id"];
                    foreach ($itemSelect as $key => $src) {
                        $items[$datas_0["id"]][$key] = $datas_0[$src];
                    }
                    if ($datas_0["jenis"] == "produk") {
                        $key_kategori = "sub_harga_produk_budget";
                        $key_kategori2 = "sub_hpp_produk_budget";
                        $hpp = ($datas_0["hrg_hpp"] == 0) ? $datas_0["harga"] : $datas_0["hrg_hpp"];
//                        $hpp = $datas_0["hrg_hpp"] == 0 ? $datas_0["jml"] * $datas_0["harga"] : $datas_0["jml"] * $datas_0["hrg_hpp"];
                    }
                    else {
                        //jasa
                        $key_kategori = "sub_harga_jasa_budget";
                        $key_kategori2 = "sub_hpp_jasa_budget";
                        $hpp = $datas_0["harga"];
//                        $hpp = $datas_0["jml"] * $datas_0["harga"];
                    }


                    $subharga += ($datas_0["jml"] * $datas_0["harga"]);
                    $subhpp += ($datas_0["jml"] * $hpp);
                    $items[$datas_0["id"]][$key_kategori] = $datas_0["jml"] * $datas_0["harga"];
                    $items[$datas_0["id"]][$key_kategori2] = $datas_0["jml"] * $hpp;
                    $items[$datas_0["id"]]["hpp"] = $hpp;
                    $items[$datas_0["id"]]["subtotal"] = $datas_0["jml"] * $datas_0["harga"];
                    $items[$datas_0["id"]]["transaksi_id"] = $tempProduk[0]->project_start_id;
                    $items[$datas_0["id"]]["transaksi_no"] = $tempProduk[0]->project_start_nomer;
                    $items[$datas_0["id"]]["oleh_id"] = $tempProduk[0]->project_started_id;
                    $items[$datas_0["id"]]["oleh_nama"] = $tempProduk[0]->project_started_name;
                    $items[$datas_0["id"]]["placeID"] = "1";
                    $items[$datas_0["id"]]["placeName"] = "Cabang 1";
                    $items[$datas_0["id"]]["cabang_id"] = "1";
                    $items[$datas_0["id"]]["cabang_nama"] = "Cabang 1";
                    $items[$datas_0["id"]]["gudangID"] = "-10";
                    $items[$datas_0["id"]]["gudangName"] = "default branch #1";

                    $mainData["dtime"] = $datas_0["dtime"];

                }


                $mainData["harga_budget"] = $subharga;
                $mainData["hpp_budget"] = $subhpp;
                $mainData["dppPpn"] = $subharga;
                $mainData["ppn"] = $subharga * (my_ppn_factor() / 100);
                $mainData["piutang_dagang"] = $subharga + $mainData["ppn"];
//                $mainData["piutang_dagang"] = $subharga + ($subharga * (my_ppn_factor() / 100));

            }

            //mulai untuk jurnal
//            arrPrint($mainData);
//            mati_disini(__LINE__);
//            arrPrint($items);
            if (count($mainData) > 0 && count($iidsUpdate) > 0) {
                $dtime = $mainData["dtime"];
                $fulldate = $mainData["fulldate"];
                $jenisTrName = "posting SPK";
                $oleh_nama = $mainData["oleh_nama"];
                $this->jenisTr = $jenis = $mainData["jenis"];
                $buildTablesMaster = $component["master"];
                if (sizeof($buildTablesMaster) > 0) {
                    $bCtr = 0;
                    foreach ($buildTablesMaster as $buildTablesMaster_specs) {
                        $bCtr++;
                        $mdlName = $buildTablesMaster_specs['comName'];
                        // if (substr($mdlName, 0, 1) == "{") {
                        //     $mdlName = trim($mdlName, "{");
                        //     $mdlName = trim($mdlName, "}");
                        //     $mdlName = str_replace($mdlName, $main[$mdlName], $mdlName);
                        // }

                        //--- INI UNTUK BUILD TABLES REKENING
                        $mdlName = "Com" . $mdlName;
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();
                        if (isset($buildTablesMaster_specs['loop']) && sizeof($buildTablesMaster_specs['loop']) > 0) {
                            foreach ($buildTablesMaster_specs['loop'] as $key => $val) {
                                if (substr($key, 0, 1) == "{") {
                                    $oldParam = $buildTablesMaster_specs['loop'][$key];
                                    unset($buildTablesMaster_specs['loop'][$key]);
                                    $key = trim($key, "{");
                                    $key = trim($key, "}");
                                    $key = str_replace($key, $main[$key], $key);
                                    $buildTablesMaster_specs['loop'][$key] = $oldParam;
                                }
                            }
                        }
                        if (method_exists($m, "getTableNameMaster")) {
                            // arrPrint($buildTablesMaster_specs);
                            // matiHEre(__LINE__);
                            if (sizeof($m->getTableNameMaster())) {
                                $m->buildTables($buildTablesMaster_specs);
                                // cekHijau(" === build tabel rekening === ");
                            }
                        }
                    }
                }

                $buildTablesDetail = $component["detail"];
                if (sizeof($component["detail"]) > 0) {
                    foreach ($buildTablesDetail as $buildTablesDetail_specs) {
                        foreach ($items as $itemSpec) {
                            $mdlName = $buildTablesDetail_specs['comName'];
                            // cekLime($mdlName);
                            if (substr($mdlName, 0, 1) == "{") {
                                $mdlName = trim($mdlName, "{");
                                $mdlName = trim($mdlName, "}");
                                $mdlName = str_replace($mdlName, $itemSpec[$mdlName], $mdlName);
                            }
                            $mdlName = "Com" . $mdlName;
                            $this->load->model("Coms/" . $mdlName);
                            $m = new $mdlName();

                            if (isset($buildTablesDetail_specs['loop']) && sizeof($buildTablesDetail_specs['loop']) > 0) {
                                foreach ($buildTablesDetail_specs['loop'] as $key => $val) {
                                    if (substr($key, 0, 1) == "{") {
                                        $oldParam = $buildTablesDetail_specs['loop'][$key];
                                        unset($buildTablesDetail_specs['loop'][$key]);
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $itemSpec[$key], $key);
                                        $buildTablesDetail_specs['loop'][$key] = $oldParam;
                                    }
                                }
                            }
                            if (method_exists($m, "getTableNameMaster")) {
                                if (sizeof($m->getTableNameMaster())) {
                                    $m->buildTables($buildTablesDetail_specs);
                                    // cekHitam(" === build tabel rekening === ");
                                }
                            }
                        }
                    }
                }

                $componentGate['master'] = array();
                $componentConfig['master'] = array();
                //==filter nilai, jika NOL tidak dikirim, sesuai config==
                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();

                $iterator = array();
                $componentConfig['master'] = $buildTablesMaster;
                $iterator = $buildTablesMaster;
                $tempTableinMAster = $mainData;

                //region master
                // $iterator = array();
                if (sizeof($iterator) > 0) {
                    $componentConfig['master'] = $iterator;
                    $cCtr = 0;
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $cCtr++;
                        $comName = $tComSpec['comName'];
                        if (substr($comName, 0, 1) == "{") {
                            $comName = trim($comName, "{");
                            $comName = trim($comName, "}");
                            $comName = str_replace($comName, $mainData, $comName);
                        }
                        // $srcGateName = $tComSpec['srcGateName'];
                        // $srcRawGateName = $tComSpec['srcRawGateName'];
                        // cekHere("component # $cCtr: $comName<br>");

                        $dSpec = $mainData;
                        $tmpOutParams = array();
                        if (isset($tComSpec['loop'])) {
                            foreach ($tComSpec['loop'] as $key => $value) {
                                if (substr($key, 0, 1) == "{") {
                                    $key = trim($key, "{");
                                    $key = trim($key, "}");
                                    $key = str_replace($key, $mainData[$key], $key);
                                }
                                $realValue = makeValue($value, $mainData, $mainData, 0);
                                $tmpOutParams['loop'][$key] = $realValue;
                            }
                        }
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {
                                $realValue = makeValue($value, $mainData, $mainData, 0);
                                $tmpOutParams['static'][$key] = $realValue;
                            }
                            if (!isset($tmpOutParams['static']["transaksi_id"])) {
                                $tmpOutParams['static']["transaksi_id"] = "0000";
                            }
                            if (!isset($tmpOutParams['static']["transaksi_no"])) {
                                $tmpOutParams['static']["transaksi_no"] = "0000";
                            }
                            $tmpOutParams['static']["urut"] = $cCtr;
                            $tmpOutParams['static']["fulldate"] = $fulldate;
                            $tmpOutParams['static']["dtime"] = $dtime;
                            $tmpOutParams['static']["keterangan"] = $jenisTrName . " oleh " . $oleh_nama;
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
                        // arrprint($jenis);
                        //                     matiHEre();
                        if ($tobeExecuted) {
                            //----- kiriman gerbang untuk counter mutasi rekening
                            if (method_exists($m, "setTableInMaster")) {
                                $m->setTableInMaster($tempTableinMAster);
                            }
                            if (method_exists($m, "setMain")) {
                                $m->setMain($mainData);
                            }
                            if (method_exists($m, "setJenisTr")) {
                                $m->setJenisTr($jenis);
                            }
                            arrPrint($tmpOutParams);
                            //----- kiriman gerbang untuk counter mutasi rekening
                            $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        }
                        $componentGate['master'][$cCtr] = $tmpOutParams;
                    }
                }
                else {
                    cekKuning("components is not set");
                }
                //endregion

                $buildTablesDetail = $component["detail"];
                if (sizeof($buildTablesDetail) > 0) {
                    foreach ($buildTablesDetail as $buildTablesDetail_specs) {
                        // arrPrint($buildTablesDetail_specs);
                        // arrPrint($buildTablesDetail_specs);
                        foreach ($items as $itemSpec) {
                            $mdlName = $buildTablesDetail_specs['comName'];
                            // cekLime($mdlName);
                            if (substr($mdlName, 0, 1) == "{") {
                                $mdlName = trim($mdlName, "{");
                                $mdlName = trim($mdlName, "}");
                                $mdlName = str_replace($mdlName, $itemSpec[$mdlName], $mdlName);
                            }
                            $mdlName = "Com" . $mdlName;
                            $this->load->model("Coms/" . $mdlName);
                            $m = new $mdlName();

                            if (isset($buildTablesDetail_specs['loop']) && sizeof($buildTablesDetail_specs['loop']) > 0) {
                                foreach ($buildTablesDetail_specs['loop'] as $key => $val) {
                                    if (substr($key, 0, 1) == "{") {
                                        $oldParam = $buildTablesDetail_specs['loop'][$key];
                                        unset($buildTablesDetail_specs['loop'][$key]);
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $itemSpec[$key], $key);
                                        $buildTablesDetail_specs['loop'][$key] = $oldParam;
                                    }
                                }
                            }
                            if (method_exists($m, "getTableNameMaster")) {
                                if (sizeof($m->getTableNameMaster())) {
                                    $m->buildTables($buildTablesDetail_specs);
                                    // cekHitam(" === build tabel rekening === ");
                                }
                            }
                        }
                    }
                }
                //region processing sub-components, if in single step geser ke CLI

                $componentGate['detail'] = array();
                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                $filterNeeded = false;
                $componentConfig['detail'] = $buildTablesDetail;
                $iterator = $buildTablesDetail;
                // $iterator =array();
                if (sizeof($iterator) > 0) {
                    $comsLocation = "Coms";
                    $comsPrefix = "Com";
                    foreach ($iterator as $cCtr => $tComSpec) {
                        // arrprint($tComSpec);
                        $tmpOutParams[$cCtr] = array();
                        $gg = 0;
                        // $srcGateName = $tComSpec['srcGateName'];
                        // if ($componentsDetailLoop == true) {
                        foreach ($items as $id => $dSpec) {
                            // $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $items, $comName);
                            }

                            $mdlName = "$comsPrefix" . ucfirst($comName);
                            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                $filterNeeded = true;
                            }
                            else {
                                $filterNeeded = false;
                            }
                            // cekHere("sub-component: [$comsLocation] $comName, initializing values <br>");

                            $subParams = array();

                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $dSpec[$key], $key);
                                    }

                                    $realValue = makeValue($value, $dSpec, $dSpec, 0);
                                    // cekMErah("$key =>".$realValue);
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
                                    $realValue = makeValue($value, $dSpec, $dSpec, 0);
                                    $subParams['static'][$key] = $realValue;
                                }
                                if (!isset($subParams['static']["transaksi_id"])) {
                                    $subParams['static']["transaksi_id"] = 0000;
                                }
                                if (!isset($subParams['static']["transaksi_no"])) {
                                    $subParams['static']["transaksi_no"] = 0000;
                                }

                                $subParams['static']["fulldate"] = $fulldate;
                                $subParams['static']["dtime"] = $dtime;
                                $subParams['static']["keterangan"] = $jenisTrName . " oleh " . $oleh_nama;
                            }

                            if (sizeof($subParams) > 0) {
                                //                                cekhitam("subparam ada isinya");
                                if ($filterNeeded) {
                                    if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                }
                            }
                            else {
                                cekhitam("subparam TIDAK ada isinya");
                            }
                        }


                        $componentGate['detail'][$cCtr] = $subParams;
                    }
                    // arrPrint($tmpOutParams);
                    // matiHEre($cCtr);

                    foreach ($iterator as $cCtr => $tComSpec) {
                        // $srcGateName = $tComSpec['srcGateName'];
                        foreach ($items as $id => $dSpec) {
                            // $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $items[$id][$comName], $comName);
                            }
                        }
                        cekHere("sub component: [$comsLocation] $comName, sending values " . __LINE__ . "<br>");

                        $mdlName = "$comsPrefix" . ucfirst($comName);
                        $this->load->model("$comsLocation/" . $mdlName);
                        $m = new $mdlName();
                        //===filter value nol, jika harus difilter

                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        // matiHEre($tobeExecuted);
                        if ($tobeExecuted) {
                            //----- kiriman gerbang
                            if (method_exists($m, "setTableInMaster")) {
                                $m->setTableInMaster($tempTableinMAster);
                            }
                            if (method_exists($m, "setDetail")) {
                                $m->setDetail($items);
                            }
                            if (method_exists($m, "setJenisTr")) {
                                $m->setJenisTr($this->jenisTr);
                            }
                            //----- kiriman gerbang
                            $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            cekBiru($this->db->last_query());
                        }
                        else {
                            cekMerah("$comName tidak eksekusi");
                        }

                    }
                }
                else {
                    cekKuning("subcomponents is not set");
                }

                //endregion
            }

            arrPrint($iidsUpdate);
            if (count($iidsUpdate) > 0) {
                $w = new MdlProjectKomposisiWorkorderSub();
                foreach ($iidsUpdate as $idupdate => $ixupdate) {
                    $w->setFilters(array());
                    $dup = $w->updateData(array("id" => "$idupdate"), array("cli" => "1")) or matiHere("failed exec on line " . __LINE__);
                    cekHitam($this->db->last_query());
                }

            }

        }
        validateAllBalances();
//        validateAllBalances($tokoID, $cabangID_validate);
        $end = microtime(true);
        $selesai = $end - $start;


        matiHEre("sengaja mati dulu karena akan dibatalain project yang lama-lama complitt [selesai dalam $selesai]");

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>SELESAI... [$selesai]</h3>");

    }

    public function runBudgetingProject()
    {
        $this->load->model("CustomCounter");
        $this->load->helper("he_angka");
        $component = array(
            "master" => array(
                // jurnal ke 1 piutang dagang/penjualan per WO
                /*
                 * realtive costnem masuk ke COA Hpp produk
                 * costing  masuk ke kategory
                 */
                array(
                    "comName" => "Jurnal",
                    "loop" => array(
//                        "1010020080" => "harga_budget",// piutang dagang project
////                        "2030060" => "ppn",// ppn Kelauran belum faktur
//                        "4030" => "harga_budget",// penjualan kontijensi projek, menggunakan gerbang yang bulat, bukan yang masih desimal (14 desember 2022)

                        "5020" => "hpp_budget",//hpp budget project debet
                        "3020010" => "hpp_budget",//efisiensi kredit
                    ),

                    "static" => array(
                        "cabang_id" => "placeID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                        "transaksi_id" => "transaksi_id",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                array(
                    "comName" => "Rekening",
                    "loop" => array(
//                        "1010020080" => "harga_budget",// piutang dagang project
////                        "2030060" => "ppn",// ppn Keluaran belum faktur
//                        "4030" => "harga_budget",// penjualan projek, menggunakan gerbang yang bulat, bukan yang masih desimal (14 desember 2022)
                        "5020" => "hpp_budget",//hpp budget project
                        "3020010" => "hpp_budget",//efisiensi
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                        "transaksi_id" => "transaksi_id",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
//                //rekening pembantu piutang project
//                array(
//                    "comName" => "RekeningPembantuCustomer",
//                    "loop" => array(
//                        "1010020080" => "harga_budget",// piutang dagang
//                    ),
//                    "static" => array(
//                        "cabang_id" => "placeID",
//                        "extern_id" => "pihakID",
//                        "extern_nama" => "pihakName",
//                        "jenis" => "jenisTr",
//                        // "transaksi_no" => "nomer",
//                    ),
//                    "srcGateName" => "main",
//                    "srcRawGateName" => "main",
//                ),
//                //rekening pembantu penjualan project
//                array(
//                    "comName" => "RekeningPembantuPenjualanKonsumen",// lokal - konsumen
//                    "loop" => array(
//                        "4030" => "harga_budget",// penjualan
//                    ),
//                    "static" => array(
//                        "cabang_id" => "placeID",
//                        "extern_id" => ".4010030",
//                        "extern_nama" => ".project",
//                        "extern2_id" => "pihakID",
//                        "extern2_nama" => "pihakName",
//                        "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
//                        "harga" => "harga_budget",
//                    ),
//                    "srcGateName" => "main",
//                    "srcRawGateName" => "main",
//                ),
                //pembantu ppn belum faktur
//                array(
//                    "comName" => "RekeningPembantuCustomer",
//                    "loop" => array(
//                        "2030060" => "ppn",// ppn out
//                    ),
//                    "static" => array(
//                        "cabang_id" => "placeID",
//                        "extern_id" => "pihakID",
//                        "extern_nama" => "pihakName",
//                        "jenis" => "jenisTr",
//                        // "transaksi_no" => "nomer",
//                    ),
//                    "srcGateName" => "main",
//                    "srcRawGateName" => "main",
//                ),

            ),
            "detail" => array(
                // pembantu efisiensi bahan baku (RAB) => kredit perkategori biaya
                array(
                    "comName" => "RekeningPembantuEfisiensiBiaya",
                    "loop" => array(
                        "3020010" => "sub_hpp_produk_budget",//bahan baku dengan nilai bom masih single produk belum suport multi, jik sudah suport multi pakai yang ke 2
                        // "3020010" => "supplies_bom",//bahan baku dengan nilai bom
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "categori_biaya_id",//id kategori biaya
                        "extern_nama" => "categori_biaya_nama",
                        "extern2_id" => "categori_biaya_id",
                        "extern2_nama" => "categori_biaya_nama",
                        "produk_qty" => "jml",
                        "produk_nilai" => "hpp",
                        "gudang_id" => "gudangID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),
                array(
                    "comName" => "RekeningPembantuEfisiensiBiaya",
                    "loop" => array(
                        "3020010" => "sub_hpp_jasa_budget",//bahan baku dengan nilai bom masih single produk belum suport multi, jik sudah suport multi pakai yang ke 2
                        // "3020010" => "supplies_bom",//bahan baku dengan nilai bom
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "categori_biaya_id",
                        "extern_nama" => "categori_biaya_nama",
//                        "extern2_id" => "categori_biaya_id",
//                        "extern2_nama" => "categori_biaya_nama",
                        "produk_qty" => "jml",
                        "produk_nilai" => "hpp",
                        "gudang_id" => "gudangID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),
                // pembantu LV2 efisiensi (RAB) => kredit per jenis biaya per kategori project, wo,spk
                array(
                    "comName" => "RekeningPembantuEfisiensiBiayaSub",
                    "loop" => array(
                        "3020010" => "sub_hpp_produk_budget",//efisiensi biaya
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id",//biaya nya
                        "extern_nama" => "nama",
                        "extern2_id" => "categori_biaya_id",
                        "extern2_nama" => "categori_biaya_nama",
                        "extern3_id" => "project_id",//projectid
                        "extern3_nama" => "project_nama",
                        "extern4_id" => "work_order_id",//wo id
                        "extern4_nama" => "work_order_nama",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),
                array(
                    "comName" => "RekeningPembantuEfisiensiBiayaSub",
                    "loop" => array(
                        "3020010" => "sub_hpp_jasa_budget",//efisiensi biaya
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id",//biaya nya
                        "extern_nama" => "nama",
                        "extern2_id" => "categori_biaya_id",
                        "extern2_nama" => "categori_biaya_nama",
                        "extern3_id" => "project_id",//projectid
                        "extern3_nama" => "project_nama",
                        "extern4_id" => "work_order_id",//wo id
                        "extern4_nama" => "work_order_nama",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),

                //pembantu hpp produksi bahan baku in
                array(
                    "comName" => "RekeningPembantuBiayaKomposisiProduksi",
                    "loop" => array(
                        "5020" => "sub_hpp_produk_budget", // isi loop adalah overhead,tenaga kerja,biaya kirim
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id", // ID supplies
                        "extern_nama" => "nama", // NAME supplies
                        "extern2_id" => "categori_biaya_id", // ID biaya
                        "extern2_nama" => "categori_biaya_nama", // label biaya
                        "jenis" => "jenisTr",
                        "produk_qty" => "jml",
                        "produk_nilai" => "hpp",
                        "gudang_id" => "gudangID",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),
                array(
                    "comName" => "RekeningPembantuBiayaKomposisiProduksi",
                    "loop" => array(
                        "5020" => "sub_hpp_jasa_budget", // isi loop adalah overhead,tenaga kerja,biaya kirim
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id", // ID supplies
                        "extern_nama" => "nama", // NAME supplies
                        "extern2_id" => "categori_biaya_id", // ID biaya
                        "extern2_nama" => "categori_biaya_nama", // label biaya
                        "jenis" => "jenisTr",
                        "produk_qty" => "jml",
                        "produk_nilai" => "hpp",
                        "gudang_id" => "gudangID",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),

                //nulis raw efisiensi
                array(
                    "comName" => "RekeningPembantuRawItemEfisiensi",
                    "loop" => array(
                        "3020010" => "sub_hpp_produk_budget", // isi loop adalah overhead,tenaga kerja,biaya kirim
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => ".3020010",//biaya
                        "extern_nama" => ".efisensi",
                        "extern2_nama" => "categori_biaya_nama",
                        "extern2_id" => "categori_biaya_id",
                        "extern3_id" => "work_order_id",//projectid
                        "extern3_nama" => "pihakWoProjekName",
                        "extern4_id" => "id",//biaya
                        "extern4_nama" => "nama",
                        "produk_id" => "project_id",//project
                        "produk_nama" => "project_nama",
                        "produk_kode" => "no_spk",
                        "produk_jenis" => ".project",
//                            "barcode" => "barcode",
                        "jml" => "jml",
                        "harga" => "hpp",// harga dpp
                        "hpp" => "hpp",// hpp produk
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),
                array(
                    "comName" => "RekeningPembantuRawItemEfisiensi",
                    "loop" => array(
                        "3020010" => "sub_hpp_jasa_budget", // isi loop adalah overhead,tenaga kerja,biaya kirim
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => ".3020010",//biaya
                        "extern_nama" => ".efisensi",
                        "extern2_nama" => "categori_biaya_nama",
                        "extern2_id" => "categori_biaya_id",
                        "extern3_id" => "work_order_id",//projectid
                        "extern3_nama" => "work_order_nama",
                        "extern4_id" => "id",//biaya
                        "extern4_nama" => "nama",
                        "produk_id" => "project_id",//project
                        "produk_nama" => "project_nama",
                        "produk_kode" => "no_spk",
                        "produk_jenis" => ".project",
//                            "barcode" => "barcode",
                        "jml" => "jml",
                        "harga" => "hpp",// harga dpp
                        "hpp" => "hpp",// hpp produk
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),

                //raw hpp
                array(
                    "comName" => "RekeningPembantuRaw",
                    "loop" => array(
                        "5020" => "sub_hpp_produk_budget", // isi loop adalah overhead,tenaga kerja,biaya kirim
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
//                        "extern_id" => ".3020010",//biaya
//                        "extern_nama" => ".efisensi",
                        "extern_id" => "id",//biaya
                        "extern_nama" => "nama",
                        "extern2_nama" => "categori_biaya_nama",
                        "extern2_id" => "categori_biaya_id",
                        "extern3_id" => "work_order_id",//projectid
                        "extern3_nama" => "pihakWoProjekName",

                        "produk_id" => "project_id",//project
                        "produk_nama" => "project_nama",
                        "produk_kode" => "no_spk",
                        "produk_jenis" => ".project",
//                            "barcode" => "barcode",
                        "jml" => "jml",
                        "harga" => "hpp",// harga dpp
                        "hpp" => "hpp",// hpp produk
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),
                array(
                    "comName" => "RekeningPembantuRaw",
                    "loop" => array(
                        "5020" => "sub_hpp_jasa_budget",
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
//                        "extern_id" => ".3020010",//biaya
//                        "extern_nama" => ".efisensi",
                        "extern_id" => "id",//biaya
                        "extern_nama" => "nama",
                        "extern2_nama" => "categori_biaya_nama",
                        "extern2_id" => "categori_biaya_id",
                        "extern3_id" => "work_order_id",//projectid
                        "extern3_nama" => "work_order_nama",
                        "produk_id" => "project_id",//project
                        "produk_nama" => "project_nama",
                        "produk_kode" => "no_spk",
                        "produk_jenis" => ".project",
//                            "barcode" => "barcode",
                        "jml" => "jml",
                        "harga" => "hpp",// harga dpp
                        "hpp" => "hpp",// hpp produk
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),


            ),
        );
        $this->db->trans_start();
        $start = microtime(true);
        $force = isset($_GET["force"]) ? $_GET["force"] : "none";
        $cekjam = date("H");
        $this->load->helper("he_angka");
        $jenisTr = "588st";
        $arrJenisTr = array(
            "588st",
        );
        $main = array();
        $items = array();
        $tableIn_master = array();
        $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();


        $timeTOexec = true;
        if ($timeTOexec) {
            $itemSelect = array(
                //key =>src
                "id" => "produk_dasar_id",
                "nama" => "produk_dasar_nama",
                "jenis" => "jenis",
                "no_spk" => "no_spk",
                "work_order_id" => "sub_fase_id",
                "work_order_nama" => "sub_fase_nama",
                "project_id" => "produk_id",
                "project_nama" => "produk_nama",
                "produk_id" => "produk_dasar_id",
                "jml" => "jml",
                "qty" => "jml",
                "harga" => "harga",
                "categori_biaya_id" => "cat_id",
                "categori_biaya_nama" => "cat_nama",
                "dtime" => "dtime"

            );
            $this->load->model("Mdls/MdlProjectKomposisiWorkorderSub");
            $tr = New MdlProjectKomposisiWorkorderSub;
            $tr->setFilters(array());
            $tr->addFilter("cli='0'");
            $tr->addFilter("status='1'");
            $tr->addFilter("trash='0'");
            $tr->addFilter("jenis_transaksi='sub_wo'");
            $tr->addFilter("jenis_transaksi='sub_wo'");
            $pakaiini = 0;
            if ($pakaiini == 1) {
//                $tr->addFilter("no_spk='001/SPK-INT/962/001/III/2024'");
                $tr->addFilter("no_spk='006/SPK-INT/979/006/III/2024'");
            }
            $this->db->order_by("id", "asc");
            $datas = $tr->lookUpall()->result();
            cekHitam($this->db->last_query());
            $grupSpkItems = array();

            if (count($datas) > 0) {
                $this->load->model("Mdls/MdlProdukProject");
                $p = new MdlProdukProject();


                foreach ($datas as $dataMaster) {
                    $grupSpkItems[$dataMaster->no_spk][] = (array)$dataMaster;
                }
                $selecyKey = key($grupSpkItems);//untuk ambil first key array
                $selectedGrupSpk = $grupSpkItems[$selecyKey];

                //ambil info transaksi startproject untuk ambil ID dan nomer dari produk project
                $produk_id = $selectedGrupSpk[0]["produk_id"];
                $p->addFilter("id='$produk_id'");
                $tempProduk = $p->fectDataProject()->result();
                showLast_query("biru");
//                arrPrint($tempProduk);
//                mati_disini(__LINE__);
                $mainData = array(
                    "transaksi_id" => $tempProduk[0]->project_start_id,
                    "transaksi_no" => $tempProduk[0]->project_start_nomer,
                    "nomer" => $tempProduk[0]->project_start_nomer,
                    "oleh_id" => $tempProduk[0]->project_started_id,
                    "oleh_nama" => $tempProduk[0]->project_started_name,
                    "dtime" => $tempProduk[0]->project_started_dtime,
                    "pihak_id" => $tempProduk[0]->customer_id,
                    "pihak_nama" => $tempProduk[0]->customer_nama,
                    "pihakID" => $tempProduk[0]->customer_id,
                    "pihakName" => $tempProduk[0]->customer_nama,
                    "project_id" => $tempProduk[0]->id,
                    "project_nama" => $tempProduk[0]->nama,
                    "jenisTr" => "588st",
                    "cabang_id" => "1",
                    "placeID" => "1",
                    "placeName" => "CABANG 1",
                    "cabang_nama" => "CABANG 1",
                    "gudangID" => "-10",
                    "gudangName" => "default branch #1",
                    "gudang_id" => "-10",
                    "gudang_nama" => "default branch #1",
                );

//arrprint($selectedGrupSpk);
//matiHere();
                arrPrintCyan($mainData);

                $iidsUpdate = array();
                $items = array();
                $subharga = 0;
                $subhpp = 0;
                foreach ($selectedGrupSpk as $datas_0) {
//                    arrPrint($datas_0);
                    $iidsUpdate[$datas_0["id"]] = $datas_0["id"];
                    foreach ($itemSelect as $key => $src) {
                        $items[$datas_0["id"]][$key] = $datas_0[$src];
                    }
                    if ($datas_0["jenis"] == "produk") {
                        $key_kategori = "sub_harga_produk_budget";
                        $key_kategori2 = "sub_hpp_produk_budget";
                        $hpp = ($datas_0["hrg_hpp"] == 0) ? $datas_0["harga"] : $datas_0["hrg_hpp"];
//                        $hpp = $datas_0["hrg_hpp"] == 0 ? $datas_0["jml"] * $datas_0["harga"] : $datas_0["jml"] * $datas_0["hrg_hpp"];
                    }
                    else {
                        //jasa
                        $key_kategori = "sub_harga_jasa_budget";
                        $key_kategori2 = "sub_hpp_jasa_budget";
                        $hpp = $datas_0["harga"];
//                        $hpp = $datas_0["jml"] * $datas_0["harga"];
                    }


                    $subharga += ($datas_0["jml"] * $datas_0["harga"]);
                    $subhpp += ($datas_0["jml"] * $hpp);
                    $items[$datas_0["id"]][$key_kategori] = $datas_0["jml"] * $datas_0["harga"];
                    $items[$datas_0["id"]][$key_kategori2] = $datas_0["jml"] * $hpp;
                    $items[$datas_0["id"]]["hpp"] = $hpp;
                    $items[$datas_0["id"]]["subtotal"] = $datas_0["jml"] * $datas_0["harga"];
                    $items[$datas_0["id"]]["transaksi_id"] = $tempProduk[0]->project_start_id;
                    $items[$datas_0["id"]]["transaksi_no"] = $tempProduk[0]->project_start_nomer;
                    $items[$datas_0["id"]]["oleh_id"] = $tempProduk[0]->project_started_id;
                    $items[$datas_0["id"]]["oleh_nama"] = $tempProduk[0]->project_started_name;
                    $items[$datas_0["id"]]["placeID"] = "1";
                    $items[$datas_0["id"]]["placeName"] = "Cabang 1";
                    $items[$datas_0["id"]]["cabang_id"] = "1";
                    $items[$datas_0["id"]]["cabang_nama"] = "Cabang 1";
                    $items[$datas_0["id"]]["gudangID"] = "-10";
                    $items[$datas_0["id"]]["gudangName"] = "default branch #1";

                    $mainData["dtime"] = $datas_0["dtime"];

                }


                $mainData["harga_budget"] = $subharga;
                $mainData["hpp_budget"] = $subhpp;
                $mainData["dppPpn"] = $subharga;
                $mainData["ppn"] = $subharga * (my_ppn_factor() / 100);
                $mainData["piutang_dagang"] = $subharga + $mainData["ppn"];
//                $mainData["piutang_dagang"] = $subharga + ($subharga * (my_ppn_factor() / 100));

            }

            //mulai untuk jurnal
//            arrPrint($mainData);
//            mati_disini(__LINE__);
//            arrPrint($items);
            if (count($mainData) > 0 && count($iidsUpdate) > 0) {
                $dtime = $mainData["dtime"];
                $fulldate = $mainData["fulldate"];
                $jenisTrName = "posting SPK";
                $oleh_nama = $mainData["oleh_nama"];
                $this->jenisTr = $jenis = $mainData["jenis"];
                $buildTablesMaster = $component["master"];
                if (sizeof($buildTablesMaster) > 0) {
                    $bCtr = 0;
                    foreach ($buildTablesMaster as $buildTablesMaster_specs) {
                        $bCtr++;
                        $mdlName = $buildTablesMaster_specs['comName'];
                        // if (substr($mdlName, 0, 1) == "{") {
                        //     $mdlName = trim($mdlName, "{");
                        //     $mdlName = trim($mdlName, "}");
                        //     $mdlName = str_replace($mdlName, $main[$mdlName], $mdlName);
                        // }

                        //--- INI UNTUK BUILD TABLES REKENING
                        $mdlName = "Com" . $mdlName;
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();
                        if (isset($buildTablesMaster_specs['loop']) && sizeof($buildTablesMaster_specs['loop']) > 0) {
                            foreach ($buildTablesMaster_specs['loop'] as $key => $val) {
                                if (substr($key, 0, 1) == "{") {
                                    $oldParam = $buildTablesMaster_specs['loop'][$key];
                                    unset($buildTablesMaster_specs['loop'][$key]);
                                    $key = trim($key, "{");
                                    $key = trim($key, "}");
                                    $key = str_replace($key, $main[$key], $key);
                                    $buildTablesMaster_specs['loop'][$key] = $oldParam;
                                }
                            }
                        }
                        if (method_exists($m, "getTableNameMaster")) {
                            // arrPrint($buildTablesMaster_specs);
                            // matiHEre(__LINE__);
                            if (sizeof($m->getTableNameMaster())) {
                                $m->buildTables($buildTablesMaster_specs);
                                // cekHijau(" === build tabel rekening === ");
                            }
                        }
                    }
                }

                $buildTablesDetail = $component["detail"];
                if (sizeof($component["detail"]) > 0) {
                    foreach ($buildTablesDetail as $buildTablesDetail_specs) {
                        foreach ($items as $itemSpec) {
                            $mdlName = $buildTablesDetail_specs['comName'];
                            // cekLime($mdlName);
                            if (substr($mdlName, 0, 1) == "{") {
                                $mdlName = trim($mdlName, "{");
                                $mdlName = trim($mdlName, "}");
                                $mdlName = str_replace($mdlName, $itemSpec[$mdlName], $mdlName);
                            }
                            $mdlName = "Com" . $mdlName;
                            $this->load->model("Coms/" . $mdlName);
                            $m = new $mdlName();

                            if (isset($buildTablesDetail_specs['loop']) && sizeof($buildTablesDetail_specs['loop']) > 0) {
                                foreach ($buildTablesDetail_specs['loop'] as $key => $val) {
                                    if (substr($key, 0, 1) == "{") {
                                        $oldParam = $buildTablesDetail_specs['loop'][$key];
                                        unset($buildTablesDetail_specs['loop'][$key]);
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $itemSpec[$key], $key);
                                        $buildTablesDetail_specs['loop'][$key] = $oldParam;
                                    }
                                }
                            }
                            if (method_exists($m, "getTableNameMaster")) {
                                if (sizeof($m->getTableNameMaster())) {
                                    $m->buildTables($buildTablesDetail_specs);
                                    // cekHitam(" === build tabel rekening === ");
                                }
                            }
                        }
                    }
                }

                $componentGate['master'] = array();
                $componentConfig['master'] = array();
                //==filter nilai, jika NOL tidak dikirim, sesuai config==
                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();

                $iterator = array();
                $componentConfig['master'] = $buildTablesMaster;
                $iterator = $buildTablesMaster;
                $tempTableinMAster = $mainData;

                //region master
                // $iterator = array();
                if (sizeof($iterator) > 0) {
                    $componentConfig['master'] = $iterator;
                    $cCtr = 0;
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $cCtr++;
                        $comName = $tComSpec['comName'];
                        if (substr($comName, 0, 1) == "{") {
                            $comName = trim($comName, "{");
                            $comName = trim($comName, "}");
                            $comName = str_replace($comName, $mainData, $comName);
                        }
                        // $srcGateName = $tComSpec['srcGateName'];
                        // $srcRawGateName = $tComSpec['srcRawGateName'];
                        // cekHere("component # $cCtr: $comName<br>");

                        $dSpec = $mainData;
                        $tmpOutParams = array();
                        if (isset($tComSpec['loop'])) {
                            foreach ($tComSpec['loop'] as $key => $value) {
                                if (substr($key, 0, 1) == "{") {
                                    $key = trim($key, "{");
                                    $key = trim($key, "}");
                                    $key = str_replace($key, $mainData[$key], $key);
                                }
                                $realValue = makeValue($value, $mainData, $mainData, 0);
                                $tmpOutParams['loop'][$key] = $realValue;
                            }
                        }
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {
                                $realValue = makeValue($value, $mainData, $mainData, 0);
                                $tmpOutParams['static'][$key] = $realValue;
                            }
                            if (!isset($tmpOutParams['static']["transaksi_id"])) {
                                $tmpOutParams['static']["transaksi_id"] = "0000";
                            }
                            if (!isset($tmpOutParams['static']["transaksi_no"])) {
                                $tmpOutParams['static']["transaksi_no"] = "0000";
                            }
                            $tmpOutParams['static']["urut"] = $cCtr;
                            $tmpOutParams['static']["fulldate"] = $fulldate;
                            $tmpOutParams['static']["dtime"] = $dtime;
                            $tmpOutParams['static']["keterangan"] = $jenisTrName . " oleh " . $oleh_nama;
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
                        // arrprint($jenis);
                        //                     matiHEre();
                        if ($tobeExecuted) {
                            //----- kiriman gerbang untuk counter mutasi rekening
                            if (method_exists($m, "setTableInMaster")) {
                                $m->setTableInMaster($tempTableinMAster);
                            }
                            if (method_exists($m, "setMain")) {
                                $m->setMain($mainData);
                            }
                            if (method_exists($m, "setJenisTr")) {
                                $m->setJenisTr($jenis);
                            }
                            arrPrint($tmpOutParams);
                            //----- kiriman gerbang untuk counter mutasi rekening
                            $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        }
                        $componentGate['master'][$cCtr] = $tmpOutParams;
                    }
                }
                else {
                    cekKuning("components is not set");
                }
                //endregion

                $buildTablesDetail = $component["detail"];
                if (sizeof($buildTablesDetail) > 0) {
                    foreach ($buildTablesDetail as $buildTablesDetail_specs) {
                        // arrPrint($buildTablesDetail_specs);
                        // arrPrint($buildTablesDetail_specs);
                        foreach ($items as $itemSpec) {
                            $mdlName = $buildTablesDetail_specs['comName'];
                            // cekLime($mdlName);
                            if (substr($mdlName, 0, 1) == "{") {
                                $mdlName = trim($mdlName, "{");
                                $mdlName = trim($mdlName, "}");
                                $mdlName = str_replace($mdlName, $itemSpec[$mdlName], $mdlName);
                            }
                            $mdlName = "Com" . $mdlName;
                            $this->load->model("Coms/" . $mdlName);
                            $m = new $mdlName();

                            if (isset($buildTablesDetail_specs['loop']) && sizeof($buildTablesDetail_specs['loop']) > 0) {
                                foreach ($buildTablesDetail_specs['loop'] as $key => $val) {
                                    if (substr($key, 0, 1) == "{") {
                                        $oldParam = $buildTablesDetail_specs['loop'][$key];
                                        unset($buildTablesDetail_specs['loop'][$key]);
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $itemSpec[$key], $key);
                                        $buildTablesDetail_specs['loop'][$key] = $oldParam;
                                    }
                                }
                            }
                            if (method_exists($m, "getTableNameMaster")) {
                                if (sizeof($m->getTableNameMaster())) {
                                    $m->buildTables($buildTablesDetail_specs);
                                    // cekHitam(" === build tabel rekening === ");
                                }
                            }
                        }
                    }
                }

                //region processing sub-components, if in single step geser ke CLI

                $componentGate['detail'] = array();
                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                $filterNeeded = false;
                $componentConfig['detail'] = $buildTablesDetail;
                $iterator = $buildTablesDetail;
                // $iterator =array();
                if (sizeof($iterator) > 0) {
                    $comsLocation = "Coms";
                    $comsPrefix = "Com";
                    foreach ($iterator as $cCtr => $tComSpec) {
                        // arrprint($tComSpec);
                        $tmpOutParams[$cCtr] = array();
                        $gg = 0;
                        // $srcGateName = $tComSpec['srcGateName'];
                        // if ($componentsDetailLoop == true) {
                        foreach ($items as $id => $dSpec) {
                            // $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $items, $comName);
                            }

                            $mdlName = "$comsPrefix" . ucfirst($comName);
                            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                $filterNeeded = true;
                            }
                            else {
                                $filterNeeded = false;
                            }
                            // cekHere("sub-component: [$comsLocation] $comName, initializing values <br>");

                            $subParams = array();

                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $dSpec[$key], $key);
                                    }

                                    $realValue = makeValue($value, $dSpec, $dSpec, 0);
                                    // cekMErah("$key =>".$realValue);
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
                                    $realValue = makeValue($value, $dSpec, $dSpec, 0);
                                    $subParams['static'][$key] = $realValue;
                                }
                                if (!isset($subParams['static']["transaksi_id"])) {
                                    $subParams['static']["transaksi_id"] = 0000;
                                }
                                if (!isset($subParams['static']["transaksi_no"])) {
                                    $subParams['static']["transaksi_no"] = 0000;
                                }

                                $subParams['static']["fulldate"] = $fulldate;
                                $subParams['static']["dtime"] = $dtime;
                                $subParams['static']["keterangan"] = $jenisTrName . " oleh " . $oleh_nama;
                            }

                            if (sizeof($subParams) > 0) {
                                //                                cekhitam("subparam ada isinya");
                                if ($filterNeeded) {
                                    if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                }
                            }
                            else {
                                cekhitam("subparam TIDAK ada isinya");
                            }
                        }


                        $componentGate['detail'][$cCtr] = $subParams;
                    }
                    // arrPrint($tmpOutParams);
                    // matiHEre($cCtr);

                    foreach ($iterator as $cCtr => $tComSpec) {
                        // $srcGateName = $tComSpec['srcGateName'];
                        foreach ($items as $id => $dSpec) {
                            // $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $items[$id][$comName], $comName);
                            }
                        }
                        cekHere("sub component: [$comsLocation] $comName, sending values " . __LINE__ . "<br>");

                        $mdlName = "$comsPrefix" . ucfirst($comName);
                        $this->load->model("$comsLocation/" . $mdlName);
                        $m = new $mdlName();
                        //===filter value nol, jika harus difilter

                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        // matiHEre($tobeExecuted);
                        if ($tobeExecuted) {
                            //----- kiriman gerbang
                            if (method_exists($m, "setTableInMaster")) {
                                $m->setTableInMaster($tempTableinMAster);
                            }
                            if (method_exists($m, "setDetail")) {
                                $m->setDetail($items);
                            }
                            if (method_exists($m, "setJenisTr")) {
                                $m->setJenisTr($this->jenisTr);
                            }
                            //----- kiriman gerbang
                            $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            cekBiru($this->db->last_query());
                        }
                        else {
                            cekMerah("$comName tidak eksekusi");
                        }

                    }
                }
                else {
                    cekKuning("subcomponents is not set");
                }

                //endregion
            }

//            arrPrint($iidsUpdate);
            if (count($iidsUpdate) > 0) {
                $w = new MdlProjectKomposisiWorkorderSub();
                foreach ($iidsUpdate as $idupdate => $ixupdate) {
                    $w->setFilters(array());
                    $dup = $w->updateData(array("id" => "$idupdate"), array("cli" => "1")) or matiHere("failed exec on line " . __LINE__);
                    cekHitam($this->db->last_query());
                }

            }

        }
        validateAllBalances();
//        validateAllBalances($tokoID, $cabangID_validate);
        $end = microtime(true);
        $selesai = $end - $start;


//        matiHEre("sengaja mati dulu karena akan dibatalain project yang lama-lama complitt [selesai dalam $selesai]");

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>SELESAI... [$selesai]</h3>");
    }

    public function cek_cliTransaksi()
    {
//        header("refresh:2");
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
        $tr->addFilter("cli='0'");
        // bila ada trID dari URL, maka ini adalah cek manual, tidak boleh close commit !!!
        if ($getTrID > 0) {
            $tr->addFilter("id='$getTrID'");

            $addJudul = "<br>cek manual";
        }
        $trTmp = $tr->lookupAll()->result();
        cekHere($this->db->last_query() . "<br>" . sizeof($trTmp));

        if (sizeof($trTmp) > 0) {
            $trID_cli = $trTmp[0]->id;
            $trTmpCabangID = $trTmp[0]->cabang_id;
            $trTmpNomer = $trTmp[0]->nomer;
            $trTmpLabel = $trTmp[0]->jenis_label;
            $trTmpDtime = $trTmp[0]->dtime;
            $trTmpDtimeNow = date("Y-m-d H:i:s");
            $selisih_detik = timeDiff($trTmpDtime, $trTmpDtimeNow);
            $batas_detik = 900;// 15 menit
            if ($selisih_detik > $batas_detik) {
                $pesan = "Transkasi $trTmpLabel nomer $trTmpNomer belum selesai dalam waktu 15 menit. Silahkan dicek.";
                cekMerah("$pesan");

            }
        }
        else {
            $stopDate = dtimeNow();
            cekMerah(":: TIDAK ADA yang perlu di-CLI-kan ::
                    <br>start: $startDate<br>stop: $stopDate<br>butuh waktu: " . timeDiff($startDate, $stopDate));
        }

    }

    public function run_cliTransaksiPacther()
    {
//        header("refresh:2");
//        mati_disini();
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
//        $tr->addFilter("cli='0'");
        // bila ada trID dari URL, maka ini adalah cek manual, tidak boleh close commit !!!
        if ($getTrID > 0) {
            $tr->addFilter("id='$getTrID'");

            $addJudul = "<br>cek manual";
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

            cekHitam(":: jenisTrMaster-> $jenisTrMaster :: jenisTr-> $jenisTr ::");
//            cekPink2("config CORE");
//            arrPrint($configCore);
//            cekPink2("=============");
//arrPrintWebs($registryGates);
            //region BUILD TABEL DATABASE OTOMATIS
            $cliComponent = "components";
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

//mati_disini(__LINE__);

            $this->db->trans_start();


            //region ----------subcomponents by cli
            //<editor-fold desc="----------subcomponents by cli">
            $paramPatchers = $this->config->item('heTransaksi_paramPatchers') != null ? $this->config->item('heTransaksi_paramPatchers') : array();
            $paramForceFillers = $this->config->item('heTransaksi_paramForceFillers') != null ? $this->config->item('heTransaksi_paramForceFillers') : array();
            $validateSubComponent = $this->config->item('heTransaksi_validateComponentDetail') != null ? $this->config->item('heTransaksi_validateComponentDetail') : array();

            $componentGate['detail'] = array();
            $componentConfig['master'] = array();
            $componentConfig['detail'] = array();
            if (isset($configCore['relativeComponets']) && $configCore['relativeComponets'] == true) {
                $iterator = isset($registryGates['revert']['jurnal']['detail']) ? $registryGates['revert']['jurnal']['detail'] : array();
                $revertedTarget = $registryGates['main']['pihakExternID'];
                $componentConfig['detail'] = $iterator;
                $componentConfig['master'] = isset($registryGates['revert']['jurnal']['master']) ? $registryGates['revert']['jurnal']['master'] : array();
            }
            else {
                $iterator = isset($configCore[$cliComponent][$jenisTr]['detail']) ? $configCore[$cliComponent][$jenisTr]['detail'] : array();
                $componentConfig['detail'] = $iterator;
                $componentConfig['master'] = isset($configCore[$cliComponent][$jenisTr]['master']) ? $configCore[$cliComponent][$jenisTr]['master'] : array();

                $revertedTarget = "";

            }


            $iterator = array();
            $iterator = array(
                array(
                    "comName" => "RekeningPembantuProdukRiil",
                    "loop" => array(
                        "1010030040" => "sub_harga_produk",//persediaan produk riil
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id",
                        "extern_nama" => "name",
                        "produk_qty" => "qty",
                        "produk_nilai" => "harga_produk",
                        "gudang_id" => "gudangID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items10_sum",
                    "srcRawGateName" => "items10_sum",
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
                ),
                array(
                    "comName" => "RekeningPembantuProdukRiil",
                    "loop" => array(
                        "1010030040" => "-sub_harga_produk",//persediaan produk riil
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id",
                        "extern_nama" => "name",
                        "produk_qty" => "-qty",
                        "produk_nilai" => "harga_produk",
                        "gudang_id" => "gudangID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items10_sum",
                    "srcRawGateName" => "items10_sum",
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
                ),
                array(
                    "comName" => "RekeningPembantuProduk",
                    "loop" => array(
                        "1010030030" => "sub_harga_produk",//persediaan produk
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id",
                        "extern_nama" => "name",
                        "produk_qty" => "qty",
                        "produk_nilai" => "harga_produk",
                        "gudang_id" => "gudangID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                        "supplierID" => "pihakID",
                    ),
                    "srcGateName" => "items10_sum",
                    "srcRawGateName" => "items10_sum",
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
                ),
            );
//arrprint($iteraror);
//            matiHere(__LINE__);
            $subComModel = array();
            if (sizeof($iterator) > 0) {
//                arrPrintKuning($iterator);
                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                $filterNeeded = false;

                $arrRekeningLoop = array();

//                if (in_array($mdlName, $compValidators)) {//perlu validasi filter
//                    $filterNeeded = true;
//                }
//                arrPrint($iterator);
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName_orig = $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $loopRequire = isset($tComSpec['loopRequire']) ? $tComSpec['loopRequire'] : false;
                    $srcRawGateName = $tComSpec['srcRawGateName'];

                    echo "sub-component: $comName, $srcGateName, initializing values <br>";

                    $tmpOutParams[$cCtr] = array();
                    if (isset($registryGates[$srcGateName]) && sizeof($registryGates[$srcGateName]) > 0) {
//arrPrint($registryGates[$srcGateName]);
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
//arrprint($registryGates[$srcGateName][$id]);
                                    cekHitam($realValue . ":: " . $key);
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
//                            arrPrint($subParams);
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
//                matiHEre();
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
                    cekHere("::::: $comName :::::");


                    echo __LINE__ . " sub $cCtr component #$it: $comName, sending values**** <br>";

                    if ($comName != NULL) {
//cekHere(":: $comName ::");
                        $mdlName = "Com" . ucfirst($comName);
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();
//arrprint($tmpOutParams[$cCtr]);
//matiHere(__LINE__);
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
            //endregion
//matiHEre();


            //---VALIDASI QTY/JML, OUT/IN
            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                if (sizeof($validateSubComponent) > 0) {
//                cekKuning($validateSubComponent);
//                cekPink($subComModel);
                    if (sizeof($subComModel) > 0) {

                        if (isset($validateSubComponent['enabled']) && ($validateSubComponent['enabled'] == true)) {

                            $arrRekeningItems = array();
                            if (!in_array($jenisTrMaster, $validateSubComponent['jenisTrException'])) {

                                $qtyValidate = false;
                                foreach ($subComModel as $rek => $subCom) {
                                    if (in_array($subCom, $validateSubComponent['subComponent']['detail'])) {
                                        $subComs = "Com" . $subCom;
                                        $this->load->model("Coms/" . $subComs);
                                        $md = New $subComs();
                                        $tbl = $md->getTableNameMaster()['mutasi'];
                                        $tblName = "_" . $tbl . "__" . str_replace(" ", "_", $rek);
                                        $md->setTableName($tblName);
                                        $md->addFilter("transaksi_id='$transaksiID'");
                                        $mdTmp = $md->lookupAll()->result();
                                        showLast_query("biru");
//                                cekHijau($mdTmp);
                                        if (sizeof($mdTmp) > 0) {
                                            foreach ($mdTmp as $mdSpec) {
                                                $arrRekeningItems[$mdSpec->extern_id]['nama'] = $mdSpec->extern_nama;

                                                if (!isset($arrRekeningItems[$mdSpec->extern_id]['jml_debet'])) {
                                                    $arrRekeningItems[$mdSpec->extern_id]['jml_debet'] = 0;
                                                }
                                                $arrRekeningItems[$mdSpec->extern_id]['jml_debet'] += $mdSpec->qty_debet;

                                                if (!isset($arrRekeningItems[$mdSpec->extern_id]['jml_kredit'])) {
                                                    $arrRekeningItems[$mdSpec->extern_id]['jml_kredit'] = 0;
                                                }
                                                $arrRekeningItems[$mdSpec->extern_id]['jml_kredit'] += $mdSpec->qty_kredit;
                                            }
                                        }

                                        $qtyValidate = true;
                                    }
                                }


                                $arrRequestItems = array();
                                if (isset($registryGates['items']) && (sizeof($registryGates['items']) > 0)) {
                                    foreach ($registryGates['items'] as $pID => $iSpec) {
                                        $arrRequestItems[$pID]['nama'] = $iSpec['nama'];
                                        $arrRequestItems[$pID]['jml'] = $iSpec['jml'];

                                    }
                                }

                                if (count($arrRequestItems) != count($arrRekeningItems)) {
                                    // STOP
                                    $msg = "Jumlah item request " . sizeof($arrRequestItems) . " tidak sama dengan jumlah masuk rekening " . sizeof($arrRekeningItems) . " line " . __LINE__;
                                    mati_disini($msg);
                                }
                                else {
                                    cekHijau("request " . count($arrRequestItems) . ", rekening " . count($arrRekeningItems));
                                    foreach ($arrRequestItems as $pID => $spec) {
                                        $req_nama = $spec['nama'];
                                        $req_jml = $spec['jml'];
                                        $rek_jml = (isset($arrRekeningItems[$pID]['jml_debet']) && ($arrRekeningItems[$pID]['jml_debet'] > 0)) ? $arrRekeningItems[$pID]['jml_debet'] : $arrRekeningItems[$pID]['jml_kredit'];
                                        $rek_jml_debet = (isset($arrRekeningItems[$pID]['jml_debet']) && ($arrRekeningItems[$pID]['jml_debet'] > 0)) ? $arrRekeningItems[$pID]['jml_debet'] : 0;
                                        $rek_jml_kredit = (isset($arrRekeningItems[$pID]['jml_kredit']) && ($arrRekeningItems[$pID]['jml_kredit'] > 0)) ? $arrRekeningItems[$pID]['jml_kredit'] : 0;

                                        if (in_array($jenisTrMaster, $validateSubComponent['dobleValidate'])) {
                                            cekBiru("cek request vs rekDebet dan request vs reqKredit");
                                            // request vs rek qty debet
                                            if ($req_jml != $rek_jml_debet) {
                                                // STOP
                                                $msg = "$req_nama, jumlah request $req_jml tidak sama dengan jumlah masuk rekening $rek_jml_debet";
                                                mati_disini($msg);
                                            }
                                            else {
                                                // LANJUT
                                                cekHijau("$req_nama, request $req_jml, rekening qtyDebet $rek_jml_debet");
                                            }
                                            // request vs rek qty kredit
                                            if ($req_jml != $rek_jml_kredit) {
                                                // STOP
                                                $msg = "$req_nama, jumlah request $req_jml tidak sama dengan jumlah masuk rekening $rek_jml_kredit";
                                                mati_disini($msg);
                                            }
                                            else {
                                                // LANJUT
                                                cekHijau("$req_nama, request $req_jml, rekening qtyKredit $rek_jml_kredit");
                                            }
                                        }
                                        else {
                                            cekBiru("cek request vs rekJml");
                                            if ($req_jml != $rek_jml) {
                                                if ($qtyValidate == true) {
                                                    // STOP
                                                    $msg = "$req_nama, jumlah request $req_jml tidak sama dengan jumlah masuk rekening $rek_jml";
                                                    mati_disini($msg);
                                                }
                                            }
                                            else {
                                                // LANJUT
                                                cekHijau("$req_nama, request $req_jml, rekening $rek_jml");
                                            }

                                        }

                                    }
                                }


                            }
                            else {
                                cekPink2(":: $jenisTrMaster masuk exception ::");
                            }
                        }
                    }
                }

            }

            //region update status sudah dirunning by cli
            $tr = New MdlTransaksi();
            $tr->setFilters(array());
            $where = array(
                "id" => $transaksiID,
            );
            $updateData = array(
                "cli" => 1,
            );
            $tr->updateData($where, $updateData);
            cekHere($this->db->last_query());
            //endregion

            $stopDate = dtimeNow();

            // region menulis ke tabel log time cli
            $cl = New MdlCliLogTime();
            $arrCliData = array(
                "web" => "cli",
                "judul" => "CLI $insertNum $addJudul",
                "waktu_start" => $startDate,
                "waktu_stop" => $stopDate,
                "waktu" => timeDiff($startDate, $stopDate),
                "transaksi_id" => $insertID,
                "nomer" => $insertNum,
                "jenis" => $jenisTr,
                "jenis_master" => $jenisTrMaster,
            );
            $rslt = $cl->addData($arrCliData);
            cekHere($this->db->last_query());
            // endregion


            cekHitam("--- MULAI VALIDATOR ---");
            $this->load->library("Validator");
            $vdt = New Validator();
//            $vdt->validateMasterDetail($trID_cli, $componentConfig['master'], $componentConfig['detail']);


//            if ($getTrID > 0) {
//                mati_disini("...cek MANUAL cli transaksi... rekening pembantu masuk disini (component detail)<br>start: $startDate<br>stop: $stopDate<br>butuh waktu: " . timeDiff($startDate, $stopDate));
//            }


            cekHijau("...tes cli transaksi... rekening pembantu masuk disini (component detail)<br>start: $startDate<br>stop: $stopDate<br>butuh waktu: " . timeDiff($startDate, $stopDate));
//            mati_disini("...tes cli transaksi... rekening pembantu masuk disini (component detail)<br>start: $startDate<br>stop: $stopDate<br>butuh waktu: " . timeDiff($startDate, $stopDate));


            $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
            cekHitam("complittt");
        }
        else {
            $stopDate = dtimeNow();
            cekMerah(":: TIDAK ADA yang perlu di-CLI-kan ::
                    <br>start: $startDate<br>stop: $stopDate<br>butuh waktu: " . timeDiff($startDate, $stopDate));
        }


    }


    public function runSuppliesProject()
    {
        $this->load->helper("he_angka");
        $component = array(
            "master" => array(
                // jurnal ke 1 piutang dagang/penjualan per WO
                /*
                 * realtive costnem masuk ke COA Hpp produk
                 * costing  masuk ke kategory
                 */
                array(
                    "comName" => "Jurnal",
                    "loop" => array(
                        "1010020080" => "harga_budget",// piutang dagang project
//                        "2030060" => "ppn",// ppn Kelauran belum faktur
                        "4010" => "harga_budget",// penjualan projek, menggunakan gerbang yang bulat, bukan yang masih desimal (14 desember 2022)

                        "5020" => "hpp_budget",//hpp budget project debet
                        "3020010" => "hpp_budget",//efisiensi kredit
                    ),

                    "static" => array(
                        "cabang_id" => "placeID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                array(
                    "comName" => "Rekening",
                    "loop" => array(
                        "1010020080" => "harga_budget",// piutang dagang project
//                        "2030060" => "ppn",// ppn Keluaran belum faktur
                        "4010" => "harga_budget",// penjualan projek, menggunakan gerbang yang bulat, bukan yang masih desimal (14 desember 2022)
                        "5020" => "hpp_budget",//hpp budget project
                        "3020010" => "hpp_budget",//efisiensi
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                //rekening pembantu piutang project
                array(
                    "comName" => "RekeningPembantuCustomer",
                    "loop" => array(
                        "1010020080" => "harga_budget",// piutang dagang
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "pihakID",
                        "extern_nama" => "pihakName",
                        "jenis" => "jenisTr",
                        // "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                //rekening pembantu penjualan project
                array(
                    "comName" => "RekeningPembantuPenjualanKonsumen",// lokal - konsumen
                    "loop" => array(
                        "4010" => "harga_budget",// penjualan
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => ".4010030",
                        "extern_nama" => ".project",
                        "extern2_id" => "pihakID",
                        "extern2_nama" => "pihakName",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                        "harga" => "harga_budget",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                //pembantu ppn belum faktur
//                array(
//                    "comName" => "RekeningPembantuCustomer",
//                    "loop" => array(
//                        "2030060" => "ppn",// ppn out
//                    ),
//                    "static" => array(
//                        "cabang_id" => "placeID",
//                        "extern_id" => "pihakID",
//                        "extern_nama" => "pihakName",
//                        "jenis" => "jenisTr",
//                        // "transaksi_no" => "nomer",
//                    ),
//                    "srcGateName" => "main",
//                    "srcRawGateName" => "main",
//                ),

            ),
            "detail" => array(
                // pembantu efisiensi bahan baku (RAB) => kredit perkategori biaya
                array(
                    "comName" => "RekeningPembantuEfisiensiBiaya",
                    "loop" => array(
                        "3020010" => "sub_hpp_produk_budget",//bahan baku dengan nilai bom masih single produk belum suport multi, jik sudah suport multi pakai yang ke 2
                        // "3020010" => "supplies_bom",//bahan baku dengan nilai bom
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "categori_biaya_id",//id kategori biaya
                        "extern_nama" => "categori_biaya_nama",
                        "extern2_id" => "categori_biaya_id",
                        "extern2_nama" => "categori_biaya_nama",
                        "produk_qty" => "jml",
                        "produk_nilai" => "hpp",
                        "gudang_id" => "gudangID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),
                array(
                    "comName" => "RekeningPembantuEfisiensiBiaya",
                    "loop" => array(
                        "3020010" => "sub_hpp_jasa_budget",//bahan baku dengan nilai bom masih single produk belum suport multi, jik sudah suport multi pakai yang ke 2
                        // "3020010" => "supplies_bom",//bahan baku dengan nilai bom
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "categori_biaya_id",
                        "extern_nama" => "categori_biaya_nama",
//                        "extern2_id" => "categori_biaya_id",
//                        "extern2_nama" => "categori_biaya_nama",
                        "produk_qty" => "jml",
                        "produk_nilai" => "hpp",
                        "gudang_id" => "gudangID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),
                // pembantu LV2 efisiensi (RAB) => kredit per jenis biaya per kategori project, wo,spk
                array(
                    "comName" => "RekeningPembantuEfisiensiBiayaSub",
                    "loop" => array(
                        "3020010" => "sub_hpp_produk_budget",//efisiensi biaya
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id",//biaya nya
                        "extern_nama" => "nama",
                        "extern2_id" => "categori_biaya_id",
                        "extern2_nama" => "categori_biaya_nama",
                        "extern3_id" => "project_id",//projectid
                        "extern3_nama" => "project_nama",
                        "extern4_id" => "work_order_id",//wo id
                        "extern4_nama" => "work_order_nama",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),
                array(
                    "comName" => "RekeningPembantuEfisiensiBiayaSub",
                    "loop" => array(
                        "3020010" => "sub_hpp_jasa_budget",//efisiensi biaya
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id",//biaya nya
                        "extern_nama" => "nama",
                        "extern2_id" => "categori_biaya_id",
                        "extern2_nama" => "categori_biaya_nama",
                        "extern3_id" => "project_id",//projectid
                        "extern3_nama" => "project_nama",
                        "extern4_id" => "work_order_id",//wo id
                        "extern4_nama" => "work_order_nama",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),

                //pembantu hpp produksi bahan baku in
                array(
                    "comName" => "RekeningPembantuBiayaKomposisiProduksi",
                    "loop" => array(
                        "5020" => "sub_hpp_produk_budget", // isi loop adalah overhead,tenaga kerja,biaya kirim
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id", // ID supplies
                        "extern_nama" => "nama", // NAME supplies
                        "extern2_id" => "categori_biaya_id", // ID biaya
                        "extern2_nama" => "categori_biaya_nama", // label biaya
                        "jenis" => "jenisTr",
                        "produk_qty" => "jml",
                        "produk_nilai" => "hpp",
                        "gudang_id" => "gudangID",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),
                array(
                    "comName" => "RekeningPembantuBiayaKomposisiProduksi",
                    "loop" => array(
                        "5020" => "sub_hpp_jasa_budget", // isi loop adalah overhead,tenaga kerja,biaya kirim
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id", // ID supplies
                        "extern_nama" => "nama", // NAME supplies
                        "extern2_id" => "categori_biaya_id", // ID biaya
                        "extern2_nama" => "categori_biaya_nama", // label biaya
                        "jenis" => "jenisTr",
                        "produk_qty" => "jml",
                        "produk_nilai" => "hpp",
                        "gudang_id" => "gudangID",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),

                //nulis raw efisiensi
                array(
                    "comName" => "RekeningPembantuRawItemEfisiensi",
                    "loop" => array(
                        "3020010" => "sub_hpp_produk_budget", // isi loop adalah overhead,tenaga kerja,biaya kirim
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => ".3020010",//biaya
                        "extern_nama" => ".efisensi",
                        "extern2_nama" => "categori_biaya_nama",
                        "extern2_id" => "categori_biaya_id",
                        "extern3_id" => "work_order_id",//projectid
                        "extern3_nama" => "pihakWoProjekName",
                        "extern4_id" => "id",//biaya
                        "extern4_nama" => "nama",
                        "produk_id" => "project_id",//project
                        "produk_nama" => "project_nama",
                        "produk_kode" => "no_spk",
                        "produk_jenis" => ".project",
//                            "barcode" => "barcode",
                        "jml" => "jml",
                        "harga" => "hpp",// harga dpp
                        "hpp" => "hpp",// hpp produk
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),
                array(
                    "comName" => "RekeningPembantuRawItemEfisiensi",
                    "loop" => array(
                        "3020010" => "sub_hpp_jasa_budget", // isi loop adalah overhead,tenaga kerja,biaya kirim
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => ".3020010",//biaya
                        "extern_nama" => ".efisensi",
                        "extern2_nama" => "categori_biaya_nama",
                        "extern2_id" => "categori_biaya_id",
                        "extern3_id" => "work_order_id",//projectid
                        "extern3_nama" => "work_order_nama",
                        "extern4_id" => "id",//biaya
                        "extern4_nama" => "nama",
                        "produk_id" => "project_id",//project
                        "produk_nama" => "project_nama",
                        "produk_kode" => "no_spk",
                        "produk_jenis" => ".project",
//                            "barcode" => "barcode",
                        "jml" => "jml",
                        "harga" => "hpp",// harga dpp
                        "hpp" => "hpp",// hpp produk
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),

                //raw hpp
                array(
                    "comName" => "RekeningPembantuRaw",
                    "loop" => array(
                        "5020" => "sub_hpp_produk_budget", // isi loop adalah overhead,tenaga kerja,biaya kirim
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
//                        "extern_id" => ".3020010",//biaya
//                        "extern_nama" => ".efisensi",
                        "extern_id" => "id",//biaya
                        "extern_nama" => "nama",
                        "extern2_nama" => "categori_biaya_nama",
                        "extern2_id" => "categori_biaya_id",
                        "extern3_id" => "work_order_id",//projectid
                        "extern3_nama" => "pihakWoProjekName",

                        "produk_id" => "project_id",//project
                        "produk_nama" => "project_nama",
                        "produk_kode" => "no_spk",
                        "produk_jenis" => ".project",
//                            "barcode" => "barcode",
                        "jml" => "jml",
                        "harga" => "hpp",// harga dpp
                        "hpp" => "hpp",// hpp produk
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),
                array(
                    "comName" => "RekeningPembantuRaw",
                    "loop" => array(
                        "5020" => "sub_hpp_jasa_budget",
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
//                        "extern_id" => ".3020010",//biaya
//                        "extern_nama" => ".efisensi",
                        "extern_id" => "id",//biaya
                        "extern_nama" => "nama",
                        "extern2_nama" => "categori_biaya_nama",
                        "extern2_id" => "categori_biaya_id",
                        "extern3_id" => "work_order_id",//projectid
                        "extern3_nama" => "work_order_nama",
                        "produk_id" => "project_id",//project
                        "produk_nama" => "project_nama",
                        "produk_kode" => "no_spk",
                        "produk_jenis" => ".project",
//                            "barcode" => "barcode",
                        "jml" => "jml",
                        "harga" => "hpp",// harga dpp
                        "hpp" => "hpp",// hpp produk
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),


            ),
        );
        $this->db->trans_start();
        $start = microtime(true);
        $force = isset($_GET["force"]) ? $_GET["force"] : "none";
        $cekjam = date("H");
        $this->load->helper("he_angka");
        $jenisTr = "588st";
        $arrJenisTr = array(
            "588st",
        );
        $main = array();
        $items = array();
        $tableIn_master = array();
        $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();


        $timeTOexec = true;
        if ($timeTOexec) {
            $itemSelect = array(
                //key =>src
                "id" => "produk_dasar_id",
                "nama" => "produk_dasar_nama",
                "jenis" => "jenis",
                "no_spk" => "no_spk",
                "work_order_id" => "sub_fase_id",
                "work_order_nama" => "sub_fase_nama",
                "project_id" => "produk_id",
                "project_nama" => "produk_nama",
                "produk_id" => "produk_dasar_id",
                "jml" => "jml",
                "qty" => "jml",
                "harga" => "harga",
                "categori_biaya_id" => "cat_id",
                "categori_biaya_nama" => "cat_nama",
                "dtime" => "dtime"

            );
            $this->load->model("Mdls/MdlProjectKomposisiWorkorderSub");
            $tr = New MdlProjectKomposisiWorkorderSub;
            $tr->setFilters(array());
            $tr->addFilter("cli='0'");
            $tr->addFilter("status='1'");
            $tr->addFilter("trash='0'");
            $tr->addFilter("jenis_transaksi='sub_wo'");
            $tr->addFilter("jenis_transaksi='sub_wo'");
            $pakaiini = 1;
            if ($pakaiini == 1) {
//                $tr->addFilter("no_spk='001/SPK-INT/962/001/III/2024'");
                $tr->addFilter("no_spk='006/SPK-INT/979/006/III/2024'");
            }
            $this->db->order_by("id", "asc");
            $datas = $tr->lookUpall()->result();
            cekHitam($this->db->last_query());
            $grupSpkItems = array();

            if (count($datas) > 0) {
                $this->load->model("Mdls/MdlProdukProject");
                $p = new MdlProdukProject();


                foreach ($datas as $dataMaster) {
                    $grupSpkItems[$dataMaster->no_spk][] = (array)$dataMaster;
                }
                $selecyKey = key($grupSpkItems);//untuk ambil first key array
                $selectedGrupSpk = $grupSpkItems[$selecyKey];

                //ambil info transaksi startproject untuk ambil ID dan nomer dari produk project
                $produk_id = $selectedGrupSpk[0]["produk_id"];
                $p->addFilter("id='$produk_id'");
                $tempProduk = $p->fectDataProject()->result();
                $mainData = array(
                    "transaksi_id" => $tempProduk[0]->project_start_id,
                    "transaksi_no" => $tempProduk[0]->project_start_nomer,
                    "nomer" => $tempProduk[0]->project_start_nomer,
                    "oleh_id" => $tempProduk[0]->project_started_id,
                    "oleh_nama" => $tempProduk[0]->project_started_name,
                    "dtime" => $tempProduk[0]->project_started_dtime,
                    "pihak_id" => $tempProduk[0]->customer_id,
                    "pihak_nama" => $tempProduk[0]->customer_nama,
                    "pihakID" => $tempProduk[0]->customer_id,
                    "pihakName" => $tempProduk[0]->customer_nama,
                    "project_id" => $tempProduk[0]->id,
                    "project_nama" => $tempProduk[0]->nama,
                    "jenisTr" => "588st",
                    "cabang_id" => "1",
                    "placeID" => "1",
                    "placeName" => "CABANG 1",
                    "cabang_nama" => "CABANG 1",
                    "gudangID" => "-10",
                    "gudangName" => "default branch #1",
                    "gudang_id" => "-10",
                    "gudang_nama" => "default branch #1",
                );

//arrprint($selectedGrupSpk);
//matiHere();
                $iidsUpdate = array();
                $items = array();
                $subharga = 0;
                $subhpp = 0;
                foreach ($selectedGrupSpk as $datas_0) {
//                    arrPrint($datas_0);
                    $iidsUpdate[$datas_0["id"]] = $datas_0["id"];
                    foreach ($itemSelect as $key => $src) {
                        $items[$datas_0["id"]][$key] = $datas_0[$src];
                    }
                    if ($datas_0["jenis"] == "produk") {
                        $key_kategori = "sub_harga_produk_budget";
                        $key_kategori2 = "sub_hpp_produk_budget";
                        $hpp = ($datas_0["hrg_hpp"] == 0) ? $datas_0["harga"] : $datas_0["hrg_hpp"];
//                        $hpp = $datas_0["hrg_hpp"] == 0 ? $datas_0["jml"] * $datas_0["harga"] : $datas_0["jml"] * $datas_0["hrg_hpp"];
                    }
                    else {
                        //jasa
                        $key_kategori = "sub_harga_jasa_budget";
                        $key_kategori2 = "sub_hpp_jasa_budget";
                        $hpp = $datas_0["harga"];
//                        $hpp = $datas_0["jml"] * $datas_0["harga"];
                    }


                    $subharga += ($datas_0["jml"] * $datas_0["harga"]);
                    $subhpp += ($datas_0["jml"] * $hpp);
                    $items[$datas_0["id"]][$key_kategori] = $datas_0["jml"] * $datas_0["harga"];
                    $items[$datas_0["id"]][$key_kategori2] = $datas_0["jml"] * $hpp;
                    $items[$datas_0["id"]]["hpp"] = $hpp;
                    $items[$datas_0["id"]]["subtotal"] = $datas_0["jml"] * $datas_0["harga"];
                    $items[$datas_0["id"]]["transaksi_id"] = $tempProduk[0]->project_start_id;
                    $items[$datas_0["id"]]["transaksi_no"] = $tempProduk[0]->project_start_nomer;
                    $items[$datas_0["id"]]["oleh_id"] = $tempProduk[0]->project_started_id;
                    $items[$datas_0["id"]]["oleh_nama"] = $tempProduk[0]->project_started_name;
                    $items[$datas_0["id"]]["placeID"] = "1";
                    $items[$datas_0["id"]]["placeName"] = "Cabang 1";
                    $items[$datas_0["id"]]["cabang_id"] = "1";
                    $items[$datas_0["id"]]["cabang_nama"] = "Cabang 1";
                    $items[$datas_0["id"]]["gudangID"] = "-10";
                    $items[$datas_0["id"]]["gudangName"] = "default branch #1";

                    $mainData["dtime"] = $datas_0["dtime"];

                }


                $mainData["harga_budget"] = $subharga;
                $mainData["hpp_budget"] = $subhpp;
                $mainData["dppPpn"] = $subharga;
                $mainData["ppn"] = $subharga * (my_ppn_factor() / 100);
                $mainData["piutang_dagang"] = $subharga + $mainData["ppn"];
//                $mainData["piutang_dagang"] = $subharga + ($subharga * (my_ppn_factor() / 100));

            }

            //mulai untuk jurnal
//            arrPrint($mainData);
//            mati_disini(__LINE__);
//            arrPrint($items);
            if (count($mainData) > 0 && count($iidsUpdate) > 0) {
                $dtime = $mainData["dtime"];
                $fulldate = $mainData["fulldate"];
                $jenisTrName = "posting SPK";
                $oleh_nama = $mainData["oleh_nama"];
                $this->jenisTr = $jenis = $mainData["jenis"];
                $buildTablesMaster = $component["master"];
                if (sizeof($buildTablesMaster) > 0) {
                    $bCtr = 0;
                    foreach ($buildTablesMaster as $buildTablesMaster_specs) {
                        $bCtr++;
                        $mdlName = $buildTablesMaster_specs['comName'];
                        // if (substr($mdlName, 0, 1) == "{") {
                        //     $mdlName = trim($mdlName, "{");
                        //     $mdlName = trim($mdlName, "}");
                        //     $mdlName = str_replace($mdlName, $main[$mdlName], $mdlName);
                        // }

                        //--- INI UNTUK BUILD TABLES REKENING
                        $mdlName = "Com" . $mdlName;
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();
                        if (isset($buildTablesMaster_specs['loop']) && sizeof($buildTablesMaster_specs['loop']) > 0) {
                            foreach ($buildTablesMaster_specs['loop'] as $key => $val) {
                                if (substr($key, 0, 1) == "{") {
                                    $oldParam = $buildTablesMaster_specs['loop'][$key];
                                    unset($buildTablesMaster_specs['loop'][$key]);
                                    $key = trim($key, "{");
                                    $key = trim($key, "}");
                                    $key = str_replace($key, $main[$key], $key);
                                    $buildTablesMaster_specs['loop'][$key] = $oldParam;
                                }
                            }
                        }
                        if (method_exists($m, "getTableNameMaster")) {
                            // arrPrint($buildTablesMaster_specs);
                            // matiHEre(__LINE__);
                            if (sizeof($m->getTableNameMaster())) {
                                $m->buildTables($buildTablesMaster_specs);
                                // cekHijau(" === build tabel rekening === ");
                            }
                        }
                    }
                }

                $buildTablesDetail = $component["detail"];
                if (sizeof($component["detail"]) > 0) {
                    foreach ($buildTablesDetail as $buildTablesDetail_specs) {
                        foreach ($items as $itemSpec) {
                            $mdlName = $buildTablesDetail_specs['comName'];
                            // cekLime($mdlName);
                            if (substr($mdlName, 0, 1) == "{") {
                                $mdlName = trim($mdlName, "{");
                                $mdlName = trim($mdlName, "}");
                                $mdlName = str_replace($mdlName, $itemSpec[$mdlName], $mdlName);
                            }
                            $mdlName = "Com" . $mdlName;
                            $this->load->model("Coms/" . $mdlName);
                            $m = new $mdlName();

                            if (isset($buildTablesDetail_specs['loop']) && sizeof($buildTablesDetail_specs['loop']) > 0) {
                                foreach ($buildTablesDetail_specs['loop'] as $key => $val) {
                                    if (substr($key, 0, 1) == "{") {
                                        $oldParam = $buildTablesDetail_specs['loop'][$key];
                                        unset($buildTablesDetail_specs['loop'][$key]);
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $itemSpec[$key], $key);
                                        $buildTablesDetail_specs['loop'][$key] = $oldParam;
                                    }
                                }
                            }
                            if (method_exists($m, "getTableNameMaster")) {
                                if (sizeof($m->getTableNameMaster())) {
                                    $m->buildTables($buildTablesDetail_specs);
                                    // cekHitam(" === build tabel rekening === ");
                                }
                            }
                        }
                    }
                }

                $componentGate['master'] = array();
                $componentConfig['master'] = array();
                //==filter nilai, jika NOL tidak dikirim, sesuai config==
                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();

                $iterator = array();
                $componentConfig['master'] = $buildTablesMaster;
                $iterator = $buildTablesMaster;
                $tempTableinMAster = $mainData;

                //region master
                // $iterator = array();
                if (sizeof($iterator) > 0) {
                    $componentConfig['master'] = $iterator;
                    $cCtr = 0;
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $cCtr++;
                        $comName = $tComSpec['comName'];
                        if (substr($comName, 0, 1) == "{") {
                            $comName = trim($comName, "{");
                            $comName = trim($comName, "}");
                            $comName = str_replace($comName, $mainData, $comName);
                        }
                        // $srcGateName = $tComSpec['srcGateName'];
                        // $srcRawGateName = $tComSpec['srcRawGateName'];
                        // cekHere("component # $cCtr: $comName<br>");

                        $dSpec = $mainData;
                        $tmpOutParams = array();
                        if (isset($tComSpec['loop'])) {
                            foreach ($tComSpec['loop'] as $key => $value) {
                                if (substr($key, 0, 1) == "{") {
                                    $key = trim($key, "{");
                                    $key = trim($key, "}");
                                    $key = str_replace($key, $mainData[$key], $key);
                                }
                                $realValue = makeValue($value, $mainData, $mainData, 0);
                                $tmpOutParams['loop'][$key] = $realValue;
                            }
                        }
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {
                                $realValue = makeValue($value, $mainData, $mainData, 0);
                                $tmpOutParams['static'][$key] = $realValue;
                            }
                            if (!isset($tmpOutParams['static']["transaksi_id"])) {
                                $tmpOutParams['static']["transaksi_id"] = "0000";
                            }
                            if (!isset($tmpOutParams['static']["transaksi_no"])) {
                                $tmpOutParams['static']["transaksi_no"] = "0000";
                            }
                            $tmpOutParams['static']["urut"] = $cCtr;
                            $tmpOutParams['static']["fulldate"] = $fulldate;
                            $tmpOutParams['static']["dtime"] = $dtime;
                            $tmpOutParams['static']["keterangan"] = $jenisTrName . " oleh " . $oleh_nama;
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
                        // arrprint($jenis);
                        //                     matiHEre();
                        if ($tobeExecuted) {
                            //----- kiriman gerbang untuk counter mutasi rekening
                            if (method_exists($m, "setTableInMaster")) {
                                $m->setTableInMaster($tempTableinMAster);
                            }
                            if (method_exists($m, "setMain")) {
                                $m->setMain($mainData);
                            }
                            if (method_exists($m, "setJenisTr")) {
                                $m->setJenisTr($jenis);
                            }
                            arrPrint($tmpOutParams);
                            //----- kiriman gerbang untuk counter mutasi rekening
                            $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        }
                        $componentGate['master'][$cCtr] = $tmpOutParams;
                    }
                }
                else {
                    cekKuning("components is not set");
                }
                //endregion

                $buildTablesDetail = $component["detail"];
                if (sizeof($buildTablesDetail) > 0) {
                    foreach ($buildTablesDetail as $buildTablesDetail_specs) {
                        // arrPrint($buildTablesDetail_specs);
                        // arrPrint($buildTablesDetail_specs);
                        foreach ($items as $itemSpec) {
                            $mdlName = $buildTablesDetail_specs['comName'];
                            // cekLime($mdlName);
                            if (substr($mdlName, 0, 1) == "{") {
                                $mdlName = trim($mdlName, "{");
                                $mdlName = trim($mdlName, "}");
                                $mdlName = str_replace($mdlName, $itemSpec[$mdlName], $mdlName);
                            }
                            $mdlName = "Com" . $mdlName;
                            $this->load->model("Coms/" . $mdlName);
                            $m = new $mdlName();

                            if (isset($buildTablesDetail_specs['loop']) && sizeof($buildTablesDetail_specs['loop']) > 0) {
                                foreach ($buildTablesDetail_specs['loop'] as $key => $val) {
                                    if (substr($key, 0, 1) == "{") {
                                        $oldParam = $buildTablesDetail_specs['loop'][$key];
                                        unset($buildTablesDetail_specs['loop'][$key]);
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $itemSpec[$key], $key);
                                        $buildTablesDetail_specs['loop'][$key] = $oldParam;
                                    }
                                }
                            }
                            if (method_exists($m, "getTableNameMaster")) {
                                if (sizeof($m->getTableNameMaster())) {
                                    $m->buildTables($buildTablesDetail_specs);
                                    // cekHitam(" === build tabel rekening === ");
                                }
                            }
                        }
                    }
                }
                //region processing sub-components, if in single step geser ke CLI

                $componentGate['detail'] = array();
                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                $filterNeeded = false;
                $componentConfig['detail'] = $buildTablesDetail;
                $iterator = $buildTablesDetail;
                // $iterator =array();
                if (sizeof($iterator) > 0) {
                    $comsLocation = "Coms";
                    $comsPrefix = "Com";
                    foreach ($iterator as $cCtr => $tComSpec) {
                        // arrprint($tComSpec);
                        $tmpOutParams[$cCtr] = array();
                        $gg = 0;
                        // $srcGateName = $tComSpec['srcGateName'];
                        // if ($componentsDetailLoop == true) {
                        foreach ($items as $id => $dSpec) {
                            // $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $items, $comName);
                            }

                            $mdlName = "$comsPrefix" . ucfirst($comName);
                            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                $filterNeeded = true;
                            }
                            else {
                                $filterNeeded = false;
                            }
                            // cekHere("sub-component: [$comsLocation] $comName, initializing values <br>");

                            $subParams = array();

                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $dSpec[$key], $key);
                                    }

                                    $realValue = makeValue($value, $dSpec, $dSpec, 0);
                                    // cekMErah("$key =>".$realValue);
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
                                    $realValue = makeValue($value, $dSpec, $dSpec, 0);
                                    $subParams['static'][$key] = $realValue;
                                }
                                if (!isset($subParams['static']["transaksi_id"])) {
                                    $subParams['static']["transaksi_id"] = 0000;
                                }
                                if (!isset($subParams['static']["transaksi_no"])) {
                                    $subParams['static']["transaksi_no"] = 0000;
                                }

                                $subParams['static']["fulldate"] = $fulldate;
                                $subParams['static']["dtime"] = $dtime;
                                $subParams['static']["keterangan"] = $jenisTrName . " oleh " . $oleh_nama;
                            }

                            if (sizeof($subParams) > 0) {
                                //                                cekhitam("subparam ada isinya");
                                if ($filterNeeded) {
                                    if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                }
                            }
                            else {
                                cekhitam("subparam TIDAK ada isinya");
                            }
                        }


                        $componentGate['detail'][$cCtr] = $subParams;
                    }
                    // arrPrint($tmpOutParams);
                    // matiHEre($cCtr);

                    foreach ($iterator as $cCtr => $tComSpec) {
                        // $srcGateName = $tComSpec['srcGateName'];
                        foreach ($items as $id => $dSpec) {
                            // $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $items[$id][$comName], $comName);
                            }
                        }
                        cekHere("sub component: [$comsLocation] $comName, sending values " . __LINE__ . "<br>");

                        $mdlName = "$comsPrefix" . ucfirst($comName);
                        $this->load->model("$comsLocation/" . $mdlName);
                        $m = new $mdlName();
                        //===filter value nol, jika harus difilter

                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        // matiHEre($tobeExecuted);
                        if ($tobeExecuted) {
                            //----- kiriman gerbang
                            if (method_exists($m, "setTableInMaster")) {
                                $m->setTableInMaster($tempTableinMAster);
                            }
                            if (method_exists($m, "setDetail")) {
                                $m->setDetail($items);
                            }
                            if (method_exists($m, "setJenisTr")) {
                                $m->setJenisTr($this->jenisTr);
                            }
                            //----- kiriman gerbang
                            $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            cekBiru($this->db->last_query());
                        }
                        else {
                            cekMerah("$comName tidak eksekusi");
                        }

                    }
                }
                else {
                    cekKuning("subcomponents is not set");
                }

                //endregion
            }

            arrPrint($iidsUpdate);
            if (count($iidsUpdate) > 0) {
                $w = new MdlProjectKomposisiWorkorderSub();
                foreach ($iidsUpdate as $idupdate => $ixupdate) {
                    $w->setFilters(array());
                    $dup = $w->updateData(array("id" => "$idupdate"), array("cli" => "1")) or matiHere("failed exec on line " . __LINE__);
                    cekHitam($this->db->last_query());
                }

            }

        }
        validateAllBalances();
//        validateAllBalances($tokoID, $cabangID_validate);
        $end = microtime(true);
        $selesai = $end - $start;


        matiHEre("complitt [selesai dalam $selesai]");

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>SELESAI... [$selesai]</h3>");
    }

    public function updateGudangID()
    {

        echo "untuk update type_data gudangID menjadi bigint(20) <br>";

        $query = $this->db->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'run_everest_modul' AND COLUMN_NAME = 'gudang_id';");
        $result = $query->result_array();
        $total_table = count($result);
        $this->db->trans_start();

        $sukses = 0;
        foreach ($result as $row) {
            $table_name = $row['TABLE_NAME'];
            $alter = $this->db->query("ALTER TABLE $table_name MODIFY gudang_id BIGINT(20);");
            if ($alter) {
                $sukses++;
            }
        }

        $commit = $this->db->trans_complete() or die("Gagal saat berusaha commit transaction!");

        $return = array(
            "status" => $commit,
            "sukses" => $sukses,
            "gagal" => ($total_table - $sukses),
        );
        echo json_encode($return);
    }

    public function runRejectedProjectFg()
    {
        $this->db->trans_start();

        $this->load->model("Mdls/MdlFifoAverage");
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlProdukProject");
        $this->load->model("CustomCounter");
        $fi = new MdlFifoAverage();
        $rek = new ComRekeningPembantuProduk();
        $p = new MdlProdukProject();
        $t = new MdlTransaksi();
        $t->addFilter("trash_4='1'");
        $t->addFilter("settlement_id='1'");
        $t->addFilter("jenis='588st'");
        $this->db->limit(1);
        $tmpTr = $t->lookUpAll()->result();
        if (count($tmpTr) > 0) {
            $curID = $tmpTr[0]->id;
            $masterID = $tmpTr[0]->id_master;
            cekHitam($curID);
            //region  reject
//        $t->addFilter("trash_4='1'");
            $t->addFilter("id_master='$masterID'");
            $t->addFilter("jenis='588strj'");
            $this->db->limit(1);
            $tmpReject = $t->lookUpAll()->result();
            $oleh_id = $tmpReject[0]->oleh_id;
            $oleh_nama = $tmpReject[0]->oleh_nama;
            $transaksi_id_reject = $tmpReject[0]->id;
            $transaksi_no_reject = $tmpReject[0]->nomer;
            $transaksi_dtime_reject = $tmpReject[0]->dtime;
            $transaksi_fulldate_reject = $tmpReject[0]->fulldate;
            $transaksi_jenis_reject = $tmpReject[0]->jenis;

            cekHitam("untuk entrypoint reject diambil transki id dan nomernya +pelakunya");
            //enregion

            //ambil data project
            $p->setFilters(array());
            $p->addFilter("project_start_id='$curID'");
            $tmpDataProject = $p->lookUpAll()->result();
            $project_id = $tmpDataProject[0]->id;
            $project_nama = $tmpDataProject[0]->nama;
            //ambil data distribusi dari
            $t->setfilters(array());
            $t->addFilter("jenis='5855'");
            $t->addFilter("project_id='$project_id'");
            $t->addFilterJoin("valid_qty>0");
            $tmpDistriProject = $t->lookupJoined();
            cekHitam(count($tmpDistriProject));
            //tambahin validqty
//        $tmpDistriProject = $t->lookUpAll()->result();
            cekLime($this->db->last_query());
            if (count($tmpDistriProject)) {
//                $oleh_id = $tmpReject[0]->oleh_id;
//                $oleh_nama = $tmpReject[0]->oleh_nama;
//                $transaksi_id_reject = $tmpReject[0]->id;
//                $transaksi_no_reject = $tmpReject[0]->nomer;
//                $transaksi_dtime_reject = $tmpReject[0]->dtime;
//                $transaksi_fulldate_reject = $tmpReject[0]->fulldate;
//                $transaksi_jenis_reject = $tmpReject[0]->jenis;
                $replacer = array(
                    "oleh_id" => $oleh_id,
                    "oleh_nama" => $oleh_nama,
                    "olehID" => $oleh_id,
                    "olehName" => $oleh_nama,
                    "transaksi_id" => $transaksi_id_reject,
                    "transaksi_no" => $transaksi_no_reject,
                    "nomer" => $transaksi_no_reject,
                    "nomer2" => $transaksi_no_reject,
                    "jenisTr" => $transaksi_jenis_reject,
//                    "dtime"=>$transaksi_dtime_reject,
//                    "fulldate"=>$transaksi_fulldate_reject,
                );
                $component = array(
                    "master" => array(
                        //<editor-fold desc="komponen milik pusat">
                        array(
                            "comName" => "Jurnal",
                            "loop" => array(
                                "1010030030" => "hpp",// persediaan produk
//                            "1010060010" => "-hpp",// piutang cabang
                                "3020050" => "hpp",// laba ditempatkan pusat
                            ),
                            "static" => array(
                                "cabang_id" => "place2ID",
                                "transaksi_id" => "transaksi_id",
                                "transaksi_no" => "nomer",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        array(
                            "comName" => "Rekening",
                            "loop" => array(
                                "1010030030" => "hpp",// persediaan produk
//                            "1010060010" => "-hpp",// piutang cabang
                                "3020050" => "hpp",// laba ditempatkan pusat
                            ),
                            "static" => array(
                                "cabang_id" => "place2ID",
                                "transaksi_id" => "transaksi_id",
                                "transaksi_no" => "nomer",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
//                    array(
//                        "comName" => "RekeningPembantuAntarcabang",
//                        "loop" => array(
//                            "1010060010" => "-hpp",// piutang cabang
//                        ),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "cabang2_id" => "pihakID",
//                            "cabang2_nama" => "pihakName",
//                            "extern_id" => "placeID",
//                            "extern_nama" => "placeName",
//                            "transaksi_id" => "transaksi_id",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
                        //</editor-fold>

                        //<editor-fold desc="komponen milik cabang">
                        array(
                            "comName" => "Jurnal",
                            "loop" => array(
                                "1010030030" => "-hpp",// persediaan produk
//                            "2040010" => "-hpp",// hutang ke pusat
                                "3020050" => "-hpp",// hutang ke pusat

                            ),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "transaksi_id" => "transaksi_id",
                                "transaksi_no" => "nomer",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        array(
                            "comName" => "Rekening",
                            "loop" => array(
                                "1010030030" => "-hpp",// persediaan produk
                                //"2040010" => "-hpp",// hutang ke pusat
                                "3020050" => "-hpp",// hutang ke pusat
                            ),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "transaksi_id" => "transaksi_id",
                                "transaksi_no" => "nomer",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
//                    array(
//                        "comName" => "RekeningPembantuAntarcabang",
//                        "loop" => array(
//                            "2040010" => "-hpp",// hutang ke pusat
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "extern_id" => "place2ID",
//                            "extern_nama" => "place2Name",
//                            "transaksi_id" => "transaksi_id",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
                        //</editor-fold>

                    ),
                    "detail" => array(

                        array(
                            "comName" => "RekeningPembantuProduk",
                            "loop" => array(
                                "1010030030" => "sub_hpp",// persediaan produk pusat
                            ),
                            "static" => array(
                                "cabang_id" => "place2ID",//pusat
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "produk_qty" => "jml",
                                "produk_nilai" => "hpp",
//                            "gudang_id" => "gudang2ID",
                                "gudang_id" => "gudangProjectID",
                                "transaksi_id" => "transaksi_id",
                                "transaksi_no" => "nomer",
                            ),
                            "srcGateName" => "items",
                            "srcRawGateName" => "items",
                        ),
                        //fifo
                        array(
                            "comName" => "FifoAverage",
                            "loop" => array(),
                            "static" => array(
                                "jenis" => ".produk",
                                "jml" => "jml",
                                "produk_id" => "id",
                                "hpp" => "hpp",
                                "jml_nilai" => "sub_hpp",
                                "hpp_riil" => "hpp_riil",
                                "jml_nilai_riil" => "sub_hpp_riil",
                                "ppv_riil" => "ppv_riil",
                                "ppv_nilai_riil" => "sub_ppv_riil",
                                "nama" => "name",
                                "cabang_id" => "place2ID",
                                "gudang_id" => "gudangProjectID",
                                "ppn_in" => "ppn_in",
                                "ppn_in_nilai" => "sub_ppn_in",
                                "suppliers_id" => "suppliers_id",
                                "suppliers_nama" => "suppliers_nama",
                                "hpp_nppv" => "hpp_nppv",
                                "jml_nilai_nppv" => "sub_hpp_nppv",
                                "produk_jenis" => "produk_jenis",
                                "transaksi_id" => "transaksi_id",
                                "transaksi_no" => "nomer",
                            ),
                            "srcGateName" => "items",
                            "srcRawGateName" => "items",
                        ),


                        array(
                            "comName" => "RekeningPembantuProduk",
                            "loop" => array(
                                "1010030030" => "-sub_hpp",// persediaan produk cabang
                            ),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "produk_qty" => "-jml",
                                "produk_nilai" => "hpp",
                                "gudang_id" => "pihakProjekWorkorderSubGudangID",
                                "transaksi_id" => "transaksi_id",
                                "transaksi_no" => "nomer",
                            ),
                            "srcGateName" => "items",
                            "srcRawGateName" => "items",
                        ),


                        //ini postproc masuk sini aja
                        //<editor-fold desc="Postproc-locker milik pusat">
                        array(
                            "comName" => "LockerStock",
                            "loop" => array(),
                            "static" => array(
                                "cabang_id" => "place2ID",
                                "jenis" => ".produk",
                                "state" => ".active",
                                "jumlah" => "qty",
                                "produk_id" => "id",
                                "nama" => "name",
                                "satuan" => "satuan",
                                "oleh_id" => ".0",
                                "oleh_nama" => "",
                                "transaksi_id" => "masterID",
                                "nomer" => "nomer",
//                            "gudang_id" => "gudang2ID",
                                "gudang_id" => "gudangProjectID",

                            ),
                            "srcGateName" => "items",
                            "srcRawGateName" => "items",
                        ),


                        // locker stok mutasi
                        array(
                            "comName" => "LockerStockMutasi",
                            "loop" => array(),
                            "static" => array(
                                "cabang_id" => "place2ID",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "qty_debet" => "qty",
                                "produk_nilai" => "hpp",
//                            "gudang_id" => "gudang2ID",
                                "gudang_id" => "gudangProjectID",
                                "jenis" => "jenisTr",
                                "transaksi_id" => "transaksi_id",
                                "transaksi_no" => "nomer",
                            ),
                            "reversable" => true,
                            "srcGateName" => "items",
                            "srcRawGateName" => "items",
                        ),
                        //</editor-fold>

                        //<editor-fold desc="Postproc-locker milik cabang">
                        array(
                            "comName" => "LockerStock",
                            "loop" => array(),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "jenis" => ".produk",
                                "state" => ".active",
                                "jumlah" => "-qty",
                                "produk_id" => "id",
                                "nama" => "name",
                                "satuan" => "satuan",
                                "oleh_id" => ".0",
                                "transaksi_id" => ".0",
                                "gudang_id" => "pihakProjekWorkorderSubGudangID",

                            ),
                            "srcGateName" => "items",
                            "srcRawGateName" => "items",
                        ),
                        // locker stok mutasi
                        array(
                            "comName" => "LockerStockMutasi",
                            "loop" => array(),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "qty_debet" => "-qty",
                                "produk_nilai" => "hpp",
                                "gudang_id" => "gudangID",
                                "jenis" => "jenisTr",
                                "transaksi_id" => "transaksi_id",
                                "transaksi_no" => "nomer",
                            ),
                            "reversable" => true,
                            "srcGateName" => "items",
                            "srcRawGateName" => "items",
                        ),
                        array(
                            "comName" => "LockerStock",
                            "loop" => array(),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "jenis" => ".produk",
                                "state" => ".reject",
                                "jumlah" => "qty",
                                "produk_id" => "id",
                                "nama" => "name",
                                "satuan" => "satuan",
                                "oleh_id" => ".0",
                                "transaksi_id" => ".0",
//                            "gudang_id" => "gudang2ID",
                                "gudang_id" => "gudangID",

                            ),
                            "srcGateName" => "items",
                            "srcRawGateName" => "items",
                        ),

                        //----memcatat produk yang diterima...
                        array(
                            "comName" => "LockerStockWorkOrder",
                            "loop" => array(),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "jenis" => ".produk",
                                "state" => ".diterima",
                                "jumlah" => "-qty",
                                "produk_id" => "id",
                                "nama" => "name",
                                "satuan" => "satuan",
                                "oleh_id" => ".0",
                                "transaksi_id" => ".0",
                                "project_id" => "pihakProjekID",
                                "work_order_id" => "pihakProjekWorkOrderID",
                            ),
                            "srcGateName" => "items",
                            "srcRawGateName" => "items",
                        ),

                    ),
                    "postproc" => array(
                        //detail
                        // rekening pembantu produk serial
                        array(
                            "comName" => "RekeningPembantuProdukPerSerial",
                            "loop" => array(
                                "1010030030" => ".1",//serial pusat masuk
                            ),
                            "static" => array(
                                "cabang_id" => "place2ID",
                                "gudang_id" => "gudangProjectID",
                                "extern_id" => ".0",
                                "extern_nama" => "produk_serial",
                                "extern2_id" => ".0",
                                "extern2_nama" => "produk_sku_part_nama",
                                "produk_id" => "id",
                                "produk_nama" => "name",
                                "produk_qty" => "jml",
                                "produk_nilai" => ".1",
                                "transaksi_id" => "transaksi_id",
                                "transaksi_no" => "nomer",
                            ),
                            "srcGateName" => "items3_sum",
                            "srcRawGateName" => "items3_sum",
                        ),
                        // rekening pembantu produk serial
                        array(
                            "comName" => "RekeningPembantuProdukPerSerial",
                            "loop" => array(
                                "1010030030" => ".-1",//persediaan produk, sub_diskon_nilai_total
                            ),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "gudang_id" => "pihakProjekWorkorderSubGudangID",
                                "extern_id" => ".0",
                                "extern_nama" => "produk_serial",
                                "extern2_id" => ".0",
                                "extern2_nama" => "produk_sku_part_nama",
                                "produk_id" => "id",
                                "produk_nama" => "name",
                                "produk_qty" => "-jml",
                                "produk_nilai" => ".1",
                                "transaksi_id" => "transaksi_id",
                                "transaksi_no" => "nomer",
                            ),
                            "srcGateName" => "items3_sum",
                            "srcRawGateName" => "items3_sum",
                        ),
                    ),
                );

                $trProject_id = $tmpDistriProject[0]->id;
                $t->setFilters(array());
                $t->addFilter("transaksi_id='$trProject_id'");
                $trReg = $t->lookupDataRegistries()->result();
                $mainData = array();
                $itemstmp = array();
                $items2 = array();
                $items3_sumTmp = array();
                foreach ($trReg[0] as $kol => $valueReg) {
                    switch ($kol) {
                        case "main":
                            $mainData = $mainData + unserialize(base64_decode($valueReg));
                            break;
                        case "items":
                            $itemstmp = $itemstmp + unserialize(base64_decode($valueReg));
                            break;
                        case "items2":
                            $items2 = $items2 + unserialize(base64_decode($valueReg));
                            break;
                        case "items3_sum":
                            $items3_sumTmp = $items3_sumTmp + unserialize(base64_decode($valueReg));
                            break;
                    }

                }
                //replacer main
                foreach ($replacer as $k => $valK) {
                    $mainData[$k] = $valK;
                }

                //replacer items
                $items = array();
                foreach ($itemstmp as $ix => $ixData) {
                    foreach ($replacer as $k => $valK) {
                        $ixData[$k] = $valK;
                    }
                    $items[$ix] = $ixData;
                }
//replacer $items3_sum
                $items3_sum = array();
                foreach ($items3_sumTmp as $ixx => $ixData_0) {
                    foreach ($replacer as $k => $valK) {
                        $ixData_0[$k] = $valK;
                    }
                    $items3_sum[$ixx] = $ixData_0;
                }

                if (count($mainData) > 0) {
                    $this->jenisTr = $jenis = $transaksi_jenis_reject;
                    $buildTablesMaster = $component["master"];
                    if (sizeof($buildTablesMaster) > 0) {
                        $bCtr = 0;
                        foreach ($buildTablesMaster as $buildTablesMaster_specs) {
                            $bCtr++;
                            $mdlName = $buildTablesMaster_specs['comName'];
                            // if (substr($mdlName, 0, 1) == "{") {
                            //     $mdlName = trim($mdlName, "{");
                            //     $mdlName = trim($mdlName, "}");
                            //     $mdlName = str_replace($mdlName, $main[$mdlName], $mdlName);
                            // }

                            //--- INI UNTUK BUILD TABLES REKENING
                            $mdlName = "Com" . $mdlName;
                            $this->load->model("Coms/" . $mdlName);
                            $m = new $mdlName();
                            if (isset($buildTablesMaster_specs['loop']) && sizeof($buildTablesMaster_specs['loop']) > 0) {
                                foreach ($buildTablesMaster_specs['loop'] as $key => $val) {
                                    if (substr($key, 0, 1) == "{") {
                                        $oldParam = $buildTablesMaster_specs['loop'][$key];
                                        unset($buildTablesMaster_specs['loop'][$key]);
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $main[$key], $key);
                                        $buildTablesMaster_specs['loop'][$key] = $oldParam;
                                    }
                                }
                            }
                            if (method_exists($m, "getTableNameMaster")) {
                                // arrPrint($buildTablesMaster_specs);
                                // matiHEre(__LINE__);
                                if (sizeof($m->getTableNameMaster())) {
                                    $m->buildTables($buildTablesMaster_specs);
                                    // cekHijau(" === build tabel rekening === ");
                                }
                            }
                        }
                    }

                    $buildTablesDetail = $component["detail"];
                    if (sizeof($component["detail"]) > 0) {
                        foreach ($buildTablesDetail as $buildTablesDetail_specs) {
                            foreach ($items as $itemSpec) {
                                $mdlName = $buildTablesDetail_specs['comName'];
                                // cekLime($mdlName);
                                if (substr($mdlName, 0, 1) == "{") {
                                    $mdlName = trim($mdlName, "{");
                                    $mdlName = trim($mdlName, "}");
                                    $mdlName = str_replace($mdlName, $itemSpec[$mdlName], $mdlName);
                                }
                                $mdlName = "Com" . $mdlName;
                                $this->load->model("Coms/" . $mdlName);
                                $m = new $mdlName();

                                if (isset($buildTablesDetail_specs['loop']) && sizeof($buildTablesDetail_specs['loop']) > 0) {
                                    foreach ($buildTablesDetail_specs['loop'] as $key => $val) {
                                        if (substr($key, 0, 1) == "{") {
                                            $oldParam = $buildTablesDetail_specs['loop'][$key];
                                            unset($buildTablesDetail_specs['loop'][$key]);
                                            $key = trim($key, "{");
                                            $key = trim($key, "}");
                                            $key = str_replace($key, $itemSpec[$key], $key);
                                            $buildTablesDetail_specs['loop'][$key] = $oldParam;
                                        }
                                    }
                                }
                                if (method_exists($m, "getTableNameMaster")) {
                                    if (sizeof($m->getTableNameMaster())) {
                                        $m->buildTables($buildTablesDetail_specs);
                                        // cekHitam(" === build tabel rekening === ");
                                    }
                                }
                            }
                        }
                    }
                    $componentGate['master'] = array();
                    $componentConfig['master'] = array();
                    //==filter nilai, jika NOL tidak dikirim, sesuai config==
                    $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                    $buildTablesMaster = $component["master"];
                    $iterator = array();
                    $componentConfig['master'] = $buildTablesMaster;
                    $iterator = $buildTablesMaster;
                    $tempTableinMAster = $mainData;
//                arrPrint($iterator);
                    //region master
                    // $iterator = array();
                    if (sizeof($iterator) > 0) {
                        $componentConfig['master'] = $iterator;
                        $cCtr = 0;
                        foreach ($iterator as $cCtr => $tComSpec) {
                            $cCtr++;
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $mainData, $comName);
                            }
                            // $srcGateName = $tComSpec['srcGateName'];
                            // $srcRawGateName = $tComSpec['srcRawGateName'];
                            // cekHere("component # $cCtr: $comName<br>");

                            $dSpec = $mainData;
                            $tmpOutParams = array();
                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $mainData[$key], $key);
                                    }
                                    $realValue = makeValue($value, $mainData, $mainData, 0);
                                    $tmpOutParams['loop'][$key] = $realValue;
                                }
                            }
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {
                                    $realValue = makeValue($value, $mainData, $mainData, 0);
                                    $tmpOutParams['static'][$key] = $realValue;
                                }
                                if (!isset($tmpOutParams['static']["transaksi_id"])) {
                                    $tmpOutParams['static']["transaksi_id"] = "0000";
                                }
                                if (!isset($tmpOutParams['static']["transaksi_no"])) {
                                    $tmpOutParams['static']["transaksi_no"] = "0000";
                                }
                                $tmpOutParams['static']["urut"] = $cCtr;
                                $tmpOutParams['static']["fulldate"] = $transaksi_fulldate_reject;
                                $tmpOutParams['static']["dtime"] = $transaksi_dtime_reject;
                                $tmpOutParams['static']["keterangan"] = "Reject project oleh $oleh_nama";
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
                            // arrprint($jenis);
                            //                     matiHEre();
                            if ($tobeExecuted) {
                                //----- kiriman gerbang untuk counter mutasi rekening
                                if (method_exists($m, "setTableInMaster")) {
                                    $m->setTableInMaster($tempTableinMAster);
                                }
                                if (method_exists($m, "setMain")) {
                                    $m->setMain($mainData);
                                }
                                if (method_exists($m, "setJenisTr")) {
                                    $m->setJenisTr($jenis);
                                }
                                arrPrint($tmpOutParams);
                                //----- kiriman gerbang untuk counter mutasi rekening
                                $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            }
                            $componentGate['master'][$cCtr] = $tmpOutParams;
                        }
                    }
                    else {
                        cekKuning("components is not set");
                    }
                    //endregion

                    //region processing sub-components, if in single step geser ke CLI

                    $componentGate['detail'] = array();
                    $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                    $filterNeeded = false;
                    $componentConfig['detail'] = $buildTablesDetail;
                    $iterator = $buildTablesDetail;
                    // $iterator =array();
                    if (sizeof($iterator) > 0) {
                        $comsLocation = "Coms";
                        $comsPrefix = "Com";
                        foreach ($iterator as $cCtr => $tComSpec) {
                            // arrprint($tComSpec);
                            $tmpOutParams[$cCtr] = array();
                            $gg = 0;
                            // $srcGateName = $tComSpec['srcGateName'];
                            // if ($componentsDetailLoop == true) {
                            foreach ($items as $id => $dSpec) {
                                // $srcRawGateName = $tComSpec['srcRawGateName'];
                                $comName = $tComSpec['comName'];
                                if (substr($comName, 0, 1) == "{") {
                                    $comName = trim($comName, "{");
                                    $comName = trim($comName, "}");
                                    $comName = str_replace($comName, $items, $comName);
                                }

                                $mdlName = "$comsPrefix" . ucfirst($comName);
                                if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                    $filterNeeded = true;
                                }
                                else {
                                    $filterNeeded = false;
                                }
                                // cekHere("sub-component: [$comsLocation] $comName, initializing values <br>");

                                $subParams = array();

                                if (isset($tComSpec['loop'])) {
                                    foreach ($tComSpec['loop'] as $key => $value) {
                                        if (substr($key, 0, 1) == "{") {
                                            $key = trim($key, "{");
                                            $key = trim($key, "}");
                                            $key = str_replace($key, $dSpec[$key], $key);
                                        }

                                        $realValue = makeValue($value, $dSpec, $dSpec, 0);
                                        // cekMErah("$key =>".$realValue);
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
                                        $realValue = makeValue($value, $dSpec, $dSpec, 0);
                                        $subParams['static'][$key] = $realValue;
                                    }
                                    if (!isset($subParams['static']["transaksi_id"])) {
                                        $subParams['static']["transaksi_id"] = 0000;
                                    }
                                    if (!isset($subParams['static']["transaksi_no"])) {
                                        $subParams['static']["transaksi_no"] = 0000;
                                    }

                                    $subParams['static']["fulldate"] = $transaksi_fulldate_reject;
                                    $subParams['static']["dtime"] = $transaksi_dtime_reject;
                                    $subParams['static']["keterangan"] = "Reject project oleh $oleh_nama";
                                }

                                if (sizeof($subParams) > 0) {
                                    //                                cekhitam("subparam ada isinya");
                                    if ($filterNeeded) {
                                        if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                            $tmpOutParams[$cCtr][] = $subParams;
                                        }
                                    }
                                    else {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    cekhitam("subparam TIDAK ada isinya");
                                }
                            }


                            $componentGate['detail'][$cCtr] = $subParams;
                        }
                        // arrPrint($tmpOutParams);
                        // matiHEre($cCtr);

                        foreach ($iterator as $cCtr => $tComSpec) {
                            // $srcGateName = $tComSpec['srcGateName'];
                            foreach ($items as $id => $dSpec) {
                                // $srcRawGateName = $tComSpec['srcRawGateName'];
                                $comName = $tComSpec['comName'];
                                if (substr($comName, 0, 1) == "{") {
                                    $comName = trim($comName, "{");
                                    $comName = trim($comName, "}");
                                    $comName = str_replace($comName, $items[$id][$comName], $comName);
                                }
                            }
                            cekHere("sub component: [$comsLocation] $comName, sending values " . __LINE__ . "<br>");

                            $mdlName = "$comsPrefix" . ucfirst($comName);
                            $this->load->model("$comsLocation/" . $mdlName);
                            $m = new $mdlName();
                            //===filter value nol, jika harus difilter

                            if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                $tobeExecuted = true;
                            }
                            else {
                                $tobeExecuted = false;
                            }

                            // matiHEre($tobeExecuted);
                            if ($tobeExecuted) {
                                //----- kiriman gerbang
                                if (method_exists($m, "setTableInMaster")) {
                                    $m->setTableInMaster($tempTableinMAster);
                                }
                                if (method_exists($m, "setDetail")) {
                                    $m->setDetail($items);
                                }
                                if (method_exists($m, "setJenisTr")) {
                                    $m->setJenisTr($this->jenisTr);
                                }
                                //----- kiriman gerbang
                                $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                cekBiru($this->db->last_query());
                            }
                            else {
                                cekMerah("$comName tidak eksekusi");
                            }

                        }
                    }
                    else {
                        cekKuning("subcomponents is not set");
                    }

                    //endregion

                    //region postproc
                    $tmpOutParams = array();
                    $componentGate['detail'] = array();
                    $buildTablesDetailpostproc = $component["postproc"];
//                arrPrint($buildTablesDetailpostproc);
//                arrPrintWEbs($items3_sum);

                    $componentConfig['detail'] = $buildTablesDetailpostproc;
                    $iterator = $buildTablesDetailpostproc;
//                arrPrint($iterator);
//                matiHere();
                    if (sizeof($iterator) > 0) {
                        $comsLocation = "Coms";
                        $comsPrefix = "Com";
                        foreach ($iterator as $cCtr => $tComSpec) {
                            // arrprint($tComSpec);
                            $tmpOutParams[$cCtr] = array();
                            $gg = 0;
                            // $srcGateName = $tComSpec['srcGateName'];
                            // if ($componentsDetailLoop == true) {
                            foreach ($items3_sum as $id => $dSpec) {
                                // $srcRawGateName = $tComSpec['srcRawGateName'];
                                $comName = $tComSpec['comName'];
//                            matiHere($comName);
                                if (substr($comName, 0, 1) == "{") {
                                    $comName = trim($comName, "{");
                                    $comName = trim($comName, "}");
                                    $comName = str_replace($comName, $items3_sum, $comName);
                                }

                                $mdlName = "$comsPrefix" . ucfirst($comName);
                                if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                    $filterNeeded = true;
                                }
                                else {
                                    $filterNeeded = false;
                                }
                                // cekHere("sub-component: [$comsLocation] $comName, initializing values <br>");

                                $subParams = array();

                                if (isset($tComSpec['loop'])) {
                                    foreach ($tComSpec['loop'] as $key => $value) {
                                        if (substr($key, 0, 1) == "{") {
                                            $key = trim($key, "{");
                                            $key = trim($key, "}");
                                            $key = str_replace($key, $dSpec[$key], $key);
                                        }

                                        $realValue = makeValue($value, $dSpec, $dSpec, 0);
                                        // cekMErah("$key =>".$realValue);
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
                                        $realValue = makeValue($value, $dSpec, $dSpec, 0);
                                        $subParams['static'][$key] = $realValue;
                                    }
                                    if (!isset($subParams['static']["transaksi_id"])) {
                                        $subParams['static']["transaksi_id"] = 0000;
                                    }
                                    if (!isset($subParams['static']["transaksi_no"])) {
                                        $subParams['static']["transaksi_no"] = 0000;
                                    }

                                    $subParams['static']["fulldate"] = $fulldate;
                                    $subParams['static']["dtime"] = $dtime;
                                    $subParams['static']["keterangan"] = $jenisTrName . " oleh " . $oleh_nama;
                                }

                                if (sizeof($subParams) > 0) {
                                    //                                cekhitam("subparam ada isinya");
                                    if ($filterNeeded) {
                                        if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                            $tmpOutParams[$cCtr][] = $subParams;
                                        }
                                    }
                                    else {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    cekhitam("subparam TIDAK ada isinya");
                                }
                            }


                            $componentGate['detail'][$cCtr] = $subParams;
                        }
//                     arrPrint($componentGate);
//                     matiHEre($cCtr);

                        foreach ($iterator as $cCtr => $tComSpec) {
                            // $srcGateName = $tComSpec['srcGateName'];
                            foreach ($items3_sum as $id => $dSpec) {
                                // $srcRawGateName = $tComSpec['srcRawGateName'];
                                $comName = $tComSpec['comName'];
                                if (substr($comName, 0, 1) == "{") {
                                    $comName = trim($comName, "{");
                                    $comName = trim($comName, "}");
                                    $comName = str_replace($comName, $items3_sum[$id][$comName], $comName);
                                }
                            }
                            cekHere("sub component: [$comsLocation] $comName, sending values " . __LINE__ . "<br>");

                            $mdlName = "$comsPrefix" . ucfirst($comName);
                            $this->load->model("$comsLocation/" . $mdlName);
                            $m = new $mdlName();
                            //===filter value nol, jika harus difilter
//arrPrint($tmpOutParams);
//                        matiHere();
                            if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                $tobeExecuted = true;
                            }
                            else {
                                $tobeExecuted = false;
                            }

                            // matiHEre($tobeExecuted);
                            if ($tobeExecuted) {
                                //----- kiriman gerbang
                                if (method_exists($m, "setTableInMaster")) {
                                    $m->setTableInMaster($tempTableinMAster);
                                }
                                if (method_exists($m, "setDetail")) {
                                    $m->setDetail($items3_sum);
                                }
                                if (method_exists($m, "setJenisTr")) {
                                    $m->setJenisTr($this->jenisTr);
                                }
                                //----- kiriman gerbang
                                $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                cekBiru($this->db->last_query());
                            }
                            else {
                                cekMerah("$comName tidak eksekusi");
                            }

                        }
                    }
                    else {
                        cekKuning("subcomponents is not set");
                    }
                    //endregion
                }


                cekLime($this->db->last_query());

//region update transakidata validqty dan cancel qty->jumlah yang direject valid qty pipindah kesini
                cekMerah("JANGAN LUPA UPDATE VALID QTY BROOO");

                foreach ($tmpDistriProject as $row) {
                    $update = array(
                        "valid_qty" => 0,
                        "cancel_qty" => $row->valid_qty,

                    );
                    $t->setFilters(array());
                    $t->setTableName("transaksi_data");
                    $t->updateData(array("transaksi_id" => $row->transaksi_id), $update) or matiHEre("gagal update");
                    cekMerah($this->db->last_query());
                }
                //update fifio cabang out karena tidak bisa dipasang preproc
                $listPRoduk = array();

                foreach ($items as $items_0) {
                    $rek->addFilter("cabang_id='" . $items_0["placeID"] . "'");
                    $rek->addFilter("gudang_id='" . $items_0["gudangID"] . "'");
                    $rek->addFilter("extern_id='" . $items_0["id"] . "'");
                    $tmpProduk = $rek->fetchBalances("1010030030");
                    $updateFifo = array(
                        "hpp" => $tmpProduk[0]->debet > 0 ? $tmpProduk[0]->debet / $tmpProduk[0]->qty_debet : 0,
                        "jml" => $tmpProduk[0]->qty_debet,
                        "jml_nilai" => $tmpProduk[0]->debet,
                    );
                    $fi->setFilters(array());
                    $fi->updateData(array("produk_id" => $items_0["id"], "cabang_id" => $items_0["placeID"], "gudang_id" => $items_0["gudangID"]), $updateFifo);
//                cekHitam($this->db->last_query());
//                arrPrint($tmpProduk);
//                matiHere(__LINE__);

//                $listPRoduk
                }
//                arrPrint($listPRoduk);

                //endregion
            }
            else {
                cekMErah("tidak ada yg perlu dilakukan");
            }
        }


//        matiHere(__LINE__);
        $commit = $this->db->trans_complete() or die("Gagal saat berusaha commit transaction!");
    }

    public function runRealisasiProject()
    {
        $this->load->model("CustomCounter");
        $this->load->helper("he_angka");
        $this->load->helper("he_mass_table");
        $component = array(
            "master" => array(
                // jurnal ke 1 piutang dagang/penjualan per WO
                /*
                 * realtive costnem masuk ke COA Hpp produk
                 * costing  masuk ke kategory
                 */
                array(
                    "comName" => "Jurnal",
                    "loop" => array(
//                        "5020" => "hpp_budget",//hpp budget project debet
                        "3020010" => "-hpp",//efisiensi kredit
                        "1010030030" => "-hpp"//persediaan
                    ),

                    "static" => array(
                        "cabang_id" => "placeID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                        "transaksi_id" => "transaksi_id",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                array(
                    "comName" => "Rekening",
                    "loop" => array(
//                        "5020" => "hpp_budget",//hpp budget project
                        "3020010" => "-hpp",//efisiensi kredit
                        "1010030030" => "-hpp"//persediaan
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                        "transaksi_id" => "transaksi_id",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),

            ),
            "detail" => array(
                //<editor-fold desc="subkomponen milik cabang">
                array(
                    "comName" => "RekeningPembantuProduk",
                    "loop" => array(
                        "1010030030" => "-sub_hpp",// persediaan produk
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id",
                        "extern_nama" => "name",
                        "produk_qty" => "-jml",
                        "produk_nilai" => "hpp",
                        "gudang_id" => "gudangID",
//                            "gudang_id" => "gudangProjectID",
                        "transaksi_no" => "transaksi_no",
                        "transaksi_id" => "transaksi_id",
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),
                // pembantu efisiensi bahan baku (RAB) => kredit perkategori biaya
                array(
                    "comName" => "RekeningPembantuEfisiensiBiaya",
                    "loop" => array(
//                        "3020010" => "sub_hpp_produk_budget",//bahan baku dengan nilai bom masih single produk belum suport multi, jik sudah suport multi pakai yang ke 2
                        "3020010" => "-sub_hpp",//bahan baku dengan nilai bom masih single produk belum suport multi, jik sudah suport multi pakai yang ke 2
                        // "3020010" => "supplies_bom",//bahan baku dengan nilai bom
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "categori_biaya_id",//id kategori biaya
                        "extern_nama" => "categori_biaya_nama",
                        "extern2_id" => "categori_biaya_id",
                        "extern2_nama" => "categori_biaya_nama",
                        "produk_qty" => "-jml",
                        "produk_nilai" => "hpp",
                        "gudang_id" => "gudangID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "transaksi_no",
                        "transaksi_id" => "transaksi_id",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),
                // pembantu LV2 efisiensi (RAB) => kredit per jenis biaya per kategori project, wo,spk
                array(
                    "comName" => "RekeningPembantuEfisiensiBiayaSub",
                    "loop" => array(
//                        "3020010" => "sub_hpp_produk_budget",//efisiensi biaya
                        "3020010" => "-sub_hpp",//efisiensi biaya
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id",//biaya nya
                        "extern_nama" => "nama",
                        "extern2_id" => "categori_biaya_id",
                        "extern2_nama" => "categori_biaya_nama",
                        "extern3_id" => "project_id",//projectid
                        "extern3_nama" => "project_nama",
                        "extern4_id" => "work_order_id",//wo id
                        "extern4_nama" => "work_order_nama",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "transaksi_no",
                        "transaksi_id" => "transaksi_id",
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),
                //nulis raw efisiensi
                array(
                    "comName" => "RekeningPembantuRawItemEfisiensi",
                    "loop" => array(
//                        "3020010" => "sub_hpp_produk_budget", // isi loop adalah overhead,tenaga kerja,biaya kirim
                        "3020010" => "-sub_hpp", // isi loop adalah overhead,tenaga kerja,biaya kirim
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => ".3020010",//biaya
                        "extern_nama" => ".efisensi",
                        "extern2_nama" => "categori_biaya_nama",
                        "extern2_id" => "categori_biaya_id",
                        "extern3_id" => "work_order_id",//projectid
                        "extern3_nama" => "pihakWoProjekName",
                        "extern4_id" => "id",//biaya
                        "extern4_nama" => "nama",
                        "produk_id" => "project_id",//project
                        "produk_nama" => "project_nama",
                        "produk_kode" => "no_spk",
                        "produk_jenis" => ".project",
//                            "barcode" => "barcode",
                        "jml" => "-jml",
                        "harga" => "hpp",// harga dpp
                        "hpp" => "hpp",// hpp produk
                        "jenis" => "jenisTr",
                        "transaksi_no" => "transaksi_no",
                        "transaksi_id" => "transaksi_id",
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),
                //locker
                array(
                    "comName" => "LockerStock",
                    "loop" => array(),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "jenis" => ".produk",
                        "state" => ".active",
                        "jumlah" => "-jml",
                        "produk_id" => "id",
                        "nama" => "name",
                        "satuan" => "satuan",
                        "oleh_id" => ".0",
                        "oleh_nama" => "",
                        "transaksi_id" => ".0",
                        "nomer" => "nomer",
                        "gudang_id" => "gudangID",// gudang work order
//                            "gudang_id" => "gudangProjectID",
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),
                array(
                    "comName" => "LockerStock",
                    "loop" => array(),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "jenis" => ".produk",
                        "state" => ".sold",
                        "jumlah" => "jml",
                        "produk_id" => "id",
                        "nama" => "name",
                        "satuan" => "satuan",
                        "oleh_id" => ".0",
                        "transaksi_id" => ".0",
                        "gudang_id" => "gudangID",// gudang work order
//                            "gudang_id" => "gudangProjectID",
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),

                // locker stok mutasi
                array(
                    "comName" => "LockerStockMutasi",
                    "loop" => array(),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id",
                        "extern_nama" => "name",
                        "qty_debet" => "-qty",
                        "produk_nilai" => "hpp",
                        "gudang_id" => "gudangID",// gudang work order
                        "jenis" => "jenisTr",
                    ),
                    "reversable" => true,
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),
                //</editor-fold>


            ),
        );
        $postSerial = array(
            "master" => array(),
            "detail" => array(
                // rekening pembantu produk serial
                array(
                    "comName" => "RekeningPembantuProdukPerSerial",
                    "loop" => array(
                        "1010030030" => ".-1",//persediaan produk, sub_diskon_nilai_total
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "extern_id" => ".0",
                        "extern_nama" => "produk_serial",
                        "extern2_id" => ".0",
                        "extern2_nama" => "produk_sku_part_nama",
                        "produk_id" => "id",
                        "produk_nama" => "name",
                        "produk_qty" => "-jml",
                        "produk_nilai" => ".1",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "supplierID" => "pihakID",
                    ),
                    "srcGateName" => "items3_sum",
                    "srcRawGateName" => "items3_sum",
                ),
            ),
        );
        $start = microtime(true);
        $force = isset($_GET["force"]) ? $_GET["force"] : "none";
        $cekjam = date("H");
        $timeTOexec = false;
        if ($cekjam >= 21) {
            $timeTOexec = true;
        }
        $timeTOexec = true;
        $arrJenisTr = array(
            "588st",
        );
        $main = array();
        $items = array();
        $tableIn_master = array();
        $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
        if ($timeTOexec) {

            // region membuat tabel rekening bila belum ada
            $buildTablesMaster = $component["master"];
            if (sizeof($buildTablesMaster) > 0) {
                $bCtr = 0;
                foreach ($buildTablesMaster as $buildTablesMaster_specs) {
                    $bCtr++;
                    $mdlName = $buildTablesMaster_specs['comName'];
                    //--- INI UNTUK BUILD TABLES REKENING
                    $mdlName = "Com" . $mdlName;
                    $this->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();
                    if (isset($buildTablesMaster_specs['loop']) && sizeof($buildTablesMaster_specs['loop']) > 0) {
                        foreach ($buildTablesMaster_specs['loop'] as $key => $val) {
                            if (substr($key, 0, 1) == "{") {
                                $oldParam = $buildTablesMaster_specs['loop'][$key];
                                unset($buildTablesMaster_specs['loop'][$key]);
                                $key = trim($key, "{");
                                $key = trim($key, "}");
                                $key = str_replace($key, $main[$key], $key);
                                $buildTablesMaster_specs['loop'][$key] = $oldParam;
                            }
                        }
                    }
                    if (method_exists($m, "getTableNameMaster")) {
                        if (sizeof($m->getTableNameMaster())) {
                            $m->buildTables($buildTablesMaster_specs);
                            // cekHijau(" === build tabel rekening === ");
                        }
                    }
                }
            }

            $buildTablesDetail = $component["detail"];
            if (sizeof($component["detail"]) > 0) {
                foreach ($buildTablesDetail as $buildTablesDetail_specs) {
                    foreach ($items as $itemSpec) {
                        $mdlName = $buildTablesDetail_specs['comName'];
                        // cekLime($mdlName);
                        if (substr($mdlName, 0, 1) == "{") {
                            $mdlName = trim($mdlName, "{");
                            $mdlName = trim($mdlName, "}");
                            $mdlName = str_replace($mdlName, $itemSpec[$mdlName], $mdlName);
                        }
                        $mdlName = "Com" . $mdlName;
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();

                        if (isset($buildTablesDetail_specs['loop']) && sizeof($buildTablesDetail_specs['loop']) > 0) {
                            foreach ($buildTablesDetail_specs['loop'] as $key => $val) {
                                if (substr($key, 0, 1) == "{") {
                                    $oldParam = $buildTablesDetail_specs['loop'][$key];
                                    unset($buildTablesDetail_specs['loop'][$key]);
                                    $key = trim($key, "{");
                                    $key = trim($key, "}");
                                    $key = str_replace($key, $itemSpec[$key], $key);
                                    $buildTablesDetail_specs['loop'][$key] = $oldParam;
                                }
                            }
                        }
                        if (method_exists($m, "getTableNameMaster")) {
                            if (sizeof($m->getTableNameMaster())) {
                                $m->buildTables($buildTablesDetail_specs);
                                // cekHitam(" === build tabel rekening === ");
                            }
                        }
                    }
                }
            }
            // endregion membuat tabel rekening bila belum ada


            $this->db->trans_start();


            $itemSelect = array(
                //key =>src
                "id" => "produk_dasar_id",
                "nama" => "produk_dasar_nama",
                "jenis" => "jenis",
                "no_spk" => "no_spk",
                "work_order_id" => "sub_fase_id",
                "work_order_nama" => "sub_fase_nama",
                "project_id" => "produk_id",
                "project_nama" => "produk_nama",
                "produk_id" => "produk_dasar_id",
                "jml" => "jml",
                "qty" => "jml",
                "harga" => "harga",
                "categori_biaya_id" => "cat_id",
                "categori_biaya_nama" => "cat_nama",
                "dtime" => "dtime"

            );
            $this->load->model("Mdls/MdlTasklistProject");
            $tr = New MdlTasklistProject;
            /**
             * query select masih ditanam di model
             */
            $datas = $tr->lookupJoin($tr->getTableName(), "project_sub_tasklist_komposisi")->result();
            cekHitam($this->db->last_query());
            arrPrintWebs($datas);
//            matiHere(__LINE__);
            $grupSpkItems = array();
            if (count($datas) > 0) {
                $this->load->model("Mdls/MdlProdukProject");
                $p = new MdlProdukProject();

                //ambil info transaksi startproject untuk ambil ID dan nomer dari produk project
                $produk_id = $datas[0]->produk_id;
                $p->addFilter("id='$produk_id'");
                $tempProduk = $p->fectDataProject()->result();
                $cabangID = $tempProduk[0]->cabang_id;
                $cabangName = $tempProduk[0]->cabang_nama;
                $gudangID = $datas[0]->gudang_wo;
                $no_spk = $datas[0]->no_spk;
                showLast_query("biru");
                arrPrint($tempProduk);
//matiHEre($cabangID);
                //pastikan sudah ada isi transaki_id
                if ($datas[0]->post_return_id == 0) {
                    matiHEre("belum diijinkan jalan belum scan serial di cabang project");
                }
                $mainData = array(
                    "transaksi_id" => $datas[0]->post_return_id,
                    "transaksi_no" => $datas[0]->post_return_no,
                    "jenis" => "9833",
                    "nomer" => $datas[0]->post_return_no,
                    "oleh_id" => $datas[0]->employee_id,
                    "oleh_nama" => $datas[0]->employee_nama,
                    "dtime" => $datas[0]->post_return_dtime,
                    "fulldate" => $datas[0]->post_return_dtime,
                    "pihak_id" => $tempProduk[0]->customer_id,
                    "pihak_nama" => $tempProduk[0]->customer_nama,
                    "pihakID" => $tempProduk[0]->customer_id,
                    "pihakName" => $tempProduk[0]->customer_nama,
                    "project_id" => $tempProduk[0]->id,
                    "project_nama" => $tempProduk[0]->nama,

                    "jenisTr" => "9833",
                    "cabang_id" => $cabangID,
                    "placeID" => $cabangID,
                    "placeName" => $cabangName,
                    "cabang_nama" => $cabangName,
                    "gudangID" => $datas[0]->gudang_wo,
                    "gudangName" => "default branch #1",
                    "gudang_id" => $gudangID,
                    "gudang_nama" => "default branch WO#1",

                );
//                arrPrintCyan($mainData);
//                mati_disini(__LINE__);
                $iidsUpdate = array();
                $items = array();

                $subhpp = 0;
                $listUpdateFIfo = array();
                $listUpdateProjectTask[$no_spk] = array(
                    "post_return_cli" => "1",

                );
                $listUpdateProjectTask_sub = array();
                $listProduk = array();
                foreach ($datas as $datas_0) {
                    $datas_0_jml = $datas_0->jml;
                    unset($datas_0->jml);
                    $datas_0->jml = $datas_0_jml - $datas_0->jml_return;
                    $datas_0->qty = $datas_0_jml - $datas_0->jml_return;

//                    arrprint($datas_0);
                    //ambil fifoavg untuk dapat nilai
                    foreach ($itemSelect as $key => $src) {
                        //overwrite jaga jaga pengembalian stok tidak ngurangi saldo_debet
                        $items[$datas_0->produk_dasar_id][$key] = $datas_0->$src;
                    }
                    $listProduk[$datas_0->produk_dasar_id] = array(
                        "produk_id" => $datas_0->produk_dasar_id,
                        "cabang_id" => $cabangID,
                        "gudang_id" => $gudangID,
                    );

                    $cekPrevalue = $this->_cekPrevalueAverage($cabangID, $datas_0->gudang_wo, $datas_0->produk_dasar_id);
                    cekHitam($this->db->last_query());
//                    arrPrintWebs($cekPrevalue);
                    cekHitam("cekPrevalue: " . count($cekPrevalue));
                    if ($cekPrevalue["jml"] >= $datas_0->jml) {
                        $new_qty = $cekPrevalue["jml"] - $datas_0->jml;
                        $dipakai = $datas_0->jml;
                        $hpp = $cekPrevalue["hpp"];
                        $sub_hpp = $cekPrevalue["hpp"] * $datas_0->jml;
                        $fifo_nilai_hpp = $cekPrevalue["jml_nilai"] - $sub_hpp;
//                        if ($cekPrevalue["jml_nilai"] - ($cekPrevalue["hpp"] * $datas_0->jml) < 0) {
                        if ($fifo_nilai_hpp < -1) {
                            cekHitam("preValue jml_nilai : " . $cekPrevalue["jml_nilai"]);
                            cekHitam("preValue hpp : " . $cekPrevalue["hpp"]);
                            cekHitam("jml : " . $datas_0->jml);
                            matiHEre("koq minuss [$fifo_nilai_hpp] || " . reformatExponent($fifo_nilai_hpp));
                        }
                        $listUpdateFIfo[$cekPrevalue['id']] = array(
                            "jml" => $new_qty,
                            "jml_nilai" => $cekPrevalue["jml_nilai"] - ($cekPrevalue["hpp"] * $datas_0->jml),
                        );
//                        $b = new MdlFifoAverage();
//                        $updaters[] = $b->updateData(array("id" => $cekPrevalue['id']), $updateData);
                        cekMerah($this->db->last_query());


                    }
                    else {
                        matiHEre($datas_0->jml . " ::STOK KURANG SILAHKAN CEK PERSEDIAAN GUDANG_WO :" . $datas_0->gudang_wo);
                    }
                    $listUpdateProjectTask_sub[$datas_0->id] = array(
//                        "id"=>$datas_0->id,
//                        "no_spk"=>$datas_0->id,
                        "qty_kredit" => $datas_0->jml,
                        "qty_saldo" => $datas_0->qty_debet - $datas_0->jml,
                        "cli" => 1,
                    );

//                    $subharga += ($datas_0["jml"] * $datas_0["harga"]);
                    $subhpp += $sub_hpp;
//cekMerah($datas_0->produk_id);
                    $items[$datas_0->produk_dasar_id]["sub_hpp"] = $sub_hpp;
                    $items[$datas_0->produk_dasar_id]["hpp"] = $hpp;
                    $items[$datas_0->produk_dasar_id]["subtotal"] = $sub_hpp;
                    $items[$datas_0->produk_dasar_id]["transaksi_id"] = $datas[0]->post_return_id;
                    $items[$datas_0->produk_dasar_id]["transaksi_no"] = $datas[0]->post_return_no;
                    $items[$datas_0->produk_dasar_id]["oleh_id"] = $datas[0]->employee_id;
                    $items[$datas_0->produk_dasar_id]["oleh_nama"] = $datas[0]->employee_nama;
                    $items[$datas_0->produk_dasar_id]["placeID"] = $cabangID;
                    $items[$datas_0->produk_dasar_id]["placeName"] = $cabangName;
                    $items[$datas_0->produk_dasar_id]["cabang_id"] = $cabangID;
                    $items[$datas_0->produk_dasar_id]["cabang_nama"] = $cabangName;
                    $items[$datas_0->produk_dasar_id]["gudangID"] = $gudangID;
                    $items[$datas_0->produk_dasar_id]["gudangName"] = "default branch wo#1";
                }
                $mainData["hpp"] = $subhpp;
//                $mainData["piutang_dagang"] = $subharga + ($subharga * (my_ppn_factor() / 100));
//                arrprint($listProduk);
                $this->load->model("Coms/ComRekeningPembantuProdukPerSerial");
                $ps = new ComRekeningPembantuProdukPerSerial();
                $itemSerial = array();
                foreach ($listProduk as $pid => $pirData) {
                    $ps->addFilter("cabang_id='" . $pirData["cabang_id"] . "'");
                    $ps->addFilter("gudang_id='" . $pirData["gudang_id"] . "'");
                    $ps->addFilter("produk_id='" . $pirData["produk_id"] . "'");
                    $ps->addFilter("qty_debet>0");
                    $preSerial = $ps->fetchBalances("1010030030");
                    if (count($preSerial) > 0) {
                        foreach ($preSerial as $preSerial_0) {
                            $itemSerial[] = array(
                                "id" => $preSerial_0->produk_id,
                                "produk_id" => $preSerial_0->produk_id,
                                "nama" => $preSerial_0->produk_nama,
                                "name" => $preSerial_0->produk_nama,
                                "produk_serial" => $preSerial_0->extern_nama,
                                "produk_sku_part_nama" => $preSerial_0->extern2_nama,
                                "cabang_id" => $preSerial_0->cabang_id,
                                "gudang_id" => $preSerial_0->gudang_id,
                                "placeID" => $preSerial_0->cabang_id,
                                "gudangID" => $preSerial_0->gudang_id,
                                "jml" => "1",
                                "transaksi_id" => $datas[0]->post_return_id,
                                "transaksi_no" => $datas[0]->post_return_no,
                                "nomer" => $datas[0]->post_return_no,
                                "jenis" => "9833",
                            );
                        }
                    }
                }
            }
            else {
                mati_disini("CLI HABIS... code: " . __LINE__);
            }


//cekHitam(count($itemSerial));
//arrprint($itemSerial);
//            matiHere(__LINE__);
            if (count($mainData) > 0) {
                $dtime = $mainData["dtime"];
                $fulldate = $mainData["fulldate"];
                $jenisTrName = $mainData["jenisTr"];
                $oleh_nama = $mainData["oleh_nama"];
                $this->jenisTr = $jenis = $mainData["jenis"];

                $componentGate['master'] = array();
                $componentConfig['master'] = array();
                //==filter nilai, jika NOL tidak dikirim, sesuai config==
                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();

                $iterator = array();
                $componentConfig['master'] = $buildTablesMaster;
                $iterator = $buildTablesMaster;
                $tempTableinMAster = $mainData;

                //region master
                // $iterator = array();
                if (sizeof($iterator) > 0) {
                    $componentConfig['master'] = $iterator;
                    $cCtr = 0;
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $cCtr++;
                        $comName = $tComSpec['comName'];
                        if (substr($comName, 0, 1) == "{") {
                            $comName = trim($comName, "{");
                            $comName = trim($comName, "}");
                            $comName = str_replace($comName, $mainData, $comName);
                        }
                        // $srcGateName = $tComSpec['srcGateName'];
                        // $srcRawGateName = $tComSpec['srcRawGateName'];
                        // cekHere("component # $cCtr: $comName<br>");

                        $dSpec = $mainData;
                        $tmpOutParams = array();
                        if (isset($tComSpec['loop'])) {
                            foreach ($tComSpec['loop'] as $key => $value) {
                                if (substr($key, 0, 1) == "{") {
                                    $key = trim($key, "{");
                                    $key = trim($key, "}");
                                    $key = str_replace($key, $mainData[$key], $key);
                                }
                                $realValue = makeValue($value, $mainData, $mainData, 0);
                                $tmpOutParams['loop'][$key] = $realValue;
                            }
                        }
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {
                                $realValue = makeValue($value, $mainData, $mainData, 0);
                                $tmpOutParams['static'][$key] = $realValue;
                            }
                            if (!isset($tmpOutParams['static']["transaksi_id"])) {
                                $tmpOutParams['static']["transaksi_id"] = "0000";
                            }
                            if (!isset($tmpOutParams['static']["transaksi_no"])) {
                                $tmpOutParams['static']["transaksi_no"] = "0000";
                            }
                            $tmpOutParams['static']["urut"] = $cCtr;
                            $tmpOutParams['static']["fulldate"] = $fulldate;
                            $tmpOutParams['static']["dtime"] = $dtime;
                            $tmpOutParams['static']["keterangan"] = $jenisTrName . " oleh " . $oleh_nama;
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
                        // arrprint($jenis);
                        //                     matiHEre();
                        if ($tobeExecuted) {
                            //----- kiriman gerbang untuk counter mutasi rekening
                            if (method_exists($m, "setTableInMaster")) {
                                $m->setTableInMaster($tempTableinMAster);
                            }
                            if (method_exists($m, "setMain")) {
                                $m->setMain($mainData);
                            }
                            if (method_exists($m, "setJenisTr")) {
                                $m->setJenisTr($jenis);
                            }
                            arrPrint($tmpOutParams);
//                            matiHere();
                            //----- kiriman gerbang untuk counter mutasi rekening
                            $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            cekBiru($this->db->last_query());
                        }
                        $componentGate['master'][$cCtr] = $tmpOutParams;
                    }
                }
                else {
                    cekKuning("components is not set");
                }
                //endregion


                //region processing sub-components
                $componentGate['detail'] = array();
                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                $filterNeeded = false;
                $componentConfig['detail'] = $buildTablesDetail;
                $iterator = $buildTablesDetail;
                // $iterator =array();
                if (sizeof($iterator) > 0) {
                    $comsLocation = "Coms";
                    $comsPrefix = "Com";
                    foreach ($iterator as $cCtr => $tComSpec) {
                        // arrprint($tComSpec);
                        $tmpOutParams[$cCtr] = array();
                        $gg = 0;
                        // $srcGateName = $tComSpec['srcGateName'];
                        // if ($componentsDetailLoop == true) {
                        foreach ($items as $id => $dSpec) {
                            // $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $items, $comName);
                            }

                            $mdlName = "$comsPrefix" . ucfirst($comName);
                            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                $filterNeeded = true;
                            }
                            else {
                                $filterNeeded = false;
                            }
                            // cekHere("sub-component: [$comsLocation] $comName, initializing values <br>");

                            $subParams = array();

                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $dSpec[$key], $key);
                                    }

                                    $realValue = makeValue($value, $dSpec, $dSpec, 0);
                                    // cekMErah("$key =>".$realValue);
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
                                    $realValue = makeValue($value, $dSpec, $dSpec, 0);
                                    $subParams['static'][$key] = $realValue;
                                }
                                if (!isset($subParams['static']["transaksi_id"])) {
                                    $subParams['static']["transaksi_id"] = 0000;
                                }
                                if (!isset($subParams['static']["transaksi_no"])) {
                                    $subParams['static']["transaksi_no"] = 0000;
                                }

                                if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                    foreach ($paramPatchers[$comName] as $k => $v) {
                                        if (!isset($subParams['static'][$k])) {
                                            $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                        }
                                    }
                                }
                                if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                    foreach ($paramForceFillers[$comName] as $k => $v) {
                                        $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                    }
                                }

                                $subParams['static']["fulldate"] = $fulldate;
                                $subParams['static']["dtime"] = $dtime;
                                $subParams['static']["keterangan"] = $jenisTrName . " oleh " . $oleh_nama;
                            }

                            if (sizeof($subParams) > 0) {
                                //                                cekhitam("subparam ada isinya");
                                if ($filterNeeded) {
                                    if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                }
                            }
                            else {
                                cekhitam("subparam TIDAK ada isinya");
                            }
                        }


                        $componentGate['detail'][$cCtr] = $subParams;
                    }
                    // arrPrint($tmpOutParams);
                    // matiHEre($cCtr);

                    foreach ($iterator as $cCtr => $tComSpec) {
                        // $srcGateName = $tComSpec['srcGateName'];
                        foreach ($items as $id => $dSpec) {
                            // $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $items[$id][$comName], $comName);
                            }
                        }
                        cekHere("sub component: [$comsLocation] $comName, sending values " . __LINE__ . "<br>");

                        $mdlName = "$comsPrefix" . ucfirst($comName);
                        $this->load->model("$comsLocation/" . $mdlName);
                        $m = new $mdlName();
                        //===filter value nol, jika harus difilter

                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        // matiHEre($tobeExecuted);
                        if ($tobeExecuted) {
                            //----- kiriman gerbang
                            if (method_exists($m, "setTableInMaster")) {
                                $m->setTableInMaster($tempTableinMAster);
                            }
                            if (method_exists($m, "setDetail")) {
                                $m->setDetail($items);
                            }
                            if (method_exists($m, "setJenisTr")) {
                                $m->setJenisTr($this->jenisTr);
                            }
                            //----- kiriman gerbang
                            $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            cekBiru($this->db->last_query());
                        }
                        else {
                            cekMerah("$comName tidak eksekusi");
                        }
                    }
                }
                else {
                    cekKuning("subcomponents is not set");
                }
                //endregion


                //region sub postserial
                $buildTablesDetailSerial = $postSerial["detail"];
                $componentGate['detail'] = array();
                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                $filterNeeded = false;
                $iterator = array();
                $iterator = $buildTablesDetailSerial;
                // $iterator =array();
//                arrPrint($itemSerial);
//                matiHere();
                if (sizeof($iterator) > 0) {
                    $comsLocation = "Coms";
                    $comsPrefix = "Com";
                    foreach ($iterator as $cCtr => $tComSpec) {
                        // arrprint($tComSpec);
                        $tmpOutParams[$cCtr] = array();
                        $gg = 0;
                        foreach ($itemSerial as $id => $dSpec) {
                            // $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $itemSerial, $comName);
                            }

                            $mdlName = "$comsPrefix" . ucfirst($comName);
                            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                $filterNeeded = true;
                            }
                            else {
                                $filterNeeded = false;
                            }
                            // cekHere("sub-component: [$comsLocation] $comName, initializing values <br>");

                            $subParams = array();

                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $dSpec[$key], $key);
                                    }

                                    $realValue = makeValue($value, $dSpec, $dSpec, 0);
                                    // cekMErah("$key =>".$realValue);
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
                                    $realValue = makeValue($value, $dSpec, $dSpec, 0);
                                    $subParams['static'][$key] = $realValue;
                                }
                                if (!isset($subParams['static']["transaksi_id"])) {
                                    $subParams['static']["transaksi_id"] = 0000;
                                }
                                if (!isset($subParams['static']["transaksi_no"])) {
                                    $subParams['static']["transaksi_no"] = 0000;
                                }

                                $subParams['static']["fulldate"] = $fulldate;
                                $subParams['static']["dtime"] = $dtime;
                                $subParams['static']["keterangan"] = $jenisTrName . " oleh " . $oleh_nama;
                            }

                            if (sizeof($subParams) > 0) {
                                //                                cekhitam("subparam ada isinya");
                                if ($filterNeeded) {
                                    if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                }
                            }
                            else {
                                cekhitam("subparam TIDAK ada isinya");
                            }
                        }


                        $componentGate['detail'][$cCtr] = $subParams;
                    }

//arrprint($componentGate);
//                    matiHere();
                    foreach ($iterator as $cCtr => $tComSpec) {
                        // $srcGateName = $tComSpec['srcGateName'];
                        foreach ($itemSerial as $id => $dSpec) {
                            // $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $itemSerial[$id][$comName], $comName);
                            }
                        }
                        cekHere("sub component: [$comsLocation] $comName, sending values " . __LINE__ . "<br>");

                        $mdlName = "$comsPrefix" . ucfirst($comName);
                        $this->load->model("$comsLocation/" . $mdlName);
                        $m = new $mdlName();
                        //===filter value nol, jika harus difilter

                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        // matiHEre($tobeExecuted);
                        if ($tobeExecuted) {
                            //----- kiriman gerbang
                            if (method_exists($m, "setTableInMaster")) {
                                $m->setTableInMaster($tempTableinMAster);
                            }
                            if (method_exists($m, "setDetail")) {
                                $m->setDetail($itemSerial);
                            }
                            if (method_exists($m, "setJenisTr")) {
                                $m->setJenisTr($this->jenisTr);
                            }
                            //----- kiriman gerbang
                            $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            cekBiru($this->db->last_query());
                        }
                        else {
                            cekMerah("$comName tidak eksekusi");
                        }
                    }
                }
                else {
                    cekKuning("subcomponents is not set");
                }

                //endregion

            }
            //region update fifoaverage
            if (count($listUpdateFIfo) > 0) {
                arrPrint($listUpdateFIfo);
                $this->load->model("Mdls/MdlFifoAverage");
                $b = new MdlFifoAverage();
                foreach ($listUpdateFIfo as $tbid => $data_update) {
                    $b->setFilters(array());
                    $b->updateData(array("id" => $tbid), $data_update) or matiHere("gagal update average");
                    cekHitam($this->db->last_query());
                }
            }
            //update project tasklist
            if (count($listUpdateProjectTask) > 0) {
                $tr = New MdlTasklistProject;
                foreach ($listUpdateProjectTask as $spk => $updatetask) {
                    $tr->setFilters(array());
                    $tr->updateData(array("no_spk" => $spk), $updatetask) or matiHere("gagal update tasklist");

                    cekHitam($this->db->last_query());
                }

            }
            //update project subtasklist
            if (count($listUpdateProjectTask_sub) > 0) {
                $this->load->model("Mdls/MdlSubProgresTasklistKomposisi");
                $pst = new MdlSubProgresTasklistKomposisi();
                foreach ($listUpdateProjectTask_sub as $sub_task_id => $updateSub) {
                    $pst->setFilters(array());
                    $pst->updateData(array("id" => $sub_task_id), $updateSub) or matiHere("gagal update sub tasklist");
                    cekHitam($this->db->last_query());
                }
            }

            //endregion
//            matiHEre(__LINE__);
            validateAllBalances();
//        validateAllBalances($tokoID, $cabangID_validate);
            $end = microtime(true);
            $selesai = $end - $start;


            //-------------------
            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                $tbl = "_rek_pembantu_produk_cache";
                $rek = "1010030030";
                $this->db->where(
                    array(
                        "rekening" => $rek,
                        "cabang_id" => $cabangID,
                        "gudang_id" => $gudangID,
                        "periode" => "forever",
                    )
                );
                $queryDetail = $this->db->get($tbl)->result();
                showLast_query("biru");
                $debet_total = 0;
                $kredit_total = 0;
                foreach ($queryDetail as $qSpec) {
                    $debet = $qSpec->debet;
                    $kredit = $qSpec->kredit;
                    $debet_total += $debet;
                    $kredit_total += $kredit;
                }
                cekMerah("[DEBET: $debet_total] [KREDIT: $kredit_total]");
                if ($debet_total > 1) {
                    mati_disini("STOP... TIDAK HABIS...");
                }
            }
            //-------------------


//            matiHEre("complitt [selesai dalam $selesai]");

            $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");


        }
        else {
            cekMerah("belum jam 21 cli belum dijalankan");
        }

        cekHijau("<h3>SELESAI... [$selesai]</h3>");
    }

    public function runRealisasiProjectSupplies()
    {
        $this->load->model("CustomCounter");
        $this->load->helper("he_angka");
        $this->load->helper("he_mass_table");
        $component = array(
            "master" => array(
                // jurnal ke 1 piutang dagang/penjualan per WO
                /*
                 * realtive costnem masuk ke COA Hpp produk
                 * costing  masuk ke kategory
                 */
                array(
                    "comName" => "Jurnal",
                    "loop" => array(
//                        "5020" => "hpp_budget",//hpp budget project debet
                        "3020010" => "-hpp",//efisiensi kredit
                        "1010030010" => "-hpp"//persediaan
                    ),

                    "static" => array(
                        "cabang_id" => "placeID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                        "transaksi_id" => "transaksi_id",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                array(
                    "comName" => "Rekening",
                    "loop" => array(
//                        "5020" => "hpp_budget",//hpp budget project
                        "3020010" => "-hpp",//efisiensi kredit
                        "1010030010" => "-hpp"//persediaan
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                        "transaksi_id" => "transaksi_id",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),

            ),
            "detail" => array(
                //<editor-fold desc="subkomponen milik cabang">
                array(
                    "comName" => "RekeningPembantuSupplies",
                    "loop" => array(
                        "1010030010" => "-sub_hpp",// persediaan produk
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id",
                        "extern_nama" => "name",
                        "produk_qty" => "-jml",
                        "produk_nilai" => "hpp",
                        "gudang_id" => "gudangID",
//                            "gudang_id" => "gudangProjectID",
                        "transaksi_no" => "transaksi_no",
                        "transaksi_id" => "transaksi_id",
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),
                // pembantu efisiensi bahan baku (RAB) => kredit perkategori biaya
                array(
                    "comName" => "RekeningPembantuEfisiensiBiaya",
                    "loop" => array(
//                        "3020010" => "sub_hpp_produk_budget",//bahan baku dengan nilai bom masih single produk belum suport multi, jik sudah suport multi pakai yang ke 2
                        "3020010" => "-sub_hpp",//bahan baku dengan nilai bom masih single produk belum suport multi, jik sudah suport multi pakai yang ke 2
                        // "3020010" => "supplies_bom",//bahan baku dengan nilai bom
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "categori_biaya_id",//id kategori biaya
                        "extern_nama" => "categori_biaya_nama",
                        "extern2_id" => "categori_biaya_id",
                        "extern2_nama" => "categori_biaya_nama",
                        "produk_qty" => "-jml",
                        "produk_nilai" => "hpp",
                        "gudang_id" => "gudangID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "transaksi_no",
                        "transaksi_id" => "transaksi_id",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),
                // pembantu LV2 efisiensi (RAB) => kredit per jenis biaya per kategori project, wo,spk
                array(
                    "comName" => "RekeningPembantuEfisiensiBiayaSub",
                    "loop" => array(
//                        "3020010" => "sub_hpp_produk_budget",//efisiensi biaya
                        "3020010" => "-sub_hpp",//efisiensi biaya
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id",//biaya nya
                        "extern_nama" => "nama",
                        "extern2_id" => "categori_biaya_id",
                        "extern2_nama" => "categori_biaya_nama",
                        "extern3_id" => "project_id",//projectid
                        "extern3_nama" => "project_nama",
                        "extern4_id" => "work_order_id",//wo id
                        "extern4_nama" => "work_order_nama",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "transaksi_no",
                        "transaksi_id" => "transaksi_id",
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),
                //nulis raw efisiensi
                array(
                    "comName" => "RekeningPembantuRawItemEfisiensi",
                    "loop" => array(
//                        "3020010" => "sub_hpp_produk_budget", // isi loop adalah overhead,tenaga kerja,biaya kirim
                        "3020010" => "-sub_hpp", // isi loop adalah overhead,tenaga kerja,biaya kirim
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => ".3020010",//biaya
                        "extern_nama" => ".efisensi",
                        "extern2_nama" => "categori_biaya_nama",
                        "extern2_id" => "categori_biaya_id",
                        "extern3_id" => "work_order_id",//projectid
                        "extern3_nama" => "pihakWoProjekName",
                        "extern4_id" => "id",//biaya
                        "extern4_nama" => "nama",
                        "produk_id" => "project_id",//project
                        "produk_nama" => "project_nama",
                        "produk_kode" => "no_spk",
                        "produk_jenis" => ".project",
//                            "barcode" => "barcode",
                        "jml" => "-jml",
                        "harga" => "hpp",// harga dpp
                        "hpp" => "hpp",// hpp produk
                        "jenis" => "jenisTr",
                        "transaksi_no" => "transaksi_no",
                        "transaksi_id" => "transaksi_id",
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),
                //locker
                array(
                    "comName" => "LockerStockSupplies",
                    "loop" => array(),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "jenis" => ".supplies",
                        "state" => ".active",
                        "jumlah" => "-jml",
                        "produk_id" => "id",
                        "nama" => "name",
                        "satuan" => "satuan",
                        "oleh_id" => ".0",
                        "oleh_nama" => "",
                        "transaksi_id" => ".0",
                        "nomer" => "nomer",
                        "gudang_id" => "gudangID",// gudang work order
//                            "gudang_id" => "gudangProjectID",
                        "biaya_id" => "biaya_id",
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),
                array(
                    "comName" => "LockerStockSupplies",
                    "loop" => array(),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "jenis" => ".supplies",
                        "state" => ".sold",
                        "jumlah" => "jml",
                        "produk_id" => "id",
                        "nama" => "name",
                        "satuan" => "satuan",
                        "oleh_id" => ".0",
                        "transaksi_id" => ".0",
                        "gudang_id" => "gudangID",// gudang work order
//                            "gudang_id" => "gudangProjectID",
                        "biaya_id" => "biaya_id",
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),

                // locker stok mutasi
                array(
                    "comName" => "LockerStockMutasiSupplies",
                    "loop" => array(),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id",
                        "extern_nama" => "name",
                        "qty_debet" => "-qty",
                        "produk_nilai" => "hpp",
                        "gudang_id" => "gudangID",// gudang work order
                        "jenis" => "jenisTr",
                    ),
                    "reversable" => true,
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),
                //</editor-fold>


            ),
        );
        $postSerial = array(
//            "master" => array(),
//            "detail" => array(
//                // rekening pembantu produk serial
//                array(
//                    "comName" => "RekeningPembantuProdukPerSerial",
//                    "loop" => array(
//                        "1010030030" => ".-1",//persediaan produk, sub_diskon_nilai_total
//                    ),
//                    "static" => array(
//                        "cabang_id" => "placeID",
//                        "gudang_id" => "gudangID",
//                        "extern_id" => ".0",
//                        "extern_nama" => "produk_serial",
//                        "extern2_id" => ".0",
//                        "extern2_nama" => "produk_sku_part_nama",
//                        "produk_id" => "id",
//                        "produk_nama" => "name",
//                        "produk_qty" => "-jml",
//                        "produk_nilai" => ".1",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                            "supplierID" => "pihakID",
//                    ),
//                    "srcGateName" => "items3_sum",
//                    "srcRawGateName" => "items3_sum",
//                ),
//            ),
        );
        $start = microtime(true);
        $force = isset($_GET["force"]) ? $_GET["force"] : "none";
        $cekjam = date("H");
        $timeTOexec = false;
        if ($cekjam >= 21) {
            $timeTOexec = true;
        }
        $timeTOexec = true;
        $arrJenisTr = array(
            "588st",
        );
        $main = array();
        $items = array();
        $tableIn_master = array();
        $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
        if ($timeTOexec) {

            // region membuat tabel rekening bila belum ada
            $buildTablesMaster = $component["master"];
            if (sizeof($buildTablesMaster) > 0) {
                $bCtr = 0;
                foreach ($buildTablesMaster as $buildTablesMaster_specs) {
                    $bCtr++;
                    $mdlName = $buildTablesMaster_specs['comName'];
                    //--- INI UNTUK BUILD TABLES REKENING
                    $mdlName = "Com" . $mdlName;
                    $this->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();
                    if (isset($buildTablesMaster_specs['loop']) && sizeof($buildTablesMaster_specs['loop']) > 0) {
                        foreach ($buildTablesMaster_specs['loop'] as $key => $val) {
                            if (substr($key, 0, 1) == "{") {
                                $oldParam = $buildTablesMaster_specs['loop'][$key];
                                unset($buildTablesMaster_specs['loop'][$key]);
                                $key = trim($key, "{");
                                $key = trim($key, "}");
                                $key = str_replace($key, $main[$key], $key);
                                $buildTablesMaster_specs['loop'][$key] = $oldParam;
                            }
                        }
                    }
                    if (method_exists($m, "getTableNameMaster")) {
                        if (sizeof($m->getTableNameMaster())) {
                            $m->buildTables($buildTablesMaster_specs);
                            // cekHijau(" === build tabel rekening === ");
                        }
                    }
                }
            }

            $buildTablesDetail = $component["detail"];
            if (sizeof($component["detail"]) > 0) {
                foreach ($buildTablesDetail as $buildTablesDetail_specs) {
                    foreach ($items as $itemSpec) {
                        $mdlName = $buildTablesDetail_specs['comName'];
                        // cekLime($mdlName);
                        if (substr($mdlName, 0, 1) == "{") {
                            $mdlName = trim($mdlName, "{");
                            $mdlName = trim($mdlName, "}");
                            $mdlName = str_replace($mdlName, $itemSpec[$mdlName], $mdlName);
                        }
                        $mdlName = "Com" . $mdlName;
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();

                        if (isset($buildTablesDetail_specs['loop']) && sizeof($buildTablesDetail_specs['loop']) > 0) {
                            foreach ($buildTablesDetail_specs['loop'] as $key => $val) {
                                if (substr($key, 0, 1) == "{") {
                                    $oldParam = $buildTablesDetail_specs['loop'][$key];
                                    unset($buildTablesDetail_specs['loop'][$key]);
                                    $key = trim($key, "{");
                                    $key = trim($key, "}");
                                    $key = str_replace($key, $itemSpec[$key], $key);
                                    $buildTablesDetail_specs['loop'][$key] = $oldParam;
                                }
                            }
                        }
                        if (method_exists($m, "getTableNameMaster")) {
                            if (sizeof($m->getTableNameMaster())) {
                                $m->buildTables($buildTablesDetail_specs);
                                // cekHitam(" === build tabel rekening === ");
                            }
                        }
                    }
                }
            }
            // endregion membuat tabel rekening bila belum ada


            $this->db->trans_start();


            $itemSelect = array(
                //key =>src
                "id" => "biaya_dasar_id",
                "nama" => "biaya_dasar_nama",
                "jenis" => "jenis",
                "no_spk" => "no_spk",
                "work_order_id" => "sub_fase_id",
                "work_order_nama" => "sub_fase_nama",
                "project_id" => "produk_id",
                "project_nama" => "produk_nama",
                "produk_id" => "biaya_dasar_id",
                "jml" => "jml",
                "qty" => "jml",
                "harga" => "harga",
                "categori_biaya_id" => "cat_id",
                "categori_biaya_nama" => "cat_nama",
                "dtime" => "dtime",
                "biaya_id" => "biaya_id",
                "biaya_nama" => "biaya_nama"
            );
            $this->load->model("Mdls/MdlTasklistProject");
            $tr = New MdlTasklistProject;
            /**
             * query select masih ditanam di model
             */
            $datas = $tr->lookupJoinSupplies($tr->getTableName(), "project_sub_tasklist_komposisi")->result();
            cekHitam($this->db->last_query());
            arrPrintWebs($datas);
//            matiHere(__LINE__);
            $grupSpkItems = array();
            if (count($datas) > 0) {
                $this->load->model("Mdls/MdlProdukProject");
                $p = new MdlProdukProject();

                //ambil info transaksi startproject untuk ambil ID dan nomer dari produk project
                $produk_id = $datas[0]->produk_id;
                $p->addFilter("id='$produk_id'");
                $tempProduk = $p->fectDataProject()->result();
                $cabangID = $tempProduk[0]->cabang_id;
                $cabangName = $tempProduk[0]->cabang_nama;
                $gudangID = $datas[0]->gudang_wo;
                $no_spk = $datas[0]->no_spk;
                showLast_query("biru");
                arrPrint($tempProduk);
//matiHEre($cabangID);
                //pastikan sudah ada isi transaki_id
                if ($datas[0]->post_return_id == 0) {
                    matiHEre("belum diijinkan jalan belum scan serial di cabang project");
                }
                $mainData = array(
                    "transaksi_id" => $datas[0]->post_return_id,
                    "transaksi_no" => $datas[0]->post_return_no,
                    "jenis" => "9833",
                    "nomer" => $datas[0]->post_return_no,
                    "oleh_id" => $datas[0]->employee_id,
                    "oleh_nama" => $datas[0]->employee_nama,
                    "dtime" => $datas[0]->post_return_dtime,
                    "fulldate" => $datas[0]->post_return_dtime,
                    "pihak_id" => $tempProduk[0]->customer_id,
                    "pihak_nama" => $tempProduk[0]->customer_nama,
                    "pihakID" => $tempProduk[0]->customer_id,
                    "pihakName" => $tempProduk[0]->customer_nama,
                    "project_id" => $tempProduk[0]->id,
                    "project_nama" => $tempProduk[0]->nama,

                    "jenisTr" => "9833",
                    "cabang_id" => $cabangID,
                    "placeID" => $cabangID,
                    "placeName" => $cabangName,
                    "cabang_nama" => $cabangName,
                    "gudangID" => $datas[0]->gudang_wo,
                    "gudangName" => "default branch #1",
                    "gudang_id" => $gudangID,
                    "gudang_nama" => "default branch WO#1",

                );
//                arrPrintCyan($mainData);
//                mati_disini(__LINE__);
                $iidsUpdate = array();
                $items = array();

                $subhpp = 0;
                $listUpdateFIfo = array();
                $listUpdateProjectTask[$no_spk] = array(
                    "post_return_cli" => "11",

                );
                $listUpdateProjectTask_sub = array();
                $listProduk = array();
                foreach ($datas as $datas_0) {
                    $datas_0_jml = $datas_0->jml;
                    unset($datas_0->jml);
                    $datas_0->jml = $datas_0_jml - $datas_0->jml_return;
                    $datas_0->qty = $datas_0_jml - $datas_0->jml_return;

//                    arrprint($datas_0);
                    //ambil fifoavg untuk dapat nilai
                    foreach ($itemSelect as $key => $src) {
                        //overwrite jaga jaga pengembalian stok tidak ngurangi saldo_debet
                        $items[$datas_0->biaya_dasar_id][$key] = $datas_0->$src;
                    }
                    $listProduk[$datas_0->biaya_dasar_id] = array(
                        "produk_id" => $datas_0->biaya_dasar_id,
                        "cabang_id" => $cabangID,
                        "gudang_id" => $gudangID,
                    );

                    $cekPrevalue = $this->_cekPrevalueAverageSupplies($cabangID, $datas_0->gudang_wo, $datas_0->biaya_dasar_id);
                    cekHitam($this->db->last_query());
//                    arrPrintWebs($cekPrevalue);
                    if ($cekPrevalue["jml"] >= $datas_0->jml) {
                        $new_qty = $cekPrevalue["jml"] - $datas_0->jml;
                        $dipakai = $datas_0->jml;
                        $hpp = $cekPrevalue["hpp"];
                        $sub_hpp = $cekPrevalue["hpp"] * $datas_0->jml;
                        $fifo_nilai_hpp = $cekPrevalue["jml_nilai"] - $sub_hpp;
//                        if ($cekPrevalue["jml_nilai"] - ($cekPrevalue["hpp"] * $datas_0->jml) < 0) {
                        if ($fifo_nilai_hpp < -1) {
                            cekHitam("preValue jml_nilai : " . $cekPrevalue["jml_nilai"]);
                            cekHitam("preValue hpp : " . $cekPrevalue["hpp"]);
                            cekHitam("jml : " . $datas_0->jml);
                            matiHEre("koq minuss [$fifo_nilai_hpp] || " . reformatExponent($fifo_nilai_hpp));
                        }
                        $listUpdateFIfo[$cekPrevalue['id']] = array(
                            "jml" => $new_qty,
                            "jml_nilai" => $cekPrevalue["jml_nilai"] - ($cekPrevalue["hpp"] * $datas_0->jml),
                        );
//                        $b = new MdlFifoAverage();
//                        $updaters[] = $b->updateData(array("id" => $cekPrevalue['id']), $updateData);
                        cekMerah($this->db->last_query());


                    }
                    else {
                        matiHEre($datas_0->jml . " ::STOK KURANG SILAHKAN CEK PERSEDIAAN GUDANG_WO :" . $datas_0->gudang_wo);
                    }
                    $listUpdateProjectTask_sub[$datas_0->id] = array(
//                        "id"=>$datas_0->id,
//                        "no_spk"=>$datas_0->id,
                        "qty_kredit" => $datas_0->jml,
                        "qty_saldo" => $datas_0->qty_debet - $datas_0->jml,
                        "cli" => 1,
                    );

//                    $subharga += ($datas_0["jml"] * $datas_0["harga"]);
                    $subhpp += $sub_hpp;
//cekMerah($datas_0->produk_id);
                    $items[$datas_0->biaya_dasar_id]["sub_hpp"] = $sub_hpp;
                    $items[$datas_0->biaya_dasar_id]["hpp"] = $hpp;
                    $items[$datas_0->biaya_dasar_id]["subtotal"] = $sub_hpp;
                    $items[$datas_0->biaya_dasar_id]["transaksi_id"] = $datas[0]->post_return_id;
                    $items[$datas_0->biaya_dasar_id]["transaksi_no"] = $datas[0]->post_return_no;
                    $items[$datas_0->biaya_dasar_id]["oleh_id"] = $datas[0]->employee_id;
                    $items[$datas_0->biaya_dasar_id]["oleh_nama"] = $datas[0]->employee_nama;
                    $items[$datas_0->biaya_dasar_id]["placeID"] = $cabangID;
                    $items[$datas_0->biaya_dasar_id]["placeName"] = $cabangName;
                    $items[$datas_0->biaya_dasar_id]["cabang_id"] = $cabangID;
                    $items[$datas_0->biaya_dasar_id]["cabang_nama"] = $cabangName;
                    $items[$datas_0->biaya_dasar_id]["gudangID"] = $gudangID;
                    $items[$datas_0->biaya_dasar_id]["gudangName"] = "default branch wo#1";
                }
                $mainData["hpp"] = $subhpp;
//                $mainData["piutang_dagang"] = $subharga + ($subharga * (my_ppn_factor() / 100));
//                arrprint($listProduk);
                $this->load->model("Coms/ComRekeningPembantuProdukPerSerial");
                $ps = new ComRekeningPembantuProdukPerSerial();
                $itemSerial = array();
                foreach ($listProduk as $pid => $pirData) {
                    $ps->addFilter("cabang_id='" . $pirData["cabang_id"] . "'");
                    $ps->addFilter("gudang_id='" . $pirData["gudang_id"] . "'");
                    $ps->addFilter("produk_id='" . $pirData["produk_id"] . "'");
                    $preSerial = $ps->fetchBalances("1010030030");
                    if (count($preSerial) > 0) {
                        foreach ($preSerial as $preSerial_0) {
                            $itemSerial[] = array(
                                "id" => $preSerial_0->produk_id,
                                "produk_id" => $preSerial_0->produk_id,
                                "nama" => $preSerial_0->produk_nama,
                                "name" => $preSerial_0->produk_nama,
                                "produk_serial" => $preSerial_0->extern_nama,
                                "produk_sku_part_nama" => $preSerial_0->extern2_nama,
                                "cabang_id" => $preSerial_0->cabang_id,
                                "gudang_id" => $preSerial_0->gudang_id,
                                "placeID" => $preSerial_0->cabang_id,
                                "gudangID" => $preSerial_0->gudang_id,
                                "jml" => "1",
                                "transaksi_id" => $datas[0]->post_return_id,
                                "transaksi_no" => $datas[0]->post_return_no,
                                "nomer" => $datas[0]->post_return_no,
                                "jenis" => "9833",
                            );
                        }
                    }
                }
            }
            else {
                mati_disini("CLI HABIS... code: " . __LINE__);
            }


//cekHitam(count($itemSerial));
//arrprint($itemSerial);
//            matiHere(__LINE__);


            if (count($mainData) > 0) {
                $dtime = $mainData["dtime"];
                $fulldate = $mainData["fulldate"];
                $jenisTrName = $mainData["jenisTr"];
                $oleh_nama = $mainData["oleh_nama"];
                $this->jenisTr = $jenis = $mainData["jenis"];

                $componentGate['master'] = array();
                $componentConfig['master'] = array();
                //==filter nilai, jika NOL tidak dikirim, sesuai config==
                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();

                $iterator = array();
                $componentConfig['master'] = $buildTablesMaster;
                $iterator = $buildTablesMaster;
                $tempTableinMAster = $mainData;

                //region master
                // $iterator = array();
                if (sizeof($iterator) > 0) {
                    $componentConfig['master'] = $iterator;
                    $cCtr = 0;
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $cCtr++;
                        $comName = $tComSpec['comName'];
                        if (substr($comName, 0, 1) == "{") {
                            $comName = trim($comName, "{");
                            $comName = trim($comName, "}");
                            $comName = str_replace($comName, $mainData, $comName);
                        }
                        // $srcGateName = $tComSpec['srcGateName'];
                        // $srcRawGateName = $tComSpec['srcRawGateName'];
                        // cekHere("component # $cCtr: $comName<br>");

                        $dSpec = $mainData;
                        $tmpOutParams = array();
                        if (isset($tComSpec['loop'])) {
                            foreach ($tComSpec['loop'] as $key => $value) {
                                if (substr($key, 0, 1) == "{") {
                                    $key = trim($key, "{");
                                    $key = trim($key, "}");
                                    $key = str_replace($key, $mainData[$key], $key);
                                }
                                $realValue = makeValue($value, $mainData, $mainData, 0);
                                $tmpOutParams['loop'][$key] = $realValue;
                            }
                        }
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {
                                $realValue = makeValue($value, $mainData, $mainData, 0);
                                $tmpOutParams['static'][$key] = $realValue;
                            }
                            if (!isset($tmpOutParams['static']["transaksi_id"])) {
                                $tmpOutParams['static']["transaksi_id"] = "0000";
                            }
                            if (!isset($tmpOutParams['static']["transaksi_no"])) {
                                $tmpOutParams['static']["transaksi_no"] = "0000";
                            }
                            $tmpOutParams['static']["urut"] = $cCtr;
                            $tmpOutParams['static']["fulldate"] = $fulldate;
                            $tmpOutParams['static']["dtime"] = $dtime;
                            $tmpOutParams['static']["keterangan"] = $jenisTrName . " oleh " . $oleh_nama;
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
                        // arrprint($jenis);
                        //                     matiHEre();
                        if ($tobeExecuted) {
                            //----- kiriman gerbang untuk counter mutasi rekening
                            if (method_exists($m, "setTableInMaster")) {
                                $m->setTableInMaster($tempTableinMAster);
                            }
                            if (method_exists($m, "setMain")) {
                                $m->setMain($mainData);
                            }
                            if (method_exists($m, "setJenisTr")) {
                                $m->setJenisTr($jenis);
                            }
                            arrPrint($tmpOutParams);
//                            matiHere();
                            //----- kiriman gerbang untuk counter mutasi rekening
                            $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            cekBiru($this->db->last_query());
                        }
                        $componentGate['master'][$cCtr] = $tmpOutParams;
                    }
                }
                else {
                    cekKuning("components is not set");
                }
                //endregion


                //region processing sub-components
                $componentGate['detail'] = array();
                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                $filterNeeded = false;
                $componentConfig['detail'] = $buildTablesDetail;
                $iterator = $buildTablesDetail;
                // $iterator =array();
                if (sizeof($iterator) > 0) {
                    $comsLocation = "Coms";
                    $comsPrefix = "Com";
                    foreach ($iterator as $cCtr => $tComSpec) {
                        // arrprint($tComSpec);
                        $tmpOutParams[$cCtr] = array();
                        $gg = 0;
                        // $srcGateName = $tComSpec['srcGateName'];
                        // if ($componentsDetailLoop == true) {
                        foreach ($items as $id => $dSpec) {
                            // $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $items, $comName);
                            }

                            $mdlName = "$comsPrefix" . ucfirst($comName);
                            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                $filterNeeded = true;
                            }
                            else {
                                $filterNeeded = false;
                            }
                            // cekHere("sub-component: [$comsLocation] $comName, initializing values <br>");

                            $subParams = array();

                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $dSpec[$key], $key);
                                    }

                                    $realValue = makeValue($value, $dSpec, $dSpec, 0);
                                    // cekMErah("$key =>".$realValue);
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
                                    $realValue = makeValue($value, $dSpec, $dSpec, 0);
                                    $subParams['static'][$key] = $realValue;
                                }
                                if (!isset($subParams['static']["transaksi_id"])) {
                                    $subParams['static']["transaksi_id"] = 0000;
                                }
                                if (!isset($subParams['static']["transaksi_no"])) {
                                    $subParams['static']["transaksi_no"] = 0000;
                                }

                                if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                    foreach ($paramPatchers[$comName] as $k => $v) {
                                        if (!isset($subParams['static'][$k])) {
                                            $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                        }
                                    }
                                }
                                if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                    foreach ($paramForceFillers[$comName] as $k => $v) {
                                        $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                    }
                                }

                                $subParams['static']["fulldate"] = $fulldate;
                                $subParams['static']["dtime"] = $dtime;
                                $subParams['static']["keterangan"] = $jenisTrName . " oleh " . $oleh_nama;
                            }

                            if (sizeof($subParams) > 0) {
                                //                                cekhitam("subparam ada isinya");
                                if ($filterNeeded) {
                                    if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                }
                            }
                            else {
                                cekhitam("subparam TIDAK ada isinya");
                            }
                        }


                        $componentGate['detail'][$cCtr] = $subParams;
                    }
                    // arrPrint($tmpOutParams);
                    // matiHEre($cCtr);

                    foreach ($iterator as $cCtr => $tComSpec) {
                        // $srcGateName = $tComSpec['srcGateName'];
                        foreach ($items as $id => $dSpec) {
                            // $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $items[$id][$comName], $comName);
                            }
                        }
                        cekHere("sub component: [$comsLocation] $comName, sending values " . __LINE__ . "<br>");

                        $mdlName = "$comsPrefix" . ucfirst($comName);
                        $this->load->model("$comsLocation/" . $mdlName);
                        $m = new $mdlName();
                        //===filter value nol, jika harus difilter

                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        // matiHEre($tobeExecuted);
                        if ($tobeExecuted) {
                            //----- kiriman gerbang
                            if (method_exists($m, "setTableInMaster")) {
                                $m->setTableInMaster($tempTableinMAster);
                            }
                            if (method_exists($m, "setDetail")) {
                                $m->setDetail($items);
                            }
                            if (method_exists($m, "setJenisTr")) {
                                $m->setJenisTr($this->jenisTr);
                            }
                            //----- kiriman gerbang
                            $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            cekBiru($this->db->last_query());
                        }
                        else {
                            cekMerah("$comName tidak eksekusi");
                        }
                    }
                }
                else {
                    cekKuning("subcomponents is not set");
                }
                //endregion


                //region sub postserial
                $buildTablesDetailSerial = $postSerial["detail"];
                $componentGate['detail'] = array();
                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                $filterNeeded = false;
                $iterator = array();
                $iterator = $buildTablesDetailSerial;
                // $iterator =array();
//                arrPrint($itemSerial);
//                matiHere();
                if (sizeof($iterator) > 0) {
                    $comsLocation = "Coms";
                    $comsPrefix = "Com";
                    foreach ($iterator as $cCtr => $tComSpec) {
                        // arrprint($tComSpec);
                        $tmpOutParams[$cCtr] = array();
                        $gg = 0;
                        foreach ($itemSerial as $id => $dSpec) {
                            // $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $itemSerial, $comName);
                            }

                            $mdlName = "$comsPrefix" . ucfirst($comName);
                            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                $filterNeeded = true;
                            }
                            else {
                                $filterNeeded = false;
                            }
                            // cekHere("sub-component: [$comsLocation] $comName, initializing values <br>");

                            $subParams = array();

                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $dSpec[$key], $key);
                                    }

                                    $realValue = makeValue($value, $dSpec, $dSpec, 0);
                                    // cekMErah("$key =>".$realValue);
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
                                    $realValue = makeValue($value, $dSpec, $dSpec, 0);
                                    $subParams['static'][$key] = $realValue;
                                }
                                if (!isset($subParams['static']["transaksi_id"])) {
                                    $subParams['static']["transaksi_id"] = 0000;
                                }
                                if (!isset($subParams['static']["transaksi_no"])) {
                                    $subParams['static']["transaksi_no"] = 0000;
                                }

                                $subParams['static']["fulldate"] = $fulldate;
                                $subParams['static']["dtime"] = $dtime;
                                $subParams['static']["keterangan"] = $jenisTrName . " oleh " . $oleh_nama;
                            }

                            if (sizeof($subParams) > 0) {
                                //                                cekhitam("subparam ada isinya");
                                if ($filterNeeded) {
                                    if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                }
                            }
                            else {
                                cekhitam("subparam TIDAK ada isinya");
                            }
                        }


                        $componentGate['detail'][$cCtr] = $subParams;
                    }

//arrprint($componentGate);
//                    matiHere();
                    foreach ($iterator as $cCtr => $tComSpec) {
                        // $srcGateName = $tComSpec['srcGateName'];
                        foreach ($itemSerial as $id => $dSpec) {
                            // $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $itemSerial[$id][$comName], $comName);
                            }
                        }
                        cekHere("sub component: [$comsLocation] $comName, sending values " . __LINE__ . "<br>");

                        $mdlName = "$comsPrefix" . ucfirst($comName);
                        $this->load->model("$comsLocation/" . $mdlName);
                        $m = new $mdlName();
                        //===filter value nol, jika harus difilter

                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        // matiHEre($tobeExecuted);
                        if ($tobeExecuted) {
                            //----- kiriman gerbang
                            if (method_exists($m, "setTableInMaster")) {
                                $m->setTableInMaster($tempTableinMAster);
                            }
                            if (method_exists($m, "setDetail")) {
                                $m->setDetail($itemSerial);
                            }
                            if (method_exists($m, "setJenisTr")) {
                                $m->setJenisTr($this->jenisTr);
                            }
                            //----- kiriman gerbang
                            $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            cekBiru($this->db->last_query());
                        }
                        else {
                            cekMerah("$comName tidak eksekusi");
                        }
                    }
                }
                else {
                    cekKuning("subcomponents is not set");
                }

                //endregion


            }

            //region update fifoaverage
            if (count($listUpdateFIfo) > 0) {
                arrPrint($listUpdateFIfo);
                $this->load->model("Mdls/MdlFifoAverageSupplies");
                $b = new MdlFifoAverageSupplies();
                foreach ($listUpdateFIfo as $tbid => $data_update) {
                    $b->setFilters(array());
                    $b->updateData(array("id" => $tbid), $data_update) or matiHere("gagal update average");
                    cekHitam($this->db->last_query());
                }
            }
            //update project tasklist
            if (count($listUpdateProjectTask) > 0) {
                $tr = New MdlTasklistProject;
                foreach ($listUpdateProjectTask as $spk => $updatetask) {
                    $tr->setFilters(array());
                    $tr->updateData(array("no_spk" => $spk), $updatetask) or matiHere("gagal update tasklist");

                    cekHitam($this->db->last_query());
                }

            }
            //update project subtasklist
            if (count($listUpdateProjectTask_sub) > 0) {
                $this->load->model("Mdls/MdlSubProgresTasklistKomposisi");
                $pst = new MdlSubProgresTasklistKomposisi();
                foreach ($listUpdateProjectTask_sub as $sub_task_id => $updateSub) {
                    $pst->setFilters(array());
                    $pst->updateData(array("id" => $sub_task_id), $updateSub) or matiHere("gagal update sub tasklist");
                    cekHitam($this->db->last_query());
                }
            }

            //endregion


            validateAllBalances();

            $end = microtime(true);
            $selesai = $end - $start;

            //-------------------
            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                $tbl = "_rek_pembantu_supplies_cache";
                $rek = "1010030010";
                $this->db->where(
                    array(
                        "rekening" => $rek,
                        "cabang_id" => $cabangID,
                        "gudang_id" => $gudangID,
                        "periode" => "forever",
                    )
                );
                $queryDetail = $this->db->get($tbl)->result();
                showLast_query("biru");
                $debet_total = 0;
                $kredit_total = 0;
                foreach ($queryDetail as $qSpec) {
                    $debet = $qSpec->debet;
                    $kredit = $qSpec->kredit;
                    $debet_total += $debet;
                    $kredit_total += $kredit;
                }
                cekMerah("[DEBET: $debet_total] [KREDIT: $kredit_total]");
                if ($debet_total > 1) {
                    mati_disini("STOP... TIDAK HABIS...");
                }
            }
            //-------------------


//            matiHEre("complitt [selesai dalam $selesai]");

            $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");


        }
        else {
            cekMerah("belum jam 21 cli belum dijalankan");
        }

        cekHijau("<h3>SELESAI... [$selesai]</h3>");
    }

    public function _cekPrevalueAverage($cabang_id, $gudang_id, $produk_id)
    {
        $this->load->model("Mdls/MdlFifoAverage");
        $b = new MdlFifoAverage();
        $b->addFilter("jenis='produk'");
        $b->addFilter("cabang_id='" . $cabang_id . "'");
        $b->addFilter("gudang_id='" . $gudang_id . "'");
        $b->addFilter("produk_id='" . $produk_id . "'");
        // ini diupdate ke => for update
//                    $tmp = $b->lookupAll()->result();

        $localFilters = array();
        if (sizeof($b->getfilters()) > 0) {
            foreach ($b->getfilters() as $f) {
                $tmpArr = explode("=", $f);
                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");
            }
        }
        $query = $this->db->select()
            ->from($b->getTableName())
            ->where($localFilters)
            ->limit(1)
            ->get_compiled_select();
        return $this->db->query("{$query} FOR UPDATE")->row_array();

    }

    public function _cekPrevalueAverageSupplies($cabang_id, $gudang_id, $produk_id)
    {
        $this->load->model("Mdls/MdlFifoAverageSupplies");
        $b = new MdlFifoAverageSupplies();
        $b->addFilter("jenis='supplies'");
        $b->addFilter("cabang_id='" . $cabang_id . "'");
        $b->addFilter("gudang_id='" . $gudang_id . "'");
        $b->addFilter("produk_id='" . $produk_id . "'");
        // ini diupdate ke => for update
//                    $tmp = $b->lookupAll()->result();

        $localFilters = array();
        if (sizeof($b->getfilters()) > 0) {
            foreach ($b->getfilters() as $f) {
                $tmpArr = explode("=", $f);
                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");
            }
        }
        $query = $this->db->select()
            ->from($b->getTableName())
            ->where($localFilters)
            ->limit(1)
            ->get_compiled_select();
        return $this->db->query("{$query} FOR UPDATE")->row_array();

    }


    //---------------------
    public function releaserLockerTransaksi()
    {
        $this->load->model("Mdls/MdlLockerTransaksi");
        $this->load->model("Mdls/MdlEmployee_all");
        $arrOlehID = array();

        $this->db->trans_start();


        $tr = New MdlLockerTransaksi();
        $tr->addFilter("state='hold'");
        $tr->addFilter("jumlah>'0'");
        $tr->addFilter("oleh_id>'0'");
        $trTmp = $tr->lookupAll()->result();
        showLast_query("biru");
        cekBiru(count($trTmp));
        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $trSpec) {
                $arrOlehID[$trSpec->oleh_id] = $trSpec->oleh_id;
                $id_tbl = $trSpec->id;
                $where = array(
                    "id" => $id_tbl,
                );
                $data = array(
                    "jumlah" => 0,
                );
                $tr = New MdlLockerTransaksi();
                $tr->setFilters(array());
                $tr->updateData($where, $data);
                showLast_query("orange");
            }
        }

        if (sizeof($arrOlehID) > 0) {
            $ep = New MdlEmployee_all();
            foreach ($arrOlehID as $employee_id) {
                $where = array(
                    "id" => $employee_id,
                );
                $data = array(
                    "phpsessid" => 0,
                    "status_login" => 0,
                );
                $ep->setFilters(array());
                $ep->updateData($where, $data);
                showLast_query("pink");
            }
        }

        mati_disini(__LINE__ . " TESTING SAJA...");

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

    }

    //untuk generate payment source data-data project lama sebelum auto terbit retensi dari approval so/quotation
    public function generatePaymentSourceRetensiProject()
    {

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        //generate payment source retensi (jika ada)
        $this->db->where("status=1");
        $this->db->where("trash=0");
//        $this->db->limit(1);
//        $this->db->order_by("id desc");
        $produk_project = $this->db->get("project_produk")->result();

        $data = [
            "jenis" => "588so",
            "target_jenis" => "7488",
            "reference_jenis" => "588so",
            "transaksi_id" => 526462, //quot_id
            "extern_id" => 210,
            "extern_nama" => "PT. PARAMOUNT ENTERPRISE INTERNATIONAL",
            "nomer" => "588so.1.210.55",
            "label" => "retensi",
            "tagihan" => "522500.0000000000",
            "sisa" => "522500.0000000000",
            "cabang_id" => 1,
            "cabang_nama" => "CABANG 1",
            "oleh_id" => 1080,
            "oleh_nama" => "manager project",
            "dtime" => "2025-08-04 14:29:51",
            "fulldate" => "2025-08-04",
            "extern_nilai2" => "11599500.0000000000",
            "project_id" => 103,
            "project_nama" => "PARAPA",
        ];

        echo "TOTAL PROJECT: " . count($produk_project) . "<br><br>";

        $belum_retensi = 0;
        $tanpa_retensi = 0;
        $sudah_retensi = 0;
        $belum_quot = 0;

        $belum_um = 0;
        $tanpa_um = 0;
        $sudah_um = 0;
        $belum_quot = 0;

        foreach ($produk_project as $k => $row) {
//            arrPrint($row);
//            echo "id: " . $row->id . "<br>";
//            echo "nama: " . $row->nama . "<br>";
//
//            echo "project_start: " . $row->project_start . "<br>";
//            echo "project_start_id: " . $row->project_start_id . "<br>";
//            echo "project_started_id: " . $row->project_started_id . "<br>";
//            echo "project_started_name: " . $row->project_started_name . "<br>";
//
//            echo "closing_status: " . $row->closing_status . "<br>";
//            echo "closing_oleh_id: " . $row->closing_oleh_id . "<br>";
//
//            echo "lock: " . $row->lock . "<br>";
//            echo "lock_id: " . $row->lock_id . "<br>";
//
//            echo "customer_id: " . $row->customer_id . "<br>";
//            echo "customer_nama: " . $row->customer_nama . "<br>";
//
//            echo "quot_id: " . $row->quot_id . "<br>";
//            echo "quot_nomer: " . $row->quot_nomer . "<br>";
//
//            echo "transaksi_id: " . $row->transaksi_id . "<br>";
//            echo "transaksi_no: " . $row->transaksi_no . "<br>";
//
//            echo "harga: " . $row->harga . "<br>";
//            echo "harga_project_so: " . $row->harga_project_so . "<br>";
//            echo "uang_muka_request: " . $row->uang_muka_request . "<br>";
//            echo "uang_muka_approved: " . $row->uang_muka_approved . "<br>";
//            echo "=================================<br>";

            if ($row->quot_id * 1 > 0) {
                $this->db->where("transaksi_id", $row->quot_id);
                $transaksi_data_registry = $this->db->get("transaksi_data_registry")->result();
                foreach ($transaksi_data_registry as $reg) {
                    $tQuoteID = $reg->transaksi_id;

                    $main = blobDecode($reg->main); // sesMain

//                    arrPrint($main);

                    $items3 = blobDecode($reg->items3); // termin
                    $items4 = blobDecode($reg->items4); // dp/um
                    $items5 = blobDecode($reg->items5); // garansi
                    $items7 = blobDecode($reg->items7); // info kontrak

                    $tr->setFilters(array());
                    $tr->addFilter("project_id='" . $row->id . "'");
                    $tr->addFilter("jenis='588so'");
                    $tr->addFilter("target_jenis='7488'");
                    $tmpRetensiPymSrc = $tr->lookUpPayment()->result();

                    if ($tmpRetensiPymSrc) {
//                        cekBiru("sudah ada pym retensi");
                        $sudah_retensi++;
                    }
                    else {
                        if (isset($items5[0]['harga']) && $items5[0]['harga'] * 1 > 0) {
//                            cekMerah("belum ada pym retensi");
                            $belum_retensi++;
                            $arrPymSrc = [
                                "jenis" => "588so",
                                "target_jenis" => "7488",
                                "reference_jenis" => "588so",
                                "extern_id" => $row->customer_id,
                                "extern_nama" => $row->customer_nama,
                                "nomer" => $row->quot_nomer,
                                "label" => "retensi",
                                "tagihan" => $items5[0]['harga'] * 1 / 1.11,
                                "sisa" => $items5[0]['harga'] * 1 / 1.11,
                                "cabang_id" => $row->cabang_id,
                                "cabang_nama" => $row->cabang_nama,
                                "oleh_id" => $row->quot_appr_id,
                                "oleh_nama" => $row->quot_appr_nama,
                                "dtime" => $row->quot_appr_dtime,
                                "fulldate" => date("Y-m-d", strtotime($row->quot_appr_dtime)),
                                "extern_nilai2" => $row->harga_nppn,
                                "project_id" => $row->id,
                                "project_nama" => $row->nama,
                            ];
                            $tr->writePaymentSrc($tQuoteID, $arrPymSrc);
                            showLast_query("hijau");
                        }
                        else {
//                            cekHijau("tidak setting retensi");
                            $tanpa_retensi++;
                        }
                    }

//                    $tr->setFilters(array());
//                    $tr->addFilter("project_id='".$row->id."'");
//                    $tr->addFilter("jenis='4467'");
//                    $tr->addFilter("target_jenis='04467'");
//                    $tmpUmPymSrc = $tr->lookUpPayment()->result();
//
//                    if($tmpUmPymSrc){
////                        cekBiru("sudah ada pym retensi");
//                        $sudah_um++;
//                    }
//                    else{
//                        if(isset($items4[0]['harga']) && $items4[0]['harga']*1 > 0){
////                            cekMerah("belum ada pym retensi");
//                            $belum_um++;
//                        }
//                        else{
////                            cekHijau("tidak setting retensi");
//                            $tanpa_um++;
//                        }
//                    }
                }
//                arrPrintWebs($transaksi_data_registry);
            }
            else {
                $belum_quot++;
            }
        }

        echo "belum_retensi: $belum_retensi <br>";
        echo "tanpa_retensi: $tanpa_retensi <br>";
        echo "sudah_retensi: $sudah_retensi <br><br>";

        echo "belum_um: $belum_um <br>";
        echo "tanpa_um: $tanpa_um <br>";
        echo "sudah_um: $sudah_um <br><br>";

        echo "belum_quotation: $belum_quot <br>";

        $total_project = $belum_retensi + $tanpa_retensi + $sudah_retensi + $belum_quot;
        $total_retensi = $belum_retensi + $tanpa_retensi + $sudah_retensi;
        $total_um = $belum_um + $tanpa_um + $sudah_um;

        echo "TOTAL PROJECT : $total_project <br>";
        echo "TOTAL RETENSI : $total_retensi <br>";
        echo "TOTAL UM-PROJECT : $total_um <br>";
    }

    public function checkerTermin()
    {
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();

        $tr->setFilters(array());
        $tr->addFilter("jenis='588so'");
        $tr->addFilter("target_jenis='7499'");
        $tmpTerminPymSrc = $tr->lookUpPayment()->result();


    }

    public function checkerTerminProject()
    {

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        //generate payment source retensi (jika ada)
        $this->db->where("status=1");
//        $this->db->where("trash=0");
//        $this->db->where("trash=1");
//        $this->db->where_in("id", array(27,28));
//        $this->db->limit(2);
//        $this->db->order_by("id desc");
        $this->db->order_by("persen_progress desc");
        $produk_project = $this->db->get("project_produk")->result();

//        echo "TOTAL PROJECT: " . count($produk_project) . "<br><br>";

        $varTerbayarLebihBesarDariTerminSeharusnya = array();
        $varTerbayarSamaTerminSeharusnya = array();
        $varHargaProjectLebihKecilTerbayar = array();
        $varSisaTerminTidakSamaDenganSisaTerminSeharusnya = array();
        $tagihanTidakSamaDenganTerminSeharusnya = array();
        $pymTerbayarLebihBesarDariProject = array();
        $pymTerbayar = array();
        $pymSisaLebihBesarDariNol = array();
        $pymTagihanLebihBesarDariNol = array();

        $idAllPaymentTermin = array();

        $paymentTermin = array();

        $belum_quot = 0;

        $view = "<table border='1' style='border-collapse: collapse;'>";
        $view .= "<thead>";
        $view .= "<th>No</th>";
        $view .= "<th>PID</th>";
        $view .= "<th>NAMA</th>";
        $view .= "<th>TGL</th>";
        $view .= "<th>PROGG</th>";
        $view .= "<th>NILAI PROJECT</th>";
        $view .= "<th>NILAI PROJECT PPN</th>";
        $view .= "<th>SESSION<br>NILAI PROJECT</th>";
        $view .= "<th>SETTING<BR>DP<br>(INCL.PPN)</th>";
        $view .= "<th>SETTING<BR>RETENSI<br>(INCL.PPN)</th>";
        $view .= "<th>SETTING<BR>TERMIN<br>(INCL.PPN)</th>";
//        $view  .= "<th style='background-color: black;'>TERMIN - NP</th>";
        $view .= "<th>ID-TERMIN</th>";
//        $view  .= "<th>TAGIHAN TERMIN<br>SEHARUSNYA</th>";
        $view .= "<th>TAGIHAN TERMIN</th>";
//        $view  .= "<th>TAGIHAN TERMIN - NP</th>";
//        $view  .= "<th>SELISIH %</th>";
        $view .= "<th>SISA TERMIN</th>";
//        $view  .= "<th>SISA TERMIN<br>SEHARUSNYA</th>";
        $view .= "<th>TERBAYAR TERMIN</th>";
        $view .= "<th>STATUS</th>";
        $view .= "<th>TRASH</th>";
        $view .= "</thead>";
        $view .= "<tbody>";

        $no = 0;
        foreach ($produk_project as $k => $row) {
            $no++;
            $view .= "<tr>";
            $view .= "<td>$no</td>";
            $view .= "<td>" . $row->id . "</td>";
            $view .= "<td>" . $row->nama . "</td>";
            $view .= "<td>" . $row->dtime . "</td>";
            $view .= "<td>" . round(floatval($row->persen_progress), 0) . "%</td>";
            $view .= "<td style='text-align: right;'>" . number_format($row->harga) . "</td>";
            $view .= "<td style='text-align: right;'>" . number_format(($row->harga * 1.11)) . "</td>";

            $tmpTermin = array(
                "id" => $row->id,
                "nama" => $row->nama,
                "dtime" => $row->dtime,
                "persen_progress" => $row->persen_progress,
                "harga" => $row->harga,
                "harga_nppn" => $row->harga * 1.11,
                "tagihan" => 0,
                "sisa" => 0,
                "terbayar" => 0,
                "dp_nilai" => 0,
                "retensi_nilai" => 0,
                "termin_nilai" => 0,
            );

            if ($row->quot_id > 0) {
                $this->db->where("transaksi_id", $row->quot_id);
                $transaksi_data_registry = $this->db->get("transaksi_data_registry")->result();
                foreach ($transaksi_data_registry as $reg) {
                    $tQuoteID = $reg->transaksi_id;

                    $main = blobDecode($reg->main);   // sesMain
                    $items3 = blobDecode($reg->items3); // termin
                    $items4 = blobDecode($reg->items4); // dp/um
                    $items5 = blobDecode($reg->items5); // garansi
                    $items7 = blobDecode($reg->items7); // info kontrak

                    if (isset($items3[0]['harga_project'])) {
                        $css01 = round(floatval($row->harga * 1.11)) != round(floatval($items3[0]['harga_project'])) ? "background: red;" : "";
//                        $view .= "<td style='text-align: right;$css01'>items3: ".number_format($items3[0]['harga_project'])."</td>";
                        $view .= "<td style='text-align: right;$css01'>" . number_format($items3[0]['harga_project']) . "</td>";
                    }
                    else {
                        $nilai_project_session = isset($main['projectHarga']) ? $main['projectHarga'] : $main['harga_non_ppn'];
                        $css02 = round(floatval($row->harga * 1.11)) != round(floatval($nilai_project_session)) ? "background: red;" : "";
//                        $view .= "<td style='text-align: right;$css02'>np_ses: ".number_format($nilai_project_session)."</td>";
                        $view .= "<td style='text-align: right;$css02'>" . number_format($nilai_project_session) . "</td>";
                    }

                    $tmpTermin['dp_nilai'] = isset($items4[0]['harga']) && $items4[0]['harga'] > 0 ? round(floatval($items4[0]['harga'] * 1)) : 0;
                    $tmpTermin['dp_persen'] = isset($items4[0]['persen']) && $items4[0]['persen'] > 0 ? round(floatval($items4[0]['persen'] * 1)) : 0;

                    //SETTING DP
                    $view .= "<td style='text-align: right;'>" . number_format($items4[0]['harga']) . "</td>";

                    $tmpTermin['retensi_nilai'] = isset($items5[0]['harga']) && $items5[0]['harga'] * 1 ? round(floatval($items5[0]['harga'] * 1)) : 0;
                    $tmpTermin['retensi_persen'] = isset($items5[0]['persen']) && $items5[0]['persen'] * 1 ? round(floatval($items5[0]['persen'] * 1)) : 0;

                    //SETTING RETENSI
                    $view .= "<td style='text-align: right;'>" . number_format($items5[0]['harga']) . "</td>";

                    $total_termin_ = 0;
                    $total_termin_persen = 0;
                    foreach ($items3 as $item) {
                        $total_termin_ += $item['harga'];
                        $total_termin_persen += $item['persen'];
                    }

                    $tmpTermin['termin_nilai'] = round(floatval($total_termin_));
                    $tmpTermin['termin_persen'] = round(floatval($total_termin_persen));


                    //SETING TERMIN
                    $view .= "<td style='text-align: right;'>" . number_format($total_termin_) . "</td>";

//                    $view .= "<td style='background-color: black;text-align: right;'>".number_format($total_termin_ - $row->harga ,2)."</td>";

                    $termin_seharusnya = 0;
                    if ($total_termin_ > 100) {
                        $termin_seharusnya = $total_termin_ / 1.11;
                    }
                    else {
                        $termin_seharusnya = $row->harga;
                    }

                    $tr->setFilters(array());
                    $tr->addFilter("project_id='" . $row->id . "'");
                    $tr->addFilter("jenis='588so'");
                    $tr->addFilter("target_jenis='7499'");
//                    $tr->addFilter("status='1'");
//                    $tr->addFilter("trash='0'");

                    $tmpTerminPymSrc = $tr->lookUpPayment()->result();

                    if (!empty($tmpTerminPymSrc)) {

                        $tmpTermin['tagihan'] = round(floatval($tmpTerminPymSrc[0]->tagihan));
                        $tmpTermin['sisa'] = round(floatval($tmpTerminPymSrc[0]->sisa));
                        $tmpTermin['terbayar'] = round(floatval($tmpTerminPymSrc[0]->terbayar)) > 0 ? round(floatval($tmpTerminPymSrc[0]->terbayar)) : 0;
                        $tmpTermin['payment_id'] = $tmpTerminPymSrc[0]->id;
                        $tmpTermin['check'] = $total_termin_ * 1 > 0 ? round(floatval(($total_termin_ / 1.11) - $tmpTerminPymSrc[0]->tagihan)) : round(floatval($row->harga - $tmpTerminPymSrc[0]->tagihan));
                        $tmpTermin['tagihan_seharusnya'] = $total_termin_ * 1 > 0 ? round(floatval(($total_termin_ / 1.11))) : round(floatval(($row->harga - ($items5[0]['harga'] / 1.11) - ($items4[0]['harga'] / 1.11))));
                        $tmpTermin['harga_nppn_seharusnya'] = $total_termin_ * 1 > 0 ? round(floatval(($total_termin_ / 1.11) * 1.11)) : round(floatval(($row->harga - ($items5[0]['harga'] / 1.11) - ($items4[0]['harga'] / 1.11)) * 1.11));
                        $tmpTermin['sisa_seharusnya'] = round(floatval($tmpTerminPymSrc[0]->tagihan - $tmpTerminPymSrc[0]->terbayar));

                        // if(round(floatval($termin_seharusnya), 2) != round(floatval($tmpTerminPymSrc[0]->tagihan), 2) ){
                        //     $tagihanTidakSamaDenganTerminSeharusnya[$tmpTerminPymSrc[0]->id] = array(
                        //         "id" => $tmpTerminPymSrc[0]->id,
                        //         "project" => $row->harga,
                        //         "tagihan" => $tmpTerminPymSrc[0]->tagihan,
                        //         "tagihan_seharusnya" => $termin_seharusnya,
                        //         "terbayar" => $tmpTerminPymSrc[0]->terbayar,
                        //         "sisa" => $tmpTerminPymSrc[0]->sisa,
                        //     );
                        // }

                        $iconpymTerbayar = "";
//                        if( round(floatval($tmpTerminPymSrc[0]->terbayar), 2) > 0 ){
//                            $pymTerbayar[$tmpTerminPymSrc[0]->id] = array(
//                                "id" => $tmpTerminPymSrc[0]->id,
//                                "project" => $row->harga,
//                                "tagihan" => $tmpTerminPymSrc[0]->tagihan,
//                                "terbayar" => $tmpTerminPymSrc[0]->terbayar,
//                                "terbayar_seharusnya" => $tmpTerminPymSrc[0]->terbayar/1.11,
//                                "sisa" => $tmpTerminPymSrc[0]->sisa,
//                            );
//                            $iconpymTerbayar = " (T>0)";
//                        }

                        $iconpymTerbayarLebihBesarDariProject = "";
                        #1 STEP 1 buang PPN dari TERMIN TERBAYAR
//                        if( round(floatval($tmpTerminPymSrc[0]->terbayar), 2) > $row->harga || round(floatval($tmpTerminPymSrc[0]->terbayar), 2) > 0){
                        if (round(floatval($tmpTerminPymSrc[0]->terbayar), 2) > $row->harga) {
                            $pymTerbayarLebihBesarDariProject[$tmpTerminPymSrc[0]->id] = array(
                                "id" => $tmpTerminPymSrc[0]->id,
                                "project" => $row->harga,
                                "tagihan" => $tmpTerminPymSrc[0]->tagihan,
                                "terbayar" => $tmpTerminPymSrc[0]->terbayar,
                                "terbayar_seharusnya" => $tmpTerminPymSrc[0]->terbayar / 1.11,
                                "sisa" => $tmpTerminPymSrc[0]->sisa,
                            );
//                            $iconpymTerbayarLebihBesarDariProject = " (T>H)";
                        }

//                        $iconpymSisa = "";
//                        #2 STEP 2 buang PPN dari sisa TERMIN
//                        if( round(floatval($tmpTerminPymSrc[0]->sisa), 2) > 0 && (round(floatval($tmpTerminPymSrc[0]->sisa), 2)+round(floatval($tmpTerminPymSrc[0]->terbayar), 2)) > $row->harga ){
////                        if( round(floatval($tmpTerminPymSrc[0]->sisa), 2) > 0 ){
//                            $pymSisaLebihBesarDariNol[$tmpTerminPymSrc[0]->id] = array(
//                                "id" => $tmpTerminPymSrc[0]->id,
//                                "project" => $row->harga,
//                                "tagihan" => $tmpTerminPymSrc[0]->tagihan,
//                                "terbayar" => $tmpTerminPymSrc[0]->terbayar,
//                                "sisa" => $tmpTerminPymSrc[0]->sisa,
//                                "sisa_seharusnya" => $tmpTerminPymSrc[0]->sisa/1.11,
//                            );
//                            $iconpymSisa = "(#)";
//                        }

                        $iconpymTagihan = "";
                        #3 STEP 3 buang PPN dari TAGIHAN TERMIN
                        if (round(floatval($tmpTerminPymSrc[0]->tagihan), 2) > 0) {
                            $pymTagihanLebihBesarDariNol[$tmpTerminPymSrc[0]->id] = array(
                                "id" => $tmpTerminPymSrc[0]->id,
                                "project" => $row->harga,
                                "tagihan" => $tmpTerminPymSrc[0]->tagihan,
                                "terbayar" => $tmpTerminPymSrc[0]->terbayar,
                                "sisa" => $tmpTerminPymSrc[0]->sisa,
                                "tagihan_seharusnya" => $tmpTerminPymSrc[0]->tagihan / 1.11,
                            );
//                            $iconpymTagihan = "(#)";
                        }

                        $view .= "<td style='background-color: skyblue; text-align: right;'>" . ($tmpTerminPymSrc[0]->id) . "</td>";

                        //nilai termin seharusnya
//                        $view .= "<td title='jika setting_termin(".number_format(round(floatval($total_termin_))).") > 100 maka termin_seharusnya(".number_format(round(floatval($termin_seharusnya))).") = setting_termin(".number_format(round(floatval($total_termin_))).")/1.11\n\nnamun jika termin tidak di setting maka termin_seharusnya(".number_format(round(floatval($termin_seharusnya))).") adalah nilai_project(".number_format(round(floatval($row->harga))).")' style='background-color: lime; text-align: right;'>".number_format($termin_seharusnya)."</td>";

                        if (round(floatval($tmpTerminPymSrc[0]->tagihan), 2) > round(floatval($termin_seharusnya), 2)) {
                            $view .= "<td title='tagihan (" . number_format($tmpTerminPymSrc[0]->tagihan * 1) . ") lebih BESAR dari termin seharusnya (" . number_format($termin_seharusnya) . ")' style='background-color: pink; text-align: right;'>" . number_format($tmpTerminPymSrc[0]->tagihan) . "</td>";
                        }
                        else {
                            if ($tmpTerminPymSrc[0]->tagihan < $termin_seharusnya) {
                                $view .= "<td title='tagihan lebih KECIL dari termin seharusnya' style='background-color: lightskyblue; text-align: right;'>" . number_format($tmpTerminPymSrc[0]->tagihan) . "</td>";
                            }
                            else {
                                $view .= "<td title='tagihan SAMA dengan termin seharusnya' style='background-color: aquamarine; text-align: right;'>" . number_format($tmpTerminPymSrc[0]->tagihan) . "</td>";
                            }
                        }

                        //sisa termin dan sisa seharusnya
                        $sisa_termin_seharusnya = 0;
                        if (round(floatval($tmpTerminPymSrc[0]->terbayar), 2) > round(floatval($termin_seharusnya), 2)) {
                            $sisa_termin_seharusnya = $row->harga - $tmpTerminPymSrc[0]->terbayar;
                        }
                        else {
                            $sisa_termin_seharusnya = $termin_seharusnya - $tmpTerminPymSrc[0]->terbayar;
                        }

                        $cssSisaTermin = "";
                        //mencari sisa termin dan terbayar yang tidak sama dengan nilai project nya
                        //terbayar + sisa harus sama dengan nilai_project
                        if (($tmpTerminPymSrc[0]->terbayar + $tmpTerminPymSrc[0]->sisa) > round(floatval($row->harga), 2)) {
                            $cssSisaTermin = "background-color: indianred;";
                        }

                        $sisa_termin_seharusnya_bg_color = round(floatval($tmpTerminPymSrc[0]->sisa), 2) != round(floatval($sisa_termin_seharusnya), 2) ? "background: red;" : "$cssSisaTermin";
                        $sisa_termin_seharusnya_bg_color = $cssSisaTermin;
                        $sisa_termin_seharusnya_title = round(floatval($tmpTerminPymSrc[0]->sisa), 2) != round(floatval($sisa_termin_seharusnya), 2) ? "sisa termin tidak sama dengan sisa seharusnya" : "";

                        // if( round(floatval($tmpTerminPymSrc[0]->sisa), 2) != round(floatval($sisa_termin_seharusnya), 2) ){
                        //     $varSisaTerminTidakSamaDenganSisaTerminSeharusnya[$tmpTerminPymSrc[0]->id] = array(
                        //         "id" => $tmpTerminPymSrc[0]->id,
                        //         "project" => $row->harga,
                        //         "tagihan_ori" => $tmpTerminPymSrc[0]->tagihan,
                        //         "tagihan" => $row->harga,
                        //         "terbayar" => $tmpTerminPymSrc[0]->terbayar,
                        //         "sisa" => $tmpTerminPymSrc[0]->sisa,
                        //         "sisa_seharusnya" => $sisa_termin_seharusnya,
                        //     );
                        // }

                        //SISA TERMIN ORI dari PAYMENT_SOURCE
                        $view .= "<td title='$sisa_termin_seharusnya_title' style='text-align: right;$sisa_termin_seharusnya_bg_color'>" . number_format($tmpTerminPymSrc[0]->sisa) . " $iconpymSisa</td>";

                        //sisa termin seharusnya
//                        $view .= "<td title='$sisa_termin_seharusnya_title\n\njika termin_terbayar(".number_format(round(floatval($tmpTerminPymSrc[0]->terbayar))).") > termin_seharusnya(".number_format($termin_seharusnya).")\nmaka sisa_termin_seharusnya(".number_format(round(floatval($sisa_termin_seharusnya))).") adalah nilai_project(".number_format(round(floatval($row->harga))).") - termin_terbayar(".number_format(round(floatval($tmpTerminPymSrc[0]->terbayar))).")\n\nnamun jika termin_terbayar(".number_format(round(floatval($tmpTerminPymSrc[0]->terbayar))).") < termin_seharusnya(".number_format($termin_seharusnya).")\nmaka sisa_termin_seharusnya(".number_format(round(floatval($sisa_termin_seharusnya))).") adalah termin_seharusnya(".number_format(round(floatval($termin_seharusnya))).") - termin_terbayar(".number_format(round(floatval($tmpTerminPymSrc[0]->terbayar))).")' titlex='$sisa_termin_seharusnya_title' style='text-align: right;$sisa_termin_seharusnya_bg_color'>".number_format($sisa_termin_seharusnya)."</td>";
                        //sisa termin dan sisa seharusnya

                        if (($row->harga * 1.11) < $tmpTerminPymSrc[0]->terbayar) {
                            $view .= "<td title='NILAI PROJECT PPN LEBIH KECIL DARI TERMIN TERBAYAR' style='background: mediumvioletred; text-align: right;'>" . number_format($tmpTerminPymSrc[0]->terbayar) . "$iconpymTerbayarLebihBesarDariProject$iconpymTerbayar</td>";
                            $varHargaProjectLebihKecilTerbayar[$row->id] = $row->nama;
                        }
                        else {
                            if (round(floatval($tmpTerminPymSrc[0]->terbayar), 2) > round(floatval($tmpTerminPymSrc[0]->tagihan), 2)) {
                                $terbayar_update = $tmpTerminPymSrc[0]->terbayar / 1.11;
                                $view .= "<td style='background: lightsalmon; text-align: right;'>" . number_format($tmpTerminPymSrc[0]->terbayar, 2) . "<br>" . number_format($terbayar_update) . "$iconpymTerbayarLebihBesarDariProject$iconpymTerbayar</td>";
                                // $idAllPaymentTermin[$tmpTerminPymSrc[0]->id] = array(
                                //     "id" => $tmpTerminPymSrc[0]->id,
                                //     "project" => $row->harga,
                                //     "tagihan_ori" => $tmpTerminPymSrc[0]->tagihan,
                                //     "tagihan" => $row->harga,
                                //     "terbayar" => $tmpTerminPymSrc[0]->terbayar,
                                //     "sisa" => $tmpTerminPymSrc[0]->sisa,
                                //     "terbayar_seharusnya" => $terbayar_update,
                                // );
                            }
                            else {
                                if ($termin_seharusnya < $tmpTerminPymSrc[0]->terbayar) {
                                    $view .= "<td title='NILAI TERBAYAR LEBIH BESAR DARI NILAI TERMIN SEHARUSNYA' style='background: violet; text-align: right;'>" . number_format($tmpTerminPymSrc[0]->terbayar) . "$iconpymTerbayarLebihBesarDariProject$iconpymTerbayar</td>";
                                }
                                else {
                                    if ($termin_seharusnya == $tmpTerminPymSrc[0]->terbayar) {
                                        $view .= "<td style='background: green; text-align: right;'>" . number_format($tmpTerminPymSrc[0]->terbayar) . "$iconpymTerbayarLebihBesarDariProject$iconpymTerbayar</td>";
                                    }
                                    else {
                                        $view .= "<td style='text-align: right;'>" . number_format($tmpTerminPymSrc[0]->terbayar) . "$iconpymTerbayarLebihBesarDariProject$iconpymTerbayar</td>";
                                    }
                                }
                            }
                        }
                    }
                    else {
                        $view .= "<td style='text-align: right;'>GAK ADA PAYMENT</td>";
                        $view .= "<td style='text-align: right;'>-</td>";
                        $view .= "<td style='text-align: right;'>-</td>";
                        $view .= "<td style='text-align: right;'>-</td>";
                        $view .= "<td style='text-align: right;'>-</td>";
                        $view .= "<td style='text-align: right;'>-</td>";
                    }

                    $view .= "<td style='text-align: right;'>" . number_format($row->status) . "</td>";
                    $view .= "<td style='text-align: right;'>" . number_format($row->trash) . "</td>";
                }
            }
            else {

                $this->db->where("transaksi_id", $row->transaksi_id);
                $transaksi_data_registry = $this->db->get("transaksi_data_registry")->result();
                foreach ($transaksi_data_registry as $reg) {
                    $tQuoteID = $reg->transaksi_id;

                    $main = blobDecode($reg->main);   // sesMain
                    $items3 = blobDecode($reg->items3); // termin
                    $items4 = blobDecode($reg->items4); // dp/um
                    $items5 = blobDecode($reg->items5); // garansi
                    $items7 = blobDecode($reg->items7); // info kontrak

                    $view .= "<td style='text-align: right;'>" . number_format($items3[0]['harga_project'], 2) . "</td>";
                    $view .= "<td style='text-align: right;'>" . number_format($items4[0]['harga'], 2) . "</td>";
                    $view .= "<td style='text-align: right;'>" . number_format($items5[0]['harga'], 2) . "</td>";

                    $total_termin_ = 0;
                    foreach ($items3 as $item) {
                        $total_termin_ += $item['harga'];
                    }

                    $view .= "<td style='text-align: right;'>" . number_format($total_termin_, 2) . "</td>";
//                    $view .= "<td style='background-color: black;text-align: right;'>".number_format($total_termin_ - $row->harga ,2)."</td>";

                    $termin_seharusnya = 0;
                    if ($total_termin_ > 100) {
                        $termin_seharusnya = $total_termin_ / 1.11;
                    }
                    else {
                        $termin_seharusnya = $row->harga;
                    }

                    $tr->setFilters(array());
                    $tr->addFilter("project_id='" . $row->id . "'");
                    $tr->addFilter("jenis='588so'");
                    $tr->addFilter("target_jenis='7499'");

                    $tmpTerminPymSrc = $tr->lookUpPayment()->result();

                    if (!empty($tmpTerminPymSrc)) {

                        if (round(floatval($termin_seharusnya), 2) != round(floatval($tmpTerminPymSrc[0]->tagihan), 2)) {
                            $tagihanTidakSamaDenganTerminSeharusnya[$tmpTerminPymSrc[0]->id] = array(
                                "id" => $tmpTerminPymSrc[0]->id,
                                "project" => $row->harga,
                                "tagihan" => $tmpTerminPymSrc[0]->tagihan,
                                "tagihan_seharusnya" => $termin_seharusnya,
                                "terbayar" => $tmpTerminPymSrc[0]->terbayar,
                                "sisa" => $tmpTerminPymSrc[0]->sisa,
                            );
                        }

                        $view .= "<td style='background-color: blue; text-align: right;'>" . ($tmpTerminPymSrc[0]->id) . "</td>";
                        //nilai termin seharusnya
//                        $view .= "<td style='background-color: lime; text-align: right;'>".number_format($termin_seharusnya,2)."</td>";

                        if (round(floatval($tmpTerminPymSrc[0]->tagihan), 2) > round(floatval($termin_seharusnya), 2)) {
                            $view .= "<td tagihan='" . ($tmpTerminPymSrc[0]->tagihan * 1) . "' termin_seharusnya='" . ($termin_seharusnya * 1) . "' title='tagihan (" . number_format($tmpTerminPymSrc[0]->tagihan * 1) . ") lebih BESAR dari termin seharusnya (" . number_format($termin_seharusnya) . ")' style='background-color: pink; text-align: right;'>" . number_format($tmpTerminPymSrc[0]->tagihan, 2) . "</td>";
                        }
                        else {
                            if ($tmpTerminPymSrc[0]->tagihan < $termin_seharusnya) {
                                $view .= "<td title='tagihan lebih KECIL dari termin seharusnya' style='background-color: lightskyblue; text-align: right;'>" . number_format($tmpTerminPymSrc[0]->tagihan, 2) . "</td>";
                            }
                            else {
                                $view .= "<td title='tagihan SAMA dengan termin seharusnya' style='background-color: aquamarine; text-align: right;'>" . number_format($tmpTerminPymSrc[0]->tagihan, 2) . "</td>";
                            }
                        }

                        //sisa termin dan sisa seharusnya
                        $sisa_termin_seharusnya = 0;
                        if (round(floatval($tmpTerminPymSrc[0]->terbayar), 2) > round(floatval($termin_seharusnya), 2)) {
                            $sisa_termin_seharusnya = $row->harga - $tmpTerminPymSrc[0]->terbayar;
                        }
                        else {
                            $sisa_termin_seharusnya = $termin_seharusnya - $tmpTerminPymSrc[0]->terbayar;
                        }

                        $sisa_termin_seharusnya_bg_color = round(floatval($tmpTerminPymSrc[0]->sisa), 2) != round(floatval($sisa_termin_seharusnya), 2) ? "background: red;" : "";
                        $sisa_termin_seharusnya_title = round(floatval($tmpTerminPymSrc[0]->sisa), 2) != round(floatval($sisa_termin_seharusnya), 2) ? "sisa termin tidak sama dengan sisa seharusnya" : "";

                        if (round(floatval($tmpTerminPymSrc[0]->sisa), 2) != round(floatval($sisa_termin_seharusnya), 2)) {
                            $varSisaTerminTidakSamaDenganSisaTerminSeharusnya[$tmpTerminPymSrc[0]->id] = array(
                                "id" => $tmpTerminPymSrc[0]->id,
                                "project" => $row->harga,
                                "tagihan_ori" => $tmpTerminPymSrc[0]->tagihan,
                                "tagihan" => $row->harga,
                                "terbayar" => $tmpTerminPymSrc[0]->terbayar,
                                "sisa" => $tmpTerminPymSrc[0]->sisa,
                                "sisa_seharusnya" => $sisa_termin_seharusnya,
                            );
                        }

                        //sisa pym source
                        $view .= "<td title='$sisa_termin_seharusnya_title' style='text-align: right;$sisa_termin_seharusnya_bg_color'>" . number_format($tmpTerminPymSrc[0]->sisa, 2) . "</td>";
                        //sisa termin seharusnya
//                        $view .= "<td title='$sisa_termin_seharusnya_title' style='text-align: right;$sisa_termin_seharusnya_bg_color'>".number_format( $sisa_termin_seharusnya ,2)."</td>";
                        //sisa termin dan sisa seharusnya

                        if (($row->harga * 1.11) < $tmpTerminPymSrc[0]->terbayar) {
                            $view .= "<td style='background: mediumvioletred; text-align: right;'>" . number_format($tmpTerminPymSrc[0]->terbayar, 2) . "</td>";
                            $varHargaProjectLebihKecilTerbayar[$row->id] = $row->nama;
                        }
                        else {
                            if ($termin_seharusnya < $tmpTerminPymSrc[0]->terbayar) {
                                $view .= "<td title='NILAI TERBAYAR LEBIH BESAR DARI NILAI TERMIN SEHARUSNYA' style='background: violet; text-align: right;'>" . number_format($tmpTerminPymSrc[0]->terbayar, 2) . "</td>";
                                $varTerbayarLebihBesarDariTerminSeharusnya[$row->id] = $row->nama;
                            }
                            else {
                                if (round(floatval($tmpTerminPymSrc[0]->terbayar), 2) > round(floatval($tmpTerminPymSrc[0]->tagihan), 2)) {
                                    $terbayar_update = $tmpTerminPymSrc[0]->terbayar / 1.11;
                                    $view .= "<td style='background: lightsalmon; text-align: right;'>" . number_format($tmpTerminPymSrc[0]->terbayar, 2) . "<br>" . number_format($terbayar_update, 2) . "</td>";
                                    $varTerbayarLebihBesarDariTerminSeharusnya[$row->id] = $row->nama;
                                    // $idAllPaymentTermin[$tmpTerminPymSrc[0]->id] = array(
                                    //     "id" => $tmpTerminPymSrc[0]->id,
                                    //     "project" => $row->harga,
                                    //     "tagihan_ori" => $tmpTerminPymSrc[0]->tagihan,
                                    //     "tagihan" => $row->harga,
                                    //     "terbayar" => $tmpTerminPymSrc[0]->terbayar,
                                    //     "sisa" => $tmpTerminPymSrc[0]->sisa,
                                    //     "terbayar_seharusnya" => $terbayar_update,
                                    // );
                                }
                                else {
                                    if ($termin_seharusnya == $tmpTerminPymSrc[0]->terbayar) {
                                        $view .= "<td style='background: green; text-align: right;'>" . number_format($tmpTerminPymSrc[0]->terbayar, 2) . "</td>";
                                        $varTerbayarSamaTerminSeharusnya[$row->id] = $row->nama;
                                    }
                                    else {
                                        $view .= "<td style='text-align: right;'>" . number_format($tmpTerminPymSrc[0]->terbayar, 2) . "</td>";
                                    }
                                }
                            }
                        }
                    }
                    else {
                        $view .= "<td style='text-align: center;color: red;'>BELUM QUOT<br>NO PAYMENT</td>";
//                        $view .= "<td style='text-align: right;'>-</td>";
                        $view .= "<td style='text-align: right;'>-</td>";
                        $view .= "<td style='text-align: right;'>-</td>";
//                        $view .= "<td style='text-align: right;'>-</td>";
                        $view .= "<td style='text-align: right;'>-</td>";
                    }
                    $view .= "<td style='text-align: right;'>" . number_format($row->status) . "</td>";
                    $view .= "<td style='text-align: right;'>" . number_format($row->trash) . "</td>";
                }
            }

            $view .= "</tr>";

            //=====================================================================
            //=====================================================================

            $paymentTermin[] = $tmpTermin;

        }

        $view .= "</tbody>";
        $view .= "</table>";


//        $belum_bayar = array();
//        foreach($paymentTermin as $row1){
//            if($row1['terbayar'] == 0 && $row1['tagihan'] > 0 && $row1['payment_id'] > 0){
//                //salah nilai tagihan
//                if($row1['check'] <> 1000){
//                    $belum_bayar[] = $row1;
//                }
//            }
//        }


//        cekMerah("TERBAYAR > DARI TERMIN SEHARUSNYA");
//        arrPrint($varTerbayarLebihBesarDariTerminSeharusnya);

//        cekMerah("TERBAYAR == DARI TERMIN SEHARUSNYA");
//        arrPrint($varTerbayarSamaTerminSeharusnya);

//        cekMerah("NILAI PROJECT < DARI TERMIN TERBAYAR");
//        arrPrint($varHargaProjectLebihKecilTerbayar);

//        cekMerah("SEMUA PYM_SOURCE TERMIN");
//        arrPrint( count($idAllPaymentTermin) );
//        arrPrint( $idAllPaymentTermin );

        $this->db->trans_start();

//        arrPrint("total payment: " . count($paymentTermin));

//        $fixTagihan = array_filter($paymentTermin, function($item) {
//            return ($item['check'] < -1000 || $item['check'] > 1000) && $item['terbayar'] == 0 && $item['tagihan'] > 0;
//        });
//
//        if(!empty($fixTagihan)){
//            foreach($fixTagihan as $tFix){
//                $where = array(
//                    "id" => $tFix["payment_id"]
//                );
//                $data = array(
//                    "tagihan" => $tFix["tagihan_seharusnya"],
//                    "ppn" => $tFix["tagihan_seharusnya"]*0.11
//                );
//                $udp = $tr->updatePaymentSrc($where, $data);
////                cekHere("updated: " . $udp);
////                showLast_query("hijau");
//            }
//        }
//        arrPrint("fixTagihan: " . count($fixTagihan));
//        arrPrint($fixTagihan);
//        arrPrint($paymentTermin);

        $fixTagihan2 = array_filter($paymentTermin, function ($item) {
            return $item['tagihan'] > $item['harga'];
        });

        if (!empty($fixTagihan2)) {
            foreach ($fixTagihan2 as $tFix) {
                $where = array(
                    "id" => $tFix["payment_id"]
                );
                $data = array(
                    "tagihan" => $tFix["tagihan_seharusnya"],
                    "sisa" => $tFix["tagihan_seharusnya"] - $tFix["terbayar"] < 0 ? 0 : $tFix["tagihan_seharusnya"] - $tFix["terbayar"],
                    "ppn" => $tFix["tagihan_seharusnya"] * 0.11
                );
                $udp = $tr->updatePaymentSrc($where, $data);
                cekUngu("terbayar: " . number_format($tFix["terbayar"]));
                cekHere("updated: " . $udp);
                showLast_query("hijau");
            }
        }

//        arrPrint("fixTagihan2: " . count($fixTagihan2));
//        arrPrint($fixTagihan2);
//        arrPrint($paymentTermin);

        if (!empty($pymTerbayarLebihBesarDariProject)) {
            //indikasi pembayaran include PPN di temin, harusnya tanpa PPN jadi maksimah senilai PROJECT
//            cekMerah("PYM TERBAYAR LEBIH BESAR DARI NILAI PROJECT");
//            arrPrint( count($pymTerbayarLebihBesarDariProject) );
            // arrPrint($pymTerbayarLebihBesarDariProject);
//            foreach($pymTerbayarLebihBesarDariProject as $idPyms => $datas){
//                $where = array(
//                    "id" => $datas["id"]
//                );
//                $data = array(
//                    "terbayar" => $datas["terbayar_seharusnya"]
//                );
//                $udp = $tr->updatePaymentSrc($where, $data);
//            }
        }
        if (!empty($pymSisaLebihBesarDariNol)) {
//            cekMerah("SISA TERMIN > 0");
//            arrPrint( count($pymSisaLebihBesarDariNol) );
            // arrPrint($pymSisaLebihBesarDariNol);
//            foreach($pymSisaLebihBesarDariNol as $idPyms => $datas){
//                $where = array(
//                    "id" => $datas["id"]
//                );
//                $data = array(
//                    "sisa" => $datas["sisa_seharusnya"]
//                );
//                $udp = $tr->updatePaymentSrc($where, $data);
//            }
        }
        if (!empty($pymTagihanLebihBesarDariNol)) {
//            cekMerah("SISA TAGIHAN > 0");
//            arrPrint( count($pymTagihanLebihBesarDariNol) );
            // arrPrint($pymSisaLebihBesarDariNol);
//            foreach($pymTagihanLebihBesarDariNol as $idPyms => $datas){
//                $where = array(
//                    "id" => $datas["id"]
//                );
//                $data = array(
//                    "tagihan" => $datas["tagihan_seharusnya"]
//                );
//                $udp = $tr->updatePaymentSrc($where, $data);
//            }
        }
        if (!empty($idAllPaymentTermin)) {
//            cekMerah("SISA TERMIN != DENGAN SISA TERMIN SEHARUSNYA");
//            arrPrint( count($idAllPaymentTermin) );
            // arrPrint($idAllPaymentTermin);
//            foreach($idAllPaymentTermin as $idPyms => $datas){
//                $where = array(
//                    "id" => $datas["id"]
//                );
//                $data = array(
//                    "terbayar" => $datas["terbayar_seharusnya"]
//                );
//                $udp = $tr->updatePaymentSrc($where, $data);
//            }
        }
        if (!empty($varSisaTerminTidakSamaDenganSisaTerminSeharusnya)) {
//            cekMerah("SISA TERMIN != DENGAN SISA TERMIN SEHARUSNYA");
//            arrPrint( count($varSisaTerminTidakSamaDenganSisaTerminSeharusnya) );
            // arrPrint($varSisaTerminTidakSamaDenganSisaTerminSeharusnya);
//            foreach($varSisaTerminTidakSamaDenganSisaTerminSeharusnya as $idPyms => $datas){
//                $where = array(
//                    "id" => $datas["id"]
//                );
//                $data = array(
//                    "sisa" => $datas["sisa_seharusnya"]
//                );
//                $udp = $tr->updatePaymentSrc($where, $data);
//            }
        }
        if (!empty($tagihanTidakSamaDenganTerminSeharusnya)) {
//            cekMerah("TAGIHAN TERMIN != TAGIHAN TERMIN SEHARUSNYA");
//            arrPrint( count($tagihanTidakSamaDenganTerminSeharusnya) );
            // arrPrint($tagihanTidakSamaDenganTerminSeharusnya);
//            foreach($tagihanTidakSamaDenganTerminSeharusnya as $idPyms => $datas){
//                $where = array(
//                    "id" => $datas["id"]
//                );
//                $data = array(
//                    "tagihan" => $datas["tagihan_seharusnya"]
//                );
//                $udp = $tr->updatePaymentSrc($where, $data);
//            }
        }

//        arrPrint($paymentTermin);
        echo $view;

        matiHere("BELUM COMMIT DULU CEK LINE: " . __LINE__);
        $commit = $this->db->trans_complete();

        cekHijau("commit: " . $commit);

    }

}