<?php

class CrmLama extends MX_Controller
{
    public function __construct()
    {
        $this->modul_path = base_url() . "penjualan/";
        $this->jenisTr = "4666";
        $this->jenisTrs = array("582spo", "382spo");
        $this->default_limit = 100;

        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        validateUserSession($this->session->login['id']);
    }

    public function requestproduk()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $ps->setSortBy($sortings);
        $ps->setJenisTr($this->jenisTr);
        // $ps->setJenisTr("582");
        $src = $ps->callMovementProduk("persediaan_produk");
        $masterData = $src['data'];
        // showLast_query("kuning");
        // cekBiru(sizeof($masterData));
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "dtime"          => array(
                "label"  => "tanggal approve",
                "format" => "formatField_he_format",
            ),
            "kode"           => array(
                "label" => "kode",
            ),
            "nama"           => array(
                "label" => "produk",
            ),
            "no_part"        => array(
                "label" => "no part",
            ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),
            "nomer"          => array(
                "label"  => "nomer",
                "format" => "formatField_he_format",
            ),
            "suppliers_nama" => array(
                "label" => "vendor",
            ),
            "m_shippingDate" => array(
                "label" => "tgl request",
            ),
            // "m_shippingDateOn" => array(
            //     "label" => "tgl grn",
            // ),
            // --
            "i_hpp"          => array(
                "label"      => "hpp",
                "format"     => "formatField_he_format",
                "format_key" => "hpp",
                "attr"       => "class='text-right'",
            ),
            "i_jml"          => array(
                "label"  => "jumlah request",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
            ),
            // "i_jml_grn"      => array(
            //     "label"  => "jumlah grn",
            //     "format" => "formatField_he_format",
            // ),
            "i_sub_hpp"      => array(
                "label"  => "nilai",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
            ),

        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "default",
            "title"       => "laporan pembelian $strDate",
            "subTitle"    => "Raw Data request pembelian",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "466",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function grnproduk()
    {
        $this->jenisTr = "4667";
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $ps = new ComRekeningPembantuProduk();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $ps->setSortBy($sortings);
        $ps->setJenisTr($this->jenisTr);
        // $ps->setJenisTr("582");
        $src = $ps->callMovementProduk("persediaan_produk");
        $masterData = $src['data'];
        // showLast_query("kuning");
        // cekBiru(sizeof($masterData));
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "dtime"          => array(
                "label"  => "tanggal",
                "format" => "formatField_he_format",
            ),
            "kode"           => array(
                "label" => "kode",
            ),
            "nama"           => array(
                "label" => "produk",
            ),
            "no_part"        => array(
                "label" => "no part",
            ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),
            "nomer"          => array(
                "label"  => "nomer",
                "format" => "formatField_he_format",
            ),
            "suppliers_nama" => array(
                "label" => "vendor",
            ),
            // --
            "harga"          => array(
                "label"  => "hpp",
                "format" => "formatField_he_format",
            ),
            "qty_debet"      => array(
                "label"  => "jumlah",
                "format" => "formatField_he_format",
            ),
            "debet"          => array(
                "label"  => "nilai",
                "format" => "formatField_he_format",
            ),

        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "default",
            "title"       => "laporan pembelian $strDate",
            "subTitle"    => "raw data GRN",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "466",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function produk()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            // $maxLimit = 50;
            $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = $this->jenisTrs;
        $ps->setSortBy($sortings);
        $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src_000 = $ps->callCacheProduk("persediaan_produk");
        $reqData_000 = $src_000['data'];
        // arrPrintKuning($reqData_000);
        $arrMaster_id = array();
        foreach ($reqData_000 as $item) {
            $master_id_000 = $item['582spo_master_id'];

            $arrMaster_id[] = $master_id_000;
        }
        asort($arrMaster_id);
        $minMaster_id = min($arrMaster_id);
        $maxMaster_id = max($arrMaster_id);
        // cekBiru($arrMaster_id);
        // cekMerah());
        // cekMerah();
        // matiHere();
        if (isset($_GET['date1']) && sizeof($arrMaster_id) > 0) {
            $condites = array(
                "master_id >=" => $minMaster_id,
                "master_id <=" => $maxMaster_id,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }

        $jenisTr = array(
            "582spo",
            "582so",
            "582pkd",
            "582spd",
            // "967"
        );
        $ps->setSortBy($sortings);
        $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src = $ps->callCacheProduk("persediaan_produk");
        $reqData = $src['data'];
        // showLast_query("kuning");
        // cekBiru(sizeof($reqData[0]));
        // arrPrintKuning($reqData);

        // foreach ($reqData as $reqDatum) {
        //     $d_id = $reqDatum['id'];
        //     $req_id = $reqDatum['transaksi_id'];
        //     $prod_id = $reqDatum['extern_id'];
        //     $master_id = $reqDatum['master_id'];
        //     $rekening = $reqDatum['rekening'];
        //     $debet = $reqDatum['debet'];
        //     $kredit = $reqDatum['kredit'];
        //     $qty_debet = $reqDatum['qty_debet'];
        //     $qty_kredit = $reqDatum['qty_kredit'];
        //
        //     $kolomTambahan['debet_' . $rekening] = $debet;
        //     $kolomTambahan['kredit_' . $rekening] = $kredit;
        //     $kolomTambahan['debet_' . $rekening] = $debet;
        //     $kolomTambahan['debet_' . $rekening] = $debet;
        //
        //     // $req_datas[$master_id][$req_id][$prod_id] = $reqDatum + $kolomTambahan;
        //     // $req_datas[$master_id][$d_id][$prod_id] = $reqDatum + $kolomTambahan;
        //     $req_datas[$master_id][$d_id][$prod_id] = $reqDatum;
        // }

        // arrPrintPink($req_datas);
        // matiHere();

        // $arr = array();
        // foreach ($req_datas as $mst_id => $req_data_1) {
        //     // cekBiru($mst_id);
        //     foreach ($req_data_1 as $reke => $req_data_2) {
        //         // cekKuning($reke);
        //         foreach ($req_data_2 as $pro_id => $item) {
        //
        //             $q_mainDatas[$mst_id][$pro_id] = $item;
        //             $q_debet = $item['qty_debet_lap'];
        //             $q_kredit = $item['qty_kredit_lap'];
        //             $q_rek = $item['rekening'];
        //             // $q_transaksi_tipe = $item['transaksi_tipe'];
        //
        //             if (!isset($arr[$mst_id][$pro_id]['debet_' . $q_rek])) {
        //                 $arr[$mst_id][$pro_id]['debet_' . $q_rek] = 0;
        //             }
        //             $arr[$mst_id][$pro_id]['debet_' . $q_rek] += $q_debet;
        //
        //             if (!isset($arr[$mst_id][$pro_id]['kredit_' . $q_rek])) {
        //                 $arr[$mst_id][$pro_id]['kredit_' . $q_rek] = 0;
        //             }
        //             $arr[$mst_id][$pro_id]['kredit_' . $q_rek] += $q_kredit;
        //
        //             // cekMerah("$q_rek : $pro_id - $q_debet - $q_kredit");
        //
        //             $arr[$mst_id][$pro_id]['dtime_' . $q_rek] = $item['dtime'];
        //         }
        //     }
        //
        // }
        // $grnData = $arr;
        // arrPrintWebs($arr);

        /* -----------------------------------------------------------------------------------------------------------
         * data penerimaan
         * -----------------------------------------------------------------------------------------------------------*/
        // $this->jenisTr = "4667";
        // // $this->load->helper("he_mass_table");
        // // $this->load->model("Coms/ComRekeningPembantuProduk");
        // // $ps = new ComRekeningPembantuProduk();
        //
        // $strDate = "";
        // if (isset($_GET['date1'])) {
        //     $condites = array(
        //         "date(dtime)>=" => $get_date1,
        //         "date(dtime)<=" => $get_date2,
        //     );
        //     $this->db->where($condites);
        //
        //     $strDate .= formatField_he_format("fulldate", $get_date1);
        //     $strDate .= " - ".formatField_he_format("fulldate", $get_date2);
        // }
        // else {
        //     $maxLimit = $this->default_limit;
        //     $this->db->limit($maxLimit);
        //
        //     $strDate = "$maxLimit data terakhir";
        // }
        //
        // $sortings = array(
        //     "kolom" => "id",
        //     "mode" => "desc",
        // );
        // $ps->setSortBy($sortings);
        // $ps->setJenisTr($this->jenisTr);
        // // $ps->setJenisTr("582");
        // $src = $ps->callMovementProduk("persediaan_produk");
        // $grnData = $src['data'];
        // cekBiru(sizeof($grnData[0]));
        // // arrPrintPink($grnData);

        //
        // foreach ($grnData as $mst_id => $item_0) {
        //     // $ref_id = $item['referensi_id'];
        //     // $prod_id = $item['extern_id'];
        //     // $grn_id = $item['transaksi_id'];
        //     foreach ($item_0 as $prd_id => $item) {
        //
        //         $req_data = isset($q_mainDatas[$mst_id][$prd_id]) ? $q_mainDatas[$mst_id][$prd_id] : array();
        //         $gabunganData[] = $req_data + $item;
        //     }
        // }
        //
        // cekMerah(sizeof($gabunganData[0]['505'][0]));
        // arrPrintKuning($gabunganData[0]['505'][0]);
        // arrPrintKuning($gabunganData);
        $masterData = $reqData;
        // matiHere(__LINE__);
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // cekHijau(sizeof($masterData));
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "582spo_dtime"          => array(
                "label"      => "tanggal",
                "format"     => "formatField_he_format",
                "format_key" => "dtime",
            ),
            "kode"                  => array(
                "label" => "kode",
            ),
            "nama"                  => array(
                "label" => "produk",
            ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),
            "582spo_oleh_nama"      => array(
                "label" => "salesman",
            ),
            "582spo_customer_nama"  => array(
                "label" => "konsumen",
            ),
            // "582spo__step_1_olehName" => array(
            //     "label" => "salesman",
            // ),
            "582spo__step_1_nomer"  => array(
                "label"      => "nomer spo",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            // "582so__step_2_nomer"          => array(
            //     "label"  => "nomer so",
            //     "format" => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582pkd__step_3_nomer"          => array(
            //     "label"  => "nomer pre pl",
            //     "format" => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582spd__step_4_nomer"          => array(
            //     "label"  => "nomer pl",
            //     "format" => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),


            // "dtime_582pkd"   => array(
            //     "label" => "tgl dikirim",
            //     "format" => "formatField_he_format",
            //     "format_key" => "dtime",
            // ),
            //
            // // --
            // // "i_hpp"          => array(
            // //     "label"      => "hpp",
            // //     "format"     => "formatField_he_format",
            // //     "format_key" => "hpp",
            // //     "attr"       => "class='text-right'",
            // // ),
            // // "i_jml"          => array(
            // //     "label"  => "jumlah request",
            // //     "format" => "formatField_he_format",
            // //     "attr"   => "class='text-right'",
            // // ),
            // // "i_jml_grn"      => array(
            // //     "label"  => "jumlah grn",
            // //     "format" => "formatField_he_format",
            // // ),
            "582spo_harga"          => array(
                "label"      => "harga",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "582spo_debet_lap"      => array(
                "label"      => "nilai order",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
            ),
            "582pkd_kredit_lap"     => array(
                "label"      => "outstading",
                "format"     => "formatField_he_format",
                "format_key" => "debet",
                "attr"       => "class='text-right bg-danger'",
            ),
            "582spd_debet_lap"      => array(
                "label"      => "shipment",
                "format"     => "formatField_he_format",
                "format_key" => "debet",
                "attr"       => "class='text-right bg-success'",
            ),
            // ///-----------------
            // ///
            //
            "582spo_qty_debet_lap"  => array(
                "label"      => "sales order",
                "format"     => "formatField_he_format",
                "format_key" => "debet",
                "attr"       => "class='text-right bg-warning'",
            ),

            // "582spo_qty_kredit_lap"      => array(
            //     "label"  => "menunggu acc",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            // ),
            "582so_qty_kredit_lap"  => array(
                "label"      => "menunggu approve",
                "format"     => "formatField_he_format",
                "format_key" => "debet",
                "attr"       => "class='text-right'",
            ),
            "582so_qty_debet_lap"   => array(
                "label"      => "approved",
                "format"     => "formatField_he_format",
                "format_key" => "debet",
                "attr"       => "class='text-right'",
            ),
            "582pkd_qty_kredit_lap" => array(
                "label"      => "outstanding",
                "format"     => "formatField_he_format",
                "format_key" => "debet",
                "attr"       => "class='text-right bg-danger'",
            ),
            // "582pkd_qty_debet_lap"  => array(
            //     "label"  => "siap kirim",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            // ),
            "582spd_qty_debet_lap"  => array(
                "label"      => "shipment",
                "format"     => "formatField_he_format",
                "format_key" => "debet",
                "attr"       => "class='text-right bg-success'",
            ),
            // "debet_582spd"  => array(
            //     "label"  => "shipment",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            // ),
            "582so_rejected"        => array(
                "label"  => "reject so",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
            ),
            "582pkd_rejected"       => array(
                "label"  => "reject out",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
            ),
            // "closed"        => array(
            //     "label"  => "fullfill",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            // ),
            // "debet_982"     => array(
            //     "label"  => "request return",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            // ),
            // "debet_967" => array(
            //     "label" => "approve return",
            //     "format" => "formatField_he_format",
            //     "attr" => "class='text-right'",
            // ),
        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "default",
            "title"       => "Aktifitas SO ",
            "subTitle"    => "Raw Data CRM",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            "sum_satu"    => base_url() . "laporan/Crm/produkperseller" . "$strGet",
            "sum_dua"     => base_url() . "laporan/Crm/produkpermaster" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function produkraw()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            // $maxLimit = 50;
            $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = "582spo";
        $ps->setSortBy($sortings);
        $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src_000 = $ps->callCacheProduk("persediaan_produk");
        $reqData_000 = $src_000['data'];
        // arrPrintKuning($reqData_000);
        $arrMaster_id = array();
        foreach ($reqData_000 as $item) {
            $master_id_000 = $item['582spo_master_id'];

            $arrMaster_id[] = $master_id_000;
        }
        asort($arrMaster_id);
        $minMaster_id = min($arrMaster_id);
        $maxMaster_id = max($arrMaster_id);
        // cekBiru($arrMaster_id);
        // cekMerah());
        // cekMerah();
        // matiHere();
        if (isset($_GET['date1']) && sizeof($arrMaster_id) > 0) {
            $condites = array(
                "master_id >=" => $minMaster_id,
                "master_id <=" => $maxMaster_id,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }

        $jenisTr = array(
            "582spo",
            "582so",
            "582pkd",
            "582spd",
            // "967"
        );
        $ps->setSortBy($sortings);
        $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src = $ps->callCacheProduk("persediaan_produk");
        $reqData = $src['data'];
        // showLast_query("kuning");
        // cekBiru(sizeof($reqData[0]));
        // arrPrintKuning($reqData);
        /* --------------------------------------------------------------------------------------------------
         *peparasi data harus 3 step
         * #1 pengumpulan data transaksi (main)
         * --------------------------------------------------------------------------------------------------*/
        $olahan = array();
        foreach ($reqData as $masterDatum) {
            $spo_oleh_id = isset($masterDatum['582spo_oleh_id']) ? $masterDatum['582spo_oleh_id'] : "";
            $spo_oleh_nama = isset($masterDatum['582spo_oleh_nama']) ? $masterDatum['582spo_oleh_nama'] : "";
            $master_id = isset($masterDatum['582spo_master_id']) ? $masterDatum['582spo_master_id'] : "";

            $olahan[$master_id] = $masterDatum;
        }
        /* --------------------------------------------------------------------------------------------------
         * #2 membuat tambahan kolom summary
         * --------------------------------------------------------------------------------------------------*/
        foreach ($reqData as $reqDatum) {

            $spo_oleh_id = isset($reqDatum['582spo_oleh_id']) ? $reqDatum['582spo_oleh_id'] : "";
            $spo_debet_lap = isset($reqDatum['582spo_debet_lap']) ? $reqDatum['582spo_debet_lap'] : "";
            $spo_kredit_lap = isset($reqDatum['582spo_kredit_lap']) ? $reqDatum['582spo_kredit_lap'] : "";
            $spo_master_id = isset($reqDatum['582spo_master_id']) ? $reqDatum['582spo_master_id'] : "";

            if (!isset($req_datas[$spo_master_id]['sum_spo_debet'])) {
                $req_datas[$spo_master_id]['sum_spo_debet'] = 0;
            }
            $req_datas[$spo_master_id]['sum_spo_debet'] += $spo_debet_lap;

            if (!isset($req_datas[$spo_master_id]['sum_spo_kredit'])) {
                $req_datas[$spo_master_id]['sum_spo_kredit'] = 0;
            }
            $req_datas[$spo_master_id]['sum_spo_kredit'] += $spo_kredit_lap;
            // ------------------------------------------
            $spd_debet_lap = isset($reqDatum['582spd_debet_lap']) ? $reqDatum['582spd_debet_lap'] : 0;
            $spd_kredit_lap = isset($reqDatum['582spd_kredit_lap']) ? $reqDatum['582spd_kredit_lap'] : 0;

            if (!isset($req_datas[$spo_master_id]['sum_spd_debet'])) {
                $req_datas[$spo_master_id]['sum_spd_debet'] = 0;
            }
            $req_datas[$spo_master_id]['sum_spd_debet'] += $spd_debet_lap;

            if (!isset($req_datas[$spo_master_id]['sum_spd_kredit'])) {
                $req_datas[$spo_master_id]['sum_spd_kredit'] = 0;
            }
            $req_datas[$spo_master_id]['sum_spd_kredit'] += $spd_kredit_lap;
            // ------------------------------------------
            $pkd_debet_lap = isset($reqDatum['582pkd_debet_lap']) ? $reqDatum['582pkd_debet_lap'] : 0;
            $pkd_kredit_lap = isset($reqDatum['582pkd_kredit_lap']) ? $reqDatum['582pkd_kredit_lap'] : 0;

            if (!isset($req_datas[$spo_master_id]['sum_pkd_debet'])) {
                $req_datas[$spo_master_id]['sum_pkd_debet'] = 0;
            }
            $req_datas[$spo_master_id]['sum_pkd_debet'] += $pkd_debet_lap;

            if (!isset($req_datas[$spo_master_id]['sum_pkd_kredit'])) {
                $req_datas[$spo_master_id]['sum_pkd_kredit'] = 0;
            }
            $req_datas[$spo_master_id]['sum_pkd_kredit'] += $pkd_kredit_lap;
            // ------------------------------------------
        }

        // arrPrintPink($req_datas);
        // matiHere();

        /* --------------------------------------------------------------------------------------------------
          * #3 pengumpulan data menjadi data siap tempur
          * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($olahan as $master_id => $itemParam) {
            // $customer_id = $itemParam['m_customerID'];
            $hasilOlahan_1[$master_id] = $itemParam + $req_datas[$master_id];
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $hasilOlahan_1;
        //
        // cekMerah(sizeof($gabunganData[0]['505'][0]));
        // arrPrintKuning($gabunganData[0]['505'][0]);
        // arrPrintKuning($gabunganData);
        // $masterData = $reqData;
        // matiHere(__LINE__);
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // cekHijau(sizeof($masterData));
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "582spo_oleh_nama"     => array(
                "label" => "salesman",
            ),
            "582spo_customer_nama" => array(
                "label" => "konsumen",
            ),
            "582spo__step_1_nomer" => array(
                "label"      => "spo",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            "582so__step_2_nomer"  => array(
                "label"      => "soa",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            "582pkd__step_3_nomer" => array(
                "label"      => "pkd",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            "582spd__step_4_nomer" => array(
                "label"      => "spd",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),

            "sum_spo_debet"  => array(
                "label"   => "order",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_pkd_kredit" => array(
                "label"   => "outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_spd_debet"  => array(
                "label"   => "shipment",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung",
            "title"       => "laporan per SO $strDate",
            "subTitle"    => "Raw Data CRM",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "permaster",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function produkperseller()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            // $maxLimit = 50;
            $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = "582spo";
        $ps->setSortBy($sortings);
        $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src_000 = $ps->callCacheProduk("persediaan_produk");
        $reqData_000 = $src_000['data'];
        // arrPrintKuning($reqData_000);
        $arrMaster_id = array();
        foreach ($reqData_000 as $item) {
            $master_id_000 = $item['582spo_master_id'];

            $arrMaster_id[] = $master_id_000;
        }
        asort($arrMaster_id);
        $minMaster_id = min($arrMaster_id);
        $maxMaster_id = max($arrMaster_id);
        // cekBiru($arrMaster_id);
        // cekMerah());
        // cekMerah();
        // matiHere();
        if (isset($_GET['date1']) && sizeof($arrMaster_id) > 0) {
            $condites = array(
                "master_id >=" => $minMaster_id,
                "master_id <=" => $maxMaster_id,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }

        $jenisTr = array(
            "582spo",
            "582so",
            "582pkd",
            "582spd",
            // "967"
        );
        $ps->setSortBy($sortings);
        $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src = $ps->callCacheProduk("persediaan_produk");
        $reqData = $src['data'];
        // showLast_query("kuning");
        // cekBiru(sizeof($reqData[0]));
        // arrPrintKuning($reqData);
        /* --------------------------------------------------------------------------------------------------
         *peparasi data harus 3 step
         * #1 pengumpulan data transaksi (main)
         * --------------------------------------------------------------------------------------------------*/
        $olahan = array();
        $olahanSpo = array();
        foreach ($reqData as $masterDatum) {
            $spo_master_id = isset($masterDatum['582spo_master_id']) ? $masterDatum['582spo_master_id'] : "";
            $so_jenis = isset($masterDatum['582so_rekening']) ? $masterDatum['582so_rekening'] : "";
            $so_master_id = isset($masterDatum['582so_master_id']) ? $masterDatum['582so_master_id'] : "";
            $spo_oleh_id = isset($masterDatum['582spo_oleh_id']) ? $masterDatum['582spo_oleh_id'] : "";
            $spo_oleh_nama = isset($masterDatum['582spo_oleh_nama']) ? $masterDatum['582spo_oleh_nama'] : "";
            // $master_id = $masterDatum['master_id'];

            $olahan[$spo_oleh_id] = $masterDatum;
            $olahanSpo[$spo_oleh_id][] = $spo_master_id;
            if ($so_jenis == "582so") {

                $olahanSo[$spo_oleh_id][] = $so_master_id;
            }
        }
        /* --------------------------------------------------------------------------------------------------
         * #2 membuat tambahan kolom summary
         * --------------------------------------------------------------------------------------------------*/
        foreach ($reqData as $reqDatum) {

            $spo_oleh_id = isset($reqDatum['582spo_oleh_id']) ? $reqDatum['582spo_oleh_id'] : "";
            $spo_debet_lap = isset($reqDatum['582spo_debet_lap']) ? $reqDatum['582spo_debet_lap'] : "";
            $spo_kredit_lap = isset($reqDatum['582spo_kredit_lap']) ? $reqDatum['582spo_kredit_lap'] : "";

            if (!isset($req_datas[$spo_oleh_id]['sum_spo_debet'])) {
                $req_datas[$spo_oleh_id]['sum_spo_debet'] = 0;
            }
            $req_datas[$spo_oleh_id]['sum_spo_debet'] += $spo_debet_lap;

            if (!isset($req_datas[$spo_oleh_id]['sum_spo_kredit'])) {
                $req_datas[$spo_oleh_id]['sum_spo_kredit'] = 0;
            }
            $req_datas[$spo_oleh_id]['sum_spo_kredit'] += $spo_kredit_lap;
            // ------------------------------------------
            $spd_debet_lap = isset($reqDatum['582spd_debet_lap']) ? $reqDatum['582spd_debet_lap'] : 0;
            $spd_kredit_lap = isset($reqDatum['582spd_kredit_lap']) ? $reqDatum['582spd_kredit_lap'] : 0;

            if (!isset($req_datas[$spo_oleh_id]['sum_spd_debet'])) {
                $req_datas[$spo_oleh_id]['sum_spd_debet'] = 0;
            }
            $req_datas[$spo_oleh_id]['sum_spd_debet'] += $spd_debet_lap;

            if (!isset($req_datas[$spo_oleh_id]['sum_spd_kredit'])) {
                $req_datas[$spo_oleh_id]['sum_spd_kredit'] = 0;
            }
            $req_datas[$spo_oleh_id]['sum_spd_kredit'] += $spd_kredit_lap;
            // ------------------------------------------   
            $pkd_debet_lap = isset($reqDatum['582pkd_debet_lap']) ? $reqDatum['582pkd_debet_lap'] : 0;
            $pkd_kredit_lap = isset($reqDatum['582pkd_kredit_lap']) ? $reqDatum['582pkd_kredit_lap'] : 0;

            if (!isset($req_datas[$spo_oleh_id]['sum_pkd_debet'])) {
                $req_datas[$spo_oleh_id]['sum_pkd_debet'] = 0;
            }
            $req_datas[$spo_oleh_id]['sum_pkd_debet'] += $pkd_debet_lap;

            if (!isset($req_datas[$spo_oleh_id]['sum_pkd_kredit'])) {
                $req_datas[$spo_oleh_id]['sum_pkd_kredit'] = 0;
            }
            $req_datas[$spo_oleh_id]['sum_pkd_kredit'] += $pkd_kredit_lap;
            // ------------------------------------------    
        }

        // arrPrintPink($req_datas);
        // matiHere();

        /* --------------------------------------------------------------------------------------------------
          * #3 pengumpulan data menjadi data siap tempur
          * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($olahan as $seller_id => $itemParam) {
            // $customer_id = $itemParam['m_customerID'];
            $itemParam['582spo_jml'] = sizeof($olahanSpo[$seller_id]);
            $itemParam['582so_jml'] = isset($olahanSo[$seller_id]) ? sizeof($olahanSo[$seller_id]) : 0;
            $hasilOlahan_1[$seller_id] = $itemParam + $req_datas[$seller_id];
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $hasilOlahan_1;
        //
        // cekMerah(sizeof($gabunganData[0]['505'][0]));
        // arrPrintKuning($gabunganData[0]['505'][0]);
        // arrPrintKuning($gabunganData);
        // $masterData = $reqData;
        // matiHere(__LINE__);
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // cekHijau(sizeof($masterData));
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "582spo_oleh_nama" => array(
                "label" => "salesman",
                // "format"     => "formatField_he_format",
                // "format_key" => "dtime",
                "links" => array(
                    "target" => "laporan/Crm/produkraw",
                    "title"  => "Transaksi per customer",
                    "key"    => "582spo_oleh_id",
                ),
            ),
            // "kode"                  => array(
            //     "label" => "kode",
            // ),
            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),

            "582spo_jml"     => array(
                "label"  => "jml order",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
                // "summary"   => true,
            ),
            "sum_spo_debet"  => array(
                "label"   => "order",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            // "582so_jml"       => array(
            //     "label"  => "jml outstanding",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     // "summary"   => true,
            // ),
            "sum_pkd_kredit" => array(
                "label"   => "outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_spd_debet"  => array(
                "label"   => "shipment",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung",
            "title"       => "laporan per salesman $strDate",
            "subTitle"    => "Raw Data CRM",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "strGet"      => $strGet,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function produkpermaster()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            // $maxLimit = 50;
            $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = "582spo";
        $ps->setSortBy($sortings);
        $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src_000 = $ps->callCacheProduk("persediaan_produk");
        $reqData_000 = $src_000['data'];
        // arrPrintKuning($reqData_000);
        $arrMaster_id = array();
        foreach ($reqData_000 as $item) {
            $master_id_000 = $item['582spo_master_id'];

            $arrMaster_id[] = $master_id_000;
        }
        asort($arrMaster_id);
        $minMaster_id = min($arrMaster_id);
        $maxMaster_id = max($arrMaster_id);
        // cekBiru($arrMaster_id);
        // cekMerah());
        // cekMerah();
        // matiHere();
        if (isset($_GET['date1']) && sizeof($arrMaster_id) > 0) {
            $condites = array(
                "master_id >=" => $minMaster_id,
                "master_id <=" => $maxMaster_id,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }

        $jenisTr = array(
            "582spo",
            "582so",
            "582pkd",
            "582spd",
            // "967"
        );
        $ps->setSortBy($sortings);
        $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src = $ps->callCacheProduk("persediaan_produk");
        $reqData = $src['data'];
        // showLast_query("kuning");
        // cekBiru(sizeof($reqData[0]));
        // arrPrintKuning($reqData);
        /* --------------------------------------------------------------------------------------------------
         *peparasi data harus 3 step
         * #1 pengumpulan data transaksi (main)
         * --------------------------------------------------------------------------------------------------*/
        $olahan = array();
        foreach ($reqData as $masterDatum) {
            $spo_oleh_id = isset($masterDatum['582spo_oleh_id']) ? $masterDatum['582spo_oleh_id'] : "";
            $spo_oleh_nama = isset($masterDatum['582spo_oleh_nama']) ? $masterDatum['582spo_oleh_nama'] : "";
            $master_id = isset($masterDatum['582spo_master_id']) ? $masterDatum['582spo_master_id'] : "";

            $olahan[$master_id] = $masterDatum;
        }
        /* --------------------------------------------------------------------------------------------------
         * #2 membuat tambahan kolom summary
         * --------------------------------------------------------------------------------------------------*/
        foreach ($reqData as $reqDatum) {

            $spo_oleh_id = isset($reqDatum['582spo_oleh_id']) ? $reqDatum['582spo_oleh_id'] : "";
            $spo_debet_lap = isset($reqDatum['582spo_debet_lap']) ? $reqDatum['582spo_debet_lap'] : "";
            $spo_kredit_lap = isset($reqDatum['582spo_kredit_lap']) ? $reqDatum['582spo_kredit_lap'] : "";
            $spo_master_id = isset($reqDatum['582spo_master_id']) ? $reqDatum['582spo_master_id'] : "";

            if (!isset($req_datas[$spo_master_id]['sum_spo_debet'])) {
                $req_datas[$spo_master_id]['sum_spo_debet'] = 0;
            }
            $req_datas[$spo_master_id]['sum_spo_debet'] += $spo_debet_lap;

            if (!isset($req_datas[$spo_master_id]['sum_spo_kredit'])) {
                $req_datas[$spo_master_id]['sum_spo_kredit'] = 0;
            }
            $req_datas[$spo_master_id]['sum_spo_kredit'] += $spo_kredit_lap;
            // ------------------------------------------
            $spd_debet_lap = isset($reqDatum['582spd_debet_lap']) ? $reqDatum['582spd_debet_lap'] : 0;
            $spd_kredit_lap = isset($reqDatum['582spd_kredit_lap']) ? $reqDatum['582spd_kredit_lap'] : 0;

            if (!isset($req_datas[$spo_master_id]['sum_spd_debet'])) {
                $req_datas[$spo_master_id]['sum_spd_debet'] = 0;
            }
            $req_datas[$spo_master_id]['sum_spd_debet'] += $spd_debet_lap;

            if (!isset($req_datas[$spo_master_id]['sum_spd_kredit'])) {
                $req_datas[$spo_master_id]['sum_spd_kredit'] = 0;
            }
            $req_datas[$spo_master_id]['sum_spd_kredit'] += $spd_kredit_lap;
            // ------------------------------------------
            $pkd_debet_lap = isset($reqDatum['582pkd_debet_lap']) ? $reqDatum['582pkd_debet_lap'] : 0;
            $pkd_kredit_lap = isset($reqDatum['582pkd_kredit_lap']) ? $reqDatum['582pkd_kredit_lap'] : 0;

            if (!isset($req_datas[$spo_master_id]['sum_pkd_debet'])) {
                $req_datas[$spo_master_id]['sum_pkd_debet'] = 0;
            }
            $req_datas[$spo_master_id]['sum_pkd_debet'] += $pkd_debet_lap;

            if (!isset($req_datas[$spo_master_id]['sum_pkd_kredit'])) {
                $req_datas[$spo_master_id]['sum_pkd_kredit'] = 0;
            }
            $req_datas[$spo_master_id]['sum_pkd_kredit'] += $pkd_kredit_lap;
            // ------------------------------------------
        }

        // arrPrintPink($req_datas);
        // matiHere();

        /* --------------------------------------------------------------------------------------------------
          * #3 pengumpulan data menjadi data siap tempur
          * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($olahan as $master_id => $itemParam) {
            // $customer_id = $itemParam['m_customerID'];
            $hasilOlahan_1[$master_id] = $itemParam + $req_datas[$master_id];
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $hasilOlahan_1;
        //
        // cekMerah(sizeof($gabunganData[0]['505'][0]));
        // arrPrintKuning($gabunganData[0]['505'][0]);
        // arrPrintKuning($gabunganData);
        // $masterData = $reqData;
        // matiHere(__LINE__);
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // cekHijau(sizeof($masterData));
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "582spo_oleh_nama"     => array(
                "label" => "salesman",
            ),
            "582spo_customer_nama" => array(
                "label" => "konsumen",
            ),
            "582spo__step_1_nomer" => array(
                "label"      => "spo",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            "582so__step_2_nomer"  => array(
                "label"      => "soa",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            "582pkd__step_3_nomer" => array(
                "label"      => "pkd",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            "582spd__step_4_nomer" => array(
                "label"      => "spd",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),

            "sum_spo_debet"  => array(
                "label"   => "order",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_pkd_kredit" => array(
                "label"   => "outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_spd_debet"  => array(
                "label"   => "shipment",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung",
            "title"       => "laporan per SO $strDate",
            "subTitle"    => "Raw Data CRM",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "permaster",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function test()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            // $maxLimit = 50;
            // $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = "582spo";
        $ps->setSortBy($sortings);
        $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src_000 = $ps->callOutstanding("persediaan_produk");
        // $src_000 = $ps->callOrderan("persediaan_produk");
    }

    // ---------------------------------
    // dibuat tgl 28juni 2022
    public function cekoutstandingseller()
    {
        /* ---------------------------------------------------------------------
         * data outstanding statik per seller akhir tahun 2021
         * ---------------------------------------------------------------------*/
        $arrOutstanding2021 = array(
            // "0"   => array("qty" => "0", "nilai" => "0",),
            "57"  => array("qty" => "0", "nilai" => "0",),
            "61"  => array("qty" => "9", "nilai" => "-21001356",),
            "65"  => array("qty" => "5", "nilai" => "-28063716.64",),
            "69"  => array("qty" => "109282", "nilai" => "8896071976",),
            "73"  => array("qty" => "12", "nilai" => "31213825.03",),
            "77"  => array("qty" => "-2", "nilai" => "-2342344",),
            "182" => array("qty" => "-2304", "nilai" => "-2252939845",),
            "205" => array("qty" => "28", "nilai" => "11886000",),
            // "551" => array("qty" => "0", "nilai" => "0",),
            // "567" => array("qty" => "0", "nilai" => "0",),
            "576" => array("qty" => "156", "nilai" => "440099108.3",),
            "663" => array("qty" => "-2", "nilai" => "-7178000",),
            "664" => array("qty" => "2239", "nilai" => "1238249916",),
            // "712" => array("qty" => "0", "nilai" => "0",),
            // // "718" => array("qty" => "-15", "nilai" => "-121500000",),
            // "718" => array("qty" => "0", "nilai" => "0",),
            // "719" => array("qty" => "0", "nilai" => "0",),
            // "808" => array("qty" => "0", "nilai" => "0",),
            // "825" => array("qty" => "0", "nilai" => "0",),
            // "826" => array("qty" => "0", "nilai" => "0",),

        );
        // -----------------------------------------------------------------
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $this->load->library("laporan/DataCrm");
        $dt = new DataCrm();

        $src_00 = $dt->callOrderan($date1, $date2);
        $src_mtd = $src_00['mtd'];
        $src_yang_lalu = $src_00['ytd_previous'];
        $arrRekenings = $src_00['rekening'];
        // cekBiru(sizeof($src_ytd));
        // arrPrint($src_ytd);
        foreach ($src_mtd as $item) {

            $subjek_id = $item['seller_id'];
            $qty_debet = $item['qty_debet'];
            $debet = $item['debet'];
            $rekening = $item['rekening'];

            if (!isset($sumSubjek[$subjek_id]['qty_debet_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_' . $rekening] += $qty_debet;

            if (!isset($sumSubjek[$subjek_id]['debet_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_' . $rekening] += $debet;

            $arrSubjek[$subjek_id] = $item;
            // $arrRekenings[$rekening] = $rekening;
        }

        // arrPrintHijau($sumSubjek);
        // foreach ($sumSubjek as $sbj_id => $sbjDatas) {
        //     foreach ($arrRekenings as $strekening) {
        //
        //         $caller_qty = "qty_debet_$strekening";
        //         $caller = "debet_$strekening";
        //         $$caller_qty = isset($sbjDatas[$caller_qty]) ? $sbjDatas[$caller_qty] : 0;
        //         $$caller = isset($sbjDatas['debet_' . $strekening]) ? $sbjDatas['debet_' . $strekening] : 0;
        //
        //     }
        //     $qty_outstanding = (($qty_debet_582so + $qty_debet_382so) - $qty_debet_982) - $qty_debet_582pkd;
        //     if (!isset($outstandingSubjek[$sbj_id]['qty_outstanding'])) {
        //         $outstandingSubjek[$sbj_id]['qty_outstanding'] = 0;
        //     }
        //     $outstandingSubjek[$sbj_id]['qty_outstanding'] += $qty_outstanding;
        //
        //     $outstanding = (($debet_582so + $debet_382so) - $debet_982) - $debet_582pkd;
        //     if (!isset($outstandingSubjek[$sbj_id]['outstanding'])) {
        //         $outstandingSubjek[$sbj_id]['outstanding'] = 0;
        //     }
        //     $outstandingSubjek[$sbj_id]['outstanding'] += $outstanding;
        // }
        // arrPrint($outstandingSubjek);

        /* ------------------------------------------------------------------------------------------
         * yang lalu
         * ------------------------------------------------------------------------------------------*/
        foreach ($src_yang_lalu as $item) {

            $subjek_id = $item['seller_id'];
            $qty_debet = $item['qty_debet'];
            $debet = $item['debet'];
            $rekening = $item['rekening'];

            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_' . $rekening] += $qty_debet;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_' . $rekening] += $debet;

            $arrSubjek[$subjek_id] = $item;
            // $arrRekenings[$rekening] = $rekening;
        }
        // arrPrint($sumSubjek);

        foreach ($sumSubjek as $sbj_id => $sbjDatas) {
            foreach ($arrRekenings as $strekening) {

                $caller_prev_qty = "prev_qty_debet_$strekening";
                $caller_qty = "qty_debet_$strekening";
                $caller_prev = "prev_debet_$strekening";
                $caller = "debet_$strekening";
                $$caller_prev_qty = isset($sbjDatas[$caller_prev_qty]) ? $sbjDatas[$caller_prev_qty] : 0;
                $$caller_prev = isset($sbjDatas[$caller_prev]) ? $sbjDatas[$caller_prev] : 0;
                $$caller_qty = isset($sbjDatas[$caller_qty]) ? $sbjDatas[$caller_qty] : 0;
                $$caller = isset($sbjDatas[$caller]) ? $sbjDatas[$caller] : 0;

            }

            $awalOts = $arrOutstanding2021[$sbj_id];
            $alhir2021qty = $awalOts["qty"];
            $alhir2021nilai = $awalOts["nilai"];

            // -------------------------NEW OUTSTANDING-------------------------------------------------
            $qty_outstanding = (($qty_debet_582so + $qty_debet_382so) - $qty_debet_982) - ($qty_debet_582pkd + $qty_debet_382pkd);
            $qty_outstanding_f = $qty_outstanding < 0 ? 0 : $qty_outstanding;
            if (!isset($outstandingSubjek[$sbj_id]['qty_outstanding'])) {
                $outstandingSubjek[$sbj_id]['qty_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['qty_outstanding'] += $qty_outstanding_f;

            $outstanding = (($debet_582so + $debet_382so) - $debet_982) - ($debet_582pkd + $debet_382pkd);
            $outstanding_f = $qty_outstanding_f == 0 ? 0 : $outstanding;
            if (!isset($outstandingSubjek[$sbj_id]['outstanding'])) {
                $outstandingSubjek[$sbj_id]['outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['outstanding'] += $outstanding_f;
            // -------------------------PREVIOUS OUTSTANDING-------------------------------------------------
            $prev_qty_outstanding = $alhir2021qty + (($prev_qty_debet_582so + $prev_qty_debet_382so) - $prev_qty_debet_982) - ($prev_qty_debet_582pkd + $prev_qty_debet_382pkd);
            if (!isset($outstandingSubjek[$sbj_id]['prev_qty_outstanding'])) {
                $outstandingSubjek[$sbj_id]['prev_qty_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['prev_qty_outstanding'] += $prev_qty_outstanding;

            $prev_outstanding = $alhir2021nilai + (($prev_debet_582so + $prev_debet_382so) - $prev_debet_982) - ($prev_debet_582pkd + $prev_debet_382pkd);
            if (!isset($outstandingSubjek[$sbj_id]['prev_outstanding'])) {
                $outstandingSubjek[$sbj_id]['prev_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['prev_outstanding'] += $prev_outstanding;
        }

        // arrPrintPink($sumSubjek);
        // mati_disini(__LINE__);
        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            // $customer_id = $itemParam['m_customerID'];
            //region penyiapan kolom data YTD
            $mtd_qty_582so = isset($sumSubjek[$subj_id]['qty_debet_582so']) ? $sumSubjek[$subj_id]['qty_debet_582so'] : 0;
            $mtd_qty_382so = isset($sumSubjek[$subj_id]['qty_debet_382so']) ? $sumSubjek[$subj_id]['qty_debet_382so'] : 0;
            $mtd_qty_982 = isset($sumSubjek[$subj_id]['qty_debet_982']) ? $sumSubjek[$subj_id]['qty_debet_982'] : 0;
            $mtd_qty_582spd = isset($sumSubjek[$subj_id]['qty_debet_582pkd']) ? $sumSubjek[$subj_id]['qty_debet_582pkd'] : 0;
            $mtd_qty_382spd = isset($sumSubjek[$subj_id]['qty_debet_382pkd']) ? $sumSubjek[$subj_id]['qty_debet_382pkd'] : 0;

            $qty_mtd = ($mtd_qty_582so + $mtd_qty_382so - $mtd_qty_982) - ($mtd_qty_582spd + $mtd_qty_382spd);
            $ytd_qty_outstanding = $outstandingSubjek[$subj_id]["prev_qty_outstanding"] + $qty_mtd;
            // $sub_outstanding["ytd_qty_outstanding"] = $outstandingSubjek[$subj_id]["prev_qty_outstanding"] + $outstandingSubjek[$subj_id]["qty_outstanding"];
            $sub_outstanding["ytd_qty_outstanding"] = $ytd_qty_outstanding;
            // -----------------------------------------------------------------------------------
            $mtd_582so = isset($sumSubjek[$subj_id]['debet_582so']) ? $sumSubjek[$subj_id]['debet_582so'] : 0;
            $mtd_382so = isset($sumSubjek[$subj_id]['debet_382so']) ? $sumSubjek[$subj_id]['debet_382so'] : 0;
            $mtd_982 = isset($sumSubjek[$subj_id]['debet_982']) ? $sumSubjek[$subj_id]['debet_982'] : 0;
            $mtd_582spd = isset($sumSubjek[$subj_id]['debet_582pkd']) ? $sumSubjek[$subj_id]['debet_582pkd'] : 0;
            $mtd_382spd = isset($sumSubjek[$subj_id]['debet_382pkd']) ? $sumSubjek[$subj_id]['debet_382pkd'] : 0;

            $mtd = ($mtd_582so + $mtd_382so - $mtd_982) - ($mtd_582spd + $mtd_382spd);
            $ytd_outstanding = $outstandingSubjek[$subj_id]["prev_outstanding"] + $mtd;
            // $sub_outstanding["ytd_outstanding"] = $outstandingSubjek[$subj_id]["prev_outstanding"] + $outstandingSubjek[$subj_id]["outstanding"];
            $sub_outstanding["ytd_outstanding"] = $ytd_qty_outstanding > 0 ? $ytd_outstanding : 0;
            //endregion

            $hasilOlahan_1[] = $itemParam + $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
            // $hasilOlahan[$customer_id] = $itemParam;
        }
        $masterData = $hasilOlahan_1;
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);

        $arrHeaders = array(
            "seller_id"            => array(
                "label" => "sid",
            ),
            "seller_nama"          => array(
                "label" => "salesman",
            ),
            // ---------------
            "prev_qty_outstanding" => array(
                "label"      => "previous outstanding qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
                "summary"    => true,
            ),
            "prev_outstanding"     => array(
                "label"      => "previous outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
                "summary"    => true,
            ),
            "qty_debet_582so"      => array(
                "label"      => "new order qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "debet_582so"          => array(
                "label"      => "new order value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "qty_debet_382so"      => array(
                "label"      => "new order export qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "debet_382so"          => array(
                "label"      => "new order export  value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "qty_debet_982"        => array(
                "label"      => "new return qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "debet_982"            => array(
                "label"      => "new return value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "qty_debet_582pkd"     => array(
                "label"      => "packing list qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "debet_582pkd"         => array(
                "label"      => "packing list value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "qty_debet_382pkd"     => array(
                "label"      => "packing list export qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "debet_382pkd"         => array(
                "label"      => "packing list export value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "qty_outstanding"      => array(
                "label"      => "new outstanding qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "outstanding"          => array(
                "label"      => "new outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "netto",
                "attr"       => "class='text-right'",
                "logics"     => array(
                    "nilai" => "<0",
                    "text" => "termasuk pengiriman dari outstanding bulan lalu",
                ),
                "summary"    => true,
            ),
            "ytd_qty_outstanding"  => array(
                "label"      => "total last outstanding qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
            ),
            "ytd_outstanding"      => array(
                "label"      => "total outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
            ),
        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        $month_now = dtimeNow('m');
        $month_req = formatTanggal($get_date1, 'm');
        // cekHere("$month_req $month_now");
        if ($month_now == $month_req) {
            $judul_lap = "Month To Date " . formatTanggal($get_date1, 'F Y');
        }
        else {
            $judul_lap = " " . formatTanggal($get_date1, 'F Y');
        }

        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung_indek",
            "title"       => "Laporan Salesman Outstanding " . $judul_lap,
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "data_id"     => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout"      => isset($_GET['layout']) ? $_GET['layout'] : true,
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "strGet"      => $strGet,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            "loader_div"  => "sum_satu",
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function cekoutstanding()
    {
        /* ---------------------------------------------------------------------
         * data outstanding statik per seller akhir tahun 2021
         * ---------------------------------------------------------------------*/
        $arrOutstanding2021 = array(
            "0"   => array("qty" => "0", "nilai" => "0",),
            "57"  => array("qty" => "0", "nilai" => "0",),
            "61"  => array("qty" => "9", "nilai" => "-21001356",),
            "65"  => array("qty" => "9", "nilai" => "21885455.36",),
            "69"  => array("qty" => "110738", "nilai" => "9024185437.5009",),
            "73"  => array("qty" => "14", "nilai" => "31213825.2128",),
            "77"  => array("qty" => "0", "nilai" => "0",),
            "182" => array("qty" => "0", "nilai" => "0",),
            "205" => array("qty" => "28", "nilai" => "11886000",),
            "551" => array("qty" => "0", "nilai" => "0",),
            "567" => array("qty" => "0", "nilai" => "0",),
            "576" => array("qty" => "189", "nilai" => "449897307.28834",),
            "663" => array("qty" => "0", "nilai" => "0",),
            "664" => array("qty" => "2339", "nilai" => "1361999916.33",),
            "712" => array("qty" => "0", "nilai" => "0",),
            "718" => array("qty" => "-15", "nilai" => "-121500000",),
            "719" => array("qty" => "0", "nilai" => "0",),
            "808" => array("qty" => "0", "nilai" => "0",),
            "825" => array("qty" => "0", "nilai" => "0",),
            "826" => array("qty" => "0", "nilai" => "0",),

        );
        // -----------------------------------------------------------------
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $this->load->library("laporan/DataCrm");
        $dt = new DataCrm();

        $src_00 = $dt->callOrderan($date1, $date2);
        $src_ytd = $src_00['ytd'];
        $src_yang_lalu = $src_00['ytd_previous'];
        $arrRekenings = $src_00['rekening'];
        // cekBiru(sizeof($src_ytd));
        // arrPrint($src_ytd);
        foreach ($src_mtd as $item) {

            $subjek_id = $item['seller_id'];
            $qty_debet = $item['qty_debet'];
            $debet = $item['debet'];
            $rekening = $item['rekening'];

            if (!isset($sumSubjek[$subjek_id]['qty_debet_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_' . $rekening] += $qty_debet;

            if (!isset($sumSubjek[$subjek_id]['debet_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_' . $rekening] += $debet;

            $arrSubjek[$subjek_id] = $item;
            // $arrRekenings[$rekening] = $rekening;
        }

        // arrPrintHijau($sumSubjek);
        // foreach ($sumSubjek as $sbj_id => $sbjDatas) {
        //     foreach ($arrRekenings as $strekening) {
        //
        //         $caller_qty = "qty_debet_$strekening";
        //         $caller = "debet_$strekening";
        //         $$caller_qty = isset($sbjDatas[$caller_qty]) ? $sbjDatas[$caller_qty] : 0;
        //         $$caller = isset($sbjDatas['debet_' . $strekening]) ? $sbjDatas['debet_' . $strekening] : 0;
        //
        //     }
        //     $qty_outstanding = (($qty_debet_582so + $qty_debet_382so) - $qty_debet_982) - $qty_debet_582pkd;
        //     if (!isset($outstandingSubjek[$sbj_id]['qty_outstanding'])) {
        //         $outstandingSubjek[$sbj_id]['qty_outstanding'] = 0;
        //     }
        //     $outstandingSubjek[$sbj_id]['qty_outstanding'] += $qty_outstanding;
        //
        //     $outstanding = (($debet_582so + $debet_382so) - $debet_982) - $debet_582pkd;
        //     if (!isset($outstandingSubjek[$sbj_id]['outstanding'])) {
        //         $outstandingSubjek[$sbj_id]['outstanding'] = 0;
        //     }
        //     $outstandingSubjek[$sbj_id]['outstanding'] += $outstanding;
        // }
        // arrPrint($outstandingSubjek);

        /* ------------------------------------------------------------------------------------------
         * yang lalu
         * ------------------------------------------------------------------------------------------*/
        foreach ($src_yang_lalu as $item) {

            $subjek_id = $item['seller_id'];
            $qty_debet = $item['qty_debet'];
            $debet = $item['debet'];
            $rekening = $item['rekening'];

            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_' . $rekening] += $qty_debet;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_' . $rekening] += $debet;

            $arrSubjek[$subjek_id] = $item;
            // $arrRekenings[$rekening] = $rekening;
        }
        // arrPrint($sumSubjek);

        foreach ($sumSubjek as $sbj_id => $sbjDatas) {
            foreach ($arrRekenings as $strekening) {

                $caller_prev_qty = "prev_qty_debet_$strekening";
                $caller_qty = "qty_debet_$strekening";
                $caller_prev = "prev_debet_$strekening";
                $caller = "debet_$strekening";
                $$caller_prev_qty = isset($sbjDatas[$caller_prev_qty]) ? $sbjDatas[$caller_prev_qty] : 0;
                $$caller_prev = isset($sbjDatas[$caller_prev]) ? $sbjDatas[$caller_prev] : 0;
                $$caller_qty = isset($sbjDatas[$caller_qty]) ? $sbjDatas[$caller_qty] : 0;
                $$caller = isset($sbjDatas[$caller]) ? $sbjDatas[$caller] : 0;

            }

            $awalOts = $arrOutstanding2021[$sbj_id];
            $alhir2021qty = $awalOts["qty"];
            $alhir2021nilai = $awalOts["nilai"];

            $qty_outstanding = (($qty_debet_582so + $qty_debet_382so) - $qty_debet_982) - ($qty_debet_582pkd + $qty_debet_382pkd);
            $qty_outstanding_f = $qty_outstanding < 0 ? 0 : $qty_outstanding;
            if (!isset($outstandingSubjek[$sbj_id]['qty_outstanding'])) {
                $outstandingSubjek[$sbj_id]['qty_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['qty_outstanding'] += $qty_outstanding_f;

            $outstanding = (($debet_582so + $debet_382so) - $debet_982) - ($debet_582pkd + $debet_382pkd);
            $outstanding_f = $qty_outstanding_f == 0 ? 0 : $outstanding;
            if (!isset($outstandingSubjek[$sbj_id]['outstanding'])) {
                $outstandingSubjek[$sbj_id]['outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['outstanding'] += $outstanding_f;
            // --------------------------------------------------------------------------
            $prev_qty_outstanding = $alhir2021qty + (($prev_qty_debet_582so + $prev_qty_debet_382so) - $prev_qty_debet_982) - ($prev_qty_debet_582pkd + $prev_qty_debet_382pkd);
            if (!isset($outstandingSubjek[$sbj_id]['prev_qty_outstanding'])) {
                $outstandingSubjek[$sbj_id]['prev_qty_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['prev_qty_outstanding'] += $prev_qty_outstanding;

            $prev_outstanding = $alhir2021nilai + (($prev_debet_582so + $prev_debet_382so) - $prev_debet_982) - ($prev_debet_582pkd + $prev_debet_382pkd);
            if (!isset($outstandingSubjek[$sbj_id]['prev_outstanding'])) {
                $outstandingSubjek[$sbj_id]['prev_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['prev_outstanding'] += $prev_outstanding;
        }

        // arrPrintPink($sumSubjek);
        // mati_disini(__LINE__);
        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            // $customer_id = $itemParam['m_customerID'];
            //region penyiapan kolom data YTD
            $mtd_qty_582so = isset($sumSubjek[$subj_id]['qty_debet_582so']) ? $sumSubjek[$subj_id]['qty_debet_582so'] : 0;
            $mtd_qty_382so = isset($sumSubjek[$subj_id]['qty_debet_382so']) ? $sumSubjek[$subj_id]['qty_debet_382so'] : 0;
            $mtd_qty_982 = isset($sumSubjek[$subj_id]['qty_debet_982']) ? $sumSubjek[$subj_id]['qty_debet_982'] : 0;
            $mtd_qty_582spd = isset($sumSubjek[$subj_id]['qty_debet_582pkd']) ? $sumSubjek[$subj_id]['qty_debet_582pkd'] : 0;
            $mtd_qty_382spd = isset($sumSubjek[$subj_id]['qty_debet_382pkd']) ? $sumSubjek[$subj_id]['qty_debet_382pkd'] : 0;

            $qty_mtd = ($mtd_qty_582so + $mtd_qty_382so - $mtd_qty_982) - ($mtd_qty_582spd + $mtd_qty_382spd);
            $ytd_qty_outstanding = $outstandingSubjek[$subj_id]["prev_qty_outstanding"] + $qty_mtd;
            // $sub_outstanding["ytd_qty_outstanding"] = $outstandingSubjek[$subj_id]["prev_qty_outstanding"] + $outstandingSubjek[$subj_id]["qty_outstanding"];
            $sub_outstanding["ytd_qty_outstanding"] = $ytd_qty_outstanding;
            // -----------------------------------------------------------------------------------
            $mtd_582so = isset($sumSubjek[$subj_id]['debet_582so']) ? $sumSubjek[$subj_id]['debet_582so'] : 0;
            $mtd_382so = isset($sumSubjek[$subj_id]['debet_382so']) ? $sumSubjek[$subj_id]['debet_382so'] : 0;
            $mtd_982 = isset($sumSubjek[$subj_id]['debet_982']) ? $sumSubjek[$subj_id]['debet_982'] : 0;
            $mtd_582spd = isset($sumSubjek[$subj_id]['debet_582pkd']) ? $sumSubjek[$subj_id]['debet_582pkd'] : 0;
            $mtd_382spd = isset($sumSubjek[$subj_id]['debet_382pkd']) ? $sumSubjek[$subj_id]['debet_382pkd'] : 0;

            $mtd = ($mtd_582so + $mtd_382so - $mtd_982) - ($mtd_582spd + $mtd_382spd);
            $ytd_outstanding = $outstandingSubjek[$subj_id]["prev_outstanding"] + $mtd;
            // $sub_outstanding["ytd_outstanding"] = $outstandingSubjek[$subj_id]["prev_outstanding"] + $outstandingSubjek[$subj_id]["outstanding"];
            $sub_outstanding["ytd_outstanding"] = $ytd_qty_outstanding > 0 ? $ytd_outstanding : 0;
            //endregion

            $hasilOlahan_1[] = $itemParam + $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $src_ytd;
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);

        $arrHeaders = array(
            "fulldate"      => array(
                "label" => "Tanggal",
            ),
            "master_id"     => array(
                "label" => "mID",
            ),
            "kode"          => array(
                "label" => "kode",
            ),
            "extern_nama"   => array(
                "label" => "produk",
            ),
            "seller_nama"   => array(
                "label" => "salesman",
            ),
            "customer_nama" => array(
                "label" => "customer",
                "attr"  => "width='100px'",
            ),
            "cabang_nama"   => array(
                "label" => "cabang",
            ),
            "_step_1_nomer" => array(
                "label"      => "nomer order",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            "produk_satuan" => array(
                "label" => "satuan",
            ),
            // ---------------
            // "prev_qty_outstanding" => array(
            //     "label"      => "previous outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "summary"    => true,
            // ),
            // "prev_outstanding"     => array(
            //     "label"      => "previous outstanding value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "summary"    => true,
            // ),
            "qty_debet"     => array(
                "label"      => "new order qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "debet"         => array(
                "label"      => "new order value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            // "qty_debet_382so"      => array(
            //     "label"      => "new order export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_382so"          => array(
            //     "label"      => "new order export  value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_982"        => array(
            //     "label"      => "new return qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_982"            => array(
            //     "label"      => "new return value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_582pkd"     => array(
            //     "label"      => "packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_582pkd"         => array(
            //     "label"      => "packing list value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_382pkd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_382pkd"         => array(
            //     "label"      => "packing list export value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_outstanding"      => array(
            //     "label"      => "new outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "outstanding"          => array(
            //     "label"      => "new outstanding value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "ytd_qty_outstanding"  => array(
            //     "label"      => "total last outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            // "ytd_outstanding"      => array(
            //     "label"      => "total outstanding value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        $month_now = dtimeNow('m');
        $month_req = formatTanggal($get_date1, 'm');
        // cekHere("$month_req $month_now");
        if ($month_now == $month_req) {
            $judul_lap = "Year To Date " . formatTanggal($get_date1, 'F Y');
        }
        else {
            $judul_lap = " " . formatTanggal($get_date1, 'F Y');
        }

        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung_indek",
            "title"       => "Raw data Outstanding " . $judul_lap,
            "subTitle"    => "Raw data penjualan",
            "modul_path"  => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "data_id"     => "rawdata0_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout"      => isset($_GET['layout']) ? $_GET['layout'] : true,
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "strGet"      => $strGet,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function produkoutstanding()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            // $maxLimit = 50;
            // $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = "582spo";
        $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src_000 = $ps->callOutstanding("persediaan_produk");
        // $reqData_000 = $src_000['produk'];
        $reqData_000 = $src_000['raw'];
        // arrPrintKuning($reqData_000);
        // $arrMaster_id = array();
        // foreach ($reqData_000 as $item) {
        //     $master_id_000 = $item['582spo_master_id'];
        //
        //     $arrMaster_id[] = $master_id_000;
        // }
        // asort($arrMaster_id);
        // $minMaster_id = min($arrMaster_id);
        // $maxMaster_id = max($arrMaster_id);
        // // cekBiru($arrMaster_id);
        // // cekMerah());
        // // cekMerah();
        // // matiHere();
        // if (isset($_GET['date1']) && sizeof($arrMaster_id) > 0) {
        //     $condites = array(
        //         "master_id >=" => $minMaster_id,
        //         "master_id <=" => $maxMaster_id,
        //     );
        //     $this->db->where($condites);
        //
        //     $strDate .= formatField_he_format("fulldate", $get_date1);
        //     $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        // }
        //
        // $jenisTr = array(
        //     "582spo",
        //     "582so",
        //     "582pkd",
        //     "582spd",
        //     // "967"
        // );
        // $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // // $ps->setJenisTr("582");
        // $src = $ps->callCacheProduk("persediaan_produk");
        // $reqData = $src['data'];
        // // showLast_query("kuning");
        // // cekBiru(sizeof($reqData[0]));
        // // arrPrintKuning($reqData);
        // /* --------------------------------------------------------------------------------------------------
        //  *peparasi data harus 3 step
        //  * #1 pengumpulan data transaksi (main)
        //  * --------------------------------------------------------------------------------------------------*/
        // $olahan = array();
        // foreach ($reqData as $masterDatum) {
        //     $spo_oleh_id = isset($masterDatum['582spo_oleh_id']) ? $masterDatum['582spo_oleh_id'] : "";
        //     $spo_oleh_nama = isset($masterDatum['582spo_oleh_nama']) ? $masterDatum['582spo_oleh_nama'] : "";
        //     $master_id = isset($masterDatum['582spo_master_id']) ? $masterDatum['582spo_master_id'] : "";
        //
        //     $olahan[$master_id] = $masterDatum;
        // }
        // /* --------------------------------------------------------------------------------------------------
        //  * #2 membuat tambahan kolom summary
        //  * --------------------------------------------------------------------------------------------------*/
        // foreach ($reqData as $reqDatum) {
        //
        //     $spo_oleh_id = isset($reqDatum['582spo_oleh_id']) ? $reqDatum['582spo_oleh_id'] : "";
        //     $spo_debet_lap = isset($reqDatum['582spo_debet_lap']) ? $reqDatum['582spo_debet_lap'] : "";
        //     $spo_kredit_lap = isset($reqDatum['582spo_kredit_lap']) ? $reqDatum['582spo_kredit_lap'] : "";
        //     $spo_master_id = isset($reqDatum['582spo_master_id']) ? $reqDatum['582spo_master_id'] : "";
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spo_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_debet'] += $spo_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spo_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_kredit'] += $spo_kredit_lap;
        //     // ------------------------------------------
        //     $spd_debet_lap = isset($reqDatum['582spd_debet_lap']) ? $reqDatum['582spd_debet_lap'] : 0;
        //     $spd_kredit_lap = isset($reqDatum['582spd_kredit_lap']) ? $reqDatum['582spd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_debet'] += $spd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_kredit'] += $spd_kredit_lap;
        //     // ------------------------------------------
        //     $pkd_debet_lap = isset($reqDatum['582pkd_debet_lap']) ? $reqDatum['582pkd_debet_lap'] : 0;
        //     $pkd_kredit_lap = isset($reqDatum['582pkd_kredit_lap']) ? $reqDatum['582pkd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_debet'] += $pkd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_kredit'] += $pkd_kredit_lap;
        //     // ------------------------------------------
        // }
        //
        // // arrPrintPink($req_datas);
        // // matiHere();
        //
        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        // $hasilOlahan_1 = array();
        // foreach ($olahan as $master_id => $itemParam) {
        //     // $customer_id = $itemParam['m_customerID'];
        //     $hasilOlahan_1[$master_id] = $itemParam + $req_datas[$master_id];
        //     // $hasilOlahan[$customer_id] = $itemParam;
        // }

        $paramex = true;
        if ($paramex == true) {

            // if (ipadd() == "192.168.5.7") {
            $tgl_bulan_ini = dtimeNow('Y-m-d');
            $tgl_bulan_cek = dtimeNow('Y-m-1'); // tgl melakukan generate data diset tiaptgl 1
            $bulan_lalu = previousMonth($tgl_bulan_ini);
            $tgl_bulan_target = formatTanggal($bulan_lalu, 'Y-m-t');
            // cekBiru("$tgl_bulan_ini == $tgl_bulan_cek");
            $str_log = "";
            if ($tgl_bulan_ini == $tgl_bulan_cek) {
                // cekHere('ceking ' . $bulan_lalu . " ggg " . $tgl_bulan_target);

                $kodisies = array(
                    "periode"     => "bulanan",
                    // "year(dtime)"  => formatTanggal($tgl_bulan_target, 'Y'),
                    // "month(dtime)" => formatTanggal($tgl_bulan_target, 'm'),
                    "date(dtime)" => $tgl_bulan_target,
                );

                $this->db->where($kodisies);
                $ceker_jml_data = $ps->lookupAll()->num_rows();
                // showLast_query("orange");
                // arrPrintPink($ceker_jml_data);
                if ($ceker_jml_data == 0) {
                    /* ------------------------------------------------------------------------
                     * membuat data chace untuk akhir bulan lalu
                     * ------------------------------------------------------------------------*/
                    $this->db->trans_start();
                    foreach ($reqData_000 as $item) {
                        // arrPrintKuning($item);
                        $kodisies_00 = array(
                            "periode"      => "bulanan",
                            "cabang_id"    => $item["cabang_id"],
                            "gudang_id"    => $item["gudang_id"],
                            "master_id"    => $item["master_id"],
                            "rekening"     => $item["rekening"],
                            "transaksi_id" => $item["transaksi_id"],
                            "extern_id"    => $item["extern_id"],
                        );

                        $kodisies = array(
                                // "periode"      => "bulanan",
                                // "year(dtime)"  => formatTanggal($date2, 'Y'),
                                // "month(dtime)" => formatTanggal($date2, 'm'),
                                "date(dtime)" => $tgl_bulan_target,
                            ) + $kodisies_00;

                        $this->db->where($kodisies);
                        $ceker = $ps->lookupAll()->row();
                        showLast_query("orange");
                        arrPrintPink($ceker);

                        // ------------------------------------------------
                        if (sizeof($ceker) == 0) {
                            $item_news = array(
                                    "dtime"          => $tgl_bulan_target,
                                    "extern_nama"    => $item["extern_nama"],
                                    "cabang_nama"    => $item["cabang_nama"],
                                    // "gudang_nama"    => $item["gudang_nama"],
                                    "debet"          => $item["debet"],
                                    "kredit"         => $item["kredit"],
                                    "qty_debet"      => $item["qty_debet"],
                                    "qty_kredit"     => $item["qty_kredit"],
                                    "harga"          => $item["harga"],
                                    "harga_avg"      => $item["harga_avg"],
                                    "harga_awal"     => $item["harga_awal"],
                                    "seller_id"      => $item["seller_id"],
                                    "seller_nama"    => $item["seller_nama"],
                                    "customer_id"    => $item["customer_id"],
                                    "customer_nama"  => $item["customer_nama"],
                                    "qty_debet_lap"  => $item["qty_debet_lap"],
                                    "qty_kredit_lap" => $item["qty_kredit_lap"],
                                    "debet_lap"      => $item["debet_lap"],
                                    "kredit_lap"     => $item["kredit_lap"],
                                    "harga_bruto"    => $item["harga_bruto"],
                                    "harga_netto"    => $item["harga_netto"],
                                    "ppn_nilai"      => $item["ppn_nilai"],
                                    "premi_nilai"    => $item["premi_nilai"],
                                    "harga_nppn"     => $item["harga_nppn"],
                                    "diskon_nilai"   => $item["diskon_nilai"],
                                    "kategori_id"    => $item["kategori_id"],
                                    "kategori_nama"  => $item["kategori_nama"],

                                ) + $kodisies_00;
                            $ps->addData($item_news);
                            showLast_query("biru");
                            // cekHijau("insert");
                        }
                        else {
                            $update_kondisies = array() + $kodisies;
                            $update_datas = array(
                                "periode" => "bulanan",
                            );
                            // $ps->updateData($update_kondisies, $update_datas);
                            // showLast_query("pink");
                            mati_disini("sudah ada data");
                        }


                        // mati_disini(__LINE__ . " belum comit");

                    }
                    // mati_disini(__LINE__ . " belum comit");
                    $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
                    $str_log .= "data cache bulan lalu:$tgl_bulan_target sukses dibuat";
                    reloaded();
                }
                else {
                    $str_log .= "data cache bulan lalu:$tgl_bulan_target sudah ada";
                    cekOrange($str_log);
                }
            }
            else {

                $str_log .= "tidak melakukan cek data, couse sudah lewat dr tgl:$tgl_bulan_cek yg ditentukan untuk ngecek";
                cekHijau($str_log);
            }
            // mati_disini(__LINE__);
            $str_logs = dtimeNow() . " " . $str_log . " by " . my_name() . " " . base_url();
            $dir = "/var/www/html/log";
            $log_file = "log_cache";
            writeLogToFile($dir, $log_file, $str_logs);

            /*
             * otomatis akan membuat fil log
             * */
            // }
        }

        $masterData = $reqData_000;
        //
        // cekMerah(sizeof($gabunganData[0]['505'][0]));
        // arrPrintKuning($gabunganData[0]['505'][0]);
        // arrPrintKuning($gabunganData);
        // $masterData = $reqData;
        // matiHere(__LINE__);
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // cekHijau(sizeof($masterData));
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "fulldate"          => array(
                "label" => "Tanggal",
            ),
            "master_id"         => array(
                "label" => "mID",
            ),
            "kode"              => array(
                "label" => "kode",
            ),
            "extern_nama"       => array(
                "label" => "produk",
            ),
            "seller_nama"       => array(
                "label" => "salesman",
            ),
            // "seller_id"         => array(
            //     "label" => "selID",
            // ),
            // "oleh_nama"         => array(
            //     "label" => "oleh",
            // ),
            // "cabang_id"         => array(
            //     "label" => "cb",
            // ),
            "cabang_nama"       => array(
                "label" => "cabang",
            ),
            "_step_1_nomer"     => array(
                "label"      => "nomer order",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            "customer_nama"     => array(
                "label" => "customer",
                "attr"  => "width='100px'",
            ),
            "produk_satuan"     => array(
                "label" => "satuan",
            ),
            "spo_qty_debet_lap" => array(
                "label"      => "qty order",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "spo_debet_lap"     => array(
                "label"      => "nilai order",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            // "rejected"     => array(
            //     "label"      => "rejected",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            // ),
            "qty_debet_lap"     => array(
                "label"      => "sudah dikrim",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "debet_lap"         => array(
                "label"      => "nilai dikrim",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "qty_kredit_lap"    => array(
                "label"      => "qty outstanding",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "kredit_lap"        => array(
                "label"      => "nilai outstanding",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),

            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),

            // "sum_spo_debet"       => array(
            //     "label"  => "order",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
            // "sum_qty_kredit"       => array(
            //     "label"  => "outstanding",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
            // "sum_kredit"       => array(
            //     "label"  => "shipment",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "default",
            "title"       => "outstanding order penjualan",
            "subTitle"    => "Raw Data outstanding penjualan",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "permaster",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            "sum_satu"    => base_url() . "laporan/Crm/produkoutstanditransaksi" . "$strGet",
            "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingproduk" . "$strGet",
            "sum_tiga"    => base_url() . "laporan/Crm/produkoutstandingsales" . "$strGet",
            "sum_empat"   => base_url() . "laporan/Crm/produkoutstandingcustomer" . "$strGet",
            "sum_lima"    => base_url() . "laporan/Crm/produkoutstandingcabang" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function produkoutstandingraw()
    {
        // arrPrintHijau($_REQUEST);
        // arrPrintPink($_GET);
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            // $maxLimit = 50;
            // $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = "582spo";
        $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src_000 = $ps->callOutstanding("persediaan_produk");
        $reqData_000 = $src_000['raw'];
        // showLast_query("kuning");
        // cekBiru(sizeof($masterData));
        // cekBiru($masterData_ori);
        // cekHijau(ipadd());

        $condites = array(
            // "date(dtime)>=" => $get_date1,
            "date(dtime)<=" => $get_date2,
        );
        $this->db->where($condites);
        $src_001 = $ps->callOutstandingBulanan("persediaan_produk");
        // arrPrintPink($src_001['raw']);
        $paramex = true;
        if ($paramex == true) {

            // if (ipadd() == "192.168.5.7") {
            $tgl_bulan_ini = dtimeNow('Y-m-d');
            $tgl_bulan_cek = dtimeNow('Y-m-1'); // tgl melakukan generate data diset tiaptgl 1
            $bulan_lalu = previousMonth($tgl_bulan_ini);
            $tgl_bulan_target = formatTanggal($bulan_lalu, 'Y-m-t');
            // cekBiru("$tgl_bulan_ini == $tgl_bulan_cek");
            $str_log = "";
            if ($tgl_bulan_ini == $tgl_bulan_cek) {
                // cekHere('ceking ' . $bulan_lalu . " ggg " . $tgl_bulan_target);

                $kodisies = array(
                    "periode"     => "bulanan",
                    // "year(dtime)"  => formatTanggal($tgl_bulan_target, 'Y'),
                    // "month(dtime)" => formatTanggal($tgl_bulan_target, 'm'),
                    "date(dtime)" => $tgl_bulan_target,
                );

                $this->db->where($kodisies);
                $ceker_jml_data = $ps->lookupAll()->num_rows();
                // showLast_query("orange");
                // arrPrintPink($ceker_jml_data);
                if ($ceker_jml_data == 0) {
                    /* ------------------------------------------------------------------------
                     * membuat data chace untuk akhir bulan lalu
                     * ------------------------------------------------------------------------*/
                    $this->db->trans_start();
                    foreach ($reqData_000 as $item) {
                        // arrPrintKuning($item);
                        $kodisies_00 = array(
                            "periode"      => "bulanan",
                            "cabang_id"    => $item["cabang_id"],
                            "gudang_id"    => $item["gudang_id"],
                            "master_id"    => $item["master_id"],
                            "rekening"     => $item["rekening"],
                            "transaksi_id" => $item["transaksi_id"],
                            "extern_id"    => $item["extern_id"],
                        );

                        $kodisies = array(
                                // "periode"      => "bulanan",
                                // "year(dtime)"  => formatTanggal($date2, 'Y'),
                                // "month(dtime)" => formatTanggal($date2, 'm'),
                                "date(dtime)" => $tgl_bulan_target,
                            ) + $kodisies_00;

                        $this->db->where($kodisies);
                        $ceker = $ps->lookupAll()->row();
                        showLast_query("orange");
                        arrPrintPink($ceker);

                        // ------------------------------------------------
                        if (sizeof($ceker) == 0) {
                            $item_news = array(
                                    "dtime"          => $tgl_bulan_target,
                                    "extern_nama"    => $item["extern_nama"],
                                    "cabang_nama"    => $item["cabang_nama"],
                                    // "gudang_nama"    => $item["gudang_nama"],
                                    "debet"          => $item["debet"],
                                    "kredit"         => $item["kredit"],
                                    "qty_debet"      => $item["qty_debet"],
                                    "qty_kredit"     => $item["qty_kredit"],
                                    "harga"          => $item["harga"],
                                    "harga_avg"      => $item["harga_avg"],
                                    "harga_awal"     => $item["harga_awal"],
                                    "seller_id"      => $item["seller_id"],
                                    "seller_nama"    => $item["seller_nama"],
                                    "customer_id"    => $item["customer_id"],
                                    "customer_nama"  => $item["customer_nama"],
                                    "qty_debet_lap"  => $item["qty_debet_lap"],
                                    "qty_kredit_lap" => $item["qty_kredit_lap"],
                                    "debet_lap"      => $item["debet_lap"],
                                    "kredit_lap"     => $item["kredit_lap"],
                                    "harga_bruto"    => $item["harga_bruto"],
                                    "harga_netto"    => $item["harga_netto"],
                                    "ppn_nilai"      => $item["ppn_nilai"],
                                    "premi_nilai"    => $item["premi_nilai"],
                                    "harga_nppn"     => $item["harga_nppn"],
                                    "diskon_nilai"   => $item["diskon_nilai"],
                                    "kategori_id"    => $item["kategori_id"],
                                    "kategori_nama"  => $item["kategori_nama"],

                                ) + $kodisies_00;
                            $ps->addData($item_news);
                            showLast_query("biru");
                            // cekHijau("insert");
                        }
                        else {
                            $update_kondisies = array() + $kodisies;
                            $update_datas = array(
                                "periode" => "bulanan",
                            );
                            // $ps->updateData($update_kondisies, $update_datas);
                            // showLast_query("pink");
                            mati_disini("sudah ada data");
                        }


                        // mati_disini(__LINE__ . " belum comit");

                    }
                    // mati_disini(__LINE__ . " belum comit");
                    $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
                    $str_log .= "data cache bulan lalu:$tgl_bulan_target sukses dibuat";
                    reloaded();
                }
                else {
                    $str_log .= "data cache bulan lalu:$tgl_bulan_target sudah ada";
                    cekOrange($str_log);
                }
            }
            else {

                $str_log .= "tidak melakukan cek data, couse sudah lewat dr tgl:$tgl_bulan_cek yg ditentukan untuk ngecek";
                cekHijau($str_log);
            }
            // mati_disini(__LINE__);
            $str_logs = dtimeNow() . " " . $str_log . " by " . my_name() . " " . base_url();
            $dir = "/var/www/html/log";
            $log_file = "log_cache";
            writeLogToFile($dir, $log_file, $str_logs);

            /*
             * otomatis akan membuat fil log
             * */
            // }
        }

        if (isset($_GET['ky'])) {
            $req_key = $_GET['ky'];
            switch ($req_key) {
                case "cabang_id":
                case "extern_id":
                case "master_id":
                case "customer_id":
                case "seller_id":
                    $hasilOlahan_bysupplier = array();
                    foreach ($reqData_000 as $item) {
                        if (isset($_GET[$req_key]) && $item[$req_key] == $_GET[$req_key]) {
                            $hasilOlahan_bysupplier[] = $item;
                        }
                    }
                    $masterData = $hasilOlahan_bysupplier;
                    break;
            }

        }
        else {
            $masterData = $reqData_000;
        }
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        // $arrHeaders = array(
        //     "kode"           => array(
        //         "label" => "kode",
        //     ),
        //     "nama"           => array(
        //         "label" => "produk",
        //     ),
        //     "no_part"        => array(
        //         "label" => "no part",
        //     ),
        //     // "kendaraan_nama" => array(
        //     //     "label" => "kendaraan",
        //     // ),
        //     "nomer"          => array(
        //         "label"  => "nomer",
        //         "format" => "formatField_he_format",
        //     ),
        //     "dtime"          => array(
        //         "label"  => "tanggal",
        //         "format" => "formatField_he_format",
        //     ),
        //     // "suppliers_nama" => array(
        //     //     "label" => "vendor",
        //     // ),
        //     // "keterangan"     => array(
        //     //     "label" => "note",
        //     // ),
        //     // --
        //     "harga"          => array(
        //         "label"  => "hpp",
        //         "format" => "formatField_he_format",
        //     ),
        //     "qty_debet"      => array(
        //         "label"  => "jumlah",
        //         "format" => "formatField_he_format",
        //     ),
        //     "debet"          => array(
        //         "label"  => "nilai",
        //         "format" => "formatField_he_format",
        //         "summary"    => true,
        //     ),
        //
        // );
        $arrHeaders = array(
            "fulldate"          => array(
                "label" => "Tanggal",
            ),
            "master_id"         => array(
                "label" => "mID",
            ),
            "kode"              => array(
                "label" => "kode",
            ),
            "extern_nama"       => array(
                "label" => "produk",
            ),
            "seller_nama"       => array(
                "label" => "salesman",
            ),
            // "seller_id"         => array(
            //     "label" => "selID",
            // ),
            // "oleh_nama"         => array(
            //     "label" => "oleh",
            // ),
            // "cabang_id"         => array(
            //     "label" => "cb",
            // ),
            "cabang_nama"       => array(
                "label" => "cabang",
            ),
            "_step_1_nomer"     => array(
                "label"      => "nomer order",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            "customer_nama"     => array(
                "label" => "customer",
                "attr"  => "width='100px'",
            ),
            "produk_satuan"     => array(
                "label" => "satuan",
            ),
            "spo_qty_debet_lap" => array(
                "label"      => "qty order",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "spo_debet_lap"     => array(
                "label"      => "nilai order",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "qty_debet_lap"     => array(
                "label"      => "sudah dikrim",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "debet_lap"         => array(
                "label"      => "nilai dikrim",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "qty_kredit_lap"    => array(
                "label"      => "qty outstanding",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "kredit_lap"        => array(
                "label"      => "nilai outstanding",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),

            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),

            // "sum_spo_debet"       => array(
            //     "label"  => "order",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
            // "sum_qty_kredit"       => array(
            //     "label"  => "outstanding",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
            // "sum_kredit"       => array(
            //     "label"  => "shipment",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
        );
        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung",
            "title"       => "detil raw data ",
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "data_id"     => "rawdata_" . randomNumber(1),
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "strGet"      => $strGet,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingproduk" . "$strGet",
            "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingsales" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    // -------------------------------------------
    /* ----------------------------------------------------
     * pengambilan data sudah dengan library
     * ----------------------------------------------------*/
    // rendering complite
    public function vieweoutstanding()
    {
        $this->load->helper("he_mass_table");
        // $this->load->model("Coms/ComRekeningTransaksiPembantu");
        // $ps = new ComRekeningTransaksiPembantu();

        $day_now = dtimeNow('d');
        $date_now = dtimeNow('Y-m-d');
        if (isset($_GET['date1'])) {
            $date_now = $_GET['date1'];
        }
        $datemin = $kemarin = date('Y-m-d', strtotime("-30 day", strtotime($date_now)));
        $dateplus = $kemarin = date('Y-m-d', strtotime("1 day", strtotime($date_now)));

        // $date_start = dtimeNow('Y-m-01');
        // if ($day_now < 14) {
        //     $date_start = $datemin;
        // }
        // cekBiru($datemin . " $date_start");

        $_GET['date1'] = $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "2022-06-01";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : $_GET['date2'] = dtimeNow('Y-m-d');

        /* -----------------------------------------------------------------------------------------------
       * navigasi bulan sebelumnya
       * -----------------------------------------------------------------------------------------------*/
        //<editor-fold desc="bulan sebelumnya">
        // $date_start_sebelumnya = dtimeNow('Y-m-01');
        $date_start_sebelumnya = previousMonth($date2) . "-01";
        $date_stop_sebelumnya = date("Y-m-t", strtotime($date_start_sebelumnya));
        $date_sebelumnya = array(
            "date1" => $date_start_sebelumnya,
            "date2" => $date_stop_sebelumnya,
        );
        $nama_bulan_sebelum = date("F Y", strtotime($date_start_sebelumnya));
        $str_geters = "";
        foreach (($date_sebelumnya + $_GET) as $item_key => $item_val) {
            $str_get = "$item_key=$item_val";

            if ($str_geters == "") {
                $str_geters .= "?$str_get";
            }
            else {
                $str_geters = "$str_geters" . "&$str_get";
            }
        }

        $ulr_bulan_sebelum = current_url() . $str_geters;
        $ulr_bulan_ini = current_url();
        //</editor-fold>

        /* -----------------------------------------------------------------------------------------------
         * navigasi bulan setelahnya
         * -----------------------------------------------------------------------------------------------*/
        //<editor-fold desc="bulan setelahnya">
        $date_start_setelahnya = aftersMonth($date2) . "-01";
        $date_stop_setelahnya = date("Y-m-t", strtotime($date_start_setelahnya));
        $date_setelahnya = array(
            "date1" => $date_start_setelahnya,
            "date2" => $date_stop_setelahnya,
        );
        $nama_bulan_setelah = date("F Y", strtotime($date_start_setelahnya));

        $str_geters = "";
        foreach (($date_setelahnya + $_GET) as $item_key => $item_val) {
            $str_get = "$item_key=$item_val";

            if ($str_geters == "") {
                $str_geters .= "?$str_get";
            }
            else {
                $str_geters = "$str_geters" . "&$str_get";
            }
        }

        $ulr_bulan_setelah = current_url() . $str_geters;
        $nama_bulan_ygtampil = formatTanggal($date2, "F Y");
        //</editor-fold>

        /* -----------------------------------------------------------------------------------------------
         * logik untuk mematikan butun bulan belum sukses jadi masih dimatikan
         * -----------------------------------------------------------------------------------------------*/
        // cekHijau("$date_now **$date2**  $date_start //// $date_start_setelahnya *** " . dtimeToSecond($date2));
        $btn_disabled = "";
        if (dtimeToSecond($date2) <= dtimeToSecond($date_start_setelahnya)) {
            // cekBiru(dtimeToSecond($date2) ."<br>" . dtimeToSecond($date_start_setelahnya));
            // $btn_disabled = "disabled";
        }

        /* -----------------------------------------------------------------------------------------------
         * button navigasi bulanan
         * -----------------------------------------------------------------------------------------------*/
        // cekMerah("$date_start_sebelumnya ****** $date_stop_sebelumnya ******* " . date('Y-m-t', strtotime('2022-02-01')));
        // cekHijau($ulr_bulan_sebelum . " **** $str_get **** $str_geters");
        $btn_td = "<button type='button' class='btn btn-danger' onclick=\"location.href='$ulr_bulan_sebelum'\"><i class='fa fa-chevron-circle-left'></i> $nama_bulan_sebelum </button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' disabled>$nama_bulan_ygtampil <i class='fa fa-chevron-circle-down'></i></button>";
        $btn_td .= "<button type='button' class='btn btn-danger' $btn_disabled onclick=\"location . href = '$ulr_bulan_ini'\">bulan ini</button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' $btn_disabled  onclick=\"location . href = '$ulr_bulan_setelah'\">$nama_bulan_setelah <i class='fa fa-chevron-circle-right '></i></button>";
        $add_td = "<td>$btn_td</td>";
        // -----------------------------------------------------------------------------------------------

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        $strDate = "";
        if (isset($_GET['date1'])) {

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        $masterData = array();
        $arrHeaders = array();
        $data = array(
            "mode"        => "indek",
            "title"       => "Outstanding Sales Order Penjualan",
            "subTitle"    => "Raw Data Sales Order",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "permaster",
            "add_td"      => $add_td,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => "2022-01-01",
            "date_max"    => dtimeNow('Y-m-d'),
            // "sum_null"    => base_url() . "laporan/Crm/perindekshow/produkoutstandingbln" . "$strGet",
            "sum_satu"    => base_url() . "laporan/Crm/cekoutstandingseller" . "$strGet",
            // "sum_null"    => base_url() . "laporan/Crm/cekoutstanding" . "$strGet",
            // "sum_null"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"    => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
            // "sum_empat"   => base_url() . "laporan/Crm/produkoutstandingblncabang" . "$strGet",
            // "sum_lima"    => base_url() . "laporan/Crm/produkoutstandingcabang" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function produkoutstandingbln()
    {
        // arrPrintHijau($_REQUEST);
        // arrPrintPink($_GET);
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $_GET['date1'] = $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "2022-06-01";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow("Y-m-d");

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            // $maxLimit = 50;
            // $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        /* ---------------------------------------------------------------------
 * filter khusus
 * ---------------------------------------------------------------------*/
        if (isset($_GET['ky'])) {
            $get_condites = array(
                $_GET['ky'] => $_GET[$_GET['ky']]
            );
            $this->db->where($get_condites);
        }

        $this->load->library("laporan/DataCrm");
        // $this->load->library("DataCrm");
        // mati_disini(__LINE__);
        $ots = new DataCrm();

        $main_datas = $ots->produkOutstanding($get_date2);
        // cekHere(sizeof($main_datas));
        // arrPrint($main_datas);


        $masterData = $main_datas;
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);

        $arrHeaders = array(

            "dtime"         => array(
                "label" => "Tanggal",
            ),
            "master_id"     => array(
                "label" => "mID",
            ),
            "kode"          => array(
                "label" => "kode",
            ),
            "extern_nama"   => array(
                "label" => "produk",
            ),
            "seller_nama"   => array(
                "label" => "salesman",
            ),
            // "seller_id"         => array(
            //     "label" => "selID",
            // ),
            // "oleh_nama"         => array(
            //     "label" => "oleh",
            // ),
            // "cabang_id"         => array(
            //     "label" => "cb",
            // ),
            "cabang_nama"   => array(
                "label" => "cabang",
            ),
            "_step_1_nomer" => array(
                "label"      => "nomer order",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            "customer_nama" => array(
                "label" => "customer",
                "attr"  => "width='100px'",
            ),
            "produk_satuan" => array(
                "label" => "satuan",
            ),

            "prev_qty_kredit"   => array(
                "label"      => "Previous outstanding qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                // "summary"    => true,
            ),
            "prev_kredit"       => array(
                "label"      => "Previous outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
            ),
            "spo_qty_debet_lap" => array(
                "label"      => "new order qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                // "summary"    => true,
            ),
            "spo_debet_lap"     => array(
                "label"      => "new order value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "rejected"          => array(
                "label"      => "rejected qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                // "summary"    => true,
            ),
            "closed"            => array(
                "label"      => "closed value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "qty_debet_lap"     => array(
                "label"      => "packing list qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "debet_lap"         => array(
                "label"      => "packing list value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "now_qty_kredit"    => array(
                "label"      => "new outstanding qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "now_kredit"        => array(
                "label"      => "new outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "qty_kredit_lap"    => array(
                "label"      => "total last outstanding qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "kredit_lap"        => array(
                "label"      => "total last outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),

            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),

            // "sum_spo_debet"       => array(
            //     "label"  => "order",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
            // "sum_qty_kredit"       => array(
            //     "label"  => "outstanding",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
            // "sum_kredit"       => array(
            //     "label"  => "shipment",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        $month_now = dtimeNow('m');
        $month_req = formatTanggal($get_date1, 'm');
        // cekHere("$month_req $month_now");
        if ($month_now == $month_req) {
            $judul_lap = "Month To Date " . formatTanggal($get_date1, 'F Y');
        }
        else {
            $judul_lap = " " . formatTanggal($get_date1, 'F Y');
        }

        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung_indek",
            "title"       => "Raw Data Outstanding " . $judul_lap,
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "data_id"     => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout"      => isset($_GET['layout']) ? $_GET['layout'] : true,
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "strGet"      => $strGet,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function produkoutstandingblnseller()
    {
        // arrPrintHijau($_REQUEST);
        // arrPrintPink($_GET);
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $_GET['date1'] = $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "2022-06-01";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow("Y-m-d");

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            // $maxLimit = 50;
            // $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $this->load->library("laporan/DataCrm");
        // $this->load->library("DataCrm");
        // mati_disini(__LINE__);
        $ots = new DataCrm();

        $main_datas = $ots->produkOutstanding($get_date2);
        // cekHere(sizeof($main_datas));
        // arrPrint($main_datas);

        /*
         *
         * -----------------------------------------------------------*/
        foreach ($main_datas as $item) {
            $spo_qty_debet_lap = $item['spo_qty_debet_lap'];
            $spo_debet_lap = $item['spo_debet_lap'];
            $qty_debet_lap = $item['qty_debet_lap'];
            $debet_lap = $item['debet_lap'];

            $qty_kredit_lap = $item['qty_kredit_lap'];
            $kredit_lap = $item['kredit_lap'];

            $prev_qty_kredit = $item['prev_qty_kredit'];
            $prev_kredit = $item['prev_kredit'];
            $now_qty_kredit = $item['now_qty_kredit'];
            $now_kredit = $item['now_kredit'];
            $subjek_id = $item['seller_id'];

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] += $spo_qty_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] += $spo_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_prev_qty_kredit'])) {
                $arrSumSubjek[$subjek_id]['sum_prev_qty_kredit'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_prev_qty_kredit'] += $prev_qty_kredit;

            if (!isset($arrSumSubjek[$subjek_id]['sum_prev_kredit'])) {
                $arrSumSubjek[$subjek_id]['sum_prev_kredit'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_prev_kredit'] += $prev_kredit;

            if (!isset($arrSumSubjek[$subjek_id]['sum_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_debet_lap'] += $debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_debet_lap'] += $qty_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_kredit_lap'] += $kredit_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'] += $qty_kredit_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_now_qty_kredit'])) {
                $arrSumSubjek[$subjek_id]['sum_now_qty_kredit'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_now_qty_kredit'] += $now_qty_kredit;
            if (!isset($arrSumSubjek[$subjek_id]['sum_now_kredit'])) {
                $arrSumSubjek[$subjek_id]['sum_now_kredit'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_now_kredit'] += $now_kredit;

            $arrSubjek[$subjek_id] = $item;
        }
        // arrPrintHijau($arrSubjek);
        //         mati_disini(__LINE__);

        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            // $customer_id = $itemParam['m_customerID'];
            $hasilOlahan_1[] = $itemParam + $arrSumSubjek[$subj_id];
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $hasilOlahan_1;
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        // mati_disini(__LINE__);

        $arrHeaders = array(

            // "dtime"         => array(
            //     "label" => "Tanggal",
            // ),
            // "master_id"     => array(
            //     "label" => "mID",
            // ),
            // "kode"          => array(
            //     "label" => "kode",
            // ),
            // "extern_nama"   => array(
            //     "label" => "produk",
            // ),
            "seller_nama" => array(
                "label"       => "salesman",
                "attr"        => "class='dt-nama-{pengenal}'",
                "collapsible" => array(
                    "layout" => false,
                    "key"    => "seller_id",
                    // "target" => "laporan/Crm/produkoutstandingblnproduk",
                    "target" => "laporan/Crm/produkoutstandingbln",
                ),
            ),
            "seller_id"   => array(
                "label" => "sID",
            ),
            // "oleh_nama"         => array(
            //     "label" => "oleh",
            // ),
            // "cabang_id"         => array(
            //     "label" => "cb",
            // ),
            // "cabang_nama"   => array(
            //     "label" => "cabang",
            // ),
            // "_step_1_nomer" => array(
            //     "label"      => "nomer order",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "customer_nama" => array(
            //     "label" => "customer",
            //     "attr"  => "width='100px'",
            // ),
            // "produk_satuan" => array(
            //     "label" => "satuan",
            // ),

            "sum_prev_qty_kredit"   => array(
                "label"      => "Previous outstanding qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
            ),
            "sum_prev_kredit"       => array(
                "label"      => "Previous outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                // "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
                "attr"       => "class='dt-nama-{pengenal} text-right bg-danger'",
                // "collapsible" => array(
                //     "layout" => false,
                //     "key"    => "seller_id",
                //     "target" => "laporan/Crm/produkoutstandingblnproduk",
                //     // "target" => "laporan/Crm/produkoutstandingbln",
                // ),
            ),
            "sum_spo_qty_debet_lap" => array(
                "label"      => "new order qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "sum_spo_debet_lap"     => array(
                "label"      => "new order value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "sum_qty_debet_lap"     => array(
                "label"      => "packing list qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "sum_debet_lap"         => array(
                "label"      => "packing list value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "sum_now_qty_kredit"    => array(
                "label"      => "new outstanding qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "sum_now_kredit"        => array(
                "label"      => "new outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "sum_qty_kredit_lap"    => array(
                "label"      => "total last outstanding qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "sum_kredit_lap"        => array(
                "label"      => "total last outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),

            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),

            // "sum_spo_debet"       => array(
            //     "label"  => "order",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
            // "sum_qty_kredit"       => array(
            //     "label"  => "outstanding",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
            // "sum_kredit"       => array(
            //     "label"  => "shipment",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        $month_now = dtimeNow('m');
        $month_req = formatTanggal($get_date1, 'm');
        // cekHere("$month_req $month_now");
        if ($month_now == $month_req) {
            $judul_lap = "Month To Date " . formatTanggal($get_date1, 'F Y');
        }
        else {
            $judul_lap = " " . formatTanggal($get_date1, 'F Y');
        }

        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung_indek",
            "title"       => "Laporan Outstanding Per Salesman" . $judul_lap,
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            "color_bar"   => "box-success",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "data_id"     => "salsman_" . randomNumber(1),
            "layout"      => isset($_GET['layout']) ? $_GET['layout'] : true,
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "strGet"      => $strGet,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingproduk" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingsales" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function produkoutstandingblncabang()
    {
        // arrPrintHijau($_REQUEST);
        // arrPrintPink($_GET);
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $_GET['date1'] = $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "2022-06-01";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow("Y-m-d");

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            // $maxLimit = 50;
            // $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $this->load->library("laporan/DataCrm");
        // $this->load->library("DataCrm");
        // mati_disini(__LINE__);
        $ots = new DataCrm();

        $main_datas = $ots->produkOutstanding($get_date2);
        // cekHere(sizeof($main_datas));
        // arrPrint($main_datas);

        /*
         *
         * -----------------------------------------------------------*/
        foreach ($main_datas as $item) {
            $spo_qty_debet_lap = $item['spo_qty_debet_lap'];
            $spo_debet_lap = $item['spo_debet_lap'];
            $qty_debet_lap = $item['qty_debet_lap'];
            $debet_lap = $item['debet_lap'];

            $qty_kredit_lap = $item['qty_kredit_lap'];
            $kredit_lap = $item['kredit_lap'];

            $prev_qty_kredit = $item['prev_qty_kredit'];
            $prev_kredit = $item['prev_kredit'];
            $now_qty_kredit = $item['now_qty_kredit'];
            $now_kredit = $item['now_kredit'];
            $subjek_id = $item['cabang_id'];

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] += $spo_qty_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] += $spo_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_prev_qty_kredit'])) {
                $arrSumSubjek[$subjek_id]['sum_prev_qty_kredit'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_prev_qty_kredit'] += $prev_qty_kredit;

            if (!isset($arrSumSubjek[$subjek_id]['sum_prev_kredit'])) {
                $arrSumSubjek[$subjek_id]['sum_prev_kredit'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_prev_kredit'] += $prev_kredit;

            if (!isset($arrSumSubjek[$subjek_id]['sum_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_debet_lap'] += $debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_debet_lap'] += $qty_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_kredit_lap'] += $kredit_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'] += $qty_kredit_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_now_qty_kredit'])) {
                $arrSumSubjek[$subjek_id]['sum_now_qty_kredit'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_now_qty_kredit'] += $now_qty_kredit;
            if (!isset($arrSumSubjek[$subjek_id]['sum_now_kredit'])) {
                $arrSumSubjek[$subjek_id]['sum_now_kredit'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_now_kredit'] += $now_kredit;

            $arrSubjek[$subjek_id] = $item;
        }
        // arrPrintHijau($arrSubjek);
        //         mati_disini(__LINE__);

        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            // $customer_id = $itemParam['m_customerID'];
            $hasilOlahan_1[] = $itemParam + $arrSumSubjek[$subj_id];
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $hasilOlahan_1;
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        // mati_disini(__LINE__);

        $arrHeaders = array(

            // "dtime"         => array(
            //     "label" => "Tanggal",
            // ),
            // "master_id"     => array(
            //     "label" => "mID",
            // ),
            // "kode"          => array(
            //     "label" => "kode",
            // ),
            // "extern_nama"   => array(
            //     "label" => "produk",
            // ),
            "cabang_nama" => array(
                "label"       => "cabang",
                "attr"        => "class='dt-nama-{pengenal}'",
                "collapsible" => array(
                    "layout" => false,
                    "key"    => "seller_id",
                    // "target" => "laporan/Crm/produkoutstandingblnproduk",
                    "target" => "laporan/Crm/produkoutstandingbln",
                ),
            ),
            // "seller_id"   => array(
            //     "label" => "sID",
            // ),
            // "oleh_nama"         => array(
            //     "label" => "oleh",
            // ),
            // "cabang_id"         => array(
            //     "label" => "cb",
            // ),
            // "cabang_nama"   => array(
            //     "label" => "cabang",
            // ),
            // "_step_1_nomer" => array(
            //     "label"      => "nomer order",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "customer_nama" => array(
            //     "label" => "customer",
            //     "attr"  => "width='100px'",
            // ),
            // "produk_satuan" => array(
            //     "label" => "satuan",
            // ),

            "sum_prev_qty_kredit"   => array(
                "label"      => "Previous outstanding qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
            ),
            "sum_prev_kredit"       => array(
                "label"       => "Previous outstanding value",
                "format"      => "formatField_he_format",
                "format_key"  => "harga",
                // "attr"       => "class='text-right bg-danger'",
                "summary"     => true,
                "attr"        => "class='dt-nama-{pengenal} text-right bg-danger'",
                "collapsible" => array(
                    "layout" => false,
                    "key"    => "seller_id",
                    "target" => "laporan/Crm/produkoutstandingblnproduk",
                    // "target" => "laporan/Crm/produkoutstandingbln",
                ),
            ),
            "sum_spo_qty_debet_lap" => array(
                "label"      => "new order qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "sum_spo_debet_lap"     => array(
                "label"      => "new order value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "sum_qty_debet_lap"     => array(
                "label"      => "packing list qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "sum_debet_lap"         => array(
                "label"      => "packing list value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "sum_now_qty_kredit"    => array(
                "label"      => "new outstanding qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "sum_now_kredit"        => array(
                "label"      => "new outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "sum_qty_kredit_lap"    => array(
                "label"      => "total last outstanding qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "sum_kredit_lap"        => array(
                "label"      => "total last outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),

            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),

            // "sum_spo_debet"       => array(
            //     "label"  => "order",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
            // "sum_qty_kredit"       => array(
            //     "label"  => "outstanding",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
            // "sum_kredit"       => array(
            //     "label"  => "shipment",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        $month_now = dtimeNow('m');
        $month_req = formatTanggal($get_date1, 'm');
        // cekHere("$month_req $month_now");
        if ($month_now == $month_req) {
            $judul_lap = "Month To Date " . formatTanggal($get_date1, 'F Y');
        }
        else {
            $judul_lap = " " . formatTanggal($get_date1, 'F Y');
        }

        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung",
            "title"       => "Laporan Outstanding Per Cabang " . $judul_lap,
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            "color_bar"   => "box-orange",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "data_id"     => "cabang_" . randomNumber(1),
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "strGet"      => $strGet,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingproduk" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingsales" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function produkoutstandingblnproduk()
    {
        // arrPrintHijau($_REQUEST);
        // arrPrintPink($_GET);
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $_GET['date1'] = $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "2022-06-01";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow("Y-m-d");

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            // $maxLimit = 50;
            // $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        /* ---------------------------------------------------------------------
 * filter khusus
 * ---------------------------------------------------------------------*/
        $get_condites = array();
        if (isset($_GET['ky'])) {
            $get_condites = array(
                $_GET['ky'] => $_GET[$_GET['ky']]
            );
            $this->db->where($get_condites);
        }

        $this->load->library("laporan/DataCrm");
        // $this->load->library("DataCrm");
        // mati_disini(__LINE__);
        $ots = new DataCrm();

        $main_datas = $ots->produkOutstanding($get_date2, $get_condites);
        // cekHere(sizeof($main_datas));
        // arrPrint($main_datas);
        // mati_disini(__LINE__);
        /*
         *
         * -----------------------------------------------------------*/
        foreach ($main_datas as $item) {
            $spo_qty_debet_lap = $item['spo_qty_debet_lap'];
            $spo_debet_lap = $item['spo_debet_lap'];
            $qty_debet_lap = $item['qty_debet_lap'];
            $debet_lap = $item['debet_lap'];

            $qty_kredit_lap = $item['qty_kredit_lap'];
            $kredit_lap = $item['kredit_lap'];

            $prev_qty_kredit = $item['prev_qty_kredit'];
            $prev_kredit = $item['prev_kredit'];
            $now_qty_kredit = $item['now_qty_kredit'];
            $now_kredit = $item['now_kredit'];
            $subjek_id = $item['extern_id'];

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] += $spo_qty_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] += $spo_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_prev_qty_kredit'])) {
                $arrSumSubjek[$subjek_id]['sum_prev_qty_kredit'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_prev_qty_kredit'] += $prev_qty_kredit;

            if (!isset($arrSumSubjek[$subjek_id]['sum_prev_kredit'])) {
                $arrSumSubjek[$subjek_id]['sum_prev_kredit'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_prev_kredit'] += $prev_kredit;

            if (!isset($arrSumSubjek[$subjek_id]['sum_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_debet_lap'] += $debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_debet_lap'] += $qty_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_kredit_lap'] += $kredit_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'] += $qty_kredit_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_now_qty_kredit'])) {
                $arrSumSubjek[$subjek_id]['sum_now_qty_kredit'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_now_qty_kredit'] += $now_qty_kredit;
            if (!isset($arrSumSubjek[$subjek_id]['sum_now_kredit'])) {
                $arrSumSubjek[$subjek_id]['sum_now_kredit'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_now_kredit'] += $now_kredit;

            $arrSubjek[$subjek_id] = $item;
        }
        // arrPrintHijau($arrSubjek);
        //         mati_disini(__LINE__);

        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            // $customer_id = $itemParam['m_customerID'];
            $hasilOlahan_1[] = $itemParam + $arrSumSubjek[$subj_id];
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $hasilOlahan_1;
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        // mati_disini(__LINE__);

        $arrHeaders = array(

            // "dtime"         => array(
            //     "label" => "Tanggal",
            // ),
            // "master_id"     => array(
            //     "label" => "mID",
            // ),
            // "kode"          => array(
            //     "label" => "kode",
            // ),
            // "extern_nama"   => array(
            //     "label" => "produk",
            // ),
            "extern_nama" => array(
                "label"       => "salesman",
                "attr"        => "class='dt-nama-{pengenal}'",
                "collapsible" => array(
                    "layout" => false,
                    "key"    => "seller_id",
                    "target" => "laporan/Crm/produkoutstandingbln",
                ),
            ),
            "extern_id"   => array(
                "label" => "pID",
            ),
            // "oleh_nama"         => array(
            //     "label" => "oleh",
            // ),
            // "cabang_id"         => array(
            //     "label" => "cb",
            // ),
            // "cabang_nama"   => array(
            //     "label" => "cabang",
            // ),
            // "_step_1_nomer" => array(
            //     "label"      => "nomer order",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "customer_nama" => array(
            //     "label" => "customer",
            //     "attr"  => "width='100px'",
            // ),
            // "produk_satuan" => array(
            //     "label" => "satuan",
            // ),

            "sum_prev_qty_kredit"   => array(
                "label"      => "Previous outstanding qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
            ),
            "sum_prev_kredit"       => array(
                "label"      => "Previous outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
            ),
            "sum_spo_qty_debet_lap" => array(
                "label"      => "new order qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "sum_spo_debet_lap"     => array(
                "label"      => "new order value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "sum_qty_debet_lap"     => array(
                "label"      => "packing list qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "sum_debet_lap"         => array(
                "label"      => "packing list value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "sum_now_qty_kredit"    => array(
                "label"      => "new outstanding qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "sum_now_kredit"        => array(
                "label"      => "new outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "sum_qty_kredit_lap"    => array(
                "label"      => "total last outstanding qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "sum_kredit_lap"        => array(
                "label"      => "total last outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),

            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),

            // "sum_spo_debet"       => array(
            //     "label"  => "order",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
            // "sum_qty_kredit"       => array(
            //     "label"  => "outstanding",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
            // "sum_kredit"       => array(
            //     "label"  => "shipment",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        $month_now = dtimeNow('m');
        $month_req = formatTanggal($get_date1, 'm');
        // cekHere("$month_req $month_now");
        if ($month_now == $month_req) {
            $judul_lap = "Month To Date " . formatTanggal($get_date1, 'F Y');
        }
        else {
            $judul_lap = " " . formatTanggal($get_date1, 'F Y');
        }

        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung",
            "title"       => "Laporan Outstanding Per Product " . $judul_lap,
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "data_id"     => "produk_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout"      => isset($_GET['layout']) ? $_GET['layout'] : true,
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "strGet"      => $strGet,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingproduk" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingsales" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function produkoutstandingblntransaksi()
    {
        // arrPrintHijau($_REQUEST);
        // arrPrintPink($_GET);
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $_GET['date1'] = $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "2022-06-01";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow("Y-m-d");

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            // $maxLimit = 50;
            // $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }
        $this->load->library("laporan/DataCrm");
        // $this->load->library("DataCrm");
        // mati_disini(__LINE__);
        $ots = new DataCrm();

        $main_datas = $ots->produkOutstanding($get_date2);
        // cekHere(sizeof($main_datas));
        // arrPrint($main_datas);
        // mati_disini(__LINE__);
        /*
         *
         * -----------------------------------------------------------*/
        foreach ($main_datas as $item) {
            $spo_qty_debet_lap = $item['spo_qty_debet_lap'];
            $spo_debet_lap = $item['spo_debet_lap'];
            $qty_debet_lap = $item['qty_debet_lap'];
            $debet_lap = $item['debet_lap'];

            $qty_kredit_lap = $item['qty_kredit_lap'];
            $kredit_lap = $item['kredit_lap'];

            $prev_qty_kredit = $item['prev_qty_kredit'];
            $prev_kredit = $item['prev_kredit'];
            $now_qty_kredit = $item['now_qty_kredit'];
            $now_kredit = $item['now_kredit'];
            $subjek_id = $item['master_id'];

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] += $spo_qty_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] += $spo_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_prev_qty_kredit'])) {
                $arrSumSubjek[$subjek_id]['sum_prev_qty_kredit'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_prev_qty_kredit'] += $prev_qty_kredit;

            if (!isset($arrSumSubjek[$subjek_id]['sum_prev_kredit'])) {
                $arrSumSubjek[$subjek_id]['sum_prev_kredit'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_prev_kredit'] += $prev_kredit;

            if (!isset($arrSumSubjek[$subjek_id]['sum_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_debet_lap'] += $debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_debet_lap'] += $qty_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_kredit_lap'] += $kredit_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'] += $qty_kredit_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_now_qty_kredit'])) {
                $arrSumSubjek[$subjek_id]['sum_now_qty_kredit'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_now_qty_kredit'] += $now_qty_kredit;
            if (!isset($arrSumSubjek[$subjek_id]['sum_now_kredit'])) {
                $arrSumSubjek[$subjek_id]['sum_now_kredit'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_now_kredit'] += $now_kredit;

            $arrSubjek[$subjek_id] = $item;
        }
        // arrPrintHijau($arrSubjek);
        //         mati_disini(__LINE__);

        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            // $customer_id = $itemParam['m_customerID'];
            $hasilOlahan_1[] = $itemParam + $arrSumSubjek[$subj_id];
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $hasilOlahan_1;
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        // mati_disini(__LINE__);

        $arrHeaders = array(

            // "dtime"         => array(
            //     "label" => "Tanggal",
            // ),
            "master_id"     => array(
                "label" => "mID",
            ),
            // "kode"          => array(
            //     "label" => "kode",
            // ),
            // "extern_nama"   => array(
            //     "label" => "produk",
            // ),
            // "extern_nama"   => array(
            //     "label" => "salesman",
            //     "attr"       => "class='dt-nama-{pengenal}'",
            //     "collapsible"  => array(
            //         "layout"     => false,
            //         "key"     => "seller_id",
            //         "target"  => "laporan/Crm/produkoutstandingbln",
            //     ),
            // ),
            // "extern_id"         => array(
            //     "label" => "pID",
            // ),
            "seller_nama"   => array(
                "label" => "oleh",
            ),
            // "cabang_id"         => array(
            //     "label" => "cb",
            // ),
            "cabang_nama"   => array(
                "label" => "cabang",
            ),
            "_step_1_nomer" => array(
                "label"      => "nomer order",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            "customer_nama" => array(
                "label" => "customer",
                "attr"  => "width='100px'",
            ),
            // "produk_satuan" => array(
            //     "label" => "satuan",
            // ),

            "sum_prev_qty_kredit"   => array(
                "label"      => "Previous outstanding qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
            ),
            "sum_prev_kredit"       => array(
                "label"      => "Previous outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
            ),
            "sum_spo_qty_debet_lap" => array(
                "label"      => "new order qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "sum_spo_debet_lap"     => array(
                "label"      => "new order value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "sum_qty_debet_lap"     => array(
                "label"      => "packing list qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "sum_debet_lap"         => array(
                "label"      => "packing list value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "sum_now_qty_kredit"    => array(
                "label"      => "new outstanding qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "sum_now_kredit"        => array(
                "label"      => "new outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "sum_qty_kredit_lap"    => array(
                "label"      => "total last outstanding qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "sum_kredit_lap"        => array(
                "label"      => "total last outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),

            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),

            // "sum_spo_debet"       => array(
            //     "label"  => "order",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
            // "sum_qty_kredit"       => array(
            //     "label"  => "outstanding",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
            // "sum_kredit"       => array(
            //     "label"  => "shipment",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        $month_now = dtimeNow('m');
        $month_req = formatTanggal($get_date1, 'm');
        // cekHere("$month_req $month_now");
        if ($month_now == $month_req) {
            $judul_lap = "Month To Date " . formatTanggal($get_date1, 'F Y');
        }
        else {
            $judul_lap = " " . formatTanggal($get_date1, 'F Y');
        }

        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung",
            "title"       => "Laporan Outstanding Per Transaksi " . $judul_lap,
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "data_id"     => "otorder_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "strGet"      => $strGet,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingproduk" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingsales" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    //--------------------------
    public function produkoutstandingproduk()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            // $maxLimit = 50;
            // $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = "582spo";
        $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");

        /* ---------------------------------------------------------------------
         * filter khusus
         * ---------------------------------------------------------------------*/
        if (isset($_GET['ky'])) {
            $get_condites = array(
                $_GET['ky'] => $_GET[$_GET['ky']]
            );
            $this->db->where($get_condites);
        }

        $src_000 = $ps->callOutstanding("persediaan_produk");
        $reqData_000 = $src_000['raw'];
        // arrPrintKuning($reqData_000);
        // $arrMaster_id = array();
        foreach ($reqData_000 as $item) {
            $spo_qty_debet_lap = $item['spo_qty_debet_lap'];
            $spo_debet_lap = $item['spo_debet_lap'];
            $qty_debet_lap = $item['qty_debet_lap'];
            $debet_lap = $item['debet_lap'];
            $qty_kredit_lap = $item['qty_kredit_lap'];
            $kredit_lap = $item['kredit_lap'];
            $subjek_id = $item['extern_id'];

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] += $spo_qty_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_debet_lap'] += $qty_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'] += $qty_kredit_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] += $spo_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_debet_lap'] += $debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_kredit_lap'] += $kredit_lap;

            $arrSubjek[$subjek_id] = $item;
        }

        // asort($arrMaster_id);
        // $minMaster_id = min($arrMaster_id);
        // $maxMaster_id = max($arrMaster_id);
        // // cekBiru($arrMaster_id);
        // // cekMerah());
        // // cekMerah();
        // // matiHere();
        // if (isset($_GET['date1']) && sizeof($arrMaster_id) > 0) {
        //     $condites = array(
        //         "master_id >=" => $minMaster_id,
        //         "master_id <=" => $maxMaster_id,
        //     );
        //     $this->db->where($condites);
        //
        //     $strDate .= formatField_he_format("fulldate", $get_date1);
        //     $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        // }
        //
        // $jenisTr = array(
        //     "582spo",
        //     "582so",
        //     "582pkd",
        //     "582spd",
        //     // "967"
        // );
        // $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // // $ps->setJenisTr("582");
        // $src = $ps->callCacheProduk("persediaan_produk");
        // $reqData = $src['data'];
        // // showLast_query("kuning");
        // // cekBiru(sizeof($reqData[0]));
        // // arrPrintKuning($reqData);
        // /* --------------------------------------------------------------------------------------------------
        //  *peparasi data harus 3 step
        //  * #1 pengumpulan data transaksi (main)
        //  * --------------------------------------------------------------------------------------------------*/
        // $olahan = array();
        // foreach ($reqData as $masterDatum) {
        //     $spo_oleh_id = isset($masterDatum['582spo_oleh_id']) ? $masterDatum['582spo_oleh_id'] : "";
        //     $spo_oleh_nama = isset($masterDatum['582spo_oleh_nama']) ? $masterDatum['582spo_oleh_nama'] : "";
        //     $master_id = isset($masterDatum['582spo_master_id']) ? $masterDatum['582spo_master_id'] : "";
        //
        //     $olahan[$master_id] = $masterDatum;
        // }
        // /* --------------------------------------------------------------------------------------------------
        //  * #2 membuat tambahan kolom summary
        //  * --------------------------------------------------------------------------------------------------*/
        // foreach ($reqData as $reqDatum) {
        //
        //     $spo_oleh_id = isset($reqDatum['582spo_oleh_id']) ? $reqDatum['582spo_oleh_id'] : "";
        //     $spo_debet_lap = isset($reqDatum['582spo_debet_lap']) ? $reqDatum['582spo_debet_lap'] : "";
        //     $spo_kredit_lap = isset($reqDatum['582spo_kredit_lap']) ? $reqDatum['582spo_kredit_lap'] : "";
        //     $spo_master_id = isset($reqDatum['582spo_master_id']) ? $reqDatum['582spo_master_id'] : "";
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spo_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_debet'] += $spo_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spo_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_kredit'] += $spo_kredit_lap;
        //     // ------------------------------------------
        //     $spd_debet_lap = isset($reqDatum['582spd_debet_lap']) ? $reqDatum['582spd_debet_lap'] : 0;
        //     $spd_kredit_lap = isset($reqDatum['582spd_kredit_lap']) ? $reqDatum['582spd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_debet'] += $spd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_kredit'] += $spd_kredit_lap;
        //     // ------------------------------------------
        //     $pkd_debet_lap = isset($reqDatum['582pkd_debet_lap']) ? $reqDatum['582pkd_debet_lap'] : 0;
        //     $pkd_kredit_lap = isset($reqDatum['582pkd_kredit_lap']) ? $reqDatum['582pkd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_debet'] += $pkd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_kredit'] += $pkd_kredit_lap;
        //     // ------------------------------------------
        // }
        //
        // // arrPrintPink($req_datas);
        // // matiHere();
        //

        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            // $customer_id = $itemParam['m_customerID'];
            $hasilOlahan_1[] = $itemParam + $arrSumSubjek[$subj_id];
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $hasilOlahan_1;
        //
        // cekMerah(sizeof($gabunganData[0]['505'][0]));
        // arrPrintKuning($gabunganData[0]['505'][0]);
        // arrPrintKuning($gabunganData);
        // $masterData = $reqData;
        // matiHere(__LINE__);
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // cekHijau(sizeof($masterData));
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "kode"   => array(
                "label" => "kode",
            ),
            "nama"   => array(
                "label" => "produk",
            ),
            // "582spo__step_1_nomer"          => array(
            //     "label"      => "spo",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582so__step_2_nomer"          => array(
            //     "label"      => "soa",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582pkd__step_3_nomer"          => array(
            //     "label"      => "pkd",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582spd__step_4_nomer"          => array(
            //     "label"      => "spd",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            "satuan" => array(
                "label" => "satuan",
            ),

            "sum_spo_qty_debet_lap" => array(
                "label"   => "qty order",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_spo_debet_lap"     => array(
                "label"   => "nilai order",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_qty_debet_lap"     => array(
                "label"   => "qty dikitrim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_debet_lap"         => array(
                "label"   => "nilai dikitrim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_qty_kredit_lap"    => array(
                "label"   => "qty outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_kredit_lap"        => array(
                "label"   => "nilai outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
                "links"   => array(
                    "target"  => "laporan/Crm/produkoutstandingraw",
                    "title"   => "Transaksi per salesman",
                    "key"     => "extern_id",
                    "heading" => "extern_nama",
                ),
            ),
        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung",
            "title"       => "laporan produk outstanding",
            "subTitle"    => "Raw Data CRM",
            "modul_path"  => $this->modul_path,
            "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "produkoutstandingproduk_" . dtimeToSecond(dtimeNow()),
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function produkoutstandingproduk_asli()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            // $maxLimit = 50;
            // $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = "582spo";
        $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src_000 = $ps->callOutstanding("persediaan_produk");
        $reqData_000 = $src_000['produk'];
        // arrPrintKuning($reqData_000);
        // $arrMaster_id = array();
        // foreach ($reqData_000 as $item) {
        //     $master_id_000 = $item['582spo_master_id'];
        //
        //     $arrMaster_id[] = $master_id_000;
        // }
        // asort($arrMaster_id);
        // $minMaster_id = min($arrMaster_id);
        // $maxMaster_id = max($arrMaster_id);
        // // cekBiru($arrMaster_id);
        // // cekMerah());
        // // cekMerah();
        // // matiHere();
        // if (isset($_GET['date1']) && sizeof($arrMaster_id) > 0) {
        //     $condites = array(
        //         "master_id >=" => $minMaster_id,
        //         "master_id <=" => $maxMaster_id,
        //     );
        //     $this->db->where($condites);
        //
        //     $strDate .= formatField_he_format("fulldate", $get_date1);
        //     $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        // }
        //
        // $jenisTr = array(
        //     "582spo",
        //     "582so",
        //     "582pkd",
        //     "582spd",
        //     // "967"
        // );
        // $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // // $ps->setJenisTr("582");
        // $src = $ps->callCacheProduk("persediaan_produk");
        // $reqData = $src['data'];
        // // showLast_query("kuning");
        // // cekBiru(sizeof($reqData[0]));
        // // arrPrintKuning($reqData);
        // /* --------------------------------------------------------------------------------------------------
        //  *peparasi data harus 3 step
        //  * #1 pengumpulan data transaksi (main)
        //  * --------------------------------------------------------------------------------------------------*/
        // $olahan = array();
        // foreach ($reqData as $masterDatum) {
        //     $spo_oleh_id = isset($masterDatum['582spo_oleh_id']) ? $masterDatum['582spo_oleh_id'] : "";
        //     $spo_oleh_nama = isset($masterDatum['582spo_oleh_nama']) ? $masterDatum['582spo_oleh_nama'] : "";
        //     $master_id = isset($masterDatum['582spo_master_id']) ? $masterDatum['582spo_master_id'] : "";
        //
        //     $olahan[$master_id] = $masterDatum;
        // }
        // /* --------------------------------------------------------------------------------------------------
        //  * #2 membuat tambahan kolom summary
        //  * --------------------------------------------------------------------------------------------------*/
        // foreach ($reqData as $reqDatum) {
        //
        //     $spo_oleh_id = isset($reqDatum['582spo_oleh_id']) ? $reqDatum['582spo_oleh_id'] : "";
        //     $spo_debet_lap = isset($reqDatum['582spo_debet_lap']) ? $reqDatum['582spo_debet_lap'] : "";
        //     $spo_kredit_lap = isset($reqDatum['582spo_kredit_lap']) ? $reqDatum['582spo_kredit_lap'] : "";
        //     $spo_master_id = isset($reqDatum['582spo_master_id']) ? $reqDatum['582spo_master_id'] : "";
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spo_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_debet'] += $spo_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spo_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_kredit'] += $spo_kredit_lap;
        //     // ------------------------------------------
        //     $spd_debet_lap = isset($reqDatum['582spd_debet_lap']) ? $reqDatum['582spd_debet_lap'] : 0;
        //     $spd_kredit_lap = isset($reqDatum['582spd_kredit_lap']) ? $reqDatum['582spd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_debet'] += $spd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_kredit'] += $spd_kredit_lap;
        //     // ------------------------------------------
        //     $pkd_debet_lap = isset($reqDatum['582pkd_debet_lap']) ? $reqDatum['582pkd_debet_lap'] : 0;
        //     $pkd_kredit_lap = isset($reqDatum['582pkd_kredit_lap']) ? $reqDatum['582pkd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_debet'] += $pkd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_kredit'] += $pkd_kredit_lap;
        //     // ------------------------------------------
        // }
        //
        // // arrPrintPink($req_datas);
        // // matiHere();
        //
        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        // $hasilOlahan_1 = array();
        // foreach ($olahan as $master_id => $itemParam) {
        //     // $customer_id = $itemParam['m_customerID'];
        //     $hasilOlahan_1[$master_id] = $itemParam + $req_datas[$master_id];
        //     // $hasilOlahan[$customer_id] = $itemParam;
        // }

        $masterData = $reqData_000;
        //
        // cekMerah(sizeof($gabunganData[0]['505'][0]));
        // arrPrintKuning($gabunganData[0]['505'][0]);
        // arrPrintKuning($gabunganData);
        // $masterData = $reqData;
        // matiHere(__LINE__);
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // cekHijau(sizeof($masterData));
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "kode"           => array(
                "label" => "kode",
            ),
            "nama"           => array(
                "label" => "produk",
            ),
            // "582spo__step_1_nomer"          => array(
            //     "label"      => "spo",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582so__step_2_nomer"          => array(
            //     "label"      => "soa",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582pkd__step_3_nomer"          => array(
            //     "label"      => "pkd",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582spd__step_4_nomer"          => array(
            //     "label"      => "spd",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),

            // "sum_spo_debet"       => array(
            //     "label"  => "order",
            //     "format" => "formatField_he_format",
            //     "attr"   => "class='text-right'",
            //     "summary"   => true,
            // ),
            "sum_qty_kredit" => array(
                "label"   => "outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_kredit"     => array(
                "label"   => "nilai outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung",
            "title"       => "laporan produk outstanding",
            "subTitle"    => "Raw Data CRM",
            "modul_path"  => $this->modul_path,
            "color_bar"   => "box-warning",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "produkoutstandingproduk",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    //--------------------------
    public function produkoutstanditransaksi()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            // $maxLimit = 50;
            // $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = "582spo";
        $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src_000 = $ps->callOutstanding("persediaan_produk");
        $reqData_000 = $src_000['raw'];
        // arrPrintKuning($reqData_000);
        // $arrMaster_id = array();
        foreach ($reqData_000 as $item) {
            $spo_debet_lap = $item['spo_debet_lap'];
            $debet_lap = $item['debet_lap'];
            $kredit_lap = $item['kredit_lap'];
            $subjek_id = $item['master_id'];

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] += $spo_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_debet_lap'] += $debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_kredit_lap'] += $kredit_lap;

            $arrSubjek[$subjek_id] = $item;
        }

        // asort($arrMaster_id);
        // $minMaster_id = min($arrMaster_id);
        // $maxMaster_id = max($arrMaster_id);
        // // cekBiru($arrMaster_id);
        // // cekMerah());
        // // cekMerah();
        // // matiHere();
        // if (isset($_GET['date1']) && sizeof($arrMaster_id) > 0) {
        //     $condites = array(
        //         "master_id >=" => $minMaster_id,
        //         "master_id <=" => $maxMaster_id,
        //     );
        //     $this->db->where($condites);
        //
        //     $strDate .= formatField_he_format("fulldate", $get_date1);
        //     $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        // }
        //
        // $jenisTr = array(
        //     "582spo",
        //     "582so",
        //     "582pkd",
        //     "582spd",
        //     // "967"
        // );
        // $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // // $ps->setJenisTr("582");
        // $src = $ps->callCacheProduk("persediaan_produk");
        // $reqData = $src['data'];
        // // showLast_query("kuning");
        // // cekBiru(sizeof($reqData[0]));
        // // arrPrintKuning($reqData);
        // /* --------------------------------------------------------------------------------------------------
        //  *peparasi data harus 3 step
        //  * #1 pengumpulan data transaksi (main)
        //  * --------------------------------------------------------------------------------------------------*/
        // $olahan = array();
        // foreach ($reqData as $masterDatum) {
        //     $spo_oleh_id = isset($masterDatum['582spo_oleh_id']) ? $masterDatum['582spo_oleh_id'] : "";
        //     $spo_oleh_nama = isset($masterDatum['582spo_oleh_nama']) ? $masterDatum['582spo_oleh_nama'] : "";
        //     $master_id = isset($masterDatum['582spo_master_id']) ? $masterDatum['582spo_master_id'] : "";
        //
        //     $olahan[$master_id] = $masterDatum;
        // }
        // /* --------------------------------------------------------------------------------------------------
        //  * #2 membuat tambahan kolom summary
        //  * --------------------------------------------------------------------------------------------------*/
        // foreach ($reqData as $reqDatum) {
        //
        //     $spo_oleh_id = isset($reqDatum['582spo_oleh_id']) ? $reqDatum['582spo_oleh_id'] : "";
        //     $spo_debet_lap = isset($reqDatum['582spo_debet_lap']) ? $reqDatum['582spo_debet_lap'] : "";
        //     $spo_kredit_lap = isset($reqDatum['582spo_kredit_lap']) ? $reqDatum['582spo_kredit_lap'] : "";
        //     $spo_master_id = isset($reqDatum['582spo_master_id']) ? $reqDatum['582spo_master_id'] : "";
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spo_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_debet'] += $spo_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spo_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_kredit'] += $spo_kredit_lap;
        //     // ------------------------------------------
        //     $spd_debet_lap = isset($reqDatum['582spd_debet_lap']) ? $reqDatum['582spd_debet_lap'] : 0;
        //     $spd_kredit_lap = isset($reqDatum['582spd_kredit_lap']) ? $reqDatum['582spd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_debet'] += $spd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_kredit'] += $spd_kredit_lap;
        //     // ------------------------------------------
        //     $pkd_debet_lap = isset($reqDatum['582pkd_debet_lap']) ? $reqDatum['582pkd_debet_lap'] : 0;
        //     $pkd_kredit_lap = isset($reqDatum['582pkd_kredit_lap']) ? $reqDatum['582pkd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_debet'] += $pkd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_kredit'] += $pkd_kredit_lap;
        //     // ------------------------------------------
        // }
        //
        // // arrPrintPink($req_datas);
        // // matiHere();
        //

        //  /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            // $customer_id = $itemParam['m_customerID'];
            $hasilOlahan_1[] = $itemParam + $arrSumSubjek[$subj_id];
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $hasilOlahan_1;
        //
        // cekMerah(sizeof($gabunganData[0]['505'][0]));
        // arrPrintKuning($gabunganData[0]['505'][0]);
        // arrPrintKuning($gabunganData);
        // $masterData = $reqData;
        // matiHere(__LINE__);
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // cekHijau(sizeof($masterData));
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "fulldate"          => array(
                "label" => "tanggal",
            ),
            "master_id"         => array(
                "label" => "mID",
            ),
            "_step_1_nomer"     => array(
                "label"      => "nomer order",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            "seller_nama"       => array(
                "label" => "oleh",
            ),
            "customer_nama"     => array(
                "label" => "customer",
            ),

            // "582spd__step_4_nomer"          => array(
            //     "label"      => "spd",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            "sum_spo_debet_lap" => array(
                "label"   => "nilai order",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_debet_lap"     => array(
                "label"   => "nilai dikitrim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_kredit_lap"    => array(
                "label"   => "nilai outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
                "links"   => array(
                    "target"  => "laporan/Crm/produkoutstandingraw",
                    "title"   => "Transaksi per vendor",
                    "key"     => "master_id",
                    "heading" => "_step_1_nomer",
                ),
            ),
        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung",
            "title"       => "laporan order outstanding",
            "subTitle"    => "Raw Data CRM",
            "modul_path"  => $this->modul_path,
            "color_bar"   => "box-danger",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "produkoutstandingmaster_" . dtimeToSecond(dtimeNow()),
            "loader_div"  => "sum_satu",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function produkoutstanditransaksi_asli()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            // $maxLimit = 50;
            // $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = "582spo";
        $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src_000 = $ps->callOutstanding("persediaan_produk");
        $reqData_000 = $src_000['master'];
        // arrPrintKuning($reqData_000);
        // $arrMaster_id = array();
        // foreach ($reqData_000 as $item) {
        //     $master_id_000 = $item['582spo_master_id'];
        //
        //     $arrMaster_id[] = $master_id_000;
        // }
        // asort($arrMaster_id);
        // $minMaster_id = min($arrMaster_id);
        // $maxMaster_id = max($arrMaster_id);
        // // cekBiru($arrMaster_id);
        // // cekMerah());
        // // cekMerah();
        // // matiHere();
        // if (isset($_GET['date1']) && sizeof($arrMaster_id) > 0) {
        //     $condites = array(
        //         "master_id >=" => $minMaster_id,
        //         "master_id <=" => $maxMaster_id,
        //     );
        //     $this->db->where($condites);
        //
        //     $strDate .= formatField_he_format("fulldate", $get_date1);
        //     $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        // }
        //
        // $jenisTr = array(
        //     "582spo",
        //     "582so",
        //     "582pkd",
        //     "582spd",
        //     // "967"
        // );
        // $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // // $ps->setJenisTr("582");
        // $src = $ps->callCacheProduk("persediaan_produk");
        // $reqData = $src['data'];
        // // showLast_query("kuning");
        // // cekBiru(sizeof($reqData[0]));
        // // arrPrintKuning($reqData);
        // /* --------------------------------------------------------------------------------------------------
        //  *peparasi data harus 3 step
        //  * #1 pengumpulan data transaksi (main)
        //  * --------------------------------------------------------------------------------------------------*/
        // $olahan = array();
        // foreach ($reqData as $masterDatum) {
        //     $spo_oleh_id = isset($masterDatum['582spo_oleh_id']) ? $masterDatum['582spo_oleh_id'] : "";
        //     $spo_oleh_nama = isset($masterDatum['582spo_oleh_nama']) ? $masterDatum['582spo_oleh_nama'] : "";
        //     $master_id = isset($masterDatum['582spo_master_id']) ? $masterDatum['582spo_master_id'] : "";
        //
        //     $olahan[$master_id] = $masterDatum;
        // }
        // /* --------------------------------------------------------------------------------------------------
        //  * #2 membuat tambahan kolom summary
        //  * --------------------------------------------------------------------------------------------------*/
        // foreach ($reqData as $reqDatum) {
        //
        //     $spo_oleh_id = isset($reqDatum['582spo_oleh_id']) ? $reqDatum['582spo_oleh_id'] : "";
        //     $spo_debet_lap = isset($reqDatum['582spo_debet_lap']) ? $reqDatum['582spo_debet_lap'] : "";
        //     $spo_kredit_lap = isset($reqDatum['582spo_kredit_lap']) ? $reqDatum['582spo_kredit_lap'] : "";
        //     $spo_master_id = isset($reqDatum['582spo_master_id']) ? $reqDatum['582spo_master_id'] : "";
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spo_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_debet'] += $spo_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spo_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_kredit'] += $spo_kredit_lap;
        //     // ------------------------------------------
        //     $spd_debet_lap = isset($reqDatum['582spd_debet_lap']) ? $reqDatum['582spd_debet_lap'] : 0;
        //     $spd_kredit_lap = isset($reqDatum['582spd_kredit_lap']) ? $reqDatum['582spd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_debet'] += $spd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_kredit'] += $spd_kredit_lap;
        //     // ------------------------------------------
        //     $pkd_debet_lap = isset($reqDatum['582pkd_debet_lap']) ? $reqDatum['582pkd_debet_lap'] : 0;
        //     $pkd_kredit_lap = isset($reqDatum['582pkd_kredit_lap']) ? $reqDatum['582pkd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_debet'] += $pkd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_kredit'] += $pkd_kredit_lap;
        //     // ------------------------------------------
        // }
        //
        // // arrPrintPink($req_datas);
        // // matiHere();
        //
        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        // $hasilOlahan_1 = array();
        // foreach ($olahan as $master_id => $itemParam) {
        //     // $customer_id = $itemParam['m_customerID'];
        //     $hasilOlahan_1[$master_id] = $itemParam + $req_datas[$master_id];
        //     // $hasilOlahan[$customer_id] = $itemParam;
        // }

        $masterData = $reqData_000;
        //
        // cekMerah(sizeof($gabunganData[0]['505'][0]));
        // arrPrintKuning($gabunganData[0]['505'][0]);
        // arrPrintKuning($gabunganData);
        // $masterData = $reqData;
        // matiHere(__LINE__);
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // cekHijau(sizeof($masterData));
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "fulldate"      => array(
                "label" => "tanggal",
            ),
            "master_id"     => array(
                "label" => "mID",
            ),
            "_step_1_nomer" => array(
                "label"      => "nomer order",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            "seller_nama"   => array(
                "label" => "oleh",
            ),
            "customer_nama" => array(
                "label" => "customer",
            ),

            // "582spd__step_4_nomer"          => array(
            //     "label"      => "spd",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            "sum_spo_debet" => array(
                "label"   => "nilai order",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),

            "sum_debet"  => array(
                "label"   => "nilai dikirim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            // "sum_qty_kredit" => array(
            //     "label"   => "outstanding",
            //     "format"  => "formatField_he_format",
            //     "attr"    => "class='text-right'",
            //     "summary" => true,
            // ),
            "sum_kredit" => array(
                "label"   => "nilai outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung",
            "title"       => "laporan order outstanding",
            "subTitle"    => "Raw Data CRM",
            "modul_path"  => $this->modul_path,
            "color_bar"   => "box-danger",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "produkoutstandingmaster",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    //--------------------------
    public function produkoutstandingsales()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            // $maxLimit = 50;
            // $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = "582spo";
        $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");

        /* ---------------------------------------------------------------------
         * filter khusus
         * ---------------------------------------------------------------------*/
        if (isset($_GET['ky'])) {
            $get_condites = array(
                $_GET['ky'] => $_GET[$_GET['ky']]
            );
            $this->db->where($get_condites);
        }

        $src_000 = $ps->callOutstanding("persediaan_produk");
        $reqData_000 = $src_000['raw'];
        // arrPrintKuning($reqData_000);
        // $arrMaster_id = array();
        foreach ($reqData_000 as $item) {
            $spo_qty_debet_lap = $item['spo_qty_debet_lap'];
            $spo_debet_lap = $item['spo_debet_lap'];
            $qty_debet_lap = $item['qty_debet_lap'];
            $debet_lap = $item['debet_lap'];
            $qty_kredit_lap = $item['qty_kredit_lap'];
            $kredit_lap = $item['kredit_lap'];
            $subjek_id = $item['seller_id'];

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] += $spo_qty_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] += $spo_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_debet_lap'] += $debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_debet_lap'] += $qty_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_kredit_lap'] += $kredit_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_kredit_lap'] += $qty_kredit_lap;

            $arrSubjek[$subjek_id] = $item;
        }

        // asort($arrMaster_id);
        // $minMaster_id = min($arrMaster_id);
        // $maxMaster_id = max($arrMaster_id);
        // // cekBiru($arrMaster_id);
        // // cekMerah());
        // // cekMerah();
        // // matiHere();
        // if (isset($_GET['date1']) && sizeof($arrMaster_id) > 0) {
        //     $condites = array(
        //         "master_id >=" => $minMaster_id,
        //         "master_id <=" => $maxMaster_id,
        //     );
        //     $this->db->where($condites);
        //
        //     $strDate .= formatField_he_format("fulldate", $get_date1);
        //     $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        // }
        //
        // $jenisTr = array(
        //     "582spo",
        //     "582so",
        //     "582pkd",
        //     "582spd",
        //     // "967"
        // );
        // $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // // $ps->setJenisTr("582");
        // $src = $ps->callCacheProduk("persediaan_produk");
        // $reqData = $src['data'];
        // // showLast_query("kuning");
        // // cekBiru(sizeof($reqData[0]));
        // // arrPrintKuning($reqData);
        // /* --------------------------------------------------------------------------------------------------
        //  *peparasi data harus 3 step
        //  * #1 pengumpulan data transaksi (main)
        //  * --------------------------------------------------------------------------------------------------*/
        // $olahan = array();
        // foreach ($reqData as $masterDatum) {
        //     $spo_oleh_id = isset($masterDatum['582spo_oleh_id']) ? $masterDatum['582spo_oleh_id'] : "";
        //     $spo_oleh_nama = isset($masterDatum['582spo_oleh_nama']) ? $masterDatum['582spo_oleh_nama'] : "";
        //     $master_id = isset($masterDatum['582spo_master_id']) ? $masterDatum['582spo_master_id'] : "";
        //
        //     $olahan[$master_id] = $masterDatum;
        // }
        // /* --------------------------------------------------------------------------------------------------
        //  * #2 membuat tambahan kolom summary
        //  * --------------------------------------------------------------------------------------------------*/
        // foreach ($reqData as $reqDatum) {
        //
        //     $spo_oleh_id = isset($reqDatum['582spo_oleh_id']) ? $reqDatum['582spo_oleh_id'] : "";
        //     $spo_debet_lap = isset($reqDatum['582spo_debet_lap']) ? $reqDatum['582spo_debet_lap'] : "";
        //     $spo_kredit_lap = isset($reqDatum['582spo_kredit_lap']) ? $reqDatum['582spo_kredit_lap'] : "";
        //     $spo_master_id = isset($reqDatum['582spo_master_id']) ? $reqDatum['582spo_master_id'] : "";
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spo_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_debet'] += $spo_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spo_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_kredit'] += $spo_kredit_lap;
        //     // ------------------------------------------
        //     $spd_debet_lap = isset($reqDatum['582spd_debet_lap']) ? $reqDatum['582spd_debet_lap'] : 0;
        //     $spd_kredit_lap = isset($reqDatum['582spd_kredit_lap']) ? $reqDatum['582spd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_debet'] += $spd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_kredit'] += $spd_kredit_lap;
        //     // ------------------------------------------
        //     $pkd_debet_lap = isset($reqDatum['582pkd_debet_lap']) ? $reqDatum['582pkd_debet_lap'] : 0;
        //     $pkd_kredit_lap = isset($reqDatum['582pkd_kredit_lap']) ? $reqDatum['582pkd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_debet'] += $pkd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_kredit'] += $pkd_kredit_lap;
        //     // ------------------------------------------
        // }
        //
        // // arrPrintPink($req_datas);
        // // matiHere();
        //

        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            // $customer_id = $itemParam['m_customerID'];
            $hasilOlahan_1[] = $itemParam + $arrSumSubjek[$subj_id];
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $hasilOlahan_1;
        //
        // cekMerah(sizeof($gabunganData[0]['505'][0]));
        // arrPrintKuning($gabunganData[0]['505'][0]);
        // arrPrintKuning($gabunganData);
        // $masterData = $reqData;
        // matiHere(__LINE__);
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // cekHijau(sizeof($masterData));
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "seller_id"   => array(
                "label" => "sID",
            ),
            "seller_nama" => array(
                "label" => "salesman",
            ),
            // "582spo__step_1_nomer"          => array(
            //     "label"      => "spo",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582so__step_2_nomer"          => array(
            //     "label"      => "soa",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582pkd__step_3_nomer"          => array(
            //     "label"      => "pkd",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582spd__step_4_nomer"          => array(
            //     "label"      => "spd",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),

            "sum_spo_qty_debet_lap" => array(
                "label"   => "qty order",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_spo_debet_lap"     => array(
                "label"   => "nilai order",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_qty_debet_lap"     => array(
                "label"   => "qty dikitrim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_debet_lap"         => array(
                "label"   => "nilai dikitrim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_qty_kredit_lap"    => array(
                "label"   => "qty outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
                "links"   => array(
                    "target"  => "laporan/Crm/produkoutstandingraw",
                    "title"   => "Transaksi per salesman",
                    "key"     => "seller_id",
                    "heading" => "seller_nama",
                ),
            ),
            "sum_kredit_lap"        => array(
                "label"   => "nilai outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
                "links"   => array(
                    "target"  => "laporan/Crm/produkoutstandingraw",
                    "title"   => "Transaksi per salesman",
                    "key"     => "seller_id",
                    "heading" => "seller_nama",
                ),
            ),
        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung",
            "title"       => "laporan salesman outstanding",
            "subTitle"    => "Raw Data CRM",
            "modul_path"  => $this->modul_path,
            "color_bar"   => "box-success",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "produkoutstandingsales_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "modal_size"  => "xl",
            "loader_div"  => "sum_tiga",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function produkoutstandingsales_ori()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            // $maxLimit = 50;
            // $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = "582spo";
        $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src_000 = $ps->callOutstanding("persediaan_produk");
        $reqData_000 = $src_000['salesman'];
        // arrPrintKuning($reqData_000);
        // $arrMaster_id = array();
        // foreach ($reqData_000 as $item) {
        //     $master_id_000 = $item['582spo_master_id'];
        //
        //     $arrMaster_id[] = $master_id_000;
        // }
        // asort($arrMaster_id);
        // $minMaster_id = min($arrMaster_id);
        // $maxMaster_id = max($arrMaster_id);
        // // cekBiru($arrMaster_id);
        // // cekMerah());
        // // cekMerah();
        // // matiHere();
        // if (isset($_GET['date1']) && sizeof($arrMaster_id) > 0) {
        //     $condites = array(
        //         "master_id >=" => $minMaster_id,
        //         "master_id <=" => $maxMaster_id,
        //     );
        //     $this->db->where($condites);
        //
        //     $strDate .= formatField_he_format("fulldate", $get_date1);
        //     $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        // }
        //
        // $jenisTr = array(
        //     "582spo",
        //     "582so",
        //     "582pkd",
        //     "582spd",
        //     // "967"
        // );
        // $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // // $ps->setJenisTr("582");
        // $src = $ps->callCacheProduk("persediaan_produk");
        // $reqData = $src['data'];
        // // showLast_query("kuning");
        // // cekBiru(sizeof($reqData[0]));
        // // arrPrintKuning($reqData);
        // /* --------------------------------------------------------------------------------------------------
        //  *peparasi data harus 3 step
        //  * #1 pengumpulan data transaksi (main)
        //  * --------------------------------------------------------------------------------------------------*/
        // $olahan = array();
        // foreach ($reqData as $masterDatum) {
        //     $spo_oleh_id = isset($masterDatum['582spo_oleh_id']) ? $masterDatum['582spo_oleh_id'] : "";
        //     $spo_oleh_nama = isset($masterDatum['582spo_oleh_nama']) ? $masterDatum['582spo_oleh_nama'] : "";
        //     $master_id = isset($masterDatum['582spo_master_id']) ? $masterDatum['582spo_master_id'] : "";
        //
        //     $olahan[$master_id] = $masterDatum;
        // }
        // /* --------------------------------------------------------------------------------------------------
        //  * #2 membuat tambahan kolom summary
        //  * --------------------------------------------------------------------------------------------------*/
        // foreach ($reqData as $reqDatum) {
        //
        //     $spo_oleh_id = isset($reqDatum['582spo_oleh_id']) ? $reqDatum['582spo_oleh_id'] : "";
        //     $spo_debet_lap = isset($reqDatum['582spo_debet_lap']) ? $reqDatum['582spo_debet_lap'] : "";
        //     $spo_kredit_lap = isset($reqDatum['582spo_kredit_lap']) ? $reqDatum['582spo_kredit_lap'] : "";
        //     $spo_master_id = isset($reqDatum['582spo_master_id']) ? $reqDatum['582spo_master_id'] : "";
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spo_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_debet'] += $spo_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spo_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_kredit'] += $spo_kredit_lap;
        //     // ------------------------------------------
        //     $spd_debet_lap = isset($reqDatum['582spd_debet_lap']) ? $reqDatum['582spd_debet_lap'] : 0;
        //     $spd_kredit_lap = isset($reqDatum['582spd_kredit_lap']) ? $reqDatum['582spd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_debet'] += $spd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_kredit'] += $spd_kredit_lap;
        //     // ------------------------------------------
        //     $pkd_debet_lap = isset($reqDatum['582pkd_debet_lap']) ? $reqDatum['582pkd_debet_lap'] : 0;
        //     $pkd_kredit_lap = isset($reqDatum['582pkd_kredit_lap']) ? $reqDatum['582pkd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_debet'] += $pkd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_kredit'] += $pkd_kredit_lap;
        //     // ------------------------------------------
        // }
        //
        // // arrPrintPink($req_datas);
        // // matiHere();
        //
        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        // $hasilOlahan_1 = array();
        // foreach ($olahan as $master_id => $itemParam) {
        //     // $customer_id = $itemParam['m_customerID'];
        //     $hasilOlahan_1[$master_id] = $itemParam + $req_datas[$master_id];
        //     // $hasilOlahan[$customer_id] = $itemParam;
        // }

        $masterData = $reqData_000;
        //
        // cekMerah(sizeof($gabunganData[0]['505'][0]));
        // arrPrintKuning($gabunganData[0]['505'][0]);
        // arrPrintKuning($gabunganData);
        // $masterData = $reqData;
        // matiHere(__LINE__);
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // cekHijau(sizeof($masterData));
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "seller_nama" => array(
                "label" => "salesman",
            ),
            "seller_id"   => array(
                "label" => "sID",
            ),
            // "582spo__step_1_nomer"          => array(
            //     "label"      => "spo",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582so__step_2_nomer"          => array(
            //     "label"      => "soa",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582pkd__step_3_nomer"          => array(
            //     "label"      => "pkd",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582spd__step_4_nomer"          => array(
            //     "label"      => "spd",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),

            "sum_spo_debet" => array(
                "label"   => "nilai order",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_debet"     => array(
                "label"   => "nilai dikirim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_kredit"    => array(
                "label"   => "nilai outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung",
            "title"       => "laporan salesman outstanding",
            "subTitle"    => "Raw Data CRM",
            "modul_path"  => $this->modul_path,
            "color_bar"   => "box-success",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "produkoutstandingsales",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    //--------------------------
    public function produkoutstandingcustomer()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            // $maxLimit = 50;
            // $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = "582spo";
        $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src_000 = $ps->callOutstanding("persediaan_produk");
        $reqData_000 = $src_000['raw'];
        // arrPrintKuning($reqData_000);
        // $arrMaster_id = array();
        foreach ($reqData_000 as $item) {
            $spo_debet_lap = $item['spo_debet_lap'];
            $debet_lap = $item['debet_lap'];
            $kredit_lap = $item['kredit_lap'];
            $subjek_id = $item['customer_id'];

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] += $spo_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_debet_lap'] += $debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_kredit_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_kredit_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_kredit_lap'] += $kredit_lap;

            $arrSubjek[$subjek_id] = $item;
        }

        // asort($arrMaster_id);
        // $minMaster_id = min($arrMaster_id);
        // $maxMaster_id = max($arrMaster_id);
        // // cekBiru($arrMaster_id);
        // // cekMerah());
        // // cekMerah();
        // // matiHere();
        // if (isset($_GET['date1']) && sizeof($arrMaster_id) > 0) {
        //     $condites = array(
        //         "master_id >=" => $minMaster_id,
        //         "master_id <=" => $maxMaster_id,
        //     );
        //     $this->db->where($condites);
        //
        //     $strDate .= formatField_he_format("fulldate", $get_date1);
        //     $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        // }
        //
        // $jenisTr = array(
        //     "582spo",
        //     "582so",
        //     "582pkd",
        //     "582spd",
        //     // "967"
        // );
        // $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // // $ps->setJenisTr("582");
        // $src = $ps->callCacheProduk("persediaan_produk");
        // $reqData = $src['data'];
        // // showLast_query("kuning");
        // // cekBiru(sizeof($reqData[0]));
        // // arrPrintKuning($reqData);
        // /* --------------------------------------------------------------------------------------------------
        //  *peparasi data harus 3 step
        //  * #1 pengumpulan data transaksi (main)
        //  * --------------------------------------------------------------------------------------------------*/
        // $olahan = array();
        // foreach ($reqData as $masterDatum) {
        //     $spo_oleh_id = isset($masterDatum['582spo_oleh_id']) ? $masterDatum['582spo_oleh_id'] : "";
        //     $spo_oleh_nama = isset($masterDatum['582spo_oleh_nama']) ? $masterDatum['582spo_oleh_nama'] : "";
        //     $master_id = isset($masterDatum['582spo_master_id']) ? $masterDatum['582spo_master_id'] : "";
        //
        //     $olahan[$master_id] = $masterDatum;
        // }
        // /* --------------------------------------------------------------------------------------------------
        //  * #2 membuat tambahan kolom summary
        //  * --------------------------------------------------------------------------------------------------*/
        // foreach ($reqData as $reqDatum) {
        //
        //     $spo_oleh_id = isset($reqDatum['582spo_oleh_id']) ? $reqDatum['582spo_oleh_id'] : "";
        //     $spo_debet_lap = isset($reqDatum['582spo_debet_lap']) ? $reqDatum['582spo_debet_lap'] : "";
        //     $spo_kredit_lap = isset($reqDatum['582spo_kredit_lap']) ? $reqDatum['582spo_kredit_lap'] : "";
        //     $spo_master_id = isset($reqDatum['582spo_master_id']) ? $reqDatum['582spo_master_id'] : "";
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spo_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_debet'] += $spo_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spo_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_kredit'] += $spo_kredit_lap;
        //     // ------------------------------------------
        //     $spd_debet_lap = isset($reqDatum['582spd_debet_lap']) ? $reqDatum['582spd_debet_lap'] : 0;
        //     $spd_kredit_lap = isset($reqDatum['582spd_kredit_lap']) ? $reqDatum['582spd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_debet'] += $spd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_kredit'] += $spd_kredit_lap;
        //     // ------------------------------------------
        //     $pkd_debet_lap = isset($reqDatum['582pkd_debet_lap']) ? $reqDatum['582pkd_debet_lap'] : 0;
        //     $pkd_kredit_lap = isset($reqDatum['582pkd_kredit_lap']) ? $reqDatum['582pkd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_debet'] += $pkd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_kredit'] += $pkd_kredit_lap;
        //     // ------------------------------------------
        // }
        //
        // // arrPrintPink($req_datas);
        // // matiHere();
        //

        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            // $customer_id = $itemParam['m_customerID'];
            $hasilOlahan_1[] = $itemParam + $arrSumSubjek[$subj_id];
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $hasilOlahan_1;
        //
        // cekMerah(sizeof($gabunganData[0]['505'][0]));
        // arrPrintKuning($gabunganData[0]['505'][0]);
        // arrPrintKuning($gabunganData);
        // $masterData = $reqData;
        // matiHere(__LINE__);
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // cekHijau(sizeof($masterData));
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "customer_id"   => array(
                "label" => "cuID",
            ),
            "customer_nama" => array(
                "label" => "customer",
            ),
            // "seller_nama"   => array(
            //     "label" => "salesman",
            // ),

            // "582spo__step_1_nomer"          => array(
            //     "label"      => "spo",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582so__step_2_nomer"          => array(
            //     "label"      => "soa",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582pkd__step_3_nomer"          => array(
            //     "label"      => "pkd",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582spd__step_4_nomer"          => array(
            //     "label"      => "spd",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),

            "sum_spo_debet_lap" => array(
                "label"   => "nilai order",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_debet_lap"     => array(
                "label"   => "nilai dikitrim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_kredit_lap"    => array(
                "label"   => "nilai outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
                "links"   => array(
                    "target"  => "laporan/Crm/produkoutstandingraw",
                    "title"   => "Transaksi per salesman",
                    "key"     => "customer_id",
                    "heading" => "customer_nama",
                ),
            ),
        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung",
            "title"       => "laporan customer outstanding",
            "subTitle"    => "Raw Data CRM",
            "modul_path"  => $this->modul_path,
            "color_bar"   => "box-danger",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "produkoutstandingcustomer",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function produkoutstandingcustomer_asli()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            // $maxLimit = 50;
            // $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = "582spo";
        $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src_000 = $ps->callOutstanding("persediaan_produk");
        $reqData_000 = $src_000['customer'];
        // arrPrintKuning($reqData_000);
        // $arrMaster_id = array();
        // foreach ($reqData_000 as $item) {
        //     $master_id_000 = $item['582spo_master_id'];
        //
        //     $arrMaster_id[] = $master_id_000;
        // }
        // asort($arrMaster_id);
        // $minMaster_id = min($arrMaster_id);
        // $maxMaster_id = max($arrMaster_id);
        // // cekBiru($arrMaster_id);
        // // cekMerah());
        // // cekMerah();
        // // matiHere();
        // if (isset($_GET['date1']) && sizeof($arrMaster_id) > 0) {
        //     $condites = array(
        //         "master_id >=" => $minMaster_id,
        //         "master_id <=" => $maxMaster_id,
        //     );
        //     $this->db->where($condites);
        //
        //     $strDate .= formatField_he_format("fulldate", $get_date1);
        //     $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        // }
        //
        // $jenisTr = array(
        //     "582spo",
        //     "582so",
        //     "582pkd",
        //     "582spd",
        //     // "967"
        // );
        // $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // // $ps->setJenisTr("582");
        // $src = $ps->callCacheProduk("persediaan_produk");
        // $reqData = $src['data'];
        // // showLast_query("kuning");
        // // cekBiru(sizeof($reqData[0]));
        // // arrPrintKuning($reqData);
        // /* --------------------------------------------------------------------------------------------------
        //  *peparasi data harus 3 step
        //  * #1 pengumpulan data transaksi (main)
        //  * --------------------------------------------------------------------------------------------------*/
        // $olahan = array();
        // foreach ($reqData as $masterDatum) {
        //     $spo_oleh_id = isset($masterDatum['582spo_oleh_id']) ? $masterDatum['582spo_oleh_id'] : "";
        //     $spo_oleh_nama = isset($masterDatum['582spo_oleh_nama']) ? $masterDatum['582spo_oleh_nama'] : "";
        //     $master_id = isset($masterDatum['582spo_master_id']) ? $masterDatum['582spo_master_id'] : "";
        //
        //     $olahan[$master_id] = $masterDatum;
        // }
        // /* --------------------------------------------------------------------------------------------------
        //  * #2 membuat tambahan kolom summary
        //  * --------------------------------------------------------------------------------------------------*/
        // foreach ($reqData as $reqDatum) {
        //
        //     $spo_oleh_id = isset($reqDatum['582spo_oleh_id']) ? $reqDatum['582spo_oleh_id'] : "";
        //     $spo_debet_lap = isset($reqDatum['582spo_debet_lap']) ? $reqDatum['582spo_debet_lap'] : "";
        //     $spo_kredit_lap = isset($reqDatum['582spo_kredit_lap']) ? $reqDatum['582spo_kredit_lap'] : "";
        //     $spo_master_id = isset($reqDatum['582spo_master_id']) ? $reqDatum['582spo_master_id'] : "";
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spo_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_debet'] += $spo_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spo_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_kredit'] += $spo_kredit_lap;
        //     // ------------------------------------------
        //     $spd_debet_lap = isset($reqDatum['582spd_debet_lap']) ? $reqDatum['582spd_debet_lap'] : 0;
        //     $spd_kredit_lap = isset($reqDatum['582spd_kredit_lap']) ? $reqDatum['582spd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_debet'] += $spd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_kredit'] += $spd_kredit_lap;
        //     // ------------------------------------------
        //     $pkd_debet_lap = isset($reqDatum['582pkd_debet_lap']) ? $reqDatum['582pkd_debet_lap'] : 0;
        //     $pkd_kredit_lap = isset($reqDatum['582pkd_kredit_lap']) ? $reqDatum['582pkd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_debet'] += $pkd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_kredit'] += $pkd_kredit_lap;
        //     // ------------------------------------------
        // }
        //
        // // arrPrintPink($req_datas);
        // // matiHere();
        //
        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        // $hasilOlahan_1 = array();
        // foreach ($olahan as $master_id => $itemParam) {
        //     // $customer_id = $itemParam['m_customerID'];
        //     $hasilOlahan_1[$master_id] = $itemParam + $req_datas[$master_id];
        //     // $hasilOlahan[$customer_id] = $itemParam;
        // }

        $masterData = $reqData_000;
        //
        // cekMerah(sizeof($gabunganData[0]['505'][0]));
        // arrPrintKuning($gabunganData[0]['505'][0]);
        // arrPrintKuning($gabunganData);
        // $masterData = $reqData;
        // matiHere(__LINE__);
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // cekHijau(sizeof($masterData));
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "customer_nama" => array(
                "label" => "customer",
            ),
            "seller_nama"   => array(
                "label" => "salesman",
            ),

            // "582spo__step_1_nomer"          => array(
            //     "label"      => "spo",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582so__step_2_nomer"          => array(
            //     "label"      => "soa",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582pkd__step_3_nomer"          => array(
            //     "label"      => "pkd",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582spd__step_4_nomer"          => array(
            //     "label"      => "spd",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),

            "sum_spo_debet" => array(
                "label"   => "nilai order",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_debet"     => array(
                "label"   => "nilai dikitrim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_kredit"    => array(
                "label"   => "nilai outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung",
            "title"       => "laporan customer outstanding",
            "subTitle"    => "Raw Data CRM",
            "modul_path"  => $this->modul_path,
            "color_bar"   => "box-danger",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "produkoutstandingcustomer",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    //--------------------------
    public function produkoutstandingcabang()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            // $maxLimit = 50;
            // $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = "582spo";
        $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src_000 = $ps->callOutstanding("persediaan_produk");
        $reqData_000 = $src_000['raw'];
        // arrPrintKuning($reqData_000);
        // $arrMaster_id = array();

        $arrCabang = array();
        foreach ($reqData_000 as $item) {
            $spo_debet_lap = $item['spo_debet_lap'];
            $debet_lap = $item['debet_lap'];
            $kredit_lap = $item['kredit_lap'];

            if (!isset($arrSumCabang[$item['cabang_id']]['sum_spo_debet_lap'])) {
                $arrSumCabang[$item['cabang_id']]['sum_spo_debet_lap'] = 0;
            }
            $arrSumCabang[$item['cabang_id']]['sum_spo_debet_lap'] += $spo_debet_lap;

            if (!isset($arrSumCabang[$item['cabang_id']]['sum_debet_lap'])) {
                $arrSumCabang[$item['cabang_id']]['sum_debet_lap'] = 0;
            }
            $arrSumCabang[$item['cabang_id']]['sum_debet_lap'] += $debet_lap;

            if (!isset($arrSumCabang[$item['cabang_id']]['sum_kredit_lap'])) {
                $arrSumCabang[$item['cabang_id']]['sum_kredit_lap'] = 0;
            }
            $arrSumCabang[$item['cabang_id']]['sum_kredit_lap'] += $kredit_lap;

            $arrCabang[$item['cabang_id']] = $item;
        }
        // arrPrintPink($arrSumCabang);
        // asort($arrMaster_id);
        // $minMaster_id = min($arrMaster_id);
        // $maxMaster_id = max($arrMaster_id);
        // // cekBiru($arrMaster_id);
        // // cekMerah());
        // // cekMerah();
        // // matiHere();

        // if (isset($_GET['date1']) && sizeof($arrMaster_id) > 0) {
        //     $condites = array(
        //         "master_id >=" => $minMaster_id,
        //         "master_id <=" => $maxMaster_id,
        //     );
        //     $this->db->where($condites);
        //
        //     $strDate .= formatField_he_format("fulldate", $get_date1);
        //     $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        // }
        //
        // $jenisTr = array(
        //     "582spo",
        //     "582so",
        //     "582pkd",
        //     "582spd",
        //     // "967"
        // );
        // $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // // $ps->setJenisTr("582");
        // $src = $ps->callCacheProduk("persediaan_produk");
        // $reqData = $src['data'];
        // // showLast_query("kuning");
        // // cekBiru(sizeof($reqData[0]));
        // // arrPrintKuning($reqData);
        // /* --------------------------------------------------------------------------------------------------
        //  *peparasi data harus 3 step
        //  * #1 pengumpulan data transaksi (main)
        //  * --------------------------------------------------------------------------------------------------*/
        // $olahan = array();
        // foreach ($reqData as $masterDatum) {
        //     $spo_oleh_id = isset($masterDatum['582spo_oleh_id']) ? $masterDatum['582spo_oleh_id'] : "";
        //     $spo_oleh_nama = isset($masterDatum['582spo_oleh_nama']) ? $masterDatum['582spo_oleh_nama'] : "";
        //     $master_id = isset($masterDatum['582spo_master_id']) ? $masterDatum['582spo_master_id'] : "";
        //
        //     $olahan[$master_id] = $masterDatum;
        // }
        // /* --------------------------------------------------------------------------------------------------
        //  * #2 membuat tambahan kolom summary
        //  * --------------------------------------------------------------------------------------------------*/
        // foreach ($reqData as $reqDatum) {
        //
        //     $spo_oleh_id = isset($reqDatum['582spo_oleh_id']) ? $reqDatum['582spo_oleh_id'] : "";
        //     $spo_debet_lap = isset($reqDatum['582spo_debet_lap']) ? $reqDatum['582spo_debet_lap'] : "";
        //     $spo_kredit_lap = isset($reqDatum['582spo_kredit_lap']) ? $reqDatum['582spo_kredit_lap'] : "";
        //     $spo_master_id = isset($reqDatum['582spo_master_id']) ? $reqDatum['582spo_master_id'] : "";
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spo_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_debet'] += $spo_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spo_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_kredit'] += $spo_kredit_lap;
        //     // ------------------------------------------
        //     $spd_debet_lap = isset($reqDatum['582spd_debet_lap']) ? $reqDatum['582spd_debet_lap'] : 0;
        //     $spd_kredit_lap = isset($reqDatum['582spd_kredit_lap']) ? $reqDatum['582spd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_debet'] += $spd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_kredit'] += $spd_kredit_lap;
        //     // ------------------------------------------
        //     $pkd_debet_lap = isset($reqDatum['582pkd_debet_lap']) ? $reqDatum['582pkd_debet_lap'] : 0;
        //     $pkd_kredit_lap = isset($reqDatum['582pkd_kredit_lap']) ? $reqDatum['582pkd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_debet'] += $pkd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_kredit'] += $pkd_kredit_lap;
        //     // ------------------------------------------
        // }
        //
        // // arrPrintPink($req_datas);
        // // matiHere();
        //

        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrCabang as $cabang_id => $itemParam) {
            // $customer_id = $itemParam['m_customerID'];
            $hasilOlahan_1[] = $itemParam + $arrSumCabang[$cabang_id];
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $hasilOlahan_1;
        //
        // cekMerah(sizeof($gabunganData[0]['505'][0]));
        // arrPrintKuning($gabunganData[0]['505'][0]);
        // arrPrintKuning($gabunganData);
        // $masterData = $reqData;
        // matiHere(__LINE__);
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // cekHijau(sizeof($masterData));
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "cabang_id"   => array(
                "label" => "cID",
            ),
            "cabang_nama" => array(
                "label" => "kantor cabang",
            ),
            // "seller_nama"   => array(
            //     "label" => "salesman",
            // ),

            // "582so__step_2_nomer"          => array(
            //     "label"      => "soa",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582pkd__step_3_nomer"          => array(
            //     "label"      => "pkd",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582spd__step_4_nomer"          => array(
            //     "label"      => "spd",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),

            "sum_spo_debet_lap" => array(
                "label"   => "nilai order",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_debet_lap"     => array(
                "label"   => "nilai dikitrim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_kredit_lap"    => array(
                "label"   => "nilai outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
                "links"   => array(
                    "target"  => "laporan/Crm/produkoutstandingraw",
                    "title"   => "Transaksi per salesman",
                    "key"     => "cabang_id",
                    "heading" => "cabang_nama",
                ),
            ),
        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung",
            "title"       => "laporan cabang outstanding",
            "subTitle"    => "Raw Data CRM",
            "modul_path"  => $this->modul_path,
            "color_bar"   => "box-aqua",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "produkoutstandingcabang",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function produkoutstandingcabang_asli()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        /* -----------------------------------------------------------------------------------------------------------
         * data request/permintaa ke vendor
         * -----------------------------------------------------------------------------------------------------------*/
        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            // $maxLimit = 50;
            // $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = "582spo";
        $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src_000 = $ps->callOutstanding("persediaan_produk");
        $reqData_000 = $src_000['cabang'];
        // arrPrintKuning($reqData_000);
        // $arrMaster_id = array();
        // foreach ($reqData_000 as $item) {
        //     $master_id_000 = $item['582spo_master_id'];
        //
        //     $arrMaster_id[] = $master_id_000;
        // }
        // asort($arrMaster_id);
        // $minMaster_id = min($arrMaster_id);
        // $maxMaster_id = max($arrMaster_id);
        // // cekBiru($arrMaster_id);
        // // cekMerah());
        // // cekMerah();
        // // matiHere();
        // if (isset($_GET['date1']) && sizeof($arrMaster_id) > 0) {
        //     $condites = array(
        //         "master_id >=" => $minMaster_id,
        //         "master_id <=" => $maxMaster_id,
        //     );
        //     $this->db->where($condites);
        //
        //     $strDate .= formatField_he_format("fulldate", $get_date1);
        //     $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        // }
        //
        // $jenisTr = array(
        //     "582spo",
        //     "582so",
        //     "582pkd",
        //     "582spd",
        //     // "967"
        // );
        // $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // // $ps->setJenisTr("582");
        // $src = $ps->callCacheProduk("persediaan_produk");
        // $reqData = $src['data'];
        // // showLast_query("kuning");
        // // cekBiru(sizeof($reqData[0]));
        // // arrPrintKuning($reqData);
        // /* --------------------------------------------------------------------------------------------------
        //  *peparasi data harus 3 step
        //  * #1 pengumpulan data transaksi (main)
        //  * --------------------------------------------------------------------------------------------------*/
        // $olahan = array();
        // foreach ($reqData as $masterDatum) {
        //     $spo_oleh_id = isset($masterDatum['582spo_oleh_id']) ? $masterDatum['582spo_oleh_id'] : "";
        //     $spo_oleh_nama = isset($masterDatum['582spo_oleh_nama']) ? $masterDatum['582spo_oleh_nama'] : "";
        //     $master_id = isset($masterDatum['582spo_master_id']) ? $masterDatum['582spo_master_id'] : "";
        //
        //     $olahan[$master_id] = $masterDatum;
        // }
        // /* --------------------------------------------------------------------------------------------------
        //  * #2 membuat tambahan kolom summary
        //  * --------------------------------------------------------------------------------------------------*/
        // foreach ($reqData as $reqDatum) {
        //
        //     $spo_oleh_id = isset($reqDatum['582spo_oleh_id']) ? $reqDatum['582spo_oleh_id'] : "";
        //     $spo_debet_lap = isset($reqDatum['582spo_debet_lap']) ? $reqDatum['582spo_debet_lap'] : "";
        //     $spo_kredit_lap = isset($reqDatum['582spo_kredit_lap']) ? $reqDatum['582spo_kredit_lap'] : "";
        //     $spo_master_id = isset($reqDatum['582spo_master_id']) ? $reqDatum['582spo_master_id'] : "";
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spo_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_debet'] += $spo_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spo_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spo_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spo_kredit'] += $spo_kredit_lap;
        //     // ------------------------------------------
        //     $spd_debet_lap = isset($reqDatum['582spd_debet_lap']) ? $reqDatum['582spd_debet_lap'] : 0;
        //     $spd_kredit_lap = isset($reqDatum['582spd_kredit_lap']) ? $reqDatum['582spd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_spd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_debet'] += $spd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_spd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_spd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_spd_kredit'] += $spd_kredit_lap;
        //     // ------------------------------------------
        //     $pkd_debet_lap = isset($reqDatum['582pkd_debet_lap']) ? $reqDatum['582pkd_debet_lap'] : 0;
        //     $pkd_kredit_lap = isset($reqDatum['582pkd_kredit_lap']) ? $reqDatum['582pkd_kredit_lap'] : 0;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_debet'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_debet'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_debet'] += $pkd_debet_lap;
        //
        //     if (!isset($req_datas[$spo_master_id]['sum_pkd_kredit'])) {
        //         $req_datas[$spo_master_id]['sum_pkd_kredit'] = 0;
        //     }
        //     $req_datas[$spo_master_id]['sum_pkd_kredit'] += $pkd_kredit_lap;
        //     // ------------------------------------------
        // }
        //
        // // arrPrintPink($req_datas);
        // // matiHere();
        //
        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        // $hasilOlahan_1 = array();
        // foreach ($olahan as $master_id => $itemParam) {
        //     // $customer_id = $itemParam['m_customerID'];
        //     $hasilOlahan_1[$master_id] = $itemParam + $req_datas[$master_id];
        //     // $hasilOlahan[$customer_id] = $itemParam;
        // }

        $masterData = $reqData_000;
        //
        // cekMerah(sizeof($gabunganData[0]['505'][0]));
        // arrPrintKuning($gabunganData[0]['505'][0]);
        // arrPrintKuning($gabunganData);
        // $masterData = $reqData;
        // matiHere(__LINE__);
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // cekHijau(sizeof($masterData));
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "cabang_id"   => array(
                "label" => "cID",
            ),
            "cabang_nama" => array(
                "label" => "kantor cabang",
            ),
            // "seller_nama"   => array(
            //     "label" => "salesman",
            // ),

            // "582so__step_2_nomer"          => array(
            //     "label"      => "soa",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582pkd__step_3_nomer"          => array(
            //     "label"      => "pkd",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "582spd__step_4_nomer"          => array(
            //     "label"      => "spd",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            // ),
            // "nama"                  => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),

            "sum_spo_debet" => array(
                "label"   => "nilai order",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_debet"     => array(
                "label"   => "nilai dikitrim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_kredit"    => array(
                "label"   => "nilai outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung",
            "title"       => "laporan cabang outstanding",
            "subTitle"    => "Raw Data CRM",
            "modul_path"  => $this->modul_path,
            "color_bar"   => "box-danger",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "produkoutstandingcabang",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }


    // -----------------------------------------------------------------------
    // perender per-peran detile dari loader bisa dipakai umum
    public function perindekshow()
    {
        $this->load->helper("he_mass_table");
        // $this->load->model("Coms/ComRekeningTransaksiPembantu");
        // $ps = new ComRekeningTransaksiPembantu();
        $ygditampilkan = url_segment(4);

        $day_now = dtimeNow('d');
        $date_now = dtimeNow('Y-m-d');
        $month_now = dtimeNow('Y-m');
        if (isset($_GET['date1'])) {
            $date_now = $_GET['date1'];
        }
        // $datemin = $kemarin = date('Y-m-d', strtotime("-30 day", strtotime($date_now)));
        $datemin = $kemarin = date('Y-m-d', strtotime("-30 day", strtotime($date_now)));
        $dateplus = $kemarin = date('Y-m-d', strtotime("1 day", strtotime($date_now)));

        $date_start = dtimeNow('Y-m-01');
        // if ($day_now < 14) {
        //     $date_start = $datemin;
        // }
        // cekBiru($datemin . " $date_start");

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : $_GET['date1'] = $date_start;
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : $_GET['date2'] = dtimeNow('Y-m-d');
        // cekBiru("$date1 $date2");

        /* -----------------------------------------------------------------------------------------------
         * navigasi bulan sebelumnya
         * -----------------------------------------------------------------------------------------------*/
        //<editor-fold desc="bulan sebelumnya">
        // $date_start_sebelumnya = dtimeNow('Y-m-01');
        $date_start_sebelumnya = previousMonth($date2) . "-01";
        $date_stop_sebelumnya = date("Y-m-t", strtotime($date_start_sebelumnya));
        $date_sebelumnya = array(
            "date1" => $date_start_sebelumnya,
            "date2" => $date_stop_sebelumnya,
        );
        $nama_bulan_sebelum = date("F Y", strtotime($date_start_sebelumnya));
        $str_geters = "";
        foreach (($date_sebelumnya + $_GET) as $item_key => $item_val) {
            $str_get = "$item_key=$item_val";

            if ($str_geters == "") {
                $str_geters .= "?$str_get";
            }
            else {
                $str_geters = "$str_geters" . "&$str_get";
            }
        }

        $ulr_bulan_sebelum = current_url() . $str_geters;
        $ulr_bulan_ini = current_url();
        //</editor-fold>

        /* -----------------------------------------------------------------------------------------------
         * navigasi bulan setelahnya
         * -----------------------------------------------------------------------------------------------*/
        //<editor-fold desc="bulan setelahnya">
        $date_start_setelahnya = aftersMonth($date2) . "-01";
        $date_stop_setelahnya = date("Y-m-t", strtotime($date_start_setelahnya));
        $date_setelahnya = array(
            "date1" => $date_start_setelahnya,
            "date2" => $date_stop_setelahnya,
        );
        $nama_bulan_setelah = date("F Y", strtotime($date_start_setelahnya));

        $str_geters = "";
        foreach (($date_setelahnya + $_GET) as $item_key => $item_val) {
            $str_get = "$item_key=$item_val";

            if ($str_geters == "") {
                $str_geters .= "?$str_get";
            }
            else {
                $str_geters = "$str_geters" . "&$str_get";
            }
        }

        $ulr_bulan_setelah = current_url() . $str_geters;
        $nama_bulan_ygtampil = formatTanggal($date2, "F Y");
        //</editor-fold>

        /* -----------------------------------------------------------------------------------------------
         * logik untuk mematikan butun bulan belum sukses jadi masih dimatikan
         * -----------------------------------------------------------------------------------------------*/
        // cekHijau("$date_now **$date2**  $date_start //// $date_start_setelahnya *** " . dtimeToSecond($date2));
        $btn_disabled = "";
        if (dtimeToSecond($date2) <= dtimeToSecond($date_start_setelahnya)) {
            // cekBiru(dtimeToSecond($date2) ."<br>" . dtimeToSecond($date_start_setelahnya));
            // $btn_disabled = "disabled";
        }

        /* -----------------------------------------------------------------------------------------------
         * button navigasi bulanan
         * -----------------------------------------------------------------------------------------------*/
        // cekMerah("$date_start_sebelumnya ****** $date_stop_sebelumnya ******* " . date('Y-m-t', strtotime('2022-02-01')));
        // cekHijau($ulr_bulan_sebelum . " **** $str_get **** $str_geters");
        $btn_td = "<button type='button' class='btn btn-danger' onclick=\"location.href='$ulr_bulan_sebelum'\"><i class='fa fa-chevron-circle-left'></i> $nama_bulan_sebelum </button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' disabled>$nama_bulan_ygtampil <i class='fa fa-chevron-circle-down'></i></button>";
        $btn_td .= "<button type='button' class='btn btn-danger' $btn_disabled onclick=\"location . href = '$ulr_bulan_ini'\">bulan ini</button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' $btn_disabled  onclick=\"location . href = '$ulr_bulan_setelah'\">$nama_bulan_setelah <i class='fa fa-chevron-circle-right '></i></button>";
        $add_td = "<td>$btn_td</td>";
        // -----------------------------------------------------------------------------------------------

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        $strDate = "";
        if (isset($_GET['date1'])) {

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        $masterData = array();
        $arrHeaders = array();
        $data = array(
            "mode"        => "indek",
            "title"       => "Outstanding Penjualan",
            "subTitle"    => "Raw Data Sales Order",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "permaster",
            "add_td"      => $add_td,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => "2022-01-01",
            "date_max"    => dtimeNow('Y-m-d'),
            // "sum_null"    => base_url() . "laporan/Penjualan/produksopersales" . "$strGet",
            "sum_null"    => base_url() . "laporan/Crm/$ygditampilkan" . "$strGet",
            // "sum_satu"    => base_url() . "laporan/Penjualan/produksosales" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Penjualan/produksotransaksi" . "$strGet",
            // "sum_tiga"    => base_url() . "laporan/Penjualan/produksoproduk" . "$strGet",
            // "sum_empat"   => base_url() . "laporan/Penjualan/produksocustomer" . "$strGet",
            // "sum_lima"    => base_url() . "laporan/Crm/produkoutstandingcabang" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }
    // -----------------------------------------------------------------------
}