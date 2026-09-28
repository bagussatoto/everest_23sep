<?php

class RouteMsg extends CI_Controller
{
//    public function __construct()
//    {
//        parent::__construct();
//        $this->selectedField = array(
//            "jumlah", "nama"
//        );
//    }
//
//    public function generate_data()
//    {
//
////        $mode = "1";//FG
////        $mode="2";//supplies
//        $mode="3";//aset
//
////        $mode = "2";//default biar gak dieksekusi
//        $mode_data = "";
//        switch ($mode) {
//            case "1" :
//                $this->load->model("Mdls/MdlLockerStock");
//                $this->load->model("Mdls/MdlLockerStockCache");
//                $mdlCache = "MdlLockerStock";
//                $l = new MdlLockerStock();
//                $m = new MdlLockerStockCache();
//                $mode_data = "produk";
//                break;
//            case "2":
//                $this->load->model("Mdls/MdlLockerStockSupplies");
//                $this->load->model("Mdls/MdlLockerStockSuppliesCache");
//                $mdlCache = "MdlLockerStockSupplies";
//                $l = new MdlLockerStockSupplies();
//                $m = new MdlLockerStockSuppliesCache();
//                $mode_data = "supplies";
//                break;
//            case"3":
//                matiHEre("belum di apa apain");
//                break;
//            default:
//                matiHere("mode not found on line :: " . __LINE__ . " :: " . __FILE__);
//                break;
//
//        }
////        $this->load->model("Mdls/MdlLockerStockSupplies");
////        $l = new MdlLockerStockSupplies();
//
//
//        switch ($mode) {
//            case "1":
//                $l->addFilter("jenis='produk'");
//                break;
//            case "2":
//                $l->addFilter("jenis='supplies'");
//                break;
//            case "3":
//                $l->addFilter("jenis='aktiva'");
//                break;
//        }
//        $l->addFilter("jenis_locker='stock'");
//        $l->addFilter("state='active'");
//        $tmp = $l->lookUpAll()->result();
//        cekLime($this->db->last_query());
//
//
//        $h = new $mdlCache();
//        $h->setfilters(array());
////        $h->addFilter("jenis in('produk','supplies','aktiva','produk_rakitan')");
////        $h->addFilter("jenis='produk'");
//        switch ($mode) {
//            case "1":
//                $h->addFilter("jenis='produk'");
//                break;
//            case "2":
//                $h->addFilter("jenis='supplies'");
//                break;
//            case "3":
//                $h->addFilter("jenis='aktiva'");
//                break;
//        }
////        $h->addFilter("jenis='supplies'");
//        $h->addFilter("jenis_locker='stock'");
//        $h->addFilter("state='hold'");
//        $h->addFilter("jumlah > 0");
////        $h->addFilter("oleh_id > 0");
////        $h->addFilter("trash ='0'");
//        $tmp2 = $h->lookUpAll()->result();
//        cekLime($this->db->last_query());
//
//        if (sizeof($tmp) > 0) {
//            $tmpProds = array();
//            foreach ($tmp as $tmp0) {
//                $tmpData = array();
//                foreach ($this->selectedField as $key) {
//                    $tmpData[$key] = $tmp0->$key;
//                }
//                $tmpProds[$tmp0->cabang_id][$tmp0->gudang_id][$tmp0->produk_id] = $tmpData;
//            }
//        }
//        $tmpProds2 = array();
//        if (sizeof($tmp2) > 0) {
//
//            foreach ($tmp2 as $tmp20) {
//                $tmpData2 = array();
//                foreach ($this->selectedField as $key) {
//                    $tmpData2[$key] = $tmp20->$key;
//                }
//                $tmpProds2[$tmp20->cabang_id][$tmp20->gudang_id][$tmp20->produk_id] = $tmpData2;
//            }
//        }
//
//        $this->db->trans_start();
//        $total_data = 0;
//        if (sizeof($tmpProds) > 0) {
////            $this->load->model("Mdls/MdlLockerStockCache");
////
////            $m = new MdlLockerStockCache();
////            $this->load->model("Mdls/MdlLockerStockSuppliesCache");
////            $m = new MdlLockerStockSuppliesCache();
//
//            foreach ($tmpProds as $cID => $tempDataCID) {
//                foreach ($tempDataCID as $gID => $gidData) {
//                    foreach ($gidData as $pID => $pidData) {
//                        $qty = isset($tmpProds2[$cID][$gID][$pID]['jumlah']) ? $tmpProds2[$cID][$gID][$pID]['jumlah'] + $pidData['jumlah'] : $pidData['jumlah'];
//                        $nama = $pidData['nama'];
//                        $inserData = array(
//                            "extern_id" => $pID,
//                            "extern_nama" => $nama,
//                            "cabang_id" => $cID,
//                            "gudang_id" => $gID,
//                            "qty_debet" => $qty,
////                            "jenis" =>"produk",
//                            "jenis" => $mode_data,
//                        );
//                        $insertID = $m->addData($inserData, $m->getTableName()) or die(lgShowError("Gagal menulis data", __FILE__));
//                        if ($insertID) {
//                            $total_data++;
//                        }
//                        cekHitam($this->db->last_query());
//
//                    }
//                }
//            }
//        }
//
////arrPrint($tmpProds2);
////        matiHEre("hoopppp comatcomit total row data ditulis " . $total_data);
//        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
//        cekMerah("insert selesai total row " . $total_data);
//    }
//
//    public function generate_trID()
//    {
//        $this->load->model("Mdls/MdlReaderMutasi");
//        $this->load->model("Mdls/MdlPembantuInjector");
//        $m = new MdlReaderMutasi();
//        $i = new MdlPembantuInjector();
//        arrPrint($i->getTableName());
//        $tmp = $m->lookUpAll()->result();
////        arrPrint($tmp);
////        display_errors = on;
//        $i->setSortBy(
//            array(
//                "kolom" => "id",
//                "mode"  => "ASC",
//            )
//        );
//        $data = array();
//        foreach ($tmp as $tmp0) {
//            $data[$tmp0->transaksi_id] = $tmp0->transaksi_no;
//        }
//        $this->db->trans_start();
////        $tmpData = array();
//        foreach ($data as $externID => $exterNama) {
////            cekBiru();
////            $tmpData[] = array("extern_id" =>$externID,"nama" =>"$exterNama");
//            $inserData = array(
//                "extern_id" => $externID,
//                "nama" => $exterNama,
//            );
////            cekOrange();
//            $insertID = $i->addData($inserData, $i->getTableName()) or die("Gagal menulis data". __FILE__);
////cekMerah();
//            if ($insertID) {
//                $total_data++;
//            }
//            cekHitam($this->db->last_query());
//        }
//        matiHEre("hoopppp comatcomit total row data ditulis " . $total_data);
//        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
//        arrPrint($tmpData);
//    }
//
//    public function insert_move(){
//        $this->load->model("Mdls/MdlPembantuInjector");
//        $this->load->model("MdlTransaksi");
//        $a = new MdlPembantuInjector();
//        $a->setSortBy(
//            array(
//                "kolom" => "id",
//                "mode"  => "ASC",
//            )
//        );
//        $this->db->limit(1);
//        $a->addFilter("cli='0'");
//        $tmp =$a->lookUpAll()->result();
//        $trID = $tmp[0]->extern_id;
//        $tr = New MdlTransaksi();
//
//        $trTmp = $tr->lookupByID($trID)->result();
//
////arrPrint($trTmp);
////        matiHEre($trID);
//        if (sizeof($trTmp) > 0) {
//            $kolom = array(
//                "trID"          => "id",
//                "jenisTr"       => "jenis",
//                "jenisTrMaster" => "jenis_master",
//                "jenisTrTop"    => "jenis_top",
//                "nomer"         => "nomer",
//                "nomerTop"      => "nomer_top",
//                "dtime"         => "dtime",
//                "fulldate"      => "fulldate",
//                "stepNumber"    => "step_number",
//                "indexRegistry" => "indexing_registry",
//                "olehID"        => "oleh_id",
//                "olehNama"      => "oleh_nama",
//            );
//            $arrKolomTrans = array();
//            foreach ($kolom as $key => $val) {
//                $arrKolomTrans[$key] = isset($trTmp[0]->$val) ? $trTmp[0]->$val : NULL;
//            }
//
//            $reg = New MdlTransaksi();
//            $key = "indexRegistry";
//            $index_reg = blobDecode($arrKolomTrans[$key]);
////            arrPrint($index_reg);
////            matiHEre();
//
//            $reg->setFilters(array());
//            if(is_array($index_reg) && sizeof($index_reg)>0){
//                $reg->addFilter("id in ('" . implode("','", $index_reg) . "')");
//            }
//            else{
//                $reg->addFilter("transaksi_id='$trID'");
//            }
//
//            $regTmp = $reg->lookupRegistries()->result();
//            $registryGates = array();
//            foreach ($regTmp as $regSpec) {
//                $registryGates[$regSpec->param] = blobDecode($regSpec->values);
//            }
//
//
////            arrprint($arrKolomTrans);
//            $jenisTr = $arrKolomTrans['jenisTr'];
//            $jenisTrMaster = $arrKolomTrans['jenisTrMaster'];
//            $fulldate = $arrKolomTrans['fulldate'];
//            $dtime = $arrKolomTrans['dtime'];
//            $stepNumber = $arrKolomTrans['stepNumber'];
//            $insertNum = $tmpNomorNota = $arrKolomTrans['nomer'];
//            $olehNama = $arrKolomTrans['olehNama'];
//            $insertID = $transaksiID = $arrKolomTrans['trID'];
//
//
//            //region BUILD TABEL DATABASE OTOMATIS
//            $cliComponent = "components";
//            $buildTablesDetail = isset($this->config->item('heTransaksi_core')[$jenisTrMaster][$cliComponent][$stepNumber]['detail']) ? $this->config->item('heTransaksi_core')[$jenisTrMaster][$cliComponent][$stepNumber]['detail'] : array();
////            arrPrint($buildTablesDetail);
//            if (sizeof($buildTablesDetail) > 0) {
//                foreach ($buildTablesDetail as $buildTablesDetail_specs) {
//                    $srcGateName = $buildTablesDetail_specs['srcGateName'];
//                    $srcRawGateName = $buildTablesDetail_specs['srcRawGateName'];
//                    foreach ($registryGates[$srcGateName] as $itemSpec) {
//
//                        $mdlName = $buildTablesDetail_specs['comName'];
//                        if (substr($mdlName, 0, 1) == "{") {
//                            $mdlName = trim($mdlName, "{");
//                            $mdlName = trim($mdlName, "}");
//                            $mdlName = str_replace($mdlName, $itemSpec[$mdlName], $mdlName);
//                        }
////                        cekHere($mdlName . " == " . $srcGateName);
//                        $mdlName = "Com" . $mdlName;
//                        $this->load->model("Coms/" . $mdlName);
//                        $m = new $mdlName();
//                        if (method_exists($m, "getTableNameMaster")) {
//                            if (sizeof($m->getTableNameMaster())) {
//                                $m->buildTables($buildTablesDetail_specs);
//                            }
//                        }
//                    }
//                }
//            }
//            else {
//                cekMerah(":: TIDAK ADA CONFIG cliComponent");
//            }
//            //endregion
////mati_disini();
//
//            $this->db->trans_start();
//
//
//            //region ----------subcomponents by cli
//            //<editor-fold desc="----------subcomponents by cli">
//
//            $paramPatchers = $this->config->item('heTransaksi_paramPatchers') != null ? $this->config->item('heTransaksi_paramPatchers') : array();
//            $paramForceFillers = $this->config->item('heTransaksi_paramForceFillers') != null ? $this->config->item('heTransaksi_paramForceFillers') : array();
//
////            $iterator = isset($this->config->item('heTransaksi_core')[$jenisTrMaster][$cliComponent][$stepNumber]['detail']) ? $this->config->item('heTransaksi_core')[$jenisTrMaster][$cliComponent][$stepNumber]['detail'] : array();
//            if (isset($this->config->item('heTransaksi_core')[$jenisTrMaster]['relativeComponets']) && $this->config->item('heTransaksi_core')[$jenisTrMaster]['relativeComponets'] == true) {
//                $iterator = isset($registryGates['revert']['jurnal']['detail']) ? $registryGates['revert']['jurnal']['detail'] : array();
//                $revertedTarget = $registryGates['main']['pihakExternID'];
//            }
//            else {
//                $iterator = isset($this->config->item('heTransaksi_core')[$jenisTrMaster][$cliComponent][$stepNumber]['detail']) ? $this->config->item('heTransaksi_core')[$jenisTrMaster][$cliComponent][$stepNumber]['detail'] : array();
//                $revertedTarget = "";
//            }
//
//
//            if (sizeof($iterator) > 0) {
//                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
//                $filterNeeded = false;
//
//                $arrRekeningLoop = array();
//
////                if (in_array($mdlName, $compValidators)) {//perlu validasi filter
////                    $filterNeeded = true;
////                }
//                foreach ($iterator as $cCtr => $tComSpec) {
//                    $comName = $tComSpec['comName'];
////                    mati_disini($comName);
//                    $srcGateName = $tComSpec['srcGateName'];
//                    $srcRawGateName = $tComSpec['srcRawGateName'];
//
//                    echo "sub-component: $comName, $srcGateName, initializing values <br>";
//
//                    $tmpOutParams[$cCtr] = array();
//                    foreach ($registryGates[$srcGateName] as $id => $dSpec) {
//                        $comName = $tComSpec['comName'];
//                        if (substr($comName, 0, 1) == "{") {
//                            $comName = trim($comName, "{");
//                            $comName = trim($comName, "}");
//                            $comName = str_replace($comName, $registryGates[$srcGateName][$id][$comName], $comName);
//                            $tComSpec['comName'] = $comName;
//                            $iterator[$cCtr]['comName'] = $comName;
//                        }
//                        $filterNeeded = false;
//                        $mdlName = "Com" . ucfirst($comName);
//                        if (in_array($mdlName, $compValidators)) {//perlu validasi filter
//                            $filterNeeded = true;
//                        }
//
//
//                        $subParams = array();
//                        if (isset($tComSpec['loop'])) {
//                            foreach ($tComSpec['loop'] as $key => $value) {
//                                if (substr($key, 0, 1) == "{") {
//                                    $key = trim($key, "{");
//                                    $key = trim($key, "}");
//                                    $key = str_replace($key, $registryGates[$srcGateName][$id][$key], $key);
//                                }
//
//
//                                $realValue = makeValue($value, $registryGates[$srcGateName][$id], $registryGates[$srcGateName][$id], 0);
//                                $subParams['loop'][$key] = $realValue;
//
//                                // =================== =================== ===================
//                                if (!isset($arrRekeningLoop[$dSpec[$tComSpec['static']['cabang_id']]][$key])) {
//                                    $arrRekeningLoop[$dSpec[$tComSpec['static']['cabang_id']]][$key] = 0;
//                                }
//                                $arrRekeningLoop[$dSpec[$tComSpec['static']['cabang_id']]][$key] += $realValue;
//                                if ($realValue != 0) {
//                                    cekUngu(":: cetak loop $key => $realValue ::");
//                                }
//
//                                if ($filterNeeded) {
//                                    if ($subParams['loop'][$key] == 0) {
//                                        unset($subParams['loop'][$key]);
//
//                                        // =================== =================== ===================
//                                    }
//                                }
//                            }
//                        }
//                        if (isset($tComSpec['static'])) {
//                            foreach ($tComSpec['static'] as $key => $value) {
//
//                                $realValue = makeValue($value, $registryGates[$srcGateName][$id], $registryGates[$srcGateName][$id], 0);
//                                $subParams['static'][$key] = $realValue;
////                                cekKuning("STATIC: $key diisi dengan $realValue");
//                            }
//                            if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
//                                foreach ($paramPatchers[$comName] as $k => $v) {
//                                    if (!isset($subParams['static'][$k])) {
//                                        $subParams['static'][$k] = isset($$v) ? $$v : "_v";
//                                        cekOrange("fill :: $comName :: $k ($v) => " . $subParams['static'][$k]);
//                                    }
//                                }
//                            }
//                            if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
//
//                                $jenis = $registryGates['main']['jenis'];
//                                foreach ($paramForceFillers[$comName] as $k => $v) {
//                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
//                                    cekOrange("fillforce :: $comName :: $k ($v) => " . $subParams['static'][$k]);
//                                }
//                            }
//                            $subParams['static']["fulldate"] = $fulldate;
//                            $subParams['static']["dtime"] = $dtime;
//                            $subParams['static']["keterangan"] = $this->config->item('heTransaksi_ui')[$jenisTrMaster]['steps'][$stepNumber]['label'] . " nomor " . $tmpNomorNota . " oleh " . $olehNama;
//                            if (strlen($revertedTarget) > 1) {
//                                $subParams['static']['reverted_target'] = $revertedTarget;
//                            }
//                        }
////arrPrint($subParams);
//                        if (sizeof($subParams) > 0) {
//                            if ($filterNeeded) {
//                                if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
//                                    $tmpOutParams[$cCtr][] = $subParams;
//                                }
//                            }
//                            else {
//
//                                $tmpOutParams[$cCtr][] = $subParams;
//                            }
//                        }
//                    }
//                }
//                cekPink(":: cetak loop ::");
//                arrPrint($arrRekeningLoop);
////                mati_disini($comName);
//                $it = 0;
//                foreach ($iterator as $cCtr => $tComSpec) {
//                    $it++;
//                    $comName = $tComSpec['comName'];
//                    $srcGateName = $tComSpec['srcGateName'];
//                    $srcRawGateName = $tComSpec['srcRawGateName'];
//
//                    echo "sub component #$it: $comName, sending values <br>";
//
//                    $mdlName = "Com" . ucfirst($comName);
//                    $this->load->model("Coms/" . $mdlName);
//                    $m = new $mdlName();
//
////arrPrint($tmpOutParams[$cCtr]);
////matiHEre();
//                    if (sizeof($tmpOutParams[$cCtr]) > 0) {
//                        $tobeExecuted = true;
//                    }
//                    else {
//                        $tobeExecuted = false;
//                    }
//
//
//                    if ($tobeExecuted) {
//                        $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $jenisTrMaster . "/" . __FUNCTION__ . "/" . __LINE__);
//                        $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $jenisTrMaster . "/" . __FUNCTION__ . "/" . __LINE__);
//                    }
//                    else {
//                        cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
//                    }
//                }
//
//
//                $pakai_ini = 0;
//                if ($pakai_ini == 1) {
//                    // region baca jurnal rekening besar
//                    $jn = New ComJurnal();
//                    $jn->addFilter("transaksi_id='$transaksiID'");
//                    $jnTmp = $jn->lookupAll()->result();
////                    arrPrint($jnTmp);
//                    $arrJurnal = array();
//                    if (sizeof($jnTmp) > 0) {
//                        foreach ($jnTmp as $ii => $spec) {
//                            $defPosition = detectRekDefaultPosition($spec->rekening);
//                            switch ($defPosition) {
//                                case "debet":
//                                    $arrJurnal[$spec->cabang_id][$spec->rekening] = $spec->debet > 0 ? $spec->debet : $spec->kredit * -1;
//                                    break;
//                                case "kredit":
//                                    $arrJurnal[$spec->cabang_id][$spec->rekening] = $spec->kredit > 0 ? $spec->kredit : $spec->debet * -1;
//                                    break;
//                                default:
//                                    mati_disini("tidak menemukan default posisi rekening...");
//                                    break;
//                            }
//                        }
//                    }
//                    // endregion
//
//                    cekHere("cetak array jurnal");
//                    arrPrint($arrJurnal);
//
//                    cekHere("cetak rek loop");
//                    arrPrint($arrRekeningLoop);
//
//
//                    if (sizeof($arrJurnal) > 0) {
//                        if (sizeof($arrRekeningLoop) > 0) {
//                            foreach ($arrRekeningLoop as $cabang_id => $loopSpec) {
//                                foreach ($loopSpec as $rekening => $rekValue) {
//                                    if (array_key_exists($rekening, $arrJurnal[$cabang_id])) {
//                                        if (floor($rekValue) != floor($arrJurnal[$cabang_id][$rekening])) {
//                                            mati_disini("nilai $rekening, jurnal: " . floor($arrJurnal[$cabang_id][$rekening]) . ", akumulasi pembantu: " . floor($rekValue));
//                                        }
//                                        else {
//                                            cekHijau(":: COCOK ::");
//                                        }
//                                    }
//                                }
//                            }
//                        }
//                    }
//
//
//                }
//
//
//            }
//            else {
//                cekMerah("subcomponents is not set");
//            }
//
//
//            //</editor-fold>
//            //endregion
//
//
//            //region update status sudah dirunning by cli
//            $a = New MdlPembantuInjector();
//            $a->setFilters(array());
//            $where = array(
//                "extern_id" => $transaksiID,
//            );
//            $updateData = array(
//                "cli" => 1,
//            );
//            $a->updateData($where, $updateData);
//            cekHere($this->db->last_query());
//            //endregion
//
//            $stopDate = dtimeNow();
//
//            // region menulis ke tabel log time cli
////            $cl = New MdlCliLogTime();
////            $arrCliData = array(
////                "web"          => "cli",
////                "judul"        => "CLI $insertNum $addJudul",
////                "waktu_start"  => $startDate,
////                "waktu_stop"   => $stopDate,
////                "waktu"        => timeDiff($startDate, $stopDate),
////                "transaksi_id" => $insertID,
////                "nomer"        => $insertNum,
////                "jenis"        => $jenisTr,
////                "jenis_master" => $jenisTrMaster,
////            );
////            $rslt = $cl->addData($arrCliData);
////            cekHere($this->db->last_query());
//            // endregion
//
//
//            if ($getTrID > 0) {
//                mati_disini("...cek MANUAL cli transaksi... rekening pembantu masuk disini (component detail)<br>start: $startDate<br>stop: $stopDate<br>butuh waktu: " . timeDiff($startDate, $stopDate));
//            }
//
//
//            cekHijau("...tes cli transaksi... rekening pembantu masuk disini (component detail)<br>start: $startDate<br>stop: $stopDate<br>butuh waktu: " . timeDiff($startDate, $stopDate));
////            mati_disini("...tes cli transaksi... rekening pembantu masuk disini (component detail)<br>start: $startDate<br>stop: $stopDate<br>butuh waktu: " . timeDiff($startDate, $stopDate));
//            $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
//        }
//        else {
//            $stopDate = dtimeNow();
//            cekMerah(":: TIDAK ADA yang perlu di-CLI-kan ::
//                    <br>start: $startDate<br>stop: $stopDate<br>butuh waktu: " . timeDiff($startDate, $stopDate));
//        }
//    }

