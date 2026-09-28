<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 2/14/2019
 * Time: 12:50 PM
 */
class Report extends CI_Controller
{
    private $jenisTr;
    private $steps = array();
    private $dates = array();

    public function f__construct___()
    {
        parent::__construct();
        $tmpJenis = $this->uri->segment(3);
        if (strlen($tmpJenis) > 0) {
            $this->jenisTr = $tmpJenis;
        }

        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        validateUserSession($this->session->login['id']);//
        // arrPrint($this->session->login);

        $this->load->library("MobileDetect");
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlEmployeeCabang");
        $this->load->model("Mdls/MdlReport");
        $this->load->model("Mdls/MdlMongoMother");
        $trd = new MdlTransaksi();
        $trd->addFilter("jenis_top='" . $this->jenisTr . "'");
        $this->dates = $trd->lookupDates();
        $this->dates['entries'][date("y-m-d")] = date("y-m-d");
        $this->placeId = $this->session->login['cabang_id'];

        $this->sID_alias = array(
            "oleh_id"      => "olehID",
            "customers_id" => "pihakID",
            "cabang_id"    => "cabangID",
            "produk_id"    => "id",
            "suppliers_id" => "pihakID",
            "seller_id"    => "sellerID",
        );
        $this->groupListTable = array(
            //urutan query select table
            "cabang"          => array(
                "tableName" => array(
                    1 => "penjualan",
                    2 => "penjualan_produk",
                    3 => "penjualan_seller",
                ),
                "indexKey"  => array(
                    1 => "cabang_id",
                    2 => "cabang_id",
                    3 => "cabang_id",
                ),

                //                2=>"",
            ),
            "produk"          => "penjualan_produk",
            "salesman"        => "penjualan_seller",
            "customer"        => "penjualan_customer",
            "salesman_produk" => "penjualan_seller_produk",
        );
        $this->selectedFields = array(
            "cabang" => array(
                1 => array(
                    "alias" => array(
                        "cabang_id" => "cabang",
                        "nilai_af"  => "saldo",
                        //                        "th" =>"periode",
                    ),

                    "indexModel" => "MdlCabang",
                    "filters"    => array(
                        "id>'0'",
                        "tipe='0'",
                        "status='1'",
                        "trash='0'",
                    ),
                ),
                2 => array(

                    "alias"        => array(
                        //                        "cabang_id" => "cabang",
                        "subject_nama" => "produk",
                        "unit_af"      => "qty",
                        "nilai_af"     => "saldo",
                        //                        "th" =>"periode",
                    ),
                    "masterHeader" => array(
                        "cabang_id" => "cabang",
                    ),
                ),
                3 => array(
                    "alias"        => array(
                        //                        "cabang_id" => "cabang",
                        "subject_nama" => "salesman",
                        "nilai_af"     => "saldo",
                        //                        "th" =>"periode",
                    ),
                    "masterHeader" => array(
                        "cabang_id" => "cabang",
                    ),
                ),
            ),
            "produk" => array(
                1 => array(
                    "fields" => array(),
                    "alias"  => array(
                        "subject_id"   => "produk",
                        "subject_nama" => "produk",
                        "unit_af"      => "qty",
                        "nilai_af"     => "saldo",
                        "cabang_id"    => "cabang"
                    ),

                ),
            ),

            "salesman" => array(),
            "customer" => array(),
        );
    }

    public function __construct()
    {
        parent::__construct();
        $tmpJenis = $this->uri->segment(3);
        if (strlen($tmpJenis) > 0) {
            $this->jenisTr = $tmpJenis;
        }

        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        validateUserSession($this->session->login['id']);//
        // arrPrint($this->session->login);

        $this->load->library("MobileDetect");
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlEmployeeCabang");
        $this->load->model("Mdls/MdlReport");
        $this->load->model("Mdls/MdlMongoMother");
        $trd = new MdlTransaksi();
        $trd->addFilter("jenis_top='" . $this->jenisTr . "'");
        $this->dates = $trd->lookupDates();
        $this->dates['entries'][date("y-m-d")] = date("y-m-d");
        $this->placeId = $this->session->login['cabang_id'];

        $this->sID_alias = array(
            "oleh_id"      => "olehID",
            "customers_id" => "pihakID",
            "cabang_id"    => "cabangID",
            "produk_id"    => "id",
            "suppliers_id" => "pihakID",
            "seller_id"    => "sellerID",
        );
        $this->groupListTable = array(
            //urutan query select table
            "cabang"   => array(
                "tableName" => array(
                    1 => "penjualan_cabang",
                    2 => "penjualan_produk_cabang",
                    3 => "penjualan_customer_cabang",
                    4 => "penjualan_seller_cabang",
                ),
            ),
            "produk"   => array(
                "tableName" => array(
                    1 => "penjualan_produk",
                    2 => "penjualan_produk_cabang",
                    4 => "penjualan_produk_customer",
                    3 => "penjualan_produk_seller",

                    //                    3 => "penjualan_seller",
                ),

            ),
            "seller"   => array(
                "tableName" => array(
                    1 => "penjualan_seller",
                    2 => "penjualan_seller_cabang",
                    3 => "penjualan_seler_customer",
                    4 => "penjualan_produk_seller",
                ),
            ),
            "customer" => array(
                "tableName" => array(
                    1 => "penjualan_customer",
                    2 => "penjualan_customer_cabang",
                    3 => "penjualan_customer_seller",
                    4 => "penjualan_produk_customer",
                ),
            ),

        );
        $this->selectedFields = array(
            "cabang" => array(
                1 => array(
                    "headerFields"    => array(
                        "subject_id" => "cabang"
                    ),
                    "headerFields2"   => array(
                        "unit_af"  => "qty",
                        "nilai_af" => "nilai netto",
                    ),
                    "masterIndex"     => "subject_id",
                    "subject_date"    => array(
                        "tahunan" => "th",
                        "bulanan" => "bl",
                        "ytd"     => "th",
                    ),
                    "selected_fields" => array(
                        "subject_id" => array("subject_nama"),
                    ),
                    "sumFields"       => array(
                        "subtotal" => array(
                            "unit_af"  => "qty",
                            "nilai_af" => "nilai netto",
                        ),
                        "footer"   => array(
                            "unit_af"  => "qty",
                            "nilai_af" => "nilai netto",
                        ),
                    ),
                    "titleMain"       => "laporan penjualan cabang"
                ),
                2 => array(
                    "headerFields"    => array(
                        "subject_nama"  => "produk",
                        "subject_kode"  => "kode",
                        "subject_label" => "label",
                    ),
                    "headerFields2"   => array(
                        "unit_af"  => "qty",
                        "nilai_af" => "nilai netto",
                    ),
                    "subject_date"    => array(
                        "tahunan" => "th",
                        "bulanan" => "bl",
                        "ytd"     => "th",
                    ),
                    "selected_fields" => array(
                        "subject_id" => array("subject_nama",
                            "subject_kode",
                            "subject_label"
                        ),
                    ),
                    "index2"          => array(
                        "object_id" => "object_nama"
                    ),
                    "masterIndex"     => "subject_id",
                    "sumFields"       => array(
                        "subtotal" => array(
                            "unit_af"  => "qty",
                            "nilai_af" => "nilai netto",
                        ),
                        "footer"   => array(
                            "unit_af"  => "qty",
                            "nilai_af" => "nilai netto",
                        ),
                    ),
                    "titleMain"       => "laporan detil penjualan cabang per produk"
                ),
                3 => array(
                    "headerFields"    => array(
                        "subject_nama" => "customer",
                        //                        "subject_kode" =>"kode",
                        //                        "subject_label" =>"label",
                    ),
                    "headerFields2"   => array(
                        //                        "unit_af" => "qty",
                        "nilai_af" => "nilai netto",
                    ),
                    "subject_date"    => array(
                        "tahunan" => "th",
                        "bulanan" => "bl",
                        "ytd"     => "th",
                    ),
                    "selected_fields" => array(
                        "subject_id" => array("subject_nama"),
                    ),
                    "index2"          => array(
                        "object_id" => "object_nama"
                    ),
                    "masterIndex"     => "subject_id",
                    "sumFields"       => array(
                        "subtotal" => array(
                            "unit_af"  => "qty",
                            "nilai_af" => "nilai netto",
                        ),
                        "footer"   => array(
                            "unit_af"  => "qty",
                            "nilai_af" => "nilai netto",
                        ),
                    ),
                    "titleMain"       => "laporan detil penjualan  cabang per customer"
                ),
                4 => array(
                    "headerFields"    => array(
                        "subject_nama" => "customer",
                        //                        "subject_kode" =>"kode",
                        //                        "subject_label" =>"label",
                    ),
                    "headerFields2"   => array(
                        //                        "unit_af" => "qty",
                        "nilai_af" => "nilai netto",
                    ),
                    "subject_date"    => array(
                        "tahunan" => "th",
                        "bulanan" => "bl",
                        "ytd"     => "th",
                    ),
                    "selected_fields" => array(
                        "subject_id" => array("subject_nama"),
                    ),
                    "index2"          => array(
                        "object_id" => "object_nama"
                    ),
                    "masterIndex"     => "subject_id",
                    "sumFields"       => array(
                        "subtotal" => array(
                            "unit_af"  => "qty",
                            "nilai_af" => "nilai netto",
                        ),
                        "footer"   => array(
                            "unit_af"  => "qty",
                            "nilai_af" => "nilai netto",
                        ),
                    ),
                    "titleMain"       => "laporan detil penjualan  cabang per salesman"
                ),
            ),
            "produk" => array(
                1 => array(
                    "headerFields"    => array(
                        "subject_id"    => "produk",
                        "subject_kode"  => "kode",
                        "subject_label" => "label",
                    ),
                    "headerFields2"   => array(
                        "unit_af"  => "qty",
                        "nilai_af" => "nilai netto",
                    ),
                    "masterIndex"     => "subject_id",
                    "subject_date"    => array(
                        "tahunan" => "th",
                        "bulanan" => "bl",
                        "ytd"     => "th",
                    ),
                    "selected_fields" => array(
                        "subject_id" => array("subject_nama",
                            "subject_kode",
                            "subject_label"
                        ),
                    ),
                    "sumFields"       => array(

                        "subtotal" => array(
                            "unit_af"  => "qty",
                            "nilai_af" => "nilai netto",
                        ),
                        "footer"   => array(
                            "unit_af"  => "qty",
                            "nilai_af" => "nilai netto",
                        ),
                    ),

                    "titleMain" => "laporan penjualan produk"

                ),
                2 => array(
                    "headerFields"    => array(
                        "subject_nama"  => "produk",
                        "subject_kode"  => "kode",
                        "subject_label" => "label",
                    ),
                    "headerFields2"   => array(
                        "unit_af"  => "qty",
                        "nilai_af" => "nilai netto",
                    ),
                    "subject_date"    => array(
                        "tahunan" => "th",
                        "bulanan" => "bl",
                        "ytd"     => "th",
                    ),
                    "selected_fields" => array(
                        "subject_id" => array("subject_nama",
                            "subject_kode",
                            "subject_label"
                        ),
                    ),
                    "index2"          => array(
                        "object_id" => "object_nama"
                    ),
                    "masterIndex"     => "subject_id",
                    "subtotal"        => array(
                        "unit_af"  => "qty",
                        "nilai_af" => "nilai netto",
                    ),
                    "titleMain"       => "laporan detil penjualan produk per cabang"
                ),
                3 => array(
                    "headerFields"    => array(
                        "subject_nama"  => "produk",
                        "subject_kode"  => "kode",
                        "subject_label" => "label",
                    ),
                    "headerFields2"   => array(
                        "unit_af"  => "qty",
                        "nilai_af" => "nilai netto",
                    ),
                    "subject_date"    => array(
                        "tahunan" => "th",
                        "bulanan" => "bl",
                        "ytd"     => "th",
                    ),
                    "selected_fields" => array(
                        "subject_id" => array("subject_nama",
                            "subject_kode",
                            "subject_label"
                        ),
                    ),
                    "index2"          => array(
                        "object_id" => "object_nama"
                    ),
                    "masterIndex"     => "subject_id",
                    "subtotal"        => array(
                        "unit_af"  => "qty",
                        "nilai_af" => "nilai netto",
                    ),
                    "titleMain"       => "laporan detil penjualan produk per customer"
                ),
                4 => array(
                    "headerFields"    => array(
                        "subject_nama" => "customer",
                        //                        "subject_kode" =>"kode",
                        //                        "subject_label" =>"label",
                    ),
                    "headerFields2"   => array(
                        //                        "unit_af" => "qty",
                        "nilai_af" => "nilai netto",
                    ),
                    "subject_date"    => array(
                        "tahunan" => "th",
                        "bulanan" => "bl",
                        "ytd"     => "th",
                    ),
                    "selected_fields" => array(
                        "subject_id" => array("subject_nama"),
                    ),
                    "index2"          => array(
                        "object_id" => "object_nama"
                    ),
                    "masterIndex"     => "subject_id",
                    "sumFields"       => array(
                        "subtotal" => array(
                            "unit_af"  => "qty",
                            "nilai_af" => "nilai netto",
                        ),
                        "footer"   => array(
                            "unit_af"  => "qty",
                            "nilai_af" => "nilai netto",
                        ),
                    ),
                    "titleMain"       => "laporan detil penjualan  produk per salesman"
                ),

            ),

            "seller"   => array(
                // 1 => array(
                //     "headerFields"    => array(
                //         "subject_id" => "salesman"
                //     ),
                //     "headerFields2"   => array(
                //         "unit_af"  => "qty",
                //         "nilai_af" => "nilai netto",
                //     ),
                //     "subject_date"    => array(
                //         "tahunan" => "th",
                //         "bulanan" => "bl",
                //         "ytd"     => "th",
                //     ),
                //     "masterIndex"     => "subject_id",
                //     "selected_fields" => array(
                //         "subject_id" => array("subject_nama"),
                //     ),
                //     "sumFields"       => array(
                //         "subtotal" => array(
                //             //                            "unit_af" => "qty",
                //             "nilai_af" => "nilai netto",
                //         ),
                //         "footer"   => array(
                //             //                            "unit_af" => "qty",
                //             "nilai_af" => "nilai netto",
                //         ),
                //     ),
                //     "titleMain"       => "laporan penjualan  per salesman"
                //
                // ),
                2 => array(
                    "headerFields"    => array(
                        "subject_nama" => "salesman",
                        //                        "subject_kode" =>"kode",
                        //                        "subject_label" =>"label",
                    ),
                    "headerFields2"   => array(
                        "unit_af"  => "qty",
                        "nilai_af" => "nilai netto",
                    ),
                    "subject_date"    => array(
                        "tahunan" => "th",
                        "bulanan" => "bl",
                        "ytd"     => "th",
                    ),
                    "selected_fields" => array(
                        "subject_id" => array("subject_nama",
                            "subject_kode",
                            "subject_label"
                        ),
                    ),
                    "index2"          => array(
                        "object_id" => "object_nama"
                    ),
                    "masterIndex"     => "subject_id",
                    "sumFields"       => array(
                        "subtotal" => array(
                            //                            "unit_af" => "qty",
                            "nilai_af" => "nilai netto",
                        ),
                        "footer"   => array(
                            //                            "unit_af" => "qty",
                            "nilai_af" => "nilai netto",
                        ),
                    ),
                    "titleMain"       => "laporan detil penjualan salesman per cabang"
                ),
                3 => array(
                    "headerFields"    => array(
                        "subject_nama" => "salesman",
                        //                        "subject_kode" =>"kode",
                        //                        "subject_label" =>"label",
                    ),
                    "headerFields2"   => array(
                        "unit_af"  => "qty",
                        "nilai_af" => "nilai netto",
                    ),
                    "subject_date"    => array(
                        "tahunan" => "th",
                        "bulanan" => "bl",
                        "ytd"     => "th",
                    ),
                    "selected_fields" => array(
                        "subject_id" => array("subject_nama",
                            "subject_kode",
                            "subject_label"
                        ),
                    ),
                    "index2"          => array(
                        "object_id" => "object_nama"
                    ),
                    "masterIndex"     => "subject_id",
                    "sumFields"       => array(
                        "subtotal" => array(
                            //                            "unit_af" => "qty",
                            "nilai_af" => "nilai netto",
                        ),
                        "footer"   => array(
                            //                            "unit_af" => "qty",
                            "nilai_af" => "nilai netto",
                        ),
                    ),
                    "titleMain"       => "laporan detil penjualan salesman per customer"
                ),
                4 => array(
                    "headerFields"    => array(
                        "subject_nama" => "customer",
                        //                        "subject_kode" =>"kode",
                        //                        "subject_label" =>"label",
                    ),
                    "headerFields2"   => array(
                        "unit_af"  => "qty",
                        "nilai_af" => "nilai netto",
                    ),
                    "subject_date"    => array(
                        "tahunan" => "th",
                        "bulanan" => "bl",
                        "ytd"     => "th",
                    ),
                    "selected_fields" => array(
                        "subject_id" => array("subject_nama"),
                    ),
                    "index2"          => array(
                        "object_id" => "object_nama"
                    ),
                    "masterIndex"     => "subject_id",
                    "sumFields"       => array(
                        "subtotal" => array(
                            "unit_af"  => "qty",
                            "nilai_af" => "nilai netto",
                        ),
                        "footer"   => array(
                            "unit_af"  => "qty",
                            "nilai_af" => "nilai netto",
                        ),
                    ),
                    "titleMain"       => "laporan penjualan salesman per produk"
                ),

            ),
            "customer" => array(
                1 => array(
                    "headerFields"    => array(
                        "subject_id" => "customer"
                    ),
                    "headerFields2"   => array(
                        "unit_af"  => "qty",
                        "nilai_af" => "nilai netto",
                    ),
                    "subject_date"    => array(
                        "tahunan" => "th",
                        "bulanan" => "bl",
                        "ytd"     => "th",
                    ),
                    "selected_fields" => array(
                        "subject_id" => array("subject_nama"),
                    ),
                    "titleMain"       => "laporan penjualan customer",
                    "sumFields"       => array(
                        "subtotal" => array(
                            //                            "unit_af" => "qty",
                            "nilai_af" => "nilai netto",
                        ),
                        "footer"   => array(
                            //                            "unit_af" => "qty",
                            "nilai_af" => "nilai netto",
                        ),
                    ),
                    "masterIndex"     => "subject_id",
                ),
                2 => array(
                    "headerFields"    => array(
                        "subject_nama" => "customer",
                        //                        "subject_kode" =>"kode",
                        //                        "subject_label" =>"label",
                    ),
                    "headerFields2"   => array(
                        "unit_af"  => "qty",
                        "nilai_af" => "nilai netto",
                    ),
                    "subject_date"    => array(
                        "tahunan" => "th",
                        "bulanan" => "bl",
                        "ytd"     => "th",
                    ),
                    "selected_fields" => array(
                        "subject_id" => array("subject_nama",
                            "subject_kode",
                            "subject_label"
                        ),
                    ),
                    "index2"          => array(
                        "object_id" => "object_nama"
                    ),
                    "masterIndex"     => "subject_id",
                    "titleMain"       => "laporan detil penjualan customer per cabang",
                    "sumFields"       => array(
                        "subtotal" => array(
                            //                            "unit_af" => "qty",
                            "nilai_af" => "nilai netto",
                        ),
                        "footer"   => array(
                            //                            "unit_af" => "qty",
                            "nilai_af" => "nilai netto",
                        ),
                    ),
                ),
                3 => array(
                    "headerFields"    => array(
                        "subject_nama" => "customer",
                        //                        "subject_kode" =>"kode",
                        //                        "subject_label" =>"label",
                    ),
                    "headerFields2"   => array(
                        "unit_af"  => "qty",
                        "nilai_af" => "nilai netto",
                    ),
                    "subject_date"    => array(
                        "tahunan" => "th",
                        "bulanan" => "bl",
                        "ytd"     => "th",
                    ),
                    "selected_fields" => array(
                        "subject_id" => array("subject_nama",
                            "subject_kode",
                            "subject_label"
                        ),
                    ),
                    "index2"          => array(
                        "object_id" => "object_nama"
                    ),
                    "masterIndex"     => "subject_id",
                    "titleMain"       => "laporan detil penjualan customer per cabang",
                    "sumFields"       => array(
                        "subtotal" => array(
                            //                            "unit_af" => "qty",
                            "nilai_af" => "nilai netto",
                        ),
                        "footer"   => array(
                            //                            "unit_af" => "qty",
                            "nilai_af" => "nilai netto",
                        ),
                    ),
                ),
                4 => array(
                    "headerFields"    => array(
                        "subject_nama" => "customer",
                        //                        "subject_kode" =>"kode",
                        //                        "subject_label" =>"label",
                    ),
                    "headerFields2"   => array(
                        //                        "unit_af" => "qty",
                        "nilai_af" => "nilai netto",
                    ),
                    "subject_date"    => array(
                        "tahunan" => "th",
                        "bulanan" => "bl",
                        "ytd"     => "th",
                    ),
                    "selected_fields" => array(
                        "subject_id" => array("subject_nama"),
                    ),
                    "index2"          => array(
                        "object_id" => "object_nama"
                    ),
                    "masterIndex"     => "subject_id",
                    "sumFields"       => array(
                        "subtotal" => array(
                            "unit_af"  => "qty",
                            "nilai_af" => "nilai netto",
                        ),
                        "footer"   => array(
                            "unit_af"  => "qty",
                            "nilai_af" => "nilai netto",
                        ),
                    ),
                    "titleMain"       => "laporan detil penjualan  customer per produk"
                ),
            ),
        );
        $this->periode = array(
            "bl"  => array(
                "label"  => "bulanan",
                "method" => "viewMonthly"
            ),
            "th"  => array(
                "label"  => "tahunan",
                "method" => "viewYear"
            ),
            "ytd" => array(
                "label"  => "tahun berjalan",
                "method" => "viewYearly"
            ),
        );
        $this->grupReport = array(
            "cabang"   => array(
                "label"  => "cabang",
                "action" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/cabang"
            ),
            "produk"   => array(
                "label"  => "produk",
                "action" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/produk"
            ),
            "salesman" => array(
                "label"  => "salesman",
                "action" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/seller"
            ),
            "customer" => array(
                "label"  => "customer",
                "action" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/customer"
            ),
        );
        $this->linkDetail = array(
            "viewYear"    => array(
                "mainHeader" => base_url() . get_class($this) . "/viewMonthly",
                "main"       => base_url() . get_class($this) . "/viewDetailMainMonthly",
                "child1"     => base_url() . get_class($this) . "/viewDetailChildMonthly",
                "child2"     => base_url() . get_class($this) . "/viewDetailSubChildMonthly",
            ),
            "viewYearly"  => array(
                "mainHeader" => base_url() . get_class($this) . "/viewMonthly",
                "main"       => base_url() . get_class($this) . "/viewDetailMainMonthly",
                "child1"     => base_url() . get_class($this) . "/viewDetailChildMonthly",
                "child2"     => base_url() . get_class($this) . "/viewDetailSubChildMonthly",
            ),
            //            "viewYearly"=>base_url() . get_class($this) . "/viewMonthly",
            "viewMonthly" => array(
                "mainHeader" => base_url() . get_class($this) . "/viewMonthly",
                //                "main" =>base_url() . get_class($this) . "/viewDetailMainMonthly",
                //                "child1" =>base_url() . get_class($this) . "/viewDetailChildMonthly",
                //                "child2" =>base_url() . get_class($this) . "/viewDetailSubChildMonthly",
            ),
            //            "viewMonthly"=>base_url() . get_class($this) . "/viewDaily",
            "viewDaily"   => array(),
            //            "viewDaily" =>base_url() . get_class($this) . "/viewDetail",

        );


    }

