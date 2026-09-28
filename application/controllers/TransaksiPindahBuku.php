<?php

class TransaksiPindahBuku extends CI_Controller
{
    private $template;
    private $jenisTr;
    private $jenisTrName;
    private $trConfig;
    private $tableInConfig;
    private $tableInConfig_static;
    private $arrButtonAction;
    private $dates = array();

    public function __construct()
    {
        parent::__construct();
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        validateUserSession($this->session->login['id']);

        $this->load->config("heWebs");
        //        arrPrintWebs($this->session->login);

        $this->load->helper("he_stepping");
        $this->load->helper("he_access_right");
        $this->load->library("MobileDetect");
        $this->load->helper("he_session_replacer");
        $this->load->model("Mdls/MdlCurrency");
        $this->load->helper('he_angka');
        $tmpJenis = $this->uri->segment(3);
        $tmpJenis = "7778";
        $this->allSteps = isset($this->config->item("heTransaksi_ui")[$tmpJenis]['steps']) ? $this->config->item("heTransaksi_ui")[$tmpJenis]['steps'] : array();
        if (strlen($tmpJenis) > 0) {
            $this->jenisTr = $tmpJenis;


            //            $membership = is_array($this->session->login['membership'])?$this->session->login['membership']:array();
            //            $steps=$this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'];
            //            $jmlAllowed=0;
            //            if(sizeof($steps)>0){
            //                foreach($steps as $num=>$sSpec){
            //                    if(in_array($sSpec['userGroup'],$membership)){
            //                        $jmlAllowed++;
            //                    }
            //                }
            //            }
            //            if($jmlAllowed<1){
            //                //cekMerah("__ILLEGAL ACCESS ATTEMPT__");die();
            //            }

            //            //cekMerah("bikin jenisTR ". $this->jenisTr);
            //            $this->jenisTr = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['target']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['target'] : $tmpJenis;
            $this->jenisTrName = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['label']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['label'] : "unnamed";

            $heTransaksi_ui = (null != $this->config->item("heTransaksi_ui")) ? $this->config->item("heTransaksi_ui") : array();
            if (sizeof($heTransaksi_ui) > 0) {
                $this->template = isset($heTransaksi_ui[$this->jenisTr]) ? base_url() . "template/" . $heTransaksi_ui[$this->jenisTr]['template'] . ".html" : "";
            }
            else {
                die("konfigurasi transaksi belum ditentukan");
            }
            //            $this->trConfig = (null != $this->config->item("heTransaksi_ui")[$this->jenisTr]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr] : array();
            $this->trConfig = (isset($this->config->item("heTransaksi_ui")[$this->jenisTr])) ? $this->config->item("heTransaksi_ui")[$this->jenisTr] : array();
        }
        else {
            // die("trJenis required!");

        }

        $this->load->model("CustomCounter");
        $this->load->model("MdlTransaksi");
        $this->tableInConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn'] : array();
        $this->tableInConfig_static = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn_static']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn_static'] : array();
        $this->arrButtonAction = $this->config->item("button");

        $trd = new MdlTransaksi();
        $trd->addFilter("jenis_top='" . $this->jenisTr . "'");
        $this->dates = $trd->lookupDates();
        $this->dates['entries'][date("y-m-d")] = date("y-m-d");
        //        arrPrint($this->session->login);
        $this->accessList = alowedAccess($this->session->login['id']);
        // arrPrint($this->session->login['cabang_id']);
        $this->placeId = $this->session->login['cabang_id'];
//        $this->tokoId = $this->session->login['toko_id'];

