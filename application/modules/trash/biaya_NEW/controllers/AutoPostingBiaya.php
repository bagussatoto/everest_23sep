<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);
require_once "Modul_Controller.php";

//class AutoDepresiasi_coa extends CI_Controller
class AutoPostingBiaya extends Modul_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper("he_stepping");
        $this->load->helper("he_access_right");
        $this->load->library("MobileDetect");
        $this->load->helper('he_angka');
        $this->load->config("heAccounting");
        $this->load->model("CustomCounter");
        $this->load->model("MdlTransaksi");
    }
    public function index()
    {

        $jenisTr = isset($_REQUEST["jenistr"]) ? $_REQUEST["jenistr"] : matiHere("jenisTr belum di set");
        $no_spk  = isset($_REQUEST["no_spk"]) ? $_REQUEST["no_spk"] : matiHere("no_spk belum di set");
        $login_connect  = isset($_REQUEST["login_connect"]) ? $_REQUEST["login_connect"] : matiHere("login_connect");

        $this->session->login = $login_connect;
//        echo "<title>AUTO-TRANSAKSI</title>";

        $this->jenisTr = $jenisTr;
        $this->tableInConfig = isset($this->configUi[$this->jenisTr]['tableIn']) ? $this->configUi[$this->jenisTr]['tableIn'] : array();
        $this->tableInConfig_static = isset($this->configUi[$this->jenisTr]['tableIn_static']) ? $this->configUi[$this->jenisTr]['tableIn_static'] : array();

        $cCode = "_TR_" . $this->jenisTr;
        $relOptionConfigs   = isset($this->configUi[$this->jenisTr]['relativeOptions']) ?            $this->configUi[$this->jenisTr]['relativeOptions'] : array();
        $itemNumLabels      = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ?   $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig        = isset($this->configUi[$this->jenisTr]['selectedPrice']) ?              $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig       = isset($this->configUi[$this->jenisTr]['lockerCheck']) ?                $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig    = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $mainClonerConfig   = isset($this->configUi[$this->jenisTr]['mainCloner']['items']) ?        $this->configUi[$this->jenisTr]['mainCloner']['items'] : array();

        $title              = $this->configUi[$this->jenisTr]["label"];
        $subTitle           = $this->configUi[$this->jenisTr]["steps"][1]['label'];
        $handler            = $this->configUi[$this->jenisTr]['selectorProcessor'];
        $handler2           = $this->configUi[$this->jenisTr]['selectorProcessor2'];
//        $nextStepNumTarget = $stepNumTarget + 1;
        $this->db->trans_start();

        //manual define
        $gudID = "-10";
        $gud2ID = "9";

        $cabID = "1";
        $cab2ID = "-1";

        //definisi gudang
        $this->load->model("Mdls/MdlGudang");
        $gud = new MdlGudang();
        $gud->setFilters(array());
        $tmpGud = $gud->lookUpAll()->result();

        $branchData=array();
        foreach($tmpGud as $rcab){
            $branchData[$rcab->cabang_id][$rcab->id] = (array)$rcab;
            $branchData[$rcab->cabang_id][$rcab->id]["gudang_nama"] = $rcab->nama;
        }

        //definisi cabang
        $this->load->model("Mdls/MdlCabang");
        $cab = new MdlCabang();
        $cab->setFilters(array());
        $tmpCab = $cab->lookUpAll()->result();

        $cabangData=array();
        foreach($tmpCab as $rcab){
            $cabangData[$rcab->id] = $rcab->nama;
        }

        $this->load->model("Mdls/MdlTasklistProject");
        $tp = new MdlTasklistProject();
        $tp->setFilters(array());
        $tp->addFilter("no_spk='$no_spk'");
        $tp->addFilter("status=1");
        $tp->addFilter("trash=0");
        $tp->addFilter("post_biaya_id=0");
        $tmpTp = $tp->lookUpAll()->result();

        $is_tasklist_tambahan = 0;
        if(empty($tmpTp)){
            $this->load->model("Mdls/MdlTasklistProjectTambahan");
            $tp = new MdlTasklistProjectTambahan();
            $tp->setFilters(array());
            $tp->addFilter("no_spk='$no_spk'");
            $tp->addFilter("status=1");
            $tp->addFilter("trash=0");
            $tp->addFilter("post_biaya_id=0");
            $tmpTp = $tp->lookUpAll()->result();

            $is_tasklist_tambahan = !empty($tmpTp) ? 1 : 0;
        }

        $arrTaskList=array();
        if(!empty($tmpTp)){
            foreach($tmpTp as $rowTp){
                $arrTaskList[$rowTp->no_spk] = (array)$rowTp;
            }
        }
        else{
            $result = array(
                "status" => 0,
                "reason" => "biaya ini sudah di posting ($no_spk)",
                "line" => __LINE__,
            );
            echo json_encode($result);
            die();
        }

        $type_pelaksana_txt = "";
        if(isset($arrTaskList[$no_spk]["type_pelaksana"]) && $arrTaskList[$no_spk]["type_pelaksana"]==22){
            $type_pelaksana_txt = "vendor";
        }
        else{
            $type_pelaksana_txt = "employee";
        }

        $this->load->model("Mdls/MdlProdukProject");
        $tpp = new MdlProdukProject();
        $tpp->setFilters(array());
        $tpp->addFilter("id='".$tmpTp[0]->produk_id."'");
        $tmpTpp = $tpp->lookUpAll()->result();

        $tmpB=array();//MdlSubProgresTasklistKomposisi
        $this->load->model("Mdls/MdlSubProgresTasklistKomposisi"); //project_sub_tasklist_komposisi
        $stlk = new MdlSubProgresTasklistKomposisi();
        $stlk->setFilters(array());
        $stlk->addFilter("no_spk='$no_spk'");
        $stlk->addFilter("status=1");
        $stlk->addFilter("trash=0");
        $stlk->addFilter("jenis='biaya'");
        $tmpB = $stlk->lookUpAll()->result();

        if(empty($tmpB)){
            $this->load->model("Mdls/MdlSubProgresTasklistKomposisiTambahan"); //project_sub_tasklist_komposisi_tambahan
            $stlk = new MdlSubProgresTasklistKomposisiTambahan();
            $stlk->setFilters(array());
            $stlk->addFilter("no_spk='$no_spk'");
            $stlk->addFilter("status=1");
            $stlk->addFilter("trash=0");
            $stlk->addFilter("jenis='biaya'");
            $tmpB = $stlk->lookUpAll()->result();
        }

        //region array builder transaction
        $mainTmp = array(
            "olehID" => "olehID",
            "olehName" => "olehName",
            "placeID" => "placeID",
            "placeName" => "placeName",
            "cabangID" => "cabangID",
            "cabangName" => "cabangName",
            "gudangID" => "gudangID",
            "gudangName" => "gudangName",
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $this->jenisTr . "r",
            "jenisTrName" => $title,
            "stepNumber" => "1",
            "stepCode" => $this->jenisTr . "r",
            "dtime" => "dtime",
            "fulldate" => "date",
            "gudang2" => "-1",
            "gudang2__label" => "default center warehouse",
            "gudang2__nama" => "",
            "harga" => "harga",
            "divID" => "18",
            "divName" => "default",
            "subtotal" => "subtotal",
            "reference" => "0",
            "gudang2ID" => "-1",
            "gudang2Name" => "default center warehouse",
            "jenis" => $this->jenisTr . "r",
            "transaksi_jenis" => $this->jenisTr . "r",
            "next_step_code" => $this->jenisTr,
            "next_group_code" => "o_finance",
            "step_number" => "1",
            "step_current" => "1",
            "longitude" => "",
            "lattitude" => "",
            "accuracy" => "",
            "nilai_bayar" => "0",
            "new_sisa" => "0",
            "note" => "0",
            "description" => "",
            "pihakID" => "-1",
            "pihakName" => "PUSAT",
            "pihakName2" => "PUSAT",
            "pihakDisc" => "",
            "cabang2ID" => "-1",
            "cabang2Name" => "PUSAT",
            "place2ID" => "-1",
            "place2Name" => "PUSAT",
//            "projectName" => "wkwkwk",
//            "cabang_id" => "wkwkwk",
//            "cabang_nama" => "wkwkwk",
            "type_pelaksana" => isset($arrTaskList[$no_spk]["type_pelaksana"]) ? $arrTaskList[$no_spk]["type_pelaksana"] : 11, // 22 = vendor || 11 = employee
            "type_pelaksana_txt" => $type_pelaksana_txt,
            "biaya_tambahan" => 0,
//            "pihakWoProjek" => "pihakWoProjek",
//            "pihakWoProjekName" => "pihakWoProjekName",
//            "pihakWoProjekSpk" => "pihakWoProjekSpk",
//            "pihakWoProjekEmployee" => "pihakWoProjekEmployee",
//            "pihakWoProjekEmployeeName" => "pihakWoProjekEmployeeName",
        );
        $itemsTmp = array(
            "handler" => "Selectors/_processSelectBiaya",
            "id" => "id",
            "jml" => "1",
            "harga" => "harga",
            "subtotal" => "subtotal",
            "nama" => "nama",
            "label" => "",
            "reference" => "",
            "qty" => "1",
            "name" => "extern_nama",
            "extern_id" => "extern_id",
            "extern_nama" => "extern_nama",
            "sub_harga" => "sub_harga",
            "sub_subtotal" => "sub_total",
            "olehID" => "olehID",
            "olehName" => "olehName",
            "placeID" => "placeID",
            "placeName" => "cabang_nama",
            "cabangID" => "cabangID",
            "cabangName" => "cabangName",
            "gudangID" => "gudangID",
            "gudangName" => "gudangName",
            "gudang2ID" => "-1",
            "gudang2Name" => "default center warehouse",
            "jenisTr" => $this->jenisTr,
            "next_substep_code" => $this->jenisTr,
            "next_subgroup_code" => "o_finance",
            "sub_step_number" => "1",
            "sub_step_current" => "1",
            "nilai_bayar" => "",
            "new_sisa" => "0",
            "sub_new_sisa" => "0",
            "note" => "",
            "pihakID" => "-1",
            "pihakName" => "PUSAT",
            "cabang2_id" => "-1",
            "cabang2_nama" => "PUSAT",
            "place2ID" => "-1",
            "place2Name" => "PUSAT",
            "cabang2ID" => "-1",
            "cabang2Name" => "PUSAT",
            "cat_id" => "cat_id",
            "cat_nama" => "cat_nama",

//            "pihakWoProjek" => $arrTaskList[$no_spk]["id"],
//            "pihakWoProjekName" => $arrTaskList[$no_spk]["produk_nama"],
//            "pihakWoProjekSpk" => $no_spk,
//            "pihakWoProjekEmployee" => $arrTaskList[$no_spk]["employee_id"],
//            "pihakWoProjekEmployeeName" => $arrTaskList[$no_spk]["employee_nama"],

        );
        $items2 = array();
        $items2_sum = array();
        $rsltItems = array();
        $rsltItems2 = array();
        $tableIn_masterTmp = array(
            "trash" => "0",
            "jenis_master" => $this->jenisTr,
            "jenis_top" => $this->jenisTr . "r",
            "jenis" => $this->jenisTr . "r",
            "jenis_label" => $title,
            "div_id" => "18",
            "div_nama" => "default",
            "oleh_id" => "olehID",
            "oleh_nama" => "olehName",
            "cabang_id" => "cabangID",
            "cabang_nama" => "cabangName",
            "transaksi_nilai" => "sub_total",
            "transaksi_jenis" => $this->jenisTr . "r",
            "gudang_id" => "gudangID",
            "gudang_nama" => "gudangName",
            "gudang2_id" => "-1",
            "gudang2_nama" => "default center warehouse",
            "keterangan" => "",
            "cabang2_id" => "-1",
            "cabang2_nama" => "PUSAT",
        );
        $tableIn_detailTmp = array(
            "dtime" => date("Y-m-d H:i:s"),
            "produk_id" => "id",
            "produk_kode" => "",
            "produk_label" => "",
            "produk_nama" => "nama",
            "produk_ord_jml" => "jml",
            "produk_ord_hrg" => "harga",
            "hpp" => "harga",
            "satuan" => "",
            "note" => "",
            "reference" => "",
            "trash" => 0,
            "produk_jenis" => "expense",
            "next_substep_code" => "3674",
            "next_subgroup_code" => "o_finance",
            "sub_step_number" => 1,
            "sub_step_current" => 1,
            "valid_qty" => "jml",
        );
        $tableIn_detail2_sum = array();
        $tableIn_detail_rsltItems = array();
        $tableIn_detail_rsltItems2 = array();
        $tableIn_master_valuesTmp = array(
            "gudang2" => "-1",
            "harga" => "harga",
            "divID" => "18",
            "subtotal" => "subtotal",
            "reference" => "0",
            "nilai_bayar" => "0",
            "note" => "0",
        );
        $tableIn_detail_valuesTmp = array(
            "jml" => "1",
            "harga" => "harga",
            "subtotal" => "subtotal",
            "qty" => "1",
            "sub_harga" => "sub_harga",
            "sub_subtotal" => "subtotal",
            "sub_new_sisa" => "0",
        );
        $tableIn_detail_values_rsltItemsTmp = array();
        $tableIn_detail_values_rsltItems2Tmp = array();
        $tableIn_detail_values2_sumTmp = array();
        $tableIn_detail2 = array();
        $main_add_values = array();
        $main_add_fields = array();
        $main_elements = array();
        $main_inputs = array();
        $main_inputs_orig = array();
        $receiptDetailFieldsTmp = array(
            "produk_nama" => "name",
        );
        $receiptSumFieldsTmp = array(
            "harga" => "total amount",
        );
        $receiptDetailFields2 = array();
        $receiptSumFields2 = array();
        $tableIn_detail_values2_sum = array();
        $items3 = array();
        $items3_sum = array();
        //endregion

        //transaksi main buildder taro sini
        $main = array();
        $arrItems=array();
        $arrItems2=array();

        if (count($tmpB) > 0) {
            //items
            foreach($tmpB as $row){
                $id = $row->produk_dasar_id;
                $satuan = (isset($row->satuan) && strlen($row->satuan) > 0) ? $row->satuan : "n/a";
                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
                if ((!array_key_exists($id, $arrItems))) {
                    //baca dari config untuk yang wajib diisi/ mandatory
                    $tmp = array(
                        "handler" => $this->uri->segment(1) . "/" . $handler,
                        "id" => $id,
                        "nama" => $row->produk_dasar_nama,
                        "jml" => $row->jml,
                        "harga" => $row->harga,
                        "harga_anggaran" => $row->harga,
                        "cat_id" => $row->cat_id,
                        "cat_nama" => $row->cat_nama,
                        "subtotal" => $row->harga*$row->jml,
                        "rekening" => isset($row->rekening) ? $row->rekening : "",
                    );

                    foreach ($fieldSrcs as $key => $src) {
                        $tmp[$key] = makeValue($src, $tmp, $tmp, isset($row->$key) ? $row->$key : 0);
                    }

                    //region perhitungan subtotal items
                    if (isset($subAmountConfig) && $subAmountConfig != null) {
                        $subtotal = makeValue($subAmountConfig, $tmp, $tmp, 0);
                    }
                    else {
                        $subtotal = 0;
                    }
                    $tmp["subtotal"] = $subtotal;
                    $arrItems[$id] = $tmp;
                    //endregion

                    if (sizeof($itemNumLabels) > 0) {
                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                $arrItems[$id][$key] = $newValue;
                            }
                        }
                        $arrItems[$id]['subtotal'] = ($arrItems[$id]['jml'] * $arrItems[$id]['harga']);
                    }
                }
            }
        }

        $this->load->model("Mdls/MdlProjectSubTasklistKomposisi"); //project_sub_tasklist_komposisi
        $stlkSup = new MdlProjectSubTasklistKomposisi();
        $stlkSup->setFilters(array());
        $stlkSup->addFilter("no_spk='$no_spk'");
        $stlkSup->addFilter("status=1");
        $stlkSup->addFilter("trash=0");
        $sub_tasklist_komposisi = $stlkSup->lookUpAll()->result();

        if(empty($sub_tasklist_komposisi)){
            $this->load->model("Mdls/MdlProjectSubTasklistKomposisiTambahan"); //project_sub_tasklist_komposisi_tambahan
            $stlkSup = new MdlProjectSubTasklistKomposisiTambahan();
            $stlkSup->setFilters(array());
            $stlkSup->addFilter("no_spk='$no_spk'");
            $stlkSup->addFilter("status=1");
            $stlkSup->addFilter("trash=0");
            $sub_tasklist_komposisi = $stlkSup->lookUpAll()->result();
        }

        $usedSuppliesBiaya = array();
        $usedBiaya = array();
        if(!empty($sub_tasklist_komposisi)){
            foreach($sub_tasklist_komposisi as $key => $subKom){
                if($subKom->jenis == "supplies"){
                    $usedSuppliesBiaya[$subKom->biaya_id][$subKom->biaya_dasar_id] = $subKom;
                }
                if($subKom->jenis == "biaya"){
                    $usedBiaya[$subKom->produk_dasar_id] = $subKom;
                }
            }
        }

        $this->load->model("Mdls/MdlProjectKomponenBiayaDetailsRabSub");
        $stlk2 = new MdlProjectKomponenBiayaDetailsRabSub();
        $stlk2->setFilters(array());
        $stlk2->addFilter("no_spk='$no_spk'");
        $stlk2->addFilter("status=1");
        $stlk2->addFilter("trash=0");
        $stlk2->addFilter("jenis='biaya'");
        $tmpB2 = $stlk2->lookUpAll()->result();

        if(empty($tmpB2)){
            $this->load->model("Mdls/MdlProjectKomponenBiayaDetailsRabSubTambahan");
            $stlk2 = new MdlProjectKomponenBiayaDetailsRabSubTambahan();
            $stlk2->setFilters(array());
            $stlk2->addFilter("no_spk='$no_spk'");
            $stlk2->addFilter("status=1");
            $stlk2->addFilter("trash=0");
            $stlk2->addFilter("jenis='biaya'");
            $tmpB2 = $stlk2->lookUpAll()->result();
        }

        if (count($tmpB2) > 0) {
            $arrItemsTmp2=array();
            foreach ($tmpB2 as $row) {
                $arrItemsTmp2[] = $row;
            }

            //items2
            foreach($arrItemsTmp2 as $row){
                $id = $row->biaya_id;
                $id_dasar = $row->biaya_dasar_id;
                $satuan = (isset($row->satuan) && strlen($row->satuan) > 0) ? $row->satuan : "n/a";
                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
                if ((!array_key_exists($id_dasar, $arrItems2[$id]))) {
                    //baca dari config untuk yang wajib diisi/ mandatory
                    $tmp = array(
                        "handler" => $this->uri->segment(1) . "/" . $handler2,
                        "editTarget" => MODUL_PATH . $handler2 ."/".$this->uri->segment(4),
                        "id" => $id,
                        "biaya_dasar_id" => $id_dasar,
                        "biaya_dasar_nama" => $row->biaya_dasar_nama,
                        "biaya_id" => $row->biaya_id,
                        "biaya_nama" => $row->biaya_nama,
                        "nama" => $row->biaya_dasar_nama,
                        "project_id" => $row->project_id,
                        "project_nama" => $row->project_nama,
                        "cabang_id" => $cabID,
                        "cabang_nama" => $cabangData[$cabID],
                        "place2ID" => $cab2ID,
                        "gudangID" => $gud2ID,
                        "no_spk" => $row->no_spk,
                        "wo_id" => $row->sub_fase_id,
                        "wo_nama" => $row->sub_fase_nama,
                        "project_employee" => $arrTaskList[$row->no_spk]['employee_id'],
                        "project_employee_nama" => $arrTaskList[$row->no_spk]['employee_nama'],
                        "jml_ori" => $row->jml,
                        "jml" => isset($usedSuppliesBiaya[$row->biaya_id][$id_dasar]) ? $usedSuppliesBiaya[$row->biaya_id][$id_dasar]->jml : $row->jml,
                        "harga" => $row->harga,
                        "cat_id" => $row->cat_id,
                        "cat_nama" => $row->cat_nama,
                        "subtotal" => isset($usedSuppliesBiaya[$row->biaya_id][$id_dasar]) ? $usedSuppliesBiaya[$row->biaya_id][$id_dasar]->jml*$row->harga : $row->jml*$row->harga,
//                        "biaya_tambahan" => $is_tasklist_tambahan ? (isset($usedSuppliesBiaya[$row->biaya_id][$id_dasar]) ? $usedSuppliesBiaya[$row->biaya_id][$id_dasar]->jml*$row->harga : $row->jml*$row->harga) : 0,
                        "biaya_tambahan" => $type_pelaksana_txt == "vendor" ? ($is_tasklist_tambahan ? (isset($usedSuppliesBiaya[$row->biaya_id][$id_dasar]) ? $usedSuppliesBiaya[$row->biaya_id][$id_dasar]->jml*$row->harga : $row->jml*$row->harga) : 0) : 0,
                        "harga_tambahan" => $is_tasklist_tambahan ? $row->harga :  0,
                        "rekening" => isset($row->rekening) ? $row->rekening : "",
                    );

                    foreach ($fieldSrcs as $key => $src) {
                        $tmp[$key] = makeValue($src, $tmp, $tmp, isset($row->$key) ? $row->$key : 0);
                    }

                    //region perhitungan subtotal items
                    if (isset($subAmountConfig) && $subAmountConfig != null) {
                        $subtotal = makeValue($subAmountConfig, $tmp, $tmp, 0);
                    }
                    else {
                        $subtotal = 0;
                    }
                    $tmp["subtotal"] = $subtotal;
                    $arrItems2[$id][$id_dasar] = $tmp;
                    //endregion

                    if (sizeof($itemNumLabels) > 0) {
                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                $arrItems2[$id][$id_dasar][$key] = $newValue;
                            }
                        }
                        $arrItems2[$id][$id_dasar]['subtotal'] = ($arrItems2[$id][$id_dasar]['jml'] * $arrItems2[$id][$id_dasar]['harga']);
                    }
                }
            }
        }
        if(isset($arrItems2)){
            foreach($arrItems2 as $by_id => $data_0){
                $totalDetails=0;
                foreach($data_0 as $by_drs_id => $data_1){
                    $totalDetails += $data_1["subtotal"]*1;
                }
                $arrItems[$by_id]["subtotal"] = $totalDetails;
            }
        }

        $subtotal=0;
        if (sizeof($arrItems) > 0) {
            foreach ($arrItems as $xid => $iSpec) {
                $subtotal += $iSpec["subtotal"]*1;
            }
        }

        $biaya_tambahan = $is_tasklist_tambahan ? $subtotal : 0;

        //region builder main
        $main = array(
            "olehID" => "-100",
            "olehName" => "sys",
            "cabang_id" => $cabID,
            "cabang_nama" => $cabangData[$cabID],
            "placeID" => $cabID,
            "placeName" => $cabangData[$cabID],
            "cabangID" => $cabID,
            "cabangName" => $cabangData[$cabID],
            "gudangID" => $gudID,
            "gudangName" => (isset($branchData[$cabID][$gudID]['gudang_nama']) ? $branchData[$cabID][$gudID]['gudang_nama'] : ""),
            "cabang2_id" => $cab2ID,
            "cabang2_nama" => "pusat",
            "place2ID" => $cab2ID,
            "place2Name" => "pusat",
            "cabang2ID" => $cab2ID,
            "cabang2Name" => "pusat",
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $this->jenisTr . "r",
            "jenisTrName" => $title,
            "stepNumber" => "1",
            "stepCode" => $this->jenisTr . "r",
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow(),
            "harga" => $subtotal,
            "divID" => "18",
            "divName" => "default",
            "subtotal" => $subtotal,
            "reference" => "0",
            "jenis" => $this->jenisTr . "r",
            "transaksi_jenis" => $this->jenisTr . "r",
            "next_step_code" => $this->jenisTr,
            "next_group_code" => "o_finance",
            "step_number" => "1",
            "step_current" => "1",
            "longitude" => "",
            "lattitude" => "",
            "accuracy" => "",
            "nilai_bayar" => "0",
            "new_sisa" => "0",
            "note" => "0",
            "description" => "",
            "pihakDisc" => "",
            "type_pelaksana" => $arrTaskList[$no_spk]["type_pelaksana"],
            "type_pelaksana_txt" => $type_pelaksana_txt,
            "pihakWoProjek" => $arrTaskList[$no_spk]["id"],
            "pihakWoProjekName" => $arrTaskList[$no_spk]["produk_nama"],
            "pihakWoProjekSpk" => $no_spk,
            "pihakWoProjekEmployee" => $arrTaskList[$no_spk]["employee_id"],
            "pihakWoProjekEmployeeName" => $arrTaskList[$no_spk]["employee_nama"],
//            "projectName" => "ADWKAWDKDAW",
            "current_projectID" => $arrTaskList[$no_spk]["produk_id"],
            "biaya_tambahan" => $biaya_tambahan,
            "piutang_tambah" => $type_pelaksana_txt == "vendor" ? $biaya_tambahan : 0,
        );
        //endregion builder main