    // sama dengan view sales all yang dicompare dengan last year
    public function viewSalesAllCompared()
    {
        $compFields = array();
        if (isset($_GET['date'])) {
            $year = formatTanggal($_GET['date'], 'Y');
            $month = formatTanggal($_GET['date'], 'm');
            $last_year = $year - 1;
            $last_month = formatTanggal($_GET['date'], 'm');

            $conditeCompared = "((th='$year' and bl='$month') or (th='$last_year' and bl='$last_month'))";

            $bulan = "$year-$month";
            $bulan_f = formatTanggal($bulan, 'Y F');
            $last_bulan = "$last_year-$last_month";
            $last_bulan_f = formatTanggal($last_bulan, 'Y F');

            $top_header = array(
                "$last_bulan" => "$last_bulan_f",
                "$bulan"      => "$bulan_f",
            );
            $comp = "th-bl";
            $compFields[] = $comp;
            $sub_title = "bulan $bulan_f";
        }
        elseif (isset($_GET['year'])) {
            $year = $_GET['year'];
            $last_year = $year - 1;

            $conditeCompared = "((th='$year') or (th='$last_year'))";

            $bulan = "$year";
            $bulan_f = $year;

            $last_bulan = "$last_year";
            $last_bulan_f = $last_bulan;

            $top_header = array(
                "$last_bulan" => "$last_bulan_f",
                "$bulan"      => "$bulan_f",
            );
            $comp = "th";
            $compFields[] = $comp;
            $sub_title = "tahun $bulan_f";
        }
        else {
            $year = dtimeNow('Y');
            $month = dtimeNow('m');
            $last_year = $year - 1;
            $last_month = $month;

            $conditeCompared = "((th='$year' and bl='$month') or (th='$last_year' and bl='$last_month'))";

            $bulan = "$year-$month";
            $bulan_f = formatTanggal($bulan, 'Y F');
            $last_bulan = "$last_year-$last_month";
            $last_bulan_f = formatTanggal($last_bulan, 'Y F');

            $top_header = array(
                "$last_bulan" => "$last_bulan_f",
                "$bulan"      => "$bulan_f",
            );
            $comp = "th-bl";
            $compFields[] = $comp;
            $sub_title = "bulan $bulan_f";
        }
        //        cekHitam("tahun $year, bulan $month :: last_tahun $last_year, last_bulan $last_month");


        $reportingNetts = $this->config->item('report')['penjualan'];
        $confReportCabang = $reportingSumCabang = $this->config->item('report')['penjualan_cabang_compared'];
        $confReportSubject = $reportingSumSubject = $reportingSumSeller = $this->config->item('report')['penjualan_seller_compared'];
        $confReportObject = $reportingSumObject = $reportingSumProduct = $this->config->item('report')['penjualan_produk'];
        // $rJmaster = $reportingNetts["returns"]["jenis_master"];

        $fields = array();
        $headers = array();
        $bodies = array();
        $fieldJenis = array();
        foreach ($reportingNetts['mdlFields'] as $field => $fChilds) {
            // arrPrint($fChilds);
            // $headers[] = array();
            // $bodies[] = array();
            $fields[] = $field;
            if (isset($fChilds['label'])) {
                $fieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $fieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $fieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $fieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $fieldFormat[$field] = $fChilds['format'];
            }

        }

        foreach ($reportingSumCabang['mdlFields'] as $field => $fChilds) {
            $cbFields[] = $field;
            if (isset($fChilds['label'])) {
                $cbFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $cbFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $cbFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $cbFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $cbFieldFormat[$field] = $fChilds['format'];
            }
        }


        foreach ($reportingSumSeller['mdlFields'] as $field => $fChilds) {
            // arrPrint($fChilds);
            // $headers[] = array();
            // $bodies[] = array();
            $sFields[] = $field;
            if (isset($fChilds['label'])) {
                $sFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $sFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $sFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $sFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $sFieldFormat[$field] = $fChilds['format'];
            }
        }


        foreach ($reportingSumObject['mdlFields'] as $field => $fChilds) {
            $pFields[] = $field;
            if (isset($fChilds['label'])) {
                $pFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $pFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $pFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $pFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $pFieldFormat[$field] = $fChilds['format'];
            }
            if (isset($fChilds['sum_rows'])) {
                $pFieldSumrows[$field] = $fChilds['sum_rows'];
            }
        }


        $stID = $this->uri->segment(3);
        $sm = new MdlEmployeeCabang();
        $tr = new MdlTransaksi();
        $rp = new MdlReport();
        // $db2 = $this->load->database('report', TRUE);

        // region sales
        $srcSm = $sm->lookupSeller();
        // showLast_query("lime");
        $sMans = $srcSm->raws->result();
        $sKoloms = $srcSm->koloms;
        $slFields = array();
        foreach ($sKoloms as $field => $fieldParams) {
            $slFields[] = $field;
        }

        foreach ($sMans as $sMan) {
            foreach ($slFields as $kolom) {
                $$kolom = $sMan->$kolom;
            }

            $sellers[$id] = $nama;
        }


        $rp->setDebug(true);

        my_cabang_id() > 0 ? $condite["cabang_id"] = my_cabang_id() : $condite = array();


        $rp->setCondites($condite);
        $rp->setConditesCompared($conditeCompared);
        $srScr = $rp->lookupSalesMonthly()->result();
        //        cekKuning(sizeof($srScr));
        //         cekMerah($this->db->last_query());
        // endregion sales

        //         arrPrint($srScr);
        //         arrPrint($sFields);
        //         arrPrint($cbFields);
        //        arrPrint($compFields);
        // arrPrint($pFieldSumrows);
        $koloms = array_unique(array_merge($cbFields, $sFields, $pFields, $compFields));
        $subjects = array();
        $cabangs = array();
        $sumCabang = array();
        $sumCabangs = array();
        $sumSubject = array();
        $sumSubjects = array();
        $sumObject = array();
        $sumObjects = array();
        foreach ($srScr as $dSources) {
            $compEx = explode("-", $comp);
            if (sizeof($compEx) > 0) {
                if (sizeof($compEx) > 1) {
                    $val_add = $dSources->$compEx[0] . "-" . dateDigit($dSources->$compEx[1]);
                }
                else {
                    $val_add = $dSources->$compEx[0];
                }
            }
            else {
                $val_add = "none";
            }
            $dSources->$comp = $val_add;
            //            cekKuning("$comp -- $val_add");
            //            arrPrint($dSources);

            foreach ($koloms as $kolom) {
                $$kolom = trim($dSources->$kolom);
            }


            //region summary cabang
            if (!isset($sumCabangs[$cabang_id][$$comp]["nilai_in"])) {
                $sumCabangs[$cabang_id][$$comp]["nilai_in"] = 0;
            }
            $sumCabangs[$cabang_id][$$comp]["nilai_in"] += $nilai_in;

            if (!isset($sumCabangs[$cabang_id][$$comp]["nilai_ot"])) {
                $sumCabangs[$cabang_id][$$comp]["nilai_ot"] = 0;
            }
            $sumCabangs[$cabang_id][$$comp]["nilai_ot"] += $nilai_ot;

            if (!isset($sumCabangs[$cabang_id][$$comp]["nilai_af"])) {
                $sumCabangs[$cabang_id][$$comp]["nilai_af"] = 0;
            }
            $sumCabangs[$cabang_id][$$comp]["nilai_af"] += $nilai_af;
            //endregion


            //region summary subject
            if (!isset($sumSubjects[$subject_id][$$comp]["nilai_in"])) {
                $sumSubjects[$subject_id][$$comp]["nilai_in"] = 0;
            }
            $sumSubjects[$subject_id][$$comp]["nilai_in"] += $nilai_in;
            if (!isset($sumSubjects[$subject_id][$$comp]["nilai_ot"])) {
                $sumSubjects[$subject_id][$$comp]["nilai_ot"] = 0;
            }
            $sumSubjects[$subject_id][$$comp]["nilai_ot"] += $nilai_ot;
            if (!isset($sumSubjects[$subject_id][$$comp]["nilai_af"])) {
                $sumSubjects[$subject_id][$$comp]["nilai_af"] = 0;
            }
            $sumSubjects[$subject_id][$$comp]["nilai_af"] += $nilai_af;
            //endregion

            //region summary object
            foreach ($pFieldSumrows as $sumField => $sumStat) {

                if (!isset($sumObject[$object_id][$sumField])) {
                    $sumObject[$object_id][$sumField] = 0;
                }
                $sumObject[$object_id][$sumField] += $$sumField;

            }
            //endregion

            $sumCabang[$cabang_id]["cabang_nama"] = $cabang_nama;
            $sumSubject[$subject_id]["subject_nama"] = $subject_nama;
            $sumObject[$object_id]["object_kode"] = $object_kode;
            $sumObject[$object_id]["object_nama"] = $object_nama;
        }

        foreach ($sellers as $sId => $sNama) {
            $sumSubject[$sId]["subject_nama"] = $sNama;
        }

        // arrPrint($sumSubject);

        // region headerCabang
        $cbHeader['no'] = "class='bg-info text-center'";
        foreach ($cbFieldToshows as $kolom => $kolomAlias) {
            $cbHeader[$kolomAlias] = "class='bg-info text-center'";
        }

        // endregion header

        // $summaryCabang = array(
        //   "cabangs" => $cabangs,
        //   "cabangs" => $cabangs,
        // );
        // arrPrint($cabangs);
        //         arrPrint($sumCabang);
        //         arrPrint($sumCabangs);
        // arrPrint($sumSubject);

        $compDatas = $srScr;
        // arrPrint($compDatas);
        // mati_disini();

        // arrPrintWebs($fieldToshows );

        // region header
        $header['no'] = "class='bg-info text-center'";
        foreach ($fieldToshows as $kolom => $kolomAlias) {
            $header[$kolomAlias] = "class='bg-info text-center'";

        }
        // $header['sales'] = "class='bg-info text-center'";
        // $header['returns'] = "class='bg-info text-center'";
        // $header['netto'] = "class='bg-info text-center'";


        // foreach ($steps as $num => $nSpec) {
        //     $stepNames[$nSpec['target']] = $nSpec['label'];
        //     $headers[$num] = $header;
        // }
        // endregion header

        // arrPrint($header);

        // arrPrintWebs($fieldAttr);
        // arrPrintWebs($tmps);
        // mati_disini(__LINE__);
        // arrPrint($fieldToshows);
        // cekHitam(sizeof($compDatas));

        if (sizeof($compDatas) > 0) {
            //region bodies
            $no = 0;
            $netto = 0;
            $sumSale = 0;
            $sumReturn = 0;
            $bodies = array();
            $rSpecs = array();
            foreach ($compDatas as $trJenis => $items) {
                $step_avail = $trJenis;
                $specs = array();


                // arrPrint($item);
                // $step_number = $item->step_number;
                // $tail_code = $item->tail_code;
                // $tail_number = $item->tail_number;
                $no++;
                $specs['no']['value'] = $no;
                $specs['no']['attr'] = "class='text-right'";
                foreach ($fieldToshows as $kolom => $kolomAlias) {

                    if (isset($fieldFormat[$kolom])) {
                        $fValue = $fieldFormat[$kolom]($kolom, $items->$kolom);
                    }
                    else {
                        $fValue = $items->$kolom;
                    }


                    if (isset($fieldLink[$kolom])) {
                        $specs[$kolom] = " < a href = '" . base_url() . $fieldLink[$kolom] . $items['id'] . "' > " . $fValue . "</a > ";
                    }
                    else {

                        $specs[$kolom]['value'] = $fValue;
                    }

                    $warna = (($kolom == "trash") && ($fValue == 0)) ? "text - red" : "";

                    $specs[$kolom]['attr'] = isset($fieldAttr[$kolom]) ? $fieldAttr[$kolom] : "class='text-left $warna'";

                }

                // $referenceID = $regDatas[$transaksi_id]['referenceID'];
                // $nett1 = round($regDatas[$transaksi_id]['nett1'], 0);
                // //region builder saldo berjalan
                // $referenceID == 0 ? $netto += $nett1 : $netto -= $nett1;
                // //endregion

                // // region sales
                // $specs['sales']['value'] = $referenceID == 0 ? formatField("number", $nett1) : 0;
                // $specs['sales']['attr'] = "class='text-right'";
                // $referenceID == 0 ? $sumSale += $nett1 : $sumSale = 0;
                // // endregion sales

                // // region return
                // $specs['return']['value'] = $referenceID > 0 ? formatField("number", $nett1) : 0;
                // $specs['return']['attr'] = "class='text-right'";
                // $referenceID > 0 ? $sumReturn += $nett1 : $sumReturn = 0;
                // // endregion return

                // // region netto berjalan
                // $specs['netto']['value'] = formatField("number", $netto);
                // $specs['netto']['attr'] = "class='text-right'";
                // // endregion netto berjalan

                // arrPrint($specs);
                // arrPrint($steps);
                if ($trJenis == "982") {
                    $rSpecs[] = $specs;
                }
                if ($trJenis == "582spd") {
                    $spdSpecs[] = $specs;
                }
                // $compSpecs = array();
                // foreach ($steps as $num => $nSpec) {
                //     // arrPrint($nSpec);
                //     $nJenis = $nSpec['jenis'];
                //     if ($nJenis == $trJenis) {
                //     // if (($nJenis == $trJenis) && ($trJenis == "582spd") || ($trJenis == "982")) {
                //         if($nJenis == "582spd"){
                //             $spdSpecs = $specs;
                //             // arrPrint($compSpecs);
                //             arrPrint($rSpecs);
                //             cekHere();
                //         }
                //         else{
                //             $compSpecs = $specs;
                //         }
                //
                //         $compSpecs2 = $rSpecs + $spdSpecs;
                //         $bodies[$num][] = $compSpecs2;
                //     }
                //     // else{
                $bodies[] = $specs;
                //     // }
                //     arrPrint($rSpecs);
                // }

            }
            //endregion


            // foreach ($steps as $num => $nSpec) {
            //     $bodies[] = array_merge($spdSpecs, $rSpecs);
            // }
        }
        else {
            $sumSale = 0;
            $sumReturn = 0;
            $bodies = array();
        }
        $footers = array();
        $sumNetto = $sumSale - $sumReturn;
        $jmlFieldToshowa = sizeof($fieldToshows) + 1;
        $footers['summary'] = "class='bg-info text-center' colspan='$jmlFieldToshowa'";
        $footers[formatField('number', $sumSale)] = "class='bg-info text-right'";
        $footers[formatField('number', $sumReturn)] = "class='bg-info text-right'";
        $footers[formatField('number', $sumNetto . ",0")] = "class='bg-info text-right'";

        // arrPrint($rSpecs);
        // arrPrint($spdSpecs);
        // arrPrint($bodies);
        // arrPrint($footers);
        // cekHijau($sumNetto);
        // mati_disini();
        // if(sizeof($bodies[3]) < 1){
        //
        //     $bodies = array();
        // }

        // arrPrint($bodies);
        //         mati_disini();

        // arrPrint($tmps);
        //        arrPrint($top_header);
        //         arrPrint($header);
        //         arrPrint($bodies);
        // foreach ($f?? as $item) {
        //
        // }


        // mati_disini();
        // $bulan = "$year-$month";
        // $bulan_f = formatTanggal($bulan, 'Y F');
        // cekMerah($bulan_f ." ". $bulan);

        //arrPrint($sumSubject);
        //arrPrint($sumSubjects);
        $data = array(
            "mode"     => "viewSalesCompared",
            "title"    => $reportingNetts['title'],
            "subTitle" => "$sub_title",

            "confReportCabang"  => $confReportCabang,
            "confReportSubject" => $confReportSubject,
            "confReportObject"  => $confReportObject,

            "sumCabang"  => $sumCabang,
            "sumSubject" => $sumSubject,
            "sumObject"  => $sumObject,

            "sumCabangs"  => $sumCabangs,
            "sumSubjects" => $sumSubjects,
            "sumObjects"  => $sumObjects,

            "tblTopHeadings" => isset($top_header) ? $top_header : array(),

            "tblHeadings" => $header,
            "tblBodies"   => $bodies,
            "tblFooters"  => $footers,

            "names"   => isset($names) ? $names : array(),
            "jenisTr" => $this->jenisTr,
            "trName"  => "",
        );
        $this->load->view("activityReports", $data);
    }

    //region generate to mongo
    public function generateMongo()
    {
        $this->load->model("Mdls/MdlMongoReport");
        $r = new MdlReport();
        $m = new MdlMongoReport();
        //        matiHEre();
        //list siudah import cabang (penjualan cabang belum ada).ok
        //list siudah import produk.ok
        //list siudah import seller.ok
        //list siudah import customer
        //        matiHEre("hoooppp cek mau ngapain!!");
        matiHEre("hooppp cek dulu gih mau ngapain?");
        $tableNames = $m->getColection()['customer'];//<--- tentukan grup report
        $dataListed = array();
        foreach ($tableNames as $tbl => $tnlAlias) {
            $r->setTabel($tbl);
            $tmpData = $r->lookUpAll()->result();
            if (sizeof($tmpData) > 0) {
                foreach ($tmpData as $data) {
                    $dataListed[$tbl][] = (array)$data;
                }
            }
        }
        //        matiHEre();
        foreach ($dataListed as $iTable => $item) {
            //            arrPrint($item);
            $m->setTableName("$iTable");
            foreach ($item as $data) {
                $m->addData($data);
            }
            //            $data = (array)$item;
            //            if(array_key_exists("tanggal",$data)){
            //                $data["tanggal"] = date_format($data['tanggal'],"Y-m-d");
            //            }
            //            if($i == 1){
            //            $m->addData($data);
            //            }

            //            arrPrint($data);
        }
        echo "Selesaiiii ";
        cekHitam("selesai import ");
        //        arrPrint($tmpSalesCabang);
        //        cekBiru($this->db2->last_query());
        //        matiHEre("selesai");
    }

    public function generateMongoNeraca()
    {
        $this->load->model("Mdls/MdlMongoMother");
        $this->load->model("Mdls/MdlNeraca");
        $r = new MdlNeraca();
        $m = new MdlMongoMother();

        //        $r->setTabel("neraca");
        //        $r->setPeriode("harian");
        $tmpSalesCabang = $r->lookUpAll()->result();
        matiHEre("hooppp cek dulu gih mau ngapain?");
        //        cekLime($this->db->last_query());
        //        arrPrint($tmpSalesCabang);
        //        matiHEre();
        //        matiHEre();
        $m->setTableName("neraca");
        //        matiHEre("ganti table broo ");
        foreach ($tmpSalesCabang as $i => $item) {
            $data = (array)$item;
            //            if(array_key_exists("tanggal",$data)){
            //                $data["tanggal"] = date_format($data['tanggal'],"Y-m-d");
            //            }
            //            if($i == 1){
            $m->addData($data);
            //            }

            //            arrPrint($data);
        }
        cekBiru("kelaaar");
        //        arrPrint($tmpSalesCabang);
        //        cekBiru($this->db2->last_query());
        //        matiHEre("selesai");
    }

    public function generateMongoRugilaba()
    {
        $this->load->model("Mdls/MdlMongoMother");
        $this->load->model("Mdls/MdlRugilaba");
        $r = new MdlRugilaba();
        $m = new MdlMongoMother();

        //        $r->setTabel("neraca");
        //        $r->setPeriode("harian");
        $tmpSalesCabang = $r->lookUpAll()->result();
        //        matiHEre("hooppp cek dulu gih mau ngapain?");
        //        cekLime($this->db->last_query());
        //        arrPrint($tmpSalesCabang);
        //        matiHEre();
        //        matiHEre();
        $m->setTableName($r->getTableName());
        //        matiHEre("ganti table broo ");
        foreach ($tmpSalesCabang as $i => $item) {
            $data = (array)$item;
            //            if(array_key_exists("tanggal",$data)){
            //                $data["tanggal"] = date_format($data['tanggal'],"Y-m-d");
            //            }
            //            if($i == 1){

            $m->addData($data);
            //            }

            //            arrPrint($data);
        }
        //        arrPrint($tmpSalesCabang);
        //        cekBiru($this->db2->last_query());
        //        matiHEre("selesai");
    }

    public function generateFinanceConfig()
    {
        $this->load->model("Mdls/MdlMongoMother");
        $this->load->model("Mdls/MdlFinanceConfig");
        $r = new MdlFinanceConfig();
        $m = new MdlMongoMother();

        //        $r->setTabel("neraca");
        //        $r->setPeriode("harian");
        $tmpSalesCabang = $r->lookUpAll()->result();
        //        matiHEre("hooppp cek dulu gih mau ngapain?");
        //        cekLime($this->db->last_query());
        //        arrPrint($tmpSalesCabang);
        //        matiHEre();
        //        matiHEre();
        $m->setTableName($r->getTableName());
        //        matiHEre("ganti table broo ");
        foreach ($tmpSalesCabang as $i => $item) {
            $data = (array)$item;
            //            if(array_key_exists("tanggal",$data)){
            //                $data["tanggal"] = date_format($data['tanggal'],"Y-m-d");
            //            }
            //            if($i == 1){

            $m->addData($data);
            //            }

            //            arrPrint($data);
        }
        //        arrPrint($tmpSalesCabang);
        //        cekBiru($this->db2->last_query());
        //        matiHEre("selesai");
    }

    public function generateJurnal()
    {
        $this->load->model("Mdls/MdlMongoMother");
        $this->load->model("Coms/ComJurnal");
        $r = new ComJurnal();
        $m = new MdlMongoMother();


        $tmpSalesCabang = $r->lookUpAll()->result();

        $m->setTableName($r->getTableName());

        foreach ($tmpSalesCabang as $i => $item) {
            $data = (array)$item;
            //            if(array_key_exists("tanggal",$data)){
            //                $data["tanggal"] = date_format($data['tanggal'],"Y-m-d");
            //            }
            //            if($i == 1){

            $m->addData($data);
            //            }

            //            arrPrint($data);
        }

    }

    //endregiom
    public function index()
    {
        //      arrPrint($this->uri->segment_array());

        $selctedMethode = $this->uri->segment(3) != null ? $this->uri->segment(3) : "cabang";
        $periode = "tahunan";
        $this->load->model("Mdls/MdlMongoReport");

        $m = new MdlMongoReport();

        //        arrPrint($br->lookupAll()->result());
        //region data cabang
        //        $br->setFilters(array());

        //        matiHEre();
        $navButton = array(
            "cabang"   => array(
                "label"  => "bycabang",
                "action" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/cabang"
            ),
            "produk"   => array(
                "label"  => "by produk",
                "action" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/produk"
            ),
            "salesman" => array(
                "label"  => "by salesman",
                "action" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/salesman"
            ),
            "customer" => array(
                "label"  => "by customer",
                "action" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/customer"
            ),
        );
        $startDate = "2019-01-01";
        $endDate = "2019-12-31";


        //        ---- end custom define harus diupdate biar relative ----
        //region builder aray pertama
        $m->addFilter(array(
                "periode"   => $periode,
                //relative
                "th"        => "2019",
                //relative
                'cabang_id' => array('$gt' => "0"),
                //ini untuk > greather than
            )
        );

        $m->setTableName($this->groupListTable[$selctedMethode]['tableName']['1']);
        $tmpMaster = $m->LookUpAll();
        //arrPrint($tmpMaster);
        //endregion

        //        matiHEre("hoopppp");
        //region shild 1
        $m = new MdlMongoReport();
        $m->setFilters(array());
        $m->addFilter(
            array(
                "periode"   => $periode,
                "th"        => "2019",
                'cabang_id' => array('$gt' => "0"),
                //ini untuk > greather than
            )
        );
        $m->setTableName($this->groupListTable[$selctedMethode]['tableName']['2']);
        $tmpChild1 = $m->lookUpAll();
        $childData1 = array();
        if (sizeof($tmpChild1) > 0) {
            $buildKey = $this->groupListTable[$selctedMethode]['indexKey']['2'];
            foreach ($tmpChild1 as $childDatas) {
                $childData1[$childDatas[$buildKey]][] = $childDatas;
            }
        }
        //endregion

        //region child data2
        $m = new MdlMongoReport();
        $m->setFilters(array());
        $m->addFilter(
            array(
                "periode"   => $periode,
                "th"        => "2019",
                'cabang_id' => array('$gt' => "0"),
                //ini untuk > greather than
            )
        );

        $m->setTableName($this->groupListTable[$selctedMethode]['tableName']['3']);
        $tmpChild2 = $m->lookUpAll();
        //        matiHEre($this->groupListTable[$selctedMethode]['tableName']['3']);
        //        arrPrint($tmpChild2);
        //matiHere();
        $childData2 = array();
        if (sizeof($tmpChild2) > 0) {
            $buildKey = $this->groupListTable[$selctedMethode]['indexKey']['3'];
            foreach ($tmpChild2 as $childDatas2) {
                $childData2[$childDatas2[$buildKey]][] = $childDatas2;
            }
        }

        $arrData = array(
            "1" => $tmpMaster,
            "2" => $childData1,
            "3" => $childData2,
        );
        //        arrPrint($this->selectedFields[$selctedMethode]);
        //        arrPrint($childData2);
        //cekLime(sizeof($childData2));
        //endregion
        $data = array(
            "mode"             => "index",
            "title"            => "Laporan Penjualan",
            "navBtn"           => $navButton,
            "subTitle"         => "",
            "indexKey"         => isset($this->groupListTable[$selctedMethode]['indexKey']) ? $this->groupListTable[$selctedMethode]['indexKey'] : array(),
            "items"            => $arrData,
            "headerFields"     => isset($this->selectedFields[$selctedMethode]) ? $this->selectedFields[$selctedMethode] : array(),
            // "times"            => $months,
            "tblHeadings"      => "",
            "tblBodies"        => "",
            "tblFooters"       => "",
            "names"            => isset($names) ? $names : array(),
            // "recaps"           => $recaps,
            "jenisTr"          => "",
            "trName"           => "",
            // "availFilters"     => $availFilters,
            // "defaultFilter"    => $defaultFilter,
            // "selectedFilter"   => isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter,
            "identifierLabels" => $this->config->item("heTransaksi_report_identifiers"),
            "thisPage"         => base_url() . get_class($this) . " / " . $this->uri->segment(2) . " / " . $this->jenisTr,
            "subPage"          => base_url() . get_class($this) . " / viewDaily / " . $this->jenisTr,
            "historyPage"      => base_url() . "Transaksi / viewHistory / " . $this->jenisTr . " ? stID = ",
            "stepNames"        => "",
            // "defaultStep"      => $defaultStep,
            // "selectedStep"     => $selectedStep,
            // "addLink"          => $addLink,
        );
        $this->load->view("activityReports", $data);
    }

