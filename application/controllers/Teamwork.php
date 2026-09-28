<?php

/**
 * Created by PhpStorm.
 * User: widi
 * Date: 16/11/18
 * Time: 16:08
 */
class Teamwork extends CI_Controller
{
    protected $koloms;

    public function __construct()
    {
        parent::__construct();
        // if (!isset($this->session->login['id'])) {
        //     gotoLogin();
        // }
        // validateUserSession($this->session->login['id']);
    }



    public function edit()
    {
        cekMErah(__LINE__);
        // arrPrint($_SESSION["NEW"]);
        // unset($_SESSION["NEW"]);
        $pid = $prodID = $_GET["sID"];
        $iframeTarget = isset($_GET["iframe"]) ? $_GET["iframe"] : (isset($_POST["iframe"]) ? $_POST["iframe"] : "result");

        $this->load->model("Mdls/" . "MdlProdukKomposisi");
        $this->load->model("Mdls/" . "MdlProdukProject");
        $this->load->model("Mdls/" . "MdlHargaProduk");
        $this->load->model("Mdls/" . "MdlProdukRakitanPreBiaya");
        $this->load->model("Mdls/MdlDtaBiayaProduksi");
        $this->load->model("Mdls/MdlSatuan");

        $o = new MdlProdukProject();
        $pk = new MdlProdukKomposisi();
        $ob = new MdlProdukRakitanPreBiaya();
        $bp = new MdlDtaBiayaProduksi();
        $st = new MdlSatuan();

        //pembuat session untuk keperluan ui
        unset( $_SESSION['PROED']);
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
// arrprint($tempProdukMaster);
        // matiHEre();
        //produk komposisi sumary
        $pk->setFilters(array());
        $pk->addFilter("produk_id='$pid'");
        $pk->addFilter("jenis in ('produk','biaya')");
        $tempProduk = $pk->lookUpAll()->result();
        // arrPrint($tempProduk);
        // ceklIme($this->db->last_query());
        if (sizeof($tempProduk) > 0) {
            foreach ($tempProduk as $temproduk_0) {
                foreach ($temproduk_0 as $k => $v) {
                    $_SESSION['PROED'][$prodID]['component_sum'][$temproduk_0->jenis][$temproduk_0->produk_dasar_id][$k] = $v;
                    $produkKomposisi[$prodID][$temproduk_0->jenis]=array(

                    );
                }
            }
        }
// arrPrint($_SESSION['PROED']);
// matiHEre();
        //bagian biaya
        $tempBiaya = $ob->lookUpAll()->result();
        $prebiaya = array();
        if (sizeof($tempBiaya) > 0) {
            foreach ($tempBiaya as $ic => $biayaData) {
                $prebiaya["cat_id"][$biayaData->id] = (array)$biayaData;
            }
        }


        $tempBiayaProduksi = $bp->lookUpAll()->result();
        // $prebiaya = array();
        if (sizeof($tempBiayaProduksi)) {
            foreach ($tempBiayaProduksi as $ixb => $tempBiayaProduksi_0) {
                $prebiaya["produk_dasar_id"][$tempBiayaProduksi_0->id] = (array)$tempBiayaProduksi_0;
            }
        }
        // arrPrint($prebiayaProduksi);

        // $tempSatuan = $st->lookUpAll()->result();
        // if (sizeof($tempSatuan) > 0) {
        //     foreach ($tempSatuan as $tempSatuan_0) {
        //         $prebiaya["satuan_id"][$tempSatuan_0->id] = (array)$tempSatuan_0;
        //     }
        // }
        //
        //
        // //relasi jadi option atau input
        // $arrRel = array();
        // $priceList = array();
        // $produkKomposisTarget = array();
        // if (sizeof($tempProduk) > 0) {
        //     foreach ($tempProduk as $ii => $iiData) {
        //         $priceList[$iiData->fase_id][$iiData->produk_dasar_id] = array(
        //             "harga_ori" => $iiData->nilai,
        //             "harga_bom" => $iiData->harga,
        //         );
        //         if (!isset($iiData->subtotal)) {
        //             $iiData->subtotal = $iiData->jml * $iiData->harga;
        //         }
        //         $produkKomposisiFase[$iiData->fase_id][$iiData->jenis][$iiData->produk_dasar_id]["subtotal"] = $iiData->jml * $iiData->harga;
        //         $produkKomposisiFase[$iiData->fase_id][$iiData->jenis][$iiData->produk_dasar_id] = (array)$iiData;
        //         if ($iiData->jenis == "target") {
        //             $produkKomposisTarget[$prodID][$iiData->fase_id] = (array)$iiData;
        //         }
        //
        //     }
        //     $_SESSION['PROED'][$prodID]['component'] = $produkKomposisiFase;
        // }
        // else {
        //     $produkKomposisiFase = array();
        // }
        // // arrPrint($_SESSION['PROED'][$prodID]['component']);
        // // matiHEre();
        // $fieldFormProdukKomposisiFase = $kf->getFields();
        // $relationDataProdukKomposisiFase = array();
        // $relSupplies = array();
        // foreach ($fieldFormProdukKomposisiFase as $keyID => $temp0) {
        //     // arrPrint($temp0);
        //     if ($temp0["inputType"] == "combo" && isset($temp0["reference"])) {
        //         $preKey = $temp0["inputType"];
        //         $preKey = $temp0["kolom"];
        //         $preModel = $temp0["reference"];
        //         if (isset($temp0["reference"])) {
        //             $this->load->model("Mdls/" . $preModel);
        //             $p = new $preModel();
        //             $tempDatas = $p->lookUpAll()->result();
        //             if (sizeof($tempDatas) > 0) {
        //                 foreach ($tempDatas as $datas_0) {
        //                     $relSupplies[$preKey][$datas_0->id] = (array)$datas_0;
        //                 }
        //             }
        //         }
        //         else {
        //
        //         }
        //     }
        // }
        // // arrPrint($relSupplies);
        // // matiHere();
        // // arrPrint($_SESSION['PROED']);
        // /*
        //  * hirarki builder produk
        //  * produk[produk_id][fase_id] =array(
        //  * array(
        //  * )
        //  * )
        //  */
        //
        // // matiHEre();
        //
        // $produkKomposisi = array();//untuk summary semua fase di simpan di komposisi produk
        $produkKomposisiHeader = array(
            "cat_id" => "cat_nama",
            "satuan_id" => "satuan",
            "produk_dasar_id" => "produk_dasar_nama",
            "harga_ori" => "harga",
            "harga_bom" => "harga_bom",
        );
        $produkFaseHeader = array(
            "urut" => "urutan pekerjaan",
            "nama" => "fase",
            "aktivitas" => "aktivitas",
        );
        $relSuppliesHeader = array(
            "cat_id" => "cat_nama",
            "satuan_id" => "satuan",
            "produk_dasar_id" => "produk_dasar_nama",
            "harga_ori" => "harga",
            "harga_bom" => "harga_bom",
            // "harga_bom"=>"harga_bom",
        );
        $produkKomposisiFaseHeader = array(
            "produk" => array(
                "produk_dasar_id" => "Bahan baku",
                "satuan" => "Satuan",
                "nilai" => "Harga standar",
                "harga" => "Harga bom",
                "jml" => "Jml",
                "subtotal" => "Subtotal",
            ),
            "biaya" => array(
                "produk_dasar_id" => "Biaya",
                "cat_id" => "Kategori biaya",
                "satuan_id" => "satuan",
                // "harga_ori"       => "harga standar",
                "harga" => "Harga",
                "jml" => "Jml",
                "subtotal" => "Subtotal",
            ),
            "target" => array(
                "produk_dasar_id" => "Produk fase(wip)",
                // "gudang2_id"         => "fase proses lanjut*",
            ),
        );
        $produkKomposisiFaseEditable = array(
            "produk_dasar_id" => "produk_dasar_id", "cat_id" => "cat_id", "satuan_id" => "satuan_id", "harga" => "harga", "jml" => "jml", "fase_id" => "fase_id",
        );
        // arrPrintWebs($tempProdukMaster);

        $data = array(
            "mode" => "edit_teamwork",
            "produkID" => $prodID,
            "produkNama" => $tempProdukMaster[0]->nama,
            "produk_komposisi" => $produkKomposisi,
            "produk_fase" => $produkFase,
            "produk_komposisi_fase" => $_SESSION['PROED'][$prodID]['component'],
            "selector" => base_url() . get_class($this) . "/addSession/PROED/",
            //bagian header
            "produk_komposisi_header" => $produkFaseHeader,
            "produk_komposisi_fase_header" => $produkKomposisiFaseHeader,
            "produk_fase_komposisiEditable" => $produkKomposisiFaseEditable,
            "relSupplies" => $relSupplies,
            "relBiaya" => $prebiaya,
            "relTarget" => $preTarget,
            "currentTargetWip" => $produkKomposisTarget,
            "relSuppliesHeader" => $relSuppliesHeader,
            // "relbiayaProduksi"=>$prebiayaProduksi,
            "addProdukKomposisiLink" => base_url() . get_class($this) . "/addData/MdlProdukKomposisi",//untuk per produk(sumary)
            "addProdukKomposisiBiayaLink" => base_url() . get_class($this) . "/addData/MdlProdukKomposisiFase",//untuk per produk(summary)
            "addFaseProdukLink" => base_url() . get_class($this) . "/addData/MdlProdukFase",//ke tabel produk fase
            "addFaseProdukKomposisiLink" => base_url() . get_class($this) . "/addData/MdlProdukKomposisiFase",//untuk per fase
            "addFaseProdukKomposisiBiayaLink" => base_url() . get_class($this) . "/addData/MdlProdukKomposisiFase",//untuk per fase
            "addFaseHasilProduksi" => base_url() . get_class($this) . "/addData/MdlProdukKomposisiFase",//supplies wip ke tabel produk denga tipe item_wip
            "newData" => isset($_SESSION["NEW"]) ? $_SESSION["NEW"] : array(),
            "result" => $iframeTarget,
        );
        $this->load->view("editor", $data);
    }


