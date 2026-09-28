<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once "Modul_Controller.php";

class MasterData extends Modul_Controller
{

    protected $nonView = 0;

    public function __construct()
    {
        parent::__construct();

        $this->load->library("Curl");

        $this->accesList = aksesProject();
        $employeMembership = in_array("o_project_spv", $this->session->login["membership"]) ? "o_project_spv" : "none";
        $this->customAccesProject = customProjectAccess($this->session->login["id"], $employeMembership);

        $this->generatorPaymentSource();
//        echo "<pre>";
//        print_r($this->session->login["membership"]);
//        echo "</pre>";

        /* ----------------------------------------------------------------------------------
         * validasi session bila tidak ada dipaksa ke halaman login
         * ----------------------------------------------------------------------------------*/
    }

    public function debug()
    {
        $data = array(
            "mode" => "index",
        );
        $this->load->view("json_viewer", $data);
    }

    public function index_()
    {

        $starttime = microtime(true);
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
        }
        $jenisTr = $this->jenisTr;

        /*
         * - dashboard per project
         * - team work
         * - source material
         * - lead
         * - progress
         * - file/kontrak
         * - note/ catatan
         */

        $this->load->model("Mdls/MdlTimworkProject");
        $this->load->model("Mdls/MdlProdukKomposisiProject");
        $this->load->model("Mdls/MdlWorkOrderProject");
        $this->load->model("Mdls/MdlProdukProject");

        $p = new MdlProdukProject();
        $tp = new MdlTimWorkProject();
        $kp = new MdlProdukKomposisiProject();
        $wp = new MdlWorkOrderProject();
        $mid = $this->uri->segment(4);

        //region komposisi
        $tempkomposisi = $kp->lookupByPID($mid)->result();
        $komposisiData = array();
        $komposisiLabel = $kp->getListedFields();
        if (count($tempkomposisi) > 0) {
            foreach ($tempkomposisi as $tempkomposisi_0) {
                $komposisiData[] = (array)$tempkomposisi_0;
            }
        }
        //endregion

        //region timwork
        $tp->addFilter("produk_id=$mid");
        $tempTim = $tp->lookUpAll()->result();
        $timWork = array();
        $timWorkLabel = $tp->getListedFields();
        if (count($tempTim) > 0) {
            foreach ($tempTim as $tempTim_0) {
                $timWork[] = (array)$tempTim_0;
            }
        }
        //endregion

        //region workorder
        $wp->addFilter("produk_id=$mid");
        $tempWorkOrder = $wp->looKupAll()->result();
        $workorderLabel = $wp->getListedFields();
        $workorder = array();
        if (count($tempWorkOrder) > 0) {
            foreach ($tempWorkOrder as $tempWorkOrder_0) {
                $workorder[] = (array)$tempWorkOrder_0;
            }
        }
        //endregion

        $data = array(
            "mode" => "master_project",
            "komposisiLabel" => $komposisiLabel,
            "komposisi" => $komposisiData,
            "workorderLabel" => $timWorkLabel,
            "workorder" => $workorder,
            "timworkLabel" => $timWorkLabel,
            "timwork" => $timWork,
        );
        $this->load->view("data", $data);
    }

    public function index_old()
    {
        $pid = $prodID = $this->uri->segment(4);
        $iframeTarget = isset($_GET["iframe"]) ? $_GET["iframe"] : (isset($_POST["iframe"]) ? $_POST["iframe"] : "result");

        //arrPrintWebs($this->customAccesProject);

        if (count($this->customAccesProject) > 0 && in_array("MdlTimWorkProject", $this->customAccesProject["create"])) {
            $this->load->model("Mdls/" . "MdlCabangProduksi");
            $this->load->model("Mdls/" . "MdlProdukProject");
            $this->load->model("Mdls/" . "MdlProjectWorkOrder");
            $this->load->model("Mdls/" . "MdlProdukRakitanPreBiaya");
            $this->load->model("Mdls/" . "MdlProjectKomposisi");
            $this->load->model("Mdls/" . "MdlProjectKomposisiWorkorder");
            // $this->load->model("Mdls/" . "MdlSupplies");
            // $this->load->model("Mdls/" . "MdlProdukTargetWip");//untuk produk per fase yang terelasi dengan produk
            $this->load->model("Mdls/" . "MdlHargaProduk");
            $this->load->model("Mdls/MdlDtaBiayaProduksi");
            $this->load->model("Mdls/MdlSatuan");
            $this->load->model("Mdls/MdlTimWorkProject");
            $this->load->model("Mdls/MdlEmployee_all");

            $o = new MdlProdukProject();
            $pk = new MdlProjectKomposisi();
            // $o2 = new MdlSupplies();
            $ob = new MdlProdukRakitanPreBiaya();
            // $hs = new MdlHargaSupplies();
            // $cb = new MdlCabangProduksi();
            // $wp = new MdlProdukTargetWip();
            $kf = new MdlProjectKomposisiWorkorder();
            $pf = new MdlProjectWorkOrder();
            $bp = new MdlDtaBiayaProduksi();
            $st = new MdlSatuan();
            $t = new MdlTimWorkProject();
            $e = new MdlEmployee_all();

            //pembuat session untuk keperluan ui
            // unset( $_SESSION['PROED'][$prodID]);
            if (!isset($_SESSION['PROED'][$prodID])) {
                $_SESSION['PROED'][$prodID] = array();
            }
            if (!isset($_SESSION['PROED'][$prodID]['component_sum'])) {
                $_SESSION['PROED'][$prodID]['component_sum'] = array();
            }
            if (!isset($_SESSION['PROED'][$prodID]['gudang'])) {
                $_SESSION['PROED'][$prodID]['gudang'] = array();
            }
            if (!isset($_SESSION['PROED'][$prodID]['component'])) {
                $_SESSION['PROED'][$prodID]['component'] = array();
            }
            if (!isset($_SESSION['PROED'][$prodID]['fase'])) {
                $_SESSION['PROED'][$prodID]['fase'] = array();
            }
            if (!isset($_SESSION['PROED'][$prodID]['gudang_fase'])) {
                $_SESSION['PROED'][$prodID]['gudang_fase'] = array();
            }
            $_SESSION['PROED'][$prodID]['backLink'] = isset($_GET['backLink']) ? unserialize(base64_decode($_GET['backLink'])) : "";

            // $data = "<div>";
            $o->addFilter("id='$pid'");

            $tempProdukMaster = $o->lookUpAll()->result();
            $showFields = $o->getListedFields();
            $dataMaster = array();
            if (count($tempProdukMaster) > 0) {
                foreach ($tempProdukMaster as $tempProdukMaster_0) {
                    foreach ($showFields as $kolom => $kolomLabel) {
                        $dataMaster[$kolom] = $tempProdukMaster_0->$kolom;
                    }
                }
            }

            //produk komposisi sumary
            $pk->setFilters(array());
            $pk->addFilter("produk_id='$pid'");
            $pk->addFilter("jenis in ('produk','biaya')");
            $this->db->order_by("jenis", "DESC");
            $tempProduk = $pk->lookUpAll()->result();
            cekMerah($this->db->last_query());
            $sumarryProjectLabel = $pk->getListedFields();
            $sumarryProject = array();
            if (sizeof($tempProduk) > 0) {
                foreach ($tempProduk as $i => $temproduk_0) {
                    foreach ($sumarryProjectLabel as $key => $label) {
                        $sumarryProject[$temproduk_0->jenis][$i][$key] = $temproduk_0->$key;
                    }
                    if ($temproduk_0->jenis == "biaya") {
                        unset($sumarryProject["biaya"][$i]["jml"]);
                        unset($sumarryProject["biaya"][$i]["harga"]);
                    }
                    $sumarryProject[$temproduk_0->jenis][$i]["subtotal"] = $temproduk_0->harga * $temproduk_0->jml;
                    foreach ($temproduk_0 as $k => $v) {
                        $_SESSION['PROED'][$prodID]['component_sum'][$temproduk_0->jenis][$temproduk_0->produk_dasar_id][$k] = $v;
                    }
                }
            }

//            arrprint($sumarryProject);
//            matiHere(__LINE__);

            $kf->setFilters(array());
            $kf->addFilter("produk_id='$pid'");
            $kf->addFilter("trash='0'");
            $kf->addFilter("status='1'");
            $tempProdukFaseKomposisi = $kf->lookUpAll()->result();
            //         cekHitam($this->db->last_query());
            showLast_query("biru");
            //        arrPrintHijau($tempProdukFaseKomposisi);
            $pf->setFilters(array());
            $pf->addFilter("produk_id='$pid'");
            $tempProdukFase = $pf->lookUpAll()->result();


            //region biaya biaya
            $tempBiaya = $ob->lookUpAll()->result();
            $prebiaya = array();
            if (sizeof($tempBiaya) > 0) {
                foreach ($tempBiaya as $ic => $biayaData) {
                    $prebiaya["cat_id"][$biayaData->id] = (array)$biayaData;
                }
            }

            //endregion

            //region timwork
            arrPrint($this->customAccesProject);
            $allEmployee = $e->lookUpAll()->result();
            $allowedAdd = array();
            if (count($allEmployee) > 0) {
                //bagian tombol-tombol crud
                // arrPrint($this->customAccesProject);
                // matiHEre();
                if (count($this->customAccesProject) > 0 && in_array("MdlTimWorkProject", $this->customAccesProject["create"])) {
                    $allowedAdd["employee_id"]["create"] = true;
                }
                if (count($this->customAccesProject) > 0 && in_array("MdlTimWorkProject", $this->customAccesProject["update"])) {
                    $allowedAdd["employee_id"]["update"] = true;
                }
                if (count($this->customAccesProject) > 0 && in_array("MdlTimWorkProject", $this->customAccesProject["view"])) {
                    $allowedAdd["employee_id"]["view"] = true;
                }
                if (count($this->customAccesProject) > 0 && in_array("MdlTimWorkProject", $this->customAccesProject["delete"])) {
                    $allowedAdd["employee_id"]["delete"] = true;
                }

                foreach ($allEmployee as $allEmployee_0) {
                    $preEmployee["employee_id"][$allEmployee_0->id] = (array)$allEmployee_0;
                }
            }
            $tempRelHakAkses = aksesProject();
            $preHakAkses = array();
            if (count($tempRelHakAkses) > 0) {
                foreach ($tempRelHakAkses as $tempRelHakAkses_0) {
                    $preEmployee["akses_id"][$tempRelHakAkses_0["id"]] = $tempRelHakAkses_0;
                }
            }

            $t->addFilter("produk_id='$prodID'");
            $tmpTimwork = $t->lookUpAll()->result();

            $timWork = array();
            $fieldsTimLabel = $t->getListedFields();
//             arrPrint($tmpTimwork);
            // arrPrint($fieldsTimLabel);
            if (count($tmpTimwork) > 0) {
                foreach ($tmpTimwork as $timWork_0) {
                    $tmp = array();
                    foreach ($fieldsTimLabel as $key => $label) {
                        $tmp[$key] = $timWork_0->$key;
                    }
                    $timWork[$timWork_0->id] = $tmp;
                    $preTimwork["employee_id"][$timWork_0->id] = (array)$timWork_0;
                }
            }
            //endregion
//             arrPrint($timWork);
            //         matiHere();
            //region komposisi work order
            $produkFase = array();
            $fields = $pf->getListedFields();
            // matiHere();
            if (sizeof($tempProdukFase) > 0) {
                if (count($this->customAccesProject) > 0 && in_array("MdlProjectWorkOrder", $this->customAccesProject["create"])) {
                    $allowedAdd["fase_id"]["create"] = true;
                }
                if (count($this->customAccesProject) > 0 && in_array("MdlProjectWorkOrder", $this->customAccesProject["update"])) {
                    $allowedAdd["fase_id"]["update"] = true;
                }
                if (count($this->customAccesProject) > 0 && in_array("MdlProjectWorkOrder", $this->customAccesProject["view"])) {
                    $allowedAdd["fase_id"]["view"] = true;
                }
                if (count($this->customAccesProject) > 0 && in_array("MdlProjectWorkOrder", $this->customAccesProject["delete"])) {
                    $allowedAdd["fase_id"]["delete"] = true;
                }
                foreach ($tempProdukFase as $data) {
                    foreach ($fields as $field => $fieldAlias) {
                        $produkFase[$data->id][$field] = isset($data->$field) ? $data->$field : "";
                    }
                }
                $_SESSION['PROED'][$prodID]['fase'] = $produkFase;
            }
            else {
                $produkFase = array();
                cekLime("belum ada relasi produk fase");
            }
            //endregion

            //region biaya
            $tempBiayaProduksi = $bp->lookUpAll()->result();

            if (sizeof($tempBiayaProduksi)) {
                foreach ($tempBiayaProduksi as $ixb => $tempBiayaProduksi_0) {
                    $prebiaya["produk_dasar_id"][$tempBiayaProduksi_0->id] = (array)$tempBiayaProduksi_0;
                }
            }

            $tempSatuan = $st->lookUpAll()->result();
            if (sizeof($tempSatuan) > 0) {
                foreach ($tempSatuan as $tempSatuan_0) {
                    $prebiaya["satuan_id"][$tempSatuan_0->id] = (array)$tempSatuan_0;
                }
            }
            //endregion


            //relasi jadi option atau input
            $arrRel = array();
            //arrPrint($tempProdukFaseKomposisi);
            //matiHere();
            $priceList = array();
            $produkKomposisTarget = array();
            if (sizeof($tempProdukFaseKomposisi) > 0) {
                if (count($this->customAccesProject) > 0 && in_array("MdlProjectKomposisiWorkorder", $this->customAccesProject["create"])) {
                    $allowedAdd["produk_dasar_id"]["create"] = true;
                }
                if (count($this->customAccesProject) > 0 && in_array("MdlProjectKomposisiWorkorder", $this->customAccesProject["update"])) {
                    $allowedAdd["produk_dasar_id"]["update"] = true;
                }
                if (count($this->customAccesProject) > 0 && in_array("MdlProjectKomposisiWorkorder", $this->customAccesProject["view"])) {
                    $allowedAdd["produk_dasar_id"]["view"] = true;
                }
                if (count($this->customAccesProject) > 0 && in_array("MdlProjectKomposisiWorkorder", $this->customAccesProject["delete"])) {
                    $allowedAdd["produk_dasar_id"]["delete"] = true;
                }

                foreach ($tempProdukFaseKomposisi as $ii => $iiData) {
                    $priceList[$iiData->fase_id][$iiData->produk_dasar_id] = array(
                        "harga_ori" => $iiData->nilai,
                        "harga_bom" => $iiData->harga,
                    );
                    if (!isset($iiData->subtotal)) {
                        $iiData->subtotal = $iiData->jml * $iiData->harga;
                    }
                    $produkKomposisiFase[$iiData->fase_id][$iiData->jenis][$iiData->produk_dasar_id]["subtotal"] = $iiData->jml * $iiData->harga;
                    $produkKomposisiFase[$iiData->fase_id][$iiData->jenis][$iiData->produk_dasar_id] = (array)$iiData;
                    if ($iiData->jenis == "target") {
                        $produkKomposisTarget[$prodID][$iiData->fase_id] = (array)$iiData;
                    }

                }
                $_SESSION['PROED'][$prodID]['component'] = $produkKomposisiFase;
            }
            else {
                $produkKomposisiFase = array();
            }
            // arrPrint($_SESSION['PROED'][$prodID]['component']);
            //        arrPrintWebs($produkKomposisiFase);
            // matiHEre();
            $fieldFormProdukKomposisiFase = $kf->getFields();
            $relationDataProdukKomposisiFase = array();
            $relSupplies = array();
            foreach ($fieldFormProdukKomposisiFase as $keyID => $temp0) {
                //             arrPrint($temp0);
                if ($temp0["inputType"] == "combo" && isset($temp0["reference"])) {
                    $preKey = $temp0["inputType"];
                    $preKey = $temp0["kolom"];
                    $preModel = $temp0["reference"];
                    if (isset($temp0["reference"])) {
                        $this->load->model("Mdls/" . $preModel);
                        $p = new $preModel();
                        $tempDatas = $p->lookUpAll()->result();
                        if (sizeof($tempDatas) > 0) {
                            foreach ($tempDatas as $datas_0) {
                                $relSupplies[$preKey][$datas_0->id] = (array)$datas_0;
                            }
                        }
                    }
                    else {

                    }
                }
            }


            /*
             * hirarki builder produk
             * produk[produk_id][fase_id] =array(
             * array(
             * )
             * )
             */

            // matiHEre();

            $produkKomposisi = array();//untuk summary semua fase di simpan di komposisi produk
            $fieldsTimLabel = array(
                "employee_id" => "nama",
                "akses_id" => "hak akses",
            );
            $produkFaseHeader = array(
                // "urut" => "urutan pekerjaan",
                "nama" => "Rencana Kerja",
                "keterangan" => "Keterangan",
                "lokasi" => "lokasi",
                "employee_id" => "penanggung jawab",
                // "daftar_tugas" => "daftar tugas",
            );
            $relSuppliesHeader = array(
                "cat_id" => "cat_nama",
                "satuan_id" => "satuan",
                "produk_dasar_id" => "produk_dasar_nama",
                "harga_ori" => "harga",
                "harga_bom" => "harga_bom",
                "employee_id" => "employee_nama",
                "akses_id" => "hak_akses_nama",
                // "harga_bom"=>"harga_bom",
            );
            $relTimHeader = array(
                "employee_id" => "tim",
                "produk_dasar_id" => "produk_dasar_nama",
                "akses" => "hak_akses",
                // "harga_bom"       => "harga_bom",
                // "harga_bom"=>"harga_bom",
            );
            $produkKomposisiFaseHeader = array(
                "produk" => array(
                    "produk_dasar_id" => "Bahan baku",
                    "satuan" => "Satuan",
                    "nilai" => "Harga standar(satuan)",
                    "harga" => "Harga anggaran(satuan)",
                    "jml" => "Jml",
                    "subtotal" => "Subtotal",
                ),
                "biaya" => array(
                    "produk_dasar_id" => "Jasa",
                    "cat_id" => "Kategori biaya",
                    "satuan_id" => "satuan",
                    // "harga_ori"       => "harga standar",
                    "harga" => "Harga",
                    "jml" => "Jml",
                    "subtotal" => "Subtotal",
                ),
                // "target" => array(
                //     "produk_dasar_id" => "Produk fase(wip)",
                //     // "gudang2_id"         => "fase proses lanjut*",
                // ),
                // "timwork" => array(
                //     "employee_id" => "nama",
                //     "akses_id" => "hak akses",
                //     // "gudang2_id"         => "fase proses lanjut*",
                // ),

            );
            $produkKomposisiFaseEditable = array(
                "produk_dasar_id" => "produk_dasar_id",
                "cat_id" => "cat_id",
                "satuan_id" => "satuan_id",
                "harga" => "harga",
                "jml" => "jml",
                "fase_id" => "fase_id",
                "employee_id" => "employee_id",
                "akses_id" => "akses_id",
            );

            $masterLabel = array(
                "produk" => "Material",
                "biaya" => "Jasa",
            );

            $sumarryProjectLabel = $sumarryProjectLabel + array("subtotal" => "subtotal");

            $data = array(
                "mode" => "master_project",
                "masterProjectField" => $showFields,
                "masterProject" => $dataMaster,
                "sumaryProjectLabel" => $sumarryProjectLabel,
                "sumaryProject" => $sumarryProject,
                "masterLabel" => $masterLabel,
                "masterTimworkLabel" => $fieldsTimLabel,
                "masterTimwork" => $timWork,
                "produkID" => $prodID,
                "produkNama" => $tempProdukMaster[0]->nama,
                "produk_komposisi" => $produkKomposisi,
                "produk_fase" => $produkFase,
                "produk_komposisi_fase" => $_SESSION['PROED'][$prodID]['component'],
                "selector" => MODUL_PATH . get_class($this) . "/addSession/PROED/",
                //bagian header
                "produk_fase_header" => $produkFaseHeader,
                "produk_komposisi_fase_header" => $produkKomposisiFaseHeader,
                "produk_fase_komposisiEditable" => $produkKomposisiFaseEditable,
                "relSupplies" => $relSupplies,
                "relBiaya" => $prebiaya,
                "relEmployee" => $preEmployee,
                "relWorkOrderEmployee" => $preTimwork,
                // "relHakAkses"                        => $preHakAkses,
                "relTarget" => array(),
                "currentTargetWip" => $produkKomposisTarget,
                "relSuppliesHeader" => $relSuppliesHeader,
                // "relbiayaProduksi"=>$prebiayaProduksi,
                "addProdukKomposisiLink" => MODUL_PATH . get_class($this) . "/addData/MdlProjectKomposisi/$prodID",//untuk per produk(sumary)
                "addProdukKomposisiBiayaLink" => MODUL_PATH . get_class($this) . "/addData/MdlProjectKomposisiWorkorder/$prodID",//untuk per produk(summary)
                "addFaseProdukLink" => MODUL_PATH . get_class($this) . "/addData/MdlProjectWorkOrder/$prodID",//ke tabel produk fase
                "addFaseProdukKomposisiLink" => MODUL_PATH . get_class($this) . "/addData/MdlProjectKomposisiWorkorder/$prodID",//untuk per fase
                "addFaseProdukKomposisiBiayaLink" => MODUL_PATH . get_class($this) . "/addData/MdlProjectKomposisiWorkorder/$prodID",//untuk per fase
                "addFaseProdukKomposisiTimLink" => MODUL_PATH . get_class($this) . "/addData/MdlTimWorkProject/$prodID",//untuk per fase
                "addFaseHasilProduksi" => MODUL_PATH . get_class($this) . "/addData/MdlProjectKomposisiWorkorder/$prodID",//supplies wip ke tabel produk denga tipe item_wip
                "newData" => isset($_SESSION["NEW"]) ? $_SESSION["NEW"] : array(),
                "result" => $iframeTarget,
                "allowedAccess" => $allowedAdd,
                "previewLink" => MODUL_PATH . get_class($this) . "/preview/",
            );
            // $this->load->view("editor", $data);
            $this->load->view("data", $data);
        }
        else {
            //diredirect ke tasklist
            echo "<script>top.location.href='" . MODUL_PATH . get_class($this) . "/tasklist/$prodID';</script>";
            // redirect(MODUL_PATH .get_class($this) ."/tasklist/$prodID");
            die();
        }


    }

    public function index()
    {
        $pid = $prodID = $this->uri->segment(4);
        $iframeTarget = isset($_GET["iframe"]) ? $_GET["iframe"] : (isset($_POST["iframe"]) ? $_POST["iframe"] : "result");
        $_SESSION['MasterData']['show_nilai'] = isset($_GET['shownilai']) ? $_GET['shownilai'] : 1;
        $non = $this->nonView;

        if (count($this->customAccesProject) > 0 && in_array("MdlTimWorkProject", $this->customAccesProject["create"])) {

            $this->load->model("Mdls/MdlCabangProduksi");
            $this->load->model("Mdls/MdlProdukProject");
            $this->load->model("Mdls/MdlProjectWorkOrder");
            $this->load->model("Mdls/MdlProdukRakitanPreBiaya");
            $this->load->model("Mdls/MdlProjectKomposisi");
            $this->load->model("Mdls/MdlProjectKomposisiWorkorder");
            $this->load->model("Mdls/MdlProjectKomposisiWorkorderSub");
            $this->load->model("Mdls/MdlHargaProduk");
            $this->load->model("Mdls/MdlDtaBiayaProduksi");
            $this->load->model("Mdls/MdlSatuan");
            $this->load->model("Mdls/MdlTimWorkProject");
            $this->load->model("Mdls/MdlEmployee_all");
            $this->load->model("Mdls/MdlProdukKomposisiPaket");
            $this->load->model("Mdls/MdlProjectProdukPaket");


            $o = new MdlProdukProject();
            $pk = new MdlProjectKomposisi();
            $ob = new MdlProdukRakitanPreBiaya();
            $kf = new MdlProjectKomposisiWorkorder();
            $kfs = new MdlProjectKomposisiWorkorderSub();
            $pf = new MdlProjectWorkOrder();
            $bp = new MdlDtaBiayaProduksi();
            $st = new MdlSatuan();
            $t = new MdlTimWorkProject();
            $e = new MdlEmployee_all();
            $pkg = new MdlProdukKomposisiPaket();
            $pp  = new MdlProjectProdukPaket();

            unset($_SESSION['PROED'][$prodID]);

            //pembuat session untuk keperluan ui
            if (!isset($_SESSION['PROED'][$prodID])) {
                $_SESSION['PROED'][$prodID] = array();
            }
            if (!isset($_SESSION['PROED'][$prodID]['component_sum'])) {
                $_SESSION['PROED'][$prodID]['component_sum'] = array();
            }
            if (!isset($_SESSION['PROED'][$prodID]['gudang'])) {
                $_SESSION['PROED'][$prodID]['gudang'] = array();
            }
            if (!isset($_SESSION['PROED'][$prodID]['component'])) {
                $_SESSION['PROED'][$prodID]['component'] = array();
            }
            if (!isset($_SESSION['PROED'][$prodID]['component_sub'])) {
                $_SESSION['PROED'][$prodID]['component_sub'] = array();
            }
            if (!isset($_SESSION['PROED'][$prodID]['component_bom'])) {
                $_SESSION['PROED'][$prodID]['component_bom'] = array();
            }
            if (!isset($_SESSION['PROED'][$prodID]['fase'])) {
                $_SESSION['PROED'][$prodID]['fase'] = array();
            }
            if (!isset($_SESSION['PROED'][$prodID]['gudang_fase'])) {
                $_SESSION['PROED'][$prodID]['gudang_fase'] = array();
            }

            $_SESSION['PROED'][$prodID]['backLink'] = isset($_GET['backLink']) ? unserialize(base64_decode($_GET['backLink'])) : "";

            $o->addFilter("id='$pid'");
            $tempProdukMaster = $o->lookUpAll()->result();
            cekHitam("==========MASTER PRODUK PROJECT=========");
            arrPrintWebs($tempProdukMaster);
            cekHitam( json_encode($tempProdukMaster) );
            $showFields = $o->getListedFields();
            cekHitam( $this->db->last_query() );
            cekHitam( __LINE__ );

            $dataMaster = array();
            if (count($tempProdukMaster) > 0) {
                foreach ($tempProdukMaster as $tempProdukMaster_0) {
                    foreach ($showFields as $kolom => $kolomLabel) {
                        $dataMaster[$kolom] = $tempProdukMaster_0->$kolom;
                    }
                }
            }

            $lock = "";
            $no_kontrak = "";
            $nilai_proyek = "";
            $batas_waktu = "";

            foreach ($showFields as $key => $label) {
                switch ($key) {
                    case "kode":
                        $no_kontrak = $dataMaster[$key];
                        break;
                    case "harga":
                        $nilai_proyek = $dataMaster[$key];
                        break;
                    case "end_dtime":
                        $batas_waktu = $dataMaster[$key];
                        break;
                    case "lock":
                        $lock = $dataMaster[$key];
                        break;
                }
            }

            //harga
            $s = new MdlHargaProduk;
            $s->addFilter("cabang_id='-1'");
            $s->addFilter("status='1'");
            $tempHarga = $s->lookUpAll()->result();
            $arrPrice = array();
            if (!empty($tempHarga)) {
                foreach ($tempHarga as $k => $prcData) {
                    $arrPrice[$prcData->produk_id][$prcData->jenis_value] = $prcData->nilai;
                }
            }

            //produk komposisi sumary
            $pk->setFilters(array());
            $pk->addFilter("produk_id='$pid'");
            $pk->addFilter("jenis in ('produk','biaya','item_komposit')");
            $this->db->order_by("jenis", "desc");
            $tempProduk = $pk->lookUpAll()->result();
//            showLast_query("biru");
            $sumarryProjectLabel = $pk->getListedFields();

            $sumarryProject = array();
            if (sizeof($tempProduk) > 0) {
                foreach ($tempProduk as $i => $temproduk_0) {
//                    arrPrint($temproduk_0);
                    foreach ($sumarryProjectLabel as $key => $label) {
                        $sumarryProject[$temproduk_0->jenis][$i][$key] = $temproduk_0->$key;
                    }
                    if ($temproduk_0->jenis == "biaya") {
                        unset($sumarryProject["biaya"][$i]["jml"]);
                        unset($sumarryProject["biaya"][$i]["harga"]);
                    }
                    $sumarryProject[$temproduk_0->jenis][$i]["subtotal"] = $temproduk_0->harga * $temproduk_0->jml;

                    foreach ($temproduk_0 as $k => $v) {
                        $_SESSION['PROED'][$prodID]['component_sum'][$temproduk_0->jenis][$temproduk_0->produk_dasar_id][$k] = $v;
                    }
                }
            }
//arrPrintHijau($_SESSION['PROED'][$prodID]['component_sum']);
//arrPrintHijau($sumarryProject);
//            matiHere(__LINE__);
            $kf->setFilters(array());
            $kf->addFilter("produk_id='$pid'");
            $kf->addFilter("trash='0'");
            $kf->addFilter("status='1'");
            $this->db->order_by("id", "ASC");
            $tempProdukFaseKomposisi = $kf->lookUpAll()->result();
            cekMerah( $this->db->last_query() );
            cekMerah( __LINE__ );
            $pf->setFilters(array());
            $pf->addFilter("produk_id='$pid'");
            $pf->addFilter("status='1'");
            $pf->addFilter("trash='0'");
            $this->db->order_by("id", "ASC");
            $tempProdukFase = $pf->lookUpAll()->result();
            cekHijau( $this->db->last_query() );
            cekHijau( __LINE__ );

            $kfs->setFilters(array());
            $kfs->addFilter("produk_id='$pid'");
            $kfs->addFilter("jenis_transaksi=5582");
            $kfs->addFilter("trash='0'");
            $kfs->addFilter("status='1'");
            $this->db->order_by("id", "ASC");
            $tempProdukFaseKomposisiSub = $kfs->lookUpAll()->result();

            //produk bom list
            $pkg->setFilters(array());
            $pkg->addFilter("project_id='$pid'");
            $pkg->addFilter("trash='0'");
            $pkg->addFilter("status='1'");
            $this->db->order_by("id", "ASC");
            $tempProdukKomposisiPaket = $pkg->lookUpAll()->result();

            if (count($tempProdukKomposisiPaket) > 0) {
                if (count($this->customAccesProject) > 0 && in_array("MdlProdukKomposisiPaket", $this->customAccesProject["delete"])) {
                    $allowedAdd["produk_paket"]["delete"] = true;
                }
                foreach ($tempProdukKomposisiPaket as $ii => $iiData) {
                    if (!isset($iiData->subtotal)) {
                        $iiData->subtotal = $iiData->jml * $iiData->harga;
                    }
                    if ($iiData->jenis == "produk" || $iiData->jenis == "jual" || $iiData->jenis == "room") {
                        $produkKomposisiPaket[$iiData->produk_id][$iiData->jenis][$iiData->produk_dasar_id]["subtotal"] = $iiData->jml * $iiData->harga;
                        $produkKomposisiPaket[$iiData->produk_id][$iiData->jenis][$iiData->produk_dasar_id] = (array)$iiData;
                        $produkKomposisiPaket[$iiData->produk_id][$iiData->jenis][$iiData->produk_dasar_id]['satuan'] = $iiData->satuan_nama;
                        $produkKomposisiPaket[$iiData->produk_id][$iiData->jenis][$iiData->produk_dasar_id]['jual'] = $iiData->nilai;
                    }
                    if ($iiData->jenis == "biaya") {
                        $produkKomposisiPaket[$iiData->produk_id][$iiData->jenis][$iiData->id] = (array)$iiData;
                        $produkKomposisiPaket[$iiData->produk_id][$iiData->jenis][$iiData->id]['satuan'] = $iiData->satuan_nama;
                    }
                }
                $_SESSION['PROED'][$prodID]['component_bom'] = $produkKomposisiPaket;
            }
            else {
                $produkKomposisiPaket = array();
            }

            //region biaya biaya
            $tempBiaya = $ob->lookUpAll()->result();
            $prebiaya = array();
            if (sizeof($tempBiaya) > 0) {
                foreach ($tempBiaya as $ic => $biayaData) {
                    $prebiaya["cat_id"][$biayaData->id] = (array)$biayaData;
                }
            }
            //endregion

            //region timwork
            $allEmployee = $e->lookUpAll()->result();
            $allowedAdd = array();
            if (count($allEmployee) > 0) {
                //bagian tombol-tombol crud
                if (count($this->customAccesProject) > 0 && in_array("MdlTimWorkProject", $this->customAccesProject["create"])) {
                    $allowedAdd["employee_id"]["create"] = true;
                }
                if (count($this->customAccesProject) > 0 && in_array("MdlTimWorkProject", $this->customAccesProject["update"])) {
                    $allowedAdd["employee_id"]["update"] = true;
                }
                if (count($this->customAccesProject) > 0 && in_array("MdlTimWorkProject", $this->customAccesProject["view"])) {
                    $allowedAdd["employee_id"]["view"] = true;
                }
                if (count($this->customAccesProject) > 0 && in_array("MdlTimWorkProject", $this->customAccesProject["delete"])) {
                    $allowedAdd["employee_id"]["delete"] = true;
                }
                foreach ($allEmployee as $allEmployee_0) {
                    $preEmployee["employee_id"][$allEmployee_0->id] = (array)$allEmployee_0;
                }
            }

            $tempRelHakAkses = aksesProject();
            $preHakAkses = array();
            if (count($tempRelHakAkses) > 0) {
                foreach ($tempRelHakAkses as $tempRelHakAkses_0) {
                    $preEmployee["akses_id"][$tempRelHakAkses_0["id"]] = $tempRelHakAkses_0;
                }
            }
            $t->addFilter("produk_id='$prodID'");
            $tmpTimwork = $t->lookUpAll()->result();
            $timWork = array();
            $preTimwork = array();
            $fieldsTimLabel = $t->getListedFields();
            if (count($tmpTimwork) > 0) {
                foreach ($tmpTimwork as $timWork_0) {
                    $tmp = array();
                    foreach ($fieldsTimLabel as $key => $label) {
                        $tmp[$key] = $timWork_0->$key;
                    }
                    $timWork[$timWork_0->id] = $tmp;
                    $preTimwork["employee_id"][$timWork_0->id] = (array)$timWork_0;
                }
            }
            //endregion
arrPrint($tempProdukFase);
            cekHitam(__LINE__);
            //region komposisi work order
            $produkFase = array();
            $fields = $pf->getListedFields();
            if (sizeof($tempProdukFase) > 0) {
                if ($lock == 0) {
                    if (count($this->customAccesProject) > 0 && in_array("MdlProjectWorkOrder", $this->customAccesProject["create"])) {
                        $allowedAdd["fase_id"]["create"] = true;
                    }
                    if (count($this->customAccesProject) > 0 && in_array("MdlProjectWorkOrder", $this->customAccesProject["update"])) {
                        $allowedAdd["fase_id"]["update"] = true;
                    }
                    if (count($this->customAccesProject) > 0 && in_array("MdlProjectWorkOrder", $this->customAccesProject["view"])) {
                        $allowedAdd["fase_id"]["view"] = true;
                    }
                    if (count($this->customAccesProject) > 0 && in_array("MdlProjectWorkOrder", $this->customAccesProject["delete"])) {
                        $allowedAdd["fase_id"]["delete"] = true;
                    }
                }
                foreach ($tempProdukFase as $data) {
                    foreach ($fields as $field => $fieldAlias) {
                        $produkFase[$data->id][$field] = isset($data->$field) ? $data->$field : "";
                    }
                    if (isset($data->qty) && $data->qty > 0) {
                        for ($di = 1; $di <= $data->qty; $di++) {
                            $produkFase[$data->id]['details'][$di] = array(
                                "idx" => $data->produk_id . $data->id . $di,
                                "nama" => $data->nama . " (" . $di . ")",
                            );
                        }
                    }
                }
                $_SESSION['PROED'][$prodID]['fase'] = $produkFase;
            }
            else {
                $produkFase = array();
                $_SESSION['PROED'][$prodID]['fase'] = array();
                cekLime("belum ada relasi produk fase");
//                $non = 1; // biasanya jadi 2 cok
            }
            //endregion

            //region daftar paket project
            $pp->setFilters(array());
            $pp->addFilter("project_id='$pid'");
            $pp->addFilter("trash='0'");
            $pp->addFilter("status='1'");
            $this->db->order_by("id", "ASC");
            $tempProdukPaket = $pp->lookUpAll()->result();

            if (count($tempProdukPaket) > 0) {
                if (count($this->customAccesProject) > 0 && in_array("MdlProdukKomposisiPaket", $this->customAccesProject["delete"])) {
                    $allowedAdd["produk_paket"]["delete"] = true;
                }
                foreach ($tempProdukPaket as $ii => $iiData) {
                    $produkPaket[$iiData->id] = $iiData;
                }
                $_SESSION['PROED'][$prodID]['produk_paket'] = $produkPaket;
            }
            else {
                $produkPaket = array();

                $_SESSION['PROED'][$prodID]['produk_paket'] = $produkPaket;
            }

            //region daftar paket project
            //region biaya
            $tempBiayaProduksi = $bp->lookUpAll()->result();
            if (sizeof($tempBiayaProduksi)) {
                foreach ($tempBiayaProduksi as $ixb => $tempBiayaProduksi_0) {
                    $prebiaya["produk_dasar_id"][$tempBiayaProduksi_0->id] = (array)$tempBiayaProduksi_0;
                }
            }
            $tempSatuan = $st->lookUpAll()->result();
            if (sizeof($tempSatuan) > 0) {
                foreach ($tempSatuan as $tempSatuan_0) {
                    $prebiaya["satuan_id"][$tempSatuan_0->id] = (array)$tempSatuan_0;
                }
            }
            //endregion

            //biaya details reguler
            $this->load->model("Mdls/" . "MdlProjectKomponenBiayaDetails");
            $by = new MdlProjectKomponenBiayaDetails();
            $arrTmpByDetails = $by->lookUpAll()->result();
            $arrByDetails = array();
            if(!empty($arrTmpByDetails)){
                foreach($arrTmpByDetails as $byData){
                    $by_id = $byData->biaya_id;
                    $by_dsr_id = $byData->biaya_dasar_id;
                    $arrByDetails[$by_id][$by_dsr_id] = (array)$byData;
                    $arrByDetails[$by_id][$by_dsr_id]["subtotal"] = $byData->jml * $byData->harga;
                }
            }
            //biaya details reguler

            //biaya details existing
            $this->load->model("Mdls/" . "MdlProjectKomponenBiayaDetailsRab");
            $byrab = new MdlProjectKomponenBiayaDetailsRab();
            $byrab->addFilter("project_id='$pid'");
            $arrTmpByDetailsRab = $byrab->lookUpAll()->result();
            $arrByDetailsRab = array();
            if(!empty($arrTmpByDetailsRab)){
                foreach($arrTmpByDetailsRab as $byData){
                    $by_id = $byData->biaya_id;
                    $by_dsr_id = $byData->biaya_dasar_id;
                    $arrByDetailsRab[$by_id][$by_dsr_id] = (array)$byData;
                    $arrByDetailsRab[$by_id][$by_dsr_id]["subtotal"] = $byData->jml * $byData->harga;
                }
            }
            //biaya details existing

            //relasi jadi option atau input
            $arrRel = array();
            $priceList = array();
            $produkKomposisTarget = array();
            $produkKomposisJual = array();
            $produkKomposisiFaseSub = array();
            $FaseSubNama = array();
            if (count($tempProdukFaseKomposisi) > 0) {
                if ($lock == 0) {
                    if (count($this->customAccesProject) > 0 && in_array("MdlProjectKomposisiWorkorder", $this->customAccesProject["create"])) {
                        $allowedAdd["produk_dasar_id"]["create"] = true;
                    }
                    if (count($this->customAccesProject) > 0 && in_array("MdlProjectKomposisiWorkorder", $this->customAccesProject["update"])) {
                        $allowedAdd["produk_dasar_id"]["update"] = true;
                    }
                    if (count($this->customAccesProject) > 0 && in_array("MdlProjectKomposisiWorkorder", $this->customAccesProject["view"])) {
                        $allowedAdd["produk_dasar_id"]["view"] = true;
                    }
                    if (count($this->customAccesProject) > 0 && in_array("MdlProjectKomposisiWorkorder", $this->customAccesProject["delete"])) {
                        $allowedAdd["produk_dasar_id"]["delete"] = true;
                    }
                }
//                arrPrintHitam($tempProdukFaseKomposisi);
                foreach ($tempProdukFaseKomposisi as $ii => $iiData) {
                    if ($iiData->jenis == "room") {
                        $iiData->produk_dasar_id = $iiData->room_id;
                    }
                    $priceList[$iiData->fase_id][$iiData->produk_dasar_id] = array(
                        "harga_ori" => $iiData->nilai,
                        "harga_bom" => $iiData->harga,
                    );
                    if (!isset($iiData->subtotal)) {
                        $iiData->subtotal = $iiData->jml * $iiData->harga;
                    }
                    if ($iiData->jenis == "produk" || $iiData->jenis == "jual" || $iiData->jenis == "room" || $iiData->jenis == "item_komposit") {
                        cekhitam($iiData->produk_dasar_id . " == " . $iiData->produk_dasar_nama);
                        $produkKomposisiFase[$iiData->fase_id][$iiData->jenis][$iiData->produk_dasar_id]["subtotal"] = $iiData->jml * $iiData->harga;
                        $produkKomposisiFase[$iiData->fase_id][$iiData->jenis][$iiData->produk_dasar_id] = (array)$iiData;
                        $produkKomposisiFase[$iiData->fase_id][$iiData->jenis][$iiData->produk_dasar_id]["jual"] = isset($arrPrice[$iiData->produk_dasar_id]['jual']) ? $arrPrice[$iiData->produk_dasar_id]['jual'] : 0;
                        $produkKomposisiFase[$iiData->fase_id][$iiData->jenis][$iiData->produk_dasar_id]["ppv"] = isset($arrPrice[$iiData->produk_dasar_id]['hpp_nppv']) ? $arrPrice[$iiData->produk_dasar_id]['hpp_nppv'] : 0;
                    }
                    if ($iiData->jenis == "target") {
                        $produkKomposisTarget[$prodID][$iiData->fase_id] = (array)$iiData;
                    }
                    if ($iiData->jenis == "jual") {
                        $produkKomposisJual[$prodID][$iiData->fase_id] = (array)$iiData;
                    }
                    if ($iiData->jenis == "produk_room") {
                        unset($produkKomposisiFase[$iiData->fase_id][$iiData->jenis][$iiData->produk_dasar_id]);
                        $produkKomposisiFase[$iiData->fase_id][$iiData->jenis][$iiData->room_id][$iiData->produk_dasar_id] = (array)$iiData;
                        $produkKomposisiFase[$iiData->fase_id][$iiData->jenis][$iiData->room_id][$iiData->produk_dasar_id]['hpp'] = $iiData->hrg_hpp;
                        $produkKomposisiFase[$iiData->fase_id][$iiData->jenis][$iiData->room_id][$iiData->produk_dasar_id]['ppv'] = $iiData->hrg_ppv;
                        $produkKomposisiFase[$iiData->fase_id][$iiData->jenis][$iiData->room_id][$iiData->produk_dasar_id]['jual'] = $iiData->hrg_jual;
                    }
                    if ($iiData->jenis == "biaya_room") {
                        $produkKomposisiFase[$iiData->fase_id][$iiData->jenis][$iiData->room_id][$iiData->produk_dasar_id] = (array)$iiData;
                    }
                    if ($iiData->jenis == "biaya") {
                        $produkKomposisiFase[$iiData->fase_id][$iiData->jenis][$iiData->id] = (array)$iiData;
                        if(isset($arrByDetailsRab[$iiData->produk_dasar_id])){
                            $produkKomposisiFase[$iiData->fase_id]["biaya_details"][$iiData->id] = $arrByDetailsRab[$iiData->produk_dasar_id];
                    }

//                        $produkKomposisiFase[$iiData->fase_id][$iiData->jenis][$iiData->id] = (array)$iiData;
//                        $produkKomposisiFase[$iiData->fase_id]["biaya_details"][$iiData->id] = $arrByDetails[$iiData->produk_dasar_id];
                }
                }
//                arrPrintWebs($produkKomposisiFase);
                $_SESSION['PROED'][$prodID]['component'] = $produkKomposisiFase;
                //SUB WO
                foreach ($tempProdukFaseKomposisiSub as $ii => $iiDataSub) {
                    $priceList[$iiDataSub->fase_id][$iiDataSub->produk_dasar_id] = array(
                        "harga_ori" => $iiDataSub->nilai,
                        "harga_bom" => $iiDataSub->harga,
                    );
                    if (!isset($iiDataSub->subtotal)) {
                        $iiDataSub->subtotal = $iiDataSub->jml * $iiDataSub->harga;
                    }
                    if ($iiDataSub->jenis == "produk" || $iiDataSub->jenis == "item_komposit") {
                        $produkKomposisiFaseSub[$iiDataSub->fase_id][$iiDataSub->sub_fase_id][$iiDataSub->jenis][$iiDataSub->produk_dasar_id]["subtotal"] = $iiDataSub->jml * $iiDataSub->harga;
                        $produkKomposisiFaseSub[$iiDataSub->fase_id][$iiDataSub->sub_fase_id][$iiDataSub->jenis][$iiDataSub->produk_dasar_id] = (array)$iiDataSub;
                        $produkKomposisiFaseSub[$iiDataSub->fase_id][$iiDataSub->sub_fase_id][$iiDataSub->jenis][$iiDataSub->produk_dasar_id]["jual"] = isset($arrPrice[$iiDataSub->produk_dasar_id]['jual']) ? $arrPrice[$iiDataSub->produk_dasar_id]['jual'] : 0;
                        $produkKomposisiFaseSub[$iiDataSub->fase_id][$iiDataSub->sub_fase_id][$iiDataSub->jenis][$iiDataSub->produk_dasar_id]["ppv"] = isset($arrPrice[$iiDataSub->produk_dasar_id]['hpp_nppv']) ? $arrPrice[$iiDataSub->produk_dasar_id]['hpp_nppv'] : 0;
                    }
                    if ($iiDataSub->jenis == "target") {
                        $produkKomposisTargetSub[$prodID][$iiDataSub->fase_id][$iiDataSub->sub_fase_id] = (array)$iiDataSub;
                    }
                    if ($iiDataSub->jenis == "biaya") {
                        $produkKomposisiFaseSub[$iiDataSub->fase_id][$iiDataSub->sub_fase_id][$iiDataSub->jenis][$iiDataSub->id] = (array)$iiDataSub;
                    }
                    $FaseSubNama[$iiDataSub->fase_id][$iiDataSub->sub_fase_id] = $iiDataSub->sub_fase_nama;
                }
                $_SESSION['PROED'][$prodID]['component_sub'] = $produkKomposisiFaseSub;
                $_SESSION['PROED'][$prodID]['sub_fase_nama'] = $FaseSubNama;
            }
            else {
                $produkKomposisiFase = array();
            }
            //relasi jadi option atau input

            //relasi produk bom
            //relasi produk bom

            //KHUSUS UNTUK $allowedAccess
            $_SESSION['PROED'][$prodID]['allowedAccess'] = $allowedAdd;
            //KHUSUS UNTUK $allowedAccess

            $fieldFormProdukKomposisiFase = $kf->getFields();
            $relationDataProdukKomposisiFase = array();
            $relSupplies = array();
            foreach ($fieldFormProdukKomposisiFase as $keyID => $temp0) {
                if ($temp0["inputType"] == "combo" && isset($temp0["reference"])) {
                    $preKey = $temp0["inputType"];
                    $preKey = $temp0["kolom"];
                    $preModel = $temp0["reference"];
                    if (isset($temp0["reference"])) {
                        $this->load->model("Mdls/" . $preModel);
                        $p = new $preModel();
                        if($preModel=="MdlProdukForProject"){
//                            $this->db->where("jenis='project' AND status='1' AND trash='0'");
                            $this->db->where("jenis='item' AND status='1' AND trash='0' AND allow_project='1'");
                            $this->db->or_where("jenis='project' AND status='1' AND trash='0'");
//                            $this->db->or_where("jenis='item' AND status='1' AND trash='0' AND allow_project='1'");
                        }
                        $tempDatas = $p->lookUpAll()->result();
                        if (sizeof($tempDatas) > 0) {
                            foreach ($tempDatas as $datas_0) {
                                $relSupplies[$preKey][$datas_0->id] = (array)$datas_0;
                                $reg = isset($datas_0->allow_project) && $datas_0->allow_project == 1 ? "| reguler" : "| project";
                                if(isset($datas_0->kategori_id) && $datas_0->kategori_id == 1){
                                    $relSupplies[$preKey][$datas_0->id]['nama'] = $datas_0->nama . " | ". $datas_0->kapasitas_nama ." $reg";
                                }
                                else{
                                    $relSupplies[$preKey][$datas_0->id]['nama'] = $datas_0->nama . " $reg";
                                }
                            }
                        }
                    }
                }
            }

            /*
             * hirarki builder produk
             * produk[produk_id][fase_id] =array(
             * array(
             * )
             * )
             */
            $produkKomposisi = array();//untuk summary semua fase di simpan di komposisi produk
            $fieldsTimLabel = array(
                "employee_id" => "nama",
                "akses_id" => "hak akses",
            );
            $produkFaseHeader = array(
                // "urut" => "urutan pekerjaan",
                "qty" => "jumlah",
                "nama" => "Rencana Kerja",
                "lokasi" => "lokasi",
                "keterangan" => "Keterangan",
//                "employee_id" => "penanggung jawab",
                // "daftar_tugas" => "daftar tugas",
            );
            $relSuppliesHeader = array(
                "cat_id" => "cat_nama",
                "satuan_id" => "satuan",
                "produk_dasar_id" => "produk_dasar_nama",
                "harga_ori" => "harga",
                "harga_bom" => "harga_bom",
                "employee_id" => "employee_nama",
                "akses_id" => "hak_akses_nama",
                // "harga_bom"=>"harga_bom",
            );
            $relTimHeader = array(
                "employee_id" => "tim",
                "produk_dasar_id" => "produk_dasar_nama",
                "akses" => "hak_akses",
                // "harga_bom"       => "harga_bom",
                // "harga_bom"=>"harga_bom",
            );
            $produkKomposisiFaseHeader = array(
                "produk" => array(
                    "produk_dasar_id" => "Material",
                    "satuan" => "UoM",
//                    "nilai" => "H.HPP",
//                    "ppv" => "H.PPV",
                    "jual" => "H.BELI",
                    "harga" => "H.JUAL",
                    "jml" => "Jml",
                    "subtotal" => "Subtotal",
                ),
                "biaya" => array(
                    "produk_dasar_id" => "Jasa",
                    "cat_id" => "Kategori biaya",
                    "satuan_id" => "satuan",
                    "keterangan" => "ket",
                    // "harga_ori"       => "harga standar",
                    "harga" => "Harga",
                    "jml" => "Jml",
                    "subtotal" => "Subtotal",
                ),
                // "target" => array(
                //     "produk_dasar_id" => "Produk fase(wip)",
                //     // "gudang2_id"         => "fase proses lanjut*",
                // ),
                // "timwork" => array(
                //     "employee_id" => "nama",
                //     "akses_id" => "hak akses",
                //     // "gudang2_id"         => "fase proses lanjut*",
                // ),
                "jual" => array(
                    "nilai" => "BOM",
                    "harga" => "JUAL",
                    "jml" => "Jml",
                    "subtotal" => "Subtotal",
                ),
                "room" => array(
                    "room_nama" => "Nama Ruang",
                    "room_ket" => "Keterangan",
                ),
                "produk_room" => array(
                    "produk_dasar_id" => "Material",
                    "satuan" => "UoM",
//                    "nilai" => "H.HPP",
//                    "ppv" => "H.PPV",
                    "jual" => "H.BELI",
                    "harga" => "H.JUAL",
                    "avail" => "Jml Avail",
                    "jml" => "Jml",
                    "subtotal" => "Subtotal",
                ),
                "biaya_room" => array(
                    "produk_dasar_id" => "Jasa",
                    "satuan_id" => "satuan",
                    "cat_id" => "Kategori biaya",
                    // "harga_ori"       => "harga standar",
                    "harga" => "Harga",
                    "avail" => "Jml Avail",
                    "jml" => "Jml",
                    "subtotal" => "Subtotal",
                ),
            );
            $produkKomposisiFaseHeaderMini = array(
                "produk" => array(
                    "produk_dasar_id" => "BB",
                    "satuan" => "UOM",
                    "nilai" => "HPP",
                    "ppv" => "PPV",
                    "jual" => "J.STD",
                    "harga" => "HA",
                    "jml" => "JML",
                    "subtotal" => "SUB",
                ),
                "biaya" => array(
                    "produk_dasar_id" => "JASA",
                    "cat_id" => "CAT",
                    "keterangan" => "ket",
                    "satuan_id" => "UOM",
                    "harga" => "HRG",
                    "jml" => "JML",
                    "subtotal" => "SUB",
                ),
            );
            $produkKomposisiFaseEditable = array(
                "room_nama" => "room_nama",
                "room_ket" => "room_ket",
                "produk_dasar_id" => "produk_dasar_id",
//                "cat_id" => "cat_id",
                "keterangan" => "keterangan",
                "satuan_id" => "satuan_id",
                "harga" => "harga",
                "jml" => "jml",
                "fase_id" => "fase_id",
                "employee_id" => "employee_id",
                "akses_id" => "akses_id",
            );
            $produkKomposisiFaseRoomEditable = array(
                "produk_dasar_id" => "produk_dasar_id",
                "jml" => "jml",
            );
            $masterLabel = array(
                "produk" => "Material",
                "biaya" => "Jasa",
            );
            $sumarryProjectLabel = $sumarryProjectLabel + array("subtotal" => "subtotal");
            $data = array(
                "mode" => "master_project",
                "masterProjectField" => $showFields,
                "masterProject" => $dataMaster,
                "sumaryProjectLabel" => $sumarryProjectLabel,
                "sumaryProject" => $sumarryProject,
                "masterLabel" => $masterLabel,
                "masterTimworkLabel" => $fieldsTimLabel,
                "masterTimwork" => $timWork,
                "produkID" => $prodID,
                "produkNama" => $tempProdukMaster[0]->nama,
//                "produk_komposisi" => $produkKomposisi,
                "produk_fase" => $produkFase,
                "produk_paket" => $produkPaket,
//                "produk_fase_sub" => $produkFaseSub,
                "produk_komposisi_fase" => $_SESSION['PROED'][$prodID]['component'],
                "produk_komposisi_fase_sub" => $_SESSION['PROED'][$prodID]['component_sub'],
                "produk_komposisi_bom" => $_SESSION['PROED'][$prodID]['component_bom'],
                "sub_fase_nama" => isset($_SESSION['PROED'][$prodID]['sub_fase_nama']) ? $_SESSION['PROED'][$prodID]['sub_fase_nama'] : "",
                "selector" => MODUL_PATH . get_class($this) . "/addSession/PROED/",
                //bagian header
                "produk_fase_header" => $produkFaseHeader,
                "produk_komposisi_fase_header" => $produkKomposisiFaseHeader,
                "produk_komposisi_fase_header_mini" => $produkKomposisiFaseHeaderMini,
                "produk_fase_komposisiEditable" => $produkKomposisiFaseEditable,
                "produk_fase_komposisiRoomEditable" => $produkKomposisiFaseRoomEditable,
                "relSupplies" => $relSupplies,
                "relBiaya" => $prebiaya,
                "relEmployee" => $preEmployee,
                "relWorkOrderEmployee" => $preTimwork,
                // "relHakAkses"                        => $preHakAkses,
                "relTarget" => array(),
                "currentTargetWip" => $produkKomposisTarget,
                "currentTargetJual" => $produkKomposisJual,
                "relSuppliesHeader" => $relSuppliesHeader,
                // "relbiayaProduksi"=>$prebiayaProduksi,
                "addProdukKomposisiLink" => MODUL_PATH . get_class($this) . "/addData/MdlProjectKomposisi/$prodID",//untuk per produk(sumary)
                "addProdukKomposisiBiayaLink" => MODUL_PATH . get_class($this) . "/addData/MdlProjectKomposisiWorkorder/$prodID",//untuk per produk(summary)
                "saveJualProject" => MODUL_PATH . get_class($this) . "/addData/MdlProjectKomposisiWorkorder/$prodID",//untuk per produk(summary)
                "addFaseProdukLink" => MODUL_PATH . get_class($this) . "/addData/MdlProjectWorkOrder/$prodID",//ke tabel produk fase
                "addFaseProdukKomposisiLink" => MODUL_PATH . get_class($this) . "/addData/MdlProjectKomposisiWorkorder/$prodID",//untuk per fase
                "addFaseProdukKomposisiBomLink" => MODUL_PATH . get_class($this) . "/addData/MdlProdukKomposisiPaket/$prodID",//untuk per fase
                "addFaseProdukKomposisiSubLink" => MODUL_PATH . get_class($this) . "/addData/MdlProjectKomposisiWorkorderSub/$prodID",//untuk per fase
                "addFaseProdukKomposisiBiayaLink" => MODUL_PATH . get_class($this) . "/addData/MdlProjectKomposisiWorkorder/$prodID",//untuk per fase
                "addFaseProdukKomposisiBomBiayaLink" => MODUL_PATH . get_class($this) . "/addData/MdlProdukKomposisiPaket/$prodID",//untuk per fase
                "addFaseProdukKomposisiBiayaSubLink" => MODUL_PATH . get_class($this) . "/addData/MdlProjectKomposisiWorkorderSub/$prodID",//untuk per fase
                "addFaseProdukKomposisiTimLink" => MODUL_PATH . get_class($this) . "/addData/MdlTimWorkProject/$prodID",//untuk per fase
                "addFaseHasilProduksi" => MODUL_PATH . get_class($this) . "/addData/MdlProjectKomposisiWorkorder/$prodID",//supplies wip ke tabel produk denga tipe item_wip
                "newData" => isset($_SESSION["NEW"]) ? $_SESSION["NEW"] : array(),
                "result" => $iframeTarget,
                "allowedAccess" => $allowedAdd,
                "previewLink" => MODUL_PATH . get_class($this) . "/preview/",
                "deleteLink" => MODUL_PATH . get_class($this) . "/delete/",
                "modulClassLink" => MODUL_PATH . get_class($this),
//                "showKomposisiProdukFase" => $this->showKomposisiProdukFase(),
            );
            if ($non == 0) {
                $this->load->view("data", $data);
            }
        }
        else {
            //diredirect ke tasklist
            echo "<script>top.location.href='" . MODUL_PATH . get_class($this) . "/tasklist/$prodID';</script>";
            // redirect(MODUL_PATH .get_class($this) ."/tasklist/$prodID");
            die();
        }
    }

    public function addSession()
    {
        $prodID = $this->uri->segment(5);
        $fase_urut = $this->uri->segment(6);
        $sub_fase_id = $this->uri->segment(7);
        $masterSes = $this->uri->segment(3);
        $key = $_GET["key"];
        $value = $_GET["value"];
        $modes = $_GET["mode"];

        switch ($_GET["mode"]) {
            case "project_timwork":
                //tim work per project dari internal tim/ambil dari data employeee
                break;
            case "produk_komposisi_fase_room":
                $selID = $_GET["selID"];
                if (!isset($_SESSION["NEW"]["produk_komposisi_fase_room"][$fase_urut][$selID][$prodID])) {
                    unset($_SESSION["NEW"]["produk_komposisi_fase_room"][$fase_urut][$selID]);
                }
                $_SESSION["NEW"]["produk_komposisi_fase_room"][$fase_urut][$selID][$prodID][$key] = $value;
                //getRoomNama
                $this->load->model("Mdls/" . "MdlProjectKomposisiWorkorderRoom");
                $pkw = new MdlProjectKomposisiWorkorderRoom();
                $pkw->addFilter("status='1'");
                $pkw->addFilter("trash='0'");
                $pkw->addFilter("room_id='$selID'");
                $pkwRoom = $pkw->lookupAll()->result();
                $pkwQuery = $this->db->last_query();
                $roomData = array();
                $roomProduk = array();
                foreach ($pkwRoom as $k => $pkwData) {
                    switch ($pkwData->jenis) {
                        case "room":
                            $roomData[$pkwData->room_id] = $pkwData;
                            break;
                    }
                }
                if ($key == "produk_dasar_id") {
                    $this->load->model("Mdls/" . "MdlProjectKomposisiWorkorderRoomProduk");
                    $pkwP = new MdlProjectKomposisiWorkorderRoomProduk();
                    $pkwP->addFilter("status='1'");
                    $pkwP->addFilter("trash='0'");
                    $pkwP->addFilter("produk_id='$prodID'");
                    $pkwP->addFilter("produk_dasar_id='$value'");
                    $pkwRoomPrd = $pkwP->lookupAll()->result();
                    $roomProdukQuery = $this->db->last_query();
                    $roomProduk = array();
                    foreach ($pkwRoomPrd as $k => $pkwPrdData) {
                        switch ($pkwPrdData->jenis) {
                            case "produk_room":
                                if (!isset($roomProduk[$pkwPrdData->produk_dasar_id])) {
                                    $roomProduk[$pkwPrdData->produk_dasar_id] = 0;
                                }
                                $roomProduk[$pkwPrdData->produk_dasar_id] += $pkwPrdData->jml;
                                break;
                        }
                    }
                    $jml_x = $_SESSION['PROED'][$prodID]['component'][$fase_urut]['produk'][$value]["jml"] * 1 > 0 ? $_SESSION['PROED'][$prodID]['component'][$fase_urut]['produk'][$value]["jml"] * 1 : 0;
                    $harga = $_SESSION['PROED'][$prodID]['component'][$fase_urut]['produk'][$value]["harga"] * 1 > 0 ? $_SESSION['PROED'][$prodID]['component'][$fase_urut]['produk'][$value]["harga"] * 1 : 0;
                    $ppv = $_SESSION['PROED'][$prodID]['component'][$fase_urut]['produk'][$value]["ppv"] * 1 > 0 ? $_SESSION['PROED'][$prodID]['component'][$fase_urut]['produk'][$value]["ppv"] * 1 : 0;
                    $jual = $_SESSION['PROED'][$prodID]['component'][$fase_urut]['produk'][$value]["jual"] * 1 > 0 ? $_SESSION['PROED'][$prodID]['component'][$fase_urut]['produk'][$value]["jual"] * 1 : 0;
                    $satuan = $_SESSION['PROED'][$prodID]['component'][$fase_urut]['produk'][$value]["satuan"] != '' ? $_SESSION['PROED'][$prodID]['component'][$fase_urut]['produk'][$value]["satuan"] : "-";
                    $nilai = $_SESSION['PROED'][$prodID]['component'][$fase_urut]['produk'][$value]["nilai"] * 1 > 0 ? $_SESSION['PROED'][$prodID]['component'][$fase_urut]['produk'][$value]["nilai"] * 1 : 0;
                    $nama = $_SESSION['PROED'][$prodID]['component'][$fase_urut]['produk'][$value]["produk_dasar_nama"] != '' ? $_SESSION['PROED'][$prodID]['component'][$fase_urut]['produk'][$value]["produk_dasar_nama"] : "-";
                    //sementara langsung ambil dari jml
                    //nanti hrus di buatkan used jadi jml ini di kurang used dapat lah nilai avail
                    $total_used = isset($roomProduk[$value]) ? $roomProduk[$value] * 1 : 0;
                    $avail = $jml_x - $total_used;
                    $_SESSION["NEW"]["produk_komposisi_fase_room"][$fase_urut][$selID][$prodID]['harga'] = $harga;
                    $_SESSION["NEW"]["produk_komposisi_fase_room"][$fase_urut][$selID][$prodID]['ppv'] = $ppv;
                    $_SESSION["NEW"]["produk_komposisi_fase_room"][$fase_urut][$selID][$prodID]['jual'] = $jual;
                    $_SESSION["NEW"]["produk_komposisi_fase_room"][$fase_urut][$selID][$prodID]['satuan'] = $satuan;
                    $_SESSION["NEW"]["produk_komposisi_fase_room"][$fase_urut][$selID][$prodID]['nilai'] = $nilai;
                    $_SESSION["NEW"]["produk_komposisi_fase_room"][$fase_urut][$selID][$prodID]['avail'] = $avail;
                    $_SESSION["NEW"]["produk_komposisi_fase_room"][$fase_urut][$selID][$prodID]['produk_dasar_nama'] = $nama;
                    $_SESSION["NEW"]["produk_komposisi_fase_room"][$fase_urut][$selID][$prodID]['room_id'] = $selID;
                    $_SESSION["NEW"]["produk_komposisi_fase_room"][$fase_urut][$selID][$prodID]['room_nama'] = $roomData[$selID]->room_nama;
                    $_SESSION["NEW"]["produk_komposisi_fase_room"][$fase_urut][$selID][$prodID]['room_ket'] = $roomData[$selID]->room_ket;
                    $subTotal = isset($_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]["subtotal"]) ? $_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]["subtotal"] : 0;
                }
                else {
                    $subTotal = $_SESSION["NEW"]["produk_komposisi_fase_room"][$fase_urut][$selID][$prodID]['harga'] * $value;
                }
                $new_jml = $_SESSION["NEW"]["produk_komposisi_fase_room"][$fase_urut][$selID][$prodID]['jml'];
                $avail = $_SESSION["NEW"]["produk_komposisi_fase_room"][$fase_urut][$selID][$prodID]['avail'];
                if ($new_jml * 1 > 0 && $new_jml <= $avail) {
                    $showButton = "
                            var tmpIdForm=top.$('#produk_komposisi_fase_room_$fase_urut$selID$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', false);
                            $(tmpIdForm).addClass('btn-success');";
                }
                else {
                    $showButton = "
                            var tmpIdForm=top.$('#produk_komposisi_fase_room_$fase_urut$selID$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', true);
                            $(tmpIdForm).removeClass('btn-success');";
                }
                if ($key == "produk_dasar_id") {
                    $nabFormJml = "";
                    if ($avail * 1 > 0) {
                        $nabFormJml .= "top.$('#produk_komposisi_fase_room_" . $fase_urut . $selID . "jml').prop('disabled', false);";
                        $nabFormJml .= "top.$('#produk_komposisi_fase_room_" . $fase_urut . $selID . "jml').addClass('bg-lime');";
                    }
                    $outputJs = base64_encode("
                        top.$('#produk_komposisi_fase_room_" . $fase_urut . $selID . "subtotal').html(addCommas($subTotal));
                        top.$('#produk_komposisi_fase_room_" . $fase_urut . $selID . "satuan').html('$satuan');
                        top.$('#produk_komposisi_fase_room_" . $fase_urut . $selID . "nilai').html(addCommas($nilai));
                        top.$('#produk_komposisi_fase_room_" . $fase_urut . $selID . "jual').html(addCommas($jual));
                        top.$('#produk_komposisi_fase_room_" . $fase_urut . $selID . "ppv').html(addCommas($ppv));
                        top.$('#produk_komposisi_fase_room_" . $fase_urut . $selID . "harga').html(addCommas($harga));
                        top.$('#produk_komposisi_fase_room_" . $fase_urut . $selID . "avail').html(addCommas($avail));
                        top.$('#produk_komposisi_fase_room_" . $fase_urut . $selID . "jml').val(0);
                        $nabFormJml
                        $showButton");
                }
                else {
                    $outputJs = base64_encode("
                        top.$('#produk_komposisi_fase_room_" . $fase_urut . $selID . "subtotal').html(addCommas($subTotal));
                        $showButton");
                }
                echo $outputJs;
                break;
            case "produk_komposisi_fase_room_biaya":
                $selID = $_GET["selID"];
                if (!isset($_SESSION["NEW"]["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$prodID])) {
                    unset($_SESSION["NEW"]["produk_komposisi_fase_room_biaya"][$fase_urut][$selID]);
                }
                $_SESSION["NEW"]["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$prodID][$key] = $value;
                //getRoomNama
                $this->load->model("Mdls/" . "MdlProjectKomposisiWorkorderRoom");
                $pkw = new MdlProjectKomposisiWorkorderRoom();
                $pkw->addFilter("status='1'");
                $pkw->addFilter("trash='0'");
                $pkw->addFilter("room_id='$selID'");
                $pkwRoom = $pkw->lookupAll()->result();
                $pkwQuery = $this->db->last_query();
                $roomData = array();
                $roomProduk = array();
                foreach ($pkwRoom as $k => $pkwData) {
                    switch ($pkwData->jenis) {
                        case "room":
                            $roomData[$pkwData->room_id] = $pkwData;
                            break;
                    }
                }
                if ($key == "produk_dasar_id") {

                    $arrIDs = explode("-", $value);
                    if (is_array($arrIDs)) {
                        $biaya_id = $arrIDs[0];
                        $produk_dasar_id = $arrIDs[1];
                    }

                    $this->load->model("Mdls/" . "MdlProjectKomposisiWorkorderRoomProdukBiaya");
                    $pkwP = new MdlProjectKomposisiWorkorderRoomProdukBiaya();
                    $pkwP->addFilter("produk_id='$prodID'");
                    $pkwP->addFilter("biaya_source_id='$biaya_id'");
                    $pkwP->addFilter("produk_dasar_id='$produk_dasar_id'");
                    $pkwRoomPrd = $pkwP->lookupAll()->result();
                    $roomProdukQuery = $this->db->last_query();

                    $roomProduk = array();
                    foreach ($pkwRoomPrd as $k => $pkwPrdData) {
                        switch ($pkwPrdData->jenis) {
                            case "biaya_room":
                                if (!isset($roomProduk[$pkwPrdData->biaya_source_id . $pkwPrdData->produk_dasar_id])) {
                                    $roomProduk[$pkwPrdData->biaya_source_id . $pkwPrdData->produk_dasar_id] = 0;
                                }
                                $roomProduk[$pkwPrdData->biaya_source_id . $pkwPrdData->produk_dasar_id] += $pkwPrdData->jml;
                                break;
                        }
                    }

                    $jml_x = $_SESSION['PROED'][$prodID]['component'][$fase_urut]['biaya'][$biaya_id]["jml"] * 1 > 0 ? $_SESSION['PROED'][$prodID]['component'][$fase_urut]['biaya'][$biaya_id]["jml"] * 1 : 0;
                    $harga = $_SESSION['PROED'][$prodID]['component'][$fase_urut]['biaya'][$biaya_id]["harga"] * 1 > 0 ? $_SESSION['PROED'][$prodID]['component'][$fase_urut]['biaya'][$biaya_id]["harga"] * 1 : 0;
                    $satuan_id = $_SESSION['PROED'][$prodID]['component'][$fase_urut]['biaya'][$biaya_id]["satuan_id"] * 1 > 0 ? $_SESSION['PROED'][$prodID]['component'][$fase_urut]['biaya'][$biaya_id]["satuan_id"] * 1 : 0;
                    $satuan = $_SESSION['PROED'][$prodID]['component'][$fase_urut]['biaya'][$biaya_id]["satuan"] != '' ? $_SESSION['PROED'][$prodID]['component'][$fase_urut]['biaya'][$biaya_id]["satuan"] : "-";
                    $dasar_id = $_SESSION['PROED'][$prodID]['component'][$fase_urut]['biaya'][$biaya_id]["produk_dasar_id"] * 1 > 0 ? $_SESSION['PROED'][$prodID]['component'][$fase_urut]['biaya'][$biaya_id]["produk_dasar_id"] : "0";
                    $nama = $_SESSION['PROED'][$prodID]['component'][$fase_urut]['biaya'][$biaya_id]["produk_dasar_nama"] != '' ? $_SESSION['PROED'][$prodID]['component'][$fase_urut]['biaya'][$biaya_id]["produk_dasar_nama"] : "-";
                    $cat_nama = $_SESSION['PROED'][$prodID]['component'][$fase_urut]['biaya'][$biaya_id]["cat_nama"] != '' ? $_SESSION['PROED'][$prodID]['component'][$fase_urut]['biaya'][$biaya_id]["cat_nama"] : "-";
                    $ket_room = $_SESSION['PROED'][$prodID]['component'][$fase_urut]['biaya'][$biaya_id]["keterangan"] != '' ? $_SESSION['PROED'][$prodID]['component'][$fase_urut]['biaya'][$biaya_id]["keterangan"] : "-";
                    $cat_id = $_SESSION['PROED'][$prodID]['component'][$fase_urut]['biaya'][$biaya_id]["cat_id"] * 1 > 0 ? $_SESSION['PROED'][$prodID]['component'][$fase_urut]['biaya'][$biaya_id]["cat_id"] * 1 : 0;
                    //sementara langsung ambil dari jml

                    //nanti hrus di buatkan used jadi jml ini di kurang used dapat lah nilai avail
                    $total_used = isset($roomProduk[$biaya_id . $produk_dasar_id]) ? $roomProduk[$biaya_id . $produk_dasar_id] * 1 : 0;
                    $avail = $jml_x - $total_used;

                    $_SESSION["NEW"]["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$prodID]['harga'] = $harga;
                    $_SESSION["NEW"]["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$prodID]['satuan'] = $satuan;
                    $_SESSION["NEW"]["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$prodID]['satuan_id'] = $satuan_id;
                    $_SESSION["NEW"]["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$prodID]['cat_id'] = $cat_id;
                    $_SESSION["NEW"]["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$prodID]['cat_nama'] = $cat_nama;
                    $_SESSION["NEW"]["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$prodID]['avail'] = $avail;
                    $_SESSION["NEW"]["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$prodID]['biaya_source_id'] = $biaya_id;
                    $_SESSION["NEW"]["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$prodID]['produk_dasar_id'] = $dasar_id;
                    $_SESSION["NEW"]["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$prodID]['produk_dasar_nama'] = $nama . " (" . $ket_room . ")";
                    $_SESSION["NEW"]["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$prodID]['room_id'] = $selID;
                    $_SESSION["NEW"]["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$prodID]['room_nama'] = $roomData[$selID]->room_nama;
                    $_SESSION["NEW"]["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$prodID]['room_ket'] = $roomData[$selID]->room_ket;

                }
                else {
                    $subTotal = $_SESSION["NEW"]["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$prodID]['harga'] * $value;
                }
                $new_jml = $_SESSION["NEW"]["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$prodID]['jml'];
                $avail = $_SESSION["NEW"]["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$prodID]['avail'];
                if ($new_jml * 1 > 0 && $new_jml <= $avail) {
                    $showButton = "
                            var tmpIdForm=top.$('#produk_komposisi_fase_room_biaya_$fase_urut$selID$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', false);
                            $(tmpIdForm).addClass('btn-success');";
                }
                else {
                    $showButton = "
                            var tmpIdForm=top.$('#produk_komposisi_fase_room_biaya_$fase_urut$selID$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', true);
                            $(tmpIdForm).removeClass('btn-success');";
                }
                if ($key == "produk_dasar_id") {
                    $nabFormJml = "";
                    if ($avail * 1 > 0) {
                        $nabFormJml .= "top.$('#produk_komposisi_fase_room_biaya_" . $fase_urut . $selID . "jml').prop('disabled', false);";
                        $nabFormJml .= "top.$('#produk_komposisi_fase_room_biaya_" . $fase_urut . $selID . "jml').addClass('bg-lime');";
                    }
                    $outputJs = base64_encode("
                        top.$('#produk_komposisi_fase_room_biaya_" . $fase_urut . $selID . "subtotal').html(0);
                        top.$('#produk_komposisi_fase_room_biaya_" . $fase_urut . $selID . "satuan_id').html('$satuan');
                        top.$('#produk_komposisi_fase_room_biaya_" . $fase_urut . $selID . "cat_id').html('$cat_nama');
                        top.$('#produk_komposisi_fase_room_biaya_" . $fase_urut . $selID . "harga').html(addCommas($harga));
                        top.$('#produk_komposisi_fase_room_biaya_" . $fase_urut . $selID . "avail').html(addCommas($avail));
                        top.$('#produk_komposisi_fase_room_biaya_" . $fase_urut . $selID . "jml').val(0);
                        $nabFormJml
                        $showButton");
                }
                else {
                    $outputJs = base64_encode("
                        top.$('#produk_komposisi_fase_room_biaya_" . $fase_urut . $selID . "subtotal').html(addCommas($subTotal));
                        $showButton");
                }
                echo $outputJs;
                break;
            case "produk_fase":
                if (!isset($_SESSION["NEW"]["produk_fase"][$prodID])) {
                    unset($_SESSION["NEW"]["produk_fase"]);
                }
                $_SESSION["NEW"]["produk_fase"][$prodID][$key] = $value;
                break;
            case "komposisi_fase":
                if (!isset($_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID])) {
                    unset($_SESSION["NEW"]["komposisi_fase"][$fase_urut]);
                }
                $this->load->model("Mdls/MdlProdukProject");
                $p = new MdlProdukProject();
                $produkID = $this->uri->segment(4);
                if ($key == "produk_dasar_id") {
//                    $this->load->model("Mdls/MdlProduk");
                    $this->load->model("Mdls/MdlProdukForProject");
                    $this->load->model("Mdls/MdlHargaProduk");
                    $s = new MdlHargaProduk;
                    $s->addFilter("produk_id='$value'");
                    $s->addFilter("cabang_id='-1'");
                    $s->addFilter("status='1'");
                    $s->addFilter("jenis_value='hpp'");
                    $tempHarga = $s->lookUpAll()->result();
                    $s = new MdlHargaProduk;
                    $s->addFilter("produk_id='$value'");
                    $s->addFilter("cabang_id='-1'");
                    $s->addFilter("status='1'");
                    $s->addFilter("jenis_value='hpp_nppv'");
                    $tempHargaPPV = $s->lookUpAll()->result();
                    $s = new MdlHargaProduk;
                    $s->addFilter("produk_id='$value'");
                    $s->addFilter("cabang_id='-1'");
                    $s->addFilter("status='1'");
                    $s->addFilter("jenis_value='jual'"); //jual_nppn= +ppn || jual = non ppn
                    $tempHargaJual = $s->lookUpAll()->result();
                    $s = new MdlProdukForProject;
                    $s->setFilters(array());
//                    $this->db->where("jenis='item' AND status='1' AND trash='0' AND allow_project='1'");
//                    $this->db->or_where("status=1");
                    $tempSupplies = $s->lookUpById($value)->result();

                    $reg = $tempSupplies[0]->allow_project==1 ? " | reguler" : " | project";
                    $kapasitas = $tempSupplies[0]->kategori_id == 1 ? " | " . $tempSupplies[0]->kapasitas_nama : "";

                    $arrData = array(
                        "produk_dasar_id" => $_GET["value"],
                        "produk_dasar_nama" => $tempSupplies[0]->nama . $kapasitas . $reg,
                        "satuan_id" => $tempSupplies[0]->kategori_id == 1 ? $tempSupplies[0]->kategori_id : $tempSupplies[0]->size_id,
                        "satuan" => $tempSupplies[0]->kategori_nama == "unit" ? $tempSupplies[0]->kategori_nama : $tempSupplies[0]->size_nama,
                        "jual" => $tempHargaJual[0]->nilai,
                        "nilai" => $tempHarga[0]->nilai,
                        "ppv" => $tempHargaPPV[0]->nilai,
                        "cat_id" => "6",
                        "cat_nama" => "bahan baku",
                    );
                    $_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID] = $arrData;
                }
                else {
                    if ($key == "jml") {
                        $_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]["subtotal"] = $value * $_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]["harga"];
                    }
                    if ($key == "harga" && isset($_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]["jml"]) && $_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]["jml"] * 1 > 0) {
                        $_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]["subtotal"] = $_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]["jml"] * $_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]["harga"];
                    }
                    $_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID][$key] = $value;
                }
                $jml = $_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]["jml"] * 1 > 0 ? $_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]["jml"] * 1 : 0;
                $harga = $_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]["harga"] * 1 > 0 ? round($_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]["harga"] * 1) : 0;
                $ppv = $_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]["ppv"] * 1 > 0 ? round($_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]["ppv"] * 1) : 0;
                $jual = $_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]["jual"] * 1 > 0 ? round($_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]["jual"]*1) : 0;
                $satuan = $_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]["satuan"] != '' ? $_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]["satuan"] : "-";
                $nilai = $_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]["nilai"] * 1 > 0 ? round($_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]["nilai"] * 1) : 0;
                $produk_dasar_id = isset($_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]['produk_dasar_id']) ? $_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]['produk_dasar_id'] * 1 : 0;
                //JSON OUTPUT HANDLE JS UI
                switch ($key) {
                    case "harga":
                        $subTotal = isset($_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]["jml"]) ? $_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]["jml"] * $value : 0;
                        $harga = $value;
                        $showButton = "";
                        if ($produk_dasar_id * 1 > 0 && $jml * 1 > 0 && $harga * 1 > 0) {
                            $showButton = "
                            var tmpIdForm=top.$('#komposisi_fase_$fase_urut$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', false);
                            $(tmpIdForm).addClass('btn-success');";
                        }
                        else {
                            $showButton = "
                            var tmpIdForm=top.$('#komposisi_fase_$fase_urut$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', true);
                            $(tmpIdForm).removeClass('btn-success');";
                        }
                        $outputJs = base64_encode("
                        top.$('#komposisi_fase_" . $fase_urut . "subtotal').html(addCommas($subTotal));
                        $showButton");
                        echo $outputJs;
                        break;
                    case "jml":
                        if ($produk_dasar_id * 1 > 0 && $jml * 1 > 0 && $harga * 1 > 0) {
                            $showButton = "
                            var tmpIdForm=top.$('#komposisi_fase_$fase_urut$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', false);
                            $(tmpIdForm).addClass('btn-success');";
                        }
                        else {
                            $showButton = "
                            var tmpIdForm=top.$('#komposisi_fase_$fase_urut$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', true);
                            $(tmpIdForm).removeClass('btn-success');";
                        }
                        $subTotal = isset($_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]["subtotal"]) ? $_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]["subtotal"] : 0;
                        $outputJs = base64_encode("
                        top.$('#komposisi_fase_" . $fase_urut . "subtotal').html(addCommas($subTotal));
                        $showButton");
                        echo $outputJs;
                        break;
                    case "produk_dasar_id":
                        $showButton = "";
                        if ($produk_dasar_id * 1 > 0 && $jml * 1 > 0 && $harga * 1 > 0) {
                            $showButton = "
                            var tmpIdForm=top.$('#komposisi_fase_$fase_urut$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', false);
                            $(tmpIdForm).addClass('btn-success');";
                        }
                        else {
                            $showButton = "
                            var tmpIdForm=top.$('#komposisi_fase_$fase_urut$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', true);
                            $(tmpIdForm).removeClass('btn-success');";
                        }
                        $subTotal = isset($_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]["subtotal"]) ? $_SESSION["NEW"]["komposisi_fase"][$fase_urut][$prodID]["subtotal"] : 0;
                        $outputJs = base64_encode("
                        top.$('#komposisi_fase_" . $fase_urut . "subtotal').html(addCommas($subTotal));
                        top.$('#komposisi_fase_" . $fase_urut . "satuan').html('$satuan');
                        top.$('#komposisi_fase_" . $fase_urut . "nilai').html(addCommas($nilai));
                        top.$('#komposisi_fase_" . $fase_urut . "jual').html(addCommas($jual));
                        top.$('#komposisi_fase_" . $fase_urut . "ppv').html(addCommas($ppv));
                        top.$('#komposisi_fase_" . $fase_urut . "harga').val(addCommas($harga));
                        top.$('#komposisi_fase_" . $fase_urut . "jml').val(addCommas($jml));
                        $showButton");
                        echo $outputJs;
//                        echo "<br>" . json_encode($_SESSION["NEW"]["komposisi_fase"]);
                        break;
                }
                break;
            case "komposisi_fase_room":
                if (!isset($_SESSION["NEW"]["komposisi_fase_room"][$fase_urut][$prodID])) {
                    unset($_SESSION["NEW"]["komposisi_fase_room"][$fase_urut]);
                }
                $_SESSION["NEW"]["komposisi_fase_room"][$fase_urut][$prodID][$key] = $value;
                //JSON OUTPUT HANDLE JS UI
                switch ($key) {
                    case "room_nama":
                        $room_nama = $_SESSION["NEW"]["komposisi_fase_room"][$fase_urut][$prodID]['room_nama'];
                        if (trim($room_nama) != '') {
                            $showButton = "
                            var tmpIdForm=top.$('#$modes" . "_$fase_urut$sub_fase_id" . "$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', false);
                            $(tmpIdForm).addClass('btn-success');";
                        }
                        else {
                            $showButton = "
                            var tmpIdForm=top.$('#$modes" . "_$fase_urut$sub_fase_id" . "$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', true);
                            $(tmpIdForm).removeClass('btn-success');";
                        }
                        $outputJs = base64_encode("$showButton");
                        echo $outputJs;
                        break;
                    case "room_ket":
                        $room_nama = $_SESSION["NEW"]["komposisi_fase_room"][$fase_urut][$prodID]['room_nama'];
                        $room_ket = $_SESSION["NEW"]["komposisi_fase_room"][$fase_urut][$prodID]['room_ket'];
                        if (trim($room_nama) != '') {
                            $showButton = "
                            var tmpIdForm=top.$('#$modes" . "_$fase_urut$sub_fase_id" . "$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', false);
                            $(tmpIdForm).addClass('btn-success');";
                        }
                        else {
                            $showButton = "
                            var tmpIdForm=top.$('#$modes" . "_$fase_urut$sub_fase_id" . "$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', true);
                            $(tmpIdForm).removeClass('btn-success');";
                        }
                        $outputJs = base64_encode("$showButton");
                        echo $outputJs;
                        break;
                }
                break;
            case "komposisi_fase_sub":
                if (!isset($_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID])) {
                    unset($_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id]);
                }
                $this->load->model("Mdls/MdlProdukProject");
                $p = new MdlProdukProject();
                if ($key == "produk_dasar_id") {
                    $this->load->model("Mdls/MdlProdukForProject");
                    $this->load->model("Mdls/MdlHargaProduk");
                    $s = new MdlHargaProduk;
                    $s->addFilter("produk_id='$value'");
                    $s->addFilter("cabang_id='-1'");
                    $s->addFilter("status='1'");
                    $s->addFilter("jenis_value='hpp'");
                    $tempHarga = $s->lookUpAll()->result();
                    $s = new MdlProdukForProject;
                    $tempSupplies = $s->lookUpById($value)->result();
                    $arrData = array(
                        "produk_dasar_id" => $_GET["value"],
                        "produk_dasar_nama" => $tempSupplies[0]->nama,
                        "satuan_id" => $tempSupplies[0]->satuan_id,
                        "satuan" => $tempSupplies[0]->satuan,
                        "nilai" => $tempHarga[0]->nilai,
                        "cat_id" => "6",
                        "cat_nama" => "bahan baku",
                    );
                    $_SESSION["NEW"]["komposisi_fase_sub"][$fase_urut][$sub_fase_id][$prodID] = $arrData;
                }
                else {
                    if ($key == "jml") {
                        $_SESSION["NEW"]["komposisi_fase_sub"][$fase_urut][$sub_fase_id][$prodID]["subtotal"] = $value * $_SESSION["NEW"]["komposisi_fase_sub"][$fase_urut][$sub_fase_id][$prodID]["harga"];
                    }
                    $_SESSION["NEW"]["komposisi_fase_sub"][$fase_urut][$sub_fase_id][$prodID][$key] = $value;
                }
                $jml = $_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]["jml"] * 1 > 0 ? $_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]["jml"] * 1 : 0;
                $harga = $_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]["harga"] * 1 > 0 ? $_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]["harga"] * 1 : 0;
                $satuan = $_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]["satuan"] != '' ? $_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]["satuan"] : "-";
                $nilai = $_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]["nilai"] * 1 > 0 ? $_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]["nilai"] * 1 : 0;
                $produk_dasar_id = isset($_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]['produk_dasar_id']) ? $_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]['produk_dasar_id'] * 1 : 0;
                //JSON OUTPUT HANDLE JS UI
                switch ($key) {
                    case "harga":
                        $subTotal = isset($_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]["jml"]) ? $_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]["jml"] * $value : 0;
                        $harga = $value;
                        $showButton = "";
                        if ($produk_dasar_id * 1 > 0 && $jml * 1 > 0 && $harga * 1 > 0) {
                            $showButton = "
                            var tmpIdForm=top.$('#$modes" . "_$fase_urut$sub_fase_id" . "$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', false);
                            $(tmpIdForm).addClass('btn-success');";
                        }
                        else {
                            $showButton = "
                            var tmpIdForm=top.$('#$modes" . "_$fase_urut$sub_fase_id" . "$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', true);
                            $(tmpIdForm).removeClass('btn-success');";
                        }
                        $outputJs = base64_encode("
                        top.$('#$modes" . "_$fase_urut$sub_fase_id" . "subtotal').html(addCommas($subTotal));
                        $showButton");
                        echo $outputJs;
                        break;
                    case "jml":
                        $showButton = "";
                        if ($produk_dasar_id * 1 > 0 && $jml * 1 > 0 && $harga * 1 > 0) {
                            $showButton = "
                            var tmpIdForm=top.$('#$modes" . "_$fase_urut$sub_fase_id" . "$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', false);
                            $(tmpIdForm).addClass('btn-success');";
                        }
                        else {
                            $showButton = "
                            var tmpIdForm=top.$('#$modes" . "_$fase_urut$sub_fase_id" . "$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', true);
                            $(tmpIdForm).removeClass('btn-success');";
                        }
                        $subTotal = isset($_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]["subtotal"]) ? $_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]["subtotal"] : 0;
                        $outputJs = base64_encode("
                        top.$('#$modes" . "_$fase_urut$sub_fase_id" . "subtotal').html(addCommas($subTotal));
                        $showButton");
                        echo $outputJs;
                        break;
                    case "produk_dasar_id":
                        $showButton = "";
                        if ($produk_dasar_id * 1 > 0 && $jml * 1 > 0 && $harga * 1 > 0) {
                            $showButton = "
                            var tmpIdForm=top.$('#$modes" . "_$fase_urut$sub_fase_id" . "$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', false);
                            $(tmpIdForm).addClass('btn-success');";
                        }
                        else {
                            $showButton = "
                            var tmpIdForm=top.$('#$modes" . "_$fase_urut$sub_fase_id" . "$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', true);
                            $(tmpIdForm).removeClass('btn-success');";
                        }
                        $subTotal = isset($_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]["subtotal"]) ? $_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]["subtotal"] : 0;
                        $outputJs = base64_encode("
                        top.$('#$modes" . "_$fase_urut$sub_fase_id" . "subtotal').html(addCommas($subTotal));
                        top.$('#$modes" . "_$fase_urut$sub_fase_id" . "satuan').html('$satuan');
                        top.$('#$modes" . "_$fase_urut$sub_fase_id" . "nilai').html(addCommas($nilai));
                        top.$('#$modes" . "_$fase_urut$sub_fase_id" . "harga').val(addCommas($harga));
                        top.$('#$modes" . "_$fase_urut$sub_fase_id" . "jml').val(addCommas($jml));
                        $showButton");
                        echo $outputJs;
                        break;
                }
                break;
            case "komposisi_fase_biaya":
                if (!isset($_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID])) {
                    unset($_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut]);
                }
                $produkID = $this->uri->segment(4);
                if ($key == "produk_dasar_id") {
                    $this->load->model("Mdls/MdlDtaBiayaProduksi");
                    $this->load->model("Mdls/MdlProdukRakitanPreBiaya");
                    $s = new MdlDtaBiayaProduksi;
                    $tempSupplies = $s->lookUpById($value)->result();
                    //region biaya biaya
                    $ob = new MdlProdukRakitanPreBiaya();
                    $tempBiaya = $ob->lookUpAll()->result();
                    $prebiaya = array();
                    if (sizeof($tempBiaya) > 0) {
                        foreach ($tempBiaya as $ic => $biayaData) {
                            $prebiaya[$biayaData->id] = (array)$biayaData;
                        }
                    }
                    //endregion
                    $arrPreBiaya=array();
                    foreach($tempSupplies as $arrPreBiaya_0){
                        $arrPreBiaya[$arrPreBiaya_0->id]=$arrPreBiaya_0->cat_id;
                    }
//                    $arrPreBiaya = array(
//                        40 => 2, //direct labor
//                        41 => 4, //quality
//                        42 => 2, //direct labor
//                        43 => 2, //direct labor
//                        44 => 2, //direct labor
//                        45 => 2, //direct labor
//                        46 => 2, //direct labor
//                        47 => 2, //direct labor
//                        48 => 2, //direct labor
//                        49 => 2, //direct labor
//                        50 => 2, //direct labor
//                    );
                    $arrData = array(
                        "produk_dasar_id" => $value,
                        "produk_dasar_nama" => $tempSupplies[0]->nama,
                    );
                    $_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID][$key] = $value;
                    $_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["produk_dasar_nama"] = $tempSupplies[0]->nama;
                    $_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["cat_id"] = $arrPreBiaya[$value];
                    $_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["cat_nama"] = $prebiaya[$arrPreBiaya[$value]]['nama'];
                }
                else {
                    if ($key == "cat_id") {
                        $this->load->model("Mdls/MdlProdukRakitan");
                        $p = new MdlProdukRakitan();
                        $this->load->model("Mdls/MdlProdukRakitanPreBiaya");
                        $s = new MdlProdukRakitanPreBiaya;
                        $tempSupplies = $s->lookUpById($value)->result();
                        $arrData = array(
                            "cat_id" => $_GET["value"],
                            "cat_nama" => $tempSupplies[0]->nama,
                        );
                        $_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID][$key] = $value;
                        $_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["cat_nama"] = $tempSupplies[0]->nama;
                    }
                    else {
                        if ($key == "jml") {
                            $_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["jml"] = $value * 1;
                            $_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["subtotal"] = $value * $_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["harga"];
                        }
                        elseif ($key == "harga") {
                            $_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["harga"] = $value * 1;
                            $_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["subtotal"] = $value * $_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["jml"];
                        }
                        else {
                            $_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID][$key] = $value;
                            $_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["subtotal"] = $_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["harga"] * $_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["jml"];
                        }
                    }
                }
                $jml = $_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["jml"];
                $harga = $_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["harga"];
                $cat_id = $_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["cat_id"];
                $cat_nama = $_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["cat_nama"];
                $satuan_id = $_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["satuan_id"];
                $keterangan = $_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["keterangan"];
                $produk_dasar_id = isset($_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]['produk_dasar_id']) ? $_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]['produk_dasar_id'] * 1 : 0;
                //JSON OUTPUT HANDLE JS UI
                switch ($key) {
                    case "harga":
                        $harga = $value;
                        $showButton = "";
                        if ($cat_id * 1 > 0 && $satuan_id * 1 > 0 && $produk_dasar_id * 1 > 0 && $jml * 1 > 0 && $harga * 1 > 0) {
                            $showButton = "
                            var tmpIdForm=top.$('#komposisi_fase_biaya_$fase_urut$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', false);
                            $(tmpIdForm).addClass('btn-success');";
                        }
                        else {
                            $showButton = "
                            var tmpIdForm=top.$('#komposisi_fase_biaya_$fase_urut$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', true);
                            $(tmpIdForm).removeClass('btn-success');";
                        }

                        $subTotal = isset($_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["jml"]) ? round($_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["jml"] * $value) : round($jml * $harga);

                        $outputJs = base64_encode("
                        top.$('#komposisi_fase_biaya_" . $fase_urut . "subtotal').html(addCommas($subTotal));
                        $showButton");

                        echo $outputJs;
                        break;
                    case "jml":
                        $showButton = "";
                        if ($cat_id * 1 > 0 && $satuan_id * 1 > 0 && $produk_dasar_id * 1 > 0 && $jml * 1 > 0 && $harga * 1 > 0) {
                            $showButton = "
                            var tmpIdForm=top.$('#komposisi_fase_biaya_$fase_urut$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', false);
                            $(tmpIdForm).addClass('btn-success');";
                        }
                        else {
                            $showButton = "
                            var tmpIdForm=top.$('#komposisi_fase_biaya_$fase_urut$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', true);
                            $(tmpIdForm).removeClass('btn-success');";
                        }

                        $subTotal = isset($_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["subtotal"]) ? round($_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["subtotal"]) : round($jml * $harga);

                        $outputJs = base64_encode("
                        top.$('#komposisi_fase_biaya_" . $fase_urut . "subtotal').html(addCommas($subTotal));
                        $showButton");

                        echo $outputJs;
                        break;
                    case "produk_dasar_id":
                        $showButton = "";
                        if ($cat_id * 1 > 0 && $satuan_id * 1 > 0 && $produk_dasar_id * 1 > 0 && $jml * 1 > 0 && $harga * 1 > 0) {
                            $showButton = "
                            var tmpIdForm=top.$('#komposisi_fase_biaya_$fase_urut$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', false);
                            $(tmpIdForm).addClass('btn-success');";
                        }
                        else {
                            $showButton = "
                            var tmpIdForm=top.$('#komposisi_fase_biaya_$fase_urut$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', true);
                            $(tmpIdForm).removeClass('btn-success');";
                        }
                        $subTotal = isset($_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["subtotal"]) ? round($_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["subtotal"]) : round($jml * $harga);
                        $outputJs = base64_encode("
                        top.$('#komposisi_fase_biaya_" . $fase_urut . "subtotal').html(addCommas($subTotal));
                        top.$('#komposisi_fase_biaya_" . $fase_urut . "cat_id').html('$cat_nama');
                        $showButton");
                        echo $outputJs;
                        break;
                    case "satuan_id":
                        $showButton = "";
                        if ($cat_id * 1 > 0 && $satuan_id * 1 > 0 && $produk_dasar_id * 1 > 0 && $jml * 1 > 0 && $harga * 1 > 0) {
                            $showButton = "
                            var tmpIdForm=top.$('#komposisi_fase_biaya_$fase_urut$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', false);
                            $(tmpIdForm).addClass('btn-success');";
                        }
                        else {
                            $showButton = "
                            var tmpIdForm=top.$('#komposisi_fase_biaya_$fase_urut$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', true);
                            $(tmpIdForm).removeClass('btn-success');";
                        }
                        $subTotal = isset($_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["subtotal"]) ? round($_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["subtotal"]) : round($jml * $harga);
                        $outputJs = base64_encode("
                        top.$('#komposisi_fase_biaya_" . $fase_urut . "subtotal').html(addCommas($subTotal));
                        $showButton");
                        echo $outputJs;
                        break;
                    case "cat_id":
                        $showButton = "";
                        if ($cat_id * 1 > 0 && $satuan_id * 1 > 0 && $produk_dasar_id * 1 > 0 && $jml * 1 > 0 && $harga * 1 > 0) {
                            $showButton = "
                            var tmpIdForm=top.$('#komposisi_fase_biaya_$fase_urut$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', false);
                            $(tmpIdForm).addClass('btn-success');";
                        }
                        else {
                            $showButton = "
                            var tmpIdForm=top.$('#komposisi_fase_biaya_$fase_urut$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', true);
                            $(tmpIdForm).removeClass('btn-success');";
                        }
                        $subTotal = isset($_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["subtotal"]) ? $_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["subtotal"] : $jml * $harga;
                        $outputJs = base64_encode("
                        top.$('#komposisi_fase_biaya_" . $fase_urut . "subtotal').html(addCommas($subTotal));
                        $showButton");
                        echo $outputJs;
                        break;
                    default:
                        $showButton = "";
                        if ($cat_id * 1 > 0 && $satuan_id * 1 > 0 && $produk_dasar_id * 1 > 0 && $jml * 1 > 0 && $harga * 1 > 0) {
                            $showButton = "
                            var tmpIdForm=top.$('#komposisi_fase_biaya_$fase_urut$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', false);
                            $(tmpIdForm).addClass('btn-success');";
                        }
                        else {
                            $showButton = "
                            var tmpIdForm=top.$('#komposisi_fase_biaya_$fase_urut$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', true);
                            $(tmpIdForm).removeClass('btn-success');";
                        }
                        $subTotal = isset($_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["subtotal"]) && $_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["subtotal"] != 0 ? $_SESSION["NEW"]["komposisi_fase_biaya"][$fase_urut][$prodID]["subtotal"] : $jml * $harga;
                        $outputJs = base64_encode("
                        top.$('#komposisi_fase_biaya_" . $fase_urut . "subtotal').html(addCommas($subTotal));
                        $showButton");
                        echo $outputJs;
                        break;
                }
                break;
            case "komposisi_fase_biaya_sub":
                if (!isset($_SESSION["NEW"]["komposisi_fase_biaya_sub"][$fase_urut][$sub_fase_id][$prodID])) {
                    unset($_SESSION["NEW"]["komposisi_fase_biaya_sub"][$fase_urut][$sub_fase_id]);
                }
                if ($key == "produk_dasar_id") {
                    $this->load->model("Mdls/MdlDtaBiayaProduksi");
                    $s = new MdlDtaBiayaProduksi;
                    $tempSupplies = $s->lookUpById($value)->result();
                    $arrData = array(
                        "produk_dasar_id" => $_GET["value"],
                        "produk_dasar_nama" => $tempSupplies[0]->nama,
                    );
                    $_SESSION["NEW"]["komposisi_fase_biaya_sub"][$fase_urut][$sub_fase_id][$prodID][$key] = $value;
                    $_SESSION["NEW"]["komposisi_fase_biaya_sub"][$fase_urut][$sub_fase_id][$prodID]["produk_dasar_nama"] = $tempSupplies[0]->nama;
                }
                else {
                    if ($key == "cat_id") {
                        $this->load->model("Mdls/MdlProdukRakitan");
                        $p = new MdlProdukRakitan();
                        $this->load->model("Mdls/MdlProdukRakitanPreBiaya");
                        $s = new MdlProdukRakitanPreBiaya;
                        $tempSupplies = $s->lookUpById($value)->result();
                        $arrData = array(
                            "cat_id" => $_GET["value"],
                            "cat_nama" => $tempSupplies[0]->nama,
                        );
                        $_SESSION["NEW"]["komposisi_fase_biaya_sub"][$fase_urut][$sub_fase_id][$prodID][$key] = $value;
                        $_SESSION["NEW"]["komposisi_fase_biaya_sub"][$fase_urut][$sub_fase_id][$prodID]["cat_nama"] = $tempSupplies[0]->nama;
                    }
                    else {
                        if ($key == "jml") {
                            $_SESSION["NEW"]["komposisi_fase_biaya_sub"][$fase_urut][$sub_fase_id][$prodID]["subtotal"] = $value * $_SESSION["NEW"]["komposisi_fase_biaya_sub"][$fase_urut][$sub_fase_id][$prodID]["harga"];
                        }
                    }
                    $_SESSION["NEW"]["komposisi_fase_biaya_sub"][$fase_urut][$sub_fase_id][$prodID][$key] = $value;
                }
                $jml = $_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]["jml"] * 1 > 0 ? $_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]["jml"] * 1 : 0;
                $harga = $_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]["harga"] * 1 > 0 ? $_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]["harga"] * 1 : 0;
                $satuan = $_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]["satuan"] != '' ? $_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]["satuan"] : "-";
                $nilai = $_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]["nilai"] * 1 > 0 ? $_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]["nilai"] * 1 : 0;
                $produk_dasar_id = isset($_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]['produk_dasar_id']) ? $_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]['produk_dasar_id'] * 1 : 0;
                //JSON OUTPUT HANDLE JS UI
                switch ($key) {
                    case "harga":
                        $subTotal = isset($_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]["jml"]) ? $_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]["jml"] * $value : 0;
                        $harga = $value;
                        $showButton = "";
                        if ($produk_dasar_id * 1 > 0 && $jml * 1 > 0 && $harga * 1 > 0) {
                            $showButton = "
                            var tmpIdForm=top.$('#$modes" . "_$fase_urut$sub_fase_id" . "$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', false);
                            $(tmpIdForm).addClass('btn-success');";
                        }
                        else {
                            $showButton = "
                            var tmpIdForm=top.$('#$modes" . "_$fase_urut$sub_fase_id" . "$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', true);
                            $(tmpIdForm).removeClass('btn-success');";
                        }

                        $outputJs = base64_encode("
                        top.$('#$modes" . "_$fase_urut$sub_fase_id" . "subtotal').html(addCommas($subTotal));
                        $showButton");

                        echo $outputJs;
                        break;
                    case "jml":
                        $showButton = "";
                        if ($produk_dasar_id * 1 > 0 && $jml * 1 > 0 && $harga * 1 > 0) {
                            $showButton = "
                            var tmpIdForm=top.$('#$modes" . "_$fase_urut$sub_fase_id" . "$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', false);
                            $(tmpIdForm).addClass('btn-success');";
                        }
                        else {
                            $showButton = "
                            var tmpIdForm=top.$('#$modes" . "_$fase_urut$sub_fase_id" . "$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', true);
                            $(tmpIdForm).removeClass('btn-success');";
                        }

                        $subTotal = isset($_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]["subtotal"]) ? $_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]["subtotal"] : 0;
                        $outputJs = base64_encode("
                        top.$('#$modes" . "_$fase_urut$sub_fase_id" . "subtotal').html(addCommas($subTotal));
                        $showButton");

                        echo $outputJs;
                        break;
                    case "produk_dasar_id":
                        $showButton = "";
                        if ($produk_dasar_id * 1 > 0 && $jml * 1 > 0 && $harga * 1 > 0) {
                            $showButton = "
                            var tmpIdForm=top.$('#$modes" . "_$fase_urut$sub_fase_id" . "$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', false);
                            $(tmpIdForm).addClass('btn-success');";
                        }
                        else {
                            $showButton = "
                            var tmpIdForm=top.$('#$modes" . "_$fase_urut$sub_fase_id" . "$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', true);
                            $(tmpIdForm).removeClass('btn-success');";
                        }

                        $subTotal = isset($_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]["subtotal"]) ? $_SESSION["NEW"][$modes][$fase_urut][$sub_fase_id][$prodID]["subtotal"] : 0;
                        $outputJs = base64_encode("
                        top.$('#$modes" . "_$fase_urut$sub_fase_id" . "subtotal').html(addCommas($subTotal));
                        top.$('#$modes" . "_$fase_urut$sub_fase_id" . "satuan').html('$satuan');
                        top.$('#$modes" . "_$fase_urut$sub_fase_id" . "nilai').html(addCommas($nilai));
                        top.$('#$modes" . "_$fase_urut$sub_fase_id" . "harga').val(addCommas($harga));
                        top.$('#$modes" . "_$fase_urut$sub_fase_id" . "jml').val(addCommas($jml));
                        $showButton");

                        echo $outputJs;
                        break;
                }
                break;
            case "komposisi_fase_timwork"://assingment, penunjukan,tasklist
                if (!isset($_SESSION["NEW"]["komposisi_fase_timwork"][$prodID])) {
                    unset($_SESSION["NEW"]["komposisi_fase_timwork"]);
                }
                $produkID = $this->uri->segment(4);
                if ($key == "produk_dasar_id") {
                    $this->load->model("Mdls/MdlEmployee_all");
                    $s = new MdlEmployee_all;
                    $tempSupplies = $s->lookUpById($value)->result();
                    $_SESSION["NEW"]["komposisi_fase_timwork"][$prodID][$key] = $value;
                    $_SESSION["NEW"]["komposisi_fase_timwork"][$prodID]["employee_nama"] = $tempSupplies[0]->nama;
                }
                else {
                    if ($key == "akses_id") {
                        $aksesData = aksesProject();
                        $_SESSION["NEW"]["komposisi_fase_timwork"][$prodID][$key] = $value;
                        $_SESSION["NEW"]["komposisi_fase_timwork"][$prodID]["akses_nama"] = $aksesData[$value]["nama"];
                    }
                    $_SESSION["NEW"]["komposisi_fase_timwork"][$prodID][$key] = $value;
                }
                break;
            case "komposisi_target":
                cekmerah("skip pakai langsung update akerna ada proses menghitung");
                break;
            case "timwork":
                if (!isset($_SESSION["NEW"]["timwork"][$prodID])) {
                    unset($_SESSION["NEW"]["timwork"]);
                }
                $produkID = $this->uri->segment(4);
                if ($key == "employee_id") {
                    $this->load->model("Mdls/MdlEmployee_all");
                    $s = new MdlEmployee_all;
                    $tempSupplies = $s->lookUpById($value)->result();
                    $_SESSION["NEW"]["timwork"][$prodID][$key] = $value;
                    $_SESSION["NEW"]["timwork"][$prodID]["employee_nama"] = $tempSupplies[0]->nama;
                }
                else {
                    if ($key == "akses_id") {
                        $aksesData = aksesProject();
                        $_SESSION["NEW"]["timwork"][$prodID][$key] = $value;
                        $_SESSION["NEW"]["timwork"][$prodID]["akses_nama"] = $aksesData[$value]["nama"];
                    }
                    $_SESSION["NEW"]["timwork"][$prodID][$key] = $value;
                }
                break;
            case "komposisi_bom":
                $paket_id = $_GET['paket_id'];

                if (!isset($_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID])) {
                    unset($_SESSION["NEW"][$modes][$fase_urut]);
        }

                if ($key == "produk_dasar_id") {
                    $_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID] = isset($_SESSION["PROED"][$prodID]["component"][$fase_urut]["produk"][$value]) ? $_SESSION["PROED"][$prodID]["component"][$fase_urut]["produk"][$value] : $_SESSION["PROED"][$prodID]["component"][$fase_urut]["item_komposit"][$value];
                }
                else {
                    if ($key == "jml") {
                        $_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["jml"] = $value;
                        $_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["subtotal"] = $value * $_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["harga"];
                    }
                }

                $jml    = $_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["jml"] * 1 > 0 ? $_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["jml"] * 1 : 0;
                $harga  = $_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["harga"] * 1 > 0 ? round($_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["harga"] * 1) : 0;
                $jual   = $_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["hrg_jual"] * 1 > 0 ? round($_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["hrg_jual"]*1) : 0;
                $satuan = $_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["satuan"] != '' ? $_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["satuan"] : "-";
                $produk_dasar_id = isset($_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]['produk_dasar_id']) ? $_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]['produk_dasar_id'] * 1 : 0;

                //JSON OUTPUT HANDLE JS UI
                switch ($key) {
                    case "jml":
                        if ($produk_dasar_id * 1 > 0 && $jml * 1 > 0 && $harga * 1 > 0) {
                            $showButton = "
                            var tmpIdForm=top.$('#".$modes."_$fase_urut$paket_id$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', false);
                            $(tmpIdForm).addClass('btn-success');";
                        }
                        else {
                            $showButton = "
                            var tmpIdForm=top.$('#".$modes."_$fase_urut$paket_id$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', true);
                            $(tmpIdForm).removeClass('btn-success');";
                        }
                        $subTotal = isset($_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["subtotal"]) ? $_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["subtotal"] : 0;
                        $outputJs = base64_encode("
                        top.$('#".$modes."_" . $fase_urut.$paket_id . "subtotal').html(addCommas($subTotal));
                        $showButton");
                        echo $outputJs;
                        break;
                    case "produk_dasar_id":
                        $showButton = "";
                        if ($produk_dasar_id * 1 > 0 && $jml * 1 > 0 && $harga * 1 > 0) {
                            $showButton = "
                            var tmpIdForm=top.$('#".$modes."_$fase_urut$paket_id$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', false);
                            $(tmpIdForm).addClass('btn-success');";
                        }
                        else {
                            $showButton = "
                            var tmpIdForm=top.$('#".$modes."_$fase_urut$paket_id$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', true);
                            $(tmpIdForm).removeClass('btn-success');";
                        }
                        $subTotal = isset($_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["subtotal"]) ? $_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["subtotal"] : 0;
                        $outputJs = base64_encode("
                        top.$('#".$modes."_" . $fase_urut.$paket_id . "subtotal').html(addCommas($subTotal));
                        top.$('#".$modes."_" . $fase_urut.$paket_id . "satuan').html('$satuan');
                        top.$('#".$modes."_" . $fase_urut.$paket_id . "jual').html(addCommas($jual));
                        top.$('#".$modes."_" . $fase_urut.$paket_id . "harga').html(addCommas($harga));
                        top.$('#".$modes."_" . $fase_urut.$paket_id . "jml').val(addCommas($jml));
                        $showButton");
                        echo $outputJs;
//                        echo "<br>" . json_encode($_SESSION["NEW"][$modes]);
                        break;
                }
                break;
            case "komposisi_bom_biaya":

                $paket_id = $_GET['paket_id'];

                if (!isset($_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID])) {
                    unset($_SESSION["NEW"][$modes][$fase_urut]);
                }

                if ($key == "produk_dasar_id") {
                    $_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID] = $_SESSION["PROED"][$prodID]["component"][$fase_urut]["biaya"][$value];
                }
                else {
                    if ($key == "jml") {
                        $_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["jml"] = $value;
                        $_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["subtotal"] = $value * $_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["harga"];
                    }
                }

                $jml             = $_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["jml"];
                $harga           = round($_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["harga"]);
                $cat_id          = $_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["cat_id"];
                $cat_nama        = $_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["cat_nama"];
                $satuan_id       = $_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["satuan_id"];
                $satuan_nama     = $_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["satuan"];
                $keterangan      = $_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["keterangan"];
                $produk_dasar_id = isset($_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]['produk_dasar_id']) ? $_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]['produk_dasar_id'] * 1 : 0;

                //JSON OUTPUT HANDLE JS UI
                switch ($key) {
                    case "jml":
                        $showButton = "";
                        if ($cat_id * 1 > 0 && $satuan_id * 1 > 0 && $produk_dasar_id * 1 > 0 && $jml * 1 > 0 && $harga * 1 > 0) {
                            $showButton = "
                            var tmpIdForm=top.$('#".$modes."_$fase_urut$paket_id$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', false);
                            $(tmpIdForm).addClass('btn-success');";
                        }
                        else {
                            $showButton = "
                            var tmpIdForm=top.$('#".$modes."_$fase_urut$paket_id$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', true);
                            $(tmpIdForm).removeClass('btn-success');";
                        }

                        $subTotal = isset($_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["subtotal"]) ? round($_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["subtotal"]) : round($jml * $harga);

                        $outputJs = base64_encode("
                        top.$('#".$modes."_" . $fase_urut.$paket_id . "subtotal').html(addCommas($subTotal));
                        $showButton");

                        echo $outputJs;
                        break;
                    case "produk_dasar_id":
                        $showButton = "";
                        if ($cat_id * 1 > 0 && $satuan_id * 1 > 0 && $produk_dasar_id * 1 > 0 && $jml * 1 > 0 && $harga * 1 > 0) {
                            $showButton = "
                            var tmpIdForm=top.$('#".$modes."_$fase_urut$paket_id$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', false);
                            $(tmpIdForm).addClass('btn-success');";
                        }
                        else {
                            $showButton = "
                            var tmpIdForm=top.$('#".$modes."_$fase_urut$paket_id$prodID');
                            var idform=$(tmpIdForm).attr('idform');
                            $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
                            $(tmpIdForm).prop('disabled', true);
                            $(tmpIdForm).removeClass('btn-success');";
                        }
                        $subTotal = isset($_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["subtotal"]) ? round($_SESSION["NEW"][$modes][$fase_urut][$paket_id][$prodID]["subtotal"]) : round($jml * $harga);
                        $outputJs = base64_encode("
                        top.$('#".$modes."_" . $fase_urut.$paket_id . "subtotal').html(addCommas($subTotal));
                        top.$('#".$modes."_" . $fase_urut.$paket_id . "cat_id').html('$cat_nama');
                        top.$('#".$modes."_" . $fase_urut.$paket_id . "keterangan').html('$keterangan');
                        top.$('#".$modes."_" . $fase_urut.$paket_id . "satuan_id').html('$satuan_nama');
                        top.$('#".$modes."_" . $fase_urut.$paket_id . "harga').html(addCommas($harga));
                        top.$('#".$modes."_" . $fase_urut.$paket_id . "jml').val(addCommas($jml));
                        $showButton");
                        echo $outputJs;
                        break;
                }
                break;
            }
        $resultID = isset($_GET['result']) ? trim($_GET['result']) : (isset($_POST['result']) ? $_POST['result'] : "");
        if ($resultID != "") {
            echo "<script>
                var iframe = top.document.getElementById('$resultID');
                iframe.src=iframe.src;
                top.window.location.reload();
            </script>";
        }
    }

    public function addData()
    {
        //wip di eindex untuk next project
        $this->load->model("Mdls/" . "MdlProjectKomposisi");
        $this->load->model("Mdls/" . "MdlProdukProject");
        $this->load->model("Mdls/" . "MdlProjectKomposisiWorkorder");
        $this->load->model("Mdls/" . "MdlProjectKomposisiWorkorderSub");
        $this->load->model("Mdls/" . "MdlSupplies");
        $this->load->model("Mdls/" . "MdlTimWorkProject");
        $this->load->model("Mdls/" . "MdlProdukKomposisiPaket"); //pf
        $this->load->model("Mdls/" . "MdlProjectProdukPaket");

        $mdlName = $this->uri->segment(4);
        $this->load->model("Mdls/$mdlName");
        $pf = new $mdlName();
        $key = $_GET["mode"];

        $produk_id = $this->uri->segment(5);

        //region satuan autopatch
        $this->load->model("Mdls/MdlSatuan");
        $s = new MdlSatuan();
        $tempsatuan = $s->lookUpAll()->result();
        $dtaSatuan = array();
        if (sizeof($tempsatuan) > 0) {
            foreach ($tempsatuan as $tempsatuan_0) {
                $dtaSatuan[$tempsatuan_0->id] = $tempsatuan_0->nama;
            }
        }

        //region satuan/size autopatch
        $this->load->model("Mdls/MdlProdukSize");
        $size = new MdlProdukSize();
        $tempSize = $size->lookUpAll()->result();
        $dtaSize = array();
        if (sizeof($tempSize) > 0) {
            foreach ($tempSize as $tempSize_0) {
                $dtaSize[$tempSize_0->id] = $tempSize_0->nama;
            }
        }

        //endregion
        $masterPID = $produk_id;
        $pr = new MdlProdukProject();
        $tempProduk = $pr->lookupById($produk_id)->result();
        $master_nama = $tempProduk[0]->nama;

        $this->db->trans_start();
        $toInsert = array();
        switch ($key) {
            case "produk_fase":
                // $addColom = array("produk_id"=>);
                arrPrintWebs($_SESSION["NEW"][$key][$masterPID]);
                $pr = new MdlProdukProject();
                $tempProduk = $pr->lookupById($masterPID)->result();
                // arrprint($tempProduk);
                //                cekHere("[$key] [$masterPID]");
                //                arrPrint($_SESSION["NEW"]);
                //                $_SESSION["NEW"] = null;
                //                unset($_SESSION["NEW"]);
                //                mati_disini(__LINE__);
                $master_nama = $tempProduk[0]->nama;
                $tempMaster = array(
                    "qty" => $_SESSION["NEW"][$key][$masterPID]["qty"],
                    "produk_id" => $masterPID,
                    "produk_nama" => $master_nama,
                    "nama" => $_SESSION["NEW"][$key][$masterPID]["nama"],
                    "keterangan" => $_SESSION["NEW"][$key][$masterPID]["keterangan"],
                    "oleh_id" => $this->session->login["id"],
                    "oleh_nama" => $this->session->login["nama"],
                    "employee_id" => $_SESSION["NEW"][$key][$masterPID]["employee_id"],
                    "lokasi" => $_SESSION["NEW"][$key][$masterPID]["lokasi"],
                    "dtime" => date("Y-m-d H:i"),

                );
                //                arrPrintWebs($tempMaster);

                $pf->addFilters(array());
                $pf->addFilters("status=1");
                $pf->addFilters("trash=0");
                $pf->addFilters("produk_id=$masterPID");
                $cekBeforeInsert = $pf->lookupall->result();

                if($cekBeforeInsert){
                    matiHere("$master_nama sudah terdaftar, silahkan edit untuk menyesuaikan.");
                }

                $insertID = $pf->addData($tempMaster) or matiHere("gagal menambahkan data");
//                cekLime($this->db->last_query());
                if (method_exists($pf, "paramSyncNamaNama")) {
//                    cekHitam("ada pram nama nama");
                    $syncNamaNamaMdls = method_exists($pf, "paramSyncNamaNama") ? $pf->paramSyncNamaNama() : mati_disini("paramSyncNamaNama belum terdifine");
                    foreach ($syncNamaNamaMdls as $syncNamaNamaMdl => $syncNamaNamaParams) {
                        $id_ygdisync = isset($_SESSION["NEW"][$key][$masterPID][$syncNamaNamaParams['id']]) ? $_SESSION["NEW"][$key][$masterPID][$syncNamaNamaParams['id']] : "";
                        // $o->setTokoId(my_toko_id());
//                        arrPrint($syncNamaNamaParams['id']);
//                        arrPrint($id_ygdisync);
                        if ($id_ygdisync > 0) {
                            $pf->syncNamaNama($id_ygdisync);
                            cekBiru($this->db->last_query());
                        }
                        else {
                            cekBiru("fale $syncNamaNamaMdl");
                        }
                    }
//                     matiHere(__LINE__);
// cekBiru($id_ygdisync);
                    // $o->syncNamaNama();
                }
                else {
                    cekHitam("gak aada pram nama nama");
                }
                $this->cloneMasterWorkOrder($insertID, $tempMaster);//untuk nulis auto sub fase
//                matiHEre(__LINE__);
                if ($insertID) {
                    unset($_SESSION["NEW"][$key][$masterPID]);
                }

                break;
            case "komposisi_fase":

                $masterFase = $_GET["fase_id"];
                if (!isset($_GET["fase_id"])) {
                    matiHere("gagal mendeteksi fase produksi! Silahkan refresh halaman dan coba kembali");
                }

                $this->load->model("Mdls/MdlProdukForProject");
                $p = new MdlProdukForProject();
                $tempProdukDasar = $p->lookupById($_SESSION["NEW"][$key][$masterFase][$masterPID]["produk_dasar_id"])->result();

                $produk_jenis = "";
                if (!empty($tempProdukDasar)) {
                    foreach ($tempProdukDasar as $prd) {
                        $produk_jenis = $prd->jenis;
                    }
                }

                $master_nama = $tempProduk[0]->nama;

//                $ec = json_encode($_SESSION["NEW"][$key][$masterFase][$masterPID]);
//                matiHere($ec . "<br>" . __LINE__);

                $tempMaster = array(
                    "produk_id" => $masterPID,
                    "produk_nama" => $master_nama,
                    "bahan_utama" => $produk_jenis == "item" || $produk_jenis == "project" ? 1 : 0,
                    // "cabang_id"=>$tempProduk[0]->cabang_id,
                    "produk_dasar_id" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["produk_dasar_id"],
                    "produk_dasar_nama" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["produk_dasar_nama"],
                    "satuan_id" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["satuan_id"],
                    "satuan" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["satuan_id"]==1 ? $dtaSatuan[$_SESSION["NEW"][$key][$masterFase][$masterPID]["satuan_id"]] : $dtaSize[$_SESSION["NEW"][$key][$masterFase][$masterPID]["satuan_id"]],
                    "jml" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["jml"],
                    "nilai" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["nilai"],
                    "harga" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["harga"],
                    "fase_id" => $masterFase,
                    "gudang_id" => $masterFase . "$masterPID",
                    "gudang2_id" => $masterFase . "$masterPID",
                    "author" => $this->session->login['id'],
                    "jenis" => "produk",
                    // "jenis_transaksi" => "7778." . $masterPID . "." . $masterFase,
                    "jenis_transaksi" => "5582",
                    "dtime" => date("Y-m-d H:i"),
                    "cat_id" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["cat_id"],//id kelompok biaya
                    "cat_nama" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["cat_nama"],
                    "debet" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["jml"] * $_SESSION["NEW"][$key][$masterFase][$masterPID]["harga"],
                    "saldo" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["jml"] * $_SESSION["NEW"][$key][$masterFase][$masterPID]["harga"],
                    "qty_debet" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["jml"],
                    "qty_saldo" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["jml"],

                    "hrg_hpp" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["nilai"],
                    "hrg_ppv" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["ppv"],
                    "hrg_jual" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["jual"],
                );
//                arrprint($tempMaster);
//                cekHitam($this->db->last_query());
                $insertID = $pf->addData($tempMaster) or matiHere("gagal menambahkan data*" . " | " . $this->db->last_query());

                $this->cloneMasterWorkOrderKomposisi($masterPID, $tempMaster, $mdlName);//untuk nulis komposisi sub workorder
//                 matiHere(__LINE__);
                if ($insertID) {
                    unset($_SESSION["NEW"]);
                }
                $pakai_ini = 0;
                if ($pakai_ini > 0) {
                    $nextFase = $masterFase + 1;

                    $p = new MdlProdukFase();
                    // $kf = new MdlProdukKomposisiFase();
                    // $kf = new MdlProdukKomposisiFase();
                    $pf->setFilters(array());
                    $pf->addFilter("status='1'");
                    $pf->addFilter("trash='0'");
                    $pf->addFilter("produk_id='$produk_id'");
                    $allKomposisi = $pf->lookUpKomposisiFase();
                    cekLime($this->db->last_query());
                    // $p->AddFilter("produk_id='$produk_id'");
                    $availFase = $p->lookUpAvailFase($produk_id);
                    $jmlFase = count($availFase);


                    for ($i = 1; $i <= $jmlFase; $i++) {
                        /*
                         * update jika(data lama di trash=1),lalu insert data baru sesuai komposisi baru untuk produk target
                         * ada perubahan komposisi produk
                         * ada perubahan komposisi biaya
                         * ada perubahan data wip target
                         */
                        if ($i > $masterFase) {
                            //lihat data fase sebelumnya
                            $prevFase = $i - 1;
                            $prevDataFase = $allKomposisi[$prevFase];
                            $curentNextFase = $allKomposisi[$i];
                            $prevSrc = $prevDataFase["target"];
                            switch ($key) {
                                case "komposisi_target":
                                    $harga = $prevDataFase["target"][0]["harga"];
                                    $nilai = $prevDataFase["target"][0]["nilai"];
                                    $relID = $prevDataFase["target"][0]["produk_dasar_id"];
                                    $refData = array(
                                        "nilai" => $nilai,
                                        "harga" => $harga,
                                        "dtime" => dtimeNow("Y-m-d H:i"),
                                        "author" => $this->session->login["id"],
                                    );


                                    $newDataTemp = $curentNextFase["produk"];
                                    $toInsert = array();
                                    foreach ($newDataTemp as $newDataTemp__0) {
                                        // unset($newDataTemp__0["id"]);
                                        $tmp = array();
                                        foreach ($newDataTemp__0 as $k => $v) {
                                            if (isset($refData[$k])) {
                                                $v = $refData[$k];
                                            }
                                            $tmp[$k] = $v;
                                        }
                                        $toInsert[$newDataTemp__0["produk_dasar_id"]] = $tmp;

                                    }
                                    $toUpdateTmp = isset($toInsert[$relID]) ? $toInsert[$relID] : array();
                                    if (count($toUpdateTmp) > 0) {
                                        $whereNextCurent = array(
                                            "id" => $toUpdateTmp["id"],
                                        );
                                        $trashNextCurentUpdate = array(
                                            "status" => "0",
                                            "trash" => "1",
                                        );
                                        unset($toUpdateTmp["id"]);
                                        $pf->setFilters(array());
                                        $pf->updateData($whereNextCurent, $trashNextCurentUpdate) or matiHere("gagl update komposisi");

                                        $pf->setFilters(array());
                                        $pf->addData($toUpdateTmp) or matiHere("gagal update komposisi");

                                        $pf->setFilters(array());
                                        $pf->addFilter("produk_id='$produk_id'");
                                        $pf->addFilter("fase_id='$i'");
                                        $pf->addFilter("status='1'");
                                        $pf->addFilter("trash='0'");
                                        $pf->addFilter("jenis='target'");
                                        $tempTarget = $pf->lookUpAll()->result();
                                        if (count($tempTarget) > 0) {
                                            //ditrash data lamanya
                                            $id_toupdate = $tempTarget[0]->id;
                                            $updatetrash = array(
                                                "status" => "0",
                                                "trash" => "1",
                                            );
                                            $where = array(
                                                "id" => $id_toupdate,
                                            );
                                            $pf->updateData($where, $updatetrash) or matiHere("gagal memperbaharui data, silahkan coba beberapa saat lagi.");
                                            // arrPrint($tempTarget);
                                            // matiHere();
                                        }
                                        // cekHitam($this->db->last_query());
                                    }
                                    break;
                                default:
                                    break;
                            }
                            //insert produk target nilai dengan nilai baru komposisi
                            // arrPrint($prevSrc);
                            matiHEre(__LINE__);
                            cekHitam($i);
                        }
                        // matiHEre();
                    }
                    matiHere();


                    // arrprint($faseProduk);
                    cekKuning($this->db->last_query());
                    matiHere();
                    $pf->setFilters(array());
                    $pf->addFilter("produk_id=$produk_id");
                    $pf->addFilter("fase_id=$nextFase");
                    $pf->addFilter("status='1'");
                    $pf->addFilter("trash='0'");
                    $pf->addFilter("produk_dasar_id=$suppliesID");
                    $tempNext = $pf->lookUpAll()->result();
                    $toAutoupdate = array();
                    if (count($tempNext) > 0) {
                        $updateDefault = array(
                            "dtime" => dtimeNow("Y-m-d H:i"),
                            "author" => $this->session->login["id"],
                        );
                        // cekHitam("ada data");
                        foreach ($tempNext[0] as $key => $values) {
                            // arrPrint($tempNext_0);
                            if ($key == "nilai") {
                                $values = $tempNilai;
                            }
                            if ($key == "harga") {
                                $values = $tempNilai;
                            }
                            if ($key != "id") {
                                $toAutoupdate[$key] = isset($updateDefault[$key]) ? $updateDefault[$key] : $values;
                            }

                        }
                        arrPrint($toAutoupdate);
                        // foreach()
                    }
                    cekMerah($this->db->last_query());
                }
                break;
            case "komposisi_bom":

                $masterFase = $_GET["fase_id"];
                $paket_id = $_GET["paket_id"];
                $this->load->model("Mdls/" . "MdlProjectProdukPaket");
                $prpkg = new MdlProjectProdukPaket();
                $tempPkg = $prpkg->lookupByPID($paket_id)->result();
                $paket_nama = $tempPkg[0]->nama;

                $tempMaster = array(
                    "produk_id" => $paket_id,
                    "produk_nama" => $paket_nama,
                    "project_id" => $produk_id,
                    "project_nama" => $master_nama,
                    "produk_dasar_id" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["produk_dasar_id"],
                    "produk_dasar_nama" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["produk_dasar_nama"],
                    "jml" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["jml"],
                    "nilai" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["hrg_jual"],
                    "dtime" => date("Y-m-d H:i"),
                    "author" => $this->session->login['id'],
                    "bahan_utama" => $produk_jenis == "item" || $produk_jenis == "project" ? 1 : 0,
                    "satuan_id" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["satuan_id"],
                    "satuan_nama" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["satuan"],
                    "jenis" => "produk",
                    "harga" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["harga"],
                    "debet" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["jml"] * $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["harga"],
                    "saldo" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["jml"] * $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["harga"],
                    "qty_debet" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["jml"],
                    "qty_saldo" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["jml"],
                    "cat_id" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["cat_id"],//id kelompok biaya
                    "cat_nama" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["cat_nama"],
                );

                $insertID = $pf->addData($tempMaster) or matiHere("gagal menambahkan data*" . " | " . $this->db->last_query());

                if ($insertID) {
                    unset($_SESSION["NEW"]);
                }
//                $lastQ = $this->db->last_query();
//                echo $lastQ;
////                echo json_encode($_SESSION["NEW"][$key][$masterFase][$masterPID]);
//                matiHere($key);

                break;
            case "komposisi_bom":

                $masterFase = $_GET["fase_id"];
                $paket_id = $_GET["paket_id"];
                $this->load->model("Mdls/" . "MdlProjectProdukPaket");
                $prpkg = new MdlProjectProdukPaket();
                $tempPkg = $prpkg->lookupByPID($paket_id)->result();
                $paket_nama = $tempPkg[0]->nama;

                $tempMaster = array(
                    "produk_id" => $paket_id,
                    "produk_nama" => $paket_nama,
                    "project_id" => $produk_id,
                    "project_nama" => $master_nama,
                    "produk_dasar_id" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["produk_dasar_id"],
                    "produk_dasar_nama" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["produk_dasar_nama"],
                    "jml" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["jml"],
                    "nilai" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["hrg_jual"],
                    "dtime" => date("Y-m-d H:i"),
                    "author" => $this->session->login['id'],
                    "bahan_utama" => $produk_jenis == "item" || $produk_jenis == "project" ? 1 : 0,
                    "satuan_id" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["satuan_id"],
                    "satuan_nama" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["satuan"],
                    "jenis" => "produk",
                    "harga" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["harga"],
                    "debet" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["jml"] * $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["harga"],
                    "saldo" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["jml"] * $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["harga"],
                    "qty_debet" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["jml"],
                    "qty_saldo" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["jml"],
                    "cat_id" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["cat_id"],//id kelompok biaya
                    "cat_nama" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["cat_nama"],
                );

                $insertID = $pf->addData($tempMaster) or matiHere("gagal menambahkan data*" . " | " . $this->db->last_query());

                if ($insertID) {
                    unset($_SESSION["NEW"]);
                }
//                $lastQ = $this->db->last_query();
//                echo $lastQ;
////                echo json_encode($_SESSION["NEW"][$key][$masterFase][$masterPID]);
//                matiHere($key);

                break;
            case "komposisi_bom_biaya":

                $masterFase = $_GET["fase_id"];
                $paket_id = $_GET["paket_id"];
                $this->load->model("Mdls/" . "MdlProjectProdukPaket");
                $prpkg = new MdlProjectProdukPaket();
                $tempPkg = $prpkg->lookupByPID($paket_id)->result();
                $paket_nama = $tempPkg[0]->nama;

                $ket_biaya = isset($_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["keterangan"]) && $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["keterangan"] != "" ? " (" . $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["keterangan"].")" : "";

                $tempMaster = array(
                    "produk_id" => $paket_id,
                    "produk_nama" => $paket_nama,
                    "project_id" => $produk_id,
                    "project_nama" => $master_nama,
                    "produk_dasar_id" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["produk_dasar_id"],
                    "produk_dasar_nama" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["produk_dasar_nama"] . $ket_biaya,
                    "jml" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["jml"],
                    "nilai" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["nilai"],
                    "dtime" => date("Y-m-d H:i"),
                    "author" => $this->session->login['id'],
                    "bahan_utama" => $produk_jenis == "item" || $produk_jenis == "project" ? 1 : 0,
                    "satuan_id" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["satuan_id"],
                    "satuan_nama" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["satuan"],
                    "jenis" => "biaya",
                    "harga" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["harga"],
                    "debet" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["jml"] * $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["harga"],
                    "saldo" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["jml"] * $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["harga"],
                    "qty_debet" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["jml"],
                    "qty_saldo" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["jml"],
                    "cat_id" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["cat_id"],//id kelompok biaya
                    "cat_nama" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["cat_nama"],
                    "keterangan" => $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["keterangan"],
                );

                $insertID = $pf->addData($tempMaster) or matiHere("gagal menambahkan data LINE: " . __LINE__ . " | " . $this->db->last_query() . " | <br>" . json_encode($tempMaster));

//                //details biaya jasa
//                $this->load->model("Mdls/" . "MdlProjectKomponenBiayaDetails");
//                $by = new MdlProjectKomponenBiayaDetails();
//                $by->addFilter("biaya_id='".$_SESSION["NEW"][$key][$masterFase][$masterPID]["produk_dasar_id"]."'");
//                $arrByDetails = $by->lookUpAll()->result();
//
//                if(!empty($arrByDetails)){
//                    $byDataCreate=array();
//                    $this->load->model("Mdls/" . "MdlProjectKomponenBiayaDetailsRab");
//                    $byCr = new MdlProjectKomponenBiayaDetailsRab();
//                    foreach($arrByDetails as $byData){
//                        $byDataCreate = (array)$byData;
//                        $jml = $byDataCreate["jml"];
//                        $byDataCreate["project_id"] = $masterPID; //project ID
//                        $byDataCreate["project_nama"] = $master_nama; //project Nama
//                        $byDataCreate["fase_id"] = $masterFase; //fase id
//                        $byDataCreate["link_id"] = $insertID;
//                        $byDataCreate["jml"] = $_SESSION["NEW"][$key][$masterFase][$paket_id][$masterPID]["jml"] * $jml; //jml
//                        $byDataCreate["jenis_transaksi"] = "main";
//
//                        unset($byDataCreate["id"]);
//                        $ins = $byCr->addData($byDataCreate) or matiHere("masterPID: $masterPID <br>gagal menambahkan data*" . " | " . $this->db->last_query());
//                    }
//                }

                if ($insertID) {
                    unset($_SESSION["NEW"]);
                }

                break;
            case "produk_paket":

                $nama_paket = $_GET['nama_paket'];

                $tempMaster = array(
                    "nama" => $nama_paket,
                    "project_id" => $masterPID,
                    "project_nama" => $master_nama,
                    "dtime" => date("Y-m-d H:i:s"),
                    "oleh_id" => $this->session->login['id'],
                    "oleh_nama" => $this->session->login['nama'],
                    "status" => 1,
                    "trash" => 0,
                );

                $insertID = $pf->addData($tempMaster) or matiHere("gagal menambahkan data*" . " | " . $this->db->last_query());

                break;
            case "komposisi_fase_room":

                $masterFase = $_GET["fase_id"];
                if (!isset($_GET["fase_id"])) {
                    matiHere("gagal mendeteksi fase produksi! Silahkan refresh halaman dan coba kembali");
                }

                $master_nama = $tempProduk[0]->nama;

                $tempMaster = array(
                    "produk_id" => $masterPID,
                    "produk_nama" => $master_nama,
                    "room_id" => strtotime(date("Y-m-d H:i:s")),
                    "room_nama" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["room_nama"],
                    "room_ket" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["room_ket"],
                    "fase_id" => $masterFase,
                    "gudang_id" => $masterFase . "$masterPID",
                    "gudang2_id" => $masterFase . "$masterPID",
                    "author" => $this->session->login['id'],
                    "jenis" => "room",
                    "jenis_transaksi" => "5582",
                    "dtime" => date("Y-m-d H:i:s"),
                    "jml" => 1,
                    "produk_dasar_id" => strtotime(date("Y-m-d H:i:s")),
                    "produk_dasar_nama" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["room_nama"],
                );

                $insertID = $pf->addData($tempMaster) or matiHere("LINE: " . __LINE__ . "| gagal menambahkan data*" . " | " . $this->db->last_query());
                $this->cloneMasterWorkOrderKomposisi($masterPID, $tempMaster, "room");//untuk nulis komposisi sub workorder

                if ($insertID) {
                    unset($_SESSION["NEW"]);
                }
                break;
            case "produk_komposisi_fase_room":

                $selID = $_GET["selID"];
                $masterFase = $_GET["fase_id"];
                if (!isset($_GET["fase_id"])) {
                    matiHere("gagal mendeteksi fase produksi! Silahkan refresh halaman dan coba kembali");
                }

                $master_nama = $tempProduk[0]->nama;
                $tempMaster = array(
                    "produk_id" => $masterPID,
                    "produk_nama" => $master_nama,
                    "room_id" => $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["room_id"],
                    "room_nama" => $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["room_nama"],
                    "room_ket" => $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["room_ket"],
                    "fase_id" => $masterFase,
                    "gudang_id" => $masterFase . "$masterPID",
                    "gudang2_id" => $masterFase . "$masterPID",
                    "author" => $this->session->login['id'],
                    "jenis" => "produk_room",
                    "jenis_transaksi" => "5582",
                    "dtime" => date("Y-m-d H:i:s"),
                    "jml" => $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["jml"],
                    "produk_dasar_id" => $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["produk_dasar_id"],
                    "produk_dasar_nama" => $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["produk_dasar_nama"],

                    "satuan" => $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["satuan"],
                    "harga" => $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["harga"],
                    "hrg_hpp" => $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["nilai"],
                    "hrg_ppv" => $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["ppv"],
                    "hrg_jual" => $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["jual"],
                    "nilai" => $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["nilai"],
                    "saldo" => $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["harga"] * $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["jml"],
                );
                $insertID = $pf->addData($tempMaster) or matiHere("LINE: " . __LINE__ . "| gagal menambahkan data*" . " | " . $this->db->last_query());

//                $this->cloneMasterWorkOrderKomposisi($masterPID,$tempMaster,"room");//untuk nulis komposisi sub workorder

                if ($insertID) {
                    unset($_SESSION["NEW"]);
                }

//                die();

                break;
            case "produk_komposisi_fase_room_biaya":

                $selID = $_GET["selID"];
                $masterFase = $_GET["fase_id"];
                if (!isset($_GET["fase_id"])) {
                    matiHere("gagal mendeteksi fase produksi! Silahkan refresh halaman dan coba kembali");
                }

                $master_nama = $tempProduk[0]->nama;

                $tempMaster = array(
                    "produk_id" => $masterPID,
                    "produk_nama" => $master_nama,
                    "room_id" => $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["room_id"],
                    "room_nama" => $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["room_nama"],
                    "room_ket" => $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["room_ket"],
                    "fase_id" => $masterFase,
                    "gudang_id" => $masterFase . "$masterPID",
                    "gudang2_id" => $masterFase . "$masterPID",
                    "author" => $this->session->login['id'],
                    "jenis" => "biaya_room",
                    "jenis_transaksi" => "5582",
                    "dtime" => date("Y-m-d H:i:s"),
                    "jml" => $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["jml"],
                    "biaya_source_id" => $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["biaya_source_id"],
                    "produk_dasar_id" => $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["produk_dasar_id"],
                    "produk_dasar_nama" => $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["produk_dasar_nama"],

                    "satuan" => $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["satuan"],
                    "satuan_id" => $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["satuan_id"],
                    "cat_id" => $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["cat_id"],
                    "cat_nama" => $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["cat_nama"],

                    "harga" => $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["harga"],
                    "nilai" => $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["harga"],
                    "saldo" => $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["harga"] * $_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]["jml"],
                );

                echo json_encode($_SESSION["NEW"][$key][$masterFase][$selID][$masterPID]);

                $insertID = $pf->addData($tempMaster) or matiHere("LINE: " . __LINE__ . "| gagal menambahkan data*" . " | " . $this->db->last_query());
//                $this->cloneMasterWorkOrderKomposisi($masterPID,$tempMaster,"room");//untuk nulis komposisi sub workorder
                if ($insertID) {
                    unset($_SESSION["NEW"]);
                }

//                die();
                break;
            case "save_jual_project":

                $json = file_get_contents('php://input');
                $json_post = json_decode($json);
                $master_nama = $tempProduk[0]->nama;
                $masterFase = $_GET["fase_id"];

                $pf->setFilters(array());
                $pf->addFilter("status='1'");
                $pf->addFilter("trash='0'");
                $pf->addFilter("produk_id='$produk_id'");
                $pf->addFilter("fase_id='$masterFase'");
                $pf->addFilter("jenis='jual'");

                $cek = $pf->lookupAll()->result();

                $tempMaster = array(
                    "produk_id" => $masterPID,
                    "produk_nama" => $master_nama,
                    "jml" => 1,
                    "nilai" => $json_post->hbom,
                    "harga" => $json_post->jual_project,
                    "fase_id" => $masterFase,
                    "gudang_id" => $masterFase . "$masterPID",
                    "gudang2_id" => $masterFase . "$masterPID",
                    "author" => $this->session->login['id'],
                    "jenis" => "jual",
                    "jenis_transaksi" => "5582",
                    "dtime" => date("Y-m-d H:i"),
                );

                $status = "";

                if (!empty($cek)) {
                    $whereNextCurent = array(
                        "id" => $cek[0]->id,
                    );
                    $pf->setFilters(array());
                    $update = $pf->updateData($whereNextCurent, $tempMaster) or matiHere("gagl update komposisi");
                    if ($update) {
                        $status = "update";
                    }
                }
                else {
                    $insertID = $pf->addData($tempMaster) or matiHere("gagal menambahkan data*" . " | " . $this->db->last_query() . "<br>" . $json_post->jual_project);
                    if ($insertID) {
                        $status = "insert";
                    }
                }

                break;
            case "save_diskon":

                $json = file_get_contents('php://input');
                $json_post = json_decode($json);
                $master_nama = $tempProduk[0]->nama;
                $masterFase = $_GET["fase_id"];

                $pf->setFilters(array());
                $pf->addFilter("status='1'");
                $pf->addFilter("trash='0'");
                $pf->addFilter("produk_id='$produk_id'");
                $pf->addFilter("fase_id='$masterFase'");
                $pf->addFilter("jenis='diskon'");

                $cek = $pf->lookupAll()->result();

                $tempMaster = array(
                    "produk_id" => $masterPID,
                    "produk_nama" => $master_nama,
                    "jml" => 1,
                    "nilai" => $json_post->hbom,
                    "harga" => $json_post->diskon_project,
                    "fase_id" => $masterFase,
                    "gudang_id" => $masterFase . "$masterPID",
                    "gudang2_id" => $masterFase . "$masterPID",
                    "author" => $this->session->login['id'],
                    "jenis" => "diskon",
                    "jenis_transaksi" => "5582",
                    "dtime" => date("Y-m-d H:i"),
                );

                $status = "";

                if (!empty($cek)) {
                    $whereNextCurent = array(
                        "id" => $cek[0]->id,
                    );
                    $pf->setFilters(array());
                    $update = $pf->updateData($whereNextCurent, $tempMaster) or matiHere("gagl update komposisi");
                    if ($update) {
                        $status = "update";
                    }
                }
                else {
                    $insertID = $pf->addData($tempMaster) or matiHere("gagal menambahkan data*" . " | " . $this->db->last_query() . "<br>" . $json_post->diskon_project);
                    if ($insertID) {
                        $status = "insert";
                    }
                }

                break;
            case "komposisi_fase_biaya":
                $masterFase = $_GET["fase_id"];
                if (!isset($_GET["fase_id"])) {
                    matiHere("gagal mendeteksi recana kerja! Silahkan refresh halaman dan coba kembali");
                }
                // $masterPID = array_keys($_SESSION["NEW"][$key])[0];
                // arrPrint($_SESSION["NEW"][$key][$masterPID]);
                // matiHere();
                // $this->load->model("Mdls/MdlProdukRakitan");
                // $pr = new MdlProdukRakitan();
                // $tempProduk = $pr->lookupById($masterPID)->result();
                // $master_nama = $tempProduk[0]->nama;
                $tempMaster = array(
                    "produk_id" => $masterPID,
                    "produk_nama" => $master_nama,
                    "satuan_id" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["satuan_id"],
                    "satuan" => $dtaSatuan[$_SESSION["NEW"][$key][$masterFase][$masterPID]["satuan_id"]],
                    // "cabang_id"=>$tempProduk[0]->cabang_id,
                    "produk_dasar_id" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["produk_dasar_id"],
                    "produk_dasar_nama" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["produk_dasar_nama"],
                    "keterangan" => isset($_SESSION["NEW"][$key][$masterFase][$masterPID]["keterangan"]) ? trim($_SESSION["NEW"][$key][$masterFase][$masterPID]["keterangan"]) : "",
                    // "satuan"=>$_SESSION["NEW"][$key][$masterPID]["satuan"],
                    "jml" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["jml"],
                    "nilai" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["harga"],
                    "harga" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["harga"],
                    "fase_id" => $masterFase,
                    "gudang_id" => $masterFase . "$masterPID",
                    "gudang2_id" => $masterFase . "$masterPID",
                    "author" => $this->session->login['id'],
                    // "jenis_transaksi"   => "7778." . $masterPID . "." . $masterFase,
                    "jenis_transaksi" => "5582",
                    "jenis" => "biaya",
                    "dtime" => date("Y-m-d H:i"),
                    "cat_id" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["cat_id"],
                    "cat_nama" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["cat_nama"],
                    "debet" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["jml"] * $_SESSION["NEW"][$key][$masterFase][$masterPID]["harga"],
                    "saldo" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["jml"] * $_SESSION["NEW"][$key][$masterFase][$masterPID]["harga"],
                    "qty_debet" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["jml"],
                    "qty_saldo" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["jml"],
                );

                $insertID = $pf->addData($tempMaster) or matiHere("masterPID: $masterPID <br>gagal menambahkan data* LINE: " . __LINE__ . " | " . $this->db->last_query() . " | <br>");

                //details biaya jasa
                $this->load->model("Mdls/" . "MdlProjectKomponenBiayaDetails");
                $by = new MdlProjectKomponenBiayaDetails();
                $by->addFilter("biaya_id='".$_SESSION["NEW"][$key][$masterFase][$masterPID]["produk_dasar_id"]."'");
                $arrByDetails = $by->lookUpAll()->result();

                if(!empty($arrByDetails)){
                    $byDataCreate=array();
                    $this->load->model("Mdls/" . "MdlProjectKomponenBiayaDetailsRab");
                    $byCr = new MdlProjectKomponenBiayaDetailsRab();
                    foreach($arrByDetails as $byData){
                        $byDataCreate = (array)$byData;
                        $jml = $byDataCreate["jml"];
                        $byDataCreate["project_id"] = $masterPID; //project ID
                        $byDataCreate["project_nama"] = $master_nama; //project Nama
                        $byDataCreate["fase_id"] = $masterFase; //fase id
                        $byDataCreate["link_id"] = $insertID;
                        $byDataCreate["satuan_id"] = $_SESSION["NEW"][$key][$masterFase][$masterPID]["satuan_id"];
                        $byDataCreate["satuan"] = $_SESSION["NEW"][$key][$masterFase][$masterPID]["satuan"];
                        $byDataCreate["jml"] = $_SESSION["NEW"][$key][$masterFase][$masterPID]["jml"] * $jml; //jml
                        $byDataCreate["jml_dasar"] = $jml; //jml_bom_satuan
                        $byDataCreate["jenis_transaksi"] = "main";

                        unset($byDataCreate["id"]);
                        $ins = $byCr->addData($byDataCreate) or matiHere("masterPID: $masterPID <br>gagal menambahkan data* LINE: " . __LINE__ . " | " . $this->db->last_query() . " | <br>" . json_encode($byDataCreate));
                    }
                }

                $this->cloneMasterWorkOrderKomposisi($masterPID, $tempMaster, "add");

                if ($insertID) {
                    unset($_SESSION["NEW"]);
                }
                $pakai_ini = 0;
                if ($pakai_ini > 0) {
                    $nextFase = $masterFase + 1;

                    $p = new MdlProdukFase();
                    // $kf = new MdlProdukKomposisiFase();
                    // $kf = new MdlProdukKomposisiFase();
                    $pf->setFilters(array());
                    $pf->addFilter("status='1'");
                    $pf->addFilter("trash='0'");
                    $pf->addFilter("produk_id='$produk_id'");
                    $allKomposisi = $pf->lookUpKomposisiFase();
                    cekLime($this->db->last_query());
                    // $p->AddFilter("produk_id='$produk_id'");
                    $availFase = $p->lookUpAvailFase($produk_id);
                    $jmlFase = count($availFase);

                    for ($i = 1; $i <= $jmlFase; $i++) {
                        /*
                         * update jika(data lama di trash=1),lalu insert data baru sesuai komposisi baru untuk produk target
                         * ada perubahan komposisi produk
                         * ada perubahan komposisi biaya
                         * ada perubahan data wip target
                         */
                        if ($i > $masterFase) {
                            //lihat data fase sebelumnya
                            $prevFase = $i - 1;
                            $prevDataFase = $allKomposisi[$prevFase];
                            $curentNextFase = $allKomposisi[$i];
                            $prevSrc = $prevDataFase["target"];
                            switch ($key) {
                                case "komposisi_target":
                                    $harga = $prevDataFase["target"][0]["harga"];
                                    $nilai = $prevDataFase["target"][0]["nilai"];
                                    $relID = $prevDataFase["target"][0]["produk_dasar_id"];
                                    $refData = array(
                                        "nilai" => $nilai,
                                        "harga" => $harga,
                                        "dtime" => dtimeNow("Y-m-d H:i"),
                                        "author" => $this->session->login["id"],
                                    );


                                    $newDataTemp = $curentNextFase["produk"];
                                    $toInsert = array();
                                    foreach ($newDataTemp as $newDataTemp__0) {
                                        // unset($newDataTemp__0["id"]);
                                        $tmp = array();
                                        foreach ($newDataTemp__0 as $k => $v) {
                                            if (isset($refData[$k])) {
                                                $v = $refData[$k];
                                            }
                                            $tmp[$k] = $v;
                                        }
                                        $toInsert[$newDataTemp__0["produk_dasar_id"]] = $tmp;

                                    }
                                    $toUpdateTmp = isset($toInsert[$relID]) ? $toInsert[$relID] : array();
                                    if (count($toUpdateTmp) > 0) {
                                        $whereNextCurent = array(
                                            "id" => $toUpdateTmp["id"],
                                        );
                                        $trashNextCurentUpdate = array(
                                            "status" => "0",
                                            "trash" => "1",
                                        );
                                        unset($toUpdateTmp["id"]);
                                        $pf->setFilters(array());
                                        $pf->updateData($whereNextCurent, $trashNextCurentUpdate) or matiHere("gagl update komposisi");

                                        $pf->setFilters(array());
                                        $pf->addData($toUpdateTmp) or matiHere("gagal update komposisi");

                                        $pf->setFilters(array());
                                        $pf->addFilter("produk_id='$produk_id'");
                                        $pf->addFilter("fase_id='$i'");
                                        $pf->addFilter("status='1'");
                                        $pf->addFilter("trash='0'");
                                        $pf->addFilter("jenis='target'");
                                        $tempTarget = $pf->lookUpAll()->result();
                                        if (count($tempTarget) > 0) {
                                            //ditrash data lamanya
                                            $id_toupdate = $tempTarget[0]->id;
                                            $updatetrash = array(
                                                "status" => "0",
                                                "trash" => "1",
                                            );
                                            $where = array(
                                                "id" => $id_toupdate,
                                            );
                                            $pf->updateData($where, $updatetrash) or matiHere("gagal memperbaharui data, silahkan coba beberapa saat lagi.");
                                            // arrPrint($tempTarget);
                                            // matiHere();
                                        }
                                        // cekHitam($this->db->last_query());
                                    }
                                    break;
                                default:
                                    break;
                            }
                            //insert produk target nilai dengan nilai baru komposisi
                            // arrPrint($prevSrc);
                            matiHEre(__LINE__);
                            cekHitam($i);
                        }
                        // matiHEre();
                    }
                    matiHere();

                    // arrprint($faseProduk);
                    cekKuning($this->db->last_query());
                    matiHere();
                    $pf->setFilters(array());
                    $pf->addFilter("produk_id=$produk_id");
                    $pf->addFilter("fase_id=$nextFase");
                    $pf->addFilter("status='1'");
                    $pf->addFilter("trash='0'");
                    $pf->addFilter("produk_dasar_id=$suppliesID");
                    $tempNext = $pf->lookUpAll()->result();
                    $toAutoupdate = array();
                    if (count($tempNext) > 0) {
                        $updateDefault = array(
                            "dtime" => dtimeNow("Y-m-d H:i"),
                            "author" => $this->session->login["id"],
                        );
                        // cekHitam("ada data");
                        foreach ($tempNext[0] as $key => $values) {
                            // arrPrint($tempNext_0);
                            if ($key == "nilai") {
                                $values = $tempNilai;
                            }
                            if ($key == "harga") {
                                $values = $tempNilai;
                            }
                            if ($key != "id") {
                                $toAutoupdate[$key] = isset($updateDefault[$key]) ? $updateDefault[$key] : $values;
                            }

                        }
                        arrPrint($toAutoupdate);
                        // foreach()
                    }
                    cekMerah($this->db->last_query());
                }
                break;
            case "komposisi_target__":
                $suppliesID = $_GET["value"];
                $masterFase = $_GET["fase_id"];
                if (!isset($_GET["fase_id"])) {
                    matiHere("gagal mendeteksi fase produksi! Silahkan refresh halaman dan coba kembali");
                }
                $selectField = array(
                    "produk_dasar_id", "produk_id", "jml", "jenis", "produk_nama", "produk_dasar_nama", "harga"
                );
                // $pf = new MdlProdukKomposisiFase();
                $pr = new MdlProdukRakitan();


                $tempProduk = $pr->lookupById($produk_id)->result();
                $master_nama = $tempProduk[0]->nama;

                $pf->setFilters(array());
                $pf->addFilter("produk_id='$produk_id'");
                $pf->addFilter("fase_id='$masterFase'");
                $pf->addFilter("status='1'");
                $pf->addFilter("trash='0'");
                $pf->addFilter("jenis<>target'");
                $allDataKomposisi = $pf->lookUpAll()->result();
                cekHitam($this->db->last_query());
                // arrprint($allDataKomposisi);
                // matiHEre();
                $tempNilai = 0;
                if (sizeof($allDataKomposisi) > 0) {
                    $subharga = 0;
                    $subqty = 0;
                    foreach ($allDataKomposisi as $allDataKomposisi_0) {
                        // arrprint($allDataKomposisi_0);
                        $bahanID = $allDataKomposisi_0->produk_dasar_id;
                        $jn = $allDataKomposisi_0->jenis;
                        $jml = $allDataKomposisi_0->jml;
                        $harga = $allDataKomposisi_0->harga;
                        $subtotal = $jml * $harga;
                        $temp = array();
                        foreach ($selectField as $ii => $fields) {
                            $temp[$fields] = $allDataKomposisi_0->$fields;
                        }
                        $tempNilai += $subtotal;
                        $temp["subtotal"] = $subtotal;
                        $newData[$jn][$bahanID][] = $temp;

                    }
                }

                //panggil data supplies hasil wip

                //region cek target fase sduah ada belum
                $pf->setFilters(array());
                $pf->addFilter("produk_id='$produk_id'");
                $pf->addFilter("fase_id='$masterFase'");
                $pf->addFilter("status='1'");
                $pf->addFilter("trash='0'");
                $pf->addFilter("jenis='target'");
                $tempTarget = $pf->lookUpAll()->result();
                if (count($tempTarget) > 0) {
                    //ditrash data lamanya
                    $id_toupdate = $tempTarget[0]->id;
                    $updatetrash = array(
                        "status" => "0",
                        "trash" => "1",
                    );
                    $where = array(
                        "id" => $id_toupdate,
                    );
                    $pf->updateData($where, $updatetrash) or matiHere("gagal memperbaharui data, silahkan coba beberapa saat lagi.");
                    // arrPrint($tempTarget);
                    // matiHere();
                }

                $s = new MdlSupplies();
                $s->setFilters(array());
                $s->addFilter("id='$suppliesID'");
                $tempSuppleis = $s->lookUpAll()->result();
                $suppliesNama = $tempSuppleis[0]->nama;
                $dataFields = array(
                    "jenis" => "target",
                    "fase_id" => "$masterFase",
                    "produk_id" => "$produk_id",
                    "produk_nama" => "$master_nama",
                    "produk_dasar_id" => "$suppliesID",
                    "produk_dasar_nama" => "$suppliesNama",
                    "jml" => "1",
                    "harga" => "$tempNilai",
                    "nilai" => "$tempNilai",
                    "status" => "1",
                    "trash" => "0",
                    "dtime" => dtimeNow("Y-m-d H:i"),
                );
                $pf->addData($dataFields) or matiHere("gagal menulis hasil fase $masterFase");
                cekMErah($this->db->last_query());
                $pakai_ini = 0;
                if ($pakai_ini > 0) {
                    $nextFase = $masterFase + 1;
                    $p = new MdlProdukFase();
                    // $kf = new MdlProdukKomposisiFase();
                    // $kf = new MdlProdukKomposisiFase();
                    $pf->setFilters(array());
                    $pf->addFilter("status='1'");
                    $pf->addFilter("trash='0'");
                    $pf->addFilter("produk_id='$produk_id'");
                    $allKomposisi = $pf->lookUpKomposisiFase();
                    cekLime($this->db->last_query());
                    // $p->AddFilter("produk_id='$produk_id'");
                    $availFase = $p->lookUpAvailFase($produk_id);
                    $jmlFase = count($availFase);


                    for ($i = 1; $i <= $jmlFase; $i++) {
                        /*
                         * update jika(data lama di trash=1),lalu insert data baru sesuai komposisi baru untuk produk target
                         * ada perubahan komposisi produk
                         * ada perubahan komposisi biaya
                         * ada perubahan data wip target
                         */
                        if ($i > $masterFase) {
                            //lihat data fase sebelumnya
                            $prevFase = $i - 1;
                            $prevDataFase = $allKomposisi[$prevFase];
                            $curentNextFase = $allKomposisi[$i];
                            $prevSrc = $prevDataFase["target"];
                            switch ($key) {
                                case "komposisi_target":
                                    $harga = $prevDataFase["target"][0]["harga"];
                                    $nilai = $prevDataFase["target"][0]["nilai"];
                                    $relID = $prevDataFase["target"][0]["produk_dasar_id"];
                                    $refData = array(
                                        "nilai" => $nilai,
                                        "harga" => $harga,
                                        "dtime" => dtimeNow("Y-m-d H:i"),
                                        "author" => $this->session->login["id"],
                                    );


                                    $newDataTemp = $curentNextFase["produk"];
                                    $toInsert = array();
                                    foreach ($newDataTemp as $newDataTemp__0) {
                                        // unset($newDataTemp__0["id"]);
                                        $tmp = array();
                                        foreach ($newDataTemp__0 as $k => $v) {
                                            if (isset($refData[$k])) {
                                                $v = $refData[$k];
                                            }
                                            $tmp[$k] = $v;
                                        }
                                        $toInsert[$newDataTemp__0["produk_dasar_id"]] = $tmp;

                                    }
                                    $toUpdateTmp = isset($toInsert[$relID]) ? $toInsert[$relID] : array();
                                    if (count($toUpdateTmp) > 0) {
                                        $whereNextCurent = array(
                                            "id" => $toUpdateTmp["id"],
                                        );
                                        $trashNextCurentUpdate = array(
                                            "status" => "0",
                                            "trash" => "1",
                                        );
                                        unset($toUpdateTmp["id"]);
                                        $pf->setFilters(array());
                                        $pf->updateData($whereNextCurent, $trashNextCurentUpdate) or matiHere("gagl update komposisi");

                                        $pf->setFilters(array());
                                        $pf->addData($toUpdateTmp) or matiHere("gagal update komposisi");

                                        $pf->setFilters(array());
                                        $pf->addFilter("produk_id='$produk_id'");
                                        $pf->addFilter("fase_id='$i'");
                                        $pf->addFilter("status='1'");
                                        $pf->addFilter("trash='0'");
                                        $pf->addFilter("jenis='target'");
                                        $tempTarget = $pf->lookUpAll()->result();
                                        if (count($tempTarget) > 0) {
                                            //ditrash data lamanya
                                            $id_toupdate = $tempTarget[0]->id;
                                            $updatetrash = array(
                                                "status" => "0",
                                                "trash" => "1",
                                            );
                                            $where = array(
                                                "id" => $id_toupdate,
                                            );
                                            $pf->updateData($where, $updatetrash) or matiHere("gagal memperbaharui data, silahkan coba beberapa saat lagi.");
                                            // arrPrint($tempTarget);
                                            // matiHere();
                                        }
                                        // cekHitam($this->db->last_query());
                                    }
                                    break;
                                default:
                                    break;
                            }
                            //insert produk target nilai dengan nilai baru komposisi
                            // arrPrint($prevSrc);
                            matiHEre(__LINE__);
                            cekHitam($i);
                        }
                        // matiHEre();
                    }
                    matiHere();


                    // arrprint($faseProduk);
                    cekKuning($this->db->last_query());
                    matiHere();
                    $pf->setFilters(array());
                    $pf->addFilter("produk_id=$produk_id");
                    $pf->addFilter("fase_id=$nextFase");
                    $pf->addFilter("status='1'");
                    $pf->addFilter("trash='0'");
                    $pf->addFilter("produk_dasar_id=$suppliesID");
                    $tempNext = $pf->lookUpAll()->result();
                    $toAutoupdate = array();
                    if (count($tempNext) > 0) {
                        $updateDefault = array(
                            "dtime" => dtimeNow("Y-m-d H:i"),
                            "author" => $this->session->login["id"],
                        );
                        // cekHitam("ada data");
                        foreach ($tempNext[0] as $key => $values) {
                            // arrPrint($tempNext_0);
                            if ($key == "nilai") {
                                $values = $tempNilai;
                            }
                            if ($key == "harga") {
                                $values = $tempNilai;
                            }
                            if ($key != "id") {
                                $toAutoupdate[$key] = isset($updateDefault[$key]) ? $updateDefault[$key] : $values;
                            }

                        }
                        arrPrint($toAutoupdate);
                        // foreach()
                    }
                    cekMerah($this->db->last_query());
                }
                //endregion
                break;
            case "komposisi_fase_timwork":
                $masterFase = $_GET["fase_id"];
                if (!isset($_GET["fase_id"])) {
                    matiHere("gagal mendeteksi fase produksi! Silahkan refresh halaman dan coba kembali");
                }
                $tempMaster = array(
                    "produk_id" => $masterPID,
                    "produk_nama" => $master_nama,
                    // "cabang_id"=>$tempProduk[0]->cabang_id,
                    "produk_dasar_id" => $_SESSION["NEW"][$key][$masterPID]["produk_dasar_id"],
                    "produk_dasar_nama" => $_SESSION["NEW"][$key][$masterPID]["produk_dasar_nama"],
                    "satuan_id" => $_SESSION["NEW"][$key][$masterPID]["satuan_id"],
                    "satuan" => $dtaSatuan[$_SESSION["NEW"][$key][$masterPID]["satuan_id"]],
                    "jml" => $_SESSION["NEW"][$key][$masterPID]["jml"],
                    "nilai" => $_SESSION["NEW"][$key][$masterPID]["harga"],
                    "harga" => $_SESSION["NEW"][$key][$masterPID]["harga"],
                    "fase_id" => $masterFase,
                    "gudang_id" => $masterFase . "$masterPID",
                    "gudang2_id" => $masterFase . "$masterPID",
                    "author" => $this->session->login['id'],
                    "jenis" => "produk",
                    // "jenis_transaksi" => "7778." . $masterPID . "." . $masterFase,
                    "jenis_transaksi" => "5582",
                    "dtime" => date("Y-m-d H:i"),
                    "cat_id" => $_SESSION["NEW"][$key][$masterPID]["cat_id"],//id kelompok biaya
                    "cat_nama" => $_SESSION["NEW"][$key][$masterPID]["cat_nama"],
                    "debet" => $_SESSION["NEW"][$key][$masterPID]["jml"] * $_SESSION["NEW"][$key][$masterPID]["harga"],
                    "saldo" => $_SESSION["NEW"][$key][$masterPID]["jml"] * $_SESSION["NEW"][$key][$masterPID]["harga"],
                    "qty_debet" => $_SESSION["NEW"][$key][$masterPID]["jml"],
                    "qty_saldo" => $_SESSION["NEW"][$key][$masterPID]["jml"],
                );
                break;
            case "timwork":
                $tempMaster = array(
                    "produk_id" => $masterPID,
                    "produk_nama" => $master_nama,
                    "nama" => $master_nama,
                    // "cabang_id"=>$tempProduk[0]->cabang_id,
                    "employee_id" => $_SESSION["NEW"][$key][$masterPID]["employee_id"],
                    "employee_nama" => $_SESSION["NEW"][$key][$masterPID]["employee_nama"],
                    "hak_akses_id" => $_SESSION["NEW"][$key][$masterPID]["akses_id"],
                    "hak_akses_nama" => $_SESSION["NEW"][$key][$masterPID]["akses_nama"],
                    "dtime" => date("Y-m-d H:i"),
                    "oleh_id" => $this->session->login["id"],
                    "oleh_nama" => $this->session->login["nama"],
                );

                $inserID = $pf->addData($tempMaster) or matiHere("gagal menambahakan anggota, silahkan ulangi beberapa saat lagi");
//                cekHitam($this->db->last_query());
//                arrPrint($tempMaster);
//                matiHEre();
                if ($inserID) {
                    //region tambah nulis ke accesmember
                    $memberAkses = array(
                        "nama" => "",
                        "per_employee_id" => "",
                    );
                    unset($_SESSION["NEW"][$key][$masterPID]);
                }
                // cekLime($this->db->last_query());
                // matiHEre($mdlName);
                break;
            case "komposisi_fase_room":
                $masterFase = $_GET["fase_id"];
                if (!isset($_GET["fase_id"])) {
                    matiHere("gagal mendeteksi fase produksi! Silahkan refresh halaman dan coba kembali");
                }

                arrPrint($_SESSION["NEW"][$key]);
                matiHere();
                $master_nama = $tempProduk[0]->nama;
                $tempMaster = array(
                    "produk_id" => $masterPID,
                    "produk_nama" => $master_nama,
                    "produk_dasar_id" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["produk_dasar_id"],
                    "produk_dasar_nama" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["produk_dasar_nama"],
                    "satuan_id" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["satuan_id"],
                    "satuan" => $dtaSatuan[$_SESSION["NEW"][$key][$masterFase][$masterPID]["satuan_id"]],
                    "jml" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["jml"],
                    "nilai" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["nilai"],
                    "harga" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["harga"],
                    "fase_id" => $masterFase,
                    "gudang_id" => $masterFase . "$masterPID",
                    "gudang2_id" => $masterFase . "$masterPID",
                    "author" => $this->session->login['id'],
                    "jenis" => "produk",
                    "jenis_transaksi" => "5582",
                    "dtime" => date("Y-m-d H:i"),
                    "cat_id" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["cat_id"],//id kelompok biaya
                    "cat_nama" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["cat_nama"],
                    "debet" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["jml"] * $_SESSION["NEW"][$key][$masterFase][$masterPID]["harga"],
                    "saldo" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["jml"] * $_SESSION["NEW"][$key][$masterFase][$masterPID]["harga"],
                    "qty_debet" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["jml"],
                    "qty_saldo" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["jml"],
                    "hrg_hpp" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["nilai"],
                    "hrg_ppv" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["ppv"],
                    "hrg_jual" => $_SESSION["NEW"][$key][$masterFase][$masterPID]["jual"],
                );
                arrPrint($_SESSION["NEW"][$key]);
                matiHere();
                $insertID = $pf->addData($tempMaster) or matiHere("gagal menambahkan data*" . " | " . $this->db->last_query());
                $this->cloneMasterWorkOrderKomposisi($masterPID, $tempMaster, $mdlName);//untuk nulis komposisi sub workorder
                if ($insertID) {
                    unset($_SESSION["NEW"]);
                }
                break;
            case "komposisi_fase_sub":

                $masterFase = $_GET["fase_id"];
                $subFase = $_GET["sub_fase_id"];

                if (!isset($_GET["fase_id"])) {
                    matiHere("gagal mendeteksi fase produksi! Silahkan refresh halaman dan coba kembali");
                }
                // $masterPID = array_keys($_SESSION["NEW"][$key])[0];
                // $this->load->model("Mdls/MdlProdukRakitan");
                // $pr = new MdlProdukRakitan();
                // $tempProduk = $pr->lookupById($masterPID)->result();
                $master_nama = $tempProduk[0]->nama;
                $tempMaster = array(
                    "produk_id" => $produk_id,
                    "produk_nama" => $master_nama,
                    "sub_fase_id" => $subFase,
                    // "cabang_id"=>$tempProduk[0]->cabang_id,
                    "produk_dasar_id" => $_SESSION["NEW"][$key][$masterFase][$subFase][$produk_id]["produk_dasar_id"],
                    "produk_dasar_nama" => $_SESSION["NEW"][$key][$masterFase][$subFase][$produk_id]["produk_dasar_nama"],
                    "satuan_id" => $_SESSION["NEW"][$key][$masterFase][$subFase][$produk_id]["satuan_id"],
                    "satuan" => $dtaSatuan[$_SESSION["NEW"][$key][$masterFase][$subFase][$produk_id]["satuan_id"]],
                    "jml" => $_SESSION["NEW"][$key][$masterFase][$subFase][$produk_id]["jml"],
                    "nilai" => $_SESSION["NEW"][$key][$masterFase][$subFase][$produk_id]["nilai"],
                    "harga" => $_SESSION["NEW"][$key][$masterFase][$subFase][$produk_id]["harga"],
                    "fase_id" => $masterFase,
                    "gudang_id" => $masterFase . "$produk_id",
                    "gudang2_id" => $masterFase . "$produk_id",
                    "author" => $this->session->login['id'],
                    "jenis" => "produk",
                    // "jenis_transaksi" => "7778." . $masterPID . "." . $masterFase,
                    "jenis_transaksi" => "5582",
                    "dtime" => date("Y-m-d H:i"),
                    "cat_id" => $_SESSION["NEW"][$key][$masterFase][$subFase][$produk_id]["cat_id"],//id kelompok biaya
                    "cat_nama" => $_SESSION["NEW"][$key][$masterFase][$subFase][$produk_id]["cat_nama"],
                    "debet" => $_SESSION["NEW"][$key][$masterFase][$subFase][$produk_id]["jml"] * $_SESSION["NEW"][$key][$masterFase][$subFase][$produk_id]["harga"],
                    "saldo" => $_SESSION["NEW"][$key][$masterFase][$subFase][$produk_id]["jml"] * $_SESSION["NEW"][$key][$masterFase][$subFase][$produk_id]["harga"],
                    "qty_debet" => $_SESSION["NEW"][$key][$masterFase][$subFase][$produk_id]["jml"],
                    "qty_saldo" => $_SESSION["NEW"][$key][$masterFase][$subFase][$produk_id]["jml"],
                );
//                arrprint($tempMaster);
//                cekHitam($this->db->last_query());
                $insertID = $pf->addData($tempMaster) or matiHere("JSON.parse(atob('" . base64_encode(json_encode($_SESSION["NEW"][$key])) . "'));<BR>gagal menambahkan data*" . "<BR>" . $this->db->last_query());

                if ($insertID) {
                    unset($_SESSION["NEW"]);
                }
                $pakai_ini = 0;
                if ($pakai_ini > 0) {
                    $nextFase = $masterFase + 1;

                    $p = new MdlProdukFase();
                    // $kf = new MdlProdukKomposisiFase();
                    // $kf = new MdlProdukKomposisiFase();
                    $pf->setFilters(array());
                    $pf->addFilter("status='1'");
                    $pf->addFilter("trash='0'");
                    $pf->addFilter("produk_id='$produk_id'");
                    $allKomposisi = $pf->lookUpKomposisiFase();
                    cekLime($this->db->last_query());
                    // $p->AddFilter("produk_id='$produk_id'");
                    $availFase = $p->lookUpAvailFase($produk_id);
                    $jmlFase = count($availFase);


                    for ($i = 1; $i <= $jmlFase; $i++) {
                        /*
                         * update jika(data lama di trash=1),lalu insert data baru sesuai komposisi baru untuk produk target
                         * ada perubahan komposisi produk
                         * ada perubahan komposisi biaya
                         * ada perubahan data wip target
                         */
                        if ($i > $masterFase) {
                            //lihat data fase sebelumnya
                            $prevFase = $i - 1;
                            $prevDataFase = $allKomposisi[$prevFase];
                            $curentNextFase = $allKomposisi[$i];
                            $prevSrc = $prevDataFase["target"];
                            switch ($key) {
                                case "komposisi_target":
                                    $harga = $prevDataFase["target"][0]["harga"];
                                    $nilai = $prevDataFase["target"][0]["nilai"];
                                    $relID = $prevDataFase["target"][0]["produk_dasar_id"];
                                    $refData = array(
                                        "nilai" => $nilai,
                                        "harga" => $harga,
                                        "dtime" => dtimeNow("Y-m-d H:i"),
                                        "author" => $this->session->login["id"],
                                    );


                                    $newDataTemp = $curentNextFase["produk"];
                                    $toInsert = array();
                                    foreach ($newDataTemp as $newDataTemp__0) {
                                        // unset($newDataTemp__0["id"]);
                                        $tmp = array();
                                        foreach ($newDataTemp__0 as $k => $v) {
                                            if (isset($refData[$k])) {
                                                $v = $refData[$k];
                                            }
                                            $tmp[$k] = $v;
                                        }
                                        $toInsert[$newDataTemp__0["produk_dasar_id"]] = $tmp;

                                    }
                                    $toUpdateTmp = isset($toInsert[$relID]) ? $toInsert[$relID] : array();
                                    if (count($toUpdateTmp) > 0) {
                                        $whereNextCurent = array(
                                            "id" => $toUpdateTmp["id"],
                                        );
                                        $trashNextCurentUpdate = array(
                                            "status" => "0",
                                            "trash" => "1",
                                        );
                                        unset($toUpdateTmp["id"]);
                                        $pf->setFilters(array());
                                        $pf->updateData($whereNextCurent, $trashNextCurentUpdate) or matiHere("gagl update komposisi");

                                        $pf->setFilters(array());
                                        $pf->addData($toUpdateTmp) or matiHere("gagal update komposisi");

                                        $pf->setFilters(array());
                                        $pf->addFilter("produk_id='$produk_id'");
                                        $pf->addFilter("fase_id='$i'");
                                        $pf->addFilter("status='1'");
                                        $pf->addFilter("trash='0'");
                                        $pf->addFilter("jenis='target'");
                                        $tempTarget = $pf->lookUpAll()->result();
                                        if (count($tempTarget) > 0) {
                                            //ditrash data lamanya
                                            $id_toupdate = $tempTarget[0]->id;
                                            $updatetrash = array(
                                                "status" => "0",
                                                "trash" => "1",
                                            );
                                            $where = array(
                                                "id" => $id_toupdate,
                                            );
                                            $pf->updateData($where, $updatetrash) or matiHere("gagal memperbaharui data, silahkan coba beberapa saat lagi.");
                                            // arrPrint($tempTarget);
                                            // matiHere();
                                        }
                                        // cekHitam($this->db->last_query());
                                    }
                                    break;
                                default:
                                    break;
                            }
                            //insert produk target nilai dengan nilai baru komposisi
                            // arrPrint($prevSrc);
                            matiHEre(__LINE__);
                            cekHitam($i);
                        }
                        // matiHEre();
                    }
                    matiHere();


                    // arrprint($faseProduk);
                    cekKuning($this->db->last_query());
                    matiHere();
                    $pf->setFilters(array());
                    $pf->addFilter("produk_id=$produk_id");
                    $pf->addFilter("fase_id=$nextFase");
                    $pf->addFilter("status='1'");
                    $pf->addFilter("trash='0'");
                    $pf->addFilter("produk_dasar_id=$suppliesID");
                    $tempNext = $pf->lookUpAll()->result();
                    $toAutoupdate = array();
                    if (count($tempNext) > 0) {
                        $updateDefault = array(
                            "dtime" => dtimeNow("Y-m-d H:i"),
                            "author" => $this->session->login["id"],
                        );
                        // cekHitam("ada data");
                        foreach ($tempNext[0] as $key => $values) {
                            // arrPrint($tempNext_0);
                            if ($key == "nilai") {
                                $values = $tempNilai;
                            }
                            if ($key == "harga") {
                                $values = $tempNilai;
                            }
                            if ($key != "id") {
                                $toAutoupdate[$key] = isset($updateDefault[$key]) ? $updateDefault[$key] : $values;
                            }

                        }
                        arrPrint($toAutoupdate);
                        // foreach()
                    }
                    cekMerah($this->db->last_query());
                }
                break;
            case "komposisi_fase_biaya_sub":

                $masterFase = $_GET["fase_id"];
                if (!isset($_GET["fase_id"])) {
                    matiHere("gagal mendeteksi recana kerja! Silahkan refresh halaman dan coba kembali");
                }
                // $masterPID = array_keys($_SESSION["NEW"][$key])[0];
                // arrPrint($_SESSION["NEW"][$key][$masterPID]);
                // matiHere();
                // $this->load->model("Mdls/MdlProdukRakitan");
                // $pr = new MdlProdukRakitan();
                // $tempProduk = $pr->lookupById($masterPID)->result();
                // $master_nama = $tempProduk[0]->nama;
                $tempMaster = array(
                    "produk_id" => $masterPID,
                    "produk_nama" => $master_nama,
                    "satuan_id" => $_SESSION["NEW"][$key][$masterPID]["satuan_id"],
                    "satuan" => $dtaSatuan[$_SESSION["NEW"][$key][$masterPID]["satuan_id"]],
                    // "cabang_id"=>$tempProduk[0]->cabang_id,
                    "produk_dasar_id" => $_SESSION["NEW"][$key][$masterPID]["produk_dasar_id"],
                    "produk_dasar_nama" => $_SESSION["NEW"][$key][$masterPID]["produk_dasar_nama"],
                    // "satuan"=>$_SESSION["NEW"][$key][$masterPID]["satuan"],
                    "jml" => $_SESSION["NEW"][$key][$masterPID]["jml"],
                    "nilai" => $_SESSION["NEW"][$key][$masterPID]["harga"],
                    "harga" => $_SESSION["NEW"][$key][$masterPID]["harga"],
                    "fase_id" => $masterFase,
                    "gudang_id" => $masterFase . "$masterPID",
                    "gudang2_id" => $masterFase . "$masterPID",
                    "author" => $this->session->login['id'],
                    // "jenis_transaksi"   => "7778." . $masterPID . "." . $masterFase,
                    "jenis_transaksi" => "5582",
                    "jenis" => "biaya",
                    "dtime" => date("Y-m-d H:i"),
                    "cat_id" => $_SESSION["NEW"][$key][$masterPID]["cat_id"],
                    "cat_nama" => $_SESSION["NEW"][$key][$masterPID]["cat_nama"],
                    "debet" => $_SESSION["NEW"][$key][$masterPID]["jml"] * $_SESSION["NEW"][$key][$masterPID]["harga"],
                    "saldo" => $_SESSION["NEW"][$key][$masterPID]["jml"] * $_SESSION["NEW"][$key][$masterPID]["harga"],
                    "qty_debet" => $_SESSION["NEW"][$key][$masterPID]["jml"],
                    "qty_saldo" => $_SESSION["NEW"][$key][$masterPID]["jml"],
                );
                $insertID = $pf->addData($tempMaster) or matiHere("gagal menambahkan data");
                // cekBiru($this->db->last_query());
                $this->cloneMasterWorkOrderKomposisi($masterPID, $tempMaster, "add");
//                $this->rebuildMasterKomposisi($masterPID, $tempMaster);
                // $this->autoPatchSrcTargetWip($masterPID, $masterFase);
                // cekMErah($this->db->last_query());
//                 matiHEre();
                if ($insertID) {
                    unset($_SESSION["NEW"]);
                }
                $pakai_ini = 0;
                if ($pakai_ini > 0) {
                    $nextFase = $masterFase + 1;

                    $p = new MdlProdukFase();
                    // $kf = new MdlProdukKomposisiFase();
                    // $kf = new MdlProdukKomposisiFase();
                    $pf->setFilters(array());
                    $pf->addFilter("status='1'");
                    $pf->addFilter("trash='0'");
                    $pf->addFilter("produk_id='$produk_id'");
                    $allKomposisi = $pf->lookUpKomposisiFase();
                    cekLime($this->db->last_query());
                    // $p->AddFilter("produk_id='$produk_id'");
                    $availFase = $p->lookUpAvailFase($produk_id);
                    $jmlFase = count($availFase);


                    for ($i = 1; $i <= $jmlFase; $i++) {
                        /*
                         * update jika(data lama di trash=1),lalu insert data baru sesuai komposisi baru untuk produk target
                         * ada perubahan komposisi produk
                         * ada perubahan komposisi biaya
                         * ada perubahan data wip target
                         */
                        if ($i > $masterFase) {
                            //lihat data fase sebelumnya
                            $prevFase = $i - 1;
                            $prevDataFase = $allKomposisi[$prevFase];
                            $curentNextFase = $allKomposisi[$i];
                            $prevSrc = $prevDataFase["target"];
                            switch ($key) {
                                case "komposisi_target":
                                    $harga = $prevDataFase["target"][0]["harga"];
                                    $nilai = $prevDataFase["target"][0]["nilai"];
                                    $relID = $prevDataFase["target"][0]["produk_dasar_id"];
                                    $refData = array(
                                        "nilai" => $nilai,
                                        "harga" => $harga,
                                        "dtime" => dtimeNow("Y-m-d H:i"),
                                        "author" => $this->session->login["id"],
                                    );


                                    $newDataTemp = $curentNextFase["produk"];
                                    $toInsert = array();
                                    foreach ($newDataTemp as $newDataTemp__0) {
                                        // unset($newDataTemp__0["id"]);
                                        $tmp = array();
                                        foreach ($newDataTemp__0 as $k => $v) {
                                            if (isset($refData[$k])) {
                                                $v = $refData[$k];
                                            }
                                            $tmp[$k] = $v;
                                        }
                                        $toInsert[$newDataTemp__0["produk_dasar_id"]] = $tmp;

                                    }
                                    $toUpdateTmp = isset($toInsert[$relID]) ? $toInsert[$relID] : array();
                                    if (count($toUpdateTmp) > 0) {
                                        $whereNextCurent = array(
                                            "id" => $toUpdateTmp["id"],
                                        );
                                        $trashNextCurentUpdate = array(
                                            "status" => "0",
                                            "trash" => "1",
                                        );
                                        unset($toUpdateTmp["id"]);
                                        $pf->setFilters(array());
                                        $pf->updateData($whereNextCurent, $trashNextCurentUpdate) or matiHere("gagl update komposisi");

                                        $pf->setFilters(array());
                                        $pf->addData($toUpdateTmp) or matiHere("gagal update komposisi");

                                        $pf->setFilters(array());
                                        $pf->addFilter("produk_id='$produk_id'");
                                        $pf->addFilter("fase_id='$i'");
                                        $pf->addFilter("status='1'");
                                        $pf->addFilter("trash='0'");
                                        $pf->addFilter("jenis='target'");
                                        $tempTarget = $pf->lookUpAll()->result();
                                        if (count($tempTarget) > 0) {
                                            //ditrash data lamanya
                                            $id_toupdate = $tempTarget[0]->id;
                                            $updatetrash = array(
                                                "status" => "0",
                                                "trash" => "1",
                                            );
                                            $where = array(
                                                "id" => $id_toupdate,
                                            );
                                            $pf->updateData($where, $updatetrash) or matiHere("gagal memperbaharui data, silahkan coba beberapa saat lagi.");
                                            // arrPrint($tempTarget);
                                            // matiHere();
                                        }
                                        // cekHitam($this->db->last_query());
                                    }
                                    break;
                                default:
                                    break;
                            }
                            //insert produk target nilai dengan nilai baru komposisi
                            // arrPrint($prevSrc);
                            matiHEre(__LINE__);
                            cekHitam($i);
                        }
                        // matiHEre();
                    }
                    matiHere();


                    // arrprint($faseProduk);
                    cekKuning($this->db->last_query());
                    matiHere();
                    $pf->setFilters(array());
                    $pf->addFilter("produk_id=$produk_id");
                    $pf->addFilter("fase_id=$nextFase");
                    $pf->addFilter("status='1'");
                    $pf->addFilter("trash='0'");
                    $pf->addFilter("produk_dasar_id=$suppliesID");
                    $tempNext = $pf->lookUpAll()->result();
                    $toAutoupdate = array();
                    if (count($tempNext) > 0) {
                        $updateDefault = array(
                            "dtime" => dtimeNow("Y-m-d H:i"),
                            "author" => $this->session->login["id"],
                        );
                        // cekHitam("ada data");
                        foreach ($tempNext[0] as $key => $values) {
                            // arrPrint($tempNext_0);
                            if ($key == "nilai") {
                                $values = $tempNilai;
                            }
                            if ($key == "harga") {
                                $values = $tempNilai;
                            }
                            if ($key != "id") {
                                $toAutoupdate[$key] = isset($updateDefault[$key]) ? $updateDefault[$key] : $values;
                            }

                        }
                        arrPrint($toAutoupdate);
                        // foreach()
                    }
                    cekMerah($this->db->last_query());
                }
                break;
            case "":
                $masterFase = $_GET["fase_id"];
                if (!isset($_GET["fase_id"])) {
                    matiHere("gagal mendeteksi recana kerja! Silahkan refresh halaman dan coba kembali");
                }
                break;
            default:
                cekHitam("skip aja ndak nulis");
                arrPrint($_SESSION["NEW"][$key][$masterPID]);
                matiHere();
                break;
        }

        $commit = $this->db->trans_complete();

        if($commit){
            unset($_SESSION["NEW"]);
        }

        $resultID = isset($_GET['result']) ? trim($_GET['result']) : (isset($_POST['result']) ? $_POST['result'] : "");

        if ($resultID != "") {
            //echo "<script>var iframe = top.document.getElementById('$resultID');iframe.src=iframe.src; top.window.location.reload();</script>";
        }
        switch ($key) {
            case "save_jual_project":
                echo json_encode(array("status" => $commit, "js" => "if(typeof top.preLoadFase=='function'){ top.location.reload('top.preLoadProdukFase()'); }"));
                break;
            case "save_diskon":
                echo json_encode(array("status" => 1, "js" => "if(typeof top.preLoadFase=='function'){ top.preLoadFase('top.preLoadProdukFase()'); }"));
                break;
            case "timwork":
                echo "<script> if(typeof top.preLoadFase=='function'){ top.preLoadFase('top.preLoadProdukFase()'); }</script>";
                break;
            default:
                echo "<script> if(typeof top.preLoadFase=='function'){ top.preLoadFase('top.preLoadProdukFase()'); }</script>";
                break;
        }
    }

    public function rebuildMasterKomposisi($pid, $data, $mode = "add")
    {
        $aliasTableFields = array(
            "produk_id" => "produk_id",
            "produk_nama" => "produk_nama",
            "produk_dasar_id" => "produk_dasar_id",
            "produk_dasar_nama" => "produk_dasar_nama",
            "jml" => "jml",
            "harga" => "harga",
            "nilai" => "nilai",
            "cat_id" => "cat_id",
            "cat_nama" => "cat_nama",
            "satuan_id" => "satuan_id",
            "satuan_nama" => "satuan",
            "jenis" => "jenis",
        );
        $this->load->model("Mdls/MdlProjectKomposisi");

        $p = new MdlProjectKomposisi(); //ORI ke komposisi default
        $fields = $p->getFields();

        switch ($mode) {
            case"update":
                if (is_array($pid)) {
                    $mid = $pid["produk_id"];
                    $bid = $pid["produk_dasar_id"];
//                    $fid = $pid["fase_id"];
                }
                else {
                    matiHere("unknown methode to exec <br>Silahkan hubungi admin untuk dilakukan pengecekan lebih lanjut");
                }
                $p->addFilter("produk_id='$mid'");
                $p->addFilter("produk_dasar_id='$bid'");

                $tem = $p->lookUpAll()->result();
                if (count($tem) > 0) {
                    unset($data["fase_id"]);
                    $p->updateData(array("id" => $tem[0]->id), $data) or matiHEre("Gagal melakukan pembaharuan data. Silahkan tutup browser telebih dahulu untuk membersihkan sesi dan coba kembali.");
                }
                else {
                    //karena cuma update kalau belum ada berarti ada kesalahan saat pembuatan komposisi workorder yang tidak melaukan insert ke komposisi project
                    matiHere("unknown methode to exec <br>Silahkan hubungi admin untuk dilakukan pengecekan lebih lanjut " . __LINE__);
                }
                break;
            case"room":
                break;
            default:
                $p->setFilters(array());
                $p->addFilter("produk_id='$pid'");
                $tem = $p->lookUpAll()->result();
                if (count($tem) > 0) {
                    $tocek = array();
                    foreach ($tem as $tem_0) {
                        $tocek[$tem_0->jenis][$tem_0->produk_dasar_id] = (array)$tem_0;
                    }
                    if (isset($tocek[$data["jenis"]][$data["produk_dasar_id"]])) {
                        //cek sudah ada
                        $preval = $tocek[$data["jenis"]][$data["produk_dasar_id"]]["nilai"];
                        $prevalQty = $tocek[$data["jenis"]][$data["produk_dasar_id"]]["jml"];
                        $preDebet = $tocek[$data["jenis"]][$data["produk_dasar_id"]]["debet"];
                        $preQtyDebet = $tocek[$data["jenis"]][$data["produk_dasar_id"]]["qty_debet"];
                        $preQtysaldo = $tocek[$data["jenis"]][$data["produk_dasar_id"]]["qty_saldo"];
                        $preSaldo = $tocek[$data["jenis"]][$data["produk_dasar_id"]]["saldo"];
                        $newJml = $prevalQty + $data["jml"];
                        $newDebet = $preDebet + ($data["jml"] * $data["harga"]);
                        $newsaldo = $preSaldo + ($data["jml"] * $data["harga"]);
                        $update = array(
                            "nilai" => $newsaldo / $newJml,
                            "harga" => $newsaldo / $newJml,
                            "jml" => $newJml,
                            "debet" => $newDebet,
                            "qty_debet" => $newJml,
                            "qty_saldo" => $newJml,
                            "saldo" => $newsaldo,
                        );
                        $where = array(
                            "id" => $tocek[$data["jenis"]][$data["produk_dasar_id"]]["id"],
                        );
                        $p->setFilters(array());
                        $p->updateData($where, $update) or matiHere("gagal memperbaharui data LINE: " . __LINE__ . "<br>" . $this->db - last_query());
                    }
                    else {
                        //belum ada insert dulu
                        $toInsert = array();
                        foreach ($aliasTableFields as $key => $src) {
                            $toInsert[$key] = $data[$src];
                        }
                        $toInsert["debet"] = $data["harga"] * $data["jml"];
                        $toInsert["saldo"] = $data["harga"] * $data["jml"];
                        $toInsert["qty_debet"] = $data["jml"];
                        $toInsert["qty_saldo"] = $data["jml"];
                        $p->setFilters(array());
                        $p->addData($toInsert) or matiHere("gagal menambahkan data LINE: " . __LINE__ . "<br>" . $this->db->last_query());
                    }
                }
                else {
                    //belum ada bro insert aja
                    $toInsert = array();
                    foreach ($aliasTableFields as $key => $src) {
                        $toInsert[$key] = $data[$src];
                    }
                    $toInsert["debet"] = $data["harga"] * $data["jml"];
                    $toInsert["saldo"] = $data["harga"] * $data["jml"];
                    $toInsert["qty_debet"] = $data["jml"];
                    $toInsert["qty_saldo"] = $data["jml"];
                    $p->setFilters(array());
                    $p->addData($toInsert) or matiHere("gagal memperbaharui data LINE: " . __LINE__ . "<br>" . $this->db->last_query());
                }
                break;
        }

    }

    public function rebuildMasterKomposisiSub($pid)
    {
        $aliasTableFields = array(
            "produk_id" => "produk_id",
            "produk_nama" => "produk_nama",
            "produk_dasar_id" => "produk_dasar_id",
            "produk_dasar_nama" => "produk_dasar_nama",
            "jml" => "jml",
            "harga" => "harga",
            "nilai" => "nilai",
            "cat_id" => "cat_id",
            "cat_nama" => "cat_nama",
            "satuan_id" => "satuan_id",
            "satuan_nama" => "satuan",
            "jenis" => "jenis",
        );
        $this->load->model("Mdls/MdlProjectKomposisi");
        $this->load->model("Mdls/MdlProjectKomposisiWorkorderSub");

        $p = new MdlProjectKomposisiWorkorderSub();
        $fields = $p->getFields();

        switch ($mode) {
            case"update":
                if (is_array($pid)) {
                    $mid = $pid["produk_id"];
                    $bid = $pid["produk_dasar_id"];
                }
                else {
                    matiHere("unknown methode to exec <br>Silahkan hubungi admin untuk dilakukan pengecekan lebih lanjut");
                }
                $p->addFilter("produk_id='$mid'");
                $p->addFilter("produk_dasar_id='$bid'");
                $tem = $p->lookUpAll()->result();
                if (count($tem) > 0) {
                    $p->updateData(array("id" => $tem[0]->id), $data) or matiHere("Gagal melakukan pembaharuan data. Silahkan tutup browser telebih dahulu untuk membersihkan sesi dan coba kembali.");
                }
                else {
                    //karena cuma update kalau belum ada berarti ada kesalahan saat pembuatan komposisi workorder yang tidak melaukan insert ke komposisi project
                    matiHere("unknown methode to exec <br>Silahkan hubungi admin untuk dilakukan pengecekan lebih lanjut " . __LINE__);
                }
                break;
            default:
                $p->setFilters(array());
                $p->addFilter("produk_id='$pid'");
                $tem = $p->lookUpAll()->result();
                if (count($tem) > 0) {
                    $tocek = array();
                    foreach ($tem as $tem_0) {
                        $tocek[$tem_0->jenis][$tem_0->produk_dasar_id] = (array)$tem_0;
                    }
                    if (isset($tocek[$data["jenis"]][$data["produk_dasar_id"]])) {
                        //cek sudah ada
                        $preval = $tocek[$data["jenis"]][$data["produk_dasar_id"]]["nilai"];
                        $prevalQty = $tocek[$data["jenis"]][$data["produk_dasar_id"]]["jml"];
                        $preDebet = $tocek[$data["jenis"]][$data["produk_dasar_id"]]["debet"];
                        $preQtyDebet = $tocek[$data["jenis"]][$data["produk_dasar_id"]]["qty_debet"];
                        $preQtysaldo = $tocek[$data["jenis"]][$data["produk_dasar_id"]]["qty_saldo"];
                        $preSaldo = $tocek[$data["jenis"]][$data["produk_dasar_id"]]["saldo"];
                        $newJml = $prevalQty + $data["jml"];
                        $newDebet = $preDebet + ($data["jml"] * $data["harga"]);
                        $newsaldo = $preSaldo + ($data["jml"] * $data["harga"]);
                        $update = array(
                            "nilai" => $newsaldo / $newJml,
                            "harga" => $newsaldo / $newJml,
                            "jml" => $newJml,
                            "debet" => $newDebet,
                            "qty_debet" => $newJml,
                            "qty_saldo" => $newJml,
                            "saldo" => $newsaldo,
                        );
                        $where = array(
                            "id" => $tocek[$data["jenis"]][$data["produk_dasar_id"]]["id"],
                        );
                        $p->setFilters(array());
                        $p->updateData($where, $update) or matiHere("gagal memperbaharui data LINE: " . __LINE__);
                    }
                    else {
                        //belum ada insert dulu
                        $toInsert = array();
                        foreach ($aliasTableFields as $key => $src) {
                            $toInsert[$key] = $data[$src];
                        }
                        $toInsert["debet"] = $data["harga"] * $data["jml"];
                        $toInsert["saldo"] = $data["harga"] * $data["jml"];
                        $toInsert["qty_debet"] = $data["jml"];
                        $toInsert["qty_saldo"] = $data["jml"];
                        $p->setFilters(array());
                        $p->addData($toInsert) or matiHere("gagal memperbaharui data *");
                    }
                }
                else {
                    //belum ada bro insert aja
                    $toInsert = array();
                    foreach ($aliasTableFields as $key => $src) {
                        $toInsert[$key] = $data[$src];
                    }
                    $toInsert["debet"] = $data["harga"] * $data["jml"];
                    $toInsert["saldo"] = $data["harga"] * $data["jml"];
                    $toInsert["qty_debet"] = $data["jml"];
                    $toInsert["qty_saldo"] = $data["jml"];
                    $p->setFilters(array());
                    $p->addData($toInsert) or matiHere("gagal memperbaharui data LINE: " . __LINE__);
                }
                break;
        }

    }

    public function rebuildMasterKomposisi__($pid, $data, $mdlName)
    {
        $aliasTableFields = array(
            //project_komposisi =>woroderprojectKomposisi //catatan

            "produk_id" => "produk_id",
            "produk_nama" => "produk_nama",
            "produk_dasar_id" => "produk_dasar_id",
            "produk_dasar_nama" => "produk_dasar_nama",
            "jml" => "jml",
            "harga" => "harga",
            "nilai" => "nilai",
            "cat_id" => "cat_id",
            "cat_nama" => "cat_nama",
            // "fase_id"           => "fase_id",
            "satuan_id" => "satuan_id",
            "satuan_nama" => "satuan",
        );
        $this->load->model("Mdls/MdlProjectKomposisi");
        $this->load->model("Mdls/" . $mdlName);
        $m = new $mdlName();
        $p = new MdlProjectKomposisi();
        $fields = $p->getFields();
        // arrprint($data);
        $p->addFilter("produk_id='$pid'");
        $tem = $p->lookUpAll()->result();

        $m->addFilter("produk_id='$pid'");
        $tmpSrc = $m->lookUpAll()->result();

        if (count($tmpSrc) > 0) {
            $tmpKOmposisiJenisWorkOrder = array();
            foreach ($tmpSrc as $tmpSrc_0) {
                $tmpKOmposisiJenisWorkOrder[$tmpSrc_0->jenis][$tmpSrc_0->produk_dasar_id][] = (array)$tmpSrc_0;
            }
            $allKomposisis = array();
            $tmp = array();
            foreach ($tmpKOmposisiJenisWorkOrder as $jenis => $tmpAllJenis) {
                $total_qty = 0;
                $total_harga = 0;

                foreach ($tmpAllJenis as $bahan_id => $rowBahanData) {
                    $allKomposisiBahan = array();

                    foreach ($rowBahanData as $rowBahanData_0) {
                        foreach ($aliasTableFields as $key => $key1) {
                            $tmp[$jenis][$bahan_id][$key1] = $rowBahanData_0[$key1];
                        }
                        if (!isset($tmp[$jenis][$bahan_id]["new_qty"])) {
                            $tmp[$jenis][$bahan_id]["new_qty"] = 0;
                        }
                        if (!isset($tmp[$jenis][$bahan_id]["harga_avg"])) {
                            $tmp[$jenis][$bahan_id]["harga_avg"] = 0;
                        }
                        $total_qty += $rowBahanData_0->jml;
                        $total_harga += $rowBahanData_0->jml * $rowBahanData_0->harga;
                        $tmp[$jenis][$bahan_id]["new_qty"] = $total_qty;
                        $tmp[$jenis][$bahan_id]["new_subtotal"] = $total_harga;
                        $tmp[$jenis][$bahan_id]["harga_avg"] = $total_harga / $total_qty;

                    }
                    // $allKomposisis[$jenis][]=$tmp + array("harga_avg"=>$total_harga/$total_qty,"new_qty"=>$total_qty,"new_subtotal"=>$total_harga);
                    arrPrint($rowBahanData);
                }
            }
            // arrPrint($tmpKOmposisiJenisWorkOrder);
        }
        arrprint($tmp);


        matiHEre(__LINE__);
        if (count($tem) > 0) {
            // arrPrint($tem);
            $tocek = array();
            foreach ($tem as $tem_0) {
                $tocek[$tem_0->jenis][$tem_0->produk_dasar_id] = (array)$tem_0;
            }
            if (isset($tocek[$data["jenis"]][$data["produk_dasar_id"]])) {
                //cek sudah ada
                // arrPrint($tocek[$data["jenis"]][$data["produk_dasar_id"]]);
                $preval = $tocek[$data["jenis"]][$data["produk_dasar_id"]]["nilai"];
                $prevalQty = $tocek[$data["jenis"]][$data["produk_dasar_id"]]["jml"];
                $preDebet = $tocek[$data["jenis"]][$data["produk_dasar_id"]]["debet"];
                $preQtyDebet = $tocek[$data["jenis"]][$data["produk_dasar_id"]]["qty_debet"];
                $preQtysaldo = $tocek[$data["jenis"]][$data["produk_dasar_id"]]["qty_saldo"];
                $preSaldo = $tocek[$data["jenis"]][$data["produk_dasar_id"]]["saldo"];
                $newJml = $prevalQty + $data["jml"];
                $newDebet = $preDebet + ($data["jml"] * $data["nilai"]);
                $newsaldo = $preSaldo + ($data["jml"] * $data["nilai"]);

                $update = array(
                    "jml" => $newJml,
                    "debet" => $newDebet,
                    "qty_debet" => $newJml,
                    "qty_saldo" => $newJml,
                    "saldo" => $newsaldo,
                );
                $where = array(
                    "id" => $tocek[$data["jenis"]][$data["produk_dasar_id"]]["id"],
                );
                $p->setFilters(array());
                $p->updateData($where, $update) or matiHere("gagal memperbaharui data LINE: " . __LINE__);

                cekMerah($this->db->last_query());
                // matiHere();
            }
            else {
                //belum ada insert dulu
                cekHitam();
                $toInsert = array();

                foreach ($aliasTableFields as $key => $src) {
                    $toInsert[$key] = $data[$src];
                }
                $toInsert["debet"] = $data["nilai"] * $data["jml"];
                $toInsert["saldo"] = $data["nilai"] * $data["jml"];
                $toInsert["qty_debet"] = $data["jml"];
                $toInsert["saldo_qty"] = $data["jml"];
                arrPrint($toInsert);
                $p->setFilters(array());
                $p->addData($toInsert) or matiHere("gagal memperbaharui data LINE: " . __LINE__);
                cekHitam($this->db->last_query());

            }
            //             arrprint($tocek);
            // arrPrint($data);
            // matiHEre();
        }
        else {
            //belum ada bro insert aja
            $toInsert = array();

            foreach ($aliasTableFields as $key => $src) {
                $toInsert[$key] = $data[$src];
            }
            $toInsert["debet"] = $data["nilai"] * $data["jml"];
            $toInsert["saldo"] = $data["nilai"] * $data["jml"];
            $toInsert["qty_debet"] = $data["jml"];
            $toInsert["qty_saldo"] = $data["jml"];
            $p->setFilters(array());
            $p->addData($toInsert) or matiHere("gagal memperbaharui data LINE: " . __LINE__);
            cekBiru($this->db->last_query());
        }

        // arrPrint($pid);
        // arrPrint($toInsert);
        // matiHEre(__LINE__);
    }

    public function selectItem()
    {
        $ccode     = $this->uri->segment(4);
        $produk_id = $this->uri->segment(5);
        $value     = $_GET["value"];
        $key       = $_GET["key"];
//        arrPrint($_GET);
//        matiHere(__LINE__);
        switch ($key) {
            case "fase_id":

                //tambahain produkid
                if (!isset($_SESSION[$ccode][$produk_id]["produk_id"])) {
                    $_SESSION[$ccode][$produk_id]["produk_id"] = $produk_id;
                }
                $_SESSION[$ccode][$produk_id][$key] = $value;

                $this->load->model("Mdls/MdlProjectWorkOrderSub");
                $mWo = new MdlProjectWorkOrderSub();
                $mWo->addFilter("fase_id='$value'");
                $WorkOrder = $mWo->lookUpAll()->result();
                $res = array(
                    'session' => $_SESSION[$ccode],
                    'fase_id' => $WorkOrder,
                );

                echo json_encode($res);

                break;
            case "sub_fase_id":

                unset($_SESSION[$ccode][$produk_id]['sub_fase_id']);
                //tambahain produkid

                $arrValue = explode(",", $value);

                foreach ($arrValue as $k => $ids) {
                    if (!isset($_SESSION[$ccode][$produk_id][$key][$k])) {
                        $_SESSION[$ccode][$produk_id][$key][$k] = $ids;
                    }
                    $_SESSION[$ccode][$produk_id]['sub_fase_id'][$k] = $ids;
                }

                $fase_id = $_SESSION[$ccode][$produk_id]["fase_id"];
                $sub_fase_id = $_SESSION[$ccode][$produk_id]['sub_fase_id'];
                $this->load->model("Mdls/MdlProjectKomposisiWorkorderSub");
                $mWo = new MdlProjectKomposisiWorkorderSub();

                $mainJenis = array(
                    "produk",
//                    "biaya"
                );

                //LIST BAHAN DAN BIAYA
                $mWo->setFilters(array());
                $mWo->addFilter("produk_id='$produk_id'");
                $mWo->addFilter("fase_id='$fase_id'");
                $mWo->addFilter("status=1");
                $mWo->addFilter("trash=0");
                $mWo->addFilter("jenis_transaksi='5582'");

                $this->db->where_in("sub_fase_id", $sub_fase_id);
                $this->db->where_in("jenis", $mainJenis);
                $this->db->order_by("jenis", "desc");

                $KomposisiWorkorder = $mWo->lookUpAll()->result();
                $qu = $this->db->last_query();

                //LIST ROOM JIKA MENGGUNAKAN MODE ROOM
                $this->load->model("Mdls/MdlProjectKomposisiWorkorderSub");
                $mWoR = new MdlProjectKomposisiWorkorderSub();
                $mWoR->setFilters(array());
                $mWoR->addFilter("produk_id='$produk_id'");
                $mWoR->addFilter("fase_id='$fase_id'");
                $mWoR->addFilter("status=1");
                $mWoR->addFilter("trash=0");
                $mWoR->addFilter("jenis='room'");
                $this->db->where_in("sub_fase_id", $sub_fase_id);
                $this->db->order_by("jenis", "desc");
                $KomposisiWorkorderRoom = $mWoR->lookUpAll()->result();
                $quR = $this->db->last_query();

                //LIST KOMPOSISI PER ROOM JIKA MENGGUNAKAN MODE ROOM
                $this->load->model("Mdls/MdlProjectKomposisiWorkorder");
                $mWoRKom = new MdlProjectKomposisiWorkorder();
                $mWoRKom->setFilters(array());
                $mWoRKom->addFilter("produk_id='$produk_id'");
                $mWoRKom->addFilter("fase_id='$fase_id'");
                $mWoRKom->addFilter("status=1");
                $mWoRKom->addFilter("trash=0");
//                $mWoRKom->addFilter("jenis='room'");
//                $this->db->where_in("sub_fase_id", $sub_fase_id);
                $this->db->order_by("jenis", "desc");
                $KomposisiWorkorderRoomKomp = $mWoRKom->lookUpAll()->result();
                $quRKomp = $this->db->last_query();

                $komposisiRoom = array();
                if (!empty($KomposisiWorkorderRoomKomp)) {
                    foreach ($KomposisiWorkorderRoomKomp as $k => $dataRoom) {
                        switch ($dataRoom->jenis) {
                            case "room":
                                break;
                            case "produk_room":
                                $komposisiRoom[$dataRoom->room_id][] = $dataRoom;
                                break;
                            case "biaya_room":
                                $komposisiRoom[$dataRoom->room_id][] = $dataRoom;
                                break;
                        }
                    }
                }

                $res = array(
                    'session' => $_SESSION[$ccode],
                    'sub_fase_id' => $KomposisiWorkorder,
                    'room' => $KomposisiWorkorderRoom,
                    'komposisi_room' => $komposisiRoom,
                );

                echo json_encode($res);

                break;
            case "produk_fase_id":
                unset($_SESSION[$ccode][$produk_id]['produk_fase_id']);
                $postProduk = $_POST['data'];
                $validProduk=array();
                if(!empty($postProduk)){
                    foreach($postProduk as $ky => $datas){
                        if($datas['values']*1>0){
                            $validProduk[$ky]  = $datas;
                        }
                    }
                }
                else{
                    $validProduk = array();
                }
                $_SESSION[$ccode][$produk_id]['produk_fase_id'] = $validProduk;
                echo json_encode($_SESSION[$ccode][$produk_id]);
                break;
            case "material":
                switch ($value) {
                    case "1":

                        break;
                    case "2":
                        break;
                }

                break;
            case "produk_paket":

                $_SESSION[$ccode][$produk_id]['produk_fase_id'] = array();
                $fase_id = $_SESSION[$ccode][$produk_id]["fase_id"];
                $sub_fase_id = $_SESSION[$ccode][$produk_id]['sub_fase_id'];

                $this->load->model("Mdls/MdlProjectSubTasklistKomposisi");
                $stlk = new MdlProjectSubTasklistKomposisi();
                $stlk->setFilters(array());
                $stlk->addFilter("fase_id='$fase_id'");
                $stlk->addFilter("sub_fase_id='$sub_fase_id'");
                $stlk->addFilter("produk_id='$produk_id'");
                $stlk->addFilter("status=1");
                $stlk->addFilter("trash=0");
                $sumSubTaskListKomp = $stlk->lookUpAll()->result();
                $arrSubTasklistKomp = array();
                if(!empty($sumSubTaskListKomp)){
                    foreach($sumSubTaskListKomp as $k => $datas){
                        $jnis=$datas->jenis == "item_komposit" ? "produk" : $datas->jenis;
                        $ket= isset($datas->keterangan) && $datas->keterangan != "" ? " (" . $datas->keterangan . ")" : "";
                        $arrSubTasklistKomp[$jnis][$datas->produk_dasar_id . $ket] = $datas;
                        if($jnis=="biaya"){
                            $arrSubTasklistKomp[$jnis][$datas->produk_dasar_id . $ket]->produk_dasar_nama = $datas->produk_dasar_nama . $ket;
                        }
                    }
                }


                $this->load->model("Mdls/MdlProjectKomposisiWorkorderSub");
                $mWo = new MdlProjectKomposisiWorkorderSub();

                $mainJenis = array(
                    "supplies",
                    "produk",
                    "item_komposit",
                    "biaya"
                );

                $mWo->setFilters(array());
                $mWo->addFilter("produk_id='$produk_id'");
                $mWo->addFilter("fase_id='$fase_id'");
                $mWo->addFilter("status=1");
                $mWo->addFilter("trash=0");
                $mWo->addFilter("jenis_transaksi='5582'");

//                $this->db->where_in("sub_fase_id", $sub_fase_id);
                $this->db->where_in("jenis", $mainJenis);
                $this->db->order_by("jenis", "desc");

                $KomposisiWorkorder = $mWo->lookUpAll()->result();
                $qu = $this->db->last_query();
                showlast_query("hitam");

                //  arrPrint($KomposisiWorkorder);
                //  matiHere(__LINE__);

                $prdFromWo = array();
                if(!empty($KomposisiWorkorder)){
                    foreach($KomposisiWorkorder as $k => $datas){
                        $jnis= $datas->jenis == "item_komposit" ? "produk" : $datas->jenis;
                        $ket= isset($datas->keterangan) && $datas->keterangan != "" ? " (" . $datas->keterangan . ")" : "";
                        $prdFromWo[$jnis][$datas->produk_dasar_id . $ket] = $datas;
                        if($jnis=="biaya"){
                            $prdFromWo[$jnis][$datas->produk_dasar_id . $ket]->produk_dasar_nama = $datas->produk_dasar_nama . $ket;
                        }
                    }
                }

                $allProduk = array();
                $this->db->select("*");
                $this->db->where("status=1 AND trash=0");
                $tmpAllProd = $this->db->get("produk")->result();
                if(!empty($tmpAllProd)){
                    foreach($tmpAllProd as $k => $ddPrd){
                        $allProduk[$ddPrd->id] = (array)$ddPrd;
                    }
                }

                $allCost = array();
                $this->db->select("*");
                $this->db->where("status=1 AND trash=0");
                $tmpAllCost = $this->db->get("dta_biayaproduksi")->result();
                if(!empty($tmpAllCost)){
                    foreach($tmpAllCost as $k => $ddCost){
                        $allCost[$ddCost->id] = (array)$ddCost;
                    }
                }

                $this->db->select("*");
                $this->db->where("produk_id='$value' and project_id='$produk_id' and status=1 and trash=0");
                $this->db->order_by("jenis", "desc");
                $tmpPaket = $this->db->get("produk_komposisi_paket")->result();
                $prdPaket=array();
                $namaPaket="";
                if(!empty($tmpPaket)){
                    foreach($tmpPaket as $row){
                        $prdPaket[] = array(
                            "produk_dasar_id" => $row->produk_dasar_id,
                            "jml" => $row->jml,
                            "keterangan" => $row->keterangan,
                            "jenis" => $row->jenis,
                            "satuan" => $row->satuan,
                        );
                        $namaPaket = $row->produk_nama;
                    }
                }
                $validProduk=array();
                $dummyProduk=array();
                if(!empty($prdPaket)){
                    foreach($prdPaket as $k => $pkdta){
                        $pdi = $pkdta['produk_dasar_id'];
                        $ket = isset($pkdta['keterangan']) && $pkdta['keterangan'] != "" ? " (".$pkdta['keterangan'].")" : "";
                        $jml = $pkdta['jml'];
                        $pkd_jnis = $pkdta['jenis'];
                        if(isset($prdFromWo[$pkd_jnis][$pdi.$ket])){
                            $validProduk[$pkd_jnis][$pdi.$ket] = (array)$prdFromWo[$pkd_jnis][$pdi.$ket];
                            $validProduk[$pkd_jnis][$pdi.$ket]['values'] = $jml; //jml penugasan
                            $validProduk[$pkd_jnis][$pdi.$ket]['jml_return'] = $arrSubTasklistKomp[$pkd_jnis][$pdi.$ket]->jml_return; //jml penugasan
                        }
                        $_SESSION[$ccode][$produk_id]['produk_fase_id'] = $validProduk;
                        $dummyProduk[] = $validProduk[$pkd_jnis][$pdi.$ket];
                    }
                }

                //console.log(".json_encode($validProduk).");
                //console.log(".json_encode($prdPaket).");
                //console.log(".json_encode($dummyProduk).");
//                console.error(".json_encode($prdFromWo).");

                echo "
                    <script>

                        var updated = JSON.parse(atob('".base64_encode(json_encode($validProduk))."'));
                        var dummyProduk = JSON.parse(atob('".base64_encode(json_encode($dummyProduk))."'));
                        var prdFromWo = JSON.parse(atob('".base64_encode(json_encode($prdFromWo))."'));
                        var prdPaket = JSON.parse(atob('".base64_encode(json_encode($prdPaket))."'));
                        var showNilai = 1
                        var table = ''

                        table += \"<div class='container-fluid'>\"
                        table += \"<input type='text' value='$value' name='produk_paket_id' id='produk_paket_id' class='hidden'>\"
                        table += \"<input type='text' value='$namaPaket' name='produk_paket_nama' id='produk_paket_nama' class='hidden'>\"
                        table += \"<div class='table-responsives'>\"
                        table += \"<table id='table_container_material' class='table dataTable compact display table-bordered table-condensedx'>\"
                        table += \"<caption class='text-center text-bold text-uppercase bg-gray fa-2x'>$namaPaket</caption>\"
                        table += '<thead>'
                        table += '<tr>'
                        table += '<th>No</th>'
                        table += '<th>MATERIAL & JASA</th>'
                        table += '<th>UoM</th>'

                        if(showNilai){
                            table += \"<th class='text-center'>Hrg Anggaran<br>(satuan)</th>\"
                        }

                        table += \"<th class='text-center'>B.O.M</th>\"
                        table += \"<th class='text-center'>digunakan</th>\"
                        table += \"<th class='text-center'>Belum<br>ditugaskan</th>\"
                        table += \"<th class='text-center'>penugasan<br>saat ini</th>\"
                        table += \"<th class='text-center'>akhir</th>\"


                        if(showNilai){
                            table += \"<th class='text-center'>Nilai<br>Anggaran/WO</th>\"
                        }

                        table += '</tr>'
                        table += '</thead>'
                        table += '<tbody>'

                        var no=0;
                        var total_saldo = 0;
                        var total_qty = 0;
                        var dikerjakan = 0;
                        var total_kredit = 0;
                        var totHpp = 0;
                        var totPpv = 0;
                        var totJual = 0;
                        var totAng = 0;
                        var sumHpp = 0;
                        var sumPpv = 0;
                        var sumJual = 0;
                        var sumAng = 0;
                        var values = 0;
                        var sisa_pakai = 0;
                        var kelebihan_pakai = 0;
                        var sumsisapakai = 0;
                        var sumsisapenugasan = 0;

                        dummyProduk.sort(function(a, b){
                            return a.jenis - b.jenis;
                        });

                        var stok_tidak_lengkap = 0;
                        var produk_tidak_lengkap = 0;
                        var arr_stok_tidak_lengkap = {};
                        var arr_produk_tidak_lengkap = {};

                        jQuery.each(dummyProduk, function(a, b){
                            no++;
                            produk_dasar_nama = b.produk_dasar_nama;
                            pdi = b.jenis + '_' + b.produk_dasar_id;
                            satuan = b.satuan;
                            harga  = b.harga*1>0 ? Math.round(b.harga*1) : 0;
                            nilai  = b.nilai*1>0 ? b.nilai*1 : 0;
                            jml    = b.qty_saldo*1;
                            bom    = b.jml*1;
                            saldo  = b.qty_saldo*1;
                            dikerjakan  = b.dikerjakan*1>0 ? b.dikerjakan*1 : 0;
                            qty_kredit  = b.qty_kredit*1>0 ? b.qty_kredit*1 : 0;
                            hpp  = b.hrg_hpp*1> 0 ? b.hrg_hpp*1 : 0;
                            ppv  = b.hrg_ppv*1> 0 ? b.hrg_ppv*1 : 0;
                            jual = b.hrg_jual*1> 0 ? b.hrg_jual*1 : 0;
                            values = b.values*1> 0 ? b.values*1 : 0;
                            sisa_pakai = saldo*1-values*1;
                            kelebihan_pakai = sisa_pakai<0 ? sisa_pakai*-1 : 0
                            returned = b.jml_return*1> 0 ? b.jml_return*1 : 0;

                            var classColor=''
                            if(bom*1> 0 && sisa_pakai*1<0){
                                stok_tidak_lengkap++;
                                arr_stok_tidak_lengkap[pdi] = {produk_dasar_nama,sisa_pakai}
                                classColor = 'text-red text-bold'
                            }
                            if(isNaN(sisa_pakai)){
                                produk_tidak_lengkap++;
                                arr_produk_tidak_lengkap[pdi+'-'+no] = {produk_dasar_nama,sisa_pakai}
                                classColor = 'text-red text-bold'
                            }

                            produk_dasar_nama_f = produk_dasar_nama.length > 35 ? produk_dasar_nama.substring(0, 35) + '...' : produk_dasar_nama;

                            table += \"<tr class='\"+classColor+\"'>\"
                            table += '<td>'+no+'</td>'
                            table += '<td>'+produk_dasar_nama_f+'</td>'
                            table += '<td>'+satuan+'</td>'
                            if(showNilai){
                                table += \"<td class='bg-infox text-right'>\"+addCommas(harga)+\"</td>\"
                            }
                            table += \"<td class='text-bold text-center'>\"+addCommas(bom)+\"</td>\"
                            table += \"<td class='text-bold text-center'>\"+addCommas(qty_kredit)+\"</td>\"
                            table += \"<td class='text-bold text-center'>\"+addCommas(saldo)+\"</td>\"
                            table += \"<td class='text-bold text-center'><input size='5' value='\"+values+\"' readonly class='form-control form-control-sm text-right'></td>\"
                            table += \"<td class='text-bold text-center'><input size='5' value='\"+sisa_pakai+\"' readonly class='form-control form-control-sm text-right'></td>\"

                            if(showNilai){
                                table += \"<td class='bg-infox text-right'>\"+addCommas(harga*values)+\"</td>\"
                            }
                            table += '</tr>'
                            total_saldo += saldo*1;
                            total_qty   += jml*1;
                            total_kredit   += qty_kredit*1;
                            totHpp  += hpp;
                            totPpv  += ppv;
                            totJual += jual;
                            totAng  += harga;
                            sumHpp  += hpp*jml;
                            sumPpv  += ppv*jml;
                            sumJual += jual*jml;
                            sumAng  += harga*values;
                            sumsisapakai  += sisa_pakai;
                            sumsisapenugasan  += values;
                        })
                        table += '</tbody>'
                        table += \"<tfoot class='bg-gray'>\"
                        table += '<tr>'
                        table += '<th>-</th>'
                        table += '<th>-</th>'
                        table += '<th>-</th>'

                        if(showNilai){
                            table += \"<th class='text-right'>-</th>\"
                        }

                        table += \"<th class='text-center'>\"+addCommas(total_qty)+\"</th>\"
                        table += \"<th class='text-center'>\"+addCommas(total_kredit)+\"</th>\"
                        table += \"<th class='text-center'>\"+addCommas(total_saldo)+\"</th>\"
                        table += \"<th class='text-right'>\"+addCommas(sumsisapenugasan)+\"</th>\"
                        table += \"<th class='text-right'>\"+addCommas(sumsisapakai)+\"</th>\"

                        if(showNilai){
                            table += \"<th class='text-right'>\"+addCommas(sumAng)+\"</th>\"
                        }
                        table += '</tr>'
                        table += '</tfoot>'
                        table += '</table>'
                        table += '</div>'
                        table += \"<div class='msgxx'></div>\"
                        table += '</div>'
                        table += '</div>'
                        top.$('#container_material_paket').html(table);

                        var notif = ''
                        if(total_saldo*1==0 || stok_tidak_lengkap*1>0 || produk_tidak_lengkap*1>0){
                            if(total_saldo*1==0){
                                notif += \"<div style='font-size: 18px;' class='panel bg-red text-center'>Paket ini belum memiliki produk komposisi, silahkan tambah baru / edit dari yang sudah ada.</div>\";
                            }
                            else if(produk_tidak_lengkap*1>0){
                                notif += \"<div style='font-size: 18px;' class='panel bg-red text-center'>ada \"+stok_tidak_lengkap+\" produk yang tidak ada pada BOM Utama, kemungkinan Anda salah dalam merelasikan paket </div>\";
                            }
                            else if(stok_tidak_lengkap*1>0){
                                notif += \"<div style='font-size: 18px;' class='panel bg-red text-center'>ada \"+stok_tidak_lengkap+\" material/jasa kekurangan stock.</div>\";
                            }
                            else{
                                notif += \"<div style='font-size: 18px;' class='panel bg-red text-center'> penyebab error tidak diketahui </div>\";
                            }
                            top.$('#simpanTugas').addClass('hidden');
                            $('#produk_paket_id').val('');
                            $('#produk_paket_nama').val('');
                        }
                        else{
                            top.$('#simpanTugas').removeClass('hidden');
                        }
                        $('.msgxx').html(notif);
                    </script>
                ";

//                echo json_encode($_SESSION[$ccode][$produk_id]['produk_fase_id']);

                break;
            case "employee_id":
                $this->load->model("Mdls/MdlEmployee_all");
                $e = new MdlEmployee_all();
                $e->addFilter("id='$value'");
                $empl = $e->lookUpAll()->result();
                if (!isset($_SESSION[$ccode][$produk_id]["produk_id"])) {
                    $_SESSION[$ccode][$produk_id]["produk_id"] = $produk_id;
                }
                $_SESSION[$ccode][$produk_id]["employee_id"] = $value;
                $_SESSION[$ccode][$produk_id]["employee_nama"] = $empl[0]->nama;
                break;
            default:
                if (!isset($_SESSION[$ccode][$produk_id]["produk_id"])) {
                    $_SESSION[$ccode][$produk_id]["produk_id"] = $produk_id;
                }
                $_SESSION[$ccode][$produk_id][$key] = $value;
                break;
        }
    }

    public function taskList()
    {

//        arrPrint( $this->generatorPaymentSource() );
        //pembagian prosedur kerja berdasarkan seting anggota dan workorder
        $cCode = $this->uri->segment(3);
        $pid = $prodID = $this->uri->segment(4);
        $load = $this->uri->segment(5);

        $this->load->model("Mdls/MdlProdukProject");
        $this->load->model("Mdls/MdlTimWorkProject");
        $this->load->model("Mdls/MdlProjectWorkOrder");
        $this->load->model("Mdls/MdlProjectWorkOrderSub");
        $this->load->model("Mdls/MdlProjectKomposisiWorkorder");
        $this->load->model("Mdls/MdlProgresTasklist");

        $_SESSION['MasterData']['show_nilai'] = isset($_GET['shownilai']) ? $_GET['shownilai'] : 1;

        $p = new MdlProdukProject;
        $t = new MdlTimWorkProject;
        $w = new MdlProjectWorkOrder;
        $k = new MdlProjectKomposisiWorkorder;
        $pt = new MdlProgresTasklist;
        $sw = new MdlProjectWorkOrderSub;

        //region data project
        $p->addFilter("id='$pid'");
        $dataMaster     = $p->lookUpAll()->result();
        $showFields     = $p->getListedFields();
        $projectNama    = $dataMaster[0]->nama;
        $projectStart   = $dataMaster[0]->project_start; // project sudah di starting atau belum
        $projectLock    = $dataMaster[0]->lock; // BOM sudah di lock/simpan atau belum
        $projectQuot    = $dataMaster[0]->quot_status; // quotation susah diapprove atau belum
//        $startDate      = $dataMaster[0]->start_dtime != "" ? $dataMaster[0]->start_dtime : "belum ditentukan";
//        $endDate        = $dataMaster[0]->end_dtime != "" ? $dataMaster[0]->end_dtime : "belum ditentukan";
        //endregion

        //region timwork
        $t->addFilter("produk_id='$prodID'");
        $timWork = $t->lookUpAll()->result();
        //endregion

        //region workOrder
        $w->addFilter("produk_id='$prodID'");
        $workOrder = $w->lookUpAll()->result();
        $workOrderLabel = $w->getListedFields();
        //endregion

        //region progess tasklist
        $progresTaskList = $pt->lookUpAll()->result();
        $progressTaskLabel = $pt->getListedFields();
        //endregion

        //region komposisi workorder
        $k->addFilter("produk_id='$prodID'");//baca dari yang dipilih di rencana kerja
        $k->addFilter("jenis='produk'");
        $tempAllKomposisi = $k->lookUpAll()->result();
        $komposisiWorkOrder = array();
        if (count($tempAllKomposisi) > 0) {
            foreach ($tempAllKomposisi as $tempAllKomposisi_0) {
                $komposisiWorkOrder[$tempAllKomposisi_0->fase_id][] = array(
                    "produk_dasar_id" => $tempAllKomposisi_0->produk_dasar_id,
                    "produk_dasar_nama" => $tempAllKomposisi_0->produk_dasar_nama,
                    "jml" => $tempAllKomposisi_0->jml,
                );
            }
        }
        //endregion

        //region tasklist
        $this->load->model("MdlTasklistProject");
        $tl = new MdlTasklistProject();
        $tl->addFilter("produk_id='$prodID'");
        $this->db->order_by("id", "desc");
        $tempTaskList = $tl->lookUpAll()->result();
        $sub_fase_id = $tempTaskList[0]->sub_fase_id;
        $taskListFields = $tl->getListedFields();
        $taskList = array();
        $allowedEdit = array();

        foreach ($this->customAccesProject as $metode => $accessList) {
            if (in_array("MdlTasklistProject", $accessList)) {
                $allowedEdit[$metode] = true;
            }
        }

        $appr_qc = !in_array("o_project_spv", $this->session->login['membership']) ? "hidden" : "";

        if (count($tempTaskList) > 0) {
            $type = 1; //1=mode button || 0=mode dropdown
            $linkSelector = MODUL_PATH . get_class($this) . "/updateTask/tasklist/";
            $linkEdit = MODUL_PATH . get_class($this) . "/editTask/tasklist/";
            $linkPrint = MODUL_PATH . get_class($this) . "/print_tasklist/";
            $linkCreate = MODUL_PATH . get_class($this) . "/showCreateSubTasklist/";
            $linkUpdate = MODUL_PATH . get_class($this) . "/exeSubTasklist/";
            $linkUpdateQc = MODUL_PATH . get_class($this) . "/exeSubTasklistQc/";
            $linkUploadPhoto = MODUL_PATH . get_class($this) . "/uploadPhoto/";

            foreach ($tempTaskList as $tempTaskList_0) {
                $tmp = array();
                foreach ($taskListFields as $key => $label) {
                    $tmp[$key] = $tempTaskList_0->$key;
                }
                if (count($allowedEdit) > 0 && $tempTaskList_0->employee_id == $this->session->login['id']) {
                    $scriptID = $tempTaskList_0->id . "-" . $tempTaskList_0->produk_id;
                    $progress_id = $tempTaskList_0->progress_id;
                    $progress_percent = $tempTaskList_0->progress_percent;

                    if ($type) {
                        $btnLink = "";
                        $btnLink .= "<div class='btn-group-vertical'>";
                        foreach ($allowedEdit as $mm => $valm) {
                            $opt = '';
                            $typBtn = 'n';
                            switch ($mm) {
                                case "create":
                                    $class = $progress_id > 1 && $progress_id < 3 ? "btn-primary" : "btn-default";
                                    $dataicon = "glyphicon glyphicon-plus";
                                    $btnDisabled = $progress_id > 1 && $progress_id < 3 ? "" : "disabled";
                                    $txt = " sub";
                                    $onclick = $progress_id > 1 && $progress_id < 3 ? " onclick=\"fnTaskList.create(this)\" " : "";
                                    break;
                                case "followup":
                                    $class = $progress_percent == 100 ? "btn-primary" : "btn-default";
                                    $dataicon = "fa fa-star";
                                    $btnDisabled = $progress_percent == 100 ? "" : "disabled";
                                    $txt = " sub";
                                    $onclick = $progress_percent == 100 ? " onclick=\"fnTaskList.followup(this)\" " : "";
                                    break;
                                case "update":
                                    $class = $progress_id == 3 ? "btn-default" : "btn-warning";
                                    $dataicon = "glyphicon glyphicon-pencil";
                                    $btnDisabled = $progress_id == 3 ? "disabled" : "";
                                    $txt = "";
                                    $onclick = $progress_id == 3 ? "" : " onclick=\"fnTaskList.update(this)\" ";
                                    $onclick_broke = $progress_id == 3 ? "x" : "";
                                    $opt = " data-progress='$progress_id' ";
                                    $typBtn = '';
                                    break;
                                case "edit":
                                    $class = $progress_percent <= 0 ? "btn-default" : "btn-warning";
                                    $dataicon = "glyphicon glyphicon-pencil";
                                    $btnDisabled = $progress_percent <= 0 ? "disabled" : "";
                                    $txt = "";
                                    $onclick = $progress_percent <= 0 ? "" : " onclick=\"fnTaskList.edit(this)\" ";
                                    $onclick_broke = $progress_percent <= 0 ? "x" : "";
                                    $opt = " data-progress='$progress_id' ";
                                    $typBtn = '';
                                    break;
                                case "delete":
                                    $class = $progress_id > 1 ? "btn-default" : "btn-danger";
                                    $dataicon = "glyphicon glyphicon-trash";
                                    $btnDisabled = $progress_id > 1 ? "disabled" : "";
                                    $txt = "";
                                    $onclick = $progress_id > 1 ? "" : " onclick=\"fnTaskList.delete(this)\" ";
                                    break;
                                default:
                                    $class = "bg-olive";
                                    $dataicon = "glyphicon glyphicon-eye-open";
                                    $btnDisabled = "";
                                    $txt = "";
                                    $onclick = " onclick=\"fnTaskList.view(this)\" ";
                                    break;
                            }

                            if ($typBtn == 'n') {
                                $btnLink .= "<div data-id='$mm-$scriptID' $opt $onclick class='btn btn-xs btn-flat $class text-left' $btnDisabled><i class='$dataicon'></i>&nbsp;&nbsp;$mm$txt</div>";
                            }
                            else {

                                $check01 = $progress_id == 1 ? "&nbsp;<i class='fa fa-check-circle text-green'></i>" : "";
                                $check02 = $progress_id == 2 ? "&nbsp;<i class='fa fa-check-circle text-green'></i>" : "";
                                $check03 = $progress_id == 3 ? "&nbsp;<i class='fa fa-check-circle text-green'></i>" : "";

                                $onclick_01 = $progress_id == 1 ? "" : "onclick=\"fnTaskList.update(this, 1)\"";
                                $onclick_02 = $progress_id == 2 ? "" : "onclick=\"fnTaskList.update(this, 2)\"";
                                $onclick_03 = $progress_id == 3 ? "" : "onclick=\"fnTaskList.update(this, 3)\"";

                                $btnLink .= "
                                    <div class='input-group-btn'>
                                        <div class='btn btn-xs btn-flat btn-block $class dropdown-toggle$onclick_broke' data-toggle$onclick_broke='dropdown' aria-expanded='false' $btnDisabled><i class='$dataicon'></i>&nbsp;&nbsp;$mm$txt&nbsp;&nbsp;<i class='fa fa-caret-down'></i></div>
                                        <ul style='border: 1px gray solid;box-shadow: 1px 1px 1px 1px;' class='dropdown-menu'>
                                            <li><a data-id='$mm-$scriptID' $opt $onclick_01 href='javascript:void(0);'><i class='fa fa-hourglass-3'></i>&nbsp;Tertunda $check01 </a></li>
                                            <li><a data-id='$mm-$scriptID' $opt $onclick_02 href='javascript:void(0);'><i class='fa fa-send'></i>&nbsp;Diproses $check02 </a></li>
                                            <li><a data-id='$mm-$scriptID' $opt $onclick_03 href='javascript:void(0);'><i class='fa fa-star'></i>&nbsp;Selesai $check03 </a></li>
                                        </ul>
                                    </div>
                                ";
                            }
                        }
                        $btnLink .= "</div>";
                    }
                    else {

                        $btnLink = "";
                        $btnLink .= "<select id='selectpicker_$scriptID' class='form-control' data-stylex=\"btn btn-sm btn-primary\" data-live-searchx='true' multiplex>";
                        $btnLink .= "<option value=''>--pilih tindakan--</optin>";
                        foreach ($allowedEdit as $mm => $valm) {
                            $link = MODULPATH . "";
                            switch ($mm) {
                                case "create":
                                    $class = "btn-primary";
                                    $dataicon = "data-icon=\"glyphicon glyphicon-plus\"";
                                    $datastyle = "data-style=\"text-primary\"";
                                    $datacontent = "data-content=\"<span class='btn btn-xs btn-primary'><i class='glyphicon glyphicon-plus'></i> CREATE</span>\"";
                                    break;
                                case "update":
                                    $class = "btn-warning";
                                    $dataicon = "data-icon=\"glyphicon glyphicon-pencil\"";
                                    $datastyle = "data-style=\"text-primary\"";
                                    $datacontent = "data-content=\"<span class='btn btn-xs btn-warning'><i class='glyphicon glyphicon-pencil'></i> UPDATE</span>\"";
                                    break;
                                case "delete":
                                    $class = "btn-danger";
                                    $dataicon = "data-icon=\"glyphicon glyphicon-trash\"";
                                    $datastyle = "data-style=\"text-primary\"";
                                    $datacontent = "data-content=\"<span class='btn btn-xs btn-danger'><i class='glyphicon glyphicon-trash'></i> DELETE</span>\"";
                                    break;
                                default:
                                    $class = "";
                                    $dataicon = "data-icon=\"glyphicon glyphicon-eye-open\"";
                                    $datastyle = "data-style=\"text-primary\"";
                                    $datacontent = "data-content=\"<span class='btn btn-xs btn-info'><i class='glyphicon glyphicon-eye-open'></i> VIEW</span>\"";
                                    break;
                            }
                            $btnLink .= "<option data-value='$mm' value='$mm-$scriptID' $dataicon $datastyle $datacontent>$btnTnt</option>";
                        }
                        $btnLink .= "</select>";

                        $btnLink .= "\n<script>
                        top.$(\"#selectpicker_$scriptID\").selectpicker();\n
                        top.$(\"#selectpicker_$scriptID\").on('change', function(){
                            var selected = $(this).val();
                            var mode = selected.split(\"-\")[0];
                            switch(mode){
                                case 'create':
                                    swal('create', '{create} ' + selected, 'success');
                                    $(this).prop('selectedIndex','');
                                    $(this).val('');
                                    $(this).selectpicker('refresh');
                                break;
                                case 'update':
                                    $(this).prop('selectedIndex','');
                                    $(this).val('');
                                    $(this).selectpicker('refresh');
                                break;
                                case 'delete':
                                    swal('delete', '{delete} ' + selected, 'error');
                                    $(this).prop('selectedIndex','');
                                    $(this).val('');
                                    $(this).selectpicker('refresh');
                                break;
                                case 'view':
                                    swal('view', '{view} ' + selected, 'info');
                                    $(this).prop('selectedIndex','');
                                    $(this).val('');
                                    $(this).selectpicker('refresh');
                                break;
                            }
                        });\n
                        </script>\n";
                    }
                    $tmp["action"] = $btnLink;
                }
                $taskList[] = $tmp;
            }

            //AREA JS TASK LIST (fnTaskList)
            if ($type) {
                $scriptBottom = "";
                $scriptBottom .= "
                <script>
                            localStorage.imgBankUrl = '{}'
                            var imgBank = JSON.parse(localStorage.imgBankUrl);
                            function imgBankCtr(data,room_id='',mode){
                                var newImg = {}
                                switch(mode){
                                    case 'del':
                                        newImg = imgBank
                                        if(room_id*1>0){
                                            if(typeof imgBank[room_id] != 'undefined' ){
                                                delete newImg[room_id][data]
                                            }
                                        }
                                        else{
                                            if(typeof imgBank != 'undefined' ){
                                                delete newImg[data]
                                            }
                                        }
                                        localStorage.imgBankUrl = JSON.stringify(newImg);
                                    break;
                                    case 'add':
                                        newImg = imgBank;
                                        if(room_id*1>0){
                                            jQuery.each(data, function(a,b){
                                                if( typeof newImg[room_id] == 'undefined' ){
                                                    newImg[room_id] = {}
                                                }
                                                newImg[room_id][a] = b;
                                            })
                                        }
                                        else{
                                            jQuery.each(data, function(a,b){
                                                if( typeof newImg == 'undefined' ){
                                                    newImg = {}
                                                }
                                                newImg[a] = b;
                                            })
                                        }
                                        localStorage.imgBankUrl = JSON.stringify(newImg);
                                    break;
                                }
                            }

                            function formHistory(d,img) {

                                var html='',html_img='',html_produk='';
                                html_produk += '<div>'
                                html_produk += '<table class=\'table compact dataTable table-bordered display\'>'
                                html_produk += '<thead>'
                                html_produk += '<tr>'
                                html_produk += '<th>No</th>'
                                html_produk += '<th>Nama</th>'
                                html_produk += '<th>Satuan</th>'
                                html_produk += '<th>Qty</th>'
                                html_produk += '<th>Keterangan</th>'
                                html_produk += '</tr>'
                                html_produk += '</thead>'
                                html_produk += '<body>'
                                var pNum = 0;
                                jQuery.each(d, function(a, b){
                                    pNum++;
                                    html_produk += '<tr>'
                                    html_produk += '<td>'+pNum+'</td>'
                                    html_produk += '<td>'+ (b.jenis=='supplies' ? b.biaya_dasar_nama : b.produk_dasar_nama) +'</td>'
                                    html_produk += '<td>'+b.satuan+'</td>'
                                    html_produk += '<td>'+b.jml+'</td>'
                                    html_produk += '<td>'+b.progress_nama+'</td>'
                                    html_produk += '</tr>'
                                })
                                html_produk += '</body>'
                                html_produk += '</table>'
                                html_produk += '</div>'
                                html_img += \"<div class='text-center box-header'>\"
                                jQuery.each(img, function(a, b){
                                    var url='';
                                    url = b.cdn_url
                                    html_img += '<span style=\"padding: 15px;\"><img width=\"150\" onclick=\"showImageSwal(\'\',\''+url+'\')\" src='+url+'></span>'
                                })
                                html_img += '</div>'
                                html += html_img
                                html += html_produk
                                return ('<div class=\"container-fluid bg-gray\">'+html+'</div>');
                            }

                            function stockCheck(){
//                                console.log('stock check');
                                var arrQty = $('.form_qty')
                                var arrF = []
                                var tmpF = {}
//                                console.log('arrQty.length: ' + arrQty.length);
                                if(arrQty.length>0){
                                    var error = 0
                                    var errorText = ''
                                    jQuery.each(arrQty, function(a, b){
                                        var ids = $(b).attr('id').replace('form_qty_','');
                                        var byid = $(b).attr('by_id');
                                        var avail = $('#avail_'+byid+'_'+ids).val();
                                        var belum = $('#belum_'+ids).val();
                                        var current = $(b).attr('byvalue');
                                        var jmlreturn = $('#form_return_'+ids).val();
                                        var prd_nama = $('nama_'+ids).html();
                                        if(current*1 > avail*1 ){
                                            $(b).val(avail);
                                            errorText += 'id: '+ids+' | current: ' + current + ' and avail: ' + avail
                                            error++
                                        }
                                        if(jmlreturn*1 > avail*1 ){
                                            $('#form_return_'+ids).val(0).trigger('keyup');
                                            errorText += 'id: '+ids+' | current: ' + current + ' and avail: ' + avail
                                            error++
                                        }
                                    });
                                    if(error>0){
//                                        console.log(errorText);
                                        swal('stok diperbaiki<br><r>anda tidak bisa menggunakan bahan melebihi stok avail</r>')
                                        return true;
                                    }
                                    else{
//                                        console.log('return true')
                                        return true;
                                    }
                                }
                                else{
                                    swal('belum ada bahan dipilih')
                                }
                            }

                            function exeCommand(progress_id,id,prd_id,room_id=''){
                                var arrQty = room_id*1>0 ? $('#list_bahan_baku_'+room_id+' .form_qty') : $('#list_bahan_baku .form_qty')
                                var no_spk = $('#no_spk').html()
                                var data_fase = {no_spk,progress_id,room_id}
                                var arrData = {}
                                var data_produk = []
                                var tmpF = {}; //untuk barang unit (bukan supplies)
                                var json_img = imgBank

                                if(arrQty.length>0){
                                    jQuery.each(arrQty, function(a, b){
                                        var by_id = $(b).attr('by_id');
                                        var produk_dasar_id = $(b).attr('id').replace('form_qty_'+by_id+'_','');
                                        var produk_type = $(b).attr('prd_type');
                                        var stok_avail = room_id*1>0 ? $('#avail_'+room_id+'_'+produk_dasar_id).val() : $('#avail_'+by_id+'_'+produk_dasar_id).val();
                                        var used = room_id*1>0 ? $('#sudah_'+room_id+'_'+produk_dasar_id).val() : $('#sudah_'+by_id+'_'+produk_dasar_id).val();
//                                        var jml = $(b).val();
                                        var jml = $(b).attr('byvalue');
                                        var jml_return = $('#form_return_'+by_id+'_'+produk_dasar_id).val();
                                        var produk_dasar_nama = room_id*1>0 ? $('#nama_'+room_id+'_'+produk_dasar_id).html() : $('#nama_'+produk_dasar_id).html();

                                        //console.log('produk_dasar_id: ' + produk_dasar_id + ' || nama: ' + produk_dasar_nama + '  ||  ' + jml + ' || return: ' + jml_return);

                                        tmpF = {
                                            produk_dasar_id,
                                            produk_dasar_nama,
                                            stok_avail,
                                            used,
                                            jml,
                                            jml_return,
                                            produk_type,
                                            by_id
                                        }
                                        data_produk.push(tmpF);

                                    });

                                    arrData = {
                                        json_img,
                                        data_produk,
                                        data_fase
                                    }

                                    swal('mengirim data<br>Mohon tunggu...')
                                    swal.enableLoading()

                                    var debug = 0;
//console.log(arrData);
                                    if(debug!=1){
                                        $.ajax({
                                            url: '$linkUpdate'+id+'/'+prd_id+'/'+room_id,
                                            type: 'post',
                                            data: arrData,
                                            success: function(res){
                                                var datas = JSON.parse(res)
                                                if( datas.commit == 1 ){
                                                    if(datas.return == 1){

                                                        var dataCallback = datas.return_list;
                                                        var no_spk = datas.data.no_spk;
                                                        var project_id = datas.data.project_id;
                                                        var tasklist = datas.data.tasklist_id;
                                                        var fase_id = datas.data.fase_id;
                                                        var sub_fase_id = datas.data.sub_fase_id;
                                                        var debuger = 0;
                                                        var ajaxList = [];
                                                        jQuery.each(dataCallback, function(a,b){
                                                            if(a=='supplies'){
                                                                ajaxList.push('".base_url()."distribusisuppliesproject/_shoppingCart/reset/9834?json=1&debuger='+debuger);
                                                                ajaxList.push('".base_url()."distribusisuppliesproject/Create/index/9834?json=1&debuger='+debuger);
                                                                ajaxList.push('".base_url()."distribusisuppliesproject/_shoppingCart/fetchElement/9834/pihakID/MdlCabang/?key=-1&json=1&debuger='+debuger);
                                                                ajaxList.push('".base_url()."distribusisuppliesproject/_shoppingCart/fetchElement/9834/gudang/MdlGudang/?key=9&debuger='+debuger);
                                                                ajaxList.push('".base_url()."distribusisuppliesproject/_processPihak/selectProjek/9834/MdlProdukProject?id='+project_id+'&json=1&debuger='+debuger);
                                                                ajaxList.push('".base_url()."distribusisuppliesproject/_processPihak/selectProjekTasklist/9834/MdlTasklistProject?id='+tasklist+'&json=1&debuger='+debuger);
                                                                jQuery.each(dataCallback[a], function(aa){
                                                                    jQuery.each(dataCallback[a][aa], function(aaa,bbb){
                                                                        ajaxList.push('".base_url()."distribusisuppliesproject/_processSelectSupplies/selectProjectReturn/9834?biaya_id='+aa+'&spk='+no_spk+'&id='+bbb.produk_dasar_id+'&jml='+bbb.jml_return+'&newQty='+bbb.jml_return+'&json=1&debuger='+debuger);
                                                                        ajaxList.push('".base_url()."distribusisuppliesproject/_shoppingCart/buildValues/9834?json=1&debuger='+debuger);
                                                                    });
                                                                });
                                                                ajaxList.push('".base_url()."distribusisuppliesproject/Transaksi/validate/9834?json=1&debuger='+debuger);
                                                                ajaxList.push('".base_url()."distribusisuppliesproject/Create/save/9834?rawPrev=&json=1&debuger='+debuger);
                                                            }
                                                            else if(a=='produk'){
                                                                ajaxList.push('".base_url()."distribusifgproject/_shoppingCart/reset/9833?json=1&debuger='+debuger);
                                                                ajaxList.push('".base_url()."distribusifgproject/Create/index/9833?json=1&debuger='+debuger);
                                                                ajaxList.push('".base_url()."distribusifgproject/_processPihak/selectProjek/9833/MdlProdukProject?id='+project_id+'&json=1&debuger='+debuger);
                                                                ajaxList.push('".base_url()."distribusifgproject/_shoppingCart/fetchElement/9833/workOrderDetails/MdlProjectWorkOrder/?key='+fase_id+'&debuger='+debuger);
                                                                ajaxList.push('".base_url()."distribusifgproject/_shoppingCart/fetchElement/9833/workOrderSubDetails/MdlProjectWorkOrderSub/?key='+sub_fase_id+'&debuger='+debuger);
                                                                ajaxList.push('".base_url()."distribusifgproject/_processSelectProduct/selectProject/9833?spk='+no_spk+'&selector&id='+sub_fase_id+'&minValue=1&debuger='+debuger);
                                                                jQuery.each(dataCallback[a], function(aa, bb){
                                                                    ajaxList.push('".base_url()."distribusifgproject/_processSelectProduct/select/9833?id='+bb.produk_dasar_id+'&jml='+bb.jml_return+'&newQty='+bb.jml_return+'&json=1&debuger='+debuger);
                                                                    ajaxList.push('".base_url()."distribusifgproject/_shoppingCart/buildValues/9833?json=1&debuger='+debuger);
                                                                });
                                                                ajaxList.push('".base_url()."distribusifgproject/Transaksi/validate/9833?json=1&debuger='+debuger);
                                                                ajaxList.push('".base_url()."distribusifgproject/Create/save/9833?rawPrev=&json=1&debuger='+debuger);
                                                            }
                                                            else{
                                                                console.log(\"none\");
                                                            }
                                                        });

                                                            var ajaxSequentErr = []
                                                           function executeSequentially(urls) {
                                                                var promise = $.Deferred().resolve(); // Mulai dengan promise yang sudah selesai
                                                                urls.forEach(function(url) {
                                                                    promise = promise.then(function() {
                                                                        return $.ajax({
                                                                            url: url,
                                                                            method: 'GET',
                                                                            success: function(data) {
                                                                                //console.log('Success:', data);
                                                                                console.log('Success:');
                                                                            },
                                                                            error: function(error) {
                                                                                console.log('Error:', error);
                                                                                ajaxSequentErr.push(url);
                                                                            }
                                                                        });
                                                                    });
                                                                });
                                                                return promise;
                                                            }

                                                            // Eksekusi fungsi
                                                            executeSequentially(ajaxList).then(function() {
                                                                console.log('All ajaxList completed');
                                                                if(ajaxSequentErr.length>0){
                                                                    swal('warning', 'di posting dengan error', 'warning');
                                                                    top.$('#btnRefreshModal').click();
                                                                }
                                                                else{
                                                                    swal('berhasil', 'berhasil posting pengerjaan project', 'success');
                                                                    top.$('#btnRefreshModal').click();
                                                                }
                                                            });

//                                                        var ajaxString = ''
//                                                        if(ajaxList!=undefined){
//                                                            jQuery.each(ajaxList, function(a, b){
//                                                                if(ajaxString==''){
//                                                                    ajaxString += \"$.when($.ajax('\"+b+\"'))\";
//                                                                }
//                                                                else{
//                                                                    ajaxString += \".then(function(){ $.ajax('\"+b+\"') })\";
//                                                                }
//                                                            })
//                                                            ajaxString += \".fail(function(jqXHR, textStatus, errorThrown){ console.log(jqXHR), console.log(textStatus), console.log(errorThrown) })\"
//                                                            ajaxString += \".done(function(done){ top.swal('sukses'); top.$('#btnRefreshModal').click(); })\"
//                                                            eval(ajaxString);
                                                            //console.log(ajaxList);
//                                                        }
                                                    }
                                                    else{
                                                        swal('berhasil');
                                                        $('#btnRefreshModal').click();
                                                    }
                                                }
                                                else{
                                                    swal('gagal', 'progress gagal disimpan', 'error');
                                                    $('#btnRefreshModal').click();
                                                }
                                            }
                                        })
                                    }
                                    else{
                                        swal('DEBUG MODE, CEK CONSOLE BROWSER')
//                                        console.log(arrData);
                                    }

                                }
                                else{
                                    swal('belum ada bahan dipilih')
                                }
                            }

                            function imgCheckBtn(id,prd_id,room_id){
//                                console.log(imgBank);
                                if(Object.keys(imgBank).length>0){
                                    $('.btnWorkDone').off();
                                    $('.btnWorkDone').on('click', function(){
                                        var cekRoomId= $(this).attr('pids')
                                        if( stockCheck() ){
                                            exeCommand(2,id,prd_id,cekRoomId)
                                        }
                                    });
                                    $('.btnOnProcess').off();
                                    $('.btnOnProcess').on('click', function(){
                                        var cekRoomId= $(this).attr('pids')
                                        if( stockCheck() ){
                                            exeCommand(1,id,prd_id,cekRoomId)
                                        }
                                    });
                                    $('.btnWorkDone').attr('disabled', false);
                                    $('.btnOnProcess').attr('disabled', false);
                                    $('.text_pilih_status_'+room_id).html(' Pilih Status => ');
                                    $('.def-images').addClass('hidden');
                                }
                                else{
                                    $('.btnWorkDone').attr('disabled', 'disabled').off();
                                    $('.btnOnProcess').attr('disabled', 'disabled').off();
                                    $('.text_pilih_status_'+room_id).html(' Silahkan Pilih/Upload Photo Dulu ');
                                    $('.def-images').removeClass('hidden');
                                }
                            }

                            function loadEditSpk(id,prd_id){

                                $('.tab-table').html(\"<div class='text-center'><img width='100' src='//cdn.mayagrahakencana.com/assets/images/loading-loading-forever.gif'></div>\");

                                var table_bahan_baku={}

                                $.ajax({
                                    url: '$linkCreate'+id+'/'+prd_id,
                                    type: 'get',
                                    success: function(res){
                                        var result = JSON.parse(res);

                                        $('#btnRefreshModal').off();
                                        $('#btnRefreshModal').on('click', function(){
                                            $('#overlay').show()
                                            loadEditSpk(id,prd_id);
                                        });

                                        if( $('#no_spk') ){
                                            $('#no_spk').html( result.no_spk )
                                        }

                                        if( $('#nomer') ){
                                            $('#nomer').html( result.nomer )
                                        }

                                        if( $('#task_nama') ){
                                            $('#task_nama').html( result.task_nama )
                                        }

                                        if( $('#employee_nama') ){
                                            $('#employee_nama').html( result.tempTaskList[0].employee_nama )
                                        }

                                        if( $('#owner_nama') ){
                                            $('#owner_nama').html( result.tempTaskList[0].owner_nama )
                                        }

                                        if( result.task_progress*1 >= 100 ){
                                            $('.notice-info').hide();
                                        }

                                        var room = result.room;
                                        var komposisi_room = result.komposisi_room;
                                        var arrProdukUsedMainRoom = result.arrProdukUsedMainRoom;
                                        var arrProdukUsedRoom = result.arrProdukUsedRoom;
                                        var list_bahan = result.list_bahan
                                        var list_bahan_used = result.arrProdukUsedMain
                                        var list_bahan_dist = result.arrMaterialDist
                                        var tempSubTaskList = result.tempSubTaskList
                                        var subKomposisi = result.arrProdukUsed
                                        var dataJson = []
                                        var arrFData = {}
                                        var num=0

                                        var createTable = ''

                                        createTable += \"<div class='box box-warning box-solid'>\"
                                        createTable += \"<div class='box-header fa-2x text-bold text-blue'>DAFTAR PAKET MATERIAL</div>\"
                                        createTable += \"<div class='box-body'>\"

                                        createTable += \"<table id='list_bahan_baku' class='table dataTable compact display' width='100%'>\"
                                        createTable += \"<thead>\"
                                        createTable += \"<tr>\"
                                        createTable += \"<th>No</th>\"
                                        createTable += \"<th>Nama</th>\"
                                        createTable += \"<th>Satuan</th>\"
                                        createTable += \"<th>QTY PAKET</th>\"
                                        createTable += \"<th>Act</th>\"
                                        createTable += \"</tr>\"
                                        createTable += \"</thead>\"
                                        createTable += \"<tbody></tbody>\"
                                        createTable += \"<tfoot>\"
                                        createTable += \"</tfoot>\"
                                        createTable += \"</table>\"
                                        createTable += \"</div>\"
                                        createTable += \"</div>\"

                                        $('.tab-table').html(createTable);

                                        var dataJson=[]
                                        var num=0;
                                        jQuery.each(list_bahan, function(a, b){
                                            num++;
                                            var prd_dsr_id            = b.produk_dasar_id;
                                            var prd_jml               = b.jml;
                                            var prd_nama              = b.produk_dasar_nama;
                                            var prd_satuan            = b.satuan;
                                            var prd_used              = typeof list_bahan_used[prd_dsr_id] != 'undefined' ? (typeof list_bahan_used[prd_dsr_id] != 'undefined' ? list_bahan_used[prd_dsr_id]['jml'] : 0) : 0;
                                            var prd_avail             = prd_jml - prd_used;
                                            var prd_diambil           = typeof list_bahan_dist[prd_dsr_id] != 'undefined' ? (typeof list_bahan_dist[prd_dsr_id] != 'undefined' ? list_bahan_dist[prd_dsr_id]['jumlah'] : 0) : 0;
                                            var prd_bisa_dikerja      = (prd_diambil-prd_used)>0 ? prd_diambil-prd_used : 0;
                                            var prd_input             = prd_bisa_dikerja;
                                            var prd_lebih             = 0;
                                            var ket                   = '';

                                            dataJson.push([
                                                num,
                                                prd_nama,
                                                prd_satuan,
                                                prd_jml, //qty harus dikerjakan
                                                prd_diambil, //qty yang di ambil/distribusikan
                                                prd_used, //qty yg sudah dikerjakan
                                                prd_avail, //sisa yg belum dikerjakan
                                                prd_bisa_dikerja, //yg bisa dikerjakan
                                                prd_input, //default dikerjakan dari kebutuhan sesuai BOM, bukan apa yg telah di distribusikan
                                                prd_lebih,
                                                prd_dsr_id,
                                            ]);
                                        });

                                        var table_bahan_baku_single = $('#list_bahan_baku').DataTable({
                                                data: dataJson,
                                                paging: false,
                                                info: false,
                                                searching: false,
                                                order: [2, 'desc'],
                                                columnDefs:[
                                                {
                                                    targets: 0,
                                                    class: 'text-center',
                                                    width: '1%'
                                                },
                                                {
                                                    targets: 1,
                                                    width: '31%',
                                                    render: function(data, type, row, meta){
//                                                        console.log(row);
                                                        var input = ''
                                                        input += \"<span id='nama_\"+row['10']+\"'>\"+data+\"</span>\"
                                                        return input;
                                                    }
                                                },
                                                {
                                                    targets: 2,
                                                    width: '10%'
                                                },

                                                {
                                                    targets: 3, //QTY AKAN DIKERJAKAN
                                                    width: '10%',
                                                    type: 'anti-the',
                                                    class: 'text-center',
                                                    createdCell: function(td, cellData, rowData, row, col){
                                                         $(td).attr('data-order', cellData)
                                                    },
                                                    render: function(data, type, row, meta){
                                                        var input = ''

                                                        var hrus_dikerjakan = row['3'];
                                                        var sdh_diambil     = row['4'];
                                                        var sdh_dikerjakan  = row['5'];
                                                        var blm_dikerjakan  = row['6'];
                                                        var bisa_dikerjakan = row['7'];
                                                        var akan_dikerjakan = row['8'];
                                                        var kelebihan       = row['9'];

                                                        input += \"<input id='qty_before_\"+row['10']+\"' type='text' onfocus='this.select()' size='1' class='text-bold text-center bg-yellow ' value='\"+data+\"'>\"

                                                        return input;
                                                    }
                                                },
                                                {
                                                    targets: 4, //BUTTON ACTION
                                                    width: '10%',
                                                    render: function(data, type, row, meta){
                                                        var input = ''
                                                        input += \"<span id='\"+row['10']+\"' class='btn btn-xs btn-warning btn-flat btn_row_simpan_perubahan'><i class='fa fa-save'></i> simpan perubahan</span>\"
                                                        input += \"<span id='\"+row['10']+\"' class='btn btn-xs btn-danger btn-flat btn_row_remove'><i class='fa fa-trash'></i></span>\"
                                                        return input;
                                                    }
                                                },
                                                ],
                                                initComplete: function(settings, json){

                                                    $('#overlay').hide()

                                                    $('.btn_row_simpan_perubahan').on('click', delay_v2(function(){
                                                        var thisID     = $(this).attr('id');
                                                        var nama       = $('#nama_'+thisID).html();
                                                        var qty_before = $('#qty_before_'+thisID).prop('defaultValue');
                                                        var qty_after  = $('#qty_before_'+thisID).val();
                                                        var no_spk   = $('#no_spk').text();

                                                        if(qty_before!=qty_after){
                                                            swal({
                                                                title : 'yakin mengubah QTY ??',
                                                                html  :'Apakah Anda Yakin Mengubah QTY Produk <br> <b class=text-uppercase>'+nama+'</b>..??<br> sebelum <b>'+qty_before+'</b> menjadi <b>'+qty_after+'</b>',
                                                            })
                                                            .then((res)=>{
                                                                $.post('".base_url()."master_project/MasterData/editTask',
                                                                  {
                                                                    produk_dasar_id: thisID,
                                                                    no_spk: no_spk,
                                                                    qty_before: qty_before,
                                                                    val: qty_after,
                                                                    nama: nama,
                                                                  },
                                                                  function(data, status){
                                                                    var response = JSON.parse(data);
                                                                    if(response.status){
                                                                        $('#btnRefreshModal').click();
                                                                        swal('berhasil', 'perubahan qty berhasil', 'success');
                                                                    }
                                                                  });
                                                            });
                                                        }
                                                        else{
                                                            swal('anda belum merubah qty <br><b>'+nama+'</b>')
                                                        }
                                                    }, 100));

                                                    $('.btn_row_remove').on('click', delay_v2(function(){
                                                        var thisID     = $(this).attr('id');
                                                        var nama       = $('#nama_'+thisID).html();
                                                        var qty_before = $('#qty_before_'+thisID).prop('defaultValue');
                                                        var qty_after  = $('#qty_before_'+thisID).val();
                                                        var no_spk   = $('#no_spk').text();

                                                            swal({
                                                            title : 'yakin menghapus material ini??',
                                                            html  :'Apakah Anda Yakin untuk menghapus material ini <br> <b class=text-uppercase>'+nama+'</b>..??',
                                                            })
                                                            .then((res)=>{
                                                            $.post('".base_url()."master_project/MasterData/removeMaterialTask',
                                                              {
                                                                produk_dasar_id: thisID,
                                                                no_spk: no_spk,
                                                                qty_before: qty_before,
                                                                val: qty_after,
                                                                nama: nama,
                                                              },
                                                              function(data, status){
                                                                var response = JSON.parse(data);
                                                                if(response.status){
                                                                    $('#btnRefreshModal').click();
                                                                    swal('berhasil', 'material berhasil dihapus', 'success');
                                                                }
                                                              });
                                                        });

                                                    }, 100));
                                                }
                                            });
                                    }
                                })
                            }
                    </script>

                    <script>
                            function loadBahanTasklist(id,prd_id){

                                $('.tab-table').html(\"<div class='text-center'><img width='100' src='//cdn.mayagrahakencana.com/assets/images/loading-loading-forever.gif'></div>\");

                                var linkCreate      = '" . MODUL_PATH . get_class($this) . "/showCreateSubTasklist/';
                                var linkUploadPhoto = '" . MODUL_PATH . get_class($this) . "/uploadPhoto/';
                                var linkUpdateQc    = '" . MODUL_PATH . get_class($this) . "/exeSubTasklistQc/';
                                var prodID      = $prodID;
                                var base_url    = '".base_url()."';
                                var sub_fase_id = $sub_fase_id;
                                var appr_qc     = '$appr_qc';

                                var table_bahan_baku={}
                                var dropzone={}

                                $.ajax({
                                    url: linkCreate+id+'/'+prd_id,
                                    type: 'get',
                                    success: function(res){

                                        var result = JSON.parse(res);

                                        $('#btnRefreshModal').off();
                                        $('#btnRefreshModal').on('click', function(){
                                            $('#overlay').show()
                                            loadBahanTasklist(id,prd_id);
                                        });

                                        if( $('#no_spk') ){
                                            $('#no_spk').html( result.no_spk )
                                        }

                                        if( $('#nomer') ){
                                            $('#nomer').html( result.nomer )
                                        }

                                        if( $('#task_nama') ){
                                            $('#task_nama').html( result.task_nama )
                                        }

                                        if( $('#employee_nama') ){
                                            $('#employee_nama').html( result.tempTaskList[0].employee_nama )
                                        }

                                        if( $('#owner_nama') ){
                                            $('#owner_nama').html( result.tempTaskList[0].owner_nama )
                                        }

                                        if( result.task_progress*1 >= 100 ){
                                            $('.notice-info').hide();
                                        }

                                        var room = result.room;
                                        var komposisi_room = result.komposisi_room;
                                        var arrProdukUsedMainRoom = result.arrProdukUsedMainRoom;
                                        var arrProdukUsedRoom = result.arrProdukUsedRoom;
                                        var list_bahan = result.list_bahan
                                        var list_bahan_used = result.arrProdukUsedMain
                                        var list_bahan_dist = result.arrMaterialDist
                                        var tempSubTaskList = result.tempSubTaskList
                                        var subKomposisi = result.arrProdukUsed
                                        var dataJson = []
                                        var arrFData = {}
                                        var num=0


                                        var createTable = ''

                                            createTable += \"<div class='box box-warning box-solid'>\"
                                            createTable += \"<div class='box-header fa-2x text-bold text-blue'>DAFTAR BAHAN BAKU</div>\"
                                            createTable += \"<div class='box-body'>\"

//                                            createTable += \"<div class='table-responsive'>\"

                                            createTable += \"<table id='list_bahan_baku' class='table dataTable compact display' width='100%'>\"
                                            createTable += \"<thead>\"
                                            createTable += \"<tr>\"
                                            createTable += \"<th>No</th>\"
                                            createTable += \"<th>Nama</th>\"
                                            createTable += \"<th>Satuan</th>\"
                                            createTable += \"<th>QTY<br>HARUS<br>DIKERJAKAN</th>\"
                                            createTable += \"<th>QTY<br>SDH<br>DIAMBIL</th>\"
                                            createTable += \"<th>QTY<br>SDH<br>DIKERJAKAN</th>\"
                                            createTable += \"<th>QTY<br>BELUM<br>DIKERJAKAN</th>\"
                                            createTable += \"<th>QTY<br>BISA<br>DIKERJAKAN</th>\"
                                            createTable += \"<th>QTY<br>AKAN<br>DIKERJAKAN</th>\"
                                            createTable += \"<th>Kelebihan<br>Bahan</th>\"
                                            createTable += \"<th>Act</th>\"
                                            createTable += \"</tr>\"
                                            createTable += \"</thead>\"
                                            createTable += \"<tbody></tbody>\"
                                            createTable += \"<tfoot>\"
                                            createTable += \"<tr class='tr_upload'>\"
                                            createTable += \"<th style='padding-top: 25px !important;' colspan='11' class='no-padding'>\"
                                            createTable += \"<div class='text-center text-blue text-bold text-uppercase'>Upload Photo / Gambar Pengerjaan pada area dibawah ini!</div>\"
                                            createTable += \"<div id='upload-form' class='dropzone'>\"
                                            createTable += \"<img class='def-images' style='width: 10%;' src='//cdn.mayagrahakencana.com/images/uploadimg.png'>\"
                                            createTable += \"</div>\"
                                            createTable += \"</th>\"
                                            createTable += \"</tr>\"
                                            createTable += \"<tr class='tr_btnact'>\"
                                            createTable += \"<th colspan='11'>\"
                                            createTable += \"<div style='margin-top: 10px;margin-right: -25px;margin-left: -10px;' class='row'>\"
                                            createTable += \"<div class='col-md-3 no-padding'>\"
//                                            createTable += \"<span class='btn btn-sm btn-flat btn-default pull-left'><i class='fa fa-times'></i> Batal</span>\"
//                                            createTable += \"<span class='btn btn-sm btn-flat btn-default pull-left'><i class='fa fa-trash'></i> Delete</span>\"
                                            createTable += \"</div>\"
                                            createTable += \"<div class='col-md-4 text-right text-red blinkc text-bold' style='padding: 6px;'>\"
                                            createTable += \"<span class='text_pilih_status'> Silahkan Pilih/Upload Photo Dulu </span>\"
                                            createTable += \"</div>\"
                                            createTable += \"<div class='col-md-5'>\"
                                            <!-- createTable += \"<span pids='' id='btnOnProcess' disabled class='btn btn-sm btn-flat btn-warning pull-right btnOnProcess'><i class='fa fa-fire'></i> Dalam Pengerjaan</span>\" -->
                                            createTable += \"<span pids='' id='btnWorkDone' disabled class='btn btn-sm btn-flat btn-success pull-right btnWorkDone'><i class='fa fa-check-circle'></i> Selesai Dikerjakan</span>\"
                                            createTable += \"</div>\"
                                            createTable += \"</div>\"
                                            createTable += \"</th>\"
                                            createTable += \"</tr>\"
                                            createTable += \"</tfoot>\"
                                            createTable += \"</table>\"
//                                            createTable += \"</div>\"

                                            createTable += \"</div>\"

                                            createTable += \"<div class='box-footer info-task-lebih hidden'>\"
                                            createTable += \"<div class='alert bg-danger text-bold text-red text-center fa-2x'> PENGERJAAN SUDAH LENGKAP, NAMUN ANDA PERLU RETURN BARANG LEBIH / SISA PENGERJAAN SEBELUM MEMASUKI TAHAP QUALITY CONTROL.</div>\"
                                            createTable += \"<div class='text-center'><span class='btn btn-lg btn_row_cancel btn-danger'><i class='fa fa-send'></i> KLIK DISINI UNTUK RETURN BAHAN SISA </span></div>\"
                                            createTable += \"</div>\"

                                            createTable += \"<div class='box-footer info-task-done hidden'>\"
                                            createTable += \"<div class='alert bg-success text-bold text-green text-center fa-2x'>TASKLIST/WO INI SUDAH SELESAI DI KERJAKAN SELURUHNYA.<br>ANDA BISA MELAKUKAN CHECK FISIK PENGERJAAN, KEMUDIAN MELAKUKAN TINDAK LANJUT APPROVAL DENGAN KLIK TOMBOL HIJAU DIBAWAH INI.</div>\"
                                            createTable += \"<div class='text-center'><span data-id='\"+id+\"' data-produk-id='\"+prd_id+\"' class='btn btn-lg btn-info btn-flatx text-bold btn-qc-done'><i class='fa fa-send'></i> SELESAI PENGECEKAN DAN SETUJUI </span></div>\"
                                            createTable += \"</div>\"

                                            createTable += \"<div class='box-footer info-task-qc-onprocess hidden'>\"
                                            createTable += \"<div class='alert bg-success text-bold text-green text-center fa-2x'>TASKLIST/WO INI SUDAH SELESAI DI KERJAKAN SELURUHNYA.<br>AKAN DILAKUKAN CHECK FISIK PENGERJAAN.</div>\"
                                            createTable += \"</div>\"

                                            createTable += \"</div>\"


                                            $('.tab-table').html(createTable);

                                            var dataJson=[]
                                            var num=0;
                                            jQuery.each(list_bahan, function(a, b){
                                                num++;
                                                var prd_dsr_id       = b.produk_dasar_id;
                                                var biy_dsr_id       = b.biy_dasar_id;
                                                var prd_jml          = b.jml_total*1>0 ? b.jml_total*1 : b.jml*1;
                                                var prd_nama         = b.produk_dasar_nama;
                                                var prd_satuan       = b.satuan;
                                                var prd_jenis        = b.jenis;
                                                var prd_biaya        = b.biaya_nama!=undefined ? b.biaya_nama : 'produk unit';
                                                var prd_biaya_id     = b.biaya_nama!=undefined ? b.biaya_id : 0;
                                                var prd_return       = typeof b.jml_return != 'undefined' ? b.jml_return : 0;



                                                if(b.jenis=='supplies'){
                                                    var prd_used         = typeof list_bahan_used[b.biaya_id+'_'+prd_dsr_id] != 'undefined' ? (typeof list_bahan_used[b.biaya_id+'_'+prd_dsr_id] != 'undefined' ? list_bahan_used[b.biaya_id+'_'+prd_dsr_id]['jml'] : 0) : 0;
                                                }
                                                else{
                                                    var prd_used         = typeof list_bahan_used['produk_'+prd_dsr_id] != 'undefined' ? (typeof list_bahan_used['produk_'+prd_dsr_id] != 'undefined' ? list_bahan_used['produk_'+prd_dsr_id]['jml'] : 0) : 0;
                                                }

                                                var prd_avail        = prd_jml - prd_used;

//console.log('prd_nama: ' + prd_nama + ' ==> prd_jenis: ' + prd_jenis + ' | prd_avail: ' + prd_avail);
//console.log('b.biaya_id: ' + b.biaya_id + ' ==> prd_jenis: ' + prd_jenis + ' | prd_avail: ' + prd_avail);

                                                if(b.jenis=='supplies'){
                                                    var prd_diambil      = 0;
                                                    if(typeof list_bahan_dist['supplies'] != 'undefined'){
                                                        if(typeof list_bahan_dist['supplies'][b.biaya_id] != 'undefined'){
                                                            if(typeof list_bahan_dist['supplies'][b.biaya_id][prd_dsr_id] != 'undefined'){
                                                                prd_diambil      = typeof list_bahan_dist['supplies'][b.biaya_id][prd_dsr_id] != 'undefined' ? (typeof list_bahan_dist['supplies'][b.biaya_id][prd_dsr_id] != 'undefined' ? list_bahan_dist['supplies'][b.biaya_id][prd_dsr_id]['jumlah'] : 0) : 0;
                                                            }
                                                        }
                                                    }
                                                }
                                                else{
                                                    var prd_diambil      = 0;
                                                    if(typeof list_bahan_dist['produk'] != 'undefined'){
                                                        if(typeof list_bahan_dist['produk'][prd_dsr_id] != 'undefined'){
                                                            prd_diambil      = typeof list_bahan_dist['produk'][prd_dsr_id] != 'undefined' ? (typeof list_bahan_dist['produk'][prd_dsr_id] != 'undefined' ? list_bahan_dist['produk'][prd_dsr_id]['jumlah'] : 0) : 0;
                                                        }
                                                    }
                                                }

                                                var prd_bisa_dikerja = (prd_diambil-prd_used-prd_return)>0 ? prd_diambil-prd_used-prd_return : 0;


//console.log(prd_nama + ' - ' + 'prd_diambil: '+prd_diambil);
//console.log(prd_nama + ' - ' + 'prd_jml: '+prd_jml);
//console.log(prd_nama + ' - ' + 'prd_used: '+prd_used);
//console.log(prd_nama + ' - ' + 'return: '+prd_return);
//console.log(prd_nama + ' - ' + 'prd_avail: '+prd_avail);
//console.log(prd_nama + ' - ' + 'prd_bisa_dikerja: '+prd_bisa_dikerja);
//console.log('==========================================');

                                                var prd_input        = prd_bisa_dikerja;
                                                var prd_lebih        = 0;
                                                var ket              = '';

                                                dataJson.push([
                                                    num,
                                                    prd_nama,
                                                    prd_biaya,
                                                    prd_jml, //qty harus dikerjakan
                                                    prd_diambil, //qty yang di ambil/distribusikan
                                                    prd_used, //qty yg sudah dikerjakan
                                                    prd_avail, //sisa yg belum dikerjakan
                                                    prd_bisa_dikerja, //yg bisa dikerjakan
                                                    prd_input, //default dikerjakan dari kebutuhan sesuai BOM, bukan apa yg telah di distribusikan
                                                    prd_lebih,
                                                    prd_dsr_id,
                                                    prd_return, //return
                                                    prd_jenis, //row[12]
                                                    prd_satuan,
                                                    prd_biaya_id,
                                                ]);
                                            });

                                        var groupColumn = 2;
                                        var table_bahan_baku_single = $('#list_bahan_baku').DataTable({
                                                data: dataJson,
                                                paging: false,
                                                info: false,
                                                searching: false,
                                                order: [[groupColumn, 'asc']],
                                                columnDefs:[
                                                {
                                                    targets: 0,
                                                    class: 'text-center',
                                                    width: '1%'
                                                },
                                                {
                                                    targets: 1,
                                                    width: '30%',
                                                    orderable: false,
                                                    render: function(data, type, row, meta){
                                                        var input = ''
                                                        var biaya_nama = row['13'] != 'null' ? row['13'] : 'unit produk';
                                                        var add = row['12']=='supplies' ? '<r>(supplies)</r> ' : '';
                                                        var title_biaya = row['13'] != 'null' ? '(supplies untuk '+biaya_nama+')' : biaya_nama + ' ****';
                                                        input += \"<span by='\"+row['13']+\"' title='\"+title_biaya+\"' id='nama_\"+row['10']+\"'>\"+add+ '&nbsp;' +data+\"</span>\"
                                                        return input;
                                                    }
                                                },
                                                {
                                                    targets: 2,
                                                    visible: false,
                                                    width: '5%'
                                                },
                                                {
                                                    targets: 3, //QTY HRUS DIKERJAKAN (BOM)
                                                    width: '3%',
                                                    render: function(data, type, row, meta){
                                                        var input = ''
                                                        input += \"<input disabled type='text' size='1' class='text-bold text-center' value='\"+data+\"'>\"
                                                        return input;
                                                    }
                                                },
                                                {
                                                    targets: 4, //QTY SDH DIAMBIL
                                                    width: '3%',
                                                    render: function(data, type, row, meta){
                                                        var input = ''

                                                        if(row['12'] == 'biaya'){
                                                            //console.error(row['12']);
                                                        }
                                                        else{

                                                            if(data*1>row['3']*1 || data*1<row['3']*1){
                                                                input += \"<input disabled by_id='\"+row['14']+\"'  id='avail_\"+row['14']+'_'+row['10']+\"' type='text' size='1' class='text-bold text-center text-bold text-red' value='\"+data+\"'>\"
                                                            }
                                                            else{
                                                                input += \"<input disabled by_id='\"+row['14']+\"'  id='avail_\"+row['14']+'_'+row['10']+\"' type='text' size='1' class='text-bold text-center' value='\"+data+\"'>\"
                                                            }
                                                        }
                                                        return input;
                                                    }
                                                },
                                                {
                                                    targets: 5, //QTY SUDH DIKERJAKAN
                                                    width: '3%',
                                                    visible: true,
                                                    render: function(data, type, row, meta){
                                                        var input = ''

                                                        if(row['12'] == 'biaya'){
                                                            console.error(row['12']);
                                                        }
                                                        else{
                                                            if(data*1>row['3']*1 || data*1<row['3']*1){
                                                                input += \"<input id='sudah_\"+row['10']+\"' disabled type='text' size='1' class='text-bold text-center text-bold text-red' value='\"+data+\"'>\"
                                                            }
                                                            else{
                                                                input += \"<input id='sudah_\"+row['10']+\"' disabled type='text' size='1' class='text-bold text-center' value='\"+data+\"'>\"
                                                            }
                                                        }

                                                        return input;
                                                    }
                                                },
                                                {
                                                    targets: 6, //QTY BELUM DIKERJAKAN
                                                    width: '3%',
                                                    visible: false,
                                                    render: function(data, type, row, meta){
                                                        var input = ''
                                                        input += \"<input id='belum_\"+row['10']+\"' disabled type='text' size='1' class='text-bold text-center' value='\"+(data*1>0 ? data*1 : 0)+\"'>\"
                                                        return input;
                                                    }
                                                },
                                                {
                                                    targets: 7, //QTY BISA DIKERJAKAN
                                                    width: '3%',
                                                    visible: false,
                                                    render: function(data, type, row, meta){
                                                        var input = ''
                                                        input += \"<input id='avail_\"+row['10']+\"' disabled type='text' size='1' class='text-bold text-center' value='\"+data+\"'>\"
                                                        return input;
                                                    }
                                                },
                                                {
                                                    targets: 8, //QTY AKAN DIKERJAKAN
                                                    width: '3%',
                                                    visible: false,
                                                    type: 'anti-the',
                                                    class: 'text-center',
                                                    createdCell: function(td, cellData, rowData, row, col){
                                                         $(td).attr('data-order', cellData)
                                                    },
                                                    render: function(data, type, row, meta){
                                                        var input = ''

                                                        var hrus_dikerjakan = row['3'];
                                                        var sdh_diambil     = row['4'];
                                                        var sdh_dikerjakan  = row['5'];
                                                        var blm_dikerjakan  = row['6'];
                                                        var bisa_dikerjakan = row['7'];
                                                        var akan_dikerjakan = row['8'];
                                                        var kelebihan       = row['9'];

                                                        if(row['12'] == 'biaya'){
                                                            console.error(row['12']);
                                                        }
                                                        else{
                                                            if( row['4']*1>0 && row['4']*1 > 0 && row['6']*1>0 ){
                                                                input += \"<input id='\"+row['10']+\"' type='text' onfocus='this.select()' size='1' class='text-bold text-center bg-yellow ' value='\"+data+\"'>\"
                                                            }
                                                            else if( sdh_dikerjakan >= hrus_dikerjakan && bisa_dikerjakan*1 > 0 ){
                                                                input += \"<input id='\"+row['10']+\"' type='text' onfocus='this.select()' size='1' class='text-bold text-center bg-yellow ' value='\"+data+\"'>\"
                                                            }
                                                            else if( sdh_dikerjakan >= hrus_dikerjakan && bisa_dikerjakan*1 == 0 ){
                                                                input += \"<input disabled type='text' size='10' class='text-bold text-center text-green' value='selesai'>\"
                                                            }
                                                            else{
                                                                input += \"<input disabled type='text' size='10' class='text-bold text-center text-red' value='belum di ambil*'>\"
                                                            }
                                                        }


                                                        return input;
                                                    }
                                                },
                                                {
                                                    targets: 9, //QTY KELEBIHAN
                                                    width: '5%',
                                                    class: 'text-center',
                                                    render: function(data, type, row, meta){
                                                        var log_arr         = {};
                                                        var input           = ''
                                                        var nama            = row['1'];
                                                        var jenis           = row['2'];
                                                        var hrus_dikerjakan = row['3'];
                                                        var sdh_diambil     = row['4'];
                                                        var sdh_dikerjakan  = row['5'];
                                                        var blm_dikerjakan  = row['6']; // return
                                                        var bisa_dikerjakan = row['7'];
                                                        var akan_dikerjakan = row['8'];
                                                        var kelebihan       = row['9'];
                                                        var sdh_direturn    = row['11']; // return
                                                        var biy             = row['14'];

                                                        log_arr = {
                                                            nama,
                                                            jenis,
                                                            biy,
                                                            hrus_dikerjakan,
                                                            sdh_diambil,
                                                            sdh_dikerjakan,
                                                            blm_dikerjakan,
                                                            sdh_direturn,
                                                            bisa_dikerjakan,
                                                            akan_dikerjakan,
                                                            kelebihan
                                                        }

//                                                        console.log(log_arr);

                                                        if(row['12'] == 'biaya'){
                                                            console.error(row);
//                                                            input += \"<input ##1 byvalue='\"+row['3']+\"' by_id='\"+row['14']+\"' prd_type='\"+row['12']+\"' id='form_qty_\"+row['10']+\"' class='hidden form_qty' value='\"+row['3']+\"'>\"
                                                        }
                                                        else{
                                                            //ini area hidden
                                                            //penampung pekerjaan
                                                            if( sdh_diambil*1>0 && sdh_diambil*1 > 0 ){
                                                                    //input += \"<input ##0 byvalue='\"+bisa_dikerjakan+\"' by_id='\"+row['14']+\"' prd_type='\"+row['12']+\"' pid=\"+row['10']+\" id='avail_\"+row['10']+\"' class='hidden' value='\"+bisa_dikerjakan+\"'>\"
                                                                if(akan_dikerjakan*1>0){
                                                                    input += \"<input ##1 byvalue='\"+akan_dikerjakan+\"' by_id='\"+row['14']+\"' prd_type='\"+row['12']+\"' id='form_qty_\"+row['14']+\"_\"+row['10']+\"' class='hidden form_qty' value='\"+akan_dikerjakan+\"'>\"
                                                                }
                                                            }
                                                            else if( sdh_dikerjakan >= hrus_dikerjakan && bisa_dikerjakan*1 > 0 ){
                                                                input += \"<input ##2 by_id='\"+row['14']+\"' prd_type='\"+row['12']+\"' id='form_qty_\"+row['10']+\"' type='text' onfocus='this.select()' size='1' class='hidden text-bold text-center bg-yellow form_qty' value='\"+akan_dikerjakan+\"'>\"
                                                            }
                                                            else if( sdh_dikerjakan >= hrus_dikerjakan && bisa_dikerjakan*1 == 0 ){
                                                                input += \"<input ##3 by_id='\"+row['14']+\"' prd_type='\"+row['12']+\"' class='hidden form_qty_selesai' value='selesai'>\"
                                                            }
                                                            else{
                                                                input += \"<input ##4 by_id='\"+row['14']+\"' prd_type='\"+row['12']+\"'  class='hidden' value='belum di ambil'>\"
                                                            }

                                                            input += \"<input ##5 by_id='\"+row['14']+\"' prd_type='\"+row['12']+\"' id='sudah_\"+row['14']+'_'+row['10']+\"' pid='\"+row['10']+\"' class='hidden qty_sudah' value='\"+sdh_dikerjakan+\"'>\"

                                                            //ini area hidden

                                                            //return
                                                            if( sdh_diambil*1>0 && sdh_diambil*1 > 0 && blm_dikerjakan*1>0 && (blm_dikerjakan*1 != sdh_direturn*1) ){
                                                                if(bisa_dikerjakan*1>0){
                                                                    input += \"<input ##6 by_id='\"+row['14']+\"' prd_type='\"+row['12']+\"' id='form_return_\"+row['14']+\"_\"+row['10']+\"' pid='\"+row['10']+\"' type='text' onfocus='this.select()' size='1' class='text-bold text-center bg-yellow form_return' value='\"+data+\"'>\"
                                                                }
                                                                else{
                                                                    input += \"<input ##6 by_id='\"+row['14']+\"' prd_type='\"+row['12']+\"' disabled type='text' size='10' class='text-bold text-center text-red' value='stok kurang'>\"
                                                                }
                                                            }
                                                            else if( sdh_dikerjakan*1 >= hrus_dikerjakan*1 && bisa_dikerjakan*1 > 0 && (blm_dikerjakan*1 != sdh_direturn*1) ){
                                                                input += \"<input ##7 by_id='\"+row['14']+\"' prd_type='\"+row['12']+\"' id='form_return_\"+row['10']+\"' pid='\"+row['10']+\"' type='text' onfocus='this.select()' size='1' class='text-bold text-center bg-yellow form_return' value='\"+data+\"'>\"
                                                            }
                                                            else if( sdh_dikerjakan >= hrus_dikerjakan && bisa_dikerjakan*1 == 0 && sdh_direturn*1 == 0 ){
                                                                input += \"<input ##8 by_id='\"+row['14']+\"' prd_type='\"+row['12']+\"' disabled type='text' size='10' class='text-bold text-center text-green form_qty_selesai' value='selesai'>\"
                                                            }
                                                            else if( sdh_direturn*1 > 0 ){
                                                                input += \"<span ##9 by_id='\"+row['14']+\"' prd_type='\"+row['12']+\"' class='text-bold text-center text-orange'>(diretur = \"+sdh_direturn+\")<br>(selesai)</span>\"
                                                            }
                                                            else{
                                                                input += \"<input ##10 by_id='\"+row['14']+\"' prd_type='\"+row['12']+\"' disabled type='text' size='10' class='text-bold text-center text-red' value='belum di ambil'>\"
                                                            }
                                                        }

                                                        return input;
                                                    }
                                                },
                                                {
                                                    targets: 10, //BUTTON ACTION
                                                    width: '3%',
                                                    visible: false,
                                                    render: function(data, type, row, meta){
                                                        var input = ''
                                                        //jika harus dikerjakan
                                                        if( row['3']*1>0 && row['3']*1 <= row['5']*1 ){
                                                            input += \"<span disabled class='btn btn-xs btn-success'><i class='fa fa-check'></i></span>\"
                                                        }
                                                        else{
                                                            input += \"<span id='\"+data+\"' class='btn btn-xs btn-danger btn-flat btn_row_remove'><i class='fa fa-remove'></i></span>\"
                                                        }

                                                        var qty_lebih   = row[9];
                                                        var qty_bom     = row[3];
                                                        var qty_used    = row[5];

                                                        if( qty_lebih*1 > 0 ){
                                                            input += \"<span id='\"+data+\"' class='btn btn-xs btn-warning btn-flat btn_row_cancel'><i class='fa fa-rotate-right'></i></span>\"
                                                        }

                                                        return input;
                                                    }
                                                },
                                                ],
                                                initComplete: function(settings, json){
                                                    arrQty = settings.aoData
                                                    //check sudah selesai semua atau belum
                                                    var arrQty = $('#list_bahan_baku tbody tr:not(.group)')
                                                    var selesai = 0;
                                                    var tmpSelesai = 0;
                                                    var hrsdikerjakan=0;
                                                    var sdhdikerjakan=0;
                                                    var sdhdireturn=0;
                                                    var qtylebihmasihbisadikerjakan=0;
//                                                    console.error(arrQty);
                                                    jQuery.each(arrQty, function(a,b){
                                                        var rows = settings.aoData[a]._aData;
//                                                        console.error(rows);
                                                        hrsdikerjakan = rows[3] * 1;
                                                        sdhdikerjakan = rows[5] * 1;
                                                        sdhdireturn = rows[11] * 1;
                                                        qtylebihmasihbisadikerjakan += rows[7] * 1;
                                                        if( hrsdikerjakan <= ((sdhdikerjakan*1) + (sdhdireturn*1)) ){
                                                            tmpSelesai++;
                                                        }
//                                                        console.log('hrsdikerjakan: ' + hrsdikerjakan + ' || sdhdikerjakan+sdhdireturn: ' + ((sdhdikerjakan*1) + (sdhdireturn*1)));
                                                    });

                                                    if(tmpSelesai==arrQty.length){
                                                        selesai = 1
                                                    }

                                                    $('.form_qty').on('keyup', delay_v2(function(){
                                                        stockCheck();
                                                        console.log('$(.form_qty) ##2');
                                                    }, 400));

                                                    var wajib_img = 0;
                                                    if(!wajib_img){
                                                        $('.btnWorkDone').off();
                                                        $('.btnWorkDone').on('click', function(){
                                                            var cekRoomId= $(this).attr('pids')
                                                            if( stockCheck() ){
                                                                exeCommand(2,id,prd_id,cekRoomId)
                                                            }
                                                        });
                                                        $('.btnWorkDone').attr('disabled', false);
//                                                        console.log('mode tidak wajib upload images');
                                                    }
                                                    else{
//                                                        console.log('mode wajib upload images');
                                                    }

                                                    $('.form_return').on('keyup', function(){
                                                        var value = $(this).val();
                                                        var pid = $(this).attr('pid');
                                                        var byid = $(this).attr('by_id');
                                                        var sdh_dkrjkan = $('#sudah_'+byid+'_'+pid).val();
                                                        var avail_stok = ($('#avail_'+byid+'_'+pid).val()*1) - (sdh_dkrjkan*1);

//                                                        console.log('sdh_dkrjkan: '+ sdh_dkrjkan + ' | pid: '+ pid + ' | avail_stok: '+ avail_stok);
//                                                        console.log('value: '+ value + ' | pid: '+ pid + ' | avail_stok: '+ avail_stok);

                                                        if(value*1>avail_stok*1){
                                                            $(this).val(0);
//                                                            console.error('form_qty '+ pid +' injected to ' + $('#form_qty_'+pid).val() );
                                                            swal('return tidak boleh lebih besar dari bom/avail stok')
                                                        }
                                                        else{
                                                            $('#form_qty_'+byid+'_'+pid).val(avail_stok*1-value*1);
                                                            $('#form_qty_'+byid+'_'+pid).attr('byvalue', avail_stok*1-value*1);
//                                                            console.log('form_qty '+ pid +' injected to ' + $('#form_qty_'+pid).val() );
                                                        }
                                                    });

                                                    $('.btn_row_remove').on('click', delay_v2(function(){
                                                        var thisID = $(this).attr('id')
                                                        var nama = $('#nama_'+thisID).html()
                                                        swal({
                                                            title: 'yakin hapus ??',
                                                            html:'Apakah Anda Yakin Menghapus Produk <br> <b class=text-uppercase>'+nama+'</b>..??',
                                                        })
                                                        .then((res)=>{
                                                            $(this).parent().parent().remove();
                                                        });
                                                    }, 100));

                                                    $('.btn_row_cancel').on('click', delay_v2(function(){
                                                        swal({
                                                            title: 'Proses Pengembalian Produk',
                                                            html:'Anda akan dibawa kehalaman Return Produk, lanjutkan??',
                                                        })
                                                        .then((res)=>{
                                                            var evalues = {
                                                                0:base_url+'distribusifgproject/_shoppingCart/reset/9833',
                                                                //1:base_url+'distribusifgproject/_processPihak/select/9833/MdlCabang?id=-1',
                                                                1:base_url+'distribusifgproject/_processPihak/selectProjek/9833/MdlProdukProject?id='+prodID,
                                                                2:base_url+'distribusifgproject/_processSelectProduct/selectProject/9833?spk='+top.$('#no_spk').text()+'&selector&id='+sub_fase_id+'&minValue=1'}
                                                                localStorage.setItem('evalues', JSON.stringify(evalues));
                                                            window.open(base_url+'distribusifgproject/Create/index/9833?gr=dW5kZWZpbmU&topGr=9833&md=transaksi','')
                                                        });
                                                    }, 100));

                                                    if( !selesai || qtylebihmasihbisadikerjakan*1>0 ){
                                                        setTimeout(function(){
                                                            Dropzone.autoDiscover = false;
                                                            var dropzone = new Dropzone('#upload-form', {
                                                                acceptedFiles: '.jpg,.png,.jpeg',
                                                                addRemoveLinks: true,
                                                                maxFilesize: 10,
                                                                parallelUploads: 2,
                                                                thumbnailHeight: 120,
                                                                thumbnailWidth: 120,
                                                                filesizeBase: 1000,
                                                                dictFileTooBig: 'File terlalu besar ({{filesize}}MiB). Max ukuran: {{maxFilesize}}MiB.',
                                                                forceFallback: false, //untuk switch ke metode upload JADUL
                                                                dictDefaultMessage: \"Klik atau Drag Drop File di sini untuk Upload\",
                                                                dictRemoveFile: 'hapus',
                                                                dictRemoveFileConfirmation: 'Anda yakin menghapus photo ini..??',
                                                                dictInvalidFileType: \"Gagal Upload, Pastikan file berjenis gambar.\",
                                                                url: linkUploadPhoto+id+'/'+prd_id,
                                                                thumbnail: function(file, dataUrl) {
                                                                    if (file.previewElement) {
                                                                      file.previewElement.classList.remove('dz-file-preview');
                                                                      var images = file.previewElement.querySelectorAll('[data-dz-thumbnail]');
                                                                      for (var i = 0; i < images.length; i++) {
                                                                        var thumbnailElement = images[i];
                                                                        thumbnailElement.alt = file.name;
                                                                        thumbnailElement.src = dataUrl;
                                                                      }
                                                                      setTimeout(function() { file.previewElement.classList.add('dz-image-preview'); }, 1);
                                                                    }
                                                                },
                                                                params(files, xhr, chunk) {
                                                                    $('.def-images').addClass('hidden');
                                                                    var uuid = files[0]['upload']['uuid'];
                                                                    var tmpImg = {}
                                                                    xhr.onreadystatechange = function() {
                                                                        if (xhr.readyState == XMLHttpRequest.DONE) {
                                                                            var callBack = JSON.parse(xhr.responseText)

//                                                                            console.log('callBack: ');
//                                                                            console.log(callBack);
                                                                            var id = callBack.id
                                                                            var produk_id = callBack.produk_id
                                                                            var cdn_url = callBack.uploaded.full_url
                                                                            tmpImg[uuid] = {
                                                                                uuid,
                                                                                id,
                                                                                produk_id,
                                                                                cdn_url
                                                                            }
                                                                            imgBankCtr(tmpImg,'','add')
                                                                            imgCheckBtn(id,prd_id,'');
                                                                        }
                                                                    }
                                                                },
                                                                removedfile(file) {
                                                                    if (file.previewElement != null && file.previewElement.parentNode != null) {
                                                                        file.previewElement.parentNode.removeChild(file.previewElement);
                                                                    }
                                                                    var removeUuid = file.upload.uuid;
                                                                    imgBankCtr(removeUuid,'','del')
                                                                    imgCheckBtn(id,prd_id,'');
                                                                },
                                                                error(file, message) {
                                                                    if (file.previewElement) {
                                                                      file.previewElement.classList.add('dz-error');
                                                                      if (typeof message !== 'string' && message.error) {
                                                                        message = message.error;
                                                                      }
                                                                      for (let node of file.previewElement.querySelectorAll(
                                                                        '[data-dz-errormessage]'
                                                                      )) {
                                                                        node.textContent = message;
                                                                      }
                                                                    }
                                                                },
                                                            });
                                                        }, 800)
                                                        $('#overlay').hide();
                                                    }
                                                    else{
                                                        $('.tr_upload').addClass('hidden');
                                                        $('.tr_btnact').addClass('hidden');
                                                        $('.check-done').removeClass('hidden');

                                                        if(qtylebihmasihbisadikerjakan*1>0){
                                                            $('.info-task-lebih').removeClass('hidden');
                                                        }
                                                        else{
                                                            if(appr_qc!='hidden'){
                                                        $('.info-task-done').removeClass('hidden');
                                                        }
                                                            else{
                                                                $('.info-task-qc-onprocess').removeClass('hidden');
                                                        }
                                                        }
                                                        $('#overlay').hide();
                                                    }
                                                },
                                                drawCallback: function (settings) {
                                                    var api = this.api();
                                                    var rows = api.rows({ page: 'current' }).nodes();
                                                    var last = null;
                                                    api.column(groupColumn, { page: 'current' })
                                                    .data()
                                                    .each(function (group, i) {
                                                        if (last !== group) {
                                                            $(rows)
                                                                .eq(i)
                                                                .before(
                                                                    '<tr class=\"group\"><td colspan=\"6\">' + group + '</td></tr>'
                                                                );
                                                            last = group;
                                                        }
                                                    });
                                                }
                                            });
//                                        }

                                        var dataUsedJson = []
                                        var arrUsedFData = {}
                                        var no=0;
                                        jQuery.each(tempSubTaskList, function(a, b){
                                            no++
                                            var tmpImg    = JSON.parse(b.json_img)
                                            var prd_nama  = b.nama
                                            var no_sub    = b.no_sub
                                            var tanggal   = b.dtime
                                            var ket       = b.progress_nama
                                            var room_id   = b.room_id
                                            var json_img  = room_id*1>0 ? JSON.stringify(tmpImg[room_id]) : JSON.stringify(tmpImg)
                                            var room_nama = b.room_nama
                                            var pelaksana = b.employee_nama
                                            var p_type = b.p_type
                                            var by_id = b.by_id
                                            arrUsedFData = [
                                                no,
                                                no_sub,
                                                prd_nama,
                                                room_nama,
                                                pelaksana,
                                                tanggal,
                                                ket,
                                                json_img,
                                                p_type,
                                                by_id,
                                            ]
                                            dataUsedJson.push(arrUsedFData)
                                        });

                                        if( typeof list_bahan_baku_used!='undefined'){
                                            if(typeof list_bahan_baku_used.destroy == 'function'){
                                                list_bahan_baku_used.destroy();
                                            }
                                        }

//console.log('dataUsedJson');
//console.log(dataUsedJson);

//console.log('subKomposisi');
//console.log(subKomposisi);

                                        list_bahan_baku_used = $('#list_bahan_baku_used').DataTable({
                                            data: dataUsedJson,
                                            paging: false,
                                            info: false,
                                            searching: false,
                                            ordering: false,
                                            columnDefs:[{
                                                targets: 1,
                                                className: 'dt-control text-link text-bold',
                                            }],
                                            initComplete: function(){
                                                $('#list_bahan_baku_used tbody').off();
                                                $('#list_bahan_baku_used tbody').on('click', 'td.dt-control', function(){
                                                    var tr        = $(this).closest('tr');
                                                    var row       = list_bahan_baku_used.row(tr);
                                                    var json_img  = JSON.parse(row.data()[7]);
                                                    var no_sub    = row.data()[1];
                                                    var sub_bahan = subKomposisi[no_sub];

                                                    if(row.child.isShown()){
                                                        row.child.hide();
                                                    }
                                                    else{
                                                        row.child(formHistory(sub_bahan,json_img)).show();
                                                    }
                                                });
                                            }
                                        });

//                                        $('.btn-qc-done').on('click', function(a){
//                                            swal('MAINTENANCE', 'QUALITY CONTROL BELUM BISA DIGUNAKAN KARENA BAGIAN INI SEDANG DI MAINTENANCE, TUNGGU INFO SELANJUTNYA.', 'info');
//                                        });

                                        $('.btn-qc-done').on('click', function(a){
                                            var tid = $(this).attr('data-id')
                                            var pid = $(this).attr('data-produk-id')

                                            swal({
                                                title: 'PROSES PENINJAUAN DAN QUALITY CONTROL PEKERJAAN',
                                                html: 'Dengan klik OK, maka Anda telah melakukan Proses Peninjauan Fisik Pekerjaan..',
                                                type: 'question',
                                                showCancelButton: true,
                                            }).then((a)=>{
//                                                console.log(a);

                                                swal('SEDANG MEMPROSES....');
                                                swal.enableLoading();

                                                if(a){
                                                    $.ajax({
                                                        url: linkUpdateQc+pid+'/'+tid,
                                                        success: function(a){
                                                            var arrData = JSON.parse(a);
                                                            if(arrData.commit){
//                                                                console.log(arrData);

                                                                //posting biaya dulu jika ada
//                                                                if( arrData.arrbiaya ){
    //                                                                swal({
    //                                                                    title: 'Proses Pengembalian Produk',
    //                                                                    html:'Anda akan dibawa kehalaman Return Produk, lanjutkan??',
    //                                                                })
    //                                                                .then((res)=>{
    //                                                                    var evalues = {
    //                                                                        0:base_url + 'distribusifgproject/_shoppingCart/reset/9833',
    //                                                                        1:base_url + 'distribusifgproject/_processPihak/select/9833/MdlCabang?id=-1',
    //                                                                        2:base_url + 'distribusifgproject/_processPihak/selectProjek/9833/MdlProdukProject?id='+prodID,
    //                                                                        3:base_url + 'distribusifgproject/_processSelectProduct/selectProject/9833?spk='+top.$('#no_spk').text()+'&selector&id='+sub_fase_id+'&minValue=1'}
    //                                                                        localStorage.setItem('evalues', JSON.stringify(evalues));
    //                                                                    window.open(base_url + 'distribusifgproject/Create/index/9833?gr=dW5kZWZpbmU&topGr=9833&md=transaksi','')
    //                                                                });
//                                                                }
                                                                //posting biaya dulu jika ada

                                                                swal('SUKSES','Proses Quality Control BERHASIL dilakukan..', 'success');
                                                                swal.enableLoading();
                                                                setTimeout(function(){
                                                                    top.BootstrapDialog.closeAll();
                                                                    top.swal.close();
                                                                    if(top.$(\"a[href='#tab_tasklist']\").length>0){
//                                                                        console.log('GAGAL reload dimatikan dulu');
                                                                        top.$(\"a[href='#tab_tasklist']\").trigger('hide.bs.tab').trigger('shown.bs.tab');
                                                                    }
                                                                    else{
//                                                                        console.log('reload dimatikan dulu');
                                                                        top.window.location.reload();
                                                                    }
                                                                }, 1000)
                                                            }
                                                            else{
                                                                swal('GAGAL','Proses Quality Control GAGAL dilakukan', 'warning');
                                                                setTimeout(function(){
                                                                    top.BootstrapDialog.closeAll();
                                                                    top.swal.close();
                                                                    if(top.$(\"a[href='#tab_tasklist']\").length>0){
//                                                                        console.log('GAGAL reload dimatikan dulu');
                                                                        top.$(\"a[href='#tab_tasklist']\").trigger('hide.bs.tab').trigger('shown.bs.tab');
                                                                    }
                                                                    else{
//                                                                    console.log('GAGAL reload dimatikan dulu');
                                                                        top.window.location.reload();
                                                                    }
                                                                }, 2500)
                                                            }
                                                        }
                                                    })
                                                }
                                            })
//                                            console.log('tid: '+tid + ' | pid: '+pid);
                                        })
                                    }
                                })
                            }


</script>
<script>

                    var list_bahan_baku_used;

                    var fnTaskList = {
                        create: function(aa){
                            var ids = $(aa).attr('data-id');
                            BootstrapDialog.closeAll();
                            BootstrapDialog.show({
                                message: $('<div></div>').load('" . base_url() . "application/modules/master_project/template/tasklist_update.html?v=" . strtotime(date('Y-m-d H:i:s')) . "'),
                                title: 'Sub Proses Task List',
//                                size: 'custom-width',
                                size:top.BootstrapDialog.SIZE_WIDE,
                                closable: true,
                                animate: false,
                                onshow: function(){
                                    //console.log('dialogOnShow');
                                },
                                onshown: function(dialog) {
                                    var id = ids.split('-')[1];
                                    var prd_id = ids.split('-')[2];
                                    loadBahanTasklist(id,prd_id);
                                }
                            });
                        },
                        edit: function(aa){
                            var ids = $(aa).attr('data-id');
                            BootstrapDialog.closeAll();
                            BootstrapDialog.show({
                                message: $('<div></div>').load('" . base_url() . "application/modules/master_project/template/edit_spk.html?v=" . strtotime(date('Y-m-d H:i:s')) . "'),
                                title: 'EDIT',
//                                size: 'custom-width',
                                size:top.BootstrapDialog.SIZE_WIDE,
                                closable: true,
                                animate: false,
                                onshow: function(){
                                    //console.log('dialogOnShow');
                        },
                                onshown: function(dialog) {
                                    var id = ids.split('-')[1];
                                    var prd_id = ids.split('-')[2];
                                    loadEditSpk(id,prd_id);
                                }
                            });
                        },
                        delete: function(aa){
                            var ids = $(aa).attr('data-id');
                            swal('delete under maintenance');
                            swal.enableLoading();
                            setTimeout(function(){
                                swal.close();
                            }, 2500)
                        },
                        followup: function(aa){
                            var ids = $(aa).attr('data-id');
//                            console.log('followup ID: ' + ids);
//                            console.log('astaga: followup');
                            swal('followup under maintenance');
                            swal.enableLoading();
                            setTimeout(function(){
                                swal.close();
                            }, 2500)
                        },
                        update: function(aa,bb=''){
                            var selected = $(aa).attr('data-id');
                            var progress = $(aa).attr('data-progress');
                            if(bb!=''){
                                swal({
                                    title: 'Laksanakan Tugas',
                                    html: 'Klik oke untuk melanjutkan menerima Tugas ini..',
                                    type: 'warning',
                                }).then((res)=>{
                                    if(res){
                                        swal('Processing<br>Please Wait...', 'Sedang Update Status di Proses, Mohon tunggu sebentar...', 'warning');
                                        swal.enableLoading();
                                        var id = selected.split('-')[1];
                                        var prd_id = selected.split('-')[2];
                                        var select_id = bb;
                                        var select_nama = '';
                                        setTimeout( function(){
                                            $.ajax({
                                                url: '$linkSelector',
                                                type: 'post',
                                                data: {'produk_id':prd_id,'id': id, 'progress_id':select_id, 'progress_nama':select_nama},
                                                success: function(res){
                                                    var result = JSON.parse(res)
                                                    if(result.status=='ok'){
                                                        swal('UPDATE BERHASIL', 'update status berhasil, anda page akan refresh dalam 5 detik', 'success');
                                                        swal.enableLoading();
                                                        setTimeout(function(){
                                                            if(top.document.getElementById('result')){
                                                                top.$(\"a[href='#tab_tasklist']\").trigger('hide.bs.tab').trigger('shown.bs.tab');
                                                            }
                                                            swal.close();
                                                        },3000)
                                                    }
                                                    else{
                                                        swal('UPDATE GAGAL', 'update status GAGAL, anda page akan refresh dalam 5 detik', 'error');
                                                        swal.enableLoading();
                                                        setTimeout(function(){
                                                          if(top.document.getElementById('result')){
                                                              top.$(\"a[href='#tab_tasklist']\").trigger('hide.bs.tab').trigger('shown.bs.tab');
                                                          }
                                                          swal.close();
                                                        },3000)
                                                    }
                                                }
                                            })
                                        }, 1000)
                                    }
                                });
                            }
                            else{
                                swal({
                                    title: 'Update Status',
                                    html: \"<select id='upd_stat' data-style='btn btn-sm btn-primary'></select>\",
                                    type: 'warning',
                                    onOpen: function(){
                                        var ls = [
                                            {
                                                'label' : 'Tertunda',
                                                'value' : '1',
                                                'icon'  : 'fa fa-hourglass',
                                            },
                                            {
                                                'label' : 'Proses',
                                                'value' : '2',
                                                'icon'  : 'fa fa-send',
                                            },
                                            {
                                                'label' : 'Selesai',
                                                'value' : '3',
                                                'icon'  : 'fa fa-star',
                                            },
                                        ]
                                        var opt = \"<option value=''>==pilih status==</option>\"
                                        jQuery.each(ls, function(a, d){
                                            var disbld = d.value*1 <= progress ? 'disabled' : ''
                                            opt += \"<option data-style='text-danger' data-icon='\"+d.icon+\"' value='\"+d.value+\"' \"+disbld+\">\"+d.label+\"</option>\"
                                        })
                                        $('select#upd_stat').html(opt)
                                        .val('')
                                        .selectpicker('refresh')
                                        .on('change', function(){
                                            var values = $(this).val();
                                        })
                                    }
                                }).then((res)=>{
                                    if(res){
                                        var id = selected.split('-')[1];
                                        var prd_id = selected.split('-')[2];
                                        var select_id = $('select#upd_stat').val();
                                        var select_nama = $('select#upd_stat option:selected').text();
                                        $.ajax({
                                            url: '$linkSelector',
                                            type: 'post',
                                            data: {'produk_id':prd_id,'id': id, 'progress_id':select_id, 'progress_nama':select_nama},
                                            success: function(res){
                                                var result = JSON.parse(res)
                                                if(result.status=='ok'){
                                                    swal('UPDATE BERHASIL', 'update status berhasil, anda page akan refresh dalam 5 detik', 'success');
                                                    swal.enableLoading();
                                                    setTimeout(function(){
                                                        if(top.document.getElementById('result')){
                                                            top.$(\"a[href='#tab_tasklist']\").trigger('hide.bs.tab').trigger('shown.bs.tab');
                                                        }
                                                        swal.close();
                                                    },3000)
                                                }
                                                else{
                                                    swal('UPDATE GAGAL', 'update status GAGAL, anda page akan refresh dalam 5 detik', 'error');
                                                    swal.enableLoading();
                                                    setTimeout(function(){
                                                        if(top.document.getElementById('result')){
                                                            top.$(\"a[href='#tab_tasklist']\").trigger('hide.bs.tab').trigger('shown.bs.tab');
                                                        }
                                                        swal.close();
                                                    },3000)
                                                }
                                            }
                                        })
                                    }
                                });
                            }
                        },
                        view: function(aa){
                            var ids = $(aa).attr('data-id');
                        },
                        print: function(aa,pre=''){
                            var ids = $(aa).attr('data-id');
                            var id = ids.split('-')[1];
                            var prd_id = ids.split('-')[2];
                            var pre_ = pre!='' ? '/1' : '';
                            window.open('$linkPrint'+id+'/'+prd_id+pre_,'_blank','left=100,width=450,height=750')
                        },
                        batal: function(aa){
                            var ids = $(aa).attr('data-id');
                            var id = ids.split('-')[1];
                            var prd_id = ids.split('-')[2];

                            $.ajax({
                                url: '".base_url()."master_project/MasterData/checkLockerTasklist',
                                type: 'post',
                                data: {'project_id':prd_id, 'id': id},
                                success: function(res){
                                    var json = JSON.parse(res);

                                    if(json.status==99){
                                        console.log(json);
                                        swal('INFORMASI',json.reason,'info');
                        }
                                    else if(json.status==2){
                                        var stok_produk = json.active.produk != undefined ? json.active.produk : {};
                                        var stok_supplies = json.active.supplies != undefined ? json.active.supplies : {};

                                        console.log(stok_produk);
                                        console.log(stok_supplies);
                                        console.log(json);

                                        var table = ''

                                        if( countObj(stok_produk)>0 || countObj(stok_supplies)>0){
                                            table += \"<table class='table dataTable compact table-bordered table-hovered'>\"
                                            table += \"<caption class='text-red text-left text-bold'>BEBERAPA PRODUK INI PERLU DI RETURN SEBELUM BISA DI BATALKAN</caption>\"
                                            table += \"<thead>\"
                                            table += \"<tr>\"
                                            table += \"<th>No</th>\"
                                            table += \"<th>Jenis</th>\"
                                            table += \"<th>Nama</th>\"
                                            table += \"<th>Jumlah</th>\"
                                            table += \"</tr>\"
                                            table += \"</thead>\"
                                            table += \"<tbody>\"
                                        }
                                        var globNum = 0;
                                        if(countObj(stok_produk)>0){
                                            jQuery.each(stok_produk ,function(a, b){
                                                var nama = b.nama;
                                                var jml = b.jumlah;
                                                var jenis = b.jenis;
                                                globNum++;
                                                table += \"<tr>\"
                                                table += \"<td>\"+globNum+\"</td>\"
                                                table += \"<td>\"+jenis+\"</td>\"
                                                table += \"<td>\"+nama+\"</td>\"
                                                table += \"<td>\"+jml+\"</td>\"
                                                table += \"</tr>\"
                                            });
                                        }

                                        if(countObj(stok_supplies)>0){
                                            jQuery.each(stok_supplies ,function(a, b){
                                                var nama = b.nama;
                                                var jml = b.jumlah;
                                                var jenis = b.jenis;
                                                globNum++;
                                                table += \"<tr>\"
                                                table += \"<td>\"+globNum+\"</td>\"
                                                table += \"<td>\"+jenis+\"</td>\"
                                                table += \"<td>\"+nama+\"</td>\"
                                                table += \"<td>\"+jml+\"</td>\"
                                                table += \"</tr>\"
                                            });
                                        }

                                        if( countObj(stok_produk)>0 || countObj(stok_supplies)>0){
                                            table += \"</tbody>\"
                                            table += \"</table>\"
                                            table += \"<div class='alert alert-danger text-auto'>Jika Anda punya wewenang, klik pada tombol PEMANTAUAN, kemudian return semua bahan baku.<img style='border-radius: 10px;' src='".base_url()."public/images/profiles/pemantauan.png'> isi => <img style='border-radius: 10px;' src='".base_url()."public/images/profiles/isikelebihan.png'>\"
                                            table += \"</div>\"
                                        }

                                        swal({
                                            title: 'STOK PERLU DI RETURN',
                                            html: table,
                                            type: 'info',
                                        });
                                    }
                                    else if(json.status==3){
                                        console.log(json);
                                        swal('INFORMASI',json.reason,'info');
                                    }
                                    else{
                                        console.log(json);

                                        swal({
                                            title: 'INFORMASI',
                                            text: 'Anda akan membatalkan tasklist ini, apakah ini oke..?',
                                            type: 'warning', // Gunakan 'type' bukan 'icon' di versi lama
                                            showCancelButton: true,
                                            confirmButtonText: 'Lanjutkan',
                                            cancelButtonText: 'Batal'
                                        }).then((isConfirm)=>{
                                            console.log(isConfirm);
                                            if (isConfirm) {
                                                // Eksekusi AJAX untuk menghapus tasklist
                                                swal('SEDANG MEMPROSES...', 'Mohon tunggu jangan tutup browser Anda.', 'warning');
                                                swal.enableLoading();;
                                                $.ajax({
                                                    url: '".base_url()."master_project/MasterData/pembatalanTasklist/'+id+'/'+prd_id+'/1', // Ganti dengan URL yang sesuai
                                                    type: 'POST',
                                                    data: { status: 1, project_id: prd_id, tasklist_id: id }, // Kirim id tasklist yang ingin dihapus
                                                    success: function (response) {
                                                        // Tampilkan pesan sukses
                                                        console.log(response);
                                                        swal('Berhasil!', 'Tasklist telah dihapus.', 'success');
                                                        // Lakukan refresh atau aksi lainnya setelah penghapusan
                                                    },
                                                    error: function (error) {
                                                        console.log(error);
                                                        // Tampilkan pesan error
                                                        swal('Oops!', 'Terjadi kesalahan saat menghapus tasklist.', 'error');
                                                    }
                                                });
                                            }
                                        });

                                    }
                                }
                            })
                        }
                    };
                ";
                $scriptBottom .= "</script>";
            }
        }
        $taskListFields["action"] = "tindakan";
        //endregion

        $showFields = array(
            "nama" => "PROJECT NAMA",
            "customer_nama" => "KONSUMEN",
            "end_dtime" => "MASA BERAKHIR",
            "harga" => "VALUASI PROJECT <r>(Rp)</r>",
            "harga_progress" => "PROGRESS <r>(Rp)</r>",
            "persen_progress" => "PROGRESS <r>(%)</r>",
            "nomor_kontrak" => "No Kontrak",
        );
        $data = array(
            "mode" => "taskList",
            "load" => $load, //untuk switch tanpa template (load dalam div)
            "masterProjectField" => $showFields,
            "masterProject" => $dataMaster[0],
            "masterLabel" => isset($masterLabel) ?  $masterLabel : "",
            "workOrderTitle" => "BUAT TUGAS",
            "workOrder" => $workOrder,
            "workOrderLabel" => $workOrderLabel,
            "timWork" => $timWork,
            "allkomposisi" => $komposisiWorkOrder,
            "produkID" => $prodID,
            "produkNama" => isset($tempProdukMaster) ? $tempProdukMaster[0]->nama : "",
            "progresTaklist" => $progresTaskList,
            "progressTaskListLabel" => $progressTaskLabel,
            "addLink" => MODUL_PATH . get_class($this) . "/tasklist/add/$prodID",
            "selector" => MODUL_PATH . get_class($this) . "/selectItem/tasklist/",
            "taskistProject" => $taskList,
            "tasklistProjectField" => $taskListFields,
            // "relbiayaProduksi"=>$prebiayaProduksi,
            // "sumaryProjectLabel"=>$sumarryProjectLabel,
            // "sumaryProject"=>$sumarryProject,
            "addtaskLink" => MODUL_PATH . get_class($this) . "/addTask/MdlTasklistProject/tasklist/$prodID",//untuk per produk(sumary)
            "timelinetaskLink" => MODUL_PATH . get_class($this) . "/ajaxTasklistTimeline/$prodID",//untuk per produk(sumary)
            "sessionData" => isset($_SESSION[$cCode]) ? $_SESSION[$cCode] : array(),
            "result" => isset($iframeTarget) ? $iframeTarget : "",
            "cCode" => $cCode,
            "jenisTr" => $cCode,
            "scriptBottomCtr" => $scriptBottom,
            "linkUploadPhoto" => $linkUploadPhoto,
            "ci_session" => $_COOKIE['ci_session'],
            "projectStart" => $projectStart,
//            "allowCreateTugas" => $allowCreateTugas,
        );
        $this->load->view("data", $data);
    }

    public function penugasan(){

        $cCode = $this->uri->segment(3);
        $pid = $prodID = $produkID = $this->uri->segment(4);
        $load = $this->uri->segment(5);
        $iframeTarget = isset($_GET["iframe"]) ? $_GET["iframe"] : (isset($_POST["iframe"]) ? $_POST["iframe"] : "result");
        $sessionData = isset($_SESSION[$cCode]) ? $_SESSION[$cCode] : array();
        $selector = MODUL_PATH . get_class($this) . "/selectItem/tasklist/";
        $this->load->model("Mdls/MdlProjectWorkOrder");
        $w = new MdlProjectWorkOrder;

        $this->load->model("Mdls/MdlTimWorkProject");
        $t = new MdlTimWorkProject;


        $this->load->model("Mdls/MdlProjectProdukPaket");
        $p_package = new MdlProjectProdukPaket();
        $p_package->addFilter("project_id='$pid'");
        $tmpPrdPackage = $p_package->lookUpAll()->result();

        $selectPackage=array();
        foreach($tmpPrdPackage as $k => $dpackage){
            $selectPackage[$dpackage->id] = $dpackage->id;
        }

        if(count($selectPackage)>0){
            $this->db->select("*");
            $this->db->where("status=1 and trash=0");
            $this->db->where_in("produk_id", $selectPackage);
            $tmpPaket = $this->db->get("produk_komposisi_paket")->result();
        }

        showLast_query('biru');

        $produkPaket=array();
        if(!empty($tmpPaket)){
            foreach($tmpPaket as $k => $pkdData){
                $produkPaket[$pkdData->produk_id] = $pkdData->produk_nama;
            }
        }
//        else{
//            foreach($tmpPrdPackage as $k => $pkdData){
//                $produkPaket[$pkdData->id] = $pkdData->nama;
//            }
//        }

        $this->load->model("Mdls/MdlEmployee_all");
        $e = new MdlEmployee_all();
        $e->addFilter("allow_project='1'");
//        $allEmployee = $e->lookUpAll()->result();

        $timWork = $e->lookUpAll()->result();

        //region timwork
//        $t->addFilter("produk_id='$prodID'");
//        $timWork = $t->lookUpAll()->result();
        //endregion

//        arrPrint($sessionData);

        $formWork_tim = "";
        $kol = "employee_id";
        $formWork_tim .= "<select id='employee_id' data-style=\"btn btn-sm btn-primary\" class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID?key=employee_id&value='+encodeURI( $(this).selectpicker('val') )+'');\">";
        $formWork_tim .= "<option value='0'>-- silahkan pilih --</option>";
        if(!empty($timWork)){
            foreach ($timWork as $timWork_0) {
                $pid = $timWork_0->id;
                $nama = $timWork_0->nama;
                $selected = isset($sessionData[$pid][$kol]) && ($sessionData[$pid][$kol] == $timWork_0->employee_id) ? "selected" : "";
                $formWork_tim .= "<option $selected value='$pid'>$nama</option>";
            }
        }
        $formWork_tim .= "</select>";

        //region workOrder
        $w->addFilter("produk_id='$prodID'");
        $workOrder = $w->lookUpAll()->result();
        $workOrderLabel = $w->getListedFields();
        //endregion

        $workOrderTitle = "BUAT PENUGASAN";
        $addtaskLink = MODUL_PATH . get_class($this) . "/addTask/MdlTasklistProject/tasklist/$prodID";
        $targetResult = isset($iframeTarget) ? "&result=$iframeTarget" : "&result=result";

        $kol = "fase_id";
        $formWork_order = "";
        $formWork_order .= "<select id='$kol' data-style=\"btn btn-sm btn-primary\" class=\"selectpicker\" data-live-search=\"true\">";
        $formWork_order .= "<option value='0'>-- silahkan pilih --</option>";

        $jml_project = count($workOrder);
        foreach ($workOrder as $workOrder_0) {
            $pid = $workOrder_0->produk_id;
            if($jml_project*1>1){
                $selected = isset($sessionData[$pid][$kol]) && ($sessionData[$pid][$kol] == $workOrder_0->id) ? "selected" : "";
            }
            else{
                $selected = "selected";
            }
            $formWork_order .= "<option $selected value='" . $workOrder_0->id . "'>" . $workOrder_0->nama . " </option>";
        }
        $formWork_order .= "</select>";

//        $showNilai = $_SESSION['MasterData']['show_nilai']*1;
        $showNilai = 0;

        $formWork_order .= "<script>
                $('#$kol').off();
                $('#$kol').on('change', function(){
                    var values = $(this).val();
                    $.ajax({
                        url: '$selector$produkID?key=fase_id&value='+encodeURI(values)+'$targetResult',
                        success: function(r_fase_id){
                            var hsl = JSON.parse(r_fase_id);
                            var opt = \"<label class='box-title'>Pilih Pekerjaan: &nbsp;</label>\"
                                    + \"<select id='sub_fase' data-style='btn btn-sm btn-success' class='selectpicker' data-live-search='true'>\"
                                    + \"<option value='0'>-- Pilih Pekerjaan --</option>\"

                            total_list = countObj(hsl.$kol)

                            jQuery.each(hsl.$kol, function(a,b){
                                if(total_list*1>1){
                                    var sel = hsl.session[$produkID].sub_fase_id*1  == b.id*1 ? 'selected' : ''
                                }
                                else{
                                    var sel = 'selected'
                                }
                                var disabeled = b.daftar_tugas*1 > 0 ? 'disabled' : ''
                                var icons__t = b.daftar_tugas*1 > 0 ? 'fa fa-check-circle' : ''
                                opt += \"<option data-icon='\"+icons__t+\"' \"+disabeled+\" \"+sel+\" value='\"+b.id+\"'>\"+b.nama+\"</option>\"
                            })

                            opt += \"</select>\"

                            $('#sub_fase_selector').html(opt);
                            $('#sub_fase_selector').removeClass('hidden');
                            top.$('select#sub_fase').selectpicker();
                            top.$('select#sub_fase').selectpicker('refresh');
                            initSubFase();
                            top.$('select#sub_fase').trigger('change');
                        }
                    })
                });

                $('#$kol').trigger('change');

                function initSubFase(){
                    $('#sub_fase').off();
                    $('#sub_fase').on('change', function(){
                        var values = $(this).val();
                        $.ajax({
                            url: '$selector$produkID?key=sub_fase_id&value='+encodeURI(values)+'$targetResult',
                            success: function(sub_fase_id){
                                var hsl = JSON.parse(sub_fase_id);
                                var room = hsl.room;
                                var komposisi = hsl.komposisi_room;
                                var updated = Object.values(hsl.sub_fase_id.reduce((obj, item) => {
                                    var key = item.jenis + '_' + item.produk_dasar_id;
                                    if(!obj[key]){
                                        obj[key]        = Object.assign(item)
                                        obj[key].harga  = obj[key].harga*1
                                        obj[key].jml    = obj[key].jml*1
                                        obj[key].saldo  = obj[key].saldo*1
                                    }
                                    else{
                                        obj[key].jml += item.jml*1
                                        obj[key].saldo += item.saldo*1
                                    }
                                    return obj
                                }, {}));
                                var showNilai = $showNilai*1;
                                var table = ''
                                    table += \"<div class='row'>\"
                                    if(room.length>0){
                                        table += \"<div class='container-fluids'>\"
                                        table += \"<div class='box box-warning box-solid'>\"
                                        table += \"<div class='box-header fa-2x text-bold text-blue'>SEPARATED ROOM</div>\"
                                        table += \"<div class='box-body'>\"
                                        table += \"<div class='nav-tabs-custom'>\"
                                        table += \"<div class='tab-content no-padding'>\"
                                        table += \"<ul class='nav nav-tabs' id='custom-content-below-tab' role='tablist'>\"
                                        jQuery.each(room, function(a, b){
                                            var defActive = a==0 ? 'active' : ''
                                            var num = a+1;
                                            table += \"<li class='nav-item \"+defActive+\"'>\"
                                            table += \"<a class='nav-link' id='cc-tab-room_\"+b.room_id+\"' data-toggle='pill' href='#tsk-tab-room_\"+b.room_id+\"' role='tab' aria-controls='cc-tab-room_\"+b.room_id+\"' aria-selected='false'><span style='font-size: 12px;' class='text-bold text-uppercase'> <b>\"+num+\". \"+b.room_nama+\"</b></span></a>\"
                                            table += '</li>'
                                        });
                                        table += '</ul>'
                                            jQuery.each(room, function(a, b){
                                                var list_bahan_biaya = Object.values(komposisi[b.room_id].reduce((obj, item) => {
                                                    var key = item.jenis + '_' + item.produk_dasar_id;
                                                    if (!obj[key]) {
                                                        obj[key] = Object.assign(item)
                                                        obj[key].harga = obj[key].harga*1
                                                        obj[key].jml = obj[key].jml*1
                                                        obj[key].qty_kredit = obj[key].qty_kredit*1
                                                        obj[key].saldo = obj[key].saldo*1
                                                    }
                                                    else {
                                                        obj[key].jml += item.jml*1
                                                        obj[key].saldo += item.saldo*1
                                                        obj[key].qty_kredit += item.qty_kredit*1
                                                    }
                                                    return obj
                                                }, {}));
                                                var defActive = a==0 ? 'active in' : ''
                                                table += \"<div class='tab-pane fade \"+defActive+\"' id='tsk-tab-room_\"+b.room_id+\"'>\"
                                                table += \"<div class='table-responsives'>\"
                                                table += \"<table id='table_container_material_\"+b.room_id+\"' class='table dataTable compact display table-bordered table-condensedx'>\"
                                                table += '<thead>'
                                                table += '<tr>'
                                                table += '<th>No</th>'
                                                table += '<th>Bahan baku</th>'
                                                table += '<th>UoM</th>'
                                                if(showNilai){
                                                    table += \"<th class='text-center'>H.HPP<br>(satuan)</th>\"
                                                    table += \"<th class='text-center'>H.PPV<br>(satuan)</th>\"
                                                    table += \"<th class='text-center'>HJ.STD<br>(satuan)</th>\"
                                                    table += \"<th class='text-center'>Hrg Anggaran<br>(satuan)</th>\"
                                                }
                                                table += \"<th class='text-center'>Jml</th>\"
                                                if(showNilai){
                                                    table += \"<th class='text-center'>Total<br>HPP</th>\"
                                                    table += \"<th class='text-center'>Total<br>PPV</th>\"
                                                    table += \"<th class='text-center'>Total<br>HJ.STD</th>\"
                                                    table += \"<th class='text-center'>Total<br>Anggaran</th>\"
                                                    table += \"<th class='text-center'>R/L</th>\"
                                                }
                                                table += '</tr>'
                                                table += '</thead>'
                                                table += '<tbody>'
                                                var no          = 0;
                                                var total_saldo = 0;
                                                var total_qty   = 0;
                                                var totHpp  = 0;
                                                var totPpv  = 0;
                                                var totJual = 0;
                                                var totAng  = 0;
                                                var sumHpp  = 0;
                                                var sumPpv  = 0;
                                                var sumJual = 0;
                                                var sumAng  = 0;
                                                jQuery.each(list_bahan_biaya, function(a, b){
                                                    no++;
                                                    produk_dasar_nama = b.produk_dasar_nama;
                                                    satuan  = b.satuan;
                                                    harga   = b.harga*1>0     ? b.harga*1 : 0;
                                                    nilai   = b.nilai*1>0     ? b.nilai*1 : 0;
                                                    jml     = b.jml*1>0       ? b.jml*1 : 0;
                                                    saldo   = b.saldo*1>0     ? b.saldo*1 : 0;
                                                    hpp     = b.hrg_hpp*1> 0  ? b.hrg_hpp*1 : 0;
                                                    ppv     = b.hrg_ppv*1> 0  ? b.hrg_ppv*1 : 0;
                                                    jual    = b.hrg_jual*1> 0 ? b.hrg_jual*1 : 0;
                                                    table += '<tr>'
                                                    table += '<td>'+no+'</td>'
                                                    table += '<td>'+produk_dasar_nama+'</td>'
                                                    table += '<td>'+satuan+'</td>'
                                                    if(showNilai){
                                                        table += \"<td class='bg-limex text-right'>\"+addCommas(hpp)  +'</td>'
                                                        table += \"<td class='bg-infox text-right'>\"+addCommas(ppv)  +'</td>'
                                                        table += \"<td class='bg-infox text-right'>\"+addCommas(jual) +'</td>'
                                                        table += \"<td class='bg-infox text-right'>\"+addCommas(harga)+'</td>'
                                                    }
                                                    table += \"<td class='text-bold text-center'>\"+addCommas(jml)+'</td>'
                                                    if(showNilai){
                                                        table += \"<td class='bg-greenx text-right'>\"+addCommas(hpp*jml)+'</td>'
                                                        table += \"<td class='bg-infox text-right'>\"+addCommas(ppv*jml)+'</td>'
                                                        table += \"<td class='bg-infox text-right'>\"+addCommas(jual*jml)+'</td>'
                                                        table += \"<td class='bg-infox text-right'>\"+addCommas(harga*jml)+'</td>'
                                                        table += \"<td class='text-right'>\"+addCommas( (harga*jml)-(hpp*jml))+'</td>'
                                                    }
                                                    table += '</tr>'
                                                    total_saldo += saldo*1;
                                                    total_qty   += jml*1;
                                                    totHpp  += hpp;
                                                    totPpv  += ppv;
                                                    totJual += jual;
                                                    totAng  += harga;
                                                    sumHpp  += hpp*jml;
                                                    sumPpv  += ppv*jml;
                                                    sumJual += jual*jml;
                                                    sumAng  += harga*jml;
                                                })
                                                table += '</tbody>'
                                                table += \"<tfoot class='bg-gray'>\"
                                                table += '<tr>'
                                                table += '<th>-</th>'
                                                table += '<th>-</th>'
                                                table += '<th>-</th>'
                                                if(showNilai){
                                                    table += \"<th class='text-right'>\"+addCommas(totHpp)+'</th>'
                                                    table += \"<th class='text-right'>\"+addCommas(totPpv)+'</th>'
                                                    table += \"<th class='text-right'>\"+addCommas(totJual)+'</th>'
                                                    table += \"<th class='text-right'>\"+addCommas(totAng)+'</th>'
                                                }
                                                    table += \"<th class='text-center'>\"+addCommas(total_qty)+'</th>'
                                                if(showNilai){
                                                    table += \"<th class='text-right'>\"+addCommas(sumHpp)+'</th>'
                                                    table += \"<th class='text-right'>\"+addCommas(sumPpv)+'</th>'
                                                    table += \"<th class='text-right'>\"+addCommas(sumJual)+'</th>'
                                                    table += \"<th class='text-right'>\"+addCommas(sumAng)+'</th>'
                                                    table += \"<th class='text-right'>\"+addCommas((sumAng)-(sumHpp))+'</th>'
                                                }
                                                table += '</tr>'
                                                table += '</tfoot>'
                                                table += '</table>'
                                                table += '</div>'
                                                table += '</div>'
                                        })
                                        table += '</div>'
                                        table += '</div>'
                                        table += '</div>'
                                        table += '</div>'
                                        table += '</div>'
                                    }

                                table += \"<div class='container-fluid'>\"
                                table += \"<div class='table-responsives'>\"
                                table += \"<table id='ctrl no_room table_container_material' class='table dataTable compact display table-bordered table-condensedx'>\"
                                table += '<thead>'
                                table += '<tr>'
                                table += '<th>No</th>'
                                table += '<th>Bahan baku</th>'
                                table += '<th>UoM</th>'

                                if(showNilai){
                                    table += \"<th class='text-center'>H.HPP<br>(satuan)</th>\"
                                    table += \"<th class='text-center'>H.PPV<br>(satuan)</th>\"
                                    table += \"<th class='text-center'>HJ.STD<br>(satuan)</th>\"
                                    table += \"<th class='text-center'>Hrg Anggaran<br>(satuan)</th>\"
                                }

                                table += \"<th class='text-center'>Total</th>\"
                                table += \"<th class='text-center'>Ongoing</th>\"
                                table += \"<th class='text-center'>Sisa</th>\"
                                table += \"<th class='text-center'>tugaskan</th>\"
                                table += \"<th class='text-center'>akhir</th>\"
                                table += \"<th class='text-center'>check <span onclick='hitung_ulang()' class=''><i class='fa fa-calculator'></i></span></th>\"

                                if(showNilai){
                                    table += \"<th class='text-center'>Total<br>HPP</th>\"
                                    table += \"<th class='text-center'>Total<br>PPV</th>\"
                                    table += \"<th class='text-center'>Total<br>HJ.STD</th>\"
                                    table += \"<th class='text-center'>Total<br>Anggaran</th>\"
                                    table += \"<th class='text-center'>R/L</th>\"
                                }

                                table += '</tr>'
                                table += '</thead>'
                                table += '<tbody>'

                                var no=0;
                                var total_saldo = 0;
                                var total_qty = 0;
                                var dikerjakan = 0;
                                var total_kredit = 0;
                                var totHpp = 0;
                                var totPpv = 0;
                                var totJual = 0;
                                var totAng = 0;
                                var sumHpp = 0;
                                var sumPpv = 0;
                                var sumJual = 0;
                                var sumAng = 0;

                                jQuery.each(updated, function(a, b){
                                    no++;
                                    produk_dasar_nama = b.produk_dasar_nama;
                                    pdi = b.produk_dasar_id;
                                    satuan = b.satuan;
                                    harga  = b.harga*1>0 ? b.harga*1 : 0;
                                    nilai  = b.nilai*1>0 ? b.nilai*1 : 0;
                                    jml    = b.jml;
                                    saldo  = b.qty_saldo;
                                    dikerjakan  = b.dikerjakan*1>0 ? b.dikerjakan*1 : 0;
                                    qty_kredit  = b.qty_kredit*1>0 ? b.qty_kredit*1 : 0;
                                    hpp  = b.hrg_hpp*1> 0 ? b.hrg_hpp*1 : 0;
                                    ppv  = b.hrg_ppv*1> 0 ? b.hrg_ppv*1 : 0;
                                    jual = b.hrg_jual*1> 0 ? b.hrg_jual*1 : 0;
                                    table += '<tr>'
                                    table += '<td>'+no+'</td>'
                                    table += '<td>'+produk_dasar_nama+'</td>'
                                    table += '<td>'+satuan+'</td>'
                                    if(showNilai){
                                        table += \"<td class='bg-limex text-right'>\"+addCommas(hpp)+\"</td>\"
                                        table += \"<td class='bg-infox text-right'>\"+addCommas(ppv)+\"</td>\"
                                        table += \"<td class='bg-infox text-right'>\"+addCommas(jual)+\"</td>\"
                                        table += \"<td class='bg-infox text-right'>\"+addCommas(harga)+\"</td>\"
                                    }
                                    table += \"<td class='text-bold text-center'>\"+addCommas(jml)+\"</td>\"
                                    table += \"<td class='text-bold text-center'>\"+addCommas(dikerjakan)+\"</td>\"
                                    table += \"<td class='text-bold text-center'>\"+addCommas(saldo)+\"</td>\"
                                    table += \"<td class='text-bold text-center'><input size='5' pid='\"+pdi+\"' jml='\"+jml+\"' ong='\"+dikerjakan+\"' sal='\"+saldo+\"' onclick='select()' value='0' style='background: greenyellow;' class='id_tugaskan_\"+pdi+\" form-control form-control-sm text-right'></td>\"
                                    table += \"<td class='text-bold text-center'><input size='5' readonly class='id_sisa_tugaskan_\"+pdi+\" form-control form-control-sm text-right' style='background: lightgrey;'></td>\"
                                    table += \"<td class='text-bold text-center'><span class='valid_tugaskan_\"+pdi+\"'></span></td>\"
                                    if(showNilai){
                                        table += \"<td class='bg-greenx text-right'>\"+addCommas(hpp*jml)+\"</td>\"
                                        table += \"<td class='bg-infox text-right'>\"+addCommas(ppv*jml)+\"</td>\"
                                        table += \"<td class='bg-infox text-right'>\"+addCommas(jual*jml)+\"</td>\"
                                        table += \"<td class='bg-infox text-right'>\"+addCommas(harga*jml)+\"</td>\"
                                        table += \"<td class='text-right'>\"+addCommas( (harga*jml)-(hpp*jml))+\"</td>\"
                                    }
                                    table += '</tr>'
                                    total_saldo += saldo*1;
                                    total_qty   += jml*1;
                                    total_kredit   += qty_kredit*1;
                                    totHpp  += hpp;
                                    totPpv  += ppv;
                                    totJual += jual;
                                    totAng  += harga;
                                    sumHpp  += hpp*jml;
                                    sumPpv  += ppv*jml;
                                    sumJual += jual*jml;
                                    sumAng  += harga*jml;
                                })
                                table += '</tbody>'
                                table += \"<tfoot class='bg-gray'>\"
                                table += '<tr>'
                                table += '<th>-</th>'
                                table += '<th>-</th>'
                                table += '<th>-</th>'
                                if(showNilai){
                                    table += \"<th class='text-right'>\"+addCommas(totHpp)+\"</th>\"
                                    table += \"<th class='text-right'>\"+addCommas(totPpv)+\"</th>\"
                                    table += \"<th class='text-right'>\"+addCommas(totJual)+\"</th>\"
                                    table += \"<th class='text-right'>\"+addCommas(totAng)+\"</th>\"
                                }
                                    table += \"<th class='text-center'>\"+addCommas(total_qty)+\"</th>\"
                                    table += \"<th class='text-center'>\"+addCommas(total_kredit)+\"</th>\"
                                    table += \"<th class='text-center'>\"+addCommas(total_saldo)+\"</th>\"
                                    table += \"<th class='text-center'>-</th>\"
                                    table += \"<th class='text-center'>-</th>\"
                                    table += \"<th class='text-center'>-</th>\"
                                if(showNilai){
                                    table += \"<th class='text-right'>\"+addCommas(sumHpp)+\"</th>\"
                                    table += \"<th class='text-right'>\"+addCommas(sumPpv)+\"</th>\"
                                    table += \"<th class='text-right'>\"+addCommas(sumJual)+\"</th>\"
                                    table += \"<th class='text-right'>\"+addCommas(sumAng)+\"</th>\"
                                    table += \"<th class='text-right'>\"+addCommas((sumAng)-(sumHpp))+\"</th>\"
                                }
                                table += '</tr>'
                                table += '</tfoot>'
                                table += '</table>'
                                table += '</div>'
                                table += '</div>'
                                table += '</div>'
                                $('#container_material').html(table);
                                var table_material = $('#table_container_material').DataTable({
                                    ordering: false,
                                    searching: false,
                                    info: false,
                                    paging: false,
                                    stateSave: true
                                });
                                top.hitung_ulang = function(){
                                    $('[class^=id_tugaskan_]').trigger('keyup')
                                }
                                function susun_produk(){
                                    var tmpProduk = $('[class^=id_tugaskan_]');
                                    var listPrd = {}
                                    jQuery.each(tmpProduk, function(a,b){
                                        var ids    = $(b).attr('pid')
                                        var values = $(b).val();
                                        var jml = $(b).attr('jml')
                                        var awal = $(b).attr('sal')
                                        var going = $(b).attr('ong')
                                        if(values==0 || values*1>0 && values*1<=awal){
                                            listPrd[ids] = {ids,values,jml,awal,going}
                                        }
                                    })
                                    $.ajax({
                                        url: '$selector$produkID?key=produk_fase_id',
                                        method: 'post',
                                        data: {data: listPrd},
                                        success: function(){
                                        }
                                    })
                                }

                                $('[class^=id_tugaskan_]').off();
                                $('[class^=id_tugaskan_]').on('keyup', function(){
                                    var ids     = $(this).attr('pid')
                                    var sisa    = $(this).attr('sal');
                                    var newVal  = removeCommas($(this).val()*1);
                                    var akhir   = (sisa*1) - newVal*1;
                                    if(isNaN(akhir)){
                                        $(this).val(0).select();
                                        $('.id_sisa_tugaskan_'+ids).val(0);
                                        $('.valid_tugaskan_'+ids).html(\"<i class='fa fa-warning text-red'></i>\");
                                    }
                                    else if(akhir*1<0){
                                        $(this).select();
                                        $('.id_sisa_tugaskan_'+ids).val(0);
                                        $('.valid_tugaskan_'+ids).html(\"<i class='fa fa-warning text-red'></i>\");
                                    }
                                    else if(akhir*1==sisa*1){
                                        $(this).val(addCommas(newVal));
                                        $('.id_sisa_tugaskan_'+ids).val(addCommas(akhir));
                                        $('.valid_tugaskan_'+ids).html(\"<i class='fa fa-minus'></i>\");
                                    }
                                    else{
                                        $(this).val(addCommas(newVal));
                                        $('.id_sisa_tugaskan_'+ids).val(addCommas(akhir));
                                        $('.valid_tugaskan_'+ids).html(\"<i class='fa fa-check text-green'></i>\");
                                    }
                                    susun_produk();
                                })
                            }
                        })
                    });
                }
            </script>";


        $workOrder = "";
        $workOrder .= "<div class='blink text-bold text-info box-header'><h3>$workOrderTitle#</h3></div>";
        $workOrder .= "<div class='box-body no-paddingx table-responsivex'>";
        $workOrder .= "<form id='tasklist_form' name='tasklist_form' target='result' action='$addtaskLink?$targetResult'>";

        $workOrder .= "<div class=''>";
        $workOrder .= "<label class='box-title'>Pilih Project: &nbsp;</label>";
        $workOrder .= $formWork_order;
        $workOrder .= "</div>";

        $workOrder .= "<div id='sub_fase_selector' style='margin-top: 6px;display:none;' class='hidden'>";
        $workOrder .= "</div>";

        $workOrder .= "<div style='margin-top: 6px;' class='row'>";

        $workOrder .= "<div class='col-md-6'>";
        $workOrder .= "<label class=''>Label Tugas/Nama: &nbsp;</label>";
        $default_nama = isset($sessionData[$produkID]["nama"]) ? $sessionData[$produkID]["nama"] : "";
        $workOrder .= "<input placeholder='Nama Tugas' id='in_tugas' type='text' class='form-control form-control-sm' value ='" . $default_nama . "'onmouseoutx=\"$('#input_temp').load('$selector" . "$produkID?key=nama&value='+encodeURI(this.value)+'$targetResult');\">";

        $workOrder .= "<label class=''>Pembeli/Pemilik Rumah/Gedung: &nbsp;</label>";
        $default_owner = isset($sessionData[$produkID]["owner"]) ? $sessionData[$produkID]["owner"] : "";
        $workOrder .= "<input placeholder='Pembeli/Pemilik Rumah/Gedung' id='in_owner' type='text' class='form-control form-control-sm' value ='" . $default_owner . "'onmouseoutx=\"$('#input_temp').load('$selector" . "$produkID?key=owner&value='+encodeURI(this.value)+'$targetResult');\">";

        $workOrder .= "</div>";

        $workOrder .= "<div class='col-md-6' class='' stylex='right: 10px;'>";
        $workOrder .= "<label class=''>Keterangan: &nbsp;</label>";

        $defaultValue = isset($sessionData[$produkID]["nilai"]) ? $sessionData[$produkID]["nilai"] : "";

        $workOrder .= "<textarea id='in_keterangan' rows=\"5\" cols=\"33\" type='textarea' class='form-control' onmouseoutx=\"$('#input_temp').load('$selector" . "$produkID?key=nilai&value='+encodeURI(this.value)+'$targetResult');\">$defaultValue</textarea>";
        $workOrder .= "</div>";
        $workOrder .= "</div>";

        $workOrder .= "<div style='margin-top: 6px;' class=''>";
        $workOrder .= "<label>Pelaksana: &nbsp;</label>";
        $workOrder .= $formWork_tim;
        $workOrder .= "</div>";

        $workOrder .= "<div style='margin-top: 6px;' class='row'>";
        $workOrder .= "<div class='col-md-6'>";
        $workOrder .= "<div class='form-group'>";
        $workOrder .= "<label>Mulai: &nbsp;</label>";
        $workOrder .= "<div class='input-group date'>";
        $workOrder .= "<div class='input-group-addon'>";
        $workOrder .= "<i class='fa fa-calendar'></i>";
        $workOrder .= "</div>";

        $defaultStartDate = isset($sessionData[$produkID]["dtime_start"]) ? date("Y-m-d", strtotime($sessionData[$produkID]["dtime_start"])) : "";

        $workOrder .= "<input type='date' class='form-control date' placeholder='tenggat waktu' id='date_start' value='$defaultStartDate' onchange =\"$('#input_temp').load('$selector" . "$produkID?key=dtime_start&value='+encodeURI(this.value)+'$targetResult');\"> ";
        $workOrder .= "</div>";
        $workOrder .= "</div>";
        $workOrder .= "</div>";

        $workOrder .= "<div class='col-md-6'>";
        $workOrder .= "<div class='form-group'>";
        $workOrder .= "<label class='box-title'>Tenggat: &nbsp;</label>";
        $workOrder .= "<div class='input-group date'>";
        $workOrder .= "<div class='input-group-addon'>";
        $workOrder .= "<i class='fa fa-calendar'></i>";
        $workOrder .= "</div>";

        $defaultEndDate = isset($sessionData[$produkID]["dtime_end"]) ? date("Y-m-d", strtotime($sessionData[$produkID]["dtime_end"])) : "";

        $workOrder .= "<input type='date' class='form-control date' placeholder='tenggat waktu' id='date_end' value='$defaultEndDate' onchange =\"$('#input_temp').load('$selector" . "$produkID?key=dtime_end&value='+encodeURI(this.value)+'$targetResult');\"> ";
        $workOrder .= "</div>";
        $workOrder .= "</div>";
        $workOrder .= "</div>";

//        $workOrder .= "<div id='container_paket' class='col-md-12'>";
//        $workOrder .= "<label>Pilih Produk Paket: &nbsp;</label>";
//        $workOrder .= "<select id='produk_paket' data-style=\"btn btn-sm btn-primary\" class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID?key=produk_paket&value='+encodeURI( $(this).selectpicker('val') )+'$targetResult');\">";
//        $workOrder .= "<option value='0'>-- silahkan pilih --</option>";
//        if(!empty($produkPaket)){
//            foreach ($produkPaket as $ids => $produkPaket_0) {
//                $workOrder .= "<option value='" . $ids . "'>" . $produkPaket_0 . "</option>";
//            }
//        }
//        $workOrder .= "</select>";
//        $workOrder .= "</div>";

//        $workOrder .= "
//            <div class='container-fluid'>
//                <h2><r><b>Pilih Produk Paket: &nbsp;</b></r></h2>
//                <p>Pilih salah satu paket menentukan daftar bahan. Atau bisa <a class='text-bold text-uppercase' href='javascript:void(0)' onclick='add_produk_paket($produkID)'>klik disini</a> untuk menambahkan.</p>
//                <form>
//        ";
        $workOrder .= "
            <div class='container-fluid'>
                <h2><r><b>Pilih Produk Paket: &nbsp;</b></r></h2>
                <p>Pilih salah satu paket menentukan daftar bahan. Atau bisa kembali kemenu overview bagian paling bawah, untuk menambahkan.</p>
                <form>
        ";
        if(!empty($produkPaket)){
            foreach ($produkPaket as $ids => $produkPaket_0) {

                $edit = "
                BootstrapDialog.show({
                    title:'Modify Paket Project ($produkPaket_0)',
                    cssClass: 'edit-dialog',
                    message: $('<div id=l1></div>').load('".base_url()."statik/Data/edit/Produk_Paket_Project/$ids?1=1&pfid=l1'),
                    draggable:false,
                    closable:true,
                    onhidden: function(dialogRef){
                        if(top.$('.modal.bootstrap-dialog.type-primary.fade.size-wide.in').length ){
                            top.BootstrapDialog.closeAll();
                            setTimeout(function(){
                                top.$('.btn_tambah_tugas').click();
                            },200)
                        }
                    }
                });";

                //edit dimatikan
                //&nbsp;&nbsp;&nbsp; <a href='javascript:void(0)' onclick=\"$edit\"> <i class='fa fa-pencil'></i> edit</a>

                $workOrder .= "
                    <label class='radio-inline text-bold'>
                        <input value='$ids' onclick=\"$('#input_temp').load('$selector" . "$produkID?key=produk_paket&value='+encodeURI( $(this).val() )+'$targetResult');\" type='radio' name='produkPaket_0'> $produkPaket_0
                    </label>
                ";
            }
        }

        $workOrder .= "
                </form>
            </div>

            <script>

                var add_produk_paket = function(aa){
                    BootstrapDialog.show(
                       {
                            title:'New Produk Paket Project',
                            message: $('<div></div>').load('".base_url()."statik/Data/add/Produk_Paket_Project/'+aa+'?auto=1'),
                            size: BootstrapDialog.SIZE_WIDE,
                            draggable:false,
                            closable:true,
                        }
                    );
                }

            </script>
        ";

        $workOrder .= "<div id='container_material_paket' class='col-md-12 no-padding'>";
        $workOrder .= "&nbsp;";
        $workOrder .= "</div>";

        $workOrder .= "<div id='container_material' class='col-md-12 hidden'>";
        $workOrder .= "&nbsp;";
        $workOrder .= "</div>";

        $workOrder .= "<div style='margin-top: 35px;' class='col-md-12'>";
        $workOrder .= "<button id='simpanTugas' type='button' class='btn btn-block btn-success btn-sm hidden' cx_onclick=\"document.getElementById('tasklist_form').submit();\">Simpan</button>";
        $workOrder .= "</div>";

        $workOrder .= "</div>";

        $workOrder .= "</form>";
        $workOrder .= "</div>";

        $workOrder .= "<div id='input_temp'></div>";

        $workOrder .= "
            <script>\n

                function init_inTugas(){
                    $('#in_tugas').on('keyup', delay_v2(function(){
                        $('#input_temp').load('$selector" . "$produkID?key=nama&value='+encodeURI(this.value)+'');
                    }, 1200))
                }
                function init_inKeterangan(){
                    $('#in_keterangan').on('keyup', delay_v2(function(){
                        $('#input_temp').load('$selector" . "$produkID?key=nilai&value='+encodeURI(this.value)+'');
                    }, 1200))
                }
                function init_inOwner(){
                    $('#in_owner').on('keyup', delay_v2(function(){
                        $('#input_temp').load('$selector" . "$produkID?key=owner&value='+encodeURI(this.value)+'');
                    }, 1200))
                }
                init_inTugas();
                init_inKeterangan();
                init_inOwner();
                function init_simpanTugas(){
                    top.$('#simpanTugas').off();
                    top.$('#simpanTugas').on('click', function(){
                        var id = $(this).attr('id');
                        var ev_aa = $(this).attr('cx_onclick');
                        var arr_input = $('.'+id);
                        var error = '';

                        var fase_id         = $('#fase_id option:selected').val();
                        var sub_fase        = $('#sub_fase option:selected').val();
                        var employee_id     = $('#employee_id option:selected').val();
                        var in_tugas        = $('#in_tugas').val();
                        var in_keterangan   = $('#in_keterangan').val();
                        var date_end        = $('#date_end').val();
                        var date_start      = $('#date_start').val();

                        if(fase_id*1 <= 0){
                            //console.error('waduh fase_id*1 > 0 yaitu: ' + fase_id);
                            error += '<div> <b>Perintah Kerja</b> Wajib di isi... </div>'
                        }

                        if(sub_fase*1 <= 0){
                            //console.error('waduh sub_fase*1 > 0 yaitu: ' + sub_fase);
                            error += '<div> <b>Sub Pekerjaan</b> Wajib di isi... </div>'
                        }

                        if(employee_id*1 <= 0){
                            console.error('waduh employee_id*1 > 0 yaitu: ' + employee_id);
                            error += '<div> <b>Pelaksana</b> Wajib di pilih... </div>'
                        }

                        if(in_tugas==''){
                            //console.error('waduh in_tugas kosong');
                            error += '<div> <b>Tugas</b> Wajib di isi... </div>'
                        }

                        if(in_keterangan==''){
                            //console.error('waduh in_keterangan kosong');
                            error += '<div> <b>Keterangan</b> Wajib di isi... </div>'
                        }

                        if(date_end==''){
                            //console.error('waduh date_end kosong');
                            error += '<div> <b>Waktu Selesai</b> Wajib di isi... </div>'
                        }

                        if(date_start==''){
                            //console.error('waduh date_start kosong');
                            error += '<div> <b>Waktu Mulai</b> Wajib di isi... </div>'
                        }

                        if( error=='' ){
                            //bisa lanjut
                            eval(ev_aa);
                        }
                        else{
                            swal('isian kurang', error, 'error');
                        }
                    });
                }

                init_simpanTugas();

                top.$(\".selectpicker\").selectpicker('refresh');
                top.init_tab();

            </script>

            ";

        echo $workOrder;
    }

    public function daftar_tugas()
    {

        $cCode = $this->uri->segment(4);
        $pid = $prodID = $this->uri->segment(5);
        $load = $this->uri->segment(6);

        $colView = array(
            "dtime_start",
            "dtime_end",
//            "nama",
            "owner_nama",
            "produk_nama",
            "produk_paket_nama",
            "employee_nama",
            "nilai",
            "progress_id",
            "progress_percent",
        );

        $allowed=array();


        //region time work
        $this->load->model("Mdls/MdlTimWorkProject");
        $tw = new MdlTimWorkProject();
        $tw->addFilter("produk_id='$prodID'");
        $tempTeamWork = $tw->lookUpAll()->result();

        $listTeamWork = array();
        if (!empty($tempTeamWork)) {
            foreach ($tempTeamWork as $k => $ko) {
                $listTeamWork[$ko->employee_id] = $ko->employee_nama;
            }
        }

        //region timeline
        $this->load->model("MdlTasklistProject");
        $tl = new MdlTasklistProject();
        $tl->addFilter("produk_id='$prodID'");
//        $tl->addFilter("progress_id>1");

        foreach ($this->customAccesProject as $metode => $accessList) {
            if (in_array("MdlTasklistProject", $accessList)) {
                $allowed["create"] = true;
                $allowed["edit"] = true;
                $allowed["update"] = true;
                $allowed["delete"] = true;
            }
        }

        if(!isset($allowed["create"])){
            $tl->addFilter("employee_id=" . $this->session->login['id'] . "");
        }

        if (isset($_GET['order'])) {
            $ky = ($_GET['order'][0]['column'] - 1) * 1 > 0 ? (isset($colView[$_GET['order'][0]['column'] - 1]) ? $colView[$_GET['order'][0]['column'] - 1] : "dtime") : "id";
            $sr = $_GET['order'][0]['dir'];
            $this->db->order_by($ky, $sr);
        }
        else {
//            $this->db->order_by("dtime", "desc");
            $this->db->order_by("id", "desc");
        }

        $tempTaskList = $tl->lookUpAll()->result();
        $lastQuery = $this->db->last_query();
        //endregion

        $allowedEdit = array();

//        foreach ($this->customAccesProject as $metode => $accessList) {
//            if (in_array("MdlTasklistProject", $accessList)) {

        $allowedEdit["create"] = true;
        $allowedEdit["edit"] = true;
        $allowedEdit["update"] = true;
        $allowedEdit["delete"] = true;

//            }
//        }

        $ajax = array();
        $colNumView = array();
        if (!empty($tempTaskList)) {
            $num = 0;
            foreach ($tempTaskList as $r => $dt) {
                $progress_id = $dt->progress_id;
                $progress_percent = $dt->progress_percent;
                $scriptID = $dt->id . "-" . $dt->produk_id;
                $arrEmply = explode(",", $dt->employee_id);
                $num++;
                $ajax[$r] = array();
                $ajax[$r][] = $num;
                $colNumView = array();
                $colNumView[] = 'numbering';
                foreach ($colView as $cc) {
                    if ($cc == 'employee_nama') {
                        $ajax[$r][] = isset($dt->employee_nama) ? $dt->employee_nama  : "<span class='text-red text-bold'>belum ada pelaksana</span>";
                    }
                    else {
                        $ajax[$r][] = $dt->$cc;
                        $colNumView[] = $cc;
                    }
                }
                $action = array();
                if (count($allowedEdit) > 0) {
                    if (in_array($this->session->login['id'], $arrEmply)) {
                        foreach ($allowedEdit as $mm => $valm) {
                            $action[$mm] = array();
                            $opt = '';
                            $typBtn = 'n';
                            switch ($mm) {
                                case "create":
                                    $action[$mm]['bclass'] = $progress_id > 1 && $progress_id < 3 ? "bg-pupus" : "bg-gray";
                                    $action[$mm]['dataicon'] = "fa fa-plus";
                                    $action[$mm]['btnDisabled'] = $progress_id > 1 && $progress_id < 3 ? "" : "disabled";
                                    $action[$mm]['label'] = "PENGERJAAN";
                                    $action[$mm]['txt'] = "";
                                    $action[$mm]['onclick'] = $progress_id > 1 && $progress_id < 3 ? " onclick=\"fnTaskList.create(this)\" " : "";
                                    $action[$mm]['onclick_broke'] = "";
                                    $action[$mm]['opt'] = "";
                                    $action[$mm]['typBtn'] = $typBtn;
                                    $action[$mm]['scriptID'] = $scriptID;
                                    $action[$mm]['progress_id'] = $progress_id;
                                    break;
                                case "update":
                                    if ($progress_id == 1) {
                                        $action[$mm]['bclass'] = "btn-warning";
                                        $action[$mm]['dataicon'] = "fa fa-send";
                                        $action[$mm]['btnDisabled'] = "";
                                        $action[$mm]['label'] = "proses";
                                        $action[$mm]['txt'] = "";
                                        $action[$mm]['onclick'] = " onclick=\"fnTaskList.update(this, 2)\" ";
                                        $action[$mm]['onclick_broke'] = "";
                                        $action[$mm]['opt'] = " data-progress='$progress_id' ";
                                        $action[$mm]['typBtn'] = '';
                                        $action[$mm]['scriptID'] = $scriptID;
                                        $action[$mm]['progress_id'] = $progress_id;
                                    }
                                    else {
                                        $action[$mm]['bclass'] = "bg-gray hidden";
                                        $action[$mm]['dataicon'] = "fa fa-send";
                                        $action[$mm]['btnDisabled'] = "disabled";
                                        $action[$mm]['label'] = "proses";
                                        $action[$mm]['txt'] = "";
                                        $action[$mm]['onclick'] = "";
                                        $action[$mm]['onclick_broke'] = "";
                                        $action[$mm]['opt'] = " data-progress='$progress_id' ";
                                        $action[$mm]['typBtn'] = '';
                                        $action[$mm]['scriptID'] = $scriptID;
                                        $action[$mm]['progress_id'] = $progress_id;
                                    }
                                    break;
                                case "delete":
                                    $action[$mm]['bclass'] = $progress_id > 1 ? "bg-gray" : "btn-danger";
                                    $action[$mm]['dataicon'] = "fa fa-trash";
                                    $action[$mm]['btnDisabled'] = $progress_id > 1 ? "disabled" : "";
                                    $action[$mm]['label'] = "";
                                    $action[$mm]['txt'] = "";
                                    $action[$mm]['onclick'] = $progress_id > 1 ? "" : " sonclick=\"fnTaskList.delete(this)\" ";
                                    $action[$mm]['onclick_broke'] = "";
                                    $action[$mm]['opt'] = "";
                                    $action[$mm]['typBtn'] = $typBtn;
                                    $action[$mm]['scriptID'] = $scriptID;
                                    $action[$mm]['progress_id'] = $progress_id;
                                    break;
                                default:
                                    $action[$mm]['bclass'] = "bg-biru hidden";
                                    $action[$mm]['dataicon'] = "fa fa-eye";
                                    $action[$mm]['btnDisabled'] = "";
                                    $action[$mm]['label'] = "view";
                                    $action[$mm]['txt'] = "";
                                    $action[$mm]['onclick'] = " onclick=\"fnTaskList.view(this)\" ";
                                    $action[$mm]['onclick_broke'] = "";
                                    $action[$mm]['opt'] = "";
                                    $action[$mm]['typBtn'] = $typBtn;
                                    $action[$mm]['scriptID'] = $scriptID;
                                    $action[$mm]['progress_id'] = $progress_id;
                                    break;
                            }
                        }
                    }

                    if ($progress_id * 1 > 0) {
                        $action['print']['bclass'] = "bg-gray";
                        $action['print']['dataicon'] = "fa fa-print";
                        $action['print']['btnDisabled'] = "";
                        $action['print']['label'] = "PRINT SPK";
                        $action['print']['txt'] = "";
                        $action['print']['onclick'] = " onclick=\"fnTaskList.print(this)\" ";
                        $action['print']['onclick_broke'] = "";
                        $action['print']['opt'] = "";
                        $action['print']['typBtn'] = $typBtn;
                        $action['print']['scriptID'] = $scriptID;
                        $action['print']['progress_id'] = $progress_id;
                    }
                    if ($progress_id * 1 == 1 && $dt->oleh_id == $this->session->login['id']) {
                        $action['batal']['bclass'] = "bg-danger";
                        $action['batal']['dataicon'] = "fa fa-trash";
                        $action['batal']['btnDisabled'] = "";
                        $action['batal']['label'] = "batalkan";
                        $action['batal']['txt'] = "";
                        $action['batal']['onclick'] = " onclick=\"fnTaskList.batal(this)\" ";
                        $action['batal']['onclick_broke'] = "";
                        $action['batal']['opt'] = "";
                        $action['batal']['typBtn'] = $typBtn;
                        $action['batal']['scriptID'] = $scriptID;
                        $action['batal']['progress_id'] = $progress_id;
                    }
                    elseif ($progress_id * 1 == 2 && $dt->employee_id != $this->session->login['id']) {
                        $action['batal']['bclass'] = "bg-info";
                        $action['batal']['dataicon'] = "fa fa-send";
                        $action['batal']['btnDisabled'] = "";
                        $action['batal']['label'] = "by: " . $dt->employee_nama;
                        $action['batal']['txt'] = "";
                        $action['batal']['onclick'] = " onclick=\"fnTaskList.batal(this)\" ";
                        $action['batal']['onclick_broke'] = "";
                        $action['batal']['opt'] = "";
                        $action['batal']['typBtn'] = $typBtn;
                        $action['batal']['scriptID'] = $scriptID;
                        $action['batal']['progress_id'] = $progress_id;
                    }
                    elseif ($progress_id * 1 == 3 && $dt->employee_id != $this->session->login['id']) {

                    }
                    else {
                        $pending_task[] = $dt;
                    }

                    if (count($this->customAccesProject) > 0 && in_array("MdlTimWorkProject", $this->customAccesProject["create"])) {

                        $action["batal"]['bclass'] = $progress_id > 1 && $progress_id < 3 ? "bg-red" : "bg-gray";
                        $action['batal']['dataicon'] = "fa fa-trash";
                        $action["batal"]['btnDisabled'] = $progress_id > 1 && $progress_id < 3 ? "" : "disabled";
                        $action['batal']['label'] = "PEMBATALAN";
                        $action['batal']['txt'] = "";
                        $action['batal']['onclick'] = $progress_id > 1 && $progress_id < 3 ? " onclick=\"fnTaskList.batal(this)\" " : "";
                        $action['batal']['onclick_broke'] = "";
                        $action['batal']['opt'] = "";
                        $action['batal']['typBtn'] = $typBtn;
                        $action['batal']['scriptID'] = $scriptID;
                        $action['batal']['progress_id'] = $progress_id;

                        $action["create"]['bclass'] = $progress_id > 1 && $progress_id < 3 ? "btn-info" : "bg-gray";
                        $action["create"]['dataicon'] = "fa fa-plus";
                        $action["create"]['btnDisabled'] = $progress_id > 1 && $progress_id < 3 ? "" : "disabled";
                        $action["create"]['label'] = "PEMANTAUAN";
                        $action["create"]['txt'] = "";
                        $action["create"]['onclick'] = $progress_id > 1 && $progress_id < 3 ? " onclick=\"fnTaskList.create(this)\" " : "";
                        $action["create"]['onclick_broke'] = "";
                        $action["create"]['opt'] = "";
                        $action["create"]['typBtn'] = $typBtn;
                        $action["create"]['scriptID'] = $scriptID;
                        $action["create"]['progress_id'] = $progress_id;

                        if ($progress_percent * 1 < 100) {
                            $action['followup']['bclass'] = "bg-default text-bold";
                            $action['followup']['dataicon'] = "fa fa-star";
                            $action['followup']['btnDisabled'] = "";
                            $action['followup']['label'] = "Quality Control";
                            $action['followup']['txt'] = "";
                            $action['followup']['onclick'] = " javascript:void(0) ";
                            $action['followup']['onclick_broke'] = "";
                            $action['followup']['opt'] = "";
                            $action['followup']['typBtn'] = $typBtn;
                            $action['followup']['scriptID'] = $scriptID;
                            $action['followup']['progress_id'] = $progress_id;
                        }
                        if ($progress_percent * 1 == 100) {
                            $action['followup']['bclass'] = "bg-violet text-bold text-grey";
                            $action['followup']['dataicon'] = "fa fa-star";
                            $action['followup']['btnDisabled'] = "";
                            $action['followup']['label'] = "Quality Control";
                            $action['followup']['txt'] = "";
                            $action['followup']['onclick'] = " onclick=\"fnTaskList.create(this)\" ";
                            $action['followup']['onclick_broke'] = "";
                            $action['followup']['opt'] = "";
                            $action['followup']['typBtn'] = $typBtn;
                            $action['followup']['scriptID'] = $scriptID;
                            $action['followup']['progress_id'] = $progress_id;
                        }
                        if ($progress_id * 1 == 3) {
                            $action['followup']['bclass'] = "bg-gray text-bold";
                            $action['followup']['dataicon'] = "fa fa-check text-yellow";
                            $action['followup']['btnDisabled'] = "";
                            $action['followup']['label'] = "Quality Control";
                            $action['followup']['txt'] = "";
                            $action['followup']['onclick'] = "";
                            $action['followup']['onclick_broke'] = "";
                            $action['followup']['opt'] = "";
                            $action['followup']['typBtn'] = $typBtn;
                            $action['followup']['scriptID'] = $scriptID;
                            $action['followup']['progress_id'] = $progress_id;
                        }
                        if ($progress_id*1>0){
                            $action[$mm]['bclass'] = $progress_id > 2 ? "bg-gray" : "bg-olive";
                            $action[$mm]['dataicon'] = "fa fa-pencil";
                            $action[$mm]['btnDisabled'] = $progress_id > 2 ? "disabled" : "";
                            $action[$mm]['label'] = "edit";
                            $action[$mm]['txt'] = "";
                            $action[$mm]['onclick'] = $progress_id > 2 ? "" : " onclick=\"fnTaskList.edit(this)\" ";
                            $action[$mm]['onclick_broke'] = "";
                            $action[$mm]['opt'] = "";
                            $action[$mm]['typBtn'] = $typBtn;
                            $action[$mm]['scriptID'] = $scriptID;
                            $action[$mm]['progress_id'] = $progress_id;
                        }
                    }
                    $ajax[$r][] = $action;
                }
                else {
                    $action['print']['bclass'] = "bg-gray";
                    $action['print']['dataicon'] = "fa fa-print";
                    $action['print']['btnDisabled'] = "";
                    $action['print']['label'] = "Print Pre SPK";
                    $action['print']['txt'] = "";
                    $action['print']['onclick'] = " onclick=\"fnTaskList.print(this,'pre')\" ";
                    $action['print']['onclick_broke'] = "";
                    $action['print']['opt'] = "";
                    $action['print']['typBtn'] = $typBtn;
                    $action['print']['scriptID'] = $scriptID;
                    $ajax[$r][] = $action;
                }
            }
        }
        $res = array(
//            "draw"  =>  1,
            "data" => $ajax,
            "pendingtask" => $pending_task,
            "arrListNama" => isset($arrListNama) ? $arrListNama : "",
            "listTeamWork" => $listTeamWork,
            "txtNama" => isset($txtNama) ? $txtNama : "",
            "lastQuery" => $lastQuery,
            "customAccesProject" => $this->customAccesProject,
            "load" => $load,
        );

        echo json_encode($res);
    }

    public function print_tasklist()
    {

        $ids = $this->uri->segment(4);
        $prodID = $this->uri->segment(5);
        $pre = $this->uri->segment(6);

        //  region company profile
        $this->load->model("Mdls/MdlCompany");
        $mc = New MdlCompany();
        $arrTmpCompany = $mc->lookupAll()->result();
        $arrCompanyProfile = array();
        if (sizeof($arrTmpCompany) > 0) {
            foreach ($arrTmpCompany as $cSpec) {
                foreach ($cSpec as $key => $val) {
                    $arrCompanyProfile['companyProfile_' . $key] = $val;
                }
            }
        }
        //  endregion

        $receiptGlobalConfig = $this->config->item('receiptGlobal_config') != null ? $this->config->item('receiptGlobal_config') : array();
        $companyProfile = array();
        if (sizeof($receiptGlobalConfig) > 0) {
            $companyStr = $receiptGlobalConfig['companyProfile'];
            foreach ($arrCompanyProfile as $key => $val) {
                if (!is_array($val)) {
                    $companyStr = str_replace("{" . $key . "}", $val, $companyStr);
                }
            }
            $companyProfile = $companyStr . " <span class='text-white no-print'>$trID</span>";
        }
        $companyProfile_alias = $arrCompanyProfile['companyProfile_alias'];

        // arrPrint();
        //region timeline
        $this->load->model("Mdls/MdlTasklistProject");
        $tl = new MdlTasklistProject();
        $tl->addFilter("id='$ids'");
        $tl->addFilter("produk_id='$prodID'");
        $tempTaskList = $tl->lookUpAll()->result();

        $this->load->model("Mdls/MdlProdukProject");
        $pp = new MdlProdukProject();
        $pp->addFilter("id='$prodID'");
        $produkProject = $pp->lookUpAll()->result();

//        arrPrint($produkProject);
//        arrPrint($tempTaskList);
        // matiHere(__LINE__);

        if (!empty($tempTaskList)) {
            $fase_id = $tempTaskList[0]->fase_id;
            $nomer = $tempTaskList[0]->no_spk;
//            $sub_fase_id = $tempTaskList[0]->sub_fase_id;
        }

        //region total produk workorder
        $this->load->model("Mdls/MdlProjectKomposisiWorkorderSub");
        $pl = new MdlProjectKomposisiWorkorderSub();
        $pl->setFilters(array());
        $pl->addFilter("produk_id='$prodID'");
        $pl->addFilter("fase_id='$fase_id'");
        $pl->addFilter("no_spk='$nomer'");
//        $pl->addFilter("status='1'");
//        $pl->addFilter("trash='0'");
        $pl->addFilter("jenis in ('produk','item_komposit')");
        $tempProduk = $pl->lookUpAll()->result();
        showLast_query("hitam");
        arrPrint($tempProduk);
        //endregion total produk workorder

        //region total supplies dari rab sub
        $this->load->model("Mdls/MdlProjectKomponenBiayaDetailsRabSub");
        $pls = new MdlProjectKomponenBiayaDetailsRabSub();
        $pls->addFilter("project_id='$prodID'");
        $pls->addFilter("fase_id='$fase_id'");
        $pls->addFilter("no_spk='$nomer'");
        $pls->addFilter("jenis='supplies'");
        $tempSupplies = $pls->lookUpAll()->result();

        $arrSupplies = array();
        if(!empty($tempSupplies)){
            foreach($tempSupplies as $row){
                if(!isset($arrSupplies[$row->biaya_dasar_id])){
                    $arrSupplies[$row->biaya_dasar_id] = (array)$row;
                }
                if(!isset($arrSupplies[$row->biaya_dasar_id]['total'])){
                    $arrSupplies[$row->biaya_dasar_id]['total'] = 0;
                }
                $arrSupplies[$row->biaya_dasar_id]['total'] += ($row->jml*1);
            }
        }
        //endregion total supplies dari rab sub

        // arrPrintWebs($tempProduk);
        // arrPrintWebs($arrSupplies);
        // arrPrintWebs($tempTaskList);
// arrPrintKuning($tempTaskList[0]->dtime_end);
        $data = array(
            "mode" => "print_tasklist",
            "title" => "SPK ($nomer)",
            "titleHeader" => "<div style='min-height: 30px;' id='title'>SURAT PERINTAH KERJA<br>(SPK)</div>
<div style='min-height: 30px;' id='title_alt' class='hidden text-uppercase'>SURAT Jalan Pengiriman<br>(SJP)</div>",
            "companyProfile" => $companyProfile,
            "companyProfile_alias" => $companyProfile_alias,
            "produkProject" => $produkProject,
            "tempTaskList" => $tempTaskList,
            "produk" => $tempProduk,
            "supplies" => $arrSupplies,
            "pre" => $pre != '' ? 1 : 0,
            "label_pekerjaan" => $tempTaskList[0]->produk_nama,
            "tujuan" => $tempTaskList[0]->produk_nama,
            "produk_nama" => $tempTaskList[0]->produk_nama,
            "nama" => $tempTaskList[0]->nama,
            "nomer_int_spk" => $nomer,
            "alamat_detail_pekerjaan" => $tempTaskList[0]->nilai,
            "masa_pelaksanaan_detail_pekerjaan" => $tempTaskList[0]->dtime_start != $tempTaskList[0]->dtime_end ? $tempTaskList[0]->dtime_start ." s/d ". $tempTaskList[0]->dtime_end : $tempTaskList[0]->dtime_end,
            "nilai_pekerjaan" => $tempTaskList[0]->nilai_sub_fase,
            "nama_pihak1" => $tempTaskList[0]->oleh_nama,
            "nama_pihak2" => $tempTaskList[0]->employee_nama,
            "owner_nama" => $tempTaskList[0]->owner_nama,
            "tanggal_pelaksana" => $tempTaskList[0]->dtime_start,
            "tanggal_jatuh_tempo" => $tempTaskList[0]->dtime_end,
            "barcodeSPK" => $nomer,
        );

        $this->load->view("printing", $data);
    }

    public function addTask()
    {

        $mdlName    = $this->uri->segment(4);
        $cCode      = $this->uri->segment(5);
        $produkID   = $this->uri->segment(6);
        $prevData   = isset($_SESSION[$cCode][$produkID]) ? $_SESSION[$cCode][$produkID] : matiHere("GAGAL MEMBUAT SESSION, SILAHKAN REFRESH BROWSER DAN MEMULAI KEMBALI.");

        if($_REQUEST['produk_paket_id']=="" || $_REQUEST['produk_paket_nama']==""){
            matiHere("PEMILIHAN PAKET BELUM SESUAI");
        }

        $produk_paket_id = $_REQUEST['produk_paket_id'];
        $produk_paket_nama = trim($_REQUEST['produk_paket_nama']);

        $this->load->model("Mdls/" . "MdlProdukProject");
        $pr = new MdlProdukProject();
        $pr->addFilter("id='$produkID'");
        $pr->addFilter("gen_tasklist='0'");
        $tmp = $pr->lookUpAll()->result();

        if(!empty($tmp)){
            $dTask = (array)$tmp[0];
        }

        $this->db->trans_start();

        //MdlProjectKomposisiWorkorder
        $this->load->model("Mdls/" . "MdlProjectKomposisiWorkorder");
        $kompWO = new MdlProjectKomposisiWorkorder();
        $kompWO->setFilters(array());
        $kompWO->addFilter("status='1'");
        $kompWO->addFilter("trash='0'");
        $kompWO->addFilter("produk_id='$produkID'");
        $kompWO->addFilter("jenis_transaksi='5582'");
//        $kompWO->addFilter("jenis='jual'");
        $tmpKompOrder = $kompWO->lookUpAll()->result();

        $arrKomposisiWO = array();
        if (!empty($tmpKompOrder)) {
            foreach ($tmpKompOrder as $kr => $rWo) {
                $arrKomposisiWO[$rWo->jenis][$rWo->produk_id][$rWo->fase_id][] = $rWo;
            }
        }

        //MdlProjectKomposisiWorkorderSub
        $this->load->model("Mdls/" . "MdlProjectKomposisiWorkorderSub");
        $kompSubWO = new MdlProjectKomposisiWorkorderSub();
        $kompSubWO->setFilters(array());
        $kompSubWO->addFilter("status='1'");
        $kompSubWO->addFilter("trash='0'");
        $kompSubWO->addFilter("produk_id='$produkID'");
        $kompSubWO->addFilter("jenis_transaksi='5582'");
//        $kompSubWO->addFilter("jenis='jual'");
        $tmpKompSubOrder = $kompSubWO->lookUpAll()->result();


        //biaya details
        $this->load->model("Mdls/" . "MdlProjectKomponenBiayaDetails");
        $by = new MdlProjectKomponenBiayaDetails();
        $arrTmpByDetails = $by->lookUpAll()->result();
        $arrByDetails = array();
        if(!empty($arrTmpByDetails)){
            foreach($arrTmpByDetails as $byData){
                $by_id = $byData->biaya_id;
                $by_dsr_id = $byData->biaya_dasar_id;
                $arrByDetails[$by_id][$by_dsr_id] = (array)$byData;
            }
        }
        //biaya details

//        showlast_query("hitam");

        $byDataDetails=array();
        $this->load->model("Mdls/" . "MdlProjectKomponenBiayaDetailsRab");
        $byCr = new MdlProjectKomponenBiayaDetailsRab();
        $byCr->addFilter("project_id='$produkID'");
        $byCr->addFilter("fase_id='".$prevData['fase_id']."'");
        $arrTmpByData = $byCr->lookUpAll()->result();

        if(!empty($arrTmpByData)){
            foreach($arrTmpByData as $by){
                $byDataDetails[$by->biaya_id][$by->biaya_dasar_id] = (array)$by;
            }
        }

        $lastQ = $this->db->last_query();
        $arrKomposisiSubWO = array();
        if (!empty($tmpKompSubOrder)) {
            foreach ($tmpKompSubOrder as $kr => $rWo) {
                $jns = $rWo->jenis == "item_komposit" ? "produk" : $rWo->jenis;
                $arrKomposisiSubWO[$jns][$rWo->produk_id][$rWo->fase_id][] = $rWo;
            }
        }
//        arrPrint($arrKomposisiSubWO);
        $arrAnehCoookkk = $tmpKompSubOrder;
        $arrAnehCoookkkQ = $lastQ;

        //sync produk
        $arrBiayaDetailsSync=array();
        $arrProdukSync=array();
        $arrProdukUpdSync=array();
        $totalProdukSubFase=0;
        $totalProdukFase=0;
        foreach($arrKomposisiSubWO['produk'][$produkID][$prevData['fase_id']] as $ky => $prd){
            if(isset($prevData['produk_fase_id']['produk'][$prd->produk_dasar_id])){
                $jml = $prevData['produk_fase_id']['produk'][$prd->produk_dasar_id]['values'];
                $origID = $prd->id;

                $arrProdukUpdSync[$origID]['debet'] = $prd->debet;
                $arrProdukUpdSync[$origID]['qty_debet'] = $prd->qty_debet;
                $arrProdukUpdSync[$origID]['qty_kredit'] = $prd->qty_kredit + ($jml*1);
                $arrProdukUpdSync[$origID]['qty_saldo'] = $prd->qty_saldo - ($jml*1);
                $arrProdukUpdSync[$origID]['kredit'] = $prd->kredit + ($jml*$prd->harga);
                $arrProdukUpdSync[$origID]['saldo'] = $prd->saldo - ($jml*$prd->harga);

                $arrProdukSync['produk'][$prd->produk_dasar_id] = (array)$prd;
                $arrProdukSync['produk'][$prd->produk_dasar_id]['jml'] = $jml;
                $arrProdukSync['produk'][$prd->produk_dasar_id]['debet'] = $jml*$prd->harga;
                $arrProdukSync['produk'][$prd->produk_dasar_id]['qty_debet'] = $jml;
                $arrProdukSync['produk'][$prd->produk_dasar_id]['qty_kredit'] = 0;
                $arrProdukSync['produk'][$prd->produk_dasar_id]['qty_saldo'] = $jml;
                $arrProdukSync['produk'][$prd->produk_dasar_id]['saldo'] = $jml*$prd->harga;
                $arrProdukSync['produk'][$prd->produk_dasar_id]['dtime'] = date("Y-m-d H:i:s");
                $arrProdukSync['produk'][$prd->produk_dasar_id]['last_update'] = date("Y-m-d H:i:s");
                $arrProdukSync['produk'][$prd->produk_dasar_id]['jenis_transaksi'] = "sub_wo";

                unset($arrProdukSync['produk'][$prd->produk_dasar_id]['id']);
                $totalProdukSubFase += $jml*$prd->harga;
            }

            $totalProdukFase += $prd->jml *  $prd->harga;
        }
        foreach($arrKomposisiSubWO['biaya'][$produkID][$prevData['fase_id']] as $ky => $prd){
            $ketJns = isset($prd->keterangan) && $prd->keterangan != "" ? " (".$prd->keterangan.")" : "";

            if(isset($prevData['produk_fase_id']['biaya'][$prd->produk_dasar_id.$ketJns])){
                $jml = $prevData['produk_fase_id']['biaya'][$prd->produk_dasar_id.$ketJns]['values'];
                $origID = $prd->id;
//                $arrProdukUpdSync[$origID]['id'] = $origID;
//                $arrProdukUpdSync[$origID]['produk_dasar_id'] = $prd->produk_dasar_id;
//                arrPrintWebs($prd);

                if(isset($prd->keterangan) && $prd->keterangan !=""){
                    $arrProdukUpdSync[$origID]['ket'] = $prd->keterangan;
                }

                $arrProdukUpdSync[$origID]['debet'] = $prd->debet;
                $arrProdukUpdSync[$origID]['qty_debet'] = $prd->qty_debet;
                $arrProdukUpdSync[$origID]['qty_kredit'] = $prd->qty_kredit + ($jml*1);
                $arrProdukUpdSync[$origID]['qty_saldo'] = $prd->qty_saldo - ($jml*1);
                $arrProdukUpdSync[$origID]['kredit'] = $prd->kredit + ($jml*$prd->harga);
                $arrProdukUpdSync[$origID]['saldo'] = $prd->saldo - ($jml*$prd->harga);

//                unset($prd->id);
                $arrProdukSync['biaya'][$prd->produk_dasar_id.$ketJns] = (array)$prd;
                $arrProdukSync['biaya'][$prd->produk_dasar_id.$ketJns]['jml'] = $jml;
                $arrProdukSync['biaya'][$prd->produk_dasar_id.$ketJns]['debet'] = $jml*$prd->harga;
                $arrProdukSync['biaya'][$prd->produk_dasar_id.$ketJns]['qty_debet'] = $jml;
                $arrProdukSync['biaya'][$prd->produk_dasar_id.$ketJns]['qty_kredit'] = 0;
                $arrProdukSync['biaya'][$prd->produk_dasar_id.$ketJns]['qty_saldo'] = $jml;
                $arrProdukSync['biaya'][$prd->produk_dasar_id.$ketJns]['saldo'] = $jml*$prd->harga;
                $arrProdukSync['biaya'][$prd->produk_dasar_id.$ketJns]['dtime'] = date("Y-m-d H:i:s");
                $arrProdukSync['biaya'][$prd->produk_dasar_id.$ketJns]['last_update'] = date("Y-m-d H:i:s");
                $arrProdukSync['biaya'][$prd->produk_dasar_id.$ketJns]['jenis_transaksi'] = "sub_wo";

                //biaya details
                if(!empty($byDataDetails)){
                    foreach($byDataDetails as $by_id => $byDetails){
                        if($by_id==$prd->produk_dasar_id.$ketJns){
                            foreach($byDetails as $by_dsr_id => $byData_1){
                                $arrBiayaDetailsSync['biaya_details'][$prd->produk_dasar_id.$ketJns][$byData_1["biaya_dasar_id"]] = $byData_1;
                                $arrBiayaDetailsSync['biaya_details'][$prd->produk_dasar_id.$ketJns][$byData_1["biaya_dasar_id"]]['jml'] = $byData_1['jml_dasar']*$jml;
                                $arrBiayaDetailsSync['biaya_details'][$prd->produk_dasar_id.$ketJns][$byData_1["biaya_dasar_id"]]['harga'] = $byData_1['harga'];
                                $arrBiayaDetailsSync['biaya_details'][$prd->produk_dasar_id.$ketJns][$byData_1["biaya_dasar_id"]]['debet'] = ($byData_1['jml_dasar']*$jml)*$byData_1['harga'];
                                $arrBiayaDetailsSync['biaya_details'][$prd->produk_dasar_id.$ketJns][$byData_1["biaya_dasar_id"]]['jml_debet'] = $byData_1['jml_dasar']*$jml;
                                $arrBiayaDetailsSync['biaya_details'][$prd->produk_dasar_id.$ketJns][$byData_1["biaya_dasar_id"]]['jml_kredit'] = 0;
                                $arrBiayaDetailsSync['biaya_details'][$prd->produk_dasar_id.$ketJns][$byData_1["biaya_dasar_id"]]['jml_saldo'] = $byData_1['jml_dasar']*$jml;
                                $arrBiayaDetailsSync['biaya_details'][$prd->produk_dasar_id.$ketJns][$byData_1["biaya_dasar_id"]]['saldo'] = ($byData_1['jml_dasar']*$jml)*$byData_1['harga'];
                                $arrBiayaDetailsSync['biaya_details'][$prd->produk_dasar_id.$ketJns][$byData_1["biaya_dasar_id"]]['dtime'] = date("Y-m-d H:i:s");
                                $arrBiayaDetailsSync['biaya_details'][$prd->produk_dasar_id.$ketJns][$byData_1["biaya_dasar_id"]]['last_update'] = date("Y-m-d H:i:s");
                                $arrBiayaDetailsSync['biaya_details'][$prd->produk_dasar_id.$ketJns][$byData_1["biaya_dasar_id"]]['jenis_transaksi'] = "sub_wo";
                            }
                        }
                    }
                }
                //biaya details

                unset($arrProdukSync['biaya'][$prd->produk_dasar_id.$ketJns]['id']);
                $totalProdukSubFase += $jml*$prd->harga;
            }
            $totalProdukFase += $prd->jml *  $prd->harga;
        }

        $persentag = $totalProdukSubFase / $totalProdukFase * 100;

//        die(json_encode($byDataDetails));
//        die(json_encode($arrBiayaDetailsSync));
        //total biaya dari persentase produk
//        $saldoBiaya=0;
//        $totalBiaya=0;
//        foreach($arrKomposisiSubWO['biaya'][$produkID][$prevData['fase_id']] as $ky => $prd){
//            $saldoBiaya += $prd->saldo;
//        }
//        $totalBiaya = ($saldoBiaya*$persentagb4Biaya)/100;
//        $persentag = ($totalProdukSubFase+$totalBiaya) / $totalProdukFase * 100;

        $arrMaterial = array(
            "dtime" => date("Y-m-d H:i:s"),
            "dtime_end" => $prevData['dtime_end'],
            "dtime_start" => $prevData['dtime_start'],
            "no_kontrak" => $dTask['nomor_kontrak'],
            "tgl_kontrak" => $dTask['tanggal_kontrak'],
            "employee_id" => $prevData['employee_id'],
            "employee_nama" => $prevData['employee_nama'],
            "cabang_id" => $this->session->login['cabang_id'],
            "fase_id" => $prevData['fase_id'],
            "material" => 1,
            "nama" => $arrKomposisiWO['jual'][$produkID][$prevData['fase_id']][0]->produk_nama,
            "nilai_sub_fase" => $totalProdukSubFase,
            "nilai_kontrak" => $arrKomposisiWO['jual'][$produkID][$prevData['fase_id']][0]->harga,
            "persen_sub" => $persentag,
            "oleh_id" => $this->session->login["id"],
            "oleh_nama" => $this->session->login["nama"],
            "produk_id" => $produkID,
            "progress_id" => 2,
            "produk_nama" => $prevData['nama'],
            "owner_nama" => $prevData['owner'],
            "nilai" => $prevData['nilai'],
            "sub_fase_id" => $prevData['sub_fase_id'][0],
            "produk_paket_id" => $produk_paket_id,
            "produk_paket_nama" => $produk_paket_nama,
//            "produk_fase_id" => $prevData['produk_fase_id'],
        );

        $arrMaterial['no_pre_spk'] = $this->numbering_new("SPK", "PRE-SPK", $arrMaterial);
        $arrMaterial['nomer'] = $this->numbering("SPK", "PRESPK", $arrMaterial);
        $arrMaterial["no_spk"] = $this->numbering_new("SPK", "SPK-INT", $arrMaterial);

        foreach($prevData['produk_fase_id'] as $jns => $prd){
            foreach($prd as $pdi => $blaa){
                if(isset($arrProdukSync[$jns][$pdi])){
                    $arrProdukSync[$jns][$pdi]['employee_id']   = $arrMaterial["employee_id"];
                    $arrProdukSync[$jns][$pdi]['no_spk']     = $arrMaterial["no_spk"];
                    $arrProdukSync[$jns][$pdi]['owner_nama']    = $prevData['owner'];
                    $arrProdukSync[$jns][$pdi]['sub_fase_nama'] = $prevData['nama'];
                    $arrProdukSync[$jns][$pdi]['keterangan']    = $prevData['nilai'];
                    $arrProdukSync[$jns][$pdi]['sub_fase_id']   = $prevData['sub_fase_id'][0];
            }
        }
        }

        $this->load->model("Mdls/MdlProjectKomposisiWorkorderSub");
        $mWo = new MdlProjectKomposisiWorkorderSub();
        $this->load->model("Mdls/" . "MdlProjectKomponenBiayaDetailsRabSub");
        $byCrSub = new MdlProjectKomponenBiayaDetailsRabSub();

        if(!empty($arrProdukSync)){
            foreach($arrProdukSync as $jn => $pkData_0){
                foreach($pkData_0 as $pk => $pkData){
                    $insertMWo = $mWo->addData((array)$pkData) or matiHere("<r>Gagal menyimpan produk</r> <br>" . base64_encode(json_encode($pkData)) . "<br>" . $this->db->last_query());
                        showLast_query("biru");
                }
            }

            if(!empty($arrBiayaDetailsSync)){
                foreach($arrBiayaDetailsSync as $jn => $byData_0){
                    foreach($byData_0 as $by => $byData_1){
                        foreach($byData_1 as $by_dsr => $byData){
                            $byCreateSub = $byData;
                            $byCreateSub["no_spk"] = $arrMaterial["no_spk"];
                            $byCreateSub["sub_fase_id"] = $prevData['sub_fase_id'][0];
                            $byCreateSub["sub_fase_nama"] = $prevData['nama'];
//                            $byCreateSub["link_id"] = $byCreateSub["id"];
                            unset($byCreateSub["id"]);
                            $insertMWo = $byCrSub->addData($byCreateSub) or matiHere("<r>Gagal menyimpan produk biaya details komponen</r> <br>" . base64_encode(json_encode($pkData)) . "<br>" . $this->db->last_query());
                            showLast_query("biru");

//                            if( $byCreateSub["jenis"] == "supplies"){
//
//                                arrPrint($byCreateSub);
//                                matiHere("supplies LINE: ". __LINE__);
//                            }
                        }
                    }
                }
            }
        }
        if(!empty($arrProdukUpdSync)){
            foreach($arrProdukUpdSync as $pd => $pdData){
                if(isset($pdData['ket'])){
                    $where = array(
                        "id" => $pd,
                            "keterangan" => $pdData['ket'],
                    );
                }
                else{
                    $where = array(
                        "id" => $pd,
                    );
                }
                unset($pdData['ket']);
                $mWo->setFilters(array());
                $mWo->updateData($where, $pdData) or matiHere("gagal memperbaharui data produk sub WO LINE: " . __LINE__);
                showLast_query("kuning");
            }
        }

        $debug = array(
//            "arrKomposisiSubWO"=> $arrKomposisiSubWO,
//            "prevData" => $prevData,
//            "arrProdukSync" => $arrProdukSync,
//            "arrMaterial" => $arrMaterial,
//            "totalProdukSubFase" => $totalProdukSubFase,
//            "mdlName" => $mdlName,
//            "arrProdukUpdSync" => $arrProdukUpdSync,
//            "lastQ" => $lastQ,
//            "arrAnehCoookkkQ" => $arrAnehCoookkkQ,
//            "arrAnehCoookkk" => $arrAnehCoookkk,
        );

//        arrPrint($_SESSION);
//        matiHere(json_encode($debug));
        $this->load->model("Mdls/" . $mdlName);
        $m = new $mdlName();
        $insertID = $m->addData($arrMaterial) or matiHere("<r>Gagal menyimpan tugas</r> <br>" . base64_encode(json_encode($arrMaterial)) . "<br>" . $this->db->last_query());
        showLast_query("biru");

        $this->load->model("Mdls/MdlTasklistProjectLog");
        $mLog = new MdlTasklistProjectLog();
        $logData = $arrMaterial;
        $logData['type'] = "create";
        $logData['person_id'] = $this->session->login["id"];
        $logData['person_nama'] = $this->session->login["nama"];
        $logID = $mLog->addData($logData) or matiHere("gagal menambahkan data*" . " | " . $this->db->last_query());
        showLast_query("biru");
        $logQuery = $this->db->last_query();

//        cekMerah("USER LOGIN");
//        arrPrintWebs($this->session->login);
//        matiHere("BELUM COMMIT");

        if ($insertID) {
            unset($_SESSION[$cCode]);
        }

        $isCommit = $this->db->trans_complete();

//        $result = array(
//            "arrMaterial" => $arrMaterial,
//            "insertID" => $insertID,
//            "prevData" => $prevData,
//        );
//        echo json_encode($result);
//        $this->load->model("Mdls/" . $mdlName);
//        $m = new $mdlName();
//        $insertID = $m->addData($prevData) or matiHere("<r>Gagal menyimpan tugas</r> <br>" . base64_encode(json_encode($prevData)) . "<br>" . $this->db->last_query());
        // //register ke daftar_tugas
//        $this->load->model("Mdls/" . "MdlProjectWorkOrderSub");
//        $ms = new MdlProjectWorkOrderSub();
//        $update = array(
//            "employee_id" => $prevData['employee_id'],
//            "daftar_tugas" => $insertID,
//            "daftar_tugas_dtime" => date("Y-m-d H:i:s"),
//        );
//        $where = array(
//            "id" => $prevData["sub_fase_id"],
//        );
//        $ms->setFilters(array());
//        $update = $ms->updateData($where, $update) or matiHere("gagal memperbaharui data LINE: " . __LINE__);
//        if (method_exists($m, "paramSyncNamaNama")) {
//            cekHitam("ada pram nama nama");
//            $syncNamaNamaMdls = method_exists($m, "paramSyncNamaNama") ? $m->paramSyncNamaNama() : mati_disini("paramSyncNamaNama belum terdifine");
//            arrPrint($syncNamaNamaMdls);
//            arrPrint($prevData);
//            foreach ($syncNamaNamaMdls as $syncNamaNamaMdl => $syncNamaNamaParams) {
//                $id_ygdisync = isset($prevData[$syncNamaNamaParams['id']]) ? $prevData[$syncNamaNamaParams['id']] : "";
//                cekUngu($syncNamaNamaParams['id']);
//                cekHitam($id_ygdisync);
//                if ($id_ygdisync > 0) {
//                    $m->syncNamaNama($id_ygdisync);
//                    cekBiru($this->db->last_query());
//                }
//            }
//        }
//        else {
//            cekHitam("gak aada pram nama nama");
//        }
//        //tambahin untuk timeline metode historycal
//        $this->load->model("Mdls/MdlTasklistProjectLog");
//        $mLog = new MdlTasklistProjectLog();
//        $logData = $prevData;
//        $logData['type'] = "create";
//        $logData['person_id'] = $this->session->login["id"];
//        $logData['person_nama'] = $this->session->login["nama"];
//        $logID = $mLog->addData($logData);
//        if (method_exists($mLog, "paramSyncNamaNama")) {
//            cekHitam("ada pram nama nama");
//            $syncNamaNamaMdls = method_exists($mLog, "paramSyncNamaNama") ? $mLog->paramSyncNamaNama() : mati_disini("paramSyncNamaNama belum terdifine");
//            foreach ($syncNamaNamaMdls as $syncNamaNamaMdl => $syncNamaNamaParams) {
//                $id_ygdisync = isset($prevData[$syncNamaNamaParams['id']]) ? $prevData[$syncNamaNamaParams['id']] : "";
//                if ($id_ygdisync > 0) {
//                    $mLog->syncNamaNama($id_ygdisync);
//                }
//            }
//        } else {
//            cekHitam("gak aada pram nama nama");
//        }
        // matiHere("BELUM COMMIT");
//        if ($insertID) {
//            unset($_SESSION[$cCode]);
//        }
//        $this->db->trans_complete();

        if($isCommit){
            echo "<script>
              if(top.document.getElementById('result')){
                top.BootstrapDialog.closeAll(); \n
                top.swal('BERHASIL','penugasan berhasil disimpan','success')
                top.$(\"a[href='#tab_tasklist']\").trigger('hide.bs.tab').trigger('shown.bs.tab');\n
              }
            </script>";
        }
        else{
            arrPrint($prevData);
            matiHere("GAGAL MENYIMPAN TUGAS, REFRESH BROWSER ANDA.");
        }

    }

    public function generatePreSPK(){
        //silahkan aktifkan  yg bawahklo mau auto build pre spk
    }

    public function generatePreSPK_matikandulu()
    {
        $mdlName = $this->uri->segment(4);
        $produkID = $this->uri->segment(5);
        $is_json = isset($_GET['is_json']) ? $_GET['is_json'] : 0;

        $login = $_POST;

        // MdlProjectWorkOrderSub project_workorder_sub
        $this->load->model("Mdls/" . "MdlProdukProject");
        $pr = new MdlProdukProject();
        $pr->addFilter("id='$produkID'");
        $pr->addFilter("gen_tasklist='0'");
        $tmp = $pr->lookUpAll()->result();

        $last_q = $this->db->last_query();
//        showLast_query("biru");
        if (!empty($tmp)) {
            if (count($tmp) == 1) {
                $this->db->trans_start();
                foreach ($tmp as $row) {
                    $pid = $row->id;
                    $nama = $row->nama;
                    $kontrak = $row->nomor_kontrak;
                    $spo = $row->transaksi_no;
                    $so = $row->quot_nomer;
                    $projectNo = $row->project_start_nomer;
                    $nilai_kontrak = $row->harga;
                    $start_dtime = $row->start_dtime;
                    $end_dtime = $row->end_dtime;
                    $tgl_kontrak = $row->tanggal_kontrak;

                    // MdlProjectWorkOrderSub project_workorder_sub
                    $this->load->model("Mdls/" . "MdlProjectWorkOrderSub");
                    $ms = new MdlProjectWorkOrderSub();
                    $ms->addFilter("produk_id='$pid'");
                    $tmpSubWorkOrder = $ms->lookUpAll()->result();

                    //MdlProjectKomposisiWorkorderSub
                    $this->load->model("Mdls/" . "MdlProjectKomposisiWorkorder");
                    $kompSubWO = new MdlProjectKomposisiWorkorder();
                    $kompSubWO->setFilters(array());
                    $kompSubWO->addFilter("produk_id='$pid'");
                    $kompSubWO->addFilter("jenis='jual'");
                    $tmpKompSubOrder = $kompSubWO->lookUpAll()->result();
                    $arrKomposisiSubWO = array();
                    if (!empty($tmpKompSubOrder)) {
                        foreach ($tmpKompSubOrder as $rWo) {
                            $arrKomposisiSubWO[$rWo->produk_id][$rWo->fase_id] = $rWo;
                        }
                    }
                    $total_persen = 0;
                    $listWorkOrder = array();
                    if (!empty($tmpSubWorkOrder)) {
                        foreach ($tmpSubWorkOrder as $rowWO) {
                            $arrMaterial = array();
                            $listWorkOrder[$rowWO->id] = $rowWO;

                            $persentag = $arrKomposisiSubWO[$rowWO->produk_id][$rowWO->fase_id]->harga / $nilai_kontrak * 100;
                            $total_persen += $persentag;

                            //untuk nulis ke project_tasklist
                            $arrMaterial = array(
                                "dtime" => date("Y-m-d H:i:s"),
                                "dtime_end" => $start_dtime,
                                "dtime_start" => $end_dtime,
                                "no_kontrak" => $kontrak,
                                "tgl_kontrak" => $tgl_kontrak,
                                "employee_id" => "",
                                "fase_id" => $rowWO->fase_id,
                                "material" => 1,
                                "nama" => $rowWO->nama,
                                "nilai" => "note",
                                "nilai_sub_fase" => $arrKomposisiSubWO[$rowWO->produk_id][$rowWO->fase_id]->harga,
                                "nilai_kontrak" => $nilai_kontrak,
                                "persen_sub" => $persentag,
                                "oleh_id" => $login["id"],
                                "oleh_nama" => $login["nama"],
                                "produk_id" => $pid,
                                "progress_id" => 1,
                                "sub_fase_id" => $rowWO->id,
                            );

                            $arrMaterial['no_pre_spk'] = $this->numbering_new("SPK", "PRE-SPK", $arrMaterial);
                            $arrMaterial['nomer'] = $this->numbering("SPK", "PRESPK", $arrMaterial);

                            $this->load->model("Mdls/" . $mdlName);
                            $m = new $mdlName();
                            $insertID = $m->addData($arrMaterial) or matiHere("<r>Gagal menyimpan tugas</r> <br>" . base64_encode(json_encode($arrMaterial)) . "<br>" . $this->db->last_query());

                            //register ke daftar_tugas
                            $this->load->model("Mdls/" . "MdlProjectWorkOrderSub");
                            $ms = new MdlProjectWorkOrderSub();
                            $update = array(
//                                "employee_id" => $arrMaterial['employee_id'],
                                "daftar_tugas" => $insertID,
                                "daftar_tugas_dtime" => date("Y-m-d H:i:s"),
                            );
                            $where = array(
                                "id" => $arrMaterial["sub_fase_id"],
                            );
                            $ms->setFilters(array());
                            $update = $ms->updateData($where, $update) or matiHere("gagal memperbaharui data LINE: " . __LINE__);
                            //echo "(arrMaterial: ".count($arrMaterial)." || mdlName: $mdlName || produkID: $produkID) <br>";

                        }
                    }

                }
                //update gen_tasklist
                $update = array(
                    "gen_tasklist" => 1,
                );
                $where = array(
                    "id" => $produkID,
                );
                $pr->setFilters(array());
                $update = $pr->updateData($where, $update) or matiHere("gagal memperbaharui data LINE: " . __LINE__);
            }

            $ipadd = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "";
//            mati_disini(__LINE__);
            $complete = $this->db->trans_complete();
//            $complete = 1;
            if ($complete) {
                $result = array(
                    "status" => 1,
                    "reason" => "sukses",
                    "arrMaterial" => $arrMaterial,
                    "ipadd" => $ipadd,
                );
            }
            else {
                $result = array(
                    "status" => 99,
                    "reason" => "gagal",
                    "arrMaterial" => $arrMaterial,
                    "ipadd" => $ipadd,
                );
            }
        }
        else {
            $result = array(
                "status" => 123,
                "reason" => "gagal",
                "arrMaterial" => $arrMaterial,
                "ipadd" => $ipadd,
                "last_q" => $last_q,
                "mdlName" => $mdlName,
                "produkID" => $produkID,
            );
        }

        echo json_encode($result);
    }

    public function updateTask()
    {

        $mdlName = $this->uri->segment(4);
        $cCode = $this->uri->segment(5);
        $produkID = $this->uri->segment(6);
        $prevData = $_POST;

        $mdlName = "MdlTasklistProject";
        $this->load->model("Mdls/" . $mdlName);
        $p = new $mdlName();

        $this->db->trans_start();

        $update = array(
            "progress_id" => $prevData['progress_id'],
            "dtime" => date("Y-m-d H:i:s"),
        );
        $where = array(
            "id" => $prevData['id'],
            "produk_id" => $prevData['produk_id'],
        );
        $p->setFilters(array());
        $update = $p->updateData($where, $update) or matiHere("gagal memperbaharui data LINE: " . __LINE__);
        if ($update) {
//            if (method_exists($p, "paramSyncNamaNama")) {
//                $syncNamaNamaMdls = method_exists($p, "paramSyncNamaNama") ? $p->paramSyncNamaNama() : mati_disini("paramSyncNamaNama belum terdifine");
//                arrPrint($syncNamaNamaMdls);
//                foreach ($syncNamaNamaMdls as $syncNamaNamaMdl => $syncNamaNamaParams) {
//                    $id_ygdisync = isset($prevData[$syncNamaNamaParams['id']]) ? $prevData[$syncNamaNamaParams['id']] : "";
//                    if ($id_ygdisync > 0) {
//                        $p->syncNamaNama($id_ygdisync);
//                    }
//                }
//            }

            $p->setFilters(array());
            $p->addFilter("id='".$prevData['id']."'");
            $p->addFilter("produk_id='".$prevData['produk_id']."'");
            $tmp_ = $p->lookupAll()->result();

            $prevData = (array)$tmp_[0];
            unset($prevData['id']);
            $prevData['dtime'] = date("Y-m-d H:i:s");

            $this->load->model("Mdls/MdlTasklistProjectLog");
            $mLog = new MdlTasklistProjectLog();
            $logData = $prevData;
            $logData['type'] = "update";
            $logData['person_id'] = $this->session->login["id"];
            $logData['person_nama'] = $this->session->login["nama"];
            $logID = $mLog->addData($logData) or matiHere("gagal add data log LINE: " . __LINE__);

            $lastQLog = $this->db->last_query();
//            if (method_exists($mLog, "paramSyncNamaNama")) {
//                $syncNamaNamaMdls = method_exists($mLog, "paramSyncNamaNama") ? $mLog->paramSyncNamaNama() : mati_disini("paramSyncNamaNama belum terdifine");
//                foreach ($syncNamaNamaMdls as $syncNamaNamaMdl => $syncNamaNamaParams) {
//                    $id_ygdisync = isset($prevData[$syncNamaNamaParams['id']]) ? $prevData[$syncNamaNamaParams['id']] : "";
//                    if ($id_ygdisync > 0) {
//                        $mLog->syncNamaNama($id_ygdisync);
//                    }
//                }
//            }
//            else {
//                cekHitam("gak aada pram nama nama");
//            }

            $isCommit = $this->db->trans_complete();

            $result = array(
                "lastQ" => $lastQLog,
                "status" => $isCommit ? "ok" : "GAGAL",
            );
            echo json_encode($result);
        }

    }

    public function editTask(){
        $no_spk             = isset($_REQUEST['no_spk']) ? $_REQUEST['no_spk'] : "";
        $produk_dasar_id    = isset($_REQUEST['produk_dasar_id']) ? $_REQUEST['produk_dasar_id'] : "";
        $val                = isset($_REQUEST['val']) ? $_REQUEST['val'] : "";
        $valBefore          = isset($_REQUEST['qty_before']) ? $_REQUEST['qty_before'] : "";

        $this->db->trans_start();

        $this->load->model("Mdls/" . "MdlProjectKomposisiWorkorderSub");
        $kompSubWO = new MdlProjectKomposisiWorkorderSub();
        $kompSubWO->setFilters(array());
        $kompSubWO->addFilter("status='1'");
        $kompSubWO->addFilter("trash='0'");
        $kompSubWO->addFilter("no_spk='$no_spk'");
        $kompSubWO->addFilter("produk_dasar_id='$produk_dasar_id'");
        $kompSubWO->addFilter("jml='$valBefore'");
        $kompSubWO->addFilter("jenis_transaksi='sub_wo'");
        $tmpKompSubOrder = $kompSubWO->lookUpAll()->result();
        $tmpKompSubOrderQuery = $this->db->last_query();

        if(!empty($tmpKompSubOrder)){
            $ids = $tmpKompSubOrder[0]->id;
            $harga = $tmpKompSubOrder[0]->harga*1;
            $upData = array(
                "status" => 0,
                "trash" => 1,
                "trash_dtime" => date("Y-m-d H:i:s"),
                "trash_oleh_id" => $this->session->login['id'],
                "trash_oleh_nama" => $this->session->login['nama'],
            );
            $kompSubWO->updateData(array("id"=>$ids), $upData);
            $new = (array)$tmpKompSubOrder[0];
            $new["jml"] = $val;
            $new["qty_debet"] = $val;
            $new["qty_saldo"] = $val;
            $new["qty_kredit"] = 0;
            $new["debet"] = $val*$harga;
            $new["saldo"] = $val*$harga;
            $new["kredit"] = 0;
            unset($new["id"]);
            $kompSubWO->addData($new) or matiHere("gagal menambahkan data*" . " | " . $this->db->last_query());
            $kompSubWOQuery = $this->db->last_query();
    }

//        $mdlName = "MdlTasklistProject";
//        $this->load->model("Mdls/" . $mdlName);
//        $p = new $mdlName();
//        $update = array(
//            "progress_id" => $prevData['progress_id'],
//            "dtime" => date("Y-m-d H:i:s"),
//        );
//        $where = array(
//            "id" => $prevData['id'],
//            "produk_id" => $prevData['produk_id'],
//        );
//        $p->setFilters(array());
//        $update = $p->updateData($where, $update) or matiHere("gagal memperbaharui data LINE: " . __LINE__);

        $isCommit = $this->db->trans_complete();

        $return = array(
            "status" => 1,
            "reason" => "wkawkaw",
            "kompSubWOQuery" => $kompSubWOQuery,
            "tmpKompSubOrderQuery" => $tmpKompSubOrderQuery,
        );

        echo json_encode($return);
    }

    public function removeMaterialTask(){
        $no_spk             = isset($_REQUEST['no_spk']) ? $_REQUEST['no_spk'] : "";
        $produk_dasar_id    = isset($_REQUEST['produk_dasar_id']) ? $_REQUEST['produk_dasar_id'] : "";
        $val                = isset($_REQUEST['val']) ? $_REQUEST['val'] : "";
        $valBefore          = isset($_REQUEST['qty_before']) ? $_REQUEST['qty_before'] : "";

        $this->db->trans_start();

        $this->load->model("Mdls/" . "MdlProjectKomposisiWorkorderSub");
        $kompSubWO = new MdlProjectKomposisiWorkorderSub();
        $kompSubWO->setFilters(array());
        $kompSubWO->addFilter("status='1'");
        $kompSubWO->addFilter("trash='0'");
        $kompSubWO->addFilter("no_spk='$no_spk'");
        $kompSubWO->addFilter("produk_dasar_id='$produk_dasar_id'");
        $kompSubWO->addFilter("jml='$valBefore'");
        $kompSubWO->addFilter("jenis_transaksi='sub_wo'");
        $tmpKompSubOrder = $kompSubWO->lookUpAll()->result();
        $tmpKompSubOrderQuery = $this->db->last_query();

        if(!empty($tmpKompSubOrder)){
            $ids = $tmpKompSubOrder[0]->id;
            $harga = $tmpKompSubOrder[0]->harga*1;
            $upData = array(
                "status" => 0,
                "trash" => 1,
                "trash_dtime" => date("Y-m-d H:i:s"),
                "trash_oleh_id" => $this->session->login['id'],
                "trash_oleh_nama" => $this->session->login['nama'],
            );
            $kompSubWO->updateData(array("id"=>$ids), $upData);
        }

        $isCommit = $this->db->trans_complete();
        $return = array(
            "status" => 1,
            "reason" => "wkawkaw",
            "kompSubWOQuery" => $kompSubWOQuery,
            "tmpKompSubOrderQuery" => $tmpKompSubOrderQuery,
        );
        echo json_encode($return);
    }

    public function reloadPaketInstalasiSPK(){

    }

    public function exe_activate_task()
    {

        $mdlName = $this->uri->segment(4);
        $cCode = $this->uri->segment(5);
        $produkID = $this->uri->segment(6);
        $prevData = $_POST;

        $person_id = $this->session->login["id"];
        $person_nama = $this->session->login["nama"];

        $mdlName = "MdlTasklistProject";
        $this->load->model("Mdls/" . $mdlName);
        $p = new $mdlName();

        $this->db->trans_start();

        $p->setFilters(array());
        $p->addFilter("id='" . $prevData['id'] . "'");
        $pre_tmp = $p->lookupAll()->result();

        $numbering = array(
            "dtime" => date("Y-m-d H:i:s"),
            "employee_id" => $person_id,
            "oleh_id" => $pre_tmp[0]->oleh_id,
            "toko_id" => 1001,
            "produk_id" => $prevData['produk_id'],
            "fase_id" => $prevData['fase_id'],
        );
        $update = array(
            "progress_id" => 2,
            "dtime_spk" => date("Y-m-d H:i:s"),
            "employee_id" => $person_id,
            "employee_nama" => $person_nama,
            "no_spk" => $this->numbering_new("SPK", "SPK-INT", $numbering),
            "pre_spk_validasi_img" => $prevData['pre_spk_validasi_img'],
        );
        $where = array(
            "id" => $prevData['id'],
            "produk_id" => $prevData['produk_id'],
            "no_pre_spk" => $prevData['pre_spk'],
        );
        $p->setFilters(array());
        $update = $p->updateData($where, $update) or matiHere("gagal memperbaharui data LINE: " . __LINE__);
        $update_query = $this->db->last_query();

        if ($update) {
            if (method_exists($p, "paramSyncNamaNama")) {
                $syncNamaNamaMdls = method_exists($p, "paramSyncNamaNama") ? $p->paramSyncNamaNama() : mati_disini("paramSyncNamaNama belum terdifine");
                foreach ($syncNamaNamaMdls as $syncNamaNamaMdl => $syncNamaNamaParams) {
                    $id_ygdisync = isset($prevData[$syncNamaNamaParams['id']]) ? $prevData[$syncNamaNamaParams['id']] : "";
                    if ($id_ygdisync > 0) {
                        $p->syncNamaNama($id_ygdisync);
                    }
                }
            }
            $p->setFilters(array());
            $tmp_ = $p->lookupAll()->result();
            $prevData = (array)$tmp_[0];
            unset($prevData['id']);
            $prevData['dtime'] = date("Y-m-d H:i:s");
            $this->load->model("Mdls/MdlTasklistProjectLog");
            $mLog = new MdlTasklistProjectLog();
            $logData = $prevData;
            $logData['type'] = "update";
            $logData['person_id'] = $this->session->login["id"];
            $logData['person_nama'] = $this->session->login["nama"];
            $logID = $mLog->addData($logData) or matiHere("gagal menambahkan data*" . " | " . $this->db->last_query());
            if (method_exists($mLog, "paramSyncNamaNama")) {
                $syncNamaNamaMdls = method_exists($mLog, "paramSyncNamaNama") ? $mLog->paramSyncNamaNama() : mati_disini("paramSyncNamaNama belum terdifine");
                foreach ($syncNamaNamaMdls as $syncNamaNamaMdl => $syncNamaNamaParams) {
                    $id_ygdisync = isset($prevData[$syncNamaNamaParams['id']]) ? $prevData[$syncNamaNamaParams['id']] : "";
                    if ($id_ygdisync > 0) {
                        $mLog->syncNamaNama($id_ygdisync);
                    }
                }
            }
            else {
                cekHitam("gak aada pram nama nama");
            }
//            $complete = 0;
            $complete = $this->db->trans_complete();
            $result = array(
                "status" => $complete ? 1 : 0,
                "reason" => $complete ? "update sukses" : "gagal commite",
                "post" => $_POST,
                "segment" => $this->uri->segment_array(),
                "update_query" => $update_query,
            );
        }
        else {
            $result = array(
                "status" => 0,
                "reason" => "update gagal nih",
                "post" => $_POST,
                "segment" => $this->uri->segment_array(),
                "update_query" => $update_query,
            );
        }

        echo json_encode($result);

    }

    public function pre_activate_task()
    {

        $produkID = $this->uri->segment(4);
        $noPreSpk = base64_decode($this->uri->segment(5));

        $mdlName = "MdlTasklistProject";
        $this->load->model("Mdls/" . $mdlName);
        $p = new MdlTasklistProject();

        $p->setFilters(array());
        $p->addFilter("no_pre_spk='$noPreSpk'");
        $tmp = $p->lookupAll()->result();

        $arrTask = array();
        if (!empty($tmp)) {
            foreach ($tmp as $k => $dTmp) {
                $arrTask[$k] = $dTmp;
                $fase_id = $dTmp->fase_id;

                //MdlProjectKomposisiWorkorderSub
                $this->load->model("Mdls/" . "MdlProjectTasklistKomposisi");
                $kompSubWO = new MdlProjectTasklistKomposisi();
                $kompSubWO->setFilters(array());
                $kompSubWO->addFilter("oleh_id='".$this->session->login['id']."'");
                $kompSubWO->addFilter("produk_id='$produkID'");
                $kompSubWO->addFilter("fase_id='$fase_id'");
                $kompSubWO->addFilter("fase_id='$fase_id'");
                $tmpKompSubOrder = $kompSubWO->lookUpAll()->result();

                $arrMaterial = array();
                if (!empty($tmpKompSubOrder)) {
                    foreach ($tmpKompSubOrder as $ks => $rowWo) {
                        $arrMaterial[$rowWo->jenis][] = $rowWo;
                    }
                }

                $arrTask[$k]->komposisi = $arrMaterial;
            }
        }

        $result = array(
            "status" => count($arrTask),
            "data" => $arrTask,
//            "segment" => $this->uri->segment_array(),
//            "noPreSpk" => $noPreSpk,
//            "tmp_" => $tmp_,
//            "query" => $this->db->last_query(),
        );
        echo json_encode($result);
//
//        $this->db->trans_start();
//
//        $update = array(
//            "progress_id" => $prevData['progress_id'],
//            "dtime" => date("Y-m-d H:i:s"),
//        );
//        $where = array(
//            "id" => $prevData['id'],
//            "produk_id" => $prevData['produk_id'],
//        );
//        $p->setFilters(array());
//        $update = $p->updateData($where, $update) or matiHere("gagal memperbaharui data LINE: " . __LINE__);
//
//        if ($update) {
//            if (method_exists($p, "paramSyncNamaNama")) {
//                $syncNamaNamaMdls = method_exists($p, "paramSyncNamaNama") ? $p->paramSyncNamaNama() : mati_disini("paramSyncNamaNama belum terdifine");
//                arrPrint($syncNamaNamaMdls);
//                foreach ($syncNamaNamaMdls as $syncNamaNamaMdl => $syncNamaNamaParams) {
//                    $id_ygdisync = isset($prevData[$syncNamaNamaParams['id']]) ? $prevData[$syncNamaNamaParams['id']] : "";
//                    if ($id_ygdisync > 0) {
//                        $p->syncNamaNama($id_ygdisync);
//                    }
//                }
//            }
//
//            $p->setFilters(array());
//            $tmp_ = $p->lookupAll()->result();
//
//            $prevData = (array)$tmp_[0];
//            unset($prevData['id']);
//            $prevData['dtime'] = date("Y-m-d H:i:s");
//
//            $this->load->model("Mdls/MdlTasklistProjectLog");
//            $mLog = new MdlTasklistProjectLog();
//            $logData = $prevData;
//            $logData['type'] = "update";
//            $logData['person_id'] = $this->session->login["id"];
//            $logData['person_nama'] = $this->session->login["nama"];
//            $logID = $mLog->addData($logData);
//
//            if (method_exists($mLog, "paramSyncNamaNama")) {
//                $syncNamaNamaMdls = method_exists($mLog, "paramSyncNamaNama") ? $mLog->paramSyncNamaNama() : mati_disini("paramSyncNamaNama belum terdifine");
//                foreach ($syncNamaNamaMdls as $syncNamaNamaMdl => $syncNamaNamaParams) {
//                    $id_ygdisync = isset($prevData[$syncNamaNamaParams['id']]) ? $prevData[$syncNamaNamaParams['id']] : "";
//                    if ($id_ygdisync > 0) {
//                        $mLog->syncNamaNama($id_ygdisync);
//                    }
//                }
//            } else {
//                cekHitam("gak aada pram nama nama");
//            }
//
//            $result = array(
//                "status" => "ok",
//            );
//            echo json_encode($result);
//        }

//        $this->db->trans_complete();

    }

    //open modal edit
    public function preview()
    {
        // arrPrint($this->uri->segment_array());
        $mdlName = $className = $ctrlName = $this->uri->segment(4);
        $curent_id = $this->uri->segment(5);
        $selectedID = $curent_id;
        $this->load->model("Mdls/" . $mdlName);
        $o = new $mdlName;
        switch ($mdlName) {
            case "MdlTimWorkProject":
                $o->addFilter("id='$curent_id'");
                break;
            case "MdlProjectKomposisiWorkorder":
                $o->addFilter("id='$curent_id'");
                break;
            case "MdlProjectKomposisiWorkorderRoom":
                $o->addFilter("id='$curent_id'");
                break;
            case "MdlProjectKomposisiWorkorderSub":
                $o->addFilter("id='$curent_id'");
                break;
            case "MdlProjectWorkOrder":
                $o->addFilter("id='$curent_id'");
                break;
            case "":
                break;
        }
        // $m->addFilter("id='$curent_id'");
        // cekMErah(__LINE__);
        // matiHEre();
        //kiriman model dan data yang dieksekusi
        $tmp = $o->lookUpAll()->result();
        $f = new MyForm($o, "edit", array(
            "id" => "f1ed_" . $className,
            "method" => "post",
            "enctype" => "multipart/form-data",
            "action" => MODUL_PATH . get_class($this) . "/editProcess/$ctrlName/" . $selectedID,
            "target" => "result",
            "class" => "form-horizontal",
        ));
        $f->openForm(MODUL_PATH . get_class($this) . "/editProcess/$ctrlName/" . $selectedID);
        $f->fillForm($className, $tmp);
        $f->closeForm();
        //        cekHere($selectedID);
        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : $ctrlName;
        $p = new Layout($title, "Ubah Data $title", "application/template/lte/index.html");

        $dataRel = isset($this->config->item('dataRelation')[$className]) ? $this->config->item('dataRelation')[$className] : array();
        $dataExtRel = isset($this->config->item('dataExtRelation')[$className]) ? $this->config->item('dataExtRelation')[$className] : array();
        //arrPrint($dataExtRel);
        //cekHitam($className);

        $content .= "<div class='panel panel-danger'>";
        $content .= "<div class='panel-heading'>";
        $content .= "<span class='text-blue no-padding text-uppercase'><span class='fa fa-folder-open'> main editor</span>";
        $content .= "</div>";

        $content .= "<div class='panel-body'>";
        if ($this->updaterUsingApproval) {
            $content .= "<div class='alert alert-warning-dot text-center'>";
            $content .= ("This modification requires approval and this entry will be deactivated until being approved<br>");
            $content .= ("</div class='panel-body'>");
        }
        $content .= ($f->getContent());
        $content .= "</div>";
        $content .= "</div>";

        // $content .= "<div class='row'>";
        // $content .= "<div class='col-lg-12 col-md-12 col-sm-12'>";

        if (sizeof($dataRel) > 0) {
            $content .= "<div class='panel panel-info'>";

            // $content .= "<div class='row panel panel-default' style='background:#f0f0f0;'>";
            // $content .= "<div class='col-lg-12 col-md-12 col-sm-12'>";
            $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
            foreach ($dataRel as $mdlName => $mSpec) {
                $tmpDataAccess = isset($this->config->item('heDataBehaviour')[$mdlName]) ? $this->config->item('heDataBehaviour')[$mdlName] : array(
                    "viewers" => array(),
                    "creators" => array(),
                    "creatorAdmins" => array(),
                    "updaters" => array(),
                    "updaterAdmins" => array(),
                    "deleters" => array(),
                    "deleterAdmins" => array(),
                );
                $allowView = false;
                $allowCreate = false;
                $allowEdit = false;
                $allowDelete = false;
                foreach ($mems as $mID) {
                    if (in_array($mID, $tmpDataAccess['viewers'])) {
                        $allowView = true;
                    }
                    if (in_array($mID, $tmpDataAccess['creators'])) {
                        $allowCreate = true;
                    }
                    if (in_array($mID, $tmpDataAccess['updaters'])) {
                        $allowEdit = true;
                    }
                    if (in_array($mID, $tmpDataAccess['deleters'])) {
                        $allowDelete = true;
                    }
                }


                $relations = array();
                $relationPairs = array();
                if (file_exists(APPPATH . "models/Mdls/$mdlName.php")) {
                    $this->load->model("Mdls/" . $mdlName);
                    $o = new $mdlName();
                    $fields = $o->getFields();
                    foreach ($fields as $f2Spec) {
                        if (isset($f2Spec['reference'])) {
                            if (array_key_exists($f2Spec['kolom'], $o->getListedFields())) {
                                $relations[$f2Spec['kolom']] = $f2Spec['reference'];
                                $this->load->model("Mdls/" . $f2Spec['reference']);
                                $o3 = new $f2Spec['reference']();
                                $tmp3 = $o3->lookupAll()->result();

                                if (sizeof($tmp3) > 0) {
                                    $mdlName2 = $f2Spec['kolom'];
                                    $relationPairs[$mdlName2] = array();
                                    foreach ($tmp3 as $row3) {
                                        $id = isset($row3->id) ? $row3->id : 0;
                                        $name = isset($row3->nama) ? $row3->nama : "";
                                        $relationPairs[$mdlName2][$id] = $name;
                                    }
                                }
                            }
                        }
                    }
                }


                $mdlLink = base_url() . get_class($this) . "/view/" . str_replace("Mdl", "", $mdlName) . "?reqField=" . $mSpec['targetField'] . "&reqVal=" . $selectedID;
                $content .= "<div class='panel-heading'>";
                $content .= "<span class='text-blue text-uppercase'>";
                $content .= "<a href='$mdlLink'>";
                $content .= "<span class='fa fa-folder-open'></span> " . $mSpec['label'] . " <span class='meta'>selengkapnya klik disini</span>";
                $content .= "</a>";

                if ($allowCreate) {
                    //                    $relPihak = "&cCode=_TR_466&pId=yes";
                    $relPihak = "&pihakId=$selectedID&pId=yes";
                    $addLink = base_url() . get_class($this) . "/add/" . str_replace("Mdl", "", $mdlName);
                    $addLink .= "?reqField=" . $mSpec['targetField'] . "&reqVal=" . $selectedID . $relPihak;

                    $addClick = "
                                BootstrapDialog.show(
                                    {
                                        title:'New " . $mSpec['label'] . "',
                                        message: $('<div></div>').load('" . $addLink . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                    }
                                );";
                    $content .= "<span class='pull-right'>";
                    $content .= "<a class=\" btn btn-default btn-xs\" onClick=\"$addClick\" data-toggle='tooltip' data-placement='top' title='Add new " . $mSpec['label'] . "' class='btn btn-circle btn-xs btn-primary bg-blue-gradient'><span class='glyphicon glyphicon-plus'></a>";
                    $content .= "</span>";
                }
                $content .= "</span>";
                $content .= "</div>";

                $content .= "<div class='panel-body'>";
                $this->load->model("Mdls/" . $mdlName);

                $o2 = new $mdlName();
                $o2->addFilter($mSpec['targetField'] . "='$selectedID'");
                $tmpo2 = $o2->lookupAll()->result();
                $content .= "<table class='table table-condensed'>";
                if (sizeof($tmpo2) > 0) {
                    $content .= "<tr bgcolor='#f0f0f0'>";
                    foreach ($o2->getListedFields() as $fName => $label) {
                        $content .= "<td>$label</td>";
                    }
                    //                    $content .= "<td>*</td>";
                    $content .= "</tr>";
                    foreach ($tmpo2 as $row) {
                        $content .= "<tr>";
                        foreach ($o2->getListedFields() as $fName => $label) {
                            $content .= "<td>";
                            if (array_key_exists($fName, $relations)) {
                                $fieldLabel = isset($relationPairs[$fName][$row->$fName]) ? $relationPairs[$fName][$row->$fName] : "unknown rel";
                            }
                            else {
                                $fieldLabel = $row->$fName;
                            }
                            $content .= $fieldLabel;
                            $content .= "</td>";
                        }
                        //                        $content .= "<td>-</td>";
                        $content .= "</tr>";
                    }

                }


                $content .= "</table class='table table-condensed'>";
            }

            // $content .= "</div>";
            $content .= "</div>";
            $content .= "</div>";
        }
        // $content .= "</div>";

        /*-------------------------------------
         * editor dalam iframe
         * -----------------------------------*/
        if (sizeof($dataExtRel) > 0) {
            $num = 0;
            foreach ($dataExtRel as $mSpec) {
                $num++;
                $content .= "<div class='panel panel-default' style='background:#f0f0f0;'>";
                // $content .= "<div class='col-lg-12 col-md-12 col-sm-12'>";
                $content .= "<div class='panel-heading'>";
                // $content .= "<h5 class='text-blue text-uppercase no-padding'><span class='fa fa-folder-open'></span> " . $mSpec['label'] . "</h5>";
                $content .= "<span class='text-blue text-uppercase no-padding'><span class='fa fa-folder-open'></span> " . $mSpec['label'] . "</span>";
                $content .= "</div>";

                $content .= "<div class='panel-body'>";
                $mSpec['target'];
                $backLink = blobEncode(current_url());
                $iframeLink = base_url() . $mSpec['target'] . "&attached=1&sID=" . $selectedID . "&backLink=$backLink";
                // cekHere("$iframeLink");
                //                $content .= "<div id='$selectedID$num' frameborder='0'  style='width:100%;height:350px;position:relative;top:0px;left:0px;right:0px;bottom:0px;overflow:scroll;'>";
                //                $content .= "</div>";
                //                $content .= "<script> $('#$selectedID$num').load('" . base_url() . $mSpec['target'] . "&attached=1&sID=" . $selectedID . "&backLink=$backLink'); </script>";

                $frameID_target = "result2_$num";
                $content .= "<iframe id='$frameID_target' frameborder='0' width=100% height=100% style='width:100%;height:500px;position:relative;top:0px;left:0px;right:0px;bottom:0px;overflow:hidden;' src='" . base_url() . $mSpec['target'] . "&attached=1&sID=" . $selectedID . "&backLink=$backLink&show=1&iframe=$frameID_target'>";
                $content .= "</iframe>";
                if (show_debuger() == 1) {
                    $content .= "<a href='javaScript:void(0);' onclick=\"window.open('$iframeLink&dock=1','mywin','width=1000,height=600');\">open New Window</a>";
                }

                $content .= "</div>"; // body
                $content .= "</div>"; // panel
            }
        }

        // $content .= "</div class='col-lg-12 col-md-12 col-sm-12'>";
        // $content .= "</div class='row'>";

        $arrSpecs = array(
            "mdlName" => "$className",
            "mainLabel" => ucwords($ctrlName),
            "images" => array(),
            "parent_id" => $selectedID,
        );

        $jsBottom .= "";

        // arrprint($tmp);
        $data = array(
            "mode" => "barcodeView",
            "title" => "Data $ctrlName",
            "subTitle" => "Create new $ctrlName",
            "content" => $content,
            "jsBottom" => $jsBottom,
        );

        $this->load->view('data', $data);
    }

    public function editProcess()
    {

        $arrAlert = array(
            "html" => "<img src='" . base_url() . "public/images/sys/loader-100.gif'> <br>Saving your data, please wait..<br>",
            "showConfirmButton" => false,
            "allowOutsideClick" => false,
        );

        $content = "";
        //==menyimpan inputan perubahan data ke dalam datamodel, lalu dari datamodel ke database (dilakukan oleh CI)
        $className = $this->uri->segment(4);
        $ctrlName = $this->uri->segment(4);
        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $this->db->trans_start();

        $postProcs = isset($this->config->item("dataPostProcessors")[$className]) ? $this->config->item("dataPostProcessors")[$className] : array();
        $indexFieldName = "id";
        $f = new MyForm($o, "editProcess");

        if ($f->isInputValid()) { //==jika validasi lengkap
            if (sizeof($o->getUnionPairs()) > 0) {
                if ($f->isUnionValid()) {
                    //lolos
                }
                else {
                    $errMsg = "";
                    foreach ($f->getValidationResults() as $err) {
                        $errMsg .= "Error in <strong>$err[fieldLabel]</strong>:  $err[errMsg]<br>";
                    }
                    echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
                    die(lgShowAlert($errMsg));
                }
            }

            foreach ($o->getFields() as $fieldName => $spec) {
                $fName = isset($spec['kolom']) ? $spec['kolom'] : $fieldName;
                if (isset($spec['inputType'])) {
                    switch ($spec['inputType']) {
                        case "checkbox":
                            $data[$fName] = base64_encode(serialize($this->input->post($fName)));
                            break;
                        case "qtyFillBox":
                            $data[$fName] = base64_encode(serialize($this->input->post($fName)));
                            break;
                        case "texts":
                            if (isset($spec['dataParams'])) {
                                $tmp = array();
                                foreach ($spec['dataParams'] as $param) {
                                    $tmp[$param] = $this->input->post($fName . "_" . $param);
                                }
                                $data[$fName] = base64_encode(serialize($tmp));
                            }
                            break;
                        case "password":
                            $data[$fName] = strlen($this->input->post($fName)) > 24 ? $this->input->post($fName) : md5($this->input->post($fName));
                            break;
                        case "file":
                            if ($_FILES[$fName]['size'] > 0) {
                                $request = curl_init(cdn_upload_images());
                                $realpath = realpath($_FILES[$fName]['tmp_name']);
                                curl_setopt($request, CURLOPT_POST, true);
                                $fields = [
                                    //                                    'file' => new \CurlFile($realpath, $_FILES[$fName]['type'], $_FILES[$fName]['name']),
                                    'file' => "@" . $realpath . ";filename=" . $_FILES[$fName]['name'] . ";type=" . $_FILES[$fName]['type'],
                                    'server_source' => $_SERVER['HTTP_HOST'],
                                ];
                                curl_setopt($request, CURLOPT_POSTFIELDS, $fields);
                                curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
                                $cUrl_result = json_decode(curl_exec($request));
                                curl_close($request);
                                if (isset($cUrl_result->status) && $cUrl_result->status == 'success') {
                                    $data[$fName] = $cUrl_result->full_url;
                                    die();
                                }
                                else {
                                    echo "<script>top.swal('error', 'image tidak valid, coba untuk ganti gambar yang akan di upload', 'error');</script>";
                                    die();
                                }
                            }
                            else {
                                if ($this->input->post($fName)) {
                                    $newFile = $this->input->post($fName);
                                }
                                else {
                                    $newFile = "";
                                }
                                $data[$fName] = $newFile;
                            }
                            break;
                        case "image":
                            if ($_FILES[$fName]['size'] > 0) {
                                $request = curl_init(cdn_upload_images());
                                $realpath = realpath($_FILES[$fName]['tmp_name']);
                                curl_setopt($request, CURLOPT_POST, true);
                                $fields = [
                                    'file' => new \CurlFile($realpath, $_FILES[$fName]['type'], $_FILES[$fName]['name']),
                                    'server_source' => $_SERVER['HTTP_HOST'],
                                ];
                                curl_setopt($request, CURLOPT_POSTFIELDS, $fields);
                                curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
                                $cUrl_result = json_decode(curl_exec($request));
                                curl_close($request);
                                if (isset($cUrl_result->status) && $cUrl_result->status == 'success') {
                                    $data[$fName] = $cUrl_result->full_url;
                                }
                                else {
                                    echo "<script>top.Swal.fire('error', 'image tidak valid, coba untuk ganti gambar yang akan di upload', 'error');</script>";
                                    die();
                                }
                            }
                            else {
                                $data[$fName] = "";
                            }
                            break;
                        case "hidden":
                            $data[$fName] = $this->input->post($fName);
                            break;
                        default:
                            $data[$fName] = heTrimAvoidedChars($this->input->post($fName));
                            break;
                    }
                }
                else {
                    switch ($spec['type']) {
                        case "varchar":
                            $data[$fName] = $this->input->post($fName);
                            break;
                        case "int":
                            $data[$fName] = $this->input->post($fName);
                            break;
                        case "date":
                            $data[$fName] = date("Y-m-d");
                            break;
                        case "datetime":
                            $data[$fName] = date("Y-m-d H:i:s");
                            break;
                        case "timestamp":
                            $data[$fName] = date("Y-m-d H:i:s");
                            break;
                        default:
                            $data[$fName] = $this->input->post($fName);
                            break;
                    }
                }
            }
            switch ($className) {
                case "MdlProjectKomposisiWorkorder":
                    $data["debet"] = $data["harga"] * $data["jml"];
                    $data["saldo"] = $data["harga"] * $data["jml"];
                    break;
                default:

                    break;
            }

//            arrprintWebs($data);

            $where = array(
                "id" => $data['id'],
            );

            $this->load->model("Mdls/" . "MdlDataTmp");
            $dTmp = new MdlDataTmp();
            if ($this->updaterUsingApproval) {
                $data['trash'] = 0;
            }
            if (sizeof($o->getAutoFillFields()) > 0) {
                foreach ($o->getAutoFillFields() as $mainCol => $autoFieldsCal) {
                    $data[$mainCol] = makeValue($autoFieldsCal, $this->input->post(), $this->input->post(), 0);
                }
            }
            if (method_exists($o, "getListedUnsetFields")) {
                if (sizeof($o->getListedUnsetFields())) {
                    foreach ($o->getListedUnsetFields() as $val) {
                        if (array_key_exists($val, $this->input->post())) {
                            cekHere("meng NULL kan -> $val");
                            $data[$val] = NULL;
                        }
                    }
                }
            }
            $tmpData = array(
                "orig_id" => $data['id'],
                "mdl_name" => $className,
                "mdl_label" => $ctrlName,
                "proposed_by" => $this->session->login['id'],
                "proposed_by_name" => $this->session->login['nama'],
                "proposed_date" => date("Y-m-d H:i:s"),
                "content" => blobEncode($data),
            );
            $tmpOrig = $o->lookupByCondition(array(
                "id" => $data['id'],
            ))->result();
            $o->setFilters(array());
            $o->updateData($where, $data, $o->getTableName());

            //tambah disini untuk load autorebuil komposisi project
            switch ($className) {
                case"MdlProjectKomposisiWorkorder":
                    $o->addFilter("produk_id='" . $data["produk_id"] . "'");
                    $o->addFilter("produk_dasar_id='" . $data["produk_dasar_id"] . "'");
                    $o->addFilter("fase_id='" . $data["fase_id"] . "'");
                    $o->addFilter("status='1'");
                    $temp = $o->lookUpAll()->result();

                    $toUpdated = array();
                    if (count($temp) > 0) {
                        foreach ($temp as $temp_0) {
                            if (!isset($toUpdated["jml"])) {
                                $toUpdated["jml"] = 0;
                            }
                            $toUpdated["jml"] += $temp_0->jml;
                            $toUpdated["debet"] += $temp_0->jml * $temp_0->harga;
                            $toUpdated["saldo"] += $temp_0->jml * $temp_0->harga;
                            $toUpdated["nilai"] = $toUpdated["debet"] / $toUpdated["jml"];
                            $toUpdated["harga"] = $toUpdated["debet"] / $toUpdated["jml"];
                        }
                    }

                    //UPDATE ALL SUB
                    $this->load->model("Mdls/MdlProjectKomposisiWorkorderSub");
                    $os = new MdlProjectKomposisiWorkorderSub();
                    $os->addFilter("produk_id='" . $data["produk_id"] . "'");
                    $os->addFilter("produk_dasar_id='" . $data["produk_dasar_id"] . "'");
                    $os->addFilter("fase_id='" . $data["fase_id"] . "'");
                    $os->addFilter("jenis_transaksi='5582'");
                    $tempSubKompos = $os->lookUpAll()->result();

                    if (!empty($tempSubKompos)) {
                        foreach ($tempSubKompos as $tempSub_0) {
                            $os->setFilters(array());
                            $os->updateData(array("id" => $tempSub_0->id), $toUpdated) or matiHere("GAGAL UPDATE SUB KOMPOSISI IDS: $tempSub_0->id || LINE: " . __LINE__);
                        }
                    }

                    $mainKomposisiUpdate = array();

                    $os->setFilters(array());
                    $os->addFilter("produk_id='" . $data["produk_id"] . "'");
                    $os->addFilter("produk_dasar_id='" . $data["produk_dasar_id"] . "'");
                    $os->addFilter("status='1'");
                    $tempMainFormSubKompos = $os->lookUpAll()->result();

//                    cekHitam( $this->db->last_query() );
//                    arrPrint($tempMainFormSubKompos);

                    if (!empty($tempMainFormSubKompos)) {
                        foreach ($tempMainFormSubKompos as $k => $tmFs) {
                            if (!isset($mainKomposisiUpdate["jml"])) {
                                $mainKomposisiUpdate["jml"] = 0;
                            }
                            $mainKomposisiUpdate["jml"] += $tmFs->jml;
                            $mainKomposisiUpdate["debet"] += $tmFs->jml * $tmFs->harga;
                            $mainKomposisiUpdate["nilai"] = $mainKomposisiUpdate["debet"] / $mainKomposisiUpdate["jml"];
                            $mainKomposisiUpdate["harga"] = $mainKomposisiUpdate["debet"] / $mainKomposisiUpdate["jml"];
                        }
                    }

                    $this->rebuildMasterKomposisi($data, $mainKomposisiUpdate, "update");

                    break;
                case"MdlProjectKomposisiWorkorderSub":
                    $o->setFilters(array());
                    $o->addFilter("produk_id='" . $data["produk_id"] . "'");
                    $o->addFilter("produk_dasar_id='" . $data["produk_dasar_id"] . "'");
                    $o->addFilter("jenis_transaksi='5582'");
                    $temp = $o->lookUpAll()->result();
                    $toUpdated = array();
                    if (count($temp) > 0) {
                        foreach ($temp as $temp_0) {
                            if (!isset($toUpdated["jml"])) {
                                $toUpdated["jml"] = 0;
                            }
                            $toUpdated["jml"] += $temp_0->jml;
                            $toUpdated["debet"] += $temp_0->jml * $temp_0->harga;
                            $toUpdated["nilai"] = $toUpdated["debet"] / $toUpdated["jml"];
                            $toUpdated["harga"] = $toUpdated["debet"] / $toUpdated["jml"];
                        }
                    }
                    $this->rebuildMasterKomposisi($data, $toUpdated, "update");
                    break;
                default:

                    break;
            }

            $this->session->errMsg = "Data has been updated";
            //arrPrint($this->config->item("dataExtended"));
            if (isset($this->config->item("dataExtended")[$className])) {
                createAccessData($this->input->post('membership'), $data['id'], "false");
            }

            if (method_exists($o, "paramSyncNamaNama")) {
                $syncNamaNamaMdls = method_exists($o, "paramSyncNamaNama") ? $o->paramSyncNamaNama() : mati_disini("paramSyncNamaNama belum terdifine");
                foreach ($syncNamaNamaMdls as $syncNamaNamaMdl => $syncNamaNamaParams) {
                    $id_ygdisync = isset($data[$syncNamaNamaParams['id']]) ? $data[$syncNamaNamaParams['id']] : "";
                    if ($id_ygdisync > 0) {
                        $o->syncNamaNama($id_ygdisync);
                    }
                }
            }

            if (sizeof($postProcs) > 0) {
                foreach ($postProcs as $pp) {
                    $comName = "DCom" . $pp;
                    $this->load->model("DComs/" . $comName);
                    $o2 = new $comName();
                    $o2->pair($data) or die(lgShowError($comName, "failed to pair the params of DCom"));
                    $o2->exec() or die(lgShowError($comName, "failed to execute DCom"));
                }
            }
            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id" => $data['id'],
                "mdl_name" => $className,
                "mdl_label" => get_class($this),
                "old_content" => base64_encode(serialize((array)$tmpOrig)),
                "old_content_intext" => print_r($tmpOrig, true),
                "new_content" => base64_encode(serialize($data)),
                "new_content_intext" => print_r($data, true),
                "label" => "applied",
                "oleh_id" => $this->session->login['id'],
                "oleh_name" => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));

//            matiHere();
            $this->db->trans_complete();

//            echo "<script>top.location.reload();</script>"; //ORIGINAL PAKAI RELOAD
            echo "<script>\n
            if(typeof top.preLoadFase=='function'){\n
                top.preLoadFase();\n
                top.BootstrapDialog.closeAll(); \n
            }\n
            else{\n
                top.window.location.reload();\n
            }\n
            </script>";

        }
        else {
            $errMsg = "";
            foreach ($f->getValidationResults() as $err) {
                $errMsg .= "Error in $err[fieldLabel]:  $err[errMsg]";
            }
            echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
            die(lgShowAlert($errMsg));
        }
    }

    public function numbering($setType = 'tasklist', $trAlias = 'TL', $dt)
    {
        $arr = array();

        //region penomoran receipt
        $this->load->model("CustomCounter");
        $cn = new CustomCounter("transaksi");
        $cn->setType($setType);
        $cn->setModul($this->modul);
        $cn->setStepCode($trAlias);

        $numBatch = array(
            "$trAlias" => array(
                "counters" => array(
                    "stepCode",
                    "stepCode|oleh_id",
                    "stepCode|produk_id",
                    "stepCode|fase_id",
                    "stepCode|employee_id",
                ),
//                "formatNota" => ".stepCode,.oleh_id,stepCode|oleh_id,stepCode|produk_id,stepCode|fase_id,.employee_id,stepCode|employee_id",
                "formatNota" => ".stepCode,.oleh_id,stepCode|oleh_id,stepCode|produk_id,stepCode|fase_id",
            )
        );

        $arr['stepCode'] = $trAlias;
        $arr['oleh_id'] = $dt['oleh_id'];
        $arr['produk_id'] = $dt['produk_id'];
        $arr['fase_id'] = $dt['fase_id'];
        $arr['employee_id'] = $dt['employee_id'];

        $counterForNumber = array($numBatch[$trAlias]['formatNota']);

        $configCustomParams = $numBatch[$trAlias]['counters'];
        if (sizeof($configCustomParams) > 0) {
            $cContent = array();
            foreach ($configCustomParams as $i => $cRawParams) {
                $cParams = explode("|", $cRawParams);
                $cValues = array();
                foreach ($cParams as $param) {
                    $cValues[$i][$param] = $arr[$param];
                }
                $cRawValues = implode("|", $cValues[$i]);
                $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);
                $cContent[$cRawParams][$cRawValues] = $paramSpec['value'];
                switch ($paramSpec['id']) {
                    case 0: //===counter type is new
                        $addData = array(
                            "toko_id" => 0,
                            "toko_nama" => "none",
                        );
                        $paramKeyRaw = print_r($cParams, true);
                        $paramValuesRaw = print_r($cValues[$i], true);
                        $cn->writeNewCount($cParams, $cValues[$i], $paramKeyRaw, $paramValuesRaw, $addData);
                        break;
                    default: //===counter to be updated
                        $cn->updateCount($paramSpec['id'], $paramSpec['value']);
                        break;
                }
            }
        }
        $appliedCounters = base64_encode(serialize($cContent));
        $appliedCounters_inText = print_r($cContent, true);

        $counterForNumber = array($numBatch[$trAlias]['formatNota']);
        $tmpNomorNota = "";
        $arrNomorNota = array();
        foreach ($counterForNumber as $i => $c0RawParams) {
            $c0Params = explode(",", $c0RawParams);
            $c0Values = array();
            foreach ($c0Params as $k => $cRawParams) {
                $arrRawParams = explode("|", $cRawParams);
                if (count($arrRawParams) > 1) {
                    $cRawParamsValues = array();
                    foreach ($arrRawParams as $key) {
                        $cRawParamsValues[$key] = $arr[$key];
                    }
                    $cRawParamsValuesK = implode("|", array_keys($cRawParamsValues));
                    $cRawParamsValuesV = implode("|", $cRawParamsValues);
                    $arrNomorNota[] = digit_3($cContent[$cRawParamsValuesK][$cRawParamsValuesV]);
                }
                else {
                    if (isset($arr[$arrRawParams[0]])) {
                        $cRawParamsValuesV = $arr[$arrRawParams[0]];
                        $cRawParamsValuesK = $arrRawParams[0];
                        if ($arrRawParams[0] == "startDate") {
                            $arrNomorNota[] = $cRawParamsValuesV;
                        }
                        elseif ($arrRawParams[0] == "toko_id") {
                            $arrNomorNota[] = $cRawParamsValuesV;
                        }
                        elseif ($arrRawParams[0] == "place_id") {
                            $arrNomorNota[] = digit_2($cRawParamsValuesV);
                        }
                        elseif ($arrRawParams[0] == "customer_id") {
                            $arrNomorNota[] = digit_3($cRawParamsValuesV);
                        }
                        elseif ($arrRawParams[0] == "employee_id") {
                            $arrNomorNota[] = digit_3($cRawParamsValuesV);
                        }
                        elseif ($arrRawParams[0] == "oleh_id") {
                            $arrNomorNota[] = digit_3($cRawParamsValuesV);
                        }
                        elseif ($arrRawParams[0] == "supplier_id") {
                            $arrNomorNota[] = digit_3($cRawParamsValuesV);
                        }
                        else {
                            if (isset($cContent[$cRawParamsValuesK])) {
                                $arrNomorNota[] = digit_3($cContent[$cRawParamsValuesK][$cRawParamsValuesV]);
                            }
                            else {
                                $arrNomorNota[] = digit_3($cRawParamsValuesV);
                            }
                        }
                    }
                    else {
                        $cc = explode(".", $arrRawParams[0]);
                        $arrNomorNota[] = $arr[$cc[1]];
                    }
                }
            }
        }
        $tmpNomorNota = implode("-", $arrNomorNota);
        return $tmpNomorNota;
    }

    public function numbering_new($setType = 'tasklist', $trAlias = 'TL', $dt)
    {
        $arr = array();

        //region penomoran receipt
        $this->load->model("CustomCounter");
        $cn = new CustomCounter("transaksi");
        $cn->setType($setType);
        $cn->setModul($this->modul);
        $cn->setStepCode($trAlias);

        $numBatch = array(
            "$trAlias" => array(
                "counters" => array(
                    "stepCode",
                    "stepCode|oleh_id",
                    "stepCode|produk_id",
                    "stepCode|fase_id",
                    "stepCode|employee_id",
                    "stepCode|month",
                    "stepCode|years",
                ),
//                "formatNota" => "stepCode|employee_id,.stepCode,.employee_id,stepCode|employee_id,.month,.years",
                "formatNota" => "stepCode|oleh_id,.stepCode,.oleh_id,stepCode|oleh_id,.month,.years",
            )
        );

        if (isset($dt['dtime'])) {
            $month_select = date("m", strtotime($dt['dtime']));
            $arr['month'] = getRomawi($month_select);
            $arr['years'] = date("Y", strtotime($dt['dtime']));
        }

        $arr['stepCode'] = $trAlias;
        $arr['oleh_id'] = $dt['oleh_id'];
        $arr['produk_id'] = $dt['produk_id'];
        $arr['fase_id'] = $dt['fase_id'];
        $arr['employee_id'] = $dt['employee_id'];

        $counterForNumber = array($numBatch[$trAlias]['formatNota']);
        $configCustomParams = $numBatch[$trAlias]['counters'];

        if (sizeof($configCustomParams) > 0) {
            $cContent = array();
            foreach ($configCustomParams as $i => $cRawParams) {
                $cParams = explode("|", $cRawParams);
                $cValues = array();
                foreach ($cParams as $param) {
                    $cValues[$i][$param] = $arr[$param];
                }
                $cRawValues = implode("|", $cValues[$i]);
                $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);
                $cContent[$cRawParams][$cRawValues] = $paramSpec['value'];
                switch ($paramSpec['id']) {
                    case 0: //===counter type is new
                        $addData = array(
                            "toko_id" => 0,
                            "toko_nama" => "none",
                        );
                        $paramKeyRaw = print_r($cParams, true);
                        $paramValuesRaw = print_r($cValues[$i], true);
                        $cn->writeNewCount($cParams, $cValues[$i], $paramKeyRaw, $paramValuesRaw, $addData);
                        break;
                    default: //===counter to be updated
                        $cn->updateCount($paramSpec['id'], $paramSpec['value']);
                        break;
                }
            }
        }
        $appliedCounters = base64_encode(serialize($cContent));
        $appliedCounters_inText = print_r($cContent, true);

        $counterForNumber = array($numBatch[$trAlias]['formatNota']);
        $tmpNomorNota = "";
        $arrNomorNota = array();
        foreach ($counterForNumber as $i => $c0RawParams) {
            $c0Params = explode(",", $c0RawParams);
            $c0Values = array();
            foreach ($c0Params as $k => $cRawParams) {
                $arrRawParams = explode("|", $cRawParams);
                if (count($arrRawParams) > 1) {
                    $cRawParamsValues = array();
                    foreach ($arrRawParams as $key) {
                        $cRawParamsValues[$key] = $arr[$key];
                    }
                    $cRawParamsValuesK = implode("|", array_keys($cRawParamsValues));
                    $cRawParamsValuesV = implode("|", $cRawParamsValues);
                    $arrNomorNota[] = digit_3($cContent[$cRawParamsValuesK][$cRawParamsValuesV]);
                }
                else {
                    if (isset($arr[$arrRawParams[0]])) {
                        $cRawParamsValuesV = $arr[$arrRawParams[0]];
                        $cRawParamsValuesK = $arrRawParams[0];
                        if ($arrRawParams[0] == "startDate") {
                            $arrNomorNota[] = $cRawParamsValuesV;
                        }
                        elseif ($arrRawParams[0] == "toko_id") {
                            $arrNomorNota[] = $cRawParamsValuesV;
                        }
                        elseif ($arrRawParams[0] == "place_id") {
                            $arrNomorNota[] = digit_2($cRawParamsValuesV);
                        }
                        elseif ($arrRawParams[0] == "customer_id") {
                            $arrNomorNota[] = digit_3($cRawParamsValuesV);
                        }
                        elseif ($arrRawParams[0] == "employee_id") {
                            $arrNomorNota[] = digit_3($cRawParamsValuesV);
                        }
                        elseif ($arrRawParams[0] == "oleh_id") {
                            $arrNomorNota[] = digit_3($cRawParamsValuesV);
                        }
                        elseif ($arrRawParams[0] == "supplier_id") {
                            $arrNomorNota[] = digit_3($cRawParamsValuesV);
                        }
                        else {
                            if (isset($cContent[$cRawParamsValuesK])) {
                                $arrNomorNota[] = digit_3($cContent[$cRawParamsValuesK][$cRawParamsValuesV]);
                            }
                            else {
                                $arrNomorNota[] = digit_3($cRawParamsValuesV);
                            }
                        }
                    }
                    else {
                        $cc = explode(".", $arrRawParams[0]);
                        if (is_numeric($arr[$cc[1]])) {
                            $arrNomorNota[] = digit_3($arr[$cc[1]]);
                        }
                        else {
                            $arrNomorNota[] = $arr[$cc[1]];
                        }
                    }
                }
            }
        }
        $tmpNomorNota = implode("/", $arrNomorNota);
        return $tmpNomorNota;
    }

    public function test_number()
    {

//        echo $this->numbering('SPK', 'SPK', array("oleh_id" => 69, "produk_id" => 1, "employee_id" => 34, "fase_id" => 1, "toko_id" => 1001, "toko_nama" => "mbuh"));
        echo $this->numbering_new('SPK', 'SPK-INT',
            array(
                "oleh_id" => 69,
                "produk_id" => 1,
                "employee_id" => 34,
                "fase_id" => 1,
                "toko_id" => 1001,
                "toko_nama" => "mbuh",
                "dtime" => date("Y-m-d H:i:s"),
            )
        );
    }

    public function ajaxTasklistTimeline()
    {

        $pid = $prodID = $this->uri->segment(4);
        $fid = $fase_id = $this->uri->segment(5);

        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();

        //region timeline
        $this->load->model("Mdls/MdlTasklistProjectLog");
        $tl = new MdlTasklistProjectLog();
        $tl->addFilter("produk_id='$prodID'");

        if( !in_array("o_project_spv", $mems) ){
            $tl->addFilter("employee_id='".$this->session->login['id']."'");
        }

        $this->db->order_by("dtime", "desc");
        $tempTaskList = $tl->lookUpAll()->result();
        //endregion

        $ajax = array();
        $colView = array(
            "dtime",
            "type",
            "produk_nama",
            "person_nama",
            "employee_nama",
            "no_spk",
            "no_sub",
        );
        if (!empty($tempTaskList)) {
            foreach ($tempTaskList as $r => $dt) {
                $ajax[$r] = array();
                foreach ($colView as $cc) {
                    $ajax[$r][] = $dt->$cc;
                }
            }
        }

        echo json_encode(array("data" => $ajax));

    }

    public function cloneMasterWorkOrder($faseID, $data)
    {
        $jmltoGenerate = !isset($data["qty"]) ? matiHEre("kolom jumlah belum diisi, silahkan diperbaiki terlebih dahulu!") : $data["qty"];
        $this->load->model('Mdls/MdlProjectWorkOrderSub');
        $m = new MdlProjectWorkOrderSub();
        for ($ii = 1; $ii <= $jmltoGenerate; $ii++) {
            $dataInsert = array(
                "qty" => "1",
                "produk_id" => $data["produk_id"],
                "produk_nama" => $data["produk_nama"],
                "fase_id" => $faseID,
//                "fase_nama" => $data["nama"],
                "keterangan" => $data["keterangan"],
                "oleh_id" => $data["oleh_id"],
                "oleh_nama" => $data["oleh_nama"],
                "nama" => $data["nama"] . " " . $ii,
                "dtime" => date("y-m-d H:i"),
                "status" => 1,
            );
            $m->addData($dataInsert) or matiHere("Gagal membuat detail rencana pekerjaan");
//            cekBiru($this->db->last_query());
//            cekMErah($ii);

        }
    }

    public function cloneMasterWorkOrderKomposisi($produkID, $data, $mode = "add")
    {
        $faseID = $data["fase_id"];
        $this->load->model('Mdls/MdlProjectWorkOrderSub');
        $this->load->model('Mdls/MdlProjectKomposisiWorkorderSub');
        switch ($mode) {
            case "update":


                break;
            default:

                $m = new MdlProjectWorkOrderSub();
                $k = new MdlProjectKomposisiWorkorderSub();

                $m->addFilter("fase_id='$faseID'");
                $m->addFilter("produk_id='$produkID'");

                $temp = $m->lookUpAll()->result();

                if (count($temp) > 0) {
                    foreach ($temp as $temp_0) {
                        $toInsert = $data + array("sub_fase_id" => $temp_0->id, "sub_fase_nama" => $temp_0->nama);
                        $k->addData($toInsert) or matiHere("gagal menambahkan data ditail. Silahkan ulangi beberapa saat lagi.<br>" . $this->db->last_query());
                        $this->rebuildMasterKomposisi($produkID, $data, $mode);
                    }
                }
                break;
        }
    }

    public function showTeamWork()
    {

        $pid = $prodID = $produkID = $this->uri->segment(4);

        $this->load->model("Mdls/MdlTimWorkProject");
        $this->load->model("Mdls/MdlEmployee_all");

        $t = new MdlTimWorkProject();
        $e = new MdlEmployee_all();
        //region timwork
        $allEmployee = $e->lookUpAll()->result();
        $allowedAdd = array();
        if (count($allEmployee) > 0) {
            //bagian tombol-tombol crud
            if (count($this->customAccesProject) > 0 && in_array("MdlTimWorkProject", $this->customAccesProject["create"])) {
                $allowedAdd["employee_id"]["create"] = true;
            }
            if (count($this->customAccesProject) > 0 && in_array("MdlTimWorkProject", $this->customAccesProject["update"])) {
                $allowedAdd["employee_id"]["update"] = true;
            }
            if (count($this->customAccesProject) > 0 && in_array("MdlTimWorkProject", $this->customAccesProject["view"])) {
                $allowedAdd["employee_id"]["view"] = true;
            }
            if (count($this->customAccesProject) > 0 && in_array("MdlTimWorkProject", $this->customAccesProject["delete"])) {
                $allowedAdd["employee_id"]["delete"] = true;
            }
            foreach ($allEmployee as $allEmployee_0) {
                $preEmployee["employee_id"][$allEmployee_0->id] = (array)$allEmployee_0;
            }
        }

        $allowedAccess = $allowedAdd;

        $tempRelHakAkses = aksesProject();
        $preHakAkses = array();
        if (count($tempRelHakAkses) > 0) {
            foreach ($tempRelHakAkses as $tempRelHakAkses_0) {
                $preEmployee["akses_id"][$tempRelHakAkses_0["id"]] = $tempRelHakAkses_0;
            }
        }
        $t->addFilter("produk_id='$prodID'");
        $tmpTimwork = $t->lookUpAll()->result();

        cekMerah($tmpTimwork);
        $timWork = array();
        $fieldsTimLabel = $t->getListedFields();
        if (count($tmpTimwork) > 0) {
            foreach ($tmpTimwork as $timWork_0) {
                $tmp = array();
                foreach ($fieldsTimLabel as $key => $label) {
                    $tmp[$key] = $timWork_0->$key;
                }
                $timWork[$timWork_0->id] = $tmp;
                $preTimwork["employee_id"][$timWork_0->id] = (array)$timWork_0;
            }
        }
        //endregion

        $masterTimwork = $timWork;

        $relEmployee = $preEmployee;

        $relSuppliesHeader = array(
            "cat_id" => "cat_nama",
            "satuan_id" => "satuan",
            "produk_dasar_id" => "produk_dasar_nama",
            "harga_ori" => "harga",
            "harga_bom" => "harga_bom",
            "employee_id" => "employee_nama",
            "akses_id" => "hak_akses_nama",
            // "harga_bom"=>"harga_bom",
        );
        $relSuppliesHeader = $relSuppliesHeader;
        $fieldsTimLabel = array(
            "employee_id" => "nama",
            "akses_id" => "hak akses",
        );
        $masterTimworkLabel = $fieldsTimLabel;
        $produkKomposisiFaseEditable = array(
            "room_nama" => "room_nama",
            "room_ket" => "room_ket",
            "produk_dasar_id" => "produk_dasar_id",
//                "cat_id" => "cat_id",
            "keterangan" => "keterangan",
            "satuan_id" => "satuan_id",
            "harga" => "harga",
            "jml" => "jml",
            "fase_id" => "fase_id",
            "employee_id" => "employee_id",
            "akses_id" => "akses_id",
        );
        $produk_fase_komposisiEditable = $produkKomposisiFaseEditable;

        $newData = isset($_SESSION["NEW"]) ? $_SESSION["NEW"] : array();

        $addFaseProdukKomposisiTimLink = MODUL_PATH . get_class($this) . "/addData/MdlTimWorkProject/$prodID";//untuk per fase
        $selector = MODUL_PATH . get_class($this) . "/addSession/PROED/";

        $timWork = "";
        $timWork .= "<div class='box-header'>";
        $timWork .= "<h3 id=''><b><u>Anggota</u></b></h3>";
        $timWork .= "</div>";
        $timWork .= "<div class='box-body no-padding'>";
        $timWork .= "<form class='form' name='timwork' id='timwork' method='post' target='result' action='$addFaseProdukKomposisiTimLink?mode=timwork'>";
        $timWork .= "<table class='table dataTable compact display table-bordered table-condensed'>";
        $timWork .= "<thead>";
        $timWork .= "<tr>";
        $timWork .= "<td>No</td>";
        foreach ($masterTimworkLabel as $hField => $hLabel) {
            $timWork .= "<td>$hLabel</td>";
        }
        $timWork .= "<td>Action</td>";
        $timWork .= "</tr>";

        $timWork .= "</thead>";
        $timWork .= "<tbody>";
        //bagaian data relasi komposisi

        $i = 0;
        if (count($masterTimwork) > 0) {
            foreach ($masterTimwork as $tID => $masterTimwork) {
                $labelName = $masterTimwork["employee_nama"];
                $modalLink = $previewLink . "MdlTimWorkProject/";
                $timWork .= "<tr>";
                $i++;
                $timWork .= "<td>$i</td>";
                foreach ($masterTimworkLabel as $hField => $hLabel) {
                    $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                    $val = isset($masterTimwork[$transformKey]) ? $masterTimwork[$transformKey] : "";
                    $timWork .= "<td>" . formatField($hField, $val) . "</td>";
                }
                //tambah logic crud
                $btn = "<div>";
                if (isset($allowedAccess["employee_id"]["update"]) && $allowedAccess["employee_id"]["update"] == true) {
                    $btn .= "<button type='button' title='edit' class='btn btn-xs btn-flat btn-warning' onclick=\"showModal('" . $previewLink . "MdlTimWorkProject/$tID/$hField','edit $labelName')\"><span class='fa fa-fw fa-edit'></span></button>";
                }
                if (isset($allowedAccess["employee_id"]["delete"]) && $allowedAccess["employee_id"]["delete"] == true) {
                    $btn .= "<button type='button' title='hapus' class='btn btn-xs btn-flat btn-danger'><span class='fa fa-fw fa-trash'></span></button>";
                }
                $btn .= "</div>";
                $timWork .= "<td class='text-center'>$btn</td>";
                $timWork .= "</tr>";
            }
        }

        //untuk tambah komponen
        //akses list untuk nambaha data
        $timWork .= "<tr>";
        $timWork .= "<td></td>";
        if (isset($allowedAccess["employee_id"]["create"]) && $allowedAccess["employee_id"]["create"] == true) {
            foreach ($masterTimworkLabel as $hField => $hLabel) {
                if (isset($produk_fase_komposisiEditable[$hField])) {
                    if (isset($relEmployee[$hField])) {
                        $strTim = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp_0').load('$selector" . "$produkID?mode=timwork&key=$hField&value='+encodeURI(this.value)+''); \">";
                        $strTim .= "<option value='0'>==PILIH==</option>";
                        $ic = 0;
                        foreach ($relEmployee[$hField] as $datas) {
                            $ic++;
                            $selected = isset($newData["timwork"][$produkID][$hField]) && $newData["timwork"][$produkID][$hField] == $datas['id'] ? "selected" : "";
                            $strTim .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                        }
                        $strTim .= "</select>";
                    }
                    else {
                        $value = isset($newData["timwork"][$produkID][$hField]) ? $newData["timwork"][$produkID][$hField] : "";
                        $strTim = "<input class='form-control form-control-sm' type='text' value='$value' onblur=\"$('#input_temp_0').load('$selector" . "$produkID?mode=timwork&key=$hField&value='+encodeURI(this.value)+'');\">";
                    }
                }
                else {
                    $value = isset($newData["timwork"][$produkID][$hField]) ? $newData["timwork"][$produkID][$hField] : "";
                    $strTim = formatField($hField, $value);
                }
                $timWork .= "<td>";
                $timWork .= $strTim;
                $timWork .= "</td>";
            }
            //logic button sini allowed add
            $btnRemoveTimwork = "<button onclick=\"document.getElementById('timwork').submit();\" type='button' title='simpan tim kerja' class='btn btn-sm btn-success'><i class='fa fa-plus'></i> tambah</button>";
            $timWork .= "<td class='text-center'>$btnRemoveTimwork</td>";
            $timWork .= "</tr>";
        }

        $timWork .= "</tbody>";
        $timWork .= "</table>";
        $timWork .= "</form>";
        $timWork .= "</div>";
        $timWork .= "

            <script>
                top.$(\".selectpicker\").selectpicker('refresh');
                top.init_tab();
            </script>
        ";


        echo $timWork;
    }

    public function showProdukFase()
    {

        $pid = $prodID = $produkID = $this->uri->segment(4);

        unset($_SESSION['PROED'][$prodID]);

        $this->nonView = 1;
        $this->index();

        $this->load->model("Mdls/" . "MdlProjectWorkOrder");
        $pf = new MdlProjectWorkOrder();

        $pf->setFilters(array());
        $pf->addFilter("produk_id='$pid'");
        $pf->addFilter("status='1'");
        $pf->addFilter("trash='0'");
        $tempProdukFase = $pf->lookUpAll()->result();

//        echo json_encode( $this->customAccesProject ) . "<br>";
        $allowedAdd = array();
        //region komposisi work order
        $produkFase = array();
        $fields = $pf->getListedFields();
        if (sizeof($tempProdukFase) > 0) {
            if (count($this->customAccesProject) > 0 && in_array("MdlProjectWorkOrder", $this->customAccesProject["create"])) {
                $allowedAdd["fase_id"]["create"] = true;
            }
            if (count($this->customAccesProject) > 0 && in_array("MdlProjectWorkOrder", $this->customAccesProject["update"])) {
                $allowedAdd["fase_id"]["update"] = true;
            }
            if (count($this->customAccesProject) > 0 && in_array("MdlProjectWorkOrder", $this->customAccesProject["view"])) {
                $allowedAdd["fase_id"]["view"] = true;
            }
            if (count($this->customAccesProject) > 0 && in_array("MdlProjectWorkOrder", $this->customAccesProject["delete"])) {
                $allowedAdd["fase_id"]["delete"] = true;
            }

            foreach ($tempProdukFase as $data) {
                foreach ($fields as $field => $fieldAlias) {
                    $produkFase[$data->id][$field] = isset($data->$field) ? $data->$field : "";
                }
                if (isset($data->qty) && $data->qty > 0) {
                    for ($di = 1; $di <= $data->qty; $di++) {
                        $produkFase[$data->id]['details'][$di] = array(
                            "idx" => $data->produk_id . $data->id . $di,
                            "nama" => $data->nama . " (" . $di . ")",
                        );
                    }
                }
            }
            //$_SESSION['PROED'][$prodID]['fase'] = $produkFase;
        }
        else {
            $produkFase = array();
            //$_SESSION['PROED'][$prodID]['fase'] = array();
        }
        //endregion

        $allowedAccess = $allowedAdd;

        $selector = MODUL_PATH . get_class($this) . "/addSession/PROED/";

        $produkKomposisiFaseEditable = array(
            "room_nama" => "room_nama",
            "room_ket" => "room_ket",
            "produk_dasar_id" => "produk_dasar_id",
            "cat_id" => "cat_id",
            "satuan_id" => "satuan_id",
            "harga" => "harga",
            "jml" => "jml",
            "fase_id" => "fase_id",
            "employee_id" => "employee_id",
            "akses_id" => "akses_id",
        );
        $produk_fase_komposisiEditable = $produkKomposisiFaseEditable;

        $produkFaseHeader = array(
            // "urut" => "urutan pekerjaan",
            "qty" => "jumlah",
            "nama" => "Rencana Kerja",
            "lokasi" => "lokasi",
            "keterangan" => "Keterangan",
//                "employee_id" => "penanggung jawab",
            // "daftar_tugas" => "daftar tugas",
        );

        $produk_fase_header = $produkFaseHeader;

        $relSuppliesHeader = array(
            "cat_id" => "cat_nama",
            "satuan_id" => "satuan",
            "produk_dasar_id" => "produk_dasar_nama",
            "harga_ori" => "harga",
            "harga_bom" => "harga_bom",
            "employee_id" => "employee_nama",
            "akses_id" => "hak_akses_nama",
            // "harga_bom"=>"harga_bom",
        );
        $relSuppliesHeader = $relSuppliesHeader;

        $produk_fase = isset($_SESSION['PROED'][$prodID]['fase']) ? $_SESSION['PROED'][$prodID]['fase'] : array();
        $sub_fase_nama = isset($_SESSION['PROED'][$prodID]['sub_fase_nama']) ? $_SESSION['PROED'][$prodID]['sub_fase_nama'] : array();
        $newData = isset($_SESSION["NEW"]) ? $_SESSION["NEW"] : array();
        $addFaseProdukLink = MODUL_PATH . get_class($this) . "/addData/MdlProjectWorkOrder/$prodID";//ke tabel produk fase
        $deleteLink = MODUL_PATH . get_class($this) . "/delete/";
        $previewLink = MODUL_PATH . get_class($this) . "/preview/";

        //JIKA ADA UPDATE COPAS DARI SINI SAJA
        //region rencana proses produksi (nama_produk)
        $produkFase = "";
        $produkFase .= "<div class='container_produk_fase'>";
        $produkFase .= "<form class='form' name='produk_fase' id='produk_fase' method='post' target='result' action='$addFaseProdukLink?mode=produk_fase'>";
        $produkFase .= "<table class='table dataTable compact display table-bordered table-condensed'>";
        $produkFase .= "<thead>";
        $produkFase .= "<tr>";
        $produkFase .= "<th>No</th>";
        foreach ($produk_fase_header as $produkfaseKey => $produkfase_alias) {
            $produkFase .= "<th>$produkfase_alias</th>";
        }
        $produkFase .= "<th>action</th>";
        $produkFase .= "</tr>";
        $produkFase .= "</thead>";
        $produkFase .= "<tbody>";
        if (isset($produk_fase) && count($produk_fase)) {
            $i = 0;
            foreach ($produk_fase as $fase_urut => $faseData) {
                $i++;
                $produkFase .= "<tr>";
                $produkFase .= "<td>$i</td>";
                $tmpNamaMaterial = "";
                foreach ($produk_fase_header as $produkfaseKey => $produkfase_alias) {
                    $newKey = isset($relSuppliesHeader[$produkfaseKey]) ? $relSuppliesHeader[$produkfaseKey] : $produkfaseKey;
                    if (isset($faseData[$newKey])) {
                        if (is_numeric($faseData[$newKey])) {
                            $fieldValue = formatField($newKey, $faseData[$newKey]);
                        }
                        else {
                            $fieldValue = $faseData[$newKey];
                        }
                    }
                    else {
                        $fieldValue = "";
                    }
                    $produkFase .= "<td>" . $fieldValue . "</td>";
                    $tmpNamaMaterial .= $newKey == "nama" ? $fieldValue : "";
                }
                //region button remove
                // $btn = "<button type='button' title='clik untuk menghapus realasi' class='btn btn-xs btn-danger' onclick=\"document.getElementById('result').src='" . $deleteTarget . "?pid=$selectedID&tokoID=$tokoID&id=$curentID&key=trash&value=0'\"><span class='glyphicon glyphicon-trash'></span></button>";
                $btn = "<div>";
                if (isset($allowedAccess["fase_id"]["update"]) && $allowedAccess["fase_id"]["update"] == true) {
                    $btn .= "<button type='button' title='edit' class='btn btn-xs btn-flat btn-warning' onclick=\"showModal('" . $previewLink . "MdlProjectWorkOrder/$fase_urut/$hField','edit $labelName')\"><i class='fa fa-fw fa-edit'></i></button>";
                }
                if (isset($allowedAccess["fase_id"]["delete"]) && $allowedAccess["fase_id"]["delete"] == true) {
                    $btn .= "<button cx_jenis='fase_id' cx_tmpnamamaterial='$tmpNamaMaterial' cx_deletelink='$deleteLink' cx_tid='$fase_urut' cx_mdl='ProjectWorkOrder' type='button' title='clik untuk menghapus realasi' class='btn btn-xs btn-flat btn-danger delWorkOrder'><i class='fa fa-fw fa-trash'></i></button>";
                }

                $btn .= "</div>";
                $produkFase .= "<td class='text-center'>$btn</td>";
                //endregion
                $produkFase .= "</tr>";
            }
            //tambahan tr untuk add data baru
        }

        if(!count($produk_fase) && $lock==0 ){
            //untuk penambahan
            $produkFase .= "<tr>";
            $produkFase .= "<td></td>";
            foreach ($produk_fase_header as $produkfaseKey => $produkfase_alias) {
                if (isset($produk_fase_komposisiEditable[$produkfaseKey])) {
                    if (isset($relWorkOrderEmployee[$produkfaseKey])) {
                        $strTim_wo = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp_0').load('$selector" . "$produkID?mode=produk_fase&key=$produkfaseKey&value='+encodeURI(this.value)+'$targetResult'); \">";
                        $strTim_wo .= "<option value='0'>==PILIH==</option>";
                        $ic = 0;
                        foreach ($relWorkOrderEmployee[$produkfaseKey] as $datas) {
                            $ic++;
                            $selected = isset($newData["produk_fase"][$produkID][$produkfaseKey]) && $newData["produk_fase"][$produkID][$produkfaseKey] == $datas['employee_id'] ? "selected" : "";
                            $strTim_wo .= "<option $selected value='" . $datas['employee_id'] . "'>" . $datas['employee_nama'] . "</option>";
                        }
                        $strTim_wo .= "</select>";
                    }
                    else {
                        $preval = isset($newData["produk_fase"][$produkID][$produkfaseKey]) ? $newData["produk_fase"][$produkID][$produkfaseKey] : "";
                        $readOnly = "";
                        $strTim_wo = "<input $readOnly class='form-control form-control-sm' type='text' value='$preval' onblur=\"$('#input_temp').load('$selector" . "$produkID?mode=produk_fase&key=$produkfaseKey&value='+encodeURI(this.value));\">";
                    }
                }
                else {
                    $preval = isset($newData["produk_fase"][$produkID][$produkfaseKey]) ? $newData["produk_fase"][$produkID][$produkfaseKey] : "";
                    $readOnly = "";
                    $strTim_wo = "<input key='$produkfaseKey' $readOnly id='produk_fase_$produkfaseKey' class='form-control form-control-sm addProdukFase' type='text' value='$preval' onfocus=\"this.select()\" onblur=\"$('#input_temp').load('$selector" . "$produkID?mode=produk_fase&key=$produkfaseKey&value='+encodeURI(this.value));\">";
                }
                $produkFase .= "<td>$strTim_wo</td>";

            }
            $produkFase .= "<td> <span id='addProdukFase' class='btn btn-sm btn-success' cx_onclick=\"document.getElementById('produk_fase').submit();\"><i class='fa fa-plus'></i> tambah</span> </td>";
            $produkFase .= "</tr>";
        }

        $produkFase .= "</tbody>";
        $produkFase .= "</table>";
        $produkFase .= "</form>";
        $produkFase .= "</div>";
        //endregion rencana proses produksi (nama_produk)
        //JIKA ADA UPDATE COPAS DARI SINI SAJA

        $produkFase .= "\n<script>\n
                            setTimeout(function(){
                                top.preLoadProdukFase();\n
                                console.log('preLoadFase: DONE at ' + new Date() ); \n
                            }, 1200);
                         </script>";
        echo $produkFase;
    }

    public function showKomposisiProdukFase()
    {
// arrPrintHijau(url_segment());
        $pid = $prodID = $produkID = $this->uri->segment(4);

        $this->load->model("Mdls/MdlProdukProject");
        $p = new MdlProdukProject;

        //region data project
        $p->addFilter("id='$pid'");
        $dataMaster     = $p->lookUpAll()->result();
        $showFields     = $p->getListedFields();
        $projectNama    = $dataMaster[0]->nama;
        $projectStart   = $dataMaster[0]->project_start; // project sudah di starting atau belum
        $projectLock    = $dataMaster[0]->lock; // BOM sudah di lock/simpan atau belum
        $projectQuot    = $dataMaster[0]->quot_status; // quotation susah diapprove atau belum
        $masterProject = (array)$dataMaster[0];
        //endregion

        $this->load->model("Mdls/" . "MdlProdukRakitanPreBiaya");
        $ob = new MdlProdukRakitanPreBiaya();
        $this->load->model("Mdls/MdlDtaBiayaProduksi");
        $bp = new MdlDtaBiayaProduksi();
        $this->load->model("Mdls/MdlSatuan");
        $st = new MdlSatuan();
        $this->load->model("Mdls/" . "MdlProjectKomposisiWorkorder");

        $this->load->model("Mdls/MdlProdukKomposisiPaket");
        $this->load->model("Mdls/MdlProjectProdukPaket");

        $pkg = new MdlProdukKomposisiPaket();
        $pp  = new MdlProjectProdukPaket();
        $kf = new MdlProjectKomposisiWorkorder();

//        $this->load->model("Mdls/" . "MdlProjectKomposisi");
//        $pk = new MdlProjectKomposisi();
//        $this->load->model("Mdls/" . "MdlProjectWorkOrder");
//        $pf = new MdlProjectWorkOrder();
//        $this->load->model("Mdls/" . "MdlProjectKomposisiWorkorderSub");
//        $kfs = new MdlProjectKomposisiWorkorderSub();

        $allowedAccess = isset($_SESSION['PROED'][$prodID]['allowedAccess']) ? $_SESSION['PROED'][$prodID]['allowedAccess'] : array();

        //region biaya biaya
        $tempBiaya = $ob->lookUpAll()->result();
        $prebiaya = array();
        if (sizeof($tempBiaya) > 0) {
            foreach ($tempBiaya as $ic => $biayaData) {
                $prebiaya["cat_id"][$biayaData->id] = (array)$biayaData;
            }
        }
        //endregion

        //region biaya
        $tempBiayaProduksi = $bp->lookUpAll()->result();

        if (sizeof($tempBiayaProduksi)) {
            foreach ($tempBiayaProduksi as $ixb => $tempBiayaProduksi_0) {
                $prebiaya["produk_dasar_id"][$tempBiayaProduksi_0->id] = (array)$tempBiayaProduksi_0;
            }
        }

        $tempSatuan = $st->lookUpAll()->result();
        if (sizeof($tempSatuan) > 0) {
            foreach ($tempSatuan as $tempSatuan_0) {
                $prebiaya["satuan_id"][$tempSatuan_0->id] = (array)$tempSatuan_0;
            }
        }
        //endregion

        $relBiaya = $prebiaya;

        $fieldFormProdukKomposisiFase = $kf->getFields();
        $relSupplies = array();
        foreach ($fieldFormProdukKomposisiFase as $keyID => $temp0) {
            if ($temp0["inputType"] == "combo" && isset($temp0["reference"])) {
                $preKey = $temp0["inputType"];
                $preKey = $temp0["kolom"];
                $preModel = $temp0["reference"];
                if (isset($temp0["reference"])) {
                    $this->load->model("Mdls/" . $preModel);
                    $p = new $preModel();
                    if($preModel=="MdlProdukForProject"){
//                            $this->db->where("jenis='project' AND status='1' AND trash='0'");
                        $this->db->where("jenis='item' AND status='1' AND trash='0' AND allow_project='1'");
                        $this->db->or_where("jenis='project' AND status='1' AND trash='0'");
//                            $this->db->or_where("jenis='item' AND status='1' AND trash='0' AND allow_project='1'");
                    }
                    $tempDatas = $p->lookUpAll()->result();
                    if (sizeof($tempDatas) > 0) {
                        foreach ($tempDatas as $datas_0) {
                            $relSupplies[$preKey][$datas_0->id] = (array)$datas_0;
                            $reg = isset($datas_0->allow_project) && $datas_0->allow_project == 1 ? "| reguler" : "| project";
                            if(isset($datas_0->kategori_id) && $datas_0->kategori_id == 1){
                                $relSupplies[$preKey][$datas_0->id]['nama'] = $datas_0->nama . " | ". $datas_0->kapasitas_nama ." $reg";
                            }
                            else{
                                $relSupplies[$preKey][$datas_0->id]['nama'] = $datas_0->nama . " $reg";
                            }
                        }
                    }
                }
                else {

                }
            }
        }

        $produkKomposisiFaseHeader = array(
            "produk" => array(
                "produk_dasar_id" => "Material",
                "satuan" => "UoM",
//                "nilai" => "H.HPP",
//                "ppv" => "H.PPV",
//                "jual" => "H.JUAL STD",
                "jual" => "H.BELI",
//                "harga" => "Hrg Anggaran",
                "harga" => "H.JUAL",
                "jml" => "Jml",
                "subtotal" => "Subtotal",
            ),
            "biaya" => array(
                "produk_dasar_id" => "Jasa",
                "cat_id" => "Kategori biaya",
                "satuan_id" => "satuan",
                "keterangan" => "ket",
                // "harga_ori"       => "harga standar",
                "harga" => "Harga",
                "jml" => "Jml",
                "subtotal" => "Subtotal",
            ),
            // "target" => array(
            //     "produk_dasar_id" => "Produk fase(wip)",
            //     // "gudang2_id"         => "fase proses lanjut*",
            // ),
            // "timwork" => array(
            //     "employee_id" => "nama",
            //     "akses_id" => "hak akses",
            //     // "gudang2_id"         => "fase proses lanjut*",
            // ),
            "jual" => array(
                "nilai" => "BOM",
                "harga" => "JUAL",
                "jml" => "Jml",
                "subtotal" => "Subtotal",
            ),
            "room" => array(
                "room_nama" => "Nama Ruang",
                "room_ket" => "Keterangan",
            ),
            "produk_room" => array(
                "produk_dasar_id" => "Material",
                "satuan" => "UoM",
//                "nilai" => "H.HPP",
//                "ppv" => "H.PPV",
                "jual" => "H.BELI",
                "harga" => "H.JUAL",
                "avail" => "Jml Avail",
                "jml" => "Jml",
                "subtotal" => "Subtotal",
            ),
            "biaya_room" => array(
                "produk_dasar_id" => "Jasa",
                "satuan_id" => "satuan",
                "cat_id" => "Kategori biaya",
                "ket" => "Keterangan",
                // "harga_ori"       => "harga standar",
                "harga" => "Harga",
                "avail" => "Jml Avail",
                "jml" => "Jml",
                "subtotal" => "Subtotal",
            ),
        );
        $produk_komposisi_fase_header = $produkKomposisiFaseHeader;

        $produkKomposisiFaseHeaderMini = array(
            "produk" => array(
                "produk_dasar_id" => "BB",
                "satuan" => "UOM",
                "nilai" => "HPP",
                "ppv" => "PPV",
                "jual" => "J.STD",
                "harga" => "HA",
                "jml" => "JML",
                "subtotal" => "SUB",
            ),
            "biaya" => array(
                "produk_dasar_id" => "JASA",
                "cat_id" => "CAT",
                "satuan_id" => "UOM",
                "harga" => "HRG",
                "jml" => "JML",
                "subtotal" => "SUB",
            ),
        );
        $produk_komposisi_fase_header_mini = $produkKomposisiFaseHeaderMini;

        $produkKomposisiFaseEditable = array(
            "room_nama" => "room_nama",
            "room_ket" => "room_ket",
            "produk_dasar_id" => "produk_dasar_id",
//                "cat_id" => "cat_id",
            "keterangan" => "keterangan",
            "satuan_id" => "satuan_id",
            "harga" => "harga",
            "jml" => "jml",
            "fase_id" => "fase_id",
            "employee_id" => "employee_id",
            "akses_id" => "akses_id",
        );
        $produk_fase_komposisiEditable = $produkKomposisiFaseEditable;

        $relSuppliesHeader = array(
            "cat_id" => "cat_nama",
            "satuan_id" => "satuan",
            "produk_dasar_id" => "produk_dasar_nama",
            "harga_ori" => "harga",
            "harga_bom" => "harga_bom",
            "employee_id" => "employee_nama",
            "akses_id" => "hak_akses_nama",
            // "harga_bom"=>"harga_bom",
        );
        $relSuppliesHeader = $relSuppliesHeader;

        $relSuppliesHeaderDetails = array(
            "cat_id" => "cat_nama",
            "satuan_id" => "satuan",
            "biaya_dasar_id" => "biaya_dasar_nama",
            "harga" => "harga",
//            "harga_bom" => "harga_bom",
            "employee_id" => "employee_nama",
            "akses_id" => "hak_akses_nama",
            // "harga_bom"=>"harga_bom",
        );
        $relSuppliesHeaderDetails = $relSuppliesHeaderDetails;

        $produkKomposisiFaseRoomEditable = array(
            "produk_dasar_id" => "produk_dasar_id",
            "jml" => "jml",
        );
        $produk_fase_komposisiRoomEditable = $produkKomposisiFaseRoomEditable;


        $lock = "";
        $no_kontrak = "";
        $nilai_proyek = "";
        $batas_waktu = "";

        foreach($showFields as $key => $label){
            switch($key){
                case "kode":
                    $no_kontrak = $dataMaster[0]->$key;
                    break;
                case "harga":
                    $nilai_proyek = $dataMaster[0]->$key;
                    break;
                case "end_dtime":
                    $batas_waktu = $dataMaster[0]->$key;
                    break;
                case "lock":
                    $lock = $dataMaster[0]->$key;
                    break;
            }
        }
//arrPrintKuning($_SESSION['PROED'][$prodID]['component']);
        $produk_fase = isset($_SESSION['PROED'][$prodID]['fase']) ? $_SESSION['PROED'][$prodID]['fase'] : array();
        $produk_komposisi_fase = isset($_SESSION['PROED'][$prodID]['component']) ? $_SESSION['PROED'][$prodID]['component'] : array();
        $produk_komposisi_fase_sub = isset($_SESSION['PROED'][$prodID]['component_sub']) ? $_SESSION['PROED'][$prodID]['component_sub'] : array();
        $sub_fase_nama = isset($_SESSION['PROED'][$prodID]['sub_fase_nama']) ? $_SESSION['PROED'][$prodID]['sub_fase_nama'] : array();
        $newData = isset($_SESSION["NEW"]) ? $_SESSION["NEW"] : array();
        $produk_paket = isset($_SESSION['PROED'][$prodID]['produk_paket']) ? $_SESSION['PROED'][$prodID]['produk_paket'] : array();
        $produk_komposisi_bom = isset($_SESSION['PROED'][$prodID]['component_bom']) ? $_SESSION['PROED'][$prodID]['component_bom'] : array();

        $addProdukKomposisiLink = MODUL_PATH . get_class($this) . "/addData/MdlProjectKomposisi/$prodID";//untuk per produk(sumary)
        $addProdukKomposisiBiayaLink = MODUL_PATH . get_class($this) . "/addData/MdlProjectKomposisiWorkorder/$prodID";//untuk per produk(summary)
        $addFaseProdukLink = MODUL_PATH . get_class($this) . "/addData/MdlProjectWorkOrder/$prodID";//ke tabel produk fase
        $addFaseProdukKomposisiLink = MODUL_PATH . get_class($this) . "/addData/MdlProjectKomposisiWorkorder/$prodID";//untuk per fase
        $addFaseProdukKomposisiSubLink = MODUL_PATH . get_class($this) . "/addData/MdlProjectKomposisiWorkorderSub/$prodID";//untuk per fase
        $addFaseProdukKomposisiBiayaLink = MODUL_PATH . get_class($this) . "/addData/MdlProjectKomposisiWorkorder/$prodID";//untuk per fase
        $addFaseProdukKomposisiBiayaSubLink = MODUL_PATH . get_class($this) . "/addData/MdlProjectKomposisiWorkorderSub/$prodID";//untuk per fase
        $addFaseProdukKomposisiTimLink = MODUL_PATH . get_class($this) . "/addData/MdlTimWorkProject/$prodID";//untuk per fase
        $addFaseHasilProduksi = MODUL_PATH . get_class($this) . "/addData/MdlProjectKomposisiWorkorder/$prodID";//supplies wip ke tabel produk denga tipe item_wip
        $deleteLink = MODUL_PATH . get_class($this) . "/delete/";
        $saveJualProject = MODUL_PATH . get_class($this) . "/addData/MdlProjectKomposisiWorkorder/$prodID";
        $selector = MODUL_PATH . get_class($this) . "/addSession/PROED/";
        $previewLink = MODUL_PATH . get_class($this) . "/preview/";
        $modulClassLink = MODUL_PATH . get_class($this);
        $addFaseProdukKomposisiBomLink = MODUL_PATH . get_class($this) . "/addData/MdlProdukKomposisiPaket/$prodID";
        $addFaseProdukKomposisiBomBiayaLink = MODUL_PATH . get_class($this) . "/addData/MdlProdukKomposisiPaket/$prodID";

        $produkKomposisiFase = "";
        //JIKA ADA UPDATE COPAS DARI SINI SAJA ( CONTROLLER->MasterData->showKomposisiProdukFase() )
        if (sizeof($produk_fase) > 0) {
//arrPrintHijau($produk_fase);
            $produkKomposisiFase .= "<div class='nav-tabs-custom'>";
            $produkKomposisiFase .= "<div class='tab-content no-padding'>";
            $produkKomposisiFase .= "<ul class='nav nav-tabs' id='custom-content-below-tab' role='tablist'>";

            $faseNoA = 0;
            foreach ($produk_fase as $fase_urut => $faseData) {
                if (isset($produk_komposisi_fase[$fase_urut])) {

                }
                else {
                    $faseNoA++;
                }
            }
            $faseNo = 0;
            $faseNoErr = 0;
            foreach ($produk_fase as $fase_urut => $faseData) {
                if (isset($produk_komposisi_fase[$fase_urut])) {
                    $actLink = $faseNo == 0 && $faseNoA == 0 ? "active" : "";
                    $produkKomposisiFase .= "
                    <li class='nav-item $actLink'>
                        <a class='nav-link' id='cc-tab-fase_$fase_urut' data-toggle='pill' href='#tab-fase_$fase_urut' role='tab' aria-controls='cc-tab-fase_$fase_urut' aria-selected='false'><span style='font-size: 12px;' class=''> <b>" . strtoupper(($faseData['nama'])) . "</b></span></a>
                    </li>";
                    $faseNo++;
                }
                else {
                    $actLink = $faseNoErr == 0 ? "active" : "";
                    $produkKomposisiFase .= "
                    <li class='nav-item $actLink'>
                        <a class='nav-link' id='cc-tab-fase_$fase_urut' data-toggle='pill' href='#tab-fase_$fase_urut' role='tab' aria-controls='cc-tab-fase_$fase_urut' aria-selected='false'><span style='font-size: 14px;' class='text-red'><i class='fa fa-warning blink text-yellow'></i> <b>" . strtoupper(($faseData['nama'])) . "</b></span></a>
                    </li>";
                    $faseNoErr++;
                }
            }
//arrPrintWebs($produk_fase);
//arrPrint($produk_komposisi_fase);
//            matiHere(__LINE__);
            $produkKomposisiFase .= "</ul>";
            $faseNoB = 0;
            foreach ($produk_fase as $fase_urut => $faseData) {
                if (isset($produk_komposisi_fase[$fase_urut])) {
                    $actLink = $faseNoB == 0 && $faseNoA == 0 ? "active in" : "";
                    $produkKomposisiFase .= "<div class='uu lv1 tab-pane fade $actLink' id='tab-fase_$fase_urut'>";
                    $produkKomposisiFase .= "
                        <div class='box box-solid'>
                        <div class='box-header with-border'>
                            <h3 class='box-title'><i class='fa fa-hand-o-right'></i>&nbsp;&nbsp;" . ($faseData['nama']) . "&nbsp;&nbsp;&nbsp;<small><i class='fa fa-clock-o text-muted'></i>&nbsp;" . date("Y-m-d H:i") . "</small></h3>
                        </div>
                        <div class='box-body'>";

                    $total_anggaran_unit = 0;
                    $jual_per_unit = 0;

                    foreach ($produk_komposisi_fase_header as $hFieldKey => $hLabelData) {
                        //MdlProjectKomposisiWorkorder
                        switch ($hFieldKey) {
                            case "item_komposit":
                            case "produk":
                                $idForm = "bahan_baku" . "$fase_urut";
                                $produkKomposisiFase .= "<div class='$idForm'>";
                                $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addFaseProdukKomposisiLink?mode=komposisi_fase&fase_id=$fase_urut'>";
                                $produkKomposisiFase .= "<table class='table dataTable compact display table-bordered table-condensed'>";
                                $produkKomposisiFase .= "<thead>";
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td>No</td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>$hLabel</td>";
                                }
                                if ($lock == 0) {
                                    $produkKomposisiFase .= "<td>action</td>";
                                }
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</thead>";
                                $produkKomposisiFase .= "<tbody>";
                                $produkKomposisiFase .= "<tr>";
                                $i = 0;
                                $arrSelected = array();
                                if (isset($produk_komposisi_fase[$fase_urut]["produk"]) && sizeof($produk_komposisi_fase[$fase_urut]["produk"])) {
                                    foreach ($produk_komposisi_fase[$fase_urut]["produk"] as $DataRelsupplies) {
                                        $tID = $DataRelsupplies["id"];
                                        $arrSelected[] = $DataRelsupplies["produk_dasar_id"];
                                        $produkKomposisiFase .= "<tr>";
                                        $i++;
                                        $produkKomposisiFase .= "<td>$i</td>";
                                        $tmpNamaMaterial = '';
                                        foreach ($hLabelData as $hField => $hLabel) {
                                            $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                                            $val = isset($DataRelsupplies[$transformKey]) ? $DataRelsupplies[$transformKey] : "";

//                                            cekMerah($hField);
//                                            cekMerah($transformKey);
//                                            cekMerah($val);
//                                            cekMerah("===============");

                                            $origVal=$val;
                                            $val = $hField=="produk_dasar_id" && strlen($val)>=34 ? substr($val,0,34)."..." : $val;

                                            $produkKomposisiFase .= "<td hField='$hField' title='$origVal'>" . formatField($hField, $val) . "</td>";
                                            $tmpNamaMaterial .= $hField == "produk_dasar_id" ? $val : "";
                                            $total_anggaran_unit += $hField == "subtotal" ? $val * 1 : 0;
                                        }
                                        $btn = "<div>";
                                        if (isset($allowedAccess["produk_dasar_id"]["update"]) && $allowedAccess["produk_dasar_id"]["update"] == true) {
                                            $btn .= "<button type='button' title='edit' class='btn btn-xs btn-flat btn-warning' onclick=\"showModal('" . $previewLink . "MdlProjectKomposisiWorkorder/$tID/$hField','edit $labelName')\"><span class='fa fa-fw fa-edit'></span></button>";
                                        }
                                        if (isset($allowedAccess["produk_dasar_id"]["delete"]) && $allowedAccess["produk_dasar_id"]["delete"] == true) {
                                            $btn .= "<button cx_jenis='produk' cx_tmpnamamaterial='$tmpNamaMaterial' cx_deletelink='$deleteLink' cx_tid='$tID' cx_mdl='ProjectKomposisiWorkorder' type='button' title='clik untuk menghapus realasi' class='btn btn-xs btn-flat btn-danger delWorkOrder'><i class='fa fa-fw fa-trash'></i></button>";
                                        }
                                        $btn .= "</div>";
                                        if ($lock == 0) {
                                            $produkKomposisiFase .= "<td >$btn</td>";
                                        }
                                        $produkKomposisiFase .= "</tr>";
                                    }
                                }

                                if (isset($produk_komposisi_fase[$fase_urut]["item_komposit"]) && sizeof($produk_komposisi_fase[$fase_urut]["item_komposit"])) {
                                    foreach ($produk_komposisi_fase[$fase_urut]["item_komposit"] as $DataRelsupplies) {
                                        $tID = $DataRelsupplies["id"];
                                        $arrSelected[] = $DataRelsupplies["produk_dasar_id"];
                                        $produkKomposisiFase .= "<tr>";
                                        $i++;
                                        $produkKomposisiFase .= "<td>$i</td>";
                                        $tmpNamaMaterial = '';
                                        foreach ($hLabelData as $hField => $hLabel) {
                                            $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                                            $val = isset($DataRelsupplies[$transformKey]) ? $DataRelsupplies[$transformKey] : "";

    //                                            cekMerah($hField);
    //                                            cekMerah($transformKey);
    //                                            cekMerah($val);
    //                                            cekMerah("===============");

                                            $origVal=$val;
                                            $val = $hField=="produk_dasar_id" && strlen($val)>=34 ? substr($val,0,34)."..." : $val;

                                            $produkKomposisiFase .= "<td hField='$hField' title='$origVal'>" . formatField($hField, $val) . "</td>";
                                            $tmpNamaMaterial .= $hField == "produk_dasar_id" ? $val : "";
                                            $total_anggaran_unit += $hField == "subtotal" ? $val * 1 : 0;
                                        }
                                        $btn = "<div>";
                                        if (isset($allowedAccess["produk_dasar_id"]["update"]) && $allowedAccess["produk_dasar_id"]["update"] == true) {
                                            $btn .= "<button type='button' title='edit' class='btn btn-xs btn-flat btn-warning' onclick=\"showModal('" . $previewLink . "MdlProjectKomposisiWorkorder/$tID/$hField','edit $labelName')\"><span class='fa fa-fw fa-edit'></span></button>";
                                        }
                                        if (isset($allowedAccess["produk_dasar_id"]["delete"]) && $allowedAccess["produk_dasar_id"]["delete"] == true) {
                                            $btn .= "<button cx_jenis='produk' cx_tmpnamamaterial='$tmpNamaMaterial' cx_deletelink='$deleteLink' cx_tid='$tID' cx_mdl='ProjectKomposisiWorkorder' type='button' title='clik untuk menghapus realasi' class='btn btn-xs btn-flat btn-danger delWorkOrder'><i class='fa fa-fw fa-trash'></i></button>";
                                        }
                                        $btn .= "</div>";
                                if ($lock == 0) {
                                            $produkKomposisiFase .= "<td >$btn</td>";
                                        }
                                        $produkKomposisiFase .= "</tr>";
                                    }
                                }

                                if ($lock == 0) {
                                    //untuk tambah komponen
                                    $produkKomposisiFase .= "</tr>";
                                    $produkKomposisiFase .= "<tr>";
                                    $produkKomposisiFase .= "<td><span style='font-size: 10px;' class='form-control form-control-sm no-border text-olive' title='pilih/isi '><i class='fa fa-plus-circle fa-2x'></i></span></td>";
                                    foreach ($hLabelData as $hField => $hLabel) {
                                        if (isset($produk_fase_komposisiEditable[$hField])) {
                                            if (isset($relSupplies[$hField])) {
                                                $strItem = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_fase&key=$hField&value='+encodeURI(this.value)+'', function(res){ eval(atob(res)); }); \">";
                                                $strItem .= "<option value='0'>==PILIH==</option>";
                                                $queryParams = "";
                                                foreach ($relSupplies[$hField] as $datas) {
                                                    $selected = isset($newData["komposisi_fase"][$fase_urut][$produkID][$hField]) && $newData["komposisi_fase"][$fase_urut][$produkID][$hField] == $datas['id'] ? "selected" : "";
                                                    $disable = in_array($datas['id'], $arrSelected, TRUE) ? "disabled" : "";
                                                    $iconCheck = in_array($datas['id'], $arrSelected, TRUE) ? "data-icon='fa fa-check-circle text-green'" : "";
                                                    $strItem .= "<option $iconCheck $selected $disable value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                                                }
                                                $strItem .= "</select>";
                                            }
                                            else {
                                                $value = isset($newData["komposisi_fase"][$fase_urut][$produkID][$hField]) ? $newData["komposisi_fase"][$fase_urut][$produkID][$hField] : "";
                                                $strItem = "<input id='komposisi_fase_$fase_urut$hField' class='form-control form-control-sm text-red text-bold text-right' type='text' value='$value' onfocus=\"this.select()\" onkeyup=\"this.value = addCommas(this.value)\" onblur=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_fase&key=$hField&value='+encodeURI(removeCommas(this.value))+'', function(res){ eval(atob(res)); });\">";
                                            }
                                        }
                                        else {
                                            $value = isset($newData["komposisi_fase"][$fase_urut][$produkID][$hField]) ? $newData["komposisi_fase"][$fase_urut][$produkID][$hField] : "";
                                            $defValue = is_numeric($value) ? number_format($value) : $value;
                                            $strItem = "<span id='komposisi_fase_$fase_urut$hField' class='form-control form-control-sm no-border text-red text-bold text-right'>$defValue</span>";
                                        }
                                        $produkKomposisiFase .= "<td>";
                                        $produkKomposisiFase .= $strItem;
                                        $produkKomposisiFase .= "</td>";
                                    }


                                    $btnRemoveFasekomposisi = "<button id='komposisi_fase_$fase_urut$produkID' idform='$idForm' disabled type='button' title='simpan komposisi baru' class='btn btn-sm'><i class='fa fa-plus'></i> tambah</button>";
                                    $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
                                    $produkKomposisiFase .= "</tr>";
                                }


                                $produkKomposisiFase .= "</tbody>";
                                $produkKomposisiFase .= "</table>";
                                $produkKomposisiFase .= "</form>";
                                $produkKomposisiFase .= "</div>";
                                break;
                            case "biaya":
                                $idForm = "biaya" . "$fase_urut";
                                $produkKomposisiFase .= "<div class='$idForm'>";
                                $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addProdukKomposisiBiayaLink?mode=komposisi_fase_biaya&fase_id=$fase_urut'>";
                                $produkKomposisiFase .= "<table line='".__LINE__."' class='table dataTable compact display table-bordered table-condensed'>";
                                $produkKomposisiFase .= "<thead>";
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td>No</td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>$hLabel</td>";
                                }
                                if ($lock == 0) {
                                    $produkKomposisiFase .= "<td>Action</td>";
                                }
                                $produkKomposisiFase .= "</tr>";

                                $produkKomposisiFase .= "</thead>";
                                $produkKomposisiFase .= "<tbody>";
                                //bagaian data relasi komposisi
                                // arrprint($relBiaya);
                                $i = 0;
                                $arrSelected = array();
                                if (isset($produk_komposisi_fase[$fase_urut]["biaya"]) && sizeof($produk_komposisi_fase[$fase_urut]["biaya"]) > 0) {
                                    foreach ($produk_komposisi_fase[$fase_urut]["biaya"] as $DataRelsuppliesBiaya) {
                                        $tID = $DataRelsuppliesBiaya["id"];

                                        $mainBiayaID = $DataRelsuppliesBiaya["produk_dasar_id"];
                                        $mainBiayaNama = $DataRelsuppliesBiaya["produk_dasar_nama"];
                                        $mainBiayaJml = $DataRelsuppliesBiaya["jml"];
                                        $arrSelected[] = $DataRelsuppliesBiaya["produk_dasar_id"];
                                        $produkKomposisiFase .= "<tr  style='cursor: pointer;' title='klik untuk melihat details biaya' tID='$tID' onclick=\"showDetBiaya($tID)\">";
                                        $i++;
                                        $tmpNamaMaterial = '';
                                        $col = count($hLabelData)+1;
                                        $produkKomposisiFase .= "<td>$i</td>";
                                        foreach ($hLabelData as $hField => $hLabel) {
                                            $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                                            $val = isset($DataRelsuppliesBiaya[$transformKey]) ? $DataRelsuppliesBiaya[$transformKey] : "";
                                            $produkKomposisiFase .= "<td hField='$hField'>" . formatField($hField, $val) . "</td>";
                                            $tmpNamaMaterial .= $hField == "produk_dasar_id" ? $val : "";
                                            $total_anggaran_unit += $hField == "subtotal" ? $val * 1 : 0;
                                        }

                                        $btn = "<div>";
                                        if (isset($allowedAccess["produk_dasar_id"]["delete"]) && $allowedAccess["produk_dasar_id"]["delete"] == true) {
                                            $btn .= "<button cx_jenis='biaya' cx_tmpnamamaterial='$tmpNamaMaterial' cx_deletelink='$deleteLink' cx_tid='$tID' cx_mdl='ProjectKomposisiWorkorder' type='button' title='clik untuk menghapus realasi' class='btn btn-xs btn-flat btn-danger delWorkOrder'><i class='fa fa-fw fa-trash'></i></button>";
                                        }
                                        $btn .= "</div>";

                                        if ($lock == 0) {
                                            $produkKomposisiFase .= "<td >$btn</td>";
                                        }

                                        $produkKomposisiFase .= "</tr>";

                                        //TR BIAYA DETAILS

                                        if (isset($produk_komposisi_fase[$fase_urut]["biaya_details"][$tID]) && count($produk_komposisi_fase[$fase_urut]["biaya_details"][$tID]) > 0) {
                                            $produkKomposisiFase .= "<tr id='by_det_$tID' class='hidden'>";
                                            $produkKomposisiFase .= "<td></td>";
                                            $produkKomposisiFase .= "<td colspan='$col' class=' bg-info'>";
                                            $produkKomposisiFase .= "<table class='table dataTable compact display table-bordered table-condensed'>";
                                            $produkKomposisiFase .= "<thead class='text-red text-bold'>";
                                            $produkKomposisiFase .= "<tr class='bg-olive'>";
                                            $produkKomposisiFase .= "<td>No</td>";
                                            foreach ($hLabelData as $hField => $hLabel) {
                                                $produkKomposisiFase .= "<td>$hLabel</td>";
                                            }
                                            if ($lock == 0) {
                                                $produkKomposisiFase .= "<td>Action</td>";
                                            }
                                            $produkKomposisiFase .= "</tr>";
                                            $produkKomposisiFase .= "</thead>";

                                            $produkKomposisiFase .= "<tbody>";

                                            $ib=0;
                                            $jml_=0;
                                            $hrg_=0;
                                            $subtotal_=0;

                                            foreach ($produk_komposisi_fase[$fase_urut]["biaya_details"][$tID] as $DataRelsuppliesBiayaDetails) {
                                                $ib++;

                                                $produkKomposisiFase .= "<tr>";
                                                $produkKomposisiFase .= "<td>$ib</td>";
                                                $produkKomposisiFase .= "<td transformKey='' hField=''>".$DataRelsuppliesBiayaDetails['biaya_dasar_nama']."</td>";
                                                $produkKomposisiFase .= "<td transformKey='' hField=''>".$DataRelsuppliesBiayaDetails['cat_nama']."</td>";
                                                $produkKomposisiFase .= "<td transformKey='' hField=''>".$DataRelsuppliesBiayaDetails['satuan']."</td>";
                                                $produkKomposisiFase .= "<td transformKey='' hField=''>".$DataRelsuppliesBiayaDetails['ket']."</td>";
                                                $produkKomposisiFase .= "<td class='text-right'  transformKey='' hField=''>".number_format($DataRelsuppliesBiayaDetails['harga'])."</td>";
                                                $produkKomposisiFase .= "<td class='text-center' transformKey='' hField=''>".number_format($DataRelsuppliesBiayaDetails['jml'])."</td>";
                                                $produkKomposisiFase .= "<td class='text-right'  transformKey='' hField=''>".number_format($DataRelsuppliesBiayaDetails['subtotal'])."</td>";
                                                $produkKomposisiFase .= "<td transformKey='' hField=''>-</td>";

                                                $jml_       += $DataRelsuppliesBiayaDetails['jml']*1;
                                                $hrg_       += $DataRelsuppliesBiayaDetails['harga']*1;
                                                $subtotal_  += $DataRelsuppliesBiayaDetails['subtotal']*1;

                                                $produkKomposisiFase .= "</tr>";
                                            }

                                            $produkKomposisiFase .= "</tbody>";

                                            $produkKomposisiFase .= "<tfoot>";
                                            $produkKomposisiFase .= "<tr style='font-weight:700;background: #80808085 !important;'>";
                                            $produkKomposisiFase .= "<td>-</td>";
                                            $produkKomposisiFase .= "<td>-</td>";
                                            $produkKomposisiFase .= "<td>-</td>";
                                            $produkKomposisiFase .= "<td>-</td>";
                                            $produkKomposisiFase .= "<td>-</td>";
                                            $produkKomposisiFase .= "<td class='text-right'  transformKey='' hField=''>".number_format($hrg_)."</td>";
                                            $produkKomposisiFase .= "<td class='text-center' transformKey='' hField=''>".number_format($jml_)."</td>";
                                            $produkKomposisiFase .= "<td class='text-right'  transformKey='' hField=''>".number_format($subtotal_)."</td>";
                                            $produkKomposisiFase .= "<td transformKey='' hField=''>-</td>";
                                            $produkKomposisiFase .= "</tr>";
                                            $produkKomposisiFase .= "</tfoot>";
                                            $produkKomposisiFase .= "</table>";
                                        }
                                        else{
                                            if ($lock == 1) {
                                                $by_det = "hidden";
                                            }
                                            $produkKomposisiFase .= "<tr id='by_det_$tID' class='$by_det'>";
                                            $produkKomposisiFase .= "<td colspan='$col' class='text-center text-info bg-red text-bold'><span> <i class='fa fa-warning text-yellow blink'></i>&nbsp;&nbsp;<i>$mainBiayaNama BELUM ADA DETAILS BIAYA</i>&nbsp;&nbsp;<a class='text-lowercase' href='javascript:void(0)' onclick=\"createDetailsBiaya('$produkID','$fase_urut','$mainBiayaID','$mainBiayaJml','$tID')\">buat disini</a></span></td>";
                                            $produkKomposisiFase .= "<script>
                                            function settingDetailBiaya(){
                                                console.log('akan diarahkan ke data');
                                                window.open('".base_url()."statik/Data/viewdt/DtaBiayaProduksi?gr=ZGJpYXlh&topGr=MdlDtaBiayaProduksi&md=data', '_blank').focus();
                                            }
                                            function createDetailsBiaya(project_id,fase_id,biaya_id,jml,link_id){

                                                swal({
                                                    title: 'Details Biaya Builder',
                                                    html: \"membuat detail biaya\",
                                                    type: 'warning',
                                                })
                                                .then((res)=>{
                                                    if(res){
                                                        $.ajax({
                                                          url: '".base_url()."master_project/MasterData/makeDetailsBiaya?project_id='+project_id+'&fase_id='+fase_id+'&biaya_id='+biaya_id+'&jml='+jml+'&link_id='+link_id,
                                                        }).done(function(aa) {
                                                            console.log(aa);
                                                            var  callback = JSON.parse(aa)
                                                            if(callback.status){
                                                                swal('SUKSES', 'berhasil build biaya detail..<br><r>browser anda akan di refresh dalam 3 detik...</r>', 'success');
                                                                swal.enableLoading();
                                                                setTimeout(function(){
                                                                    top.window.location.reload();
                                                                }, 1500)
                                                            }
                                                            else{
                                                                // console.log('hahaha');
                                                                var  reason = callback.reason;
                                                                swal('!!! Whoop...', reason, 'info');
                                                            }
                                                        });
                                                    }
                                                });
                                            }

                                            </script>";
                                        }

//                                        if ($lock == 0) {
//                                            //untuk tambah komponen details biaya
//                                            $produkKomposisiFase .= "<tr>";
//                                            $produkKomposisiFase .= "<td><span style='font-size: 10px;' class='form-control form-control-sm no-border text-olive' title='pilih/isi '><i class='fa fa-plus-circle fa-2x'></i></span></td>";
//                                            foreach ($hLabelData as $hField => $hLabel) {
//                                                if (isset($produk_fase_komposisiEditable[$hField])) {
//                                                    if (isset($relBiaya[$hField])) {
//                                                        $strItem = "<select data-style=\"btn btn-xs btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_fase_biaya_details&biaya_id=$tID&key=$hField&value='+encodeURI(this.value)+'', function(res){ eval(atob(res)); }); \">";
//                                                        $strItem .= "<option value='0'>==PILIH==</option>";
//                                                        foreach ($relBiaya[$hField] as $datas) {
//                                                            $selected = isset($newData["komposisi_fase_biaya_details"][$fase_urut][$produkID][$tID][$hField]) && $newData["komposisi_fase_biaya_details"][$fase_urut][$produkID][$tID][$hField] == $datas['id'] ? "selected" : "";
//                                                            $disable = "";
//                                                            $iconCheck = in_array($datas['id'], $arrSelected, TRUE) ? "data-icon='fa fa-check-circle text-green'" : "";
//                                                            $strItem .= "<option $iconCheck $selected $disable value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
//                                                        }
//                                                        $strItem .= "</select>";
//                                                    }
//                                                    else {
//                                                        $value = isset($newData["komposisi_fase_biaya_details"][$fase_urut][$produkID][$tID][$hField]) ? $newData["komposisi_fase_biaya_details"][$fase_urut][$produkID][$tID][$hField] : "";
//                                                        if (is_numeric($value)) {
//                                                            $strItem = "<input id='komposisi_fase_biaya_details_$fase_urut$tID$hField' class='form-control form-control-sm text-red text-bold text-right' type='text' value='$value' onfocus=\"this.select()\" onkeyup=\"this.value = addCommas(this.value)\" onblur=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_fase_biaya_details&biaya_id=$tID&key=$hField&value='+encodeURI(removeCommas(this.value))+'', function(res){ eval(atob(res)); });\">";
//                                                        }
//                                                        else {
//                                                            $strItem = "<input id='komposisi_fase_biaya_details_$fase_urut$tID$hField' class='form-control form-control-sm text-red text-bold text-right' type='text' value='$value' onfocus=\"this.select()\" onblur=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_fase_biaya_details&biaya_id=$tID&key=$hField&value='+encodeURI(this.value)+'', function(res){ eval(atob(res)); });\">";
//                                                        }
//                                                    }
//                                                }
//                                                else {
//                                                    $value = isset($newData["komposisi_fase_biaya_details"][$fase_urut][$produkID][$tID][$hField]) ? $newData["komposisi_fase_biaya_details"][$fase_urut][$produkID][$tID][$hField] : "";
//                                                    $defValue = is_numeric($value) ? number_format($value) : $value;
//                                                    $strItem = "<span id='komposisi_fase_biaya_details_$fase_urut$tID$hField' class='form-control form-control-sm no-border text-red text-bold text-right'>$defValue</span>";
//                                                }
//                                                $produkKomposisiFase .= "<td>";
//                                                $produkKomposisiFase .= $strItem;
//                                                $produkKomposisiFase .= "</td>";
//                                            }
//                                            $btnRemoveFasekomposisi = "<button id='komposisi_fase_biaya_details_$fase_urut$produkID$tID' idform='$idForm' disabled type='button' title='simpan komposisi biaya details' class='btn btn-xs'><i class='fa fa-plus'></i> tambah</button>";
//                                            $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
//                                            $produkKomposisiFase .= "</tr>";
//                                        }

//                                        $jml_ += $DataRelsuppliesBiayaDetails['jml']*1;
//                                        $hrg_ += $DataRelsuppliesBiayaDetails['harga']*1;
//                                        $subtotal_ += $DataRelsuppliesBiayaDetails['subtotal']*1;




                                        $produkKomposisiFase .= "</td>";
                                        $produkKomposisiFase .= "</tr>";
                                        $produkKomposisiFase .= "
                                        <script>
                                        function showDetBiaya(ids){
                                            if( top.$('#by_det_'+ids).hasClass('hidden') ){
                                                top.$('#by_det_'+ids).removeClass('hidden');
                                                top.$('tr[tid='+ids+']').addClass('bg-yellow');
                                            }
                                            else{
                                                top.$('#by_det_'+ids).addClass('hidden');
                                                top.$('tr[tid='+ids+']').removeClass('bg-yellow');
                                            }
                                        }
                                        </script>
                                        ";
                                    }
                                }

                                if ($lock == 0) {
                                    //untuk tambah komponen
                                    $produkKomposisiFase .= "<tr>";
                                    $produkKomposisiFase .= "<td><span style='font-size: 10px;' class='form-control form-control-sm no-border text-olive' title='pilih/isi '><i class='fa fa-plus-circle fa-2x'></i></span></td>";
                                    foreach ($hLabelData as $hField => $hLabel) {
                                        if (isset($produk_fase_komposisiEditable[$hField])) {
                                            if (isset($relBiaya[$hField])) {
                                                $strItem = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_fase_biaya&key=$hField&value='+encodeURI(this.value)+'', function(res){ eval(atob(res)); }); \">";
                                                $strItem .= "<option value='0'>==PILIH==</option>";
                                                foreach ($relBiaya[$hField] as $datas) {
                                                    $selected = isset($newData["komposisi_fase_biaya"][$fase_urut][$produkID][$hField]) && $newData["komposisi_fase_biaya"][$fase_urut][$produkID][$hField] == $datas['id'] ? "selected" : "";
//                                                    $disable = in_array($datas['id'], $arrSelected, TRUE) ? "disabled" : "";
                                                    $disable = "";
                                                    $iconCheck = in_array($datas['id'], $arrSelected, TRUE) ? "data-icon='fa fa-check-circle text-green'" : "";
                                                    $strItem .= "<option $iconCheck $selected $disable value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                                                }
                                                $strItem .= "</select>";
                                            }
                                            else {
                                                $value = isset($newData["komposisi_fase_biaya"][$fase_urut][$produkID][$hField]) ? $newData["komposisi_fase_biaya"][$fase_urut][$produkID][$hField] : "";
                                                if (is_numeric($value)) {
                                                    $strItem = "<input id='komposisi_fase_biaya_$fase_urut$hField' class='form-control form-control-sm text-red text-bold text-right' type='text' value='$value' onfocus=\"this.select()\" onkeyup=\"this.value = addCommas(this.value)\" onblur=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_fase_biaya&key=$hField&value='+encodeURI(removeCommas(this.value))+'', function(res){ eval(atob(res)); });\">";
                                                }
                                                else {
                                                    $strItem = "<input id='komposisi_fase_biaya_$fase_urut$hField' class='form-control form-control-sm text-red text-bold text-right' type='text' value='$value' onfocus=\"this.select()\" onblur=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_fase_biaya&key=$hField&value='+encodeURI(this.value)+'', function(res){ eval(atob(res)); });\">";
                                                }
                                            }
                                        }
                                        else {
                                            $value = isset($newData["komposisi_fase_biaya"][$fase_urut][$produkID][$hField]) ? $newData["komposisi_fase_biaya"][$fase_urut][$produkID][$hField] : "";
                                            $defValue = is_numeric($value) ? number_format($value) : $value;
                                            $strItem = "<span id='komposisi_fase_biaya_$fase_urut$hField' class='form-control form-control-sm no-border text-red text-bold text-right'>$defValue</span>";
//                                        $strItem = "<input id='inhide_komposisi_fase_biaya_$fase_urut$hField' class='hidden' value='$defValue'>";
                                        }
                                        $produkKomposisiFase .= "<td>";
                                        $produkKomposisiFase .= $strItem;
                                        $produkKomposisiFase .= "</td>";
                                    }
                                    $btnRemoveFasekomposisi = "<button id='komposisi_fase_biaya_$fase_urut$produkID' idform='$idForm' disabled type='button' title='simpan komposisi biaya' class='btn btn-sm'><i class='fa fa-plus'></i> tambah</button>";
                                    $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
                                    $produkKomposisiFase .= "</tr>";
                                }

                                $produkKomposisiFase .= "</tbody>";
                                $produkKomposisiFase .= "</table>";
                                $produkKomposisiFase .= "</form>";
                                $produkKomposisiFase .= "</div>";
                                break;
                            case "timwork":
                                $idForm = "timwork" . "$fase_urut";
                                $produkKomposisiFase .= "<div class='$idForm'>";
                                $produkKomposisiFase .= "<div class='panel'>";
                                $produkKomposisiFase .= "<div class='panel-header'><h4>Tim Kerja</h4></div>";
                                $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addFaseProdukKomposisiTimLink?mode=komposisi_fase_timwork&fase_id=$fase_urut$targetResult'>";
                                $produkKomposisiFase .= "<table class='table dataTable compact display table-bordered table-condensed'>";
                                $produkKomposisiFase .= "<thead>";
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td>No</td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>$hLabel</td>";
                                }
                                $produkKomposisiFase .= "<td>Action</td>";
                                $produkKomposisiFase .= "</tr>";

                                $produkKomposisiFase .= "</thead>";
                                $produkKomposisiFase .= "<tbody>";
                                //bagaian data relasi komposisi
                                // arrprint($relBiaya);
                                $i = 0;
                                if (isset($produk_komposisi_fase[$fase_urut]["timwork"]) && sizeof($produk_komposisi_fase[$fase_urut]["timwork"]) > 0) {
                                    foreach ($produk_komposisi_fase[$fase_urut]["timwork"] as $DataRelsuppliesBiaya) {
                                        //                                        arrPrint($DataRelsuppliesBiaya);
                                        $produkKomposisiFase .= "<tr>";
                                        $i++;
                                        $produkKomposisiFase .= "<td>$i</td>";
                                        foreach ($hLabelData as $hField => $hLabel) {
                                            $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                                            $val = isset($DataRelsuppliesBiaya[$transformKey]) ? $DataRelsuppliesBiaya[$transformKey] : "";
                                            $produkKomposisiFase .= "<td>" . formatField($hField, $val) . "</td>";
                                        }
                                        $produkKomposisiFase .= "</tr>";
                                    }
                                }

                                //untuk tambah komponen
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td></td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    if (isset($produk_fase_komposisiEditable[$hField])) {
                                        if (isset($relEmployee[$hField])) {
                                            $strItem = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase_timwork&key=$hField&value='+encodeURI(this.value)+'$targetResult'); \">";
                                            $strItem .= "<option value='0'>==PILIH==</option>";
                                            foreach ($relEmployee[$hField] as $datas) {
                                                $selected = isset($newData["komposisi_fase_timwork"][$produkID][$hField]) && $newData["komposisi_fase_timwork"][$produkID][$hField] == $datas['id'] ? "selected" : "";
                                                $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                                            }
                                            $strItem .= "</select>";

                                        }
                                        else {
                                            $value = isset($newData["komposisi_fase_timwork"][$produkID][$hField]) ? $newData["komposisi_fase_timwork"][$produkID][$hField] : "";
                                            $strItem = "<input class='form-control form-control-sm' type='text' value='$value' onblur=\"$('#input_temp').load('$selector" . "$produkID?mode=komposisi_fase_timwork&key=$hField&value='+encodeURI(this.value)+'$targetResult');\">";
                                        }
                                    }
                                    else {
                                        $value = isset($newData["komposisi_fase_timwork"][$produkID][$hField]) ? $newData["komposisi_fase_timwork"][$produkID][$hField] : "";
                                        $strItem = formatField($hField, $value);
                                        // $strItem ="";
                                    }
                                    $produkKomposisiFase .= "<td>";
                                    $produkKomposisiFase .= $strItem;
                                    $produkKomposisiFase .= "</td>";
                                }
                                $btnRemoveFasekomposisi = "<button onclick=\"document.getElementById('$idForm').submit();\" type='button' title='simpan tim kerja' class='btn btn-sm btn-success'><i class='fa fa-plus'></i>  tambah</button>";
                                $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
                                $produkKomposisiFase .= "</tr>";


                                $produkKomposisiFase .= "</tbody>";
                                $produkKomposisiFase .= "</table>";
                                $produkKomposisiFase .= "</form>";
                                $produkKomposisiFase .= "</div>";//clas panel
                                $produkKomposisiFase .= "</div>";
                                break;
                            case "target":
                                // $idForm = "target" . $fase_urut;
                                // $produkKomposisiFase .= "<div class=''>";
                                // $produkKomposisiFase .= "<table class='table table-bordered'>";
                                // $produkKomposisiFase .= "<thead>";
                                // $produkKomposisiFase .= "<tr>";
                                // foreach ($hLabelData as $hField => $hLabel) {
                                //     $produkKomposisiFase .= "<td>$hLabel</td>";
                                // }
                                //
                                // $produkKomposisiFase .= "</tr>";
                                // $produkKomposisiFase .= "</thead>";
                                // $produkKomposisiFase .= "<tbody>";
                                //
                                // $produkKomposisiFase .= "<tr>";
                                // foreach ($hLabelData as $hField => $hLabel) {
                                //     if (isset($produk_fase_komposisiEditable[$hField])) {
                                //         if (isset($relTarget[$hField])) {
                                //             $strItem = "<select data-style=\"btn-primary\" class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$addFaseHasilProduksi" . "/$produkID?mode=komposisi_target&key=$hField&&fase_id=$fase_urut&value='+encodeURI(this.value)+'$targetResult'); \">";
                                //             $strItem .= "<option value='0'> ---silahkan pilih--</option>";
                                //             foreach ($relTarget[$hField] as $datas) {
                                //                 $selected = isset($currentTargetWip[$produkID][$fase_urut][$hField]) && $currentTargetWip[$produkID][$fase_urut][$hField] == $datas['id'] ? "selected" : "";
                                //                 // $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] ."||". $currentTargetWip[$produkID][$fase_urut][$hField]."</option>";
                                //                 $strItem .= "<option $selected value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                                //             }
                                //             $strItem .= "</select>";
                                //         }
                                //
                                //     }
                                //
                                //     $produkKomposisiFase .= "<td>";
                                //     $produkKomposisiFase .= $strItem;
                                //     $produkKomposisiFase .= "</td>";
                                // }
                                // $produkKomposisiFase .= "</tr>";
                                // $produkKomposisiFase .= "</tbody>";
                                // $produkKomposisiFase .= "</table>";
                                // $produkKomposisiFase .= "</div>";
                                break;
                            case "jual":
//                                arrPrint($produk_komposisi_fase);

                                if (isset($produk_komposisi_fase[$fase_urut]["jual"]) && sizeof($produk_komposisi_fase[$fase_urut]["jual"])) {
                                    foreach ($produk_komposisi_fase[$fase_urut]["jual"] as $DataRelsupplies) {
                                        foreach ($hLabelData as $hField => $hLabel) {
                                            $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                                            $val = isset($DataRelsupplies[$transformKey]) ? $DataRelsupplies[$transformKey] : "";
                                            $jual_per_unit += $hField == "subtotal" ? $val * 1 : 0;
                                        }
                                    }
                                }

                                break;
                            case "room":
                                $produkKomposisiRoom = "";
                                $idForm = "rooms" . "$fase_urut";
                                $produkKomposisiRoom .= "<div class='$idForm'>";
                                $produkKomposisiRoom .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addFaseProdukKomposisiLink?mode=komposisi_fase_room&fase_id=$fase_urut'>";
                                $produkKomposisiRoom .= "<table class='table dataTable compact display table-bordered table-condensed'>";
                                $produkKomposisiRoom .= "<thead>";
                                $produkKomposisiRoom .= "<tr>";
                                $produkKomposisiRoom .= "<td>No</td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiRoom .= "<td>$hLabel</td>";
                                }

                                if ($lock == 0) {
                                    $produkKomposisiRoom .= "<td>action</td>";
                                }

                                $produkKomposisiRoom .= "</tr>";
                                $produkKomposisiRoom .= "</thead>";
                                $produkKomposisiRoom .= "<tbody>";
                                $produkKomposisiRoom .= "<tr>";
                                $i = 0;
                                $arrSelectedRooms = array();
                                if (isset($produk_komposisi_fase[$fase_urut]["room"]) && sizeof($produk_komposisi_fase[$fase_urut]["room"])) {
                                    foreach ($produk_komposisi_fase[$fase_urut]["room"] as $DataRelsupplies) {
                                        $tID = $DataRelsupplies["id"];
                                        $arrSelectedRooms[] = $DataRelsupplies["produk_dasar_id"];
                                        $produkKomposisiRoom .= "<tr>";
                                        $i++;
                                        $produkKomposisiRoom .= "<td>$i</td>";
                                        $tmpNamaMaterial = '';
                                        foreach ($hLabelData as $hField => $hLabel) {
                                            $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                                            $val = isset($DataRelsupplies[$transformKey]) ? $DataRelsupplies[$transformKey] : "";
                                            $produkKomposisiRoom .= "<td hField='$hField'>" . formatField($hField, $val) . "</td>";
                                            $tmpNamaMaterial .= $hField == "room_nama" ? $val : "";
                                        }
                                        $btn = "<div>";
                                        if (isset($allowedAccess["produk_dasar_id"]["update"]) && $allowedAccess["produk_dasar_id"]["update"] == true) {
                                            $btn .= "<button type='button' title='edit' class='btn btn-xs btn-flat btn-warning' onclick=\"showModal('" . $previewLink . "MdlProjectKomposisiWorkorderRoom/$tID','edit $labelName')\"><span class='fa fa-fw fa-edit'></span></button>";
                                        }
                                        if (isset($allowedAccess["produk_dasar_id"]["delete"]) && $allowedAccess["produk_dasar_id"]["delete"] == true) {
                                            $btn .= "<button cx_jenis='room' cx_tmpnamamaterial='$tmpNamaMaterial' cx_deletelink='$deleteLink' cx_tid='$tID' cx_mdl='ProjectKomposisiWorkorderRoom' type='button' title='click untuk menghapus realasi' class='btn btn-xs btn-flat btn-danger delWorkOrder'><i class='fa fa-fw fa-trash'></i></button>";
                                        }
                                        $btn .= "</div>";

                                        if ($lock == 0) {
                                            $produkKomposisiRoom .= "<td>$btn</td>";
                                        }

                                        $produkKomposisiRoom .= "</tr>";
                                    }
                                }
                                $produkKomposisiRoom .= "</tr>";


                                if ($lock == 0) {
                                    //untuk tambah komponen
                                    $produkKomposisiRoom .= "<tr>";
                                    $produkKomposisiRoom .= "<td><span style='font-size: 10px;' class='form-control form-control-sm no-border text-olive' title='pilih/isi '><i class='fa fa-plus-circle fa-2x'></i></span></td>";
                                    foreach ($hLabelData as $hField => $hLabel) {
                                        if (isset($produk_fase_komposisiEditable[$hField])) {
                                            $value = isset($newData["komposisi_fase_room"][$fase_urut][$produkID][$hField]) ? $newData["komposisi_fase_room"][$fase_urut][$produkID][$hField] : "";
                                            $strItem = "<input id='komposisi_fase_room_$fase_urut$hField' class='form-control' type='text' value='$value' onfocus=\"this.select()\" onblur=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_fase_room&key=$hField&value='+encodeURI(this.value)+'', function(res){ eval(atob(res)) });\">";
                                        }
                                        else {
                                            $value = isset($newData["komposisi_fase_room"][$fase_urut][$produkID][$hField]) ? $newData["komposisi_fase_room"][$fase_urut][$produkID][$hField] : "";
                                            $defValue = is_numeric($value) ? number_format($value) : $value;
                                            $strItem = "<span id='komposisi_fase_room_$fase_urut$hField' class='form-control'>$defValue</span>";
                                        }
                                        $produkKomposisiRoom .= "<td>";
                                        $produkKomposisiRoom .= $strItem;
                                        $produkKomposisiRoom .= "</td>";
                                    }

                                    $btnRemoveFasekomposisiRoom = "<button id='komposisi_fase_room_$fase_urut$produkID' idform='$idForm' disabled type='button' title='simpan komposisi baru' class='btn btn-sm'><i class='fa fa-plus'></i> tambah</button>";
                                    $produkKomposisiRoom .= "<td class='text-center'>$btnRemoveFasekomposisiRoom</td>";
                                    $produkKomposisiRoom .= "</tr>";
                                }


                                $produkKomposisiRoom .= "</tbody>";
                                $produkKomposisiRoom .= "</table>";
                                $produkKomposisiRoom .= "</form>";
                                $produkKomposisiRoom .= "</div>";

                                $produkKomposisiRoom .= "<div style='margin-top: 40px;' class='box box-success'>";
                                $produkKomposisiRoom .= "<div class='box-header text-bold fa-2x text-blue'>
                                                            DETAIL MATERIAL PER RUANGAN
                                                            <div class='meta'></div>
                                                        </div>";
                                $produkKomposisiRoom .= "<div class='box-body no-padding'>";

                                $produkKomposisiRoom .= "<div class='nav-tabs-custom'>";
                                $produkKomposisiRoom .= "<div class='tab-content no-padding'>";

                                if (isset($produk_komposisi_fase[$fase_urut]["room"]) && sizeof($produk_komposisi_fase[$fase_urut]["room"])) {
                                    $produkKomposisiRoom .= "<ul class='nav nav-tabs' id='custom-content-below-tab' role='tablist'>";
                                    $numTab = 0;
                                    foreach ($produk_komposisi_fase[$fase_urut]["room"] as $DataRelsupplies) {
                                        $numTab++;
                                        $val = $DataRelsupplies['room_nama'];
                                        $selID = $DataRelsupplies['room_id'];
                                        $selActive = $numTab == 1 ? "active" : "";
                                        $produkKomposisiRoom .= "<li class='nav-item $selActive'>";
                                        $produkKomposisiRoom .= "<a class='nav-link' id='cc-tab-room_$selID' data-toggle='pill' href='#tab-room_$selID' role='tab' aria-controls='cc-tab-room_$selID' aria-selected='false'>";
                                        $produkKomposisiRoom .= "<span style='font-size: 12px;' class='text-bold text-uppercase'> <b>$val</b></span>";
                                        $produkKomposisiRoom .= "</a>";
                                        $produkKomposisiRoom .= "</li>";
                                    }
                                    $produkKomposisiRoom .= "</ul>";

                                    $numTabCont = 0;
                                    foreach ($produk_komposisi_fase[$fase_urut]["room"] as $DataRelsupplies) {
                                        $numTabCont++;
                                        $selID = $DataRelsupplies['room_id'];
                                        $selNama = $DataRelsupplies['room_nama'];
                                        $selActive = $numTabCont == 1 ? "active in" : "";
                                        $produkKomposisiRoom .= "<div class='tab-pane fade $selActive' id='tab-room_$selID'>";
                                        foreach ($produk_komposisi_fase_header as $hFieldKey => $hLabelData) {
                                            switch ($hFieldKey) {
                                                case "produk_room":
                                                    $produkKomposisiRoom .= "<div class='text-uppercase text-bold'>------------------------------- BAHAN BAKU $selNama -------------------------------</div>";
                                                    $idForm = "rooms" . "$fase_urut$selID";
                                                    $produkKomposisiRoom .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addFaseProdukKomposisiLink?mode=produk_komposisi_fase_room&fase_id=$fase_urut&selID=$selID'>";
                                                    $produkKomposisiRoom .= "<table class='table dataTable compact display table-bordered table-condensed'>";
                                                    $produkKomposisiRoom .= "<thead>";
                                                    $produkKomposisiRoom .= "<tr>";
                                                    $produkKomposisiRoom .= "<td>No</td>";

                                                    foreach ($hLabelData as $hField => $hLabel) {
                                                        $produkKomposisiRoom .= "<td>$hLabel</td>";
                                                    }

                                                    if ($lock == 0) {
                                                        $produkKomposisiRoom .= "<td>action</td>";
                                                    }

                                                    $produkKomposisiRoom .= "</tr>";
                                                    $produkKomposisiRoom .= "</thead>";
                                                    $produkKomposisiRoom .= "<tbody>";
                                                    $produkKomposisiRoom .= "<tr>";
                                                    $i = 0;

                                                    $arrSelected = array();
                                                    if (isset($produk_komposisi_fase[$fase_urut]["produk_room"][$selID]) && sizeof($produk_komposisi_fase[$fase_urut]["produk_room"][$selID])) {
                                                        foreach ($produk_komposisi_fase[$fase_urut]["produk_room"][$selID] as $DataRelsupplies) {
                                                            $tID = $DataRelsupplies["id"];
                                                            $arrSelected[] = $DataRelsupplies["produk_dasar_id"];
                                                            $produkKomposisiRoom .= "<tr>";
                                                            $i++;
                                                            $produkKomposisiRoom .= "<td>$i</td>";
                                                            $tmpNamaMaterial = '';
                                                            foreach ($hLabelData as $hField => $hLabel) {
                                                                $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                                                                $val = isset($DataRelsupplies[$transformKey]) ? $DataRelsupplies[$transformKey] : "";
                                                                $produkKomposisiRoom .= "<td hField='$hField'>" . formatField($hField, $val) . "</td>";
                                                                $tmpNamaMaterial .= $hField == "produk_dasar_id" ? $val : "";
                                                            }

                                                            $btn = "";
                                                            if (isset($allowedAccess["produk_dasar_id"]["delete"]) && $allowedAccess["produk_dasar_id"]["delete"] == true) {
                                                                $btn .= "<div>";
                                                                $btn .= "<button cx_jenis='room' cx_tmpnamamaterial='$tmpNamaMaterial' cx_deletelink='$deleteLink' cx_tid='$tID' cx_mdl='ProjectKomposisiWorkorderRoomProduk' type='button' title='click untuk menghapus realasi' class='btn btn-xs btn-flat btn-danger delWorkOrder'><i class='fa fa-fw fa-trash'></i></button>";
                                                                $btn .= "</div>";
                                                            }

                                                            if ($lock == 0) {
                                                                $produkKomposisiRoom .= "<td>$btn</td>";
                                                            }

                                                            $produkKomposisiRoom .= "</tr>";
                                                        }
                                                    }
                                                    $produkKomposisiRoom .= "</tr>";

                                                    if ($lock == 0) {
                                                        //untuk tambah komponen
                                                        $produkKomposisiRoom .= "<tr>";
                                                        $produkKomposisiRoom .= "<td><span style='font-size: 10px;' class='form-control form-control-sm no-border text-olive' title='pilih/isi '><i class='fa fa-plus-circle fa-2x'></i></span></td>";
                                                        foreach ($hLabelData as $hField => $hLabel) {
                                                            if (isset($produk_fase_komposisiRoomEditable[$hField])) {
                                                                if (isset($relSupplies[$hField])) {
                                                                    $strItem = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=produk_komposisi_fase_room&key=$hField&value='+encodeURI(this.value)+'&selID=$selID', function(res){ eval(atob(res)) }); \">";
                                                                    $strItem .= "<option value='0'>==PILIH==</option>";
                                                                    $queryParams = "";
                                                                    foreach ($produk_komposisi_fase[$fase_urut]["produk"] as $datas) {
                                                                        $selected = isset($newData["produk_komposisi_fase_room"][$fase_urut][$selID][$produkID][$hField]) && $newData["produk_komposisi_fase_room"][$fase_urut][$selID][$produkID][$hField] == $datas['produk_dasar_id'] ? "selected" : "";
                                                                        $disable = in_array($datas['produk_dasar_id'], $arrSelected, TRUE) ? "disabled" : "";
                                                                        $iconCheck = in_array($datas['produk_dasar_id'], $arrSelected, TRUE) ? "data-icon='fa fa-check-circle text-green'" : "";
                                                                        $strItem .= "<option $iconCheck $selected $disable value='" . $datas['produk_dasar_id'] . "'>" . $datas['produk_dasar_nama'] . "</option>";
                                                                    }
                                                                    $strItem .= "</select>";
                                                                }
                                                                else {
                                                                    $value = isset($newData["produk_komposisi_fase_room"][$fase_urut][$selID][$produkID][$hField]) ? $newData["produk_komposisi_fase_room"][$fase_urut][$selID][$produkID][$hField] : "";
                                                                    $strItem = "<input size='4' disabled id='produk_komposisi_fase_room_$fase_urut$selID$hField' class='form-control form-control-sm text-red text-bolds text-right' type='text' value='$value' onfocus=\"this.select()\" onkeyup=\"this.value = addCommas(this.value)\" onblur=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=produk_komposisi_fase_room&key=$hField&value='+encodeURI(removeCommas(this.value))+'&selID=$selID', function(res){ eval(atob(res)) });\">";
                                                                }
                                                            }
                                                            else {
                                                                $value = isset($newData["produk_komposisi_fase_room"][$fase_urut][$selID][$produkID][$hField]) ? $newData["produk_komposisi_fase_room"][$fase_urut][$selID][$produkID][$hField] : "";
                                                                $defValue = is_numeric($value) ? number_format($value) : $value;
                                                                $strItem = "<span id='produk_komposisi_fase_room_$fase_urut$selID$hField' class='form-control form-control-sm no-border text-red text-bolds text-right'>$defValue</span>";
                                                            }
                                                            $produkKomposisiRoom .= "<td>";
                                                            $produkKomposisiRoom .= $strItem;
                                                            $produkKomposisiRoom .= "</td>";
                                                        }


                                                        $btnRemoveFasekomposisi = "<button id='produk_komposisi_fase_room_$fase_urut$selID$produkID' idform='$idForm' disabled type='button' title='simpan komposisi baru' class='btn btn-sm'><i class='fa fa-plus'></i> tambah</button>";
                                                        $produkKomposisiRoom .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
                                                        $produkKomposisiRoom .= "</tr>";
                                                    }

                                                    $produkKomposisiRoom .= "</tbody>";
                                                    $produkKomposisiRoom .= "</table>";
                                                    $produkKomposisiRoom .= "</form>";

                                                    break;
                                                case "biaya_room":

                                                    $produkKomposisiRoom .= "<div class='text-uppercase text-bold'>------------------------------- BIAYA/JASA $selNama -------------------------------</div>";

                                                    $idForm = "biayarooms" . "$fase_urut$selID";
                                                    $produkKomposisiRoom .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addFaseProdukKomposisiLink?mode=produk_komposisi_fase_room_biaya&fase_id=$fase_urut&selID=$selID'>";
                                                    $produkKomposisiRoom .= "<table class='table dataTable compact display table-bordered table-condensed'>";
                                                    $produkKomposisiRoom .= "<thead>";
                                                    $produkKomposisiRoom .= "<tr>";
                                                    $produkKomposisiRoom .= "<td>No</td>";
                                                    foreach ($hLabelData as $hField => $hLabel) {
                                                        $produkKomposisiRoom .= "<td>$hLabel</td>";
                                                    }

                                                    if ($lock == 0) {
                                                        $produkKomposisiRoom .= "<td>action</td>";
                                                    }

                                                    $produkKomposisiRoom .= "</tr>";
                                                    $produkKomposisiRoom .= "</thead>";
                                                    $produkKomposisiRoom .= "<tbody>";
                                                    $produkKomposisiRoom .= "<tr>";
                                                    $i = 0;

                                                    $arrSelected = array();
                                                    if (isset($produk_komposisi_fase[$fase_urut]["biaya_room"][$selID]) && sizeof($produk_komposisi_fase[$fase_urut]["biaya_room"][$selID])) {
                                                        foreach ($produk_komposisi_fase[$fase_urut]["biaya_room"][$selID] as $DataRelsupplies) {
                                                            $tID = $DataRelsupplies["id"];
                                                            $arrSelected[] = $DataRelsupplies["produk_dasar_id"];
                                                            $produkKomposisiRoom .= "<tr>";
                                                            $i++;
                                                            $produkKomposisiRoom .= "<td>$i</td>";
                                                            $tmpNamaMaterial = '';
                                                            foreach ($hLabelData as $hField => $hLabel) {
                                                                $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                                                                $val = isset($DataRelsupplies[$transformKey]) ? $DataRelsupplies[$transformKey] : "";
                                                                $produkKomposisiRoom .= "<td hField='$hField'>" . formatField($hField, $val) . "</td>";
                                                                $tmpNamaMaterial .= $hField == "produk_dasar_id" ? $val : "";
                                                            }
                                                            $btn = "<div>";
                                                            if (isset($allowedAccess["produk_dasar_id"]["delete"]) && $allowedAccess["produk_dasar_id"]["delete"] == true) {
                                                                $btn .= "<button cx_jenis='room' cx_tmpnamamaterial='$tmpNamaMaterial' cx_deletelink='$deleteLink' cx_tid='$tID' cx_mdl='ProjectKomposisiWorkorderRoomProdukBiaya' type='button' title='click untuk menghapus realasi' class='btn btn-xs btn-flat btn-danger delWorkOrder'><i class='fa fa-fw fa-trash'></i></button>";
                                                            }
                                                            $btn .= "</div>";

                                                            if ($lock == 0) {
                                                                $produkKomposisiRoom .= "<td>$btn</td>";
                                                            }

                                                            $produkKomposisiRoom .= "</tr>";
                                                        }
                                                    }
                                                    $produkKomposisiRoom .= "</tr>";

                                                    if ($lock == 0) {
                                                        arrPrint($produk_komposisi_fase[$fase_urut]["biaya"]);
                                                        //untuk tambah komponen
                                                        $produkKomposisiRoom .= "<tr>";
                                                        $produkKomposisiRoom .= "<td><span style='font-size: 10px;' class='form-control form-control-sm no-border text-olive' title='pilih/isi '><i class='fa fa-plus-circle fa-2x'></i></span></td>";
                                                        foreach ($hLabelData as $hField => $hLabel) {
                                                            if (isset($produk_fase_komposisiRoomEditable[$hField])) {
                                                                if (isset($relSupplies[$hField])) {
//                                                                    $hField = $hField=="produk_dasar_id" ? "id" : $hField;
                                                                    $strItem = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=produk_komposisi_fase_room_biaya&key=$hField&value='+encodeURI(this.value)+'&selID=$selID', function(res){ eval(atob(res)) }); \">";
                                                                    $strItem .= "<option value='0'>==PILIH==</option>";
                                                                    $queryParams = "";
                                                                    foreach ($produk_komposisi_fase[$fase_urut]["biaya"] as $datas) {
                                                                        $selected = isset($newData["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$produkID][$hField]) && $newData["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$produkID][$hField] == $datas['produk_dasar_id'] ? "selected" : "";
                                                                        $disable = in_array($datas['produk_dasar_id'], $arrSelected, TRUE) ? "disabled" : "";
                                                                        $iconCheck = in_array($datas['produk_dasar_id'], $arrSelected, TRUE) ? "data-icon='fa fa-check-circle text-green'" : "";
                                                                        $ketRoom = $datas['keterangan'] != '' ? "(" . $datas['keterangan'] . ")" : "";
                                                                        $strItem .= "<option $iconCheck $selected $disable value='" . $datas['id'] . "-" . $datas['produk_dasar_id'] . "'>" . $datas['produk_dasar_nama'] . " $ketRoom</option>";
                                                                    }
                                                                    $strItem .= "</select>";
                                                                }
                                                                else {
                                                                    $value = isset($newData["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$produkID][$hField]) ? $newData["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$produkID][$hField] : "";
                                                                    $strItem = "<input size='4' disabled id='produk_komposisi_fase_room_biaya_$fase_urut$selID$hField' class='form-control form-control-sm text-red text-bolds text-right' type='text' value='$value' onfocus=\"this.select()\" onkeyup=\"this.value = addCommas(this.value)\" onblur=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=produk_komposisi_fase_room_biaya&key=$hField&value='+encodeURI(removeCommas(this.value))+'&selID=$selID', function(res){ eval(atob(res)) });\">";
                                                                }
                                                            }
                                                            else {
                                                                $value = isset($newData["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$produkID][$hField]) ? $newData["produk_komposisi_fase_room_biaya"][$fase_urut][$selID][$produkID][$hField] : "";
                                                                $defValue = is_numeric($value) ? number_format($value) : $value;
                                                                $strItem = "<span id='produk_komposisi_fase_room_biaya_$fase_urut$selID$hField' class='form-control form-control-sm no-border text-red text-bolds text-right'>$defValue</span>";
                                                            }
                                                            $produkKomposisiRoom .= "<td>";
                                                            $produkKomposisiRoom .= $strItem;
                                                            $produkKomposisiRoom .= "</td>";
                                                        }

                                                        $btnRemoveFasekomposisi = "<button id='produk_komposisi_fase_room_biaya_$fase_urut$selID$produkID' idform='$idForm' disabled type='button' title='simpan komposisi baru' class='btn btn-sm'><i class='fa fa-plus'></i> tambah</button>";
                                                        $produkKomposisiRoom .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
                                                        $produkKomposisiRoom .= "</tr>";
                                                    }


                                                    $produkKomposisiRoom .= "</tbody>";
                                                    $produkKomposisiRoom .= "</table>";
                                                    $produkKomposisiRoom .= "</form>";

                                                    break;
                                            }
                                        }

                                        $produkKomposisiRoom .= "<div class='text-uppercase text-bold'>------------------------------- $selNama -------------------------------</div>";

                                        $produkKomposisiRoom .= "</div>";
                                    }
                                }
                                $produkKomposisiRoom .= "</div>";
                                $produkKomposisiRoom .= "</div>";

                                $produkKomposisiRoom .= "</div>";
                                $produkKomposisiRoom .= "</div>";

                                $room_data = $faseData['nama'];

                                $collapsed_box = count($arrSelectedRooms) > 0 ? "" : "collapsed-box";
                                $collapsed_checked = count($arrSelectedRooms) > 0 ? "checked" : "";

                                $disableCheckSparatedRoom = $lock == 0 ? "" : "disabled";

                                //separated room di matikan dulu
//                                $produkKomposisiFase .= "
//                                    <div class='box box-warning box-solid $collapsed_box'>
//                                        <div class='box-header with-border'>
//                                            <h3 class='box-title text-bold text-black'>
//                                                SEPARATED ROOM &nbsp;
//                                                <input $disableCheckSparatedRoom $collapsed_checked pids='$fase_urut' id='spr_$fase_urut' type='checkbox' class='minimal spr_check'>
//                                                <div class='small text-bold text-white'>Silahkan di centang jika anda memerlukan pendistribusian bahan baku yang lebih rinci di setiap ruangan</div>
//                                            </h3>
//                                            <button id='btnColls_$fase_urut' type='button' class='btn btn-box-tool hidden' data-widget='collapse'><i class='fa fa-minus'></i></button>
//                                        </div>
//                                        <div class='box-body'>
//                                            <div class='text-bold fa-2x'>$room_data</div>
//                                            <div class='text-bolds text-red'><i>Tambahkan nama ruangan untuk mendistribusikan bahan baku lebih rinci di setiap ruangan</i></div>
//                                            $produkKomposisiRoom
//                                        </div>
//                                    </div>
//                                ";

                                break;
                        }
                    }

                    $produkKomposisiFase .= "<div style='padding: 5px;' class='bg-info'>";
                    $produkKomposisiFase .= "<div style='margin-top: 15px;' class='row'>";
                    $produkKomposisiFase .= "<div class='col-md-8 text-right' id=''><span style='font-size: 16px;' class='text-bold'>Total BOM @Unit :</span></div>";
                    $produkKomposisiFase .= "<div class='col-md-3 no-paddingx text-right' id=''><span style='font-size: 16px;' class='text-bold' total_anggaran_unit='$total_anggaran_unit'><span class='pull-left'>Rp. </span>" . number_format($total_anggaran_unit) . "</span></div>";
                    $produkKomposisiFase .= "<div class='col-md-1 text-right' id=''><span style='font-size: 12px;' class='ext-bold'>&nbsp;</span></div>";
                    $produkKomposisiFase .= "</div>";

                    $defValue = $jual_per_unit * 1 > 0 && $jual_per_unit * 1 > $total_anggaran_unit ? $jual_per_unit * 1 : $total_anggaran_unit;

                    $produkKomposisiFase .= "<div style='margin-top: 5px;' class='row'>";
                    $produkKomposisiFase .= "<div class='col-md-8 text-right' id=''><span style='font-size: 16px;' class='text-bold'>Jual Project @Unit (Rp): </span></div>";
                    $disableInputSetJual = $lock == 0 ? "" : "disabled";

                    $produkKomposisiFase .= "<div class='col-md-3 no-padding text-right' id=''>
                    <input $disableInputSetJual id='hbom_$produkID$fase_urut' class='hidden' value='$total_anggaran_unit'>
                    <input $disableInputSetJual id='saved_jual_$produkID$fase_urut' class='hidden' value='$jual_per_unit'>
                    <input $disableInputSetJual pidfase='$produkID$fase_urut' id='hjp_$produkID$fase_urut' class='form-control form-control-sm text-bold text-right int_jual_project' style='font-size: 16px;padding-right: 14px !important;' onfocus=\"this.select()\" onkeyup=\"this.value = addCommas(removeCommas(this.value))\" value='" . number_format($defValue) . " '>
                    </div>";

                    $btn_danger = "hidden";
                    $btn_success = "hidden";
                    $btn_save = "hidden";

                    $jual_per_unit_f = round($jual_per_unit);
                    $total_anggaran_unit_f = round($total_anggaran_unit);

                    if ($jual_per_unit_f * 1 > 0 && $jual_per_unit_f * 1 > $total_anggaran_unit_f) {
                        $btn_danger = "hidden";
                        $btn_success = "";
                        $btn_save = "hidden";
                    }

                    if ($jual_per_unit_f * 1 == 0 && $jual_per_unit_f * 1 < $total_anggaran_unit_f) {
                        $btn_danger = "hidden";
                        $btn_success = "hidden";
                        $btn_save = "";
                    }

                    if ($jual_per_unit_f * 1 > 0 && $jual_per_unit_f * 1 < $total_anggaran_unit_f) {
                        $btn_danger = "";
                        $btn_success = "hidden";
                        $btn_save = "hidden";
                    }

                    if ($jual_per_unit_f * 1 > 0 && $jual_per_unit_f * 1 == $total_anggaran_unit_f) {
                        $btn_danger = "hidden";
                        $btn_success = "";
                        $btn_save = "hidden";
                    }

                    $produkKomposisiFase .= "<div class='col-md-1 text-right' id='md1_btnSave_$produkID$fase_urut'>
                                                <span class='btn btn-sm btn-danger $btn_danger' disabled><i class='fa fa-times'></i></span>
                                                <span class='btn btn-sm btn-success $btn_success' disabled><i class='fa fa-check-circle'></i></span>
                                                <span class='btn btn-sm btn-warning save_jual_project $btn_save' cx_url='$saveJualProject?mode=save_jual_project&fase_id=$fase_urut' produk_id='$produkID' fase_id='$fase_urut'><i class='fa fa-save'></i></span>
                                            </div>";

                    $produkKomposisiFase .= "</div>";

                    $diskon_value = 0;

//                    $produkKomposisiFase .= "<div style='margin-top: 5px;' class='row'>";
//                    $produkKomposisiFase .= "<div class='col-md-8 text-right' id=''><span style='font-size: 16px;' class='text-bold'>Diskon (Rp): </span></div>";
//                    $produkKomposisiFase .= "<div class='col-md-3 no-padding text-right' id=''>
//                        <input $disableInputSetJual produk_id='$produkID' fase_id='$fase_urut' pidfase='$produkID$fase_urut' id='diskon_$produkID$fase_urut' cx_url='$saveJualProject?mode=save_diskon&fase_id=$fase_urut' class='form-control form-control-sm text-bold text-right int_diskon' style='font-size: 16px;padding-right: 14px !important;' onfocus=\"this.select()\" onkeyup=\"this.value = addCommas(removeCommas(this.value))\" value='".number_format($diskon_value)."'>
//                    </div>";

//                    $produkKomposisiFase .= "</div>";

//                    $produkKomposisiFase .= "<div style='margin-top: 1px;' class='row'>";
//                    $produkKomposisiFase .= "<div class='col-md-8 text-right' id=''>&nbsp;</div>";
//                    $produkKomposisiFase .= "<div class='col-md-3 no-paddingx text-right' id=''><span style='font-size: 16px;' class='text-bold'>______________________</div>";
//                    $produkKomposisiFase .= "<div class='col-md-1 no-padding text-left' id=''><span style='font-size: 12px;' class='text-bold'><i class='fa fa-plus'></i></span></div>";
//                    $produkKomposisiFase .= "</div>";

//                    $produkKomposisiFase .= "<div style='margin-top: 1px;' class='row'>";
//                    $produkKomposisiFase .= "<div class='col-md-8 text-right' id=''><span style='font-size: 16px;' class='text-boldx'>SUBTOTAL:</span></div>";
//                    $produkKomposisiFase .= "<div class='col-md-3 no-paddingx text-right' id=''><span style='font-size: 16px;' class='text-bold'><span class='pull-left'>Rp. </span>".number_format($defValue-$diskon_value)."</span></div>";
//                    $produkKomposisiFase .= "<div class='col-md-1 text-right' id=''><span style='font-size: 12px;' class='ext-bold'>&nbsp;</span></div>";
//                    $produkKomposisiFase .= "</div>";

                    //==========================================================

                    $produkKomposisiFase .= "<div style='margin-top: 1px;' class='row'>";
                    $produkKomposisiFase .= "<div class='col-md-8 text-right' id=''><span style='font-size: 16px;' class='text-boldx'>PPN(".$this->session->login['ppnFactor']."%):</span></div>";
                    $produkKomposisiFase .= "<div class='col-md-3 no-paddingx text-right' id=''><span style='font-size: 16px;' class='text-bold'><span class='pull-left'>Rp. </span>".number_format((($defValue-$diskon_value)*$this->session->login['ppnFactor'])/100)."</span></div>";
                    $produkKomposisiFase .= "<div class='col-md-1 text-right' id=''><span style='font-size: 12px;' class='ext-bold'>&nbsp;</span></div>";
                    $produkKomposisiFase .= "</div>";

                    $produkKomposisiFase .= "<div style='margin-top: 1px;' class='row'>";
                    $produkKomposisiFase .= "<div class='col-md-8 text-right' id=''>&nbsp;</div>";
                    $produkKomposisiFase .= "<div class='col-md-3 no-paddingx text-right' id=''><span style='font-size: 16px;' class='text-bold'>______________________</div>";
                    $produkKomposisiFase .= "<div class='col-md-1 no-padding text-left' id=''><span style='font-size: 12px;' class='text-bold'><i class='fa fa-plus'></i></span></div>";
                    $produkKomposisiFase .= "</div>";

                    $produkKomposisiFase .= "<div style='margin-top: 1px;' class='row'>";
                    $produkKomposisiFase .= "<div class='col-md-8 text-right' id=''><span style='font-size: 16px;' class='text-boldx'>GRAND TOTAL: </span></div>";
                    $produkKomposisiFase .= "<div class='col-md-3 no-paddingx text-right' id=''><span style='font-size: 16px;' class='text-bold'><span class='pull-left'>Rp. </span>".number_format($defValue-$diskon_value+((($defValue-$diskon_value)*$this->session->login['ppnFactor'])/100))."</span></div>";
                    $produkKomposisiFase .= "<div class='col-md-1 text-right' id=''><span style='font-size: 12px;' class='text-bold'>&nbsp;</span></div>";
                    $produkKomposisiFase .= "</div>";

                    //==========================================================

                    $produkKomposisiFase .= "<div style='margin-top: 25px;' class='row'>";
                    $produkKomposisiFase .= "<div class='col-md-8 text-right' id=''><span style='font-size: 16px;' class='text-boldx'>Total R/L @Unit :</span></div>";
                    $produkKomposisiFase .= "<div class='col-md-3 no-paddingx text-right' id=''><span style='font-size: 16px;' class='text-bold'><span class='pull-left'>Rp. </span>" . number_format($jual_per_unit - $total_anggaran_unit) . "</span></div>";
                    $produkKomposisiFase .= "<div class='col-md-1 text-right' id=''><span style='font-size: 12px;' class='ext-bold'>&nbsp;</span></div>";
                    $produkKomposisiFase .= "</div>";

                    $produkKomposisiFase .= "<div style='margin-top: 1px;' class='row'>";
                    $produkKomposisiFase .= "<div class='col-md-8 text-right' id=''><span style='font-size: 16px;' class='text-boldx'>Total R/L :</span></div>";
                    $produkKomposisiFase .= "<div class='col-md-3 no-paddingx text-right' id=''><span style='font-size: 16px;' class='text-bold'><span class='pull-left'>Rp. </span>" . number_format(($jual_per_unit - $total_anggaran_unit) * $faseData['qty']) . "</span></div>";
                    $produkKomposisiFase .= "<div class='col-md-1 text-right' id=''><span style='font-size: 12px;' class='ext-bold'>&nbsp;</span></div>";
                    $produkKomposisiFase .= "</div>";

                    $produkKomposisiFase .= "</div>";

                    //BOM / KOMPOSISI PAKET
                    $produkKomposisiFase .= "<div style='margin-top: 30px;margin-bottom: 6px;' class='fa-2x text-muted'> <i class='fa fa-hand-o-right'></i>&nbsp;&nbsp;PAKET INSTALASI&nbsp;&nbsp;&nbsp;
                                                <div class='box-tools pull-right'>
                                                    <button type='button' class='btn btn-sm btn-success btn-tambah-paket'><i class='fa fa-plus'></i> TAMBAH PAKET</button>
                                                </div>
                                             </div>";
                    $produkKomposisiFase .= "<div class='box-group bom-container' id='accordion'>";
                    $produkKomposisiFase .= "
                        <script>
                            function showTutorialEditPaket(){
                                BootstrapDialog.show({
                                    title:'CARA EDIT PAKET INSTALASI',
                                    message: $('<div></div>').html(\"<iframe width='850' height='500' src='https://www.youtube.com/embed/eTmkeDavzgc?si=IeCRTpaNcBj2SF13' title='YouTube video player' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share' referrerpolicy='strict-origin-when-cross-origin' allowfullscreen></iframe>\"),
                                    size: BootstrapDialog.SIZE_WIDE,
                                    type: BootstrapDialog.TYPE_INFO,
                                    draggable:true,
                                    closable:true,
                                    buttons: [{
                                        label: 'Close',
                                        cssClass: 'btn-primary pull-left',
                                        title: 'close',
                                        action: function(dialogItself){
                                            dialogItself.close();
                                        }
                                    }],
                                });
                            }
                        </script>
                    ";

                    $video_tutorial_edit = "
                        <a class='text-bold' href='javascript:void(0)' data-toggle='tooltip' data-placement='top' title='klik untuk lihat video tutorial' onclick='showTutorialEditPaket()'><i class='fa fa-video-camera text-red'></i> tonton video</a>
                    ";

                    if (sizeof($produk_paket) > 0) {
                        foreach ($produk_paket as $pp_key => $faseData) {

                            $produkKomposisiFase .= "<div class='box box-solid box-info'>";
                            $produkKomposisiFase .= "<div class='box-header with-border'>";
                            $produkKomposisiFase .= "<h3 class='title-produk-paket box-title'><i class='fa fa-tags'></i>".($faseData->nama)."</h3>";
                            $produkKomposisiFase .= "<div class='box-tools'>";

                            if(isset($allowedAccess["produk_paket"]["delete"]) && $allowedAccess["produk_paket"]["delete"] == true) {
                                $produkKomposisiFase .= "<button cx_jenis='produk' cx_tmpnamamaterial='".($faseData->nama)."' cx_deletelink='$deleteLink' cx_tid='$pp_key' cx_mdl='ProjectProdukPaket' type='button' title='clik untuk menghapus realasi' class='btn btn-xs btn-danger btn-box-tool delWorkOrder'><i class='fa fa-trash'></i> Hapus Paket</button>";
                                $produkKomposisiFase .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                            }

                            $produkKomposisiFase .= "<button type='button' class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>";
                            $produkKomposisiFase .= "</div>";
                            $produkKomposisiFase .= "</div>";

                            $produkKomposisiFase .= "<div class='box-body'>";

                            $produkKomposisiFase .= "<div class='alert alert-warning alert-sm text-uppercase'><i class='fa fa-warning text-red blink'></i> jika anda akan melakukan perubahan pada rab besar, maka hapus dulu komponen paket yang salah, kemudian tambahkan kembali setelah rab besar di perbaiki. $video_tutorial_edit</div>";

                            $total_anggaran_unit = 0;
                            $jual_per_unit = 0;

                            $total_rab_wo = 0;

                            foreach ($produk_komposisi_fase_header as $hFieldKey => $hLabelData) {
                                switch ($hFieldKey) {
                                    case "item_komposit":
                                    case "produk":
                                        $idForm = "komposisi_bom" . "$pp_key";
                                        $produkKomposisiFase .= "<div class='$idForm'>";
                                        $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addFaseProdukKomposisiBomLink?mode=komposisi_bom&paket_id=$pp_key&fase_id=$fase_urut'>";
                                        $produkKomposisiFase .= "<table class='table dataTable compact display table-bordered table-condensed'>";
                                        $produkKomposisiFase .= "<thead>";
                                        $produkKomposisiFase .= "<tr>";
                                        $produkKomposisiFase .= "<td>No</td>";
                                        foreach ($hLabelData as $hField => $hLabel) {
                                            $produkKomposisiFase .= "<td>$hLabel</td>";
                                        }
                                        $produkKomposisiFase .= "<td>action</td>";
                                        $produkKomposisiFase .= "</tr>";
                                        $produkKomposisiFase .= "</thead>";
                                        $produkKomposisiFase .= "<tbody>";
                                        $produkKomposisiFase .= "<tr>";
                                        $i = 0;
                                        $arrSelected = array();
                                        if (isset($produk_komposisi_bom[$pp_key]["produk"]) && sizeof($produk_komposisi_bom[$pp_key]["produk"])) {
                                            foreach ($produk_komposisi_bom[$pp_key]["produk"] as $DataRelsupplies) {
                                                $tID = $DataRelsupplies["id"];
                                                $arrSelected[] = $DataRelsupplies["produk_dasar_id"];
                                                $produkKomposisiFase .= "<tr>";
                                                $i++;
                                                $produkKomposisiFase .= "<td>$i</td>";
                                                $tmpNamaMaterial = '';
                                                foreach ($hLabelData as $hField => $hLabel) {
                                                    $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                                                    $val = isset($DataRelsupplies[$transformKey]) ? $DataRelsupplies[$transformKey] : "";

                                                    $origVal=$val;
                                                    $val = $hField=="produk_dasar_id" && strlen($val)>=34 ? substr($val,0,34)."..." : $val;

                                                    $produkKomposisiFase .= "<td hField='$hField' title='$origVal'>" . formatField($hField, $val) . "</td>";
                                                    $tmpNamaMaterial .= $hField=="produk_dasar_id" ? $val."" : "";
                                                    $total_anggaran_unit += $hField=="subtotal" ? $val*1 : 0;
                                                }
                                                $btn = "<div>";
//                                                if(isset($allowedAccess["produk_paket"]["update"]) && $allowedAccess["produk_paket"]["update"] == true) {
//                                                    $btn .= "<button type='button' title='edit' class='btn btn-xs btn-flat btn-warning' onclick=\"showModal('" . $previewLink . "MdlProjectKomposisiWorkorder/$tID/$hField','edit $labelName')\"><span class='fa fa-fw fa-edit'></span></button>";
//                                                }
                                                if(isset($allowedAccess["produk_paket"]["delete"]) && $allowedAccess["produk_paket"]["delete"] == true) {
                                                    $btn .= "<button cx_jenis='produk' cx_tmpnamamaterial='$tmpNamaMaterial' cx_deletelink='$deleteLink' cx_tid='$tID' cx_mdl='ProdukKomposisiPaket' type='button' title='clik untuk menghapus realasi' class='btn btn-xs btn-flat btn-danger delWorkOrder'><i class='fa fa-fw fa-trash'></i></button>";
                                                }
                                                $btn .= "</div>";

                                                $produkKomposisiFase .= "<td >$btn</td>";

                                                $produkKomposisiFase .= "</tr>";
                                            }
                                        }

                                        if (isset($produk_komposisi_bom[$pp_key]["item_komposit"]) && sizeof($produk_komposisi_bom[$pp_key]["item_komposit"])) {
                                            foreach ($produk_komposisi_bom[$pp_key]["item_komposit"] as $DataRelsupplies) {
                                                $tID = $DataRelsupplies["id"];
                                                $arrSelected[] = $DataRelsupplies["produk_dasar_id"];
                                                $produkKomposisiFase .= "<tr>";
                                                $i++;
                                                $produkKomposisiFase .= "<td>$i</td>";
                                                $tmpNamaMaterial = '';
                                                foreach ($hLabelData as $hField => $hLabel) {
                                                    $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                                                    $val = isset($DataRelsupplies[$transformKey]) ? $DataRelsupplies[$transformKey] : "";

                                                    $origVal=$val;
                                                    $val = $hField=="produk_dasar_id" && strlen($val)>=34 ? substr($val,0,34)."..." : $val;

                                                    $produkKomposisiFase .= "<td hField='$hField' title='$origVal'>" . formatField($hField, $val) . "</td>";
                                                    $tmpNamaMaterial .= $hField=="produk_dasar_id" ? $val."" : "";
                                                    $total_anggaran_unit += $hField=="subtotal" ? $val*1 : 0;
                                                }
                                                $btn = "<div>";
    //                                                if(isset($allowedAccess["produk_paket"]["update"]) && $allowedAccess["produk_paket"]["update"] == true) {
    //                                                    $btn .= "<button type='button' title='edit' class='btn btn-xs btn-flat btn-warning' onclick=\"showModal('" . $previewLink . "MdlProjectKomposisiWorkorder/$tID/$hField','edit $labelName')\"><span class='fa fa-fw fa-edit'></span></button>";
    //                                                }
                                                if(isset($allowedAccess["produk_paket"]["delete"]) && $allowedAccess["produk_paket"]["delete"] == true) {
                                                    $btn .= "<button cx_jenis='produk' cx_tmpnamamaterial='$tmpNamaMaterial' cx_deletelink='$deleteLink' cx_tid='$tID' cx_mdl='ProdukKomposisiPaket' type='button' title='clik untuk menghapus realasi' class='btn btn-xs btn-flat btn-danger delWorkOrder'><i class='fa fa-fw fa-trash'></i></button>";
                                                }
                                                $btn .= "</div>";

                                                $produkKomposisiFase .= "<td >$btn</td>";

                                                $produkKomposisiFase .= "</tr>";
                                            }
                                        }

                                        //untuk tambah komponen
                                        $produkKomposisiFase .= "</tr>";
                                        $produkKomposisiFase .= "<tr>";
                                        $produkKomposisiFase .= "<td><span style='font-size: 10px;' class='form-control form-control-sm no-border text-olive' title='pilih/isi '><i class='fa fa-plus-circle fa-2x'></i></span></td>";
//                                        cekMerah("=============================");
//                                        arrPrintWebs($hLabelData);
                                        foreach ($hLabelData as $hField => $hLabel) {
                                            if ($hField=="produk_dasar_id") {
                                                $strItem = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_bom&paket_id=$pp_key&key=$hField&value='+encodeURI(this.value)+'', function(res){ eval(atob(res)); }); \">";
                                                $strItem .= "<option value='0'>==PILIH==</option>";
                                                $queryParams = "";
                                                foreach ($produk_komposisi_fase[$fase_urut]["produk"] as $datas) {
                                                    $selected = isset($newData["komposisi_bom"][$fase_urut][$pp_key][$produkID][$hField]) && $newData["komposisi_bom"][$fase_urut][$pp_key][$produkID][$hField] == $datas['produk_dasar_id'] ? "selected" : "";
                                                    $disable = in_array($datas['produk_dasar_id'], $arrSelected, TRUE) ? "disabled" : "";
                                                    $iconCheck = in_array($datas['produk_dasar_id'], $arrSelected, TRUE) ? "data-icon='fa fa-check-circle text-green'" : "";
                                                    $strItem .= "<option $iconCheck $selected $disable value='" . $datas['produk_dasar_id'] . "'>" . $datas['produk_dasar_nama'] . "</option>";
                                        }

                                                foreach ($produk_komposisi_fase[$fase_urut]["item_komposit"] as $datas) {
                                                    $selected = isset($newData["komposisi_bom"][$fase_urut][$pp_key][$produkID][$hField]) && $newData["komposisi_bom"][$fase_urut][$pp_key][$produkID][$hField] == $datas['produk_dasar_id'] ? "selected" : "";
                                                    $disable = in_array($datas['produk_dasar_id'], $arrSelected, TRUE) ? "disabled" : "";
                                                    $iconCheck = in_array($datas['produk_dasar_id'], $arrSelected, TRUE) ? "data-icon='fa fa-check-circle text-green'" : "";
                                                    $strItem .= "<option $iconCheck $selected $disable value='" . $datas['produk_dasar_id'] . "'>" . $datas['produk_dasar_nama'] . "</option>";
                                                }

                                                $strItem .= "</select>";
                                                }
                                            else if ($hField=="jml") {
                                                $value = isset($newData["komposisi_bom"][$fase_urut][$pp_key][$produkID][$hField]) ? $newData["komposisi_bom"][$fase_urut][$pp_key][$produkID][$hField] : "";
                                                $strItem = "<input id='komposisi_bom_$fase_urut$pp_key$hField' class='form-control form-control-sm text-red text-bold text-right' type='text' value='$value' onfocus=\"this.select()\" onkeyup=\"this.value = addCommas(this.value)\" onblur=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_bom&paket_id=$pp_key&key=$hField&value='+encodeURI(removeCommas(this.value))+'', function(res){ eval(atob(res)); });\">";
                                            }
                                            else {
                                                $value = isset($newData["komposisi_bom"][$fase_urut][$pp_key][$produkID][$hField]) ? $newData["komposisi_bom"][$fase_urut][$pp_key][$produkID][$hField] : "";
                                                $defValue = is_numeric($value) ? number_format($value) : $value;
                                                $strItem = "<span id='komposisi_bom_$fase_urut$pp_key$hField' class='form-control form-control-sm no-border text-red text-bold text-right'>$defValue</span>";
                                        }
                                            $produkKomposisiFase .= "<td>";
                                            $produkKomposisiFase .= $strItem;
                                            $produkKomposisiFase .= "</td>";
                                        }

                                        $btnRemoveFasekomposisi = "<button id='komposisi_bom_$fase_urut$pp_key$produkID' idform='$idForm' disabled type='button' title='simpan komposisi baru' class='btn btn-sm'><i class='fa fa-plus'></i> tambah</button>";
                                        $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
                                        $produkKomposisiFase .= "</tr>";

                                        $produkKomposisiFase .= "</tbody>";
                                        $produkKomposisiFase .= "</table>";
                                        $produkKomposisiFase .= "</form>";
                                        $produkKomposisiFase .= "</div>";
                                        break;
                                    case "biaya":
                                        $idForm = "bom_biaya" . "$pp_key";
                                        $produkKomposisiFase .= "<div class='$idForm'>";
                                        $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addFaseProdukKomposisiBomBiayaLink?mode=komposisi_bom_biaya&paket_id=$pp_key&fase_id=$fase_urut'>";
                                        $produkKomposisiFase .= "<table class='table dataTable compact display table-bordered table-condensed'>";
                                        $produkKomposisiFase .= "<thead>";
                                        $produkKomposisiFase .= "<tr>";
                                        $produkKomposisiFase .= "<td>No</td>";
                                        foreach ($hLabelData as $hField => $hLabel) {
                                            $produkKomposisiFase .= "<td>$hLabel</td>";
                                        }

                                        $produkKomposisiFase .= "<td>Action</td>";
                                        $produkKomposisiFase .= "</tr>";

                                        $produkKomposisiFase .= "</thead>";
                                        $produkKomposisiFase .= "<tbody>";
                                        //bagaian data relasi komposisi
                                        $i = 0;
                                        $arrSelected = array();
                                        if (isset($produk_komposisi_bom[$pp_key]["biaya"]) && sizeof($produk_komposisi_bom[$pp_key]["biaya"]) > 0) {
                                            foreach ($produk_komposisi_bom[$pp_key]["biaya"] as $DataRelsuppliesBiaya) {
                                                $tID=$DataRelsuppliesBiaya["id"];
                                                $arrSelected[] = $DataRelsuppliesBiaya["produk_dasar_id"];
                                                $produkKomposisiFase .= "<tr>";
                                                $i++;
                                                $tmpNamaMaterial = '';
                                                $produkKomposisiFase .= "<td>$i</td>";
                                                foreach ($hLabelData as $hField => $hLabel) {
                                                    $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                                                    $val = isset($DataRelsuppliesBiaya[$transformKey]) ? $DataRelsuppliesBiaya[$transformKey] : "";

                                                    $origVal=$val;
                                                    $val = $hField=="produk_dasar_id" && strlen($val)>=34 ? substr($val,0,34)."..." : $val;

                                                    $produkKomposisiFase .= "<td hField='$hField'>" . formatField($hField, $val) . "</td>";
                                                    $tmpNamaMaterial .= $hField=="produk_dasar_id" ? $val : "";
                                                    $total_anggaran_unit += $hField=="subtotal" ? $val*1 : 0;
                                                }

                                                $btn = "<div>";
                                                if(isset($allowedAccess["produk_paket"]["delete"]) && $allowedAccess["produk_paket"]["delete"] == true) {
                                                    $btn .= "<button cx_jenis='biaya' cx_tmpnamamaterial='$tmpNamaMaterial' cx_deletelink='$deleteLink' cx_tid='$tID' cx_mdl='ProdukKomposisiPaket' type='button' title='clik untuk menghapus realasi' class='btn btn-xs btn-flat btn-danger delWorkOrder'><i class='fa fa-fw fa-trash'></i></button>";
                                                }
                                                $btn .="</div>";

                                                $produkKomposisiFase .= "<td >$btn</td>";

                                                $produkKomposisiFase .= "</tr>";
                                            }
                                        }

                                        //untuk tambah komponen
                                        $produkKomposisiFase .= "<tr>";
                                        $produkKomposisiFase .= "<td><span style='font-size: 10px;' class='form-control form-control-sm no-border text-olive' title='pilih/isi '><i class='fa fa-plus-circle fa-2x'></i></span></td>";
                                        foreach ($hLabelData as $hField => $hLabel) {
                                            if ($hField=="produk_dasar_id") {
                                                $strItem = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_bom_biaya&paket_id=$pp_key&key=$hField&value='+encodeURI(this.value)+'', function(res){ eval(atob(res)); }); \">";
                                                    $strItem .= "<option value='0'>==PILIH==</option>";
                                                foreach ($produk_komposisi_fase[$fase_urut]["biaya"] as $datas) {
                                                    $selected = isset($newData["komposisi_bom_biaya"][$fase_urut][$pp_key][$produkID][$hField]) && $newData["komposisi_bom_biaya"][$fase_urut][$pp_key][$produkID][$hField] == $datas['id'] ? "selected" : "";
                                                    $disable = "";
                                                    $iconCheck = in_array($datas['produk_dasar_id'], $arrSelected, TRUE) ? "data-icon='fa fa-check-circle text-green'" : "";
                                                    $ket = isset($datas['keterangan']) && $datas['keterangan'] != "" ? " (".$datas['keterangan'].")" : "";
                                                    $strItem .= "<option $iconCheck $selected $disable value='" . $datas['id'] . "'>" . $datas['produk_dasar_nama'] . "$ket</option>";
                                                    }
                                                    $strItem .= "</select>";
                                                }
                                            else if($hField=="jml"){
                                                $value = isset($newData["komposisi_bom_biaya"][$fase_urut][$pp_key][$produkID][$hField]) ? $newData["komposisi_bom_biaya"][$fase_urut][$pp_key][$produkID][$hField] : "";
                                                $strItem = "<input id='komposisi_bom_biaya_$fase_urut$pp_key$hField' class='form-control form-control-sm text-red text-bold text-right' type='text' value='$value' onfocus=\"this.select()\" onkeyup=\"this.value = addCommas(this.value)\" onblur=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_bom_biaya&paket_id=$pp_key&key=$hField&value='+encodeURI(removeCommas(this.value))+'', function(res){ eval(atob(res)); });\">";
                                            }
                                            else {
                                                $value = isset($newData["komposisi_bom_biaya"][$fase_urut][$pp_key][$produkID][$hField]) ? $newData["komposisi_bom_biaya"][$fase_urut][$pp_key][$produkID][$hField] : "";
                                                $defValue = is_numeric($value) ? number_format($value) : $value;
                                                $strItem = "<span id='komposisi_bom_biaya_$fase_urut$pp_key$hField' class='form-control form-control-sm no-border text-red text-bold text-right'>$defValue</span>";
                                            }

                                            $produkKomposisiFase .= "<td>";
                                            $produkKomposisiFase .= $strItem;
                                            $produkKomposisiFase .= "</td>";
                                        }
                                        $btnRemoveFasekomposisi = "<button id='komposisi_bom_biaya_$fase_urut$pp_key$produkID' idform='$idForm' disabled type='button' title='simpan komposisi biaya' class='btn btn-sm'><i class='fa fa-plus'></i> tambah</button>";
                                        $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
                                        $produkKomposisiFase .= "</tr>";

                                        $produkKomposisiFase .= "</tbody>";
                                        $produkKomposisiFase .= "</table>";
                                        $produkKomposisiFase .= "</form>";
                                        $produkKomposisiFase .= "</div>";
                                        break;
                                }
                            }


                            $produkKomposisiFase .= "<table class='table table-bordered dataTable compact'>";
                            $produkKomposisiFase .= "<tr>";
                            $produkKomposisiFase .= "<th width='80%' class='text-right text-bold'> TOTAL (".$faseData->nama.") </th>";
                            $produkKomposisiFase .= "<th width='20%' class='text-right text-bold'> Rp. ".number_format($total_anggaran_unit)." </th>";
                            $produkKomposisiFase .= "</tr>";
                            $produkKomposisiFase .= "</table>";

                            $produkKomposisiFase .= "</div>";
                            $produkKomposisiFase .= "</div>";
//                            $produkKomposisiFase .= "</div>";

                        }
                    }
                    else{
                        $produkKomposisiFase .= "<div class='box box-danger box-solid box-header text-center text-bold'> SILAHKAN BUAT PAKET INSTALASI DULU </div>";

                        $produkKomposisiFase .= "
                            <script>
                                setTimeout(function(){
                                    top.$('#produk_fase_qty').val(1).trigger('blur');
                                    top.$('#produk_fase_nama').val('".$masterProject['nama']."').trigger('blur');
                                    top.$('#produk_fase_lokasi').val('-').trigger('blur');
                                    setTimeout(function(){
                                        $('#addProdukFase').click();
                                    },500);
                                },1000);

//                                var nilai_jual_project = removeCommas($('#hjp_$produkID$fase_urut').val());
//                                console.log('nilai_jual_project: '+nilai_jual_project);
//                                if( nilai_jual_project*1> 0 && $('.title-produk-paket').length *1 == 0 ){
//                                    swal('Project Belum Lengkap', 'Project Belum Memiliki PAKET INSTALASI, silahkan tambah menggunakan menu paling bawah pada Page ini...', 'warning');
//                                }
                            </script>";
                    }

                    $produkKomposisiFase .= "</div>";
                    $produkKomposisiFase .= "</div>";
//                    $produkKomposisiFase .= "</div>";
//                    $produkKomposisiFase .= "</div>";
                    //SAMPE SINI SAJA REPLACE BOM

                }
                else {
                    $produkKomposisiFase .= "<div class='bg-ble lv12 tab-pane fade active in' id='tab-fase_$fase_urut'>";
                    $produkKomposisiFase .= "<div class='blink text-bold text-danger'><h4 class=''>Material " . ($faseData['nama']) . " belum diseting, silahkan klik tombol tambah</h3></div>";
                    foreach ($produk_komposisi_fase_header as $hFieldKey => $hLabelData) {
                        switch ($hFieldKey) {
                            case "produk":
                                $idForm = "bahan_baku" . "$fase_urut";
                                $produkKomposisiFase .= "<div class='$idForm'>";
                                $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addFaseProdukKomposisiLink?mode=komposisi_fase&fase_id=$fase_urut'>";
                                $produkKomposisiFase .= "<table class='table dataTable compact display table-bordered table-condensed'>";
                                $produkKomposisiFase .= "<thead>";
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td>No</td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>$hLabel</td>";
                                }
                                $produkKomposisiFase .= "<td>action</td>";
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "</thead>";
                                $produkKomposisiFase .= "<tbody>";
                                $produkKomposisiFase .= "<tr>";

                                $arrSelected = array();

                                $i = 0;
                                //untuk tambah komponen
                                $produkKomposisiFase .= "</tr>";
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td><span style='font-size: 10px;' class='form-control form-control-sm no-border text-olive' title='pilih/isi '><i class='fa fa-plus-circle fa-2x'></i></span></td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    if (isset($produk_fase_komposisiEditable[$hField])) {
                                        if (isset($relSupplies[$hField])) {
                                            $strItem = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_fase&key=$hField&value='+encodeURI(this.value)+'', function(res){ eval(atob(res)); }); \">";
                                            $strItem .= "<option value='0'>==PILIH==</option>";
                                            $queryParams = "";
                                            foreach ($relSupplies[$hField] as $datas) {
                                                $selected = isset($newData["komposisi_bom"][$fase_urut][$produkID][$hField]) && $newData["komposisi_bom"][$fase_urut][$produkID][$hField] == $datas['id'] ? "selected" : "";
                                                $disable = in_array($datas['id'], $arrSelected, TRUE) ? "disabled" : "";
                                                $iconCheck = in_array($datas['id'], $arrSelected, TRUE) ? "data-icon='fa fa-check-circle text-green'" : "";
                                                $strItem .= "<option $iconCheck $selected $disable value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                                            }
                                            $strItem .= "</select>";
                                        }
                                        else {
                                            $value = isset($newData["komposisi_bom"][$fase_urut][$produkID][$hField]) ? $newData["komposisi_bom"][$fase_urut][$produkID][$hField] : "";
                                            $strItem = "<input id='komposisi_fase_$fase_urut$hField' class='form-control form-control-sm text-red text-bold text-right' type='text' value='$value' onfocus=\"this.select()\" onkeyup=\"this.value = addCommas(this.value)\" onblur=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_fase&key=$hField&value='+encodeURI(removeCommas(this.value))+'', function(res){ eval(atob(res)); });\">";
                                        }
                                    }
                                    else {
                                        $value = isset($newData["komposisi_bom"][$fase_urut][$produkID][$hField]) ? $newData["komposisi_bom"][$fase_urut][$produkID][$hField] : "";
                                        $defValue = is_numeric($value) ? number_format($value) : $value;
                                        $strItem = "<span id='komposisi_fase_$fase_urut$hField' class='form-control form-control-sm no-border text-red text-bold text-right'>$defValue</span>";
                                    }
                                    $produkKomposisiFase .= "<td>";
                                    $produkKomposisiFase .= $strItem;
                                    $produkKomposisiFase .= "</td>";
                                }


                                $btnRemoveFasekomposisi = "<button id='komposisi_fase_$fase_urut$produkID' idform='$idForm' disabled type='button' title='simpan komposisi baru' class='btn btn-sm'><i class='fa fa-plus'></i> tambah</button>";
                                $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
                                $produkKomposisiFase .= "</tr>";

                                $produkKomposisiFase .= "</tbody>";
                                $produkKomposisiFase .= "</table>";
                                $produkKomposisiFase .= "</form>";
                                $produkKomposisiFase .= "</div>";
                                break;
                            case "biaya":
                                $idForm = "biaya" . "$fase_urut";
                                $produkKomposisiFase .= "<div class='$idForm'>";
                                $produkKomposisiFase .= "<form class='form' id='$idForm' method='post' name='$idForm' target='result' action='$addProdukKomposisiBiayaLink?mode=komposisi_fase_biaya&fase_id=$fase_urut'>";
                                $produkKomposisiFase .= "<table class='table dataTable compact display table-bordered table-condensed'>";
                                $produkKomposisiFase .= "<thead>";
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td>No</td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $produkKomposisiFase .= "<td>$hLabel</td>";
                                }
                                $produkKomposisiFase .= "<td>Action</td>";
                                $produkKomposisiFase .= "</tr>";

                                $produkKomposisiFase .= "</thead>";
                                $produkKomposisiFase .= "<tbody>";
                                //bagaian data relasi komposisi
                                // arrprint($relBiaya);
                                $i = 0;
                                //bagian add baru
                                //untuk tambah komponen
                                $produkKomposisiFase .= "<tr>";
                                $produkKomposisiFase .= "<td><span style='font-size: 10px;' class='form-control form-control-sm no-border text-olive' title='pilih/isi '><i class='fa fa-plus-circle fa-2x'></i></span></td>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    if (isset($produk_fase_komposisiEditable[$hField])) {
                                        if (isset($relBiaya[$hField])) {
                                            $strItem = "<select data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchange=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_fase_biaya&key=$hField&value='+encodeURI(this.value)+'', function(res){ eval(atob(res)); }); \">";
                                            $strItem .= "<option value='0'>==PILIH==</option>";
                                            foreach ($relBiaya[$hField] as $datas) {
                                                $selected = isset($newData["komposisi_fase_biaya"][$fase_urut][$produkID][$hField]) && $newData["komposisi_fase_biaya"][$fase_urut][$produkID][$hField] == $datas['id'] ? "selected" : "";
                                                $disable = in_array($datas['id'], $arrSelected, TRUE) ? "disabled" : "";
                                                $iconCheck = in_array($datas['id'], $arrSelected, TRUE) ? "data-icon='fa fa-check-circle text-green'" : "";
                                                $strItem .= "<option $iconCheck $selected $disable value='" . $datas['id'] . "'>" . $datas['nama'] . "</option>";
                                            }
                                            $strItem .= "</select>";
                                        }
                                        else {
                                            $value = isset($newData["komposisi_fase_biaya"][$fase_urut][$produkID][$hField]) ? $newData["komposisi_fase_biaya"][$fase_urut][$produkID][$hField] : "";
                                            $strItem = "<input id='komposisi_fase_biaya_$fase_urut$hField' class='form-control form-control-sm text-red text-bold text-right' type='text' value='$value' onfocus=\"this.select()\" onkeyup=\"this.value = addCommas(this.value)\" onblur=\"$('#input_temp').load('$selector" . "$produkID/$fase_urut?mode=komposisi_fase_biaya&key=$hField&value='+encodeURI(removeCommas(this.value))+'', function(res){ eval(atob(res)); });\">";
                                        }
                                    }
                                    else {
                                        $value = isset($newData["komposisi_fase_biaya"][$fase_urut][$produkID][$hField]) ? $newData["komposisi_fase_biaya"][$fase_urut][$produkID][$hField] : "";
                                        $defValue = is_numeric($value) ? number_format($value) : $value;
                                        $strItem = "<span id='komposisi_fase_biaya_$fase_urut$hField' class='form-control form-control-sm no-border text-red text-bold text-right'>$defValue</span>";
                                    }
                                    $produkKomposisiFase .= "<td>";
                                    $produkKomposisiFase .= $strItem;
                                    $produkKomposisiFase .= "</td>";
                                }
                                $btnRemoveFasekomposisi = "<button id='komposisi_fase_biaya_$fase_urut$produkID' idform='$idForm' disabled type='button' title='simpan komposisi biaya' class='btn btn-sm'><i class='fa fa-plus'></i> tambah</button>";
                                $produkKomposisiFase .= "<td class='text-center'>$btnRemoveFasekomposisi</td>";
                                $produkKomposisiFase .= "</tr>";


                                $produkKomposisiFase .= "</tbody>";
                                $produkKomposisiFase .= "</table>";
                                $produkKomposisiFase .= "</form>";
                                $produkKomposisiFase .= "</div>";
                                break;
                        }
                    }
                    $produkKomposisiFase .= "</div>";
                }
            }

            $produkKomposisiFase .= "</div>"; //box-body
            $produkKomposisiFase .= "</div>";

        }
        else {
            $produkKomposisiFase .= "<div class='box box-danger box-solid box-header text-center text-bold'> SILAHKAN BUAT RENCANA KERJA DULU </div>";

            //auto bikin workorder
            if( !isset($_GET['t1']) ){
                $produkKomposisiFase .= "<script>
                    var protokol = top.window.location.protocol;
                    var hostname = top.window.location.hostname;
                    var pathname = top.window.location.pathname;
                    var urlNow = protokol + '//' + hostname + '' + pathname;
                    setTimeout(function(){
                        window.location.href = urlNow + '?1=1&t1=1';
                    }, 1000);
                </script>";
            }
            else{
                $produkKomposisiFase .= "
                <script>
//                        setTimeout(function(){
    //                    top.$('#produk_fase_qty').val(1).trigger('blur');
    //                    top.$('#produk_fase_nama').val('".$masterProject['nama']."').trigger('blur');
    //                    top.$('#produk_fase_lokasi').val('-').trigger('blur');
    //                        setTimeout(function(){
//                            $('#addProdukFase').click();
//                        },500);
    //                },1000);
                top.swal('WORKORDER KOSONG', 'jika work order kosong, kemungkinan transaksi ini belum di approve..', 'info');
                </script>";
            }

        }

        $produkKomposisiFase .= "
        <script>

            $('.title_xedit').editable({
                url: '".base_url()."master_project/MasterData/exeEditable'
            });

            $('.btn-tambah-paket').off();
            $('.btn-tambah-paket').on('click', function(){

                swal({
                    title: 'ketik nama paket baru',
                    html: \"<div><input id='nama_new_paket' class='form-control text-center text-bold'></div>\",
                    type: 'info',
                    confirmButtonText: \"<i class='fa fa-save'></i> simpan paket\",
                    onOpen: function(){
                        console.log('willOpen');

                        var tmpnama = $('.title-produk-paket');
                        var listnama = []
                        jQuery.each(tmpnama, function(a, b){
                            var nama = $(b).text();
                            var tmp = {nama}
                            listnama.push(tmp)
                        });

                        function trimString(s) {
                          var l=0, r=s.length -1;
                          while(l < s.length && s[l] == ' ') l++;
                          while(r > l && s[r] == ' ') r-=1;
                          return s.substring(l, r+1);
                        }

                        function compareObjects(o1, o2) {
                          var k = '';
                          for(k in o1) if(o1[k] != o2[k]) return false;
                          for(k in o2) if(o1[k] != o2[k]) return false;
                          return true;
                        }

                        function itemExists(haystack, needle) {
                          for(var i=0; i<haystack.length; i++) if(compareObjects(haystack[i], needle)) return true;
                          return false;
                        }

                        function searchFor(toSearch) {
                          var results = [];
                          toSearch = trimString(toSearch); // trim it
                          for(var i=0; i<listnama.length; i++) {
                            for(var key in listnama[i]) {
                              if(listnama[i][key].indexOf(toSearch)!=-1) {
                                if(!itemExists(results, listnama[i])) results.push(listnama[i]);
                              }
                            }
                          }
                          return results;
                        }

                        console.error(listnama);
                        $('#nama_new_paket').off();
                        $('#nama_new_paket').on('keyup', function(){
                            //console.log(searchFor(this.value));
                            //console.log( countObj(searchFor(this.value)) );
                            if( countObj(searchFor(this.value))*1 > 0){
                                swal.disableConfirmButton();
                            }
                            else{
                                swal.enableConfirmButton();
                            }
                        });

                        swal.disableConfirmButton();
                    }
                }).then((result) => {
                    if (result) {
                        var nama_paket = $('#nama_new_paket').val();
                        top.$('#result').load('".base_url()."master_project/MasterData/addData/MdlProjectProdukPaket/$produkID?mode=produk_paket&nama_paket='+encodeURI(nama_paket)+'&debuger=0', function(aaa){
                            swal('Paket Berhasil disave!!', 'close dalam 2 detik', 'success');
                            console.log(aaa);
                            swal.enableLoading();
                            setTimeout(function(){
                                swal.close();
                            }, 1500)

                        })
                    }
                    else if (result) {
                        swal('Changes are not saved', '', 'info');
                    }
                });
            })

            $('.xedit').on('click', function(e){
                var pid = $(this).attr('data-produk_id');
                var nama = $(this).attr('data-sub_nama');
                var furut = $(this).attr('data-fase_urut');
                var sfurut = $(this).attr('data-sub_fase_urut');
                var href = $(this).attr('data-href');
                e.stopPropagation();
                e.preventDefault();
                console.log('pid: '+pid+'| nama: '+nama+'| furut: '+furut+'| sfurut: '+sfurut+'| href: '+href);
                $('.title_xedit[href=\''+href+'\']').editable('toggle')
                console.log( $('.title_xedit[href=\''+href+'\']') );
            })

            $('.spr_check').on('ifChanged', function(event){
                var pids = $(this).attr('pids');
                if( $(this).is(':checked') ){
                    $('#btnColls_'+pids).trigger('click');
                }
                else{
                    event
                    swal('mematikan mode separated room', 'dengan mematikan mode separated room, data yang pernah tersimpan akan dihapus, apakah anda yakin dengan hal ini??', 'question')
                    .then( (res)=>{
                        if(res){
                            $('#btnColls_'+pids).trigger('click');
                        }
                    })
                    .then((aaa)=>{
                        console.log(aaa);
                    })
                }
            });

            $('input[type=\"checkbox\"].minimal, input[type=\"radio\"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });

            function init_int_jual_project(){
                $('.int_jual_project').off();
                $('.int_jual_project').on('keyup', function(){
                    var pidfase     = $(this).attr('pidfase');
                    var cur         = removeCommas( $(this).val() );
                    var ori         = Math.round($('#hbom_'+pidfase).val());
                    var saved       = $('#saved_jual_'+pidfase).val();
                    var btn_danger  = $('#md1_btnSave_'+pidfase+' > span.btn-danger');
                    var btn_success = $('#md1_btnSave_'+pidfase+' > span.btn-success');
                    var btn_save    = $('#md1_btnSave_'+pidfase+' > span.btn-warning');

                    console.log('cur: '+cur);
                    console.log('ori: '+ori);
                    console.log('saved: '+saved);

                    if( cur*1 > ori*1 && saved!=cur ){
//                        console.log('oke');
                        $(btn_danger).addClass('hidden');
                        $(btn_success).addClass('hidden');
                        $(btn_save).removeClass('hidden');
                        $(this).addClass('text-green');
                        $(this).removeClass('text-red');
                        $(this).removeClass('text-orange');
                    }
                    else if( cur*1 < ori*1 ){
//                        console.error('not good');
                        $(btn_danger).removeClass('hidden');
                        $(btn_success).addClass('hidden');
                        $(btn_save).addClass('hidden');
                        $(this).addClass('text-red');
                        $(this).removeClass('text-orange');
                        $(this).removeClass('text-green');
                    }
                    else if( cur*1 == saved*1 ){
//                        console.log('no change');
                        $(btn_danger).addClass('hidden');
                        $(btn_success).removeClass('hidden');
                        $(btn_save).addClass('hidden');
                        $(this).removeClass('text-green');
                        $(this).removeClass('text-red');
                        $(this).removeClass('text-orange');
                    }
                    else if( cur*1 < saved*1 && cur*1 == ori*1){
//                        console.log('price change down');
                        $(btn_danger).addClass('hidden');
                        $(btn_success).addClass('hidden');
                        $(btn_save).removeClass('hidden');
                        $(this).removeClass('text-green');
                        $(this).removeClass('text-red');
                        $(this).addClass('text-orange');
                    }
                    else{
//                        console.log('kondisi embuh');
//                        console.log('kondisi embuh');
                        $(btn_danger).addClass('hidden');
                        $(btn_success).addClass('hidden');
                        $(btn_save).addClass('hidden');
                        $(btn_save).trigger('click');
                    }
                });
                $('.int_jual_project').trigger('keyup');
            }

            function init_saveHrgProject(){
                $('.save_jual_project').on('click', function(){
                    var cx_url    = $(this).attr('cx_url');
                    var produk_id = $(this).attr('produk_id');
                    var fase_id   = $(this).attr('fase_id');
                    var hjp       = $('#hjp_'+produk_id+''+fase_id).val();
                    var hbom      = $('#hbom_'+produk_id+''+fase_id).val();
                    (async () => {
                        const rawResponse = await fetch(cx_url, {
                             method: 'POST',
                             body: JSON.stringify({jual_project: removeCommas(hjp),hbom: removeCommas(hbom)})
                        });
                        const content = await rawResponse.json();
                        if(content.status){
                            eval(content.js)
                        }
                    })();
                });
            }

            function init_saveDiscProject(){
                $('.int_diskon').on('keyup', delay_v2(function(){
                    var cx_url      = $(this).attr('cx_url');
                    var produk_id   = $(this).attr('produk_id');
                    var fase_id     = $(this).attr('fase_id');
                    var diskon      = $('#diskon_'+produk_id+''+fase_id).val();
                    (async () => {
                      const rawResponse = await fetch(cx_url, {
                        method: 'POST',
                        body: JSON.stringify({diskon_project: removeCommas(diskon)})
                      });
                      const content = await rawResponse.json();
                      if(content.status){
                            eval(content.js)
                      }
                    })();
                },2500));
            }

            function preLoadFase(ev=''){
                $('.container_produk_fase').load('$modulClassLink/showProdukFase/$produkID', function(){
                    if(ev!=''){
                        eval(ev)
                    }
                });
            }

            function preLoadProdukFase(){
                var winSearch = top.window.location.search;
                $('#containerPreLoadProdukFase').load('$modulClassLink/showKomposisiProdukFase/$produkID'+winSearch);
            }

            function reloadSelectpicker(){
                $('.selectpicker').selectpicker('refresh');
            }

            function reloadTombolSimpan(){
                var formJml = document.querySelectorAll('[id*=\"jml\"]');
                jQuery.each(formJml, function(a,b){
                    var jml_value = $(b);
                    if( $(jml_value).val() * 1 > 0 ){
                        $(jml_value).trigger('blur');
                    }
                });
            }

            function initDeleteFunc(){
                $('.delWorkOrder').off();
                $('.delWorkOrder').on('click', function(){
                    var cx_jenis = $(this).attr('cx_jenis');
                    var cx_tmpnamamaterial = $(this).attr('cx_tmpnamamaterial');
                    var cx_deletelink = $(this).attr('cx_deletelink');
                    var cx_tid = $(this).attr('cx_tid');
                    var cx_mdl = $(this).attr('cx_mdl');
                    swal({
                        title: 'Apakah kamu yakin untuk menghapus?',
                        html: \"<span class='text-bold text-red text-uppercase'>\"+cx_tmpnamamaterial+\"</span> akan dihapus, data yg telah di hapus tidak bisa dikembalikan.\",
                        type: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'setuju & lanjutkan!'
                    })
                    .then(function(){
                        swal('proses menghapus<br>mohon tunggu sebentar');
                        swal.enableLoading();
                        $.ajax({
                            url: cx_deletelink + '' + cx_mdl + '/' + cx_tid,
                            success: function(res){
                                arrDatas = JSON.parse(res)
                                if(arrDatas.status){
                                    setTimeout(function(){
                                        swal('SUKSES', 'data berhasil dihapus', 'success');
                                        if (typeof top.preLoadFase == 'function') {
                                            top.preLoadFase('top.preLoadProdukFase()');
                                        }
                                    },1000)
                                }
                            }
                        });
                    })
                    .catch(function(reason){
                        //alert(\"The alert was dismissed by the user: \"+reason);
                    });
                })
            }

            function init_addProdukFase(){
                top.$('#addProdukFase').off();
                top.$('#addProdukFase').on('click', function(){
                    var id = $(this).attr('id');
                    var ev_aa = $(this).attr('cx_onclick');
                    var arr_input = $('.'+id);
                    var error = '';
                    jQuery.each(arr_input, function(a, b){
                        var keys = $(b).attr('key');
                        var val = $(b).val();
                        if( keys=='qty' && val=='' ){
                            error += '<b><r> Jumlah </r></b> wajib di isi<br>'
                        }
                        if( keys=='nama' && val=='' ){
                            error += '<b><r> Nama Rencana Kerja </r></b> wajib di isi'
                        }
                    });
                    if( error=='' ){
                        //bisa lanjut
                        eval(ev_aa);
                    }
                    else{
                        swal('isian kurang', error, 'error');
                    }
                });
            }
        </script>

        ";

        //JIKA ADA UPDATE COPAS DARI SINI SAJA

        $produkKomposisiFase .= "
        <script>
            top.reloadSummaryProject();\n
            console.log('showKomposisiProdukFase: DONE at ' + new Date() ); \n
        </script>";
        echo $produkKomposisiFase;
    }

    public function showSummaryProject()
    {

        $pid = $prodID = $produkID = $this->uri->segment(4);

        $this->load->model("Mdls/" . "MdlProjectKomposisi");
        $this->load->model("Mdls/" . "MdlProdukProject");
        $pk = new MdlProjectKomposisi();
        $o = new MdlProdukProject();

        $o->addFilter("id='$pid'");
        $tempProdukMaster = $o->lookUpAll()->result();
        $showFields     = $o->getListedFields();

        $lock = "";
        $no_kontrak = "";
        $nilai_proyek = "";
        $batas_waktu = "";
        $project_start = $tempProdukMaster[0]->project_start;

        foreach($showFields as $key => $label){
            switch($key){
                case "kode":
                    $no_kontrak = $tempProdukMaster[0]->$key;
                    break;
                case "harga":
                    $nilai_proyek = $tempProdukMaster[0]->$key;
                    break;
                case "end_dtime":
                    $batas_waktu = $tempProdukMaster[0]->$key;
                    break;
                case "lock":
                    $lock = $tempProdukMaster[0]->$key;
                    break;
            }
        }

        //produk komposisi sumary
        $pk->setFilters(array());
        $pk->addFilter("produk_id='$pid'");
        $pk->addFilter("jenis in ('produk','biaya')");
        $this->db->order_by("jenis", "DESC");
        $tempProduk = $pk->lookUpAll()->result();

        $sumarryProjectLabel = $pk->getListedFields();
        $sumarryProject = array();
        if (sizeof($tempProduk) > 0) {
            foreach ($tempProduk as $i => $temproduk_0) {
                foreach ($sumarryProjectLabel as $key => $label) {
                    $sumarryProject[$temproduk_0->jenis][$i][$key] = $temproduk_0->$key;
                }
                if ($temproduk_0->jenis == "biaya") {
                    unset($sumarryProject["biaya"][$i]["jml"]);
                    unset($sumarryProject["biaya"][$i]["harga"]);
                }
                $sumarryProject[$temproduk_0->jenis][$i]["subtotal"] = $temproduk_0->harga * $temproduk_0->jml;
                foreach ($temproduk_0 as $k => $v) {
                    //$_SESSION['PROED'][$prodID]['component_sum'][$temproduk_0->jenis][$temproduk_0->produk_dasar_id][$k] = $v;
                }
            }
        }

        $produkKomposisiFaseHeaderMini = array(
            "produk" => array(
                "produk_dasar_id" => "BB",
                "satuan" => "UOM",
                "ppv" => "HPPV",
                "nilai" => "HS",
                "harga" => "HA",
                "jml" => "JML",
                "subtotal" => "SUB",
            ),
            "biaya" => array(
                "produk_dasar_id" => "JASA",
                "cat_id" => "CAT",
                "satuan_id" => "UOM",
                "harga" => "HRG",
                "jml" => "JML",
                "subtotal" => "SUB",
            ),
        );
        $produk_komposisi_fase_header_mini = $produkKomposisiFaseHeaderMini;

        $relSuppliesHeader = array(
            "cat_id" => "cat_nama",
            "satuan_id" => "satuan",
            "produk_dasar_id" => "produk_dasar_nama",
            "harga_ori" => "harga",
            "harga_bom" => "harga_bom",
            "employee_id" => "employee_nama",
            "akses_id" => "hak_akses_nama",
            // "harga_bom"=>"harga_bom",
        );
        $relSuppliesHeader = $relSuppliesHeader;

        $sumaryProject = $sumarryProject;
        $produkNama = $tempProdukMaster[0]->nama;
        $submasterLabel = "";
        $produk_komposisi_fase_sub = isset($_SESSION['PROED'][$prodID]['component_sub']) ? $_SESSION['PROED'][$prodID]['component_sub'] : array();
        $produk_fase = isset($_SESSION['PROED'][$prodID]['fase']) ? $_SESSION['PROED'][$prodID]['fase'] : array();
        $sub_fase_nama = isset($_SESSION['PROED'][$prodID]['sub_fase_nama']) ? $_SESSION['PROED'][$prodID]['sub_fase_nama'] : array();
        $produk_komposisi_fase = isset($_SESSION['PROED'][$prodID]['component']) ? $_SESSION['PROED'][$prodID]['component'] : array();

        //KLO ADA UPDATE UBAH DI SINI AJA
        $rincianProject = "";
        $total_anggaran = 0;
        if (count($sumaryProject) > 0) {

            $rincianProject .= "<div class='box-header'>
            <h3>Ringkasan Anggaran <br><small><i><b><r>($produkNama)</r></b></i></small></h3>
            </div>";
            $rincianProject .= "<div class='panel panel-body no-padding table-responsive'>
                                    <h5 class='text-bold text-muted'>$submasterLabel</h5>";
            $rincianProject .= "<table class='table dataTable compact display table-bordered table-condensed'>";
            $rincianProject .= "<thead>";
            $rincianProject .= "<tr>";
            $rincianProject .= "<th>No</th>";
            $rincianProject .= "<th>Rencana Kerja</th>";
            $rincianProject .= "<th>Qty</th>";
            $rincianProject .= "<th>@BOM</th>";
            $rincianProject .= "<th>T.BOM</th>";
            $rincianProject .= "<th>@jual</th>";
            $rincianProject .= "<th>T.JUAL</th>";
            $rincianProject .= "<th>T.R/L</th>";
            $rincianProject .= "</tr>";
            $rincianProject .= "</thead>";
            $rincianProject .= "<tbody>";

            $no = 0;
            $subtotal = 0;
            $subtotalTotal = 0;
            $subtotalTotalJual = 0;
            foreach ($produk_fase as $k => $sumarryProdukFase_0) {
                $no++;
                $anggaran_total = 0;

                foreach ($produk_komposisi_fase_sub[$k] as $jenis_ => $arrAgr0) {
                    $nilai_anggaran = 0;
                    foreach ($arrAgr0 as $jenis => $arrAgr) {
                        switch ($jenis) {
                            case "produk":
                                foreach ($arrAgr as $ks => $prdAgr) {
                                    $nilai_anggaran += $prdAgr['subtotal'];
                                }
                                break;
                            case "biaya":
                                foreach ($arrAgr as $ks => $prdAgr) {
                                    $nilai_anggaran += $prdAgr['subtotal'];
                                }
                                break;
                        }
                    }
                    $anggaran_total += $nilai_anggaran;
                }

                $subtotal = $anggaran_total;
                $subtotalRata = $anggaran_total / $sumarryProdukFase_0['qty'];
                $subtotalJual = $produk_komposisi_fase[$k]['jual'][0]['harga'];
                $totalJual = $produk_komposisi_fase[$k]['jual'][0]['harga'] * $sumarryProdukFase_0['qty'];

//                echo json_encode($produk_komposisi_fase[$k]['jual'][0]['harga']) . "<br><br>";

                $rincianProject .= "<tr onclick=\"showHideRow($k);\">";
                $rincianProject .= "<td class='text-center text-bold'>$no</td>";
                $limitNama = strlen($sumarryProdukFase_0['nama']) > 25 ? substr($sumarryProdukFase_0['nama'], 0, 22) . "..." : $sumarryProdukFase_0['nama'];
                $rincianProject .= "<td class='text-left text-link text-bold' title='klik " . $sumarryProdukFase_0['nama'] . " untuk melihat detail'>" . $limitNama . "</td>";
                $rincianProject .= "<td class='text-left text-link text-bold'>" . $sumarryProdukFase_0['qty'] . "</td>";
                $rincianProject .= "<td>" . formatField("subtotal", $subtotalRata) . "</td>";
                $rincianProject .= "<td>" . formatField("subtotal", $subtotal) . "</td>";
                $rincianProject .= "<td>" . formatField("subtotal", $subtotalJual) . "</td>";
                $rincianProject .= "<td>" . formatField("subtotal", $totalJual) . "</td>";
                $rincianProject .= "<td>" . formatField("subtotal", $totalJual - $subtotal) . "</td>";
                $rincianProject .= "</tr>";

                $rincianProject .= "<tr id='hr_$k' class='hidden'>";
                $rincianProject .= "<td colspan='5' class='bg-olive text-black'>";
                $rincianProject .= "<div id='wrapper_$k' class='open' style='height:0px;overflow: hidden;transition: height 200ms;'>";
                $rincianProject .= "<div id='height_$k'>";

                $fase_urut = $k;
                foreach ($produk_komposisi_fase_sub[$k] as $nSubFase => $subFaseData) {
                    $sub_nama = isset($sub_fase_nama[$k][$nSubFase]) ? $sub_fase_nama[$k][$nSubFase] : "####";
                    $rincianProject .= "<div class='box'>";
                    $rincianProject .= "<div class='box-header text-bold text-uppercase text-primary'>$sub_nama</div>";
                    $rincianProject .= "<div class='box-body no-padding'>";
                    $faseSubTotal = 0;
                    foreach ($produk_komposisi_fase_header_mini as $hFieldKey => $hLabelData) {
                        switch ($hFieldKey) {
                            case "produk":
                                $rincianProject .= "<table style='zoom: 0.8;' class='table dataTable compact display striped table-bordered'>";
                                $rincianProject .= "<thead>";
                                $rincianProject .= "<tr>";
                                $rincianProject .= "<th>No</th>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $rincianProject .= "<th>$hLabel</th>";
                                }
                                $rincianProject .= "</tr>";
                                $rincianProject .= "</thead>";
                                $rincianProject .= "<tbody>";
                                $i = 0;
                                $presub = 0;
                                if (isset($produk_komposisi_fase_sub[$fase_urut][$nSubFase]["produk"]) && sizeof($produk_komposisi_fase_sub[$fase_urut][$nSubFase]["produk"])) {
                                    foreach ($produk_komposisi_fase_sub[$fase_urut][$nSubFase]["produk"] as $aa => $DataRelsupplies) {
                                        $tID = $DataRelsupplies["id"];
                                        $presub += $DataRelsupplies["jml"] * $DataRelsupplies["harga"];
                                        $rincianProject .= "<tr>";
                                        $i++;
                                        $rincianProject .= "<td>$i</td>";
                                        foreach ($hLabelData as $hField => $hLabel) {
                                            $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                                            $val = isset($DataRelsupplies[$transformKey]) ? $DataRelsupplies[$transformKey] : "";
                                            $rincianProject .= "<td>" . formatField($hField, $val) . "</td>";

                                        }
                                        $rincianProject .= "</tr>";
                                    }
                                }
                                $rincianProject .= "</tbody>";
                                $rincianProject .= "<tfoot class='text-bold'>";
                                $rincianProject .= "<tr>";
                                $rincianProject .= "<td colspan='" . count($hLabelData) . "' class='text-right'>Total</td>";
                                $rincianProject .= "<td>" . formatField("subtotal", $presub) . "</td>";
                                $rincianProject .= "</tr>";
                                $rincianProject .= "</tfoot>";
                                $rincianProject .= "</table>";

                                $faseSubTotal += $presub;
                                break;
                            case "biaya":
                                $rincianProject .= "<table style='zoom: 0.8;' class='table dataTable compact display table-bordered'>";
                                $rincianProject .= "<thead>";
                                $rincianProject .= "<tr>";
                                $rincianProject .= "<th>No</th>";
                                foreach ($hLabelData as $hField => $hLabel) {
                                    $rincianProject .= "<th>$hLabel</th>";
                                }
                                $rincianProject .= "</tr>";
                                $rincianProject .= "</thead>";
                                $rincianProject .= "<tbody>";
                                //bagaian data relasi komposisi
                                $i = 0;
                                $presub = 0;
                                if (isset($produk_komposisi_fase_sub[$fase_urut][$nSubFase]["biaya"]) && sizeof($produk_komposisi_fase_sub[$fase_urut][$nSubFase]["biaya"]) > 0) {
                                    foreach ($produk_komposisi_fase_sub[$fase_urut][$nSubFase]["biaya"] as $DataRelsuppliesBiaya) {
                                        $presub += $DataRelsuppliesBiaya["jml"] * $DataRelsuppliesBiaya["harga"];
                                        $rincianProject .= "<tr>";
                                        $i++;
                                        $rincianProject .= "<td>$i</td>";
                                        foreach ($hLabelData as $hField => $hLabel) {
                                            $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                                            $val = isset($DataRelsuppliesBiaya[$transformKey]) ? $DataRelsuppliesBiaya[$transformKey] : "";
                                            $rincianProject .= "<td>" . formatField($hField, $val) . "</td>";
                                        }
                                        $rincianProject .= "</tr>";
                                    }
                                }
                                $rincianProject .= "</tbody>";
                                $rincianProject .= "<tfoot class='text-bold'>";
                                $rincianProject .= "<tr>";
                                $rincianProject .= "<td colspan='" . count($hLabelData) . "' class='text-right'>Total</td>";
                                $rincianProject .= "<td>" . formatField("subtotal", $presub) . "</td>";
                                $rincianProject .= "</tr>";
                                $rincianProject .= "</tfoot>";
                                $rincianProject .= "</table>";

                                $faseSubTotal += $presub;
                                break;
                            case "jual":
                                if (isset($produk_komposisi_fase_sub[$fase_urut][$nSubFase]["jual"]) && sizeof($produk_komposisi_fase_sub[$fase_urut][$nSubFase]["jual"])) {
                                    foreach ($produk_komposisi_fase_sub[$fase_urut][$nSubFase]["jual"] as $aa => $DataRelsupplies) {
                                        foreach ($hLabelData as $hField => $hLabel) {
                                            $transformKey = isset($relSuppliesHeader[$hField]) ? $relSuppliesHeader[$hField] : $hField;
                                            $val = isset($DataRelsupplies[$transformKey]) ? $DataRelsupplies[$transformKey] : "";

                                        }
                                    }
                                }
                                break;
                        }
                    }
                    $rincianProject .= "<table style='zoom: 0.8;' class='table dataTable compact display table-bordered'>";
                    $rincianProject .= "<thead class='text-bold'>";
                    $rincianProject .= "<tr>";
                    $rincianProject .= "<th colspan='' class='text-right fa-2x'>Total</th>";
                    $rincianProject .= "<th class='fa-2x'>" . formatField("subtotal", $faseSubTotal) . "</th>";
                    $rincianProject .= "</tr>";
                    $rincianProject .= "</thead>";
                    $rincianProject .= "</table>";
                    $rincianProject .= "</div>";
                    $rincianProject .= "</div>";
                }

                $rincianProject .= "<table style='zoom: 0.8;' class='table dataTable compact display table-bordered'>";
                $rincianProject .= "<thead class='text-bold'>";
                $rincianProject .= "<tr>";
                $rincianProject .= "<th colspan='' class='text-right fa-2x'>Total</th>";
                $rincianProject .= "<th class='fa-2x'>" . formatField("subtotal", $subtotal) . "</th>";
                $rincianProject .= "</tr>";
                $rincianProject .= "</thead>";
                $rincianProject .= "</table>";

                $rincianProject .= "</div>";
                $rincianProject .= "</div>";

                $rincianProject .= "</td>";
                $rincianProject .= "</tr>";

                $subtotalTotal += $subtotal;
                $subtotalTotalJual += $totalJual;
            }

            $rincianProject .= "</tbody>";
            $rincianProject .= "<tfoot>";

            $rincianProject .= "<tr>";
            $rincianProject .= "<td colspan='3' class='text-center'>Total Anggaran</td>";
            $rincianProject .= "<td colspan='2'>" . formatField("subtotal", $subtotalTotal) . "</td>";
            $rincianProject .= "</tr>";

            $rincianProject .= "<tr>";
            $rincianProject .= "<td colspan='3' class='text-center'>Total Jual Project</td>";
            $rincianProject .= "<td colspan='2'>" . formatField("subtotal", $subtotalTotalJual) . "</td>";
            $rincianProject .= "</tr>";

            $bg_color = $subtotalTotalJual - $subtotalTotal < 0 ? "bg-danger" : "bg-info";
            $kerugin = $subtotalTotalJual - $subtotalTotal < 0 ? " <br><small><r>rugi " . number_format($subtotalTotal - $subtotalTotalJual) . "</r></small>" : "";

            $rincianProject .= "<tr class='$bg_color text-bold fa-2x'>";
            $rincianProject .= "<td colspan='3' class='text-center'>Total R/L $kerugin</td>";
            $rincianProject .= "<td colspan='2'>" . formatField("subtotal", $subtotalTotalJual - $subtotalTotal) . "</td>";
            $rincianProject .= "</tr>";
            $rincianProject .= "</tfoot>";
            $rincianProject .= "</table>";
            $rincianProject .= "</div>";

            $id = $this->uri->segment(4);

            //TOMBOL SAVE PROJECT
            $linkSaveBom = "" . MODUL_PATH . "MasterData/saveBom/" . $id . "/" . $subtotalTotalJual;
            $editBomLink = "" . MODUL_PATH . "MasterData/editBom/" . $id;

            if ($lock) {
                if ($project_start) {
                    $rincianProject .= "<div class='alert alert-default'>";
                    $rincianProject .= "<div class='btn-group btn-sm'>";
                    $rincianProject .= "<button type='button' class='btn btn-block btn-flat btn-default'>EDIT MATERIAL BOM</button>";
                    $rincianProject .= "<span class='text-bold text-auto text-center'>EDIT MATERIAL TIDAK DAPAT DILAKUKAN LAGI KARENA PROJECT TELAH RUNNING</span>";
                    $rincianProject .= "</div>";
                    $rincianProject .= "</div>";
                }
                else {
                    $rincianProject .= "<div class='alert alert-success'>";
                    $rincianProject .= "<div class='btn-group btn-sm'>";
                    $rincianProject .= "<button type='button' onclick=\"if(confirm('Edit Material BOM dapat membatalkan Quotation sebelumnya..!! Yakin melanjutkan ini..??')){window.location.href='$editBomLink'}\" class='btn btn-block btn-flat btn-default'>EDIT MATERIAL BOM</button>";
                    $rincianProject .= "<span class='text-bold text-auto text-center'>Edit Material BOM dapat membatalkan <r>Quotation</r> sebelumnya..!!</span>";
                    $rincianProject .= "<span class='text-bold text-auto text-center'>Sehingga Quotation baru yg di update, harus diapprove kembali.</span>";
                    $rincianProject .= "</div>";
                    $rincianProject .= "</div>";
                }
            }
            else {
                if ($lock && $transaksi_no != "" && $no_kontrak != 0) {
                    $rincianProject .= "lock: $lock || transaksi_no: $transaksi_no || no_kontrak: $no_kontrak";
                    //jika sudah lock dan sudah di approve serta sudah ada no kontraknya
                }
                else {
                    $rincianProject .= "<div class='alert alert-success'>";
                    $rincianProject .= "<div class='btn-group btn-sm'>";
                    $rincianProject .= "<button type='button' onclick=\"if(confirm('Setelah Material BOM disimpan, maka BOM tidak dapat ditambah atau dikurangi lagi, namun tombol Edit bisa membantu Anda untuk kembali pada Mode Editor BOM. Yakin melanjutkan ini..??')){window.location.href='$linkSaveBom'}\" class='btn btn-block btn-flat btn-warning'>SIMPAN BOM PROJECT & LANJUTKAN</button>";
                    $rincianProject .= "<span class='text-bold text-auto text-center'>Setelah Material BOM disimpan, maka BOM tidak dapat ditambah atau dikurangi lagi, namun tombol Edit bisa membantu Anda untuk kembali pada Mode Editor BOM.</span>";
                    $rincianProject .= "</div>";
                    $rincianProject .= "</div>";
                }
            }
        }
        //KLO ADA UPDATE UBAH DI SINI AJA

        $rincianProject .= "<script>\n

            top.init_addProdukFase();\n
            top.initDeleteFunc();\n
            top.reloadTombolSimpan();\n
            top.init_saveHrgProject();\n
            top.init_saveDiscProject();\n
            top.init_int_jual_project();\n
            top.init_mainMaterialSetting();\n

            if( localStorage.main_last_collapsed!= undefined && localStorage.main_last_collapsed != '' ){\n
                $('.nav-tabs a[href=\"'+localStorage.main_last_collapsed+'\"]').tab('show');\n
            }\n

            top.reloadSelectpicker();\n

        </script>";

        echo $rincianProject;
    }

    public function delete()
    {
        $content = "";
        //==menghapus (aslinya mendisable) data sesuai datamodel dan id-nya yang bersesuaian
        $ctrlName = $this->uri->segment(4);
        $selectedID = $this->uri->segment(5);

        $className = "Mdl" . $ctrlName;
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();

        $this->db->trans_start();

        switch ($ctrlName) {
            case "ProjectWorkOrder":

                $this->load->model("Mdls/" . $className);
                $o = new $className;
                $indexFieldName = "id";
                $where = array("id" => $selectedID);
                $oldDataTmp = $o->lookupByID($selectedID)->result();

                if (!empty($oldDataTmp)) {
                    $whereTrash = array(
                        "id" => $selectedID,
                    );
                    $trashCommand = array(
                        "status" => "0",
                        "trash" => "1",
                        "trash_dtime" => date("Y-m-d H:i:s"),
                        "trash_oleh_id" => $this->session->login['id'],
                        "trash_oleh_nama" => $this->session->login['nama'],
                    );
                    $o->setFilters(array());
                    $o->updateData($whereTrash, $trashCommand) or matiHere("GAGAL HAPUS RENCANA KERJA IDS: $selectedID || LINE: " . __LINE__);

                    $fase_id = $oldDataTmp[0]->id;
                    $produk_id = $oldDataTmp[0]->produk_id;

                    $this->load->model("Mdls/" . "MdlProjectWorkOrderSub");
                    $os3 = new MdlProjectWorkOrderSub();
                    $where = array(
                        "fase_id" => $fase_id,
                        "produk_id" => $produk_id,
                    );
                    $this->db->where($where);
                    $oldSubDataTmp3 = $os3->lookupAll()->result();
                    if (!empty($oldSubDataTmp3)) {
                        foreach ($oldSubDataTmp3 as $is => $subData) {
                            $ids = $subData->id;
                            $whereTrash = array(
                                "id" => $ids,
                            );
                            $trashCommand = array(
                                "status" => "0",
                                "trash" => "1",
                                "trash_dtime" => date("Y-m-d H:i:s"),
                                "trash_oleh_id" => $this->session->login['id'],
                                "trash_oleh_nama" => $this->session->login['nama'],
                            );
                            $os3->setFilters(array());
                            $os3->updateData($whereTrash, $trashCommand) or matiHere("GAGAL HAPUS WORK ORDER SUB IDS: $ids || LINE: " . __LINE__);
                        }
                    }

                    $this->load->model("Mdls/" . "MdlProjectKomposisiWorkorder");
                    $os = new MdlProjectKomposisiWorkorder();
                    $where = array(
                        "fase_id" => $fase_id,
                        "produk_id" => $produk_id,
                    );
                    $this->db->where($where);
                    $oldSubDataTmp = $os->lookupAll()->result();
                    if (!empty($oldSubDataTmp)) {
                        foreach ($oldSubDataTmp as $is => $subData) {
                            $ids = $subData->id;
                            $whereTrash = array(
                                "id" => $ids,
                            );
                            $trashCommand = array(
                                "status" => "0",
                                "trash" => "1",
                                "trash_dtime" => date("Y-m-d H:i:s"),
                                "trash_oleh_id" => $this->session->login['id'],
                                "trash_oleh_nama" => $this->session->login['nama'],
                            );
                            $os->setFilters(array());
                            $os->updateData($whereTrash, $trashCommand) or matiHere("GAGAL HAPUS KOMPOSISI WORK ORDER IDS: $ids || LINE: " . __LINE__);
                        }
                    }

                    $this->load->model("Mdls/" . "MdlProjectKomposisiWorkorderSub");
                    $os1 = new MdlProjectKomposisiWorkorderSub();
                    $where = array(
                        "fase_id" => $fase_id,
                        "produk_id" => $produk_id,
                        "jenis_transaksi" => 5582,//jenis_transaksi='5582'
                    );
                    $this->db->where($where);
                    $oldSubDataTmp1 = $os1->lookupAll()->result();
                    if (!empty($oldSubDataTmp1)) {
                        foreach ($oldSubDataTmp1 as $is => $subData) {
                            $ids = $subData->id;
                            $whereTrash = array(
                                "id" => $ids,
                            );
                            $trashCommand = array(
                                "status" => "0",
                                "trash" => "1",
                                "trash_dtime" => date("Y-m-d H:i:s"),
                                "trash_oleh_id" => $this->session->login['id'],
                                "trash_oleh_nama" => $this->session->login['nama'],
                            );
                            $os1->setFilters(array());
                            $os1->updateData($whereTrash, $trashCommand) or matiHere("GAGAL HAPUS KOMPOSISI SUB WORK ORDER IDS: $ids || LINE: " . __LINE__);
                        }
                    }
                }
                break;
            case "TimWorkProject":
                $this->load->model("Mdls/" . $className);
                $o = new $className;
                $indexFieldName = "id";
                $where = array("id" => $selectedID);
                $oldDataTmp = $o->lookupByID($selectedID)->result();
                if (!empty($oldDataTmp)) {
                    $whereTrash = array(
                        "id" => $selectedID,
                    );
                    $trashCommand = array(
                        "status" => "0",
                        "trash" => "1",
                        "trash_dtime" => date("Y-m-d H:i:s"),
                        "trash_oleh_id" => $this->session->login['id'],
                        "trash_oleh_nama" => $this->session->login['nama'],
                    );
                    $o->setFilters(array());
                    $o->updateData($whereTrash, $trashCommand) or matiHere("GAGAL HAPUS RENCANA KERJA IDS: $selectedID || LINE: " . __LINE__);
                }
                break;
            case "ProjectKomposisiWorkorder":
                $this->load->model("Mdls/" . $className);
                $o = new $className;
                $indexFieldName = "id";
                $where = array("id" => $selectedID);
                $o->setFilters(array());
                $oldDataTmp = $o->lookupByID($selectedID)->result();
                $oldDataTmpQuery = $this->db->last_query();
                if (!empty($oldDataTmp)) {
                    $whereTrash = array(
                        "id" => $selectedID,
                    );
                    $trashCommand = array(
                        "status" => "0",
                        "trash" => "1",
                        "trash_dtime" => date("Y-m-d H:i:s"),
                        "trash_oleh_id" => $this->session->login['id'],
                        "trash_oleh_nama" => $this->session->login['nama'],
                    );
                    $o->setFilters(array());
                    $o->updateData($whereTrash, $trashCommand) or matiHere("GAGAL HAPUS KOMPOSISI SUB WORK ORDER IDS: $ids || LINE: " . __LINE__);
                    $deleteMain = $this->db->last_query();
                    $jenis = $oldDataTmp[0]->jenis;
                    $fase_id = $oldDataTmp[0]->fase_id;
                    $produk_id = $oldDataTmp[0]->produk_id;
                    $produk_dasar_id = $oldDataTmp[0]->produk_dasar_id;
                    $this->load->model("Mdls/" . "MdlProjectKomposisiWorkorderSub");
                    $os = new MdlProjectKomposisiWorkorderSub();
                    $os->setFilters(array());
                    $where = array(
                        "jenis" => $jenis,
                        "fase_id" => $fase_id,
                        "produk_id" => $produk_id,
                        "produk_dasar_id" => $produk_dasar_id,
                        "status" => 1,
                        "trash" => 0,
                        "jenis_transaksi" => 5582,//jenis_transaksi='5582'
                    );
                    $this->db->where($where);
                    $oldSubDataTmp = $os->lookupAll()->result();
                    $oldSubDataTmpQuery = $this->db->last_query();
                    $deleteSub = array();
                    if (!empty($oldSubDataTmp)) {
                        foreach ($oldSubDataTmp as $is => $subData) {
                            $ids = $subData->id;
                            $whereTrash = array(
                                "id" => $ids,
                            );
                            $trashCommand = array(
                                "status" => "0",
                                "trash" => "1",
                                "trash_dtime" => date("Y-m-d H:i:s"),
                                "trash_oleh_id" => $this->session->login['id'],
                                "trash_oleh_nama" => $this->session->login['nama'],
                            );
                            $os->setFilters(array());
                            $os->updateData($whereTrash, $trashCommand) or matiHere("GAGAL HAPUS KOMPOSISI SUB WORK ORDER IDS: $ids || LINE: " . __LINE__);
                            $deleteSub[$ids] = $this->db->last_query();
                        }
                    }
                }

                //detailsBiaya cek jika ada supplies dari biaya
                $this->load->model("Mdls/" . "MdlProjectKomponenBiayaDetailsRab"); //register detail biaya per project
                $byCr = new MdlProjectKomponenBiayaDetailsRab();
                $byCr->setFilters(array());
                $where = array(
                    "link_id" => $selectedID,
                    "status" => 1,
                    "trash" => 0,
                );
                $this->db->where($where);
                $tmpByData = $byCr->lookUpAll()->result();
                if(!empty($tmpByData)){
                    $ids = $selectedID;
                    $whereTrash = array(
                        "link_id" => $selectedID,
                    );
                    $trashCommand = array(
                        "status" => "0",
                        "trash" => "1",
                    );
                    $byCr->setFilters(array());
                    $byCr->updateData($whereTrash, $trashCommand) or matiHere("GAGAL HAPUS project_komponen_biaya_details_rab link_id: $ids || LINE: " . __LINE__);
                }

                break;
            case "ProjectKomposisiWorkorderRoom":

                $this->load->model("Mdls/" . $className);
                $o = new $className;
                $indexFieldName = "id";
                $where = array("id" => $selectedID);
                $o->setFilters(array());
                $oldDataTmp = $o->lookupByID($selectedID)->result();
                $oldDataTmpQuery = $this->db->last_query();
                if (!empty($oldDataTmp)) {
                    $whereTrash = array(
                        "id" => $selectedID,
                    );
                    $trashCommand = array(
                        "status" => "0",
                        "trash" => "1",
                        "trash_dtime" => date("Y-m-d H:i:s"),
                        "trash_oleh_id" => $this->session->login['id'],
                        "trash_oleh_nama" => $this->session->login['nama'],
                    );
                    $o->setFilters(array());
                    $o->updateData($whereTrash, $trashCommand) or matiHere("GAGAL HAPUS KOMPOSISI SUB WORK ORDER IDS: $ids || LINE: " . __LINE__);

//                    $deleteMain = $this->db->last_query();
//                    $jenis = $oldDataTmp[0]->jenis;
//                    $fase_id = $oldDataTmp[0]->fase_id;
//                    $produk_id = $oldDataTmp[0]->produk_id;
//                    $produk_dasar_id = $oldDataTmp[0]->produk_dasar_id;
//                    $this->load->model("Mdls/" . "MdlProjectKomposisiWorkorderSub");
//                    $os = new MdlProjectKomposisiWorkorderSub();
//                    $os->setFilters(array());
//                    $where = array(
//                        "jenis" => $jenis,
//                        "fase_id" => $fase_id,
//                        "produk_id" => $produk_id,
//                        "produk_dasar_id" => $produk_dasar_id,
//                        "status" => 1,
//                        "trash" => 0,
//                    );
//                    $this->db->where($where);

//                    $oldSubDataTmp = $os->lookupAll()->result();

//                    $oldSubDataTmpQuery = $this->db->last_query();
//                    $deleteSub=array();
//                    if(!empty($oldSubDataTmp)){
//                        foreach($oldSubDataTmp as $is => $subData){
//                            $ids = $subData->id;
//                            $whereTrash = array(
//                                "id" => $ids,
//                            );
//                            $trashCommand = array(
//                                "status" => "0",
//                                "trash" => "1",
//                                "trash_dtime" => date("Y-m-d H:i:s"),
//                                "trash_oleh_id" => $this->session->login['id'],
//                                "trash_oleh_nama" => $this->session->login['nama'],
//                            );
//                            $os->setFilters(array());
//                            $os->updateData($whereTrash, $trashCommand) or matiHere("GAGAL HAPUS KOMPOSISI SUB WORK ORDER IDS: $ids || LINE: " . __LINE__);
//                            $deleteSub[$ids] = $this->db->last_query();
//                        }
//                    }
                }
                break;
            case "ProjectKomposisiWorkorderRoomProduk":

                $this->load->model("Mdls/" . $className);
                $o = new $className;
                $indexFieldName = "id";
                $where = array("id" => $selectedID);
                $o->setFilters(array());
                $oldDataTmp = $o->lookupByID($selectedID)->result();
                $oldDataTmpQuery = $this->db->last_query();
                if (!empty($oldDataTmp)) {
                    $whereTrash = array(
                        "id" => $selectedID,
                    );
                    $trashCommand = array(
                        "status" => "0",
                        "trash" => "1",
                        "trash_dtime" => date("Y-m-d H:i:s"),
                        "trash_oleh_id" => $this->session->login['id'],
                        "trash_oleh_nama" => $this->session->login['nama'],
                    );
                    $o->setFilters(array());
                    $o->updateData($whereTrash, $trashCommand) or matiHere("GAGAL HAPUS KOMPOSISI SUB WORK ORDER IDS: $ids || LINE: " . __LINE__);

//                    $deleteMain = $this->db->last_query();
//                    $jenis = $oldDataTmp[0]->jenis;
//                    $fase_id = $oldDataTmp[0]->fase_id;
//                    $produk_id = $oldDataTmp[0]->produk_id;
//                    $produk_dasar_id = $oldDataTmp[0]->produk_dasar_id;
//                    $this->load->model("Mdls/" . "MdlProjectKomposisiWorkorderSub");
//                    $os = new MdlProjectKomposisiWorkorderSub();
//                    $os->setFilters(array());
//                    $where = array(
//                        "jenis" => $jenis,
//                        "fase_id" => $fase_id,
//                        "produk_id" => $produk_id,
//                        "produk_dasar_id" => $produk_dasar_id,
//                        "status" => 1,
//                        "trash" => 0,
//                    );
//                    $this->db->where($where);

//                    $oldSubDataTmp = $os->lookupAll()->result();

//                    $oldSubDataTmpQuery = $this->db->last_query();
//                    $deleteSub=array();
//                    if(!empty($oldSubDataTmp)){
//                        foreach($oldSubDataTmp as $is => $subData){
//                            $ids = $subData->id;
//                            $whereTrash = array(
//                                "id" => $ids,
//                            );
//                            $trashCommand = array(
//                                "status" => "0",
//                                "trash" => "1",
//                                "trash_dtime" => date("Y-m-d H:i:s"),
//                                "trash_oleh_id" => $this->session->login['id'],
//                                "trash_oleh_nama" => $this->session->login['nama'],
//                            );
//                            $os->setFilters(array());
//                            $os->updateData($whereTrash, $trashCommand) or matiHere("GAGAL HAPUS KOMPOSISI SUB WORK ORDER IDS: $ids || LINE: " . __LINE__);
//                            $deleteSub[$ids] = $this->db->last_query();
//                        }
//                    }
                }
                break;
            case "ProjectKomposisiWorkorderRoomProdukBiaya":

                $this->load->model("Mdls/" . $className);
                $o = new $className;
                $indexFieldName = "id";
                $where = array("id" => $selectedID);
                $o->setFilters(array());
                $oldDataTmp = $o->lookupByID($selectedID)->result();
                $oldDataTmpQuery = $this->db->last_query();
                if (!empty($oldDataTmp)) {
                    $whereTrash = array(
                        "id" => $selectedID,
                    );
                    $trashCommand = array(
                        "status" => "0",
                        "trash" => "1",
                        "trash_dtime" => date("Y-m-d H:i:s"),
                        "trash_oleh_id" => $this->session->login['id'],
                        "trash_oleh_nama" => $this->session->login['nama'],
                    );
                    $o->setFilters(array());
                    $o->updateData($whereTrash, $trashCommand) or matiHere("GAGAL HAPUS KOMPOSISI SUB WORK ORDER IDS: $ids || LINE: " . __LINE__);

//                    $deleteMain = $this->db->last_query();
//                    $jenis = $oldDataTmp[0]->jenis;
//                    $fase_id = $oldDataTmp[0]->fase_id;
//                    $produk_id = $oldDataTmp[0]->produk_id;
//                    $produk_dasar_id = $oldDataTmp[0]->produk_dasar_id;
//                    $this->load->model("Mdls/" . "MdlProjectKomposisiWorkorderSub");
//                    $os = new MdlProjectKomposisiWorkorderSub();
//                    $os->setFilters(array());
//                    $where = array(
//                        "jenis" => $jenis,
//                        "fase_id" => $fase_id,
//                        "produk_id" => $produk_id,
//                        "produk_dasar_id" => $produk_dasar_id,
//                        "status" => 1,
//                        "trash" => 0,
//                    );
//                    $this->db->where($where);

//                    $oldSubDataTmp = $os->lookupAll()->result();

//                    $oldSubDataTmpQuery = $this->db->last_query();
//                    $deleteSub=array();
//                    if(!empty($oldSubDataTmp)){
//                        foreach($oldSubDataTmp as $is => $subData){
//                            $ids = $subData->id;
//                            $whereTrash = array(
//                                "id" => $ids,
//                            );
//                            $trashCommand = array(
//                                "status" => "0",
//                                "trash" => "1",
//                                "trash_dtime" => date("Y-m-d H:i:s"),
//                                "trash_oleh_id" => $this->session->login['id'],
//                                "trash_oleh_nama" => $this->session->login['nama'],
//                            );
//                            $os->setFilters(array());
//                            $os->updateData($whereTrash, $trashCommand) or matiHere("GAGAL HAPUS KOMPOSISI SUB WORK ORDER IDS: $ids || LINE: " . __LINE__);
//                            $deleteSub[$ids] = $this->db->last_query();
//                        }
//                    }
                }
                break;
            case "ProdukKomposisiPaket":

                $this->load->model("Mdls/" . $className);
                $o = new $className;
                $indexFieldName = "id";
                $where = array("id" => $selectedID);
                $o->setFilters(array());
                $oldDataTmp = $o->lookupByID($selectedID)->result();

                $oldDataTmpQuery = $this->db->last_query();

                if (!empty($oldDataTmp)) {
                    $whereTrash = array(
                        "id" => $selectedID,
                    );
                    $trashCommand = array(
                        "status" => "0",
                        "trash" => "1",
                        "trash_dtime" => date("Y-m-d H:i:s"),
                        "trash_oleh_id" => $this->session->login['id'],
                        "trash_oleh_nama" => $this->session->login['nama'],
                    );
                    $o->setFilters(array());
                    $o->updateData($whereTrash, $trashCommand) or matiHere("GAGAL HAPUS KOMPOSISI BOM IDS: $ids || LINE: " . __LINE__);

                    $deleteMain = $this->db->last_query();

        }
                break;
            case "ProjectProdukPaket":

                $this->load->model("Mdls/" . $className);
                $o = new $className;
                $indexFieldName = "id";
                $where = array("id" => $selectedID);
                $o->setFilters(array());
                $oldDataTmp = $o->lookupByID($selectedID)->result();

                $oldDataTmpQuery = $this->db->last_query();

                if (!empty($oldDataTmp)) {
                    $whereTrash = array(
                        "id" => $selectedID,
                    );
                    $trashCommand = array(
                        "status" => "0",
                        "trash" => "1",
                        "trash_dtime" => date("Y-m-d H:i:s"),
                        "trash_oleh_id" => $this->session->login['id'],
                        "trash_oleh_nama" => $this->session->login['nama'],
                    );
                    $o->setFilters(array());
                    $o->updateData($whereTrash, $trashCommand) or matiHere("GAGAL HAPUS PRODUK PAKET IDS: $ids || LINE: " . __LINE__);

                    $deleteMain = $this->db->last_query();

                }
                break;
        }

        $this->db->trans_complete();

        $result = array(
            "status" => 1,
            "data" => $oldDataTmp,
        );

        echo json_encode($result);

    }

    public function showCreateSubTasklist()
    {

        $id = $this->uri->segment(4);
        $produk_id = $this->uri->segment(5);

        $this->load->model("MdlTasklistProject");
        $tl = new MdlTasklistProject();
        $tl->addFilter("id='$id'");
        $tl->addFilter("produk_id='$produk_id'");
        $tl->addFilter("status='1'");
        $tl->addFilter("trash='0'");
        $this->db->where("progress_percent <=", 100);

        $tempTaskList = $tl->lookUpAll()->result();
        $tempTaskListQuery = $tl->db->last_query();

        $nomer = $tempTaskList[0]->nomer;
        $no_spk = $tempTaskList[0]->no_spk;
        $produk_id = $tempTaskList[0]->produk_id;
        $fase_id = $tempTaskList[0]->fase_id;
        $sub_fase_id = $tempTaskList[0]->sub_fase_id;
        $task_progress = $tempTaskList[0]->progress_percent;
        $task_nama = $tempTaskList[0]->produk_nama;
        $project_nama = $tempTaskList[0]->nama;
        $ambil_angka_depan_spk = explode("/", $no_spk)[0];
        $gudang_project = "$produk_id" . "$fase_id" . "$sub_fase_id" . "$ambil_angka_depan_spk";

        //material sudah distribusi
        $arrMaterialDist = array();
        $this->load->model("Mdls/MdlLockerStock");
        $ls = new MdlLockerStock();
//        $ls->addFilter("cabang_id='$sub_fase_id'");
        $ls->addFilter("gudang_id='$gudang_project'");
        $ls->addFilter("state='active'");
        $tempLs = $ls->lookUpAll()->result();
        $arrProdukDistQuery = $this->db->last_query();
        if (!empty($tempLs)) {
            foreach ($tempLs as $rows) {
                $arrMaterialDist['produk'][$rows->produk_id] = $rows;
                $arrMaterialDist['produk'][$rows->produk_id]->biaya_nama = 'produk';
            }
        }

        $this->load->model("Mdls/MdlLockerStockSupplies");
        $lss = new MdlLockerStockSupplies();
//        $ls->addFilter("cabang_id='$sub_fase_id'");
        $lss->addFilter("gudang_id='$gudang_project'");
        $lss->addFilter("state='active'");
        $tempLss = $lss->lookUpAll()->result();
        $arrSuppliesDistQuery = $this->db->last_query();
        if (!empty($tempLss)) {
            foreach ($tempLss as $rows) {
                $arrMaterialDist['supplies'][$rows->biaya_id][$rows->produk_id] = $rows;
                $arrMaterialDist['supplies'][$rows->biaya_id][$rows->produk_id]->biaya_nama = 'supplies';
            }
        }

        //material sudah distribusi hold
        $arrMaterialDistHold = array();
        $this->load->model("Mdls/MdlLockerStock");
        $lsh = new MdlLockerStock();
//        $lsh->addFilter("cabang_id='$sub_fase_id'");
        $lsh->addFilter("gudang_id='$gudang_project'");
        $lsh->addFilter("state='hold'");
        $tempLsh = $lsh->lookUpAll()->result();
        $arrProdukDistHoldQuery = $this->db->last_query();

        if (!empty($tempLsh)) {
            foreach ($tempLsh as $rows) {
                $arrMaterialDistHold[$rows->produk_id] = $rows;
            }
        }

        $this->load->model("Mdls/MdlLockerStockSupplies");
        $lshs = new MdlLockerStockSupplies();
//        $lsh->addFilter("cabang_id='$sub_fase_id'");
        $lshs->addFilter("gudang_id='$gudang_project'");
        $lshs->addFilter("state='distribute'");
        $tempLshs = $lshs->lookUpAll()->result();
        $arrSuppliesDistHoldQuery = $this->db->last_query();

        if (!empty($tempLshs)) {
            foreach ($tempLshs as $rows) {
                $arrMaterialDistHold[$rows->biaya_id][$rows->produk_id] = $rows;
            }
        }

//        if(!empty($arrMaterialDistHold)){
//            foreach($arrMaterialDist as $dpid => $dpidData){
//                if(isset($arrMaterialDistHold[$dpid])){
//                    $arrMaterialDist[$dpid]->jumlah = $arrMaterialDist[$dpid]->jumlah + $arrMaterialDistHold[$dpid]->jumlah;
//                }
//            }
//        }

        $arrProdukUsedMain = array();
        $arrProdukUsedMainRoom = array();
        //LIST BAHAN-BAKU NYA

        $KomposisiWorkorder = array();
        if (!empty($tempTaskList)) {

            $this->load->model("Mdls/MdlProjectKomposisiWorkorderSub");
            $mWo = new MdlProjectKomposisiWorkorderSub();

            $fase_id = $tempTaskList[0]->fase_id;
            $sub_fase_id = $tempTaskList[0]->sub_fase_id;

            $mWo->setFilters(array());
            $mWo->addFilter("produk_id='$produk_id'");
            $mWo->addFilter("fase_id='$fase_id'");
            $mWo->addFilter("sub_fase_id='$sub_fase_id'");
            $mWo->addFilter("no_spk='$no_spk'");
            $mWo->addFilter("jenis_transaksi='sub_wo'");
            $mWo->addFilter("status=1");
            $mWo->addFilter("trash=0");
            $mWo->addFilter("jenis in ('produk','item_komposit')");
            $this->db->order_by("jenis", "desc");
            $tmpKomposisiWorkorder = $mWo->lookUpAll()->result();

            if(!empty($tmpKomposisiWorkorder)){
                foreach($tmpKomposisiWorkorder as $row){
                    $KomposisiWorkorder['produk_'.$row->produk_dasar_id] = (array)$row;
                    if (!isset($KomposisiWorkorder['produk_'.$row->produk_dasar_id]['jml_total'])) {
                        $KomposisiWorkorder['produk_'.$row->produk_dasar_id]['jml_total'] = 0;
                    }
                    $KomposisiWorkorder['produk_'.$row->produk_dasar_id]['jml_total'] += $row->jml*1;
                    $KomposisiWorkorder['produk_'.$row->produk_dasar_id]['jml_return'] = 0;
                }
            }

            $KomposisiWorkorderQuery = $this->db->last_query();

            //supplies
            $this->load->model("Mdls/MdlProjectKomponenBiayaDetailsRabSub");
            $mBo = new MdlProjectKomponenBiayaDetailsRabSub();

            $mBo->setFilters(array());
            $mBo->addFilter("fase_id='$fase_id'");
            $mBo->addFilter("sub_fase_id='$sub_fase_id'");
            $mBo->addFilter("no_spk='$no_spk'");
            $mBo->addFilter("jenis_transaksi='sub_wo'");
            $mBo->addFilter("status=1");
            $mBo->addFilter("trash=0");
            $mBo->addFilter("jenis in ('supplies')");
            $tmpWorkorderSupplies = $mBo->lookUpAll()->result();

            if(!empty($tmpWorkorderSupplies)){
                foreach($tmpWorkorderSupplies as $row){
                    $KomposisiWorkorder[$row->biaya_id."_".$row->biaya_dasar_id] = (array)$row;
                    $KomposisiWorkorder[$row->biaya_id."_".$row->biaya_dasar_id]['jml_return'] = 0;
                    $KomposisiWorkorder[$row->biaya_id."_".$row->biaya_dasar_id]['produk_dasar_id'] = $row->biaya_dasar_id*1;
                    $KomposisiWorkorder[$row->biaya_id."_".$row->biaya_dasar_id]['produk_dasar_nama'] = $row->biaya_dasar_nama;
                }
            }
            //supplies

            $arrProdukUsed = array();
            $arrProdukUsedRoom = array();
            //LIST USED
            $this->load->model("Mdls/MdlProjectSubTasklistKomposisi");
            $stlk = new MdlProjectSubTasklistKomposisi();
            $stlk->setFilters(array());
            $stlk->addFilter("fase_id='$fase_id'");
            $stlk->addFilter("sub_fase_id='$sub_fase_id'");
            $stlk->addFilter("produk_id='$produk_id'");
            $stlk->addFilter("no_spk='$no_spk'");
            $stlk->addFilter("status=1");
            $stlk->addFilter("trash=0");
            $stlk->addFilter("jenis in ('produk','item_komposit')");
            $komposisiSubKomTasklist = $stlk->lookUpAll()->result();
            $komposisiSubKomTasklistQuery = $this->db->last_query();
            $KomposisiWorkorderLine['LINE_'.__LINE__] = $komposisiSubKomTasklist;
            if (!empty($komposisiSubKomTasklist)) {
                foreach ($komposisiSubKomTasklist as $usedKomp) {
                    $arrProdukUsed[$usedKomp->no_sub][$usedKomp->produk_dasar_id] = (array)$usedKomp;
                    if (!isset($arrProdukUsedMain[$usedKomp->jenis.'_'.$usedKomp->produk_dasar_id]['jml'])) {
                        $arrProdukUsedMain[$usedKomp->jenis.'_'.$usedKomp->produk_dasar_id]['jml'] = 0;
                    }
                    $arrProdukUsedMain[$usedKomp->jenis.'_'.$usedKomp->produk_dasar_id]['jml'] += $usedKomp->jml;
                    if (!isset($arrProdukUsedMain[$usedKomp->jenis.'_'.$usedKomp->produk_dasar_id]['return'])) {
                        $arrProdukUsedMain[$usedKomp->jenis.'_'.$usedKomp->produk_dasar_id]['return'] = 0;
                    }
                    $arrProdukUsedMain[$usedKomp->jenis.'_'.$usedKomp->produk_dasar_id]['return'] += $usedKomp->jml_return;
                    if(isset($KomposisiWorkorder[$usedKomp->jenis.'_'.$usedKomp->produk_dasar_id])){
                        $KomposisiWorkorder[$usedKomp->jenis.'_'.$usedKomp->produk_dasar_id]['jml_return'] += $usedKomp->jml_return;
                    }
                }
            }

            $this->load->model("Mdls/MdlProjectSubTasklistKomposisi");
            $stlkSup = new MdlProjectSubTasklistKomposisi();
            $stlkSup->setFilters(array());
            $stlkSup->addFilter("fase_id='$fase_id'");
            $stlkSup->addFilter("sub_fase_id='$sub_fase_id'");
            $stlkSup->addFilter("produk_id='$produk_id'");
            $stlkSup->addFilter("no_spk='$no_spk'");
            $stlkSup->addFilter("status=1");
            $stlkSup->addFilter("trash=0");
            $stlkSup->addFilter("jenis='supplies'");
            $komposisiSubKomTasklistSup = $stlkSup->lookUpAll()->result();
            $komposisiSubKomTasklistQuery = $this->db->last_query();
            if (!empty($komposisiSubKomTasklistSup)) {
                foreach ($komposisiSubKomTasklistSup as $usedKomp) {
                    $arrProdukUsed[$usedKomp->no_sub][$usedKomp->biaya_dasar_id] = (array)$usedKomp;

                    if (!isset($arrProdukUsedMain[$usedKomp->biaya_id.'_'.$usedKomp->biaya_dasar_id]['jml'])) {
                        $arrProdukUsedMain[$usedKomp->biaya_id.'_'.$usedKomp->biaya_dasar_id]['jml'] = 0;
                    }

                    $arrProdukUsedMain[$usedKomp->biaya_id.'_'.$usedKomp->biaya_dasar_id]['jml'] += $usedKomp->jml;

                    if (!isset($arrProdukUsedMain[$usedKomp->biaya_id.'_'.$usedKomp->biaya_dasar_id]['return'])) {
                        $arrProdukUsedMain[$usedKomp->biaya_id.'_'.$usedKomp->biaya_dasar_id]['return'] = 0;
                    }
                    $arrProdukUsedMain[$usedKomp->biaya_id.'_'.$usedKomp->biaya_dasar_id]['return'] += $usedKomp->jml_return;

                    if(isset($KomposisiWorkorder[$usedKomp->biaya_id."_".$usedKomp->biaya_dasar_id])){
                        $KomposisiWorkorder[$usedKomp->biaya_id."_".$usedKomp->biaya_dasar_id]['jml_return'] += $usedKomp->jml_return;
                    }
                }
            }
        }

        //main subtasklist
        $this->load->model("Mdls/MdlSubProgresTasklist");
        $stl = new MdlSubProgresTasklist();
        $stl->setFilters(array());
        $stl->addFilter("fase_id='$fase_id'");
        $stl->addFilter("sub_fase_id='$sub_fase_id'");
        $stl->addFilter("no_spk='$no_spk'");
        $stl->addFilter("produk_id='$produk_id'");
        $stl->addFilter("status=1");
        $stl->addFilter("trash=0");
        $tempSubTaskList = $stl->lookUpAll()->result();
        $tempSubTaskListQuery = $this->db->last_query();

        //LIST ROOM JIKA MENGGUNAKAN MODE ROOM
        $this->load->model("Mdls/MdlProjectKomposisiWorkorderSub");
        $mWoR = new MdlProjectKomposisiWorkorderSub();
        $mWoR->setFilters(array());
        $mWoR->addFilter("produk_id='$produk_id'");
        $mWoR->addFilter("fase_id='$fase_id'");
        $mWoR->addFilter("status=1");
        $mWoR->addFilter("trash=0");
        $mWoR->addFilter("jenis='room'");
        $mWoR->addFilter("jenis_transaksi=5582"); //jenis_transaksi='5582'
        $this->db->where_in("sub_fase_id", $sub_fase_id);
        $this->db->order_by("jenis", "desc");
        $KomposisiWorkorderRoom = $mWoR->lookUpAll()->result();
        $quR = $this->db->last_query();

        //LIST KOMPOSISI PER ROOM JIKA MENGGUNAKAN MODE ROOM
        $this->load->model("Mdls/MdlProjectKomposisiWorkorder");
        $mWoRKom = new MdlProjectKomposisiWorkorder();
        $mWoRKom->setFilters(array());
        $mWoRKom->addFilter("produk_id='$produk_id'");
        $mWoRKom->addFilter("fase_id='$fase_id'");
        $mWoRKom->addFilter("status=1");
        $mWoRKom->addFilter("trash=0");
        $this->db->order_by("jenis", "desc");
        $KomposisiWorkorderRoomKomp = $mWoRKom->lookUpAll()->result();
        $quRKomp = $this->db->last_query();

        $komposisiRoom = array();
        if (!empty($KomposisiWorkorderRoomKomp)) {
            foreach ($KomposisiWorkorderRoomKomp as $k => $dataRoom) {
                switch ($dataRoom->jenis) {
                    case "room":
                        break;
                    case "produk_room":
                        $komposisiRoom[$dataRoom->room_id][] = $dataRoom;
                        break;
//                    case "biaya_room":
//                        $komposisiRoom[$dataRoom->room_id][] = $dataRoom;
//                        break;
                }
            }
        }

        $result = array(
            "nomer" => $nomer,
            "gudang_project" => $gudang_project,
            "no_spk" => $no_spk,
            "project_nama" => $task_nama,
            "task_nama" => $task_nama,
            "task_progress" => $task_progress,
            "id" => $id,
            "room" => $KomposisiWorkorderRoom,
            "komposisi_room" => $komposisiRoom,
            "list_bahan" => $KomposisiWorkorder,
            "arrProdukUsedMain" => $arrProdukUsedMain,
            "arrProdukUsedMainRoom" => $arrProdukUsedMainRoom,
            "komposisiSubKomTasklist" => $komposisiSubKomTasklist,
            "arrProdukUsed" => $arrProdukUsed,
            "arrProdukUsedRoom" => $arrProdukUsedRoom,
            "produk_id" => $produk_id,
            "tempTaskList" => $tempTaskList,
            "tempSubTaskList" => $tempSubTaskList,
            "arrMaterialDist" => $arrMaterialDist,
            "KomposisiWorkorderLine" => $KomposisiWorkorderLine,
            "tempTaskListQuery" => $tempTaskListQuery,

//            "tempSubTaskListQuery" => $tempSubTaskListQuery,
//            "komposisiSubKomTasklistQuery" => $komposisiSubKomTasklistQuery,
//            "arrProdukDistQuery" => $arrProdukDistQuery,
//            "arrSuppliesDistQuery" => $arrSuppliesDistQuery,
            "123_tmpKomposisiWorkorder" => $tmpKomposisiWorkorder,
            "123_komposisiSubKomTasklist" => $komposisiSubKomTasklist,
            "123_komposisiSubKomTasklistSup" => $komposisiSubKomTasklistSup,

        );

        echo json_encode($result);
    }

    public function exeSubTasklist()
    {
        $arrData = isset($_POST) ? $_POST : array();
        $id = $tasklist_id = $this->uri->segment(4); //tasklist id
        $produk_id = $this->uri->segment(5); //project_id
        $room_id = $this->uri->segment(6);

        $progress = array(
            "1" => "ongoing",
            "2" => "selesai",
        );

        $no_spk = $arrData['data_fase']['no_spk'];
        $progress_id = $arrData['data_fase']['progress_id'];
        $data_produk = $arrData['data_produk'];
        $json_img = isset($arrData['json_img']) ? $arrData['json_img'] : array();

        $return = 0;
        $return_list = array();
//        $revert_list = array();
        $arrPrdPrj = array();

        $arrSuppliesProject = array();
        $return_list_spl = array();
        $revert_list_spl = array();

        foreach ($data_produk as $k => $da) {
            if($da['produk_type']=='supplies'){
                $arrSuppliesProject["biaya_".$da['by_id']][$da['produk_dasar_id']] = $da;
                $arrSuppliesProject_R[$da['by_id']][$da['produk_dasar_id']] = $da;
                $return_list['supplies'] =  $arrSuppliesProject_R;
            }
            else{
                $arrPrdPrj["produk_" .$da['produk_dasar_id']] = $da;
                $return_list['produk'] =  $arrPrdPrj;
            }
        }

        $return = count($return_list)>0 || count($return_list_spl)>0 ? 1 : 0;

        //nama room
        $roomD = array();
        if ($room_id * 1 > 0) {
            $this->load->model("Mdls/MdlProjectKomposisiWorkorder");
            $rwo = new MdlProjectKomposisiWorkorder();
            $rwo->addFilter("room_id='$room_id'");
            $rwo->addFilter("jenis='room'");
            $tempRoom = $rwo->lookUpAll()->result();
            if (!empty($tempRoom)) {
                foreach ($tempRoom as $k => $room) {
                    $roomD[$room->room_id] = $room;
                }
            }
        }

        $commit = 0;
        $this->db->trans_start();

        $this->load->model("MdlTasklistProject");
        $tl = new MdlTasklistProject();
        $tl->addFilter("no_spk='$no_spk'");
        $tl->addFilter("progress_id='2'");
        $tempTaskList = $tl->lookUpAll()->result();
//        showLast_query("biru");
        $tempTaskListQuery = $this->db->last_query();

        if (!empty($tempTaskList)) {
            $task_id = $tempTaskList[0]->id;
            $produk_id = $tempTaskList[0]->produk_id;
            $fase_id = $tempTaskList[0]->fase_id;
            $sub_fase_id = $tempTaskList[0]->sub_fase_id;

            $this->load->model("Mdls/MdlSubProgresTasklist");
            $stl = new MdlSubProgresTasklist();

            $write = array();
            $writeLog = array();
            $sub_nomer = "";
            $progress_persen_sebelumnya = $tempTaskList[0]->progress_percent;
            //create sub_tasklist
            foreach ($tempTaskList as $ky => $task) {
                $link_id = $task->id;
                unset($task->id);
                $task->link_id = $link_id;
                $task->json_img = json_encode($json_img);
                $task->progress_id = $progress_id;

                $task->progress_nama = $progress[$progress_id];
                $task->oleh_id = $this->session->login['id'];
                $task->oleh_nama = $this->session->login['nama'];

                if ($progress_id == 2) {
                    $task->dtime_start = date("Y-m-d H:i:s");
                    $task->dtime_end = date("Y-m-d H:i:s");
                }
                else {
                    $task->dtime_start = date("Y-m-d H:i:s");
                    $task->dtime_end = null;
                }

                $forNum = $task;
                $task->no_sub = $this->numbering("SPK", "STL", (array)$forNum);
                $task->dtime = date("Y-m-d H:i:s");

                $sub_nomer = $task->no_sub;
                $subTaskListID = $stl->addData((array)$task) or die("INSERT ERROR :: CEK QUERY>><br>" . $this->db->last_query());
                cekMerah(__LINE__);
                showLast_query("hijau");
                $lastQuery = $this->db->last_query();
                $write[] = $lastQuery;
                $writeLog = (array)$task;
            }

            /*
             * ===========================================================================================================================
             */


            //region handler supplies
            $this->load->model("Mdls/MdlProjectKomponenBiayaDetailsRabSub");
            $mWoTask = new MdlProjectKomponenBiayaDetailsRabSub();

            $mWoTask->setFilters(array());
            $mWoTask->addFilter("fase_id='$fase_id'");
            $mWoTask->addFilter("sub_fase_id='$sub_fase_id'");
            $mWoTask->addFilter("project_id='$produk_id'");
            $mWoTask->addFilter("no_spk='$no_spk'");
            $mWoTask->addFilter("status=1");
            $mWoTask->addFilter("trash=0");
            $mWoTask->addFilter("jenis='supplies'");
            $KomposisiWorkorderTask = $mWoTask->lookUpAll()->result();
            //            showLast_query("biru");
            $KomposisiWorkorderTaskQuery = $this->db->last_query();


            //system bobot untuk biaya dan supplies
            $bobotBiayaSuppliesWo = array();
            foreach($KomposisiWorkorderTask as $rbot){
                $bobotBiayaSuppliesWo["biaya_".$rbot->biaya_id][$rbot->biaya_dasar_id] = $rbot;
            }

            $writeKomp = array();
            $total_anggaran = 0;
            $total_anggaran_supplies = 0;
//            $saldoPerSubTasklist = 0;
            if (!empty($KomposisiWorkorderTask)) {
                $this->load->model("Mdls/MdlProjectSubTasklistKomposisi");
                $stlkTask = new MdlProjectSubTasklistKomposisi();
                $queryWriteSubTaskKomposisiTask = array();
                $keyAliasing = array(
                    "project_id" => "produk_id",
                    "project_nama" => "produk_nama",
                    "jml_debet" => "qty_debet",
                    "jml_kredit" => "qty_kredit",
                    "jml_saldo" => "qty_saldo",
                );
                foreach ($KomposisiWorkorderTask as $k => $kwo) {
                    if ($kwo->jenis == "supplies") {
                        $biaya_id = $kwo->biaya_id;
                        $dasar_id = $kwo->biaya_dasar_id;

                        if (isset($arrSuppliesProject["biaya_".$biaya_id][$dasar_id])) {
                            $writeKomp[$biaya_id][$dasar_id] = $kwo;
                            foreach($keyAliasing as $rem => $knew){
                                $writeKomp[$biaya_id][$dasar_id]->$knew = $kwo->$rem;
                                unset($writeKomp[$biaya_id][$dasar_id]->$rem);
                            }
                            $harga = $kwo->harga;
                            $used_jml = isset($arrSuppliesProject["biaya_".$biaya_id][$dasar_id]['jml']) ? $arrSuppliesProject["biaya_".$biaya_id][$dasar_id]['jml']*1 : 0;
                            $return_jml = isset($arrSuppliesProject["biaya_".$biaya_id][$dasar_id]['jml_return']) ? $arrSuppliesProject["biaya_".$biaya_id][$dasar_id]['jml_return']*1 : 0;
                            $saldo = $harga * $used_jml;
                            $nilai_return = $harga * $return_jml;
                            $writeKomp[$biaya_id][$dasar_id]->jml = $used_jml;
                            $writeKomp[$biaya_id][$dasar_id]->jml_return = $return_jml;
                            $writeKomp[$biaya_id][$dasar_id]->link_id = $subTaskListID;
                            $writeKomp[$biaya_id][$dasar_id]->no_sub = $sub_nomer;
                            $writeKomp[$biaya_id][$dasar_id]->saldo = $saldo;
                            $writeKomp[$biaya_id][$dasar_id]->nilai_return = $nilai_return;
                            $writeKomp[$biaya_id][$dasar_id]->dtime = date("Y-m-d H:i:s");
                            $writeKomp[$biaya_id][$dasar_id]->author_nama = $this->session->login["nama"];
                            $writeKomp[$biaya_id][$dasar_id]->progress_id = $progress_id; //1=tunda || 2=selesai
                            $writeKomp[$biaya_id][$dasar_id]->progress_nama = $progress[$progress_id];
                            $writeKomp[$biaya_id][$dasar_id]->employee_id = isset($arrSuppliesProject["biaya_".$biaya_id][$dasar_id]['employee_id']) ? $arrSuppliesProject["biaya_".$biaya_id][$dasar_id]['employee_id']*1 : 0;
                            $writeKomp[$biaya_id][$dasar_id]->owner_nama = isset($arrSuppliesProject["biaya_".$biaya_id][$dasar_id]['owner_nama']) ? $arrSuppliesProject["biaya_".$biaya_id][$dasar_id]['owner_nama']*1 : "-";

                            unset($writeKomp[$biaya_id][$dasar_id]->id);
                            unset($writeKomp[$biaya_id][$dasar_id]->last_update);
//                            $saldoPerSubTasklist += $saldo * 1;

                            $stlkTask->addData((array)$writeKomp[$biaya_id][$dasar_id]) or die($this->db->last_query());
//                            arrPrint($writeKomp[$biaya_id][$dasar_id]);
                            cekMerah(__LINE__);
                            showLast_query("hijau");
                            $queryWriteSubTaskKomposisiTask[] = $this->db->last_query();
                        }
                    }
                }
            }
            //endregion handler supplies

//            echo json_encode($queryWriteSubTaskKomposisiTask);
//            die(__LINE__);

            /*
             * ===========================================================================================================================
             */

            //region handler produk
            $this->load->model("Mdls/MdlProjectKomposisiWorkorderSub");
            $mWo = new MdlProjectKomposisiWorkorderSub();

            $mWo->setFilters(array());
            $mWo->addFilter("fase_id='$fase_id'");
            $mWo->addFilter("sub_fase_id='$sub_fase_id'");
            $mWo->addFilter("produk_id='$produk_id'");
            $mWo->addFilter("no_spk='$no_spk'");
            $mWo->addFilter("status=1");
            $mWo->addFilter("trash=0");
//            $mWo->addFilter("jenis='produk'");

            $KomposisiWorkorder = $mWo->lookUpAll()->result();
            $oriKomposisiWorkorder = json_encode($KomposisiWorkorder);

//            arrPrint($KomposisiWorkorder);
//            showLast_query("biru");

            $KomposisiWorkorderQuery = $this->db->last_query();

            $writeKomp = array();
            $total_anggaran_produk_biaya = 0;
//            $saldoPerSubTasklist = 0;

            //total dari workorder
            $this->load->model("Mdls/MdlProjectKomposisiWorkorderSub");
            $mWoSub = new MdlProjectKomposisiWorkorderSub();
            $mWoSub->setFilters(array());
            $mWoSub->addFilter("fase_id='$fase_id'");
            $mWoSub->addFilter("produk_id='$produk_id'");
            $mWoSub->addFilter("no_spk='$no_spk'");
            $mWoSub->addFilter("status=1");
            $mWoSub->addFilter("trash=0");
            $KomposisiWorkorderSub = $mWoSub->lookUpAll()->result();
            cekMerah("WORKORDER<BR>". $this->db->last_query());

            $total = 0;
            $biayaSub = 0;
            $arrbiaya = array();
            $arrMainWorkOrderSub = array();
            $jual = 0;
            foreach ($KomposisiWorkorderSub as $sum) {
                if ($sum->jenis == "produk") {
                    $total += $sum->saldo * 1;
                }
                if ($sum->jenis == "biaya") {
                    $biayaSub += $sum->saldo * 1;
                }
            }

            $laba = $jual - ($total + $biayaSub);
//            $saldoPerSubTasklist = $total + $biayaSub;
            $bobotProdukUnitWo = array();
            if (!empty($KomposisiWorkorder)) {
                $this->load->model("Mdls/MdlProjectSubTasklistKomposisi");
                $stlk = new MdlProjectSubTasklistKomposisi();
                $queryWriteSubTaskKomposisi = array();
                foreach ($KomposisiWorkorder as $k => $kwo) {
                    if ($kwo->jenis == "produk") {
                        $dasar_id = $kwo->produk_dasar_id;
                        $bobotProdukUnitWo[$dasar_id] = $kwo;
                        $total_anggaran_produk_biaya += $kwo->saldo * 1;
                        if (isset($arrPrdPrj["produk_" .$dasar_id])) {
                            $writeKomp[$dasar_id] = $kwo;
                            $harga = $kwo->harga;
                            $used_jml = isset($arrPrdPrj["produk_" .$dasar_id]['jml']) ? $arrPrdPrj["produk_" .$dasar_id]['jml']*1 : 0;
                            $return_jml = isset($arrPrdPrj["produk_" .$dasar_id]['jml_return']) ? $arrPrdPrj["produk_" .$dasar_id]['jml_return']*1 : 0;
                            $saldo = $harga * $used_jml;
                            $nilai_return = $harga * $return_jml;
                            $writeKomp[$dasar_id]->jml = $used_jml;
                            $writeKomp[$dasar_id]->jml_return = $return_jml;
                            $writeKomp[$dasar_id]->link_id = $subTaskListID;
                            $writeKomp[$dasar_id]->no_sub = $sub_nomer;
                            $writeKomp[$dasar_id]->saldo = $saldo;
                            $writeKomp[$dasar_id]->nilai_return = $nilai_return;
                            $writeKomp[$dasar_id]->dtime = date("Y-m-d H:i:s");
                            $writeKomp[$dasar_id]->author_nama = $this->session->login["nama"];
                            $writeKomp[$dasar_id]->progress_id = $progress_id; //1=tunda || 2=selesai
                            $writeKomp[$dasar_id]->progress_nama = $progress[$progress_id];

                            unset($writeKomp[$dasar_id]->id);
                            unset($writeKomp[$dasar_id]->last_update);
                            $stlk->addData((array)$writeKomp[$dasar_id]) or die($this->db->last_query());
                            cekMerah(__LINE__);
                            showLast_query("hijau");
                            $queryWriteSubTaskKomposisi[] = $this->db->last_query();
                        }

                    }
                    else if($kwo->jenis == "biaya"){
                        $dasar_id = $kwo->produk_dasar_id;
                        $idKwo = $kwo->id;
                        if ($kwo->qty_saldo > 0) {
                            $total_anggaran_produk_biaya += $kwo->saldo * 1;
                            $writeKomp[$dasar_id] = $kwo;
                            $harga = $kwo->harga;
                            $used_jml = $kwo->qty_saldo;
                            $saldo = $harga * $used_jml;
                            $nilai_return = 0;
                            $writeKomp[$dasar_id]->jml = $used_jml;
                            $writeKomp[$dasar_id]->jml_return = $return_jml;
                            $writeKomp[$dasar_id]->link_id = $subTaskListID;
                            $writeKomp[$dasar_id]->no_sub = $sub_nomer;
                            $writeKomp[$dasar_id]->saldo = $saldo;
                            $writeKomp[$dasar_id]->nilai_return = $nilai_return;
                            $writeKomp[$dasar_id]->dtime = date("Y-m-d H:i:s");
                            $writeKomp[$dasar_id]->author_nama = $this->session->login["nama"];
                            $writeKomp[$dasar_id]->progress_id = 1; //1=tunda || 2=selesai
                            $writeKomp[$dasar_id]->progress_nama = $progress[1];

                            unset($writeKomp[$dasar_id]->id);
                            unset($writeKomp[$dasar_id]->last_update);
                            $stlk->addData((array)$writeKomp[$dasar_id]) or die($this->db->last_query());
                            $kwoUpdate = array(
                                "qty_saldo" => 0,
                                "qty_debet" => 0,
                                "qty_kredit" => $used_jml,
                            );
                            $mWo->setFilters(array());
                            $mWo->updateData(array("id" => $idKwo), $kwoUpdate) or matiHere("gagal memperbaharui data produk sub WO LINE: " . __LINE__);
                        }
                    }
                    else{}
                }
            }
            //endregion handler produk
            $total_anggaran = $total_anggaran_produk_biaya + $total_anggaran_supplies;
//            cekHitam("total_anggaran: $total_anggaran");

//            arrPrintWebs($KomposisiWorkorder);
            /*
             * ===========================================================================================================================
             * */

            //region membalikan stok pada sub work order
            //MdlProjectKomposisiWorkorderSub
            $this->load->model("Mdls/" . "MdlProjectKomposisiWorkorderSub");
            $kompSubWO = new MdlProjectKomposisiWorkorderSub();
            $kompSubWO->setFilters(array());
            $kompSubWO->addFilter("status='1'");
            $kompSubWO->addFilter("trash='0'");
            $kompSubWO->addFilter("produk_id='$produk_id'");
            $kompSubWO->addFilter("jenis_transaksi='5582'");
            $tmpKompSubOrder = $kompSubWO->lookUpAll()->result();

            $arrUpdKomposisiSubWO = array();
            if (!empty($tmpKompSubOrder)) {
                foreach ($tmpKompSubOrder as $kr => $rWo) {
                    $dasar_id = $rWo->produk_dasar_id;
                    $id = $rWo->id;
                    if (isset($arrPrdPrj["produk_" .$dasar_id])) {
                        $used_jml = isset($arrPrdPrj["produk_" .$dasar_id]['jml']) ? $arrPrdPrj["produk_" .$dasar_id]['jml']*1 : 0;
                        $return_jml = isset($arrPrdPrj["produk_" .$dasar_id]['jml_return']) ? $arrPrdPrj["produk_" .$dasar_id]['jml_return']*1 : 0;
                        $harga = $rWo->harga*1;

                        $arrUpdKomposisiSubWO[$id]["qty_kredit"] = $rWo->qty_kredit - $return_jml;
                        $arrUpdKomposisiSubWO[$id]["qty_saldo"] = $rWo->qty_saldo + $return_jml;
                        $arrUpdKomposisiSubWO[$id]["kredit"] = ($rWo->qty_kredit - $return_jml) * $harga;
                        $arrUpdKomposisiSubWO[$id]["saldo"] = ($rWo->qty_saldo + $return_jml) * $harga;

                        $kompSubWO->setFilters(array());
                        $kompSubWO->updateData(array("id" => $id), $arrUpdKomposisiSubWO[$id]) or matiHere("gagal memperbaharui data produk sub WO LINE: " . __LINE__);
                        cekMerah(__LINE__);
                        showLast_query("kuning");
                    }

                }
            }
            //endregion membalikan stok pada sub work order

            /*
             * ===========================================================================================================================
             * */

            //region update rab sub
            $this->load->model("Mdls/" . "MdlProjectKomponenBiayaDetailsRabSub");
            $kompSubWO_spl = new MdlProjectKomponenBiayaDetailsRabSub();
            $kompSubWO_spl->setFilters(array());
            $kompSubWO_spl->addFilter("fase_id='$fase_id'");
            $kompSubWO_spl->addFilter("sub_fase_id='$sub_fase_id'");
            $kompSubWO_spl->addFilter("project_id='$produk_id'");
            $kompSubWO_spl->addFilter("no_spk='$no_spk'");
            $kompSubWO_spl->addFilter("status=1");
            $kompSubWO_spl->addFilter("trash=0");
            $kompSubWO_spl->addFilter("jenis='supplies'");

            $tmpKompSubOrder_spl = $kompSubWO_spl->lookUpAll()->result();

            $arrUpdKomposisiSubWO = array();
            if (!empty($tmpKompSubOrder_spl)) {
                foreach ($tmpKompSubOrder_spl as $kr => $rWo) {
                    $dasar_id = $rWo->biaya_dasar_id;
                    $biaya_id = $rWo->biaya_id;
                    $id = $rWo->id;
                    if (isset($arrSuppliesProject["biaya_".$biaya_id][$dasar_id])) {
                        $used_jml = isset($arrSuppliesProject["biaya_".$biaya_id][$dasar_id]['jml']) ? $arrSuppliesProject["biaya_".$biaya_id][$dasar_id]['jml']*1 : 0;
                        $return_jml = isset($arrSuppliesProject["biaya_".$biaya_id][$dasar_id]['jml_return']) ? $arrSuppliesProject["biaya_".$biaya_id][$dasar_id]['jml_return']*1 : 0;
                        $harga = $rWo->harga*1;

                        $arrUpdKomposisiSubWO[$id]["jml_kredit"] = $rWo->jml_kredit + $return_jml + $used_jml;
                        $arrUpdKomposisiSubWO[$id]["jml_saldo"]  = $rWo->jml_saldo - $return_jml - $used_jml;
                        $arrUpdKomposisiSubWO[$id]["kredit"]     = ($rWo->jml_kredit + $return_jml + $used_jml) * $harga;
                        $arrUpdKomposisiSubWO[$id]["saldo"]      = ($rWo->jml_saldo - $return_jml - $used_jml) * $harga;

                        $kompSubWO_spl->setFilters(array());
                        $kompSubWO_spl->updateData(array("id" => $id), $arrUpdKomposisiSubWO[$id]) or matiHere("gagal memperbaharui data produk sub WO LINE: " . __LINE__);
                        cekMerah(__LINE__);
                        showLast_query("kuning");
                    }
                }
            }
            //endregion update rab sub

            //bobot biaya besar
            $bobotBiayaBesar = array();
            foreach($KomposisiWorkorder as $rByaBsr){
                $bobotBiayaBesar[$rByaBsr->jenis . "_" . $rByaBsr->produk_dasar_id] = $rByaBsr;
            }

            $oriWorkOrderSub = json_decode($oriKomposisiWorkorder,1);

            if(!empty($oriWorkOrderSub)){
                foreach($oriWorkOrderSub as $k => $oriWork){
                    $bobotBiayaBesar[$oriWork['jenis'] . "_" . $oriWork['produk_dasar_id']] = (object)$oriWork;
                    }
                }

            $totalWoProject = 0;
            $totalProgressWo = 0;
            $arrTotalProgressWo = array();
            $perbobotan = array();
            foreach($bobotBiayaBesar as $bbid => $biy){
                $perbobotan[$bbid]['saldo'] = $biy->jml * $biy->harga;
                $totalBiayaBesar = $biy->jml * $biy->harga;
                $totalWoProject+= $totalBiayaBesar;

                //jika isset disini berarti biaya memiliki supplies
                if( isset($bobotBiayaSuppliesWo[$bbid]) ){
                    $totalSuppliesBiaya = 0;
                    foreach($bobotBiayaSuppliesWo[$bbid] as $bsid => $biyk){
                        $totalSuppliesBiaya+= $biyk->saldo;
                    }
                    $perbobotan[$bbid]['saldo_supplies'] = $totalSuppliesBiaya;
                }
                if( isset($arrSuppliesProject[$bbid]) ){
                    //region dalam progress
                    $totalSaldoBobotSupp = 0;
                    foreach($arrSuppliesProject[$bbid] as $bsid => $biyk){
                        $suppID     = $bsid;
                        $nilaiReturn = $bobotBiayaSuppliesWo[$bbid][$suppID]->nilai_return;
                        $bobotSupBiy = ($bobotBiayaSuppliesWo[$bbid][$suppID]->saldo+$nilaiReturn) / $totalSuppliesBiaya;
                        $saldoBobotSupp = $totalBiayaBesar * $bobotSupBiy;
                        $satuanBobotSupp = $saldoBobotSupp / $bobotBiayaSuppliesWo[$bbid][$suppID]->jml;
                        $jmlProgg   = $biyk['jml'];
                        $jmlProggReturn   = $biyk['jml_return'];
                        $hrgBobot   = $jmlProgg * $satuanBobotSupp;
                        $hrgBobotReturn   = $jmlProggReturn * $satuanBobotSupp;
//                        $hrgBobotReturn   = 0;
                        $totalProgressBiy = $hrgBobot + $hrgBobotReturn;
                        $perbobotan[$bbid]['supplies'][$bsid] = $biyk;
                        $perbobotan[$bbid]['supplies'][$bsid]['bobotSupBiy']        = $bobotSupBiy;
                        $perbobotan[$bbid]['supplies'][$bsid]['saldoBobotSupp']     = $saldoBobotSupp;
                        $perbobotan[$bbid]['supplies'][$bsid]['satuanBobotSupp']    = $satuanBobotSupp;
                        $totalSaldoBobotSupp+= $totalProgressBiy;
                    }
                    $perbobotan[$bbid]['total_saldo_bobot_supp'] = $totalSaldoBobotSupp;
                    $perbobotan[$bbid]['bobot_persen'] = ($totalSaldoBobotSupp/$totalBiayaBesar)*100;
                    $totalProgressWo+= $totalSaldoBobotSupp;
                    $arrTotalProgressWo[$bbid] = $totalSaldoBobotSupp;
                    //endregion dalam progress
                }
                else{
                    //jika tidak ada relasi ke supplies,
                    //region dalam progress
                    if(isset($arrPrdPrj[$bbid])){
                        $totalSaldoBobotSupp = 0;
                        foreach($arrPrdPrj[$bbid] as $kk => $kVal){
                            $perbobotan[$bbid][$kk] = $kVal;
                            $perbobotan[$bbid]['progress_harga_'] = $biy->harga;
                            $perbobotan[$bbid]['progress_jml'] = $arrPrdPrj[$bbid]['jml'];
                            $perbobotan[$bbid]['progress_jml_return'] = $arrPrdPrj[$bbid]['jml_return'];
                            $perbobotan[$bbid]['progress_subtotal'] = $arrPrdPrj[$bbid]['jml']*$biy->harga;
                            $perbobotan[$bbid]['progress_subtotal_return'] = $arrPrdPrj[$bbid]['jml_return']*$biy->harga;
                            $saldoProgress = $arrPrdPrj[$bbid]['jml']*$biy->harga;
                            $saldoProgressReturn = $arrPrdPrj[$bbid]['jml_return']*$biy->harga;
                            $totalSaldoBobotSupp = $biy->harga*1 == $arrPrdPrj[$bbid]['saldo']*1 ? $saldoProgress : $saldoProgressReturn + $saldoProgress;
                        }
                        $perbobotan[$bbid]['total_saldo_bobot_supp'] = $totalSaldoBobotSupp;
                        $perbobotan[$bbid]['bobot_persen'] = ($totalSaldoBobotSupp/$totalBiayaBesar)*100;
                        $totalProgressWo+= $totalSaldoBobotSupp;
                        $arrTotalProgressWo[$bbid] = $totalSaldoBobotSupp;
                    }
                    else{
                        $perbobotan[$bbid] = $biy;
                        if(isset($bobotBiayaSuppliesWo[$bbid])){
                            $arrTotalProgressWo[$bbid] = 0;
                        }
                        else{
                            if($biy->jenis!="produk"){
                                if($biy->qty_saldo*1 > 0){
                                    $bbiyHrg = $biy->harga * $biy->qty_saldo;
                                    $totalProgressWo+= $bbiyHrg;
                                    $arrTotalProgressWo[$bbid] = $bbiyHrg;
                                }
                            }
                        }
                    }
                    //endregion dalam progress
                }
            }

            $saldoPerSubTasklist = $totalProgressWo;
            $perbobotan['total_wo'] = $totalWoProject;
            $perbobotan['total_wo_progress'] = $totalProgressWo;
            $perbobotan['persen_wo_progress'] = ($totalProgressWo/$totalWoProject)*100;
            $perbobotan['total_anggaran'] = $total_anggaran;
            //kalkulasi untuk menentukan progress di tasklist utama
            // belum termasuk biaya upah / biaya upah masuk saat QC
            // belum termasuk biaya overhead

            $this->load->model("Mdls/MdlProjectSubTasklistKomposisi");
            $stlk = new MdlProjectSubTasklistKomposisi();
            $stlk->setFilters(array());
            $stlk->addFilter("fase_id='$fase_id'");
            $stlk->addFilter("sub_fase_id='$sub_fase_id'");
            $stlk->addFilter("produk_id='$produk_id'");
            $stlk->addFilter("no_spk='$no_spk'");
            $stlk->addFilter("status=1");
            $stlk->addFilter("trash=0");
            $stlk->addFilter("progress_id='2'");
            $sumSubTaskListKomp = $stlk->lookUpAll()->result();
            $sumSubTaskListKompQuery = $this->db->last_query();

            $total_produk   = 0;
            $biayaSubTask   = 0;
            $total_supplies = 0;
            $qty_return     = 0;
            $nilai_return   = 0;

            foreach ($sumSubTaskListKomp as $sum) {
                $qty_return += $sum->jml_return * 1;
                $nilai_return += $sum->nilai_return * 1;
                if ($sum->jenis == "produk") {
                    $total_produk += $sum->saldo * 1;
                }
                if ($sum->jenis == "biaya") {
                    $biayaSubTask += $sum->saldo * 1;
                }
                if ($sum->jenis == "supplies") {
                    $total_supplies += $sum->saldo * 1;
                }
            }

            $progress_percent = ceil(( ($total_produk+$total_supplies+$nilai_return) / ($total_anggaran)) * 100);
            $progress_percent = $perbobotan['persen_wo_progress'];
//            $progress_percent_per_sub = ceil(($saldoPerSubTasklist+$nilai_return) / ($total_anggaran) * 100);

            cekHitam(__LINE__);
            cekHijau("total_anggaran: $total_anggaran");
            cekHijau("total_produk: $total_produk");
            cekHijau("total_supplies: $total_supplies");
            cekHijau("nilai_return: $nilai_return");

            $update = array(
                "progress_percent" => ($progress_persen_sebelumnya + $progress_percent)*1>100 ? 100 : ($progress_persen_sebelumnya+$progress_percent),
                "distribute_percent" => $progress_percent*1>100 ? ($progress_percent*1)-100 : 0,
            );
            $where = array(
                "no_spk" => $no_spk,
                "progress_id" => 2,
            );

            //update tasklist
            $tl->setFilters(array());
            $tl->updateData($where, $update) or matiHere("gagal memperbaharui data LINE: " . __LINE__);
            cekMerah(__LINE__);
            showLast_query("kuning");

            //update tasklist komposisi
            $stl->setFilters(array());
            $stl->updateData(array("id" => $subTaskListID), array("progress_percent" => 100)) or matiHere("gagal memperbaharui data LINE: " . __LINE__);
            cekMerah(__LINE__);
            showLast_query("kuning");

            $this->load->model("Mdls/MdlProdukProject");
            $prdprj = new MdlProdukProject();
            $prdprj->addFilter("id='$produk_id'");
            $prevProdukProject = $prdprj->lookUpAll()->result();
            cekMerah(__LINE__);
            showLast_query("biru");
            if (!empty($prevProdukProject)) {
                $nilai_project = $prevProdukProject[0]->harga;
                $prevHrgProgg = $prevProdukProject[0]->harga_progress;
                $totalProgress = $prevHrgProgg + $totalProgressWo;
                $update = array(
                    "harga_progress" => $totalProgress,
                    "persen_progress" => $totalProgress/$nilai_project*100,
                );
                $where = array(
                    "id" => $produk_id,
                );
                $prdprj->setFilters(array());
                $prdprj->updateData($where, $update) or matiHere("gagal memperbaharui data produk project LINE: " . __LINE__);
                cekMerah(__LINE__);
                cekHijau("nilai_project: $nilai_project");
                cekHijau("prevHrgProgg: $prevHrgProgg");
                cekHijau("totalProgress: $totalProgress");
                cekHijau("saldoPerSubTasklist: $saldoPerSubTasklist");
                showLast_query("kuning");
            }

            //write log untuk timeline
            $this->load->model("Mdls/MdlTasklistProjectLog");
            $mLog = new MdlTasklistProjectLog();
            $logData = $writeLog;
            $logData['type']             = "subtasklist";
            $logData['person_id']        = $this->session->login["id"];
            $logData['person_nama']      = $this->session->login["nama"];
            $logData['oleh_id']          = $this->session->login["id"];
            $logData['oleh_nama']        = $this->session->login["nama"];
            $logData['progress_percent'] = $progress_percent;
            $logData['dtime']            = date("Y-m-d H:i:s");

            $mLog->addData($logData) or die("WRITE LOG ERROR - <br>" . $this->db->last_query());
            cekMerah(__LINE__);
            showLast_query("hijau");
            $qLog   = $this->db->last_query();

            $this->load->model("Mdls/MdlReturnProjectLog");
            $mrLog = new MdlReturnProjectLog();
            $rlogData = array();
            $rlogData['jenis']           = "return_log";
            $rlogData['project_id']      = $produk_id;
            $rlogData['tasklist_id']     = $tasklist_id;
            $rlogData['spk']             = $no_spk;
            $rlogData['oleh_id']         = $this->session->login["id"];
            $rlogData['oleh_nama']       = $this->session->login["nama"];
            $rlogData['content']         = base64_encode(serialize(json_encode($return_list)));
            $rlogData['dtime']           = date("Y-m-d H:i:s");

            $mrLog->addData($rlogData) or die("WRITE RETURN LOG ERROR - <br>" . $this->db->last_query());
            cekMerah(__LINE__);
            showLast_query("hijau");

//            matiHere("BELUM == COMMIT || LINE: " . __LINE__);
            $commit = $this->db->trans_complete();
        }

//        $commit = 1;
//        $progress_percent = ceil( ( $total / $total_anggaran ) * 100 );
//        $progress_percent_per_sub = ceil( ( $total / $saldoPerSubTasklist ) * 100 );
        $ipadd = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "";
        $rk = array(
            "tasklist_id" => $tasklist_id,
            "project_id" => $produk_id,
            "no_spk" => $no_spk,
            "fase_id" => $fase_id,
            "sub_fase_id" => $sub_fase_id,
        );
        $result = array(
            "return" => $return,
            "return_list" => $return_list,
            "data" => $rk,
            "commit" => $commit,
            "ipadd" => $ipadd,
            "data_produk" => $data_produk,
            "sumSubTaskListKompQuery" => $sumSubTaskListKompQuery,
            "total_anggaran" => $total_anggaran,
            "total" => $total,
            "arrTotalProgressWo" => $arrTotalProgressWo,
//            "progress_percent_per_sub" => $progress_percent_per_sub,

//            "return_list_spl" => $return_list_spl,
//            "revert_list" => $revert_list,
//            "revert_list_spl" => $revert_list_spl,
//            "progress_nya" => ceil( ($total/$total_anggaran)*100),
//            "progress_nya_pertask" => ceil( ( $saldoPerSubTasklist / $total_anggaran ) * 100 ),
//            "writeKomp" => $writeKomp,
//            "last_query" => $this->db->last_query(),
//            "sumSubTaskListKomp" => $sumSubTaskListKomp,
//            "rumus_total_anggaran" => "$total_anggaran_produk_biaya + $total_anggaran_supplies",
//            "biayaSubTask" => $biayaSubTask,
//            "biayaSub" => $biayaSub,
//            "KomposisiWorkorderSub" => $KomposisiWorkorderSub,
            "KomposisiWorkorder" => $KomposisiWorkorder,
//            "oriKomposisiWorkorder" => json_decode($oriKomposisiWorkorder,1),
//            "rumus" => "ceil(($saldoPerSubTasklist+$nilai_return) / ($total_anggaran) * 100)",
//            "saldoPerSub" => $saldoPerSubTasklist,
//            "KomposisiWorkorderTask" => $KomposisiWorkorderTask,
            "bobotBiayaBesar" => $bobotBiayaBesar,
//            "arrOriWorkOrderSub" => $arrOriWorkOrderSub,
            "bobotBiayaSuppliesWo" => $bobotBiayaSuppliesWo,
            "perbobotan" => $perbobotan,
            "data_produk" => $data_produk,
            "arrPrdPrj" => $arrPrdPrj,
//            "arrSuppliesProject" => $arrSuppliesProject,
            "oriWorkOrderSub" => $oriWorkOrderSub,
        );
        echo json_encode($result);
    }

    public function exeSubTasklistQc()
    {
        $id = $this->uri->segment(5);
        $produk_id = $this->uri->segment(4);

        $commit = 0;
        $writeLog = array();
        $this->db->trans_start();

        $this->load->model("Mdls/MdlSubProgresTasklist");
        $stl = new MdlSubProgresTasklist();
        $stl->addFilter("link_id='$id'");
        $stl->addFilter("produk_id='$produk_id'");
        $tempSubTaskList = $stl->lookUpAll()->result();

        $this->load->model("Mdls/MdlProjectSubTasklistKomposisi");
        $stlk = new MdlProjectSubTasklistKomposisi();

        $this->load->model("MdlTasklistProject");
        $tl = new MdlTasklistProject();
        $tl->addFilter("id='$id'");
        $tl->addFilter("produk_id='$produk_id'");
        $tl->addFilter("progress_id='2'");
        $tempTaskList = $tl->lookUpAll()->result();
        $tempTaskListQuery = $this->db->last_query();

        if (!empty($tempTaskList)) {
            $task_id        = $tempTaskList[0]->id;
            $produk_id      = $tempTaskList[0]->produk_id;
            $produk_nama    = $tempTaskList[0]->produk_nama;
            $fase_id        = $tempTaskList[0]->fase_id;
            $sub_fase_id    = $tempTaskList[0]->sub_fase_id;
            $nomer_task     = $tempTaskList[0]->nomer;
            $no_spk         = $tempTaskList[0]->no_spk;
            $nama_task      = $tempTaskList[0]->nama;
            $no_kontrak     = $tempTaskList[0]->no_kontrak;
            $nilai_kontrak  = $tempTaskList[0]->nilai_kontrak;

            //total dari workorder
            $this->load->model("Mdls/MdlProjectKomposisiWorkorder");
            $mWo = new MdlProjectKomposisiWorkorder();
            $mWo->setFilters(array());
            $mWo->addFilter("fase_id='$fase_id'");
            $mWo->addFilter("produk_id='$produk_id'");
            $mWo->addFilter("status=1");
            $mWo->addFilter("trash=0");
            $KomposisiWorkorder = $mWo->lookupAll()->result();
            cekMerah("WORKORDER<BR>". $this->db->last_query());

            //sub per paket
            $this->load->model("Mdls/MdlProjectKomposisiWorkorderSub");
            $mWoSUB = new MdlProjectKomposisiWorkorderSub();
            $mWoSUB->setFilters(array());
            $mWoSUB->addFilter("fase_id='$fase_id'");
            $mWoSUB->addFilter("produk_id='$produk_id'");
            $mWoSUB->addFilter("no_spk='$no_spk'");
            $mWoSUB->addFilter("status=1");
            $mWoSUB->addFilter("trash=0");
            $KomposisiWorkorderSub = $mWoSUB->lookupAll()->result();
            cekMerah("SUB WORKORDER<BR>". $this->db->last_query());
            $KomposisiWorkorderSubQuery = $this->db->last_query();

            $arrBiayaSubWO = array();
            foreach($KomposisiWorkorderSub as $ky => $sub_wo){
                $arrBiayaSubWO[$sub_wo->produk_dasar_id] =  (array)$sub_wo;
            }

            $total = 0;
            $biaya = 0;
            $arrbiaya = array();
            $jual = 0;
            foreach ($KomposisiWorkorder as $sum) {
                if ($sum->jenis == "produk") {
                    $total += $sum->saldo * 1;
                }
                if ($sum->jenis == "biaya") {
                    $biaya += $sum->saldo * 1;
                    $arrbiaya[] = $sum;
                }
                if ($sum->jenis == "jual") {
                    $jual += $sum->harga * 1;
                }
            }

            $laba = $jual - ($total + $biaya);
            $saldoPerSubTasklist = $biaya;

            $update = array(
                "progress_id" => 3,
                "progress_nama" => "Quality Control Selesai",
                "qc_auth_id" => $this->session->login["id"],
                "qc_auth_nama" => $this->session->login["nama"],
                "qc_dtime" => date("Y-m-d H:i:s"),
            );
            $where = array(
                "nomer" => $nomer_task,
                "progress_id" => 2,
            );

            $tl->setFilters(array());
            $tl->updateData($where, $update) or matiHere("gagal memperbaharui data LINE: " . __LINE__);
            showLast_query('kuning');

            $qc_regist_no = $this->numbering("QC", "QC", (array)$tempTaskList[0]);

            //catat biaya di project_sub_tasklist_komposisi
            if(!empty($arrbiaya)){
                $link_id = $tempSubTaskList[0]->id;
                $no_sub = $tempSubTaskList[0]->no_sub;
                foreach($arrbiaya as $wrData){
                    if(isset($arrBiayaSubWO[$wrData->produk_dasar_id])){
                        $forWriteBiaya = (array)$wrData;

                        $forWriteBiaya['jml'] = $arrBiayaSubWO[$wrData->produk_dasar_id]['jml'];
                        $forWriteBiaya['debet'] = $arrBiayaSubWO[$wrData->produk_dasar_id]['debet'];
                        $forWriteBiaya['saldo'] = $arrBiayaSubWO[$wrData->produk_dasar_id]['saldo'];
                        $forWriteBiaya['qty_debet'] = $arrBiayaSubWO[$wrData->produk_dasar_id]['qty_debet'];
                        $forWriteBiaya['qty_saldo'] = $arrBiayaSubWO[$wrData->produk_dasar_id]['qty_saldo'];
                        $forWriteBiaya['no_spk'] = $arrBiayaSubWO[$wrData->produk_dasar_id]['no_spk'];
                        $forWriteBiaya['employee_id'] = $arrBiayaSubWO[$wrData->produk_dasar_id]['employee_id'];
                        $forWriteBiaya['produk_paket_id'] = $arrBiayaSubWO[$wrData->produk_dasar_id]['produk_paket_id'];
                        $forWriteBiaya['produk_paket_nama'] = $arrBiayaSubWO[$wrData->produk_dasar_id]['produk_paket_nama'];
                        $forWriteBiaya['jml_return'] = $arrBiayaSubWO[$wrData->produk_dasar_id]['jml_return'];
                        $forWriteBiaya['sub_fase_nama'] = $arrBiayaSubWO[$wrData->produk_dasar_id]['sub_fase_nama'];
                        $forWriteBiaya['sub_fase_id'] = $arrBiayaSubWO[$wrData->produk_dasar_id]['sub_fase_id'];
                        $forWriteBiaya['keterangan'] = $arrBiayaSubWO[$wrData->produk_dasar_id]['keterangan'];
                        $forWriteBiaya['owner_nama'] = $arrBiayaSubWO[$wrData->produk_dasar_id]['owner_nama'];
                        $forWriteBiaya['qc_nomer'] = $qc_regist_no;
                        $forWriteBiaya['jenis_transaksi'] = "sub_wo";

                        $forWriteBiaya['progress_id'] = 3;
                        $forWriteBiaya['progress_nama'] = "Quality Control";
                        $forWriteBiaya['link_id'] = $link_id;
                        $forWriteBiaya['no_sub'] = $no_sub;
                        $forWriteBiaya['qc_auth_id'] = $this->session->login["id"];
                        $forWriteBiaya['qc_auth_nama'] = $this->session->login["nama"];
                        $forWriteBiaya['qc_dtime'] = date("Y-m-d H:i:s");

                        //replace_hpp
                        $forWriteBiaya['hrg_hpp'] = $forWriteBiaya['nilai'];
                        $forWriteBiaya['hrg_ppv'] = $forWriteBiaya['nilai'];
                        $forWriteBiaya['hrg_jual'] = $forWriteBiaya['nilai'];
                        $forWriteBiaya['author'] = $this->session->login["id"];
                        $forWriteBiaya['author_nama'] = $this->session->login["nama"];
                        $forWriteBiaya['dtime'] = date("Y-m-d H:i:s");

                        unset($forWriteBiaya['id']);

                        cekHere(__LINE__);
                        arrPrintWebs($forWriteBiaya);
                        $stlk->addData($forWriteBiaya) or die("ERROR WRITE: MdlProjectSubTasklistKomposisi <br>" . $this->db->last_query());
                        showLast_query('biru');
                    }
                }
            }

//            $this->load->model("Mdls/MdlProdukProject");
//            $prdprj = new MdlProdukProject();
//            $prdprj->addFilter("id='$produk_id'");
//            $prevProdukProject = $prdprj->lookUpAll()->result();
//
//            if (!empty($prevProdukProject)) {
//                $nilai_project = $prevProdukProject[0]->harga;
//                $prevHrgProgg = $prevProdukProject[0]->harga_progress;
//                $totalProgress = $prevHrgProgg + $saldoPerSubTasklist;
//                $update = array(
//                    "harga_progress" => $totalProgress,
//                    "persen_progress" => $totalProgress/$nilai_project*100,
//                );
//                $where = array(
//                    "id" => $produk_id,
//                );
//                $prdprj->setFilters(array());
//                $prdprj->updateData($where, $update) or matiHere("gagal memperbaharui data produk project LINE: " . __LINE__);
//                showLast_query('kuning');
//            }

            $ipadd = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "";

            //write log untuk timeline
            $this->load->model("Mdls/MdlTasklistProjectLog");
            $mLog = new MdlTasklistProjectLog();

            $logData = $writeLog = array(
                "nama" => $nama_task,
                "nomer" => $nomer_task,
                "no_spk" => $no_spk,
                "date_process" => date("Y-m-d H:i:s"),
                "no_kontrak" => $no_kontrak,
                "nilai_sub_fase" => $saldoPerSubTasklist,
                "nilai_kontrak" => $nilai_kontrak,
                "persen_sub" => $saldoPerSubTasklist / $nilai_kontrak * 100,
                "link_id" => $task_id,
                "fase_id" => $fase_id,
                "produk_id" => $produk_id,
                "produk_nama" => $produk_nama,
                "employee_id" => $tempTaskList[0]->employee_id,
                "employee_nama" => $tempTaskList[0]->employee_nama,
            );

            $logData['type'] = "QC PROSES";
            $logData['person_id'] = $this->session->login["id"];
            $logData['person_nama'] = $this->session->login["nama"];
            $logData['oleh_id'] = $this->session->login["id"];
            $logData['oleh_nama'] = $this->session->login["nama"];
            $logData['dtime'] = date("Y-m-d H:i:s");
            $logData['progress_id'] = 3;
            $logData['progress_nama'] = "Quality Control Selesai";
            $mLog->addData($logData) or die($this->db->last_query());
            showLast_query('biru');

            $qLog = $this->db->last_query();

//            matiHere("====SAMPAI BAWAH MATI DULU====");
            $commit = $this->db->trans_complete();

        }

        if($commit){
            //=== CONFIG
            $jenistr = "3674";
            $modulTarget = "biaya";
            $controllerTarget = "AutoPostingBiaya";
            $method = "post";
            //=== CONNECT API
            $apiConnect = array(
                "jenistr" => $jenistr,
                "no_spk" => $no_spk,
                "debuger" => 0,
            );
            $urlConnect = base_url() . "$modulTarget/$controllerTarget/index/$jenistr";
            $curl = New Curl();
            $api = $curl->_simple_call($method, $urlConnect, $apiConnect);
            $return = json_decode($api, true);

            $result = array(
                "commit" => $commit,
                "api" => $api,
                "api_return" => $return,
                "tempTaskList" => count($tempTaskList),
                "urlConnect" => $urlConnect,
                "ipadd" => $ipadd,
                "biaya" => $biaya,
                "arrbiaya" => $arrbiaya,
                "produk" => $total,
                "jual" => $jual,
                "laba" => $laba,
                "totalProgress" => $totalProgress,
            );

        }
        else{
            $result = array(
                "commit" => $commit,
                "api_return" => $return,
                "tempTaskList" => count($tempTaskList),
                "urlConnect" => $urlConnect,
                "reason" => "gagal commit",
                "ipadd" => $ipadd,
                "biaya" => $biaya,
                "arrbiaya" => $arrbiaya,
                "produk" => $total,
                "jual" => $jual,
                "laba" => $laba,
                "totalProgress" => $totalProgress,
            );
        }

        echo json_encode($result);
    }

    public function followupSubTasklist()
    {
        $id = $this->uri->segment(4);
        $produk_id = $this->uri->segment(5);
        $arrData = isset($_POST) ? $_POST : array();
        $result = array(
            "id" => $id,
            "produk_id" => $produk_id,
            "arrData" => $arrData,
        );
        echo json_encode($result);
    }

    public function uploadPhoto()
    {
        $id = $this->uri->segment(4);
        $produk_id = $this->uri->segment(5);

        $files = $_FILES['file'];
        $uploaded = upload_image($files);
        $result = array(
            "files" => $files,
            "uploaded" => $uploaded,
            "id" => $id,
            "produk_id" => $produk_id,
        );
        echo json_encode($result);
    }

    public function debuger()
    {
        echo "<pre>";
        print_r($_SESSION);
        echo "</pre>";
    }

    public function editBom()
    {
        $id = $this->uri->segment(4);
        $produk_id = $this->uri->segment(5);

        $this->load->model("Mdls/" . "MdlProdukProject");
        $o = new MdlProdukProject();
        $o->addFilter("id='$id'");
        $tempProdukMaster = $o->lookUpAll()->result();

        $this->db->trans_start();

        if (count($tempProdukMaster) == 1) {
            $update = array(
                "lock" => 0,
                "harga" => 0,
            );
            $where = array(
                "id" => $id,
            );
            $o->setFilters(array());
            $updated = $o->updateData($where, $update) or matiHere("gagal memperbaharui data LINE: " . __LINE__);

            $this->db->trans_complete();

            $link = MODUL_PATH . "MasterData/index/" . $id;
            echo "<script>
                top.window.location.href = '$link';
            </script>";
        }

    }

    public function saveBom()
    {
        $id = $this->uri->segment(4);
        $harga_project = $this->uri->segment(5);

        $this->load->model("Mdls/" . "MdlProdukProject");
        $o = new MdlProdukProject();
        $o->addFilter("id='$id'");
        $tempProdukMaster = $o->lookUpAll()->result();

        $this->db->trans_start();

        if (count($tempProdukMaster) == 1) {
            $update = array(
                "lock" => 1,
                "lock_id" => $this->session->login["id"],
                "lock_nama" => $this->session->login["nama"],
                "lock_dtime" => date("Y-m-d H:i:s"),
                "harga" => $harga_project * 1,
                "tarif_ppn" => $this->session->login["ppnFactor"],
                "ppn" => ($harga_project*1) * ($this->session->login["ppnFactor"]/100),
                "harga_nppn" => $harga_project*1 + (($harga_project*1) * ($this->session->login["ppnFactor"]/100)),
                "start_dtime" => date("Y-m-d H:i:s"),
                "end_dtime" => date("Y-m-d H:i:s"),
            );
            $where = array(
                "id" => $id,
            );
            $o->setFilters(array());
            $updated = $o->updateData($where, $update) or matiHere("gagal memperbaharui data LINE: " . __LINE__);

            $this->db->trans_complete();

            $link = MODUL_PATH . "MasterData/index/" . $id;
            echo "<script>
                top.window.location.href = '$link';
            </script>";
        }

    }

    public function exeEditable()
    {
        $mdl = $_POST['name'];
        $tmpPK = $_POST['pk'];
        $arrPK = explode('-', $tmpPK);
        $produk_id = $arrPK[0];
        $fase_id = $arrPK[1];
        $sub_fase_id = $arrPK[2];
        $this->load->model("Mdls/" . "$mdl");
        $this->load->model("Mdls/" . "MdlTasklistProject");
        $o = new $mdl();
        $ts = new MdlTasklistProject();
        $o->setFilters(array());
        $o->addFilter("produk_id='$produk_id'");
        $o->addFilter("fase_id='$fase_id'");
        $o->addFilter("sub_fase_id='$sub_fase_id'");
        $$mdl = $o->lookUpAll()->result();
        if (!empty($$mdl)) {

            //================== project_komposisi_sub_workorder
            $update = array(
                "sub_fase_nama" => trim($_POST['value'])
            );
            $where = array(
                "produk_id" => $produk_id,
                "fase_id" => $fase_id,
                "sub_fase_id" => $sub_fase_id,
            );
            $o->updateData($where, $update) or die('error update');

            //================== tasklist_project

            $update = array(
                "nama" => trim($_POST['value'])
            );
            $where = array(
                "produk_id" => $produk_id,
                "fase_id" => $fase_id,
                "sub_fase_id" => $sub_fase_id,
            );
            $ts->updateData($where, $update) or die('error update');

        }
        echo json_encode($$mdl);
    }

    public function generatorPaymentSource($id_project=0){

        $targetJenis =7499;

//        $this->load->model("MdlTransaksi");
//        $tr = new MdlTransaksi();
//        $tr->setFilters(array());
//        $tr->addFilter("target_jenis=7499");
//        $tr->addFilter("extern_id='$externID'");
//        $tr->addFilter("sisa>0");
//        $tr->addFilter("transaksi_id='$selectedTrID'");
//        $tmpSrc = $tr->lookupPaymentSrcByJenis_joined($targetJenis)->result();

        $this->db->trans_start();

        $this->db->where("project_start=1");
        $this->db->where("status=1");
        $this->db->where("trash=0");
        $produk_project = $this->db->get("project_produk")->result();

        if(!empty($produk_project)){
            foreach($produk_project as $k => $pdata){
                $prj_id = $pdata->id;
                $prj_nama = $pdata->nama;
                $nomer = $pdata->quot_nomer;
                $trID = $pdata->quot_id;
                $extern_id = $pdata->customer_id;
                $extern_nama = $pdata->customer_nama;
                $tagihan = $pdata->harga_nppn;
                $oleh_id = $pdata->lock_id;
                $oleh_nama = $pdata->lock_nama;
                $dtime = $pdata->quot_appr_dtime;

                $this->db->where("target_jenis=$targetJenis");
                $this->db->where("transaksi_id=$trID");
                $this->db->where("nomer='$nomer'");
                $tmpSrc = $this->db->get("transaksi_payment_source")->result();

                if(empty($tmpSrc)){
                    //buat payment source
                    $insert = array(
                        "jenis" => "588so",
                        "target_jenis" => "7499",
                        "reference_jenis" => "588so",
                        "transaksi_id" => $trID,
                        "extern_id" => $extern_id,
                        "extern_nama" => $extern_nama,
                        "nomer" => $nomer,
                        "label"=> "piutang dagang",
                        "tagihan" => $tagihan,
                        "sisa" => $tagihan,
                        "cabang_id" => "1",
                        "cabang_nama" => "CABANG 1",
                        "oleh_id" => $oleh_id,
                        "oleh_nama" => $oleh_nama,
                        "dtime" => $dtime,
                        "fulldate" => date("Y-m-d", strtotime($dtime)),
                        "project_id" => $prj_id,
                        "project_nama" => $prj_nama,
                    );

                    $this->db->insert("transaksi_payment_source", $insert);
                    showLast_query("merah");
                    arrPrintWebs($insert);
                }
            }
        }

//        matiHere("mati dulu");
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

//        $result = array(
//            "data" => "test",
//            "id_project" => "$id_project",
////            "tmpSrc" => $tmpSrc,
//        );
//        return $result;
    }

    public function generateNotifTermin(){


    }

    //membuatkan biaya detail saat pembuatan RAB
    public function makeDetailsBiaya(){

        $project_id = isset($_GET['project_id']) ? $_GET['project_id']*1 : matiHere("project id harap ditentukan");
        $fase_id    = isset($_GET['fase_id'])    ? $_GET['fase_id']*1    : matiHere("fase id harap ditentukan");
        $biaya_id   = isset($_GET['biaya_id'])   ? $_GET['biaya_id']*1   : matiHere("biaya id harap ditentukan");
        $jml        = isset($_GET['jml'])        ? $_GET['jml']*1        : matiHere("jml biaya jasa harap ditentukan");
        $link_id    = isset($_GET['link_id'])    ? $_GET['link_id']*1    : matiHere("link_id biaya jasa harap ditentukan");

        $commit=0;
        $this->db->trans_start();

        $this->load->model("Mdls/" . "MdlProdukProject");
        $pr = new MdlProdukProject();
        $pr->addFilter("id='$project_id'");
        $pr->addFilter("gen_tasklist='0'");
        $tmp = $pr->lookUpAll()->result();

        if(!empty($tmp)){
            $dTask = (array)$tmp[0];
        }

        //details biaya jasa
        $this->load->model("Mdls/" . "MdlDtaBiayaProduksi");
        $byProd = new MdlDtaBiayaProduksi();
        $byProd->addFilter("id='$biaya_id'");
        $arrByProduksi = $byProd->lookUpAll()->result();

        //details biaya jasa
        $this->load->model("Mdls/" . "MdlProjectKomponenBiayaDetails");
        $by = new MdlProjectKomponenBiayaDetails();
        $by->addFilter("biaya_id='$biaya_id'");
        $arrByDetails = $by->lookUpAll()->result();

        if(!empty($arrByDetails)){
            $byDataCreate=array();
            $this->load->model("Mdls/" . "MdlProjectKomponenBiayaDetailsRab");
            $byCr = new MdlProjectKomponenBiayaDetailsRab();
            foreach($arrByDetails as $byData){
                $byDataCreate = (array)$byData;
                $jml_dasar = $byDataCreate["jml"];
                $byDataCreate["project_id"] = $project_id; //project ID
                $byDataCreate["project_nama"] = $dTask["nama"]; //project Nama
                $byDataCreate["fase_id"] = $fase_id; //fase id
                $byDataCreate["link_id"] = $link_id;
                $byDataCreate["jml"] = $jml_dasar * $jml; //jml
                $byDataCreate["jml_dasar"] = $jml_dasar; //jml_bom_satuan
                $byDataCreate["jenis_transaksi"] = "main";
                $byDataCreate["dtime"] = date("Y-m-d H:i:s");
                $byDataCreate["last_update"] =  date("Y-m-d H:i:s");
                $byDataCreate["author"] = $this->session->login['id'];
                unset($byDataCreate["id"]);
                $ins = $byCr->addData($byDataCreate) or matiHere("masterPID: $masterPID <br>gagal menambahkan data*" . " | " . $this->db->last_query());
            }

            $commit = $this->db->trans_complete();

            if($commit){
                $result = array(
                    "status" => 1
                );
                echo json_encode($result);
            }
            else{
                $result = array(
                    "status" => 0,
                    "reason" => "gagal save details biaya",
                );
                echo json_encode($result);
            }
        }
        else{

            $mdlName = "MdlDtaBiayaProduksi";
            $dataAccess = isset($this->config->item('heDataBehaviour')[$mdlName]) ? $this->config->item('heDataBehaviour')[$mdlName] : array(
                "viewers"       => array(),
                "creators"      => array(),
                "creatorAdmins" => array(),
                "updaters"      => array(),
                "updaterAdmins" => array(),
                "deleters"      => array(),
                "deleterAdmins" => array(),
            );
            //                $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
            $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
            $allowView = false;
            $allowCreate = false;
            $allowEdit = false;
            $allowDelete = false;
            foreach ($mems as $mID) {
                if (in_array($mID, $dataAccess['viewers'])) {
                    $allowView = true;
                }
                if (in_array($mID, $dataAccess['creators'])) {
                    $allowCreate = true;
                }
                if (in_array($mID, $dataAccess['updaters'])) {
                    $allowEdit = true;
                }
                if (in_array($mID, $dataAccess['deleters'])) {
                    $allowDelete = true;
                }
            }

            if($allowCreate){
                $btnAct = "<a class='text-bold text-link' onclick=\"settingDetailBiaya()\">disini</a>";
            }
            else{
                $btnAct = "<span class='text-red text-bold'>Anda bisa meminta atasan Anda untuk menambahkan Details Biaya.</span>";
            }

            $nama_biaya_main = $arrByProduksi[0]->nama;
            $result = array(
                "status" => 0,
                "reason" => "biaya <b>$nama_biaya_main</b> tidak memiliki details, silahkan di setting dulu <br> $btnAct",
            );
            echo json_encode($result);
        }

        $this->generateDetailsBiayaRab($project_id);

    }

    //jika komponen biaya ada perubahan, maka biaya pada project bisa direnew atau menggunakan apa adanya
    public function renewDetailsBiaya(){

        $project_id = isset($_GET['project_id']) ? $_GET['project_id'] : matiHere("project id harap ditentukan");
        $fase_id    = isset($_GET['fase_id']) ? $_GET['fase_id'] : matiHere("fase id harap ditentukan");
        $biaya_id   = isset($_GET['biaya_id']) ? $_GET['biaya_id'] : matiHere("biaya id harap ditentukan");

        $this->db->trans_start();

        $this->load->model("Mdls/" . "MdlProdukProject");
        $pr = new MdlProdukProject();
        $pr->addFilter("id='$project_id'");
        $pr->addFilter("gen_tasklist='0'");
        $tmp = $pr->lookUpAll()->result();

        showLast_query("merah");

        if(!empty($tmp)){
            $dTask = (array)$tmp[0];
        }

        arrPrint($dTask);

        //details biaya jasa
        $this->load->model("Mdls/" . "MdlProjectKomponenBiayaDetails");
        $by = new MdlProjectKomponenBiayaDetails();
        $by->addFilter("biaya_id='$biaya_id'");
        $arrByDetails = $by->lookUpAll()->result();

        if(!empty($arrByDetails)){
            $this->load->model("Mdls/" . "MdlProjectKomponenBiayaDetailsRab");
            $byCrCek = new MdlProjectKomponenBiayaDetailsRab();

            $whereNextCurent = array(
                "project_id" => $project_id,
                "fase_id" => $fase_id,
                "biaya_id" => $biaya_id,
            );

            $trashNextCurentUpdate = array(
                "status" => "0",
                "trash" => "1",
            );

            $byCrCek->setFilters(array());
            $byCrCek->updateData($whereNextCurent, $trashNextCurentUpdate) or matiHere("gagl update details biaya");

            $byDataCreate=array();
            $this->load->model("Mdls/" . "MdlProjectKomponenBiayaDetailsRab");
            $byCr = new MdlProjectKomponenBiayaDetailsRab();
            foreach($arrByDetails as $byData){
                $byDataCreate = (array)$byData;
                $jml = $byDataCreate["jml"];
                $byDataCreate["project_id"] = $project_id; //project ID
                $byDataCreate["project_nama"] = $dTask["nama"]; //project Nama
                $byDataCreate["fase_id"] = $fase_id; //fase id
                $byDataCreate["link_id"] = $insertID;
                $byDataCreate["jml"] = $_SESSION["NEW"][$key][$masterFase][$masterPID]["jml"] * $jml; //jml
                $byDataCreate["jml_dasar"] = $jml; //jml_bom_satuan
                $byDataCreate["jenis_transaksi"] = "main";
                unset($byDataCreate["id"]);
                $ins = $byCr->addData($byDataCreate) or matiHere("masterPID: $masterPID <br>gagal menambahkan data*" . " | " . $this->db->last_query());
                showLast_query("biru");
                arrPrintWebs($byDataCreate);
            }
        }
    }

    public function generateDetailsBiayaRab($project_id=""){

        $project_id = isset($_GET['project_id']) ? $_GET['project_id'] : $project_id;

        $this->db->trans_start();
        $this->load->model("Mdls/" . "MdlProjectKomponenBiayaDetailsRab"); //register detail biaya per project
        $byCr = new MdlProjectKomponenBiayaDetailsRab();
        $tmpByData = $byCr->lookUpAll()->result();
        $arrRabBiaya=array();
        if(!empty($tmpByData)){
            foreach($tmpByData as $by){
                $arrRabBiaya[$by->project_id][$by->fase_id][$by->biaya_id][$by->biaya_dasar_id] = (array)$by;
            }
        }

        //details biaya jasa
        $this->load->model("Mdls/" . "MdlDtaBiayaProduksi"); // list biaya utama
        $byProd = new MdlDtaBiayaProduksi();
        $tmpByProduksi = $byProd->lookUpAll()->result();
        $arrByProduksi=array();

        //details biaya jasa
        $this->load->model("Mdls/" . "MdlProjectKomponenBiayaDetails"); //seting global details biaya
        $by = new MdlProjectKomponenBiayaDetails();
        $tmpByDetails = $by->lookUpAll()->result();
        $arrByDetails=array();

        //details biaya jasa
        $this->load->model("Mdls/" . "MdlProjectKomponenBiayaDetailsRabSub"); //seting global details biaya
        $byCrSub = new MdlProjectKomponenBiayaDetailsRabSub();
        $byCrSub->addFilter("project_id='$project_id'");
        $tmpByDetailsRabSub = $byCrSub->lookUpAll()->result();
        $arrByDetailsRabSub=array();
        if(!empty($tmpByDetailsRabSub)){
            foreach($tmpByDetailsRabSub as $row){

                $arrByDetailsRabSub[$row->project_id][$row->no_spk][$row->biaya_id][$row->biaya_dasar_id] = $row;
            }
        }

        /*
         * ==========================================================================
         * =============-----------------E X E C U T O R----------------=============
         * ==========================================================================
         */
        $this->load->model('Mdls/MdlProjectKomposisiWorkorderSub');
        $k = new MdlProjectKomposisiWorkorderSub();
        $k->addFilter("produk_id='$project_id'");
        $k->addFilter("jenis='biaya'");
        $k->addFilter("jenis_transaksi='sub_wo'");
        $temp = $k->lookUpAll()->result();

        $arrGen = array();
        if(!empty($temp)){
            foreach($temp as $row){
                $arrGen[$row->no_spk][$row->id] = $row;
            }
        }

        $writer = array();
        if(!empty($arrGen)){
            foreach($arrGen as $no_spk => $dBiaya){
//                echo "<br><b>##" . $no_spk . "</b><br>";
                foreach($dBiaya as $gid => $cBiaya){
//                    echo "<pre>";
//                    print_r($cBiaya);
//                    echo "</pre>";

//                    echo "<br>==" . $gid . "";
//                    echo "- " . $cBiaya->produk_dasar_nama . "<br>";
                    //cek dari biaya details RAB
                    if(isset($arrRabBiaya[$project_id][$cBiaya->fase_id][$cBiaya->produk_dasar_id])){
                        if(isset($arrByDetailsRabSub[$project_id][$no_spk][$cBiaya->produk_dasar_id])){
                            if(isset($arrByDetailsRabSub[$project_id][$no_spk][$cBiaya->produk_dasar_id])){
                                foreach($arrByDetailsRabSub[$project_id][$no_spk][$cBiaya->produk_dasar_id] as $row2){
//                                    echo "---- " . $row2->biaya_dasar_nama . "<br>";
                                }
                            }
                        }
                        else{
//                            echo "<r>- BELUM ADA BIAYA DETAILS di RAB SUB</r> <br>";
//                            echo "<r>AKAN WRITE RAB SUB dari list RAB<br>";


                            $byWrSub = new MdlProjectKomponenBiayaDetailsRabSub();
                            foreach($arrRabBiaya[$project_id][$cBiaya->fase_id][$cBiaya->produk_dasar_id] as $rows){
                                $rows["no_spk"] = $no_spk;
                                $rows["link_id"] = $gid;
                                $rows["sub_fase_id"] = $cBiaya->sub_fase_id;
                                $rows["sub_fase_nama"] = $cBiaya->sub_fase_nama;
                                $rows["jenis_transaksi"] = "sub_wo";
                                $rows["jml"] = $rows['jml_dasar']*$cBiaya->jml;
                                $rows["debet"] = $rows['harga']*$rows['jml'];
                                $rows["kredit"] = 0;
                                $rows["saldo"] = $rows['harga']*$rows['jml'];
                                $rows["jml_debet"] = $rows["jml"];
                                $rows["jml_kredit"] = 0;
                                $rows["jml_saldo"] = $rows["jml"];
                                $rows["dtime"] = date("Y-m-d H:i:s");
                                $rows["last_update"] = date("Y-m-d H:i:s");
                                unset($rows["id"]);
                                $byWrSub->addData($rows) or matiHere("gagal menambahkan data ditail. Silahkan ulangi beberapa saat lagi.<br>" . $this->db->last_query());
                                $writer[$cBiaya->produk_dasar_id][$rows['biaya_dasar_id']] = $rows;
                            }
                        }
                    }
                    else{
//                        echo "<r>- BELUM ADA BIAYA DI RAB</r> <br>";
                    }
                }
            }
        }

//        arrPrint($writer);
//        matiHere("====MATI DULU====<BR>=========== BELUM COMIT =============");
        $commit = $this->db->trans_complete();

    }

    public function testApiCallback(){

        $jenistr = "3674";
        $modulTarget = "biaya";
        $controllerTarget = "AutoPostingBiaya";
        $method = "post";
        $no_spk = "007/SPK-INT/962/007/III/2024";
        //=== CONNECT API
        $apiConnect = array(
            "jenistr" => $jenistr,
            "no_spk" => $no_spk,
            "debuger" => 0,
        );
        $urlConnect = base_url() . "$modulTarget/$controllerTarget/index/$jenistr";
        $curl = New Curl();
        $api = $curl->_simple_call($method, $urlConnect, $apiConnect);

        $return = json_decode($api, true);

        arrPrint( $return["status"] );
//        echo json_encode($curl->debug());
//        echo json_encode($curl->debug());
    }

    public function calcSubWorker($no_spk=0){

        $no_spk = $no_spk!=0 ? $no_spk : ($_GET["no_spk"] ? $_GET["no_spk"] : 0);

        $this->load->model("Mdls/MdlProjectKomposisiWorkorderSub");
        $mWo = new MdlProjectKomposisiWorkorderSub();
        $mWo->setFilters(array());
        $mWo->addFilter("no_spk='$no_spk'");
        $mWo->addFilter("status=1");
        $mWo->addFilter("trash=0");
        $KomposisiWorkorder = $mWo->lookUpAll()->result();

        arrPrint($KomposisiWorkorder);

    }

    //RESET PENGERJAAN TASKLIST
    public function resetPengerjaan($spk=''){

        $no_spk = isset($_GET['spk']) ? $_GET['spk'] : ($spk!='' ? $spk : matiHere("nomor SPK belum di define, silahkan define dengan GET spk=xxx."));

        $this->load->model("MdlTasklistProject");
        $tl = new MdlTasklistProject();
        $tl->addFilter("no_spk='$no_spk'");
        $tempTaskList = $tl->lookUpAll()->result();

        //delete no_spk project_sub_tasklist
        //update no_spk project_tasklist => progress_id = 2, progress_nama=tertunda, progress_percent=0, qc_ dll = null
        //delete no_spk project_sub_tasklist_komposisi
        //update project_produk persen_progress = persen_progress - persentase tasklist sekarang

        if(!empty($tempTaskList)){
            foreach($tempTaskList as $k => $row){

                if( $row->progress_percent == 100 && $row->progress_id == 3){
                    // ini membalikan QC juga

                }
                else{
                    if( $row->progress_percent == 0 ){
                        matiHere("TASKLIST DENGAN SPK: <b>$no_spk</b> INI BELUM PERNAH DIKERJAKAN");
                    }
                    else{

                        //lanjut
                    }
                }

            }
        }

    }

    public function bridgeProject(){

//        $pid = $prodID = $this->uri->segment(5);

        $this->load->model("Mdls/MdlCabangProduksi");
        $this->load->model("Mdls/MdlProdukProject");
        $this->load->model("Mdls/MdlProjectWorkOrder");
        $this->load->model("Mdls/MdlProdukRakitanPreBiaya");
        $this->load->model("Mdls/MdlProjectKomposisi");
        $this->load->model("Mdls/MdlProjectKomposisiWorkorder");
        $this->load->model("Mdls/MdlProjectKomposisiWorkorderSub");
        $this->load->model("Mdls/MdlHargaProduk");
        $this->load->model("Mdls/MdlDtaBiayaProduksi");
        $this->load->model("Mdls/MdlSatuan");
        $this->load->model("Mdls/MdlTimWorkProject");
        $this->load->model("Mdls/MdlEmployee_all");
        $this->load->model("Mdls/MdlProdukKomposisiPaket");
        $this->load->model("Mdls/MdlProjectProdukPaket");
        $this->load->model("Mdls/MdlLockerStock");
        $this->load->model("Mdls/MdlLockerStockSupplies");
        $this->load->model("MdlTasklistProject");

        $o   = new MdlProdukProject();
        $pk  = new MdlProjectKomposisi();
        $ob  = new MdlProdukRakitanPreBiaya();
        $kf  = new MdlProjectKomposisiWorkorder();
        $kfs = new MdlProjectKomposisiWorkorderSub();
        $pf  = new MdlProjectWorkOrder();
        $bp  = new MdlDtaBiayaProduksi();
        $st  = new MdlSatuan();
        $t   = new MdlTimWorkProject();
        $e   = new MdlEmployee_all();
        $pkg = new MdlProdukKomposisiPaket();
        $pp  = new MdlProjectProdukPaket();
        $tl  = new MdlTasklistProject();
        $ls  = new MdlLockerStock();
        $lss  = new MdlLockerStockSupplies();

        $this->db->select('pt.*, pp.*'); // Pilih kolom dari kedua tabel (sesuaikan dengan kebutuhan)
        $this->db->from('project_tasklist pt'); // Tabel tasklist dengan alias pt
        $this->db->join('project_produk pp', 'pt.produk_id = pp.id'); // Join dengan produk_project berdasarkan produk_id
        $this->db->where('pp.status', 1); // Hanya ambil project yang masih aktif
        $this->db->where('pp.trash', 0); // Hanya ambil project yang masih aktif
        $this->db->where('pp.lock', 1); // Kondisi lock harus 1
        $this->db->where('pp.project_start', 1); // Kondisi project_start harus 1
        $this->db->where('pp.gen_tasklist', 0); // Kondisi gen_tasklist harus 0

//        $this->db->where('pt.produk_id', $prodID); // Filter berdasarkan produk_id

        $this->db->order_by('pt.id', 'desc'); // Urutkan berdasarkan ID tasklist
        $this->db->limit(1); // limit
        $tempTaskList = $this->db->get()->result();
//        showlast_Query("biru");
//        arrPrint($tempTaskList);

        if(!$tempTaskList){
            cekHijau("trx master belum sinkron sudah abis");
        }
        else{
            $prodID = $tempTaskList[0]->id;
            //MdlProdukProject
            $o->addFilter("id='$prodID'");
            $this->db->order_by("id", "desc");
            $tempProdukProject = $o->lookUpAll()->result();
//            showlast_Query("biru");

            //MdlProjectKomposisi
            $pk->addFilter("produk_id='$prodID'");
            $this->db->order_by("id", "desc");
            $tempProdukKomposisi = $pk->lookUpAll()->result();
//            showlast_Query("biru");

            if(empty($tempTaskList)){
                cekHijau("PROJECT BELUM ADA TASKLIST");
            }
            else{
                //LIST no-transaksi
                $arrMon = array(
                    "lock",
                    "nama",
                    "cabang_id",
                    "lock",
                    "lock_id",
                    "lock_nama",
                    "lock_dtime",
                    "quot_nomer",
                    "quot_desc",
                    "quot_status",
                    "quot_id",
                    "quot_appr_id",
                    "quot_appr_nama",
                    "quot_appr_dtime",
                    "project_start_nomer",
                    "project_start",
                    "project_start_dtime",
                    "project_start_id",
                    "project_start_name",
                    "dtime",
                    "transaksi_id",
                    "transaksi_no",
                    "nomor_kontrak",
                    "tanggal_kontrak",
                    "customer_id",
                    "customer_nama",
                    "closing_status",
                    "spek",
                    "start_dtime",
                    "end_dtime",
                    "harga",
                    "tarif_ppn",
                    "ppn",
                    "harga_nppn",
                    "uang_muka_request",
                    "uang_muka_approved",
                    "project_started_id",
                    "project_started_name",
                    "project_started_dtime",
                    "project_started_desc",
                );
                $arrTransaksi = array();
                foreach($tempProdukProject as $k => $pprd){
                    foreach($pprd as $ky => $kkVal){
                        if(in_array($ky, $arrMon)){
                            if($ky=="transaksi_id"){
                                $ky = "transaksi_spo_id";
                            }
                            if($ky=="transaksi_no"){
                                $ky = "transaksi_spo_no";
                            }
                            $arrTransaksi[$ky] = $kkVal;
                        }
                    }
                }
                //LIST GUDANG dari TASKLIST
                $arrGudang = array();
                foreach($tempTaskList as $k => $task){
                    $no_spk = $task->no_spk;
                    $produk_id = $task->produk_id;
                    $fase_id = $task->fase_id;
                    $sub_fase_id = $task->sub_fase_id;
                    $sub_fase_id = $task->sub_fase_id;
                    $ambil_angka_depan_spk = explode("/", $no_spk)[0];
                    $gudang_project = "$produk_id" . "$fase_id" . "$sub_fase_id" . "$ambil_angka_depan_spk";
                    $arrGudang[$gudang_project] = array(
                        "gudang_id" => $gudang_project,
                        "nama_project" => $task->nama,
                        "task_nama" => $task->produk_nama,
                        "employee_id" => $task->employee_id,
                        "employee_nama" => $task->employee_nama,
                        "owner_nama" => $task->owner_nama,
                    );
                }
                $arrProdukUtama = array();
                $arrProdukSupplies = array();

                //lockerStock
                if(!empty($arrGudang)){

                    $listGudang = implode(",", array_map(
                        function ($v, $k) { return sprintf("%s", $k); },$arrGudang,array_keys($arrGudang)
                    ));

                    //PRODUK UTAMA
                    $this->db->where_in("gudang_id",$listGudang);
                    $this->db->where_in("state", array("active","hold"));
                    $this->db->where("jumlah>",0);
                    $this->db->order_by("id", "desc");
                    $tempLockerStock = $ls->lookUpAll()->result();
                    if(!empty($tempLockerStock)){
                        foreach($tempLockerStock as $row){
                            $prev_id = $row->id;
                            unset($row->id);
                            $row->locker_id = $prev_id;
                            $rowArray = (array)$row+$arrTransaksi;
                            $this->db->insert('project_bridge_produk', $rowArray);
                            showlast_Query("biru");
                            // Jika ingin mendapatkan ID yang diinsert
                            if(!$this->db->insert_id()){
                                echo "GAGAL INSERT PRODUK";
                            }
                        }
                    }

                    //SUPPLIES
                    $this->db->where_in("gudang_id",$listGudang);
                    $this->db->where_in("state", array("active","hold"));
                    $this->db->where("jumlah>",0);
                    $this->db->order_by("id", "desc");
                    $tempLockerStockSupplies = $lss->lookUpAll()->result();
                    if(!empty($tempLockerStockSupplies)){
                        foreach($tempLockerStockSupplies as $row){
                            $prev_id = $row->id;
                            unset($row->id);
                            $row->locker_id = $prev_id;
                            $rowArray = (array)$row+$arrTransaksi;
                            $this->db->insert('project_bridge_supplies', $rowArray);
                            showlast_Query("biru");
                            // Jika ingin mendapatkan ID yang diinsert
                            if(!$this->db->insert_id()){
                                echo "GAGAL INSERT SUPPLIES";
                            }
                        }
                    }
                }

                //update project produk
                $data = array(
                    'gen_tasklist' => 1
                );
                // Kondisi update berdasarkan project_id
                $this->db->where('id', $prodID);
                $this->db->update('project_produk', $data);
                showlast_query('merah');

            }
        }

    }
    public function projectID_inject_tr(){
        $this->load->model("Mdls/" . "MdlProdukProject");
        $pr = new MdlProdukProject();
        $pr->setFilters(array());
        $pr->addFilter("project_start_id > 0");
//        $this->db->where("project_start_name IS NULL");
        $tmp = $pr->lookUpAll()->result();
//        showlast_query("merah");
//        arrPrint($tmp);
//        matiHere(__LINE__);
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        if(!empty($tmp)){
            foreach($tmp as $row){
                $project_id = $row->id;
                $project_nama = $row->nama;
                $master_id = $row->transaksi_id;
//                cekMerah($master_id);
                $tr->setFilters(array());
                $tr->addFilter("id_master=$master_id");
                $tr->addFilter("project_id=0");
                $tr->addFilter("link_id=0");
                $tmpTr = $tr->lookUpAll()->result();
                if(!empty($tmpTr)){
                    foreach($tmpTr as $subRow){
//                        cekHijau($subRow->id);
                        $update = array(
                            "id" => $subRow->id
                        );
                        $where = array(
                            "project_id" => $project_id,
                            "project_nama" => $project_nama,
                        );
                        $tr->setFilters(array());
                        $tr->updateData($update, $where);
//                        showlast_query('biru');
                    }
                }
                else{
                    cekHijau("master_id:: $master_id sudah sinkron semua");
                }

                $update = array(
                    "id" => $project_id
                );
                $where = array(
                    "project_start_name" => 1
                );
                $pr->setFilters(array());
                $pr->updateData($update, $where);

                showlast_query("merah");
            }
        }
        else{
            cekHijau("project vs transaksi sudah sinkron semua");
        }

    }
    public function projectID_inject_tr5833(){

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("jenis=5833");
        $tr->addFilter("link_id=0");
        $tr->addFilter("project_id=0");
        $tmp = $tr->lookUpAll()->result();
//        showlast_query('biru');

        if(!empty($tmp)){
            foreach($tmp as $row){
                $id = $row->id;

                $tr->setFilters(array());
                $tr->setJointSelectFields("transaksi_id,main");
                $tmpReg = $tr->lookupBaseDataRegistries($id)->result();
//                showlast_query('merah');
                $pairRegistriesResult = array();
                if (sizeof($tmpReg) > 0) {
                    foreach ($tmpReg as $regRow) {
                        $pairRegistriesResult = blobDecode($regRow->main);
                        $project_id = $pairRegistriesResult['projectID'];
                        $update = array(
                            "id" => $id
                        );
                        $where = array(
                            "project_id" => $project_id
                        );
                        $tr->setFilters(array());
                        $tr->updateData($update, $where);
//                        showlast_query('biru');
                    }
                }

            }
        }
        else{
            cekHijau("tidak ada trx 5833 (distribusi ke pelaksana) yang belum sinkron...");
        }

    }
    public function projectID_inject_tr5855(){

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("jenis=5855");
        $tr->addFilter("link_id=0");
        $tr->addFilter("project_id=0");
        $tmp = $tr->lookUpAll()->result();
//        showlast_query('biru');

        if(!empty($tmp)){
            foreach($tmp as $row){
                $id = $row->id;

                $tr->setFilters(array());
                $tr->setJointSelectFields("transaksi_id,main");
                $tmpReg = $tr->lookupBaseDataRegistries($id)->result();
//                showlast_query('merah');
                $pairRegistriesResult = array();
                if (sizeof($tmpReg) > 0) {
                    foreach ($tmpReg as $regRow) {
                        $pairRegistriesResult = blobDecode($regRow->main);
                        $project_id = $pairRegistriesResult['projectID'];
                        $update = array(
                            "id" => $id
                        );
                        $where = array(
                            "project_id" => $project_id
                        );
                        $tr->setFilters(array());
                        $tr->updateData($update, $where);
//                        showlast_query('biru');
                    }
                }

            }
        }
        else{
            cekHijau("tidak ada trx 5855 (penerimaan fg pelaksana) yang belum sinkron...");
        }

    }

    public function cli_sync_project_id(){
        $this->bridgeProject();
        $this->projectID_inject_tr();
        $this->projectID_inject_tr5833();
        $this->projectID_inject_tr5855();
    }

    public function checkLockerTasklist(){

        $project_id = $_POST['project_id'];
        $task_id = $_POST['id'];

        $this->load->model("MdlTasklistProject");
        $tl = new MdlTasklistProject();
        $tl->addFilter("id='$task_id'");
        $tl->addFilter("produk_id='$project_id'");
//        $tl->addFilter("status='1'");
//        $tl->addFilter("trash='0'");
//        $this->db->where("progress_percent <=", 100);

        $tempTaskList = $tl->lookUpAll()->result();
        $tempTaskListQuery = $tl->db->last_query();

        $result = array(
            "status" => 0,
        );

        if(!empty($tempTaskList)){

            $task_progress = $tempTaskList[0]->progress_percent;
            $progress_id = $tempTaskList[0]->progress_id;
            $nomer = $tempTaskList[0]->nomer;
            $no_spk = $tempTaskList[0]->no_spk;
            $produk_id = $tempTaskList[0]->produk_id;
            $fase_id = $tempTaskList[0]->fase_id;
            $sub_fase_id = $tempTaskList[0]->sub_fase_id;

            $task_nama = $tempTaskList[0]->produk_nama;
            $project_nama = $tempTaskList[0]->nama;
            $ambil_angka_depan_spk = explode("/", $no_spk)[0];
            $gudang_project = "$produk_id" . "$fase_id" . "$sub_fase_id" . "$ambil_angka_depan_spk";

            if($progress_id==3){
                $result = array(
                    "status" => 3,
                    "reason" => 'workorder ini telah selesai dikerjakan dan sudah QC, apakah tetap ingin dibatalkan...?',
                );
            }
            else if($task_progress==0){

                //material sudah distribusi
                $arrMaterialDist = array();
                $this->load->model("Mdls/MdlLockerStock");
                $ls = new MdlLockerStock();
                $ls->addFilter("gudang_id='$gudang_project'");
                $ls->addFilter("state='active'");
                $tempLs = $ls->lookUpAll()->result();
                $arrProdukDistQuery = $this->db->last_query();
                if (!empty($tempLs)) {
                    foreach ($tempLs as $rows) {
                        $arrMaterialDist['produk'][$rows->produk_id] = $rows;
                        $arrMaterialDist['produk'][$rows->produk_id]->biaya_nama = 'produk';
                    }
                }

                $this->load->model("Mdls/MdlLockerStockSupplies");
                $lss = new MdlLockerStockSupplies();
                $lss->addFilter("gudang_id='$gudang_project'");
                $lss->addFilter("state='active'");
                $tempLss = $lss->lookUpAll()->result();
                $arrSuppliesDistQuery = $this->db->last_query();
                if (!empty($tempLss)) {
                    foreach ($tempLss as $rows) {
                        $arrMaterialDist['supplies'][$rows->biaya_id][$rows->produk_id] = $rows;
                        $arrMaterialDist['supplies'][$rows->biaya_id][$rows->produk_id]->biaya_nama = 'supplies';
                    }
                }

                //material sudah distribusi hold
                $arrMaterialDistHold = array();
                $this->load->model("Mdls/MdlLockerStock");
                $lsh = new MdlLockerStock();
                $lsh->addFilter("gudang_id='$gudang_project'");
                $lsh->addFilter("state='hold'");
                $tempLsh = $lsh->lookUpAll()->result();
                $arrProdukDistHoldQuery = $this->db->last_query();

                if (!empty($tempLsh)) {
                    foreach ($tempLsh as $rows) {
                        $arrMaterialDistHold[$rows->produk_id] = $rows;
                    }
                }

                $this->load->model("Mdls/MdlLockerStockSupplies");
                $lshs = new MdlLockerStockSupplies();
                $lshs->addFilter("gudang_id='$gudang_project'");
                $lshs->addFilter("state='distribute'");
                $tempLshs = $lshs->lookUpAll()->result();
                $arrSuppliesDistHoldQuery = $this->db->last_query();

                if (!empty($tempLshs)) {
                    foreach ($tempLshs as $rows) {
                        $arrMaterialDistHold[$rows->biaya_id][$rows->produk_id] = $rows;
                    }
                }

                $isProductsEmpty = empty($arrMaterialDist);
                $isSuppliesEmpty = empty($arrMaterialDistHold);

                if ($isProductsEmpty && $isSuppliesEmpty) {
                    // Keduanya kosong
                    $result = array(
                        "status" => 1,
                        "reason" => 'Produk dan supplies kosong. Silakan lanjut.',
                    );
                }
                else {
                    // Tambahkan pesan untuk produk jika ada
                    if (!$isProductsEmpty) {
                        $result_[] = json_encode($arrMaterialDist);
                    }
                    // Tambahkan pesan untuk supplies jika ada
                    if (!$isSuppliesEmpty) {
                        $result_[] = json_encode($arrMaterialDistHold);
                    }
                    $result = array(
                        "status" => 2,
                        "active" => $arrMaterialDist,
                        "hold" => $arrMaterialDistHold,
                        "reason" => implode("<br>", $result_),
                    );
                }
            }
            else{
                $result = array(
                    "status" => 99,
                    "reason" => 'workorder ini sedang dalam penyelesaian, apakah akan dibatalkan...? <br>semua unit dan supplies akan dikembalikan ke pusat.',
                );
            }

        }

        echo json_encode($result);
    }

    public function pembatalanTasklist(){
        $tasklist_id = $this->uri->segment(4);
        $project_id = $this->uri->segment(5);
        $status = $this->uri->segment(6);

        switch($status){
            case 1:
                $this->load->model("MdlTasklistProject");
                $tl = new MdlTasklistProject();
                $tl->addFilter("id='$tasklist_id'");
                $tl->addFilter("produk_id='$project_id'");
                $tl->addFilter("status='1'");
                $tl->addFilter("trash='0'");
                $tempTaskList = $tl->lookUpAll()->result();

                $arrUpdTasklist = array();

                if(!empty($tempTaskList)){
                    $spk = $tempTaskList[0]->no_spk;
                    $fase_id = $tempTaskList[0]->fase_id;

                    //usednya
                    $this->load->model("Mdls/MdlProjectKomposisiWorkorderSub");
                    $os = new MdlProjectKomposisiWorkorderSub();
                    $os->setFilters(array());
                    $os->addFilter("no_spk='$spk'");
                    $os->addFilter("jenis_transaksi='sub_wo'");
                    $os->addFilter("status='1'");
                    $os->addFilter("trash='0'");
                    $tempSubWo = $os->lookUpAll()->result();

                    $arrBahan = array();

                    if(!empty($tempSubWo)){

                        $arrupdate=array();

                        foreach($tempSubWo as $row){
                            $prd_dasar_id = $row->produk_dasar_id;
                            $jml_subwo = $row->qty_saldo;
                            $saldo_subwo = $row->saldo;
                            $jenis = $row->jenis;
                            $subwo_id = $row->id;

                            //update ke komposisi utama
                            $os->setFilters(array());
                            $os->addFilter("produk_dasar_id='$prd_dasar_id'");
                            $os->addFilter("jenis='$jenis'");
                            $os->addFilter("fase_id='$fase_id'");
                            $os->addFilter("produk_id='$project_id'");
                            $os->addFilter("jenis_transaksi='5582'");
                            $tempSubWo5582 = $os->lookUpAll()->result();

                            if(!empty($tempSubWo5582)){
                                $qty_before = $tempSubWo5582[0]->qty_kredit;
                                $qty_saldo_before = $tempSubWo5582[0]->qty_saldo;
                                $kredit_before = $tempSubWo5582[0]->kredit;
                                $saldo_before = $tempSubWo5582[0]->saldo;

                                $where = array(
                                    "jenis" => $jenis,
                                    "produk_dasar_id" => $prd_dasar_id,
                                    "fase_id" => $fase_id,
                                    "produk_id" => $project_id,
                                    "jenis_transaksi" => "5582",
                                );

                                $update = array(
                                    "qty_kredit" => $qty_before-$jml_subwo,
                                    "qty_saldo" => $qty_saldo_before+$jml_subwo,
                                    "kredit" => $kredit_before-$saldo_subwo,
                                    "saldo" => $saldo_before+$saldo_subwo,
                                );

                                $os->setFilters(array());
                                $os->updateData($where, $update) or matiHere("gagal memperbaharui data produk sub WO LINE: " . __LINE__);

                                $arrUpdTasklist[$tasklist_id] = 1;
                            }
                        }

                        if(!empty($arrUpdTasklist)){
                            $tl->setFilters(array());
                            $tl->updateData(array("id" => $tasklist_id), array("status" => 0, "trash" => 1)) or matiHere("gagal memperbaharui data produk sub WO LINE: " . __LINE__);
                        }

                    }

                    $query = $this->db->last_query();
                    $result = array(
                        'project_id' => $project_id,
                        'tasklist_id' => $tasklist_id,
                        'status' => $status,
                        'fase_id' => $fase_id,
                        'arrupdate' => $arrupdate,
//                        'query' => $query,
//                        '$tempTaskList' => $tempTaskList,
//                        '$tempSubWo' => $tempSubWo,
//                        '$arrBahan' => $arrBahan,
                    );

                    echo json_encode($result);
                }

                break;
        }



    }

}