//        $subcat_ = array();
//        if (sizeof($arrItems2) > 0) {
//            foreach ($arrItems2 as $xid => $iSpec) {
//                $id = $iSpec['id'];
//                $main['harga'] += ($iSpec['jml'] * $iSpec['harga']);
//                $catnama_ = str_replace(" ","_", $iSpec["cat_nama"]);
//                if(!isset($subcat_[$catnama_])){
//                    $subcat_[$catnama_] = 0;
//                }
//                $subcat_[$catnama_] += $iSpec["subtotal"]*1;
//                if(!isset($subcat_["piutang_cabang"])){
//                    $subcat_["piutang_cabang"] = 0;
//                }
//                $subcat_["piutang_cabang"] += $iSpec["subtotal"]*1;
//            }
//            foreach($subcat_ as $key => $val){
//                $main[$key] = $val;
//            }
//        }

        $subcat_ = array();
        if (sizeof($arrItems2) > 0) {
            foreach ($arrItems as $xid => $dSpec) {
                if(!isset($arrItems2[$xid])){
                    $main['harga'] += ($dSpec['jml'] * $dSpec['harga']);
                    $catnama_ = $dSpec["cat_id"];
                    if(!isset($subcat_[$catnama_])){
                        $subcat_[$catnama_] = 0;
                    }
                    $subcat_[$catnama_] += $dSpec["subtotal"]*1;
                    if(!isset($subcat_["piutang_cabang"])){
                        $subcat_["piutang_cabang"] = 0;
                    }
                    $subcat_["piutang_cabang"] += $dSpec["subtotal"]*1;
                }
                else{
                    foreach($arrItems2[$xid] as $iSpec){
                        $main['harga'] += ($iSpec['jml'] * $iSpec['harga']);
//                    $catnama_ = str_replace(" ","_", $iSpec["cat_id"]);
                        $catnama_ = $iSpec["cat_id"];
                        if(!isset($subcat_[$catnama_])){
                            $subcat_[$catnama_] = 0;
                        }
                        $subcat_[$catnama_] += $iSpec["subtotal"]*1;
                        if(!isset($subcat_["piutang_cabang"])){
                            $subcat_["piutang_cabang"] = 0;
                        }
                        $subcat_["piutang_cabang"] += $iSpec["subtotal"]*1;
                    }
                }
            }
            foreach($subcat_ as $key => $val){
                $main[$key] = $val;
            }
        }

//        arrPrint($tmpB2);
//        arrPrint($arrItems);
        arrPrint($main);