    public function cekSsl(){
//        $url = "https://demo.mayagrahakencana.com";
//        $this->load->model("Mdls/MdlRoute");
//        $r = new MdlRoute();
        $this->load->helper("he_lib_route");
        $listedDomainMaya = array(
            "demo.mayagrahakencana.com",
            "coba.mayagrahakencana.com",
            "san.mayagrahakencana.com",
            "spm.mayagrahakencana.com",
            "teguh.mayagrahakencana.com",
//            "sbm.mayagrahakencana.com",
            "cdn.mayagrahakencana.com",
            "mayanet.mayagrahakencana.com",
            "majumapan88.com",
            "malioboro.co.id",
            "vitocafe.com",
            "pos.vitocafe.com",

        );

        $tmpList = array();
        foreach($listedDomainMaya as $sub){
            $url = "https://".$sub;

            $orignal_parse = parse_url($url, PHP_URL_HOST);
            $get = stream_context_create(array("ssl" => array("capture_peer_cert" => TRUE)));
            $read = stream_socket_client("ssl://".$orignal_parse.":443", $errno, $errstr,
                30, STREAM_CLIENT_CONNECT, $get);
            $cert = stream_context_get_params($read);
            $certinfo = openssl_x509_parse($cert['options']['ssl']['peer_certificate']);
//arrPrint($certinfo);
            $akhir =date("Y-m-d",$certinfo['validTo_time_t']);
            arrPrint($akhir);
            $currentDate = (date("Y-m-d"));
            $difDay = getDayDifference($currentDate,$akhir) -1;
//            cekHitam($url. " end date".$akhir);
            $tmpList[$url] = $difDay;

        }
arrPrint($tmpList);
        matiHEre("hoop");
        foreach($tmpList as $dom =>$sisaHari){
            $send_tele = 1;
            if(($sisaHari < 4) && ($sisaHari >= 0)){
                $pesan_tele = "⚠️ WARNING...!!!".PHP_EOL.PHP_EOL;
                $pesan_tele .= PHP_EOL."👉  $dom ";
                $pesan_tele .= PHP_EOL."⏱️  Tersisa $sisaHari Hari Lagi".PHP_EOL;
                $pesan_tele .= PHP_EOL."Segera Lakukan Renew";
                $pesan_tele .= PHP_EOL."LAPORAN SELESAI...!!!";
                $send_tele == 1 ? kirim_tele($pesan_tele) : cekHere("tele tidak dikirim<hr>$pesan_tele");

            }
            elseif($sisaHari < 0){
                $sisaHari_f = $sisaHari * -1;
                $pesan_tele =  "$dom sudah expired dari $sisaHari_f hari yang lalu";
                $pesan_tele .=  PHP_EOL. "Segera diregistrasikan ulang";
                //        $kirim_tele = kirim_tele($pesan_tele);
                $send_tele == 1 ? kirim_tele($pesan_tele) . cekHere("$pesan_tele") : cekHere("tele tidak dikirim<hr>$pesan_tele");

            }
            elseif($sisaHari >= 89){
                $pesan_tele =  "SSL $dom sudah aktive/diperbarui";
                $pesan_tele .=  PHP_EOL. "Akan berakhir pada $sisaHari hari lagi ";
//        $kirim_tele = kirim_tele($pesan_tele);
                $send_tele == 1 ? kirim_tele($pesan_tele) . cekHere("$pesan_tele") : cekHere("tele tidak dikirim<hr>$pesan_tele");

            }
            elseif($sisaHari <= 0){
                $pesan_tele =  "SSL $dom belum ativ";
                // $pesan_tele .=  PHP_EOL. "Akan berakhir pada $sisaHari hari lagi (*$dtime_validUntil*)";
                //        $kirim_tele = kirim_tele($pesan_tele);
                $send_tele == 1 ? kirim_tele($pesan_tele) . cekHere("$pesan_tele") : cekHere("tele tidak dikirim<hr>$pesan_tele");
            }
            else{
                $pesan_tele =  "$dom akan expired  $sisaHari hari lagi";
                $pesan_tele .=  PHP_EOL. "Masih aman ";
                $pesan_tele .= PHP_EOL."LAPORAN SELESAI...!!!";
                //        $kirim_tele = kirim_tele($pesan_tele);
                $send_tele == 1 ? kirim_tele($pesan_tele) . cekHere("$pesan_tele") : cekHere("tele tidak dikirim<hr>$pesan_tele");
            }
        }



//arrPrint($tmpList);

    }
}

?>