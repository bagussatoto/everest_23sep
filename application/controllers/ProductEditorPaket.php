<?php

/**
 * Created by PhpStorm.
 * User: widi
 * Date: 16/11/18
 * Time: 16:08
 */
class ProductEditorPaket extends CI_Controller
{
    protected $koloms;

    public function __construct()
    {
        parent::__construct();
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        validateUserSession($this->session->login['id']);
    }

    public function edit()
    {
        $prodID = $_GET['sID'];
        $this->load->model("Mdls/MdlProduk_Paket_Project");
        $this->load->model("Mdls/MdlProdukRakitanBiayaPaket");
        $this->load->model("Mdls/MdlProdukKomposisiPaket");
        $this->load->model("Mdls/MdlProduk2");
        $this->load->model("Mdls/MdlHargaProduk");

        $o = new MdlProduk_Paket_Project();
        $pk = new MdlProdukKomposisiPaket();
        $o2 = new MdlProduk2();
        $ob = new MdlProdukRakitanBiayaPaket();
        $hs = new MdlHargaProduk();

        $oProp = $o->lookupByID($prodID)->result();
        $components = $pk->lookupbyPID($prodID)->result();
        $biayas = $pk->lookupBiayaByPID($prodID)->result();
//        showLast_query("biru");
        $tmpBahan = $o2->lookupAll()->result();

        $cacheBahan = array();
        if (sizeof($tmpBahan) > 0) {
            foreach ($tmpBahan as $rowB) {
                $cacheBahan[$rowB->id] = array(
                    "name" => $rowB->nama,
                    "satuan" => $rowB->satuan,
                );
            }
        }

        //hanya untuk reset, normalnya dimatikan
//        unset($_SESSION['PROED']);

        if (!isset($_SESSION['PROED'][$prodID])) {
            $_SESSION['PROED'][$prodID] = array();
        }
        if (!isset($_SESSION['PROED'][$prodID]['component'])) {
            $_SESSION['PROED'][$prodID]['component'] = array();
        }
        $_SESSION['PROED'][$prodID]['backLink'] = isset($_GET['backLink']) ? unserialize(base64_decode($_GET['backLink'])) : "";

//        arrPrint($components);
        if (sizeof($_SESSION['PROED'][$prodID]['component']) == 0) {
            if (sizeof($components) > 0) {
                foreach ($components as $row) {
                    if (isset($cacheBahan[$row->produk_dasar_id])) {
                        $_SESSION['PROED'][$prodID]['component'][$row->produk_dasar_id] = array(
                            "name" => $cacheBahan[$row->produk_dasar_id]['name'],
                            "satuan" => $cacheBahan[$row->produk_dasar_id]['satuan'],
                            "harga_old" => $row->nilai,
                            "harga" => $row->nilai,
                            "nilai" => $row->nilai,
                            "jml" => $row->jml,
                        );
                    }
                }
            }
        }

        //region build session biaya cost
        if (sizeof($biayas) > 0) {
            foreach ($biayas as $row) {
                $_SESSION['PROED'][$prodID]['cost'][$row->produk_dasar_id] = array(
                    "name" => $row->produk_dasar_nama,
                    "value" => $row->nilai,
                    "jml" => $row->jml,
                );
            }
        }
        //endregion

//
//        arrPrint($prodID);
//        arrPrint($biayas);
//        arrPrint($_SESSION['PROED'][$prodID]['cost']);

        // region logic syncronisasi
        $dbP_datas = array();
        if (sizeof($components) > 0) {
            foreach ($components as $row) {
                $dbP_datas[$row->produk_dasar_id][] = $row->jml;
                $dbP_datas[$row->produk_dasar_id][] = $row->nilai;
            }
        }
        $sP_datas = array();
        foreach ($_SESSION['PROED'][$prodID]['component'] as $bahanID => $bahanSpec) {
            $sP_datas[$bahanID][] = isset($bahanSpec["nilai"]) ? $bahanSpec["nilai"]: 0;
            $sP_datas[$bahanID][] = isset($bahanSpec["jml"]) ? $bahanSpec["jml"] : 1;
        }


        $condite = array("produk_id" => $prodID);
        $dBiayas = $ob->lookupByCondition($condite)->result();
//        showLast_query("biru");
        $dbRow = sizeof($dBiayas);
        $dbDatas = array();
        foreach ($dBiayas as $ii => $dBiaya) {
            $dbDatas[$dBiaya->biaya_id][] = $dBiaya->nilai;
            $dbDatas[$dBiaya->biaya_id][] = abs($dBiaya->jml);
        }

        $bRow = sizeof($biayas);
        $bDatas = array();
        foreach ($biayas as $i => $bRows) {
            $bDatas[$bRows->produk_dasar_id][] = $bRows->nilai;
            $bDatas[$bRows->produk_dasar_id][] = abs($bRows->jml);
        }
//
//        arrPrint($dbRow);
//        arrPrint($bRow);
//        arrPrint($components);
//

        // region cek komposisi session dan db biaya
        $sync = 0;
        if ($dbRow != $bRow) {
            $sync = 1;
        }
        else {
//            cekMerah("BAWAH...");
//            arrPrintKuning($dbDatas);
//            arrPrintPink($bDatas);
//
            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                $difs = array_diff($dbDatas, $bDatas);
                $sync = sizeof($difs) > 0 ? 1 : 0;
            }
            else {
                if (sizeof($dbDatas) > 0) {
                    foreach ($dbDatas as $bid => $dbspec) {
                        $bspec = isset($bDatas[$bid]) ? $bDatas[$bid] : array();
                        $difs = array_diff($dbDatas, $bDatas);
                        if (sizeof($difs) > 0) {
                            $sync = 1;
                            break;
                        }
                        else {
                            $sync = 0;
                        }
                    }
                }
            }

        }
        // endregion cek komposisi session dan db biaya