//        arrPrint($arrItems2);
//        arrPrintWebs($arrItemsTmp2);
//        matiHere(__LINE__);

        //region builder items
        $items = array();
        foreach ($arrItems as $itsID => $itsData) {
            foreach ($itemsTmp as $col => $selectedRow) {
                $items[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
            }
        }

        //region builder items
        $items2 = $arrItems2;

        //region builder tabel in master
        $tableIn_master = array(
            "trash" => "0",
            "jenis_master" => $this->jenisTr,
            "jenis_top" => $this->jenisTr . "r",
            "jenis" => $this->jenisTr . "r",
            "jenis_label" => $title,
            "div_id" => "18",
            "div_nama" => "default",
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow(),
            "oleh_id" => "-100",
            "oleh_nama" => "sys",
            "cabang_id" => $cabID,
            "cabang_nama" => $cabangData[$cabID],
            "transaksi_nilai" => $subtotal,
            "transaksi_jenis" => $this->jenisTr . "r",
            "gudang_id" => $gudID,
            "gudang_nama" => isset($branchData[$cabID][$gudID]['gudang_nama']) ? $branchData[$cabID][$gudID]['gudang_nama'] : "",
            "gudang2_id" => "-1",
            "gudang2_nama" => "default center warehouse",
            "keterangan" => "",
            "cabang2_id" => "-1",
            "cabang2_nama" => "PUSAT",
        );
        //endregion builder tabel in master

        //region builder table in detil
        $tableIn_detail = array();
        foreach ($arrItems as $itsID => $itsData) {
            foreach ($tableIn_detailTmp as $col => $selectedRow) {
                $tableIn_detail[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
            }
        }
        //endregion builder table in detil

        //region table in master values
        $tableIn_master_values = array(
            "gudang" => $gudID,
            "harga" => $subtotal,
            "divID" => "18",
            "subtotal" => $subtotal,
            "reference" => "0",
            "nilai_bayar" => "0",
            "note" => "0",
        );
        //endregion table in master values

        //region build table in detil values
        $tableIn_detail_values = array();
        foreach ($arrItems as $itsID => $itsData) {
            foreach ($tableIn_detail_valuesTmp as $col => $selectedRow) {
                $tableIn_detail_values[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
            }
        }
        //endregion build table in detil values

        //region build table receipDetailFields
        $receiptDetailFields = array();
        foreach ($arrItems as $itsID => $itsData) {
            foreach ($receiptDetailFieldsTmp as $col => $selectedRow) {
                $receiptDetailFields[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
            }
        }
        //endregion

        //region receiptSumFields
        $receiptSumFields = array();
        foreach ($arrItems as $itsID => $itsData) {
            foreach ($receiptSumFieldsTmp as $col => $selectedRow) {
                $receiptSumFields[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
            }
        }
        //endregion

        if(!empty($tmpTpp)){
            $main['pihakProjekID'] = $tmpTp[0]->produk_id;
            $main['pihakProjekName'] = $tmpTp[0]->nama;
            //-GUDANG PER PROJECT------
            $main['pihakProjekCustomerID'] = isset($tmpTpp[0]->customer_id) ? $tmpTpp[0]->customer_id : 0;
            $main['pihakProjekCustomerName'] = isset($tmpTpp[0]->customer_nama) ? $tmpTpp[0]->customer_nama : 0;
            $main['pihakProjekCustomerNama'] = isset($tmpTpp[0]->customer_nama) ? $tmpTpp[0]->customer_nama : 0;

            $main['pihakProjekGudangID']   = getDefaultWarehouseProject($tmpTp[0]->produk_id, $main['pihakProjekName'])["gudang_id"];
            $main['pihakProjekGudangName'] = getDefaultWarehouseProject($tmpTp[0]->produk_id, $main['pihakProjekName'])["gudang_nama"];
            $main['pihakProjekGudangNama'] = getDefaultWarehouseProject($tmpTp[0]->produk_id, $main['pihakProjekName'])["gudang_nama"];
            //-------
        }

//        arrPrintWebs($items2);
//        cekMerah("items2");
//        arrPrintWebs($arrItems2);
//        cekMerah("arrItems2");
        arrPrintWebs($arrItems);
//        arrPrintWebs($main);
//        arrPrint($arrItems2);
//        matiHere(__LINE__);
        if (sizeof($arrItems) > 0) {

            $gate['items'] = $arrItems;
            $gate['items2'] = $arrItems2;
            $gate['main'] = $main;
            $jenisTrTarget = isset($this->configUi[$this->jenisTr]["steps"][1]["target"]) ? $this->configUi[$this->jenisTr]["steps"][1]["target"] : NULL;

            //region transaksional
            $buildTablesMaster = isset($this->configCore[$this->jenisTr]['components'][1]['master']) ? $this->configCore[$this->jenisTr]['components'][1]['master'] : array();
            $buildTablesDetail = isset($this->configCore[$this->jenisTr]['components'][1]['detail']) ? $this->configCore[$this->jenisTr]['components'][1]['detail'] : array();
            $addMasterTables = array(
                "rugilaba",
                "laba ditahan",
                "rugilaba lain lain",
            );
            foreach ($addMasterTables as $trek) {
                $buildTablesMaster[] = array(
                    "comName" => "RugiLaba",
                    "loop" => array(
                        "$trek" => .0,
                    ),
                );
            }
            if (sizeof($buildTablesMaster) > 0) {
                $bCtr = 0;
                foreach ($buildTablesMaster as $buildTablesMaster_specs) {
                    $bCtr++;
                    $mdlName = $buildTablesMaster_specs['comName'];
                    if (substr($mdlName, 0, 1) == "{") {
                        $mdlName = trim($mdlName, "{");
                        $mdlName = trim($mdlName, "}");
                        $mdlName = str_replace($mdlName, $main[$mdlName], $mdlName);
                    }
                    else {
                        //                        cekkuning("TIDAK mengandung kurawal");
                    }

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
                        }
                    }
                }
            }
            if (sizeof($buildTablesDetail) > 0) {
                foreach ($buildTablesDetail as $buildTablesDetail_specs) {
                    foreach ($items as $itemSpec) {
                        $mdlName = $buildTablesDetail_specs['comName'];
                        if (substr($mdlName, 0, 1) == "{") {
                            $mdlName = trim($mdlName, "{");
                            $mdlName = trim($mdlName, "}");
                            $mdlName = str_replace($mdlName, $itemSpec[$mdlName], $mdlName);
                        }
                        $mdlName = "Com" . $mdlName;
                        cekbiru("model: $mdlName");
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
                            }
                        }
                    }
                }
            }

            //region pre-processors (master)
            if (isset($this->configCore[$this->jenisTr]['preProcessor'][1]['master'])) {
                $iterator = isset($this->configCore[$this->jenisTr]['preProcessor'][1]['detail']) ? $this->configCore[$this->jenisTr]['preProcessor'][1]['master'] : array();
                $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields']) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'] : array();
                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                        $subParams = array();

                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {
                                $realValue = makeValue($value, $main, $main, 0);
                                $subParams['static'][$key] = $realValue;
                            }
                            $subParams['static']["fulldate"] = date("Y-m-d");
                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                            $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
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
                            if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                foreach ($gotParams as $gateName => $gSpec) {
                                    if (isset($main)) {
                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                            foreach ($gSpec as $key => $val) {
                                                $main[$key] = $val;
                                            }
                                        }
                                    }
                                    //==inject gotParams to child gate
                                    if (isset($main)) {
                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                            foreach ($gSpec as $key => $val) {
                                                $main[$key] = $val;
                                            }
                                        }
                                    }
                                    //cekMerah("REBUILDING VALUES..");
                                    if (sizeof($itemNumLabels) > 0) {
                                        //cekHijau("REBUILDING SUBS FOR ITEMS");
                                        foreach ($itemNumLabels as $key => $label) {
                                            //cekHere("$id === $key => $label");
                                            if (isset($main[$key])) {
                                                $main['sub_' . $key] = ($main['jml'] * $main[$key]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        else {
                            cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                        }
                    }
                }
                else {
                    //cekKuning("sub-preproc is not set");
                }
                $this->load->helper("he_value_builder");
                fillValues($this->jenisTr, 1, 1);
            }
            else {
                //echo("no processor defined. skipping preprocessor..<br>");
            }
            //endregion

            //region pre-processors (item)
            if (isset($this->configCore[$this->jenisTr]['preProcessor'][1]['detail'])) {
                $iterator = isset($this->configCore[$this->jenisTr]['preProcessor'][1]['detail']) ? $this->configCore[$this->jenisTr]['preProcessor'][1]['detail'] : array();
                $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields']) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'] : array();
                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        foreach ($gate[$srcGateName] as $xid => $dSpec) {
                            $tmpOutParams[$cCtr] = array();
                            $id = $xid;
                            $subParams = array();
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {
                                    $realValue = makeValue($value, $gate[$srcGateName][$id], $gate[$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;
                                }
                                $subParams['static']["fulldate"] = date("Y-m-d");
                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
                            }
                            if (sizeof($subParams) > 0) {
                                $tmpOutParams[$cCtr][] = $subParams;
                                $comName = $tComSpec['comName'];
                                $srcGateName = $tComSpec['srcGateName'];
                                $srcRawGateName = $tComSpec['srcRawGateName'];
                                $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
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
                                    if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                        foreach ($gotParams as $gateName => $paramSpec) {
                                            if (!isset($gate[$gateName])) {
                                                $gate[$gateName] = array();
                                            }
                                            else {

                                            }
                                            foreach ($paramSpec as $id => $gSpec) {
                                                if (!isset($gate[$gateName][$id])) {
                                                    $gate[$gateName][$id] = array();
                                                }
                                                if (isset($gate[$gateName][$id])) {
                                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                        foreach ($gSpec as $key => $val) {
                                                            $gate[$gateName][$id][$key] = $val;
                                                        }
                                                    }
                                                }
                                                //==inject gotParams to child gate
                                                if (isset($gate[$srcGateName][$id])) {
                                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                        foreach ($gSpec as $key => $val) {
                                                            $gate[$srcGateName][$id][$key] = $val;
                                                        }
                                                    }
                                                }
                                                //cekMerah("REBUILDING VALUES..");
                                                if (sizeof($itemNumLabels) > 0) {
                                                    //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                    foreach ($itemNumLabels as $key => $label) {
                                                        if (isset($gate[$gateName][$id][$key])) {
                                                            $gate[$gateName][$id]['sub_' . $key] = ($gate[$gateName][$id]['jml'] * $gate[$gateName][$id][$key]);
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
                            }
                        }
                    }
                }
                else {
                    //cekKuning("sub-preproc is not set");
                }
                $this->load->helper("he_value_builder");
                fillValues($this->jenisTr, 1, 1);
            }
            else {
                //echo("no processor defined. skipping preprocessor..<br>");
            }
            //endregion

//            $this->midValidate();
//            $this->unionValidate();
            //===finalisasi sebelum masuk tabel beneran

            //===isinya ada pembentukan nomor nota dll
            //region penomoran receipt
            $this->load->model("CustomCounter");
            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");
            $cn->setModul($this->modul);
            $cn->setStepCode($this->jenisTr.'r'); // di kasi r karena create request

            $counterForNumber = array($this->configCore[$this->jenisTr]['formatNota']);

            if (!in_array($counterForNumber[0], $this->configCore[$this->jenisTr]['counters'])) {
                die("LINE: ".__LINE__." || Used number should be registered in 'counters' config as well");
            }

            foreach ($counterForNumber as $i => $cRawParams) {
                $cParams = explode("|", $cRawParams);
                $cValues = array();
                foreach ($cParams as $param) {
                    $cValues[$i][$param] = $main[$param];
                }
                $cRawValues = implode("|", $cValues[$i]);
                $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);
//                cekMerah( $this->db->last_query() );
//                cekMerah( $cParams );
//                cekMerah( $cValues[$i] );
            }

//            cekHere($paramSpec);
//            cekHere($paramSpec);
//            cekHere($paramSpec);

            $stepNumber = 1;
            $tmpNomorNota = $paramSpec['paramString'];

            if (isset($this->configUi[$this->jenisTr]['steps'][2])) {
                $nextProp = array(
                    "num" => 2,
                    "code" => $this->configUi[$this->jenisTr]['steps'][2]['target'],
                    "label" => $this->configUi[$this->jenisTr]['steps'][2]['label'],
                    "groupID" => $this->configUi[$this->jenisTr]['steps'][2]['userGroup'],
                );
            }
            else {
                $nextProp = array(
                    "num" => 0,
                    "code" => "",
                    "label" => "",
                    "groupID" => "",
                );
            }
            //endregion

            //region dynamic counters
            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");
            $cn->setModul($this->modul);
            $cn->setStepCode($this->jenisTr . 'r');

            $configCustomParams = $this->configCore[$this->jenisTr]['counters'];
            $configCustomParams[] = "stepCode";

            if (sizeof($configCustomParams) > 0) {
                $cContent = array();
                foreach ($configCustomParams as $i => $cRawParams) {
                    $cParams = explode("|", $cRawParams);
                    $cValues = array();
                    foreach ($cParams as $param) {
                        $cValues[$i][$param] = $main[$param];
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
                }
            }
            $appliedCounters = base64_encode(serialize($cContent));
            $appliedCounters_inText = print_r($cContent, true);

            //region addition on master
            $addValues = array(
                'counters' => $appliedCounters,
                'counters_intext' => $appliedCounters_inText,
                'nomer' => $tmpNomorNota,
                'dtime' => date("Y-m-d H:i:s"),
                'fulldate' => date("Y-m-d"),
                "step_avail" => sizeof($this->configUi[$this->jenisTr]['steps']),
                "step_number" => 1,
                "step_current" => 1,
                "next_step_num" => $nextProp['num'],
                "next_step_code" => $nextProp['code'],
                "next_step_label" => $nextProp['label'],
                "next_group_code" => $nextProp['groupID'],
                "tail_number" => 1,
                "tail_code" => $this->configUi[$this->jenisTr]['steps'][1]['target'],
            );
            foreach ($addValues as $key => $val) {
                $tableIn_master[$key] = $val;
            }
            //endregion

            //region addition on detail
            $addSubValues = array(
                "sub_step_number" => 1,
                "sub_step_current" => 1,
                "sub_step_avail" => sizeof($this->configUi[$this->jenisTr]['steps']),
                "next_substep_num" => $nextProp['num'],
                "next_substep_code" => $nextProp['code'],
                "next_substep_label" => $nextProp['label'],
                "next_subgroup_code" => $nextProp['groupID'],
                "sub_tail_number" => 1,
                "sub_tail_code" => $this->configUi[$this->jenisTr]['steps'][1]['target'],
            );
            foreach ($tableIn_detail as $id => $dSpec) {
                foreach ($addSubValues as $key => $val) {
                    $tableIn_detail[$id][$key] = $val;
                }
            }
            //endregion

//            arrPrintWebs($main);
//            arrPrintWebs($arrItems);
//            arrPrint($tableIn_detail);
//            matiHere(__LINE__);
            //region ----------write transaksi, transaksi_data, main_fields, main_values, main_applets, etc
            if (sizeof($tableIn_master) > 0) {
                $tableIn_master['status_4'] = 11;
                $tableIn_master['trash_4'] = 0;

                $tr = new MdlTransaksi();
                $tr->addFilter("transaksi.cabang_id='" . $tableIn_master['cabang_id'] . "'");
                $transaksiID_current_step = $insertID = $tr->writeMainEntries($tableIn_master);
                $tableIn_master_query = $this->db->last_query();

                cekMerah(__LINE__);
                showLast_query("hijau");
                $mongoList['main'][] = $insertID;
                $epID = $tr->writeMainEntries_entryPoint($insertID, $insertID, $tableIn_master);
                $mongoList['main'][] = $epID;

                $insertNum = $tableIn_master['nomer'];
                $main['nomer'] = $insertNum;
                if ($insertID < 1) {
                    die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                }

                //==transaksi_id dan nomor nota diinject kan ke gate utama
                $injectors = array(
                    "transaksi_id" => $insertID,
                    "nomer" => $tmpNomorNota,
                );
                $arrInjectorsTarget = array(
                    "items",
                );
                foreach ($injectors as $key => $val) {
                    $main[$key] = $val;
                    foreach ($arrInjectorsTarget as $target) {
                        foreach ($items as $xis => $iSpec) {
                            $id = isset($iSpec['id']) && $iSpec['id'] > 0 ? $iSpec['id'] : $xis;
                            if (isset($items[$id])) {
                                $items[$id][$key] = $val;
                            }
                        }
                        foreach ($gate[$target] as $xis => $iSpec) {
                            $id = isset($iSpec['id']) && $iSpec['id'] > 0 ? $iSpec['id'] : $xis;
                            $gate[$target][$id][$key] = $val;
                        }
                    }
                }

                //===signature
                $dwsign = $tr->writeSignature($insertID, array(
                    "nomer" => $main['nomer'],
                    "step_number" => 1,
                    "step_code" => $this->jenisTr,
                    "step_name" => $this->configUi[$this->jenisTr]['steps'][1]['label'],
                    "group_code" => $this->configUi[$this->jenisTr]['steps'][1]['userGroup'],
                    "oleh_id" => "-100",
                    "oleh_nama" => "sys",
                    "keterangan" => $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh sys",
                    "transaksi_id" => $insertID,
                )) or die("Failed to write signature");

                $mongoList['sign'][] = $dwsign;
                $idHis = array(
                    $stepNumber => array(
                        "step" => $stepNumber,
                        "trID" => $insertID,
                        "nomer" => $tmpNomorNota,
                        "counters" => $appliedCounters,
                        "counters_intext" => $appliedCounters_inText,
                    ),
                );
                $idHis_blob = blobEncode($idHis);
                $idHis_intext = print_r($idHis, true);
                $tr = new MdlTransaksi();
                $dupState = $tr->updateData(array("id" => $insertID), array(
                    "next_step_num" => $nextProp['num'],
                    "next_step_code" => $nextProp['code'],
                    "next_step_label" => $nextProp['label'],
                    "next_group_code" => $nextProp['groupID'],

                    //===references
                    "id_master" => $insertID,
                    "id_top" => $insertID,
                    "ids_prev" => "",
                    "ids_prev_intext" => "",
                    "nomer_top" => $main['nomer'],
                    "nomers_prev" => "",
                    "nomers_prev_intext" => "",
                    "jenises_prev" => "",
                    "jenises_prev_intext" => "",
                    "ids_his" => $idHis_blob,
                    "ids_his_intext" => $idHis_intext,
                )) or die("Failed to update tr next-state!");

                $addValues = array(
                    //===references
                    "id_master" => $insertID,
                    "id_top" => $insertID,
                    "ids_prev" => "",
                    "ids_prev_intext" => "",
                    "nomer_top" => $main['nomer'],
                    "nomers_prev" => "",
                    "nomers_prev_intext" => "",
                    "jenises_prev" => "",
                    "jenises_prev_intext" => "",
                    "ids_his" => $idHis_blob,
                    "ids_his_intext" => $idHis_intext,
                );
                foreach ($addValues as $key => $val) {
                    $tableIn_master[$key] = $val;
                }

                $injectors_items = array(
                    "transaksi_id" => $insertID,
                    "transaksi_no" => $tmpNomorNota,
                    "nomer" => $tmpNomorNota,
                );
            }
            if (sizeof($tableIn_master_values) > 0) {
                if (isset($this->configCore[$this->jenisTr]['tableIn']['mainValues'])) {
                    $inserMainValues = array();
                    foreach ($this->configCore[$this->jenisTr]['tableIn']['mainValues'] as $key => $src) {
                        if (isset($tableIn_master_values[$key])) {
                            $dd = $tr->writeMainValues($insertID, array(
                                "key" => $key,
                                "value" => $tableIn_master_values[$key],
                            ));
                            $inserMainValues[] = $dd;
                            $mongoList['mainValues'][] = $dd;
                        }
                    }
                    if (sizeof($inserMainValues) > 0) {
                        $arrBlob = blobEncode($inserMainValues);
                        $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                    }
                }
            }
            if (sizeof($main_add_values) > 0) {
                $inserMainValues = array();
                foreach ($main_add_values as $key => $val) {
                    $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                    $inserMainValues[] = $dd;
                    $mongoList['mainValues'][] = $dd;
                }
                if (sizeof($inserMainValues) > 0) {
                    $arrBlob = blobEncode($inserMainValues);
                    $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                }

                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
            }
            if (sizeof($main_inputs) > 0) {
                foreach ($main_inputs as $key => $val) {
                    $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                    $inserMainValues[] = $dd;
                    $mongoList['mainValues'][] = $dd;
                }
                if (sizeof($inserMainValues) > 0) {
                    $arrBlob = blobEncode($inserMainValues);
                    $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                }
                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
            }
            if (sizeof($main_add_fields) > 0) {
                foreach ($main_add_fields as $key => $val) {
                    $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
                }
                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
            }
            if (sizeof($main_elements) > 0) {
                foreach ($main_elements as $elName => $aSpec) {
                    $tr->writeMainElements($insertID, array(
                        "mdl_name" => isset($aSpec['mdl_name']) ? $aSpec['mdl_name'] : "",
                        "key" => isset($aSpec['key']) ? $aSpec['key'] : 0,
                        "value" => isset($aSpec['value']) ? $aSpec['value'] : "",
                        "name" => $aSpec['name'],
                        "label" => isset($aSpec['label']) ? $aSpec['label'] : "",
                        "contents" => isset($aSpec['contents']) ? $aSpec['contents'] : "",
                        "contents_intext" => isset($aSpec['contents_intext']) ? $aSpec['contents_intext'] : "",
                    ));

                    //==nebeng bikin inputLabels
                    $currentValue = "";
                    switch ($aSpec['elementType']) {
                        case "dataModel":
                            $currentValue = $aSpec['key'];
                            break;
                        case "dataField":
                            $currentValue = $aSpec['value'];
                            break;
                    }
                    if (array_key_exists($elName, $relOptionConfigs)) {
                        if (isset($relOptionConfigs[$elName][$currentValue])) {
                            if (sizeof($relOptionConfigs[$elName][$currentValue]) > 0) {
                                foreach ($relOptionConfigs[$elName][$currentValue] as $oValueName => $oValSpec) {
                                    $inputLabels[$oValueName] = $oValSpec['label'];
                                    if (isset($oValSpec['auth'])) {
                                        if (isset($oValSpec['auth']['groupID'])) {
                                            $inputAuthConfigs[$oValueName] = $oValSpec['auth']['groupID'];
                                        }
                                    }
                                }
                            }
                        }
                        else {
                            //						cekKuning("option $currentValue pada $eName TIDAK ada pilihannya");
                        }
                    }
                }
            }
            if (sizeof($tableIn_detail) > 0) {
                $insertIDs = array();
                $insertDeIDs = array();
                $arrLastQuery_tableIn_detail = array();
                foreach ($tableIn_detail as $dSpec) {
                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                    $arrLastQuery_tableIn_detail[$insertDetailID] = $this->db->last_query();
                    $insertIDs[] = $insertDetailID;
                    $insertDeIDs[$insertID][] = $insertDetailID;
                    $mongoList['detail'][] = $insertDetailID;
                    if ($epID != 999) {
                        $insertEpID = $tr->writeDetailEntries($epID, $dSpec);
                        $insertIDs[] = $insertEpID;
                        $insertDeIDs[$epID][] = $insertEpID;
                        $mongoList['detail'][] = $insertEpID;
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
            if (sizeof($tableIn_detail2) > 0) {
                $insertIDs = array();
                foreach ($tableIn_detail2 as $dSpec) {
                    $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                    $mongoList['detail'] = $insertIDs;
                    if ($epID != 999) {
                        $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                        $mongoList['detail'] = $insertIDs;
                    }
                }
            }
            if (sizeof($tableIn_detail2_sum) > 0) {
                $insertIDs = array();
                foreach ($tableIn_detail2_sum as $dSpec) {
                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                    $insertIDs[] = $insertDetailID;
                    $mongoList['detail'][] = $insertDetailID;

                    if ($epID != 999) {
                        $insertDetailID = $tr->writeDetailEntries($epID, $dSpec);
                        $insertIDs[] = $insertDetailID;
                        $mongoList['detail'][] = $insertDetailID;
                    }
                }
                cekOrange($this->db->last_query());
            }
            if (sizeof($tableIn_detail_rsltItems) > 0) {
                $insertIDs = array();
                foreach ($tableIn_detail_rsltItems as $dSpec) {
                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                    $insertIDs[] = $insertDetailID;
                    $mongoList['detail'][] = $insertDetailID;
                    if ($epID != 999) {
                        $insertDetailID = $tr->writeDetailEntries($epID, $dSpec);
                        $insertIDs[] = $insertDetailID;
                        $mongoList['detail'][] = $insertDetailID;
                    }
                    cekUngu($this->db->last_query());
                }
            }
            if (sizeof($tableIn_detail_values) > 0) {
                foreach ($tableIn_detail_values as $pID => $dSpec) {
                    if (isset($this->configCore[$this->jenisTr]['tableIn']['detailValues'])) {
                        $insertIDs = array();
                        foreach ($this->configCore[$this->jenisTr]['tableIn']['detailValues'] as $key => $src) {
                            if (isset($tableIn_detail[$pID])) {
                                $dd = $tr->writeDetailValues($insertID, array(
                                    "produk_jenis" => $tableIn_detail[$pID]['produk_jenis'],
                                    "produk_id" => $pID,
                                    "key" => $key,
                                    "value" => $dSpec[$src],
                                ));
                                $insertIDs[$pID][] = $dd;
                                $mongoList['detailValues'][] = $dd;
                            }
                            cekLime($this->db->last_query());
                        }
                        if (sizeof($insertIDs) > 0) {
                            $arrBlob = blobEncode($insertIDs);
                            $this->db->query("UPDATE transaksi SET indexing_detail_values = '$arrBlob' WHERE id=$insertID");
                        }
                    }
                }
            }
            if (sizeof($tableIn_detail_values2_sum) > 0) {
                foreach ($tableIn_detail_values2_sum as $pID => $dSpec) {
                    if (isset($this->configCore[$this->jenisTr]['tableIn']['detailValues2_sum'])) {
                        foreach ($this->configCore[$this->jenisTr]['tableIn']['detailValues2_sum'] as $key => $src) {
                            $dd = $tr->writeDetailValues($insertID, array(
                                "produk_jenis" => $tableIn_detail2_sum[$pID]['produk_jenis'],
                                "produk_id" => $pID,
                                "key" => $key,
                                "value" => $dSpec[$src],
                            ));
                            $insertIDs[] = $dd;
                            $mongoList['detailValues'][] = $dd;
                        }
                    }
                }
            }
            //endregion

            //===components akan langsung dieksekusi jika steps-nya tidak pakai approval
            $steps = $this->configUi[$this->jenisTr]['steps'];

            $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
            $filterNeeded = false;

            //====registri value-gate
            $baseRegistries = array(
                'main' => sizeof($main) > 0 ? $main : array(),
                'items' => sizeof($items) > 0 ? $items : array(),
                'items2' => sizeof($items2) > 0 ? $items2 : array(),
                'items2_sum' => sizeof($items2_sum) > 0 ? $items2_sum : array(),
                'items3' => sizeof($items3) > 0 ? $items3 : array(),
                'items3_sum' => sizeof($items3_sum) > 0 ? $items3_sum : array(),
                'items4_sum' => isset($items4_sum) && count($items4_sum) > 0 ? $items4_sum : array(),
                'rsltItems' => sizeof($rsltItems) > 0 ? $rsltItems : array(),
                'rsltItems2' => sizeof($rsltItems2) > 0 ? $rsltItems2 : array(),
                'tableIn_master' => sizeof($tableIn_master) > 0 ? $tableIn_master : array(),
                'tableIn_detail' => sizeof($tableIn_detail) > 0 ? $tableIn_detail : array(),
                'tableIn_detail2_sum' => sizeof($tableIn_detail2_sum) > 0 ? $tableIn_detail2_sum : array(),
                'tableIn_detail_rsltItems' => sizeof($tableIn_detail_rsltItems) > 0 ? $tableIn_detail_rsltItems : array(),
                'tableIn_detail_rsltItems2' => sizeof($tableIn_detail_rsltItems2) > 0 ? $tableIn_detail_rsltItems2 : array(),
                'tableIn_master_values' => sizeof($tableIn_master_values) > 0 ? $tableIn_master_values : array(),
                'tableIn_detail_values' => sizeof($tableIn_detail_values) > 0 ? $tableIn_detail_values : array(),
                'tableIn_detail_values_rsltItems' => isset($tableIn_detail_values_rsltItems) && count($tableIn_detail_values_rsltItems) > 0 ? $tableIn_detail_values_rsltItems : array(),
                'tableIn_detail_values_rsltItems2' => isset($tableIn_detail_values_rsltItems2) && count($tableIn_detail_values_rsltItems2) > 0 ? $tableIn_detail_values_rsltItems2 : array(),
                'tableIn_detail_values2_sum' => sizeof($tableIn_detail_values2_sum) > 0 ? $tableIn_detail_values2_sum : array(),
                'main_add_values' => sizeof($main_add_values) > 0 ? $main_add_values : array(),
                'main_add_fields' => sizeof($main_add_fields) > 0 ? $main_add_fields : array(),
                'main_elements' => sizeof($main_elements) > 0 ? $main_elements : array(),
                'main_inputs' => sizeof($main_inputs) > 0 ? $main_inputs : array(),
                'main_inputs_orig' => sizeof($main_inputs) > 0 ? $main_inputs : array(),
                "receiptDetailFields" => isset($this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptDetailFields'][1]) ? $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptDetailFields'][1] : array(),
                "receiptSumFields" => isset($this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields'][1]) ? $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields'][1] : array(),
                "receiptDetailFields2" => isset($this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptDetailFields2'][1]) ? $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptDetailFields2'][1] : array(),
                "receiptSumFields2" => isset($this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields2'][1]) ? $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields2'][1] : array(),
            );

//            echo json_encode($baseRegistries);
//            matiHere(__LINE__);
            $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
            $baseRegistriesQuery = $this->db->last_query();
            showLast_query("biru");
            $mongRegID[] = $doWriteReg;
            //endregion

            //region processing sub-post-processors, always items
            $iterator = isset($this->configCore[$this->jenisTr]['postProcessor'][$jenisTrTarget]['detail']) ? $this->configCore[$this->jenisTr]['postProcessor'][$jenisTrTarget]['detail'] : array();
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    $tmpOutParams[$cCtr] = array();
                    foreach ($gate[$srcGateName] as $cnt => $dSpec) {
                        foreach ($injectors_items as $ikey => $ival) {
                            $gate[$srcGateName][$cnt][$ikey] = $ival;
                        }

                        $subParams = array();
                        if (isset($tComSpec['loop'])) {
                            foreach ($tComSpec['loop'] as $key => $value) {
                                $realValue = makeValue($value, $gate[$srcGateName][$cnt], $gate[$srcGateName][$cnt], 0);
                                $subParams['loop'][$key] = $realValue;
                            }
                        }
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {
                                $realValue = makeValue($value, $gate[$srcGateName][$cnt], $gate[$srcGateName][$cnt], 0);
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
                                $jenis = $gate['main']['jenis'];
                                foreach ($paramForceFillers[$comName] as $k => $v) {
                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                }
                            }
                            $subParams['static']["fulldate"] = date("Y-m-d");
                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                            $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . (isset($this->session->login['nama']) ? $this->session->login['nama'] : "sys");
                        }

                        if (sizeof($subParams) > 0) {
                            $tmpOutParams[$cCtr][] = $subParams;
                        }
//                                        echo "<script>top.writeProgress('" . $subParams['static']['name'] . " " . $subParams['static']['extern_nama'] . " " . $subParams['static']['nama'] . "');</script>";
                    }
                }

                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    $mdlName = "Com" . ucfirst($comName);
                    $this->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();
                    $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    $m->exec() or die("Gagal saat berusaha exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                }
            }
            //endregion

            // endregion
            //region writelog
            $this->load->model("Mdls/" . "MdlActivityLog");
            $hTmp = new MdlActivityLog();
            $tmpHData = array(
                "title" => $main['jenisTrName'],
                "sub_title" => "SPK ($no_spk)",
                "uid" => "-100",
                "uname" => "sys",
                "dtime" => date("Y-m-d H:i:s"),
                "transaksi_id" => $insertID,
                "deskripsi_old" => "",
                "deskripsi_new" => "",
                "jenis" => $this->jenisTr . 'r',
                "ipadd" => $_SERVER['REMOTE_ADDR'],
                "devices" => $_SERVER['HTTP_USER_AGENT'],
                "category" => "transaksi",
                "controller" => "AutoPostingBiaya",
                "method" => "index",
                "url" => "",
            );
            $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
        }
        else {
            cekOrange('gak bikin transaksi mungkin nilai udh abiss wkwkwk');
            $result = array(
                "status" => 0,
                "reason" => "tidak ada arrItems nya",
                "arrItems" => $arrItems,
                "line" => __LINE__,
            );
            echo json_encode($result);
            die();
        }

        //endregion


//        cekOrange("======================================= batas bawah ASSET =======================================");
        //endregion

        $nextStepNumTarget = 2;
        $autoNextStep = isset($this->configUi[$this->jenisTr]["steps"][$nextStepNumTarget]["autoNextStep"]) ? $this->configUi[$this->jenisTr]["steps"][$nextStepNumTarget]["autoNextStep"] : false;
        if ($autoNextStep == true) {
            cekHijau("mulai AUTO OTORISASI");
            $returnTransaksi = $this->autoOtorisasi($this->jenisTr, $transaksiID_current_step, $nextStepNumTarget, "1", $itemsReplacer = array());
            arrPrintWebs($returnTransaksi);
            cekHijau("selesai AUTO OTORISASI");
            $insertConnectingID = $returnTransaksi["transaksi_id_connecting"];
        }

        //==TANDAI TASKLIST SUDAH DI POSTING BIAYA
        if($is_tasklist_tambahan){
            $this->load->model("Mdls/MdlTasklistProjectTambahan");
            $tpPost = new MdlTasklistProjectTambahan();
        }
        else{
            $this->load->model("Mdls/MdlTasklistProject");
            $tpPost = new MdlTasklistProject();
        }
        $update = array(
            "post_biaya_id" => $insertID,
            "post_biaya_no" => $tmpNomorNota,
            "post_biaya_dtime" => date("Y-m-d H:i:s"),
        );
        $where = array(
            "no_spk" => $no_spk,
            "post_biaya_id" => 0,
            "progress_id" => 3,
            "progress_percent>" => 95,
        );
        $tpPost->setFilters(array());
        $writeUpdate = $tpPost->updateData($where, $update) or matiHere("gagal memperbaharui data LINE: " . __LINE__);
        $updateTaskQuery = $this->db->last_query();
        //==TANDAI TASKLIST SUDAH DI POSTING BIAYA

//        mati_disini("============= BELUM COMMIT AUTO TRANSAKSI ===============");
//        if (isset($_GET['debug'])) {
//            mati_disini("============= BELUM COMMIT SEWA COA DEBUG ===============");
//        }
//        $commit = 1;
//        matiHere(__LINE__);

        $debug = 0;

        if($debug){
            $commit = 1;
        }
        else{
            $commit = $this->db->trans_complete() or die("Gagal saat berusaha commit transaction! || LINE: " . __LINE__);
        }

        if($commit){
            $result = array(
                "debug" => $debug,
                "status" => $commit,
                "reason" => "biaya berhasil diposting",
                "updateTaskQuery" => "$updateTaskQuery",
            );
            echo json_encode($result);
        }
        else{
            $result = array(
                "status" => 0,
                "debug" => $debug,
                "reason" => "gagal posting biaya ($no_spk)",
                "line" => __LINE__,
            );
            echo json_encode($result);
        }

//        cekHijau("======================================= BERHASIL COMMIT =======================================");

    }
    public function autoOtorisasi($jenisTr, $no, $stepNum, $stepNumCurrent, $itemsReplacer = array())
    {
        $transaksiID_reference = $masterID = $no;
        $nextStepNum = $stepNum + 1;
        $itemsReplacerQty = array();
        if (sizeof($itemsReplacer) > 0) {
            foreach ($itemsReplacer as $pid => $spec) {
                $itemsReplacerQty[$pid] = $spec["jml"];
            }
        }

        $paramPatchers = $this->config->item('heTransaksi_paramPatchers') != null ? $this->config->item('heTransaksi_paramPatchers') : array();
        $paramForceFillers = $this->config->item('heTransaksi_paramForceFillers') != null ? $this->config->item('heTransaksi_paramForceFillers') : array();
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();
        $stepNowParameter = array();
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("id in (" . implode(",", explode("-", $no)) . ")");
//        $tr->addFilter("step_number='" . $stepNumCurrent . "'");
        $tr->addFilterJoin("transaksi_data.trash='0'");
        $tmpTr = $tr->lookupJoined();
//        showLast_query("biru");
        $conData = array();
        if (sizeof($tmpTr) > 0) {
            $extractedItems = array();//==untuk urusan update transaksi referer
            $validItems = array();
            $validItemSends = array();
            $validItemReqCancels = array();
            $validItemCancels = array();
            $validItemPreCancels = array();
            $validItemSents = array();
            foreach ($tmpTr as $row) {
                if ($row->valid_qty > 0) {
                    if (!isset($validItems[$row->produk_id])) {
                        $validItems[$row->produk_id] = 0;
                    }
                    if (!isset($validItemSends[$row->produk_id])) {
                        $validItemSends[$row->produk_id] = 0;
                    }
                    if (!isset($validItemCancels[$row->produk_id])) {
                        $validItemCancels[$row->produk_id] = 0;
                    }
                    if (!isset($validItemReqCancels[$row->produk_id])) {
                        $validItemReqCancels[$row->produk_id] = 0;
                    }
                    if (!isset($validItemPackeds[$row->produk_id])) {
                        $validItemPackeds[$row->produk_id] = 0;
                    }
                    if (!isset($validItemPreCancels[$row->produk_id])) {
                        $validItemPreCancels[$row->produk_id] = 0;
                    }

                    $validItems[$row->produk_id] += isset($row->valid_qty) ? $row->valid_qty : 0;
                    $validItemSends[$row->produk_id] += isset($arrTmp__['582spd'][$row->produk_id]) ? $arrTmp__['582spd'][$row->produk_id] : 0;
                    $validItemCancels[$row->produk_id] += isset($row->cancel_qty) ? $row->cancel_qty : 0;
                    $validItemReqCancels[$row->produk_id] += isset($row->req_cancel_qty) ? $row->req_cancel_qty : 0;
                    $validItemPreCancels[$row->produk_id] += isset($arrPreTmp__['1982'][$row->produk_id]) ? $arrPreTmp__['1982'][$row->produk_id] : 0;
                    $validItemPackeds[$row->produk_id] += isset($arrTmp__['582pkd'][$row->produk_id]) ? $arrTmp__['582pkd'][$row->produk_id] : 0;

                    if (!isset($extractedItems[$row->produk_id])) {
                        $extractedItems[$row->produk_id] = array();
                    }
                    $extractedItems[$row->produk_id][$row->id_detail] = array(
                        "id" => $row->id_detail,
                        "produk_id" => $row->produk_id,
                        "qty" => $row->produk_ord_jml,
                        "valid_qty" => $row->valid_qty,
                        "transaksi_id" => $row->transaksi_id,
                        "packed_qty" => isset($arrTmp__['582pkd'][$row->produk_id]) ? $arrTmp__['582pkd'][$row->produk_id] : 0,
                        "sent_qty" => isset($arrTmp__['582spd'][$row->produk_id]) ? $arrTmp__['582spd'][$row->produk_id] : 0,
                        "req_cancel_qty" => isset($arrPreTmp__['1982'][$row->produk_id]) ? $arrPreTmp__['1982'][$row->produk_id] : 0,
                        "cancel_qty" => $row->cancel_qty,
                        "outstanding" => $row->produk_ord_jml - ($row->produk_ord_jml - $row->valid_qty),
                    );
                }
            }
            $this->jenisTr = $tmpTr[0]->jenis_master;
            $masterID = $tmpTr[0]->id_master;
            $topID = $tmpTr[0]->id_top;
            $tmpNomorNota = $tmpTr[0]->nomer;
            $origJenis = $tmpTr[0]->jenis_master;
            $pengirimID = $tmpTr[0]->pengirim_id;
            $pengirimName = $tmpTr[0]->pengirim_nama;
            //--------------------------------
            $gudangStatusJenis = $tmpTr[0]->gudang_status_jenis;
            $cabangTujuanID = $tmpTr[0]->cabang_id;

            $trID = $tmpTr[0]->transaksi_id;
            $cCode = "_TR_" . $this->jenisTr;
            if (isset($conData[$cCode])) {
                $conData[$cCode] = null;
                unset($conData[$cCode]);
            }
            //region session init
            if (!isset($conData[$cCode])) {
                $conData[$cCode] = array(
                    "items" => array(),
                    "main" => array(),
                );
            }
            if (!isset($conData[$cCode]['main'])) {
                $conData[$cCode]['main'] = array();
            }
            if (!isset($conData[$cCode]['items'])) {
                $conData[$cCode]['items'] = array();
            }
            //endregion
            $conData[$cCode]['extractedItems'] = $extractedItems;


            $configUiMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiUi");
            $configCoreMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiCore");
            $configLayoutMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiLayout");
            $configValuesMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiValues");

            $configUiMasterModulOrigJenis = loadConfigModulJenis_he_misc($origJenis, "coTransaksiUi");
            $configCoreMasterModulOrigJenis = loadConfigModulJenis_he_misc($origJenis, "coTransaksiCore");
            $configLayoutMasterModulOrigJenis = loadConfigModulJenis_he_misc($origJenis, "coTransaksiLayout");


            $jenisTrTarget = isset($configUiMasterModulJenis["steps"][$stepNum]["target"]) ? $configUiMasterModulJenis["steps"][$stepNum]["target"] : NULL;
            $detailValuesConfig = isset($configCoreMasterModulJenis['tableIn']['detailValues']) ? $configCoreMasterModulJenis['tableIn']['detailValues'] : array();
            $additionalData = isset($configUiMasterModulJenis["addDetailData"][$stepNum]) ? $configUiMasterModulJenis["addDetailData"][$stepNum] : array();

            $totalSteps = sizeof($configUiMasterModulJenis['steps']);
            //==references, previous entry
            $prevProp = array(
                "id" => $tmpTr[0]->transaksi_id,
                "jenis" => $tmpTr[0]->jenis,
                "nomer" => $tmpTr[0]->nomer,
            );
            //------
            $stepNowParameter = array(
                "next_step_code" => $tmpTr[0]->next_step_code,
                "next_step_label" => $tmpTr[0]->next_step_label,
                "next_group_code" => $tmpTr[0]->next_group_code,
                "next_step_num" => $tmpTr[0]->next_step_num,
                "step_current" => $tmpTr[0]->step_current,
            );
            $tmpVal_main = $tr->lookupMainValuesByTransID($trID)->result();
            $tmpVal_detail = $tr->lookupDetailValuesByTransID($trID)->result();
            $mainValues = array();
            if (sizeof($tmpVal_main) > 0) {
                foreach ($tmpVal_main as $row) {
                    $mainValues[$row->key] = $row->value;
                }
            }
            $detailValues = array();
            if (sizeof($tmpVal_detail) > 0) {
                foreach ($tmpVal_detail as $row) {
                    $detailValues[$row->produk_id][$row->key] = $row->value;
                }
            }

            $main = array();
            $items = array();
            $prevIDs = array();
            $prevNos = array();
            foreach ($tmpTr as $row) {
                $items[$row->produk_id] = array(
                    "id" => $row->produk_id,
                    "nama" => $row->produk_nama,
                    "jml" => $row->produk_ord_jml,
                    "harga" => $row->produk_ord_hrg,
                    "valid_qty" => $row->valid_qty,
                    "transaksi_id" => $row->transaksi_id,
                    "nomer" => $row->nomer,
                );
                if ($row->valid_qty > 0) {
                    cekHitam("ok lanjut");
                }
                else {
                    if (isset($conData[$cCode]['items'][$row->produk_id])) {
                        matiHere("Followed up already. Please close and refresh your browser " . $row->produk_nama . " " . $row->produk_id);//kalo session active ya harus dimatiin biar gak dobel
                    }
                }
                if (!in_array($row->transaksi_id, $prevIDs)) {
                    $prevIDs[] = $row->transaksi_id;
                }
                if (!in_array($row->nomer, $prevNos)) {
                    $prevNos[] = $row->nomer;
                }
                if (sizeof($detailValuesConfig) > 0) {
//                    echo "detail values ada..<br>";
                    foreach ($detailValuesConfig as $key => $src) {
//                        echo "$key akan ambil nilai dari $src<br>";
//                        echo "<script>top.writeProgress('$key akan ambil nilai dari $src');</script>";
                        //                            $tmp[$key]=isset($iSpec[$val])?$iSpec[$val]:0;
                        if (isset($detailValues[$row->produk_id][$key])) {
                            //                            $tmp[$key] = formatField($key, $detailValues[$row->produk_id][$key]);
                            $items[$row->produk_id][$key] = $detailValues[$row->produk_id][$key];
                        }
                        else {
                            if (isset($row->$key)) {
                                //                                $tmp[$key] = formatField($key, $row->$key);
                                $items[$row->produk_id][$key] = $row->$key;
                            }
                        }
//                        echo "dan sekarang nilainya: " . $items[$row->produk_id][$key] . "<br>";
//                        echo "<script>top.writeProgress('dan sekarang nilainya: " . $items[$row->produk_id][$key] . "');</script>";
                    }
                }
            }

            //region take from registries
            $trr = new MdlTransaksi();
            $trr->setFilters(array());
            $trr->addFilter("transaksi_id in (" . implode(",", explode("-", $no)) . ")");
            $tmpReg = $trr->lookupDataRegistries()->result();

            $main = array();
            $items = array();
            $items2 = array();
            $items2_sum = array();
            $items3 = array();
            $items3_sum = array();
            $items4_sum = array();
            $rsltItems = array();
            $rsltItems2 = array();

            $masterGates = array();
            $childGates = array();
            $childGates2 = array();
            $childGates2_sum = array();
            $childGatesRsltItems = array();
            $childGatesRsltItems2 = array();
            $masterTableInParams = array();
            $childTableInParams = array();
            $childTableInParamsRsltItems = array();
            $childTableInParamsRsltItems2 = array();
            $masterTableInValueParams = array();
            $childTableInValueParams = array();
            $childTableInValueParamsRsltItems = array();
            $childTableInValueParamsRsltItems2 = array();
            $masterAddValues = array();
            $masterAddFields = array();
            $mainElements = array();
            $mainInputs = array();
            $itemsKomposisi = array();
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $row) {
                    foreach ($row as $key_reg => $val_reg) {
                        switch ($key_reg) {
                            case "main"://
                                $main = $main + unserialize(base64_decode($val_reg));
                                break;
                            case "items"://
                                $items = $items + unserialize(base64_decode($val_reg));
                                break;
                            case "items2"://
                                $items2 = $items2 + unserialize(base64_decode($val_reg));
                                break;
                            case "rsltItems"://
                                $rsltItems = $rsltItems + unserialize(base64_decode($val_reg));
                                break;
                            case "rsltItems2"://
                                $rsltItems2 = $rsltItems2 + unserialize(base64_decode($val_reg));
                                break;
                            case "items2_sum"://
                                $items2_sum = $items2_sum + unserialize(base64_decode($val_reg));
                                break;
                            case "items3"://
                                $items3 = $items3 + unserialize(base64_decode($val_reg));
                                break;
                            case "items3_sum"://
                                $items3_sum = $items3_sum + unserialize(base64_decode($val_reg));
                                break;
                            case "items4_sum"://
                                $items4_sum = $items4_sum + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_master"://
                                $masterTableInParams = $masterTableInParams + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_detail"://
                                $childTableInParams = $childTableInParams + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_detail_rsltItems"://
                                $childTableInParamsRsltItems = $childTableInParamsRsltItems + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_detail_rsltItems2"://
                                $childTableInParamsRsltItems2 = $childTableInParamsRsltItems2 + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_master_values"://
                                $masterTableInValueParams = $masterTableInValueParams + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_detail_values"://
                                $childTableInValueParams = $childTableInValueParams + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_detail_values_rsltItems"://
                                $childTableInValueParamsRsltItems = $childTableInValueParamsRsltItems + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_detail_values_rsltItems2"://
                                $childTableInValueParamsRsltItems2 = $childTableInValueParamsRsltItems2 + unserialize(base64_decode($val_reg));
                                break;
                            case "main_add_values"://
                                $masterAddValues = $masterAddValues + unserialize(base64_decode($val_reg));
                                break;
                            case "main_add_fields"://
                                $masterAddFields = $masterAddFields + unserialize(base64_decode($val_reg));
                                break;
                            case "main_elements"://
                                $mainElements = unserialize(base64_decode($val_reg));
                                break;
                            case "main_inputs"://
                                $mainInputs = unserialize(base64_decode($val_reg));
                                break;
                            case "items_komposisi"://
                                $itemsKomposisi = unserialize(base64_decode($val_reg));
                                break;
                        }
                    }
                }

            }
            else {
                die("Cannot read the registry entries from $masterID!");
            }
            //endregion

            $masterReplacers = array(
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $masterTableInParams['jenis_top'],
                "harga" => 0,
                "masterID" => $masterID,
            );
            foreach ($masterReplacers as $key => $src) {
                $main[$key] = $src;
                $mainValues[$key] = $src;
                $masterGates[$key] = $src;
            }
            if (sizeof($itemsReplacerQty) > 0) {
                foreach ($itemsReplacerQty as $pid => $qty) {
                    if (array_key_exists($pid, $items)) {
                        $items[$pid]["qty"] = $qty;
                        $items[$pid]["jml"] = $qty;
                    }
                }
            }
            //region session-swapper
            $main["pengirimID"] = $pengirimID;
            $main["pengirimName"] = $pengirimName;
//            $main["cabang_id"] = $main["placeID"];
//            $main["cabang_nama"] = $main["placeName"];
//            $main["place2ID"] = "-1";
//            $main["place2Name"] ="pusat";
//            $main["cabang2_id"] = "-1";
//            $main["cabang2_nama"] ="pusat";
            $swappers = array(
                "main" => $main,
                "items" => $items,
                "items2" => $items2,
                "items2_sum" => $items2_sum,
                "items3" => $items3,
                "items3_sum" => $items3_sum,
                "items4_sum" => $items4_sum,
                "items_child" => $itemChildData,
                "rsltItems" => $rsltItems,
                "rsltItems2" => $rsltItems2,
                "extractedItems" => $extractedItems,

                "tableIn_master" => $masterTableInParams,
                "tableIn_detail" => $childTableInParams,
                "tableIn_detail_rsltItems" => $childTableInParamsRsltItems,
                "tableIn_detail_rsltItems2" => $childTableInParamsRsltItems2,
                "tableIn_master_values" => $masterTableInValueParams,
                "tableIn_detail_values" => $childTableInValueParams,
                "tableIn_detail_values_rsltItems" => $childTableInValueParamsRsltItems,
                "tableIn_detail_values_rsltItems2" => $childTableInValueParamsRsltItems2,
                "main_add_values" => $masterAddValues,
                "main_add_fields" => $masterAddFields,
                "main_elements" => $mainElements,
                "main_inputs" => $mainInputs,
                "extSteps" => $extSteps,
                "paySrcs" => $paySrcs,
                "lockerPayment" => $tempBtnUndo,
                "items_komposisi" => $itemsKomposisi,
            );
            foreach ($swappers as $targetVar => $src) {
                $conData[$cCode][$targetVar] = $src;

            }
            //endregion


            // region copy gerbang serial dari distribusi
            $shoppingCartCopySerialNumber = isset($configUiMasterModulJenis["shoppingCartCopySerialNumber"][$stepNum]) ? $configUiMasterModulJenis["shoppingCartCopySerialNumber"][$stepNum] : array();
            if (sizeof($shoppingCartCopySerialNumber) > 0) {
                $statusGudangConfig = $shoppingCartCopySerialNumber["statusGudang"];
                $copyGateConfig = $shoppingCartCopySerialNumber["copyGate"];
                $copyJenisConfig = $shoppingCartCopySerialNumber["copyJenis"];
                if ($gudangStatusJenis == $statusGudangConfig) {
                    $trs = new MdlTransaksi();
                    $trs->addFilter("jenis='$copyJenisConfig'");
                    $trs->addFilter("reference_id_top='$topID'");
                    $trsTmp = $trs->lookupAll()->result();
                    showLast_query("biru");
                    $trsID = $trsTmp[0]->id;

                    $trs = new MdlTransaksi();
                    $trs->setFilters(array());
                    $trs->setJointSelectFields($copyGateConfig);
                    $trs->addFilter("transaksi_id='$trsID'");
                    $tmpReg = $trs->lookupDataRegistries()->result();
                    showLast_query("biru");
                    if (sizeof($tmpReg) > 0) {
                        foreach ($tmpReg as $row) {
                            foreach ($row as $key_reg => $val_reg) {
                                $conData[$cCode][$key_reg] = blobDecode($val_reg);
                            }
                        }
                    }
                }

            }
            // endregion copy gerbang serial dari distribusi


            $ppnFactor = isset($conData[$cCode]["main"]["ppnFactor"]) ? $conData[$cCode]["main"]["ppnFactor"] : 11;

            $this->load->helper("he_value_builder");
            resetValues($this->jenisTr);
            fillValues_he_value_builder($this->jenisTr, $stepNumCurrent, $stepNum, $configCoreMasterModulJenis, $configUiMasterModulJenis, $configValuesMasterModulJenis, $ppnFactor);


            //region pembulatan replacer disini
            $injectBulat = isset($configCoreMasterModulJenis['valuePembulatan'][$stepNum]) ? $configCoreMasterModulJenis['valuePembulatan'][$stepNum] : array();
            if (sizeof($injectBulat) > 0) {
//                echo "<script>top.writeProgress('PEMBULATAN', 'HEAD');</script>";
                //            arrPrint($injectBulat);
                $selectedSource = $injectBulat['source'];
                $injectSource = makeDppBulat($conData[$cCode]['main'][$selectedSource]);
                foreach ($injectBulat['replacer'] as $k => $fields) {
                    $conData[$cCode]['main'][$fields] = $injectSource[$k];
//                    echo "<script>top.writeProgress('PEMBULATAN ($fields)');</script>";
                }

            }
            //endregion

//            cekMerah(":: MEMULAI PRE-PROCC ITEMS...");
            $ppnFactor = isset($conData[$cCode]["main"]["ppnFactor"]) ? $conData[$cCode]["main"]["ppnFactor"] :11;

            //region pre-processors (item)
            if (isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['detail'])) {
                $iterator = isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['detail']) ? $configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['detail'] : array();
                $itemNumLabels = isset($configUiMasterModulJenis['shoppingCartNumFields'][$stepNum]) ? $configUiMasterModulJenis['shoppingCartNumFields'][$stepNum] : array();