    /* ====================================================================
     * editor komposisi produk rakitan
     * ====================================================================*/
    public function save()
    {

        $prodID = $this->uri->segment(3);

        // arrPrint($_SESSION['PROED']);
        // mati_disini(__LINE__);
        $this->load->model("Mdls/" . 'MdlProdukKomposisi');
        $this->load->model("Mdls/" . 'MdlProdukRakitanBiaya');
        $this->load->model("Mdls/" . 'MdlProdukRakitan');
        $pk0 = New MdlProdukKomposisi();
        $pk = New MdlProdukKomposisi();

        $prb = New MdlProdukRakitanBiaya();
        $pr = New MdlProdukRakitan();

        $this->db->trans_start();


        $preTmp = $pk->lookupByPID($prodID)->result();
        if (sizeof($preTmp) > 0) {
            foreach ($preTmp as $eSpec) {
                $arrUpdate = array(
                    "trash" => 1,
                );
                $where = array(
                    "id" => $eSpec->id,
                );
                // $pk->updateData($where, $arrUpdate, $pk->getTableName());
                // cekHere($this->db->last_query());
            }

            $pk0->setFilters(array());
            $where = array(
                "produk_id" => $prodID,
            );
            $arrUpdate = array(
                "trash" => 1,
            );
            $pk0->updateData($where, $arrUpdate, $pk->getTableName());
            cekHere($this->db->last_query());
            // matiHere($prodID);
        }

        $arrData = array();
        if (sizeof($_SESSION['PROED'][$prodID]['component']) > 0) {
            $total_komponen = 0;
            foreach ($_SESSION['PROED'][$prodID]['component'] as $bahanID => $eSpec) {
                $arrData = array(
                    "produk_id" => $prodID,
                    "produk_nama" => "",
                    "produk_dasar_id" => $bahanID,
                    "produk_dasar_nama" => $eSpec['name'],
                    "satuan_nama" => $eSpec['satuan'],
                    "jml" => $eSpec['jml'],
                    "nilai" => $eSpec['harga'],
                );
                $pk->addData($arrData);
                //                cekHere($this->db->last_query());

                $total_komponen += ($eSpec['harga'] * $eSpec['jml']);
            }
            $total_cost = 0;
            foreach ($_SESSION['PROED'][$prodID]['cost'] as $bahanID => $eSpec) {
                $arrData = array(
                    "produk_id" => $prodID,
                    "produk_nama" => "",
                    "produk_dasar_id" => $bahanID,
                    "produk_dasar_nama" => $eSpec['name'],
                    "nilai" => $eSpec['value'],
                    "jenis" => "biaya",
                );

                $total_cost += $eSpec['value'];
                $pk->addData($arrData);
                //                cekKuning($this->db->last_query());
            }
        }

        $hpp_bom = $total_komponen + $total_cost;
        cekMerah("$hpp_bom = $total_komponen + $total_cost;");
        //----------?? -----------
        if (sizeof($_SESSION['PROED'][$prodID]['cost'])) {
            $pr->addFilter("id='$prodID'");
            $prTmp = $pr->lookupAll()->result();
            $produkNama = $prTmp[0]->nama;
            //            $prb->addFilter("produk_id='$prodID'");
            //            $prbTmp = $prb->lookupAll()->result();
            //            if (sizeof($prbTmp) == 0) {
            foreach ($_SESSION['PROED'][$prodID]['cost'] as $bahanID => $eSpec) {
                $arrData = array(
                    "produk_id" => $prodID,
                    "produk_nama" => $produkNama,
                    "biaya_id" => $bahanID,
                    "biaya_nama" => $eSpec['name'],
                    "nilai" => $eSpec['value'],
                    "dtime" => date("Y-m-d H:i:s"),
                );
                /* -------------------------------------------------------------------------
                 * dimatikan karena inginsert ke produk biaya lagi sehingga data double-double
                 * -------------------------------------------------------------------------*/
                // $prb->addData($arrData);
            }
            //            }
        }

        /*---insert ke price--*/
        $this->load->model("Mdls/MdlHargaProduk2");
        $pc = new MdlHargaProduk2();
        $condites = array(
            "produk_id" => $prodID,
            "jenis" => "item_rakitan",
            "jenis_value" => "hpp_bom",
            "trash" => "0",
            "status" => "1",
            "cabang_id" => my_cabang_id(),
        );
        $dbPrice = $pc->lookupByCondition($condites)->result();
        // showLast_query("lime");
        // arrPrintHijau($dbPrice);
        if (sizeof($dbPrice) == 0) {
            $dtPrices = array(
                    "nilai" => $hpp_bom,
                    "dtime" => dtimeNow(),
                    "oleh_id" => my_id(),
                    "oleh_nama" => my_name(),
                ) + $condites;
            $pc->addData($dtPrices);
            showLast_query("biru");
        }
        else {
            $dtPrices = array(
                "nilai" => $hpp_bom,
            );
            $pc->updateData($condites, $dtPrices);
            // showLast_query("biru");
        }

        /*history price*/
        // $data_id = $uSpec['where']['produk_id'];
        $this->load->model("Mdls/" . "MdlDataHistory");
        $hTmp = new MdlDataHistory();
        $tmpHData = array(
            "orig_id" => (sizeof($dbPrice) > 0 && $dbPrice[0]->id != null ? $dbPrice[0]->id : 0),
            "mdl_name" => "MdlHargaProduk2",
            "mdl_label" => "MdlHargaProduk2",
            "old_content" => blobEncode($dbPrice),
            // "old_content_intext" => print_r($tempOld, true),
            "new_content" => blobEncode($dtPrices),
            // "new_content_intext" => print_r($uSpec["history"], true),
            "label" => "price",
            "oleh_id" => my_id(),
            "oleh_name" => my_name(),
            "data_id" => $prodID,
            "cabang_id" => my_cabang_id(),
        );
        $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
        // showLast_query("kuning");
        // -----------------------------------------------------------------------------------------


        //         mati_disini("BERHASIL SAVE <br>".__METHOD__ . " blom commit @" . __LINE__);
        $this->db->trans_complete();


        $backLink = $_SESSION['PROED'][$prodID]['backLink'];

        $alerts = array(
            "type" => "success",
            "html" => "data saved successfully",
            "timer" => "5000",
        );
        echo swalAlert($alerts);

        $_SESSION['PROED'][$prodID]['cost'] = NULL;
        $_SESSION['PROED'][$prodID]['component'] = NULL;
        $_SESSION['PROED'][$prodID]['backLink'] = NULL;

        //        matihere();

        unset($_SESSION['PROED']);


        $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(
                                   {
                                        title:'Modify Product ',
//                                        size: BootstrapDialog.SIZE_WIDE,
                                        cssClass: 'edit-dialog',
                                        message: " . '$' . "('<div></div>').load('" . $backLink . "'),
                                        draggable:true,
                                        closable:true,
                                        });";

        $actionTarget = "top.$('#result2').attr('src', '" . base_url() . "ProductEditor/edit?attached=1&sID=$prodID&backlink=$backLink');";

        echo "<html>";
        echo "<head>";
        echo "<script src=\"" . cdn_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
        echo "</head>";
        echo "<body onload=\"$actionTarget\">";
        echo "</body>";
    }

