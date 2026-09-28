<?php


class AutoLoanInterest extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        //loader config
        $this->load->config("heAccounting");

        //loader helper
        $this->load->helper("he_stepping");
        $this->load->helper("he_access_right");
        $this->load->helper("he_session_replacer");
        $this->load->helper('he_angka');

        //loader model
        $this->load->model("Mdls/MdlCurrency");
        $this->load->model("CustomCounter");
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlMongoMother");

        //loader library
        $this->load->library("MobileDetect");

        $this->load->model("Mdls/" . "MdlCliLog");
        $hTmp = new MdlCliLog();
        $tmpHData = array(
            "title" => "CLI AutoLoanInterest",
            "sub_title" => "",
            "uid" => "-100",
            "uname" => "sys",
            "dtime" => date("Y-m-d H:i:s"),
            "transaksi_id" => "",
            "deskripsi_old" => "",
            "deskripsi_new" => "",
            "jenis" => "",
            "ipadd" => $_SERVER['REMOTE_ADDR'],
            "devices" => $_SERVER['HTTP_USER_AGENT'],
            "category" => "",
            "controller" => "",
            "method" => "",
            "url" => current_url(),
        );
        $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));

    }

    public function midValidate()
    {
        $fieldMidValidatorRules = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartFieldMidValidators"]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartFieldMidValidators"] : array();
        $fieldMidPairedItemValidatorRules = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartFieldMidValidatorsPairedItem"]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartFieldMidValidatorsPairedItem"] : array();
        $rowMidValidatorRules = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartRowMidValidators"]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartRowMidValidators"] : array();
        $fieldMidComparisonValidatorRules = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartFieldMidValidatorsComparison"]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartFieldMidValidatorsComparison"] : array();

        $cCode = "_TR_" . $this->jenisTr;
        $rawPrevURL = isset($_GET['rawPrev']) ? $_GET['rawPrev'] : "";

        $errMsgs = array();
        $errLines = array();
        $errFields = array();
        $errRows = array();

        if (sizeof($rowMidValidatorRules) > 0) {
            foreach ($rowMidValidatorRules as $field => $label) {
                if (!isset($_SESSION[$cCode]['main'][$field])) {
                    $errMsgs[] = "$label is required";
                    $errRows[] = $field;
                }
            }
        }

        if (sizeof($fieldMidPairedItemValidatorRules) > 0) {
            $result = array();
            foreach ($fieldMidPairedItemValidatorRules as $field => $label) {
                if (!isset($_SESSION[$cCode]['main'][$field])) {
                    $errMsgs[] = "$field value is required";
                    $errRows[] = $field;
                }
                $result[$field] = isset($_SESSION[$cCode]['main'][$field]) ? $_SESSION[$cCode]['main'][$field] : 0;
            }

            if (($result['hpp_sumber'] - $result['hpp_target']) > 0.0000000100) {
                $selisih = $result['hpp_sumber'] - $result['hpp_target'];
                $errMsgs[] = "Nilai konversi tidak sama, silahkan cek harga per-unitnya.<br>$selisih";
                $errRows[] = "test";
            }
            elseif (($result['hpp_sumber'] - $result['hpp_target']) < -0.0000000100) {
                $selisih = $result['hpp_sumber'] - $result['hpp_target'];
                $errMsgs[] = "Nilai konversi tidak sama, silahkan cek harga per-unitnya.<br>$selisih";
                $errRows[] = "test";
            }
        }

        //        if (sizeof($rowOptValidatorRules) > 0) {
        //            foreach ($rowOptValidatorRules as $srcName => $srcSpec) {
        //                foreach ($srcSpec as $value => $pair) {
        //                    if (isset($_SESSION[$cCode]['main'][$srcName]) && $_SESSION[$cCode]['main'][$srcName] == $value) {
        //                        foreach ($pair as $k => $v) {
        //                            if (!isset($_SESSION[$cCode]['main'][$k])) {
        //                                $errMsgs[] = "$k is required";
        //                                $errRows[] = $k;
        //                            }
        //                        }
        //                    }
        //                }
        //            }
        //        }

        if (sizeof($fieldMidValidatorRules) > 0) {
            if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
                foreach ($_SESSION[$cCode]['items'] as $xid => $iSpec) {
                    $id = $iSpec['id'];
                    if ((isset($iSpec['disabled']) && $iSpec['disabled'] == "0") || !isset($iSpec['disabled'])) {
                        if (!isset($errFields[$id])) {
                            $errFields[$id] = array();
                        }
                        foreach ($fieldMidValidatorRules as $field => $label) {
                            if (!isset($iSpec[$field])) {
                                $errMsgs[] = "item $label is required";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if (!is_numeric($iSpec[$field])) {
                                $errMsgs[] = "item $label must be a valid number";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if ($iSpec[$field] < 0.5) {
                                $errMsgs[] = "item $label must be > 0";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                        }
                    }
                }
            }

            if (isset($_SESSION[$cCode]['rsltItems']) && sizeof($_SESSION[$cCode]['rsltItems']) > 0) {
                foreach ($_SESSION[$cCode]['rsltItems'] as $xid => $iSpec) {
                    $id = $iSpec['id'];
                    $name = $iSpec['nama'];
                    if ((isset($iSpec['disabled']) && $iSpec['disabled'] == "0") || !isset($iSpec['disabled'])) {
                        if (!isset($errFields[$id])) {
                            $errFields[$id] = array();
                        }
                        foreach ($fieldMidValidatorRules as $field => $label) {
                            if (!isset($iSpec[$field])) {
                                $errMsgs[] = "item $label $name is required";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if (!is_numeric($iSpec[$field])) {
                                $errMsgs[] = "item $label $name must be a valid number";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if ($iSpec[$field] < 0.5) {
                                $errMsgs[] = "item $label $name must be > 0";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                        }
                    }
                }
            }
        }

        if (sizeof($fieldMidComparisonValidatorRules) > 0) {
            $result = array();
            $labels = array();
            foreach ($fieldMidComparisonValidatorRules as $field => $label) {
                if (!isset($_SESSION[$cCode]['main'][$field])) {
                    $errMsgs[] = "$field value is required";
                    $errRows[] = $field;
                }
                if ($_SESSION[$cCode]['main'][$field] < 0) {
                    $errMsgs[] = "$field must be >= 0";
                    $errRows[] = $field;
                }
                $labels[$label] = $field;
                $result[$label] = isset($_SESSION[$cCode]['main'][$field]) ? $_SESSION[$cCode]['main'][$field] : 0;
            }
            if ($result["sumber"] > $result["target"]) {
                $labelSrc = isset($labels["sumber"]) ? $labels["sumber"] : "";
                $labelTarget = isset($labels["target"]) ? $labels["target"] : "";
                $errMsgs[] = "Nilai $labelSrc lebih besar dari nilai $labelTarget.";
                $errRows[] = "test";
            }
        }
        //arrPrint($result);
        //arrPrint($labels);
        //mati_disini();
        if (sizeof($errMsgs) > 0) {
            $_SESSION['errMsg'] = implode("<br>", $errMsgs);
            if (sizeof($errLines) > 0) {
                $_SESSION['errLines'] = $errLines;
            }
            if (sizeof($errFields) > 0) {
                $_SESSION['errFields'] = $errFields;
            }
            echo lgShowAlert($_SESSION['errMsg']);
            die();
        }
        //        else {
        //
        //            $actionTarget = "top.BootstrapDialog.show(                                   {
        //                                       title:'preview',
        //                                        message: " . '$' . "('<div></div>').load('" . base_url() . "Transaksi/preview/" . $this->jenisTr . "?rawPrev=$rawPrevURL'),
        //                                        draggable:false,
        //                                        size:top.BootstrapDialog.SIZE_WIDE,
        //                                        type:top.BootstrapDialog.TYPE_SUCCESS,
        //                                        closable:true,
        //                                        }
        //                                        );";
        //
        //            echo "<html>";
        //            echo "<head>";
        //            echo "<script src=\"" . cdn_suport()."AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
        //            echo "</head>";
        //            echo "<body onload=\"$actionTarget\">";
        //            echo "</body>";
        //
        //        }
    }

    public function unionValidate()
    {
        $unionValidatorRules = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartUnionValidators"]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartUnionValidators"] : array();
        $cCode = "_TR_" . $this->jenisTr;
        $rawPrevURL = isset($_GET['rawPrev']) ? $_GET['rawPrev'] : "";
        $errMsgs = array();
        $errLines = array();
        $errFields = array();
        $errRows = array();

        if (sizeof($unionValidatorRules) > 0) {
            $result = array();
            foreach ($unionValidatorRules as $uSpec) {
                foreach ($uSpec as $field => $label) {
                    if (!isset($_SESSION[$cCode]['main'][$field])) {
                        //                        $errMsgs[] = "$field value is required";
                        //                        $errRows[] = $field;
                        $result[$field] = "$label value is required";
                    }
                }
                //                $result[$field] = isset($_SESSION[$cCode]['main'][$field]) ? $_SESSION[$cCode]['main'][$field] : 0;
            }
            if (sizeof($result) > 1) {
                foreach ($result as $field => $label) {
                    $errMsgs[] = $label;
                    $errRows[] = $field;
                }
            }

        }


        if (sizeof($errMsgs) > 0) {
            $_SESSION['errMsg'] = implode("<br>", $errMsgs);
            //            print_r($_SESSION['errMsg']);
            //            echo "<script>";
            //            echo "top.getData('" . base_url() . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id','shopping_cart');";
            //            echo "</script>";
            if (sizeof($errLines) > 0) {
                $_SESSION['errLines'] = $errLines;
            }
            if (sizeof($errFields) > 0) {
                $_SESSION['errFields'] = $errFields;
            }

            echo lgShowAlert($_SESSION['errMsg']);
            die();
        }
        //        else {
        //
        //            $actionTarget = "top.BootstrapDialog.show(                                   {
        //                                       title:'preview',
        //                                        message: " . '$' . "('<div></div>').load('" . base_url() . "Transaksi/preview/" . $this->jenisTr . "?rawPrev=$rawPrevURL'),
        //                                        draggable:false,
        //                                        size:top.BootstrapDialog.SIZE_WIDE,
        //                                        type:top.BootstrapDialog.TYPE_SUCCESS,
        //                                        closable:true,
        //                                        }
        //                                        );";
        //
        //            echo "<html>";
        //            echo "<head>";
        //            echo "<script src=\"" . cdn_suport()."AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
        //            echo "</head>";
        //            echo "<body onload=\"$actionTarget\">";
        //            echo "</body>";
        //
        //        }
    }

    public function index()
    {

        /*
         * list data depresiasi
         * grouping by cabang
         * generate per item jadi transaksi untuk di otorisasi
         * id aset,nama aset sebagai rekening
         */

        $cli = php_sapi_name();
        $get = "";
        if ($cli == 'cli') {
            $get = "exe";
        }

        $this->load->model("Mdls/MdlDtaHutangPihak3");
        $ls = new MdlDtaHutangPihak3();
        $ls->addFilter("status>0");
        $dtaValue = $ls->lookupAll()->result();
        $arrDtaHutangPihak3 = array();
        if (sizeof($dtaValue) > 0) {
            foreach ($dtaValue as $k => $dta) {
                if (!isset($arrDtaHutangPihak3[$dta->id])) {
                    $arrDtaHutangPihak3[$dta->id] = array();
                }
                $arrDtaHutangPihak3[$dta->id] = $dta;
            }
        }

        $this->load->model("Mdls/MdlDtaModal");
        $ls = new MdlDtaModal();
        $ls->addFilter("status>0");
        $dtaValue = $ls->lookupAll()->result();

        $arrDtaHutangPihak = array();
        if (sizeof($dtaValue) > 0) {
            foreach ($dtaValue as $k => $dta) {
                if (!isset($arrDtaHutangPihak[$dta->id])) {
                    $arrDtaHutangPihak[$dta->id] = array();
                }
                $arrDtaHutangPihak[$dta->id] = $dta;
            }
        }

//        cekHijau('byrekmainid = select dengan id rekening');
//        cekHijau('byexternid = select dengan extern/id produk / asset');
//        cekHijau('force = paksa depresiasi tanpa lihat tanggal depre');
//        cekHijau('exe = harus di pakai untuk melakukan execution transaksi');

        cekOrange("======================================= batas ▲▲▲▲▲ LOAN =======================================");

        $this->jenisTr = "4449";//ditembak untuk auto generate
        $this->tableInConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn'] : array();
        $this->tableInConfig_static = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn_static']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn_static'] : array();
        $accountChilds = $this->config->item("accountChilds");

        //region Loan Interest
        $this->load->model("Mdls/MdlSetupLoanInterest");
        $d = new MdlSetupLoanInterest();
        $d->addFilter("extern_jenis='main'");

        $a = new MdlSetupLoanInterest();
        $a->addFilter("extern_jenis='detail'");
        $a->addFilter("depresiasi=1");
//        $a->addFilter("nomer_approve_bunga=''");

        $data = $d->lookupAll()->result();
        $dataSetupDetails = $a->lookupAll()->result();

        $allSetupDetails = array();
        if (sizeof($dataSetupDetails) > 0) {
            foreach ($dataSetupDetails as $k => $dataDetails) {
                $periode = $dataDetails->periode;
                $nomer = $dataDetails->nomer;
                if (!isset($allSetupDetails[$nomer][$periode])) {
                    $allSetupDetails[$nomer][$periode] = array();
                }
                $allSetupDetails[$nomer][$periode] = $dataDetails;
            }
        }

        //region cek pre value locker value
        $this->load->model("Mdls/MdlPaymentSource");
        $l = new MdlPaymentSource();
        $l->addFilter("jenis=446");
        $l->addFilter("sisa>0");
        $paymentValue = $l->lookupAll()->result();

        $paymentSource = array();
        if (sizeof($paymentValue) > 0) {
            $arrSisa = array();
            $arrNomer = array();
            $arrTagihan = array();
            $arrTerbayar = array();
            foreach ($paymentValue as $paymentTmp) {
                if (!isset($arrSisa[$paymentTmp->nomer])) {
                    $arrSisa[$paymentTmp->nomer] = 0;
                }
                $arrSisa[$paymentTmp->nomer] += $paymentTmp->sisa;
                if (!isset($arrNomer[$paymentTmp->nomer])) {
                    $arrNomer[$paymentTmp->nomer] = "";
                }
                $arrNomer[$paymentTmp->nomer] .= formatField("nomer", $paymentTmp->nomer) . "<br>";
                if (!isset($arrTagihan[$paymentTmp->nomer])) {
                    $arrTagihan[$paymentTmp->nomer] = 0;
                }
                $arrTagihan[$paymentTmp->nomer] += $paymentTmp->tagihan;
                if (!isset($arrTerbayar[$paymentTmp->nomer])) {
                    $arrTerbayar[$paymentTmp->nomer] = 0;
                }
                $arrTerbayar[$paymentTmp->nomer] += $paymentTmp->terbayar;
                $paymentSource[$paymentTmp->nomer] = array(
                    "nomer_pinjaman" => $arrNomer[$paymentTmp->nomer],
                    "tagihan" => $arrTagihan[$paymentTmp->nomer],
                    "terbayar" => $arrTerbayar[$paymentTmp->nomer],
                    "sisa" => $arrSisa[$paymentTmp->nomer],
                );
            }
        }
        //endregion

        $this->db->trans_start();
        //tabahin detector tanggal sini broo untuk dijalankan tiap tanggal
        //-----belum disetup ya!!
        //tambahin auto rekekning (baca dari kolom folders);

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
            "jenisTr" => "4449",
            "jenisTrMaster" => "4449",
            "jenisTrTop" => "4449r",
            "jenisTrName" => "request ",
            "stepNumber" => "1",
            "persen_bunga" => "persen_bunga",
            "nilai_bunga" => "nilai_bunga",
            "nilai_pph23" => "nilai_pph23",
            "grand_total" => "grand_total",
            "nilai_sisa" => "nilai_sisa",
            "stepCode" => "4449r",
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
            "jenis" => "4449r",
            "transaksi_jenis" => "4449r",
            "next_step_code" => "4449",
            "next_group_code" => "o_finance",
            "step_number" => "1",
            "step_current" => "1",
            "longitude" => "",
            "lattitude" => "",
            "accuracy" => "",
            "nilai_bayar" => "0",
            "new_sisa" => "0",
            "note" => "0",
            "description" => "keterangan",
            "keterangan" => "keterangan",
            "pihakID" => "-1",
            "pihakName" => "PUSAT",
            "pihakName2" => "PUSAT",
            "pihakDisc" => "",
            "cabang2ID" => "-1",
            "cabang2Name" => "PUSAT",
            "place2ID" => "-1",
            "place2Name" => "PUSAT",
            "pihakMainName" => "",
            "pihakMainID" => "",
            "nomer_top2" => "nomer_top2",
            "nilai_sisa" => "nilai_sisa",
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
            "name" => "nama",
            "nilai_bunga" => "nilai_bunga",
            "nilai_pph23" => "nilai_pph23",
            "persen_bunga" => "persen_bunga",
            "nilai_sisa" => "nilai_sisa",
            "sub_harga" => "sub_harga",
            "sub_subtotal" => "sub_total",
            "olehID" => "olehID",
            "olehName" => "olehName",
            "placeID" => "placeID",
            "placeName" => "cabang_nama",
            "cabangID" => "cabangID",
            "cabangName" => "cabangName",
            "gudangID" => "gudangID",
            "gudangName" => "default center warehouse",
            "gudang2ID" => "-1",
            "gudang2Name" => "default center warehouse",
            "jenisTr" => "4449",
            "next_substep_code" => "4449",
            "next_subgroup_code" => "o_finance",
            "sub_step_number" => "1",
            "sub_step_current" => "1",
            "nilai_bayar" => "",
            "new_sisa" => "0",
            "sub_new_sisa" => "0",
            "note" => "",
            "pihakID" => "-1",
            "pihakName" => "PUSAT",
            "place2ID" => "-1",
            "place2Name" => "PUSAT",
            "cabang2ID" => "-1",
            "cabang2Name" => "PUSAT",
            "cabang2Name" => "PUSAT",
        );
        $items2 = array();
        $items2_sum = array();
        $rsltItems = array();
        $rsltItems2 = array();
        $tableIn_masterTmp = array(
            "trash" => "0",
            "jenis_master" => "4449",
            "jenis_top" => "4449r",
            "jenis" => "4449r",
            "jenis_label" => "request loan interest",
            "div_id" => "18",
            "div_nama" => "default",
            "oleh_id" => "olehID",
            "oleh_nama" => "olehName",
            "cabang_id" => "cabangID",
            "cabang_nama" => "cabangName",
            "transaksi_nilai" => "sub_total",
            "transaksi_jenis" => "4449r",
            "gudang_id" => "gudangID",
            "gudang_nama" => "gudangName",
            "gudang2_id" => "-1",
            "gudang2_nama" => "default center warehouse",
            "keterangan" => "",
            "cabang2_id" => "-1",
            "cabang2_nama" => "PUSAT",
            "pihakMainName" => "",
            "pihakMainID" => "",
        );
        $tableIn_detailTmp = array(
            "produk_id" => "produk_id",
            "produk_kode" => "produk_kode",
            "produk_label" => "produk_labeling",
            "produk_nama" => "produk_nama",
            "produk_ord_jml" => "produk_ord_jml",
            "produk_ord_hrg" => "produk_ord_hrg",
            "hpp" => "hpp",
            "satuan" => "",
            "note" => "",
            "reference" => "",
            "trash" => "0",
            "produk_jenis" => "produk_jenis",
            "valid_qty" => "1",
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
        $main_elements = array(
            "gudang2" => array
            (
                "elementType" => "dataModel",
                "name" => "gudang2",
                "key" => "-1",
                "labelSrc" => "name",
                "label" => "gudang dc",
                "labelValue" => "default center warehouse",
                "mdl_name" => "MdlGudangDefault_center",
                "contents" => "YToxOntzOjQ6Im5hbWEiO3M6MDoiIjt9",
                "contents_intext" => print_r(array("nama" => "")),
            ),
            "cash_account" => array
            (
                "elementType" => "dataModel",
                "name" => "cash_account",
                "label" => "cash account",
                "key" => "0",
                "labelSrc" => "name",
                "labelValue" => "<span class='text-bold text-red blink'>{ BANK PEMBARAYAN BELUM DITENTUKAN }</span>",
                "mdl_name" => "MdlBankAccount_cash_and_in",
                "contents" => "YToyOntzOjQ6Im5hbWEiO3M6ODI6IjxzcGFuIGNsYXNzPSd0ZXh0LWJvbGQgdGV4dC1yZWQgYmxpbmsnPnsgQUNDT1VOVCBQQVlNRU5UIEJFTFVNIERJVEVOVFVLQU4gfTwvc3Bhbj4iO3M6NToic2FsZG8iO3M6MToiMCI7fQ==",
//                    "contents" => "YToyOntzOjQ6Im5hbWEiO3M6MzQ6IntCQU5LIFBlbWJheWFyYW4gYmVsdW0gZGl0ZW50dWthbn0iO3M6NToic2FsZG8iO3M6MToiMCI7fQ==",
                "contents_intext" => print_r(array("nama" => "")),
            ),
        );
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
        $tableIn_detail_values_rsltItems = array();
        $tableIn_detail_values_rsltItems2 = array();
        //endregion

        $jenisTr = $this->jenisTr;
        $cCode = "_TR_" . $this->jenisTr;
        $relOptionConfigs = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['relativeOptions']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['relativeOptions'] : array();
        $title = $this->config->item("heTransaksi_ui")[$jenisTr]["label"];
        $subTitle = $this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['label'];
        $mongoList = array();
        $mongRegID = array();
        if (sizeof($data) > 0) {
            $listedCabang = array();
            $finalDataItems = array();
            foreach ($data as $dataTmp) {
                $cabang_id = $dataTmp->cabang_id;
                $gudang_id = $dataTmp->gudang_id;
                $listedCabang[$cabang_id] = $gudang_id;
            }

            $this->load->model("Mdls/MdlGudangDefault");
            $this->load->model("Mdls/MdlCabang");
            $c = new MdlCabang();
            $g = new MdlGudangDefault();
            $cabangData = array();
            $branchData = array();
            foreach ($listedCabang as $cID => $bID) {
                $c->addFilter("id='$cID'");
                $temCabang = $c->lookupAll()->result();
                $g->addFilter("cabang_id='$cID'");
                $tempBranch = $g->lookupAll()->result();
                foreach ($temCabang as $cabData) {
                    $cabangData[$cabData->id] = $cabData->nama;
                }
                foreach ($tempBranch as $tempBranchData) {
                    $branchData[$cID][$tempBranchData->id] = array(
                        "gudang_nama" => $tempBranchData->name,
                    );
                }
            }

            $sourceItems = array();
            foreach ($data as $dataExtern) {
                $sourceItemsTmp = array();
                foreach ($dataExtern as $coll => $colVal) {
                    $sourceItemsTmp[$coll] = $colVal;
                }
                $sourceItems[$dataExtern->nomer] = $sourceItemsTmp;
            }

            //region build dulu berdasarkan jenis depresiasi

            //endregion build dulu berdasarkan jenis depresiasi
            foreach ($allSetupDetails as $nomer => $detilSourceItems) {

                //foreach untuk periode
                foreach ($detilSourceItems as $periode => $objDataPeriode) {


                    $dataPeriode = (array)$objDataPeriode;
//                    arrPrint($dataPeriode);

                    $traficLight = $dataPeriode['silangan']; //untuk membuat Loan Interest Dari pertama saat request pinjaman di approve
                    $depresiasi = $dataPeriode['depresiasi'];
                    $periode_data = $dataPeriode['periode'];
                    $total_bulan = $dataPeriode['total_bulan']; //postingan bunga ke berapa ?
                    $nomer_request_bunga = $dataPeriode['nomer_request_bunga'];

                    $_GET['hari'] = $dataPeriode['jml_hari_dbln'] > 0 ? $dataPeriode['jml_hari_dbln'] : "";


                    //sinkronisasi periode
                    $tanggal = date("Y-m-d");
                    $periode_lalu = date('Y-m', strtotime('-1 month', strtotime($tanggal)));

                    $periodNow = $periode_lalu;

                    $extern_id = $dataPeriode['extern_id'];
                    $extern_nama = $dataPeriode['extern_nama'];

                    $subtotal = 0;
                    $grandtotal = 0;
                    $totalSisa = 0;

                    $arrItems = array();
                    $total_nilai_bunga = 0;
                    $total_nilai_pph23 = 0;
                    $total_valid_bunga = 0;
                    $npwp = isset($arrDtaHutangPihak[$extern_id]->npwp) ? $arrDtaHutangPihak[$extern_id]->npwp : "";
                    $pph_nilai = isset($arrDtaHutangPihak[$extern_id]->ppn) ? $arrDtaHutangPihak[$extern_id]->ppn : "";
                    $pph_nilai = strlen($npwp) > 10 && $pph_nilai == 15 ? 15 : 15; //dipaksa 15% untuk pemegang saham

                    cekHitam("$traficLight nomer ||| $nomer ");

                    $pid = $dataPeriode['id'];
                    $tr_id = $dataPeriode['transaksi_id'];
                    $dateNow = date('d');
                    $dateNowLeng = date('Y-m');
                    $tglDepreItems = $dataPeriode['repeat'];
                    $noP = $nomer;
                    $namaP = isset($dataPeriode['extern_nama']) ? $dataPeriode['extern_nama'] : "";
                    $idP = isset($dataPeriode['extern_id']) ? $dataPeriode['extern_id'] : "";

                    $valid_bunga_s = $dataPeriode['valid_bunga'];
                    $nilai_bunga_s = $dataPeriode['nilai_bunga'];
                    $nilai_pph23_s = $dataPeriode['nilai_pph23'];

//                    $sisaPaySource = ($paymentSource[$nomer]['sisa']*1!=$dataPeriode['extern_value']*1)?$paymentSource[$nomer]['sisa']*1:$dataPeriode['extern_value']*1;

                    $sisaPaySource = ($paymentSource[$nomer]['sisa'] * 1 > 1) ? $paymentSource[$nomer]['sisa'] * 1 : 0;

                    $valid_bunga = $sisaPaySource > 1 ? ($sisaPaySource / 12) : 0;
                    $nilai_bunga = ($valid_bunga * $dataPeriode['extern_value_2']) / 100;
                    $nilai_pph23 = ($nilai_bunga * $pph_nilai) / 100;

                    if (isset($_GET['hari']) && $_GET['hari'] > 0) {
                        $valid_bunga = $valid_bunga * ($_GET['hari'] / 30);
                        $nilai_bunga = $nilai_bunga * ($_GET['hari'] / 30);
                        $nilai_pph23 = $nilai_pph23 * ($_GET['hari'] / 30);
                    }

                    $total_valid_bunga += $valid_bunga * 1;
                    $total_nilai_bunga += $nilai_bunga * 1;
                    $total_nilai_pph23 += $nilai_pph23 * 1;

                    if (
                        $traficLight == 'hijau' &&
                        $depresiasi == 1 &&
                        strlen($nomer_request_bunga) == 0
                        OR
                        $depresiasi == 1 &&
                        $periodNow == $periode

                    ) {

                        //hanya untuk debug

                        $itemsNilaiSisa = $paymentSource[$nomer]['sisa'] * 1;
                        $itemsPersenBunga = $dataPeriode['extern_value_2'] * 1;

                        $itemsGrandTotal = ($nilai_bunga - $nilai_pph23);
                        $totalSisa += $itemsNilaiSisa;
                        $grandtotal += $itemsGrandTotal;

                        $subtotal += $nilai_bunga;

                        //klo pakai $pid item gak muncul saat approval
                        $arrItems[$extern_id] = array(
                            "harga" => $nilai_bunga,
                            "produk_ord_hrg" => $nilai_bunga,
                            "qty" => "1",
                            "jml" => "1",
                            "produk_labeling" => "request bunga ke-$total_bulan ($periode_data)",
                            "npwp" => $npwp,
                            "pph_nilai" => $pph_nilai,
                            "subtotal" => $nilai_bunga,
                            "subTotal" => $nilai_bunga,
                            "sub_total" => $nilai_bunga,
                            "sub_subtotal" => $nilai_bunga,
                            "sub_harga" => $nilai_bunga,
                            "id_data" => $pid,
                            "id" => $extern_id,
                            "extId" => $nomer,
                            "produk_id" => $extern_id,
                            "produk_kode" => trim($nomer),
                            "produk_ord_jml" => 1,
                            "produk_nama" => isset($dataPeriode['extern_nama']) ? $dataPeriode['extern_nama'] : "",
                            "name" => isset($dataPeriode['extern_nama']) ? $dataPeriode['extern_nama'] : "",
                            "nama" => isset($dataPeriode['extern_nama']) ? $dataPeriode['extern_nama'] : "",
                            "produk_jenis" => "loan",
                            "nilai_sisa" => $itemsNilaiSisa,
                            "nomer_top2" => trim($nomer),
                            "hpp" => $nilai_bunga,
                            "persen_bunga" => $dataPeriode['extern_value_2'] * 1,
                            "nilai_bunga" => $nilai_bunga,
                            "nilai_pph23" => $nilai_pph23,
                            "grand_total" => $itemsGrandTotal,
                            "olehID" => "-100",
                            "olehName" => "sys",
                            "placeID" => -1,
                            "placeName" => "PUSAT",
                            "cabangName" => "PUSAT",
                            "cabangID" => -1,
                            "gudangID" => -1,
                        );

                    }
                    else {
                        cekUngu("============= BELUM SAATNYA BOSSS ===============");
                    }

                    //region builder main
                    // main untuk mode gabungan
                    $main = array(
                        "olehID" => "-100",
                        "pihakID" => $idP,
                        "pihakName" => $namaP,
                        "olehName" => "sys",
                        "placeID" => -1,
                        "placeName" => "PUSAT",
                        "cabangID" => -1,
                        "cabangName" => "PUSAT",
                        "gudangID" => -1,
                        "jenisTr" => "4449",
                        "customers_nama" => $namaP,
                        "jenisTrMaster" => "4449",
                        "jenisTrTop" => "4449r",
                        "jenisTrName" => "REQUEST LOAN INTEREST",
                        "stepNumber" => "1",
                        "stepCode" => "4449r",
                        "dtime" => dtimeNow(),
                        "fulldate" => dtimeNow(),
                        "harga" => $subtotal,
                        "divID" => "18",
                        "divName" => "default",
                        "subtotal" => $subtotal,
                        "reference" => "0",
                        "jenis" => "4449r",
                        "transaksi_jenis" => "4449r",
                        "next_step_code" => "4449",
                        "next_group_code" => "o_finance",
                        "step_number" => "1",
                        "step_current" => "1",
                        "longitude" => "",
                        "lattitude" => "",
                        "accuracy" => "",
                        "nilai_bayar" => "0",
                        "new_sisa" => "0",
                        "note" => "",
                        "description" => "request bunga ke-$total_bulan ($periode_data)",
                        "keterangan" => "request bunga ke-$total_bulan ($periode_data)",
                        "pihakDisc" => "",
                        "nomer_top2" => $noP,
//                        "nilai_sisa_text" => $totalSisaText . "<div class='text-right text-bold'>".number_format($totalSisa)."</div>",
                        "nilai_sisa" => $totalSisa,
                        "persen_bunga" => $dataPeriode['extern_value_2'] * 1,
//                        "persen_bunga_text" => $dataPeriode['extern_value_2']*1,
                        "nilai_bunga" => $total_nilai_bunga,
//                        "nilai_bunga_text" =>$total_nilai_bunga_text . "<div class='text-right text-bold'>".number_format($total_nilai_bunga)."</div>",
                        "nilai_pph23" => $total_nilai_pph23,
//                        "nilai_pph23_text" =>$total_nilai_pph23_text . "<div class='text-right text-bold'>".number_format($total_nilai_pph23)."</div>",
                        "grand_total" => $grandtotal,
//                        "grand_total_text" => $grandtotalText . "<div class='text-right text-bold'>".number_format($grandtotal)."</div>",
                        "npwp" => $npwp,
                        "pph_nilai" => $pph_nilai,
                    );
                    //endregion builder main

                    //region builder items
                    $items = array();
                    foreach ($arrItems as $itsID => $itsData) {
                        foreach ($itemsTmp as $col => $selectedRow) {
                            $items[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
                        }
                    }
                    //endregion builder items

                    //region builder tabel in master
                    $tableIn_master = array(
                        "trash" => "0",
                        "jenis_master" => "4449",
                        "jenis_top" => "4449r",
                        "jenis" => "4449r",
                        "nomer_top2" => $noP,
                        "jenis_label" => "request loan interest",
                        "div_id" => "18",
                        "div_nama" => "default",
                        "dtime" => dtimeNow(),
                        "fulldate" => dtimeNow(),
                        "oleh_id" => "-100",
                        "oleh_nama" => "sys",
                        "cabang_id" => -1,
                        "cabang_nama" => "PUSAT",
                        "transaksi_nilai" => $subtotal,
                        "transaksi_jenis" => "4449r",
                        "gudang_id" => -1,
//                            "gudang_nama"=> $branchData[$cabID][$gudID]['gudang_nama'],
                        "gudang2_id" => "-1",
                        "gudang2_nama" => "default center warehouse",
                        "keterangan" => "",
                        "cabang2_id" => "-1",
                        "cabang2_nama" => "PUSAT",
//                            "pihakMainName" =>$pihakMainID,
//                            "pihakMainID" =>$pihakMainID,
                    );
                    //endregion builder tabel in master

                    //region builder table in detil
                    $tableIn_detail = array();
                    foreach ($arrItems as $itsID => $itsData) {
                        foreach ($tableIn_detailTmp as $col => $selectedRow) {
                            $tableIn_detail[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
                        }
                    }

//                cekUngu('$tableIn_detail');
//                arrPrintWebs($tableIn_detail);
                    //endregion builder table in detil

                    //region table in master values
                    $tableIn_master_values = array(
                        "gudang" => -1,
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

                    if (sizeof($arrItems) > 0) {
                        //region transaksional
                        $buildTablesMaster = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['components'][1]['master']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['components'][1]['master'] : array();
                        $buildTablesDetail = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['components'][1]['detail']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['components'][1]['detail'] : array();
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
                        if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['preProcessor'][1]['master'])) {
                            $iterator = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['preProcessor'][1]['detail']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['preProcessor'][1]['master'] : array();
                            $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'] : array();
                            if (sizeof($iterator) > 0) {
                                foreach ($iterator as $cCtr => $tComSpec) {
                                    $comName = $tComSpec['comName'];
                                    $srcGateName = $tComSpec['srcGateName'];
                                    $srcRawGateName = $tComSpec['srcRawGateName'];
                                    $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                                    $subParams = array();

                                    if (isset($tComSpec['static'])) {
                                        foreach ($tComSpec['static'] as $key => $value) {
                                            $realValue = makeValue($value, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                                            $subParams['static'][$key] = $realValue;
                                        }
                                        if (!isset($subParams['static']["transaksi_id"])) {

                                        }
                                        $subParams['static']["fulldate"] = date("Y-m-d");
                                        $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                        $subParams['static']["keterangan"] = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
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
                                                        //cekHere("$id === $key => $label");
                                                        if (isset($_SESSION[$cCode]['main'][$key])) {
                                                            $_SESSION[$cCode]['main']['sub_' . $key] = ($_SESSION[$cCode]['main']['jml'] * $_SESSION[$cCode]['main'][$key]);
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
                            echo("no processor defined. skipping preprocessor..<br>");
                        }
                        //endregion

                        //region pre-processors (item)
                        if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['preProcessor'][1]['detail'])) {
                            $iterator = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['preProcessor'][1]['detail']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['preProcessor'][1]['detail'] : array();
                            $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'] : array();
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

                                            }
                                            $subParams['static']["fulldate"] = date("Y-m-d");
                                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                            $subParams['static']["keterangan"] = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
                                        }
                                        cekLime(":: cetak preprocc... $comName :: $srcGateName ::");
                                        arrPrint($subParams);
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
                                                cekmerah("gotparams dari pre-proc $comName");
                                                arrprint($gotParams);
                                                if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                                    foreach ($gotParams as $gateName => $paramSpec) {
                                                        cekBiru(":: getParams inject ke $gateName ::");
                                                        if (!isset($_SESSION[$cCode][$gateName])) {
                                                            $_SESSION[$cCode][$gateName] = array();
                                                        }
                                                        else {

                                                        }

                                                        foreach ($paramSpec as $id => $gSpec) {
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
                                                                    if (isset($_SESSION[$cCode][$gateName][$id][$key])) {
                                                                        $_SESSION[$cCode][$gateName][$id]['sub_' . $key] = ($_SESSION[$cCode][$gateName][$id]['jml'] * $_SESSION[$cCode][$gateName][$id][$key]);
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
                            echo("no processor defined. skipping preprocessor..<br>");
                        }
                        //endregion

                        $this->midValidate();
                        $this->unionValidate();
                        //===finalisasi sebelum masuk tabel beneran
                        //===isinya ada pembentukan nomor nota dll
                        //region penomoran receipt
                        $this->load->model("CustomCounter");
                        $cn = new CustomCounter("transaksi");
                        $cn->setType("transaksi");

                        $counterForNumber = array($this->config->item('heTransaksi_core')[$this->jenisTr]['formatNota']);
                        if (!in_array($counterForNumber[0], $this->config->item('heTransaksi_core')[$this->jenisTr]['counters'])) {
                            die("Used number should be registered in 'counters' config as well");
                        }
                        echo "<div style='background:#ff7766;'>";
                        foreach ($counterForNumber as $i => $cRawParams) {
                            $cParams = explode("|", $cRawParams);
                            $cValues = array();
                            foreach ($cParams as $param) {
                                $cValues[$i][$param] = $main[$param];
                            }
                            $cRawValues = implode("|", $cValues[$i]);
                            $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

                        }
                        echo "</div style='background:#ff7766;'>";

                        $stepNumber = 1;
                        $tmpNomorNota = $paramSpec['paramString'];

                        if (isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][2])) {
                            $nextProp = array(
                                "num" => 2,
                                "code" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][2]['target'],
                                "label" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][2]['label'],
                                "groupID" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][2]['userGroup'],
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
                        $configCustomParams = $this->config->item('heTransaksi_core')[$this->jenisTr]['counters'];
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
                            "step_avail" => sizeof($this->config->item('heTransaksi_ui')[$this->jenisTr]['steps']),
                            "step_number" => 1,
                            "step_current" => 1,
                            "next_step_num" => $nextProp['num'],
                            "next_step_code" => $nextProp['code'],
                            "next_step_label" => $nextProp['label'],
                            "next_group_code" => $nextProp['groupID'],
                            "tail_number" => 1,
                            "tail_code" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['target'],
                        );
                        foreach ($addValues as $key => $val) {
                            $tableIn_master[$key] = $val;
                        }
                        //endregion

                        //region addition on detail
                        $addSubValues = array(
                            "sub_step_number" => 1,
                            "sub_step_current" => 1,
                            "sub_step_avail" => sizeof($this->config->item("heTransaksi_ui")[$this->jenisTr]['steps']),
                            "next_substep_num" => $nextProp['num'],
                            "next_substep_code" => $nextProp['code'],
                            "next_substep_label" => $nextProp['label'],
                            "next_subgroup_code" => $nextProp['groupID'],
                            "sub_tail_number" => 1,
                            "sub_tail_code" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['target'],
                        );
                        foreach ($tableIn_detail as $id => $dSpec) {
                            foreach ($addSubValues as $key => $val) {
                                $tableIn_detail[$id][$key] = $val;
                            }
                        }
                        //endregion

                        //region ----------write transaksi, transaksi_data, main_fields, main_values, main_applets, etc
                        if (sizeof($tableIn_master) > 0) {
                            $tableIn_master['status_4'] = 11;
                            $tableIn_master['trash_4'] = 0;

                            $tr = new MdlTransaksi();
                            $tr->addFilter("transaksi.cabang_id='" . $tableIn_master['cabang_id'] . "'");
                            $insertID = $tr->writeMainEntries($tableIn_master);

                            $epID = $tr->writeMainEntries_entryPoint($insertID, $insertID, $tableIn_master);
                            $mongoList['main'] = array($insertID, $epID);
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
                                        $id = isset($iSpec['id']) && $iSpec['id'] > 0 ? $iSpec['id'] : $xid;
                                        if (isset($items[$id])) {
                                            $items[$id][$key] = $val;
                                        }
                                    }
                                }
                            }

                            //===signature
                            $dwsign = $tr->writeSignature($insertID, array(
                                "nomer" => $main['nomer'],
                                "step_number" => 1,
                                "step_code" => $this->jenisTr,
                                "step_name" => $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['label'],
                                "group_code" => $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['userGroup'],
                                "oleh_id" => "-100",
                                "oleh_nama" => "sys",
                                "keterangan" => $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['label'] . " oleh sys",
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

                        }
                        if (sizeof($tableIn_master_values) > 0) {
                            if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['mainValues'])) {
                                foreach ($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['mainValues'] as $key => $src) {
                                    if (isset($tableIn_master_values[$key])) {
                                        $dd =$tr->writeMainValues($insertID, array(
                                            "key" => $key,
                                            "value" => $tableIn_master_values[$key],
                                        ));
                                        $inserMainValues[] = $dd;
                                        $mongoList['mainValues'][] = $dd;
                                    }
                                }
                            }
                        }
                        if (sizeof($main_add_values) > 0) {
                            $inserMainValues = array();
                            foreach ($main_add_values as $key => $val) {
                                $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                                $inserMainValues[] =$dd;
                                $mongoList['mainValues'][] = $dd;
                            }
                            if (sizeof($inserMainValues) > 0) {
                                $arrBlob = blobEncode($inserMainValues);
                                $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                            }
//                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                        }
                        if (sizeof($main_inputs) > 0) {
                            foreach ($main_inputs as $key => $val) {
                                $dd =$tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                                $inserMainValues[] =$dd;
                                $mongoList['mainValues'][] = $dd;

                            }
                            if (sizeof($inserMainValues) > 0) {
                                $arrBlob = blobEncode($inserMainValues);
                                $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                            }
//                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                        }
                        if (sizeof($main_add_fields) > 0) {
                            foreach ($main_add_fields as $key => $val) {
                                $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
                            }
//                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
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
//                                cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                            }
                        }
                        if (sizeof($tableIn_detail) > 0) {
                            $insertIDs = array();
                            foreach ($tableIn_detail as $dSpec) {
                                $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                                $mongoList['detail'] = $insertIDs;
                                if ($epID != 999) {
                                    $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                                    $insertDeIDs[$epID] = $insertIDs;
                                    $mongoList['detail'] = $insertIDs;
                                }
//                                cekUngu("LINE: " . __LINE__ . " <br> " . $this->db->last_query());
                            }
                            if (sizeof($insertIDs) == 0) {
                                die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
                            } else {
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
                        if (sizeof($tableIn_detail2) > 0) {
                            $insertIDs = array();
                            foreach ($tableIn_detail2 as $dSpec) {
                                $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                                $mongoList['detail'] = $insertIDs;
                                if ($epID != 999) {
                                    $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                                    $mongoList['detail'] = $insertIDs;
                                }
//                                cekUngu($this->db->last_query());
                            }
                        }
                        if (sizeof($tableIn_detail2_sum) > 0) {
                            $insertIDs = array();
                            foreach ($tableIn_detail2_sum as $dSpec) {
                                $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                                $insertIDs[]=$insertDetailID;
                                if ($epID != 999) {
                                    $insertDetailID = $tr->writeDetailEntries($epID, $dSpec);
                                    $insertIDs[]=$insertDetailID;
                                    $mongoList['detail'][] = $insertDetailID;
                                }
                            }
//                            cekOrange($this->db->last_query());
                        }
                        if (sizeof($tableIn_detail_rsltItems) > 0) {
                            $insertIDs = array();
                            foreach ($tableIn_detail_rsltItems as $dSpec) {
                                $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                                $insertIDs[]=$insertDetailID;
                                $mongoList['detail'][] = $insertDetailID;
                                if ($epID != 999) {
                                    $insertDetailID = $tr->writeDetailEntries($epID, $dSpec);
                                    $insertIDs[]=$insertDetailID;
                                    $mongoList['detail'][] = $insertDetailID;
                                }
//                                cekUngu($this->db->last_query());
                            }
                        }
                        if (sizeof($tableIn_detail_values) > 0) {
                            foreach ($tableIn_detail_values as $pID => $dSpec) {
                                if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues'])) {
                                    $insertIDs = array();
                                    foreach ($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues'] as $key => $src) {
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
//                                        cekLime($this->db->last_query());
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
                                if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues2_sum'])) {
                                    foreach ($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues2_sum'] as $key => $src) {
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
                        $steps = $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'];
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
                            'rsltItems' => sizeof($rsltItems) > 0 ? $rsltItems : array(),
                            'rsltItems2' => sizeof($rsltItems2) > 0 ? $rsltItems2 : array(),
                            'tableIn_master' => sizeof($tableIn_master) > 0 ? $tableIn_master : array(),
                            'tableIn_detail' => sizeof($tableIn_detail) > 0 ? $tableIn_detail : array(),
                            'tableIn_detail2_sum' => sizeof($tableIn_detail2_sum) > 0 ? $tableIn_detail2_sum : array(),
                            'tableIn_detail_rsltItems' => sizeof($tableIn_detail_rsltItems) > 0 ? $tableIn_detail_rsltItems : array(),
                            'tableIn_detail_rsltItems2' => sizeof($tableIn_detail_rsltItems2) > 0 ? $tableIn_detail_rsltItems2 : array(),
                            'tableIn_master_values' => sizeof($tableIn_master_values) > 0 ? $tableIn_master_values : array(),
                            'tableIn_detail_values' => sizeof($tableIn_detail_values) > 0 ? $tableIn_detail_values : array(),
                            'tableIn_detail_values_rsltItems' => sizeof($tableIn_detail_values_rsltItems) > 0 ? $tableIn_detail_values_rsltItems : array(),
                            'tableIn_detail_values_rsltItems2' => sizeof($tableIn_detail_values_rsltItems2) > 0 ? $tableIn_detail_values_rsltItems2 : array(),
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

                        //===
                        $doWriteReg = (floatval($subtotal) > 1) ? $tr->writeRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries")) : "";
                        $mongRegID = $doWriteReg;
//                        cekHitam($doWriteReg);
//                        arrPrintWebs($baseRegistries);

                        $this->load->model("Mdls/" . "MdlActivityLog");
                        $hTmp = new MdlActivityLog();
                        $tmpHData = array(
                            "title" => $main['jenisTrName'],
                            "sub_title" => "auto new transaction",
                            "uid" => "-100",
                            "uname" => "sys",
                            "dtime" => date("Y-m-d H:i:s"),
                            "transaksi_id" => $insertID,
                            "deskripsi_old" => "",
                            "deskripsi_new" => "",
                            "jenis" => $this->jenisTr,
                            "ipadd" => $_SERVER['REMOTE_ADDR'],
                            "devices" => $_SERVER['HTTP_USER_AGENT'],
                            "category" => "transaksi",
                            "controller" => $this->uri->segment(1),
                            "method" => $this->uri->segment(2),
                            "url" => current_url(),
                        );
                        $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                        foreach ($arrItems as $itsID => $itsData) {

                            $main_update = $d->updateData(
                                array("id" => $itsData['id_data']),
                                array("last_updated" => date("Y-m-d H:i:s"))
                            ) or die("Failed to update tr next-state!");

                            if ($dataPeriode['extern_value'] * 1 != $paymentSource[$nomer]['sisa'] * 1) {
                                cekMerah("sisaPaySource | " . $dataPeriode['extern_value'] * 1 . " - " . $paymentSource[$nomer]['sisa']);
                                cekMerah("valid_bunga_ | " . $valid_bunga_s . " - " . $valid_bunga);
                                cekMerah("nilai_bunga_ | " . $nilai_bunga_s . " - " . $nilai_bunga);
                                cekMerah("nilai_pph23_ | " . $nilai_pph23_s . " - " . $nilai_pph23);
                                cekMerah("bunga saja_ | " . ($nilai_bunga - $nilai_pph23));
                            }
                            else {
                                cekBiru("sisaPaySource | " . $dataPeriode['extern_value'] * 1 . " - " . $paymentSource[$nomer]['sisa']);
                                cekBiru("valid_bunga_ | " . $valid_bunga_s . " - " . $valid_bunga);
                                cekBiru("nilai_bunga_ | " . $nilai_bunga_s . " - " . $nilai_bunga);
                                cekBiru("nilai_pph23_ | " . $nilai_pph23_s . " - " . $nilai_pph23);
                                cekBiru("bunga saja_ | " . ($nilai_bunga - $nilai_pph23));
                            }

                            if ($dataPeriode['extern_value'] * 1 != $paymentSource[$nomer]['sisa'] * 1) {
                                $detil_update = $a->updateData(
                                    array("id" => $itsData['id_data']),
                                    array(
                                        "last_updated" => date("Y-m-d H:i:s"),
                                        "dtime_request_bunga" => date("Y-m-d H:i:s"),
                                        "nomer_request_bunga" => $tmpNomorNota,
                                        "transaksi_id_request_bunga" => $insertID,

                                        "extern_value" => $paymentSource[$nomer]['sisa'],
                                        "valid_bunga" => $valid_bunga,
                                        "nilai_bunga" => $nilai_bunga,
                                        "nilai_pph23" => $nilai_pph23,
                                        "nett_bunga" => ($nilai_bunga - $nilai_pph23),

                                        "depresiasi" => 0,
                                    )
                                ) or die("Failed to update tr next-state!");
                            }
                            else {
                                $detil_update = $a->updateData(
                                    array("id" => $itsData['id_data']),
                                    array(
                                        "last_updated" => date("Y-m-d H:i:s"),
                                        "dtime_request_bunga" => date("Y-m-d H:i:s"),
                                        "nomer_request_bunga" => $tmpNomorNota,
                                        "transaksi_id_request_bunga" => $insertID,
                                        "depresiasi" => 0,
                                    )
                                ) or die("Failed to update tr next-state!");
                            }

                        }

                    }
                    else {
//                            cekOrange('gak bikin transaksi mungkin nilai udh abiss wkwkwk');
                    }
                    //endregion

                    $exed = array();
                    if (sizeof($arrItems) > 0) {
                        foreach ($arrItems as $idPrd => $das) {
                            if (!isset($exed[$idPrd])) {
                                $exed[$idPrd] = array();
                            }
                            $exed[$idPrd] = $idPrd;
                        }
                    }
                }

            }
        }

        cekOrange("======================================= batas ▼▼▼▼▼ LOAN (" . $this->jenisTr . ")=======================================");
        //endregion Loan Interest

        //===== ========== ========== ========== ========== ========== ========== =====//
        //========== ========== ========== ========== ========== ========== ==========//

        cekUngu("======================================= batas ♣♣♣♣♣♣ HUTANG PIHAK KE-TIGA (" . $this->jenisTr . ")=======================================");

        $this->jenisTr = "4449";//ditembak untuk auto generate
        $this->tableInConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn'] : array();
        $this->tableInConfig_static = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn_static']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn_static'] : array();
        $accountChilds = $this->config->item("accountChilds");

        //region hutang pihak 3
        $this->load->model("Mdls/MdlSetupBungaPihak3");
        $d = new MdlSetupBungaPihak3(); //
        $a = new MdlSetupBungaPihak3(); //

        $data = $d->lookupAll()->result();

        //region cek pre value locker value
        $this->load->model("Mdls/MdlPaymentSource");
        $l = new MdlPaymentSource();
        $l->addFilter("jenis=447");
        $l->addFilter("sisa>0");
        $paymentValue = $l->lookupAll()->result();

        $paymentSource = array();
        if (sizeof($paymentValue) > 0) {
            $arrSisa = array();
            $arrNomer = array();
            $arrTagihan = array();
            $arrTerbayar = array();
            foreach ($paymentValue as $paymentTmp) {
                if (!isset($arrSisa[$paymentTmp->extern_id])) {
                    $arrSisa[$paymentTmp->extern_id] = 0;
                }
                $arrSisa[$paymentTmp->extern_id] += $paymentTmp->sisa;
                if (!isset($arrNomer[$paymentTmp->extern_id])) {
                    $arrNomer[$paymentTmp->extern_id] = "";
                }
                $arrNomer[$paymentTmp->extern_id] .= formatField("nomer", $paymentTmp->nomer) . "<br>";
                if (!isset($arrTagihan[$paymentTmp->extern_id])) {
                    $arrTagihan[$paymentTmp->extern_id] = 0;
                }
                $arrTagihan[$paymentTmp->extern_id] += $paymentTmp->tagihan;
                if (!isset($arrTerbayar[$paymentTmp->extern_id])) {
                    $arrTerbayar[$paymentTmp->extern_id] = 0;
                }
                $arrTerbayar[$paymentTmp->extern_id] += $paymentTmp->terbayar;
                $paymentSource[$paymentTmp->extern_id] = array(
                    "nomer_pinjaman" => $arrNomer[$paymentTmp->extern_id],
                    "tagihan" => $arrTagihan[$paymentTmp->extern_id],
                    "terbayar" => $arrTerbayar[$paymentTmp->extern_id],
                    "sisa" => $arrSisa[$paymentTmp->extern_id],
                );
            }
        }

        //endregion

        //tabahin detector tanggal sini broo untuk dijalankan tiap tanggal
        //-----belum disetup ya!!

        //tambahin auto rekekning (baca dari kolom folders);
        $itemsFields = $d->getFields();
        $groupItems = array();
        $kol = array();
        foreach ($itemsFields as $tmpFields) {
            $kol[] = $tmpFields['kolom'];
        }

        foreach ($data as $itemsData) {
            foreach ($kol as $kolom) {
                if ($itemsData->last_updated == "" OR isset($itemsData->last_updated) && date("Y-m-d", strtotime($itemsData->last_updated)) != date("Y-m-d")) {
                    $groupItems[$itemsData->id][$kolom] = $itemsData->$kolom;
                }
            }
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
            "jenisTrName" => "request ",
            "stepNumber" => "1",
            "persen_bunga" => "persen_bunga",
            "nilai_bunga" => "nilai_bunga",
            "nilai_pph23" => "nilai_pph23",
            "grand_total" => "grand_total",
            "nilai_sisa" => "nilai_sisa",
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
            "jenis" => "4412r",
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
            "pihakMainName" => "",
            "pihakMainID" => "",
            "nomer_top2" => "nomer_top2",
            "nilai_sisa" => "nilai_sisa",
        );
        $itemsTmp = array(
            "handler" => "Selectors/_processSelectBiaya",
            "id" => "id",
            "jml" => "1",
            "harga" => "harga",
            "subtotal" => "subtotal",
            "nama" => "nama",
            "npwp" => "npwp",
            "pph_nilai" => "pph_nilai",
            "label" => "",
            "reference" => "",
            "qty" => "1",
            "name" => "nama",
            "nilai_bunga" => "nilai_bunga",
            "nilai_pph23" => "nilai_pph23",
            "persen_bunga" => "persen_bunga",
            "nilai_sisa" => "nilai_sisa",
            "sub_harga" => "sub_harga",
            "sub_subtotal" => "sub_total",
            "olehID" => "olehID",
            "olehName" => "olehName",
            "placeID" => "placeID",
            "placeName" => "cabang_nama",
            "cabangID" => "cabangID",
            "cabangName" => "cabangName",
            "gudangID" => "gudangID",
            "gudangName" => "default center warehouse",
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
            "place2ID" => "-1",
            "place2Name" => "PUSAT",
            "cabang2ID" => "-1",
            "cabang2Name" => "PUSAT",
            "cabang2Name" => "PUSAT",
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
            "jenis_label" => "request loan interest",
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
            "pihakMainName" => "",
            "pihakMainID" => "",
        );
        $tableIn_detailTmp = array(
            "produk_id" => "produk_id",
            "produk_kode" => "",
            "produk_label" => "",
            "produk_nama" => "produk_nama",
            "produk_ord_jml" => "produk_ord_jml",
            "produk_ord_hrg" => "produk_ord_hrg",
            "hpp" => "hpp",
            "satuan" => "",
            "note" => "",
            "reference" => "",
            "trash" => "0",
            "produk_jenis" => "produk_jenis",
            "valid_qty" => "1",
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
        $main_elements = array(
            "gudang2" => array
            (
                "elementType" => "dataModel",
                "name" => "gudang2",
                "key" => "-1",
                "labelSrc" => "name",
                "label" => "gudang dc",
                "labelValue" => "default center warehouse",
                "mdl_name" => "MdlGudangDefault_center",
                "contents" => "YToxOntzOjQ6Im5hbWEiO3M6MDoiIjt9",
                "contents_intext" => print_r(array("nama" => "")),
            ),
            "cash_account" => array
            (
                "elementType" => "dataModel",
                "name" => "cash_account",
                "label" => "cash account",
                "key" => "0",
                "labelSrc" => "name",
                "labelValue" => "<span class='text-bold text-red blink'>{ BANK PEMBARAYAN BELUM DITENTUKAN }</span>",
                "mdl_name" => "MdlBankAccount_cash_and_in",
                "contents" => "YToyOntzOjQ6Im5hbWEiO3M6ODI6IjxzcGFuIGNsYXNzPSd0ZXh0LWJvbGQgdGV4dC1yZWQgYmxpbmsnPnsgQUNDT1VOVCBQQVlNRU5UIEJFTFVNIERJVEVOVFVLQU4gfTwvc3Bhbj4iO3M6NToic2FsZG8iO3M6MToiMCI7fQ==",
//                    "contents" => "YToyOntzOjQ6Im5hbWEiO3M6MzQ6IntCQU5LIFBlbWJheWFyYW4gYmVsdW0gZGl0ZW50dWthbn0iO3M6NToic2FsZG8iO3M6MToiMCI7fQ==",
                "contents_intext" => print_r(array("nama" => "")),
            ),
        );
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
        $tableIn_detail_values_rsltItems = array();
        $tableIn_detail_values_rsltItems2 = array();
        //endregion

        $jenisTr = $this->jenisTr;
        $cCode = "_TR_" . $this->jenisTr;
        $relOptionConfigs = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['relativeOptions']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['relativeOptions'] : array();
        $title = $this->config->item("heTransaksi_ui")[$jenisTr]["label"];
        $subTitle = $this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['label'];

//            $mongoList = array();
//            $mongRegID = array();
        if (sizeof($data) > 0) {
            $listedCabang = array();
            $finalDataItems = array();
            foreach ($data as $dataTmp) {
                $cabang_id = $dataTmp->cabang_id;
                $gudang_id = $dataTmp->gudang_id;
                $listedCabang[$cabang_id] = $gudang_id;
            }

            $this->load->model("Mdls/MdlGudangDefault");
            $this->load->model("Mdls/MdlCabang");
            $c = new MdlCabang();
            $g = new MdlGudangDefault();
            $cabangData = array();
            $branchData = array();
            foreach ($listedCabang as $cID => $bID) {
                $c->addFilter("id='$cID'");
                $temCabang = $c->lookupAll()->result();
                $g->addFilter("cabang_id='$cID'");
                $tempBranch = $g->lookupAll()->result();
                foreach ($temCabang as $cabData) {
                    $cabangData[$cabData->id] = $cabData->nama;
                }
                foreach ($tempBranch as $tempBranchData) {
                    $branchData[$cID][$tempBranchData->id] = array(
                        "gudang_nama" => $tempBranchData->name,
                    );
                }
            }

            //cek value transaksi dari locker value dan locker stok(dalam bulan)
            $sourceItems = array();
            foreach ($data as $dataExtern) {
                $sourceItemsTmp = array();
                foreach ($dataExtern as $coll => $colVal) {
                    $sourceItemsTmp[$coll] = $colVal;
                }
                $sourceItems[$dataExtern->extern_id] = $sourceItemsTmp;
            }

            //region build dulu berdasarkan jenis depresiasi
            $finalData = array();
            foreach ($sourceItems as $externID => $detilExtern) {
                if (isset($paymentSource[$externID])) {
                    if (!isset($finalData[$externID])) {
                        $finalData[$externID] = array();
                    }
                    $finalData[$externID] = $paymentSource[$externID] + $detilExtern;
                }
            }

            if (isset($_GET['byexternid'])) {

                $tmp_finalData = $finalData;
                $finalData = array();
                $arrExtrn = explode(",", $_GET['byexternid']);
                foreach ($tmp_finalData as $extID => $iDetails) {

                    if (in_array($extID, $arrExtrn)) {
                        $finalData[$extID] = $iDetails;
                    }
                }
            }

//            arrPrint($paymentSource);
            //endregion build dulu berdasarkan jenis depresiasi
            foreach ($finalData as $produk_id => $detilSourceItems) {

                $npwp = isset($arrDtaHutangPihak3[$produk_id]->npwp) ? $arrDtaHutangPihak3[$produk_id]->npwp : "";
                $pph_nilai = isset($arrDtaHutangPihak3[$produk_id]->ppn) ? $arrDtaHutangPihak3[$produk_id]->ppn : "";
                $pph_nilai = strlen($npwp) > 10 && $pph_nilai == 15 ? 15 : 30;

                //transaksi main buildder taro sini
                $arrItems = array();
                $subtotal = 0;
                $grandtotal = 0;

                $dateNow = date('d');
                $dateNowLeng = date('Y-m');
                $tglDepreItems = $detilSourceItems['repeat'];
                $noP = $detilSourceItems['nomer_pinjaman'];
                $namaP = isset($detilSourceItems['extern_nama']) ? $detilSourceItems['extern_nama'] : "";
                $idP = isset($detilSourceItems['extern_id']) ? $detilSourceItems['extern_id'] : "";

                $valid_bunga = ($paymentSource[$produk_id]['sisa'] / 12);
                $nilai_bunga = ($valid_bunga * $detilSourceItems['extern_value_2']) / 100;
                $nilai_pph23 = ($nilai_bunga * $pph_nilai) / 100;

                if (isset($paymentSource[$produk_id]) && $paymentSource[$produk_id]['sisa'] > 1 && ($dateNow - $tglDepreItems) == 0 && $dateNowLeng != date('Y-m', strtotime($detilSourceItems['last_updated'])) OR
                    isset($paymentSource[$produk_id]) && $paymentSource[$produk_id]['sisa'] > 1 && isset($_GET['force'])) {

                    if (isset($_GET['force'])) {

                        cekMerah($namaP . ' DIPAKSA UNTUK BUNGA ');
                        cekMerah($noP . ' $noP ');
                        cekMerah($paymentSource[$produk_id]['sisa'] . ' TOTAL PINJAMAN ');
                        cekMerah($detilSourceItems['extern_value_2'] . ' persentase bunga ');
                        cekMerah($valid_bunga . ' $valid_bunga ');
                        cekMerah($nilai_bunga . ' $nilai_bunga ');
                        cekMerah($nilai_pph23 . ' nilai_pph23 ');

                        cekHijau($npwp . ' $npwp ');
                        cekBiru($pph_nilai . ' $pph_nilai ');

                        cekHijau($dateNowLeng . ' $dateNowLeng ');
                        cekHijau(date('Y-m', strtotime($detilSourceItems['last_updated'])) . ' last_updated ');
                        cekHijau(($dateNow - $tglDepreItems) == 0 . ' ($dateNow-$tglDepreItems)==0 ');

                    }
                    else {

                        cekMerah($namaP . ' TANGGAL BUNGA ');

                    }

                    $grandtotal = $nilai_bunga - $nilai_pph23;
                    $subtotal += $nilai_bunga;
                    $arrItems[$produk_id] = array(
                        "harga" => $nilai_bunga,
                        "produk_ord_hrg" => $nilai_bunga,
                        "qty" => "1",
                        "jml" => "1",
                        "npwp" => $npwp,
                        "pph_nilai" => $pph_nilai,
                        "subtotal" => $nilai_bunga,
                        "subTotal" => $nilai_bunga,
                        "sub_total" => $nilai_bunga,
                        "sub_subtotal" => $nilai_bunga,
                        "sub_harga" => $nilai_bunga,
                        "id" => $detilSourceItems['extern_id'],
                        "produk_id" => $detilSourceItems['extern_id'],
                        "produk_ord_jml" => 1,
                        "produk_nama" => isset($detilSourceItems['extern_nama']) ? $detilSourceItems['extern_nama'] : "",
                        "name" => isset($detilSourceItems['extern_nama']) ? $detilSourceItems['extern_nama'] : "",
                        "nama" => isset($detilSourceItems['extern_nama']) ? $detilSourceItems['extern_nama'] : "",
                        "produk_jenis" => "loan",
                        "nilai_sisa" => $paymentSource[$produk_id]['sisa'] * 1,
                        "nomer_top2" => $noP,
                        "hpp" => $nilai_bunga,
                        "persen_bunga" => $detilSourceItems['extern_value_2'] * 1,
                        "nilai_bunga" => $nilai_bunga,
                        "nilai_pph23" => $nilai_pph23,
                        "grand_total" => $grandtotal,
                        "olehID" => "-100",
                        "olehName" => "sys",
                        "placeID" => -1,
                        "placeName" => "PUSAT",
                        "cabangName" => "PUSAT",
                        "cabangID" => -1,
                        "gudangID" => -1,
                    );
                }
                else {
                    cekUngu("============= BELUM SAATNYA BOSSS ===============");
                    cekMerah(isset($paymentSource[$produk_id]) . ' isset($paymentSource[$produk_id]) ');
                    cekMerah($paymentSource[$produk_id]['sisa'] . ' $paymentSource[$produk_id][sisa]>1 ');
                    cekMerah(($dateNow - $tglDepreItems) . ' ($dateNow-$tglDepreItems)==0 ');
                    cekMerah($dateNowLeng . ' $dateNowLeng != date(Y-m, strtotime($detilSourceItems[last_updated])) ');
                    cekMerah(date('Y-m', strtotime($detilSourceItems['last_updated'])) . ' $dateNowLeng != date(Y-m, strtotime($detilSourceItems[last_updated])) ');
                    cekHitam("============= BATAS ===============");
                }

                //region builder main
                $main = array(
                    "olehID" => "-100",
                    "pihakID" => $idP,
                    "pihakName" => $namaP,
                    "olehName" => "sys",
                    "placeID" => -1,
                    "placeName" => "PUSAT",
                    "cabangID" => -1,
                    "cabangName" => "PUSAT",
                    "gudangID" => -1,
                    "jenisTr" => $this->jenisTr,
                    "customers_nama" => $namaP,
                    "jenisTrMaster" => $this->jenisTr,
                    "jenisTrTop" => $this->jenisTr . "r",
                    "jenisTrName" => "request loan interest",
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
                    "nomer_top2" => $noP,
                    "nilai_sisa" => $paymentSource[$produk_id]['sisa'] * 1,
                    "persen_bunga" => $detilSourceItems['extern_value_2'] * 1,
                    "nilai_bunga" => $nilai_bunga,
                    "nilai_pph23" => $nilai_pph23,
                    "grand_total" => $grandtotal,
                    "npwp" => $npwp,
                    "pph_nilai" => $pph_nilai,
                );
                //endregion builder main

                //region builder items
                $items = array();
                foreach ($arrItems as $itsID => $itsData) {
                    foreach ($itemsTmp as $col => $selectedRow) {
                        $items[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
                    }
                }
                //endregion builder items

                //region builder tabel in master
                $tableIn_master = array(
                    "trash" => "0",
                    "jenis_master" => $this->jenisTr,
                    "jenis_top" => $this->jenisTr . "r",
                    "jenis" => $this->jenisTr . "r",
                    "nomer_top2" => $noP,
                    "jenis_label" => "request loan interest",
                    "div_id" => "18",
                    "div_nama" => "default",
                    "dtime" => dtimeNow(),
                    "fulldate" => dtimeNow(),
                    "oleh_id" => "-100",
                    "oleh_nama" => "sys",
                    "cabang_id" => -1,
                    "cabang_nama" => "PUSAT",
                    "transaksi_nilai" => $subtotal,
                    "transaksi_jenis" => $this->jenisTr . "r",
                    "gudang_id" => -1,
//                            "gudang_nama"=> $branchData[$cabID][$gudID]['gudang_nama'],
                    "gudang2_id" => "-1",
                    "gudang2_nama" => "default center warehouse",
                    "keterangan" => "",
                    "cabang2_id" => "-1",
                    "cabang2_nama" => "PUSAT",
//                            "pihakMainName" =>$pihakMainID,
//                            "pihakMainID" =>$pihakMainID,
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
                    "gudang" => -1,
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

                if (sizeof($arrItems) > 0) {
                    //region transaksional
                    $buildTablesMaster = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['components'][1]['master']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['components'][1]['master'] : array();
                    $buildTablesDetail = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['components'][1]['detail']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['components'][1]['detail'] : array();
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
                    if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['preProcessor'][1]['master'])) {
                        $iterator = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['preProcessor'][1]['detail']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['preProcessor'][1]['master'] : array();
                        $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'] : array();
                        if (sizeof($iterator) > 0) {
                            foreach ($iterator as $cCtr => $tComSpec) {
                                $comName = $tComSpec['comName'];
                                $srcGateName = $tComSpec['srcGateName'];
                                $srcRawGateName = $tComSpec['srcRawGateName'];
                                $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                                $subParams = array();

                                if (isset($tComSpec['static'])) {
                                    foreach ($tComSpec['static'] as $key => $value) {
                                        $realValue = makeValue($value, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                                        $subParams['static'][$key] = $realValue;
                                    }
                                    if (!isset($subParams['static']["transaksi_id"])) {

                                    }
                                    $subParams['static']["fulldate"] = date("Y-m-d");
                                    $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                    $subParams['static']["keterangan"] = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
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
                                                    //cekHere("$id === $key => $label");
                                                    if (isset($_SESSION[$cCode]['main'][$key])) {
                                                        $_SESSION[$cCode]['main']['sub_' . $key] = ($_SESSION[$cCode]['main']['jml'] * $_SESSION[$cCode]['main'][$key]);
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
                        echo("no processor defined. skipping preprocessor..<br>");
                    }
                    //endregion

                    //region pre-processors (item)
                    if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['preProcessor'][1]['detail'])) {
                        $iterator = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['preProcessor'][1]['detail']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['preProcessor'][1]['detail'] : array();
                        $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'] : array();
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

                                        }
                                        $subParams['static']["fulldate"] = date("Y-m-d");
                                        $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                        $subParams['static']["keterangan"] = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
                                    }
                                    cekLime(":: cetak preprocc... $comName :: $srcGateName ::");
                                    arrPrint($subParams);
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
                                            cekmerah("gotparams dari pre-proc $comName");
                                            arrprint($gotParams);
                                            if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                                foreach ($gotParams as $gateName => $paramSpec) {
                                                    cekBiru(":: getParams inject ke $gateName ::");
                                                    if (!isset($_SESSION[$cCode][$gateName])) {
                                                        $_SESSION[$cCode][$gateName] = array();
                                                    }
                                                    else {

                                                    }
                                                    foreach ($paramSpec as $id => $gSpec) {
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
                                                                if (isset($_SESSION[$cCode][$gateName][$id][$key])) {
                                                                    $_SESSION[$cCode][$gateName][$id]['sub_' . $key] = ($_SESSION[$cCode][$gateName][$id]['jml'] * $_SESSION[$cCode][$gateName][$id][$key]);
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
                        echo("no processor defined. skipping preprocessor..<br>");
                    }
                    //endregion

                    $this->midValidate();
                    $this->unionValidate();
                    //===finalisasi sebelum masuk tabel beneran
//                            matiHere("matek sini " . __LINE__ );
                    //===isinya ada pembentukan nomor nota dll
                    //region penomoran receipt
                    $this->load->model("CustomCounter");
                    $cn = new CustomCounter("transaksi");
                    $cn->setType("transaksi");

                    $counterForNumber = array($this->config->item('heTransaksi_core')[$this->jenisTr]['formatNota']);
                    if (!in_array($counterForNumber[0], $this->config->item('heTransaksi_core')[$this->jenisTr]['counters'])) {
                        die("Used number should be registered in 'counters' config as well");
                    }
                    echo "<div style='background:#ff7766;'>";
                    foreach ($counterForNumber as $i => $cRawParams) {
                        $cParams = explode("|", $cRawParams);
                        $cValues = array();
                        foreach ($cParams as $param) {
                            $cValues[$i][$param] = $main[$param];
                        }
                        $cRawValues = implode("|", $cValues[$i]);
                        $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

                    }
                    echo "</div style='background:#ff7766;'>";

                    $stepNumber = 1;
                    $tmpNomorNota = $paramSpec['paramString'];

                    if (isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][2])) {
                        $nextProp = array(
                            "num" => 2,
                            "code" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][2]['target'],
                            "label" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][2]['label'],
                            "groupID" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][2]['userGroup'],
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
                    $configCustomParams = $this->config->item('heTransaksi_core')[$this->jenisTr]['counters'];
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
                        "step_avail" => sizeof($this->config->item('heTransaksi_ui')[$this->jenisTr]['steps']),
                        "step_number" => 1,
                        "step_current" => 1,
                        "next_step_num" => $nextProp['num'],
                        "next_step_code" => $nextProp['code'],
                        "next_step_label" => $nextProp['label'],
                        "next_group_code" => $nextProp['groupID'],
                        "tail_number" => 1,
                        "tail_code" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['target'],
                    );
                    foreach ($addValues as $key => $val) {
                        $tableIn_master[$key] = $val;
                    }
                    //endregion

                    //region addition on detail
                    $addSubValues = array(
                        "sub_step_number" => 1,
                        "sub_step_current" => 1,
                        "sub_step_avail" => sizeof($this->config->item("heTransaksi_ui")[$this->jenisTr]['steps']),
                        "next_substep_num" => $nextProp['num'],
                        "next_substep_code" => $nextProp['code'],
                        "next_substep_label" => $nextProp['label'],
                        "next_subgroup_code" => $nextProp['groupID'],
                        "sub_tail_number" => 1,
                        "sub_tail_code" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['target'],
                    );
                    foreach ($tableIn_detail as $id => $dSpec) {
                        foreach ($addSubValues as $key => $val) {
                            $tableIn_detail[$id][$key] = $val;
                        }
                    }
                    //endregion

                    //region ----------write transaksi, transaksi_data, main_fields, main_values, main_applets, etc
                    if (sizeof($tableIn_master) > 0) {
                        $tableIn_master['status_4'] = 11;
                        $tableIn_master['trash_4'] = 0;

                        $tr = new MdlTransaksi();
                        $tr->addFilter("transaksi.cabang_id='" . $tableIn_master['cabang_id'] . "'");
                        $insertID = $tr->writeMainEntries($tableIn_master);
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
                                    $id = isset($iSpec['id']) && $iSpec['id'] > 0 ? $iSpec['id'] : $xid;
                                    if (isset($items[$id])) {
                                        $items[$id][$key] = $val;
                                    }
                                }
                            }
                        }

                        //===signature
                        $dwsign = $tr->writeSignature($insertID, array(
                            "nomer" => $main['nomer'],
                            "step_number" => 1,
                            "step_code" => $this->jenisTr,
                            "step_name" => $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['label'],
                            "group_code" => $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['userGroup'],
                            "oleh_id" => "-100",
                            "oleh_nama" => "sys",
                            "keterangan" => $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['label'] . " oleh sys",
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

                    }
                    if (sizeof($tableIn_master_values) > 0) {
                        if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['mainValues'])) {
                            $inserMainValues =array();
                            foreach ($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['mainValues'] as $key => $src) {
                                if (isset($tableIn_master_values[$key])) {
                                    $dd =$tr->writeMainValues($insertID, array(
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
                        foreach ($main_add_values as $key => $val) {
                            $dd =$tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                            $inserMainValues[] =$dd;
                            $mongoList['mainValues'][] = $dd;

                        }
                        if (sizeof($inserMainValues) > 0) {
                            $arrBlob = blobEncode($inserMainValues);
                            $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                        }
//                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                    }
                    if (sizeof($main_inputs) > 0) {
                        foreach ($main_inputs as $key => $val) {
                            $dd =$tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                            $inserMainValues[] =$dd;
                            $mongoList['mainValues'][] = $dd;
                        }
                        if (sizeof($inserMainValues) > 0) {
                            $arrBlob = blobEncode($inserMainValues);
                            $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                        }
//                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                    }
                    if (sizeof($main_add_fields) > 0) {
                        foreach ($main_add_fields as $key => $val) {
                            $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
                        }
//                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
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
//                                cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                        }
                    }
                    if (sizeof($tableIn_detail) > 0) {
                        $insertIDs = array();
                        foreach ($tableIn_detail as $dSpec) {
                            $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                            $insertIDs[] = $insertDetailID;
                            $insertDeIDs[$insertID][] = $insertDetailID;
                            if ($epID != 999) {
                                $insertEpID = $tr->writeDetailEntries($epID, $dSpec);
                                $insertIDs[] = $insertEpID;
                                $insertDeIDs[$epID][] = $insertEpID;
                                $mongoList['detail'][] = $insertEpID;
                            }
//                                cekUngu("LINE: " . __LINE__ . " <br> " . $this->db->last_query());
                        }
                        if (sizeof($insertIDs) == 0) {
                            die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
                        } else {
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
//                                cekUngu($this->db->last_query());
                        }
                    }
                    if (sizeof($tableIn_detail2_sum) > 0) {
                        $insertIDs = array();
                        foreach ($tableIn_detail2_sum as $dSpec) {
                            $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                            $insertIDs[]=$insertDetailID;
                            $mongoList['detail'][] = $insertDetailID;
                            if ($epID != 999) {
                                $insertDetailID = $tr->writeDetailEntries($epID, $dSpec);
                                $insertIDs[]=$insertDetailID;
                                $mongoList['detail'][] = $insertDetailID;
                            }
                        }
//                            cekOrange($this->db->last_query());
                    }
                    if (sizeof($tableIn_detail_rsltItems) > 0) {
                        $insertIDs = array();
                        foreach ($tableIn_detail_rsltItems as $dSpec) {
                            $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                            $insertIDs[]=$insertDetailID;
                            $mongoList['detail'][] = $insertDetailID;
                            if ($epID != 999) {
                                $insertDetailID = $tr->writeDetailEntries($epID, $dSpec);
                                $insertIDs[]=$insertDetailID;
                                $mongoList['detail'][] = $insertDetailID;
                            }
//                                cekUngu($this->db->last_query());
                        }
                    }
                    if (sizeof($tableIn_detail_values) > 0) {
                        foreach ($tableIn_detail_values as $pID => $dSpec) {
                            if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues'])) {
                                $insertIDs= array();
                                foreach ($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues'] as $key => $src) {
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
//                                        cekLime($this->db->last_query());
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
                            if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues2_sum'])) {
                                $insertIDs = array();
                                foreach ($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues2_sum'] as $key => $src) {
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
                    $steps = $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'];

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
                        'rsltItems' => sizeof($rsltItems) > 0 ? $rsltItems : array(),
                        'rsltItems2' => sizeof($rsltItems2) > 0 ? $rsltItems2 : array(),
                        'tableIn_master' => sizeof($tableIn_master) > 0 ? $tableIn_master : array(),
                        'tableIn_detail' => sizeof($tableIn_detail) > 0 ? $tableIn_detail : array(),
                        'tableIn_detail2_sum' => sizeof($tableIn_detail2_sum) > 0 ? $tableIn_detail2_sum : array(),
                        'tableIn_detail_rsltItems' => sizeof($tableIn_detail_rsltItems) > 0 ? $tableIn_detail_rsltItems : array(),
                        'tableIn_detail_rsltItems2' => sizeof($tableIn_detail_rsltItems2) > 0 ? $tableIn_detail_rsltItems2 : array(),
                        'tableIn_master_values' => sizeof($tableIn_master_values) > 0 ? $tableIn_master_values : array(),
                        'tableIn_detail_values' => sizeof($tableIn_detail_values) > 0 ? $tableIn_detail_values : array(),
                        'tableIn_detail_values_rsltItems' => sizeof($tableIn_detail_values_rsltItems) > 0 ? $tableIn_detail_values_rsltItems : array(),
                        'tableIn_detail_values_rsltItems2' => sizeof($tableIn_detail_values_rsltItems2) > 0 ? $tableIn_detail_values_rsltItems2 : array(),
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

                    //===
                    $doWriteReg = (floatval($subtotal) > 1) ? $tr->writeRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries")) : "";
                    $mongRegID[] = $doWriteReg;
                    // cekHitam($doWriteReg);
//                     arrPrintWebs($baseRegistries);
                    // arrPrintWebs($baseRegistries);
                    // cekHitam($dataTmp->cabang_id);
                    // endregion
                    // validateAllBalances($cabangData[$dataTmp->cabang_id]);
                    // region writelog

                    $this->load->model("Mdls/" . "MdlActivityLog");
                    $hTmp = new MdlActivityLog();
                    $tmpHData = array(
                        "title" => $main['jenisTrName'],
                        "sub_title" => "auto new transaction",
                        "uid" => "-100",
                        "uname" => "sys",
                        "dtime" => date("Y-m-d H:i:s"),
                        "transaksi_id" => $insertID,
                        "deskripsi_old" => "",
                        "deskripsi_new" => "",
                        "jenis" => $this->jenisTr,
                        "ipadd" => $_SERVER['REMOTE_ADDR'],
                        "devices" => $_SERVER['HTTP_USER_AGENT'],
                        "category" => "transaksi",
                        "controller" => $this->uri->segment(1),
                        "method" => $this->uri->segment(2),
                        "url" => current_url(),
                    );
                    $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));

//                            cekHitam($logID);
//                            arrPrintWebs($tmpHData);
//                            $a->updateData("id")
//                            foreach($arrItems as $itsID =>$itsData){

                    $cekId = $a->updateData(
                        array("extern_id" => $produk_id),
                        array(
                            "last_updated" => date("Y-m-d H:i:s"),
                        )
                    ) or die("Failed to update tr next-state!");

//                                cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
//                            }


                }
                else {
//                            cekOrange('gak bikin transaksi mungkin nilai udh abiss wkwkwk');
                }

                //endregion

                $exed = array();
                if (sizeof($arrItems) > 0) {
                    foreach ($arrItems as $idPrd => $das) {
                        if (!isset($exed[$idPrd])) {
                            $exed[$idPrd] = array();
                        }
                        $exed[$idPrd] = $idPrd;
                    }
                }
            }

        }

        cekUngu("======================================= batas bawah HUTANG PIHAK KE-TIGA =======================================");
        //endregion hutang pihak 3

//        if(isset($_GET['exe']) OR $get == "exe"){
//            if(isset($_GET['force'])){
////                $arrExtrnId = isset($_GET['byexternid']) ? trim($_GET['byexternid']) : "id produknya gak dapet";
////                writeLog(__FUNCTION__, "force bunga pinjaman", "bunga pinjaman", $arrExtrnId, $this->session->login['nama']);
//            }
//        matiHere("dawkdawkdawkawkdawdkaw");
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
            //region cloning mongoDB
            if (sizeof($mongoList) > 0) {
                $mong = new MdlMongoMother();
                $tr = new MdlTransaksi();
                foreach ($mongoList as $gateList => $listIDSData) {
                    $tr->setFilters(array());
                    $tr->setSortBy(array());
                    $tr->setTableName($this->mongoTableList[$gateList]);
                    $tr->addFilter("id in (" . implode(",", $listIDSData) . ")");
                    $tmpTrm = $tr->lookUpAll()->result();

                    if (sizeof($tmpTrm) > 0) {
                        $mong->setTableName($this->mongoTableList[$gateList]);
                        foreach ($tmpTrm as $tmpMain) {
                            $transksi_main = json_decode(json_encode($tmpMain), true);
//                            arrPrint($transksi_main);
                            $mong->addData($transksi_main);
                        }
                    }
//                    cekHitam($listedTable[$gateList]);
//                    cekLime($gateList);

                }
            }
            if (sizeof($mongRegID) > 0) {
                $mong = new MdlMongoMother();
                $tr = new MdlTransaksi();
                foreach ($mongRegID as $listIDSData) {
                $tr->setFilters(array());
                $tr->setSortBy(array());
                    $tr->setTableName("transaksi_registry");
                    $tr->addFilter("id in (" . implode(",", $listIDSData) . ")");
                $tmpTrm = $tr->lookUpAll()->result();
//
                    if (sizeof($tmpTrm) > 0) {
                $mong->setTableName("transaksi_registry");
                    foreach ($tmpTrm as $tmpMain) {
                        $transksi_main = json_decode(json_encode($tmpMain), true);
                        $mong->addData($transksi_main);
                    }
                }
//                    cekHitam($listedTable[$gateList]);
//                    cekLime($gateList);

                }
            }
            //endregion

        cekMerah("harusnya done");
        mati_disini("BERHASIL !!! <br> Bunga pinjaman yang di request bisa <span class='text-green text-bold'>diapprove</span> atau <b><r>direject</r></b> pada menu <b>Transaksi => <a href='javascript:void(0)' onclick=window.open('" . base_url() . "Transaksi/index/4449')>Auto Loan Interest</a></b>");
//        }
//        else{
//            mati_disini(__FUNCTION__ . " under maintenance");
//        }
    }
}
}

?>