//                echo "ITEM NUM LABELS";

                if (sizeof($iterator) > 0) {
//                    echo "<script>top.writeProgress('PERSIAPAN PRE-PROCESSOR...', 'HEAD');</script>";
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
//                        echo __LINE__ . " :: sub-preproc: $comName, initializing values <br>";

                        foreach ($conData[$cCode][$srcGateName] as $xid => $dSpec) {
                            $tmpOutParams[$cCtr] = array();
                            //                        $id = $dSpec['id'];
                            $id = $xid;
                            $subParams = array();

                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {

                                    $realValue = makeValue($value, $conData[$cCode][$srcGateName][$id], $conData[$cCode][$srcGateName][$id], 0);
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
                                    $jenis = $conData[$cCode]['main']['jenis'];
                                    foreach ($paramForceFillers[$comName] as $k => $v) {
                                        $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                    }
                                }

                                $subParams['static']["fulldate"] = date("Y-m-d");
                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                            }

                            if (sizeof($subParams) > 0) {

                                $tmpOutParams[$cCtr][] = $subParams;
                            }


                            $comName = $tComSpec['comName'];
                            $srcGateName = $tComSpec['srcGateName'];
                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();

//                            echo "sub preproc #$it: $comName, sending values <br>";

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
                                cekHitam(":: PRE-PROCC -> GOTNAME, ITERATING...");
                                arrprint($gotParams);
                                if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                    foreach ($gotParams as $gateName => $paramSpec) {

                                        if (!isset($conData[$cCode][$gateName])) {
                                            $conData[$cCode][$gateName] = array();
                                            //                                    cekhijau("building the session: $gateName");
                                        }
                                        else {
                                            //                                    cekhijau("NOT building the session: $gateName");
                                        }

                                        foreach ($paramSpec as $id => $gSpec) {
                                            //                                        $id = $gSpec['id'];
                                            if (!isset($conData[$cCode][$gateName][$id])) {
                                                $conData[$cCode][$gateName][$id] = array();
                                            }

                                            if (isset($conData[$cCode][$gateName][$id])) {
                                                if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                    foreach ($gSpec as $key => $val) {
                                                        $conData[$cCode][$gateName][$id][$key] = $val;
                                                    }
                                                }
                                            }
                                            //==inject gotParams to child gate
                                            if ($gateName == $srcGateName) {
                                                if (isset($conData[$cCode][$srcGateName][$id])) {
                                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                        foreach ($gSpec as $key => $val) {
                                                            $conData[$cCode][$srcGateName][$id][$key] = $val;
                                                        }
                                                    }
                                                }
                                            }

                                            //cekMerah("REBUILDING VALUES..");
                                            if (sizeof($itemNumLabels) > 0) {
                                                //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                foreach ($itemNumLabels as $key => $label) {
                                                    //cekHere("$id === $key => $label");
                                                    $conData[$cCode][$gateName][$id]['sub_' . $key] = ($conData[$cCode][$gateName][$id]['jml'] * $conData[$cCode][$gateName][$id][$key]);
                                                    //                                        die();
                                                }
                                            }
                                        }
                                        //                                    arrPrint($conData[$cCode][$gateName]);die();
                                    }
                                }

                            }
                            else {
                                cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                            }
                        }

                        $this->load->helper("he_value_builder");
                        fillValues_he_value_builder($this->jenisTr, $stepNumCurrent, $stepNum, $configCoreMasterModulJenis, $configUiMasterModulJenis, $configValuesMasterModulJenis, $ppnFactor);
                    }
                }
                else {
                    //cekKuning("sub-preproc is not set");
                }


                $this->load->helper("he_value_builder");
                fillValues_he_value_builder($this->jenisTr, $stepNumCurrent, $stepNum, $configCoreMasterModulJenis, $configUiMasterModulJenis, $configValuesMasterModulJenis, $ppnFactor);
            }
            else {
                echo("no processor defined. skipping preprocessor..<br>");
            }

            //endregion

            //region pre-processors (master)
            if (isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['master'])) {
                $iterator = isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['master']) ? $configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['master'] : array();
                $itemNumLabels = isset($configUiMasterModulJenis['shoppingCartNumFields']) ? $configUiMasterModulJenis['shoppingCartNumFields'] : array();

//                echo "ITEM NUM LABELS";

                if (sizeof($iterator) > 0) {
//                    echo "<script>top.writeProgress('PERSIAPAN PRE-PROCESSOR...', 'HEAD');</script>";
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                        $switchResultParams = isset($tComSpec['switchResultParams']) ? $tComSpec['switchResultParams'] : false;

//                        echo "master-preproc: $comName, initializing values <br>";
                        $tmpOutParams[$cCtr] = array();


                        $subParams = array();
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {

                                $realValue = makeValue($value, $conData[$cCode][$srcGateName], $conData[$cCode][$srcGateName], 0);
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
                                $jenis = $conData[$cCode]['main']['jenis'];
                                foreach ($paramForceFillers[$comName] as $k => $v) {
                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                }
                            }

                            $subParams['static']["fulldate"] = date("Y-m-d");
                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                            $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
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

//                            cekbiru("gotparams dari $comName");
//                            arrprint($gotParams);

                            if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
//                                cekhijau("ada gotparam, sekarang mau replace");
                                foreach ($gotParams as $gateName => $gSpec) {

                                    if ($switchResultParams == true) {
                                        foreach ($gSpec as $id => $ggSpec) {
                                            if (!isset($conData[$cCode][$gateName][$id])) {
                                                $conData[$cCode][$gateName][$id] = array();
                                            }
                                            if (isset($conData[$cCode][$gateName][$id])) {
                                                if (is_array($ggSpec) && sizeof($ggSpec) > 0) {
                                                    foreach ($ggSpec as $key => $val) {
                                                        $conData[$cCode][$gateName][$id][$key] = $val;
                                                    }
                                                }
                                            }
                                            //cekMerah("REBUILDING VALUES..");
                                            if (sizeof($itemNumLabels) > 0) {
                                                //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                foreach ($itemNumLabels as $key => $label) {
                                                    //cekHere("$id === $key => $label");
                                                    if (isset($conData[$cCode][$gateName][$id][$key])) {
                                                        $conData[$cCode][$gateName][$id]['sub_' . $key] = ($conData[$cCode][$gateName][$id]['jml'] * $conData[$cCode][$gateName][$id][$key]);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    else {

                                        if (isset($conData[$cCode]['main'])) {
                                            if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                foreach ($gSpec as $key => $val) {
                                                    cekbiru("injecting param $key with $val");
                                                    $conData[$cCode]['main'][$key] = $val;
                                                }
                                            }
                                        }
                                        //==inject gotParams to child gate
                                        if (isset($conData[$cCode]['main'])) {
                                            if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                foreach ($gSpec as $key => $val) {
                                                    $conData[$cCode]['main'][$key] = $val;
                                                }
                                            }
                                        }
                                    }

                                }
                            }
                            else {
                                cekmerah("TIDAK ada gotparam, tidak perlu replace");
                            }

                        }
                        else {
                            cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                        }
                    }
                }
                else {
                    //cekKuning("sub-preproc is not set");
                }


                $this->load->helper("he_value_builder");
                fillValues_he_value_builder($this->jenisTr, $stepNumCurrent, $stepNum, $configCoreMasterModulJenis, $configUiMasterModulJenis, $configValuesMasterModulJenis, $ppnFactor);


            }
            else {
                echo("no processor defined. skipping preprocessor..<br>");
            }

            //endregion

            //region pre-proc value injector items2 items2_sum dari gerbang main
            $injectValues = isset($configCoreMasterModulJenis['preInjectValue'][$stepNum]) ? $configCoreMasterModulJenis['preInjectValue'][$stepNum] : array();
            if (sizeof($injectValues) > 0) {
                $iterator = isset($configCoreMasterModulJenis['preInjectValue'][$stepNum]['master']) ? $configCoreMasterModulJenis['preInjectValue'][$stepNum]['master'] : array();
                $itemNumLabels = isset($configUiMasterModulJenis['shoppingCartNumFields']) ? $configUiMasterModulJenis['shoppingCartNumFields'] : array();
                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                        //                    echo "master-preproc: $comName, initializing values <br>";
                        $tmpOutParams[$cCtr] = array();


                        $subParams = array();
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {

                                $realValue = makeValue($value, $conData[$cCode][$srcGateName], $conData[$cCode][$srcGateName], 0);
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
                                $jenis = $conData[$cCode]['main']['jenis'];
                                foreach ($paramForceFillers[$comName] as $k => $v) {
                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                }
                            }

                            $subParams['static']["fulldate"] = date("Y-m-d");
                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                            $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
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
                            if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                //                            cekhijau("ada gotparam, sekarang mau replace");
                                foreach ($gotParams as $gateName => $gSpec) {
                                    if ($gateName == "main") {
                                        foreach ($gSpec as $key => $val) {
                                            $conData[$cCode]['main'][$key] = $val;
                                        }
                                    }
                                    if ($gateName == "items2") {
                                        foreach ($conData[$cCode]['items2'] as $k => $tmpSes) {
                                            foreach ($gSpec as $key => $val) {
                                                foreach ($tmpSes as $y => $sesData) {
                                                    if (array_key_exists($key, $sesData)) {
                                                        $conData[$cCode]['items2'][$k][$y][$key] = $val;
                                                    }
                                                }
                                            }
                                        }

                                    }
                                    if ($gateName == "items2_sum") {
                                        foreach ($conData[$cCode]['items2_sum'] as $k => $tmpSes) {
                                            foreach ($gSpec as $key => $val) {
                                                $conData[$cCode]['items2_sum'][$k][$key] = $val;
                                            }
                                        }

                                    }

                                }
                            }
                            else {
                                cekmerah("TIDAK ada gotparam, tidak perlu replace");
                            }

                        }
                        else {
                            cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                        }

                    }
                }
                else {
                    //cekKuning("sub-preproc is not set");
                }

                $this->load->helper("he_value_builder");
                fillValues_he_value_builder($this->jenisTr, $stepNumCurrent, $stepNum, $configCoreMasterModulJenis, $configUiMasterModulJenis, $configValuesMasterModulJenis, $ppnFactor);

            }
            //endregion

//            $this->load->library("Validator");
//            $va = new Validator();
//            $va->setConfigUiJenis($configUiMasterModulJenis);
//            $va->setCCode($cCode);
//            $va->midValidate($stepNum);
//            $va->unionValidate();

            //region update step2an
            if (isset($configUiMasterModulJenis['steps'][$nextStepNum])) {//===masih ada langkah selanjutnya
//                echo "authorizing to next step..<br>";
                $nextProp = array(
                    "num" => $nextStepNum,
                    "code" => $configUiMasterModulJenis['steps'][$nextStepNum]['target'],
                    "label" => $configUiMasterModulJenis['steps'][$nextStepNum]['label'],
                    "groupID" => $configUiMasterModulJenis['steps'][$nextStepNum]['userGroup'],
                );
            }
            else {//==ini step terakhir, tulis komponen jika ada
                $nextProp = array(
                    "num" => 0,
                    "code" => "",
                    "label" => "",
                    "groupID" => "",
                );
            }
            //endregion

            $writeSignature_ = array(
                "nomer" => $tmpNomorNota,
                "step_number" => $stepNum,
                "step_code" => $configUiMasterModulOrigJenis['steps'][$stepNum]['target'],
                "step_name" => $configUiMasterModulOrigJenis['steps'][$stepNum]['label'],
                "group_code" => $configUiMasterModulOrigJenis['steps'][$stepNum]['userGroup'],
                "oleh_id" => $this->session->login['id'],
                "oleh_nama" => $this->session->login['nama'] . " by system",
                "keterangan" => $configUiMasterModulOrigJenis['steps'][$stepNum]['label'] . " oleh " . $this->session->login['nama'],
                "transaksi_id" => $masterID,
            );

            //==tulis signature
            $dwsign = $tr->writeSignature($masterID, $writeSignature_);

            if(!$dwsign){
                echo json_encode($writeSignature_);
                die("Failed to write signature**masterID: $masterID*");
            }

            $mongoList['sign'][] = $dwsign;
//            cekKuning($this->db->last_query());
//            matiHEre(__LINE__."****.$masterID");
            //region update step terdahulu
            $tr = new MdlTransaksi();
            $dupState = $tr->updateData(array("id" => $topID), array(
                "next_step_code" => $nextProp['code'],
                "next_step_label" => $nextProp['label'],
                "next_group_code" => $nextProp['groupID'],
                "next_step_num" => $nextProp['num'],
                "step_current" => $stepNum,
                "partial" => isset($conData[$cCode]['main']['partial']) ? $conData[$cCode]['main']['partial'] : 0,

            )) or die("Failed to update tr next-state!");
            $mongUpdateList['update']['main'][] = array(
                "where" => array("id" => "$topID"),
                "value" => array(
                    "next_step_code" => $nextProp['code'],
                    "next_step_label" => $nextProp['label'],
                    "next_group_code" => $nextProp['groupID'],
                    "next_step_num" => $nextProp['num'],
                    "step_current" => $stepNum,
                ),
            );
//            cekHijau(__LINE__ . " ::: " . $this->db->last_query());

            //-------------------------------------------------
            $tr = new MdlTransaksi();
            $dupState = $tr->updateData(array("id" => $trID), array(
                "partial" => isset($conData[$cCode]['main']['partial']) ? $conData[$cCode]['main']['partial'] : 0,
            )) or die("Failed to update tr next-state!");
            $mongUpdateList['update']['main'][] = array(
                "where" => array("id" => "$trID"),
                "value" => array(
                    "partial" => isset($conData[$cCode]['main']['partial']) ? $conData[$cCode]['main']['partial'] : 0,
                ),
            );


            //mati_disini("==== ==== ====");
            //endregion

            $modul_transaksi = $this->modul;
            $tCodeTargetJenisTransaksi = $tCode = $configUiMasterModulOrigJenis['steps'][$stepNum]['target'];
            $tCodeName = $configUiMasterModulOrigJenis['steps'][$stepNum]['label'];
            $masterReplacers = array(
                //            "referensi_id" => $masterID, (dimatikan)
                //            "id_master"       => $masterID,
                //            "id_top"          => $topID,
                "inv" => $tmpNomorNota,
                //            "jenis_top"           => $tCode,
                "jenis" => $tCode,
                "jenis_label" => $tCodeName,
                "transaksi_jenis" => $tCode,
                "cabang_id" => selectedTransactionSession() ? $conData[$cCode]['main']['cabangID'] : $this->session->login['cabang_id'],
                "cabang_nama" => selectedTransactionSession() ? $conData[$cCode]['main']['cabangName'] : $this->session->login['cabang_nama'],
                "oleh_id" => $this->session->login['id'],
                "oleh_nama" => $this->session->login['nama'] . " by system",
                "step_current" => "0",
                "step_number" => $stepNum,
                //            "next_step_code"      => "",
                //            "next_step_label"     => "",
                //            "next_group_code"     => "",
                "next_step_code" => $nextProp['code'],
                "next_step_label" => $nextProp['label'],
                "next_group_code" => $nextProp['groupID'],
                //===references
                "id_master" => $masterID,
                "id_top" => $topID,
                "ids_prev" => base64_encode(serialize($prevIDs)),
                "ids_prev_intext" => print_r($prevIDs, true),
                "nomer_top2" => isset($conData[$cCode]['main']['nomer_top2']) ? $conData[$cCode]['main']['nomer_top2'] : "",
                "nomer_top" => $conData[$cCode]['tableIn_master']['nomer_top'],
                "nomers_prev" => base64_encode(serialize($prevNos)),
                "nomers_prev_intext" => print_r($prevNos, true),
                //            "jenis_top"           => $this->jenisTr,
                "jenises_prev" => base64_encode(serialize(array($prevProp['jenis']))),
                "jenises_prev_intext" => print_r(array($prevProp['jenis']), true),
                "tail_number" => $stepNum,
                "tail_code" => $configUiMasterModulJenis['steps'][$stepNum]['target'],
            );

            foreach ($masterReplacers as $key => $val) {
                $conData[$cCode]['tableIn_master'][$key] = $val;
            }

            $childTableRepaclers = array(
                "sub_step_number" => $stepNum,
                "sub_step_current" => $stepNum,
                "sub_step_avail" => sizeof($configUiMasterModulJenis['steps']),
                "next_substep_num" => $nextProp['num'],
                "next_substep_code" => $nextProp['code'],
                "next_substep_label" => $nextProp['label'],
                "next_subgroup_code" => $nextProp['groupID'],
            );
            foreach ($conData[$cCode]['tableIn_detail'] as $id => $dSpec) {
                //			$id = $dSpec['id'];
                foreach ($childTableRepaclers as $key => $val) {
                    $conData[$cCode]['tableIn_detail'][$id][$key] = $val;
                }
            }


            $masterReplacersO = array(
                "jenisTr" => $tCode,
                "jenisTrName" => $tCodeName,
                "olehID" => $this->session->login['id'],
                "olehName" => $this->session->login['nama'] . " by system",
                "stepNumber" => $stepNum,
                "stepCode" => $tCode,
            );
            foreach ($masterReplacersO as $key => $val) {
                $conData[$cCode]['main'][$key] = $val;
            }

            //region menimbulkan nilai tagihan
            $unpaidList = null != $this->config->item('tr_unpaidList') ? $this->config->item('tr_unpaidList') : array();
            //        arrprint($conData[$cCode]['tableIn_master']);
            if (in_array($tCode, $unpaidList)) {
                $conData[$cCode]['tableIn_master']["transaksi_nilai_tagihan"] = $conData[$cCode]['tableIn_master']['transaksi_nilai'];
                $conData[$cCode]['tableIn_master']["transaksi_nilai_terbayar"] = 0;
                $conData[$cCode]['tableIn_master']["transaksi_nilai_sisa"] = ($conData[$cCode]['tableIn_master']['transaksi_nilai_tagihan'] - $conData[$cCode]['tableIn_master']['transaksi_nilai_terbayar']);
                //cekMerah("NULIS TAGIHANN");
            }
            else {
                //cekMerah("TIDAK NULIS TAGIHANN");
            }
            //endregion


            //region penomoran receipt #1

            $this->load->model("CustomCounter");
            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");
            $cn->setModul($modul_transaksi);
            $cn->setStepCode($tCodeTargetJenisTransaksi);
            $counterForNumber = array($configCoreMasterModulOrigJenis['formatNota']);
            if (!in_array($counterForNumber[0], $configCoreMasterModulOrigJenis['counters'])) {
                die(__LINE__ . " Used number should be registered in 'counters' config as well");
            }

            foreach ($counterForNumber as $i => $cRawParams) {
                $cParams = explode("|", $cRawParams);
                $cValues = array();
                foreach ($cParams as $param) {
                    $cValues[$i][$param] = $conData[$cCode]['main'][$param];
                }
                $cRawValues = implode("|", $cValues[$i]);
                $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);
            }
            $tmpNomorNota2_current = $tmpNomorNota2 = $paramSpec['paramString'];
            $tmpNomorNota2Alias_current = $tmpNomorNota2Alias = formatNota("nomer_nolink", $tmpNomorNota2);

            //endregion

            //region dynamic counters #1
