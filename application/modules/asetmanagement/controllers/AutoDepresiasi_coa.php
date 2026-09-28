<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 0);
require_once "Modul_Controller.php";

//class AutoDepresiasi_coa extends CI_Controller
class AutoDepresiasi_coa extends Modul_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper("he_stepping");
        $this->load->helper("he_access_right");
        $this->load->library("MobileDetect");
        $this->load->helper("he_session_replacer");
        $this->load->model("Mdls/MdlCurrency");
        $this->load->helper('he_angka');
        $this->load->config("heAccounting");

        $this->load->model("CustomCounter");
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlAsetDetail");
        $this->load->model("Mdls/MdlSewaDetail");
        $this->load->model("Mdls/MdlFolderAset");
        $this->load->model("Mdls/MdlMongoMother");
        $this->mongoTableList = array(
            "main" => "transaksi",
            "mainValues" => "transaksi_values",
            "detail" => "transaksi_data",
            "detailValues" => "transaksi_data_values",
            "sign" => "transaksi_sign",
            "extras" => "transaksi_extstep",
            "registry" => "transaksi_registry",
        );
    }

    public function midValidate($sessionData)
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
                if (!isset($sessionData['main'][$field])) {
                    $errMsgs[] = "$label is required";
                    $errRows[] = $field;
                }
            }
        }

        if (sizeof($fieldMidPairedItemValidatorRules) > 0) {
            $result = array();
            foreach ($fieldMidPairedItemValidatorRules as $field => $label) {
                if (!isset($sessionData['main'][$field])) {
                    $errMsgs[] = "$field value is required";
                    $errRows[] = $field;
                }
                $result[$field] = isset($sessionData['main'][$field]) ? $sessionData['main'][$field] : 0;
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

        if (sizeof($fieldMidValidatorRules) > 0) {
            if (isset($sessionData['items']) && sizeof($sessionData['items']) > 0) {
                foreach ($sessionData['items'] as $xid => $iSpec) {
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

            if (isset($sessionData['rsltItems']) && sizeof($sessionData['rsltItems']) > 0) {
                foreach ($sessionData['rsltItems'] as $xid => $iSpec) {
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
                if (!isset($sessionData['main'][$field])) {
                    $errMsgs[] = "$field value is required";
                    $errRows[] = $field;
                }
                if ($sessionData['main'][$field] < 0) {
                    $errMsgs[] = "$field must be >= 0";
                    $errRows[] = $field;
                }
                $labels[$label] = $field;
                $result[$label] = isset($sessionData['main'][$field]) ? $sessionData['main'][$field] : 0;
            }
            if ($result["sumber"] > $result["target"]) {
                $labelSrc = isset($labels["sumber"]) ? $labels["sumber"] : "";
                $labelTarget = isset($labels["target"]) ? $labels["target"] : "";
                $errMsgs[] = "Nilai $labelSrc lebih besar dari nilai $labelTarget.";
                $errRows[] = "test";
            }
        }

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

    }

    public function unionValidate($sessionData)
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
                    if (!isset($sessionData['main'][$field])) {
                        //                        $errMsgs[] = "$field value is required";
                        //                        $errRows[] = $field;
                        $result[$field] = "$label value is required";
                    }
                }
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

    public function index_OLD()
    {

        /*
         * list data depresiasi
         * grouping by cabang
         * generate per item jadi transaksi untuk di otorisasi
         * id aset,nama aset sebagai rekening
         * untuk solo pembantu efisiensi ambil dari pair produkprebiaya berdasarkan coa untuk dapat id dan labelnya
         * dibwa ke main dan items untuk pembentu level 2 extern2_id
         */

        echo "<title>AUTO-DEPRE</title>";

        cekHijau('byrekmainid = select dengan id rekening');
        cekHijau('byexternid = select dengan extern/id produk / asset');
        cekHijau('force = paksa depresiasi tanpa lihat tanggal depre');
        cekHijau('exe = harus di pakai untuk melakukan execution transaksi');
        $modul_transaksi = $this->modul;
        $this->jenisTr = "8787";//ditembak untuk auto generate
        $this->tableInConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn'] : array();
        $this->tableInConfig_static = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn_static']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn_static'] : array();

        //list rekening dari config HeAccounting
        $accountChilds = $this->config->item("accountChilds");

        //region batas aset
        $this->load->model("Mdls/MdlAccounts");
        $this->load->model("Mdls/MdlSetupDepresiasiAssetsProduction");
        $this->load->model("Mdls/MdlProdukRakitanPreBiaya");//untuk baca biaya produksi
        $this->load->model("Mdls/MdlDtaBiayaProduksi");//untuk baca biaya produksi


        $d = new MdlSetupDepresiasiAssetsProduction(); //
        $f = new MdlFolderAset(); //aset wujud dan sewa sama2 pakai ini
        $a = new MdlAsetDetail(); //aset wujud dan sewa sudah termasuk
        $pr = new MdlProdukRakitanPreBiaya();

        $masterPrebiaya = $pr->lookUpAll()->result();
        $dtaProduksi = array();
        $dataPrebiaya = array();
        if (sizeof($masterPrebiaya) > 0) {
            $dta_p = new MdlDtaBiayaProduksi();
            $dtaBiayaproduksi = $dta_p->lookUpAll()->result();

            foreach ($dtaBiayaproduksi as $tempBiayaProduksi) {
                $dtaProduksi[$tempBiayaProduksi->id] = $tempBiayaProduksi->nama;
            }
            foreach ($masterPrebiaya as $preBiayaTmp) {
                // arrPrint($preBiayaTmp);
                $dataPrebiaya[$preBiayaTmp->coa_code] = array(
                    "id" => $preBiayaTmp->id,
                    "nama" => $preBiayaTmp->nama,
                    "coa_code_2" => $preBiayaTmp->coa_code_2,//efisiensi
                );
            }
        }
//         arrPrint($dataPrebiaya);
// matiHEre();
        if (isset($_GET["byexternid"])) {
            $d->addFilter("extern_id='" . $_GET["byexternid"] . "'");
        }
        $a->addFilter("jenis=item");

        $folders = $f->lookupAll()->result();
        $detailItems = $a->lookupAll()->result();
        $arrCoaList = $this->MdlAccounts->get_userlist();

//         arrPrint($arrCoaList);

        $arrCoa = array();
        $arrCoaExtern = array();
        if (!empty($arrCoaList)) {
            foreach ($arrCoaList as $kk => $cc) {
                $arrCoa[$cc->head_code] = $cc->head_name;
                $arrCoaExtern[$cc->head_code] = array(
                    "extern_id" => $cc->extern_id,
                    "extern_nama" => $cc->head_name,
                );
            }
        }
//         arrPrint($arrCoa);
//         matiHere(__LINE__);
        $arrCoa_P_HeadCode = array();
        if (!empty($arrCoaList)) {
            foreach ($arrCoaList as $kk => $cc) {
                $arrCoa_P_HeadCode[$cc->head_code] = $cc->p_head_code;
            }
        }

//        arrPrint($arrCoa);
// matiHere();
        $data = $d->lookupAll()->result();
//        arrPrint($data);
//         cekMerah($this->db->last_query());
// matiHEre();
        //region cek pre value locker value
        $this->load->model("Mdls/MdlLockerValue");
        $l = new MdlLockerValue();
        $l->addFilter("jenis=aktiva");
        $l->addFilter("state=active");
        $lockerValue = $l->lookupAll()->result();

        $lockerSource = array();
        if (sizeof($lockerValue) > 0) {
            foreach ($lockerValue as $lockerTmp) {
                $lockerSource[$lockerTmp->cabang_id][$lockerTmp->produk_id] = array(
                    "nilai" => $lockerTmp->nilai,
                    "gudang_id" => $lockerTmp->gudang_id,
                );
            }
        }
        //endregion

        $this->db->trans_start();
        //tabahin detector tanggal sini broo untuk dijalankan tiap tanggal
        //-----belum disetup ya!!

        //tambahin auto rekekning (baca dari kolom folders);
        $pihakMain = array();
        $pihakMain_coa = array();
        foreach ($folders as $foldersTmp) {
            $pihakMain[$foldersTmp->id] = $foldersTmp->nama;
            $pihakMain_coa[$foldersTmp->coa_code] = $foldersTmp->coa_code;
        }

        //tambahin auto rekekning (baca dari kolom folders);
        $this->load->model("Mdls/MdlProdukRakitanPreBiaya");
        $bmp = new MdlProdukRakitanPreBiaya();
        $biayaMethodProduksi = $bmp->lookupAll()->result();
        // matiHEre($this->db->last_query());
        // matiHere();
        $autoRekNameProduksi_2 = array();
        $autoRekNameProduksi_lv2 = array();
        foreach ($biayaMethodProduksi as $biayaMethodProduksiTmp) {
//            $autoRekNameProduksi_2[$biayaMethodProduksiTmp->id] = $biayaMethodProduksiTmp->nama;
//            $autoRekNameProduksi_lv2[$biayaMethodProduksiTmp->nama] = "MdlRekeningPembantuBiayaKomposisiProduksi";
            $autoRekNameProduksi_2[$biayaMethodProduksiTmp->coa_code] = $biayaMethodProduksiTmp->nama;
            $autoRekNameProduksi_lv2[$biayaMethodProduksiTmp->coa_code] = "MdlRekeningPembantuBiayaKomposisiProduksi";
        }

        //tambahin auto rekekning (baca dari kolom folders);
        $this->load->model("Mdls/MdlRekeningBesar");
        $rb = new MdlRekeningBesar();
        $rekeningBesar = $rb->lookupAll()->result();
        $autoRekNameSales_2 = array();
        $autoRekNameSales_lv2 = array();
        foreach ($rekeningBesar as $rekeningBesarTmp) {
//            $autoRekNameSales_2[$rekeningBesarTmp->id] = $rekeningBesarTmp->nama;
//            $autoRekNameSales_lv2[$rekeningBesarTmp->nama] = $rekeningBesarTmp->mdl_name;
            $autoRekNameSales_2[$rekeningBesarTmp->coa_code] = $rekeningBesarTmp->coa_code;
            $autoRekNameSales_lv2[$rekeningBesarTmp->coa_code] = $rekeningBesarTmp->mdl_name;
        }

        //tambahin auto rekekning (baca dari kolom folders);
        $autoRekNameSales_3 = array();
        if (sizeof($autoRekNameSales_lv2) > 0) {
            foreach ($autoRekNameSales_lv2 as $name => $mdlName) {
                $this->load->model("Mdls/$mdlName");
                $prpb = new $mdlName();
                $produkRakitanPreBiaya = $prpb->lookupAll()->result();
                foreach ($produkRakitanPreBiaya as $produkRakitanPreBiayaTmp) {
                    $autoRekNameSales_3[$name][$produkRakitanPreBiayaTmp->id] = $produkRakitanPreBiayaTmp->nama;
                }
            }
        }

        //Coa Account Alias
        $arrCoaAccount = fetchAccountStructureAlias();

//arrPrint($arrCoaAccount);
//matiHere(__LINE__);

//        $staticAccountComRekening = array(
//            "kendaraan"             => "RekeningPembantuAkumPenyusutanKendaraan",
//            "peralatan kantor"      => "RekeningPembantuAkumPenyusutanPeralatanKantor",
//            "mesin produksi"        => "RekeningPembantuAkumPenyusutanMesinProduksi",
//            "mesin"                 => "RekeningPembantuAkumPenyusutanMesin",
//            "peralatan produksi"    => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
//            "tanah dan bangunan"    => "RekeningPembantuAkumPenyusutanBangunan",
//        );

        $staticAccountComRekening = array(
            // //rombongan aktiva coa lama
            // "01050100005"  => "RekeningPembantuAkumPenyusutanKendaraan",
            // "01050100004"  => "RekeningPembantuAkumPenyusutanPeralatanKantor",
            // "01050100002"  => "RekeningPembantuAkumPenyusutanMesinProduksi",
            // "01050100006"  => "RekeningPembantuAkumPenyusutanMesin",
            // "01050100003"  => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
            // "01050100001"  => "RekeningPembantuAkumPenyusutanBangunan",
            //
            // //rombongan biaya
            // "061100001"  => "RekeningPembantuAkumPenyusutanKendaraan",
            // "061100002"  => "RekeningPembantuAkumPenyusutanPeralatanKantor",
            // "061100005"  => "RekeningPembantuAkumPenyusutanMesinProduksi",
            // "061100004"  => "RekeningPembantuAkumPenyusutanMesin",
            // "061100003"  => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
            // "061100006"  => "RekeningPembantuAkumPenyusutanBangunan",

            //rombongan aktiva
            "1020010010" => "RekeningPembantuAkumPenyusutanKendaraan",
            "1020020010" => "RekeningPembantuAkumPenyusutanPeralatanKantor",
            "1020040010" => "RekeningPembantuAkumPenyusutanMesinProduksi",
            "1020030010" => "RekeningPembantuAkumPenyusutanMesin",
            "1020041010" => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
            "1020050010" => "RekeningPembantuAkumPenyusutanBangunan",

            //rombongan biaya
            "6040010" => "RekeningPembantuAkumPenyusutanKendaraan",
            "6040020" => "RekeningPembantuAkumPenyusutanPeralatanKantor",
            "6040050" => "RekeningPembantuAkumPenyusutanMesinProduksi",
            "6040040" => "RekeningPembantuAkumPenyusutanMesin",
            "6040030" => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
            "6040060" => "RekeningPembantuAkumPenyusutanBangunan",
        );

        //pilih menggunakan asset account
        $staticAssetsAccount = array(
            "1020010010" => "1020010020", //Akum Penyu Kendaraan
            "1020020010" => "1020020020", //Akum Penyu Peralatan Kantor
            "1020040010" => "1020040020", //Akum Penyu Mesin Produksi
            "1020030010" => "1020030020", //Akum Penyu Mesin
            "1020041010" => "1020041020", //Akum Penyu Peralatan Produksi
            "1020050010" => "1020050020", //Akum Penyu Tanah Dan Bangunan
        );

        //ini hanya untuk solo, pilih dengan akun utama (dalam hal ini static) JIKA ADA :P
        $staticAssetsAccountProduksi = array(
            "1020010010" => "1020010020", //Akum Penyu Kendaraan
            "1020020010" => "1020020020", //Akum Penyu Peralatan Kantor
            "1020040010" => "1020040020", //Akum Penyu Mesin Produksi
            "1020030010" => "1020030020", //Akum Penyu Mesin
            "1020041010" => "1020041020", //Akum Penyu Peralatan Produksi
            "1020050010" => "1020050020", //Akum Penyu Tanah Dan Bangunan
        );

        $staticAccountPenyusutan = array(
            "1020010010" => "6040010",//beban penyusutan kendaraan
            "1020020010" => "6040020",//penyusutan peraltan kantor
            "1020041010" => "6040030",//penyusutan peralatan produksi
            "1020030010" => "6040040",//penyususutan mesin
            "1020040010" => "6040050",//penyusutan meisn prouksi
            "1020050010" => "6040060",//penyusutan bangunan
        );

        $staticAccountHppProduksi = array(
            //efisiensi vs hpp produk produksi
            //delivery cost =>delivery cost
            "5020020" => "3020010010",
            //direct labour =>direct labour
            "5020030" => "3020010020",
            //quality =>quality
            "5020040" => "3020010030",
        );
        $itemsFields = $a->getFields();
        $groupItems = array();
        $kol = array();
        foreach ($itemsFields as $tmpFields) {
            $kol[] = $tmpFields['kolom'];
        }
        foreach ($detailItems as $itemsData) {
            foreach ($kol as $kolom) {
//                if ($itemsData->dtime_last_depresiasi == "" OR
//                    isset($itemsData->dtime_last_depresiasi) && date("Y-m", strtotime($itemsData->dtime_last_depresiasi)) != date("Y-m") OR
//                    isset($_GET['force'])) {
                $groupItems[$itemsData->folders][$itemsData->id][$kolom] = $itemsData->$kolom;
//                }
            }
        }
        // arrPrint($groupItems);


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
            "jenisTrName" => "request depresiasi",
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
            "pihakMainName" => "",
            "pihakMainID" => "",


            "comRekName_2_coa" => "comRekName_2_child_coa",
            "rekName_2_coa" => "rekName_2_child_coa",
            "comRekName_3_coa" => "comRekName_3_child_coa",
            "rekName_3_coa" => "rekName_3_child_coa",

            "comRekName_2" => "comRekName_2_child",
            "rekName_2" => "rekName_2_child",
            "comRekName_3" => "comRekName_3_child",
            "rekName_3" => "rekName_3_child",
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
            "place2ID" => "-1",
            "place2Name" => "PUSAT",
            "cabang2ID" => "-1",
            "cabang2Name" => "PUSAT",

            "pihakMainChild_coa" => "pihakMainChild_coa",
            "rekAkum_child_coa" => "rekAkum_child_coa",

            "comRekName_1_child_coa" => "comRekName_1_child_coa",
            "comRekName_2_child_coa" => "comRekName_2_child_coa",
            "comRekName_3_child_coa" => "comRekName_3_child_coa",

            "rekName_1_child_coa" => "rekName_1_child_coa",
            "rekName_2_child_coa" => "rekName_2_child_coa",
            "rekName_3_child_coa" => "rekName_3_child_coa",

            "rekName1IDChild_coa" => "rekName_1_child_coa",
            "rekName2IDChild_coa" => "rekName2IDChild_coa",
            "rekName3IDChild_coa" => "rekName3IDChild_coa",

            "pihakMainChild" => "pihakMainChild",
            "rekAkum_child" => "rekAkum_child",

            "comRekName_2_coa" => "comRekName_2_coa",
            "rekName_2_coa" => "rekName_2_coa",

            "comRekName_1_child" => "comRekName_1_child",
            "comRekName_2_child" => "comRekName_2_child",
            "comRekName_3_child" => "comRekName_3_child",

            "rekName_1_child" => "rekName_1_child",
            "rekName_2_child" => "rekName_2_child",
            "rekName_3_child" => "rekName_3_child",

            "rekName1IDChild" => "rekName_1_child",
            "rekName2IDChild" => "rekName2IDChild",
            "rekName3IDChild" => "rekName3IDChild",
            "rekEfisiensi_coa" => "rekEfisiensi_coa",
            //pembantu efisiensi
            "efisensi_child_id" => "efisensi_child_id",
            "efisensi_child_nama" => "efisensi_child_nama",
            "efisensi_child2_id" => "efisensi_child2_id",
            "efisensi_child2_nama" => "efisensi_child2_nama",

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
            "jenis_label" => "request depresiasi",
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
            "gudang2" => Array
            (
                "elementType" => "dataModel",
                "name" => "gudang2",
                "key" => "-1",
                "labelSrc" => "name",
                "label" => "gudang dc",
                "labelValue" => "default center warehouse",
                "mdl_name" => "MdlGudangDefault_center",
                "contents" => "YToxOntzOjQ6Im5hbWEiO3M6MDoiIjt9",
                "contents_intext" => print_r(array("nama" => ""), 1),
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
        $relOptionConfigs = isset($this->configUi[$this->jenisTr]['relativeOptions']) ? $this->configUi[$this->jenisTr]['relativeOptions'] : array();
        $title = $this->configUi[$jenisTr]["label"];
        $subTitle = $this->configUi[$jenisTr]["steps"][1]['label'];

        // arrPrint($data);

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
            $this->load->model("Mdls/MdlMongoMother");
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
                $rekMain = $dataExtern->rekening_main;
                $sourceItemsTmp = array();
                if ($dataExtern->dtime_last_depresiasi == "" OR
                    isset($dataExtern->dtime_last_depresiasi) && date("Y-m", strtotime($dataExtern->dtime_last_depresiasi)) != date("Y-m") OR
                    isset($_GET['force'])) {
                    foreach ($dataExtern as $coll => $colVal) {
                        $sourceItemsTmp[$coll] = $colVal;
                    }
                    if ($lockerSource[$dataExtern->cabang_id][$dataExtern->extern_id]['nilai'] > 1) {
                        $sourceItems[$dataExtern->cabang_id][$rekMain][$dataExtern->extern_id] = $sourceItemsTmp;
                    }
                    else {
                        // jika mau tetap di tampilkan pada antarmuka CLI,
                        // maka produk/jasa akan tampil di CLI, namun tidak akan di proses penyusutan nya...
                        // $sourceItems[$dataExtern->cabang_id][$rekMain][$dataExtern->extern_id] = $sourceItemsTmp;
                    }
                }
            }

            //region build dulu berdasarkan jenis depresiasi
            $finalData = array();
            foreach ($sourceItems as $cabID => $dataSource2) {
                foreach ($dataSource2 as $_rekMainID => $dataSource) {
//                cekMerah("cabID: $cabID | dataSource");
//                arrPrint($dataSource);
                    foreach ($groupItems as $gID => $detailGID) {
//                    cekMerah("gID: $gID | detailGID");
//                    arrPrint($detailGID);
                        foreach ($detailGID as $externID => $detilExtern) {
//                        cekMerah("externID: $externID | detilExtern");
//                        arrPrint($detilExtern);
                            if (isset($dataSource[$externID])) {
                                $finalData[$cabID][$_rekMainID][$gID][$externID] = $dataSource[$externID] + $detilExtern;
                            }
                        }
                    }
                }
            }

            $tableView = "";
            $tableView .= "<table cellspacing='1' cellpadding='1' border='1' bgcolor='#fff'>";
            $tableView .= "<caption>ITEMS SEWA</caption>";
            $tableView .= "<thead>";
            $tableView .= "<tr>";
            $tableView .= "<th>No.</th>";
            $tableView .= "<th>Extern No</th>";
            $tableView .= "<th>Cabang/Pusat</th>";
            $tableView .= "<th>Nama Asset/Sewa</th>";
            $tableView .= "<th>Jenis TR</th>";
            $tableView .= "<th>Rek ACC</th>";
            $tableView .= "<th>Rek MAIN</th>";
            $tableView .= "<th>Rek Details</th>";
            $tableView .= "<th>TGL Depre</th>";
            $tableView .= "<th>Last Depre</th>";
            $tableView .= "<th>Nilai Sisa (locker)</th>";
            $tableView .= "<th>Keterangan</th>";
            $tableView .= "<th>ACTION</th>";
            $tableView .= "</tr>";
            $tableView .= "</thead>";
            $tableView .= "<tbody>";


            //endregion build dulu berdasarkan jenis depresiasi
            foreach ($finalData as $cabID => $detilSourceTmp) {
                foreach ($detilSourceTmp as $rekMainID => $detilSource) {
                    if (isset($_GET['byrekmainid'])) {
                        $tmp_detilSource = $detilSource;
                        $detilSource = array();
                        $arrRek = explode(",", $_GET['byrekmainid']);
                        foreach ($tmp_detilSource as $rekID => $iDetails) {
                            if (in_array($rekID, $arrRek)) {
                                $detilSource[$rekID] = $iDetails;
                            }
                        }
                    }
//                    arrprint($detilSource);
//                    matiHEre();
                    foreach ($detilSource as $pihakMainID => $detilSourceItems) {
                        //transaksi main buildder taro sini
                        $arrItems = array();
                        $subtotal = 0;

                        $rekName_1_child = "";
                        $comRekName_2_child = "";
                        $rekName_2_child = "";
                        $comRekName_3_child = "";
                        $rekName_3_child = "";
                        $rekName3IDChild = "";

                        if (isset($_GET['byexternid'])) {
                            $tmp_detilSourceItems = $detilSourceItems;
                            $detilSourceItems = array();
                            $arrExtrn = explode(",", $_GET['byexternid']);
                            foreach ($tmp_detilSourceItems as $extID => $iDetails) {
                                if (in_array($extID, $arrExtrn)) {
                                    $detilSourceItems[$extID] = $iDetails;
                                }
                            }
                        }

                        $numAsset = 1;

                        foreach ($detilSourceItems as $produk_id => $prodsDetil) {

                            // cekKuning(":: masuk disini ::");
                            // arrPrintKuning($prodsDetil);
//                            arrPrint($arrCoa[$prodsDetil['rekening_details']]);
                            // matiHEre();

                            $this->jenisTr = "8787"; // selain pusat

                            if ($cabID == -1) {
                                $this->jenisTr = "8786"; //khusus pusat
                            }

                            $dateNow = date('d');
                            $tglDepreItems = $prodsDetil['repeat'];
                            $namaP = isset($prodsDetil['extern_nama']) ? $prodsDetil['extern_nama'] : "";
                            $tglTerakhirDepresiasi = $prodsDetil['dtime_last_depresiasi'];
                            $gudang_nama = isset($cabangData[$cabID]) ? $cabangData[$cabID] : "undefined";

//                            if($lockerSource[$cabID][$produk_id]['nilai']*1==1){
//                                cekHijau("(" . $prodsDetil['extern_id'] . ") $namaP | kurang " . ($tglDepreItems - $dateNow) . "NILAI DEPRESIASI SUDAH HABIS");
//                                cekHijau('stockLocker: ' . $lockerSource[$cabID][$produk_id]['nilai'] . " || " . "used_value: (" . $prodsDetil['value_used'] . ")");
//                            }
//                            else{
//                                cekHitam(
//                                    ($dateNow - $tglDepreItems) == 0 ?
//                                        " cabang $cabID ($gudang_nama) (" . $prodsDetil['extern_id'] . ") $namaP (" . $prodsDetil['value_used'] . ") | saatnya depresiasi tgl " . date('Y-m-d H:i:s') :
//                                        (($dateNow - $tglDepreItems) < 0 ?
//                                            "(" . $prodsDetil['extern_id'] . ") $namaP | kurang " . ($tglDepreItems - $dateNow) . " hari hingga depresiasi" :
//                                            "(" . $prodsDetil['extern_id'] . ") $namaP | sudah lewat " . ($dateNow - $tglDepreItems) . " hari sejak depresiasi")
//                                );
//                                cekHere('stockLocker: ' . $lockerSource[$cabID][$produk_id]['nilai'] . " || " . "used_value: (" . $prodsDetil['value_used'] . ")");
//                            }

                            $txtNotif = ($dateNow - $tglDepreItems) == 0 ? "saatnya amortisasi<br>tgl " . date('Y-m-d H:i:s') :
                                (($dateNow - $tglDepreItems) < 0 ? "kurang " . ($tglDepreItems - $dateNow) . " hari hingga amortisasi" :
                                    "lewat " . ($dateNow - $tglDepreItems) . " hari");

                            $gudSpec = getDefaultWarehouseID($cabID);
                            $gudID = isset($lockerSource[$cabID][$produk_id]['gudang_id']) ? $lockerSource[$cabID][$produk_id]['gudang_id'] : $gudSpec['gudang_id'];

                            $rekName_1_child = "" . isset($pihakMain[$prodsDetil['asset_account']]) ? "" . $pihakMain[$prodsDetil['asset_account']] : "";
                            $rekName_1_child_coa = "" . $prodsDetil['asset_account'];
                            $comRekName_1_child = isset($staticAccountComRekening[$rekName_1_child_coa]) ? "" . $staticAccountComRekening[$rekName_1_child_coa] : "";
                            $comRekName_2_child = $cabID == 25 ? "" . $accountChilds[$prodsDetil['rekening_main']] : "" . $accountChilds[$prodsDetil['rekening_main']];
                            $rekName_2_child = $cabID == 25 ? "" . $prodsDetil['rekening_main'] : "" . $prodsDetil['rekening_main'];
                            $comRekName_3_child = $cabID == 25 ? 0 : "RekeningPembantuDepresiasi";

                            cekMerah($prodsDetil['rekening_main']);
                            cekMerah($prodsDetil['rekening_details']);
                            arrPrintWebs($prodsDetil);
                            cekHitam("LINE: " . __LINE__ . " rekening_main | rekening_details");

                            $rekName_3_child = $cabID == 25 ? $arrCoa[$prodsDetil['rekening_main']] : "" . $arrCoa[$prodsDetil['rekening_details']];
                            $rekName3IDChild = $cabID == 25 ? $staticAccountHppProduksi[$prodsDetil['rekening_main']] : "" . $prodsDetil['rekening_details'];


                            //untuk urusan efisiensi
                            $efisensi_child_id = $cabID == 25 ? $dataPrebiaya[$prodsDetil['rekening_main']]["id"] : "";
                            $efisensi_child_nama = $cabID == 25 ? $dataPrebiaya[$prodsDetil['rekening_main']]["nama"] : "";
                            $efisensi_child2_id = $cabID == 25 ? $prodsDetil['rekening_details'] : "";
                            $efisensi_child2_nama = $cabID == 25 ? $dtaProduksi[$prodsDetil['rekening_details']] : "";

                            $pihakMainChild = $cabID == 25 ? "" . $staticAccountPenyusutan[$prodsDetil['asset_account']] : "" . $staticAccountPenyusutan[$prodsDetil['asset_account']];

                            $rekAkumPenyu_coa = $cabID == 25 ? "" . $staticAssetsAccountProduksi[$prodsDetil['asset_account']] : "" . $staticAssetsAccount[$prodsDetil['asset_account']];
                            $rekAkum_child = $cabID == 25 ? "" . $staticAssetsAccountProduksi[$prodsDetil['asset_account']] : "" . $staticAssetsAccount[$prodsDetil['asset_account']];
// arrPrint($staticAssetsAccountProduksi);
// arrPrint($staticAccountHppProduksi);
// arrPrint($staticAccountPenyusutan);
// //$staticAccountHppProduksi[$prodsDetil['rekening_main']]  //pihak main child produksi
// matiHEre($prodsDetil['rekening_main']);
                            //row table view


                            $tableView .= "<tr>";
                            $tableView .= "<td>$numAsset</td>";
                            $tableView .= "<td>" . $prodsDetil['extern_id'] . "</td>";
                            $tableView .= "<td>" . $cabangData[$cabID] . "</td>";
                            $tableView .= "<td>" . $prodsDetil['extern_nama'] . "</td>";
                            $tableView .= "<td>" . $this->jenisTr . "</td>";
                            $tableView .= "<td>" . $prodsDetil['asset_account'] . "<br>" . $arrCoaAccount[$prodsDetil['asset_account']] . "</td>";
                            $tableView .= "<td>" . $prodsDetil['rekening_main'] . "<br>" . $arrCoaAccount[$prodsDetil['rekening_main']] . "</td>";
                            $tableView .= "<td>" . $prodsDetil['rekening_details'] . "<br>" . (isset($prodsDetil['rekening_details']) ? $arrCoaAccount[$prodsDetil['rekening_details']] : " - ") . "</td>";
                            $tableView .= "<td>Tgl " . $tglDepreItems . "</td>";
                            $tableView .= "<td>" . $tglTerakhirDepresiasi . "</td>";
                            $tableView .= "<td>" . number_format($lockerSource[$cabID][$produk_id]['nilai'], 2) . "</td>";
                            $tableView .= "<td>" . $txtNotif . "</td>";
                            $tableView .= "<td>NONE</td>";
                            $tableView .= "</tr>";


                            if (isset($lockerSource[$cabID][$produk_id]) && $lockerSource[$cabID][$produk_id]['nilai'] > 1 && ($dateNow - $tglDepreItems) == 0 OR
                                isset($lockerSource[$cabID][$produk_id]) && $lockerSource[$cabID][$produk_id]['nilai'] > 1 && isset($_GET['force'])) {

                                $valCount = (($prodsDetil['harga_perolehan'] - 1) / $prodsDetil['economic_life_time']);
                                $valSisaLocker = isset($lockerSource[$cabID][$produk_id]['nilai']) ? $lockerSource[$cabID][$produk_id]['nilai'] : 0;
                                $valDef = (($valSisaLocker - 1) < $valCount) ? ($valSisaLocker - 1) : ceil(($prodsDetil['harga_perolehan']) / $prodsDetil['economic_life_time']);

                                if (isset($_GET['force'])) {
                                    cekMerah($namaP . ' DIPAKSA UNTUK DIDEPRESIASI ');
                                }

                                $subtotal += $valDef;
                                $arrItems[$produk_id] = array(
                                    "harga" => $valDef,
                                    "produk_ord_hrg" => $valDef,
                                    "qty" => "1",
                                    "jml" => "1",
                                    "subtotal" => $valDef * 1,
                                    "subTotal" => $valDef * 1,
                                    "sub_total" => $valDef,
                                    "sub_subtotal" => $valDef,
                                    "sub_harga" => $valDef,
                                    "id" => $prodsDetil['extern_id'],
                                    "produk_id" => $prodsDetil['extern_id'],
                                    "produk_ord_jml" => 1,
                                    "produk_nama" => isset($prodsDetil['extern_nama']) ? $prodsDetil['extern_nama'] : "",
                                    "name" => isset($prodsDetil['extern_nama']) ? $prodsDetil['extern_nama'] : "",
                                    "nama" => isset($prodsDetil['extern_nama']) ? $prodsDetil['extern_nama'] : "",
                                    "kode" => isset($prodsDetil['kode']) ? $prodsDetil['kode'] : "",
                                    "merk" => isset($prodsDetil['merk']) ? $prodsDetil['merk'] : "",
                                    "serial_no" => isset($prodsDetil['serial_no']) ? $prodsDetil['serial_no'] : "",
                                    "extern_id" => $cabID == 25 ? "$efisensi_child2_id" : $arrCoaExtern[$prodsDetil["rekening_details"]]["extern_id"],
                                    "extern_nama" => $cabID == 25 ? "$efisensi_child2_nama" : $arrCoaExtern[$prodsDetil["rekening_details"]]["extern_nama"],
                                    "produk_jenis" => "aktiva",
                                    "hpp" => $valDef,
                                    "olehID" => "-100",
                                    "olehName" => "sys",
                                    "placeID" => $cabID,
                                    "placeName" => $cabangData[$cabID],
                                    "cabangName" => $cabangData[$cabID],
                                    "gudangName" => isset($branchData[$cabID][$gudID]['gudang_nama']) ? $branchData[$cabID][$gudID]['gudang_nama'] : "",
                                    "cabangID" => $cabID,
                                    "gudangID" => $gudID,

                                    "pihakMainChild_coa" => $pihakMainChild,
                                    "rekAkum_child_coa" => $rekAkum_child,
                                    "rekName_1_child_coa" => $rekName_1_child_coa,

                                    "comRekName_2_coa" => $comRekName_2_child,
                                    "rekName_2_coa" => $rekName_2_child,

                                    "comRekName_1_child_coa" => $comRekName_1_child,
                                    "comRekName_2_child_coa" => $comRekName_2_child,
                                    "rekName_2_child_coa" => $rekName_2_child,
                                    "comRekName_3_child_coa" => $comRekName_3_child,
                                    "rekName_3_child_coa" => $rekName_3_child,
                                    "rekName3IDChild_coa" => $rekName3IDChild,

                                    "pihakMainChild" => "penyusutan " . $pihakMain[$pihakMainID],
                                    "rekAkum_child" => $rekAkum_child,
                                    "rekName_1_child" => $rekName_1_child,
                                    "comRekName_1_child" => $comRekName_1_child,
                                    "comRekName_2_child" => $comRekName_2_child,
                                    "rekName_2_child" => $rekName_2_child,
                                    "comRekName_3_child" => $comRekName_3_child,
                                    "rekName_3_child" => $rekName_3_child,
                                    "rekName3IDChild" => $rekName3IDChild,
                                    "rekEfisiensi_coa" => "3020010",
                                    "efisensi_child_id" => $efisensi_child_id,
                                    "efisensi_child_nama" => $efisensi_child_nama,
                                    "efisensi_child2_id" => $efisensi_child2_id,
                                    "efisensi_child2_nama" => $efisensi_child2_nama,


                                );

                                if ($cabID == 25) {
                                    // $arrITems[$produk_id] = array(
                                    // "" => $comRekName_3_child,
                                    // "" => $rekName_3_child,
                                    // "" => $rekName3IDChild,
                                    // "" => $comRekName_3_child,
                                    // "" => $rekName_3_child,
                                    // "" => $rekName3IDChild,

                                    // );
                                }
                                else {
                                    unset($arrItems[$produk_id]['comRekName_3_child_coa']);
                                    unset($arrItems[$produk_id]['rekName_3_child_coa']);
                                    unset($arrItems[$produk_id]['rekName3IDChild_coa']);
                                    unset($arrItems[$produk_id]['comRekName_3_child']);
                                    unset($arrItems[$produk_id]['rekName_3_child']);
                                    unset($arrItems[$produk_id]['rekName3IDChild']);
                                    unset($arrItems[$produk_id]['rekEfisiensi_coa']);
                                    unset($arrItems[$produk_id]['efisensi_child_id']);
                                    unset($arrItems[$produk_id]['efisensi_child_nama']);
                                    unset($arrItems[$produk_id]['efisensi_child2_id']);
                                    unset($arrItems[$produk_id]['efisensi_child2_nama']);

                                    unset($itemsTmp["comRekName_3_child_coa"]);
                                    unset($itemsTmp["rekName_3_child_coa"]);
                                    unset($itemsTmp["rekName3IDChild_coa"]);
                                    unset($itemsTmp["comRekName_3_child"]);
                                    unset($itemsTmp["rekName_3_child"]);
                                    unset($itemsTmp["rekName3IDChild"]);
                                    unset($itemsTmp["rekEfisiensi_coa"]);
                                    unset($itemsTmp["efisensi_child_id"]);
                                    unset($itemsTmp["efisensi_child_nama"]);
                                    unset($itemsTmp["efisensi_child2_id"]);
                                    unset($itemsTmp["efisensi_child2_nama"]);
                                }

// arrPrint($arrItems);
//                                 matiHere(__LINE__." || ".__FUNCTION__);
                            }

                            $numAsset++;
                        }

                        //$detilSourceItems
                        //region builder main
                        $main = array(
                            "olehID" => "-100",
                            "olehName" => "sys",
                            "placeID" => $cabID,
                            "placeName" => $cabangData[$cabID],
                            "cabangID" => $cabID,
                            "cabangName" => $cabangData[$cabID],
                            "gudangID" => $gudID,
                            "gudangName" => (isset($branchData[$cabID][$gudID]['gudang_nama']) ? $branchData[$cabID][$gudID]['gudang_nama'] : ""),
                            "jenisTr" => $this->jenisTr,
                            "jenisTrMaster" => $this->jenisTr,
                            "jenisTrTop" => $this->jenisTr . "r",
                            "jenisTrName" => "request depresiasi",
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

                            "pihakMainName_coa" => $pihakMain[$pihakMainID],
                            "pihakMainID_coa" => $pihakMainID,
                            "pihakMainChild_coa" => $pihakMainChild,

                            "rekAkumPenyu_coa" => $rekAkumPenyu_coa,
                            "rekName_1_coa" => $rekName_1_child,
                            "comRekName_2_coa" => $comRekName_2_child,
                            "rekName_2_coa" => $rekName_2_child,
                            "comRekName_3_coa" => $comRekName_3_child,
                            "rekName_3_coa" => $rekName_3_child,
                            "rekName3ID_coa" => $rekName3IDChild,

                            "pihakMainName" => $pihakMain[$pihakMainID],
                            "pihakMainID" => $pihakMainID,
                            "pihakMainChild" => "penyusutan " . $pihakMain[$pihakMainID],

                            "rekAkumPenyu" => "akum penyu " . $pihakMain[$pihakMainID],
                            "rekName_1" => $rekName_1_child,
                            "comRekName_2" => $comRekName_2_child,
                            "rekName_2" => $rekName_2_child,
                            "comRekName_3" => $comRekName_3_child,
                            "rekName_3" => $rekName_3_child,
                            "rekName3ID" => $rekName3IDChild,
                            "rekEfisiensi_coa" => "3020010",
                            "efisensi_child_id" => $efisensi_child_id,
                            "efisensi_child_nama" => $efisensi_child_nama,
                            "efisensi_child2_id" => $efisensi_child2_id,
                            "efisensi_child2_nama" => $efisensi_child2_nama,


                        );
                        if ($cabID == 25) {
                            $main["rekEfisiensi_coa"] = "3020010";
                        }
                        else {
                            /*
                             * diunset biar jurnal gak jalan selain di cabang produksi supaya gerbang tidak tercipta
                             */
                            unset($main["comRekName_3_coa"]);
                            unset($main["rekName_3_coa"]);
                            unset($main["rekName3ID_coa"]);
                            unset($main["comRekName_3"]);
                            unset($main["rekName_3"]);
                            unset($main["rekName3ID"]);
                            unset($main["rekEfisiensi_coa"]);
                            unset($main["efisensi_child_id"]);
                            unset($main["efisensi_child_nama"]);
                            unset($main["efisensi_child2_id"]);
                            unset($main["efisensi_child2_nama"]);

                        }
                        //endregion builder main

                        //region builder items
                        $items = array();
                        foreach ($arrItems as $itsID => $itsData) {
                            foreach ($itemsTmp as $col => $selectedRow) {
                                $items[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
                            }
                        }
//                        ceklIme(__LINE__ . " || builder items");
//                        arrPrintWebs($items);
//                         matiHere();
                        //endregion builder items


                        //region builder tabel in master
                        $tableIn_master = array(
                            "trash" => "0",
                            "jenis_master" => $this->jenisTr,
                            "jenis_top" => $this->jenisTr . "r",
                            "jenis" => $this->jenisTr . "r",
                            "jenis_label" => "request depresiasi",
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
                            "pihakMainName" => $pihakMainID,
                            "pihakMainID" => $pihakMainID,
                        );
                        //endregion builder tabel in master

                        //region builder table in detil
                        $tableIn_detail = array();
                        foreach ($arrItems as $itsID => $itsData) {
                            foreach ($tableIn_detailTmp as $col => $selectedRow) {
                                $tableIn_detail[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
                            }
                        }
//                        arrPrintPink($tableIn_detail);
                        //endregion builder table in detil

                        //region table in master values
                        $tableIn_master_values = array(
                            "gudang" => $gID,
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
                            $gate['items'] = $arrItems;
                            $gate['main'] = $main;
                            $tCodeTargetJenisTransaksi = $jenisTrTarget = isset($this->configUi[$this->jenisTr]["steps"][1]["target"]) ? $this->configUi[$this->jenisTr]["steps"][1]["target"] : NULL;


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
//                                            if (!isset($subParams['static']["transaksi_id"])) {
//
//                                            }
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
//                                            cekbiru("gotparams dari pre-proc $comName");
//                                            arrprint($gotParams);
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
                                echo("no processor defined. skipping preprocessor..<br>");
                            }
                            //endregion


                            //region pre-processors (item)
                            if (isset($this->configCore[$this->jenisTr]['preProcessor'][1]['detail'])) {
                                $iterator = isset($this->configCore[$this->jenisTr]['preProcessor'][1]['detail']) ? $this->configCore[$this->jenisTr]['preProcessor'][1]['detail'] : array();
                                $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields']) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'] : array();
//                                echo "ITEM NUM LABELS";

                                if (sizeof($iterator) > 0) {
                                    foreach ($iterator as $cCtr => $tComSpec) {
                                        $comName = $tComSpec['comName'];
                                        $srcGateName = $tComSpec['srcGateName'];
                                        $srcRawGateName = $tComSpec['srcRawGateName'];
//                                        echo "sub-preproc: $comName, initializing values <br>";
                                        foreach ($gate[$srcGateName] as $xid => $dSpec) {
                                            $tmpOutParams[$cCtr] = array();
                                            $id = $xid;
                                            $subParams = array();
                                            if (isset($tComSpec['static'])) {
                                                foreach ($tComSpec['static'] as $key => $value) {
                                                    $realValue = makeValue($value, $gate[$srcGateName][$id], $gate[$srcGateName][$id], 0);
                                                    $subParams['static'][$key] = $realValue;
                                                }
//                                                if (!isset($subParams['static']["transaksi_id"])) {
//
//                                                }
                                                $subParams['static']["fulldate"] = date("Y-m-d");
                                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                                $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
                                            }
//                                            cekLime(":: cetak preprocc... $comName :: $srcGateName ::");
//                                            arrPrint($subParams);
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
//                                                    cekmerah("gotparams dari pre-proc $comName");
//                                                    arrprint($gotParams);
                                                    if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                                        foreach ($gotParams as $gateName => $paramSpec) {
//                                                            cekBiru(":: getParams inject ke $gateName ::");
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
//                                                                            cekHere(":: injecte ke $gateName, ::: $key diisi dengan $val");
                                                                            $gate[$gateName][$id][$key] = $val;
                                                                        }
                                                                    }
                                                                }
                                                                //==inject gotParams to child gate
//                                                                cekHitam("srcGateName = $srcGateName :: " . __LINE__);
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
                            $cn->setModul($modul_transaksi);
                            $cn->setStepCode($tCodeTargetJenisTransaksi);

//                            matiHere("modul_transaksi: $modul_transaksi || tCodeTargetJenisTransaksi: $tCodeTargetJenisTransaksi");
//arrPrint($this->configCore[$this->jenisTr]);
//                            matiHere();
                            $counterForNumber = array($this->configCore[$this->jenisTr]['formatNota']);
                            if (!in_array($counterForNumber[0], $this->configCore[$this->jenisTr]['counters'])) {
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
                            $cn->setModul($modul_transaksi);
                            $cn->setStepCode($tCodeTargetJenisTransaksi);
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

                                $injectors_items = array(
                                    "transaksi_id" => $insertID,
                                    "transaksi_no" => $tmpNomorNota,
                                    "nomer" => $tmpNomorNota,
                                );
                            }
                            if (sizeof($tableIn_master_values) > 0) {
                                if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['mainValues'])) {
                                    $inserMainValues = array();
                                    foreach ($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['mainValues'] as $key => $src) {
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

//                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
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
                                $insertDeIDs = array();
                                foreach ($tableIn_detail as $dSpec) {
                                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                                    $insertIDs[] = $insertDetailID;
                                    $insertDeIDs[$insertID][] = $insertDetailID;
                                    $mongoList['detail'][] = $insertDetailID;
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
//                                cekUngu($this->db->last_query());
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
//                            arrPrintWebs($main);
//                            arrPrintWebs($items);
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
//                            die("sini");
//                            $doWriteReg = $tr->writeRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
                            $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));

                            $mongRegID[] = $doWriteReg;
//                            arrPrint($mongRegID);
//                            matiHEre(floatval($subtotal));
//                            arrPrint($baseRegistries);

                            //endregion

//                            validateAllBalances($cabangData[$dataTmp->cabang_id]);
                            //region processing sub-post-processors, always items
//                            $iterator = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['postProcessor'][1]['detail']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['postProcessor'][1]['detail'] : array();
                            $iterator = isset($this->configCore[$this->jenisTr]['postProcessor'][$jenisTrTarget]['detail']) ? $this->configCore[$this->jenisTr]['postProcessor'][$jenisTrTarget]['detail'] : array();
                            arrprint($iterator);

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
                                            $subParams['static']["keterangan"] = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . (isset($this->session->login['nama']) ? $this->session->login['nama'] : "sys");
                                        }

                                        if (sizeof($subParams) > 0) {
                                            $tmpOutParams[$cCtr][] = $subParams;
                                        }
//                                        echo "<script>top.writeProgress('" . $subParams['static']['name'] . " " . $subParams['static']['extern_nama'] . " " . $subParams['static']['nama'] . "');</script>";
                                    }
                                }

//                                arrPrintWebs($iterator);

                                foreach ($iterator as $cCtr => $tComSpec) {
                                    $comName = $tComSpec['comName'];
                                    $srcGateName = $tComSpec['srcGateName'];
                                    $srcRawGateName = $tComSpec['srcRawGateName'];
//                                    echo "sub-postProcessor: $comName, sending values <br>";
//                                    echo "<script>top.writeProgress('SENDING SUB-PROCESSORS ($comName)...', 'head');</script>";
                                    $mdlName = "Com" . ucfirst($comName);
                                    $this->load->model("Coms/" . $mdlName);
                                    $m = new $mdlName();
                                    arrprint($tmpOutParams[$cCtr]);
//                                    matiHEre(__LINE__);
                                    $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                    $m->exec() or die("Gagal saat berusaha exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
//                                    matiHEre(__LINE__);
                                    cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                                }
                            }
//                            matiHEre(__LINE__);
                            //endregion


                            // endregion
                            //region writelog
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
                                "url" => "",
                            );
                            $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));

//                            $a->updateData("id")

                            foreach ($arrItems as $itsID => $itsData) {
                                $cekId = $d->updateData(
                                    array(
                                        "extern_id" => $itsID,
                                        "cabang_id" => $itsData['cabangID'],
                                    ),
                                    array(
                                        "dtime_last_depresiasi" => date("Y-m-d H:i:s"),
                                        "last_tr_id" => $insertID,
                                    )
                                ) or die("Failed to update tr next-state!");

//                                cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                            }

//                            arrPrint($cekId);

                        }
                        else {
//                            cekOrange('gak bikin transaksi mungkin nilai udh abiss wkwkwk');
                        }

                        //endregion
                    }
                }
            }

            $tableView .= "</tbody>";
            $tableView .= "</table>";
        }