    public function syncKomposisiBiaya()
    {

        $this->load->model("Mdls/MdlProdukKomposisi");
        $this->load->model("Mdls/MdlProdukRakitanBiaya");
        // $this->load->model("Mdls/MdlProdukRakitanBiaya");
        $km = new MdlProdukKomposisi();
        $by = new MdlProdukRakitanBiaya();

        $produk_id = $this->uri->segment(3);
        $condite = array("produk_id" => $produk_id);

        $biayas = $by->lookupByCondition($condite)->result();
        // showLast_query("biru");
        // arrPrint($biayas);

        $this->db->trans_start();

        $km->deleteKomposisiBiaya($condite);
        // showLast_query("lime");
        $jml = $this->db->affected_rows();
        // cekBiru("terdampak:: $jml");

        foreach ($biayas as $biayaItems) {
            $produk_nama = $biayaItems->produk_nama;

            $datas[$biayaItems->biaya_id]["produk_id"] = $produk_id;
            $datas[$biayaItems->biaya_id]["produk_nama"] = $produk_nama;
            $datas[$biayaItems->biaya_id]["produk_dasar_id"] = $biayaItems->biaya_id;
            $datas[$biayaItems->biaya_id]["produk_dasar_nama"] = $biayaItems->biaya_nama;
            $datas[$biayaItems->biaya_id]["nilai"] = $biayaItems->nilai;
            $datas[$biayaItems->biaya_id]["jml"] = 1;
            $datas[$biayaItems->biaya_id]["status"] = 1;
            // $km->addKomposisiBiaya($condite, $datas);
            // showLast_query("kuning");
        }
        foreach ($datas as $biayaID => $biayaData) {
            $km->addKomposisiBiaya($condite, $biayaData);
            showLast_query("kuning");
        }

        // die();
        $this->db->trans_complete();
        unset($_SESSION['PROED'][$produk_id]);

        $alerts = array(
            "type" => "success",
            "html" => "synchronization was successful done " . $produk_id,
        );
        echo swalAlert($alerts);
        // reloaded();
        // topReload();
        $backLink = "";
        $actionTarget = "top.$('#result2').attr('src', '" . base_url() . "ProductEditor/edit?attached=1&sID=$produk_id&backlink=$backLink');";
        echo "<body onload=\"$actionTarget\">";
        echo "</body>";
        die();
        mati_disini(__METHOD__);
    }

