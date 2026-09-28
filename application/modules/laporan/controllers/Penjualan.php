<?php

class Penjualan extends MX_Controller
{
    public function __construct()
    {
        $this->modul_path = base_url() . "penjualan/";
        $this->default_limit = 200;
        // $this->jenisTr = "582";
        $this->jenisTr_penjualan = "582spd";
        $this->jenisTrs = array("582spd", "982", "382spd");
    }

    public function preso()
    {
        /* ----------------------------------------------------------------------------------------------------------
         *
         *
         * ----------------------------------------------------------------------------------------------------------*/
        $jenisTr = $this->jenisTr = "582so";
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        $this->load->library("Bigdata");
        $ps = new Bigdata();

        $strDate = "";
        $ps->setJenisTr("582spo");
        /* ----------------------------------------------------------------------------------------------------------
         * jika tidak ambil range tanggal hanya ditampilkan data ygterlimit
         * ----------------------------------------------------------------------------------------------------------*/
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $ps->setCondites($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $ps->setLimit($this->default_limit);
        }

        /* ----------------------------------------------------------------------------------------------------------
         * sorting data transaksi
         * ----------------------------------------------------------------------------------------------------------*/
        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $ps->setSortBy($sortings);

        $src = $ps->callBdProdukNonAkunting();
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
            // "kode"           => array(
            //     "label" => "kode",
            // ),
            "nama"           => array(
                "label" => "produk",
            ),
            "no_part"        => array(
                "label" => "no part",
            ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),
            "cabang_nama"    => array(
                "label" => "branch",
                // "format" => "formatField_he_format",
            ),
            "nomer"          => array(
                "label"  => "nomer",
                "format" => "formatField_he_format",
            ),
            "seller_nama"    => array(
                "label" => "salesman",
            ),
            "customers_nama" => array(
                "label" => "customer",
            ),
            // --
            "harga"          => array(
                "label"  => "bruto",
                "format" => "formatField_he_format",
            ),
            "disc"           => array(
                "label"  => "diskon",
                "format" => "formatField_he_format",
            ),
            "nett1"          => array(
                "label"  => "netto",
                "format" => "formatField_he_format",
            ),
            "jml"            => array(
                "label"  => "jumlah",
                "format" => "formatField_he_format",
            ),
            "sub_nett1"      => array(
                "label"  => "nilai",
                "format" => "formatField_he_format",
            ),
            // "subtotal"       => array(
            //     "label"  => "nilai",
            //     "format" => "formatField_he_format",
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
            "title"       => "laporan " . callMenuLabel_he_menu() . " $strDate",
            "subTitle"    => "Raw " . callMenuLabel_he_menu(),
            "modul_path"  => $this->modul_path,
            "jenistr"     => $this->jenisTr,
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow("Y-m-d"),
            "sum_satu"    => base_url() . "laporan/Penjualan/presopersales" . "$strGet",
            "sum_dua"     => base_url() . "laporan/Penjualan/presopercabang" . "$strGet",
            "sum_tiga"    => base_url() . "laporan/Penjualan/presopercustomer" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function presopersales()
    {
        /* ----------------------------------------------------------------------------------------------------------
         *
         *
         * ----------------------------------------------------------------------------------------------------------*/
        $jenisTr = $this->jenisTr = "582";
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        $this->load->library("Bigdata");
        $ps = new Bigdata();

        $strDate = "";
        $ps->setJenisTr("582spo");
        /* ----------------------------------------------------------------------------------------------------------
         * jika tidak ambil range tanggal hanya ditampilkan data ygterlimit
         * ----------------------------------------------------------------------------------------------------------*/
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(transaksi.dtime)>=" => $get_date1,
                "date(transaksi.dtime)<=" => $get_date2,
                // "trash_4" => 1,
            );
            $ps->setCondites($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $ps->setLimit($this->default_limit);
        }

        /* ----------------------------------------------------------------------------------------------------------
         * sorting data transaksi
         * ----------------------------------------------------------------------------------------------------------*/
        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $ps->setSortBy($sortings);


        $src = $ps->callBdProdukNonAkunting();
        $masterData = $src['data'];
        // showLast_query("kuning");
        // cekBiru(sizeof($masterData));
        // arrPrintHijau($masterData);
        foreach ($masterData as $masterDatum) {
            $sellerID = $masterDatum['oleh_id'];
            $cabangID = $masterDatum['cabang_id'];
            // $sellerID = $masterDatum['seller_id'];
            // $sub_nett1 = $masterDatum['nett1'];;
            $sub_nett1 = $masterDatum['sub_nett1'];

            $olahan[$sellerID] = $masterDatum;

            if (!isset($olahan0[$sellerID]['sumPso'])) {
                $olahan0[$sellerID]['sumPso'] = 0;
            }
            $olahan0[$sellerID]['sumPso'] += $sub_nett1;


            // cekMerah($sellerID);
            if (!isset($olahan2[$sellerID]['sumCancel'])) {
                $olahan2[$sellerID]['sumCancel'] = 0;
            }
            if ($masterDatum['trash_4'] == 1) {
                $olahan2[$sellerID]['sumCancel'] += $sub_nett1;
            }
            // $olahan[$sellerID] = $datas;
        }

        foreach ($olahan as $seller_id => $itemParam) {
            $total = $olahan0[$seller_id]['sumPso'];
            $total2 = $olahan2[$seller_id]['sumCancel'];

            $subtotal['subTotal'] = $total - $total2;
            $hasilOlahan[$seller_id] = $itemParam + $olahan0[$seller_id] + $olahan2[$seller_id] + $subtotal;
        }
        // cekBiru($olahan2['69']['sumCancel']);
        $masterData = $hasilOlahan;
        // matiHere();
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "olehName"  => array(
                "label"  => "salesman",
                "format" => "formatField_he_format",
            ),
            "sumPso"    => array(
                "label"   => "pre SO bruto",
                "format"  => "formatField_he_format",
                "summary" => true,
                "attr"    => "class='text-right'",
            ),
            "sumCancel" => array(
                "label"   => "pre SO cancel",
                "format"  => "formatField_he_format",
                "summary" => true,
                "attr"    => "class='text-right'",
            ),
            "subTotal"  => array(
                "label"   => "pre SO netto",
                "format"  => "formatField_he_format",
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
            "title"       => "Laporan By Salesman" . " $strDate",
            "subTitle"    => "all",
            "modul_path"  => $this->modul_path,
            "jenistr"     => $this->jenisTr,
            "data_id"     => "presopersales",
            "color_bar"   => "box-warning",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow("Y-m-d"),
        );
        $this->load->view("laporan", $data);
    }

    public function presopercabang()
    {
        /* ----------------------------------------------------------------------------------------------------------
         *
         *
         * ----------------------------------------------------------------------------------------------------------*/
        $jenisTr = $this->jenisTr = "582";
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        $this->load->library("Bigdata");
        $ps = new Bigdata();

        $strDate = "";
        $ps->setJenisTr("582spo");
        /* ----------------------------------------------------------------------------------------------------------
         * jika tidak ambil range tanggal hanya ditampilkan data ygterlimit
         * ----------------------------------------------------------------------------------------------------------*/
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(transaksi.dtime)>=" => $get_date1,
                "date(transaksi.dtime)<=" => $get_date2,
                // "trash_4" => 1,

            );
            $ps->setCondites($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $ps->setLimit($this->default_limit);
        }

        /* ----------------------------------------------------------------------------------------------------------
         * sorting data transaksi
         * ----------------------------------------------------------------------------------------------------------*/
        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $ps->setSortBy($sortings);


        $src = $ps->callBdProdukNonAkunting();
        $masterData = $src['data'];
        // showLast_query("kuning");
        // cekBiru(sizeof($masterData));
        // arrPrintHijau($masterData);
        foreach ($masterData as $masterDatum) {
            $sellerID = $masterDatum['oleh_id'];
            $cabangID = $masterDatum['cabang_id'];
            // $sub_nett1 = $masterDatum['nett1'];;
            $sub_nett1 = $masterDatum['sub_nett1'];

            $olahan[$cabangID] = $masterDatum;

            if (!isset($olahan0[$cabangID]['sumPso'])) {
                $olahan0[$cabangID]['sumPso'] = 0;
            }
            $olahan0[$cabangID]['sumPso'] += $sub_nett1;


            // cekMerah($cabangID);
            if (!isset($olahan2[$cabangID]['sumCancel'])) {
                $olahan2[$cabangID]['sumCancel'] = 0;
            }
            if ($masterDatum['trash_4'] == 1) {
                $olahan2[$cabangID]['sumCancel'] += $sub_nett1;
            }
            // $olahan[$cabangID] = $datas;
        }

        foreach ($olahan as $cabang_id => $itemParam) {
            $total = $olahan0[$cabang_id]['sumPso'];
            $total2 = $olahan2[$cabang_id]['sumCancel'];

            $subtotal['subTotal'] = $total - $total2;
            $hasilOlahan[$cabang_id] = $itemParam + $olahan0[$cabang_id] + $olahan2[$cabang_id] + $subtotal;
        }