//matiHere(__LINE__);
        cekOrange("======================================= batas bawah ASSET =======================================");
        //endregion batas aset

        cekHere($tableView);

//        mati_disini("============= BELUM COMMIT COA===============".__LINE__);


        if (isset($_GET['debug'])) {
            mati_disini("============= BELUM COMMIT SEWA COA DEBUG ===============");
        }

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("======================================= BERHASIL COMMIT =======================================");
        //region cloning mongoDB
        $mongoList = array();
        $mongRegID = array();
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
                        $mong->addData($transksi_main);
                    }
                }
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
                if (sizeof($tmpTrm) > 0) {
                    $mong->setTableName("transaksi_registry");
                    foreach ($tmpTrm as $tmpMain) {
                        $transksi_main = json_decode(json_encode($tmpMain), true);
                        $mong->addData($transksi_main);
                    }
                }
            }
        }
        //endregion
        if (isset($_GET['force'])) {
            if (isset($_GET['byexternid'])) {
                writeLog(__FUNCTION__, "force depresiasi", "depresiasi", $_GET['byexternid'], $this->session->login['nama']);
            }
            else {
                writeLog(__FUNCTION__, "force depresiasi", "depresiasi", "all", $this->session->login['nama']);
            }
        }

        echo "jika halaman tidak menutup dengan otomatis, anda boleh klik close";

    }


    public function index()
    {

        /*
         * list data depresiasi
         * grouping by cabang
         * generate per item jadi transaksi untuk di otorisasi
         * id aset,nama aset sebagai rekening
         * untuk solo pembantu efisiensi ambil dari pair produkprebiaya berdasarkan coa untuk dapat id dan labelnya
         * dibwa ke main dan items untuk pembentu level 2 extern2_id
         */
//        $_GET["force"] = 1;
//        $produkException = array(
//
//        );
//

        echo "<title>AUTO-DEPRE</title>";

        cekHijau('byrekmainid = select dengan id rekening');
        cekHijau('byexternid = select dengan extern/id produk / asset');
        cekHijau('force = paksa depresiasi tanpa lihat tanggal depre');
        cekHijau('exe = harus di pakai untuk melakukan execution transaksi');
        $modul_transaksi = $this->modul;
        $this->jenisTr = "8787";//ditembak untuk auto generate
        $this->tableInConfig = isset($this->configUi[$this->jenisTr]['tableIn']) ? $this->configUi[$this->jenisTr]['tableIn'] : array();
        $this->tableInConfig_static = isset($this->configUi[$this->jenisTr]['tableIn_static']) ? $this->configUi[$this->jenisTr]['tableIn_static'] : array();

        //list rekening dari config HeAccounting
        $accountChilds = $this->config->item("accountChilds");

        //region batas aset
        $this->load->model("Mdls/MdlAccounts");
        $this->load->model("Mdls/MdlSetupDepresiasiAssetsProduction");
        $this->load->model("Mdls/MdlProdukRakitanPreBiaya");//untuk baca biaya produksi
        $this->load->model("Mdls/MdlDtaBiayaProduksi");//untuk baca biaya produksi


        $d = new MdlSetupDepresiasiAssetsProduction(); //
        $f = new MdlFolderAset(); //aset wujud dan sewa sama2 pakai ini
        $a = new MdlAsetDetail(); //aset wujud dan sewa sudah termasuk
        $pr = new MdlProdukRakitanPreBiaya();

        $masterPrebiaya = $pr->lookUpAll()->result();
        $dtaProduksi = array();
        $dataPrebiaya = array();
        if (sizeof($masterPrebiaya) > 0) {
            $dta_p = new MdlDtaBiayaProduksi();
            $dtaBiayaproduksi = $dta_p->lookUpAll()->result();

            foreach ($dtaBiayaproduksi as $tempBiayaProduksi) {
                $dtaProduksi[$tempBiayaProduksi->id] = $tempBiayaProduksi->nama;
            }
            foreach ($masterPrebiaya as $preBiayaTmp) {
                // arrPrint($preBiayaTmp);
                $dataPrebiaya[$preBiayaTmp->coa_code] = array(
                    "id" => $preBiayaTmp->id,
                    "nama" => $preBiayaTmp->nama,
                    "coa_code_2" => $preBiayaTmp->coa_code_2,//efisiensi
                );
            }
        }
//         arrPrint($dataPrebiaya);
// matiHEre();
        if (isset($_GET["byexternid"])) {
            $d->addFilter("extern_id='" . $_GET["byexternid"] . "'");
        }
        $a->addFilter("jenis=item");

        $folders = $f->lookupAll()->result();
        $detailItems = $a->lookupAll()->result();
        $arrCoaList = $this->MdlAccounts->get_userlist();

//         arrPrint($arrCoaList);

        $arrCoa = array();
        $arrCoaExtern = array();
        if (!empty($arrCoaList)) {
            foreach ($arrCoaList as $kk => $cc) {
                $arrCoa[$cc->head_code] = $cc->head_name;
                $arrCoaExtern[$cc->head_code] = array(
                    "extern_id" => $cc->extern_id,
                    "extern_nama" => $cc->head_name,
                );
            }
        }
//         arrPrint($arrCoa);
//         matiHere(__LINE__);
        $arrCoa_P_HeadCode = array();
        if (!empty($arrCoaList)) {
            foreach ($arrCoaList as $kk => $cc) {
                $arrCoa_P_HeadCode[$cc->head_code] = $cc->p_head_code;
            }
        }

//        arrPrint($arrCoa);
// matiHere();
        $data = $d->lookupAll()->result();
//        arrPrint($data);
        cekMerah($this->db->last_query());
// matiHEre();
        //region cek pre value locker value
        $this->load->model("Mdls/MdlLockerValue");
        $l = new MdlLockerValue();
        $l->addFilter("jenis=aktiva");
        $l->addFilter("state=active");
        $lockerValue = $l->lookupAll()->result();
        showLast_query("biru");
        $lockerSource = array();
        if (sizeof($lockerValue) > 0) {
            foreach ($lockerValue as $lockerTmp) {
                $lockerSource[$lockerTmp->cabang_id][$lockerTmp->produk_id] = array(
                    "nilai" => $lockerTmp->nilai,
                    "gudang_id" => $lockerTmp->gudang_id,
                );
            }
        }
        //endregion

        $this->db->trans_start();
        //tabahin detector tanggal sini broo untuk dijalankan tiap tanggal
        //-----belum disetup ya!!

        //tambahin auto rekekning (baca dari kolom folders);
        $pihakMain = array();
        $pihakMain_coa = array();
        foreach ($folders as $foldersTmp) {
            $pihakMain[$foldersTmp->id] = $foldersTmp->nama;
            $pihakMain_coa[$foldersTmp->coa_code] = $foldersTmp->coa_code;
        }

        //tambahin auto rekekning (baca dari kolom folders);
        $this->load->model("Mdls/MdlProdukRakitanPreBiaya");
        $bmp = new MdlProdukRakitanPreBiaya();
        $biayaMethodProduksi = $bmp->lookupAll()->result();
        // matiHEre($this->db->last_query());
        // matiHere();
        $autoRekNameProduksi_2 = array();
        $autoRekNameProduksi_lv2 = array();
        foreach ($biayaMethodProduksi as $biayaMethodProduksiTmp) {
//            $autoRekNameProduksi_2[$biayaMethodProduksiTmp->id] = $biayaMethodProduksiTmp->nama;
//            $autoRekNameProduksi_lv2[$biayaMethodProduksiTmp->nama] = "MdlRekeningPembantuBiayaKomposisiProduksi";
            $autoRekNameProduksi_2[$biayaMethodProduksiTmp->coa_code] = $biayaMethodProduksiTmp->nama;
            $autoRekNameProduksi_lv2[$biayaMethodProduksiTmp->coa_code] = "MdlRekeningPembantuBiayaKomposisiProduksi";
        }

        //tambahin auto rekekning (baca dari kolom folders);
        $this->load->model("Mdls/MdlRekeningBesar");
        $rb = new MdlRekeningBesar();
        $rekeningBesar = $rb->lookupAll()->result();
        $autoRekNameSales_2 = array();
        $autoRekNameSales_lv2 = array();
        foreach ($rekeningBesar as $rekeningBesarTmp) {
//            $autoRekNameSales_2[$rekeningBesarTmp->id] = $rekeningBesarTmp->nama;
//            $autoRekNameSales_lv2[$rekeningBesarTmp->nama] = $rekeningBesarTmp->mdl_name;
            $autoRekNameSales_2[$rekeningBesarTmp->coa_code] = $rekeningBesarTmp->coa_code;
            $autoRekNameSales_lv2[$rekeningBesarTmp->coa_code] = $rekeningBesarTmp->mdl_name;
        }

        //tambahin auto rekekning (baca dari kolom folders);
        $autoRekNameSales_3 = array();
        if (sizeof($autoRekNameSales_lv2) > 0) {
            foreach ($autoRekNameSales_lv2 as $name => $mdlName) {
                $this->load->model("Mdls/$mdlName");
                $prpb = new $mdlName();
                $produkRakitanPreBiaya = $prpb->lookupAll()->result();
                foreach ($produkRakitanPreBiaya as $produkRakitanPreBiayaTmp) {
                    $autoRekNameSales_3[$name][$produkRakitanPreBiayaTmp->id] = $produkRakitanPreBiayaTmp->nama;
                }
            }
        }

        //Coa Account Alias
        $arrCoaAccount = fetchAccountStructureAlias();

//arrPrint($arrCoaAccount);
//matiHere(__LINE__);

//        $staticAccountComRekening = array(
//            "kendaraan"             => "RekeningPembantuAkumPenyusutanKendaraan",
//            "peralatan kantor"      => "RekeningPembantuAkumPenyusutanPeralatanKantor",
//            "mesin produksi"        => "RekeningPembantuAkumPenyusutanMesinProduksi",
//            "mesin"                 => "RekeningPembantuAkumPenyusutanMesin",
//            "peralatan produksi"    => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
//            "tanah dan bangunan"    => "RekeningPembantuAkumPenyusutanBangunan",
//        );

        $staticAccountComRekening = array(
            // //rombongan aktiva coa lama
            // "01050100005"  => "RekeningPembantuAkumPenyusutanKendaraan",
            // "01050100004"  => "RekeningPembantuAkumPenyusutanPeralatanKantor",
            // "01050100002"  => "RekeningPembantuAkumPenyusutanMesinProduksi",
            // "01050100006"  => "RekeningPembantuAkumPenyusutanMesin",
            // "01050100003"  => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
            // "01050100001"  => "RekeningPembantuAkumPenyusutanBangunan",
            //
            // //rombongan biaya
            // "061100001"  => "RekeningPembantuAkumPenyusutanKendaraan",
            // "061100002"  => "RekeningPembantuAkumPenyusutanPeralatanKantor",
            // "061100005"  => "RekeningPembantuAkumPenyusutanMesinProduksi",
            // "061100004"  => "RekeningPembantuAkumPenyusutanMesin",
            // "061100003"  => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
            // "061100006"  => "RekeningPembantuAkumPenyusutanBangunan",

            //rombongan aktiva
            "1020010010" => "RekeningPembantuAkumPenyusutanKendaraan",
            "1020020010" => "RekeningPembantuAkumPenyusutanPeralatanKantor",
            "1020040010" => "RekeningPembantuAkumPenyusutanMesinProduksi",
            "1020030010" => "RekeningPembantuAkumPenyusutanMesin",
            "1020041010" => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
            "1020050010" => "RekeningPembantuAkumPenyusutanBangunan",

            //rombongan biaya
            "6040010" => "RekeningPembantuAkumPenyusutanKendaraan",
            "6040020" => "RekeningPembantuAkumPenyusutanPeralatanKantor",
            "6040050" => "RekeningPembantuAkumPenyusutanMesinProduksi",
            "6040040" => "RekeningPembantuAkumPenyusutanMesin",
            "6040030" => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
            "6040060" => "RekeningPembantuAkumPenyusutanBangunan",
        );

        //pilih menggunakan asset account
        $staticAssetsAccount = array(
            "1020010010" => "1020010020", //Akum Penyu Kendaraan
            "1020020010" => "1020020020", //Akum Penyu Peralatan Kantor
            "1020040010" => "1020040020", //Akum Penyu Mesin Produksi
            "1020030010" => "1020030020", //Akum Penyu Mesin
            "1020041010" => "1020041020", //Akum Penyu Peralatan Produksi
            "1020050010" => "1020050020", //Akum Penyu Tanah Dan Bangunan
        );

        //ini hanya untuk solo, pilih dengan akun utama (dalam hal ini static) JIKA ADA :P
        $staticAssetsAccountProduksi = array(
            "1020010010" => "1020010020", //Akum Penyu Kendaraan
            "1020020010" => "1020020020", //Akum Penyu Peralatan Kantor
            "1020040010" => "1020040020", //Akum Penyu Mesin Produksi
            "1020030010" => "1020030020", //Akum Penyu Mesin
            "1020041010" => "1020041020", //Akum Penyu Peralatan Produksi
            "1020050010" => "1020050020", //Akum Penyu Tanah Dan Bangunan
        );

        $staticAccountPenyusutan = array(
            "1020010010" => "6040010",//beban penyusutan kendaraan
            "1020020010" => "6040020",//penyusutan peraltan kantor
            "1020041010" => "6040030",//penyusutan peralatan produksi
            "1020030010" => "6040040",//penyususutan mesin
            "1020040010" => "6040050",//penyusutan meisn prouksi
            "1020050010" => "6040060",//penyusutan bangunan
        );

        $staticAccountHppProduksi = array(
            //efisiensi vs hpp produk produksi
            //delivery cost =>delivery cost
            "5020020" => "3020010010",
            //direct labour =>direct labour
            "5020030" => "3020010020",
            //quality =>quality
            "5020040" => "3020010030",
        );
        $itemsFields = $a->getFields();
        $groupItems = array();
        $kol = array();
        foreach ($itemsFields as $tmpFields) {
            $kol[] = $tmpFields['kolom'];
        }
        foreach ($detailItems as $itemsData) {
            foreach ($kol as $kolom) {
//                if ($itemsData->dtime_last_depresiasi == "" OR
//                    isset($itemsData->dtime_last_depresiasi) && date("Y-m", strtotime($itemsData->dtime_last_depresiasi)) != date("Y-m") OR
//                    isset($_GET['force'])) {
                $groupItems[$itemsData->folders][$itemsData->id][$kolom] = $itemsData->$kolom;
//                }
            }
        }
        // arrPrint($groupItems);


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
            "jenisTrName" => "request depresiasi",
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
            "pihakMainName" => "",
            "pihakMainID" => "",


            "comRekName_2_coa" => "comRekName_2_child_coa",
            "rekName_2_coa" => "rekName_2_child_coa",
            "comRekName_3_coa" => "comRekName_3_child_coa",
            "rekName_3_coa" => "rekName_3_child_coa",

            "comRekName_2" => "comRekName_2_child",
            "rekName_2" => "rekName_2_child",
            "comRekName_3" => "comRekName_3_child",
            "rekName_3" => "rekName_3_child",
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
            "place2ID" => "-1",
            "place2Name" => "PUSAT",
            "cabang2ID" => "-1",
            "cabang2Name" => "PUSAT",

            "pihakMainChild_coa" => "pihakMainChild_coa",
            "rekAkum_child_coa" => "rekAkum_child_coa",

            "comRekName_1_child_coa" => "comRekName_1_child_coa",
            "comRekName_2_child_coa" => "comRekName_2_child_coa",
            "comRekName_3_child_coa" => "comRekName_3_child_coa",

            "rekName_1_child_coa" => "rekName_1_child_coa",
            "rekName_2_child_coa" => "rekName_2_child_coa",
            "rekName_3_child_coa" => "rekName_3_child_coa",

            "rekName1IDChild_coa" => "rekName_1_child_coa",
            "rekName2IDChild_coa" => "rekName2IDChild_coa",
            "rekName3IDChild_coa" => "rekName3IDChild_coa",

            "pihakMainChild" => "pihakMainChild",
            "rekAkum_child" => "rekAkum_child",

            "comRekName_2_coa" => "comRekName_2_coa",
            "rekName_2_coa" => "rekName_2_coa",

            "comRekName_1_child" => "comRekName_1_child",
            "comRekName_2_child" => "comRekName_2_child",
            "comRekName_3_child" => "comRekName_3_child",

            "rekName_1_child" => "rekName_1_child",
            "rekName_2_child" => "rekName_2_child",
            "rekName_3_child" => "rekName_3_child",

            "rekName1IDChild" => "rekName_1_child",
            "rekName2IDChild" => "rekName2IDChild",
            "rekName3IDChild" => "rekName3IDChild",
            "rekEfisiensi_coa" => "rekEfisiensi_coa",
            //pembantu efisiensi
            "efisensi_child_id" => "efisensi_child_id",
            "efisensi_child_nama" => "efisensi_child_nama",
            "efisensi_child2_id" => "efisensi_child2_id",
            "efisensi_child2_nama" => "efisensi_child2_nama",

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
            "jenis_label" => "request depresiasi",
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
            "gudang2" => Array
            (
                "elementType" => "dataModel",
                "name" => "gudang2",
                "key" => "-1",
                "labelSrc" => "name",
                "label" => "gudang dc",
                "labelValue" => "default center warehouse",
                "mdl_name" => "MdlGudangDefault_center",
                "contents" => "YToxOntzOjQ6Im5hbWEiO3M6MDoiIjt9",
                "contents_intext" => print_r(array("nama" => ""), 1),
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
        $relOptionConfigs = isset($this->configUi[$this->jenisTr]['relativeOptions']) ? $this->configUi[$this->jenisTr]['relativeOptions'] : array();
        $title = $this->configUi[$jenisTr]["label"];
        $subTitle = $this->configUi[$jenisTr]["steps"][1]['label'];

//        arrPrint($data);
//        mati_disini(__LINE__);

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
            $this->load->model("Mdls/MdlMongoMother");
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
            $nn = 0;
            $sourceItems = array();
            foreach ($data as $dataExtern) {
                $rekMain = $dataExtern->rekening_main;
                $sourceItemsTmp = array();
                if ($dataExtern->dtime_last_depresiasi == "" OR
                    isset($dataExtern->dtime_last_depresiasi) && date("Y-m", strtotime($dataExtern->dtime_last_depresiasi)) != date("Y-m") OR
                    isset($_GET['force'])) {
                    $nn++;
                    cekHijau("[$nn] RUNNING DEPRESIASI... || " . $dataExtern->extern_id . " || " . $dataExtern->extern_nama);

                    foreach ($dataExtern as $coll => $colVal) {
                        $sourceItemsTmp[$coll] = $colVal;
                    }
                    if ($lockerSource[$dataExtern->cabang_id][$dataExtern->extern_id]['nilai'] > 1) {
                        $sourceItems[$dataExtern->cabang_id][$rekMain][$dataExtern->extern_id] = $sourceItemsTmp;
                    }
                    else {
                        // jika mau tetap di tampilkan pada antarmuka CLI,
                        // maka produk/jasa akan tampil di CLI, namun tidak akan di proses penyusutan nya...
                        // $sourceItems[$dataExtern->cabang_id][$rekMain][$dataExtern->extern_id] = $sourceItemsTmp;
                    }
                }
                else {
                    cekHitam("TIDAK RUNNING DEPRESIASI... || " . $dataExtern->extern_id . " || " . $dataExtern->extern_nama);
                }
            }

            //region build dulu berdasarkan jenis depresiasi
            $finalData = array();
            foreach ($sourceItems as $cabID => $dataSource2) {
                foreach ($dataSource2 as $_rekMainID => $dataSource) {
//                cekMerah("cabID: $cabID | dataSource");
//                arrPrint($dataSource);
                    foreach ($groupItems as $gID => $detailGID) {
//                    cekMerah("gID: $gID | detailGID");
//                    arrPrint($detailGID);
                        foreach ($detailGID as $externID => $detilExtern) {
//                        cekMerah("externID: $externID | detilExtern");
//                        arrPrint($detilExtern);
                            if (isset($dataSource[$externID])) {
                                $finalData[$cabID][$_rekMainID][$gID][$externID] = $dataSource[$externID] + $detilExtern;
                            }
                        }
                    }
                }
            }

            $tableView = "";
            $tableView .= "<table cellspacing='1' cellpadding='1' border='1' bgcolor='#fff'>";
            $tableView .= "<caption>ITEMS SEWA</caption>";
            $tableView .= "<thead>";
            $tableView .= "<tr>";
            $tableView .= "<th>No.</th>";
            $tableView .= "<th>Extern No</th>";
            $tableView .= "<th>Cabang/Pusat</th>";
            $tableView .= "<th>Nama Asset/Sewa</th>";
            $tableView .= "<th>Jenis TR</th>";
            $tableView .= "<th>Rek ACC</th>";
            $tableView .= "<th>Rek MAIN</th>";
            $tableView .= "<th>Rek Details</th>";
            $tableView .= "<th>TGL Depre</th>";
            $tableView .= "<th>Last Depre</th>";
            $tableView .= "<th>Nilai Sisa (locker)</th>";
            $tableView .= "<th>Keterangan</th>";
            $tableView .= "<th>ACTION</th>";
            $tableView .= "</tr>";
            $tableView .= "</thead>";
            $tableView .= "<tbody>";


            //endregion build dulu berdasarkan jenis depresiasi

            foreach ($finalData as $cabID => $detilSourceTmp) {
                foreach ($detilSourceTmp as $rekMainID => $detilSource) {
                    if (isset($_GET['byrekmainid'])) {
                        $tmp_detilSource = $detilSource;
                        $detilSource = array();
                        $arrRek = explode(",", $_GET['byrekmainid']);
                        foreach ($tmp_detilSource as $rekID => $iDetails) {
                            if (in_array($rekID, $arrRek)) {
                                $detilSource[$rekID] = $iDetails;
                            }
                        }
                    }
//                    arrprint($detilSource);
//                    matiHEre();
                    foreach ($detilSource as $pihakMainID => $detilSourceItems) {
                        //transaksi main buildder taro sini
                        $arrItems = array();
                        $subtotal = 0;

                        $rekName_1_child = "";
                        $comRekName_2_child = "";
                        $rekName_2_child = "";
                        $comRekName_3_child = "";
                        $rekName_3_child = "";
                        $rekName3IDChild = "";

                        if (isset($_GET['byexternid'])) {
                            $tmp_detilSourceItems = $detilSourceItems;
                            $detilSourceItems = array();
                            $arrExtrn = explode(",", $_GET['byexternid']);
                            foreach ($tmp_detilSourceItems as $extID => $iDetails) {
                                if (in_array($extID, $arrExtrn)) {
                                    $detilSourceItems[$extID] = $iDetails;
                                }
                            }
                        }

                        $numAsset = 1;

                        foreach ($detilSourceItems as $produk_id => $prodsDetil) {

                            // cekKuning(":: masuk disini ::");
                            // arrPrintKuning($prodsDetil);
//                            arrPrint($arrCoa[$prodsDetil['rekening_details']]);
                            // matiHEre();

                            $this->jenisTr = "8787"; // selain pusat

                            if ($cabID == -1) {
                                $this->jenisTr = "8786"; //khusus pusat
                            }

                            $dateNow = date('d');
                            $tglDepreItems = $prodsDetil['repeat'];
                            $namaP = isset($prodsDetil['extern_nama']) ? $prodsDetil['extern_nama'] : "";
                            $tglTerakhirDepresiasi = $prodsDetil['dtime_last_depresiasi'];
                            $gudang_nama = isset($cabangData[$cabID]) ? $cabangData[$cabID] : "undefined";

//                            if($lockerSource[$cabID][$produk_id]['nilai']*1==1){
//                                cekHijau("(" . $prodsDetil['extern_id'] . ") $namaP | kurang " . ($tglDepreItems - $dateNow) . "NILAI DEPRESIASI SUDAH HABIS");
//                                cekHijau('stockLocker: ' . $lockerSource[$cabID][$produk_id]['nilai'] . " || " . "used_value: (" . $prodsDetil['value_used'] . ")");
//                            }
//                            else{
//                                cekHitam(
//                                    ($dateNow - $tglDepreItems) == 0 ?
//                                        " cabang $cabID ($gudang_nama) (" . $prodsDetil['extern_id'] . ") $namaP (" . $prodsDetil['value_used'] . ") | saatnya depresiasi tgl " . date('Y-m-d H:i:s') :
//                                        (($dateNow - $tglDepreItems) < 0 ?
//                                            "(" . $prodsDetil['extern_id'] . ") $namaP | kurang " . ($tglDepreItems - $dateNow) . " hari hingga depresiasi" :
//                                            "(" . $prodsDetil['extern_id'] . ") $namaP | sudah lewat " . ($dateNow - $tglDepreItems) . " hari sejak depresiasi")
//                                );
//                                cekHere('stockLocker: ' . $lockerSource[$cabID][$produk_id]['nilai'] . " || " . "used_value: (" . $prodsDetil['value_used'] . ")");
//                            }

                            $txtNotif = ($dateNow - $tglDepreItems) == 0 ? "saatnya amortisasi<br>tgl " . date('Y-m-d H:i:s') :
                                (($dateNow - $tglDepreItems) < 0 ? "kurang " . ($tglDepreItems - $dateNow) . " hari hingga amortisasi" :
                                    "lewat " . ($dateNow - $tglDepreItems) . " hari");

                            $gudSpec = getDefaultWarehouseID($cabID);
                            $gudID = isset($lockerSource[$cabID][$produk_id]['gudang_id']) ? $lockerSource[$cabID][$produk_id]['gudang_id'] : $gudSpec['gudang_id'];

                            $rekName_1_child = "" . isset($pihakMain[$prodsDetil['asset_account']]) ? "" . $pihakMain[$prodsDetil['asset_account']] : "";
                            $rekName_1_child_coa = "" . $prodsDetil['asset_account'];
                            $comRekName_1_child = isset($staticAccountComRekening[$rekName_1_child_coa]) ? "" . $staticAccountComRekening[$rekName_1_child_coa] : "";
                            $comRekName_2_child = $cabID == 25 ? "" . $accountChilds[$prodsDetil['rekening_main']] : "" . $accountChilds[$prodsDetil['rekening_main']];
                            $rekName_2_child = $cabID == 25 ? "" . $prodsDetil['rekening_main'] : "" . $prodsDetil['rekening_main'];
                            $comRekName_3_child = $cabID == 25 ? 0 : "RekeningPembantuDepresiasi";

                            cekMerah($prodsDetil['rekening_main']);
                            cekMerah($prodsDetil['rekening_details']);
                            arrPrintWebs($prodsDetil);
                            cekHitam("LINE: " . __LINE__ . " rekening_main | rekening_details");

                            $rekName_3_child = $cabID == 25 ? $arrCoa[$prodsDetil['rekening_main']] : "" . $arrCoa[$prodsDetil['rekening_details']];
                            $rekName3IDChild = $cabID == 25 ? $staticAccountHppProduksi[$prodsDetil['rekening_main']] : "" . $prodsDetil['rekening_details'];


                            //untuk urusan efisiensi
                            $efisensi_child_id = $cabID == 25 ? $dataPrebiaya[$prodsDetil['rekening_main']]["id"] : "";
                            $efisensi_child_nama = $cabID == 25 ? $dataPrebiaya[$prodsDetil['rekening_main']]["nama"] : "";
                            $efisensi_child2_id = $cabID == 25 ? $prodsDetil['rekening_details'] : "";
                            $efisensi_child2_nama = $cabID == 25 ? $dtaProduksi[$prodsDetil['rekening_details']] : "";

                            $pihakMainChild = $cabID == 25 ? "" . $staticAccountPenyusutan[$prodsDetil['asset_account']] : "" . $staticAccountPenyusutan[$prodsDetil['asset_account']];

                            $rekAkumPenyu_coa = $cabID == 25 ? "" . $staticAssetsAccountProduksi[$prodsDetil['asset_account']] : "" . $staticAssetsAccount[$prodsDetil['asset_account']];
                            $rekAkum_child = $cabID == 25 ? "" . $staticAssetsAccountProduksi[$prodsDetil['asset_account']] : "" . $staticAssetsAccount[$prodsDetil['asset_account']];
// arrPrint($staticAssetsAccountProduksi);
// arrPrint($staticAccountHppProduksi);
// arrPrint($staticAccountPenyusutan);
// //$staticAccountHppProduksi[$prodsDetil['rekening_main']]  //pihak main child produksi
// matiHEre($prodsDetil['rekening_main']);
                            //row table view


                            $tableView .= "<tr>";
                            $tableView .= "<td>$numAsset</td>";
                            $tableView .= "<td>" . $prodsDetil['extern_id'] . "</td>";
                            $tableView .= "<td>" . $cabangData[$cabID] . "</td>";
                            $tableView .= "<td>" . $prodsDetil['extern_nama'] . "</td>";
                            $tableView .= "<td>" . $this->jenisTr . "</td>";
                            $tableView .= "<td>" . $prodsDetil['asset_account'] . "<br>" . $arrCoaAccount[$prodsDetil['asset_account']] . "</td>";
                            $tableView .= "<td>" . $prodsDetil['rekening_main'] . "<br>" . $arrCoaAccount[$prodsDetil['rekening_main']] . "</td>";
                            $tableView .= "<td>" . $prodsDetil['rekening_details'] . "<br>" . (isset($prodsDetil['rekening_details']) ? $arrCoaAccount[$prodsDetil['rekening_details']] : " - ") . "</td>";
                            $tableView .= "<td>Tgl " . $tglDepreItems . "</td>";
                            $tableView .= "<td>" . $tglTerakhirDepresiasi . "</td>";
                            $tableView .= "<td>" . number_format($lockerSource[$cabID][$produk_id]['nilai'], 2) . "</td>";
                            $tableView .= "<td>" . $txtNotif . "</td>";
                            $tableView .= "<td>NONE</td>";
                            $tableView .= "</tr>";


                            if ((isset($lockerSource[$cabID][$produk_id]) && $lockerSource[$cabID][$produk_id]['nilai'] > 1 && ($dateNow - $tglDepreItems) == 0) OR
                                (isset($lockerSource[$cabID][$produk_id]) && $lockerSource[$cabID][$produk_id]['nilai'] > 1 && isset($_GET['force']))) {

                                $valCount = (($prodsDetil['harga_perolehan'] - 1) / $prodsDetil['economic_life_time']);
                                $valSisaLocker = isset($lockerSource[$cabID][$produk_id]['nilai']) ? $lockerSource[$cabID][$produk_id]['nilai'] : 0;
                                $valDef = (($valSisaLocker - 1) < $valCount) ? ($valSisaLocker - 1) : ceil(($prodsDetil['harga_perolehan']) / $prodsDetil['economic_life_time']);

                                if (isset($_GET['force'])) {
                                    cekMerah($namaP . ' DIPAKSA UNTUK DIDEPRESIASI ');
                                }

                                $subtotal += $valDef;
                                $arrItems[$produk_id] = array(
                                    "harga" => $valDef,
                                    "produk_ord_hrg" => $valDef,
                                    "qty" => "1",
                                    "jml" => "1",
                                    "subtotal" => $valDef * 1,
                                    "subTotal" => $valDef * 1,
                                    "sub_total" => $valDef,
                                    "sub_subtotal" => $valDef,
                                    "sub_harga" => $valDef,
                                    "id" => $prodsDetil['extern_id'],
                                    "produk_id" => $prodsDetil['extern_id'],
                                    "produk_ord_jml" => 1,
                                    "produk_nama" => isset($prodsDetil['extern_nama']) ? $prodsDetil['extern_nama'] : "",
                                    "name" => isset($prodsDetil['extern_nama']) ? $prodsDetil['extern_nama'] : "",
                                    "nama" => isset($prodsDetil['extern_nama']) ? $prodsDetil['extern_nama'] : "",
                                    "kode" => isset($prodsDetil['kode']) ? $prodsDetil['kode'] : "",
                                    "merk" => isset($prodsDetil['merk']) ? $prodsDetil['merk'] : "",
                                    "serial_no" => isset($prodsDetil['serial_no']) ? $prodsDetil['serial_no'] : "",
                                    "extern_id" => $cabID == 25 ? "$efisensi_child2_id" : $arrCoaExtern[$prodsDetil["rekening_details"]]["extern_id"],
                                    "extern_nama" => $cabID == 25 ? "$efisensi_child2_nama" : $arrCoaExtern[$prodsDetil["rekening_details"]]["extern_nama"],
                                    "produk_jenis" => "aktiva",
                                    "hpp" => $valDef,
                                    "olehID" => "-100",
                                    "olehName" => "sys",
                                    "placeID" => $cabID,
                                    "placeName" => $cabangData[$cabID],
                                    "cabangName" => $cabangData[$cabID],
                                    "gudangName" => isset($branchData[$cabID][$gudID]['gudang_nama']) ? $branchData[$cabID][$gudID]['gudang_nama'] : "",
                                    "cabangID" => $cabID,
                                    "gudangID" => $gudID,

                                    "pihakMainChild_coa" => $pihakMainChild,
                                    "rekAkum_child_coa" => $rekAkum_child,
                                    "rekName_1_child_coa" => $rekName_1_child_coa,

                                    "comRekName_2_coa" => $comRekName_2_child,
                                    "rekName_2_coa" => $rekName_2_child,

                                    "comRekName_1_child_coa" => $comRekName_1_child,
                                    "comRekName_2_child_coa" => $comRekName_2_child,
                                    "rekName_2_child_coa" => $rekName_2_child,
                                    "comRekName_3_child_coa" => $comRekName_3_child,
                                    "rekName_3_child_coa" => $rekName_3_child,
                                    "rekName3IDChild_coa" => $rekName3IDChild,

                                    "pihakMainChild" => "penyusutan " . $pihakMain[$pihakMainID],
                                    "rekAkum_child" => $rekAkum_child,
                                    "rekName_1_child" => $rekName_1_child,
                                    "comRekName_1_child" => $comRekName_1_child,
                                    "comRekName_2_child" => $comRekName_2_child,
                                    "rekName_2_child" => $rekName_2_child,
                                    "comRekName_3_child" => $comRekName_3_child,
                                    "rekName_3_child" => $rekName_3_child,
                                    "rekName3IDChild" => $rekName3IDChild,
                                    "rekEfisiensi_coa" => "3020010",
                                    "efisensi_child_id" => $efisensi_child_id,
                                    "efisensi_child_nama" => $efisensi_child_nama,
                                    "efisensi_child2_id" => $efisensi_child2_id,
                                    "efisensi_child2_nama" => $efisensi_child2_nama,


                                );

                                if ($cabID == 25) {
                                    // $arrITems[$produk_id] = array(
                                    // "" => $comRekName_3_child,
                                    // "" => $rekName_3_child,
                                    // "" => $rekName3IDChild,
                                    // "" => $comRekName_3_child,
                                    // "" => $rekName_3_child,
                                    // "" => $rekName3IDChild,

                                    // );
                                }
                                else {
                                    unset($arrItems[$produk_id]['comRekName_3_child_coa']);
                                    unset($arrItems[$produk_id]['rekName_3_child_coa']);
                                    unset($arrItems[$produk_id]['rekName3IDChild_coa']);
                                    unset($arrItems[$produk_id]['comRekName_3_child']);
                                    unset($arrItems[$produk_id]['rekName_3_child']);
                                    unset($arrItems[$produk_id]['rekName3IDChild']);
                                    unset($arrItems[$produk_id]['rekEfisiensi_coa']);
                                    unset($arrItems[$produk_id]['efisensi_child_id']);
                                    unset($arrItems[$produk_id]['efisensi_child_nama']);
                                    unset($arrItems[$produk_id]['efisensi_child2_id']);
                                    unset($arrItems[$produk_id]['efisensi_child2_nama']);

                                    unset($itemsTmp["comRekName_3_child_coa"]);
                                    unset($itemsTmp["rekName_3_child_coa"]);
                                    unset($itemsTmp["rekName3IDChild_coa"]);
                                    unset($itemsTmp["comRekName_3_child"]);
                                    unset($itemsTmp["rekName_3_child"]);
                                    unset($itemsTmp["rekName3IDChild"]);
                                    unset($itemsTmp["rekEfisiensi_coa"]);
                                    unset($itemsTmp["efisensi_child_id"]);
                                    unset($itemsTmp["efisensi_child_nama"]);
                                    unset($itemsTmp["efisensi_child2_id"]);
                                    unset($itemsTmp["efisensi_child2_nama"]);
                                }

// arrPrint($arrItems);
//                                 matiHere(__LINE__." || ".__FUNCTION__);
                            }

                            $numAsset++;
                        }

                        //$detilSourceItems

                        //region builder main
                        $main = array(
                            "olehID" => "-100",
                            "olehName" => "sys",
                            "placeID" => $cabID,
                            "placeName" => $cabangData[$cabID],
                            "cabangID" => $cabID,
                            "cabangName" => $cabangData[$cabID],
                            "gudangID" => $gudID,
                            "gudangName" => (isset($branchData[$cabID][$gudID]['gudang_nama']) ? $branchData[$cabID][$gudID]['gudang_nama'] : ""),
                            "jenisTr" => $this->jenisTr,
                            "jenisTrMaster" => $this->jenisTr,
                            "jenisTrTop" => $this->jenisTr . "r",
                            "jenisTrName" => "request depresiasi",
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

                            "pihakMainName_coa" => $pihakMain[$pihakMainID],
                            "pihakMainID_coa" => $pihakMainID,
                            "pihakMainChild_coa" => $pihakMainChild,

                            "rekAkumPenyu_coa" => $rekAkumPenyu_coa,
                            "rekName_1_coa" => $rekName_1_child,
                            "comRekName_2_coa" => $comRekName_2_child,
                            "rekName_2_coa" => $rekName_2_child,
                            "comRekName_3_coa" => $comRekName_3_child,
                            "rekName_3_coa" => $rekName_3_child,
                            "rekName3ID_coa" => $rekName3IDChild,

                            "pihakMainName" => $pihakMain[$pihakMainID],
                            "pihakMainID" => $pihakMainID,
                            "pihakMainChild" => "penyusutan " . $pihakMain[$pihakMainID],

                            "rekAkumPenyu" => "akum penyu " . $pihakMain[$pihakMainID],
                            "rekName_1" => $rekName_1_child,
                            "comRekName_2" => $comRekName_2_child,
                            "rekName_2" => $rekName_2_child,
                            "comRekName_3" => $comRekName_3_child,
                            "rekName_3" => $rekName_3_child,
                            "rekName3ID" => $rekName3IDChild,
                            "rekEfisiensi_coa" => "3020010",
                            "efisensi_child_id" => $efisensi_child_id,
                            "efisensi_child_nama" => $efisensi_child_nama,
                            "efisensi_child2_id" => $efisensi_child2_id,
                            "efisensi_child2_nama" => $efisensi_child2_nama,


                        );
                        if ($cabID == 25) {
                            $main["rekEfisiensi_coa"] = "3020010";
                        }
                        else {
                            /*
                             * diunset biar jurnal gak jalan selain di cabang produksi supaya gerbang tidak tercipta
                             */
                            unset($main["comRekName_3_coa"]);
                            unset($main["rekName_3_coa"]);
                            unset($main["rekName3ID_coa"]);
                            unset($main["comRekName_3"]);
                            unset($main["rekName_3"]);
                            unset($main["rekName3ID"]);
                            unset($main["rekEfisiensi_coa"]);
                            unset($main["efisensi_child_id"]);
                            unset($main["efisensi_child_nama"]);
                            unset($main["efisensi_child2_id"]);
                            unset($main["efisensi_child2_nama"]);

                        }
                        //endregion builder main

                        //region builder items
                        $items = array();
                        foreach ($arrItems as $itsID => $itsData) {
                            foreach ($itemsTmp as $col => $selectedRow) {
                                $items[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
                            }
                        }
//                        ceklIme(__LINE__ . " || builder items");
//                        arrPrintWebs($items);
//                         matiHere();
                        //endregion builder items

                        //region builder tabel in master
                        $tableIn_master = array(
                            "trash" => "0",
                            "jenis_master" => $this->jenisTr,
                            "jenis_top" => $this->jenisTr . "r",
                            "jenis" => $this->jenisTr . "r",
                            "jenis_label" => "request depresiasi",
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
                            "pihakMainName" => $pihakMainID,
                            "pihakMainID" => $pihakMainID,
                        );
                        //endregion builder tabel in master

                        //region builder table in detil
                        $tableIn_detail = array();
                        foreach ($arrItems as $itsID => $itsData) {
                            foreach ($tableIn_detailTmp as $col => $selectedRow) {
                                $tableIn_detail[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
                            }
                        }
//                        arrPrintPink($tableIn_detail);
                        //endregion builder table in detil

                        //region table in master values
                        $tableIn_master_values = array(
                            "gudang" => $gID,
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


                        $sessionData = array(
                            "main" => $main,
                            "items" => $items,
                            "tableIn_master" => $tableIn_master,
                            "tableIn_detail" => $tableIn_detail,
                            "tableIn_master_values" => $tableIn_master_values,
                            "tableIn_detail_values" => $tableIn_detail_values,
                            "receiptDetailFields" => $receiptDetailFields,
                            "receiptSumFields" => $receiptSumFields,
                        );

                        // region membuat element
                        $elementConfigs = isset($this->configUi[$this->jenisTr]["receiptElements"]) ? $this->configUi[$this->jenisTr]["receiptElements"] : array();
                        if (sizeof($elementConfigs) > 0) {
                            foreach ($elementConfigs as $eName => $eSpec) {
                                switch ($eSpec['elementType']) {
                                    case "dataModel":
                                        $amdlName = $eSpec['mdlName'];
                                        $this->load->model("Mdls/" . $amdlName);
                                        $labelSrc = $eSpec['labelSrc'];
                                        $keySrc = $eSpec['key'];
                                        $oo = new $amdlName();
                                        $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : array();
                                        if (sizeof($aFilter) > 0) {
                                            $oo = makeFilter($aFilter, $main, $oo);
                                        }
                                        if (sizeof($autoFilter) > 0) {
                                            $keyFilter = $autoFilter['key'];
                                            $keySrcVAl = $main[$keyFilter];
                                            $mdlSrc = $autoFilter['srcRef']['mdl'];
                                            $srcField = $autoFilter['srcRef']['srcField'];
                                            $srcFieldKey = $autoFilter['srcRef']['filter'];
                                            $this->load->model("Mdls/" . $mdlSrc);
                                            $src = new $mdlSrc();
                                            $srcData = $src->lookupByID($main[$srcFieldKey])->result();
                                            $valSrc = $srcData[0]->$srcField;
                                            $pairSrc = $autoFilter['pairKey'];
                                            if (strlen($valSrc) > 2) {
                                                $validate = "true";
                                            }
                                            else {
                                                $validate = "false";
                                            }
                                            $pairTargetKey = $pairSrc['methode'][$keySrcVAl][$validate];
                                            $oo->setAddKey($pairTargetKey);
                                        }
                                        $tmpo = $oo->lookupAll()->result();
                                        if (sizeof($tmpo) == 1) {
                                            $usedKey = $eSpec['key'];
                                            $defValueSrc = $tmpo[0]->$usedKey;
                                            $configUiJenis = $this->configUi[$this->jenisTr];
                                            $sessionData = heFetchElement_modul_ns($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc, $this->configUi[$this->jenisTr], array(), $sessionData);
                                        }
                                        break;
                                    case "dataField":
                                        break;
                                }
                            }
                        }
                        // endregion membuat element

                        if (sizeof($arrItems) > 0) {
                            $gate['items'] = $arrItems;
                            $gate['main'] = $main;
                            $tCodeTargetJenisTransaksi = $jenisTrTarget = isset($this->configUi[$this->jenisTr]["steps"][1]["target"]) ? $this->configUi[$this->jenisTr]["steps"][1]["target"] : NULL;


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
                                        $mdlName = str_replace($mdlName, $sessionData["main"][$mdlName], $mdlName);
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
                                                $key = str_replace($key, $sessionData["main"][$key], $key);
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
//                                            if (!isset($subParams['static']["transaksi_id"])) {
//
//                                            }
                                            $subParams['static']["fulldate"] = date("Y-m-d");
                                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                            $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh SYSTEM";
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
//                                            cekbiru("gotparams dari pre-proc $comName");
//                                            arrprint($gotParams);
                                            if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                                foreach ($gotParams as $gateName => $gSpec) {
                                                    if (isset($main)) {
                                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                            foreach ($gSpec as $key => $val) {
                                                                $sessionData["main"][$key] = $val;
                                                            }
                                                        }
                                                    }
                                                    //==inject gotParams to child gate
                                                    if (isset($main)) {
                                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                            foreach ($gSpec as $key => $val) {
                                                                $sessionData["main"][$key] = $val;
                                                            }
                                                        }
                                                    }
                                                    //cekMerah("REBUILDING VALUES..");
                                                    if (sizeof($itemNumLabels) > 0) {
                                                        //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                        foreach ($itemNumLabels as $key => $label) {
                                                            //cekHere("$id === $key => $label");
                                                            if (isset($sessionData["main"][$key])) {
                                                                $sessionData["main"]['sub_' . $key] = ($main['jml'] * $sessionData["main"][$key]);
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
//                                fillValues($this->jenisTr, 1, 1);
                                $sessionData = fillValues_he_value_builder_ns($this->jenisTr, 1, 1, $this->configCore[$this->jenisTr], $this->configUi[$this->jenisTr], $this->configValues[$this->jenisTr], 0, $sessionData);
                            }
                            else {
                                echo("no processor defined. skipping preprocessor..<br>");
                            }
                            //endregion

                            //region pre-processors (item)
                            if (isset($this->configCore[$this->jenisTr]['preProcessor'][1]['detail'])) {
                                $iterator = isset($this->configCore[$this->jenisTr]['preProcessor'][1]['detail']) ? $this->configCore[$this->jenisTr]['preProcessor'][1]['detail'] : array();
                                $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields']) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'] : array();
//                                echo "ITEM NUM LABELS";

                                if (sizeof($iterator) > 0) {
                                    foreach ($iterator as $cCtr => $tComSpec) {
                                        $comName = $tComSpec['comName'];
                                        $srcGateName = $tComSpec['srcGateName'];
                                        $srcRawGateName = $tComSpec['srcRawGateName'];
//                                        echo "sub-preproc: $comName, initializing values <br>";
                                        foreach ($sessionData[$srcGateName] as $xid => $dSpec) {
                                            $tmpOutParams[$cCtr] = array();
                                            $id = $xid;
                                            $subParams = array();
                                            if (isset($tComSpec['static'])) {
                                                foreach ($tComSpec['static'] as $key => $value) {
                                                    $realValue = makeValue($value, $sessionData[$srcGateName][$id], $sessionData[$srcGateName][$id], 0);
                                                    $subParams['static'][$key] = $realValue;
                                                }
//                                                if (!isset($subParams['static']["transaksi_id"])) {
//
//                                                }
                                                $subParams['static']["fulldate"] = date("Y-m-d");
                                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                                $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh SYSTEM";
                                            }
//                                            cekLime(":: cetak preprocc... $comName :: $srcGateName ::");
//                                            arrPrint($subParams);
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
//                                                    cekmerah("gotparams dari pre-proc $comName");
//                                                    arrprint($gotParams);
                                                    if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                                        foreach ($gotParams as $gateName => $paramSpec) {
//                                                            cekBiru(":: getParams inject ke $gateName ::");
                                                            if (!isset($sessionData[$gateName])) {
                                                                $sessionData[$gateName] = array();
                                                            }
                                                            else {

                                                            }

                                                            foreach ($paramSpec as $id => $gSpec) {
                                                                if (!isset($sessionData[$gateName][$id])) {
                                                                    $sessionData[$gateName][$id] = array();
                                                                }
                                                                if (isset($sessionData[$gateName][$id])) {
                                                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                                        foreach ($gSpec as $key => $val) {
//                                                                            cekHere(":: injecte ke $gateName, ::: $key diisi dengan $val");
                                                                            $sessionData[$gateName][$id][$key] = $val;
                                                                        }
                                                                    }
                                                                }
                                                                //==inject gotParams to child gate
//                                                                cekHitam("srcGateName = $srcGateName :: " . __LINE__);
                                                                if (isset($sessionData[$srcGateName][$id])) {
                                                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                                        foreach ($gSpec as $key => $val) {
                                                                            $sessionData[$srcGateName][$id][$key] = $val;
                                                                        }
                                                                    }
                                                                }

                                                                //cekMerah("REBUILDING VALUES..");
                                                                if (sizeof($itemNumLabels) > 0) {
                                                                    //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                                    foreach ($itemNumLabels as $key => $label) {
                                                                        if (isset($sessionData[$gateName][$id][$key])) {
                                                                            $sessionData[$gateName][$id]['sub_' . $key] = ($sessionData[$gateName][$id]['jml'] * $sessionData[$gateName][$id][$key]);
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
//                                fillValues($this->jenisTr, 1, 1);
                                $sessionData = fillValues_he_value_builder_ns($this->jenisTr, 1, 1, $this->configCore[$this->jenisTr], $this->configUi[$this->jenisTr], $this->configValues[$this->jenisTr], 0, $sessionData);

                            }
                            else {
                                echo("no processor defined. skipping preprocessor..<br>");
                            }
                            //endregion


                            $this->midValidate($sessionData);
                            $this->unionValidate($sessionData);
                            //===finalisasi sebelum masuk tabel beneran


                            //===isinya ada pembentukan nomor nota dll
                            //region penomoran receipt
                            $this->load->model("CustomCounter");
                            $cn = new CustomCounter("transaksi");
                            $cn->setType("transaksi");
                            $cn->setModul($modul_transaksi);
                            $cn->setStepCode($tCodeTargetJenisTransaksi);

//                            matiHere("modul_transaksi: $modul_transaksi || tCodeTargetJenisTransaksi: $tCodeTargetJenisTransaksi");
//arrPrint($this->configCore[$this->jenisTr]);
//                            matiHere();
                            $counterForNumber = array($this->configCore[$this->jenisTr]['formatNota']);
                            if (!in_array($counterForNumber[0], $this->configCore[$this->jenisTr]['counters'])) {
                                die("Used number should be registered in 'counters' config as well");
                            }
                            echo "<div style='background:#ff7766;'>";
                            foreach ($counterForNumber as $i => $cRawParams) {
                                $cParams = explode("|", $cRawParams);
                                $cValues = array();
                                foreach ($cParams as $param) {
                                    $cValues[$i][$param] = $sessionData["main"][$param];
                                }
                                $cRawValues = implode("|", $cValues[$i]);
                                $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);
                            }
                            echo "</div style='background:#ff7766;'>";

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
                            $cn->setModul($modul_transaksi);
                            $cn->setStepCode($tCodeTargetJenisTransaksi);
                            $configCustomParams = $this->configCore[$this->jenisTr]['counters'];
                            $configCustomParams[] = "stepCode";

                            if (sizeof($configCustomParams) > 0) {
                                $cContent = array();
                                foreach ($configCustomParams as $i => $cRawParams) {
                                    $cParams = explode("|", $cRawParams);
                                    $cValues = array();
                                    foreach ($cParams as $param) {
                                        $cValues[$i][$param] = $sessionData["main"][$param];
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
                                $sessionData["tableIn_master"][$key] = $val;
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
                            foreach ($sessionData["tableIn_detail"] as $id => $dSpec) {
                                foreach ($addSubValues as $key => $val) {
                                    $sessionData["tableIn_detail"][$id][$key] = $val;
                                }
                            }
                            //endregion

                            //region ----------write transaksi, transaksi_data, main_fields, main_values, main_applets, etc
                            if (sizeof($sessionData["tableIn_master"]) > 0) {
                                $sessionData["tableIn_master"]['status_4'] = 11;
                                $sessionData["tableIn_master"]['trash_4'] = 0;

                                $tr = new MdlTransaksi();
                                $tr->addFilter("transaksi.cabang_id='" . $sessionData["tableIn_master"]['cabang_id'] . "'");
                                $insertID = $tr->writeMainEntries($sessionData["tableIn_master"]);
                                showLast_query("hijau");
                                $mongoList['main'][] = $insertID;
                                $epID = $tr->writeMainEntries_entryPoint($insertID, $insertID, $sessionData["tableIn_master"]);
                                $mongoList['main'][] = $epID;

                                $insertNum = $sessionData["tableIn_master"]['nomer'];
                                $sessionData["main"]['nomer'] = $insertNum;
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
                                    $sessionData["main"][$key] = $val;
                                    foreach ($arrInjectorsTarget as $target) {
                                        foreach ($sessionData["items"] as $xis => $iSpec) {
                                            $id = isset($iSpec['id']) && $iSpec['id'] > 0 ? $iSpec['id'] : $xis;
                                            if (isset($sessionData["items"][$id])) {
                                                $sessionData["items"][$id][$key] = $val;
                                            }
                                        }
                                        foreach ($sessionData[$target] as $xis => $iSpec) {
                                            $id = isset($iSpec['id']) && $iSpec['id'] > 0 ? $iSpec['id'] : $xis;
                                            $sessionData[$target][$id][$key] = $val;
                                        }
                                    }
                                }

                                //===signature
                                $dwsign = $tr->writeSignature($insertID, array(
                                    "nomer" => $sessionData["main"]['nomer'],
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
                                    "nomer_top" => $sessionData["main"]['nomer'],
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
                                    "nomer_top" => $sessionData["main"]['nomer'],
                                    "nomers_prev" => "",
                                    "nomers_prev_intext" => "",
                                    "jenises_prev" => "",
                                    "jenises_prev_intext" => "",
                                    "ids_his" => $idHis_blob,
                                    "ids_his_intext" => $idHis_intext,
                                );
                                foreach ($addValues as $key => $val) {
                                    $sessionData["tableIn_master"][$key] = $val;
                                }

                                $injectors_items = array(
                                    "transaksi_id" => $insertID,
                                    "transaksi_no" => $tmpNomorNota,
                                    "nomer" => $tmpNomorNota,
                                );
                            }
                            if (sizeof($sessionData["tableIn_master_values"]) > 0) {
                                if (isset($this->configCore[$this->jenisTr]['tableIn']['mainValues'])) {
                                    $inserMainValues = array();
                                    foreach ($this->configCore[$this->jenisTr]['tableIn']['mainValues'] as $key => $src) {
                                        if (isset($sessionData["tableIn_master_values"][$key])) {
                                            $dd = $tr->writeMainValues($insertID, array(
                                                "key" => $key,
                                                "value" => $sessionData["tableIn_master_values"][$key],
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
                            if (sizeof($sessionData["main_add_values"]) > 0) {
                                $inserMainValues = array();
                                foreach ($sessionData["main_add_values"] as $key => $val) {
                                    $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                                    $inserMainValues[] = $dd;
                                    $mongoList['mainValues'][] = $dd;
                                }
                                if (sizeof($inserMainValues) > 0) {
                                    $arrBlob = blobEncode($inserMainValues);
                                    $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                                }

//                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                            }
                            if (sizeof($sessionData["main_inputs"]) > 0) {
                                foreach ($sessionData["main_inputs"] as $key => $val) {
                                    $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                                    $inserMainValues[] = $dd;
                                    $mongoList['mainValues'][] = $dd;
                                }
                                if (sizeof($inserMainValues) > 0) {
                                    $arrBlob = blobEncode($inserMainValues);
                                    $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                                }
//                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                            }
                            if (sizeof($sessionData["main_add_fields"]) > 0) {
                                foreach ($sessionData["main_add_fields"] as $key => $val) {
                                    $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
                                }
//                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                            }
                            if (sizeof($sessionData["main_elements"]) > 0) {
                                foreach ($sessionData["main_elements"] as $elName => $aSpec) {
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
                            if (sizeof($sessionData["tableIn_detail"]) > 0) {
                                $insertIDs = array();
                                $insertDeIDs = array();
                                foreach ($sessionData["tableIn_detail"] as $dSpec) {
                                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                                    showLast_query("kuning");
                                    $insertIDs[] = $insertDetailID;
                                    $insertDeIDs[$insertID][] = $insertDetailID;
                                    $mongoList['detail'][] = $insertDetailID;
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
                            if (sizeof($sessionData["tableIn_detail2"]) > 0) {
                                $insertIDs = array();
                                foreach ($sessionData["tableIn_detail2"] as $dSpec) {
                                    $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                                    $mongoList['detail'] = $insertIDs;
                                    if ($epID != 999) {
                                        $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                                        $mongoList['detail'] = $insertIDs;
                                    }
//                                cekUngu($this->db->last_query());
                                }
                            }
                            if (sizeof($sessionData["tableIn_detail2_sum"]) > 0) {
                                $insertIDs = array();
                                foreach ($sessionData["tableIn_detail2_sum"] as $dSpec) {
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
                            if (sizeof($sessionData["tableIn_detail_rsltItems"]) > 0) {
                                $insertIDs = array();
                                foreach ($sessionData["tableIn_detail_rsltItems"] as $dSpec) {
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
                            if (sizeof($sessionData["tableIn_detail_values"]) > 0) {
                                foreach ($sessionData["tableIn_detail_values"] as $pID => $dSpec) {
                                    if (isset($this->configCore[$this->jenisTr]['tableIn']['detailValues'])) {
                                        $insertIDs = array();
                                        foreach ($this->configCore[$this->jenisTr]['tableIn']['detailValues'] as $key => $src) {
                                            if (isset($sessionData["tableIn_detail"][$pID])) {
                                                $dd = $tr->writeDetailValues($insertID, array(
                                                    "produk_jenis" => $sessionData["tableIn_detail"][$pID]['produk_jenis'],
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
                            if (sizeof($sessionData["tableIn_detail_values2_sum"]) > 0) {
                                foreach ($sessionData["tableIn_detail_values2_sum"] as $pID => $dSpec) {
                                    if (isset($this->configCore[$this->jenisTr]['tableIn']['detailValues2_sum'])) {
                                        foreach ($this->configCore[$this->jenisTr]['tableIn']['detailValues2_sum'] as $key => $src) {
                                            $dd = $tr->writeDetailValues($insertID, array(
                                                "produk_jenis" => $sessionData["tableIn_detail2_sum"][$pID]['produk_jenis'],
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
//                            arrPrintWebs($main);
//                            arrPrintWebs($items);
                            $baseRegistries = array(
                                'main' => sizeof($sessionData["main"]) > 0 ? $sessionData["main"] : array(),
                                'items' => sizeof($sessionData["items"]) > 0 ? $sessionData["items"] : array(),
                                'items2' => sizeof($sessionData["items2"]) > 0 ? $sessionData["items2"] : array(),
                                'items2_sum' => sizeof($sessionData["items2_sum"]) > 0 ? $sessionData["items2_sum"] : array(),
                                'items3' => sizeof($sessionData["items3"]) > 0 ? $sessionData["items3"] : array(),
                                'items3_sum' => sizeof($sessionData["items3_sum"]) > 0 ? $sessionData["items3_sum"] : array(),
                                'rsltItems' => sizeof($sessionData["rsltItems"]) > 0 ? $sessionData["rsltItems"] : array(),
                                'rsltItems2' => sizeof($sessionData["rsltItems2"]) > 0 ? $sessionData["rsltItems2"] : array(),
                                'tableIn_master' => sizeof($sessionData["tableIn_master"]) > 0 ? $sessionData["tableIn_master"] : array(),
                                'tableIn_detail' => sizeof($sessionData["tableIn_detail"]) > 0 ? $sessionData["tableIn_detail"] : array(),
                                'tableIn_detail2_sum' => sizeof($sessionData["tableIn_detail2_sum"]) > 0 ? $sessionData["tableIn_detail2_sum"] : array(),
                                'tableIn_detail_rsltItems' => sizeof($sessionData["tableIn_detail_rsltItems"]) > 0 ? $sessionData["tableIn_detail_rsltItems"] : array(),
                                'tableIn_detail_rsltItems2' => sizeof($sessionData["tableIn_detail_rsltItems2"]) > 0 ? $sessionData["tableIn_detail_rsltItems2"] : array(),
                                'tableIn_master_values' => sizeof($sessionData["tableIn_master_values"]) > 0 ? $sessionData["tableIn_master_values"] : array(),
                                'tableIn_detail_values' => sizeof($sessionData["tableIn_detail_values"]) > 0 ? $sessionData["tableIn_detail_values"] : array(),
                                'tableIn_detail_values_rsltItems' => sizeof($sessionData["tableIn_detail_values_rsltItems"]) > 0 ? $sessionData["tableIn_detail_values_rsltItems"] : array(),
                                'tableIn_detail_values_rsltItems2' => sizeof($sessionData["tableIn_detail_values_rsltItems2"]) > 0 ? $sessionData["tableIn_detail_values_rsltItems2"] : array(),
                                'tableIn_detail_values2_sum' => sizeof($sessionData["tableIn_detail_values2_sum"]) > 0 ? $sessionData["tableIn_detail_values2_sum"] : array(),
                                'main_add_values' => sizeof($sessionData["main_add_values"]) > 0 ? $sessionData["main_add_values"] : array(),
                                'main_add_fields' => sizeof($sessionData["main_add_fields"]) > 0 ? $sessionData["main_add_fields"] : array(),
                                'main_elements' => sizeof($sessionData["main_elements"]) > 0 ? $sessionData["main_elements"] : array(),
                                'main_inputs' => sizeof($sessionData["main_inputs"]) > 0 ? $sessionData["main_inputs"] : array(),
                                'main_inputs_orig' => sizeof($sessionData["main_inputs"]) > 0 ? $sessionData["main_inputs"] : array(),
                                "receiptDetailFields" => isset($this->configLayout[$this->jenisTr]['receiptDetailFields'][1]) ? $this->configLayout[$this->jenisTr]['receiptDetailFields'][1] : array(),
                                "receiptSumFields" => isset($this->configLayout[$this->jenisTr]['receiptSumFields'][1]) ? $this->configLayout[$this->jenisTr]['receiptSumFields'][1] : array(),
                                "receiptDetailFields2" => isset($this->configLayout[$this->jenisTr]['receiptDetailFields2'][1]) ? $this->configLayout[$this->jenisTr]['receiptDetailFields2'][1] : array(),
                                "receiptSumFields2" => isset($this->configLayout[$this->jenisTr]['receiptSumFields2'][1]) ? $this->configLayout[$this->jenisTr]['receiptSumFields2'][1] : array(),
                            );

                            //===
//                            die("sini");
//                            $doWriteReg = $tr->writeRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
                            $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));

                            $mongRegID[] = $doWriteReg;
//                            arrPrint($mongRegID);
//                            matiHEre(floatval($subtotal));
//                            arrPrint($baseRegistries);

                            //endregion

//                            validateAllBalances($cabangData[$dataTmp->cabang_id]);
                            //region processing sub-post-processors, always items
                            $iterator = isset($this->configCore[$this->jenisTr]['postProcessor'][$jenisTrTarget]['detail']) ? $this->configCore[$this->jenisTr]['postProcessor'][$jenisTrTarget]['detail'] : array();
                            arrprint($iterator);

                            if (sizeof($iterator) > 0) {
                                foreach ($iterator as $cCtr => $tComSpec) {
                                    $comName = $tComSpec['comName'];
                                    $srcGateName = $tComSpec['srcGateName'];
                                    $srcRawGateName = $tComSpec['srcRawGateName'];
                                    $tmpOutParams[$cCtr] = array();
                                    foreach ($sessionData[$srcGateName] as $cnt => $dSpec) {
                                        foreach ($injectors_items as $ikey => $ival) {
                                            $sessionData[$srcGateName][$cnt][$ikey] = $ival;
                                        }

                                        $subParams = array();
                                        if (isset($tComSpec['loop'])) {
                                            foreach ($tComSpec['loop'] as $key => $value) {
                                                $realValue = makeValue($value, $sessionData[$srcGateName][$cnt], $sessionData[$srcGateName][$cnt], 0);
                                                $subParams['loop'][$key] = $realValue;
                                            }
                                        }
                                        if (isset($tComSpec['static'])) {
                                            foreach ($tComSpec['static'] as $key => $value) {
                                                $realValue = makeValue($value, $sessionData[$srcGateName][$cnt], $sessionData[$srcGateName][$cnt], 0);
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
                                                $jenis = $sessionData['main']['jenis'];
                                                foreach ($paramForceFillers[$comName] as $k => $v) {
                                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                                }
                                            }
                                            $subParams['static']["fulldate"] = date("Y-m-d");
                                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                            $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh SYSTEM";
                                        }

                                        if (sizeof($subParams) > 0) {
                                            $tmpOutParams[$cCtr][] = $subParams;
                                        }
//                                        echo "<script>top.writeProgress('" . $subParams['static']['name'] . " " . $subParams['static']['extern_nama'] . " " . $subParams['static']['nama'] . "');</script>";
                                    }
                                }

//                                arrPrintWebs($iterator);

                                foreach ($iterator as $cCtr => $tComSpec) {
                                    $comName = $tComSpec['comName'];
                                    $srcGateName = $tComSpec['srcGateName'];
                                    $srcRawGateName = $tComSpec['srcRawGateName'];
//                                    echo "sub-postProcessor: $comName, sending values <br>";
//                                    echo "<script>top.writeProgress('SENDING SUB-PROCESSORS ($comName)...', 'head');</script>";
                                    $mdlName = "Com" . ucfirst($comName);
                                    $this->load->model("Coms/" . $mdlName);
                                    $m = new $mdlName();
                                    arrprint($tmpOutParams[$cCtr]);
//                                    matiHEre(__LINE__);
                                    $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                    $m->exec() or die("Gagal saat berusaha exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
//                                    matiHEre(__LINE__);
                                    cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                                }
                            }
//                            matiHEre(__LINE__);
                            //endregion


                            // endregion

                            //region writelog
                            $this->load->model("Mdls/" . "MdlActivityLog");
                            $hTmp = new MdlActivityLog();
                            $tmpHData = array(
                                "title" => $sessionData["main"]['jenisTrName'],
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
                                "url" => "",
                            );
                            $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));

//                            $a->updateData("id")

                            foreach ($arrItems as $itsID => $itsData) {
                                $cekId = $d->updateData(
                                    array(
                                        "extern_id" => $itsID,
                                        "cabang_id" => $itsData['cabangID'],
                                    ),
                                    array(
                                        "dtime_last_depresiasi" => date("Y-m-d H:i:s"),
                                        "last_tr_id" => $insertID,
                                    )
                                ) or die("Failed to update tr next-state!");

//                                cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                            }

//                            arrPrint($cekId);

                            //endregion
                        }
                        else {
//                            cekOrange('gak bikin transaksi mungkin nilai udh abiss wkwkwk');
                        }

                    }
                }
            }

            $tableView .= "</tbody>";
            $tableView .= "</table>";
        }
//matiHere(__LINE__);
        cekOrange("======================================= batas bawah ASSET =======================================");
        //endregion batas aset

        cekHere($tableView);
//        arrPrintCyan($sessionData);

//        mati_disini("============= BELUM COMMIT COA===============" . __LINE__);


        if (isset($_GET['debug'])) {
            mati_disini("============= BELUM COMMIT SEWA COA DEBUG ===============");
        }

        $this->db->trans_complete() or mati_disini("Gagal saat berusaha  commit transaction!");

        cekHijau("======================================= BERHASIL COMMIT =======================================");

        //region cloning mongoDB
        $mongoList = array();
        $mongRegID = array();
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
                        $mong->addData($transksi_main);
                    }
                }
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
                if (sizeof($tmpTrm) > 0) {
                    $mong->setTableName("transaksi_registry");
                    foreach ($tmpTrm as $tmpMain) {
                        $transksi_main = json_decode(json_encode($tmpMain), true);
                        $mong->addData($transksi_main);
                    }
                }
            }
        }
        //endregion

        if (isset($_GET['force'])) {
            if (isset($_GET['byexternid'])) {
                writeLog(__FUNCTION__, "force depresiasi", "depresiasi", $_GET['byexternid'], "SYSTEM");
            }
            else {
                writeLog(__FUNCTION__, "force depresiasi", "depresiasi", "all", "SYSTEM");
            }
        }

        echo "jika halaman tidak menutup dengan otomatis, anda boleh klik close";

    }

    // hanya untuk nyusulin yang ketinggalan/tidak terbit request depresiasinya
    public function customRun()
    {

        /*
         * list data depresiasi
         * grouping by cabang
         * generate per item jadi transaksi untuk di otorisasi
         * id aset,nama aset sebagai rekening
         * untuk solo pembantu efisiensi ambil dari pair produkprebiaya berdasarkan coa untuk dapat id dan labelnya
         * dibwa ke main dan items untuk pembentu level 2 extern2_id
         */
        $_GET["force"] = 1;
        // tool untuk menyusulkan depresiasi dari bulan januari - oktober 2025
        // dibawah ini adalah daftar asset_id yang tidak dijalankan karena belum disetting waktu/bulan itu atau sudah didepresiasi
        // url tambah run=1 (1=bulan yang didepresiasi)1,2,3...dst 10
        $excepRun = isset($_GET["run"]) ? $_GET["run"] : 0;
        $produkException = array(// berisi produk_id, atau asset_id
            "1" => array(88, 89, 90, 90, 90, 91, 92, 93, 94, 95, 96, 97, 98, 99),
            "2" => array(88, 89, 90, 90, 90, 91, 92, 93, 94, 95, 96, 97, 98, 99, 85),
            "3" => array(92, 93, 94, 95, 96, 97, 98, 99),
            "4" => array(94, 95, 96, 97, 98, 99, 85),
            "5" => array(97, 98, 99),
            "6" => array(97, 98, 99, 88, 89, 91),
            "7" => array(97, 98, 99),
            "8" => array(97, 98, 99, 86, 87, 93),
            "9" => array(98, 99),
            "10" => array(87, 86, 89, 90, 91, 94, 88, 95, 93, 85),
        );
        $produkExceptionLabel = array(// berisi produk_id, atau asset_id
            "1" => "depresiasi susulan bulan januari 2025",
            "2" => "depresiasi susulan bulan februari 2025",
            "3" => "depresiasi susulan bulan maret 2025",
            "4" => "depresiasi susulan bulan april 2025",
            "5" => "depresiasi susulan bulan mei 2025",
            "6" => "depresiasi susulan bulan juni 2025",
            "7" => "depresiasi susulan bulan juli 2025",
            "8" => "depresiasi susulan bulan agustus 2025",
            "9" => "depresiasi susulan bulan september 2025",
            "10" => "depresiasi susulan bulan oktober 2025",
        );
        $arrException = isset($produkException[$excepRun]) ? $produkException[$excepRun] : array();


        echo "<title>AUTO-DEPRE</title>";

        cekHijau('byrekmainid = select dengan id rekening');
        cekHijau('byexternid = select dengan extern/id produk / asset');
        cekHijau('force = paksa depresiasi tanpa lihat tanggal depre');
        cekHijau('exe = harus di pakai untuk melakukan execution transaksi');
        $modul_transaksi = $this->modul;
        $this->jenisTr = "8787";//ditembak untuk auto generate
        $this->tableInConfig = isset($this->configUi[$this->jenisTr]['tableIn']) ? $this->configUi[$this->jenisTr]['tableIn'] : array();
        $this->tableInConfig_static = isset($this->configUi[$this->jenisTr]['tableIn_static']) ? $this->configUi[$this->jenisTr]['tableIn_static'] : array();

        //list rekening dari config HeAccounting
        $accountChilds = $this->config->item("accountChilds");

        //region batas aset
        $this->load->model("Mdls/MdlAccounts");
        $this->load->model("Mdls/MdlSetupDepresiasiAssetsProduction");
        $this->load->model("Mdls/MdlProdukRakitanPreBiaya");//untuk baca biaya produksi
        $this->load->model("Mdls/MdlDtaBiayaProduksi");//untuk baca biaya produksi


        $d = new MdlSetupDepresiasiAssetsProduction(); //
        $f = new MdlFolderAset(); //aset wujud dan sewa sama2 pakai ini
        $a = new MdlAsetDetail(); //aset wujud dan sewa sudah termasuk
        $pr = new MdlProdukRakitanPreBiaya();

        $masterPrebiaya = $pr->lookUpAll()->result();
        $dtaProduksi = array();
        $dataPrebiaya = array();
        if (sizeof($masterPrebiaya) > 0) {
            $dta_p = new MdlDtaBiayaProduksi();
            $dtaBiayaproduksi = $dta_p->lookUpAll()->result();

            foreach ($dtaBiayaproduksi as $tempBiayaProduksi) {
                $dtaProduksi[$tempBiayaProduksi->id] = $tempBiayaProduksi->nama;
            }
            foreach ($masterPrebiaya as $preBiayaTmp) {
                // arrPrint($preBiayaTmp);
                $dataPrebiaya[$preBiayaTmp->coa_code] = array(
                    "id" => $preBiayaTmp->id,
                    "nama" => $preBiayaTmp->nama,
                    "coa_code_2" => $preBiayaTmp->coa_code_2,//efisiensi
                );
            }
        }
//         arrPrint($dataPrebiaya);

        if (isset($_GET["byexternid"])) {
            $d->addFilter("extern_id='" . $_GET["byexternid"] . "'");
        }
        $a->addFilter("jenis=item");

        $folders = $f->lookupAll()->result();
        showLast_query("ungu");

        if (sizeof($arrException) > 0) {
            $this->db->where_not_in('id', $arrException);
        }
        $detailItems = $a->lookupAll()->result();
        showLast_query("kuning");
        cekKuning(count($detailItems));

        $arrCoaList = $this->MdlAccounts->get_userlist();


        $arrCoa = array();
        $arrCoaExtern = array();
        if (!empty($arrCoaList)) {
            foreach ($arrCoaList as $kk => $cc) {
                $arrCoa[$cc->head_code] = $cc->head_name;
                $arrCoaExtern[$cc->head_code] = array(
                    "extern_id" => $cc->extern_id,
                    "extern_nama" => $cc->head_name,
                );
            }
        }


        $arrCoa_P_HeadCode = array();
        if (!empty($arrCoaList)) {
            foreach ($arrCoaList as $kk => $cc) {
                $arrCoa_P_HeadCode[$cc->head_code] = $cc->p_head_code;
            }
        }


        $data = $d->lookupAll()->result();
        showLast_query("biru");
//        mati_disini(__LINE__);


        //region cek pre value locker value
        $this->load->model("Mdls/MdlLockerValue");
        $l = new MdlLockerValue();
        $l->addFilter("jenis=aktiva");
        $l->addFilter("state=active");
        $lockerValue = $l->lookupAll()->result();
        showLast_query("biru");
        $lockerSource = array();
        if (sizeof($lockerValue) > 0) {
            foreach ($lockerValue as $lockerTmp) {
                $lockerSource[$lockerTmp->cabang_id][$lockerTmp->produk_id] = array(
                    "nilai" => $lockerTmp->nilai,
                    "gudang_id" => $lockerTmp->gudang_id,
                );
            }
        }
        //endregion

        $this->db->trans_start();
        //tabahin detector tanggal sini broo untuk dijalankan tiap tanggal
        //-----belum disetup ya!!

        //tambahin auto rekekning (baca dari kolom folders);
        $pihakMain = array();
        $pihakMain_coa = array();
        foreach ($folders as $foldersTmp) {
            $pihakMain[$foldersTmp->id] = $foldersTmp->nama;
            $pihakMain_coa[$foldersTmp->coa_code] = $foldersTmp->coa_code;
        }

        //tambahin auto rekekning (baca dari kolom folders);
        $this->load->model("Mdls/MdlProdukRakitanPreBiaya");
        $bmp = new MdlProdukRakitanPreBiaya();
        $biayaMethodProduksi = $bmp->lookupAll()->result();
        // matiHEre($this->db->last_query());
        // matiHere();
        $autoRekNameProduksi_2 = array();
        $autoRekNameProduksi_lv2 = array();
        foreach ($biayaMethodProduksi as $biayaMethodProduksiTmp) {
//            $autoRekNameProduksi_2[$biayaMethodProduksiTmp->id] = $biayaMethodProduksiTmp->nama;
//            $autoRekNameProduksi_lv2[$biayaMethodProduksiTmp->nama] = "MdlRekeningPembantuBiayaKomposisiProduksi";
            $autoRekNameProduksi_2[$biayaMethodProduksiTmp->coa_code] = $biayaMethodProduksiTmp->nama;
            $autoRekNameProduksi_lv2[$biayaMethodProduksiTmp->coa_code] = "MdlRekeningPembantuBiayaKomposisiProduksi";
        }

        //tambahin auto rekekning (baca dari kolom folders);
        $this->load->model("Mdls/MdlRekeningBesar");
        $rb = new MdlRekeningBesar();
        $rekeningBesar = $rb->lookupAll()->result();
        $autoRekNameSales_2 = array();
        $autoRekNameSales_lv2 = array();
        foreach ($rekeningBesar as $rekeningBesarTmp) {
//            $autoRekNameSales_2[$rekeningBesarTmp->id] = $rekeningBesarTmp->nama;
//            $autoRekNameSales_lv2[$rekeningBesarTmp->nama] = $rekeningBesarTmp->mdl_name;
            $autoRekNameSales_2[$rekeningBesarTmp->coa_code] = $rekeningBesarTmp->coa_code;
            $autoRekNameSales_lv2[$rekeningBesarTmp->coa_code] = $rekeningBesarTmp->mdl_name;
        }

        //tambahin auto rekekning (baca dari kolom folders);
        $autoRekNameSales_3 = array();
        if (sizeof($autoRekNameSales_lv2) > 0) {
            foreach ($autoRekNameSales_lv2 as $name => $mdlName) {
                $this->load->model("Mdls/$mdlName");
                $prpb = new $mdlName();
                $produkRakitanPreBiaya = $prpb->lookupAll()->result();
                foreach ($produkRakitanPreBiaya as $produkRakitanPreBiayaTmp) {
                    $autoRekNameSales_3[$name][$produkRakitanPreBiayaTmp->id] = $produkRakitanPreBiayaTmp->nama;
                }
            }
        }

        //Coa Account Alias
        $arrCoaAccount = fetchAccountStructureAlias();


        $staticAccountComRekening = array(
            // //rombongan aktiva coa lama
            // "01050100005"  => "RekeningPembantuAkumPenyusutanKendaraan",
            // "01050100004"  => "RekeningPembantuAkumPenyusutanPeralatanKantor",
            // "01050100002"  => "RekeningPembantuAkumPenyusutanMesinProduksi",
            // "01050100006"  => "RekeningPembantuAkumPenyusutanMesin",
            // "01050100003"  => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
            // "01050100001"  => "RekeningPembantuAkumPenyusutanBangunan",
            //
            // //rombongan biaya
            // "061100001"  => "RekeningPembantuAkumPenyusutanKendaraan",
            // "061100002"  => "RekeningPembantuAkumPenyusutanPeralatanKantor",
            // "061100005"  => "RekeningPembantuAkumPenyusutanMesinProduksi",
            // "061100004"  => "RekeningPembantuAkumPenyusutanMesin",
            // "061100003"  => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
            // "061100006"  => "RekeningPembantuAkumPenyusutanBangunan",

            //rombongan aktiva
            "1020010010" => "RekeningPembantuAkumPenyusutanKendaraan",
            "1020020010" => "RekeningPembantuAkumPenyusutanPeralatanKantor",
            "1020040010" => "RekeningPembantuAkumPenyusutanMesinProduksi",
            "1020030010" => "RekeningPembantuAkumPenyusutanMesin",
            "1020041010" => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
            "1020050010" => "RekeningPembantuAkumPenyusutanBangunan",

            //rombongan biaya
            "6040010" => "RekeningPembantuAkumPenyusutanKendaraan",
            "6040020" => "RekeningPembantuAkumPenyusutanPeralatanKantor",
            "6040050" => "RekeningPembantuAkumPenyusutanMesinProduksi",
            "6040040" => "RekeningPembantuAkumPenyusutanMesin",
            "6040030" => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
            "6040060" => "RekeningPembantuAkumPenyusutanBangunan",
        );

        //pilih menggunakan asset account
        $staticAssetsAccount = array(
            "1020010010" => "1020010020", //Akum Penyu Kendaraan
            "1020020010" => "1020020020", //Akum Penyu Peralatan Kantor
            "1020040010" => "1020040020", //Akum Penyu Mesin Produksi
            "1020030010" => "1020030020", //Akum Penyu Mesin
            "1020041010" => "1020041020", //Akum Penyu Peralatan Produksi
            "1020050010" => "1020050020", //Akum Penyu Tanah Dan Bangunan
        );

        //ini hanya untuk solo, pilih dengan akun utama (dalam hal ini static) JIKA ADA :P
        $staticAssetsAccountProduksi = array(
            "1020010010" => "1020010020", //Akum Penyu Kendaraan
            "1020020010" => "1020020020", //Akum Penyu Peralatan Kantor
            "1020040010" => "1020040020", //Akum Penyu Mesin Produksi
            "1020030010" => "1020030020", //Akum Penyu Mesin
            "1020041010" => "1020041020", //Akum Penyu Peralatan Produksi
            "1020050010" => "1020050020", //Akum Penyu Tanah Dan Bangunan
        );

        $staticAccountPenyusutan = array(
            "1020010010" => "6040010",//beban penyusutan kendaraan
            "1020020010" => "6040020",//penyusutan peraltan kantor
            "1020041010" => "6040030",//penyusutan peralatan produksi
            "1020030010" => "6040040",//penyususutan mesin
            "1020040010" => "6040050",//penyusutan meisn prouksi
            "1020050010" => "6040060",//penyusutan bangunan
        );

        $staticAccountHppProduksi = array(
            //efisiensi vs hpp produk produksi
            //delivery cost =>delivery cost
            "5020020" => "3020010010",
            //direct labour =>direct labour
            "5020030" => "3020010020",
            //quality =>quality
            "5020040" => "3020010030",
        );
        $itemsFields = $a->getFields();
        $groupItems = array();
        $kol = array();
        foreach ($itemsFields as $tmpFields) {
            $kol[] = $tmpFields['kolom'];
        }
        foreach ($detailItems as $itemsData) {
            foreach ($kol as $kolom) {
//                if ($itemsData->dtime_last_depresiasi == "" OR
//                    isset($itemsData->dtime_last_depresiasi) && date("Y-m", strtotime($itemsData->dtime_last_depresiasi)) != date("Y-m") OR
//                    isset($_GET['force'])) {
                $groupItems[$itemsData->folders][$itemsData->id][$kolom] = $itemsData->$kolom;
//                }
            }
        }
        // arrPrint($groupItems);


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
            "jenisTrName" => "request depresiasi",
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
            "pihakMainName" => "",
            "pihakMainID" => "",
            "description" => isset($produkExceptionLabel[$excepRun]) ? $produkExceptionLabel[$excepRun] : "",
            "keterangan" => isset($produkExceptionLabel[$excepRun]) ? $produkExceptionLabel[$excepRun] : "",


            "comRekName_2_coa" => "comRekName_2_child_coa",
            "rekName_2_coa" => "rekName_2_child_coa",
            "comRekName_3_coa" => "comRekName_3_child_coa",
            "rekName_3_coa" => "rekName_3_child_coa",

            "comRekName_2" => "comRekName_2_child",
            "rekName_2" => "rekName_2_child",
            "comRekName_3" => "comRekName_3_child",
            "rekName_3" => "rekName_3_child",
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
            "place2ID" => "-1",
            "place2Name" => "PUSAT",
            "cabang2ID" => "-1",
            "cabang2Name" => "PUSAT",

            "pihakMainChild_coa" => "pihakMainChild_coa",
            "rekAkum_child_coa" => "rekAkum_child_coa",

            "comRekName_1_child_coa" => "comRekName_1_child_coa",
            "comRekName_2_child_coa" => "comRekName_2_child_coa",
            "comRekName_3_child_coa" => "comRekName_3_child_coa",

            "rekName_1_child_coa" => "rekName_1_child_coa",
            "rekName_2_child_coa" => "rekName_2_child_coa",
            "rekName_3_child_coa" => "rekName_3_child_coa",

            "rekName1IDChild_coa" => "rekName_1_child_coa",
            "rekName2IDChild_coa" => "rekName2IDChild_coa",
            "rekName3IDChild_coa" => "rekName3IDChild_coa",

            "pihakMainChild" => "pihakMainChild",
            "rekAkum_child" => "rekAkum_child",

            "comRekName_2_coa" => "comRekName_2_coa",
            "rekName_2_coa" => "rekName_2_coa",

            "comRekName_1_child" => "comRekName_1_child",
            "comRekName_2_child" => "comRekName_2_child",
            "comRekName_3_child" => "comRekName_3_child",

            "rekName_1_child" => "rekName_1_child",
            "rekName_2_child" => "rekName_2_child",
            "rekName_3_child" => "rekName_3_child",

            "rekName1IDChild" => "rekName_1_child",
            "rekName2IDChild" => "rekName2IDChild",
            "rekName3IDChild" => "rekName3IDChild",
            "rekEfisiensi_coa" => "rekEfisiensi_coa",
            //pembantu efisiensi
            "efisensi_child_id" => "efisensi_child_id",
            "efisensi_child_nama" => "efisensi_child_nama",
            "efisensi_child2_id" => "efisensi_child2_id",
            "efisensi_child2_nama" => "efisensi_child2_nama",

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
            "jenis_label" => "request depresiasi",
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
            "keterangan" => isset($produkExceptionLabel[$excepRun]) ? $produkExceptionLabel[$excepRun] : "",
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
            "gudang2" => Array
            (
                "elementType" => "dataModel",
                "name" => "gudang2",
                "key" => "-1",
                "labelSrc" => "name",
                "label" => "gudang dc",
                "labelValue" => "default center warehouse",
                "mdl_name" => "MdlGudangDefault_center",
                "contents" => "YToxOntzOjQ6Im5hbWEiO3M6MDoiIjt9",
                "contents_intext" => print_r(array("nama" => ""), 1),
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
        $relOptionConfigs = isset($this->configUi[$this->jenisTr]['relativeOptions']) ? $this->configUi[$this->jenisTr]['relativeOptions'] : array();
        $title = $this->configUi[$jenisTr]["label"];
        $subTitle = $this->configUi[$jenisTr]["steps"][1]['label'];

//        arrPrint($data);
//        mati_disini(__LINE__);

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
            $this->load->model("Mdls/MdlMongoMother");
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
            $nn = 0;
            $sourceItems = array();
            foreach ($data as $dataExtern) {
                $rekMain = $dataExtern->rekening_main;
                $sourceItemsTmp = array();
                if ($dataExtern->dtime_last_depresiasi == "" OR
                    isset($dataExtern->dtime_last_depresiasi) && date("Y-m", strtotime($dataExtern->dtime_last_depresiasi)) != date("Y-m") OR
                    isset($_GET['force'])) {
                    $nn++;
                    cekHijau("[$nn] RUNNING DEPRESIASI... || " . $dataExtern->extern_id . " || " . $dataExtern->extern_nama);

                    foreach ($dataExtern as $coll => $colVal) {
                        $sourceItemsTmp[$coll] = $colVal;
                    }
                    if ($lockerSource[$dataExtern->cabang_id][$dataExtern->extern_id]['nilai'] > 1) {
                        $sourceItems[$dataExtern->cabang_id][$rekMain][$dataExtern->extern_id] = $sourceItemsTmp;
                    }
                    else {
                        // jika mau tetap di tampilkan pada antarmuka CLI,
                        // maka produk/jasa akan tampil di CLI, namun tidak akan di proses penyusutan nya...
                        // $sourceItems[$dataExtern->cabang_id][$rekMain][$dataExtern->extern_id] = $sourceItemsTmp;
                    }
                }
                else {
                    cekHitam("TIDAK RUNNING DEPRESIASI... || " . $dataExtern->extern_id . " || " . $dataExtern->extern_nama);
                }
            }

            //region build dulu berdasarkan jenis depresiasi
            $finalData = array();
            foreach ($sourceItems as $cabID => $dataSource2) {
                foreach ($dataSource2 as $_rekMainID => $dataSource) {
//                cekMerah("cabID: $cabID | dataSource");
//                arrPrint($dataSource);
                    foreach ($groupItems as $gID => $detailGID) {
//                    cekMerah("gID: $gID | detailGID");
//                    arrPrint($detailGID);
                        foreach ($detailGID as $externID => $detilExtern) {
//                        cekMerah("externID: $externID | detilExtern");
//                        arrPrint($detilExtern);
                            if (isset($dataSource[$externID])) {
                                $finalData[$cabID][$_rekMainID][$gID][$externID] = $dataSource[$externID] + $detilExtern;
                            }
                        }
                    }
                }
            }

            $tableView = "";
            $tableView .= "<table cellspacing='1' cellpadding='1' border='1' bgcolor='#fff'>";
            $tableView .= "<caption>ITEMS SEWA</caption>";
            $tableView .= "<thead>";
            $tableView .= "<tr>";
            $tableView .= "<th>No.</th>";
            $tableView .= "<th>Extern No</th>";
            $tableView .= "<th>Cabang/Pusat</th>";
            $tableView .= "<th>Nama Asset/Sewa</th>";
            $tableView .= "<th>Jenis TR</th>";
            $tableView .= "<th>Rek ACC</th>";
            $tableView .= "<th>Rek MAIN</th>";
            $tableView .= "<th>Rek Details</th>";
            $tableView .= "<th>TGL Depre</th>";
            $tableView .= "<th>Last Depre</th>";
            $tableView .= "<th>Nilai Sisa (locker)</th>";
            $tableView .= "<th>Keterangan</th>";
            $tableView .= "<th>ACTION</th>";
            $tableView .= "</tr>";
            $tableView .= "</thead>";
            $tableView .= "<tbody>";


            //endregion build dulu berdasarkan jenis depresiasi

            foreach ($finalData as $cabID => $detilSourceTmp) {
                foreach ($detilSourceTmp as $rekMainID => $detilSource) {
                    if (isset($_GET['byrekmainid'])) {
                        $tmp_detilSource = $detilSource;
                        $detilSource = array();
                        $arrRek = explode(",", $_GET['byrekmainid']);
                        foreach ($tmp_detilSource as $rekID => $iDetails) {
                            if (in_array($rekID, $arrRek)) {
                                $detilSource[$rekID] = $iDetails;
                            }
                        }
                    }
                    foreach ($detilSource as $pihakMainID => $detilSourceItems) {
                        //transaksi main buildder taro sini
                        $arrItems = array();
                        $subtotal = 0;

                        $rekName_1_child = "";
                        $comRekName_2_child = "";
                        $rekName_2_child = "";
                        $comRekName_3_child = "";
                        $rekName_3_child = "";
                        $rekName3IDChild = "";

                        if (isset($_GET['byexternid'])) {
                            $tmp_detilSourceItems = $detilSourceItems;
                            $detilSourceItems = array();
                            $arrExtrn = explode(",", $_GET['byexternid']);
                            foreach ($tmp_detilSourceItems as $extID => $iDetails) {
                                if (in_array($extID, $arrExtrn)) {
                                    $detilSourceItems[$extID] = $iDetails;
                                }
                            }
                        }

                        $numAsset = 1;

                        foreach ($detilSourceItems as $produk_id => $prodsDetil) {

                            $this->jenisTr = "8787"; // selain pusat

                            if ($cabID == -1) {
                                $this->jenisTr = "8786"; //khusus pusat
                            }

                            $dateNow = date('d');
                            $tglDepreItems = $prodsDetil['repeat'];
                            $namaP = isset($prodsDetil['extern_nama']) ? $prodsDetil['extern_nama'] : "";
                            $tglTerakhirDepresiasi = $prodsDetil['dtime_last_depresiasi'];
                            $gudang_nama = isset($cabangData[$cabID]) ? $cabangData[$cabID] : "undefined";

                            $txtNotif = ($dateNow - $tglDepreItems) == 0 ? "saatnya amortisasi<br>tgl " . date('Y-m-d H:i:s') :
                                (($dateNow - $tglDepreItems) < 0 ? "kurang " . ($tglDepreItems - $dateNow) . " hari hingga amortisasi" :
                                    "lewat " . ($dateNow - $tglDepreItems) . " hari");

                            $gudSpec = getDefaultWarehouseID($cabID);
                            $gudID = isset($lockerSource[$cabID][$produk_id]['gudang_id']) ? $lockerSource[$cabID][$produk_id]['gudang_id'] : $gudSpec['gudang_id'];

                            $rekName_1_child = "" . isset($pihakMain[$prodsDetil['asset_account']]) ? "" . $pihakMain[$prodsDetil['asset_account']] : "";
                            $rekName_1_child_coa = "" . $prodsDetil['asset_account'];
                            $comRekName_1_child = isset($staticAccountComRekening[$rekName_1_child_coa]) ? "" . $staticAccountComRekening[$rekName_1_child_coa] : "";
                            $comRekName_2_child = $cabID == 25 ? "" . $accountChilds[$prodsDetil['rekening_main']] : "" . $accountChilds[$prodsDetil['rekening_main']];
                            $rekName_2_child = $cabID == 25 ? "" . $prodsDetil['rekening_main'] : "" . $prodsDetil['rekening_main'];
                            $comRekName_3_child = $cabID == 25 ? 0 : "RekeningPembantuDepresiasi";

                            cekMerah($prodsDetil['rekening_main']);
                            cekMerah($prodsDetil['rekening_details']);
//                            arrPrintWebs($prodsDetil);
                            cekHitam("LINE: " . __LINE__ . " rekening_main | rekening_details");

                            $rekName_3_child = $cabID == 25 ? $arrCoa[$prodsDetil['rekening_main']] : "" . $arrCoa[$prodsDetil['rekening_details']];
                            $rekName3IDChild = $cabID == 25 ? $staticAccountHppProduksi[$prodsDetil['rekening_main']] : "" . $prodsDetil['rekening_details'];


                            //untuk urusan efisiensi
                            $efisensi_child_id = $cabID == 25 ? $dataPrebiaya[$prodsDetil['rekening_main']]["id"] : "";
                            $efisensi_child_nama = $cabID == 25 ? $dataPrebiaya[$prodsDetil['rekening_main']]["nama"] : "";
                            $efisensi_child2_id = $cabID == 25 ? $prodsDetil['rekening_details'] : "";
                            $efisensi_child2_nama = $cabID == 25 ? $dtaProduksi[$prodsDetil['rekening_details']] : "";

                            $pihakMainChild = $cabID == 25 ? "" . $staticAccountPenyusutan[$prodsDetil['asset_account']] : "" . $staticAccountPenyusutan[$prodsDetil['asset_account']];

                            $rekAkumPenyu_coa = $cabID == 25 ? "" . $staticAssetsAccountProduksi[$prodsDetil['asset_account']] : "" . $staticAssetsAccount[$prodsDetil['asset_account']];
                            $rekAkum_child = $cabID == 25 ? "" . $staticAssetsAccountProduksi[$prodsDetil['asset_account']] : "" . $staticAssetsAccount[$prodsDetil['asset_account']];

                            $tableView .= "<tr>";
                            $tableView .= "<td>$numAsset</td>";
                            $tableView .= "<td>" . $prodsDetil['extern_id'] . "</td>";
                            $tableView .= "<td>" . $cabangData[$cabID] . "</td>";
                            $tableView .= "<td>" . $prodsDetil['extern_nama'] . "</td>";
                            $tableView .= "<td>" . $this->jenisTr . "</td>";
                            $tableView .= "<td>" . $prodsDetil['asset_account'] . "<br>" . $arrCoaAccount[$prodsDetil['asset_account']] . "</td>";
                            $tableView .= "<td>" . $prodsDetil['rekening_main'] . "<br>" . $arrCoaAccount[$prodsDetil['rekening_main']] . "</td>";
                            $tableView .= "<td>" . $prodsDetil['rekening_details'] . "<br>" . (isset($prodsDetil['rekening_details']) ? $arrCoaAccount[$prodsDetil['rekening_details']] : " - ") . "</td>";
                            $tableView .= "<td>Tgl " . $tglDepreItems . "</td>";
                            $tableView .= "<td>" . $tglTerakhirDepresiasi . "</td>";
                            $tableView .= "<td>" . number_format($lockerSource[$cabID][$produk_id]['nilai'], 2) . "</td>";
                            $tableView .= "<td>" . $txtNotif . "</td>";
                            $tableView .= "<td>NONE</td>";
                            $tableView .= "</tr>";

                            if (isset($lockerSource[$cabID][$produk_id]) && $lockerSource[$cabID][$produk_id]['nilai'] > 1 && ($dateNow - $tglDepreItems) == 0 OR
                                isset($lockerSource[$cabID][$produk_id]) && $lockerSource[$cabID][$produk_id]['nilai'] > 1 && isset($_GET['force'])) {

                                $valCount = (($prodsDetil['harga_perolehan'] - 1) / $prodsDetil['economic_life_time']);
                                $valSisaLocker = isset($lockerSource[$cabID][$produk_id]['nilai']) ? $lockerSource[$cabID][$produk_id]['nilai'] : 0;
                                $valDef = (($valSisaLocker - 1) < $valCount) ? ($valSisaLocker - 1) : ceil(($prodsDetil['harga_perolehan']) / $prodsDetil['economic_life_time']);

                                if (isset($_GET['force'])) {
                                    cekMerah($namaP . ' DIPAKSA UNTUK DIDEPRESIASI ');
                                }

                                $subtotal += $valDef;
                                $arrItems[$produk_id] = array(
                                    "harga" => $valDef,
                                    "produk_ord_hrg" => $valDef,
                                    "qty" => "1",
                                    "jml" => "1",
                                    "subtotal" => $valDef * 1,
                                    "subTotal" => $valDef * 1,
                                    "sub_total" => $valDef,
                                    "sub_subtotal" => $valDef,
                                    "sub_harga" => $valDef,
                                    "id" => $prodsDetil['extern_id'],
                                    "produk_id" => $prodsDetil['extern_id'],
                                    "produk_ord_jml" => 1,
                                    "produk_nama" => isset($prodsDetil['extern_nama']) ? $prodsDetil['extern_nama'] : "",
                                    "name" => isset($prodsDetil['extern_nama']) ? $prodsDetil['extern_nama'] : "",
                                    "nama" => isset($prodsDetil['extern_nama']) ? $prodsDetil['extern_nama'] : "",
                                    "kode" => isset($prodsDetil['kode']) ? $prodsDetil['kode'] : "",
                                    "merk" => isset($prodsDetil['merk']) ? $prodsDetil['merk'] : "",
                                    "serial_no" => isset($prodsDetil['serial_no']) ? $prodsDetil['serial_no'] : "",
                                    "extern_id" => $cabID == 25 ? "$efisensi_child2_id" : $arrCoaExtern[$prodsDetil["rekening_details"]]["extern_id"],
                                    "extern_nama" => $cabID == 25 ? "$efisensi_child2_nama" : $arrCoaExtern[$prodsDetil["rekening_details"]]["extern_nama"],
                                    "produk_jenis" => "aktiva",
                                    "hpp" => $valDef,
                                    "olehID" => "-100",
                                    "olehName" => "sys",
                                    "placeID" => $cabID,
                                    "placeName" => $cabangData[$cabID],
                                    "cabangName" => $cabangData[$cabID],
                                    "gudangName" => isset($branchData[$cabID][$gudID]['gudang_nama']) ? $branchData[$cabID][$gudID]['gudang_nama'] : "",
                                    "cabangID" => $cabID,
                                    "gudangID" => $gudID,

                                    "pihakMainChild_coa" => $pihakMainChild,
                                    "rekAkum_child_coa" => $rekAkum_child,
                                    "rekName_1_child_coa" => $rekName_1_child_coa,

                                    "comRekName_2_coa" => $comRekName_2_child,
                                    "rekName_2_coa" => $rekName_2_child,

                                    "comRekName_1_child_coa" => $comRekName_1_child,
                                    "comRekName_2_child_coa" => $comRekName_2_child,
                                    "rekName_2_child_coa" => $rekName_2_child,
                                    "comRekName_3_child_coa" => $comRekName_3_child,
                                    "rekName_3_child_coa" => $rekName_3_child,
                                    "rekName3IDChild_coa" => $rekName3IDChild,

                                    "pihakMainChild" => "penyusutan " . $pihakMain[$pihakMainID],
                                    "rekAkum_child" => $rekAkum_child,
                                    "rekName_1_child" => $rekName_1_child,
                                    "comRekName_1_child" => $comRekName_1_child,
                                    "comRekName_2_child" => $comRekName_2_child,
                                    "rekName_2_child" => $rekName_2_child,
                                    "comRekName_3_child" => $comRekName_3_child,
                                    "rekName_3_child" => $rekName_3_child,
                                    "rekName3IDChild" => $rekName3IDChild,
                                    "rekEfisiensi_coa" => "3020010",
                                    "efisensi_child_id" => $efisensi_child_id,
                                    "efisensi_child_nama" => $efisensi_child_nama,
                                    "efisensi_child2_id" => $efisensi_child2_id,
                                    "efisensi_child2_nama" => $efisensi_child2_nama,


                                );

                                if ($cabID == 25) {
                                    // $arrITems[$produk_id] = array(
                                    // "" => $comRekName_3_child,
                                    // "" => $rekName_3_child,
                                    // "" => $rekName3IDChild,
                                    // "" => $comRekName_3_child,
                                    // "" => $rekName_3_child,
                                    // "" => $rekName3IDChild,

                                    // );
                                }
                                else {
                                    unset($arrItems[$produk_id]['comRekName_3_child_coa']);
                                    unset($arrItems[$produk_id]['rekName_3_child_coa']);
                                    unset($arrItems[$produk_id]['rekName3IDChild_coa']);
                                    unset($arrItems[$produk_id]['comRekName_3_child']);
                                    unset($arrItems[$produk_id]['rekName_3_child']);
                                    unset($arrItems[$produk_id]['rekName3IDChild']);
                                    unset($arrItems[$produk_id]['rekEfisiensi_coa']);
                                    unset($arrItems[$produk_id]['efisensi_child_id']);
                                    unset($arrItems[$produk_id]['efisensi_child_nama']);
                                    unset($arrItems[$produk_id]['efisensi_child2_id']);
                                    unset($arrItems[$produk_id]['efisensi_child2_nama']);

                                    unset($itemsTmp["comRekName_3_child_coa"]);
                                    unset($itemsTmp["rekName_3_child_coa"]);
                                    unset($itemsTmp["rekName3IDChild_coa"]);
                                    unset($itemsTmp["comRekName_3_child"]);
                                    unset($itemsTmp["rekName_3_child"]);
                                    unset($itemsTmp["rekName3IDChild"]);
                                    unset($itemsTmp["rekEfisiensi_coa"]);
                                    unset($itemsTmp["efisensi_child_id"]);
                                    unset($itemsTmp["efisensi_child_nama"]);
                                    unset($itemsTmp["efisensi_child2_id"]);
                                    unset($itemsTmp["efisensi_child2_nama"]);
                                }

// arrPrint($arrItems);
//                                 matiHere(__LINE__." || ".__FUNCTION__);
                            }

                            $numAsset++;
                        }

                        //$detilSourceItems

                        //region builder main
                        $main = array(
                            "olehID" => "-100",
                            "olehName" => "sys",
                            "placeID" => $cabID,
                            "placeName" => $cabangData[$cabID],
                            "cabangID" => $cabID,
                            "cabangName" => $cabangData[$cabID],
                            "gudangID" => $gudID,
                            "gudangName" => (isset($branchData[$cabID][$gudID]['gudang_nama']) ? $branchData[$cabID][$gudID]['gudang_nama'] : ""),
                            "jenisTr" => $this->jenisTr,
                            "jenisTrMaster" => $this->jenisTr,
                            "jenisTrTop" => $this->jenisTr . "r",
                            "jenisTrName" => "request depresiasi",
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

                            "pihakMainName_coa" => $pihakMain[$pihakMainID],
                            "pihakMainID_coa" => $pihakMainID,
                            "pihakMainChild_coa" => $pihakMainChild,

                            "rekAkumPenyu_coa" => $rekAkumPenyu_coa,
                            "rekName_1_coa" => $rekName_1_child,
                            "comRekName_2_coa" => $comRekName_2_child,
                            "rekName_2_coa" => $rekName_2_child,
                            "comRekName_3_coa" => $comRekName_3_child,
                            "rekName_3_coa" => $rekName_3_child,
                            "rekName3ID_coa" => $rekName3IDChild,

                            "pihakMainName" => $pihakMain[$pihakMainID],
                            "pihakMainID" => $pihakMainID,
                            "pihakMainChild" => "penyusutan " . $pihakMain[$pihakMainID],

                            "rekAkumPenyu" => "akum penyu " . $pihakMain[$pihakMainID],
                            "rekName_1" => $rekName_1_child,
                            "comRekName_2" => $comRekName_2_child,
                            "rekName_2" => $rekName_2_child,
                            "comRekName_3" => $comRekName_3_child,
                            "rekName_3" => $rekName_3_child,
                            "rekName3ID" => $rekName3IDChild,
                            "rekEfisiensi_coa" => "3020010",
                            "efisensi_child_id" => $efisensi_child_id,
                            "efisensi_child_nama" => $efisensi_child_nama,
                            "efisensi_child2_id" => $efisensi_child2_id,
                            "efisensi_child2_nama" => $efisensi_child2_nama,

                            "description" => isset($produkExceptionLabel[$excepRun]) ? $produkExceptionLabel[$excepRun] : "",
                            "keterangan" => isset($produkExceptionLabel[$excepRun]) ? $produkExceptionLabel[$excepRun] : "",
                        );
                        if ($cabID == 25) {
                            $main["rekEfisiensi_coa"] = "3020010";
                        }
                        else {
                            /*
                             * diunset biar jurnal gak jalan selain di cabang produksi supaya gerbang tidak tercipta
                             */
                            unset($main["comRekName_3_coa"]);
                            unset($main["rekName_3_coa"]);
                            unset($main["rekName3ID_coa"]);
                            unset($main["comRekName_3"]);
                            unset($main["rekName_3"]);
                            unset($main["rekName3ID"]);
                            unset($main["rekEfisiensi_coa"]);
                            unset($main["efisensi_child_id"]);
                            unset($main["efisensi_child_nama"]);
                            unset($main["efisensi_child2_id"]);
                            unset($main["efisensi_child2_nama"]);

                        }
                        //endregion builder main

                        //region builder items
                        $items = array();
                        foreach ($arrItems as $itsID => $itsData) {
                            foreach ($itemsTmp as $col => $selectedRow) {
                                $items[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
                            }
                        }
//                        ceklIme(__LINE__ . " || builder items");
//                        arrPrintWebs($items);
//                         matiHere();
                        //endregion builder items

                        //region builder tabel in master
                        $tableIn_master = array(
                            "trash" => "0",
                            "jenis_master" => $this->jenisTr,
                            "jenis_top" => $this->jenisTr . "r",
                            "jenis" => $this->jenisTr . "r",
                            "jenis_label" => "request depresiasi",
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
                            "pihakMainName" => $pihakMainID,
                            "pihakMainID" => $pihakMainID,
                            "keterangan" => isset($produkExceptionLabel[$excepRun]) ? $produkExceptionLabel[$excepRun] : "",
                        );
                        //endregion builder tabel in master

                        //region builder table in detil
                        $tableIn_detail = array();
                        foreach ($arrItems as $itsID => $itsData) {
                            foreach ($tableIn_detailTmp as $col => $selectedRow) {
                                $tableIn_detail[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
                            }
                        }
//                        arrPrintPink($tableIn_detail);
                        //endregion builder table in detil

                        //region table in master values
                        $tableIn_master_values = array(
                            "gudang" => $gID,
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


                        $sessionData = array(
                            "main" => $main,
                            "items" => $items,
                            "tableIn_master" => $tableIn_master,
                            "tableIn_detail" => $tableIn_detail,
                            "tableIn_master_values" => $tableIn_master_values,
                            "tableIn_detail_values" => $tableIn_detail_values,
                            "receiptDetailFields" => $receiptDetailFields,
                            "receiptSumFields" => $receiptSumFields,
                        );

                        // region membuat element
                        $elementConfigs = isset($this->configUi[$this->jenisTr]["receiptElements"]) ? $this->configUi[$this->jenisTr]["receiptElements"] : array();
                        if (sizeof($elementConfigs) > 0) {
                            foreach ($elementConfigs as $eName => $eSpec) {
                                switch ($eSpec['elementType']) {
                                    case "dataModel":
                                        $amdlName = $eSpec['mdlName'];
                                        $this->load->model("Mdls/" . $amdlName);
                                        $labelSrc = $eSpec['labelSrc'];
                                        $keySrc = $eSpec['key'];
                                        $oo = new $amdlName();
                                        $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : array();
                                        if (sizeof($aFilter) > 0) {
                                            $oo = makeFilter($aFilter, $main, $oo);
                                        }
                                        if (sizeof($autoFilter) > 0) {
                                            $keyFilter = $autoFilter['key'];
                                            $keySrcVAl = $main[$keyFilter];
                                            $mdlSrc = $autoFilter['srcRef']['mdl'];
                                            $srcField = $autoFilter['srcRef']['srcField'];
                                            $srcFieldKey = $autoFilter['srcRef']['filter'];
                                            $this->load->model("Mdls/" . $mdlSrc);
                                            $src = new $mdlSrc();
                                            $srcData = $src->lookupByID($main[$srcFieldKey])->result();
                                            $valSrc = $srcData[0]->$srcField;
                                            $pairSrc = $autoFilter['pairKey'];
                                            if (strlen($valSrc) > 2) {
                                                $validate = "true";
                                            }
                                            else {
                                                $validate = "false";
                                            }
                                            $pairTargetKey = $pairSrc['methode'][$keySrcVAl][$validate];
                                            $oo->setAddKey($pairTargetKey);
                                        }
                                        $tmpo = $oo->lookupAll()->result();
                                        if (sizeof($tmpo) == 1) {
                                            $usedKey = $eSpec['key'];
                                            $defValueSrc = $tmpo[0]->$usedKey;
                                            $configUiJenis = $this->configUi[$this->jenisTr];
                                            $sessionData = heFetchElement_modul_ns($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc, $this->configUi[$this->jenisTr], array(), $sessionData);
                                        }
                                        break;
                                    case "dataField":
                                        break;
                                }
                            }
                        }
                        // endregion membuat element

                        if (sizeof($arrItems) > 0) {
                            $gate['items'] = $arrItems;
                            $gate['main'] = $main;
                            $tCodeTargetJenisTransaksi = $jenisTrTarget = isset($this->configUi[$this->jenisTr]["steps"][1]["target"]) ? $this->configUi[$this->jenisTr]["steps"][1]["target"] : NULL;


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
                                        $mdlName = str_replace($mdlName, $sessionData["main"][$mdlName], $mdlName);
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
                                                $key = str_replace($key, $sessionData["main"][$key], $key);
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
//                                            if (!isset($subParams['static']["transaksi_id"])) {
//
//                                            }
                                            $subParams['static']["fulldate"] = date("Y-m-d");
                                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                            $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh SYSTEM";
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
//                                            cekbiru("gotparams dari pre-proc $comName");
//                                            arrprint($gotParams);
                                            if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                                foreach ($gotParams as $gateName => $gSpec) {
                                                    if (isset($main)) {
                                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                            foreach ($gSpec as $key => $val) {
                                                                $sessionData["main"][$key] = $val;
                                                            }
                                                        }
                                                    }
                                                    //==inject gotParams to child gate
                                                    if (isset($main)) {
                                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                            foreach ($gSpec as $key => $val) {
                                                                $sessionData["main"][$key] = $val;
                                                            }
                                                        }
                                                    }
                                                    //cekMerah("REBUILDING VALUES..");
                                                    if (sizeof($itemNumLabels) > 0) {
                                                        //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                        foreach ($itemNumLabels as $key => $label) {
                                                            //cekHere("$id === $key => $label");
                                                            if (isset($sessionData["main"][$key])) {
                                                                $sessionData["main"]['sub_' . $key] = ($main['jml'] * $sessionData["main"][$key]);
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
//                                fillValues($this->jenisTr, 1, 1);
                                $sessionData = fillValues_he_value_builder_ns($this->jenisTr, 1, 1, $this->configCore[$this->jenisTr], $this->configUi[$this->jenisTr], $this->configValues[$this->jenisTr], 0, $sessionData);
                            }
                            else {
                                echo("no processor defined. skipping preprocessor..<br>");
                            }
                            //endregion

                            //region pre-processors (item)
                            if (isset($this->configCore[$this->jenisTr]['preProcessor'][1]['detail'])) {
                                $iterator = isset($this->configCore[$this->jenisTr]['preProcessor'][1]['detail']) ? $this->configCore[$this->jenisTr]['preProcessor'][1]['detail'] : array();
                                $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields']) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'] : array();
//                                echo "ITEM NUM LABELS";

                                if (sizeof($iterator) > 0) {
                                    foreach ($iterator as $cCtr => $tComSpec) {
                                        $comName = $tComSpec['comName'];
                                        $srcGateName = $tComSpec['srcGateName'];
                                        $srcRawGateName = $tComSpec['srcRawGateName'];
//                                        echo "sub-preproc: $comName, initializing values <br>";
                                        foreach ($sessionData[$srcGateName] as $xid => $dSpec) {
                                            $tmpOutParams[$cCtr] = array();
                                            $id = $xid;
                                            $subParams = array();
                                            if (isset($tComSpec['static'])) {
                                                foreach ($tComSpec['static'] as $key => $value) {
                                                    $realValue = makeValue($value, $sessionData[$srcGateName][$id], $sessionData[$srcGateName][$id], 0);
                                                    $subParams['static'][$key] = $realValue;
                                                }
//                                                if (!isset($subParams['static']["transaksi_id"])) {
//
//                                                }
                                                $subParams['static']["fulldate"] = date("Y-m-d");
                                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                                $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh SYSTEM";
                                            }
//                                            cekLime(":: cetak preprocc... $comName :: $srcGateName ::");
//                                            arrPrint($subParams);
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
//                                                    cekmerah("gotparams dari pre-proc $comName");
//                                                    arrprint($gotParams);
                                                    if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                                        foreach ($gotParams as $gateName => $paramSpec) {
//                                                            cekBiru(":: getParams inject ke $gateName ::");
                                                            if (!isset($sessionData[$gateName])) {
                                                                $sessionData[$gateName] = array();
                                                            }
                                                            else {

                                                            }

                                                            foreach ($paramSpec as $id => $gSpec) {
                                                                if (!isset($sessionData[$gateName][$id])) {
                                                                    $sessionData[$gateName][$id] = array();
                                                                }
                                                                if (isset($sessionData[$gateName][$id])) {
                                                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                                        foreach ($gSpec as $key => $val) {
//                                                                            cekHere(":: injecte ke $gateName, ::: $key diisi dengan $val");
                                                                            $sessionData[$gateName][$id][$key] = $val;
                                                                        }
                                                                    }
                                                                }
                                                                //==inject gotParams to child gate
//                                                                cekHitam("srcGateName = $srcGateName :: " . __LINE__);
                                                                if (isset($sessionData[$srcGateName][$id])) {
                                                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                                        foreach ($gSpec as $key => $val) {
                                                                            $sessionData[$srcGateName][$id][$key] = $val;
                                                                        }
                                                                    }
                                                                }

                                                                //cekMerah("REBUILDING VALUES..");
                                                                if (sizeof($itemNumLabels) > 0) {
                                                                    //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                                    foreach ($itemNumLabels as $key => $label) {
                                                                        if (isset($sessionData[$gateName][$id][$key])) {
                                                                            $sessionData[$gateName][$id]['sub_' . $key] = ($sessionData[$gateName][$id]['jml'] * $sessionData[$gateName][$id][$key]);
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
//                                fillValues($this->jenisTr, 1, 1);
                                $sessionData = fillValues_he_value_builder_ns($this->jenisTr, 1, 1, $this->configCore[$this->jenisTr], $this->configUi[$this->jenisTr], $this->configValues[$this->jenisTr], 0, $sessionData);

                            }
                            else {
                                echo("no processor defined. skipping preprocessor..<br>");
                            }
                            //endregion


                            $this->midValidate($sessionData);
                            $this->unionValidate($sessionData);
                            //===finalisasi sebelum masuk tabel beneran


                            //===isinya ada pembentukan nomor nota dll
                            //region penomoran receipt
                            $this->load->model("CustomCounter");
                            $cn = new CustomCounter("transaksi");
                            $cn->setType("transaksi");
                            $cn->setModul($modul_transaksi);
                            $cn->setStepCode($tCodeTargetJenisTransaksi);

//                            matiHere("modul_transaksi: $modul_transaksi || tCodeTargetJenisTransaksi: $tCodeTargetJenisTransaksi");
//arrPrint($this->configCore[$this->jenisTr]);
//                            matiHere();
                            $counterForNumber = array($this->configCore[$this->jenisTr]['formatNota']);
                            if (!in_array($counterForNumber[0], $this->configCore[$this->jenisTr]['counters'])) {
                                die("Used number should be registered in 'counters' config as well");
                            }
                            echo "<div style='background:#ff7766;'>";
                            foreach ($counterForNumber as $i => $cRawParams) {
                                $cParams = explode("|", $cRawParams);
                                $cValues = array();
                                foreach ($cParams as $param) {
                                    $cValues[$i][$param] = $sessionData["main"][$param];
                                }
                                $cRawValues = implode("|", $cValues[$i]);
                                $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);
                            }
                            echo "</div style='background:#ff7766;'>";

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
                            $cn->setModul($modul_transaksi);
                            $cn->setStepCode($tCodeTargetJenisTransaksi);
                            $configCustomParams = $this->configCore[$this->jenisTr]['counters'];
                            $configCustomParams[] = "stepCode";

                            if (sizeof($configCustomParams) > 0) {
                                $cContent = array();
                                foreach ($configCustomParams as $i => $cRawParams) {
                                    $cParams = explode("|", $cRawParams);
                                    $cValues = array();
                                    foreach ($cParams as $param) {
                                        $cValues[$i][$param] = $sessionData["main"][$param];
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
                                $sessionData["tableIn_master"][$key] = $val;
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
                            foreach ($sessionData["tableIn_detail"] as $id => $dSpec) {
                                foreach ($addSubValues as $key => $val) {
                                    $sessionData["tableIn_detail"][$id][$key] = $val;
                                }
                            }
                            //endregion

                            //region ----------write transaksi, transaksi_data, main_fields, main_values, main_applets, etc
                            if (sizeof($sessionData["tableIn_master"]) > 0) {
                                $sessionData["tableIn_master"]['status_4'] = 11;
                                $sessionData["tableIn_master"]['trash_4'] = 0;

                                $tr = new MdlTransaksi();
                                $tr->addFilter("transaksi.cabang_id='" . $sessionData["tableIn_master"]['cabang_id'] . "'");
                                $insertID = $tr->writeMainEntries($sessionData["tableIn_master"]);
                                showLast_query("hijau");
                                $mongoList['main'][] = $insertID;
                                $epID = $tr->writeMainEntries_entryPoint($insertID, $insertID, $sessionData["tableIn_master"]);
                                $mongoList['main'][] = $epID;

                                $insertNum = $sessionData["tableIn_master"]['nomer'];
                                $sessionData["main"]['nomer'] = $insertNum;
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
                                    $sessionData["main"][$key] = $val;
                                    foreach ($arrInjectorsTarget as $target) {
                                        foreach ($sessionData["items"] as $xis => $iSpec) {
                                            $id = isset($iSpec['id']) && $iSpec['id'] > 0 ? $iSpec['id'] : $xis;
                                            if (isset($sessionData["items"][$id])) {
                                                $sessionData["items"][$id][$key] = $val;
                                            }
                                        }
                                        foreach ($sessionData[$target] as $xis => $iSpec) {
                                            $id = isset($iSpec['id']) && $iSpec['id'] > 0 ? $iSpec['id'] : $xis;
                                            $sessionData[$target][$id][$key] = $val;
                                        }
                                    }
                                }

                                //===signature
                                $dwsign = $tr->writeSignature($insertID, array(
                                    "nomer" => $sessionData["main"]['nomer'],
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
                                    "nomer_top" => $sessionData["main"]['nomer'],
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
                                    "nomer_top" => $sessionData["main"]['nomer'],
                                    "nomers_prev" => "",
                                    "nomers_prev_intext" => "",
                                    "jenises_prev" => "",
                                    "jenises_prev_intext" => "",
                                    "ids_his" => $idHis_blob,
                                    "ids_his_intext" => $idHis_intext,
                                );
                                foreach ($addValues as $key => $val) {
                                    $sessionData["tableIn_master"][$key] = $val;
                                }

                                $injectors_items = array(
                                    "transaksi_id" => $insertID,
                                    "transaksi_no" => $tmpNomorNota,
                                    "nomer" => $tmpNomorNota,
                                );
                            }
                            if (sizeof($sessionData["tableIn_master_values"]) > 0) {
                                if (isset($this->configCore[$this->jenisTr]['tableIn']['mainValues'])) {
                                    $inserMainValues = array();
                                    foreach ($this->configCore[$this->jenisTr]['tableIn']['mainValues'] as $key => $src) {
                                        if (isset($sessionData["tableIn_master_values"][$key])) {
                                            $dd = $tr->writeMainValues($insertID, array(
                                                "key" => $key,
                                                "value" => $sessionData["tableIn_master_values"][$key],
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
                            if (sizeof($sessionData["main_add_values"]) > 0) {
                                $inserMainValues = array();
                                foreach ($sessionData["main_add_values"] as $key => $val) {
                                    $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                                    $inserMainValues[] = $dd;
                                    $mongoList['mainValues'][] = $dd;
                                }
                                if (sizeof($inserMainValues) > 0) {
                                    $arrBlob = blobEncode($inserMainValues);
                                    $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                                }

//                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                            }
                            if (sizeof($sessionData["main_inputs"]) > 0) {
                                foreach ($sessionData["main_inputs"] as $key => $val) {
                                    $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                                    $inserMainValues[] = $dd;
                                    $mongoList['mainValues'][] = $dd;
                                }
                                if (sizeof($inserMainValues) > 0) {
                                    $arrBlob = blobEncode($inserMainValues);
                                    $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                                }
//                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                            }
                            if (sizeof($sessionData["main_add_fields"]) > 0) {
                                foreach ($sessionData["main_add_fields"] as $key => $val) {
                                    $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
                                }
//                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                            }
                            if (sizeof($sessionData["main_elements"]) > 0) {
                                foreach ($sessionData["main_elements"] as $elName => $aSpec) {
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
                            if (sizeof($sessionData["tableIn_detail"]) > 0) {
                                $insertIDs = array();
                                $insertDeIDs = array();
                                foreach ($sessionData["tableIn_detail"] as $dSpec) {
                                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                                    showLast_query("kuning");
                                    $insertIDs[] = $insertDetailID;
                                    $insertDeIDs[$insertID][] = $insertDetailID;
                                    $mongoList['detail'][] = $insertDetailID;
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
                            if (sizeof($sessionData["tableIn_detail2"]) > 0) {
                                $insertIDs = array();
                                foreach ($sessionData["tableIn_detail2"] as $dSpec) {
                                    $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                                    $mongoList['detail'] = $insertIDs;
                                    if ($epID != 999) {
                                        $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                                        $mongoList['detail'] = $insertIDs;
                                    }
//                                cekUngu($this->db->last_query());
                                }
                            }
                            if (sizeof($sessionData["tableIn_detail2_sum"]) > 0) {
                                $insertIDs = array();
                                foreach ($sessionData["tableIn_detail2_sum"] as $dSpec) {
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
                            if (sizeof($sessionData["tableIn_detail_rsltItems"]) > 0) {
                                $insertIDs = array();
                                foreach ($sessionData["tableIn_detail_rsltItems"] as $dSpec) {
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
                            if (sizeof($sessionData["tableIn_detail_values"]) > 0) {
                                foreach ($sessionData["tableIn_detail_values"] as $pID => $dSpec) {
                                    if (isset($this->configCore[$this->jenisTr]['tableIn']['detailValues'])) {
                                        $insertIDs = array();
                                        foreach ($this->configCore[$this->jenisTr]['tableIn']['detailValues'] as $key => $src) {
                                            if (isset($sessionData["tableIn_detail"][$pID])) {
                                                $dd = $tr->writeDetailValues($insertID, array(
                                                    "produk_jenis" => $sessionData["tableIn_detail"][$pID]['produk_jenis'],
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
                            if (sizeof($sessionData["tableIn_detail_values2_sum"]) > 0) {
                                foreach ($sessionData["tableIn_detail_values2_sum"] as $pID => $dSpec) {
                                    if (isset($this->configCore[$this->jenisTr]['tableIn']['detailValues2_sum'])) {
                                        foreach ($this->configCore[$this->jenisTr]['tableIn']['detailValues2_sum'] as $key => $src) {
                                            $dd = $tr->writeDetailValues($insertID, array(
                                                "produk_jenis" => $sessionData["tableIn_detail2_sum"][$pID]['produk_jenis'],
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
//                            arrPrintWebs($main);
//                            arrPrintWebs($items);
                            $baseRegistries = array(
                                'main' => sizeof($sessionData["main"]) > 0 ? $sessionData["main"] : array(),
                                'items' => sizeof($sessionData["items"]) > 0 ? $sessionData["items"] : array(),
                                'items2' => sizeof($sessionData["items2"]) > 0 ? $sessionData["items2"] : array(),
                                'items2_sum' => sizeof($sessionData["items2_sum"]) > 0 ? $sessionData["items2_sum"] : array(),
                                'items3' => sizeof($sessionData["items3"]) > 0 ? $sessionData["items3"] : array(),
                                'items3_sum' => sizeof($sessionData["items3_sum"]) > 0 ? $sessionData["items3_sum"] : array(),
                                'rsltItems' => sizeof($sessionData["rsltItems"]) > 0 ? $sessionData["rsltItems"] : array(),
                                'rsltItems2' => sizeof($sessionData["rsltItems2"]) > 0 ? $sessionData["rsltItems2"] : array(),
                                'tableIn_master' => sizeof($sessionData["tableIn_master"]) > 0 ? $sessionData["tableIn_master"] : array(),
                                'tableIn_detail' => sizeof($sessionData["tableIn_detail"]) > 0 ? $sessionData["tableIn_detail"] : array(),
                                'tableIn_detail2_sum' => sizeof($sessionData["tableIn_detail2_sum"]) > 0 ? $sessionData["tableIn_detail2_sum"] : array(),
                                'tableIn_detail_rsltItems' => sizeof($sessionData["tableIn_detail_rsltItems"]) > 0 ? $sessionData["tableIn_detail_rsltItems"] : array(),
                                'tableIn_detail_rsltItems2' => sizeof($sessionData["tableIn_detail_rsltItems2"]) > 0 ? $sessionData["tableIn_detail_rsltItems2"] : array(),
                                'tableIn_master_values' => sizeof($sessionData["tableIn_master_values"]) > 0 ? $sessionData["tableIn_master_values"] : array(),
                                'tableIn_detail_values' => sizeof($sessionData["tableIn_detail_values"]) > 0 ? $sessionData["tableIn_detail_values"] : array(),
                                'tableIn_detail_values_rsltItems' => sizeof($sessionData["tableIn_detail_values_rsltItems"]) > 0 ? $sessionData["tableIn_detail_values_rsltItems"] : array(),
                                'tableIn_detail_values_rsltItems2' => sizeof($sessionData["tableIn_detail_values_rsltItems2"]) > 0 ? $sessionData["tableIn_detail_values_rsltItems2"] : array(),
                                'tableIn_detail_values2_sum' => sizeof($sessionData["tableIn_detail_values2_sum"]) > 0 ? $sessionData["tableIn_detail_values2_sum"] : array(),
                                'main_add_values' => sizeof($sessionData["main_add_values"]) > 0 ? $sessionData["main_add_values"] : array(),
                                'main_add_fields' => sizeof($sessionData["main_add_fields"]) > 0 ? $sessionData["main_add_fields"] : array(),
                                'main_elements' => sizeof($sessionData["main_elements"]) > 0 ? $sessionData["main_elements"] : array(),
                                'main_inputs' => sizeof($sessionData["main_inputs"]) > 0 ? $sessionData["main_inputs"] : array(),
                                'main_inputs_orig' => sizeof($sessionData["main_inputs"]) > 0 ? $sessionData["main_inputs"] : array(),
                                "receiptDetailFields" => isset($this->configLayout[$this->jenisTr]['receiptDetailFields'][1]) ? $this->configLayout[$this->jenisTr]['receiptDetailFields'][1] : array(),
                                "receiptSumFields" => isset($this->configLayout[$this->jenisTr]['receiptSumFields'][1]) ? $this->configLayout[$this->jenisTr]['receiptSumFields'][1] : array(),
                                "receiptDetailFields2" => isset($this->configLayout[$this->jenisTr]['receiptDetailFields2'][1]) ? $this->configLayout[$this->jenisTr]['receiptDetailFields2'][1] : array(),
                                "receiptSumFields2" => isset($this->configLayout[$this->jenisTr]['receiptSumFields2'][1]) ? $this->configLayout[$this->jenisTr]['receiptSumFields2'][1] : array(),
                            );

                            //===
//                            die("sini");
//                            $doWriteReg = $tr->writeRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
                            $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));

                            $mongRegID[] = $doWriteReg;
//                            arrPrint($mongRegID);
//                            matiHEre(floatval($subtotal));
//                            arrPrint($baseRegistries);

                            //endregion

//                            validateAllBalances($cabangData[$dataTmp->cabang_id]);
                            //region processing sub-post-processors, always items
                            $iterator = isset($this->configCore[$this->jenisTr]['postProcessor'][$jenisTrTarget]['detail']) ? $this->configCore[$this->jenisTr]['postProcessor'][$jenisTrTarget]['detail'] : array();
                            arrprint($iterator);

                            if (sizeof($iterator) > 0) {
                                foreach ($iterator as $cCtr => $tComSpec) {
                                    $comName = $tComSpec['comName'];
                                    $srcGateName = $tComSpec['srcGateName'];
                                    $srcRawGateName = $tComSpec['srcRawGateName'];
                                    $tmpOutParams[$cCtr] = array();
                                    foreach ($sessionData[$srcGateName] as $cnt => $dSpec) {
                                        foreach ($injectors_items as $ikey => $ival) {
                                            $sessionData[$srcGateName][$cnt][$ikey] = $ival;
                                        }

                                        $subParams = array();
                                        if (isset($tComSpec['loop'])) {
                                            foreach ($tComSpec['loop'] as $key => $value) {
                                                $realValue = makeValue($value, $sessionData[$srcGateName][$cnt], $sessionData[$srcGateName][$cnt], 0);
                                                $subParams['loop'][$key] = $realValue;
                                            }
                                        }
                                        if (isset($tComSpec['static'])) {
                                            foreach ($tComSpec['static'] as $key => $value) {
                                                $realValue = makeValue($value, $sessionData[$srcGateName][$cnt], $sessionData[$srcGateName][$cnt], 0);
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
                                                $jenis = $sessionData['main']['jenis'];
                                                foreach ($paramForceFillers[$comName] as $k => $v) {
                                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                                }
                                            }
                                            $subParams['static']["fulldate"] = date("Y-m-d");
                                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                            $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh SYSTEM";
                                        }

                                        if (sizeof($subParams) > 0) {
                                            $tmpOutParams[$cCtr][] = $subParams;
                                        }
//                                        echo "<script>top.writeProgress('" . $subParams['static']['name'] . " " . $subParams['static']['extern_nama'] . " " . $subParams['static']['nama'] . "');</script>";
                                    }
                                }

//                                arrPrintWebs($iterator);

                                foreach ($iterator as $cCtr => $tComSpec) {
                                    $comName = $tComSpec['comName'];
                                    $srcGateName = $tComSpec['srcGateName'];
                                    $srcRawGateName = $tComSpec['srcRawGateName'];
//                                    echo "sub-postProcessor: $comName, sending values <br>";
//                                    echo "<script>top.writeProgress('SENDING SUB-PROCESSORS ($comName)...', 'head');</script>";
                                    $mdlName = "Com" . ucfirst($comName);
                                    $this->load->model("Coms/" . $mdlName);
                                    $m = new $mdlName();
                                    arrprint($tmpOutParams[$cCtr]);
//                                    matiHEre(__LINE__);
                                    $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                    $m->exec() or die("Gagal saat berusaha exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
//                                    matiHEre(__LINE__);
                                    cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                                }
                            }
//                            matiHEre(__LINE__);
                            //endregion


                            // endregion

                            //region writelog
                            $this->load->model("Mdls/" . "MdlActivityLog");
                            $hTmp = new MdlActivityLog();
                            $tmpHData = array(
                                "title" => $sessionData["main"]['jenisTrName'],
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
                                "url" => "",
                            );
                            $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));

//                            $a->updateData("id")

                            foreach ($arrItems as $itsID => $itsData) {
                                $cekId = $d->updateData(
                                    array(
                                        "extern_id" => $itsID,
                                        "cabang_id" => $itsData['cabangID'],
                                    ),
                                    array(
                                        "dtime_last_depresiasi" => date("Y-m-d H:i:s"),
                                        "last_tr_id" => $insertID,
                                    )
                                ) or die("Failed to update tr next-state!");

//                                cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                            }

//                            arrPrint($cekId);

                            //endregion
                        }
                        else {
//                            cekOrange('gak bikin transaksi mungkin nilai udh abiss wkwkwk');
                        }

                    }
                }
            }

            $tableView .= "</tbody>";
            $tableView .= "</table>";
        }

        cekOrange("======================================= batas bawah ASSET =======================================");
        //endregion batas aset

        cekHere($tableView);
//        arrPrintCyan($sessionData);

//        mati_disini("============= BELUM COMMIT COA===============" . __LINE__);


        if (isset($_GET['debug'])) {
            mati_disini("============= BELUM COMMIT SEWA COA DEBUG ===============");
        }

        $this->db->trans_complete() or mati_disini("Gagal saat berusaha  commit transaction!");

        cekHijau("======================================= BERHASIL COMMIT =======================================");

        //region cloning mongoDB
        $mongoList = array();
        $mongRegID = array();
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
                        $mong->addData($transksi_main);
                    }
                }
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
                if (sizeof($tmpTrm) > 0) {
                    $mong->setTableName("transaksi_registry");
                    foreach ($tmpTrm as $tmpMain) {
                        $transksi_main = json_decode(json_encode($tmpMain), true);
                        $mong->addData($transksi_main);
                    }
                }
            }
        }
        //endregion

        if (isset($_GET['force'])) {
            if (isset($_GET['byexternid'])) {
                writeLog(__FUNCTION__, "force depresiasi", "depresiasi", $_GET['byexternid'], "SYSTEM");
            }
            else {
                writeLog(__FUNCTION__, "force depresiasi", "depresiasi", "all", "SYSTEM");
            }
        }

        echo "jika halaman tidak menutup dengan otomatis, anda boleh klik close";

    }


}