    /**/
    public function delet_produk_biaya()
    {

        $this->load->model("Mdls/MdlProdukRakitanBiaya");
        $o = new MdlProdukRakitanBiaya();

        $this->load->model("Mdls/MdlProdukRakitan");
        $dt = new MdlProdukRakitan();

        $this->db->trans_start();

        // $this->db->where_in("id",array('476','475'));
        // $this->db->where("id=474");
        $srcs = $dt->lookupAll()->result();
        showLast_query("hijau");
        // arrPrintHijau($srcs);

        foreach ($srcs as $src) {
            $bProdukId = $src->id;
            $bNama = $src->nama;

            if ($bProdukId > 0) {
                $where2 = array("produk_id" => $bProdukId);
            }
            else {
                $where2 = array();
            }
            $tmpOrig2 = $o->lookupByCondition($where2)->result();
            showLast_query("biru");
            // arrPrint($tmpOrig2);

            $hasil = "";
            $hasil .= "$bNama  already set up<br>";

            $data_biayas = array();
            $group_data_biayas = array();
            foreach ($tmpOrig2 as $itemOrigs) {
                $bNama2 = $itemOrigs->biaya_nama;
                $bDtime = $itemOrigs->dtime;
                $bId = $itemOrigs->biaya_id;
                $id = $itemOrigs->id;

                $data_biayas[$bId] = $bNama2;
                $group_data_biayas[$bId][$id] = $bDtime;

                $bNilai2 = formatField("harga", $itemOrigs->nilai);

                // foreach ($o->getListedFieldsView() as $val) {
                //     $bNama22 = $itemOrigs->$val;
                //     $bNilai2 = isset($itemOrigs->nilai) ? formatField("harga", $itemOrigs->nilai) : "";
                //     $var = "$bDtime $bNama2 <span>$bNilai2</span>";
                //     if ($hasil == "") {
                //         $hasil .= "$var";
                //     }
                //     else {
                //         $hasil = "$hasil<br>$var";
                //     }
                // }


            }

            arrPrint($data_biayas);
            arrPrint($group_data_biayas);
            foreach ($data_biayas as $data_biaya_id => $data_biaya_nama) {

                $dt_pembantu = $group_data_biayas[$data_biaya_id];
                ksort($dt_pembantu);
                arrPrintHijau($dt_pembantu);
                $keep_id = reset(array_flip($dt_pembantu));
                cekHijau("$keep_id");
                $ceks = 0;
                foreach ($dt_pembantu as $db_id => $item) {
                    if ($db_id != $keep_id) {
                        $where = array(
                            "id" => $db_id,
                            "produk_id" => $bProdukId,
                        );
                        $data = array(
                            "trash" => 1
                        );
                        $o->updateData($where, $data);
                        showLast_query("kuning");
                    }
                }
            }

        } // dt produk

        // arrPrint($tmpOrig2);
        $this->db->trans_complete();
        cekHitam("<h1>done</h1>");

    }

    /*
 * tool sync hpp supplies bom
 */
    public function syncHargaKomposisiBom()
    {
        $this->load->model("Mdls/MdlHargaSupplies");
        $this->load->model("Mdls/" . "MdlProdukKomposisi");

        $hs = new MdlHargaSupplies();
        $pk = new MdlProdukKomposisi();


        $components = $pk->lookUpAll()->result();
        ceklIme($this->db->last_query());
        $arrBahanID = array();
        foreach ($components as $components0) {
            $arrBahanID[$components0->produk_dasar_id] = $components0->produk_dasar_id;
        }

        $hs->addFilter("jenis_value='hpp'");
        $hs->addFilter("cabang_id='" . CB_ID_PUSAT . "'");
        $hs->addFilter("produk_id in ('" . implode("','", $arrBahanID) . "')");
        $hsTmp = $hs->lookupAll()->result();
        $arrBahanPrice = array();
        if (sizeof($hsTmp) > 0) {
            foreach ($hsTmp as $hsSpec) {
                $arrBahanPrice[$hsSpec->produk_id] = $hsSpec->nilai;
            }
        }
        $this->db->trans_start();
        // arrPrint($arrBahanPrice);
        foreach ($arrBahanPrice as $pid => $price) {
            $where = array("produk_dasar_id" => $pid);
            $data = array("nilai" => $price, "harga" => $price);
            $pk->updateData($where, $data);
            ceklIme($this->db->last_query());
        }

        // matiHEre();
        mati_disini();
        $this->db->trans_commit();
        cekHitam("selesai");
    }