        $sync_produk = 0;
        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            // region cek komposisi produk session dan db produk
            $count_p_datas = count($dbP_datas);
            $count_s_datas = count($sP_datas);
            if ($count_p_datas != $count_s_datas) {
                $sync_produk = 1;
            }
            else {
                if (sizeof($dbP_datas) > 0) {
                    foreach ($dbP_datas as $bid => $dbspec) {
                        $bspec = isset($sP_datas[$bid]) ? $sP_datas[$bid] : array();
                        $difs = array_diff($dbspec, $bspec);
                        if (sizeof($difs) > 0) {
                            $sync_produk = 1;
                            break;
                        }
                        else {
                            $sync_produk = 0;
                        }
                    }
                }
            }
            // endregion
        }


        $btnLagi = "";
        if (sizeof($components) > 0) {
            if ($sync == 1) {
                $alerts = array(
                    "title" => "Syncronize",
//                    "html" => "there is some update on the standard cost<br>please do synchronize",
                    "html" => "",
                );
                $url_sync = base_url() . "ProductEditorPaket/syncKomposisiBiaya/$prodID";
                $btnLagi = $btnSync = "<button type='button' class='btn btn-danger' onclick=\"confirm_alert_result('Synchronize','standard costs will be updated to the latest data','$url_sync');\">Syncronize data</button>";
                $btnLagi .= swalAlert($alerts);
            }
            else {
                $btnLagi = "";
            }
        }


        // endregion

        $headers = array(
            "no" => "class='text-center bg-grey-2 text-uppercase'",
            "pID" => "class='text-center bg-grey-2 text-uppercase'",
            "Material" => "class='text-center bg-grey-2 text-uppercase'",
            "price" => "class='text-center bg-grey-2 text-uppercase'",
            "price BOM" => "class='text-center bg-grey-2 text-uppercase'",
            "Qty" => "class='text-center bg-grey-2 text-uppercase'",
            "uom" => "class='text-center bg-grey-2 text-uppercase'",
            "subtotal" => "class='text-center bg-grey-2 text-uppercase'",
            "rem" => "class='text-center bg-grey-2 text-uppercase'",
        );
        $fcHeaders = array(
            "no" => "class='text-center bg-grey-2 text-uppercase' width='40'",
            "Cost Name" => "class='text-center bg-grey-2 text-uppercase'",
            "Qty" => "class='text-center bg-grey-2 text-uppercase'",
            "value" => "class='text-center bg-grey-2 text-uppercase'",
        );
        $content = "";
        if (sizeof($_SESSION['PROED'][$prodID]['component']) > 0) {
            $arrBahanID = array();
            $arrBahanPrice = array();
            foreach ($_SESSION['PROED'][$prodID]['component'] as $bahanID => $bahanSpec) {
                $arrBahanID[$bahanID] = $bahanID;
            }
            if (sizeof($arrBahanID) > 0) {
                $hs->addFilter("jenis_value='hpp'");
                $hs->addFilter("cabang_id='" . CB_ID_PUSAT . "'");
                $hs->addFilter("produk_id in ('" . implode("','", $arrBahanID) . "')");
                $hsTmp = $hs->lookupAll()->result();
                if (sizeof($hsTmp) > 0) {
                    foreach ($hsTmp as $hsSpec) {
                        $arrBahanPrice[$hsSpec->produk_id] = $hsSpec->nilai;
                    }
                }
            }

            $btnAttr = "";
            $content .= "<table class='table table-striped' style='border: 0px solid red;background-color: transparent;'>";
            $content .= "<tr>";
            foreach ($headers as $header => $hAttr) {
                $content .= "<th $hAttr>$header</th>";
            }
            $content .= "</tr>";

            $no = 0;
            $cTab = 100;
            $subtotal = 0;
            foreach ($_SESSION['PROED'][$prodID]['component'] as $id => $eSpec) {
                // menambahan isi dari array eSpec

                if (!isset($_SESSION['PROED'][$prodID]['component'][$id]['harga'])) {
                    $_SESSION['PROED'][$prodID]['component'][$id]['harga_old'] = isset($arrBahanPrice[$id]) ? $arrBahanPrice[$id] : 0;
                    $harga_bom = $_SESSION['PROED'][$prodID]['component'][$id]['harga'] = isset($arrBahanPrice[$id]) ? $arrBahanPrice[$id] : 0;
                    // cekHijau("buat");
                }
                else {
                    // cekMerah("baca");
                    // $harga_bom = $_SESSION['PROED'][$prodID]['component'][$id]['harga'];
                    $harga_bom = $eSpec['harga'];
                }

                $eSpec['cost'] = isset($arrBahanPrice[$id]) ? $arrBahanPrice[$id] : 0;
                // $eSpec['subtotal'] = $eSpec['cost'] * $eSpec['jml'];

                $eSpec['subtotal'] = $harga_bom * $eSpec['jml'];
                $subtotal += $eSpec['subtotal'];

                $no++;
                $cTab++;
                $content .= "<tr>";
                $content .= "<td class='text-right valign-m' valign='middle' width='40'>$no</td>";
                $content .= "<td class='text-center' style='vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= $id;
                $content .= "</td>";
                $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= $eSpec['name'];
                if ($eSpec['cost'] == 0) {
//                    $content .= "<br><span style='font-style: italic;color: red;font-size: 12px;'>harga supplies belum ditentukan.</span>";
                }
                $content .= "</td>";

                $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= formatField("harga", $eSpec['cost']);
                $content .= "</td>";

                $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;width: 80px'>";
                // $content .= formatField("harga", $eSpec['harga']);
                $content .= "<input type='number' tabindex='$cTab' name='harga[]' value='" . (isset($eSpec['harga']) && $eSpec['harga'] * 1 > 0 ? $eSpec['harga'] * 1 : 0) . "'
                    onblur =\"top.$('#result').load('" . base_url() . "_productEditorPaket/addItem?sID=$prodID&bID=$id&harga='+this.value);\"
                    onchange =\"top.$('#result').load('" . base_url() . "_productEditorPaket/addItem?sID=$prodID&bID=$id&harga='+this.value);\"
                    class='form-control text-right'>";
                $content .= "</td>";
                $content .= "<td style='width: 70px;vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= "<input type='number' tabindex='$cTab' name='jml[]' onfocus='select()' value='" . $eSpec['jml'] . "'
                    onblur =\"top.$('#result').load('" . base_url() . "_productEditorPaket/addItem?sID=$prodID&bID=$id&jml='+this.value);\"
                    onchange =\"top.$('#result').load('" . base_url() . "_productEditorPaket/addItem?sID=$prodID&bID=$id&jml='+this.value);\"
                    class='form-control text-right'>";
                // $content .= "<input type='text' tabindex='$cTab'>";
                $content .= "</td>";

                $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= "<span style='text-transform: uppercase;'>" . $eSpec['satuan'] . "</span>";
                $content .= "</td>";

                $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= "<span style='text-transform: uppercase;'>" . formatField("harga", $eSpec['subtotal']) . "</span>";
                $content .= "</td>";


                $content .= "<td class='text-center valign-m'>";
                $content .= "<a class='text-red' href=# onClick =\"window.open_holdon();top.$('#result').load('" . base_url() . "_productEditorPaket/removeItem/$prodID/" . $id . "');\"><span class='glyphicon glyphicon-remove'></span></a>";
                $content .= "</td>";


                $content .= "</tr>";
            }
            // jumlah total bahan footer....
            $content .= "<tr>";
            $content .= "<td colspan='7' class='text-right text-bold text-uppercase'>Estimasi Bahan</td>";
            $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;'>";
            $content .= "<span style='text-transform: uppercase;font-weight: bold;'>" . formatField("harga", $subtotal) . "</span>";
            $content .= "</td>";
            $content .= "<td class='text-center'>-</td>";
            $content .= "</tr>";

            $content .= "</table class='table'>";

            //===============================================================

            $content .= "<div class='margin-top-10 table-responsive'>";
            $content .= "<table class='table display'>";
            $content .= "<caption class='text-bold text-grey font-size-1-2'>Standart Cost Package</caption>";
            $content .= "<tr>";
            foreach ($fcHeaders as $header => $hAttr) {
                $content .= "<th $hAttr>$header</th>";
            }
            $content .= "</tr>";

            if (isset($_SESSION['PROED'][$prodID]['cost'])) {
                $no = 0;
                $sumCost = 0;
                foreach ($_SESSION['PROED'][$prodID]['cost'] as $costItem) {
                    // arrPrint($costItem);
                    $no++;
                    $content .= "<tr class='text-capitalize'>";
                    $content .= "<td class='text-right'>$no</td>";
                    $content .= "<td class='text-left'>" . $costItem['name'] . "</td>";
                    $content .= "<td class='text-left'>" . formatField('harga', $costItem['jml']) . "</td>";
                    $content .= "<td class='text-right'>" . formatField('harga', $costItem['value'] * $costItem['jml']) . "</td>";
                    $content .= "</tr>";

                    $sumCost += $costItem['value'] * $costItem['jml'];
                }

                $totalCostKomposisi = $sumCost + $subtotal;

                $sumCost_f = formatField("harga", $sumCost);
                $totalCostKomposisi_f = formatField("harga", $totalCostKomposisi);

                $content .= "<tr class='bg-grey-1'>";
                $content .= "<th class='text-right text-uppercase' colspan='3'>jumlah standat cost</th>";
                $content .= "<th class='text-right'>$sumCost_f</th>";
                $content .= "</tr>";

                $content .= "<tr class='bg-grey-1'>";
                $content .= "<th class='text-right text-uppercase' colspan='3'>total estimasi bom</th>";
                $content .= "<th class='text-right'>$totalCostKomposisi_f</th>";
                $content .= "</tr>";
            }
            else {
                // $content .= "<caption class='no-margin'>tidak ada data, harap di setUp terlebih dahulu via login holding</caption>";
                $content .= "<tr>";
                $content .= "<td class='text-center text-red' colspan='3'>belum ada data <span class='font-size-1-2'>Standart Cost By Product</span>, harap di setUp terlebih dahulu via login holding</td>";
                $content .= "</tr>";

                $btnAttr = "disabled";
            }


            $content .= "</table>";
            $content .= "</div>";


            if ((sizeof($_SESSION['PROED'][$prodID]['component']) != sizeof($components)) || ($sync_produk == 1)) {

                $content .= "<div class='alert alert-danger margin-top-10 text-center' sstyle='background-color: yellow;'>";
                $content .= "<span class='blink text-renggang-5 text-bold'>Perubahan Components belum disimpan.</span>";
                $content .= "</div>";

            }

        }
        else {
            $content .= "<div class='row text-center' style='border: 0px solid green;'>";
            $content .= "<h2><small>Komposisi produk </small><p class='text-red'>" . $oProp[0]->nama . "</p> <small>belum ditentukan</small></h2>";
            $content .= "<p class='text-danger'>Silahkan pilih material yang diperlukan dari kolom sebelah kiri</p>";
            $content .= "</div>";

            $btnAttr = "disabled";
        }


        $anu = array(
            "mode" => "edit",
            "content" => $content,
            //            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2)
        );
        $data = array(
            "mode" => "edit",
            "content" => $content,
            "btnAttr" => $btnAttr,
            "btnLagi" => $btnLagi,
            //            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            "anu" => $anu,
        );
        $this->load->view("editor_paket", $data);
    }

    /* ====================================================================
     * editor komposisi produk rakitan
     * ====================================================================*/
    public function save()
    {

        $prodID = $this->uri->segment(3);

        // arrPrint($_SESSION['PROED']);
        // mati_disini(__LINE__);
        $this->load->model("Mdls/" . 'MdlProdukKomposisiPaket');
        $this->load->model("Mdls/" . 'MdlProdukRakitanBiayaPaket');
        $this->load->model("Mdls/" . 'MdlProduk_Paket_Project');
        $pk0 = New MdlProdukKomposisiPaket();
        $pk = New MdlProdukKomposisiPaket();
        $prb = New MdlProdukRakitanBiayaPaket();
        $pr = New MdlProduk_Paket_Project();

        //nama
        $pr->addFilter("id='$prodID'");
        $tmpNamaProduk = $pr->lookupAll()->result();

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
                "trash" => 0,
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
                    "produk_nama" => $tmpNamaProduk[0]->nama,
                    "produk_dasar_id" => $bahanID,
                    "produk_dasar_nama" => $eSpec['name'],
                    "satuan_nama" => $eSpec['satuan'],
                    "jml" => $eSpec['jml'],
                    "nilai" => $eSpec['harga'],
                );
                $pk->addData($arrData);
                cekHere($this->db->last_query());
                $total_komponen += ($eSpec['harga'] * $eSpec['jml']);
            }
            $total_cost = 0;
            foreach ($_SESSION['PROED'][$prodID]['cost'] as $bahanID => $eSpec) {
                $arrData = array(
                    "produk_id" => $prodID,
                    "produk_nama" => $tmpNamaProduk[0]->nama,
                    "produk_dasar_id" => $bahanID,
                    "produk_dasar_nama" => $eSpec['name'],
                    "nilai" => $eSpec['value'],
//                    "nilai" => $eSpec['value'] * $eSpec['jml'],
                    "jml" => $eSpec['jml'],
                    "jenis" => "biaya",
                );

                $total_cost += $eSpec['value'];
                $pk->addData($arrData);
                cekKuning($this->db->last_query());
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
            "jenis" => "item_rakitan_paket",
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


//        mati_disini("BERHASIL SAVE <br>".__METHOD__ . " blom commit @" . __LINE__);
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

        unset($_SESSION['PROED']);

        $actionTarget = "
            top.BootstrapDialog.closeAll();
            top.BootstrapDialog.show({
                title:'Modify Product ',
    //      size: BootstrapDialog.SIZE_WIDE,
                cssClass: 'edit-dialog',
                message: " . '$' . "('<div></div>').load('" . $backLink . "'),
                draggable:true,
                closable:true,
            });";

        $actionTarget = "top.$('#result2').attr('src', '" . base_url() . "ProductEditorPaket/edit?attached=1&sID=$prodID&backlink=$backLink');";

        echo "<html>";
        echo "<head>";
        echo "<script src=\"" . cdn_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
        echo "</head>";
        echo "<body onload=\"$actionTarget\">";
        echo "</body>";
    }

    public function syncKomposisiBiaya()
    {

        $this->load->model("Mdls/MdlProdukKomposisiPaket");
        $this->load->model("Mdls/MdlProdukRakitanBiayaPaket");
        // $this->load->model("Mdls/MdlProdukRakitanBiaya");
        $km = new MdlProdukKomposisiPaket();
        $by = new MdlProdukRakitanBiayaPaket();

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
            $datas[$biayaItems->biaya_id]["jml"] = $biayaItems->jml;
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
        $actionTarget = "top.$('#result2').attr('src', '" . base_url() . "ProductEditorPaket/edit?attached=1&sID=$produk_id&backlink=$backLink');";
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

}