//            echo "<script>top.writeProgress('sedang membuat penomoran');</script>";
            // <editor-fold defaultstate="collapsed" desc="==========__init+update dynamic-counters ">
            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");
            $cn->setModul($modul_transaksi);
            $cn->setStepCode($tCodeTargetJenisTransaksi);
            $configCustomParams = $configCoreMasterModulOrigJenis['counters'];
            $configCustomParams[] = "stepCode";
            if (sizeof($configCustomParams) > 0) {
                $cContent = array();
                foreach ($configCustomParams as $i => $cRawParams) {
                    $cParams = explode("|", $cRawParams);
                    $cValues = array();
                    foreach ($cParams as $param) {
                        $cValues[$i][$param] = $conData[$cCode]['main'][$param];
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


            $masterReplacers = array(
                "nomer" => $tmpNomorNota2,
                "nomer2" => $tmpNomorNota2Alias,
                "counters" => $appliedCounters2,
                "counters_intext" => $appliedCounters_inText2,
            );
            foreach ($masterReplacers as $key => $val) {
                $conData[$cCode]['tableIn_master'][$key] = $val;
            }

            $addValues = array(
                'counters' => $appliedCounters2,
                'counters_intext' => $appliedCounters_inText2,
                'nomer' => $tmpNomorNota2,
                'nomer2' => $tmpNomorNota2Alias,
                'dtime' => date("Y-m-d H:i:s"),
                'fulldate' => date("Y-m-d"),
            );
            foreach ($addValues as $key => $val) {
                $conData[$cCode]['tableIn_master'][$key] = $val;
            }

            // </editor-fold>
            //endregion

            //region numbering tambahan
            $this->load->library("CounterNumber");
            $ccn = new CounterNumber();
            $ccn->setCCode($cCode);
            $ccn->setJenisTr($this->jenisTr);
            $ccn->setTransaksiGate($conData[$cCode]['tableIn_master']);
            $ccn->setMainGate($conData[$cCode]['main']);
            $ccn->setItemsGate($conData[$cCode]['items']);
            $ccn->setItems2SumGate($conData[$cCode]['items2_sum']);
            $new_counter = $ccn->getCounterNumber();
//            cekHitam("jenistr yang disett dari create " . $this->jenisTr);

            if (isset($new_counter['main']) && sizeof($new_counter['main']) > 0) {
                foreach ($new_counter['main'] as $ckey => $cval) {
                    $conData[$cCode]['tableIn_master'][$ckey] = $cval;
                    $conData[$cCode]['main'][$ckey] = $cval;
                }
            }
            if (isset($new_counter['items']) && sizeof($new_counter['items']) > 0) {
                foreach ($new_counter['items'] as $ikey => $iSpec) {
                    foreach ($iSpec as $iikey => $iival) {
                        $conData[$cCode]['items'][$ikey][$iikey] = $iival;
                    }
                }
            }
            if (isset($new_counter['items2_sum']) && sizeof($new_counter['items2_sum']) > 0) {
                foreach ($new_counter['items2_sum'] as $ikey => $iSpec) {
                    foreach ($iSpec as $iikey => $iival) {
                        $conData[$cCode]['items2_sum'][$ikey][$iikey] = $iival;
                    }
                }
            }
            //endregion
            //==tulis kloningan transaksi

            //region write entries
            if (sizeof($conData[$cCode]['tableIn_master']) > 0) {

                // region locker transaksi---------------------------------
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    if ($this->session->login['ghost'] == 0) {
                        //                $followUpValidator = isset($configUiMasterModulOrigJenis['followUpValidator'][$stepNum]) ? $configUiMasterModulOrigJenis['followUpValidator'][$stepNum] : false;
                        //                if ($followUpValidator == true) {

                        $this->load->model("Mdls/MdlLockerTransaksi");
                        $lt = New MdlLockerTransaksi();
                        $lt->addFilter("transaksi_id='$no'");
                        $lt->addFilter("state='hold'");
                        $lt->addFilter("jumlah='1'");
                        $lt->addFilter("oleh_id=" . my_id());
                        $ltTmp = $lt->lookupAll()->result();
                        showLast_query("biru");
                        if (sizeof($ltTmp) == 1) {
                            cekHijau(":: lanjuut eksekusi transaksi ini....");
                        }
                        else {
                            $msg = "Transaksi sudah dieksekusi atau ada indikasi transaksi ganda. Silahkan tutup halaman ini dan refresh ulang.";
                            cekMerah($msg);
                            die(lgShowAlertBiru($msg));
                        }

                        //                }
                    }
                }
                // endregion locker transaksi---------------------------------

                $conData[$cCode]['tableIn_master']['status_4'] = 11;
                $conData[$cCode]['tableIn_master']['trash_4'] = 0;
                $conData[$cCode]['main']['status_4'] = 1;
                $conData[$cCode]['main']['trash_4'] = 0;
                $conData[$cCode]['tableIn_master']['project_id'] = $conData[$cCode]['main']['current_projectID'];
                $conData[$cCode]['tableIn_master']['project_nama'] = $conData[$cCode]['main']['projectName'];
                $conData[$cCode]['tableIn_master']['cabang_id'] = $conData[$cCode]['main']['cabang_id'];
                $conData[$cCode]['tableIn_master']['cabang_nama'] = $conData[$cCode]['main']['cabang_nama'];

                $insertTransaksiID = $insertID = $tr->writeMainEntries($conData[$cCode]['tableIn_master']);
                cekHitam($this->db->last_query());
                $midmaster = $insertID;
//                cekBiru("master invoice " . $insertID);
                $epID = $tr->writeMainEntries_entryPoint($insertID, $masterID, $conData[$cCode]['tableIn_master']);
                $mongoList['main'] = array($insertID, $epID);
                $insertNum = $conData[$cCode]['tableIn_master']['nomer'];
                $mNumMaster = $insertNum;
                $mJenisMaster = $conData[$cCode]['tableIn_master']['jenis'];
                $conData[$cCode]['main']['nomer'] = $insertNum;

                if ($insertID < 1) {
                    die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                }


                if (isset($conData[$cCode]['tableIn_master']['ids_his'])) {
                    $idHis_decode = blobDecode($conData[$cCode]['tableIn_master']['ids_his']);
                    $idHis_decode[$stepNum] = array(
                        "dtime" => date("Y-m-d H:i:s"),
                        "fulldate" => date("Y-m-d"),
                        "olehID" => $conData[$cCode]['main']['olehID'],
                        "olehName" => $conData[$cCode]['main']['olehName'],
                        "step" => $stepNum,
                        "trID" => $insertID,
                        "nomer" => $tmpNomorNota2,
                        "nomer2" => $tmpNomorNota2Alias,
                        "counters" => $appliedCounters2,
                        "counters_intext" => $appliedCounters_inText2,
                    );
                    $idHis_blob = blobEncode($idHis_decode);
                    $idHis_intext = print_r($idHis_decode, true);

                    $conData[$cCode]['tableIn_master']['ids_his'] = $idHis_blob;
                    $conData[$cCode]['tableIn_master']['ids_his_intext'] = $idHis_intext;

                    $tr = new MdlTransaksi();
                    $dup = $tr->updateData(array("id" => $insertID), array(
                        "ids_his" => $idHis_blob,
                        "ids_his_intext" => $idHis_intext,

                    )) or die("Failed to update tr next-state!");
//                    cekUngu($this->db->last_query());
                }

//                cekUngu(":: insertID => $insertID ::");
                if (isset($conData[$cCode]['tableIn_master_values']) && sizeof($conData[$cCode]['tableIn_master_values']) > 0) {
                    $inserMainValues = array();
                    $mongoList['mainValues'] = array();
                    foreach ($conData[$cCode]['tableIn_master_values'] as $key => $val) {
                        $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                        $inserMainValues[] = $dd;
                        $mongoList['mainValues'][] = $dd;
                    }
                    if (sizeof($inserMainValues) > 0) {
                        $arrBlob = blobEncode($inserMainValues);
                        $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                    }
                }
                if (isset($conData[$cCode]['main_add_values']) && sizeof($conData[$cCode]['main_add_values']) > 0) {
                    foreach ($conData[$cCode]['main_add_values'] as $key => $val) {
                        $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                        $mongoList['mainValues'][] = $dd;
                    }
                }
                if (isset($conData[$cCode]['main_inputs']) && sizeof($conData[$cCode]['main_inputs']) > 0) {
                    foreach ($conData[$cCode]['main_inputs'] as $key => $val) {
                        $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                        $mongoList['mainValues'][] = $dd;
                    }
                }
                if (isset($conData[$cCode]['main_add_fields']) && sizeof($conData[$cCode]['main_add_fields']) > 0) {
                    foreach ($conData[$cCode]['main_add_fields'] as $key => $val) {
                        $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
                    }
                }


                if (isset($conData[$cCode]['main_elements']) && sizeof($conData[$cCode]['main_elements']) > 0) {
                    //                cekMerah("ada mainElements $cCode");
                    //                arrprint($conData[$cCode]['main_elements']);die();
                    foreach ($conData[$cCode]['main_elements'] as $elName => $aSpec) {
                        $tr->writeMainElements($insertID, array(
                            "mdl_name" => isset($aSpec['mdl_name']) ? $aSpec['mdl_name'] : "",
                            "key" => isset($aSpec['key']) ? $aSpec['key'] : 0,
                            "value" => isset($aSpec['value']) ? $aSpec['value'] : "",
                            "name" => $aSpec['name'],
                            "label" => $aSpec['label'],
                            "contents" => isset($aSpec['contents']) ? $aSpec['contents'] : "",
                            "contents_intext" => isset($aSpec['contents_intext']) ? print_r($aSpec['contents_intext'], true) : "",

                        ));
                    }
                }
                else {
                    //                cekMerah("TAK ada mainElements");
                }

                if (isset($conData[$cCode]['tableIn_detail_values']) && sizeof($conData[$cCode]['tableIn_detail_values']) > 0) {
                    $insertIDs = array();
                    foreach ($conData[$cCode]['tableIn_detail_values'] as $pID => $dSpec) {
                        if (isset($configCoreMasterModulJenis['tableIn']['detailValues'])) {
                            foreach ($configCoreMasterModulJenis['tableIn']['detailValues'] as $key => $src) {
                                $dd = $tr->writeDetailValues($insertID, array(
                                    "produk_jenis" => $conData[$cCode]['tableIn_detail'][$pID]['produk_jenis'],
                                    "produk_id" => $pID,
                                    "key" => $key,
                                    "value" => isset($dSpec[$src]) ? $dSpec[$src] : 0,
                                ));
                                $insertIDs[$pID][] = $dd;
                                $mongoList['detailValues'][] = $dd;
                            }

                        }
                    }
                    if (sizeof($insertIDs) > 0) {
                        $arrBlob = blobEncode($insertIDs);
                        $this->db->query("UPDATE transaksi SET indexing_detail_values = '$arrBlob' WHERE id=$insertID");
                    }
                }
                if (isset($conData[$cCode]['tableIn_detail_values2_sum']) && sizeof($conData[$cCode]['tableIn_detail_values2_sum']) > 0) {
                    foreach ($conData[$cCode]['tableIn_detail_values2_sum'] as $pID => $dSpec) {
                        if (isset($configCoreMasterModulJenis['tableIn']['detailValues2_sum'])) {
                            foreach ($configCoreMasterModulJenis['tableIn']['detailValues2_sum'] as $key => $src) {
                                $dd = $tr->writeDetailValues($insertID, array(
                                    "produk_jenis" => $conData[$cCode]['tableIn_detail2_sum'][$pID]['produk_jenis'],
                                    "produk_id" => $pID,
                                    "key" => $key,
                                    "value" => $dSpec[$src],
                                ));
                                $insertIDs[] = $dd;
                                $mongoList['detailValues'][] = $dd;
                            }
                        }


                    }
                }
                if (isset($conData[$cCode]['tableIn_detail_rsltItems']) && sizeof($conData[$cCode]['tableIn_detail_rsltItems']) > 0) {
                    foreach ($conData[$cCode]['tableIn_detail_rsltItems'] as $pID => $dSpec) {
                        if (isset($configCoreMasterModulJenis['tableIn']['detail_rsltItems'])) {
                            foreach ($configCoreMasterModulJenis['tableIn']['detail_rsltItems'] as $key => $src) {
                                $dd = $tr->writeDetailValues($insertID, array(
                                    "produk_jenis" => $conData[$cCode]['tableIn_detail_rsltItems'][$pID]['produk_jenis'],
                                    "produk_id" => $pID,
                                    "key" => $key,
                                    "value" => $dSpec[$src],
                                ));
                                $insertIDs[$pID][] = $dd;
                                $mongoList['detailValues'][] = $dd;
                            }
                        }


                    }
                }

                //region update validQty pada step sebelumnya yang di-refer
//                echo "<script>top.writeProgress('EXTRACT ITEMS...','head');</script>";
                $seluruhnya = true;
                $prevTrID = 0;
                $arrvalidQtySisa = array();
                if (isset($conData[$cCode]['tableIn_detail']) && sizeof($conData[$cCode]['tableIn_detail']) > 0) {
                    $closedRequest = isset($configCoreMasterModulOrigJenis['closedRequest'][$stepNum]['enabled']) ? $configCoreMasterModulOrigJenis['closedRequest'][$stepNum]['enabled'] : false;
                    $insertIDs = array();
                    $insertDeIDs = array();
                    foreach ($conData[$cCode]['tableIn_detail'] as $iID => $dSpec) {
                        $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                        if ($insertDetailID < 1) {
                            die("Gagal saat berusaha write transaction detail entry pada " . __FILE__ . " baris " . __LINE__);
                        }
                        else {
                            $insertIDs[] = $insertDetailID;
                            $insertDeIDs[$insertID][] = $insertDetailID;
                            $mongoList['detail'][] = $insertDetailID;

                        }

                        if ($epID != 999) {
                            $insertEpID = $tr->writeDetailEntries($epID, $dSpec);
                            if ($insertEpID < 1) {
                                die("Gagal saat berusaha write transaction detail entry point pada " . __FILE__ . " baris " . __LINE__);
                            }
                            else {
                                $insertIDs[] = $insertEpID;
                                $insertDeIDs[$epID][] = $insertEpID;
                                $mongoList['detail'][] = $insertDetailID;
                            }
                        }

                        cekHitam("EXTRACTED ITEMS... [$iID]");
//                        echo "<script>top.writeProgress('" . strtoupper($dSpec['produk_nama']) . "');</script>";


                        if (isset($conData[$cCode]['extractedItems'])) {
                            if (array_key_exists($iID, $conData[$cCode]['extractedItems'])) {
                                $itemFulfilledJml = 0;
                                foreach ($conData[$cCode]['extractedItems'][$iID] as $triID => $triSpec) {
                                    $prevTrID = $triSpec['transaksi_id'];
                                    $tru = new MdlTransaksi();
                                    $tru->setFilters(array());
                                    $tru->setTableName($tru->getTableNames()['detail']);
                                    //----------------------------------------------------------
                                    if ($triSpec['valid_qty'] >= $dSpec['produk_ord_jml']) {
                                        $newValidQty = ($triSpec['valid_qty'] - $dSpec['produk_ord_jml']);
                                        //                                    cekmerah("validQty dikurangi oleh produk_ord_jml, yaitu " . $dSpec['produk_ord_jml']);
                                    }
                                    else {
                                        $newValidQty = ($triSpec['valid_qty'] - $triSpec['valid_qty']);
                                        //                                    cekmerah("validQty dikurangi oleh triSpec,  myaitu " . $triSpec['valid_qty']);
                                    }
                                    //----------------------------------------------------------
                                    $newValidQtyNotApprove = 0;
                                    if ($closedRequest == true) {
                                        cekPink2("closed Request enabled, request: " . $triSpec['valid_qty'] . ", approve: " . $dSpec['produk_ord_jml'] . ", newValidQty: " . $newValidQty);
                                        if ($triSpec['valid_qty'] >= $dSpec['produk_ord_jml']) {
                                            $newValidQty = 0;
                                            $newValidQtyNotApprove = ($triSpec['valid_qty'] - $dSpec['produk_ord_jml']);

                                        }
                                        //                                    else{
                                        //                                        $newValidQty = 0;
                                        //                                        $newValidQtyNotApprove = ($triSpec['valid_qty'] - $dSpec['produk_ord_jml']);
                                        //                                    }
                                        cekPink2("new valid qty: $newValidQty, valid qty not approve: $newValidQtyNotApprove");
                                    }
                                    //----------------------------------------------------------


                                    $itemFulfilledJml += $newValidQty;
                                    $updateContents = array(
                                        "valid_qty" => $newValidQty,
                                        "valid_qty_no_approve" => $newValidQtyNotApprove,
                                    );
                                    if ($newValidQty < 1) {
                                        $childPrevRepaclers = array(
                                            "next_substep_code" => "",
                                            "next_substep_label" => "",
                                            "next_subgroup_code" => "",
                                            "sub_tail_number" => $stepNum,
                                            "sub_tail_code" => $configUiMasterModulJenis['steps'][$stepNum]['target'],
                                        );
                                        foreach ($childPrevRepaclers as $key => $val) {
                                            $updateContents[$key] = $val;
                                        }
                                    }
                                    else {//==kalau ada yang tidak habis, berarti TIDAK seluruhnya yang dilanjutkan pada step berikutnya
                                        $seluruhnya = false;
                                        $arrvalidQtySisa[$iID] = $newValidQty;
                                    }
                                    $dupState = $tru->updateData(array(
                                        "produk_id" => $iID,
                                        "id" => $triID,
                                        "transaksi_id" => $triSpec['transaksi_id'],
                                    ), $updateContents) or die("Failed to update previous detail entries!");
                                    cekHijau(__LINE__ . " :: " . $this->db->last_query());

                                    $mongUpdateList['update']['detail'][] = array(
                                        "where" => array(
                                            //                                        "transaksi_id" => $triSpec['transaksi_id'],
                                            "id" => "$triID",
                                            //                                        "produk_id" => $iID,
                                        ),
                                        "value" => $updateContents,
                                    );
                                    unset($tru);
                                }
                            }
                            //                        else{
                            //                            if($closedRequest == true){
                            //
                            //                            }
                            //                        }
                        }
                    }

                    if ($closedRequest == true) {
                        if (isset($conData[$cCode]['extractedItems'])) {
                            foreach ($conData[$cCode]['extractedItems'] as $iIDex => $exSpec) {
                                if (!array_key_exists($iIDex, $conData[$cCode]['tableIn_detail'])) {
                                    foreach ($exSpec as $trDataID => $trdSpec) {
                                        $tru = new MdlTransaksi();
                                        $tru->setFilters(array());
                                        $tru->setTableName($tru->getTableNames()['detail']);
                                        $updateContents = array(
                                            "valid_qty" => 0,
                                            "valid_qty_no_approve" => $trdSpec['qty'],
                                        );
                                        $childPrevRepaclers = array(
                                            "next_substep_code" => "",
                                            "next_substep_label" => "",
                                            "next_subgroup_code" => "",
                                            "sub_tail_number" => $stepNum,
                                            "sub_tail_code" => $configUiMasterModulJenis['steps'][$stepNum]['target'],
                                        );
                                        foreach ($childPrevRepaclers as $key => $val) {
                                            $updateContents[$key] = $val;
                                        }
                                        $dupState = $tru->updateData(array(
                                            "produk_id" => $iIDex,
                                            "id" => $trDataID,
                                            "transaksi_id" => $trdSpec['transaksi_id'],
                                        ), $updateContents) or die("Failed to update previous detail entries!");
                                        //                                    cekHijau($this->db->last_query());
                                        $mongUpdateList['update']['detail'][] = array(
                                            "where" => array(
                                                //                                            "transaksi_id" => $trdSpec['transaksi_id'],
                                                "id" => "$trDataID",
                                                //                                            "produk_id" => $iIDex,
                                            ),
                                            "value" => $updateContents,
                                        );
                                        unset($tru);
                                    }
                                }
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
                            arrPrint($arrID);
                            $arrBlob = blobEncode($arrID);
                            $this->db->query("UPDATE transaksi SET indexing_details = '$arrBlob' WHERE id=$k");
                            cekOrange($this->db->last_query());
                        }
                    }

                    //-------------
                    $lastStepPartialApprove = isset($configUiMasterModulJenis['lastStepPartialApprove']) ? $configUiMasterModulJenis['lastStepPartialApprove'] : false;
                    if ($lastStepPartialApprove == true) {
                        cekKuning(__LINE__ . " $lastStepPartialApprove :: $totalSteps");
                        if ($totalSteps == 2) {
                            if (sizeof($arrvalidQtySisa) > 0) {
                                cekPink("ada valid qty yang tersisa");
                                $tr = new MdlTransaksi();
                                $dupState = $tr->updateData(array("id" => $topID), $stepNowParameter) or die("Failed to update tr next-state!");
                                cekHitam(__LINE__ . " ## 2 step, dan step akhir partial, YESS...");
                                showLast_query("orange");
                            }
                        }
                    }
                }
                else {
                    die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
                }

                if ($seluruhnya) {
                    $tr = new MdlTransaksi();
                    $dupState = $tr->updateData(array("id" => $prevTrID), array(
                        "tail_number" => $stepNum,
                        "tail_code" => $configUiMasterModulJenis['steps'][$stepNum]['target'],
                        "status_4" => $conData[$cCode]['main']['status_4'],
                        "trash_4" => $conData[$cCode]['main']['trash_4'],
                    )) or die("Failed to update tr next-state!");
                    cekHijau(":: UOPDATE transaksi dengan trID -> $prevTrID");
                    $mongUpdateList['update']['main'][] = array(
                        "where" => array(
                            "id" => "$prevTrID",
                        ),
                        "value" => array(
                            "tail_number" => $stepNum,
                            "tail_code" => $configUiMasterModulJenis['steps'][$stepNum]['target'],
                            "status_4" => $conData[$cCode]['main']['status_4'],
                            "trash_4" => $conData[$cCode]['main']['trash_4'],
                        ),
                    );
                    cekHijau($this->db->last_query());
                }
                //endregion

                //region cloner items to item_child
                if (sizeof($additionalData) > 0) {
//                    echo "<script>top.writeProgress('CLONING ITEMS TO ITEM CHILD...','head');</script>";
                    cekHitam("ini data");
                    $dataMdl = $additionalData["mdlName"];
                    $this->load->model("Mdls/" . $dataMdl);
                    $da = new $dataMdl();
                    $arrColl = $da->getFields();
                    $selectedCol = array();
                    foreach ($arrColl as $colSpec) {
                        $selectedCol[] = $colSpec['kolom'];
                    }

                    if (isset($conData[$cCode]['items_child']) && sizeof($conData[$cCode]['items_child'])) {
                        $gateData = isset($configUiMasterModulJenis['shopingCartDetailFields'][$stepNum]['gate']) ? $configUiMasterModulJenis['shopingCartDetailFields'][$stepNum]['gate'] : "detail";

                        $arrBlacklist = array(
                            "jml", "max_jml", "qty",
                        );
                        if (isset($conData[$cCode]["items2_sum"])) {
                            unset($conData[$cCode]["items2_sum"]);
                            unset($conData[$cCode]["items2"]);
                            unset($conData[$cCode]["tableIn_detail_values2_sum"]);
                        }
                        foreach ($conData[$cCode]['items_child'] as $mainProdsID => $defData) {
                            if ($gateData == "detail") {
                                $itemsMain = isset($conData[$cCode]['items'][$mainProdsID]) ? $conData[$cCode]['items'][$mainProdsID] : array();
                            }
                            else {
                                $forceMainToItems = isset($configUiMasterModulJenis['shopingCartDetailFields'][$stepNum]['changeToItems'][$gateData]) ? $configUiMasterModulJenis['shopingCartDetailFields'][$stepNum]['changeToItems'][$gateData] : array();
                                if (sizeof($forceMainToItems) > 0) {
                                    foreach ($forceMainToItems as $key1 => $key2) {
                                        $keyForce = strlen($key2) > 2 ? $key2 : $key1;
                                        $itemsMain[$key1] = isset($conData[$cCode]['main'][$keyForce]) ? $conData[$cCode]['main'][$keyForce] : "";
                                    }
                                    $itemsMain["jml"] = "1";
                                    $itemsMain["qty"] = "1";
                                    $itemsMain["max_jml"] = "1";

                                }
                                else {
                                    matiHEre("detil aset gagal di tulis!");
                                }
                                //                            arrPrint($forceMainToItems);
                            }

                            $arrChilds = array_diff_key($itemsMain, array_flip($arrBlacklist));
                            //                        arrPrint($itemsMain);
                            //                        matiHEre();
                            //
                            //arrPrint($arrChilds);
                            cekLime("ini brooo " . $gateData);

                            $arrNew = array();
                            if (sizeof($itemsMain) > 0) {
                                foreach ($defData as $inID => $detil_child) {
                                    //                        $arrNewChild = array_diff($itemsMain,$detil_child);

                                    $paramDetil = array_replace($arrChilds, $detil_child);
                                    if (array_key_exists("id", $paramDetil)) {

                                        $paramDetil["parent_id"] = $paramDetil["id"];
                                        if (!isset($paramDetil["folders"]) || $paramDetil["folders"] == 0) {
                                            $paramDetil["folders"] = $paramDetil["pihakMainId"];
                                            $paramDetil["keterangan"] = $paramDetil["pihakMainName"];
                                        }
                                        unset($paramDetil["id"]);
                                    }
                                    $tmpData = array();
                                    foreach ($selectedCol as $i => $coloum) {
                                        if (isset($paramDetil[$coloum])) {
                                            $tmpData[$coloum] = $paramDetil[$coloum];
                                        }
                                    }
                                    //                                arrPrint($paramDetil);
                                    if (isset($paramDetil["subtotal"])) {
                                        $paramDetil["subtotal"] = $paramDetil["jml"] * $paramDetil["harga"];
                                    }

                                    $insertDataID = $da->addData($tmpData, $da->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));
                                    cekHere($this->db->last_query());
                                    $paramDetil["id"] = $insertDataID;
//                                    echo "<script>top.writeProgress('PENGAJUAN DATA (TRID:$insertDataID)');</script>";
                                    $conData[$cCode]["items2_sum"][$insertDataID] = $paramDetil;
                                    $conData[$cCode]["items2"][$mainProdsID][$insertDataID] = $paramDetil;
                                    //                            $arrNew

                                }
                            }


                            //                        arrPrint($arrNew);
                            //


                            //                  arrPrint($itemsMain);
                        }

                    }
                }

                //endregion

                if (isset($conData[$cCode]['tableIn_detail2_sum']) && sizeof($conData[$cCode]['tableIn_detail2_sum']) > 0) {
                    $insertIDs = array();
                    foreach ($conData[$cCode]['tableIn_detail2_sum'] as $iID => $dSpec) {
                        $dd = $tr->writeDetailEntries($insertID, $dSpec);
                        $insertIDs[] = $dd;
                        $mongoList['detail'][] = $dd;
                        if ($epID != 999) {
                            $dd = $tr->writeDetailEntries($epID, $dSpec);
                            $insertIDs[] = $dd;
                            $mongoList['detail'][] = $dd;
                        }
                    }
                }
                if (isset($conData[$cCode]['tableIn_detail2']) && sizeof($conData[$cCode]['tableIn_detail2']) > 0) {
                    $insertIDs = array();
                    foreach ($conData[$cCode]['tableIn_detail2'] as $iID => $dSpec) {
                        $dd = $tr->writeDetailEntries($insertID, $dSpec);
                        $insertIDs[] = $dd;
                        $mongoList['detail'][] = $dd;
                        if ($epID != 999) {
                            $dd = $tr->writeDetailEntries($epID, $dSpec);
                            $insertIDs[] = $dd;
                            $mongoList['detail'][] = $dd;
                        }
                        cekUngu($this->db->last_query());
                    }
                }


                if (isset($configUiMasterModulJenis['updateDueDate'][$stepNum])) {
                    $dueDateConf = $configUiMasterModulJenis['updateDueDate'][$stepNum];
                    $sourceDue = $dueDateConf['source'];
                    $targetDue = $dueDateConf['target'];
                    $datenow = date("Y-m-d");
                    foreach ($sourceDue as $key => $val) {
                        $indexVal = isset($conData[$cCode]['main_elements'][$key][$val]) ? $conData[$cCode]['main_elements'][$key][$val] : 14;
                        $dueDate = dueDate($datenow, $indexVal);
                    }
                    $fieldDue = $tr->getFields()["dueDate"];
                    $dataDue = array();
                    foreach ($fieldDue as $kol) {
                        if (isset($conData[$cCode]['tableIn_master'][$kol])) {
                            $dataDue[$kol] = $conData[$cCode]['tableIn_master'][$kol];
                        }
                    }
                    $dataDue['due_date'] = $dueDate;
                    $validateDue = validateDueDate($conData[$cCode]['main']['customerID'], $conData[$cCode]['main']['dtime']);

                    arrPrint($validateDue);
                    if ($validateDue['allow_create'] == "true") {
                        if (isset($conData[$cCode]['main']['nilai_tambah_hutang_ke_konsumen']) && $conData[$cCode]['main']['nilai_tambah_hutang_ke_konsumen'] > 0) {
                            cekBiru($conData[$cCode]['main']['nilai_tambah_hutang_ke_konsumen']);

                            $tr->writeDueDate($insertID, $dataDue);
                        }
                    }
                    else {
                        $allowedOver = validateOverDue($conData[$cCode]['main']['customerID']);
                        if ($allowedOver['status'] == "allowed") {

                        }
                        else {
                            //                        matiHere($validateDue['error']);//matiin transaksi sudah over due
                        }
                        //                    arrPrint()
                        //                    matiHere($validateDue['error']);//matiin transaksi sudah over due
                    }
                    //                matiHere();
                    //update main elementnya
                    foreach ($targetDue as $keyTarget => $valTarget) {
                        $conData[$cCode]['main_elements'][$keyTarget][$valTarget] = $dueDate;
                        $conData[$cCode]['main']['dueDate'] = $dueDate;
                    }
                }
//                arrPrintPink($conData[$cCode]['tableIn_master']);

                $baseRegistries = array(
                    'main' => isset($conData[$cCode]['main']) ? $conData[$cCode]['main'] : array(),
                    'items' => isset($conData[$cCode]['items']) ? $conData[$cCode]['items'] : array(),
                    'items2' => isset($conData[$cCode]['items2']) ? $conData[$cCode]['items2'] : array(),
                    'items2_sum' => isset($conData[$cCode]['items2_sum']) ? $conData[$cCode]['items2_sum'] : array(),
                    'itemSrc' => isset($conData[$cCode]['itemSrc']) ? $conData[$cCode]['itemSrc'] : array(),
                    'itemSrc_sum' => isset($conData[$cCode]['itemSrc_sum']) ? $conData[$cCode]['itemSrc_sum'] : array(),
                    'items3' => isset($conData[$cCode]['items3']) ? $conData[$cCode]['items3'] : array(),
                    'items3_sum' => isset($conData[$cCode]['items3_sum']) ? $conData[$cCode]['items3_sum'] : array(),
                    'items4' => isset($conData[$cCode]['items4']) ? $conData[$cCode]['items4'] : array(),
                    'items4_sum' => isset($conData[$cCode]['items4_sum']) ? $conData[$cCode]['items4_sum'] : array(),
                    'items5_sum' => isset($conData[$cCode]['items5_sum']) ? $conData[$cCode]['items5_sum'] : array(),
                    'items6_sum' => isset($conData[$cCode]['items6_sum']) ? $conData[$cCode]['items6_sum'] : array(),
                    'items7_sum' => isset($conData[$cCode]['items7_sum']) ? $conData[$cCode]['items7_sum'] : array(),
                    'items8_sum' => isset($conData[$cCode]['items8_sum']) ? $conData[$cCode]['items8_sum'] : array(),
                    'items9_sum' => isset($conData[$cCode]['items9_sum']) ? $conData[$cCode]['items9_sum'] : array(),
                    'items10_sum' => isset($conData[$cCode]['items10_sum']) ? $conData[$cCode]['items10_sum'] : array(),
                    'items_noapprove' => isset($conData[$cCode]['items_noapprove']) ? $conData[$cCode]['items_noapprove'] : array(),

                    'rsltItems' => isset($conData[$cCode]['rsltItems']) ? $conData[$cCode]['rsltItems'] : array(),
                    'rsltItems2' => isset($conData[$cCode]['rsltItems2']) ? $conData[$cCode]['rsltItems2'] : array(),
                    'rsltItems3' => isset($conData[$cCode]['rsltItems3']) ? $conData[$cCode]['rsltItems3'] : array(),

                    'tableIn_master' => isset($conData[$cCode]['tableIn_master']) ? $conData[$cCode]['tableIn_master'] : array(),
                    'tableIn_detail' => isset($conData[$cCode]['tableIn_detail']) ? $conData[$cCode]['tableIn_detail'] : array(),
                    'tableIn_detail2_sum' => isset($conData[$cCode]['tableIn_detail2_sum']) ? $conData[$cCode]['tableIn_detail2_sum'] : array(),
                    'tableIn_detail_rsltItems' => isset($conData[$cCode]['tableIn_detail_rsltItems']) ? $conData[$cCode]['tableIn_detail_rsltItems'] : array(),
                    'tableIn_detail_rsltItems2' => isset($conData[$cCode]['tableIn_detail_rsltItems2']) ? $conData[$cCode]['tableIn_detail_rsltItems2'] : array(),
                    'tableIn_master_values' => isset($conData[$cCode]['tableIn_master_values']) ? $conData[$cCode]['tableIn_master_values'] : array(),
                    'tableIn_detail_values' => isset($conData[$cCode]['tableIn_detail_values']) ? $conData[$cCode]['tableIn_detail_values'] : array(),
                    'tableIn_detail_values_rsltItems' => isset($conData[$cCode]['tableIn_detail_values_rsltItems']) ? $conData[$cCode]['tableIn_detail_values_rsltItems'] : array(),
                    'tableIn_detail_values_rsltItems2' => isset($conData[$cCode]['tableIn_detail_values_rsltItems2']) ? $conData[$cCode]['tableIn_detail_values_rsltItems2'] : array(),
                    'tableIn_detail_values2_sum' => isset($conData[$cCode]['tableIn_detail_values2_sum']) ? $conData[$cCode]['tableIn_detail_values2_sum'] : array(),
                    'main_add_values' => isset($conData[$cCode]['main_add_values']) ? $conData[$cCode]['main_add_values'] : array(),
                    'main_add_fields' => isset($conData[$cCode]['main_add_fields']) ? $conData[$cCode]['main_add_fields'] : array(),
                    'main_elements' => isset($conData[$cCode]['main_elements']) ? $conData[$cCode]['main_elements'] : array(),
                    'main_inputs' => isset($conData[$cCode]['main_inputs']) ? $conData[$cCode]['main_inputs'] : array(),
                    'main_inputs_orig' => isset($conData[$cCode]['main_inputs']) ? $conData[$cCode]['main_inputs'] : array(),
                    "receiptDetailFields" => isset($configLayoutMasterModulJenis['receiptDetailFields'][$stepNum]) ? $configLayoutMasterModulJenis['receiptDetailFields'][$stepNum] : array(),
                    "receiptSumFields" => isset($configLayoutMasterModulJenis['receiptSumFields'][$stepNum]) ? $configLayoutMasterModulJenis['receiptSumFields'][$stepNum] : array(),
                    "receiptDetailFields2" => isset($configLayoutMasterModulJenis['receiptDetailFields2'][$stepNum]) ? $configLayoutMasterModulJenis['receiptDetailFields2'][$stepNum] : array(),
                    "receiptSumFields2" => isset($configLayoutMasterModulJenis['receiptSumFields2'][$stepNum]) ? $configLayoutMasterModulJenis['receiptSumFields2'][$stepNum] : array(),
                    "receiptDetailSrcFields" => isset($configLayoutMasterModulJenis['receiptDetailSrcFields'][$stepNum]) ? $configLayoutMasterModulJenis['receiptDetailSrcFields'][$stepNum] : array(),
                    "jurnal_index" => isset($configCoreMasterModulJenis['components'][$jenisTrTarget]) ? $configCoreMasterModulJenis['components'][$jenisTrTarget] : array(),
                    "preProcessor" => isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]) ? $configCoreMasterModulJenis['preProcessor'][$jenisTrTarget] : array(),
                    "postProcessor" => isset($configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]) ? $configCoreMasterModulJenis['postProcessor'][$jenisTrTarget] : array(),
                    "revert" => isset($conData[$cCode]['revert']) ? $conData[$cCode]['revert'] : array(),
                    "items_komposisi" => isset($conData[$cCode]['items_komposisi']) ? $conData[$cCode]['items_komposisi'] : array(),
                    "componentsBuilder" => isset($conData[$cCode]['componentsBuilder']) ? $conData[$cCode]['componentsBuilder'] : array(),
                    "jurnalItems" => isset($conData[$cCode]['jurnalItems']) ? $conData[$cCode]['jurnalItems'] : array(),

                );
                $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
                $mongRegID = $doWriteReg;