        $this->transaksiMaintenance = $this->config->item("maintenanceTransaksi") != null && $this->config->item("maintenanceTransaksi") == true ? true : false;
        $this->transaksiMaintenanceMsg = isset($this->config->item("maintenanceOptions")[1]) ? $this->config->item("maintenanceOptions")[1] : array();
        $this->registryFields = $trd->getRegistryFields();

    }

    public function index()
    {
        $starttime = microtime(true);
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
        }
        $scriptBottom = "";
        $sesionReplacer = replaceSession();
        $jenisTr = $this->uri->segment(3);
        //        $cCode = "_TR_" . $this->jenisTr;
        //        $paymentConfig = isset($this->config->item("heTransaksi_ui")[$jenisTr]['paymentConfig']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['paymentConfig'] : false;
        //        $paymentConfigMultiStep = isset($this->config->item("heTransaksi_ui")[$jenisTr]['paymentConfigMultiStep']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['paymentConfigMultiStep'] : false;
        //        $historyFields = isset($this->config->item("heTransaksi_ui")[$jenisTr]['shortHistoryFields']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['shortHistoryFields'] : array();
        //        $kepoinFields = isset($this->config->item("heTransaksi_ui")[$jenisTr]['shortKepoinFields']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['shortKepoinFields'] : array();
        //        $pairRegistries = isset($this->config->item("heTransaksi_ui")[$jenisTr]['pairRegistries']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['pairRegistries'] : array();
        //        $connectTo = isset($this->config->item("heTransaksi_ui")[$jenisTr]['connectTo']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['connectTo'] : "";
        //        $stepHistoryFields = isset($this->config->item("heTransaksi_ui")[$jenisTr]['shortStepHistoryFields']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['shortStepHistoryFields'] : array();
        //        $shoppingCartPerTransaksiBtn = isset($this->config->item("heTransaksi_ui")[$jenisTr]['shoppingCartPerTransaksiBtn']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['shoppingCartPerTransaksiBtn'] : 0;
        //        $shoppingCartPerTransaksi = isset($this->config->item("heTransaksi_ui")[$jenisTr]['shoppingCartPerTransaksi']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['shoppingCartPerTransaksi'] : 0;
        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        if ($isMob) {
            //            $historyFields = isset($this->config->item("heTransaksi_ui")[$jenisTr]['compactHistoryFields']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['compactHistoryFields'] : array();
        }
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();

        //
        //        $jenisTr = $this->jenisTr;
        //        $sesNama = my_name();
        //        $confSettlement = $this->config->item('settlement');
        //        $warning = "";
        //        if ($confSettlement['restrictions'] == true) {
        //            $condites = array(
        //                "date(dtime)<" => dtimeNow('Y-m-d'),
        //            );
        //            $this->db->where($condites);
        //            $mySettlementDatas = $tr->callMyTransaksi(my_id(), my_cabang_id());
        //            // showLast_query("lime");
        //            $jmlOldTransaksi = sizeof($mySettlementDatas);
        //            // cekPink($jmlOldTransaksi);
        //
        //            if ($jmlOldTransaksi > 0) {
        //                // cekHitam();
        //                $alerts = array(
        //                    "type" => "error",
        //                    "title" => "Peringatan!",
        //                    "html" => "Hi $sesNama, Masih ada setelmen belum diselesaikan, silahkan diselesaikan terlebih dahulu",
        //                    // "allowOutsideClick" => true,
        //                    // "allowEscapeKey" => false,
        //                    // "confirmButtonText" => "Settlement",
        //                );
        //
        //                $warning = swalAlertSettlement($alerts, $jenisTr);
        //            }
        //            // echo $warning;
        //        }
        // -----------end off settlement--------

        // $mobileSupport = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]["mobileSupport"]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]["mobileSupport"] : false;

        //---------------------------------------------------------

        $download_coa = array(
            "lajur" => base_url() . "ExcelWriter/download_coa/lajur",
            "neraca" => base_url() . "ExcelWriter/download_coa/neraca",
        );
        $upload_coa = base_url() . "TransaksiPindahBuku/save";

        /* ------------------------------------------------
         *  companu profile cek
         * ------------------------------------------------*/
        $this->load->model("Mdls/MdlCompany");
        $cp = new MdlCompany();