        $masterData = $hasilOlahan;
        // matiHere();
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "cabangName" => array(
                "label"  => "Branch",
                "format" => "formatField_he_format",
            ),
            "sumPso"     => array(
                "label"   => "pre SO bruto",
                "format"  => "formatField_he_format",
                "summary" => true,
                "attr"    => "class='text-right'",
            ),
            "sumCancel"  => array(
                "label"   => "pre SO cancel",
                "format"  => "formatField_he_format",
                "summary" => true,
                "attr"    => "class='text-right'",
            ),
            "subTotal"   => array(
                "label"   => "pre SO netto",
                "format"  => "formatField_he_format",
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
            "title"       => "Laporan By Branch" . " $strDate",
            "subTitle"    => "all",
            "modul_path"  => $this->modul_path,
            "jenistr"     => $this->jenisTr,
            "data_id"     => "presopercabang",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow("Y-m-d"),
        );
        $this->load->view("laporan", $data);
    }

    public function presopercustomer()
    {
        /* ----------------------------------------------------------------------------------------------------------
         *
         *
         * ----------------------------------------------------------------------------------------------------------*/
        $jenisTr = $this->jenisTr = "582";
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        $this->load->library("Bigdata");
        $ps = new Bigdata();

        $strDate = "";
        $ps->setJenisTr("582spo");
        /* ----------------------------------------------------------------------------------------------------------
         * jika tidak ambil range tanggal hanya ditampilkan data ygterlimit
         * ----------------------------------------------------------------------------------------------------------*/
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(transaksi.dtime)>=" => $get_date1,
                "date(transaksi.dtime)<=" => $get_date2,
                // "trash_4" => 1,

            );
            $ps->setCondites($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $ps->setLimit($this->default_limit);
        }

        /* ----------------------------------------------------------------------------------------------------------
         * sorting data transaksi
         * ----------------------------------------------------------------------------------------------------------*/
        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $ps->setSortBy($sortings);


        $src = $ps->callBdProdukNonAkunting();
        $masterData = $src['data'];
        // showLast_query("kuning");
        // cekBiru(sizeof($masterData));
        // arrPrintHijau($masterData);
        foreach ($masterData as $masterDatum) {
            $sellerID = $masterDatum['oleh_id'];
            $cabangID = $masterDatum['cabang_id'];
            $customerID = $masterDatum['customerID'];;
            $sub_nett1 = $masterDatum['sub_nett1'];

            $olahan[$customerID] = $masterDatum;

            if (!isset($olahan0[$customerID]['sumPso'])) {
                $olahan0[$customerID]['sumPso'] = 0;
            }
            $olahan0[$customerID]['sumPso'] += $sub_nett1;


            // cekMerah($customerID);
            if (!isset($olahan2[$customerID]['sumCancel'])) {
                $olahan2[$customerID]['sumCancel'] = 0;
            }
            if ($masterDatum['trash_4'] == 1) {
                $olahan2[$customerID]['sumCancel'] += $sub_nett1;
            }
            // $olahan[$customerID] = $datas;
        }

        foreach ($olahan as $customer_id => $itemParam) {
            $total = $olahan0[$customer_id]['sumPso'];
            $total2 = $olahan2[$customer_id]['sumCancel'];

            $subtotal['subTotal'] = $total - $total2;
            $hasilOlahan[$customer_id] = $itemParam + $olahan0[$customer_id] + $olahan2[$customer_id] + $subtotal;
        }

        $masterData = $hasilOlahan;
        // matiHere();
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "customerName" => array(
                "label"  => "Customer",
                "format" => "formatField_he_format",
            ),
            "sumPso"       => array(
                "label"   => "pre SO bruto",
                "format"  => "formatField_he_format",
                "summary" => true,
                "attr"    => "class='text-right'",
            ),
            "sumCancel"    => array(
                "label"   => "pre SO cancel",
                "format"  => "formatField_he_format",
                "summary" => true,
                "attr"    => "class='text-right'",
            ),
            "subTotal"     => array(
                "label"   => "pre SO netto",
                "format"  => "formatField_he_format",
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
            "title"       => "Laporan By Customer" . " $strDate",
            "subTitle"    => "all",
            "modul_path"  => $this->modul_path,
            "jenistr"     => $jenisTr,
            "data_id"     => "presopercustomer",
            "color_bar"   => "box-success",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow("Y-m-d"),
        );
        $this->load->view("laporan", $data);
    }

    // -------------------------------
    public function so()
    {
        /* ----------------------------------------------------------------------------------------------------------
         *
         *
         * ----------------------------------------------------------------------------------------------------------*/
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        $this->load->library("Bigdata");
        $ps = new Bigdata();
        $macDataLimit = $this->default_limit;
        $strDate = "";
        $ps->setJenisTr("582so");
        /* ----------------------------------------------------------------------------------------------------------
         * jika tidak ambil range tanggal hanya ditampilkan data ygterlimit $macDataLimit
         * ----------------------------------------------------------------------------------------------------------*/
        //<editor-fold desc="jika hanya punya special dilimit">
        // $myMembers = my_memberships();
        // $limitedMember = array(
        //     "c_special",
        // );
        // $allowedMember = array(
        //     "c_owner",
        //     "c_holding",
        // );
        // $limited = false;
        // $special_condites = array();
        // foreach ($myMembers as $myMember) {
        //     if(in_array($myMember, $limitedMember)){
        //         $special_condites = array(
        //             "oleh_id" => my_id(),
        //         );
        //         $limited = true;
        //         break;
        //     }
        //     if(in_array($myMember, $allowedMember)){
        //         // $special_condites = array(
        //         //     "oleh_id" => my_id(),
        //         // );
        //         $limited = false;
        //         break;
        //     }
        // }
        //</editor-fold>

        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $ps->setCondites($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {

            // if($limited == true){
            //     $condites = array(
            //         "oleh_id" => my_id(),
            //     );
            //     $ps->setCondites($condites);
            // }

            $ps->setLimit($macDataLimit);
        }

        /* ----------------------------------------------------------------------------------------------------------
         * sorting data transaksi
         * ----------------------------------------------------------------------------------------------------------*/
        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $ps->setSortBy($sortings);

        $src = $ps->callBdProdukNonAkunting();
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
            // "kode"           => array(
            //     "label" => "kode",
            // ),
            "nama"           => array(
                "label" => "produk",
            ),
            "no_part"        => array(
                "label" => "no part",
            ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),
            "cabang_nama"    => array(
                "label" => "branch",
                // "format" => "formatField_he_format",
            ),
            "nomer_top"      => array(
                "label"  => "nomer pre so",
                "format" => "formatField_he_format",
            ),
            "nomer"          => array(
                "label"  => "nomer",
                "format" => "formatField_he_format",
            ),
            "oleh_nama"      => array(
                "label" => "otorisator",
            ),
            "seller_nama"    => array(
                "label" => "salesman",
            ),
            "customers_nama" => array(
                "label" => "customer",
            ),
            // --
            "harga"          => array(
                "label"  => "bruto",
                "format" => "formatField_he_format",
            ),
            "disc"           => array(
                "label"  => "diskon",
                "format" => "formatField_he_format",
            ),
            "nett1"          => array(
                "label"  => "netto",
                "format" => "formatField_he_format",
            ),
            "jml"            => array(
                "label"  => "jumlah",
                "format" => "formatField_he_format",
            ),
            "sub_nett1"      => array(
                "label"      => "nilai",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            // "subtotal"       => array(
            //     "label"  => "nilai",
            //     "format" => "formatField_he_format",
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
            "mode"        => "langsung_indek",
            "title"       => "laporan " . callMenuLabel_he_menu() . " $strDate",
            "subTitle"    => "Raw " . callMenuLabel_he_menu(),
            "modul_path"  => $this->modul_path,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow("Y-m-d"),
            // "presopersales"    => base_url() . "laporan/Penjualan/presopersales" . "$strGet",
            // "presopercabang"   => base_url() . "laporan/Penjualan/presopercabang" . "$strGet",
            // "presopercustomer" => base_url() . "laporan/Penjualan/presopercustomer" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function so2()
    {
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $this->load->library("laporan/DataOutstanding");
        $dt = new DataOutstanding();
        /* ---------------------------------------------------------------------
        * filter khusus
        * ---------------------------------------------------------------------*/
        // arrPrint($_GET);
        if (isset($_GET['ky'])) {
            $get_condites = array(
                $_GET['ky'] => $_GET[$_GET['ky']]
            );
            if (method_exists($dt, "getSellerId")) {

                // $dt->setSellerId('808');
            }
        }
        // cekHere(ipadd());
        if (ipadd() == "202.65.117.72") {
            // $dt->setSellerId('65');
            // $dt->setSellerId('73');
            // ------------------
            // $dt->setSellerId('61');
            // $dt->setSellerId('69');
        }

        $src_00 = $dt->getSaldoSellerTransaksi($date1, $date2);
        $src_mtd = $src_00['mtd'];
        $src_yang_lalu = $src_00['ytd_previous'];
        $src_ytd = $src_00['ytd'];
        $src_ytd_pluss = $src_00['ytd_pluss'];
        $arrRekenings = $src_00['rekening'];
        $arrTransaksiTipes = $src_00['transaksi_tipe'];
        $src_tr = $src_00['transaksi'];
        $src_koloms = $src_00['kolom'];
        // arrPrintPink($src_mtd);

        $tr_datas = array();
        foreach ($src_tr as $item) {
            $tr_data = addPrefixKeyT_he_format((array)$item);
            $tr_datas[$item->id] = $tr_data;
        }

        // arrPrintKuning($src_tr);
        $src_mtd_pluss = array();
        foreach ($src_mtd as $item) {
            $master_id = $item['master_id'];
            // cekPink($master_id);
            // arrPrintPink($src_tr[$master_id]);
            $item_trs = isset($src_tr[$master_id]) ? $src_tr[$master_id] : array("kosong" => "no");
            $src_mtd_pluss[] = $item + $item_trs;
            // if($master_id == "164329"){
            //     break;
            // }
        }

        // $src_00 = $dt->callPerTransaksi($date1, $date2);
        $masterData = $src_mtd_pluss;
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "master_id"        => array(
                "label" => "mid",
                // "format" => "formatField_he_format",
            ),
            "dtime"            => array(
                "label"  => "tanggal",
                "format" => "formatField_he_format",
            ),
            // "kode"           => array(
            //     "label" => "kode",
            // ),
            // "nama"           => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),
            "t_cabang_nama"    => array(
                "label" => "branch",
                // "format" => "formatField_he_format",
            ),
            "extern_nama"      => array(
                "label"  => "nomer pre so",
                "format" => "formatField_he_format",
            ),
            "t_nomer_top"      => array(
                "label"  => "nomer pre so",
                "format" => "formatField_he_format",
            ),
            "t_nomer"          => array(
                "label"  => "nomer",
                "format" => "formatField_he_format",
            ),
            "oleh_nama"        => array(
                "label" => "otorisator",
            ),
            "seller_nama"      => array(
                "label" => "salesman",
            ),
            "t_customers_nama" => array(
                "label" => "customer",
            ),
            // --
            "harga_bruto"      => array(
                "label"  => "bruto",
                "format" => "formatField_he_format",
            ),
            "diskon_nilai"     => array(
                "label"  => "diskon",
                "format" => "formatField_he_format",
            ),
            "ppn_nilai"        => array(
                "label"  => "ppn",
                "format" => "formatField_he_format",
            ),
            "harga_netto"      => array(
                "label"  => "netto",
                "format" => "formatField_he_format",
            ),
            // "jml"            => array(
            //     "label"  => "jumlah",
            //     "format" => "formatField_he_format",
            // ),
            // "sub_nett1"      => array(
            //     "label"      => "nilai",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "subtotal"       => array(
            //     "label"  => "nilai",
            //     "format" => "formatField_he_format",
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
            $judul_lap = "MTD " . dtimeNow('d F Y H:i:s');
        }
        elseif ($date1 == dtimeNow('Y-01-01') && $date2 == dtimeNow('Y-m-d')) {
            $judul_lap = "YTD " . dtimeNow('d F Y H:i');
        }
        else {
            $judul_lap = " " . formatTanggal($get_date1, 'd F Y') . " - " . formatTanggal($get_date2, 'd F Y');
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung_indek",
            "title"       => "laporan " . callMenuLabel_he_menu() . " $judul_lap",
            "subTitle"    => "Raw " . callMenuLabel_he_menu(),
            "modul_path"  => $this->modul_path,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow("Y-m-d"),
            // "presopersales"    => base_url() . "laporan/Penjualan/presopersales" . "$strGet",
            // "presopercabang"   => base_url() . "laporan/Penjualan/presopercabang" . "$strGet",
            // "presopercustomer" => base_url() . "laporan/Penjualan/presopercustomer" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function soindek()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $day_now = dtimeNow('d');
        $date_now = dtimeNow('Y-m-d');
        if (isset($_GET['date1'])) {
            $date_now = $_GET['date1'];
        }
        $datemin = $kemarin = date('Y-m-d', strtotime("-30 day", strtotime($date_now)));
        $dateplus = $kemarin = date('Y-m-d', strtotime("1 day", strtotime($date_now)));

        $date_start = dtimeNow('Y-m-01');
        if ($day_now < 14) {
            $date_start = $datemin;
        }
        // cekBiru($datemin . " $date_start");

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : $_GET['date1'] = $date_start;
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : $_GET['date2'] = dtimeNow('Y-m-d');

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
            "title"       => "Laporan " . callMenuLabel_he_menu(),
            "subTitle"    => "Raw Data Sales Order",
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
            "sum_null"    => base_url() . "laporan/Penjualan/so" . "$strGet",
            // "sum_satu"    => base_url() . "laporan/Penjualan/produksosales" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Penjualan/produksotransaksi" . "$strGet",
            // "sum_tiga"    => base_url() . "laporan/Penjualan/produksoproduk" . "$strGet",
            // "sum_empat"   => base_url() . "laporan/Penjualan/produksocustomer" . "$strGet",
            // "sum_lima"    => base_url() . "laporan/Crm/produkoutstandingcabang" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function sosales()
    {
        /* ----------------------------------------------------------------------------------------------------------
         *
         *
         * ----------------------------------------------------------------------------------------------------------*/
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        $this->load->library("Bigdata");
        $ps = new Bigdata();
        $macDataLimit = $this->default_limit;
        $strDate = "";
        $ps->setJenisTr("582so");
        /* ----------------------------------------------------------------------------------------------------------
         * jika tidak ambil range tanggal hanya ditampilkan data ygterlimit $macDataLimit
         * ----------------------------------------------------------------------------------------------------------*/
        //<editor-fold desc="jika hanya punya special dilimit">
        // $myMembers = my_memberships();
        // $limitedMember = array(
        //     "c_special",
        // );
        // $allowedMember = array(
        //     "c_owner",
        //     "c_holding",
        // );
        // $limited = false;
        // $special_condites = array();
        // foreach ($myMembers as $myMember) {
        //     if(in_array($myMember, $limitedMember)){
        //         $special_condites = array(
        //             "oleh_id" => my_id(),
        //         );
        //         $limited = true;
        //         break;
        //     }
        //     if(in_array($myMember, $allowedMember)){
        //         // $special_condites = array(
        //         //     "oleh_id" => my_id(),
        //         // );
        //         $limited = false;
        //         break;
        //     }
        // }
        //</editor-fold>

        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $ps->setCondites($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {

            // if($limited == true){
            //     $condites = array(
            //         "oleh_id" => my_id(),
            //     );
            //     $ps->setCondites($condites);
            // }

            $ps->setLimit($macDataLimit);
        }

        /* ----------------------------------------------------------------------------------------------------------
         * sorting data transaksi
         * ----------------------------------------------------------------------------------------------------------*/
        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $ps->setSortBy($sortings);

        $src = $ps->callBdProdukNonAkunting();
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
            // "kode"           => array(
            //     "label" => "kode",
            // ),
            "nama"           => array(
                "label" => "produk",
            ),
            "no_part"        => array(
                "label" => "no part",
            ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),
            "cabang_nama"    => array(
                "label" => "branch",
                // "format" => "formatField_he_format",
            ),
            "nomer_top"      => array(
                "label"  => "nomer pre so",
                "format" => "formatField_he_format",
            ),
            "nomer"          => array(
                "label"  => "nomer",
                "format" => "formatField_he_format",
            ),
            "oleh_nama"      => array(
                "label" => "otorisator",
            ),
            "seller_nama"    => array(
                "label" => "salesman",
            ),
            "customers_nama" => array(
                "label" => "customer",
            ),
            // --
            "harga"          => array(
                "label"  => "bruto",
                "format" => "formatField_he_format",
            ),
            "disc"           => array(
                "label"  => "diskon",
                "format" => "formatField_he_format",
            ),
            "nett1"          => array(
                "label"  => "netto",
                "format" => "formatField_he_format",
            ),
            "jml"            => array(
                "label"  => "jumlah",
                "format" => "formatField_he_format",
            ),
            "sub_nett1"      => array(
                "label"  => "nilai",
                "format" => "formatField_he_format",
            ),
            // "subtotal"       => array(
            //     "label"  => "nilai",
            //     "format" => "formatField_he_format",
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
            "mode"        => "langsung_indek",
            "title"       => "laporan " . callMenuLabel_he_menu() . " $strDate",
            "subTitle"    => "Raw " . callMenuLabel_he_menu(),
            "modul_path"  => $this->modul_path,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow("Y-m-d"),
            // "presopersales"    => base_url() . "laporan/Penjualan/presopersales" . "$strGet",
            // "presopercabang"   => base_url() . "laporan/Penjualan/presopercabang" . "$strGet",
            // "presopercustomer" => base_url() . "laporan/Penjualan/presopercustomer" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    // ----------------------sales order-------------
    // rendering complite
    public function produksoindek()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $day_now = dtimeNow('d');
        $date_now = dtimeNow('Y-m-d');
        if (isset($_GET['date1'])) {
            $date_now = $_GET['date1'];
        }
        $datemin = $kemarin = date('Y-m-d', strtotime("-30 day", strtotime($date_now)));
        $dateplus = $kemarin = date('Y-m-d', strtotime("1 day", strtotime($date_now)));

        $date_start = dtimeNow('Y-m-01');
        if ($day_now < 14) {
            $date_start = $datemin;
        }
        // cekBiru($datemin . " $date_start");

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : $_GET['date1'] = $date_start;
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : $_GET['date2'] = dtimeNow('Y-m-d');

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
            "title"       => "Sales Order Penjualan",
            "subTitle"    => "Raw Data Sales Order",
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
            "sum_null"    => base_url() . "laporan/Penjualan/produkso" . "$strGet",
            "sum_satu"    => base_url() . "laporan/Penjualan/produksosales" . "$strGet",
            "sum_dua"     => base_url() . "laporan/Penjualan/produksotransaksi" . "$strGet",
            "sum_tiga"    => base_url() . "laporan/Penjualan/produksoproduk" . "$strGet",
            // "sum_empat"   => base_url() . "laporan/Penjualan/produksocustomer" . "$strGet",
            // "sum_lima"    => base_url() . "laporan/Crm/produkoutstandingcabang" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function produkso()
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

        $src_000 = $ps->callOrderan("persediaan_produk");
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
            "fulldate"           => array(
                "label" => "Tanggal",
            ),
            "master_id"          => array(
                "label" => "mID",
            ),
            "kode"               => array(
                "label" => "kode",
            ),
            "extern_nama"        => array(
                "label" => "produk",
            ),
            "_company_olehID"    => array(
                "label" => "urut salesman",
            ),
            "seller_nama"        => array(
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
            "cabang_nama"        => array(
                "label" => "cabang",
            ),
            "_company_stepCode"  => array(
                "label" => "urut order",
            ),
            "_step_1_nomer"      => array(
                "label"      => "nomer order",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            // "_company_customerID" => array(
            //     "label" => "urut customer",
            //     "attr"  => "width='100px'",
            // ),
            "customer_nama"      => array(
                "label" => "customer",
                "attr"  => "width='100px'",
            ),
            "produk_satuan"      => array(
                "label" => "satuan",
            ),
            // "spo_qty_debet_lap" => array(
            //     "label"      => "qty order",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            // ),
            // "spo_debet_lap"     => array(
            //     "label"      => "nilai order",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            // ),
            "qty_debet_lap"      => array(
                "label"      => "qty order",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "debet_lap"          => array(
                "label"      => "nilai order",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "pkd_rejected"       => array(
                "label"      => "qty dibatalkan",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "pkd_closed"         => array(
                "label"      => "qty fulfill",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "pkd_qty_debet_lap"  => array(
                "label"      => "qty dikirim",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "pkd_debet_lap"      => array(
                "label"      => "nilai dikirim",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "pkd_qty_kredit_lap" => array(
                "label"      => "qty outstanding",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
            ),
            "pkd_kredit_lap"     => array(
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
            // "mode"        => "default",
            "mode"        => "langsung_indek",
            // "title"       => "Sales Order Penjualan",
            "title"       => "Raw Data Sales Order $strDate",
            "subTitle"    => "Raw Data Sales Order",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "order_" . dtimeToSecond(dtimeNow()),
            "modal_size"  => "xl",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            // "sum_satu"    => base_url() . "laporan/Penjualan/produksosales" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Penjualan/produksotransaksi" . "$strGet",
            // "sum_tiga"    => base_url() . "laporan/Crm/produkoutstandingsales" . "$strGet",
            // "sum_empat"   => base_url() . "laporan/Crm/produkoutstandingcustomer" . "$strGet",
            // "sum_lima"    => base_url() . "laporan/Crm/produkoutstandingcabang" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function produksosales()
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

        $src_000 = $ps->callOrderan("persediaan_produk");
        $reqData_000 = $src_000['raw'];
        // arrPrintKuning($reqData_000);
        // $arrMaster_id = array();
        foreach ($reqData_000 as $item) {
            $spo_qty_debet_lap = $item['qty_debet_lap'];
            $spo_debet_lap = $item['debet_lap'];
            $qty_rejected = $item['pkd_rejected'];
            $qty_closed = $item['pkd_closed'];

            $qty_debet_lap = $item['pkd_qty_debet_lap'];
            $debet_lap = $item['pkd_debet_lap'];

            $qty_kredit_lap = $item['pkd_qty_kredit_lap'];
            $kredit_lap = $item['pkd_kredit_lap'];
            $subjek_id = $item['seller_id'];

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] += $spo_qty_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] += $spo_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_rejected'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_rejected'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_rejected'] += $qty_rejected;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_closed'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_closed'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_closed'] += $qty_closed;

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
            "sum_qty_rejected"      => array(
                "label"   => "qty dibatalkan",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_qty_closed"        => array(
                "label"   => "qty fulfill",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),

            "sum_qty_debet_lap"  => array(
                "label"   => "qty dikitrim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_debet_lap"      => array(
                "label"   => "nilai dikitrim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_qty_kredit_lap" => array(
                "label"   => "qty outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
                // "links"   => array(
                //     "target"  => "laporan/Crm/produkoutstandingraw",
                //     "title"   => "Transaksi per salesman",
                //     "key"     => "seller_id",
                //     "heading" => "seller_nama",
                // ),
            ),
            "sum_kredit_lap"     => array(
                "label"   => "nilai outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
                "links"   => array(
                    "target"  => "laporan/Penjualan/produksotransaksipersales",
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
            "title"       => "Summary Order by salesman $strDate",
            "subTitle"    => "Raw Data order",
            "modul_path"  => $this->modul_path,
            "color_bar"   => "box-success",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "produkso_" . dtimeToSecond(dtimeNow()),
            "link_detile" => base_url() . "laporan/Penjualan/perindekshow/produksotransaksipersales",
            // "loader_div"  => "sum_tiga",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function produksotransaksi()
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

        $src_000 = $ps->callOrderan("persediaan_produk");
        $reqData_000 = $src_000['raw'];
        // arrPrintKuning($reqData_000);
        // $arrMaster_id = array();
        $arrSubjek = array();
        $seller_nama = "";
        $seller_id = "";

        foreach ($reqData_000 as $item) {
            $spo_qty_debet_lap = $item['qty_debet_lap'];
            $spo_debet_lap = $item['debet_lap'];
            $qty_rejected = $item['pkd_rejected'];
            $qty_closed = $item['pkd_closed'];

            $qty_debet_lap = $item['pkd_qty_debet_lap'];
            $debet_lap = $item['pkd_debet_lap'];

            $qty_kredit_lap = $item['pkd_qty_kredit_lap'];
            $kredit_lap = $item['pkd_kredit_lap'];
            $subjek_id = $item['master_id'];

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] += $spo_qty_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] += $spo_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_rejected'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_rejected'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_rejected'] += $qty_rejected;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_closed'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_closed'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_closed'] += $qty_closed;

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

            // $seller_id = $item['seller_id'];
            // $seller_nama = $item['seller_nama'];
        }

        /* ------------------------------------------------------------------
         * untuk kepentingan pembuatan title
         * ------------------------------------------------------------------*/
        if (isset($_GET['ky'])) {

            switch ($_GET['ky']) {
                case "seller_id":
                    $this->load->model("Mdls/MdlEmployee");
                    $sl = new MdlEmployee();
                    $this->db->where("id", $_GET[$_GET['ky']]);
                    $sellers = $sl->callAllSeller();
                    $seller_datas = $sellers['data'];
                    // arrPrintPink($seller_datas);
                    $seller_nama = $seller_datas[$_GET[$_GET['ky']]]['nama'];
                    break;
            }
            // cekMerah($seller_id);
        }

        // cekBiru($seller_nama);
        // asort($arrMaster_id);
        // $minMaster_id = min($arrMaster_id);
        // $maxMaster_id = max($arrMaster_id);
        // // cekBiru($arrMaster_id);
        // // cekMerah());
        // // cekMerah();
        // // matiHere();

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
            "fulldate"          => array(
                "label"  => "Tanggal",
                "format" => "formatField_he_format",
            ),
            "master_id"         => array(
                "label" => "mID",
            ),
            // "_company_olehID" => array(
            //     "label" => "urut salesman",
            // ),
            "_company_sellerID" => array(
                "label" => "urut salesman",
            ),
            "seller_nama"       => array(
                "label" => "salesman",
            ),
            // "seller_id"   => array(
            //     "label" => "sID",
            // ),

            "cabang_nama"       => array(
                "label" => "cabang",
            ),
            "_company_stepCode" => array(
                "label" => "urut order",
            ),
            "_step_1_nomer"     => array(
                "label"      => "nomer order",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            // "_company_customerID" => array(
            //     "label" => "urut customer",
            //     "attr"  => "width='100px'",
            // ),
            "customer_nama"     => array(
                "label"       => "customer",
                "attr"        => "class='dt-nama-{pengenal}'",
                "collapsible" => array(
                    "layout" => false,
                    "key"    => "master_id",
                    "target" => "laporan/Penjualan/produksoproduk",
                ),
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
                "links"   => array(
                    "target"  => "laporan/Penjualan/produkso",
                    "title"   => "Produk per salesman",
                    "key"     => "master_id",
                    "heading" => "_step_1_nomer",
                ),
            ),
            "sum_qty_rejected"      => array(
                "label"   => "qty dibatalkan",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_qty_closed"        => array(
                "label"   => "qty fulfill",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),

            "sum_qty_debet_lap"  => array(
                "label"   => "qty dikirim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_debet_lap"      => array(
                "label"   => "nilai dikirim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_qty_kredit_lap" => array(
                "label"   => "qty outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
                // "links"   => array(
                //     "target"  => "laporan/Crm/produkoutstandingraw",
                //     "title"   => "Transaksi per salesman",
                //     "key"     => "seller_id",
                //     "heading" => "seller_nama",
                // ),
            ),
            "sum_kredit_lap"     => array(
                "label"   => "nilai outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
                "links"   => array(
                    "target"  => "laporan/Penjualan/produksoproduk",
                    "title"   => "Produk per salesman",
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
        $i = isset($i) ? $i++ : 0;
        $title_str = isset($_GET['ky']) ? "Laporan SO oleh <b>" . $seller_nama . "</b>" : 'laporan Transaksi SO';
        $data = array(
            "mode"        => "langsung_indek",
            "title"       => "$title_str $strDate",
            "subTitle"    => "Raw Data order",
            "modul_path"  => $this->modul_path,
            "color_bar"   => "box-danger",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "produksotransaksi_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "modal_size"  => "xl",
            // "loader_div"  => "sum_tiga",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function produksoproduk()
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

        $src_000 = $ps->callOrderan("persediaan_produk");
        $reqData_000 = $src_000['raw'];
        // arrPrintKuning($reqData_000);
        // $arrMaster_id = array();
        foreach ($reqData_000 as $item) {
            $spo_qty_debet_lap = $item['qty_debet_lap'];
            $spo_debet_lap = $item['debet_lap'];
            $qty_rejected = $item['pkd_rejected'];
            $qty_closed = $item['pkd_closed'];

            $qty_debet_lap = $item['pkd_qty_debet_lap'];
            $debet_lap = $item['pkd_debet_lap'];

            $qty_kredit_lap = $item['pkd_qty_kredit_lap'];
            $kredit_lap = $item['pkd_kredit_lap'];
            $subjek_id = $item['extern_id'];

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] += $spo_qty_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] += $spo_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_rejected'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_rejected'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_rejected'] += $qty_rejected;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_closed'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_closed'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_closed'] += $qty_closed;

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
            "extern_id"   => array(
                "label" => "pID",
            ),
            "kode"        => array(
                "label" => "kode",
            ),
            "extern_nama" => array(
                "label" => "produk",
            ),

            // "seller_nama" => array(
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
            "sum_qty_rejected"      => array(
                "label"   => "qty dibatalkan",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_qty_closed"        => array(
                "label"   => "qty fulfill",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),

            "sum_qty_debet_lap"  => array(
                "label"   => "qty dikirim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_debet_lap"      => array(
                "label"   => "nilai dikirim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_qty_kredit_lap" => array(
                "label"   => "qty outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
                // "links"   => array(
                //     "target"  => "laporan/Crm/produkoutstandingraw",
                //     "title"   => "Transaksi per salesman",
                //     "key"     => "seller_id",
                //     "heading" => "seller_nama",
                // ),
            ),
            "sum_kredit_lap"     => array(
                "label"   => "nilai outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
                // "links"   => array(
                //     "target"  => "laporan/Penjualan/produksotransaksi",
                //     "title"   => "Transaksi per salesman",
                //     "key"     => "seller_id",
                //     "heading" => "seller_nama",
                // ),
            ),
        );
        // die("testing");
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
            "title"       => "Summary per Produk SO $strDate",
            "subTitle"    => "Raw Data order",
            "modul_path"  => $this->modul_path,
            "color_bar"   => "box-success",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "produksoproduk_" . dtimeToSecond(dtimeNow()),
            "modal_size"  => "xl",
            "layout"      => isset($_GET['layout']) ? $_GET['layout'] : true,
            // "loader_div"  => "sum_tiga",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function produksocustomer()
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

        $src_000 = $ps->callOrderan("persediaan_produk");
        $reqData_000 = $src_000['raw'];
        // arrPrintKuning($reqData_000);
        // $arrMaster_id = array();
        foreach ($reqData_000 as $item) {
            $spo_qty_debet_lap = $item['qty_debet_lap'];
            $spo_debet_lap = $item['debet_lap'];
            $qty_rejected = $item['pkd_rejected'];
            $qty_closed = $item['pkd_closed'];

            $qty_debet_lap = $item['pkd_qty_debet_lap'];
            $debet_lap = $item['pkd_debet_lap'];

            $qty_kredit_lap = $item['pkd_qty_kredit_lap'];
            $kredit_lap = $item['pkd_kredit_lap'];
            $subjek_id = $item['customer_id'];

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_qty_debet_lap'] += $spo_qty_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_spo_debet_lap'])) {
                $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_spo_debet_lap'] += $spo_debet_lap;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_rejected'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_rejected'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_rejected'] += $qty_rejected;

            if (!isset($arrSumSubjek[$subjek_id]['sum_qty_closed'])) {
                $arrSumSubjek[$subjek_id]['sum_qty_closed'] = 0;
            }
            $arrSumSubjek[$subjek_id]['sum_qty_closed'] += $qty_closed;

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
            "customer_id"   => array(
                "label" => "pID",
            ),
            // "kode"                => array(
            //     "label" => "kode",
            // ),
            "customer_nama" => array(
                "label" => "produk",
                "links" => array(
                    "target"  => "laporan/Penjualan/produksotransaksi",
                    "title"   => "Transaksi per konsumen",
                    "key"     => "customer_id",
                    "heading" => "customer_nama",
                ),
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
            "sum_qty_rejected"      => array(
                "label"   => "qty dibatalkan",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_qty_closed"        => array(
                "label"   => "qty fulfill",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),

            "sum_qty_debet_lap"  => array(
                "label"   => "qty dikirim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_debet_lap"      => array(
                "label"   => "nilai dikirim",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
            ),
            "sum_qty_kredit_lap" => array(
                "label"   => "qty outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
                // "links"   => array(
                //     "target"  => "laporan/Crm/produkoutstandingraw",
                //     "title"   => "Transaksi per salesman",
                //     "key"     => "seller_id",
                //     "heading" => "seller_nama",
                // ),
            ),
            "sum_kredit_lap"     => array(
                "label"   => "nilai outstanding",
                "format"  => "formatField_he_format",
                "attr"    => "class='text-right'",
                "summary" => true,
                // "links"   => array(
                //     "target"  => "laporan/Penjualan/produksotransaksi",
                //     "title"   => "Transaksi per salesman",
                //     "key"     => "seller_id",
                //     "heading" => "seller_nama",
                // ),
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
            "title"       => "Summary per konsumen SO $strDate",
            "subTitle"    => "Raw Data order",
            "modul_path"  => $this->modul_path,
            "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "produksokonsumen_" . dtimeToSecond(dtimeNow()),
            // "loader_div"  => "sum_tiga",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    // loader dowang, isi ngeload dari methode produksotransaksi
    public function produksotransaksipersales()
    {
        if (isset($_GET['ky'])) {
            $seller_datas[$_GET[$_GET['ky']]]['id'] = $_GET[$_GET['ky']];
        }
        else {
            $this->load->model("Mdls/MdlEmployee");
            $sl = new MdlEmployee();
            $sellers = $sl->callAllSeller();
            $seller_datas = $sellers['data'];
        }

        // showLast_query("kuning");
        // cekHijau(sizeof($seller_datas));
        // arrPrintPink($seller_datas);

        /* -------------------------------------------------------------------------------------------------
         * menampilkan data
         * -------------------------------------------------------------------------------------------------*/
        foreach ($seller_datas as $seller_id => $seller_speks) {
            $_GET['ky'] = "seller_id";
            $_GET[$_GET['ky']] = $seller_id;
            $seller_nama = $seller_speks['nama'];
            $div_id = "show_$seller_id";
            $strget = $_GET;
            $strGet = "?1=1";
            foreach ($strget as $kget => $vget) {
                $strGet .= "&$kget=$vget";
            }

            // mati_disini(__LINE__);
            $link = base_url() . "laporan/Penjualan/produksotransaksi" . "$strGet";
            echo "<div id='$div_id'>$div_id</div>";
            echo "<script>$('#$div_id').load('$link')</script>";
        }
    }

    // -----------------------------------------------------------------------
    // perender per-peran detile dari loader bisa dipakai umum
    public function perindekshow()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();
        $ygditampilkan = url_segment(4);

        $day_now = dtimeNow('d');
        $date_now = dtimeNow('Y-m-d');
        $month_now = dtimeNow('Y-m');
        if (isset($_GET['date1'])) {
            $date_now = $_GET['date1'];
        }
        $datemin = $kemarin = date('Y-m-d', strtotime("-30 day", strtotime($date_now)));
        $dateplus = $kemarin = date('Y-m-d', strtotime("1 day", strtotime($date_now)));

        $date_start = dtimeNow('Y-m-01');
        if ($day_now < 14) {
            $date_start = $datemin;
        }
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
            "title"       => "Sales Order Penjualan",
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
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            // "sum_null"    => base_url() . "laporan/Penjualan/produksopersales" . "$strGet",
            "sum_null"    => base_url() . "laporan/Penjualan/$ygditampilkan" . "$strGet",
            // "sum_satu"    => base_url() . "laporan/Penjualan/produksosales" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Penjualan/produksotransaksi" . "$strGet",
            // "sum_tiga"    => base_url() . "laporan/Penjualan/produksoproduk" . "$strGet",
            // "sum_empat"   => base_url() . "laporan/Penjualan/produksocustomer" . "$strGet",
            // "sum_lima"    => base_url() . "laporan/Crm/produkoutstandingcabang" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }
    // -----------------------------------------------------------------------

    // ----------------------------------------------penjualan
    public function produk()
    {
        /* ----------------------------------------------------------------------------------------------------------
         * untuk memasang master data dari persediaan harus update Coms/ComRekeningPembantuProduk
         * di dlmnya terjadi pairingan dengan data produk dan transaksi da ditampilkan dl satu array
         * ----------------------------------------------------------------------------------------------------------*/
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $ps = new ComRekeningPembantuProduk();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        // $jenisTr = $this->jenisTr_penjualan;
        $jenisTr = $this->jenisTrs;

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
            $this->db->limit($this->default_limit);
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $ps->setSortBy($sortings);

        $ps->setJenisTr($jenisTr);
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
            "placeName"      => array(
                "label" => "cb",
                // "format" => "formatField_he_format",
            ),
            "nomer_top"      => array(
                "label"  => "nomer pre",
                "format" => "formatField_he_format",
            ),
            "nomer"          => array(
                "label"  => "nomer",
                "format" => "formatField_he_format",
            ),
            "seller_nama"    => array(
                "label" => "salesman",
            ),
            "customers_nama" => array(
                "label" => "customer",
            ),
            // --
            // "harga"          => array(
            //     "label"  => "hpp",
            //     "format" => "formatField_he_format",
            // ),
            "i_jual"         => array(
                "label"  => "bruto",
                "format" => "formatField_he_format",
            ),
            "m_disc"         => array(
                "label"  => "diskon",
                "format" => "formatField_he_format",
            ),

            "i_nett1"    => array(
                "label"  => "netto",
                "format" => "formatField_he_format",
            ),
            "qty_kredit" => array(
                "label"  => "jumlah",
                "format" => "formatField_he_format",
            ),

            // "ppn"     => array(
            //     "label"  => "ppn",
            //     "format" => "formatField_he_format",
            // ),
            // "kredit"         => array(
            //     "label"  => "persediaan",
            //     "format" => "formatField_he_format",
            // ),
            "i_subtotal" => array(
                "label"  => "penjualan",
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
            // "mode" => "default",
            "mode"        => "langsung",
            "title"       => "Aktifitas penjualan $strDate",
            "subTitle"    => "all",
            "modul_path"  => $this->modul_path,
            "jenisTr"     => $this->jenisTr_penjualan,
            "data_id"     => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow("Y-m-d"),
            // "sum_satu" => base_url() . "laporan/Penjualan/produkperproduk" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function produkperproduk()
    {
        /* ----------------------------------------------------------------------------------------------------------
         *
         *
         * ----------------------------------------------------------------------------------------------------------*/
        $jenisTr = $this->jenisTrs;
        // $this->default_limit =2;
        $this->load->library("Bigdata");
        $bd = new Bigdata();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $bd->setCondites($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $bd->setLimit($this->default_limit);
        }
        $bd->setJenistr($jenisTr);
        $src = $bd->callBdProdukAkunting();
        $srcMasterData = $masterData = $src['data'];
        $masterDataJml = $src['data_jml'];
        // showLast_query("kuning");
        // cekBiru(sizeof($srcMasterData));
        // arrPrintPink($srcMasterData);
        /* --------------------------------------------------------------------------------------------------
         *peparasi data harus 3 step
         * #1 pengumpulan data transaksi (main)
         * --------------------------------------------------------------------------------------------------*/
        $olahan = array();
        foreach ($srcMasterData as $masterDatum) {
            // $sellerID = $masterDatum['oleh_id'];
            // $cabangID = $masterDatum['cabang_id'];
            // $transaksi_id = $masterDatum['transaksi_id'];
            $extern_id = $masterDatum['extern_id'];

            $olahan_src[$extern_id] = $masterDatum;

            //---------------------------------------------------------------------------------
            $sum_jml = $masterDatum['qty_kredit'];
            if (!isset($olahan[$extern_id]['sumJml'])) {
                $olahan[$extern_id]['sumJml'] = 0;
            }
            $olahan[$extern_id]['sumJml'] += $sum_jml;
            //---------------------------------------------------------------------------------
            $sum_jual_nppn = $masterDatum['harga'];
            // if(!isset($olahan[$extern_id]['sumPenjualan'])){
            //     $olahan[$extern_id]['sumPenjualan'] = 0;
            // }
            $olahan[$extern_id]['harga'] = $sum_jual_nppn;
            //---------------------------------------------------------------------------------
            $sum_sub_jual_nppn = $masterDatum['i_subtotal'];
            if (!isset($olahan[$extern_id]['sumPenjualan'])) {
                $olahan[$extern_id]['sumPenjualan'] = 0;
            }
            $olahan[$extern_id]['sumPenjualan'] += $sum_sub_jual_nppn;

        }

        // arrPrintWebs($olahan);
        /* --------------------------------------------------------------------------------------------------
         * #2 membuat tambahan kolom summary
         * --------------------------------------------------------------------------------------------------*/
        // $hasilOlahan = array();
        // foreach ($olahan as $tr_id => $itemParam) {
        //     // arrPrintWebs($itemParam);
        //     $customer_id = $itemParam['m_customerID'];
        //
        //     //---------------------------------------------------------------------------------
        //     $sub_transaksi_nilai = $itemParam['m_harga_nett1'];
        //     if(!isset($hasilOlahan[$customer_id]['sumBruto'])){
        //         $hasilOlahan[$customer_id]['sumBruto'] = 0;
        //     }
        //     $hasilOlahan[$customer_id]['sumBruto'] += $sub_transaksi_nilai;
        //     //---------------------------------------------------------------------------------
        //     $sub_transaksi_nilai_2 = $itemParam['m_harga_nett3'];
        //     if(!isset($hasilOlahan[$customer_id]['sumNetto'])){
        //         $hasilOlahan[$customer_id]['sumNetto'] = 0;
        //     }
        //     $hasilOlahan[$customer_id]['sumNetto'] += $sub_transaksi_nilai_2;
        //     //---------------------------------------------------------------------------------
        //     $sub_total_disc = $itemParam['m_total_disc'];
        //     if(!isset($hasilOlahan[$customer_id]['sumTotalDisc'])){
        //         $hasilOlahan[$customer_id]['sumTotalDisc'] = 0;
        //     }
        //     $hasilOlahan[$customer_id]['sumTotalDisc'] += $sub_total_disc;
        //     //---------------------------------------------------------------------------------
        //
        // }
        // cekBiru($hasilOlahan);
        /* --------------------------------------------------------------------------------------------------
         * #3 pengumpulan data menjadi data siap tempur
         * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($olahan as $prod_id => $itemParam) {
            // $customer_id = $itemParam['m_customerID'];
            $hasilOlahan_1[$prod_id] = $itemParam + $olahan_src[$prod_id];
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $hasilOlahan_1;
        // matiHere();
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "kode"        => array(
                "label"  => "kode",
                "format" => "formatField_he_format",
            ),
            "extern_nama" => array(
                "label"  => "Produk",
                "format" => "formatField_he_format",
            ),
            "no_part"     => array(
                "label"  => "no part",
                "format" => "formatField_he_format",
            ),
            // "merek_nama"     => array(
            //     "label"  => "merek",
            //     "format" => "formatField_he_format",
            // ),
            // "harga" => array(
            //     "label"  => "hpp",
            //     "format" => "formatField_he_format",
            // ),
            // "disc"    => array(
            //     "label"   => "disc",
            //     "format"  => "formatField_he_format",
            //     "summary" => true,
            //     "attr"    => "class='text-right'",
            // ),
            // "nett1"      => array(
            //     "label"   => "harga nett",
            //     "format"  => "formatField_he_format",
            //     // "summary" => true,
            //     "attr"    => "class='text-right'",
            // ),

            "sumJml" => array(
                "label"   => "qty",
                "format"  => "formatField_he_format",
                "summary" => true,
                "attr"    => "class='text-right'",
            ),

            "sumPenjualan" => array(
                "label"   => "nilai penjualan",
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
            "title"       => "Laporan penjualan by produk" . " $strDate",
            "subTitle"    => "all",
            "modul_path"  => $this->modul_path,
            "jenisTr"     => $jenisTr,
            "data_id"     => "perproduk_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "color_bar"   => "box-info",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow("Y-m-d"),
        );
        // $this->load->view("laporan", $data);
        $this->load->view("laporan", $data);
    }

    public function produkpercustomer()
    {
        /* ----------------------------------------------------------------------------------------------------------
         *
         *
         * ----------------------------------------------------------------------------------------------------------*/
        $jenisTr = $this->jenisTrs;
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        $this->load->library("Bigdata");
        $ps = new Bigdata();

        $strDate = "";
        $ps->setJenisTr("582spo");
        /* ----------------------------------------------------------------------------------------------------------
         * jika tidak ambil range tanggal hanya ditampilkan data ygterlimit
         * ----------------------------------------------------------------------------------------------------------*/
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(transaksi.dtime)>=" => $get_date1,
                "date(transaksi.dtime)<=" => $get_date2,
                // "trash_4" => 1,

            );
            $ps->setCondites($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $ps->setLimit($this->default_limit);
        }

        /* ----------------------------------------------------------------------------------------------------------
         * sorting data transaksi
         * ----------------------------------------------------------------------------------------------------------*/
        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $ps->setSortBy($sortings);


        $src = $ps->callBdProdukNonAkunting();
        $masterData = $src['data'];
        // showLast_query("kuning");
        // cekBiru(sizeof($masterData));
        // arrPrintHijau($masterData);
        foreach ($masterData as $masterDatum) {
            $sellerID = $masterDatum['oleh_id'];
            $cabangID = $masterDatum['cabang_id'];
            $customerID = $masterDatum['customerID'];;
            $sub_nett1 = $masterDatum['sub_nett1'];

            $olahan[$customerID] = $masterDatum;

            if (!isset($olahan0[$customerID]['sumPso'])) {
                $olahan0[$customerID]['sumPso'] = 0;
            }
            $olahan0[$customerID]['sumPso'] += $sub_nett1;


            // cekMerah($customerID);
            if (!isset($olahan2[$customerID]['sumCancel'])) {
                $olahan2[$customerID]['sumCancel'] = 0;
            }
            if ($masterDatum['trash_4'] == 1) {
                $olahan2[$customerID]['sumCancel'] += $sub_nett1;
            }
            // $olahan[$customerID] = $datas;
        }

        foreach ($olahan as $customer_id => $itemParam) {
            $total = $olahan0[$customer_id]['sumPso'];
            $total2 = $olahan2[$customer_id]['sumCancel'];

            $subtotal['subTotal'] = $total - $total2;
            $hasilOlahan[$customer_id] = $itemParam + $olahan0[$customer_id] + $olahan2[$customer_id] + $subtotal;
        }

        $masterData = $hasilOlahan;
        // matiHere();
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "customerName" => array(
                "label"  => "Customer",
                "format" => "formatField_he_format",
            ),
            "sumPso"       => array(
                "label"   => "pre SO bruto",
                "format"  => "formatField_he_format",
                "summary" => true,
                "attr"    => "class='text-right'",
            ),
            "sumCancel"    => array(
                "label"   => "pre SO cancel",
                "format"  => "formatField_he_format",
                "summary" => true,
                "attr"    => "class='text-right'",
            ),
            "subTotal"     => array(
                "label"   => "pre SO netto",
                "format"  => "formatField_he_format",
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
            "title"       => "Laporan By Customer" . " $strDate",
            "subTitle"    => "all",
            "modul_path"  => $this->modul_path,
            "jenistr"     => $jenisTr,
            "data_id"     => "presopercustomer",
            "color_bar"   => "box-success",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow("Y-m-d"),
        );
        $this->load->view("laporan", $data);
    }

    /*------------bulanan per-per-----view:laporan_periode/produk_bulanan----------------------------------------------------------*/
    public function cekpenjualanbulankategori()
    {
        // arrPrintHijau(url_segment());
        $subjek_0 = url_segment(4);
        $subjek = isset($subjek_0) ? "/$subjek_0" : "";
        $aktivitas = url_segment(5);
        // $aktivitas = url_segment(5);
        $title_now = isset($aktivitas) ? "Sales Order" : "Penjualan";
        // -----------------------------------------------------------------
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-01-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $this->load->model("Mdls/MdlRawPenjualan");
        $dt = new MdlRawPenjualan();

        // $src_00 = $dt->callSummaryProdukBulanan($date1, $date2);
        $tbl_1 = "__raw_rek_pembantu__4010";
        $condites = array(
            // "fulldate>=" => $date1,
            // "fulldate<=" => $date2,
            "date($tbl_1.dtime)>=" => $date1,
            "date($tbl_1.dtime)<=" => $date2,
            // "produk_id" => "46768",
        );
        $this->db->where($condites);
        if (my_cabang_id() != CB_ID_PUSAT) {
            $this->db->where("cabang_id", my_cabang_id());
        }

        //    ----------------------------
        //     $src_00 = $dt->callSummaryProdukBulanan($date1, $date2);
        $src_00 = $dt->callJoinSummary();
        // showLast_query("kuning");
        // $src_mtd = $src_00['mtd'];
        // $src_yang_lalu = $src_00['ytd_previous'];
        // $arrRekenings = $src_00['rekening'];
        // $arrTransaksiTipes = $src_00['transaksi_tipe'];
        // cekBiru(sizeof($src_00));
        // arrPrint($src_00);
        // arrPrint(array_slice($src_00,0,3));
        // arrPrint(array_slice($src_00,0,1000));
        // matiHere(__LINE__);
        $src_harians = array();
        $total_hpp = 0;
        $total_penjualan = 0;
        $total_qty = 0;
        foreach ($src_00 as $items) {
            $thn = $items->thn;
            $bln = $items->bln;
            // $tgl = $items->tgl;
            // $dtime = $items->dtime;
            $thn_bln = "$thn-$bln";

            $sum_qty_kredit = $items->sum_qty_kredit;
            $sum_qty_debet = $items->sum_qty_debet;
            $sum_kredit = $items->sum_kredit;
            $sum_debet = $items->sum_debet;
            $penjualan_nett = $sum_kredit - $sum_debet;
            $qty_nett = $sum_qty_kredit - $sum_qty_debet;
            // $penjualan_nett = $sum_kredit;
            $sum_hpp = $items->sum_hpp;
            $produk_id = $items->kategori_id;
            // $produk_id = $oleh_id = $items->seller_id;
            $margin_data = $penjualan_nett > 0 ? ((1 - ($sum_hpp / $penjualan_nett)) * 100) : "0";
            $ymd = "$thn-$bln";
            $item_data = (array)$items + array('netto' => $penjualan_nett, 'netto_qty' => $qty_nett);
            // $item_data['margin'] = 1 - ($sum_hpp / $penjualan_nett);

            $src_harians[$produk_id][$ymd] = $item_data;
            $src_margins[$produk_id] = $margin_data;

            if (!isset($src_qty[$produk_id])) {
                $src_qty[$produk_id] = 0;
            }
            $src_qty[$produk_id] += $sum_qty_kredit;

            $total_hpp += $sum_hpp;
            $total_penjualan += $penjualan_nett;
            $total_qty += $sum_qty_kredit;

            $subjekYgTerlibats[$produk_id]["id"] = $produk_id;
            $subjekYgTerlibats[$produk_id]["nama"] = $items->kategori_nama;

            /*
             * sumbulanan
             * */
            if (!isset($sumbulanan[$thn_bln])) {
                $sumbulanan[$thn_bln] = 0;
            }
            $sumbulanan[$thn_bln] += $penjualan_nett;
        }

        // cekBiru($sumbulanan);
        // cekBiru(array_sum($sumbulanan));

        $margin_avg = (1 - ($total_hpp / $total_penjualan)) * 100;
        $total_bawah["penjualan"] = $total_penjualan;
        $total_bawah["hpp"] = $total_hpp;
        $total_bawah["qty"] = $total_qty;
        // cekBiru("$margin_avg = (1 - ($total_hpp / $total_penjualan)) * 100;");
        // arrPrint($src_qty);
        // // arrPrint($src_ytd);
        // // arrPrint($src_mtd);
        // cekBiru(count($src_harians));
        // arrPrintWebs(array_slice($src_harians,0,2));
        // matiHere(__LINE__);

        /* --------------------------------------------------------------------------------
         * locker stok aktive
         * --------------------------------------------------------------------------------
         */
        // $this->load->model("Mdls/MdlLockerStock");
        // $ls = new MdlLockerStock();
        // if (my_cabang_id() != CB_ID_PUSAT) {
        //     $this->db->where("cabang_id", my_cabang_id());
        // }
        // $this->db->select(array(
        //     "produk_id",
        //     "nama",
        //     "sum(jumlah) as 'sum_jumlah'"
        // ));
        // $this->db->group_by("produk_id");
        // $src_ls_00 = $ls->callProdukStok();
        // $src_ls = $src_ls_00['datas'];
        // $src_ls_all = $src_ls_00['all'];
        // // showLast_query("orange");
        // // cekOrange(sizeof($src_ls) . " all $src_ls_all");
        // // arrPrintHijau(array_slice($src_ls, 0, 1));
        // $ls_aktivies = array();
        // foreach ($src_ls as $src_l) {
        //     $ls_produk_id = $src_l->produk_id;
        //
        //     $ls_aktivies[$ls_produk_id] = $src_l;
        // }
        // cekOrange(sizeof($ls_aktivies));

        /* --------------------------------------------------------------------------------
         * SUBJECT SPEKS
         * --------------------------------------------------------------------------------
         */
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $masterHeaders = array(
            // "kategori_id"   => array(
            //     "label" => "cid",
            // ),
            "kategori_nama" => array(
                "label" => "produk",
                // "attr"       => "style='width=100px;'",
                // "links"       => array(
                //     "heading" => "kategori_nama",
                //     "key" => "kategori_id",
                //     "target" => "laporan/Penjualan/cekpenjualanbulanan/produk",
                // ),
            ),
        );
        //    ----------------------------

        // $condites_prod = array();
        // $this->db->where($condites_prod);
        if (ipadd() == "202.65.117.72") {
            if (isset($_GET['limit']) && $_GET['limit'] > 0) {
                $this->db->limit($_GET['limit']);
            }
        }

        $src_produks_0 = $pr->callSpecs();
        // showLast_query("biru");
        foreach ($src_produks_0 as $p_id => $item) {
            $subj_id = $item->kategori_id;
            $stok_locker["stok_locker"] = isset($ls_aktivies[$p_id]) ? $ls_aktivies[$p_id]->sum_jumlah : 0;
            $src_produks[$subj_id] = (array)$item + $stok_locker;
        }
        // cekHijau(count($src_produks));
        // arrPrintHijau($src_produks + $subjekYgTerlibats);
        // arrPrintHijau(array_slice($src_produks, 0, 1));
        // matiHere(__LINE__);
        $arrHeaders = array();


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
            $judul_lap = "Year To Date () ";
            $subTitlelap = dtimeNow('d F Y H:i:s');
        }
        elseif ($date1 == dtimeNow('Y-01-01') && $date2 == dtimeNow('Y-m-d')) {
            $judul_lap = "YTD ";
            $subTitlelap = dtimeNow('d F Y H:i:s');
        }
        else {
            $judul_lap = " " . formatTanggal($get_date1, 'd F Y') . " - " . formatTanggal($get_date2, 'd F Y');
        }

        /* --------------------------------------------------------------------------------
         * sub_header
         * --------------------------------------------------------------------------------*/
        foreach ($src_harians as $prid => $src_harian_dts) {
            foreach ($src_harian_dts as $ymd => $src_harian_dt) {

            }
        }
        $subHeaders = array(
            "sum_kredit" => array(
                "label" => "penjualan"
            ),
            "sum_hpp"    => array(
                "label" => "hpp"
            ),

        );

        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "produk_bulanan_perkategori",
            "title"       => "$title_now Per $subjek_0 $judul_lap",
            "subTitle"    => $subTitlelap,
            "title_now"   => $title_now,
            "judul_lap"   => $judul_lap,
            "modul_path"  => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "data_id"     => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout"      => isset($_GET['layout']) ? $_GET['layout'] : true,
            "master_data" => $src_produks + $subjekYgTerlibats,
            "child_data"  => $src_harians,
            "margin_data" => $src_margins,
            "margin_avg"  => $margin_avg,
            "qty_data"    => $src_qty,
            "total_bawah" => $total_bawah,

            "arrHeaders"    => $arrHeaders,
            "masterHeaders" => $masterHeaders,

            // navigasi
            "url"           => base_url(uri_string()) . "$strGet",
            "aktivitas"     => $aktivitas,
            "subjek"        => $subjek_0,
            "strGet"        => $strGet,
            "date1"         => $date1,
            "date2"         => $date2,
            "date_min"      => 1,
            "date_max"      => dtimeNow('Y-m-d'),
            "loader_div"    => "sum_null",
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        $this->load->view("laporan_periode", $data);


    }

    public function viewpenjualanbulananperkategori()
    {
        $this->load->helper("he_mass_table");
        // arrPrintKuning(url_segment());
        $subjek_0 = url_segment(4);
        $subjek = isset($subjek_0) ? "/$subjek_0" : "";
        $aktivitas_0 = url_segment(5);
        $aktivitas = isset($aktivitas_0) ? "/$aktivitas_0" : "";

        $day_now = dtimeNow('d');
        $date_now = dtimeNow('Y-m-d');
        $year_now = dtimeNow('Y');
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

        $_GET['date1'] = $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-01-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : $_GET['date2'] = dtimeNow('Y-m-d');

        /* -----------------------------------------------------------------------------------------------
       * navigasi bulan sebelumnya
       * -----------------------------------------------------------------------------------------------*/
        //<editor-fold desc="bulan sebelumnya">
        // $date_start_sebelumnya = dtimeNow('Y-m-01');
        $date_start_sebelumnya = previousYear($date2) . "-01-01";
        $date_stop_sebelumnya = date("Y-12-31", strtotime($date_start_sebelumnya));
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
        $date_start_setelahnya = date("Y-01-01", strtotime($date2));
        if (date("Y", strtotime($date_start_setelahnya)) == $year_now) {

            $date_stop_setelahnya = date("Y-m-d", strtotime($date_now));
        }
        else {
            $date_stop_setelahnya = date("Y-12-31", strtotime($date_start_setelahnya));
        }

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
        $nama_bulan_ygtampil = formatTanggal($date2, "Y");
        $ulr_tahun_ini = current_url() . "?date1=" . dtimeNow('Y-01-01') . "&date2=" . dtimeNow('Y-m-d');
        //</editor-fold>

        /* -----------------------------------------------------------------------------------------------
         * logik untuk mematikan butun bulan belum sukses jadi masih dimatikan
         * -----------------------------------------------------------------------------------------------*/
        // cekHijau("$date_now **$date2**  $date_start //// $date_start_setelahnya *** " . dtimeToSecond($date2));
        $btn_disabled = "";
        $btn_disabled_mtd = "";
        $btn_disabled_ytd = "";
        $btn_disabled_aft = "";
        $btn_active = "";
        if (($date1 == dtimeNow('Y-m-01')) && ($date2 == dtimeNow('Y-m-d'))) {
            // cekBiru(dtimeToSecond($date2) ."<br>" . dtimeToSecond($date_start_setelahnya));
            $btn_disabled_mtd = "disabled";
            $btn_disabled_aft = "disabled";
            $btn_active = "btn-primary";
        }
        elseif (($date1 == dtimeNow('Y-01-01')) && ($date2 == dtimeNow('Y-m-d'))) {
            $btn_disabled_ytd = "disabled";
            $btn_disabled_aft = "disabled";
        }
        elseif (($date_stop_setelahnya > dtimeNow('Y-m-d'))) {
            $btn_disabled_aft = "disabled";
        }

        /* -----------------------------------------------------------------------------------------------
         * button navigasi bulanan
         * -----------------------------------------------------------------------------------------------*/
        // cekMerah("$date_start_sebelumnya ****** $date_stop_sebelumnya ******* " . date('Y-m-t', strtotime('2022-02-01')));
        // cekHijau($ulr_bulan_sebelum . " **** $str_get **** $str_geters");
        $btn_td = "";
        $btn_td .= "<div class='btn-group'>";
        // $btn_td .= "<button type='button' class='btn btn-danger' onclick=\"location.href='$ulr_bulan_sebelum'\"><i class='fa fa-chevron-circle-left'></i> $nama_bulan_sebelum </button>";
        $btn_td .= "<button type='button' class='btn btn-danger' onclick=\"location.href='$ulr_bulan_sebelum'\"><i class='fa fa-chevron-circle-left'></i></button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-primary' disabled>$nama_bulan_ygtampil <i class='fa fa-chevron-circle-down'></i></button>";
        // $btn_td .= " <button type='button' class='btn btn-danger' $btn_disabled_ytd onclick=\"location . href = '$ulr_tahun_ini'\">YTD</button>";
        // $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' $btn_disabled_aft  onclick=\"location . href = '$ulr_bulan_setelah'\">$nama_bulan_setelah <i class='fa fa-chevron-circle-right '></i></button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' $btn_disabled_aft  onclick=\"location . href = '$ulr_bulan_setelah'\"><i class='fa fa-chevron-circle-right '></i></button>";
        $btn_td .= "</div>";
        $btn_td .= " <button type='button' class='btn $btn_active btn-success' $btn_disabled_mtd onclick=\"location . href = '$ulr_tahun_ini'\">YTD</button>";
        // cekHere(current_url());
        // cekHere($aktivitas_0);
        if (isset($aktivitas_0) && !empty($aktivitas_0)) {
            $btn_disabled_so = "disabled";
            $btn_disabled_pl = "";

            $title_now = "sales order";
        }
        else {
            $btn_disabled_so = "";
            $btn_disabled_pl = "disabled";

            $title_now = "Penjualan";
        }

        // $ulr_tahun_ini = base_url() . "laporan/Penjualan/viewpenjualanbulananper$subjek/so?gr=" . $_GET['gr'];
        // $btn_td .= " <button type='button' $btn_disabled_so class='btn $btn_active btn-primary text-uppercase' onclick=\"location . href = '$ulr_tahun_ini'\">Sales Order</button>";
        // $ulr_tahun_ini = base_url() . "laporan/Penjualan/viewpenjualanbulananper$subjek?gr=" . $_GET['gr'];
        // $btn_td .= " <button type='button' $btn_disabled_pl class='btn $btn_active btn-primary text-uppercase' onclick=\"location . href = '$ulr_tahun_ini'\">packing list</button>";


        $add_td = "<td>$btn_td</td>";
        // $add_td = "";
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
        //callMenuLabel_he_menu()
        $data = array(
            "mode"        => "indek",
            "title"       => "$title_now Bulanan",
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
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            // "sum_null"    => base_url() . "laporan/Crm/maintenance" . "$strGet",
            // "sum_satu"    => base_url() . "laporan/Penjualan/cekpenjualanproduk" . "$strGet",
            "sum_null"    => base_url() . "laporan/Penjualan/cekpenjualanbulankategori$subjek$aktivitas" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Penjualan/cekpenjualantransaksi" . "$strGet",

        );
        $this->load->view("laporan_periode", $data);
    }
    // ---------------

    public function cekpenjualanbulanan()
    {
        // arrPrintHijau(url_segment());
        $subjek_0 = url_segment(4);
        $subjek = isset($subjek_0) ? "/$subjek_0" : "";
        $aktivitas = url_segment(5);
        // $aktivitas = url_segment(5);
        // $title_now = isset($aktivitas) ? "Sales Order" : "Packing List";
        // cekHere("$subjek_0");
        $title_now = isset($aktivitas) ? "Sales Order" : "Penjualan";
        // -----------------------------------------------------------------
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-01-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $this->load->model("Mdls/MdlRawPenjualan");
        $dt = new MdlRawPenjualan();

        $tbl_1= $dt->getTbl1();
        $tbl_2= $dt->getTbl2();
        // $src_00 = $dt->callSummaryProdukBulanan($date1, $date2);
        $condites = array(
            // "fulldate>=" => $date1,
            // "fulldate<=" => $date2,
            "date($tbl_1.dtime)>=" => $date1,
            "date($tbl_1.dtime)<=" => $date2,
            // "produk_id" => "46768",
        );
        $this->db->where($condites);
        if (my_cabang_id() != CB_ID_PUSAT) {
            $this->db->where("cabang_id", my_cabang_id());
        }
        $condite_cust = array(
            "produk_id >=" => "33",
            "produk_id <=" => "50",
        );
        // $this->db->where($condite_cust);
        switch ($subjek_0) {
            case "kategori":

                $src_00 = $dt->callSummaryKategoriProdukBulanan();

                // showLast_query("kuning");
                // matiHere(__LINE__);
                // $src_mtd = $src_00['mtd'];
                // $src_yang_lalu = $src_00['ytd_previous'];
                // $arrRekenings = $src_00['rekening'];
                // $arrTransaksiTipes = $src_00['transaksi_tipe'];
                // cekBiru(sizeof($src_00));
                // arrPrint($src_00);
                // arrPrint(array_slice($src_00,0,3));
                // arrPrint(array_slice($src_00,0,1000));
                // matiHere(__LINE__);
                $src_harians = array();
                $total_hpp = 0;
                $total_penjualan = 0;
                $total_qty = 0;
                foreach ($src_00 as $items) {
                    $thn = $items->thn;
                    $bln = $items->bln;
                    // $tgl = $items->tgl;
                    // $dtime = $items->dtime;
                    $thn_bln = "$thn-$bln";

                    $sum_qty_kredit = $items->sum_qty_kredit;
                    $sum_qty_debet = $items->sum_qty_debet;
                    $sum_kredit = $items->sum_kredit;
                    $sum_debet = $items->sum_debet;
                    $penjualan_nett = $sum_kredit - $sum_debet;
                    $qty_nett = $sum_qty_kredit - $sum_qty_debet;
                    // $penjualan_nett = $sum_kredit;
                    $sum_hpp = $items->sum_hpp;
                    $produk_id = $items->merek_id;
                    // $produk_id = $oleh_id = $items->seller_id;
                    $margin_data = $penjualan_nett > 0 ? ((1 - ($sum_hpp / $penjualan_nett)) * 100) : "0";
                    $ymd = "$thn-$bln";
                    $item_data = (array)$items + array('netto' => $penjualan_nett, 'netto_qty' => $qty_nett);
                    // $item_data['margin'] = 1 - ($sum_hpp / $penjualan_nett);

                    $src_harians[$produk_id][$ymd] = $item_data;
                    $src_margins[$produk_id] = $margin_data;

                    if (!isset($src_qty[$produk_id])) {
                        $src_qty[$produk_id] = 0;
                    }
                    $src_qty[$produk_id] += $sum_qty_kredit;

                    $total_hpp += $sum_hpp;
                    $total_penjualan += $penjualan_nett;
                    $total_qty += $sum_qty_kredit;

                    $subjekYgTerlibats[$produk_id]["id"] = $produk_id;
                    $subjekYgTerlibats[$produk_id]["nama"] = $items->merek_nama;

                    /* -----------------------------------------------------------
                     * sumbulanan
                     * -----------------------------------------------------------*/
                    if (!isset($sumbulanan[$thn_bln])) {
                        $sumbulanan[$thn_bln] = 0;
                    }
                    $sumbulanan[$thn_bln] += $penjualan_nett;
                }
                // $subjekYgTerlibats = array();
            // arrPrintHijau(array_slice($subjekYgTerlibats,1,1));
                // cekBiru($sumbulanan);
                // cekBiru(array_sum($sumbulanan));

                $margin_avg = $total_penjualan > 0 ? ((1 - ($total_hpp / $total_penjualan)) * 100) : 0;
                $total_bawah["penjualan"] = $total_penjualan;
                $total_bawah["hpp"] = $total_hpp;
                $total_bawah["qty"] = $total_qty;
                // cekBiru("$margin_avg = (1 - ($total_hpp / $total_penjualan)) * 100;");
                // arrPrint($src_qty);
                // // arrPrint($src_ytd);
                // // arrPrint($src_mtd);
                // cekBiru(count($src_harians));
                // arrPrintWebs(array_slice($src_harians,0,2));
                // matiHere(__LINE__);

                /* --------------------------------------------------------------------------------
                 * locker stok aktive
                 * --------------------------------------------------------------------------------
                 */
                // $this->load->model("Mdls/MdlLockerStock");
                // $ls = new MdlLockerStock();
                // if (my_cabang_id() != CB_ID_PUSAT) {
                //     $this->db->where("cabang_id", my_cabang_id());
                // }
                // $this->db->select(array(
                //     "produk_id",
                //     "nama",
                //     "sum(jumlah) as 'sum_jumlah'"
                // ));
                // $this->db->group_by("produk_id");
                // $src_ls_00 = $ls->callProdukStok();
                // $src_ls = $src_ls_00['datas'];
                // $src_ls_all = $src_ls_00['all'];
                // // showLast_query("orange");
                // // cekOrange(sizeof($src_ls) . " all $src_ls_all");
                // // arrPrintHijau(array_slice($src_ls, 0, 1));
                // $ls_aktivies = array();
                // foreach ($src_ls as $src_l) {
                //     $ls_produk_id = $src_l->produk_id;
                //
                //     $ls_aktivies[$ls_produk_id] = $src_l;
                // }
                // cekOrange(sizeof($ls_aktivies));

                /* --------------------------------------------------------------------------------
                 * SUBJECT SPEKS
                 * --------------------------------------------------------------------------------
                 */
                $this->load->model("Mdls/MdlProduk");
                $pr = new MdlProduk();
                // $this->db->where("kategori_id", $_GET['kategori_id']);
                $masterHeaders = array(
                    // "kategori_id"   => array(
                    //     "label" => "cid",
                    // ),
                    "nama" => array(
                        "label" => "produk",
                        // "attr"       => "style='width=100px;'",
                        // "links"       => array(
                        //     "heading" => "kategori_nama",
                        //     "key" => "kategori_id",
                        //     "target" => "laporan/Penjualan/cekpenjualanbulanan/produk",
                        // ),
                    ),
                );
                break;
            case "produk":
                $src_00 = $dt->callSummaryProdukBulanan($date1, $date2);
                // showLast_query("kuning");
                // $src_mtd = $src_00['mtd'];
                // $src_yang_lalu = $src_00['ytd_previous'];
                // $arrRekenings = $src_00['rekening'];
                // $arrTransaksiTipes = $src_00['transaksi_tipe'];
                // cekBiru(sizeof($src_00));
                // arrPrint(array_slice($src_00,0,3));
                // arrPrint(array_slice($src_00,0,1000));
                $src_harians = array();
                $total_hpp = 0;
                $total_penjualan = 0;
                $total_qty = 0;
                foreach ($src_00 as $items) {
                    $thn = $items->thn;
                    $bln = $items->bln;
                    // $tgl = $items->tgl;
                    // $dtime = $items->dtime;
                    $thn_bln = "$thn-$bln";

                    $sum_qty_kredit = $items->sum_qty_kredit;
                    $sum_qty_debet = $items->sum_qty_debet;
                    $sum_kredit = $items->sum_kredit;
                    $sum_debet = $items->sum_debet;
                    $penjualan_nett = $sum_kredit - $sum_debet;
                    $qty_nett = $sum_qty_kredit - $sum_qty_debet;
                    // $penjualan_nett = $sum_kredit;
                    $sum_hpp = $items->sum_hpp;
                    $produk_id = $items->produk_id;
                    // $produk_id = $oleh_id = $items->seller_id;
                    $margin_data = $penjualan_nett > 0 ? ((1 - ($sum_hpp / $penjualan_nett)) * 100) : "0";
                    $ymd = "$thn-$bln";
                    $item_data = (array)$items + array('netto' => $penjualan_nett, 'netto_qty' => $qty_nett);
                    // $item_data['margin'] = 1 - ($sum_hpp / $penjualan_nett);

                    $src_harians[$produk_id][$ymd] = $item_data;
                    $src_margins[$produk_id] = $margin_data;

                    if (!isset($src_qty[$produk_id])) {
                        $src_qty[$produk_id] = 0;
                    }
                    $src_qty[$produk_id] += $sum_qty_kredit;

                    $total_hpp += $sum_hpp;
                    $total_penjualan += $penjualan_nett;
                    $total_qty += $sum_qty_kredit;

                    $subjekYgTerlibats[$produk_id]["id"] = $produk_id;
                    $subjekYgTerlibats[$produk_id]["nama"] = $items->produk_nama;

                    /*
                     * sumbulanan
                     * */
                    if (!isset($sumbulanan[$thn_bln])) {
                        $sumbulanan[$thn_bln] = 0;
                    }
                    $sumbulanan[$thn_bln] += $penjualan_nett;
                }
                // $subjekYgTerlibats = array();

                // cekBiru($sumbulanan);
                // cekBiru(array_sum($sumbulanan));

                $margin_avg = (1 - ($total_hpp / $total_penjualan)) * 100;
                $total_bawah["penjualan"] = $total_penjualan;
                $total_bawah["hpp"] = $total_hpp;
                $total_bawah["qty"] = $total_qty;
                // cekBiru("$margin_avg = (1 - ($total_hpp / $total_penjualan)) * 100;");
                // arrPrint($src_qty);
                // // arrPrint($src_ytd);
                // // arrPrint($src_mtd);
                // cekBiru(count($src_harians));
                // arrPrintWebs(array_slice($src_harians,0,2));
                // matiHere(__LINE__);

                /* --------------------------------------------------------------------------------
                 * locker stok aktive
                 * --------------------------------------------------------------------------------
                 */
                // $this->load->model("Mdls/MdlLockerStock");
                // $ls = new MdlLockerStock();
                // if (my_cabang_id() != CB_ID_PUSAT) {
                //     $this->db->where("cabang_id", my_cabang_id());
                // }
                // $this->db->select(array(
                //     "produk_id",
                //     "nama",
                //     "sum(jumlah) as 'sum_jumlah'"
                // ));
                // $this->db->group_by("produk_id");
                // $src_ls_00 = $ls->callProdukStok();
                // $src_ls = $src_ls_00['datas'];
                // $src_ls_all = $src_ls_00['all'];
                // // showLast_query("orange");
                // // cekOrange(sizeof($src_ls) . " all $src_ls_all");
                // // arrPrintHijau(array_slice($src_ls, 0, 1));
                // $ls_aktivies = array();
                // foreach ($src_ls as $src_l) {
                //     $ls_produk_id = $src_l->produk_id;
                //
                //     $ls_aktivies[$ls_produk_id] = $src_l;
                // }
                // cekOrange(sizeof($ls_aktivies));

                /* --------------------------------------------------------------------------------
                 * SUBJECT SPEKS
                 * --------------------------------------------------------------------------------
                 */
                // arrPrintHijau($_GET);
                $this->load->model("Mdls/MdlProduk");
                $pr = new MdlProduk();
                // // if(isset($_GET['kategori_id']) ){
                //     $this->db->where("kategori_id", $_GET['kategori_id']);
                //     $subjekYgTerlibats = array();
                // // }
                $masterHeaders = array(
                    "id"   => array(
                        "label" => "cid",
                    ),
                    "nama" => array(
                        "label" => "produk",
                        // "attr"       => "style='width=100px;'",
                    ),
                    "kode" => array(
                        "label" => "SKU",
                        // "attr"       => "style='width=100px;'",
                    ),
                );
                break;
            case "customer":
                $condite_cust = array(
                    "produk_id >=" => "33",
                    "produk_id <=" => "50",
                );
                // $this->db->where($condite_cust);
                $src_00 = $dt->callSummaryCustomerBulanan($date1, $date2);
                showLast_query("kuning");
                // $src_mtd = $src_00['mtd'];
                // $src_yang_lalu = $src_00['ytd_previous'];
                // $arrRekenings = $src_00['rekening'];
                // $arrTransaksiTipes = $src_00['transaksi_tipe'];
                // cekBiru(sizeof($src_00));
                // arrPrint(array_slice($src_00,0,3));
                // arrPrint(array_slice($src_00,0,1000));
                $src_harians = array();
                $total_hpp = 0;
                $total_penjualan = 0;
                $total_qty = 0;
                foreach ($src_00 as $items) {
                    $thn = $items->thn;
                    $bln = $items->bln;
                    // $tgl = $items->tgl;
                    // $dtime = $items->dtime;
                    $thn_bln = "$thn-$bln";

                    $sum_qty_kredit = $items->sum_qty_kredit;
                    $sum_qty_debet = $items->sum_qty_debet;
                    $sum_kredit = $items->sum_kredit;
                    $sum_debet = $items->sum_debet;
                    $penjualan_nett = $sum_kredit - $sum_debet;
                    $qty_nett = $sum_qty_kredit - $sum_qty_debet;
                    // $penjualan_nett = $sum_kredit;
                    $sum_hpp = $items->sum_hpp;
                    // $produk_id = $items->produk_id;
                    $produk_id = $items->pihak_id;
                    // $produk_id = $oleh_id = $items->seller_id;
                    $margin_data = $penjualan_nett > 0 ? ((1 - ($sum_hpp / $penjualan_nett)) * 100) : "0";
                    $ymd = "$thn-$bln";
                    $item_data = (array)$items + array('netto' => $penjualan_nett, 'netto_qty' => $qty_nett);
                    // $item_data['margin'] = 1 - ($sum_hpp / $penjualan_nett);

                    $src_harians[$produk_id][$ymd] = $item_data;
                    $src_margins[$produk_id] = $margin_data;

                    if (!isset($src_qty[$produk_id])) {
                        $src_qty[$produk_id] = 0;
                    }
                    $src_qty[$produk_id] += $sum_qty_kredit;

                    $total_hpp += $sum_hpp;
                    $total_penjualan += $penjualan_nett;
                    $total_qty += $sum_qty_kredit;

                    $subjekYgTerlibats[$produk_id]["id"] = $produk_id;
                    $subjekYgTerlibats[$produk_id]["nama"] = $items->pihak_nama;

                    /*
                     * sumbulanan
                     * */
                    if (!isset($sumbulanan[$thn_bln])) {
                        $sumbulanan[$thn_bln] = 0;
                    }
                    $sumbulanan[$thn_bln] += $penjualan_nett;
                }

                // cekBiru($sumbulanan);
                // cekBiru(array_sum($sumbulanan));

                $margin_avg = (1 - ($total_hpp / $total_penjualan)) * 100;
                $total_bawah["penjualan"] = $total_penjualan;
                $total_bawah["hpp"] = $total_hpp;
                $total_bawah["qty"] = $total_qty;
                // cekBiru("$margin_avg = (1 - ($total_hpp / $total_penjualan)) * 100;");
                // arrPrint($src_qty);
                // // arrPrint($src_ytd);
                // // arrPrint($src_mtd);
                // cekBiru(count($src_harians));
                // arrPrintWebs(array_slice($src_harians,0,2));
                // matiHere(__LINE__);

                /* --------------------------------------------------------------------------------
                 * SUBJECT SPEKS
                 * --------------------------------------------------------------------------------
                 */
                $this->load->model("Mdls/MdlCustomer");
                $pr = new MdlCustomer();

                $masterHeaders = array(
                    "id"   => array(
                        "label" => "cid",
                    ),
                    "nama" => array(
                        "label" => "Customer",
                        // "attr"       => "style='width=100px;'",
                    ),
                );
                break;
            case "seller":
                if (isset($aktivitas)) {
                    $src_00 = $dt->callSummarySellerSoBulanan($date1, $date2);
                }
                else {
                    $condite_cust = array(
                        "produk_id >=" => "33",
                        "produk_id <=" => "50",
                    );
                    // $this->db->where($condite_cust);

                    $src_00 = $dt->callSummarySellerBulanan($date1, $date2);
                }

                showLast_query("kuning");
                // cekBiru(sizeof($src_00));
                // arrPrint(array_slice($src_00,0,3));
                // arrPrint(array_slice($src_00,0,1000));
                $src_harians = array();
                $total_hpp = 0;
                $total_penjualan = 0;
                $total_qty = 0;
                foreach ($src_00 as $items) {
                    $thn = $items->thn;
                    $bln = $items->bln;
                    // $tgl = $items->tgl;
                    // $dtime = $items->dtime;
                    $thn_bln = "$thn-$bln";

                    $sum_qty_kredit = $items->sum_qty_kredit;
                    $sum_qty_debet = $items->sum_qty_debet;
                    $sum_kredit = $items->sum_kredit;
                    $sum_debet = $items->sum_debet;
                    $penjualan_nett = $sum_kredit - $sum_debet;
                    $qty_nett = $sum_qty_kredit - $sum_qty_debet;
                    // $penjualan_nett = $sum_kredit;
                    $sum_hpp = $items->sum_hpp;
                    // $produk_id = $items->produk_id;
                    $produk_id = $oleh_id = $items->oleh_id;
                    $margin_data = $penjualan_nett > 0 ? ((1 - ($sum_hpp / $penjualan_nett)) * 100) : "0";
                    $ymd = "$thn-$bln";
                    $item_data = (array)$items + array('netto' => $penjualan_nett, 'netto_qty' => $qty_nett);
                    // $item_data['margin'] = 1 - ($sum_hpp / $penjualan_nett);

                    $src_harians[$produk_id][$ymd] = $item_data;
                    $src_margins[$produk_id] = $margin_data;

                    if (!isset($src_qty[$produk_id])) {
                        $src_qty[$produk_id] = 0;
                    }
                    $src_qty[$produk_id] += $sum_qty_kredit;

                    $total_hpp += $sum_hpp;
                    $total_penjualan += $penjualan_nett;
                    $total_qty += $sum_qty_kredit;

                    $subjekYgTerlibats[$oleh_id]["id"] = $oleh_id;
                    $subjekYgTerlibats[$oleh_id]["nama"] = $items->oleh_nama;

                    if (!isset($sumbulanan[$thn_bln])) {
                        $sumbulanan[$thn_bln] = 0;
                    }
                    $sumbulanan[$thn_bln] += $penjualan_nett;
                }

                // cekBiru($sumbulanan);
                // cekBiru(array_sum($sumbulanan));
                // cekBiru($src_harians);
                // cekHere(count($subjekYgTerlibats));
                // matiHere(__LINE__);
                // arrPrint(array_slice($subjekYgTerlibats,0,3));
                $margin_avg = (1 - ($total_hpp / $total_penjualan)) * 100;
                $total_bawah["penjualan"] = $total_penjualan;
                $total_bawah["hpp"] = $total_hpp;
                $total_bawah["qty"] = $total_qty;
                // cekBiru("$margin_avg = (1 - ($total_hpp / $total_penjualan)) * 100;");
                // arrPrint($src_qty);
                // // arrPrint($src_ytd);
                // // arrPrint($src_mtd);
                // cekBiru(count($src_harians));
                // arrPrintWebs($src_harians);
                // matiHere(__LINE__);

                $this->load->model("Mdls/MdlEmployeeCabang");
                $pr = new MdlEmployeeCabang();
                $condite_subj = array(
                    "jenis" => "seller",
                );
                $this->db->where($condite_subj);
                $pr->setFilters(array());
                $subjek_0 = "salesman";
                $masterHeaders = array(
                    "id"   => array(
                        "label" => "pid",
                    ),
                    "nama" => array(
                        "label" => "salesman",
                        // "attr"       => "style='width=100px;'",
                    ),
                    // "stok_locker" => array(
                    //     "label"      => "Qty",
                    //     "format"     => "formatField_he_format",
                    //     "format_key" => "harga",
                    //     "attr"       => "class='text-right'",
                    // ),
                    // "satuan"      => array(
                    //     "label" => "unit",
                    // ),
                );
                break;
            case "cabang":
                $condite_cust = array(
                    "produk_id >=" => "33",
                    "produk_id <=" => "50",
                );
                $this->db->where($condite_cust);
                if (isset($aktivitas)) {
                    $src_00 = $dt->callSummaryCabangSoBulanan($date1, $date2);
                }
                else {
                    $src_00 = $dt->callSummaryCabangBulanan($date1, $date2);
                }
                // showLast_query("kuning");
                // cekBiru(sizeof($src_00));
                // arrPrint(array_slice($src_00,0,3));
                // arrPrint(array_slice($src_00,0,1000));
                $src_harians = array();
                $total_hpp = 0;
                $total_penjualan = 0;
                $total_qty = 0;
                foreach ($src_00 as $items) {
                    $thn = $items->thn;
                    $bln = $items->bln;
                    // $tgl = $items->tgl;
                    // $dtime = $items->dtime;
                    $thn_bln = "$thn-$bln";

                    $sum_qty_kredit = $items->sum_qty_kredit;
                    $sum_qty_debet = $items->sum_qty_debet;
                    $sum_kredit = $items->sum_kredit;
                    $sum_debet = $items->sum_debet;
                    $penjualan_nett = $sum_kredit - $sum_debet;
                    $qty_nett = $sum_qty_kredit - $sum_qty_debet;
                    // $penjualan_nett = $sum_kredit;
                    $sum_hpp = $items->sum_hpp;
                    // $produk_id = $items->produk_id;
                    $produk_id = $oleh_id = $items->cabang_id;
                    $margin_data = $penjualan_nett > 0 ? ((1 - ($sum_hpp / $penjualan_nett)) * 100) : "0";
                    $ymd = "$thn-$bln";
                    $item_data = (array)$items + array('netto' => $penjualan_nett, 'netto_qty' => $qty_nett);
                    // $item_data['margin'] = 1 - ($sum_hpp / $penjualan_nett);

                    $src_harians[$produk_id][$ymd] = $item_data;
                    $src_margins[$produk_id] = $margin_data;

                    if (!isset($src_qty[$produk_id])) {
                        $src_qty[$produk_id] = 0;
                    }
                    $src_qty[$produk_id] += $sum_qty_kredit;

                    $total_hpp += $sum_hpp;
                    $total_penjualan += $penjualan_nett;
                    $total_qty += $sum_qty_kredit;

                    $subjekYgTerlibats[$produk_id]["id"] = $produk_id;
                    $subjekYgTerlibats[$produk_id]["nama"] = $items->cabang_nama;

                    if (!isset($sumbulanan[$thn_bln])) {
                        $sumbulanan[$thn_bln] = 0;
                    }
                    $sumbulanan[$thn_bln] += $penjualan_nett;
                }

                // cekBiru($sumbulanan);
                // cekBiru(array_sum($sumbulanan));
                // cekBiru($src_harians);
                // cekHere(count($subjekYgTerlibats));
                // matiHere(__LINE__);
                // arrPrint(array_slice($subjekYgTerlibats,0,3));
                $margin_avg = (1 - ($total_hpp / $total_penjualan)) * 100;
                $total_bawah["penjualan"] = $total_penjualan;
                $total_bawah["hpp"] = $total_hpp;
                $total_bawah["qty"] = $total_qty;
                // cekBiru("$margin_avg = (1 - ($total_hpp / $total_penjualan)) * 100;");
                // arrPrint($src_qty);
                // // arrPrint($src_ytd);
                // // arrPrint($src_mtd);
                // cekBiru(count($src_harians));
                // arrPrintWebs($src_harians);
                // matiHere(__LINE__);

                $this->load->model("Mdls/MdlCabang");
                $pr = new MdlCabang();
                $condite_subj = array(
                    "id>" => "0",
                );
                $this->db->where($condite_subj);

                // $title_now =
                $masterHeaders = array(
                    "id"   => array(
                        "label" => "pid",
                    ),
                    "nama" => array(
                        "label" => "cabang",
                        // "attr"       => "style='width=100px;'",
                    ),
                    // "stok_locker" => array(
                    //     "label"      => "Qty",
                    //     "format"     => "formatField_he_format",
                    //     "format_key" => "harga",
                    //     "attr"       => "class='text-right'",
                    // ),
                    // "satuan"      => array(
                    //     "label" => "unit",
                    // ),
                );
                break;
            case "tipe":
                $condite_cust = array(
                    "produk_id >=" => "33",
                    "produk_id <=" => "50",
                );
                $this->db->where($condite_cust);
                if (isset($aktivitas)) {
                    // $src_00 = $dt->callSummaryCabangSoBulanan($date1, $date2);
                }
                else {
                    $src_00 = $dt->callSummaryTipePejualanBulanan();
                }
                showLast_query("kuning");
                // cekBiru(sizeof($src_00));
                // arrPrint(array_slice($src_00,0,3));
                // arrPrint(array_slice($src_00,0,1000));
                $src_harians = array();
                $src_margins = array();
                $total_hpp = 0;
                $total_penjualan = 0;
                $total_qty = 0;
                foreach ($src_00 as $items) {
                    $thn = $items->thn;
                    $bln = $items->bln;
                    // $tgl = $items->tgl;
                    // $dtime = $items->dtime;
                    $thn_bln = "$thn-$bln";

                    $sum_qty_kredit = $items->sum_qty_kredit;
                    $sum_qty_debet = $items->sum_qty_debet;
                    $sum_kredit = $items->sum_kredit;
                    $sum_debet = $items->sum_debet;
                    $penjualan_nett = $sum_kredit - $sum_debet;
                    $qty_nett = $sum_qty_kredit - $sum_qty_debet;
                    // $penjualan_nett = $sum_kredit;
                    $sum_hpp = $items->sum_hpp;
                    // $produk_id = $items->produk_id;
                    $produk_id = $oleh_id = $items->tipe_id;
                    $margin_data = $penjualan_nett > 0 ? ((1 - ($sum_hpp / $penjualan_nett)) * 100) : "0";
                    $ymd = "$thn-$bln";
                    $item_data = (array)$items + array('netto' => $penjualan_nett, 'netto_qty' => $qty_nett);
                    // $item_data['margin'] = 1 - ($sum_hpp / $penjualan_nett);

                    $src_harians[$produk_id][$ymd] = $item_data;
                    $src_margins[$produk_id] = $margin_data;

                    if (!isset($src_qty[$produk_id])) {
                        $src_qty[$produk_id] = 0;
                    }
                    $src_qty[$produk_id] += $sum_qty_kredit;

                    $total_hpp += $sum_hpp;
                    $total_penjualan += $penjualan_nett;
                    $total_qty += $sum_qty_kredit;

                    $subjekYgTerlibats[$produk_id]["id"] = $produk_id;
                    $subjekYgTerlibats[$produk_id]["nama"] = $items->tipe_nama;

                    if (!isset($sumbulanan[$thn_bln])) {
                        $sumbulanan[$thn_bln] = 0;
                    }
                    $sumbulanan[$thn_bln] += $penjualan_nett;
                }

                // cekBiru($sumbulanan);
                // cekBiru(array_sum($sumbulanan));
                // cekBiru($src_harians);
                // cekHere(count($subjekYgTerlibats));
                // matiHere(__LINE__);
            // arrPrintHijau($subjekYgTerlibats);
                // arrPrint(array_slice($subjekYgTerlibats,0,3));
                $margin_avg = (1 - ($total_hpp / $total_penjualan)) * 100;
                $total_bawah["penjualan"] = $total_penjualan;
                $total_bawah["hpp"] = $total_hpp;
                $total_bawah["qty"] = $total_qty;
                // cekBiru("$margin_avg = (1 - ($total_hpp / $total_penjualan)) * 100;");
                // arrPrint($src_qty);
                // // arrPrint($src_ytd);
                // // arrPrint($src_mtd);
                // cekBiru(count($src_harians));
                // arrPrintWebs($src_harians);
                // matiHere(__LINE__);

                $this->load->model("Mdls/MdlCabang");
                $pr = new MdlCabang();
                $condite_subj = array(
                    "id>" => "0",
                );
                $this->db->where($condite_subj);

                // $subjek_0 = "tipe penjualan";
                $masterHeaders = array(
                    // "id"   => array(
                    //     "label" => "pid",
                    // ),
                    "nama" => array(
                        "label" => "tipe penjualan",
                        // "attr"       => "style='width=100px;'",
                    ),
                    // "stok_locker" => array(
                    //     "label"      => "Qty",
                    //     "format"     => "formatField_he_format",
                    //     "format_key" => "harga",
                    //     "attr"       => "class='text-right'",
                    // ),
                    // "satuan"      => array(
                    //     "label" => "unit",
                    // ),
                );
                break;
        }

        // $condites_prod = array();
        // $this->db->where($condites_prod);
        if (ipadd() == "202.65.117.72") {
            if (isset($_GET['limit']) && $_GET['limit'] > 0) {
                $this->db->limit($_GET['limit']);
            }
        }

        $src_produks_0 = $pr->callSpecs();
        showLast_query("biru");
        foreach ($src_produks_0 as $p_id => $item) {

            $stok_locker["stok_locker"] = isset($ls_aktivies[$p_id]) ? $ls_aktivies[$p_id]->sum_jumlah : 0;

            /*
             * */
            if($subjek_0 == "kategori"){
                $subj_id = $item->kategori_id;
                $src_produks[$subj_id] = (array)$item + $stok_locker;
            }
            elseif (($subjek_0 == "tipe") || ($subjek_0 == "salesman")){
                $src_produks = array();
            }
            else{
                $src_produks[$p_id] = (array)$item + $stok_locker;
            }
        }
        // cekHijau(count($src_produks));
        // arrPrintHijau($src_produks + $subjekYgTerlibats);
        // arrPrintHijau(array_slice($src_produks, 0, 1));
        // matiHere(__LINE__);
        $arrHeaders = array();


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
            $judul_lap = "YTD ";
            $subTitlelap = dtimeNow('d F Y H:i:s');
        }
        elseif ($date1 == dtimeNow('Y-01-01') && $date2 == dtimeNow('Y-m-d')) {
            $judul_lap = "YTD ";
            $subTitlelap = dtimeNow('d F Y H:i:s');
        }
        else {
            $judul_lap = " " . formatTanggal($get_date1, 'd F Y') . " - " . formatTanggal($get_date2, 'd F Y');
        }

        /* --------------------------------------------------------------------------------
         * sub_header
         * --------------------------------------------------------------------------------*/
        foreach ($src_harians as $prid => $src_harian_dts) {
            foreach ($src_harian_dts as $ymd => $src_harian_dt) {

            }
        }
        $subHeaders = array(
            "sum_kredit" => array(
                "label" => "penjualan"
            ),
            "sum_hpp"    => array(
                "label" => "hpp"
            ),

        );

        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "produk_bulanan_per",
            "title"       => "Laporan $title_now Per $subjek_0 (Year To Date) <small>dalam jutaan</small>",
            "subTitle"    => $subTitlelap,
            "title_now"   => $title_now,
            "judul_lap"   => $judul_lap,
            "modul_path"  => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "5822",
            "data_id"     => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout"      => isset($_GET['layout']) ? $_GET['layout'] : true,
            "master_data" => $src_produks + $subjekYgTerlibats,
            "child_data"  => $src_harians,
            "margin_data" => isset($src_margins) ? $src_margins : array(),
            "margin_avg"  => $margin_avg,
            "qty_data"    => isset($src_qty) ? $src_qty : array(),
            "total_bawah" => $total_bawah,

            "arrHeaders"    => $arrHeaders,
            "masterHeaders" => $masterHeaders,

            // navigasi
            "url"           => base_url(uri_string()) . "$strGet",
            "aktivitas"     => $aktivitas,
            "subjek"        => $subjek_0,
            "strGet"        => $strGet,
            "date1"         => $date1,
            "date2"         => $date2,
            "date_min"      => 1,
            "date_max"      => dtimeNow('Y-m-d'),
            "loader_div"    => "sum_satu",
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        $this->load->view("laporan_periode", $data);
    }

    public function viewpenjualanbulananper()
    {
        $this->load->helper("he_mass_table");
        // arrPrintKuning(url_segment());
        $subjek_0 = url_segment(4);
        $subjek = isset($subjek_0) ? "/$subjek_0" : "";
        $aktivitas_0 = url_segment(5);
        $aktivitas = isset($aktivitas_0) ? "/$aktivitas_0" : "";

        $day_now = dtimeNow('d');
        $date_now = dtimeNow('Y-m-d');
        $year_now = dtimeNow('Y');
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

        $_GET['date1'] = $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-01-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : $_GET['date2'] = dtimeNow('Y-m-d');

        /* -----------------------------------------------------------------------------------------------
       * navigasi bulan sebelumnya
       * -----------------------------------------------------------------------------------------------*/
        //<editor-fold desc="bulan sebelumnya">
        // $date_start_sebelumnya = dtimeNow('Y-m-01');
        $date_start_sebelumnya = previousYear($date2) . "-01-01";
        $date_stop_sebelumnya = date("Y-12-31", strtotime($date_start_sebelumnya));
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
        $date_start_setelahnya = date("Y-01-01", strtotime($date2));
        if (date("Y", strtotime($date_start_setelahnya)) == $year_now) {

            $date_stop_setelahnya = date("Y-m-d", strtotime($date_now));
        }
        else {
            $date_stop_setelahnya = date("Y-12-31", strtotime($date_start_setelahnya));
        }

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
        $nama_bulan_ygtampil = formatTanggal($date2, "Y");
        $ulr_tahun_ini = current_url() . "?date1=" . dtimeNow('Y-01-01') . "&date2=" . dtimeNow('Y-m-d');
        //</editor-fold>

        /* -----------------------------------------------------------------------------------------------
         * logik untuk mematikan butun bulan belum sukses jadi masih dimatikan
         * -----------------------------------------------------------------------------------------------*/
        // cekHijau("$date_now **$date2**  $date_start //// $date_start_setelahnya *** " . dtimeToSecond($date2));
        $btn_disabled = "";
        $btn_disabled_mtd = "";
        $btn_disabled_ytd = "";
        $btn_disabled_aft = "";
        $btn_active = "";
        if (($date1 == dtimeNow('Y-m-01')) && ($date2 == dtimeNow('Y-m-d'))) {
            // cekBiru(dtimeToSecond($date2) ."<br>" . dtimeToSecond($date_start_setelahnya));
            $btn_disabled_mtd = "disabled";
            $btn_disabled_aft = "disabled";
            $btn_active = "btn-primary";
        }
        elseif (($date1 == dtimeNow('Y-01-01')) && ($date2 == dtimeNow('Y-m-d'))) {
            $btn_disabled_ytd = "disabled";
            $btn_disabled_aft = "disabled";
        }
        elseif (($date_stop_setelahnya > dtimeNow('Y-m-d'))) {
            $btn_disabled_aft = "disabled";
        }

        /* -----------------------------------------------------------------------------------------------
         * button navigasi bulanan
         * -----------------------------------------------------------------------------------------------*/
        // cekMerah("$date_start_sebelumnya ****** $date_stop_sebelumnya ******* " . date('Y-m-t', strtotime('2022-02-01')));
        // cekHijau($ulr_bulan_sebelum . " **** $str_get **** $str_geters");
        $btn_td = "";
        $btn_td .= "<div class='btn-group'>";
        // $btn_td .= "<button type='button' class='btn btn-danger' onclick=\"location.href='$ulr_bulan_sebelum'\"><i class='fa fa-chevron-circle-left'></i> $nama_bulan_sebelum </button>";
        $btn_td .= "<button type='button' class='btn btn-danger' onclick=\"location.href='$ulr_bulan_sebelum'\"><i class='fa fa-chevron-circle-left'></i></button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-primary' disabled>$nama_bulan_ygtampil <i class='fa fa-chevron-circle-down'></i></button>";
        // $btn_td .= " <button type='button' class='btn btn-danger' $btn_disabled_ytd onclick=\"location . href = '$ulr_tahun_ini'\">YTD</button>";
        // $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' $btn_disabled_aft  onclick=\"location . href = '$ulr_bulan_setelah'\">$nama_bulan_setelah <i class='fa fa-chevron-circle-right '></i></button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' $btn_disabled_aft  onclick=\"location . href = '$ulr_bulan_setelah'\"><i class='fa fa-chevron-circle-right '></i></button>";
        $btn_td .= "</div>";
        $btn_td .= " <button type='button' class='btn $btn_active btn-success' $btn_disabled_mtd onclick=\"location . href = '$ulr_tahun_ini'\">YTD</button>";
        // cekHere(current_url());
        // cekHere($aktivitas_0);
        if (isset($aktivitas_0) && !empty($aktivitas_0)) {
            $btn_disabled_so = "disabled";
            $btn_disabled_pl = "";

            $title_now = "sales order";
        }
        else {
            $btn_disabled_so = "";
            $btn_disabled_pl = "disabled";

            // $title_now = "Packing list";
            $title_now = "Penjualan";
        }

        // $ulr_tahun_ini = base_url() . "laporan/Penjualan/viewpenjualanbulananper$subjek/so?gr=" . $_GET['gr'];
        // $btn_td .= " <button type='button' $btn_disabled_so class='btn $btn_active btn-primary text-uppercase' onclick=\"location . href = '$ulr_tahun_ini'\">Sales Order</button>";
        // $ulr_tahun_ini = base_url() . "laporan/Penjualan/viewpenjualanbulananper$subjek?gr=" . $_GET['gr'];
        // $btn_td .= " <button type='button' $btn_disabled_pl class='btn $btn_active btn-primary text-uppercase' onclick=\"location . href = '$ulr_tahun_ini'\">packing list</button>";


        $add_td = "<td>$btn_td</td>";
        // $add_td = "";
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
        $url = base_url() . "laporan/Penjualan/cekpenjualanbulanan$subjek$aktivitas" . "$strGet";
        cekHere("$url");
        //callMenuLabel_he_menu()
        $data = array(
            "mode"        => "indek",
            "title"       => "Laporan $title_now Bulanan",
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
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            // "sum_null"    => base_url() . "laporan/Crm/maintenance" . "$strGet",
            // "sum_satu"    => base_url() . "laporan/Penjualan/cekpenjualanproduk" . "$strGet",
            "sum_satu"    => base_url() . "laporan/Penjualan/cekpenjualanbulanan$subjek$aktivitas" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Penjualan/cekpenjualantransaksi" . "$strGet",

        );
        $this->load->view("laporan_periode", $data);
    }
    // ------------------------------------------------------end off-bulanan per-per -------------

    /*mengunakan library data_crm yg hanya ditampilkan kolom penjualan*/
    public function cekpenjualanseller()
    {
        // arrPrint($_GET);
        /* ---------------------------------------------------------------------
         * data outstanding statik per seller akhir tahun 2021
         * ---------------------------------------------------------------------*/
        $arrOutstanding2021 = array(
            // "0"   => array("qty" => "0", "nilai" => "0",),
            // "57"  => array("qty" => "0", "nilai" => "0",),
            // "61"  => array("qty" => "69", "nilai" => "108594099.1",),
            "61"  => array("qty" => "44", "nilai" => "99561369",),
            "65"  => array("qty" => "9", "nilai" => "21598185",),
            "69"  => array("qty" => "111936", "nilai" => "10243373229",),
            "73"  => array("qty" => "14", "nilai" => "31213718.1828",),
            "576" => array("qty" => "189", "nilai" => "449897306.72834",),
            // "576" => array("qty" => "171", "nilai" => "449897306.72834",),
            "664" => array("qty" => "2339", "nilai" => "1361999916.33",),
            "718" => array("qty" => "15", "nilai" => "119260509",),
            // "663" => array("qty" => "-2", "nilai" => "-7178000",),
            // "718" => array("qty" => "0", "nilai" => "0",),
            // "719" => array("qty" => "0", "nilai" => "0",),
            // "808" => array("qty" => "0", "nilai" => "0",),
            // "825" => array("qty" => "0", "nilai" => "0",),
            // "826" => array("qty" => "0", "nilai" => "0",),
            // "712" => array("qty" => "0", "nilai" => "0",),
            // "567" => array("qty" => "189", "nilai" => "0",),
            // // "551" => array("qty" => "0", "nilai" => "0",),
            // "205" => array("qty" => "28", "nilai" => "11886000",),
            // "182" => array("qty" => "-2304", "nilai" => "2252939845",),
            // "77"  => array("qty" => "-2", "nilai" => "-2342344",),

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
        $arrTransaksiTipes = $src_00['transaksi_tipe'];
        // cekBiru(sizeof($src_ytd));
        // arrPrint($src_ytd);
        // arrPrint($src_mtd);

        /* ------------------------------------------------------------------------------------------
         * saat ini
         * ------------------------------------------------------------------------------------------*/
        foreach ($src_mtd as $item) {

            $subjek_id = $item['seller_id'];
            $qty_debet = $item['qty_debet_reguler'];
            $debet = $item['debet_reguler'];
            $qty_debet_rejected = $item['qty_debet_rejected'];
            $debet_rejected = $item['debet_rejected'];
            $qty_kredit_rejected = $item['qty_kredit_rejected'];
            $kredit_rejected = $item['kredit_rejected'];
            $qty_debet_closed = $item['qty_debet_closed'];
            $debet_closed = $item['debet_closed'];
            $qty_debet_batal = $item['qty_debet_batal'];
            $debet_batal = $item['debet_batal'];
            $rekening = $item['rekening'];
            // cekBiru("$rekening");
            $transaksi_tipe = $item['transaksi_tipe'];

            if (!isset($sumSubjek[$subjek_id]['qty_debet_reguler_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_reguler_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_reguler_' . $rekening] += $qty_debet;

            if (!isset($sumSubjek[$subjek_id]['debet_reguler_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_reguler_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_reguler_' . $rekening] += $debet;

            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['qty_debet_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_rejected_' . $rekening] += $qty_debet_rejected;

            if (!isset($sumSubjek[$subjek_id]['debet_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_rejected_' . $rekening] += $debet_rejected;

            if (!isset($sumSubjek[$subjek_id]['qty_kredit_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_kredit_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_kredit_rejected_' . $rekening] += $qty_kredit_rejected;

            if (!isset($sumSubjek[$subjek_id]['kredit_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['kredit_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['kredit_rejected_' . $rekening] += $kredit_rejected;

            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['qty_debet_closed_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_closed_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_closed_' . $rekening] += $qty_debet_closed;

            if (!isset($sumSubjek[$subjek_id]['debet_closed_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_closed_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_closed_' . $rekening] += $debet_closed;

            //---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['qty_debet_batal_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_batal_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_batal_' . $rekening] += $qty_debet_batal;

            if (!isset($sumSubjek[$subjek_id]['debet_batal_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_batal_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_batal_' . $rekening] += $debet_batal;

            //---------------------------------------------------------

            $arrSubjek[$subjek_id] = $item;
            // $arrRekenings[$rekening] = $rekening;
        }

        // arrPrintHijau($sumSubjek);
        // test_table($sumSubjek);
        // mati_disini(__LINE__);

        /* ------------------------------------------------------------------------------------------
         * yang lalu dengan prefik prev
         * ------------------------------------------------------------------------------------------*/
        // $sumSubjek = array();
        foreach ($src_yang_lalu as $item) {

            $subjek_id = $item['seller_id'];
            $qty_debet = $item['qty_debet_reguler'];
            $debet = $item['debet_reguler'];
            $qty_debet_rejected = $item['qty_debet_rejected'];
            $debet_rejected = $item['debet_rejected'];
            $qty_kredit_rejected = $item['qty_kredit_rejected'];
            $kredit_rejected = $item['kredit_rejected'];
            $qty_debet_closed = $item['qty_debet_closed'];
            $debet_closed = $item['debet_closed'];
            $qty_debet_batal = $item['qty_debet_batal'];
            $debet_batal = $item['debet_batal'];
            $rekening = $item['rekening'];

            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_reguler_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_reguler_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_reguler_' . $rekening] += $qty_debet;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_reguler_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_reguler_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_reguler_' . $rekening] += $debet;

            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_rejected_' . $rekening] += $qty_debet_rejected;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_rejected_' . $rekening] += $debet_rejected;

            if (!isset($sumSubjek[$subjek_id]['prev_qty_kredit_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_kredit_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_kredit_rejected_' . $rekening] += $qty_kredit_rejected;

            if (!isset($sumSubjek[$subjek_id]['prev_kredit_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_kredit_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_kredit_rejected_' . $rekening] += $kredit_rejected;

            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_closed_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_closed_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_closed_' . $rekening] += $qty_debet_closed;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_closed_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_closed_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_closed_' . $rekening] += $debet_closed;
            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_batal_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_batal_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_batal_' . $rekening] += $qty_debet_batal;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_batal_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_batal_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_batal_' . $rekening] += $debet_batal;

            //---------------------------------------------------------
            $arrSubjek[$subjek_id] = $item;
            // $arrRekenings[$rekening] = $rekening;
        }
        // arrPrint($sumSubjek);
        // test_table($sumSubjek);

        /* ----------------------------------------------------------------------------------------------------------
         * Rumus san order netto order - kirim - return_kirim
         * dikarekan untuk penilaian performa selesman, dan return tidak menghidupkan so
         * ----------------------------------------------------------------------------------------------------------*/
        // $sumSubjek = array();
        foreach ($sumSubjek as $sbj_id => $sbjDatas) {
            // arrPrintPink($sbjDatas);
            foreach ($arrRekenings as $strekening) {
                foreach ($arrTransaksiTipes as $trtipe) {

                    $caller_prev_qty = "prev_qty_debet_$trtipe" . "_$strekening";
                    $caller_prev_qty_kredit = "prev_qty_kredit_$trtipe" . "_$strekening";
                    $caller_qty = "qty_debet_$trtipe" . "_$strekening";

                    $caller_prev = "prev_debet_$trtipe" . "_$strekening";
                    $caller_prev_kredit = "prev_kredit_$trtipe" . "_$strekening";

                    $caller = "debet_$trtipe" . "_$strekening";
                    $caller_qty_kredit = "qty_kredit_$trtipe" . "_$strekening";
                    $caller_kredit = "kredit_$trtipe" . "_$strekening";

                    $$caller_prev_qty = isset($sbjDatas[$caller_prev_qty]) ? $sbjDatas[$caller_prev_qty] : 0;
                    $$caller_prev = isset($sbjDatas[$caller_prev]) ? $sbjDatas[$caller_prev] : 0;
                    $$caller_prev_qty_kredit = isset($sbjDatas[$caller_prev_qty_kredit]) ? $sbjDatas[$caller_prev_qty_kredit] : 0;
                    $$caller_prev_kredit = isset($sbjDatas[$caller_prev_kredit]) ? $sbjDatas[$caller_prev_kredit] : 0;
                    $$caller_qty = isset($sbjDatas[$caller_qty]) ? $sbjDatas[$caller_qty] : 0;
                    $$caller = isset($sbjDatas[$caller]) ? $sbjDatas[$caller] : 0;
                    $$caller_qty_kredit = isset($sbjDatas[$caller_qty_kredit]) ? $sbjDatas[$caller_qty_kredit] : 0;
                    $$caller_kredit = isset($sbjDatas[$caller_kredit]) ? $sbjDatas[$caller_kredit] : 0;
                }
            }

            $awalOts = isset($arrOutstanding2021[$sbj_id]) ? $arrOutstanding2021[$sbj_id] : 0;
            $alhir2021qty = $awalOts["qty"];
            $alhir2021nilai = $awalOts["nilai"];

            // mati_disini($qty_debet_reguler_582so);
            // -------------------------NEW OUTSTANDING-------------------------------------------------
            //region Description
            $qty_return_order = ($qty_debet_rejected_582so + $qty_debet_closed_582so + $qty_kredit_rejected_582so);
            // cekMerah("$qty_return_order = ($qty_debet_rejected_582so + $qty_debet_closed_582so + $qty_kredit_rejected_582so)");
            $qty_return_kirim = ($qty_debet_rejected_582spd + $qty_debet_closed_582spd);
            $qty_pembatalan_kirim = ($qty_debet_batal_9912);
            $qty_order = ($qty_debet_reguler_582so + $qty_debet_reguler_382so) - $qty_return_order - $qty_return_kirim - $qty_pembatalan_kirim;
            // cekBiru("$qty_order = ($qty_debet_reguler_582so + $qty_debet_reguler_382so) - $qty_return_order - $qty_return_kirim - $qty_pembatalan_kirim;");
            // $qty_kirim = ($qty_debet_reguler_582spd + $qty_debet_reguler_382spd) - $qty_return_kirim;
            $qty_total_kirim = $qty_debet_reguler_582spd + $qty_debet_reguler_382spd;
            $qty_kirim = $qty_total_kirim - $qty_pembatalan_kirim;
            // cekKuning("$qty_kirim = ($qty_debet_reguler_582spd + $qty_debet_reguler_382spd) - $qty_pembatalan_kirim;");
            $qty_outstanding = $qty_order - $qty_kirim;
            // cekBiru("$qty_outstanding = $qty_order - $qty_kirim");
            $qty_outstanding_f = $qty_outstanding < 0 ? 0 : $qty_outstanding;
            if (!isset($outstandingSubjek[$sbj_id]['qty_outstanding'])) {
                $outstandingSubjek[$sbj_id]['qty_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['qty_outstanding'] += $qty_outstanding;
            $sumSubjek[$sbj_id]['qty_order_netto'] = $qty_order;
            $sumSubjek[$sbj_id]['qty_kirim_netto'] = $qty_kirim;
            $sumSubjek[$sbj_id]['qty_kirim_batal'] = $qty_pembatalan_kirim;
            $sumSubjek[$sbj_id]['qty_kirim_total'] = $qty_total_kirim;

            $return_order = ($debet_rejected_582so + $debet_closed_582so + $kredit_rejected_582so);
            $return_kirim = ($debet_rejected_582spd + $debet_closed_582spd);
            $pembatalan_kirim = ($debet_batal_9912);
            $order = ($debet_reguler_582so + $debet_reguler_382so) - $return_order - $return_kirim - $pembatalan_kirim;
            // $kirim = ($debet_reguler_582spd + $debet_reguler_382spd) - $return_kirim;
            $total_kirim = $debet_reguler_582spd + $debet_reguler_382spd;
            $kirim = $total_kirim - $pembatalan_kirim;

            $outstanding = $order - $kirim;
            $outstanding_f = $qty_outstanding_f == 0 ? 0 : $outstanding;
            if (!isset($outstandingSubjek[$sbj_id]['outstanding'])) {
                $outstandingSubjek[$sbj_id]['outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['outstanding'] += $outstanding;
            $sumSubjek[$sbj_id]['order_netto'] = $order;
            $sumSubjek[$sbj_id]['kirim_netto'] = $kirim;
            $sumSubjek[$sbj_id]['kirim_batal'] = $pembatalan_kirim;
            $sumSubjek[$sbj_id]['kirim_total'] = $total_kirim;
            //endregion

            // -------------------------PREVIOUS OUTSTANDING-------------------------------------------------
            $prev_qty_return_order = ($prev_qty_debet_rejected_582so + $prev_qty_debet_closed_582so + $prev_qty_kredit_closed_582so + $prev_qty_kredit_rejected_582so);
            // cekBiru("$prev_qty_return_order = ($prev_qty_debet_rejected_582so + $prev_qty_debet_closed_582so + $prev_qty_kredit_closed_582so);");
            $prev_qty_return_kirim = ($prev_qty_debet_rejected_582spd + $prev_qty_debet_closed_582spd);
            $prev_qty_pembatalan_kirim = ($prev_qty_debet_batal_9912);
            $prev_qty_order = ($prev_qty_debet_reguler_582so + $prev_qty_debet_reguler_382so) - $prev_qty_return_order - $prev_qty_return_kirim - $prev_qty_pembatalan_kirim;
            // cekPink("order $prev_qty_order = ($prev_qty_debet_reguler_582so + $prev_qty_debet_reguler_382so) - $prev_qty_return_order - $prev_qty_return_kirim - $prev_qty_pembatalan_kirim;");
            // $prev_qty_kirim = ($prev_qty_debet_reguler_582spd + $prev_qty_debet_reguler_382spd) - $prev_qty_return_kirim;
            $prev_qty_kirim = ($prev_qty_debet_reguler_582spd + $prev_qty_debet_reguler_382spd) - $prev_qty_pembatalan_kirim;
            // cekBiru("kirim [$sbj_id] $prev_qty_kirim = ($prev_qty_debet_reguler_582spd + $prev_qty_debet_reguler_382spd) - $prev_qty_pembatalan_kirim;");
            $prev_qty_outstanding = $alhir2021qty + ($prev_qty_order - $prev_qty_kirim);
            // cekHijau("ots $prev_qty_outstanding = $alhir2021qty + ($prev_qty_order - $prev_qty_kirim);");
            // $prev_qty_outstanding = ($prev_qty_order - $prev_qty_kirim);
            if (!isset($outstandingSubjek[$sbj_id]['prev_qty_outstanding'])) {
                $outstandingSubjek[$sbj_id]['prev_qty_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['prev_qty_outstanding'] += $prev_qty_outstanding;

            if (!isset($outstandingSubjek[$sbj_id]['prev_qty_kirim'])) {
                $outstandingSubjek[$sbj_id]['prev_qty_kirim'] = 0;
            }
            $outstandingSubjek[$sbj_id]['prev_qty_kirim'] += $prev_qty_kirim;
            // ------------------------------------------------------------------------------------------------
            $prev_return_order = ($prev_debet_rejected_582so + $prev_debet_closed_582so + $prev_kredit_closed_582so + $prev_kredit_rejected_582so);
            $prev_return_kirim = ($prev_debet_rejected_582spd + $prev_debet_closed_582spd);
            $prev_pembatalan_kirim = ($prev_debet_batal_9912);
            $prev_order = ($prev_debet_reguler_582so + $prev_debet_reguler_382so) - $prev_return_order - $prev_return_kirim - $prev_pembatalan_kirim;
            $prev_kirim = ($prev_debet_reguler_582spd + $prev_debet_reguler_382spd) - $prev_pembatalan_kirim;
            // cekPink("kirim [$sbj_id] $prev_kirim = ($prev_debet_reguler_582spd + $prev_debet_reguler_382spd) - $prev_pembatalan_kirim");
            $prev_outstanding = $alhir2021nilai + ($prev_order - $prev_kirim);
            // $prev_outstanding = $alhir2021nilai + (($prev_debet_582so + $prev_debet_382so) - $prev_debet_982) - ($prev_debet_582spd + $prev_debet_382spd);
            if (!isset($outstandingSubjek[$sbj_id]['prev_outstanding'])) {
                $outstandingSubjek[$sbj_id]['prev_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['prev_outstanding'] += $prev_outstanding;

            if (!isset($outstandingSubjek[$sbj_id]['prev_kirim'])) {
                $outstandingSubjek[$sbj_id]['prev_kirim'] = 0;
            }
            $outstandingSubjek[$sbj_id]['prev_kirim'] += $prev_kirim;
        }

        // arrPrintPink($sumSubjek);
        // test_table($sumSubjek);
        // arrPrintPink($sumSubjek);
        // mati_disini(__LINE__);
        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            $sumParams = $sumSubjek[$subj_id];
            // $customer_id = $itemParam['m_customerID'];
            // arrPrintKuning($itemParam);
            //region penyiapan kolom data YTD
            foreach ($arrRekenings as $strekening) {
                foreach ($arrTransaksiTipes as $trtipe) {
                    $caller_prev_qty = "prev_qty_debet_$trtipe" . "_$strekening";
                    $caller_prev_qty_kredit = "prev_qty_kredit_$trtipe" . "_$strekening";
                    $caller_qty = "qty_debet_$trtipe" . "_$strekening";

                    $caller_prev = "prev_debet_$trtipe" . "_$strekening";
                    $caller_prev_kredit = "prev_kredit_$trtipe" . "_$strekening";

                    $caller = "debet_$trtipe" . "_$strekening";
                    $caller_qty_kredit = "qty_kredit_$trtipe" . "_$strekening";
                    $caller_kredit = "kredit_$trtipe" . "_$strekening";

                    $$caller_prev_qty = isset($sumParams[$caller_prev_qty]) ? $sumParams[$caller_prev_qty] : 0;
                    $$caller_prev = isset($sumParams[$caller_prev]) ? $sumParams[$caller_prev] : 0;
                    $$caller_prev_qty_kredit = isset($sumParams[$caller_prev_qty_kredit]) ? $sumParams[$caller_prev_qty_kredit] : 0;
                    $$caller_prev_kredit = isset($sumParams[$caller_prev_kredit]) ? $sumParams[$caller_prev_kredit] : 0;
                    $$caller_qty = isset($sumParams[$caller_qty]) ? $sumParams[$caller_qty] : 0;
                    $$caller = isset($sumParams[$caller]) ? $sumParams[$caller] : 0;
                    $$caller_qty_kredit = isset($sumParams[$caller_qty_kredit]) ? $sumParams[$caller_qty_kredit] : 0;
                    $$caller_kredit = isset($sumParams[$caller_kredit]) ? $sumParams[$caller_kredit] : 0;
                }
            }

            // cekMerah("$qty_debet_batal_9912");

            $qty_return_order_mtd = ($qty_debet_rejected_582so + $qty_debet_closed_582so + $qty_kredit_rejected_582so);
            // cekMerah("[$subj_id] $qty_return_order_mtd_e *** $qty_kredit_rejected_582so");
            $qty_return_kirim_mtd = ($qty_debet_rejected_582spd + $qty_debet_closed_582spd);
            $qty_batal_kirim_mtd = $qty_debet_batal_9912;
            $qty_order_mtd = ($qty_debet_reguler_582so + $qty_debet_reguler_382so) - $qty_return_order_mtd - $qty_return_kirim_mtd - $qty_batal_kirim_mtd;
            // $qty_kirim_mtd = ($qty_debet_reguler_582spd + $qty_debet_reguler_382spd) - $qty_return_kirim_mtd;
            $qty_kirim_mtd = ($qty_debet_reguler_582spd + $qty_debet_reguler_382spd) - $qty_batal_kirim_mtd;

            $qty_mtd = $qty_order_mtd - $qty_kirim_mtd;
            // if($subj_id == "65"){
            //     arrPrintHijau($sumSubjek[$subj_id]);
            //     $qty_kredit_rejected_582so_tes = ($sumSubjek[$subj_id]['qty_kredit_rejected_582so']);
            //     cekHijau("*** $qty_return_order_mtd = ($qty_debet_rejected_582so + $qty_debet_closed_582so + $qty_kredit_rejected_582so || $qty_kredit_rejected_582so_tes)");
            //     cekHijau("$qty_order_mtd = ($qty_debet_reguler_582so + $qty_debet_reguler_382so) - $qty_return_order_mtd - $qty_return_kirim_mtd - $qty_batal_kirim_mtd;");
            //     cekHijau("[$subj_id] ".$outstandingSubjek[$subj_id]["prev_qty_outstanding"]." $qty_mtd = $qty_order_mtd - $qty_kirim_mtd;");
            //     // mati_disini($qty_return_order_mtd_e);
            // }
            $ytd_qty_outstanding = $outstandingSubjek[$subj_id]["prev_qty_outstanding"] + $qty_mtd;
            $ytd_qty_kirim = $outstandingSubjek[$subj_id]["prev_qty_kirim"] + $qty_kirim_mtd;
            // $sub_outstanding["ytd_qty_outstanding"] = $outstandingSubjek[$subj_id]["prev_qty_outstanding"] + $outstandingSubjek[$subj_id]["qty_outstanding"];
            $sub_outstanding["ytd_qty_kirim"] = $ytd_qty_kirim;
            $sub_outstanding["ytd_qty_outstanding"] = $ytd_qty_outstanding;
            // -----------------------------------------------------------------------------------
            $return_order_mtd = ($debet_rejected_582so + $debet_closed_582so + $kredit_rejected_582so);
            $return_kirim_mtd = ($debet_rejected_582spd + $debet_closed_582spd);
            $batal_kirim_mtd = $debet_batal_9912;
            $order_mtd = ($debet_reguler_582so + $debet_reguler_382so) - $return_order_mtd - $return_kirim_mtd - $batal_kirim_mtd;
            // $kirim_mtd = ($debet_reguler_582spd + $debet_reguler_382spd) - $return_kirim_mtd;
            $kirim_mtd = ($debet_reguler_582spd + $debet_reguler_382spd) - $batal_kirim_mtd;

            $mtd = $order_mtd - $kirim_mtd;
            // $mtd = ($mtd_582so + $mtd_382so - $mtd_982) - ($mtd_582spd + $mtd_382spd);
            $ytd_outstanding = $outstandingSubjek[$subj_id]["prev_outstanding"] + $mtd;
            $ytd_kirim = $outstandingSubjek[$subj_id]["prev_kirim"] + $kirim_mtd;

            $sub_outstanding["ytd_penjualan"] = $ytd_kirim;
            $sub_outstanding["ytd_outstanding"] = $ytd_qty_outstanding > 0 ? $ytd_outstanding : 0;
            //endregion

            // $hasilOlahan_1[] = $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
            $hasilOlahan_1[] = $itemParam + $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
            // $hasilOlahan[$customer_id] = $itemParam;
        }
        $masterData = $hasilOlahan_1;
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);

        $arrHeaders = array(
            "seller_id"       => array(
                "label" => "sid",
            ),
            "seller_nama"     => array(
                "label" => "salesman",
            ),
            /*previous rinci*/
            "prev_qty_kirim"  => array(
                "label"      => "previous Sales qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "prev_kirim"      => array(
                "label"      => "previous sales value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            /*prevous outstanding*/
            // "prev_qty_outstanding" => array(
            //     "label"      => "previous outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_outstanding"     => array(
            //     "label"      => "previous outstanding value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*order rincian*/
            // "qty_debet_reguler_582so"      => array(
            //     "label"      => "new order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // // "debet_reguler_582so"          => array(
            // //     "label"      => "new order value",
            // //     "format"     => "formatField_he_format",
            // //     "format_key" => "harga",
            // //     "attr"       => "class='text-right'",
            // //     "summary"    => true,
            // // ),
            // "qty_debet_reguler_382so"      => array(
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
            // "qty_debet_rejected_582so"      => array(
            //     "label"      => "new order reject qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_rejected_582spd"      => array(
            //     "label"      => "new kirim reject qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*order new netto*/
            // "qty_order_netto"      => array(
            //     "label"      => "new netto order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "order_netto"          => array(
            //     "label"      => "new netto order value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*RETURNAN*/
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
            /*kirim rinci*/
            // "qty_debet_reguler_582spd"     => array(
            //     "label"      => "packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_reguler_582spd"         => array(
            //     "label"      => "packing list local value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_reguler_382spd"         => array(
            //     "label"      => "packing list export value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_rejected_582spd"     => array(
            //     "label"      => "packing list balik qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_batal_9912"         => array(
            //     "label"      => "packing list cancel",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*kirim*/
            "qty_kirim_total" => array(
                "label"      => "new sales qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "kirim_total"     => array(
                "label"      => "new sales value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "qty_kirim_batal" => array(
                "label"      => "new canceled sales qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "kirim_batal"     => array(
                "label"      => "new canceled sales value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "qty_kirim_netto" => array(
                "label"      => "new netto sales qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "kirim_netto"     => array(
                "label"      => "new netto sales value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            /*outstanding*/
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
            //     "format_key" => "netto",
            //     "attr"       => "class='text-right'",
            //     "logics"     => array(
            //         "nilai" => "<0",
            //         "text"  => "termasuk pengiriman dari outstanding sebelumnya",
            //     ),
            //     "summary"    => true,
            // ),
            /*last outstanding*/
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
            "ytd_qty_kirim"   => array(
                "label"      => "total sales qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
            ),
            "ytd_penjualan"   => array(
                "label"      => "total sales value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
            ),
            // "seller_id"      => array(
            //     "label"      => "sID",
            // ),
        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        // arrPrintHijau($get_date1);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        $month_now = dtimeNow('Y-m');
        $month_req = formatTanggal($get_date1, 'Y-m');
        // cekHere("$month_req $month_now");
        if ($month_now == $month_req) {
            $judul_lap = "MTD " . dtimeNow('d F Y H:i:s');
        }
        elseif ($date1 == dtimeNow('Y-01-01') && $date2 == dtimeNow('Y-m-d')) {
            $judul_lap = "YTD " . dtimeNow('d F Y H:i');
        }
        else {
            $judul_lap = " " . formatTanggal($get_date1, 'd F Y') . " - " . formatTanggal($get_date2, 'd F Y');
        }

        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung_indek",
            "title"       => "Laporan penjualan Per Salesman " . $judul_lap,
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

    public function cekpenjualantransaksi()
    {
        /* ---------------------------------------------------------------------
         * data outstanding statik per seller akhir tahun 2021
         * ---------------------------------------------------------------------*/
        $arrOutstanding2021 = array(
            // "0"   => array("qty" => "0", "nilai" => "0",),
            // "57"  => array("qty" => "0", "nilai" => "0",),
            // "61"  => array("qty" => "69", "nilai" => "108594099.1",),
            "61"  => array("qty" => "44", "nilai" => "99561369",),
            "65"  => array("qty" => "9", "nilai" => "21598185",),
            "69"  => array("qty" => "111936", "nilai" => "10243373229",),
            "73"  => array("qty" => "14", "nilai" => "31213718.1828",),
            "576" => array("qty" => "189", "nilai" => "449897306.72834",),
            // "576" => array("qty" => "171", "nilai" => "449897306.72834",),
            "664" => array("qty" => "2339", "nilai" => "1361999916.33",),
            "718" => array("qty" => "15", "nilai" => "119260509",),
            // "663" => array("qty" => "-2", "nilai" => "-7178000",),
            // "718" => array("qty" => "0", "nilai" => "0",),
            // "719" => array("qty" => "0", "nilai" => "0",),
            // "808" => array("qty" => "0", "nilai" => "0",),
            // "825" => array("qty" => "0", "nilai" => "0",),
            // "826" => array("qty" => "0", "nilai" => "0",),
            // "712" => array("qty" => "0", "nilai" => "0",),
            // "567" => array("qty" => "189", "nilai" => "0",),
            // // "551" => array("qty" => "0", "nilai" => "0",),
            // "205" => array("qty" => "28", "nilai" => "11886000",),
            // "182" => array("qty" => "-2304", "nilai" => "2252939845",),
            // "77"  => array("qty" => "-2", "nilai" => "-2342344",),

        );
        // -----------------------------------------------------------------
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $this->load->library("laporan/DataCrm");
        $dt = new DataCrm();

        /* -----------------------------------------------------------------
         * filter khusus buat seller, berlaku diluar pusat
         * -----------------------------------------------------------------*/
        if (my_cabang_id() > 0) {
            $dt->setSellerId(my_id());
            // $dt->setMasterId("127365");
        }
        else {
            if (isset($_GET['ky'])) {
                switch ($_GET['ky']) {
                    case "seller_id":
                        $dt->setSellerId($_GET[$_GET['ky']]);
                        break;
                    case "master_id":
                        $dt->setMasterId($_GET[$_GET['ky']]);
                        break;
                }
            }
        }

        $src_00 = $dt->callOrderan($date1, $date2);
        $src_mtd = $src_00['mtd'];
        $src_ytd_pluss = $src_00['ytd_pluss'];
        $src_yang_lalu = $src_00['ytd_previous'];
        $arrRekenings = $src_00['rekening'];
        $arrTransaksiTipes = $src_00['transaksi_tipe'];
        $src_tr = $src_00['transaksi'];
        // cekBiru(sizeof($src_ytd));
        // arrPrint($src_ytd);
        // arrPrint($src_mtd);

        /* ------------------------------------------------------------------------------------------
         * saat ini
         * ------------------------------------------------------------------------------------------*/
        foreach ($src_mtd as $item) {

            $subjek_id = $item['master_id'];
            $transaksi_id = $item['transaksi_id'];
            $qty_debet = $item['qty_debet_reguler'];
            $debet = $item['debet_reguler'];
            $qty_debet_rejected = $item['qty_debet_rejected'];
            $debet_rejected = $item['debet_rejected'];
            $qty_kredit_rejected = $item['qty_kredit_rejected'];
            $kredit_rejected = $item['kredit_rejected'];
            $qty_debet_closed = $item['qty_debet_closed'];
            $debet_closed = $item['debet_closed'];
            $qty_debet_batal = $item['qty_debet_batal'];
            $debet_batal = $item['debet_batal'];
            $rekening = $item['rekening'];
            // cekBiru("$rekening");
            $transaksi_tipe = $item['transaksi_tipe'];
            if (($rekening == "582spd") && ($qty_debet > 0)) {
                // cekKuning("$rekening == \"582so\") && ($qty_debet ** $transaksi_id");
                $trid_582spd[$subjek_id] = $transaksi_id;
            }

            if (!isset($sumSubjek[$subjek_id]['qty_debet_reguler_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_reguler_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_reguler_' . $rekening] += $qty_debet;

            if (!isset($sumSubjek[$subjek_id]['debet_reguler_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_reguler_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_reguler_' . $rekening] += $debet;

            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['qty_debet_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_rejected_' . $rekening] += $qty_debet_rejected;

            if (!isset($sumSubjek[$subjek_id]['debet_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_rejected_' . $rekening] += $debet_rejected;

            if (!isset($sumSubjek[$subjek_id]['qty_kredit_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_kredit_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_kredit_rejected_' . $rekening] += $qty_kredit_rejected;

            if (!isset($sumSubjek[$subjek_id]['kredit_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['kredit_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['kredit_rejected_' . $rekening] += $kredit_rejected;

            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['qty_debet_closed_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_closed_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_closed_' . $rekening] += $qty_debet_closed;

            if (!isset($sumSubjek[$subjek_id]['debet_closed_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_closed_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_closed_' . $rekening] += $debet_closed;

            //---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['qty_debet_batal_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_batal_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_batal_' . $rekening] += $qty_debet_batal;

            if (!isset($sumSubjek[$subjek_id]['debet_batal_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_batal_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_batal_' . $rekening] += $debet_batal;

            //---------------------------------------------------------

            $arrSubjek[$subjek_id] = $item;
            // $arrRekenings[$rekening] = $rekening;
        }

        // arrPrintHijau($sumSubjek);
        // test_table($sumSubjek);
        // mati_disini(__LINE__);

        /* ------------------------------------------------------------------------------------------
         * yang lalu dengan prefik prev
         * ------------------------------------------------------------------------------------------*/
        // $sumSubjek = array();
        foreach ($src_yang_lalu as $item) {

            $subjek_id = $item['master_id'];
            $transaksi_id = $item['transaksi_id'];
            $qty_debet = $item['qty_debet_reguler'];
            $debet = $item['debet_reguler'];
            $qty_debet_rejected = $item['qty_debet_rejected'];
            $debet_rejected = $item['debet_rejected'];
            $qty_kredit_rejected = $item['qty_kredit_rejected'];
            $kredit_rejected = $item['kredit_rejected'];
            $qty_debet_closed = $item['qty_debet_closed'];
            $debet_closed = $item['debet_closed'];
            $qty_debet_batal = $item['qty_debet_batal'];
            $debet_batal = $item['debet_batal'];
            $rekening = $item['rekening'];

            if (($rekening == "582spd") && ($qty_debet > 0)) {
                // cekKuning("$rekening == \"582so\") && ($qty_debet ** $transaksi_id");
                $trid_582spd[$subjek_id] = $transaksi_id;
            }

            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_reguler_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_reguler_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_reguler_' . $rekening] += $qty_debet;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_reguler_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_reguler_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_reguler_' . $rekening] += $debet;

            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_rejected_' . $rekening] += $qty_debet_rejected;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_rejected_' . $rekening] += $debet_rejected;

            if (!isset($sumSubjek[$subjek_id]['prev_qty_kredit_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_kredit_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_kredit_rejected_' . $rekening] += $qty_kredit_rejected;

            if (!isset($sumSubjek[$subjek_id]['prev_kredit_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_kredit_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_kredit_rejected_' . $rekening] += $kredit_rejected;

            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_closed_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_closed_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_closed_' . $rekening] += $qty_debet_closed;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_closed_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_closed_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_closed_' . $rekening] += $debet_closed;
            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_batal_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_batal_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_batal_' . $rekening] += $qty_debet_batal;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_batal_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_batal_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_batal_' . $rekening] += $debet_batal;

            //---------------------------------------------------------
            $arrSubjek[$subjek_id] = $item;
            // $arrRekenings[$rekening] = $rekening;
        }
        // arrPrint($sumSubjek);
        // test_table($sumSubjek);

        /* ----------------------------------------------------------------------------------------------------------
         * Rumus san order netto order - kirim - return_kirim
         * dikarekan untuk penilaian performa selesman, dan return tidak menghidupkan so
         * ----------------------------------------------------------------------------------------------------------*/
        // $sumSubjek = array();
        foreach ($sumSubjek as $sbj_id => $sbjDatas) {
            // arrPrintPink($sbjDatas);
            foreach ($arrRekenings as $strekening) {
                foreach ($arrTransaksiTipes as $trtipe) {

                    $caller_prev_qty = "prev_qty_debet_$trtipe" . "_$strekening";
                    $caller_prev_qty_kredit = "prev_qty_kredit_$trtipe" . "_$strekening";
                    $caller_qty = "qty_debet_$trtipe" . "_$strekening";

                    $caller_prev = "prev_debet_$trtipe" . "_$strekening";
                    $caller_prev_kredit = "prev_kredit_$trtipe" . "_$strekening";

                    $caller = "debet_$trtipe" . "_$strekening";
                    $caller_qty_kredit = "qty_kredit_$trtipe" . "_$strekening";
                    $caller_kredit = "kredit_$trtipe" . "_$strekening";

                    $$caller_prev_qty = isset($sbjDatas[$caller_prev_qty]) ? $sbjDatas[$caller_prev_qty] : 0;
                    $$caller_prev = isset($sbjDatas[$caller_prev]) ? $sbjDatas[$caller_prev] : 0;
                    $$caller_prev_qty_kredit = isset($sbjDatas[$caller_prev_qty_kredit]) ? $sbjDatas[$caller_prev_qty_kredit] : 0;
                    $$caller_prev_kredit = isset($sbjDatas[$caller_prev_kredit]) ? $sbjDatas[$caller_prev_kredit] : 0;
                    $$caller_qty = isset($sbjDatas[$caller_qty]) ? $sbjDatas[$caller_qty] : 0;
                    $$caller = isset($sbjDatas[$caller]) ? $sbjDatas[$caller] : 0;
                    $$caller_qty_kredit = isset($sbjDatas[$caller_qty_kredit]) ? $sbjDatas[$caller_qty_kredit] : 0;
                    $$caller_kredit = isset($sbjDatas[$caller_kredit]) ? $sbjDatas[$caller_kredit] : 0;
                }
            }

            $awalOts = isset($arrOutstanding2021[$sbj_id]) ? $arrOutstanding2021[$sbj_id] : 0;
            $alhir2021qty = $awalOts["qty"];
            $alhir2021nilai = $awalOts["nilai"];

            // mati_disini($qty_debet_reguler_582so);
            // -------------------------NEW OUTSTANDING-------------------------------------------------
            //region Description
            $qty_return_order = ($qty_debet_rejected_582so + $qty_debet_closed_582so + $qty_kredit_rejected_582so);
            // cekMerah("$qty_return_order = ($qty_debet_rejected_582so + $qty_debet_closed_582so + $qty_kredit_rejected_582so)");
            $qty_return_kirim = ($qty_debet_rejected_582spd + $qty_debet_closed_582spd);
            $qty_pembatalan_kirim = ($qty_debet_batal_9912);
            $qty_order = ($qty_debet_reguler_582so + $qty_debet_reguler_382so) - $qty_return_order - $qty_return_kirim - $qty_pembatalan_kirim;
            // cekBiru("order: $qty_order = ($qty_debet_reguler_582so + $qty_debet_reguler_382so) - $qty_return_order - $qty_return_kirim - $qty_pembatalan_kirim;");
            $qty_kirim = ($qty_debet_reguler_582spd + $qty_debet_reguler_382spd) - $qty_return_kirim;
            $qty_total_kirim = $qty_debet_reguler_582spd + $qty_debet_reguler_382spd;
            $qty_kirim = $qty_total_kirim - $qty_pembatalan_kirim;
            // cekKuning("$qty_kirim = ($qty_debet_reguler_582spd + $qty_debet_reguler_382spd) - $qty_pembatalan_kirim;");
            $qty_outstanding = $qty_order - $qty_kirim;
            // cekBiru("$qty_outstanding = $qty_order - $qty_kirim");
            $qty_outstanding_f = $qty_outstanding < 0 ? 0 : $qty_outstanding;
            if (!isset($outstandingSubjek[$sbj_id]['qty_outstanding'])) {
                $outstandingSubjek[$sbj_id]['qty_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['qty_outstanding'] += $qty_outstanding;
            $sumSubjek[$sbj_id]['qty_order_netto'] = $qty_order;
            $sumSubjek[$sbj_id]['qty_kirim_netto'] = $qty_kirim;
            $sumSubjek[$sbj_id]['qty_kirim_batal'] = $qty_pembatalan_kirim;
            $sumSubjek[$sbj_id]['qty_kirim_total'] = $qty_total_kirim;

            $return_order = ($debet_rejected_582so + $debet_closed_582so + $kredit_rejected_582so);
            $return_kirim = ($debet_rejected_582spd + $debet_closed_582spd);
            $pembatalan_kirim = ($debet_batal_9912);
            $order = ($debet_reguler_582so + $debet_reguler_382so) - $return_order - $return_kirim - $pembatalan_kirim;
            // $kirim = ($debet_reguler_582spd + $debet_reguler_382spd) - $return_kirim;
            $total_kirim = $debet_reguler_582spd + $debet_reguler_382spd;
            $kirim = $total_kirim - $pembatalan_kirim;

            $outstanding = $order - $kirim;
            $outstanding_f = $qty_outstanding_f == 0 ? 0 : $outstanding;
            if (!isset($outstandingSubjek[$sbj_id]['outstanding'])) {
                $outstandingSubjek[$sbj_id]['outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['outstanding'] += $outstanding;
            $sumSubjek[$sbj_id]['order_netto'] = $order;
            $sumSubjek[$sbj_id]['kirim_netto'] = $kirim;
            $sumSubjek[$sbj_id]['kirim_batal'] = $pembatalan_kirim;
            $sumSubjek[$sbj_id]['kirim_total'] = $total_kirim;
            //endregion

            // -------------------------PREVIOUS OUTSTANDING-------------------------------------------------
            $prev_qty_return_order = ($prev_qty_debet_rejected_582so + $prev_qty_debet_closed_582so + $prev_qty_kredit_closed_582so + $prev_qty_kredit_rejected_582so);
            // cekBiru("$prev_qty_return_order = ($prev_qty_debet_rejected_582so + $prev_qty_debet_closed_582so + $prev_qty_kredit_closed_582so);");
            $prev_qty_return_kirim = ($prev_qty_debet_rejected_582spd + $prev_qty_debet_closed_582spd);
            $prev_qty_pembatalan_kirim = ($prev_qty_debet_batal_9912);
            $prev_qty_order = ($prev_qty_debet_reguler_582so + $prev_qty_debet_reguler_382so) - $prev_qty_return_order - $prev_qty_return_kirim - $prev_qty_pembatalan_kirim;
            // cekPink("order $prev_qty_order = ($prev_qty_debet_reguler_582so + $prev_qty_debet_reguler_382so) - $prev_qty_return_order - $prev_qty_return_kirim - $prev_qty_pembatalan_kirim;");
            // $prev_qty_kirim = ($prev_qty_debet_reguler_582spd + $prev_qty_debet_reguler_382spd) - $prev_qty_return_kirim;
            $prev_qty_kirim = ($prev_qty_debet_reguler_582spd + $prev_qty_debet_reguler_382spd) - $prev_qty_pembatalan_kirim;
            // cekBiru("kirim [$sbj_id] $prev_qty_kirim = ($prev_qty_debet_reguler_582spd + $prev_qty_debet_reguler_382spd) - $prev_qty_pembatalan_kirim;");
            $prev_qty_outstanding = $alhir2021qty + ($prev_qty_order - $prev_qty_kirim);
            // cekHijau("ots $prev_qty_outstanding = $alhir2021qty + ($prev_qty_order - $prev_qty_kirim);");
            // $prev_qty_outstanding = ($prev_qty_order - $prev_qty_kirim);
            if (!isset($outstandingSubjek[$sbj_id]['prev_qty_outstanding'])) {
                $outstandingSubjek[$sbj_id]['prev_qty_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['prev_qty_outstanding'] += $prev_qty_outstanding;

            if (!isset($outstandingSubjek[$sbj_id]['prev_qty_kirim'])) {
                $outstandingSubjek[$sbj_id]['prev_qty_kirim'] = 0;
            }
            $outstandingSubjek[$sbj_id]['prev_qty_kirim'] += $prev_qty_kirim;
            // ------------------------------------------------------------------------------------------------
            $prev_return_order = ($prev_debet_rejected_582so + $prev_debet_closed_582so + $prev_kredit_closed_582so + $prev_kredit_rejected_582so);
            $prev_return_kirim = ($prev_debet_rejected_582spd + $prev_debet_closed_582spd);
            $prev_pembatalan_kirim = ($prev_debet_batal_9912);
            $prev_order = ($prev_debet_reguler_582so + $prev_debet_reguler_382so) - $prev_return_order - $prev_return_kirim - $prev_pembatalan_kirim;
            $prev_kirim = ($prev_debet_reguler_582spd + $prev_debet_reguler_382spd) - $prev_pembatalan_kirim;
            // cekPink("kirim [$sbj_id] $prev_kirim = ($prev_debet_reguler_582spd + $prev_debet_reguler_382spd) - $prev_pembatalan_kirim");
            $prev_outstanding = $alhir2021nilai + ($prev_order - $prev_kirim);
            // $prev_outstanding = $alhir2021nilai + (($prev_debet_582so + $prev_debet_382so) - $prev_debet_982) - ($prev_debet_582spd + $prev_debet_382spd);
            if (!isset($outstandingSubjek[$sbj_id]['prev_outstanding'])) {
                $outstandingSubjek[$sbj_id]['prev_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['prev_outstanding'] += $prev_outstanding;

            if (!isset($outstandingSubjek[$sbj_id]['prev_kirim'])) {
                $outstandingSubjek[$sbj_id]['prev_kirim'] = 0;
            }
            $outstandingSubjek[$sbj_id]['prev_kirim'] += $prev_kirim;
        }

        // arrPrintPink($sumSubjek);
        // test_table($sumSubjek);
        // arrPrintPink($sumSubjek);
        // mati_disini(__LINE__);
        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            $sumParams = $sumSubjek[$subj_id];
            // $customer_id = $itemParam['m_customerID'];
            // arrPrintKuning($itemParam);
            //region penyiapan kolom data YTD
            foreach ($arrRekenings as $strekening) {
                foreach ($arrTransaksiTipes as $trtipe) {
                    $caller_prev_qty = "prev_qty_debet_$trtipe" . "_$strekening";
                    $caller_prev_qty_kredit = "prev_qty_kredit_$trtipe" . "_$strekening";
                    $caller_qty = "qty_debet_$trtipe" . "_$strekening";

                    $caller_prev = "prev_debet_$trtipe" . "_$strekening";
                    $caller_prev_kredit = "prev_kredit_$trtipe" . "_$strekening";

                    $caller = "debet_$trtipe" . "_$strekening";
                    $caller_qty_kredit = "qty_kredit_$trtipe" . "_$strekening";
                    $caller_kredit = "kredit_$trtipe" . "_$strekening";

                    $$caller_prev_qty = isset($sumParams[$caller_prev_qty]) ? $sumParams[$caller_prev_qty] : 0;
                    $$caller_prev = isset($sumParams[$caller_prev]) ? $sumParams[$caller_prev] : 0;
                    $$caller_prev_qty_kredit = isset($sumParams[$caller_prev_qty_kredit]) ? $sumParams[$caller_prev_qty_kredit] : 0;
                    $$caller_prev_kredit = isset($sumParams[$caller_prev_kredit]) ? $sumParams[$caller_prev_kredit] : 0;
                    $$caller_qty = isset($sumParams[$caller_qty]) ? $sumParams[$caller_qty] : 0;
                    $$caller = isset($sumParams[$caller]) ? $sumParams[$caller] : 0;
                    $$caller_qty_kredit = isset($sumParams[$caller_qty_kredit]) ? $sumParams[$caller_qty_kredit] : 0;
                    $$caller_kredit = isset($sumParams[$caller_kredit]) ? $sumParams[$caller_kredit] : 0;
                }
            }

            // cekMerah("$qty_debet_batal_9912");

            $qty_return_order_mtd = ($qty_debet_rejected_582so + $qty_debet_closed_582so + $qty_kredit_rejected_582so);
            // cekMerah("[$subj_id] $qty_return_order_mtd_e *** $qty_kredit_rejected_582so");
            $qty_return_kirim_mtd = ($qty_debet_rejected_582spd + $qty_debet_closed_582spd);
            $qty_batal_kirim_mtd = $qty_debet_batal_9912;
            $qty_order_mtd = ($qty_debet_reguler_582so + $qty_debet_reguler_382so) - $qty_return_order_mtd - $qty_return_kirim_mtd - $qty_batal_kirim_mtd;
            // $qty_kirim_mtd = ($qty_debet_reguler_582spd + $qty_debet_reguler_382spd) - $qty_return_kirim_mtd;
            $qty_kirim_mtd = ($qty_debet_reguler_582spd + $qty_debet_reguler_382spd) - $qty_batal_kirim_mtd;

            $qty_mtd = $qty_order_mtd - $qty_kirim_mtd;
            // if($subj_id == "65"){
            //     arrPrintHijau($sumSubjek[$subj_id]);
            //     $qty_kredit_rejected_582so_tes = ($sumSubjek[$subj_id]['qty_kredit_rejected_582so']);
            //     cekHijau("*** $qty_return_order_mtd = ($qty_debet_rejected_582so + $qty_debet_closed_582so + $qty_kredit_rejected_582so || $qty_kredit_rejected_582so_tes)");
            //     cekHijau("$qty_order_mtd = ($qty_debet_reguler_582so + $qty_debet_reguler_382so) - $qty_return_order_mtd - $qty_return_kirim_mtd - $qty_batal_kirim_mtd;");
            //     cekHijau("[$subj_id] ".$outstandingSubjek[$subj_id]["prev_qty_outstanding"]." $qty_mtd = $qty_order_mtd - $qty_kirim_mtd;");
            //     // mati_disini($qty_return_order_mtd_e);
            // }
            $ytd_qty_outstanding = $outstandingSubjek[$subj_id]["prev_qty_outstanding"] + $qty_mtd;
            $ytd_qty_kirim = $outstandingSubjek[$subj_id]["prev_qty_kirim"] + $qty_kirim_mtd;
            // $sub_outstanding["ytd_qty_outstanding"] = $outstandingSubjek[$subj_id]["prev_qty_outstanding"] + $outstandingSubjek[$subj_id]["qty_outstanding"];
            $sub_outstanding["ytd_qty_kirim"] = $ytd_qty_kirim;
            $sub_outstanding["ytd_qty_outstanding"] = $ytd_qty_outstanding;
            // -----------------------------------------------------------------------------------
            $return_order_mtd = ($debet_rejected_582so + $debet_closed_582so + $kredit_rejected_582so);
            $return_kirim_mtd = ($debet_rejected_582spd + $debet_closed_582spd);
            $batal_kirim_mtd = $debet_batal_9912;
            $order_mtd = ($debet_reguler_582so + $debet_reguler_382so) - $return_order_mtd - $return_kirim_mtd - $batal_kirim_mtd;
            // $kirim_mtd = ($debet_reguler_582spd + $debet_reguler_382spd) - $return_kirim_mtd;
            $kirim_mtd = ($debet_reguler_582spd + $debet_reguler_382spd) - $batal_kirim_mtd;

            $mtd = $order_mtd - $kirim_mtd;
            // $mtd = ($mtd_582so + $mtd_382so - $mtd_982) - ($mtd_582spd + $mtd_382spd);
            $ytd_outstanding = $outstandingSubjek[$subj_id]["prev_outstanding"] + $mtd;
            $ytd_kirim = $outstandingSubjek[$subj_id]["prev_kirim"] + $kirim_mtd;

            $sub_outstanding["ytd_penjualan"] = $ytd_kirim;
            $sub_outstanding["ytd_outstanding"] = $ytd_qty_outstanding > 0 ? $ytd_outstanding : 0;
            //endregion

            $tr_id = isset($trid_582spd[$subj_id]) ? $trid_582spd[$subj_id] : 0;
            $tr_data = isset($src_tr[$tr_id]) ? $src_tr[$tr_id] : array();
            // $tr_data = isset($src_tr[$subj_id]) ? $src_tr[$subj_id] : array();
            // $hasilOlahan_1[] = $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
            $hasilOlahan_1[] = $itemParam + $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding + $tr_data;
            // $hasilOlahan[$customer_id] = $itemParam;
        }
        $masterData = $hasilOlahan_1;
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);

        $arrHeaders = array(
            "dtime"                                                      => array(
                "label"      => "tanggal",
                "format"     => "formatField_he_format",
                "format_key" => "fulldate",
            ),
            "master_id"                                                  => array(
                "label" => "mid",
            ),
            "_step_1_nomer"                                              => array(
                "label"      => "nomer",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            "cabang_nama"                                                => array(
                "label" => "Cabang",
            ),
            // "seller_id"            => array(
            //     "label" => "sid",
            // ),
            "seller_nama"                                                => array(
                "label" => "salesman",
            ),
            "_company_cabangID_modul_subModul_jenisTr_stepCode_sellerID" => array(
                "label" => "salesman counter",
            ),
            "customer_nama"                                              => array(
                "label" => "customer",
            ),
            /*previous rinci*/
            "prev_qty_kirim"                                             => array(
                "label"      => "previous sales qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "prev_kirim"                                                 => array(
                "label"      => "previous sales value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            /*prevous outstanding*/
            // "prev_qty_outstanding" => array(
            //     "label"      => "previous outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_outstanding"     => array(
            //     "label"      => "previous outstanding value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*order rincian*/
            // "qty_debet_reguler_582so"      => array(
            //     "label"      => "new order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // // "debet_reguler_582so"          => array(
            // //     "label"      => "new order value",
            // //     "format"     => "formatField_he_format",
            // //     "format_key" => "harga",
            // //     "attr"       => "class='text-right'",
            // //     "summary"    => true,
            // // ),
            // "qty_debet_reguler_382so"      => array(
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
            // "qty_debet_rejected_582so"      => array(
            //     "label"      => "new order reject qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_rejected_582spd"      => array(
            //     "label"      => "new kirim reject qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*order new netto*/
            // "qty_order_netto"      => array(
            //     "label"      => "new netto order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "order_netto"          => array(
            //     "label"      => "new netto order value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*RETURNAN*/
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
            /*kirim rinci*/
            // "qty_debet_reguler_582spd"     => array(
            //     "label"      => "packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_reguler_582spd"         => array(
            //     "label"      => "packing list local value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_reguler_382spd"         => array(
            //     "label"      => "packing list export value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_rejected_582spd"     => array(
            //     "label"      => "packing list balik qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_batal_9912"         => array(
            //     "label"      => "packing list cancel",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*kirim*/
            "qty_kirim_total"                                            => array(
                "label"      => "new sales qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "kirim_total"                                                => array(
                "label"      => "new sales value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "qty_kirim_batal"                                            => array(
                "label"      => "new canceled sales qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "kirim_batal"                                                => array(
                "label"      => "new canceled sales value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "qty_kirim_netto"                                            => array(
                "label"      => "new netto sales qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "kirim_netto"                                                => array(
                "label"      => "new netto sales value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            /*outstanding*/
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
            //     "format_key" => "netto",
            //     "attr"       => "class='text-right'",
            //     "logics"     => array(
            //         "nilai" => "<0",
            //         "text"  => "termasuk pengiriman dari outstanding sebelumnya",
            //     ),
            //     "summary"    => true,
            // ),
            /*last outstanding*/
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
            "ytd_qty_kirim"                                              => array(
                "label"      => "total sales qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
            ),
            "ytd_penjualan"                                              => array(
                "label"      => "total sales value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
            ),
            // "seller_id"      => array(
            //     "label"      => "sID",
            // ),
        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        $month_now = dtimeNow('Y-m');
        $month_req = formatTanggal($get_date1, 'Y-m');
        // cekHere("$month_req $month_now");
        if ($month_now == $month_req) {
            $judul_lap = "MTD " . dtimeNow('d F Y H:i:s');
        }
        elseif ($date1 == dtimeNow('Y-01-01') && $date2 == dtimeNow('Y-m-d')) {
            $judul_lap = "YTD " . dtimeNow('d F Y H:i');
        }
        else {
            $judul_lap = " " . formatTanggal($get_date1, 'd F Y') . " - " . formatTanggal($get_date2, 'd F Y');
        }

        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung_indek",
            "title"       => "Laporan Penjualan per Transaksi " . $judul_lap,
            "subTitle"    => "Raw data penjualan",
            "modul_path"  => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "data_id"     => "transaksi_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
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
            "loader_div"  => "sum_dua",
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function cekpenjualancustomer()
    {
        // arrPrint($_GET);
        /* ---------------------------------------------------------------------
         * data outstanding statik per seller akhir tahun 2021
         * ---------------------------------------------------------------------*/
        $arrOutstanding2021 = array(
            // "0"   => array("qty" => "0", "nilai" => "0",),
            // "57"  => array("qty" => "0", "nilai" => "0",),
            // "61"  => array("qty" => "69", "nilai" => "108594099.1",),
            "61"  => array("qty" => "44", "nilai" => "99561369",),
            "65"  => array("qty" => "9", "nilai" => "21598185",),
            "69"  => array("qty" => "111936", "nilai" => "10243373229",),
            "73"  => array("qty" => "14", "nilai" => "31213718.1828",),
            "576" => array("qty" => "189", "nilai" => "449897306.72834",),
            // "576" => array("qty" => "171", "nilai" => "449897306.72834",),
            "664" => array("qty" => "2339", "nilai" => "1361999916.33",),
            "718" => array("qty" => "15", "nilai" => "119260509",),
            // "663" => array("qty" => "-2", "nilai" => "-7178000",),
            // "718" => array("qty" => "0", "nilai" => "0",),
            // "719" => array("qty" => "0", "nilai" => "0",),
            // "808" => array("qty" => "0", "nilai" => "0",),
            // "825" => array("qty" => "0", "nilai" => "0",),
            // "826" => array("qty" => "0", "nilai" => "0",),
            // "712" => array("qty" => "0", "nilai" => "0",),
            // "567" => array("qty" => "189", "nilai" => "0",),
            // // "551" => array("qty" => "0", "nilai" => "0",),
            // "205" => array("qty" => "28", "nilai" => "11886000",),
            // "182" => array("qty" => "-2304", "nilai" => "2252939845",),
            // "77"  => array("qty" => "-2", "nilai" => "-2342344",),

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
        $arrTransaksiTipes = $src_00['transaksi_tipe'];
        // cekBiru(sizeof($src_ytd));
        // arrPrint($src_ytd);
        // arrPrint($src_mtd);

        /* ------------------------------------------------------------------------------------------
         * saat ini
         * ------------------------------------------------------------------------------------------*/
        foreach ($src_mtd as $item) {

            $subjek_id = $item['customer_id'];
            $qty_debet = $item['qty_debet_reguler'];
            $debet = $item['debet_reguler'];
            $qty_debet_rejected = $item['qty_debet_rejected'];
            $debet_rejected = $item['debet_rejected'];
            $qty_kredit_rejected = $item['qty_kredit_rejected'];
            $kredit_rejected = $item['kredit_rejected'];
            $qty_debet_closed = $item['qty_debet_closed'];
            $debet_closed = $item['debet_closed'];
            $qty_debet_batal = $item['qty_debet_batal'];
            $debet_batal = $item['debet_batal'];
            $rekening = $item['rekening'];
            // cekBiru("$rekening");
            $transaksi_tipe = $item['transaksi_tipe'];

            if (!isset($sumSubjek[$subjek_id]['qty_debet_reguler_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_reguler_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_reguler_' . $rekening] += $qty_debet;

            if (!isset($sumSubjek[$subjek_id]['debet_reguler_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_reguler_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_reguler_' . $rekening] += $debet;

            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['qty_debet_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_rejected_' . $rekening] += $qty_debet_rejected;

            if (!isset($sumSubjek[$subjek_id]['debet_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_rejected_' . $rekening] += $debet_rejected;

            if (!isset($sumSubjek[$subjek_id]['qty_kredit_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_kredit_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_kredit_rejected_' . $rekening] += $qty_kredit_rejected;

            if (!isset($sumSubjek[$subjek_id]['kredit_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['kredit_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['kredit_rejected_' . $rekening] += $kredit_rejected;

            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['qty_debet_closed_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_closed_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_closed_' . $rekening] += $qty_debet_closed;

            if (!isset($sumSubjek[$subjek_id]['debet_closed_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_closed_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_closed_' . $rekening] += $debet_closed;

            //---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['qty_debet_batal_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_batal_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_batal_' . $rekening] += $qty_debet_batal;

            if (!isset($sumSubjek[$subjek_id]['debet_batal_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_batal_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_batal_' . $rekening] += $debet_batal;

            //---------------------------------------------------------

            $arrSubjek[$subjek_id] = $item;
            // $arrRekenings[$rekening] = $rekening;
        }

        // arrPrintHijau($sumSubjek);
        // test_table($sumSubjek);
        // mati_disini(__LINE__);

        /* ------------------------------------------------------------------------------------------
         * yang lalu dengan prefik prev
         * ------------------------------------------------------------------------------------------*/
        // $sumSubjek = array();
        foreach ($src_yang_lalu as $item) {

            $subjek_id = $item['customer_id'];
            $qty_debet = $item['qty_debet_reguler'];
            $debet = $item['debet_reguler'];
            $qty_debet_rejected = $item['qty_debet_rejected'];
            $debet_rejected = $item['debet_rejected'];
            $qty_kredit_rejected = $item['qty_kredit_rejected'];
            $kredit_rejected = $item['kredit_rejected'];
            $qty_debet_closed = $item['qty_debet_closed'];
            $debet_closed = $item['debet_closed'];
            $qty_debet_batal = $item['qty_debet_batal'];
            $debet_batal = $item['debet_batal'];
            $rekening = $item['rekening'];

            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_reguler_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_reguler_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_reguler_' . $rekening] += $qty_debet;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_reguler_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_reguler_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_reguler_' . $rekening] += $debet;

            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_rejected_' . $rekening] += $qty_debet_rejected;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_rejected_' . $rekening] += $debet_rejected;

            if (!isset($sumSubjek[$subjek_id]['prev_qty_kredit_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_kredit_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_kredit_rejected_' . $rekening] += $qty_kredit_rejected;

            if (!isset($sumSubjek[$subjek_id]['prev_kredit_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_kredit_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_kredit_rejected_' . $rekening] += $kredit_rejected;

            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_closed_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_closed_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_closed_' . $rekening] += $qty_debet_closed;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_closed_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_closed_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_closed_' . $rekening] += $debet_closed;
            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_batal_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_batal_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_batal_' . $rekening] += $qty_debet_batal;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_batal_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_batal_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_batal_' . $rekening] += $debet_batal;

            //---------------------------------------------------------
            $arrSubjek[$subjek_id] = $item;
            // $arrRekenings[$rekening] = $rekening;
        }
        // arrPrint($sumSubjek);
        // test_table($sumSubjek);

        /* ----------------------------------------------------------------------------------------------------------
         * Rumus san order netto order - kirim - return_kirim
         * dikarekan untuk penilaian performa selesman, dan return tidak menghidupkan so
         * ----------------------------------------------------------------------------------------------------------*/
        // $sumSubjek = array();
        foreach ($sumSubjek as $sbj_id => $sbjDatas) {
            // arrPrintPink($sbjDatas);
            foreach ($arrRekenings as $strekening) {
                foreach ($arrTransaksiTipes as $trtipe) {

                    $caller_prev_qty = "prev_qty_debet_$trtipe" . "_$strekening";
                    $caller_prev_qty_kredit = "prev_qty_kredit_$trtipe" . "_$strekening";
                    $caller_qty = "qty_debet_$trtipe" . "_$strekening";

                    $caller_prev = "prev_debet_$trtipe" . "_$strekening";
                    $caller_prev_kredit = "prev_kredit_$trtipe" . "_$strekening";

                    $caller = "debet_$trtipe" . "_$strekening";
                    $caller_qty_kredit = "qty_kredit_$trtipe" . "_$strekening";
                    $caller_kredit = "kredit_$trtipe" . "_$strekening";

                    $$caller_prev_qty = isset($sbjDatas[$caller_prev_qty]) ? $sbjDatas[$caller_prev_qty] : 0;
                    $$caller_prev = isset($sbjDatas[$caller_prev]) ? $sbjDatas[$caller_prev] : 0;
                    $$caller_prev_qty_kredit = isset($sbjDatas[$caller_prev_qty_kredit]) ? $sbjDatas[$caller_prev_qty_kredit] : 0;
                    $$caller_prev_kredit = isset($sbjDatas[$caller_prev_kredit]) ? $sbjDatas[$caller_prev_kredit] : 0;
                    $$caller_qty = isset($sbjDatas[$caller_qty]) ? $sbjDatas[$caller_qty] : 0;
                    $$caller = isset($sbjDatas[$caller]) ? $sbjDatas[$caller] : 0;
                    $$caller_qty_kredit = isset($sbjDatas[$caller_qty_kredit]) ? $sbjDatas[$caller_qty_kredit] : 0;
                    $$caller_kredit = isset($sbjDatas[$caller_kredit]) ? $sbjDatas[$caller_kredit] : 0;
                }
            }

            $awalOts = isset($arrOutstanding2021[$sbj_id]) ? $arrOutstanding2021[$sbj_id] : 0;
            $alhir2021qty = $awalOts["qty"];
            $alhir2021nilai = $awalOts["nilai"];

            // mati_disini($qty_debet_reguler_582so);
            // -------------------------NEW OUTSTANDING-------------------------------------------------
            //region Description
            $qty_return_order = ($qty_debet_rejected_582so + $qty_debet_closed_582so + $qty_kredit_rejected_582so);
            // cekMerah("$qty_return_order = ($qty_debet_rejected_582so + $qty_debet_closed_582so + $qty_kredit_rejected_582so)");
            $qty_return_kirim = ($qty_debet_rejected_582spd + $qty_debet_closed_582spd);
            $qty_pembatalan_kirim = ($qty_debet_batal_9912);
            $qty_order = ($qty_debet_reguler_582so + $qty_debet_reguler_382so) - $qty_return_order - $qty_return_kirim - $qty_pembatalan_kirim;
            // cekBiru("$qty_order = ($qty_debet_reguler_582so + $qty_debet_reguler_382so) - $qty_return_order - $qty_return_kirim - $qty_pembatalan_kirim;");
            // $qty_kirim = ($qty_debet_reguler_582spd + $qty_debet_reguler_382spd) - $qty_return_kirim;
            $qty_total_kirim = $qty_debet_reguler_582spd + $qty_debet_reguler_382spd;
            $qty_kirim = $qty_total_kirim - $qty_pembatalan_kirim;
            // cekKuning("$qty_kirim = ($qty_debet_reguler_582spd + $qty_debet_reguler_382spd) - $qty_pembatalan_kirim;");
            $qty_outstanding = $qty_order - $qty_kirim;
            // cekBiru("$qty_outstanding = $qty_order - $qty_kirim");
            $qty_outstanding_f = $qty_outstanding < 0 ? 0 : $qty_outstanding;
            if (!isset($outstandingSubjek[$sbj_id]['qty_outstanding'])) {
                $outstandingSubjek[$sbj_id]['qty_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['qty_outstanding'] += $qty_outstanding;
            $sumSubjek[$sbj_id]['qty_order_netto'] = $qty_order;
            $sumSubjek[$sbj_id]['qty_kirim_netto'] = $qty_kirim;
            $sumSubjek[$sbj_id]['qty_kirim_batal'] = $qty_pembatalan_kirim;
            $sumSubjek[$sbj_id]['qty_kirim_total'] = $qty_total_kirim;

            $return_order = ($debet_rejected_582so + $debet_closed_582so + $kredit_rejected_582so);
            $return_kirim = ($debet_rejected_582spd + $debet_closed_582spd);
            $pembatalan_kirim = ($debet_batal_9912);
            $order = ($debet_reguler_582so + $debet_reguler_382so) - $return_order - $return_kirim - $pembatalan_kirim;
            // $kirim = ($debet_reguler_582spd + $debet_reguler_382spd) - $return_kirim;
            $total_kirim = $debet_reguler_582spd + $debet_reguler_382spd;
            $kirim = $total_kirim - $pembatalan_kirim;

            $outstanding = $order - $kirim;
            $outstanding_f = $qty_outstanding_f == 0 ? 0 : $outstanding;
            if (!isset($outstandingSubjek[$sbj_id]['outstanding'])) {
                $outstandingSubjek[$sbj_id]['outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['outstanding'] += $outstanding;
            $sumSubjek[$sbj_id]['order_netto'] = $order;
            $sumSubjek[$sbj_id]['kirim_netto'] = $kirim;
            $sumSubjek[$sbj_id]['kirim_batal'] = $pembatalan_kirim;
            $sumSubjek[$sbj_id]['kirim_total'] = $total_kirim;
            //endregion

            // -------------------------PREVIOUS OUTSTANDING-------------------------------------------------
            $prev_qty_return_order = ($prev_qty_debet_rejected_582so + $prev_qty_debet_closed_582so + $prev_qty_kredit_closed_582so + $prev_qty_kredit_rejected_582so);
            // cekBiru("$prev_qty_return_order = ($prev_qty_debet_rejected_582so + $prev_qty_debet_closed_582so + $prev_qty_kredit_closed_582so);");
            $prev_qty_return_kirim = ($prev_qty_debet_rejected_582spd + $prev_qty_debet_closed_582spd);
            $prev_qty_pembatalan_kirim = ($prev_qty_debet_batal_9912);
            $prev_qty_order = ($prev_qty_debet_reguler_582so + $prev_qty_debet_reguler_382so) - $prev_qty_return_order - $prev_qty_return_kirim - $prev_qty_pembatalan_kirim;
            // cekPink("order $prev_qty_order = ($prev_qty_debet_reguler_582so + $prev_qty_debet_reguler_382so) - $prev_qty_return_order - $prev_qty_return_kirim - $prev_qty_pembatalan_kirim;");
            // $prev_qty_kirim = ($prev_qty_debet_reguler_582spd + $prev_qty_debet_reguler_382spd) - $prev_qty_return_kirim;
            $prev_qty_kirim = ($prev_qty_debet_reguler_582spd + $prev_qty_debet_reguler_382spd) - $prev_qty_pembatalan_kirim;
            // cekBiru("kirim [$sbj_id] $prev_qty_kirim = ($prev_qty_debet_reguler_582spd + $prev_qty_debet_reguler_382spd) - $prev_qty_pembatalan_kirim;");
            $prev_qty_outstanding = $alhir2021qty + ($prev_qty_order - $prev_qty_kirim);
            // cekHijau("ots $prev_qty_outstanding = $alhir2021qty + ($prev_qty_order - $prev_qty_kirim);");
            // $prev_qty_outstanding = ($prev_qty_order - $prev_qty_kirim);
            if (!isset($outstandingSubjek[$sbj_id]['prev_qty_outstanding'])) {
                $outstandingSubjek[$sbj_id]['prev_qty_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['prev_qty_outstanding'] += $prev_qty_outstanding;

            if (!isset($outstandingSubjek[$sbj_id]['prev_qty_kirim'])) {
                $outstandingSubjek[$sbj_id]['prev_qty_kirim'] = 0;
            }
            $outstandingSubjek[$sbj_id]['prev_qty_kirim'] += $prev_qty_kirim;
            // ------------------------------------------------------------------------------------------------
            $prev_return_order = ($prev_debet_rejected_582so + $prev_debet_closed_582so + $prev_kredit_closed_582so + $prev_kredit_rejected_582so);
            $prev_return_kirim = ($prev_debet_rejected_582spd + $prev_debet_closed_582spd);
            $prev_pembatalan_kirim = ($prev_debet_batal_9912);
            $prev_order = ($prev_debet_reguler_582so + $prev_debet_reguler_382so) - $prev_return_order - $prev_return_kirim - $prev_pembatalan_kirim;
            $prev_kirim = ($prev_debet_reguler_582spd + $prev_debet_reguler_382spd) - $prev_pembatalan_kirim;
            // cekPink("kirim [$sbj_id] $prev_kirim = ($prev_debet_reguler_582spd + $prev_debet_reguler_382spd) - $prev_pembatalan_kirim");
            $prev_outstanding = $alhir2021nilai + ($prev_order - $prev_kirim);
            // $prev_outstanding = $alhir2021nilai + (($prev_debet_582so + $prev_debet_382so) - $prev_debet_982) - ($prev_debet_582spd + $prev_debet_382spd);
            if (!isset($outstandingSubjek[$sbj_id]['prev_outstanding'])) {
                $outstandingSubjek[$sbj_id]['prev_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['prev_outstanding'] += $prev_outstanding;

            if (!isset($outstandingSubjek[$sbj_id]['prev_kirim'])) {
                $outstandingSubjek[$sbj_id]['prev_kirim'] = 0;
            }
            $outstandingSubjek[$sbj_id]['prev_kirim'] += $prev_kirim;
        }

        // arrPrintPink($sumSubjek);
        // test_table($sumSubjek);
        // arrPrintPink($sumSubjek);
        // mati_disini(__LINE__);
        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            $sumParams = $sumSubjek[$subj_id];
            // $customer_id = $itemParam['m_customerID'];
            // arrPrintKuning($itemParam);
            //region penyiapan kolom data YTD
            foreach ($arrRekenings as $strekening) {
                foreach ($arrTransaksiTipes as $trtipe) {
                    $caller_prev_qty = "prev_qty_debet_$trtipe" . "_$strekening";
                    $caller_prev_qty_kredit = "prev_qty_kredit_$trtipe" . "_$strekening";
                    $caller_qty = "qty_debet_$trtipe" . "_$strekening";

                    $caller_prev = "prev_debet_$trtipe" . "_$strekening";
                    $caller_prev_kredit = "prev_kredit_$trtipe" . "_$strekening";

                    $caller = "debet_$trtipe" . "_$strekening";
                    $caller_qty_kredit = "qty_kredit_$trtipe" . "_$strekening";
                    $caller_kredit = "kredit_$trtipe" . "_$strekening";

                    $$caller_prev_qty = isset($sumParams[$caller_prev_qty]) ? $sumParams[$caller_prev_qty] : 0;
                    $$caller_prev = isset($sumParams[$caller_prev]) ? $sumParams[$caller_prev] : 0;
                    $$caller_prev_qty_kredit = isset($sumParams[$caller_prev_qty_kredit]) ? $sumParams[$caller_prev_qty_kredit] : 0;
                    $$caller_prev_kredit = isset($sumParams[$caller_prev_kredit]) ? $sumParams[$caller_prev_kredit] : 0;
                    $$caller_qty = isset($sumParams[$caller_qty]) ? $sumParams[$caller_qty] : 0;
                    $$caller = isset($sumParams[$caller]) ? $sumParams[$caller] : 0;
                    $$caller_qty_kredit = isset($sumParams[$caller_qty_kredit]) ? $sumParams[$caller_qty_kredit] : 0;
                    $$caller_kredit = isset($sumParams[$caller_kredit]) ? $sumParams[$caller_kredit] : 0;
                }
            }

            // cekMerah("$qty_debet_batal_9912");

            $qty_return_order_mtd = ($qty_debet_rejected_582so + $qty_debet_closed_582so + $qty_kredit_rejected_582so);
            // cekMerah("[$subj_id] $qty_return_order_mtd_e *** $qty_kredit_rejected_582so");
            $qty_return_kirim_mtd = ($qty_debet_rejected_582spd + $qty_debet_closed_582spd);
            $qty_batal_kirim_mtd = $qty_debet_batal_9912;
            $qty_order_mtd = ($qty_debet_reguler_582so + $qty_debet_reguler_382so) - $qty_return_order_mtd - $qty_return_kirim_mtd - $qty_batal_kirim_mtd;
            // $qty_kirim_mtd = ($qty_debet_reguler_582spd + $qty_debet_reguler_382spd) - $qty_return_kirim_mtd;
            $qty_kirim_mtd = ($qty_debet_reguler_582spd + $qty_debet_reguler_382spd) - $qty_batal_kirim_mtd;

            $qty_mtd = $qty_order_mtd - $qty_kirim_mtd;
            // if($subj_id == "65"){
            //     arrPrintHijau($sumSubjek[$subj_id]);
            //     $qty_kredit_rejected_582so_tes = ($sumSubjek[$subj_id]['qty_kredit_rejected_582so']);
            //     cekHijau("*** $qty_return_order_mtd = ($qty_debet_rejected_582so + $qty_debet_closed_582so + $qty_kredit_rejected_582so || $qty_kredit_rejected_582so_tes)");
            //     cekHijau("$qty_order_mtd = ($qty_debet_reguler_582so + $qty_debet_reguler_382so) - $qty_return_order_mtd - $qty_return_kirim_mtd - $qty_batal_kirim_mtd;");
            //     cekHijau("[$subj_id] ".$outstandingSubjek[$subj_id]["prev_qty_outstanding"]." $qty_mtd = $qty_order_mtd - $qty_kirim_mtd;");
            //     // mati_disini($qty_return_order_mtd_e);
            // }
            $ytd_qty_outstanding = $outstandingSubjek[$subj_id]["prev_qty_outstanding"] + $qty_mtd;
            $ytd_qty_kirim = $outstandingSubjek[$subj_id]["prev_qty_kirim"] + $qty_kirim_mtd;
            // $sub_outstanding["ytd_qty_outstanding"] = $outstandingSubjek[$subj_id]["prev_qty_outstanding"] + $outstandingSubjek[$subj_id]["qty_outstanding"];
            $sub_outstanding["ytd_qty_kirim"] = $ytd_qty_kirim;
            $sub_outstanding["ytd_qty_outstanding"] = $ytd_qty_outstanding;
            // -----------------------------------------------------------------------------------
            $return_order_mtd = ($debet_rejected_582so + $debet_closed_582so + $kredit_rejected_582so);
            $return_kirim_mtd = ($debet_rejected_582spd + $debet_closed_582spd);
            $batal_kirim_mtd = $debet_batal_9912;
            $order_mtd = ($debet_reguler_582so + $debet_reguler_382so) - $return_order_mtd - $return_kirim_mtd - $batal_kirim_mtd;
            // $kirim_mtd = ($debet_reguler_582spd + $debet_reguler_382spd) - $return_kirim_mtd;
            $kirim_mtd = ($debet_reguler_582spd + $debet_reguler_382spd) - $batal_kirim_mtd;

            $mtd = $order_mtd - $kirim_mtd;
            // $mtd = ($mtd_582so + $mtd_382so - $mtd_982) - ($mtd_582spd + $mtd_382spd);
            $ytd_outstanding = $outstandingSubjek[$subj_id]["prev_outstanding"] + $mtd;
            $ytd_kirim = $outstandingSubjek[$subj_id]["prev_kirim"] + $kirim_mtd;

            $sub_outstanding["ytd_penjualan"] = $ytd_kirim;
            $sub_outstanding["ytd_outstanding"] = $ytd_qty_outstanding > 0 ? $ytd_outstanding : 0;
            //endregion

            // $hasilOlahan_1[] = $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
            $hasilOlahan_1[] = $itemParam + $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
            // $hasilOlahan[$customer_id] = $itemParam;
        }
        $masterData = $hasilOlahan_1;
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);

        $arrHeaders = array(
            "customer_id"     => array(
                "label" => "cid",
            ),
            "customer_nama"   => array(
                "label" => "konsumen",
            ),
            "cabang_nama"     => array(
                "label" => "Cabang",
            ),
            /*previous rinci*/
            "prev_qty_kirim"  => array(
                "label"      => "previous Sales qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "prev_kirim"      => array(
                "label"      => "previous sales value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            /*prevous outstanding*/
            // "prev_qty_outstanding" => array(
            //     "label"      => "previous outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_outstanding"     => array(
            //     "label"      => "previous outstanding value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*order rincian*/
            // "qty_debet_reguler_582so"      => array(
            //     "label"      => "new order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // // "debet_reguler_582so"          => array(
            // //     "label"      => "new order value",
            // //     "format"     => "formatField_he_format",
            // //     "format_key" => "harga",
            // //     "attr"       => "class='text-right'",
            // //     "summary"    => true,
            // // ),
            // "qty_debet_reguler_382so"      => array(
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
            // "qty_debet_rejected_582so"      => array(
            //     "label"      => "new order reject qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_rejected_582spd"      => array(
            //     "label"      => "new kirim reject qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*order new netto*/
            // "qty_order_netto"      => array(
            //     "label"      => "new netto order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "order_netto"          => array(
            //     "label"      => "new netto order value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*RETURNAN*/
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
            /*kirim rinci*/
            // "qty_debet_reguler_582spd"     => array(
            //     "label"      => "packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_reguler_582spd"         => array(
            //     "label"      => "packing list local value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_reguler_382spd"         => array(
            //     "label"      => "packing list export value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_rejected_582spd"     => array(
            //     "label"      => "packing list balik qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_batal_9912"         => array(
            //     "label"      => "packing list cancel",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*kirim*/
            "qty_kirim_total" => array(
                "label"      => "new sales qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "kirim_total"     => array(
                "label"      => "new sales value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "qty_kirim_batal" => array(
                "label"      => "new canceled sales qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "kirim_batal"     => array(
                "label"      => "new canceled sales value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "qty_kirim_netto" => array(
                "label"      => "new netto sales qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "kirim_netto"     => array(
                "label"      => "new netto sales value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            /*outstanding*/
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
            //     "format_key" => "netto",
            //     "attr"       => "class='text-right'",
            //     "logics"     => array(
            //         "nilai" => "<0",
            //         "text"  => "termasuk pengiriman dari outstanding sebelumnya",
            //     ),
            //     "summary"    => true,
            // ),
            /*last outstanding*/
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
            "ytd_qty_kirim"   => array(
                "label"      => "total sales qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
            ),
            "ytd_penjualan"   => array(
                "label"      => "total sales value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
            ),
            // "seller_id"      => array(
            //     "label"      => "sID",
            // ),
        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        // arrPrintHijau($get_date1);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        $month_now = dtimeNow('Y-m');
        $month_req = formatTanggal($get_date1, 'Y-m');
        // cekHere("$month_req $month_now");
        if ($month_now == $month_req) {
            $judul_lap = "MTD " . dtimeNow('d F Y H:i:s');
        }
        elseif ($date1 == dtimeNow('Y-01-01') && $date2 == dtimeNow('Y-m-d')) {
            $judul_lap = "YTD " . dtimeNow('d F Y H:i');
        }
        else {
            $judul_lap = " " . formatTanggal($get_date1, 'd F Y') . " - " . formatTanggal($get_date2, 'd F Y');
        }

        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung_indek",
            "title"       => "Laporan penjualan per konsumen " . $judul_lap,
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "data_id"     => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(4),
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

    public function cekpenjualanproduk()
    {
        // arrPrint($_GET);
        /* ---------------------------------------------------------------------
         * data outstanding statik per seller akhir tahun 2021
         * ---------------------------------------------------------------------*/
        $arrOutstanding2021 = array(
            // "0"   => array("qty" => "0", "nilai" => "0",),
            // "57"  => array("qty" => "0", "nilai" => "0",),
            // "61"  => array("qty" => "69", "nilai" => "108594099.1",),
            "61"  => array("qty" => "44", "nilai" => "99561369",),
            "65"  => array("qty" => "9", "nilai" => "21598185",),
            "69"  => array("qty" => "111936", "nilai" => "10243373229",),
            "73"  => array("qty" => "14", "nilai" => "31213718.1828",),
            "576" => array("qty" => "189", "nilai" => "449897306.72834",),
            // "576" => array("qty" => "171", "nilai" => "449897306.72834",),
            "664" => array("qty" => "2339", "nilai" => "1361999916.33",),
            "718" => array("qty" => "15", "nilai" => "119260509",),
            // "663" => array("qty" => "-2", "nilai" => "-7178000",),
            // "718" => array("qty" => "0", "nilai" => "0",),
            // "719" => array("qty" => "0", "nilai" => "0",),
            // "808" => array("qty" => "0", "nilai" => "0",),
            // "825" => array("qty" => "0", "nilai" => "0",),
            // "826" => array("qty" => "0", "nilai" => "0",),
            // "712" => array("qty" => "0", "nilai" => "0",),
            // "567" => array("qty" => "189", "nilai" => "0",),
            // // "551" => array("qty" => "0", "nilai" => "0",),
            // "205" => array("qty" => "28", "nilai" => "11886000",),
            // "182" => array("qty" => "-2304", "nilai" => "2252939845",),
            // "77"  => array("qty" => "-2", "nilai" => "-2342344",),

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
        $arrTransaksiTipes = $src_00['transaksi_tipe'];
        // cekBiru(sizeof($src_ytd));
        // arrPrint($src_ytd);
        // arrPrint($src_mtd);

        /* ------------------------------------------------------------------------------------------
         * saat ini
         * ------------------------------------------------------------------------------------------*/
        foreach ($src_mtd as $item) {

            $subjek_id = $item['extern_id'];
            $qty_debet = $item['qty_debet_reguler'];
            $debet = $item['debet_reguler'];
            $qty_debet_rejected = $item['qty_debet_rejected'];
            $debet_rejected = $item['debet_rejected'];
            $qty_kredit_rejected = $item['qty_kredit_rejected'];
            $kredit_rejected = $item['kredit_rejected'];
            $qty_debet_closed = $item['qty_debet_closed'];
            $debet_closed = $item['debet_closed'];
            $qty_debet_batal = $item['qty_debet_batal'];
            $debet_batal = $item['debet_batal'];
            $rekening = $item['rekening'];
            // cekBiru("$rekening");
            $transaksi_tipe = $item['transaksi_tipe'];

            if (!isset($sumSubjek[$subjek_id]['qty_debet_reguler_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_reguler_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_reguler_' . $rekening] += $qty_debet;

            if (!isset($sumSubjek[$subjek_id]['debet_reguler_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_reguler_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_reguler_' . $rekening] += $debet;

            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['qty_debet_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_rejected_' . $rekening] += $qty_debet_rejected;

            if (!isset($sumSubjek[$subjek_id]['debet_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_rejected_' . $rekening] += $debet_rejected;

            if (!isset($sumSubjek[$subjek_id]['qty_kredit_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_kredit_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_kredit_rejected_' . $rekening] += $qty_kredit_rejected;

            if (!isset($sumSubjek[$subjek_id]['kredit_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['kredit_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['kredit_rejected_' . $rekening] += $kredit_rejected;

            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['qty_debet_closed_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_closed_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_closed_' . $rekening] += $qty_debet_closed;

            if (!isset($sumSubjek[$subjek_id]['debet_closed_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_closed_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_closed_' . $rekening] += $debet_closed;

            //---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['qty_debet_batal_' . $rekening])) {
                $sumSubjek[$subjek_id]['qty_debet_batal_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['qty_debet_batal_' . $rekening] += $qty_debet_batal;

            if (!isset($sumSubjek[$subjek_id]['debet_batal_' . $rekening])) {
                $sumSubjek[$subjek_id]['debet_batal_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['debet_batal_' . $rekening] += $debet_batal;

            //---------------------------------------------------------

            $arrSubjek[$subjek_id] = $item;
            // $arrRekenings[$rekening] = $rekening;
        }

        // arrPrintHijau($sumSubjek);
        // test_table($sumSubjek);
        // mati_disini(__LINE__);

        /* ------------------------------------------------------------------------------------------
         * yang lalu dengan prefik prev
         * ------------------------------------------------------------------------------------------*/
        // $sumSubjek = array();
        foreach ($src_yang_lalu as $item) {

            $subjek_id = $item['extern_id'];
            $qty_debet = $item['qty_debet_reguler'];
            $debet = $item['debet_reguler'];
            $qty_debet_rejected = $item['qty_debet_rejected'];
            $debet_rejected = $item['debet_rejected'];
            $qty_kredit_rejected = $item['qty_kredit_rejected'];
            $kredit_rejected = $item['kredit_rejected'];
            $qty_debet_closed = $item['qty_debet_closed'];
            $debet_closed = $item['debet_closed'];
            $qty_debet_batal = $item['qty_debet_batal'];
            $debet_batal = $item['debet_batal'];
            $rekening = $item['rekening'];

            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_reguler_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_reguler_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_reguler_' . $rekening] += $qty_debet;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_reguler_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_reguler_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_reguler_' . $rekening] += $debet;

            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_rejected_' . $rekening] += $qty_debet_rejected;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_rejected_' . $rekening] += $debet_rejected;

            if (!isset($sumSubjek[$subjek_id]['prev_qty_kredit_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_kredit_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_kredit_rejected_' . $rekening] += $qty_kredit_rejected;

            if (!isset($sumSubjek[$subjek_id]['prev_kredit_rejected_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_kredit_rejected_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_kredit_rejected_' . $rekening] += $kredit_rejected;

            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_closed_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_closed_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_closed_' . $rekening] += $qty_debet_closed;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_closed_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_closed_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_closed_' . $rekening] += $debet_closed;
            // ---------------------------------------------------------
            if (!isset($sumSubjek[$subjek_id]['prev_qty_debet_batal_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_qty_debet_batal_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_qty_debet_batal_' . $rekening] += $qty_debet_batal;

            if (!isset($sumSubjek[$subjek_id]['prev_debet_batal_' . $rekening])) {
                $sumSubjek[$subjek_id]['prev_debet_batal_' . $rekening] = 0;
            }
            $sumSubjek[$subjek_id]['prev_debet_batal_' . $rekening] += $debet_batal;

            //---------------------------------------------------------
            $arrSubjek[$subjek_id] = $item;
            // $arrRekenings[$rekening] = $rekening;
        }
        // arrPrint($sumSubjek);
        // test_table($sumSubjek);

        /* ----------------------------------------------------------------------------------------------------------
         * Rumus san order netto order - kirim - return_kirim
         * dikarekan untuk penilaian performa selesman, dan return tidak menghidupkan so
         * ----------------------------------------------------------------------------------------------------------*/
        // $sumSubjek = array();
        foreach ($sumSubjek as $sbj_id => $sbjDatas) {
            // arrPrintPink($sbjDatas);
            foreach ($arrRekenings as $strekening) {
                foreach ($arrTransaksiTipes as $trtipe) {

                    $caller_prev_qty = "prev_qty_debet_$trtipe" . "_$strekening";
                    $caller_prev_qty_kredit = "prev_qty_kredit_$trtipe" . "_$strekening";
                    $caller_qty = "qty_debet_$trtipe" . "_$strekening";

                    $caller_prev = "prev_debet_$trtipe" . "_$strekening";
                    $caller_prev_kredit = "prev_kredit_$trtipe" . "_$strekening";

                    $caller = "debet_$trtipe" . "_$strekening";
                    $caller_qty_kredit = "qty_kredit_$trtipe" . "_$strekening";
                    $caller_kredit = "kredit_$trtipe" . "_$strekening";

                    $$caller_prev_qty = isset($sbjDatas[$caller_prev_qty]) ? $sbjDatas[$caller_prev_qty] : 0;
                    $$caller_prev = isset($sbjDatas[$caller_prev]) ? $sbjDatas[$caller_prev] : 0;
                    $$caller_prev_qty_kredit = isset($sbjDatas[$caller_prev_qty_kredit]) ? $sbjDatas[$caller_prev_qty_kredit] : 0;
                    $$caller_prev_kredit = isset($sbjDatas[$caller_prev_kredit]) ? $sbjDatas[$caller_prev_kredit] : 0;
                    $$caller_qty = isset($sbjDatas[$caller_qty]) ? $sbjDatas[$caller_qty] : 0;
                    $$caller = isset($sbjDatas[$caller]) ? $sbjDatas[$caller] : 0;
                    $$caller_qty_kredit = isset($sbjDatas[$caller_qty_kredit]) ? $sbjDatas[$caller_qty_kredit] : 0;
                    $$caller_kredit = isset($sbjDatas[$caller_kredit]) ? $sbjDatas[$caller_kredit] : 0;
                }
            }

            $awalOts = isset($arrOutstanding2021[$sbj_id]) ? $arrOutstanding2021[$sbj_id] : 0;
            $alhir2021qty = $awalOts["qty"];
            $alhir2021nilai = $awalOts["nilai"];

            // mati_disini($qty_debet_reguler_582so);
            // -------------------------NEW OUTSTANDING-------------------------------------------------
            //region Description
            $qty_return_order = ($qty_debet_rejected_582so + $qty_debet_closed_582so + $qty_kredit_rejected_582so);
            // cekMerah("$qty_return_order = ($qty_debet_rejected_582so + $qty_debet_closed_582so + $qty_kredit_rejected_582so)");
            $qty_return_kirim = ($qty_debet_rejected_582spd + $qty_debet_closed_582spd);
            $qty_pembatalan_kirim = ($qty_debet_batal_9912);
            $qty_order = ($qty_debet_reguler_582so + $qty_debet_reguler_382so) - $qty_return_order - $qty_return_kirim - $qty_pembatalan_kirim;
            // cekBiru("$qty_order = ($qty_debet_reguler_582so + $qty_debet_reguler_382so) - $qty_return_order - $qty_return_kirim - $qty_pembatalan_kirim;");
            // $qty_kirim = ($qty_debet_reguler_582spd + $qty_debet_reguler_382spd) - $qty_return_kirim;
            $qty_total_kirim = $qty_debet_reguler_582spd + $qty_debet_reguler_382spd;
            $qty_kirim = $qty_total_kirim - $qty_pembatalan_kirim;
            // cekKuning("$qty_kirim = ($qty_debet_reguler_582spd + $qty_debet_reguler_382spd) - $qty_pembatalan_kirim;");
            $qty_outstanding = $qty_order - $qty_kirim;
            // cekBiru("$qty_outstanding = $qty_order - $qty_kirim");
            $qty_outstanding_f = $qty_outstanding < 0 ? 0 : $qty_outstanding;
            if (!isset($outstandingSubjek[$sbj_id]['qty_outstanding'])) {
                $outstandingSubjek[$sbj_id]['qty_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['qty_outstanding'] += $qty_outstanding;
            $sumSubjek[$sbj_id]['qty_order_netto'] = $qty_order;
            $sumSubjek[$sbj_id]['qty_kirim_netto'] = $qty_kirim;
            $sumSubjek[$sbj_id]['qty_kirim_batal'] = $qty_pembatalan_kirim;
            $sumSubjek[$sbj_id]['qty_kirim_total'] = $qty_total_kirim;

            $return_order = ($debet_rejected_582so + $debet_closed_582so + $kredit_rejected_582so);
            $return_kirim = ($debet_rejected_582spd + $debet_closed_582spd);
            $pembatalan_kirim = ($debet_batal_9912);
            $order = ($debet_reguler_582so + $debet_reguler_382so) - $return_order - $return_kirim - $pembatalan_kirim;
            // $kirim = ($debet_reguler_582spd + $debet_reguler_382spd) - $return_kirim;
            $total_kirim = $debet_reguler_582spd + $debet_reguler_382spd;
            $kirim = $total_kirim - $pembatalan_kirim;

            $outstanding = $order - $kirim;
            $outstanding_f = $qty_outstanding_f == 0 ? 0 : $outstanding;
            if (!isset($outstandingSubjek[$sbj_id]['outstanding'])) {
                $outstandingSubjek[$sbj_id]['outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['outstanding'] += $outstanding;
            $sumSubjek[$sbj_id]['order_netto'] = $order;
            $sumSubjek[$sbj_id]['kirim_netto'] = $kirim;
            $sumSubjek[$sbj_id]['kirim_batal'] = $pembatalan_kirim;
            $sumSubjek[$sbj_id]['kirim_total'] = $total_kirim;
            //endregion

            // -------------------------PREVIOUS OUTSTANDING-------------------------------------------------
            $prev_qty_return_order = ($prev_qty_debet_rejected_582so + $prev_qty_debet_closed_582so + $prev_qty_kredit_closed_582so + $prev_qty_kredit_rejected_582so);
            // cekBiru("$prev_qty_return_order = ($prev_qty_debet_rejected_582so + $prev_qty_debet_closed_582so + $prev_qty_kredit_closed_582so);");
            $prev_qty_return_kirim = ($prev_qty_debet_rejected_582spd + $prev_qty_debet_closed_582spd);
            $prev_qty_pembatalan_kirim = ($prev_qty_debet_batal_9912);
            $prev_qty_order = ($prev_qty_debet_reguler_582so + $prev_qty_debet_reguler_382so) - $prev_qty_return_order - $prev_qty_return_kirim - $prev_qty_pembatalan_kirim;
            // cekPink("order $prev_qty_order = ($prev_qty_debet_reguler_582so + $prev_qty_debet_reguler_382so) - $prev_qty_return_order - $prev_qty_return_kirim - $prev_qty_pembatalan_kirim;");
            // $prev_qty_kirim = ($prev_qty_debet_reguler_582spd + $prev_qty_debet_reguler_382spd) - $prev_qty_return_kirim;
            $prev_qty_kirim = ($prev_qty_debet_reguler_582spd + $prev_qty_debet_reguler_382spd) - $prev_qty_pembatalan_kirim;
            // cekBiru("kirim [$sbj_id] $prev_qty_kirim = ($prev_qty_debet_reguler_582spd + $prev_qty_debet_reguler_382spd) - $prev_qty_pembatalan_kirim;");
            $prev_qty_outstanding = $alhir2021qty + ($prev_qty_order - $prev_qty_kirim);
            // cekHijau("ots $prev_qty_outstanding = $alhir2021qty + ($prev_qty_order - $prev_qty_kirim);");
            // $prev_qty_outstanding = ($prev_qty_order - $prev_qty_kirim);
            if (!isset($outstandingSubjek[$sbj_id]['prev_qty_outstanding'])) {
                $outstandingSubjek[$sbj_id]['prev_qty_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['prev_qty_outstanding'] += $prev_qty_outstanding;

            if (!isset($outstandingSubjek[$sbj_id]['prev_qty_kirim'])) {
                $outstandingSubjek[$sbj_id]['prev_qty_kirim'] = 0;
            }
            $outstandingSubjek[$sbj_id]['prev_qty_kirim'] += $prev_qty_kirim;
            // ------------------------------------------------------------------------------------------------
            $prev_return_order = ($prev_debet_rejected_582so + $prev_debet_closed_582so + $prev_kredit_closed_582so + $prev_kredit_rejected_582so);
            $prev_return_kirim = ($prev_debet_rejected_582spd + $prev_debet_closed_582spd);
            $prev_pembatalan_kirim = ($prev_debet_batal_9912);
            $prev_order = ($prev_debet_reguler_582so + $prev_debet_reguler_382so) - $prev_return_order - $prev_return_kirim - $prev_pembatalan_kirim;
            $prev_kirim = ($prev_debet_reguler_582spd + $prev_debet_reguler_382spd) - $prev_pembatalan_kirim;
            // cekPink("kirim [$sbj_id] $prev_kirim = ($prev_debet_reguler_582spd + $prev_debet_reguler_382spd) - $prev_pembatalan_kirim");
            $prev_outstanding = $alhir2021nilai + ($prev_order - $prev_kirim);
            // $prev_outstanding = $alhir2021nilai + (($prev_debet_582so + $prev_debet_382so) - $prev_debet_982) - ($prev_debet_582spd + $prev_debet_382spd);
            if (!isset($outstandingSubjek[$sbj_id]['prev_outstanding'])) {
                $outstandingSubjek[$sbj_id]['prev_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['prev_outstanding'] += $prev_outstanding;

            if (!isset($outstandingSubjek[$sbj_id]['prev_kirim'])) {
                $outstandingSubjek[$sbj_id]['prev_kirim'] = 0;
            }
            $outstandingSubjek[$sbj_id]['prev_kirim'] += $prev_kirim;
        }

        // arrPrintPink($sumSubjek);
        // test_table($sumSubjek);
        // arrPrintPink($sumSubjek);
        // mati_disini(__LINE__);
        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            $sumParams = $sumSubjek[$subj_id];
            // $customer_id = $itemParam['m_customerID'];
            // arrPrintKuning($itemParam);
            //region penyiapan kolom data YTD
            foreach ($arrRekenings as $strekening) {
                foreach ($arrTransaksiTipes as $trtipe) {
                    $caller_prev_qty = "prev_qty_debet_$trtipe" . "_$strekening";
                    $caller_prev_qty_kredit = "prev_qty_kredit_$trtipe" . "_$strekening";
                    $caller_qty = "qty_debet_$trtipe" . "_$strekening";

                    $caller_prev = "prev_debet_$trtipe" . "_$strekening";
                    $caller_prev_kredit = "prev_kredit_$trtipe" . "_$strekening";

                    $caller = "debet_$trtipe" . "_$strekening";
                    $caller_qty_kredit = "qty_kredit_$trtipe" . "_$strekening";
                    $caller_kredit = "kredit_$trtipe" . "_$strekening";

                    $$caller_prev_qty = isset($sumParams[$caller_prev_qty]) ? $sumParams[$caller_prev_qty] : 0;
                    $$caller_prev = isset($sumParams[$caller_prev]) ? $sumParams[$caller_prev] : 0;
                    $$caller_prev_qty_kredit = isset($sumParams[$caller_prev_qty_kredit]) ? $sumParams[$caller_prev_qty_kredit] : 0;
                    $$caller_prev_kredit = isset($sumParams[$caller_prev_kredit]) ? $sumParams[$caller_prev_kredit] : 0;
                    $$caller_qty = isset($sumParams[$caller_qty]) ? $sumParams[$caller_qty] : 0;
                    $$caller = isset($sumParams[$caller]) ? $sumParams[$caller] : 0;
                    $$caller_qty_kredit = isset($sumParams[$caller_qty_kredit]) ? $sumParams[$caller_qty_kredit] : 0;
                    $$caller_kredit = isset($sumParams[$caller_kredit]) ? $sumParams[$caller_kredit] : 0;
                }
            }

            // cekMerah("$qty_debet_batal_9912");

            $qty_return_order_mtd = ($qty_debet_rejected_582so + $qty_debet_closed_582so + $qty_kredit_rejected_582so);
            // cekMerah("[$subj_id] $qty_return_order_mtd_e *** $qty_kredit_rejected_582so");
            $qty_return_kirim_mtd = ($qty_debet_rejected_582spd + $qty_debet_closed_582spd);
            $qty_batal_kirim_mtd = $qty_debet_batal_9912;
            $qty_order_mtd = ($qty_debet_reguler_582so + $qty_debet_reguler_382so) - $qty_return_order_mtd - $qty_return_kirim_mtd - $qty_batal_kirim_mtd;
            // $qty_kirim_mtd = ($qty_debet_reguler_582spd + $qty_debet_reguler_382spd) - $qty_return_kirim_mtd;
            $qty_kirim_mtd = ($qty_debet_reguler_582spd + $qty_debet_reguler_382spd) - $qty_batal_kirim_mtd;

            $qty_mtd = $qty_order_mtd - $qty_kirim_mtd;
            // if($subj_id == "65"){
            //     arrPrintHijau($sumSubjek[$subj_id]);
            //     $qty_kredit_rejected_582so_tes = ($sumSubjek[$subj_id]['qty_kredit_rejected_582so']);
            //     cekHijau("*** $qty_return_order_mtd = ($qty_debet_rejected_582so + $qty_debet_closed_582so + $qty_kredit_rejected_582so || $qty_kredit_rejected_582so_tes)");
            //     cekHijau("$qty_order_mtd = ($qty_debet_reguler_582so + $qty_debet_reguler_382so) - $qty_return_order_mtd - $qty_return_kirim_mtd - $qty_batal_kirim_mtd;");
            //     cekHijau("[$subj_id] ".$outstandingSubjek[$subj_id]["prev_qty_outstanding"]." $qty_mtd = $qty_order_mtd - $qty_kirim_mtd;");
            //     // mati_disini($qty_return_order_mtd_e);
            // }
            $ytd_qty_outstanding = $outstandingSubjek[$subj_id]["prev_qty_outstanding"] + $qty_mtd;
            $ytd_qty_kirim = $outstandingSubjek[$subj_id]["prev_qty_kirim"] + $qty_kirim_mtd;
            // $sub_outstanding["ytd_qty_outstanding"] = $outstandingSubjek[$subj_id]["prev_qty_outstanding"] + $outstandingSubjek[$subj_id]["qty_outstanding"];
            $sub_outstanding["ytd_qty_kirim"] = $ytd_qty_kirim;
            $sub_outstanding["ytd_qty_outstanding"] = $ytd_qty_outstanding;
            // -----------------------------------------------------------------------------------
            $return_order_mtd = ($debet_rejected_582so + $debet_closed_582so + $kredit_rejected_582so);
            $return_kirim_mtd = ($debet_rejected_582spd + $debet_closed_582spd);
            $batal_kirim_mtd = $debet_batal_9912;
            $order_mtd = ($debet_reguler_582so + $debet_reguler_382so) - $return_order_mtd - $return_kirim_mtd - $batal_kirim_mtd;
            // $kirim_mtd = ($debet_reguler_582spd + $debet_reguler_382spd) - $return_kirim_mtd;
            $kirim_mtd = ($debet_reguler_582spd + $debet_reguler_382spd) - $batal_kirim_mtd;

            $mtd = $order_mtd - $kirim_mtd;
            // $mtd = ($mtd_582so + $mtd_382so - $mtd_982) - ($mtd_582spd + $mtd_382spd);
            $ytd_outstanding = $outstandingSubjek[$subj_id]["prev_outstanding"] + $mtd;
            $ytd_kirim = $outstandingSubjek[$subj_id]["prev_kirim"] + $kirim_mtd;

            $sub_outstanding["ytd_penjualan"] = $ytd_kirim;
            $sub_outstanding["ytd_outstanding"] = $ytd_qty_outstanding > 0 ? $ytd_outstanding : 0;
            //endregion

            // $hasilOlahan_1[] = $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
            $hasilOlahan_1[] = $itemParam + $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
            // $hasilOlahan[$customer_id] = $itemParam;
        }
        $masterData = $hasilOlahan_1;
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);

        $arrHeaders = array(
            "extern_id"       => array(
                "label" => "pid",
            ),
            "extern_nama"     => array(
                "label" => "Produk",
            ),
            "cabang_nama"     => array(
                "label" => "cabang",
            ),
            /*previous rinci*/
            "prev_qty_kirim"  => array(
                "label"      => "previous Sales qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "prev_kirim"      => array(
                "label"      => "previous sales value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            /*prevous outstanding*/
            // "prev_qty_outstanding" => array(
            //     "label"      => "previous outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_outstanding"     => array(
            //     "label"      => "previous outstanding value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*order rincian*/
            // "qty_debet_reguler_582so"      => array(
            //     "label"      => "new order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // // "debet_reguler_582so"          => array(
            // //     "label"      => "new order value",
            // //     "format"     => "formatField_he_format",
            // //     "format_key" => "harga",
            // //     "attr"       => "class='text-right'",
            // //     "summary"    => true,
            // // ),
            // "qty_debet_reguler_382so"      => array(
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
            // "qty_debet_rejected_582so"      => array(
            //     "label"      => "new order reject qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_rejected_582spd"      => array(
            //     "label"      => "new kirim reject qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*order new netto*/
            // "qty_order_netto"      => array(
            //     "label"      => "new netto order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "order_netto"          => array(
            //     "label"      => "new netto order value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*RETURNAN*/
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
            /*kirim rinci*/
            // "qty_debet_reguler_582spd"     => array(
            //     "label"      => "packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_reguler_582spd"         => array(
            //     "label"      => "packing list local value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_reguler_382spd"         => array(
            //     "label"      => "packing list export value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_rejected_582spd"     => array(
            //     "label"      => "packing list balik qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_batal_9912"         => array(
            //     "label"      => "packing list cancel",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*kirim*/
            "qty_kirim_total" => array(
                "label"      => "new sales qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "kirim_total"     => array(
                "label"      => "new sales value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "qty_kirim_batal" => array(
                "label"      => "new canceled sales qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "kirim_batal"     => array(
                "label"      => "new canceled sales value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "qty_kirim_netto" => array(
                "label"      => "new netto sales qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "kirim_netto"     => array(
                "label"      => "new netto sales value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            /*outstanding*/
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
            //     "format_key" => "netto",
            //     "attr"       => "class='text-right'",
            //     "logics"     => array(
            //         "nilai" => "<0",
            //         "text"  => "termasuk pengiriman dari outstanding sebelumnya",
            //     ),
            //     "summary"    => true,
            // ),
            /*last outstanding*/
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
            "ytd_qty_kirim"   => array(
                "label"      => "total sales qty",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
            ),
            "ytd_penjualan"   => array(
                "label"      => "total sales value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
            ),
            // "seller_id"      => array(
            //     "label"      => "sID",
            // ),
        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        // arrPrintHijau($get_date1);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        $month_now = dtimeNow('Y-m');
        $month_req = formatTanggal($get_date1, 'Y-m');
        // cekHere("$month_req $month_now");
        if ($month_now == $month_req) {
            $judul_lap = "MTD " . dtimeNow('d F Y H:i:s');
        }
        elseif ($date1 == dtimeNow('Y-01-01') && $date2 == dtimeNow('Y-m-d')) {
            $judul_lap = "YTD " . dtimeNow('d F Y H:i');
        }
        else {
            $judul_lap = " " . formatTanggal($get_date1, 'd F Y') . " - " . formatTanggal($get_date2, 'd F Y');
        }

        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung_indek",
            "title"       => "Laporan penjualan per Produk " . $judul_lap,
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "data_id"     => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(2),
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

    /* ----------------------------------------------------
    * pengambilan data masih dr com
    * ----------------------------------------------------*/
    // rendering complite
    public function viewepenjualan()
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

        $_GET['date1'] = $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
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
        $ulr_tahun_ini = current_url() . "?date1=" . dtimeNow('Y-01-01') . "&date2=" . dtimeNow('Y-m-d');
        //</editor-fold>

        /* -----------------------------------------------------------------------------------------------
         * logik untuk mematikan butun bulan belum sukses jadi masih dimatikan
         * -----------------------------------------------------------------------------------------------*/
        // cekHijau("$date_now **$date2**  $date_start //// $date_start_setelahnya *** " . dtimeToSecond($date2));
        $btn_disabled = "";
        $btn_disabled_mtd = "";
        $btn_disabled_ytd = "";
        $btn_disabled_aft = "";
        $btn_active = "";
        if (($date1 == dtimeNow('Y-m-01')) && ($date2 == dtimeNow('Y-m-d'))) {
            // cekBiru(dtimeToSecond($date2) ."<br>" . dtimeToSecond($date_start_setelahnya));
            $btn_disabled_mtd = "disabled";
            $btn_disabled_aft = "disabled";
            $btn_active = "btn-primary";
        }
        elseif (($date1 == dtimeNow('Y-01-01')) && ($date2 == dtimeNow('Y-m-d'))) {
            $btn_disabled_ytd = "disabled";
            $btn_disabled_aft = "disabled";
        }
        elseif (($date_stop_setelahnya > dtimeNow('Y-m-d'))) {
            $btn_disabled_aft = "disabled";
        }

        /* -----------------------------------------------------------------------------------------------
         * button navigasi bulanan
         * -----------------------------------------------------------------------------------------------*/
        // cekMerah("$date_start_sebelumnya ****** $date_stop_sebelumnya ******* " . date('Y-m-t', strtotime('2022-02-01')));
        // cekHijau($ulr_bulan_sebelum . " **** $str_get **** $str_geters");
        $btn_td = "<button type='button' class='btn btn-danger' onclick=\"location.href='$ulr_bulan_sebelum'\"><i class='fa fa-chevron-circle-left'></i> $nama_bulan_sebelum </button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' disabled>$nama_bulan_ygtampil <i class='fa fa-chevron-circle-down'></i></button>";
        $btn_td .= " <button type='button' class='btn $btn_active btn-danger' $btn_disabled_mtd onclick=\"location . href = '$ulr_bulan_ini'\">MTD</button>";
        $btn_td .= " <button type='button' class='btn btn-danger' $btn_disabled_ytd onclick=\"location . href = '$ulr_tahun_ini'\">YTD</button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' $btn_disabled_aft  onclick=\"location . href = '$ulr_bulan_setelah'\">$nama_bulan_setelah <i class='fa fa-chevron-circle-right '></i></button>";
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
            "title"       => "Laporan Aktifitas Penjualan",
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
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            // "sum_null"    => base_url() . "laporan/Crm/maintenance" . "$strGet",
            "sum_satu"    => base_url() . "laporan/Penjualan/cekpenjualanseller" . "$strGet",
            "sum_dua"     => base_url() . "laporan/Penjualan/cekpenjualantransaksi" . "$strGet",
            "sum_tiga"    => base_url() . "laporan/Penjualan/cekpenjualancustomer" . "$strGet",
            "sum_empat"   => base_url() . "laporan/Penjualan/cekpenjualanproduk" . "$strGet",

        );
        $this->load->view("laporan", $data);
    }

    public function viewmypenjualan()
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

        $_GET['date1'] = $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
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
        $ulr_tahun_ini = current_url() . "?date1=" . dtimeNow('Y-01-01') . "&date2=" . dtimeNow('Y-m-d');
        //</editor-fold>

        /* -----------------------------------------------------------------------------------------------
         * logik untuk mematikan butun bulan belum sukses jadi masih dimatikan
         * -----------------------------------------------------------------------------------------------*/
        // cekHijau("$date_now **$date2**  $date_start //// $date_start_setelahnya *** " . dtimeToSecond($date2));
        $btn_disabled = "";
        $btn_disabled_mtd = "";
        $btn_disabled_ytd = "";
        $btn_disabled_aft = "";
        $btn_active = "";
        if (($date1 == dtimeNow('Y-m-01')) && ($date2 == dtimeNow('Y-m-d'))) {
            // cekBiru(dtimeToSecond($date2) ."<br>" . dtimeToSecond($date_start_setelahnya));
            $btn_disabled_mtd = "disabled";
            $btn_disabled_aft = "disabled";
            $btn_active = "btn-primary";
        }
        elseif (($date1 == dtimeNow('Y-01-01')) && ($date2 == dtimeNow('Y-m-d'))) {
            $btn_disabled_ytd = "disabled";
            $btn_disabled_aft = "disabled";
        }
        elseif (($date_stop_setelahnya > dtimeNow('Y-m-d'))) {
            $btn_disabled_aft = "disabled";
        }

        /* -----------------------------------------------------------------------------------------------
         * button navigasi bulanan
         * -----------------------------------------------------------------------------------------------*/
        // cekMerah("$date_start_sebelumnya ****** $date_stop_sebelumnya ******* " . date('Y-m-t', strtotime('2022-02-01')));
        // cekHijau($ulr_bulan_sebelum . " **** $str_get **** $str_geters");
        $btn_td = "<button type='button' class='btn btn-danger' onclick=\"location.href='$ulr_bulan_sebelum'\"><i class='fa fa-chevron-circle-left'></i> $nama_bulan_sebelum </button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' disabled>$nama_bulan_ygtampil <i class='fa fa-chevron-circle-down'></i></button>";
        $btn_td .= " <button type='button' class='btn $btn_active btn-danger' $btn_disabled_mtd onclick=\"location . href = '$ulr_bulan_ini'\">MTD</button>";
        $btn_td .= " <button type='button' class='btn btn-danger' $btn_disabled_ytd onclick=\"location . href = '$ulr_tahun_ini'\">YTD</button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' $btn_disabled_aft  onclick=\"location . href = '$ulr_bulan_setelah'\">$nama_bulan_setelah <i class='fa fa-chevron-circle-right '></i></button>";
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
            "title"       => "Laporan Aktifitas Penjualan",
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
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            // "sum_null"    => base_url() . "laporan/Crm/maintenance" . "$strGet",
            // "sum_satu"    => base_url() . "laporan/Penjualan/cekpenjualanseller" . "$strGet",
            "sum_dua"     => base_url() . "laporan/Penjualan/cekpenjualantransaksi" . "$strGet",

        );
        $this->load->view("laporan", $data);
    }

    public function test()
    {
        $jenisTr = $this->jenisTr_penjualan;
        $this->load->library("Bigdata");
        $bd = new Bigdata();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $bd->setCondites($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $bd->setLimit($this->default_limit);
        }
        $bd->setJenistr($jenisTr);
        $src = $bd->callBdProdukAkunting();
        $masterData = $src['data'];
        $masterDataJml = $src['data_jml'];

        cekMerah(sizeof($masterData));
        // arrPrintKuning($masterData);
    }
}