//                echo "<script>top.writeProgress('MENULIS KE-REGISTRY....');</script>";
            }
            else {
                die(lgShowAlert("Transaksi gagal disimpan, silahkan cek kembali transaksi ini."));
            }
            //endregion

            //mati_disini("LINE: " . __LINE__ . " under maintenance, tunggu beberapa saat lagi yaa.., TRID: $insertID");

            //region processing sub-post-processors, always
            //<editor-fold desc="----------sub postProc">
            // matiHEre();
            $iterator = isset($configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['detail']) ? $configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['detail'] : array();
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
//                    echo "sub-postProcessor: $comName, initializing values <br>";
//                    echo "<script>top.writeProgress('MENYIAPKAN DATA SUB-PROCESSORS UNTUK DIKIRIM...', 'head');</script>";

                    $tmpOutParams[$cCtr] = array();
                    foreach ($conData[$cCode][$srcGateName] as $cnt => $dSpec) {
                        $subParams = array();
                        if (isset($tComSpec['loop'])) {
                            foreach ($tComSpec['loop'] as $key => $value) {

                                $realValue = makeValue($value, $conData[$cCode][$srcGateName][$cnt], $conData[$cCode][$srcGateName][$cnt], 0);
                                $subParams['loop'][$key] = $realValue;

                            }
                        }
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {

                                $realValue = makeValue($value, $conData[$cCode][$srcGateName][$cnt], $conData[$cCode][$srcGateName][$cnt], 0);
                                $subParams['static'][$key] = $realValue;
                                cekBiru("$key diisi dengan $realValue");

                            }

                            if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                foreach ($paramPatchers[$comName] as $k => $v) {
                                    if (!isset($subParams['static'][$k])) {
                                        $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                    }
                                }
                            }
                            if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                $jenis = $conData[$cCode]['main']['jenis'];
                                foreach ($paramForceFillers[$comName] as $k => $v) {
                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                    cekorange(":: $k diisikan dengan " . $subParams['static'][$k]);
                                }
                            }

                            $subParams['static']["fulldate"] = date("Y-m-d");
                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                            $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                        }

                        if (sizeof($subParams) > 0) {
                            $tmpOutParams[$cCtr][] = $subParams;
                        }
//                        echo "<script>top.writeProgress('" . isset($subParams['static']['name']) ? $subParams['static']['name'] : "" . " " . isset($subParams['static']['extern_nama']) ? $subParams['static']['extern_nama'] : "" . " " . isset($subParams['static']['nama']) ? $subParams['static']['nama'] : "" . "');</script>";
                    }
                }

                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
//                    echo "sub-postProcessor: $comName, sending values <br>";
//                    echo "<script>top.writeProgress('SENDING SUB-PROCESSORS ($comName)...', 'head');</script>";
                    $mdlName = "Com" . ucfirst($comName);
                    $this->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();

                    $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    cekBiru($this->db->last_query());
                }
            }

            //endregion

            //region processing main-post-processors, always
            //<editor-fold desc="----------postProc">

            $iterator = isset($configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['master']) ? $configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['master'] : array();
            if (sizeof($iterator) > 0) {
//                echo "<script>top.writeProgress('MEMPROSES MAIN-PROCESSORS...', 'head');</script>";
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
//                    echo "post-processor: $comName<br>";

                    $dSpec = $conData[$cCode][$srcGateName];
                    $tmpOutParams = array();
                    if (isset($tComSpec['loop'])) {
                        foreach ($tComSpec['loop'] as $key => $value) {

                            $realValue = makeValue($value, $conData[$cCode][$srcGateName], $conData[$cCode][$srcGateName], 0);
                            $tmpOutParams['loop'][$key] = $realValue;

                        }
                    }
                    if (isset($tComSpec['static'])) {
                        //cekHere("DISINI OIII");
                        foreach ($tComSpec['static'] as $key => $value) {

                            $realValue = makeValue($value, $conData[$cCode][$srcGateName], $conData[$cCode][$srcGateName], 0);
                            $tmpOutParams['static'][$key] = $realValue;

                        }
                        if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                            foreach ($paramPatchers[$comName] as $k => $v) {
                                if (!isset($tmpOutParams['static'][$k])) {
                                    $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
//                                    echo "<script>top.writeProgress(':: $key diisikan dengan " . $tmpOutParams['static'][$k] . ");</script>";
                                }
                            }
                        }
                        if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                            $jenis = $conData[$cCode]['main']['jenis'];
                            foreach ($paramForceFillers[$comName] as $k => $v) {
                                $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
//                                echo "<script>top.writeProgress(':: $key diisikan dengan " . $tmpOutParams['static'][$k] . ");</script>";
                            }
                        }
                        $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                    }
                    if (isset($tComSpec['static2'])) {
                        //cekHere("DISINI OIII");
                        foreach ($tComSpec['static2'] as $key => $value) {

                            $realValue = makeValue($value, $conData[$cCode][$srcGateName][$cCtr], $conData[$cCode][$srcGateName][$cCtr], 0);
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
                            $jenis = $conData[$cCode]['main']['jenis'];
                            foreach ($paramForceFillers[$comName] as $k => $v) {
                                $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                            }
                        }
                        $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static2']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                    }

                    //lgShowError("Ada kesalahan",);
                    $mdlName = "Com" . ucfirst($comName);
                    $this->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();

                    //                cekBiru("kiriman komponem $comName");
                    //                                    arrPrint($tmpOutParams);
                    $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);


                }
            }


            //</editor-fold>
            //endregion

            //region ----------subcomponents GESER KE CLI

            //        $componentGate['detail'] = array();
            //        //arrPrint($paramForceFillers);
            $iterator = isset($configCoreMasterModulJenis['components'][$jenisTrTarget]['detail']) ? $configCoreMasterModulJenis['components'][$jenisTrTarget]['detail'] : array();
            $componentConfig['detail'] = $iterator;
            //        if (sizeof($iterator) > 0) {
            //            $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
            //            $filterNeeded = false;
            //            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
            //                $filterNeeded = true;
            //            }
            //            foreach ($iterator as $cCtr => $tComSpec) {
            ////                $comName = $tComSpec['comName'];
            //                $srcGateName = $tComSpec['srcGateName'];
            //                $srcRawGateName = $tComSpec['srcRawGateName'];
            //
            //                echo "sub-component: $comName, $srcGateName, initializing values <br>";
            //                $tmpOutParams[$cCtr] = array();
            //                foreach ($conData[$cCode][$srcGateName] as $id => $dSpec) {
            //                    cekmerah("mengevaluasi $srcGateName..");
            //                    $comName = $tComSpec['comName'];
            //                    if (substr($comName, 0, 1) == "{") {
            //                        $comName = trim($comName, "{");
            //                        $comName = trim($comName, "}");
            //                        $comName = str_replace($comName, $conData[$cCode][$srcGateName][$id][$comName], $comName);
            //                        $tComSpec['comName'] = $comName;
            //                        $iterator[$cCtr]['comName'] = $comName;
            //                    }
            //
            //                    $filterNeeded = false;
            //                    $mdlName = "Com" . ucfirst($comName);
            //                    if (in_array($mdlName, $compValidators)) {//perlu validasi filter
            //                        $filterNeeded = true;
            //                    }
            //
            //
            //                    $subParams = array();
            //                    if (isset($tComSpec['loop'])) {
            //                        foreach ($tComSpec['loop'] as $key => $value) {
            //                            if (substr($key, 0, 1) == "{") {
            //                                $key = trim($key, "{");
            //                                $key = trim($key, "}");
            //                                $key = str_replace($key, $conData[$cCode][$srcGateName][$id][$key], $key);
            //                            }
            //                            $realValue = makeValue($value, $conData[$cCode][$srcGateName][$id], $conData[$cCode][$srcGateName][$id], 0);
            //                            $subParams['loop'][$key] = $realValue;
            //                            cekKuning("LOOP: $key diisi dengan $realValue");
            //
            //                            if ($filterNeeded) {
            //                                if ($subParams['loop'][$key] == 0) {
            //                                    unset($subParams['loop'][$key]);
            //                                }
            //                            }
            //                        }
            //                    }
            //                    if (isset($tComSpec['static'])) {
            //                        foreach ($tComSpec['static'] as $key => $value) {
            //
            //                            $realValue = makeValue($value, $conData[$cCode][$srcGateName][$id], $conData[$cCode][$srcGateName][$id], 0);
            //                            $subParams['static'][$key] = $realValue;
            //                            cekKuning("STATIC: $key diisi dengan $realValue");
            //
            //                        }
            //                        if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
            //                            foreach ($paramPatchers[$comName] as $k => $v) {
            //                                if (!isset($subParams['static'][$k])) {
            //                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
            //                                    cekOrange("fill :: $comName :: $k => " . $subParams['static'][$k]);
            //                                }
            //                            }
            //                        }
            //                        if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
            //                            //                            cekOrange("comName:: $comName");
            //                            $jenis = $conData[$cCode]['main']['jenis'];
            //                            foreach ($paramForceFillers[$comName] as $k => $v) {
            //                                $subParams['static'][$k] = isset($$v) ? $$v : "_v";
            //                                cekOrange("fillforce :: $comName :: $k => " . $subParams['static'][$k]);
            //                            }
            //                        }
            //                        $subParams['static']["fulldate"] = date("Y-m-d");
            //                        $subParams['static']["dtime"] = date("Y-m-d H:i:s");
            //                        $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
            //                    }
            //                    cekHitam("cetak subParams");
            //                    arrPrint($subParams);
            //                    if (sizeof($subParams) > 0) {
            //                        if ($filterNeeded) {
            //                            if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
            //                                $tmpOutParams[$cCtr][] = $subParams;
            //                            }
            //                        }
            //                        else {
            //
            //                            $tmpOutParams[$cCtr][] = $subParams;
            //                        }
            //                    }
            //                }
            //
            //                $componentGate['detail'][$cCtr] = $subParams;
            //            }
            //
            //
            //            $it = 0;
            //            foreach ($iterator as $cCtr => $tComSpec) {
            //                $it++;
            //
            //
            //                $comName = $tComSpec['comName'];
            //                $srcGateName = $tComSpec['srcGateName'];
            //                $srcRawGateName = $tComSpec['srcRawGateName'];
            //
            //                echo "sub component #$it: $comName, sending values <br>";
            //
            //                $mdlName = "Com" . ucfirst($comName);
            //                $this->load->model("Coms/" . $mdlName);
            //                $m = new $mdlName();
            //
            //
            //                if (sizeof($tmpOutParams[$cCtr]) > 0) {
            //                    $tobeExecuted = true;
            //                }
            //                else {
            //                    $tobeExecuted = false;
            //                }
            //
            //
            //                if ($tobeExecuted) {
            //                    $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
            //                    $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
            //                }
            //                else {
            //                    cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
            //                }
            //            }
            //        }
            //        else {
            //            //cekKuning("subcomponents is not set");
            //        }

            //endregion

            //region ----------components
            //<editor-fold desc="----------components">
            $componentJurnal = array();
            $componentGate['master'] = array();
            $componentConfig['master'] = array();
            if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
                $iterator = isset($conData[$cCode]['revert']['jurnal'][$stepNum]['master']) ? $conData[$cCode]['revert']['jurnal'][$stepNum]['master'] : array();
            }
            else {
                if (isset($conData[$cCode]['componentsBuilder'][$stepNum]['master'])) {
                    $iterator = $conData[$cCode]['componentsBuilder'][$stepNum]['master'];
                }
                elseif (isset($configCoreMasterModulJenis['components'][$jenisTrTarget]['master'])) {
                    $iterator = $configCoreMasterModulJenis['components'][$jenisTrTarget]['master'];
                }
                else {
                    $iterator = array();
                }
            }