    public function viewYear()
    {
        //tahunan year to date

        $starttime = microtime(true);
        $selctedMethode = $this->uri->segment(3) != null ? $this->uri->segment(3) : "cabang";
        $this->load->model("Mdls/MdlMongoReport");

        $m = new MdlMongoReport();


        $startDate = isset($_GET['date1']) ? $_GET['date1'] : date("Y") - 1;
        //        $endDate = isset($_GET['date2']) ? $_GET['date2'] : date("Y");
        $selectedPeriode = isset($_GET['nav2']) ? $_GET['nav2'] : "th";
        $year = $startDate;
        //        $month = formatTanggal($endDate, 'm');
        $last_year = $year - 1;
        //        $last_month = $month;
        //        cekHitam($selectedPeriode);
        //region date
        $navigateData = array(
            "date1" => $startDate,
            //            "date2" => $endDate,
            "nav2"  => $selectedPeriode
        );

        //endregion
        $filters = array();

        $compare = 1;
        $param = "th";

        $periode = "tahunan";
        $arrTimeSelect = array(
            $last_year,
            $year,
        );
        $curentDate = date("Y");
        if ($curentDate == $year) {
            $inParam = array("$last_year");
        }
        else {
            $inParam = array("$last_year",
                "$year"
            );
        }

        switch ($selctedMethode) {
            case "cabang":
                $filters = array(
                    "periode"    => $periode,
                    //relative
                    'subject_id' => array('$gt' => "0"),
                    //ini untuk > greather than
                    //                    'object_id' => array('$gt' => "0"),//ini untuk > greather than
                );
                break;
            default:
                $filters = array(
                    "periode"    => $periode,
                    //relative
                    'subject_id' => array('$gt' => "0"),
                    //ini untuk > greather than
                    'object_id'  => array('$gt' => "0"),
                    //ini untuk > greather than
                    //                    "th" => $year
                );
                break;
        }
        //        ---- end custom define harus diupdate biar relative ----

        //region data pertama
        $m->setFields(array());
        $m->setTableName($this->groupListTable[$selctedMethode]['tableName']['1']);
        $m->setInParam($inParam);
        $m->setParam($param);
        $m->setFilters($filters);
        $tmpMaster = $m->LookUpAll();
        //        print_r($tmpMaster);
        //        arrPrint($inParam);

        $headerField = $this->selectedFields[$selctedMethode]['1']['headerFields'];
        $headerField2 = $this->selectedFields[$selctedMethode]['1']['headerFields2'];
        $selectFieldTime = isset($this->selectedFields[$selctedMethode]['1']['subject_date'][$periode]) ? $this->selectedFields[$selctedMethode]['1']['subject_date'][$periode] : "";
        $selectFieldData = isset($this->selectedFields[$selctedMethode]['1']['selected_fields']) ? $this->selectedFields[$selctedMethode]['1']['selected_fields'] : array();
        $masterIndex = isset($this->selectedFields[$selctedMethode]['1']['masterIndex']) ? $this->selectedFields[$selctedMethode]['1']['masterIndex'] : "";
        $dataMainValues = array();
        $dataSumValues = array();
        $dataSumFooterValues = array();
        $dataMain = array();


        //        cekHitam($linkDetil);
        foreach ($tmpMaster as $tmp) {
            if ($compare) {
                foreach ($headerField as $s => $sAlias) {
                    //                    $dataMainValues[$tmp[$selectFieldTime]][$tmp[$s]] = $tmp;
                    foreach ($headerField2 as $k => $alias) {
                        if (!isset($tmp[$k])) {
                            $tmp[$k] = 0;
                        }
                        if (!isset($tmp[$selectFieldTime])) {
                            $tmp[$selectFieldTime] = 0;
                        }
                        if (!isset($dataSumFooterValues[$k][$tmp[$selectFieldTime]])) {
                            $dataSumFooterValues[$k][$tmp[$selectFieldTime]] = 0;
                        }
                        if (!isset($dataMainValues[$tmp[$selectFieldTime]][$tmp[$s]][$k])) {
                            $dataMainValues[$tmp[$selectFieldTime]][$tmp[$s]][$k] = 0;
                        }
                        $dataSumFooterValues[$k][$tmp[$selectFieldTime]] += $tmp[$k];
                        $dataMainValues[$tmp[$selectFieldTime]][$tmp[$s]][$k] = $tmp[$k];
                    }

                }
            }

            foreach ($selectFieldData as $keyMaster => $tmpFildsData) {
                foreach ($tmpFildsData as $fields) {
                    $dataMain[$keyMaster][$tmp[$keyMaster]][$fields] = $tmp[$fields];
                }
                foreach ($headerField2 as $h => $hLabel) {
                    if (!isset($tmp[$h])) {
                        $tmp[$h] = 0;
                    }
                    if (!isset($tmp[$keyMaster])) {
                        $tmp[$keyMaster] = 0;
                    }
                    if (!isset($dataSumValues[$tmp[$keyMaster]][$h])) {
                        $dataSumValues[$tmp[$keyMaster]][$h] = 0;
                    }
                    $dataSumValues[$tmp[$keyMaster]][$h] += $tmp[$h];
                }
            }
        }

        $itemMaster = array(
            "mainValues"    => $dataMainValues,
            "mainData"      => $dataMain,
            "mainSumValues" => $dataSumValues,
            "sumFooter"     => $dataSumFooterValues,
            "title"         => isset($this->selectedFields[$selctedMethode][1]['titleMain']) ? $this->selectedFields[$selctedMethode][1]['titleMain'] . " <small class='text-red'><em>komparasi th $last_year - $year</em></small>" : "",
            "subtitle"      => "periode $periode (tahun $year)",
            "sumfield"      => isset($this->selectedFields[$selctedMethode][1]['sumFields']) ? $this->selectedFields[$selctedMethode][1]['sumFields'] : array(),
        );
        //        arrPrint($itemMaster);
        //endregion

        //region data ke dua detil
        $tmpChild = array();
        if (isset($this->groupListTable[$selctedMethode]['tableName']['2'])) {
            $m->setFilters(array());
            $m->setFields(array());
            $m->setTableName($this->groupListTable[$selctedMethode]['tableName']['2']);
            $m->setInParam($inParam);
            $m->setParam($param);
            $m->setFilters($filters);
            $tmpChild = $m->LookUpAll();
            //            arrPrint($filters);
            //            arrPrint($inParam);
            //            cekLime($param);
        }
        //        arrPrint($tmpChild);
        $headerFieldChild = $this->selectedFields[$selctedMethode]['2']['headerFields'];
        $headerFieldChild2 = $this->selectedFields[$selctedMethode]['2']['headerFields2'];
        $indexField2 = $this->selectedFields[$selctedMethode]['2']['index2'];
        $selectFieldTime = isset($this->selectedFields[$selctedMethode]['2']['subject_date'][$periode]) ? $this->selectedFields[$selctedMethode]['2']['subject_date'][$periode] : "";
        $selectFieldDataChild = isset($this->selectedFields[$selctedMethode]['2']['selected_fields']) ? $this->selectedFields[$selctedMethode]['2']['selected_fields'] : array();
        $masterIndex = isset($this->selectedFields[$selctedMethode]['2']['masterIndex']) ? $this->selectedFields[$selctedMethode]['2']['masterIndex'] : "";
        //        [produk][cabang][unit][th] = array("unit_af"=>2,"nilai_af"=>"50000")
        $childData = array();
        $chilValues = array();
        $chilDataIndex2 = array();
        $chilValusIndex2 = array();
        $dataSumValues2 = array();
        $dataSumFooterValues2 = array();
        if (sizeof($tmpChild) > 0) {
            foreach ($tmpChild as $tmpChild_0) {
                foreach ($selectFieldDataChild as $pID => $pidName) {
                    foreach ($pidName as $gateLabel) {
                        $childData[$tmpChild_0[$pID]][$gateLabel] = $tmpChild_0[$gateLabel];
                    }
                }

                foreach ($indexField2 as $ind2Key => $ind2_alias) {
                    $chilDataIndex2[$tmpChild_0[$ind2Key]] = $tmpChild_0[$ind2_alias];
                    //                    if(!isset($tmpChild_0[$ind2Key]))
                    foreach ($headerFieldChild2 as $f_key => $f_alias) {
                        if (!isset($tmpChild_0[$f_key])) {
                            $tmpChild_0[$f_key] = 0;
                        }
                        $chilValusIndex2[$tmpChild_0[$masterIndex]][$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]] = $tmpChild_0[$f_key];
                        if (!isset($dataSumFooterValues2[$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]])) {
                            $dataSumFooterValues2[$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]] = 0;
                        }
                        if (!isset($dataSumFooterValues2['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]])) {
                            $dataSumFooterValues2['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]] = 0;
                        }

                        $dataSumFooterValues2[$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]] += $tmpChild_0[$f_key];
                        $dataSumFooterValues2['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]] += $tmpChild_0[$f_key];
                    }

                }
                foreach ($headerFieldChild2 as $f_key => $f_alias) {
                    if (!isset($chilValusIndex2[$tmpChild_0[$masterIndex]]['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]])) {
                        $chilValusIndex2[$tmpChild_0[$masterIndex]]['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]] = 0;
                    }

                    $chilValusIndex2[$tmpChild_0[$masterIndex]]['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]] += $tmpChild_0[$f_key];

                }

            }
        }
        $itemMaster2 = array(
            "mainValues" => $chilValusIndex2,
            "mainData"   => $childData,
            "mainIndex2" => $chilDataIndex2,
            "title"      => isset($this->selectedFields[$selctedMethode][2]['titleMain']) ? $this->selectedFields[$selctedMethode][2]['titleMain'] . " <small class='text-red'><em>komparasi th $last_year - $year</em></small>" : "",
            "subtitle"   => "periode $periode (tahun $year)",
            "sumfield"   => isset($this->selectedFields[$selctedMethode][2]['sumFields']) ? $this->selectedFields[$selctedMethode][2]['sumFields'] : array(),
            "sumFooter"  => $dataSumFooterValues2,
        );
        //endregion

        //region data ke tiga detil
        $itemMaster3 = array();
        if (isset($this->groupListTable[$selctedMethode]['tableName']['3'])) {
            $m->setFilters(array());
            $m->setFields(array());
            $m->setTableName($this->groupListTable[$selctedMethode]['tableName']['3']);
            $m->setInParam($inParam);
            $m->setParam($param);
            $m->setFilters($filters);

            $tmpChild3 = $m->LookUpAll();
            //        arrPrint($tmpChild);
            $headerFieldChild3 = $this->selectedFields[$selctedMethode]['3']['headerFields'];
            $headerFieldChild_3_inde = $this->selectedFields[$selctedMethode]['3']['headerFields2'];
            $indexField3 = $this->selectedFields[$selctedMethode]['3']['index2'];
            $selectFieldTime3 = isset($this->selectedFields[$selctedMethode]['3']['subject_date'][$periode]) ? $this->selectedFields[$selctedMethode]['3']['subject_date'][$periode] : "";
            $selectFieldDataChild3 = isset($this->selectedFields[$selctedMethode]['3']['selected_fields']) ? $this->selectedFields[$selctedMethode]['3']['selected_fields'] : array();
            $masterIndex3 = isset($this->selectedFields[$selctedMethode]['3']['masterIndex']) ? $this->selectedFields[$selctedMethode]['3']['masterIndex'] : "";
            //        [produk][cabang][unit][th] = array("unit_af"=>2,"nilai_af"=>"50000")
            $childData3 = array();
            $chilValues = array();
            $chilDataIndex3 = array();
            $chilValusIndex3 = array();
            $dataSumFooterValues3 = array();
            if (sizeof($tmpChild3) > 0) {
                foreach ($tmpChild3 as $tmpChild_0) {
                    foreach ($selectFieldDataChild3 as $pID => $pidName) {
                        foreach ($pidName as $gateLabel) {
                            $childData3[$tmpChild_0[$pID]][$gateLabel] = $tmpChild_0[$gateLabel];
                        }
                    }
                    foreach ($indexField3 as $ind2Key => $ind2_alias) {
                        $chilDataIndex3[$tmpChild_0[$ind2Key]] = $tmpChild_0[$ind2_alias];

                        foreach ($headerFieldChild_3_inde as $f_key => $f_alias) {
                            if (!isset($chilValusIndex3[$tmpChild_0[$masterIndex]][$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]])) {
                                $chilValusIndex3[$tmpChild_0[$masterIndex]][$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]] = 0;
                            }
                            if (!isset($chilValusIndex3[$tmpChild_0[$masterIndex]]['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]])) {
                                $chilValusIndex3[$tmpChild_0[$masterIndex]]['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]] = 0;
                            }
                            $chilValusIndex3[$tmpChild_0[$masterIndex]][$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]] += $tmpChild_0[$f_key];
                            $chilValusIndex3[$tmpChild_0[$masterIndex]]['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]] += $tmpChild_0[$f_key];

                            if (!isset($dataSumFooterValues3[$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]])) {
                                $dataSumFooterValues3[$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]] = 0;
                            }
                            if (!isset($dataSumFooterValues3['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]])) {
                                $dataSumFooterValues3['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]] = 0;
                            }
                            $dataSumFooterValues3[$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]] += $tmpChild_0[$f_key];
                            $dataSumFooterValues3['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]] += $tmpChild_0[$f_key];

                        }
                    }
                }
            }

            //            arrPrintWebs($dataSumFooterValues3);
            $itemMaster3 = array(
                "mainValues" => $chilValusIndex3,
                "mainData"   => $childData3,
                "mainIndex2" => $chilDataIndex3,
                "title"      => isset($this->selectedFields[$selctedMethode][3]['titleMain']) ? $this->selectedFields[$selctedMethode][3]['titleMain'] . "<small class='text-red'><em>komparasi th $last_year - $year</em></small>" : "",
                "subtitle"   => "periode $periode (tahun $year)",
                //                "sumfield" => isset($this->selectedFields[$selctedMethode][3]['sumFields']) ? $this->selectedFields[$selctedMethode][3]['sumFields'] : array(),
                "sumFooter"  => $dataSumFooterValues3,
            );
        }

        //endregion

        //region data ke empat detil
        $itemMaster4 = array();
        if (isset($this->groupListTable[$selctedMethode]['tableName']['4'])) {
            $m->setFilters(array());
            $m->setFields(array());
            $m->setTableName($this->groupListTable[$selctedMethode]['tableName']['4']);
            $m->setInParam($inParam);
            $m->setParam($param);
            $m->setFilters(array("periode"    => $periode,
                //relative
                                 'subject_id' => array('$gt' => "0"),
                //ini untuk > greather than
                                 'object_id'  => array('$gt' => "0"),
                //ini untuk > greather than
            ));

            $tmpChild4 = $m->LookUpAll();

            $headerFieldChild4 = $this->selectedFields[$selctedMethode]['4']['headerFields'];
            $headerFieldChild_4_inde = $this->selectedFields[$selctedMethode]['4']['headerFields2'];
            $indexField4 = $this->selectedFields[$selctedMethode]['4']['index2'];
            $selectFieldTime4 = isset($this->selectedFields[$selctedMethode]['4']['subject_date'][$periode]) ? $this->selectedFields[$selctedMethode]['3']['subject_date'][$periode] : "";
            $selectFieldDataChild4 = isset($this->selectedFields[$selctedMethode]['4']['selected_fields']) ? $this->selectedFields[$selctedMethode]['3']['selected_fields'] : array();
            $masterIndex3 = isset($this->selectedFields[$selctedMethode]['4']['masterIndex']) ? $this->selectedFields[$selctedMethode]['3']['masterIndex'] : "";
            //        [produk][cabang][unit][th] = array("unit_af"=>2,"nilai_af"=>"50000")
            $childData4 = array();
            $chilValues = array();
            $chilDataIndex4 = array();
            $chilValusIndex4 = array();
            $dataSumFooterValues4 = array();
            if (sizeof($tmpChild4) > 0) {
                foreach ($tmpChild4 as $tmpChild_0) {
                    foreach ($selectFieldDataChild4 as $pID => $pidName) {
                        foreach ($pidName as $gateLabel) {
                            $childData4[$tmpChild_0[$pID]][$gateLabel] = $tmpChild_0[$gateLabel];
                        }
                    }
                    foreach ($indexField4 as $ind2Key => $ind2_alias) {
                        $chilDataIndex4[$tmpChild_0[$ind2Key]] = $tmpChild_0[$ind2_alias];
                        //                    if(!isset($tmpChild_0[$ind2Key]))
                        foreach ($headerFieldChild_4_inde as $f_key => $f_alias) {
                            if (!isset($chilValusIndex4[$tmpChild_0[$masterIndex]][$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]])) {
                                $chilValusIndex4[$tmpChild_0[$masterIndex]][$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]] = 0;
                            }

                            if (!isset($chilValusIndex4[$tmpChild_0[$masterIndex]]['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]])) {
                                $chilValusIndex4[$tmpChild_0[$masterIndex]]['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]] = 0;
                            }
                            //                        $chilValusIndex2[$tmpChild_0[$masterIndex]][$tmpChild_0[$ind2Key]][$tmpChild_0[$selectFieldTime]][] = $tmpChild_0;
                            $chilValusIndex4[$tmpChild_0[$masterIndex]][$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]] += $tmpChild_0[$f_key];
                            $chilValusIndex4[$tmpChild_0[$masterIndex]]['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]] += $tmpChild_0[$f_key];

                            if (!isset($dataSumFooterValues4[$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]])) {
                                $dataSumFooterValues4[$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]] = 0;
                            }
                            if (!isset($dataSumFooterValues4[$tmpChild_0[$selectFieldTime]]['subtotal'][$f_key])) {
                                $dataSumFooterValues4[$tmpChild_0[$selectFieldTime]]['subtotal'][$f_key] = 0;
                            }

                            $dataSumFooterValues4[$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]] += $tmpChild_0[$f_key];
                            $dataSumFooterValues4[$tmpChild_0[$selectFieldTime]]['subtotal'][$f_key] += $tmpChild_0[$f_key];

                        }
                        //                        foreach ($headerFieldChild3 as $f_key => $f_alias) {
                        //                            if (!isset($chilValusIndex2[$tmpChild_0[$masterIndex]]['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]])) {
                        //                                $chilValusIndex2[$tmpChild_0[$masterIndex]]['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]] = 0;
                        //                            }
                        //
                        //                            $chilValusIndex2[$tmpChild_0[$masterIndex]]['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]] += $tmpChild_0[$f_key];
                        //
                        //                        }
                    }

                }
            }
            $itemMaster4 = array(
                "mainValues" => $chilValusIndex4,
                "mainData"   => $childData4,
                "mainIndex2" => $chilDataIndex4,
                "title"      => isset($this->selectedFields[$selctedMethode][4]['titleMain']) ? $this->selectedFields[$selctedMethode][4]['titleMain'] . "<small class='text-red'><em>komparasi th $last_year - $year</em></small>" : "",
                "subtitle"   => "periode $periode (tahun $year)",
                "sumfield"   => isset($this->selectedFields[$selctedMethode][4]['sumFields']) ? $this->selectedFields[$selctedMethode][4]['sumFields'] : array(),
                "sumFooter"  => $dataSumFooterValues4,
            );
        }

        //endregion

        $endtime = microtime(true); // Bottom of page
        $val = $endtime - $starttime;


        $items = array(
            1 => $itemMaster,
            2 => $itemMaster2,
            3 => isset($itemMaster3) ? $itemMaster3 : array(),
            4 => isset($itemMaster4) ? $itemMaster4 : array(),
        );
        $itemHeaderFields = array(
            1 => array(
                "headerField"     => $headerField,
                "selectFieldTime" => $selectFieldTime,
                "selectFieldData" => $selectFieldData,
                "header2"         => $this->selectedFields[$selctedMethode][1]['headerFields2'],
            ),
            2 => array(
                "headerField"     => $headerFieldChild,
                "selectFieldTime" => $selectFieldTime,
                "selectFieldData" => $selectFieldDataChild,
                "header2"         => $this->selectedFields[$selctedMethode][2]['headerFields2'],
                "index2"          => $this->selectedFields[$selctedMethode][2]['index2'],
            ),
            3 => array(
                "headerField"     => isset($headerFieldChild3) ? $headerFieldChild3 : array(),
                "selectFieldTime" => $selectFieldTime,
                "selectFieldData" => isset($selectFieldDataChild3) ? $selectFieldDataChild3 : array(),
                "header2"         => isset($this->selectedFields[$selctedMethode][3]['headerFields2']) ? $this->selectedFields[$selctedMethode][3]['headerFields2'] : array(),
                "index2"          => isset($this->selectedFields[$selctedMethode][3]['index2']) ? $this->selectedFields[$selctedMethode][3]['index2'] : array(),
            ),
            4 => array(
                "headerField"     => isset($headerFieldChild4) ? $headerFieldChild4 : array(),
                "selectFieldTime" => $selectFieldTime,
                "selectFieldData" => isset($selectFieldDataChild4) ? $selectFieldDataChild4 : array(),
                "header2"         => isset($this->selectedFields[$selctedMethode][4]['headerFields2']) ? $this->selectedFields[$selctedMethode][4]['headerFields2'] : array(),
                "index2"          => isset($this->selectedFields[$selctedMethode][4]['index2']) ? $this->selectedFields[$selctedMethode][4]['index2'] : array(),
            ),
        );

        $data = array(
            "mode"  => "viewYear",
            "title" => "Laporan Penjualan Komparasi",

            "navBtn"       => $this->grupReport,
            "subTitle"     => "",
            "indexKey"     => isset($this->groupListTable[$selctedMethode]['indexKey']) ? $this->groupListTable[$selctedMethode]['indexKey'] : array(),
            "itemsMain"    => $items,
            "itemsPeriode" => $arrTimeSelect,
            "headerFields" => $itemHeaderFields,
            "navGate"      => $navigateData,
            "names"        => isset($names) ? $names : array(),
            "thisPage"     => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            "historyPage"  => base_url() . "Transaksi / viewHistory / " . $this->jenisTr . " ? stID = ",
            "stepNames"    => "",
            "periode"      => $this->periode,
            "detilLink"    => $this->linkDetail[$this->uri->segment(2)],
        );
        $this->load->view("reports", $data);
    }

    public function viewYearly()
    {
        //tahun berjalan year to date

        $starttime = microtime(true);
        $selctedMethode = $this->uri->segment(3) != null ? $this->uri->segment(3) : "cabang";
        $this->load->model("Mdls/MdlMongoReport");

        $m = new MdlMongoReport();


        $startDate = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
        $endDate = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
        $selectedPeriode = isset($_GET['nav2']) ? $_GET['nav2'] : "ytd";
        $year = formatTanggal($endDate, 'Y');
        $month = formatTanggal($endDate, 'm');
        $last_year = $year - 1;
        $last_month = $month;
        //        cekHitam($selectedPeriode);
        //region date
        $navigateData = array(
            "date1" => $startDate,
            "date2" => $endDate,
            "nav2"  => $selectedPeriode
        );

        //endregion
        $filters = array();

        $compare = true;
        $param = "th";


        $periode1 = "bulanan";
        $periode = isset($_GET['nav2']) ? $_GET['nav2'] : "ytd";
        //        $periode = "tahunan";
        $arrTimeSelect = array(
            $last_year,
            $year,
        );
        $curentDate = date("Y");

        $inParam = array("$last_year",
            "$year"
        );

        switch ($selctedMethode) {
            case "cabang":
                $filters = array(
                    "periode"    => $periode1,
                    //relative
                    'subject_id' => array('$gt' => "0"),
                    //ini untuk > greather than
                );
                break;

            default:
                $filters = array(
                    "periode"    => $periode1,
                    //relative
                    'subject_id' => array('$gt' => "0"),
                    //ini untuk > greather than
                    //                    "th" => $year
                );
                break;

        }
        //        ---- end custom define harus diupdate biar relative ----

        //region data pertama

        $m->setTableName($this->groupListTable[$selctedMethode]['tableName']['1']);
        $m->setInParam($inParam);
        $m->setParam($param);
        $m->addFilter($filters);
        $m->setFields(array());
        $tmpMaster = $m->LookUpAll();
        //        arrPrint($tmpMaster);

        $headerField = $this->selectedFields[$selctedMethode]['1']['headerFields'];
        $headerField2 = $this->selectedFields[$selctedMethode]['1']['headerFields2'];
        $selectFieldTime = isset($this->selectedFields[$selctedMethode]['1']['subject_date'][$periode]) ? $this->selectedFields[$selctedMethode]['1']['subject_date'][$periode] : "";
        $selectFieldData = isset($this->selectedFields[$selctedMethode]['1']['selected_fields']) ? $this->selectedFields[$selctedMethode]['1']['selected_fields'] : array();
        //       arrPrint($selectFieldData);
        $dataMainValues = array();
        $dataSumValues = array();
        $dataSumFooterValues = array();
        $dataMain = array();
        foreach ($tmpMaster as $tmp) {
            if ($compare) {
                foreach ($headerField as $s => $sAlias) {
                    //                    $dataMainValues[$tmp[$selectFieldTime]][$tmp[$s]] = $tmp;
                    foreach ($headerField2 as $k => $alias) {
                        if (!isset($tmp[$k])) {
                            $tmp[$k] = 0;
                        }
                        if (!isset($tmp[$selectFieldTime])) {
                            $tmp[$selectFieldTime] = 0;
                        }
                        if (!isset($dataSumFooterValues[$k][$tmp[$selectFieldTime]])) {
                            $dataSumFooterValues[$k][$tmp[$selectFieldTime]] = 0;
                        }
                        if (!isset($dataMainValues[$tmp[$selectFieldTime]][$tmp[$s]][$k])) {
                            $dataMainValues[$tmp[$selectFieldTime]][$tmp[$s]][$k] = 0;
                        }
                        $dataSumFooterValues[$k][$tmp[$selectFieldTime]] += $tmp[$k];
                        $dataMainValues[$tmp[$selectFieldTime]][$tmp[$s]][$k] += $tmp[$k];
                    }

                }
            }

            foreach ($selectFieldData as $keyMaster => $tmpFildsData) {
                foreach ($tmpFildsData as $fields) {
                    $dataMain[$keyMaster][$tmp[$keyMaster]][$fields] = $tmp[$fields];
                }
                foreach ($headerField2 as $h => $hLabel) {
                    if (!isset($tmp[$h])) {
                        $tmp[$h] = 0;
                    }
                    if (!isset($tmp[$keyMaster])) {
                        $tmp[$keyMaster] = 0;
                    }
                    if (!isset($dataSumValues[$tmp[$keyMaster]][$h])) {
                        $dataSumValues[$tmp[$keyMaster]][$h] = 0;
                    }
                    $dataSumValues[$tmp[$keyMaster]][$h] += $tmp[$h];
                }
            }
        }
        //        arrPrint($dataSumValues);
        $itemMaster = array(
            "mainValues"    => $dataMainValues,
            "mainData"      => $dataMain,
            "mainSumValues" => $dataSumValues,
            "sumFooter"     => $dataSumFooterValues,
            "title"         => isset($this->selectedFields[$selctedMethode][1]['titleMain']) ? $this->selectedFields[$selctedMethode][1]['titleMain'] . " <small class='text-red'><em>komparasi th $last_year - $year</em></small>" : "",
            "subtitle"      => "periode tahun berjalan (tahun $year)",
            "sumfield"      => isset($this->selectedFields[$selctedMethode][1]['sumFields']) ? $this->selectedFields[$selctedMethode][1]['sumFields'] : array(),
        );
        //endregion

        //region data ke dua detil
        $tmpChild = array();
        if (isset($this->groupListTable[$selctedMethode]['tableName']['2'])) {
            $m->setFilters(array());
            $m->setTableName($this->groupListTable[$selctedMethode]['tableName']['2']);
            $m->setInParam($inParam);
            $m->setParam($param);
            $m->setFilters($filters);
            $tmpChild = $m->LookUpAll();

        }
        //        arrPrint($tmpChild);
        $headerFieldChild = $this->selectedFields[$selctedMethode]['2']['headerFields'];
        $headerFieldChild2 = $this->selectedFields[$selctedMethode]['2']['headerFields2'];
        $indexField2 = $this->selectedFields[$selctedMethode]['2']['index2'];
        $selectFieldTime = isset($this->selectedFields[$selctedMethode]['2']['subject_date'][$periode]) ? $this->selectedFields[$selctedMethode]['2']['subject_date'][$periode] : "";
        $selectFieldDataChild = isset($this->selectedFields[$selctedMethode]['2']['selected_fields']) ? $this->selectedFields[$selctedMethode]['2']['selected_fields'] : array();
        $masterIndex = isset($this->selectedFields[$selctedMethode]['2']['masterIndex']) ? $this->selectedFields[$selctedMethode]['2']['masterIndex'] : "";
        //        [produk][cabang][unit][th] = array("unit_af"=>2,"nilai_af"=>"50000")
        //        cekBiru($selectFieldTime." $periode");
        //        matiHEre();
        //        arrPrint();
        $childData = array();
        $chilValues = array();
        $chilDataIndex2 = array();
        $chilValusIndex2 = array();
        $dataSumValues2 = array();
        $dataSumFooterValues2 = array();
        $valSubtotal = array();
        if (sizeof($tmpChild) > 0) {
            foreach ($tmpChild as $tmpChild_0) {
                foreach ($selectFieldDataChild as $pID => $pidName) {
                    foreach ($pidName as $gateLabel) {
                        $childData[$tmpChild_0[$pID]][$gateLabel] = $tmpChild_0[$gateLabel];
                    }
                }
                foreach ($indexField2 as $ind2Key => $ind2_alias) {
                    $chilDataIndex2[$tmpChild_0[$ind2Key]] = $tmpChild_0[$ind2_alias];
                    //                    if(!isset($tmpChild_0[$ind2Key]))
                    foreach ($headerFieldChild2 as $f_key => $f_alias) {
                        if (!isset($chilValusIndex2[$tmpChild_0[$masterIndex]][$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]])) {
                            $chilValusIndex2[$tmpChild_0[$masterIndex]][$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]] = 0;
                        }
                        $chilValusIndex2[$tmpChild_0[$masterIndex]][$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]] += $tmpChild_0[$f_key];
                        if (!isset($dataSumFooterValues2[$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]])) {
                            $dataSumFooterValues2[$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]] = 0;
                        }
                        if (!isset($dataSumFooterValues2['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]])) {
                            $dataSumFooterValues2['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]] = 0;
                        }

                        $dataSumFooterValues2[$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]] += $tmpChild_0[$f_key];
                        $dataSumFooterValues2['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]] += $tmpChild_0[$f_key];
                    }

                }
                $valsub = array();
                $valuesum = 0;
                foreach ($headerFieldChild2 as $f_key => $f_alias) {
                    if (!isset($chilValusIndex2[$tmpChild_0[$masterIndex]]['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]])) {
                        $chilValusIndex2[$tmpChild_0[$masterIndex]]['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]] = 0;
                    }

                    $chilValusIndex2[$tmpChild_0[$masterIndex]]['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]] += $tmpChild_0[$f_key];

                }
                $valSubtotal[$tmpChild_0[$masterIndex]] = $valsub;

            }
        }
        //        arrPrint($valSubtotal);
        $itemMaster2 = array(
            "mainValues" => $chilValusIndex2,
            "mainData"   => $childData,
            "mainIndex2" => $chilDataIndex2,
            "title"      => isset($this->selectedFields[$selctedMethode][2]['titleMain']) ? $this->selectedFields[$selctedMethode][2]['titleMain'] . " <small class='text-red'><em>komparasi th $last_year - $year</em></small>" : "",
            "subtitle"   => "periode tahun berjalan (tahun $year)",
            "sumfield"   => isset($this->selectedFields[$selctedMethode][2]['sumFields']) ? $this->selectedFields[$selctedMethode][2]['sumFields'] : array(),
            "sumFooter"  => $dataSumFooterValues2,
        );
        //endregion


        //region data ke tiga detil
        $itemMaster3 = array();
        if (isset($this->groupListTable[$selctedMethode]['tableName']['3'])) {
            //            cekHere("tabel ke 3: " . $this->groupListTable[$selctedMethode]['tableName']['3']);
            $m->setFields(array());
            $m->setFilters(array());
            $m->setTableName($this->groupListTable[$selctedMethode]['tableName']['3']);
            $m->setInParam($inParam);
            $m->setParam($param);
            $m->setFilters(array(
                'subject_id' => array(
                    '$gt' => "0"
                    //cari yang cabang bukan pusat
                ),
                "periode"    => $periode1,
                //relative
            ));

            $tmpChild3 = $m->LookUpAll();
            //arrPrint($tmpChild3);
            $headerFieldChild3 = $this->selectedFields[$selctedMethode]['3']['headerFields'];
            $headerFieldChild_3_inde = $this->selectedFields[$selctedMethode]['3']['headerFields2'];
            $indexField3 = $this->selectedFields[$selctedMethode]['3']['index2'];
            $selectFieldTime3 = isset($this->selectedFields[$selctedMethode]['3']['subject_date'][$periode]) ? $this->selectedFields[$selctedMethode]['3']['subject_date'][$periode] : "";
            $selectFieldDataChild3 = isset($this->selectedFields[$selctedMethode]['3']['selected_fields']) ? $this->selectedFields[$selctedMethode]['3']['selected_fields'] : array();
            $masterIndex3 = isset($this->selectedFields[$selctedMethode]['3']['masterIndex']) ? $this->selectedFields[$selctedMethode]['3']['masterIndex'] : "";
            //        [produk][cabang][unit][th] = array("unit_af"=>2,"nilai_af"=>"50000")
            //cekHere(":: selectFields: $selectFieldTime :: selectFields3: $selectFieldTime3 :: masterIndex: $masterIndex :: masterIndex3: $masterIndex3");
            $childData3 = array();
            $chilValues = array();
            $chilDataIndex3 = array();
            $chilValusIndex3 = array();
            $dataSumFooterValues3 = array();
            if (sizeof($tmpChild3) > 0) {
                foreach ($tmpChild3 as $tmpChild_0) {
                    //                    arrPrintWebs($tmpChild_0);
                    foreach ($selectFieldDataChild3 as $pID => $pidName) {
                        foreach ($pidName as $gateLabel) {
                            $childData3[$tmpChild_0[$pID]][$gateLabel] = $tmpChild_0[$gateLabel];
                        }
                    }
                    foreach ($indexField3 as $ind2Key => $ind2_alias) {
                        $chilDataIndex3[$tmpChild_0[$ind2Key]] = $tmpChild_0[$ind2_alias];

                        foreach ($headerFieldChild_3_inde as $f_key => $f_alias) {
                            if (!isset($tmpChild_0[$f_key])) {
                                $tmpChild_0[$f_key] = 0;
                            }
                            if (!isset($chilValusIndex3[$tmpChild_0[$masterIndex]][$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]])) {
                                $chilValusIndex3[$tmpChild_0[$masterIndex]][$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]] = 0;
                            }
                            if (!isset($chilValusIndex3[$tmpChild_0[$masterIndex]]['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]])) {
                                $chilValusIndex3[$tmpChild_0[$masterIndex]]['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]] = 0;
                            }

                            $chilValusIndex3[$tmpChild_0[$masterIndex]][$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]] += $tmpChild_0[$f_key];
                            $chilValusIndex3[$tmpChild_0[$masterIndex]]['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]] += $tmpChild_0[$f_key];

                            if (!isset($dataSumFooterValues3[$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]])) {
                                $dataSumFooterValues3[$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]] = 0;
                            }
                            if (!isset($dataSumFooterValues3['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]])) {
                                $dataSumFooterValues3['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]] = 0;
                            }
                            $dataSumFooterValues3[$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]] += $tmpChild_0[$f_key];
                            $dataSumFooterValues3['subtotal'][$f_key][$tmpChild_0[$selectFieldTime]] += $tmpChild_0[$f_key];


                        }
                    }

                }
            }
            //            arrPrintWebs($dataSumFooterValues3);
            $itemMaster3 = array(
                "mainValues" => $chilValusIndex3,
                "mainData"   => $childData3,
                "mainIndex2" => $chilDataIndex3,
                "title"      => isset($this->selectedFields[$selctedMethode][3]['titleMain']) ? $this->selectedFields[$selctedMethode][3]['titleMain'] . "<small class='text-red'><em>komparasi th $last_year - $year</em></small>" : "",
                "subtitle"   => "periode $periode (tahun $year)",
                "sumfield"   => isset($this->selectedFields[$selctedMethode][3]['sumFields']) ? $this->selectedFields[$selctedMethode][3]['sumFields'] : array(),
                "sumFooter"  => $dataSumFooterValues3,
            );
        }

        //endregion

        //region data ke enpat detil
        $itemMaster4 = array();
        if (isset($this->groupListTable[$selctedMethode]['tableName']['4'])) {
            $m->setFields(array());
            $m->setFilters(array());
            $m->setTableName($this->groupListTable[$selctedMethode]['tableName']['4']);
            $m->setInParam($inParam);
            $m->setParam($param);
            $m->setFilters(array(
                'subject_id' => array(
                    '$gt' => "0"
                    //cari yang cabang bukan pusat
                ),
                'object_id'  => array(
                    '$gt' => "0"
                    //cari yang cabang bukan pusat
                ),
                "periode"    => $periode1,
                //relative
            ));

            $tmpChild4 = $m->LookUpAll();

            //            $numb=1;
            //            $tmpChild4_ = array();
            //            foreach($tmpChild4 as $kky => $reessul){if($numb<25){$tmpChild4_[$kky] = $reessul;}$numb++;}
            //            $tmpChild4 = $tmpChild4_;

            $headerFieldChild4 = $this->selectedFields[$selctedMethode]['4']['headerFields'];
            $headerFieldChild_4_inde = $this->selectedFields[$selctedMethode]['4']['headerFields2'];
            $indexField4 = $this->selectedFields[$selctedMethode]['4']['index2'];
            $selectFieldTime4 = isset($this->selectedFields[$selctedMethode]['4']['subject_date'][$periode]) ? $this->selectedFields[$selctedMethode]['4']['subject_date'][$periode] : "";
            $selectFieldDataChild4 = isset($this->selectedFields[$selctedMethode]['4']['selected_fields']) ? $this->selectedFields[$selctedMethode]['4']['selected_fields'] : array();
            $masterIndex4 = isset($this->selectedFields[$selctedMethode]['4']['masterIndex']) ? $this->selectedFields[$selctedMethode]['4']['masterIndex'] : "";

            $childData4 = array();
            $chilValues = array();
            $chilDataIndex4 = array();
            $chilValusIndex4 = array();
            $dataSumFooterValues4 = array();

            if (sizeof($tmpChild4) > 0) {
                foreach ($tmpChild4 as $tmpChild_0) {
                    foreach ($selectFieldDataChild4 as $pID => $pidName) {
                        foreach ($pidName as $gateLabel) {
                            $childData4[$tmpChild_0[$pID]][$gateLabel] = $tmpChild_0[$gateLabel];
                        }
                    }
                    foreach ($indexField4 as $ind2Key => $ind2_alias) {
                        $chilDataIndex4[$tmpChild_0[$ind2Key]] = $tmpChild_0[$ind2_alias];
                        foreach ($headerFieldChild_4_inde as $f_key => $f_alias) {
                            if (!isset($tmpChild_0[$f_key])) {
                                $tmpChild_0[$f_key] = 0;
                            }
                            if (!isset($chilValusIndex4[$tmpChild_0[$masterIndex4]][$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime4]])) {
                                $chilValusIndex4[$tmpChild_0[$masterIndex4]][$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime4]] = 0;
                            }
                            if (!isset($chilValusIndex4[$tmpChild_0[$masterIndex4]]['subtotal'][$f_key][$tmpChild_0[$selectFieldTime4]])) {
                                $chilValusIndex4[$tmpChild_0[$masterIndex4]]['subtotal'][$f_key][$tmpChild_0[$selectFieldTime4]] = 0;
                            }
                            $chilValusIndex4[$tmpChild_0[$masterIndex4]][$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime4]] += $tmpChild_0[$f_key];
                            $chilValusIndex4[$tmpChild_0[$masterIndex4]]['subtotal'][$f_key][$tmpChild_0[$selectFieldTime4]] += $tmpChild_0[$f_key];
                            if (!isset($dataSumFooterValues4[$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime4]])) {
                                $dataSumFooterValues4[$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime4]] = 0;
                            }
                            if (!isset($dataSumFooterValues4['subtotal'][$f_key][$tmpChild_0[$selectFieldTime4]])) {
                                $dataSumFooterValues4['subtotal'][$f_key][$tmpChild_0[$selectFieldTime4]] = 0;
                            }
                            $dataSumFooterValues4[$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime4]] += $tmpChild_0[$f_key];
                            $dataSumFooterValues4['subtotal'][$f_key][$tmpChild_0[$selectFieldTime4]] += $tmpChild_0[$f_key];
                        }
                    }
                }
            }
            $itemMaster4 = array(
                "mainValues" => $chilValusIndex4,
                "mainData"   => $childData4,
                "mainIndex2" => $chilDataIndex4,
                "title"      => isset($this->selectedFields[$selctedMethode][4]['titleMain']) ? $this->selectedFields[$selctedMethode][4]['titleMain'] . "<small class='text-red'><em>komparasi th $last_year - $year</em></small>" : "",
                "subtitle"   => "periode $periode (tahun $year)",
                "sumfield"   => isset($this->selectedFields[$selctedMethode][4]['sumFields']) ? $this->selectedFields[$selctedMethode][4]['sumFields'] : array(),
                "sumFooter"  => $dataSumFooterValues4,
            );
        }
        //endregion

        $endtime = microtime(true); // Bottom of page
        $val = $endtime - $starttime;
        $items = array(
            1 => $itemMaster,
            2 => $itemMaster2,
            3 => isset($itemMaster3) ? $itemMaster3 : array(),
            4 => isset($itemMaster4) ? $itemMaster4 : array(),
        );
        $itemHeaderFields = array(
            1 => array(
                "headerField"     => $headerField,
                "selectFieldTime" => $selectFieldTime,
                "selectFieldData" => $selectFieldData,
                "header2"         => $this->selectedFields[$selctedMethode][1]['headerFields2'],
            ),
            2 => array(
                "headerField"     => $headerFieldChild,
                "selectFieldTime" => $selectFieldTime,
                "selectFieldData" => $selectFieldDataChild,
                "header2"         => $this->selectedFields[$selctedMethode][2]['headerFields2'],
                "index2"          => $this->selectedFields[$selctedMethode][2]['index2'],
            ),
            3 => array(
                "headerField"     => isset($headerFieldChild3) ? $headerFieldChild3 : array(),
                "selectFieldTime" => $selectFieldTime,
                "selectFieldData" => isset($selectFieldDataChild3) ? $selectFieldDataChild3 : array(),
                "header2"         => isset($this->selectedFields[$selctedMethode][3]['headerFields2']) ? $this->selectedFields[$selctedMethode][3]['headerFields2'] : array(),
                "index2"          => isset($this->selectedFields[$selctedMethode][3]['index2']) ? $this->selectedFields[$selctedMethode][3]['index2'] : array(),
            ),
            4 => array(
                "headerField"     => isset($headerFieldChild4) ? $headerFieldChild4 : array(),
                "selectFieldTime" => $selectFieldTime,
                "selectFieldData" => isset($selectFieldDataChild4) ? $selectFieldDataChild4 : array(),
                "header2"         => isset($this->selectedFields[$selctedMethode][4]['headerFields2']) ? $this->selectedFields[$selctedMethode][4]['headerFields2'] : array(),
                "index2"          => isset($this->selectedFields[$selctedMethode][4]['index2']) ? $this->selectedFields[$selctedMethode][4]['index2'] : array(),
            ),
        );
        $data = array(
            "mode"         => "viewYearly",
            "title"        => "Laporan Penjualan Komparasi(netto)",
            "navBtn"       => $this->grupReport,
            "subTitle"     => "",
            "indexKey"     => isset($this->groupListTable[$selctedMethode]['indexKey']) ? $this->groupListTable[$selctedMethode]['indexKey'] : array(),
            "itemsMain"    => $items,
            "itemsPeriode" => $arrTimeSelect,
            "headerFields" => $itemHeaderFields,
            "navGate"      => $navigateData,
            "names"        => isset($names) ? $names : array(),
            "thisPage"     => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            "historyPage"  => base_url() . "Transaksi / viewHistory / " . $this->jenisTr . " ? stID = ",
            "stepNames"    => "",
            "periode"      => $this->periode,
            "detilLink"    => $this->linkDetail[$this->uri->segment(2)],
        );
        $this->load->view("reports", $data);
    }

    public function index_rev___()
    {
        //      arrPrint($this->uri->segment_array());

        //        $selctedMethode = $this->uri->segment(3) != null ? $this->uri->segment(3) : "cabang";
        $selctedMethode = $this->uri->segment(3) != null ? $this->uri->segment(3) : "produk";
        $periode = "tahunan";
        $this->load->model("Mdls/MdlMongoReport");

        $m = new MdlMongoReport();

        $navButton = array(
            "cabang"   => array(
                "label"  => "bycabang",
                "action" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/cabang"
            ),
            "produk"   => array(
                "label"  => "by produk",
                "action" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/produk"
            ),
            "salesman" => array(
                "label"  => "by salesman",
                "action" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/salesman"
            ),
            "customer" => array(
                "label"  => "by customer",
                "action" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/customer"
            ),
        );
        $startDate = "2019-01-01";
        $endDate = "2019-12-31";


        //        ---- end custom define harus diupdate biar relative ----
        $compFields = array();
        if (isset($_GET['date'])) {
            $year = formatTanggal($_GET['date'], 'Y');
            $month = formatTanggal($_GET['date'], 'm');
            $last_year = $year - 1;
            $last_month = formatTanggal($_GET['date'], 'm');

            $conditeCompared = "((th='$year' and bl='$month') or (th='$last_year' and bl='$last_month'))";

            $bulan = "$year-$month";
            $bulan_f = formatTanggal($bulan, 'Y F');
            $last_bulan = "$last_year-$last_month";
            $last_bulan_f = formatTanggal($last_bulan, 'Y F');

            $top_header = array(
                "$last_bulan" => "$last_bulan_f",
                "$bulan"      => "$bulan_f",
            );
            $comp = "th-bl";
            $compFields[] = $comp;
            $sub_title = "bulan $bulan_f";
        }
        elseif (isset($_GET['year'])) {
            $year = $_GET['year'];
            $last_year = $year - 1;

            $conditeCompared = "((th='$year') or (th='$last_year'))";

            $bulan = "$year";
            $bulan_f = $year;

            $last_bulan = "$last_year";
            $last_bulan_f = $last_bulan;

            $top_header = array(
                "$last_bulan" => "$last_bulan_f",
                "$bulan"      => "$bulan_f",
            );
            $comp = "th";
            $compFields[] = $comp;
            $sub_title = "tahun $bulan_f";
        }
        else {
            //            $year = dtimeNow('Y');
            //            $month = dtimeNow('m');
            //            $last_year = $year - 1;
            //            $last_month = $month;
            $year = "2020";
            $month = "12";
            $last_year = "2019";
            $last_month = "11";
            $conditeCompared = "((th='$year' and bl='$month') or (th='$last_year' and bl='$last_month'))";
            $conditeCompared = array(
                "periode" => $periode,

            );
            switch ($selctedMethode) {
                case "cabang":
                    if ($periode == "tahunan") {
                        $inParam = array("2019",
                            "2020"
                        );
                        $param = "th";
                        $filters = array(
                            "periode"   => $periode,
                            //relative
                            'cabang_id' => array('$gt' => "0"),
                            //ini untuk > greather than
                        );
                    }

                    //                    $m->addFilter(array(
                    //                            "periode" => $periode,//relative
                    ////                            "th" => "2019",//relative
                    //                            'cabang_id' => array('$gt' => "0"),//ini untuk > greather than
                    //                        )
                    //                    );

                    break;
                case "produk":
                    if ($periode == "tahunan") {
                        $inParam = array("2019",
                            "2020"
                        );
                        $param = "th";
                        $filters = array(
                            "periode" => $periode,
                            //relative
                            //                            'subject_id' => array('$gt' => "0"),//ini untuk > greather than
                        );
                    }
                    break;
                case "salesman":
                    $m->addFilter(array(
                            "periode"   => $periode,
                            //relative
                            "th"        => "2019",
                            //relative
                            "cabang_id" => "0"
                            //ini untuk > greather than
                        )
                    );
                    break;
            }
            $bulan = "$year-$month";
            $bulan_f = formatTanggal($bulan, 'Y F');
            $last_bulan = "$last_year-$last_month";
            $last_bulan_f = formatTanggal($last_bulan, 'Y F');

            $top_header = array(
                "$last_bulan" => "$last_bulan_f",
                "$bulan"      => "$bulan_f",
            );
            $comp = "th-bl";
            $compFields[] = $comp;
            $sub_title = "bulan $bulan_f";
        }
        //region builder aray pertama


        //        $m->setShortBy(array(
        //            "cabang_id"=>"desc",
        //        ));
        //region selector periode
        $perodeSelected = "bulanan";
        //        $perodeSelected = "3_bulanan";
        $perodeSelected = "tahunan";
        //        $perodeSelected = "yaertodate";

        switch ($perodeSelected) {
            case "bulanan":
                $labelMont = namaBulan2();
                //                arrPRint($labelMont);
                $i = 1;
                $keyPeriode = array();
                for ($i; $i <= 12; $i++) {
                    $keyPeriode[$i] = $labelMont[$i];
                }
                break;
            case "tahunan":
                //                $year = "2020";
                //                $month = "12";
                //                $last_year = "2019";
                //                $last_month = "11";
                ////                $curentDate = "2019";
                $arrTimeSelect = array(
                    $last_year,
                    $year,
                );
                break;

            case "tri_wulan":
                $i = 3;
                $keyPeriode = array();
                //                for ($i; $i <= 12; $i++) {
                //                    $keyPeriode[$i] = $labelMont[$i];
                //                }
                break;
            case "semester":

                break;
        }

        //endregion

        $compare = $periode == "tahunan" ? true : false;

        //region data pertama
        $m->setTableName($this->groupListTable[$selctedMethode]['tableName']['1']);
        $m->setInParam($inParam);
        $m->setParam($param);
        $m->setFilters($filters);
        $tmpMaster = $m->LookUpAll();
        //        arrPrint($tmpMaster);
        $headerField = $this->selectedFields[$selctedMethode]['1']['headerFields'];
        $selectFieldTime = isset($this->selectedFields[$selctedMethode]['1']['subject_date'][$periode]) ? $this->selectedFields[$selctedMethode]['1']['subject_date'][$periode] : "";
        $selectFieldData = isset($this->selectedFields[$selctedMethode]['1']['selected_fields']) ? $this->selectedFields[$selctedMethode]['1']['selected_fields'] : array();
        $dataMainValues = array();
        $dataSumValues = array();
        $dataMain = array();
        foreach ($tmpMaster as $tmp) {
            if ($compare) {
                foreach ($headerField as $s => $sAlias) {
                    $dataMainValues[$tmp[$selectFieldTime]][$tmp[$s]] = $tmp;
                    //                    $dataSumValues[$tmp[$selectFieldTime]][$tmp[$s]]['nilai_af'] += $tmp['nilai_af'];
                }

            }

            foreach ($selectFieldData as $keyMaster => $tmpFildsData) {
                foreach ($tmpFildsData as $fields) {
                    $dataMain[$keyMaster][$tmp[$keyMaster]][$fields] = $tmp[$fields];
                }
            }
        }
        $itemMaster = array(
            "mainValues"    => $dataMainValues,
            "mainData"      => $dataMain,
            "mainSumValues" => "",

        );
        //endregion
        matiHere();

        //region data ke dua detil
        $m->setFilters(array());
        $m->setTableName($this->groupListTable[$selctedMethode]['tableName']['2']);
        $m->setInParam($inParam);
        $m->setParam($param);
        $m->setFilters(array(
            'subject_id' => array(
                '$gt' => "0"
                //cari yang cabang bukan pusat
            ),
        ));

        $tmpChild = $m->LookUpAll();
        //        arrPrint($tmpChild);
        $headerFieldChild = $this->selectedFields[$selctedMethode]['2']['headerFields'];
        $headerFieldChild2 = $this->selectedFields[$selctedMethode]['2']['headerFields2'];
        $indexField2 = $this->selectedFields[$selctedMethode]['2']['index2'];
        $selectFieldTime = isset($this->selectedFields[$selctedMethode]['2']['subject_date'][$periode]) ? $this->selectedFields[$selctedMethode]['2']['subject_date'][$periode] : "";
        $selectFieldDataChild = isset($this->selectedFields[$selctedMethode]['2']['selected_fields']) ? $this->selectedFields[$selctedMethode]['2']['selected_fields'] : array();
        $masterIndex = isset($this->selectedFields[$selctedMethode]['2']['masterIndex']) ? $this->selectedFields[$selctedMethode]['2']['masterIndex'] : "";
        $dataMainValues = array();
        $dataSumValues = array();
        $dataMain = array();
        //arrPrint($tmpChild);
        //        [produk][cabang][unit][th] = array("unit_af"=>2,"nilai_af"=>"50000")
        $childData = array();
        $chilValues = array();
        $chilDataIndex2 = array();
        $chilValusIndex2 = array();
        if (sizeof($tmpChild) > 0) {
            foreach ($tmpChild as $tmpChild_0) {
                foreach ($selectFieldDataChild as $pID => $pidName) {
                    foreach ($pidName as $gateLabel) {
                        $childData[$tmpChild_0[$pID]][$gateLabel] = $tmpChild_0[$gateLabel];
                    }
                }
                foreach ($indexField2 as $ind2Key => $ind2_alias) {
                    $chilDataIndex2[$tmpChild_0[$ind2Key]] = $tmpChild_0[$ind2_alias];
                    //                    if(!isset($tmpChild_0[$ind2Key]))
                    foreach ($headerFieldChild2 as $f_key => $f_alias) {
                        if (!isset($tmpChild_0[$f_key])) {
                            $tmpChild_0[$f_key] = 0;
                        }
                        //                        $chilValusIndex2[$tmpChild_0[$masterIndex]][$tmpChild_0[$ind2Key]][$tmpChild_0[$selectFieldTime]][] = $tmpChild_0;
                        $chilValusIndex2[$tmpChild_0[$masterIndex]][$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]] = $tmpChild_0[$f_key];


                    }
                }

            }
        }
        //        arrPrint($chilDataIndex2);
        $itemMaster2 = array(
            "mainValues" => $chilValusIndex2,
            "mainData"   => $childData,
            "mainIndex2" => $chilDataIndex2,
        );
        //endregion

        //region data ke tiga detil
        $itemMaster3 = array();
        if (isset($this->groupListTable[$selctedMethode]['tableName']['3'])) {
            $m->setFilters(array());
            $m->setTableName($this->groupListTable[$selctedMethode]['tableName']['3']);
            $m->setInParam($inParam);
            $m->setParam($param);
            $m->setFilters(array(
                'subject_id' => array(
                    '$gt' => "0"
                    //cari yang cabang bukan pusat
                ),
            ));

            $tmpChild3 = $m->LookUpAll();
            //        arrPrint($tmpChild);
            $headerFieldChild3 = $this->selectedFields[$selctedMethode]['3']['headerFields'];
            $headerFieldChild_3_inde = $this->selectedFields[$selctedMethode]['3']['headerFields2'];
            $indexField3 = $this->selectedFields[$selctedMethode]['3']['index2'];
            $selectFieldTime3 = isset($this->selectedFields[$selctedMethode]['3']['subject_date'][$periode]) ? $this->selectedFields[$selctedMethode]['3']['subject_date'][$periode] : "";
            $selectFieldDataChild3 = isset($this->selectedFields[$selctedMethode]['3']['selected_fields']) ? $this->selectedFields[$selctedMethode]['3']['selected_fields'] : array();
            $masterIndex3 = isset($this->selectedFields[$selctedMethode]['3']['masterIndex']) ? $this->selectedFields[$selctedMethode]['3']['masterIndex'] : "";
            //        [produk][cabang][unit][th] = array("unit_af"=>2,"nilai_af"=>"50000")
            $childData3 = array();
            $chilValues = array();
            $chilDataIndex3 = array();
            $chilValusIndex3 = array();
            if (sizeof($tmpChild3) > 0) {
                foreach ($tmpChild3 as $tmpChild_0) {
                    foreach ($selectFieldDataChild3 as $pID => $pidName) {
                        foreach ($pidName as $gateLabel) {
                            $childData3[$tmpChild_0[$pID]][$gateLabel] = $tmpChild_0[$gateLabel];
                        }
                    }
                    foreach ($indexField3 as $ind2Key => $ind2_alias) {
                        $chilDataIndex3[$tmpChild_0[$ind2Key]] = $tmpChild_0[$ind2_alias];
                        //                    if(!isset($tmpChild_0[$ind2Key]))
                        foreach ($headerFieldChild_3_inde as $f_key => $f_alias) {
                            if (!isset($tmpChild_0[$f_key])) {
                                $tmpChild_0[$f_key] = 0;
                            }
                            //                        $chilValusIndex2[$tmpChild_0[$masterIndex]][$tmpChild_0[$ind2Key]][$tmpChild_0[$selectFieldTime]][] = $tmpChild_0;
                            $chilValusIndex3[$tmpChild_0[$masterIndex]][$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]] = $tmpChild_0[$f_key];


                        }
                    }

                }
            }
            $itemMaster3 = array(
                "mainValues" => $chilValusIndex3,
                "mainData"   => $childData3,
                "mainIndex2" => $chilDataIndex3,
            );
        }