    /*
* tool syncroner harga bom = supplies + standar cost
*/
    public function syncHargaBom()
    {
        // $this->load->model("Mdls/MdlHargaSupplies");
        $cabID = 25;
        $listedPrice = array(
            "hpp_bom" => "harga",
            "jual" => "harga",
            "jual_nppn" => "harga",
        );
        $this->load->model("Mdls/" . "MdlProdukKomposisi");
        $this->load->model("Mdls/" . "MdlProdukKomposisi_and_cost");
        $this->load->model("Mdls/" . "MdlHargaProdukRakitan");

        $h = new MdlHargaProdukRakitan();
        $pk = new MdlProdukKomposisi_and_cost();
        $components = $pk->lookUpAll()->result();

        $hargaBomList = array();
        foreach ($components as $components_00) {

            if (!isset($hargaBom[$components_00->produk_id]["supplies"])) {
                $hargaBom[$components_00->produk_id]["supplies"] = 0;
                // $hargaBom[$components_00->produk_id]["biaya"] = 0;
            }
            if (!isset($hargaBom[$components_00->produk_id]["biaya"])) {
                $hargaBom[$components_00->produk_id]["biaya"] = 0;
                // $hargaBom[$components_00->produk_id]["biaya"] = 0;
            }
            if (!isset($hargaBom[$components_00->produk_id]["harga"])) {
                $hargaBom[$components_00->produk_id]["harga"] = 0;
                // $hargaBom[$components_00->produk_id]["biaya"] = 0;
            }
            switch ($components_00->jenis) {
                case "produk":
                    $hargaBom[$components_00->produk_id]["supplies"] += $components_00->nilai * $components_00->jml;
                    $hargaBomList[$components_00->produk_id]["supplies"][$components_00->produk_dasar_id] = $components_00->nilai;
                    break;
                case "biaya":
                    $hargaBom[$components_00->produk_id]["biaya"] += $components_00->nilai * $components_00->jml;
                    $hargaBomList[$components_00->produk_id]["biaya"][$components_00->produk_dasar_id] = $components_00->nilai;

                    break;
            }

            $hargaBom[$components_00->produk_id]["harga"] += $components_00->nilai * $components_00->jml;
            $hargaBom[$components_00->produk_id]["nama"] = $components_00->produk_nama;
        }


        $toUpdate = array();
        foreach ($hargaBom as $PID => $pidData) {
            foreach ($listedPrice as $key => $src) {
                $toUpdate[$PID][$key] = $pidData[$src];
            }

        }
        $this->db->trans_start();
        $ii = 0;
        foreach ($toUpdate as $produkid => $dataudpate) {
            foreach ($dataudpate as $jenis_value => $valueNilai) {
                /*
                 * kalau mau insert data updtae harga lama trash 1 sttaus 0 dulu
                 * aktive kan datainsert yang di matikan, lalu danti methode update data ke addData
                 */
                $ii++;
                $datainsert = array(
                    // "produk_id"=>$produkid,
                    // "cabang_id"=>$cabID,
                    // "jenis_value"=>"$jenis_value",
                    "nilai" => $valueNilai,
                    // "jenis"=>"item_rakitan",
                    // "oleh_id"=>"-100",
                    // "oleh_nama"=>"system",
                    // "status"=>"1",
                    // "trash"=>"0",
                    // "dtime"=>dtimeNow(),
                );
                $where = array(
                    "produk_id" => $produkid,
                    "cabang_id" => $cabID,
                    "jenis_value" => "$jenis_value",
                    "jenis" => "item_rakitan",
                );
                $inserID = $h->updateData($where, $datainsert) or matiHere("gagal insert data");
                cekMerah($this->db->last_query());
                // arrPrint($datainsert);
                // matiHEre($this->db->last_query());
            }
            // arrPrint($dataudpate);
            // $update[$PID]["jenis"] = "item_rakitan";
            // $toUpdate[$PID]["dtime"] = dtimeNow();
            // $toUpdate[$PID]["oleh_id"] = "-1000";
            // $toUpdate[$PID]["oleh_nama"] = "sys";
        }
        // arrPrint($toUpdate);
        // cekLime($this->db->last_query());
        // arrprint($components);


        // matiHEre();
        // mati_disini($ii);
        $this->db->trans_commit();
        cekHitam("selesai");
    }