//arrPrint($conData);
//            matiHere(__LINE__);
            if (sizeof($iterator) > 0) {
//                echo "<script>top.writeProgress('KOMPONEN...', 'head');</script>";
                $componentConfig['master'] = $iterator;

                $it = 0;
                //==filter nilai, jika NOL tidak dikirim, sesuai config==
                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                foreach ($iterator as $cCtr => $tComSpec) {
                    //                cekPink($tComSpec);
                    //                mati_disini();
                    $it++;
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
//                    echo "component #$it: $comName :: $srcGateName <br>";

                    $dSpec = $conData[$cCode][$srcGateName];
                    $tmpOutParams = array();
                    if (isset($tComSpec['loop'])) {
                        foreach ($tComSpec['loop'] as $key => $value) {
                            if (substr($key, 0, 1) == "{") {
                                $key = trim($key, "{");
                                $key = trim($key, "}");
                                //                            $key = str_replace($key, $conData[$cCode]['main'][$key], $key);
                                $key = str_replace($key, $conData[$cCode][$srcGateName][$key], $key);
                            }
                            $realValue = makeValue($value, $conData[$cCode][$srcGateName], $conData[$cCode][$srcGateName], 0);
                            if ($key != null) {
                                $tmpOutParams['loop'][$key] = $realValue;
                            }

                        }
                    }
                    //                cekBiru($tmpOutParams);
                    //                mati_disini(__LINE__);
                    if (isset($tComSpec['static'])) {
                        foreach ($tComSpec['static'] as $key => $value) {

                            $realValue = makeValue($value, $conData[$cCode][$srcGateName], $conData[$cCode][$srcGateName], 0);
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
                            $jenis = $conData[$cCode]['main']['jenis'];
                            foreach ($paramForceFillers[$comName] as $k => $v) {
                                $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
                                cekHijau(":: FORCEFILL :: $key => $realValue ::");
                            }
                        }
                        $tmpOutParams['static']["urut"] = $cCtr;
                        $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                    }
                    if (isset($tComSpec['static2'])) {
                        foreach ($tComSpec['static2'] as $key => $value) {

                            $realValue = makeValue($value, $conData[$cCode][$srcGateName], $conData[$cCode][$srcGateName], 0);
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
                            $jenis = $conData[$cCode]['main']['jenis'];
                            foreach ($paramForceFillers[$comName] as $k => $v) {
                                $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                            }
                        }
                        $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static2']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                    }

                    //lgShowError("Ada kesalahan",);
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
                        $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    }
                    else {
                        cekBiru("komponem $comName tidak memenuhi syarat untuk ditulis");
                    }

                    $componentGate['master'][$cCtr] = $tmpOutParams;
                    if ($comName == "Jurnal") {
                        $componentJurnal[] = $tmpOutParams;
                    }
                }
            }
            else {
                //cekKuning("components is not set");
            }


            //endregion

            //region nulis paymentSource
            $stepCode = $configUiMasterModulJenis['steps'][$stepNum]['target'];
            $paymentSources = $this->config->item("payment_source");
            if (array_key_exists($stepCode, $paymentSources)) {
                $payConfigs = isset($paymentSources[$stepCode][$stepNum]) ? $paymentSources[$stepCode][$stepNum] : array();
                if (sizeof($payConfigs) > 0) {
                    foreach ($payConfigs as $paymentSrcConfig) {
                        $valueLabel = isset($paymentSrcConfig['label_key']) ? $paymentSrcConfig['label_key'] : $paymentSrcConfig['label'];
                        $valueSrc = $paymentSrcConfig['valueSrc'];
                        $externSrc = $paymentSrcConfig['externSrc'];
                        $valueAdd = isset($conData[$cCode]['main'][$paymentSrcConfig['addValueValidator']]) ? $conData[$cCode]['main'][$paymentSrcConfig['addValueValidator']] : 0;
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

                        if (isset($conData[$cCode]['main'][$valueSrc]) && $conData[$cCode]['main'][$valueSrc] > 0) {
                            if (isset($externSrc['extern_label2'])) {
                                //cek ada isinya atau kosong
                                $cek = strlen($conData[$cCode]['main'][$externSrc['extern_label2']]) > 4 ? "" : matiHere("jenis biaya tidak dikenali " . __LINE__);//
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
                            $this->load->helper("he_payment_source");
                            //                        paymentSource($this->jenisTr, $componentJurnal, $conData[$cCode]['main'], $valueLabel, $valueSrc, $valueAdd);
                            //-----------------------

                            $arrPymSrc = array(
                                "jenis" => $stepCode,
                                "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                "extern_id" => isset($conData[$cCode]['main'][$externSrc['id']]) ? $conData[$cCode]['main'][$externSrc['id']] : "",
                                "extern_nama" => isset($conData[$cCode]['main'][$externSrc['nama']]) ? $conData[$cCode]['main'][$externSrc['nama']] : "",
                                "nomer" => $tmpNomorNota2,
                                "label" => $paymentSrcConfig['label'],

                                "tagihan" => $conData[$cCode]['main'][$valueSrc],
                                "terbayar" => 0,
                                "sisa" => $conData[$cCode]['main'][$valueSrc],

                                "cabang_id" => isset($externSrc['cabang_id']) && isset($conData[$cCode]['main'][$externSrc['cabang_id']]) ? $conData[$cCode]['main'][$externSrc['cabang_id']] : $conData[$cCode]['main']['placeID'],
                                "cabang_nama" => isset($externSrc['cabang_nama']) && isset($conData[$cCode]['main'][$externSrc['cabang_nama']]) ? $conData[$cCode]['main'][$externSrc['cabang_nama']] : $conData[$cCode]['main']['placeName'],
                                "oleh_id" => $this->session->login['id'],
                                "oleh_nama" => $this->session->login['nama'],
                                "dtime" => date("Y-m-d H:i:s"),
                                "fulldate" => date("Y-m-d"),
                                "valas_id" => isset($externSrc['valasId']) && isset($conData[$cCode]['main'][$externSrc['valasId']]) ? $conData[$cCode]['main'][$externSrc['valasId']] : '',
                                "valas_nama" => isset($externSrc['valasLabel']) && isset($conData[$cCode]['main'][$externSrc['valasLabel']]) ? $conData[$cCode]['main'][$externSrc['valasLabel']] : '',
                                "valas_nilai" => isset($externSrc['valasValue']) && isset($conData[$cCode]['main'][$externSrc['valasValue']]) ? $conData[$cCode]['main'][$externSrc['valasValue']] : '',

                                "tagihan_valas" => isset($externSrc['valasTagihan']) && isset($conData[$cCode]['main'][$externSrc['valasTagihan']]) ? $conData[$cCode]['main'][$externSrc['valasTagihan']] : '',
                                "terbayar_valas" => 0,
                                "sisa_valas" => isset($externSrc['valasSisa']) && isset($conData[$cCode]['main'][$externSrc['valasSisa']]) ? $conData[$cCode]['main'][$externSrc['valasSisa']] : '',

                                //                            "extern_label2" => isset($conData[$cCode]['main']['pihakMainName']) ? $conData[$cCode]['main']['pihakMainName'] : "",
                                "extern_label2" => (isset($externSrc['extern_label2']) && ($conData[$cCode]['main'][$externSrc['extern_label2']])) ? $conData[$cCode]['main'][$externSrc['extern_label2']] : "",

                                "dpp_ppn" => (isset($externSrc['dpp_ppn']) && ($conData[$cCode]['main'][$externSrc['dpp_ppn']])) ? $conData[$cCode]['main'][$externSrc['dpp_ppn']] : 0,
                                "ppn" => (isset($externSrc['ppn']) && ($conData[$cCode]['main'][$externSrc['ppn']])) ? $conData[$cCode]['main'][$externSrc['ppn']] : 0,
                                "ppn_approved" => (isset($externSrc['ppn_approved']) && ($conData[$cCode]['main'][$externSrc['ppn_approved']])) ? $conData[$cCode]['main'][$externSrc['ppn_approved']] : 0,
                                "ppn_sisa" => (isset($externSrc['ppn']) && ($conData[$cCode]['main'][$externSrc['ppn']])) ? $conData[$cCode]['main'][$externSrc['ppn']] : "",
                                "ppn_status" => (isset($externSrc['ppn_status'])) ? $externSrc['ppn_status'] : 0,
                                "extern_nilai2" => (isset($externSrc['extern_nilai2']) && ($conData[$cCode]['main'][$externSrc['extern_nilai2']])) ? $conData[$cCode]['main'][$externSrc['extern_nilai2']] : 0,
                                "extern_date2" => (isset($externSrc['extern_date2']) && ($conData[$cCode]['main'][$externSrc['extern_date2']])) ? $conData[$cCode]['main'][$externSrc['extern_date2']] : "",
                                "pph_23" => (isset($externSrc['pph_23']) && ($conData[$cCode]['main'][$externSrc['pph_23']])) ? $conData[$cCode]['main'][$externSrc['pph_23']] : "",

                                "npwp" => (isset($externSrc['npwp']) && ($conData[$cCode]['main'][$externSrc['npwp']])) ? $conData[$cCode]['main'][$externSrc['npwp']] : "",
                                "project_id" => isset($externSrc['project_id']) && isset($conData[$cCode]['main'][$externSrc['project_id']]) ? $conData[$cCode]['main'][$externSrc['project_id']] : "",
                                "project_nama" => isset($externSrc['project_nama']) && isset($conData[$cCode]['main'][$externSrc['project_nama']]) ? $conData[$cCode]['main'][$externSrc['project_nama']] : "",
                                "extern2_id" => (isset($externSrc['extern2_id']) && ($conData[$cCode]['main'][$externSrc['extern2_id']])) ? $conData[$cCode]['main'][$externSrc['extern2_id']] : "",
                                "extern2_nama" => (isset($externSrc['extern2_nama']) && ($conData[$cCode]['main'][$externSrc['extern2_nama']])) ? $conData[$cCode]['main'][$externSrc['extern2_nama']] : "",
                                "ppn_pph_faktor" => (isset($externSrc['ppn_pph_faktor']) && ($conData[$cCode]['main'][$externSrc['ppn_pph_faktor']])) ? $conData[$cCode]['main'][$externSrc['ppn_pph_faktor']] : "",
                                "extern_jenis" => (isset($externSrc['extern_jenis']) && ($conData[$cCode]['main'][$externSrc['extern_jenis']])) ? $conData[$cCode]['main'][$externSrc['extern_jenis']] : "",
                                "extern_nilai3" => (isset($externSrc['extern_nilai3']) && ($conData[$cCode]['main'][$externSrc['extern_nilai3']])) ? $conData[$cCode]['main'][$externSrc['extern_nilai3']] : "",
                                "extern_nilai4" => (isset($externSrc['extern_nilai4']) && ($conData[$cCode]['main'][$externSrc['extern_nilai4']])) ? $conData[$cCode]['main'][$externSrc['extern_nilai4']] : "",
                                "extern3_id" => isset($externSrc['extern3_id']) && isset($conData[$cCode]['main'][$externSrc['extern3_id']]) ? $conData[$cCode]['main'][$externSrc['extern3_id']] : "",
                                "extern3_nama" => isset($externSrc['extern3_nama']) && isset($conData[$cCode]['main'][$externSrc['extern3_nama']]) ? $conData[$cCode]['main'][$externSrc['extern3_nama']] : "",
                                "extern4_id" => isset($externSrc['extern4_id']) && isset($conData[$cCode]['main'][$externSrc['extern4_id']]) ? $conData[$cCode]['main'][$externSrc['extern4_id']] : "",
                                "extern4_nama" => isset($externSrc['extern4_nama']) && isset($conData[$cCode]['main'][$externSrc['extern4_nama']]) ? $conData[$cCode]['main'][$externSrc['extern4_nama']] : "",
                                "extern5_id" => isset($externSrc['extern5_id']) && isset($conData[$cCode]['main'][$externSrc['extern5_id']]) ? $conData[$cCode]['main'][$externSrc['extern5_id']] : "",
                                "extern5_nama" => isset($externSrc['extern5_nama']) && isset($conData[$cCode]['main'][$externSrc['extern5_nama']]) ? $conData[$cCode]['main'][$externSrc['extern5_nama']] : "",
//                                "npwp" => (isset($externSrc['npwp']) && ($conData[$cCode]['main'][$externSrc['npwp']])) ? $conData[$cCode]['main'][$externSrc['npwp']] : "",
                                //                            "extern_nilai2" => (isset($externSrc['extern_nilai2']) && ($conData[$cCode]['main'][$externSrc['extern_nilai2']])) ? $conData[$cCode]['main'][$externSrc['extern_nilai2']] : "",
                                "payment_locked" => (isset($externSrc['payment_locked']) && ($conData[$cCode]['main'][$externSrc['payment_locked']])) ? $conData[$cCode]['main'][$externSrc['payment_locked']] : 0,
                                "cash_account" => (isset($externSrc['cash_account']) && ($conData[$cCode]['main'][$externSrc['cash_account']])) ? $conData[$cCode]['main'][$externSrc['cash_account']] : 0,
                                "cash_account_nama" => (isset($externSrc['cash_account_nama']) && ($conData[$cCode]['main'][$externSrc['cash_account_nama']])) ? $conData[$cCode]['main'][$externSrc['cash_account_nama']] : 0,
                            );
                            $tr->writePaymentSrc($insertID, $arrPymSrc);

                        }

                    }
                }

            }
            else {
                cekMerah("TIDAK nulis paymentSrc");
            }

            $addPaymentSource = isset($configUiMasterModulJenis['steps'][$stepNum]['additionalStep']['shippingService']) ? $configUiMasterModulJenis['steps'][$stepNum]['additionalStep']['shippingService'] : array();

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
//                        if(isset($_SESSION[$cCode][$gate])){
                            foreach($items2 as $pembantuData_tmp){
                                foreach($pembantuData_tmp as $pembantuData){
//                                    arrprint($pembantuData);
                                    if (isset($pembantuData[$valueSrc]) && $pembantuData[$valueSrc] > 0) {
                                        $arrPymSrc = array(
                                            "jenis" => $stepCode,
                                            "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                            "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                            "extern_id" => isset($pembantuData[$externSrc['id']]) ? $pembantuData[$externSrc['id']] : "",
                                            "extern_nama" => isset($pembantuData[$externSrc['nama']]) ? $pembantuData[$externSrc['nama']] : "",
                                            "nomer" => $tmpNomorNota2,
                                            "label" => $paymentSrcConfig['label'],

                                            "tagihan" => $pembantuData[$valueSrc],
                                            "terbayar" => 0,
                                            "sisa" => $pembantuData[$valueSrc],

                                            "cabang_id" => $pembantuData['cabang_id'],
                                            "cabang_nama" => $pembantuData['cabang_nama'],
                                            "oleh_id" => $this->session->login['id'],
                                            "oleh_nama" => $this->session->login['nama'],
                                            "dtime" => date("Y-m-d H:i:s"),
                                            "fulldate" => date("Y-m-d"),
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
//                                        cekMerah($this->db->last_query());
                                    }
                                }

                            }
//                            matiHere(__LINE__);
//                        }

//                        cekMerah($this->db->last_query());
                    }
//                    matiHere();
                }

            }
            else {
                cekMerah("TIDAK nulis paymentSrc");
            }
            //endregion