//        $cp->setTokoId(my_toko_id());

        $cpSrc = $cp->callDatas();
        $neracaStatus = $cpSrc->neraca_ok;
        // cekOrange($neracaStatus);
        if ($neracaStatus == 1) {
            $btn_status = "disabled";
        }
        else {
            $btn_status = "";
        }

        //region prepare params to viewer
        $data = array(
            "mode" => $this->uri->segment(2),
            "title" => "Pemindahbukuan",
            "subTitle" => "",
            "trName" => "Pemindahbukuan",
            "download_coa" => $download_coa,
            "upload_coa" => $upload_coa,
            "btn_status" => $btn_status,
            //            "isMobile" => $isMob,
            //            "mobileSupport" => isset($mobileSupport) ? $mobileSupport : "",
            //            "errMsg" => $this->session->errMsg,
            //            "template" => $this->config->item("heTransaksi_ui")[$jenisTr]["template"],
            //
            //            "jenisTr" => $jenisTr,
            //
            //            'addLink' => $addLink,
            //            //            "historyTitle" => "<span class='glyphicon glyphicon-time'></span> " . $this->config->item("heTransaksi_ui")[$jenisTr]["label"] . " histories",
            //            "historyTitle" => "<span class='glyphicon glyphicon-time'></span> recent " . $this->config->item("heTransaksi_ui")[$jenisTr]["label"] . " histories",
            //            "arrayHistoryLabels" => array("dtime" => "time") + $historyFields,
            //            "arrayHistory" => $arrayHistory,
            //            "onprogressTitle" => "<span class='glyphicon glyphicon-alert'></span> incomplete " . $this->config->item("heTransaksi_ui")[$jenisTr]["label"],
            //            "arrayProgressLabels" => $progressFields,
            //            "arrayOnProgressToPay" => $paymentConfig,
            //            "stepHistoryFields" => array(),
            //            // ---
            //            "onprogressViewTitle" => "<span class='fa fa-eye'></span> show incomplete step " . $this->config->item("heTransaksi_ui")[$jenisTr]["label"],
            //            // "arrayProgressViewLabels" => $progressFields,
            //            "arrayOnProgressView" => $arrayOnProgressView,
            //            "connectTo" => $connectTo,
            //            "kepoinFields" => $kepoinFields,
            //            //--
            //            "itemLabels" => isset($itemLabels) ? $itemLabels : "",
            //            "srcLabel" => isset($srcLabel) ? $srcLabel : "",
            //            "arrayOnProgress" => $arrayOnprogress,
            //            "entities" => $entities,
            //            //            "recapTitle" => "<span class='fa fa-newspaper-o'></span> today " . $this->config->item("heTransaksi_ui")[$jenisTr]["label"] . " reports (" . date("F Y") . ")",
            //            "recapTitle" => "<span class='fa fa-newspaper-o'></span> today " . $this->config->item("heTransaksi_ui")[$jenisTr]["label"] . " reports",
            //            "arrayRecapLabels" => $recapLabels,
            //            "arrayRecap" => $arrayRecap,
            //            "warning" => $warning,
            //
            //
            //            //----
            //            "onprogressSettlementTitle" => "<span class='fa fa-eye'></span> SETTLEMENT PENYETORAN KAS",
            //            "arrayOnprogressSettlement" => isset($arrayOnprogressSettlement) ? $arrayOnprogressSettlement : array(),
            //            "arrayOnprogressSettlementLabels" => isset($this->config->item("heTransaksi_ui")[$jenisTr]["settlementHistoryFields"]) ? $this->config->item("heTransaksi_ui")[$jenisTr]["settlementHistoryFields"] : array(),
            //            "settlementEnabled" => isset($settlementEnabled) ? $settlementEnabled : false,
            //            "showBtnSettlement" => isset($settleBtnShow) ? $settleBtnShow : false,
            //            "settlementTarget" => isset($this->config->item("heTransaksi_ui")[$jenisTr]["settlementTarget"]) ? $this->config->item("heTransaksi_ui")[$jenisTr]["settlementTarget"] : "",
            //
            //
            //            //-----
            //            "arrayOnProgressTransaksi" => isset($arrayOnprogressTransaksi) ? $arrayOnprogressTransaksi : array(),
            //            "arrayOnProgressTransaksiKoloms" => isset($arrayOnprogressTransaksiKoloms) ? $arrayOnprogressTransaksiKoloms : array(),
            //            "shoppingCartPerTransaksiBtn" => isset($shoppingCartPerTransaksiBtn) ? $shoppingCartPerTransaksiBtn : array(),
            "simple" => isset($_GET["s"]) ? $_GET["s"] : 0,
        );
        //endregion

        $this->load->view("transaksiPindahBuku", $data);

    }

    public function save()
    {
        $post = $_POST;
        $files = $_FILES["userfile"];
        //        arrPrintPink($post);
        //        arrPrintPink($files);

        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'xlsx|csv|xls|ods';
        //        $config['max_size'] = 100;

        $file_name = $files['name'];
        $this->load->library('upload', $config);
        //arrPrint($this->upload->do_upload('userfile'));

        if (!$this->upload->do_upload('userfile')) {
            cekHitam("TIDAK ada UPLOAD...");

            $error = array('error' => $this->upload->display_errors());

            $keterangan = "<br>format file yang diupload harus berextensi <b>xlsx</b>";
            echo lgShowWarning('salah file', "file anda:: $file_name $keterangan");
            return $error;
        }
        else {
            cekHijau("ada UPLOAD...");
            $data = array('upload_data' => $this->upload->data());
            $tokoID = $this->session->login['toko_id'];
            $this->load->model("Mdls/MdlCompany");
            $d = new MdlCompany();
            $update_data = array(
                "file_name" => $data['upload_data']['file_name'],
                "file_type" => $data['upload_data']['file_type'],
                "full_path" => $data['upload_data']['full_path'],
                "file_ext" => $data['upload_data']['file_ext'],
            );
            $where = array(
                "toko_id" => $tokoID,
            );

            $d->updateData($where, $update_data);

            echo lgShowSuccess("Berhasil", "File $file_name sudah berhasil disimpan");


            //--- memasukkan ke tabel transaksi_pemindahbukuan (data sesuai dengan excellnya)
            echo "<script>";
            echo "  top.$('#result').load('" . base_url() . "Cli/saveFileNeraca/$tokoID')";
            echo "</script>";

        }


        //        mati_disini("MASUK SAVE");
    }

    public function viewNeracaTmp()
    {

        $this->load->model("Mdls/MdlTransaksiPemindahbukuan");
        $pb = new MdlTransaksiPemindahbukuan();
        $condites = array(
            "status" => 1,

        );

        $this->db->where($condites);
        $srcPbs = $pb->lookupAll()->result();
//        cekHitam($this->db->last_query());
        $rekGede = array();
        $rekAkumulasiGede = array();
        $rekAkumulasiDetail = array();
        $rekCode = array();
        foreach ($srcPbs as $item) {
            $extern_id = $item->extern_id;
            $nDebet = $item->debet * 1;
            $nKredit = $item->kredit * 1;
            $parent = $item->parent;

            if ($extern_id > 0) {
                $rekGede[$item->head_code]['debet'] = $item->debet * 1;
                $rekGede[$item->head_code]['kredit'] = $item->kredit * 1;

                //----------------------------------------------------------------
                if (!isset($rekGede[$parent]['debet'])) {
                    $rekGede[$parent]['debet'] = 0;
                }
//                $rekGede[$parent]['debet'] += $nDebet;//dimatiin biar gak

                if (!isset($rekGede[$parent]['kredit'])) {
                    $rekGede[$parent]['kredit'] = 0;
                }
//                $rekGede[$parent]['kredit'] += $nKredit;


                //----------------------------------------------------------------
                if (!isset($rekAkumulasiDetail[$parent]['debet'])) {
                    $rekAkumulasiDetail[$parent]['debet'] = 0;
                }
                $rekAkumulasiDetail[$parent]['debet'] += $nDebet;//dimatiin biar gak

                if (!isset($rekAkumulasiDetail[$parent]['kredit'])) {
                    $rekAkumulasiDetail[$parent]['kredit'] = 0;
                }
                $rekAkumulasiDetail[$parent]['kredit'] += $nKredit;
                //----------------------------------------------------------------
            }
            if ($extern_id == 0 || $extern_id == "") {
                // $rekKecil[$item->p_head_code]['debet'] = $item->debet * 1;
                // $rekKecil[$item->p_head_code]['kredit'] = $item->kredit * 1;

//                $rekGede[$item->head_code]['debet'] = $item->debet * 1;
                $rekGede[$item->head_code]['kredit'] = $item->kredit * 1;
                //----------------------------------------------------------------
                $rekAkumulasiGede[$item->head_code]['debet'] = $item->debet * 1;
                $rekAkumulasiGede[$item->head_code]['kredit'] = $item->kredit * 1;
            }
            //------------
            if (($nDebet > 0) || ($nKredit > 0)) {
                $rekCode[$item->head_code] = $item->p_head_code;
            }
        }

//        arrPrintPink($rekCode);
//        arrPrintPink($rekAkumulasiDetail);
//        arrPrintKuning($rekAkumulasiGede);
        $rekAkumulasiGabungan = ($rekAkumulasiGede + $rekAkumulasiDetail);
//        arrPrintHijau($rekAkumulasiGabungan);


        $confUis = $this->config->item("heTransaksi_ui");
        foreach ($confUis as $trJenis => $confUi) {
            $place = $confUi['place'];
            $label = $confUi['label'];
            if ($place == "branch") {
                $confUiPusats[$trJenis]['label'] = $label;
            }
        }

        /*
         * cabang
         * */
        $this->load->model("Mdls/MdlAccounts");
        $cb = new MdlAccounts();
//        $cb->setTablename("acc_coa");
//        $cb->setFilters(array());
        $condites = array(
            "is_active" => "1",
//            "toko_id"   => my_toko_id(),
        );
//        $cb->addFilter("p_head_code!='1010030030'");
        $this->db->where($condites);
        $cbDatas = $cb->lookupAll()->result();
//        arrPrint($cbDatas);

        /* ------------------------------------------------
         *  companu profile cek
         * ------------------------------------------------*/
        $this->load->model("Mdls/MdlCompany");
        $cp = new MdlCompany();
        $cp->setTokoId(my_toko_id());

        $cpSrc = $cp->callDatas();
        $neracaStatus = $cpSrc->neraca_ok;

        $data = array(
            "mode" => "viewNeracaTmp",
            "hirarkies" => $cbDatas,
            "fields" => $cb->getFields(),
            "rekGede" => $rekGede,
            "cp_data" => $cpSrc,
            "neraca_status" => $neracaStatus,
            "toko_id" => my_toko_id(),
            //--------------------------
            "rekAkumulasiGabungan" => $rekAkumulasiGabungan,
//            "rekAkumulasiGabungan" => array(),
            "simple" => isset($_GET["s"]) ? $_GET["s"] : 0,
        );
        $this->load->view("home", $data);
    }

    public function prosesEdit()
    {

        cekBiru(url_segment());

        $toko_id = url_segment(3);
        $posisi = url_segment(4);
        $coa = url_segment(5);
        $nilai_old = url_segment(6);
        $nilai_new = url_segment(7);
        $tgl_now = dtimeNow('Y-m-d');

        if ($nilai_old != $nilai_new) {
            $this->db->trans_start();

            $this->load->model("Mdls/MdlTransaksiPemindahbukuan");
            $pb = new MdlTransaksiPemindahbukuan();
            $condites = array(
                "toko_id" => $toko_id,
                "head_code" => $coa,
                "date(dtime)" => $tgl_now,
                "status" => 1,
            );
            $this->db->where($condites);
            $src_pbs = $pb->lookupAll($condites)->row();
//            showLast_query("lime");
//            arrPrintPink($src_pbs);

            // ------------------------------start
            $this->load->model("Mdls/MdlAccounts");
            $cb = new MdlAccounts();
            $condites = array(
                "is_active" => "1",
                "toko_id" => $toko_id,
                "head_code" => $coa,
//                "p_head_code" => $coa,
            );
            $this->db->where($condites);
            $cbDatas = $cb->lookupAll()->row();

//            showLast_query("kuning");
            arrPrintWebs($cbDatas);

            // ------------------------------end

            $newDatas = array(
                "toko_id" => $toko_id,
                "p_head_code" => isset($cbDatas->p_head_code) ? $cbDatas->p_head_code : 0,

//                "p_head_code"   => $coa,

                "dtime" => $tgl_now,
                "status" => 1,
                $posisi => $nilai_new,
                "extern_id" => isset($cbDatas->extern_id) ? $cbDatas->extern_id : 0,
                "head_code" => isset($cbDatas->head_code) ? $cbDatas->head_code : 0,
                "parent" => isset($cbDatas->p_head_code) ? $cbDatas->p_head_code : 0,
                "rekening" => isset($cbDatas->rekening) ? $cbDatas->rekening : "",

                "oleh_nama" => my_name(),
                "oleh_id" => my_id(),
            );
            if (sizeof($src_pbs) == 0) {
                $pb->addData($newDatas);
                showLast_query("merah");
            }
            else {
                $updCondites = array(
                    "toko_id" => $toko_id,
                    "head_code" => $coa,
                    "status" => 1,
                );
                $updDatas = array(
                    "status" => 0,
                );
                $pb->updateData($updCondites, $updDatas);
                showLast_query("kuning");
                // ---------------------------
                $pb->addData($newDatas);
                showLast_query("merah");
            }

//            matiHere(__LINE__);
            $this->db->trans_complete();
        }
        cekHitam();
    }

}