        //endregion
        $items = array(
            1 => $itemMaster,
            2 => $itemMaster2,
            3 => $itemMaster3,
        );
        $itemHeaderFields = array(
            1 => array(
                "headerField"     => $headerField,
                "selectFieldTime" => $selectFieldTime,
                "selectFieldData" => $selectFieldData,
                "header2"         => $this->selectedFields[$selctedMethode][1]['headerFields2'],
            ),
            2 => array(
                "headerField"     => $headerFieldChild,
                "selectFieldTime" => $selectFieldTime,
                "selectFieldData" => $selectFieldDataChild,
                "header2"         => $this->selectedFields[$selctedMethode][2]['headerFields2'],
                "index2"          => $this->selectedFields[$selctedMethode][2]['index2'],
            ),
            3 => array(
                "headerField"     => $headerFieldChild3,
                "selectFieldTime" => $selectFieldTime,
                "selectFieldData" => $selectFieldDataChild3,
                "header2"         => $this->selectedFields[$selctedMethode][3]['headerFields2'],
                "index2"          => $this->selectedFields[$selctedMethode][3]['index2'],
            ),
        );

        //arrPrint($itemMaster2);

        $data = array(
            "mode"  => "index_rev",
            "title" => "Laporan Penjualan",

            "navBtn"            => $navButton,
            "subTitle"          => "",
            "indexKey"          => isset($this->groupListTable[$selctedMethode]['indexKey']) ? $this->groupListTable[$selctedMethode]['indexKey'] : array(),
            "itemsMain"         => $items,
            "itemsPeriode"      => $arrTimeSelect,
            "secondIndexFields" => "",
            "sumfields"         => "",
            "sumFoter"          => array(),
            "headerFields"      => $itemHeaderFields,
            // "times"            => $months,
            "tblHeadings"       => "",
            "tblBodies"         => "",
            "tblFooters"        => "",
            "names"             => isset($names) ? $names : array(),
            // "recaps"           => $recaps,
            "jenisTr"           => "",
            "trName"            => "",
            // "availFilters"     => $availFilters,
            // "defaultFilter"    => $defaultFilter,
            // "selectedFilter"   => isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter,
            "identifierLabels"  => $this->config->item("heTransaksi_report_identifiers"),
            "thisPage"          => base_url() . get_class($this) . " / " . $this->uri->segment(2) . " / " . $this->jenisTr,
            "subPage"           => base_url() . get_class($this) . " / viewDaily / " . $this->jenisTr,
            "historyPage"       => base_url() . "Transaksi / viewHistory / " . $this->jenisTr . " ? stID = ",
            "stepNames"         => "",
            // "defaultStep"      => $defaultStep,
            // "selectedStep"     => $selectedStep,
            // "addLink"          => $addLink,
        );
        $this->load->view("activityReports", $data);
    }

    public function viewMonthly()
    {
        //tahun berjalan year to date

        $starttime = microtime(true);
        $selctedMethode = $this->uri->segment(3) != null ? $this->uri->segment(3) : "cabang";
        $this->load->model("Mdls/MdlMongoReport");

        $m = new MdlMongoReport();

        $startDate = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
        $endDate = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
        $selectedPeriode = isset($_GET['nav2']) ? $_GET['nav2'] : "th";
        $year = formatTanggal($startDate, 'Y');
        $month = formatTanggal($startDate, 'm');

        $last_year = $year - 1;
        $last_month = $month;
        //        cekHitam($selectedPeriode);
        //region date
        $navigateData = array(
            "date1" => $startDate,
            "date2" => $endDate,
            "nav2"  => $selectedPeriode
        );

        //endregion
        $filters = array();

        $compare = false;
        $periode = "bulanan";
        $arrTimeSelect = namaBulan2();

        $curentDate = date("Y");

        $inParam = array("$last_year",
            "$year"
        );

        switch ($selctedMethode) {
            case "cabang":
                $filters = array(
                    "periode"    => $periode,
                    //relative
                    "th"         => $year,
                    'subject_id' => array('$gt' => "0"),
                    //ini untuk > greather than
                    //                    'bl' => array('$lte' => $month), //ini untuk lebih kecil sama dengan (bl <='12')
                );
                break;
            default:
                $filters = array(
                    "periode"    => $periode,
                    //relative
                    'subject_id' => array('$gt' => "0"),
                    //ini untuk > greather than
                    "th"         => $year,
                    //                    'bl' => array('$lte' => $month),
                );
                break;
        }
        //        arrPrint($periode);
        //        arrPrint($filters);
        //        ---- end custom define harus diupdate biar relative ----

        //region data pertama
        //cekHitam($this->groupListTable[$selctedMethode]['tableName']['1']);
        $m->setFields(array());
        $m->setTableName($this->groupListTable[$selctedMethode]['tableName']['1']);

        $m->setFilters($filters);
        $m->setFields(array());
        $tmpMaster = $m->LookUpAll();
        //        arrPRint($tmpMaster);
        //        showLast_query("lime");
        //        arrPrint($tmpMaster);
        //        matiHere();
        $headerField = $this->selectedFields[$selctedMethode]['1']['headerFields'];
        $headerField2 = $this->selectedFields[$selctedMethode]['1']['headerFields2'];
        $selectFieldTime = isset($this->selectedFields[$selctedMethode]['1']['subject_date'][$periode]) ? $this->selectedFields[$selctedMethode]['1']['subject_date'][$periode] : "";
        $selectFieldData = isset($this->selectedFields[$selctedMethode]['1']['selected_fields']) ? $this->selectedFields[$selctedMethode]['1']['selected_fields'] : array();
        $dataMainValues = array();
        $dataSumValues = array();
        $dataSumFooterValues = array();
        $dataMain = array();

        //        $itemMaster = array();
        //        arrPRint($headerField2);
        //        if(sizeof($tmpMaster)>0){
        foreach ($tmpMaster as $tmp) {
            //            if ($compare) {
            foreach ($headerField as $s => $sAlias) {
                //                cekHitam($s);
                //                    $dataMainValues[$tmp[$selectFieldTime]][$tmp[$s]] = $tmp;
                foreach ($headerField2 as $k => $alias) {
                    if (!isset($tmp[$k])) {
                        $tmp[$k] = 0;
                    }
                    if (!isset($tmp[$selectFieldTime])) {
                        $tmp[$selectFieldTime] = 0;
                    }
                    if (!isset($dataSumFooterValues[$k][$tmp[$selectFieldTime]])) {
                        $dataSumFooterValues[$k][$tmp[$selectFieldTime]] = 0;
                    }
                    if (!isset($dataMainValues[$tmp[$s]][$tmp[$selectFieldTime]][$k])) {
                        $dataMainValues[$tmp[$s]][$tmp[$selectFieldTime]][$k] = 0;
                    }
                    //                    if (!isset($dataSumFooterValues2[$s]['subtotal'][$k])) {
                    //                        $dataSumFooterValues2[$s]['subtotal'][$k] = 0;
                    //                    }
                    //                    $dataSumFooterValues2[$s]['subtotal'][$k] +=$tmp[$k];
                    $dataSumFooterValues[$k][$tmp[$selectFieldTime]] += $tmp[$k];
                    $dataMainValues[$tmp[$s]][$tmp[$selectFieldTime]][$k] = $tmp[$k];
                }


            }
            //            }else{

            //            }

            foreach ($selectFieldData as $keyMaster => $tmpFildsData) {
                foreach ($tmpFildsData as $fields) {
                    $dataMain[$keyMaster][$tmp[$keyMaster]][$fields] = $tmp[$fields];
                }
                foreach ($headerField2 as $h => $hLabel) {
                    if (!isset($tmp[$h])) {
                        $tmp[$h] = 0;
                    }
                    if (!isset($tmp[$keyMaster])) {
                        $tmp[$keyMaster] = 0;
                    }
                    if (!isset($dataSumValues[$tmp[$keyMaster]]['subtotal'][$h])) {
                        $dataSumValues[$tmp[$keyMaster]]['subtotal'][$h] = 0;
                    }
                    $dataSumValues[$tmp[$keyMaster]]['subtotal'][$h] += $tmp[$h];
                }
            }
        }

        //        arrPrint($dataSumValues);

        //        }

        $itemMaster = array(
            "mainValues"    => $dataMainValues,
            "mainData"      => $dataMain,
            "mainSumValues" => $dataSumValues,
            "sumFooter"     => $dataSumFooterValues,
            "title"         => isset($this->selectedFields[$selctedMethode][1]['titleMain']) ? $this->selectedFields[$selctedMethode][1]['titleMain'] . " <small class='text-red'><em>1# $periode - $year</em></small>" : "",
            "subtitle"      => "periode Januari - " . $arrTimeSelect[$month] . " (tahun $year)",
            "sumfield"      => isset($this->selectedFields[$selctedMethode][1]['sumFields']) ? $this->selectedFields[$selctedMethode][1]['sumFields'] : array(),
        );
        //endregion


        //region data ke dua detil
        $tmpChild = array();

        if (isset($this->groupListTable[$selctedMethode]['tableName']['2'])) {
            $m->setFilters(array());
            $m->setTableName($this->groupListTable[$selctedMethode]['tableName']['2']);
            //            $m->setInParam($inParam);
            //            $m->setParam($param);
            $m->setFilters($filters);
            $tmpChild = $m->LookUpAll();
            //            arrPrint($filters);
            //            arrPrint($inParam);
            //            cekLime($param);
        }
        //        arrPrint($tmpChild);
        $headerFieldChild = $this->selectedFields[$selctedMethode]['2']['headerFields'];
        $headerFieldChild2 = $this->selectedFields[$selctedMethode]['2']['headerFields2'];
        $indexField2 = $this->selectedFields[$selctedMethode]['2']['index2'];
        $selectFieldTime = isset($this->selectedFields[$selctedMethode]['2']['subject_date'][$periode]) ? $this->selectedFields[$selctedMethode]['2']['subject_date'][$periode] : "";
        $selectFieldDataChild = isset($this->selectedFields[$selctedMethode]['2']['selected_fields']) ? $this->selectedFields[$selctedMethode]['2']['selected_fields'] : array();
        $masterIndex = isset($this->selectedFields[$selctedMethode]['2']['masterIndex']) ? $this->selectedFields[$selctedMethode]['2']['masterIndex'] : "";
        //        [produk][cabang][unit][th] = array("unit_af"=>2,"nilai_af"=>"50000")
        $childData = array();
        $chilValues = array();
        $chilDataIndex2 = array();
        $chilValusIndex2 = array();
        $dataSumValues2 = array();
        $dataSumFooterValues2 = array();
        if (sizeof($tmpChild) > 0) {
            foreach ($tmpChild as $tmpChild_0) {
                foreach ($selectFieldDataChild as $pID => $pidName) {
                    foreach ($pidName as $gateLabel) {
                        $childData[$tmpChild_0[$pID]][$gateLabel] = $tmpChild_0[$gateLabel];
                    }
                }

                foreach ($indexField2 as $ind2Key => $ind2_alias) {
                    $chilDataIndex2[$tmpChild_0[$ind2Key]] = $tmpChild_0[$ind2_alias];
                    //                    if(!isset($tmpChild_0[$ind2Key]))
                    //[produk][periode][qty][cb]
                    foreach ($headerFieldChild2 as $f_key => $f_alias) {
                        if (!isset($tmpChild_0[$f_key])) {
                            $tmpChild_0[$f_key] = 0;
                        }
                        if (!isset($chilValusIndex2[$tmpChild_0[$masterIndex]][$tmpChild_0[$selectFieldTime]][$f_key][$tmpChild_0[$ind2Key]])) {
                            $chilValusIndex2[$tmpChild_0[$masterIndex]][$tmpChild_0[$selectFieldTime]][$f_key][$tmpChild_0[$ind2Key]] = 0;
                        }
                        $chilValusIndex2[$tmpChild_0[$masterIndex]][$tmpChild_0[$selectFieldTime]][$f_key][$tmpChild_0[$ind2Key]] += $tmpChild_0[$f_key];

                        //BELUM VALID BROOOOO chepy 26-12-2020 20.14
                        if (!isset($dataSumFooterValues2[$tmpChild_0[$selectFieldTime]][$f_key][$tmpChild_0[$ind2Key]])) {
                            $dataSumFooterValues2[$tmpChild_0[$selectFieldTime]][$f_key][$tmpChild_0[$ind2Key]] = 0;
                        }
                        $dataSumFooterValues2[$tmpChild_0[$selectFieldTime]][$f_key][$tmpChild_0[$ind2Key]] += $tmpChild_0[$f_key];

                        if (!isset($dataSumFooterValues2['subtotal'][$f_key][$tmpChild_0[$ind2Key]])) {
                            $dataSumFooterValues2['subtotal'][$f_key][$tmpChild_0[$ind2Key]] = 0;
                        }

                        $dataSumFooterValues2['subtotal'][$f_key][$tmpChild_0[$ind2Key]] += $tmpChild_0[$f_key];
                        //                        cekHijau($tmpChild_0[$masterIndex]); //produknya
                        //                        cekOrange($tmpChild_0[$selectFieldTime]); //bulannya-
                        //                        cekBiru($f_key); //key nilai-
                        //                        cekPink($tmpChild_0[$ind2Key]); //cabangnya-

                    }

                }
                foreach ($headerFieldChild2 as $f_key => $f_alias) {
                    if (!isset($chilValusIndex2[$tmpChild_0[$masterIndex]]['subtotal'][$f_key][$tmpChild_0[$ind2Key]])) {
                        $chilValusIndex2[$tmpChild_0[$masterIndex]]['subtotal'][$f_key][$tmpChild_0[$ind2Key]] = 0;
                    }

                    $chilValusIndex2[$tmpChild_0[$masterIndex]]['subtotal'][$f_key][$tmpChild_0[$ind2Key]] += $tmpChild_0[$f_key];


                }

            }
        }
        //[prodykID][periode][qty][cabang]=val
        //        arrPRint($chilValusIndex2);
        //        matiHEre();
        $itemMaster2 = array(
            "mainValues"   => $chilValusIndex2,
            "mainData"     => $childData,
            "mainIndex2"   => $chilDataIndex2,
            "title"        => isset($this->selectedFields[$selctedMethode][2]['titleMain']) ? $this->selectedFields[$selctedMethode][2]['titleMain'] . " <small class='text-red'><em>2# $periode - $year</em></small>" : "",
            "subtitle"     => "periode tahun berjalan (tahun $year)",
            "sumfield"     => isset($this->selectedFields[$selctedMethode][2]['sumFields']) ? $this->selectedFields[$selctedMethode][2]['sumFields'] : array(),
            "sumFooter"    => $dataSumFooterValues2,
            "paramPeriode" => $arrTimeSelect,
            //            "subTotal" => $subtotal,
        );
        //endregion

        //region data ke tiga detil
        $itemMaster3 = array();
        //        cekBiru($this->groupListTable[$selctedMethode]['tableName']['3']);
        if (isset($this->groupListTable[$selctedMethode]['tableName']['3'])) {
            $m->setFilters(array());
            $m->setFields(array());
            $m->setTableName($this->groupListTable[$selctedMethode]['tableName']['3']);
            $m->setInParam($inParam);
            //            $m->setParam($param);
            //            $m->setFilters(array(
            //                'subject_id' => array(
            //                    '$gt' => "0"//cari yang cabang bukan pusat
            //                ),
            //            ));
            $m->setFilters($filters);

            $tmpChild3 = $m->LookUpAll();
            //        arrPrint($tmpChild);

            $headerFieldChild3 = $this->selectedFields[$selctedMethode]['3']['headerFields'];
            $headerFieldChild_3_inde = $this->selectedFields[$selctedMethode]['3']['headerFields2'];
            $indexField3 = $this->selectedFields[$selctedMethode]['3']['index2'];
            $selectFieldTime3 = isset($this->selectedFields[$selctedMethode]['3']['subject_date'][$periode]) ? $this->selectedFields[$selctedMethode]['3']['subject_date'][$periode] : "";
            $selectFieldDataChild3 = isset($this->selectedFields[$selctedMethode]['3']['selected_fields']) ? $this->selectedFields[$selctedMethode]['3']['selected_fields'] : array();
            $masterIndex3 = isset($this->selectedFields[$selctedMethode]['3']['masterIndex']) ? $this->selectedFields[$selctedMethode]['3']['masterIndex'] : "";
            //        [produk][cabang][unit][th] = array("unit_af"=>2,"nilai_af"=>"50000")
            $childData3 = array();
            $chilValues = array();
            $chilDataIndex3 = array();
            $chilValusIndex3 = array();
            $dataSumFooterValues3 = array();
            //            arrprint($tmpChild3);
            if (sizeof($tmpChild3) > 0) {
                foreach ($tmpChild3 as $tmpChild_0) {
                    foreach ($selectFieldDataChild3 as $pID => $pidName) {
                        foreach ($pidName as $gateLabel) {
                            $childData3[$tmpChild_0[$pID]][$gateLabel] = $tmpChild_0[$gateLabel];
                        }
                    }
                    foreach ($indexField3 as $ind2Key => $ind2_alias) {
                        $chilDataIndex3[$tmpChild_0[$ind2Key]] = $tmpChild_0[$ind2_alias];

                        foreach ($headerFieldChild_3_inde as $f_key => $f_alias) {
                            if (!isset($tmpChild_0[$f_key])) {
                                $tmpChild_0[$f_key] = 0;
                            }

                            if (!isset($chilValusIndex3[$tmpChild_0[$masterIndex3]][$tmpChild_0[$ind2Key]][$f_key][$arrTimeSelect[$tmpChild_0[$selectFieldTime3]]])) {
                                $chilValusIndex3[$tmpChild_0[$masterIndex3]][$tmpChild_0[$ind2Key]][$f_key][$arrTimeSelect[$tmpChild_0[$selectFieldTime3]]] = 0;
                            }

                            if (!isset($chilValusIndex3[$tmpChild_0[$masterIndex3]]['subtotal'][$arrTimeSelect[$tmpChild_0[$selectFieldTime3]]])) {
                                $chilValusIndex3[$tmpChild_0[$masterIndex3]]['subtotal'][$arrTimeSelect[$tmpChild_0[$selectFieldTime3]]] = 0;
                            }

                            $chilValusIndex3[$tmpChild_0[$masterIndex3]][$tmpChild_0[$ind2Key]][$f_key][$arrTimeSelect[$tmpChild_0[$selectFieldTime3]]] += $tmpChild_0[$f_key];
                            $chilValusIndex3[$tmpChild_0[$masterIndex3]]['subtotal'][$arrTimeSelect[$tmpChild_0[$selectFieldTime]]] += $tmpChild_0[$f_key];

                            if (!isset($dataSumFooterValues3[$tmpChild_0[$ind2Key]][$f_key][$arrTimeSelect[$tmpChild_0[$selectFieldTime3]]])) {
                                $dataSumFooterValues3[$tmpChild_0[$ind2Key]][$f_key][$arrTimeSelect[$tmpChild_0[$selectFieldTime3]]] = 0;
                            }
                            if (!isset($dataSumFooterValues3['subtotal'][$arrTimeSelect[$tmpChild_0[$selectFieldTime3]]])) {
                                $dataSumFooterValues3['subtotal'][$arrTimeSelect[$tmpChild_0[$selectFieldTime3]]] = 0;
                            }
                            $dataSumFooterValues3[$tmpChild_0[$ind2Key]][$f_key][$arrTimeSelect[$tmpChild_0[$selectFieldTime3]]] += $tmpChild_0[$f_key];
                            $dataSumFooterValues3['subtotal'][$arrTimeSelect[$tmpChild_0[$selectFieldTime3]]] += $tmpChild_0[$f_key];

                            //                        cekHijau($tmpChild_0[$masterIndex]); //produknya
                            //                        cekOrange($tmpChild_0[$selectFieldTime]); //bulannya-
                            //                        cekBiru($f_key); //key nilai-
                            //                        cekPink($tmpChild_0[$ind2Key]); //cabangnya-

                        }
                    }
                }
            }
            //            arrPrint($chilValusIndex3);
            $itemMaster3 = array(
                "mainValues" => $chilValusIndex3,
                "mainData"   => $childData3,
                "mainIndex2" => $chilDataIndex3,
                "title"      => isset($this->selectedFields[$selctedMethode][3]['titleMain']) ? $this->selectedFields[$selctedMethode][3]['titleMain'] . "<small class='text-red'><em>3# $periode - $year</em></small>" : "",
                "subtitle"   => "periode $periode (tahun $year)",
                "sumfield"   => isset($this->selectedFields[$selctedMethode][3]['sumFields']) ? $this->selectedFields[$selctedMethode][3]['sumFields'] : array(),
                "sumFooter"  => $dataSumFooterValues3,
            );
        }
        //endregion

        $endtime = microtime(true); // Bottom of page
        //        $valt = $endtime - $starttime;
        //        cekBiru("load time =>" . "$val");
        $items = array(
            1 => $itemMaster,
            2 => $itemMaster2,
            3 => isset($itemMaster3) ? $itemMaster3 : array(),
            4 => isset($itemMaster3) ? $itemMaster3 : array(),
        );
        $itemHeaderFields = array(
            1 => array(
                "headerField"     => $headerField,
                "selectFieldTime" => $selectFieldTime,
                "selectFieldData" => $selectFieldData,
                "header2"         => $this->selectedFields[$selctedMethode][1]['headerFields2'],
            ),
            2 => array(
                "headerField"     => $headerFieldChild,
                "selectFieldTime" => $selectFieldTime,
                "selectFieldData" => $selectFieldDataChild,
                "header2"         => $this->selectedFields[$selctedMethode][2]['headerFields2'],
                "index2"          => $this->selectedFields[$selctedMethode][2]['index2'],
            ),
            3 => array(
                "headerField"     => isset($headerFieldChild3) ? $headerFieldChild3 : array(),
                "selectFieldTime" => $selectFieldTime,
                "selectFieldData" => isset($selectFieldDataChild3) ? $selectFieldDataChild3 : array(),
                "header2"         => isset($this->selectedFields[$selctedMethode][3]['headerFields2']) ? $this->selectedFields[$selctedMethode][3]['headerFields2'] : array(),
                "index2"          => isset($this->selectedFields[$selctedMethode][3]['index2']) ? $this->selectedFields[$selctedMethode][3]['index2'] : array(),
            ),
            4 => array(
                "headerField"     => isset($headerFieldChild3) ? $headerFieldChild3 : array(),
                "selectFieldTime" => $selectFieldTime,
                "selectFieldData" => isset($selectFieldDataChild3) ? $selectFieldDataChild3 : array(),
                "header2"         => isset($this->selectedFields[$selctedMethode][3]['headerFields2']) ? $this->selectedFields[$selctedMethode][3]['headerFields2'] : array(),
                "index2"          => isset($this->selectedFields[$selctedMethode][3]['index2']) ? $this->selectedFields[$selctedMethode][3]['index2'] : array(),
            ),
        );
        $data = array(
            "mode"         => "viewMonthly",
            "title"        => "Laporan Penjualan",
            "navBtn"       => $this->grupReport,
            "subTitle"     => "",
            "indexKey"     => isset($this->groupListTable[$selctedMethode]['indexKey']) ? $this->groupListTable[$selctedMethode]['indexKey'] : array(),
            "itemsMain"    => $items,
            "itemsPeriode" => $arrTimeSelect,
            "headerFields" => $itemHeaderFields,
            "navGate"      => $navigateData,
            "names"        => isset($names) ? $names : array(),
            "thisPage"     => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            "historyPage"  => base_url() . "Transaksi / viewHistory / " . $this->jenisTr . " ? stID = ",
            "stepNames"    => "",
            "periode"      => $this->periode,
            "detilLink"    => $this->linkDetail[$this->uri->segment(2)],
        );
        $this->load->view("reports", $data);
    }

    public function viewDayly()
    {
        //tahun berjalan year to date

        $starttime = microtime(true);
        $selctedMethode = $this->uri->segment(3) != null ? $this->uri->segment(3) : "cabang";
        $this->load->model("Mdls/MdlMongoReport");

        $m = new MdlMongoReport();


        $startDate = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
        $endDate = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
        $selectedPeriode = isset($_GET['nav2']) ? $_GET['nav2'] : "th";
        $year = formatTanggal($endDate, 'Y');
        $month = formatTanggal($endDate, 'm');
        $last_year = $year - 1;
        $last_month = $month;
        //        cekHitam($selectedPeriode);
        //region date
        $navigateData = array(
            "date1" => $startDate,
            "date2" => $endDate,
            "nav2"  => $selectedPeriode
        );

        //endregion
        $filters = array();

        $compare = false;
        $periode = "bulanan";
        $arrTimeSelect = namaBulan2();
        //        arrPrint($arrTimeSelect);
        $curentDate = date("Y");

        $inParam = array("$last_year",
            "$year"
        );

        switch ($selctedMethode) {
            case "cabang":
                $filters = array(
                    "periode"    => $periode,
                    //relative
                    "th"         => $year,
                    'subject_id' => array('$gt' => "0"),
                    //ini untuk > greather than
                    //                    'bl' => array('$lte' => $month), //ini untuk lebih kecil sama dengan (bl <='12')

                );
                break;

            default:
                $filters = array(
                    "periode"    => $periode,
                    //relative
                    'subject_id' => array('$gt' => "0"),
                    //ini untuk > greather than
                    "th"         => $year,
                    //                    'bl' => array('$lte' => $month),
                );
                break;

        }
        //        ---- end custom define harus diupdate biar relative ----

        //region data pertama

        $m->setTableName($this->groupListTable[$selctedMethode]['tableName']['1']);
        //        $m->setInParam($inParam);
        //        $m->setParam($param);
        $m->setFields(array());
        $m->setFilters($filters);
        $tmpMaster = $m->LookUpAll();

        $headerField = $this->selectedFields[$selctedMethode]['1']['headerFields'];
        $headerField2 = $this->selectedFields[$selctedMethode]['1']['headerFields2'];
        $selectFieldTime = isset($this->selectedFields[$selctedMethode]['1']['subject_date'][$periode]) ? $this->selectedFields[$selctedMethode]['1']['subject_date'][$periode] : "";
        $selectFieldData = isset($this->selectedFields[$selctedMethode]['1']['selected_fields']) ? $this->selectedFields[$selctedMethode]['1']['selected_fields'] : array();
        $dataMainValues = array();
        $dataSumValues = array();
        $dataSumFooterValues = array();
        $dataMain = array();
        //        arrPRint($headerField2);
        foreach ($tmpMaster as $tmp) {
            //            if ($compare) {
            foreach ($headerField as $s => $sAlias) {
                //                cekHitam($s);
                //                    $dataMainValues[$tmp[$selectFieldTime]][$tmp[$s]] = $tmp;
                foreach ($headerField2 as $k => $alias) {
                    if (!isset($tmp[$k])) {
                        $tmp[$k] = 0;
                    }
                    if (!isset($tmp[$selectFieldTime])) {
                        $tmp[$selectFieldTime] = 0;
                    }
                    if (!isset($dataSumFooterValues[$k][$tmp[$selectFieldTime]])) {
                        $dataSumFooterValues[$k][$tmp[$selectFieldTime]] = 0;
                    }
                    if (!isset($dataMainValues[$tmp[$s]][$tmp[$selectFieldTime]][$k])) {
                        $dataMainValues[$tmp[$s]][$tmp[$selectFieldTime]][$k] = 0;
                    }
                    //                    if (!isset($dataSumFooterValues2[$s]['subtotal'][$k])) {
                    //                        $dataSumFooterValues2[$s]['subtotal'][$k] = 0;
                    //                    }
                    //                    $dataSumFooterValues2[$s]['subtotal'][$k] +=$tmp[$k];
                    $dataSumFooterValues[$k][$tmp[$selectFieldTime]] += $tmp[$k];
                    $dataMainValues[$tmp[$s]][$tmp[$selectFieldTime]][$k] = $tmp[$k];
                }


            }
            //            }else{
            //
            //            }

            foreach ($selectFieldData as $keyMaster => $tmpFildsData) {
                foreach ($tmpFildsData as $fields) {
                    $dataMain[$keyMaster][$tmp[$keyMaster]][$fields] = $tmp[$fields];
                }
                foreach ($headerField2 as $h => $hLabel) {
                    if (!isset($tmp[$h])) {
                        $tmp[$h] = 0;
                    }
                    if (!isset($tmp[$keyMaster])) {
                        $tmp[$keyMaster] = 0;
                    }
                    if (!isset($dataSumValues[$tmp[$keyMaster]]['subtotal'][$h])) {
                        $dataSumValues[$tmp[$keyMaster]]['subtotal'][$h] = 0;
                    }
                    $dataSumValues[$tmp[$keyMaster]]['subtotal'][$h] += $tmp[$h];
                }
            }
        }
        //        arrPrint($dataSumValues);
        $itemMaster = array(
            "mainValues"    => $dataMainValues,
            "mainData"      => $dataMain,
            "mainSumValues" => $dataSumValues,
            "sumFooter"     => $dataSumFooterValues,
            "title"         => isset($this->selectedFields[$selctedMethode][1]['titleMain']) ? $this->selectedFields[$selctedMethode][1]['titleMain'] . " <small class='text-red'><em>$periode - $year</em></small>" : "",
            "subtitle"      => "periode Januari - " . $arrTimeSelect[$month] . " (tahun $year)",
            "sumfield"      => isset($this->selectedFields[$selctedMethode][1]['sumFields']) ? $this->selectedFields[$selctedMethode][1]['sumFields'] : array(),
        );
        //endregion

        //region data ke dua detil
        $tmpChild = array();
        if (isset($this->groupListTable[$selctedMethode]['tableName']['2'])) {
            $m->setFilters(array());
            $m->setFields(array());
            $m->setTableName($this->groupListTable[$selctedMethode]['tableName']['2']);
            //            $m->setInParam($inParam);
            //            $m->setParam($param);
            $m->setFilters($filters);
            $tmpChild = $m->LookUpAll();
            //            arrPrint($filters);
            //            arrPrint($inParam);
            //            cekLime($param);
        }
        //        arrPrint($tmpChild);
        $headerFieldChild = $this->selectedFields[$selctedMethode]['2']['headerFields'];
        $headerFieldChild2 = $this->selectedFields[$selctedMethode]['2']['headerFields2'];
        $indexField2 = $this->selectedFields[$selctedMethode]['2']['index2'];
        $selectFieldTime = isset($this->selectedFields[$selctedMethode]['2']['subject_date'][$periode]) ? $this->selectedFields[$selctedMethode]['2']['subject_date'][$periode] : "";
        $selectFieldDataChild = isset($this->selectedFields[$selctedMethode]['2']['selected_fields']) ? $this->selectedFields[$selctedMethode]['2']['selected_fields'] : array();
        $masterIndex = isset($this->selectedFields[$selctedMethode]['2']['masterIndex']) ? $this->selectedFields[$selctedMethode]['2']['masterIndex'] : "";
        //        [produk][cabang][unit][th] = array("unit_af"=>2,"nilai_af"=>"50000")
        $childData = array();
        $chilValues = array();
        $chilDataIndex2 = array();
        $chilValusIndex2 = array();
        $dataSumValues2 = array();
        $dataSumFooterValues2 = array();
        if (sizeof($tmpChild) > 0) {
            foreach ($tmpChild as $tmpChild_0) {
                foreach ($selectFieldDataChild as $pID => $pidName) {
                    foreach ($pidName as $gateLabel) {
                        $childData[$tmpChild_0[$pID]][$gateLabel] = $tmpChild_0[$gateLabel];
                    }
                }

                foreach ($indexField2 as $ind2Key => $ind2_alias) {
                    $chilDataIndex2[$tmpChild_0[$ind2Key]] = $tmpChild_0[$ind2_alias];
                    //                    if(!isset($tmpChild_0[$ind2Key]))
                    //[produk][periode][qty][cb]
                    foreach ($headerFieldChild2 as $f_key => $f_alias) {
                        if (!isset($tmpChild_0[$f_key])) {
                            $tmpChild_0[$f_key] = 0;
                        }
                        if (!isset($chilValusIndex2[$tmpChild_0[$masterIndex]][$tmpChild_0[$selectFieldTime]][$f_key][$tmpChild_0[$ind2Key]])) {
                            $chilValusIndex2[$tmpChild_0[$masterIndex]][$tmpChild_0[$selectFieldTime]][$f_key][$tmpChild_0[$ind2Key]][$f_key] = 0;
                        }
                        $chilValusIndex2[$tmpChild_0[$masterIndex]][$tmpChild_0[$selectFieldTime]][$f_key][$tmpChild_0[$ind2Key]] = $tmpChild_0[$f_key];
                        if (!isset($dataSumFooterValues2[$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]])) {
                            $dataSumFooterValues2[$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]] = 0;
                        }
                        if (!isset($dataSumFooterValues2[$tmpChild_0[$selectFieldTime]]['subtotal'][$f_key])) {
                            $dataSumFooterValues2[$tmpChild_0[$selectFieldTime]]['subtotal'][$f_key] = 0;
                        }

                        $dataSumFooterValues2[$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]] += $tmpChild_0[$f_key];
                        $dataSumFooterValues2[$tmpChild_0[$selectFieldTime]]['subtotal'][$f_key] += $tmpChild_0[$f_key];
                    }

                }
                foreach ($headerFieldChild2 as $f_key => $f_alias) {
                    if (!isset($chilValusIndex2[$tmpChild_0[$masterIndex]]['subtotal'][$f_key][$tmpChild_0[$ind2Key]])) {
                        $chilValusIndex2[$tmpChild_0[$masterIndex]]['subtotal'][$f_key][$tmpChild_0[$ind2Key]] = 0;
                    }

                    $chilValusIndex2[$tmpChild_0[$masterIndex]]['subtotal'][$f_key][$tmpChild_0[$ind2Key]] += $tmpChild_0[$f_key];

                }

            }
        }
        //[prodykID][periode][qty][cabang]=val
        //        arrPRint($chilValusIndex2);
        //        matiHEre();
        $itemMaster2 = array(
            "mainValues"   => $chilValusIndex2,
            "mainData"     => $childData,
            "mainIndex2"   => $chilDataIndex2,
            "title"        => isset($this->selectedFields[$selctedMethode][2]['titleMain']) ? $this->selectedFields[$selctedMethode][2]['titleMain'] . " <small class='text-red'><em>$periode - $year</em></small>" : "",
            "subtitle"     => "periode tahun berjalan (tahun $year)",
            "sumfield"     => isset($this->selectedFields[$selctedMethode][2]['sumFields']) ? $this->selectedFields[$selctedMethode][2]['sumFields'] : array(),
            "sumFooter"    => $dataSumFooterValues2,
            "paramPeriode" => $arrTimeSelect,
            "subTotal"     => $subtotal,
        );
        //endregion

        //region data ke tiga detil
        $itemMaster3 = array();
        if (isset($this->groupListTable[$selctedMethode]['tableName']['3'])) {
            $m->setFilters(array());
            $m->setFields(array());
            $m->setTableName($this->groupListTable[$selctedMethode]['tableName']['3']);
            $m->setInParam($inParam);
            $m->setParam($param);
            $m->setFilters(array(
                'subject_id' => array(
                    '$gt' => "0"
                    //cari yang cabang bukan pusat
                ),
            ));

            $tmpChild3 = $m->LookUpAll();
            //        arrPrint($tmpChild);
            $headerFieldChild3 = $this->selectedFields[$selctedMethode]['3']['headerFields'];
            $headerFieldChild_3_inde = $this->selectedFields[$selctedMethode]['3']['headerFields2'];
            $indexField3 = $this->selectedFields[$selctedMethode]['3']['index2'];
            $selectFieldTime3 = isset($this->selectedFields[$selctedMethode]['3']['subject_date'][$periode]) ? $this->selectedFields[$selctedMethode]['3']['subject_date'][$periode] : "";
            $selectFieldDataChild3 = isset($this->selectedFields[$selctedMethode]['3']['selected_fields']) ? $this->selectedFields[$selctedMethode]['3']['selected_fields'] : array();
            $masterIndex3 = isset($this->selectedFields[$selctedMethode]['3']['masterIndex']) ? $this->selectedFields[$selctedMethode]['3']['masterIndex'] : "";
            //        [produk][cabang][unit][th] = array("unit_af"=>2,"nilai_af"=>"50000")
            $childData3 = array();
            $chilValues = array();
            $chilDataIndex3 = array();
            $chilValusIndex3 = array();
            if (sizeof($tmpChild3) > 0) {
                foreach ($tmpChild3 as $tmpChild_0) {
                    foreach ($selectFieldDataChild3 as $pID => $pidName) {
                        foreach ($pidName as $gateLabel) {
                            $childData3[$tmpChild_0[$pID]][$gateLabel] = $tmpChild_0[$gateLabel];
                        }
                    }
                    foreach ($indexField3 as $ind2Key => $ind2_alias) {
                        $chilDataIndex3[$tmpChild_0[$ind2Key]] = $tmpChild_0[$ind2_alias];
                        //                    if(!isset($tmpChild_0[$ind2Key]))
                        foreach ($headerFieldChild_3_inde as $f_key => $f_alias) {
                            if (!isset($tmpChild_0[$f_key])) {
                                $tmpChild_0[$f_key] = 0;
                            }
                            //                        $chilValusIndex2[$tmpChild_0[$masterIndex]][$tmpChild_0[$ind2Key]][$tmpChild_0[$selectFieldTime]][] = $tmpChild_0;
                            $chilValusIndex3[$tmpChild_0[$masterIndex]][$tmpChild_0[$ind2Key]][$f_key][$tmpChild_0[$selectFieldTime]] = $tmpChild_0[$f_key];


                        }
                    }

                }
            }


            $itemMaster3 = array(
                "mainValues" => $chilValusIndex3,
                "mainData"   => $childData3,
                "mainIndex2" => $chilDataIndex3,
                "title"      => isset($this->selectedFields[$selctedMethode][3]['titleMain']) ? $this->selectedFields[$selctedMethode][3]['titleMain'] . "<small class='text-red'><em>komparasi th $last_year - $year</em></small>" : "",
                "subtitle"   => "periode $periode (tahun $year)",
                "sumfield"   => isset($this->selectedFields[$selctedMethode][3]['sumFields']) ? $this->selectedFields[$selctedMethode][3]['sumFields'] : array(),
            );
        }

        //endregion

        $endtime = microtime(true); // Bottom of page
        $val = $endtime - $starttime;
        //        cekBiru("load time =>" . "$val");
        $items = array(
            1 => $itemMaster,
            2 => $itemMaster2,
            //            3 => isset($itemMaster3) ? $itemMaster3 : array(),
        );
        $itemHeaderFields = array(
            1 => array(
                "headerField"     => $headerField,
                "selectFieldTime" => $selectFieldTime,
                "selectFieldData" => $selectFieldData,
                "header2"         => $this->selectedFields[$selctedMethode][1]['headerFields2'],
            ),
            2 => array(
                "headerField"     => $headerFieldChild,
                "selectFieldTime" => $selectFieldTime,
                "selectFieldData" => $selectFieldDataChild,
                "header2"         => $this->selectedFields[$selctedMethode][2]['headerFields2'],
                "index2"          => $this->selectedFields[$selctedMethode][2]['index2'],
            ),
            3 => array(
                "headerField"     => isset($headerFieldChild3) ? $headerFieldChild3 : array(),
                "selectFieldTime" => $selectFieldTime,
                "selectFieldData" => isset($selectFieldDataChild3) ? $selectFieldDataChild3 : array(),
                "header2"         => isset($this->selectedFields[$selctedMethode][3]['headerFields2']) ? $this->selectedFields[$selctedMethode][3]['headerFields2'] : array(),
                "index2"          => isset($this->selectedFields[$selctedMethode][3]['index2']) ? $this->selectedFields[$selctedMethode][3]['index2'] : array(),
            ),
        );
        $data = array(
            "mode"  => "viewMontly",
            "title" => "Laporan Penjualan",

            "navBtn"       => $this->grupReport,
            "subTitle"     => "",
            "indexKey"     => isset($this->groupListTable[$selctedMethode]['indexKey']) ? $this->groupListTable[$selctedMethode]['indexKey'] : array(),
            "itemsMain"    => $items,
            "itemsPeriode" => $arrTimeSelect,
            "headerFields" => $itemHeaderFields,
            "navGate"      => $navigateData,
            "names"        => isset($names) ? $names : array(),
            "thisPage"     => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            "historyPage"  => base_url() . "Transaksi / viewHistory / " . $this->jenisTr . " ? stID = ",
            "stepNames"    => "",
            "periode"      => $this->periode,
            "detilLink"    => $this->linkDetail[$this->uri->segment(2)],
        );
        $this->load->view("reports", $data);
    }

    public function viewReport()
    {
        //tahun berjalan year to date

        $starttime = microtime(true);
        $selctedMethode = $this->uri->segment(3) != null ? $this->uri->segment(3) : "seller";
        $this->load->model("Mdls/MdlMongoReport");

        $m = new MdlMongoReport();

        $startDate = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
        $endDate = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
        $selectedPeriode = isset($_GET['nav2']) ? $_GET['nav2'] : "th";
        $year = formatTanggal($startDate, 'Y');
        $month = formatTanggal($startDate, 'm');

        $last_year = $year - 1;
        $last_month = $month;
        //        cekHitam($selectedPeriode);
        //region date
        $navigateData = array(
            "date1" => $startDate,
            "date2" => $endDate,
            "nav2"  => $selectedPeriode
        );

        //endregion
        $filters = array();

        $compare = false;
        $periode = "bulanan";
        $arrTimeSelect = namaBulan2();
        $myId = my_id();
        $curentDate = date("Y");

        $inParam = array("$last_year",
            "$year"
        );

        switch ($selctedMethode) {
            case "cabang":
                $filters = array(
                    "periode"    => $periode,
                    //relative
                    "th"         => $year,
                    'subject_id' => $myId,
                    //ini untuk > greather than
                    // 'subject_id' => array('$gt' => "0"),//ini untuk > greather than
                    //                    'bl' => array('$lte' => $month), //ini untuk lebih kecil sama dengan (bl <='12')
                );
                break;
            default:
                $filters = array(
                    "periode"    => $periode,
                    //relative
                    // 'subject_id' => array('$gt' => "0"),//ini untuk > greather than
                    'subject_id' => $myId,
                    //ini untuk > greather than
                    "th"         => $year,
                    //                    'bl' => array('$lte' => $month),
                );
                break;
        }
        //        arrPrint($periode);
        //        arrPrint($filters);
        //        ---- end custom define harus diupdate biar relative ----

        //region data pertama
        //cekHitam($this->groupListTable[$selctedMethode]['tableName']['1']);
        $m->setFields(array());
        $m->setTableName($this->groupListTable[$selctedMethode]['tableName']['1']);

        $m->setFilters($filters);
        $m->setFields(array());
        $tmpMaster = $m->LookUpAll();
        //        arrPRint($tmpMaster);
        //        showLast_query("lime");
        //        arrPrint($tmpMaster);
        //        matiHere();
        $headerField = isset($this->selectedFields[$selctedMethode]['1']['headerFields']) ? $this->selectedFields[$selctedMethode]['1']['headerFields'] : "";
        $headerField2 = isset($this->selectedFields[$selctedMethode]['1']['headerFields2']) ? $this->selectedFields[$selctedMethode]['1']['headerFields2'] : "";
        $selectFieldTime = isset($this->selectedFields[$selctedMethode]['1']['subject_date'][$periode]) ? $this->selectedFields[$selctedMethode]['1']['subject_date'][$periode] : "";
        $selectFieldData = isset($this->selectedFields[$selctedMethode]['1']['selected_fields']) ? $this->selectedFields[$selctedMethode]['1']['selected_fields'] : array();
        $dataMainValues = array();
        $dataSumValues = array();
        $dataSumFooterValues = array();
        $dataMain = array();

        //        $itemMaster = array();
        //        arrPRint($headerField2);
        //        if(sizeof($tmpMaster)>0){
        foreach ($tmpMaster as $tmp) {
            //            if ($compare) {
            if (is_array($headerField) > 0) {
                foreach ($headerField as $s => $sAlias) {
                    //                cekHitam($s);
                    //                    $dataMainValues[$tmp[$selectFieldTime]][$tmp[$s]] = $tmp;
                    foreach ($headerField2 as $k => $alias) {
                        if (!isset($tmp[$k])) {
                            $tmp[$k] = 0;
                        }
                        if (!isset($tmp[$selectFieldTime])) {
                            $tmp[$selectFieldTime] = 0;
                        }
                        if (!isset($dataSumFooterValues[$k][$tmp[$selectFieldTime]])) {
                            $dataSumFooterValues[$k][$tmp[$selectFieldTime]] = 0;
                        }
                        if (!isset($dataMainValues[$tmp[$s]][$tmp[$selectFieldTime]][$k])) {
                            $dataMainValues[$tmp[$s]][$tmp[$selectFieldTime]][$k] = 0;
                        }
                        //                    if (!isset($dataSumFooterValues2[$s]['subtotal'][$k])) {
                        //                        $dataSumFooterValues2[$s]['subtotal'][$k] = 0;
                        //                    }
                        //                    $dataSumFooterValues2[$s]['subtotal'][$k] +=$tmp[$k];
                        $dataSumFooterValues[$k][$tmp[$selectFieldTime]] += $tmp[$k];
                        $dataMainValues[$tmp[$s]][$tmp[$selectFieldTime]][$k] = $tmp[$k];
                    }


                }
            }
            //            }else{

            //            }

            foreach ($selectFieldData as $keyMaster => $tmpFildsData) {
                foreach ($tmpFildsData as $fields) {
                    $dataMain[$keyMaster][$tmp[$keyMaster]][$fields] = $tmp[$fields];
                }
                foreach ($headerField2 as $h => $hLabel) {
                    if (!isset($tmp[$h])) {
                        $tmp[$h] = 0;
                    }
                    if (!isset($tmp[$keyMaster])) {
                        $tmp[$keyMaster] = 0;
                    }
                    if (!isset($dataSumValues[$tmp[$keyMaster]]['subtotal'][$h])) {
                        $dataSumValues[$tmp[$keyMaster]]['subtotal'][$h] = 0;
                    }
                    $dataSumValues[$tmp[$keyMaster]]['subtotal'][$h] += $tmp[$h];
                }
            }
        }

        //        arrPrint($dataSumValues);

        // cekBiru($arrTimeSelect);
        // cekMerah($month);
        // $dataMain = array();
        $itemMaster = array(
            "mainValues"    => $dataMainValues,
            "mainData"      => $dataMain,
            "mainSumValues" => $dataSumValues,
            "sumFooter"     => $dataSumFooterValues,
            "title"         => isset($this->selectedFields[$selctedMethode][1]['titleMain']) ? $this->selectedFields[$selctedMethode][1]['titleMain'] . " <small class='text-red'><em>1# $periode - $year</em></small>" : "",
            "subtitle"      => "periode Januari - " . isset($arrTimeSelect[$month * 1]) ? $arrTimeSelect[$month * 1] : "" . " (tahun $year)",
            "sumfield"      => isset($this->selectedFields[$selctedMethode][1]['sumFields']) ? $this->selectedFields[$selctedMethode][1]['sumFields'] : array(),
        );
        //endregion

        //region data ke dua detil
        $tmpChild = array();

        if (isset($this->groupListTable[$selctedMethode]['tableName']['2'])) {
            $m->setFilters(array());
            $m->setTableName($this->groupListTable[$selctedMethode]['tableName']['2']);
            //            $m->setInParam($inParam);
            //            $m->setParam($param);
            $m->setFilters($filters);
            $tmpChild = $m->LookUpAll();
            //            arrPrint($filters);
            //            arrPrint($inParam);
            //            cekLime($param);
        }
        //        arrPrint($tmpChild);
        $headerFieldChild = $this->selectedFields[$selctedMethode]['2']['headerFields'];
        $headerFieldChild2 = $this->selectedFields[$selctedMethode]['2']['headerFields2'];
        $indexField2 = $this->selectedFields[$selctedMethode]['2']['index2'];
        $selectFieldTime = isset($this->selectedFields[$selctedMethode]['2']['subject_date'][$periode]) ? $this->selectedFields[$selctedMethode]['2']['subject_date'][$periode] : "";
        $selectFieldDataChild = isset($this->selectedFields[$selctedMethode]['2']['selected_fields']) ? $this->selectedFields[$selctedMethode]['2']['selected_fields'] : array();
        $masterIndex = isset($this->selectedFields[$selctedMethode]['2']['masterIndex']) ? $this->selectedFields[$selctedMethode]['2']['masterIndex'] : "";
        //        [produk][cabang][unit][th] = array("unit_af"=>2,"nilai_af"=>"50000")
        $childData = array();
        $chilValues = array();
        $chilDataIndex2 = array();
        $chilValusIndex2 = array();
        $dataSumValues2 = array();
        $dataSumFooterValues2 = array();
        if (sizeof($tmpChild) > 0) {
            foreach ($tmpChild as $tmpChild_0) {
                foreach ($selectFieldDataChild as $pID => $pidName) {
                    foreach ($pidName as $gateLabel) {
                        $childData[$tmpChild_0[$pID]][$gateLabel] = $tmpChild_0[$gateLabel];
                    }
                }

                foreach ($indexField2 as $ind2Key => $ind2_alias) {
                    $chilDataIndex2[$tmpChild_0[$ind2Key]] = $tmpChild_0[$ind2_alias];
                    //                    if(!isset($tmpChild_0[$ind2Key]))
                    //[produk][periode][qty][cb]
                    foreach ($headerFieldChild2 as $f_key => $f_alias) {
                        if (!isset($tmpChild_0[$f_key])) {
                            $tmpChild_0[$f_key] = 0;
                        }
                        if (!isset($chilValusIndex2[$tmpChild_0[$masterIndex]][$tmpChild_0[$selectFieldTime]][$f_key][$tmpChild_0[$ind2Key]])) {
                            $chilValusIndex2[$tmpChild_0[$masterIndex]][$tmpChild_0[$selectFieldTime]][$f_key][$tmpChild_0[$ind2Key]] = 0;
                        }
                        $chilValusIndex2[$tmpChild_0[$masterIndex]][$tmpChild_0[$selectFieldTime]][$f_key][$tmpChild_0[$ind2Key]] += $tmpChild_0[$f_key];

                        //BELUM VALID BROOOOO chepy 26-12-2020 20.14
                        if (!isset($dataSumFooterValues2[$tmpChild_0[$selectFieldTime]][$f_key][$tmpChild_0[$ind2Key]])) {
                            $dataSumFooterValues2[$tmpChild_0[$selectFieldTime]][$f_key][$tmpChild_0[$ind2Key]] = 0;
                        }
                        $dataSumFooterValues2[$tmpChild_0[$selectFieldTime]][$f_key][$tmpChild_0[$ind2Key]] += $tmpChild_0[$f_key];

                        if (!isset($dataSumFooterValues2['subtotal'][$f_key][$tmpChild_0[$ind2Key]])) {
                            $dataSumFooterValues2['subtotal'][$f_key][$tmpChild_0[$ind2Key]] = 0;
                        }

                        $dataSumFooterValues2['subtotal'][$f_key][$tmpChild_0[$ind2Key]] += $tmpChild_0[$f_key];
                        //                        cekHijau($tmpChild_0[$masterIndex]); //produknya
                        //                        cekOrange($tmpChild_0[$selectFieldTime]); //bulannya-
                        //                        cekBiru($f_key); //key nilai-
                        //                        cekPink($tmpChild_0[$ind2Key]); //cabangnya-

                    }

                }
                foreach ($headerFieldChild2 as $f_key => $f_alias) {
                    if (!isset($chilValusIndex2[$tmpChild_0[$masterIndex]]['subtotal'][$f_key][$tmpChild_0[$ind2Key]])) {
                        $chilValusIndex2[$tmpChild_0[$masterIndex]]['subtotal'][$f_key][$tmpChild_0[$ind2Key]] = 0;
                    }

                    $chilValusIndex2[$tmpChild_0[$masterIndex]]['subtotal'][$f_key][$tmpChild_0[$ind2Key]] += $tmpChild_0[$f_key];


                }

            }
        }
        //[prodykID][periode][qty][cabang]=val
        //        arrPRint($chilValusIndex2);
        //        matiHEre();
        $itemMaster2 = array(
            "mainValues"   => $chilValusIndex2,
            "mainData"     => $childData,
            "mainIndex2"   => $chilDataIndex2,
            "title"        => isset($this->selectedFields[$selctedMethode][2]['titleMain']) ? $this->selectedFields[$selctedMethode][2]['titleMain'] . " <small class='text-red'><em>2# $periode - $year</em></small>" : "",
            "subtitle"     => "periode tahun berjalan (tahun $year)",
            "sumfield"     => isset($this->selectedFields[$selctedMethode][2]['sumFields']) ? $this->selectedFields[$selctedMethode][2]['sumFields'] : array(),
            "sumFooter"    => $dataSumFooterValues2,
            "paramPeriode" => $arrTimeSelect,
            //            "subTotal" => $subtotal,
        );
        //endregion

        //region data ke tiga detil
        $itemMaster3 = array();
        //        cekBiru($this->groupListTable[$selctedMethode]['tableName']['3']);
        if (isset($this->groupListTable[$selctedMethode]['tableName']['4'])) {
            $m->setFilters(array());
            $m->setFields(array());
            $m->setTableName($this->groupListTable[$selctedMethode]['tableName']['4']);
            $m->setInParam($inParam);
            //            $m->setParam($param);
            //            $m->setFilters(array(
            //                'subject_id' => array(
            //                    '$gt' => "0"//cari yang cabang bukan pusat
            //                ),
            //            ));
            $filters['object_id'] = $myId;
            $filters['subject_id'] = array('$gt' => "0");
            $m->setFilters($filters);

            $tmpChild3 = $m->LookUpAll();
            //        arrPrint($tmpChild3);
            // cekPink($filters);

            $headerFieldChild3 = $this->selectedFields[$selctedMethode]['4']['headerFields'];
            $headerFieldChild_3_inde = $this->selectedFields[$selctedMethode]['4']['headerFields2'];
            $indexField3 = $this->selectedFields[$selctedMethode]['4']['index2'];
            $selectFieldTime3 = isset($this->selectedFields[$selctedMethode]['4']['subject_date'][$periode]) ? $this->selectedFields[$selctedMethode]['4']['subject_date'][$periode] : "";
            $selectFieldDataChild3 = isset($this->selectedFields[$selctedMethode]['4']['selected_fields']) ? $this->selectedFields[$selctedMethode]['4']['selected_fields'] : array();
            $masterIndex3 = isset($this->selectedFields[$selctedMethode]['4']['masterIndex']) ? $this->selectedFields[$selctedMethode]['4']['masterIndex'] : "";
            //        [produk][cabang][unit][th] = array("unit_af"=>2,"nilai_af"=>"50000")
            $childData3 = array();
            $chilValues = array();
            $chilDataIndex3 = array();
            $chilValusIndex3 = array();
            $dataSumFooterValues3 = array();
            //            arrprint($tmpChild3);
            if (sizeof($tmpChild3) > 0) {
                foreach ($tmpChild3 as $tmpChild_0) {
                    foreach ($selectFieldDataChild3 as $pID => $pidName) {
                        foreach ($pidName as $gateLabel) {
                            $childData3[$tmpChild_0[$pID]][$gateLabel] = $tmpChild_0[$gateLabel];
                        }
                    }
                    foreach ($indexField3 as $ind2Key => $ind2_alias) {
                        $chilDataIndex3[$tmpChild_0[$ind2Key]] = $tmpChild_0[$ind2_alias];

                        foreach ($headerFieldChild_3_inde as $f_key => $f_alias) {
                            if (!isset($tmpChild_0[$f_key])) {
                                $tmpChild_0[$f_key] = 0;
                            }

                            if (!isset($chilValusIndex3[$tmpChild_0[$masterIndex3]][$tmpChild_0[$ind2Key]][$f_key][$arrTimeSelect[$tmpChild_0[$selectFieldTime3]]])) {
                                $chilValusIndex3[$tmpChild_0[$masterIndex3]][$tmpChild_0[$ind2Key]][$f_key][$arrTimeSelect[$tmpChild_0[$selectFieldTime3]]] = 0;
                            }

                            if (!isset($chilValusIndex3[$tmpChild_0[$masterIndex3]]['subtotal'][$arrTimeSelect[$tmpChild_0[$selectFieldTime3]]])) {
                                $chilValusIndex3[$tmpChild_0[$masterIndex3]]['subtotal'][$arrTimeSelect[$tmpChild_0[$selectFieldTime3]]] = 0;
                            }

                            $chilValusIndex3[$tmpChild_0[$masterIndex3]][$tmpChild_0[$ind2Key]][$f_key][$arrTimeSelect[$tmpChild_0[$selectFieldTime3]]] += $tmpChild_0[$f_key];
                            $chilValusIndex3[$tmpChild_0[$masterIndex3]]['subtotal'][$arrTimeSelect[$tmpChild_0[$selectFieldTime]]] += $tmpChild_0[$f_key];

                            if (!isset($dataSumFooterValues3[$tmpChild_0[$ind2Key]][$f_key][$arrTimeSelect[$tmpChild_0[$selectFieldTime3]]])) {
                                $dataSumFooterValues3[$tmpChild_0[$ind2Key]][$f_key][$arrTimeSelect[$tmpChild_0[$selectFieldTime3]]] = 0;
                            }
                            if (!isset($dataSumFooterValues3['subtotal'][$arrTimeSelect[$tmpChild_0[$selectFieldTime3]]])) {
                                $dataSumFooterValues3['subtotal'][$arrTimeSelect[$tmpChild_0[$selectFieldTime3]]] = 0;
                            }
                            $dataSumFooterValues3[$tmpChild_0[$ind2Key]][$f_key][$arrTimeSelect[$tmpChild_0[$selectFieldTime3]]] += $tmpChild_0[$f_key];
                            $dataSumFooterValues3['subtotal'][$arrTimeSelect[$tmpChild_0[$selectFieldTime3]]] += $tmpChild_0[$f_key];

                            //                        cekHijau($tmpChild_0[$masterIndex]); //produknya
                            //                        cekOrange($tmpChild_0[$selectFieldTime]); //bulannya-
                            //                        cekBiru($f_key); //key nilai-
                            //                        cekPink($tmpChild_0[$ind2Key]); //cabangnya-

                        }
                    }
                }
            }
            //            arrPrint($chilValusIndex3);
            $itemMaster3 = array(
                "mainValues" => $chilValusIndex3,
                "mainData"   => $childData3,
                "mainIndex2" => $chilDataIndex3,
                "title"      => isset($this->selectedFields[$selctedMethode][4]['titleMain']) ? $this->selectedFields[$selctedMethode][4]['titleMain'] . "<small class='text-red'><em>3# $periode - $year</em></small>" : "",
                "subtitle"   => "periode $periode (tahun $year)",
                "sumfield"   => isset($this->selectedFields[$selctedMethode][4]['sumFields']) ? $this->selectedFields[$selctedMethode][4]['sumFields'] : array(),
                "sumFooter"  => $dataSumFooterValues3,
            );
        }
        //endregion

        $endtime = microtime(true); // Bottom of page
        //        $valt = $endtime - $starttime;
        //        cekBiru("load time =>" . "$val");
        $items = array(
            1 => $itemMaster,
            2 => $itemMaster2,
            3 => isset($itemMaster3) ? $itemMaster3 : array(),
            4 => isset($itemMaster3) ? $itemMaster3 : array(),
        );
        $itemHeaderFields = array(
            1 => array(
                "headerField"     => $headerField,
                "selectFieldTime" => $selectFieldTime,
                "selectFieldData" => $selectFieldData,
                "header2"         => isset($this->selectedFields[$selctedMethode][1]['headerFields2']) ? $this->selectedFields[$selctedMethode][1]['headerFields2'] : "",
            ),
            2 => array(
                "headerField"     => $headerFieldChild,
                "selectFieldTime" => $selectFieldTime,
                "selectFieldData" => $selectFieldDataChild,
                "header2"         => $this->selectedFields[$selctedMethode][2]['headerFields2'],
                "index2"          => $this->selectedFields[$selctedMethode][2]['index2'],
            ),
            3 => array(
                "headerField"     => isset($headerFieldChild3) ? $headerFieldChild3 : array(),
                "selectFieldTime" => $selectFieldTime,
                "selectFieldData" => isset($selectFieldDataChild3) ? $selectFieldDataChild3 : array(),
                "header2"         => isset($this->selectedFields[$selctedMethode][3]['headerFields2']) ? $this->selectedFields[$selctedMethode][3]['headerFields2'] : array(),
                "index2"          => isset($this->selectedFields[$selctedMethode][3]['index2']) ? $this->selectedFields[$selctedMethode][3]['index2'] : array(),
            ),
            4 => array(
                "headerField"     => isset($headerFieldChild3) ? $headerFieldChild3 : array(),
                "selectFieldTime" => $selectFieldTime,
                "selectFieldData" => isset($selectFieldDataChild3) ? $selectFieldDataChild3 : array(),
                "header2"         => isset($this->selectedFields[$selctedMethode][3]['headerFields2']) ? $this->selectedFields[$selctedMethode][3]['headerFields2'] : array(),
                "index2"          => isset($this->selectedFields[$selctedMethode][3]['index2']) ? $this->selectedFields[$selctedMethode][3]['index2'] : array(),
            ),
        );
        $data = array(
            "mode"         => "viewReport",
            "title"        => "Laporan Penjualan",
            "navBtn"       => $this->grupReport,
            "subTitle"     => "",
            "indexKey"     => isset($this->groupListTable[$selctedMethode]['indexKey']) ? $this->groupListTable[$selctedMethode]['indexKey'] : array(),
            "itemsMain"    => $items,
            "itemsPeriode" => $arrTimeSelect,
            "headerFields" => $itemHeaderFields,
            "navGate"      => $navigateData,
            "names"        => isset($names) ? $names : array(),
            "thisPage"     => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            "historyPage"  => base_url() . "Transaksi / viewHistory / " . $this->jenisTr . " ? stID = ",
            "stepNames"    => "",
            "periode"      => $this->periode,
            "detilLink"    => isset($this->linkDetail[$this->uri->segment(2)]) ? $this->linkDetail[$this->uri->segment(2)] : "",
        );
        $this->load->view("reports", $data);
    }

    //detil link params
    public function viewDetailMainMonthly()
    {
        //        ceklime();
        //        arrPrint($this->uri->segment_array());
        $selctedMethode = $this->uri->segment(3);
        $th = $_GET['th'];
        $subjectID = $_GET['cb'];

        $arrTimeSelect = namaBulan2();
        $this->load->model("Mdls/MdlMongoReport");
        $m = new MdlMongoReport();
        $filters = array(
            "th"         => $th,
            "subject_id" => $subjectID,
            "periode"    => "bulanan",
        );

        $m->addFilter($filters);
        $m->setTableName($this->groupListTable[$selctedMethode]['tableName']['1']);
        $tmp = $m->lookUpAll();

        $headerField = $this->selectedFields[$selctedMethode]['1']['headerFields'];
        $headerField2 = $this->selectedFields[$selctedMethode]['1']['headerFields2'];
        $selectFieldData = isset($this->selectedFields[$selctedMethode]['1']['selected_fields']) ? $this->selectedFields[$selctedMethode]['1']['selected_fields'] : array();
        $selectFieldTime = isset($this->selectedFields[$selctedMethode]['1']['subject_date']['bulanan']) ? $this->selectedFields[$selctedMethode]['1']['subject_date']['bulanan'] : "";
        if (sizeof($tmp) > 0) {
            $mainValues = array();
            $sumvalues = array();
            $title = "";
            foreach ($tmp as $tmp0) {
                //                foreach ($arrTimeSelect as $k =>$kAlias){
                $title = htmlspecialchars($tmp0['subject_nama']);
                foreach ($headerField2 as $kField => $kAlias) {
                    if (!isset($mainValues[$tmp0[$selectFieldTime]][$kField])) {
                        $mainValues[$tmp0[$selectFieldTime]][$kField] = 0;
                    }

                    $mainValues[$tmp0[$selectFieldTime]][$kField] = $tmp0[$kField];

                }
                //                }
                foreach ($headerField2 as $field => $kAlias) {
                    if (!isset($sumvalues[$field])) {
                        $sumvalues[$field] = 0;
                    }
                    $sumvalues[$field] += $tmp0[$field];
                }
            }
        }

        $itemHeaderFields = array(
            "bulan"      => 'bulan',
            "unit_qty"   => "unit",
            "unit_nilai" => "nilai(netto)"
        );
        $data = array(
            "mode"  => "viewDetailItem",
            "title" => "Laporan Penjualan " . $this->uri->segment(3) . " $title",

            "navBtn"       => "",
            "subTitle"     => "periode bulanan th $th",
            "indexKey"     => isset($this->groupListTable[$selctedMethode]['indexKey']) ? $this->groupListTable[$selctedMethode]['indexKey'] : array(),
            "itemsMain"    => $mainValues,
            "indexHeader"  => $headerField2,
            "itemsPeriode" => $arrTimeSelect,
            "headerFields" => $itemHeaderFields,
            "navGate"      => "",
            "names"        => isset($names) ? $names : array(),
            "thisPage"     => base_url() . get_class($this) . "/" . $this->uri->segment(2),

            "stepNames" => "",
            "periode"   => $this->periode,
            //            "detilLink" =>$this->linkDetail[$this->uri->segment(2)],
        );
        //        $this->load->view("reports", $data);
        $this->load->view("reports", $data);
        //        matiHEre();
    }

    public function viewDetailChildMonthly()
    {
        ceklime();
        arrPrint($this->uri->segment_array());
    }

    public function viewDetailSubChildMonthly()
    {
        ceklime();
        arrPrint($this->uri->segment_array());
    }

    public function salesKonsolidate_1()
    {
        if (!isset($this->session->login['id'])) {
            gotoLogin();
            // redirect(base_url() . "Login");
        }

        $limit = 1000;
        //        $limit = 18;
        $maxPageNum = 20;
        //        $jenisTr = $this->uri->segment(3);
        $jenisTr = "582";
        $jenisTrsub = $this->uri->segment(4);
        $cCode = "_TR_" . $this->jenisTr;
        $currentState = strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : "transaksi";
        $selecetedCode = isset($this->config->item("heTransaksi_ui")[$jenisTr]['steps']['2']['target']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['steps']['2']['target'] : "";
        $historyFields = isset($this->config->item("heTransaksi_layout")[$jenisTr]['fixedFieldHoldConsolidate']) ? $this->config->item("heTransaksi_layout")[$jenisTr]['fixedFieldHoldConsolidate'] : array();
        $detailsFields = isset($this->config->item("heTransaksi_layout")[$jenisTr]['fixedFieldHoldConsolidate'][$currentState]['loop']) ? $this->config->item("heTransaksi_layout")[$jenisTr]['fixedFieldHoldConsolidate'][$currentState]['loop'] : array();
        $arrayValid = isset($this->config->item("heTransaksi_layout")[$jenisTr]['fixedFieldHoldConsolidate'][$currentState]['array_flip']) ? $this->config->item("heTransaksi_layout")[$jenisTr]['fixedFieldHoldConsolidate'][$currentState]['array_flip'] : array();
        $lockerStock = isset($this->config->item("heTransaksi_layout")[$jenisTr]['lockerStock']) ? $this->config->item("heTransaksi_layout")[$jenisTr]['lockerStock'] : "MdlLockerStock";

        $menuLabel = isset($this->config->item("heTransaksi_ui")[$jenisTr]['label']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['label'] : "";

        if (isset($_SESSION[$cCode])) {
            $_SESSION[$cCode] = null;
            unset($_SESSION[$cCode]);
        }


        //            $stepNumber = isset($_SESSION[$cCode]['tableIn_master']['step_number']) ? $_SESSION[$cCode]['tableIn_master']['step_number'] : 1;
        //region session init
        if (!isset($_SESSION[$cCode])) {
            $_SESSION[$cCode] = array(
                "items" => array(),
                "main"  => array(),
            );
        }
        if (!isset($_SESSION[$cCode]['main'])) {
            $_SESSION[$cCode]['main'] = array();
        }
        if (!isset($_SESSION[$cCode]['items'])) {
            $_SESSION[$cCode]['items'] = array();
        }
        //endregion

        //       arrPrint($arrayFlip);
        $mb = New MobileDetect();
        $this->load->model("Mdls/$lockerStock");
        $st = new $lockerStock();
        //        $st->addFilter("jenis='produk'");
        //        $st->addFilter("jenis_locker='stock'");
        $st->addFilter("state='active'");

        $tmpStock = $st->lookupAll()->result();
        // showLast_query("lime");
        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];
        $gudangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['gudang_id'];

        $marking_style = array();
        if (isset($_GET['trID']) && ($_GET['trID'] > 0)) {
            $marking_style[$_GET['trID']] = "background-color:yellow;font-size:20px;";
        }


        //        arrPrint($tmpStock);

        $isMob = $mb->isMobile();
        if ($isMob) {
            $historyFields = isset($this->config->item("heTransaksi_ui")[$jenisTr]['compactHistoryFields']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['compactHistoryFields'] : array();
        }

        $backdate_f = formatTanggal(backDate(30), 'Y-m-d');

        $date1 = isset($_GET['date1']) ? $_GET['date1'] : $backdate_f;
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

        //region prepare ERP
        $stepLabels = array(//            "0" => "all"
        );
        $stepLinks = array(//            "0" => base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3)
        );
        if (sizeof($historyFields) > 1) {
            $subCodes = array();
            $stepCodes = array();
            $jmlStep = count($historyFields);

            foreach ($historyFields as $stepNumber => $stepSpec) {
                $subCodes[$stepNumber] = $stepNumber;
                $stepCodes[] = $stepNumber;
                $stepLabels[$stepNumber] = $stepSpec['label'];
                $stepLinks[$stepNumber] = base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $stepNumber . "/" . $this->uri->segment(3) . "?date1=$date1&date2=$date2";
            }
        }
        //endregion


        //region prepare data outstanding
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr1 = new MdlTransaksi();

        $tr->addFilter("jenis_master='582'");
        $tr->addFilter("div_id='" . $this->session->login['div_id'] . "'");
        $tr->addFilter("jenis='582so'");
        $tr->addFilter("transaksi.cabang_id<>'" . $this->session->login['cabang_id'] . "'");

        //        $tr->addFilter("id_master='14005'");
        $searchStr = isset($_GET['search']) ? $_GET['search'] : "";

        //region date filter
        //         $this->db->where("fulldate>='" . $date1 . "'");
        //         $this->db->where("fulldate<='" . $date2 . "'");
        //endregion


        $addParams = array();
        if (isset($_GET['addParams'])) {
            $addParams = unserialize(base64_decode($_GET['addParams']));
        }
        if ($addParams != null && sizeof($addParams) > 0) {
            //            arrprint($addParams);
            foreach ($addParams as $f) {
                $tr->addFilter($f);
            }
        }

        if (isset($_GET['search'])) {
            $tr->setKeyWord($searchStr);
        }
        else {
            // $this->db->where("fulldate>='" . $_GET['date1'] . "'");
            // $this->db->where("fulldate<='" . $_GET['date2'] . "'");
            //            $this->db->where("fulldate>='$date1'");
            //            $this->db->where("fulldate<='$date2'");
        }
        $jmlData = $tr->lookupDataCount();
        //        $limit=10;
        $page = (isset($_GET['page']) && $_GET['page'] > 0) ? ($_GET['page']) : 1;
        $offset = ($limit * ($page - 1));

        $addParams = array();
        if (isset($_GET['addParams'])) {
            $addParams = unserialize(base64_decode($_GET['addParams']));
        }
        if ($addParams != null && sizeof($addParams) > 0) {
            //            arrprint($addParams);
            foreach ($addParams as $f) {
                $tr->addFilter($f);
            }
        }


        if (isset($_GET['search'])) {
            $tr->setKeyWord($searchStr);
        }
        else {
            //            if (isset($_GET['date1'])) {
            //                $this->db->where("fulldate>='" . $_GET['date1'] . "'");
            //                $this->db->where("fulldate<='" . $_GET['date2'] . "'");
            //            }
            //            $this->db->where("fulldate>='" . $date1 . "'");
            //            $this->db->where("fulldate<='" . $date2 . "'");
        }

        $tr->addFilter("sub_step_number>0");
        $tr->addFilter("valid_qty>0");
        $tmpTr = $tr->lookupJoined()->result();
        // showLast_query("kuning");
        // arrPrint($tmpTr);
        //        $tmpTr = $tr->lookupRegistries_joined()->result();

        //        endregion


        //        matiHere("888");
        $extractedItems = array();//==untuk urusan update transaksi referer
        $validItems = array();
        $validItemSends = array();
        $mainData = array();
        $itemsVal = array();
        $itemData = array();
        $mainDataOutstandingItems = array();

        $kolom_2s = array(
            "cabang_id",
            "produk_id",
            "jumlah",
            "gudang_id",
        );

        $stocks = array();
        foreach ($tmpStock as $temps) {
            $tempDatas = array();
            foreach ($kolom_2s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = $temps->$kolom;
            }
            $stocks[$cabang_id][$produk_id] = $tempDatas;
        }

        $arrayAction = array();
        if (sizeof($tmpTr) > 0) {
            $listedMainID = array();
            foreach ($tmpTr as $row) {
                //                cekHitam("00");
                if (!isset($validItems[$row->produk_id])) {
                    $validItems[$row->id_master][$row->produk_id] = 0;
                }
                if (!isset($validItemSends[$row->produk_id])) {
                    $validItemSends[$row->id_master][$row->produk_id] = 0;
                }

                $validItems[$row->id_master][$row->produk_id] += $row->valid_qty;
                $validItemSends[$row->id_master][$row->produk_id] += $row->produk_ord_jml - $row->valid_qty;

                if (!isset($extractedItems[$row->produk_id])) {
                    $extractedItems[$row->id_master][$row->produk_id] = array();
                }

                $no = 0;
                $srcKey = isset($historyFields[$currentState]['srcKey']) ? $historyFields[$currentState]['srcKey'] : array();

                if (isset($historyFields[$currentState]['fields'])) {
                    foreach ($historyFields[$currentState]['fields'] as $srcKey_0 => $srcAlias) {
                        $no++;
                        $val_main = isset($row->$srcKey_0) ? makeValue($srcKey_0, json_decode(json_encode($row), true), json_decode(json_encode($row), true)) : "";
                        //                        $mainData[$row->$srcKey][$srcKey_0] = formatField($srcKey_0, $val_main);

                        if (is_array($srcAlias)) {
                            $hisStep = $srcAlias['step'];
                            $hisKey = $srcAlias['key'];
                            if (isset($row->ids_his)) {
                                if ($hisKey == "nomer") {
                                    $returnVal = showHistoriGlobalNumbers($row->ids_his, $hisStep, true);
                                    if ($returnVal == "") {
                                        $mainData[$row->$srcKey][$srcKey_0] = "-";
                                    }
                                    else {
                                        $mainData[$row->$srcKey][$srcKey_0] = $returnVal;
                                    }
                                }
                                else {
                                    $ids_his_decode = blobDecode($row->ids_his);
                                    if (isset($ids_his_decode[$hisStep][$hisKey])) {
                                        $mainData[$row->$srcKey][$srcKey_0] = $ids_his_decode[$hisStep][$hisKey];
                                    }
                                    else {
                                        $mainData[$row->$srcKey][$srcKey_0] = "-";
                                    }
                                }
                            }
                            else {
                                $mainData[$row->$srcKey][$srcKey_0] = "-";
                            }
                        }
                        else {
                            $mainData[$row->$srcKey][$srcKey_0] = formatField($srcKey_0, $val_main);
                        }
                    }

                    // mereplace manual aoutstanding items...
                    $mainDataOutstandingItems[$row->$srcKey][] = array(
                        "valid_qty"          => $row->valid_qty,
                        "label"              => $row->produk_label,
                        "kode"               => $row->produk_kode,
                        "produk_ord_hrg"     => $row->produk_ord_hrg,
                        "produk_ord_hrg_sum" => ($row->produk_ord_hrg * $row->valid_qty),
                    );
                    // $mainDataOutstandingNilaiItems[$row->$srcKey][] = array(
                    //     "valid_qty" => $row->valid_qty,
                    //     "label" => $row->produk_label,
                    //     "kode" => $row->produk_kode,
                    // );

                }

                $arrAsem[$row->produk_id][] = $row->produk_kode;

                if (sizeof($detailsFields) > 0) {
                    foreach ($detailsFields as $fieldKey => $key) {
                        $val = makeValue($key, json_decode(json_encode($row), true), json_decode(json_encode($row), true));
                        $itemData[$row->$srcKey][$fieldKey][] = formatField($fieldKey, $val);
                        //                        foreach ($tmpStock as $temps) {
                        //                            if($row->produk_id==$temps->produk_id && $temps->cabang_id==$cabangID){
                        //                                $stocks[$cabangID][$row->produk_id] = $temps->jumlah;
                        //                            }
                        //                        }
                        //                        $itemData[$row->$srcKey]['stok'][] = $row->produk_id;
                        $itemData[$row->$srcKey]['stok'][] = isset($stocks[$cabangID][$row->produk_id]['jumlah']) ? $stocks[$cabangID][$row->produk_id]['jumlah'] : 0;
                    }
                }

            }
            // ======================================================================
            // memairingkan qty outstanding dengan masterID masing-masing.
            //            arrPrint($mainData);
            if (sizeof($mainData) > 0) {
                if (sizeof($mainDataOutstandingItems) > 0) {
                    foreach ($mainDataOutstandingItems as $masterID => $spec) {
                        // cekBiru($spec);
                        if (array_key_exists($masterID, $mainData)) {
                            $hasil = "";
                            $nilai_hasil = "";
                            foreach ($spec as $dSpec) {
                                if ($hasil == "") {
                                    $hasil = $dSpec['valid_qty'] . "x " . $dSpec['label'] . " " . $dSpec['kode'];
                                }
                                else {
                                    $hasil .= "<br>" . $dSpec['valid_qty'] . "x " . $dSpec['label'] . " " . $dSpec['kode'];
                                }

                                $nilai_hasil += $dSpec['produk_ord_hrg_sum'];
                            }
                            //                            cekHere($hasil);
                            $mainData[$masterID]['outstanding_items'] = $hasil;
                            $mainData[$masterID]['outstanding_nilai_items'] = $nilai_hasil;
                        }
                    }
                }
            }
        }
        else {
            cekmerah("TIDAK ada yang mau diekstrak");
        }
        //endregion

        // arrPrint($mainData);

        //region header label
        $headerFieldLabel = array();
        if (isset($historyFields[$currentState]['fields'])) {
            foreach ($historyFields[$currentState]['fields'] as $srcKey => $alias) {
                if (is_array($alias)) {
                    $headerFieldLabel[$srcKey] = isset($alias['label']) ? $alias['label'] : "-";
                }
                else {
                    $headerFieldLabel[$srcKey] = $alias;
                }
            }
        }
        // arrPrint($headerFieldLabel);
        //endregion
        $addLink = null;
        $data = array(
            //            "mode" => $this->uri->segment(2),
            "mode"                 => "salesKonsolidate",
            "isMobile"             => $isMob,
            "jenisTr"              => $jenisTr,
            "trName"               => $this->config->item("heTransaksi_ui")[$jenisTr]["label"],
            "errMsg"               => $this->session->errMsg,
            "title"                => isset($subCodes) && isset($currentState) ? $subCodes[$currentState] : $this->jenisTrName,
            //            "subTitle" => "<b> Out Standing $menuLabel <r>( periode " . (isset($date1) && isset($date2) ? date("d-M-Y", strtotime($date1)) . " s/d " . date("d-M-Y", strtotime($date2)) : "HINGGA HARI INI") . " )</r></b> ",
            "subTitle"             => "<b> Out Standing $menuLabel",
            "arrayHistoryLabels"   => $headerFieldLabel,
            "arrayHistory"         => $mainData,
            "arrayAction"          => $arrayAction,
            "arrayHistorySumField" => array(),
            "detailsFields"        => $itemData,
            "arrayHistoryId"       => array(),
            "action"               => array(),
            "steps"                => $historyFields,
            "arrayValid"           => $arrayValid,
            "stepLabels"           => $stepLabels,
            "stepLinks"            => $stepLinks,
            "addParams"            => isset($_GET['addParams']) ? $_GET['addParams'] : null,
            "currentState"         => isset($currentState) ? $currentState : "all states",
            "alternateLink"        => base_url() . $this->uri->segment(1) . "/viewIncomplete/" . $this->uri->segment(3),
            "alternateLinkCaption" => "incomplete " . $this->config->item("heTransaksi_ui")[$jenisTr]["label"] . " <span class='glyphicon glyphicon-arrow-right'></span>",
            "addLink"              => $addLink,
            "filters"              => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage"             => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5),
            "marking_style"        => $marking_style,
        );

        $this->load->view("history", $data);
    }

    public function salesKonsolidate()
    {
        if (!isset($this->session->login['id'])) {
            gotoLogin();
            // redirect(base_url() . "Login");
        }

        $limit = 1000;
        //        $limit = 18;
        $maxPageNum = 20;
        //        $jenisTr = $this->uri->segment(3);
        $jenisTr = "582";
        $jenisTrsub = $this->uri->segment(4);
        $cCode = cCodeBuilderMisc($this->jenisTr);
        $currentState = strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : "transaksi";
        $configUiJenis = loadConfigModulJenis_he_misc($jenisTr, "coTransaksiUi");
        $selecetedCode = isset($configUiJenis['steps']['2']['target']) ? $configUiJenis['steps']['2']['target'] : "";
        $configUiLayout = loadConfigModulJenis_he_misc($jenisTr, "coTransaksiLayout");
        $historyFields = isset($configUiLayout['fixedFieldHoldConsolidate']) ? $configUiLayout['fixedFieldHoldConsolidate'] : array();
        $detailsFields = isset($configUiLayout['fixedFieldHoldConsolidate'][$currentState]['loop']) ? $configUiLayout['fixedFieldHoldConsolidate'][$currentState]['loop'] : array();
        $arrayValid = isset($configUiLayout['fixedFieldHoldConsolidate'][$currentState]['array_flip']) ? $configUiLayout['fixedFieldHoldConsolidate'][$currentState]['array_flip'] : array();
        $lockerStock = isset($configUiLayout['lockerStock']) ? $configUiLayout['lockerStock'] : "MdlLockerStock";

        $menuLabel = isset($configUiJenis['label']) ? $configUiJenis['label'] : "";

        if (isset($_SESSION[$cCode])) {
            $_SESSION[$cCode] = null;
            unset($_SESSION[$cCode]);
        }


        //            $stepNumber = isset($_SESSION[$cCode]['tableIn_master']['step_number']) ? $_SESSION[$cCode]['tableIn_master']['step_number'] : 1;
        //region session init
        if (!isset($_SESSION[$cCode])) {
            $_SESSION[$cCode] = array(
                "items" => array(),
                "main"  => array(),
            );
        }
        if (!isset($_SESSION[$cCode]['main'])) {
            $_SESSION[$cCode]['main'] = array();
        }
        if (!isset($_SESSION[$cCode]['items'])) {
            $_SESSION[$cCode]['items'] = array();
        }
        //endregion

        //       arrPrint($arrayFlip);
        $mb = New MobileDetect();
        $this->load->model("Mdls/$lockerStock");
        $st = new $lockerStock();
        //        $st->addFilter("jenis='produk'");
        //        $st->addFilter("jenis_locker='stock'");
        $st->addFilter("state='active'");

        $tmpStock = $st->lookupAll()->result();
        // showLast_query("lime");
        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];
        $gudangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['gudang_id'];

        $marking_style = array();
        if (isset($_GET['trID']) && ($_GET['trID'] > 0)) {
            $marking_style[$_GET['trID']] = "background-color:yellow;font-size:20px;";
        }


        //        arrPrint($tmpStock);

        $isMob = $mb->isMobile();
        if ($isMob) {
            $historyFields = isset($configUiJenis['compactHistoryFields']) ? $configUiJenis['compactHistoryFields'] : array();
        }

        $backdate_f = formatTanggal(backDate(30), 'Y-m-d');

        $date1 = isset($_GET['date1']) ? $_GET['date1'] : $backdate_f;
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

        //region prepare ERP
        $stepLabels = array(//            "0" => "all"
        );
        $stepLinks = array(//            "0" => base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3)
        );
        if (sizeof($historyFields) > 1) {
            $subCodes = array();
            $stepCodes = array();
            $jmlStep = count($historyFields);

            foreach ($historyFields as $stepNumber => $stepSpec) {
                $subCodes[$stepNumber] = $stepNumber;
                $stepCodes[] = $stepNumber;
                $stepLabels[$stepNumber] = $stepSpec['label'];
                $stepLinks[$stepNumber] = base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $stepNumber . "/" . $this->uri->segment(3) . "?date1=$date1&date2=$date2";
            }
        }
        //endregion


        //region prepare data outstanding
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr1 = new MdlTransaksi();

        // $tr->addFilter("jenis_master='582'");
        $tr->addFilter("div_id='" . $this->session->login['div_id'] . "'");
        // $tr->addFilter("jenis='582so'");
        $tr->addFilter("jenis in ('582so','382so','588so')");
        $tr->addFilter("transaksi.cabang_id<>'" . $this->session->login['cabang_id'] . "'");

        //        $tr->addFilter("id_master='14005'");
        $searchStr = isset($_GET['search']) ? $_GET['search'] : "";

        //region date filter
        //         $this->db->where("fulldate>='" . $date1 . "'");
        //         $this->db->where("fulldate<='" . $date2 . "'");
        //endregion


        $addParams = array();
        if (isset($_GET['addParams'])) {
            $addParams = unserialize(base64_decode($_GET['addParams']));
        }
        if ($addParams != null && sizeof($addParams) > 0) {
            //            arrprint($addParams);
            foreach ($addParams as $f) {
                $tr->addFilter($f);
            }
        }

        if (isset($_GET['search'])) {
            $tr->setKeyWord($searchStr);
        }
        else {
            // $this->db->where("fulldate>='" . $_GET['date1'] . "'");
            // $this->db->where("fulldate<='" . $_GET['date2'] . "'");
            //            $this->db->where("fulldate>='$date1'");
            //            $this->db->where("fulldate<='$date2'");
        }
        $jmlData = $tr->lookupDataCount();
        // showLast_query("biru");
        //        $limit=10;
        $page = (isset($_GET['page']) && $_GET['page'] > 0) ? ($_GET['page']) : 1;
        $offset = ($limit * ($page - 1));

        $addParams = array();
        if (isset($_GET['addParams'])) {
            $addParams = unserialize(base64_decode($_GET['addParams']));
        }
        if ($addParams != null && sizeof($addParams) > 0) {
            //            arrprint($addParams);
            foreach ($addParams as $f) {
                $tr->addFilter($f);
            }
        }


        if (isset($_GET['search'])) {
            $tr->setKeyWord($searchStr);
        }
        else {
            //            if (isset($_GET['date1'])) {
            //                $this->db->where("fulldate>='" . $_GET['date1'] . "'");
            //                $this->db->where("fulldate<='" . $_GET['date2'] . "'");
            //            }
            //            $this->db->where("fulldate>='" . $date1 . "'");
            //            $this->db->where("fulldate<='" . $date2 . "'");
        }

        $tr->addFilterJoin("sub_step_number>0");
        $tr->addFilterJoin("valid_qty>0");
        $tmpTr = $tr->lookupJoined();
        // showLast_query("merah");
        // arrPrint($tmpTr);
        //        $tmpTr = $tr->lookupRegistries_joined()->result();

        //        endregion

        // matiHere(__LINE__);

        $extractedItems = array();//==untuk urusan update transaksi referer
        $validItems = array();
        $validItemSends = array();
        $mainData = array();
        $itemsVal = array();
        $itemData = array();
        $mainDataOutstandingItems = array();

        $kolom_2s = array(
            "cabang_id",
            "produk_id",
            "jumlah",
            "gudang_id",
        );

        $stocks = array();
        foreach ($tmpStock as $temps) {
            $tempDatas = array();
            foreach ($kolom_2s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = $temps->$kolom;
            }
            $stocks[$cabang_id][$produk_id] = $tempDatas;
        }

        $arrayAction = array();
        if (sizeof($tmpTr) > 0) {
            //            arrprint($historyFields[$currentState]);
            if (isset($historyFields[$currentState]['items'])) {
                // cekBiru(__LINE__);
                $arrmasterId = array();
                foreach ($tmpTr as $row) {
                    $ind_reg = $row->indexing_registry != NULL ? blobDecode($row->indexing_registry) : array();
                    $arrayItemsReg[$row->id_master] = isset($ind_reg['items']) ? $ind_reg['items'] : 0;
                    $arrayItemsMasterToID[$row->id_master] = $row->transaksi_id;

                    $arrmasterId[$row->id_master] = $row->id_master;
                }
                // arrPrintPink($arrayItemsReg);
                // membaca registry items
                $tr->setFilters(array());
                $fields = array("items");
                $tr->setJointSelectFields(implode(",", $fields) . ", transaksi_id");
                // $tr->addFilter("id in ('" . implode("','", $arrayItemsReg) . "')");
                // $tmpReg = $tr->lookupRegistries()->result();
                //                $tr->addFilter("transaksi_id in ('" . implode("','", $arrayItemsReg) . "')");
                $tr->addFilter("transaksi_id in ('" . implode("','", $arrmasterId) . "')");
                $tmpReg = $tr->lookupDataRegistries()->result();

                //                                showLast_query("kuning");
                //                                arrPrint($tmpReg);
                //                                mati_disini();
                $arrayItemsRegPair = array();
                foreach ($tmpReg as $rSpec) {
                    //                    cekPink2($rSpec->transaksi_id);
                    $arrayItemsRegPair[$rSpec->transaksi_id] = blobDecode($rSpec->items);
                }
                foreach ($arrayItemsRegPair as $trID => $tmp) {
                    foreach ($tmp as $pID => $etmp) {
                        if (isset($historyFields[$currentState]['items']['outstanding_items'])) {
                            foreach ($historyFields[$currentState]['items']['outstanding_items'] as $val) {
                                $arrayItemsOutstandingPair[$trID][$pID] = $etmp[$val];
                            }
                        }
                    }
                }
            }

            //                        arrPrintWebs($arrayItemsMasterToID);
            //                        arrPrintPink($arrayItemsOutstandingPair);
            // arrPrintWebs($tmpTr);
            $modul_path = base_url()."penjualan/";
            $arrMasterSeller = array();
            foreach ($tmpTr as $row) {
                // arrPrintPink($row->seller_nama);

                $arrMasterSeller[$row->id_master] = $row->seller_nama;

                if (!isset($validItems[$row->produk_id])) {
                    $validItems[$row->id_master][$row->produk_id] = 0;
                }
                if (!isset($validItemSends[$row->produk_id])) {
                    $validItemSends[$row->id_master][$row->produk_id] = 0;
                }

                $validItems[$row->id_master][$row->produk_id] += $row->valid_qty;
                $validItemSends[$row->id_master][$row->produk_id] += $row->produk_ord_jml - $row->valid_qty;

                if (!isset($extractedItems[$row->produk_id])) {
                    $extractedItems[$row->id_master][$row->produk_id] = array();
                }

                $no = 0;
                $srcKey = isset($historyFields[$currentState]['srcKey']) ? $historyFields[$currentState]['srcKey'] : array();

                if (isset($historyFields[$currentState]['fields'])) {
                    foreach ($historyFields[$currentState]['fields'] as $srcKey_0 => $srcAlias) {
                        $no++;
                        $val_main = isset($row->$srcKey_0) ? makeValue($srcKey_0, json_decode(json_encode($row), true), json_decode(json_encode($row), true)) : "";
                        //                        $mainData[$row->$srcKey][$srcKey_0] = formatField($srcKey_0, $val_main);

                        if (is_array($srcAlias)) {
                            $hisStep = $srcAlias['step'];
                            $hisKey = $srcAlias['key'];
                            if (isset($row->ids_his)) {
                                if ($hisKey == "nomer") {
                                    $returnVal = showHistoriGlobalNumbers($row->ids_his, $hisStep, true);
                                    if ($returnVal == "") {
                                        $mainData[$row->$srcKey][$srcKey_0] = "-";
                                    }
                                    else {
                                        $mainData[$row->$srcKey][$srcKey_0] = $returnVal;
                                    }
                                }
                                else {
                                    $ids_his_decode = blobDecode($row->ids_his);
                                    if (isset($ids_his_decode[$hisStep][$hisKey])) {
                                        $mainData[$row->$srcKey][$srcKey_0] = $ids_his_decode[$hisStep][$hisKey];
                                    }
                                    else {
                                        $mainData[$row->$srcKey][$srcKey_0] = "-";
                                    }
                                }
                            }
                            else {
                                $mainData[$row->$srcKey][$srcKey_0] = "-";
                            }
                        }
                        else {
                            $mainData[$row->$srcKey][$srcKey_0] = formatField_he_format($srcKey_0, $val_main, $jenisTr, $modul_path);
                        }
                    }

                    // mereplace manual aoutstanding items...
                    $mainDataOutstandingItems[$row->$srcKey][] = array(
                        "valid_qty" => $row->valid_qty,
                        "label"     => $row->produk_label,
                        "kode"      => $row->produk_kode,
                        "produk_id" => $row->produk_id,
                    );

                }

                $arrAsem[$row->produk_id][] = $row->produk_kode;

                if (sizeof($detailsFields) > 0) {
                    foreach ($detailsFields as $fieldKey => $key) {
                        $val = makeValue($key, json_decode(json_encode($row), true), json_decode(json_encode($row), true));
                        // cekMerah("$fieldKey, $val ::" . formatField_he_format($fieldKey, $val, $jenisTr, $modul_path));
                        // $itemData[$row->$srcKey][$fieldKey][] = formatField_he_format($fieldKey, $val, $jenisTr, $modul_path);
                        $itemData[$row->$srcKey][$fieldKey][] = is_numeric($val) ? $val : formatField_he_format($fieldKey, $val, $jenisTr, $modul_path);
                        // $itemData[$row->$srcKey][$fieldKey][] = $val;
                        //                        foreach ($tmpStock as $temps) {
                        //                            if($row->produk_id==$temps->produk_id && $temps->cabang_id==$cabangID){
                        //                                $stocks[$cabangID][$row->produk_id] = $temps->jumlah;
                        //                            }
                        //                        }
                        //                        $itemData[$row->$srcKey]['stok'][] = $row->produk_id;
                        $itemData[$row->$srcKey]['stok'][] = isset($stocks[$cabangID][$row->produk_id]['jumlah']) ? $stocks[$cabangID][$row->produk_id]['jumlah'] : 0;
                    }
                }
                // $itemData[$row->$srcKey][$fieldKey][] = $row->seller_nama;
                $itemData[$row->$srcKey]["seller"][] = $row->seller_nama;
            }
                       // arrPrintWebs($itemData);
            //            arrPrintWebs($arrayItemsOutstandingPair);
            // ======================================================================
            // memairingkan qty outstanding dengan masterID masing-masing.
            $summary = array();
            if (sizeof($mainData) > 0) {
                if (sizeof($mainDataOutstandingItems) > 0) {
                    foreach ($mainDataOutstandingItems as $masterID => $spec) {


                        if (array_key_exists($masterID, $mainData)) {
                            $hasil = "";
                            $sub_tmpOutstandingPair = 0;
                            foreach ($spec as $dSpec) {



                                $addSpec = "";
                                if (isset($arrayItemsMasterToID[$masterID])) {
                                    $pairTrID = $arrayItemsMasterToID[$masterID];
                                    $tmpOutstandingPair = isset($arrayItemsOutstandingPair[$masterID][$dSpec['produk_id']]) ? $arrayItemsOutstandingPair[$masterID][$dSpec['produk_id']] : "0";
                                    //                                    $tmpOutstandingPair = isset($arrayItemsOutstandingPair[$pairTrID][$dSpec['produk_id']]) ? $arrayItemsOutstandingPair[$pairTrID][$dSpec['produk_id']] : "0";
                                    $sub_tmpOutstandingPair += ($tmpOutstandingPair * $dSpec['valid_qty']);

                                    $price = number_format($tmpOutstandingPair);
                                    $sum_price = number_format($tmpOutstandingPair * $dSpec['valid_qty']);
                                    $addSpec = "<span style='width:200px;' class='pull-right text-right'>@ $price  |  $sum_price</span>";
                                }

                                if ($hasil == "") {
                                    $hasil = $dSpec['valid_qty'] . "x " . $dSpec['label'] . " " . $dSpec['kode'] . $addSpec;
                                }
                                else {
                                    $hasil .= "<br>" . $dSpec['valid_qty'] . "x " . $dSpec['label'] . " " . $dSpec['kode'] . $addSpec;
                                }


                            }

                            if(!isset($arrSummSeller[$arrMasterSeller[$masterID]])){
                                $arrSummSeller[$arrMasterSeller[$masterID]] = 0;
                            }
                            $arrSummSeller[$arrMasterSeller[$masterID]] += $sub_tmpOutstandingPair;

                            //                            cekHere($hasil);
                            $mainData[$masterID]['outstanding_items'] = $hasil;
                            $mainData[$masterID]['sub_outstanding_items'] = formatField("debet", $sub_tmpOutstandingPair);
                            if (!isset($summary['sub_outstanding_items'])) {
                                $summary['sub_outstanding_items'] = 0;
                            }
                            $summary['sub_outstanding_items'] += $sub_tmpOutstandingPair;


                        }
                    }
                }
            }
        }
        else {
            cekmerah("TIDAK ada yang mau diekstrak");
        }
        //endregion
        // cekBiru($arrSummSeller);
        // arrPrint($mainData);
//        cekmerah($configUiJenis["label"]);
// matiHere(__LINE__);
        //region header label
        $headerFieldLabel = array();
        if (isset($historyFields[$currentState]['fields'])) {
            foreach ($historyFields[$currentState]['fields'] as $srcKey => $alias) {
                if (is_array($alias)) {
                    $headerFieldLabel[$srcKey] = isset($alias['label']) ? $alias['label'] : "-";
                }
                else {
                    $headerFieldLabel[$srcKey] = $alias;
                }
            }
        }
        // arrPrint($headerFieldLabel);
        //endregion
        $url_target = base_url();
        $loaders = array(
            // "auto_satu" => $url_target . "Tool/generateNonAkuntingAllSales",
            // "auto_dua" => $url_target . "Tool/generateNonAkuntingAllBatal",
            // "auto_tiga" => $url_target . "Tool/generateNonAkuntingAllSalesExport",
            // "auto_empat" => $url_target . "Tool/generateNonAkuntingAllSalesReject",
        );
        $addLink = null;
        $data = array(
            //            "mode" => $this->uri->segment(2),
            "mode"                 => "salesKonsolidate",
            "isMobile"             => $isMob,
            "jenisTr"              => $jenisTr,
            "trName"               => $configUiJenis["label"],
            "errMsg"               => $this->session->errMsg,
            "title"                => isset($subCodes) && isset($currentState) ? $subCodes[$currentState] : $this->jenisTrName,
            //            "subTitle" => "<b> Out Standing $menuLabel <r>( periode " . (isset($date1) && isset($date2) ? date("d-M-Y", strtotime($date1)) . " s/d " . date("d-M-Y", strtotime($date2)) : "HINGGA HARI INI") . " )</r></b> ",
//            "subTitle"             => "<b> Out Standing $menuLabel <small>dikeluarkan dari outstanding  saat prepacking</small>",
            "subTitle"             => "<b> Out Standing $menuLabel <small>(versi pre-packing list)</small>",
            "arrayHistoryLabels"   => $headerFieldLabel,
            "arrayHistory"         => $mainData,
            "arrayAction"          => $arrayAction,
            "arrayHistorySumField" => array(),
            "detailsFields"        => $itemData,
            "arrayHistoryId"       => array(),
            "action"               => array(),
            "steps"                => $historyFields,
            "arrayValid"           => $arrayValid,
            "stepLabels"           => $stepLabels,
            "stepLinks"            => $stepLinks,
            "addParams"            => isset($_GET['addParams']) ? $_GET['addParams'] : null,
            "currentState"         => isset($currentState) ? $currentState : "all states",
            "alternateLink"        => base_url() . $this->uri->segment(1) . "/viewIncomplete/" . $this->uri->segment(3),
            "alternateLinkCaption" => "incomplete " . $configUiJenis["label"] . " <span class='glyphicon glyphicon-arrow-right'></span>",
            "addLink"              => $addLink,
            "filters"              => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage"             => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5),
            "marking_style"        => $marking_style,
            "summary"              => $summary,
            "loader"              => $loaders,
        );

        $this->load->view("history", $data);
    }

}