//            matiHere(__LINE__);

            //region nulis paymentAntiSource
            $stepCode = $configUiMasterModulJenis['steps'][$stepNum]['target'];
            $paymentSources = $this->config->item("payment_antiSource");
            if (array_key_exists($stepCode, $paymentSources)) {
                cekMerah(":: starting PAYMENT ANTI SOURCE");
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
                            "extern_id" => $conData[$cCode]['main'][$externSrc['id']],
                            "extern_nama" => $conData[$cCode]['main'][$externSrc['nama']],
                            "nomer" => $tmpNomorNota2,
                            "label" => $paymentSrcConfig['label'],
                            "tagihan" => $conData[$cCode]['main'][$valueSrc],
                            "terbayar" => 0,
                            "sisa" => $conData[$cCode]['main'][$valueSrc],
                            "cabang_id" => $conData[$cCode]['main']['placeID'],
                            "cabang_nama" => $conData[$cCode]['main']['placeName'],
                            "oleh_id" => $this->session->login['id'],
                            "oleh_nama" => $this->session->login['nama'],
                            "dtime" => date("Y-m-d H:i:s"),
                            "fulldate" => date("Y-m-d"),
                        ));
                        //cekMerah($this->db->last_query());
                    }
                }

            }
            else {
                //cekMerah("TIDAK nulis paymentSrc");
            }
            //endregion

            //region nulis uangMukaSource
            /*dimatiin geser ke ComUangmukaSourceDetail karena ada di items.
            /*revisi tanggal 27 mei 2020 subject digeser ke vendor dari jenis transaksi misal uangmuka asuransi,uang muka pembelian ->uang muka.
             *
             */
            $stepCode = $configUiMasterModulJenis['steps'][$stepNum]['target'];
            $uangMukaSources = $this->config->item("uang_muka");

            if (array_key_exists($stepCode, $uangMukaSources)) {
                cekMerah(":: starting UANG MUKA  SOURCE");
                //            matiHere();
                $uangMukaConfigs = isset($uangMukaSources[$stepCode][$stepNum]) ? $uangMukaSources[$stepCode][$stepNum] : array();
                if (sizeof($uangMukaConfigs) > 0) {
                    $cekPreValue = "";
                    $this->load->model("Mdls/MdlPaymentUangMuka");
                    $l = new MdlPaymentUangMuka();
                    foreach ($uangMukaConfigs as $uangMukaSrcConfig) {
                        //					$paymentSrcConfig = $paymentSources[$stepCode];
                        //                    arrPrint($uangMukaSrcConfig);
                        $valueSrc = $uangMukaSrcConfig['valueSrc'];
                        $externSrc = $uangMukaSrcConfig['externSrc'];
                        $l->addFilter("extern_id='" . $conData[$cCode]['main'][$externSrc['id']] . "'");
                        $l->addFilter("extern_label2='" . $externSrc['extLabel'] . "'");
                        $tmpUm = $l->lookupAll()->result();
                        //                    arrPrint($tmpUm);
                        if (sizeof($tmpUm) > 0) {
                            //update here broo
                            $preTagihan = $tmpUm[0]->tagihan;
                            $preSisa = $tmpUm[0]->sisa;

                            $newTahigan = $preTagihan + $conData[$cCode]['main'][$valueSrc];
                            $newsisa = $preSisa + $conData[$cCode]['main'][$valueSrc];
                            $update = array(
                                "tagihan" => $newTahigan,
                                "sisa" => $newsisa,
                            );
                            $where = array(
                                "extern_id" => $conData[$cCode]['main'][$externSrc['id']],
                            );
                            $tr->updateUangMukaSrc($where, $update);
                            cekHitam($this->db->last_query());
                        }
                        else {
                            //insertbaru brooo
                            $tr->writeUangMukaSrc($insertID, array(
                                "jenis" => $stepCode,
                                "target_jenis" => $uangMukaSrcConfig['jenisTarget'],
                                "reference_jenis" => $uangMukaSrcConfig['jenisSrc'],
                                "extern_id" => $conData[$cCode]['main'][$externSrc['id']],
                                "extern_nama" => $conData[$cCode]['main'][$externSrc['nama']],
                                "nomer" => "",
                                "note" => "",
                                "label" => $uangMukaSrcConfig['label'],
                                "tagihan" => $conData[$cCode]['main'][$valueSrc],
                                "terbayar" => 0,
                                "sisa" => $conData[$cCode]['main'][$valueSrc],
                                "cabang_id" => $conData[$cCode]['main']['placeID'],
                                "cabang_nama" => $conData[$cCode]['main']['placeName'],
                                "oleh_id" => $this->session->login['id'],
                                "oleh_nama" => $this->session->login['nama'],
                                "dtime" => date("Y-m-d H:i:s"),
                                "fulldate" => date("Y-m-d"),
                                "extern_label2" => $externSrc['extLabel'],
                            ));
                        }
                        cekMerah($this->db->last_query());
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
//            mati_disini(__LINE__);
            validateAllBalances($cabangTujuanID);
//arrPrintKuning($conData[$cCode]["items3_sum"]);



            //region connecting antar cabang
            $configUiMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiUi");
            $configCoreMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiCore");
            $configLayoutMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiLayout");
            $configValuesMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiValues");

            $configUiMasterModulOrigJenis = loadConfigModulJenis_he_misc($origJenis, "coTransaksiUi");
            $configCoreMasterModulOrigJenis = loadConfigModulJenis_he_misc($origJenis, "coTransaksiCore");
            $configLayoutMasterModulOrigJenis = loadConfigModulJenis_he_misc($origJenis, "coTransaksiLayout");

            $steps = isset($configUiMasterModulOrigJenis['steps']) ? $configUiMasterModulOrigJenis['steps'] : array();
            $connector = isset($configUiMasterModulOrigJenis['connectTo']) ? $configUiMasterModulOrigJenis['connectTo'] : "";
            $preReplacer = isset($configUiMasterModulJenis['replacerConnectTo']) ? $configUiMasterModulJenis['replacerConnectTo'] : array();
            $validateValueConnector = isset($configUiMasterModulJenis['connectoValidate'][$stepNum]) ? $configUiMasterModulJenis['connectoValidate'][$stepNum] : array();
            $mongoListConnect = array();
            $mongRegIDConnect = array();
            $insertConnectingID = 0;
            if (strlen($connector) > 0) {
                cekMerah("TO BE CONNECT TO $connector |$stepNum|" . sizeof($steps));
                if (isset($configUiMasterModulJenis['connectoValidate'][$stepNum])) {
                    $validateValueConnector = $configUiMasterModulJenis['connectoValidate'][$stepNum];
                    $preVal = $conData[$cCode]['main'][$validateValueConnector];
                    $stepNum = $preVal > 0 ? $stepNum : "1000";//1000 untuk nglewatin step biar gak jalan connectingnya karena nilai yang dicari 0 kasusnya cash in advance ppn sudah masuk pusat tidak perlu diterbitkan auto dorong ppn ke pusat
                }
                if ($stepNum == sizeof($steps)) {
                    cekMerah("NOW CONNECTING to $connector");

                    $configUiMasterModulJenis = loadConfigModulJenis_he_misc($connector, "coTransaksiUi");
                    $configCoreMasterModulJenis = loadConfigModulJenis_he_misc($connector, "coTransaksiCore");
                    $configLayoutMasterModulJenis = loadConfigModulJenis_he_misc($connector, "coTransaksiLayout");
                    $configValuesMasterModulJenis = loadConfigModulJenis_he_misc($connector, "coTransaksiValues");
                    $modul_transaksi = $this->config->item("heTransaksi_ui")[$connector]["modul"];
                    $tCodeTargetJenisTransaksi = $configUiMasterModulJenis['steps'][1]['target'];

//                    if (!array_key_exists($connector, $this->configUi)) {
                    if (sizeof($configUiMasterModulJenis) == 0) {
                        die("kode connector tidak dikenali!");
                    }
                    if (sizeof($configUiMasterModulJenis['steps']) < 2) {
                        die("konfigurasi connector harus memiliki step lebih dari satu!");
                    }


                    $oldCode = $cCode;
                    $cCode = "_TR_" . $connector;

                    $conData[$cCode] = array();
                    $conData[$cCode] = array(
                        "main" => $conData[$oldCode]['main'],
                        "items" => $conData[$oldCode]['items'],
                        "items2" => $conData[$oldCode]['items2'],
                        "items2_sum" => $conData[$oldCode]['items2_sum'],
                        "items3" => $conData[$oldCode]['items3'],
                        "items3_sum" => $conData[$oldCode]['items3_sum'],
                        "items4_sum" => $conData[$oldCode]['items4_sum'],
                        "items_noapprove" => $conData[$oldCode]['items_noapprove'],

                        "tableIn_master" => $conData[$oldCode]['tableIn_master'],
                        "tableIn_detail" => $conData[$oldCode]['tableIn_detail'],

                        "rsltItems" => $conData[$oldCode]['rsltItems'],
                        "tableIn_detail_rsltItems" => $conData[$oldCode]['tableIn_detail_rsltItems'],

                        "tableIn_master_values" => $conData[$oldCode]['tableIn_master_values'],
                        "tableIn_detail_values" => $conData[$oldCode]['tableIn_detail_values'],
                        "tableIn_detail_values_rsltItems" => $conData[$oldCode]['tableIn_detail_values_rsltItems'],
                    );

                    //==replace pertama
                    $masterReplacersO = array(
                        "jenisTr" => $connector,
                        "jenisTrMaster" => $connector,
                        "jenisTrTop" => $configUiMasterModulJenis['steps'][1]['target'],
                        "jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                        "jenis_label" => $configUiMasterModulJenis['steps'][1]['label'],
                        "transaksi_jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                        "stepCode" => $configUiMasterModulJenis['steps'][1]['target'],
                        "placeID" => isset($preReplacer['place2ID']) ? $preReplacer['place2ID'] : $conData[$cCode]['main']['place2ID'],
                        "placeName" => isset($preReplacer['place2Name']) ? $preReplacer['place2Name'] : $conData[$cCode]['main']['place2Name'],
                        "place2ID" => $conData[$cCode]['main']['placeID'],
                        "place2Name" => $conData[$cCode]['main']['placeName'],
                        "cabangID" => isset($preReplacer['cabang2ID']) ? $preReplacer['cabang2ID'] : $conData[$cCode]['main']['place2ID'],
                        "cabangName" => isset($preReplacer['place2Name']) ? $preReplacer['place2Name'] : $conData[$cCode]['main']['place2Name'],
                        "cabang2ID" => $conData[$cCode]['main']['placeID'],
                        "cabang2Name" => $conData[$cCode]['main']['placeName'],
                        //
                        "gudang2ID" => $conData[$cCode]['main']['gudangID'],
                        "gudang2Name" => $conData[$cCode]['main']['gudangName'],
                        "gudangID" => isset($preReplacer['gudang2ID']) ? $preReplacer['gudang2ID'] : $conData[$cCode]['main']['gudang2ID'],
                        "gudangName" => isset($preReplacer['gudang2Name']) ? $preReplacer['gudang2Name'] : $conData[$cCode]['main']['gudang2Name'],
                        "pihakID" => isset($conData[$cCode]['main']['placeID']) ? $conData[$cCode]['main']['placeID'] : "",
                        "pihakName" => isset($conData[$cCode]['main']['placeName']) ? $conData[$cCode]['main']['placeName'] : "",
                        "pihakName2" => $conData[$cCode]['main']['placeName'],
                        "gudang" => $conData[$cCode]['main']['gudangID'],
                        "gudang__name" => $conData[$cCode]['main']['gudangName'],
                        "gudang__label" => $conData[$cCode]['main']['gudangName'],
                        "efaktur_source" => isset($preReplacer['efaktur_source']) ? $conData[$cCode]['main']['nomer'] : "",

                    );
                    foreach ($masterReplacersO as $key => $val) {
                        $conData[$cCode]['main'][$key] = $val;
                        //                    $conData[$cCode]['main'][$key] = $val;
                    }
                    $masterReplacers = array(
                        //                    "referensi_id" => $masterID, (dimatikan)
                        "inv" => $tmpNomorNota,
                        "jenis_master" => $connector,
                        "jenis_top" => $configUiMasterModulJenis['steps'][1]['target'],
                        "jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                        "jenis_label" => $configUiMasterModulJenis['steps'][1]['label'],
                        "transaksi_jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                        "cabang_id" => isset($preReplacer['cabang2ID']) ? $preReplacer['cabang2ID'] : $conData[$cCode]['tableIn_master']['cabang2_id'],
                        "cabang_nama" => isset($preReplacer['cabang2Name']) ? $preReplacer['cabang2Name'] : $conData[$cCode]['tableIn_master']['cabang2_nama'],
                        "cabang2_id" => $conData[$cCode]['tableIn_master']['cabang_id'],
                        "cabang2_nama" => $conData[$cCode]['tableIn_master']['cabang_nama'],
                        "gudang_id" => isset($preReplacer['gudang2ID']) ? $preReplacer['gudang2ID'] : $conData[$cCode]['tableIn_master']['gudang2_id'],
                        "gudang_nama" => isset($preReplacer['gudang2Name']) ? $preReplacer['gudang2Name'] : $conData[$cCode]['tableIn_master']['gudang2_nama'],
                        "gudang2_id" => $conData[$cCode]['tableIn_master']['gudang_id'],
                        "gudang2_nama" => $conData[$cCode]['tableIn_master']['gudang_nama'],
                        "gudang" => $conData[$cCode]['tableIn_master']['gudang_id'],
                        "gudang__name" => $conData[$cCode]['tableIn_master']['gudang_nama'],
                        "gudang__label" => $conData[$cCode]['tableIn_master']['gudang_nama'],

                        "step_avail" => sizeof($configUiMasterModulJenis['steps']),
                        "step_current" => 1,
                        "step_number" => 1,
                        "next_step_code" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['target'] : "",
                        "next_step_label" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['label'] : "",
                        "next_group_code" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['userGroup'] : "",
                        "next_step_num" => isset($configUiMasterModulJenis['steps'][2]) ? 2 : "0",
                        "efaktur_source" => isset($preReplacer['efaktur_source']) ? $conData[$cCode]['main']['nomer'] : "",
                        //===references
                        //                    "id_master"            => $masterID,
                        //                    "id_top"               => $topID,
                        //                    "ids_prev"             => base64_encode(serialize(array($prevProp['id']))),
                        //                    "ids_prev_intext"      => print_r(array($prevProp['id'], true)),
                        //                    "nomer_top"            => $conData[$cCode]['main']['nomer'],
                        //                    "nomers_prev"          => base64_encode(serialize(array($prevProp['nomer']))),
                        //                    "nomers_prev_intext"   => print_r(array($prevProp['nomer'], true)),
                        //                    "jenis_top"            => $this->jenisTr,
                        //                    "jenises_prev"        => base64_encode(serialize(array($prevProp['jenis']))),
                        //                    "jenises_prev_intext" => print_r(array($prevProp['jenis'], true)),
                    );

                    foreach ($masterReplacers as $key => $val) {
                        $conData[$cCode]['tableIn_master'][$key] = $val;
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
                            //                    $cValues[$i][$param] = $conData[$cCode]['main'][$param];
                            //                    echo "filling $param with " . $conData[$cCode]['main'][$param] . "<br>";
                            $cValues[$i][$param] = $conData[$cCode]['main'][$param];
                            //                    echo "filling $param with " . $conData[$cCode]['main'][$param] . "<br>";
                        }
                        $cRawValues = implode("|", $cValues[$i]);
                        $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

                    }

                    $tmpNomorNotaConnecting = $tmpNomorNota2 = $paramSpec['paramString'];
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
                                $cValues[$i][$param] = $conData[$cCode]['main'][$param];
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
                    $ccn->setTransaksiGate($conData[$cCode]['tableIn_master']);
                    $ccn->setMainGate($conData[$cCode]['main']);
                    $ccn->setItemsGate($conData[$cCode]['items']);
                    $ccn->setItems2SumGate($conData[$cCode]['items2_sum']);
                    $new_counter = $ccn->getCounterNumber();
                    cekHitam("jenistr yang disett dari create " . $this->jenisTr);


                    if (isset($new_counter['main']) && sizeof($new_counter['main']) > 0) {
                        foreach ($new_counter['main'] as $ckey => $cval) {
                            $conData[$cCode]['tableIn_master'][$ckey] = $cval;
                            $conData[$cCode]['main'][$ckey] = $cval;
                        }
                    }
                    if (isset($new_counter['items']) && sizeof($new_counter['items']) > 0) {
                        foreach ($new_counter['items'] as $ikey => $iSpec) {
                            foreach ($iSpec as $iikey => $iival) {
                                $conData[$cCode]['items'][$ikey][$iikey] = $iival;
                            }
                        }
                    }
                    if (isset($new_counter['items2_sum']) && sizeof($new_counter['items2_sum']) > 0) {
                        foreach ($new_counter['items2_sum'] as $ikey => $iSpec) {
                            foreach ($iSpec as $iikey => $iival) {
                                $conData[$cCode]['items2_sum'][$ikey][$iikey] = $iival;
                            }
                        }
                    }
                    //endregion
                    $addValues = array(
                        'counters' => $appliedCounters2,
                        'counters_intext' => $appliedCounters_inText2,
                        'nomer' => $tmpNomorNota2,
                        'nomer2' => $tmpNomorNota2Alias,
                        'dtime' => date("Y-m-d H:i:s"),
                        'fulldate' => date("Y-m-d"),
                    );
                    foreach ($addValues as $key => $val) {
                        $conData[$cCode]['tableIn_master'][$key] = $val;
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
                        $conData[$cCode]['tableIn_master'][$key] = $val;
                    }

                    //===cloning detail/items cabang1 ke cabang2
                    //===yang direplace: sub_step_number, sub_step_current, sub_step_avail, next_substep_num, next_substep_code, next_substep_label, next_subgroup_code
                    $detailReplacers = array(
                        "sub_step_avail" => sizeof($configUiMasterModulJenis['steps']),
                        "sub_step_current" => 1,
                        "sub_step_number" => 1,
                        "next_substep_num" => $conData[$cCode]['tableIn_master']['next_step_num'],
                        "next_substep_code" => $conData[$cCode]['tableIn_master']['next_step_code'],
                        "next_substep_label" => $conData[$cCode]['tableIn_master']['next_step_label'],
                        "next_subgroup_code" => $conData[$cCode]['tableIn_master']['next_group_code'],
                        //                    "next_substep_code" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['target'] : "",
                        //                    "next_substep_label" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['label'] : "",
                        //                    "next_subgroup_code" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['userGroup'] : "",
                    );
                    if (isset($conData[$cCode]['tableIn_detail']) && sizeof($conData[$cCode]['tableIn_detail']) > 0) {
                        //                    cekmerah("tulis rincian transaksi kedua");
                        foreach ($conData[$cCode]['tableIn_detail'] as $k => $dSpec) {
                            foreach ($dSpec as $key => $val) {
                                $conData[$cCode]['tableIn_detail'][$k][$key] = isset($detailReplacers[$key]) ? $detailReplacers[$key] : $val;
                            }
                        }
                    }
                    else {
                        //                    cekmerah("GAGAL tulis rincian transaksi kedua");
                    }


                    //region ----------write transaksi & transaksi_data #2
                    if (isset($conData[$cCode]['tableIn_master']) && sizeof($conData[$cCode]['tableIn_master']) > 0) {
                        $conData[$cCode]['tableIn_master']['project_id'] = $conData[$cCode]['main']['current_projectID'];
                        $conData[$cCode]['tableIn_master']['project_nama'] = $conData[$cCode]['main']['projectName'];
                        $tr = new MdlTransaksi();
                        $tr->addFilter("transaksi.cabang_id='" . $this->session->login['cabang_id'] . "'");
                        $insertConnectingID = $insertID = $tr->writeMainEntries($conData[$cCode]['tableIn_master']);
                        cekUngu($this->db->last_query());
                        $epID = $tr->writeMainEntries_entryPoint($insertID, $masterID, $conData[$cCode]['tableIn_master']);
                        $insertNum = $conData[$cCode]['tableIn_master']['nomer'];
                        $conData[$cCode]['main']['nomer'] = $insertNum;
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
                    if (isset($conData[$cCode]['tableIn_master_values']) && sizeof($conData[$cCode]['tableIn_master_values']) > 0) {
                        $inserMainValues = array();
                        foreach ($conData[$cCode]['tableIn_master_values'] as $key => $val) {
                            $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                            $inserMainValues[] = $dd;
                            $mongoListConnect['mainValues'][] = $dd;
                        }
                        if (sizeof($inserMainValues) > 0) {
                            $arrBlob = blobEncode($inserMainValues);
                            $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                        }
                    }
                    if (isset($conData[$cCode]['main_add_values']) && sizeof($conData[$cCode]['main_add_values']) > 0) {
                        foreach ($conData[$cCode]['main_add_values'] as $key => $val) {
                            $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                            $mongoListConnect['mainValues'][] = $dd;
                        }
                    }
                    if (isset($conData[$cCode]['main_inputs']) && sizeof($conData[$cCode]['main_inputs']) > 0) {
                        foreach ($conData[$cCode]['main_inputs'] as $key => $val) {
                            $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                            $inserMainValues[] = $dd;
                            $mongoListConnect['mainValues'][] = $dd;
                        }
                    }
                    if (isset($conData[$cCode]['main_elements']) && sizeof($conData[$cCode]['main_elements']) > 0) {
                        //                    cekMerah("ada mainElements");
                        foreach ($conData[$cCode]['main_elements'] as $elName => $aSpec) {
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
                    if (isset($conData[$cCode]['tableIn_detail']) && sizeof($conData[$cCode]['tableIn_detail']) > 0) {
                        $insertIDs = array();
                        $insertDeIDs = array();
                        foreach ($conData[$cCode]['tableIn_detail'] as $dSpec) {
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
                    if (isset($conData[$cCode]['tableIn_detail2_sum']) && sizeof($conData[$cCode]['tableIn_detail2_sum']) > 0) {
                        $insertIDs = array();
                        foreach ($conData[$cCode]['tableIn_detail2_sum'] as $dSpec) {
                            $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                            $mongoListConnect['detail'] = $insertIDs;
                            if ($epID != 999) {
                                $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                                $mongoListConnect['detail'] = $mongoListConnect['detail'] = $insertIDs;;
                            }
                        }
                    }
                    if (isset($conData[$cCode]['tableIn_detail_values']) && sizeof($conData[$cCode]['tableIn_detail_values']) > 0) {
                        $insertIDs = array();
                        foreach ($conData[$cCode]['tableIn_detail_values'] as $pID => $dSpec) {
                            if (isset($this->configCore[$this->jenisTr]['tableIn']['detailValues'])) {
                                foreach ($this->configCore[$this->jenisTr]['tableIn']['detailValues'] as $key => $src) {
                                    //                                $insertIDs[$pID][] = $tr->writeDetailValues($insertID, array(
                                    //                                    "produk_jenis" => $conData[$cCode]['tableIn_detail'][$pID]['produk_jenis'],
                                    //                                    "produk_id" => $pID,
                                    //                                    "key" => $key,
                                    //                                    "value" => isset($dSpec[$src]) ? $dSpec[$src] : 0,
                                    //                                ));
                                    $dd = $tr->writeDetailValues($insertID, array(
                                        "produk_jenis" => $conData[$cCode]['tableIn_detail'][$pID]['produk_jenis'],
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
                    if (isset($conData[$cCode]['tableIn_detail_values2_sum']) && sizeof($conData[$cCode]['tableIn_detail_values2_sum']) > 0) {
                        foreach ($conData[$cCode]['tableIn_detail_values2_sum'] as $pID => $dSpec) {
                            if (isset($this->configCore[$this->jenisTr]['tableIn']['detailValues2_sum'])) {
                                foreach ($this->configCore[$this->jenisTr]['tableIn']['detailValues2_sum'] as $key => $src) {
                                    $insertIDs[] = $tr->writeDetailValues($insertID, array(
                                        "produk_jenis" => $conData[$cCode]['tableIn_detail2_sum'][$pID]['produk_jenis'],
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
                    //                $stepCode = $configUiMasterModulJenis['steps'][1]['target'];
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
                                    "extern_id" => $conData[$cCode]['main'][$externSrc['id']],
                                    "extern_nama" => $conData[$cCode]['main'][$externSrc['nama']],
                                    "nomer" => $tmpNomorNota2,
                                    "label" => $paymentSrcConfig['label'],
                                    "tagihan" => $conData[$cCode]['main'][$valueSrc],
                                    "terbayar" => 0,
                                    "sisa" => $conData[$cCode]['main'][$valueSrc],
                                    "cabang_id" => $conData[$cCode]['main']['placeID'],
                                    "cabang_nama" => $conData[$cCode]['main']['placeName'],
                                    "oleh_id" => $this->session->login['id'],
                                    "oleh_nama" => $this->session->login['nama'],
                                    "dtime" => date("Y-m-d H:i:s"),
                                    "fulldate" => date("Y-m-d"),
                                    "valas_id" => isset($conData[$cCode]['main'][$externSrc['valasId']]) ? $conData[$cCode]['main'][$externSrc['valasId']] : '',
                                    "valas_nama" => isset($conData[$cCode]['main'][$externSrc['valasLabel']]) ? $conData[$cCode]['main'][$externSrc['valasLabel']] : '',
                                    "valas_nilai" => isset($conData[$cCode]['main'][$externSrc['valasValue']]) ? $conData[$cCode]['main'][$externSrc['valasValue']] : '',
                                    "tagihan_valas" => isset($conData[$cCode]['main'][$externSrc['valasTagihan']]) ? $conData[$cCode]['main'][$externSrc['valasTagihan']] : '',
                                    "terbayar_valas" => 0,
                                    "sisa_valas" => isset($conData[$cCode]['main'][$externSrc['valasSisa']]) ? $conData[$cCode]['main'][$externSrc['valasSisa']] : '',
                                ));
                            }
                        }


                        //cekMerah($this->db->last_query());

                    }
                    else {
                        //cekMerah("TIDAK nulis paymentSrc");
                    }
                    //endregion


                    //region nulis paymentAntiSource
                    //                $stepCode = $configUiMasterModulJenis['steps'][1]['target'];
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
                                    "extern_id" => $conData[$cCode]['main'][$externSrc['id']],
                                    "extern_nama" => $conData[$cCode]['main'][$externSrc['nama']],
                                    "nomer" => $tmpNomorNota2,
                                    "label" => $paymentSrcConfig['label'],
                                    "tagihan" => $conData[$cCode]['main'][$valueSrc],
                                    "terbayar" => 0,
                                    "sisa" => $conData[$cCode]['main'][$valueSrc],
                                    "cabang_id" => $conData[$cCode]['main']['placeID'],
                                    "cabang_nama" => $conData[$cCode]['main']['placeName'],
                                    "oleh_id" => $this->session->login['id'],
                                    "oleh_nama" => $this->session->login['nama'],
                                    "dtime" => date("Y-m-d H:i:s"),
                                    "fulldate" => date("Y-m-d"),
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
                        "olehID" => $conData[$cCode]['main']['olehID'],
                        "olehName" => $conData[$cCode]['main']['olehName'],
                        "step" => $stepNum,
                        "trID" => $insertID,
                        "nomer" => $tmpNomorNota2,
                        "nomer2" => $tmpNomorNota2Alias,
                        "counters" => $appliedCounters2,
                        "counters_intext" => $appliedCounters_inText2,
                        "dtime" => date("Y-m-d H:i:s"),
                        "fulldate" => date("Y-m-d"),
                    );
                    $idHis_blob = blobEncode($idHis_decode);
                    $idHis_intext = print_r($idHis_decode, true);

                    $conData[$cCode]['tableIn_master']['ids_his'] = $idHis_blob;
                    $conData[$cCode]['tableIn_master']['ids_his_intext'] = $idHis_intext;

                    $tr = new MdlTransaksi();
                    $dupState = $tr->updateData(array("id" => $insertID), array(
                        "id_master" => $masterID,
                        "id_top" => $insertID,

                        "ids_his" => $idHis_blob,
                        "ids_his_intext" => $idHis_intext,

                    )) or die("Failed to update tr next-state!");

                    //                if($seluruhnya){
                    //                    $dupState = $tr->updateData(array("id" => $insertID), array(
                    //                        "tail_number" => $nextStepNum,
                    //                        "tail_code" => $configUiMasterModulJenis['steps'][$nextStepNum]['target'],
                    //
                    //                    )) or die("Failed to update trans tail!");
                    //                }
                    //cekHijau($this->db->last_query());


                    $baseRegistries = array(
                        'main' => isset($conData[$cCode]['main']) ? $conData[$cCode]['main'] : array(),
                        'items' => isset($conData[$cCode]['items']) ? $conData[$cCode]['items'] : array(),
                        'items2' => isset($conData[$cCode]['items2']) ? $conData[$cCode]['items2'] : array(),
                        'items2_sum' => isset($conData[$cCode]['items2_sum']) ? $conData[$cCode]['items2_sum'] : array(),
                        'itemSrc' => isset($conData[$cCode]['itemSrc']) ? $conData[$cCode]['itemSrc'] : array(),
                        'itemSrc_sum' => isset($conData[$cCode]['itemSrc_sum']) ? $conData[$cCode]['itemSrc_sum'] : array(),
                        'items3' => isset($conData[$cCode]['items3']) ? $conData[$cCode]['items3'] : array(),
                        'items3_sum' => isset($conData[$cCode]['items3_sum']) ? $conData[$cCode]['items3_sum'] : array(),
                        'items4' => isset($conData[$cCode]['items4']) ? $conData[$cCode]['items4'] : array(),
                        'items4_sum' => isset($conData[$cCode]['items4_sum']) ? $conData[$cCode]['items4_sum'] : array(),
                        'items5_sum' => isset($conData[$cCode]['items5_sum']) ? $conData[$cCode]['items5_sum'] : array(),
                        'items6_sum' => isset($conData[$cCode]['items6_sum']) ? $conData[$cCode]['items6_sum'] : array(),
                        'items7_sum' => isset($conData[$cCode]['items7_sum']) ? $conData[$cCode]['items7_sum'] : array(),
                        'items8_sum' => isset($conData[$cCode]['items8_sum']) ? $conData[$cCode]['items8_sum'] : array(),
                        'items9_sum' => isset($conData[$cCode]['items9_sum']) ? $conData[$cCode]['items9_sum'] : array(),
                        'items10_sum' => isset($conData[$cCode]['items10_sum']) ? $conData[$cCode]['items10_sum'] : array(),
                        'items_noapprove' => isset($conData[$cCode]['items_noapprove']) ? $conData[$cCode]['items_noapprove'] : array(),

                        'rsltItems' => isset($conData[$cCode]['rsltItems']) ? $conData[$cCode]['rsltItems'] : array(),
                        'rsltItems2' => isset($conData[$cCode]['rsltItems2']) ? $conData[$cCode]['rsltItems2'] : array(),
                        'rsltItems3' => isset($conData[$cCode]['rsltItems3']) ? $conData[$cCode]['rsltItems3'] : array(),

                        'tableIn_master' => isset($conData[$cCode]['tableIn_master']) ? $conData[$cCode]['tableIn_master'] : array(),
                        'tableIn_detail' => isset($conData[$cCode]['tableIn_detail']) ? $conData[$cCode]['tableIn_detail'] : array(),
                        'tableIn_detail2_sum' => isset($conData[$cCode]['tableIn_detail2_sum']) ? $conData[$cCode]['tableIn_detail2_sum'] : array(),
                        'tableIn_detail_rsltItems' => isset($conData[$cCode]['tableIn_detail_rsltItems']) ? $conData[$cCode]['tableIn_detail_rsltItems'] : array(),
                        'tableIn_detail_rsltItems2' => isset($conData[$cCode]['tableIn_detail_rsltItems2']) ? $conData[$cCode]['tableIn_detail_rsltItems2'] : array(),
                        'tableIn_master_values' => isset($conData[$cCode]['tableIn_master_values']) ? $conData[$cCode]['tableIn_master_values'] : array(),
                        'tableIn_detail_values' => isset($conData[$cCode]['tableIn_detail_values']) ? $conData[$cCode]['tableIn_detail_values'] : array(),
                        'tableIn_detail_values_rsltItems' => isset($conData[$cCode]['tableIn_detail_values_rsltItems']) ? $conData[$cCode]['tableIn_detail_values_rsltItems'] : array(),
                        'tableIn_detail_values_rsltItems2' => isset($conData[$cCode]['tableIn_detail_values_rsltItems2']) ? $conData[$cCode]['tableIn_detail_values_rsltItems2'] : array(),
                        'tableIn_detail_values2_sum' => isset($conData[$cCode]['tableIn_detail_values2_sum']) ? $conData[$cCode]['tableIn_detail_values2_sum'] : array(),
                        'main_add_values' => isset($conData[$cCode]['main_add_values']) ? $conData[$cCode]['main_add_values'] : array(),
                        'main_add_fields' => isset($conData[$cCode]['main_add_fields']) ? $conData[$cCode]['main_add_fields'] : array(),
                        'main_elements' => isset($conData[$cCode]['main_elements']) ? $conData[$cCode]['main_elements'] : array(),
                        'main_inputs' => isset($conData[$cCode]['main_inputs']) ? $conData[$cCode]['main_inputs'] : array(),
                        'main_inputs_orig' => isset($conData[$cCode]['main_inputs']) ? $conData[$cCode]['main_inputs'] : array(),
                        "receiptDetailFields" => isset($configLayoutMasterModulJenis['receiptDetailFields'][1]) ? $configLayoutMasterModulJenis['receiptDetailFields'][1] : array(),
                        "receiptSumFields" => isset($configLayoutMasterModulJenis['receiptSumFields'][1]) ? $configLayoutMasterModulJenis['receiptSumFields'][1] : array(),
                        "receiptDetailFields2" => isset($configLayoutMasterModulJenis['receiptDetailFields2'][1]) ? $configLayoutMasterModulJenis['receiptDetailFields2'][1] : array(),
                        "receiptSumFields2" => isset($configLayoutMasterModulJenis['receiptSumFields2'][1]) ? $configLayoutMasterModulJenis['receiptSumFields2'][1] : array(),
                        "receiptDetailSrcFields" => isset($configLayoutMasterModulJenis['receiptDetailSrcFields'][1]) ? $configLayoutMasterModulJenis['receiptDetailSrcFields'][1] : array(),
                        "items_komposisi" => isset($conData[$cCode]['items_komposisi']) ? $conData[$cCode]['items_komposisi'] : array(),
                        "componentsBuilder" => isset($conData[$cCode]['componentsBuilder']) ? $conData[$cCode]['componentsBuilder'] : array(),
                        "jurnalItems" => isset($conData[$cCode]['jurnalItems']) ? $conData[$cCode]['jurnalItems'] : array(),
                        "jurnal_index" => isset($conData[$cCode]['jurnal_index']) ? $conData[$cCode]['jurnal_index'] : array(),
                        "postProcessor" => isset($conData[$cCode]['postProcessor']) ? $conData[$cCode]['postProcessor'] : array(),
                        "preProcessor" => isset($conData[$cCode]['preProcessor']) ? $conData[$cCode]['preProcessor'] : array(),
                        "revert" => isset($conData[$cCode]['revert']) ? $conData[$cCode]['revert'] : array(),

                    );
                    $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
                    $mongRegIDConnect = $doWriteReg;
                    //endregion


                    //==================================================================================================
                    //==MENULIS LOCKER TRANSAKSI ACTIVE=================================================================
                    $this->load->model("Mdls/MdlLockerTransaksi");
                    $lt = New MdlLockerTransaksi();
                    $lt->execLocker($conData[$cCode]['main'], $nextProp['num'], NULL, $insertID);
                    //                mati_disini();
                    //==================================================================================================
                }
                else {
                    cekMerah("to be delayed to connect to $connector");
                }
            }
            else {
                //cekKuning("not connecting to any tCode");
            }
            //endregion

            $returnTransaksi = array(
                "transaksi_id" => $insertTransaksiID,
                "transaksi_nomer" => $tmpNomorNota2_current,
                "transaksi_id_connecting" => $insertConnectingID,
                "transaksi_nomer_connecting" => isset($tmpNomorNotaConnecting) ? $tmpNomorNotaConnecting : "",
            );
            return $returnTransaksi;
        }
        else {
            $masterID = 0;
            $tmpNomorNota = "XXXX";
            $origJenis = 0;
            $topID = 0;
            mati_disini(("No such receipt ID: $no, pada step: $stepNumCurrent, // code: " . __LINE__));
        }


    }
}