    public function addSession()
    {
        //buat sesi untuk menambah data produk fase komposisi disimpan di sesi master aka PROED dan NEW dan
        // arrPrint($this->uri->segment_array());
        // arrPrintWEbs($_GET);
        // unset($_SESSION["NEW"]["produk_fase"]);
        $prodID = $this->uri->segment(4);
        $masterSes = $this->uri->segment(3);
        $key = $_GET["key"];
        $value = $_GET["value"];
        // unset($_SESSION["NEW"]);
        switch ($_GET["mode"]) {
            case "produk_fase":
                //untuk tambah fase
                if (!isset($_SESSION["NEW"]["produk_fase"][$prodID])) {
                    unset($_SESSION["NEW"]["produk_fase"]);
                }
                $_SESSION["NEW"]["produk_fase"][$prodID][$key] = $value;
                break;
            case "komposisi_fase":
                // unset($_SESSION["NEW"]["komposisi_fase"]);

                if (!isset($_SESSION["NEW"]["komposisi_fase"][$prodID])) {
                    unset($_SESSION["NEW"]["komposisi_fase"]);
                }
                $this->load->model("Mdls/MdlProdukRakitan");
                $p = new MdlProdukRakitan();
                $produkID = $this->uri->segment(4);
                if ($key == "produk_dasar_id") {
                    $this->load->model("Mdls/MdlSupplies");
                    $this->load->model("Mdls/MdlHargaSupplies");

                    $s = new MdlHargaSupplies;
                    $s->addFilter("produk_id='$value'");
                    $s->addFilter("cabang_id='-1'");
                    $s->addFilter("status='1'");
                    $s->addFilter("jenis_value='hpp'");
                    $tempHarga = $s->lookUpAll()->result();

                    // cekMerah($this->db->last_query());
                    // arrprint($tempHarga);
                    $s = new MdlSupplies;
                    $tempSupplies = $s->lookUpById($value)->result();


                    $arrData = array(
                        "produk_dasar_id" => $_GET["value"],
                        "produk_dasar_nama" => $tempSupplies[0]->nama,
                        "satuan_id" => $tempSupplies[0]->satuan_id,
                        "satuan" => $tempSupplies[0]->satuan,
                        "nilai" => $tempHarga[0]->nilai,
                    );
                    // matiHEre();
                    // $_SESSION["NEW"]["komposisi_fase"][$prodID][$value][$key] = $value;
                    $_SESSION["NEW"]["komposisi_fase"][$prodID] = $arrData;
                }
                else {
                    if ($key == "jml") {
                        $_SESSION["NEW"]["komposisi_fase"][$prodID]["subtotal"] = $value * $_SESSION["NEW"]["komposisi_fase"][$prodID]["harga"];
                    }
                    $_SESSION["NEW"]["komposisi_fase"][$prodID][$key] = $value;
                }

                // matiHEre();
                break;
            case "komposisi_fase_biaya":
                if (!isset($_SESSION["NEW"]["komposisi_fase_biaya"][$prodID])) {
                    unset($_SESSION["NEW"]["komposisi_fase_biaya"]);
                }

                $produkID = $this->uri->segment(4);
                if ($key == "produk_dasar_id") {
                    $this->load->model("Mdls/MdlDtaBiayaProduksi");

                    $s = new MdlDtaBiayaProduksi;
                    $tempSupplies = $s->lookUpById($value)->result();
                    // arrPrint($tempSupplies);
                    // matiHere(__LINE__);

                    $arrData = array(
                        "produk_dasar_id" => $_GET["value"],
                        "produk_dasar_nama" => $tempSupplies[0]->nama,
                        // "satuan_id"=>$tempSupplies[0]->satuan_id,
                        // "satuan"=>$tempSupplies[0]->satuan,
                        // "nilai"=>$tempHarga[0]->nilai,
                    );
                    // matiHEre();
                    $_SESSION["NEW"]["komposisi_fase_biaya"][$prodID][$key] = $value;
                    $_SESSION["NEW"]["komposisi_fase_biaya"][$prodID]["produk_dasar_nama"] = $tempSupplies[0]->nama;
                    // arrPrint($_SESSION["NEW"]["komposisi_fase"][$prodID]);
                    // matiHere(__LINE__);
                    // $_SESSION["NEW"]["komposisi_fase_biaya"][$prodID] = $arrData;
                }
                else {
                    if ($key == "cat_id") {
                        $this->load->model("Mdls/MdlProdukRakitan");
                        $p = new MdlProdukRakitan();
                        $this->load->model("Mdls/MdlProdukRakitanPreBiaya");

                        $s = new MdlProdukRakitanPreBiaya;
                        $tempSupplies = $s->lookUpById($value)->result();
                        // arrPrint($tempSupplies);
                        // matiHere(__LINE__);

                        $arrData = array(
                            "cat_id" => $_GET["value"],
                            "cat_nama" => $tempSupplies[0]->nama,
                            // "satuan_id"=>$tempSupplies[0]->satuan_id,
                            // "satuan"=>$tempSupplies[0]->satuan,
                            // "nilai"=>$tempHarga[0]->nilai,
                        );
                        // matiHEre();
                        $_SESSION["NEW"]["komposisi_fase_biaya"][$prodID][$key] = $value;
                        $_SESSION["NEW"]["komposisi_fase_biaya"][$prodID]["cat_nama"] = $tempSupplies[0]->nama;
                        // $_SESSION["NEW"]["komposisi_fase_biaya"][$prodID] = $arrData;
                    }
                    else {
                        if ($key == "jml") {
                            $_SESSION["NEW"]["komposisi_fase_biaya"][$prodID]["subtotal"] = $value * $_SESSION["NEW"]["komposisi_fase_biaya"][$prodID]["harga"];
                        }
                    }

                    $_SESSION["NEW"]["komposisi_fase_biaya"][$prodID][$key] = $value;
                }

                // matiHEre();
                break;
            case "komposisi_target":
                cekmerah("skip pakai langsung update akerna ada proses menghitung");
                break;
        }
        // arrPrint( $_SESSION["NEW"]["komposisi_fase_biaya"]);
        // arrPrint($_SESSION["NEW"]);
        // matiHEre();

        $resultID = isset($_GET['result']) ? trim($_GET['result']) : (isset($_POST['result']) ? $_POST['result'] : "");
        if ($resultID != "") {
            echo "<script>var iframe = top.document.getElementById('$resultID');iframe.src=iframe.src;</script>";
        }


    }

    public function addData()
    {
        //wip di eindex untuk next project
        // arrprint($this->uri->segment_array());
        // arrPrintWebs($_GET);
        $this->load->model("Mdls/" . "MdlProdukKomposisi");
        $this->load->model("Mdls/" . "MdlProdukRakitan");
        $this->load->model("Mdls/" . "MdlProdukKomposisiFase");
        $this->load->model("Mdls/" . "MdlSupplies");
        $this->load->model("Mdls/MdlProdukRakitan");
        $this->load->model("Mdls/MdlProdukFase");
        $mdlName = $this->uri->segment(3);
        $this->load->model("Mdls/$mdlName");
        $pf = new $mdlName();
        $key = $_GET["mode"];
        // $key2 = $_GET["key"];
        $produk_id = $this->uri->segment(4);
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

        //endregion
        $masterPID = array_keys($_SESSION["NEW"][$key])[0];
        $pr = new MdlProdukRakitan();
        $tempProduk = $pr->lookupById($masterPID)->result();
        $master_nama = $tempProduk[0]->nama;

        // arrPrint($_SESSION["NEW"]);
        // matiHere();
        $this->db->trans_start();
        $toInsert = array();
        switch ($key) {
            case "produk_fase":
                // $addColom = array("produk_id"=>);
                $pr = new MdlProdukRakitan();
                $tempProduk = $pr->lookupById($masterPID)->result();
                // arrprint($tempProduk);
//                cekHere("[$key] [$masterPID]");
//                arrPrint($_SESSION["NEW"]);
//                $_SESSION["NEW"] = null;
//                unset($_SESSION["NEW"]);
//                mati_disini(__LINE__);
                $master_nama = $tempProduk[0]->nama;
                $tempMaster = array(
                    "produk_id" => $masterPID,
                    "cabang_id" => $tempProduk[0]->cabang_id,
                    "jenis_master" => "7778",
                    "urut" => $_SESSION["NEW"][$key][$masterPID]["urut"],
                    "nama" => $_SESSION["NEW"][$key][$masterPID]["nama"],
                    "aktivitas" => $_SESSION["NEW"][$key][$masterPID]["aktivitas"],
                    "kode" => "7778r" . $masterPID . "" . $_SESSION["NEW"][$key][$masterPID]["urut"],
                    "next_kode_transaksi" => "7778" . $masterPID . "" . $_SESSION["NEW"][$key][$masterPID]["urut"],
                    "gudang_id" => $_SESSION["NEW"][$key][$masterPID]["urut"] . "$masterPID",
                    "gudang2_id" => $_SESSION["NEW"][$key][$masterPID]["urut"] . "$masterPID",
                );
//                arrPrintWebs($tempMaster);
                $insertID = $pf->addData($tempMaster) or matiHere("gagal menambahkan data");
//                cekLime($this->db->last_query());
//                matiHEre(__LINE__);
                if ($insertID) {
                    //sambungin ke supplies untuk buat item_target
                    $n = new MdlProdukKomposisiFase();
                    $s = new MdlSupplies();
                    $dataSupplies = array(
                        "jenis" => "item_wip",
                        "nama" => $_SESSION["NEW"][$key][$masterPID]["nama"],
                        "bom_id" => $masterPID,
                        "bom_nama" => $master_nama,
                    );
                    $insertSuplies = $s->addData($dataSupplies) or matiHere("Gagal menambahkan data fase produksi, silahkan refresh browser terlebih dahulu untuk membersihkan sesi");

                    //auto build komposisi dari fase sebelumnya (jenis=produk)
                    $prevFase = $_SESSION["NEW"][$key][$masterPID]["urut"] - 1;
                    if ($prevFase > 0) {
                        $n->addFilter("fase_id='$prevFase'");
                        $n->addFilter("jenis='target'");
                        $prevTarget = $n->lookUpAll()->result();
                        // arrprintWebs($prevTarget);
                        $tempMasterPrev = array(
                            "jenis" => "produk",
                            "produk_id" => $masterPID,
                            "produk_nama" => $master_nama,
                            // "cabang_id"=>$tempProduk[0]->cabang_id,
                            "produk_dasar_id" => $prevTarget[0]->produk_dasar_id,
                            "produk_dasar_nama" => $prevTarget[0]->produk_dasar_nama,
                            "satuan_id" => $prevTarget[0]->satuan_id,
                            "satuan" => $prevTarget[0]->satuan,
                            "jml" => $prevTarget[0]->jml,
                            "nilai" => $prevTarget[0]->nilai,
                            "harga" => $prevTarget[0]->harga,
                            "fase_id" => $_SESSION["NEW"][$key][$masterPID]["urut"],
                            "gudang_id" => $_SESSION["NEW"][$key][$masterPID]["urut"] . "$masterPID",
                            "gudang2_id" => $_SESSION["NEW"][$key][$masterPID]["urut"] . "$masterPID",
                            "author" => $this->session->login['id'],
                            "jenis_transaksi" => "7778." . $masterPID . "." . $_SESSION["NEW"][$key][$masterPID]["urut"],
                            "dtime" => date("Y-m-d H:i"),
                            "cat_id" => "6",
                            "cat_nama" => "bahan_baku"
                        );
                        $insertID = $n->addData($tempMasterPrev) or matiHere("gagal menambahkan data");
                        // arrPrint($tempMasterPrev);

                        //sini update gudang 2ID fase sebelumnya di produk_fase curent fase -1
                        $updateProdukFase = array(
                            "gudang2_id" => $_SESSION["NEW"][$key][$masterPID]["urut"] . "$masterPID",
                        );
                        $pf->updateData(array("urut" => $prevFase, "produk_id" => "$masterPID", "cabang_id" => $tempProduk[0]->cabang_id), $updateProdukFase) or matiHere("gagal memperbaharui fase produksi, silahkan relogin untuk membersihkan sesi browser");
                        cekBiru($this->db->last_query());
                        $n->setFilters(array());
                        $n->updateData(array("produk_id" => $masterPID, "fase_id" => $prevFase), $updateProdukFase) or matiHere("gagal memperbaharui fase produksi, silahkan relogin untuk membersihkan sesi browser");
// cekBiru($this->db->last_query());
//                             matiHere("ada fase sebelumnya buat produk komposisinya dong ".__LINE__);
                    }


                    //auto build target wip komposisi fase (jenis=target)

                    $tempMaster = array(
                        "jenis" => "target",
                        "produk_id" => $masterPID,
                        "produk_nama" => $master_nama,
                        // "cabang_id"=>$tempProduk[0]->cabang_id,
                        "produk_dasar_id" => $insertSuplies,
                        "produk_dasar_nama" => $_SESSION["NEW"][$key][$masterPID]["nama"],
                        "satuan_id" => isset($_SESSION["NEW"][$key][$masterPID]["satuan_id"]) ? $_SESSION["NEW"][$key][$masterPID]["satuan_id"] : "",
                        "satuan" => isset($dtaSatuan[$_SESSION["NEW"][$key][$masterPID]["satuan_id"]]) ? $dtaSatuan[$_SESSION["NEW"][$key][$masterPID]["satuan_id"]] : "",
                        "jml" => 1,
                        "nilai" => 0,
                        "harga" => 0,
                        "fase_id" => $_SESSION["NEW"][$key][$masterPID]["urut"],
                        "gudang_id" => $_SESSION["NEW"][$key][$masterPID]["urut"] . "$masterPID",
                        "gudang2_id" => $_SESSION["NEW"][$key][$masterPID]["urut"] . "$masterPID",
                        "author" => $this->session->login['id'],
                        "jenis_transaksi" => "7778." . $masterPID . "." . $_SESSION["NEW"][$key][$masterPID]["urut"],
                        "dtime" => date("Y-m-d H:i"),
                    );
                    $insertID = $n->addData($tempMaster) or matiHere("gagal menambahkan data");
                    unset($_SESSION["NEW"][$key][$masterPID]);
                }

                break;
            case "komposisi_fase":
                // arrPrint($_SESSION["NEW"]);
                // matiEHre();
                $masterFase = $_GET["fase_id"];
                if (!isset($_GET["fase_id"])) {
                    matiHere("gagal mendeteksi fase produksi! Silahkan refresh halaman dan coba kembali");
                }
                // $masterPID = array_keys($_SESSION["NEW"][$key])[0];
                // $this->load->model("Mdls/MdlProdukRakitan");
                // $pr = new MdlProdukRakitan();
                // $tempProduk = $pr->lookupById($masterPID)->result();
                $master_nama = $tempProduk[0]->nama;
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
                    "jenis_transaksi" => "7778." . $masterPID . "." . $masterFase,
                    "dtime" => date("Y-m-d H:i"),
                    "cat_id" => "6",//id kelompok biaya
                    "cat_nama" => "bahan baku",
                );
                $insertID = $pf->addData($tempMaster) or matiHere("gagal menambahkan data");
                $this->autoPatchSrcTargetWip($masterPID, $masterFase);
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
            case "komposisi_fase_biaya":

                $masterFase = $_GET["fase_id"];
                if (!isset($_GET["fase_id"])) {
                    matiHere("gagal mendeteksi fase produksi! Silahkan refresh halaman dan coba kembali");
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
                    "jenis_transaksi" => "7778." . $masterPID . "." . $masterFase,
                    "jenis" => "biaya",
                    "dtime" => date("Y-m-d H:i"),
                    "cat_id" => $_SESSION["NEW"][$key][$masterPID]["cat_id"],
                    "cat_nama" => $_SESSION["NEW"][$key][$masterPID]["cat_nama"],
                );
                $insertID = $pf->addData($tempMaster) or matiHere("gagal menambahkan data");
                $this->autoPatchSrcTargetWip($masterPID, $masterFase);
                // cekMErah($this->db->last_query());
                // matiHEre();
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
            default:
                cekHitam("skip aja ndak nulis");
                break;
        }

        //region auto updater all fase
        //auto update komposisi data next fase


        //endregion
        // matiHere(__LINE__);
        $this->db->trans_complete();
        // switch ()
        // matiHere(__LINE__." || ".__FILE__);
        $resultID = isset($_GET['result']) ? trim($_GET['result']) : (isset($_POST['result']) ? $_POST['result'] : "");
        if ($resultID != "") {
            echo "<script>var iframe = top.document.getElementById('$resultID');iframe.src=iframe.src;</script>";
        }

    }

    public function autoPatchSrcTargetWip($bomID, $faseID)
    {
        $nextFase = $faseID + 1;
        $this->load->model("Mdls/MdlProdukKomposisiFase");
        $n = new MdlProdukKomposisiFase();
        $n->setFilters(array());
        $n->addFilter("produk_id='$bomID'");
        $n->addFilter("fase_id='$faseID'");
        $n->addFilter("status='1'");
        $n->addFilter("trash='0'");
        $n->addFilter("jenis in ('produk','biaya')");
        $tempCurent = $n->lookUpAll()->result();
        // arrprint($tempCurent);
        cekBiru($this->db->last_query());
        if (count($tempCurent) > 0) {
            $harga = 0;
            $nilai = 0;
            foreach ($tempCurent as $data) {
                $newNilai = $data->nilai * $data->jml;
                $newharga = $data->harga * $data->jml;
                $harga += $newharga;
                $nilai += $newNilai;
            }
            $touUpdateCurent = array(
                "harga" => $harga,
                "nilai" => $nilai,
            );
            $where = array(
                "produk_id" => "$bomID",
                "fase_id" => "$faseID",
                "jenis" => "target",
            );
            $n->setFilters(array());
            $updateID = $n->updateData($where, $touUpdateCurent) or matiHere("gagal memperbahaui komposisi, silahkan relogin untuk membersihkan sesi terlebih dahulu");
            cekhijau($this->db->last_query());

            //lanjut update jenis produk next target
            $n = new MdlProdukKomposisiFase();
            $n->setFilters(array());
            $n->addFilter("produk_id='$bomID'");
            $n->addFilter("fase_id='$faseID'");
            $n->addFilter("status='1'");
            $n->addFilter("trash='0'");
            $n->addFilter("jenis='target'");
            $curentTarget = $n->lookUpAll()->result();

            $produk_dasar_id = $curentTarget[0]->produk_dasar_id;
            $harga = $curentTarget[0]->nilai;
            $nilai = $curentTarget[0]->harga;
            $updateNExt = array(
                "harga" => $harga,
                "nilai" => $nilai,
            );
            $where1 = array(
                "produk_id" => $bomID,
                "fase_id" => $nextFase,
                "produk_dasar_id" => $produk_dasar_id,
                "jenis" => "produk",
            );
            $n->setFilters(array());
            $n->updateData($where1, $updateNExt) or matiHere("gagal memperbahaui komposisi, silahkan relogin untuk membersihkan sesi terlebih dahulu");
            cekHitam($this->db->last_query());
            // arrPrint($curentTarget);


            // cekKuning($this->db->last_query());
        }
//         arrprint($tempCurent);
// cekHitam($bomID);
// cekMerah($faseID);
//         matiHEre();
    }


}